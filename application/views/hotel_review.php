<?php
$bSummary = isset($booking_summary) ? $booking_summary : (isset($booking_data) ? $booking_data : array());
$hotel_id = $bSummary['hotel_id'] ?? 'HTL_101';
$hotel_name = $bSummary['hotel_name'] ?? 'Luxury Resort';
$hotel_address = $bSummary['hotel_address'] ?? 'Goa, India';
$hotel_image = $bSummary['hotel_image'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80';
$room_type = $bSummary['room_type'] ?? 'Deluxe Room';
$checkin_date = $bSummary['checkin_date'] ?? ($bSummary['checkin'] ?? date('Y-m-d', strtotime('+2 days')));
$checkout_date = $bSummary['checkout_date'] ?? ($bSummary['checkout'] ?? date('Y-m-d', strtotime('+5 days')));
$nights = $bSummary['nights'] ?? max(1, round((strtotime($checkout_date) - strtotime($checkin_date)) / 86400));
$rooms = (int)($bSummary['rooms'] ?? 1);
$adults = (int)($bSummary['adults'] ?? 2);
$children = (int)($bSummary['children'] ?? 0);
$total_amount = (float)($bSummary['total_amount'] ?? ($bSummary['grand_total'] ?? 4500));
$base_total = (float)($bSummary['base_total'] ?? 0);
$taxes = (float)($bSummary['taxes'] ?? 0);
if ($taxes <= 0 || $base_total <= 0 || $base_total >= $total_amount) {
    $base_total = round($total_amount / 1.12, 2);
    $taxes = round($total_amount - $base_total, 2);
}
$discount_total = (float)($bSummary['discount_total'] ?? 0);
$is_refundable = isset($bSummary['is_refundable']) ? (bool)$bSummary['is_refundable'] : true;
$star_rating = (int)($bSummary['star_rating'] ?? 5);
$inclusions = !empty($bSummary['inclusions']) && is_array($bSummary['inclusions']) ? $bSummary['inclusions'] : array('Free High-Speed WiFi', 'Complimentary Breakfast', 'Early Check-in Subject to Availability');
$cancellation_text = !empty($bSummary['cancellation_text']) ? $bSummary['cancellation_text'] : ($is_refundable ? 'Free cancellation up to 48 hours before check-in.' : 'Non-refundable booking.');
$changeRoomUrl = site_url('hotels/detail/' . $hotel_id . '?city=' . urlencode($bSummary['city'] ?? 'Goa') . '&checkin=' . urlencode($checkin_date) . '&checkout=' . urlencode($checkout_date) . '&rooms=' . $rooms . '&adults=' . $adults . '&children=' . $children . (!empty($bSummary['roomData']) ? '&roomData=' . urlencode($bSummary['roomData']) : '') . (!empty($bSummary['search_id']) ? '&search_id=' . urlencode($bSummary['search_id']) : '') . (!empty($bSummary['tui']) ? '&search_tracing_key=' . urlencode($bSummary['tui']) : ''));

$isUserLoggedIn   = isset($this->session) && $this->session->userdata('user_logged_in');
$sessionUserName  = $isUserLoggedIn ? ($this->session->userdata('user_name') ?: '') : '';
$sessionUserEmail = $isUserLoggedIn ? ($this->session->userdata('user_email') ?: '') : '';
$sessionUserPhone = $isUserLoggedIn ? ($this->session->userdata('user_phone') ?: '') : '';
$cleanPhone       = preg_replace('/^\+91/', '', $sessionUserPhone);

$names = explode(' ', trim($sessionUserName));
$defaultFname = $names[0] ?? '';
$defaultLname = isset($names[1]) ? implode(' ', array_slice($names, 1)) : '';
?>
<div style="background-color: #f5f7fa; padding: 30px 0 60px 0;">
    <div class="container">
        
        <!-- Header Step Progress -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="font-family: var(--font-heading); font-size: 20px; color: #09204b; margin: 0;">Review Your Hotel Reservation</h2>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">Fill primary guest info to confirm your hotel booking</p>
            </div>
            <div style="display: flex; gap: 20px; font-weight: 600; font-size: 14px;">
                <span style="color: #16a34a;"><i class="fa-solid fa-circle-check"></i> 1. Hotel Selected</span>
                <span style="color: #0d3470;"><i class="fa-solid fa-circle-dot"></i> 2. Guest Information</span>
                <span style="color: #94a3b8;"><i class="fa-regular fa-circle"></i> 3. Voucher Confirmation</span>
            </div>
        </div>

        <!-- Professional User Login Gate / Verified Status Banner -->
        <?php if ($isUserLoggedIn): ?>
            <div id="hotelLoginBanner" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 20px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #16a34a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 14.5px; font-weight: 800; color: #166534;">Logged in as <?php echo htmlspecialchars($sessionUserName ?: $sessionUserPhone); ?></div>
                        <div style="font-size: 12.5px; color: #15803d;">Your verified guest contact details have been pre-filled below for instant confirmation.</div>
                    </div>
                </div>
                <span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-shield-halved"></i> Phone Verified
                </span>
            </div>
        <?php else: ?>
            <div id="hotelLoginBanner" style="background: linear-gradient(135deg, #09204b 0%, #1e3a8a 100%); color: #ffffff; border-radius: 14px; padding: 18px 24px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; gap: 20px; box-shadow: 0 10px 25px rgba(9,32,75,0.12); flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 46px; height: 46px; border-radius: 50%; background: rgba(250, 58, 58, 0.2); color: #fa3a3a; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #ffffff;">Please Sign In with Mobile OTP to Confirm Reservation</h4>
                        <p style="margin: 0; font-size: 13px; color: #cbd5e1;">Sign in to guarantee your hotel booking rates, auto-fill guest information, and receive instant voucher.</p>
                    </div>
                </div>
                <button type="button" onclick="triggerBookingLogin('Please enter your mobile number to sign in and confirm this hotel reservation.')" style="background: #fa3a3a; color: #ffffff; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13.5px; font-weight: 800; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(250,58,58,0.4); transition: transform 0.15s ease;">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>Sign In with OTP</span>
                </button>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <!-- Left Side Form -->
            <div>
                <!-- Section Title (Exact Screenshot 5 Matching) -->
                <h3 style="font-family: var(--font-heading); font-size: 19px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 14px;">
                    Review your Hotel details
                </h3>

                <!-- Hotel Details Card (Exact Screenshot 5 Matching) -->
                <div style="background: #ffffff; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; overflow: hidden;">
                    
                    <!-- Card Top Header -->
                    <div style="padding: 18px 22px 14px 22px; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #09204b;"><?php echo htmlspecialchars($hotel_name); ?></h3>
                                    <span style="color: #f59e0b; font-size: 15px; letter-spacing: 2px;">
                                        <?php echo str_repeat('★', max(1, min(5, $star_rating))); ?>
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: #0284c7; margin-top: 6px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span><?php echo htmlspecialchars($hotel_address); ?></span>
                                </div>
                            </div>
                            <div>
                                <?php if ($is_refundable): ?>
                                    <span style="font-size: 13px; font-weight: 700; color: #16a34a; background: #f0fdf4; padding: 4px 10px; border-radius: 4px; border: 1px solid #bbf7d0;">
                                        <i class="fa-solid fa-check"></i> Free Cancellation
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 13px; font-weight: 700; color: #dc2626;">
                                        Non-Refundable
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div style="padding: 20px 22px; display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
                        <!-- Hotel Image Thumbnail -->
                        <div style="flex-shrink: 0;">
                            <img src="<?php echo htmlspecialchars($hotel_image); ?>" alt="<?php echo htmlspecialchars($hotel_name); ?>" style="width: 175px; height: 115px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                        </div>

                        <!-- Dates & Duration Strip -->
                        <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                                <!-- Check-in -->
                                <div>
                                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">CHECK-IN</div>
                                    <div style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-top: 2px;">
                                        <?php echo date('M d', strtotime($checkin_date)); ?>
                                    </div>
                                    <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-top: 2px;">
                                        <?php echo date('D', strtotime($checkin_date)); ?>, 4:00 PM
                                    </div>
                                </div>

                                <!-- Center Dashed Duration -->
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 110px;">
                                    <div style="width: 100%; border-top: 2px dashed #cbd5e1; margin-bottom: 4px;"></div>
                                    <div style="font-size: 12px; font-weight: 700; color: #475569;"><?php echo $nights; ?> Night<?php echo $nights > 1 ? 's' : ''; ?></div>
                                </div>

                                <!-- Check-out -->
                                <div>
                                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">CHECK-OUT</div>
                                    <div style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-top: 2px;">
                                        <?php echo date('M d', strtotime($checkout_date)); ?>
                                    </div>
                                    <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-top: 2px;">
                                        <?php echo date('D', strtotime($checkout_date)); ?>, 4:00 PM
                                    </div>
                                </div>

                                <!-- Change Room Button -->
                                <div style="margin-left: auto;">
                                    <a href="<?php echo $changeRoomUrl; ?>" style="color: #0284c7; font-size: 12.5px; font-weight: 800; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px;">
                                        CHANGE ROOM
                                    </a>
                                </div>
                            </div>

                            <!-- Rooms & Guests -->
                            <div style="margin-top: 14px; font-size: 13px; color: #475569;">
                                <span style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-right: 8px;">ROOMS & GUESTS</span>
                                <strong style="color: #0f172a; font-size: 15px;"><?php echo $rooms; ?></strong> Room &nbsp; 
                                <strong style="color: #0f172a; font-size: 15px;"><?php echo ($adults + $children); ?></strong> Guest<?php echo ($adults + $children) > 1 ? 's' : ''; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar (Room Type + Guests + Inclusions) -->
                    <div style="background: #fafafa; border-top: 1px solid #f1f5f9; padding: 12px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                            <span style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                <?php echo htmlspecialchars($room_type); ?>
                            </span>
                            <span style="font-size: 12.5px; font-weight: 700; color: #475569; text-transform: uppercase;">
                                <strong style="color: #0f172a;"><?php echo $adults; ?></strong> ADULT<?php echo $adults > 1 ? 'S' : ''; ?><?php echo $children > 0 ? ', <strong style="color: #0f172a;">' . $children . '</strong> CHILD' . ($children > 1 ? 'REN' : '') : ''; ?>
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 14px; font-size: 12.5px;">
                            <a href="javascript:void(0)" onclick="openEssentialInfoModal()" style="color: #0284c7; text-decoration: underline; font-weight: 600;">Essential Info</a>
                            <span style="color: #cbd5e1;">|</span>
                            <a href="javascript:void(0)" onclick="openInclusionsModal()" style="color: #0284c7; text-decoration: underline; font-weight: 600;">Inclusions</a>
                        </div>
                    </div>

                </div>

                <!-- Main Guest Form -->
                <form id="hotelBookingForm" action="<?php echo site_url('hotels/payment'); ?>" method="POST">
                    
                    <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel_id); ?>">
                    <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hotel_name); ?>">
                    <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($hotel_address); ?>">
                    <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($hotel_image); ?>">
                    <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($booking_data['room_id'] ?? 'RM_01'); ?>">
                    <input type="hidden" name="room_group_id" value="<?php echo htmlspecialchars($booking_data['room_group_id'] ?? 'RGRP_01'); ?>">
                    <input type="hidden" name="recommendation_id" value="<?php echo htmlspecialchars($booking_data['recommendation_id'] ?? 'REC_01'); ?>">
                    <input type="hidden" name="search_id" value="<?php echo htmlspecialchars($booking_data['search_id'] ?? ''); ?>">
                    <input type="hidden" name="tui" value="<?php echo htmlspecialchars($booking_data['tui'] ?? ($booking_data['search_tracing_key'] ?? '')); ?>">
                    <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($booking_data['board_type'] ?? 'Breakfast Included'); ?>">
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($booking_data['city'] ?? 'Goa'); ?>">
                    <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin_date); ?>">
                    <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout_date); ?>">
                    <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($checkin_date); ?>">
                    <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($checkout_date); ?>">
                    <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($booking_data['rooms'] ?? 1); ?>">
                    <input type="hidden" name="adults" value="<?php echo htmlspecialchars($booking_data['adults'] ?? 2); ?>">
                    <input type="hidden" name="children" value="<?php echo htmlspecialchars($booking_data['children'] ?? 0); ?>">
                    <input type="hidden" name="roomData" value="<?php echo htmlspecialchars($booking_data['roomData'] ?? ''); ?>">
                    <input type="hidden" name="provider" value="<?php echo htmlspecialchars($booking_data['provider'] ?? 'CleartripAPI'); ?>">
                    <input type="hidden" name="nights" value="<?php echo htmlspecialchars($booking_data['nights'] ?? 1); ?>">
                    <input type="hidden" name="base_total" value="<?php echo htmlspecialchars($base_total); ?>">
                    <input type="hidden" name="taxes" value="<?php echo htmlspecialchars($taxes); ?>">
                    <input type="hidden" name="discount_total" value="<?php echo htmlspecialchars($discount_total); ?>">
                    <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($total_amount); ?>">
                    <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
                    <input type="hidden" name="star_rating" value="<?php echo htmlspecialchars($star_rating); ?>">
                    <input type="hidden" name="is_refundable" value="<?php echo $is_refundable ? '1' : '0'; ?>">
                    <input type="hidden" name="cancellation_text" value="<?php echo htmlspecialchars($cancellation_text ?? 'Free cancellation available'); ?>">
                    <input type="hidden" name="primary_guest_name" id="hidden_primary_guest_name" value="<?php echo htmlspecialchars($sessionUserName ?: ''); ?>">

                    <?php
                    // Parse rooms data
                    $roomDataRaw = $booking_data['roomData'] ?? '';
                    $roomDataList = !empty($roomDataRaw) ? json_decode($roomDataRaw, true) : array();
                    if (empty($roomDataList) || !is_array($roomDataList)) {
                        $roomCount = max(1, (int)($booking_data['rooms'] ?? 1));
                        $adultCount = max(1, (int)($booking_data['adults'] ?? 2));
                        $childCount = (int)($booking_data['children'] ?? 0);
                        $roomDataList = array();
                        for ($i = 0; $i < $roomCount; $i++) {
                            $roomDataList[] = array(
                                'adults'    => max(1, round($adultCount / $roomCount)),
                                'children'  => ($i === 0) ? $childCount : 0,
                                'childAges' => ($i === 0 && $childCount > 0) ? array_fill(0, $childCount, 7) : array()
                            );
                        }
                    }
                    ?>

                    <!-- 1. Traveller Details Card (Akbar Travels Style) -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 12px; font-weight: 700;">
                            Traveller Details
                        </h3>

                        <!-- Info Alert Banner -->
                        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 14px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; font-size: 12.5px; color: #b45309;">
                            <i class="fa-solid fa-circle-exclamation" style="font-size: 14px; color: #d97706;"></i>
                            <span>Please make sure you enter the Name as per your Government photo id.</span>
                        </div>

                        <!-- Rooms Accordion List -->
                        <?php foreach ($roomDataList as $rIdx => $rm): 
                            $rNum = $rIdx + 1;
                            $rAdults = max(1, (int)($rm['adults'] ?? 1));
                            $rChildren = (int)($rm['children'] ?? 0);
                            $rChildAges = $rm['childAges'] ?? array();
                        ?>
                        <div style="border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 16px; overflow: hidden; background: #ffffff;">
                            <!-- Room Header -->
                            <div style="background: #f8fafc; padding: 12px 18px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 700; color: #0d3470; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-angle-down" style="color: #64748b; font-size: 13px;"></i>
                                Room <?php echo $rNum; ?>
                                <span style="font-size: 12px; font-weight: 500; color: #64748b; margin-left: 8px;">
                                    (<?php echo $rAdults; ?> Adult<?php echo $rAdults > 1 ? 's' : ''; ?><?php echo $rChildren > 0 ? ', ' . $rChildren . ' Child' . ($rChildren > 1 ? 'ren' : '') : ''; ?>)
                                </span>
                            </div>

                            <!-- Room Guests Body -->
                            <div style="padding: 16px 18px;">
                                <!-- Adults -->
                                <?php for ($a = 0; $a < $rAdults; $a++): 
                                    $paxNum = $a + 1;
                                    $isLead = ($rIdx === 0 && $a === 0);
                                ?>
                                <div class="traveller-row" style="display: grid; grid-template-columns: 80px 100px 1fr 1fr; gap: 12px; align-items: center; margin-bottom: 12px;">
                                    <div style="font-size: 13px; font-weight: 600; color: #475569;">
                                        Adult <?php echo $paxNum; ?>
                                    </div>
                                    <div>
                                        <select name="pax[<?php echo $rIdx; ?>][adults][<?php echo $a; ?>][title]" style="width: 100%; padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff; color: #1e293b;">
                                            <option value="Mr" <?php echo $isLead ? 'selected' : ''; ?>>Mr</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Mrs">Mrs</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" name="pax[<?php echo $rIdx; ?>][adults][<?php echo $a; ?>][fname]" required placeholder="First Name / Given Name" value="<?php echo $isLead ? htmlspecialchars($defaultFname) : ''; ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                    </div>
                                    <div>
                                        <input type="text" name="pax[<?php echo $rIdx; ?>][adults][<?php echo $a; ?>][lname]" required placeholder="Last Name / Surname" value="<?php echo $isLead ? htmlspecialchars($defaultLname) : ''; ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                    </div>
                                </div>
                                <?php endfor; ?>

                                <!-- Children -->
                                <?php if ($rChildren > 0): ?>
                                    <?php for ($c = 0; $c < $rChildren; $c++): 
                                        $cNum = $rAdults + $c + 1;
                                        $cAge = isset($rChildAges[$c]) && (int)$rChildAges[$c] > 0 ? (int)$rChildAges[$c] : (($c === 0) ? 7 : 3);
                                    ?>
                                    <div class="traveller-row" style="display: grid; grid-template-columns: 80px 100px 1fr 1fr; gap: 12px; align-items: center; margin-bottom: 12px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #475569;">
                                            Child <?php echo $cNum; ?>
                                            <div style="font-size: 11px; color: #94a3b8; font-weight: 400;">Age: <?php echo $cAge; ?></div>
                                        </div>
                                        <div>
                                            <select name="pax[<?php echo $rIdx; ?>][children][<?php echo $c; ?>][title]" style="width: 100%; padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff; color: #1e293b;">
                                                <option value="Mstr" selected>Mstr</option>
                                                <option value="Ms">Ms</option>
                                            </select>
                                        </div>
                                        <div>
                                            <input type="text" name="pax[<?php echo $rIdx; ?>][children][<?php echo $c; ?>][fname]" required placeholder="First Name / Given Name" value="" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                        </div>
                                        <div>
                                            <input type="text" name="pax[<?php echo $rIdx; ?>][children][<?php echo $c; ?>][lname]" required placeholder="Last Name / Surname" value="Sharma" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                            <input type="hidden" name="pax[<?php echo $rIdx; ?>][children][<?php echo $c; ?>][age]" value="<?php echo $cAge; ?>">
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!-- Save Entire Traveller details checkbox -->
                        <div style="margin-top: 14px; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="saveTravellerDetails" checked style="accent-color: #0d3470; width: 16px; height: 16px; cursor: pointer;">
                            <label for="saveTravellerDetails" style="font-size: 13px; color: #334155; cursor: pointer; font-weight: 500;">Save Entire Traveller details</label>
                        </div>
                    </div>

                    <!-- 2. Contact Information Card (Akbar Travels Style) -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 6px; font-weight: 700;">
                            Contact information
                        </h3>
                        <div style="font-size: 13px; color: #64748b; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-envelope-open-text" style="color: #ef4444; font-size: 14px;"></i>
                            Your ticket and hotels information will be sent here..
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <!-- Phone with +91 Country Code -->
                            <div>
                                <div style="display: flex;">
                                    <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #cbd5e1; border-right: none; border-radius: 6px 0 0 6px; padding: 0 10px; font-size: 13px; font-weight: 600; color: #334155; gap: 4px; white-space: nowrap;">
                                        <img src="https://flagcdn.com/w20/in.png" alt="IN" style="width: 16px; height: 11px; object-fit: cover; border-radius: 2px;">
                                        +91 <i class="fa-solid fa-angle-down" style="font-size: 10px; color: #94a3b8; margin-left: 2px;"></i>
                                    </div>
                                    <input type="tel" name="guest_phone" class="field-input" required placeholder="10-digit mobile" value="<?php echo htmlspecialchars($cleanPhone ?: $sessionUserPhone); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 0 6px 6px 0; font-size: 13px;">
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div>
                                <input type="email" name="guest_email" class="field-input" required placeholder="Email Address" value="<?php echo htmlspecialchars($sessionUserEmail ?: ''); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Travel Services Card (Hidden per user request) -->
                    <div style="display: none;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 6px; font-weight: 700;">
                            Travel services
                        </h3>
                        <div style="font-size: 12.5px; color: #64748b; margin-bottom: 14px;">
                            Save more by using our frequent travel service
                        </div>
                        <label style="display: inline-flex; align-items: center; gap: 12px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 18px; cursor: pointer; background: #f8fafc; transition: all 0.2s;">
                            <input type="checkbox" name="travel_insurance" value="1" style="width: 18px; height: 18px; accent-color: #0d3470; cursor: pointer;">
                            <i class="fa-solid fa-suitcase-medical" style="font-size: 22px; color: #0284c7;"></i>
                            <span style="font-size: 14px; font-weight: 700; color: #1e293b;">Domestic Travel Insurance</span>
                        </label>
                    </div>

                    <!-- 4. Essential Information Card (Exact Screenshot 1 Matching) -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 28px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 16px; font-weight: 700;">
                            Essential Information
                        </h3>
                        <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; font-size: 13px; color: #334155; line-height: 1.7; background: #ffffff;">
                            
                            <h4 style="font-size: 13.5px; font-weight: 800; color: #09204b; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                                HOTEL POLICY
                            </h4>
                            <p style="margin: 0 0 8px 0;">Unmarried couples allowed</p>
                            <p style="margin: 0 0 8px 0;">Local ids are allowed Acceptable ID Proofs are Passport, Aadhar, Driving License, and Govt. ID IDs of the same city at the property are allowed</p>
                            <p style="margin: 0 0 8px 0;">Pets NOT allowed within the premises</p>
                            <p style="margin: 0 0 8px 0;">Non-Veg allowed within the premises Outside food is not allowed</p>
                            <p style="margin: 0 0 18px 0;">Certain hotels may have mandatory gala dinner charges for Christmas and New Year's Eve, which, if applicable, are payable directly at the property during check-in. Bookings for 5 rooms or more will be treated as a group booking. Different policies and conditions may apply. Please contact the property for more information.</p>

                            <h4 style="font-size: 13.5px; font-weight: 800; color: #09204b; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                                CHECKIN SPECIAL INSTRUCTIONS
                            </h4>
                            <p style="margin: 0 0 8px 0;">These additional charge are not included in the booking amount and will be collected directly at the hotel.</p>
                            <p style="margin: 0 0 8px 0;">Extra beds for additional adults are subject to availability at the hotel and may be chargeable.</p>
                            <p style="margin: 0 0 8px 0;">During Christmas, New Year, or festive seasons, hotels may collect Gala Meal charges at check-in. If these charges are not included in the booking, they must be paid directly at the hotel.</p>
                            <p style="margin: 0 0 8px 0;">Hotels may also collect tourism taxes, city taxes, resort fees, or similar mandatory charges at check-in (payable in local currency).</p>
                            <p style="margin: 0 0 8px 0;">Peak-season or long-weekend surcharges may apply as per hotel policy and must be paid directly by the guest.</p>
                            <p style="margin: 0;">In rare cases, if the hotel is sold out due to peak dates or technical issues, an alternative hotel of similar value or a full refund will be provided.</p>
                        </div>
                    </div>

                    <!-- Continue to Payment Button (Exact Screenshot 1 Matching) -->
                    <div style="text-align: right; margin-bottom: 24px;">
                        <button type="submit" id="btnContinueToPayment" style="background: #ef4444; color: #ffffff; border: none; padding: 14px 42px; font-size: 15px; font-weight: 800; border-radius: 6px; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35); transition: background 0.15s, transform 0.15s;">
                            CONTINUE TO PAYMENT
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Side Price Breakdown (Exact Screenshot 5 Matching) -->
            <div>
                <div style="position: sticky; top: 90px; display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- Fare Summary Card -->
                    <div style="background: #ffffff; border-radius: 8px; padding: 22px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 18px;">
                            Fare Summary
                        </h3>

                        <!-- Room Rates line with chevron -->
                        <div style="border-bottom: 1px solid #f8fafc;">
                            <div onclick="toggleFareRow('fareRoomRatesDetails', 'chevronRoomRates')" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 14px; cursor: pointer; user-select: none;">
                                <div style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-weight: 600;">
                                    <i id="chevronRoomRates" class="fa-solid fa-angle-right" style="font-size: 12px; color: #64748b; transition: transform 0.2s;"></i>
                                    <span>Room Rates</span>
                                </div>
                                <strong style="color: #0f172a; font-weight: 700;">₹ <span id="dispBaseRate"><?php echo number_format($base_total); ?></span></strong>
                            </div>
                            <div id="fareRoomRatesDetails" style="display: none; padding: 0 0 10px 20px; font-size: 12.5px; color: #64748b;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <span>Base Room Tariff (<?php echo $nights; ?> Night<?php echo $nights > 1 ? 's' : ''; ?>, <?php echo $rooms; ?> Room<?php echo $rooms > 1 ? 's' : ''; ?>)</span>
                                    <span>₹ <?php echo number_format($base_total); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tax & Charges line with chevron -->
                        <div style="border-bottom: 1px solid #f8fafc;">
                            <div onclick="toggleFareRow('fareTaxDetails', 'chevronTax')" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 14px; cursor: pointer; user-select: none;">
                                <div style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-weight: 600;">
                                    <i id="chevronTax" class="fa-solid fa-angle-right" style="font-size: 12px; color: #64748b; transition: transform 0.2s;"></i>
                                    <span>Tax & Charges</span>
                                </div>
                                <strong style="color: #0f172a; font-weight: 700;">₹ <span id="dispTaxAmount"><?php echo number_format($taxes); ?></span></strong>
                            </div>
                            <div id="fareTaxDetails" style="display: none; padding: 0 0 10px 20px; font-size: 12.5px; color: #64748b;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <span>Hotel GST / Local VAT (10%)</span>
                                    <span>₹ <?php echo number_format(round($taxes * 0.83)); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Tourism Fee & Service Surcharges (2%)</span>
                                    <span>₹ <?php echo number_format(round($taxes * 0.17)); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Discount line with chevron -->
                        <div id="dispDiscountRow" style="display: <?php echo ($discount_total > 0) ? 'flex' : 'none'; ?>; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f8fafc; font-size: 14px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-weight: 600;">
                                <i class="fa-solid fa-angle-right" style="font-size: 12px; color: #64748b;"></i>
                                <span>Discount</span>
                            </div>
                            <strong style="color: #16a34a; font-weight: 700;">- ₹ <span id="dispDiscountAmount"><?php echo number_format($discount_total); ?></span></strong>
                        </div>

                        <!-- Total Amount -->
                        <div style="border-top: 1px solid #e2e8f0; margin-top: 14px; padding-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 16px; font-weight: 800; color: #0f172a;">Total Amount:</span>
                            <strong style="font-size: 22px; font-weight: 900; color: #0f172a;">₹ <span id="dispTotalAmount"><?php echo number_format($total_amount); ?></span></strong>
                        </div>
                    </div>

                    <!-- Promo Code Card (Exact Screenshot 5 Matching) -->
                    <div style="background: #ffffff; border-radius: 8px; padding: 20px 22px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
                        <h4 style="font-family: var(--font-heading); font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 6px;">
                            Promo code
                        </h4>
                        <div style="font-size: 12.5px; font-weight: 600; color: #475569; margin-bottom: 12px;">
                            Apply Promo Code
                        </div>

                        <div style="display: flex; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #fff;">
                            <input type="text" id="promoCodeInput" placeholder="ENTER PROMO CODE" value="" onkeypress="if(event.key === 'Enter') { event.preventDefault(); applyHotelPromo(); }" style="flex: 1; padding: 10px 14px; border: none; outline: none; font-size: 13.5px; font-weight: 700; text-transform: uppercase; color: #1e293b; letter-spacing: 0.5px;">
                            <button type="button" id="btnApplyPromo" onclick="applyHotelPromo()" style="background: #16a34a; color: #ffffff; width: 48px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: background 0.15s;">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>

                        <div id="promoFeedbackMsg" style="display: none; font-size: 12px; color: #16a34a; font-weight: 700; margin-top: 10px; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Your Promocode has been applied you've saved ₹ <strong id="promoSavedVal">0</strong></span>
                        </div>
                    </div>

                    <!-- Razorpay Security Badge -->
                    <div style="background: #f8fafc; border-radius: 8px; padding: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-shield-halved" style="color: #2563eb; font-size: 22px;"></i>
                        <div style="font-size: 12px; color: #64748b; line-height: 1.4;">
                            <strong style="color: #334155;">100% Safe & Secure Payment</strong><br>
                            Protected by 256-bit SSL Bank-Grade Encryption.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;

