<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$b = $booking ?? array();
$hotel_id = $b['hotel_id'] ?? 'HTL_101';
$hotel_name = $b['hotel_name'] ?? 'Luxury Hotel';
$hotel_address = $b['hotel_address'] ?? 'City Center';
$hotel_image = !empty($b['hotel_image']) ? $b['hotel_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
$room_type = $b['room_type'] ?? 'Deluxe Room';
$star_rating = (int)($b['star_rating'] ?? 5);
$checkin_date = $b['checkin'] ?? ($b['checkin_date'] ?? date('Y-m-d', strtotime('+3 days')));
$checkout_date = $b['checkout'] ?? ($b['checkout_date'] ?? date('Y-m-d', strtotime('+5 days')));
$nights = max(1, (int)($b['nights'] ?? 2));
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
$primary_name = $b['primary_guest_name'] ?? 'Guest User';
$guest_email = $b['guest_email'] ?? 'guest@voyogo.com';
$guest_phone = $b['guest_phone'] ?? '9876543210';

// Format checkin / checkout dates nicely
$checkin_ts = strtotime($checkin_date);
$checkout_ts = strtotime($checkout_date);
$checkin_fmt = date('M d \'y', $checkin_ts) . ' ' . date('D, 2:00 PM', $checkin_ts);
$checkout_fmt = date('M d \'y', $checkout_ts) . ' ' . date('D, 12:00 PM', $checkout_ts);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --voyogo-navy: #09204b;
    --voyogo-red: #ef4444;
    --voyogo-red-hover: #dc2626;
    --voyogo-blue: #0284c7;
    --voyogo-green: #16a34a;
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Inter', -apple-system, sans-serif;
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

/* Top Trust Badges Bar */
.payment-trust-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 32px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.trust-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.trust-item i {
    color: #94a3b8;
    font-size: 16px;
}

/* Two Column Layout */
.payment-grid-layout {
    display: grid;
    grid-template-columns: 1fr 360px;
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
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
}

/* Hotel Details Summary Card (Screenshot 2 Matching) */
.hotel-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px 22px;
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
    font-size: 17px;
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
    font-size: 12px;
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
    padding: 12px 0;
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
    font-size: 13.5px;
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
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.room-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
}
.room-name-lbl {
    font-size: 14px;
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
    font-size: 12.5px;
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

/* Logged in / User Strip */
.logged-in-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
}
.logged-user-name {
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.logged-subtext {
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
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 18px;
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
    padding: 6px 16px;
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

/* Make Payment Box (Exact Screenshot 2 Matching) */
.make-payment-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
    margin-bottom: 24px;
}
.convenience-fee-strip {
    background: #f0fdf4;
    border-bottom: 1px solid #dcfce7;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #15803d;
}
.zero-fee-badge {
    background: #16a34a;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 3px;
    text-transform: uppercase;
}

.payment-methods-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    min-height: 420px;
}

@media (max-width: 680px) {
    .payment-methods-grid {
        grid-template-columns: 1fr;
    }
}

/* Payment Left Tabs */
.payment-tabs-col {
    background: #fafafa;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}
.payment-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 18px;
    text-align: left;
    font-size: 13.5px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.15s;
}
.payment-tab-btn i {
    font-size: 15px;
    width: 20px;
    color: #64748b;
}
.payment-tab-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.payment-tab-btn.active {
    background: #ffffff;
    color: #ef4444;
    border-left: 4px solid #ef4444;
}
.payment-tab-btn.active i {
    color: #ef4444;
}

/* Payment Tab Contents */
.payment-content-col {
    padding: 24px;
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
}

/* UPI Content Styling */
.upi-steps-row {
    display: flex;
    justify-content: space-around;
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.upi-step-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 10px;
}
.upi-step-num {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    margin-bottom: 6px;
}
.upi-step-icon {
    width: 48px;
    height: 48px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #0284c7;
    margin-bottom: 8px;
}
.upi-step-desc {
    font-size: 11.5px;
    color: #64748b;
    line-height: 1.4;
}

.upi-input-box {
    margin-bottom: 20px;
}
.upi-vpa-input {
    width: 100%;
    max-width: 320px;
    padding: 11px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13.5px;
    outline: none;
    margin-bottom: 12px;
}
.upi-vpa-input:focus {
    border-color: #ef4444;
}

.btn-make-payment-red {
    background: #ef4444;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    font-size: 14.5px;
    font-weight: 800;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
    transition: background 0.15s, transform 0.15s;
}
.btn-make-payment-red:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.or-divider-row {
    position: relative;
    text-align: center;
    margin: 24px 0;
}
.or-divider-row:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
}
.or-circle-badge {
    position: relative;
    background: #ffffff;
    display: inline-block;
    padding: 0 12px;
    font-size: 12px;
    font-weight: 800;
    color: #94a3b8;
}

.qr-pay-area {
    display: flex;
    align-items: center;
    gap: 24px;
}
.upi-apps-icons {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}
.app-logo-badge {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Card Form Styles */
.card-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    max-width: 440px;
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
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.promo-offers-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.promo-radio-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
    cursor: pointer;
}
.promo-radio-item:last-child {
    border-bottom: none;
}
.promo-code-name {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
}
.promo-code-desc {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
}

.session-countdown-pill {
    text-align: center;
    font-size: 12.5px;
    color: #64748b;
    font-weight: 600;
    padding: 8px 12px;
    background: #f1f5f9;
    border-radius: 6px;
}

