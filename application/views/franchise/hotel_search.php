<div style="display: grid; grid-template-columns: 220px 1fr; gap: 20px; align-items: start;">

    <!-- Left Vertical Nav Card matching reference screenshot -->
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

    <!-- Main Hotel Search Card -->
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); padding: 28px 32px;">
        
        <form method="get" action="<?php echo site_url('franchise/hotel_search'); ?>">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">Search Hotels</h1>
                
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 700; color: #09204b;">
                    <i class="fa-solid fa-hotel" style="color: #ea580c;"></i>
                    <span>Voyogo B2B Special Rates</span>
                    <span style="background: #2563eb; color: #ffffff; font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 10px;">PRO</span>
                </div>
            </div>

            <!-- Destination & Dates Grid -->
            <div style="display: grid; grid-template-columns: 2fr 1.2fr 1.2fr; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px; overflow: hidden; background: #ffffff;">
                
                <!-- City / Destination -->
                <div style="padding: 16px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">City or Destination</label>
                    <select name="city" style="width: 100%; border: none; outline: none; font-size: 20px; font-weight: 800; color: #0f172a; background: transparent; cursor: pointer;">
                        <option value="Goa" selected>Goa, India</option>
                        <option value="Mumbai">Mumbai, Maharashtra</option>
                        <option value="Delhi">New Delhi, NCR</option>
                        <option value="Bangalore">Bangalore, Karnataka</option>
                        <option value="Jaipur">Jaipur, Rajasthan</option>
                        <option value="Dubai">Dubai, UAE</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Bangkok">Bangkok, Thailand</option>
                        <option value="Phuket">Phuket, Thailand</option>
                    </select>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Domestic & International Hotel Inventory</div>
                </div>

                <!-- Check In -->
                <div style="padding: 16px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="fa-regular fa-calendar" style="font-size: 12px;"></i> CHECK IN
                    </label>
                    <input type="date" name="checkin" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" min="<?php echo date('Y-m-d'); ?>" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; width: 100%; cursor: pointer;">
                </div>

                <!-- Check Out -->
                <div style="padding: 16px 20px;">
                    <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="fa-regular fa-calendar" style="font-size: 12px;"></i> CHECK OUT
                    </label>
                    <input type="date" name="checkout" value="<?php echo date('Y-m-d', strtotime('+5 days')); ?>" min="<?php echo date('Y-m-d'); ?>" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; width: 100%; cursor: pointer;">
                </div>

            </div>

            <!-- Rooms & Guests -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; background: #ffffff;">
                <div style="padding: 14px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Rooms</label>
                    <select name="rooms" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; width: 100%;">
                        <option value="1">1 Room</option>
                        <option value="2">2 Rooms</option>
                        <option value="3">3 Rooms</option>
                        <option value="4">4 Rooms</option>
                    </select>
                </div>

                <div style="padding: 14px 20px; border-right: 1px solid #e2e8f0;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Adults (12+ Yrs)</label>
                    <select name="adults" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; width: 100%;">
                        <option value="1">1 Adult</option>
                        <option value="2" selected>2 Adults</option>
                        <option value="3">3 Adults</option>
                        <option value="4">4 Adults</option>
                    </select>
                </div>

                <div style="padding: 14px 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Children (0-11 Yrs)</label>
                    <select name="children" style="border: none; outline: none; font-size: 15px; font-weight: 700; color: #0f172a; background: transparent; cursor: pointer; width: 100%;">
                        <option value="0" selected>0 Children</option>
                        <option value="1">1 Child</option>
                        <option value="2">2 Children</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" style="background: #78B722; color: #ffffff; border: none; padding: 14px 38px; border-radius: 8px; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(120, 183, 34, 0.35); display: inline-flex; align-items: center; gap: 10px;">
                    <span>Search Hotels</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </form>

    </div>

</div>
