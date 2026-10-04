<?php
    $isUserLoggedIn   = isset($this->session) && $this->session->userdata('user_logged_in');
    $sessionUserName  = $isUserLoggedIn ? ($this->session->userdata('user_name') ?: '') : '';
    $sessionUserEmail = $isUserLoggedIn ? ($this->session->userdata('user_email') ?: '') : '';
    $sessionUserPhone = $isUserLoggedIn ? ($this->session->userdata('user_phone') ?: '') : '';
    $cleanPhone       = preg_replace('/^\+91/', '', $sessionUserPhone);
    $savedReview      = !empty($saved_review_post) ? $saved_review_post : ($this->session->userdata('flight_review_post') ?: array());

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

    if (!empty($flight['total_base_fare']) && (float)$flight['total_base_fare'] > 0) {
        $fBaseFare = (float)$flight['total_base_fare'];
        $fTaxes = max(0, $reviewPrice - $fBaseFare);
    }
    if (!empty($flight['itemized_taxes']) && is_array($flight['itemized_taxes']) && count($flight['itemized_taxes']) > 0) {
        $airlineTaxes = $flight['itemized_taxes'];
    }

    $adultBaseFare = round($fBaseFare * ($adult_count / $total_travelers_review));
    $childBaseFare = ($child_count > 0) ? round($fBaseFare * ($child_count / $total_travelers_review)) : 0;
    $infantBaseFare = ($infant_count > 0) ? max(0, $fBaseFare - $adultBaseFare - $childBaseFare) : 0;

    $insurancePerPax = 199;
    $initialInsurance = 0; // Default unselected
    $initialDiscount = 18; // Default ATFLY discount matching Screenshot 1
    $initialGrandTotal = $fBaseFare + $fTaxes + $initialInsurance - $initialDiscount;
    $retReviewPrice = !empty($return_flight['price']) ? (float)$return_flight['price'] : $reviewPrice;

    // Airport Database for detailed connecting flight segments (Screenshot 2)
    $airportDb = array(
        'DEL' => array('city' => 'New Delhi', 'name' => 'Indira Gandhi International Airport', 'country' => 'India', 'terminal' => 'Terminal 2'),
        'BOM' => array('city' => 'Mumbai', 'name' => 'Chhatrapati Shivaji Maharaj International airport', 'country' => 'India', 'terminal' => 'Terminal 2'),
        'HYD' => array('city' => 'Hyderabad', 'name' => 'Rajiv Gandhi International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'BHO' => array('city' => 'Bhopal', 'name' => 'Raja Bhoj Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'BLR' => array('city' => 'Bengaluru', 'name' => 'Kempegowda International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'MAA' => array('city' => 'Chennai', 'name' => 'Chennai International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'CCU' => array('city' => 'Kolkata', 'name' => 'Netaji Subhash Chandra Bose International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'GOI' => array('city' => 'Goa', 'name' => 'Dabolim Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'GOX' => array('city' => 'Goa', 'name' => 'Manohar International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'AMD' => array('city' => 'Ahmedabad', 'name' => 'Sardar Vallabhbhai Patel International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'PNQ' => array('city' => 'Pune', 'name' => 'Pune International Airport', 'country' => 'India', 'terminal' => 'Terminal 1'),
        'JAI' => array('city' => 'Jaipur', 'name' => 'Jaipur International Airport', 'country' => 'India', 'terminal' => 'Terminal 2'),
        'COK' => array('city' => 'Kochi', 'name' => 'Cochin International Airport', 'country' => 'India', 'terminal' => 'Terminal 3'),
        'DXB' => array('city' => 'Dubai', 'name' => 'Dubai International Airport', 'country' => 'United Arab Emirates', 'terminal' => 'Terminal 3'),
        'SIN' => array('city' => 'Singapore', 'name' => 'Singapore Changi Airport', 'country' => 'Singapore', 'terminal' => 'Terminal 3'),
        'BKK' => array('city' => 'Bangkok', 'name' => 'Suvarnabhumi Airport', 'country' => 'Thailand', 'terminal' => 'Terminal 1'),
        'LHR' => array('city' => 'London', 'name' => 'Heathrow Airport', 'country' => 'United Kingdom', 'terminal' => 'Terminal 2')
    );

    if (!function_exists('parseTimeToMins')) {
        function parseTimeToMins($timeStr) {
            if (preg_match('/(\d{1,2}):(\d{2})/', (string)$timeStr, $m)) {
                return (int)$m[1] * 60 + (int)$m[2];
            }
            return 0;
        }
    }
    if (!function_exists('minsToTimeStr')) {
        function minsToTimeStr($mins) {
            $mins = ($mins % 1440 + 1440) % 1440;
            $h = floor($mins / 60);
            $m = $mins % 60;
            return sprintf('%02d:%02d', $h, $m);
        }
    }
    if (!function_exists('formatMinsDuration')) {
        function formatMinsDuration($mins) {
            $h = floor($mins / 60);
            $m = $mins % 60;
            if ($h > 0 && $m > 0) {
                return sprintf('%02d Hr. %02d Min.', $h, $m);
            } elseif ($h > 0) {
                return sprintf('%02d Hr.', $h);
            } else {
                return sprintf('%02d Min.', $m);
            }
        }
    }
    if (!function_exists('buildConnectingFlightSegments')) {
        function buildConnectingFlightSegments($flt, $airportDb) {
            $depMins = parseTimeToMins($flt['departure_time'] ?? '07:15');
            $arrMins = parseTimeToMins($flt['arrival_time'] ?? '12:45');
            if ($arrMins < $depMins) {
                $arrMins += 1440;
            }
            $totalJourneyMins = $arrMins - $depMins;
            if ($totalJourneyMins <= 60) {
                $totalJourneyMins = 330; // fallback 5h 30m
            }

            // Proportional leg division:
            // Leg 1: ~38% of total journey
            // Layover: ~30% of total journey
            // Leg 2: remaining (~32%)
            $leg1Mins = max(60, (int)round($totalJourneyMins * 0.38));
            $layoverMins = max(45, (int)round($totalJourneyMins * 0.30));
            $leg2Mins = $totalJourneyMins - $leg1Mins - $layoverMins;
            if ($leg2Mins < 45) {
                $leg2Mins = 55;
                $layoverMins = max(40, $totalJourneyMins - $leg1Mins - $leg2Mins);
            }

            $leg1Dep = $flt['departure_time'] ?? '07:15';
            $leg1ArrMins = $depMins + $leg1Mins;
            $leg1Arr = minsToTimeStr($leg1ArrMins);

            $leg2DepMins = $leg1ArrMins + $layoverMins;
            $leg2Dep = minsToTimeStr($leg2DepMins);
            $leg2Arr = $flt['arrival_time'] ?? '12:45';

            $fromCode = strtoupper($flt['from_code'] ?? 'DEL');
            $toCode = strtoupper($flt['to_code'] ?? 'BOM');
            $viaCode = !empty($flt['via']) ? strtoupper($flt['via']) : 'HYD';
            if ($viaCode === $fromCode || $viaCode === $toCode) {
                $viaCode = ($fromCode === 'HYD' || $toCode === 'HYD') ? 'BHO' : 'HYD';
            }

            $fromInfo = $airportDb[$fromCode] ?? array('city' => $fromCode, 'name' => ($flt['from_airport'] ?? $fromCode . ' Airport'), 'country' => 'India', 'terminal' => ($flt['from_terminal'] ?? 'Terminal 2'));
            $toInfo = $airportDb[$toCode] ?? array('city' => $toCode, 'name' => ($flt['to_airport'] ?? $toCode . ' Airport'), 'country' => 'India', 'terminal' => ($flt['to_terminal'] ?? 'Terminal 1'));
            $viaInfo = $airportDb[$viaCode] ?? array('city' => $viaCode, 'name' => $viaCode . ' Airport', 'country' => 'India', 'terminal' => 'Terminal 1');

            // Connecting flight number
            $fn1 = $flt['flight_number'] ?? '6E-5021';
            $fn2 = $fn1;
            if (preg_match('/^([A-Z0-9]{2})[-\s]?(\d+)$/i', trim($fn1), $fnM)) {
                $fn2 = strtoupper($fnM[1]) . '-' . ((int)$fnM[2] + 18);
            } else {
                $fn2 = $fn1 . 'B';
            }

            return array(
                'from_info' => $fromInfo,
                'to_info' => $toInfo,
                'via_info' => $viaInfo,
                'from_code' => $fromCode,
                'to_code' => $toCode,
                'via_code' => $viaCode,
                'leg1_fn' => $fn1,
                'leg2_fn' => $fn2,
                'leg1_dep' => $leg1Dep,
                'leg1_arr' => $leg1Arr,
                'leg1_duration' => formatMinsDuration($leg1Mins),
                'layover_duration' => sprintf('%02dh:%02dm', floor($layoverMins / 60), $layoverMins % 60),
                'leg2_dep' => $leg2Dep,
                'leg2_arr' => $leg2Arr,
                'leg2_duration' => formatMinsDuration($leg2Mins),
            );
        }
    }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div style="background-color: #f4f7fe; padding: 25px 0 60px 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        
        <!-- Header Step Progress Bar -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 14px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.05); display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-plane-departure" style="color: #2563eb;"></i> Review Your Flight Itinerary
                </h1>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">
                    Complete passenger details & select fare options before add-on services
                </p>
            </div>
            <div style="display: flex; gap: 16px; font-weight: 700; font-size: 13px; flex-wrap: wrap;">
                <span style="color: #16a34a; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle-check"></i> 1. Flight Selected</span>
                <span style="color: #2563eb; background: #eff6ff; padding: 6px 14px; border-radius: 20px; border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-circle-dot"></i> 2. Review & Pax Details</span>
                <span style="color: #94a3b8; display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-circle"></i> 3. Add-on Services</span>
                <span style="color: #94a3b8; display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-circle"></i> 4. Payment</span>
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

        <!-- Dedicated Form to accurately return to search results with exact criteria -->
        <form id="backToSearchForm" action="<?php echo site_url('flight/search'); ?>" method="POST" style="display: none;">
            <input type="hidden" name="tripType" value="<?php echo !empty($is_roundtrip) ? 'roundtrip' : 'oneway'; ?>">
            <input type="hidden" name="from_city" value="<?php echo htmlspecialchars($search_query['from'] ?? ($flight['from_airport'] ?? 'Delhi (DEL)')); ?>">
            <input type="hidden" name="to_city" value="<?php echo htmlspecialchars($search_query['to'] ?? ($flight['to_airport'] ?? 'Mumbai (BOM)')); ?>">
            <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($search_query['from_code'] ?? ($flight['from_code'] ?? 'DEL')); ?>">
            <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($search_query['to_code'] ?? ($flight['to_code'] ?? 'BOM')); ?>">
            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($search_query['date'] ?? ($flight['departure_date'] ?? date('Y-m-d'))); ?>">
            <?php if (!empty($is_roundtrip)): ?>
            <input type="hidden" name="return_date" value="<?php echo htmlspecialchars($search_query['return_date'] ?? ($return_flight['departure_date'] ?? date('Y-m-d', strtotime('+7 days')))); ?>">
            <?php endif; ?>
            <input type="hidden" name="adults" value="<?php echo (int)($search_query['adults'] ?? 1); ?>">
            <input type="hidden" name="children" value="<?php echo (int)($search_query['children'] ?? 0); ?>">
            <input type="hidden" name="infants" value="<?php echo (int)($search_query['infants'] ?? 0); ?>">
            <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">
        </form>

        <!-- Subheader: Review your flight details & Back to Search (Screenshot 1) -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 0 4px;">
            <h2 style="font-size: 19px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                Review your flight details
            </h2>
            <a href="<?php echo htmlspecialchars($back_search_url ?? site_url('flight')); ?>" onclick="goBackToSearch(); return false;" class="back-to-search-btn" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; padding: 6px 12px; border-radius: 6px; transition: all 0.15s ease;">
                <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i> Back to Search
            </a>
        </div>

        <?php if (!empty($fare_updated)): ?>
        <!-- Real-Time Airline Fare Change Alert Banner -->
        <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #f59e0b; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 14px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.12);">
            <div style="background: #f59e0b; color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 16px; margin-top: 2px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; flex-wrap: wrap; gap: 8px;">
                    <strong style="color: #92400e; font-size: 14.5px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-bolt" style="color: #d97706;"></i> Live Airline Fare Update
                    </strong>
                    <span style="background: #d97706; color: #ffffff; font-size: 11.5px; font-weight: 700; padding: 2px 8px; border-radius: 12px; letter-spacing: 0.3px;">
                        REAL-TIME GDS FARE
                    </span>
                </div>
                <p style="margin: 0; font-size: 13.5px; color: #78350f; line-height: 1.5;">
                    <?php echo htmlspecialchars($fare_change_msg ?? 'The airline has updated the fare based on real-time seat availability.'); ?>
                </p>
                <div style="margin-top: 8px; font-size: 12.5px; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <span>Previous Fare: <del style="color: #b45309;">₹ <?php echo number_format((float)($old_fare ?? 0)); ?></del></span>
                    <span>Updated Live Fare: <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format((float)($new_fare ?? $flight['price'])); ?></strong></span>
                    <span style="font-weight: 500; color: #78350f;">(Guaranteed seat reservation at current inventory)</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="flight-review-layout-grid" style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px;">
            
            <!-- Left Column: Flight Details & Passenger Form -->
            <div>
                
                <!-- Flight Summary Card -->
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

                $isOnwardConnecting = !empty($flight['stops']) && (int)$flight['stops'] > 0;
                ?>

                <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                    <?php if ($isOnwardConnecting): ?>
                        <?php $segData = buildConnectingFlightSegments($flight, $airportDb); ?>
                        <!-- 1+ Stop Connecting Flight View (Screenshot 2 Match) -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #edf2f7; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                                    <span><?php echo htmlspecialchars($segData['from_info']['city']); ?></span>
                                    <i class="fa-solid fa-arrow-right" style="font-size: 15px; color: #0284c7;"></i>
                                    <span><?php echo htmlspecialchars($segData['to_info']['city']); ?></span>
                                </h3>
                                <div style="font-size: 13px; color: #64748b; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span><i class="fa-regular fa-calendar-days" style="color: #94a3b8; margin-right: 3px;"></i> <?php echo date('D, d M y', strtotime($flight['departure_date'])); ?></span>
                                    <span style="color: #cbd5e1;">&bull;</span>
                                    <span>Duration <?php echo htmlspecialchars($flight['duration'] ?? '05h 30m'); ?></span>
                                    <span style="color: #cbd5e1;">&bull;</span>
                                    <span style="color: #b45309; font-weight: 700;"><?php echo (int)$flight['stops'] . ' ' . ((int)$flight['stops'] > 1 ? 'Stops' : 'Stop'); ?></span>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <a href="javascript:void(0);" onclick="scrollToFareOptions('onward');" style="color: #0284c7; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                                    <i class="fa-solid fa-circle-info"></i> Fare Rules
                                </a>
                                <?php if (!empty($flight['fare_type'])): ?>
                                    <span style="background: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 20px; font-weight: 800; font-size: 11.5px; border: 1px solid <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                        <i class="<?php echo (stripos($flight['fare_type'], 'upfront') !== false || stripos($flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($flight['fare_type']); ?> Fare
                                    </span>
                                <?php endif; ?>
                                <span style="border: 1.5px solid #16a34a; color: #16a34a; background: #f0fdf4; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-rotate-left"></i> <?php echo !empty($flight['refundable']) ? 'Refundable' : 'Partially Refundable'; ?>
                                </span>
                                <!-- Maximize / Minimize Icon (Screenshot 2 Match) -->
                                <button type="button" id="btnToggle_onward" onclick="toggleFlightBox('onward')" title="Minimize" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #334155; margin-left: 6px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <i id="iconToggle_onward" class="fa-solid fa-chevron-up" style="font-size: 13px;"></i>
                                </button>
                            </div>
                        </div>

                        <div id="flightBoxBody_onward">
                        <!-- Segment 1 (Origin -> Layover) -->
                        <div style="padding-top: 18px;">
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($flight['airline_logo']); ?>" alt="logo" style="height: 36px; width: 36px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($flight['airline_name']); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($flight['airline_name']); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($segData['leg1_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($aircraftDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($travelClassDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($checkinDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($cabinDisplay); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding: 0 4px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg1_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['from_info']['city']); ?> [<?php echo htmlspecialchars($segData['from_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['from_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($segData['from_info']['terminal']); ?></span>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($segData['leg1_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #38bdf8;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #0284c7; font-size: 15px; transform: rotate(0deg); background: #ffffff; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg1_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['via_info']['city']); ?> [<?php echo htmlspecialchars($segData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['via_info']['name']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Layover / Connecting Plane Banner (Screenshot 2 Exact Match) -->
                        <div style="background: linear-gradient(90deg, #e0f2fe 0%, #bae6fd 50%, #e0f2fe 100%); border: 1px solid #7dd3fc; color: #0369a1; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; margin: 20px 0; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);">
                            <i class="fa-solid fa-circle-info" style="font-size: 14px; color: #0284c7;"></i>
                            <span>Change planes at <strong><?php echo htmlspecialchars($segData['via_info']['city']); ?> | <?php echo htmlspecialchars($segData['via_info']['city']); ?> | IN | India (<?php echo htmlspecialchars($segData['via_code']); ?>)</strong>, Connecting Time: <strong><?php echo htmlspecialchars($segData['layover_duration']); ?></strong></span>
                        </div>

                        <!-- Segment 2 (Layover -> Destination) -->
                        <div>
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($flight['airline_logo']); ?>" alt="logo" style="height: 36px; width: 36px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($flight['airline_name']); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($flight['airline_name']); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($segData['leg2_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($aircraftDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($travelClassDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($checkinDisplay); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($cabinDisplay); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding: 0 4px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg2_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['via_info']['city']); ?> [<?php echo htmlspecialchars($segData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['via_info']['name']); ?></div>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($segData['leg2_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #38bdf8;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #0284c7; font-size: 15px; transform: rotate(0deg); background: #ffffff; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($segData['leg2_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($segData['to_info']['city']); ?> [<?php echo htmlspecialchars($segData['to_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($segData['to_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($segData['to_info']['terminal']); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Notice -->
                        <div style="margin-top: 20px; display: flex; align-items: center; justify-content: flex-start;">
                            <span style="background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-circle-info"></i> Meal, Seat are chargeable.
                            </span>
                        </div>

                    <?php else: ?>
                        <!-- Standard Non-Stop Flight View -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 18px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <img src="<?php echo htmlspecialchars($flight['airline_logo']); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($flight['airline_name']); ?>&background=0d3470&color=fff';">
                                <div>
                                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0d3470;">
                                        <?php echo htmlspecialchars($flight['airline_name']); ?> 
                                        <span style="font-size: 14px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($flight['flight_number']); ?>)</span>
                                    </h3>
                                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                        Aircraft: <?php echo htmlspecialchars($aircraftDisplay); ?> | Cabin: <strong style="color: #0d3470;"><?php echo htmlspecialchars($travelClassDisplay); ?></strong>
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                                <a href="javascript:void(0);" onclick="scrollToFareOptions('onward');" style="color: #0284c7; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                                    <i class="fa-solid fa-circle-info"></i> Fare Rules
                                </a>
                                <span style="background: #eff6ff; color: #1d4ed8; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px; display: inline-block;">
                                    <?php echo date('D, d M Y', strtotime($flight['departure_date'])); ?>
                                </span>
                                <?php if (!empty($flight['fare_type'])): ?>
                                    <span style="background: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 12px; font-weight: 800; font-size: 11px; border: 1px solid <?php echo (stripos($flight['fare_type'], 'flex') !== false || stripos($flight['fare_type'], 'super') !== false || stripos($flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                        <i class="<?php echo (stripos($flight['fare_type'], 'upfront') !== false || stripos($flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 2px;"></i> <?php echo htmlspecialchars($flight['fare_type']); ?> Fare
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($flight['refundable'])): ?>
                                    <span style="background: #f0fdf4; color: #15803d; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11px; border: 1px solid #bbf7d0;">
                                        <i class="fa-solid fa-rotate-left"></i> Refundable
                                    </span>
                                <?php endif; ?>

                                <!-- Maximize / Minimize Icon (Screenshot 2 Match) -->
                                <button type="button" id="btnToggle_onward" onclick="toggleFlightBox('onward')" title="Minimize" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #334155; margin-left: 6px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <i id="iconToggle_onward" class="fa-solid fa-chevron-up" style="font-size: 13px;"></i>
                                </button>
                            </div>
                        </div>

                        <div id="flightBoxBody_onward">
                        <!-- Flight Timing & Sector Grid -->
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 18px 22px; border-radius: 10px; border: 1px solid #edf2f7; margin-bottom: 20px;">
                            <div style="text-align: left; max-width: 32%;">
                                <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($flight['departure_time']); ?></span>
                                <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($flight['from_code']); ?></div>
                                <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($flight['from_airport'] ?? 'Airport'); ?></div>
                                <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($flight['from_terminal'] ?? 'Terminal 2'); ?></span>
                            </div>

                            <div style="text-align: center; flex: 1; margin: 0 20px;">
                                <span style="font-size: 12px; color: #64748b; font-weight: 700;"><?php echo htmlspecialchars($flight['duration'] ?? '2h 15m'); ?></span>
                                <div style="height: 2px; background: #cbd5e1; margin: 8px 0; position: relative;">
                                    <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 48%; color: #2563eb; transform: rotate(0deg); font-size: 14px;"></i>
                                </div>
                                <span style="font-size: 11px; color: #16a34a; font-weight: 800; background: #f0fdf4; padding: 3px 10px; border-radius: 12px; border: 1px solid #86efac;">
                                    Non-Stop Direct
                                </span>
                            </div>

                            <div style="text-align: right; max-width: 32%;">
                                <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($flight['arrival_time']); ?></span>
                                <div style="font-size: 16px; font-weight: 800; color: #0d3470; margin-top: 4px;"><?php echo htmlspecialchars($flight['to_code']); ?></div>
                                <div style="font-size: 12px; color: #475569; font-weight: 500; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo htmlspecialchars($flight['to_airport'] ?? 'Airport'); ?></div>
                                <span style="display: inline-block; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 8px; border-radius: 4px; margin-top: 4px;"><?php echo htmlspecialchars($flight['to_terminal'] ?? 'Terminal 1'); ?></span>
                            </div>
                        </div>

                        <!-- Flight Specs & Baggage Allowance (4 Columns) -->
                        <div class="flight-specs-strip" style="margin-bottom: 16px;">
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
                    <?php endif; ?>

                    <!-- Cancellation & Date Change Policy (Brought Down, Always Visible) -->
                    <div id="cancellationPolicyTable" style="background: #f8fafc; padding: 16px 20px; border-radius: 8px; border: 1px solid #edf2f7; margin-top: 18px;">
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
                        </div> <!-- Closes #flightBoxBody_onward -->
                    </div>

                <?php if (!empty($return_flight)): ?>
                <!-- Return Flight Summary Card -->
                <?php
                $isReturnConnecting = !empty($return_flight['stops']) && (int)$return_flight['stops'] > 0;
                $retAircraft = !empty($return_flight['aircraft']) ? strtoupper(trim($return_flight['aircraft'])) : 'BOEING';
                $retClass = !empty($return_flight['cabin_class']) ? ucfirst(strtolower($return_flight['cabin_class'])) : $travelClassDisplay;
                $retCheckin = !empty($return_flight['checkin_baggage']) ? $return_flight['checkin_baggage'] : $checkinDisplay;
                $retCabin = !empty($return_flight['cabin_baggage']) ? $return_flight['cabin_baggage'] : $cabinDisplay;
                ?>
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                    <?php if ($isReturnConnecting): ?>
                        <?php $retSegData = buildConnectingFlightSegments($return_flight, $airportDb); ?>
                        <!-- Return 1+ Stop Connecting Flight View (Screenshot 2 Match) -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #edf2f7; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                                    <i class="fa-solid fa-plane-arrival" style="color: #10b981; font-size: 18px;"></i>
                                    <span><?php echo htmlspecialchars($retSegData['from_info']['city']); ?></span>
                                    <i class="fa-solid fa-arrow-right" style="font-size: 15px; color: #10b981;"></i>
                                    <span><?php echo htmlspecialchars($retSegData['to_info']['city']); ?></span>
                                </h3>
                                <div style="font-size: 13px; color: #64748b; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span><i class="fa-regular fa-calendar-days" style="color: #94a3b8; margin-right: 3px;"></i> <?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?></span>
                                    <span style="color: #cbd5e1;">&bull;</span>
                                    <span>Duration <?php echo htmlspecialchars($return_flight['duration'] ?? '05h 30m'); ?></span>
                                    <span style="color: #cbd5e1;">&bull;</span>
                                    <span style="color: #b45309; font-weight: 700;"><?php echo (int)$return_flight['stops'] . ' ' . ((int)$return_flight['stops'] > 1 ? 'Stops' : 'Stop'); ?></span>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <a href="javascript:void(0);" onclick="scrollToFareOptions('return');" style="color: #0284c7; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                                    <i class="fa-solid fa-circle-info"></i> Fare Rules
                                </a>
                                <?php if (!empty($return_flight['fare_type'])): ?>
                                    <span style="background: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 12px; font-weight: 800; font-size: 11.5px; border: 1px solid <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                        <i class="<?php echo (stripos($return_flight['fare_type'], 'upfront') !== false || stripos($return_flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($return_flight['fare_type']); ?> Fare
                                    </span>
                                <?php endif; ?>
                                <span style="border: 1.5px solid #16a34a; color: #16a34a; background: #f0fdf4; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-rotate-left"></i> <?php echo !empty($return_flight['refundable']) ? 'Refundable' : 'Partially Refundable'; ?>
                                </span>
                                <!-- Maximize / Minimize Icon (Screenshot 3 Match) -->
                                <button type="button" id="btnToggle_return" onclick="toggleFlightBox('return')" title="Minimize" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #334155; margin-left: 6px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <i id="iconToggle_return" class="fa-solid fa-chevron-up" style="font-size: 13px;"></i>
                                </button>
                            </div>
                        </div>

                        <div id="flightBoxBody_return">
                        <!-- Return Segment 1 (Origin -> Layover) -->
                        <div style="padding-top: 18px;">
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($return_flight['airline_logo']); ?>" alt="logo" style="height: 36px; width: 36px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($return_flight['airline_name']); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($return_flight['airline_name']); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($retSegData['leg1_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retAircraft); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retClass); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCheckin); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCabin); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding: 0 4px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg1_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['from_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['from_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['from_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($retSegData['from_info']['terminal']); ?></span>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($retSegData['leg1_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #10b981;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #10b981; font-size: 15px; transform: rotate(0deg); background: #ffffff; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg1_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['via_info']['name']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Layover / Connecting Plane Banner -->
                        <div style="background: linear-gradient(90deg, #ecfdf5 0%, #d1fae5 50%, #ecfdf5 100%); border: 1px solid #a7f3d0; color: #065f46; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center; margin: 20px 0; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);">
                            <i class="fa-solid fa-circle-info" style="font-size: 14px; color: #10b981;"></i>
                            <span>Change planes at <strong><?php echo htmlspecialchars($retSegData['via_info']['city']); ?> | <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> | IN | India (<?php echo htmlspecialchars($retSegData['via_code']); ?>)</strong>, Connecting Time: <strong><?php echo htmlspecialchars($retSegData['layover_duration']); ?></strong></span>
                        </div>

                        <!-- Return Segment 2 (Layover -> Destination) -->
                        <div>
                            <div class="seg-header-row" style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?php echo htmlspecialchars($return_flight['airline_logo']); ?>" alt="logo" style="height: 36px; width: 36px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($return_flight['airline_name']); ?>&background=0d3470&color=fff';">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <strong style="font-size: 16px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($return_flight['airline_name']); ?></strong>
                                        <span style="color: #94a3b8; font-weight: 400;">|</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($retSegData['leg2_fn']); ?></span>
                                    </div>
                                </div>
                                <div class="seg-specs-box">
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Aircraft</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retAircraft); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Travel Class</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retClass); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Check-In Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCheckin); ?></strong>
                                    </div>
                                    <div class="seg-specs-divider"></div>
                                    <div>
                                        <span style="color: #64748b; font-size: 11px; display: block; font-weight: 600;">Cabin Baggage</span>
                                        <strong style="color: #0f172a; font-size: 12.5px;"><?php echo htmlspecialchars($retCabin); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding: 0 4px;">
                                <div style="text-align: left; max-width: 38%;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg2_dep']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['via_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['via_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['via_info']['name']); ?></div>
                                </div>

                                <div style="text-align: center; flex: 1; margin: 0 20px;">
                                    <span style="font-size: 13px; color: #0f172a; font-weight: 800; display: block; margin-bottom: 6px;"><?php echo htmlspecialchars($retSegData['leg2_duration']); ?></span>
                                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 100%; border-top: 2px dashed #10b981;"></div>
                                        <i class="fa-solid fa-plane" style="position: absolute; color: #10b981; font-size: 15px; transform: rotate(0deg); background: #ffffff; padding: 0 6px;"></i>
                                    </div>
                                </div>

                                <div style="text-align: left; max-width: 38%; min-width: 190px;">
                                    <span style="font-size: 26px; font-weight: 900; color: #0f172a; display: block; line-height: 1.1;"><?php echo htmlspecialchars($retSegData['leg2_arr']); ?></span>
                                    <span style="font-size: 13px; color: #475569; font-weight: 600; display: block; margin-top: 3px;"><?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?></span>
                                    <div style="font-size: 14.5px; font-weight: 800; color: #0d3470; margin-top: 4px;">
                                        <?php echo htmlspecialchars($retSegData['to_info']['city']); ?> [<?php echo htmlspecialchars($retSegData['to_code']); ?>]
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;"><?php echo htmlspecialchars($retSegData['to_info']['name']); ?></div>
                                    <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #334155; margin-top: 4px;"><?php echo htmlspecialchars($retSegData['to_info']['terminal']); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Notice -->
                        <div style="margin-top: 20px; display: flex; align-items: center; justify-content: flex-start;">
                            <span style="background: #f0fdf4; color: #059669; border: 1px solid #a7f3d0; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-circle-info"></i> Meal, Seat are chargeable.
                            </span>
                        </div>
                    <?php else: ?>
                        <!-- Standard Return Non-Stop Flight View -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 18px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <img src="<?php echo htmlspecialchars($return_flight['airline_logo']); ?>" alt="logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 6px; padding: 2px; background: #f8fafc; border: 1px solid #e2e8f0;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($return_flight['airline_name']); ?>&background=0d3470&color=fff';">
                                <div>
                                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #0d3470;">
                                        <i class="fa-solid fa-plane-arrival" style="color: #10b981; font-size: 16px;"></i> Return Flight: <?php echo htmlspecialchars($return_flight['airline_name']); ?> 
                                        <span style="font-size: 14px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($return_flight['flight_number']); ?>)</span>
                                    </h3>
                                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                        Aircraft: <?php echo htmlspecialchars($retAircraft); ?> | Cabin: <strong style="color: #0d3470;"><?php echo htmlspecialchars($retClass); ?></strong>
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                                <a href="javascript:void(0);" onclick="scrollToFareOptions('return');" style="color: #0284c7; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; cursor: pointer;">
                                    <i class="fa-solid fa-circle-info"></i> Fare Rules
                                </a>
                                <span style="background: #f0fdf4; color: #15803d; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px; display: inline-block;">
                                    <?php echo date('D, d M Y', strtotime($return_flight['departure_date'])); ?>
                                </span>
                                <?php if (!empty($return_flight['fare_type'])): ?>
                                    <span style="background: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fef3c7' : '#f1f5f9'; ?>; color: <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#b45309' : '#334155'; ?>; padding: 5px 12px; border-radius: 12px; font-weight: 800; font-size: 11px; margin-left: 6px; border: 1px solid <?php echo (stripos($return_flight['fare_type'], 'flex') !== false || stripos($return_flight['fare_type'], 'super') !== false || stripos($return_flight['fare_type'], 'upfront') !== false) ? '#fcd34d' : '#e2e8f0'; ?>;">
                                        <i class="<?php echo (stripos($return_flight['fare_type'], 'upfront') !== false || stripos($return_flight['fare_type'], 'super') !== false) ? 'fa-solid fa-crown' : 'fa-solid fa-tag'; ?>" style="font-size: 10px; margin-right: 2px;"></i> <?php echo htmlspecialchars($return_flight['fare_type']); ?> Fare
                                    </span>
                                <?php endif; ?>

                                <!-- Maximize / Minimize Icon (Screenshot 3 Match) -->
                                <button type="button" id="btnToggle_return" onclick="toggleFlightBox('return')" title="Minimize" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #334155; margin-left: 6px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <i id="iconToggle_return" class="fa-solid fa-chevron-up" style="font-size: 13px;"></i>
                                </button>
                            </div>
                        </div>

                        <div id="flightBoxBody_return">
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
                                <span style="font-size: 11px; color: #16a34a; font-weight: 800; background: #f0fdf4; padding: 3px 10px; border-radius: 12px; border: 1px solid #86efac;">
                                    Non-Stop Direct
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
                    <?php endif; ?>

                    <!-- Cancellation & Date Change Policy for Return Flight (Per Pax) -->
                    <div id="returnCancellationPolicyTable" style="background: #f8fafc; padding: 16px 20px; border-radius: 8px; border: 1px solid #edf2f7; margin-top: 18px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <h4 style="font-size: 13.5px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-file-contract" style="color: #2563eb;"></i> Cancellation & Date Change Policy (Per Pax)
                            </h4>
                            <span style="font-size: 11px; font-weight: 700; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px;">
                                <?php echo !empty($return_flight['refundable']) ? 'Refundable' : 'Partially Refundable'; ?>
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
                                <?php 
                                $retRules = !empty($return_fare_rules['cancellation']) ? $return_fare_rules : (!empty($fare_rules['cancellation']) ? $fare_rules : null);
                                ?>
                                <?php if (!empty($retRules['cancellation'])): ?>
                                    <?php foreach ($retRules['cancellation'] as $idx => $rule): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 8px 12px; font-weight: 600; color: #475569;"><?php echo htmlspecialchars($rule['time']); ?></td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #dc2626;"><?php echo htmlspecialchars($rule['fee']); ?></td>
                                            <td style="padding: 8px 12px; font-weight: 700; color: #2563eb;"><?php echo htmlspecialchars($retRules['date_change'][$idx]['fee'] ?? '₹ 2,500 + Diff'); ?></td>
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
                    </div> <!-- Closes #flightBoxBody_return -->
                </div>
                <?php endif; ?>

                <!-- Form Section (Points to Step 3: Add-on Services) -->
                <form id="bookingForm" action="<?php echo site_url('flight/addons'); ?>" method="POST">
                    
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
                    <input type="hidden" name="base_fare" id="form_base_fare" value="<?php echo htmlspecialchars($fBaseFare); ?>">
                    <input type="hidden" name="taxes" value="<?php echo htmlspecialchars($fTaxes); ?>">
                    <input type="hidden" name="total_amount" id="form_total_amount" value="<?php echo htmlspecialchars($initialGrandTotal); ?>">
                    
                    <!-- More Fare Options Fields -->
                    <input type="hidden" name="fare_tier" id="form_fare_tier" value="Value">
                    <input type="hidden" name="fare_tier_price_delta" id="form_fare_tier_price_delta" value="0">
                    <input type="hidden" name="onward_fare_tier" id="form_onward_fare_tier" value="Value">
                    <input type="hidden" name="onward_fare_tier_price_delta" id="form_onward_fare_tier_price_delta" value="0">
                    <input type="hidden" name="return_fare_tier" id="form_return_fare_tier" value="Value">
                    <input type="hidden" name="return_fare_tier_price_delta" id="form_return_fare_tier_price_delta" value="0">
                    
                    <!-- Safety & Flexibility Fields -->
                    <input type="hidden" name="safety_cancellation_type" id="form_safety_cancellation_type" value="">
                    <input type="hidden" name="safety_cancellation_amount" id="form_safety_cancellation_amount" value="0">
                    <input type="hidden" name="travel_insurance_selected" id="form_travel_insurance_selected" value="0">
                    <input type="hidden" name="insurance_amount" id="form_insurance_amount" value="0">
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

                    <!-- 1. More Fare Options for Additional Benefits (Screenshot 2) -->
                    <div id="moreFareOptionsSection" style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0; scroll-margin-top: 85px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 16px 0;">
                            More Fare Options for Additional Benefits
                        </h3>

                        <!-- Route Sector Tabs -->
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <div id="fareTab_onward" class="fare-route-tab active" onclick="switchFareRouteSector('onward');" style="padding: 10px 18px; border-radius: 8px; border: 1.5px solid #0284c7; background: #f0f9ff; cursor: pointer; display: flex; flex-direction: column; transition: all 0.2s ease;">
                                <div class="tab-title" style="font-size: 13.5px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px;">
                                    <span><?php echo htmlspecialchars($flight['from_code']); ?></span>
                                    <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                                    <span><?php echo htmlspecialchars($flight['to_code']); ?></span>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; font-weight: 600; margin-top: 2px;">
                                    <?php echo date('D, d M y', strtotime($flight['departure_date'])); ?> &bull; <?php echo (int)($flight['stops'] ?? 0) > 0 ? ((int)$flight['stops'] . ' stop') : 'Non Stop'; ?>
                                </div>
                            </div>

                            <?php if (!empty($return_flight)): ?>
                            <div id="fareTab_return" class="fare-route-tab" onclick="switchFareRouteSector('return');" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; cursor: pointer; display: flex; flex-direction: column; transition: all 0.2s ease;">
                                <div class="tab-title" style="font-size: 13.5px; font-weight: 800; color: #334155; display: flex; align-items: center; gap: 6px;">
                                    <span><?php echo htmlspecialchars($return_flight['from_code']); ?></span>
                                    <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                                    <span><?php echo htmlspecialchars($return_flight['to_code']); ?></span>
                                </div>
                                <div style="font-size: 11.5px; color: #64748b; font-weight: 600; margin-top: 2px;">
                                    <?php echo date('D, d M y', strtotime($return_flight['departure_date'])); ?> &bull; <?php echo (int)($return_flight['stops'] ?? 0) > 0 ? ((int)$return_flight['stops'] . ' stop') : 'Non Stop'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php 
                        $onwardTiers = !empty($onward_fare_tiers) ? $onward_fare_tiers : array();
                        if (empty($onwardTiers) && isset($this->benzyflightapi)) {
                            $onwardTiers = $this->benzyflightapi->getDynamicFareTiers($flight['airline_code'] ?? '6E', $fBaseFare, $fTaxes, $fare_rules ?? array(), $flight['inclusions'] ?? array(), $ssr ?? array());
                        }
                        $returnTiers = !empty($return_fare_tiers) ? $return_fare_tiers : array();
                        if (empty($returnTiers) && !empty($return_flight) && isset($this->benzyflightapi)) {
                            $retBaseVal = round($retReviewPrice * 0.788);
                            $retTaxVal  = max(0, $retReviewPrice - $retBaseVal);
                            $returnTiers = $this->benzyflightapi->getDynamicFareTiers($return_flight['airline_code'] ?? '6E', $retBaseVal, $retTaxVal, $fare_rules ?? array(), array(), $ssr ?? array());
                        }
                        ?>

                        <!-- ONWARD SECTOR CONTAINER -->
                        <div id="sectorContainer_onward" style="display: block;">
                            <!-- Economy Starting Pill -->
                            <div style="margin-bottom: 20px;">
                                <span style="background: #e0f2fe; color: #0369a1; padding: 6px 14px; border-radius: 6px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                    Economy <span style="font-weight: 500; color: #475569;">Starting at ₹ <?php echo number_format($onwardTiers['Value']['price_per_pax'] ?? ($fBaseFare + $fTaxes)); ?></span>
                                </span>
                            </div>

                            <!-- 3 Comparison Cards Grid (Onward) -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                                <?php foreach ($onwardTiers as $tKey => $tier): 
                                    $isDefaultSelected = ($tKey === 'Value');
                                ?>
                                <div id="fareTierCard_onward_<?php echo $tKey; ?>" class="fare-tier-card" style="border: <?php echo $isDefaultSelected ? '2px solid #0284c7' : '1px solid #e2e8f0'; ?>; border-radius: 12px; background: #ffffff; position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; <?php echo $isDefaultSelected ? 'box-shadow: 0 4px 15px rgba(2,132,199,0.08);' : ''; ?> transition: all 0.2s ease;">
                                    <?php if (!empty($tier['badge'])): ?>
                                    <div style="position: absolute; top: -12px; right: 14px; background: <?php echo htmlspecialchars($tier['badge_color'] ?? '#16a34a'); ?>; color: #ffffff; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.12);">
                                        <?php if ($tKey === 'Flex'): ?><i class="fa-solid fa-crown" style="font-size: 10px;"></i> <?php endif; ?>
                                        <?php echo htmlspecialchars($tier['badge']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                            <?php echo htmlspecialchars($tier['name']); ?>
                                            <?php if (!empty($tier['sub_name']) && $tier['sub_name'] !== $tier['name']): ?>
                                                <span style="font-size: 12px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($tier['sub_name']); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 16px;">
                                            ₹ <?php echo number_format($tier['price_per_pax']); ?> <span style="font-size: 12px; font-weight: 500; color: #64748b;">per person</span>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; margin-bottom: 12px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-suitcase"></i> Baggage
                                            </div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;">Checked Baggage : <strong style="<?php echo ($tKey === 'Flex') ? 'color:#16a34a;' : ''; ?>"><?php echo htmlspecialchars($tier['checked_baggage']); ?></strong></div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;">Carry-on Baggage : <strong><?php echo htmlspecialchars($tier['cabin_baggage']); ?></strong></div>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-bottom: 12px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-rotate-left"></i> Flexibility
                                            </div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;"><?php echo htmlspecialchars($tier['cancellation_text']); ?></div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;"><?php echo htmlspecialchars($tier['change_text']); ?></div>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-bottom: 16px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-utensils"></i> Seat & meal
                                            </div>
                                            <div style="font-size: 12px; color: <?php echo ($tKey !== 'Value') ? '#16a34a; font-weight: 700;' : '#475569;'; ?> line-height: 1.5;"><?php echo htmlspecialchars($tier['seat_text']); ?></div>
                                            <div style="font-size: 12px; color: <?php echo !empty($tier['meal_highlight']) ? '#16a34a; font-weight: 700;' : '#475569;'; ?> line-height: 1.5;"><?php echo htmlspecialchars($tier['meal_text']); ?></div>
                                            <?php if (!empty($tier['priority_text'])): ?>
                                            <div style="font-size: 12px; color: #16a34a; font-weight: 700; line-height: 1.5; margin-top: 4px;">
                                                <i class="fa-solid fa-bolt"></i> <?php echo htmlspecialchars($tier['priority_text']); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <button type="button" class="tier-select-btn" onclick="selectFareTier('onward', '<?php echo $tKey; ?>', <?php echo (float)$tier['delta']; ?>, this)" style="width: 100%; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 13.5px; cursor: pointer; transition: all 0.2s ease; background: <?php echo $isDefaultSelected ? '#0284c7' : '#ffffff'; ?>; color: <?php echo $isDefaultSelected ? '#ffffff' : '#0f172a'; ?>; border: <?php echo $isDefaultSelected ? 'none' : '1.5px solid #cbd5e1'; ?>;">
                                        <?php echo $isDefaultSelected ? 'Selected' : 'Select'; ?>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if (!empty($return_flight)): ?>
                        <!-- RETURN SECTOR CONTAINER -->
                        <div id="sectorContainer_return" style="display: none;">
                            <!-- Economy Starting Pill (Return) -->
                            <div style="margin-bottom: 20px;">
                                <span style="background: #e0f2fe; color: #0369a1; padding: 6px 14px; border-radius: 6px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                    Economy <span style="font-weight: 500; color: #475569;">Starting at ₹ <?php echo number_format($returnTiers['Value']['price_per_pax'] ?? $retReviewPrice); ?></span>
                                </span>
                            </div>

                            <!-- 3 Comparison Cards Grid (Return) -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                                <?php foreach ($returnTiers as $tKey => $tier): 
                                    $isDefaultSelected = ($tKey === 'Value');
                                ?>
                                <div id="fareTierCard_return_<?php echo $tKey; ?>" class="fare-tier-card" style="border: <?php echo $isDefaultSelected ? '2px solid #0284c7' : '1px solid #e2e8f0'; ?>; border-radius: 12px; background: #ffffff; position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; <?php echo $isDefaultSelected ? 'box-shadow: 0 4px 15px rgba(2,132,199,0.08);' : ''; ?> transition: all 0.2s ease;">
                                    <?php if (!empty($tier['badge'])): ?>
                                    <div style="position: absolute; top: -12px; right: 14px; background: <?php echo htmlspecialchars($tier['badge_color'] ?? '#16a34a'); ?>; color: #ffffff; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.12);">
                                        <?php if ($tKey === 'Flex'): ?><i class="fa-solid fa-crown" style="font-size: 10px;"></i> <?php endif; ?>
                                        <?php echo htmlspecialchars($tier['badge']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                            <?php echo htmlspecialchars($tier['name']); ?>
                                            <?php if (!empty($tier['sub_name']) && $tier['sub_name'] !== $tier['name']): ?>
                                                <span style="font-size: 12px; font-weight: 600; color: #64748b;">(<?php echo htmlspecialchars($tier['sub_name']); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 16px;">
                                            ₹ <?php echo number_format($tier['price_per_pax']); ?> <span style="font-size: 12px; font-weight: 500; color: #64748b;">per person</span>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; margin-bottom: 12px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-suitcase"></i> Baggage
                                            </div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;">Checked Baggage : <strong style="<?php echo ($tKey === 'Flex') ? 'color:#16a34a;' : ''; ?>"><?php echo htmlspecialchars($tier['checked_baggage']); ?></strong></div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;">Carry-on Baggage : <strong><?php echo htmlspecialchars($tier['cabin_baggage']); ?></strong></div>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-bottom: 12px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-rotate-left"></i> Flexibility
                                            </div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;"><?php echo htmlspecialchars($tier['cancellation_text']); ?></div>
                                            <div style="font-size: 12px; color: #475569; line-height: 1.5;"><?php echo htmlspecialchars($tier['change_text']); ?></div>
                                        </div>

                                        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; margin-bottom: 16px;">
                                            <div style="font-size: 12px; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                                <i class="fa-solid fa-utensils"></i> Seat & meal
                                            </div>
                                            <div style="font-size: 12px; color: <?php echo ($tKey !== 'Value') ? '#16a34a; font-weight: 700;' : '#475569;'; ?> line-height: 1.5;"><?php echo htmlspecialchars($tier['seat_text']); ?></div>
                                            <div style="font-size: 12px; color: <?php echo !empty($tier['meal_highlight']) ? '#16a34a; font-weight: 700;' : '#475569;'; ?> line-height: 1.5;"><?php echo htmlspecialchars($tier['meal_text']); ?></div>
                                            <?php if (!empty($tier['priority_text'])): ?>
                                            <div style="font-size: 12px; color: #16a34a; font-weight: 700; line-height: 1.5; margin-top: 4px;">
                                                <i class="fa-solid fa-bolt"></i> <?php echo htmlspecialchars($tier['priority_text']); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <button type="button" class="tier-select-btn" onclick="selectFareTier('return', '<?php echo $tKey; ?>', <?php echo (float)$tier['delta']; ?>, this)" style="width: 100%; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 13.5px; cursor: pointer; transition: all 0.2s ease; background: <?php echo $isDefaultSelected ? '#0284c7' : '#ffffff'; ?>; color: <?php echo $isDefaultSelected ? '#ffffff' : '#0f172a'; ?>; border: <?php echo $isDefaultSelected ? 'none' : '1.5px solid #cbd5e1'; ?>;">
                                        <?php echo $isDefaultSelected ? 'Selected' : 'Select'; ?>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- 2. Maximize Your Travel Safety & Flexibility (Screenshot 1) -->
                    <div style="background: #ffffff; border-radius: 14px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                            Maximize Your Travel Safety & Flexibility
                        </h3>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0 0 16px 0;">
                            Secure your booking with cancellation safety, date change freedom, and baggage support—all designed to make your trip stress-free.
                        </p>

                        <!-- Option 1: Select for Zero Cancellation (Screenshot 1) -->
                        <div id="cardZeroCancellation" class="safety-card" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; transition: all 0.2s ease;">
                            <label style="display: flex; gap: 12px; cursor: pointer; flex: 1;">
                                <input type="radio" name="safety_coverage_radio" id="radioZeroCancellation" value="zero_cancellation" onchange="onSafetyRadioChange('zero_cancellation')" style="accent-color: #dc2626; margin-top: 3px;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        Select for Zero Cancellation <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 3px; line-height: 1.4;">
                                        No Questions asked on cancellation. Get a full refund, in case of cancellation up to 24 hours before departure! <a href="javascript:void(0);" style="color: #0284c7; text-decoration: underline;">T&Cs</a>
                                    </div>
                                </div>
                            </label>
                            <div style="text-align: right; min-width: 90px;">
                                <a href="javascript:void(0);" id="removeZeroCancelLink" onclick="removeSafetyOption('zero_cancellation')" style="display: none; font-size: 12px; color: #0284c7; font-weight: 700; text-decoration: underline; margin-bottom: 2px;">Remove</a>
                                <div style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ <?php echo number_format(2365 * $total_travelers_review); ?></div>
                            </div>
                        </div>

                        <!-- Option 2: Make your booking refundable (Screenshot 1) -->
                        <div id="cardRefundable" class="safety-card" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; transition: all 0.2s ease;">
                            <label style="display: flex; gap: 12px; cursor: pointer; flex: 1;">
                                <input type="radio" name="safety_coverage_radio" id="radioRefundable" value="refundable" onchange="onSafetyRadioChange('refundable')" style="accent-color: #dc2626; margin-top: 3px;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        Make your booking refundable <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 3px; line-height: 1.4;">
                                        Upgrade to refundable. Get up to <strong>100% refund</strong> if plans change, as per applicable. <a href="javascript:void(0);" style="color: #0284c7; text-decoration: underline;">T&Cs</a>
                                    </div>
                                    <div style="display: flex; gap: 14px; font-size: 11.5px; color: #475569; margin-top: 6px; flex-wrap: wrap;">
                                        <span>&bull; Illness/Injury</span>
                                        <span>&bull; Pre-existing medical condition</span>
                                        <span>&bull; Last Minute Emergency</span>
                                    </div>
                                </div>
                            </label>
                            <div style="text-align: right; min-width: 90px;">
                                <a href="javascript:void(0);" id="removeRefundableLink" onclick="removeSafetyOption('refundable')" style="display: none; font-size: 12px; color: #0284c7; font-weight: 700; text-decoration: underline; margin-bottom: 2px;">Remove</a>
                                <div style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ <?php echo number_format(1467 * $total_travelers_review); ?></div>
                            </div>
                        </div>

                        <!-- Option 3: Travel Insurance (Screenshot 1) -->
                        <div id="cardInsurance" class="safety-card" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; transition: all 0.2s ease;">
                            <label style="display: flex; gap: 12px; cursor: pointer; flex: 1;">
                                <input type="checkbox" id="travelInsuranceCheckbox" onchange="toggleTravelInsurance(this.checked)" style="accent-color: #2563eb; margin-top: 3px;">
                                <div>
                                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                        Travel Insurance <i class="fa-solid fa-shield-halved" style="color: #0284c7;"></i>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                                        Secure your trip with our Travel Insurance.
                                    </div>
                                    <div style="display: flex; gap: 14px; font-size: 11.5px; color: #475569; margin-top: 6px; flex-wrap: wrap;">
                                        <span>&bull; Trip Delays</span>
                                        <span>&bull; Trip Cancellation</span>
                                        <span>&bull; Lost Baggage</span>
                                        <a href="javascript:void(0);" style="color: #0284c7; text-decoration: underline;">View more benefits</a>
                                    </div>
                                    <div style="font-size: 11px; color: #0284c7; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-circle-info"></i> Only for Indian Citizen below the age of 70 years
                                    </div>
                                </div>
                            </label>
                            <div style="text-align: right; min-width: 90px;">
                                <a href="javascript:void(0);" id="insuranceToggleBtn" onclick="toggleInsuranceLink()" style="font-size: 12px; color: #0284c7; font-weight: 700; text-decoration: underline;">+ Add</a>
                                <div style="font-size: 16px; font-weight: 900; color: #0f172a; margin-top: 2px;">₹ <?php echo number_format(199 * $total_travelers_review); ?></div>
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
                        $p_arr_idx = $p_index - 1;
                        $curTitle  = !empty($savedReview['passenger_title'][$p_arr_idx]) ? $savedReview['passenger_title'][$p_arr_idx] : 'Mr';
                        $curName   = !empty($savedReview['passenger_name'][$p_arr_idx]) ? $savedReview['passenger_name'][$p_arr_idx] : (($p_index === 1 && empty($savedReview['passenger_name'])) ? 'Rahul Sharma' : '');
                        $curDob    = !empty($savedReview['passenger_dob'][$p_arr_idx]) ? $savedReview['passenger_dob'][$p_arr_idx] : '1996-05-15';
                        $curAge    = !empty($savedReview['passenger_age'][$p_arr_idx]) ? $savedReview['passenger_age'][$p_arr_idx] : '28';
                        $curGender = !empty($savedReview['passenger_gender_' . $p_index]) ? $savedReview['passenger_gender_' . $p_index] : 'Male';
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
                                    <option value="Mr" <?php echo ($curTitle === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                                    <option value="Ms" <?php echo ($curTitle === 'Ms') ? 'selected' : ''; ?>>Ms</option>
                                    <option value="Mrs" <?php echo ($curTitle === 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter First & Last Name" value="<?php echo htmlspecialchars($curName); ?>" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="<?php echo htmlspecialchars($curDob); ?>" max="<?php echo date('Y-m-d', strtotime('-12 years', strtotime($flight_dep_date))); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="<?php echo htmlspecialchars($curAge); ?>" min="12" max="99" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" <?php echo ($curGender !== 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" <?php echo ($curGender === 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- Child Passenger Cards -->
                    <?php for ($c = 1; $c <= $child_count; $c++): $p_index++; 
                        $p_arr_idx = $p_index - 1;
                        $curTitle  = !empty($savedReview['passenger_title'][$p_arr_idx]) ? $savedReview['passenger_title'][$p_arr_idx] : 'Mstr';
                        $curName   = !empty($savedReview['passenger_name'][$p_arr_idx]) ? $savedReview['passenger_name'][$p_arr_idx] : '';
                        $curDob    = !empty($savedReview['passenger_dob'][$p_arr_idx]) ? $savedReview['passenger_dob'][$p_arr_idx] : '2019-08-30';
                        $curAge    = !empty($savedReview['passenger_age'][$p_arr_idx]) ? $savedReview['passenger_age'][$p_arr_idx] : '7';
                        $curGender = !empty($savedReview['passenger_gender_' . $p_index]) ? $savedReview['passenger_gender_' . $p_index] : 'Male';
                    ?>
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
                                    <option value="Mstr" <?php echo ($curTitle === 'Mstr') ? 'selected' : ''; ?>>Master</option>
                                    <option value="Miss" <?php echo ($curTitle === 'Miss') ? 'selected' : ''; ?>>Miss</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter Child's Name" value="<?php echo htmlspecialchars($curName); ?>" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="<?php echo htmlspecialchars($curDob); ?>" min="<?php echo date('Y-m-d', strtotime('-12 years', strtotime($flight_dep_date))); ?>" max="<?php echo date('Y-m-d', strtotime('-2 years', strtotime($flight_dep_date))); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="<?php echo htmlspecialchars($curAge); ?>" min="2" max="11" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" <?php echo ($curGender !== 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" <?php echo ($curGender === 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- Infant Passenger Cards -->
                    <?php for ($i_cnt = 1; $i_cnt <= $infant_count; $i_cnt++): $p_index++; 
                        $p_arr_idx = $p_index - 1;
                        $curTitle  = !empty($savedReview['passenger_title'][$p_arr_idx]) ? $savedReview['passenger_title'][$p_arr_idx] : 'Mstr';
                        $curName   = !empty($savedReview['passenger_name'][$p_arr_idx]) ? $savedReview['passenger_name'][$p_arr_idx] : '';
                        $curDob    = !empty($savedReview['passenger_dob'][$p_arr_idx]) ? $savedReview['passenger_dob'][$p_arr_idx] : '2025-08-30';
                        $curAge    = !empty($savedReview['passenger_age'][$p_arr_idx]) ? $savedReview['passenger_age'][$p_arr_idx] : '1';
                        $curGender = !empty($savedReview['passenger_gender_' . $p_index]) ? $savedReview['passenger_gender_' . $p_index] : 'Male';
                    ?>
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
                                    <option value="Mstr" <?php echo ($curTitle === 'Mstr') ? 'selected' : ''; ?>>Master</option>
                                    <option value="Miss" <?php echo ($curTitle === 'Miss') ? 'selected' : ''; ?>>Miss</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Full Name *</label>
                                <input type="text" name="passenger_name[]" class="field-input" required placeholder="Enter Infant's Name" value="<?php echo htmlspecialchars($curName); ?>" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Date of Birth (DOB) *</label>
                                <input type="date" name="passenger_dob[]" class="field-input dob-input" required value="<?php echo htmlspecialchars($curDob); ?>" min="<?php echo date('Y-m-d', strtotime('-2 years', strtotime($flight_dep_date))); ?>" max="<?php echo date('Y-m-d', strtotime($flight_dep_date)); ?>" data-travel-date="<?php echo htmlspecialchars($flight_dep_date); ?>" onchange="calculatePassengerAge(this)" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input age-input" required value="<?php echo htmlspecialchars($curAge); ?>" min="0" max="2" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Gender *</label>
                            <div style="display: flex; gap: 24px; font-size: 14px; font-weight: 600; color: #334155;">
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Male" <?php echo ($curGender !== 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Male</label>
                                <label style="cursor: pointer;"><input type="radio" name="passenger_gender_<?php echo $p_index; ?>" value="Female" <?php echo ($curGender === 'Female') ? 'checked' : ''; ?> style="accent-color: #2563eb;"> Female</label>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <!-- Contact & GST Details Card -->
                    <?php
                        $curContactName  = !empty($savedReview['contact_name']) ? $savedReview['contact_name'] : ($sessionUserName ?: 'Rahul Sharma');
                        $curContactEmail = !empty($savedReview['contact_email']) ? $savedReview['contact_email'] : ($sessionUserEmail ?: '');
                        $curContactPhone = !empty($savedReview['contact_phone']) ? $savedReview['contact_phone'] : ($cleanPhone ?: $sessionUserPhone);
                        $curGstNumber    = !empty($savedReview['gst_number']) ? $savedReview['gst_number'] : '';
                        $curGstCompany   = !empty($savedReview['gst_company']) ? $savedReview['gst_company'] : '';
                    ?>
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-address-book" style="color: #2563eb;"></i> Contact & E-Ticket Details
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Contact Person *</label>
                                <input type="text" name="contact_name" class="field-input" required value="<?php echo htmlspecialchars($curContactName); ?>" placeholder="Full Name" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Email Address *</label>
                                <input type="email" name="contact_email" class="field-input" required value="<?php echo htmlspecialchars($curContactEmail); ?>" placeholder="name@example.com" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Mobile Number *</label>
                                <input type="tel" name="contact_phone" class="field-input" required value="<?php echo htmlspecialchars($curContactPhone); ?>" placeholder="10-digit mobile" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                        </div>

                        <!-- GST Checkbox Toggle -->
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 14px;">
                            <label style="font-size: 13px; font-weight: 700; color: #0d3470; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="gstToggle" onchange="toggleGstFields()" <?php echo !empty($curGstNumber) ? 'checked' : ''; ?> style="accent-color: #2563eb; width: 16px; height: 16px;">
                                Use GSTIN for Business Travel & Tax Invoice Claim (Optional)
                            </label>

                            <div id="gstFieldsSection" style="display: <?php echo !empty($curGstNumber) ? 'grid' : 'none'; ?>; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 14px; background: #f8fafc; padding: 14px; border-radius: 8px;">
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">GSTIN Number</label>
                                    <input type="text" name="gst_number" value="<?php echo htmlspecialchars($curGstNumber); ?>" placeholder="27AAAAA0000A1Z5" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Registered Company Name</label>
                                    <input type="text" name="gst_company" value="<?php echo htmlspecialchars($curGstCompany); ?>" placeholder="Voyogo Solutions Pvt Ltd" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit to Add-on Services (Screenshot 4 & 5) -->
                    <div style="text-align: right; margin-bottom: 30px;">
                        <button type="submit" id="continueToAddonsBtn" style="padding: 16px 40px; font-size: 16.5px; font-weight: 800; color: #ffffff; background: linear-gradient(135deg, #0d3470, #2563eb); border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 15px rgba(37,99,235,0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 10px;">
                            <span>Continue to Add-on Services</span> <i class="fa-solid fa-arrow-right"></i>
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

                        <!-- Base Fare Item with Subrow (Toggleable +/-) (Screenshot 3) -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to expand/collapse Base Fare breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Base Fare
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

                        <!-- Tax & Charges Item with Subrows (Toggleable +/-) (Screenshot 3) -->
                        <div class="f-fare-group" style="margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to expand/collapse Tax & Charges breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Tax & Charges
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

                        <!-- Insurance Item with Subrows (Toggleable +/-) (Screenshot 3) -->
                        <div class="f-fare-group" id="fareGroupInsurance" style="display: none; margin-bottom: 12px;">
                            <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;" title="Click to expand/collapse Insurance breakdown">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Insurance
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

                        <!-- Zero Cancellation / Refundable Upgrade Item -->
                        <div class="f-fare-group" id="fareGroupSafety" style="display: none; margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-shield-heart" style="color: #16a34a;"></i> <span id="summarySafetyLabel">Zero Cancellation</span>
                                </span>
                                <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summarySafetyAmount">0</span></strong>
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
var isInsuranceIncluded = false;
var appliedDiscount = <?php echo (float)$initialDiscount; ?>;
var appliedPromoCodeName = 'ATFLY';

function goBackToSearch() {
    var form = document.getElementById('backToSearchForm');
    if (form) {
        form.submit();
        return;
    }
    var fallbackUrl = <?php echo json_encode(!empty($back_search_url) ? $back_search_url : site_url('flight')); ?>;
    window.location.href = fallbackUrl;
}

function toggleFlightBox(type) {
    var body = document.getElementById('flightBoxBody_' + type);
    var icon = document.getElementById('iconToggle_' + type);
    var btn = document.getElementById('btnToggle_' + type);
    if (!body) return;
    
    var isHidden = (body.style.display === 'none');
    if (isHidden) {
        body.style.display = 'block';
        if (icon) {
            icon.className = 'fa-solid fa-chevron-up';
        }
        if (btn) {
            btn.title = 'Minimize';
        }
    } else {
        body.style.display = 'none';
        if (icon) {
            icon.className = 'fa-solid fa-chevron-down';
        }
        if (btn) {
            btn.title = 'Maximize';
        }
    }
}

function toggleFareBreakdown(triggerEl) {
    var group = triggerEl.closest('.f-fare-group');
    if (!group) return;
    var subitems = group.querySelector('.f-fare-subitems');
    var circleIcon = group.querySelector('.f-fare-toggle-circle');
    if (!subitems) return;

    var isHidden = (subitems.style.display === 'none' || window.getComputedStyle(subitems).display === 'none');
    if (isHidden) {
        subitems.style.display = 'flex';
        if (circleIcon) {
            circleIcon.textContent = '−';
            circleIcon.style.background = '#0284c7';
            circleIcon.style.color = '#ffffff';
            circleIcon.style.borderColor = '#0284c7';
        }
    } else {
        subitems.style.display = 'none';
        if (circleIcon) {
            circleIcon.textContent = '+';
            circleIcon.style.background = 'transparent';
            circleIcon.style.color = '#0284c7';
            circleIcon.style.borderColor = '#0284c7';
        }
    }
}

var zeroCancellationTotal = <?php echo 2365 * $total_travelers_review; ?>;
var refundableTotal = <?php echo 1467 * $total_travelers_review; ?>;
var selectedSafetyType = '';
var selectedSafetyAmount = 0;
var selectedFareTier = 'Value';
var fareTierDeltaTotal = 0;

function onSafetyRadioChange(type) {
    selectedSafetyType = type;
    if (type === 'zero_cancellation') {
        selectedSafetyAmount = zeroCancellationTotal;
        var cZero = document.getElementById('cardZeroCancellation');
        if (cZero) {
            cZero.style.borderColor = '#dc2626';
            cZero.style.background = '#fff5f5';
        }
        var rmZero = document.getElementById('removeZeroCancelLink');
        if (rmZero) rmZero.style.display = 'inline-block';

        var cRef = document.getElementById('cardRefundable');
        if (cRef) {
            cRef.style.borderColor = '#e2e8f0';
            cRef.style.background = '#f8fafc';
        }
        var rmRef = document.getElementById('removeRefundableLink');
        if (rmRef) rmRef.style.display = 'none';
        var rRef = document.getElementById('radioRefundable');
        if (rRef) rRef.checked = false;
    } else if (type === 'refundable') {
        selectedSafetyAmount = refundableTotal;
        var cRef = document.getElementById('cardRefundable');
        if (cRef) {
            cRef.style.borderColor = '#16a34a';
            cRef.style.background = '#f0fdf4';
        }
        var rmRef = document.getElementById('removeRefundableLink');
        if (rmRef) rmRef.style.display = 'inline-block';

        var cZero = document.getElementById('cardZeroCancellation');
        if (cZero) {
            cZero.style.borderColor = '#e2e8f0';
            cZero.style.background = '#f8fafc';
        }
        var rmZero = document.getElementById('removeZeroCancelLink');
        if (rmZero) rmZero.style.display = 'none';
        var rZero = document.getElementById('radioZeroCancellation');
        if (rZero) rZero.checked = false;
    }

    if (document.getElementById('form_safety_cancellation_type')) {
        document.getElementById('form_safety_cancellation_type').value = selectedSafetyType;
    }
    if (document.getElementById('form_safety_cancellation_amount')) {
        document.getElementById('form_safety_cancellation_amount').value = selectedSafetyAmount;
    }

    var safetyRow = document.getElementById('fareGroupSafety');
    if (safetyRow) {
        safetyRow.style.display = 'block';
        var lbl = document.getElementById('summarySafetyLabel');
        if (lbl) lbl.textContent = (type === 'zero_cancellation') ? 'Zero Cancellation' : 'Refundable Booking Upgrade';
        var amt = document.getElementById('summarySafetyAmount');
        if (amt) amt.textContent = selectedSafetyAmount.toLocaleString('en-IN');
    }

    recalculateAllFares();
}

function removeSafetyOption(type) {
    selectedSafetyType = '';
    selectedSafetyAmount = 0;

    var rZero = document.getElementById('radioZeroCancellation');
    if (rZero) rZero.checked = false;
    var rRef = document.getElementById('radioRefundable');
    if (rRef) rRef.checked = false;

    var cZero = document.getElementById('cardZeroCancellation');
    if (cZero) {
        cZero.style.borderColor = '#e2e8f0';
        cZero.style.background = '#f8fafc';
    }
    var rmZero = document.getElementById('removeZeroCancelLink');
    if (rmZero) rmZero.style.display = 'none';

    var cRef = document.getElementById('cardRefundable');
    if (cRef) {
        cRef.style.borderColor = '#e2e8f0';
        cRef.style.background = '#f8fafc';
    }
    var rmRef = document.getElementById('removeRefundableLink');
    if (rmRef) rmRef.style.display = 'none';

    if (document.getElementById('form_safety_cancellation_type')) {
        document.getElementById('form_safety_cancellation_type').value = '';
    }
    if (document.getElementById('form_safety_cancellation_amount')) {
        document.getElementById('form_safety_cancellation_amount').value = 0;
    }

    var safetyRow = document.getElementById('fareGroupSafety');
    if (safetyRow) {
        safetyRow.style.display = 'none';
    }

    recalculateAllFares();
}

function toggleTravelInsurance(include) {
    isInsuranceIncluded = include;
    var cb = document.getElementById('travelInsuranceCheckbox');
    if (cb) cb.checked = include;
    var linkBtn = document.getElementById('insuranceToggleBtn');
    if (linkBtn) linkBtn.textContent = include ? 'Remove' : '+ Add';

    var insCard = document.getElementById('cardInsurance');
    if (insCard) {
        insCard.style.borderColor = include ? '#2563eb' : '#e2e8f0';
        insCard.style.background = include ? '#f8fafc' : '#ffffff';
    }

    var insGroup = document.getElementById('fareGroupInsurance');
    if (insGroup) {
        insGroup.style.display = include ? 'block' : 'none';
    }

    if (document.getElementById('form_travel_insurance_selected')) {
        document.getElementById('form_travel_insurance_selected').value = include ? '1' : '0';
    }
    if (document.getElementById('form_insurance_amount')) {
        document.getElementById('form_insurance_amount').value = include ? (insurancePerPax * totalPax) : 0;
    }

    recalculateAllFares();
}

function toggleInsuranceLink() {
    toggleTravelInsurance(!isInsuranceIncluded);
}

var selectedOnwardTier = 'Value';
var onwardTierDelta = 0;
var selectedReturnTier = 'Value';
var returnTierDelta = 0;

function scrollToFareOptions(sector) {
    if (sector === 'return') {
        switchFareRouteSector('return');
    } else {
        switchFareRouteSector('onward');
    }
    var target = document.getElementById('moreFareOptionsSection');
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        target.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
        target.style.boxShadow = '0 0 0 3px rgba(2, 132, 199, 0.4)';
        target.style.borderColor = '#0284c7';
        setTimeout(function() {
            target.style.boxShadow = '0 4px 20px rgba(0,32,90,0.04)';
            target.style.borderColor = '#e2e8f0';
        }, 1500);
    }
}

function switchFareRouteSector(sector) {
    var tabOnward = document.getElementById('fareTab_onward');
    var tabReturn = document.getElementById('fareTab_return');
    var secOnward = document.getElementById('sectorContainer_onward');
    var secReturn = document.getElementById('sectorContainer_return');

    if (sector === 'onward') {
        if (tabOnward) {
            tabOnward.classList.add('active');
            tabOnward.style.borderColor = '#0284c7';
            tabOnward.style.background = '#f0f9ff';
            var t1 = tabOnward.querySelector('.tab-title');
            if (t1) t1.style.color = '#0369a1';
        }
        if (tabReturn) {
            tabReturn.classList.remove('active');
            tabReturn.style.borderColor = '#e2e8f0';
            tabReturn.style.background = '#f8fafc';
            var t2 = tabReturn.querySelector('.tab-title');
            if (t2) t2.style.color = '#334155';
        }
        if (secOnward) secOnward.style.display = 'block';
        if (secReturn) secReturn.style.display = 'none';
    } else {
        if (tabReturn) {
            tabReturn.classList.add('active');
            tabReturn.style.borderColor = '#0284c7';
            tabReturn.style.background = '#f0f9ff';
            var t2 = tabReturn.querySelector('.tab-title');
            if (t2) t2.style.color = '#0369a1';
        }
        if (tabOnward) {
            tabOnward.classList.remove('active');
            tabOnward.style.borderColor = '#e2e8f0';
            tabOnward.style.background = '#f8fafc';
            var t1 = tabOnward.querySelector('.tab-title');
            if (t1) t1.style.color = '#334155';
        }
        if (secOnward) secOnward.style.display = 'none';
        if (secReturn) secReturn.style.display = 'block';
    }
}

function selectFareTier(sectorOrTier, tierOrDelta, deltaOrBtn, btnEl) {
    var sector = 'onward';
    var tier = sectorOrTier;
    var deltaPerPax = parseFloat(tierOrDelta) || 0;

    if (sectorOrTier === 'onward' || sectorOrTier === 'return') {
        sector = sectorOrTier;
        tier = tierOrDelta;
        deltaPerPax = parseFloat(deltaOrBtn) || 0;
    }

    if (sector === 'onward') {
        selectedOnwardTier = tier;
        onwardTierDelta = deltaPerPax * totalPax;
        if (document.getElementById('form_onward_fare_tier')) {
            document.getElementById('form_onward_fare_tier').value = tier;
        }
        if (document.getElementById('form_onward_fare_tier_price_delta')) {
            document.getElementById('form_onward_fare_tier_price_delta').value = onwardTierDelta;
        }
    } else {
        selectedReturnTier = tier;
        returnTierDelta = deltaPerPax * totalPax;
        if (document.getElementById('form_return_fare_tier')) {
            document.getElementById('form_return_fare_tier').value = tier;
        }
        if (document.getElementById('form_return_fare_tier_price_delta')) {
            document.getElementById('form_return_fare_tier_price_delta').value = returnTierDelta;
        }
    }

    selectedFareTier = selectedOnwardTier + (selectedReturnTier && selectedReturnTier !== selectedOnwardTier ? (' / ' + selectedReturnTier) : '');
    fareTierDeltaTotal = onwardTierDelta + returnTierDelta;

    if (document.getElementById('form_fare_tier')) {
        document.getElementById('form_fare_tier').value = selectedFareTier;
    }
    if (document.getElementById('form_fare_tier_price_delta')) {
        document.getElementById('form_fare_tier_price_delta').value = fareTierDeltaTotal;
    }

    // Update UI cards for this sector
    ['Value', 'Classic', 'Flex'].forEach(function(t) {
        var card = document.getElementById('fareTierCard_' + sector + '_' + t) || document.getElementById('fareTierCard_' + t);
        if (card) {
            var isThis = (t === tier);
            card.style.borderColor = isThis ? '#0284c7' : '#e2e8f0';
            card.style.boxShadow = isThis ? '0 4px 15px rgba(2,132,199,0.1)' : 'none';
            var btn = card.querySelector('.tier-select-btn');
            if (btn) {
                btn.textContent = isThis ? 'Selected' : 'Select';
                btn.style.background = isThis ? '#0284c7' : '#ffffff';
                btn.style.color = isThis ? '#ffffff' : '#0f172a';
                btn.style.border = isThis ? 'none' : '1.5px solid #cbd5e1';
            }
        }
    });

    recalculateAllFares();
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
    var currentBase = baseFlightFare + fareTierDeltaTotal;
    var grandTotal = Math.max(0, currentBase + taxesFare + insuranceAmount + selectedSafetyAmount - appliedDiscount);

    var baseEl = document.getElementById('summaryBaseFare');
    if (baseEl) baseEl.textContent = currentBase.toLocaleString('en-IN');

    var insEl = document.getElementById('summaryInsurance');
    if (insEl) insEl.textContent = insuranceAmount.toLocaleString('en-IN');

    var discRow = document.getElementById('summaryDiscountRow');
    if (discRow) {
        discRow.style.display = (appliedDiscount > 0) ? 'flex' : 'none';
        var discDisp = document.getElementById('summaryDiscount');
        if (discDisp) discDisp.textContent = appliedDiscount.toLocaleString('en-IN');
    }

    var totalDisp = document.getElementById('summaryTotalAmount');
    if (totalDisp) totalDisp.textContent = grandTotal.toLocaleString('en-IN');

    var formTotal = document.getElementById('form_total_amount');
    if (formTotal) formTotal.value = grandTotal;

    var formBase = document.getElementById('form_base_fare');
    if (formBase) formBase.value = currentBase;

    var formIns = document.getElementById('form_insurance_amount');
    if (formIns) formIns.value = insuranceAmount;

    var formDisc = document.getElementById('form_discount_amount');
    if (formDisc) formDisc.value = appliedDiscount;

    var formPromo = document.getElementById('form_promo_code');
    if (formPromo) formPromo.value = appliedPromoCodeName;
}

function toggleGstFields() {
    var isChecked = document.getElementById('gstToggle').checked;
    document.getElementById('gstFieldsSection').style.display = isChecked ? 'grid' : 'none';
}

var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;

// In-page Login Success Handler
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
            '<div style="font-size: 12.5px; color: #15803d;">Your verified contact details have been applied.</div></div>' +
            '</div>' +
            '<span style="font-size: 11.5px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved"></i> Phone Verified</span>';
    }
};

// Form submission handler: Validates details and proceeds to Step 3: Add-on Services (Screenshot 4 & 5)
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    var contactName = document.querySelector('input[name="contact_name"]').value.trim();
    var contactEmail = document.querySelector('input[name="contact_email"]').value.trim();
    var contactPhone = document.querySelector('input[name="contact_phone"]').value.trim();

    if (!contactName || !contactEmail || !contactPhone) {
        e.preventDefault();
        alert('Please fill in your Contact Person, Email Address, and Mobile Number.');
        return false;
    }

    var paxNames = document.querySelectorAll('input[name="passenger_name[]"]');
    for (var i = 0; i < paxNames.length; i++) {
        if (!paxNames[i].value.trim()) {
            e.preventDefault();
            alert('Please enter the full name for Passenger ' + (i + 1) + '.');
            paxNames[i].focus();
            return false;
        }
    }

    // Submit proceeds smoothly to flight/addons
    return true;
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

/* Multi-Segment Connecting Flight Styles (Screenshot 2 Match) */
.seg-specs-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    gap: 12px;
    font-size: 12px;
}
.seg-specs-divider {
    width: 1px;
    height: 22px;
    background: #cbd5e1;
}
@media (max-width: 768px) {
    .seg-header-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px !important;
    }
    .seg-specs-box {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        width: 100% !important;
        padding: 8px 12px !important;
    }
    .seg-specs-divider {
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
@media (max-width: 992px) {
    .flight-review-layout-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
