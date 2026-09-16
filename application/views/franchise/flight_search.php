<?php
$origCode   = $search_query['from_code'] ?? 'DEL';
$destCode   = $search_query['to_code'] ?? 'BOM';
$departDate = $search_query['depart_date'] ?? date('Y-m-d', strtotime('+3 days'));
$returnDate = $search_query['return_date'] ?? date('Y-m-d', strtotime('+7 days'));
$tripType   = $search_query['trip_type'] ?? 'oneway';
$is_roundtrip = !empty($is_roundtrip);
$paxCount   = ((int)($search_query['adults'] ?? 1)) + ((int)($search_query['children'] ?? 0)) + ((int)($search_query['infants'] ?? 0));
$cabinClass = $search_query['cabin'] ?? 'Economy';
$onwardList = !empty($onwardFlights) ? $onwardFlights : (!empty($flights) ? $flights : array());
$returnList = !empty($returnFlights) ? $returnFlights : array();
$activeTui  = $search_tui ?? ($search_query['tui'] ?? '');

// Calculate airline counts and min price for filters
$airlineCounts = array();
$minPriceFound = 999999;
$maxPriceFound = 0;
$nonStopCount  = 0;
$oneStopCount  = 0;

foreach ($onwardList as $f) {
    $c = $f['airline_code'] ?? '6E';
    $airlineCounts[$c] = ($airlineCounts[$c] ?? 0) + 1;
    $p = (float)($f['price'] ?? 4999);
    if ($p < $minPriceFound) $minPriceFound = $p;
    if ($p > $maxPriceFound) $maxPriceFound = $p;
    if (($f['stops'] ?? 0) == 0) $nonStopCount++;
    else $oneStopCount++;
}
if ($minPriceFound > $maxPriceFound) {
    $minPriceFound = 3500;
    $maxPriceFound = 12000;
}
?>

<div style="margin-bottom: 24px;">
    <!-- Top Search Header Box (Identical to voyogos.com/flight) -->
    <div style="background: linear-gradient(135deg, #09204b 0%, #0d3470 100%); border-radius: 14px; padding: 18px 24px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 16px rgba(9, 32, 75, 0.15);">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <!-- From -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">From</span>
                <strong style="font-size: 19px; font-weight: 800; letter-spacing: -0.3px;"><?php echo htmlspecialchars($search_query['origin']); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo $origCode; ?>, Airport</span>
            </div>

            <div style="color: #78B722; font-size: 18px;">
                <i class="fa-solid fa-<?php echo $is_roundtrip ? 'arrow-right-arrow-left' : 'plane'; ?>"></i>
            </div>

            <!-- To -->
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #93c5fd; font-weight: 700; display: block;">To</span>
                <strong style="font-size: 19px; font-weight: 800; letter-spacing: -0.3px;"><?php echo htmlspecialchars($search_query['destination']); ?></strong>
                <span style="font-size: 12px; color: #cbd5e1; display: block;"><?php echo $destCode; ?>, Airport</span>
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
            <a href="<?php echo site_url('franchise/flight'); ?>" style="background: #ffffff; color: #09204b; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.2s;">
                <i class="fa-solid fa-pen-to-square"></i> MODIFY SEARCH
            </a>
        </div>
    </div>
</div>

