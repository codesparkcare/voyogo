<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Voucher: <?php echo htmlspecialchars($booking['booking_ref'] ?? ($booking['booking_reference'] ?? 'HTL-VOUCHER')); ?> - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', -apple-system, sans-serif; }
        body { background: #f1f5f9; color: #0f172a; padding: 30px 15px; }
        .voucher-container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }
        .voucher-header {
            background: linear-gradient(135deg, #09204b, #0d3470);
            color: #ffffff;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand img { max-height: 42px; margin-bottom: 4px; }
        .header-brand div { font-size: 11px; color: #78B722; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .conf-box {
            text-align: right;
            background: rgba(255,255,255,0.12);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .conf-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #93c5fd; font-weight: 600; }
        .conf-code { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; font-family: 'Outfit', sans-serif; }

        .agent-bar {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
            color: #475569;
        }

        .voucher-body { padding: 30px 32px; }

        .hotel-hero-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
            background: #ffffff;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .hotel-hero-img {
            width: 140px;
            height: 115px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
        }

        .timing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        table.guest-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 24px;
        }
        table.guest-table th {
            background: #f1f5f9;
            padding: 11px 14px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
        }
        table.guest-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .voucher-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 32px;
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.6;
        }

        .action-bar {
            max-width: 850px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media print {
            body { background: #ffffff; padding: 0; }
            .action-bar { display: none !important; }
            .voucher-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<?php
// Safely unpack booking and metadata
$meta = !empty($booking_meta) ? $booking_meta : (!empty($booking['booking_meta']) ? (is_array($booking['booking_meta']) ? $booking['booking_meta'] : json_decode($booking['booking_meta'], true)) : array());
if (!is_array($meta)) $meta = array();

$bookingRef = $booking['booking_ref'] ?? ($booking['booking_reference'] ?? ('HTL-' . date('Ymd')));
$hotelName = $booking['hotel_name'] ?? ($meta['hotel_name'] ?? 'Luxury Resort');
$hotelAddress = $meta['hotel_address'] ?? ($booking['hotel_address'] ?? 'City Center');
$hotelImage = !empty($meta['hotel_image']) ? $meta['hotel_image'] : (!empty($booking['hotel_image']) ? $booking['hotel_image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80');
$roomType = $booking['room_type'] ?? ($meta['room_type'] ?? 'Deluxe Room');
$boardType = $meta['board_type'] ?? 'Breakfast Included';

$checkinDate = $booking['checkin_date'] ?? ($booking['check_in'] ?? ($meta['checkin'] ?? date('Y-m-d')));
$checkoutDate = $booking['checkout_date'] ?? ($booking['check_out'] ?? ($meta['checkout'] ?? date('Y-m-d', strtotime('+2 days'))));
$checkinTs = strtotime($checkinDate);
$checkoutTs = strtotime($checkoutDate);
$nights = max(1, (int)($meta['nights'] ?? round(($checkoutTs - $checkinTs) / 86400)));
$roomsCount = (int)($meta['rooms_count'] ?? ($meta['rooms'] ?? 1));
$adultsCount = (int)($meta['adults_count'] ?? ($meta['adults'] ?? 2));
$childrenCount = (int)($meta['children_count'] ?? ($meta['children'] ?? 0));
$guestsCount = $adultsCount + $childrenCount;

$primaryName = $booking['primary_guest_name'] ?? ($meta['lead_guest_name'] ?? ($store['store_name'] ?? 'Primary Guest'));
$primaryPhone = $booking['guest_phone'] ?? ($meta['guest_phone'] ?? ($store['phone'] ?? ''));
$primaryEmail = $booking['guest_email'] ?? ($meta['guest_email'] ?? ($store['email'] ?? ''));
$specialReq = $meta['special_requests'] ?? 'Non-smoking room';
$totalAmount = (float)($booking['total_amount'] ?? ($booking['wallet_deducted'] ?? 0));
$status = strtoupper($booking['status'] ?? ($booking['booking_status'] ?? 'CONFIRMED'));
$supplierRef = $meta['supplier_ref'] ?? ($meta['supplier_itinerary_id'] ?? ('BNZ-' . strtoupper(substr(md5($bookingRef), 0, 8))));

// Extract pax list from meta
$paxList = array();
if (!empty($meta['pax']) && is_array($meta['pax'])) {
    foreach ($meta['pax'] as $rIdx => $rData) {
        $roomNum = $rIdx + 1;
        if (!empty($rData['adults']) && is_array($rData['adults'])) {
            foreach ($rData['adults'] as $a) {
                $paxList[] = array(
                    'room'  => "Room $roomNum",
                    'name'  => trim(($a['title'] ?? 'Mr') . ' ' . ($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')),
                    'type'  => 'Adult',
                    'lead'  => (count($paxList) === 0)
                );
            }
        }
        if (!empty($rData['children']) && is_array($rData['children'])) {
            foreach ($rData['children'] as $c) {
                $paxList[] = array(
                    'room'  => "Room $roomNum",
                    'name'  => trim(($c['title'] ?? 'Mstr') . ' ' . ($c['fname'] ?? '') . ' ' . ($c['lname'] ?? '')),
                    'type'  => 'Child (' . ($c['age'] ?? '5') . ' yrs)',
                    'lead'  => false
                );
            }
        }
    }
}

if (empty($paxList)) {
    $paxList[] = array(
        'room'  => 'Room 1',
        'name'  => $primaryName,
        'type'  => 'Adult',
        'lead'  => true
    );
}
?>

<div class="action-bar">
    <a href="<?php echo site_url('franchise/bookings'); ?>" style="color: #09204b; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Back to My Bookings
    </a>

    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="window.print()" style="background: #2563eb; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(37,99,235,0.3);">
            <i class="fa-solid fa-print"></i> Print Hotel Voucher / Save PDF
        </button>
    </div>
</div>

<div class="voucher-container">
    
    <!-- Voucher Header -->
    <div class="voucher-header">
        <div class="header-brand">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo" onerror="this.style.display='none'">
            <div style="font-size: 18px; font-weight: 800; color: #ffffff; font-family: 'Outfit', sans-serif;">VOYOGO TRAVEL NETWORK</div>
            <div style="font-size: 11px; color: #78B722; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">
                Official Hotel Confirmation Voucher
            </div>
        </div>
        <div class="conf-box">
            <div class="conf-title">Booking Confirmation Reference</div>
            <div class="conf-code"><?php echo htmlspecialchars($bookingRef); ?></div>
            <div style="font-size: 10.5px; color: #cbd5e1; margin-top: 3px;">
                Supplier Ref: <strong><?php echo htmlspecialchars($supplierRef); ?></strong>
            </div>
        </div>
    </div>

    <!-- Agency & Settlement Bar -->
    <div class="agent-bar">
        <div>
            <strong>Booking Agency:</strong> <?php echo htmlspecialchars($store['store_name'] ?? ''); ?> (Agent Code: <strong><?php echo htmlspecialchars($store['agent_code'] ?? ''); ?></strong>)
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="background: #dcfce7; color: #15803d; font-size: 11.5px; font-weight: 800; padding: 3px 10px; border-radius: 20px; border: 1px solid #bbf7d0;">
                <i class="fa-solid fa-circle-check"></i> <?php echo $status; ?>
            </span>
            <span style="font-size: 11.5px; color: #64748b;">
                Issued: <?php echo date('d M Y, h:i A', strtotime($booking['created_at'] ?? date('Y-m-d H:i:s'))); ?>
            </span>
        </div>
    </div>

    <div class="voucher-body">

        <!-- Hotel Hero Card -->
        <div class="hotel-hero-card">
            <img src="<?php echo htmlspecialchars($hotelImage); ?>" alt="hotel" class="hotel-hero-img" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80'">
            <div style="flex: 1;">
                <div style="font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                    <?php echo htmlspecialchars($hotelName); ?>
                    <span style="color: #f59e0b; font-size: 13px; margin-left: 6px;">★★★★★</span>
                </div>
                <div style="font-size: 12.5px; color: #64748b; margin-bottom: 8px;">
                    <i class="fa-solid fa-location-dot" style="color: #ef4444; margin-right: 4px;"></i>
                    <?php echo htmlspecialchars($hotelAddress); ?>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                    <span style="font-weight: 700; color: #0284c7;">Room: <?php echo htmlspecialchars($roomType); ?></span>
                    <span style="color: #cbd5e1;">|</span>
                    <span style="color: #16a34a; font-weight: 700;"><i class="fa-solid fa-utensils"></i> <?php echo htmlspecialchars($boardType); ?></span>
                </div>
            </div>
        </div>

        <!-- Schedule / Check-in & Check-out Grid -->
        <div class="timing-grid">
            <div>
                <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 3px;">Check-In Date</span>
                <strong style="font-size: 15px; color: #09204b; display: block;"><?php echo date('D, d M Y', $checkinTs); ?></strong>
                <span style="font-size: 11px; color: #64748b;">From 02:00 PM</span>
            </div>
            <div style="border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 3px;">Check-Out Date</span>
                <strong style="font-size: 15px; color: #09204b; display: block;"><?php echo date('D, d M Y', $checkoutTs); ?></strong>
                <span style="font-size: 11px; color: #64748b;">Until 11:00 AM</span>
            </div>
            <div>
                <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 3px;">Stay Summary</span>
                <strong style="font-size: 15px; color: #16a34a; display: block;"><?php echo $nights; ?> Night(s) &bull; <?php echo $roomsCount; ?> Room(s)</strong>
                <span style="font-size: 11px; color: #64748b;"><?php echo $guestsCount; ?> Guest(s) (<?php echo $adultsCount; ?> Adult, <?php echo $childrenCount; ?> Child)</span>
            </div>
        </div>

        <!-- Guest Details Table -->
        <h4 style="font-size: 14.5px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Guest Information</h4>
        <table class="guest-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Room</th>
                    <th>Guest Name</th>
                    <th>Pax Type</th>
                    <th>Lead Contact Info</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paxList as $idx => $p): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($p['room']); ?></strong></td>
                    <td>
                        <strong style="color: #0f172a; font-size: 13.5px;"><?php echo htmlspecialchars($p['name']); ?></strong>
                        <?php if ($p['lead']): ?>
                            <span style="background: #e0f2fe; color: #0284c7; font-size: 10.5px; font-weight: 800; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">PRIMARY GUEST</span>
                        <?php endif; ?>
                    </td>
                    <td><span style="color: #475569; font-weight: 600;"><?php echo htmlspecialchars($p['type']); ?></span></td>
                    <td>
                        <?php if ($p['lead']): ?>
                            <div style="font-size: 12px; color: #475569;">
                                <div><i class="fa-solid fa-phone" style="font-size: 11px; color: #94a3b8; width: 14px;"></i> <?php echo htmlspecialchars($primaryPhone); ?></div>
                                <div><i class="fa-solid fa-envelope" style="font-size: 11px; color: #94a3b8; width: 14px;"></i> <?php echo htmlspecialchars($primaryEmail); ?></div>
                            </div>
                        <?php else: ?>
                            <span style="color: #94a3b8; font-size: 12px;">-- Same as Lead --</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Settlement & Barcode Section -->
        <div style="border-top: 1.5px dashed #cbd5e1; padding-top: 18px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="font-size: 12.5px; color: #64748b;">
                    Payment Method: <strong>Franchise Store Wallet Float</strong>
                </div>
                <div style="font-size: 12px; color: #16a34a; font-weight: 700; margin-top: 3px;">
                    <i class="fa-solid fa-circle-check"></i> PREPAID (Debited from Agency Float)
                </div>
                <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                    Special Requests: <em><?php echo htmlspecialchars($specialReq); ?></em>
                </div>
            </div>

            <!-- Total Paid Box -->
            <div style="text-align: right;">
                <div style="font-size: 11.5px; color: #64748b; text-transform: uppercase; font-weight: 700;">Total Amount Paid</div>
                <div style="font-size: 24px; font-weight: 800; color: #09204b; font-family: 'Outfit', sans-serif;">
                    ₹ <?php echo number_format($totalAmount, 2); ?>
                </div>
                <div style="font-size: 10.5px; color: #16a34a; font-weight: 700;">Zero Convenience Fee Charged</div>
            </div>
        </div>

        <!-- Simulated Barcode / Security ID -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="180" height="36" viewBox="0 0 180 36" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="5" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="9" y="0" width="5" height="36" fill="#0f172a"/>
                    <rect x="16" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="20" y="0" width="4" height="36" fill="#0f172a"/>
                    <rect x="26" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="30" y="0" width="6" height="36" fill="#0f172a"/>
                    <rect x="38" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="43" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="47" y="0" width="5" height="36" fill="#0f172a"/>
                    <rect x="54" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="58" y="0" width="4" height="36" fill="#0f172a"/>
                    <rect x="64" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="69" y="0" width="5" height="36" fill="#0f172a"/>
                    <rect x="76" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="80" y="0" width="6" height="36" fill="#0f172a"/>
                    <rect x="88" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="92" y="0" width="4" height="36" fill="#0f172a"/>
                    <rect x="98" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="103" y="0" width="5" height="36" fill="#0f172a"/>
                    <rect x="110" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="114" y="0" width="6" height="36" fill="#0f172a"/>
                    <rect x="122" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="127" y="0" width="4" height="36" fill="#0f172a"/>
                    <rect x="133" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="137" y="0" width="5" height="36" fill="#0f172a"/>
                    <rect x="144" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="149" y="0" width="6" height="36" fill="#0f172a"/>
                    <rect x="157" y="0" width="2" height="36" fill="#0f172a"/>
                    <rect x="161" y="0" width="4" height="36" fill="#0f172a"/>
                    <rect x="167" y="0" width="3" height="36" fill="#0f172a"/>
                    <rect x="172" y="0" width="5" height="36" fill="#0f172a"/>
                </svg>
                <span style="font-size: 11px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($bookingRef); ?></span>
            </div>
            <div style="font-size: 11px; color: #64748b; text-align: right;">
                <i class="fa-solid fa-lock" style="color: #16a34a;"></i> Cryptographically Signed &bull; Voyogo Secure Voucher
            </div>
        </div>

    </div>

    <!-- Voucher Footer & Hotel Check-in Policies -->
    <div class="voucher-footer">
        <strong style="color: #0f172a; font-size: 12px; display: block; margin-bottom: 6px;">Important Check-In Instructions & Terms:</strong>
        <p style="margin-bottom: 4px;">1. <strong>Photo ID Required:</strong> Primary guest must be at least 18 years of age and carry a valid Government-issued photo ID with address proof (Aadhaar / Passport / Driving License). PAN card is not accepted.</p>
        <p style="margin-bottom: 4px;">2. <strong>Check-in & Check-out:</strong> Standard check-in is 02:00 PM and check-out is 11:00 AM. Early check-in or late check-out is subject to hotel availability and may incur supplemental charges.</p>
        <p style="margin-bottom: 4px;">3. <strong>Prepaid B2B Voucher:</strong> This voucher is confirmed and fully prepaid by Voyogo partner agency <strong><?php echo htmlspecialchars($store['store_name'] ?? ''); ?></strong>. The guest is NOT required to pay room charges at the front desk, except personal incidentals (room service, laundry, telephone).</p>
        <p>4. <strong>24x7 Emergency Partner Helpline:</strong> For any check-in assistance or supplier confirmation, contact Voyogo B2B Operations at +91 8098999096 or email support@voyogo.com.</p>
    </div>

</div>

</body>
</html>
