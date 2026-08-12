<div style="background-color: #f5f7fa; padding: 40px 0 80px 0;">
    <div class="container" style="max-width: 800px;">
        
        <!-- Print Button Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="no-print">
            <a href="<?php echo site_url('flight'); ?>" style="color: #0d3470; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Back to Flight Search</a>
            <button onclick="window.print();" class="btn-search" style="padding: 10px 20px; font-size: 14px; background: #0d3470;">
                <i class="fa-solid fa-print" style="margin-right: 8px;"></i> Print E-Ticket / Save PDF
            </button>
        </div>

        <!-- Ticket Printable Container -->
        <div id="printableTicket" style="background: #ffffff; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">
            
            <!-- Header Banner -->
            <div style="background: linear-gradient(135deg, #09204b, #0d3470); color: #ffffff; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-family: var(--font-heading); margin: 0; font-size: 24px; color: #ffffff;">Voyogo E-Ticket Confirmation</h2>
                    <span style="font-size: 13px; color: #cbd5e1;">Booking Ref: <strong><?php echo htmlspecialchars($booking['booking_ref']); ?></strong></span>
                </div>
                <div style="text-align: right;">
                    <span style="background: #22c55e; color: #ffffff; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 800; display: inline-block;">
                        <i class="fa-solid fa-circle-check"></i> BOOKING CONFIRMED
                    </span>
                    <div style="font-size: 11px; color: #cbd5e1; margin-top: 6px;">Issued On: <?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></div>
                </div>
            </div>

            <div style="padding: 32px;">
                
                <!-- PNR & Airline Summary Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px dashed #cbd5e1; margin-bottom: 24px;">
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Airline PNR / Booking Code</div>
                        <div style="font-size: 24px; font-weight: 900; color: #09204b; letter-spacing: 1px;"><?php echo htmlspecialchars($booking['pnr']); ?></div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Airline & Flight No</div>
                        <div style="font-size: 18px; font-weight: 700; color: #0d3470;"><?php echo htmlspecialchars($booking['airline_name']); ?> (<?php echo htmlspecialchars($booking['flight_number']); ?>)</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Cabin Class</div>
                        <div style="font-size: 18px; font-weight: 700; color: #16a34a;"><?php echo htmlspecialchars($booking['cabin_class']); ?></div>
                    </div>
                </div>

                <!-- Route & Timing Box -->
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
                    <h3 style="font-family: var(--font-heading); font-size: 16px; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        Flight Itinerary Details
                    </h3>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="text-align: left;">
                            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Departure</div>
                            <div style="font-size: 26px; font-weight: 800; color: #09204b;"><?php echo date('H:i', strtotime($booking['departure_datetime'])); ?></div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($booking['origin']); ?></div>
                            <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', strtotime($booking['departure_datetime'])); ?></div>
                        </div>

                        <div style="text-align: center; flex: 1; margin: 0 40px;">
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">2h 15m (Non-Stop)</span>
                            <div style="height: 2px; background: #cbd5e1; margin: 10px 0; position: relative;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 48%; color: #0d3470;"></i>
                            </div>
                            <span style="font-size: 11px; color: #16a34a; font-weight: 700;">Check-in: 15kg | Cabin: 7kg</span>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-size: 13px; color: #64748b; font-weight: 600;">Arrival</div>
                            <div style="font-size: 26px; font-weight: 800; color: #09204b;"><?php echo date('H:i', strtotime($booking['arrival_datetime'])); ?></div>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($booking['destination']); ?></div>
                            <div style="font-size: 12px; color: #64748b;"><?php echo date('D, d M Y', strtotime($booking['arrival_datetime'])); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Passenger List Box -->
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
                    <h3 style="font-family: var(--font-heading); font-size: 16px; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        Passenger Information
                    </h3>

                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                        <thead>
                            <tr style="background: #f8fafc; color: #475569;">
                                <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Passenger Name</th>
                                <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Type</th>
                                <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Ticket Status</th>
                                <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Seat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $passengers = json_decode($booking['passenger_details'], true);
                            if (is_array($passengers)) {
                                foreach ($passengers as $p) {
                            ?>
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #09204b;"><?php echo htmlspecialchars(($p['title'] ?? 'Mr') . ' ' . ($p['name'] ?? $booking['contact_name'])); ?></td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #64748b;">Adult</td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #16a34a; font-weight: 600;">CONFIRMED</td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #475569;">Auto-Assigned at Check-in</td>
                            </tr>
                            <?php 
                                }
                            } else {
                            ?>
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #09204b;"><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #64748b;">Adult</td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #16a34a; font-weight: 600;">CONFIRMED</td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #475569;">Auto-Assigned</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Payment & Barcode Footer Row -->
                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <div>
                        <div style="font-size: 12px; color: #64748b;">Payment Status: <strong style="color: #16a34a;"><?php echo htmlspecialchars($booking['payment_status']); ?></strong></div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Payment Ref ID: <strong><?php echo htmlspecialchars($booking['payment_id']); ?></strong></div>
                        <div style="font-size: 16px; font-weight: 800; color: #09204b; margin-top: 6px;">Total Paid: ₹ <?php echo number_format($booking['total_amount'], 2); ?></div>
                    </div>
                    <div style="text-align: right;">
                        <!-- SVG Barcode placeholder -->
                        <svg style="height: 45px; width: 180px;">
                            <rect width="180" height="45" fill="#ffffff" />
                            <?php for($i=10; $i<170; $i+=rand(3,8)): ?>
                                <rect x="<?php echo $i; ?>" y="5" width="<?php echo rand(1,3); ?>" height="35" fill="#000000" />
                            <?php endfor; ?>
                        </svg>
                        <div style="font-size: 10px; color: #64748b; margin-top: 2px;">SCAN AT AIRPORT BOARDING GATE</div>
                    </div>
                </div>

            </div>

            <!-- Footer terms -->
            <div style="background: #f1f5f9; padding: 16px 32px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0;">
                * Terms & Conditions: Boarding closes 25 minutes prior to departure. Passengers must carry photo ID. Voyogo 24x7 Customer Support: 1800-123-4567.
            </div>

        </div>
    </div>
</div>

<style>
@media print {
    .no-print, header, footer, .top-strip {
        display: none !important;
    }
    body {
        background: #ffffff !important;
    }
    #printableTicket {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