/* ========================================================
   RIGHT SLIDE-OVER DRAWER MODAL (Exact Screenshot 3 Matching)
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

.drawer-accordion-sec {
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 0;
}
.drawer-acc-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    cursor: pointer;
    user-select: none;
}
.drawer-acc-body {
    padding-top: 10px;
    font-size: 13px;
    color: #475569;
}

/* Itemized Fare Table (Screenshot 3) */
.drawer-fare-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 13px;
}
.drawer-fare-table th {
    text-align: left;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    padding: 6px 0;
    border-bottom: 1px solid #e2e8f0;
}
.drawer-fare-table td {
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
    color: #0f172a;
    font-weight: 600;
}
</style>

<div class="payment-page-container">

    <!-- Top Trust Badges Bar (Screenshot 2 Matching) -->
    <div class="payment-trust-bar">
        <div class="trust-item">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Secure Payment</span>
        </div>
        <div class="trust-item">
            <i class="fa-solid fa-credit-card"></i>
            <span>10 Million + Transactions</span>
        </div>
        <div class="trust-item">
            <i class="fa-solid fa-lock"></i>
            <span>256 Bit Encryption</span>
        </div>
    </div>

    <!-- Back to Review Navigation -->
    <div style="margin-bottom: 16px;">
        <a href="<?php echo site_url('hotels/review'); ?>" style="color: #0b438c; font-weight: 700; font-size: 13.5px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.15s;">
            <i class="fa-solid fa-arrow-left"></i> Back to Review & Guest Details
        </a>
    </div>

    <!-- Main Grid: Left Payment Details & Right Fare Breakdown -->
    <div class="payment-grid-layout">
        
        <!-- Left Column: Hotel Details & Make Payment -->
        <div>
            
            <h2 class="pay-sec-title">Review your hotel details</h2>

            <!-- Hotel Summary Card (Screenshot 2 Matching) -->
            <div class="hotel-summary-card">
                <div class="hotel-header-row">
                    <h3 class="hotel-title-text">
                        <?php echo htmlspecialchars($hotel_name); ?>
                        <span class="hotel-stars-span"><?php echo str_repeat('★', max(1, min(5, $star_rating))); ?></span>
                    </h3>
                    <div style="display: flex; align-items: center;">
                        <?php if ($is_refundable): ?>
                        <span class="refundable-badge">Refundable</span>
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
                        <span class="stay-duration-tag"><?php echo $nights; ?> NIGHTS</span>
                    </div>

                    <div class="timing-block">
                        <span class="timing-lbl">CHECK-OUT</span>
                        <span class="timing-val"><?php echo $checkout_fmt; ?></span>
                    </div>

                    <div class="rooms-tag">
                        <?php echo $rooms; ?> ROOM
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
                        <span class="adults-count-lbl"><?php echo $adults; ?> ADULT<?php echo $adults > 1 ? 'S' : ''; ?></span>
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

            <!-- Logged-in Status Bar -->
            <div class="logged-in-bar">
                <div class="logged-user-name">
                    <i class="fa-solid fa-circle-user" style="font-size: 16px; color: #0284c7;"></i>
                    <span>Logged in as <?php echo htmlspecialchars(!empty($sessionUserName) ? $sessionUserName : $primary_name); ?></span>
                </div>
                <div class="logged-subtext">
                    <i class="fa-solid fa-circle-info" style="color: #0284c7;"></i>
                    <span>Details will be sent to this address</span>
                </div>
            </div>

            <!-- Traveller Details Summary Bar -->
            <div class="traveller-pill-card">
                <div class="pill-label">Traveller Details</div>
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

            <!-- Make Payment Section (Screenshot 2 Matching) -->
            <div class="make-payment-container">
                <div class="convenience-fee-strip">
                    <span class="zero-fee-badge">NOTE</span>
                    <span>Great! Zero on convenience Fee on Hotel Bookings. Save More! Travel More!</span>
                </div>

                <div class="payment-methods-grid">
                    
                    <!-- Left Payment Navigation Tabs -->
                    <div class="payment-tabs-col">
                        <button type="button" class="payment-tab-btn active" onclick="switchPayTab(this, 'tabUPI')">
                            <i class="fa-solid fa-mobile-screen-button"></i> UPI
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabCard')">
                            <i class="fa-solid fa-credit-card"></i> Credit Card
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabCard')">
                            <i class="fa-brands fa-cc-amex"></i> AMEX
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabCard')">
                            <i class="fa-solid fa-credit-card"></i> Debit Card
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabNetBanking')">
                            <i class="fa-solid fa-building-columns"></i> Net Banking
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabCard')">
                            <i class="fa-solid fa-calculator"></i> Credit Card EMI
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabWallet')">
                            <i class="fa-solid fa-wallet"></i> Wallet
                        </button>
                        <button type="button" class="payment-tab-btn" onclick="switchPayTab(this, 'tabUPI')">
                            <i class="fa-brands fa-google-pay"></i> Google Pay
                        </button>
                    </div>

                    <!-- Right Payment Tab Content Area -->
                    <div class="payment-content-col">
                        
                        <!-- 1. UPI Tab Content -->
                        <div id="tabUPI" class="tab-pane active">
                            <div class="upi-steps-row">
                                <div class="upi-step-item">
                                    <div class="upi-step-num">1</div>
                                    <div class="upi-step-icon"><i class="fa-solid fa-mobile-screen"></i></div>
                                    <div class="upi-step-desc">Enter VPA on Voyogo payment page</div>
                                </div>
                                <div class="upi-step-item">
                                    <div class="upi-step-num">2</div>
                                    <div class="upi-step-icon"><i class="fa-solid fa-building-columns"></i></div>
                                    <div class="upi-step-desc">Go to your bank's app to access our request</div>
                                </div>
                                <div class="upi-step-item">
                                    <div class="upi-step-num">3</div>
                                    <div class="upi-step-icon"><i class="fa-solid fa-shield-halved"></i></div>
                                    <div class="upi-step-desc">Enter MPIN to authenticate on your bank app</div>
                                </div>
                            </div>

                            <div class="upi-input-box">
                                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                                    Virtual payment address
                                </label>
                                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                                    <input type="text" id="upiVpaInput" placeholder="Virtual payment address" value="" class="upi-vpa-input">
                                    <button type="button" onclick="triggerRazorpayHotelPayment()" class="btn-make-payment-red">
                                        Make Payment
                                    </button>
                                </div>
                            </div>

                            <div class="or-divider-row">
                                <span class="or-circle-badge">OR</span>
                            </div>

                            <div class="qr-pay-area">
                                <div>
                                    <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                        Scan and Pay with UPI App
                                    </div>
                                    <div style="font-size: 12px; color: #64748b;">
                                        Scan and make payment using any banking UPI app
                                    </div>
                                    <div class="upi-apps-icons">
                                        <span class="app-logo-badge"><i class="fa-brands fa-google-pay" style="color: #ea4335;"></i> GPay</span>
                                        <span class="app-logo-badge"><i class="fa-solid fa-wallet" style="color: #5f259f;"></i> PhonePe</span>
                                        <span class="app-logo-badge"><i class="fa-solid fa-money-bill-wave" style="color: #00b9f1;"></i> Paytm</span>
                                        <span class="app-logo-badge"><i class="fa-solid fa-building-columns"></i> BHIM</span>
                                    </div>
                                </div>

                                <button type="button" onclick="triggerRazorpayHotelPayment()" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 18px; font-size: 12.5px; font-weight: 700; color: #334155; cursor: pointer; white-space: nowrap;">
                                    <i class="fa-solid fa-qrcode" style="margin-right: 6px;"></i> View QR Code
                                </button>
                            </div>
                        </div>

                        <!-- 2. Card Tab Content -->
                        <div id="tabCard" class="tab-pane">
                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 14px;">
                                Enter Card Details
                            </h4>
                            <div class="card-form-grid">
                                <div style="grid-column: span 2;">
                                    <label style="font-size: 12px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">Card Number</label>
                                    <input type="text" placeholder="XXXX XXXX XXXX XXXX" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label style="font-size: 12px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">Name on Card</label>
                                    <input type="text" placeholder="Cardholder Name" value="<?php echo htmlspecialchars($primary_name); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">Expiry (MM/YY)</label>
                                    <input type="text" placeholder="MM / YY" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">CVV</label>
                                    <input type="password" placeholder="CVV" maxlength="4" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                                </div>
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="button" onclick="triggerRazorpayHotelPayment()" class="btn-make-payment-red">
                                    Pay ₹ <span class="dispPayBtnAmount"><?php echo number_format($total_amount); ?></span>
                                </button>
                            </div>
                        </div>

                        <!-- 3. Net Banking Tab Content -->
                        <div id="tabNetBanking" class="tab-pane">
                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 14px;">
                                Select Popular Banks
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px;">
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank" checked> SBI
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank"> HDFC Bank
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank"> ICICI Bank
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank"> Axis Bank
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank"> Kotak Bank
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; cursor: pointer; font-size: 12.5px; font-weight: 700;">
                                    <input type="radio" name="nbBank"> PNB
                                </label>
                            </div>
                            <button type="button" onclick="triggerRazorpayHotelPayment()" class="btn-make-payment-red">
                                Pay ₹ <span class="dispPayBtnAmount"><?php echo number_format($total_amount); ?></span>
                            </button>
                        </div>

                        <!-- 4. Wallet Tab Content -->
                        <div id="tabWallet" class="tab-pane">
                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 14px;">
                                Select Digital Wallet
                            </h4>
                            <div style="display: flex; flex-direction: column; gap: 10px; max-width: 320px; margin-bottom: 20px;">
                                <label style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700;">
                                    <input type="radio" name="digitalWallet" checked> Paytm Wallet
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700;">
                                    <input type="radio" name="digitalWallet"> PhonePe / BHIM Wallet
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700;">
                                    <input type="radio" name="digitalWallet"> Mobikwik
                                </label>
                            </div>
                            <button type="button" onclick="triggerRazorpayHotelPayment()" class="btn-make-payment-red">
                                Pay ₹ <span class="dispPayBtnAmount"><?php echo number_format($total_amount); ?></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Fare Summary & Promo Code Offers -->
        <div class="right-sidebar-sticky">
            
            <!-- Fare Summary Card (Screenshot 2 Matching) -->
            <div class="fare-summary-card">
                <h3 style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px;">
                    Fare Summary
                </h3>

                <!-- Room Rates Accordion -->
                <div style="border-bottom: 1px solid #f8fafc; padding: 10px 0;">
                    <div onclick="toggleFareDetails('fareRoomRateBox', 'iconFareRoomRate')" style="display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; cursor: pointer; user-select: none;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #1e293b;">
                            <i id="iconFareRoomRate" class="fa-solid fa-angle-right" style="font-size: 11px; color: #64748b; transition: transform 0.2s;"></i>
                            <span>Room Rates</span>
                        </div>
                        <strong style="color: #0f172a; font-weight: 700;">₹ <span id="dispBaseRate"><?php echo number_format($base_total); ?></span></strong>
                    </div>
                    <div id="fareRoomRateBox" style="display: none; padding: 6px 0 0 18px; font-size: 12px; color: #64748b;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Room 1</span>
                            <span>₹ <?php echo number_format($base_total); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tax & Charges Accordion -->
                <div style="border-bottom: 1px solid #f8fafc; padding: 10px 0;">
                    <div onclick="toggleFareDetails('fareTaxBox', 'iconFareTax')" style="display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; cursor: pointer; user-select: none;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #1e293b;">
                            <i id="iconFareTax" class="fa-solid fa-angle-right" style="font-size: 11px; color: #64748b; transition: transform 0.2s;"></i>
                            <span>Tax & Charges</span>
                        </div>
                        <strong style="color: #0f172a; font-weight: 700;">₹ <span id="dispTaxAmount"><?php echo number_format($taxes); ?></span></strong>
                    </div>
                    <div id="fareTaxBox" style="display: none; padding: 6px 0 0 18px; font-size: 12px; color: #64748b;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                            <span>Hotel GST / Local VAT (10%)</span>
                            <span>₹ <?php echo number_format(round($taxes * 0.83)); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Tourism & Municipal Fee (2%)</span>
                            <span>₹ <?php echo number_format(round($taxes * 0.17)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Discount Accordion (Dynamic) -->
                <div id="fareDiscountRow" style="display: <?php echo ($discount_total > 0) ? 'block' : 'none'; ?>; border-bottom: 1px solid #f8fafc; padding: 10px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13.5px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #1e293b;">
                            <i class="fa-solid fa-angle-right" style="font-size: 11px; color: #64748b;"></i>
                            <span>Discount</span>
                        </div>
                        <strong style="color: #16a34a; font-weight: 700;">- ₹ <span id="dispDiscountVal"><?php echo number_format($discount_total); ?></span></strong>
                    </div>
                </div>

                <!-- Total Amount Line -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; margin-top: 8px; border-top: 1px solid #e2e8f0;">
                    <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Total Amount:</span>
                    <strong style="font-size: 20px; font-weight: 900; color: #0f172a;">₹ <span id="dispTotalNetVal"><?php echo number_format($total_amount); ?></span></strong>
                </div>
            </div>

            <!-- Promo Code Offers Card (Exact Screenshot 2 Matching) -->
            <div class="promo-offers-card">
                <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px;">
                    Promo code
                </h4>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 10px;">
                    Apply Promo Code
                </div>

                <!-- Input Promo Box -->
                <div style="display: flex; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #fff; margin-bottom: 16px;">
                    <input type="text" id="customPromoInput" placeholder="ENTER PROMO CODE" value="" style="flex: 1; padding: 9px 12px; border: none; outline: none; font-size: 13px; font-weight: 700; text-transform: uppercase; color: #1e293b;">
                    <button type="button" onclick="applyCustomPromo()" style="background: #16a34a; color: #ffffff; width: 44px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>

                <div id="promoNoticeMsg" style="display: none; font-size: 11.5px; color: #16a34a; font-weight: 700; margin-bottom: 14px;">
                    <i class="fa-solid fa-circle-check"></i> <span id="promoNoticeTxt"></span>
                </div>

                <div class="or-divider-row" style="margin: 14px 0;">
                    <span class="or-circle-badge">OR</span>
                </div>

                <div style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 10px;">
                    Choose from the offers below
                </div>

                <!-- Selectable Radio Offers List (Exact Screenshot 2) -->
                <div>
                    <label class="promo-radio-item">
                        <input type="radio" name="bankOfferRadio" value="ATHOTEL" onchange="selectOfferRadio('ATHOTEL', 0.08, 'Get up to 75% instant discount on your booking. T&C Apply')" style="accent-color: #0284c7; margin-top: 3px;">
                        <div>
                            <div class="promo-code-name">ATHOTEL</div>
                            <div class="promo-code-desc">Get up to 75% instant discount on your booking. T&C Apply</div>
                        </div>
                    </label>

                    <label class="promo-radio-item">
                        <input type="radio" name="bankOfferRadio" value="ATAUHOTEL" onchange="selectOfferRadio('ATAUHOTEL', 0.14, 'Flat 14% deal applicable on AU Bank Credit/Debit Cards, T&C Apply')" style="accent-color: #0284c7; margin-top: 3px;">
                        <div>
                            <div class="promo-code-name">ATAUHOTEL</div>
                            <div class="promo-code-desc">Flat 14% deal applicable on AU Bank Credit/Debit Cards, T&C Apply.</div>
                        </div>
                    </label>

                    <label class="promo-radio-item">
                        <input type="radio" name="bankOfferRadio" value="ATDBSHOTEL" onchange="selectOfferRadio('ATDBSHOTEL', 0.14, 'Flat 14% offer applicable on DBS bank selected cards')" style="accent-color: #0284c7; margin-top: 3px;">
                        <div>
                            <div class="promo-code-name">ATDBSHOTEL</div>
                            <div class="promo-code-desc">Flat 14% offer applicable on DBS bank selected cards</div>
                        </div>
                    </label>

                    <label class="promo-radio-item">
                        <input type="radio" name="bankOfferRadio" value="ATIDFCPROMO" onchange="selectOfferRadio('ATIDFCPROMO', 0.14, 'Flat 14% deal applicable on IDFC Bank Credit/Debit Cards, T&C Apply. Flat Off')" style="accent-color: #0284c7; margin-top: 3px;">
                        <div>
                            <div class="promo-code-name">ATIDFCPROMO</div>
                            <div class="promo-code-desc">Flat 14% deal applicable on IDFC Bank Credit/Debit Cards, T&C Apply. Flat Off</div>
                        </div>
                    </label>

                    <label class="promo-radio-item">
                        <input type="radio" name="bankOfferRadio" value="ATSBI40" onchange="selectOfferRadio('ATSBI40', 0.14, 'Get Instant Discount up to 14% on domestic hotels with SBI Credit Card, T&C Apply')" style="accent-color: #0284c7; margin-top: 3px;">
                        <div>
                            <div class="promo-code-name">ATSBI40</div>
                            <div class="promo-code-desc">Get Instant Discount up to 14% on domestic hotels with SBI Credit Card, T&C Apply</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Session Countdown Timer (Screenshot 2 Matching) -->
            <div class="session-countdown-pill">
                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                Your session will expire in <span id="countdownTimer" style="font-weight: 800; color: #0f172a;">09 min: 58 sec</span>
            </div>

        </div>

    </div>

</div>

<!-- Hidden POST form to complete the booking via process_payment on successful payment -->
<form id="hotelFinalBookingForm" action="<?php echo site_url('hotels/process_payment'); ?>" method="POST" style="display: none;">
    <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel_id); ?>">
    <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hotel_name); ?>">
    <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($hotel_address); ?>">
    <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($hotel_image); ?>">
    <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>">
    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($b['room_id'] ?? 'RM_01'); ?>">
    <input type="hidden" name="room_group_id" value="<?php echo htmlspecialchars($b['room_group_id'] ?? 'RGRP_01'); ?>">
    <input type="hidden" name="recommendation_id" value="<?php echo htmlspecialchars($b['recommendation_id'] ?? 'REC_01'); ?>">
    <input type="hidden" name="search_id" value="<?php echo htmlspecialchars($b['search_id'] ?? ''); ?>">
    <input type="hidden" name="tui" value="<?php echo htmlspecialchars($b['tui'] ?? ''); ?>">
    <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($b['board_type'] ?? 'Breakfast Included'); ?>">
    <input type="hidden" name="city" value="<?php echo htmlspecialchars($b['city'] ?? 'Goa'); ?>">
    <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin_date); ?>">
    <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout_date); ?>">
    <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($rooms); ?>">
    <input type="hidden" name="adults" value="<?php echo htmlspecialchars($adults); ?>">
    <input type="hidden" name="children" value="<?php echo htmlspecialchars($children); ?>">
    <input type="hidden" name="roomData" value="<?php echo htmlspecialchars($b['roomData'] ?? ''); ?>">
    <input type="hidden" name="provider" value="<?php echo htmlspecialchars($b['provider'] ?? 'CleartripAPI'); ?>">
    <input type="hidden" name="nights" value="<?php echo htmlspecialchars($nights); ?>">
    <input type="hidden" name="base_total" value="<?php echo htmlspecialchars($base_total); ?>">
    <input type="hidden" name="taxes" value="<?php echo htmlspecialchars($taxes); ?>">
    <input type="hidden" name="discount_total" id="formDiscountTotal" value="<?php echo htmlspecialchars($discount_total); ?>">
    <input type="hidden" name="grand_total" id="formGrandTotal" value="<?php echo htmlspecialchars($total_amount); ?>">
    <input type="hidden" name="total_amount" id="formTotalAmount" value="<?php echo htmlspecialchars($total_amount); ?>">
    <input type="hidden" name="primary_guest_name" value="<?php echo htmlspecialchars($primary_name); ?>">
    <input type="hidden" name="guest_email" value="<?php echo htmlspecialchars($guest_email); ?>">
    <input type="hidden" name="razorpay_payment_id" id="formRazorpayPaymentId" value="">
    <input type="hidden" name="razorpay_order_id" id="formRazorpayOrderId" value="">
    <input type="hidden" name="razorpay_signature" id="formRazorpaySignature" value="">
    <?php
    // Serialize pax data
    if (!empty($pax) && is_array($pax)) {
        foreach ($pax as $rI => $rm) {
            if (!empty($rm['adults'])) {
                foreach ($rm['adults'] as $aI => $ad) {
                    echo '<input type="hidden" name="pax[' . $rI . '][adults][' . $aI . '][title]" value="' . htmlspecialchars($ad['title'] ?? 'Mr') . '">';
                    echo '<input type="hidden" name="pax[' . $rI . '][adults][' . $aI . '][fname]" value="' . htmlspecialchars($ad['fname'] ?? '') . '">';
                    echo '<input type="hidden" name="pax[' . $rI . '][adults][' . $aI . '][lname]" value="' . htmlspecialchars($ad['lname'] ?? '') . '">';
                }
            }
            if (!empty($rm['children'])) {
                foreach ($rm['children'] as $cI => $ch) {
                    echo '<input type="hidden" name="pax[' . $rI . '][children][' . $cI . '][title]" value="' . htmlspecialchars($ch['title'] ?? 'Mstr') . '">';
                    echo '<input type="hidden" name="pax[' . $rI . '][children][' . $cI . '][fname]" value="' . htmlspecialchars($ch['fname'] ?? '') . '">';
                    echo '<input type="hidden" name="pax[' . $rI . '][children][' . $cI . '][lname]" value="' . htmlspecialchars($ch['lname'] ?? '') . '">';
                }
            }
        }
    }
    ?>
