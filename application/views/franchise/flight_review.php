<div style="margin-bottom: 20px;">
    <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 6px; color: #475569; text-decoration: none; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Flight Results
    </a>
</div>

<form method="post" action="<?php echo site_url('franchise/flight_book'); ?>">
    <input type="hidden" name="flight_json" value='<?php echo json_encode($flight); ?>'>
    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
    <input type="hidden" name="adults" value="<?php echo $adults; ?>">
    <input type="hidden" name="children" value="<?php echo $children; ?>">
    <input type="hidden" name="infants" value="<?php echo $infants; ?>">

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start;">

        <!-- Left Column: Flight Summary & Passenger Details -->
        <div>
            <!-- Flight Overview Box -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 26px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-size: 17px; font-weight: 800; color: #0f172a;">
                        <i class="fa-solid fa-plane-departure" style="color: #78B722; margin-right: 8px;"></i>
                        Flight Itinerary
                    </div>
                    <span style="background: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 12px;">
                        Confirmed Flight
                    </span>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 18px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($flight['airline'] ?? 'Airline'); ?></div>
                        <div style="font-size: 13px; color: #64748b;"><?php echo htmlspecialchars($flight['flight_number'] ?? ''); ?> &bull; Economy</div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 32px; text-align: center;">
                        <div>
                            <div style="font-size: 20px; font-weight: 800; color: #0f172a;"><?php echo date('H:i', strtotime($flight['departure'] ?? 'now')); ?></div>
                            <div style="font-size: 13px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($flight['origin'] ?? 'BOM'); ?></div>
                            <div style="font-size: 11px; color: #94a3b8;"><?php echo date('d M Y', strtotime($flight['departure'] ?? 'now')); ?></div>
                        </div>

                        <div style="font-size: 12px; color: #64748b;">
                            <span><?php echo htmlspecialchars($flight['duration'] ?? '2h 15m'); ?></span>
                            <div style="width: 80px; height: 2px; background: #cbd5e1; margin: 4px auto; position: relative;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -5px; left: 35px; font-size: 10px; color: #78B722;"></i>
                            </div>
                            <span style="color: #15803d; font-weight: 600;">Non-stop</span>
                        </div>

                        <div>
                            <div style="font-size: 20px; font-weight: 800; color: #0f172a;"><?php echo date('H:i', strtotime($flight['arrival'] ?? 'now')); ?></div>
                            <div style="font-size: 13px; font-weight: 700; color: #475569;"><?php echo htmlspecialchars($flight['destination'] ?? 'DEL'); ?></div>
                            <div style="font-size: 11px; color: #94a3b8;"><?php echo date('d M Y', strtotime($flight['arrival'] ?? 'now')); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger Details Form -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 26px; margin-bottom: 24px;">
                <div style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <i class="fa-solid fa-users" style="color: #0284c7; margin-right: 8px;"></i> Passenger Details (<?php echo (int)$pax_count; ?> Pax)
                </div>

                <?php for ($i = 0; $i < $pax_count; $i++): ?>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 20px; margin-bottom: 16px;">
                    <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 12px;">
                        Passenger #<?php echo ($i + 1); ?> <?php echo ($i < $adults) ? '(Adult)' : '(Child)'; ?>
                    </div>
                    <input type="hidden" name="pax_type[]" value="<?php echo ($i < $adults) ? 'Adult' : 'Child'; ?>">

                    <div style="display: grid; grid-template-columns: 100px 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Title</label>
                            <select name="title[]" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px;">First / Given Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="first_name[]" required placeholder="First name as per ID" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Last / Surname <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="last_name[]" required placeholder="Last name as per ID" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        </div>
                    </div>
                </div>
                <?php endfor; ?>

                <!-- Contact Info -->
                <div style="margin-top: 20px;">
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Customer Contact Information (For E-Ticket)</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Mobile Number <span style="color: #ef4444;">*</span></label>
                            <input type="tel" name="contact_phone" required placeholder="e.g. 9876543210" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 4px;">Email Address <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="contact_email" required placeholder="customer@example.com" style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Wallet Settlement Card -->
        <div>
            <div style="background: #ffffff; border: 2px solid <?php echo $can_book ? '#86efac' : '#fca5a5'; ?>; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); position: sticky; top: 85px;">
                <div style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-wallet" style="color: <?php echo $can_book ? '#16a34a' : '#dc2626'; ?>;"></i>
                    Franchise Wallet Settlement
                </div>

                <!-- Price Breakdown -->
                <div style="margin-bottom: 16px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #475569; margin-bottom: 8px;">
                        <span>Base Fare & Taxes (<?php echo (int)$pax_count; ?> Pax)</span>
                        <span>₹ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #16a34a; margin-bottom: 8px;">
                        <span>B2B Franchise Commission</span>
                        <span>Included in Net</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #09204b; margin-top: 10px;">
                        <span>Total Payable:</span>
                        <span>₹ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>

                <!-- Wallet Balance Audit -->
                <div style="background: #f8fafc; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #475569; margin-bottom: 6px;">
                        <span>Your Current Balance:</span>
                        <strong style="color: #0f172a;">₹ <?php echo number_format($store['wallet_balance'], 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #475569; margin-bottom: 6px;">
                        <span>Deduction for Booking:</span>
                        <strong style="color: #dc2626;">- ₹ <?php echo number_format($total_amount, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; color: #15803d; border-top: 1px solid #e2e8f0; padding-top: 6px;">
                        <span>Balance After Booking:</span>
                        <span>₹ <?php echo number_format($store['wallet_balance'] - $total_amount, 2); ?></span>
                    </div>
                </div>

                <?php if ($can_book): ?>
                    <button type="submit" style="width: 100%; background: #78B722; color: #ffffff; border: none; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(120, 183, 34, 0.35);">
                        <i class="fa-solid fa-circle-check"></i>
                        Confirm & Deduct ₹ <?php echo number_format($total_amount, 2); ?>
                    </button>
                    <div style="text-align: center; font-size: 11.5px; color: #64748b; margin-top: 10px;">
                        <i class="fa-solid fa-lock"></i> Instant ticket issuance from store float
                    </div>
                <?php else: ?>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px; color: #991b1b; font-size: 12.5px; margin-bottom: 12px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <strong>Insufficient Balance:</strong> You need at least ₹ <?php echo number_format($total_amount, 2); ?> to issue this ticket. Please contact Franchise Admin for a float top-up.
                    </div>
                    <button type="button" disabled style="width: 100%; background: #cbd5e1; color: #64748b; border: none; padding: 14px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: not-allowed;">
                        Booking Blocked (Low Float)
                    </button>
                <?php endif; ?>

            </div>
        </div>

    </div>
</form>