<!-- Main Search Layout: Sidebar + Flight Results -->
<div style="display: grid; grid-template-columns: 270px 1fr; gap: 24px; align-items: start;">

    <!-- Left Interactive Filters Sidebar -->
    <aside style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-sliders" style="color: #78B722;"></i> Filters
            </h3>
            <span onclick="resetFlightFilters()" style="font-size: 12px; color: #2563eb; font-weight: 600; cursor: pointer;">Reset All</span>
        </div>

        <!-- 1. Stops Filter -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Stops</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div class="f-stop-pill active" data-stop="0" onclick="toggleStopFilter(0, this)">
                    <div style="font-weight: 700; font-size: 13px;">Non Stop</div>
                    <div style="font-size: 11px; opacity: 0.8;"><?php echo $nonStopCount; ?> Flights</div>
                </div>
                <div class="f-stop-pill active" data-stop="1" onclick="toggleStopFilter(1, this)">
                    <div style="font-weight: 700; font-size: 13px;">1+ Stops</div>
                    <div style="font-size: 11px; opacity: 0.8;"><?php echo $oneStopCount; ?> Flights</div>
                </div>
            </div>
        </div>

        <!-- 2. Airlines Filter with Logos & Counts -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Airlines</div>
            <div style="display: flex; flex-direction: column; gap: 10px;" id="airlineFilterList">
                <?php 
                $uniqueAirlines = array();
                foreach ($onwardList as $f) {
                    $ac = $f['airline_code'] ?? '6E';
                    if (!isset($uniqueAirlines[$ac])) {
                        $uniqueAirlines[$ac] = array(
                            'code'  => $ac,
                            'name'  => $f['airline_name'] ?? ($f['airline'] ?? 'Airline'),
                            'logo'  => $f['airline_logo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png',
                            'count' => $airlineCounts[$ac] ?? 1
                        );
                    }
                }
                foreach ($uniqueAirlines as $ac => $ua):
                ?>
                <label style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; color: #334155; cursor: pointer; padding: 4px 0;">
                    <span style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" class="airline-filter-checkbox" value="<?php echo htmlspecialchars($ac); ?>" checked onchange="filterFlights()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                        <img src="<?php echo htmlspecialchars($ua['logo']); ?>" alt="<?php echo htmlspecialchars($ua['name']); ?>" style="width: 20px; height: 20px; object-fit: contain;">
                        <span style="font-weight: 600;"><?php echo htmlspecialchars($ua['name']); ?></span>
                    </span>
                    <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">(<?php echo $ua['count']; ?>)</span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 3. Departure Times Filter -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Departure Times</div>
            <div style="font-size: 11px; color: #64748b; margin-bottom: 10px;">From <?php echo $origCode; ?></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div class="f-time-btn" data-time="early" onclick="toggleTimeFilter('early', this)">
                    <i class="fa-regular fa-sun" style="color: #f59e0b;"></i> Before 6 AM
                </div>
                <div class="f-time-btn" data-time="morning" onclick="toggleTimeFilter('morning', this)">
                    <i class="fa-solid fa-sun" style="color: #f59e0b;"></i> 6 AM - 12 PM
                </div>
                <div class="f-time-btn" data-time="afternoon" onclick="toggleTimeFilter('afternoon', this)">
                    <i class="fa-solid fa-cloud-sun" style="color: #ea580c;"></i> 12 PM - 6 PM
                </div>
                <div class="f-time-btn" data-time="night" onclick="toggleTimeFilter('night', this)">
                    <i class="fa-solid fa-moon" style="color: #6366f1;"></i> After 6 PM
                </div>
            </div>
        </div>

        <!-- 4. Price Range Slider -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">Max Price</span>
                <span style="font-size: 13px; font-weight: 800; color: #09204b;" id="priceDisplay">₹ <?php echo number_format($maxPriceFound); ?></span>
            </div>
            <input type="range" id="priceRange" min="<?php echo (int)$minPriceFound; ?>" max="<?php echo (int)$maxPriceFound; ?>" value="<?php echo (int)$maxPriceFound; ?>" step="100" oninput="updatePriceFilter(this.value)" style="width: 100%; accent-color: #78B722; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; margin-top: 4px;">
                <span>₹ <?php echo number_format($minPriceFound); ?></span>
                <span>₹ <?php echo number_format($maxPriceFound); ?></span>
            </div>
        </div>

        <!-- B2B Special Tag -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; text-align: center;">
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
                        <strong style="font-size: 15px;"><?php echo $origCode; ?> &rarr; <?php echo $destCode; ?></strong>
                    </div>
                    <span style="font-size: 12px; background: rgba(255,255,255,0.15); padding: 3px 8px; border-radius: 4px; font-weight: 700;">
                        <?php echo date('d M, D', strtotime($departDate)); ?>
                    </span>
                </div>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="onwardFlightGroup">
                    <?php foreach ($onwardList as $idx => $f): ?>
                    <label class="rt-card onward-card <?php echo ($idx === 0) ? 'selected-rt-card' : ''; ?>" data-idx="<?php echo $idx; ?>" data-stops="<?php echo (int)($f['stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($f['airline_code'] ?? '6E'); ?>" data-price="<?php echo (float)$f['price']; ?>" data-time="<?php echo htmlspecialchars($f['departure_time']); ?>">
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
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['departure_time']); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo $origCode; ?></span>
                            </div>
                            <div style="text-align: center; color: #64748b; font-size: 11px;">
                                <span><?php echo htmlspecialchars($f['duration']); ?></span>
                                <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                <span style="color: <?php echo ($f['stops'] == 0) ? '#166534' : '#d97706'; ?>; font-weight: 700;">
                                    <?php echo ($f['stops'] == 0) ? 'Non Stop' : ($f['stops'] . ' Stop'); ?>
                                </span>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['arrival_time']); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo $destCode; ?></span>
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
                        <strong style="font-size: 15px;"><?php echo $destCode; ?> &rarr; <?php echo $origCode; ?></strong>
                    </div>
                    <span style="font-size: 12px; background: rgba(255,255,255,0.15); padding: 3px 8px; border-radius: 4px; font-weight: 700;">
                        <?php echo date('d M, D', strtotime($returnDate)); ?>
                    </span>
                </div>

                <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="returnFlightGroup">
                    <?php foreach ($returnList as $rIdx => $rf): ?>
                    <label class="rt-card return-card <?php echo ($rIdx === 0) ? 'selected-rt-card' : ''; ?>" data-idx="<?php echo $rIdx; ?>" data-stops="<?php echo (int)($rf['stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($rf['airline_code'] ?? '6E'); ?>" data-price="<?php echo (float)$rf['price']; ?>" data-time="<?php echo htmlspecialchars($rf['departure_time']); ?>">
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
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['departure_time']); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo $destCode; ?></span>
                            </div>
                            <div style="text-align: center; color: #64748b; font-size: 11px;">
                                <span><?php echo htmlspecialchars($rf['duration']); ?></span>
                                <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                <span style="color: <?php echo ($rf['stops'] == 0) ? '#166534' : '#d97706'; ?>; font-weight: 700;">
                                    <?php echo ($rf['stops'] == 0) ? 'Non Stop' : ($rf['stops'] . ' Stop'); ?>
                                </span>
                            </div>
                            <div style="text-align: right;">
                                <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['arrival_time']); ?></strong>
                                <span style="color: #64748b; font-size: 11px; display: block;"><?php echo $origCode; ?></span>
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
        <!-- ONE WAY FLIGHT LIST (Identical richness to voyogos.com/flight)   -->
        <!-- ================================================================ -->
        <div id="flightListContainer" style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($onwardList as $f): 
                $fId        = $f['id'] ?? 'FL_101';
                $alCode     = $f['airline_code'] ?? '6E';
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
            ?>
            <div class="f-flight-item" data-price="<?php echo $grossPrice; ?>" data-stops="<?php echo $stops; ?>" data-airline="<?php echo htmlspecialchars($alCode); ?>" data-time="<?php echo htmlspecialchars($depTime); ?>">
                
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
                            <span style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo $origCode; ?></span>
                            <span style="font-size: 11px; color: #94a3b8; display: block;">Airport</span>
                        </div>

                        <!-- Duration Path -->
                        <div style="display: flex; flex-direction: column; align-items: center; width: 140px;">
                            <span style="font-size: 11.5px; color: #64748b; font-weight: 600;"><?php echo htmlspecialchars($duration); ?></span>
                            <div style="width: 100%; height: 2px; background: #cbd5e1; position: relative; margin: 6px 0;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%); font-size: 11px; color: #78B722; background: #ffffff; padding: 0 4px;"></i>
                            </div>
                            <span style="font-size: 11px; font-weight: 700; color: <?php echo ($stops == 0) ? '#166534' : '#d97706'; ?>;">
                                <?php echo ($stops == 0) ? 'Non Stop' : ($stops . ' Stop'); ?>
                            </span>
                        </div>

                        <!-- Arr -->
                        <div style="text-align: right;">
                            <strong style="font-size: 22px; color: #0f172a; display: block; font-weight: 800;"><?php echo htmlspecialchars($arrTime); ?></strong>
                            <span style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo $destCode; ?></span>
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

