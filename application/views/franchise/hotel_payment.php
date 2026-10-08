<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Voyogo Franchise B2B Hotel Payment & Float Settlement View
 * Replicates the Akbar Travels / OTA UX hotel payment recap while enforcing B2B Store Wallet Float settlement
 */

$b = $booking ?? ($booking_data ?? array());
$hotel_id = $b['hotel_id'] ?? 'HTL_101';
$hotel_name = $b['hotel_name'] ?? 'Luxury Hotel';
$hotel_address = $b['hotel_address'] ?? 'City Center';
$hotel_image = !empty($b['hotel_image']) ? $b['hotel_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
$room_type = $b['room_type'] ?? 'Deluxe Room';
$star_rating = (int)($b['star_rating'] ?? 5);
$checkin_date = $b['checkin'] ?? ($b['checkin_date'] ?? date('Y-m-d', strtotime('+3 days')));
$checkout_date = $b['checkout'] ?? ($b['checkout_date'] ?? date('Y-m-d', strtotime('+5 days')));
$nights = max(1, (int)($b['nights'] ?? 1));
$rooms = max(1, (int)($b['rooms'] ?? 1));
$adults = max(1, (int)($b['adults'] ?? 2));
$children = (int)($b['children'] ?? 0);
$total_amount = (float)($b['total_amount'] ?? ($b['grand_total'] ?? 4500));
$base_total = (float)($b['base_total'] ?? 0);
$taxes = (float)($b['taxes'] ?? 0);
if ($taxes <= 0 || $base_total <= 0) {
    $base_total = round($total_amount / 1.12, 2);
    $taxes = round($total_amount - $base_total, 2);
}
$discount_total = (float)($b['discount_total'] ?? 0);
$is_refundable = isset($b['is_refundable']) ? (bool)$b['is_refundable'] : true;
$cancellation_text = $b['cancellation_text'] ?? 'Free cancellation available';
$pax = $b['pax'] ?? array();
$primary_name = $b['primary_guest_name'] ?? ($store['store_name'] ?? 'Guest Partner');
$guest_email = $b['guest_email'] ?? ($store['email'] ?? 'booking@voyogo.com');
$guest_phone = $b['guest_phone'] ?? ($store['phone'] ?? '9876543210');
$special_requests = $b['special_requests'] ?? 'Non-smoking room';

// Format checkin / checkout dates nicely
$checkin_ts = strtotime($checkin_date);
$checkout_ts = strtotime($checkout_date);
$checkin_fmt = date('M d \'y', $checkin_ts) . ' ' . date('D, 2:00 PM', $checkin_ts);
$checkout_fmt = date('M d \'y', $checkout_ts) . ' ' . date('D, 12:00 PM', $checkout_ts);

$storeFloat = (float)($store['wallet_balance'] ?? 0);
$floatAfterBooking = $storeFloat - $total_amount;
$hasSufficientFloat = ($storeFloat >= $total_amount);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --voyogo-navy: #09204b;
    --voyogo-blue: #0284c7;
    --voyogo-green: #16a34a;
    --voyogo-brand-green: #78B722;
    --voyogo-red: #ef4444;
    --font-heading: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

body {
    background-color: #f4f6f8;
    color: #1e293b;
    font-family: var(--font-body);
}

.payment-page-container {
    max-width: 1220px;
    margin: 20px auto 40px;
    padding: 0 16px;
}

/* 4-Step Breadcrumb Bar */
.step-tracker-bar {
    background: #ffffff;
    border-radius: 10px;
    padding: 14px 22px;
    border: 1px solid #e2e8f0;
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.step-items-container {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.step-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
}

.step-item.completed {
    color: var(--voyogo-green);
}

.step-item.active {
    color: var(--voyogo-navy);
    background: #e0f2fe;
    padding: 4px 12px;
    border-radius: 20px;
}

.btn-back-link {
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s ease;
}

.btn-back-link:hover {
    color: var(--voyogo-blue);
}

/* Two Column Layout */
.payment-grid-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: flex-start;
}

@media (max-width: 991px) {
    .payment-grid-layout {
        grid-template-columns: 1fr;
    }
}

/* Section Title */
.pay-sec-title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 14px 0;
}

