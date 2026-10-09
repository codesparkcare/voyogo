<?php
$qCity      = $search_query['city'] ?? ($city ?? 'Tirunelveli');
$qCheckin   = $search_query['checkin'] ?? ($checkin ?? date('Y-m-d', strtotime('+3 days')));
$qCheckout  = $search_query['checkout'] ?? ($checkout ?? date('Y-m-d', strtotime('+7 days')));
$qNights    = isset($search_query['nights']) ? $search_query['nights'] : (isset($nights) ? $nights : max(1, round((strtotime($qCheckout) - strtotime($qCheckin)) / 86400)));
$qRooms     = $search_query['rooms'] ?? ($rooms ?? 1);
$qAdults    = $search_query['adults'] ?? ($adults ?? 2);
$qChildren  = $search_query['children'] ?? ($children ?? 0);
$qRoomData  = $search_query['roomData'] ?? ($roomDataJson ?? ($roomData ?? ''));
if (is_array($qRoomData)) {
    $qRoomData = json_encode($qRoomData);
}
$qSearchId  = $search_query['search_id'] ?? ($search_id ?? '');
$qTracingKey= $search_query['search_tracing_key'] ?? ($tracing_key ?? '');
$hotelList  = !empty($hotels) ? $hotels : array();

// Calculate counts & price limits for filter
$star5Count = 0;
$star4Count = 0;
$star3Count = 0;
$minHotelPrice = 999999;
$maxHotelPrice = 0;

foreach ($hotelList as $h) {
    $stars = (int)($h['star_rating'] ?? 4);
    if ($stars >= 5) $star5Count++;
    elseif ($stars == 4) $star4Count++;
    else $star3Count++;

    $p = (float)($h['price_per_night'] ?? 3800);
    if ($p < $minHotelPrice) $minHotelPrice = $p;
    if ($p > $maxHotelPrice) $maxHotelPrice = $p;
}
if ($minHotelPrice > $maxHotelPrice) {
    $minHotelPrice = 2000;
    $maxHotelPrice = 15000;
}
?>

<div style="margin-bottom: 24px;">
    <!-- Top Search Summary Header (Identical to voyogos.com/hotels) -->
    <div style="background: linear-gradient(135deg, #09204b 0%, #0d3470 100%); color: #ffffff; border-radius: 14px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 16px rgba(9, 32, 75, 0.15);">
        <div>
            <h1 style="font-size: 22px; font-weight: 800; margin: 0; color: #ffffff; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-hotel" style="color: #78B722;"></i>
                <span>Hotels in <?php echo htmlspecialchars($qCity); ?></span>
            </h1>
            <p style="font-size: 13px; color: #cbd5e1; margin: 6px 0 0 0; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('d M Y', strtotime($qCheckin)); ?> &rarr; <?php echo date('d M Y', strtotime($qCheckout)); ?> (<?php echo $qNights; ?> Nights)</span> &bull;
                <span><i class="fa-solid fa-door-closed"></i> <?php echo $qRooms; ?> Room<?php echo $qRooms > 1 ? 's' : ''; ?></span> &bull;
                <span><i class="fa-solid fa-user-group"></i> <?php echo $qAdults + $qChildren; ?> Guest<?php echo ($qAdults + $qChildren) > 1 ? 's' : ''; ?> (<?php echo $qAdults; ?> Adults<?php echo $qChildren > 0 ? ', ' . $qChildren . ' Children' : ''; ?>)</span> &bull;
                <span style="color: #86efac; font-weight: 700;"><i class="fa-solid fa-tag"></i> B2B Net Rates</span>
            </p>
        </div>

        <div>
            <button type="button" id="btnOpenModifyModal" class="btn-modify-search">
                <i class="fa-solid fa-pen-to-square"></i> MODIFY SEARCH
            </button>
        </div>
    </div>
</div>

