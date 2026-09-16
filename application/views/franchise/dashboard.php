<!-- Main Container with B2B Left Sidebar -->
<div style="display: grid; grid-template-columns: 200px 1fr; gap: 24px; align-items: start;">

    <!-- Left Vertical Nav matching user interface -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); overflow: hidden; min-height: 480px;">
        <a href="<?php echo site_url('franchise/flight'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #ea580c; font-weight: 700; font-size: 15px; border-left: 5px solid #f97316; background: #fff7ed;">
            <i class="fa-solid fa-plane-departure" style="font-size: 18px; color: #ea580c;"></i>
            <span>Flight</span>
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #475569; font-weight: 600; font-size: 15px; border-left: 5px solid transparent; transition: background 0.15s;">
            <i class="fa-solid fa-hotel" style="font-size: 18px; color: #64748b;"></i>
            <span>Hotel</span>
        </a>
    </div>

    <!-- Right Side: Flight Search Box (Faithfully Matching 1st Screenshot) -->
    <div>
        <div class="f-flight-card">
            
            <form action="<?php echo site_url('franchise/flight_search'); ?>" method="GET" id="b2bFlightForm">
                
                <!-- Top Header Row: Radios on Left & Fare Types on Right -->
                <div class="f-top-bar">
                    <!-- Trip Type Radios -->
                    <div class="f-trip-types">
                        <label class="f-radio-label active" id="lblOneWay">
                            <input type="radio" name="trip_type" value="oneway" checked onchange="handleTripTypeChange('oneway')">
                            <span class="f-radio-custom"></span>
                            <span class="f-radio-text">One Way</span>
                        </label>
                        <label class="f-radio-label" id="lblRoundTrip">
                            <input type="radio" name="trip_type" value="roundtrip" onchange="handleTripTypeChange('roundtrip')">
                            <span class="f-radio-custom"></span>
                            <span class="f-radio-text">Round Trip</span>
                        </label>
                        <label class="f-radio-label" id="lblMultiCity">
                            <input type="radio" name="trip_type" value="multicity" onchange="handleTripTypeChange('multicity')">
                            <span class="f-radio-custom"></span>
                            <span class="f-radio-text">Multi-City</span>
                        </label>
                    </div>

                    <!-- Special Fare Tags matching screenshot 1 -->
                    <div class="f-fare-tags">
                        <span class="f-fare-pill active" onclick="selectFareTag(this)">Regular Fares</span>
                        <span class="f-fare-pill" onclick="selectFareTag(this)"><i class="fa-solid fa-graduation-cap"></i> Student Fares</span>
                        <span class="f-fare-pill" onclick="selectFareTag(this)"><i class="fa-solid fa-person-military-pointing"></i> Armed Forces</span>
                        <span class="f-fare-pill" onclick="selectFareTag(this)"><i class="fa-solid fa-person-cane"></i> Senior Citizen</span>
                    </div>
                </div>

                <!-- Main Inputs Grid matching 1st Screenshot -->
                <div class="f-search-grid">

                    <!-- FROM Box with plane icon -->
                    <div class="f-input-box active-box" id="fFromBox" onclick="openAirportDropdown(event, 'from')">
                        <div class="f-input-lbl"><i class="fa-solid fa-plane-departure"></i> FROM</div>
                        <div class="f-input-main" id="fFromCityText">Delhi (DEL)</div>
                        <input type="hidden" name="origin" id="fFromCityInput" value="Delhi (DEL)">
                        <div class="f-input-sub" id="fFromAirportText">Indira Gandhi Intl Airport</div>

                        <!-- Dropdown Popup for FROM -->
                        <div class="f-dropdown-popup" id="fFromDropdown" onclick="event.stopPropagation()">
                            <div class="f-dropdown-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="fFromSearchInput" placeholder="Type city or airport (e.g. GOX, Delhi, BOM)..." oninput="filterAirports('from', this.value)">
                            </div>
                            <div class="f-pills-section">
                                <span class="f-pills-title">POPULAR DESTINATIONS</span>
                                <div class="f-pills-container" id="fFromPills"></div>
                            </div>
                            <div class="f-airport-list" id="fFromList"></div>
                        </div>
                    </div>

                    <!-- Swap Circle Button -->
                    <div class="f-swap-wrap">
                        <button type="button" class="f-swap-btn" onclick="swapFromTo(event)" title="Swap Cities">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        </button>
                    </div>

                    <!-- TO Box with plane icon -->
                    <div class="f-input-box" id="fToBox" onclick="openAirportDropdown(event, 'to')">
                        <div class="f-input-lbl"><i class="fa-solid fa-plane-arrival"></i> TO</div>
                        <div class="f-input-main" id="fToCityText">Mumbai (BOM)</div>
                        <input type="hidden" name="destination" id="fToCityInput" value="Mumbai (BOM)">
                        <div class="f-input-sub" id="fToAirportText">Chhatrapati Shivaji Maharaj Intl</div>

                        <!-- Dropdown Popup for TO -->
                        <div class="f-dropdown-popup" id="fToDropdown" onclick="event.stopPropagation()">
                            <div class="f-dropdown-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="fToSearchInput" placeholder="Type city or airport (e.g. GOX, Mumbai, GOI)..." oninput="filterAirports('to', this.value)">
                            </div>
                            <div class="f-pills-section">
                                <span class="f-pills-title">POPULAR DESTINATIONS</span>
                                <div class="f-pills-container" id="fToPills"></div>
                            </div>
                            <div class="f-airport-list" id="fToList"></div>
                        </div>
                    </div>

                    <!-- DEPARTURE Date Box matching Screenshot 1 & 3 -->
                    <div class="f-input-box" id="fDepartBox" onclick="openFlightCalendar(event, 'depart')">
                        <div class="f-input-lbl"><i class="fa-regular fa-calendar-days"></i> DEPARTURE</div>
                        <div class="f-input-main" id="fDepartMainDate"><?php echo date('d/m/Y', strtotime('+3 days')); ?></div>
                        <div class="f-input-sub" id="fDepartSubDate"><?php echo date('D, d M Y', strtotime('+3 days')); ?></div>
                        <input type="hidden" name="depart_date" id="fDepartDateInput" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                    </div>

                    <!-- RETURN Date Box matching Screenshot 1 & 3 -->
                    <div class="f-input-box disabled" id="fReturnBox" onclick="openFlightCalendar(event, 'return')">
                        <div class="f-input-lbl"><i class="fa-regular fa-calendar-days"></i> RETURN</div>
                        <div class="f-input-main" id="fReturnMainDate"><?php echo date('d/m/Y', strtotime('+7 days')); ?></div>
                        <div class="f-input-sub" id="fReturnSubDate">Save up to 20% on round trips</div>
                        <input type="hidden" name="return_date" id="fReturnDateInput" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" disabled>
                    </div>

                    <!-- Akbar Dual-Month Interactive Calendar Dropdown matching Screenshot 3 -->
                    <div class="akbar-dropdown-panel akbar-calendar-panel" id="flightCalendarDropdown" onclick="event.stopPropagation();">
                        <!-- Top Switcher Tabs -->
                        <div class="akbar-cal-header-tabs">
                            <div class="akbar-cal-tab active" id="fFlightTabDepart" onclick="switchFlightCalTab('depart')">
                                <span class="akbar-cal-tab-label">DEPARTURE</span>
                                <span class="akbar-cal-tab-val" id="fFlightTabDepartVal"><?php echo date('M d, Y', strtotime('+3 days')); ?></span>
                            </div>
                            <div class="akbar-cal-tab" id="fFlightTabReturn" onclick="switchFlightCalTab('return')">
                                <span class="akbar-cal-tab-label">RETURN</span>
                                <span class="akbar-cal-tab-val" id="fFlightTabReturnVal"><?php echo date('M d, Y', strtotime('+7 days')); ?></span>
                            </div>
                        </div>

                        <!-- Dual Month Calendars Body -->
                        <div class="akbar-cal-body">
                            <!-- Left Month -->
                            <div class="akbar-cal-month-wrap">
                                <div class="akbar-cal-month-head">
                                    <button type="button" class="akbar-cal-nav-btn" id="fFlightCalPrevBtn" onclick="navigateFlightCal(-1)">&larr;</button>
                                    <span class="akbar-cal-month-title" id="fFlightCalMonth1Title">SEPTEMBER 2026</span>
                                    <span style="width: 30px;"></span>
                                </div>
                                <div class="akbar-cal-weekdays">
                                    <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                </div>
                                <div class="akbar-cal-days-grid" id="fFlightCalDays1">
                                    <!-- Days injected via JS -->
                                </div>
                            </div>

                            <!-- Right Month -->
                            <div class="akbar-cal-month-wrap">
                                <div class="akbar-cal-month-head">
                                    <span style="width: 30px;"></span>
                                    <span class="akbar-cal-month-title" id="fFlightCalMonth2Title">OCTOBER 2026</span>
                                    <button type="button" class="akbar-cal-nav-btn" id="fFlightCalNextBtn" onclick="navigateFlightCal(1)">&rarr;</button>
                                </div>
                                <div class="akbar-cal-weekdays">
                                    <span class="sun">Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                </div>
                                <div class="akbar-cal-days-grid" id="fFlightCalDays2">
                                    <!-- Days injected via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TRAVELERS & CLASS Box -->
                    <div class="f-input-box" id="fPaxBox" onclick="openPaxDropdown(event)">
                        <div class="f-input-lbl"><i class="fa-solid fa-users"></i> TRAVELERS & CLASS</div>
                        <div class="f-input-main" id="fPaxMainText">1 Traveler, Economy</div>
                        <div class="f-input-sub">Click to change</div>

                        <input type="hidden" name="adults" id="fHiddenAdults" value="1">
                        <input type="hidden" name="children" id="fHiddenChildren" value="0">
                        <input type="hidden" name="infants" id="fHiddenInfants" value="0">
                        <input type="hidden" name="cabin" id="fHiddenCabin" value="ECONOMY">

                        <!-- Travelers & Class Dropdown Popup -->
                        <div class="f-dropdown-popup f-pax-popup" id="fPaxDropdown" onclick="event.stopPropagation()">
                            <!-- Adults -->
                            <div class="f-counter-row">
                                <div>
                                    <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Adults</div>
                                    <div style="font-size: 11px; color: #64748b;">12+ years</div>
                                </div>
                                <div class="f-counter-ctrl">
                                    <button type="button" onclick="adjustPax('adult', -1)">-</button>
                                    <span id="fCountAdult">1</span>
                                    <button type="button" onclick="adjustPax('adult', 1)">+</button>
                                </div>
                            </div>
                            <!-- Children -->
                            <div class="f-counter-row">
                                <div>
                                    <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Children</div>
                                    <div style="font-size: 11px; color: #64748b;">2 - 12 years</div>
                                </div>
                                <div class="f-counter-ctrl">
                                    <button type="button" onclick="adjustPax('child', -1)">-</button>
                                    <span id="fCountChild">0</span>
                                    <button type="button" onclick="adjustPax('child', 1)">+</button>
                                </div>
                            </div>
                            <!-- Infants -->
                            <div class="f-counter-row">
                                <div>
                                    <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Infants</div>
                                    <div style="font-size: 11px; color: #64748b;">Under 2 years</div>
                                </div>
                                <div class="f-counter-ctrl">
                                    <button type="button" onclick="adjustPax('infant', -1)">-</button>
                                    <span id="fCountInfant">0</span>
                                    <button type="button" onclick="adjustPax('infant', 1)">+</button>
                                </div>
                            </div>
                            <!-- Cabin Class -->
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-top: 10px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Cabin Class</div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                    <label class="f-cabin-opt"><input type="radio" name="cabin_select" value="ECONOMY" checked onchange="setFranchiseCabin('ECONOMY', 'Economy')"> <span>Economy</span></label>
                                    <label class="f-cabin-opt"><input type="radio" name="cabin_select" value="PREMIUM_ECONOMY" onchange="setFranchiseCabin('PREMIUM_ECONOMY', 'Premium Economy')"> <span>Premium Econ</span></label>
                                    <label class="f-cabin-opt"><input type="radio" name="cabin_select" value="BUSINESS" onchange="setFranchiseCabin('BUSINESS', 'Business')"> <span>Business</span></label>
                                    <label class="f-cabin-opt"><input type="radio" name="cabin_select" value="FIRST" onchange="setFranchiseCabin('FIRST', 'First Class')"> <span>First</span></label>
                                </div>
                            </div>
                            <div style="text-align: right; margin-top: 14px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                                <button type="button" onclick="closeAllFranchiseDropdowns()" style="background: #78B722; color: #ffffff; border: none; padding: 7px 18px; border-radius: 6px; font-size: 12.5px; font-weight: 700; cursor: pointer;">Done</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom Center: Search Flights Button matching Screenshot 1 -->
                <div style="text-align: center; margin-top: 24px;">
                    <button type="submit" class="f-search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i> Search Flights
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>
/* 1st Screenshot Precise Aesthetic Styles */
.f-flight-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px 28px 28px 28px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    position: relative;
}

