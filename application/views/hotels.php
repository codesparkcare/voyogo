<?php
$defaultCheckin  = date('Y-m-d', strtotime('+3 days'));
$defaultCheckout = date('Y-m-d', strtotime('+7 days'));
$defaultNights   = 4;
?>
<!-- Custom Styles for Akbar-Style Hotel Engine -->
<style>
.akbar-hero-section {
    position: relative;
    background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=85') no-repeat center center / cover;
    padding: 70px 0 100px 0;
    min-height: 480px;
}
.akbar-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(9, 32, 75, 0.65) 0%, rgba(13, 52, 112, 0.75) 50%, rgba(9, 32, 75, 0.9) 100%);
}
.akbar-hero-container {
    position: relative;
    z-index: 10;
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 15px;
}
.akbar-hero-title {
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 20px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
}

/* Horizontal Search Box Card */
.akbar-search-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
}
.akbar-search-form {
    display: flex;
    align-items: stretch;
    width: 100%;
}
.akbar-search-col {
    padding: 14px 18px;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: background 0.15s ease;
}
.akbar-search-col:hover {
    background: #f8fafc;
}
.akbar-col-label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.akbar-col-label span {
    color: #0d3470;
}
.akbar-col-value {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.akbar-date-display {
    display: flex;
    align-items: baseline;
    gap: 4px;
}
.akbar-date-num {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.akbar-date-month {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}
.akbar-date-day {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
    font-weight: 500;
}

/* Floating Nights Pill */
.akbar-nights-divider {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 0;
    z-index: 12;
}
.akbar-nights-pill {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 20px;
    padding: 3px 9px;
    font-size: 11px;
    font-weight: 800;
    color: #334155;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
    text-align: center;
}

/* Guest Selector Styling */
.akbar-guest-display {
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.akbar-guest-num {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.akbar-guest-lbl {
    font-size: 14px;
    font-weight: 600;
    color: #475569;
}

/* Red Search Button */
.akbar-search-btn-col {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.akbar-search-btn {
    background: #eb2027;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 16px 30px;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(235, 32, 39, 0.4);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.akbar-search-btn:hover {
    background: #d61a20;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(235, 32, 39, 0.5);
}

/* Quick Services Strip */
.akbar-services-strip {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    border: 1px solid #e2e8f0;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 24px;
    overflow-x: auto;
    gap: 16px;
}
.akbar-service-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    text-decoration: none;
    white-space: nowrap;
    padding: 6px 10px;
    border-radius: 6px;
    transition: all 0.15s ease;
}
.akbar-service-item:hover {
    background: #f1f5f9;
    color: #0d3470;
}
.akbar-service-item i {
    color: #0d3470;
    font-size: 14px;
}

/* Dropdown popups */
.akbar-dropdown-panel {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    border: 1px solid #cbd5e1;
    z-index: 99999;
    display: none;
    cursor: default;
}
.akbar-dropdown-panel.show {
    display: block;
}

/* Akbar Dual-Month Calendar Dropdown */
.akbar-calendar-panel {
    width: 620px;
    max-width: 95vw;
    padding: 0;
    border-radius: 12px;
    overflow: hidden;
    left: 20%;
    top: calc(100% + 10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    border: 1px solid #cbd5e1;
    background: #ffffff;
    z-index: 999999;
}
@media (max-width: 991px) {
    .akbar-calendar-panel {
        left: 0;
        width: 100%;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 90vh;
        overflow-y: auto;
    }
}
.akbar-cal-header-tabs {
    display: flex;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.akbar-cal-tab {
    flex: 1;
    padding: 12px 20px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    background: #f8fafc;
}
.akbar-cal-tab.active {
    background: #ffffff;
    border-bottom-color: #0d3470;
}
.akbar-cal-tab-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}
.akbar-cal-tab.active .akbar-cal-tab-label {
    color: #0d3470;
}
.akbar-cal-tab-val {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 3px;
    display: block;
}
.akbar-cal-body {
    display: flex;
    gap: 20px;
    padding: 18px 20px 22px;
}
@media (max-width: 640px) {
    .akbar-cal-body {
        flex-direction: column;
    }
}
.akbar-cal-month-wrap {
    flex: 1;
}
.akbar-cal-month-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    height: 32px;
}
.akbar-cal-month-title {
    font-size: 13px;
    font-weight: 800;
    color: #0d3470;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex: 1;
    text-align: center;
}
.akbar-cal-nav-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 700;
    color: #0f172a;
    transition: all 0.15s;
    font-size: 14px;
}
.akbar-cal-nav-btn:hover:not(:disabled) {
    background: #0d3470;
    color: #ffffff;
    border-color: #0d3470;
}
.akbar-cal-nav-btn:disabled {
    opacity: 0.25;
    cursor: not-allowed;
}
.akbar-cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 8px;
}
.akbar-cal-weekdays span.sun {
    color: #ef4444;
}
.akbar-cal-days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    row-gap: 4px;
    text-align: center;
}
.akbar-cal-day {
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
    user-select: none;
    transition: all 0.1s ease;
    border-radius: 4px;
}
.akbar-cal-day:hover:not(.disabled):not(.selected-in):not(.selected-out) {
    background: #e2e8f0;
    color: #0d3470;
}
.akbar-cal-day.sun:not(.disabled):not(.selected-in):not(.selected-out) {
    color: #ef4444;
}
.akbar-cal-day.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    background: transparent;
}
.akbar-cal-day.selected-in {
    background: #0d3470 !important;
    color: #ffffff !important;
    font-weight: 800;
    border-radius: 4px;
}
.akbar-cal-day.selected-out {
    background: #0d3470 !important;
    color: #ffffff !important;
    font-weight: 800;
    border-radius: 4px;
}
.akbar-cal-day.in-range {
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 0;
    font-weight: 700;
}

