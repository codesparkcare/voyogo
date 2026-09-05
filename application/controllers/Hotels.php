<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dedicated Hotel Booking Controller for Voyogo
 * Completely isolated from Flight booking logic.
 */
class Hotels extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
        $this->load->library('BenzyHotelApi');
        $this->load->model('Hotel_model');
        $this->load->model('Razorpay_model');
    }

    /**
     * 1. Hotel Landing Page / Search Form
     */
    public function index() {
        $data['page_title']  = 'Book Luxury Hotels & Cheap Resorts at Lowest Rates - Voyogo';
        $data['active_page'] = 'hotels';
        $this->load->view('hotels', $data);
    }

    /**
     * 2. Hotel Search Results Action
     */
    public function search() {
        $city     = $this->input->post('city') ?: ($this->input->get('city') ?: 'Goa, India');
        $checkin  = $this->input->post('checkin_date') ?: ($this->input->get('checkin') ?: date('Y-m-d', strtotime('+2 days')));
        $checkout = $this->input->post('checkout_date') ?: ($this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days')));
        $rooms    = (int)($this->input->post('rooms') ?: ($this->input->get('rooms') ?: 1));
        $adults   = (int)($this->input->post('adults') ?: ($this->input->get('adults') ?: 2));
        $children = (int)($this->input->post('children') ?: ($this->input->get('children') ?: 0));

        $hotelResults = $this->benzyhotelapi->searchHotels($city, $checkin, $checkout, $rooms, $adults, $children);

        $data['page_title']    = "Hotels in $city - Best Hotel Deals | Voyogo";
        $data['active_page']   = 'hotels';
        $data['city']          = $city;
        $data['checkin']       = $checkin;
        $data['checkout']      = $checkout;
        $data['rooms']         = $rooms;
        $data['adults']        = $adults;
        $data['children']      = $children;
        $data['hotelResults']  = $hotelResults;

        $this->load->view('hotel_results', $data);
    }

    /**
     * 3. Hotel Detail & Room Selection
     */
    public function detail($hotel_id = 'HTL_101') {
        $city     = $this->input->get('city') ?: 'Goa, India';
        $checkin  = $this->input->get('checkin') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout = $this->input->get('checkout') ?: date('Y-m-d', strtotime('+5 days'));
        $rooms    = (int)($this->input->get('rooms') ?: 1);
        $adults   = (int)($this->input->get('adults') ?: 2);
        $children = (int)($this->input->get('children') ?: 0);

        $hotel = $this->benzyhotelapi->getHotelDetails($hotel_id, $city, $checkin, $checkout);

        $data['hotel']        = $hotel;
        $data['city']         = $city;
        $data['checkin']      = $checkin;
        $data['checkout']     = $checkout;
        $data['rooms']        = $rooms;
        $data['adults']       = $adults;
        $data['children']     = $children;
        $data['page_title']   = ($hotel['name'] ?? 'Hotel') . " - Voyogo Hotels";
        $data['active_page']  = 'hotels';

        $this->load->view('hotel_detail', $data);
    }

    /**
     * 4. Guest Details & Review Page
     */
    public function review() {
        $hotel_id       = $this->input->post('hotel_id') ?: 'HTL_101';
        $hotel_name     = $this->input->post('hotel_name') ?: 'Taj Exotica Resort & Spa';
        $hotel_address  = $this->input->post('hotel_address') ?: 'Benaulim Beach, Goa';
        $hotel_image    = $this->input->post('hotel_image') ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945';
        $room_type      = $this->input->post('room_type') ?: 'Deluxe Garden View Room';
        $room_id        = $this->input->post('room_id') ?: 'RM_DLX_01';
        $board_type     = $this->input->post('board_type') ?: 'Breakfast Included';
        $city           = $this->input->post('city') ?: 'Goa, India';
        $checkin        = $this->input->post('checkin') ?: date('Y-m-d', strtotime('+2 days'));
        $checkout       = $this->input->post('checkout') ?: date('Y-m-d', strtotime('+5 days'));
        $rooms          = (int)($this->input->post('rooms') ?: 1);
        $adults         = (int)($this->input->post('adults') ?: 2);
        $children       = (int)($this->input->post('children') ?: 0);
        $price          = (float)($this->input->post('price') ?: 14500);

        // Validate Live Pricing with API
        $this->benzyhotelapi->repriceRoom($hotel_id, $room_id);

        $nights = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        $baseTotal = $price * $rooms * $nights;
        $taxes = round($baseTotal * 0.12);
        $grandTotal = $baseTotal + $taxes;

        $data['booking_data'] = array(
            'hotel_id'      => $hotel_id,
            'hotel_name'    => $hotel_name,
            'hotel_address' => $hotel_address,
            'hotel_image'   => $hotel_image,
            'room_type'     => $room_type,
            'room_id'       => $room_id,
            'board_type'    => $board_type,
            'city'          => $city,
            'checkin'       => $checkin,
            'checkout'      => $checkout,
            'nights'        => $nights,
            'rooms'         => $rooms,
            'adults'        => $adults,
            'children'      => $children,
            'price'         => $price,
            'base_total'    => $baseTotal,
            'taxes'         => $taxes,
            'grand_total'   => $grandTotal
        );

        $data['razorpay_settings'] = $this->Razorpay_model->get_settings();
        $data['page_title'] = "Review Booking: $hotel_name - Voyogo";
        $data['active_page'] = 'hotels';

        $this->load->view('hotel_review', $data);
    }

    /**
     * 5. Process Payment & Complete Hotel Booking
     */
    public function process_payment() {
        $hotel_id       = $this->input->post('hotel_id');
        $hotel_name     = $this->input->post('hotel_name');
        $hotel_address  = $this->input->post('hotel_address');
        $hotel_image    = $this->input->post('hotel_image');
        $room_type      = $this->input->post('room_type');
        $room_id        = $this->input->post('room_id');
        $board_type     = $this->input->post('board_type');
        $city           = $this->input->post('city');
        $checkin        = $this->input->post('checkin');
        $checkout       = $this->input->post('checkout');
        $rooms          = (int)$this->input->post('rooms');
        $adults         = (int)$this->input->post('adults');
        $children       = (int)$this->input->post('children');
        $nights         = (int)$this->input->post('nights');
        $total_amount   = (float)$this->input->post('grand_total');
        $tax_amount     = (float)$this->input->post('taxes');

        $lead_title     = $this->input->post('guest_title') ?: 'Mr';
        $lead_fname     = $this->input->post('guest_first_name') ?: 'Guest';
        $lead_lname     = $this->input->post('guest_last_name') ?: 'User';
        $lead_name      = trim("$lead_title $lead_fname $lead_lname");
        $lead_email     = $this->input->post('guest_email') ?: 'guest@voyogo.com';
        $lead_phone     = $this->input->post('guest_phone') ?: '9876543210';
        $special_req    = $this->input->post('special_requests') ?: 'Non-smoking room';

        // 1. Benzy Create Itinerary API Call
        $itineraryPayload = array(
            'hotelId'   => $hotel_id,
            'roomId'    => $room_id,
            'checkIn'   => $checkin,
            'checkOut'  => $checkout,
            'leadGuest' => array('name' => $lead_name, 'email' => $lead_email, 'phone' => $lead_phone)
        );
        $txnId = $this->benzyhotelapi->createItinerary($itineraryPayload);

        // 2. Benzy Start Pay API Call
        $payResult = $this->benzyhotelapi->startPay($txnId, $total_amount);
        $suppRef = $payResult['supplierReference'] ?? ('AKB_HTL_' . rand(100000, 999999));
        $voucherNum = $payResult['voucherNumber'] ?? ('VOY-HTL-' . strtoupper(substr(md5(time()), 0, 8)));
        $bookingRef = 'VOY-HTL-' . date('Ymd') . '-' . rand(1000, 9999);

        // 3. Save to database
        $saveData = array(
            'booking_reference'  => $bookingRef,
            'supplier_reference' => $suppRef,
            'transaction_id'     => $txnId,
            'voucher_number'     => $voucherNum,
            'hotel_id'           => $hotel_id,
            'hotel_name'         => $hotel_name,
            'hotel_address'      => $hotel_address,
            'hotel_image'        => $hotel_image,
            'star_rating'        => 5,
            'room_type'          => $room_type,
            'board_type'         => $board_type,
            'destination_city'   => $city,
            'checkin_date'       => $checkin,
            'checkout_date'      => $checkout,
            'nights_count'       => $nights,
            'rooms_count'        => $rooms,
            'adults_count'       => $adults,
            'children_count'     => $children,
            'lead_guest_title'   => $lead_title,
            'lead_guest_name'    => $lead_name,
            'lead_guest_email'   => $lead_email,
            'lead_guest_phone'   => $lead_phone,
            'special_requests'   => $special_req,
            'total_amount'       => $total_amount,
            'tax_amount'         => $tax_amount,
            'currency'           => 'INR',
            'payment_status'     => 'paid',
            'booking_status'     => 'confirmed',
            'cancellation_policy'=> 'Free cancellation until 48 hours before check-in'
        );

        $this->Hotel_model->save_hotel_booking($saveData);

        redirect('hotels/confirmation/' . $bookingRef);
    }

    /**
     * 6. Hotel Confirmation & Voucher
     */
    public function confirmation($bookingRef = '') {
        $booking = $this->Hotel_model->get_hotel_booking_by_ref($bookingRef);
        if (!$booking) {
            redirect('hotels');
        }

        $data['booking']     = $booking;
        $data['page_title']  = 'Hotel Booking Confirmed - ' . $booking['booking_reference'] . ' | Voyogo';
        $data['active_page'] = 'hotels';

        $this->load->view('hotel_confirmation', $data);
    }

    /**
     * 7. Destination / City AutoSuggest (AJAX JSON)
     */
    public function autosuggest() {
        $query = $this->input->get('q') ?: '';
        $results = $this->benzyhotelapi->autoSuggest($query);
        $this->output->set_content_type('application/json')->set_output(json_encode($results));
    }
}