<!-- Interactive Client-side Filter & Sort Script (Parity with voyogos.com/flight) -->
<script>
const onwardFlightsData = <?php echo json_encode($onwardList); ?>;
const returnFlightsData = <?php echo json_encode($returnList); ?>;

let activeStops = [0, 1];
let activeTimeFilters = [];
let maxPriceFilter = <?php echo (int)$maxPriceFound; ?>;

function toggleStopFilter(stopVal, elem) {
    if (elem.classList.contains('active')) {
        elem.classList.remove('active');
        activeStops = activeStops.filter(s => s !== stopVal);
    } else {
        elem.classList.add('active');
        activeStops.push(stopVal);
    }
    filterFlights();
}

function toggleTimeFilter(timeVal, elem) {
    if (elem.classList.contains('active')) {
        elem.classList.remove('active');
        activeTimeFilters = activeTimeFilters.filter(t => t !== timeVal);
    } else {
        elem.classList.add('active');
        activeTimeFilters.push(timeVal);
    }
    filterFlights();
}

function updatePriceFilter(val) {
    maxPriceFilter = parseFloat(val);
    document.getElementById('priceDisplay').innerText = '₹ ' + parseInt(val).toLocaleString('en-IN');
    filterFlights();
}

function filterFlights() {
    const selectedAirlines = Array.from(document.querySelectorAll('.airline-filter-checkbox:checked')).map(cb => cb.value);

    // Filter One-way cards
    let visibleCount = 0;
    document.querySelectorAll('.f-flight-item').forEach(card => {
        const stops   = parseInt(card.getAttribute('data-stops') || 0);
        const airline = card.getAttribute('data-airline');
        const price   = parseFloat(card.getAttribute('data-price') || 0);
        const timeStr = card.getAttribute('data-time') || '06:00';
        const hour    = parseInt(timeStr.split(':')[0]);

        // Stop match
        const stopMatch = (activeStops.length === 0) || (stops === 0 && activeStops.includes(0)) || (stops > 0 && activeStops.includes(1));
        // Airline match
        const airlineMatch = selectedAirlines.length === 0 || selectedAirlines.includes(airline);
        // Price match
        const priceMatch = price <= maxPriceFilter;
        // Time match
        let timeMatch = true;
        if (activeTimeFilters.length > 0) {
            timeMatch = false;
            if (activeTimeFilters.includes('early') && hour < 6) timeMatch = true;
            if (activeTimeFilters.includes('morning') && hour >= 6 && hour < 12) timeMatch = true;
            if (activeTimeFilters.includes('afternoon') && hour >= 12 && hour < 18) timeMatch = true;
            if (activeTimeFilters.includes('night') && hour >= 18) timeMatch = true;
        }

        if (stopMatch && airlineMatch && priceMatch && timeMatch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const countEl = document.getElementById('resultsCountText');
    if (countEl) countEl.innerText = `Showing ${visibleCount} Flights`;

    // Filter Round-trip items
    document.querySelectorAll('.onward-card, .return-card').forEach(card => {
        const stops   = parseInt(card.getAttribute('data-stops') || 0);
        const airline = card.getAttribute('data-airline');
        const price   = parseFloat(card.getAttribute('data-price') || 0);
        const timeStr = card.getAttribute('data-time') || '06:00';
        const hour    = parseInt(timeStr.split(':')[0]);

        const stopMatch = (activeStops.length === 0) || (stops === 0 && activeStops.includes(0)) || (stops > 0 && activeStops.includes(1));
        const airlineMatch = selectedAirlines.length === 0 || selectedAirlines.includes(airline);
        const priceMatch = price <= maxPriceFilter;

        let timeMatch = true;
        if (activeTimeFilters.length > 0) {
            timeMatch = false;
            if (activeTimeFilters.includes('early') && hour < 6) timeMatch = true;
            if (activeTimeFilters.includes('morning') && hour >= 6 && hour < 12) timeMatch = true;
            if (activeTimeFilters.includes('afternoon') && hour >= 12 && hour < 18) timeMatch = true;
            if (activeTimeFilters.includes('night') && hour >= 18) timeMatch = true;
        }

        card.style.display = (stopMatch && airlineMatch && priceMatch && timeMatch) ? 'block' : 'none';
    });
}

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

function resetFlightFilters() {
    activeStops = [0, 1];
    activeTimeFilters = [];
    document.querySelectorAll('.f-stop-pill').forEach(p => p.classList.add('active'));
    document.querySelectorAll('.f-time-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.airline-filter-checkbox').forEach(cb => cb.checked = true);
    const range = document.getElementById('priceRange');
    if (range) {
        range.value = range.max;
        updatePriceFilter(range.max);
    }
    filterFlights();
}

// Round-trip sticky bar update
function updateRoundTripSelection() {
    const onwardRadio = document.querySelector('input[name="selected_onward_idx"]:checked');
    const returnRadio = document.querySelector('input[name="selected_return_idx"]:checked');

    const oIdx = onwardRadio ? parseInt(onwardRadio.value) : 0;
    const rIdx = returnRadio ? parseInt(returnRadio.value) : 0;

    const o = onwardFlightsData[oIdx] || onwardFlightsData[0];
    const r = returnFlightsData[rIdx] || returnFlightsData[0];

    // Card border states
    document.querySelectorAll('.onward-card').forEach((card, idx) => {
        if (idx === oIdx) {
            card.classList.add('selected-rt-card');
        } else {
            card.classList.remove('selected-rt-card');
        }
    });

    document.querySelectorAll('.return-card').forEach((card, idx) => {
        if (idx === rIdx) {
            card.classList.add('selected-rt-card');
        } else {
            card.classList.remove('selected-rt-card');
        }
    });

    if (o) {
        document.getElementById('barOnwardAirline').textContent = o.airline_name + ' ' + o.flight_number;
        document.getElementById('barOnwardTime').textContent = o.departure_time + ' - ' + o.arrival_time + ' (' + o.from_code + ' → ' + o.to_code + ')';
        
        document.getElementById('rt_flight_id').value = o.id || 'FL_101';
        document.getElementById('rt_airline_name').value = o.airline_name;
        document.getElementById('rt_flight_number').value = o.flight_number;
        document.getElementById('rt_airline_code').value = o.airline_code;
        document.getElementById('rt_airline_logo').value = o.airline_logo;
        document.getElementById('rt_dep_time').value = o.departure_time;
        document.getElementById('rt_arr_time').value = o.arrival_time;
        document.getElementById('rt_onward_price').value = o.price;
    }

    if (r) {
        document.getElementById('barReturnAirline').textContent = r.airline_name + ' ' + r.flight_number;
        document.getElementById('barReturnTime').textContent = r.departure_time + ' - ' + r.arrival_time + ' (' + r.from_code + ' → ' + r.to_code + ')';

        document.getElementById('rt_ret_airline_name').value = r.airline_name;
        document.getElementById('rt_ret_flight_number').value = r.flight_number;
        document.getElementById('rt_ret_dep_time').value = r.departure_time;
        document.getElementById('rt_ret_arr_time').value = r.arrival_time;
        document.getElementById('rt_ret_price').value = r.price;
    }

    const total = (o ? parseFloat(o.price) : 0) + (r ? parseFloat(r.price) : 0);
    document.getElementById('barTotalPrice').textContent = '₹ ' + total.toLocaleString('en-IN');
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if ($is_roundtrip): ?>
    updateRoundTripSelection();
    <?php endif; ?>
});
</script>

<style>
/* Stop filter pills */
.f-stop-pill {
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 8px 10px;
    text-align: center;
    cursor: pointer;
    background: #ffffff;
    color: #475569;
    transition: all 0.15s;
}
.f-stop-pill.active {
    border-color: #78B722;
    background: #f0fdf4;
    color: #166534;
}

/* Time filter buttons */
.f-time-btn {
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 6px;
    text-align: center;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.15s;
}
.f-time-btn.active {
    border-color: #09204b;
    background: #09204b;
    color: #ffffff;
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
