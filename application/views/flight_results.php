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
                <label class="custom-checkbox">
                    <span>Air India (AI)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>Vistara (UK)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>Akasa Air (QP)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
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
            <div class="flights-list">
                <?php 
                $flights = array();
                if (isset($flightResults['Flights']) && is_array($flightResults['Flights'])) {
                    $flights = $flightResults['Flights'];
                } elseif (isset($flightResults['Trips'][0]['Journey']) && is_array($flightResults['Trips'][0]['Journey'])) {
                    $airlineMap = array(
                        '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
                        'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
                        'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
                        'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
                        'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
                        'I5' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
                        'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png')
                    );
                    foreach ($flightResults['Trips'][0]['Journey'] as $j) {
                        $provider = isset($j['Provider']) && !empty($j['Provider']) ? strtoupper($j['Provider']) : '6E';
                        $aName = isset($airlineMap[$provider]) ? $airlineMap[$provider]['name'] : 'IndiGo (' . $provider . ')';
                        $aLogo = isset($airlineMap[$provider]) ? $airlineMap[$provider]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';

                        $flights[] = array(
                            'ResultID' => 'FL_' . rand(100, 999),
                            'AirlineName' => $aName,
                            'AirlineLogo' => $aLogo,
                            'FlightNumber' => $provider . '-' . (isset($j['FlightNo']) ? $j['FlightNo'] : rand(100, 999)),
                            'FromCode' => $search_query['from_code'],
                            'ToCode' => $search_query['to_code'],
                            'DepartureTime' => isset($j['DepartureTime']) ? date('H:i', strtotime($j['DepartureTime'])) : '16:30',
                            'ArrivalTime' => isset($j['ArrivalTime']) ? date('H:i', strtotime($j['ArrivalTime'])) : '21:50',
                            'Duration' => isset($j['Duration']) ? $j['Duration'] : '5h 20m',
                            'Stops' => 0,
                            'Price' => isset($j['GrossFare']) ? (float)$j['GrossFare'] : 12377,
                            'Baggage' => '15 Kgs',
                            'Refundable' => true,
                            'SeatsLeft' => rand(3, 9)
                        );
                    }
                }

                if (!empty($flights)) {
                    foreach ($flights as $f) {
                ?>
                <div class="f-card">
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
                                <span style="font-size: 11px; color: #16a34a; font-weight: 600;"><?php echo ($f['Stops'] == 0) ? 'Non-Stop' : $f['Stops'] . ' Stop'; ?></span>
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
                        
                        <div class="f-action">
                            <form action="<?php echo site_url('flight/review'); ?>" method="POST">
                                <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($f['ResultID']); ?>">
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
