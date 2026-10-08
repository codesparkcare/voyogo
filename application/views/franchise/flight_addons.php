<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sessionUser = $this->session->userdata('user');
$isUserLoggedIn = true;

// Flight and fare details passed from Controller / Session
$post_data = $post_data ?? array();
$flight = $flight ?? ($post_data['flight'] ?? null);
$return_flight = $return_flight ?? ($post_data['return_flight'] ?? null);

$flight_number = $flight_number ?? ($flight['flight_number'] ?? ($post_data['flight_number'] ?? 'SG-304'));
$airline_name = $airline_name ?? ($flight['airline_name'] ?? ($post_data['airline_name'] ?? 'SpiceJet'));
$origin = strtoupper(trim($origin ?? ($flight['from_code'] ?? ($post_data['origin'] ?? 'DEL'))));
$destination = strtoupper(trim($destination ?? ($flight['to_code'] ?? ($post_data['destination'] ?? 'BOM'))));
$departure_date = $departure_date ?? ($flight['departure_date'] ?? ($post_data['departure_date'] ?? '2026-10-28'));
$departure_time = $departure_time ?? ($flight['departure_time'] ?? ($post_data['departure_time'] ?? '11:00'));
$arrival_time = $arrival_time ?? ($flight['arrival_time'] ?? ($post_data['arrival_time'] ?? '15:45'));
$duration = $duration ?? ($flight['duration'] ?? ($post_data['duration'] ?? '04h 45m'));
$stops = (int)($stops ?? ($flight['stops'] ?? ($post_data['stops'] ?? 1)));
$via = $via ?? ($flight['via'] ?? ($post_data['via'] ?? ''));

$is_roundtrip = !empty($is_roundtrip) || !empty($post_data['is_roundtrip']) || !empty($post_data['return_flight_number']) || !empty($return_flight);
$return_flight_number = $return_flight_number ?? ($return_flight['flight_number'] ?? ($post_data['return_flight_number'] ?? 'AI-632'));
$return_airline_name = $return_airline_name ?? ($return_flight['airline_name'] ?? ($post_data['return_airline_name'] ?? 'Air India'));
$return_origin = strtoupper(trim($return_origin ?? ($return_flight['from_code'] ?? ($post_data['return_origin'] ?? 'BOM'))));
$return_destination = strtoupper(trim($return_destination ?? ($return_flight['to_code'] ?? ($post_data['return_destination'] ?? 'DEL'))));
$return_departure_date = $return_departure_date ?? ($return_flight['departure_date'] ?? ($post_data['return_departure_date'] ?? '2026-10-31'));
$return_departure_time = $return_departure_time ?? ($return_flight['departure_time'] ?? ($post_data['return_departure_time'] ?? '19:45'));
$return_arrival_time = $return_arrival_time ?? ($return_flight['arrival_time'] ?? ($post_data['return_arrival_time'] ?? '01:00'));
$return_duration = $return_duration ?? ($return_flight['duration'] ?? ($post_data['return_duration'] ?? '05h 15m'));
$return_stops = (int)($return_stops ?? ($return_flight['stops'] ?? ($post_data['return_stops'] ?? 1)));

$airportDb = array(
    'BOM' => array('city' => 'Mumbai', 'name' => 'Chhatrapati Shivaji Maharaj Intl Airport'),
    'DEL' => array('city' => 'New Delhi', 'name' => 'Indira Gandhi Intl Airport'),
    'BLR' => array('city' => 'Bengaluru', 'name' => 'Kempegowda Intl Airport'),
    'MAA' => array('city' => 'Chennai', 'name' => 'Chennai Intl Airport'),
    'HYD' => array('city' => 'Hyderabad', 'name' => 'Rajiv Gandhi Intl Airport'),
    'CCU' => array('city' => 'Kolkata', 'name' => 'Netaji Subhash Chandra Bose Intl Airport'),
    'GOI' => array('city' => 'Goa', 'name' => 'Dabolim Airport'),
    'GOX' => array('city' => 'Goa', 'name' => 'Manohar Intl Airport (Mopa)'),
    'COK' => array('city' => 'Kochi', 'name' => 'Cochin Intl Airport'),
    'AMD' => array('city' => 'Ahmedabad', 'name' => 'Sardar Vallabhbhai Patel Intl Airport'),
    'PNQ' => array('city' => 'Pune', 'name' => 'Pune Airport'),
    'JAI' => array('city' => 'Jaipur', 'name' => 'Jaipur Intl Airport'),
    'LKO' => array('city' => 'Lucknow', 'name' => 'Chaudhary Charan Singh Intl Airport'),
    'DXB' => array('city' => 'Dubai', 'name' => 'Dubai Intl Airport'),
    'SHJ' => array('city' => 'Sharjah', 'name' => 'Sharjah Intl Airport'),
    'DOH' => array('city' => 'Doha', 'name' => 'Hamad Intl Airport'),
    'SIN' => array('city' => 'Singapore', 'name' => 'Changi Airport'),
    'BKK' => array('city' => 'Bangkok', 'name' => 'Suvarnabhumi Airport'),
    'KUL' => array('city' => 'Kuala Lumpur', 'name' => 'Kuala Lumpur Intl Airport')
);

$originCity = $airportDb[$origin]['city'] ?? $origin;
$destCity = $airportDb[$destination]['city'] ?? $destination;
$retOriginCity = $airportDb[$return_origin]['city'] ?? $return_origin;
$retDestCity = $airportDb[$return_destination]['city'] ?? $return_destination;

$base_fare = (float)($base_fare ?? ($post_data['base_fare'] ?? ($post_data['net_amount'] ?? 8739)));
$taxes = (float)($taxes ?? ($post_data['taxes'] ?? 1531));
$insurance_amount = (float)($insurance_amount ?? ($post_data['insurance_amount'] ?? 0));
$discount_amount = (float)($discount_amount ?? ($post_data['discount_amount'] ?? 18));
$promo_code = $promo_code ?? ($post_data['promo_code'] ?? 'ATFLY');

$fare_tier = $fare_tier ?? ($post_data['fare_tier'] ?? 'Value');
$fare_tier_price_delta = (float)($fare_tier_price_delta ?? ($post_data['fare_tier_price_delta'] ?? 0));
$safety_cancellation_type = $safety_cancellation_type ?? ($post_data['safety_cancellation_type'] ?? '');
$safety_cancellation_amount = (float)($safety_cancellation_amount ?? ($post_data['safety_cancellation_amount'] ?? 0));

$allPaxList = array();
if (!empty($passengers) && is_array($passengers)) {
    foreach ($passengers as $pIdx => $p) {
        $pTitle = $p['title'] ?? 'Mr';
        $pName  = !empty($p['name']) ? $p['name'] : trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? ''));
        if (empty($pName)) $pName = 'Passenger ' . ($pIdx + 1);
        $pType  = $p['type'] ?? 'Adult';
        $allPaxList[] = array(
            'index'        => $pIdx,
            'pax_id'       => $pIdx + 1,
            'title'        => $pTitle,
            'name'         => $pName,
            'first_name'   => $p['first_name'] ?? '',
            'last_name'    => $p['last_name'] ?? '',
            'type'         => $pType,
            'full_display' => trim($pTitle . ' ' . $pName)
        );
    }
} elseif (!empty($post_data['passenger_first_name']) && is_array($post_data['passenger_first_name'])) {
    foreach ($post_data['passenger_first_name'] as $pIdx => $pfn) {
        $pTitle = $post_data['passenger_title'][$pIdx] ?? 'Mr';
        $pln    = $post_data['passenger_last_name'][$pIdx] ?? '';
        $pName  = trim("$pfn $pln") ?: (!empty($post_data['passenger_name'][$pIdx]) ? $post_data['passenger_name'][$pIdx] : ('Passenger ' . ($pIdx + 1)));
        $pType  = $post_data['passenger_type'][$pIdx] ?? 'Adult';
        $allPaxList[] = array(
            'index'        => $pIdx,
            'pax_id'       => $pIdx + 1,
            'title'        => $pTitle,
            'name'         => $pName,
            'first_name'   => $pfn,
            'last_name'    => $pln,
            'type'         => $pType,
            'full_display' => trim($pTitle . ' ' . $pName)
        );
    }
} elseif (!empty($post_data['passenger_name']) && is_array($post_data['passenger_name'])) {
    foreach ($post_data['passenger_name'] as $pIdx => $pName) {
        $pTitle = $post_data['passenger_title'][$pIdx] ?? 'Mr';
        $pType  = $post_data['passenger_type'][$pIdx] ?? 'Adult';
        $allPaxList[] = array(
            'index'        => $pIdx,
            'pax_id'       => $pIdx + 1,
            'title'        => $pTitle,
            'name'         => $pName,
            'type'         => $pType,
            'full_display' => trim($pTitle . ' ' . $pName)
        );
    }
}

if (empty($allPaxList)) {
    $cName = !empty($post_data['contact_name']) ? trim($post_data['contact_name']) : 'Rahul Sharma';
    $allPaxList[] = array(
        'index'        => 0,
        'pax_id'       => 1,
        'title'        => 'Mr',
        'name'         => $cName,
        'type'         => 'Adult',
        'full_display' => 'Mr ' . $cName
    );
}

$firstPaxName = $allPaxList[0]['full_display'];
$total_passengers = count($allPaxList);
$initialGrandTotal = max(0, $base_fare + $taxes + $insurance_amount + $safety_cancellation_amount - $discount_amount);

