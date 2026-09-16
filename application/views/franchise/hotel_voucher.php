<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Voucher: <?php echo htmlspecialchars($booking['booking_reference']); ?> - Voyogo B2B</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
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
            border: 1px solid #e2e8f0;
        }
        .voucher-header {
            background: #09204b;
            color: #ffffff;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand img { max-height: 40px; margin-bottom: 4px; }
        .header-brand div { font-size: 11px; color: #78B722; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .conf-box {
            text-align: right;
            background: rgba(255,255,255,0.1);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .conf-title { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #93c5fd; }
        .conf-code { font-size: 20px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }

        .agent-bar {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            color: #475569;
        }

        .voucher-body { padding: 32px; }

        .hotel-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
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

<div class="action-bar">
    <a href="<?php echo site_url('franchise/bookings'); ?>" style="color: #09204b; text-decoration: none; font-size: 14px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to My Bookings
    </a>

    <button type="button" onclick="window.print()" style="background: #2563eb; color: #ffffff; border: none; padding: 9px 20px; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-print"></i> Print Hotel Voucher
    </button>
</div>

<div class="voucher-container">
    <div class="voucher-header">
        <div class="header-brand">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Voyogo Logo">
            <div>Official Hotel Confirmation Voucher</div>
        </div>
        <div class="conf-box">
            <div class="conf-title">Booking Confirmation No.</div>
            <div class="conf-code"><?php echo htmlspecialchars($booking['booking_reference']); ?></div>
        </div>
    </div>

    <!-- Agency Info -->
    <div class="agent-bar">
        <div>
            <strong>Booking Agency:</strong> <?php echo htmlspecialchars($store['store_name']); ?> (Agent Code: <strong><?php echo htmlspecialchars($store['agent_code']); ?></strong>)
        </div>
        <div>
            <strong>Status:</strong> <span style="color: #15803d; font-weight: 700; text-transform: uppercase;"><?php echo htmlspecialchars($booking['booking_status']); ?></span>
        </div>
    </div>

    <div class="voucher-body">
        <!-- Hotel Details -->
        <div class="hotel-box">
            <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                <i class="fa-solid fa-hotel" style="color: #78B722; margin-right: 6px;"></i>
                <?php echo htmlspecialchars($booking['hotel_name']); ?>
            </div>
            <div style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                Room Category: <strong style="color: #0284c7;"><?php echo htmlspecialchars($booking['room_type']); ?></strong>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; background: #f8fafc; border-radius: 8px; padding: 16px; font-size: 13px;">
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; display: block;">Check-in Date</span>
                    <strong><?php echo date('D, d M Y', strtotime($booking['check_in'])); ?></strong>
                    <div style="font-size: 11px; color: #64748b;">From 02:00 PM</div>
                </div>
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; display: block;">Check-out Date</span>
                    <strong><?php echo date('D, d M Y', strtotime($booking['check_out'])); ?></strong>
                    <div style="font-size: 11px; color: #64748b;">Until 11:00 AM</div>
                </div>
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; display: block;">Room / Meal Plan</span>
                    <strong style="color: #15803d;">Breakfast Included</strong>
                    <div style="font-size: 11px; color: #64748b;">All standard amenities</div>
                </div>
            </div>
        </div>

        <!-- Guest Details -->
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Guest Information</h3>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 20px; margin-bottom: 24px; font-size: 13.5px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <span style="font-size: 11.5px; color: #64748b; display: block;">Primary Guest Name</span>
                    <strong><?php echo htmlspecialchars($booking['primary_guest_name']); ?></strong>
                </div>
                <div>
                    <span style="font-size: 11.5px; color: #64748b; display: block;">Contact Details</span>
                    <strong><?php echo htmlspecialchars($booking['guest_phone']); ?> &bull; <?php echo htmlspecialchars($booking['guest_email']); ?></strong>
                </div>
            </div>
        </div>

        <!-- Payment Settlement -->
        <div style="border-top: 1px dashed #cbd5e1; padding-top: 16px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <div style="font-size: 12px; color: #64748b;">Payment Method: <strong>Franchise Store Wallet Float</strong></div>
                <div style="font-size: 12px; color: #64748b;">Voucher Issued On: <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?></div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 12px; color: #64748b;">Total Amount Paid</div>
                <div style="font-size: 22px; font-weight: 800; color: #09204b;">₹ <?php echo number_format($booking['total_amount'], 2); ?></div>
            </div>
        </div>
    </div>

    <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; font-size: 11.5px; color: #64748b; line-height: 1.5;">
        <strong>Hotel Check-in Policies:</strong>
        <p>1. Primary guest must be at least 18 years of age and carry a valid government-issued photo ID along with address proof.</p>
        <p>2. Applicable city taxes or incidental deposit (if any) to be paid directly to hotel at check-in.</p>
        <p>3. This voucher is fully prepaid by agency <?php echo htmlspecialchars($store['store_name']); ?> via Voyogo B2B network.</p>
    </div>
</div>

</body>
</html>