// In-page Login Success Handler (Called by Firebase OTP in header without page reload)
window.onBookingReviewLoginSuccess = function(user) {
    isUserLoggedIn = true;
    
    if (user.first_name) {
        var fn = document.querySelector('input[name="pax[0][adults][0][fname]"]');
        if (fn) fn.value = user.first_name;
    }
    if (user.last_name) {
        var ln = document.querySelector('input[name="pax[0][adults][0][lname]"]');
        if (ln) ln.value = user.last_name;
    }
    if (user.name) {
        var prim = document.getElementById('hidden_primary_guest_name');
        if (prim) prim.value = user.name;
    }
    if (user.email) {
        var em = document.querySelector('input[name="guest_email"]');
        if (em) em.value = user.email;
    }
    if (user.phone) {
        var ph = document.querySelector('input[name="guest_phone"]');
        var clean = user.phone.replace(/^\+91/, '');
        if (ph) ph.value = clean;
    }

    var banner = document.getElementById('hotelLoginBanner');
    if (banner) {
        banner.style.background = '#f0fdf4';
        banner.style.border = '1px solid #bbf7d0';
        banner.style.boxShadow = 'none';
        banner.innerHTML = '<div style="display: flex; align-items: center; gap: 14px;">' +
            '<div style="width: 40px; height: 40px; border-radius: 50%; background: #16a34a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;"><i class="fa-solid fa-circle-check"></i></div>' +
            '<div><div style="font-size: 14.5px; font-weight: 800; color: #166534;">Logged in as ' + (user.name || user.phone) + '</div>' +
            '<div style="font-size: 12.5px; color: #15803d;">Your verified contact details have been applied. You can now proceed to payment!</div></div>' +
            '</div>' +
            '<span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> Phone Verified</span>';
    }
};

