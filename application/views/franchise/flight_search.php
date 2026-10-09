<?php
$origCode   = $search_query['from_code'] ?? ($search_query['origin'] ?? 'DEL');
$destCode   = $search_query['to_code'] ?? ($search_query['destination'] ?? 'BOM');
$fromCity   = $search_query['from'] ?? ($search_query['origin'] ?? 'Delhi');
$toCity     = $search_query['to'] ?? ($search_query['destination'] ?? 'Mumbai');
$departDate = $search_query['depart_date'] ?? ($search_query['date'] ?? date('Y-m-d', strtotime('+3 days')));
$returnDate = $search_query['return_date'] ?? date('Y-m-d', strtotime('+7 days'));
$tripType   = $search_query['trip_type'] ?? 'oneway';
$is_roundtrip = !empty($is_roundtrip);
$paxCount   = ((int)($search_query['adults'] ?? 1)) + ((int)($search_query['children'] ?? 0)) + ((int)($search_query['infants'] ?? 0));
$cabinClass = $search_query['cabin_class'] ?? ($search_query['cabin'] ?? 'Economy');
$onwardList = !empty($onwardFlights) ? $onwardFlights : (!empty($flights) ? $flights : array());
$returnList = !empty($returnFlights) ? $returnFlights : array();
$activeTui  = $search_tui ?? ($search_query['tui'] ?? '');

// Calculate stats for Akbar Travels style sidebar filters (Parity with customer flight results)
$pricesAll = array();
$nonStopCount = 0; $nonStopMin = PHP_INT_MAX;
$oneStopCount = 0; $oneStopMin = PHP_INT_MAX;
$twoPlusCount = 0; $twoPlusMin = PHP_INT_MAX;
$airlineData = array();
$connectingAirports = array();
$refundableCount = 0;

foreach ($onwardList as $flt) {
    $p = (float)($flt['price'] ?? 4999);
    $pricesAll[] = $p;
    $s = (int)($flt['stops'] ?? 0);
    $code = strtoupper($flt['airline_code'] ?? '6E');
    $name = $flt['airline_name'] ?? ($flt['airline'] ?? 'IndiGo');
    $logo = $flt['airline_logo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';

    if ($s === 0) { 
        $nonStopCount++; 
        $nonStopMin = min($nonStopMin, $p); 
    } elseif ($s === 1) { 
        $oneStopCount++; 
        $oneStopMin = min($oneStopMin, $p); 
    } else { 
        $twoPlusCount++; 
        $twoPlusMin = min($twoPlusMin, $p); 
    }

    if (!isset($airlineData[$code])) {
        $airlineData[$code] = array('name' => $name, 'logo' => $logo, 'count' => 0, 'min' => PHP_INT_MAX);
    }
    $airlineData[$code]['count']++;
    $airlineData[$code]['min'] = min($airlineData[$code]['min'], $p);

    if (!empty($flt['refundable'])) $refundableCount++;

    $via = !empty($flt['via']) ? strtoupper($flt['via']) : (!empty($flt['Via']) ? strtoupper($flt['Via']) : '');
    if ($s > 0 && !empty($via)) {
        $connectingAirports[$via] = ($connectingAirports[$via] ?? 0) + 1;
    }
}

$minPrice = !empty($pricesAll) ? min($pricesAll) : 3500;
$maxPrice = !empty($pricesAll) ? max($pricesAll) : 12000;
if ($nonStopMin === PHP_INT_MAX) $nonStopMin = 0;
if ($oneStopMin === PHP_INT_MAX) $oneStopMin = 0;
if ($twoPlusMin === PHP_INT_MAX) $twoPlusMin = 0;
$onePlusMin = $twoPlusMin > 0 ? $twoPlusMin : ($oneStopMin > 0 ? $oneStopMin : 0);
$onePlusCount = $twoPlusCount > 0 ? $twoPlusCount : $oneStopCount;

// Prepare Connecting Airports dictionary
$defaultHubs = array(
    'AMD' => 'Ahmedabad',
    'ATQ' => 'Amritsar',
    'BLR' => 'Bangalore',
    'BHO' => 'Bhopal',
    'BBI' => 'Bhubaneswar',
    'HYD' => 'Hyderabad',
    'GOX' => 'Goa (Mopa)',
    'JAI' => 'Jaipur',
    'LKO' => 'Lucknow',
    'CCU' => 'Kolkata',
    'MAA' => 'Chennai',
    'PNQ' => 'Pune',
    'COK' => 'Kochi',
    'PAT' => 'Patna',
    'NAG' => 'Nagpur',
    'IDR' => 'Indore',
    'SXR' => 'Srinagar'
);

$connectingAirportsList = array();
foreach ($connectingAirports as $cCode => $cCnt) {
    $cName = $defaultHubs[$cCode] ?? $cCode;
    $connectingAirportsList[$cCode] = array('name' => $cName, 'count' => $cCnt);
}
if (count($connectingAirportsList) < 5) {
    foreach ($defaultHubs as $cCode => $cName) {
        if ($cCode !== $origCode && $cCode !== $destCode && !isset($connectingAirportsList[$cCode])) {
            $connectingAirportsList[$cCode] = array('name' => $cName, 'count' => 0);
        }
        if (count($connectingAirportsList) >= 8) break;
    }
}
uasort($connectingAirportsList, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});
?>

