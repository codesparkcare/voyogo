<?php
    $isUserLoggedIn   = isset($this->session) && $this->session->userdata('user_logged_in');
    $sessionUserName  = $isUserLoggedIn ? ($this->session->userdata('user_name') ?: '') : '';
    $sessionUserEmail = $isUserLoggedIn ? ($this->session->userdata('user_email') ?: '') : '';
    $sessionUserPhone = $isUserLoggedIn ? ($this->session->userdata('user_phone') ?: '') : '';
    $cleanPhone       = preg_replace('/^\+91/', '', $sessionUserPhone);

    $total_travelers_review = max(1, ($search_query['adults'] ?? 1) + ($search_query['children'] ?? 0) + ($search_query['infants'] ?? 0));
    $adult_count  = max(1, (int)($search_query['adults'] ?? 1));
    $child_count  = max(0, (int)($search_query['children'] ?? 0));
    $infant_count = max(0, (int)($search_query['infants'] ?? 0));

    $rawAirlineCode = strtoupper(substr($flight['flight_number'] ?? '', 0, 2));
    if (strpos($rawAirlineCode, '-') !== false) {
        $rawAirlineCode = explode('-', $flight['flight_number'])[0];
    }
    if (empty($rawAirlineCode)) $rawAirlineCode = '6E';

    $reviewPrice = (float)($flight['price'] ?? 5150);

    // Calculate airline-specific base fare and itemized taxes matching Akbar Travels
    switch ($rawAirlineCode) {
        case 'AI': // Air India
            $fBaseFare = round($reviewPrice * 0.725);
            $fTaxes = max(0, $reviewPrice - $fBaseFare);
            $taxFuel = 549 * $total_travelers_review;
            $taxUdf = 207 * $total_travelers_review;
            $taxSt = 25 * $total_travelers_review; // Service Tax ₹ 25
            $taxK3 = round(max(0, $fTaxes - ($taxFuel + $taxUdf + $taxSt)) * 0.72);
            $taxMisc = max(0, $fTaxes - ($taxFuel + $taxUdf + $taxSt + $taxK3));
            $airlineTaxes = array(
                array('name' => 'Fuel Surcharge', 'amount' => $taxFuel),
                array('name' => 'User Dev. Fee', 'amount' => $taxUdf),
                array('name' => 'K3 Tax', 'amount' => $taxK3),
                array('name' => 'Service Tax', 'amount' => $taxSt),
                array('name' => 'Airline Misc', 'amount' => $taxMisc)
            );
            break;

        case 'IX': // Air India Express: ONLY Fuel Surcharge + Airline Misc!
            $fBaseFare = round($reviewPrice * 0.694);
            $fTaxes = max(0, $reviewPrice - $fBaseFare);
            $taxFuel = 549 * $total_travelers_review;
            $taxMisc = max(0, $fTaxes - $taxFuel);
            $airlineTaxes = array(
                array('name' => 'Fuel Surcharge', 'amount' => $taxFuel),
                array('name' => 'Airline Misc', 'amount' => $taxMisc)
            );
            break;

        case 'SG': // SpiceJet (Screenshot 1 & 2: Base 4500, Fuel 599, UDF 207, K3 264, Misc 482)
            $fBaseFare = round($reviewPrice * 0.745);
            $fTaxes = max(0, $reviewPrice - $fBaseFare);
            $taxFuel = 599 * $total_travelers_review;
            $taxUdf = 207 * $total_travelers_review;
            $taxK3 = round($fTaxes * 0.1701);
            $taxMisc = max(0, $fTaxes - ($taxFuel + $taxUdf + $taxK3));
            $airlineTaxes = array(
                array('name' => 'Fuel Surcharge', 'amount' => $taxFuel),
                array('name' => 'User Dev. Fee', 'amount' => $taxUdf),
                array('name' => 'K3 Tax', 'amount' => $taxK3),
                array('name' => 'Airline Misc', 'amount' => $taxMisc)
            );
            break;

        case 'QP': // Akasa Air
            $fBaseFare = round($reviewPrice * 0.825);
            $fTaxes = max(0, $reviewPrice - $fBaseFare);
            $taxFuel = round($fTaxes * 0.386);
            $taxUdf = round($fTaxes * 0.133);
            $taxK3 = round($fTaxes * 0.170);
            $taxMisc = max(0, $fTaxes - ($taxFuel + $taxUdf + $taxK3));
            $airlineTaxes = array(
                array('name' => 'Fuel Surcharge', 'amount' => $taxFuel),
                array('name' => 'User Dev. Fee', 'amount' => $taxUdf),
                array('name' => 'K3 Tax', 'amount' => $taxK3),
                array('name' => 'Airline Misc', 'amount' => $taxMisc)
            );
            break;

        case '6E': // IndiGo & default
        default:
            $fBaseFare = round($reviewPrice * 0.788);
            $fTaxes = max(0, $reviewPrice - $fBaseFare);
            $taxFuel = 549 * $total_travelers_review;
            $taxUdf = round($fTaxes * 0.1334);
            $taxK3 = round($fTaxes * 0.1701);
            $taxMisc = max(0, $fTaxes - ($taxFuel + $taxUdf + $taxK3));
            $airlineTaxes = array(
                array('name' => 'Fuel Surcharge', 'amount' => $taxFuel),
                array('name' => 'User Dev. Fee', 'amount' => $taxUdf),
                array('name' => 'K3 Tax', 'amount' => $taxK3),
                array('name' => 'Airline Misc', 'amount' => $taxMisc)
            );
            break;
    }

    $adultBaseFare = round($fBaseFare * ($adult_count / $total_travelers_review));
    $childBaseFare = ($child_count > 0) ? round($fBaseFare * ($child_count / $total_travelers_review)) : 0;
    $infantBaseFare = ($infant_count > 0) ? max(0, $fBaseFare - $adultBaseFare - $childBaseFare) : 0;

    $insurancePerPax = 199;
    $initialInsurance = $insurancePerPax * $total_travelers_review;
    $initialDiscount = 18; // Default ATFLY discount matching Screenshot 1
    $initialGrandTotal = $fBaseFare + $fTaxes + $initialInsurance - $initialDiscount;