/* Top bar */
.f-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 14px;
}
.f-trip-types {
    display: flex;
    gap: 20px;
    align-items: center;
}
.f-radio-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
}
.f-radio-label input {
    accent-color: #558B2F;
    width: 17px;
    height: 17px;
    cursor: pointer;
}
.f-radio-label.active .f-radio-text {
    color: #0f172a;
    font-weight: 700;
}

/* Special fare pills on right */
.f-fare-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.f-fare-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    cursor: pointer;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.f-fare-pill.active {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1.5px solid #81c784;
    font-weight: 700;
}

/* Search Grid */
.f-search-grid {
    display: grid;
    grid-template-columns: 1.35fr auto 1.35fr 1.05fr 1.05fr 1.25fr;
    align-items: stretch;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
    position: relative;
}

/* Input Boxes */
.f-input-box {
    padding: 14px 18px;
    cursor: pointer;
    position: relative;
    border-right: 1px solid #e2e8f0;
    transition: background 0.15s;
}
.f-input-box:hover {
    background: #f8fafc;
}
.f-input-box.active-box {
    border: 2px solid #78B722;
    border-radius: 10px;
    background: #ffffff;
    margin: -1px;
    z-index: 2;
}
.f-input-box:last-child {
    border-right: none;
}
.f-input-box.disabled {
    background: #f8fafc;
    opacity: 0.75;
}

