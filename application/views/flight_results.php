<!-- Main Wrapper -->
<div style="background-color: #f5f7fa; padding-bottom: 50px;">
    
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

    <!-- Main Layout: Sidebar + Results -->
    <div class="container layout-grid">
        
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
                $flights = array();
                $airlineMap = array(
                    '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
                    'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
                    'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
                    'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
                    'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
                    'I5' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
                    'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png')
                );

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

                        $activeSearchTui = !empty($search_tui) ? $search_tui : ($search_query['tui'] ?? '');

                        // Case 1: Formatted array from BenzyFlightApi (lowercase keys)
                        if (isset($item['flight_number']) || isset($item['airline_name']) || isset($item['price'])) {
                            $code = isset($item['airline_code']) ? strtoupper($item['airline_code']) : '6E';
                            $defaultLogo = isset($airlineMap[$code]) ? $airlineMap[$code]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
                            $defaultName = isset($airlineMap[$code]) ? $airlineMap[$code]['name'] : ($code . ' Airlines');

                            $flights[] = array(
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
                        }
                        // Case 2: Uppercase keys format
                        elseif (isset($item['AirlineName']) || isset($item['FlightNumber']) || isset($item['Price'])) {
                            $code = isset($item['AirlineCode']) ? strtoupper($item['AirlineCode']) : '6E';
                            $flights[] = array(
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
                        // Case 3: Raw Journey format
                        else {
                            $provider = isset($item['Provider']) && !empty($item['Provider']) ? strtoupper($item['Provider']) : '6E';
                            $aName = isset($airlineMap[$provider]) ? $airlineMap[$provider]['name'] : 'IndiGo (' . $provider . ')';
                            $aLogo = isset($airlineMap[$provider]) ? $airlineMap[$provider]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';

                            $flights[] = array(
                                'ResultID' => !empty($item['TUI']) ? $item['TUI'] : (!empty($activeSearchTui) ? $activeSearchTui : ('FL_' . ($idx + 100))),
                                'AirlineCode' => $provider,
                                'AirlineName' => $aName,
                                'AirlineLogo' => $aLogo,
                                'FlightNumber' => $provider . '-' . (isset($item['FlightNo']) ? $item['FlightNo'] : rand(100, 999)),
                                'FromCode' => $search_query['from_code'],
                                'ToCode' => $search_query['to_code'],
                                'DepartureTime' => isset($item['DepartureTime']) ? date('H:i', strtotime($item['DepartureTime'])) : '16:30',
                                'ArrivalTime' => isset($item['ArrivalTime']) ? date('H:i', strtotime($item['ArrivalTime'])) : '21:50',
                                'Duration' => isset($item['Duration']) ? $item['Duration'] : '5h 20m',
                                'Stops' => isset($item['Stops']) ? (int)$item['Stops'] : 0,
                                'Price' => isset($item['GrossFare']) ? (float)$item['GrossFare'] : 4999,
                                'Baggage' => '15 Kgs',
                                'Refundable' => true,
                                'SeatsLeft' => rand(3, 9)
                            );
                        }
                    }
                }

                // If still empty, supply mock flights for the searched route (IndiGo test scope)
                if (empty($flights)) {
                    $mockAirlines = array(
                        array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2000', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '06:00', 'arr' => '08:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4999),
                        array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2134', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '09:30', 'arr' => '11:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5450),
                        array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-5042', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '14:15', 'arr' => '16:30', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4750),
                        array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-6891', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '17:00', 'arr' => '19:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5120),
                        array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-7205', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '20:30', 'arr' => '22:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4650),
                        array('code' => '6E', 'name' => 'IndiGo (Via HYD)', 'flight_no' => '6E-8721', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '07:30', 'arr' => '13:00', 'dur' => '5h 30m', 'stops' => 1, 'price' => 4350)
                    );
                    foreach ($mockAirlines as $mIdx => $m) {
                        $flights[] = array(
                            'ResultID' => 'FL_' . ($mIdx + 100) . '_' . date('YmdHis'),
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

                if (!empty($flights)) {
                    foreach ($flights as $f) {
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
                                <span style="font-size: 11px; color: <?php echo ($f['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 600;"><?php echo ($f['Stops'] == 0) ? 'Non-Stop' : $f['Stops'] . ' Stop'; ?></span>
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
                            <span style="font-size:11px; color:#64748b; display:block;">per adult</span>
                        </div>
                        
                            <?php 
                            $cardTui = (!empty($f['ResultID']) && strpos($f['ResultID'], 'FL_') !== 0) ? $f['ResultID'] : (!empty($search_tui) ? $search_tui : ($search_query['tui'] ?? $f['ResultID']));
                            ?>
                            <form action="<?php echo site_url('flight/review'); ?>" method="POST">
                                <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="tui" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($f['AirlineName']); ?>">
                                <input type="hidden" name="airline_logo" value="<?php echo htmlspecialchars($f['AirlineLogo']); ?>">
                                <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($f['FlightNumber']); ?>">
                                <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                                <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                                <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($f['DepartureTime']); ?>">
                                <input type="hidden" name="arrival_time" value="<?php echo htmlspecialchars($f['ArrivalTime']); ?>">
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
            
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Filter and Sort Script
    const cards = Array.from(document.querySelectorAll('.f-card'));
    const container = document.getElementById('flightListContainer');
    
    // Sort Tabs
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

    // Sidebar Stop Filter Click
    const stopBoxes = document.querySelectorAll('.stop-box');
    stopBoxes.forEach(box => {
        box.addEventListener('click', function() {
            stopBoxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const isNonStop = this.querySelector('.stop-name').textContent.toLowerCase().includes('non');
            cards.forEach(card => {
                const stops = parseInt(card.getAttribute('data-stops') || 0);
                if (isNonStop) {
                    card.style.display = (stops === 0) ? 'block' : 'none';
                } else {
                    card.style.display = (stops > 0) ? 'block' : 'none';
                }
            });
        });
    });
});
</script>
