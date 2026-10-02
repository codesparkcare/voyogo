<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sessionUser = $this->session->userdata('user');
$isUserLoggedIn = !empty($sessionUser);

// Flight and fare details passed from Controller / Session
$post_data = $post_data ?? array();
$flight = $flight ?? ($post_data['flight'] ?? null);
$return_flight = $return_flight ?? ($post_data['return_flight'] ?? null);

$flight_number = $flight_number ?? ($flight['flight_number'] ?? ($post_data['flight_number'] ?? '6E-2054'));
$airline_name = $airline_name ?? ($flight['airline_name'] ?? ($post_data['airline_name'] ?? 'IndiGo'));
$origin = strtoupper(trim($origin ?? ($flight['from_code'] ?? ($post_data['origin'] ?? 'BOM'))));
$destination = strtoupper(trim($destination ?? ($flight['to_code'] ?? ($post_data['destination'] ?? 'DEL'))));
$departure_date = $departure_date ?? ($flight['departure_date'] ?? ($post_data['departure_date'] ?? date('Y-m-d', strtotime('+3 days'))));
$departure_time = $departure_time ?? ($flight['departure_time'] ?? ($post_data['departure_time'] ?? '06:00'));
$arrival_time = $arrival_time ?? ($flight['arrival_time'] ?? ($post_data['arrival_time'] ?? '08:25'));
$duration = $duration ?? ($flight['duration'] ?? ($post_data['duration'] ?? '02h 25m'));
$stops = (int)($stops ?? ($flight['stops'] ?? ($post_data['stops'] ?? 0)));
$via = $via ?? ($flight['via'] ?? ($post_data['via'] ?? ''));

$is_roundtrip = !empty($is_roundtrip) || !empty($post_data['is_roundtrip']) || !empty($post_data['return_flight_number']) || !empty($return_flight);
$return_flight_number = $return_flight_number ?? ($return_flight['flight_number'] ?? ($post_data['return_flight_number'] ?? '6E-5021'));
$return_airline_name = $return_airline_name ?? ($return_flight['airline_name'] ?? ($post_data['return_airline_name'] ?? $airline_name));
$return_origin = strtoupper(trim($return_origin ?? ($return_flight['from_code'] ?? ($post_data['return_origin'] ?? $destination))));
$return_destination = strtoupper(trim($return_destination ?? ($return_flight['to_code'] ?? ($post_data['return_destination'] ?? $origin))));
$return_departure_date = $return_departure_date ?? ($return_flight['departure_date'] ?? ($post_data['return_departure_date'] ?? date('Y-m-d', strtotime('+12 days'))));
$return_departure_time = $return_departure_time ?? ($return_flight['departure_time'] ?? ($post_data['return_departure_time'] ?? '18:30'));
$return_arrival_time = $return_arrival_time ?? ($return_flight['arrival_time'] ?? ($post_data['return_arrival_time'] ?? '20:45'));
$return_duration = $return_duration ?? ($return_flight['duration'] ?? ($post_data['return_duration'] ?? '13h 20m'));
$return_stops = (int)($return_stops ?? ($return_flight['stops'] ?? ($post_data['return_stops'] ?? 0)));

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

$base_fare = (float)($base_fare ?? ($post_data['base_fare'] ?? ($post_data['net_amount'] ?? 10916)));
$taxes = (float)($taxes ?? ($post_data['taxes'] ?? 2527));
$insurance_amount = (float)($insurance_amount ?? ($post_data['insurance_amount'] ?? 199));
$discount_amount = (float)($discount_amount ?? ($post_data['discount_amount'] ?? 47));
$promo_code = $promo_code ?? ($post_data['promo_code'] ?? 'ATFLY');

$fare_tier = $fare_tier ?? ($post_data['fare_tier'] ?? 'Value');
$fare_tier_price_delta = (float)($fare_tier_price_delta ?? ($post_data['fare_tier_price_delta'] ?? 0));
$safety_cancellation_type = $safety_cancellation_type ?? ($post_data['safety_cancellation_type'] ?? '');
$safety_cancellation_amount = (float)($safety_cancellation_amount ?? ($post_data['safety_cancellation_amount'] ?? 0));

