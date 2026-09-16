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

                    <!-- DEPARTURE Date Box -->
                    <div class="f-input-box" onclick="document.getElementById('fDepartDateInput').showPicker ? document.getElementById('fDepartDateInput').showPicker() : document.getElementById('fDepartDateInput').focus()">
                        <div class="f-input-lbl"><i class="fa-regular fa-calendar-days"></i> DEPARTURE</div>
                        <div class="f-input-main" id="fDepartMainDate"><?php echo date('d/m/Y', strtotime('+3 days')); ?></div>
                        <div class="f-input-sub" id="fDepartSubDate"><?php echo date('D, d M Y', strtotime('+3 days')); ?></div>
                        <input type="date" name="depart_date" id="fDepartDateInput" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" min="<?php echo date('Y-m-d'); ?>" onchange="updateDepartDisplay(this.value)" style="position: absolute; opacity: 0; width: 0; height: 0;">
                    </div>

                    <!-- RETURN Date Box -->
                    <div class="f-input-box disabled" id="fReturnBox" onclick="triggerReturnPicker()">
                        <div class="f-input-lbl"><i class="fa-regular fa-calendar-days"></i> RETURN</div>
                        <div class="f-input-main" id="fReturnMainDate"><?php echo date('d/m/Y', strtotime('+7 days')); ?></div>
                        <div class="f-input-sub" id="fReturnSubDate">Save up to 20% on round trips</div>
                        <input type="date" name="return_date" id="fReturnDateInput" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" min="<?php echo date('Y-m-d'); ?>" onchange="updateReturnDisplay(this.value)" disabled style="position: absolute; opacity: 0; width: 0; height: 0;">
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
</style>

<script>
// Comprehensive Airports Data matching 1st Screenshot
const fAirports = [
    { city: "Delhi", code: "DEL", airport: "Indira Gandhi Intl Airport", popular: true },
    { city: "Mumbai", code: "BOM", airport: "Chhatrapati Shivaji Maharaj Intl", popular: true },
    { city: "Bengaluru", code: "BLR", airport: "Kempegowda International Airport", popular: true },
    { city: "Hyderabad", code: "HYD", airport: "Rajiv Gandhi International Airport", popular: true },
    { city: "Chennai", code: "MAA", airport: "Chennai International Airport", popular: true },
    { city: "Kolkata", code: "CCU", airport: "Netaji Subhash Chandra Bose Intl", popular: true },
    { city: "Goa (Dabolim)", code: "GOI", airport: "Dabolim Airport", popular: true },
    { city: "Goa (Mopa)", code: "GOX", airport: "Manohar International Airport", popular: true },
    { city: "Ahmedabad", code: "AMD", airport: "Sardar Vallabhbhai Patel Intl", popular: true },
    { city: "Kochi", code: "COK", airport: "Cochin International Airport", popular: true },
    { city: "Pune", code: "PNQ", airport: "Pune Airport", popular: true },
    { city: "Dubai", code: "DXB", airport: "Dubai International Airport", popular: true },
    { city: "Abu Dhabi", code: "AUH", airport: "Zayed International Airport", popular: true },
    { city: "Sharjah", code: "SHJ", airport: "Sharjah International Airport", popular: true },
    { city: "Doha", code: "DOH", airport: "Hamad International Airport", popular: true },
    { city: "Riyadh", code: "RUH", airport: "King Khalid International Airport", popular: true },
    { city: "Jeddah", code: "JED", airport: "King Abdulaziz International Airport", popular: true },
    { city: "Muscat", code: "MCT", airport: "Muscat International Airport", popular: true },
    { city: "Kuwait", code: "KWI", airport: "Kuwait International Airport", popular: true },
    { city: "Bahrain", code: "BAH", airport: "Bahrain International Airport", popular: true },
    { city: "Singapore", code: "SIN", airport: "Changi Airport", popular: true },
    { city: "Bangkok", code: "BKK", airport: "Suvarnabhumi Airport", popular: true },
    { city: "Phuket", code: "HKT", airport: "Phuket International Airport", popular: true },
    { city: "Kuala Lumpur", code: "KUL", airport: "Kuala Lumpur International Airport", popular: true },
    { city: "Bali (Denpasar)", code: "DPS", airport: "Ngurah Rai International Airport", popular: true },
    { city: "Colombo", code: "CMB", airport: "Bandaranaike International Airport", popular: true },
    { city: "Male (Maldives)", code: "MLE", airport: "Velana International Airport", popular: true },
    { city: "Kathmandu", code: "KTM", airport: "Tribhuvan International Airport", popular: true },
    { city: "Hong Kong", code: "HKG", airport: "Hong Kong International Airport", popular: true },
    { city: "Tokyo (Narita)", code: "NRT", airport: "Narita International Airport", popular: true },
    { city: "Seoul (ICN)", code: "ICN", airport: "Incheon International Airport", popular: true },
    { city: "London (Heathrow)", code: "LHR", airport: "Heathrow Airport", popular: true },
    { city: "Paris (CDG)", code: "CDG", airport: "Charles de Gaulle Airport", popular: true },
    { city: "Frankfurt", code: "FRA", airport: "Frankfurt Airport", popular: true },
    { city: "Amsterdam", code: "AMS", airport: "Schiphol Airport", popular: true },
    { city: "New York (JFK)", code: "JFK", airport: "John F. Kennedy Intl Airport", popular: true }
];

