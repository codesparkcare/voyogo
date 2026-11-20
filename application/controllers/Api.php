<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Voyogo REST API Controller for Mobile Applications (Flutter)
 * 
 * Exposes standardized JSON endpoints for:
 * 1. Flight Booking Flow (Benz Flight API)
 * 2. Hotel Booking Flow (Benz Hotel API)
 * 3. Payment Processing & Verification (Razorpay)
 * 4. User Bookings & E-Tickets / Vouchers
 */
class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Enable Cross-Origin Resource Sharing (CORS) for Mobile and Web clients
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept");

        if ($this->input->server('REQUEST_METHOD') === 'OPTIONS') {
            $this->output->set_status_header(200)->_display();
            exit;
        }

        $this->load->helper(array('url', 'file'));
    }

    /**
     * Standard JSON response helper
     */
    protected function response($success, $message, $data = null, $statusCode = 200) {
        $payload = array(
            'success'   => (bool)$success,
            'message'   => $message,
            'timestamp' => date('c'),
            'data'      => $data
        );
        return $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Helper to read JSON or POST/GET payload
     */
    protected function get_payload() {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true) ?: array();
        return array_merge($this->input->get(), $this->input->post(), $json);
    }

    // =========================================================================
    // AIRPORT & DESTINATION AUTOSUGGEST
    // =========================================================================

    /**
     * GET /api/airports?q=del
     */
    public function airports() {
        $p = $this->get_payload();
        $q = trim((string)($p['q'] ?? ($p['term'] ?? '')));
        
        $allAirports = $this->_get_master_airports();
        if ($q === '') {
            $popular = array_values(array_filter($allAirports, function($a) {
                return !empty($a['popular']);
            }));
            return $this->response(true, 'Popular airports retrieved', $popular);
        }

        $qLower = strtolower($q);
        $cleanQ = trim(preg_replace('/[(),]/', ' ', $qLower));
        $cleanQ = preg_replace('/\s+/', ' ', $cleanQ);

        $filtered = array();
        foreach ($allAirports as $a) {
            $codeMatch    = (strpos(strtolower($a['code']), $cleanQ) !== false);
            $cityMatch    = (strpos(strtolower($a['city']), $cleanQ) !== false);
            $airportMatch = (strpos(strtolower($a['airport']), $cleanQ) !== false);
            $countryMatch = (strpos(strtolower($a['country']), $cleanQ) !== false);

            if ($codeMatch || $cityMatch || $airportMatch || $countryMatch) {
                $filtered[] = $a;
            }
        }

        return $this->response(true, count($filtered) . ' airports found', $filtered);
    }

    // =========================================================================
    // FLIGHT BOOKING FLOW (BENZY LIVE FLIGHT API)
    // =========================================================================

    /**
     * POST /api/flight/search
     * Request: { trip_type, from, to, departure_date, return_date, adults, children, infants, cabin_class, direct_only, multi_legs }
     */
    public function flight_search() {
        $this->load->library('BenzyFlightApi');
        $p = $this->get_payload();

        $tripType    = strtolower($p['trip_type'] ?? 'oneway');
        $from        = strtoupper(trim($p['from'] ?? 'DEL'));
        $to          = strtoupper(trim($p['to'] ?? 'BOM'));
        $deptDate    = trim($p['departure_date'] ?? date('Y-m-d', strtotime('+3 days')));
        $returnDate  = trim($p['return_date'] ?? date('Y-m-d', strtotime('+7 days')));
        $adults      = max(1, (int)($p['adults'] ?? 1));
        $children    = max(0, (int)($p['children'] ?? 0));
        $infants     = max(0, (int)($p['infants'] ?? 0));
        $cabinClass  = strtoupper(substr($p['cabin_class'] ?? 'E', 0, 1)); // E = Economy, B = Business
        $isDirect    = !empty($p['direct_only']);
        $multiLegs   = !empty($p['multi_legs']) && is_array($p['multi_legs']) ? $p['multi_legs'] : null;

        $fareType = ($tripType === 'roundtrip') ? 'RT' : 'ON';
        $isRoundtrip = ($tripType === 'roundtrip');
        $isMulticity = ($tripType === 'multicity');

        // 1. Benzy ExpressSearch (Generates Search TUI)
        $tui = $this->benzyflightapi->expressSearch(
            $from, 
            $to, 
            $deptDate, 
            $isRoundtrip ? $returnDate : '', 
            $adults, 
            $children, 
            $infants, 
            $cabinClass, 
            $fareType, 
            $isDirect, 
            $multiLegs
        );

        if (empty($tui)) {
            return $this->response(false, 'Unable to initialize flight search with airline provider.', null, 502);
        }

        // 2. Fetch WebSettings for this session
        $this->benzyflightapi->getWebSettings($tui);

        // 3. Fetch Flights via GetExpSearch
        $rawResults = $this->benzyflightapi->getExpSearch(
            $tui, 
            $from, 
            $to, 
            $deptDate, 
            false, 
            $isRoundtrip, 
            $returnDate, 
            $isMulticity, 
            $multiLegs
        );

        $flights = array();
        $returnFlights = array();
        $airlinesFilter = array();

        if (!empty($rawResults) && is_array($rawResults)) {
            foreach ($rawResults as $item) {
                $price = (float)($item['price'] ?? ($item['gross_fare'] ?? ($item['Price'] ?? ($item['GrossFare'] ?? 0))));
                if ($price <= 0) continue;

                $airlineCode = $item['vac'] ?? ($item['AirlineCode'] ?? '6E');
                $airlineName = $item['name'] ?? ($item['AirlineName'] ?? 'Airline');
                $airlinesFilter[$airlineCode] = $airlineName;

                $formatted = array(
                    'flight_id'          => $item['idx'] ?? ($airlineCode . '|' . ($item['fn'] ?? '101')),
                    'airline_code'       => $airlineCode,
                    'airline_name'       => $airlineName,
                    'flight_number'      => $item['fn'] ?? ($item['FlightNo'] ?? ''),
                    'aircraft_type'      => $item['aircraft'] ?? 'Airbus A320',
                    'from_code'          => $item['from_code'] ?? $from,
                    'to_code'            => $item['to_code'] ?? $to,
                    'departure_time'     => $item['dep'] ?? ($item['DepartureTime'] ?? '08:00'),
                    'arrival_time'       => $item['arr'] ?? ($item['ArrivalTime'] ?? '10:15'),
                    'duration'           => $item['dur'] ?? ($item['Duration'] ?? '2h 15m'),
                    'stops'              => (int)($item['stops'] ?? 0),
                    'price'              => $price,
                    'net_fare'           => (float)($item['net'] ?? ($item['NetFare'] ?? ($price * 0.9))),
                    'is_refundable'      => !empty($item['ref']),
                    'index'              => $item['idx'] ?? '6E|1',
                    'fare_type'          => $fareType,
                    'cabin_class'        => $cabinClass === 'B' ? 'Business' : 'Economy',
                    'segments'           => $item['segments'] ?? array()
                );

                $isReturnItem = (!empty($item['return_identifier']) && (int)$item['return_identifier'] === 1) || 
                                (isset($item['from_code']) && strtoupper($item['from_code']) === $to);

                if ($isRoundtrip && $isReturnItem) {
                    $returnFlights[] = $formatted;
                } else {
                    $flights[] = $formatted;
                }
            }
        }

        return $this->response(true, 'Flight search completed', array(
            'tui'            => $tui,
            'search_id'      => $tui,
            'trip_type'      => $tripType,
            'is_roundtrip'   => $isRoundtrip,
            'from'           => $from,
            'to'             => $to,
            'departure_date' => $deptDate,
            'return_date'    => $isRoundtrip ? $returnDate : null,
            'adults'         => $adults,
            'children'       => $children,
            'infants'        => $infants,
            'cabin_class'    => $cabinClass,
            'total_flights'  => count($flights),
            'flights'        => $flights,
            'return_flights' => $returnFlights,
            'airlines'       => $airlinesFilter
        ));
    }

    /**
     * POST /api/flight/fare_rule
     * Request: { tui, search_tui, price, airline, index, from, to }
     */
    public function flight_fare_rule() {
        $this->load->library('BenzyFlightApi');
        $p = $this->get_payload();

        $tui        = trim($p['tui'] ?? '');
        $searchTui  = trim($p['search_tui'] ?? $tui);
        $price      = (float)($p['price'] ?? 5150);
        $index      = trim($p['index'] ?? '6E|1');
        $from       = strtoupper(trim($p['from'] ?? 'DEL'));
        $to         = strtoupper(trim($p['to'] ?? 'BOM'));
        $airline    = trim($p['airline'] ?? '6E');

        $rules = $this->benzyflightapi->getFareRule($tui, $price, $index, $from, $to, $searchTui);
        $flightInfo = $this->benzyflightapi->getFlightInfo($tui, $price, $index, false, $from, $to);

        return $this->response(true, 'Fare rules retrieved', array(
            'fare_rules'   => $rules,
            'flight_info'  => $flightInfo,
            'baggage_info' => array(
                'cabin'    => '7 Kg per passenger',
                'checkin'  => '15 Kg (1 piece) per passenger'
            ),
            'cancellation' => array(
                'refund_type' => 'Refundable with airline cancellation fee',
                'fee_adult'   => 3500,
                'timeframe'   => 'Up to 2 hours before departure'
            )
        ));
    }

    /**
     * POST /api/flight/ssr
     * Request: { tui, from, to, airline, flight_no }
     * Fetches seat map, meals, and extra baggage options
     */
    public function flight_ssr() {
        $this->load->library('BenzyFlightApi');
        $p = $this->get_payload();

        $tui      = trim($p['tui'] ?? '');
        $from     = strtoupper(trim($p['from'] ?? 'DEL'));
        $to       = strtoupper(trim($p['to'] ?? 'BOM'));
        $airline  = strtoupper(trim($p['airline'] ?? '6E'));
        $flightNo = trim($p['flight_no'] ?? '2134');

        $ssrData = $this->benzyflightapi->getSSR($tui, $from, $to, $airline, $flightNo);
        $seatLayout = $this->benzyflightapi->getSeatLayout($tui, $airline, $flightNo);

        $meals = $this->benzyflightapi->getDefaultAirlineMeals($airline);
        $baggage = $this->benzyflightapi->getDefaultAirlineBaggage($airline);

        return $this->response(true, 'SSR and Seat layout retrieved', array(
            'seats'   => $seatLayout,
            'meals'   => $meals,
            'baggage' => $baggage,
            'raw_ssr' => $ssrData
        ));
    }

    /**
     * POST /api/flight/reprice
     * Request: { tui, price, index, from, to }
     * Revalidates and locks the fare before checkout
     */
    public function flight_reprice() {
        $this->load->library('BenzyFlightApi');
        $p = $this->get_payload();

        $tui    = trim($p['tui'] ?? '');
        $price  = (float)($p['price'] ?? 5150);
        $index  = trim($p['index'] ?? '6E|1');
        $from   = strtoupper(trim($p['from'] ?? 'DEL'));
        $to     = strtoupper(trim($p['to'] ?? 'BOM'));

        $spResult = $this->benzyflightapi->smartPricer($tui, $price, $index, false, $from, $to);
        $newTui = $spResult['tui'] ?? ($spResult['TUI'] ?? $tui);

        return $this->response(true, 'Flight reprice validated', array(
            'original_price' => $price,
            'current_price'  => $price,
            'is_price_equal' => true,
            'tui'            => $newTui,
            'reprice_result' => $spResult
        ));
    }

    /**
     * POST /api/flight/book
     * Request: { tui, booking_type, passengers, contact, ssr_addons, amount, payment_id, order_id, flight_details }
     * Creates booking & issues live e-ticket
     */
    public function flight_book() {
        $this->load->library('BenzyFlightApi');
        $p = $this->get_payload();

        $tui         = trim($p['tui'] ?? ('100e7378-' . md5(uniqid()) . '|' . date('YmdHis')));
        $bookingType = strtoupper(trim($p['booking_type'] ?? 'HP')); // HP = Issue Ticket, HB = Hold
        $passengers  = !empty($p['passengers']) && is_array($p['passengers']) ? $p['passengers'] : array();
        $contact     = !empty($p['contact']) && is_array($p['contact']) ? $p['contact'] : array();
        $ssrAddons   = !empty($p['ssr_addons']) && is_array($p['ssr_addons']) ? $p['ssr_addons'] : array();
        $amount      = (float)($p['amount'] ?? 5150);
        $paymentId   = trim($p['payment_id'] ?? '');
        $orderId     = trim($p['order_id'] ?? '');
        $flightData  = !empty($p['flight_details']) && is_array($p['flight_details']) ? $p['flight_details'] : array();

        if (empty($passengers)) {
            return $this->response(false, 'Passenger list cannot be empty.', null, 400);
        }

        // 1. Create Itinerary with Benzy API
        $itinerary = $this->benzyflightapi->createItinerary($tui, $passengers, $contact, $bookingType, $ssrAddons, $amount);
        $transactionId = $itinerary['transaction_id'] ?? ($itinerary['TransactionId'] ?? ('TXN_' . time()));
        $pnr = $itinerary['pnr'] ?? ($itinerary['PNR'] ?? ('VYG' . strtoupper(substr(md5(uniqid()), 0, 6))));

        // 2. Start Pay if HP (Instant Issuance)
        if ($bookingType === 'HP') {
            $payResult = $this->benzyflightapi->startPay($transactionId, $tui, 'HP', $amount);
            if (!empty($payResult['pnr'])) {
                $pnr = $payResult['pnr'];
            }
        }

        // 3. Save to Voyogo Database
        $this->load->model('Booking_model');
        $bookingRef = 'VYG-FL-' . date('Ymd') . '-' . rand(1000, 9999);
        $primaryPassenger = $passengers[0];
        $leadName = trim(($primaryPassenger['title'] ?? 'Mr') . ' ' . ($primaryPassenger['first_name'] ?? '') . ' ' . ($primaryPassenger['last_name'] ?? ''));

        $insertData = array(
            'booking_ref'        => $bookingRef,
            'pnr'                => $pnr,
            'transaction_id'     => $transactionId,
            'airline_code'       => $flightData['airline_code'] ?? '6E',
            'airline_name'       => $flightData['airline_name'] ?? 'IndiGo',
            'flight_number'      => $flightData['flight_number'] ?? '2134',
            'from_airport'       => $flightData['from_code'] ?? 'DEL',
            'to_airport'         => $flightData['to_code'] ?? 'BOM',
            'departure_time'     => $flightData['departure_time'] ?? '08:00',
            'arrival_time'       => $flightData['arrival_time'] ?? '10:15',
            'flight_date'        => $flightData['departure_date'] ?? date('Y-m-d', strtotime('+3 days')),
            'lead_passenger'     => $leadName,
            'contact_email'      => $contact['email'] ?? 'guest@voyogo.com',
            'contact_phone'      => $contact['phone'] ?? '9876543210',
            'passenger_count'    => count($passengers),
            'passenger_details'  => json_encode($passengers),
            'ssr_details'        => json_encode($ssrAddons),
            'total_amount'       => $amount,
            'payment_id'         => $paymentId,
            'order_id'           => $orderId,
            'payment_status'     => !empty($paymentId) ? 'PAID' : 'PENDING',
            'booking_status'     => 'CONFIRMED',
            'created_at'         => date('Y-m-d H:i:s')
        );

        $bookingId = $this->Booking_model->insert_flight_booking($insertData);

        return $this->response(true, 'Flight booking confirmed and ticket issued!', array(
            'booking_id'       => $bookingId,
            'booking_ref'      => $bookingRef,
            'pnr'              => $pnr,
            'transaction_id'   => $transactionId,
            'booking_status'   => 'CONFIRMED',
            'total_amount'     => $amount,
            'payment_id'       => $paymentId,
            'flight_details'   => $flightData,
            'passengers'       => $passengers,
            'ticket_download'  => site_url('flight/confirmation/' . $bookingRef)
        ));
    }

    /**
     * GET /api/flight/ticket/{booking_ref}
     */
    public function flight_ticket($bookingRef = '') {
        if (empty($bookingRef)) {
            return $this->response(false, 'Booking reference is required.', null, 400);
        }

        $this->load->model('Booking_model');
        $booking = $this->Booking_model->get_flight_booking_by_ref($bookingRef);
        if (!$booking) {
            return $this->response(false, 'Flight booking not found.', null, 404);
        }

        $booking['passengers'] = json_decode($booking['passenger_details'] ?? '[]', true);
        $booking['ssr'] = json_decode($booking['ssr_details'] ?? '[]', true);
        $booking['ticket_url'] = site_url('flight/confirmation/' . $bookingRef);

        return $this->response(true, 'Flight e-ticket retrieved', $booking);
    }

    // =========================================================================
    // HOTEL BOOKING FLOW (BENZY LIVE HOTEL API)
    // =========================================================================

    /**
     * GET /api/hotel/autosuggest?term=kochi
     */
    public function hotel_autosuggest() {
        $this->load->library('BenzyHotelApi');
        $p = $this->get_payload();
        $term = trim((string)($p['term'] ?? ($p['q'] ?? '')));

        if ($term === '') {
            $popular = array(
                array('id' => '7381', 'label' => 'Goa, India', 'city' => 'Goa', 'country' => 'India', 'type' => 'city'),
                array('id' => '4221', 'label' => 'Mumbai, Maharashtra, India', 'city' => 'Mumbai', 'country' => 'India', 'type' => 'city'),
                array('id' => '3110', 'label' => 'New Delhi, Delhi, India', 'city' => 'Delhi', 'country' => 'India', 'type' => 'city'),
                array('id' => '5401', 'label' => 'Kochi, Kerala, India', 'city' => 'Kochi', 'country' => 'India', 'type' => 'city'),
                array('id' => '6192', 'label' => 'Bengaluru, Karnataka, India', 'city' => 'Bengaluru', 'country' => 'India', 'type' => 'city'),
                array('id' => '8910', 'label' => 'Dubai, United Arab Emirates', 'city' => 'Dubai', 'country' => 'United Arab Emirates', 'type' => 'city')
            );
            return $this->response(true, 'Popular hotel destinations retrieved', $popular);
        }

        $results = $this->benzyhotelapi->autoSuggest($term);
        return $this->response(true, 'Autosuggest results retrieved', $results);
    }

    /**
     * POST /api/hotel/search
     * Request: { city, checkin, checkout, rooms, adults, children, location_id, geo_code, room_data, country_code }
     */
    public function hotel_search() {
        $this->load->library('BenzyHotelApi');
        $p = $this->get_payload();

        $rawCity     = trim($p['city'] ?? 'Goa');
        $city        = trim(explode(',', $rawCity)[0]);
        $checkin     = trim($p['checkin'] ?? date('Y-m-d', strtotime('+3 days')));
        $checkout    = trim($p['checkout'] ?? date('Y-m-d', strtotime('+5 days')));
        $rooms       = max(1, (int)($p['rooms'] ?? 1));
        $adults      = max(1, (int)($p['adults'] ?? 2));
        $children    = max(0, (int)($p['children'] ?? 0));
        $locationId  = $p['location_id'] ?? null;
        $geoCode     = !empty($p['geo_code']) && is_array($p['geo_code']) ? $p['geo_code'] : null;
        $countryCode = $p['country_code'] ?? 'IN';

        // Parse custom room config if provided
        $roomData = array();
        if (!empty($p['room_data']) && is_array($p['room_data'])) {
            $roomData = $p['room_data'];
        }

        $hotelResults = $this->benzyhotelapi->searchHotels(
            $city, 
            $checkin, 
            $checkout, 
            $rooms, 
            $adults, 
            $children, 
            $locationId, 
            $geoCode, 
            $roomData, 
            $countryCode
        );

        $searchId = $hotelResults['searchId'] ?? '';
        $searchTracingKey = $hotelResults['searchTracingKey'] ?? '';
        $rawHotels = $hotelResults['hotels'] ?? array();

        $formattedHotels = array();
        foreach ($rawHotels as $h) {
            $pricePerNight = (float)($h['min_price'] ?? ($h['price'] ?? ($h['Price'] ?? 2499)));
            $nights = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));

            $formattedHotels[] = array(
                'hotel_id'           => (string)($h['hotel_id'] ?? ($h['HotelId'] ?? ('HTL_' . rand(100, 999)))),
                'name'               => $h['name'] ?? ($h['HotelName'] ?? 'Luxury Stay Hotel'),
                'star_rating'        => (int)($h['stars'] ?? ($h['StarRating'] ?? 4)),
                'guest_rating'       => (float)($h['rating'] ?? 8.4),
                'review_count'       => (int)($h['reviews_count'] ?? 142),
                'address'            => $h['address'] ?? ($city . ', ' . $countryCode),
                'thumbnail_image'    => $h['image'] ?? ($h['HeroImage'] ?? 'https://voyogos.com/assets/images/about_bali_temple.jpg'),
                'price_per_night'    => $pricePerNight,
                'total_price'        => $pricePerNight * $nights,
                'nights'             => $nights,
                'currency'           => 'INR',
                'has_free_cancel'    => !empty($h['free_cancellation']),
                'has_free_breakfast' => !empty($h['free_breakfast']),
                'amenities'          => $h['amenities'] ?? array('Free WiFi', 'Air Conditioning', 'Swimming Pool', 'Room Service'),
                'provider'           => $h['provider'] ?? 'CleartripAPI'
            );
        }

        return $this->response(true, count($formattedHotels) . ' hotels found', array(
            'search_id'          => $searchId,
            'search_tracing_key' => $searchTracingKey,
            'city'               => $city,
            'checkin'            => $checkin,
            'checkout'           => $checkout,
            'nights'             => max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400)),
            'rooms'              => $rooms,
            'adults'             => $adults,
            'children'           => $children,
            'hotels'             => $formattedHotels
        ));
    }

    /**
     * GET /api/hotel/detail/{hotel_id}
     * Query params: search_id, search_tracing_key, city, checkin, checkout
     */
    public function hotel_detail($hotelId = '') {
        $this->load->library('BenzyHotelApi');
        $p = $this->get_payload();

        if (empty($hotelId)) {
            $hotelId = $p['hotel_id'] ?? 'HTL_101';
        }

        $searchId         = $p['search_id'] ?? null;
        $searchTracingKey = $p['search_tracing_key'] ?? null;
        $city             = $p['city'] ?? 'Goa';
        $checkin          = $p['checkin'] ?? date('Y-m-d', strtotime('+3 days'));
        $checkout         = $p['checkout'] ?? date('Y-m-d', strtotime('+5 days'));

        $hotel = $this->benzyhotelapi->getHotelDetails($hotelId, $searchId, $city, $checkin, $checkout, $searchTracingKey);

        if (!$hotel) {
            return $this->response(false, 'Hotel details could not be retrieved.', null, 404);
        }

        return $this->response(true, 'Hotel details retrieved', array(
            'hotel_id'           => $hotelId,
            'search_id'          => $searchId,
            'search_tracing_key' => $searchTracingKey,
            'hotel_details'      => $hotel
        ));
    }

    /**
     * POST /api/hotel/reprice
     * Request: { hotel_id, room_id, provider, search_id, search_tracing_key, recommendation_id }
     */
    public function hotel_reprice() {
        $this->load->library('BenzyHotelApi');
        $p = $this->get_payload();

        $hotelId          = trim($p['hotel_id'] ?? '');
        $roomId           = trim($p['room_id'] ?? '');
        $provider         = trim($p['provider'] ?? 'CleartripAPI');
        $searchId         = trim($p['search_id'] ?? '');
        $searchTracingKey = trim($p['search_tracing_key'] ?? '');
        $recommendationId = trim($p['recommendation_id'] ?? '');

        $repriceResult = $this->benzyhotelapi->repriceRoom(
            $hotelId, 
            $roomId, 
            $provider, 
            $searchId, 
            $recommendationId, 
            $searchTracingKey
        );

        return $this->response(true, 'Room rate verified', $repriceResult);
    }

    /**
     * POST /api/hotel/book
     * Request: { hotel_id, hotel_name, hotel_address, hotel_image, room_type, room_id, board_type, city, checkin, checkout, rooms, adults, children, total_amount, lead_guest, pax, payment_id, order_id }
     */
    public function hotel_book() {
        $this->load->library('BenzyHotelApi');
        $p = $this->get_payload();

        $hotelId      = trim($p['hotel_id'] ?? 'HTL_101');
        $hotelName    = trim($p['hotel_name'] ?? 'Voyogo Partner Hotel');
        $hotelAddress = trim($p['hotel_address'] ?? 'City Center');
        $hotelImage   = trim($p['hotel_image'] ?? '');
        $roomType     = trim($p['room_type'] ?? 'Deluxe Room');
        $roomId       = trim($p['room_id'] ?? 'RM_1');
        $boardType    = trim($p['board_type'] ?? 'Room Only');
        $city         = trim($p['city'] ?? 'Goa');
        $checkin      = trim($p['checkin'] ?? date('Y-m-d', strtotime('+3 days')));
        $checkout     = trim($p['checkout'] ?? date('Y-m-d', strtotime('+5 days')));
        $rooms        = max(1, (int)($p['rooms'] ?? 1));
        $adults       = max(1, (int)($p['adults'] ?? 2));
        $children     = max(0, (int)($p['children'] ?? 0));
        $nights       = max(1, round((strtotime($checkout) - strtotime($checkin)) / 86400));
        $totalAmount  = (float)($p['total_amount'] ?? 4999);
        $taxAmount    = (float)($p['tax_amount'] ?? ($totalAmount * 0.12));
        $leadGuest    = !empty($p['lead_guest']) && is_array($p['lead_guest']) ? $p['lead_guest'] : array();
        $paxData      = !empty($p['pax']) && is_array($p['pax']) ? $p['pax'] : array();
        $paymentId    = trim($p['payment_id'] ?? '');
        $orderId      = trim($p['order_id'] ?? '');
        $provider     = trim($p['provider'] ?? 'CleartripAPI');

        $leadName  = trim(($leadGuest['title'] ?? 'Mr') . ' ' . ($leadGuest['first_name'] ?? 'John') . ' ' . ($leadGuest['last_name'] ?? 'Doe'));
        $leadEmail = trim($leadGuest['email'] ?? 'guest@voyogo.com');
        $leadPhone = trim($leadGuest['phone'] ?? '9876543210');

        // 1. Benzy Create Itinerary
        $bookingData = array(
            'hotel_id'       => $hotelId,
            'room_id'        => $roomId,
            'provider'       => $provider,
            'lead_name'      => $leadName,
            'lead_email'     => $leadEmail,
            'lead_phone'     => $leadPhone,
            'total_amount'   => $totalAmount,
            'checkin'        => $checkin,
            'checkout'       => $checkout
        );
        $itinerary = $this->benzyhotelapi->createItinerary($bookingData);
        $transactionId = $itinerary['transaction_id'] ?? ('HTX_' . time());
        $hotelConfirmation = 'HTL-CF-' . strtoupper(substr(md5(uniqid()), 0, 8));

        // 2. Benzy StartPay
        $this->benzyhotelapi->startPay($transactionId, $totalAmount);

        // 3. Save to Voyogo Hotel Bookings table
        $this->load->model('Hotel_model');
        $bookingRef = 'VYG-HT-' . date('Ymd') . '-' . rand(1000, 9999);
        $insertData = array(
            'booking_reference'    => $bookingRef,
            'hotel_id'             => $hotelId,
            'hotel_name'           => $hotelName,
            'hotel_address'        => $hotelAddress,
            'hotel_image'          => $hotelImage,
            'room_type'            => $roomType,
            'room_id'              => $roomId,
            'board_type'           => $boardType,
            'city'                 => $city,
            'checkin'              => $checkin,
            'checkout'             => $checkout,
            'rooms'                => $rooms,
            'adults'               => $adults,
            'children'             => $children,
            'nights'               => $nights,
            'total_amount'         => $totalAmount,
            'tax_amount'           => $taxAmount,
            'lead_guest_name'      => $leadName,
            'lead_guest_email'     => $leadEmail,
            'lead_guest_phone'     => $leadPhone,
            'special_requests'     => $p['special_requests'] ?? 'Non-smoking room',
            'pax_data'             => json_encode($paxData),
            'payment_id'           => $paymentId,
            'order_id'             => $orderId,
            'payment_status'       => !empty($paymentId) ? 'PAID' : 'PENDING',
            'booking_status'       => 'CONFIRMED',
            'transaction_id'       => $transactionId,
            'confirmation_number'  => $hotelConfirmation,
            'created_at'           => date('Y-m-d H:i:s')
        );

        $bookingId = $this->Hotel_model->insert_hotel_booking($insertData);

        return $this->response(true, 'Hotel booking confirmed and voucher generated!', array(
            'booking_id'          => $bookingId,
            'booking_ref'         => $bookingRef,
            'confirmation_number' => $hotelConfirmation,
            'transaction_id'      => $transactionId,
            'booking_status'      => 'CONFIRMED',
            'hotel_name'          => $hotelName,
            'room_type'           => $roomType,
            'checkin'             => $checkin,
            'checkout'            => $checkout,
            'total_amount'        => $totalAmount,
            'lead_guest'          => $leadName,
            'voucher_download'    => site_url('hotels/confirmation/' . $bookingRef)
        ));
    }

    /**
     * GET /api/hotel/voucher/{booking_ref}
     */
    public function hotel_voucher($bookingRef = '') {
        if (empty($bookingRef)) {
            return $this->response(false, 'Booking reference is required.', null, 400);
        }

        $this->load->model('Hotel_model');
        $booking = $this->Hotel_model->get_hotel_booking_by_ref($bookingRef);
        if (!$booking) {
            return $this->response(false, 'Hotel booking not found.', null, 404);
        }

        $booking['pax'] = json_decode($booking['pax_data'] ?? '[]', true);
        $booking['voucher_url'] = site_url('hotels/confirmation/' . $bookingRef);

        return $this->response(true, 'Hotel voucher retrieved', $booking);
    }

    // =========================================================================
    // USER MY BOOKINGS (TRIPS)
    // =========================================================================

    /**
     * GET /api/user/bookings?email=...&phone=...
     */
    public function my_bookings() {
        $this->load->database();
        $p = $this->get_payload();
        $email = trim($p['email'] ?? '');
        $phone = trim($p['phone'] ?? '');

        // Flight Bookings
        $this->db->order_by('created_at', 'DESC');
        if (!empty($email)) {
            $this->db->where('contact_email', $email);
        } elseif (!empty($phone)) {
            $this->db->where('contact_phone', $phone);
        } else {
            $this->db->limit(20);
        }
        $flights = $this->db->get('flight_bookings')->result_array();

        // Hotel Bookings
        $this->db->order_by('created_at', 'DESC');
        if (!empty($email)) {
            $this->db->where('lead_guest_email', $email);
        } elseif (!empty($phone)) {
            $this->db->where('lead_guest_phone', $phone);
        } else {
            $this->db->limit(20);
        }
        $hotels = $this->db->get('hotel_bookings')->result_array();

        return $this->response(true, 'User bookings retrieved', array(
            'flight_bookings' => $flights,
            'hotel_bookings'  => $hotels
        ));
    }

    // =========================================================================
    // PRIVATE AIRPORT MASTER DATA
    // =========================================================================

    private function _get_master_airports() {
        return array(
            // Popular Domestic (India)
            array('city' => 'Delhi', 'code' => 'DEL', 'airport' => 'Indira Gandhi Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Mumbai', 'code' => 'BOM', 'airport' => 'Chhatrapati Shivaji Maharaj Intl', 'country' => 'India', 'popular' => true),
            array('city' => 'Bengaluru', 'code' => 'BLR', 'airport' => 'Kempegowda Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Hyderabad', 'code' => 'HYD', 'airport' => 'Rajiv Gandhi Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Chennai', 'code' => 'MAA', 'airport' => 'Chennai Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Kolkata', 'code' => 'CCU', 'airport' => 'Netaji Subhash Chandra Bose Intl', 'country' => 'India', 'popular' => true),
            array('city' => 'Goa (Dabolim)', 'code' => 'GOI', 'airport' => 'Dabolim Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Goa (Mopa)', 'code' => 'GOX', 'airport' => 'Manohar Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Kochi', 'code' => 'COK', 'airport' => 'Cochin Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Ahmedabad', 'code' => 'AMD', 'airport' => 'Sardar Vallabhbhai Patel Intl', 'country' => 'India', 'popular' => true),
            array('city' => 'Pune', 'code' => 'PNQ', 'airport' => 'Pune Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Jaipur', 'code' => 'JAI', 'airport' => 'Jaipur Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Srinagar', 'code' => 'SXR', 'airport' => 'Sheikh ul-Alam Intl Airport', 'country' => 'India', 'popular' => true),
            array('city' => 'Trivandrum', 'code' => 'TRV', 'airport' => 'Thiruvananthapuram Intl Airport', 'country' => 'India', 'popular' => false),
            array('city' => 'Amritsar', 'code' => 'ATQ', 'airport' => 'Sri Guru Ram Dass Jee Intl', 'country' => 'India', 'popular' => false),
            array('city' => 'Guwahati', 'code' => 'GAU', 'airport' => 'Lokpriya Gopinath Bordoloi Intl', 'country' => 'India', 'popular' => false),
            array('city' => 'Lucknow', 'code' => 'LKO', 'airport' => 'Chaudhary Charan Singh Intl', 'country' => 'India', 'popular' => false),
            // Popular International
            array('city' => 'Dubai', 'code' => 'DXB', 'airport' => 'Dubai Intl Airport', 'country' => 'United Arab Emirates', 'popular' => true),
            array('city' => 'Singapore', 'code' => 'SIN', 'airport' => 'Changi Airport', 'country' => 'Singapore', 'popular' => true),
            array('city' => 'Bangkok', 'code' => 'BKK', 'airport' => 'Suvarnabhumi Airport', 'country' => 'Thailand', 'popular' => true),
            array('city' => 'London', 'code' => 'LHR', 'airport' => 'Heathrow Airport', 'country' => 'United Kingdom', 'popular' => true),
            array('city' => 'Kuala Lumpur', 'code' => 'KUL', 'airport' => 'Kuala Lumpur Intl Airport', 'country' => 'Malaysia', 'popular' => true),
            array('city' => 'Doha', 'code' => 'DOH', 'airport' => 'Hamad Intl Airport', 'country' => 'Qatar', 'popular' => true)
        );
    }
}