$total_passengers = max(1, count($passengers ?? array()));
$initialGrandTotal = max(0, $base_fare + $taxes + $insurance_amount + $safety_cancellation_amount - $discount_amount);

$firstPaxName = 'Mrs DFGFDGDFG GFDGDG';
if (!empty($passengers) && is_array($passengers)) {
    $firstPax = reset($passengers);
    $firstPaxName = trim(($firstPax['title'] ?? 'Mr') . ' ' . ($firstPax['name'] ?? 'DFGFDGDFG GFDGDG'));
} elseif (!empty($post_data['passenger_name']) && is_array($post_data['passenger_name'])) {
    $title = $post_data['passenger_title'][0] ?? 'Mr';
    $name = $post_data['passenger_name'][0] ?? 'DFGFDGDFG GFDGDG';
    $firstPaxName = trim($title . ' ' . $name);
} elseif (!empty($post_data['contact_name'])) {
    $firstPaxName = 'Mr ' . trim($post_data['contact_name']);
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

    /* Top Connected Flight Bar (Screenshots 1 & 4) */
    .akbar-flight-summary-bar {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0,32,90,0.03);
        margin-bottom: 20px;
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

    /* Addon Main Card (Screenshots 1, 3, 4) */
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

    /* Addon Navigation Tabs (Screenshots 1, 3, 4) */
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
    .akbar-tab-btn img, .akbar-tab-btn .tab-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    /* Sector & Passenger Selectors (Screenshots 1, 3, 4) */
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

    .passenger-selector-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid #38bdf8;
        background: #ffffff;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 13.5px;
        font-weight: 700;
        color: #0284c7;
        box-shadow: 0 1px 4px rgba(2, 132, 199, 0.08);
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
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px;
    }

    /* Seat Selection Fuselage Styles (Screenshot 4) */
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
        max-width: 360px;
        margin: 0 auto;
        border: 1.5px solid #cbd5e1;
        border-radius: 140px 140px 24px 24px;
        background: #ffffff;
        padding: 24px 16px 30px;
        position: relative;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .plane-nose-front {
        text-align: center;
        margin-bottom: 22px;
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
        top: 155px;
        font-size: 11px;
        font-weight: 800;
        color: #f97316;
        transform: rotate(-90deg);
    }
    .exit-marker-right {
        position: absolute;
        right: -18px;
        top: 155px;
        font-size: 11px;
        font-weight: 800;
        color: #f97316;
        transform: rotate(90deg);
    }
    .seat-grid-columns {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 10px;
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
    }
    .seat-col-header {
        width: 30px;
        text-align: center;
    }
    .seat-aisle-header {
        width: 28px;
    }
    .plane-seat-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .plane-seat-num {
        width: 16px;
        text-align: center;
        font-size: 11px;
        font-weight: 800;
        color: #0f172a;
    }
    .fuselage-seat {
        width: 30px;
        height: 30px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10.5px;
        font-weight: 700;
        transition: all 0.15s ease;
        user-select: none;
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
    }
    .fuselage-seat.booked::after {
        content: '\2573';
        font-size: 11px;
        color: #94a3b8;
    }
    .fuselage-seat.selected {
        background: #16a34a !important;
        border: 1.5px solid #15803d !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.4);
    }

    /* Bottom Action Bar (Screenshots 1, 3, 4) */
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

        <!-- Top Flight Summary Bar (Screenshots 1 & 4 Match) -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 8px;">
            <a href="javascript:history.back();" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Back to Flight details
            </a>
        </div>

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

        <!-- Main 2-Column Grid -->
        <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px;">

            <!-- Left Column: Addon Services (Screenshots 1, 3, 4) -->
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

                        <!-- Baggage Tab (Screenshot 3) -->
                        <button type="button" class="akbar-tab-btn" id="tabBtn_baggage" onclick="switchAddonTab('baggage', this)">
                            <span style="font-size: 18px;">🧳</span>
                            <span>Baggage</span>
                        </button>

                        <!-- Seat Selection Tab (Screenshot 4) -->
                        <button type="button" class="akbar-tab-btn" id="tabBtn_seats" onclick="switchAddonTab('seats', this)">
                            <span style="font-size: 18px;">💺</span>
                            <span>Seat Selection</span>
                        </button>
                    </div>

                    <!-- Sector & Passenger Selectors (Screenshots 1, 3, 4) -->
                    <div class="sector-selector-strip">
                        <div class="sector-btn-group">
                            <div class="sector-btn active" id="sectorBtn_1" onclick="switchSector('onward', this)">
                                <span><?php echo htmlspecialchars($origin); ?> <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i> <?php echo htmlspecialchars($destination); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo date('D, d M y', strtotime($departure_date)); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo $stops > 0 ? $stops . ' Stop' : 'Non Stop'; ?></span>
                            </div>

                            <?php if ($is_roundtrip): ?>
                            <div class="sector-btn inactive" id="sectorBtn_2" onclick="switchSector('return', this)">
                                <span><?php echo htmlspecialchars($return_origin); ?> <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i> <?php echo htmlspecialchars($return_destination); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo date('D, d M y', strtotime($return_departure_date)); ?></span>
                                <span style="color: #cbd5e1;">|</span>
                                <span><?php echo $return_stops > 0 ? $return_stops . ' Stop' : 'Non Stop'; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Passenger Pill -->
                        <div>
                            <div class="passenger-selector-pill">
                                <i class="fa-solid fa-user" style="color: #0284c7; font-size: 14px;"></i>
                                <span><?php echo htmlspecialchars($firstPaxName); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB CONTENT 1: MEALS (Screenshot 1 Exact Match) -->
                    <div id="tabContent_meals" class="addon-tab-content" style="display: block;">
                        <div class="addon-items-grid">
                            
                            <!-- Meal 1: Amul Kool Cafe (Cold Coffee) -->
                            <div class="akbar-service-card meal-item-card" onclick="selectMealItem('MEAL_AKC', 'Amul Kool Cafe (Cold Coffee)', 100, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #fb923c, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234,88,12,0.25);">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"></path>
                                            <path d="M15 2v10"></path>
                                            <path d="M12 2v6"></path>
                                            <path d="M18 10v12"></path>
                                            <path d="M6 2v20"></path>
                                            <path d="M6 2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 18px; font-weight: 800; color: #0f172a;">₹ 100</div>
                                    <div style="font-size: 12.5px; color: #475569; font-weight: 600; margin-top: 4px; line-height: 1.35;">Amul Kool Cafe (Cold Coffee)</div>
                                </div>
                            </div>

                            <!-- Meal 2: Rawcha Basil Shikanji -->
                            <div class="akbar-service-card meal-item-card" onclick="selectMealItem('MEAL_RBS', 'Rawcha Basil Shikanji', 100, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #fb923c, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234,88,12,0.25);">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"></path>
                                            <path d="M15 2v10"></path>
                                            <path d="M12 2v6"></path>
                                            <path d="M18 10v12"></path>
                                            <path d="M6 2v20"></path>
                                            <path d="M6 2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 18px; font-weight: 800; color: #0f172a;">₹ 100</div>
                                    <div style="font-size: 12.5px; color: #475569; font-weight: 600; margin-top: 4px; line-height: 1.35;">Rawcha Basil Shikanji</div>
                                </div>
                            </div>

                            <!-- Meal 3: Black Coffee -->
                            <div class="akbar-service-card meal-item-card" onclick="selectMealItem('MEAL_BCF', 'Black Coffee', 100, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #fb923c, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234,88,12,0.25);">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"></path>
                                            <path d="M15 2v10"></path>
                                            <path d="M12 2v6"></path>
                                            <path d="M18 10v12"></path>
                                            <path d="M6 2v20"></path>
                                            <path d="M6 2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 18px; font-weight: 800; color: #0f172a;">₹ 100</div>
                                    <div style="font-size: 12.5px; color: #475569; font-weight: 600; margin-top: 4px; line-height: 1.35;">Black Coffee</div>
                                </div>
                            </div>

                            <!-- Meal 4: Coconut Water -->
                            <div class="akbar-service-card meal-item-card" onclick="selectMealItem('MEAL_CWT', 'Coconut Water', 100, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #fb923c, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(234,88,12,0.25);">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"></path>
                                            <path d="M15 2v10"></path>
                                            <path d="M12 2v6"></path>
                                            <path d="M18 10v12"></path>
                                            <path d="M6 2v20"></path>
                                            <path d="M6 2a4 4 0 0 1 4 4v4a4 4 0 0 1-4 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 18px; font-weight: 800; color: #0f172a;">₹ 100</div>
                                    <div style="font-size: 12.5px; color: #475569; font-weight: 600; margin-top: 4px; line-height: 1.35;">Coconut Water</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- TAB CONTENT 2: BAGGAGE (Screenshot 3 Exact Match) -->
                    <div id="tabContent_baggage" class="addon-tab-content" style="display: none;">
                        <div class="addon-items-grid">
                            
                            <!-- 3 Kgs - Cyan Suitcase -->
                            <div class="akbar-service-card baggage-item-card" onclick="selectBaggageItem('XBPE', '3 Kgs', 2100, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <!-- Cyan 3D Rolling Suitcase -->
                                    <svg width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="cyanBody" x1="0" y1="20" x2="80" y2="105" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#38bdf8"/>
                                                <stop offset="50%" stop-color="#0284c7"/>
                                                <stop offset="100%" stop-color="#0369a1"/>
                                            </linearGradient>
                                        </defs>
                                        <!-- Handle -->
                                        <rect x="36" y="2" width="28" height="5" rx="2.5" fill="#475569"/>
                                        <rect x="41" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <rect x="56" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <!-- Suitcase Body -->
                                        <rect x="18" y="24" width="64" height="82" rx="12" fill="url(#cyanBody)"/>
                                        <!-- Front Ridges -->
                                        <line x1="32" y1="36" x2="32" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="44" y1="36" x2="44" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="56" y1="36" x2="56" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="68" y1="36" x2="68" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <!-- Carry Handle -->
                                        <rect x="40" y="21" width="20" height="5" rx="2" fill="#1e293b"/>
                                        <!-- Wheels -->
                                        <circle cx="28" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="28" cy="110" r="2" fill="#94a3b8"/>
                                        <circle cx="72" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="72" cy="110" r="2" fill="#94a3b8"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 19px; font-weight: 800; color: #0f172a;">₹2100</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Prepaid excess baggage</div>
                                    <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">3 Kgs</div>
                                </div>
                            </div>

                            <!-- 5 kgs - Red Suitcase -->
                            <div class="akbar-service-card baggage-item-card" onclick="selectBaggageItem('XBPA', '5 kgs', 3750, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <!-- Red 3D Rolling Suitcase -->
                                    <svg width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="redBody" x1="0" y1="20" x2="80" y2="105" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#f87171"/>
                                                <stop offset="50%" stop-color="#ef4444"/>
                                                <stop offset="100%" stop-color="#b91c1c"/>
                                            </linearGradient>
                                        </defs>
                                        <!-- Handle -->
                                        <rect x="36" y="2" width="28" height="5" rx="2.5" fill="#475569"/>
                                        <rect x="41" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <rect x="56" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <!-- Suitcase Body -->
                                        <rect x="18" y="24" width="64" height="82" rx="12" fill="url(#redBody)"/>
                                        <!-- Front Ridges -->
                                        <line x1="32" y1="36" x2="32" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="44" y1="36" x2="44" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="56" y1="36" x2="56" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="68" y1="36" x2="68" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <!-- Carry Handle -->
                                        <rect x="40" y="21" width="20" height="5" rx="2" fill="#1e293b"/>
                                        <!-- Wheels -->
                                        <circle cx="28" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="28" cy="110" r="2" fill="#94a3b8"/>
                                        <circle cx="72" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="72" cy="110" r="2" fill="#94a3b8"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 19px; font-weight: 800; color: #0f172a;">₹3750</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Prepaid excess baggage</div>
                                    <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">5 kgs</div>
                                </div>
                            </div>

                            <!-- 10 kgs - Orange Suitcase -->
                            <div class="akbar-service-card baggage-item-card" onclick="selectBaggageItem('XBPB', '10 kgs', 7250, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <!-- Orange 3D Rolling Suitcase -->
                                    <svg width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="orangeBody" x1="0" y1="20" x2="80" y2="105" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#fb923c"/>
                                                <stop offset="50%" stop-color="#f97316"/>
                                                <stop offset="100%" stop-color="#c2410c"/>
                                            </linearGradient>
                                        </defs>
                                        <!-- Handle -->
                                        <rect x="36" y="2" width="28" height="5" rx="2.5" fill="#475569"/>
                                        <rect x="41" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <rect x="56" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <!-- Suitcase Body -->
                                        <rect x="18" y="24" width="64" height="82" rx="12" fill="url(#orangeBody)"/>
                                        <!-- Front Ridges -->
                                        <line x1="32" y1="36" x2="32" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="44" y1="36" x2="44" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="56" y1="36" x2="56" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="68" y1="36" x2="68" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <!-- Carry Handle -->
                                        <rect x="40" y="21" width="20" height="5" rx="2" fill="#1e293b"/>
                                        <!-- Wheels -->
                                        <circle cx="28" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="28" cy="110" r="2" fill="#94a3b8"/>
                                        <circle cx="72" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="72" cy="110" r="2" fill="#94a3b8"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 19px; font-weight: 800; color: #0f172a;">₹7250</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Prepaid excess baggage</div>
                                    <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">10 kgs</div>
                                </div>
                            </div>

                            <!-- 15 kgs - Pink Suitcase -->
                            <div class="akbar-service-card baggage-item-card" onclick="selectBaggageItem('XBPC', '15 kgs', 10500, this)">
                                <div class="card-radio-circle"></div>
                                <div class="card-img-wrapper">
                                    <!-- Pink 3D Rolling Suitcase -->
                                    <svg width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="pinkBody" x1="0" y1="20" x2="80" y2="105" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#f472b6"/>
                                                <stop offset="50%" stop-color="#ec4899"/>
                                                <stop offset="100%" stop-color="#be185d"/>
                                            </linearGradient>
                                        </defs>
                                        <!-- Handle -->
                                        <rect x="36" y="2" width="28" height="5" rx="2.5" fill="#475569"/>
                                        <rect x="41" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <rect x="56" y="7" width="3" height="18" fill="#94a3b8"/>
                                        <!-- Suitcase Body -->
                                        <rect x="18" y="24" width="64" height="82" rx="12" fill="url(#pinkBody)"/>
                                        <!-- Front Ridges -->
                                        <line x1="32" y1="36" x2="32" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="44" y1="36" x2="44" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="56" y1="36" x2="56" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <line x1="68" y1="36" x2="68" y2="94" stroke="#ffffff" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                                        <!-- Carry Handle -->
                                        <rect x="40" y="21" width="20" height="5" rx="2" fill="#1e293b"/>
                                        <!-- Wheels -->
                                        <circle cx="28" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="28" cy="110" r="2" fill="#94a3b8"/>
                                        <circle cx="72" cy="110" r="4.5" fill="#1e293b"/>
                                        <circle cx="72" cy="110" r="2" fill="#94a3b8"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: 19px; font-weight: 800; color: #0f172a;">₹10500</div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Prepaid excess baggage</div>
                                    <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">15 kgs</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- TAB CONTENT 3: SEAT SELECTION (Screenshot 4 Exact Match) -->
                    <div id="tabContent_seats" class="addon-tab-content" style="display: none;">
                        <div style="margin-top: 14px; margin-bottom: 8px;">
                            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">
                                <?php echo htmlspecialchars($originCity); ?> &rarr; <?php echo htmlspecialchars($destCity); ?>
                            </h3>
                            <div style="font-size: 13px; color: #475569; font-weight: 600; margin-top: 3px;">
                                <?php echo date('l, d M y', strtotime($departure_date)); ?>
                            </div>
                        </div>

                        <div class="seat-cabin-container">
                            
                            <!-- Left: Seat Legend (Screenshot 4) -->
                            <div class="seat-legend-box">
                                <div class="legend-row">
                                    <span class="legend-swatch" style="border: 1.5px solid #cbd5e1; background: #ffffff; color: #94a3b8;">&#x2573;</span>
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
                                    <span>0 - 800</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #fde68a; border: 1px solid #fcd34d;"></span>
                                    <span>801 - 1600</span>
                                </div>
                                <div class="legend-row">
                                    <span class="legend-swatch" style="background: #fdba74; border: 1px solid #fb923c;"></span>
                                    <span>1601 & above</span>
                                </div>
                            </div>

                            <!-- Center: Airplane Fuselage (Screenshot 4) -->
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

                                <!-- Rows 1 to 9 (Screenshot 4 Exact Match) -->
                                <?php
                                $seatMapData = array(
                                    1 => array('tier' => 'tier-orange', 'price' => 1650, 'booked' => array()),
                                    2 => array('tier' => 'tier-blue', 'price' => 350, 'booked' => array('A', 'E', 'F')),
                                    3 => array('tier' => 'tier-blue', 'price' => 350, 'booked' => array('A')),
                                    4 => array('tier' => 'tier-blue', 'price' => 350, 'booked' => array()),
                                    5 => array('tier' => 'tier-blue', 'price' => 350, 'booked' => array('F')),
                                    6 => array('tier' => 'tier-blue', 'price' => 250, 'booked' => array()),
                                    7 => array('tier' => 'tier-blue', 'price' => 250, 'booked' => array()),
                                    8 => array('tier' => 'tier-blue', 'price' => 250, 'booked' => array()),
                                    9 => array('tier' => 'tier-blue', 'price' => 250, 'booked' => array())
                                );

                                for ($r = 1; $r <= 9; $r++):
                                    $rowInfo = $seatMapData[$r];
                                ?>
                                <div class="plane-seat-row">
                                    <div class="plane-seat-num"><?php echo $r; ?></div>
                                    
                                    <!-- A, B, C -->
                                    <?php foreach (array('A', 'B', 'C') as $col): 
                                        $seatCode = $r . $col;
                                        $isBooked = in_array($col, $rowInfo['booked']);
                                    ?>
                                        <div class="fuselage-seat <?php echo $isBooked ? 'booked' : $rowInfo['tier']; ?>"
                                             data-seat="<?php echo $seatCode; ?>"
                                             data-price="<?php echo $rowInfo['price']; ?>"
                                             onclick="<?php echo $isBooked ? '' : "selectSeatItem('{$seatCode}', {$rowInfo['price']}, this)"; ?>"
                                             title="Seat <?php echo $seatCode; ?> (₹<?php echo $rowInfo['price']; ?>)">
                                        </div>
                                    <?php endforeach; ?>

                                    <!-- Aisle -->
                                    <div class="seat-aisle-header"></div>

                                    <!-- D, E, F -->
                                    <?php foreach (array('D', 'E', 'F') as $col): 
                                        $seatCode = $r . $col;
                                        $isBooked = in_array($col, $rowInfo['booked']);
                                    ?>
                                        <div class="fuselage-seat <?php echo $isBooked ? 'booked' : $rowInfo['tier']; ?>"
                                             data-seat="<?php echo $seatCode; ?>"
                                             data-price="<?php echo $rowInfo['price']; ?>"
                                             onclick="<?php echo $isBooked ? '' : "selectSeatItem('{$seatCode}', {$rowInfo['price']}, this)"; ?>"
                                             title="Seat <?php echo $seatCode; ?> (₹<?php echo $rowInfo['price']; ?>)">
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="plane-seat-num"><?php echo $r; ?></div>
                                </div>
                                <?php endfor; ?>

                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Bar (Screenshots 1, 3, 4) -->
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

            <!-- Right Column: Sticky Fare Details & Promo Code Sidebar (Screenshots 1 & 4) -->
            <div>
                <div style="position: sticky; top: 90px; display: flex; flex-direction: column; gap: 18px;">
                    
                    <!-- 1. Fare Details Card (Screenshots 1 & 4) -->
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

                    <!-- 2. Promo Code Card (Screenshots 1 & 4 Exact Match) -->
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

                            <!-- Offer 5: ATFLY (Save 47 - Default Checked) -->
                            <div class="promo-item active-promo" onclick="applyPromoOption('ATFLY', 47, this)" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1.5px solid #22c55e; background: #f0fdf4; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                                <input type="radio" name="promo_radio" id="p_ATFLY" checked style="margin-top: 3px; accent-color: #ef4444;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 12.5px; color: #0f172a;">ATFLY</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a;">Save 47</span>
                                    </div>
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">Flat ₹ 47 Instant Web Discount applied automatically!</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 3. Trust Badges (Screenshot 3 Match) -->
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

