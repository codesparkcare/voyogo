<div style="display: grid; grid-template-columns: 220px 1fr; gap: 20px; align-items: start;">

    <!-- Left Vertical Nav Card matching reference screenshot -->
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

    <!-- Main Flight Search Card matching screenshot -->
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); padding: 28px 32px;">
        
        <form method="get" action="<?php echo site_url('franchise/flight_search'); ?>" id="b2bFlightForm">
            
            <!-- Header Row -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">Search Flights</h1>
                
                <div style="display: flex; align-items: center; gap: 20px;">
                    <a href="javascript:void(0)" onclick="alert('Franchise Guide: Select your origin, destination, and travel dates. Wallet balance will be debited instantly on confirmation.')" style="color: #dc2626; text-decoration: none; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-bell"></i> HOW TO USE
                    </a>

                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: #09204b;">
                        <i class="fa-solid fa-plane" style="color: #ea580c;"></i>
                        <span>Voyogo Special Fare</span>
                        <span style="background: #2563eb; color: #ffffff; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 10px;">NEW</span>
                    </div>
                </div>
            </div>

            <!-- Trip Type Radios -->
            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: #1e293b;">
                    <input type="radio" name="trip_type" value="oneway" checked onchange="toggleReturn(false)" style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span>One Way</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: #64748b;">
                    <input type="radio" name="trip_type" value="roundtrip" onchange="toggleReturn(true)" style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span>Round Trip</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: #64748b;">
                    <input type="radio" name="trip_type" value="multicity" disabled style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span style="color: #94a3b8;">Multi City</span>
                </label>
            </div>

            <!-- Route & Date Row (Boxes) -->
            <div style="display: grid; grid-template-columns: 1.4fr 1.4fr 1fr 1fr; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 18px; overflow: hidden; background: #ffffff;">
                
                <!-- FROM Block -->
                <div style="padding: 16px 20px; border-right: 1px solid #e2e8f0; position: relative;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">FROM</label>
                    <select name="origin" id="originSelect" onchange="updateOriginCity(this)" style="width: 100%; border: none; outline: none; font-size: 22px; font-weight: 800; color: #0f172a; background: transparent; cursor: pointer;">
                        <option value="BOM" selected>Mumbai</option>
                        <option value="DEL">New Delhi</option>
                        <option value="BLR">Bengaluru</option>
                        <option value="MAA">Chennai</option>
                        <option value="CCU">Kolkata</option>
                        <option value="HYD">Hyderabad</option>
                        <option value="GOI">Goa (Dabolim)</option>
                        <option value="GOX">Goa (Mopa)</option>
                        <option value="COK">Kochi</option>
                        <option value="AMD">Ahmedabad</option>
                        <option value="DXB">Dubai (DXB)</option>
                    </select>
                    <div id="originSub" style="font-size: 12px; color: #64748b; margin-top: 2px;">[BOM] Chhatrapati Shivaji Maharaj Intl</div>
                </div>

                <!-- TO Block with Swap Icon -->
                <div style="padding: 16px 20px; border-right: 1px solid #e2e8f0; position: relative;">
                    <!-- Swap Button Floating -->
                    <button type="button" onclick="swapAirports()" title="Swap Origin & Destination" style="position: absolute; left: -16px; top: 50%; transform: translateY(-50%); width: 32px; height: 32px; border-radius: 50%; background: #ffffff; border: 1.5px solid #cbd5e1; color: #78B722; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                        <i class="fa-solid fa-arrow-right-arrow-left" style="font-size: 12px;"></i>
                    </button>

                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">TO</label>
                    <select name="destination" id="destSelect" onchange="updateDestCity(this)" style="width: 100%; border: none; outline: none; font-size: 22px; font-weight: 800; color: #0f172a; background: transparent; cursor: pointer;">
                        <option value="DEL" selected>New Delhi</option>
                        <option value="BOM">Mumbai</option>
                        <option value="BLR">Bengaluru</option>
                        <option value="MAA">Chennai</option>
                        <option value="CCU">Kolkata</option>
                        <option value="HYD">Hyderabad</option>
                        <option value="GOI">Goa (Dabolim)</option>
                        <option value="GOX">Goa (Mopa)</option>
                        <option value="COK">Kochi</option>
                        <option value="AMD">Ahmedabad</option>
                        <option value="DXB">Dubai (DXB)</option>
                    </select>
                    <div id="destSub" style="font-size: 12px; color: #64748b; margin-top: 2px;">[DEL] Indira Gandhi International</div>
                </div>

                <!-- DEPART Block -->
                <div style="padding: 16px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="fa-regular fa-calendar" style="font-size: 12px;"></i> DEPART
                    </label>
                    <input type="date" name="depart_date" id="departDateInput" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" min="<?php echo date('Y-m-d'); ?>" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; width: 100%; cursor: pointer;">
                    <div id="departDaySub" style="font-size: 12px; color: #64748b; margin-top: 4px;"><?php echo date('l', strtotime('+7 days')); ?></div>
                </div>

                <!-- RETURN Block -->
                <div id="returnBlock" style="padding: 16px 20px; background: #fafafa; opacity: 0.7;">
                    <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="fa-regular fa-calendar" style="font-size: 12px;"></i> RETURN
                    </label>
                    <input type="date" name="return_date" id="returnDateInput" value="<?php echo date('Y-m-d', strtotime('+10 days')); ?>" min="<?php echo date('Y-m-d'); ?>" disabled style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; width: 100%; cursor: pointer; background: transparent;">
                    <div id="returnSubText" style="font-size: 12px; color: #64748b; margin-top: 4px;">Book a round trip to save more</div>
                </div>

            </div>

            <!-- Travellers & Cabin Class Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px; background: #ffffff;">
                <div style="padding: 14px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Travellers</label>
                    <select name="adults" style="border: none; outline: none; font-size: 16px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; width: 100%;">
                        <option value="1">1 Passenger (Adult)</option>
                        <option value="2">2 Passengers</option>
                        <option value="3">3 Passengers</option>
                        <option value="4">4 Passengers</option>
                        <option value="5">5 Passengers</option>
                        <option value="6">6 Passengers</option>
                        <option value="9">9 Passengers (Group)</option>
                    </select>
                </div>

                <div style="padding: 14px 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Onward Cabin Class</label>
                    <select name="cabin" style="border: none; outline: none; font-size: 16px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; width: 100%;">
                        <option value="ECONOMY" selected>Economy</option>
                        <option value="PREMIUM_ECONOMY">Premium Economy</option>
                        <option value="BUSINESS">Business Class</option>
                        <option value="FIRST">First Class</option>
                    </select>
                </div>
            </div>

            <!-- Airline Preference Strip matching screenshot -->
            <div style="background: #f0f7ff; border-radius: 10px; padding: 12px 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 24px;">
                <span style="font-size: 12.5px; font-weight: 700; color: #1e3a8a;">Airline Preference</span>
                
                <span style="background: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 14px; display: inline-flex; align-items: center; gap: 6px;">
                    All Airlines <i class="fa-solid fa-xmark" style="font-size: 11px; cursor: pointer;"></i>
                </span>

                <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" checked style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span>Low Cost Airlines</span>
                </label>

                <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" checked style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span>GDS Airlines</span>
                </label>

                <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                    <input type="checkbox" checked style="accent-color: #78B722; width: 16px; height: 16px;">
                    <span>NDC Airlines</span>
                </label>
            </div>

            <!-- Bottom Row: Filters and Search Button -->
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 24px; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer;">
                        <input type="checkbox" style="accent-color: #78B722; width: 15px; height: 15px;">
                        <span>Direct Flight</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer;">
                        <input type="checkbox" style="accent-color: #78B722; width: 15px; height: 15px;">
                        <span>Nearby Airports</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer;">
                        <input type="checkbox" style="accent-color: #78B722; width: 15px; height: 15px;">
                        <span>Student Fare</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer;">
                        <input type="checkbox" style="accent-color: #78B722; width: 15px; height: 15px;">
                        <span>Sr. Citizen Fare</span>
                    </label>
                </div>

                <button type="submit" style="background: #78B722; color: #ffffff; border: none; padding: 14px 38px; border-radius: 8px; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(120, 183, 34, 0.35); transition: background 0.15s; display: inline-flex; align-items: center; gap: 10px;">
                    <span>Search Flights</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </form>

    </div>

