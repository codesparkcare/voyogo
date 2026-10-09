<?php
$defaultCheckin  = date('Y-m-d', strtotime('+3 days'));
$defaultCheckout = date('Y-m-d', strtotime('+7 days'));
$defaultNights   = 4;
?>

<!-- Main Layout with B2B Left Sidebar -->
<div style="display: grid; grid-template-columns: 200px 1fr; gap: 24px; align-items: start;">

    <!-- Left Vertical Nav matching user interface -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); overflow: hidden; min-height: 480px;">
        <a href="<?php echo site_url('franchise/flight'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #475569; font-weight: 600; font-size: 15px; border-left: 5px solid transparent; transition: background 0.15s;">
            <i class="fa-solid fa-plane-departure" style="font-size: 18px; color: #64748b;"></i>
            <span>Flight</span>
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #ea580c; font-weight: 700; font-size: 15px; border-left: 5px solid #f97316; background: #fff7ed;">
            <i class="fa-solid fa-hotel" style="font-size: 18px; color: #ea580c;"></i>
            <span>Hotel</span>
        </a>
    </div>

    <!-- Right Side: Hotel Hero & Akbar Search Engine matching 3rd Screenshot -->
    <div>
        <!-- Blue Hero Banner matching Screenshot 3 -->
        <div class="ak-hero-banner">
            <div class="ak-hero-overlay"></div>
            <div class="ak-hero-content">
                
                <!-- Headline matching Screenshot 3 -->
                <h1 class="ak-hero-heading">Book Domestic and International Hotels</h1>

                <!-- Unified White Horizontal Search Card matching Screenshot 3 -->
                <div class="ak-search-card">
                    <form action="<?php echo site_url('franchise/hotel_search'); ?>" method="GET" id="franchiseHotelForm" class="ak-search-form">
                        
                        <!-- 1. Destination Column -->
                        <div class="ak-search-col" id="colDest" onclick="openHotelDestDropdown(event)" style="flex: 2.2;">
                            <div class="ak-col-label">
                                <span>ENTER YOUR DESTINATION OR PROPERTY</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div id="hotelDestCity" class="ak-dest-main">Tirunelveli</div>
                                    <div id="hotelDestSub" class="ak-dest-sub">Tirunelveli</div>
                                </div>
                                <button type="button" class="ak-crosshair-icon" id="hotelLocationBtn" title="Detect Current Location" style="background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; outline: none;">
                                    <i class="fa-solid fa-crosshairs" style="color: #64748b; font-size: 16px; transition: color 0.2s;"></i>
                                </button>
                            </div>
                            <input type="hidden" name="city" id="hotelCityInput" value="Tirunelveli">

                            <!-- Destination Autocomplete Popup -->
                            <div class="ak-dest-popup" id="hotelDestDropdown" onclick="event.stopPropagation()">
                                <div style="position: relative; margin-bottom: 12px;">
                                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 12px; color: #64748b; font-size: 13px;"></i>
                                    <input type="text" id="hotelCitySearchInput" placeholder="Type city or hotel destination (e.g. Tirunelveli, Goa, Mumbai)..." oninput="filterHotelCities(this.value)">
                                </div>
                                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Popular Hotel Destinations</div>
                                <div class="ak-pills-wrap">
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Tirunelveli', 'Tamil Nadu, India')">Tirunelveli</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Goa', 'Goa, India')">Goa</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Mumbai', 'Maharashtra, India')">Mumbai</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Delhi NCR', 'Delhi, India')">Delhi NCR</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Dubai', 'United Arab Emirates')">Dubai</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Madurai', 'Tamil Nadu, India')">Madurai</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Jaipur', 'Rajasthan, India')">Jaipur</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Maldives', 'South Asia')">Maldives</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Bangalore', 'Karnataka, India')">Bangalore</span>
                                    <span class="ak-dest-pill" onclick="selectHotelCity('Singapore', 'Singapore')">Singapore</span>
                                </div>
                                <div class="ak-city-list" id="hotelCityList"></div>
                            </div>
                        </div>

                        <!-- 2. Check In Date Column matching Screenshot 3 -->
                        <div class="ak-search-col" id="colCheckin" onclick="openHotelCalendar(event, 'checkin')" style="flex: 1.25;">
                            <div class="ak-col-label">
                                <span>CHECK IN</span> <i class="fa-solid fa-chevron-down ak-chevron"></i>
                            </div>
                            <div class="ak-date-main-row">
                                <span class="ak-date-num" id="hotelCheckinNum"><?php echo date('d', strtotime($defaultCheckin)); ?></span>
                                <span class="ak-date-mon" id="hotelCheckinMon"><?php echo date("M'y", strtotime($defaultCheckin)); ?></span>
                            </div>
                            <div class="ak-date-day" id="hotelCheckinDay"><?php echo date('l', strtotime($defaultCheckin)); ?></div>
                            <input type="hidden" name="checkin" id="hotelCheckinInput" value="<?php echo $defaultCheckin; ?>">
                        </div>

                        <!-- Floating Nights Badge Divider matching Screenshot 3 -->
                        <div class="ak-nights-divider">
                            <div class="ak-nights-pill">
                                <span id="hotelNightsCount"><?php echo $defaultNights; ?></span> NIGHTS
                            </div>
                        </div>

                        <!-- 3. Check Out Date Column matching Screenshot 3 -->
                        <div class="ak-search-col" id="colCheckout" onclick="openHotelCalendar(event, 'checkout')" style="flex: 1.25; padding-left: 36px;">
                            <div class="ak-col-label">
                                <span>CHECK OUT</span> <i class="fa-solid fa-chevron-down ak-chevron"></i>
                            </div>
                            <div class="ak-date-main-row">
                                <span class="ak-date-num" id="hotelCheckoutNum"><?php echo date('d', strtotime($defaultCheckout)); ?></span>
                                <span class="ak-date-mon" id="hotelCheckoutMon"><?php echo date("M'y", strtotime($defaultCheckout)); ?></span>
                            </div>
                            <div class="ak-date-day" id="hotelCheckoutDay"><?php echo date('l', strtotime($defaultCheckout)); ?></div>
                            <input type="hidden" name="checkout" id="hotelCheckoutInput" value="<?php echo $defaultCheckout; ?>">
                        </div>

                        <!-- Akbar Dual-Month Interactive Calendar Dropdown matching Screenshot 3 -->
                        <div class="akbar-dropdown-panel akbar-calendar-panel" id="hotelCalendarDropdown" onclick="event.stopPropagation();">
                            <!-- Top Switcher Tabs -->
                            <div class="akbar-cal-header-tabs">
                                <div class="akbar-cal-tab active" id="hotelTabCheckin" onclick="switchHotelCalTab('checkin')">
                                    <span class="akbar-cal-tab-label">CHECK-IN</span>
                                    <span class="akbar-cal-tab-val" id="hotelCalTabCheckinVal"><?php echo date('M d, Y', strtotime($defaultCheckin)); ?></span>
                                </div>
                                <div class="akbar-cal-tab" id="hotelTabCheckout" onclick="switchHotelCalTab('checkout')">
                                    <span class="akbar-cal-tab-label">CHECK-OUT</span>
                                    <span class="akbar-cal-tab-val" id="hotelCalTabCheckoutVal"><?php echo date('M d, Y', strtotime($defaultCheckout)); ?></span>
                                </div>
                            </div>

                            <!-- Dual Month Calendars Body -->
                            <div class="akbar-cal-body">
                                <!-- Left Month -->
                                <div class="akbar-cal-month-wrap">
                                    <div class="akbar-cal-month-head">
                                        <button type="button" class="akbar-cal-nav-btn" id="hotelCalPrevBtn" onclick="navigateHotelCal(-1)">&larr;</button>
                                        <span class="akbar-cal-month-title" id="hotelCalMonth1Title">SEPTEMBER 2026</span>
                                        <span style="width: 30px;"></span>
                                    </div>
                                    <div class="akbar-cal-weekdays">
                                        <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                    </div>
                                    <div class="akbar-cal-days-grid" id="hotelCalDays1">
                                        <!-- Days injected via JS -->
                                    </div>
                                </div>

                                <!-- Right Month -->
                                <div class="akbar-cal-month-wrap">
                                    <div class="akbar-cal-month-head">
                                        <span style="width: 30px;"></span>
                                        <span class="akbar-cal-month-title" id="hotelCalMonth2Title">OCTOBER 2026</span>
                                        <button type="button" class="akbar-cal-nav-btn" id="hotelCalNextBtn" onclick="navigateHotelCal(1)">&rarr;</button>
                                    </div>
                                    <div class="akbar-cal-weekdays">
                                        <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                    </div>
                                    <div class="akbar-cal-days-grid" id="hotelCalDays2">
                                        <!-- Days injected via JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Rooms & Guests Column matching Screenshot 1 -->
                        <div class="ak-search-col" id="colGuests" onclick="openHotelGuestDropdown(event)" style="flex: 1.6;">
                            <div class="ak-col-label">
                                <span>ROOMS & GUESTS</span> <i class="fa-solid fa-chevron-down ak-chevron"></i>
                            </div>
                            <div class="ak-guests-main-row">
                                <span class="ak-guest-num" id="hotelRoomsDisplay">2</span>
                                <span class="ak-guest-lbl">Rooms</span>
                                <span class="ak-guest-num" id="hotelGuestsDisplay" style="margin-left: 6px;">4</span>
                                <span class="ak-guest-lbl">Guests</span>
                            </div>
                            <div class="ak-date-day" id="hotelGuestSub">4 Adults, 0 Children</div>

                            <input type="hidden" name="rooms" id="hotelHiddenRooms" value="2">
                            <input type="hidden" name="adults" id="hotelHiddenAdults" value="4">
                            <input type="hidden" name="children" id="hotelHiddenChildren" value="0">
                            <input type="hidden" name="roomData" id="hotelHiddenRoomData" value="">

                            <!-- Multi-Room & Guests Configuration Popover (Screenshot 1 Exact Match) -->
                            <div class="akbar-dropdown-panel akbar-guests-panel ak-guests-popup" id="hotelGuestsPopup" onclick="event.stopPropagation();">
                                <!-- Scrollable Room Cards List Container -->
                                <div class="akbar-rooms-list-container" id="hotelRoomsListContainer">
                                    <!-- Populated dynamically by JS -->
                                </div>

                                <!-- Footer: Add Room + Done -->
                                <div class="akbar-guests-footer">
                                    <button type="button" class="akbar-add-room-btn" id="hotelAddRoomBtn" onclick="addHotelRoom()">
                                        <i class="fa-solid fa-plus"></i> Add Another Room
                                    </button>
                                    <button type="button" class="akbar-done-btn" onclick="closeAllHotelDropdowns()">
                                        DONE
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Vibrant Red Search Button matching Screenshot 3 -->
                        <div class="ak-search-btn-col">
                            <button type="submit" class="ak-search-btn">
                                <span>SEARCH</span> <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <!-- B2B Franchise Hotel Info Cards Below Banner -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 24px;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 14px; color: #0f172a;">Direct B2B Net Rates</div>
                    <div style="font-size: 12px; color: #64748b;">Wholesale negotiated rates for store owners</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 14px; color: #0f172a;">Instant Float Settlement</div>
                    <div style="font-size: 12px; color: #64748b;">Instant voucher issuance from wallet float</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fa-solid fa-print"></i>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 14px; color: #0f172a;">Branded Vouchers</div>
                    <div style="font-size: 12px; color: #64748b;">Agency header printed with your Agent Code</div>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