</form>

<!-- ========================================================
     SLIDE-OVER ITINERARY DRAWER (Exact Screenshot 3 Matching)
     ======================================================== -->
<div id="itineraryDrawerOverlay" class="itinerary-drawer-overlay" onclick="onDrawerOverlayClick(event)">
    <div class="itinerary-drawer-container">
        
        <!-- Header -->
        <div class="drawer-header">
            <h3 class="drawer-title">Review your hotel Itinerary</h3>
            <button type="button" class="drawer-close-btn" onclick="closeItineraryDrawer()">&times;</button>
        </div>

        <!-- Body Content -->
        <div class="drawer-content">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                        <?php echo htmlspecialchars($hotel_name); ?>
                        <span class="hotel-stars-span"><?php echo str_repeat('★', max(1, min(5, $star_rating))); ?></span>
                    </h4>
                    <div style="font-size: 12.5px; color: #0284c7; margin-bottom: 8px;">
                        <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hotel_address); ?>
                    </div>
                </div>
                <a href="javascript:void(0)" onclick="alert('Cancellation policy: <?php echo addslashes($cancellation_text); ?>')" style="font-size: 12px; color: #09204b; font-weight: 700; text-decoration: underline; white-space: nowrap;">
                    Cancellation Policy
                </a>
            </div>

            <!-- Hotel Hero Image -->
            <img src="<?php echo htmlspecialchars($hotel_image); ?>" alt="<?php echo htmlspecialchars($hotel_name); ?>" class="drawer-hotel-img">

            <!-- Timing Block -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; margin-bottom: 16px;">
                <div>
                    <span class="timing-lbl">CHECK-IN</span>
                    <div class="timing-val"><?php echo $checkin_fmt; ?></div>
                </div>
                <div style="text-align: center;">
                    <span class="stay-duration-tag"><?php echo $nights; ?> Nights</span>
                </div>
                <div>
                    <span class="timing-lbl">CHECK-OUT</span>
                    <div class="timing-val"><?php echo $checkout_fmt; ?></div>
                </div>
                <div>
                    <?php if ($is_refundable): ?>
                    <span style="font-size: 12px; font-weight: 700; color: #16a34a; background: #f0fdf4; padding: 4px 8px; border-radius: 4px;">Refundable</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Room & Cancellation -->
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="font-size: 14.5px; color: #0f172a;"><?php echo htmlspecialchars($room_type); ?></strong>
                    <span style="font-size: 12.5px; font-weight: 800; color: #0f172a;"><?php echo $adults; ?> ADULT<?php echo $adults > 1 ? 'S' : ''; ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12px; color: #ef4444; font-weight: 600;">
                        Free cancellation till <?php echo date('d M Y', strtotime($checkin_date . ' -2 days')); ?>
                    </span>
                    <span style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">
                        <i class="fa-regular fa-circle-check"></i> Free cancellation
                    </span>
                </div>
            </div>

            <!-- Accordion: Guest Details -->
            <div class="drawer-accordion-sec">
                <div class="drawer-acc-title" onclick="toggleDrawerAcc('drawerGuestDetails')">
                    <i class="fa-solid fa-angle-down"></i>
                    <span>Guest Details</span>
                </div>
                <div id="drawerGuestDetails" class="drawer-acc-body">
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Room 1</div>
                    <div style="color: #475569; padding-left: 12px;">
                        <?php echo htmlspecialchars($primary_name); ?>
                    </div>
                </div>
            </div>

            <!-- Accordion: Contact Information -->
            <div class="drawer-accordion-sec">
                <div class="drawer-acc-title" onclick="toggleDrawerAcc('drawerContactDetails')">
                    <i class="fa-solid fa-angle-down"></i>
                    <span>Contact Information</span>
                </div>
                <div id="drawerContactDetails" class="drawer-acc-body">
                    <div style="display: flex; gap: 20px; color: #475569;">
                        <div><i class="fa-solid fa-envelope" style="color: #94a3b8; margin-right: 6px;"></i> <?php echo htmlspecialchars($guest_email); ?></div>
                        <div><i class="fa-solid fa-phone" style="color: #94a3b8; margin-right: 6px;"></i> <?php echo htmlspecialchars($guest_phone); ?></div>
                    </div>
                </div>
            </div>

            <!-- Accordion: Fare Summary Table (Screenshot 3 Matching) -->
            <div class="drawer-accordion-sec" style="border-bottom: none;">
                <div class="drawer-acc-title" onclick="toggleDrawerAcc('drawerFareTable')">
                    <i class="fa-solid fa-angle-down"></i>
                    <span>Fare Summary</span>
                </div>
                <div id="drawerFareTable" class="drawer-acc-body">
                    <table class="drawer-fare-table">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Base Fare</th>
                                <th>Taxes and Charges</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($room_type); ?></td>
                                <td>₹ <?php echo number_format($base_total); ?></td>
                                <td>₹ <?php echo number_format($taxes); ?></td>
                                <td>₹ <?php echo number_format($base_total + $taxes); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="drawerDiscountLine" style="display: <?php echo ($discount_total > 0) ? 'flex' : 'none'; ?>; justify-content: flex-end; padding: 10px 0; font-size: 13px; color: #16a34a; font-weight: 700;">
                        <span>Promo code discount: - ₹ <span id="drawerDiscountVal"><?php echo number_format($discount_total); ?></span></span>
                    </div>

                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 14px; padding-top: 12px; border-top: 2px solid #0f172a;">
                        <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Total Net Fare :</span>
                        <strong style="font-size: 22px; font-weight: 900; color: #0f172a;">₹ <span id="drawerTotalNetVal"><?php echo number_format($total_amount); ?></span></strong>
                    </div>
                </div>
            </div>

            <!-- Drawer Bottom Close / Continue Button -->
            <div style="margin-top: 24px; text-align: right;">
                <button type="button" onclick="closeItineraryDrawer()" style="background: #09204b; color: #ffffff; border: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 800; cursor: pointer;">
                    Back to Payment
                </button>
            </div>

        </div>

    </div>