.f-input-lbl {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.f-input-lbl i {
    color: #475569;
    font-size: 12px;
}
.f-input-main {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.f-input-sub {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Swap Button */
.f-swap-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 0;
    position: relative;
    z-index: 10;
}
.f-swap-btn {
    position: absolute;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #86efac;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.f-swap-btn:hover {
    transform: scale(1.1);
    border-color: #16a34a;
}

/* Dropdown Popups */
.f-dropdown-popup {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 360px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    padding: 16px;
    z-index: 99999;
    display: none;
    text-align: left;
    cursor: default;
}
.f-dropdown-popup.open {
    display: block;
}
.f-dropdown-search {
    position: relative;
    margin-bottom: 12px;
}
.f-dropdown-search i {
    position: absolute;
    left: 12px;
    top: 11px;
    color: #64748b;
    font-size: 13px;
}
.f-dropdown-search input {
    width: 100%;
    padding: 9px 12px 9px 34px;
    border: 1.5px solid #09204b;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    outline: none;
}

/* Popular Pills */
.f-pills-section {
    margin-bottom: 12px;
}
.f-pills-title {
    font-size: 10.5px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 8px;
}
.f-pills-container {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    max-height: 130px;
    overflow-y: auto;
}
.f-dest-pill {
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 11.5px;
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    transition: all 0.12s;
    white-space: nowrap;
}
.f-dest-pill:hover {
    background: #e0f2fe;
    color: #0284c7;
    border-color: #bae6fd;
}

/* Airport List */
.f-airport-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
.f-airport-item {
    padding: 9px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s;
}
.f-airport-item:hover {
    background: #f0fdf4;
}
.f-airport-code {
    background: #e2e8f0;
    color: #0f172a;
    font-weight: 800;
    font-size: 11.5px;
    padding: 2px 7px;
    border-radius: 4px;
}

/* Travelers Counter Popover */
.f-pax-popup {
    width: 290px;
    right: 0;
    left: auto;
}
.f-counter-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.f-counter-ctrl {
    display: flex;
    align-items: center;
    gap: 10px;
}
.f-counter-ctrl button {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.f-counter-ctrl button:hover {
    background: #f1f5f9;
}
.f-counter-ctrl span {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    min-width: 16px;
    text-align: center;
}
.f-cabin-opt {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
}

/* Search Flights Button */
.f-search-btn {
    background: #558B2F;
    background: linear-gradient(135deg, #689F38 0%, #558B2F 100%);
    color: #ffffff;
    border: none;
    padding: 13px 40px;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(85, 139, 47, 0.35);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: transform 0.15s, box-shadow 0.15s;
}
.f-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(85, 139, 47, 0.45);
}

/* Akbar Dual-Month Calendar Dropdown for Flights */
.akbar-calendar-panel {
    width: 630px;
    max-width: 95vw;
    padding: 0;
    border-radius: 12px;
    overflow: hidden;
    position: absolute;
    top: calc(100% + 10px);
    left: 28%;
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
        left: 50%;
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
    border-bottom-color: #558B2F;
}
.akbar-cal-tab-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
}
.akbar-cal-tab.active .akbar-cal-tab-label {
    color: #558B2F;
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
    background: #558B2F;
    color: #ffffff;
    border-color: #558B2F;
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
    color: #558B2F;
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
    background: #558B2F !important;
    color: #ffffff !important;
    font-weight: 800;
    border-radius: 4px;
}
.akbar-cal-day.in-range {
    background: #dcfce7;
    color: #166534;
    border-radius: 0;
    font-weight: 700;
}
</style>

<script>
// Comprehensive Domestic & International Airports Data matching Screenshot 1 & voyogo/flight
const fAirports = [
    // Popular Domestic (India)
    { city: "Delhi", code: "DEL", airport: "Indira Gandhi Intl Airport", country: "India", popular: true },
    { city: "Mumbai", code: "BOM", airport: "Chhatrapati Shivaji Maharaj Intl", country: "India", popular: true },
    { city: "Bengaluru", code: "BLR", airport: "Kempegowda Intl Airport", country: "India", popular: true },
    { city: "Hyderabad", code: "HYD", airport: "Rajiv Gandhi Intl Airport", country: "India", popular: true },
    { city: "Chennai", code: "MAA", airport: "Chennai International Airport", country: "India", popular: true },
    { city: "Kolkata", code: "CCU", airport: "Netaji Subhash Chandra Bose Intl", country: "India", popular: true },
    { city: "Goa (Dabolim)", code: "GOI", airport: "Dabolim Airport", country: "India", popular: true },
    { city: "Goa (Mopa)", code: "GOX", airport: "Manohar International Airport", country: "India", popular: true },
    { city: "Ahmedabad", code: "AMD", airport: "Sardar Vallabhbhai Patel Intl", country: "India", popular: true },
    { city: "Kochi", code: "COK", airport: "Cochin International Airport", country: "India", popular: true },
    { city: "Pune", code: "PNQ", airport: "Pune Airport", country: "India", popular: true },

    // Popular Middle East International
    { city: "Dubai", code: "DXB", airport: "Dubai International Airport", country: "UAE", popular: true },
    { city: "Abu Dhabi", code: "AUH", airport: "Zayed International Airport", country: "UAE", popular: true },
    { city: "Sharjah", code: "SHJ", airport: "Sharjah International Airport", country: "UAE", popular: true },
    { city: "Doha", code: "DOH", airport: "Hamad International Airport", country: "Qatar", popular: true },
    { city: "Riyadh", code: "RUH", airport: "King Khalid International Airport", country: "Saudi Arabia", popular: true },
    { city: "Jeddah", code: "JED", airport: "King Abdulaziz Intl Airport", country: "Saudi Arabia", popular: true },
    { city: "Dammam", code: "DMM", airport: "King Fahd International Airport", country: "Saudi Arabia", popular: false },
    { city: "Muscat", code: "MCT", airport: "Muscat International Airport", country: "Oman", popular: true },
    { city: "Kuwait", code: "KWI", airport: "Kuwait International Airport", country: "Kuwait", popular: true },
    { city: "Bahrain", code: "BAH", airport: "Bahrain International Airport", country: "Bahrain", popular: true },

    // Popular Southeast Asia & Far East
    { city: "Singapore", code: "SIN", airport: "Singapore Changi Airport", country: "Singapore", popular: true },
    { city: "Bangkok", code: "BKK", airport: "Suvarnabhumi International Airport", country: "Thailand", popular: true },
    { city: "Bangkok (Don Mueang)", code: "DMK", airport: "Don Mueang International Airport", country: "Thailand", popular: false },
    { city: "Phuket", code: "HKT", airport: "Phuket International Airport", country: "Thailand", popular: true },
    { city: "Kuala Lumpur", code: "KUL", airport: "Kuala Lumpur Intl Airport", country: "Malaysia", popular: true },
    { city: "Bali (Denpasar)", code: "DPS", airport: "Ngurah Rai International Airport", country: "Indonesia", popular: true },
    { city: "Jakarta", code: "CGK", airport: "Soekarno-Hatta International Airport", country: "Indonesia", popular: false },
    { city: "Colombo", code: "CMB", airport: "Bandaranaike International Airport", country: "Sri Lanka", popular: true },
    { city: "Male (Maldives)", code: "MLE", airport: "Velana International Airport", country: "Maldives", popular: true },
    { city: "Kathmandu", code: "KTM", airport: "Tribhuvan International Airport", country: "Nepal", popular: true },
    { city: "Dhaka", code: "DAC", airport: "Hazrat Shahjalal Intl Airport", country: "Bangladesh", popular: false },
    { city: "Hong Kong", code: "HKG", airport: "Hong Kong International Airport", country: "Hong Kong", popular: true },
    { city: "Tokyo (Narita)", code: "NRT", airport: "Narita International Airport", country: "Japan", popular: true },
    { city: "Tokyo (Haneda)", code: "HND", airport: "Tokyo Haneda Airport", country: "Japan", popular: false },
    { city: "Osaka", code: "KIX", airport: "Kansai International Airport", country: "Japan", popular: false },
    { city: "Seoul", code: "ICN", airport: "Incheon International Airport", country: "South Korea", popular: true },
    { city: "Manila", code: "MNL", airport: "Ninoy Aquino International Airport", country: "Philippines", popular: false },
    { city: "Ho Chi Minh City", code: "SGN", airport: "Tan Son Nhat International Airport", country: "Vietnam", popular: true },
    { city: "Hanoi", code: "HAN", airport: "Noi Bai International Airport", country: "Vietnam", popular: false },

    // Popular Europe
    { city: "London (Heathrow)", code: "LHR", airport: "Heathrow Airport", country: "UK", popular: true },
    { city: "London (Gatwick)", code: "LGW", airport: "Gatwick Airport", country: "UK", popular: false },
    { city: "Manchester", code: "MAN", airport: "Manchester Airport", country: "UK", popular: false },
    { city: "Birmingham", code: "BHX", airport: "Birmingham Airport", country: "UK", popular: false },
    { city: "Paris (Charles de Gaulle)", code: "CDG", airport: "Charles de Gaulle Airport", country: "France", popular: true },
    { city: "Frankfurt", code: "FRA", airport: "Frankfurt am Main Airport", country: "Germany", popular: true },
    { city: "Munich", code: "MUC", airport: "Munich International Airport", country: "Germany", popular: false },
    { city: "Amsterdam", code: "AMS", airport: "Amsterdam Schiphol Airport", country: "Netherlands", popular: true },
    { city: "Zurich", code: "ZRH", airport: "Zurich Airport", country: "Switzerland", popular: true },
    { city: "Geneva", code: "GVA", airport: "Geneva Airport", country: "Switzerland", popular: false },
    { city: "Rome", code: "FCO", airport: "Leonardo da Vinci–Fiumicino", country: "Italy", popular: true },
    { city: "Milan", code: "MXP", airport: "Milan Malpensa Airport", country: "Italy", popular: false },
    { city: "Madrid", code: "MAD", airport: "Adolfo Suárez Madrid–Barajas", country: "Spain", popular: false },
    { city: "Barcelona", code: "BCN", airport: "Josep Tarradellas Barcelona-El Prat", country: "Spain", popular: false },
    { city: "Vienna", code: "VIE", airport: "Vienna International Airport", country: "Austria", popular: false },
    { city: "Brussels", code: "BRU", airport: "Brussels Airport", country: "Belgium", popular: false },
    { city: "Istanbul", code: "IST", airport: "Istanbul Airport", country: "Turkey", popular: true },
    { city: "Dublin", code: "DUB", airport: "Dublin Airport", country: "Ireland", popular: false },
    { city: "Copenhagen", code: "CPH", airport: "Copenhagen Airport", country: "Denmark", popular: false },
    { city: "Stockholm", code: "ARN", airport: "Stockholm Arlanda Airport", country: "Sweden", popular: false },
    { city: "Helsinki", code: "HEL", airport: "Helsinki-Vantaa Airport", country: "Finland", popular: false },
    { city: "Lisbon", code: "LIS", airport: "Humberto Delgado Airport", country: "Portugal", popular: false },
    { city: "Athens", code: "ATH", airport: "Athens International Airport", country: "Greece", popular: false },

    // Popular North America
    { city: "New York (JFK)", code: "JFK", airport: "John F. Kennedy Intl Airport", country: "USA", popular: true },
    { city: "New York (Newark)", code: "EWR", airport: "Newark Liberty Intl Airport", country: "USA", popular: false },
    { city: "San Francisco", code: "SFO", airport: "San Francisco Intl Airport", country: "USA", popular: true },
    { city: "Los Angeles", code: "LAX", airport: "Los Angeles Intl Airport", country: "USA", popular: true },
    { city: "Chicago", code: "ORD", airport: "O'Hare International Airport", country: "USA", popular: true },
    { city: "Washington", code: "IAD", airport: "Washington Dulles Intl Airport", country: "USA", popular: false },
    { city: "Dallas", code: "DFW", airport: "Dallas/Fort Worth Intl Airport", country: "USA", popular: false },
    { city: "Houston", code: "IAH", airport: "George Bush Intercontinental", country: "USA", popular: false },
    { city: "Boston", code: "BOS", airport: "Boston Logan Intl Airport", country: "USA", popular: false },
    { city: "Seattle", code: "SEA", airport: "Seattle-Tacoma Intl Airport", country: "USA", popular: false },
    { city: "Atlanta", code: "ATL", airport: "Hartsfield-Jackson Atlanta Intl", country: "USA", popular: false },
    { city: "Toronto", code: "YYZ", airport: "Toronto Pearson Intl Airport", country: "Canada", popular: true },
    { city: "Vancouver", code: "YVR", airport: "Vancouver International Airport", country: "Canada", popular: true },
    { city: "Montreal", code: "YUL", airport: "Montréal–Trudeau Intl Airport", country: "Canada", popular: false },

    // Australia, New Zealand & Africa
    { city: "Sydney", code: "SYD", airport: "Sydney Kingsford Smith Airport", country: "Australia", popular: true },
    { city: "Melbourne", code: "MEL", airport: "Melbourne Airport", country: "Australia", popular: true },
    { city: "Brisbane", code: "BNE", airport: "Brisbane Airport", country: "Australia", popular: false },
    { city: "Perth", code: "PER", airport: "Perth Airport", country: "Australia", popular: false },
    { city: "Auckland", code: "AKL", airport: "Auckland Airport", country: "New Zealand", popular: false },
    { city: "Johannesburg", code: "JNB", airport: "O. R. Tambo Intl Airport", country: "South Africa", popular: false },
    { city: "Cape Town", code: "CPT", airport: "Cape Town International Airport", country: "South Africa", popular: false },
    { city: "Nairobi", code: "NBO", airport: "Jomo Kenyatta Intl Airport", country: "Kenya", popular: false },
    { city: "Cairo", code: "CAI", airport: "Cairo International Airport", country: "Egypt", popular: false },
    { city: "Mauritius", code: "MRU", airport: "Sir Seewoosagur Ramgoolam Intl", country: "Mauritius", popular: true },

    // More Indian Cities
    { city: "Jaipur", code: "JAI", airport: "Jaipur International Airport", country: "India", popular: false },
    { city: "Lucknow", code: "LKO", airport: "Chaudhary Charan Singh Intl", country: "India", popular: false },
    { city: "Chandigarh", code: "IXC", airport: "Shaheed Bhagat Singh Intl", country: "India", popular: false },
    { city: "Srinagar", code: "SXR", airport: "Sheikh ul-Alam Intl Airport", country: "India", popular: false },
    { city: "Amritsar", code: "ATQ", airport: "Sri Guru Ram Dass Jee Intl", country: "India", popular: false },
    { city: "Varanasi", code: "VNS", airport: "Lal Bahadur Shastri Intl", country: "India", popular: false },
    { city: "Patna", code: "PAT", airport: "Jay Prakash Narayan Airport", country: "India", popular: false },
    { city: "Guwahati", code: "GAU", airport: "Lokpriya Gopinath Bordoloi Intl", country: "India", popular: false },
    { city: "Thiruvananthapuram", code: "TRV", airport: "Trivandrum International Airport", country: "India", popular: false },
    { city: "Kozhikode", code: "CCJ", airport: "Calicut International Airport", country: "India", popular: false }
];

// Dual-Month Calendar States & Variables
var fMonthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];
var fDayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
var fMonthShort = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

var rawDepartVal = document.getElementById('fDepartDateInput').value;
var rawReturnVal = document.getElementById('fReturnDateInput').value;

var fSelDepart = rawDepartVal ? new Date(rawDepartVal + 'T00:00:00') : new Date();
fSelDepart.setHours(0,0,0,0);
var fSelReturn = rawReturnVal ? new Date(rawReturnVal + 'T00:00:00') : new Date(fSelDepart.getTime() + 4 * 86400000);
fSelReturn.setHours(0,0,0,0);

var fCalActiveTab = 'depart';
var fCalViewYear = fSelDepart.getFullYear();
var fCalViewMonth = fSelDepart.getMonth();

function closeAllFranchiseDropdowns() {
    var fromDrop = document.getElementById('fFromDropdown');
    var toDrop   = document.getElementById('fToDropdown');
    var paxDrop  = document.getElementById('fPaxDropdown');
    var calDrop  = document.getElementById('flightCalendarDropdown');
    if (fromDrop) fromDrop.classList.remove('open');
    if (toDrop)   toDrop.classList.remove('open');
    if (paxDrop)  paxDrop.classList.remove('open');
    if (calDrop)  calDrop.classList.remove('open', 'show');
}

document.addEventListener('click', function(e) {
    const fromBox = document.getElementById('fFromBox');
    const toBox = document.getElementById('fToBox');
    const paxBox = document.getElementById('fPaxBox');
    const departBox = document.getElementById('fDepartBox');
    const returnBox = document.getElementById('fReturnBox');
    const calDrop = document.getElementById('flightCalendarDropdown');

    if ((fromBox && fromBox.contains(e.target)) ||
        (toBox && toBox.contains(e.target)) ||
        (paxBox && paxBox.contains(e.target)) ||
        (departBox && departBox.contains(e.target)) ||
        (returnBox && returnBox.contains(e.target)) ||
        (calDrop && calDrop.contains(e.target))) {
        return;
    }
    closeAllFranchiseDropdowns();
});

function openAirportDropdown(e, type) {
    e.stopPropagation();
    closeAllFranchiseDropdowns();
    const dropdown = document.getElementById(type === 'from' ? 'fFromDropdown' : 'fToDropdown');
    dropdown.classList.add('open');
    renderPills(type);
    renderAirportList(type, '');
    const searchInp = document.getElementById(type === 'from' ? 'fFromSearchInput' : 'fToSearchInput');
    searchInp.value = '';
    setTimeout(() => searchInp.focus(), 100);
}

function renderPills(type) {
    const container = document.getElementById(type === 'from' ? 'fFromPills' : 'fToPills');
    container.innerHTML = '';
    const popular = fAirports.filter(a => a.popular);
    popular.forEach(item => {
        const pill = document.createElement('span');
        pill.className = 'f-dest-pill';
        pill.innerText = item.city + ' (' + item.code + ')';
        pill.onclick = function(e) {
            e.stopPropagation();
            selectAirport(type, item);
        };
        container.appendChild(pill);
    });
}

function renderAirportList(type, query) {
    const container = document.getElementById(type === 'from' ? 'fFromList' : 'fToList');
    container.innerHTML = '';
    const q = query.trim().toLowerCase();
    const filtered = fAirports.filter(a => {
        return a.city.toLowerCase().includes(q) || 
               a.code.toLowerCase().includes(q) || 
               a.airport.toLowerCase().includes(q) ||
               (a.country && a.country.toLowerCase().includes(q));
    });

    if (filtered.length === 0) {
        container.innerHTML = '<div style="padding: 12px; text-align: center; color: #94a3b8; font-size: 12px;">No matching airports found</div>';
        return;
    }

    filtered.forEach(item => {
        const div = document.createElement('div');
        div.className = 'f-airport-item';
        div.innerHTML = `<div>
            <div style="font-weight: 700; font-size: 13px; color: #0f172a;">${item.city} <span style="font-weight: 500; font-size: 11px; color: #64748b;">${item.country ? '· ' + item.country : ''}</span></div>
            <div style="font-size: 11px; color: #64748b;">${item.airport}</div>
        </div>
        <span class="f-airport-code">${item.code}</span>`;
        div.onclick = function(e) {
            e.stopPropagation();
            selectAirport(type, item);
        };
        container.appendChild(div);
    });
}

function filterAirports(type, val) {
    renderAirportList(type, val);
}

function selectAirport(type, item) {
    const label = item.city + ' (' + item.code + ')';
    if (type === 'from') {
        document.getElementById('fFromCityText').innerText = label;
        document.getElementById('fFromCityInput').value = label;
        document.getElementById('fFromAirportText').innerText = item.airport;
    } else {
        document.getElementById('fToCityText').innerText = label;
        document.getElementById('fToCityInput').value = label;
        document.getElementById('fToAirportText').innerText = item.airport;
    }
    closeAllFranchiseDropdowns();
}

function swapFromTo(e) {
    e.stopPropagation();
    const fromCity = document.getElementById('fFromCityText').innerText;
    const fromVal  = document.getElementById('fFromCityInput').value;
    const fromSub  = document.getElementById('fFromAirportText').innerText;

    const toCity   = document.getElementById('fToCityText').innerText;
    const toVal    = document.getElementById('fToCityInput').value;
    const toSub    = document.getElementById('fToAirportText').innerText;

    document.getElementById('fFromCityText').innerText = toCity;
    document.getElementById('fFromCityInput').value = toVal;
    document.getElementById('fFromAirportText').innerText = toSub;

    document.getElementById('fToCityText').innerText = fromCity;
    document.getElementById('fToCityInput').value = fromVal;
    document.getElementById('fToAirportText').innerText = fromSub;
}

// Akbar Dual-Month Calendar Logic for Flight Search
function openFlightCalendar(e, tab) {
    e.stopPropagation();
    closeAllFranchiseDropdowns();

    // If clicking return while oneway is active, automatically activate roundtrip
    var returnInput = document.getElementById('fReturnDateInput');
    if (tab === 'return' && returnInput.disabled) {
        document.getElementById('lblRoundTrip').click();
    }

    switchFlightCalTab(tab);
    var cal = document.getElementById('flightCalendarDropdown');
    if (cal) cal.classList.add('open', 'show');
}

function formatISO(d) {
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
}

function formatTabDateStr(d) {
    var monNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return monNames[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
}

function updateFlightDateDisplays() {
    var depISO = formatISO(fSelDepart);
    var retISO = formatISO(fSelReturn);

    document.getElementById('fDepartDateInput').value = depISO;
    document.getElementById('fReturnDateInput').value = retISO;

    // Depart card display
    var ddIn = String(fSelDepart.getDate()).padStart(2, '0');
    var mmIn = String(fSelDepart.getMonth() + 1).padStart(2, '0');
    var yyyyIn = fSelDepart.getFullYear();
    document.getElementById('fDepartMainDate').innerText = `${ddIn}/${mmIn}/${yyyyIn}`;
    document.getElementById('fDepartSubDate').innerText = `${fDayNames[fSelDepart.getDay()]}, ${fSelDepart.getDate()} ${fMonthShort[fSelDepart.getMonth()]} ${yyyyIn}`;

    // Return card display
    var ddOut = String(fSelReturn.getDate()).padStart(2, '0');
    var mmOut = String(fSelReturn.getMonth() + 1).padStart(2, '0');
    var yyyyOut = fSelReturn.getFullYear();
    document.getElementById('fReturnMainDate').innerText = `${ddOut}/${mmOut}/${yyyyOut}`;

    var isRoundTrip = !document.getElementById('fReturnDateInput').disabled;
    if (isRoundTrip) {
        document.getElementById('fReturnSubDate').innerText = `${fDayNames[fSelReturn.getDay()]}, ${fSelReturn.getDate()} ${fMonthShort[fSelReturn.getMonth()]} ${yyyyOut}`;
    }

    // Tabs display
    document.getElementById('fFlightTabDepartVal').textContent = formatTabDateStr(fSelDepart);
    document.getElementById('fFlightTabReturnVal').textContent = formatTabDateStr(fSelReturn);
}

function switchFlightCalTab(tab) {
    fCalActiveTab = tab;
    var tabDep = document.getElementById('fFlightTabDepart');
    var tabRet = document.getElementById('fFlightTabReturn');
    if (tab === 'depart') {
        if (tabDep) tabDep.classList.add('active');
        if (tabRet) tabRet.classList.remove('active');
        fCalViewYear = fSelDepart.getFullYear();
        fCalViewMonth = fSelDepart.getMonth();
    } else {
        if (tabRet) tabRet.classList.add('active');
        if (tabDep) tabDep.classList.remove('active');
        fCalViewYear = fSelReturn.getFullYear();
        fCalViewMonth = fSelReturn.getMonth();
    }
    renderFlightCalendarMonths();
}

function navigateFlightCal(dir) {
    fCalViewMonth += dir;
    if (fCalViewMonth < 0) {
        fCalViewMonth = 11;
        fCalViewYear--;
    } else if (fCalViewMonth > 11) {
        fCalViewMonth = 0;
        fCalViewYear++;
    }
    renderFlightCalendarMonths();
}

function renderFlightMonthGrid(year, month, container) {
    container.innerHTML = '';
    var today = new Date();
    today.setHours(0,0,0,0);

    var firstDayIndex = new Date(year, month, 1).getDay();
    var daysInMonth = new Date(year, month + 1, 0).getDate();

    // 1. Padding days
    for (var i = 0; i < firstDayIndex; i++) {
        var emptyCell = document.createElement('div');
        emptyCell.className = 'akbar-cal-day disabled';
        container.appendChild(emptyCell);
    }

    // 2. Real days
    var isRoundTrip = !document.getElementById('fReturnDateInput').disabled;
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
            var depTime = fSelDepart.getTime();
            var retTime = fSelReturn.getTime();

            if (dTime === depTime) {
                cell.classList.add('selected-in');
            } else if (isRoundTrip && dTime === retTime) {
                cell.classList.add('selected-out');
            } else if (isRoundTrip && dTime > depTime && dTime < retTime) {
                cell.classList.add('in-range');
            }

            (function(selectedDate) {
                cell.addEventListener('click', function(e) {
                    e.stopPropagation();
                    handleFlightDateSelection(selectedDate);
                });
            })(dateObj);
        }

        container.appendChild(cell);
    }
}

