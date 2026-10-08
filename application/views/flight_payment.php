<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Voyogo Flight Payment & Final Review Page
 * Matches Akbar Travels Flight Details Modal & Review Page Architecture
 * Supports 1+ Stops Connecting Flight Legs with Layover Notification Banner (Screenshots 2 & 3)
 */

$flight        = $flight ?? array();
$return_flight = $return_flight ?? array();
$search_query  = $search_query ?? array();
$is_roundtrip  = !empty($is_roundtrip) || !empty($return_flight);
$post_data     = $post_data ?? array();
$passengers    = $passengers ?? array();
$total_pax     = max(1, count($passengers));

// Airport Database for rich city, airport names, and terminals
$airportDb = array(
    'DEL' => array('city' => 'New Delhi', 'name' => 'Indira Gandhi International Airport, Delhi', 'country' => 'India', 'terminal' => 'Terminal 2'),
    'BOM' => array('city' => 'Mumbai', 'name' => 'Chhatrapati Shivaji Maharaj International Airport, Mumbai', 'country' => 'India', 'terminal' => 'Terminal 2'),
    'HYD' => array('city' => 'Hyderabad', 'name' => 'Rajiv Gandhi International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'BLR' => array('city' => 'Bengaluru', 'name' => 'Kempegowda International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'CCU' => array('city' => 'Kolkata', 'name' => 'Netaji Subhash Chandra Bose International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'MAA' => array('city' => 'Chennai', 'name' => 'Chennai International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'GOI' => array('city' => 'Goa', 'name' => 'Dabolim Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'GOX' => array('city' => 'Goa MOPA', 'name' => 'Manohar International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'AMD' => array('city' => 'Ahmedabad', 'name' => 'Sardar Vallabhbhai Patel International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'PNQ' => array('city' => 'Pune', 'name' => 'Pune International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'JAI' => array('city' => 'Jaipur', 'name' => 'Jaipur International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
    'LKO' => array('city' => 'Lucknow', 'name' => 'Chaudhary Charan Singh International Airport', 'country' => 'India', 'terminal' => 'Terminal 2'),
    'COK' => array('city' => 'Kochi', 'name' => 'Cochin International Airport', 'country' => 'India', 'terminal' => 'Terminal 3'),
    'DXB' => array('city' => 'Dubai', 'name' => 'Dubai International Airport', 'country' => 'United Arab Emirates', 'terminal' => 'Terminal 3'),
    'SIN' => array('city' => 'Singapore', 'name' => 'Singapore Changi Airport', 'country' => 'Singapore', 'terminal' => 'Terminal 3'),
    'BKK' => array('city' => 'Bangkok', 'name' => 'Suvarnabhumi Airport', 'country' => 'Thailand', 'terminal' => 'Terminal 1'),
    'LHR' => array('city' => 'London', 'name' => 'Heathrow Airport', 'country' => 'United Kingdom', 'terminal' => 'Terminal 2'),
    'DMM' => array('city' => 'Dammam', 'name' => 'King Fahd International Airport', 'country' => 'Saudi Arabia', 'terminal' => 'Terminal 1'),
    'RUH' => array('city' => 'Riyadh', 'name' => 'King Khalid International Airport', 'country' => 'Saudi Arabia', 'terminal' => 'Terminal 2'),
    'JED' => array('city' => 'Jeddah', 'name' => 'King Abdulaziz International Airport', 'country' => 'Saudi Arabia', 'terminal' => 'North Terminal'),
    'NMI' => array('city' => 'Navi Mumbai', 'name' => 'Navi Mumbai International Airport', 'country' => 'India', 'terminal' => 'Terminal 1')
);

if (!function_exists('parseTimeToMins')) {
    function parseTimeToMins($timeStr) {
        if (preg_match('/(\d{1,2}):(\d{2})/', (string)$timeStr, $m)) {
            return (int)$m[1] * 60 + (int)$m[2];
        }
        return 0;
    }
}
if (!function_exists('minsToTimeStr')) {
    function minsToTimeStr($mins) {
        $mins = ($mins % 1440 + 1440) % 1440;
        $h = floor($mins / 60);
        $m = $mins % 60;
        return sprintf('%02d:%02d', $h, $m);
    }
}
if (!function_exists('formatMinsDuration')) {
    function formatMinsDuration($mins) {
        $h = floor($mins / 60);
        $m = $mins % 60;
        if ($h > 0 && $m > 0) {
            return sprintf('%02d Hr. %02d Min.', $h, $m);
        } elseif ($h > 0) {
            return sprintf('%02d Hr.', $h);
        } else {
            return sprintf('%02d Min.', $m);
        }
    }
}
if (!function_exists('buildConnectingFlightSegments')) {
    function buildConnectingFlightSegments($flt, $airportDb) {
        if (!empty($flt['segments']) && is_array($flt['segments']) && count($flt['segments']) >= 2) {
            $seg1 = $flt['segments'][0];
            $seg2 = $flt['segments'][1];
            
            $fromCode = strtoupper($seg1['from_code'] ?? ($flt['from_code'] ?? 'DEL'));
            $viaCode  = strtoupper($seg1['to_code'] ?? ($seg2['from_code'] ?? 'HYD'));
            $toCode   = strtoupper($seg2['to_code'] ?? ($flt['to_code'] ?? 'BOM'));
            
            $fromInfo = $airportDb[$fromCode] ?? array('city' => $seg1['from_city'] ?? $fromCode, 'name' => $seg1['from_airport'] ?? ($fromCode . ' Airport'), 'country' => 'India', 'terminal' => $seg1['from_terminal'] ?? 'Terminal 2');
            $viaInfo  = $airportDb[$viaCode] ?? array('city' => $seg1['to_city'] ?? ($seg2['from_city'] ?? $viaCode), 'name' => $seg1['to_airport'] ?? ($viaCode . ' Airport'), 'country' => 'India', 'terminal' => $seg2['from_terminal'] ?? 'Terminal 1');
            $toInfo   = $airportDb[$toCode] ?? array('city' => $seg2['to_city'] ?? $toCode, 'name' => $seg2['to_airport'] ?? ($toCode . ' Airport'), 'country' => 'India', 'terminal' => $seg2['to_terminal'] ?? 'Terminal 2');
            
            $dep1 = $seg1['departure_time'] ?? ($flt['departure_time'] ?? '07:15');
            $arr1 = $seg1['arrival_time'] ?? '09:20';
            $dep2 = $seg2['departure_time'] ?? '10:59';
            $arr2 = $seg2['arrival_time'] ?? ($flt['arrival_time'] ?? '12:45');
            
            $depM1 = parseTimeToMins($dep1);
            $arrM1 = parseTimeToMins($arr1);
            if ($arrM1 < $depM1) $arrM1 += 1440;
            $dur1 = $arrM1 - $depM1;
            
            $depM2 = parseTimeToMins($dep2);
            $arrM2 = parseTimeToMins($arr2);
            if ($arrM2 < $depM2) $arrM2 += 1440;
            $dur2 = $arrM2 - $depM2;
            
            $layM = $depM2 - $arrM1;
            if ($layM < 0) $layM += 1440;
            if ($layM <= 0) $layM = 99;
            
            return array(
                'from_info' => $fromInfo,
                'to_info' => $toInfo,
                'via_info' => $viaInfo,
                'from_code' => $fromCode,
                'to_code' => $toCode,
                'via_code' => $viaCode,
                'leg1_fn' => $seg1['flight_number'] ?? ($flt['flight_number'] ?? '6E-5021'),
                'leg2_fn' => $seg2['flight_number'] ?? ($flt['flight_number'] ?? '6E-5039'),
                'leg1_dep' => $dep1,
                'leg1_arr' => $arr1,
                'leg1_duration' => formatMinsDuration($dur1),
                'layover_duration' => sprintf('%02dh:%02dm', floor($layM / 60), $layM % 60),
                'leg2_dep' => $dep2,
                'leg2_arr' => $arr2,
                'leg2_duration' => formatMinsDuration($dur2),
            );
        }

        $depMins = parseTimeToMins($flt['departure_time'] ?? '07:15');
        $arrMins = parseTimeToMins($flt['arrival_time'] ?? '12:45');
        if ($arrMins < $depMins) {
            $arrMins += 1440;
        }
        $totalJourneyMins = $arrMins - $depMins;
        if ($totalJourneyMins <= 60) {
            $totalJourneyMins = 330;
        }

        $leg1Mins = max(60, (int)round($totalJourneyMins * 0.38));
        $layoverMins = max(45, (int)round($totalJourneyMins * 0.30));
        $leg2Mins = $totalJourneyMins - $leg1Mins - $layoverMins;
        if ($leg2Mins < 45) {
            $leg2Mins = 55;
            $layoverMins = max(40, $totalJourneyMins - $leg1Mins - $leg2Mins);
        }

        $leg1Dep = $flt['departure_time'] ?? '07:15';
        $leg1ArrMins = $depMins + $leg1Mins;
        $leg1Arr = minsToTimeStr($leg1ArrMins);

        $leg2DepMins = $leg1ArrMins + $layoverMins;
        $leg2Dep = minsToTimeStr($leg2DepMins);
        $leg2Arr = $flt['arrival_time'] ?? '12:45';

        $fromCode = strtoupper($flt['from_code'] ?? 'DEL');
        $toCode = strtoupper($flt['to_code'] ?? 'BOM');
        $viaCode = !empty($flt['via']) ? strtoupper($flt['via']) : 'HYD';
        if ($viaCode === $fromCode || $viaCode === $toCode) {
            $viaCode = ($fromCode === 'HYD' || $toCode === 'HYD') ? 'BHO' : 'HYD';
        }

        $fromInfo = $airportDb[$fromCode] ?? array('city' => ($flt['from_city'] ?? $fromCode), 'name' => ($flt['from_airport'] ?? $fromCode . ' Airport'), 'country' => 'India', 'terminal' => ($flt['from_terminal'] ?? 'Terminal 2'));
        $toInfo = $airportDb[$toCode] ?? array('city' => ($flt['to_city'] ?? $toCode), 'name' => ($flt['to_airport'] ?? $toCode . ' Airport'), 'country' => 'India', 'terminal' => ($flt['to_terminal'] ?? 'Terminal 1'));
        $viaInfo = $airportDb[$viaCode] ?? array('city' => $viaCode, 'name' => $viaCode . ' Airport', 'country' => 'India', 'terminal' => 'Terminal 1');

        $fn1 = $flt['flight_number'] ?? '6E-5021';
        $fn2 = $fn1;
        if (preg_match('/^([A-Z0-9]{2})[-\s]?(\d+)$/i', trim($fn1), $fnM)) {
            $fn2 = strtoupper($fnM[1]) . '-' . ((int)$fnM[2] + 18);
        } else {
            $fn2 = $fn1 . 'B';
        }

        return array(
            'from_info' => $fromInfo,
            'to_info' => $toInfo,
            'via_info' => $viaInfo,
            'from_code' => $fromCode,
            'to_code' => $toCode,
            'via_code' => $viaCode,
            'leg1_fn' => $fn1,
            'leg2_fn' => $fn2,
            'leg1_dep' => $leg1Dep,
            'leg1_arr' => $leg1Arr,
            'leg1_duration' => formatMinsDuration($leg1Mins),
            'layover_duration' => sprintf('%02dh:%02dm', floor($layoverMins / 60), $layoverMins % 60),
            'leg2_dep' => $leg2Dep,
            'leg2_arr' => $leg2Arr,
            'leg2_duration' => formatMinsDuration($leg2Mins),
        );
    }
}

// Onward flight display vars
$airlineName   = $flight['airline_name'] ?? 'IndiGo';
$airlineLogo   = $flight['airline_logo'] ?? 'https://imgak.akbartravels.com/airline-logo/6E.png';
$flightNumber  = $flight['flight_number'] ?? '6E-5021';
$depTime       = $flight['departure_time'] ?? '07:15';
$arrTime       = $flight['arrival_time'] ?? '12:45';
$depDate       = $flight['departure_date'] ?? date('Y-m-d', strtotime('+3 days'));
$arrDate       = $flight['arrival_date'] ?? $depDate;
$fromCity      = $flight['from_city'] ?? 'New Delhi';
$fromCode      = $flight['from_code'] ?? 'DEL';
$fromAirport   = $flight['from_airport'] ?? 'Indira Gandhi International Airport, Delhi';
$fromTerminal  = $flight['from_terminal'] ?? 'Terminal 2';
$toCity        = $flight['to_city'] ?? 'Mumbai';
$toCode        = $flight['to_code'] ?? 'BOM';
$toAirport     = $flight['to_airport'] ?? 'Chhatrapati Shivaji Maharaj International Airport, Mumbai';
$toTerminal    = $flight['to_terminal'] ?? 'Terminal 2';
$flightDuration = $flight['duration'] ?? '05h 30m';
$flightStops   = (int)($flight['stops'] ?? 0);
$isOnwardConnecting = ($flightStops > 0);
$isRefundable  = !empty($flight['refundable']);

// Return flight display vars (if roundtrip)
$retAirlineName = $return_flight['airline_name'] ?? 'Air India';
$retAirlineLogo = $return_flight['airline_logo'] ?? 'https://imgak.akbartravels.com/airline-logo/AI.png';
$retFlightNumber = $return_flight['flight_number'] ?? 'AI-632';
$retDepTime    = $return_flight['departure_time'] ?? '19:45';
$retArrTime    = $return_flight['arrival_time'] ?? '01:00';
$retDepDate    = $return_flight['departure_date'] ?? date('Y-m-d', strtotime('+7 days'));
$retArrDate    = $return_flight['arrival_date'] ?? $retDepDate;
$retFromCity   = $return_flight['from_city'] ?? $toCity;
$retFromCode   = $return_flight['from_code'] ?? $toCode;
$retFromAirport = $return_flight['from_airport'] ?? $toAirport;
$retFromTerminal = $return_flight['from_terminal'] ?? $toTerminal;
$retToCity     = $return_flight['to_city'] ?? $fromCity;
$retToCode     = $return_flight['to_code'] ?? $fromCode;
$retToAirport  = $return_flight['to_airport'] ?? $fromAirport;
$retToTerminal = $return_flight['to_terminal'] ?? $fromTerminal;
$retDuration   = $return_flight['duration'] ?? '05h 15m';
$retStops      = (int)($return_flight['stops'] ?? 0);
$isReturnConnecting = ($retStops > 0);
$retIsRefundable = !empty($return_flight['refundable']);

// Travel Class, Aircraft and Baggage allowances
$aircraftDisplay    = !empty($flight['aircraft']) ? strtoupper(trim($flight['aircraft'])) : 'BOEING';
$travelClassDisplay = $post_data['cabin_class'] ?? ($search_query['cabin_class'] ?? ($flight['cabin_class'] ?? 'Economy'));
$checkinDisplay     = $flight['checkin_baggage'] ?? 'Adult - 15Kg';
$cabinDisplay       = $flight['cabin_baggage'] ?? 'Adult - 7Kg';

$retAircraft = !empty($return_flight['aircraft']) ? strtoupper(trim($return_flight['aircraft'])) : 'BOEING';
$retClass    = !empty($return_flight['cabin_class']) ? ucfirst(strtolower($return_flight['cabin_class'])) : $travelClassDisplay;
$retCheckin  = !empty($return_flight['checkin_baggage']) ? $return_flight['checkin_baggage'] : $checkinDisplay;
$retCabin    = !empty($return_flight['cabin_baggage']) ? $return_flight['cabin_baggage'] : $cabinDisplay;

// Contact Info
$contactName  = $post_data['contact_name'] ?? 'Voyogo Traveller';
$contactEmail = $post_data['contact_email'] ?? 'booking@voyogo.com';
$contactPhone = $post_data['contact_phone'] ?? '9876543210';

// Pricing variables
$fBaseFare       = (float)($fBaseFare ?? 8274);
$fTaxes          = (float)($fTaxes ?? 2226);
$fareTierDelta   = (float)($fareTierDelta ?? 0);
$insuranceAmount = (float)($insuranceAmount ?? 0);
$addonTotal      = (float)($addonTotal ?? 0);
$promoDiscount   = (float)($promoDiscount ?? 0);
$promoCode       = $post_data['promo_code'] ?? 'ATFLY';
$grandTotal      = (float)($grandTotal ?? max(0, ($fBaseFare + $fTaxes + $fareTierDelta + $insuranceAmount + $addonTotal) - $promoDiscount));

// Addon Selections for display
$onwardBaggageDesc = $post_data['selected_baggage_desc'] ?? '';
$onwardBaggageAmt  = (float)($post_data['selected_baggage_amount'] ?? 0);
$onwardMealDesc    = $post_data['selected_meal_desc'] ?? '';
$onwardMealAmt     = (float)($post_data['selected_meal_amount'] ?? 0);
$onwardSeatCode    = $post_data['selected_seat_code'] ?? '';
$onwardSeatAmt     = (float)($post_data['selected_seat_amount'] ?? 0);

$returnBaggageDesc = $post_data['return_selected_baggage_desc'] ?? '';
$returnBaggageAmt  = (float)($post_data['return_selected_baggage_amount'] ?? 0);
$returnMealDesc    = $post_data['return_selected_meal_desc'] ?? '';
$returnMealAmt     = (float)($post_data['return_selected_meal_amount'] ?? 0);
$returnSeatCode    = $post_data['return_selected_seat_code'] ?? '';
$returnSeatAmt     = (float)($post_data['return_selected_seat_amount'] ?? 0);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --voyogo-navy: #0d3470;
    --voyogo-dark: #09204b;
    --voyogo-blue: #0284c7;
    --voyogo-blue-light: #f0f9ff;
    --voyogo-red: #ef4444;
    --voyogo-green: #16a34a;
    --voyogo-green-bg: #f0fdf4;
    --voyogo-border: #e2e8f0;
    --voyogo-bg: #f8fafc;
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Inter', -apple-system, sans-serif;
}

body {
    background-color: var(--voyogo-bg);
    font-family: var(--font-body);
    color: #1e293b;
    margin: 0;
    padding: 0;
}

.review-payment-wrapper {
    max-width: 1240px;
    margin: 20px auto 40px;
    padding: 0 16px;
}

/* 4-Step Breadcrumb Bar */
.step-tracker-bar {
    background: #ffffff;
    border-radius: 12px;
    padding: 14px 20px;
    border: 1px solid var(--voyogo-border);
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}

.step-items-container {
    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.step-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
}

.step-item.completed {
    color: var(--voyogo-green);
}

.step-item.active {
    color: var(--voyogo-blue);
    background: #e0f2fe;
    padding: 4px 12px;
    border-radius: 20px;
}

.btn-back-link {
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s ease;
}

.btn-back-link:hover {
    color: var(--voyogo-blue);
}

/* Two Column Layout */
.payment-main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: start;
}

@media (max-width: 991px) {
    .payment-main-grid {
        grid-template-columns: 1fr;
    }
}

/* Review Card Container */
.flight-details-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--voyogo-border);
    box-shadow: 0 4px 20px rgba(0,32,90,0.04);
    overflow: hidden;
    margin-bottom: 24px;
}

.card-header-bar {
    padding: 18px 24px;
    border-bottom: 1px solid var(--voyogo-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
}

.card-header-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--voyogo-navy);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.card-header-sub {
    font-size: 12.5px;
    color: #64748b;
    margin-top: 3px;
}

/* Route Tabs (Akbar Travels Pattern) */
.route-nav-tabs {
    display: flex;
    background: #f8fafc;
    border-bottom: 1px solid var(--voyogo-border);
    padding: 0 16px;
    overflow-x: auto;
}

.route-tab-btn {
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 800;
    color: #64748b;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.route-tab-btn:hover {
    color: var(--voyogo-blue);
}

.route-tab-btn.active {
    color: var(--voyogo-blue);
    border-bottom-color: var(--voyogo-blue);
    background: #ffffff;
}

.tab-pane-content {
    padding: 24px;
    display: none;
}

.tab-pane-content.active {
    display: block;
}

/* Blue Sector Banner Strip */
.sector-banner-strip {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 10px;
    padding: 12px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.sector-banner-route {
    font-size: 15px;
    font-weight: 900;
    color: #0369a1;
    letter-spacing: 0.3px;
}

.sector-banner-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.refund-pill {
    background: var(--voyogo-green-bg);
    color: var(--voyogo-green);
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11.5px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.fare-rules-link {
    color: var(--voyogo-blue);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}

.fare-rules-link:hover {
    text-decoration: underline;
}

/* Flight Details Row */
.flight-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 12px;
}

.airline-brand-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.airline-brand-group img {
    height: 38px;
    width: 38px;
    object-fit: contain;
    border-radius: 8px;
    padding: 3px;
    background: #f8fafc;
    border: 1px solid var(--voyogo-border);
}

.airline-brand-text h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    color: var(--voyogo-navy);
}

.airline-brand-text span {
    font-size: 12.5px;
    color: #64748b;
    font-weight: 600;
}

.cabin-baggage-pill {
    background: #f8fafc;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 12px;
    color: #334155;
    font-weight: 600;
}

/* Multi-Segment Connecting Flight Styles (Screenshot 2 & 3 Match) */
.seg-specs-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    gap: 12px;
    font-size: 12px;
}

