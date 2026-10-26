<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$meta = json_decode($booking['passenger_details'] ?? '{}', true) ?: array();
$passengers = $meta['passengers'] ?? array();
$ssr_addons = $meta['ssr_addons'] ?? array();
$return_flight = $meta['return_flight'] ?? null;
$holdExpiresAt = !empty($booking['hold_expires_at']) ? strtotime($booking['hold_expires_at']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hold Quotation Voucher: <?php echo htmlspecialchars($booking['pnr']); ?> - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
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
            background: linear-gradient(135deg, #09204b, #003B95);
            color: #ffffff;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand img { max-height: 42px; margin-bottom: 4px; }
        .header-brand div { font-size: 11px; color: #78B722; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .pnr-box {
            text-align: right;
            background: rgba(255,255,255,0.12);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .pnr-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #fde047; font-weight: 700; }
        .pnr-code { font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 1px; font-family: 'Outfit', sans-serif; }

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

        .flight-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
        }

        .hold-deadline-banner {
            background: #fff7ed;
            border: 1.5px solid #fed7aa;
            border-radius: 8px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }
        .table-data th {
            background: #f8fafc;
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-data td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }

        .action-bar {
            max-width: 850px;
            margin: 20px auto 0;
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

<div class="action-bar">
    <a href="<?php echo site_url('franchise/hold_bookings'); ?>" style="color: #475569; text-decoration: none; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Manage Hold Bookings
    </a>
    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="window.print()" style="background: #09204b; color: #ffffff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-print"></i> Print Voucher
        </button>
        <?php if ($booking['status'] === 'held' && ($holdExpiresAt == 0 || $holdExpiresAt > time())): ?>
        <a href="<?php echo site_url('franchise/confirm_hold_ticket/' . urlencode($booking['booking_ref'])); ?>" onclick="return confirm('Deduct float and confirm this ticket now?')" style="background: #78B722; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-size: 13.5px; font-weight: 800; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-circle-check"></i> Issue Ticket Now
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="voucher-container">
    <!-- Header -->
    <div class="voucher-header">
        <div class="header-brand">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">VOYOGO</h2>
            <div>OFFICIAL FLIGHT HOLD QUOTATION &amp; ITINERARY</div>
        </div>
        <div class="pnr-box">
            <div class="pnr-title"><i class="fa-solid fa-hand-holding-dollar"></i> AIRLINE HOLD PNR</div>
            <div class="pnr-code"><?php echo htmlspecialchars($booking['pnr']); ?></div>
        </div>
    </div>

    <!-- Agent strip -->
    <div class="agent-bar">
        <div>
            <strong>Issued By Agency:</strong> <?php echo htmlspecialchars($store['store_name']); ?> (Code: <?php echo htmlspecialchars($store['agent_code']); ?>)
        </div>
        <div>
            <strong>Booking Ref:</strong> <?php echo htmlspecialchars($booking['booking_ref']); ?> &bull; <strong>Booking Date:</strong> <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?>
        </div>
    </div>

    <div class="voucher-body">
        <!-- Hold Deadline Banner -->
        <div class="hold-deadline-banner">
            <div>
                <strong style="color: #c2410c; font-size: 14.5px; display: block; margin-bottom: 2px;">
                    <i class="fa-solid fa-clock"></i> SEATS TEMPORARILY RESERVED ON HOLD
                </strong>
                <span style="font-size: 12.5px; color: #7c2d12;">
                    Please confirm this reservation before the deadline. Unconfirmed hold bookings are auto-released by the airline.
                </span>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 11px; color: #9a3412; font-weight: 700; text-transform: uppercase;">Ticketing Cutoff</span>
                <div style="font-size: 15px; font-weight: 800; color: #b91c1c;">
                    <?php echo !empty($booking['hold_expires_at']) ? date('d M Y, h:i A', strtotime($booking['hold_expires_at'])) : 'Standard 4 Hours'; ?>
                </div>
            </div>
        </div>

        <!-- Flight Itinerary -->
        <h4 style="font-size: 15px; font-weight: 800; color: #09204b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-plane-departure" style="color: #78B722;"></i> Flight Details (Onward)
        </h4>
        <div class="flight-card">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="font-size: 18px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($booking['airline_name']); ?></div>
                    <span style="background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700; padding: 2px 8px; border-radius: 4px;"><?php echo htmlspecialchars($booking['flight_number']); ?></span>
                </div>
                <span style="background: #fef3c7; color: #b45309; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 12px; border: 1px solid #fde68a;">
                    STATUS: <?php echo strtoupper($booking['status']); ?>
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: center;">
                <div>
                    <span style="font-size: 12px; color: #64748b; font-weight: 600;">Origin</span>
                    <h3 style="font-size: 24px; font-weight: 800; color: #09204b; margin: 2px 0;"><?php echo htmlspecialchars($booking['origin']); ?></h3>
                    <div style="font-size: 13px; font-weight: 600; color: #334155;"><?php echo !empty($booking['departure_datetime']) ? date('d M Y, h:i A', strtotime($booking['departure_datetime'])) : 'N/A'; ?></div>
                </div>

                <div style="text-align: center;">
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600; display: block; margin-bottom: 4px;"><?php echo htmlspecialchars($meta['duration'] ?? 'Direct'); ?></span>
                    <i class="fa-solid fa-plane" style="color: #78B722; font-size: 18px;"></i>
                    <span style="font-size: 11px; color: #94a3b8; display: block; margin-top: 4px;"><?php echo ($meta['stops'] ?? 0) == 0 ? 'Non-Stop' : ($meta['stops'] . ' Stop(s)'); ?></span>
                </div>

                <div style="text-align: right;">
                    <span style="font-size: 12px; color: #64748b; font-weight: 600;">Destination</span>
                    <h3 style="font-size: 24px; font-weight: 800; color: #09204b; margin: 2px 0;"><?php echo htmlspecialchars($booking['destination']); ?></h3>
                    <div style="font-size: 13px; font-weight: 600; color: #334155;"><?php echo htmlspecialchars($meta['arrival_time'] ?? ''); ?></div>
                </div>
            </div>
        </div>

        <!-- Passenger List -->
        <h4 style="font-size: 15px; font-weight: 800; color: #09204b; margin-top: 24px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-users" style="color: #78B722;"></i> Passenger Information (<?php echo count($passengers); ?>)
        </h4>
        <div style="border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 24px;">
            <table class="table-data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Passenger Name</th>
                        <th>Type / Gender</th>
                        <th>Passport / ID</th>
                        <th>Hold Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($passengers)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94a3b8;">No passenger list recorded.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($passengers as $idx => $p): ?>
                    <tr>
                        <td><?php echo $idx + 1; ?></td>
                        <td><strong><?php echo htmlspecialchars(($p['title'] ?? 'Mr') . ' ' . ($p['name'] ?? 'Passenger')); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['type'] ?? 'Adult'); ?> (<?php echo htmlspecialchars($p['gender'] ?? 'M'); ?>)</td>
                        <td><?php echo !empty($p['passport_no']) ? htmlspecialchars($p['passport_no']) : 'N/A'; ?></td>
                        <td><span style="color: #b45309; font-weight: 700;">Reserved (Hold)</span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Quotation Fare Summary -->
        <h4 style="font-size: 15px; font-weight: 800; color: #09204b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-receipt" style="color: #78B722;"></i> Fare Quotation Breakdown
        </h4>
        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                <span style="color: #64748b;">Flight Base Fare:</span>
                <strong>₹ <?php echo number_format($booking['base_fare'], 2); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                <span style="color: #64748b;">Airline Taxes &amp; Surcharges:</span>
                <strong>₹ <?php echo number_format($booking['taxes'], 2); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                <span style="color: #64748b;">Advance Amount Paid to Hold:</span>
                <strong style="color: #15803d;">₹ <?php echo number_format($booking['advance_amount'] ?? 0, 2); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1.5px solid #cbd5e1; padding-top: 10px; font-size: 16px; font-weight: 800;">
                <span style="color: #09204b;">Total Customer Payable Quotation:</span>
                <span style="color: #78B722; font-size: 20px;">₹ <?php echo number_format($booking['total_amount'], 2); ?></span>
            </div>
        </div>

        <!-- Terms & Disclaimer -->
        <div style="font-size: 11px; color: #64748b; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 16px;">
            &bull; This is an official booking quotation and seat reservation confirmation, not a valid boarding pass.<br>
            &bull; Seats and fares are held temporarily at the airline system. To prevent cancellation, full ticketing confirmation must be executed before the cutoff deadline.<br>
            &bull; Once ticketed, standard airline cancellation and date change policies apply.<br>
            &bull; For any modifications or assistance, contact support at supports@voyogo.com or agency desk.
        </div>
    </div>
</div>

</body>
</html>