/* Hotel Details Summary Card */
.hotel-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 22px 24px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.hotel-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 6px;
}
.hotel-title-text {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.hotel-stars-span {
    color: #f59e0b;
    font-size: 13px;
    letter-spacing: 1px;
}
.refundable-badge {
    font-size: 12px;
    font-weight: 700;
    color: #16a34a;
    margin-right: 12px;
}
.cancellation-link {
    font-size: 12.5px;
    color: #09204b;
    font-weight: 700;
    text-decoration: underline;
    cursor: pointer;
}

.hotel-location-text {
    font-size: 12.5px;
    color: #0284c7;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.stay-timing-row {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 14px 0;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
.timing-block {
    display: flex;
    flex-direction: column;
}
.timing-lbl {
    font-size: 11px;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
}
.timing-val {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}
.stay-duration-tag {
    font-size: 12px;
    color: #64748b;
    font-weight: 700;
    border-bottom: 1px dashed #cbd5e1;
    padding-bottom: 2px;
}
.rooms-tag {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}
.free-cancel-tag {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #16a34a;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.room-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
}
.room-name-lbl {
    font-size: 14.5px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 2px;
}
.free-cancel-date-text {
    font-size: 12px;
    color: #ef4444;
    font-weight: 600;
}
.adults-count-lbl {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}
.inclusions-btn-link {
    color: #0284c7;
    font-size: 12px;
    font-weight: 700;
    text-decoration: underline;
    cursor: pointer;
    margin-left: 12px;
}

/* Blue Action Link: REVIEW YOUR ITINERARY -> */
.review-itinerary-action {
    display: flex;
    justify-content: flex-end;
    margin-top: 14px;
    padding-top: 10px;
    border-top: 1px solid #f8fafc;
}
.btn-review-itinerary {
    color: #0b438c;
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.15s;
    cursor: pointer;
}
.btn-review-itinerary:hover {
    color: #0284c7;
    text-decoration: underline;
}

/* Franchise Agent Strip */
.franchise-agent-strip {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 20px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}
.agent-badge-title {
    font-weight: 800;
    color: #09204b;
    display: flex;
    align-items: center;
    gap: 10px;
}
.agent-subtext {
    color: #64748b;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Traveller Details Pill Card */
.traveller-pill-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 20px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 13px;
}
.pill-label {
    font-weight: 800;
    color: #0f172a;
    white-space: nowrap;
}
.pill-data-capsule {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
    color: #334155;
    font-weight: 600;
}
.pill-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}
.pill-cell i {
    color: #94a3b8;
}

/* Franchise Store Wallet Settlement Box */
.wallet-settlement-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 24px;
}

.wallet-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.wallet-header-title {
    font-size: 18px;
    font-weight: 800;
    color: #09204b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.wallet-badge {
    background: #ecfdf5;
    color: #047857;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid #a7f3d0;
}

/* Wallet Float Breakdown 3-Box */
.float-breakdown-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
    text-align: center;
    margin-bottom: 22px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px;
}
.float-box-item {
    padding: 12px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}
.float-lbl {
    font-size: 11px;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    display: block;
    margin-bottom: 4px;
}
.float-val {
    font-size: 19px;
    font-weight: 800;
}

/* Right Sticky Sidebar */
.right-sidebar-sticky {
    position: sticky;
    top: 80px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.fare-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 22px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.fare-card-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 12px;
}

.fare-row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    color: #475569;
    margin-bottom: 10px;
}

.fare-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 2px dashed #e2e8f0;
    font-size: 16px;
    font-weight: 800;
    color: #09204b;
}

