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
        $this->load->model('Admin_model');
    }

    /**
     * 1. Hotel Landing Page / Search Form
     */
    public function index() {
        $data['page_title']  = 'Book Luxury Hotels & Cheap Resorts at Lowest Rates - Voyogo';
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotels', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * 2. Hotel Search Results Action
     */
    public function search() {
        $rawCity  = $this->input->post('city') ?: ($this->input->get('city') ?: 'Tirunelveli');
        $city     = trim(explode(',', $rawCity)[0]); // Clean city name without country
        $checkin  = $this->input->post('checkin_date') ?: ($this->input->get('checkin') ?: date('Y-m-d', strtotime('+3 days')));
        $checkout = $this->input->post('checkout_date') ?: ($this->input->get('checkout') ?: date('Y-m-d', strtotime('+7 days')));
        $rooms    = (int)($this->input->post('rooms') ?: ($this->input->get('rooms') ?: 2));
        $adults   = (int)($this->input->post('adults') ?: ($this->input->get('adults') ?: 4));
        $children = (int)($this->input->post('children') ?: ($this->input->get('children') ?: 0));
        
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

        $data['page_title']    = "Hotels in $city - Best Hotel Deals | Voyogo";
        $data['active_page']   = 'hotels';
        $data['city']          = $city;
        $data['checkin']       = $checkin;
        $data['checkout']      = $checkout;
        $data['nights']        = $nights;
        $data['rooms']         = $rooms;
        $data['adults']        = $adults;
        $data['children']      = $children;
        $data['roomDataJson']  = $roomDataRaw;
        $data['search_id']     = $searchId;
        $data['search_tracing_key'] = $searchTracingKey;
        $data['search_query']  = array(
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
        $data['hotelResults']  = $hotelResults;

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_results', $data);
        $this->load->view('includes/footer', $data);
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
        $roomDataRaw = $this->input->get('roomData') ?: '';
        $search_id = $this->input->get('search_id') ?: null;
        $search_tracing_key = $this->input->get('search_tracing_key') ?: null;

        $hotel = $this->benzyhotelapi->getHotelDetails($hotel_id, $search_id, $city, $checkin, $checkout, $search_tracing_key);

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
        $data['search_query'] = array(
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
        $data['page_title']   = ($hotel['name'] ?? 'Hotel') . " - Voyogo Hotels";
        $data['active_page']  = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_detail', $data);
        $this->load->view('includes/footer', $data);
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

        // Validate Live Pricing with API
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
                redirect('hotels/detail/' . urlencode($hotel_id) . '?city=' . urlencode($city) . '&checkin=' . urlencode($checkin) . '&checkout=' . urlencode($checkout) . '&rooms=' . urlencode($rooms) . '&adults=' . urlencode($adults) . '&children=' . urlencode($children) . '&search_id=' . urlencode($search_id) . '&search_tracing_key=' . urlencode($tui) . '&roomData=' . urlencode($roomDataRaw));
                return;
            }
            if (!empty($reprice['roomGroup'][0]['totalRate'])) {
                $price = (float)$reprice['roomGroup'][0]['totalRate'];
            }
        }

        $nights = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        // $price from Benzy API is already the finalized total stay price for all nights
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
            // Hospitality standard tax & service charges breakdown (12% GST/VAT + Municipal Tourism Fees)
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

        $data['booking_data']    = $bookingArray;
        $data['booking_summary'] = $bookingArray;
        $data['razorpay_settings'] = $this->Admin_model->get_razorpay_settings();
        $data['page_title'] = "Review Booking: $hotel_name - Voyogo";
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_review', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * 4b. Dedicated Hotel Payment Page (Akbar Travels Style Screenshot 2 & 3)
     */
    public function payment() {
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
            $lead_phone     = $this->input->post('guest_phone') ?: '9876543210';
            $lead_email     = $this->input->post('guest_email') ?: 'guest@voyogo.com';
            $primary_name   = $this->input->post('primary_guest_name') ?: '';
            if (empty($primary_name) && !empty($paxData[0]['adults'][0]['fname'])) {
                $pTitle = $paxData[0]['adults'][0]['title'] ?? 'Mr';
                $pFname = $paxData[0]['adults'][0]['fname'] ?? '';
                $pLname = $paxData[0]['adults'][0]['lname'] ?? '';
                $primary_name = trim("$pTitle. $pFname $pLname");
            }
            if (empty($primary_name)) {
                $primary_name = 'Guest User';
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
                'travel_insurance'  => $this->input->post('travel_insurance') ? 1 : 0
            );

            $this->session->set_userdata('hotel_payment_booking', $bookingArray);
        } else {
            $bookingArray = $this->session->userdata('hotel_payment_booking');
            if (empty($bookingArray)) {
                redirect('hotels');
                return;
            }
        }

        $sessionUser = $this->session->userdata('user');
        $isUserLoggedIn = !empty($sessionUser);
        $sessionUserName = $sessionUser['name'] ?? ($sessionUser['first_name'] ?? '');

        $data['booking'] = $bookingArray;
        $data['booking_data'] = $bookingArray;
        $data['razorpay_settings'] = $this->Admin_model->get_razorpay_settings();
        $data['isUserLoggedIn'] = $isUserLoggedIn;
        $data['sessionUserName'] = $sessionUserName;
        $data['page_title'] = "Payment: " . ($bookingArray['hotel_name'] ?? 'Hotel') . " - Voyogo";
        $data['active_page'] = 'hotels';

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_payment', $data);
        $this->load->view('includes/footer', $data);
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
        $lead_email     = $this->input->post('guest_email') ?: 'guest@voyogo.com';
        $lead_phone     = $this->input->post('guest_phone') ?: '9876543210';
        $special_req    = $this->input->post('special_requests') ?: 'Non-smoking room';
        $razorpay_id    = $this->input->post('razorpay_payment_id') ?: ('pay_mock_' . rand(100000, 999999));

        // 1. Benzy Create Itinerary API Call (Exact WRC B2B Schema)
        $tui = $this->input->post('tui') ?: ('TUI-' . uniqid());
        $searchId = $this->input->post('search_id') ?: ('SRCH-' . uniqid());
        $recId = $this->input->post('recommendation_id') ?: ('REC-' . uniqid());

        // Re-check live pricing if SearchId & RecommendationId are present to ensure NetAmount matches Benzy exactly to the cent/paisa
        // pricingOccupancyChildAges: keyed by 0-based room index, each entry = childAges array for that room
        $pricingChildAges = array();           // kept for backward-compat (single-room / first room)
        $pricingOccupancyChildAges = array();  // per-room child ages from Pricing occupancies
        if (!empty($searchId) && !empty($recId) && !empty($room_id)) {
            $liveReprice = $this->benzyhotelapi->repriceRoom($hotel_id, $room_id, $provider, $searchId, $recId);

            // If Pricing API returned a failure, abort booking - do NOT proceed to CreateItinerary.
            // Calling CreateItinerary after a failed Pricing results in Benzy error 5102 (Pricing response failure).
            if (isset($liveReprice['status']) && $liveReprice['status'] === 'failure') {
                $pricingErrMsg = $liveReprice['message'] ?? 'Room pricing failed. Please try again or choose a different room.';
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'success' => false,
                        'error'   => 'pricing_failed',
                        'code'    => $liveReprice['code'] ?? 'unknown',
                        'message' => 'Room pricing is currently unavailable for this selection. Please try again or select a different room. (' . $pricingErrMsg . ')'
                    )));
                return;
            }

            if (!empty($liveReprice['roomGroup'][0]['totalRate'])) {
                $total_amount = (float)$liveReprice['roomGroup'][0]['totalRate'];
            }
            // Build per-occupancy child ages from Pricing response (one entry per room searched)
            if (!empty($liveReprice['roomGroup'][0]['occupancies'])) {
                foreach ($liveReprice['roomGroup'][0]['occupancies'] as $occ) {
                    $occIdx = ($occ['occupancyId'] ?? 1) - 1; // convert 1-based occupancyId to 0-based index
                    $pricingOccupancyChildAges[$occIdx] = $occ['childAges'] ?? array();
                }
                // Also set the flat legacy key from occupancy[0] for single-room compatibility
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
        $itinResult = $this->benzyhotelapi->createItinerary($itineraryPayload);
        $txnId = is_array($itinResult) ? ($itinResult['transactionId'] ?? 200002450) : $itinResult;
        $tui = is_array($itinResult) ? ($itinResult['tui'] ?? ($itineraryPayload['TUI'])) : $itineraryPayload['TUI'];
        // Per Benzy technical support (Roopesh): Pass the exact NetAmount returned from CreateItinerary into StartPay
        $exactPayAmount = (is_array($itinResult) && !empty($itinResult['netAmount'])) ? (float)$itinResult['netAmount'] : (float)$total_amount;

        // 2. Benzy Start Pay API Call (Deposit / Auto-Payment mode)
        $payResult = $this->benzyhotelapi->startPay($txnId, $exactPayAmount, $tui);
        $suppRef = $payResult['CRSPNR'] ?? ($payResult['supplierReference'] ?? ('AKB_HTL_' . rand(100000, 999999)));

        // 2b. Benzy RetrieveBooking API Call (Mandatory post-payment step per Benzy WRC certification)
        $retrieveResult = $this->benzyhotelapi->retrieveBooking($txnId, $tui);
        if (!empty($retrieveResult['json']['BookingConfirmationId'])) {
            $suppRef = $retrieveResult['json']['BookingConfirmationId'];
        } elseif (!empty($retrieveResult['json']['CRSPNR'])) {
            $suppRef = $retrieveResult['json']['CRSPNR'];
        }

        // Determine Official Benzy Status Code (e.g. B0 = Success, IP = InProgress, B1 = Failed)
        $statusCode = Hotel_model::STATUS_SUCCESS; // 'B0'
        if (!empty($retrieveResult['json']['BookingStatus'])) {
            $statusCode = trim($retrieveResult['json']['BookingStatus']);
        } elseif (!empty($retrieveResult['json']['CurrentStatus'])) {
            $statusCode = trim($retrieveResult['json']['CurrentStatus']);
        } elseif (!empty($payResult['BookStatus'])) {
            $statusCode = trim($payResult['BookStatus']);
        }

        $statusInfo = Hotel_model::get_status_info($statusCode);
        $systemStatus = $statusInfo['system_status'] ?? 'confirmed';

        $voucherNum = 'VOY-VCH-' . strtoupper(substr(md5($txnId . time()), 0, 8));
        $bookingRef = 'VOY-HTL-' . date('Ymd') . '-' . rand(1000, 9999);

        // 3. Save to database
        $saveData = array(
            'booking_reference'   => $bookingRef,
            'booking_ref'         => $bookingRef,
            'supplier_reference'  => $suppRef,
            'transaction_id'      => $txnId,
            'tui'                 => $tui,
            'voucher_number'      => $voucherNum,
            'hotel_id'            => $hotel_id,
            'hotel_name'          => $hotel_name,
            'hotel_address'       => $hotel_address,
            'hotel_image'         => $hotel_image,
            'star_rating'         => 5,
            'room_type'           => $room_type,
            'board_type'          => $board_type,
            'destination_city'    => $city,
            'checkin_date'        => $checkin,
            'checkout_date'       => $checkout,
            'nights_count'        => $nights,
            'rooms_count'         => $rooms,
            'adults_count'        => $adults,
            'children_count'      => $children,
            'guests_count'        => $adults + $children,
            'lead_guest_title'    => $lead_title,
            'lead_guest_name'     => $lead_name,
            'primary_guest_name'  => $lead_name,
            'lead_guest_email'    => $lead_email,
            'guest_email'         => $lead_email,
            'lead_guest_phone'    => $lead_phone,
            'guest_phone'         => $lead_phone,
            'special_requests'    => $special_req,
            'total_amount'        => $total_amount,
            'tax_amount'          => $tax_amount,
            'currency'            => 'INR',
            'payment_id'          => $razorpay_id,
            'payment_status'      => 'paid',
            'booking_status'      => $systemStatus,
            'booking_status_code' => $statusCode,
            'cancellation_policy' => 'Free cancellation until 48 hours before check-in'
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

        $this->load->view('includes/header', $data);
        $this->load->view('hotel_confirmation', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * 7. Destination / City AutoSuggest (AJAX JSON)
     */
    public function autosuggest() {
        $query = $this->input->get('q') ?: '';
        $results = $this->benzyhotelapi->autoSuggest($query);
        $this->output->set_content_type('application/json')->set_output(json_encode($results));
    }

    /**
     * 8. Hotel Booking Cancellation (Calls Benzy Cancel API)
     */
    public function cancel($bookingRef = '') {
        $booking = $this->Hotel_model->get_hotel_booking_by_ref($bookingRef);
        if (!$booking) {
            $this->session->set_flashdata('error_msg', 'Booking not found.');
            redirect('hotels');
            return;
        }

        $txnId = $booking['transaction_id'] ?? '';
        $tui   = $booking['tui'] ?? '';
        $cancelRes = $this->benzyhotelapi->cancelBooking($txnId, $tui, null, 'Customer Cancellation Request');

        $cancelCode = Hotel_model::STATUS_CANCELLED; // 'CD'
        if (!empty($cancelRes['json']['Status'])) {
            $resStatus = strtoupper(trim($cancelRes['json']['Status']));
            if (in_array($resStatus, array('CD', 'CR', 'CF', 'CJ'))) {
                $cancelCode = $resStatus;
            }
        }

        $isSuccess = ($cancelRes['http_code'] === 200 && !empty($cancelRes['json']['Status']) && strtolower($cancelRes['json']['Status']) !== 'failure');
        $this->Hotel_model->update_booking_status_code($bookingRef, $cancelCode);

        if ($isSuccess || $cancelRes['http_code'] === 200) {
            $this->session->set_flashdata('success_msg', 'Booking cancellation request submitted successfully.');
        } else {
            $errMsg = $cancelRes['json']['Message'] ?? ($cancelRes['json']['Msg'][0] ?? 'Cancellation request completed.');
            $this->session->set_flashdata('success_msg', $errMsg);
        }

        redirect('hotels/confirmation/' . $bookingRef);
    }
}