// Enhance $addons_data with city labels for frontend JS consumption
if (!empty($addons_data['onward'])) {
    $addons_data['onward']['origin_city'] = $originCity;
    $addons_data['onward']['destination_city'] = $destCity;
    $addons_data['onward']['formatted_date'] = date('l, d M y', strtotime($departure_date));
    $addons_data['onward']['pill_date'] = date('D, d M y', strtotime($departure_date));
}
if (!empty($addons_data['return'])) {
    $addons_data['return']['origin_city'] = $retOriginCity;
    $addons_data['return']['destination_city'] = $retDestCity;
    $addons_data['return']['formatted_date'] = date('l, d M y', strtotime($return_departure_date));
    $addons_data['return']['pill_date'] = date('D, d M y', strtotime($return_departure_date));
}

$razorpay_settings = $this->Admin_model->get_razorpay_settings();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .akbar-addons-wrapper {
        background-color: #f1f5f9;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: #1e293b;
        padding: 24px 0 60px;
    }
    .akbar-addons-container {
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Top Connected Flight Bar (Matches Screenshot 1) */
    .akbar-flight-summary-bar {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,32,90,0.03);
        margin-bottom: 16px;
        overflow: hidden;
    }
    .flight-pill-grid {
        display: grid;
        grid-template-columns: 1fr <?php echo $is_roundtrip ? '1fr' : ''; ?>;
        position: relative;
    }
    .flight-pill-item {
        padding: 16px 22px;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
    }
    .flight-pill-item.with-border-right {
        border-right: 1px solid #e2e8f0;
    }
    .flight-route-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .flight-sub-info {
        font-size: 12.5px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .flight-badge-pill {
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 14px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .flight-badge-pill.onwards {
        background: #0284c7;
        color: #ffffff;
    }
    .flight-badge-pill.return {
        background: #0284c7;
        color: #ffffff;
    }
    .flight-chevron-icon {
        color: #64748b;
        font-size: 13px;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .view-all-details-strip {
        text-align: center;
        padding: 8px 12px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        font-size: 12.5px;
        font-weight: 700;
        color: #0284c7;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* Sub Flight Information Strip (Screenshot 1 Exact) */
    .flight-sub-banner {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding: 0 4px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .flight-sub-banner-col {
        display: flex;
        flex-direction: column;
    }
    .flight-sub-banner-title {
        font-size: 15.5px;
        font-weight: 800;
        color: #0f172a;
    }
    .flight-sub-banner-desc {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 3px;
    }

    /* Addon Main Card */
    .akbar-addon-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,32,90,0.04);
        padding: 24px;
        margin-bottom: 24px;
    }
    .akbar-addon-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 16px 0;
    }

    /* Addon Navigation Tabs (Screenshots 1 & 2) */
    .akbar-tab-row {
        display: flex;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .akbar-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 14.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #1e293b;
    }
    .akbar-tab-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .akbar-tab-btn.active {
        background: #fef2f2;
        border-color: #f87171;
        color: #dc2626;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
    }

    /* Sector & Passenger Selectors */
    .sector-selector-strip {
        border-top: 1px dashed #cbd5e1;
        padding-top: 18px;
        margin-bottom: 20px;
    }
    .sector-btn-group {
        display: flex;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .sector-btn {
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 13.5px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sector-btn.active {
        background: #ffffff;
        border: 1.5px solid #0284c7;
        color: #0f172a;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.12);
    }
    .sector-btn.inactive {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
    }
    .sector-btn.inactive:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .passenger-strip-wrapper {
        border-top: 1px dotted #cbd5e1;
        padding-top: 14px;
        margin-bottom: 20px;
    }
    .passenger-strip-container {
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }
    .passenger-scroll-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 4px 2px;
        flex: 1;
    }
    .passenger-scroll-row::-webkit-scrollbar {
        display: none;
    }
    .pax-scroll-arrow-btn {
        width: 32px;
        height: 38px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .pax-scroll-arrow-btn:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #0284c7;
    }
    .passenger-selector-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        user-select: none;
        flex-shrink: 0;
    }
    .passenger-selector-pill.active {
        border: 1.5px solid #0284c7;
        background: #ffffff;
        color: #0284c7;
        box-shadow: 0 2px 10px rgba(2, 132, 199, 0.18);
    }
    .passenger-selector-pill.active .pax-user-icon {
        color: #0284c7;
    }
    .passenger-selector-pill.inactive {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
    }
    .passenger-selector-pill.inactive:hover {
        border-color: #94a3b8;
        background: #f8fafc;
        color: #0f172a;
    }
    .passenger-selector-pill.inactive .pax-user-icon {
        color: #64748b;
    }
    .pax-addon-badge {
        font-size: 11px;
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: 800;
        margin-left: 4px;
    }

    /* Cards Grid (Meals & Baggage) */
    .addon-items-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 20px;
    }
    @media (max-width: 900px) {
        .addon-items-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 540px) {
        .addon-items-grid {
            grid-template-columns: 1fr;
        }
    }

    .akbar-service-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 16px;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .akbar-service-card:hover {
        border-color: #0284c7;
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.1);
        transform: translateY(-2px);
    }
    .akbar-service-card.selected {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 4px 16px rgba(2, 132, 199, 0.15);
    }

    .card-radio-circle {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .akbar-service-card.selected .card-radio-circle {
        border-color: #0284c7;
        background: #0284c7;
    }
    .card-radio-circle::after {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ffffff;
        display: none;
    }
    .akbar-service-card.selected .card-radio-circle::after {
        display: block;
    }

    .card-img-wrapper {
        min-height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px;
    }

    /* Seat Selection Fuselage Styles (Screenshot 2 Match) */
    .seat-cabin-container {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 24px;
        align-items: flex-start;
        margin-top: 20px;
    }
    @media (max-width: 820px) {
        .seat-cabin-container {
            grid-template-columns: 1fr;
        }
    }
    .seat-legend-box {
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }
    .legend-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .legend-swatch {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }

    .airplane-fuselage {
        max-width: 380px;
        margin: 0 auto;
        border: 1.5px solid #cbd5e1;
        border-radius: 140px 140px 24px 24px;
        background: #ffffff;
        padding: 24px 16px 24px;
        position: relative;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .plane-nose-front {
        text-align: center;
        margin-bottom: 20px;
    }
    .plane-nose-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1.5px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 4px;
    }
    .plane-front-text {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
    }
    .exit-marker-left {
        position: absolute;
        left: -18px;
        top: 20px;
        font-size: 11px;
        font-weight: 800;
        color: #f97316;
        transform: rotate(-90deg);
    }
    .exit-marker-right {
        position: absolute;
        right: -18px;
        top: 20px;
        font-size: 11px;
        font-weight: 800;
        color: #f97316;
        transform: rotate(90deg);
    }
    .seat-grid-columns {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
    }
    .seat-col-header {
        width: 32px;
        text-align: center;
    }
    .seat-aisle-header {
        width: 24px;
    }

    .fuselage-scroll-area {
        max-height: 540px;
        overflow-y: auto;
        padding: 4px 4px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }
    .fuselage-scroll-area::-webkit-scrollbar {
        width: 6px;
    }
    .fuselage-scroll-area::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 3px;
    }
    .fuselage-scroll-area::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .fuselage-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .plane-seat-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 6px;
        position: relative;
    }
    .plane-seat-num {
        width: 16px;
        text-align: center;
        font-size: 11px;
        font-weight: 800;
        color: #0f172a;
    }
    .fuselage-seat {
        width: 32px;
        height: 32px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10.5px;
        font-weight: 700;
        transition: all 0.15s ease;
        user-select: none;
        box-sizing: border-box;
    }
    .fuselage-seat.tier-orange {
        background: #fdba74;
        border: 1px solid #fb923c;
        color: #7c2d12;
    }
    .fuselage-seat.tier-yellow {
        background: #fde68a;
        border: 1px solid #fcd34d;
        color: #78350f;
    }
    .fuselage-seat.tier-blue {
        background: #bae6fd;
        border: 1px solid #7dd3fc;
        color: #0369a1;
    }
    .fuselage-seat.tier-green {
        background: #bbf7d0;
        border: 1px solid #86efac;
        color: #166534;
    }
    .fuselage-seat.booked {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
        position: relative;
        font-size: 11px;
        font-weight: 800;
    }
    .fuselage-seat.selected {
        background: #16a34a !important;
        border: 1.5px solid #15803d !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.4);
    }

    /* Bottom Action Bar */
    .akbar-bottom-bar {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
        margin-top: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-skip-addons {
        color: #0f172a;
        text-decoration: underline;
        font-weight: 700;
        font-size: 14.5px;
        cursor: pointer;
        margin-right: 20px;
    }
    .btn-continue-red {
        background: #ef4444;
        color: #ffffff;
        font-size: 15px;
        font-weight: 800;
        border: none;
        border-radius: 8px;
        padding: 12px 36px;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(239,68,68,0.3);
        transition: all 0.2s ease;
    }
    .btn-continue-red:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(220,38,38,0.35);
    }
</style>