.btn-proceed-pay {
    width: 100%;
    background: var(--voyogo-brand-green);
    color: #ffffff;
    border: none;
    padding: 16px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(120, 183, 34, 0.4);
    transition: all 0.2s ease;
}
.btn-proceed-pay:hover {
    background: #6ba61e;
    transform: translateY(-1px);
}

/* ========================================================
   RIGHT SLIDE-OVER DRAWER MODAL
   ======================================================== */
.itinerary-drawer-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    z-index: 999999;
    justify-content: flex-end;
}

.itinerary-drawer-container {
    width: 600px;
    max-width: 92vw;
    height: 100vh;
    background: #ffffff;
    overflow-y: auto;
    box-shadow: -6px 0 30px rgba(0,0,0,0.25);
    display: flex;
    flex-direction: column;
    animation: slideDrawerIn 0.25s ease-out;
}

@keyframes slideDrawerIn {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

.drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 10;
}
.drawer-title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.drawer-close-btn {
    border: none;
    background: transparent;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    transition: background 0.15s;
}
.drawer-close-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.drawer-content {
    padding: 24px;
}

.drawer-hotel-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 8px;
    margin: 12px 0 16px 0;
}

/* Processing Overlay */
#hotelProcessingOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(9, 32, 75, 0.85);
    z-index: 9999999;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    text-align: center;
}
</style>

