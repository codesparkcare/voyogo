<!-- Main Wrapper -->
<div style="background-color: #f5f7fa; padding-bottom: 90px;">
    
    <!-- Top Search Header Box -->
    <div class="search-header-container">
        <div class="container">
            <div class="search-header-box">
                <div class="sh-block">
                    <span class="sh-label">From</span>
                    <strong class="sh-title"><?php echo htmlspecialchars($search_query['from']); ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['from_code']); ?>, Airport</span>
                </div>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">To</span>
                    <strong class="sh-title"><?php echo htmlspecialchars($search_query['to']); ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['to_code']); ?>, Airport</span>
                </div>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Departure</span>
                    <strong class="sh-title"><?php echo date('d M\'y', strtotime($search_query['date'])); ?></strong>
                    <span class="sh-sub"><?php echo date('l', strtotime($search_query['date'])); ?></span>
                </div>
                <?php if (!empty($is_roundtrip) && !empty($search_query['return_date'])): ?>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Return</span>
                    <strong class="sh-title" style="color: #2563eb;"><?php echo date('d M\'y', strtotime($search_query['return_date'])); ?></strong>
                    <span class="sh-sub"><?php echo date('l', strtotime($search_query['return_date'])); ?></span>
                </div>
                <?php endif; ?>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Travellers & Class</span>
                    <?php 
                        $total_travelers = ($search_query['adults'] ?? 1) + ($search_query['children'] ?? 0) + ($search_query['infants'] ?? 0);
                    ?>
                    <strong class="sh-title"><?php echo sprintf('%02d', $total_travelers); ?> Traveller<?php echo $total_travelers > 1 ? 's' : ''; ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?></span>
                </div>
                <div class="sh-action">
                    <a href="<?php echo site_url('flight'); ?>" class="btn-modify">MODIFY SEARCH <i class="fa-solid fa-magnifying-glass"></i></a>
                </div>
            </div>
        </div>
    </div>

    <?php 
    $airlineMap = array(
        '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
        'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
        'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
        'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
        'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
        'I5' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
        'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png')
    );

    // Normalize Onward Flights
    $onwardFlights = array();
    $activeSearchTui = !empty($search_tui) ? $search_tui : ($search_query['tui'] ?? '');

    if (!empty($flightResults) && is_array($flightResults)) {
        if (isset($flightResults['Flights']) && is_array($flightResults['Flights'])) {
            $rawList = $flightResults['Flights'];
        } elseif (isset($flightResults['Trips'][0]['Journey']) && is_array($flightResults['Trips'][0]['Journey'])) {
            $rawList = $flightResults['Trips'][0]['Journey'];
        } elseif (isset($flightResults['Trips'][0]['Journeys']) && is_array($flightResults['Trips'][0]['Journeys'])) {
            $rawList = $flightResults['Trips'][0]['Journeys'];
        } elseif (isset($flightResults[0]) && is_array($flightResults[0])) {
            $rawList = $flightResults;
        } else {
            $rawList = array($flightResults);
        }

        foreach ($rawList as $idx => $item) {
            if (!is_array($item)) continue;
            if (isset($item['flight_number']) || isset($item['airline_name']) || isset($item['price'])) {
                $code = isset($item['airline_code']) ? strtoupper($item['airline_code']) : '6E';
                $defaultLogo = isset($airlineMap[$code]) ? $airlineMap[$code]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
                $defaultName = isset($airlineMap[$code]) ? $airlineMap[$code]['name'] : ($code . ' Airlines');

                $onwardFlights[] = array(
                    'ResultID' => (!empty($item['tui']) && strpos($item['tui'], 'FL_') !== 0) ? $item['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ($item['ResultID'] ?? ('FL_' . ($idx + 100)))),
                    'AirlineCode' => $code,
                    'AirlineName' => $item['airline_name'] ?? $defaultName,
                    'AirlineLogo' => !empty($item['airline_logo']) ? $item['airline_logo'] : $defaultLogo,
                    'FlightNumber' => $item['flight_number'] ?? ($code . '-' . (2000 + $idx)),
                    'FromCode' => $item['from_code'] ?? $search_query['from_code'],
                    'ToCode' => $item['to_code'] ?? $search_query['to_code'],
                    'DepartureTime' => $item['departure_time'] ?? '06:00',
                    'ArrivalTime' => $item['arrival_time'] ?? '08:15',
                    'Duration' => $item['duration'] ?? '2h 15m',
                    'Stops' => isset($item['stops']) ? (int)$item['stops'] : 0,
                    'Price' => (float)($item['price'] ?? 4999),
                    'Baggage' => $item['checkin_baggage'] ?? $item['baggage'] ?? '15 Kgs',
                    'Refundable' => isset($item['refundable']) ? $item['refundable'] : true,
                    'SeatsLeft' => $item['seats_left'] ?? rand(3, 9)
                );
            } elseif (isset($item['AirlineName']) || isset($item['FlightNumber']) || isset($item['Price'])) {
                $code = isset($item['AirlineCode']) ? strtoupper($item['AirlineCode']) : '6E';
                $onwardFlights[] = array(
                    'ResultID' => (!empty($item['ResultID']) && strpos($item['ResultID'], 'FL_') !== 0) ? $item['ResultID'] : (!empty($item['tui']) && strpos($item['tui'], 'FL_') !== 0 ? $item['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ('FL_' . ($idx + 100)))),
                    'AirlineCode' => $code,
                    'AirlineName' => $item['AirlineName'] ?? 'IndiGo',
                    'AirlineLogo' => $item['AirlineLogo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png',
                    'FlightNumber' => $item['FlightNumber'] ?? ($code . '-' . (2000 + $idx)),
                    'FromCode' => $item['FromCode'] ?? $search_query['from_code'],
                    'ToCode' => $item['ToCode'] ?? $search_query['to_code'],
                    'DepartureTime' => $item['DepartureTime'] ?? '06:00',
                    'ArrivalTime' => $item['ArrivalTime'] ?? '08:15',
                    'Duration' => $item['Duration'] ?? '2h 15m',
                    'Stops' => isset($item['Stops']) ? (int)$item['Stops'] : 0,
                    'Price' => (float)($item['Price'] ?? 4999),
                    'Baggage' => $item['Baggage'] ?? '15 Kgs',
                    'Refundable' => isset($item['Refundable']) ? $item['Refundable'] : true,
                    'SeatsLeft' => $item['SeatsLeft'] ?? rand(3, 9)
                );
            }
        }
    }

    if (empty($onwardFlights)) {
        $mockAirlines = array(
            array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2134', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '06:00', 'arr' => '08:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5150),
            array('code' => 'SG', 'name' => 'SpiceJet', 'flight_no' => 'SG-162', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png', 'dep' => '09:30', 'arr' => '11:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4999),
            array('code' => 'AI', 'name' => 'Air India', 'flight_no' => 'AI-805', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png', 'dep' => '14:15', 'arr' => '16:30', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5450),
            array('code' => 'QP', 'name' => 'Akasa Air', 'flight_no' => 'QP-1311', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png', 'dep' => '18:20', 'arr' => '20:35', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4850),
            array('code' => 'UK', 'name' => 'Vistara', 'flight_no' => 'UK-945', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png', 'dep' => '20:45', 'arr' => '23:00', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5800)
        );
        foreach ($mockAirlines as $mIdx => $m) {
            $onwardFlights[] = array(
                'ResultID' => !empty($activeSearchTui) ? $activeSearchTui : ('FL_' . ($mIdx + 100)),
                'AirlineCode' => $m['code'],
                'AirlineName' => $m['name'],
                'AirlineLogo' => $m['logo'],
                'FlightNumber' => $m['flight_no'],
                'FromCode' => $search_query['from_code'],
                'ToCode' => $search_query['to_code'],
                'DepartureTime' => $m['dep'],
                'ArrivalTime' => $m['arr'],
                'Duration' => $m['dur'],
                'Stops' => $m['stops'],
                'Price' => $m['price'],
                'Baggage' => '15 Kgs',
                'Refundable' => true,
                'SeatsLeft' => rand(3, 9)
            );
        }
    }

    // Normalize Return Flights (for Round Trip)
    $inboundFlights = array();
    if (!empty($is_roundtrip)) {
        if (!empty($returnFlights) && is_array($returnFlights)) {
            $rawRetList = (isset($returnFlights['Flights'])) ? $returnFlights['Flights'] : (isset($returnFlights[0]) ? $returnFlights : array($returnFlights));
            foreach ($rawRetList as $rIdx => $rItem) {
                if (!is_array($rItem)) continue;
                $rCode = isset($rItem['airline_code']) ? strtoupper($rItem['airline_code']) : (isset($rItem['AirlineCode']) ? strtoupper($rItem['AirlineCode']) : '6E');
                $inboundFlights[] = array(
                    'ResultID' => (!empty($rItem['tui']) && strpos($rItem['tui'], 'FL_') !== 0) ? $rItem['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ('FL_RET_' . ($rIdx + 100))),
                    'AirlineCode' => $rCode,
                    'AirlineName' => $rItem['airline_name'] ?? ($airlineMap[$rCode]['name'] ?? 'IndiGo'),
                    'AirlineLogo' => $rItem['airline_logo'] ?? ($airlineMap[$rCode]['logo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
                    'FlightNumber' => $rItem['flight_number'] ?? ($rCode . '-' . (3000 + $rIdx)),
                    'FromCode' => $search_query['to_code'],
                    'ToCode' => $search_query['from_code'],
                    'DepartureTime' => $rItem['departure_time'] ?? '18:00',
                    'ArrivalTime' => $rItem['arrival_time'] ?? '20:15',
                    'Duration' => $rItem['duration'] ?? '2h 15m',
                    'Stops' => isset($rItem['stops']) ? (int)$rItem['stops'] : 0,
                    'Price' => (float)($rItem['price'] ?? 5150),
                    'Baggage' => $rItem['checkin_baggage'] ?? $rItem['baggage'] ?? '15 Kgs',
                    'Refundable' => true,
                    'SeatsLeft' => rand(3, 9)
                );
            }
        }
        if (empty($inboundFlights)) {
            $mockRetAirlines = array(
                array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2135', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '07:30', 'arr' => '09:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5150),
                array('code' => 'SG', 'name' => 'SpiceJet', 'flight_no' => 'SG-163', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png', 'dep' => '11:00', 'arr' => '13:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4999),
                array('code' => 'AI', 'name' => 'Air India', 'flight_no' => 'AI-806', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png', 'dep' => '16:00', 'arr' => '18:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5450),
                array('code' => 'QP', 'name' => 'Akasa Air', 'flight_no' => 'QP-1312', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png', 'dep' => '19:40', 'arr' => '21:55', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4850),
                array('code' => 'UK', 'name' => 'Vistara', 'flight_no' => 'UK-946', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png', 'dep' => '21:30', 'arr' => '23:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5800)
            );
            foreach ($mockRetAirlines as $rIdx => $m) {
                $inboundFlights[] = array(
                    'ResultID' => !empty($activeSearchTui) ? $activeSearchTui : ('FL_RET_' . ($rIdx + 100)),
                    'AirlineCode' => $m['code'],
                    'AirlineName' => $m['name'],
                    'AirlineLogo' => $m['logo'],
                    'FlightNumber' => $m['flight_no'],
                    'FromCode' => $search_query['to_code'],
                    'ToCode' => $search_query['from_code'],
                    'DepartureTime' => $m['dep'],
                    'ArrivalTime' => $m['arr'],
                    'Duration' => $m['dur'],
                    'Stops' => $m['stops'],
                    'Price' => $m['price'],
                    'Baggage' => '15 Kgs',
                    'Refundable' => true,
                    'SeatsLeft' => rand(3, 9)
                );
            }
        }
    }
    ?>

    <!-- Main Layout: Sidebar + Results -->
    <div class="container layout-grid" style="<?php echo !empty($is_roundtrip) ? 'grid-template-columns: 240px 1fr;' : ''; ?>">
        
        <!-- Sidebar (Filters) -->
        <aside class="filters-sidebar">
            <h3 class="filter-heading">Filters</h3>
            
            <div class="filter-section">
                <h4 class="filter-title">Stops</h4>
                <div class="stops-grid">
                    <div class="stop-box active">
                        <span class="stop-name">Non Stop</span>
                        <span class="stop-price">₹4999</span>
                    </div>
                    <div class="stop-box">
                        <span class="stop-name">1 Stop</span>
                        <span class="stop-price">₹5120</span>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <h4 class="filter-title">Airlines</h4>
                <label class="custom-checkbox">
                    <span>IndiGo (6E)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <div style="font-size: 11px; color: #16a34a; font-weight: 600; margin-top: 6px;">
                    <i class="fa-solid fa-circle-check"></i> Enabled for Test Sandbox
                </div>
            </div>

            <div class="filter-section">
                <h4 class="filter-title">Departure Times</h4>
                <p class="filter-subtitle">From <?php echo htmlspecialchars($search_query['from_code']); ?></p>
                <div class="time-grid">
                    <div class="time-box"><i class="fa-regular fa-sun"></i> 05am - 12pm</div>
                    <div class="time-box"><i class="fa-solid fa-sun"></i> 12pm - 6pm</div>
                    <div class="time-box"><i class="fa-solid fa-cloud-sun"></i> 6pm - 11pm</div>
                </div>
            </div>
        </aside>

        <!-- Right Side Results -->
        <div class="results-area">
            
            <?php if (empty($is_roundtrip)): ?>
            <!-- ========================================== -->
            <!-- ONE WAY FLIGHT RESULTS VIEW               -->
            <!-- ========================================== -->

            <!-- Date Carousel -->
            <div class="date-carousel">
                <div class="date-items-wrapper">
                    <div class="date-item"><span><?php echo date('D, d M', strtotime($search_query['date'] . ' -2 days')); ?></span><strong>₹ 5674</strong></div>
                    <div class="date-item"><span><?php echo date('D, d M', strtotime($search_query['date'] . ' -1 days')); ?></span><strong>₹ 5350</strong></div>
                    <div class="date-item active"><span><?php echo date('D, d M', strtotime($search_query['date'])); ?></span><strong class="green">₹ 4999</strong></div>
                    <div class="date-item"><span><?php echo date('D, d M', strtotime($search_query['date'] . ' +1 days')); ?></span><strong>₹ 5290</strong></div>
                    <div class="date-item"><span><?php echo date('D, d M', strtotime($search_query['date'] . ' +2 days')); ?></span><strong>₹ 5490</strong></div>
                </div>
            </div>

            <!-- Sorting Tabs -->
            <div class="sorting-bar">
                <div class="sort-tabs">
                    <button class="sort-tab"><i class="fa-regular fa-thumbs-up" style="color:#d97706; margin-right:5px;"></i> Best Value</button>
                    <button class="sort-tab active"><i class="fa-solid fa-money-bill-wave" style="margin-right:5px;"></i> Cheapest</button>
                    <button class="sort-tab"><i class="fa-solid fa-stopwatch" style="color:#2563eb; margin-right:5px;"></i> Fastest</button>
                </div>
                <div class="sort-right">
                    <span class="flight-count">Select your flight to continue</span>
                </div>
            </div>

            <!-- Flights List -->
            <div class="flights-list" id="flightListContainer">
                <?php 
                if (!empty($onwardFlights)) {
                    foreach ($onwardFlights as $f) {
                        $depHour = (int)substr($f['DepartureTime'], 0, 2);
                        $timeSlot = 'night';
                        if ($depHour >= 5 && $depHour < 12) $timeSlot = 'morning';
                        elseif ($depHour >= 12 && $depHour < 18) $timeSlot = 'afternoon';
                        elseif ($depHour >= 18 && $depHour < 23) $timeSlot = 'evening';
                ?>
                <div class="f-card" data-airline="<?php echo htmlspecialchars($f['AirlineCode'] ?? '6E'); ?>" data-stops="<?php echo (int)$f['Stops']; ?>" data-price="<?php echo (float)$f['Price']; ?>" data-timeslot="<?php echo $timeSlot; ?>">
                    <div class="f-card-main">
                        <div class="f-airline">
                            <div class="f-logo">
                                <img src="<?php echo htmlspecialchars($f['AirlineLogo']); ?>" alt="logo" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($f['AirlineName']); ?>&background=0d3470&color=fff';">
                            </div>
                            <div class="f-name">
                                <strong><?php echo htmlspecialchars($f['AirlineName']); ?></strong>
                                <span><?php echo htmlspecialchars($f['FlightNumber']); ?></span>
                            </div>
                        </div>
                        
                        <div class="f-time-block">
                            <div class="f-time-left">
                                <strong class="f-time"><?php echo htmlspecialchars($f['DepartureTime']); ?></strong>
                                <span class="f-city"><?php echo htmlspecialchars($search_query['from_code']); ?></span>
                            </div>
                            <div class="f-duration">
                                <span><?php echo htmlspecialchars($f['Duration']); ?></span>
                                <div class="f-line">
                                    <i class="fa-solid fa-plane"></i>
                                </div>
                                <span style="font-size: 11px; color: <?php echo ($f['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 600;"><?php echo ($f['Stops'] == 0) ? 'Non-Stop' : ($f['Stops'] . ' Stop' . (!empty($f['via']) ? (', Via ' . htmlspecialchars($f['via'])) : '')); ?></span>
                            </div>
                            <div class="f-time-right">
                                <strong class="f-time"><?php echo htmlspecialchars($f['ArrivalTime']); ?></strong>
                                <span class="f-city"><?php echo htmlspecialchars($search_query['to_code']); ?></span>
                            </div>
                        </div>
                        
                        <div class="f-seats">
                            <i class="fa-solid fa-chair" style="color: #ef4444;"></i>
                            <span><?php echo $f['SeatsLeft']; ?> Seats Left</span>
                        </div>
                        
                        <div class="f-price">
                            <strong>₹ <?php echo number_format($f['Price']); ?></strong>
                            <span style="font-size:11px; color:#64748b; display:block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                        </div>
                        
                        <div class="f-action">
                            <?php 
                            $cardTui = (!empty($f['ResultID']) && strpos($f['ResultID'], 'FL_') !== 0) ? $f['ResultID'] : (!empty($search_tui) ? $search_tui : ($search_query['tui'] ?? $f['ResultID']));
                            ?>
                            <form action="<?php echo site_url('flight/review'); ?>" method="POST">
                                <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="tui" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($f['AirlineName']); ?>">
                                <input type="hidden" name="airline_logo" value="<?php echo htmlspecialchars($f['AirlineLogo']); ?>">
                                <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($f['FlightNumber']); ?>">
                                <input type="hidden" name="flight_index" value="<?php echo htmlspecialchars($f['FlightIndex'] ?? $f['flight_index'] ?? ($f['Index'] ?? '6E|1')); ?>">
                                <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                                <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                                <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($f['DepartureTime']); ?>">
                                <input type="hidden" name="arrival_time" value="<?php echo htmlspecialchars($f['ArrivalTime']); ?>">
                                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($f['Duration']); ?>">
                                <input type="hidden" name="stops" value="<?php echo htmlspecialchars($f['Stops'] ?? 0); ?>">
                                <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                                <input type="hidden" name="price" value="<?php echo htmlspecialchars($f['Price']); ?>">
                                <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                                <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                                <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                                <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">
                                <button type="submit" class="f-book-btn">BOOK NOW</button>
                            </form>
                        </div>
                    </div>
                    <div class="f-card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fa-solid fa-suitcase" style="color: #0d3470;"></i> Check-in Baggage: <strong><?php echo htmlspecialchars($f['Baggage']); ?></strong> Included
                            &nbsp;|&nbsp;
                            <i class="fa-solid fa-shield-halved" style="color: #16a34a;"></i> <?php echo !empty($f['Refundable']) ? '<span style="color:#16a34a; font-weight:bold;">Refundable Fare</span>' : 'Standard Fare'; ?>
                        </div>
                        <div style="font-size:12px; color:#2563eb; font-weight:600;">
                            Get ₹500 Instant Discount with Code: <strong>VOYOGO500</strong>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                ?>
                    <div class="no-flights-msg" style="padding: 50px; text-align: center; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
                        <h3 style="color: #ef4444; font-family: var(--font-heading);">No Flights Found</h3>
                        <p style="color: #64748b; margin-top: 10px;">Please try modifying your origin, destination, or travel date.</p>
                        <a href="<?php echo site_url('flight'); ?>" class="btn-search" style="display: inline-flex; margin-top: 15px;">Search Again</a>
                    </div>
                <?php } ?>
            </div>

            <?php else: ?>
            <!-- ========================================== -->
            <!-- ROUND TRIP DUAL COLUMN SELECTION VIEW     -->
            <!-- ========================================== -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                
                <!-- Left Sector: Onward Departure Flights -->
                <div>
                    <div style="background: #0d3470; color: #fff; padding: 12px 18px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; opacity: 0.8; display: block;"><i class="fa-solid fa-plane-departure"></i> DEPARTURE FLIGHT</span>
                            <strong style="font-size: 16px;"><?php echo htmlspecialchars($search_query['from_code']); ?> &rarr; <?php echo htmlspecialchars($search_query['to_code']); ?></strong>
                        </div>
                        <span style="font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px;">
                            <?php echo date('d M, D', strtotime($search_query['date'])); ?>
                        </span>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="onwardFlightGroup">
                        <?php foreach ($onwardFlights as $idx => $f): ?>
                        <label class="rt-card onward-card <?php echo ($idx === 0) ? 'selected-rt-card' : ''; ?>" data-stops="<?php echo (int)($f['Stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($f['AirlineCode'] ?? '6E'); ?>" data-price="<?php echo (float)$f['Price']; ?>" style="display: block; cursor: pointer; border: 2px solid <?php echo ($idx === 0) ? '#2563eb' : '#e2e8f0'; ?>; border-radius: 8px; padding: 12px; transition: all 0.2s; background: <?php echo ($idx === 0) ? '#eff6ff' : '#fff'; ?>;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="selected_onward_idx" value="<?php echo $idx; ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> style="accent-color: #2563eb;" onchange="updateRoundTripSelection()">
                                    <img src="<?php echo htmlspecialchars($f['AirlineLogo']); ?>" alt="logo" style="height: 24px; width: 24px; object-fit: contain;">
                                    <div>
                                        <strong style="font-size: 13px; color: #0d3470; display: block;"><?php echo htmlspecialchars($f['AirlineName']); ?></strong>
                                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['FlightNumber']); ?></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 16px; color: #0f172a;">₹ <?php echo number_format($f['Price']); ?></strong>
                                    <span style="font-size: 10px; color: #64748b; display: block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: 13px;">
                                <div>
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['DepartureTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($f['FromCode']); ?></span>
                                </div>
                                <div style="text-align: center; color: #64748b; font-size: 11px;">
                                    <span><?php echo htmlspecialchars($f['Duration']); ?></span>
                                    <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                    <span style="color: <?php echo ($f['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 700;"><?php echo ($f['Stops'] == 0) ? 'Non Stop' : ($f['Stops'] . ' Stop' . (!empty($f['via']) ? (', Via ' . htmlspecialchars($f['via'])) : '')); ?></span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['ArrivalTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($f['ToCode']); ?></span>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right Sector: Return Inbound Flights -->
                <div>
                    <div style="background: #1e3a8a; color: #fff; padding: 12px 18px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; opacity: 0.8; display: block;"><i class="fa-solid fa-plane-arrival"></i> RETURN FLIGHT</span>
                            <strong style="font-size: 16px;"><?php echo htmlspecialchars($search_query['to_code']); ?> &rarr; <?php echo htmlspecialchars($search_query['from_code']); ?></strong>
                        </div>
                        <span style="font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px;">
                            <?php echo date('d M, D', strtotime($search_query['return_date'])); ?>
                        </span>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="returnFlightGroup">
                        <?php foreach ($inboundFlights as $rIdx => $rf): ?>
                        <label class="rt-card return-card <?php echo ($rIdx === 0) ? 'selected-rt-card' : ''; ?>" data-stops="<?php echo (int)($rf['Stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($rf['AirlineCode'] ?? '6E'); ?>" data-price="<?php echo (float)$rf['Price']; ?>" style="display: block; cursor: pointer; border: 2px solid <?php echo ($rIdx === 0) ? '#2563eb' : '#e2e8f0'; ?>; border-radius: 8px; padding: 12px; transition: all 0.2s; background: <?php echo ($rIdx === 0) ? '#eff6ff' : '#fff'; ?>;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="selected_return_idx" value="<?php echo $rIdx; ?>" <?php echo ($rIdx === 0) ? 'checked' : ''; ?> style="accent-color: #2563eb;" onchange="updateRoundTripSelection()">
                                    <img src="<?php echo htmlspecialchars($rf['AirlineLogo']); ?>" alt="logo" style="height: 24px; width: 24px; object-fit: contain;">
                                    <div>
                                        <strong style="font-size: 13px; color: #0d3470; display: block;"><?php echo htmlspecialchars($rf['AirlineName']); ?></strong>
                                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($rf['FlightNumber']); ?></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 16px; color: #0f172a;">₹ <?php echo number_format($rf['Price']); ?></strong>
                                    <span style="font-size: 10px; color: #64748b; display: block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: 13px;">
                                <div>
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['DepartureTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($rf['FromCode']); ?></span>
                                </div>
                                <div style="text-align: center; color: #64748b; font-size: 11px;">
                                    <span><?php echo htmlspecialchars($rf['Duration']); ?></span>
                                    <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                    <span style="color: <?php echo ($rf['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 700;"><?php echo ($rf['Stops'] == 0) ? 'Non Stop' : ($rf['Stops'] . ' Stop' . (!empty($rf['via']) ? (', Via ' . htmlspecialchars($rf['via'])) : '')); ?></span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['ArrivalTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($rf['ToCode']); ?></span>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Sticky Bottom Round Trip Action Bar -->
            <div id="roundTripStickyBar" style="position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 2px solid #2563eb; box-shadow: 0 -8px 30px rgba(0,0,0,0.12); z-index: 9999; padding: 14px 0;">
                <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 15px;">
                    
                    <div style="display: flex; gap: 30px; align-items: center;">
                        <!-- Departure Summary -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 800; padding: 4px 8px; border-radius: 4px;">DEPART</span>
                            <div>
                                <strong style="font-size: 14px; color: #0f172a;" id="barOnwardAirline">IndiGo 6E-2134</strong>
                                <div style="font-size: 12px; color: #64748b;" id="barOnwardTime">06:00 - 08:15 (DEL &rarr; BOM)</div>
                            </div>
                        </div>

                        <div style="height: 35px; width: 1px; background: #e2e8f0;"></div>

                        <!-- Return Summary -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 800; padding: 4px 8px; border-radius: 4px;">RETURN</span>
                            <div>
                                <strong style="font-size: 14px; color: #0f172a;" id="barReturnAirline">IndiGo 6E-2135</strong>
                                <div style="font-size: 12px; color: #64748b;" id="barReturnTime">07:30 - 09:45 (BOM &rarr; DEL)</div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 24px;">
                        <div style="text-align: right;">
                            <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Round Trip Fare</span>
                            <strong style="font-size: 24px; color: #0d3470; display: block; line-height: 1;" id="barTotalPrice">₹ 10,300</strong>
                        </div>

                        <form action="<?php echo site_url('flight/review'); ?>" method="POST" id="roundTripBookingForm">
                            <input type="hidden" name="is_roundtrip" value="1">
                            <input type="hidden" name="flight_id" id="rt_flight_id" value="">
                            <input type="hidden" name="tui" id="rt_tui" value="<?php echo htmlspecialchars($activeSearchTui); ?>">
                            
                            <!-- Onward Details -->
                            <input type="hidden" name="airline_name" id="rt_airline_name" value="">
                            <input type="hidden" name="airline_logo" id="rt_airline_logo" value="">
                            <input type="hidden" name="flight_number" id="rt_flight_number" value="">
                            <input type="hidden" name="from_code" id="rt_from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                            <input type="hidden" name="to_code" id="rt_to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                            <input type="hidden" name="departure_time" id="rt_departure_time" value="">
                            <input type="hidden" name="arrival_time" id="rt_arrival_time" value="">
                            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                            <input type="hidden" name="price" id="rt_onward_price" value="">
                            <input type="hidden" name="flight_index" id="rt_flight_index" value="">
                            <input type="hidden" name="duration" id="rt_duration" value="">
                            <input type="hidden" name="stops" id="rt_stops" value="">
                            
                            <!-- Return Details -->
                            <input type="hidden" name="return_airline_name" id="rt_return_airline_name" value="">
                            <input type="hidden" name="return_airline_logo" id="rt_return_airline_logo" value="">
                            <input type="hidden" name="return_flight_number" id="rt_return_flight_number" value="">
                            <input type="hidden" name="return_from_code" id="rt_return_from_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                            <input type="hidden" name="return_to_code" id="rt_return_to_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                            <input type="hidden" name="return_departure_time" id="rt_return_departure_time" value="">
                            <input type="hidden" name="return_arrival_time" id="rt_return_arrival_time" value="">
                            <input type="hidden" name="return_departure_date" value="<?php echo htmlspecialchars($search_query['return_date']); ?>">
                            <input type="hidden" name="return_price" id="rt_return_price" value="">
                            <input type="hidden" name="return_flight_index" id="rt_return_flight_index" value="">
                            <input type="hidden" name="return_duration" id="rt_return_duration" value="">
                            <input type="hidden" name="return_stops" id="rt_return_stops" value="">

                            <!-- Pax info -->
                            <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                            <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                            <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                            <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">

                            <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); padding: 12px 28px; font-size: 15px; font-weight: 800; border-radius: 8px; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(234, 88, 12, 0.35);">
                                <span>BOOK ROUND TRIP</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <script>
            const onwardData = <?php echo json_encode($onwardFlights); ?>;
            const returnData = <?php echo json_encode($inboundFlights); ?>;

            function updateRoundTripSelection() {
                const onwardRadio = document.querySelector('input[name="selected_onward_idx"]:checked');
                const returnRadio = document.querySelector('input[name="selected_return_idx"]:checked');

                const oIdx = onwardRadio ? parseInt(onwardRadio.value) : 0;
                const rIdx = returnRadio ? parseInt(returnRadio.value) : 0;

                const o = onwardData[oIdx] || onwardData[0];
                const r = returnData[rIdx] || returnData[0];

                // Update Visual Card Borders
                document.querySelectorAll('.onward-card').forEach((card, idx) => {
                    if (idx === oIdx) {
                        card.style.borderColor = '#2563eb';
                        card.style.background = '#eff6ff';
                    } else {
                        card.style.borderColor = '#e2e8f0';
                        card.style.background = '#ffffff';
                    }
                });

                document.querySelectorAll('.return-card').forEach((card, idx) => {
                    if (idx === rIdx) {
                        card.style.borderColor = '#2563eb';
                        card.style.background = '#eff6ff';
                    } else {
                        card.style.borderColor = '#e2e8f0';
                        card.style.background = '#ffffff';
                    }
                });

                // Update Sticky Bar Text
                if (o) {
                    document.getElementById('barOnwardAirline').textContent = o.AirlineName + ' ' + o.FlightNumber;
                    document.getElementById('barOnwardTime').textContent = o.DepartureTime + ' - ' + o.ArrivalTime + ' (' + o.FromCode + ' → ' + o.ToCode + ')';
                    
                    document.getElementById('rt_flight_id').value = o.ResultID;
                    document.getElementById('rt_airline_name').value = o.AirlineName;
                    document.getElementById('rt_airline_logo').value = o.AirlineLogo;
                    document.getElementById('rt_flight_number').value = o.FlightNumber;
                    document.getElementById('rt_departure_time').value = o.DepartureTime;
                    document.getElementById('rt_arrival_time').value = o.ArrivalTime;
                    document.getElementById('rt_onward_price').value = o.Price;
                    document.getElementById('rt_flight_index').value = o.FlightIndex || o.flight_index || o.Index || ((o.FlightNumber ? o.FlightNumber.substring(0, 2) : '6E') + '|1');
                    document.getElementById('rt_duration').value = o.Duration || '02h 15m';
                    document.getElementById('rt_stops').value = (o.Stops !== undefined) ? o.Stops : 0;
                }

                if (r) {
                    document.getElementById('barReturnAirline').textContent = r.AirlineName + ' ' + r.FlightNumber;
                    document.getElementById('barReturnTime').textContent = r.DepartureTime + ' - ' + r.ArrivalTime + ' (' + r.FromCode + ' → ' + r.ToCode + ')';

                    document.getElementById('rt_return_airline_name').value = r.AirlineName;
                    document.getElementById('rt_return_airline_logo').value = r.AirlineLogo;
                    document.getElementById('rt_return_flight_number').value = r.FlightNumber;
                    document.getElementById('rt_return_departure_time').value = r.DepartureTime;
                    document.getElementById('rt_return_arrival_time').value = r.ArrivalTime;
                    document.getElementById('rt_return_price').value = r.Price;
                    document.getElementById('rt_return_flight_index').value = r.FlightIndex || r.flight_index || r.Index || ((r.FlightNumber ? r.FlightNumber.substring(0, 2) : '6E') + '|1');
                    document.getElementById('rt_return_duration').value = r.Duration || '02h 15m';
                    document.getElementById('rt_return_stops').value = (r.Stops !== undefined) ? r.Stops : 0;
                }

                const total = (o ? parseFloat(o.Price) : 0) + (r ? parseFloat(r.Price) : 0);
                document.getElementById('barTotalPrice').textContent = '₹ ' + total.toLocaleString('en-IN');
            }

            document.addEventListener('DOMContentLoaded', updateRoundTripSelection);
            </script>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Dynamic Sort Script for One-Way
    const cards = Array.from(document.querySelectorAll('.f-card'));
    const container = document.getElementById('flightListContainer');
    
    if (container && cards.length > 0) {
        const sortTabs = document.querySelectorAll('.sort-tab');
        sortTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                sortTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const sortType = this.textContent.trim().toLowerCase();
                
                const sortedCards = [...cards].sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price') || 0);
                    const priceB = parseFloat(b.getAttribute('data-price') || 0);
                    if (sortType.includes('cheapest')) {
                        return priceA - priceB;
                    } else if (sortType.includes('fastest')) {
                        const stopsA = parseInt(a.getAttribute('data-stops') || 0);
                        const stopsB = parseInt(b.getAttribute('data-stops') || 0);
                        return stopsA - stopsB || priceA - priceB;
                    } else {
                        return (priceA - priceB);
                    }
                });
                
                sortedCards.forEach(c => container.appendChild(c));
            });
        });
    }

    // 2. Sidebar Stop Filter Click (One-Way & Round-Trip Dual Sector Support)
    const stopBoxes = document.querySelectorAll('.stop-box');
    stopBoxes.forEach(box => {
        box.addEventListener('click', function() {
            stopBoxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const isNonStop = this.querySelector('.stop-name').textContent.toLowerCase().includes('non');
            
            // Filter One-Way Cards
            document.querySelectorAll('.f-card').forEach(card => {
                const stops = parseInt(card.getAttribute('data-stops') || 0);
                card.style.display = (isNonStop ? stops === 0 : stops > 0) ? 'block' : 'none';
            });

            // Filter Round-Trip Departure Cards
            let firstVisibleOnward = null;
            document.querySelectorAll('.onward-card').forEach(card => {
                const stops = parseInt(card.getAttribute('data-stops') || 0);
                const isMatch = (isNonStop ? stops === 0 : stops > 0);
                card.style.display = isMatch ? 'block' : 'none';
                if (isMatch && !firstVisibleOnward) {
                    firstVisibleOnward = card.querySelector('input[type="radio"]');
                }
            });
            if (firstVisibleOnward) {
                firstVisibleOnward.checked = true;
            }

            // Filter Round-Trip Return Cards
            let firstVisibleReturn = null;
            document.querySelectorAll('.return-card').forEach(card => {
                const stops = parseInt(card.getAttribute('data-stops') || 0);
                const isMatch = (isNonStop ? stops === 0 : stops > 0);
                card.style.display = isMatch ? 'block' : 'none';
                if (isMatch && !firstVisibleReturn) {
                    firstVisibleReturn = card.querySelector('input[type="radio"]');
                }
            });
            if (firstVisibleReturn) {
                firstVisibleReturn.checked = true;
            }

            if (typeof updateRoundTripSelection === 'function') {
                updateRoundTripSelection();
            }
        });
    });
});
</script>