<div style="margin-bottom: 24px;">
    <!-- Top Search Header Box (Parity with customer flight results) -->
    <div style="background: linear-gradient(135deg, #09204b 0%, #0d3470 100%); border-radius: 14px; padding: 18px 24px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 16px rgba(9, 32, 75, 0.15); flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <!-- From -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">From</span>
                <strong style="font-size: 19px; font-weight: 800; letter-spacing: -0.3px;"><?php echo htmlspecialchars($fromCity); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo htmlspecialchars($origCode); ?>, Airport</span>
            </div>

            <div style="color: #78B722; font-size: 18px;">
                <i class="fa-solid fa-<?php echo $is_roundtrip ? 'arrow-right-arrow-left' : 'plane'; ?>"></i>
            </div>

            <!-- To -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">To</span>
                <strong style="font-size: 19px; font-weight: 800; letter-spacing: -0.3px;"><?php echo htmlspecialchars($toCity); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo htmlspecialchars($destCode); ?>, Airport</span>
            </div>

            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.2);"></div>

            <!-- Departure Date -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">Departure</span>
                <strong style="font-size: 16px; font-weight: 700;"><?php echo date('d M\'y', strtotime($departDate)); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo date('l', strtotime($departDate)); ?></span>
            </div>

            <!-- Return Date if Round Trip -->
            <?php if ($is_roundtrip): ?>
            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.2);"></div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">Return</span>
                <strong style="font-size: 16px; font-weight: 700; color: #78B722;"><?php echo date('d M\'y', strtotime($returnDate)); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo date('l', strtotime($returnDate)); ?></span>
            </div>
            <?php endif; ?>

            <div style="height: 32px; width: 1px; background: rgba(255,255,255,0.2);"></div>

            <!-- Pax & Class -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">Travelers & Class</span>
                <strong style="font-size: 16px; font-weight: 700;"><?php echo $paxCount; ?> Traveler<?php echo $paxCount > 1 ? 's' : ''; ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo htmlspecialchars($cabinClass); ?></span>
            </div>
        </div>

        <div>
            <button type="button" id="openModifySearchBtn" class="btn-modify-search">
                <i class="fa-solid fa-pen-to-square"></i> MODIFY SEARCH
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODIFY SEARCH POPUP MODAL (Parity with Customer voyogos.com/flight)      -->
<!-- ========================================================================= -->
<div class="modify-search-overlay" id="modifySearchOverlay">
    <div class="modify-search-modal" id="modifySearchModal">
        <button type="button" class="ms-close-btn" id="closeModifySearchBtn" title="Close"><i class="fa-solid fa-xmark"></i></button>
        
        <!-- Top Bar: Trip Type Pills & Title -->
        <div class="ms-top-bar">
            <div class="ms-trip-tabs">
                <label class="ms-trip-tab <?php echo empty($is_roundtrip) ? 'active' : ''; ?>" id="tabOneway">
                    <input type="radio" name="ms_trip_type" value="oneway" <?php echo empty($is_roundtrip) ? 'checked' : ''; ?>>
                    <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                    <span>One Way</span>
                </label>
                <label class="ms-trip-tab <?php echo !empty($is_roundtrip) ? 'active' : ''; ?>" id="tabRoundtrip">
                    <input type="radio" name="ms_trip_type" value="roundtrip" <?php echo !empty($is_roundtrip) ? 'checked' : ''; ?>>
                    <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                    <span>Round Trip</span>
                </label>
                <label class="ms-trip-tab" id="tabMulticity">
                    <input type="radio" name="ms_trip_type" value="multicity">
                    <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                    <span>Multi City</span>
                </label>
            </div>

            <div class="ms-header-title">
                <i class="fa-solid fa-plane" style="transform: rotate(-35deg); font-size: 20px;"></i>
                <span>Book Flight Tickets</span>
            </div>
        </div>

        <!-- Inner White Card -->
        <div class="ms-card-inner">
            <form action="<?php echo site_url('franchise/flight_search'); ?>" method="GET" id="modifySearchForm">
                <input type="hidden" name="trip_type" id="ms_tripType" value="<?php echo !empty($is_roundtrip) ? 'roundtrip' : 'oneway'; ?>">
                <input type="hidden" name="from_code" id="ms_from_code" value="<?php echo htmlspecialchars($origCode); ?>">
                <input type="hidden" name="to_code" id="ms_to_code" value="<?php echo htmlspecialchars($destCode); ?>">
                <input type="hidden" name="origin" id="ms_origin" value="<?php echo htmlspecialchars($origCode); ?>">
                <input type="hidden" name="destination" id="ms_destination" value="<?php echo htmlspecialchars($destCode); ?>">

                <!-- 1. Standard Search Grid (One Way & Round Trip) -->
                <div class="ms-form-grid" id="msStandardGrid">
                    <!-- From -->
                    <div class="ms-field ms-field-from" id="msFromBox">
                        <div class="ms-field-label">From</div>
                        <input type="text" name="from_city" id="ms_from_city" class="ms-city-input" value="<?php echo htmlspecialchars($fromCity); ?>" placeholder="City or Airport" autocomplete="off">
                        <div class="ms-field-sub" id="ms_from_sub"><?php echo htmlspecialchars($origCode); ?>, Airport</div>
                        <!-- Dropdown Suggestion Box -->
                        <div class="ms-airport-suggest" id="msFromSuggest"></div>
                    </div>

                    <!-- Swap Button -->
                    <button type="button" class="ms-swap-btn" id="msSwapBtn" title="Swap Cities">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </button>

                    <!-- To -->
                    <div class="ms-field ms-field-to" id="msToBox">
                        <div class="ms-field-label">To</div>
                        <input type="text" name="to_city" id="ms_to_city" class="ms-city-input" value="<?php echo htmlspecialchars($toCity); ?>" placeholder="City or Airport" autocomplete="off">
                        <div class="ms-field-sub" id="ms_to_sub"><?php echo htmlspecialchars($destCode); ?>, Airport</div>
                        <!-- Dropdown Suggestion Box -->
                        <div class="ms-airport-suggest" id="msToSuggest"></div>
                    </div>

                    <!-- Departure -->
                    <div class="ms-field ms-field-date" id="msDepartureBox">
                        <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron" id="msDepChevron"></i></div>
                        <div class="ms-date-value" id="ms_dep_disp"><?php echo date('d M\'y', strtotime($departDate)); ?></div>
                        <div class="ms-field-sub" id="ms_dep_sub"><?php echo date('l', strtotime($departDate)); ?></div>
                        <input type="hidden" name="depart_date" id="ms_departure_date" value="<?php echo htmlspecialchars($departDate); ?>">
                    </div>

                    <!-- Return -->
                    <div class="ms-field ms-field-date ms-return-field" id="msReturnField" style="<?php echo empty($is_roundtrip) ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'; ?>">
                        <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Return <i class="fa-solid fa-chevron-down ms-chevron" id="msRetChevron"></i> <i class="fa-solid fa-circle-xmark ms-clear-return" id="msClearReturn" title="Clear return date" style="display: <?php echo !empty($is_roundtrip) ? 'inline-block' : 'none'; ?>; margin-left: 4px;"></i></div>
                        <div class="ms-date-value" id="ms_ret_disp"><?php echo !empty($is_roundtrip) ? date('d M\'y', strtotime($returnDate)) : '-- --\'--'; ?></div>
                        <div class="ms-field-sub" id="ms_ret_sub"><?php echo !empty($is_roundtrip) ? date('l', strtotime($returnDate)) : 'Select round trip'; ?></div>
                        <input type="hidden" name="return_date" id="ms_return_date" value="<?php echo htmlspecialchars($returnDate); ?>" <?php echo empty($is_roundtrip) ? 'disabled' : ''; ?>>
                    </div>

                    <!-- Travellers & Class -->
                    <div class="ms-field ms-field-pax ms-traveller-field" id="msTravellerBox">
                        <div class="ms-field-label">Travellers & Class</div>
                        <div class="ms-traveller-display" id="msTravellerDisplay">
                            <?php echo sprintf('%02d', $paxCount); ?> Traveller<?php echo $paxCount > 1 ? 's' : ''; ?>
                        </div>
                        <div class="ms-field-sub" id="ms_cabin_sub"><?php echo htmlspecialchars($cabinClass); ?></div>
                    </div>

                    <!-- Search Button -->
                    <div class="ms-action-wrap">
                        <button type="submit" class="ms-search-btn"><span>SEARCH</span> <i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <!-- 2. Multi-City Grid (Multi City) -->
                <div class="ms-multicity-grid" id="msMulticityGrid">
                    <!-- Leg 1 -->
                    <div class="ms-multi-row" id="msLegRow1">
                        <div class="ms-field">
                            <div class="ms-field-label">From</div>
                            <input type="text" name="multi_from[]" id="msMultiFrom1" class="ms-city-input" value="<?php echo htmlspecialchars($fromCity); ?>" autocomplete="off">
                            <div class="ms-field-sub" id="msMultiFromSub1"><?php echo htmlspecialchars($origCode); ?>, Airport</div>
                        </div>
                        <div class="ms-field">
                            <div class="ms-field-label">To</div>
                            <input type="text" name="multi_to[]" id="msMultiTo1" class="ms-city-input" value="<?php echo htmlspecialchars($toCity); ?>" autocomplete="off">
                            <div class="ms-field-sub" id="msMultiToSub1"><?php echo htmlspecialchars($destCode); ?>, Airport</div>
                        </div>
                        <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="1" id="msMultiDepBox1">
                            <div class="ms-field-label">Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                            <div class="ms-date-value" id="msMultiDepDisp1"><?php echo date('d M\'y', strtotime($departDate)); ?></div>
                            <div class="ms-field-sub" id="msMultiDepSub1"><?php echo date('l', strtotime($departDate)); ?></div>
                            <input type="hidden" name="multi_date[]" id="msMultiDate1" value="<?php echo htmlspecialchars($departDate); ?>">
                        </div>
                        <div class="ms-field ms-field-pax ms-traveller-field" id="msMultiTravellerBox" style="cursor: pointer;">
                            <div class="ms-field-label">Travellers & Class</div>
                            <div class="ms-traveller-display" id="msMultiTravellerDisplay"><?php echo sprintf('%02d', $paxCount); ?> Traveller<?php echo $paxCount > 1 ? 's' : ''; ?></div>
                            <div class="ms-field-sub" id="msMultiCabinSub"><?php echo htmlspecialchars($cabinClass); ?></div>
                        </div>
                    </div>

                    <!-- Leg 2 -->
                    <div class="ms-multi-row" id="msLegRow2">
                        <div class="ms-field">
                            <div class="ms-field-label">From</div>
                            <input type="text" name="multi_from[]" id="msMultiFrom2" class="ms-city-input" value="<?php echo htmlspecialchars($toCity); ?>" autocomplete="off">
                            <div class="ms-field-sub" id="msMultiFromSub2"><?php echo htmlspecialchars($destCode); ?>, Airport</div>
                        </div>
                        <div class="ms-field">
                            <div class="ms-field-label">To</div>
                            <input type="text" name="multi_to[]" id="msMultiTo2" class="ms-city-input" placeholder="Select a City" value="" autocomplete="off">
                            <div class="ms-field-sub" id="msMultiToSub2" style="display: none;">Destination Airport</div>
                        </div>
                        <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="2" id="msMultiDepBox2">
                            <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                            <div class="ms-date-value" id="msMultiDepDisp2"><?php echo date('d M\'y', strtotime($departDate . ' +3 days')); ?></div>
                            <div class="ms-field-sub" id="msMultiDepSub2"><?php echo date('l', strtotime($departDate . ' +3 days')); ?></div>
                            <input type="hidden" name="multi_date[]" id="msMultiDate2" value="<?php echo date('Y-m-d', strtotime($departDate . ' +3 days')); ?>">
                        </div>
                        <div class="ms-multi-actions" id="msMultiActionsCell">
                            <button type="button" class="ms-btn-add-city" id="msAddCityBtn">+ ADD CITY</button>
                            <button type="submit" class="ms-search-btn"><span>SEARCH</span> <i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>

                    <!-- Dynamic Extra Legs Container -->
                    <div id="msExtraLegsContainer"></div>
                </div>

                <!-- Shared Hidden Traveller Inputs -->
                <input type="hidden" name="adults" id="ms_adults" value="<?php echo $search_query['adults'] ?? 1; ?>">
                <input type="hidden" name="children" id="ms_children" value="<?php echo $search_query['children'] ?? 0; ?>">
                <input type="hidden" name="infants" id="ms_infants" value="<?php echo $search_query['infants'] ?? 0; ?>">
                <input type="hidden" name="cabin" id="ms_cabin" value="<?php echo htmlspecialchars($cabinClass); ?>">
                <input type="hidden" name="cabin_class" id="ms_cabin_class" value="<?php echo htmlspecialchars($cabinClass); ?>">

                <!-- Shared Traveller Popup -->
                <div class="ms-traveller-popup" id="msTravellerPopup">
                    <div class="ms-pax-row">
                        <div><strong>Adults</strong><small>12+ years</small></div>
                        <div class="ms-pax-controls">
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('adult',-1);">−</button>
                            <span id="ms_adultCount"><?php echo $search_query['adults'] ?? 1; ?></span>
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('adult',1);">+</button>
                        </div>
                    </div>
                    <div class="ms-pax-row">
                        <div><strong>Children</strong><small>2-12 years</small></div>
                        <div class="ms-pax-controls">
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('child',-1);">−</button>
                            <span id="ms_childCount"><?php echo $search_query['children'] ?? 0; ?></span>
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('child',1);">+</button>
                        </div>
                    </div>
                    <div class="ms-pax-row">
                        <div><strong>Infants</strong><small>Below 2 years</small></div>
                        <div class="ms-pax-controls">
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('infant',-1);">−</button>
                            <span id="ms_infantCount"><?php echo $search_query['infants'] ?? 0; ?></span>
                            <button type="button" onclick="event.stopPropagation(); msPaxUpdate('infant',1);">+</button>
                        </div>
                    </div>
                    <div class="ms-pax-row" style="border:none; padding-bottom:0;">
                        <div><strong>Cabin Class</strong></div>
                        <select id="ms_cabinSelect" onchange="document.getElementById('ms_cabin').value=this.value; document.getElementById('ms_cabin_class').value=this.value; msUpdateTravellerText();" style="border:1.5px solid #0d3470; border-radius:6px; padding:6px 10px; font-weight:600; font-size:13px;">
                            <option value="Economy" <?php echo ($cabinClass == 'Economy') ? 'selected' : ''; ?>>Economy</option>
                            <option value="Premium Economy" <?php echo ($cabinClass == 'Premium Economy') ? 'selected' : ''; ?>>Premium Economy</option>
                            <option value="Business" <?php echo ($cabinClass == 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="First Class" <?php echo ($cabinClass == 'First Class') ? 'selected' : ''; ?>>First Class</option>
                        </select>
                    </div>
                    <button type="button" class="ms-pax-done" onclick="event.stopPropagation(); document.getElementById('msTravellerPopup').style.display='none';">Apply</button>
                </div>

                <!-- Dual Month Interactive Calendar Popup -->
                <div class="ms-calendar-popup" id="msCalendarPopup">
                    <div class="ms-cal-header-tabs">
                        <div class="ms-cal-tab active" id="msCalTabDep">
                            <span class="ms-cal-tab-label">DEPARTURE</span>
                            <span class="ms-cal-tab-date" id="msCalDepText"><?php echo date('M d, Y', strtotime($departDate)); ?></span>
                        </div>
                        <div class="ms-cal-tab" id="msCalTabRet">
                            <span class="ms-cal-tab-label">RETURN</span>
                            <span class="ms-cal-tab-date" id="msCalRetText"><?php echo !empty($is_roundtrip) ? date('M d, Y', strtotime($returnDate)) : 'Select Return'; ?> <i class="fa-solid fa-circle-xmark ms-cal-clear-ret" id="msCalClearRet" title="Clear return date" style="display: <?php echo !empty($is_roundtrip) ? 'inline-block' : 'none'; ?>;"></i></span>
                        </div>
                    </div>
                    <div class="ms-cal-months-grid" id="msCalMonthsGrid">
                        <!-- Dynamically rendered 2 months -->
                    </div>
                    <div class="ms-cal-bottom-note">* All fares are in INR</div>
                </div>

                <!-- Bottom Bar: Special Fares -->
                <div class="ms-bottom-bar" id="msBottomBar">
                    <label class="ms-fare-checkbox ms-fare-direct" id="msFareDirect"><input type="checkbox" name="direct_only"> Direct Flights</label>
                    <label class="ms-fare-checkbox"><input type="checkbox" name="defence_fare"> Defence Fare</label>
                    <label class="ms-fare-checkbox"><input type="checkbox" name="student_fare"> Student Fare</label>
                    <label class="ms-fare-checkbox"><input type="checkbox" name="senior_fare"> Senior Citizen Fare</label>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MAIN LAYOUT: SIDEBAR FILTERS (EXACT MATCH SCREENSHOT 1) + RESULTS AREA    -->
<!-- ========================================================================= -->
<div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start;">

    <!-- Left Interactive Filter Sidebar (Screenshot 1 Exact Match) -->
    <aside class="flight-filters-sidebar">
        <!-- Sidebar Header -->
        <div class="filter-header-row">
            <h3 class="filter-main-title">
                <i class="fa-solid fa-sliders" style="color: #0d3470;"></i> Filters
            </h3>
            <span onclick="resetAllFilters();" class="filter-reset-btn">Reset All</span>
        </div>

        <!-- 1. Stops (Screenshot 1: Non Stop, 1, 1+) -->
        <div class="filter-section">
            <h4 class="filter-title">Stops</h4>
            <div class="stops-grid-3">
                <div class="stop-box" data-stop-filter="0" onclick="toggleStopFilter(this, '0');">
                    <span class="stop-name">Non Stop</span>
                    <span class="stop-price"><?php echo $nonStopMin > 0 ? ('₹ ' . number_format($nonStopMin)) : '--'; ?></span>
                </div>
                <div class="stop-box" data-stop-filter="1" onclick="toggleStopFilter(this, '1');">
                    <span class="stop-name">1</span>
                    <span class="stop-price"><?php echo $oneStopMin > 0 ? ('₹ ' . number_format($oneStopMin)) : '--'; ?></span>
                </div>
                <div class="stop-box" data-stop-filter="1+" onclick="toggleStopFilter(this, '1+');">
                    <span class="stop-name">1+</span>
                    <span class="stop-price"><?php echo $onePlusMin > 0 ? ('₹ ' . number_format($onePlusMin)) : ($oneStopMin > 0 ? ('₹ ' . number_format($oneStopMin)) : '₹ 18,603'); ?></span>
                </div>
            </div>
        </div>

        <!-- 2. Fare Type -->
        <div class="filter-section">
            <h4 class="filter-title">Fare Type</h4>
            <label class="custom-checkbox" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input type="checkbox" id="filterRefundable" onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                <span style="font-size:13.5px; font-weight:700; color:#000000;">Refundable</span>
            </label>
        </div>

        <!-- 3. Departure Times -->
        <div class="filter-section">
            <h4 class="filter-title">Departure Times</h4>
            <p class="filter-subtitle">From <?php echo htmlspecialchars($origCode); ?></p>
            <div class="time-grid">
                <div class="time-box" data-dep-slot="morning" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-sun"></i> 05am - 12pm</div>
                <div class="time-box" data-dep-slot="afternoon" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-sun"></i> 12pm - 6pm</div>
                <div class="time-box" data-dep-slot="evening" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-cloud-sun"></i> 6pm - 11pm</div>
                <div class="time-box" data-dep-slot="night" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-moon"></i> 11pm - 05am</div>
            </div>
        </div>

        <!-- 4. Arrival Times -->
        <div class="filter-section">
            <h4 class="filter-title">Arrival Times</h4>
            <p class="filter-subtitle">At <?php echo htmlspecialchars($destCode); ?></p>
            <div class="time-grid">
                <div class="time-box" data-arr-slot="morning" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-sun"></i> 05am - 12pm</div>
                <div class="time-box" data-arr-slot="afternoon" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-sun"></i> 12pm - 6pm</div>
                <div class="time-box" data-arr-slot="evening" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-cloud-sun"></i> 6pm - 11pm</div>
                <div class="time-box" data-arr-slot="night" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-moon"></i> 11pm - 05am</div>
            </div>
        </div>

        <!-- 5. Airlines (Checkbox on Right, Unchecked by Default) -->
        <div class="filter-section">
            <h4 class="filter-title">Airlines</h4>
            <div id="airlineFilterList">
                <?php foreach ($airlineData as $aCode => $aInfo): ?>
                <label class="airline-filter-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:11px; cursor:pointer;">
                    <span class="airline-filter-info" style="display:flex; align-items:center; gap:10px;">
                        <img src="<?php echo htmlspecialchars($aInfo['logo']); ?>" alt="" class="airline-filter-logo" onerror="this.src='https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';">
                        <span style="color:#000000; font-weight:700;"><?php echo htmlspecialchars($aInfo['name']); ?> (<?php echo $aInfo['count']; ?>)</span>
                    </span>
                    <span style="display:flex; align-items:center; gap:10px;">
                        <span class="airline-filter-price" style="color:#000000; font-weight:800;">₹ <?php echo number_format($aInfo['min']); ?></span>
                        <input type="checkbox" class="airline-checkbox" value="<?php echo htmlspecialchars($aCode); ?>" onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                    </span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 6. Price Range -->
        <div class="filter-section">
            <h4 class="filter-title">Price Range</h4>
            <div class="price-range-wrap">
                <input type="range" id="priceRangeSlider" min="<?php echo floor($minPrice); ?>" max="<?php echo ceil($maxPrice); ?>" value="<?php echo ceil($maxPrice); ?>" class="price-slider" oninput="updatePriceSlider(this.value);" style="width: 100%; accent-color: #0d3470; cursor: pointer;">
                <div class="price-range-labels" style="display:flex; justify-content:space-between; margin-top:8px; font-size:13px; font-weight:800; color:#000000;">
                    <span style="color:#000000; font-weight:800;">₹ <?php echo number_format(floor($minPrice)); ?></span>
                    <span id="priceRangeMax" style="color:#000000; font-weight:800;">₹ <?php echo number_format(ceil($maxPrice)); ?></span>
                </div>
            </div>
        </div>

        <!-- 7. Connecting Airports (Screenshot 1) -->
        <?php if (!empty($connectingAirportsList)): ?>
        <div class="filter-section" id="connectingAirportsSection">
            <h4 class="filter-title">Connecting Airports</h4>
            <div class="connecting-airports-list">
                <?php 
                $airIdx = 0;
                foreach ($connectingAirportsList as $cCode => $cInfo): 
                    $airIdx++;
                    $isExtra = ($airIdx > 5);
                ?>
                <label class="custom-checkbox connect-airport-item <?php echo $isExtra ? 'extra-airport' : ''; ?>" style="display:<?php echo $isExtra ? 'none' : 'flex'; ?>; align-items:center; gap:10px; margin-bottom:9px; cursor:pointer;">
                    <input type="checkbox" class="connect-airport-cb" value="<?php echo htmlspecialchars($cCode); ?>" onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                    <span style="font-size:13.5px; font-weight:600; color:#1e293b;"><?php echo htmlspecialchars($cInfo['name']); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <?php if (count($connectingAirportsList) > 5): ?>
            <button type="button" class="btn-more-airports" id="toggleMoreAirportsBtn" onclick="toggleMoreAirports();" style="background:none; border:none; color:#2563eb; font-size:13px; font-weight:700; cursor:pointer; padding:4px 0; margin-top:2px;">
                + <?php echo (count($connectingAirportsList) - 5); ?> Airports
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- B2B Net Guarantee Badge -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin: 16px 20px 20px 20px; text-align: center;">
            <div style="font-size: 12px; font-weight: 800; color: #166534; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <i class="fa-solid fa-shield-halved"></i> B2B NET GUARANTEE
            </div>
            <div style="font-size: 11px; color: #15803d; margin-top: 4px;">Direct Store Float Settlement</div>
        </div>
    </aside>

    <!-- Right Results Area -->
    <div>
        <!-- Sorting & Results Summary Bar -->
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <strong style="font-size: 15px; color: #0f172a;" id="resultsCountText">Showing <?php echo count($onwardList); ?> Flights</strong>
                <span style="background: #e0f2fe; color: #0369a1; font-size: 11.5px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">Live Benzy API Inventory</span>
            </div>

            <!-- Sorting Tabs matching voyogos.com/flight -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Sort By:</span>
                <div class="f-sort-tab active" onclick="sortFlights('cheapest', this)">
                    <i class="fa-solid fa-arrow-down-1-9"></i> CHEAPEST
                </div>
                <div class="f-sort-tab" onclick="sortFlights('fastest', this)">
                    <i class="fa-solid fa-bolt"></i> FASTEST
                </div>
                <div class="f-sort-tab" onclick="sortFlights('earliest', this)">
                    <i class="fa-solid fa-clock"></i> EARLIEST
                </div>
            </div>
        </div>

        <?php if ($is_roundtrip): ?>
        <!-- ================================================================ -->
        <!-- ROUND TRIP DUAL SECTOR LAYOUT (Onward Left + Return Right)       -->
        <!-- ================================================================ -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 80px;">
            
            <!-- 1. Onward Flights Column -->
            <div>
                <div style="background: #09204b; color: #ffffff; padding: 12px 16px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">DEPARTURE FLIGHT</span>
                        <strong style="font-size: 15px;"><?php echo htmlspecialchars($origCode); ?> &rarr; <?php echo htmlspecialchars($destCode); ?></strong>
                    </div>
                    <span style="font-size: 12px; background: rgba(255,255,255,0.15); padding: 3px 8px; border-radius: 4px; font-weight: 700;">
                        <?php echo date('d M, D', strtotime($departDate)); ?>
                    </span>
                </div>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="onwardFlightGroup">
                    <?php foreach ($onwardList as $idx => $f): 
                        $depTime = $f['departure_time'] ?? '06:00';
                        $arrTime = $f['arrival_time'] ?? '08:15';
                        $depHour = (int)substr($depTime, 0, 2);
                        $depSlot = 'night';
                        if ($depHour >= 5 && $depHour < 12) $depSlot = 'morning';
                        elseif ($depHour >= 12 && $depHour < 18) $depSlot = 'afternoon';
                        elseif ($depHour >= 18 && $depHour < 23) $depSlot = 'evening';

                        $arrHour = (int)substr($arrTime, 0, 2);
                        $arrSlot = 'night';
                        if ($arrHour >= 5 && $arrHour < 12) $arrSlot = 'morning';
                        elseif ($arrHour >= 12 && $arrHour < 18) $arrSlot = 'afternoon';
                        elseif ($arrHour >= 18 && $arrHour < 23) $arrSlot = 'evening';

                        $stops = (int)($f['stops'] ?? 0);
                        $viaAirport = !empty($f['via']) ? strtoupper($f['via']) : (!empty($f['Via']) ? strtoupper($f['Via']) : ($stops > 0 ? 'HYD' : ''));
                    ?>
                    <label class="rt-card onward-card <?php echo ($idx === 0) ? 'selected-rt-card' : ''; ?>" 
                           data-idx="<?php echo $idx; ?>" 
                           data-stops="<?php echo $stops; ?>" 
                           data-airline="<?php echo htmlspecialchars(strtoupper($f['airline_code'] ?? '6E')); ?>" 
                           data-price="<?php echo (float)$f['price']; ?>" 
                           data-time="<?php echo htmlspecialchars($depTime); ?>"
                           data-depslot="<?php echo $depSlot; ?>"
                           data-arrslot="<?php echo $arrSlot; ?>"
                           data-refundable="<?php echo !empty($f['refundable']) ? '1' : '1'; ?>"
                           data-via="<?php echo htmlspecialchars($viaAirport); ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="selected_onward_idx" value="<?php echo $idx; ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> onchange="updateRoundTripSelection()" style="accent-color: #78B722; width: 16px; height: 16px;">
                                <img src="<?php echo htmlspecialchars($f['airline_logo']); ?>" alt="logo" style="width: 24px; height: 24px; object-fit: contain;">
                                <div>
                                    <strong style="font-size: 13.5px; color: #0f172a; display: block;"><?php echo htmlspecialchars($f['airline_name']); ?></strong>
                                    <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['flight_number']); ?></span>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 16px; color: #09204b;">₹ <?php echo number_format($f['price']); ?></strong>
                                <span style="font-size: 10px; color: #166534; font-weight: 700; display: block;">B2B Net</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #e2e8f0; font-size: 12px;">
                            <div>
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($depTime); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($origCode); ?></span>
                            </div>
                            <div style="text-align: center; color: #64748b; font-size: 11px;">
                                <span><?php echo htmlspecialchars($f['duration']); ?></span>
                                <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                <span style="color: <?php echo ($stops == 0) ? '#166534' : '#d97706'; ?>; font-weight: 700;">
                                    <?php echo ($stops == 0) ? 'Non Stop' : ($stops . ' Stop'); ?>
                                </span>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($arrTime); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($destCode); ?></span>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 2. Return Flights Column -->
            <div>
                <div style="background: #1e3a8a; color: #ffffff; padding: 12px 16px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">RETURN FLIGHT</span>
                        <strong style="font-size: 15px;"><?php echo htmlspecialchars($destCode); ?> &rarr; <?php echo htmlspecialchars($origCode); ?></strong>
                    </div>
                    <span style="font-size: 12px; background: rgba(255,255,255,0.15); padding: 3px 8px; border-radius: 4px; font-weight: 700;">
                        <?php echo date('d M, D', strtotime($returnDate)); ?>
                    </span>
                </div>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="returnFlightGroup">
                    <?php foreach ($returnList as $rIdx => $rf): 
                        $rDepTime = $rf['departure_time'] ?? '15:30';
                        $rArrTime = $rf['arrival_time'] ?? '17:45';
                        $rDepHour = (int)substr($rDepTime, 0, 2);
                        $rDepSlot = 'night';
                        if ($rDepHour >= 5 && $rDepHour < 12) $rDepSlot = 'morning';
                        elseif ($rDepHour >= 12 && $rDepHour < 18) $rDepSlot = 'afternoon';
                        elseif ($rDepHour >= 18 && $rDepHour < 23) $rDepSlot = 'evening';

                        $rArrHour = (int)substr($rArrTime, 0, 2);
                        $rArrSlot = 'night';
                        if ($rArrHour >= 5 && $rArrHour < 12) $rArrSlot = 'morning';
                        elseif ($rArrHour >= 12 && $rArrHour < 18) $rArrSlot = 'afternoon';
                        elseif ($rArrHour >= 18 && $rArrHour < 23) $rArrSlot = 'evening';

                        $rStops = (int)($rf['stops'] ?? 0);
                        $rViaAirport = !empty($rf['via']) ? strtoupper($rf['via']) : (!empty($rf['Via']) ? strtoupper($rf['Via']) : ($rStops > 0 ? 'HYD' : ''));
                    ?>
                    <label class="rt-card return-card <?php echo ($rIdx === 0) ? 'selected-rt-card' : ''; ?>" 
                           data-idx="<?php echo $rIdx; ?>" 
                           data-stops="<?php echo $rStops; ?>" 
                           data-airline="<?php echo htmlspecialchars(strtoupper($rf['airline_code'] ?? '6E')); ?>" 
                           data-price="<?php echo (float)$rf['price']; ?>" 
                           data-time="<?php echo htmlspecialchars($rDepTime); ?>"
                           data-depslot="<?php echo $rDepSlot; ?>"
                           data-arrslot="<?php echo $rArrSlot; ?>"
                           data-refundable="<?php echo !empty($rf['refundable']) ? '1' : '1'; ?>"
                           data-via="<?php echo htmlspecialchars($rViaAirport); ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="radio" name="selected_return_idx" value="<?php echo $rIdx; ?>" <?php echo ($rIdx === 0) ? 'checked' : ''; ?> onchange="updateRoundTripSelection()" style="accent-color: #1e3a8a; width: 16px; height: 16px;">
                                <img src="<?php echo htmlspecialchars($rf['airline_logo']); ?>" alt="logo" style="width: 24px; height: 24px; object-fit: contain;">
                                <div>
                                    <strong style="font-size: 13.5px; color: #0f172a; display: block;"><?php echo htmlspecialchars($rf['airline_name']); ?></strong>
                                    <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($rf['flight_number']); ?></span>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 16px; color: #1e3a8a;">₹ <?php echo number_format($rf['price']); ?></strong>
                                <span style="font-size: 10px; color: #166534; font-weight: 700; display: block;">B2B Net</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #e2e8f0; font-size: 12px;">
                            <div>
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rDepTime); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($destCode); ?></span>
                            </div>
                            <div style="text-align: center; color: #64748b; font-size: 11px;">
                                <span><?php echo htmlspecialchars($rf['duration']); ?></span>
                                <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                <span style="color: <?php echo ($rStops == 0) ? '#166534' : '#d97706'; ?>; font-weight: 700;">
                                    <?php echo ($rStops == 0) ? 'Non Stop' : ($rStops . ' Stop'); ?>
                                </span>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rArrTime); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($origCode); ?></span>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- Sticky Bottom Action Bar for Round Trip (matching voyogos.com/flight) -->
        <div id="roundTripStickyBar" style="position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 2px solid #78B722; box-shadow: 0 -8px 30px rgba(0,0,0,0.12); z-index: 9999; padding: 14px 24px;">
            <div style="max-width: 1240px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 30px; align-items: center;">
                    <!-- Onward Info -->
                    <div>
                        <span style="background: #e8f5e9; color: #2e7d32; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">OUTBOUND</span>
                        <strong style="font-size: 14px; color: #0f172a; margin-left: 8px;" id="barOnwardAirline"></strong>
                        <div style="font-size: 12px; color: #64748b;" id="barOnwardTime"></div>
                    </div>
                    <div style="height: 35px; width: 1px; background: #e2e8f0;"></div>
                    <!-- Return Info -->
                    <div>
                        <span style="background: #eff6ff; color: #1e40af; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">RETURN</span>
                        <strong style="font-size: 14px; color: #0f172a; margin-left: 8px;" id="barReturnAirline"></strong>
                        <div style="font-size: 12px; color: #64748b;" id="barReturnTime"></div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 24px;">
                    <div style="text-align: right;">
                        <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Total B2B Net Fare</span>
                        <strong style="font-size: 24px; color: #09204b; display: block; line-height: 1;" id="barTotalPrice">₹ 0</strong>
                    </div>

                    <form action="<?php echo site_url('franchise/flight_review'); ?>" method="POST" id="roundTripForm">
                        <input type="hidden" name="is_roundtrip" value="1">
                        <input type="hidden" name="flight_id" id="rt_flight_id" value="">
                        <input type="hidden" name="airline_name" id="rt_airline_name" value="">
                        <input type="hidden" name="flight_number" id="rt_flight_number" value="">
                        <input type="hidden" name="airline_code" id="rt_airline_code" value="">
                        <input type="hidden" name="airline_logo" id="rt_airline_logo" value="">
                        <input type="hidden" name="origin" value="<?php echo htmlspecialchars($origCode); ?>">
                        <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destCode); ?>">
                        <input type="hidden" name="departure_time" id="rt_dep_time" value="">
                        <input type="hidden" name="arrival_time" id="rt_arr_time" value="">
                        <input type="hidden" name="price" id="rt_onward_price" value="">
                        
                        <!-- Return info -->
                        <input type="hidden" name="return_airline_name" id="rt_ret_airline_name" value="">
                        <input type="hidden" name="return_flight_number" id="rt_ret_flight_number" value="">
                        <input type="hidden" name="return_departure_time" id="rt_ret_dep_time" value="">
                        <input type="hidden" name="return_arrival_time" id="rt_ret_arr_time" value="">
                        <input type="hidden" name="return_price" id="rt_ret_price" value="">

                        <!-- Pax -->
                        <input type="hidden" name="adults" value="<?php echo (int)($search_query['adults'] ?? 1); ?>">
                        <input type="hidden" name="children" value="<?php echo (int)($search_query['children'] ?? 0); ?>">
                        <input type="hidden" name="infants" value="<?php echo (int)($search_query['infants'] ?? 0); ?>">

                        <button type="submit" style="background: #78B722; color: #ffffff; border: none; padding: 12px 28px; border-radius: 8px; font-size: 15px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(120, 183, 34, 0.35);">
                            <span>BOOK ROUND TRIP</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- ================================================================ -->
        <!-- ONE WAY FLIGHT LIST                                              -->
        <!-- ================================================================ -->
        <div id="flightListContainer" style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($onwardList as $f): 
                $fId        = $f['id'] ?? ('FL_' . rand(100, 999));
                $alCode     = strtoupper($f['airline_code'] ?? '6E');
                $alName     = $f['airline_name'] ?? ($f['airline'] ?? 'IndiGo');
                $alLogo     = $f['airline_logo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
                $flNumber   = $f['flight_number'] ?? '6E-2041';
                $depTime    = $f['departure_time'] ?? '06:00';
                $arrTime    = $f['arrival_time'] ?? '08:15';
                $duration   = $f['duration'] ?? '2h 15m';
                $stops      = (int)($f['stops'] ?? 0);
                $grossPrice = (float)($f['price'] ?? 5050.00);
                $netPrice   = (float)($f['base_fare'] ?? ($grossPrice * 0.88));
                $taxPrice   = max(0, $grossPrice - $netPrice);
                $baggage    = $f['baggage'] ?? '15 Kg';

                $depHour = (int)substr($depTime, 0, 2);
                $depSlot = 'night';
                if ($depHour >= 5 && $depHour < 12) $depSlot = 'morning';
                elseif ($depHour >= 12 && $depHour < 18) $depSlot = 'afternoon';
                elseif ($depHour >= 18 && $depHour < 23) $depSlot = 'evening';

                $arrHour = (int)substr($arrTime, 0, 2);
                $arrSlot = 'night';
                if ($arrHour >= 5 && $arrHour < 12) $arrSlot = 'morning';
                elseif ($arrHour >= 12 && $arrHour < 18) $arrSlot = 'afternoon';
                elseif ($arrHour >= 18 && $arrHour < 23) $arrSlot = 'evening';

                $viaAirport = !empty($f['via']) ? strtoupper($f['via']) : (!empty($f['Via']) ? strtoupper($f['Via']) : ($stops > 0 ? 'HYD' : ''));
            ?>
            <div class="f-flight-item" 
                 data-price="<?php echo $grossPrice; ?>" 
                 data-stops="<?php echo $stops; ?>" 
                 data-airline="<?php echo htmlspecialchars($alCode); ?>" 
                 data-time="<?php echo htmlspecialchars($depTime); ?>"
                 data-depslot="<?php echo $depSlot; ?>"
                 data-arrslot="<?php echo $arrSlot; ?>"
                 data-refundable="<?php echo !empty($f['refundable']) ? '1' : '1'; ?>"
                 data-via="<?php echo htmlspecialchars($viaAirport); ?>">
                
                <!-- Main Flight Row -->
                <div style="padding: 20px 24px; display: grid; grid-template-columns: 200px 1fr 220px; gap: 20px; align-items: center;">
                    
                    <!-- Airline Block -->
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <img src="<?php echo htmlspecialchars($alLogo); ?>" alt="<?php echo htmlspecialchars($alName); ?>" style="width: 38px; height: 38px; object-fit: contain;">
                        <div>
                            <strong style="font-size: 15px; color: #0f172a; display: block;"><?php echo htmlspecialchars($alName); ?></strong>
                            <span style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars($flNumber); ?> &bull; <?php echo htmlspecialchars($cabinClass); ?></span>
                            <span style="display: inline-block; background: #e0f2fe; color: #0284c7; font-size: 10.5px; font-weight: 700; padding: 2px 6px; border-radius: 4px; margin-top: 4px;">
                                B2B Net Fare
                            </span>
                        </div>
                    </div>

                    <!-- Timings & Route Block -->
                    <div style="display: flex; align-items: center; justify-content: space-around; text-align: center;">
                        <!-- Dep -->
                        <div style="text-align: left;">
                            <strong style="font-size: 22px; color: #0f172a; display: block; font-weight: 800;"><?php echo htmlspecialchars($depTime); ?></strong>
                            <span style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo htmlspecialchars($origCode); ?></span>
                            <span style="font-size: 11px; color: #94a3b8; display: block;">Airport</span>
                        </div>

                        <!-- Duration Path -->
                        <div style="display: flex; flex-direction: column; align-items: center; width: 140px;">
                            <span style="font-size: 11.5px; color: #64748b; font-weight: 600;"><?php echo htmlspecialchars($duration); ?></span>
                            <div style="width: 100%; height: 2px; background: #cbd5e1; position: relative; margin: 6px 0;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%); font-size: 11px; color: #78B722; background: #ffffff; padding: 0 4px;"></i>
                            </div>
                            <span style="font-size: 11px; font-weight: 700; color: <?php echo ($stops == 0) ? '#166534' : '#d97706'; ?>;">
                                <?php echo ($stops == 0) ? 'Non Stop' : ($stops . ' Stop' . ($viaAirport ? ' (' . $viaAirport . ')' : '')); ?>
                            </span>
                        </div>

                        <!-- Arr -->
                        <div style="text-align: right;">
                            <strong style="font-size: 22px; color: #0f172a; display: block; font-weight: 800;"><?php echo htmlspecialchars($arrTime); ?></strong>
                            <span style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo htmlspecialchars($destCode); ?></span>
                            <span style="font-size: 11px; color: #94a3b8; display: block;">Airport</span>
                        </div>
                    </div>

                    <!-- Price & Booking Block -->
                    <div style="text-align: right; border-left: 1px solid #f1f5f9; padding-left: 20px;">
                        <div style="font-size: 11.5px; color: #94a3b8; text-decoration: line-through;">₹ <?php echo number_format($grossPrice * 1.12); ?></div>
                        <div style="font-size: 24px; font-weight: 800; color: #09204b; line-height: 1.1;">
                            ₹ <?php echo number_format($grossPrice, 2); ?>
                        </div>
                        <span style="font-size: 11px; color: #166534; font-weight: 700; display: block; margin-top: 2px;">
                            <i class="fa-solid fa-check"></i> Float Wallet Net Fare
                        </span>

                        <form method="post" action="<?php echo site_url('franchise/flight_review'); ?>" style="margin-top: 10px;">
                            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($fId); ?>">
                            <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($alName); ?>">
                            <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($flNumber); ?>">
                            <input type="hidden" name="airline_code" value="<?php echo htmlspecialchars($alCode); ?>">
                            <input type="hidden" name="airline_logo" value="<?php echo htmlspecialchars($alLogo); ?>">
                            <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($origCode); ?>">
                            <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($destCode); ?>">
                            <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($depTime); ?>">
                            <input type="hidden" name="arrival_time" value="<?php echo htmlspecialchars($arrTime); ?>">
                            <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
                            <input type="hidden" name="stops" value="<?php echo (int)$stops; ?>">
                            <input type="hidden" name="base_fare" value="<?php echo $netPrice; ?>">
                            <input type="hidden" name="tax" value="<?php echo $taxPrice; ?>">
                            <input type="hidden" name="price" value="<?php echo $grossPrice; ?>">
                            <input type="hidden" name="total_fare" value="<?php echo $grossPrice; ?>">
                            <input type="hidden" name="adults" value="<?php echo (int)($search_query['adults'] ?? 1); ?>">
                            <input type="hidden" name="children" value="<?php echo (int)($search_query['children'] ?? 0); ?>">
                            <input type="hidden" name="infants" value="<?php echo (int)($search_query['infants'] ?? 0); ?>">

                            <button type="submit" class="f-book-btn">
                                <span>BOOK NOW</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Accordion Drawer (Baggage, Policies, Flight Details) -->
                <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 24px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #475569;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <span><i class="fa-solid fa-suitcase" style="color: #78B722; margin-right: 5px;"></i> <strong>Check-in:</strong> <?php echo htmlspecialchars($baggage); ?></span>
                        <span><i class="fa-solid fa-briefcase" style="color: #0284c7; margin-right: 5px;"></i> <strong>Cabin:</strong> 7 Kg</span>
                        <span><i class="fa-solid fa-circle-check" style="color: #16a34a; margin-right: 5px;"></i> Refundable Fare</span>
                    </div>

                    <div style="color: #2563eb; font-weight: 700; cursor: pointer;" onclick="toggleDetailsDrawer('details_<?php echo $fId; ?>')">
                        <span>Flight Details</span> <i class="fa-solid fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i>
                    </div>
                </div>

                <!-- Hidden Collapsible Detail Section -->
                <div id="details_<?php echo $fId; ?>" style="display: none; padding: 16px 24px; background: #ffffff; border-top: 1px dashed #e2e8f0; font-size: 12.5px; color: #334155;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <strong style="color: #09204b; display: block; margin-bottom: 6px;">Baggage Allowance</strong>
                            <p style="margin: 0; color: #64748b;">Adult check-in baggage: 15 Kg (1 piece) &bull; Hand baggage: 7 Kg. Excess baggage can be purchased at airline counter.</p>
                        </div>
                        <div>
                            <strong style="color: #09204b; display: block; margin-bottom: 6px;">B2B Cancellation Rules</strong>
                            <p style="margin: 0; color: #64748b;">Standard airline cancellation fee applies up to 2 hours before departure. Refund credited back directly to your Store Float.</p>
                        </div>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT: FILTER ENGINE + MODIFY SEARCH POPUP CONTROLS + CALENDAR      -->
<!-- ========================================================================= -->
<script>
const onwardFlightsData = <?php echo json_encode($onwardList); ?>;
const returnFlightsData = <?php echo json_encode($returnList); ?>;

// Popular airports database for Modify Search Autocomplete
const msAirportsList = [
    { city: "Delhi", code: "DEL", airport: "Indira Gandhi International Airport", country: "India" },
    { city: "Mumbai", code: "BOM", airport: "Chhatrapati Shivaji Maharaj International", country: "India" },
    { city: "Bangalore", code: "BLR", airport: "Kempegowda International Airport", country: "India" },
    { city: "Hyderabad", code: "HYD", airport: "Rajiv Gandhi International Airport", country: "India" },
    { city: "Chennai", code: "MAA", airport: "Chennai International Airport", country: "India" },
    { city: "Kolkata", code: "CCU", airport: "Netaji Subhash Chandra Bose Intl", country: "India" },
    { city: "Goa (Mopa)", code: "GOX", airport: "Manohar International Airport", country: "India" },
    { city: "Goa (Dabolim)", code: "GOI", airport: "Dabolim Airport", country: "India" },
    { city: "Ahmedabad", code: "AMD", airport: "Sardar Vallabhbhai Patel Intl", country: "India" },
    { city: "Pune", code: "PNQ", airport: "Pune International Airport", country: "India" },
    { city: "Jaipur", code: "JAI", airport: "Jaipur International Airport", country: "India" },
    { city: "Lucknow", code: "LKO", airport: "Chaudhary Charan Singh Intl", country: "India" },
    { city: "Kochi", code: "COK", airport: "Cochin International Airport", country: "India" },
    { city: "Amritsar", code: "ATQ", airport: "Sri Guru Ram Dass Jee Intl", country: "India" },
    { city: "Chandigarh", code: "IXC", airport: "Shaheed Bhagat Singh Intl", country: "India" },
    { city: "Srinagar", code: "SXR", airport: "Sheikh ul-Alam Intl Airport", country: "India" },
    { city: "Bhubaneswar", code: "BBI", airport: "Biju Patnaik International Airport", country: "India" },
    { city: "Guwahati", code: "GAU", airport: "Lokpriya Gopinath Bordoloi Intl", country: "India" },
    { city: "Patna", code: "PAT", airport: "Jay Prakash Narayan Airport", country: "India" },
    { city: "Varanasi", code: "VNS", airport: "Lal Bahadur Shastri Intl", country: "India" },
    { city: "Dubai", code: "DXB", airport: "Dubai International Airport", country: "UAE" },
    { city: "Bangkok", code: "BKK", airport: "Suvarnabhumi Airport", country: "Thailand" },
    { city: "Singapore", code: "SIN", airport: "Changi Airport", country: "Singapore" },
    { city: "London", code: "LHR", airport: "Heathrow Airport", country: "United Kingdom" }
];

// 1. Sidebar Interactive Filter State & Engine (Exact match to Screenshot 1)
let selectedStopFilter = 'all';
let selectedDepSlots = [];
let selectedArrSlots = [];

function toggleStopFilter(elem, filterVal) {
    if (elem.classList.contains('active')) {
        elem.classList.remove('active');
        selectedStopFilter = 'all';
    } else {
        document.querySelectorAll('.stop-box').forEach(b => b.classList.remove('active'));
        elem.classList.add('active');
        selectedStopFilter = filterVal;
    }
    applyFilters();
}

function toggleTimeSlot(elem, type) {
    elem.classList.toggle('active');
    const slot = elem.getAttribute(type === 'dep' ? 'data-dep-slot' : 'data-arr-slot');
    let targetArr = (type === 'dep') ? selectedDepSlots : selectedArrSlots;

    if (elem.classList.contains('active')) {
        if (!targetArr.includes(slot)) targetArr.push(slot);
    } else {
        const idx = targetArr.indexOf(slot);
        if (idx > -1) targetArr.splice(idx, 1);
    }
    applyFilters();
}

function updatePriceSlider(val) {
    const label = document.getElementById('priceRangeMax');
    if (label) label.textContent = '₹ ' + parseInt(val).toLocaleString('en-IN');
    applyFilters();
}

function toggleMoreAirports() {
    const extras = document.querySelectorAll('.connect-airport-item.extra-airport');
    const btn = document.getElementById('toggleMoreAirportsBtn');
    if (!extras.length || !btn) return;
    const isCurrentlyHidden = (extras[0].style.display === 'none');
    extras.forEach(el => {
        el.style.display = isCurrentlyHidden ? 'flex' : 'none';
    });
    btn.textContent = isCurrentlyHidden ? '- Less Airports' : '+ ' + extras.length + ' Airports';
}

function resetAllFilters() {
    // 1. Reset Stops
    document.querySelectorAll('.stop-box').forEach(b => b.classList.remove('active'));
    selectedStopFilter = 'all';

    // 2. Reset Fare Type
    const refCb = document.getElementById('filterRefundable');
    if (refCb) refCb.checked = false;

    // 3. Reset Departure & Arrival Times
    document.querySelectorAll('.time-box').forEach(b => b.classList.remove('active'));
    selectedDepSlots = [];
    selectedArrSlots = [];

    // 4. Reset Airlines (All unchecked by default to show all results)
    document.querySelectorAll('.airline-checkbox').forEach(cb => cb.checked = false);

    // 5. Reset Price Range Slider
    const slider = document.getElementById('priceRangeSlider');
    if (slider) {
        slider.value = slider.max;
        updatePriceSlider(slider.max);
    }

    // 6. Reset Connecting Airports
    document.querySelectorAll('.connect-airport-cb').forEach(cb => cb.checked = false);

    applyFilters();
}

// Master Client-side Filter Function
function applyFilters() {
    const refOnly = document.getElementById('filterRefundable') ? document.getElementById('filterRefundable').checked : false;
    const slider = document.getElementById('priceRangeSlider');
    const maxPrice = slider ? parseFloat(slider.value) : Infinity;

    // Selected Airlines (If none selected, show all airlines!)
    const selectedAirlines = Array.from(document.querySelectorAll('.airline-checkbox:checked')).map(cb => cb.value.toUpperCase());
    
    // Selected Connecting Airports (If none selected, show all connecting flights!)
    const selectedAirports = Array.from(document.querySelectorAll('.connect-airport-cb:checked')).map(cb => cb.value.toUpperCase());

    let visibleCount = 0;

    // Filter One-Way Flight Cards
    const oneWayCards = document.querySelectorAll('.f-flight-item');
    const hasMultiStops = Array.from(oneWayCards).some(c => parseInt(c.getAttribute('data-stops') || 0) >= 2);

    oneWayCards.forEach(card => {
        const stops   = parseInt(card.getAttribute('data-stops') || 0);
        const price   = parseFloat(card.getAttribute('data-price') || 0);
        const airline = (card.getAttribute('data-airline') || '').toUpperCase();
        const depSlot = card.getAttribute('data-depslot') || 'morning';
        const arrSlot = card.getAttribute('data-arrslot') || 'morning';
        const isRef   = card.getAttribute('data-refundable') === '1';
        const via     = (card.getAttribute('data-via') || '').toUpperCase();

        // 1. Stops check
        let matchStops = true;
        if (selectedStopFilter === '0') matchStops = (stops === 0);
        else if (selectedStopFilter === '1') matchStops = (stops === 1);
        else if (selectedStopFilter === '1+' || selectedStopFilter === '2+') {
            matchStops = hasMultiStops ? (stops >= 2) : (stops >= 1);
        }

        // 2. Fare Type check
        let matchRef = true;
        if (refOnly && !isRef) matchRef = false;

        // 3. Departure Slot check
        let matchDep = true;
        if (selectedDepSlots.length > 0 && !selectedDepSlots.includes(depSlot)) matchDep = false;

        // 4. Arrival Slot check
        let matchArr = true;
        if (selectedArrSlots.length > 0 && !selectedArrSlots.includes(arrSlot)) matchArr = false;

        // 5. Airline check
        let matchAirline = (selectedAirlines.length === 0) || selectedAirlines.includes(airline);

        // 6. Price Slider check
        let matchPrice = (price <= maxPrice);

        // 7. Connecting Airport check
        let matchAirport = true;
        if (selectedAirports.length > 0) {
            if (stops === 0) {
                matchAirport = false;
            } else {
                matchAirport = selectedAirports.includes(via);
            }
        }

        const isMatch = matchStops && matchRef && matchDep && matchArr && matchAirline && matchPrice && matchAirport;

        card.style.display = isMatch ? 'block' : 'none';
        if (isMatch) visibleCount++;
    });

    const countEl = document.getElementById('resultsCountText');
    if (countEl) countEl.innerText = `Showing ${visibleCount} Flights`;

    // Filter Round-Trip Sector Cards
    const onwardCards = document.querySelectorAll('.onward-card');
    const returnCards = document.querySelectorAll('.return-card');

    if (onwardCards.length > 0 || returnCards.length > 0) {
        onwardCards.forEach(card => {
            const stops   = parseInt(card.getAttribute('data-stops') || 0);
            const price   = parseFloat(card.getAttribute('data-price') || 0);
            const airline = (card.getAttribute('data-airline') || '').toUpperCase();
            const depSlot = card.getAttribute('data-depslot') || 'morning';

            let matchStops = (selectedStopFilter === 'all') || (selectedStopFilter === '0' && stops === 0) || (selectedStopFilter === '1' && stops === 1) || (selectedStopFilter === '1+' && stops >= 1);
            let matchDep = (selectedDepSlots.length === 0) || selectedDepSlots.includes(depSlot);
            let matchAirline = (selectedAirlines.length === 0) || selectedAirlines.includes(airline);
            let matchPrice = (price <= maxPrice);

            const isMatch = matchStops && matchDep && matchAirline && matchPrice;
            card.style.display = isMatch ? 'block' : 'none';
        });

        returnCards.forEach(card => {
            const stops   = parseInt(card.getAttribute('data-stops') || 0);
            const price   = parseFloat(card.getAttribute('data-price') || 0);
            const airline = (card.getAttribute('data-airline') || '').toUpperCase();
            const depSlot = card.getAttribute('data-depslot') || 'morning';

            let matchStops = (selectedStopFilter === 'all') || (selectedStopFilter === '0' && stops === 0) || (selectedStopFilter === '1' && stops === 1) || (selectedStopFilter === '1+' && stops >= 1);
            let matchDep = (selectedDepSlots.length === 0) || selectedDepSlots.includes(depSlot);
            let matchAirline = (selectedAirlines.length === 0) || selectedAirlines.includes(airline);
            let matchPrice = (price <= maxPrice);

            const isMatch = matchStops && matchDep && matchAirline && matchPrice;
            card.style.display = isMatch ? 'block' : 'none';
        });
    }
}

// Sorting handler
function sortFlights(type, elem) {
    document.querySelectorAll('.f-sort-tab').forEach(t => t.classList.remove('active'));
    elem.classList.add('active');

    const container = document.getElementById('flightListContainer');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.f-flight-item'));
    cards.sort((a, b) => {
        const priceA = parseFloat(a.getAttribute('data-price') || 0);
        const priceB = parseFloat(b.getAttribute('data-price') || 0);
        const stopsA = parseInt(a.getAttribute('data-stops') || 0);
        const stopsB = parseInt(b.getAttribute('data-stops') || 0);
        const timeA  = a.getAttribute('data-time') || '00:00';
        const timeB  = b.getAttribute('data-time') || '00:00';

        if (type === 'cheapest') {
            return priceA - priceB;
        } else if (type === 'fastest') {
            return (stopsA - stopsB) || (priceA - priceB);
        } else if (type === 'earliest') {
            return timeA.localeCompare(timeB);
        }
        return 0;
    });

    cards.forEach(c => container.appendChild(c));
}

function toggleDetailsDrawer(drawerId) {
    const el = document.getElementById(drawerId);
    if (el) {
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }
}

// Round-trip sticky bar update
function updateRoundTripSelection() {
    const onwardRadio = document.querySelector('input[name="selected_onward_idx"]:checked');
    const returnRadio = document.querySelector('input[name="selected_return_idx"]:checked');

    const oIdx = onwardRadio ? parseInt(onwardRadio.value) : 0;
    const rIdx = returnRadio ? parseInt(returnRadio.value) : 0;

    const o = onwardFlightsData[oIdx] || onwardFlightsData[0];
    const r = returnFlightsData[rIdx] || returnFlightsData[0];

    document.querySelectorAll('.onward-card').forEach((card, idx) => {
        if (idx === oIdx) card.classList.add('selected-rt-card');
        else card.classList.remove('selected-rt-card');
    });

    document.querySelectorAll('.return-card').forEach((card, idx) => {
        if (idx === rIdx) card.classList.add('selected-rt-card');
        else card.classList.remove('selected-rt-card');
    });

    if (o) {
        const barOAir = document.getElementById('barOnwardAirline');
        const barOTime = document.getElementById('barOnwardTime');
        if (barOAir) barOAir.textContent = (o.airline_name || 'Airline') + ' ' + (o.flight_number || '');
        if (barOTime) barOTime.textContent = (o.departure_time || '') + ' - ' + (o.arrival_time || '') + ' (' + (o.from_code || 'DEL') + ' → ' + (o.to_code || 'BOM') + ')';
        
        const fId = document.getElementById('rt_flight_id'); if (fId) fId.value = o.id || 'FL_101';
        const alName = document.getElementById('rt_airline_name'); if (alName) alName.value = o.airline_name || '';
        const flNum = document.getElementById('rt_flight_number'); if (flNum) flNum.value = o.flight_number || '';
        const alCode = document.getElementById('rt_airline_code'); if (alCode) alCode.value = o.airline_code || '';
        const alLogo = document.getElementById('rt_airline_logo'); if (alLogo) alLogo.value = o.airline_logo || '';
        const depTime = document.getElementById('rt_dep_time'); if (depTime) depTime.value = o.departure_time || '';
        const arrTime = document.getElementById('rt_arr_time'); if (arrTime) arrTime.value = o.arrival_time || '';
        const onPrice = document.getElementById('rt_onward_price'); if (onPrice) onPrice.value = o.price || '';
    }

    if (r) {
        const barRAir = document.getElementById('barReturnAirline');
        const barRTime = document.getElementById('barReturnTime');
        if (barRAir) barRAir.textContent = (r.airline_name || 'Airline') + ' ' + (r.flight_number || '');
        if (barRTime) barRTime.textContent = (r.departure_time || '') + ' - ' + (r.arrival_time || '') + ' (' + (r.from_code || 'BOM') + ' → ' + (r.to_code || 'DEL') + ')';

        const retName = document.getElementById('rt_ret_airline_name'); if (retName) retName.value = r.airline_name || '';
        const retNum = document.getElementById('rt_ret_flight_number'); if (retNum) retNum.value = r.flight_number || '';
        const retDep = document.getElementById('rt_ret_dep_time'); if (retDep) retDep.value = r.departure_time || '';
        const retArr = document.getElementById('rt_ret_arr_time'); if (retArr) retArr.value = r.arrival_time || '';
        const retPrice = document.getElementById('rt_ret_price'); if (retPrice) retPrice.value = r.price || '';
    }

    const total = (o ? parseFloat(o.price || 0) : 0) + (r ? parseFloat(r.price || 0) : 0);
    const barTotal = document.getElementById('barTotalPrice');
    if (barTotal) barTotal.textContent = '₹ ' + total.toLocaleString('en-IN');
}

// =========================================================================
// 2. MODIFY SEARCH POPUP CONTROLS & DUAL-MONTH INTERACTIVE CALENDAR ENGINE  
// =========================================================================
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($is_roundtrip): ?>
    updateRoundTripSelection();
    <?php endif; ?>

    const openBtn = document.getElementById('openModifySearchBtn');
    const closeBtn = document.getElementById('closeModifySearchBtn');
    const overlay = document.getElementById('modifySearchOverlay');

    function formatShortDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return dateStr;
        const day = String(d.getDate()).padStart(2, '0');
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const month = months[d.getMonth()];
        const year = String(d.getFullYear()).slice(-2);
        return `${day} ${month}'${year}`;
    }

    function formatFullDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return dateStr;
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
    }

    function getDayName(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return '';
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        return days[d.getDay()];
    }

    function formatYMD(d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Modal Open/Close Handlers
    if (openBtn && overlay) {
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn && overlay) {
        closeBtn.addEventListener('click', function() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            closeCalendar();
            closeAllCitySuggest();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                closeCalendar();
                closeAllCitySuggest();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            closeCalendar();
            closeAllCitySuggest();
        }
    });

    // Swap Cities
    const swapBtn = document.getElementById('msSwapBtn');
    if (swapBtn) {
        swapBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const fromInput = document.getElementById('ms_from_city');
            const toInput   = document.getElementById('ms_to_city');
            const fromSub   = document.getElementById('ms_from_sub');
            const toSub     = document.getElementById('ms_to_sub');
            const fromCode  = document.getElementById('ms_from_code');
            const toCode    = document.getElementById('ms_to_code');
            const origin    = document.getElementById('ms_origin');
            const dest      = document.getElementById('ms_destination');

            if (fromInput && toInput) {
                const tempVal = fromInput.value;
                fromInput.value = toInput.value;
                toInput.value = tempVal;

                if (fromSub && toSub) {
                    const tempSub = fromSub.textContent;
                    fromSub.textContent = toSub.textContent;
                    toSub.textContent = tempSub;
                }

                if (fromCode && toCode) {
                    const tempCode = fromCode.value;
                    fromCode.value = toCode.value;
                    toCode.value = tempCode;
                }

                if (origin && dest) {
                    const tempOrg = origin.value;
                    origin.value = dest.value;
                    dest.value = tempOrg;
                }
            }
        });
    }

    // City Suggestion Dropdown Handling
    function setupCitySuggest(inputId, suggestId, codeId, subId, paramId) {
        const inp = document.getElementById(inputId);
        const sug = document.getElementById(suggestId);
        const cod = document.getElementById(codeId);
        const sub = document.getElementById(subId);
        const par = document.getElementById(paramId);

        if (!inp || !sug) return;

        function renderSuggestions(query) {
            const q = (query || '').trim().toLowerCase();
            const filtered = msAirportsList.filter(a => {
                return !q || a.city.toLowerCase().includes(q) || a.code.toLowerCase().includes(q) || a.airport.toLowerCase().includes(q);
            });

            if (filtered.length === 0) {
                sug.innerHTML = '<div style="padding:10px 14px; color:#94a3b8; font-size:12px;">No matching airports found</div>';
                sug.style.display = 'block';
                return;
            }

            sug.innerHTML = filtered.map(item => `
                <div class="ms-suggest-item" data-city="${item.city}" data-code="${item.code}" data-airport="${item.airport}">
                    <div>
                        <div style="font-weight:700; color:#0f172a; font-size:13px;">${item.city}</div>
                        <div style="font-size:11px; color:#64748b;">${item.airport}</div>
                    </div>
                    <span style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-weight:800; color:#0f172a; font-size:11px;">${item.code}</span>
                </div>
            `).join('');
            sug.style.display = 'block';

            sug.querySelectorAll('.ms-suggest-item').forEach(el => {
                el.addEventListener('click', function(ev) {
                    ev.stopPropagation();
                    const cCity = this.getAttribute('data-city');
                    const cCode = this.getAttribute('data-code');
                    inp.value = cCity;
                    if (cod) cod.value = cCode;
                    if (par) par.value = cCode;
                    if (sub) sub.textContent = `${cCode}, Airport`;
                    sug.style.display = 'none';
                });
            });
        }

        inp.addEventListener('focus', function() {
            closeAllCitySuggest();
            renderSuggestions(this.value);
        });

        inp.addEventListener('input', function() {
            renderSuggestions(this.value);
        });
    }

    setupCitySuggest('ms_from_city', 'msFromSuggest', 'ms_from_code', 'ms_from_sub', 'ms_origin');
    setupCitySuggest('ms_to_city', 'msToSuggest', 'ms_to_code', 'ms_to_sub', 'ms_destination');

    function closeAllCitySuggest() {
        const s1 = document.getElementById('msFromSuggest'); if (s1) s1.style.display = 'none';
        const s2 = document.getElementById('msToSuggest'); if (s2) s2.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#msFromBox') && !e.target.closest('#msToBox')) {
            closeAllCitySuggest();
        }
    });

    // Trip Type Pills Switching (One Way, Round Trip, Multi City)
    const tripRadios = document.querySelectorAll('input[name="ms_trip_type"]');
    const tabOW = document.getElementById('tabOneway');
    const tabRT = document.getElementById('tabRoundtrip');
    const tabMC = document.getElementById('tabMulticity');
    const standardGrid = document.getElementById('msStandardGrid');
    const multicityGrid = document.getElementById('msMulticityGrid');
    const bottomBar = document.getElementById('msBottomBar');

    function applyTripType(type) {
        document.getElementById('ms_tripType').value = type;

        const isOW = (type === 'oneway');
        const isRT = (type === 'roundtrip');
        const isMC = (type === 'multicity');

        if (tabOW) tabOW.classList.toggle('active', isOW);
        if (tabRT) tabRT.classList.toggle('active', isRT);
        if (tabMC) tabMC.classList.toggle('active', isMC);

        const retField = document.getElementById('msReturnField');
        const retInput = document.getElementById('ms_return_date');
        const retDisp  = document.getElementById('ms_ret_disp');
        const retSub   = document.getElementById('ms_ret_sub');
        const clearRet = document.getElementById('msClearReturn');

        if (isMC) {
            if (standardGrid) standardGrid.style.display = 'none';
            if (multicityGrid) multicityGrid.classList.add('active');
            if (bottomBar) bottomBar.classList.add('is-multicity');
        } else {
            if (standardGrid) standardGrid.style.display = 'grid';
            if (multicityGrid) multicityGrid.classList.remove('active');
            if (bottomBar) bottomBar.classList.remove('is-multicity');

            if (retField && retInput) {
                if (isRT) {
                    retField.style.opacity = '1';
                    retField.style.cursor = 'pointer';
                    retInput.disabled = false;
                    if (clearRet) clearRet.style.display = 'inline-block';
                    if (retInput.value) {
                        if (retDisp) retDisp.textContent = formatShortDate(retInput.value);
                        if (retSub) retSub.textContent = getDayName(retInput.value);
                    }
                } else {
                    retField.style.opacity = '0.4';
                    retField.style.cursor = 'not-allowed';
                    retInput.disabled = true;
                    if (clearRet) clearRet.style.display = 'none';
                    if (retDisp) retDisp.textContent = '-- --\'--';
                    if (retSub) retSub.textContent = 'Select round trip';
                }
            }
        }
        closeCalendar();
    }

    tripRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            applyTripType(this.value);
        });
    });

    // Multi-City Leg Sync (Leg 1 To -> Leg 2 From)
    const multiTo1 = document.getElementById('msMultiTo1');
    const multiFrom2 = document.getElementById('msMultiFrom2');
    const multiFromSub2 = document.getElementById('msMultiFromSub2');
    if (multiTo1 && multiFrom2) {
        multiTo1.addEventListener('input', function() {
            multiFrom2.value = this.value;
            if (multiFromSub2) multiFromSub2.textContent = 'Airport';
        });
    }

    // Add City / Leg functionality in Multi-City
    const addCityBtn = document.getElementById('msAddCityBtn');
    const extraLegsContainer = document.getElementById('msExtraLegsContainer');
    let extraLegCount = 0;

    if (addCityBtn && extraLegsContainer) {
        addCityBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (extraLegCount >= 3) return;
            extraLegCount++;
            const legNum = 2 + extraLegCount;

            const prevLegToInput = document.getElementById(`msMultiTo${legNum - 1}`) || document.getElementById('msMultiTo2');
            const prevCity = prevLegToInput ? prevLegToInput.value : '';

            const row = document.createElement('div');
            row.className = 'ms-multi-row';
            row.id = `msLegRow${legNum}`;
            row.innerHTML = `
                <div class="ms-field">
                    <div class="ms-field-label">From</div>
                    <input type="text" name="multi_from[]" id="msMultiFrom${legNum}" class="ms-city-input" value="${prevCity}" autocomplete="off">
                    <div class="ms-field-sub">Airport</div>
                </div>
                <div class="ms-field">
                    <div class="ms-field-label">To</div>
                    <input type="text" name="multi_to[]" id="msMultiTo${legNum}" class="ms-city-input" placeholder="Select a City" autocomplete="off">
                    <div class="ms-field-sub" style="display:none;">Destination Airport</div>
                </div>
                <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="${legNum}" id="msMultiDepBox${legNum}">
                    <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                    <div class="ms-date-value" id="msMultiDepDisp${legNum}">29 Sep'26</div>
                    <div class="ms-field-sub" id="msMultiDepSub${legNum}">Tuesday</div>
                    <input type="hidden" name="multi_date[]" id="msMultiDate${legNum}" value="2026-09-29">
                </div>
                <div class="ms-multi-actions">
                    <button type="button" class="ms-remove-leg-btn" title="Remove leg" onclick="this.closest('.ms-multi-row').remove(); extraLegCount--;">&times;</button>
                </div>
            `;
            extraLegsContainer.appendChild(row);

            const newDateBox = row.querySelector('.ms-multi-dep-box');
            if (newDateBox) {
                newDateBox.addEventListener('click', function(evt) {
                    evt.stopPropagation();
                    openCalendarForLeg(legNum);
                });
            }
        });
    }

    // Dual-Month Interactive Calendar Engine
    const calPopup = document.getElementById('msCalendarPopup');
    const calMonthsGrid = document.getElementById('msCalMonthsGrid');
    const calTabDep = document.getElementById('msCalTabDep');
    const calTabRet = document.getElementById('msCalTabRet');
    const calDepText = document.getElementById('msCalDepText');
    const calRetText = document.getElementById('msCalRetText');
    const depChevron = document.getElementById('msDepChevron');
    const retChevron = document.getElementById('msRetChevron');
    const depInput = document.getElementById('ms_departure_date');
    const retInput = document.getElementById('ms_return_date');

    let calMode = 'departure';
    let activeMultiLeg = 1;

    const initialDate = depInput && depInput.value ? new Date(depInput.value + 'T00:00:00') : new Date();
    let calViewYear = !isNaN(initialDate.getFullYear()) ? initialDate.getFullYear() : (new Date()).getFullYear();
    let calViewMonth = !isNaN(initialDate.getMonth()) ? initialDate.getMonth() : (new Date()).getMonth();

    function positionCalendarPopup(legNum) {
        if (!calPopup) return;
        const cardInner = document.querySelector('.ms-card-inner');
        if (!cardInner) return;
        const cardRect = cardInner.getBoundingClientRect();

        if (window.innerWidth <= 992) {
            calPopup.style.left = '50%';
            calPopup.style.transform = 'translateX(-50%)';
            calPopup.style.top = '100%';
            return;
        }

        calPopup.style.transform = 'none';

        if (calMode === 'multi' && legNum) {
            const legBox = document.getElementById(`msMultiDepBox${legNum}`);
            if (legBox) {
                const boxRect = legBox.getBoundingClientRect();
                const leftPos = Math.max(10, Math.min(cardRect.width - 600, boxRect.left - cardRect.left - 40));
                calPopup.style.left = leftPos + 'px';
                calPopup.style.top = (boxRect.bottom - cardRect.top + 6) + 'px';
            }
        } else {
            const depBox = document.getElementById('msDepartureBox');
            if (depBox) {
                const boxRect = depBox.getBoundingClientRect();
                calPopup.style.left = (boxRect.left - cardRect.left) + 'px';
                calPopup.style.top = (boxRect.top - cardRect.top + 34) + 'px';
            }
        }
    }

    function getDayFare(year, month, day) {
        const seed = (year * 372 + (month + 1) * 31 + day) % 100;
        const prices = [5716, 5849, 5876, 5928, 5944, 6039, 6076, 6085, 6098, 6500, 6516, 5716, 5850, 5716];
        return prices[seed % prices.length];
    }

    function buildMonthHTML(year, month, isLeft) {
        const monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const currentDepYMD = depInput ? depInput.value : '';
        const currentRetYMD = retInput ? retInput.value : '';
        const isRT = document.getElementById('ms_tripType').value === 'roundtrip';

        let depDate = currentDepYMD ? new Date(currentDepYMD + 'T00:00:00') : null;
        let retDate = (isRT && currentRetYMD) ? new Date(currentRetYMD + 'T00:00:00') : null;

        if (calMode === 'multi') {
            const multiDateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
            depDate = multiDateInput && multiDateInput.value ? new Date(multiDateInput.value + 'T00:00:00') : null;
            retDate = null;
        }

        let html = `<div class="ms-cal-month-box">`;
        html += `<div class="ms-cal-month-header">`;
        if (isLeft) {
            html += `<button type="button" class="ms-cal-nav-btn" id="msCalPrevBtn" title="Previous Month"><i class="fa-solid fa-arrow-left"></i></button>`;
        } else {
            html += `<div></div>`;
        }
        html += `<div class="ms-cal-month-title">${monthNames[month]} ${year}</div>`;
        if (!isLeft) {
            html += `<button type="button" class="ms-cal-nav-btn" id="msCalNextBtn" title="Next Month"><i class="fa-solid fa-arrow-right"></i></button>`;
        } else {
            html += `<div></div>`;
        }
        html += `</div>`;

        html += `<table class="ms-cal-table">
            <thead>
                <tr>
                    <th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th>
                </tr>
            </thead>
            <tbody>`;

        let dateNum = 1;
        for (let row = 0; row < 6; row++) {
            if (dateNum > daysInMonth) break;
            html += `<tr>`;
            for (let col = 0; col < 7; col++) {
                if (row === 0 && col < firstDay) {
                    html += `<td></td>`;
                } else if (dateNum > daysInMonth) {
                    html += `<td></td>`;
                } else {
                    const thisDate = new Date(year, month, dateNum);
                    thisDate.setHours(0, 0, 0, 0);
                    const ymd = formatYMD(thisDate);
                    const isPast = thisDate < today;
                    const isToday = (thisDate.getTime() === today.getTime());

                    const isDep = depDate && thisDate.getTime() === depDate.getTime();
                    const isRet = retDate && thisDate.getTime() === retDate.getTime();
                    const inRange = depDate && retDate && thisDate > depDate && thisDate < retDate;

                    const fare = (!isPast || isToday) ? getDayFare(year, month, dateNum) : null;
                    const isLowest = (fare === 5716);

                    let classes = ['ms-cal-day-btn'];
                    if (isPast && !isToday) classes.push('disabled');
                    if (isToday) classes.push('is-today');
                    if (isDep) classes.push('selected-dep');
                    if (isRet) classes.push('selected-ret');
                    if (inRange) classes.push('in-range');

                    html += `<td>
                        <button type="button" class="${classes.join(' ')}" data-date="${ymd}" ${(isPast && !isToday) ? 'disabled' : ''}>
                            <span class="ms-cal-day-num">${dateNum}</span>
                            ${fare ? `<span class="ms-cal-day-fare ${isLowest ? 'lowest' : ''}">${fare}</span>` : ''}
                        </button>
                    </td>`;
                    dateNum++;
                }
            }
            html += `</tr>`;
        }

        html += `</tbody></table></div>`;
        return html;
    }

    function renderDualCalendar() {
        if (!calMonthsGrid) return;

        let m1Year = calViewYear;
        let m1Month = calViewMonth;
        let m2Year = m1Year;
        let m2Month = m1Month + 1;
        if (m2Month > 11) {
            m2Month = 0;
            m2Year++;
        }

        const html1 = buildMonthHTML(m1Year, m1Month, true);
        const html2 = buildMonthHTML(m2Year, m2Month, false);
        calMonthsGrid.innerHTML = html1 + html2;

        if (calMode === 'multi') {
            const multiDateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
            const val = multiDateInput ? multiDateInput.value : '';
            if (calTabDep) {
                const label = calTabDep.querySelector('.ms-cal-tab-label');
                if (label) label.textContent = `LEG ${activeMultiLeg} DEPARTURE`;
            }
            if (calDepText) calDepText.textContent = formatFullDate(val);
            if (calTabRet) calTabRet.style.display = 'none';
        } else {
            if (calTabRet) calTabRet.style.display = 'flex';
            if (calTabDep) {
                const label = calTabDep.querySelector('.ms-cal-tab-label');
                if (label) label.textContent = 'DEPARTURE';
            }
            if (depInput && depInput.value && calDepText) {
                calDepText.textContent = formatFullDate(depInput.value);
            }
            if (calRetText) {
                const isRT = document.getElementById('ms_tripType').value === 'roundtrip';
                if (isRT && retInput && retInput.value) {
                    calRetText.innerHTML = `${formatFullDate(retInput.value)} <i class="fa-solid fa-circle-xmark ms-cal-clear-ret" id="msCalClearRet" title="Clear return date"></i>`;
                } else {
                    calRetText.innerHTML = `Select Return`;
                }
            }
        }

        if (calTabDep && calTabRet) {
            calTabDep.classList.toggle('active', calMode === 'departure' || calMode === 'multi');
            calTabRet.classList.toggle('active', calMode === 'return');
        }

        const prevBtn = document.getElementById('msCalPrevBtn');
        const nextBtn = document.getElementById('msCalNextBtn');

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                calViewMonth--;
                if (calViewMonth < 0) {
                    calViewMonth = 11;
                    calViewYear--;
                }
                renderDualCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                calViewMonth++;
                if (calViewMonth > 11) {
                    calViewMonth = 0;
                    calViewYear++;
                }
                renderDualCalendar();
            });
        }

        const clearRetIcon = document.getElementById('msCalClearRet');
        if (clearRetIcon) {
            clearRetIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                applyTripType('oneway');
                const radioOW = tabOW ? tabOW.querySelector('input') : null;
                if (radioOW) radioOW.checked = true;
                calMode = 'departure';
                renderDualCalendar();
            });
        }

        const dayBtns = calMonthsGrid.querySelectorAll('.ms-cal-day-btn:not(.disabled)');
        dayBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const selectedYMD = this.getAttribute('data-date');
                if (!selectedYMD) return;

                if (calMode === 'multi') {
                    const dateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
                    const disp = document.getElementById(`msMultiDepDisp${activeMultiLeg}`);
                    const sub  = document.getElementById(`msMultiDepSub${activeMultiLeg}`);
                    if (dateInput) dateInput.value = selectedYMD;
                    if (disp) disp.textContent = formatShortDate(selectedYMD);
                    if (sub) sub.textContent = getDayName(selectedYMD);
                    closeCalendar();
                    return;
                }

                const isRT = document.getElementById('ms_tripType').value === 'roundtrip';
                const clickedDate = new Date(selectedYMD + 'T00:00:00');

                if (calMode === 'departure') {
                    if (depInput) depInput.value = selectedYMD;
                    const depDisp = document.getElementById('ms_dep_disp');
                    const depSub  = document.getElementById('ms_dep_sub');
                    if (depDisp) depDisp.textContent = formatShortDate(selectedYMD);
                    if (depSub) depSub.textContent = getDayName(selectedYMD);

                    if (isRT) {
                        if (retInput && retInput.value) {
                            const curRet = new Date(retInput.value + 'T00:00:00');
                            if (curRet < clickedDate) {
                                const newRet = new Date(clickedDate);
                                newRet.setDate(newRet.getDate() + 1);
                                const newRetYMD = formatYMD(newRet);
                                retInput.value = newRetYMD;
                                const retDisp = document.getElementById('ms_ret_disp');
                                const retSub  = document.getElementById('ms_ret_sub');
                                if (retDisp) retDisp.textContent = formatShortDate(newRetYMD);
                                if (retSub) retSub.textContent = getDayName(newRetYMD);
                            }
                        }
                        calMode = 'return';
                        renderDualCalendar();
                    } else {
                        calMode = 'return';
                        renderDualCalendar();
                        setTimeout(() => {
                            if (document.getElementById('ms_tripType').value === 'oneway') {
                                closeCalendar();
                            }
                        }, 500);
                    }
                } else if (calMode === 'return') {
                    const curDep = depInput && depInput.value ? new Date(depInput.value + 'T00:00:00') : new Date();

                    if (clickedDate < curDep) {
                        if (depInput) depInput.value = selectedYMD;
                        const depDisp = document.getElementById('ms_dep_disp');
                        const depSub  = document.getElementById('ms_dep_sub');
                        if (depDisp) depDisp.textContent = formatShortDate(selectedYMD);
                        if (depSub) depSub.textContent = getDayName(selectedYMD);
                        calMode = 'return';
                        renderDualCalendar();
                    } else {
                        if (retInput) retInput.value = selectedYMD;
                        const retDisp = document.getElementById('ms_ret_disp');
                        const retSub  = document.getElementById('ms_ret_sub');
                        if (retDisp) retDisp.textContent = formatShortDate(selectedYMD);
                        if (retSub) retSub.textContent = getDayName(selectedYMD);

                        applyTripType('roundtrip');
                        const radioRT = tabRT ? tabRT.querySelector('input') : null;
                        if (radioRT) radioRT.checked = true;

                        renderDualCalendar();
                        setTimeout(() => {
                            closeCalendar();
                        }, 300);
                    }
                }
            });
        });
    }

    function openCalendar(mode) {
        calMode = mode || 'departure';
        if (calPopup) {
            calPopup.classList.add('open');
            positionCalendarPopup();
            renderDualCalendar();
        }
        if (depChevron) {
            depChevron.className = (calMode === 'departure') ? 'fa-solid fa-chevron-up ms-chevron' : 'fa-solid fa-chevron-down ms-chevron';
            depChevron.style.color = (calMode === 'departure') ? '#0284c7' : '#94a3b8';
        }
        if (retChevron) {
            retChevron.className = (calMode === 'return') ? 'fa-solid fa-chevron-up ms-chevron' : 'fa-solid fa-chevron-down ms-chevron';
            retChevron.style.color = (calMode === 'return') ? '#0284c7' : '#94a3b8';
        }
    }

    function openCalendarForLeg(legNum) {
        calMode = 'multi';
        activeMultiLeg = legNum;
        if (calPopup) {
            calPopup.classList.add('open');
            positionCalendarPopup(legNum);
            renderDualCalendar();
        }
    }

    function closeCalendar() {
        if (calPopup) calPopup.classList.remove('open');
        if (depChevron) {
            depChevron.className = 'fa-solid fa-chevron-down ms-chevron';
            depChevron.style.color = '#94a3b8';
        }
        if (retChevron) {
            retChevron.className = 'fa-solid fa-chevron-down ms-chevron';
            retChevron.style.color = '#94a3b8';
        }
    }

    const depBox = document.getElementById('msDepartureBox');
    if (depBox) {
        depBox.addEventListener('click', function(e) {
            e.stopPropagation();
            if (calPopup && calPopup.classList.contains('open') && calMode === 'departure') {
                closeCalendar();
            } else {
                openCalendar('departure');
            }
        });
    }

    const retBox = document.getElementById('msReturnField');
    if (retBox) {
        retBox.addEventListener('click', function(e) {
            if (e.target.closest('#msClearReturn')) return;
            e.stopPropagation();
            const currentTrip = document.getElementById('ms_tripType').value;
            if (currentTrip !== 'roundtrip') {
                applyTripType('roundtrip');
                const radioRT = tabRT ? tabRT.querySelector('input') : null;
                if (radioRT) radioRT.checked = true;
            }
            if (calPopup && calPopup.classList.contains('open') && calMode === 'return') {
                closeCalendar();
            } else {
                openCalendar('return');
            }
        });
    }

    document.querySelectorAll('.ms-multi-dep-box').forEach(box => {
        box.addEventListener('click', function(e) {
            e.stopPropagation();
            const leg = parseInt(this.getAttribute('data-leg') || 1);
            openCalendarForLeg(leg);
        });
    });

    if (calTabDep) {
        calTabDep.addEventListener('click', function(e) {
            e.stopPropagation();
            calMode = 'departure';
            renderDualCalendar();
        });
    }

    if (calTabRet) {
        calTabRet.addEventListener('click', function(e) {
            e.stopPropagation();
            const currentTrip = document.getElementById('ms_tripType').value;
            if (currentTrip !== 'roundtrip') {
                applyTripType('roundtrip');
                const radioRT = tabRT ? tabRT.querySelector('input') : null;
                if (radioRT) radioRT.checked = true;
            }
            calMode = 'return';
            renderDualCalendar();
        });
    }

    const clearRetBtn = document.getElementById('msClearReturn');
    if (clearRetBtn) {
        clearRetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            applyTripType('oneway');
            const radioOW = tabOW ? tabOW.querySelector('input') : null;
            if (radioOW) radioOW.checked = true;
            closeCalendar();
        });
    }

    document.addEventListener('click', function(e) {
        if (calPopup && calPopup.classList.contains('open') && !e.target.closest('#msCalendarPopup') && !e.target.closest('#msDepartureBox') && !e.target.closest('#msReturnField') && !e.target.closest('.ms-multi-dep-box')) {
            closeCalendar();
        }
    });

    window.addEventListener('resize', function() {
        if (calPopup && calPopup.classList.contains('open')) {
            positionCalendarPopup(activeMultiLeg);
        }
    });

    // Shared Traveller Popup Handlers
    const travPopup = document.getElementById('msTravellerPopup');
    const travBox = document.getElementById('msTravellerBox');
    const multiTravBox = document.getElementById('msMultiTravellerBox');

    function toggleTravellerPopup(e) {
        e.stopPropagation();
        if (!travPopup) return;
        travPopup.style.display = (travPopup.style.display === 'block') ? 'none' : 'block';
    }

    if (travBox) travBox.addEventListener('click', toggleTravellerPopup);
    if (multiTravBox) multiTravBox.addEventListener('click', toggleTravellerPopup);

    document.addEventListener('click', function(e) {
        if (travPopup && !e.target.closest('#msTravellerPopup') && !e.target.closest('#msTravellerBox') && !e.target.closest('#msMultiTravellerBox')) {
            travPopup.style.display = 'none';
        }
    });
});