// Prompt login on page load if guest
document.addEventListener('DOMContentLoaded', function() {
    if (!isUserLoggedIn) {
        setTimeout(function() {
            if (!isUserLoggedIn && typeof window.triggerBookingLogin === 'function') {
                window.triggerBookingLogin('Please log in with mobile OTP to continue your hotel booking.');
            }
        }, 1200);
    }
});

// Form submit validation & lead guest name population before navigating to Payment page
var form = document.getElementById('hotelBookingForm');
if (form) {
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return false;
        }

        var leadFname = document.querySelector('input[name="pax[0][adults][0][fname]"]') ? document.querySelector('input[name="pax[0][adults][0][fname]"]').value.trim() : '';
        var leadLname = document.querySelector('input[name="pax[0][adults][0][lname]"]') ? document.querySelector('input[name="pax[0][adults][0][lname]"]').value.trim() : '';
        var guestName = (leadFname + ' ' + leadLname).trim();
        if (!guestName) {
            var primInput = document.querySelector('input[name="primary_guest_name"]');
            if (primInput) guestName = primInput.value.trim();
        }
        if (guestName) {
            var hiddenPrim = document.getElementById('hidden_primary_guest_name');
            if (hiddenPrim) hiddenPrim.value = guestName;
        }
    });
}