.seg-specs-divider {
    width: 1px;
    height: 22px;
    background: #cbd5e1;
}

@media (max-width: 768px) {
    .seg-header-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px !important;
    }
    .seg-specs-box {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        width: 100% !important;
        padding: 8px 12px !important;
    }
    .seg-specs-divider {
        display: none !important;
    }
}

/* Flight Schedule Grid */
.flight-schedule-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border: 1px solid var(--voyogo-border);
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 24px;
}

.schedule-city-col {
    max-width: 38%;
}

.schedule-city-col.text-right {
    text-align: right;
}

.flight-time-big {
    font-size: 26px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
}

.flight-date-sub {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    margin-top: 3px;
}

.flight-city-title {
    font-size: 15px;
    font-weight: 800;
    color: var(--voyogo-navy);
    margin-top: 4px;
}

.flight-terminal-text {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
}

.schedule-duration-col {
    text-align: center;
    min-width: 140px;
    padding: 0 16px;
}

.duration-line-pill {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin: 8px 0;
}

.duration-line-pill::before {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    height: 2px;
    background: #cbd5e1;
    border-top: 1px dashed #94a3b8;
}

.duration-line-pill i {
    position: relative;
    background: #f8fafc;
    padding: 0 6px;
    color: var(--voyogo-blue);
    font-size: 14px;
}

