<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a;">Agency Booking History</h2>
        <p style="font-size: 13px; color: #64748b;">Review confirmed tickets, hotel vouchers, and reprint client itineraries.</p>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?php echo site_url('franchise/flight'); ?>" style="background: #78B722; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus"></i> New Flight Booking
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" style="background: #09204b; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-plus"></i> New Hotel Booking
        </a>
    </div>
</div>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
    <button type="button" id="tabBtnFlights" class="tab-btn active" onclick="switchBookingTab('flights')">
        <i class="fa-solid fa-plane-departure"></i> Flight Bookings (<?php echo count($flight_bookings ?? []); ?>)
    </button>
    <button type="button" id="tabBtnHotels" class="tab-btn" onclick="switchBookingTab('hotels')">
        <i class="fa-solid fa-hotel"></i> Hotel Bookings (<?php echo count($hotel_bookings ?? []); ?>)
    </button>
</div>

<!-- Flight Bookings List -->
<div id="tabFlights" class="tab-content">
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">PNR & Ref</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Sector / Route</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Airline</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Travel Date</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Amount Deducted</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Status</th>
                    <th style="padding: 14px 18px; text-align: right; font-weight: 700; color: #475569;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($flight_bookings)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px;">
                        <i class="fa-solid fa-plane-slash" style="font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                        No flight bookings executed by your agency yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($flight_bookings as $f): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px 18px;">
                        <span style="font-size: 15px; font-weight: 800; color: #09204b;"><?php echo htmlspecialchars($f['pnr']); ?></span><br>
                        <small style="color: #64748b; font-size: 11px;">Ref: <?php echo htmlspecialchars($f['booking_ref']); ?></small>
                    </td>
                    <td style="padding: 14px 18px;">
                        <strong><?php echo htmlspecialchars($f['origin']); ?></strong>
                        <i class="fa-solid fa-arrow-right" style="font-size: 11px; margin: 0 4px; color: #78B722;"></i>
                        <strong><?php echo htmlspecialchars($f['destination']); ?></strong>
                        <div style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['trip_type'] ?? 'one_way'); ?></div>
                    </td>
                    <td style="padding: 14px 18px;">
                        <div><?php echo htmlspecialchars($f['airline_name'] ?: ($f['airline_code'] ?? 'Airline')); ?></div>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($f['flight_number']); ?></small>
                    </td>
                    <td style="padding: 14px 18px;">
                        <?php echo !empty($f['departure_datetime']) ? date('d M Y', strtotime($f['departure_datetime'])) : 'N/A'; ?>
                    </td>
                    <td style="padding: 14px 18px;">
                        <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format($f['total_amount'], 2); ?></strong>
                    </td>
                    <td style="padding: 14px 18px;">
                        <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                            <?php echo htmlspecialchars($f['status'] ?? 'confirmed'); ?>
                        </span>
                    </td>
                    <td style="padding: 14px 18px; text-align: right;">
                        <a href="<?php echo site_url('franchise/flight_ticket/' . urlencode($f['booking_ref'])); ?>" target="_blank" style="background: #eff6ff; color: #2563eb; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-print"></i> E-Ticket
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hotel Bookings List -->
<div id="tabHotels" class="tab-content" style="display: none;">
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Booking Ref</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Hotel & Location</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Primary Guest</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Dates</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Amount Deducted</th>
                    <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Status</th>
                    <th style="padding: 14px 18px; text-align: right; font-weight: 700; color: #475569;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hotel_bookings)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px;">
                        <i class="fa-solid fa-hotel" style="font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                        No hotel bookings executed by your agency yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($hotel_bookings as $h): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px 18px;">
                        <strong style="color: #09204b; font-size: 14px;"><?php echo htmlspecialchars($h['booking_ref']); ?></strong>
                    </td>
                    <td style="padding: 14px 18px;">
                        <strong><?php echo htmlspecialchars($h['hotel_name']); ?></strong><br>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($h['room_type']); ?></small>
                    </td>
                    <td style="padding: 14px 18px;">
                        <strong><?php echo htmlspecialchars($h['primary_guest_name']); ?></strong><br>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($h['guest_phone']); ?></small>
                    </td>
                    <td style="padding: 14px 18px;">
                        <div><strong>In:</strong> <?php echo date('d M Y', strtotime($h['checkin_date'])); ?></div>
                        <small style="color: #64748b;"><strong>Out:</strong> <?php echo date('d M Y', strtotime($h['checkout_date'])); ?></small>
                    </td>
                    <td style="padding: 14px 18px;">
                        <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format($h['total_amount'], 2); ?></strong>
                    </td>
                    <td style="padding: 14px 18px;">
                        <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                            <?php echo htmlspecialchars($h['status'] ?? 'confirmed'); ?>
                        </span>
                    </td>
                    <td style="padding: 14px 18px; text-align: right;">
                        <a href="<?php echo site_url('franchise/hotel_voucher/' . urlencode($h['booking_ref'])); ?>" target="_blank" style="background: #eff6ff; color: #2563eb; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-print"></i> Voucher
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .tab-btn {
        background: none;
        border: none;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s;
    }
    .tab-btn:hover { background: #f1f5f9; color: #0f172a; }
    .tab-btn.active { background: #09204b; color: #ffffff; }
</style>

<script>
function switchBookingTab(tab) {
    if (tab === 'flights') {
        document.getElementById('tabFlights').style.display = 'block';
        document.getElementById('tabHotels').style.display = 'none';
        document.getElementById('tabBtnFlights').classList.add('active');
        document.getElementById('tabBtnHotels').classList.remove('active');
    } else {
        document.getElementById('tabFlights').style.display = 'none';
        document.getElementById('tabHotels').style.display = 'block';
        document.getElementById('tabBtnFlights').classList.remove('active');
        document.getElementById('tabBtnHotels').classList.add('active');
    }
}
</script>