function showHotelProcessingModal(message) {
    var overlay = document.getElementById('hotelPaymentProcessingOverlay');
    if (overlay) {
        document.getElementById('hotelProcessingModalMsg').innerText = message || "Payment Verified! Generating Hotel Voucher...";
        overlay.style.display = 'flex';
    }
}

// Promo Code Application Functionality
function applyHotelPromo() {
    var input = document.getElementById('promoCodeInput');
    var code = input ? input.value.trim().toUpperCase() : '';
    var feedback = document.getElementById('promoFeedbackMsg');
    var savedSpan = document.getElementById('promoSavedVal');
    var discRow = document.getElementById('dispDiscountRow');
    var discSpan = document.getElementById('dispDiscountAmount');
    var totalSpan = document.getElementById('dispTotalAmount');
    var payBtn = document.getElementById('payHotelRazorpayBtn');

    if (!code) {
        if (feedback) {
            feedback.style.display = 'flex';
            feedback.style.color = '#dc2626';
            feedback.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please enter a promo code.';
        }
        return;
    }

    var baseTotal = <?php echo (float)$base_total; ?>;
    var currentTotal = <?php echo (float)$total_amount; ?>;
    var discount = Math.round(baseTotal * 0.08); // 8% promo savings
    if (discount < 300) discount = 300;
    var newTotal = Math.max(1, currentTotal - discount);

    if (savedSpan) savedSpan.innerText = discount.toLocaleString();
    if (discSpan) discSpan.innerText = discount.toLocaleString();
    if (totalSpan) totalSpan.innerText = newTotal.toLocaleString();
    if (discRow) discRow.style.display = 'flex';
    if (payBtn) payBtn.innerHTML = '<i class="fa-solid fa-lock" style="margin-right: 8px;"></i> Pay ₹ ' + newTotal.toLocaleString() + ' & Confirm Voucher';

    if (feedback) {
        feedback.style.display = 'flex';
        feedback.style.color = '#16a34a';
        feedback.innerHTML = '<i class="fa-solid fa-circle-check"></i> Your Promocode <strong>' + code + '</strong> has been applied you\'ve saved ₹ <strong>' + discount.toLocaleString() + '</strong>';
    }
}