<div class="akbar-addons-wrapper">
    <div class="akbar-addons-container">

        <!-- Top Navigation -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 8px;">
            <a href="<?php echo site_url('franchise/flight_review'); ?>" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Back to Flight details
            </a>
        </div>

        <!-- Top Flight Summary Bar (Screenshot 1 Exact) -->
        <div class="akbar-flight-summary-bar">
            <div class="flight-pill-grid">
                
                <!-- Onward Flight Pill -->
                <div class="flight-pill-item <?php echo $is_roundtrip ? 'with-border-right' : ''; ?>">
                    <div>
                        <div class="flight-route-title">
                            <span><?php echo htmlspecialchars($originCity); ?></span>
                            <i class="fa-solid fa-arrow-right" style="font-size: 14px; color: #0284c7;"></i>
                            <span><?php echo htmlspecialchars($destCity); ?></span>
                            <i class="fa-solid fa-plane" style="font-size: 13px; color: #0284c7; margin-left: 2px;"></i>
                            <i class="fa-solid fa-chevron-down flight-chevron-icon" onclick="toggleDetailsBox();"></i>
                        </div>
                        <div class="flight-sub-info">
                            <span><?php echo $stops > 0 ? $stops . ' Stop' : 'Non Stop'; ?></span>
                            <span style="color: #cbd5e1;">|</span>
                            <span>Economy</span>
                        </div>
                        <div class="flight-sub-info" style="margin-top: 3px;">
                            <span><?php echo date('D, d M y', strtotime($departure_date)); ?></span>
                            <span style="color: #cbd5e1;">|</span>
                            <span>Duration <?php echo htmlspecialchars($duration); ?></span>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span class="flight-badge-pill onwards">Onwards</span>
                    </div>
                </div>

                <!-- Return Flight Pill (If Round-Trip) -->
                <?php if ($is_roundtrip): ?>
                <div class="flight-pill-item">
                    <div>
                        <div class="flight-route-title">
                            <span><?php echo htmlspecialchars($retOriginCity); ?></span>
                            <i class="fa-solid fa-arrow-right" style="font-size: 14px; color: #0284c7;"></i>
                            <span><?php echo htmlspecialchars($retDestCity); ?></span>
                            <i class="fa-solid fa-plane" style="font-size: 13px; color: #0284c7; margin-left: 2px;"></i>
                            <i class="fa-solid fa-chevron-down flight-chevron-icon" onclick="toggleDetailsBox();"></i>
                        </div>
                        <div class="flight-sub-info">
                            <span><?php echo $return_stops > 0 ? $return_stops . ' Stop' : 'Non Stop'; ?></span>
                            <span style="color: #cbd5e1;">|</span>
                            <span>Economy</span>
                        </div>
                        <div class="flight-sub-info" style="margin-top: 3px;">
                            <span><?php echo date('D, d M y', strtotime($return_departure_date)); ?></span>
                            <span style="color: #cbd5e1;">|</span>
                            <span>Duration <?php echo htmlspecialchars($return_duration); ?></span>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span class="flight-badge-pill return">Return</span>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- View all details collapsible strip -->
            <div class="view-all-details-strip" onclick="toggleDetailsBox();">
                <i id="detailsChevron" class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                <span>View all details</span>
            </div>

            <div id="flightDetailsExpandBox" style="display: none; padding: 18px 24px; background: #f8fafc; border-top: 1px solid #edf2f7; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <strong><?php echo htmlspecialchars($airline_name); ?> (<?php echo htmlspecialchars($flight_number); ?>)</strong>
                        <div style="color: #64748b; margin-top: 4px;"><?php echo htmlspecialchars($origin); ?> &rarr; <?php echo htmlspecialchars($destination); ?> &bull; Dep: <?php echo htmlspecialchars($departure_time); ?> &bull; Arr: <?php echo htmlspecialchars($arrival_time); ?></div>
                    </div>
                    <?php if ($is_roundtrip): ?>
                    <div>
                        <strong><?php echo htmlspecialchars($return_airline_name); ?> (<?php echo htmlspecialchars($return_flight_number); ?>)</strong>
                        <div style="color: #64748b; margin-top: 4px;"><?php echo htmlspecialchars($return_origin); ?> &rarr; <?php echo htmlspecialchars($return_destination); ?> &bull; Dep: <?php echo htmlspecialchars($return_departure_time); ?> &bull; Arr: <?php echo htmlspecialchars($return_arrival_time); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Prominent Flight Sub-Banner (Screenshot 1 Exact) -->
        <div class="flight-sub-banner">
            <div class="flight-sub-banner-col" id="subBannerOnward">
                <div class="flight-sub-banner-title">
                    <?php echo htmlspecialchars($airline_name); ?> (<?php echo htmlspecialchars($flight_number); ?>)
                </div>
                <div class="flight-sub-banner-desc">
                    <?php echo htmlspecialchars($origin); ?> &rarr; <?php echo htmlspecialchars($destination); ?> &bull; Dep: <?php echo htmlspecialchars($departure_time); ?> &bull; Arr: <?php echo htmlspecialchars($arrival_time); ?>
                </div>
            </div>

            <?php if ($is_roundtrip): ?>
            <div class="flight-sub-banner-col" id="subBannerReturn" style="text-align: right;">
                <div class="flight-sub-banner-title">
                    <?php echo htmlspecialchars($return_airline_name); ?> (<?php echo htmlspecialchars($return_flight_number); ?>)
                </div>
                <div class="flight-sub-banner-desc">
                    <?php echo htmlspecialchars($return_origin); ?> &rarr; <?php echo htmlspecialchars($return_destination); ?> &bull; Dep: <?php echo htmlspecialchars($return_departure_time); ?> &bull; Arr: <?php echo htmlspecialchars($return_arrival_time); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Main 2-Column Grid -->
        <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px;">

            <!-- Left Column: Addon Services (Screenshots 1 & 2) -->
            <div>
                <div class="akbar-addon-card">
                    <h2 class="akbar-addon-title">Addon Services</h2>

                    <!-- 3 Primary Tabs (Meals, Baggage, Seat Selection) -->
                    <div class="akbar-tab-row">
                        <!-- Meals Tab (Screenshot 1) -->
                        <button type="button" class="akbar-tab-btn active" id="tabBtn_meals" onclick="switchAddonTab('meals', this)">
                            <span style="font-size: 18px;">🥪</span>
                            <span>Meals</span>
                        </button>

                        <!-- Baggage Tab (Screenshot 1) -->
                        <button type="button" class="akbar-tab-btn" id="tabBtn_baggage" onclick="switchAddonTab('baggage', this)">
                            <span style="font-size: 18px;">🧳</span>
                            <span>Baggage</span>
                        </button>

                        <!-- Seat Selection Tab (Screenshot 3 Matching Akbar) -->
                        <button type="button" class="akbar-tab-btn" id="tabBtn_seats" onclick="switchAddonTab('seats', this)">
                            <div class="tab-seat-default" id="tabSeatsDefaultContent" style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 18px;">💺</span>
                                <span>Seat Selection</span>
                            </div>
                            <div class="tab-seat-selected" id="tabSeatsSelectedContent" style="display: none; align-items: center; justify-content: space-between; gap: 12px; width: 100%;">
                                <div style="text-align: left;">
                                    <div id="tabSeatsBadgeText" style="font-size: 13.5px; font-weight: 800; color: inherit;">0 Seat(s) added - ₹ 0</div>
                                    <div style="font-size: 12px; font-weight: 700; color: #0284c7; text-decoration: underline;">Edit</div>
                                </div>
                                <span style="width: 20px; height: 20px; border-radius: 50%; background: #22c55e; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;"><i class="fa-solid fa-check"></i></span>
                            </div>
                        </button>
                    </div>

                    <!-- Sector & Passenger Selectors (Screenshots 1, 2, 3) -->
                    <div class="sector-selector-strip" style="border-top: 1px dashed #cbd5e1; padding-top: 16px; margin-bottom: 12px;">
                        <div class="sector-btn-group" style="display: flex; gap: 12px; margin-bottom: 0; flex-wrap: wrap;">
                            <div class="sector-btn active" id="sectorBtn_onward" onclick="switchSector('onward', this)">
                                <span><?php echo htmlspecialchars($origin); ?> <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i> <?php echo htmlspecialchars($destination); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo date('D, d M y', strtotime($departure_date)); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo $stops > 0 ? $stops . ' Stop' : 'Non Stop'; ?></span>
                            </div>

                            <?php if ($is_roundtrip): ?>
                            <div class="sector-btn inactive" id="sectorBtn_return" onclick="switchSector('return', this)">
                                <span><?php echo htmlspecialchars($return_origin); ?> <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i> <?php echo htmlspecialchars($return_destination); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo date('D, d M y', strtotime($return_departure_date)); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo $return_stops > 0 ? $return_stops . ' Stop' : 'Non Stop'; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Passenger Strip Selector (Screenshot 3 Exact Match) -->
                    <div class="passenger-strip-wrapper">
                        <div class="passenger-strip-container">
                            <button type="button" class="pax-scroll-arrow-btn" onclick="scrollPaxStrip(-1)" title="Previous Passenger">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <div class="passenger-scroll-row" id="paxScrollRow">
                                <?php foreach ($allPaxList as $pIdx => $pax): ?>
                                <div class="passenger-selector-pill <?php echo ($pIdx === 0) ? 'active' : 'inactive'; ?>" id="paxPill_<?php echo $pIdx; ?>" onclick="selectActivePassenger(<?php echo $pIdx; ?>)">
                                    <i class="fa-solid fa-user pax-user-icon"></i>
                                    <span class="pax-name-label"><?php echo htmlspecialchars($pax['full_display']); ?></span>
                                    <span class="pax-addon-badge" id="paxBadge_<?php echo $pIdx; ?>" style="display: none;"></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="pax-scroll-arrow-btn" onclick="scrollPaxStrip(1)" title="Next Passenger">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Active Passenger Notification Banner -->
                    <div style="margin-bottom: 16px; font-size: 13.5px; color: #475569; font-weight: 600; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            Selecting add-ons for: <strong id="activePaxNameBanner" style="color: #0284c7; font-weight: 800;"><?php echo htmlspecialchars($firstPaxName); ?></strong>
                            <span id="activePaxTypeBanner" style="font-size: 12px; color: #64748b; background: #e2e8f0; padding: 2px 8px; border-radius: 12px; margin-left: 6px;"><?php echo htmlspecialchars($allPaxList[0]['type'] ?? 'Adult'); ?> 1</span>
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                            <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i> Select each traveller above to configure add-ons separately
                        </div>
                    </div>

                    <!-- TAB CONTENT 1: MEALS (Screenshot 1 Exact Match) -->
                    <div id="tabContent_meals" class="addon-tab-content" style="display: block;">
                        <div class="addon-items-grid" id="mealsGridContainer">
                            <!-- Populated dynamically based on active sector airline from Benzy APIs -->
                        </div>
                    </div>

                    <!-- TAB CONTENT 2: BAGGAGE (Screenshot 3 Exact Match) -->
                    <div id="tabContent_baggage" class="addon-tab-content" style="display: none;">
                        <div class="addon-items-grid" id="baggageGridContainer">
                            <!-- Populated dynamically based on active sector airline from Benzy APIs -->
                        </div>
                    </div>

                    <!-- TAB CONTENT 3: SEAT SELECTION (Screenshot 2 & 3 Exact Match) -->
                    <div id="tabContent_seats" class="addon-tab-content" style="display: none;">
                        
                        <div style="margin-top: 14px; margin-bottom: 8px;">
                            <h3 id="seatSectorTitle" style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">
                                <?php echo htmlspecialchars($originCity); ?> &rarr; <?php echo htmlspecialchars($destCity); ?>
                            </h3>
                            <div id="seatSectorDate" style="font-size: 13px; color: #475569; font-weight: 600; margin-top: 3px;">
                                <?php echo date('l, d M y', strtotime($departure_date)); ?>
                            </div>
                        </div>

                        <div class="seat-cabin-container">
                            
                            <!-- Left: Seat Legend (Screenshot 2 & 3) -->
                            <div class="seat-legend-box">
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #94a3b8; border: 1px solid #64748b;"></span>
                                    <span>Blocked/Available at Airport Check-in/Occupied</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="border: 1.5px solid #cbd5e1; background: #ffffff; color: #94a3b8; font-weight: 800;">&#x2573;</span>
                                    <span>Already booked</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #16a34a; color: #ffffff;"><i class="fa-solid fa-check"></i></span>
                                    <span>Selected Seat</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #bbf7d0; border: 1px solid #86efac;"></span>
                                    <span>Free</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #bae6fd; border: 1px solid #7dd3fc;"></span>
                                    <span>0 - 500</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #fed7aa; border: 1px solid #fdba74;"></span>
                                    <span>501 - 1000</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #fb923c; border: 1px solid #ea580c;"></span>
                                    <span>1001 & above</span>
                                </div>

                                <!-- Dynamic List of Assigned Seats per Passenger (Screenshot 3) -->
                                <div id="paxSeatAssignmentsContainer" style="margin-top: 14px; border-top: 1px dashed #cbd5e1; padding-top: 12px;">
                                    <div id="paxSeatAssignmentsList" style="display: flex; flex-direction: column; gap: 6px;"></div>
                                </div>
                            </div>

                            <!-- Center: Airplane Fuselage (Screenshot 2 Exact) -->
                            <div class="airplane-fuselage">
                                
                                <span class="exit-marker-left">Exit</span>
                                <span class="exit-marker-right">Exit</span>

                                <!-- Front Nose -->
                                <div class="plane-nose-front">
                                    <div class="plane-nose-circle">
                                        <i class="fa-solid fa-chevron-up"></i>
                                    </div>
                                    <div class="plane-front-text">Front</div>
                                </div>

                                <!-- Column Headers A B C | D E F -->
                                <div class="seat-grid-columns">
                                    <div class="plane-seat-num"></div>
                                    <div class="seat-col-header">A</div>
                                    <div class="seat-col-header">B</div>
                                    <div class="seat-col-header">C</div>
                                    <div class="seat-aisle-header"></div>
                                    <div class="seat-col-header">D</div>
                                    <div class="seat-col-header">E</div>
                                    <div class="seat-col-header">F</div>
                                    <div class="plane-seat-num"></div>
                                </div>

                                <!-- Scrollable Container for All 30 Rows from Benzy API -->
                                <div class="fuselage-scroll-area" id="seatRowsContainer">
                                    <!-- Populated dynamically with live booking status and prices -->
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Bar (Screenshots 1 & 2) -->
                    <div class="akbar-bottom-bar">
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #64748b;">Total Amount</div>
                            <div style="font-size: 26px; font-weight: 900; color: #0f172a;">₹ <span id="bottomAddonTotal">0</span></div>
                        </div>

                        <div style="display: flex; align-items: center;">
                            <a href="javascript:void(0)" onclick="skipAddonsAndProceed();" class="btn-skip-addons">Skip Addons</a>
                            <button type="button" onclick="continueToPayment();" class="btn-continue-red">Continue</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Sticky Fare Details & Promo Code Sidebar (Screenshots 1 & 2) -->
            <div>
                <div style="position: sticky; top: 90px; display: flex; flex-direction: column; gap: 18px;">
                    
                    <!-- 1. Fare Details Card (Screenshots 1 & 2) -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            <strong style="font-size: 15px; color: #0f172a; font-weight: 700;">Fare Details</strong>
                            <span style="font-size: 13px; color: #0284c7; font-weight: 600;"><?php echo $total_passengers; ?> Traveller<?php echo $total_passengers > 1 ? 's' : ''; ?></span>
                        </div>

                        <!-- Base Fare with Subrow Toggle (+ / -) -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                                <span style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1;">+</span> Base Fare
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryBaseFare"><?php echo number_format($base_fare + $fare_tier_price_delta); ?></span></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Adult (<?php echo $total_passengers; ?>)</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($base_fare + $fare_tier_price_delta); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tax & Charges -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                                <span style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1;">+</span> Tax & Charges
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryTaxes"><?php echo number_format($taxes); ?></span></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Aviation Taxes & Fees</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($taxes); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance -->
                        <?php if ($insurance_amount > 0): ?>
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                                <span style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1;">+</span> Insurance
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <?php echo number_format($insurance_amount); ?></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Travel Insurance Cover</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($insurance_amount); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- SSR Dynamic Add-ons Breakdown in Sidebar -->
                        <div class="f-fare-group" id="summaryAddonsRow" style="display: none; margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13.5px; font-weight: 600; color: #0284c7; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-plus-circle"></i> Addon Services
                                </span>
                                <strong style="color: #0284c7; font-size: 13.5px;">₹ <span id="summaryAddonsAmount">0</span></strong>
                            </div>
                        </div>

                        <!-- Promo Discount Applied -->
                        <div class="f-fare-group" id="summaryDiscountRow" style="margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; font-weight: 700; color: #16a34a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-circle-check"></i> Promo Discount Applied
                                </span>
                                <strong style="color: #16a34a; font-size: 13.5px;">₹ <span id="summaryDiscountAmount"><?php echo number_format($discount_amount); ?></span></strong>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div style="border-top: 2px dashed #e2e8f0; margin: 14px 0 0 0; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                            <strong style="font-size: 15px; color: #0f172a; font-weight: 800;">Total Amount:</strong>
                            <strong style="font-size: 22px; color: #0f172a; font-weight: 900;">₹ <span id="summaryGrandTotal"><?php echo number_format($initialGrandTotal); ?></span></strong>
                        </div>
                    </div>

                    <!-- 2. Promo Code Card (Screenshots 1 & 2 Exact Match) -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <strong style="font-size: 15px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 12px;">Promo Code</strong>

                        <!-- Top Green Applied Box -->
                        <div style="background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #166534; font-weight: 800; font-size: 14px;">
                                <i class="fa-solid fa-percent"></i> <span id="appliedPromoBadge"><?php echo htmlspecialchars($promo_code); ?></span>
                            </div>
                            <div style="font-size: 11.5px; color: #15803d; margin-top: 3px; font-weight: 600;">
                                Your Promocode has been applied you've saved ₹ <span id="appliedPromoSavings"><?php echo number_format($discount_amount); ?></span>
                            </div>
                        </div>

                        <!-- Choose from the offers below -->
                        <div style="font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
                            Choose from the offers below
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            
                            <!-- Offer 1: ATHDFCEMI (Save 1000) -->
                            <div class="promo-item" onclick="applyPromoOption('ATHDFCEMI', 1000, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_ATHDFCEMI" style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">ATHDFCEMI</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save 1000</span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">Applicable on HDFC Bank Credit Card EMI,choose the promo to get the discount T&C Apply. ₹ 1000</div>
                                </div>
                            </div>

                            <!-- Offer 2: ZEROFEE (Save 500) -->
                            <div class="promo-item" onclick="applyPromoOption('ZEROFEE', 500, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_ZEROFEE" style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">ZEROFEE</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save 500</span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">#NoConvenienceFee. Choose this promo to get a discount of ₹ 500</div>
                                </div>
                            </div>

                            <!-- Offer 3: GOWEEKEND (Save 422) -->
                            <div class="promo-item" onclick="applyPromoOption('GOWEEKEND', 422, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_GOWEEKEND" style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">GOWEEKEND</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save 422</span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">#Weekendspecial - Choose this promo to enjoy a discount of ₹ 422</div>
                                </div>
                            </div>

                            <!-- Offer 4: RAINYDEAL (Save 397) -->
                            <div class="promo-item" onclick="applyPromoOption('RAINYDEAL', 397, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_RAINYDEAL" style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">RAINYDEAL</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save 397</span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">#AkbarSpecial - Choose this promo to enjoy a discount of ₹ 397</div>
                                </div>
                            </div>

                            <!-- Offer 5: ATFLY (Save 18 - Default Checked) -->
                            <div class="promo-item active-promo" onclick="applyPromoOption('ATFLY', <?php echo $discount_amount; ?>, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1.5px solid #22c55e; background: #f0fdf4; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_ATFLY" checked style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">ATFLY</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save <?php echo number_format($discount_amount); ?></span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">Your Promocode has been applied you've saved ₹ <?php echo number_format($discount_amount); ?></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 3. Trust Badges (Screenshot 2 Match) -->
                    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 14px 16px; font-size: 12px; color: #475569; display: flex; flex-direction: column; gap: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-shield-halved" style="color: #2563eb; font-size: 15px;"></i>
                            <span style="font-weight: 600;">100% Safe & Instant Booking</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-envelope-circle-check" style="color: #16a34a; font-size: 15px;"></i>
                            <span style="font-weight: 600;">Instant E-Ticket Sent to Email</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<!-- Hidden POST form to proceed to flight review & payment upon button click -->
