<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Master Franchise Bookings History</h2>
        <p style="font-size: 13px; color: #64748b;">Consolidated flight and hotel bookings executed across all franchise store branches.</p>
    </div>
</div>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
    <button type="button" id="tabBtnFlights" class="tab-btn active" onclick="switchTab('flights')">
        <i class="fa-solid fa-plane-departure"></i> Flight Bookings (<?php echo count($flight_bookings ?? []); ?>)
    </button>
    <button type="button" id="tabBtnHotels" class="tab-btn" onclick="switchTab('hotels')">
        <i class="fa-solid fa-hotel"></i> Hotel Bookings (<?php echo count($hotel_bookings ?? []); ?>)
    </button>
</div>

<!-- ==================== FLIGHT BOOKINGS ==================== -->
<div id="tabFlights" class="card tab-content">
    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Franchise Flight Bookings</h3>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Booking Ref & PNR</th>
                    <th>Branch / Store</th>
                    <th>Route / Sector</th>
                    <th>Airline</th>
                    <th>Travel Date</th>
                    <th>Passengers</th>
                    <th>Wallet Deducted</th>
                    <th>Status</th>
                    <th>Booked On</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($flight_bookings)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 32px;">
                        <i class="fa-solid fa-plane-slash" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No flight bookings from franchise stores yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($flight_bookings as $f): ?>
                <tr>
                    <td>
                        <strong style="color: #0284c7;"><?php echo htmlspecialchars($f['booking_ref']); ?></strong><br>
                        <small style="color: #64748b;">PNR: <strong><?php echo htmlspecialchars($f['pnr'] ?: 'PENDING'); ?></strong></small>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($f['store_name'] ?? ('Store #' . $f['store_id'])); ?></strong><br>
                        <small style="color: #0284c7;"><?php echo htmlspecialchars($f['agent_code'] ?? ''); ?></small>
                    </td>
                    <td>
                        <span style="font-weight: 700;"><?php echo htmlspecialchars($f['origin']); ?></span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 11px; margin: 0 4px; color: #64748b;"></i>
                        <span style="font-weight: 700;"><?php echo htmlspecialchars($f['destination']); ?></span>
                        <div style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['trip_type'] ?? 'one_way'); ?></div>
                    </td>
                    <td>
                        <div><?php echo htmlspecialchars($f['airline_name'] ?: ($f['airline_code'] ?? 'Airline')); ?></div>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($f['flight_number']); ?></small>
                    </td>
                    <td>
                        <?php echo !empty($f['departure_datetime']) ? date('d M Y', strtotime($f['departure_datetime'])) : 'N/A'; ?>
                    </td>
                    <td>
                        <i class="fa-solid fa-users" style="font-size: 11px; color: #64748b;"></i> Pax
                    </td>
                    <td>
                        <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format($f['total_amount'], 2); ?></strong>
                    </td>
                    <td>
                        <span class="badge <?php echo ($f['status'] === 'confirmed') ? 'badge-success' : 'badge-info'; ?>">
                            <?php echo htmlspecialchars($f['status'] ?? 'confirmed'); ?>
                        </span>
                    </td>
                    <td style="font-size: 12.5px; color: #64748b; white-space: nowrap;">
                        <?php echo date('d M Y, h:i A', strtotime($f['created_at'])); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==================== HOTEL BOOKINGS ==================== -->
<div id="tabHotels" class="card tab-content" style="display: none;">
    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Franchise Hotel Bookings</h3>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Booking Ref</th>
                    <th>Branch / Store</th>
                    <th>Hotel Name</th>
                    <th>Check In / Out</th>
                    <th>Primary Guest</th>
                    <th>Wallet Deducted</th>
                    <th>Status</th>
                    <th>Booked On</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hotel_bookings)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 32px;">
                        <i class="fa-solid fa-hotel" style="font-size: 28px; margin-bottom: 8px; display: block; color: #cbd5e1;"></i>
                        No hotel bookings from franchise stores yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($hotel_bookings as $h): ?>
                <tr>
                    <td>
                        <strong style="color: #0284c7;"><?php echo htmlspecialchars($h['booking_ref']); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($h['store_name'] ?? ('Store #' . $h['store_id'])); ?></strong><br>
                        <small style="color: #0284c7;"><?php echo htmlspecialchars($h['agent_code'] ?? ''); ?></small>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($h['hotel_name']); ?></strong><br>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($h['room_type']); ?></small>
                    </td>
                    <td>
                        <div><strong>In:</strong> <?php echo !empty($h['checkin_date']) ? date('d M Y', strtotime($h['checkin_date'])) : 'N/A'; ?></div>
                        <div><small><strong>Out:</strong> <?php echo !empty($h['checkout_date']) ? date('d M Y', strtotime($h['checkout_date'])) : 'N/A'; ?></small></div>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($h['primary_guest_name']); ?></strong><br>
                        <small style="color: #64748b;"><?php echo htmlspecialchars($h['guest_phone']); ?></small>
                    </td>
                    <td>
                        <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format($h['total_amount'], 2); ?></strong>
                    </td>
                    <td>
                        <span class="badge <?php echo ($h['status'] === 'confirmed') ? 'badge-success' : 'badge-info'; ?>">
                            <?php echo htmlspecialchars($h['status'] ?? 'confirmed'); ?>
                        </span>
                    </td>
                    <td style="font-size: 12.5px; color: #64748b; white-space: nowrap;">
                        <?php echo date('d M Y, h:i A', strtotime($h['created_at'])); ?>
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
function switchTab(tab) {
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
