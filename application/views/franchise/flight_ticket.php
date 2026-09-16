<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket: <?php echo htmlspecialchars($booking['pnr']); ?> - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; color: #0f172a; padding: 30px 15px; }
        .ticket-container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .ticket-header {
            background: #09204b;
            color: #ffffff;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand img { max-height: 40px; margin-bottom: 4px; }
        .header-brand div { font-size: 11px; color: #78B722; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .pnr-box {
            text-align: right;
            background: rgba(255,255,255,0.1);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .pnr-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #93c5fd; }
        .pnr-code { font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 1px; }

        .agent-bar {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            color: #475569;
        }

        .ticket-body { padding: 32px; }

        .flight-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .flight-route-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
            text-align: center;
        }

        table.pax-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            margin-bottom: 24px;
        }
        table.pax-table th {
            background: #f1f5f9;
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
        }
        table.pax-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .ticket-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 32px;
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.5;
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
            .ticket-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="<?php echo site_url('franchise/bookings'); ?>" style="color: #09204b; text-decoration: none; font-size: 14px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to My Bookings
    </a>

    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="window.print()" style="background: #2563eb; color: #ffffff; border: none; padding: 9px 20px; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-print"></i> Print E-Ticket
        </button>
    </div>
</div>

<div class="ticket-container">
    <div class="ticket-header">
        <div class="header-brand">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
            <div>Electronic Travel Document &bull; Confirmed</div>
        </div>
        <div class="pnr-box">
            <div class="pnr-title">Booking PNR</div>
            <div class="pnr-code"><?php echo htmlspecialchars($booking['pnr']); ?></div>
        </div>
    </div>

    <!-- Agency / Store Banner -->
    <div class="agent-bar">
        <div>
            <strong>Issued By Agency:</strong> <?php echo htmlspecialchars($store['store_name']); ?> (Agent Code: <strong><?php echo htmlspecialchars($store['agent_code']); ?></strong>)
        </div>
        <div>
            <strong>Booking Ref:</strong> <?php echo htmlspecialchars($booking['booking_reference']); ?>
        </div>
    </div>

    <div class="ticket-body">
        <!-- Flight Segment Details -->
        <div class="flight-card">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                <div style="font-size: 16px; font-weight: 800; color: #0f172a;">
                    <i class="fa-solid fa-plane" style="color: #78B722; margin-right: 6px;"></i>
                    <?php echo htmlspecialchars($booking['airline_name'] ?: 'IndiGo'); ?> (<?php echo htmlspecialchars($booking['flight_number']); ?>)
                </div>
                <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">
                    Status: <?php echo htmlspecialchars($booking['booking_status']); ?>
                </span>
            </div>

            <div class="flight-route-row">
                <div style="text-align: left;">
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($booking['origin']); ?></div>
                    <div style="font-size: 13px; font-weight: 600; color: #475569;"><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo date('H:i', strtotime($booking['travel_date'])); ?></div>
                </div>

                <div>
                    <i class="fa-solid fa-plane" style="font-size: 20px; color: #78B722;"></i>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Economy Class</div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($booking['destination']); ?></div>
                    <div style="font-size: 13px; font-weight: 600; color: #475569;"><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></div>
                    <div style="font-size: 12px; color: #64748b;">Arrival Scheduled</div>
                </div>
            </div>
        </div>

        <!-- Passengers Table -->
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Passenger Information</h3>
        <table class="pax-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Passenger Name</th>
                    <th>Type</th>
                    <th>Ticket / Seat</th>
                    <th>Baggage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $pax_list = $passengers['passengers'] ?? array();
                if (empty($pax_list)) {
                    $pax_list = array(array('name' => 'Primary Passenger', 'type' => 'Adult'));
                }
                foreach ($pax_list as $idx => $p): 
                ?>
                <tr>
                    <td><?php echo ($idx + 1); ?></td>
                    <td><strong><?php echo htmlspecialchars($p['name'] ?? 'Passenger'); ?></strong></td>
                    <td><?php echo htmlspecialchars($p['type'] ?? 'Adult'); ?></td>
                    <td>Confirmed / Check-in required</td>
                    <td>15 Kg Check-in + 7 Kg Cabin</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Fare & Payment Summary -->
        <div style="border-top: 1px dashed #cbd5e1; padding-top: 16px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <div style="font-size: 12px; color: #64748b;">Payment Method: <strong>Franchise Store Wallet Float</strong></div>
                <div style="font-size: 12px; color: #64748b;">Booked on: <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?></div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 12px; color: #64748b;">Total Amount Paid</div>
                <div style="font-size: 22px; font-weight: 800; color: #09204b;">₹ <?php echo number_format($booking['total_amount'], 2); ?></div>
            </div>
        </div>
    </div>

    <div class="ticket-footer">
        <strong>Important Travel Notice:</strong>
        <p>1. Please carry a valid government photo ID card along with this E-Ticket to present at airline check-in.</p>
        <p>2. Web check-in is mandatory on the airline website 48 hours to 60 minutes prior to scheduled departure.</p>
        <p>3. Gates close 25 minutes before departure for domestic flights. For support, contact agency <?php echo htmlspecialchars($store['store_name']); ?>.</p>
    </div>
</div>

</body>
</html>