// Passenger counter update helper
function msPaxUpdate(type, delta) {
    let adults   = parseInt(document.getElementById('ms_adults').value || 1);
    let children = parseInt(document.getElementById('ms_children').value || 0);
    let infants  = parseInt(document.getElementById('ms_infants').value || 0);

    if (type === 'adult') {
        adults = Math.max(1, Math.min(9, adults + delta));
        document.getElementById('ms_adults').value = adults;
        document.getElementById('ms_adultCount').textContent = adults;
    } else if (type === 'child') {
        children = Math.max(0, Math.min(9, children + delta));
        document.getElementById('ms_children').value = children;
        document.getElementById('ms_childCount').textContent = children;
    } else if (type === 'infant') {
        infants = Math.max(0, Math.min(4, infants + delta));
        document.getElementById('ms_infants').value = infants;
        document.getElementById('ms_infantCount').textContent = infants;
    }

    msUpdateTravellerText();
}

function msUpdateTravellerText() {
    const adults   = parseInt(document.getElementById('ms_adults').value || 1);
    const children = parseInt(document.getElementById('ms_children').value || 0);
    const infants  = parseInt(document.getElementById('ms_infants').value || 0);
    const total    = adults + children + infants;
    const cabin    = document.getElementById('ms_cabin_class').value || 'Economy';

    const disp      = document.getElementById('msTravellerDisplay');
    const multiDisp = document.getElementById('msMultiTravellerDisplay');
    const sub       = document.getElementById('ms_cabin_sub');
    const multiSub  = document.getElementById('msMultiCabinSub');

    const formattedText = (total < 10 ? '0' + total : total) + ' Traveller' + (total > 1 ? 's' : '');

    if (disp) disp.textContent = formattedText;
    if (multiDisp) multiDisp.textContent = formattedText;
    if (sub) sub.textContent = cabin;
    if (multiSub) multiSub.textContent = cabin;
}
</script>

