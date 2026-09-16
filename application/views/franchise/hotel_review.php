<div style="margin-bottom: 20px;">
    <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 6px; color: #475569; text-decoration: none; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Hotel Details
    </a>
</div>

<form method="post" action="<?php echo site_url('franchise/hotel_book'); ?>">
    <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel_id); ?>">
    <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hotel_name); ?>">
    <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>">
    <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
    <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start;">

        <!-- Left Column: Hotel Details & Guest Information -->
        <div>
            <!-- Hotel Overview Card -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 26px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-size: 17px; font-weight: 800; color: #0f172a;">
                        <i class="fa-solid fa-hotel" style="color: #78B722; margin-right: 8px;"></i>
                        Hotel Reservation Summary
                    </div>
                    <span style="background: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 12px;">
                        Instant Confirmation
                    </span>
                </div>

                <div style="margin-bottom: 14px;">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 4px;"><?php echo htmlspecialchars($hotel_name); ?></h2>
                    <div style="font-size: 14px; font-weight: 600; color: #0284c7;"><?php echo htmlspecialchars($room_type); ?></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; background: #f8fafc; border-radius: 8px; padding: 14px 18px; font-size: 13px;">
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block; text-transform: uppercase;">Check-in</span>
                        <strong><?php echo date('D, d M Y', strtotime($checkin)); ?></strong>
                        <div style="color: #64748b; font-size: 11.5px;">From 02:00 PM</div>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block; text-transform: uppercase;">Check-out</span>
                        <strong><?php echo date('D, d M Y', strtotime($checkout)); ?></strong>
                        <div style="color: #64748b; font-size: 11.5px;">Until 11:00 AM</div>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block; text-transform: uppercase;">Rooms</span>
                        <strong><?php echo (int)$rooms; ?> Room(s)</strong>
                        <div style="color: #166534; font-size: 11.5px; font-weight: 600;">Breakfast Included</div>
                    </div>
                </div>
            </div>

            <!-- Primary Guest Information -->
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 26px;">
                <div style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <i class="fa-solid fa-user-check" style="color: #0284c7; margin-right: 8px;"></i>
                    Primary Guest Details
                </div>

                <div style="display: grid; grid-template-columns: 120px 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Title</label>
                        <select name="guest_title" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Ms">Ms</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Full Name (As per Passport / Gov ID) <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="primary_guest_name" required placeholder="e.g. Rajesh Sharma" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Contact Phone <span style="color: #ef4444;">*</span></label>
                        <input type="tel" name="guest_phone" required placeholder="e.g. 9876543210" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Email ID for Voucher <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="guest_email" required placeholder="guest@example.com" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px;">Special Requests (Optional)</label>
                    <input type="text" name="special_request" placeholder="e.g. Early check-in requested, Quiet room on upper floor" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
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
                        <span>Room Charges & Taxes</span>
                        <span>₹ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #16a34a; margin-bottom: 8px;">
                        <span>Franchise B2B Commission</span>
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
                        <span>Store Wallet Float:</span>
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
                        <i class="fa-solid fa-lock"></i> Instant hotel voucher generation from float
                    </div>
                <?php else: ?>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px; color: #991b1b; font-size: 12.5px; margin-bottom: 12px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <strong>Insufficient Balance:</strong> You need ₹ <?php echo number_format($total_amount, 2); ?> in your wallet float to confirm this hotel. Contact Franchise Admin to top up.
                    </div>
                    <button type="button" disabled style="width: 100%; background: #cbd5e1; color: #64748b; border: none; padding: 14px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: not-allowed;">
                        Booking Blocked (Low Float)
                    </button>
                <?php endif; ?>

            </div>
        </div>

    </div>
</form>
