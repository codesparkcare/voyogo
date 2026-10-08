<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket: <?php echo htmlspecialchars($booking['pnr']); ?> - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
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
            border: 1px solid #cbd5e1;
        }
        .ticket-header {
            background: linear-gradient(135deg, #09204b, #0d3470);
            color: #ffffff;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand img { max-height: 42px; margin-bottom: 4px; }
        .header-brand div { font-size: 11px; color: #78B722; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .pnr-box {
            text-align: right;
            background: rgba(255,255,255,0.12);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .pnr-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #93c5fd; font-weight: 600; }
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

        .ticket-body { padding: 30px 32px; }

        .flight-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #ffffff;
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
            font-size: 13px;
            margin-bottom: 24px;
        }
        table.pax-table th {
            background: #f1f5f9;
            padding: 11px 14px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
        }
        table.pax-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .ticket-footer {
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
            .ticket-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<?php
// Decode passenger metadata safely
$meta = !empty($booking['passenger_details']) ? (is_array($booking['passenger_details']) ? $booking['passenger_details'] : json_decode($booking['passenger_details'], true)) : array();
if (!is_array($meta)) $meta = array();

// Extract passengers list
$pax_list = array();
if (!empty($meta['passengers']) && is_array($meta['passengers'])) {
    $pax_list = $meta['passengers'];
} elseif (!empty($meta['passenger_list']) && is_array($meta['passenger_list'])) {
    $pax_list = $meta['passenger_list'];
} elseif (!empty($passengers) && is_array($passengers)) {
    $pax_list = isset($passengers['passengers']) ? $passengers['passengers'] : $passengers;
}

if (empty($pax_list)) {
    $pax_list = array(
        array(
            'title' => 'Mr',
            'name'  => $store['store_name'] ?? 'Primary Passenger',
            'type'  => 'Adult'
        )
    );
}

// Addon SSR details
$ssr = $meta['ssr_addons'] ?? ($ssr_addons ?? array());
$selectedSeat = $ssr['seat'] ?? '';
$selectedMeal = $ssr['meal'] ?? '';
$selectedBaggage = $ssr['baggage'] ?? '';
$paxSeats = $ssr['passenger_seats'] ?? array();
$paxMeals = $ssr['passenger_meals'] ?? array();
$paxBaggage = $ssr['passenger_baggage'] ?? array();

$isRoundtrip = !empty($meta['is_roundtrip']) || !empty($meta['return_flight']);
$returnFlight = $meta['return_flight'] ?? null;
$onwardDuration = $meta['duration'] ?? '02h 15m';
$onwardStops = isset($meta['stops']) ? (int)$meta['stops'] : 0;
$onwardVia = $meta['via'] ?? '';

$depDatetime = !empty($booking['departure_datetime']) ? strtotime($booking['departure_datetime']) : time();
$bookingRef = $booking['booking_ref'] ?? ($booking['booking_reference'] ?? 'FB-' . date('Ymd'));
$status = strtoupper($booking['status'] ?? 'CONFIRMED');
?>

<div class="action-bar">
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="<?php echo site_url('franchise/bookings'); ?>" style="color: #09204b; text-decoration: none; font-size: 13.5px; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> My Bookings
        </a>
        <span style="color: #cbd5e1;">|</span>
        <a href="<?php echo site_url('franchise/flight'); ?>" style="color: #2563eb; text-decoration: none; font-size: 13.5px; font-weight: 600;">
            <i class="fa-solid fa-plane"></i> Book Another Flight
        </a>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="window.print()" style="background: #09204b; color: #ffffff; border: none; padding: 9px 20px; border-radius: 6px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-print"></i> Print E-Ticket / PDF
        </button>
    </div>
</div>

<div class="ticket-container">
    <!-- Header Banner -->
    <div class="ticket-header">
        <div class="header-brand">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
            <div>Electronic Travel Document &bull; Confirmed</div>
        </div>
        <div class="pnr-box">
            <div class="pnr-title">Airline PNR</div>
            <div class="pnr-code"><?php echo htmlspecialchars($booking['pnr']); ?></div>
        </div>
    </div>

    <!-- Agency / Store Banner -->
    <div class="agent-bar">
        <div>
            <strong>Issued By Agency:</strong> <?php echo htmlspecialchars($store['store_name']); ?> 
            (Agent Code: <strong style="color: #0d3470;"><?php echo htmlspecialchars($store['agent_code']); ?></strong>)
        </div>
        <div>
            <strong>Booking Ref:</strong> <?php echo htmlspecialchars($bookingRef); ?>
        </div>
    </div>

    <div class="ticket-body">
        <!-- Onward Flight Segment -->
        <div class="flight-card">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div style="font-size: 15.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plane-departure" style="color: #2563eb;"></i>
                    <span><?php echo htmlspecialchars($booking['airline_name'] ?: 'Airline'); ?> (<?php echo htmlspecialchars($booking['flight_number']); ?>)</span>
                    <span style="font-size: 12px; font-weight: 600; color: #64748b;">&bull; Economy Class</span>
                </div>
                <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 12px; text-transform: uppercase;">
                    <i class="fa-solid fa-circle-check"></i> <?php echo $status; ?>
                </span>
            </div>

            <div class="flight-route-row">
                <div style="text-align: left;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">DEPARTURE</div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($booking['origin']); ?></div>
                    <div style="font-size: 14px; font-weight: 700; color: #0d3470;"><?php echo date('H:i', $depDatetime); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', $depDatetime); ?></div>
                </div>

                <div style="flex: 1; margin: 0 35px; text-align: center;">
                    <div style="font-size: 12px; font-weight: 700; color: <?php echo $onwardStops > 0 ? '#b45309' : '#16a34a'; ?>;">
                        <?php echo htmlspecialchars($onwardDuration); ?> &bull; <?php echo ($onwardStops == 0) ? 'Non-Stop' : ($onwardStops . ' Stop' . (!empty($onwardVia) ? ', Via ' . htmlspecialchars($onwardVia) : '')); ?>
                    </div>
                    <div style="height: 2px; background: #cbd5e1; margin: 10px 0; position: relative;">
                        <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 47%; color: #0d3470;"></i>
                    </div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">
                        Baggage: 15 Kg Check-in + 7 Kg Cabin
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">ARRIVAL</div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($booking['destination']); ?></div>
                    <div style="font-size: 14px; font-weight: 700; color: #0d3470;"><?php echo !empty($meta['arrival_time']) ? htmlspecialchars($meta['arrival_time']) : date('H:i', $depDatetime + 7200); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', $depDatetime); ?></div>
                </div>
            </div>
        </div>

        <!-- Return Flight Segment (If Round Trip) -->
        <?php if ($isRoundtrip && !empty($returnFlight)): 
            $retDepDatetime = !empty($returnFlight['departure_date']) ? strtotime($returnFlight['departure_date']) : time();
            $retStops = (int)($returnFlight['stops'] ?? 0);
        ?>
        <div class="flight-card" style="background: #f8fafc;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="font-size: 15.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plane-arrival" style="color: #10b981;"></i>
                    <span><?php echo htmlspecialchars($returnFlight['airline_name'] ?: 'Airline'); ?> (<?php echo htmlspecialchars($returnFlight['flight_number']); ?>)</span>
                    <span style="font-size: 12px; font-weight: 600; color: #64748b;">&bull; Return Sector</span>
                </div>
                <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 12px; text-transform: uppercase;">
                    <i class="fa-solid fa-circle-check"></i> <?php echo $status; ?>
                </span>
            </div>

            <div class="flight-route-row">
                <div style="text-align: left;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">DEPARTURE</div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($returnFlight['origin'] ?: $booking['destination']); ?></div>
                    <div style="font-size: 14px; font-weight: 700; color: #0d3470;"><?php echo htmlspecialchars($returnFlight['departure_time'] ?: '18:00'); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', $retDepDatetime); ?></div>
                </div>

                <div style="flex: 1; margin: 0 35px; text-align: center;">
                    <div style="font-size: 12px; font-weight: 700; color: <?php echo $retStops > 0 ? '#b45309' : '#16a34a'; ?>;">
                        <?php echo htmlspecialchars($returnFlight['duration'] ?: '02h 15m'); ?> &bull; <?php echo ($retStops == 0) ? 'Non-Stop' : ($retStops . ' Stop'); ?>
                    </div>
                    <div style="height: 2px; background: #cbd5e1; margin: 10px 0; position: relative;">
                        <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 47%; color: #10b981; transform: rotate(180deg);"></i>
                    </div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 600;">
                        Baggage: 15 Kg Check-in + 7 Kg Cabin
                    </div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">ARRIVAL</div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($returnFlight['destination'] ?: $booking['origin']); ?></div>
                    <div style="font-size: 14px; font-weight: 700; color: #0d3470;"><?php echo htmlspecialchars($returnFlight['arrival_time'] ?: '20:15'); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', $retDepDatetime); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Passengers Information Table -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #0f172a;">Passenger Information</h3>
            <span style="font-size: 12px; color: #64748b; font-weight: 600;"><?php echo count($pax_list); ?> Traveller<?php echo count($pax_list) > 1 ? 's' : ''; ?></span>
        </div>

        <table class="pax-table">
            <thead>
                <tr>
                    <th style="width: 35px;">#</th>
                    <th>Passenger Name</th>
                    <th>Type</th>
                    <th>Seat</th>
                    <th>Meal</th>
                    <th>Baggage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pax_list as $idx => $p): 
                    $pName = !empty($p['name']) ? $p['name'] : (trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) ?: 'Traveller ' . ($idx + 1));
                    $pTitle = $p['title'] ?? 'Mr';
                    $pType = $p['type'] ?? 'Adult';
                    $pSeat = $paxSeats[$idx] ?? (!empty($selectedSeat) && $idx == 0 ? $selectedSeat : 'Auto-Assigned');
                    $pMeal = $paxMeals[$idx] ?? (!empty($selectedMeal) && $idx == 0 ? $selectedMeal : 'Standard');
                    $pExtraBag = $paxBaggage[$idx] ?? (!empty($selectedBaggage) && $idx == 0 ? $selectedBaggage : '');
                    $pPassport = !empty($p['passport_no']) ? $p['passport_no'] : '';
                ?>
                <tr>
                    <td style="color: #64748b; font-weight: 700;"><?php echo ($idx + 1); ?></td>
                    <td>
                        <strong style="color: #09204b;"><?php echo htmlspecialchars($pTitle . ' ' . $pName); ?></strong>
                        <?php if (!empty($pPassport)): ?>
                            <div style="font-size: 11px; color: #64748b;">Passport: <?php echo htmlspecialchars($pPassport); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 11.5px; font-weight: 600; color: #475569;">
                            <?php echo htmlspecialchars($pType); ?>
                        </span>
                    </td>
                    <td>
                        <strong style="color: #0284c7; font-size: 13.5px;"><?php echo htmlspecialchars($pSeat); ?></strong>
                    </td>
                    <td style="font-size: 12px; color: #475569;">
                        <?php echo htmlspecialchars($pMeal); ?>
                    </td>
                    <td style="font-size: 12px; color: #16a34a; font-weight: 600;">
                        15 Kg <?php if (!empty($pExtraBag)) echo ' + ' . htmlspecialchars($pExtraBag); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Fare & Payment Summary -->
        <div style="border-top: 1.5px dashed #cbd5e1; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 18px 24px; border-radius: 10px; margin-bottom: 24px;">
            <div>
                <div style="font-size: 12px; color: #64748b;">Payment Method: <strong style="color: #09204b;">Store Wallet Float Settlement</strong></div>
                <div style="font-size: 12px; color: #64748b; margin-top: 3px;">Booked on: <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?></div>
                <div style="font-size: 12px; color: #16a34a; font-weight: 700; margin-top: 3px;">
                    <i class="fa-solid fa-shield-check"></i> Float Deduction Verified &bull; Reference: <?php echo htmlspecialchars($bookingRef); ?>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Settlement Amount</div>
                <div style="font-size: 24px; font-weight: 900; color: #09204b; font-family: 'Outfit', sans-serif;">
                    ₹ <?php echo number_format($booking['total_amount'], 2); ?>
                </div>
            </div>
        </div>

        <!-- Airport Barcode Notice -->
        <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; padding: 16px 24px; border-radius: 10px;">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #0f172a;">AIRLINE BOARDING PASS ASSIGNMENT</div>
                <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Web check-in opens 48 hours before flight departure. Present this E-Ticket and a valid photo ID at the airport check-in counter.</div>
            </div>
            <div style="text-align: right;">
                <svg style="height: 38px; width: 170px;">
                    <rect width="170" height="38" fill="#ffffff" />
                    <?php 
                    $hash = md5($booking['pnr'] . $bookingRef);
                    for($i=6; $i<164; $i+=5) {
                        $w = (hexdec(substr($hash, ($i % 30), 1)) % 3) + 1;
                        echo '<rect x="' . $i . '" y="3" width="' . $w . '" height="32" fill="#000000" />';
                    }
                    ?>
                </svg>
                <div style="font-size: 9.5px; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">*<?php echo htmlspecialchars($booking['pnr']); ?>*</div>
            </div>
        </div>

    </div>

    <!-- Ticket Footer -->
    <div class="ticket-footer">
        <strong>Important B2B Travel Instructions:</strong>
        <p>1. <strong>Identification:</strong> All passengers must carry government-issued valid photo ID (Aadhaar, Passport, Voter ID) matching the name on this ticket.</p>
        <p>2. <strong>Baggage:</strong> Standard check-in baggage is 15 kg per adult/child. Hand baggage limit is 7 kg. Additional baggage pre-booked will reflect on the airline boarding pass.</p>
        <p>3. <strong>Agency Support:</strong> Issued through Franchise Store <strong><?php echo htmlspecialchars($store['store_name']); ?></strong> (Code: <?php echo htmlspecialchars($store['agent_code']); ?>). For modifications or cancellations, contact your booking agent or Voyogo 24x7 Support at <strong>+91 8098999096</strong>.</p>
    </div>
</div>

</body>
</html>