</div>

<!-- Fullscreen Processing Overlay -->
<div id="hotelPaymentProcessingOverlay" style="display: none; position: fixed; inset: 0; background: rgba(9, 32, 75, 0.95); z-index: 9999999; backdrop-filter: blur(5px); align-items: center; justify-content: center; flex-direction: column; color: #ffffff; text-align: center; padding: 20px;">
    <div style="width: 56px; height: 56px; border: 4px solid rgba(255,255,255,0.2); border-top-color: #ef4444; border-radius: 50%; animation: spinProcessing 0.9s linear infinite; margin-bottom: 20px;"></div>
    <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-bottom: 8px;">Processing Hotel Reservation...</h3>
    <p id="hotelProcessingModalMsg" style="font-size: 14px; color: #cbd5e1; max-width: 480px;">Payment Verified! Generating your Official Hotel Voucher & Confirmation...</p>
</div>

<style>
@keyframes spinProcessing {
    to { transform: rotate(360deg); }
}
</style>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var baseTotalAmount = <?php echo (float)$base_total; ?>;
var taxesAmount = <?php echo (float)$taxes; ?>;
var originalGrandTotal = <?php echo (float)$total_amount; ?>;
var currentDiscount = <?php echo (float)$discount_total; ?>;
var currentPayableTotal = <?php echo (float)$total_amount; ?>;