// Modals for Inclusions & Essential Info
function openEssentialInfoModal() {
    var overlay = document.getElementById('essentialInfoModal');
    if (overlay) overlay.style.display = 'flex';
}
function closeEssentialInfoModal() {
    var overlay = document.getElementById('essentialInfoModal');
    if (overlay) overlay.style.display = 'none';
}
function openInclusionsModal() {
    var overlay = document.getElementById('inclusionsModal');
    if (overlay) overlay.style.display = 'flex';
}
function closeInclusionsModal() {
    var overlay = document.getElementById('inclusionsModal');
    if (overlay) overlay.style.display = 'none';
}
</script>

<!-- Essential Info Modal -->
<div id="essentialInfoModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 99999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 520px; width: 100%; padding: 26px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h4 style="margin: 0; font-size: 17px; font-weight: 800; color: #09204b;"><i class="fa-solid fa-circle-info" style="color: #0284c7; margin-right: 6px;"></i> Essential Hotel Information</h4>
            <button type="button" onclick="closeEssentialInfoModal()" style="border: none; background: transparent; font-size: 20px; color: #64748b; cursor: pointer;">&times;</button>
        </div>
        <div style="font-size: 13.5px; color: #334155; line-height: 1.6;">
            <div style="margin-bottom: 14px;">
                <strong style="color: #0f172a;">Check-in / Check-out:</strong><br>
                Standard check-in time is 2:00 PM - 4:00 PM. Standard check-out time is 11:00 AM - 12:00 PM.
            </div>
            <div style="margin-bottom: 14px;">
                <strong style="color: #0f172a;">Cancellation Policy:</strong><br>
                <?php echo htmlspecialchars($cancellation_text); ?>
            </div>
            <div>
                <strong style="color: #0f172a;">Guest Identity:</strong><br>
                Government photo ID required at check-in for all adult guests (Passport, Voter ID, Driving License).
            </div>
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" onclick="closeEssentialInfoModal()" style="background: #0d3470; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