?>
<div style="background-color: #f4f7fe; padding: 25px 0 60px 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        
        <!-- Header Step Progress Bar -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 14px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.05); display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-plane-departure" style="color: #2563eb;"></i> Review Your Flight Itinerary
                </h1>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">
                    Complete passenger details & revalidate fare before final ticket issuance
                </p>
            </div>
            <div style="display: flex; gap: 20px; font-weight: 700; font-size: 13px;">
                <span style="color: #16a34a; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle-check"></i> 1. Flight Selected</span>
                <span style="color: #2563eb; background: #eff6ff; padding: 6px 14px; border-radius: 20px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle-dot"></i> 2. Review & Pax Details</span>
                <span style="color: #94a3b8; display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-circle"></i> 3. Payment & E-Ticket</span>
            </div>
        </div>

        <!-- Professional User Login Gate / Verified Status Banner -->
        <?php if ($isUserLoggedIn): ?>
            <div id="flightLoginBanner" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 20px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #16a34a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 14.5px; font-weight: 800; color: #166534;">Logged in as <?php echo htmlspecialchars($sessionUserName ?: $sessionUserPhone); ?></div>
                        <div style="font-size: 12.5px; color: #15803d;">Your verified contact details have been pre-filled below for ticket issuance.</div>
                    </div>
                </div>
                <span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-shield-halved"></i> Phone Verified
                </span>
            </div>
        <?php else: ?>
            <div id="flightLoginBanner" style="background: linear-gradient(135deg, #09204b 0%, #1e3a8a 100%); color: #ffffff; border-radius: 14px; padding: 18px 24px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; gap: 20px; box-shadow: 0 10px 25px rgba(9,32,75,0.12); flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 46px; height: 46px; border-radius: 50%; background: rgba(120, 183, 34, 0.2); color: #78B722; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #ffffff;">Please Sign In with Mobile OTP to Complete Booking</h4>
                        <p style="margin: 0; font-size: 13px; color: #cbd5e1;">Sign in to lock your fare, auto-fill passenger contact details, and receive e-tickets instantly.</p>
                    </div>
                </div>
                <button type="button" onclick="triggerBookingLogin('Please enter your mobile number to sign in and confirm this flight booking.')" style="background: #78B722; color: #ffffff; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13.5px; font-weight: 800; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(120,183,34,0.4); transition: transform 0.15s ease;">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>Sign In with OTP</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Subheader: Review your flight details & Back to Search (Screenshot 1) -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 0 4px;">
            <h2 style="font-size: 19px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                Review your flight details
            </h2>
            <a href="<?php echo isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'flight') !== false && strpos($_SERVER['HTTP_REFERER'], 'review') === false ? htmlspecialchars($_SERVER['HTTP_REFERER']) : site_url('flight'); ?>" onclick="goBackToSearch(); return false;" class="back-to-search-btn" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 12px; border-radius: 6px; transition: all 0.15s ease;">
                <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Back to Search
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px;">
            
            <!-- Left Column: Flight Details & Passenger Form -->
            <div>
                
                <!-- Flight Summary Card -->
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 18px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="<?php echo htmlspecialchars($flight['airline_logo']); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($flight['airline_name']); ?>&background=0d3470&color=fff';">
                            <div>
                                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0d3470;">
                                    <?php echo htmlspecialchars($flight['airline_name']); ?> 
                                    <span style="font-size: 14px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($flight['flight_number']); ?>)</span>
                                </h3>
                                <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                    Aircraft: Airbus A320 | Cabin: <strong style="color: #0d3470;"><?php echo htmlspecialchars($flight['cabin_class'] ?? 'Economy'); ?></strong>
                                </span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="background: #eff6ff; color: #1d4ed8; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px; display: inline-block;">
                                <?php echo date('D, d M Y', strtotime($flight['departure_date'])); ?>
                            </span>
                            <?php if (!empty($flight['fare_type'])): ?>
                                <span style="background: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 12px; font-weight: 800; font-size: 11px; margin-left: 6px; border: 1px solid <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                    <i class="<?php echo (stripos($flight['fare_type'], 'upfront') !== false || stripos($flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 2px;"></i> <?php echo htmlspecialchars($flight['fare_type']); ?> Fare
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($flight['refundable'])): ?>
                                <span style="background: #f0fdf4; color: #15803d; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11px; margin-left: 6px; border: 1px solid #bbf7d0;">
                                    <i class="fa-solid fa-rotate-left"></i> Refundable
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Flight Timing & Sector Grid -->
                    <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 18px 22px; border-radius: 10px; border: 1px solid #edf2f7; margin-bottom: 20px;">
                        <div style="text-align: left; max-width: 32%;">
                            <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($flight['departure_time']); ?></span>
                            <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($flight['from_code']); ?></div>
                            <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($flight['from_airport'] ?? 'Delhi Airport'); ?></div>
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($flight['from_terminal'] ?? 'Terminal 2'); ?></span>
                        </div>

                        <div style="text-align: center; flex: 1; margin: 0 20px;">
                            <span style="font-size: 12px; color: #64748b; font-weight: 700;"><?php echo htmlspecialchars($flight['duration'] ?? '2h 15m'); ?></span>
                            <div style="height: 2px; background: #cbd5e1; margin: 8px 0; position: relative;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 48%; color: #2563eb; transform: rotate(0deg); font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 11px; color: <?php echo (!empty($flight['stops'])) ? '#b45309' : '#16a34a'; ?>; font-weight: 800; background: <?php echo (!empty($flight['stops'])) ? '#fef3c7' : '#f0fdf4'; ?>; padding: 3px 10px; border-radius: 12px; border: 1px solid <?php echo (!empty($flight['stops'])) ? '#fcd34d' : '#86efac'; ?>;">
                                <?php 
                                if (isset($flight['stops']) && (int)$flight['stops'] > 0) {
                                    $v = !empty($flight['via']) ? $flight['via'] : 'HYD';
                                    echo $flight['stops'] . ' Stop (Via ' . htmlspecialchars($v) . ')';
                                } else {
                                    echo 'Non-Stop Direct';
                                }
                                ?>
                            </span>
                        </div>

                        <div style="text-align: right; max-width: 32%;">
                            <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($flight['arrival_time']); ?></span>
                            <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($flight['to_code']); ?></div>
                            <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($flight['to_airport'] ?? 'Mumbai Airport'); ?></div>
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($flight['to_terminal'] ?? 'Terminal 1'); ?></span>
                        </div>
                    </div>

                    <!-- Flight Specs & Baggage Allowance (4 Columns) + Cancellation Below (No Tab UI) -->
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 16px;">
                        <?php
                        $aircraftDisplay = !empty($flight['aircraft']) ? strtoupper(trim($flight['aircraft'])) : '';
                        if (empty($aircraftDisplay) || $aircraftDisplay === 'AIRBUS JET') {
                            $aircraftDisplay = 'BOEING';
                        } elseif ($aircraftDisplay === '320' || $aircraftDisplay === 'A320') {
                            $aircraftDisplay = 'AIRBUS A320';
                        } elseif ($aircraftDisplay === '737' || $aircraftDisplay === 'B737') {
                            $aircraftDisplay = 'BOEING 737';
                        } elseif ($aircraftDisplay === '787') {
                            $aircraftDisplay = 'BOEING 787';
                        }
                        if (empty($aircraftDisplay)) {
                            $aircraftDisplay = 'BOEING';
                        }

                        $travelClassDisplay = !empty($flight['cabin_class']) ? ucfirst(strtolower($flight['cabin_class'])) : (!empty($search_query['cabin_class']) ? ucfirst(strtolower($search_query['cabin_class'])) : 'Economy');

                        $rawCheckin = !empty($flight['checkin_baggage']) ? $flight['checkin_baggage'] : 'Adult - 15Kg';
                        if (stripos($rawCheckin, 'Adult') !== false) {
                            $checkinDisplay = $rawCheckin;
                        } elseif (preg_match('/(\d+)\s*Kg/i', $rawCheckin, $m)) {
                            $checkinDisplay = 'Adult - ' . $m[1] . 'Kg';
                        } else {
                            $checkinDisplay = 'Adult - 15Kg';
                        }

                        $rawCabin = !empty($flight['cabin_baggage']) ? $flight['cabin_baggage'] : 'Adult - 7Kg';
                        if (stripos($rawCabin, 'Adult') !== false) {
                            $cabinDisplay = $rawCabin;
                        } elseif (preg_match('/(\d+)\s*Kg/i', $rawCabin, $m)) {
                            $cabinDisplay = 'Adult - ' . $m[1] . 'Kg';
                        } else {
                            $cabinDisplay = 'Adult - 7Kg';
                        }
                        ?>
                        <div class="flight-specs-strip">
                            <div class="spec-col">
                                <div class="spec-col-title">Aircraft</div>
                                <div class="spec-col-value"><?php echo htmlspecialchars($aircraftDisplay); ?></div>
                            </div>
                            <div class="spec-divider"></div>
                            <div class="spec-col">
                                <div class="spec-col-title">Travel Class</div>
                                <div class="spec-col-value"><?php echo htmlspecialchars($travelClassDisplay); ?></div>
                            </div>
                            <div class="spec-divider"></div>
                            <div class="spec-col" style="flex: 1.2;">
                                <div class="spec-col-title">Check-In Baggage</div>
                                <div class="spec-col-value"><?php echo htmlspecialchars($checkinDisplay); ?></div>
                            </div>
                            <div class="spec-divider"></div>
                            <div class="spec-col">
                                <div class="spec-col-title">Cabin Baggage</div>
                                <div class="spec-col-value"><?php echo htmlspecialchars($cabinDisplay); ?></div>
                            </div>
                        </div>

                        <!-- Cancellation & Date Change Policy (Brought Down, Always Visible) -->
                        <div style="background: #f8fafc; padding: 16px 20px; border-radius: 8px; border: 1px solid #edf2f7; margin-top: 14px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                <h4 style="font-size: 13.5px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-file-contract" style="color: #2563eb;"></i> Cancellation & Date Change Policy (Per Pax)
                                </h4>
                                <span style="font-size: 11px; font-weight: 700; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px;">
                                    <?php echo !empty($flight['refundable']) ? 'Refundable' : 'Standard Rules'; ?>
                                </span>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 10px; background: #ffffff; border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <thead>
                                    <tr style="background: #f1f5f9; color: #334155; text-align: left; border-bottom: 1px solid #e2e8f0;">
                                        <th style="padding: 8px 12px; font-weight: 700;">Timeframe Before Departure</th>
                                        <th style="padding: 8px 12px; font-weight: 700;">Cancellation Charge</th>
                                        <th style="padding: 8px 12px; font-weight: 700;">Date Change Charge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fare_rules['cancellation'])): ?>
                                        <?php foreach ($fare_rules['cancellation'] as $idx => $rule): ?>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 8px 12px; font-weight: 600; color: #475569;"><?php echo htmlspecialchars($rule['time']); ?></td>
                                                <td style="padding: 8px 12px; font-weight: 700; color: #dc2626;"><?php echo htmlspecialchars($rule['fee']); ?></td>
                                                <td style="padding: 8px 12px; font-weight: 700; color: #2563eb;"><?php echo htmlspecialchars($fare_rules['date_change'][$idx]['fee'] ?? '₹ 2,500 + Diff'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 8px 12px; font-weight: 600; color: #475569;">2 hours to 24 hours</td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #dc2626;">₹ 3,500 per pax</td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #2563eb;">₹ 3,000 + Fare Diff</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 12px; font-weight: 600; color: #475569;">More than 24 hours</td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #dc2626;">₹ 3,000 per pax</td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #2563eb;">₹ 2,500 + Fare Diff</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <p style="font-size: 11px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Convenience fee & addon service charges are non-refundable.
                            </p>
                        </div>
                    </div>

                </div>

                <?php if (!empty($return_flight)): ?>
                <!-- Return Flight Summary Card -->
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 18px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="<?php echo htmlspecialchars($return_flight['airline_logo']); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($return_flight['airline_name']); ?>&background=0d3470&color=fff';">
                            <div>
                                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0d3470;">
                                    <i class="fa-solid fa-plane-arrival" style="color: #10b981; font-size: 16px;"></i> Return Flight: <?php echo htmlspecialchars($return_flight['airline_name']); ?> 
                                    <span style="font-size: 14px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($return_flight['flight_number']); ?>)</span>
                                </h3>
                                <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                    Aircraft: Airbus A320 | Cabin: <strong style="color: #0d3470;"><?php echo htmlspecialchars($flight['cabin_class'] ?? 'Economy'); ?></strong>
                                </span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="background: #f0fdf4; color: #15803d; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px; display: inline-block;">
                                <?php echo date('D, d M Y', strtotime($return_flight['departure_date'])); ?>
                            </span>
                            <?php if (!empty($return_flight['fare_type'])): ?>
                                <span style="background: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 12px; font-weight: 800; font-size: 11px; margin-left: 6px; border: 1px solid <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                    <i class="<?php echo (stripos($return_flight['fare_type'], 'upfront') !== false || stripos($return_flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 2px;"></i> <?php echo htmlspecialchars($return_flight['fare_type']); ?> Fare
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Return Flight Timing & Sector Grid -->
                    <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 18px 22px; border-radius: 10px; border: 1px solid #edf2f7;">
                        <div style="text-align: left; max-width: 32%;">
                            <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($return_flight['departure_time']); ?></span>
                            <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($return_flight['from_code']); ?></div>
                            <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($return_flight['from_airport'] ?? 'Airport'); ?></div>
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($return_flight['from_terminal'] ?? 'Terminal 1'); ?></span>
                        </div>

                        <div style="text-align: center; flex: 1; margin: 0 20px;">
                            <span style="font-size: 12px; color: #64748b; font-weight: 700;"><?php echo htmlspecialchars($return_flight['duration'] ?? '2h 15m'); ?></span>
                            <div style="height: 2px; background: #cbd5e1; margin: 8px 0; position: relative;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 48%; color: #10b981; transform: rotate(180deg); font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 11px; color: <?php echo (!empty($return_flight['stops'])) ? '#b45309' : '#16a34a'; ?>; font-weight: 800; background: <?php echo (!empty($return_flight['stops'])) ? '#fef3c7' : '#f0fdf4'; ?>; padding: 3px 10px; border-radius: 12px; border: 1px solid <?php echo (!empty($return_flight['stops'])) ? '#fcd34d' : '#86efac'; ?>;">
                                <?php 
                                if (isset($return_flight['stops']) && (int)$return_flight['stops'] > 0) {
                                    $rv = !empty($return_flight['via']) ? $return_flight['via'] : 'HYD';
                                    echo $return_flight['stops'] . ' Stop (Via ' . htmlspecialchars($rv) . ')';
                                } else {
                                    echo 'Non-Stop Direct';
                                }
                                ?>
                            </span>
                        </div>

                        <div style="text-align: right; max-width: 32%;">
                            <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($return_flight['arrival_time']); ?></span>
                            <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($return_flight['to_code']); ?></div>
                            <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($return_flight['to_airport'] ?? 'Airport'); ?></div>
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($return_flight['to_terminal'] ?? 'Terminal 2'); ?></span>
                        </div>
                    </div>

                    <!-- Return Flight 4-Column Specs Strip -->
                    <?php
                    $retAircraft = !empty($return_flight['aircraft']) ? strtoupper(trim($return_flight['aircraft'])) : 'BOEING';
                    $retClass = !empty($return_flight['cabin_class']) ? ucfirst(strtolower($return_flight['cabin_class'])) : $travelClassDisplay;
                    $retCheckin = !empty($return_flight['checkin_baggage']) ? $return_flight['checkin_baggage'] : $checkinDisplay;
                    $retCabin = !empty($return_flight['cabin_baggage']) ? $return_flight['cabin_baggage'] : $cabinDisplay;
                    ?>
                    <div class="flight-specs-strip" style="margin-top: 16px;">
                        <div class="spec-col">
                            <div class="spec-col-title">Aircraft</div>
                            <div class="spec-col-value"><?php echo htmlspecialchars($retAircraft); ?></div>
                        </div>
                        <div class="spec-divider"></div>
                        <div class="spec-col">
                            <div class="spec-col-title">Travel Class</div>
                            <div class="spec-col-value"><?php echo htmlspecialchars($retClass); ?></div>
                        </div>
                        <div class="spec-divider"></div>
                        <div class="spec-col" style="flex: 1.2;">
                            <div class="spec-col-title">Check-In Baggage</div>
                            <div class="spec-col-value"><?php echo htmlspecialchars($retCheckin); ?></div>
                        </div>
                        <div class="spec-divider"></div>
                        <div class="spec-col">
                            <div class="spec-col-title">Cabin Baggage</div>
                            <div class="spec-col-value"><?php echo htmlspecialchars($retCabin); ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form Section -->
                <form id="bookingForm" action="<?php echo site_url('flight/process_payment'); ?>" method="POST">
                    
                    <input type="hidden" name="tui" value="<?php echo htmlspecialchars($flight['tui'] ?? $url_meta['tui'] ?? ''); ?>">
                    <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($flight['flight_number']); ?>">
                    <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($flight['airline_name']); ?>">
                    <input type="hidden" name="origin" value="<?php echo htmlspecialchars($flight['from_code']); ?>">
                    <input type="hidden" name="destination" value="<?php echo htmlspecialchars($flight['to_code']); ?>">
                    <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($flight['departure_date']); ?>">
                    <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($flight['departure_time']); ?>">
                    <input type="hidden" name="arrival_time" value="<?php echo htmlspecialchars($flight['arrival_time'] ?? ''); ?>">
                    <input type="hidden" name="duration" value="<?php echo htmlspecialchars($flight['duration'] ?? ''); ?>">
                    <input type="hidden" name="stops" value="<?php echo htmlspecialchars($flight['stops'] ?? 0); ?>">
                    <input type="hidden" name="via" value="<?php echo htmlspecialchars($flight['via'] ?? ''); ?>">
                    <input type="hidden" name="net_amount" value="<?php echo htmlspecialchars($fBaseFare); ?>">
                    <input type="hidden" name="total_amount" id="form_total_amount" value="<?php echo htmlspecialchars($initialGrandTotal); ?>">
                    <input type="hidden" name="insurance_amount" id="form_insurance_amount" value="<?php echo htmlspecialchars($initialInsurance); ?>">
                    <input type="hidden" name="discount_amount" id="form_discount_amount" value="<?php echo htmlspecialchars($initialDiscount); ?>">
                    <input type="hidden" name="promo_code" id="form_promo_code" value="ATFLY">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
                    <input type="hidden" name="is_roundtrip" value="<?php echo !empty($is_roundtrip) ? '1' : '0'; ?>">
                    <input type="hidden" name="fare_type" value="<?php echo htmlspecialchars($flight['fare_type'] ?? 'Retail'); ?>">
                    <input type="hidden" name="onward_fare_type" value="<?php echo htmlspecialchars($flight['fare_type'] ?? 'Retail'); ?>">

                    <?php if (!empty($return_flight)): ?>
                    <input type="hidden" name="return_fare_type" value="<?php echo htmlspecialchars($return_flight['fare_type'] ?? 'Retail'); ?>">
                    <input type="hidden" name="return_flight_number" value="<?php echo htmlspecialchars($return_flight['flight_number']); ?>">
                    <input type="hidden" name="return_airline_name" value="<?php echo htmlspecialchars($return_flight['airline_name']); ?>">
                    <input type="hidden" name="return_origin" value="<?php echo htmlspecialchars($return_flight['from_code']); ?>">
                    <input type="hidden" name="return_destination" value="<?php echo htmlspecialchars($return_flight['to_code']); ?>">
                    <input type="hidden" name="return_departure_date" value="<?php echo htmlspecialchars($return_flight['departure_date']); ?>">
                    <input type="hidden" name="return_departure_time" value="<?php echo htmlspecialchars($return_flight['departure_time']); ?>">
                    <input type="hidden" name="return_arrival_time" value="<?php echo htmlspecialchars($return_flight['arrival_time']); ?>">
                    <input type="hidden" name="return_duration" value="<?php echo htmlspecialchars($return_flight['duration']); ?>">
                    <input type="hidden" name="return_stops" value="<?php echo htmlspecialchars($return_flight['stops'] ?? 0); ?>">
                    <input type="hidden" name="return_via" value="<?php echo htmlspecialchars($return_flight['via'] ?? ''); ?>">
                    <?php endif; ?>

                    <input type="hidden" name="ssr_baggage_code" id="ssr_baggage_code" value="">
                    <input type="hidden" name="ssr_baggage_amount" id="ssr_baggage_amount" value="0">
                    <input type="hidden" name="ssr_baggage_desc" id="ssr_baggage_desc" value="">
                    <input type="hidden" name="ssr_meal_code" id="ssr_meal_code" value="">
                    <input type="hidden" name="ssr_meal_amount" id="ssr_meal_amount" value="0">
                    <input type="hidden" name="ssr_meal_desc" id="ssr_meal_desc" value="">

                    <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                    <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                    <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                    <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">

                    <!-- Maximize Your Travel Safety & Flexibility (Screenshot 1) -->
                    <div style="background: #ffffff; border-radius: 14px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                            Maximize Your Travel Safety & Flexibility
                        </h3>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0 0 16px 0;">
                            Secure your booking with cancellation safety, date change freedom, and baggage support—all designed to make your trip stress-free.
                        </p>

                        <!-- Option 1: Refundable Upgrade -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                            <label style="display: flex; gap: 12px; cursor: pointer; flex: 1;">
                                <input type="radio" name="refundable_opt" value="0" checked style="accent-color: #2563eb; margin-top: 3px;">
                                <div>
                                    <div style="font-size: 13.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        Make your booking refundable <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                                        Upgrade to refundable. Get up to <strong>100% refund</strong> if plans change, as per applicable <a href="javascript:void(0);" style="color: #0284c7; text-decoration: none;">T&Cs</a>
                                    </div>
                                    <div style="display: flex; gap: 14px; font-size: 11.5px; color: #475569; margin-top: 6px; flex-wrap: wrap;">
                                        <span>• Illness/Injury</span>
                                        <span>• Pre-existing medical condition</span>
                                        <span>• Last Minute Emergency</span>
                                    </div>
                                </div>
                            </label>
                            <div style="text-align: right; min-width: 90px;">
                                <div style="font-size: 14.5px; font-weight: 800; color: #0f172a;">₹ 724</div>
                                <div style="font-size: 11px; color: #64748b;">per passenger per trip</div>
                            </div>
                        </div>

                        <!-- Option 2: Travel Insurance -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                            <label style="display: flex; gap: 12px; cursor: pointer; flex: 1;">
                                <input type="checkbox" id="travelInsuranceCheckbox" checked onchange="toggleTravelInsurance(this.checked)" style="accent-color: #2563eb; margin-top: 3px;">
                                <div>
                                    <div style="font-size: 13.5px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        Travel Insurance <i class="fa-solid fa-shield-halved" style="color: #0284c7;"></i>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                                        Secure your trip with our Travel Insurance.
                                    </div>
                                    <div style="display: flex; gap: 14px; font-size: 11.5px; color: #475569; margin-top: 6px; flex-wrap: wrap;">
                                        <span>• Trip Delays</span>
                                        <span>• Trip Cancellation</span>
                                        <span>• Lost Baggage</span>
                                        <a href="javascript:void(0);" style="color: #0284c7; text-decoration: underline;">View more benefits</a>
                                    </div>
                                    <div style="font-size: 11px; color: #0284c7; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-circle-info"></i> Only for Indian Citizen below the age of 70 years
                                    </div>
                                </div>
                            </label>
                            <div style="text-align: right; min-width: 90px;">
                                <a href="javascript:void(0);" id="insuranceToggleBtn" onclick="toggleInsuranceLink()" style="font-size: 12px; color: #0284c7; font-weight: 700; text-decoration: underline;">Remove</a>
                                <div style="font-size: 14.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">₹ 199</div>
                                <div style="font-size: 11px; color: #64748b;">per passenger per trip</div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $p_index = 0;
                    ?>

                    <!-- Adult Passenger Cards -->
                    <?php 
                    $flight_dep_date = !empty($flightDetails['departure_date']) ? $flightDetails['departure_date'] : date('Y-m-d', strtotime('+7 days'));
                    for ($a = 1; $a <= $adult_count; $a++): $p_index++; 
                    ?>
                    <div class="passenger-card" style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-users" style="color: #ef4444;"></i> Passenger Information (Adult <?php echo $a; ?>)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Govt. ID (Aadhaar / Passport)</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Adult">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1.5fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Title *</label>
                                <select name="passenger_title[]" class="field-input" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;">
                                    <option value="Mr">Mr</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Mrs">Mrs</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter First & Last Name" value="<?php echo ($p_index === 1) ? 'Rahul Sharma' : ''; ?>" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="1996-05-15" max="<?php echo date('Y-m-d', strtotime('-12 years', strtotime($flight_dep_date))); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="28" min="12" max="99" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" checked style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- Child Passenger Cards -->
                    <?php for ($c = 1; $c <= $child_count; $c++): $p_index++; ?>
                    <div class="passenger-card" style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-child" style="color: #3b82f6;"></i> Passenger Information (Child <?php echo $c; ?> - Age 2-12 yrs)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Govt. ID / Birth Cert.</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Child">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1.5fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Title *</label>
                                <select name="passenger_title[]" class="field-input" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;">
                                    <option value="Mstr">Master</option>
                                    <option value="Miss">Miss</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter Child's Name" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="2019-08-30" min="<?php echo date('Y-m-d', strtotime('-12 years', strtotime($flight_dep_date))); ?>" max="<?php echo date('Y-m-d', strtotime('-2 years', strtotime($flight_dep_date))); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="7" min="2" max="11" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" checked style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- Infant Passenger Cards -->
                    <?php for ($i_cnt = 1; $i_cnt <= $infant_count; $i_cnt++): $p_index++; ?>
                    <div class="passenger-card" style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-baby" style="color: #10b981;"></i> Passenger Information (Infant <?php echo $i_cnt; ?> - Below 2 yrs)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Birth Cert.</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Infant">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1.5fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Title *</label>
                                <select name="passenger_title[]" class="field-input" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;">
                                    <option value="Mstr">Master</option>
                                    <option value="Miss">Miss</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter Infant's Name" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="2025-08-30" min="<?php echo date('Y-m-d', strtotime('-2 years', strtotime($flight_dep_date))); ?>" max="<?php echo date('Y-m-d', strtotime($flight_dep_date)); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="1" min="0" max="2" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" checked style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- SSR Add-on Services Card (Meals, Extra Baggage, Seats) -->
                    <?php
                    $baggageList = array();
                    $mealsList = array();

                    // Parse dynamic SSR returned by Benzy Flights/SSR
                    if (!empty($ssr['Trips']) && is_array($ssr['Trips'])) {
                        foreach ($ssr['Trips'] as $tr) {
                            if (!empty($tr['Journey']) && is_array($tr['Journey'])) {
                                foreach ($tr['Journey'] as $jrn) {
                                    if (!empty($jrn['Segments']) && is_array($jrn['Segments'])) {
                                        foreach ($jrn['Segments'] as $seg) {
                                            if (!empty($seg['SSR']) && is_array($seg['SSR'])) {
                                                foreach ($seg['SSR'] as $item) {
                                                    $type = (string)($item['Type'] ?? '');
                                                    $code = $item['Code'] ?? '';
                                                    $desc = $item['Description'] ?? '';
                                                    $charge = isset($item['Charge']) ? (float)$item['Charge'] : (isset($item['SSRNetAmount']) ? (float)$item['SSRNetAmount'] : 0);

                                                    if ($type === '2' || stripos($desc, 'baggage') !== false || stripos($code, 'XBP') !== false || stripos($code, 'IXB') !== false) {
                                                        if (!isset($baggageList[$code]) && !empty($code)) {
                                                            $baggageList[$code] = array(
                                                                'Code'        => $code,
                                                                'Description' => $desc,
                                                                'Amount'      => $charge
                                                            );
                                                        }
                                                    } elseif ($type === '1' || stripos($desc, 'meal') !== false || stripos($desc, 'sandwich') !== false || stripos($desc, 'combo') !== false || stripos($desc, 'biryani') !== false) {
                                                        if (!isset($mealsList[$code]) && !empty($code)) {
                                                            $mealsList[$code] = array(
                                                                'Code'        => $code,
                                                                'Description' => $desc,
                                                                'Amount'      => $charge
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $baggageList = array_values($baggageList);
                    $mealsList = array_values($mealsList);

                    // Always prepend standard Free baggage option at index 0
                    array_unshift($baggageList, array(
                        "Code"        => "FREE",
                        "Description" => "Standard Cabin (7kg) + Check-in (15kg) - Included",
                        "Amount"      => 0
                    ));

                    // Always prepend "No Meal" option at index 0
                    array_unshift($mealsList, array(
                        "Code"        => "NO_MEAL",
                        "Description" => "No In-Flight Meal",
                        "Amount"      => 0
                    ));

                    // If still empty, use official live IndiGo Benzy SSR rates
                    if (count($baggageList) <= 1) {
                        $baggageList = array(
                            array("Code" => "FREE", "Description" => "Standard Cabin (7kg) + Check-in (15kg) - Included", "Amount" => 0),
                            array("Code" => "XBPE", "Description" => "Prepaid Excess Baggage – 3 Kg", "Amount" => 2100),
                            array("Code" => "XBPA", "Description" => "Prepaid Excess Baggage – 5 Kg", "Amount" => 3250),
                            array("Code" => "XBPB", "Description" => "Prepaid Excess Baggage – 10 Kg", "Amount" => 6250),
                            array("Code" => "XBPC", "Description" => "Prepaid Excess Baggage – 15 Kg", "Amount" => 9400),
                            array("Code" => "XBPJ", "Description" => "Prepaid Excess Baggage – 20 Kg", "Amount" => 12000),
                            array("Code" => "XBPD", "Description" => "Prepaid Excess Baggage – 30 Kg", "Amount" => 19500),
                        );
                    }

                    if (count($mealsList) <= 1) {
                        $mealsList = array(
                            array("Code" => "NO_MEAL", "Description" => "No In-Flight Meal", "Amount" => 0),
                            array("Code" => "VGML", "Description" => "Veg Meal (For Retail Fare)", "Amount" => 400),
                            array("Code" => "VCSW", "Description" => "6E Eats choice of the day (veg) + beverage", "Amount" => 400),
                            array("Code" => "VBIR", "Description" => "VEG BIRYANI Combo", "Amount" => 400),
                            array("Code" => "AGSW", "Description" => "#IndiaByIndiGo regional favourite (veg) + beverage", "Amount" => 400),
                            array("Code" => "PTSW", "Description" => "Paneer Tikka Sandwich Combo", "Amount" => 500),
                            array("Code" => "CJSW", "Description" => "Chicken Junglee Sandwich Combo", "Amount" => 500),
                        );
                    }
                    ?>
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-utensils" style="color: #f59e0b;"></i> Select Add-on Services (SSR)
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <!-- Extra Baggage -->
                            <div>
                                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                                    <i class="fa-solid fa-suitcase" style="color: #2563eb;"></i> Extra Check-in Baggage
                                </label>
                                <select id="extraBaggageSelect" name="extra_baggage" onchange="calculateTotalAddons()" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; background: #fff;">
                                    <?php foreach ($baggageList as $bag): 
                                        $desc = $bag['Description'] ?? $bag['description'] ?? $bag['Name'] ?? 'Extra Baggage';
                                        $amt = isset($bag['Amount']) ? (float)$bag['Amount'] : (isset($bag['amount']) ? (float)$bag['amount'] : (isset($bag['Price']) ? (float)$bag['Price'] : (isset($bag['price']) ? (float)$bag['price'] : 0)));
                                        $bCode = $bag['Code'] ?? $bag['code'] ?? ($amt > 0 ? 'XBPE' : '');
                                    ?>
                                        <option value="<?php echo $amt; ?>" data-code="<?php echo htmlspecialchars($bCode); ?>" data-desc="<?php echo htmlspecialchars($desc); ?>">
                                            <?php echo htmlspecialchars($desc); ?> <?php echo ($amt > 0) ? '(+₹' . number_format($amt) . ')' : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- In-Flight Meal -->
                            <div>
                                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                                    <i class="fa-solid fa-bowl-food" style="color: #16a34a;"></i> In-Flight Meal Selection
                                </label>
                                <select id="mealSelect" name="meal_selection" onchange="calculateTotalAddons()" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; background: #fff;">
                                    <?php foreach ($mealsList as $meal): 
                                        $desc = $meal['Description'] ?? $meal['description'] ?? $meal['Name'] ?? 'Meal Selection';
                                        $amt = isset($meal['Amount']) ? (float)$meal['Amount'] : (isset($meal['amount']) ? (float)$meal['amount'] : (isset($meal['Price']) ? (float)$meal['Price'] : (isset($meal['price']) ? (float)$meal['price'] : 0)));
                                        $mCode = $meal['Code'] ?? $meal['code'] ?? ($amt > 0 ? 'VGML' : '');
                                    ?>
                                        <option value="<?php echo $amt; ?>" data-code="<?php echo htmlspecialchars($mCode); ?>" data-desc="<?php echo htmlspecialchars($desc); ?>">
                                            <?php echo htmlspecialchars($desc); ?> <?php echo ($amt > 0) ? '(+₹' . number_format($amt) . ')' : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & GST Details Card -->
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-address-book" style="color: #2563eb;"></i> Contact & E-Ticket Details
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Contact Person *</label>
                                <input type="text" name="contact_name" class="field-input" required value="<?php echo htmlspecialchars($sessionUserName ?: ''); ?>" placeholder="Full Name" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Email Address *</label>
                                <input type="email" name="contact_email" class="field-input" required value="<?php echo htmlspecialchars($sessionUserEmail ?: ''); ?>" placeholder="name@example.com" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Mobile Number *</label>
                                <input type="tel" name="contact_phone" class="field-input" required value="<?php echo htmlspecialchars($cleanPhone ?: $sessionUserPhone); ?>" placeholder="10-digit mobile" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                        </div>

                        <!-- GST Checkbox Toggle -->
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 14px;">
                            <label style="font-size: 13px; font-weight: 700; color: #0d3470; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="gstToggle" onchange="toggleGstFields()" style="accent-color: #2563eb; width: 16px; height: 16px;">
                                Use GSTIN for Business Travel & Tax Invoice Claim (Optional)
                            </label>

                            <div id="gstFieldsSection" style="display: none; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px; background: #f8fafc; padding: 14px; border-radius: 8px;">
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">GSTIN Number</label>
                                    <input type="text" name="gst_number" placeholder="27AAAAA0000A1Z5" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Registered Company Name</label>
                                    <input type="text" name="gst_company" placeholder="Voyogo Solutions Pvt Ltd" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button Banner -->
                    <div style="text-align: right; margin-bottom: 30px;">
                        <button type="button" id="payRazorpayBtn" style="padding: 16px 36px; font-size: 17px; font-weight: 800; color: #ffffff; background: linear-gradient(135deg, #0d3470, #2563eb); border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 15px rgba(37,99,235,0.3); transition: all 0.3s ease;">
                            <i class="fa-solid fa-lock" style="margin-right: 8px;"></i> Pay ₹ <span id="btnPayAmount"><?php echo number_format($initialGrandTotal); ?></span> & Instant Confirm Booking
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Column: Sticky Fare Details & Promo Code Sidebar (Screenshots 1 & 2) -->
            <div>
                <div style="position: sticky; top: 90px; display: flex; flex-direction: column; gap: 18px;">
                    
                    <!-- 1. Fare Details Card (Screenshots 1 & 2) -->
                    <div class="f-fare-details-box" style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            <strong style="font-size: 15px; color: #0f172a; font-weight: 700;">Fare Details</strong>
                            <span style="font-size: 13px; color: #0284c7; font-weight: 600;"><?php echo $total_travelers_review; ?> Traveller<?php echo $total_travelers_review > 1 ? 's' : ''; ?></span>
                        </div>

                        <!-- Base Fare Item with Subrow (Toggleable +/-) -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to view Base Fare breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-regular fa-circle-plus f-fare-toggle-icon"></i> Base Fare
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryBaseFare"><?php echo number_format($fBaseFare); ?></span></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Adult (<?php echo $adult_count; ?> X ₹ <?php echo number_format(round($fBaseFare / max(1, $total_travelers_review))); ?>)</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($adultBaseFare); ?></span>
                                </div>
                                <?php if ($child_count > 0): ?>
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Child (<?php echo $child_count; ?> X ₹ <?php echo number_format(round($fBaseFare / max(1, $total_travelers_review))); ?>)</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($childBaseFare); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($infant_count > 0): ?>
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>Infant (<?php echo $infant_count; ?>)</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($infantBaseFare); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tax & Charges Item with Subrows (Toggleable +/-) -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to view Tax & Charges breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-regular fa-circle-plus f-fare-toggle-icon"></i> Tax & Charges
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryTaxes"><?php echo number_format($fTaxes); ?></span></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <?php foreach ($airlineTaxes as $tax): ?>
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span><?php echo htmlspecialchars($tax['name']); ?></span>
                                    <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($tax['amount']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Insurance Item with Subrows (Toggleable +/-) (Screenshots 1 & 2) -->
                        <div class="f-fare-group" id="fareGroupInsurance" style="margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to view Insurance breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-regular fa-circle-plus f-fare-toggle-icon"></i> Insurance
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryInsurance"><?php echo number_format($initialInsurance); ?></span></strong>
                            </div>
                            <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>BasePremium</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <span id="dispBasePremium"><?php echo number_format(169 * $total_travelers_review); ?></span></span>
                                </div>
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>SGST</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <span id="dispSGST"><?php echo number_format(15 * $total_travelers_review); ?></span></span>
                                </div>
                                <div class="f-fare-subrow" style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                    <span>CGST</span>
                                    <span style="font-weight: 600; color: #334155;">₹ <span id="dispCGST"><?php echo number_format(15 * $total_travelers_review); ?></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- SSR / Add-on Services Group (If selected) -->
                        <div class="f-fare-group" id="summaryAddonsRow" style="display: none; margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; font-weight: 600; color: #1e293b;">Add-on Services (SSR)</span>
                                <strong style="color: #2563eb; font-size: 13.5px;">₹ <span id="summaryAddons">0</span></strong>
                            </div>
                        </div>

                        <!-- Promo Discount Applied (Screenshots 1 & 2) -->
                        <div id="summaryDiscountRow" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-size: 13px; font-weight: 600; color: #16a34a; display: flex; align-items: center; gap: 6px;">
                                <span style="background: #16a34a; color: #ffffff; width: 18px; height: 18px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 900;">%</span>
                                Promo Discount Applied
                            </span>
                            <strong style="color: #16a34a; font-size: 13.5px;">- ₹ <span id="summaryDiscount"><?php echo number_format($initialDiscount); ?></span></strong>
                        </div>

                        <!-- Total Amount Row (Screenshots 1 & 2) -->
                        <div class="f-fare-total-row" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #f1f5f9; border-radius: 6px; margin-top: 14px;">
                            <span style="font-size: 14px; font-weight: 700; color: #0f172a;">Total Amount:</span>
                            <span class="total-amount" style="font-size: 19px; font-weight: 800; color: #0f172a;">₹ <span id="summaryTotalAmount"><?php echo number_format($initialGrandTotal); ?></span></span>
                        </div>
                    </div>

                    <!-- 2. Promo Code Card (Screenshot 1) -->
                    <div class="promo-card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                        <div class="promo-header" style="background: #86efac; padding: 10px 18px; font-size: 14px; font-weight: 700; color: #065f46; letter-spacing: 0.2px;">
                            Promo Code
                        </div>
                        <div class="promo-body" style="padding: 16px;">
                            <!-- Applied Promo Badge -->
                            <div class="promo-applied-banner" id="promoAppliedBanner" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-weight: 800; font-size: 13px; color: #166534; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-circle-percent" style="color: #16a34a; font-size: 15px;"></i>
                                        <span id="dispAppliedCode">ATFLY</span>
                                    </span>
                                    <span style="font-size: 11px; font-weight: 700; color: #16a34a; background: #dcfce7; padding: 2px 8px; border-radius: 12px; border: 1px solid #86efac;">APPLIED</span>
                                </div>
                                <div style="font-size: 11.5px; color: #15803d; font-weight: 500; margin-top: 4px;">
                                    Your Promocode has been applied you've saved ₹ <span id="dispAppliedSavings"><?php echo number_format($initialDiscount); ?></span>
                                </div>
                            </div>

                            <!-- Custom Promo Code Input Box -->
                            <div style="display: flex; gap: 8px; margin-bottom: 12px;">
                                <input type="text" id="promoCodeInput" placeholder="Enter Promo Code" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; text-transform: uppercase;">
                                <button type="button" onclick="applyCustomPromoCode()" style="padding: 8px 14px; background: #0d3470; color: #ffffff; border: none; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer; transition: background 0.15s ease;">
                                    Apply
                                </button>
                            </div>
                            <div id="promoMsg" style="font-size: 11px; margin-bottom: 12px; display: none;"></div>

                            <div style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 10px;">
                                Choose from the offers below
                            </div>

                            <!-- Offer 1: ATRBL (Screenshot 1) -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('ATRBL', 450, 'Applicable on RBL Bank Credit/Debit Cards, T&C Apply. Flat Off ₹ 450')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_ATRBL" value="ATRBL" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">ATRBL</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 450</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">Applicable on RBL Bank Credit/Debit Cards, T&C Apply. Flat Off ₹ 450</div>
                                </div>
                            </div>

                            <!-- Offer 2: ZEROFEE (Screenshot 1) -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('ZEROFEE', 350, '#NoConvenienceFee. Choose this promo to get a discount of ₹ 350')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_ZEROFEE" value="ZEROFEE" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">ZEROFEE</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 350</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">#NoConvenienceFee. Choose this promo to get a discount of ₹ 350</div>
                                </div>
                            </div>

                            <!-- Offer 3: RAINYDEAL (Screenshot 1) -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('RAINYDEAL', 218, '#AkbarSpecial - Choose this promo to enjoy a discount of ₹ 218')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_RAINYDEAL" value="RAINYDEAL" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">RAINYDEAL</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 218</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">#AkbarSpecial - Choose this promo to enjoy a discount of ₹ 218</div>
                                </div>
                            </div>

                            <!-- Offer 4: ATAUDD (Screenshot 1) -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('ATAUDD', 200, 'Applicable on AU Small Financial Bank Debit/Credit Card, T&C Apply. Flat Off ₹ 200')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_ATAUDD" value="ATAUDD" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">ATAUDD</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 200</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">Applicable on AU Small Financial Bank Debit/Credit Card, T&C Apply. Flat Off ₹ 200</div>
                                </div>
                            </div>

                            <!-- Offer 5: ATDBS (Screenshot 1) -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('ATDBS', 200, 'Applicable on DBS Bank Credit/Debit Cards, T&C Apply. Flat Off ₹ 200')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_ATDBS" value="ATDBS" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">ATDBS</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 200</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">Applicable on DBS Bank Credit/Debit Cards, T&C Apply. Flat Off ₹ 200</div>
                                </div>
                            </div>

                            <!-- Offer 6: VOYOGO500 -->
                            <div class="promo-offer-item" onclick="selectPromoOffer('VOYOGO500', 500, 'Exclusive Voyogo Special. Flat ₹ 500 Instant Discount on all bookings!')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s ease; background: #ffffff;">
                                <input type="radio" name="promo_radio" id="promo_VOYOGO500" value="VOYOGO500" style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">VOYOGO500</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 500</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">Exclusive Voyogo Special. Flat ₹ 500 Instant Discount on all bookings!</div>
                                </div>
                            </div>

                            <!-- Offer 7: ATFLY (Default Selected in Screenshot 1) -->
                            <div class="promo-offer-item active" onclick="selectPromoOffer('ATFLY', 18, 'Default Instant Web Discount. Instant Flat Off ₹ 18')" style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border: 1px solid #22c55e; border-radius: 8px; margin-bottom: 4px; cursor: pointer; transition: all 0.2s ease; background: #f0fdf4;">
                                <input type="radio" name="promo_radio" id="promo_ATFLY" value="ATFLY" checked style="margin-top: 2px; accent-color: #16a34a;">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="font-size: 13px; font-weight: 800; color: #0f172a;">ATFLY</strong>
                                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 2px 7px; border-radius: 10px;">Save 18</span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; line-height: 1.35;">Default Instant Web Discount. Instant Flat Off ₹ 18</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Trust Badges -->
                    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 14px 16px; font-size: 12px; color: #475569; display: flex; flex-direction: column; gap: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-shield-halved" style="color: #2563eb; font-size: 15px;"></i>
                            <span style="font-weight: 600;">100% Safe & Instant Booking</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-envelope-circle-check" style="color: #16a34a; font-size: 15px;"></i>
                            <span style="font-weight: 600;">Instant E-Ticket Sent to Email</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript Interactivity -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var baseFlightFare = <?php echo (float)$fBaseFare; ?>;
var taxesFare = <?php echo (float)$fTaxes; ?>;
var totalPax = <?php echo (int)$total_travelers_review; ?>;
var insurancePerPax = 199;
var isInsuranceIncluded = true;
var appliedDiscount = <?php echo (float)$initialDiscount; ?>;
var appliedPromoCodeName = 'ATFLY';

function goBackToSearch() {
    if (document.referrer && (document.referrer.indexOf('/flight') !== -1 || document.referrer.indexOf('/search') !== -1) && document.referrer.indexOf('/flight/review') === -1) {
        window.location.href = document.referrer;
    } else if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '<?php echo site_url('flight'); ?>';
    }
}

function toggleFareBreakdown(triggerEl) {
    var group = triggerEl.closest('.f-fare-group');
    if (!group) return;
    var subitems = group.querySelector('.f-fare-subitems');
    var icon = group.querySelector('.f-fare-toggle-icon');
    if (!subitems || !icon) return;

    var isHidden = (subitems.style.display === 'none' || window.getComputedStyle(subitems).display === 'none');
    if (isHidden) {
        subitems.style.display = 'flex';
        icon.classList.remove('fa-circle-plus');
        icon.classList.add('fa-circle-minus');
    } else {
        subitems.style.display = 'none';
        icon.classList.remove('fa-circle-minus');
        icon.classList.add('fa-circle-plus');
    }
}

function toggleTravelInsurance(include) {
    isInsuranceIncluded = include;
    var cb = document.getElementById('travelInsuranceCheckbox');
    if (cb) cb.checked = include;
    var linkBtn = document.getElementById('insuranceToggleBtn');
    if (linkBtn) linkBtn.textContent = include ? 'Remove' : '+ Add';

    var insGroup = document.getElementById('fareGroupInsurance');
    if (insGroup) {
        insGroup.style.display = include ? 'block' : 'none';
    }
    recalculateAllFares();
}

function toggleInsuranceLink() {
    toggleTravelInsurance(!isInsuranceIncluded);
}

function selectPromoOffer(code, discountAmt, desc) {
    appliedDiscount = parseFloat(discountAmt) || 0;
    appliedPromoCodeName = code;

    // Highlight active radio and card
    document.querySelectorAll('input[name="promo_radio"]').forEach(function(r) {
        r.checked = (r.value === code);
    });
    document.querySelectorAll('.promo-offer-item').forEach(function(item) {
        item.classList.remove('active');
        item.style.borderColor = '#e2e8f0';
        item.style.background = '#ffffff';
    });
    var selectedRadio = document.getElementById('promo_' + code);
    if (selectedRadio) {
        selectedRadio.checked = true;
        var parentItem = selectedRadio.closest('.promo-offer-item');
        if (parentItem) {
            parentItem.classList.add('active');
            parentItem.style.borderColor = '#22c55e';
            parentItem.style.background = '#f0fdf4';
        }
    }

    // Update applied banner
    var banner = document.getElementById('promoAppliedBanner');
    if (banner) banner.style.display = 'block';
    var dispCode = document.getElementById('dispAppliedCode');
    if (dispCode) dispCode.textContent = code;
    var dispSavings = document.getElementById('dispAppliedSavings');
    if (dispSavings) dispSavings.textContent = appliedDiscount.toLocaleString('en-IN');

    var msg = document.getElementById('promoMsg');
    if (msg) {
        msg.style.display = 'block';
        msg.style.color = '#16a34a';
        msg.textContent = 'Promo code ' + code + ' applied! Saved ₹' + appliedDiscount.toLocaleString('en-IN') + '.';
    }

    recalculateAllFares();
}

function applyCustomPromoCode() {
    var input = document.getElementById('promoCodeInput');
    if (!input) return;
    var code = input.value.trim().toUpperCase();
    var msg = document.getElementById('promoMsg');

    if (!code) {
        if (msg) {
            msg.style.display = 'block';
            msg.style.color = '#dc2626';
            msg.textContent = 'Please enter a valid promo code.';
        }
        return;
    }

    var discountMap = {
        'VOYOGO500': 500,
        'ATRBL': 450,
        'ZEROFEE': 350,
        'RAINYDEAL': 218,
        'ATAUDD': 200,
        'ATDBS': 200,
        'ATFLY': 18,
        'SWADES': 500
    };

    var disc = discountMap[code] || 150;

    selectPromoOffer(code, disc, 'Promo code applied.');
}

function recalculateAllFares() {
    var insuranceAmount = isInsuranceIncluded ? (insurancePerPax * totalPax) : 0;
    
    // SSR addons
    var bagSel = document.getElementById('extraBaggageSelect');
    var baggagePrice = parseFloat(bagSel ? (bagSel.value || 0) : 0);
    var mealSel = document.getElementById('mealSelect');
    var mealPrice = parseFloat(mealSel ? (mealSel.value || 0) : 0);
    var totalAddons = baggagePrice + mealPrice;

    var ssrRow = document.getElementById('summaryAddonsRow');
    if (ssrRow) {
        ssrRow.style.display = (totalAddons > 0) ? 'flex' : 'none';
        var ssrDisp = document.getElementById('summaryAddons');
        if (ssrDisp) ssrDisp.textContent = totalAddons.toLocaleString('en-IN');
    }

    // Discount row in Fare Details
    var discRow = document.getElementById('summaryDiscountRow');
    if (discRow) {
        discRow.style.display = (appliedDiscount > 0) ? 'flex' : 'none';
        var discDisp = document.getElementById('summaryDiscount');
        if (discDisp) discDisp.textContent = appliedDiscount.toLocaleString('en-IN');
    }

    var grandTotal = Math.max(0, baseFlightFare + taxesFare + insuranceAmount + totalAddons - appliedDiscount);

    var totalDisp = document.getElementById('summaryTotalAmount');
    if (totalDisp) totalDisp.textContent = grandTotal.toLocaleString('en-IN');

    var btnPayAmt = document.getElementById('btnPayAmount');
    if (btnPayAmt) btnPayAmt.textContent = grandTotal.toLocaleString('en-IN');

    var formTotal = document.getElementById('form_total_amount');
    if (formTotal) formTotal.value = grandTotal;

    var formIns = document.getElementById('form_insurance_amount');
    if (formIns) formIns.value = insuranceAmount;

    var formDisc = document.getElementById('form_discount_amount');
    if (formDisc) formDisc.value = appliedDiscount;

    var formPromo = document.getElementById('form_promo_code');
    if (formPromo) formPromo.value = appliedPromoCodeName;
}

function calculateTotalAddons() {
    var bagSel = document.getElementById('extraBaggageSelect');
    var optBag = bagSel ? bagSel.options[bagSel.selectedIndex] : null;
    var bagCode = optBag ? (optBag.getAttribute('data-code') || '') : '';
    var bagDesc = optBag ? (optBag.getAttribute('data-desc') || '') : '';
    var baggagePrice = parseFloat(bagSel ? (bagSel.value || 0) : 0);

    var mealSel = document.getElementById('mealSelect');
    var optMeal = mealSel ? mealSel.options[mealSel.selectedIndex] : null;
    var mealCode = optMeal ? (optMeal.getAttribute('data-code') || '') : '';
    var mealDesc = optMeal ? (optMeal.getAttribute('data-desc') || '') : '';
    var mealPrice = parseFloat(mealSel ? (mealSel.value || 0) : 0);

    if (document.getElementById('ssr_baggage_code')) document.getElementById('ssr_baggage_code').value = baggagePrice > 0 ? bagCode : '';
    if (document.getElementById('ssr_baggage_amount')) document.getElementById('ssr_baggage_amount').value = baggagePrice;
    if (document.getElementById('ssr_baggage_desc')) document.getElementById('ssr_baggage_desc').value = baggagePrice > 0 ? bagDesc : '';

    if (document.getElementById('ssr_meal_code')) document.getElementById('ssr_meal_code').value = mealPrice > 0 ? mealCode : '';
    if (document.getElementById('ssr_meal_amount')) document.getElementById('ssr_meal_amount').value = mealPrice;
    if (document.getElementById('ssr_meal_desc')) document.getElementById('ssr_meal_desc').value = mealPrice > 0 ? mealDesc : '';

    recalculateAllFares();
}

function toggleGstFields() {
    var isChecked = document.getElementById('gstToggle').checked;
    document.getElementById('gstFieldsSection').style.display = isChecked ? 'grid' : 'none';
}

var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;

// In-page Login Success Handler (Called by Firebase OTP in header without page reload)
window.onBookingReviewLoginSuccess = function(user) {
    isUserLoggedIn = true;
    if (user.name) {
        var nameInput = document.querySelector('input[name="contact_name"]');
        if (nameInput) nameInput.value = user.name;
    }
    if (user.email) {
        var emailInput = document.querySelector('input[name="contact_email"]');
        if (emailInput) emailInput.value = user.email;
    }
    if (user.phone) {
        var phoneInput = document.querySelector('input[name="contact_phone"]');
        var clean = user.phone.replace(/^\+91/, '');
        if (phoneInput) phoneInput.value = clean;
    }

    var banner = document.getElementById('flightLoginBanner');
    if (banner) {
        banner.style.background = '#f0fdf4';
        banner.style.border = '1px solid #bbf7d0';
        banner.style.boxShadow = 'none';
        banner.innerHTML = '<div style="display: flex; align-items: center; gap: 14px;">' +
            '<div style="width: 40px; height: 40px; border-radius: 50%; background: #16a34a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;"><i class="fa-solid fa-circle-check"></i></div>' +
            '<div><div style="font-size: 14.5px; font-weight: 800; color: #166534;">Logged in as ' + (user.name || user.phone) + '</div>' +
            '<div style="font-size: 12.5px; color: #15803d;">Your verified contact details have been applied. You can now proceed to payment!</div></div>' +
            '</div>' +
            '<span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> Phone Verified</span>';
    }
};

// Prompt login on page load if guest
document.addEventListener('DOMContentLoaded', function() {
    if (!isUserLoggedIn) {
        setTimeout(function() {
            if (!isUserLoggedIn && typeof window.triggerBookingLogin === 'function') {
                window.triggerBookingLogin('Please log in with mobile OTP to continue your flight booking.');
            }
        }, 1200);
    }
});

document.getElementById('payRazorpayBtn').addEventListener('click', function(e) {
    e.preventDefault();

    if (!isUserLoggedIn) {
        if (typeof window.triggerBookingLogin === 'function') {
            window.triggerBookingLogin('Please sign in with mobile OTP to complete payment and issue your flight ticket.');
        } else {
            alert('Please sign in to complete payment.');
        }
        return;
    }

    var finalAmount = parseFloat(document.getElementById('form_total_amount').value);
    var amountInPaise = Math.round(finalAmount * 100);
    var contactName = document.querySelector('input[name="contact_name"]').value;
    var contactEmail = document.querySelector('input[name="contact_email"]').value;
    var contactPhone = document.querySelector('input[name="contact_phone"]').value;

    if (!contactName || !contactEmail || !contactPhone) {
        alert('Please complete all required contact details.');
        return;
    }

    var options = {
        "key": "<?php echo !empty($razorpay_settings['razorpay_key_id']) ? htmlspecialchars($razorpay_settings['razorpay_key_id']) : 'rzp_test_TTVGSNKy0V1o7B'; ?>",
        "amount": amountInPaise,
        "currency": "<?php echo !empty($razorpay_settings['currency']) ? htmlspecialchars($razorpay_settings['currency']) : 'INR'; ?>",
        "name": "<?php echo !empty($razorpay_settings['merchant_name']) ? htmlspecialchars($razorpay_settings['merchant_name']) : 'Voyogo Travels'; ?>",
        "description": "Flight Ticket Booking - <?php echo htmlspecialchars($flight['flight_number']); ?>",
        "image": "<?php echo base_url('assets/images/logo.png'); ?>",
        "handler": function (response){
            showProcessingModal("Payment Verified (HTTP 200 OK)! Generating your Official Flight E-Ticket...");
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('bookingForm').submit();
        },
        "prefill": {
            "name": contactName,
            "email": contactEmail,
            "contact": contactPhone
        },
        "theme": {
            "color": "<?php echo !empty($razorpay_settings['theme_color']) ? htmlspecialchars($razorpay_settings['theme_color']) : '#0d3470'; ?>"
        },
        "modal": {
            "ondismiss": function() {
                if (confirm("Razorpay Payment Gateway Closed. Would you like to finish test booking using Test Payment Mode?")) {
                    showProcessingModal("Confirming Test Booking & Generating E-Ticket...");
                    document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
                    document.getElementById('bookingForm').submit();
                }
            }
        }
    };

    try {
        var rzp1 = new Razorpay(options);
        rzp1.open();
    } catch(err) {
        showProcessingModal("Processing Booking Confirmation...");
        document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
        document.getElementById('bookingForm').submit();
    }
});

function calculatePassengerAge(input) {
    if (!input || !input.value) return;
    var travelDateStr = input.getAttribute('data-travel-date') || '<?php echo date("Y-m-d"); ?>';
    var dob = new Date(input.value);
    var travel = new Date(travelDateStr);
    if (isNaN(dob.getTime()) || isNaN(travel.getTime())) return;
    var age = travel.getFullYear() - dob.getFullYear();
    var m = travel.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && travel.getDate() < dob.getDate())) {
        age--;
    }
    age = Math.max(0, age);
    var card = input.closest('.passenger-card');
    if (card) {
        var ageInput = card.querySelector('.age-input');
        if (ageInput) {
            ageInput.value = age;
        }
    }
}

function showProcessingModal(message) {
    var overlay = document.getElementById('paymentProcessingOverlay');
    if (overlay) {
        document.getElementById('processingModalMsg').innerText = message || "Payment Verified! Generating E-Ticket...";
        overlay.style.display = 'flex';
    }
}
</script>

<!-- Payment Processing Fullscreen Modal Overlay -->
<div id="paymentProcessingOverlay" style="display: none; position: fixed; inset: 0; background: rgba(13, 52, 112, 0.94); z-index: 999999; backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; flex-direction: column; color: #ffffff; text-align: center; padding: 20px;">
    <div style="background: #ffffff; color: #0d3470; border-radius: 20px; padding: 40px 32px; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #eff6ff; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 3px solid #dbeafe;">
            <i class="fa-solid fa-plane-departure fa-beat" style="font-size: 30px; color: #2563eb;"></i>
        </div>
        <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px;">Processing Your Booking</h3>
        <p id="processingModalMsg" style="font-size: 14px; color: #64748b; margin-bottom: 24px; font-weight: 500;">Payment Verified! Generating your Official Flight E-Ticket & PNR...</p>
        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden; position: relative;">
            <div style="height: 100%; width: 75%; background: linear-gradient(90deg, #2563eb, #38bdf8); border-radius: 4px; animation: pulseProgress 1.5s infinite ease-in-out;"></div>
        </div>
        <div style="font-size: 12px; color: #94a3b8; margin-top: 14px;">Please do not refresh or close this window.</div>
    </div>
</div>
<style>
.flight-specs-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
    padding: 14px 24px;
    border-radius: 8px;
    border: 1px solid #edf2f7;
}
.spec-col {
    flex: 1;
    min-width: 0;
}
.spec-col-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
    letter-spacing: -0.2px;
}
.spec-col-value {
    font-size: 13.5px;
    color: #334155;
    font-weight: 500;
}
.spec-divider {
    width: 2px;
    height: 30px;
    background: #0ea5e9;
    border-radius: 2px;
    margin: 0 20px;
    flex-shrink: 0;
}
@media (max-width: 640px) {
    .flight-specs-strip {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 16px !important;
        padding: 14px 16px !important;
    }
    .spec-divider {
        display: none !important;
    }
}
@keyframes pulseProgress {
    0% { width: 30%; }
    50% { width: 90%; }
    100% { width: 30%; }
}

/* Fare Details & Toggleable Subitems (Screenshots 1 & 2) */
.f-fare-details-box {
    transition: box-shadow 0.2s ease;
}
.f-fare-parent {
    user-select: none;
    transition: color 0.15s ease;
}
.f-fare-parent:hover .f-fare-label {
    color: #0284c7 !important;
}
.f-fare-toggle-icon {
    font-size: 14px;
    color: #64748b;
    transition: transform 0.15s ease, color 0.15s ease;
}
.f-fare-parent:hover .f-fare-toggle-icon {
    color: #0284c7;
}

/* Promo Code Card & Selectable Offers (Screenshot 1) */
.promo-card {
    transition: box-shadow 0.2s ease;
}
.promo-offer-item {
    transition: all 0.2s ease;
}
.promo-offer-item:hover {
    border-color: #86efac !important;
    background: #f9fdfa !important;
}
.promo-offer-item.active {
    border-color: #22c55e !important;
    background: #f0fdf4 !important;
}
.back-to-search-btn:hover {
    color: #0369a1 !important;
    background: #e0f2fe !important;
}
</style>