/* 3rd Screenshot Style Replicas */
.ak-hero-banner {
    position: relative;
    border-radius: 16px;
    background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=85') no-repeat center center / cover;
    padding: 50px 36px 60px 36px;
    overflow: visible;
    box-shadow: 0 12px 30px rgba(9, 32, 75, 0.15);
}
.ak-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(9, 32, 75, 0.7) 0%, rgba(13, 52, 112, 0.8) 50%, rgba(9, 32, 75, 0.9) 100%);
    border-radius: 16px;
}
.ak-hero-content {
    position: relative;
    z-index: 10;
}
.ak-hero-heading {
    font-family: 'Outfit', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 22px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
    letter-spacing: -0.5px;
}

/* Horizontal Search Card */
.ak-search-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.ak-search-form {
    display: flex;
    align-items: stretch;
    width: 100%;
}
.ak-search-col {
    padding: 14px 18px;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: background 0.15s ease;
}
.ak-search-col:hover {
    background: #f8fafc;
}
.ak-col-label {
    font-size: 11px;
    font-weight: 700;
    color: #0d3470;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ak-chevron {
    font-size: 9px;
    color: #64748b;
}

.ak-dest-main {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ak-dest-sub {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ak-crosshair-icon {
    color: #94a3b8;
    font-size: 17px;
    padding: 6px;
    border-radius: 50%;
    transition: background 0.15s;
}
.ak-crosshair-icon:hover {
    background: #f1f5f9;
    color: #0d3470;
}

/* Date Displays */
.ak-date-main-row {
    display: flex;
    align-items: baseline;
    gap: 4px;
}
.ak-date-num {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.ak-date-mon {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}
.ak-date-day {
    font-size: 12px;
    color: #64748b;
    margin-top: 3px;
    font-weight: 500;
}

/* Floating Nights Divider */
.ak-nights-divider {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 0;
    z-index: 12;
    pointer-events: none;
}
.ak-nights-pill {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 10px;
    font-weight: 800;
    color: #334155;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
    text-align: center;
    transform: translateX(-50%);
    position: absolute;
    letter-spacing: 0.3px;
}

/* Guests Displays */
.ak-guests-main-row {
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.ak-guest-num {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.ak-guest-lbl {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
}

/* Red Search Button matching Screenshot 3 */
.ak-search-btn-col {
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ak-search-btn {
    background: #eb2027;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 16px 32px;
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
.ak-search-btn:hover {
    background: #d61a20;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(235, 32, 39, 0.5);
}

/* Popups */
.ak-dest-popup, .ak-guests-popup {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    border: 1px solid #cbd5e1;
    z-index: 99999;
    display: none;
    cursor: default;
}
.ak-dest-popup {
    width: 360px;
    padding: 16px;
}
.ak-dest-popup.open, .ak-guests-popup.open {
    display: block;
}
.ak-dest-popup input {
    width: 100%;
    padding: 9px 12px 9px 34px;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    outline: none;
}
.ak-pills-wrap {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.ak-dest-pill {
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #0d3470;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    transition: all 0.12s;
}
.ak-dest-pill:hover {
    background: #e0f2fe;
    color: #0284c7;
    border-color: #bae6fd;
}
.ak-city-list {
    max-height: 180px;
    overflow-y: auto;
    border: 1px solid #f1f5f9;
    border-radius: 6px;
}
.ak-city-item {
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
    border-bottom: 1px solid #f8fafc;
}
.ak-city-item:hover {
    background: #f1f5f9;
    color: #0284c7;
}

/* Akbar Multi-Room Guest Popover (Screenshot 1 Exact Match) */
.ak-guests-popup.akbar-guests-panel {
    width: 380px;
    max-width: 95vw;
    padding: 0;
    border-radius: 8px;
    overflow: hidden;
    left: auto;
    right: 0;
    top: calc(100% + 10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.22);
    border: 1px solid #cbd5e1;
    background: #ffffff;
    z-index: 999999;
}
@media (max-width: 768px) {
    .ak-guests-popup.akbar-guests-panel {
        position: fixed;
        top: 50%;
        left: 50%;
        right: auto;
        transform: translate(-50%, -50%);
        max-height: 90vh;
        overflow-y: auto;
        width: 92vw;
    }
}
.akbar-rooms-list-container {
    max-height: 380px;
    overflow-y: auto;
}
.akbar-room-card {
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 20px;
    transition: background 0.15s ease;
    background: #ffffff;
}
.akbar-room-card.collapsed {
    background: #f8fafc;
    padding: 12px 20px;
    cursor: pointer;
}
.akbar-room-card.collapsed:hover {
    background: #f1f5f9;
}
.akbar-room-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.akbar-room-card.collapsed .akbar-room-head {
    margin-bottom: 0;
}
.akbar-room-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}
.akbar-room-summary-text {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    margin-top: 2px;
}
.akbar-room-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.akbar-room-row-label {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}
.akbar-room-row-sub {
    font-size: 11px;
    color: #64748b;
    font-weight: 500;
}
.akbar-counter-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}
.akbar-counter-val {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    min-width: 16px;
    text-align: center;
}
.akbar-counter-btn-group {
    display: flex;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    overflow: hidden;
}
.akbar-counter-btn {
    background: #ffffff;
    border: none;
    width: 32px;
    height: 30px;
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.1s;
}
.akbar-counter-btn:hover:not(:disabled) {
    background: #f1f5f9;
    color: #0d3470;
}
.akbar-counter-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
    background: #f8fafc;
}
.akbar-counter-btn:first-child {
    border-right: 1px solid #cbd5e1;
}
.akbar-child-age-box {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #e2e8f0;
}
.akbar-child-age-title {
    font-size: 11px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.akbar-child-age-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}
.akbar-child-select-wrap {
    position: relative;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    padding: 3px 8px 3px 8px;
    background: #ffffff;
}
.akbar-child-select-label {
    font-size: 9px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    display: block;
    line-height: 1;
    margin-bottom: 2px;
}
.akbar-child-select {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
}
.akbar-guests-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 20px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
}
.akbar-add-room-btn {
    background: none;
    border: none;
    color: #eb2027;
    font-weight: 800;
    font-size: 13px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0;
}
.akbar-add-room-btn:hover {
    text-decoration: underline;
}
.akbar-done-btn {
    background: #eb2027;
    color: #ffffff;
    border: none;
    padding: 8px 24px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.15s;
}
.akbar-done-btn:hover {
    background: #d01c22;
}

/* Akbar Dual-Month Calendar Dropdown matching Screenshot 3 */
.akbar-calendar-panel {
    width: 630px;
    max-width: 95vw;
    padding: 0;
    border-radius: 12px;
    overflow: hidden;
    position: absolute;
    top: calc(100% + 10px);
    left: 20%;
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    border: 1px solid #cbd5e1;
    background: #ffffff;
    z-index: 999999;
    display: none;
    text-align: left;
    cursor: default;
}
.akbar-calendar-panel.open, .akbar-calendar-panel.show {
    display: block;
}
@media (max-width: 991px) {
    .akbar-calendar-panel {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 90vh;
        overflow-y: auto;
        width: 95%;
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
    border-bottom-color: #09204b;
}
.akbar-cal-tab-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}
.akbar-cal-tab.active .akbar-cal-tab-label {
    color: #09204b;
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
    color: #09204b;
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
    background: #09204b;
    color: #ffffff;
    border-color: #09204b;
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
    color: #09204b;
}
.akbar-cal-day.sun:not(.disabled):not(.selected-in):not(.selected-out) {
    color: #ef4444;
}
.akbar-cal-day.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
    background: transparent;
}
.akbar-cal-day.selected-in, .akbar-cal-day.selected-out {
    background: #09204b !important;
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
</style>

<script>
const hotelCities = [
    { name: "Tirunelveli", sub: "Tamil Nadu, India" },
    { name: "Goa", sub: "Goa, India" },
    { name: "Mumbai", sub: "Maharashtra, India" },
    { name: "Delhi NCR", sub: "Delhi, India" },
    { name: "Dubai", sub: "United Arab Emirates" },
    { name: "Madurai", sub: "Tamil Nadu, India" },
    { name: "Jaipur", sub: "Rajasthan, India" },
    { name: "Maldives", sub: "South Asia" },
    { name: "Bangalore", sub: "Karnataka, India" },
    { name: "Chennai", sub: "Tamil Nadu, India" },
    { name: "Hyderabad", sub: "Telangana, India" },
    { name: "Kolkata", sub: "West Bengal, India" },
    { name: "Singapore", sub: "Singapore" },
    { name: "Bangkok", sub: "Thailand" },
    { name: "Phuket", sub: "Thailand" }
];

// Dual-Month Calendar States & Variables
var monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];
var shortMonNames = ["Jan'y", "Feb'y", "Mar'y", "Apr'y", "May'y", "Jun'y", "Jul'y", "Aug'y", "Sep'y", "Oct'y", "Nov'y", "Dec'y"];
var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

var rawCheckinVal = document.getElementById('hotelCheckinInput').value;
var rawCheckoutVal = document.getElementById('hotelCheckoutInput').value;

var selCheckin = rawCheckinVal ? new Date(rawCheckinVal + 'T00:00:00') : new Date();
selCheckin.setHours(0,0,0,0);
var selCheckout = rawCheckoutVal ? new Date(rawCheckoutVal + 'T00:00:00') : new Date(selCheckin.getTime() + 4 * 86400000);
selCheckout.setHours(0,0,0,0);

var calActiveTab = 'checkin';
var calViewYear = selCheckin.getFullYear();
var calViewMonth = selCheckin.getMonth();

function closeAllHotelDropdowns() {
    var destDropdown = document.getElementById('hotelDestDropdown');
    var guestsPopup = document.getElementById('hotelGuestsPopup');
    var calDropdown = document.getElementById('hotelCalendarDropdown');
    if (destDropdown) destDropdown.classList.remove('open');
    if (guestsPopup) guestsPopup.classList.remove('open');
    if (calDropdown) calDropdown.classList.remove('open', 'show');
}

document.addEventListener('click', function(e) {
    const colDest = document.getElementById('colDest');
    const colGuests = document.getElementById('colGuests');
    const colCheckin = document.getElementById('colCheckin');
    const colCheckout = document.getElementById('colCheckout');
    const calDropdown = document.getElementById('hotelCalendarDropdown');

    if ((colDest && colDest.contains(e.target)) ||
        (colGuests && colGuests.contains(e.target)) ||
        (colCheckin && colCheckin.contains(e.target)) ||
        (colCheckout && colCheckout.contains(e.target)) ||
        (calDropdown && calDropdown.contains(e.target))) {
        return;
    }
    closeAllHotelDropdowns();
});

// Location Crosshairs Geolocation Button Handler (Reverse Geocoding via OSM Nominatim)
document.addEventListener('DOMContentLoaded', function() {
    var locBtn = document.getElementById('hotelLocationBtn');
    if (locBtn) {
        locBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            var icon = locBtn.querySelector('i');
            if (icon) {
                icon.className = 'fa-solid fa-spinner fa-spin';
                icon.style.color = '#0284c7';
            }

            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                if (icon) { icon.className = 'fa-solid fa-crosshairs'; icon.style.color = '#64748b'; }
                return;
            }

            navigator.geolocation.getCurrentPosition(function(pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;

                // Reverse geocoding via OpenStreetMap Nominatim
                fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=10')
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        var city = '';
                        var state = '';
                        var country = '';
                        if (data && data.address) {
                            city = data.address.city || data.address.town || data.address.village || data.address.county || data.address.state_district || 'My Location';
                            state = data.address.state || '';
                            country = data.address.country || 'India';
                        } else {
                            city = 'Current Location';
                        }
                        var sub = (state ? state + ', ' : '') + country;
                        selectHotelCity(city, sub);

                        if (icon) {
                            icon.className = 'fa-solid fa-crosshairs';
                            icon.style.color = '#10b981';
                            setTimeout(function() { icon.style.color = '#64748b'; }, 2500);
                        }
                    })
                    .catch(function() {
                        selectHotelCity('Current Location', 'GPS (' + lat.toFixed(2) + ', ' + lng.toFixed(2) + ')');
                        if (icon) {
                            icon.className = 'fa-solid fa-crosshairs';
                            icon.style.color = '#10b981';
                            setTimeout(function() { icon.style.color = '#64748b'; }, 2500);
                        }
                    });
            }, function(err) {
                if (icon) { icon.className = 'fa-solid fa-crosshairs'; icon.style.color = '#64748b'; }
                if (err.code === 1) {
                    alert('Location access was denied. Please allow location permissions in your browser.');
                } else {
                    alert('Unable to retrieve your location. Please select your destination city from the list.');
                }
            }, {
                enableHighAccuracy: true,
                timeout: 8000,
                maximumAge: 60000
            });
        });
    }
});