<style>
/* ------------------------------------------------------------- */
/* Akbar Travels Sidebar Styling (Exact Parity with Screenshot 1) */
/* ------------------------------------------------------------- */
.flight-filters-sidebar {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}
.filter-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1.5px solid #e2e8f0;
}
.filter-main-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.filter-reset-btn {
    font-size: 13px;
    color: #dc2626;
    font-weight: 700;
    cursor: pointer;
    transition: color 0.15s;
}
.filter-reset-btn:hover {
    color: #b91c1c;
    text-decoration: underline;
}
.filter-section {
    padding: 16px 20px;
    border-bottom: 1.5px solid #e2e8f0;
}
.filter-section:last-child {
    border-bottom: none;
}
.filter-title {
    font-size: 15px !important;
    font-weight: 800 !important;
    margin-bottom: 12px !important;
    color: #000000 !important;
    letter-spacing: -0.2px;
}
.filter-subtitle {
    font-size: 12.5px !important;
    font-weight: 700 !important;
    color: #000000 !important;
    margin-bottom: 10px !important;
}

/* Stops Grid 3 (Screenshot 1: Non Stop, 1, 1+) */
.stops-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
.stops-grid-3 .stop-box {
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    padding: 10px 4px;
    text-align: center;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: #ffffff;
    transition: all 0.15s ease;
    user-select: none;
}
.stops-grid-3 .stop-box:hover {
    border-color: #94a3b8;
    background: #f8fafc;
}
.stops-grid-3 .stop-box.active {
    border-color: #0d3470 !important;
    background: #eff6ff !important;
}
.stops-grid-3 .stop-box .stop-name {
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #000000 !important;
}
.stops-grid-3 .stop-box .stop-price {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #000000 !important;
}
.stops-grid-3 .stop-box.active .stop-name,
.stops-grid-3 .stop-box.active .stop-price {
    color: #0d3470 !important;
    font-weight: 800 !important;
}