<!-- Inclusions Modal -->
<div id="inclusionsModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 99999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 500px; width: 100%; padding: 26px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h4 style="margin: 0; font-size: 17px; font-weight: 800; color: #09204b;"><i class="fa-solid fa-gift" style="color: #16a34a; margin-right: 6px;"></i> Room Inclusions</h4>
            <button type="button" onclick="closeInclusionsModal()" style="border: none; background: transparent; font-size: 20px; color: #64748b; cursor: pointer;">&times;</button>
        </div>
        <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #334155; line-height: 1.8;">
            <?php foreach ($inclusions as $inc): ?>
                <li><i class="fa-solid fa-check" style="color: #16a34a; margin-right: 6px;"></i> <?php echo htmlspecialchars($inc); ?></li>
            <?php endforeach; ?>
        </ul>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" onclick="closeInclusionsModal()" style="background: #16a34a; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer;">Got It</button>
        </div>
    </div>
</div>

<!-- Hotel Payment Processing Fullscreen Modal Overlay -->
<div id="hotelPaymentProcessingOverlay" style="display: none; position: fixed; inset: 0; background: rgba(13, 52, 112, 0.94); z-index: 999999; backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; flex-direction: column; color: #ffffff; text-align: center; padding: 20px;">
    <div style="background: #ffffff; color: #0d3470; border-radius: 20px; padding: 40px 32px; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #fff1f2; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 3px solid #fecdd3;">
            <i class="fa-solid fa-hotel fa-beat" style="font-size: 30px; color: #e11d48;"></i>
        </div>
        <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px;">Processing Your Hotel Booking</h3>
        <p id="hotelProcessingModalMsg" style="font-size: 14px; color: #64748b; margin-bottom: 24px; font-weight: 500;">Payment Verified! Generating your Official Hotel Voucher & Confirmation...</p>
        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden; position: relative;">
            <div style="height: 100%; width: 75%; background: linear-gradient(90deg, #e11d48, #f43f5e); border-radius: 4px; animation: pulseProgress 1.5s infinite ease-in-out;"></div>
        </div>
        <div style="font-size: 12px; color: #94a3b8; margin-top: 14px;">Please do not refresh or close this window.</div>
    </div>
</div>
<style>
@keyframes pulseProgress {
    0% { width: 30%; }
    50% { width: 90%; }
    100% { width: 30%; }
}
</style>
