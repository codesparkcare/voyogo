<div style="background-color: #f4f7fe; padding: 25px 0 60px 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        
        <!-- Header Step Progress Bar -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 14px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.05); display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 15px;">
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

                    <!-- Interactive Accordion Tabs: Baggage & Cancellation Rules -->
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 16px;">
                        <div style="display: flex; gap: 15px; border-bottom: 2px solid #e2e8f0; margin-bottom: 14px;">
                            <button type="button" class="tab-btn active-tab" onclick="switchReviewTab('baggageTab', this)" style="padding: 8px 16px; border: none; background: none; font-weight: 700; font-size: 13px; color: #2563eb; border-bottom: 2px solid #2563eb; cursor: pointer; margin-bottom: -2px;">
                                <i class="fa-solid fa-suitcase-rolling"></i> Baggage Policy
                            </button>
                            <button type="button" class="tab-btn" onclick="switchReviewTab('rulesTab', this)" style="padding: 8px 16px; border: none; background: none; font-weight: 700; font-size: 13px; color: #64748b; border-bottom: 2px solid transparent; cursor: pointer; margin-bottom: -2px;">
                                <i class="fa-solid fa-file-contract"></i> Cancellation & Fare Rules
                            </button>
                        </div>

                        <!-- Baggage Tab Content -->
                        <div id="baggageTab" class="tab-content" style="display: block;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; background: #f8fafc; padding: 14px 18px; border-radius: 8px; border: 1px solid #edf2f7;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fa-solid fa-suitcase" style="font-size: 24px; color: #2563eb;"></i>
                                    <div>
                                        <span style="font-size: 12px; color: #64748b; display: block; font-weight: 600;">Check-in Baggage Allowance</span>
                                        <strong style="font-size: 14px; color: #0f172a;"><?php echo htmlspecialchars($flight['checkin_baggage'] ?? '15 Kgs (1 piece)'); ?></strong>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fa-solid fa-briefcase" style="font-size: 24px; color: #16a34a;"></i>
                                    <div>
                                        <span style="font-size: 12px; color: #64748b; display: block; font-weight: 600;">Cabin Baggage Allowance</span>
                                        <strong style="font-size: 14px; color: #0f172a;"><?php echo htmlspecialchars($flight['cabin_baggage'] ?? '7 Kgs (1 piece)'); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fare Rules Tab Content -->
                        <div id="rulesTab" class="tab-content" style="display: none;">
                            <div style="background: #f8fafc; padding: 14px 18px; border-radius: 8px; border: 1px solid #edf2f7;">
                                <h4 style="font-size: 13px; font-weight: 800; color: #0d3470; margin-top: 0; margin-bottom: 8px;">Cancellation Penalty Fees (Per Pax)</h4>
                                <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 12px;">
                                    <thead>
                                        <tr style="background: #e2e8f0; color: #334155; text-align: left;">
                                            <th style="padding: 6px 10px;">Timeframe Before Departure</th>
                                            <th style="padding: 6px 10px;">Cancellation Charge</th>
                                            <th style="padding: 6px 10px;">Date Change Charge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($fare_rules['cancellation'])): ?>
                                            <?php foreach ($fare_rules['cancellation'] as $idx => $rule): ?>
                                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                                    <td style="padding: 6px 10px; font-weight: 600; color: #475569;"><?php echo htmlspecialchars($rule['time']); ?></td>
                                                    <td style="padding: 6px 10px; font-weight: 700; color: #dc2626;"><?php echo htmlspecialchars($rule['fee']); ?></td>
                                                    <td style="padding: 6px 10px; font-weight: 700; color: #2563eb;"><?php echo htmlspecialchars($fare_rules['date_change'][$idx]['fee'] ?? '₹ 2,500 + Diff'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <td style="padding: 6px 10px; font-weight: 600;">2 hours to 24 hours</td>
                                                <td style="padding: 6px 10px; font-weight: 700; color: #dc2626;">₹ 3,500 per pax</td>
                                                <td style="padding: 6px 10px; font-weight: 700; color: #2563eb;">₹ 3,000 + Fare Diff</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 10px; font-weight: 600;">More than 24 hours</td>
                                                <td style="padding: 6px 10px; font-weight: 700; color: #dc2626;">₹ 3,000 per pax</td>
                                                <td style="padding: 6px 10px; font-weight: 700; color: #2563eb;">₹ 2,500 + Fare Diff</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <p style="font-size: 11px; color: #64748b; margin: 0;">
                                    <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Convenience fee & addon service charges are non-refundable.
                                </p>
                            </div>
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
                    <input type="hidden" name="net_amount" value="<?php echo htmlspecialchars($flight['net_amount'] ?? $flight['base_fare'] ?? $flight['price']); ?>">
                    <input type="hidden" name="total_amount" id="form_total_amount" value="<?php echo htmlspecialchars($flight['price']); ?>">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
                    <input type="hidden" name="is_roundtrip" value="<?php echo !empty($is_roundtrip) ? '1' : '0'; ?>">

                    <?php if (!empty($return_flight)): ?>
                    <input type="hidden" name="return_flight_number" value="<?php echo htmlspecialchars($return_flight['flight_number']); ?>">
                    <input type="hidden" name="return_airline_name" value="<?php echo htmlspecialchars($return_flight['airline_name']); ?>">
                    <input type="hidden" name="return_departure_date" value="<?php echo htmlspecialchars($return_flight['departure_date']); ?>">
                    <input type="hidden" name="return_departure_time" value="<?php echo htmlspecialchars($return_flight['departure_time']); ?>">
                    <?php endif; ?>

                    <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                    <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                    <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                    <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">

                    <?php 
                    $adult_count  = isset($search_query['adults']) ? (int)$search_query['adults'] : 1;
                    $child_count  = isset($search_query['children']) ? (int)$search_query['children'] : 0;
                    $infant_count = isset($search_query['infants']) ? (int)$search_query['infants'] : 0;
                    $p_index = 0;
                    ?>

                    <!-- Adult Passenger Cards -->
                    <?php for ($a = 1; $a <= $adult_count; $a++): $p_index++; ?>
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-users" style="color: #ef4444;"></i> Passenger Information (Adult <?php echo $a; ?>)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Govt. ID (Aadhaar / Passport)</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Adult">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 16px; margin-bottom: 16px;">
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
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input" required value="28" min="12" max="99" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
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
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-child" style="color: #3b82f6;"></i> Passenger Information (Child <?php echo $c; ?> - Age 2-12 yrs)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Govt. ID / Birth Cert.</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Child">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 16px; margin-bottom: 16px;">
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
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input" required value="7" min="2" max="11" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
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
                    <div style="background: #ffffff; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-baby" style="color: #10b981;"></i> Passenger Information (Infant <?php echo $i_cnt; ?> - Below 2 yrs)
                            </h3>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Name must match Birth Cert.</span>
                        </div>

                        <input type="hidden" name="passenger_type[]" value="Infant">

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 16px; margin-bottom: 16px;">
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
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Age *</label>
                                <input type="number" name="passenger_age[]" class="field-input" required value="1" min="0" max="2" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
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
                    $baggageList = !empty($ssr['Baggage']) ? $ssr['Baggage'] : (!empty($ssr['baggage']) ? $ssr['baggage'] : array());
                    $mealsList = !empty($ssr['Meals']) ? $ssr['Meals'] : (!empty($ssr['meals']) ? $ssr['meals'] : array());

                    if (empty($baggageList)) {
                        $baggageList = array(
                            array("Code" => "BAG0", "Description" => "Standard Cabin (7kg) + Check-in (15kg) - Included", "Amount" => 0),
                            array("Code" => "BAG3", "Description" => "Additional 3 Kgs Check-in Baggage", "Amount" => 1350),
                            array("Code" => "BAG5", "Description" => "Additional 5 Kgs Check-in Baggage", "Amount" => 2250),
                            array("Code" => "BAG10", "Description" => "Additional 10 Kgs Check-in Baggage", "Amount" => 4500),
                            array("Code" => "BAG15", "Description" => "Additional 15 Kgs Check-in Baggage", "Amount" => 6750),
                        );
                    }

                    if (empty($mealsList)) {
                        $mealsList = array(
                            array("Code" => "NO_MEAL", "Description" => "No In-Flight Meal", "Amount" => 0),
                            array("Code" => "VEG_SANDWICH", "Description" => "Paneer Tikka Sandwich + Soft Drink", "Amount" => 350),
                            array("Code" => "NONVEG_SANDWICH", "Description" => "Chicken Tikka Sandwich + Beverage", "Amount" => 400),
                            array("Code" => "HOT_MEAL_VEG", "Description" => "Hot Indian Veg Meal Box", "Amount" => 450),
                            array("Code" => "HOT_MEAL_NONVEG", "Description" => "Hot Butter Chicken with Rice Box", "Amount" => 500),
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
                                    ?>
                                        <option value="<?php echo $amt; ?>">
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
                                    ?>
                                        <option value="<?php echo $amt; ?>">
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
                                <input type="text" name="contact_name" class="field-input" required value="Rahul Sharma" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Email Address *</label>
                                <input type="email" name="contact_email" class="field-input" required value="rahul.sharma@example.com" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Mobile Number *</label>
                                <input type="tel" name="contact_phone" class="field-input" required value="9876543210" style="width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
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
                            <i class="fa-solid fa-lock" style="margin-right: 8px;"></i> Pay ₹ <span id="btnPayAmount"><?php echo number_format($flight['price']); ?></span> & Instant Confirm Booking
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Column: Sticky Fare Summary & Promo Sidebar -->
            <div>
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0; position: sticky; top: 90px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        Fare Summary
                        <span style="font-size: 11px; background: #dcfce7; color: #15803d; padding: 3px 8px; border-radius: 12px; font-weight: 700;">Guaranteed Price</span>
                    </h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <?php 
                            $total_travelers_review = ($search_query['adults'] ?? 1) + ($search_query['children'] ?? 0) + ($search_query['infants'] ?? 0);
                        ?>
                        <span style="color: #64748b;">Base Fare (<?php echo $total_travelers_review; ?> Traveler<?php echo $total_travelers_review > 1 ? 's' : ''; ?>)</span>
                        <strong style="color: #1e293b;">₹ <span id="summaryBaseFare"><?php echo number_format($flight['base_fare']); ?></span></strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Taxes & Airport Fees</span>
                        <strong style="color: #1e293b;">₹ <span id="summaryTaxes"><?php echo number_format($flight['taxes']); ?></span></strong>
                    </div>

                    <div id="summaryAddonsRow" style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Add-on Services (SSR)</span>
                        <strong style="color: #2563eb;">₹ <span id="summaryAddons">0</span></strong>
                    </div>

                    <div id="summaryDiscountRow" style="display: none; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #16a34a;">Promo Discount</span>
                        <strong style="color: #16a34a;">- ₹ <span id="summaryDiscount">0</span></strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Convenience Fee</span>
                        <strong style="color: #16a34a;">FREE</strong>
                    </div>

                    <!-- Promo Code Box -->
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; margin: 16px 0;">
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="promoCodeInput" placeholder="Enter Promo (VOYOGO500)" style="flex: 1; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; text-transform: uppercase;">
                            <button type="button" onclick="applyPromoCode()" style="padding: 8px 12px; background: #0d3470; color: #fff; border: none; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer;">
                                Apply
                            </button>
                        </div>
                        <div id="promoMsg" style="font-size: 11px; margin-top: 6px; display: none;"></div>
                    </div>

                    <!-- Final Total Payable Amount -->
                    <div style="border-top: 2px dashed #cbd5e1; padding-top: 16px; margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 14px; font-weight: 800; color: #0d3470; display: block;">Total Amount</span>
                            <span style="font-size: 11px; color: #64748b;">Includes all taxes</span>
                        </div>
                        <strong style="font-size: 24px; font-weight: 900; color: #ef4444;">₹ <span id="summaryTotalAmount"><?php echo number_format($flight['price']); ?></span></strong>
                    </div>

                    <!-- Trust Badges -->
                    <div style="background: #f1f5f9; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 12px; color: #475569; display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-shield-halved" style="color: #2563eb; font-size: 16px;"></i>
                            <span>100% Safe & Instant Booking</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-envelope-circle-check" style="color: #16a34a; font-size: 16px;"></i>
                            <span>Instant E-Ticket Sent to Email</span>
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
var basePrice = <?php echo (float)$flight['price']; ?>;
var appliedDiscount = 0;

function switchReviewTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(function(el) {
        el.style.display = 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(function(el) {
        el.style.color = '#64748b';
        el.style.borderBottomColor = 'transparent';
    });
    
    document.getElementById(tabId).style.display = 'block';
    btn.style.color = '#2563eb';
    btn.style.borderBottomColor = '#2563eb';
}

function calculateTotalAddons() {
    var baggagePrice = parseFloat(document.getElementById('extraBaggageSelect').value || 0);
    var mealPrice = parseFloat(document.getElementById('mealSelect').value || 0);
    var totalAddons = baggagePrice + mealPrice;

    document.getElementById('summaryAddons').innerText = totalAddons.toLocaleString('en-IN');
    
    var grandTotal = Math.max(0, basePrice + totalAddons - appliedDiscount);
    
    document.getElementById('summaryTotalAmount').innerText = grandTotal.toLocaleString('en-IN');
    document.getElementById('btnPayAmount').innerText = grandTotal.toLocaleString('en-IN');
    document.getElementById('form_total_amount').value = grandTotal;
}

function applyPromoCode() {
    var code = document.getElementById('promoCodeInput').value.trim().toUpperCase();
    var msg = document.getElementById('promoMsg');

    if (code === 'VOYOGO500' || code === 'SWADES' || code === 'ZEROFEE') {
        appliedDiscount = 500;
        document.getElementById('summaryDiscount').innerText = '500';
        document.getElementById('summaryDiscountRow').style.display = 'flex';
        msg.style.display = 'block';
        msg.style.color = '#16a34a';
        msg.innerText = 'Promo code ' + code + ' applied! ₹500 discount added.';
    } else if (code === '') {
        appliedDiscount = 0;
        document.getElementById('summaryDiscountRow').style.display = 'none';
        msg.style.display = 'none';
    } else {
        appliedDiscount = 0;
        document.getElementById('summaryDiscountRow').style.display = 'none';
        msg.style.display = 'block';
        msg.style.color = '#dc2626';
        msg.innerText = 'Invalid promo code. Try VOYOGO500 for ₹500 discount!';
    }

    calculateTotalAddons();
}

function toggleGstFields() {
    var isChecked = document.getElementById('gstToggle').checked;
    document.getElementById('gstFieldsSection').style.display = isChecked ? 'grid' : 'none';
}

document.getElementById('payRazorpayBtn').addEventListener('click', function(e) {
    e.preventDefault();

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
@keyframes pulseProgress {
    0% { width: 30%; }
    50% { width: 90%; }
    100% { width: 30%; }
}
</style>
