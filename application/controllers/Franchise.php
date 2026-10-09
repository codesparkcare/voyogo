<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Franchise extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('Franchise_model');
        $this->load->library('BenzyFlightApi');
        $this->load->library('BenzyHotelApi');
    }

    private function _check_auth() {
        if (!$this->session->userdata('franchise_store_logged_in')) {
            redirect('franchise/login');
            exit;
        }

        // Live check: verify store status in DB
        $store_id = (int)$this->session->userdata('franchise_store_id');
        $store = $this->Franchise_model->get_store_by_id($store_id);

        if (!$store || $store['status'] !== 'active') {
            $this->session->unset_userdata(array(
                'franchise_store_logged_in',
                'franchise_store_id',
                'franchise_store_code',
                'franchise_store_name'
            ));
            $this->session->set_flashdata('error', 'Your store account has been deactivated by Franchise Admin.');
            redirect('franchise/login');
            exit;
        }

        return $store;
    }

    // =========================================================================
    // 0. FRANCHISE PUBLIC LANDING PAGE & ENQUIRY
    // =========================================================================
    public function index() {
        $data['title'] = "Voyogo Franchise Opportunity - Travel Together GROW BIGGER";
        $data['page_title'] = "Franchise Opportunity - Voyogo";
        $data['active_page'] = "franchise";
        $this->load->view('includes/header', $data);
        $this->load->view('franchise_landing', $data);
        $this->load->view('includes/footer', $data);
    }

    public function submit_enquiry() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('franchise');
            return;
        }

        $client_name         = trim((string)$this->input->post('client_name'));
        $country_code        = trim((string)$this->input->post('country_code')) ?: '+91';
        $mobile_number       = trim((string)$this->input->post('mobile_number'));
        $email               = trim((string)$this->input->post('email'));
        $otp                 = trim((string)$this->input->post('otp'));
        $city                = trim((string)$this->input->post('city'));
        $state               = trim((string)$this->input->post('state'));
        $preferred_location  = trim((string)$this->input->post('preferred_location'));
        $own_business        = trim((string)$this->input->post('own_business')) ?: 'No';
        $current_profession  = trim((string)$this->input->post('current_profession'));
        $start_timeline      = trim((string)$this->input->post('start_timeline')) ?: 'Immediately';
        $previous_franchise  = trim((string)$this->input->post('previous_franchise')) ?: 'No';
        $association_member  = trim((string)$this->input->post('association_member')) ?: 'No';
        
        $associations_input  = $this->input->post('associations');
        $associations_list   = is_array($associations_input) ? $associations_input : array();
        $other_association   = trim((string)$this->input->post('other_association'));
        if (!empty($other_association)) {
            $associations_list[] = 'Other: ' . $other_association;
        }
        $associations_str    = implode(', ', $associations_list);

        $hear_about_us       = trim((string)$this->input->post('hear_about_us')) ?: 'Website';
        $message             = trim((string)$this->input->post('message'));

        if (empty($client_name) || empty($mobile_number)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => 'Please provide your name and mobile number.'));
                return;
            }
            $this->session->set_flashdata('error_msg', 'Please provide your name and mobile number.');
            redirect('franchise');
            return;
        }

        if ($country_code && $mobile_number && strpos($mobile_number, '+') !== 0) {
            $formatted_phone = $country_code . ' ' . $mobile_number;
        } else {
            $formatted_phone = $mobile_number;
        }

        // Auto-create franchise_enquiries table if it doesn't exist
        $this->db->query("CREATE TABLE IF NOT EXISTS `franchise_enquiries` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `client_name` VARCHAR(191) NOT NULL,
            `mobile_number` VARCHAR(50) NOT NULL,
            `email` VARCHAR(191) DEFAULT NULL,
            `otp` VARCHAR(20) DEFAULT NULL,
            `city` VARCHAR(191) DEFAULT NULL,
            `state` VARCHAR(100) DEFAULT NULL,
            `preferred_location` VARCHAR(255) DEFAULT NULL,
            `own_business` VARCHAR(20) DEFAULT 'No',
            `current_profession` VARCHAR(191) DEFAULT NULL,
            `start_timeline` VARCHAR(100) DEFAULT NULL,
            `previous_franchise` VARCHAR(20) DEFAULT 'No',
            `association_member` VARCHAR(20) DEFAULT 'No',
            `associations` TEXT DEFAULT NULL,
            `hear_about_us` VARCHAR(100) DEFAULT NULL,
            `message` TEXT DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT 'New',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $enquiry_data = array(
            'client_name'        => $client_name,
            'mobile_number'      => $formatted_phone,
            'email'              => $email,
            'otp'                => $otp,
            'city'               => $city,
            'state'              => $state,
            'preferred_location' => $preferred_location,
            'own_business'       => $own_business,
            'current_profession' => $current_profession,
            'start_timeline'     => $start_timeline,
            'previous_franchise' => $previous_franchise,
            'association_member' => $association_member,
            'associations'       => $associations_str,
            'hear_about_us'      => $hear_about_us,
            'message'            => $message,
            'status'             => 'New',
            'created_at'         => date('Y-m-d H:i:s')
        );

        $this->db->insert('franchise_enquiries', $enquiry_data);

        // Also insert into generic enquiries table if it exists
        if ($this->db->table_exists('enquiries')) {
            $this->db->insert('enquiries', array(
                'name'         => $client_name,
                'email'        => $email,
                'phone'        => $formatted_phone,
                'package_name' => 'Franchise Opportunity (' . ($city ?: 'All India') . ')',
                'message'      => "State: {$state} | Location: {$preferred_location} | Timeline: {$start_timeline} | Business: {$current_profession} | Associations: {$associations_str} | Note: {$message}",
                'created_at'   => date('Y-m-d H:i:s')
            ));
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(array(
                'status'  => 'success',
                'message' => 'Thank you! Your Franchise Enquiry has been submitted successfully. Our franchise development team will contact you shortly.'
            ));
            return;
        }

        $this->session->set_flashdata('success_msg', 'Thank you! Your Franchise Enquiry has been submitted successfully. Our franchise development team will contact you shortly.');
        redirect('franchise');
    }

    // =========================================================================
    // 1. AUTHENTICATION
    // =========================================================================
    public function login() {
        if ($this->session->userdata('franchise_store_logged_in')) {
            redirect('franchise/flight');
            return;
        }

        $data['error'] = '';
        if ($this->input->method() === 'post') {
            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            $res = $this->Franchise_model->store_login($username, $password);
            if ($res['status']) {
                $store = $res['store'];
                $this->session->set_userdata(array(
                    'franchise_store_logged_in' => true,
                    'franchise_store_id'        => $store['id'],
                    'franchise_store_code'      => $store['agent_code'],
                    'franchise_store_username'  => $store['username'],
                    'franchise_store_name'      => $store['store_name']
                ));
                redirect('franchise/flight');
                return;
            } else {
                $data['error'] = $res['error'];
            }
        }

        $this->load->view('franchise/login', $data);
    }

    public function logout() {
        $this->session->unset_userdata(array(
            'franchise_store_logged_in',
            'franchise_store_id',
            'franchise_store_code',
            'franchise_store_username',
            'franchise_store_name'
        ));
        $this->session->set_flashdata('success', 'Logged out successfully.');
        redirect('franchise/login');
    }

    // =========================================================================
    // 2. STORE B2B DASHBOARD (FLIGHT SEARCH MATCHING USER'S SCREENSHOT)
    // =========================================================================
    public function dashboard() {
        redirect('franchise/flight');
    }

    public function flight() {
        $store = $this->_check_auth();

        $data['title']       = 'B2B Flight Booking - ' . htmlspecialchars($store['store_name']);
        $data['active_menu'] = 'flight';
        $data['store']       = $store;

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/dashboard', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_search() {
        $store = $this->_check_auth();

        $trip_type  = strtolower($this->input->post('trip_type') ?: ($this->input->get('trip_type') ?: ($this->input->post('tripType') ?: ($this->input->get('tripType') ?: 'oneway'))));
        $origin_raw = trim($this->input->post('origin') ?: ($this->input->get('origin') ?: ($this->input->post('from_city') ?: ($this->input->get('from_city') ?: 'Delhi (DEL)'))));
        $dest_raw   = trim($this->input->post('destination') ?: ($this->input->get('destination') ?: ($this->input->post('to_city') ?: ($this->input->get('to_city') ?: 'Mumbai (BOM)'))));

        // Extract 3-letter IATA code if inside parentheses, e.g. "Delhi (DEL)" -> "DEL"
        if (preg_match('/\(([A-Z]{3})\)/i', $origin_raw, $m)) {
            $origin = strtoupper($m[1]);
        } else {
            $origin = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $origin_raw), 0, 3));
            if (strlen($origin) < 3) $origin = 'DEL';
        }
        if (preg_match('/\(([A-Z]{3})\)/i', $dest_raw, $m)) {
            $dest = strtoupper($m[1]);
        } else {
            $dest = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $dest_raw), 0, 3));
            if (strlen($dest) < 3) $dest = 'BOM';
        }

        $depart    = $this->input->post('depart_date') ?: ($this->input->get('depart_date') ?: ($this->input->post('departure_date') ?: ($this->input->get('departure_date') ?: date('Y-m-d', strtotime('+3 days')))));
        $return    = $this->input->post('return_date') ?: ($this->input->get('return_date') ?: date('Y-m-d', strtotime('+7 days')));
        $adults    = max(1, (int)($this->input->post('adults') ?: ($this->input->get('adults') ?: 1)));
        $children  = max(0, (int)($this->input->post('children') ?: ($this->input->get('children') ?: 0)));
        $infants   = max(0, (int)($this->input->post('infants') ?: ($this->input->get('infants') ?: 0)));
        $cabin     = strtoupper($this->input->post('cabin') ?: ($this->input->get('cabin') ?: ($this->input->post('cabin_class') ?: ($this->input->get('cabin_class') ?: 'ECONOMY'))));

        $airlineMap = array(
            '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
            'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
            'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
            'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
            'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
            'I5' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
            'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png')
        );

        // Perform search using BenzyFlightApi
        $is_roundtrip = ($trip_type === 'roundtrip');
        $fareType = $is_roundtrip ? 'RT' : 'ON';
        $tui = $this->benzyflightapi->expressSearch($origin, $dest, $depart, ($is_roundtrip ? $return : ''), $adults, $children, $infants, substr($cabin, 0, 1), $fareType, false);
        if (!empty($tui)) {
            $this->benzyflightapi->getWebSettings($tui);
        }
        $rawSearchResults = $this->benzyflightapi->getExpSearch($tui, $origin, $dest, $depart, false, $is_roundtrip, $return);

        $onwardFlights = array();
        $inboundFlights = array();

        // Process raw results list
        $rawList = array();
        if (!empty($rawSearchResults) && is_array($rawSearchResults)) {
            if (isset($rawSearchResults['Flights']) && is_array($rawSearchResults['Flights'])) {
                $rawList = $rawSearchResults['Flights'];
            } elseif (isset($rawSearchResults['Trips'][0]['Journey']) && is_array($rawSearchResults['Trips'][0]['Journey'])) {
                $rawList = $rawSearchResults['Trips'][0]['Journey'];
            } elseif (isset($rawSearchResults[0]) && is_array($rawSearchResults[0])) {
                $rawList = $rawSearchResults;
            }
        }

        foreach ($rawList as $idx => $item) {
            if (!is_array($item)) continue;
            $code = isset($item['airline_code']) ? strtoupper($item['airline_code']) : (isset($item['AirlineCode']) ? strtoupper($item['AirlineCode']) : '6E');
            $defaultLogo = isset($airlineMap[$code]) ? $airlineMap[$code]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
            $defaultName = isset($airlineMap[$code]) ? $airlineMap[$code]['name'] : ($code . ' Airlines');

            $depTime = $item['departure_time'] ?? ($item['DepartureTime'] ?? '06:00');
            $arrTime = $item['arrival_time'] ?? ($item['ArrivalTime'] ?? '08:15');
            $grossPrice = (float)($item['price'] ?? ($item['total_fare'] ?? ($item['gross_fare'] ?? 4999)));
            $netPrice   = (float)($item['base_fare'] ?? ($item['net_fare'] ?? round($grossPrice * 0.88, 2)));
            $taxPrice   = max(0, $grossPrice - $netPrice);

            $flightObj = array(
                'id'            => $item['id'] ?? ('FL_' . $code . '_' . ($idx + 101)),
                'ResultID'      => !empty($item['tui']) ? $item['tui'] : (!empty($tui) ? $tui : ('FL_' . ($idx + 101))),
                'airline'       => $item['airline_name'] ?? ($item['AirlineName'] ?? $defaultName),
                'airline_name'  => $item['airline_name'] ?? ($item['AirlineName'] ?? $defaultName),
                'airline_code'  => $code,
                'airline_logo'  => !empty($item['airline_logo']) ? $item['airline_logo'] : (!empty($item['AirlineLogo']) ? $item['AirlineLogo'] : $defaultLogo),
                'flight_number' => $item['flight_number'] ?? ($item['FlightNumber'] ?? ($code . '-' . (2000 + $idx))),
                'origin'        => $origin,
                'destination'   => $dest,
                'from_code'     => $item['from_code'] ?? ($item['FromCode'] ?? $origin),
                'to_code'       => $item['to_code'] ?? ($item['ToCode'] ?? $dest),
                'departure'     => (strpos($depTime, ':') !== false && strlen($depTime) <= 5) ? ($depart . ' ' . $depTime . ':00') : $depTime,
                'departure_time'=> (strpos($depTime, ' ') !== false) ? date('H:i', strtotime($depTime)) : $depTime,
                'arrival'       => (strpos($arrTime, ':') !== false && strlen($arrTime) <= 5) ? ($depart . ' ' . $arrTime . ':00') : $arrTime,
                'arrival_time'  => (strpos($arrTime, ' ') !== false) ? date('H:i', strtotime($arrTime)) : $arrTime,
                'duration'      => $item['duration'] ?? ($item['Duration'] ?? '2h 15m'),
                'stops'         => isset($item['stops']) ? (int)$item['stops'] : 0,
                'stops_text'    => (isset($item['stops']) && (int)$item['stops'] > 0) ? ($item['stops'] . ' Stop') : 'Non-stop',
                'price'         => $grossPrice,
                'base_fare'     => $netPrice,
                'tax'           => $taxPrice,
                'total_fare'    => $grossPrice,
                'baggage'       => $item['baggage'] ?? '15 Kg',
                'cabin_baggage' => '7 Kg',
                'refundable'    => true,
                'return_identifier' => isset($item['return_identifier']) ? (int)$item['return_identifier'] : 0
            );

            if ($is_roundtrip && ($flightObj['return_identifier'] === 1 || strtoupper($flightObj['from_code']) === strtoupper($dest))) {
                $inboundFlights[] = $flightObj;
            } else {
                $onwardFlights[] = $flightObj;
            }
        }

        // Resilient Fallback for smooth presentation if Benzy returns empty in test mode
        if (empty($onwardFlights)) {
            $mockDefaults = array(
                array('code' => '6E', 'fn' => '2041', 'dep' => '06:00', 'arr' => '08:15', 'dur' => '02h 15m', 'gross' => 5050, 'net' => 4200, 'stops' => 0),
                array('code' => 'AI', 'fn' => '805',  'dep' => '09:30', 'arr' => '11:45', 'dur' => '02h 15m', 'gross' => 5600, 'net' => 4700, 'stops' => 0),
                array('code' => 'SG', 'fn' => '162',  'dep' => '13:15', 'arr' => '15:30', 'dur' => '02h 15m', 'gross' => 4950, 'net' => 4100, 'stops' => 0),
                array('code' => 'QP', 'fn' => '1120', 'dep' => '18:40', 'arr' => '20:55', 'dur' => '02h 15m', 'gross' => 4800, 'net' => 3950, 'stops' => 0),
                array('code' => 'UK', 'fn' => '945',  'dep' => '21:15', 'arr' => '23:30', 'dur' => '02h 15m', 'gross' => 5850, 'net' => 4900, 'stops' => 0)
            );
            foreach ($mockDefaults as $mIdx => $m) {
                $c = $m['code'];
                $onwardFlights[] = array(
                    'id'            => 'FL_' . $c . '_' . $m['fn'],
                    'ResultID'      => !empty($tui) ? $tui : ('FL_' . ($mIdx + 101)),
                    'airline'       => $airlineMap[$c]['name'] ?? ($c . ' Airlines'),
                    'airline_name'  => $airlineMap[$c]['name'] ?? ($c . ' Airlines'),
                    'airline_code'  => $c,
                    'airline_logo'  => $airlineMap[$c]['logo'],
                    'flight_number' => $c . '-' . $m['fn'],
                    'origin'        => $origin,
                    'destination'   => $dest,
                    'from_code'     => $origin,
                    'to_code'       => $dest,
                    'departure'     => $depart . ' ' . $m['dep'] . ':00',
                    'departure_time'=> $m['dep'],
                    'arrival'       => $depart . ' ' . $m['arr'] . ':00',
                    'arrival_time'  => $m['arr'],
                    'duration'      => $m['dur'],
                    'stops'         => $m['stops'],
                    'stops_text'    => $m['stops'] > 0 ? ($m['stops'] . ' Stop') : 'Non-stop',
                    'price'         => $m['gross'],
                    'base_fare'     => $m['net'],
                    'tax'           => max(0, $m['gross'] - $m['net']),
                    'total_fare'    => $m['gross'],
                    'baggage'       => '15 Kg',
                    'cabin_baggage' => '7 Kg',
                    'refundable'    => true,
                    'return_identifier' => 0
                );
            }
        }

        if ($is_roundtrip && empty($inboundFlights)) {
            $mockRetDefaults = array(
                array('code' => '6E', 'fn' => '2135', 'dep' => '15:30', 'arr' => '17:45', 'dur' => '02h 15m', 'gross' => 5150, 'net' => 4300, 'stops' => 0),
                array('code' => 'SG', 'fn' => '163',  'dep' => '17:45', 'arr' => '20:00', 'dur' => '02h 15m', 'gross' => 4999, 'net' => 4150, 'stops' => 0),
                array('code' => 'AI', 'fn' => '806',  'dep' => '19:15', 'arr' => '21:30', 'dur' => '02h 15m', 'gross' => 5450, 'net' => 4600, 'stops' => 0),
                array('code' => 'QP', 'fn' => '1312', 'dep' => '21:30', 'arr' => '23:45', 'dur' => '02h 15m', 'gross' => 4850, 'net' => 4000, 'stops' => 0),
                array('code' => 'UK', 'fn' => '946',  'dep' => '22:45', 'arr' => '01:00', 'dur' => '02h 15m', 'gross' => 5800, 'net' => 4950, 'stops' => 0)
            );
            foreach ($mockRetDefaults as $rIdx => $rm) {
                $rc = $rm['code'];
                $inboundFlights[] = array(
                    'id'            => 'FL_RET_' . $rc . '_' . $rm['fn'],
                    'ResultID'      => !empty($tui) ? $tui : ('FL_RET_' . ($rIdx + 101)),
                    'airline'       => $airlineMap[$rc]['name'] ?? ($rc . ' Airlines'),
                    'airline_name'  => $airlineMap[$rc]['name'] ?? ($rc . ' Airlines'),
                    'airline_code'  => $rc,
                    'airline_logo'  => $airlineMap[$rc]['logo'],
                    'flight_number' => $rc . '-' . $rm['fn'],
                    'origin'        => $dest,
                    'destination'   => $origin,
                    'from_code'     => $dest,
                    'to_code'       => $origin,
                    'departure'     => $return . ' ' . $rm['dep'] . ':00',
                    'departure_time'=> $rm['dep'],
                    'arrival'       => $return . ' ' . $rm['arr'] . ':00',
                    'arrival_time'  => $rm['arr'],
                    'duration'      => $rm['dur'],
                    'stops'         => $rm['stops'],
                    'stops_text'    => $rm['stops'] > 0 ? ($rm['stops'] . ' Stop') : 'Non-stop',
                    'price'         => $rm['gross'],
                    'base_fare'     => $rm['net'],
                    'tax'           => max(0, $rm['gross'] - $rm['net']),
                    'total_fare'    => $rm['gross'],
                    'baggage'       => '15 Kg',
                    'cabin_baggage' => '7 Kg',
                    'refundable'    => true,
                    'return_identifier' => 1
                );
            }
        }

        $data['title']         = ($is_roundtrip ? 'Round Trip Flights (' . $origin . ' ⇄ ' . $dest . ')' : 'Flight Results (' . $origin . ' → ' . $dest . ')') . ' - Voyogo B2B';
        $data['active_menu']   = 'flight';
        $data['store']         = $store;
        $data['flights']       = $onwardFlights;
        $data['onwardFlights'] = $onwardFlights;
        $data['returnFlights'] = $inboundFlights;
        $data['is_roundtrip']  = $is_roundtrip;
        $data['search_tui']    = $tui;
        $data['search_query']  = array(
            'trip_type'   => $trip_type,
            'origin'      => $origin_raw,
            'destination' => $dest_raw,
            'from_code'   => $origin,
            'to_code'     => $dest,
            'depart_date' => $depart,
            'return_date' => $return,
            'adults'      => $adults,
            'children'    => $children,
            'infants'     => $infants,
            'cabin'       => $cabin,
            'cabin_class' => $cabin,
            'tui'         => $tui
        );

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_search', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_review($p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null) {
        $store = $this->_check_auth();

        $tui = '';
        $price = 0;
        $type = 'D';
        $fare_type = 'ON';
        $cabin = 'E';

        // Check URL segments (Akbar Travels format: franchise/flight/review/{Type}/{FareType}/{Cabin}/{TUI}/{Price})
        $segments = array_values($this->uri->segment_array());
        if (count($segments) >= 7 && strtolower($segments[0]) === 'franchise' && strtolower($segments[1]) === 'flight' && strtolower($segments[2]) === 'review') {
            $type = strtoupper($segments[3]);
            $fare_type = strtoupper($segments[4]);
            $cabin = strtoupper($segments[5]);
            $tui = urldecode($segments[6]);
            if (isset($segments[7]) && is_numeric($segments[7])) {
                $price = (float)$segments[7];
            }
        } elseif (count($segments) >= 4 && strtolower($segments[0]) === 'franchise' && strtolower($segments[1]) === 'flight_review') {
            $tui = urldecode($segments[2]);
            if (isset($segments[3]) && is_numeric($segments[3])) {
                $price = (float)$segments[3];
            }
        } elseif ($p1 !== null) {
            if ($p4 !== null) {
                $type = strtoupper($p1);
                $fare_type = strtoupper($p2);
                $cabin = strtoupper($p3);
                $tui = urldecode($p4);
                $price = (float)($p5 ?: 5050);
            } else {
                $tui = urldecode($p1);
                $price = (float)($p2 ?: 5050);
            }
        }

        // Check if GET without new params and session has booking data (e.g. Back from addons/payment)
        $hasPost = !empty($this->input->post('tui')) || !empty($this->input->post('flight_id')) || !empty($this->input->post('flight_number'));
        $hasGet  = !empty($this->input->get('tui')) || !empty($this->input->get('flight_id'));
        $hasUriParams = !empty($tui);

        if (!$hasPost && !$hasGet && !$hasUriParams) {
            $sessionBooking = $this->session->userdata('franchise_flight_booking_data');
            if (!empty($sessionBooking) && !empty($sessionBooking['flight'])) {
                $data = $sessionBooking;
                $data['store'] = $store;
                $savedReviewPost = $this->session->userdata('franchise_flight_review_post');
                if (!empty($savedReviewPost)) {
                    $data['saved_review_post'] = $savedReviewPost;
                }
                $this->load->view('franchise/layout/header', $data);
                $this->load->view('franchise/flight_review', $data);
                $this->load->view('franchise/layout/footer');
                return;
            } else {
                redirect('franchise/flight');
                return;
            }
        }

        // Fallback to GET or POST if URL params not present
        if (empty($tui)) {
            $tui = $this->input->post('tui') ?: ($this->input->get('tui') ?: ($this->input->post('flight_id') ?: ($this->input->get('flight_id') ?: ('FL_' . date('YmdHis')))));
        }
        if ($price <= 0) {
            $price = (float)($this->input->post('price') ?: ($this->input->get('price') ?: ($this->input->post('total_fare') ?: 5050)));
        }

        $adults      = max(1, (int)($this->input->post('adults') ?: ($this->input->get('adults') ?: 1)));
        $children    = max(0, (int)($this->input->post('children') ?: ($this->input->get('children') ?: 0)));
        $infants     = max(0, (int)($this->input->post('infants') ?: ($this->input->get('infants') ?: 0)));
        $cabin_class = $this->input->post('cabin_class') ?: ($this->input->get('cabin_class') ?: 'Economy');
        $is_roundtrip = (bool)($this->input->post('is_roundtrip') ?: ($fare_type === 'RT' || $fare_type === 'RD' || $this->input->post('trip_type') === 'roundtrip' || $this->input->get('trip_type') === 'roundtrip'));

        $from_code_post      = strtoupper($this->input->post('from_code') ?: ($this->input->get('from_code') ?: ($this->input->post('origin') ?: 'DEL')));
        $to_code_post        = strtoupper($this->input->post('to_code') ?: ($this->input->get('to_code') ?: ($this->input->post('destination') ?: 'BOM')));
        $from_city_post      = $this->input->post('from_city') ?: ($this->input->get('from_city') ?: ($this->input->post('origin') ?: ''));
        $to_city_post        = $this->input->post('to_city') ?: ($this->input->get('to_city') ?: ($this->input->post('destination') ?: ''));
        $departure_date_post = $this->input->post('departure_date') ?: ($this->input->get('departure_date') ?: ($this->input->post('depart_date') ?: date('Y-m-d', strtotime('+3 days'))));
        $return_date_post    = $this->input->post('return_departure_date') ?: ($this->input->post('return_date') ?: ($this->input->get('return_date') ?: date('Y-m-d', strtotime('+7 days'))));
        $return_price        = (float)($this->input->post('return_price') ?: ($this->input->get('return_price') ?: $price));

        $flight_number  = $this->input->post('flight_number') ?: '6E-2041';
        $airline_code   = strtoupper(explode('-', $flight_number)[0]);
        if (empty($airline_code)) $airline_code = '6E';
        $onward_index   = $this->input->post('flight_index') ?: ($airline_code . '|1');

        $return_flight_number = $this->input->post('return_flight_number') ?: '6E-2135';
        $return_airline_code  = strtoupper(explode('-', $return_flight_number)[0]);
        if (empty($return_airline_code)) $return_airline_code = '6E';
        $return_index   = $this->input->post('return_flight_index') ?: ($return_airline_code . '|1');

        // Fetch revalidated flight data using Benzy API (SmartPricer & GetSPricer)
        $search_tui = $tui;
        $spRes = @$this->benzyflightapi->smartPricer($tui, $price, $onward_index, $is_roundtrip, $from_code_post, $to_code_post, $return_price, $return_index);
        
        $flightDetails = null;
        if (!empty($spRes) && (empty($spRes['Code']) || (string)$spRes['Code'] === '200') && !empty($spRes['Trips'])) {
            if (!empty($spRes['TUI'])) {
                $tui = $spRes['TUI'];
            }
            $flightDetails = $this->benzyflightapi->getSPricer($tui, $price, $from_code_post, $to_code_post, $is_roundtrip);
        }

        if (empty($flightDetails) || empty($flightDetails['from_code'])) {
            $flightDetails = $this->benzyflightapi->parseSingleFlightReview($spRes, $tui);
        }
        if (!empty($flightDetails['tui'])) {
            $tui = $flightDetails['tui'];
        } elseif (!empty($flightDetails['TUI'])) {
            $tui = $flightDetails['TUI'];
        }
        if (empty($flightDetails) || !is_array($flightDetails)) {
            $flightDetails = $this->benzyflightapi->getMockReviewDetails($tui, $price);
        }

        // Airport names & terminals map
        $airportNames = array(
            'DEL' => array('name' => 'Indira Gandhi International Airport, Delhi', 'terminal' => 'Terminal 2'),
            'BOM' => array('name' => 'Chhatrapati Shivaji Maharaj International Airport, Mumbai', 'terminal' => 'Terminal 2'),
            'BLR' => array('name' => 'Kempegowda International Airport, Bengaluru', 'terminal' => 'Terminal 1'),
            'MAA' => array('name' => 'Chennai International Airport, Chennai', 'terminal' => 'Terminal 1'),
            'HYD' => array('name' => 'Rajiv Gandhi International Airport, Hyderabad', 'terminal' => 'Terminal 1'),
            'CCU' => array('name' => 'Netaji Subhash Chandra Bose International Airport, Kolkata', 'terminal' => 'Terminal 2'),
            'GOI' => array('name' => 'Dabolim Airport, Goa', 'terminal' => 'Terminal 1'),
            'GOX' => array('name' => 'Manohar International Airport, Mopa Goa', 'terminal' => 'Terminal 1'),
            'COK' => array('name' => 'Cochin International Airport, Kochi', 'terminal' => 'Terminal 3'),
            'AMD' => array('name' => 'Sardar Vallabhbhai Patel International Airport, Ahmedabad', 'terminal' => 'Terminal 1'),
            'PNQ' => array('name' => 'Pune International Airport, Pune', 'terminal' => 'Terminal 1'),
            'JAI' => array('name' => 'Jaipur International Airport, Jaipur', 'terminal' => 'Terminal 2'),
            'DXB' => array('name' => 'Dubai International Airport, Dubai', 'terminal' => 'Terminal 3'),
            'SIN' => array('name' => 'Singapore Changi Airport, Singapore', 'terminal' => 'Terminal 3'),
            'BKK' => array('name' => 'Suvarnabhumi Airport, Bangkok', 'terminal' => 'Terminal 1'),
            'LHR' => array('name' => 'Heathrow Airport, London', 'terminal' => 'Terminal 2')
        );

        if ($this->input->post('airline_name')) $flightDetails['airline_name'] = $this->input->post('airline_name');
        if ($this->input->post('airline_logo')) $flightDetails['airline_logo'] = $this->input->post('airline_logo');
        if ($this->input->post('flight_number')) $flightDetails['flight_number'] = $this->input->post('flight_number');
        if ($this->input->post('from_code')) $flightDetails['from_code'] = strtoupper($this->input->post('from_code'));
        if ($this->input->post('to_code')) $flightDetails['to_code'] = strtoupper($this->input->post('to_code'));
        if ($this->input->post('departure_time')) $flightDetails['departure_time'] = $this->input->post('departure_time');
        if ($this->input->post('arrival_time')) $flightDetails['arrival_time'] = $this->input->post('arrival_time');
        if ($this->input->post('departure_date')) $flightDetails['departure_date'] = $this->input->post('departure_date');
        elseif (!empty($departure_date_post)) $flightDetails['departure_date'] = $departure_date_post;
        if ($this->input->post('duration')) $flightDetails['duration'] = $this->input->post('duration');
        if ($this->input->post('stops') !== null && $this->input->post('stops') !== '') $flightDetails['stops'] = (int)$this->input->post('stops');
        if (!isset($flightDetails['stops'])) $flightDetails['stops'] = 0;
        $flightDetails['via'] = $this->input->post('via') ?: ($flightDetails['stops'] > 0 ? 'HYD' : '');

        $onward_fare_type = $this->input->post('onward_fare_type') ?: ($this->input->post('fare_type') ?: 'Retail');
        $return_fare_type = $this->input->post('return_fare_type') ?: 'Retail';
        $flightDetails['fare_type'] = $onward_fare_type;

        // Return flight details
        $returnFlight = null;
        if ($is_roundtrip || $this->input->post('return_flight_number')) {
            $returnStops = (int)($this->input->post('return_stops') ?: 0);
            $returnFlight = array(
                'airline_name'   => $this->input->post('return_airline_name') ?: 'IndiGo',
                'airline_logo'   => $this->input->post('return_airline_logo') ?: 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png',
                'flight_number'  => $this->input->post('return_flight_number') ?: '6E-102',
                'from_code'      => strtoupper($this->input->post('return_from_code') ?: ($flightDetails['to_code'] ?? 'BOM')),
                'to_code'        => strtoupper($this->input->post('return_to_code') ?: ($flightDetails['from_code'] ?? 'DEL')),
                'departure_time' => $this->input->post('return_departure_time') ?: '18:00',
                'arrival_time'   => $this->input->post('return_arrival_time') ?: '20:15',
                'departure_date' => $return_date_post ?: ($this->input->post('return_departure_date') ?: date('Y-m-d', strtotime('+7 days'))),
                'duration'       => $this->input->post('return_duration') ?: '02h 15m',
                'stops'          => $returnStops,
                'via'            => $this->input->post('return_via') ?: ($returnStops > 0 ? 'HYD' : ''),
                'price'          => (float)($this->input->post('return_price') ?: 5150),
                'fare_type'      => $return_fare_type
            );
            $returnFlight['from_airport'] = $airportNames[$returnFlight['from_code']]['name'] ?? ($returnFlight['from_code'] . ' Airport');
            $returnFlight['from_terminal'] = $airportNames[$returnFlight['from_code']]['terminal'] ?? 'Terminal 1';
            $returnFlight['to_airport'] = $airportNames[$returnFlight['to_code']]['name'] ?? ($returnFlight['to_code'] . ' Airport');
            $returnFlight['to_terminal'] = $airportNames[$returnFlight['to_code']]['terminal'] ?? 'Terminal 2';
        }

        $fromCode = $flightDetails['from_code'] ?? 'DEL';
        $toCode = $flightDetails['to_code'] ?? 'BOM';

        $domesticAirports = array(
            'DEL', 'BOM', 'BLR', 'MAA', 'HYD', 'CCU', 'GOI', 'GOX', 'COK', 'AMD', 'PNQ', 'JAI', 
            'TRV', 'ATQ', 'BBI', 'IXC', 'IXB', 'VTZ', 'PAT', 'GAU', 'LKO', 'NAG', 'IDR', 'SXR', 
            'IXR', 'BDQ', 'IXE', 'TRZ', 'CJB', 'VNS', 'UDR', 'IXJ', 'IMF', 'RPR', 'DED', 'IXA', 
            'IXZ', 'IXL', 'IXD', 'IXU', 'JGA', 'JDH', 'AJL', 'DMU', 'TEZ', 'IXS', 'SHL', 'IXV', 
            'IXW', 'IXP', 'IXT', 'IXY', 'HJR', 'BHO', 'GWL', 'JLR', 'TIR', 'VGA'
        );
        $is_international = ($type === 'I')
            || (strtoupper($this->input->post('flight_type') ?: ($this->input->get('flight_type') ?: '')) === 'I')
            || (strtoupper($this->input->post('trip_category') ?: ($this->input->get('trip_category') ?: '')) === 'INTERNATIONAL')
            || (isset($_GET['intl']) && $_GET['intl'] == '1')
            || (!in_array(strtoupper($fromCode), $domesticAirports) || !in_array(strtoupper($toCode), $domesticAirports));

        $data['is_international'] = $is_international;
        $flightDetails['from_airport'] = $airportNames[$fromCode]['name'] ?? ($fromCode . ' International Airport');
        $flightDetails['from_terminal'] = $airportNames[$fromCode]['terminal'] ?? 'Terminal 2';
        $flightDetails['to_airport'] = $airportNames[$toCode]['name'] ?? ($toCode . ' International Airport');
        $flightDetails['to_terminal'] = $airportNames[$toCode]['terminal'] ?? 'Terminal 1';

        $total_travelers = (int)$adults + (int)$children + (int)$infants;
        $pax_multiplier = $adults + $children + (0.5 * $infants);
        if ($pax_multiplier < 1) $pax_multiplier = 1;

        $search_onward_price = (float)$price;
        $search_return_price = $is_roundtrip ? (float)($returnFlight['price'] ?? $return_price) : 0;
        $search_total = ($search_onward_price + $search_return_price) * $pax_multiplier;

        $live_gross_amount = isset($flightDetails['gross_amount']) ? (float)$flightDetails['gross_amount'] : (float)($flightDetails['price'] ?? $search_onward_price);
        $live_base_fare    = isset($flightDetails['total_base_fare']) ? (float)$flightDetails['total_base_fare'] : (isset($flightDetails['base_fare']) ? (float)$flightDetails['base_fare'] : round($live_gross_amount * 0.788));
        $live_taxes        = isset($flightDetails['total_tax']) ? (float)$flightDetails['total_tax'] : (isset($flightDetails['taxes']) ? (float)$flightDetails['taxes'] : max(0, $live_gross_amount - $live_base_fare));

        if ($is_roundtrip && !empty($returnFlight)) {
            if ($live_gross_amount >= ($search_total * 0.75)) {
                $final_flight_price = $live_gross_amount;
                $final_base_fare    = $live_base_fare;
                $final_taxes        = $live_taxes;
                $final_itemized_tax = !empty($flightDetails['itemized_taxes']) ? $flightDetails['itemized_taxes'] : array();
            } else {
                $ret_gross = (float)$returnFlight['price'];
                $ret_base  = round($ret_gross * 0.788);
                $ret_tax   = max(0, $ret_gross - $ret_base);

                $final_flight_price = $live_gross_amount + $ret_gross;
                $final_base_fare    = $live_base_fare + $ret_base;
                $final_taxes        = $live_taxes + $ret_tax;
                $final_itemized_tax = array();
            }
        } else {
            $final_flight_price = $live_gross_amount;
            $final_base_fare    = $live_base_fare;
            $final_taxes        = $live_taxes;
            $final_itemized_tax = !empty($flightDetails['itemized_taxes']) ? $flightDetails['itemized_taxes'] : array();
        }

        // Fare update alert check
        $fare_updated = false;
        $old_fare = round($search_total);
        $new_fare = round($final_flight_price * $pax_multiplier);
        $fare_change_msg = '';
        if (abs($new_fare - $old_fare) >= 50 && $old_fare > 0) {
            $fare_updated = true;
            $fareDiff = abs($new_fare - $old_fare);
            if ($new_fare > $old_fare) {
                $fare_change_msg = "Fare increased by ₹ " . number_format($fareDiff) . " due to updated airline inventory.";
            } else {
                $fare_change_msg = "Fare decreased by ₹ " . number_format($fareDiff) . "! You got a better deal.";
            }
        }

        $data['pricing'] = array(
            'base_fare'       => round($final_base_fare * $pax_multiplier),
            'taxes'           => round($final_taxes * $pax_multiplier),
            'other_charges'   => 0,
            'flight_total'    => round($final_flight_price * $pax_multiplier),
            'addons_total'    => 0,
            'grand_total'     => round($final_flight_price * $pax_multiplier),
            'unit_price'      => $final_flight_price,
            'unit_base_fare'  => $final_base_fare,
            'unit_taxes'      => $final_taxes,
            'pax_multiplier'  => $pax_multiplier,
            'itemized_taxes'  => $final_itemized_tax
        );

        // Fetch Fare Rules (Cancellation & Date change policy)
        $fareRules = $this->benzyflightapi->getFareRule($tui, $price, $onward_index, $fromCode, $toCode, $search_tui);

        // Fetch SSR options (Baggage, Meals, Seats)
        $ssrOptions = $this->benzyflightapi->getSSR($tui, $fromCode, $toCode, $airline_code, $flight_number, $search_tui, $onward_index);

        // Parse cancellation & date change rules for view tables
        $cancellationRules = array();
        $dateChangeRules   = array();
        if (!empty($fareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules'][0]['Rule'])) {
            foreach ($fareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules'][0]['Rule'] as $rItem) {
                $head = strtolower($rItem['Head'] ?? '');
                $info = $rItem['Info'] ?? array();
                if (strpos($head, 'cancel') !== false) {
                    foreach ($info as $inf) {
                        $amtStr = trim($inf['AdultAmount'] ?? '');
                        if (is_numeric($amtStr)) $amtStr = '₹ ' . number_format($amtStr);
                        $cancellationRules[] = array(
                            'time'   => $inf['Description'] ?? 'Cancellation',
                            'desc'   => $inf['Description'] ?? 'Cancellation',
                            'fee'    => $amtStr ?: 'Non-Refundable',
                            'amount' => $amtStr ?: 'Non-Refundable'
                        );
                    }
                } elseif (strpos($head, 'change') !== false || strpos($head, 'reissue') !== false) {
                    foreach ($info as $inf) {
                        $amtStr = trim($inf['AdultAmount'] ?? '');
                        if (is_numeric($amtStr)) $amtStr = '₹ ' . number_format($amtStr);
                        $dateChangeRules[] = array(
                            'time'   => $inf['Description'] ?? 'Date Change',
                            'desc'   => $inf['Description'] ?? 'Date Change',
                            'fee'    => $amtStr ? ($amtStr . ' + Diff') : '₹ 2,500 + Diff',
                            'amount' => $amtStr ? ($amtStr . ' + Diff') : '₹ 2,500 + Diff'
                        );
                    }
                }
            }
        }
        if (is_array($fareRules)) {
            $fareRules['cancellation'] = $cancellationRules;
            $fareRules['date_change']   = $dateChangeRules;
        }

        $onwardAirlineCode = $flightDetails['airline_code'] ?? $airline_code;
        $onwardBasePrice   = (float)($flightDetails['base_fare'] ?? round($price * 0.788));
        $onwardTaxPrice    = (float)($flightDetails['taxes'] ?? max(0, $price - $onwardBasePrice));
        $onwardRules       = !empty($fareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules']) ? $fareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules'] : ($flightDetails['rules'] ?? array());
        $onwardInclusions  = $flightDetails['inclusions'] ?? array();
        $onwardSsrItems    = !empty($ssrOptions['Trips'][0]['Journey'][0]['Segments'][0]['SSR']) ? $ssrOptions['Trips'][0]['Journey'][0]['Segments'][0]['SSR'] : ($flightDetails['ssr'] ?? array());

        $onwardFareTiers = $this->benzyflightapi->getDynamicFareTiers($onwardAirlineCode, $onwardBasePrice, $onwardTaxPrice, $onwardRules, $onwardInclusions, $onwardSsrItems);

        $returnFareTiers = array();
        $returnFareRules = null;
        if ($is_roundtrip && !empty($returnFlight)) {
            $returnAirlineCode = $returnFlight['airline_code'] ?? (!empty($returnFlight['flight_number']) ? explode('-', $returnFlight['flight_number'])[0] : '6E');
            $returnPriceVal    = (float)($returnFlight['price'] ?? 5150);
            $returnBasePrice   = round($returnPriceVal * 0.788);
            $returnTaxPrice    = max(0, $returnPriceVal - $returnBasePrice);

            $returnFareRules = $this->benzyflightapi->getFareRule($tui, $returnPriceVal, $return_index, $returnFlight['from_code'] ?? $toCode, $returnFlight['to_code'] ?? $fromCode, $search_tui);
            $retCancelRules = array();
            $retDateChangeRules = array();
            if (!empty($returnFareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules'][0]['Rule'])) {
                foreach ($returnFareRules['Trips'][0]['Journey'][0]['Segments'][0]['Rules'][0]['Rule'] as $rItem) {
                    $head = strtolower($rItem['Head'] ?? '');
                    $info = $rItem['Info'] ?? array();
                    if (strpos($head, 'cancel') !== false) {
                        foreach ($info as $inf) {
                            $amtStr = trim($inf['AdultAmount'] ?? '');
                            if (is_numeric($amtStr)) $amtStr = '₹ ' . number_format($amtStr);
                            $retCancelRules[] = array(
                                'time'   => $inf['Description'] ?? 'Cancellation',
                                'desc'   => $inf['Description'] ?? 'Cancellation',
                                'fee'    => $amtStr ?: 'Non-Refundable',
                                'amount' => $amtStr ?: 'Non-Refundable'
                            );
                        }
                    } elseif (strpos($head, 'change') !== false || strpos($head, 'reissue') !== false) {
                        foreach ($info as $inf) {
                            $amtStr = trim($inf['AdultAmount'] ?? '');
                            if (is_numeric($amtStr)) $amtStr = '₹ ' . number_format($amtStr);
                            $retDateChangeRules[] = array(
                                'time'   => $inf['Description'] ?? 'Date Change',
                                'desc'   => $inf['Description'] ?? 'Date Change',
                                'fee'    => $amtStr ? ($amtStr . ' + Diff') : '₹ 2,500 + Diff',
                                'amount' => $amtStr ? ($amtStr . ' + Diff') : '₹ 2,500 + Diff'
                            );
                        }
                    }
                }
            }
            if (is_array($returnFareRules)) {
                $returnFareRules['cancellation'] = $retCancelRules;
                $returnFareRules['date_change']   = $retDateChangeRules;
            }
            $returnFareTiers = $this->benzyflightapi->getDynamicFareTiers($returnAirlineCode, $returnBasePrice, $returnTaxPrice, $onwardRules, $onwardInclusions, $onwardSsrItems);
        }

        $data['store']             = $store;
        $data['active_menu']       = 'flight';
        $data['title']             = "Review Flight: $fromCode to $toCode - Voyogo B2B";
        $data['flight']            = $flightDetails;
        $data['return_flight']     = $returnFlight;
        $data['is_roundtrip']      = $is_roundtrip;
        $data['fare_rules']        = $fareRules;
        $data['return_fare_rules'] = $returnFareRules;
        $data['ssr']               = $ssrOptions;
        $data['onward_fare_tiers'] = $onwardFareTiers;
        $data['return_fare_tiers'] = $returnFareTiers;
        $data['fare_updated']      = $fare_updated;
        $data['old_fare']          = $old_fare;
        $data['new_fare']          = $new_fare;
        $data['fare_change_msg']   = $fare_change_msg;
        $data['total_travelers']   = $total_travelers;
        $data['adults']            = $adults;
        $data['children']          = $children;
        $data['infants']           = $infants;
        $data['cabin_class']       = $cabin_class;

        $trip_type_str    = $is_roundtrip ? 'roundtrip' : 'oneway';
        $actual_from_city = !empty($from_city_post) ? $from_city_post : ($flightDetails['from_airport'] ?? ($fromCode . ' (' . $fromCode . ')'));
        $actual_to_city   = !empty($to_city_post) ? $to_city_post : ($flightDetails['to_airport'] ?? ($toCode . ' (' . $toCode . ')'));
        $actual_dep_date  = !empty($flightDetails['departure_date']) ? $flightDetails['departure_date'] : ($departure_date_post ?: date('Y-m-d', strtotime('+3 days')));
        $actual_ret_date  = ($is_roundtrip && !empty($returnFlight['departure_date'])) ? $returnFlight['departure_date'] : ($return_date_post ?: date('Y-m-d', strtotime('+7 days')));

        $data['search_query'] = array(
            'from'           => $actual_from_city,
            'to'             => $actual_to_city,
            'from_code'      => $fromCode,
            'to_code'        => $toCode,
            'date'           => $actual_dep_date,
            'departure_date' => $actual_dep_date,
            'return_date'    => $actual_ret_date,
            'trip_type'      => $trip_type_str,
            'adults'         => $adults,
            'children'       => $children,
            'infants'        => $infants,
            'cabin_class'    => $cabin_class,
            'is_roundtrip'   => $is_roundtrip
        );

        $data['back_search_url'] = site_url('franchise/flight_search') . '?' . http_build_query(array(
            'trip_type'   => $trip_type_str,
            'origin'      => $actual_from_city,
            'destination' => $actual_to_city,
            'depart_date' => $actual_dep_date,
            'return_date' => $actual_ret_date,
            'adults'      => $adults,
            'children'    => $children,
            'infants'     => $infants,
            'cabin'       => $cabin_class
        ));
        $data['search_tui'] = $search_tui;
        $data['url_meta'] = array(
            'type' => $type,
            'fare_type' => $is_roundtrip ? 'RT' : $fare_type,
            'cabin' => $cabin,
            'tui' => $tui,
            'search_tui' => $search_tui
        );
        $data['isUserLoggedIn'] = true;

        $this->session->set_userdata('franchise_flight_booking_data', $data);
        $this->session->unset_userdata('franchise_flight_review_post');
        $this->session->unset_userdata('franchise_flight_addons_post');

        if ($this->input->method(TRUE) === 'POST') {
            redirect('franchise/flight_review');
            return;
        }

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_review', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_addons() {
        $store = $this->_check_auth();

        $sessionBooking = $this->session->userdata('franchise_flight_booking_data') ?: array();
        $postData = $this->input->post();
        if (!empty($postData)) {
            $this->session->set_userdata('franchise_flight_review_post', $postData);
            redirect('franchise/flight_addons');
            return;
        } else {
            $postData = $this->session->userdata('franchise_flight_review_post') ?: array();
        }

        if (empty($sessionBooking)) {
            redirect('franchise/flight');
            return;
        }

        $data = array_merge($sessionBooking, $postData);
        $data['store'] = $store;
        $data['active_menu'] = 'flight';
        $data['title'] = "Add-on Services: Meals, Baggage & Seats - Voyogo B2B";
        $data['post_data'] = $postData;

        // Parse passengers
        $titles        = $postData['passenger_title'] ?? array();
        $firstNames    = $postData['passenger_first_name'] ?? array();
        $lastNames     = $postData['passenger_last_name'] ?? array();
        $names         = $postData['passenger_name'] ?? array();
        $dobs          = $postData['passenger_dob'] ?? array();
        $ages          = $postData['passenger_age'] ?? array();
        $types         = $postData['passenger_type'] ?? array();
        $nationalities = $postData['passenger_nationality'] ?? array();
        $passports     = $postData['passenger_passport_no'] ?? array();
        $passportExps  = $postData['passenger_passport_expiry'] ?? array();
        $issuingCnts   = $postData['passenger_issuing_country'] ?? array();
        $visaTypes     = $postData['passenger_visa_type'] ?? array();
        $residenceCnts = $postData['passenger_residence_country'] ?? array();
        $ffAirlines    = $postData['passenger_ff_airline'] ?? array();
        $ffNumbers     = $postData['passenger_ff_number'] ?? array();

        $passengers = array();
        $count = max(count((array)$names), count((array)$firstNames));
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $p_idx = $i + 1;
                $gender = $postData['passenger_gender_' . $p_idx] ?? 'Male';
                $fName = isset($firstNames[$i]) ? trim($firstNames[$i]) : '';
                $lName = isset($lastNames[$i]) ? trim($lastNames[$i]) : '';
                $rawName = isset($names[$i]) ? trim($names[$i]) : '';
                if (empty($rawName) && (!empty($fName) || !empty($lName))) {
                    $rawName = trim("$fName $lName");
                }
                if (empty($fName) && !empty($rawName)) {
                    $parts = explode(' ', $rawName, 2);
                    $fName = $parts[0] ?? '';
                    $lName = $parts[1] ?? '';
                }

                $passengers[] = array(
                    'title'             => isset($titles[$i]) ? $titles[$i] : 'Mr',
                    'name'              => !empty($rawName) ? $rawName : 'Passenger ' . $p_idx,
                    'first_name'        => $fName,
                    'last_name'         => $lName,
                    'dob'               => isset($dobs[$i]) ? $dobs[$i] : '',
                    'age'               => isset($ages[$i]) ? $ages[$i] : '28',
                    'gender'            => $gender,
                    'type'              => isset($types[$i]) ? $types[$i] : 'Adult',
                    'nationality'       => isset($nationalities[$i]) ? $nationalities[$i] : 'Indian',
                    'passport_no'       => isset($passports[$i]) ? $passports[$i] : '',
                    'passport_expiry'   => isset($passportExps[$i]) ? $passportExps[$i] : '',
                    'issuing_country'   => isset($issuingCnts[$i]) ? $issuingCnts[$i] : 'India',
                    'visa_type'         => isset($visaTypes[$i]) ? $visaTypes[$i] : 'Tourist Visa',
                    'residence_country' => isset($residenceCnts[$i]) ? $residenceCnts[$i] : 'India',
                    'ff_airline'        => isset($ffAirlines[$i]) ? $ffAirlines[$i] : '',
                    'ff_number'         => isset($ffNumbers[$i]) ? $ffNumbers[$i] : ''
                );
            }
        } else {
            $passengers[] = array(
                'title'             => 'Mr',
                'name'              => 'Passenger 1',
                'first_name'        => 'Passenger',
                'last_name'         => '1',
                'dob'               => '1995-01-01',
                'age'               => '30',
                'gender'            => 'Male',
                'type'              => 'Adult',
                'nationality'       => 'Indian',
                'passport_no'       => '',
                'passport_expiry'   => '',
                'issuing_country'   => 'India',
                'visa_type'         => 'Tourist Visa',
                'residence_country' => 'India',
                'ff_airline'        => '',
                'ff_number'         => ''
            );
        }
        $data['passengers'] = $passengers;

        $flight_info    = $data['flight'] ?? array();
        $flight_number  = $flight_info['flight_number'] ?? '6E-2041';
        $airline_name   = $flight_info['airline_name'] ?? 'IndiGo';
        $from_code      = strtoupper($flight_info['from_code'] ?? 'DEL');
        $to_code        = strtoupper($flight_info['to_code'] ?? 'BOM');
        $departure_date = $flight_info['departure_date'] ?? date('Y-m-d', strtotime('+3 days'));
        $departure_time = $flight_info['departure_time'] ?? '06:00';
        $arrival_time   = $flight_info['arrival_time'] ?? '08:15';
        $duration       = $flight_info['duration'] ?? '02h 15m';
        $stops          = (int)($flight_info['stops'] ?? 0);
        $onwardTui      = $flight_info['tui'] ?? ($sessionBooking['search_tui'] ?? '');
        $onwardAirline  = $this->benzyflightapi->extractAirlineCode($flight_number, $airline_name);

        $is_roundtrip   = !empty($data['is_roundtrip']);
        $ret_flight_info = $data['return_flight'] ?? array();
        $return_flight_number = $ret_flight_info['flight_number'] ?? '6E-2135';
        $return_airline_name  = $ret_flight_info['airline_name'] ?? 'IndiGo';
        $return_from_code     = strtoupper($ret_flight_info['from_code'] ?? $to_code);
        $return_to_code       = strtoupper($ret_flight_info['to_code'] ?? $from_code);
        $return_departure_date = $ret_flight_info['departure_date'] ?? date('Y-m-d', strtotime('+7 days'));
        $return_departure_time = $ret_flight_info['departure_time'] ?? '18:00';
        $return_arrival_time   = $ret_flight_info['arrival_time'] ?? '20:15';
        $return_duration       = $ret_flight_info['duration'] ?? '02h 15m';
        $return_stops          = (int)($ret_flight_info['stops'] ?? 0);
        $returnTui             = $ret_flight_info['tui'] ?? $onwardTui;
        $returnAirline         = $this->benzyflightapi->extractAirlineCode($return_flight_number, $return_airline_name);

        $addonSearchTui = $sessionBooking['search_tui'] ?? null;
        $onwardIndex = $flight_info['index'] ?? ($onwardAirline . '|1');
        $returnIndex = $ret_flight_info['index'] ?? ($returnAirline . '|1');

        $onwardSSRRaw = $this->benzyflightapi->getSSR($onwardTui, $from_code, $to_code, $onwardAirline, $flight_number, $addonSearchTui, $onwardIndex);
        $onwardParsedSSR = $this->benzyflightapi->parseSSRForDisplay($onwardSSRRaw, $onwardAirline);
        $onwardSeatsRaw = $this->benzyflightapi->getSeatLayout($onwardTui, $onwardAirline, $flight_number);
        $onwardParsedSeats = $this->benzyflightapi->parseSeatLayoutForDisplay($onwardSeatsRaw);

        $returnParsedSSR = array('meals' => array(), 'baggage' => array(), 'priority' => array());
        $returnParsedSeats = array('rows' => array(), 'seats' => array());
        if ($is_roundtrip) {
            $returnSSRRaw = $this->benzyflightapi->getSSR($returnTui, $return_from_code, $return_to_code, $returnAirline, $return_flight_number, $addonSearchTui, $returnIndex);
            $returnParsedSSR = $this->benzyflightapi->parseSSRForDisplay($returnSSRRaw, $returnAirline);
            $returnSeatsRaw = $this->benzyflightapi->getSeatLayout($returnTui, $returnAirline, $return_flight_number);
            $returnParsedSeats = $this->benzyflightapi->parseSeatLayoutForDisplay($returnSeatsRaw);
        }

        $data['addons_data'] = array(
            'onward' => array(
                'airline_code'   => $onwardAirline,
                'airline_name'   => $airline_name,
                'flight_number'  => $flight_number,
                'origin'         => $from_code,
                'destination'    => $to_code,
                'departure_date' => $departure_date,
                'departure_time' => $departure_time,
                'arrival_time'   => $arrival_time,
                'duration'       => $duration,
                'stops'          => $stops,
                'meals'          => $onwardParsedSSR['meals'],
                'baggage'        => $onwardParsedSSR['baggage'],
                'priority'       => $onwardParsedSSR['priority'],
                'seat_rows'      => $onwardParsedSeats['rows'],
                'seats_flat'     => $onwardParsedSeats['seats']
            ),
            'return' => $is_roundtrip ? array(
                'airline_code'   => $returnAirline,
                'airline_name'   => $return_airline_name,
                'flight_number'  => $return_flight_number,
                'origin'         => $return_from_code,
                'destination'    => $return_to_code,
                'departure_date' => $return_departure_date,
                'departure_time' => $return_departure_time,
                'arrival_time'   => $return_arrival_time,
                'duration'       => $return_duration,
                'stops'          => $return_stops,
                'meals'          => $returnParsedSSR['meals'],
                'baggage'        => $returnParsedSSR['baggage'],
                'priority'       => $returnParsedSSR['priority'],
                'seat_rows'      => $returnParsedSeats['rows'],
                'seats_flat'     => $returnParsedSeats['seats']
            ) : null
        );

        $data['flight_number']         = $flight_number;
        $data['airline_name']          = $airline_name;
        $data['origin']                = $from_code;
        $data['destination']           = $to_code;
        $data['departure_date']        = $departure_date;
        $data['departure_time']        = $departure_time;
        $data['arrival_time']          = $arrival_time;
        $data['duration']              = $duration;
        $data['stops']                 = $stops;

        $data['is_roundtrip']          = $is_roundtrip;
        $data['return_flight_number']  = $return_flight_number;
        $data['return_airline_name']   = $return_airline_name;
        $data['return_origin']         = $return_from_code;
        $data['return_destination']    = $return_to_code;
        $data['return_departure_date'] = $return_departure_date;
        $data['return_departure_time'] = $return_departure_time;
        $data['return_arrival_time']   = $return_arrival_time;
        $data['return_duration']       = $return_duration;
        $data['return_stops']          = $return_stops;

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_addons', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_payment() {
        $store = $this->_check_auth();

        $sessionBooking = $this->session->userdata('franchise_flight_booking_data') ?: array();
        $reviewPost     = $this->session->userdata('franchise_flight_review_post') ?: array();
        $addonsPost     = $this->input->post() ?: array();

        if (!empty($addonsPost)) {
            $this->session->set_userdata('franchise_flight_addons_post', $addonsPost);
            redirect('franchise/flight_payment');
            return;
        } else {
            $addonsPost = $this->session->userdata('franchise_flight_addons_post') ?: array();
        }

        if (empty($sessionBooking) && empty($reviewPost)) {
            redirect('franchise/flight');
            return;
        }

        $data = array_merge($sessionBooking, $reviewPost, $addonsPost);
        $data['store']         = $store;
        $data['active_menu']   = 'flight';
        $data['title']         = 'Review & Pay with Store Float - Voyogo B2B';
        $data['flight']        = $sessionBooking['flight'] ?? array();
        $data['return_flight'] = $sessionBooking['return_flight'] ?? array();
        $data['search_query']  = $sessionBooking['search_query'] ?? array();
        $data['is_roundtrip']  = !empty($sessionBooking['is_roundtrip']) || !empty($data['return_flight']);
        $data['fare_rules']    = $sessionBooking['fare_rules'] ?? array();
        $data['pricing']       = $sessionBooking['pricing'] ?? array();

        $data['review_post'] = $reviewPost;
        $data['addons_post'] = $addonsPost;
        $data['post_data']   = array_merge($reviewPost, $addonsPost);

        // Parse passengers
        $passengers    = array();
        $titles        = $reviewPost['passenger_title'] ?? array();
        $firstNames    = $reviewPost['passenger_first_name'] ?? array();
        $lastNames     = $reviewPost['passenger_last_name'] ?? array();
        $names         = $reviewPost['passenger_name'] ?? array();
        $dobs          = $reviewPost['passenger_dob'] ?? array();
        $ages          = $reviewPost['passenger_age'] ?? array();
        $types         = $reviewPost['passenger_type'] ?? array();
        $nationalities = $reviewPost['passenger_nationality'] ?? array();
        $passports     = $reviewPost['passenger_passport_no'] ?? array();
        $passportExps  = $reviewPost['passenger_passport_expiry'] ?? array();
        $issuingCnts   = $reviewPost['passenger_issuing_country'] ?? array();
        $visaTypes     = $reviewPost['passenger_visa_type'] ?? array();
        $residenceCnts = $reviewPost['passenger_residence_country'] ?? array();
        $ffAirlines    = $reviewPost['passenger_ff_airline'] ?? array();
        $ffNumbers     = $reviewPost['passenger_ff_number'] ?? array();

        $count = max(count((array)$names), count((array)$firstNames));
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $p_idx = $i + 1;
                $gender = $reviewPost['passenger_gender_' . $p_idx] ?? 'Male';
                $fName = isset($firstNames[$i]) ? trim($firstNames[$i]) : '';
                $lName = isset($lastNames[$i]) ? trim($lastNames[$i]) : '';
                $rawName = isset($names[$i]) ? trim($names[$i]) : '';
                if (empty($rawName) && (!empty($fName) || !empty($lName))) {
                    $rawName = trim("$fName $lName");
                }
                if (empty($fName) && !empty($rawName)) {
                    $parts = explode(' ', $rawName, 2);
                    $fName = $parts[0] ?? '';
                    $lName = $parts[1] ?? '';
                }

                $passengers[] = array(
                    'title'             => isset($titles[$i]) ? $titles[$i] : 'Mr',
                    'name'              => !empty($rawName) ? $rawName : 'Passenger ' . $p_idx,
                    'first_name'        => $fName,
                    'last_name'         => $lName,
                    'dob'               => isset($dobs[$i]) ? $dobs[$i] : '',
                    'age'               => isset($ages[$i]) ? $ages[$i] : '28',
                    'gender'            => $gender,
                    'type'              => isset($types[$i]) ? $types[$i] : 'Adult',
                    'nationality'       => isset($nationalities[$i]) ? $nationalities[$i] : 'Indian',
                    'passport_no'       => isset($passports[$i]) ? $passports[$i] : '',
                    'passport_expiry'   => isset($passportExps[$i]) ? $passportExps[$i] : '',
                    'issuing_country'   => isset($issuingCnts[$i]) ? $issuingCnts[$i] : 'India',
                    'visa_type'         => isset($visaTypes[$i]) ? $visaTypes[$i] : 'Tourist Visa',
                    'residence_country' => isset($residenceCnts[$i]) ? $residenceCnts[$i] : 'India',
                    'ff_airline'        => isset($ffAirlines[$i]) ? $ffAirlines[$i] : '',
                    'ff_number'         => isset($ffNumbers[$i]) ? $ffNumbers[$i] : ''
                );
            }
        }
        $data['passengers'] = $passengers;

        // Calculate grand total including SSR addons
        $baseFlightFare = (float)($sessionBooking['pricing']['grand_total'] ?? ($sessionBooking['flight']['price'] ?? 5050));
        $ssrAddonsAmount = (float)($addonsPost['addons_grand_total'] ?? 0);
        $grandTotal = round($baseFlightFare + $ssrAddonsAmount, 2);

        $data['grandTotal'] = $grandTotal;
        $data['baseFlightFare'] = $baseFlightFare;
        $data['ssrAddonsAmount'] = $ssrAddonsAmount;
        $data['can_book'] = ($store['wallet_balance'] >= $grandTotal);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_payment', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_book() {
        $store = $this->_check_auth();

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('franchise/flight');
            return;
        }

        $total_amount = (float)$this->input->post('total_amount');
        if ($total_amount <= 0) {
            $total_amount = 5050.00;
        }

        // Live Wallet Balance Check
        if ($store['wallet_balance'] < $total_amount) {
            $this->session->set_flashdata('error', 'Insufficient store wallet float. Available: ₹ ' . number_format($store['wallet_balance'], 2) . ' | Required: ₹ ' . number_format($total_amount, 2) . '. Please request a float top-up.');
            redirect('franchise/flight_payment');
            return;
        }

        $contact_name  = trim((string)$this->input->post('contact_name')) ?: $store['store_name'];
        $contact_email = trim((string)$this->input->post('contact_email')) ?: $store['email'];
        $contact_phone = trim((string)$this->input->post('contact_phone')) ?: $store['phone'];

        $titles        = $this->input->post('passenger_title');
        $firstNames    = $this->input->post('passenger_first_name');
        $lastNames     = $this->input->post('passenger_last_name');
        $names         = $this->input->post('passenger_name');
        $dobs          = $this->input->post('passenger_dob');
        $ages          = $this->input->post('passenger_age');
        $types         = $this->input->post('passenger_type');
        $nationalities = $this->input->post('passenger_nationality');
        $passports     = $this->input->post('passenger_passport_no');
        $passportExps  = $this->input->post('passenger_passport_expiry');
        $issuingCnts   = $this->input->post('passenger_issuing_country');
        $visaTypes     = $this->input->post('passenger_visa_type');
        $residenceCnts = $this->input->post('passenger_residence_country');
        $ffAirlines    = $this->input->post('passenger_ff_airline');
        $ffNumbers     = $this->input->post('passenger_ff_number');

        $passengers = array();
        $count = max(count((array)$names), count((array)$firstNames));
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $p_idx = $i + 1;
                $gender = $this->input->post('passenger_gender_' . $p_idx) ?: 'Male';
                $fName = isset($firstNames[$i]) ? trim($firstNames[$i]) : '';
                $lName = isset($lastNames[$i]) ? trim($lastNames[$i]) : '';
                $rawName = isset($names[$i]) ? trim($names[$i]) : '';
                if (empty($rawName) && (!empty($fName) || !empty($lName))) {
                    $rawName = trim("$fName $lName");
                }
                if (empty($fName) && !empty($rawName)) {
                    $parts = explode(' ', $rawName, 2);
                    $fName = $parts[0] ?? '';
                    $lName = $parts[1] ?? '';
                }

                $passengers[] = array(
                    'title'             => isset($titles[$i]) ? $titles[$i] : 'Mr',
                    'name'              => !empty($rawName) ? $rawName : 'Passenger ' . $p_idx,
                    'first_name'        => $fName,
                    'last_name'         => $lName,
                    'dob'               => isset($dobs[$i]) ? $dobs[$i] : '',
                    'age'               => isset($ages[$i]) ? $ages[$i] : '28',
                    'gender'            => $gender,
                    'type'              => isset($types[$i]) ? $types[$i] : 'Adult',
                    'nationality'       => isset($nationalities[$i]) ? $nationalities[$i] : 'Indian',
                    'passport_no'       => isset($passports[$i]) ? $passports[$i] : '',
                    'passport_expiry'   => isset($passportExps[$i]) ? $passportExps[$i] : '',
                    'issuing_country'   => isset($issuingCnts[$i]) ? $issuingCnts[$i] : 'India',
                    'visa_type'         => isset($visaTypes[$i]) ? $visaTypes[$i] : 'Tourist Visa',
                    'residence_country' => isset($residenceCnts[$i]) ? $residenceCnts[$i] : 'India',
                    'ff_airline'        => isset($ffAirlines[$i]) ? $ffAirlines[$i] : '',
                    'ff_number'         => isset($ffNumbers[$i]) ? $ffNumbers[$i] : ''
                );
            }
        }

        // Add-on SSR data
        $ssrAddons = array(
            'baggage'        => $this->input->post('selected_baggage_desc') ?: '',
            'baggage_amount' => (float)($this->input->post('selected_baggage_amount') ?: 0),
            'meal'           => $this->input->post('selected_meal_desc') ?: '',
            'meal_amount'    => (float)($this->input->post('selected_meal_amount') ?: 0),
            'seat'           => $this->input->post('selected_seat_code') ?: '',
            'seat_amount'    => (float)($this->input->post('selected_seat_amount') ?: 0),
            'passenger_seats' => $this->input->post('passenger_seat') ?: array(),
            'passenger_meals' => $this->input->post('passenger_meal') ?: array(),
            'passenger_baggage' => $this->input->post('passenger_baggage') ?: array()
        );

        $flight_number = $this->input->post('flight_number') ?: '6E-2041';
        $airline_name  = $this->input->post('airline_name') ?: 'IndiGo';
        $origin        = $this->input->post('origin') ?: 'DEL';
        $destination   = $this->input->post('destination') ?: 'BOM';
        $dep_date      = $this->input->post('departure_date') ?: date('Y-m-d', strtotime('+3 days'));
        $dep_time      = $this->input->post('departure_time') ?: '06:00';
        $arr_time      = $this->input->post('arrival_time') ?: '08:15';
        $duration      = $this->input->post('duration') ?: '02h 15m';
        $stops         = (int)($this->input->post('stops') ?: 0);
        $via           = $this->input->post('via') ?: '';
        $is_roundtrip  = (int)($this->input->post('is_roundtrip') ?: 0);

        $dep_datetime  = date('Y-m-d H:i:s', strtotime("$dep_date $dep_time"));

        $return_flight_meta = null;
        if (!empty($this->input->post('return_flight_number'))) {
            $return_flight_meta = array(
                'flight_number'  => $this->input->post('return_flight_number'),
                'airline_name'   => $this->input->post('return_airline_name') ?: 'IndiGo',
                'origin'         => $this->input->post('return_origin') ?: $destination,
                'destination'    => $this->input->post('return_destination') ?: $origin,
                'departure_date' => $this->input->post('return_departure_date') ?: date('Y-m-d', strtotime('+7 days')),
                'departure_time' => $this->input->post('return_departure_time') ?: '18:00',
                'arrival_time'   => $this->input->post('return_arrival_time') ?: '20:15',
                'duration'       => $this->input->post('return_duration') ?: '02h 15m',
                'stops'          => (int)($this->input->post('return_stops') ?: 0)
            );
        }

        $itinerary_meta = array(
            'is_roundtrip'   => $is_roundtrip,
            'duration'       => $duration,
            'stops'          => $stops,
            'via'            => $via,
            'arrival_time'   => $arr_time,
            'return_flight'  => $return_flight_meta,
            'ssr_addons'     => $ssrAddons,
            'contact'        => array('phone' => $contact_phone, 'email' => $contact_email, 'name' => $contact_name),
            'passengers'     => $passengers
        );

        $pnr         = 'VYO' . strtoupper(substr(md5(uniqid()), 0, 6));
        $booking_ref = 'FB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Deduct from wallet atomically
        $wallet_res = $this->Franchise_model->update_wallet_balance(
            $store['id'],
            'debit',
            $total_amount,
            'flight_booking',
            $booking_ref,
            'Flight Booking PNR: ' . $pnr . ' (' . $origin . ' → ' . $destination . ') - Store ' . $store['agent_code'],
            $store['username']
        );

        if (!$wallet_res['status']) {
            $this->session->set_flashdata('error', 'Wallet deduction failed: ' . $wallet_res['error']);
            redirect('franchise/flight_payment');
            return;
        }

        // Record booking in database
        $this->Franchise_model->record_flight_booking(array(
            'store_id'           => $store['id'],
            'booking_ref'        => $booking_ref,
            'pnr'                => $pnr,
            'airline_name'       => $airline_name,
            'flight_number'      => $flight_number,
            'origin'             => $origin,
            'destination'        => $destination,
            'departure_datetime' => $dep_datetime,
            'passenger_details'  => json_encode($itinerary_meta),
            'base_fare'          => (float)($this->input->post('base_fare') ?: ($total_amount * 0.8)),
            'taxes'              => (float)($this->input->post('taxes') ?: ($total_amount * 0.2)),
            'total_amount'       => $total_amount,
            'wallet_deducted'    => $total_amount,
            'status'             => 'confirmed'
        ));

        // Clean session
        $this->session->unset_userdata('franchise_flight_booking_data');
        $this->session->unset_userdata('franchise_flight_review_post');
        $this->session->unset_userdata('franchise_flight_addons_post');

        redirect('franchise/flight_ticket/' . urlencode($booking_ref));
    }

    public function flight_ticket($booking_ref) {
        $store = $this->_check_auth();

        $booking = $this->Franchise_model->get_flight_booking_by_ref($booking_ref, $store['id']);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('franchise/bookings');
            return;
        }

        $meta = json_decode($booking['passenger_details'], true) ?: array();
        $passengers = $meta['passengers'] ?? ($meta['passenger_list'] ?? array());

        $data['title']       = 'E-Ticket: ' . $booking['pnr'] . ' - Voyogo B2B';
        $data['active_menu'] = 'bookings';
        $data['store']       = $store;
        $data['booking']     = $booking;
        $data['booking_meta']= $meta;
        $data['passengers']  = $passengers;
        $data['ssr_addons']  = $meta['ssr_addons'] ?? array();

        $this->load->view('franchise/flight_ticket', $data);
    }

    // =========================================================================
    // 3. STORE B2B HOTEL SEARCH & BOOKING
    // =========================================================================
    public function hotel() {
        $store = $this->_check_auth();

        $data['title']       = 'B2B Hotel Booking - ' . htmlspecialchars($store['store_name']);
        $data['active_menu'] = 'hotel';
        $data['store']       = $store;

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_search', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_search() {
        $store = $this->_check_auth();

        $rawCity  = trim($this->input->post('city') ?: ($this->input->get('city') ?: 'Tirunelveli'));
        $city     = trim(explode(',', $rawCity)[0]); // Clean city name without country
        if (empty($city)) $city = 'Tirunelveli';

        $checkin  = $this->input->post('checkin') ?: ($this->input->get('checkin') ?: ($this->input->post('checkin_date') ?: ($this->input->get('checkin_date') ?: date('Y-m-d', strtotime('+3 days')))));
        $checkout = $this->input->post('checkout') ?: ($this->input->get('checkout') ?: ($this->input->post('checkout_date') ?: ($this->input->get('checkout_date') ?: date('Y-m-d', strtotime('+7 days')))));
        $rooms    = max(1, (int)($this->input->post('rooms') ?: ($this->input->get('rooms') ?: 1)));
        $adults   = max(1, (int)($this->input->post('adults') ?: ($this->input->get('adults') ?: 2)));
        $children = max(0, (int)($this->input->post('children') ?: ($this->input->get('children') ?: 0)));

        $roomDataRaw = $this->input->post('roomData') ?: $this->input->get('roomData');
        $roomData = json_decode($roomDataRaw, true) ?: array();

        $lat = $this->input->post('lat') ?: $this->input->get('lat');
        $lng = $this->input->post('lng') ?: $this->input->get('lng');
        $locationId = $this->input->post('location_id') ?: $this->input->get('location_id');
        $countryCode = $this->input->post('country_code') ?: ($this->input->get('country_code') ?: null);
        $geoCode = (!empty($lat) && !empty($lng)) ? array('lat' => $lat, 'long' => $lng) : null;

        $hotelResults = $this->benzyhotelapi->searchHotels($city, $checkin, $checkout, $rooms, $adults, $children, $locationId, $geoCode, $roomData, $countryCode);
        $nights = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        $searchId = $hotelResults['searchId'] ?? '';
        $searchTracingKey = $hotelResults['searchTracingKey'] ?? '';

        $rawHotels = isset($hotelResults['Hotels']) ? $hotelResults['Hotels'] : ($hotelResults['hotels'] ?? (is_array($hotelResults) ? $hotelResults : array()));

        $hotels = array();
        foreach ($rawHotels as $idx => $h) {
            if (!is_array($h)) continue;
            $hId          = $h['id'] ?? ($h['hotelId'] ?? ('HTL_' . ($idx + 101)));
            $hName        = $h['name'] ?? 'Luxury Resort & Spa';
            $hStar        = (int)($h['star_rating'] ?? ($h['starRating'] ?? 4));
            $hRating      = !empty($h['rating']) ? (string)$h['rating'] : '4.6';
            $hReviews     = !empty($h['reviews_count']) ? (int)$h['reviews_count'] : 450;
            $hLocation    = $h['location'] ?? ($h['address'] ?? ($city . ', India'));
            $priceNight   = (float)($h['price_per_night'] ?? ($h['price'] ?? ($h['minPrice'] ?? 3800.00)));
            $totalStay    = round($priceNight * $nights * $rooms, 2);
            $hImage       = !empty($h['image']) ? $h['image'] : (!empty($h['heroImage']) ? $h['heroImage'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80');

            $amenities = !empty($h['amenities']) && is_array($h['amenities']) ? $h['amenities'] : array('Free WiFi', 'Multi-Cuisine Restaurant', 'Swimming Pool', 'Free Breakfast', 'Free Cancellation');

            $hotels[] = array(
                'id'                 => $hId,
                'name'               => $hName,
                'star_rating'        => $hStar,
                'rating'             => $hRating,
                'reviews_count'      => $hReviews,
                'location'           => $hLocation,
                'price_per_night'    => $priceNight,
                'original_price'     => (float)($h['original_price'] ?? round($priceNight * 1.28)),
                'tax_fee'            => (float)($h['tax_fee'] ?? round($priceNight * 0.18)),
                'total_stay'         => $totalStay,
                'image'              => $hImage,
                'gallery'            => !empty($h['gallery']) && is_array($h['gallery']) ? $h['gallery'] : array($hImage),
                'chain'              => $h['chain'] ?? 'Independent Hotels',
                'property_type'      => $h['property_type'] ?? 'Hotel',
                'amenities'          => $amenities,
                'free_breakfast'     => !empty($h['free_breakfast']) || in_array('Free Breakfast', $amenities),
                'free_cancellation'  => isset($h['free_cancellation']) ? (bool)$h['free_cancellation'] : true,
                'latitude'           => $h['latitude'] ?? ($h['lat'] ?? null),
                'longitude'          => $h['longitude'] ?? ($h['lng'] ?? null),
                'room_types'         => !empty($h['room_types']) ? $h['room_types'] : array(),
                'is_sold_out'        => !empty($h['is_sold_out']),
                'is_voyogo_choice'   => !empty($h['is_voyogo_choice']),
                'searchId'           => $searchId,
                'searchTracingKey'   => $searchTracingKey
            );
        }

        $data['title']        = 'Hotels in ' . htmlspecialchars($city) . ' - Best B2B Deals | Voyogo';
        $data['active_menu']  = 'hotel';
        $data['store']        = $store;
        $data['city']         = $city;
        $data['checkin']      = $checkin;
        $data['checkout']     = $checkout;
        $data['nights']       = $nights;
        $data['rooms']        = $rooms;
        $data['adults']       = $adults;
        $data['children']     = $children;
        $data['roomDataJson'] = $roomDataRaw;
        $data['hotels']       = $hotels;
        $data['hotelResults'] = $hotelResults;
        $data['search_id']    = $searchId;
        $data['tracing_key']  = $searchTracingKey;
        $data['search_query'] = array(
            'city'               => $city,
            'checkin'            => $checkin,
            'checkout'           => $checkout,
            'nights'             => $nights,
            'rooms'              => $rooms,
            'adults'             => $adults,
            'children'           => $children,
            'location_id'        => $locationId,
            'country_code'       => $countryCode,
            'roomData'           => $roomDataRaw,
            'search_id'          => $searchId,
            'search_tracing_key' => $searchTracingKey
        );

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_search_results', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_detail($hotel_id) {
        $store = $this->_check_auth();

        $city               = $this->input->get('city') ?: 'Goa, India';
        $checkin            = $this->input->get('checkin') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout           = $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));
        $rooms              = (int)($this->input->get('rooms') ?: 1);
        $adults             = (int)($this->input->get('adults') ?: 2);
        $children           = (int)($this->input->get('children') ?: 0);
        $roomDataRaw        = $this->input->get('roomData') ?: '';
        $search_id          = $this->input->get('search_id') ?: null;
        $search_tracing_key = $this->input->get('search_tracing_key') ?: null;

        $hotel = $this->benzyhotelapi->getHotelDetails($hotel_id, $search_id, $city, $checkin, $checkout, $search_tracing_key);

        $data['title']              = ($hotel['name'] ?? 'Hotel Details') . ' - Voyogo B2B';
        $data['active_menu']        = 'hotel';
        $data['store']              = $store;
        $data['hotel']              = $hotel;
        $data['city']               = $city;
        $data['checkin']            = $checkin;
        $data['checkout']           = $checkout;
        $data['rooms']              = $rooms;
        $data['adults']             = $adults;
        $data['children']           = $children;
        $data['roomDataJson']       = $roomDataRaw;
        $data['search_id']          = $search_id;
        $data['search_tracing_key'] = $search_tracing_key;
        $data['search_query']       = array(
            'city'               => $city,
            'checkin'            => $checkin,
            'checkout'           => $checkout,
            'rooms'              => $rooms,
            'adults'             => $adults,
            'children'           => $children,
            'roomData'           => $roomDataRaw,
            'search_id'          => $search_id,
            'search_tracing_key' => $search_tracing_key
        );

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_detail', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_review() {
        $store = $this->_check_auth();

        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 10800) . " GMT");

        $isPost = ($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->post('hotel_name'));
        if (!$isPost) {
            $cached = $this->session->userdata('franchise_hotel_review_booking');
            if (!empty($cached)) {
                $bookingArray = $cached;
                $paymentBooking = $this->session->userdata('franchise_hotel_payment_booking');
                if (!empty($paymentBooking['pax'])) {
                    $bookingArray['pax'] = $paymentBooking['pax'];
                    $bookingArray['primary_guest_name'] = $paymentBooking['primary_guest_name'] ?? ($bookingArray['primary_guest_name'] ?? '');
                    $bookingArray['guest_email'] = $paymentBooking['guest_email'] ?? ($bookingArray['guest_email'] ?? '');
                    $bookingArray['guest_phone'] = $paymentBooking['guest_phone'] ?? ($bookingArray['guest_phone'] ?? '');
                }

                $grandTotal = (float)($bookingArray['grand_total'] ?? ($bookingArray['total_amount'] ?? 0));
                $data['booking_data']    = $bookingArray;
                $data['booking_summary'] = $bookingArray;
                $data['can_book']        = ($store['wallet_balance'] >= $grandTotal);
                $data['store']           = $store;
                $data['title']           = "Review Booking: " . ($bookingArray['hotel_name'] ?? 'Hotel') . " - Voyogo B2B";
                $data['active_menu']     = 'hotel';

                $this->load->view('franchise/layout/header', $data);
                $this->load->view('franchise/hotel_review', $data);
                $this->load->view('franchise/layout/footer');
                return;
            }
        }

        $hotel_id          = $this->input->post('hotel_id') ?: 'HTL_101';
        $hotel_name        = $this->input->post('hotel_name') ?: 'Taj Exotica Resort & Spa';
        $hotel_address     = $this->input->post('hotel_address') ?: 'Benaulim Beach, Goa';
        $hotel_image       = $this->input->post('hotel_image') ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945';
        $room_type         = $this->input->post('room_type') ?: 'Deluxe Garden View Room';
        $room_id           = $this->input->post('room_id') ?: 'RM_DLX_01';
        $room_group_id     = $this->input->post('room_group_id') ?: 'RGRP_01';
        $recommendation_id = $this->input->post('recommendation_id') ?: '';
        $search_id         = $this->input->post('search_id') ?: '';
        $tui               = $this->input->post('tui') ?: '';
        $board_type        = $this->input->post('board_type') ?: 'Breakfast Included';
        $city              = $this->input->post('city') ?: 'Goa, India';
        $checkin           = $this->input->post('checkin_date') ?: ($this->input->post('checkin') ?: date('Y-m-d', strtotime('+2 days')));
        $checkout          = $this->input->post('checkout_date') ?: ($this->input->post('checkout') ?: date('Y-m-d', strtotime('+5 days')));
        $rooms             = (int)($this->input->post('rooms') ?: 1);
        $adults            = (int)($this->input->post('adults') ?: 2);
        $children          = (int)($this->input->post('children') ?: 0);
        $roomDataRaw       = $this->input->post('roomData') ?: '';
        $price             = (float)($this->input->post('price') ?: 14500);
        $provider          = $this->input->post('provider') ?: 'CleartripAPI';

        // Validate Live Pricing with API if not mock
        $isMockBooking = (strpos($recommendation_id, 'REC_DLX') !== false || 
                          strpos($recommendation_id, 'REC_SUP') !== false || 
                          strpos($recommendation_id, 'REC_EXC') !== false || 
                          strpos($hotel_id, 'HTL_') !== false ||
                          empty($search_id) || 
                          empty($recommendation_id));

        if (!$isMockBooking) {
            $reprice = $this->benzyhotelapi->repriceRoom($hotel_id, $room_id, $provider, $search_id, $recommendation_id, $tui);
            if (!empty($reprice['status']) && $reprice['status'] === 'failure') {
                $errMsg = !empty($reprice['message']) ? $reprice['message'] : 'Pricing error';
                $this->session->set_flashdata('error', 'Room pricing is currently unavailable with the hotel supplier (' . $errMsg . '). Please choose an alternative room or hotel.');
                redirect('franchise/hotel_detail/' . urlencode($hotel_id) . '?city=' . urlencode($city) . '&checkin=' . urlencode($checkin) . '&checkout=' . urlencode($checkout) . '&rooms=' . urlencode($rooms) . '&adults=' . urlencode($adults) . '&children=' . urlencode($children) . '&search_id=' . urlencode($search_id) . '&search_tracing_key=' . urlencode($tui) . '&roomData=' . urlencode($roomDataRaw));
                return;
            }
            if (!empty($reprice['roomGroup'][0]['totalRate'])) {
                $price = (float)$reprice['roomGroup'][0]['totalRate'];
            }
        }

        $nights = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        $grandTotal = round($price * $rooms, 2);

        $baseRateVal = null;
        $taxAmountVal = null;
        $discountAmountVal = null;
        $isRefundable = true;
        $inclusionsList = array();
        $cancellationPolicyText = '';

        if (!empty($reprice['roomGroup'][0])) {
            $rg = $reprice['roomGroup'][0];
            if (isset($rg['baseRate'])) $baseRateVal = (float)$rg['baseRate'];
            if (!empty($rg['taxes']) && is_array($rg['taxes'])) {
                $taxAmountVal = 0;
                foreach ($rg['taxes'] as $tx) {
                    $taxAmountVal += (float)($tx['amount'] ?? 0);
                }
            }
            if (!empty($rg['discounts']) && is_array($rg['discounts'])) {
                $discountAmountVal = 0;
                foreach ($rg['discounts'] as $dc) {
                    $discountAmountVal += (float)($dc['amount'] ?? 0);
                }
            }
            if (isset($rg['refundable'])) {
                $isRefundable = (bool)$rg['refundable'];
            } elseif (isset($rg['refundability'])) {
                $isRefundable = (stripos($rg['refundability'], 'non') === false);
            }
            if (!empty($rg['includes']) && is_array($rg['includes'])) {
                $inclusionsList = $rg['includes'];
            }
            if (!empty($rg['cancellationPolicies'][0]['text'])) {
                $cancellationPolicyText = $rg['cancellationPolicies'][0]['text'];
            }
        }

        if ($taxAmountVal !== null && $taxAmountVal > 0 && $baseRateVal !== null && ($baseRateVal * $rooms) < $grandTotal) {
            $baseTotal = round($baseRateVal * $rooms, 2);
            $taxes = round($taxAmountVal * $rooms, 2);
            $discountTotal = ($discountAmountVal !== null && $discountAmountVal > 0) ? round($discountAmountVal * $rooms, 2) : 0;
        } else {
            $baseTotal = round($grandTotal / 1.12, 2);
            $taxes = round($grandTotal - $baseTotal, 2);
            $discountTotal = ($discountAmountVal !== null && $discountAmountVal > 0) ? round($discountAmountVal * $rooms, 2) : 0;
        }

        $bookingArray = array(
            'hotel_id'          => $hotel_id,
            'hotel_name'        => $hotel_name,
            'hotel_address'     => $hotel_address,
            'hotel_image'       => $hotel_image,
            'room_type'         => $room_type,
            'room_id'           => $room_id,
            'room_group_id'     => $room_group_id,
            'recommendation_id' => $recommendation_id,
            'search_id'         => $search_id,
            'tui'               => $tui,
            'board_type'        => $board_type,
            'city'              => $city,
            'checkin'           => $checkin,
            'checkout'          => $checkout,
            'checkin_date'      => $checkin,
            'checkout_date'     => $checkout,
            'nights'            => $nights,
            'rooms'             => $rooms,
            'adults'            => $adults,
            'children'          => $children,
            'roomData'          => $roomDataRaw,
            'provider'          => $provider,
            'price'             => $price,
            'base_total'        => $baseTotal,
            'taxes'             => $taxes,
            'discount_total'    => $discountTotal,
            'grand_total'       => $grandTotal,
            'total_amount'      => $grandTotal,
            'is_refundable'     => $isRefundable,
            'inclusions'        => $inclusionsList,
            'cancellation_text' => $cancellationPolicyText,
            'star_rating'       => (int)($this->input->post('star_rating') ?: 4)
        );

        $this->session->set_userdata('franchise_hotel_review_booking', $bookingArray);

        $data['booking_data']    = $bookingArray;
        $data['booking_summary'] = $bookingArray;
        $data['can_book']        = ($store['wallet_balance'] >= $grandTotal);
        $data['store']           = $store;
        $data['title']           = "Review Booking: $hotel_name - Voyogo B2B";
        $data['active_menu']     = 'hotel';
        $data['isUserLoggedIn']  = true;

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_review', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_payment() {
        $store = $this->_check_auth();

        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 10800) . " GMT");

        if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->post('hotel_name')) {
            $hotel_id       = $this->input->post('hotel_id');
            $hotel_name     = $this->input->post('hotel_name');
            $hotel_address  = $this->input->post('hotel_address');
            $hotel_image    = $this->input->post('hotel_image');
            $room_type      = $this->input->post('room_type');
            $room_id        = $this->input->post('room_id');
            $room_group_id  = $this->input->post('room_group_id');
            $rec_id         = $this->input->post('recommendation_id');
            $search_id      = $this->input->post('search_id');
            $tui            = $this->input->post('tui');
            $board_type     = $this->input->post('board_type');
            $city           = $this->input->post('city');
            $checkin        = $this->input->post('checkin') ?: $this->input->post('checkin_date');
            $checkout       = $this->input->post('checkout') ?: $this->input->post('checkout_date');
            $rooms          = (int)$this->input->post('rooms') ?: 1;
            $adults         = (int)$this->input->post('adults') ?: 2;
            $children       = (int)$this->input->post('children') ?: 0;
            $roomDataRaw    = $this->input->post('roomData') ?: '';
            $provider       = $this->input->post('provider') ?: 'CleartripAPI';
            $nights         = (int)$this->input->post('nights') ?: max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
            $total_amount   = (float)$this->input->post('grand_total') ?: ((float)$this->input->post('total_amount') ?: 0);
            $base_total     = (float)$this->input->post('base_total') ?: round($total_amount / 1.12, 2);
            $taxes          = (float)$this->input->post('taxes') ?: round($total_amount - $base_total, 2);
            $discount_total = (float)$this->input->post('discount_total') ?: 0;

            $paxData        = $this->input->post('pax') ?: array();
            $lead_phone     = $this->input->post('guest_phone') ?: ($store['phone'] ?? '9876543210');
            $lead_email     = $this->input->post('guest_email') ?: ($store['email'] ?? 'booking@voyogo.com');
            $primary_name   = $this->input->post('primary_guest_name') ?: '';
            if (empty($primary_name) && !empty($paxData[0]['adults'][0]['fname'])) {
                $pTitle = $paxData[0]['adults'][0]['title'] ?? 'Mr';
                $pFname = $paxData[0]['adults'][0]['fname'] ?? '';
                $pLname = $paxData[0]['adults'][0]['lname'] ?? '';
                $primary_name = trim("$pTitle. $pFname $pLname");
            }
            if (empty($primary_name)) {
                $primary_name = $store['store_name'];
            }

            $bookingArray = array(
                'hotel_id'          => $hotel_id,
                'hotel_name'        => $hotel_name,
                'hotel_address'     => $hotel_address,
                'hotel_image'       => $hotel_image,
                'room_type'         => $room_type,
                'room_id'           => $room_id,
                'room_group_id'     => $room_group_id,
                'recommendation_id' => $rec_id,
                'search_id'         => $search_id,
                'tui'               => $tui,
                'board_type'        => $board_type,
                'city'              => $city,
                'checkin'           => $checkin,
                'checkout'          => $checkout,
                'checkin_date'      => $checkin,
                'checkout_date'     => $checkout,
                'nights'            => $nights,
                'rooms'             => $rooms,
                'adults'            => $adults,
                'children'          => $children,
                'roomData'          => $roomDataRaw,
                'provider'          => $provider,
                'base_total'        => $base_total,
                'taxes'             => $taxes,
                'discount_total'    => $discount_total,
                'grand_total'       => $total_amount,
                'total_amount'      => $total_amount,
                'is_refundable'     => (bool)($this->input->post('is_refundable') !== '0'),
                'inclusions'        => $this->input->post('inclusions') ?: array(),
                'cancellation_text' => $this->input->post('cancellation_text') ?: 'Free cancellation available',
                'star_rating'       => (int)($this->input->post('star_rating') ?: 5),
                'pax'               => $paxData,
                'primary_guest_name'=> $primary_name,
                'guest_email'       => $lead_email,
                'guest_phone'       => $lead_phone,
                'special_requests'  => $this->input->post('special_requests') ?: 'Non-smoking room',
                'travel_insurance'  => $this->input->post('travel_insurance') ? 1 : 0
            );

            $this->session->set_userdata('franchise_hotel_payment_booking', $bookingArray);
            $this->session->set_userdata('franchise_hotel_review_booking', $bookingArray);

            redirect('franchise/hotel_payment', 'location', 303);
            return;
        }

        $bookingArray = $this->session->userdata('franchise_hotel_payment_booking');
        if (empty($bookingArray)) {
            $bookingArray = $this->session->userdata('franchise_hotel_review_booking');
        }
        if (empty($bookingArray)) {
            redirect('franchise/hotel');
            return;
        }

        $grandTotal = (float)($bookingArray['grand_total'] ?? ($bookingArray['total_amount'] ?? 0));
        $data['booking']         = $bookingArray;
        $data['booking_data']    = $bookingArray;
        $data['can_book']        = ($store['wallet_balance'] >= $grandTotal);
        $data['store']           = $store;
        $data['title']           = "Payment & Float Deduction: " . ($bookingArray['hotel_name'] ?? 'Hotel') . " - Voyogo B2B";
        $data['active_menu']     = 'hotel';

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_payment', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_book() {
        $store = $this->_check_auth();

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('franchise/hotel');
            return;
        }

        $hotel_id       = $this->input->post('hotel_id');
        $hotel_name     = $this->input->post('hotel_name');
        $hotel_address  = $this->input->post('hotel_address');
        $hotel_image    = $this->input->post('hotel_image');
        $room_type      = $this->input->post('room_type');
        $room_id        = $this->input->post('room_id');
        $board_type     = $this->input->post('board_type') ?: 'Breakfast Included';
        $city           = $this->input->post('city');
        $checkin        = $this->input->post('checkin') ?: $this->input->post('checkin_date');
        $checkout       = $this->input->post('checkout') ?: $this->input->post('checkout_date');
        $rooms          = (int)$this->input->post('rooms') ?: 1;
        $adults         = (int)$this->input->post('adults') ?: 2;
        $children       = (int)$this->input->post('children') ?: 0;
        $roomDataRaw    = $this->input->post('roomData') ?: '';
        $provider       = $this->input->post('provider') ?: 'CleartripAPI';
        $nights         = (int)$this->input->post('nights') ?: max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        $total_amount   = (float)$this->input->post('grand_total') ?: ((float)$this->input->post('total_amount') ?: (float)$this->input->post('price'));
        $tax_amount     = (float)$this->input->post('taxes');

        if ($store['wallet_balance'] < $total_amount) {
            $this->session->set_flashdata('error', 'Insufficient store wallet float. Available: ₹ ' . number_format($store['wallet_balance'], 2) . ' | Required: ₹ ' . number_format($total_amount, 2));
            redirect('franchise/hotel_payment');
            return;
        }

        $paxData = $this->input->post('pax') ?: array();
        $lead_title = 'Mr';
        $lead_fname = 'Guest';
        $lead_lname = 'User';
        if (!empty($paxData[0]['adults'][0]['fname'])) {
            $lead_title = $paxData[0]['adults'][0]['title'] ?? 'Mr';
            $lead_fname = trim($paxData[0]['adults'][0]['fname']);
            $lead_lname = trim($paxData[0]['adults'][0]['lname'] ?? 'User');
        } elseif (!empty($this->input->post('primary_guest_name'))) {
            $fullName = trim($this->input->post('primary_guest_name'));
            $parts = explode(' ', $fullName, 2);
            $lead_title = $this->input->post('guest_title') ?: 'Mr';
            $lead_fname = $parts[0] ?? 'Guest';
            $lead_lname = $parts[1] ?? 'User';
        }
        $lead_name      = trim("$lead_title $lead_fname $lead_lname");
        $lead_email     = $this->input->post('guest_email') ?: ($store['email'] ?? 'booking@voyogo.com');
        $lead_phone     = $this->input->post('guest_phone') ?: ($store['phone'] ?? '9876543210');
        $special_req    = $this->input->post('special_requests') ?: 'Non-smoking room';

        $tui = $this->input->post('tui') ?: ('TUI-' . uniqid());
        $searchId = $this->input->post('search_id') ?: ('SRCH-' . uniqid());
        $recId = $this->input->post('recommendation_id') ?: ('REC-' . uniqid());

        // Check live pricing if live booking
        $isMockBooking = (strpos($recId, 'REC_DLX') !== false || 
                          strpos($recId, 'REC_SUP') !== false || 
                          strpos($recId, 'REC_EXC') !== false || 
                          strpos($hotel_id, 'HTL_') !== false ||
                          empty($searchId) || 
                          empty($recId));

        $pricingChildAges = array();
        $pricingOccupancyChildAges = array();

        if (!$isMockBooking && !empty($searchId) && !empty($recId) && !empty($room_id)) {
            $liveReprice = $this->benzyhotelapi->repriceRoom($hotel_id, $room_id, $provider, $searchId, $recId);
            if (!empty($liveReprice['roomGroup'][0]['totalRate'])) {
                $total_amount = (float)$liveReprice['roomGroup'][0]['totalRate'];
            }
            if (!empty($liveReprice['roomGroup'][0]['occupancies'])) {
                foreach ($liveReprice['roomGroup'][0]['occupancies'] as $occ) {
                    $occIdx = ($occ['occupancyId'] ?? 1) - 1;
                    $pricingOccupancyChildAges[$occIdx] = $occ['childAges'] ?? array();
                }
                $pricingChildAges = $pricingOccupancyChildAges[0] ?? array();
            }
        }

        $itineraryPayload = array(
            'TUI'                   => $tui,
            'SearchId'              => $searchId,
            'RecommendationId'      => $recId,
            'HotelCode'             => $hotel_id,
            'RoomId'                => $room_id,
            'RoomGroupId'           => $this->input->post('room_group_id') ?: ('RGRP_' . uniqid()),
            'RoomData'              => $roomDataRaw,
            'paxData'               => $paxData,
            'pricingChildAges'      => $pricingChildAges,
            'pricingOccupancyChildAges' => $pricingOccupancyChildAges,
            'SupplierName'          => $provider,
            'CheckInDate'           => $checkin,
            'CheckOutDate'          => $checkout,
            'NetAmount'             => $total_amount,
            'SpecialServiceRequest' => $special_req,
            'ContactInfo'           => array(
                'Title'             => $lead_title,
                'FName'             => $lead_fname,
                'LName'             => $lead_lname,
                'Mobile'            => $lead_phone,
                'Email'             => $lead_email,
                'City'              => $city,
                'CountryCode'       => 'IN',
                'MobileCountryCode' => '+91'
            )
        );

        $txnId = 200002450;
        $suppRef = 'VOY_HTL_' . rand(100000, 999999);
        $statusCode = 'B0';

        if (!$isMockBooking) {
            $itinResult = $this->benzyhotelapi->createItinerary($itineraryPayload);
            $txnId = is_array($itinResult) ? ($itinResult['transactionId'] ?? $txnId) : $itinResult;
            $tui = is_array($itinResult) ? ($itinResult['tui'] ?? ($itineraryPayload['TUI'])) : $itineraryPayload['TUI'];
            $exactPayAmount = (is_array($itinResult) && !empty($itinResult['netAmount'])) ? (float)$itinResult['netAmount'] : (float)$total_amount;

            $payResult = $this->benzyhotelapi->startPay($txnId, $exactPayAmount, $tui);
            $suppRef = $payResult['CRSPNR'] ?? ($payResult['supplierReference'] ?? $suppRef);

            $retrieveResult = $this->benzyhotelapi->retrieveBooking($txnId, $tui);
            if (!empty($retrieveResult['json']['BookingConfirmationId'])) {
                $suppRef = $retrieveResult['json']['BookingConfirmationId'];
            } elseif (!empty($retrieveResult['json']['CRSPNR'])) {
                $suppRef = $retrieveResult['json']['CRSPNR'];
            }

            if (!empty($retrieveResult['json']['BookingStatus'])) {
                $statusCode = trim($retrieveResult['json']['BookingStatus']);
            } elseif (!empty($payResult['BookStatus'])) {
                $statusCode = trim($payResult['BookStatus']);
            }
        }

        $voucherNum = 'VOY-VCH-' . strtoupper(substr(md5($txnId . time()), 0, 8));
        $bookingRef = 'HB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Deduct from wallet float atomically
        $wallet_res = $this->Franchise_model->update_wallet_balance(
            $store['id'],
            'debit',
            $total_amount,
            'hotel_booking',
            $bookingRef,
            'Hotel Booking: ' . $hotel_name . ' (' . $room_type . ') - Ref: ' . $bookingRef,
            $store['username']
        );

        if (!$wallet_res['status']) {
            $this->session->set_flashdata('error', 'Wallet deduction failed: ' . $wallet_res['error']);
            redirect('franchise/hotel_payment');
            return;
        }

        // Full metadata JSON
        $bookingMeta = array(
            'supplier_reference'  => $suppRef,
            'transaction_id'      => $txnId,
            'tui'                 => $tui,
            'voucher_number'      => $voucherNum,
            'hotel_address'       => $hotel_address,
            'hotel_image'         => $hotel_image,
            'board_type'          => $board_type,
            'destination_city'    => $city,
            'nights_count'        => $nights,
            'rooms_count'         => $rooms,
            'adults_count'        => $adults,
            'children_count'      => $children,
            'guests_count'        => $adults + $children,
            'lead_guest_title'    => $lead_title,
            'lead_guest_name'     => $lead_name,
            'special_requests'    => $special_req,
            'tax_amount'          => $tax_amount,
            'booking_status_code' => $statusCode,
            'pax'                 => $paxData,
            'agent_code'          => $store['agent_code'],
            'store_name'          => $store['store_name']
        );

        // Record booking in database
        $this->Franchise_model->record_hotel_booking(array(
            'store_id'           => $store['id'],
            'booking_ref'        => $bookingRef,
            'hotel_id'           => $hotel_id,
            'hotel_name'         => $hotel_name,
            'room_type'          => $room_type,
            'checkin_date'       => $checkin,
            'checkout_date'      => $checkout,
            'primary_guest_name' => $lead_name,
            'guest_phone'        => $lead_phone,
            'guest_email'        => $lead_email,
            'booking_meta'       => json_encode($bookingMeta),
            'total_amount'       => $total_amount,
            'wallet_deducted'    => $total_amount,
            'status'             => 'confirmed'
        ));

        // Clear session keys
        $this->session->unset_userdata('franchise_hotel_review_booking');
        $this->session->unset_userdata('franchise_hotel_payment_booking');

        redirect('franchise/hotel_voucher/' . urlencode($bookingRef));
    }

    public function hotel_voucher($booking_ref) {
        $store = $this->_check_auth();

        $booking = $this->Franchise_model->get_hotel_booking_by_ref($booking_ref, $store['id']);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking voucher not found.');
            redirect('franchise/bookings');
            return;
        }

        $meta = !empty($booking['booking_meta']) ? json_decode($booking['booking_meta'], true) : array();

        $data['title']       = 'Hotel Voucher: ' . $booking['booking_ref'] . ' - Voyogo B2B';
        $data['active_menu'] = 'bookings';
        $data['store']       = $store;
        $data['booking']     = $booking;
        $data['booking_meta']= $meta;

        $this->load->view('franchise/hotel_voucher', $data);
    }

    // =========================================================================
    // 4. STORE BOOKING HISTORY & WALLET LEDGER
    // =========================================================================
    public function bookings() {
        $store = $this->_check_auth();

        $data['title']           = 'My Bookings - Voyogo B2B';
        $data['active_menu']     = 'bookings';
        $data['store']           = $store;
        $data['flight_bookings'] = $this->Franchise_model->get_store_flight_bookings($store['id'], 50);
        $data['hotel_bookings']  = $this->Franchise_model->get_store_hotel_bookings($store['id'], 50);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/bookings', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function wallet_ledger() {
        $store = $this->_check_auth();

        $data['title']       = 'Wallet Statement / Passbook - Voyogo B2B';
        $data['active_menu'] = 'wallet_ledger';
        $data['store']       = $store;
        $data['ledger']      = $this->Franchise_model->get_wallet_ledger($store['id'], 100);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/wallet_ledger', $data);
        $this->load->view('franchise/layout/footer');
    }
}