function handleFlightDateSelection(chosenDate) {
    var isRoundTrip = !document.getElementById('fReturnDateInput').disabled;

    if (fCalActiveTab === 'depart') {
        fSelDepart = new Date(chosenDate);
        if (isRoundTrip) {
            if (fSelReturn.getTime() <= fSelDepart.getTime()) {
                fSelReturn = new Date(fSelDepart.getTime() + 4 * 86400000);
            }
            updateFlightDateDisplays();
            switchFlightCalTab('return');
        } else {
            updateFlightDateDisplays();
            renderFlightCalendarMonths();
            setTimeout(function() {
                closeAllFranchiseDropdowns();
            }, 250);
        }
    } else {
        // Return tab selection
        if (chosenDate.getTime() < fSelDepart.getTime()) {
            fSelDepart = new Date(chosenDate);
            fSelReturn = new Date(fSelDepart.getTime() + 4 * 86400000);
            updateFlightDateDisplays();
            switchFlightCalTab('return');
        } else {
            fSelReturn = new Date(chosenDate);
            updateFlightDateDisplays();
            renderFlightCalendarMonths();
            setTimeout(function() {
                closeAllFranchiseDropdowns();
            }, 250);
        }
    }
}

function renderFlightCalendarMonths() {
    var m1Year = fCalViewYear;
    var m1Month = fCalViewMonth;

    var m2Year = m1Month === 11 ? m1Year + 1 : m1Year;
    var m2Month = m1Month === 11 ? 0 : m1Month + 1;

    var t1 = document.getElementById('fFlightCalMonth1Title');
    var t2 = document.getElementById('fFlightCalMonth2Title');
    if (t1) t1.textContent = fMonthNames[m1Month] + ' ' + m1Year;
    if (t2) t2.textContent = fMonthNames[m2Month] + ' ' + m2Year;

    var realToday = new Date();
    var curYear = realToday.getFullYear();
    var curMonth = realToday.getMonth();
    var prevBtn = document.getElementById('fFlightCalPrevBtn');
    if (prevBtn) {
        prevBtn.disabled = (m1Year < curYear || (m1Year === curYear && m1Month <= curMonth));
    }

    var grid1 = document.getElementById('fFlightCalDays1');
    var grid2 = document.getElementById('fFlightCalDays2');
    if (grid1) renderFlightMonthGrid(m1Year, m1Month, grid1);
    if (grid2) renderFlightMonthGrid(m2Year, m2Month, grid2);
}

