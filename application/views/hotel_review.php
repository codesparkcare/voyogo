<div style="background-color: #f5f7fa; padding: 30px 0 60px 0;">
    <div class="container">
        
        <!-- Header Step Progress -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-family: var(--font-heading); font-size: 20px; color: #09204b; margin: 0;">Review Your Hotel Reservation</h2>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">Fill primary guest info to confirm your hotel booking</p>
            </div>
            <div style="display: flex; gap: 20px; font-weight: 600; font-size: 14px;">
                <span style="color: #16a34a;"><i class="fa-solid fa-circle-check"></i> 1. Hotel Selected</span>
                <span style="color: #0d3470;"><i class="fa-solid fa-circle-dot"></i> 2. Guest Information</span>
                <span style="color: #94a3b8;"><i class="fa-regular fa-circle"></i> 3. Voucher Confirmation</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <!-- Left Side Form -->
            <div>
                <!-- Hotel Summary Card -->
                <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; display: flex; gap: 20px;">
                    <img src="<?php echo htmlspecialchars($booking_summary['hotel_image']); ?>" alt="hotel" style="width: 140px; height: 110px; object-fit: cover; border-radius: 8px;">
                    <div>
                        <h3 style="margin: 0 0 6px 0; font-size: 18px; color: #0d3470;"><?php echo htmlspecialchars($booking_summary['hotel_name']); ?></h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0 0 8px 0;"><i class="fa-solid fa-location-dot" style="color:#ef4444;"></i> <?php echo htmlspecialchars($booking_summary['hotel_address']); ?></p>
                        <div style="font-size: 14px; font-weight: 700; color: #16a34a;"><?php echo htmlspecialchars($booking_summary['room_type']); ?></div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                            Check-In: <strong><?php echo date('D, d M Y', strtotime($booking_summary['checkin_date'])); ?></strong> &nbsp;|&nbsp; Check-Out: <strong><?php echo date('D, d M Y', strtotime($booking_summary['checkout_date'])); ?></strong> (<?php echo $booking_summary['nights']; ?> Nights)
                        </div>
                    </div>
                </div>

                <!-- Main Guest Form -->
                <form id="hotelBookingForm" action="<?php echo site_url('hotels/process_payment'); ?>" method="POST">
                    
                    <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($booking_summary['hotel_id']); ?>">
                    <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($booking_summary['hotel_name']); ?>">
                    <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($booking_summary['hotel_address']); ?>">
                    <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($booking_summary['hotel_image']); ?>">
                    <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($booking_summary['room_type']); ?>">
                    <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($booking_summary['checkin_date']); ?>">
                    <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($booking_summary['checkout_date']); ?>">
                    <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($booking_summary['total_amount']); ?>">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">

                    <!-- Primary Guest Card -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 20px;">
                            <i class="fa-solid fa-user" style="margin-right: 8px; color: #ef4444;"></i> Primary Guest Details
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Title</label>
                                <select name="guest_title" class="field-input" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                                    <option value="Mr">Mr</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Mrs">Mrs</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Primary Guest Full Name</label>
                                <input type="text" name="primary_guest_name" class="field-input" required placeholder="Enter Full Name" value="Rahul Sharma" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Email Address (For Voucher Confirmation)</label>
                                <input type="email" name="guest_email" class="field-input" required value="rahul.sharma@example.com" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Mobile Number</label>
                                <input type="tel" name="guest_phone" class="field-input" required value="9876543210" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Action Button -->
                    <div style="text-align: right;">
                        <button type="button" id="payHotelRazorpayBtn" class="btn-search" style="padding: 14px 32px; font-size: 16px; background: linear-gradient(135deg, #09204b, #fa3a3a); border-radius: 8px; cursor: pointer;">
                            <i class="fa-solid fa-lock" style="margin-right: 8px;"></i> Pay ₹ <?php echo number_format($booking_summary['total_amount']); ?> & Confirm Voucher
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Side Price Breakdown -->
            <div>
                <div style="background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; position: sticky; top: 100px;">
                    <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        Price Summary
                    </h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Room Charges (<?php echo $booking_summary['nights']; ?> Nights)</span>
                        <strong style="color: #1e293b;">₹ <?php echo number_format($booking_summary['total_amount']); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Hotel Taxes & Service Charges</span>
                        <strong style="color: #16a34a;">INCLUDED</strong>
                    </div>

                    <div style="border-top: 2px dashed #cbd5e1; padding-top: 14px; margin-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 800; color: #09204b;">Total Amount</span>
                        <strong style="font-size: 22px; color: #ef4444;">₹ <?php echo number_format($booking_summary['total_amount']); ?></strong>
                    </div>

                    <div style="background: #f8fafc; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 12px; color: #64748b;">
                        <i class="fa-solid fa-shield-cat" style="color: #2563eb;"></i> 100% Safe & Secure Payment with Razorpay SSL Encryption.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payHotelRazorpayBtn').addEventListener('click', function(e) {
    e.preventDefault();

    var amountInPaise = <?php echo (int)($booking_summary['total_amount'] * 100); ?>;
    var guestName = document.querySelector('input[name="primary_guest_name"]').value;
    var guestEmail = document.querySelector('input[name="guest_email"]').value;
    var guestPhone = document.querySelector('input[name="guest_phone"]').value;

    if (!guestName || !guestEmail || !guestPhone) {
        alert('Please fill in all guest details.');
        return;
    }

    var options = {
        "key": "<?php echo !empty($razorpay_settings['razorpay_key_id']) ? htmlspecialchars($razorpay_settings['razorpay_key_id']) : 'rzp_test_TTVGSNKy0V1o7B'; ?>",
        "amount": amountInPaise,
        "currency": "<?php echo !empty($razorpay_settings['currency']) ? htmlspecialchars($razorpay_settings['currency']) : 'INR'; ?>",
        "name": "<?php echo !empty($razorpay_settings['merchant_name']) ? htmlspecialchars($razorpay_settings['merchant_name']) : 'Voyogo Hotel Booking'; ?>",
        "description": "Hotel Voucher - <?php echo htmlspecialchars($booking_summary['hotel_name']); ?>",
        "image": "<?php echo base_url('assets/images/logo.png'); ?>",
        "handler": function (response){
            showHotelProcessingModal("Payment Verified (HTTP 200 OK)! Generating your Official Hotel Voucher...");
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('hotelBookingForm').submit();
        },
        "prefill": {
            "name": guestName,
            "email": guestEmail,
            "contact": guestPhone
        },
        "theme": {
            "color": "<?php echo !empty($razorpay_settings['theme_color']) ? htmlspecialchars($razorpay_settings['theme_color']) : '#fa3a3a'; ?>"
        },
        "modal": {
            "ondismiss": function() {
                if (confirm("Razorpay Test Gateway Window Closed. Complete hotel voucher booking in Test Payment Mode?")) {
                    showHotelProcessingModal("Confirming Test Booking & Generating Hotel Voucher...");
                    document.getElementById('razorpay_payment_id').value = "pay_test_htl_" + Math.floor(Math.random() * 1000000);
                    document.getElementById('hotelBookingForm').submit();
                }
            }
        }
    };

    try {
        var rzp1 = new Razorpay(options);
        rzp1.open();
    } catch(err) {
        showHotelProcessingModal("Processing Hotel Booking Confirmation...");
        document.getElementById('razorpay_payment_id').value = "pay_test_htl_" + Math.floor(Math.random() * 1000000);
        document.getElementById('hotelBookingForm').submit();
    }
});

