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
    public function index() {
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

        $trip_type = $this->input->get('trip_type') ?: 'oneway';
        $origin    = strtoupper(trim($this->input->get('origin') ?: 'BOM'));
        $dest      = strtoupper(trim($this->input->get('destination') ?: 'DEL'));
        $depart    = $this->input->get('depart_date') ?: date('Y-m-d', strtotime('+7 days'));
        $return    = $this->input->get('return_date') ?: date('Y-m-d', strtotime('+10 days'));
        $adults    = max(1, (int)($this->input->get('adults') ?: 1));
        $children  = max(0, (int)($this->input->get('children') ?: 0));
        $infants   = max(0, (int)($this->input->get('infants') ?: 0));
        $cabin     = $this->input->get('cabin') ?: 'ECONOMY';

        // Perform search using BenzyFlightApi
        $is_roundtrip = ($trip_type === 'roundtrip');
        $fareType = $is_roundtrip ? 'RT' : 'ON';
        $tui = $this->benzyflightapi->expressSearch($origin, $dest, $depart, ($is_roundtrip ? $return : ''), $adults, $children, $infants, substr($cabin, 0, 1), $fareType, false);
        if (!empty($tui)) {
            $this->benzyflightapi->getWebSettings($tui);
        }
        $rawFlights = $this->benzyflightapi->getExpSearch($tui, $origin, $dest, $depart, false, $is_roundtrip, $return);
        
        $flights = array();
        if (!empty($rawFlights) && is_array($rawFlights)) {
            foreach ($rawFlights as $rf) {
                $flights[] = array(
                    'id'            => $rf['id'] ?? ('FL_' . ($rf['airline_code'] ?? '6E') . '_' . ($rf['flight_number'] ?? '101')),
                    'airline'       => $rf['airline_name'] ?? ($rf['airline_code'] ?? 'IndiGo'),
                    'airline_code'  => $rf['airline_code'] ?? '6E',
                    'flight_number' => $rf['flight_number'] ?? '6E-101',
                    'origin'        => $origin,
                    'destination'   => $dest,
                    'departure'     => $rf['departure_time'] ?? ($depart . ' 08:30:00'),
                    'arrival'       => $rf['arrival_time'] ?? ($depart . ' 10:45:00'),
                    'duration'      => $rf['duration'] ?? '2h 15m',
                    'stops'         => ($rf['stops'] ?? 0) > 0 ? ($rf['stops'] . ' Stop(s)') : 'Non-stop',
                    'base_fare'     => (float)($rf['base_fare'] ?? ($rf['net_fare'] ?? 4200.00)),
                    'tax'           => (float)($rf['tax'] ?? 850.00),
                    'total_fare'    => (float)($rf['total_fare'] ?? ($rf['gross_fare'] ?? 5050.00))
                );
            }
        }

        $data['title']         = 'Flight Results (' . $origin . ' → ' . $dest . ') - Voyogo B2B';
        $data['active_menu']   = 'flight';
        $data['store']         = $store;
        $data['flights']       = $flights;
        $data['search_query']  = array(
            'trip_type'   => $trip_type,
            'origin'      => $origin,
            'destination' => $dest,
            'depart_date' => $depart,
            'return_date' => $return,
            'adults'      => $adults,
            'children'    => $children,
            'infants'     => $infants,
            'cabin'       => $cabin
        );

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_search', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_review() {
        $store = $this->_check_auth();

        $flight_id = $this->input->post('flight_id') ?: $this->input->get('flight_id');
        $flight_data_raw = $this->input->post('flight_data');
        $flight = !empty($flight_data_raw) ? json_decode($flight_data_raw, true) : null;

        if (!$flight) {
            // Demo flight fallback if direct review
            $flight = array(
                'id'            => 'FL_101',
                'airline'       => 'IndiGo',
                'airline_code'  => '6E',
                'flight_number' => '6E-2041',
                'origin'        => 'BOM',
                'destination'   => 'DEL',
                'departure'     => date('Y-m-d 08:30:00', strtotime('+7 days')),
                'arrival'       => date('Y-m-d 10:45:00', strtotime('+7 days')),
                'duration'      => '2h 15m',
                'base_fare'     => 4200.00,
                'tax'           => 850.00,
                'total_fare'    => 5050.00
            );
        }

        $adults   = max(1, (int)($this->input->post('adults') ?: 1));
        $children = max(0, (int)($this->input->post('children') ?: 0));
        $infants  = max(0, (int)($this->input->post('infants') ?: 0));
        $pax_count = $adults + $children + $infants;

        $total_amount = ($flight['total_fare'] ?? 5050.00) * ($adults + $children) + (($flight['total_fare'] ?? 5050.00) * 0.15 * $infants);
        $total_amount = round($total_amount, 2);

        $data['title']        = 'Review & Book Flight - Voyogo B2B';
        $data['active_menu']  = 'flight';
        $data['store']        = $store;
        $data['flight']       = $flight;
        $data['adults']       = $adults;
        $data['children']     = $children;
        $data['infants']      = $infants;
        $data['pax_count']    = $pax_count;
        $data['total_amount'] = $total_amount;
        $data['can_book']     = ($store['wallet_balance'] >= $total_amount);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/flight_review', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function flight_book() {
        $store = $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise/flight');
            return;
        }

        $flight_json = $this->input->post('flight_json');
        $flight      = json_decode($flight_json, true);
        $total_amount= (float)$this->input->post('total_amount');
        $adults      = (int)$this->input->post('adults');
        $children    = (int)$this->input->post('children');
        $infants     = (int)$this->input->post('infants');

        // Check wallet balance
        if ($store['wallet_balance'] < $total_amount) {
            $this->session->set_flashdata('error', 'Insufficient wallet balance. Available: ₹ ' . number_format($store['wallet_balance'], 2) . ' | Required: ₹ ' . number_format($total_amount, 2) . '. Please top up your wallet.');
            redirect('franchise/flight');
            return;
        }

        // Collect passenger details
        $passengers = array();
        $lead_name  = '';
        $lead_phone = trim($this->input->post('contact_phone'));
        $lead_email = trim($this->input->post('contact_email'));

        $titles = $this->input->post('title');
        $firsts = $this->input->post('first_name');
        $lasts  = $this->input->post('last_name');
        $types  = $this->input->post('pax_type');

        if (is_array($firsts)) {
            foreach ($firsts as $k => $fn) {
                $name = ($titles[$k] ?? 'Mr') . ' ' . $fn . ' ' . ($lasts[$k] ?? '');
                if (empty($lead_name)) $lead_name = $name;
                $passengers[] = array(
                    'title'      => $titles[$k] ?? 'Mr',
                    'first_name' => $fn,
                    'last_name'  => $lasts[$k] ?? '',
                    'name'       => $name,
                    'type'       => $types[$k] ?? 'Adult'
                );
            }
        }

        // Generate PNR & Reference
        $pnr         = 'VYO' . strtoupper(substr(md5(uniqid()), 0, 6));
        $booking_ref = 'FB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Deduct from wallet atomically
        $wallet_res = $this->Franchise_model->update_wallet_balance(
            $store['id'],
            'debit',
            $total_amount,
            'flight_booking',
            $booking_ref,
            'Flight Booking PNR: ' . $pnr . ' (' . ($flight['origin'] ?? 'BOM') . ' → ' . ($flight['destination'] ?? 'DEL') . ')',
            $store['username']
        );

        if (!$wallet_res['status']) {
            $this->session->set_flashdata('error', 'Wallet deduction failed: ' . $wallet_res['error']);
            redirect('franchise/flight');
            return;
        }

        // Save booking record
        $this->Franchise_model->record_flight_booking(array(
            'store_id'           => $store['id'],
            'booking_ref'        => $booking_ref,
            'pnr'                => $pnr,
            'airline_name'       => $flight['airline'] ?? 'IndiGo',
            'flight_number'      => $flight['flight_number'] ?? '6E-2041',
            'origin'             => $flight['origin'] ?? 'BOM',
            'destination'        => $flight['destination'] ?? 'DEL',
            'departure_datetime' => $flight['departure'] ?? date('Y-m-d H:i:s'),
            'passenger_details'  => json_encode(array(
                'lead_contact' => array('phone' => $lead_phone, 'email' => $lead_email),
                'passengers'   => $passengers
            )),
            'base_fare'          => (float)($flight['base_fare'] ?? ($total_amount * 0.8)),
            'taxes'              => (float)($flight['tax'] ?? ($total_amount * 0.2)),
            'total_amount'       => $total_amount,
            'wallet_deducted'    => $total_amount,
            'status'             => 'confirmed'
        ));

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

        $data['title']       = 'E-Ticket: ' . $booking['pnr'] . ' - Voyogo B2B';
        $data['active_menu'] = 'bookings';
        $data['store']       = $store;
        $data['booking']     = $booking;
        $data['passengers']  = json_decode($booking['passenger_details'], true);

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

        $city     = $this->input->get('city') ?: 'Goa';
        $checkin  = $this->input->get('checkin') ?: date('Y-m-d', strtotime('+3 days'));
        $checkout = $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));
        $rooms    = max(1, (int)($this->input->get('rooms') ?: 1));
        $adults   = max(1, (int)($this->input->get('adults') ?: 2));
        $children = max(0, (int)($this->input->get('children') ?: 0));

        $res = $this->benzyhotelapi->searchHotels($city, $checkin, $checkout, $rooms, $adults, $children);

        $data['title']        = 'Hotel Search in ' . htmlspecialchars($city) . ' - Voyogo B2B';
        $data['active_menu']  = 'hotel';
        $data['store']        = $store;
        $data['hotels']       = $res['hotels'] ?? array();
        $data['search_id']    = $res['searchId'] ?? '';
        $data['tracing_key']  = $res['searchTracingKey'] ?? '';
        $data['search_query'] = array(
            'city'     => $city,
            'checkin'  => $checkin,
            'checkout' => $checkout,
            'rooms'    => $rooms,
            'adults'   => $adults,
            'children' => $children
        );

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_search_results', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_detail($hotel_id) {
        $store = $this->_check_auth();

        $city      = $this->input->get('city') ?: 'Goa';
        $checkin   = $this->input->get('checkin') ?: date('Y-m-d', strtotime('+3 days'));
        $checkout  = $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));
        $search_id = $this->input->get('search_id');

        $hotel = $this->benzyhotelapi->getHotelDetails($hotel_id, $search_id, $city, $checkin, $checkout);

        $data['title']       = ($hotel['name'] ?? 'Hotel Details') . ' - Voyogo B2B';
        $data['active_menu'] = 'hotel';
        $data['store']       = $store;
        $data['hotel']       = $hotel;
        $data['search_id']   = $search_id;
        $data['city']        = $city;
        $data['checkin']     = $checkin;
        $data['checkout']    = $checkout;
        $data['rooms']       = (int)($this->input->get('rooms') ?: 1);
        $data['adults']      = (int)($this->input->get('adults') ?: 2);
        $data['children']    = (int)($this->input->get('children') ?: 0);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_detail', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_review() {
        $store = $this->_check_auth();

        $hotel_id          = $this->input->post('hotel_id');
        $hotel_name        = $this->input->post('hotel_name');
        $room_type         = $this->input->post('room_type');
        $room_id           = $this->input->post('room_id');
        $recommendation_id = $this->input->post('recommendation_id');
        $provider          = $this->input->post('provider') ?: 'CleartripAPI';
        $search_id         = $this->input->post('search_id');
        $checkin           = $this->input->post('checkin');
        $checkout          = $this->input->post('checkout');
        $rooms             = (int)($this->input->post('rooms') ?: 1);
        $price             = (float)($this->input->post('price') ?: 3500);

        $total_amount = $price; // Already full stay price from Benzy

        $data['title']             = 'Review Hotel Booking - Voyogo B2B';
        $data['active_menu']       = 'hotel';
        $data['store']             = $store;
        $data['hotel_id']          = $hotel_id;
        $data['hotel_name']        = $hotel_name;
        $data['room_type']         = $room_type;
        $data['room_id']           = $room_id;
        $data['recommendation_id'] = $recommendation_id;
        $data['provider']          = $provider;
        $data['search_id']         = $search_id;
        $data['checkin']           = $checkin;
        $data['checkout']          = $checkout;
        $data['rooms']             = $rooms;
        $data['total_amount']      = $total_amount;
        $data['can_book']          = ($store['wallet_balance'] >= $total_amount);

        $this->load->view('franchise/layout/header', $data);
        $this->load->view('franchise/hotel_review', $data);
        $this->load->view('franchise/layout/footer');
    }

    public function hotel_book() {
        $store = $this->_check_auth();

        if ($this->input->method() !== 'post') {
            redirect('franchise/hotel');
            return;
        }

        $hotel_id     = $this->input->post('hotel_id');
        $hotel_name   = $this->input->post('hotel_name');
        $room_type    = $this->input->post('room_type');
        $checkin      = $this->input->post('checkin');
        $checkout     = $this->input->post('checkout');
        $guest_name   = trim($this->input->post('primary_guest_name'));
        $guest_phone  = trim($this->input->post('guest_phone'));
        $guest_email  = trim($this->input->post('guest_email'));
        $total_amount = (float)$this->input->post('total_amount');

        if ($store['wallet_balance'] < $total_amount) {
            $this->session->set_flashdata('error', 'Insufficient wallet balance for this booking. Available: ₹ ' . number_format($store['wallet_balance'], 2));
            redirect('franchise/hotel');
            return;
        }

        $booking_ref = 'HB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Deduct from wallet
        $wallet_res = $this->Franchise_model->update_wallet_balance(
            $store['id'],
            'debit',
            $total_amount,
            'hotel_booking',
            $booking_ref,
            'Hotel Booking: ' . $hotel_name . ' (' . $room_type . ')',
            $store['username']
        );

        if (!$wallet_res['status']) {
            $this->session->set_flashdata('error', 'Wallet deduction failed: ' . $wallet_res['error']);
            redirect('franchise/hotel');
            return;
        }

        // Record booking
        $this->Franchise_model->record_hotel_booking(array(
            'store_id'           => $store['id'],
            'booking_ref'        => $booking_ref,
            'hotel_id'           => $hotel_id,
            'hotel_name'         => $hotel_name,
            'room_type'          => $room_type,
            'checkin_date'       => $checkin,
            'checkout_date'      => $checkout,
            'primary_guest_name' => $guest_name,
            'guest_phone'        => $guest_phone,
            'guest_email'        => $guest_email,
            'total_amount'       => $total_amount,
            'wallet_deducted'    => $total_amount,
            'status'             => 'confirmed'
        ));

        redirect('franchise/hotel_voucher/' . urlencode($booking_ref));
    }

    public function hotel_voucher($booking_ref) {
        $store = $this->_check_auth();

        $booking = $this->Franchise_model->get_hotel_booking_by_ref($booking_ref, $store['id']);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking voucher not found.');
            redirect('franchise/bookings');
            return;
        }

        $data['title']       = 'Hotel Voucher: ' . $booking['booking_ref'] . ' - Voyogo B2B';
        $data['active_menu'] = 'bookings';
        $data['store']       = $store;
        $data['booking']     = $booking;

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
