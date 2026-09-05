<?php
$b = isset($booking) && is_array($booking) ? $booking : array();
$b['booking_ref']   = $b['booking_ref'] ?? 'HTL-CONF-' . strtoupper(substr(md5(time()), 0, 8));
$b['created_at']    = $b['created_at'] ?? date('Y-m-d H:i:s');
$b['hotel_name']    = $b['hotel_name'] ?? 'Luxury Resort';
$b['hotel_address'] = $b['hotel_address'] ?? 'Goa, India';
$b['hotel_image']   = $b['hotel_image'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80';
$b['room_type']     = $b['room_type'] ?? 'Deluxe Garden View Room';
$b['checkin_date']  = $b['checkin_date'] ?? date('Y-m-d', strtotime('+2 days'));
$b['checkout_date'] = $b['checkout_date'] ?? date('Y-m-d', strtotime('+5 days'));
$b['rooms_count']   = $b['rooms_count'] ?? 1;
$b['guests_count']  = $b['guests_count'] ?? 2;
$booking = $b;
?>
<div style="background-color: #f5f7fa; padding: 40px 0 80px 0;">
    <div class="container" style="max-width: 800px;">
        
        <!-- Print Button Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="no-print">
            <a href="<?php echo site_url('hotels'); ?>" style="color: #0d3470; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-arrow-left"></i> Back to Hotel Search</a>
            <button onclick="window.print();" class="btn-search" style="padding: 10px 20px; font-size: 14px; background: #0d3470;">
                <i class="fa-solid fa-print" style="margin-right: 8px;"></i> Print Hotel Voucher / Save PDF
            </button>
        </div>

        <!-- Voucher Printable Container -->
        <div id="printableVoucher" style="background: #ffffff; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden;">
            
            <!-- Header Banner -->
            <div style="background: linear-gradient(135deg, #09204b, #fa3a3a); color: #ffffff; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-family: var(--font-heading); margin: 0; font-size: 24px; color: #ffffff;">Voyogo Hotel Booking Voucher</h2>
                    <span style="font-size: 13px; color: #cbd5e1;">Voucher ID: <strong><?php echo htmlspecialchars($booking['booking_ref']); ?></strong></span>
                </div>
                <div style="text-align: right;">
                    <span style="background: #22c55e; color: #ffffff; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 800; display: inline-block;">
                        <i class="fa-solid fa-circle-check"></i> RESERVATION CONFIRMED
                    </span>
                    <div style="font-size: 11px; color: #cbd5e1; margin-top: 6px;">Booked On: <?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></div>
                </div>
            </div>

            <div style="padding: 32px;">
                
                <!-- Hotel Details Header Card -->
                <div style="display: flex; gap: 20px; background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
                    <img src="<?php echo htmlspecialchars($booking['hotel_image']); ?>" alt="hotel" style="width: 140px; height: 110px; object-fit: cover; border-radius: 8px;">
                    <div>
                        <h3 style="font-family: var(--font-heading); font-size: 20px; color: #0d3470; margin: 0 0 6px 0;"><?php echo htmlspecialchars($booking['hotel_name']); ?></h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0 0 8px 0;"><i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> <?php echo htmlspecialchars($booking['hotel_address']); ?></p>
                        <div style="font-size: 14px; font-weight: 700; color: #16a34a;"><?php echo htmlspecialchars($booking['room_type']); ?></div>
                    </div>
                </div>

                <!-- Check-in / Check-out Box -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 20px; margin-bottom: 24px; text-align: center;">
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Check-In Date</div>
                        <div style="font-size: 18px; font-weight: 800; color: #09204b; margin-top: 4px;"><?php echo date('D, d M Y', strtotime($booking['checkin_date'])); ?></div>
                        <div style="font-size: 11px; color: #64748b;">From 14:00 PM</div>
                    </div>
                    <div style="border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Check-Out Date</div>
                        <div style="font-size: 18px; font-weight: 800; color: #09204b; margin-top: 4px;"><?php echo date('D, d M Y', strtotime($booking['checkout_date'])); ?></div>
                        <div style="font-size: 11px; color: #64748b;">Until 11:00 AM</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Rooms & Guests</div>
                        <div style="font-size: 18px; font-weight: 800; color: #16a34a; margin-top: 4px;"><?php echo $booking['rooms_count']; ?> Room, <?php echo $booking['guests_count']; ?> Guests</div>
                    </div>
                </div>

                <!-- Guest & Payment Summary -->
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
                    <h3 style="font-family: var(--font-heading); font-size: 16px; color: #0d3470; margin-top: 0; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        Guest & Payment Information
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                        <div>
                            <span style="color: #64748b; display: block; font-size: 12px;">Primary Guest Name:</span>
                            <strong style="color: #09204b; font-size: 16px;"><?php echo htmlspecialchars($booking['primary_guest_name']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b; display: block; font-size: 12px;">Contact Email:</span>
                            <strong style="color: #09204b; font-size: 14px;"><?php echo htmlspecialchars($booking['guest_email']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b; display: block; font-size: 12px;">Payment Status:</span>
                            <strong style="color: #16a34a; font-size: 14px;"><?php echo htmlspecialchars($booking['payment_status']); ?> (₹ <?php echo number_format($booking['total_amount'], 2); ?>)</strong>
                        </div>
                        <div>
                            <span style="color: #64748b; display: block; font-size: 12px;">Razorpay Payment ID:</span>
                            <strong style="color: #475569; font-size: 14px;"><?php echo htmlspecialchars($booking['payment_id']); ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Hotel Notice Box -->
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 12px; color: #64748b;">
                    <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> <strong>Check-In Instructions:</strong> Please present this printed hotel voucher along with a valid Government Photo ID (Aadhaar / Passport / Driving License) at the hotel reception desk during check-in.
                </div>

            </div>

            <!-- Footer terms -->
            <div style="background: #f1f5f9; padding: 16px 32px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; text-align: center;">
                &copy; <?php echo date('Y'); ?> Voyogo Travel Technologies. 24x7 Customer Helpline: 1800-123-4567 / support@voyogo.com
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
    #printableVoucher {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