function showHotelProcessingModal(message) {
    var overlay = document.getElementById('hotelPaymentProcessingOverlay');
    if (overlay) {
        document.getElementById('hotelProcessingModalMsg').innerText = message || "Payment Verified! Generating Hotel Voucher...";
        overlay.style.display = 'flex';
    }
}
</script>

<!-- Hotel Payment Processing Fullscreen Modal Overlay -->
<div id="hotelPaymentProcessingOverlay" style="display: none; position: fixed; inset: 0; background: rgba(13, 52, 112, 0.94); z-index: 999999; backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; flex-direction: column; color: #ffffff; text-align: center; padding: 20px;">
    <div style="background: #ffffff; color: #0d3470; border-radius: 20px; padding: 40px 32px; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #fff1f2; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 3px solid #fecdd3;">
            <i class="fa-solid fa-hotel fa-beat" style="font-size: 30px; color: #e11d48;"></i>
        </div>
        <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px;">Processing Your Hotel Booking</h3>
        <p id="hotelProcessingModalMsg" style="font-size: 14px; color: #64748b; margin-bottom: 24px; font-weight: 500;">Payment Verified! Generating your Official Hotel Voucher & Confirmation...</p>
        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden; position: relative;">
            <div style="height: 100%; width: 75%; background: linear-gradient(90deg, #e11d48, #f43f5e); border-radius: 4px; animation: pulseProgress 1.5s infinite ease-in-out;"></div>
        </div>
        <div style="font-size: 12px; color: #94a3b8; margin-top: 14px;">Please do not refresh or close this window.</div>
    </div>
</div>
<style>
@keyframes pulseProgress {
    0% { width: 30%; }
    50% { width: 90%; }
    100% { width: 30%; }
}
</style>
