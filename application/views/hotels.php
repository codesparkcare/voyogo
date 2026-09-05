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
                <div class="akbar-search-col" id="akbarDestCol" style="flex: 2.2;">
                    <div class="akbar-col-label">
                        <span>ENTER YOUR DESTINATION OR PROPERTY</span>
                    </div>
                    <div class="akbar-col-value" id="akbarDestDisplay">Enter City/Hotel/Area/building</div>
                    <input type="hidden" name="city" id="akbarCityInput" value="Goa, India">

                    <!-- Destination Autocomplete Dropdown -->
                    <div class="akbar-dropdown-panel" id="akbarDestDropdown" style="width: 360px; padding: 14px;" onclick="event.stopPropagation();">
                        <div style="position: relative; margin-bottom: 12px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 12px; color: #64748b; font-size: 13px;"></i>
                            <input type="text" id="akbarDestSearchInput" placeholder="Type your destination (e.g. Goa, Mumbai, Dubai)..." style="width: 100%; padding: 10px 12px 10px 34px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-weight: 600; outline: none;">
                        </div>
                        
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Popular Hotel Destinations</div>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px;" id="akbarPopularPills">
                            <span class="ak-pill" onclick="selectAkbarCity('Goa, India', 'Popular: Baga, Calangute, Panjim')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Goa</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Mumbai, India', 'Popular: Marine Drive, Juhu, Bandra')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Mumbai</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Delhi NCR, India', 'Popular: Connaught Place, Aerocity')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Delhi NCR</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Dubai, UAE', 'Popular: Downtown, Marina, Palm')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Dubai</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Jaipur, India', 'Popular: Pink City, Amer, Mansarovar')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Jaipur</span>
                            <span class="ak-pill" onclick="selectAkbarCity('Maldives', 'Popular: Male, Maafushi, Overwater')" style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0d3470; cursor: pointer;">Maldives</span>
                        </div>

                        <div id="akbarDestList" style="max-height: 200px; overflow-y: auto; border: 1px solid #f1f5f9; border-radius: 6px;">
                            <!-- Populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- 2. Check In Date -->
                <div class="akbar-search-col" id="akbarCheckinCol" style="flex: 1.2;">
                    <div class="akbar-col-label">
                        <span>CHECK IN</span> <i class="fa-solid fa-chevron-down" style="font-size: 9px; color: #64748b;"></i>
                    </div>
                    <div class="akbar-date-display">
                        <span class="akbar-date-num" id="akbarCheckinNum"><?php echo date('d', strtotime($defaultCheckin)); ?></span>
                        <span class="akbar-date-month" id="akbarCheckinMon"><?php echo date("M'y", strtotime($defaultCheckin)); ?></span>
                    </div>
                    <div class="akbar-date-day" id="akbarCheckinDay"><?php echo date('l', strtotime($defaultCheckin)); ?></div>
                    <input type="date" id="akbarCheckinInput" name="checkin_date" value="<?php echo $defaultCheckin; ?>" style="position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; cursor: pointer;">
                </div>

                <!-- Floating Nights Divider Badge -->
                <div class="akbar-nights-divider">
                    <div class="akbar-nights-pill">
                        <span id="akbarNightsVal"><?php echo $defaultNights; ?></span> NIGHTS
                    </div>
                </div>

                <!-- 3. Check Out Date -->
                <div class="akbar-search-col" id="akbarCheckoutCol" style="flex: 1.2; padding-left: 24px;">
                    <div class="akbar-col-label">
                        <span>CHECK OUT</span> <i class="fa-solid fa-chevron-down" style="font-size: 9px; color: #64748b;"></i>
                    </div>
                    <div class="akbar-date-display">
                        <span class="akbar-date-num" id="akbarCheckoutNum"><?php echo date('d', strtotime($defaultCheckout)); ?></span>
                        <span class="akbar-date-month" id="akbarCheckoutMon"><?php echo date("M'y", strtotime($defaultCheckout)); ?></span>
                    </div>
                    <div class="akbar-date-day" id="akbarCheckoutDay"><?php echo date('l', strtotime($defaultCheckout)); ?></div>
                    <input type="date" id="akbarCheckoutInput" name="checkout_date" value="<?php echo $defaultCheckout; ?>" style="position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; cursor: pointer;">
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

    var guestsCol      = document.getElementById('akbarGuestsCol');
    var guestsDropdown = document.getElementById('akbarGuestsDropdown');

    // 1. Destination Dropdown Logic
    var cities = [
        { name: "Goa, India", sub: "Popular: Baga Beach, Calangute, Panjim, Candolim" },
        { name: "Mumbai, India", sub: "Popular: Marine Drive, Juhu, Bandra, Andheri" },
        { name: "Delhi NCR, India", sub: "Popular: Connaught Place, South Delhi, Aerocity" },
        { name: "Dubai, UAE", sub: "Popular: Downtown, Dubai Marina, Palm Jumeirah" },
        { name: "Jaipur, India", sub: "Popular: Pink City, Amer, Mansarovar, MI Road" },
        { name: "Maldives", sub: "Popular: Male, Maafushi, Overwater Villas" },
        { name: "Bengaluru, India", sub: "Popular: MG Road, Indiranagar, Whitefield" },
        { name: "Bangkok, Thailand", sub: "Popular: Sukhumvit, Siam, Silom, Riverside" },
        { name: "Singapore", sub: "Popular: Marina Bay, Orchard Road, Sentosa" },
        { name: "Udaipur, India", sub: "Popular: Lake Pichola, Fatehsagar, City Palace" }
    ];

    function renderCities(filter) {
        var query = (filter || '').toLowerCase().trim();
        var html = '';
        var matches = cities.filter(function(c) {
            return c.name.toLowerCase().indexOf(query) !== -1 || c.sub.toLowerCase().indexOf(query) !== -1;
        });

        if (matches.length === 0) {
            html = '<div style="padding: 12px; font-size: 13px; color: #64748b; text-align: center;">No destinations found matching "' + query + '"</div>';
        } else {
            matches.forEach(function(c) {
                html += '<div onclick="selectAkbarCity(\'' + c.name + '\', \'' + c.sub + '\')" style="padding: 10px 14px; border-bottom: 1px solid #f8fafc; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: background 0.1s ease;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'#fff\'">';
                html += '<i class="fa-solid fa-hotel" style="color: #eb2027; font-size: 14px;"></i>';
                html += '<div><div style="font-size: 14px; font-weight: 700; color: #0f172a;">' + c.name + '</div><div style="font-size: 11px; color: #64748b;">' + c.sub + '</div></div>';
                html += '</div>';
            });
        }
        destList.innerHTML = html;
    }

    renderCities('');

    window.selectAkbarCity = function(cityName, sub) {
        destDisplay.innerHTML = cityName;
        destHiddenInp.value = cityName;
        closeAkbarDropdowns();
    };

    if (destCol) {
        destCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            destDropdown.classList.add('show');
            setTimeout(function() { if (destSearchInp) destSearchInp.focus(); }, 100);
        });
    }

    if (destSearchInp) {
        destSearchInp.addEventListener('input', function(e) {
            renderCities(e.target.value);
        });
    }

    // 2. Date Formatting & Calculation
    function updateDateDisplays() {
        var dIn = new Date(checkinInput.value);
        var dOut = new Date(checkoutInput.value);

        if (dOut <= dIn) {
            dOut = new Date(dIn);
            dOut.setDate(dOut.getDate() + 1);
            checkoutInput.value = dOut.toISOString().split('T')[0];
        }

        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        checkinNum.textContent = String(dIn.getDate()).padStart(2, '0');
        checkinMon.textContent = months[dIn.getMonth()] + "'" + String(dIn.getFullYear()).slice(-2);
        checkinDay.textContent = days[dIn.getDay()];

        checkoutNum.textContent = String(dOut.getDate()).padStart(2, '0');
        checkoutMon.textContent = months[dOut.getMonth()] + "'" + String(dOut.getFullYear()).slice(-2);
        checkoutDay.textContent = days[dOut.getDay()];

        var diffTime = Math.abs(dOut - dIn);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        nightsVal.textContent = diffDays;
    }

    if (checkinInput) checkinInput.addEventListener('change', updateDateDisplays);
    if (checkoutInput) checkoutInput.addEventListener('change', updateDateDisplays);

    // 3. Guests Selector Logic
    var akRooms = 2;
    var akAdults = 4;
    var akChildren = 0;

    if (guestsCol) {
        guestsCol.addEventListener('click', function(e) {
            e.stopPropagation();
            closeAkbarDropdowns();
            guestsDropdown.classList.add('show');
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