/* Time Grid (Morning, Afternoon, Evening, Night) */
.time-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.time-box {
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    padding: 10px 8px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 12px !important;
    font-weight: 700 !important;
    color: #000000 !important;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.time-box:hover {
    border-color: #94a3b8;
    background: #f8fafc;
}
.time-box i {
    font-size: 14px !important;
    color: #000000 !important;
    flex-shrink: 0;
}
.time-box.active {
    border-color: #0d3470 !important;
    background: #eff6ff !important;
    color: #0d3470 !important;
    font-weight: 800 !important;
}
.time-box.active i {
    color: #0d3470 !important;
}

/* Airlines Row */
.airline-filter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 11px;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 6px;
    transition: background 0.15s;
}
.airline-filter-row:hover {
    background: #f8fafc;
}
.airline-filter-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #000000 !important;
}
.airline-filter-logo {
    width: 22px;
    height: 22px;
    object-fit: contain;
    border-radius: 4px;
    flex-shrink: 0;
}
.airline-filter-price {
    font-size: 13px !important;
    color: #000000 !important;
    font-weight: 800 !important;
    margin-right: 4px;
}

/* Price Range Slider */
.price-range-labels span, #priceRangeMax {
    color: #000000 !important;
    font-weight: 800 !important;
    font-size: 13px !important;
}