function openHotelDestDropdown(e) {
    e.stopPropagation();
    closeAllHotelDropdowns();
    document.getElementById('hotelDestDropdown').classList.add('open');
    filterHotelCities('');
    const input = document.getElementById('hotelCitySearchInput');
    input.value = '';
    setTimeout(() => input.focus(), 100);
}

function filterHotelCities(query) {
    const list = document.getElementById('hotelCityList');
    list.innerHTML = '';
    const q = query.trim().toLowerCase();
    const filtered = hotelCities.filter(c => c.name.toLowerCase().includes(q) || c.sub.toLowerCase().includes(q));

    if (filtered.length === 0) {
        list.innerHTML = '<div style="padding: 10px; color: #94a3b8; font-size: 12px; text-align: center;">No cities found</div>';
        return;
    }

    filtered.forEach(c => {
        const item = document.createElement('div');
        item.className = 'ak-city-item';
        item.innerHTML = `<strong>${c.name}</strong> <small style="color: #64748b; display: block; font-size: 11px;">${c.sub}</small>`;
        item.onclick = function(e) {
            e.stopPropagation();
            selectHotelCity(c.name, c.sub);
        };
        list.appendChild(item);
    });
}

function selectHotelCity(name, sub) {
    document.getElementById('hotelDestCity').innerText = name;
    document.getElementById('hotelDestSub').innerText = sub || name;
    document.getElementById('hotelCityInput').value = name;
    closeAllHotelDropdowns();
}

