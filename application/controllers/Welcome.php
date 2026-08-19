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
        $multi_from = $this->input->post('multi_from');
        $multi_to   = $this->input->post('multi_to');
        $multi_date = $this->input->post('multi_date');

        if (!empty($multi_from) && is_array($multi_from)) {
            $from_raw = $multi_from[0];
            $to_raw   = end($multi_to);
            $date     = isset($multi_date[0]) ? $multi_date[0] : date('Y-m-d', strtotime('+3 days'));
            $is_multicity = true;
        } else {
            $from_raw = $this->input->post('from_city') ?: $this->input->get('from') ?: 'Delhi (DEL)';
            $to_raw   = $this->input->post('to_city') ?: $this->input->get('to') ?: 'Mumbai (BOM)';
            $date     = $this->input->post('departure_date') ?: $this->input->get('date') ?: date('Y-m-d', strtotime('+3 days'));
            $is_multicity = false;
        }

        $adults      = max(1, (int)($this->input->post('adults') ?: $this->input->get('adults') ?: 1));
        $children    = max(0, (int)($this->input->post('children') ?: $this->input->get('children') ?: 0));
        $infants     = max(0, (int)($this->input->post('infants') ?: $this->input->get('infants') ?: 0));
        $cabin_class = $this->input->post('cabin_class') ?: $this->input->get('cabin_class') ?: 'Economy';

        preg_match('/\(([A-Z]{3})\)/', $from_raw, $from_match);
        preg_match('/\(([A-Z]{3})\)/', $to_raw, $to_match);
        
        $from = isset($from_match[1]) ? $from_match[1] : (strlen($from_raw) == 3 ? strtoupper($from_raw) : 'DEL');
        $to   = isset($to_match[1]) ? $to_match[1] : (strlen($to_raw) == 3 ? strtoupper($to_raw) : 'BOM');

        $this->load->library('BenzyFlightApi');
        
        $tui = $this->benzyflightapi->expressSearch($from, $to, $date, $adults, $children, $infants, substr($cabin_class, 0, 1));
        $flightResults = $this->benzyflightapi->getExpSearch($tui, $from, $to, $date);

        $data['page_title'] = $is_multicity ? "Multi-City Flight Itinerary: $from to $to - Voyogo" : "Flight Search: $from to $to - Voyogo";
        $data['active_page'] = 'flight';
        $data['search_query'] = array(
            'from' => $from_raw,
            'to'   => $to_raw,
            'from_code' => $from,
            'to_code' => $to,
            'date' => $date,
            'is_multicity' => $is_multicity,
            'multi_from' => $multi_from,
            'multi_to' => $multi_to,
            'multi_date' => $multi_date,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'cabin_class' => $cabin_class
        );
        $data['flightResults'] = $flightResults;

        $this->load->view('includes/header', $data);
        $this->load->view('flight_results', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Flight Review & Passenger Details Form
     * Supports Akbar Travels URL Format: /flight/review/{Type}/{FareType}/{Cabin}/{TUI}/{Price}
     */
    public function flight_review($p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null)
    {
        $this->load->library('BenzyFlightApi');

        $tui = '';
        $price = 0;
        $type = 'D';
        $fare_type = 'ON';
        $cabin = 'E';

        // Check if params are passed via URI segments (Akbar Travels format: flight/review/D/ON/E/{TUI}/{Price})
        $segments = array_values($this->uri->segment_array());
        if (count($segments) >= 6 && strtolower($segments[0]) === 'flight' && strtolower($segments[1]) === 'review') {
            $type = strtoupper($segments[2]);
            $fare_type = strtoupper($segments[3]);
            $cabin = strtoupper($segments[4]);
            $tui = urldecode($segments[5]);
            if (isset($segments[6]) && is_numeric($segments[6])) {
                $price = (float)$segments[6];
            }
        } elseif (count($segments) >= 3 && strtolower($segments[0]) === 'flight' && strtolower($segments[1]) === 'review') {
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
                $price = (float)($p5 ?: 5350);
            } else {
                $tui = urldecode($p1);
                $price = (float)($p2 ?: 5350);
            }
        }

        // Fallback to GET or POST if URL params not present
        if (empty($tui)) {
            $tui = $this->input->post('tui') ?: $this->input->get('tui') ?: $this->input->post('flight_id') ?: $this->input->get('flight_id') ?: '';
        }
        if ($price <= 0) {
            $price = (float)($this->input->post('price') ?: $this->input->get('price') ?: 5350);
        }

        $adults      = max(1, (int)($this->input->post('adults') ?: $this->input->get('adults') ?: 1));
        $children    = max(0, (int)($this->input->post('children') ?: $this->input->get('children') ?: 0));
        $infants     = max(0, (int)($this->input->post('infants') ?: $this->input->get('infants') ?: 0));
        $cabin_class = $this->input->post('cabin_class') ?: $this->input->get('cabin_class') ?: 'Economy';

        // Fetch revalidated flight data using Benzy API (SmartPricer / GetSPricer)
        $flightDetails = $this->benzyflightapi->smartPricer($tui, $price);
        
        // Adjust fares for total passenger count
        $pax_multiplier = $adults + $children + (0.5 * $infants);
        if ($pax_multiplier < 1) $pax_multiplier = 1;

        $flightDetails['unit_price'] = isset($flightDetails['price']) ? (float)$flightDetails['price'] : $price;
        $unit_base = isset($flightDetails['base_fare']) ? (float)$flightDetails['base_fare'] : round($flightDetails['unit_price'] * 0.82);
        $unit_taxes = isset($flightDetails['taxes']) ? (float)$flightDetails['taxes'] : round($flightDetails['unit_price'] * 0.18);

        $flightDetails['base_fare'] = round($unit_base * $pax_multiplier);
        $flightDetails['taxes'] = round($unit_taxes * $pax_multiplier);
        $flightDetails['price'] = $flightDetails['base_fare'] + $flightDetails['taxes'];

        // Fetch Fare Rules (Cancellation & Date change policy)
        $fareRules = $this->benzyflightapi->getFareRule($tui);

        // Fetch SSR options (Baggage, Meals, Seats)
        $ssrOptions = $this->benzyflightapi->getSSR($tui);

        // Merge POST inputs if coming from search results selection
        if ($this->input->post('airline_name')) {
            $flightDetails['airline_name'] = $this->input->post('airline_name');
        }
        if ($this->input->post('airline_logo')) {
            $flightDetails['airline_logo'] = $this->input->post('airline_logo');
        }
        if ($this->input->post('flight_number')) {
            $flightDetails['flight_number'] = $this->input->post('flight_number');
        }
        if ($this->input->post('from_code')) {
            $flightDetails['from_code'] = $this->input->post('from_code');
        }
        if ($this->input->post('to_code')) {
            $flightDetails['to_code'] = $this->input->post('to_code');
        }
        if ($this->input->post('departure_time')) {
            $flightDetails['departure_time'] = $this->input->post('departure_time');
        }
        if ($this->input->post('arrival_time')) {
            $flightDetails['arrival_time'] = $this->input->post('arrival_time');
        }
        if ($this->input->post('departure_date')) {
            $flightDetails['departure_date'] = $this->input->post('departure_date');
        }

        $data['flight'] = $flightDetails;
        $data['fare_rules'] = $fareRules;
        $data['ssr'] = $ssrOptions;
        $data['search_query'] = array(
            'adults'      => $adults,
            'children'    => $children,
            'infants'     => $infants,
            'cabin_class' => $cabin_class
        );
        $data['url_meta'] = array(
            'type' => $type,
            'fare_type' => $fare_type,
            'cabin' => $cabin,
            'tui' => $tui
        );

        $from_code = $flightDetails['from_code'];
        $to_code = $flightDetails['to_code'];
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
        
        $titles  = $this->input->post('passenger_title');
        $names   = $this->input->post('passenger_name');
        $ages    = $this->input->post('passenger_age');
        $types   = $this->input->post('passenger_type');

        $passengers = array();
        if (is_array($names) && count($names) > 0) {
            for ($i = 0; $i < count($names); $i++) {
                $p_idx = $i + 1;
                $gender = $this->input->post('passenger_gender_' . $p_idx) ?: 'Male';
                $passengers[] = array(
                    'title'  => isset($titles[$i]) ? $titles[$i] : 'Mr',
                    'name'   => !empty($names[$i]) ? $names[$i] : 'Passenger ' . $p_idx,
                    'age'    => isset($ages[$i]) ? $ages[$i] : '25',
                    'gender' => $gender,
                    'type'   => isset($types[$i]) ? $types[$i] : 'Adult'
                );
            }
        } else {
            $passengers = array(
                array(
                    'title'  => $this->input->post('passenger_title') ?: 'Mr',
                    'name'   => $this->input->post('passenger_name') ?: $contact_name,
                    'age'    => $this->input->post('passenger_age') ?: '30',
                    'gender' => $this->input->post('passenger_gender') ?: 'Male',
                    'type'   => 'Adult'
                )
            );
        }

        $this->load->library('BenzyFlightApi');

        $tui = $this->input->post('tui') ?: ('100e7378-' . md5(uniqid()) . '|' . date('YmdHis'));
        $booking_type = $this->input->post('booking_type') ?: 'HP'; // HP = Ticketed, HB = Hold Booking
        $ssr_baggage = $this->input->post('selected_baggage') ?: '';
        $ssr_meal = $this->input->post('selected_meal') ?: '';

        $contact_payload = array(
            "Title"       => "Mr",
            "FName"       => explode(' ', $contact_name)[0],
            "LName"       => isset(explode(' ', $contact_name)[1]) ? explode(' ', $contact_name)[1] : "Customer",
            "Mobile"      => $contact_phone,
            "Email"       => $contact_email,
            "City"        => "Delhi",
            "CountryCode" => "91"
        );

        $pax_api_payload = array();
        foreach ($passengers as $p) {
            $pax_api_payload[] = array(
                "Title"      => $p['title'],
                "FName"      => explode(' ', $p['name'])[0],
                "LName"      => isset(explode(' ', $p['name'])[1]) ? explode(' ', $p['name'])[1] : "Traveler",
                "PaxType"    => $p['type'] === 'Child' ? 'CHD' : ($p['type'] === 'Infant' ? 'INF' : 'ADT'),
                "Gender"     => ($p['gender'] === 'Female') ? 'F' : 'M',
                "Age"        => (int)$p['age'],
                "DOB"        => date('Y-m-d', strtotime('-' . (int)$p['age'] . ' years')),
                "PassportNo" => "",
                "Baggage"    => $ssr_baggage,
                "Meals"      => $ssr_meal
            );
        }

        // 1. Create Itinerary via Benzy API
        $itineraryRes = $this->benzyflightapi->createItinerary($tui, $pax_api_payload, $contact_payload, $booking_type, array('baggage' => $ssr_baggage, 'meal' => $ssr_meal));
        $transaction_id = isset($itineraryRes['TransactionID']) ? $itineraryRes['TransactionID'] : (int)('2500' . rand(37000, 37999));

        // 2. Start Pay
        $total_amount = (float)($this->input->post('total_amount') ?: 5350);
        $this->benzyflightapi->startPay($transaction_id, $tui, $booking_type, $total_amount);

        // 3. Get Itinerary Status
        $this->benzyflightapi->getItineraryStatus($transaction_id, $tui);

        // 4. Retrieve Booking & Live PNR
        $bookingRes = $this->benzyflightapi->retrieveBooking($transaction_id, $tui, ($booking_type === 'HB'), false);
        
        $pnr = !empty($bookingRes['PNR']) ? $bookingRes['PNR'] : ('W' . strtoupper(substr(md5($transaction_id), 0, 5)));
        $booking_ref = 'VYG-FL-' . strtoupper(substr(md5($transaction_id), 0, 8));
        $razorpay_payment_id = $this->input->post('razorpay_payment_id') ?: ('pay_txn_' . $transaction_id);

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
            'payment_status'    => ($booking_type === 'HB') ? 'Hold (Unpaid)' : 'Paid',
            'booking_status'    => ($booking_type === 'HB') ? 'On Hold' : 'Confirmed',
            'created_at'        => date('Y-m-d H:i:s')
        );

        $this->Booking_model->insert_flight_booking($booking_data);

        // Send Confirmation / Ticket Email
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