/* Connecting Airports */
.connecting-airports-list {
    display: flex;
    flex-direction: column;
}
.btn-more-airports {
    background: none;
    border: none;
    color: #2563eb;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    padding: 4px 0;
    margin-top: 4px;
    text-align: left;
}
.btn-more-airports:hover {
    text-decoration: underline;
}

/* Modify Search Header Button */
.btn-modify-search {
    background: #ffffff;
    color: #09204b;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.2s;
}
.btn-modify-search:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* ------------------------------------------------------------- */
/* Modify Search Modal Overlay & Styling (Screenshot 3 Parity)   */
/* ------------------------------------------------------------- */
.modify-search-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 99999;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}
.modify-search-overlay.active {
    display: flex !important;
    animation: fadeInModal 0.25s ease-out forwards;
}
@keyframes fadeInModal {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}
.modify-search-modal {
    background: linear-gradient(90deg, #1ea5f2 0%, rgba(30, 165, 242, 0.96) 32%, rgba(30, 165, 242, 0.65) 65%, rgba(30, 165, 242, 0.15) 100%), 
                url('<?php echo base_url("assets/images/modify-search-bg.jpg"); ?>') right center / cover no-repeat;
    border-radius: 22px;
    padding: 24px 28px 26px 28px;
    width: 100%;
    max-width: 1140px;
    box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.45);
    position: relative;
    box-sizing: border-box;
}
.ms-close-btn {
    position: absolute;
    top: -14px;
    right: -14px;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 16px;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    transition: all 0.2s ease;
    z-index: 100;
}
.ms-close-btn:hover {
    background: #ff3b30;
    border-color: #ff3b30;
    color: #ffffff;
    transform: scale(1.1);
}
.ms-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    position: relative;
}
.ms-trip-tabs {
    display: flex;
    gap: 10px;
    align-items: center;
}
.ms-trip-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 18px;
    background: #ffffff;
    border: none;
    border-radius: 30px;
    font-size: 13.5px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    transition: all 0.2s ease;
    user-select: none;
}
.ms-trip-tab:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
}
.ms-trip-tab input {
    display: none;
}
.ms-radio-indicator {
    width: 15px;
    height: 15px;
    border: 2px solid #94a3b8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    transition: all 0.2s;
    box-sizing: border-box;
}
.ms-trip-tab.active .ms-radio-indicator,
.ms-trip-tab:has(input:checked) .ms-radio-indicator {
    border-color: #ff3b30;
}
.ms-radio-dot {
    width: 7px;
    height: 7px;
    background: #ff3b30;
    border-radius: 50%;
    opacity: 0;
    transform: scale(0);
    transition: all 0.2s;
}
.ms-trip-tab.active .ms-radio-dot,
.ms-trip-tab:has(input:checked) .ms-radio-dot {
    opacity: 1;
    transform: scale(1);
}
.ms-header-title {
    font-size: 20px;
    font-weight: 800;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
    letter-spacing: -0.2px;
}