/* Exclusive Deals Section */
.exclusive-deals-section {
    padding: 40px 0;
    background: #ffffff;
}
.deals-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.deals-title-wrap {
    display: flex;
    align-items: center;
    gap: 28px;
}
.deals-heading {
    font-family: 'Outfit', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.deals-tabs {
    display: flex;
    gap: 20px;
}
.deals-tab-btn {
    background: none;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    padding: 6px 0;
    cursor: pointer;
    position: relative;
    text-transform: uppercase;
}
.deals-tab-btn.active {
    color: #eb2027;
    border-bottom: 2px solid #eb2027;
}
.deals-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.deal-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.deal-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}
.deal-card-img {
    height: 180px;
    position: relative;
    overflow: hidden;
}
.deal-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.deal-tag-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #ffffff;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}
.deal-promo-badge {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    backdrop-filter: blur(4px);
}
.deal-card-content {
    padding: 18px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.deal-card-content h3 {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 6px 0;
}
.deal-card-content p {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 16px 0;
    line-height: 1.4;
}
.deal-card-content a {
    color: #eb2027;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    align-self: flex-end;
}

/* Bank Promo Banner */
.bank-promo-banner {
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

@media (max-width: 991px) {
    .akbar-search-form {
        flex-direction: column;
    }
    .akbar-search-col {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }
    .akbar-nights-divider {
        display: none;
    }
    .deals-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Hero Section with Akbar Travels Style Search Engine -->
<section class="akbar-hero-section">
    <div class="akbar-hero-overlay"></div>
    <div class="akbar-hero-container">
        
        <!-- Headline -->
        <h1 class="akbar-hero-title">Book Domestic and International Hotels</h1>

        <!-- Unified White Search Card -->
        <div class="akbar-search-card">
            
            <form action="<?php echo site_url('hotels/search'); ?>" method="POST" id="akbarHotelForm" class="akbar-search-form">
                
                <!-- 1. Destination / Property -->
                <div class="akbar-search-col" id="akbarDestCol" style="flex: 2.2; position: relative;">
                    <div class="akbar-col-label">
                        <span>ENTER YOUR DESTINATION OR PROPERTY</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-right: 4px;">
                        <div>
                            <div id="akbarDestCity" style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.1;">Tirunelveli</div>
                            <div id="akbarDestSub" style="font-size: 12px; color: #64748b; margin-top: 3px;">Tirunelveli</div>
                        </div>
                        <i class="fa-solid fa-crosshairs" style="color: #94a3b8; font-size: 16px; margin-right: 6px;"></i>
                    </div>
                    <input type="hidden" name="city" id="akbarCityInput" value="Tirunelveli">

                    <!-- Destination Autocomplete Dropdown -->
                    <div class="akbar-dropdown-panel" id="akbarDestDropdown" style="width: 360px; padding: 14px;" onclick="event.stopPropagation();">
                        <div style="position: relative; margin-bottom: 12px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 12px; color: #64748b; font-size: 13px;"></i>
                            <input type="text" id="akbarDestSearchInput" placeholder="Type your destination (e.g. Tirunelveli, Goa, Mumbai)..." style="width: 100%; padding: 10px 12px 10px 34px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: 600; outline: none;">
                        </div>
                        
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Popular Hotel Destinations</div>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px;" id="akbarPopularPills">
                            <span class="ak-pill" onclick="selectAkbarCity('Tirunelveli', 'Tamil Nadu, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Tirunelveli</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Goa', 'Goa, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Goa</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Mumbai', 'Maharashtra, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Mumbai</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Delhi NCR', 'Delhi, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Delhi NCR</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Dubai', 'United Arab Emirates')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Dubai</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Madurai', 'Tamil Nadu, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Madurai</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Jaipur', 'Rajasthan, India')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Jaipur</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Maldives', 'South Asia')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Maldives</span>
                        </div>

                        <div id="akbarDestList" style="max-height: 200px; overflow-y: auto; border: 1px solid #f1f5f9; border-radius: 6px;">
                            <!-- Populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- 2. Check In Date -->
                <div class="akbar-search-col" id="akbarCheckinCol" style="flex: 1.2; cursor: pointer;">
                    <div class="akbar-col-label">
                        <span>CHECK IN</span> <i class="fa-solid fa-chevron-down" style="font-size: 9px; color: #64748b;"></i>
                    </div>
                    <div class="akbar-date-display">
                        <span class="akbar-date-num" id="akbarCheckinNum"><?php echo date('d', strtotime($defaultCheckin)); ?></span>
                        <span class="akbar-date-month" id="akbarCheckinMon"><?php echo date("M'y", strtotime($defaultCheckin)); ?></span>
                    </div>
                    <div class="akbar-date-day" id="akbarCheckinDay"><?php echo date('l', strtotime($defaultCheckin)); ?></div>
                    <input type="hidden" id="akbarCheckinInput" name="checkin_date" value="<?php echo $defaultCheckin; ?>">
                </div>

                <!-- Floating Nights Divider Badge -->
                <div class="akbar-nights-divider">
                    <div class="akbar-nights-pill">
                        <span id="akbarNightsVal"><?php echo $defaultNights; ?></span> NIGHTS
                    </div>
                </div>

                <!-- 3. Check Out Date -->
                <div class="akbar-search-col" id="akbarCheckoutCol" style="flex: 1.2; padding-left: 24px; cursor: pointer;">
                    <div class="akbar-col-label">
                        <span>CHECK OUT</span> <i class="fa-solid fa-chevron-down" style="font-size: 9px; color: #64748b;"></i>
                    </div>
                    <div class="akbar-date-display">
                        <span class="akbar-date-num" id="akbarCheckoutNum"><?php echo date('d', strtotime($defaultCheckout)); ?></span>
                        <span class="akbar-date-month" id="akbarCheckoutMon"><?php echo date("M'y", strtotime($defaultCheckout)); ?></span>
                    </div>
                    <div class="akbar-date-day" id="akbarCheckoutDay"><?php echo date('l', strtotime($defaultCheckout)); ?></div>
                    <input type="hidden" id="akbarCheckoutInput" name="checkout_date" value="<?php echo $defaultCheckout; ?>">
                </div>

                <!-- Akbar Interactive Dual-Month Calendar Dropdown Panel -->
                <div class="akbar-dropdown-panel akbar-calendar-panel" id="akbarCalendarDropdown" onclick="event.stopPropagation();">
                    <!-- Top Switcher Tabs -->
                    <div class="akbar-cal-header-tabs">
                        <div class="akbar-cal-tab active" id="akbarTabCheckin" onclick="switchAkbarCalTab('checkin')">
                            <span class="akbar-cal-tab-label">CHECK-IN</span>
                            <span class="akbar-cal-tab-val" id="akbarCalTabCheckinVal"><?php echo date('M d, Y', strtotime($defaultCheckin)); ?></span>
                        </div>
                        <div class="akbar-cal-tab" id="akbarTabCheckout" onclick="switchAkbarCalTab('checkout')">
                            <span class="akbar-cal-tab-label">CHECK-OUT</span>
                            <span class="akbar-cal-tab-val" id="akbarCalTabCheckoutVal"><?php echo date('M d, Y', strtotime($defaultCheckout)); ?></span>
                        </div>
                    </div>

                    <!-- Dual Month Calendars Body -->
                    <div class="akbar-cal-body">
                        <!-- Left Month -->
                        <div class="akbar-cal-month-wrap">
                            <div class="akbar-cal-month-head">
                                <button type="button" class="akbar-cal-nav-btn" id="akbarCalPrevBtn" onclick="navigateAkbarCal(-1)">&larr;</button>
                                <span class="akbar-cal-month-title" id="akbarCalMonth1Title">SEPTEMBER 2026</span>
                                <span style="width: 30px;"></span>
                            </div>
                            <div class="akbar-cal-weekdays">
                                <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                            </div>
                            <div class="akbar-cal-days-grid" id="akbarCalDays1">
                                <!-- Days injected via JS -->
                            </div>
                        </div>

                        <!-- Right Month -->
                        <div class="akbar-cal-month-wrap">
                            <div class="akbar-cal-month-head">
                                <span style="width: 30px;"></span>
                                <span class="akbar-cal-month-title" id="akbarCalMonth2Title">OCTOBER 2026</span>
                                <button type="button" class="akbar-cal-nav-btn" id="akbarCalNextBtn" onclick="navigateAkbarCal(1)">&rarr;</button>
                            </div>
                            <div class="akbar-cal-weekdays">
                                <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                            </div>
                            <div class="akbar-cal-days-grid" id="akbarCalDays2">
                                <!-- Days injected via JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Rooms & Guests -->
                <div class="akbar-search-col" id="akbarGuestsCol" style="flex: 1.6;">
                    <div class="akbar-col-label">
                        <span>ROOMS & GUESTS</span> <i class="fa-solid fa-chevron-down" style="font-size: 9px; color: #64748b;"></i>
                    </div>
                    <div class="akbar-guest-display">
                        <span class="akbar-guest-num" id="akbarRoomsDisplay">2</span>
                        <span class="akbar-guest-lbl">Rooms</span>
                        <span class="akbar-guest-num" id="akbarGuestsDisplay" style="margin-left: 6px;">4</span>
                        <span class="akbar-guest-lbl">Guests</span>
                    </div>
                    <div class="akbar-date-day" id="akbarGuestSubtitle">2 Adults, 0 Children</div>

                    <input type="hidden" name="rooms" id="akbarHiddenRooms" value="2">
                    <input type="hidden" name="adults" id="akbarHiddenAdults" value="4">
                    <input type="hidden" name="children" id="akbarHiddenChildren" value="0">

                    <!-- Guests Configuration Popover -->
                    <div class="akbar-dropdown-panel" id="akbarGuestsDropdown" style="width: 320px; padding: 18px;" onclick="event.stopPropagation();">
                        
                        <div style="font-size: 14px; font-weight: 800; color: #0d3470; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                            Select Rooms & Guests
                        </div>

                        <!-- Rooms Counter -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">Rooms</div>
                                <div style="font-size: 11px; color: #64748b;">Max 10 rooms</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" onclick="modifyAkbarGuests('rooms', -1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">-</button>
                                <span id="akbarRoomsCounterVal" style="font-weight: 700; font-size: 14px; min-width: 18px; text-align: center;">2</span>
                                <button type="button" onclick="modifyAkbarGuests('rooms', 1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">+</button>
                            </div>
                        </div>

                        <!-- Adults Counter -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">Adults</div>
                                <div style="font-size: 11px; color: #64748b;">12+ yrs per room</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" onclick="modifyAkbarGuests('adults', -1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">-</button>
                                <span id="akbarAdultsCounterVal" style="font-weight: 700; font-size: 14px; min-width: 18px; text-align: center;">4</span>
                                <button type="button" onclick="modifyAkbarGuests('adults', 1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">+</button>
                            </div>
                        </div>

                        <!-- Children Counter -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">Children</div>
                                <div style="font-size: 11px; color: #64748b;">0-11 yrs per room</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" onclick="modifyAkbarGuests('children', -1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">-</button>
                                <span id="akbarChildrenCounterVal" style="font-weight: 700; font-size: 14px; min-width: 18px; text-align: center;">0</span>
                                <button type="button" onclick="modifyAkbarGuests('children', 1)" style="width: 28px; height: 28px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; font-weight: 700; cursor: pointer;">+</button>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <button type="button" onclick="closeAkbarDropdowns();" style="background: #eb2027; color: #ffffff; border: none; padding: 8px 22px; border-radius: 6px; font-weight: 800; font-size: 13px; cursor: pointer;">APPLY</button>
                        </div>
                    </div>
                </div>

                <!-- 5. Search Button -->
                <div class="akbar-search-btn-col">
                    <button type="submit" class="akbar-search-btn">
                        <span>SEARCH</span>
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

            </form>

        </div>

        <!-- You've Searched Recent Pill Strip (Matching Akbar Travels) -->
        <div id="akbarRecentSearchesStrip" style="display: flex; align-items: center; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
            <span style="font-size: 12px; font-weight: 700; color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">You've Searched</span>
            <div onclick="selectAkbarCity('Dubai', 'United Arab Emirates')" style="background: rgba(255, 255, 255, 0.95); border-radius: 6px; padding: 6px 14px; font-size: 11px; color: #0f172a; cursor: pointer; display: flex; flex-direction: column; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                <strong>Dubai, United Arab Emirates</strong>
                <span style="color: #64748b; font-size: 10px;">08 Sep 26 - 12 Sep 26 | 4 Guests, 2 Rooms</span>
            </div>
            <div onclick="selectAkbarCity('Madurai', 'Tamil Nadu, India')" style="background: rgba(255, 255, 255, 0.95); border-radius: 6px; padding: 6px 14px; font-size: 11px; color: #0f172a; cursor: pointer; display: flex; flex-direction: column; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                <strong>Madurai, Tamil Nadu, India</strong>
                <span style="color: #64748b; font-size: 10px;">08 Sep 26 - 12 Sep 26 | 4 Guests, 2 Rooms</span>
            </div>
            <span style="font-size: 11px; font-weight: 700; color: #cbd5e1; cursor: pointer; text-decoration: underline;" onclick="document.getElementById('akbarRecentSearchesStrip').style.display='none';">Clear All</span>
        </div>

        <!-- Quick Secondary Services Strip (Academy, Umrah, Passport, Cargo, etc.) -->
        <div class="akbar-services-strip">
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-graduation-cap"></i> Academy</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-plane-departure"></i> Study Abroad</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-moon"></i> Umrah</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-passport"></i> Passport</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-jet-fighter"></i> Charters</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-box"></i> Cargo</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-train"></i> IRCTC Agent</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-building-user"></i> MICE</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-briefcase"></i> Corporate</a>
            <a href="#" class="akbar-service-item"><i class="fa-solid fa-money-bill-transfer"></i> Forex</a>
        </div>

    </div>
</section>

<!-- Exclusive Deals Section (Matching Akbar Travels UI) -->
<section class="exclusive-deals-section">
    <div class="container">
        
        <div class="deals-header-row">
            <div class="deals-title-wrap">
                <h2 class="deals-heading">Exclusive Deals</h2>
                <div class="deals-tabs">
                    <button class="deals-tab-btn" onclick="filterDealsTab(this, 'all')">HOT DEAL</button>
                    <button class="deals-tab-btn" onclick="filterDealsTab(this, 'flight')">FLIGHT</button>
                    <button class="deals-tab-btn active" onclick="filterDealsTab(this, 'hotel')">HOTEL</button>
                    <button class="deals-tab-btn" onclick="filterDealsTab(this, 'holidays')">HOLIDAYS</button>
                    <button class="deals-tab-btn" onclick="filterDealsTab(this, 'cruise')">CRUISE</button>
                </div>
            </div>
            <div>
                <a href="<?php echo site_url('hotels'); ?>" style="color: #eb2027; font-size: 13px; font-weight: 700; text-decoration: none; margin-right: 12px;">View All Deals</a>
                <span style="background: #f1f5f9; padding: 6px 12px; border-radius: 50%; font-size: 13px; cursor: pointer; color: #475569;"><i class="fa-solid fa-chevron-left"></i></span>
                <span style="background: #0d3470; padding: 6px 12px; border-radius: 50%; font-size: 13px; cursor: pointer; color: #ffffff;"><i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </div>

        <!-- Deals Grid Cards -->
        <div class="deals-grid">
            
            <!-- Card 1: Spree Hotels -->
            <div class="deal-card">
                <div class="deal-card-img">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80" alt="Spree Hotels">
                    <span class="deal-tag-badge">HOTEL</span>
                    <span class="deal-promo-badge">Up to 75% OFF</span>
                </div>
                <div class="deal-card-content">
                    <div>
                        <h3>Smart Stays at Spree Hotels</h3>
                        <p>Grab up to 75% off on stylish and comfortable stays across premier leisure locations.</p>
                    </div>
                    <a href="<?php echo site_url('hotels'); ?>">Book Now &rarr;</a>
                </div>
            </div>

            <!-- Card 2: Club Mahindra -->
            <div class="deal-card">
                <div class="deal-card-img">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80" alt="Club Mahindra">
                    <span class="deal-tag-badge">HOTEL</span>
                    <span class="deal-promo-badge">Up to 75% OFF</span>
                </div>
                <div class="deal-card-content">
                    <div>
                        <h3>Holiday Escapes at Club Mahindra</h3>
                        <p>Plan your next family vacation with up to 75% off on curated hill, beach & wildlife resorts.</p>
                    </div>
                    <a href="<?php echo site_url('hotels'); ?>">Book Now &rarr;</a>
                </div>
            </div>

            <!-- Card 3: Fern Hotels -->
            <div class="deal-card">
                <div class="deal-card-img">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80" alt="Fern Hotels">
                    <span class="deal-tag-badge">HOTEL</span>
                    <span class="deal-promo-badge">Up to 75% OFF</span>
                </div>
                <div class="deal-card-content">
                    <div>
                        <h3>Eco-Friendly Stays at Fern Hotels</h3>
                        <p>Save up to 75% on sustainable and serene boutique hotel stays with premium amenities.</p>
                    </div>
                    <a href="<?php echo site_url('hotels'); ?>">Book Now &rarr;</a>
                </div>
            </div>

        </div>

        <!-- Bank Discount Banner -->
        <div class="bank-promo-banner">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 50px; height: 50px; background: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-credit-card" style="font-size: 24px; color: #eb2027;"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #0f172a;">Flat 25% off on Domestic & International Hotels</h4>
                    <p style="margin: 0; font-size: 13px; color: #64748b;">with HDFC Bank Credit Card EMI Offers & instant partner discounts.</p>
                </div>
            </div>
            <div>
                <a href="<?php echo site_url('hotels'); ?>" style="background: #0d3470; color: #fff; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none;">CLAIM DISCOUNT</a>
            </div>
        </div>

    </div>
</section>

<!-- Featured Destinations Section -->
<section class="section-padding" style="background: #f8fafc;">
    <div class="container">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Top Hotel Destinations</h2>
                <p>Most booked holiday locations with handpicked hotel deals</p>
            </div>
        </div>

        <div class="cards-grid">
            
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80" alt="Goa Beach Resort">
                    <span class="card-tag">1,240+ PROPERTIES</span>
                </div>
                <div class="card-body">
                    <h3>Goa Beach Stays</h3>
                    <div class="card-sub"><i class="fa-solid fa-umbrella-beach"></i> Beachfront, Pool Resorts & Shacks</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 1,899 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80" alt="Dubai Luxury Hotel">
                    <span class="card-tag">850+ HOTELS</span>
                </div>
                <div class="card-body">
                    <h3>Dubai Luxury Hotels</h3>
                    <div class="card-sub"><i class="fa-solid fa-building"></i> Downtown, Marina & Palm Jumeirah</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 5,499 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1599661046827-dacff0c0f09a?auto=format&fit=crop&w=600&q=80" alt="Jaipur Palace Hotel">
                    <span class="card-tag">450+ HOTELS</span>
                </div>
                <div class="card-body">
                    <h3>Jaipur Heritage Haveli Stays</h3>
                    <div class="card-sub"><i class="fa-solid fa-crown"></i> Royal Palaces & Boutique Haveli</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 2,499 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80" alt="Maldives Overwater Resort">
                    <span class="card-tag">120+ RESORTS</span>
                </div>
                <div class="card-body">
                    <h3>Maldives Island Resorts</h3>
                    <div class="card-sub"><i class="fa-solid fa-water"></i> Overwater Villas & All-Inclusive Stays</div>
                    <div class="price-row">
                        <span class="price-label">Resorts Starting From</span>
                        <span class="price-amount">₹ 18,999 <small>/night</small></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Interactive Akbar-Style Search Controller JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Elements
    var destCol        = document.getElementById('akbarDestCol');
    var destDropdown   = document.getElementById('akbarDestDropdown');
    var destSearchInp  = document.getElementById('akbarDestSearchInput');
    var destDisplay    = document.getElementById('akbarDestDisplay');
    var destHiddenInp  = document.getElementById('akbarCityInput');
    var destList       = document.getElementById('akbarDestList');

    var checkinInput   = document.getElementById('akbarCheckinInput');
    var checkoutInput  = document.getElementById('akbarCheckoutInput');
    var checkinNum     = document.getElementById('akbarCheckinNum');
    var checkinMon     = document.getElementById('akbarCheckinMon');
    var checkinDay     = document.getElementById('akbarCheckinDay');
    var checkoutNum    = document.getElementById('akbarCheckoutNum');
    var checkoutMon    = document.getElementById('akbarCheckoutMon');
    var checkoutDay    = document.getElementById('akbarCheckoutDay');
    var nightsVal      = document.getElementById('akbarNightsVal');

    var checkinCol     = document.getElementById('akbarCheckinCol');
    var checkoutCol    = document.getElementById('akbarCheckoutCol');
    var calDropdown    = document.getElementById('akbarCalendarDropdown');

    var tabCheckin     = document.getElementById('akbarTabCheckin');
    var tabCheckout    = document.getElementById('akbarTabCheckout');
    var tabCheckinVal  = document.getElementById('akbarCalTabCheckinVal');
    var tabCheckoutVal = document.getElementById('akbarCalTabCheckoutVal');

    var calMonth1Title = document.getElementById('akbarCalMonth1Title');
    var calMonth2Title = document.getElementById('akbarCalMonth2Title');
    var calDays1       = document.getElementById('akbarCalDays1');
    var calDays2       = document.getElementById('akbarCalDays2');
    var calPrevBtn     = document.getElementById('akbarCalPrevBtn');
    var calNextBtn     = document.getElementById('akbarCalNextBtn');

    var guestsCol      = document.getElementById('akbarGuestsCol');
    var guestsDropdown = document.getElementById('akbarGuestsDropdown');

    // 1. Destination Dropdown Logic
    var cities = [
        { city: "Tirunelveli", sub: "Tamil Nadu, India" },
        { city: "Goa", sub: "Goa, India" },
        { city: "Mumbai", sub: "Maharashtra, India" },
        { city: "Delhi NCR", sub: "Delhi, India" },
        { city: "Dubai", sub: "United Arab Emirates" },
        { city: "Madurai", sub: "Tamil Nadu, India" },
        { city: "Jaipur", sub: "Rajasthan, India" },
        { city: "Chennai", sub: "Tamil Nadu, India" },
        { city: "Bengaluru", sub: "Karnataka, India" },
        { city: "Maldives", sub: "South Asia" },
        { city: "Hyderabad", sub: "Telangana, India" },
        { city: "Kochi", sub: "Kerala, India" },
        { city: "Bangkok", sub: "Thailand" },
        { city: "Singapore", sub: "Singapore" }
    ];

    function renderCities(filter) {
        var query = (filter || '').toLowerCase().trim();
        var html = '';
        var matches = cities.filter(function(c) {
            return c.city.toLowerCase().indexOf(query) !== -1 || c.sub.toLowerCase().indexOf(query) !== -1;
        });

        if (matches.length === 0) {
            html = '<div style="padding: 12px; font-size: 13px; color: #64748b; text-align: center;">No destinations found matching "' + query + '"</div>';
        } else {
            matches.forEach(function(c) {
                html += '<div onclick="selectAkbarCity(\'' + c.city + '\', \'' + c.sub + '\')" style="padding: 10px 14px; border-bottom: 1px solid #f8fafc; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: background 0.1s ease;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'#fff\'">';
                html += '<i class="fa-solid fa-hotel" style="color: #eb2027; font-size: 14px;"></i>';
                html += '<div><div style="font-size: 14px; font-weight: 700; color: #0f172a;">' + c.city + '</div><div style="font-size: 11px; color: #64748b;">' + c.sub + '</div></div>';
                html += '</div>';
            });
        }
        if (destList) destList.innerHTML = html;
    }

    renderCities('');

    window.selectAkbarCity = function(cityName, sub) {
        var destCityEl = document.getElementById('akbarDestCity');
        var destSubEl  = document.getElementById('akbarDestSub');
        if (destCityEl) destCityEl.textContent = cityName;
        if (destSubEl) destSubEl.textContent = sub || cityName;
        if (destHiddenInp) destHiddenInp.value = cityName;
        closeAkbarDropdowns();
    };

    if (destCol) {
        destCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            if (destDropdown) destDropdown.classList.add('show');
            setTimeout(function() { if (destSearchInp) destSearchInp.focus(); }, 100);
        });
    }

    if (destSearchInp) {
        destSearchInp.addEventListener('input', function(e) {
            renderCities(e.target.value);
        });
    }

    // =========================================================================
    // 2. AKBAR DUAL-MONTH CALENDAR ENGINE
    // =========================================================================
    var monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];
    var shortMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var selCheckin = new Date(checkinInput.value || Date.now());
    var selCheckout = new Date(checkoutInput.value || (Date.now() + 86400000 * 4));
    selCheckin.setHours(0,0,0,0);
    selCheckout.setHours(0,0,0,0);

    var calViewYear = selCheckin.getFullYear();
    var calViewMonth = selCheckin.getMonth(); // 0-indexed
    var calActiveTab = 'checkin'; // 'checkin' or 'checkout'

    function formatDateIso(d) {
        var y = d.getFullYear();
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    function formatTabDate(d) {
        return shortMonths[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    function updateMainDateDisplays() {
        if (!checkinInput || !checkoutInput) return;

        checkinInput.value = formatDateIso(selCheckin);
        checkoutInput.value = formatDateIso(selCheckout);

        if (checkinNum) checkinNum.textContent = String(selCheckin.getDate()).padStart(2, '0');
        if (checkinMon) checkinMon.textContent = shortMonths[selCheckin.getMonth()] + "'" + String(selCheckin.getFullYear()).slice(-2);
        if (checkinDay) checkinDay.textContent = dayNames[selCheckin.getDay()];

        if (checkoutNum) checkoutNum.textContent = String(selCheckout.getDate()).padStart(2, '0');
        if (checkoutMon) checkoutMon.textContent = shortMonths[selCheckout.getMonth()] + "'" + String(selCheckout.getFullYear()).slice(-2);
        if (checkoutDay) checkoutDay.textContent = dayNames[selCheckout.getDay()];

        var diffTime = selCheckout.getTime() - selCheckin.getTime();
        var diffDays = Math.max(1, Math.round(diffTime / (1000 * 60 * 60 * 24)));
        if (nightsVal) nightsVal.textContent = diffDays;

        if (tabCheckinVal) tabCheckinVal.textContent = formatTabDate(selCheckin);
        if (tabCheckoutVal) tabCheckoutVal.textContent = formatTabDate(selCheckout);
    }

    window.switchAkbarCalTab = function(tab) {
        calActiveTab = tab;
        if (tab === 'checkin') {
            tabCheckin.classList.add('active');
            tabCheckout.classList.remove('active');
            calViewYear = selCheckin.getFullYear();
            calViewMonth = selCheckin.getMonth();
        } else {
            tabCheckout.classList.add('active');
            tabCheckin.classList.remove('active');
            calViewYear = selCheckout.getFullYear();
            calViewMonth = selCheckout.getMonth();
        }
        renderCalendarMonths();
    };

    window.navigateAkbarCal = function(dir) {
        calViewMonth += dir;
        if (calViewMonth < 0) {
            calViewMonth = 11;
            calViewYear--;
        } else if (calViewMonth > 11) {
            calViewMonth = 0;
            calViewYear++;
        }
        renderCalendarMonths();
    };

    function renderMonthGrid(year, month, container) {
        container.innerHTML = '';
        var today = new Date();
        today.setHours(0,0,0,0);

        var firstDayIndex = new Date(year, month, 1).getDay();
        var daysInMonth = new Date(year, month + 1, 0).getDate();

        // 1. Empty padding days
        for (var i = 0; i < firstDayIndex; i++) {
            var emptyCell = document.createElement('div');
            emptyCell.className = 'akbar-cal-day disabled';
            container.appendChild(emptyCell);
        }

        // 2. Real days
        for (var d = 1; d <= daysInMonth; d++) {
            var dateObj = new Date(year, month, d);
            dateObj.setHours(0,0,0,0);

            var cell = document.createElement('div');
            cell.className = 'akbar-cal-day';
            cell.textContent = d;

            var dayOfWeek = dateObj.getDay();
            if (dayOfWeek === 0) cell.classList.add('sun');

            var isPast = dateObj.getTime() < today.getTime();
            if (isPast) {
                cell.classList.add('disabled');
            } else {
                var dTime = dateObj.getTime();
                var inTime = selCheckin.getTime();
                var outTime = selCheckout.getTime();

                if (dTime === inTime) {
                    cell.classList.add('selected-in');
                } else if (dTime === outTime) {
                    cell.classList.add('selected-out');
                } else if (dTime > inTime && dTime < outTime) {
                    cell.classList.add('in-range');
                }

                (function(selectedDate) {
                    cell.addEventListener('click', function(e) {
                        e.stopPropagation();
                        handleDateSelection(selectedDate);
                    });
                })(dateObj);
            }

            container.appendChild(cell);
        }
    }

    function handleDateSelection(chosenDate) {
        if (calActiveTab === 'checkin') {
            selCheckin = new Date(chosenDate);
            // If checkout is on or before new checkin, move checkout to next day
            if (selCheckout.getTime() <= selCheckin.getTime()) {
                selCheckout = new Date(selCheckin.getTime() + 86400000);
            }
            updateMainDateDisplays();
            // Automatically switch to checkout selection tab
            switchAkbarCalTab('checkout');
        } else {
            // Checkout mode
            if (chosenDate.getTime() <= selCheckin.getTime()) {
                // If user clicks a date before checkin, reset checkin to that date
                selCheckin = new Date(chosenDate);
                selCheckout = new Date(selCheckin.getTime() + 86400000);
                updateMainDateDisplays();
                switchAkbarCalTab('checkout');
            } else {
                selCheckout = new Date(chosenDate);
                updateMainDateDisplays();
                renderCalendarMonths();
                // Both dates chosen -> close dropdown smoothly
                setTimeout(function() {
                    closeAkbarDropdowns();
                }, 200);
            }
        }
    }

    function renderCalendarMonths() {
        var m1Year = calViewYear;
        var m1Month = calViewMonth;

        var m2Year = m1Month === 11 ? m1Year + 1 : m1Year;
        var m2Month = m1Month === 11 ? 0 : m1Month + 1;

        if (calMonth1Title) calMonth1Title.textContent = monthNames[m1Month] + ' ' + m1Year;
        if (calMonth2Title) calMonth2Title.textContent = monthNames[m2Month] + ' ' + m2Year;

        // Check if prev button should be disabled (cannot navigate before current month)
        var realToday = new Date();
        var currentRealMonth = realToday.getMonth();
        var currentRealYear = realToday.getFullYear();
        if (calPrevBtn) {
            calPrevBtn.disabled = (m1Year < currentRealYear || (m1Year === currentRealYear && m1Month <= currentRealMonth));
        }

        if (calDays1) renderMonthGrid(m1Year, m1Month, calDays1);
        if (calDays2) renderMonthGrid(m2Year, m2Month, calDays2);
    }

    // Trigger Calendar on Check-In click
    if (checkinCol) {
        checkinCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            switchAkbarCalTab('checkin');
            if (calDropdown) calDropdown.classList.add('show');
        });
    }

    // Trigger Calendar on Check-Out click
    if (checkoutCol) {
        checkoutCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            switchAkbarCalTab('checkout');
            if (calDropdown) calDropdown.classList.add('show');
        });
    }

    // Initial render of calendar and dates
    updateMainDateDisplays();
    renderCalendarMonths();

    // =========================================================================
    // 3. GUESTS SELECTOR LOGIC
    // =========================================================================
    var akRooms = 2;
    var akAdults = 4;
    var akChildren = 0;

    if (guestsCol) {
        guestsCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            if (guestsDropdown) guestsDropdown.classList.add('show');
        });
    }

    window.modifyAkbarGuests = function(type, delta) {
        if (type === 'rooms') {
            akRooms = Math.max(1, Math.min(10, akRooms + delta));
            document.getElementById('akbarRoomsCounterVal').textContent = akRooms;
        } else if (type === 'adults') {
            akAdults = Math.max(1, Math.min(30, akAdults + delta));
            document.getElementById('akbarAdultsCounterVal').textContent = akAdults;
        } else if (type === 'children') {
            akChildren = Math.max(0, Math.min(10, akChildren + delta));
            document.getElementById('akbarChildrenCounterVal').textContent = akChildren;
        }

        var totalGuests = akAdults + akChildren;
        document.getElementById('akbarRoomsDisplay').textContent = akRooms;
        document.getElementById('akbarGuestsDisplay').textContent = totalGuests;
        document.getElementById('akbarGuestSubtitle').textContent = akAdults + " Adults, " + akChildren + " Children";

        document.getElementById('akbarHiddenRooms').value = akRooms;
        document.getElementById('akbarHiddenAdults').value = akAdults;
        document.getElementById('akbarHiddenChildren').value = akChildren;
    };

    window.closeAkbarDropdowns = function() {
        if (destDropdown) destDropdown.classList.remove('show');
        if (calDropdown) calDropdown.classList.remove('show');
        if (guestsDropdown) guestsDropdown.classList.remove('show');
    };

    document.addEventListener('click', function() {
        closeAkbarDropdowns();
    });

    window.filterDealsTab = function(btn, category) {
        var tabs = document.querySelectorAll('.deals-tab-btn');
        tabs.forEach(function(t) { t.classList.remove('active'); });
        btn.classList.add('active');
    };

});
</script>
