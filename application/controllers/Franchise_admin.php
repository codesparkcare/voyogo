<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise_admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('Franchise_model');
    }

    private function _check_auth() {
        if (!$this->session->userdata('franchise_admin_logged_in')) {
            redirect('franchise-admin/login');
            exit;
        }
    }

    // =========================================================================
    // 1. AUTHENTICATION
    // =========================================================================
    public function login() {
        if ($this->session->userdata('franchise_admin_logged_in')) {
            redirect('franchise-admin');
            return;
        }

        $data['error'] = '';
        if ($this->input->method() === 'post') {
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            $admin = $this->Franchise_model->admin_login($username, $password);
            if ($admin) {
                $this->session->set_userdata(array(
                    'franchise_admin_logged_in' => true,
                    'franchise_admin_id'        => $admin['id'],
                    'franchise_admin_username'  => $admin['username'],
                    'franchise_admin_name'      => $admin['name']
                ));
                redirect('franchise-admin');
                return;
            } else {
                $data['error'] = 'Invalid username or password.';
            }
        }

        $this->load->view('franchise_admin/login', $data);
    }

    public function logout() {
        $this->session->unset_userdata(array(
            'franchise_admin_logged_in',
            'franchise_admin_id',
            'franchise_admin_username',
            'franchise_admin_name'
        ));
        $this->session->set_flashdata('success', 'Logged out successfully.');
        redirect('franchise-admin/login');
    }

    // =========================================================================
    // 2. DASHBOARD
    // =========================================================================
    public function index() {
        $this->_check_auth();

        $data['title']        = 'Franchise Admin Dashboard';
        $data['active_menu']  = 'dashboard';
        $data['stats']        = $this->Franchise_model->get_admin_dashboard_stats();
        $data['recent_stores']= $this->Franchise_model->get_all_stores();
        $data['recent_tx']    = $this->Franchise_model->get_wallet_ledger(null, 10);
        $data['admin_name']   = $this->session->userdata('franchise_admin_name');

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/dashboard', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    // =========================================================================
    // 3. STORE OWNERS MANAGEMENT
    // =========================================================================
    public function stores() {
        $this->_check_auth();

        $search = $this->input->get('q');
        $data['title']       = 'Manage Franchise Stores';
        $data['active_menu'] = 'stores';
        $data['stores']      = $this->Franchise_model->get_all_stores($search);
        $data['search']      = $search;

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/stores', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    public function store_create() {
        $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise-admin/stores');
            return;
        }

        $agent_code = trim($this->input->post('agent_code'));
        $username   = trim($this->input->post('username'));
        $password   = trim($this->input->post('password'));
        $store_name = trim($this->input->post('store_name'));
        $phone      = trim($this->input->post('phone'));
        $email      = trim($this->input->post('email'));
        $address    = trim($this->input->post('address'));
        $gst_number = trim($this->input->post('gst_number'));
        $init_wallet= (float)$this->input->post('initial_wallet');

        if (empty($agent_code) || empty($username) || empty($password) || empty($store_name) || empty($phone) || empty($email)) {
            $this->session->set_flashdata('error', 'Please fill all required fields.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->Franchise_model->check_agent_code_exists($agent_code)) {
            $this->session->set_flashdata('error', 'Agent Code already exists. Please choose a unique code.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->Franchise_model->check_username_exists($username)) {
            $this->session->set_flashdata('error', 'Username already taken. Please choose another username.');
            redirect('franchise-admin/stores');
            return;
        }

        $store_id = $this->Franchise_model->create_store(array(
            'agent_code'     => $agent_code,
            'username'       => $username,
            'password'       => password_hash($password, PASSWORD_DEFAULT),
            'store_name'     => $store_name,
            'phone'          => $phone,
            'email'          => $email,
            'address'        => $address,
            'gst_number'     => $gst_number,
            'wallet_balance' => 0.00,
            'status'         => 'active'
        ));

        if ($store_id && $init_wallet > 0) {
            $this->Franchise_model->update_wallet_balance(
                $store_id,
                'credit',
                $init_wallet,
                'admin_initial_setup',
                null,
                'Initial Wallet Top-up on Store Creation',
                $this->session->userdata('franchise_admin_username')
            );
        }

        $this->session->set_flashdata('success', 'Franchise Store Owner "' . htmlspecialchars($store_name) . '" created successfully!');
        redirect('franchise-admin/stores');
    }

    public function store_edit($id) {
        $this->_check_auth();

        $store = $this->Franchise_model->get_store_by_id($id);
        if (!$store) {
            $this->session->set_flashdata('error', 'Store not found.');
            redirect('franchise-admin/stores');
            return;
        }

        if ($this->input->method() === 'post') {
            $store_name = trim($this->input->post('store_name'));
            $phone      = trim($this->input->post('phone'));
            $email      = trim($this->input->post('email'));
            $address    = trim($this->input->post('address'));
            $gst_number = trim($this->input->post('gst_number'));
            $new_pass   = trim($this->input->post('password'));

            $update_data = array(
                'store_name' => $store_name,
                'phone'      => $phone,
                'email'      => $email,
                'address'    => $address,
                'gst_number' => $gst_number
            );

            if (!empty($new_pass)) {
                $update_data['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
            }

            $this->Franchise_model->update_store($id, $update_data);
            $this->session->set_flashdata('success', 'Store details updated successfully!');
            redirect('franchise-admin/stores');
            return;
        }

        redirect('franchise-admin/stores');
    }

    public function store_toggle($id) {
        $this->_check_auth();
        $new_status = $this->Franchise_model->toggle_store_status($id);

        if ($new_status) {
            $this->session->set_flashdata('success', 'Store status updated to ' . strtoupper($new_status) . ' successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update store status.');
        }
        redirect('franchise-admin/stores');
    }

    // =========================================================================
    // 4. WALLET MANAGEMENT & MANUAL TOP-UP
    // =========================================================================
    public function wallets() {
        $this->_check_auth();

        $data['title']       = 'Wallet Management';
        $data['active_menu'] = 'wallets';
        $data['stores']      = $this->Franchise_model->get_all_stores();
        $data['ledger']      = $this->Franchise_model->get_wallet_ledger(null, 100);

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/wallets', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    public function wallet_update() {
        $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise-admin/wallets');
            return;
        }

        $store_id = (int)$this->input->post('store_id');
        $type     = trim($this->input->post('transaction_type')); // 'credit' or 'debit'
        $amount   = (float)$this->input->post('amount');
        $remarks  = trim($this->input->post('remarks'));

        if ($amount <= 0) {
            $this->session->set_flashdata('error', 'Amount must be greater than zero.');
            redirect('franchise-admin/wallets');
            return;
        }

        $res = $this->Franchise_model->update_wallet_balance(
            $store_id,
            $type,
            $amount,
            'admin_manual',
            null,
            $remarks ?: ('Manual ' . ucfirst($type) . ' by Admin'),
            $this->session->userdata('franchise_admin_username')
        );

        if ($res['status']) {
            $this->session->set_flashdata('success', 'Wallet ' . ucfirst($type) . ' of ₹ ' . number_format($amount, 2) . ' applied successfully! New Balance: ₹ ' . number_format($res['new_balance'], 2));
        } else {
            $this->session->set_flashdata('error', 'Transaction Failed: ' . ($res['error'] ?? 'Unknown error'));
        }

        redirect('franchise-admin/wallets');
    }

    // =========================================================================
    // 5. MASTER BOOKINGS HISTORY
    // =========================================================================
    public function bookings() {
        $this->_check_auth();

        $data['title']           = 'Master Booking History';
        $data['active_menu']     = 'bookings';
        $data['flight_bookings'] = $this->Franchise_model->get_all_flight_bookings(100);
        $data['hotel_bookings']  = $this->Franchise_model->get_all_hotel_bookings(100);

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/bookings', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    // =========================================================================
    // 6. GROUP TOUR FOLLOW UP
    // =========================================================================
    public function group_tour_follow_up() {
        $this->_check_auth();

        $month = $this->input->get('month') ?: 'all';
        $search = $this->input->get('search');

        $data['title']        = 'Group Tour Follow Up';
        $data['active_menu']  = 'group_tours';
        $data['selected_month'] = $month;
        $data['search_query'] = $search;
        $data['tours']        = $this->Franchise_model->get_group_tours($month, $search);
        $data['months']       = $this->Franchise_model->get_group_tour_months();
        $data['stats']        = $this->Franchise_model->get_group_tours_stats();

        $this->load->view('franchise_admin/layout/header', $data);
        $this->load->view('franchise_admin/group_tours', $data);
        $this->load->view('franchise_admin/layout/footer');
    }

    public function group_tour_import() {
        $this->_check_auth();

        // 1. Check if JSON data posted via SheetJS client importer
        $raw_json = $this->input->raw_input_stream;
        $payload = json_decode($raw_json, true);

        if (!$payload && $this->input->post('rows_json')) {
            $payload = array(
                'rows'    => json_decode($this->input->post('rows_json'), true),
                'replace' => $this->input->post('replace') === '1'
            );
        }

        if (!empty($payload['rows']) && is_array($payload['rows'])) {
            $replace = !empty($payload['replace']);
            $count = $this->Franchise_model->insert_group_tours_batch($payload['rows'], $replace);

            header('Content-Type: application/json');
            echo json_encode(array(
                'status'  => true,
                'count'   => $count,
                'message' => "Successfully imported {$count} group tour records into database!"
            ));
            return;
        }

        // 2. Fallback: Standard CSV file upload
        if (!empty($_FILES['excel_file']['tmp_name'])) {
            $replace = $this->input->post('replace') === '1';
            $file = $_FILES['excel_file']['tmp_name'];
            $handle = fopen($file, 'r');
            $rows = array();
            $headers = array();
            $active_month = 'SEPTEMBER:2026';

            if ($handle !== FALSE) {
                while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                    if (empty($headers)) {
                        // Check if first row is headers
                        if (stripos(implode(' ', $data), 'TOUR') !== false || stripos(implode(' ', $data), 'DATE') !== false) {
                            $headers = array_map('trim', $data);
                            continue;
                        }
                    }

                    // Check for month section header row
                    $first_cell = trim($data[0] ?? '');
                    if (preg_match('/(JANUARY|FEBRUARY|MARCH|APRIL|MAY|JUNE|JULY|AUGUST|SEPTEMBER|OCTOBER|NOVEMBER|DECEMBER)\s*:\s*\d{4}/i', $first_cell, $matches)) {
                        $active_month = strtoupper($matches[0]);
                        continue;
                    }

                    if (!empty($data[1]) || !empty($data[2]) || !empty($data[3])) {
                        $rows[] = array(
                            's_no'            => $data[0] ?? '',
                            'tour_date'       => $data[1] ?? '',
                            'tour_code'       => $data[2] ?? '',
                            'country_name'    => $data[3] ?? '',
                            'tour_amount'     => $data[4] ?? '',
                            'confirmed'       => $data[5] ?? '',
                            'grp_tkn'         => $data[6] ?? '',
                            'need'            => $data[7] ?? '',
                            'reminder_45'     => $data[8] ?? '',
                            'reminder_30'     => $data[9] ?? '',
                            'ticket_status'   => $data[10] ?? '',
                            'visa_status'     => $data[11] ?? '',
                            'handling_person' => $data[12] ?? '',
                            'travel_bag'      => $data[13] ?? '',
                            'tour_manager'    => $data[14] ?? '',
                            'month_group'     => $active_month
                        );
                    }
                }
                fclose($handle);

                $count = $this->Franchise_model->insert_group_tours_batch($rows, $replace);
                $this->session->set_flashdata('success', "Imported {$count} records successfully!");
                redirect('franchise-admin/group-tour-follow-up');
                return;
            }
        }

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(array('status' => false, 'error' => 'No valid tour rows found in import file.'));
            return;
        }

        $this->session->set_flashdata('error', 'Please select a valid Excel or CSV file to import.');
        redirect('franchise-admin/group-tour-follow-up');
    }

    public function group_tour_save_row() {
        $this->_check_auth();

        $id = $this->input->post('id') ? (int)$this->input->post('id') : null;
        $data = array(
            's_no'            => trim($this->input->post('s_no')),
            'tour_date'       => trim($this->input->post('tour_date')),
            'tour_code'       => trim($this->input->post('tour_code')),
            'country_name'    => trim($this->input->post('country_name')),
            'tour_amount'     => trim($this->input->post('tour_amount')),
            'confirmed'       => trim($this->input->post('confirmed')),
            'grp_tkn'         => trim($this->input->post('grp_tkn')),
            'need'            => trim($this->input->post('need')),
            'reminder_45'     => trim($this->input->post('reminder_45')),
            'reminder_30'     => trim($this->input->post('reminder_30')),
            'ticket_status'   => trim($this->input->post('ticket_status')),
            'visa_status'     => trim($this->input->post('visa_status')),
            'handling_person' => trim($this->input->post('handling_person')),
            'travel_bag'      => trim($this->input->post('travel_bag')),
            'tour_manager'    => trim($this->input->post('tour_manager')),
            'month_group'     => trim($this->input->post('month_group')) ?: 'SEPTEMBER:2026',
            'notes'           => trim($this->input->post('notes'))
        );

        $saved_id = $this->Franchise_model->save_group_tour($data, $id);

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'id' => $saved_id, 'message' => 'Tour row saved successfully!'));
            return;
        }

        $this->session->set_flashdata('success', 'Tour record saved successfully.');
        redirect('franchise-admin/group-tour-follow-up');
    }

    public function group_tour_delete_row($id) {
        $this->_check_auth();
        $this->Franchise_model->delete_group_tour($id);

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(array('status' => true, 'message' => 'Row deleted successfully.'));
            return;
        }

        $this->session->set_flashdata('success', 'Tour record deleted.');
        redirect('franchise-admin/group-tour-follow-up');
    }

    public function group_tour_clear_all() {
        $this->_check_auth();
        $this->Franchise_model->clear_all_group_tours();
        $this->session->set_flashdata('success', 'All group tour records cleared. You can upload a new Excel file anytime.');
        redirect('franchise-admin/group-tour-follow-up');
    }

    public function group_tour_export_template() {
        $this->_check_auth();

        $filename = 'Group_Tour_Follow_Up_Template.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        // Exact 15 headers matching workbook1.pdf
        fputcsv($output, array(
            'S. NO',
            'DATE',
            'TOUR CODE',
            'COUNTRY NAME',
            'TOUR AMOUNT',
            'CONFIRMED',
            'GRP. TKN',
            'NEED',
            '45 DAYS REMINDER',
            '30 DAYS REMINDER',
            'TICKET STATUS',
            'VISA STATUS',
            'HANDLING PERSON',
            'TRAVEL BAG',
            'TOUR MANAGER',
            'MONTH'
        ));

        // Sample rows from PDF
        fputcsv($output, array('1', '16.09.2026', 'NBO160926', 'KENYA (5N/6D)', '259,000', '21+1+1+2+2', '22', '-', '02.08.2026', '17.08.2026', 'GROUP TAKEN', 'WE APPLY (INCLUDE)', 'KEERTHANA DEVI', '-', 'YOGESH', 'SEPTEMBER:2026'));
        fputcsv($output, array('2', '17.09.2026', 'NRT170926', 'JAPAN (7N/8D)', '259,000', '35+1', '36', '-', '03.08.2026', '18.08.2026', 'GROUP TAKEN', 'WE APPLY (INCLUDE)', 'NAVEENA', '-', 'SARATHKUMAR', 'SEPTEMBER:2026'));
        fputcsv($output, array('3', '20.09.2025', 'CDG200926', 'AMAZING EUROPE (9N/10)', '274,000', '31+1+1+2', '32', '-', '06.08.2026', '21.08.2026', 'GROUP TAKEN', 'WE APPLY (INCLUDE)', 'MUKESH', '-', 'RAJA MITHUN', 'SEPTEMBER:2026'));
        fclose($output);
        exit;
    }
}