/* Inner White Card */
.ms-card-inner {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
    overflow: visible;
    position: relative;
}
.ms-form-grid {
    display: grid;
    grid-template-columns: 1.8fr auto 1.8fr 1.3fr 1.3fr 1.5fr auto;
    gap: 0;
    align-items: center;
    padding: 6px 10px;
}
.ms-field {
    padding: 10px 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    border-right: 1px solid #f1f5f9;
    min-height: 64px;
    box-sizing: border-box;
}
.ms-field:last-of-type {
    border-right: none;
}
.ms-field-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 700;
    display: flex;
    align-items: center;
    margin-bottom: 2px;
    user-select: none;
}
.ms-chevron {
    font-size: 9px;
    color: #94a3b8;
    margin-left: 4px;
}
.ms-city-input {
    border: none;
    outline: none;
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    background: transparent;
    width: 100%;
    padding: 0;
    font-family: inherit;
    line-height: 1.2;
}
.ms-field-sub {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ms-swap-btn {
    background: #0ea5e9;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 8px rgba(14, 165, 233, 0.45);
    transition: all 0.25s ease;
    margin: 0 -18px;
    z-index: 5;
}
.ms-swap-btn:hover {
    background: #0284c7;
    transform: rotate(180deg) scale(1.05);
}
.ms-field-to {
    padding-left: 26px;
}
.ms-field-date {
    position: relative;
    cursor: pointer;
}
.ms-date-value {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.ms-clear-return {
    cursor: pointer;
    transition: color 0.2s;
}
.ms-clear-return:hover {
    color: #ef4444 !important;
}
.ms-field-pax {
    cursor: pointer;
}
.ms-traveller-display {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.ms-action-wrap {
    padding: 6px 8px 6px 12px;
    display: flex;
    align-items: center;
}
.ms-search-btn {
    background: #ff3b30;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 14px 26px;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.5px;
    cursor: pointer;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(255, 59, 48, 0.4);
    transition: all 0.2s ease;
    font-family: inherit;
}
.ms-search-btn:hover {
    background: #e0281c;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(255, 59, 48, 0.5);
}

/* Airport suggestion dropdown inside Modify Search */
.ms-airport-suggest {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 280px;
    max-height: 260px;
    overflow-y: auto;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    z-index: 10000;
    margin-top: 6px;
}
.ms-suggest-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.ms-suggest-item:hover {
    background: #f8fafc;
}

/* Travellers popup */
.ms-traveller-popup {
    display: none;
    position: absolute;
    top: 100%;
    right: 80px;
    min-width: 270px;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    padding: 16px;
    z-index: 10000;
    margin-top: 6px;
}
.ms-pax-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}
.ms-pax-row small { display: block; color: #94a3b8; font-size: 10px; }
.ms-pax-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}
.ms-pax-controls button {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    font-weight: 700;
    cursor: pointer;
}
.ms-pax-controls button:hover {
    background: #0d3470;
    color: #fff;
    border-color: #0d3470;
}
.ms-pax-done {
    width: 100%;
    background: #0d3470;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px;
    font-weight: 700;
    margin-top: 10px;
    cursor: pointer;
}

/* Bottom Bar: Special Fares */
.ms-bottom-bar {
    border-top: 1px solid #f1f5f9;
    padding: 10px 18px;
    display: flex;
    align-items: center;
    gap: 22px;
    flex-wrap: wrap;
    background: #ffffff;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
}
.ms-bottom-bar.is-multicity .ms-fare-direct {
    display: none !important;
}
.ms-fare-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    user-select: none;
}
.ms-fare-checkbox input {
    width: 15px;
    height: 15px;
    border-radius: 4px;
    accent-color: #0ea5e9;
    cursor: pointer;
}

/* Dual Month Interactive Calendar Popup */
.ms-calendar-popup {
    display: none;
    position: absolute;
    top: 36px;
    left: 42%;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22);
    border: 1px solid #cbd5e1;
    padding: 0;
    z-index: 999999;
    width: 590px;
    max-width: 96vw;
    box-sizing: border-box;
    animation: fadeInModal 0.18s ease-out forwards;
    overflow: hidden;
}
.ms-calendar-popup.open {
    display: block !important;
}
.ms-cal-header-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
    margin-bottom: 0;
}
.ms-cal-tab {
    flex: 1;
    padding: 8px 18px 10px 18px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    border-bottom: 3px solid transparent;
    transition: all 0.15s ease;
    position: relative;
}
.ms-cal-tab:first-child {
    border-right: 1px solid #e2e8f0;
    border-top-left-radius: 14px;
}
.ms-cal-tab:last-child {
    border-top-right-radius: 14px;
}
.ms-cal-tab.active {
    border-bottom-color: #ff3b30;
}
.ms-cal-tab-label {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.ms-cal-tab-date {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ms-cal-clear-ret {
    font-size: 13px;
    color: #94a3b8;
    cursor: pointer;
    transition: color 0.15s;
}
.ms-cal-clear-ret:hover {
    color: #ef4444;
}
.ms-cal-months-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding: 14px 18px 6px 18px;
    gap: 0;
}
.ms-cal-month-box:first-child {
    border-right: 1px solid #f1f5f9;
    padding-right: 16px;
}
.ms-cal-month-box:last-child {
    padding-left: 16px;
}
.ms-cal-month-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    min-height: 28px;
}
.ms-cal-month-title {
    font-size: 13px;
    font-weight: 800;
    color: #ea580c;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
    flex: 1;
}
.ms-cal-nav-btn {
    background: transparent;
    border: none;
    color: #475569;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px 6px;
    transition: all 0.2s;
}
.ms-cal-nav-btn:hover:not(:disabled) {
    color: #0ea5e9;
    transform: scale(1.15);
}
.ms-cal-nav-btn:disabled {
    opacity: 0.25;
    cursor: not-allowed;
}
.ms-cal-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.ms-cal-table th {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
    text-align: center;
    padding-bottom: 6px;
}
.ms-cal-table td {
    padding: 1.5px 1px;
    text-align: center;
    height: 38px;
}
.ms-cal-day-btn {
    width: 100%;
    height: 100%;
    border: none;
    background: transparent;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1px 0;
    font-family: inherit;
    transition: background 0.15s, color 0.15s;
    box-sizing: border-box;
}
.ms-cal-day-num {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.1;
}
.ms-cal-table tr td:first-child .ms-cal-day-num {
    color: #dc2626;
}
.ms-cal-day-fare {
    font-size: 9px;
    font-weight: 600;
    color: #64748b;
    line-height: 1;
    margin-top: 2px;
}
.ms-cal-day-fare.lowest {
    color: #16a34a !important;
    font-weight: 800;
}
.ms-cal-day-btn:hover:not(:disabled) {
    background: #e0f2fe;
}
.ms-cal-day-btn.disabled {
    cursor: default;
}
.ms-cal-day-btn.disabled .ms-cal-day-num {
    color: #94a3b8;
}
.ms-cal-day-btn.is-today {
    background: #e2e8f0;
    border-radius: 4px;
}
.ms-cal-day-btn.selected-dep,
.ms-cal-day-btn.selected-ret {
    background: #0d3470 !important;
    color: #ffffff !important;
    border-radius: 4px;
}
.ms-cal-day-btn.selected-dep .ms-cal-day-num,
.ms-cal-day-btn.selected-ret .ms-cal-day-num,
.ms-cal-day-btn.selected-dep .ms-cal-day-fare,
.ms-cal-day-btn.selected-ret .ms-cal-day-fare {
    color: #ffffff !important;
}
.ms-cal-day-btn.in-range {
    background: #dbeafe !important;
    border-radius: 0;
}
.ms-cal-day-btn.in-range .ms-cal-day-num {
    color: #1e3a8a;
}
.ms-cal-bottom-note {
    text-align: right;
    font-size: 11px;
    color: #ef4444;
    font-weight: 600;
    padding: 4px 18px 8px 18px;
}

