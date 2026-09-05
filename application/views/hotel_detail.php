<?php
$qCity     = isset($search_query['city']) ? $search_query['city'] : (isset($city) ? $city : 'Goa, India');
$qCheckin  = isset($search_query['checkin']) ? $search_query['checkin'] : (isset($checkin) ? $checkin : date('Y-m-d', strtotime('+2 days')));
$qCheckout = isset($search_query['checkout']) ? $search_query['checkout'] : (isset($checkout) ? $checkout : date('Y-m-d', strtotime('+5 days')));
$qRooms    = isset($search_query['rooms']) ? $search_query['rooms'] : (isset($rooms) ? $rooms : 1);
$qAdults   = isset($search_query['adults']) ? $search_query['adults'] : (isset($adults) ? $adults : 2);
$qChildren = isset($search_query['children']) ? $search_query['children'] : (isset($children) ? $children : 0);
?>
<div style="background-color: #f5f7fa; padding-bottom: 60px;">
    
    <!-- Hero Detail Section -->
    <div style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 30px 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="background: #09204b; color: #ffffff; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 700; display: inline-block; margin-bottom: 8px;">
                        <?php echo str_repeat('★', $hotel['star_rating']); ?> <?php echo $hotel['star_rating']; ?> STAR LUXURY PROPERTY
                    </span>
                    <h1 style="font-family: var(--font-heading); font-size: 28px; color: #0d3470; margin: 0 0 6px 0;"><?php echo htmlspecialchars($hotel['name']); ?></h1>
                    <p style="font-size: 14px; color: #64748b; margin: 0;">
                        <i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> <?php echo htmlspecialchars($hotel['location']); ?>
                    </p>
                </div>
                <div style="text-align: right;">
                    <div style="background: #16a34a; color: #ffffff; font-weight: 800; padding: 8px 16px; border-radius: 8px; font-size: 18px; display: inline-block;">
                        <?php echo $hotel['rating']; ?> / 5
                    </div>
                    <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?php echo $hotel['reviews_count']; ?> Verified Guest Reviews</div>
                </div>
            </div>

            <!-- Image Gallery Banner -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-top: 24px; border-radius: 12px; overflow: hidden; height: 360px;">
                <img src="<?php echo htmlspecialchars($hotel['image']); ?>" alt="main" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 16px;">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80" alt="img2" style="width: 100%; height: 100%; object-fit: cover;">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80" alt="img3" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- Room Selection Table Section -->
    <div class="container" style="margin-top: 30px;">
        <div style="background: #ffffff; border-radius: 12px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <h2 style="font-family: var(--font-heading); font-size: 22px; color: #0d3470; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                Select Your Room Category
            </h2>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php 
                $rooms = isset($hotel['room_types']) ? $hotel['room_types'] : array(
                    array('type_id' => 'RM_101A', 'name' => 'Deluxe Garden View Room', 'price' => $hotel['price_per_night'], 'board' => 'Breakfast Included'),
                    array('type_id' => 'RM_101B', 'name' => 'Premium Sea View Suite', 'price' => $hotel['price_per_night'] + 3500, 'board' => 'Breakfast & Dinner Included')
                );

                foreach ($rooms as $r):
                ?>
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                    <div>
                        <h3 style="font-size: 18px; color: #09204b; margin: 0 0 6px 0;"><?php echo htmlspecialchars($r['name']); ?></h3>
                        <div style="font-size: 13px; color: #16a34a; font-weight: 700; margin-bottom: 6px;">
                            <i class="fa-solid fa-utensils"></i> <?php echo htmlspecialchars($r['board']); ?>
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                            <i class="fa-solid fa-user-group"></i> Max 2 Adults, 1 Child | <i class="fa-solid fa-bed"></i> 1 King Bed or 2 Twin Beds | <i class="fa-solid fa-shield-halved"></i> Free Cancellation up to 24h before Check-In
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <div style="font-size: 22px; font-weight: 800; color: #ef4444;">₹ <?php echo number_format($r['price']); ?> <small style="font-size: 12px; color: #64748b; font-weight: 400;">/night</small></div>
                        <form action="<?php echo site_url('hotels/review'); ?>" method="POST" style="margin-top: 8px;">
                            <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel['id']); ?>">
                            <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hotel['name']); ?>">
                            <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($hotel['location']); ?>">
                            <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($hotel['image']); ?>">
                            <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($r['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($r['price']); ?>">
                            <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($qCheckin); ?>">
                            <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($qCheckout); ?>">
                            <input type="hidden" name="city" value="<?php echo htmlspecialchars($qCity); ?>">
                            <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($qRooms); ?>">
                            <input type="hidden" name="adults" value="<?php echo htmlspecialchars($qAdults); ?>">
                            <input type="hidden" name="children" value="<?php echo htmlspecialchars($qChildren); ?>">
                            <button type="submit" class="btn-search" style="padding: 10px 24px; font-size: 14px; background: linear-gradient(135deg, #09204b, #2563eb);">
                                SELECT ROOM <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