.flight-duration-label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
}

.flight-stops-label {
    font-size: 11px;
    color: #b45309;
    font-weight: 700;
}

/* Accordion Sections (Screenshot 2 & 3) */
.flight-accordion-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 24px;
    border-top: 1px solid var(--voyogo-border);
    padding-top: 20px;
}

.accordion-card {
    border: 1px solid var(--voyogo-border);
    border-radius: 10px;
    overflow: hidden;
    background: #ffffff;
}

.accordion-trigger {
    width: 100%;
    padding: 14px 18px;
    background: #ffffff;
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    transition: background 0.2s ease;
}

.accordion-trigger:hover {
    background: #f8fafc;
}

.accordion-trigger .icon-chevron {
    color: #94a3b8;
    font-size: 13px;
    transition: transform 0.2s ease;
}

.accordion-card.open .accordion-trigger .icon-chevron {
    transform: rotate(180deg);
}

.accordion-body {
    padding: 16px 20px;
    border-top: 1px solid var(--voyogo-border);
    background: #fafbfc;
    display: none;
}

.accordion-card.open .accordion-body {
    display: block;
}

/* Traveller Subtabs (Baggage / Meal / Seats - Screenshot 3) */
.traveller-subtabs-row {
    display: flex;
    gap: 8px;
    border-bottom: 1.5px solid #e2e8f0;
    margin-bottom: 14px;
}

.traveller-subtab-btn {
    padding: 8px 16px;
    background: transparent;
    border: none;
    border-bottom: 2.5px solid transparent;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.traveller-subtab-btn:hover {
    color: var(--voyogo-blue);
}

.traveller-subtab-btn.active {
    color: var(--voyogo-blue);
    border-bottom-color: var(--voyogo-blue);
}

.addon-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    background: #ffffff;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.addon-status-pill i {
    color: var(--voyogo-green);
    font-size: 14px;
}

/* Fare Rules & Summary Tab (Screenshot 4) */
.policy-subtabs-container {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--voyogo-border);
    padding-bottom: 12px;
}