<form id="finalPaymentForm" action="<?php echo site_url('franchise/flight_payment'); ?>" method="POST" style="display: none;">
    <?php if (!empty($post_data) && is_array($post_data)): ?>
        <?php foreach ($post_data as $k => $v): ?>
            <?php if (is_array($v)): ?>
                <?php foreach ($v as $subKey => $subVal): ?>
                    <input type="hidden" name="<?php echo htmlspecialchars($k); ?>[]" value="<?php echo htmlspecialchars($subVal); ?>">
                <?php endforeach; ?>
            <?php else: ?>
                <input type="hidden" name="<?php echo htmlspecialchars($k); ?>" value="<?php echo htmlspecialchars($v); ?>">
            <?php endIf; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Onward Sector Addon Hidden Fields -->
    <input type="hidden" name="selected_baggage_code" id="form_baggage_code" value="">
    <input type="hidden" name="selected_baggage_desc" id="form_baggage_desc" value="">
    <input type="hidden" name="selected_baggage_amount" id="form_baggage_amount" value="0">

    <input type="hidden" name="selected_meal_code" id="form_meal_code" value="">
    <input type="hidden" name="selected_meal_desc" id="form_meal_desc" value="">
    <input type="hidden" name="selected_meal_amount" id="form_meal_amount" value="0">

    <input type="hidden" name="selected_seat_code" id="form_seat_code" value="">
    <input type="hidden" name="selected_seat_amount" id="form_seat_amount" value="0">
    <input type="hidden" name="selected_seat_ssid" id="form_seat_ssid" value="0">

    <!-- Return Sector Addon Hidden Fields -->
    <input type="hidden" name="return_selected_baggage_code" id="form_ret_baggage_code" value="">
    <input type="hidden" name="return_selected_baggage_desc" id="form_ret_baggage_desc" value="">
    <input type="hidden" name="return_selected_baggage_amount" id="form_ret_baggage_amount" value="0">

    <input type="hidden" name="return_selected_meal_code" id="form_ret_meal_code" value="">
    <input type="hidden" name="return_selected_meal_desc" id="form_ret_meal_desc" value="">
    <input type="hidden" name="return_selected_meal_amount" id="form_ret_meal_amount" value="0">

    <input type="hidden" name="return_selected_seat_code" id="form_ret_seat_code" value="">
    <input type="hidden" name="return_selected_seat_amount" id="form_ret_seat_amount" value="0">
    <input type="hidden" name="return_selected_seat_ssid" id="form_ret_seat_ssid" value="0">

    <input type="hidden" name="addon_total_amount" id="form_addon_total_amount" value="0">
    <input type="hidden" name="total_amount" id="form_final_grand_total" value="<?php echo $initialGrandTotal; ?>">
    <input type="hidden" name="selected_ssr_json" id="form_selected_ssr_json" value="[]">
    <div id="dynamicPaxAddonInputs"></div>