// Switch Payment Method Tabs on Left
function switchPayTab(btn, tabId) {
    document.querySelectorAll('.payment-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    
    btn.classList.add('active');
    const targetPane = document.getElementById(tabId);
    if (targetPane) targetPane.classList.add('active');
}

// Toggle Fare Summary Details
function toggleFareDetails(contentId, iconId) {
    var content = document.getElementById(contentId);
    var icon = document.getElementById(iconId);
    if (!content) return;
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        if (icon) icon.style.transform = 'rotate(90deg)';
    } else {
        content.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}

// Open and Close Slide-over Itinerary Drawer (Screenshot 3)
function openItineraryDrawer() {
    var overlay = document.getElementById('itineraryDrawerOverlay');
    if (overlay) overlay.style.display = 'flex';
}
function closeItineraryDrawer() {
    var overlay = document.getElementById('itineraryDrawerOverlay');
    if (overlay) overlay.style.display = 'none';
}
function onDrawerOverlayClick(e) {
    if (e.target.id === 'itineraryDrawerOverlay') {
        closeItineraryDrawer();
    }
}
function toggleDrawerAcc(accId) {
    var acc = document.getElementById(accId);
    if (!acc) return;
    acc.style.display = (acc.style.display === 'none') ? 'block' : 'none';
}

// Session Countdown Timer (Screenshot 2 Matching)
(function startCountdown() {
    var totalSeconds = 9 * 60 + 58;
    var timerElem = document.getElementById('countdownTimer');
    var timerInterval = setInterval(function() {
        totalSeconds--;
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            if (timerElem) timerElem.innerText = '00 min: 00 sec';
            alert('Your payment session has expired. Please refresh the page to update pricing.');
            return;
        }
        var mins = Math.floor(totalSeconds / 60);
        var secs = totalSeconds % 60;
        var formatted = (mins < 10 ? '0' : '') + mins + ' min: ' + (secs < 10 ? '0' : '') + secs + ' sec';
        if (timerElem) timerElem.innerText = formatted;
    }, 1000);
})();