.policy-subtab-btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid var(--voyogo-border);
    background: #f8fafc;
    font-size: 12px;
    font-weight: 800;
    color: #475569;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.policy-subtab-btn.active {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #7dd3fc;
}

.policy-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--voyogo-border);
    margin-bottom: 16px;
}

.policy-table th {
    background: #f1f5f9;
    padding: 10px 14px;
    font-weight: 700;
    color: #334155;
    text-align: left;
    border-bottom: 1px solid var(--voyogo-border);
}

.policy-table td {
    padding: 10px 14px;
    color: #475569;
    border-bottom: 1px solid #f1f5f9;
}

/* Make Payment Card */
.payment-methods-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--voyogo-border);
    box-shadow: 0 4px 20px rgba(0,32,90,0.04);
    padding: 24px;
    margin-bottom: 24px;
}

.payment-methods-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--voyogo-navy);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.payment-options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
}

.payment-option-item {
    border: 1.5px solid var(--voyogo-border);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #ffffff;
}

.payment-option-item:hover, .payment-option-item.selected {
    border-color: var(--voyogo-blue);
    background: var(--voyogo-blue-light);
}

.payment-option-item input[type="radio"] {
    accent-color: var(--voyogo-blue);
    width: 17px;
    height: 17px;
}

.payment-option-info h5 {
    margin: 0;
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
}

.payment-option-info p {
    margin: 2px 0 0;
    font-size: 11.5px;
    color: #64748b;
}

.btn-proceed-pay {
    width: 100%;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 16px 24px;
    font-size: 17px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    transition: all 0.2s ease;
}

.btn-proceed-pay:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Sticky Fare Summary Sidebar */
.sticky-fare-sidebar {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.fare-card-box {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--voyogo-border);
    box-shadow: 0 4px 20px rgba(0,32,90,0.04);
    padding: 22px;
}

.fare-row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    color: #475569;
    margin-bottom: 12px;
}