<!-- Hidden POST form to process payment upon button click -->
<form id="finalPaymentForm" action="<?php echo site_url('flight/process_payment'); ?>" method="POST" style="display: none;">
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

    <input type="hidden" name="selected_baggage_code" id="form_baggage_code" value="">
    <input type="hidden" name="selected_baggage_desc" id="form_baggage_desc" value="">
    <input type="hidden" name="selected_baggage_amount" id="form_baggage_amount" value="0">

    <input type="hidden" name="selected_meal_code" id="form_meal_code" value="">
    <input type="hidden" name="selected_meal_desc" id="form_meal_desc" value="">
    <input type="hidden" name="selected_meal_amount" id="form_meal_amount" value="0">

    <input type="hidden" name="selected_seat_code" id="form_seat_code" value="">
    <input type="hidden" name="selected_seat_amount" id="form_seat_amount" value="0">

    <input type="hidden" name="total_amount" id="form_final_grand_total" value="<?php echo $initialGrandTotal; ?>">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var baseFlightAmount = <?php echo (float)($base_fare + $taxes + $insurance_amount + $safety_cancellation_amount + $fare_tier_price_delta); ?>;
var appliedDiscount = <?php echo (float)$discount_amount; ?>;
var appliedPromoCode = "<?php echo htmlspecialchars($promo_code); ?>";