function handleTripTypeChange(type) {
    document.querySelectorAll('.f-radio-label').forEach(l => l.classList.remove('active'));
    if (type === 'oneway') {
        document.getElementById('lblOneWay').classList.add('active');
        document.getElementById('fReturnBox').classList.add('disabled');
        document.getElementById('fReturnDateInput').disabled = true;
        document.getElementById('fReturnSubDate').innerText = 'Save up to 20% on round trips';
    } else if (type === 'roundtrip') {
        document.getElementById('lblRoundTrip').classList.add('active');
        document.getElementById('fReturnBox').classList.remove('disabled');
        document.getElementById('fReturnDateInput').disabled = false;
        updateFlightDateDisplays();
    } else {
        document.getElementById('lblMultiCity').classList.add('active');
        alert('Multi-City search is available for corporate bookings. Defaulting to Round Trip.');
        document.getElementById('lblRoundTrip').click();
    }
    renderFlightCalendarMonths();
}

function selectFareTag(elem) {
    document.querySelectorAll('.f-fare-pill').forEach(p => p.classList.remove('active'));
    elem.classList.add('active');
}

// Travelers Counter
let paxCounts = { adult: 1, child: 0, infant: 0 };
let currentCabinName = 'Economy';

function openPaxDropdown(e) {
    e.stopPropagation();
    closeAllFranchiseDropdowns();
    document.getElementById('fPaxDropdown').classList.add('open');
}

