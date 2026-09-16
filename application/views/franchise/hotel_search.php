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
                                <div class="ak-crosshair-icon" title="Select Destination">
                                    <i class="fa-solid fa-crosshairs"></i>
                                </div>
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

                        <!-- 2. Check In Date Column -->
                        <div class="ak-search-col" id="colCheckin" onclick="openHotelDatePicker('in')" style="flex: 1.25;">
                            <div class="ak-col-label">
                                <span>CHECK IN</span> <i class="fa-solid fa-chevron-down ak-chevron"></i>
                            </div>
                            <div class="ak-date-main-row">
                                <span class="ak-date-num" id="hotelCheckinNum"><?php echo date('d', strtotime($defaultCheckin)); ?></span>
                                <span class="ak-date-mon" id="hotelCheckinMon"><?php echo date("M'y", strtotime($defaultCheckin)); ?></span>
                            </div>
                            <div class="ak-date-day" id="hotelCheckinDay"><?php echo date('l', strtotime($defaultCheckin)); ?></div>
                            <input type="date" name="checkin" id="hotelCheckinInput" value="<?php echo $defaultCheckin; ?>" min="<?php echo date('Y-m-d'); ?>" onchange="updateHotelCheckin(this.value)" style="position: absolute; opacity: 0; width: 0; height: 0;">
                        </div>

                        <!-- Floating Nights Badge Divider matching Screenshot 3 -->
                        <div class="ak-nights-divider">
                            <div class="ak-nights-pill">
                                <span id="hotelNightsCount"><?php echo $defaultNights; ?></span> NIGHTS
                            </div>
                        </div>

                        <!-- 3. Check Out Date Column -->
                        <div class="ak-search-col" id="colCheckout" onclick="openHotelDatePicker('out')" style="flex: 1.25; padding-left: 36px;">
                            <div class="ak-col-label">
                                <span>CHECK OUT</span> <i class="fa-solid fa-chevron-down ak-chevron"></i>
                            </div>
                            <div class="ak-date-main-row">
                                <span class="ak-date-num" id="hotelCheckoutNum"><?php echo date('d', strtotime($defaultCheckout)); ?></span>
                                <span class="ak-date-mon" id="hotelCheckoutMon"><?php echo date("M'y", strtotime($defaultCheckout)); ?></span>
                            </div>
                            <div class="ak-date-day" id="hotelCheckoutDay"><?php echo date('l', strtotime($defaultCheckout)); ?></div>
                            <input type="date" name="checkout" id="hotelCheckoutInput" value="<?php echo $defaultCheckout; ?>" min="<?php echo date('Y-m-d'); ?>" onchange="updateHotelCheckout(this.value)" style="position: absolute; opacity: 0; width: 0; height: 0;">
                        </div>

                        <!-- 4. Rooms & Guests Column matching Screenshot 3 -->
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

                            <!-- Guests Counter Popover -->
                            <div class="ak-guests-popup" id="hotelGuestsPopup" onclick="event.stopPropagation()">
                                <div class="ak-counter-item">
                                    <div>
                                        <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Rooms</div>
                                        <div style="font-size: 11px; color: #64748b;">Max 4 rooms</div>
                                    </div>
                                    <div class="ak-counter-btn-group">
                                        <button type="button" onclick="adjustHotelRooms(-1)">-</button>
                                        <span id="hotelRoomsCount">2</span>
                                        <button type="button" onclick="adjustHotelRooms(1)">+</button>
                                    </div>
                                </div>
                                <div class="ak-counter-item">
                                    <div>
                                        <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Adults</div>
                                        <div style="font-size: 11px; color: #64748b;">12+ years</div>
                                    </div>
                                    <div class="ak-counter-btn-group">
                                        <button type="button" onclick="adjustHotelAdults(-1)">-</button>
                                        <span id="hotelAdultsCount">4</span>
                                        <button type="button" onclick="adjustHotelAdults(1)">+</button>
                                    </div>
                                </div>
                                <div class="ak-counter-item">
                                    <div>
                                        <div style="font-weight: 700; font-size: 14px; color: #0f172a;">Children</div>
                                        <div style="font-size: 11px; color: #64748b;">0 - 11 years</div>
                                    </div>
                                    <div class="ak-counter-btn-group">
                                        <button type="button" onclick="adjustHotelChildren(-1)">-</button>
                                        <span id="hotelChildrenCount">0</span>
                                        <button type="button" onclick="adjustHotelChildren(1)">+</button>
                                    </div>
                                </div>
                                <div style="text-align: right; margin-top: 14px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                                    <button type="button" onclick="closeAllHotelDropdowns()" style="background: #eb2027; color: #ffffff; border: none; padding: 7px 20px; border-radius: 6px; font-size: 12.5px; font-weight: 800; cursor: pointer;">Done</button>
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