</div>

<script>
const airportNames = {
    'BOM': '[BOM] Chhatrapati Shivaji Maharaj Intl',
    'DEL': '[DEL] Indira Gandhi International',
    'BLR': '[BLR] Kempegowda International',
    'MAA': '[MAA] Chennai International',
    'CCU': '[CCU] Netaji Subhash Chandra Bose',
    'HYD': '[HYD] Rajiv Gandhi International',
    'GOI': '[GOI] Dabolim Airport',
    'GOX': '[GOX] Manohar International Airport',
    'COK': '[COK] Cochin International',
    'AMD': '[AMD] Sardar Vallabhbhai Patel Intl',
    'DXB': '[DXB] Dubai International Airport'
};

function updateOriginCity(select) {
    const code = select.value;
    document.getElementById('originSub').innerText = airportNames[code] || ('[' + code + '] Airport');
}

function updateDestCity(select) {
    const code = select.value;
    document.getElementById('destSub').innerText = airportNames[code] || ('[' + code + '] Airport');
}

function swapAirports() {
    const origSelect = document.getElementById('originSelect');
    const destSelect = document.getElementById('destSelect');
    const temp = origSelect.value;
    origSelect.value = destSelect.value;
    destSelect.value = temp;
    updateOriginCity(origSelect);
    updateDestCity(destSelect);
}

function toggleReturn(enable) {
    const returnBlock = document.getElementById('returnBlock');
    const returnInput = document.getElementById('returnDateInput');
    const returnSub   = document.getElementById('returnSubText');
    if (enable) {
        returnBlock.style.background = '#ffffff';
        returnBlock.style.opacity = '1';
        returnInput.disabled = false;
        returnSub.innerText = 'Return Date Selected';
    } else {
        returnBlock.style.background = '#fafafa';
        returnBlock.style.opacity = '0.7';
        returnInput.disabled = true;
        returnSub.innerText = 'Book a round trip to save more';
    }
}

document.getElementById('departDateInput').addEventListener('change', function() {
    const d = new Date(this.value);
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    document.getElementById('departDaySub').innerText = days[d.getDay()];
});
</script>