// Promo Code Selection & Application
function selectOfferRadio(code, percentage, desc) {
    var discount = Math.round(baseTotalAmount * percentage);
    if (discount < 300) discount = 300;
    applyDiscountValue(code, discount);
    
    var customInput = document.getElementById('customPromoInput');
    if (customInput) customInput.value = code;
}

function applyCustomPromo() {
    var input = document.getElementById('customPromoInput');
    var code = input ? input.value.trim().toUpperCase() : '';
    if (!code) {
        alert('Please enter a promo code');
        return;
    }
    var discount = Math.round(baseTotalAmount * 0.10); // default 10%
    if (discount < 300) discount = 300;
    applyDiscountValue(code, discount);
}

function applyDiscountValue(code, discount) {
    currentDiscount = discount;
    currentPayableTotal = Math.max(1, originalGrandTotal - currentDiscount);

    // Update Right Sidebar Fare Summary
    var discRow = document.getElementById('fareDiscountRow');
    var discVal = document.getElementById('dispDiscountVal');
    var netVal = document.getElementById('dispTotalNetVal');
    if (discRow) discRow.style.display = 'block';
    if (discVal) discVal.innerText = currentDiscount.toLocaleString();
    if (netVal) netVal.innerText = currentPayableTotal.toLocaleString();

    // Update Promo notice
    var noticeBox = document.getElementById('promoNoticeMsg');
    var noticeTxt = document.getElementById('promoNoticeTxt');
    if (noticeBox && noticeTxt) {
        noticeTxt.innerHTML = 'Promo <strong>' + code + '</strong> applied! You saved ₹ ' + currentDiscount.toLocaleString();
        noticeBox.style.display = 'block';
    }

    // Update Pay buttons
    document.querySelectorAll('.dispPayBtnAmount').forEach(function(el) {
        el.innerText = currentPayableTotal.toLocaleString();
    });

    // Update Drawer
    var drawerDiscLine = document.getElementById('drawerDiscountLine');
    var drawerDiscVal = document.getElementById('drawerDiscountVal');
    var drawerNetVal = document.getElementById('drawerTotalNetVal');
    if (drawerDiscLine) drawerDiscLine.style.display = 'flex';
    if (drawerDiscVal) drawerDiscVal.innerText = currentDiscount.toLocaleString();
    if (drawerNetVal) drawerNetVal.innerText = currentPayableTotal.toLocaleString();

    // Update Hidden Form Fields
    var formDisc = document.getElementById('formDiscountTotal');
    var formGrand = document.getElementById('formGrandTotal');
    var formTotal = document.getElementById('formTotalAmount');
    if (formDisc) formDisc.value = currentDiscount;
    if (formGrand) formGrand.value = currentPayableTotal;
    if (formTotal) formTotal.value = currentPayableTotal;
}