// Akbar Dual-Month Calendar Logic
function openHotelCalendar(e, tab) {
    e.stopPropagation();
    closeAllHotelDropdowns();
    switchHotelCalTab(tab);
    var cal = document.getElementById('hotelCalendarDropdown');
    if (cal) cal.classList.add('open', 'show');
}

function formatDateISO(d) {
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
}

function formatTabDate(d) {
    var monNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return monNames[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
}

function updateHotelDateDisplays() {
    var inISO = formatDateISO(selCheckin);
    var outISO = formatDateISO(selCheckout);

    document.getElementById('hotelCheckinInput').value = inISO;
    document.getElementById('hotelCheckoutInput').value = outISO;

    // Checkin card text
    var yyIn = String(selCheckin.getFullYear()).slice(-2);
    document.getElementById('hotelCheckinNum').innerText = String(selCheckin.getDate()).padStart(2, '0');
    document.getElementById('hotelCheckinMon').innerText = shortMonNames[selCheckin.getMonth()].replace("'y", "'" + yyIn);
    document.getElementById('hotelCheckinDay').innerText = dayNames[selCheckin.getDay()];

    // Checkout card text
    var yyOut = String(selCheckout.getFullYear()).slice(-2);
    document.getElementById('hotelCheckoutNum').innerText = String(selCheckout.getDate()).padStart(2, '0');
    document.getElementById('hotelCheckoutMon').innerText = shortMonNames[selCheckout.getMonth()].replace("'y", "'" + yyOut);
    document.getElementById('hotelCheckoutDay').innerText = dayNames[selCheckout.getDay()];

    // Tab values
    document.getElementById('hotelCalTabCheckinVal').textContent = formatTabDate(selCheckin);
    document.getElementById('hotelCalTabCheckoutVal').textContent = formatTabDate(selCheckout);

    // Nights count
    var diffMs = selCheckout.getTime() - selCheckin.getTime();
    var nights = Math.max(1, Math.round(diffMs / 86400000));
    document.getElementById('hotelNightsCount').innerText = nights;
}

function switchHotelCalTab(tab) {
    calActiveTab = tab;
    var tabIn = document.getElementById('hotelTabCheckin');
    var tabOut = document.getElementById('hotelTabCheckout');
    if (tab === 'checkin') {
        if (tabIn) tabIn.classList.add('active');
        if (tabOut) tabOut.classList.remove('active');
        calViewYear = selCheckin.getFullYear();
        calViewMonth = selCheckin.getMonth();
    } else {
        if (tabOut) tabOut.classList.add('active');
        if (tabIn) tabIn.classList.remove('active');
        calViewYear = selCheckout.getFullYear();
        calViewMonth = selCheckout.getMonth();
    }
    renderHotelCalendarMonths();
}

function navigateHotelCal(dir) {
    calViewMonth += dir;
    if (calViewMonth < 0) {
        calViewMonth = 11;
        calViewYear--;
    } else if (calViewMonth > 11) {
        calViewMonth = 0;
        calViewYear++;
    }
    renderHotelCalendarMonths();
}

function renderHotelMonthGrid(year, month, container) {
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

        if (dateObj.getDay() === 0) cell.classList.add('sun');

        if (dateObj.getTime() < today.getTime()) {
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
                    handleHotelDateSelection(selectedDate);
                });
            })(dateObj);
        }

        container.appendChild(cell);
    }
}