.fare-total-row {
    border-top: 1.5px dashed var(--voyogo-border);
    padding-top: 14px;
    margin-top: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.trust-badges-container {
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid var(--voyogo-border);
    padding: 14px 18px;
    font-size: 12.5px;
    color: #475569;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
</style>

<div class="review-payment-wrapper">

    <!-- 1. Breadcrumb / Progress Bar -->
    <div class="step-tracker-bar">
        <div class="step-items-container">
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 1. Flight Selected
            </span>
            <span style="color: #cbd5e1;">&bull;</span>
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 2. Review &amp; Pax Details
            </span>
            <span style="color: #cbd5e1;">&bull;</span>
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 3. Add-on Services
            </span>
            <span style="color: #cbd5e1;">&bull;</span>
            <span class="step-item active">
                <i class="fa-solid fa-circle-dot"></i> 4. Review Details &amp; Payment
            </span>
        </div>
        <div>
            <a href="<?php echo site_url('flight/addons'); ?>" class="btn-back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Addon Services
            </a>
        </div>
    </div>

    <!-- 2. Main Two Column Grid -->
    <div class="payment-main-grid">

        <!-- Left Column: Flight Details Review & Payment Modes -->
        <div>

            <!-- A. Review Your Flight Details Card (Akbar Travels Pattern) -->
            <div class="flight-details-card">
                
                <div class="card-header-bar">
                    <div>
                        <h3 class="card-header-title">
                            <i class="fa-solid fa-plane-departure" style="color: var(--voyogo-blue);"></i> Review Your Flight Details
                        </h3>
                        <div class="card-header-sub">
                            Please verify your flight itinerary, traveller details and selected addons before payment
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs: Onward Route | Return Route | Fare Rules & Summary -->
                <div class="route-nav-tabs">
                    <button type="button" class="route-tab-btn active" id="tabBtn_onward" onclick="switchReviewTab('onward');">
                        <span><?php echo htmlspecialchars($fromCode); ?></span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                        <span><?php echo htmlspecialchars($toCode); ?></span>
                    </button>

                    <?php if ($is_roundtrip): ?>
                    <button type="button" class="route-tab-btn" id="tabBtn_return" onclick="switchReviewTab('return');">
                        <span><?php echo htmlspecialchars($retFromCode); ?></span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                        <span><?php echo htmlspecialchars($retToCode); ?></span>
                    </button>
                    <?php endif; ?>

                    <button type="button" class="route-tab-btn" id="tabBtn_farerules" onclick="switchReviewTab('farerules');">
                        <i class="fa-solid fa-file-invoice"></i> Fare Rules &amp; Summary
                    </button>
                </div>

                <!-- Tab 1: Onward Sector Content -->
                <div class="tab-pane-content active" id="tabPane_onward">
                    
                    <!-- Blue Strip Banner -->
                    <div class="sector-banner-strip">
                        <div class="sector-banner-route">
                            <?php echo strtoupper(htmlspecialchars($fromCity)); ?> &rarr; <?php echo strtoupper(htmlspecialchars($toCity)); ?> , <?php echo date('d M', strtotime($depDate)); ?>
                        </div>
                        <div class="sector-banner-meta">
                            <span class="refund-pill">
                                <i class="fa-solid fa-rotate-left"></i> <?php echo $isRefundable ? 'Refundable' : 'Partially Refundable'; ?>
                            </span>
                            <a href="javascript:void(0);" onclick="switchReviewTab('farerules');" class="fare-rules-link">
                                <i class="fa-solid fa-circle-info"></i> Fare Rules
                            </a>
                        </div>
                    </div>

                    <?php if ($isOnwardConnecting): ?>
                        <!-- 1+ Stop Connecting Flight View (Screenshot 2 Match) -->
                        <?php $segData = buildConnectingFlightSegments($flight, $airportDb); ?>
                        
                        <!-- Segment 1 (Origin -> Layover) -->
                        <div style="padding-top: 6px;">
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($airlineLogo); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($airlineName); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($airlineName); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($segData['leg1_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($aircraftDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($travelClassDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($checkinDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($cabinDisplay); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; margin-bottom: 18px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg1_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($depDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['from_info']['city']); ?> [<?php echo htmlspecialchars($segData['from_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['from_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($segData['from_info']['terminal']); ?></span>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($segData['leg1_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #38bdf8;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #0284c7; font-size: 15px; background: #f8fafc; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg1_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($depDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['via_info']['city']); ?> [<?php echo htmlspecialchars($segData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['via_info']['name']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Layover / Connecting Plane Banner (Screenshot 2 Exact Match) -->
                        <div style="background: linear-gradient(90deg, #e0f2fe 0%, #bae6fd 50%, #e0f2fe 100%); border: 1px solid #7dd3fc; color: #0369a1; padding: 11px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; margin: 18px 0; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);">
                            <i class="fa-solid fa-circle-info" style="font-size: 14px; color: #0284c7;"></i>
                            <span>Change planes at <strong><?php echo htmlspecialchars($segData['via_info']['city']); ?> | <?php echo htmlspecialchars($segData['via_info']['city']); ?> | IN | India (<?php echo htmlspecialchars($segData['via_code']); ?>)</strong>, Connecting Time: <strong><?php echo htmlspecialchars($segData['layover_duration']); ?></strong></span>
                        </div>

                        <!-- Segment 2 (Layover -> Destination) -->
                        <div>
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($airlineLogo); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($airlineName); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($airlineName); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($segData['leg2_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($aircraftDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($travelClassDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($checkinDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($cabinDisplay); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; margin-bottom: 24px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg2_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($depDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['via_info']['city']); ?> [<?php echo htmlspecialchars($segData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['via_info']['name']); ?></div>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($segData['leg2_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #38bdf8;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #0284c7; font-size: 15px; background: #f8fafc; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg2_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($arrDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['to_info']['city']); ?> [<?php echo htmlspecialchars($segData['to_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['to_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($segData['to_info']['terminal']); ?></span>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Direct Flight Single Leg Layout -->
                        <div class="flight-meta-row">
                            <div class="airline-brand-group">
                                <img src="<?php echo htmlspecialchars($airlineLogo); ?>" alt="airline" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($airlineName); ?>&background=0d3470&color=fff';">
                                <div class="airline-brand-text">
                                    <h4><?php echo htmlspecialchars($airlineName); ?> <span style="color: #64748b; font-weight: 600;">(<?php echo htmlspecialchars($flightNumber); ?>)</span></h4>
                                    <span>Aircraft: <strong><?php echo htmlspecialchars($aircraftDisplay); ?></strong> | Cabin: <strong><?php echo htmlspecialchars($travelClassDisplay); ?></strong></span>
                                </div>
                            </div>

                            <div class="cabin-baggage-pill">
                                <i class="fa-solid fa-suitcase" style="color: #0284c7; margin-right: 4px;"></i> Check-In: <strong><?php echo htmlspecialchars($checkinDisplay); ?></strong>
                                <span style="color: #cbd5e1; margin: 0 6px;">|</span>
                                <i class="fa-solid fa-briefcase" style="color: #0284c7; margin-right: 4px;"></i> Cabin: <strong><?php echo htmlspecialchars($cabinDisplay); ?></strong>
                            </div>
                        </div>

                        <div class="flight-schedule-grid">
                            <div class="schedule-city-col">
                                <div class="flight-time-big"><?php echo htmlspecialchars($depTime); ?></div>
                                <div class="flight-date-sub"><?php echo date('D, d M y', strtotime($depDate)); ?></div>
                                <div class="flight-city-title"><?php echo htmlspecialchars($fromCity); ?> [<?php echo htmlspecialchars($fromCode); ?>]</div>
                                <div class="flight-terminal-text"><?php echo htmlspecialchars($fromAirport); ?> &bull; <?php echo htmlspecialchars($fromTerminal); ?></div>
                            </div>

                            <div class="schedule-duration-col">
                                <div class="flight-duration-label"><?php echo htmlspecialchars($flightDuration); ?></div>
                                <div class="duration-line-pill">
                                    <i class="fa-solid fa-plane"></i>
                                </div>
                                <div class="flight-stops-label">
                                    Non-Stop
                                </div>
                            </div>

                            <div class="schedule-city-col text-right">
                                <div class="flight-time-big"><?php echo htmlspecialchars($arrTime); ?></div>
                                <div class="flight-date-sub"><?php echo date('D, d M y', strtotime($arrDate)); ?></div>
                                <div class="flight-city-title"><?php echo htmlspecialchars($toCity); ?> [<?php echo htmlspecialchars($toCode); ?>]</div>
                                <div class="flight-terminal-text"><?php echo htmlspecialchars($toAirport); ?> &bull; <?php echo htmlspecialchars($toTerminal); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 3 Collapsible Accordion Sections -->
                    <div class="flight-accordion-group">
                        
                        <!-- 1. Travel Insurance Accordion -->
                        <div class="accordion-card" id="accCard_insurance">
                            <button type="button" class="accordion-trigger" onclick="toggleReviewAcc('accCard_insurance');">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-shield-halved" style="color: #2563eb;"></i> Travel Insurance
                                </span>
                                <i class="fa-solid fa-chevron-down icon-chevron"></i>
                            </button>
                            <div class="accordion-body">
                                <?php if ($insuranceAmount > 0): ?>
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                        <span class="refund-pill" style="font-size: 12px;">
                                            <i class="fa-solid fa-circle-check"></i> Comprehensive Travel Protection Active
                                        </span>
                                        <span style="font-size: 13px; font-weight: 800; color: #0f172a;">₹ <?php echo number_format($insuranceAmount); ?></span>
                                    </div>
                                    <div style="font-size: 12px; color: #475569; line-height: 1.6;">
                                        &bull; Emergency Medical Treatment up to ₹ 1,00,000<br>
                                        &bull; Trip Cancellation &amp; Delay Coverage up to ₹ 25,000<br>
                                        &bull; Baggage Loss &amp; Delayed Delivery Coverage up to ₹ 10,000
                                    </div>
                                <?php else: ?>
                                    <div style="font-size: 12.5px; color: #64748b;">
                                        No optional travel insurance was selected for this journey. You are covered by standard airline carriage terms.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 2. Traveller Details & Addons Accordion (Open by Default - Screenshot 3) -->
                        <div class="accordion-card open" id="accCard_travellers">
                            <button type="button" class="accordion-trigger" onclick="toggleReviewAcc('accCard_travellers');">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-users" style="color: #0284c7;"></i> Traveller Details and Addons
                                </span>
                                <i class="fa-solid fa-chevron-down icon-chevron"></i>
                            </button>
                            <div class="accordion-body">
                                
                                <?php foreach ($passengers as $pIdx => $pax): 
                                    $paxBaggageDesc = $post_data['passenger_baggage'][$pIdx]['desc'] ?? $post_data['passenger_baggage'][$pIdx]['code'] ?? ($pIdx === 0 ? $onwardBaggageDesc : '');
                                    $paxBaggageAmt  = (float)($post_data['passenger_baggage'][$pIdx]['amount'] ?? ($pIdx === 0 ? $onwardBaggageAmt : 0));
                                    $paxMealDesc    = $post_data['passenger_meal'][$pIdx]['desc'] ?? $post_data['passenger_meal'][$pIdx]['code'] ?? ($pIdx === 0 ? $onwardMealDesc : '');
                                    $paxMealAmt     = (float)($post_data['passenger_meal'][$pIdx]['amount'] ?? ($pIdx === 0 ? $onwardMealAmt : 0));
                                    $paxSeatCode    = $post_data['passenger_seat'][$pIdx]['code'] ?? ($pIdx === 0 ? $onwardSeatCode : '');
                                    $paxSeatAmt     = (float)($post_data['passenger_seat'][$pIdx]['amount'] ?? ($pIdx === 0 ? $onwardSeatAmt : 0));

                                    $paxRetBaggageDesc = $post_data['return_passenger_baggage'][$pIdx]['desc'] ?? $post_data['return_passenger_baggage'][$pIdx]['code'] ?? ($pIdx === 0 ? $returnBaggageDesc : '');
                                    $paxRetBaggageAmt  = (float)($post_data['return_passenger_baggage'][$pIdx]['amount'] ?? ($pIdx === 0 ? $returnBaggageAmt : 0));
                                    $paxRetMealDesc    = $post_data['return_passenger_meal'][$pIdx]['desc'] ?? $post_data['return_passenger_meal'][$pIdx]['code'] ?? ($pIdx === 0 ? $returnMealDesc : '');
                                    $paxRetMealAmt     = (float)($post_data['return_passenger_meal'][$pIdx]['amount'] ?? ($pIdx === 0 ? $returnMealAmt : 0));
                                    $paxRetSeatCode    = $post_data['return_passenger_seat'][$pIdx]['code'] ?? ($pIdx === 0 ? $returnSeatCode : '');
                                    $paxRetSeatAmt     = (float)($post_data['return_passenger_seat'][$pIdx]['amount'] ?? ($pIdx === 0 ? $returnSeatAmt : 0));
                                ?>
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <div style="font-size: 14px; font-weight: 800; color: #0d3470; display: flex; align-items: center; gap: 8px;">
                                            <i class="fa-solid fa-user" style="color: #64748b; font-size: 13px;"></i>
                                            <?php echo htmlspecialchars($pax['title'] . ' ' . $pax['name']); ?>
                                        </div>
                                        <span style="font-size: 11.5px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 2px 10px; border-radius: 12px;">
                                            <?php echo htmlspecialchars($pax['type'] ?? 'Adult'); ?>
                                        </span>
                                    </div>

                                    <!-- Sub-tabs: Baggage | Meal | Seats (Screenshot 3) -->
                                    <div class="traveller-subtabs-row">
                                        <button type="button" class="traveller-subtab-btn active" id="subtabBtn_baggage_<?php echo $pIdx; ?>" onclick="switchPaxAddonSubtab(<?php echo $pIdx; ?>, 'baggage');">
                                            <i class="fa-solid fa-suitcase"></i> Baggage
                                        </button>
                                        <button type="button" class="traveller-subtab-btn" id="subtabBtn_meal_<?php echo $pIdx; ?>" onclick="switchPaxAddonSubtab(<?php echo $pIdx; ?>, 'meal');">
                                            <i class="fa-solid fa-utensils"></i> Meal
                                        </button>
                                        <button type="button" class="traveller-subtab-btn" id="subtabBtn_seats_<?php echo $pIdx; ?>" onclick="switchPaxAddonSubtab(<?php echo $pIdx; ?>, 'seats');">
                                            <i class="fa-solid fa-chair"></i> Seats
                                        </button>
                                    </div>

                                    <!-- Baggage Tab View -->
                                    <div id="paxSubtab_baggage_<?php echo $pIdx; ?>" style="display: block;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155; margin-bottom: 6px;">
                                            <span><?php echo htmlspecialchars($fromCity); ?> &rarr; <?php echo htmlspecialchars($toCity); ?></span>
                                            <span class="addon-status-pill">
                                                <i class="fa-solid fa-check"></i>
                                                <?php echo !empty($paxBaggageDesc) ? htmlspecialchars($paxBaggageDesc) . ' (₹ ' . number_format($paxBaggageAmt) . ')' : 'Standard 15 Kg Included'; ?>
                                            </span>
                                        </div>
                                        <?php if ($is_roundtrip): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155;">
                                            <span><?php echo htmlspecialchars($retFromCity); ?> &rarr; <?php echo htmlspecialchars($retToCity); ?></span>
                                            <span class="addon-status-pill">
                                                <i class="fa-solid fa-check"></i>
                                                <?php echo !empty($paxRetBaggageDesc) ? htmlspecialchars($paxRetBaggageDesc) . ' (₹ ' . number_format($paxRetBaggageAmt) . ')' : 'Standard 15 Kg Included'; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Meal Tab View -->
                                    <div id="paxSubtab_meal_<?php echo $pIdx; ?>" style="display: none;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155; margin-bottom: 6px;">
                                            <span><?php echo htmlspecialchars($fromCity); ?> &rarr; <?php echo htmlspecialchars($toCity); ?></span>
                                            <span class="addon-status-pill">
                                                <?php if (!empty($paxMealDesc)): ?>
                                                    <i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($paxMealDesc); ?> (₹ <?php echo number_format($paxMealAmt); ?>)
                                                <?php else: ?>
                                                    <span style="color: #64748b;">No meal pre-booked</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php if ($is_roundtrip): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155;">
                                            <span><?php echo htmlspecialchars($retFromCity); ?> &rarr; <?php echo htmlspecialchars($retToCity); ?></span>
                                            <span class="addon-status-pill">
                                                <?php if (!empty($paxRetMealDesc)): ?>
                                                    <i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($paxRetMealDesc); ?> (₹ <?php echo number_format($paxRetMealAmt); ?>)
                                                <?php else: ?>
                                                    <span style="color: #64748b;">No meal pre-booked</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Seats Tab View -->
                                    <div id="paxSubtab_seats_<?php echo $pIdx; ?>" style="display: none;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155; margin-bottom: 6px;">
                                            <span><?php echo htmlspecialchars($fromCity); ?> &rarr; <?php echo htmlspecialchars($toCity); ?></span>
                                            <span class="addon-status-pill">
                                                <?php if (!empty($paxSeatCode)): ?>
                                                    <i class="fa-solid fa-check"></i> Seat <?php echo htmlspecialchars($paxSeatCode); ?> (₹ <?php echo number_format($paxSeatAmt); ?>)
                                                <?php else: ?>
                                                    <span style="color: #64748b;">Auto-assigned at check-in</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php if ($is_roundtrip): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #334155;">
                                            <span><?php echo htmlspecialchars($retFromCity); ?> &rarr; <?php echo htmlspecialchars($retToCity); ?></span>
                                            <span class="addon-status-pill">
                                                <?php if (!empty($paxRetSeatCode)): ?>
                                                    <i class="fa-solid fa-check"></i> Seat <?php echo htmlspecialchars($paxRetSeatCode); ?> (₹ <?php echo number_format($paxRetSeatAmt); ?>)
                                                <?php else: ?>
                                                    <span style="color: #64748b;">Auto-assigned at check-in</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                                <?php endforeach; ?>

                            </div>
                        </div>

                        <!-- 3. Contact Information Accordion -->
                        <div class="accordion-card" id="accCard_contact">
                            <button type="button" class="accordion-trigger" onclick="toggleReviewAcc('accCard_contact');">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-address-book" style="color: #10b981;"></i> Contact Information
                                </span>
                                <i class="fa-solid fa-chevron-down icon-chevron"></i>
                            </button>
                            <div class="accordion-body">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                                    <div>
                                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b;">CONTACT NAME</div>
                                        <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 2px;">
                                            <?php echo htmlspecialchars($contactName); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b;">EMAIL ADDRESS (E-TICKET)</div>
                                        <div style="font-size: 14px; font-weight: 800; color: #0284c7; margin-top: 2px;">
                                            <?php echo htmlspecialchars($contactEmail); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b;">MOBILE NUMBER (SMS &amp; WHATSAPP)</div>
                                        <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 2px;">
                                            +91 <?php echo htmlspecialchars($contactPhone); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Tab 2: Return Sector Content (if Roundtrip) -->
                <?php if ($is_roundtrip): ?>
                <div class="tab-pane-content" id="tabPane_return">
                    
                    <!-- Blue Strip Banner -->
                    <div class="sector-banner-strip">
                        <div class="sector-banner-route">
                            <?php echo strtoupper(htmlspecialchars($retFromCity)); ?> &rarr; <?php echo strtoupper(htmlspecialchars($retToCity)); ?> , <?php echo date('d M', strtotime($retDepDate)); ?>
                        </div>
                        <div class="sector-banner-meta">
                            <span class="refund-pill">
                                <i class="fa-solid fa-rotate-left"></i> <?php echo $retIsRefundable ? 'Refundable' : 'Partially Refundable'; ?>
                            </span>
                            <a href="javascript:void(0);" onclick="switchReviewTab('farerules');" class="fare-rules-link">
                                <i class="fa-solid fa-circle-info"></i> Fare Rules
                            </a>
                        </div>
                    </div>

                    <?php if ($isReturnConnecting): ?>
                        <!-- Return 1+ Stop Connecting Flight View (Screenshot 3 Match) -->
                        <?php $retSegData = buildConnectingFlightSegments($return_flight, $airportDb); ?>
                        
                        <!-- Return Segment 1 (Origin -> Layover) -->
                        <div style="padding-top: 6px;">
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($retAirlineLogo); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($retAirlineName); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($retAirlineName); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($retSegData['leg1_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retAircraft); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retClass); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCheckin); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCabin); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; margin-bottom: 18px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg1_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($retDepDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['from_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['from_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['from_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($retSegData['from_info']['terminal']); ?></span>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($retSegData['leg1_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #10b981;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #10b981; font-size: 15px; background: #f8fafc; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg1_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($retDepDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['via_info']['name']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Layover / Connecting Plane Banner (Screenshot 3 Exact Match) -->
                        <div style="background: linear-gradient(90deg, #ecfdf5 0%, #d1fae5 50%, #ecfdf5 100%); border: 1px solid #a7f3d0; color: #065f46; padding: 11px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; margin: 18px 0; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);">
                            <i class="fa-solid fa-circle-info" style="font-size: 14px; color: #10b981;"></i>
                            <span>Change planes at <strong><?php echo htmlspecialchars($retSegData['via_info']['city']); ?> | <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> | IN | India (<?php echo htmlspecialchars($retSegData['via_code']); ?>)</strong>, Connecting Time: <strong><?php echo htmlspecialchars($retSegData['layover_duration']); ?></strong></span>
                        </div>

                        <!-- Return Segment 2 (Layover -> Destination) -->
                        <div>
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($retAirlineLogo); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($retAirlineName); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($retAirlineName); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($retSegData['leg2_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retAircraft); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retClass); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCheckin); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCabin); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; margin-bottom: 24px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg2_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($retDepDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['via_info']['name']); ?></div>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($retSegData['leg2_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #10b981;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #10b981; font-size: 15px; background: #f8fafc; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg2_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($retArrDate)); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['to_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['to_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['to_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($retSegData['to_info']['terminal']); ?></span>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Direct Return Flight Single Leg Layout -->
                        <div class="flight-meta-row">
                            <div class="airline-brand-group">
                                <img src="<?php echo htmlspecialchars($retAirlineLogo); ?>" alt="airline" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($retAirlineName); ?>&background=0d3470&color=fff';">
                                <div class="airline-brand-text">
                                    <h4><?php echo htmlspecialchars($retAirlineName); ?> <span style="color: #64748b; font-weight: 600;">(<?php echo htmlspecialchars($retFlightNumber); ?>)</span></h4>
                                    <span>Aircraft: <strong><?php echo htmlspecialchars($retAircraft); ?></strong> | Cabin: <strong><?php echo htmlspecialchars($retClass); ?></strong></span>
                                </div>
                            </div>

                            <div class="cabin-baggage-pill">
                                <i class="fa-solid fa-suitcase" style="color: #0284c7; margin-right: 4px;"></i> Check-In: <strong><?php echo htmlspecialchars($retCheckin); ?></strong>
                                <span style="color: #cbd5e1; margin: 0 6px;">|</span>
                                <i class="fa-solid fa-briefcase" style="color: #0284c7; margin-right: 4px;"></i> Cabin: <strong><?php echo htmlspecialchars($retCabin); ?></strong>
                            </div>
                        </div>

                        <div class="flight-schedule-grid">
                            <div class="schedule-city-col">
                                <div class="flight-time-big"><?php echo htmlspecialchars($retDepTime); ?></div>
                                <div class="flight-date-sub"><?php echo date('D, d M y', strtotime($retDepDate)); ?></div>
                                <div class="flight-city-title"><?php echo htmlspecialchars($retFromCity); ?> [<?php echo htmlspecialchars($retFromCode); ?>]</div>
                                <div class="flight-terminal-text"><?php echo htmlspecialchars($retFromAirport); ?> &bull; <?php echo htmlspecialchars($retFromTerminal); ?></div>
                            </div>

                            <div class="schedule-duration-col">
                                <div class="flight-duration-label"><?php echo htmlspecialchars($retDuration); ?></div>
                                <div class="duration-line-pill">
                                    <i class="fa-solid fa-plane"></i>
                                </div>
                                <div class="flight-stops-label">
                                    Non-Stop
                                </div>
                            </div>

                            <div class="schedule-city-col text-right">
                                <div class="flight-time-big"><?php echo htmlspecialchars($retArrTime); ?></div>
                                <div class="flight-date-sub"><?php echo date('D, d M y', strtotime($retArrDate)); ?></div>
                                <div class="flight-city-title"><?php echo htmlspecialchars($retToCity); ?> [<?php echo htmlspecialchars($retToCode); ?>]</div>
                                <div class="flight-terminal-text"><?php echo htmlspecialchars($retToAirport); ?> &bull; <?php echo htmlspecialchars($retToTerminal); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

                <!-- Tab 3: Fare Rules & Summary (Screenshot 4) -->
                <div class="tab-pane-content" id="tabPane_farerules">
                    
                    <div class="policy-subtabs-container">
                        <button type="button" class="policy-subtab-btn active" id="btnPolicyChange" onclick="switchPolicySubtab('change');">Change Fee</button>
                        <button type="button" class="policy-subtab-btn" id="btnPolicyCancel" onclick="switchPolicySubtab('cancel');">Cancellation Fee</button>
                        <button type="button" class="policy-subtab-btn" id="btnPolicyAto" onclick="switchPolicySubtab('ato');">ATO Service Fee</button>
                    </div>

                    <!-- Change Fee Matrix -->
                    <div id="policyBox_change" style="display: block;">
                        <table class="policy-table">
                            <thead>
                                <tr>
                                    <th>Timeframe Before Departure</th>
                                    <th>Change Fee (Per Passenger)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0 HRS - 4 HRS To Departure</td>
                                    <td><strong style="color: #dc2626;">Non-Changeable</strong></td>
                                </tr>
                                <tr>
                                    <td>4 HRS - 4 Days To Departure</td>
                                    <td><strong>₹ 3,899</strong> + Fare Difference</td>
                                </tr>
                                <tr>
                                    <td>4 Days - 999 Days To Departure</td>
                                    <td><strong>₹ 3,899</strong> + Fare Difference</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cancellation Fee Matrix -->
                    <div id="policyBox_cancel" style="display: none;">
                        <table class="policy-table">
                            <thead>
                                <tr>
                                    <th>Timeframe Before Departure</th>
                                    <th>Cancellation Fee (Per Passenger)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0 HRS - 4 HRS To Departure</td>
                                    <td><strong style="color: #dc2626;">Non-Refundable</strong></td>
                                </tr>
                                <tr>
                                    <td>4 HRS - 4 Days To Departure</td>
                                    <td><strong>₹ 3,999</strong></td>
                                </tr>
                                <tr>
                                    <td>4 Days - 999 Days To Departure</td>
                                    <td><strong>₹ 3,500</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ATO Service Fee -->
                    <div id="policyBox_ato" style="display: none;">
                        <table class="policy-table">
                            <thead>
                                <tr>
                                    <th>Service Type</th>
                                    <th>Charge</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Airport Ticket Counter (ATO) Assistance</td>
                                    <td>₹ 500 per request</td>
                                </tr>
                                <tr>
                                    <td>Voyogo Customer Desk Processing Fee</td>
                                    <td>Standard Free 24x7 Support</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="font-size: 11.5px; color: #64748b; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                        &bull; The above data is indicative. Fare rules are subject to change by the Airline from time to time.<br>
                        &bull; GST &amp; applicable airline reissue penalty are charged per passenger per sector.<br>
                        &bull; Free cancellation or flexibility applies if opted during initial booking selection.
                    </div>

                </div>

            </div>

            <!-- B. Make Payment Card -->
            <div class="payment-methods-card">
                <h3 class="payment-methods-title">
                    <i class="fa-solid fa-lock" style="color: var(--voyogo-green);"></i> Select Payment Method
                </h3>

                <div class="payment-options-grid">
                    <label class="payment-option-item selected" onclick="selectPayOption(this);">
                        <input type="radio" name="pay_mode" value="upi" checked>
                        <div class="payment-option-info">
                            <h5>UPI / QR Code</h5>
                            <p>Google Pay, PhonePe, Paytm, BHIM</p>
                        </div>
                    </label>

                    <label class="payment-option-item" onclick="selectPayOption(this);">
                        <input type="radio" name="pay_mode" value="card">
                        <div class="payment-option-info">
                            <h5>Credit / Debit Card</h5>
                            <p>Visa, MasterCard, RuPay, Amex</p>
                        </div>
                    </label>

                    <label class="payment-option-item" onclick="selectPayOption(this);">
                        <input type="radio" name="pay_mode" value="netbanking">
                        <div class="payment-option-info">
                            <h5>Net Banking</h5>
                            <p>All 50+ Major Indian Banks</p>
                        </div>
                    </label>

                    <label class="payment-option-item" onclick="selectPayOption(this);">
                        <input type="radio" name="pay_mode" value="wallet">
                        <div class="payment-option-info">
                            <h5>Wallets</h5>
                            <p>Paytm, Mobikwik, PhonePe</p>
                        </div>
                    </label>
                </div>

                <!-- Hidden POST Form to execute final booking creation in process_flight_payment -->
                <form id="flightPaymentForm" action="<?php echo site_url('flight/process_payment'); ?>" method="POST" style="display: none;">
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

                    <input type="hidden" name="total_amount" id="payment_total_amount" value="<?php echo $grandTotal; ?>">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="">
                </form>

                <button type="button" class="btn-proceed-pay" onclick="triggerRazorpayPayment();">
                    <i class="fa-solid fa-shield-halved"></i> Pay Securely ₹ <?php echo number_format($grandTotal); ?>
                </button>

                <div style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 14px; font-size: 11.5px; color: #64748b;">
                    <span><i class="fa-solid fa-lock" style="color: #16a34a;"></i> 256-Bit SSL Encrypted</span>
                    <span>&bull;</span>
                    <span>PCI-DSS Level 1 Certified</span>
                    <span>&bull;</span>
                    <span>Instant Booking Confirmation</span>
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Fare Summary Sidebar -->
        <div>
            <div class="sticky-fare-sidebar">
                
                <!-- Fare Details Box -->
                <div class="fare-card-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--voyogo-border); padding-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">Fare Details</h4>
                        <span style="font-size: 12.5px; font-weight: 700; color: #0284c7;"><?php echo $total_pax; ?> Traveller<?php echo $total_pax > 1 ? 's' : ''; ?></span>
                    </div>

                    <div class="fare-row-item">
                        <span>Base Fare</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($fBaseFare); ?></strong>
                    </div>

                    <div class="fare-row-item">
                        <span>Taxes &amp; Surcharges</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($fTaxes); ?></strong>
                    </div>

                    <?php if ($fareTierDelta > 0): ?>
                    <div class="fare-row-item">
                        <span>Fare Option Upgrade</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($fareTierDelta); ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($insuranceAmount > 0): ?>
                    <div class="fare-row-item">
                        <span>Travel Insurance</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($insuranceAmount); ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($addonTotal > 0): ?>
                    <div class="fare-row-item">
                        <span>Addon Services (Baggage / Meals / Seats)</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($addonTotal); ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($promoDiscount > 0): ?>
                    <div class="fare-row-item" style="color: #16a34a;">
                        <span>Promo Discount Applied</span>
                        <strong>- ₹ <?php echo number_format($promoDiscount); ?></strong>
                    </div>
                    <?php endif; ?>

                    <div class="fare-total-row">
                        <div>
                            <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Payable</div>
                            <div style="font-size: 26px; font-weight: 900; color: #0f172a;">₹ <?php echo number_format($grandTotal); ?></div>
                        </div>
                        <span style="font-size: 11px; font-weight: 700; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 20px;">
                            Guaranteed Fare
                        </span>
                    </div>
                </div>

                <!-- Promo Applied Box -->
                <?php if (!empty($promoCode)): ?>
                <div style="background: #ffffff; border-radius: 10px; border: 1px solid #bbf7d0; padding: 14px 16px; background: #f0fdf4;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-tags" style="color: #16a34a;"></i>
                            <span style="font-size: 13.5px; font-weight: 800; color: #15803d;"><?php echo htmlspecialchars($promoCode); ?> APPLIED</span>
                        </div>
                        <span style="font-size: 12px; font-weight: 800; color: #15803d;">Saved ₹ <?php echo number_format($promoDiscount); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Trust Badges -->
                <div class="trust-badges-container">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-halved" style="color: #2563eb; font-size: 15px;"></i>
                        <span style="font-weight: 700; color: #0f172a;">100% Safe &amp; Instant Booking</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-envelope-circle-check" style="color: #16a34a; font-size: 15px;"></i>
                        <span style="font-weight: 700; color: #0f172a;">Instant E-Ticket Sent to Email &amp; WhatsApp</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- Razorpay Checkout Integration -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Tab Switching: Onward | Return | Fare Rules
function switchReviewTab(tabName) {
    document.querySelectorAll('.route-tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    document.querySelectorAll('.tab-pane-content').forEach(function(p) {
        p.classList.remove('active');
    });

    var targetBtn = document.getElementById('tabBtn_' + tabName);
    var targetPane = document.getElementById('tabPane_' + tabName);

    if (targetBtn) targetBtn.classList.add('active');
    if (targetPane) targetPane.classList.add('active');
}

// Accordion Toggle
function toggleReviewAcc(accId) {
    var card = document.getElementById(accId);
    if (card) {
        card.classList.toggle('open');
    }
}

// Traveller Addons Sub-tab Switcher (Baggage / Meal / Seats)
function switchPaxAddonSubtab(pIdx, subtab) {
    ['baggage', 'meal', 'seats'].forEach(function(s) {
        var btn = document.getElementById('subtabBtn_' + s + '_' + pIdx);
        var pane = document.getElementById('paxSubtab_' + s + '_' + pIdx);
        if (btn) btn.classList.remove('active');
        if (pane) pane.style.display = 'none';
    });

    var activeBtn = document.getElementById('subtabBtn_' + subtab + '_' + pIdx);
    var activePane = document.getElementById('paxSubtab_' + subtab + '_' + pIdx);
    if (activeBtn) activeBtn.classList.add('active');
    if (activePane) activePane.style.display = 'block';
}

// Policy Subtab Switcher
function switchPolicySubtab(pol) {
    document.querySelectorAll('.policy-subtab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    ['change', 'cancel', 'ato'].forEach(function(k) {
        var box = document.getElementById('policyBox_' + k);
        if (box) box.style.display = 'none';
    });

    if (pol === 'change') {
        var b = document.getElementById('btnPolicyChange');
        if (b) b.classList.add('active');
        var box = document.getElementById('policyBox_change');
        if (box) box.style.display = 'block';
    } else if (pol === 'cancel') {
        var b = document.getElementById('btnPolicyCancel');
        if (b) b.classList.add('active');
        var box = document.getElementById('policyBox_cancel');
        if (box) box.style.display = 'block';
    } else if (pol === 'ato') {
        var b = document.getElementById('btnPolicyAto');
        if (b) b.classList.add('active');
        var box = document.getElementById('policyBox_ato');
        if (box) box.style.display = 'block';
    }
}

// Payment Option Selection Visual
function selectPayOption(el) {
    document.querySelectorAll('.payment-option-item').forEach(function(item) {
        item.classList.remove('selected');
    });
    if (el) el.classList.add('selected');
}

// Trigger Razorpay Standard Web Checkout
function triggerRazorpayPayment() {
    var payBtn = document.querySelector('.btn-proceed-pay');
    var originalBtnHtml = payBtn ? payBtn.innerHTML : '';
    
    function setBtnLoading(isLoading) {
        if (!payBtn) return;
        if (isLoading) {
            payBtn.disabled = true;
            payBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Initializing Secure Checkout...';
        } else {
            payBtn.disabled = false;
            payBtn.innerHTML = originalBtnHtml;
        }
    }

    var finalAmount = <?php echo (float)$grandTotal; ?>;
    var amountInPaise = Math.round(finalAmount * 100);
    var contactName = "<?php echo htmlspecialchars($contactName); ?>";
    var contactEmail = "<?php echo htmlspecialchars($contactEmail); ?>";
    var contactPhone = "<?php echo htmlspecialchars($contactPhone); ?>";

    setBtnLoading(true);

    // STEP 1: Call Backend to Create Order
    fetch("<?php echo site_url('api/create-order'); ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            amount: amountInPaise,
            currency: "INR",
            receipt: "rcpt_flt_" + Date.now(),
            service: "Flight Booking"
        })
    })
    .then(function(res) {
        if (!res.ok) {
            return res.json().then(function(errData) {
                throw new Error(errData.message || "Failed to initialize Razorpay order (HTTP " + res.status + ")");
            });
        }
        return res.json();
    })
    .then(function(orderData) {
        if (orderData.status !== 'success' || !orderData.order_id) {
            throw new Error(orderData.message || "Order creation failed.");
        }

        // STEP 2: Configure & Open Razorpay Standard Checkout Modal
        var options = {
            "key": orderData.key_id,
            "amount": orderData.amount,
            "currency": orderData.currency || "INR",
            "name": orderData.merchant_name || "Voyogo Travels",
            "description": "Flight Ticket Booking - <?php echo htmlspecialchars($flightNumber); ?>",
            "image": "<?php echo base_url('assets/images/logo.png'); ?>",
            "order_id": orderData.order_id,
            "handler": function (response) {
                if (payBtn) payBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Verifying Payment Signature...';

                // STEP 3: Verify Payment Signature via Backend
                fetch("<?php echo site_url('api/verify-payment'); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(function(vRes) {
                    return vRes.json();
                })
                .then(function(vData) {
                    if (vData.status === 'success' && vData.verified) {
                        // Signature valid -> Mark inputs and submit booking confirmation
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        if (payBtn) payBtn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Payment Verified! Booking Flight...';
                        document.getElementById('flightPaymentForm').submit();
                    } else {
                        alert("Payment Verification Error: " + (vData.message || "Signature mismatch. Transaction cannot be validated."));
                        setBtnLoading(false);
                    }
                })
                .catch(function(vErr) {
                    alert("Error verifying payment signature: " + vErr.message);
                    setBtnLoading(false);
                });
            },
            "prefill": {
                "name": contactName,
                "email": contactEmail,
                "contact": contactPhone
            },
            "theme": {
                "color": "<?php echo !empty($razorpay_settings['theme_color']) ? htmlspecialchars($razorpay_settings['theme_color']) : '#0d3470'; ?>"
            },
            "modal": {
                "ondismiss": function() {
                    console.log("Razorpay checkout modal closed by customer.");
                    setBtnLoading(false);
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function (resp) {
            var errMsg = resp.error ? (resp.error.description || resp.error.reason) : "Payment failed.";
            alert("Payment Failed: " + errMsg);
            setBtnLoading(false);
        });
        rzp.open();
    })
    .catch(function(err) {
        alert("Payment Gateway Error: " + err.message);
        setBtnLoading(false);
    });
}
</script>