<!-- INTERACTIVE MODIFY SEARCH POPUP MODAL (Matching Screenshot 4) -->
<div class="modify-search-modal-overlay" id="modifySearchModalOverlay" role="dialog" aria-modal="true">
    <div class="modify-search-modal-box">
        <div class="modify-modal-head">
            <h3><i class="fa-solid fa-hotel" style="margin-right: 8px; color: #86efac;"></i> Modify Hotel Search</h3>
            <button type="button" class="modify-modal-close" id="btnModifyModalClose" aria-label="Close modal">&times;</button>
        </div>

        <div class="modify-modal-content">
            <form action="<?php echo site_url('franchise/hotel_search'); ?>" method="GET" id="modifySearchForm">
                
                <div class="modify-grid-form">
                    
                    <!-- Destination City with Autosuggest -->
                    <div class="form-field-wrap autosuggest-dropdown-wrap">
                        <label for="modifyCityInput">Destination City</label>
                        <input type="text" id="modifyCityInput" name="city" value="<?php echo htmlspecialchars($qCity); ?>" placeholder="Where are you going?" autocomplete="off" required>
                        <div class="city-suggestions-box" id="modifyCitySuggestions">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Check-In Date -->
                    <div class="form-field-wrap">
                        <label for="modifyCheckinDate">Check-In</label>
                        <input type="date" id="modifyCheckinDate" name="checkin" value="<?php echo htmlspecialchars($qCheckin); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Check-Out Date -->
                    <div class="form-field-wrap">
                        <label for="modifyCheckoutDate">Check-Out</label>
                        <input type="date" id="modifyCheckoutDate" name="checkout" value="<?php echo htmlspecialchars($qCheckout); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>

                    <!-- Rooms & Guests Quick Summary -->
                    <div class="form-field-wrap">
                        <label>Rooms &amp; Guests</label>
                        <input type="text" id="modifyOccupancySummary" readonly value="<?php echo $qRooms; ?> Room<?php echo $qRooms > 1 ? 's' : ''; ?>, <?php echo $qAdults; ?> Adult<?php echo $qAdults > 1 ? 's' : ''; ?><?php echo $qChildren > 0 ? (', ' . $qChildren . ' Child' . ($qChildren > 1 ? 'ren' : '')) : ''; ?>" style="cursor: pointer; background: #f8fafc;">
                    </div>

                </div>

                <!-- Hidden inputs for adults, rooms, children, roomData -->
                <input type="hidden" name="rooms" id="hiddenRoomsInput" value="<?php echo $qRooms; ?>">
                <input type="hidden" name="adults" id="hiddenAdultsInput" value="<?php echo $qAdults; ?>">
                <input type="hidden" name="children" id="hiddenChildrenInput" value="<?php echo $qChildren; ?>">
                <input type="hidden" name="roomData" id="hiddenRoomDataInput" value="<?php echo htmlspecialchars($qRoomData ?? ''); ?>">

                <!-- Steppers Drawer -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        
                        <!-- Rooms Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Rooms</strong>
                                <div style="font-size: 11px; color: #64748b;">Max 8 rooms</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('rooms', -1)">-</button>
                                <span id="displayRoomsCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qRooms; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('rooms', 1)">+</button>
                            </div>
                        </div>

                        <!-- Adults Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Adults</strong>
                                <div style="font-size: 11px; color: #64748b;">Age 12+ yrs</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('adults', -1)">-</button>
                                <span id="displayAdultsCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qAdults; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('adults', 1)">+</button>
                            </div>
                        </div>

                        <!-- Children Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Children</strong>
                                <div style="font-size: 11px; color: #64748b;">Age 0-11 yrs</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('children', -1)">-</button>
                                <span id="displayChildrenCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qChildren; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('children', 1)">+</button>
                            </div>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn-update-search-submit">
                    <i class="fa-solid fa-magnifying-glass" style="margin-right: 8px;"></i> UPDATE SEARCH
                </button>

            </form>
        </div>
    </div>
</div>