function handleHotelDateSelection(chosenDate) {
    if (calActiveTab === 'checkin') {
        selCheckin = new Date(chosenDate);
        if (selCheckout.getTime() <= selCheckin.getTime()) {
            selCheckout = new Date(selCheckin.getTime() + 86400000);
        }
        updateHotelDateDisplays();
        switchHotelCalTab('checkout');
    } else {
        if (chosenDate.getTime() <= selCheckin.getTime()) {
            selCheckin = new Date(chosenDate);
            selCheckout = new Date(selCheckin.getTime() + 86400000);
            updateHotelDateDisplays();
            switchHotelCalTab('checkout');
        } else {
            selCheckout = new Date(chosenDate);
            updateHotelDateDisplays();
            renderHotelCalendarMonths();
            setTimeout(function() {
                closeAllHotelDropdowns();
            }, 250);
        }
    }
}

function renderHotelCalendarMonths() {
    var m1Year = calViewYear;
    var m1Month = calViewMonth;

    var m2Year = m1Month === 11 ? m1Year + 1 : m1Year;
    var m2Month = m1Month === 11 ? 0 : m1Month + 1;

    var t1 = document.getElementById('hotelCalMonth1Title');
    var t2 = document.getElementById('hotelCalMonth2Title');
    if (t1) t1.textContent = monthNames[m1Month] + ' ' + m1Year;
    if (t2) t2.textContent = monthNames[m2Month] + ' ' + m2Year;

    var realToday = new Date();
    var curYear = realToday.getFullYear();
    var curMonth = realToday.getMonth();
    var prevBtn = document.getElementById('hotelCalPrevBtn');
    if (prevBtn) {
        prevBtn.disabled = (m1Year < curYear || (m1Year === curYear && m1Month <= curMonth));
    }

    var grid1 = document.getElementById('hotelCalDays1');
    var grid2 = document.getElementById('hotelCalDays2');
    if (grid1) renderHotelMonthGrid(m1Year, m1Month, grid1);
    if (grid2) renderHotelMonthGrid(m2Year, m2Month, grid2);
}

