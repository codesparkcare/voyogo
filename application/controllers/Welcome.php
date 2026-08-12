<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
        $this->load->model('Booking_model');
    }

    /**
     * Home Page / Flight Landing
     */
    public function index()
    {
        $data['page_title'] = 'Voyogo - Book Cheap Flight Tickets Online';
        $data['active_page'] = 'flight';

        $this->load->view('includes/header', $data);
        $this->load->view('index', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Flight Route Shortcut
     */
    public function flight()
    {
        $this->index();
    }

    /**
     * Flight Search Action
     */
    public function search_flights()
    {
        $from_raw = $this->input->post('from_city') ?: $this->input->get('from') ?: 'Delhi (DEL)';
        $to_raw = $this->input->post('to_city') ?: $this->input->get('to') ?: 'Mumbai (BOM)';
        $date = $this->input->post('departure_date') ?: $this->input->get('date') ?: date('Y-m-d', strtotime('+3 days'));

        preg_match('/\(([A-Z]{3})\)/', $from_raw, $from_match);
        preg_match('/\(([A-Z]{3})\)/', $to_raw, $to_match);
        
        $from = isset($from_match[1]) ? $from_match[1] : (strlen($from_raw) == 3 ? strtoupper($from_raw) : 'DEL');
        $to = isset($to_match[1]) ? $to_match[1] : (strlen($to_raw) == 3 ? strtoupper($to_raw) : 'BOM');

        $this->load->library('BenzyFlightApi');
        
        $tui = $this->benzyflightapi->expressSearch($from, $to, $date);
        $flightResults = $this->benzyflightapi->getExpSearch($tui, $from, $to, $date);

        $data['page_title'] = "Flight Search: $from to $to - Voyogo";
        $data['active_page'] = 'flight';
        $data['search_query'] = array(
            'from' => $from_raw,
            'to'   => $to_raw,
            'from_code' => $from,
            'to_code' => $to,
            'date' => $date
        );
        $data['flightResults'] = $flightResults;

        $this->load->view('includes/header', $data);
        $this->load->view('flight_results', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Flight Review & Passenger Details Form
     */
    public function flight_review()
    {
        $flight_id = $this->input->post('flight_id') ?: $this->input->get('flight_id') ?: 'FL_101';
        $airline_name = $this->input->post('airline_name') ?: 'IndiGo';
        $airline_logo = $this->input->post('airline_logo') ?: 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
        $flight_number = $this->input->post('flight_number') ?: '6E-2134';
        $from_code = $this->input->post('from_code') ?: 'DEL';
        $to_code = $this->input->post('to_code') ?: 'BOM';
        $departure_time = $this->input->post('departure_time') ?: '06:00';
        $arrival_time = $this->input->post('arrival_time') ?: '08:15';
        $departure_date = $this->input->post('departure_date') ?: date('Y-m-d', strtotime('+3 days'));
        $price = (float)($this->input->post('price') ?: 5350);

        $data['flight'] = array(
            'id' => $flight_id,
            'airline_name' => $airline_name,
            'airline_logo' => $airline_logo,
            'flight_number' => $flight_number,
            'from_code' => $from_code,
            'to_code' => $to_code,
            'departure_time' => $departure_time,
            'arrival_time' => $arrival_time,
            'departure_date' => $departure_date,
            'price' => $price,
            'base_fare' => round($price * 0.82),
            'taxes' => round($price * 0.18)
        );

        $data['page_title'] = "Review Booking: $from_code to $to_code - Voyogo";
        $data['active_page'] = 'flight';

        $this->load->view('includes/header', $data);
        $this->load->view('flight_review', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Process Flight Payment & Save Booking
     */
    public function process_flight_payment()
    {
        $contact_name  = $this->input->post('contact_name') ?: 'John Doe';
        $contact_email = $this->input->post('contact_email') ?: 'customer@example.com';
        $contact_phone = $this->input->post('contact_phone') ?: '9876543210';
        
        $passengers = array(
            array(
                'title' => $this->input->post('passenger_title') ?: 'Mr',
                'name'  => $this->input->post('passenger_name') ?: $contact_name,
                'age'   => $this->input->post('passenger_age') ?: '30',
                'gender'=> $this->input->post('passenger_gender') ?: 'Male'
            )
        );

        $booking_ref = 'VYG-FL-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $pnr = 'PNR' . rand(100000, 999999);
        $total_amount = (float)($this->input->post('total_amount') ?: 5350);
        $razorpay_payment_id = $this->input->post('razorpay_payment_id') ?: ('pay_mock_' . rand(100000, 999999));

        $flight_number = $this->input->post('flight_number') ?: '6E-2134';
        $airline_name  = $this->input->post('airline_name') ?: 'IndiGo';
        $origin        = $this->input->post('origin') ?: 'Delhi (DEL)';
        $destination   = $this->input->post('destination') ?: 'Mumbai (BOM)';
        $dep_date      = $this->input->post('departure_date') ?: date('Y-m-d', strtotime('+3 days'));
        $dep_time      = $this->input->post('departure_time') ?: '06:00';

        $dep_datetime  = date('Y-m-d H:i:s', strtotime("$dep_date $dep_time"));

        $booking_data = array(
            'booking_ref'       => $booking_ref,
            'pnr'               => $pnr,
            'airline_name'      => $airline_name,
            'airline_code'      => substr($flight_number, 0, 2),
            'flight_number'     => $flight_number,
            'origin'            => $origin,
            'destination'       => $destination,
            'departure_datetime'=> $dep_datetime,
            'arrival_datetime'  => date('Y-m-d H:i:s', strtotime("$dep_datetime + 2 hours 15 mins")),
            'cabin_class'       => 'Economy',
            'passenger_details' => json_encode($passengers),
            'contact_name'      => $contact_name,
            'contact_email'     => $contact_email,
            'contact_phone'     => $contact_phone,
            'total_amount'      => $total_amount,
            'payment_id'        => $razorpay_payment_id,
            'payment_status'    => 'Paid',
            'booking_status'    => 'Confirmed',
            'created_at'        => date('Y-m-d H:i:s')
        );

        $this->Booking_model->insert_flight_booking($booking_data);

        // Send Email
        $this->load->library('Mailer');
        @$this->mailer->send_flight_ticket($booking_data);

        redirect('flight/confirmation/' . $booking_ref);
    }

    /**
     * Flight E-Ticket Confirmation View
     */
    public function flight_confirmation($booking_ref)
    {
        $booking = $this->Booking_model->get_flight_booking_by_ref($booking_ref);
        if (!$booking) {
            show_404();
        }

        $data['booking'] = $booking;
        $data['page_title'] = "Flight E-Ticket Confirmed - Ref: $booking_ref";
        $data['active_page'] = 'flight';

        $this->load->view('includes/header', $data);
        $this->load->view('flight_confirmation', $data);
        $this->load->view('includes/footer', $data);
    }

    // =========================================================================
    // HOTEL BOOKING FLOW
    // =========================================================================

    /**
     * Hotel Landing Page
     */
    public function hotels()
    {
        $data['page_title'] = 'Voyogo - Cheap Hotel Room Bookings & Luxury Resorts';
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotels', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Hotel Search Results Action
     */
    public function search_hotels()
    {
        $city = $this->input->post('city') ?: $this->input->get('city') ?: 'Goa, India';
        $checkin = $this->input->post('checkin_date') ?: $this->input->get('checkin') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout = $this->input->post('checkout_date') ?: $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));

        $this->load->library('BenzyHotelApi');
        $hotelResults = $this->benzyhotelapi->searchHotels($city, $checkin, $checkout);

        $data['page_title'] = "Hotels in $city - Voyogo";
        $data['active_page'] = 'hotels';
        $data['search_query'] = array(
            'city'     => $city,
            'checkin'  => $checkin,
            'checkout' => $checkout
        );
        $data['hotelResults'] = $hotelResults;

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_results', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Hotel Detail & Room Selection
     */
    public function hotel_detail($hotel_id = 'HTL_101')
    {
        $city = $this->input->get('city') ?: 'Goa';
        $checkin = $this->input->get('checkin') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout = $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));

        $this->load->library('BenzyHotelApi');
        $hotel = $this->benzyhotelapi->getHotelItinerary($hotel_id, $city, $checkin, $checkout);

        $data['hotel'] = $hotel;
        $data['search_query'] = array(
            'city'     => $city,
            'checkin'  => $checkin,
            'checkout' => $checkout
        );
        $data['page_title'] = ($hotel['name'] ?? 'Hotel') . " - Voyogo";
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_detail', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Hotel Guest Details Review & Payment Form
     */
    public function hotel_review()
    {
        $hotel_id = $this->input->post('hotel_id') ?: 'HTL_101';
        $hotel_name = $this->input->post('hotel_name') ?: 'Taj Exotica Resort & Spa';
        $hotel_address = $this->input->post('hotel_address') ?: 'Benaulim Beach, Goa';
        $hotel_image = $this->input->post('hotel_image') ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80';
        $room_type = $this->input->post('room_type') ?: 'Deluxe Garden View Room';
        $price = (float)($this->input->post('price') ?: 8499);
        $checkin = $this->input->post('checkin_date') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout = $this->input->post('checkout_date') ?: date('Y-m-d', strtotime('+5 days'));

        // Calculate nights
        $diff = max(1, (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24));
        $total_amount = $price * $diff;

        $data['booking_summary'] = array(
            'hotel_id'      => $hotel_id,
            'hotel_name'    => $hotel_name,
            'hotel_address' => $hotel_address,
            'hotel_image'   => $hotel_image,
            'room_type'     => $room_type,
            'price_per_night' => $price,
            'nights'        => $diff,
            'total_amount'  => $total_amount,
            'checkin_date'  => $checkin,
            'checkout_date' => $checkout
        );

        $data['page_title'] = "Review Hotel Booking: $hotel_name - Voyogo";
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_review', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Process Hotel Payment & Save Booking
     */
    public function process_hotel_payment()
    {
        $guest_name  = $this->input->post('primary_guest_name') ?: 'John Doe';
        $guest_email = $this->input->post('guest_email') ?: 'guest@example.com';
        $guest_phone = $this->input->post('guest_phone') ?: '9876543210';
        
        $booking_ref = 'VYG-HTL-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $total_amount = (float)($this->input->post('total_amount') ?: 8499);
        $razorpay_payment_id = $this->input->post('razorpay_payment_id') ?: ('pay_mock_htl_' . rand(100000, 999999));

        $booking_data = array(
            'booking_ref'       => $booking_ref,
            'hotel_id'          => $this->input->post('hotel_id') ?: 'HTL_101',
            'hotel_name'        => $this->input->post('hotel_name') ?: 'Taj Exotica Resort & Spa',
            'hotel_address'     => $this->input->post('hotel_address') ?: 'Benaulim Beach, Goa',
            'hotel_image'       => $this->input->post('hotel_image') ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
            'room_type'         => $this->input->post('room_type') ?: 'Deluxe Garden View Room',
            'checkin_date'      => $this->input->post('checkin_date') ?: date('Y-m-d', strtotime('+2 days')),
            'checkout_date'     => $this->input->post('checkout_date') ?: date('Y-m-d', strtotime('+5 days')),
            'guests_count'      => (int)($this->input->post('guests_count') ?: 2),
            'rooms_count'       => (int)($this->input->post('rooms_count') ?: 1),
            'primary_guest_name'=> $guest_name,
            'guest_email'       => $guest_email,
            'guest_phone'       => $guest_phone,
            'total_amount'      => $total_amount,
            'payment_id'        => $razorpay_payment_id,
            'payment_status'    => 'Paid',
            'booking_status'    => 'Confirmed',
            'created_at'        => date('Y-m-d H:i:s')
        );

        $this->Booking_model->insert_hotel_booking($booking_data);

        // Send Email Voucher
        $this->load->library('Mailer');
        @$this->mailer->send_hotel_voucher($booking_data);

        redirect('hotels/confirmation/' . $booking_ref);
    }

    /**
     * Hotel Voucher Confirmation View
     */
    public function hotel_confirmation($booking_ref)
    {
        $booking = $this->Booking_model->get_hotel_booking_by_ref($booking_ref);
        if (!$booking) {
            show_404();
        }

        $data['booking'] = $booking;
        $data['page_title'] = "Hotel Voucher Confirmed - Ref: $booking_ref";
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_confirmation', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Submit General Contact Enquiry
     */
    public function save_enquiry()
    {
        $data = array(
            'name'    => $this->input->post('name'),
            'email'   => $this->input->post('email'),
            'phone'   => $this->input->post('phone'),
            'message' => $this->input->post('message'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->db->table_exists('enquiries')) {
            $this->db->insert('enquiries', $data);
        }

        $this->session->set_flashdata('success_msg', 'Your enquiry has been received! Our travel expert will call you back shortly.');
        redirect('welcome');
    }
}
