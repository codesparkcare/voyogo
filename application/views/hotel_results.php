<div style="background-color: #f5f7fa; padding-bottom: 60px;">
    
    <!-- Top Search Summary Header -->
    <div style="background: linear-gradient(135deg, #09204b, #153e7e); color: #ffffff; padding: 24px 0;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-family: var(--font-heading); font-size: 24px; margin: 0; color: #ffffff;">Hotels in <?php echo htmlspecialchars($search_query['city']); ?></h1>
                <p style="font-size: 13px; color: #cbd5e1; margin: 4px 0 0 0;">
                    <i class="fa-solid fa-calendar-days"></i> <?php echo date('d M Y', strtotime($search_query['checkin'])); ?> &rarr; <?php echo date('d M Y', strtotime($search_query['checkout'])); ?> (3 Nights) | 1 Room, 2 Guests
                </p>
            </div>
            <div>
                <a href="<?php echo site_url('hotels'); ?>" class="btn-modify" style="background: #ffffff; color: #0d3470; font-weight: 700; padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen"></i> MODIFY SEARCH
                </a>
            </div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="container layout-grid" style="margin-top: 30px;">
        
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar">
            <h3 class="filter-heading">Filter Properties</h3>
            
            <div class="filter-section">
                <h4 class="filter-title">Star Rating</h4>
                <label class="custom-checkbox">
                    <span>5 Star Luxury Resorts (12)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>4 Star Premium Hotels (28)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>3 Star Comfort Stays (45)</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
            </div>

            <div class="filter-section">
                <h4 class="filter-title">Amenities</h4>
                <label class="custom-checkbox">
                    <span>Swimming Pool</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>Free Breakfast</span>
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>Beach Front</span>
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="custom-checkbox">
                    <span>Spa & Wellness</span>
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
        </aside>

        <!-- Hotels Results Area -->
        <div class="results-area">
            
            <div style="display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                <span style="font-weight: 700; color: #0d3470;">Found Top Recommended Stays</span>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span style="font-size: 13px; color: #64748b;">Sort By:</span>
                    <select class="field-input" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13px;">
                        <option>Popularity & Rating</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="hotel-list">
                <?php 
                $hotels = isset($hotelResults['Hotels']) ? $hotelResults['Hotels'] : array();
                if (!empty($hotels)) {
                    foreach ($hotels as $h) {
                ?>
                <div style="background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 24px; display: grid; grid-template-columns: 280px 1fr; transition: transform 0.2s;">
                    <div style="position: relative; height: 100%;">
                        <img src="<?php echo htmlspecialchars($h['image']); ?>" alt="hotel" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; top: 12px; left: 12px; background: rgba(9, 32, 75, 0.85); color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                            <?php echo str_repeat('★', $h['star_rating']); ?> <?php echo $h['star_rating']; ?> STAR HOTEL
                        </span>
                    </div>

                    <div style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h3 style="font-family: var(--font-heading); font-size: 20px; color: #0d3470; margin: 0 0 6px 0;"><?php echo htmlspecialchars($h['name']); ?></h3>
                                    <p style="font-size: 13px; color: #64748b; margin: 0;"><i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> <?php echo htmlspecialchars($h['location']); ?></p>
                                </div>
                                <div style="text-align: right;">
                                    <span style="background: #16a34a; color: #ffffff; font-weight: 800; padding: 4px 8px; border-radius: 6px; font-size: 14px;"><?php echo $h['rating']; ?> / 5</span>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;"><?php echo $h['reviews_count']; ?> Guest Reviews</div>
                                </div>
                            </div>

                            <div style="display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap;">
                                <?php foreach ($h['amenities'] as $am): ?>
                                    <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;"><i class="fa-solid fa-check" style="color: #16a34a; margin-right: 4px;"></i> <?php echo htmlspecialchars($am); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 16px;">
                            <div>
                                <span style="color: #16a34a; font-weight: 700; font-size: 13px;"><i class="fa-solid fa-mug-hot"></i> Free Breakfast Included</span>
                                <div style="font-size: 12px; color: #64748b;">Instant Confirmation & Zero Booking Fees</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 12px; color: #64748b;">Starts From</div>
                                <div style="font-size: 22px; font-weight: 800; color: #ef4444;">₹ <?php echo number_format($h['price_per_night']); ?> <small style="font-size: 12px; color: #64748b; font-weight: 400;">/night</small></div>
                                <a href="<?php echo site_url('hotels/detail/' . $h['id'] . '?city=' . urlencode($search_query['city']) . '&checkin=' . $search_query['checkin'] . '&checkout=' . $search_query['checkout']); ?>" class="btn-search" style="padding: 8px 20px; font-size: 13px; text-decoration: none; display: inline-flex; margin-top: 6px; background: linear-gradient(135deg, #0d3470, #fa3a3a);">
                                    VIEW ROOMS <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                ?>
                    <div style="padding: 50px; text-align: center; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <h3>No Hotels Found</h3>
                        <p style="color: #64748b;">Try searching for another destination.</p>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>