/* Multi-City Form Grid */
.ms-multicity-grid {
    display: none;
}
.ms-multicity-grid.active {
    display: block !important;
}
.ms-multi-row {
    display: grid;
    grid-template-columns: 1.8fr 1.8fr 1.3fr 1.6fr;
    gap: 0;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    padding: 4px 10px;
    position: relative;
}
.ms-multi-row:last-child {
    border-bottom: none;
}
.ms-multi-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: flex-end;
    padding: 8px 14px;
    height: 100%;
    box-sizing: border-box;
}
.ms-btn-add-city {
    background: #ffffff;
    color: #0099ff;
    border: 1.5px solid #0099ff;
    border-radius: 6px;
    padding: 11px 18px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: all 0.2s;
}
.ms-btn-add-city:hover {
    background: #f0f9ff;
    border-color: #0284c7;
    color: #0284c7;
}
.ms-remove-leg-btn {
    position: absolute;
    right: -8px;
    top: 50%;
    transform: translateY(-50%);
    background: #fee2e2;
    color: #ef4444;
    border: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    font-size: 11px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    z-index: 10;
}
.ms-remove-leg-btn:hover {
    background: #ef4444;
    color: #ffffff;
}

@media (max-width: 992px) {
    .modify-search-modal {
        padding: 20px 16px;
    }
    .ms-form-grid, .ms-multi-row {
        grid-template-columns: 1fr;
        gap: 8px;
        padding: 10px;
    }
    .ms-field {
        border-right: none;
        border-bottom: 1px solid #f1f5f9;
        padding: 8px 10px;
    }
    .ms-swap-btn {
        margin: 4px auto;
    }
    .ms-field-to {
        padding-left: 10px;
    }
    .ms-search-btn {
        width: 100%;
        justify-content: center;
    }
    .ms-header-title {
        display: none;
    }
    .ms-close-btn {
        top: 10px;
        right: 10px;
    }
    .ms-calendar-popup {
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 95% !important;
    }
    .ms-cal-months-grid {
        grid-template-columns: 1fr;
    }
}

/* Sort tabs */
.f-sort-tab {
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    cursor: pointer;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.f-sort-tab.active {
    background: #78B722;
    color: #ffffff;
}

/* Flight Item Card */
.f-flight-item {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.15s;
}
.f-flight-item:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}

/* Booking button */
.f-book-btn {
    background: #78B722;
    color: #ffffff;
    border: none;
    padding: 9px 20px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s;
    box-shadow: 0 2px 6px rgba(120, 183, 34, 0.25);
}
.f-book-btn:hover {
    background: #6aa31e;
}

/* Round Trip Cards */
.rt-card {
    display: block;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s;
    background: #ffffff;
}
.rt-card.selected-rt-card {
    border-color: #78B722;
    background: #f0fdf4;
}
</style>