function adjustPax(type, delta) {
    if (type === 'adult') {
        paxCounts.adult = Math.max(1, Math.min(9, paxCounts.adult + delta));
        document.getElementById('fCountAdult').innerText = paxCounts.adult;
        document.getElementById('fHiddenAdults').value = paxCounts.adult;
    } else if (type === 'child') {
        paxCounts.child = Math.max(0, Math.min(6, paxCounts.child + delta));
        document.getElementById('fCountChild').innerText = paxCounts.child;
        document.getElementById('fHiddenChildren').value = paxCounts.child;
    } else if (type === 'infant') {
        paxCounts.infant = Math.max(0, Math.min(paxCounts.adult, paxCounts.infant + delta));
        document.getElementById('fCountInfant').innerText = paxCounts.infant;
        document.getElementById('fHiddenInfants').value = paxCounts.infant;
    }
    updatePaxSummary();
}

function setFranchiseCabin(val, name) {
    document.getElementById('fHiddenCabin').value = val;
    currentCabinName = name;
    updatePaxSummary();
}

function updatePaxSummary() {
    const total = paxCounts.adult + paxCounts.child + paxCounts.infant;
    const txt = `${total} Traveler${total > 1 ? 's' : ''}, ${currentCabinName}`;
    document.getElementById('fPaxMainText').innerText = txt;
}

// Initial flight calendar setup
updateFlightDateDisplays();
renderFlightCalendarMonths();
</script>