function closeAllFranchiseDropdowns() {
    document.getElementById('fFromDropdown').classList.remove('open');
    document.getElementById('fToDropdown').classList.remove('open');
    document.getElementById('fPaxDropdown').classList.remove('open');
}

document.addEventListener('click', function(e) {
    const fromBox = document.getElementById('fFromBox');
    const toBox = document.getElementById('fToBox');
    const paxBox = document.getElementById('fPaxBox');
    if ((fromBox && fromBox.contains(e.target)) || (toBox && toBox.contains(e.target)) || (paxBox && paxBox.contains(e.target))) {
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
        return a.city.toLowerCase().includes(q) || a.code.toLowerCase().includes(q) || a.airport.toLowerCase().includes(q);
    });

    if (filtered.length === 0) {
        container.innerHTML = '<div style="padding: 12px; text-align: center; color: #94a3b8; font-size: 12px;">No matching airports found</div>';
        return;
    }

    filtered.forEach(item => {
        const div = document.createElement('div');
        div.className = 'f-airport-item';
        div.innerHTML = `<div>
            <div style="font-weight: 700; font-size: 13px; color: #0f172a;">${item.city}</div>
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

// Date helpers
function updateDepartDisplay(val) {
    if (!val) return;
    const d = new Date(val);
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dd = String(d.getDate()).padStart(2, '0');
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const yyyy = d.getFullYear();
    document.getElementById('fDepartMainDate').innerText = `${dd}/${mm}/${yyyy}`;
    document.getElementById('fDepartSubDate').innerText = `${dayNames[d.getDay()]}, ${d.getDate()} ${monthNames[d.getMonth()]} ${yyyy}`;
}

function updateReturnDisplay(val) {
    if (!val) return;
    const d = new Date(val);
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dd = String(d.getDate()).padStart(2, '0');
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const yyyy = d.getFullYear();
    document.getElementById('fReturnMainDate').innerText = `${dd}/${mm}/${yyyy}`;
    document.getElementById('fReturnSubDate').innerText = `${dayNames[d.getDay()]}, ${d.getDate()} ${monthNames[d.getMonth()]} ${yyyy}`;
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
        updateReturnDisplay(document.getElementById('fReturnDateInput').value);
    } else {
        document.getElementById('lblMultiCity').classList.add('active');
        alert('Multi-City search is available for corporate bookings. Defaulting to Round Trip.');
        document.getElementById('lblRoundTrip').click();
    }
}

function triggerReturnPicker() {
    const input = document.getElementById('fReturnDateInput');
    if (input.disabled) {
        document.getElementById('lblRoundTrip').click();
    }
    input.showPicker ? input.showPicker() : input.focus();
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
</script>