// Akbar Multi-Room & Guests Engine (Screenshot 1 Exact Match)
var hotelRoomsData = [
    { adults: 2, children: 0, childAges: [] },
    { adults: 2, children: 0, childAges: [] }
];
var activeEditRoomIndex = 0;

var hotelRoomsListContainer = document.getElementById('hotelRoomsListContainer');
var hotelAddRoomBtn = document.getElementById('hotelAddRoomBtn');

function openHotelGuestDropdown(e) {
    e.stopPropagation();
    closeAllHotelDropdowns();
    renderHotelRooms();
    var popup = document.getElementById('hotelGuestsPopup');
    if (popup) {
        popup.classList.add('open', 'show');
    }
}

function renderHotelRooms() {
    if (!hotelRoomsListContainer) return;
    hotelRoomsListContainer.innerHTML = '';

    var totalAdults = 0;
    var totalChildren = 0;
    var totalRooms = hotelRoomsData.length;

    hotelRoomsData.forEach(function(room, idx) {
        totalAdults += room.adults;
        totalChildren += room.children;

        var isExpanded = (idx === activeEditRoomIndex || totalRooms === 1);
        var card = document.createElement('div');
        card.className = 'akbar-room-card' + (isExpanded ? '' : ' collapsed');

        if (isExpanded) {
            var html = '';
            html += '<div class="akbar-room-head">';
            html += '  <div class="akbar-room-title">Room ' + (idx + 1) + '</div>';
            if (totalRooms > 1) {
                html += '  <i class="fa-solid fa-xmark" onclick="event.stopPropagation(); removeHotelRoom(' + idx + ');" style="font-size: 16px; color: #94a3b8; cursor: pointer;" title="Remove Room"></i>';
            }
            html += '</div>';

            // Adults Row
            html += '<div class="akbar-room-row">';
            html += '  <div><div class="akbar-room-row-label">Adults</div><div class="akbar-room-row-sub">12+ yrs</div></div>';
            html += '  <div class="akbar-counter-wrap">';
            html += '    <span class="akbar-counter-val">' + room.adults + '</span>';
            html += '    <div class="akbar-counter-btn-group">';
            html += '      <button type="button" class="akbar-counter-btn" onclick="updateHotelRoomAdults(' + idx + ', -1)" ' + (room.adults <= 1 ? 'disabled' : '') + '>-</button>';
            html += '      <button type="button" class="akbar-counter-btn" onclick="updateHotelRoomAdults(' + idx + ', 1)" ' + (room.adults >= 8 ? 'disabled' : '') + '>+</button>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            // Children Row
            html += '<div class="akbar-room-row">';
            html += '  <div><div class="akbar-room-row-label">Children</div><div class="akbar-room-row-sub">0- 12 Years</div></div>';
            html += '  <div class="akbar-counter-wrap">';
            html += '    <span class="akbar-counter-val">' + room.children + '</span>';
            html += '    <div class="akbar-counter-btn-group">';
            html += '      <button type="button" class="akbar-counter-btn" onclick="updateHotelRoomChildren(' + idx + ', -1)" ' + (room.children <= 0 ? 'disabled' : '') + '>-</button>';
            html += '      <button type="button" class="akbar-counter-btn" onclick="updateHotelRoomChildren(' + idx + ', 1)" ' + (room.children >= 4 ? 'disabled' : '') + '>+</button>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            // Children Ages Box
            if (room.children > 0) {
                html += '<div class="akbar-child-age-box">';
                html += '  <div class="akbar-child-age-title">CHILDREN\'S AGE</div>';
                html += '  <div class="akbar-child-age-grid">';
                for (var c = 0; c < room.children; c++) {
                    var childAge = room.childAges[c] !== undefined ? room.childAges[c] : 0;
                    html += '    <div class="akbar-child-select-wrap">';
                    html += '      <label class="akbar-child-select-label">Child ' + (c + 1) + '</label>';
                    html += '      <select class="akbar-child-select" onchange="updateHotelChildAge(' + idx + ', ' + c + ', this.value)">';
                    html += '        <option value="0"' + (childAge === 0 ? ' selected' : '') + '>Under 1</option>';
                    for (var a = 1; a <= 12; a++) {
                        html += '        <option value="' + a + '"' + (childAge === a ? ' selected' : '') + '>' + a + ' ' + (a === 1 ? 'yr' : 'yrs') + '</option>';
                    }
                    html += '      </select>';
                    html += '    </div>';
                }
                html += '  </div>';
                html += '</div>';
            }

            card.innerHTML = html;
        } else {
            // Collapsed View
            var html = '';
            html += '<div class="akbar-room-head">';
            html += '  <div>';
            html += '    <div class="akbar-room-title">Room ' + (idx + 1) + '</div>';
            html += '    <div class="akbar-room-summary-text"><strong>' + room.adults + '</strong> Adults <strong>' + room.children + '</strong> Children</div>';
            html += '  </div>';
            html += '  <div style="display: flex; gap: 12px; align-items: center;">';
            html += '    <i class="fa-solid fa-pen" onclick="event.stopPropagation(); editHotelRoom(' + idx + ');" style="font-size: 13px; color: #64748b; cursor: pointer;" title="Edit Room"></i>';
            if (totalRooms > 1) {
                html += '    <i class="fa-solid fa-xmark" onclick="event.stopPropagation(); removeHotelRoom(' + idx + ');" style="font-size: 16px; color: #94a3b8; cursor: pointer;" title="Remove Room"></i>';
            }
            html += '  </div>';
            html += '</div>';

            card.innerHTML = html;
            card.onclick = function() {
                editHotelRoom(idx);
            };
        }

        hotelRoomsListContainer.appendChild(card);
    });

    if (hotelAddRoomBtn) {
        hotelAddRoomBtn.style.display = (totalRooms >= 8) ? 'none' : 'inline-flex';
    }

    var totalGuests = totalAdults + totalChildren;
    var roomsDisplay = document.getElementById('hotelRoomsDisplay');
    var guestsDisplay = document.getElementById('hotelGuestsDisplay');
    var guestSubtitle = document.getElementById('hotelGuestSub');

    if (roomsDisplay) roomsDisplay.textContent = totalRooms;
    if (guestsDisplay) guestsDisplay.textContent = totalGuests;
    if (guestSubtitle) {
        var childStr = totalChildren === 1 ? '1 Child' : totalChildren + ' Children';
        guestSubtitle.textContent = totalAdults + ' Adults, ' + childStr;
    }

    var hiddenRooms = document.getElementById('hotelHiddenRooms');
    var hiddenAdults = document.getElementById('hotelHiddenAdults');
    var hiddenChildren = document.getElementById('hotelHiddenChildren');
    var hiddenRoomData = document.getElementById('hotelHiddenRoomData');

    if (hiddenRooms) hiddenRooms.value = totalRooms;
    if (hiddenAdults) hiddenAdults.value = totalAdults;
    if (hiddenChildren) hiddenChildren.value = totalChildren;
    if (hiddenRoomData) hiddenRoomData.value = JSON.stringify(hotelRoomsData);
}