<!-- Main Layout Grid (Sidebar + Results) -->
<div style="display: grid; grid-template-columns: 270px 1fr; gap: 24px; align-items: start;">

    <!-- Left Filters Sidebar (Identical to voyogos.com/hotels) -->
    <aside style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-sliders" style="color: #78B722;"></i> Filter Properties
            </h3>
            <span onclick="resetHotelFilters()" style="font-size: 12px; color: #2563eb; font-weight: 600; cursor: pointer;">Reset All</span>
        </div>

        <!-- 1. Star Rating Filter -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Star Rating</div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <span style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" class="hotel-star-filter" value="5" checked onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                        <span>5 Star Luxury Resorts</span>
                    </span>
                    <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">(<?php echo $star5Count; ?>)</span>
                </label>
                <label style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <span style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" class="hotel-star-filter" value="4" checked onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                        <span>4 Star Premium Hotels</span>
                    </span>
                    <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">(<?php echo $star4Count; ?>)</span>
                </label>
                <label style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <span style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" class="hotel-star-filter" value="3" checked onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                        <span>3 Star Comfort Stays</span>
                    </span>
                    <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">(<?php echo $star3Count; ?>)</span>
                </label>
            </div>
        </div>

        <!-- 2. Amenities Filter -->
        <div style="margin-bottom: 24px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Popular Amenities</div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <input type="checkbox" class="hotel-amenity-filter" value="Free Breakfast" onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                    <span>Free Breakfast</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <input type="checkbox" class="hotel-amenity-filter" value="Free WiFi" onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                    <span>Free High-Speed WiFi</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <input type="checkbox" class="hotel-amenity-filter" value="Swimming Pool" onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                    <span>Swimming Pool</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <input type="checkbox" class="hotel-amenity-filter" value="Restaurant" onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                    <span>Restaurant / Dining</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155; cursor: pointer;">
                    <input type="checkbox" class="hotel-amenity-filter" value="Free Cancellation" onchange="filterHotels()" style="accent-color: #78B722; width: 16px; height: 16px; cursor: pointer;">
                    <span>Free Cancellation</span>
                </label>
            </div>
        </div>

        <!-- 3. Price Range Slider -->
        <div style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">Max Night Rate</span>
                <span style="font-size: 13px; font-weight: 800; color: #09204b;" id="hotelPriceDisplay">₹ <?php echo number_format($maxHotelPrice); ?></span>
            </div>
            <input type="range" id="hotelPriceRange" min="<?php echo (int)$minHotelPrice; ?>" max="<?php echo (int)$maxHotelPrice; ?>" value="<?php echo (int)$maxHotelPrice; ?>" step="100" oninput="updateHotelPriceFilter(this.value)" style="width: 100%; accent-color: #78B722; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; margin-top: 4px;">
                <span>₹ <?php echo number_format($minHotelPrice); ?></span>
                <span>₹ <?php echo number_format($maxHotelPrice); ?></span>
            </div>
        </div>

        <!-- Float Info Banner -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; text-align: center;">
            <div style="font-size: 12px; font-weight: 800; color: #166534; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <i class="fa-solid fa-wallet"></i> DIRECT WALLET SETTLEMENT
            </div>
            <div style="font-size: 11px; color: #15803d; margin-top: 4px;">Instant Voucher with Agency Stamp</div>
        </div>
    </aside>

    <!-- Right Hotel Results Area -->
    <div>

        <!-- Results Toolbar matching voyogos.com/hotels -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; border: 1.5px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <strong style="font-weight: 800; color: #09204b; font-size: 15px;" id="hotelCountText">Found <?php echo count($hotelList); ?> Top Recommended Stays in <?php echo htmlspecialchars($qCity); ?></strong>
                <span style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">Benzy Live Inventory</span>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">Sort By:</span>
                <select id="hotelSortSelect" onchange="sortHotels(this.value)" style="padding: 7px 14px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #0f172a; outline: none; cursor: pointer;">
                    <option value="popularity">Popularity & Rating</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                </select>
            </div>
        </div>

        <!-- Hotel Card List -->
        <div id="hotelResultsList" style="display: flex; flex-direction: column; gap: 20px;">
            <?php if (!empty($hotelList)): 
                foreach ($hotelList as $h):
                    $hid       = $h['id'] ?? 'HTL_001';
                    $hname     = $h['name'] ?? 'Hotel';
                    $hrating   = (int)($h['star_rating'] ?? 4);
                    $hscore    = !empty($h['rating']) ? $h['rating'] : '4.6';
                    $hreviews  = !empty($h['reviews_count']) ? (int)$h['reviews_count'] : 450;
                    $hlocation = $h['location'] ?? ($qCity . ', India');
                    $himg      = !empty($h['image']) ? $h['image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80';
                    $pNight    = (float)($h['price_per_night'] ?? 3800.00);
                    $totalCost = (float)($h['total_stay'] ?? ($pNight * $qNights * $qRooms));
                    $amenities = !empty($h['amenities']) && is_array($h['amenities']) ? $h['amenities'] : array('Free WiFi', 'Multi-Cuisine Restaurant', 'Swimming Pool', 'Free Breakfast', 'Free Cancellation');
            ?>
            <div class="h-card-item" data-price="<?php echo $pNight; ?>" data-stars="<?php echo $hrating; ?>" data-rating="<?php echo (float)$hscore; ?>" data-amenities="<?php echo htmlspecialchars(json_encode($amenities)); ?>">
                
                <!-- Left Image Block (280px) -->
                <div style="position: relative; height: 100%; min-height: 220px; background: #e2e8f0; overflow: hidden;">
                    <img src="<?php echo htmlspecialchars($himg); ?>" alt="<?php echo htmlspecialchars($hname); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" class="h-img-zoom">
                    
                    <!-- Star Rating Badge -->
                    <span style="position: absolute; top: 12px; left: 12px; background: rgba(9, 32, 75, 0.88); backdrop-filter: blur(4px); color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.3px;">
                        <?php echo str_repeat('★', min(5, max(1, $hrating))); ?> <?php echo $hrating; ?> STAR HOTEL
                    </span>

                    <!-- Wholesale Net Badge -->
                    <span style="position: absolute; bottom: 12px; left: 12px; background: #78B722; color: #ffffff; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                        B2B WHOLESALE NET
                    </span>
                </div>

                <!-- Right Content Block -->
                <div style="padding: 22px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <!-- Header Row: Title & Score -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px;">
                            <div>
                                <h3 style="font-size: 19px; font-weight: 800; color: #09204b; margin: 0 0 6px 0; line-height: 1.25;"><?php echo htmlspecialchars($hname); ?></h3>
                                <p style="font-size: 13px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-location-dot" style="color: #ef4444;"></i>
                                    <span><?php echo htmlspecialchars($hlocation); ?></span>
                                </p>
                            </div>

                            <!-- Rating Badge -->
                            <div style="text-align: right; flex-shrink: 0;">
                                <span style="background: #16a34a; color: #ffffff; font-weight: 800; padding: 5px 9px; border-radius: 6px; font-size: 14px; display: inline-block;">
                                    <?php echo $hscore; ?> / 5
                                </span>
                                <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?php echo number_format($hreviews); ?> Reviews</div>
                            </div>
                        </div>

                        <!-- Amenities Pills matching voyogos.com/hotels -->
                        <div style="display: flex; gap: 6px; margin-top: 14px; flex-wrap: wrap;">
                            <?php foreach (array_slice($amenities, 0, 5) as $am): ?>
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 4px; font-size: 11.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-check" style="color: #16a34a;"></i> <?php echo htmlspecialchars($am); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Bottom Pricing & View Rooms Row -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 16px;">
                        <div>
                            <span style="color: #16a34a; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-mug-hot"></i> Free Breakfast Included
                            </span>
                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                Instant Confirmation &bull; Zero Booking Fees
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-size: 11.5px; color: #64748b;">Starting from</div>
                            <div style="font-size: 22px; font-weight: 800; color: #09204b; line-height: 1.1;">
                                ₹ <?php echo number_format($pNight, 2); ?>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b;">/ night</span>
                            </div>
                            <div style="font-size: 11px; color: #166534; font-weight: 700; margin-top: 2px;">
                                Total ₹ <?php echo number_format($totalCost, 2); ?> for <?php echo $qNights; ?> nights
                            </div>

                            <!-- View Rooms Button -->
                            <a href="<?php echo site_url('franchise/hotel_detail/' . urlencode($hid) . '?city=' . urlencode($qCity) . '&checkin=' . $qCheckin . '&checkout=' . $qCheckout . '&rooms=' . $qRooms . '&adults=' . $qAdults . '&children=' . $qChildren . (!empty($qSearchId) ? '&search_id=' . urlencode($qSearchId) : '') . (!empty($qTracingKey) ? '&search_tracing_key=' . urlencode($qTracingKey) : '') . (!empty($roomDataJson) ? '&roomData=' . urlencode($roomDataJson) : '')); ?>" class="h-view-rooms-btn">
                                <span>VIEW ROOMS</span> <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
            <?php endforeach; ?>

            <?php else: ?>
            <div style="padding: 50px 30px; text-align: center; background: #ffffff; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                <i class="fa-solid fa-hotel" style="font-size: 40px; color: #cbd5e1; margin-bottom: 14px;"></i>
                <h3 style="font-size: 18px; color: #0f172a; margin-bottom: 6px;">No Hotels Found</h3>
                <p style="color: #64748b; font-size: 13.5px; margin-bottom: 16px;">Try searching for another city destination or adjust dates.</p>
                <a href="<?php echo site_url('franchise/hotel'); ?>" style="background: #78B722; color: #ffffff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 13px;">
                    Search Different City
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<!-- Interactive Client-side Filter & Sort Script for Hotels -->
<script>
let activeStarRatings = [3, 4, 5];
let activeAmenities = [];
let maxHotelPriceFilter = <?php echo (int)$maxHotelPrice; ?>;

function filterHotels() {
    activeStarRatings = Array.from(document.querySelectorAll('.hotel-star-filter:checked')).map(cb => parseInt(cb.value));
    activeAmenities = Array.from(document.querySelectorAll('.hotel-amenity-filter:checked')).map(cb => cb.value.toLowerCase());

    let visibleCount = 0;
    document.querySelectorAll('.h-card-item').forEach(card => {
        const stars     = parseInt(card.getAttribute('data-stars') || 4);
        const price     = parseFloat(card.getAttribute('data-price') || 0);
        let amenities = [];
        try {
            amenities = JSON.parse(card.getAttribute('data-amenities') || '[]').map(a => a.toLowerCase());
        } catch(e) {}

        // Star rating match
        const starMatch = (activeStarRatings.length === 0) || activeStarRatings.includes(stars);
        // Price match
        const priceMatch = price <= maxHotelPriceFilter;
        // Amenities match
        let amenityMatch = true;
        if (activeAmenities.length > 0) {
            amenityMatch = activeAmenities.every(req => {
                return amenities.some(am => am.includes(req) || req.includes(am));
            });
        }

        if (starMatch && priceMatch && amenityMatch) {
            card.style.display = 'grid';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const countEl = document.getElementById('hotelCountText');
    if (countEl) countEl.innerText = `Found ${visibleCount} Top Recommended Stays in <?php echo htmlspecialchars($qCity); ?>`;
}

function updateHotelPriceFilter(val) {
    maxHotelPriceFilter = parseFloat(val);
    document.getElementById('hotelPriceDisplay').innerText = '₹ ' + parseInt(val).toLocaleString('en-IN');
    filterHotels();
}

function sortHotels(sortType) {
    const container = document.getElementById('hotelResultsList');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.h-card-item'));
    cards.sort((a, b) => {
        const priceA  = parseFloat(a.getAttribute('data-price') || 0);
        const priceB  = parseFloat(b.getAttribute('data-price') || 0);
        const ratingA = parseFloat(a.getAttribute('data-rating') || 0);
        const ratingB = parseFloat(b.getAttribute('data-rating') || 0);

        if (sortType === 'price_asc') {
            return priceA - priceB;
        } else if (sortType === 'price_desc') {
            return priceB - priceA;
        } else {
            return ratingB - ratingA || priceA - priceB;
        }
    });

    cards.forEach(c => container.appendChild(c));
}

function resetHotelFilters() {
    activeStarRatings = [3, 4, 5];
    activeAmenities = [];
    document.querySelectorAll('.hotel-star-filter').forEach(cb => cb.checked = true);
    document.querySelectorAll('.hotel-amenity-filter').forEach(cb => cb.checked = false);
    const range = document.getElementById('hotelPriceRange');
    if (range) {
        range.value = range.max;
        updateHotelPriceFilter(range.max);
    }
    filterHotels();
}

// ========================================================
// MODIFY SEARCH POPUP MODAL & STEPPERS (Matching Screenshot 4)
// ========================================================
const modifyModal = document.getElementById('modifySearchModalOverlay');
const btnOpenModify = document.getElementById('btnOpenModifyModal');
const btnCloseModify = document.getElementById('btnModifyModalClose');

if (btnOpenModify && modifyModal) {
    btnOpenModify.onclick = () => {
        modifyModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
}
if (btnCloseModify && modifyModal) {
    btnCloseModify.onclick = () => {
        modifyModal.classList.remove('active');
        document.body.style.overflow = '';
    };
}
if (modifyModal) {
    modifyModal.onclick = (e) => {
        if (e.target === modifyModal) {
            modifyModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modifyModal && modifyModal.classList.contains('active')) {
        modifyModal.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// Steppers logic
function stepCounter(type, change) {
    const countElem = document.getElementById('display' + type.charAt(0).toUpperCase() + type.slice(1) + 'Count');
    const hiddenInput = document.getElementById('hidden' + type.charAt(0).toUpperCase() + type.slice(1) + 'Input');
    if (!countElem || !hiddenInput) return;

    let current = parseInt(hiddenInput.value) || 0;
    current += change;

    if (type === 'rooms') {
        if (current < 1) current = 1;
        if (current > 8) current = 8;
    } else if (type === 'adults') {
        if (current < 1) current = 1;
        if (current > 16) current = 16;
    } else if (type === 'children') {
        if (current < 0) current = 0;
        if (current > 8) current = 8;
    }

    hiddenInput.value = current;
    countElem.textContent = current;

    // Update summary string
    const r = parseInt(document.getElementById('hiddenRoomsInput').value) || 1;
    const a = parseInt(document.getElementById('hiddenAdultsInput').value) || 1;
    const c = parseInt(document.getElementById('hiddenChildrenInput').value) || 0;
    const summaryInput = document.getElementById('modifyOccupancySummary');
    if (summaryInput) {
        summaryInput.value = r + ' Room' + (r > 1 ? 's' : '') + ', ' + a + ' Adult' + (a > 1 ? 's' : '') + (c > 0 ? (', ' + c + ' Child' + (c > 1 ? 'ren' : '')) : '');
    }
}

// Checkin & Checkout date synchronizer
const modCheckin = document.getElementById('modifyCheckinDate');
const modCheckout = document.getElementById('modifyCheckoutDate');
if (modCheckin && modCheckout) {
    modCheckin.addEventListener('change', () => {
        const d = new Date(modCheckin.value);
        if (!isNaN(d.getTime())) {
            d.setDate(d.getDate() + 1);
            const nextDayStr = d.toISOString().split('T')[0];
            modCheckout.min = nextDayStr;
            if (modCheckout.value <= modCheckin.value) {
                modCheckout.value = nextDayStr;
            }
        }
    });
}

// City Autosuggest in Modify Modal
const popularDestinations = [
    'Tirunelveli, Tamil Nadu, India',
    'Goa, India',
    'Dubai, United Arab Emirates',
    'Mumbai, Maharashtra, India',
    'New Delhi, Delhi, India',
    'Bengaluru, Karnataka, India',
    'Chennai, Tamil Nadu, India',
    'Jaipur, Rajasthan, India',
    'Madurai, Tamil Nadu, India',
    'Kochi (Cochin), Kerala, India',
    'Singapore',
    'Bangkok, Thailand',
    'Maldives',
    'Bali, Indonesia',
    'London, United Kingdom'
];

const cityInput = document.getElementById('modifyCityInput');
const suggestionsBox = document.getElementById('modifyCitySuggestions');

if (cityInput && suggestionsBox) {
    cityInput.addEventListener('input', () => {
        const q = cityInput.value.trim().toLowerCase();
        if (q.length === 0) {
            suggestionsBox.style.display = 'none';
            return;
        }
        const matches = popularDestinations.filter(d => d.toLowerCase().includes(q));
        if (matches.length > 0) {
            suggestionsBox.innerHTML = '';
            matches.forEach(item => {
                const div = document.createElement('div');
                div.className = 'city-suggestion-item';
                div.innerHTML = '<i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> ' + item;
                div.onclick = () => {
                    cityInput.value = item;
                    suggestionsBox.style.display = 'none';
                };
                suggestionsBox.appendChild(div);
            });
            suggestionsBox.style.display = 'block';
        } else {
            suggestionsBox.style.display = 'none';
        }
    });

    document.addEventListener('click', (e) => {
        if (!cityInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
}
</script>

<style>
/* MODIFY SEARCH BUTTON & MODAL */
.btn-modify-search {
    background: #ffffff;
    color: #09204b;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}
.btn-modify-search:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.modify-search-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.65);
    z-index: 99990;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modify-search-modal-overlay.active {
    display: flex;
}

.modify-search-modal-box {
    background: #ffffff;
    border-radius: 14px;
    width: 90%;
    max-width: 820px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    animation: zoomInModify 0.2s ease;
}

@keyframes zoomInModify {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.modify-modal-head {
    background: linear-gradient(135deg, #09204b 0%, #0d3470 100%);
    color: #ffffff;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modify-modal-head h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    display: flex;
    align-items: center;
}

.modify-modal-close {
    background: none;
    border: none;
    color: #cbd5e1;
    font-size: 26px;
    cursor: pointer;
    line-height: 1;
    transition: color 0.15s;
}

.modify-modal-close:hover {
    color: #ffffff;
}

.modify-modal-content {
    padding: 24px;
}

.modify-grid-form {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 16px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .modify-grid-form {
        grid-template-columns: 1fr;
    }
}

.form-field-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-field-wrap label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
}

.form-field-wrap input {
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
    transition: border-color 0.15s;
}

.form-field-wrap input:focus {
    border-color: #0b438c;
}

/* Autosuggest Dropdown */
.autosuggest-dropdown-wrap {
    position: relative;
}

.city-suggestions-box {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    z-index: 100;
    max-height: 220px;
    overflow-y: auto;
    display: none;
}

.city-suggestion-item {
    padding: 10px 14px;
    font-size: 13px;
    color: #1e293b;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}

.city-suggestion-item:hover {
    background: #f0fdf4;
    color: #0b438c;
}

/* Stepper Controls */
.stepper-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 0;
}

.stepper-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.15s;
}

.stepper-btn:hover {
    background: #f1f5f9;
}

.btn-update-search-submit {
    background: linear-gradient(135deg, #09204b, #2563eb);
    color: #ffffff;
    font-weight: 800;
    font-size: 15px;
    border: none;
    border-radius: 6px;
    padding: 14px 28px;
    cursor: pointer;
    text-transform: uppercase;
    width: 100%;
    letter-spacing: 0.5px;
    transition: filter 0.15s;
}

.btn-update-search-submit:hover {
    filter: brightness(1.1);
}

/* Hotel Card Styles matching voyogos.com/hotels */
.h-card-item {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    display: grid;
    grid-template-columns: 280px 1fr;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
}
.h-card-item:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border-color: #cbd5e1;
}
.h-card-item:hover .h-img-zoom {
    transform: scale(1.04);
}

.h-view-rooms-btn {
    padding: 9px 22px;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    background: #78B722;
    border-radius: 6px;
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(120, 183, 34, 0.3);
    transition: all 0.15s;
}
.h-view-rooms-btn:hover {
    background: #6aa31e;
}
</style>