// Razorpay Standard Checkout Trigger & Verification
function triggerRazorpayHotelPayment() {
    var amountInPaise = Math.round(currentPayableTotal * 100);
    var guestName = "<?php echo addslashes($primary_name); ?>";
    var guestEmail = "<?php echo addslashes($guest_email); ?>";
    var guestPhone = "<?php echo addslashes($guest_phone); ?>";

    showHotelPaymentProcessing("Initializing Secure Razorpay Checkout...");

    // STEP 1: Backend creates Razorpay order
    fetch("<?php echo site_url('api/create-order'); ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            amount: amountInPaise,
            currency: "INR",
            receipt: "rcpt_htl_" + Date.now(),
            service: "Hotel Booking"
        })
    })
    .then(function(res) {
        if (!res.ok) {
            return res.json().then(function(errData) {
                throw new Error(errData.message || "Failed to initialize Razorpay order (HTTP " + res.status + ")");
            });
        }
        return res.json();
    })
    .then(function(orderData) {
        hideHotelPaymentProcessing();
        if (orderData.status !== 'success' || !orderData.order_id) {
            throw new Error(orderData.message || "Order creation failed.");
        }

        // STEP 2: Open Razorpay modal with server-generated order_id
        var options = {
            "key": orderData.key_id,
            "amount": orderData.amount,
            "currency": orderData.currency || "INR",
            "name": orderData.merchant_name || "Voyogo Hotel Booking",
            "description": "Hotel Voucher - <?php echo addslashes($hotel_name); ?>",
            "image": "<?php echo base_url('assets/images/logo.png'); ?>",
            "order_id": orderData.order_id,
            "handler": function (response) {
                showHotelPaymentProcessing("Payment received! Verifying digital signature...");

                // STEP 3: Verify Payment Signature via Backend
                fetch("<?php echo site_url('api/verify-payment'); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(function(vRes) {
                    return vRes.json();
                })
                .then(function(vData) {
                    if (vData.status === 'success' && vData.verified) {
                        showHotelPaymentProcessing("Payment Verified (HTTP 200 OK)! Generating your Official Hotel Voucher...");
                        document.getElementById('formRazorpayPaymentId').value = response.razorpay_payment_id;
                        document.getElementById('formRazorpayOrderId').value = response.razorpay_order_id;
                        document.getElementById('formRazorpaySignature').value = response.razorpay_signature;
                        document.getElementById('hotelFinalBookingForm').submit();
                    } else {
                        hideHotelPaymentProcessing();
                        alert("Payment Verification Error: " + (vData.message || "Signature mismatch. Transaction cannot be validated."));
                    }
                })
                .catch(function(vErr) {
                    hideHotelPaymentProcessing();
                    alert("Error verifying payment signature: " + vErr.message);
                });
            },
            "prefill": {
                "name": guestName,
                "email": guestEmail,
                "contact": guestPhone
            },
            "theme": {
                "color": "<?php echo !empty($razorpay_settings['theme_color']) ? htmlspecialchars($razorpay_settings['theme_color']) : '#ef4444'; ?>"
            },
            "modal": {
                "ondismiss": function() {
                    console.log("Razorpay checkout modal closed by customer.");
                    hideHotelPaymentProcessing();
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function (resp) {
            var errMsg = resp.error ? (resp.error.description || resp.error.reason) : "Payment failed.";
            hideHotelPaymentProcessing();
            alert("Payment Failed: " + errMsg);
        });
        rzp.open();
    })
    .catch(function(err) {
        hideHotelPaymentProcessing();
        alert("Payment Gateway Error: " + err.message);
    });
}

function showHotelPaymentProcessing(message) {
    var overlay = document.getElementById('hotelPaymentProcessingOverlay');
    if (overlay) {
        var msgEl = document.getElementById('hotelProcessingModalMsg');
        if (msgEl) msgEl.innerText = message || "Payment Verified! Generating Hotel Voucher...";
        overlay.style.display = 'flex';
    }
}

function hideHotelPaymentProcessing() {
    var overlay = document.getElementById('hotelPaymentProcessingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}
</script>