var selectedBaggagePrice = 0;
var selectedMealPrice = 0;
var selectedSeatPrice = 0;

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

function switchSector(sectorType, btnEl) {
    document.querySelectorAll('.sector-btn').forEach(function(btn) {
        btn.classList.remove('active');
        btn.classList.add('inactive');
    });
    if (btnEl) {
        btnEl.classList.remove('inactive');
        btnEl.classList.add('active');
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

function selectMealItem(code, name, price, cardEl) {
    var isAlready = cardEl.classList.contains('selected');

    document.querySelectorAll('.meal-item-card').forEach(function(c) {
        c.classList.remove('selected');
    });

    if (isAlready) {
        selectedMealPrice = 0;
        document.getElementById('form_meal_code').value = '';
        document.getElementById('form_meal_desc').value = '';
        document.getElementById('form_meal_amount').value = 0;
    } else {
        cardEl.classList.add('selected');
        selectedMealPrice = parseFloat(price);
        document.getElementById('form_meal_code').value = code;
        document.getElementById('form_meal_desc').value = name;
        document.getElementById('form_meal_amount').value = selectedMealPrice;
    }
    recalcTotal();
}

function selectBaggageItem(code, weight, price, cardEl) {
    var isAlready = cardEl.classList.contains('selected');

    document.querySelectorAll('.baggage-item-card').forEach(function(c) {
        c.classList.remove('selected');
    });

    if (isAlready) {
        selectedBaggagePrice = 0;
        document.getElementById('form_baggage_code').value = '';
        document.getElementById('form_baggage_desc').value = '';
        document.getElementById('form_baggage_amount').value = 0;
    } else {
        cardEl.classList.add('selected');
        selectedBaggagePrice = parseFloat(price);
        document.getElementById('form_baggage_code').value = code;
        document.getElementById('form_baggage_desc').value = 'Prepaid Excess Baggage - ' + weight;
        document.getElementById('form_baggage_amount').value = selectedBaggagePrice;
    }
    recalcTotal();
}

function selectSeatItem(seatCode, price, seatEl) {
    var isAlready = seatEl.classList.contains('selected');

    document.querySelectorAll('.fuselage-seat').forEach(function(s) {
        s.classList.remove('selected');
    });

    if (isAlready) {
        selectedSeatPrice = 0;
        document.getElementById('form_seat_code').value = '';
        document.getElementById('form_seat_amount').value = 0;
    } else {
        seatEl.classList.add('selected');
        selectedSeatPrice = parseFloat(price);
        document.getElementById('form_seat_code').value = seatCode;
        document.getElementById('form_seat_amount').value = selectedSeatPrice;
    }
    recalcTotal();
}

function recalcTotal() {
    var addonTotal = selectedBaggagePrice + selectedMealPrice + selectedSeatPrice;
    document.getElementById('bottomAddonTotal').textContent = addonTotal.toLocaleString('en-IN');

    var addonsRow = document.getElementById('summaryAddonsRow');
    if (addonTotal > 0) {
        addonsRow.style.display = 'block';
        document.getElementById('summaryAddonsAmount').textContent = addonTotal.toLocaleString('en-IN');
    } else {
        addonsRow.style.display = 'none';
    }

    var grandTotal = Math.max(0, (baseFlightAmount + addonTotal) - appliedDiscount);
    document.getElementById('summaryGrandTotal').textContent = grandTotal.toLocaleString('en-IN');
    document.getElementById('form_final_grand_total').value = grandTotal;
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

function skipAddonsAndProceed() {
    selectedBaggagePrice = 0;
    selectedMealPrice = 0;
    selectedSeatPrice = 0;

    document.getElementById('form_baggage_code').value = '';
    document.getElementById('form_baggage_desc').value = '';
    document.getElementById('form_baggage_amount').value = 0;

    document.getElementById('form_meal_code').value = '';
    document.getElementById('form_meal_desc').value = '';
    document.getElementById('form_meal_amount').value = 0;

    document.getElementById('form_seat_code').value = '';
    document.getElementById('form_seat_amount').value = 0;

    recalcTotal();
    triggerFinalPayment();
}

function continueToPayment() {
    triggerFinalPayment();
}

function triggerFinalPayment() {
    var finalAmount = parseFloat(document.getElementById('form_final_grand_total').value);
    var amountInPaise = Math.round(finalAmount * 100);
    var contactName = "<?php echo htmlspecialchars($post_data['contact_name'] ?? 'Voyogo Traveller'); ?>";
    var contactEmail = "<?php echo htmlspecialchars($post_data['contact_email'] ?? 'booking@voyogo.com'); ?>";
    var contactPhone = "<?php echo htmlspecialchars($post_data['contact_phone'] ?? '9876543210'); ?>";

    var options = {
        "key": "<?php echo !empty($razorpay_settings['razorpay_key_id']) ? htmlspecialchars($razorpay_settings['razorpay_key_id']) : 'rzp_test_TTVGSNKy0V1o7B'; ?>",
        "amount": amountInPaise,
        "currency": "INR",
        "name": "Voyogo Travels",
        "description": "Flight Ticket Booking - <?php echo htmlspecialchars($flight_number); ?>",
        "image": "<?php echo base_url('assets/images/logo.png'); ?>",
        "handler": function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('finalPaymentForm').submit();
        },
        "prefill": {
            "name": contactName,
            "email": contactEmail,
            "contact": contactPhone
        },
        "theme": {
            "color": "#0d3470"
        },
        "modal": {
            "ondismiss": function() {
                if (confirm("Razorpay Payment Gateway Closed. Complete booking in test mode?")) {
                    document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
                    document.getElementById('finalPaymentForm').submit();
                }
            }
        }
    };

    try {
        var rzp = new Razorpay(options);
        rzp.open();
    } catch(e) {
        document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
        document.getElementById('finalPaymentForm').submit();
    }
}
</script>