function addHotelRoom() {
    if (hotelRoomsData.length < 8) {
        hotelRoomsData.push({ adults: 1, children: 0, childAges: [] });
        activeEditRoomIndex = hotelRoomsData.length - 1;
        renderHotelRooms();
    }
}

function removeHotelRoom(idx) {
    if (hotelRoomsData.length > 1) {
        hotelRoomsData.splice(idx, 1);
        if (activeEditRoomIndex >= hotelRoomsData.length) {
            activeEditRoomIndex = hotelRoomsData.length - 1;
        }
        renderHotelRooms();
    }
}

function editHotelRoom(idx) {
    activeEditRoomIndex = idx;
    renderHotelRooms();
}

function updateHotelRoomAdults(idx, delta) {
    if (hotelRoomsData[idx]) {
        hotelRoomsData[idx].adults = Math.max(1, Math.min(8, hotelRoomsData[idx].adults + delta));
        renderHotelRooms();
    }
}

function updateHotelRoomChildren(idx, delta) {
    if (hotelRoomsData[idx]) {
        var newCount = Math.max(0, Math.min(4, hotelRoomsData[idx].children + delta));
        hotelRoomsData[idx].children = newCount;
        while (hotelRoomsData[idx].childAges.length < newCount) {
            var defaultAge = hotelRoomsData[idx].childAges.length === 0 ? 7 : 3;
            hotelRoomsData[idx].childAges.push(defaultAge);
        }
        if (hotelRoomsData[idx].childAges.length > newCount) {
            hotelRoomsData[idx].childAges.length = newCount;
        }
        renderHotelRooms();
    }
}

function updateHotelChildAge(roomIdx, childIdx, age) {
    if (hotelRoomsData[roomIdx] && hotelRoomsData[roomIdx].childAges) {
        hotelRoomsData[roomIdx].childAges[childIdx] = parseInt(age) || 0;
        var hiddenRoomData = document.getElementById('hotelHiddenRoomData');
        if (hiddenRoomData) hiddenRoomData.value = JSON.stringify(hotelRoomsData);
    }
}

// Initial setup
renderHotelRooms();
updateHotelDateDisplays();
renderHotelCalendarMonths();
</script>