</form>

<script>
// Live Benzy API Addons Data passed from Controller
var addonsData = <?php echo json_encode($addons_data); ?>;
var isRoundtrip = <?php echo !empty($is_roundtrip) ? 'true' : 'false'; ?>;
var currentSector = 'onward'; // 'onward' or 'return'
var passengers = <?php echo json_encode($allPaxList); ?>;
var activePaxIndex = 0; // index into passengers array

var baseFlightAmount = <?php echo (float)($base_fare + $taxes + $insurance_amount + $safety_cancellation_amount + $fare_tier_price_delta); ?>;
var appliedDiscount = <?php echo (float)$discount_amount; ?>;
var appliedPromoCode = "<?php echo htmlspecialchars($promo_code); ?>";

// Separate user addon selections per flight sector AND per passenger
var userSelections = {
    onward: passengers.map(function() { return { meal: null, baggage: null, seat: null }; }),
    return: passengers.map(function() { return { meal: null, baggage: null, seat: null }; })
};

// 3D Suitcase SVGs per weight tier matching Akbar / Benzy UI
function getSuitcaseSvg(weightTier) {
    var gradId = 'suitGrad_' + Math.random().toString(36).substr(2, 6);
    var c1 = '#38bdf8', c2 = '#0284c7', c3 = '#0369a1'; // Default Cyan
    
    var w = parseInt(weightTier) || 0;
    if (w <= 3) {
        c1 = '#38bdf8'; c2 = '#0284c7'; c3 = '#0369a1'; // Cyan (3 kg)
    } else if (w <= 5) {
        c1 = '#f87171'; c2 = '#ef4444'; c3 = '#b91c1c'; // Red (5 kg)
    } else if (w <= 10) {
        c1 = '#fb923c'; c2 = '#f97316'; c3 = '#c2410c'; // Orange (10 kg)
    } else if (w <= 15) {
        c1 = '#f472b6'; c2 = '#ec4899'; c3 = '#be185d'; // Pink (15 kg)
    } else if (w <= 20) {
        c1 = '#c084fc'; c2 = '#a855f7'; c3 = '#7e22ce'; // Purple (20 kg)
    } else {
        c1 = '#34d399'; c2 = '#10b981'; c3 = '#047857'; // Emerald (>20 kg)
    }

    return '<svg width="96" height="114" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">' +
        '<defs>' +
            '<linearGradient id="' + gradId + '" x1="0" y1="20" x2="80" y2="105" gradientUnits="userSpaceOnUse">' +
                '<stop offset="0%" stop-color="' + c1 + '"/>' +
                '<stop offset="50%" stop-color="' + c2 + '"/>' +
                '<stop offset="100%" stop-color="' + c3 + '"/>' +
            '</linearGradient>' +
        '</defs>' +
        '<rect x="36" y="2" width="28" height="5" rx="2.5" fill="#475569"/>' +
        '<rect x="41" y="7" width="3" height="18" fill="#94a3b8"/>' +
        '<rect x="56" y="7" width="3" height="18" fill="#94a3b8"/>' +
        '<rect x="18" y="24" width="64" height="82" rx="12" fill="url(#' + gradId + ')"/>' +
        '<line x1="32" y1="36" x2="32" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>' +
        '<line x1="44" y1="36" x2="44" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>' +
        '<line x1="56" y1="36" x2="56" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>' +
        '<line x1="68" y1="36" x2="68" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>' +
        '<rect x="40" y="21" width="20" height="5" rx="2" fill="#1e293b"/>' +
        '<circle cx="28" cy="110" r="4.5" fill="#1e293b"/>' +
        '<circle cx="28" cy="110" r="2" fill="#94a3b8"/>' +
        '<circle cx="72" cy="110" r="4.5" fill="#1e293b"/>' +
        '<circle cx="72" cy="110" r="2" fill="#94a3b8"/>' +
    '</svg>';
}

// Select active passenger from horizontal strip (Screenshot 3)
function selectActivePassenger(idx) {
    if (idx < 0 || idx >= passengers.length) return;
    activePaxIndex = idx;

    for (var i = 0; i < passengers.length; i++) {
        var pill = document.getElementById('paxPill_' + i);
        if (pill) {
            if (i === idx) {
                pill.classList.remove('inactive');
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
                pill.classList.add('inactive');
            }
        }
    }

    var activePill = document.getElementById('paxPill_' + idx);
    if (activePill) {
        activePill.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
    }

    var nameBanner = document.getElementById('activePaxNameBanner');
    var typeBanner = document.getElementById('activePaxTypeBanner');
    if (nameBanner && passengers[idx]) nameBanner.textContent = passengers[idx].full_display;
    if (typeBanner && passengers[idx]) typeBanner.textContent = (passengers[idx].type || 'Adult') + ' ' + (idx + 1);

    renderMeals(currentSector);
    renderBaggage(currentSector);
    renderSeatMap(currentSector);
}

function scrollPaxStrip(direction) {
    var row = document.getElementById('paxScrollRow');
    if (row) {
        row.scrollBy({ left: direction * 160, behavior: 'smooth' });
    }
}