/* Guests Popup */
.ak-guests-popup {
    width: 280px;
    padding: 18px;
    right: 0;
    left: auto;
}
.ak-counter-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.ak-counter-btn-group {
    display: flex;
    align-items: center;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    overflow: hidden;
}
.ak-counter-btn-group button {
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
}
.ak-counter-btn-group button:hover {
    background: #f1f5f9;
}
.ak-counter-btn-group span {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    min-width: 28px;
    text-align: center;
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

function closeAllHotelDropdowns() {
    document.getElementById('hotelDestDropdown').classList.remove('open');
    document.getElementById('hotelGuestsPopup').classList.remove('open');
}

document.addEventListener('click', function(e) {
    const colDest = document.getElementById('colDest');
    const colGuests = document.getElementById('colGuests');
    if ((colDest && colDest.contains(e.target)) || (colGuests && colGuests.contains(e.target))) {
        return;
    }
    closeAllHotelDropdowns();
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

function openHotelDatePicker(type) {
    const input = document.getElementById(type === 'in' ? 'hotelCheckinInput' : 'hotelCheckoutInput');
    input.showPicker ? input.showPicker() : input.focus();
}

function updateHotelCheckin(val) {
    if (!val) return;
    const d = new Date(val);
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = ["Jan'y", "Feb'y", "Mar'y", "Apr'y", "May'y", "Jun'y", "Jul'y", "Aug'y", "Sep'y", "Oct'y", "Nov'y", "Dec'y"];
    const yy = String(d.getFullYear()).slice(-2);
    const monStr = monthNames[d.getMonth()].replace("'y", "'" + yy);

    document.getElementById('hotelCheckinNum').innerText = String(d.getDate()).padStart(2, '0');
    document.getElementById('hotelCheckinMon').innerText = monStr;
    document.getElementById('hotelCheckinDay').innerText = dayNames[d.getDay()];

    calcNights();
}

function updateHotelCheckout(val) {
    if (!val) return;
    const d = new Date(val);
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = ["Jan'y", "Feb'y", "Mar'y", "Apr'y", "May'y", "Jun'y", "Jul'y", "Aug'y", "Sep'y", "Oct'y", "Nov'y", "Dec'y"];
    const yy = String(d.getFullYear()).slice(-2);
    const monStr = monthNames[d.getMonth()].replace("'y", "'" + yy);

    document.getElementById('hotelCheckoutNum').innerText = String(d.getDate()).padStart(2, '0');
    document.getElementById('hotelCheckoutMon').innerText = monStr;
    document.getElementById('hotelCheckoutDay').innerText = dayNames[d.getDay()];

    calcNights();
}

function calcNights() {
    const cin = new Date(document.getElementById('hotelCheckinInput').value);
    const cout = new Date(document.getElementById('hotelCheckoutInput').value);
    const diffTime = Math.abs(cout - cin);
    const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
    document.getElementById('hotelNightsCount').innerText = diffDays;
}

// Rooms & Guests Handlers
let hotelRooms = 2;
let hotelAdults = 4;
let hotelChildren = 0;

function openHotelGuestDropdown(e) {
    e.stopPropagation();
    closeAllHotelDropdowns();
    document.getElementById('hotelGuestsPopup').classList.add('open');
}

function adjustHotelRooms(delta) {
    hotelRooms = Math.max(1, Math.min(4, hotelRooms + delta));
    if (hotelAdults < hotelRooms) {
        hotelAdults = hotelRooms;
        document.getElementById('hotelAdultsCount').innerText = hotelAdults;
    }
    document.getElementById('hotelRoomsCount').innerText = hotelRooms;
    updateHotelGuestSummary();
}

function adjustHotelAdults(delta) {
    hotelAdults = Math.max(hotelRooms, Math.min(12, hotelAdults + delta));
    document.getElementById('hotelAdultsCount').innerText = hotelAdults;
    updateHotelGuestSummary();
}

function adjustHotelChildren(delta) {
    hotelChildren = Math.max(0, Math.min(6, hotelChildren + delta));
    document.getElementById('hotelChildrenCount').innerText = hotelChildren;
    updateHotelGuestSummary();
}

function updateHotelGuestSummary() {
    const totalGuests = hotelAdults + hotelChildren;
    document.getElementById('hotelRoomsDisplay').innerText = hotelRooms;
    document.getElementById('hotelGuestsDisplay').innerText = totalGuests;
    document.getElementById('hotelGuestSub').innerText = `${hotelAdults} Adults, ${hotelChildren} Children`;

    document.getElementById('hotelHiddenRooms').value = hotelRooms;
    document.getElementById('hotelHiddenAdults').value = hotelAdults;
    document.getElementById('hotelHiddenChildren').value = hotelChildren;
}
</script>