<div class="payment-page-container">

    <!-- 4-Step Breadcrumb Bar -->
    <div class="step-tracker-bar">
        <div class="step-items-container">
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 1. Hotel Search
            </span>
            <span style="color: #cbd5e1;">&rsaquo;</span>
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 2. Room Selection
            </span>
            <span style="color: #cbd5e1;">&rsaquo;</span>
            <span class="step-item completed">
                <i class="fa-solid fa-circle-check"></i> 3. Guest Details
            </span>
            <span style="color: #cbd5e1;">&rsaquo;</span>
            <span class="step-item active">
                <i class="fa-solid fa-wallet"></i> 4. Store Float Settlement
            </span>
        </div>
        <a href="<?php echo site_url('franchise/hotel_review'); ?>" class="btn-back-link">
            <i class="fa-solid fa-arrow-left"></i> Edit Guest Details
        </a>
    </div>

    <!-- Main Grid: Left Review & Float Settlement / Right Fare Summary -->
    <div class="payment-grid-layout">
        
        <!-- Left Column: Hotel Details & Store Float Deduction -->
        <div>
            
            <h2 class="pay-sec-title">Review your hotel reservation</h2>

            <!-- Hotel Summary Card -->
            <div class="hotel-summary-card">
                <div class="hotel-header-row">
                    <h3 class="hotel-title-text">
                        <?php echo htmlspecialchars($hotel_name); ?>
                        <span class="hotel-stars-span"><?php echo str_repeat('★', max(1, min(5, $star_rating))); ?></span>
                    </h3>
                    <div style="display: flex; align-items: center;">
                        <?php if ($is_refundable): ?>
                        <span class="refundable-badge"><i class="fa-solid fa-check"></i> Refundable</span>
                        <?php endif; ?>
                        <a href="javascript:void(0)" onclick="openItineraryDrawer()" class="cancellation-link">Cancellation Policy</a>
                    </div>
                </div>

                <div class="hotel-location-text">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?php echo htmlspecialchars($hotel_address); ?></span>
                </div>

                <div class="stay-timing-row">
                    <div class="timing-block">
                        <span class="timing-lbl">CHECK-IN</span>
                        <span class="timing-val"><?php echo $checkin_fmt; ?></span>
                    </div>

                    <div style="flex: 1; text-align: center;">
                        <span class="stay-duration-tag"><?php echo $nights; ?> NIGHT<?php echo $nights > 1 ? 'S' : ''; ?></span>
                    </div>

                    <div class="timing-block">
                        <span class="timing-lbl">CHECK-OUT</span>
                        <span class="timing-val"><?php echo $checkout_fmt; ?></span>
                    </div>

                    <div class="rooms-tag">
                        <?php echo $rooms; ?> ROOM<?php echo $rooms > 1 ? 'S' : ''; ?>
                    </div>

                    <div>
                        <span class="free-cancel-tag">
                            <i class="fa-regular fa-circle-check"></i> Free cancellation
                        </span>
                    </div>
                </div>

                <div class="room-meta-row">
                    <div>
                        <div class="room-name-lbl"><?php echo htmlspecialchars($room_type); ?></div>
                        <div class="free-cancel-date-text">
                            Free cancellation till <?php echo date('d M Y', strtotime($checkin_date . ' -2 days')); ?> 
                            <a href="javascript:void(0)" onclick="openItineraryDrawer()" style="color: #0284c7; text-decoration: underline; margin-left: 4px;">More Info</a>
                        </div>
                    </div>
                    <div>
                        <span class="adults-count-lbl"><?php echo $adults; ?> ADULT<?php echo $adults > 1 ? 'S' : ''; ?><?php echo $children > 0 ? " &bull; $children CHILD" : ''; ?></span>
                        <a href="javascript:void(0)" onclick="openItineraryDrawer()" class="inclusions-btn-link">Inclusions</a>
                    </div>
                </div>

                <!-- Blue Action Link: REVIEW YOUR ITINERARY -> -->
                <div class="review-itinerary-action">
                    <a href="javascript:void(0)" onclick="openItineraryDrawer()" class="btn-review-itinerary">
                        REVIEW YOUR ITINERARY <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Franchise Agent Strip -->
            <div class="franchise-agent-strip">
                <div class="agent-badge-title">
                    <i class="fa-solid fa-store" style="font-size: 16px; color: #0284c7;"></i>
                    <span>Franchise Partner: <strong><?php echo htmlspecialchars($store['store_name'] ?? ''); ?></strong> (Agent: <strong><?php echo htmlspecialchars($store['agent_code'] ?? ''); ?></strong>)</span>
                </div>
                <div class="agent-subtext">
                    <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                    <span>Authorized B2B Terminal</span>
                </div>
            </div>

            <!-- Traveller Details Summary Bar -->
            <div class="traveller-pill-card">
                <div class="pill-label">Primary Guest</div>
                <div class="pill-data-capsule">
                    <div class="pill-cell">
                        <i class="fa-solid fa-user"></i>
                        <span><?php echo htmlspecialchars($primary_name); ?></span>
                    </div>
                    <div class="pill-cell">
                        <i class="fa-solid fa-phone"></i>
                        <span><?php echo htmlspecialchars($guest_phone); ?></span>
                    </div>
                    <div class="pill-cell">
                        <i class="fa-solid fa-envelope"></i>
                        <span><?php echo htmlspecialchars($guest_email); ?></span>
                    </div>
                </div>
            </div>

            <!-- Franchise Store Wallet Float Deduction Card -->
            <div class="wallet-settlement-card">
                <div class="wallet-header">
                    <h3 class="wallet-header-title">
                        <i class="fa-solid fa-wallet" style="color: var(--voyogo-brand-green);"></i> Pay with Store Wallet Float
                    </h3>
                    <span class="wallet-badge">
                        <i class="fa-solid fa-circle-check"></i> B2B Instant Settlement
                    </span>
                </div>

                <!-- Float Metrics 3-Card Grid -->
                <div class="float-breakdown-grid">
                    <div class="float-box-item">
                        <span class="float-lbl">Available Store Float</span>
                        <strong class="float-val" style="color: #09204b;">₹ <?php echo number_format($storeFloat, 2); ?></strong>
                    </div>
                    <div class="float-box-item">
                        <span class="float-lbl">Reservation Total</span>
                        <strong class="float-val" style="color: #dc2626;">- ₹ <?php echo number_format($total_amount, 2); ?></strong>
                    </div>
                    <div class="float-box-item">
                        <span class="float-lbl">Float After Booking</span>
                        <strong class="float-val" style="color: <?php echo $hasSufficientFloat ? '#16a34a' : '#ef4444'; ?>;">
                            ₹ <?php echo number_format($floatAfterBooking, 2); ?>
                        </strong>
                    </div>
                </div>

                <!-- Final Booking Form Submitting to franchise/hotel_book -->
                <form id="hotelFinalBookingForm" action="<?php echo site_url('franchise/hotel_book'); ?>" method="POST" onsubmit="return confirmHotelBooking();">
                    
                    <!-- Core Booking Meta Hidden Inputs -->
                    <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel_id); ?>">
                    <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hotel_name); ?>">
                    <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($hotel_address); ?>">
                    <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($hotel_image); ?>">
                    <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($b['room_id'] ?? 'RM_01'); ?>">
                    <input type="hidden" name="room_group_id" value="<?php echo htmlspecialchars($b['room_group_id'] ?? 'RGRP_01'); ?>">
                    <input type="hidden" name="recommendation_id" value="<?php echo htmlspecialchars($b['recommendation_id'] ?? 'REC_01'); ?>">
                    <input type="hidden" name="search_id" value="<?php echo htmlspecialchars($b['search_id'] ?? ''); ?>">
                    <input type="hidden" name="tui" value="<?php echo htmlspecialchars($b['tui'] ?? ($b['search_tracing_key'] ?? '')); ?>">
                    <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($b['board_type'] ?? 'Breakfast Included'); ?>">
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($b['city'] ?? 'Goa'); ?>">
                    <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin_date); ?>">
                    <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout_date); ?>">
                    <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($checkin_date); ?>">
                    <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($checkout_date); ?>">
                    <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($rooms); ?>">
                    <input type="hidden" name="adults" value="<?php echo htmlspecialchars($adults); ?>">
                    <input type="hidden" name="children" value="<?php echo htmlspecialchars($children); ?>">
                    <input type="hidden" name="roomData" value="<?php echo htmlspecialchars($b['roomData'] ?? ''); ?>">
                    <input type="hidden" name="provider" value="<?php echo htmlspecialchars($b['provider'] ?? 'CleartripAPI'); ?>">
                    <input type="hidden" name="nights" value="<?php echo htmlspecialchars($nights); ?>">
                    <input type="hidden" name="base_total" value="<?php echo htmlspecialchars($base_total); ?>">
                    <input type="hidden" name="taxes" value="<?php echo htmlspecialchars($taxes); ?>">
                    <input type="hidden" name="discount_total" value="<?php echo htmlspecialchars($discount_total); ?>">
                    <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($total_amount); ?>">
                    <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
                    <input type="hidden" name="primary_guest_name" value="<?php echo htmlspecialchars($primary_name); ?>">
                    <input type="hidden" name="guest_email" value="<?php echo htmlspecialchars($guest_email); ?>">
                    <input type="hidden" name="guest_phone" value="<?php echo htmlspecialchars($guest_phone); ?>">
                    <input type="hidden" name="special_requests" value="<?php echo htmlspecialchars($special_requests); ?>">

                    <!-- Pax Array Inputs -->
                    <?php if (!empty($pax) && is_array($pax)): ?>
                        <?php foreach ($pax as $rIdx => $rData): ?>
                            <?php if (!empty($rData['adults']) && is_array($rData['adults'])): ?>
                                <?php foreach ($rData['adults'] as $aIdx => $adult): ?>
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][adults][<?php echo $aIdx; ?>][title]" value="<?php echo htmlspecialchars($adult['title'] ?? 'Mr'); ?>">
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][adults][<?php echo $aIdx; ?>][fname]" value="<?php echo htmlspecialchars($adult['fname'] ?? ''); ?>">
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][adults][<?php echo $aIdx; ?>][lname]" value="<?php echo htmlspecialchars($adult['lname'] ?? ''); ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (!empty($rData['children']) && is_array($rData['children'])): ?>
                                <?php foreach ($rData['children'] as $cIdx => $child): ?>
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][children][<?php echo $cIdx; ?>][title]" value="<?php echo htmlspecialchars($child['title'] ?? 'Mstr'); ?>">
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][children][<?php echo $cIdx; ?>][fname]" value="<?php echo htmlspecialchars($child['fname'] ?? ''); ?>">
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][children][<?php echo $cIdx; ?>][lname]" value="<?php echo htmlspecialchars($child['lname'] ?? ''); ?>">
                                    <input type="hidden" name="pax[<?php echo $rIdx; ?>][children][<?php echo $cIdx; ?>][age]" value="<?php echo htmlspecialchars($child['age'] ?? '5'); ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($hasSufficientFloat): ?>
                        <button type="submit" class="btn-proceed-pay">
                            <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i> Confirm & Deduct ₹ <?php echo number_format($total_amount); ?> from Store Float
                        </button>
                        <div style="display: flex; justify-content: center; align-items: center; gap: 16px; margin-top: 14px; font-size: 11.5px; color: #64748b;">
                            <span><i class="fa-solid fa-lock" style="color: #16a34a;"></i> Instant Float Settlement</span>
                            <span>&bull;</span>
                            <span>Official Hotel Confirmation Voucher</span>
                            <span>&bull;</span>
                            <span>Direct Supplier Connection (Benzy API)</span>
                        </div>
                    <?php else: ?>
                        <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; padding: 16px; color: #991b1b; font-size: 13.5px; margin-bottom: 14px;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px; margin-right: 8px;"></i>
                            <strong>Insufficient Store Wallet Float:</strong> Your available balance is ₹ <?php echo number_format($storeFloat, 2); ?>, but this booking requires ₹ <?php echo number_format($total_amount, 2); ?>. Please contact Franchise Admin for a float top-up.
                        </div>
                        <button type="button" disabled style="width: 100%; background: #94a3b8; color: #ffffff; border: none; padding: 16px; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fa-solid fa-ban"></i> Booking Blocked (Low Float)
                        </button>
                    <?php endif; ?>
                </form>
            </div>

        </div>

        <!-- Right Column: Sticky Fare Breakdown -->
        <div>
            <div class="right-sidebar-sticky">
                
                <!-- Fare Details Box -->
                <div class="fare-summary-card">
                    <div class="fare-card-title">
                        <span>Price Summary</span>
                        <span style="font-size: 12px; font-weight: 700; color: #0284c7;"><?php echo $rooms; ?> Room &bull; <?php echo $nights; ?> Night<?php echo $nights > 1 ? 's' : ''; ?></span>
                    </div>

                    <div class="fare-row-item">
                        <span>Room Base Price</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($base_total, 2); ?></strong>
                    </div>

                    <div class="fare-row-item">
                        <span>Hotel Taxes & GST</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($taxes, 2); ?></strong>
                    </div>

                    <?php if ($discount_total > 0): ?>
                    <div class="fare-row-item" style="color: #16a34a;">
                        <span>Promotional Discount</span>
                        <strong>- ₹ <?php echo number_format($discount_total, 2); ?></strong>
                    </div>
                    <?php endif; ?>

                    <div class="fare-row-item" style="font-size: 12px; color: #16a34a;">
                        <span>Convenience Fee</span>
                        <strong>FREE (₹ 0.00)</strong>
                    </div>

                    <div class="fare-total-row">
                        <span>Grand Total</span>
                        <span style="color: #09204b; font-size: 20px;">₹ <?php echo number_format($total_amount, 2); ?></span>
                    </div>

                    <div style="margin-top: 14px; padding: 10px; background: #f0fdf4; border-radius: 6px; border: 1px solid #bbf7d0; font-size: 11.5px; color: #166534; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-check" style="font-size: 14px;"></i>
                        <span>Includes all applicable government taxes and service charges.</span>
                    </div>
                </div>

                <!-- Agency Support Card -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; font-size: 12px; color: #64748b;">
                    <div style="font-weight: 800; color: #0f172a; margin-bottom: 6px; font-size: 13px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-headset" style="color: #0284c7;"></i> 24x7 B2B Partner Support
                    </div>
                    <div>Need help or float assistance?</div>
                    <div style="margin-top: 6px; font-weight: 700; color: #09204b;">
                        <i class="fa-solid fa-phone" style="color: #64748b; font-size: 11px;"></i> +91 8098999096 / support@voyogo.com
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- Slide-over Itinerary Drawer Modal -->
<div class="itinerary-drawer-overlay" id="itineraryDrawerOverlay" onclick="if(event.target === this) closeItineraryDrawer()">
    <div class="itinerary-drawer-container">
        <div class="drawer-header">
            <h3 class="drawer-title">Reservation Summary & Policy</h3>
            <button type="button" class="drawer-close-btn" onclick="closeItineraryDrawer()">&times;</button>
        </div>
        <div class="drawer-content">
            <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;"><?php echo htmlspecialchars($hotel_name); ?></h4>
            <div style="font-size: 12.5px; color: #64748b;"><i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> <?php echo htmlspecialchars($hotel_address); ?></div>
            
            <img src="<?php echo htmlspecialchars($hotel_image); ?>" alt="hotel" class="drawer-hotel-img" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80'">

            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin-bottom: 20px; border: 1px solid #e2e8f0; font-size: 13px;">
                <div style="font-weight: 800; color: #09204b; margin-bottom: 4px;"><?php echo htmlspecialchars($room_type); ?></div>
                <div style="color: #16a34a; font-weight: 700;"><?php echo htmlspecialchars($b['board_type'] ?? 'Breakfast Included'); ?></div>
                <div style="margin-top: 8px; color: #64748b;">
                    <?php echo $nights; ?> Night(s) &bull; <?php echo $rooms; ?> Room(s) &bull; <?php echo $adults; ?> Adult(s)<?php echo $children > 0 ? " &bull; $children Child(ren)" : ''; ?>
                </div>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-bottom: 20px;">
                <h5 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><i class="fa-solid fa-circle-info" style="color: #0284c7;"></i> Cancellation Policy</h5>
                <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin: 0;">
                    <?php echo htmlspecialchars($cancellation_text); ?>. Free cancellation is permitted up to 48 hours prior to check-in. Cancellations after this window or no-shows are subject to hotel charges equivalent to the first night's tariff.
                </p>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <h5 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;"><i class="fa-solid fa-id-card" style="color: #0284c7;"></i> Hotel Check-in Rules</h5>
                <ul style="font-size: 12.5px; color: #475569; padding-left: 20px; line-height: 1.6; margin: 0;">
                    <li>Standard check-in time is 02:00 PM and check-out is 11:00 AM.</li>
                    <li>Primary guest must be at least 18 years of age with a valid government ID.</li>
                    <li>PAN card is not accepted as address proof according to local municipal norms.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Processing Overlay -->
<div id="hotelProcessingOverlay">
    <div style="background: rgba(15, 23, 42, 0.95); padding: 32px 48px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
        <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 42px; color: #78B722; margin-bottom: 16px;"></i>
        <h3 style="font-size: 20px; font-weight: 800; margin: 0 0 8px 0;">Processing Store Float Settlement...</h3>
        <p style="font-size: 13px; color: #94a3b8; margin: 0;">Contacting supplier & issuing official hotel confirmation voucher.</p>
    </div>
</div>

<script>
function openItineraryDrawer() {
    var overlay = document.getElementById('itineraryDrawerOverlay');
    if (overlay) overlay.style.display = 'flex';
}

function closeItineraryDrawer() {
    var overlay = document.getElementById('itineraryDrawerOverlay');
    if (overlay) overlay.style.display = 'none';
}

function confirmHotelBooking() {
    var overlay = document.getElementById('hotelProcessingOverlay');
    if (overlay) overlay.style.display = 'flex';
    return true;
}
</script>