// Render dynamic Meals grid from Benzy SSR
function renderMeals(sector) {
    var container = document.getElementById('mealsGridContainer');
    if (!container) return;

    var secData = addonsData && addonsData[sector] ? addonsData[sector] : null;
    var meals = secData && secData.meals ? secData.meals : [];

    if (!meals || meals.length === 0) {
        container.innerHTML = '<div style="grid-column: 1/-1; padding: 24px; text-align: center; color: #64748b; background: #f8fafc; border-radius: 8px;">No meal options available for this sector from Benzy APIs.</div>';
        return;
    }

    var currentPaxMeal = userSelections[sector][activePaxIndex] ? userSelections[sector][activePaxIndex].meal : null;
    var html = '';

    meals.forEach(function(item, idx) {
        var isSelected = (currentPaxMeal && currentPaxMeal.code === item.code);
        
        // Count selections by other passengers
        var totalCount = 0;
        passengers.forEach(function(p, pIdx) {
            if (userSelections[sector][pIdx] && userSelections[sector][pIdx].meal && userSelections[sector][pIdx].meal.code === item.code) {
                totalCount++;
            }
        });

        var isVeg = (item.category && item.category.toLowerCase().indexOf('non') === -1 && item.category.toLowerCase().indexOf('chicken') === -1 && item.category.toLowerCase().indexOf('mutton') === -1 && item.category.toLowerCase().indexOf('fish') === -1);
        
        var vegBadge = isVeg ? 
            '<span style="display: inline-flex; align-items: center; justify-content: center; width: 13px; height: 13px; border: 1.5px solid #16a34a; border-radius: 2px; padding: 1px; margin-right: 5px; vertical-align: middle;"><span style="display: block; width: 5px; height: 5px; border-radius: 50%; background: #16a34a;"></span></span>' :
            '<span style="display: inline-flex; align-items: center; justify-content: center; width: 13px; height: 13px; border: 1.5px solid #dc2626; border-radius: 2px; padding: 1px; margin-right: 5px; vertical-align: middle;"><span style="display: block; width: 0; height: 0; border-left: 3px solid transparent; border-right: 3px solid transparent; border-bottom: 5px solid #dc2626;"></span></span>';

        var imgGraphic = '';
        if (item.image && item.image.length > 5) {
            imgGraphic = '<img src="' + item.image + '" alt="' + item.name + '" style="max-height: 100px; max-width: 100px; object-fit: contain; border-radius: 8px;">';
        } else {
            imgGraphic = '<div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #fb923c, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234,88,12,0.25);">' +
                '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"></path>' +
                    '<path d="M15 2v10"></path>' +
                    '<path d="M12 2v6"></path>' +
                    '<path d="M18 10v12"></path>' +
                    '<path d="M6 2v20"></path>' +
                    '<path d="M6 2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4"></path>' +
                '</svg>' +
            '</div>';
        }

        html += '<div class="akbar-service-card meal-item-card ' + (isSelected ? 'selected' : '') + '" onclick="selectMealItem(\'' + item.code + '\', \'' + item.name.replace(/'/g, "\\'") + '\', ' + item.price + ', ' + (item.ssid || 0) + ', this)">';
        html += '<div class="card-radio-circle"></div>';
        html += '<div class="card-img-wrapper">' + imgGraphic + '</div>';
        html += '<div>';
        html += '<div style="display: flex; justify-content: space-between; align-items: center;">';
        html += '<div style="font-size: 18px; font-weight: 800; color: #0f172a;">₹ ' + item.price + '</div>';
        if (totalCount > 0) {
            html += '<span style="font-size: 10.5px; background: #e0f2fe; color: #0284c7; padding: 2px 7px; border-radius: 10px; font-weight: 800;">' + totalCount + ' added</span>';
        }
        html += '</div>';
        html += '<div style="font-size: 12.5px; color: #475569; font-weight: 600; margin-top: 4px; line-height: 1.35;">' + vegBadge + item.name + '</div>';
        html += '</div>';
        html += '</div>';
    });

    container.innerHTML = html;
}

// Render dynamic Baggage grid from Benzy SSR
function renderBaggage(sector) {
    var container = document.getElementById('baggageGridContainer');
    if (!container) return;

    var secData = addonsData && addonsData[sector] ? addonsData[sector] : null;
    var baggage = secData && secData.baggage ? secData.baggage : [];

    if (!baggage || baggage.length === 0) {
        container.innerHTML = '<div style="grid-column: 1/-1; padding: 24px; text-align: center; color: #64748b; background: #f8fafc; border-radius: 8px;">No extra baggage options available for this sector from Benzy APIs.</div>';
        return;
    }

    var currentPaxBaggage = userSelections[sector][activePaxIndex] ? userSelections[sector][activePaxIndex].baggage : null;
    var html = '';

    baggage.forEach(function(item) {
        var isSelected = (currentPaxBaggage && currentPaxBaggage.code === item.code);
        var svgGraphic = getSuitcaseSvg(item.weight);

        var totalCount = 0;
        passengers.forEach(function(p, pIdx) {
            if (userSelections[sector][pIdx] && userSelections[sector][pIdx].baggage && userSelections[sector][pIdx].baggage.code === item.code) {
                totalCount++;
            }
        });

        html += '<div class="akbar-service-card baggage-item-card ' + (isSelected ? 'selected' : '') + '" onclick="selectBaggageItem(\'' + item.code + '\', \'' + item.weight + '\', ' + item.price + ', ' + (item.ssid || 0) + ', this)">';
        html += '<div class="card-radio-circle"></div>';
        html += '<div class="card-img-wrapper">' + svgGraphic + '</div>';
        html += '<div>';
        html += '<div style="display: flex; justify-content: space-between; align-items: center;">';
        html += '<div style="font-size: 19px; font-weight: 800; color: #0f172a;">₹' + item.price + '</div>';
        if (totalCount > 0) {
            html += '<span style="font-size: 10.5px; background: #e0f2fe; color: #0284c7; padding: 2px 7px; border-radius: 10px; font-weight: 800;">' + totalCount + ' added</span>';
        }
        html += '</div>';
        html += '<div style="font-size: 12px; color: #64748b; margin-top: 2px;">Prepaid excess baggage</div>';
        html += '<div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">' + item.weight + '</div>';
        html += '</div>';
        html += '</div>';
    });

    container.innerHTML = html;
}

// Render dynamic 30-row Seat Map from Benzy SeatLayout API
function renderSeatMap(sector) {
    var container = document.getElementById('seatRowsContainer');
    if (!container) return;

    var secData = addonsData && addonsData[sector] ? addonsData[sector] : null;
    var rows = secData && secData.seat_rows ? secData.seat_rows : null;

    if (!rows || Object.keys(rows).length === 0) {
        container.innerHTML = '<div style="padding: 24px; text-align: center; color: #64748b;">Seat layout information currently unavailable for this flight sector from Benzy APIs.</div>';
        return;
    }

    var html = '';
    var colsLeft = ['A', 'B', 'C'];
    var colsRight = ['D', 'E', 'F'];

    // Render Rows 1 through 30
    for (var r = 1; r <= 30; r++) {
        var rowObj = rows[r] || {};

        html += '<div class="plane-seat-row">';
        html += '<div class="plane-seat-num">' + r + '</div>';

        // Left Column Seats (A, B, C)
        colsLeft.forEach(function(col) {
            html += renderSingleSeatHtml(rowObj[col], sector);
        });

        // Aisle Gap
        html += '<div class="seat-aisle-header"></div>';

        // Right Column Seats (D, E, F)
        colsRight.forEach(function(col) {
            html += renderSingleSeatHtml(rowObj[col], sector);
        });

        html += '<div class="plane-seat-num">' + r + '</div>';
        html += '</div>';
    }

    container.innerHTML = html;
    updateSeatAssignmentsList(sector);
}

function renderSingleSeatHtml(seat, sector) {
    if (!seat) {
        return '<div class="fuselage-seat" style="visibility: hidden;"></div>';
    }

    var seatCode = seat.seat;
    var isBooked = seat.is_booked;

    // Check if this seat is selected by ANY passenger
    var assignedPaxIdx = -1;
    for (var p = 0; p < passengers.length; p++) {
        if (userSelections[sector][p] && userSelections[sector][p].seat && userSelections[sector][p].seat.code === seatCode) {
            assignedPaxIdx = p;
            break;
        }
    }

    if (isBooked) {
        return '<div class="fuselage-seat booked" title="Seat ' + seatCode + ' (Already booked)">&#x2573;</div>';
    } else if (assignedPaxIdx !== -1) {
        var isCurrentPaxSeat = (assignedPaxIdx === activePaxIndex);
        var paxName = passengers[assignedPaxIdx] ? passengers[assignedPaxIdx].name : ('Passenger ' + (assignedPaxIdx + 1));
        var borderStyle = isCurrentPaxSeat ? 'outline: 2.5px solid #0284c7; outline-offset: 1px;' : '';
        return '<div class="fuselage-seat selected" style="' + borderStyle + '" data-seat="' + seatCode + '" data-price="' + seat.price + '" data-ssid="' + (seat.ssid || 0) + '" onclick="selectSeatItem(\'' + seatCode + '\', ' + seat.price + ', ' + (seat.ssid || 0) + ', this)" title="Seat ' + seatCode + ' - ' + paxName + ' (₹' + seat.price + ')"><i class="fa-solid fa-check" style="color: #fff; font-size: 11px;"></i></div>';
    } else {
        var tooltip = 'Seat ' + seatCode + (seat.info ? ' • ' + seat.info : '') + ' (₹' + seat.price + ')';
        return '<div class="fuselage-seat ' + seat.tier + '" data-seat="' + seatCode + '" data-price="' + seat.price + '" data-ssid="' + (seat.ssid || 0) + '" onclick="selectSeatItem(\'' + seatCode + '\', ' + seat.price + ', ' + (seat.ssid || 0) + ', this)" title="' + tooltip + '"></div>';
    }
}

// Update the left-side assigned seats list (Screenshot 3 Exact Match)
function updateSeatAssignmentsList(sector) {
    var listEl = document.getElementById('paxSeatAssignmentsList');
    if (!listEl) return;

    var html = '';
    passengers.forEach(function(pax, idx) {
        var s = userSelections[sector][idx] ? userSelections[sector][idx].seat : null;
        if (s) {
            html += '<div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px; color: #334155; margin-bottom: 6px; cursor: pointer; padding: 3px 6px; border-radius: 4px; background: ' + (idx === activePaxIndex ? '#f0f9ff; border: 1px solid #bae6fd;' : 'transparent;') + '" onclick="selectActivePassenger(' + idx + ')" title="Click to view/change seat for ' + pax.name + '">';
            html += '<span>(<span style="font-weight: 700; color: #0f172a;">' + pax.name + '</span>, ' + s.code + ')</span>';
            html += '<span style="font-weight: 800; color: #0f172a;">₹ ' + s.price.toLocaleString('en-IN') + '</span>';
            html += '</div>';
        }
    });

    if (html === '') {
        html = '<div style="font-size: 11.5px; color: #94a3b8; font-style: italic;">No seats selected yet.</div>';
    }

    listEl.innerHTML = html;
}

// Update sector title and subtitle in Seat Selection Tab
function renderSectorHeader(sector) {
    var secData = addonsData && addonsData[sector] ? addonsData[sector] : null;
    if (!secData) return;

    var titleEl = document.getElementById('seatSectorTitle');
    var dateEl = document.getElementById('seatSectorDate');

    if (titleEl) {
        titleEl.innerHTML = (secData.origin_city || secData.origin) + ' &rarr; ' + (secData.destination_city || secData.destination);
    }
    if (dateEl) {
        dateEl.textContent = secData.formatted_date || secData.departure_date;
    }
}

// Sector Switcher Handler
function switchSector(sectorType, btnEl) {
    if (!addonsData || !addonsData[sectorType]) return;

    currentSector = sectorType;

    document.querySelectorAll('.sector-btn').forEach(function(btn) {
        btn.classList.remove('active');
        btn.classList.add('inactive');
    });
    if (btnEl) {
        btnEl.classList.remove('inactive');
        btnEl.classList.add('active');
    }

    var onwardCol = document.getElementById('subBannerOnward');
    var returnCol = document.getElementById('subBannerReturn');
    if (onwardCol) {
        onwardCol.style.opacity = (sectorType === 'onward') ? '1' : '0.65';
    }
    if (returnCol) {
        returnCol.style.opacity = (sectorType === 'return') ? '1' : '0.65';
    }

    renderSectorHeader(sectorType);
    renderMeals(sectorType);
    renderBaggage(sectorType);
    renderSeatMap(sectorType);
    recalcTotal();
}

// Addon Tab Switcher (Meals / Baggage / Seat Selection)
function switchAddonTab(tabId, tabEl) {
    document.querySelectorAll('.akbar-tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    if (tabEl) tabEl.classList.add('active');

    document.querySelectorAll('.addon-tab-content').forEach(function(content) {
        content.style.display = 'none';
    });
    var target = document.getElementById('tabContent_' + tabId);
    if (target) target.style.display = 'block';
}

function selectMealItem(code, name, price, ssid, cardEl) {
    var sec = currentSector;
    var currentPaxMeal = userSelections[sec][activePaxIndex] ? userSelections[sec][activePaxIndex].meal : null;
    var isAlready = (currentPaxMeal && currentPaxMeal.code === code);

    if (isAlready) {
        userSelections[sec][activePaxIndex].meal = null;
    } else {
        userSelections[sec][activePaxIndex].meal = {
            code: code,
            name: name,
            price: parseFloat(price) || 0,
            ssid: ssid || 0
        };
    }

    renderMeals(sec);
    recalcTotal();
}

function selectBaggageItem(code, weight, price, ssid, cardEl) {
    var sec = currentSector;
    var currentPaxBaggage = userSelections[sec][activePaxIndex] ? userSelections[sec][activePaxIndex].baggage : null;
    var isAlready = (currentPaxBaggage && currentPaxBaggage.code === code);

    if (isAlready) {
        userSelections[sec][activePaxIndex].baggage = null;
    } else {
        userSelections[sec][activePaxIndex].baggage = {
            code: code,
            weight: weight,
            desc: 'Prepaid Excess Baggage - ' + weight,
            price: parseFloat(price) || 0,
            ssid: ssid || 0
        };
    }

    renderBaggage(sec);
    recalcTotal();
}

function selectSeatItem(seatCode, price, ssid, seatEl) {
    var sec = currentSector;

    // Check if this seat is already assigned to any passenger on this sector
    var assignedPaxIdx = -1;
    for (var p = 0; p < passengers.length; p++) {
        if (userSelections[sec][p] && userSelections[sec][p].seat && userSelections[sec][p].seat.code === seatCode) {
            assignedPaxIdx = p;
            break;
        }
    }

    if (assignedPaxIdx === activePaxIndex) {
        // Active passenger clicked their own seat -> deselect
        userSelections[sec][activePaxIndex].seat = null;
    } else if (assignedPaxIdx !== -1) {
        // Seat already assigned to another passenger -> reassign to active passenger
        userSelections[sec][assignedPaxIdx].seat = null;
        userSelections[sec][activePaxIndex].seat = {
            code: seatCode,
            price: parseFloat(price) || 0,
            ssid: ssid || 0
        };
    } else {
        // Seat is available -> assign to active passenger
        userSelections[sec][activePaxIndex].seat = {
            code: seatCode,
            price: parseFloat(price) || 0,
            ssid: ssid || 0
        };

        // Auto-advance to next passenger without a seat
        var nextUnseatedPax = -1;
        for (var i = 0; i < passengers.length; i++) {
            var checkIdx = (activePaxIndex + 1 + i) % passengers.length;
            if (!userSelections[sec][checkIdx].seat) {
                nextUnseatedPax = checkIdx;
                break;
            }
        }
        if (nextUnseatedPax !== -1) {
            selectActivePassenger(nextUnseatedPax);
            return;
        }
    }

    renderSeatMap(sec);
    recalcTotal();
}

// Recalculate combined totals across all passengers and flight sectors
function recalcTotal() {
    var totalAddons = 0;
    var onwardSeatsCount = 0;
    var onwardSeatsTotal = 0;

    var ssrItems = [];

    passengers.forEach(function(pax, idx) {
        var pId = idx + 1; // 1-based sequence for Benzy CreateItinerary (PDF page 84-86)
        var on = userSelections.onward[idx];
        var ret = userSelections.return[idx];

        // Onward Sector
        if (on.meal) {
            totalAddons += on.meal.price;
            ssrItems.push({
                FUID: 1,
                PaxID: pId,
                SSID: on.meal.ssid || 1,
                Type: "1",
                Code: on.meal.code,
                Description: on.meal.name,
                Charge: on.meal.price
            });
        }
        if (on.baggage) {
            totalAddons += on.baggage.price;
            ssrItems.push({
                FUID: 1,
                PaxID: pId,
                SSID: on.baggage.ssid || 1,
                Type: "2",
                Code: on.baggage.code,
                Description: on.baggage.desc,
                Charge: on.baggage.price
            });
        }
        if (on.seat) {
            totalAddons += on.seat.price;
            onwardSeatsCount++;
            onwardSeatsTotal += on.seat.price;
            ssrItems.push({
                FUID: 1,
                PaxID: pId,
                SSID: on.seat.ssid || 501,
                Type: "9",
                Code: on.seat.code,
                Description: "Seat " + on.seat.code,
                Charge: on.seat.price
            });
        }

        // Return Sector
        if (isRoundtrip && ret) {
            if (ret.meal) {
                totalAddons += ret.meal.price;
                ssrItems.push({
                    FUID: 2,
                    PaxID: pId,
                    SSID: ret.meal.ssid || 1,
                    Type: "1",
                    Code: ret.meal.code,
                    Description: ret.meal.name,
                    Charge: ret.meal.price
                });
            }
            if (ret.baggage) {
                totalAddons += ret.baggage.price;
                ssrItems.push({
                    FUID: 2,
                    PaxID: pId,
                    SSID: ret.baggage.ssid || 1,
                    Type: "2",
                    Code: ret.baggage.code,
                    Description: ret.baggage.desc,
                    Charge: ret.baggage.price
                });
            }
            if (ret.seat) {
                totalAddons += ret.seat.price;
                ssrItems.push({
                    FUID: 2,
                    PaxID: pId,
                    SSID: ret.seat.ssid || 501,
                    Type: "9",
                    Code: ret.seat.code,
                    Description: "Seat " + ret.seat.code,
                    Charge: ret.seat.price
                });
            }
        }

        // Update Passenger Strip Badges
        var badge = document.getElementById('paxBadge_' + idx);
        if (badge) {
            var paxItemCount = (on.meal ? 1 : 0) + (on.baggage ? 1 : 0) + (on.seat ? 1 : 0) +
                               (isRoundtrip && ret && ret.meal ? 1 : 0) +
                               (isRoundtrip && ret && ret.baggage ? 1 : 0) +
                               (isRoundtrip && ret && ret.seat ? 1 : 0);
            if (paxItemCount > 0) {
                badge.style.display = 'inline-block';
                badge.textContent = on.seat ? ('Seat: ' + on.seat.code) : ('✓ ' + paxItemCount);
            } else {
                badge.style.display = 'none';
            }
        }
    });

    // Update Bottom Bar & Fare Details Sidebar
    document.getElementById('bottomAddonTotal').textContent = totalAddons.toLocaleString('en-IN');

    var addonsRow = document.getElementById('summaryAddonsRow');
    if (totalAddons > 0) {
        addonsRow.style.display = 'block';
        document.getElementById('summaryAddonsAmount').textContent = totalAddons.toLocaleString('en-IN');
    } else {
        addonsRow.style.display = 'none';
    }

    var grandTotal = Math.max(0, (baseFlightAmount + totalAddons) - appliedDiscount);
    document.getElementById('summaryGrandTotal').textContent = grandTotal.toLocaleString('en-IN');
    document.getElementById('form_final_grand_total').value = grandTotal;
    if (document.getElementById('form_addon_total_amount')) {
        document.getElementById('form_addon_total_amount').value = totalAddons;
    }

    // Update Seat Selection Tab Badge (Screenshot 3 Matching Akbar)
    var curSectorSeatsCount = 0;
    var curSectorSeatsTotal = 0;
    passengers.forEach(function(p, i) {
        if (userSelections[currentSector][i].seat) {
            curSectorSeatsCount++;
            curSectorSeatsTotal += userSelections[currentSector][i].seat.price;
        }
    });

    var defTab = document.getElementById('tabSeatsDefaultContent');
    var selTab = document.getElementById('tabSeatsSelectedContent');
    if (curSectorSeatsCount > 0) {
        if (defTab) defTab.style.display = 'none';
        if (selTab) {
            selTab.style.display = 'flex';
            document.getElementById('tabSeatsBadgeText').textContent = curSectorSeatsCount + ' Seat(s) added - ₹ ' + curSectorSeatsTotal.toLocaleString('en-IN');
        }
    } else {
        if (defTab) defTab.style.display = 'flex';
        if (selTab) selTab.style.display = 'none';
    }

    // Populate Benzy API JSON format
    var jsonInput = document.getElementById('form_selected_ssr_json');
    if (jsonInput) {
        jsonInput.value = JSON.stringify(ssrItems);
    }

    // Populate Dynamic Per-Passenger Hidden Inputs for Payment Bridge
    var dynContainer = document.getElementById('dynamicPaxAddonInputs');
    if (dynContainer) {
        var dynHtml = '';
        passengers.forEach(function(pax, idx) {
            var on = userSelections.onward[idx];
            var ret = userSelections.return[idx];

            dynHtml += '<input type="hidden" name="passenger_seat[' + idx + ']" value="' + (on.seat ? on.seat.code : '') + '">';
            dynHtml += '<input type="hidden" name="passenger_seat_price[' + idx + ']" value="' + (on.seat ? on.seat.price : 0) + '">';
            dynHtml += '<input type="hidden" name="passenger_seat_ssid[' + idx + ']" value="' + (on.seat ? on.seat.ssid : 0) + '">';

            dynHtml += '<input type="hidden" name="passenger_meal[' + idx + ']" value="' + (on.meal ? on.meal.code : '') + '">';
            dynHtml += '<input type="hidden" name="passenger_meal_name[' + idx + ']" value="' + (on.meal ? on.meal.name : '') + '">';
            dynHtml += '<input type="hidden" name="passenger_meal_price[' + idx + ']" value="' + (on.meal ? on.meal.price : 0) + '">';
            dynHtml += '<input type="hidden" name="passenger_meal_ssid[' + idx + ']" value="' + (on.meal ? on.meal.ssid : 0) + '">';

            dynHtml += '<input type="hidden" name="passenger_baggage[' + idx + ']" value="' + (on.baggage ? on.baggage.code : '') + '">';
            dynHtml += '<input type="hidden" name="passenger_baggage_desc[' + idx + ']" value="' + (on.baggage ? on.baggage.desc : '') + '">';
            dynHtml += '<input type="hidden" name="passenger_baggage_price[' + idx + ']" value="' + (on.baggage ? on.baggage.price : 0) + '">';
            dynHtml += '<input type="hidden" name="passenger_baggage_ssid[' + idx + ']" value="' + (on.baggage ? on.baggage.ssid : 0) + '">';

            if (isRoundtrip && ret) {
                dynHtml += '<input type="hidden" name="return_passenger_seat[' + idx + ']" value="' + (ret.seat ? ret.seat.code : '') + '">';
                dynHtml += '<input type="hidden" name="return_passenger_seat_price[' + idx + ']" value="' + (ret.seat ? ret.seat.price : 0) + '">';
                dynHtml += '<input type="hidden" name="return_passenger_seat_ssid[' + idx + ']" value="' + (ret.seat ? ret.seat.ssid : 0) + '">';

                dynHtml += '<input type="hidden" name="return_passenger_meal[' + idx + ']" value="' + (ret.meal ? ret.meal.code : '') + '">';
                dynHtml += '<input type="hidden" name="return_passenger_meal_name[' + idx + ']" value="' + (ret.meal ? ret.meal.name : '') + '">';
                dynHtml += '<input type="hidden" name="return_passenger_meal_price[' + idx + ']" value="' + (ret.meal ? ret.meal.price : 0) + '">';
                dynHtml += '<input type="hidden" name="return_passenger_meal_ssid[' + idx + ']" value="' + (ret.meal ? ret.meal.ssid : 0) + '">';

                dynHtml += '<input type="hidden" name="return_passenger_baggage[' + idx + ']" value="' + (ret.baggage ? ret.baggage.code : '') + '">';
                dynHtml += '<input type="hidden" name="return_passenger_baggage_desc[' + idx + ']" value="' + (ret.baggage ? ret.baggage.desc : '') + '">';
                dynHtml += '<input type="hidden" name="return_passenger_baggage_price[' + idx + ']" value="' + (ret.baggage ? ret.baggage.price : 0) + '">';
                dynHtml += '<input type="hidden" name="return_passenger_baggage_ssid[' + idx + ']" value="' + (ret.baggage ? ret.baggage.ssid : 0) + '">';
            }
        });
        dynContainer.innerHTML = dynHtml;
    }

    // Populate Legacy Single Inputs (using Pax 1 or aggregate)
    var p0On = userSelections.onward[0] || {};
    document.getElementById('form_meal_code').value = p0On.meal ? p0On.meal.code : '';
    document.getElementById('form_meal_desc').value = p0On.meal ? p0On.meal.name : '';
    document.getElementById('form_meal_amount').value = p0On.meal ? p0On.meal.price : 0;

    document.getElementById('form_baggage_code').value = p0On.baggage ? p0On.baggage.code : '';
    document.getElementById('form_baggage_desc').value = p0On.baggage ? p0On.baggage.desc : '';
    document.getElementById('form_baggage_amount').value = p0On.baggage ? p0On.baggage.price : 0;

    document.getElementById('form_seat_code').value = p0On.seat ? p0On.seat.code : '';
    document.getElementById('form_seat_amount').value = p0On.seat ? p0On.seat.price : 0;
    document.getElementById('form_seat_ssid').value = p0On.seat ? (p0On.seat.ssid || 0) : 0;

    if (document.getElementById('form_ret_meal_code')) {
        var p0Ret = userSelections.return[0] || {};
        document.getElementById('form_ret_meal_code').value = p0Ret.meal ? p0Ret.meal.code : '';
        document.getElementById('form_ret_meal_desc').value = p0Ret.meal ? p0Ret.meal.name : '';
        document.getElementById('form_ret_meal_amount').value = p0Ret.meal ? p0Ret.meal.price : 0;

        document.getElementById('form_ret_baggage_code').value = p0Ret.baggage ? p0Ret.baggage.code : '';
        document.getElementById('form_ret_baggage_desc').value = p0Ret.baggage ? p0Ret.baggage.desc : '';
        document.getElementById('form_ret_baggage_amount').value = p0Ret.baggage ? p0Ret.baggage.price : 0;

        document.getElementById('form_ret_seat_code').value = p0Ret.seat ? p0Ret.seat.code : '';
        document.getElementById('form_ret_seat_amount').value = p0Ret.seat ? p0Ret.seat.price : 0;
        document.getElementById('form_ret_seat_ssid').value = p0Ret.seat ? (p0Ret.seat.ssid || 0) : 0;
    }

    updateSeatAssignmentsList(currentSector);
}

function applyPromoOption(code, discount, el) {
    document.querySelectorAll('.promo-item').forEach(function(p) {
        p.style.borderColor = '#e2e8f0';
        p.style.background = '#ffffff';
        var radio = p.querySelector('input[type="radio"]');
        if (radio) radio.checked = false;
    });

    el.style.borderColor = '#22c55e';
    el.style.background = '#f0fdf4';
    var myRadio = el.querySelector('input[type="radio"]');
    if (myRadio) myRadio.checked = true;

    appliedDiscount = parseFloat(discount);
    appliedPromoCode = code;

    document.getElementById('appliedPromoBadge').textContent = code;
    document.getElementById('appliedPromoSavings').textContent = appliedDiscount.toLocaleString('en-IN');
    document.getElementById('summaryDiscountAmount').textContent = appliedDiscount.toLocaleString('en-IN');

    recalcTotal();
}

function toggleFareBreakdown(triggerEl) {
    var group = triggerEl.closest('.f-fare-group');
    if (!group) return;
    var subitems = group.querySelector('.f-fare-subitems');
    var circleIcon = group.querySelector('.f-fare-toggle-circle');
    if (!subitems) return;

    var isHidden = (subitems.style.display === 'none' || window.getComputedStyle(subitems).display === 'none');
    if (isHidden) {
        subitems.style.display = 'flex';
        if (circleIcon) {
            circleIcon.textContent = '−';
            circleIcon.style.background = '#0284c7';
            circleIcon.style.color = '#ffffff';
            circleIcon.style.borderColor = '#0284c7';
        }
    } else {
        subitems.style.display = 'none';
        if (circleIcon) {
            circleIcon.textContent = '+';
            circleIcon.style.background = 'transparent';
            circleIcon.style.color = '#0284c7';
            circleIcon.style.borderColor = '#0284c7';
        }
    }
}

function toggleDetailsBox() {
    var box = document.getElementById('flightDetailsExpandBox');
    var chevron = document.getElementById('detailsChevron');
    if (!box) return;
    if (box.style.display === 'none') {
        box.style.display = 'block';
        if (chevron) chevron.className = 'fa-solid fa-chevron-up';
    } else {
        box.style.display = 'none';
        if (chevron) chevron.className = 'fa-solid fa-chevron-down';
    }
}

function skipAddonsAndProceed() {
    userSelections.onward = { meal: null, baggage: null, seat: null };
    userSelections.return = { meal: null, baggage: null, seat: null };
    recalcTotal();
    proceedToPaymentReview();
}

function continueToPayment() {
    proceedToPaymentReview();
}

function proceedToPaymentReview() {
    var form = document.getElementById('finalPaymentForm');
    if (form) {
        form.action = "<?php echo site_url('franchise/flight_payment'); ?>";
        form.submit();
    }
}


// Initial Render on Page Load
document.addEventListener('DOMContentLoaded', function() {
    renderSectorHeader(currentSector);
    renderMeals(currentSector);
    renderBaggage(currentSector);
    renderSeatMap(currentSector);
    recalcTotal();
});
</script>
