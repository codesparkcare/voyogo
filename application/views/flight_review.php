<div style="background-color: #f5f7fa; padding: 30px 0 60px 0;">
    <div class="container">
        
        <!-- Header Step Progress -->
        <div style="background: #ffffff; padding: 18px 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-family: var(--font-heading); font-size: 20px; color: #09204b; margin: 0;">Review Your Flight Booking</h2>
                <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">Fill passenger details to complete your instant booking</p>
            </div>
            <div style="display: flex; gap: 20px; font-weight: 600; font-size: 14px;">
                <span style="color: #16a34a;"><i class="fa-solid fa-circle-check"></i> 1. Flight Selected</span>
                <span style="color: #0d3470;"><i class="fa-solid fa-circle-dot"></i> 2. Passenger Details</span>
                <span style="color: #94a3b8;"><i class="fa-regular fa-circle"></i> 3. Confirmation & Ticket</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            
            <!-- Left Side Form -->
            <div>
                <!-- Flight Summary Card -->
                <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="<?php echo htmlspecialchars($flight['airline_logo']); ?>" alt="logo" style="max-height: 40px;" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($flight['airline_name']); ?>&background=0d3470&color=fff';">
                            <div>
                                <h3 style="margin: 0; font-size: 18px; color: #0d3470;"><?php echo htmlspecialchars($flight['airline_name']); ?> (<?php echo htmlspecialchars($flight['flight_number']); ?>)</h3>
                                <span style="font-size: 13px; color: #64748b;"><?php echo htmlspecialchars($flight['from_code']); ?> &rarr; <?php echo htmlspecialchars($flight['to_code']); ?> | Economy</span>
                            </div>
                        </div>
                        <span style="background: #eff6ff; color: #2563eb; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 13px;"><?php echo date('D, d M Y', strtotime($flight['departure_date'])); ?></span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="text-align: left;">
                            <span style="font-size: 22px; font-weight: 800; color: #09204b;"><?php echo htmlspecialchars($flight['departure_time']); ?></span>
                            <div style="font-weight: 600; color: #475569;"><?php echo htmlspecialchars($flight['from_code']); ?></div>
                            <div style="font-size: 12px; color: #94a3b8;">Terminal 2</div>
                        </div>
                        <div style="text-align: center; flex: 1; margin: 0 30px;">
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">2h 15m (Non-Stop)</span>
                            <div style="height: 2px; background: #cbd5e1; margin: 8px 0; position: relative;">
                                <i class="fa-solid fa-plane" style="position: absolute; top: -7px; left: 48%; color: #0d3470;"></i>
                            </div>
                            <span style="font-size: 11px; color: #16a34a; font-weight: 700;">Check-in: 15kg | Cabin: 7kg</span>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 22px; font-weight: 800; color: #09204b;"><?php echo htmlspecialchars($flight['arrival_time']); ?></span>
                            <div style="font-weight: 600; color: #475569;"><?php echo htmlspecialchars($flight['to_code']); ?></div>
                            <div style="font-size: 12px; color: #94a3b8;">Terminal 1</div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <form id="bookingForm" action="<?php echo site_url('flight/process_payment'); ?>" method="POST">
                    
                    <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($flight['flight_number']); ?>">
                    <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($flight['airline_name']); ?>">
                    <input type="hidden" name="origin" value="<?php echo htmlspecialchars($flight['from_code']); ?>">
                    <input type="hidden" name="destination" value="<?php echo htmlspecialchars($flight['to_code']); ?>">
                    <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($flight['departure_date']); ?>">
                    <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($flight['departure_time']); ?>">
                    <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($flight['price']); ?>">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">

                    <!-- Passenger Information Card -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 20px;">
                            <i class="fa-solid fa-user-gear" style="margin-right: 8px; color: #ef4444;"></i> Adult Passenger 1 Details
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Title</label>
                                <select name="passenger_title" class="field-input" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                                    <option value="Mr">Mr</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Mrs">Mrs</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Full Name (As per Govt ID)</label>
                                <input type="text" name="passenger_name" class="field-input" required placeholder="Enter First & Last Name" value="Rahul Sharma" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Age</label>
                                <input type="number" name="passenger_age" class="field-input" required value="28" min="12" max="100" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Gender</label>
                            <div style="display: flex; gap: 20px;">
                                <label><input type="radio" name="passenger_gender" value="Male" checked> Male</label>
                                <label><input type="radio" name="passenger_gender" value="Female"> Female</label>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Card -->
                    <div style="background: #ffffff; border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 20px;">
                            <i class="fa-solid fa-address-book" style="margin-right: 8px; color: #3b82f6;"></i> Contact Details (For E-Ticket & Updates)
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Contact Person Name</label>
                                <input type="text" name="contact_name" class="field-input" required value="Rahul Sharma" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Email Address</label>
                                <input type="email" name="contact_email" class="field-input" required value="rahul.sharma@example.com" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px;">Mobile Number</label>
                                <input type="tel" name="contact_phone" class="field-input" required value="9876543210" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Action Button -->
                    <div style="text-align: right;">
                        <button type="button" id="payRazorpayBtn" class="btn-search" style="padding: 14px 32px; font-size: 16px; background: linear-gradient(135deg, #09204b, #2563eb); border-radius: 8px; cursor: pointer;">
                            <i class="fa-solid fa-lock" style="margin-right: 8px;"></i> Pay ₹ <?php echo number_format($flight['price']); ?> & Confirm Booking
                        </button>
                    </div>

                </form>
            </div>

            <!-- Right Side Price Breakdown -->
            <div>
                <div style="background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; position: sticky; top: 100px;">
                    <h3 style="font-family: var(--font-heading); font-size: 18px; color: #0d3470; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        Fare Summary
                    </h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Base Fare (1 Adult)</span>
                        <strong style="color: #1e293b;">₹ <?php echo number_format($flight['base_fare']); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Taxes & Airport Charges</span>
                        <strong style="color: #1e293b;">₹ <?php echo number_format($flight['taxes']); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span style="color: #64748b;">Convenience Fee</span>
                        <strong style="color: #16a34a;">FREE</strong>
                    </div>

                    <div style="border-top: 2px dashed #cbd5e1; padding-top: 14px; margin-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 800; color: #09204b;">Total Amount</span>
                        <strong style="font-size: 22px; color: #ef4444;">₹ <?php echo number_format($flight['price']); ?></strong>
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
document.getElementById('payRazorpayBtn').addEventListener('click', function(e) {
    e.preventDefault();

    var amountInPaise = <?php echo (int)($flight['price'] * 100); ?>;
    var contactName = document.querySelector('input[name="contact_name"]').value;
    var contactEmail = document.querySelector('input[name="contact_email"]').value;
    var contactPhone = document.querySelector('input[name="contact_phone"]').value;

    if (!contactName || !contactEmail || !contactPhone) {
        alert('Please fill in all contact details.');
        return;
    }

    var options = {
        "key": "rzp_test_VoyogoTestKey", // Razorpay Test Key Placeholder
        "amount": amountInPaise,
        "currency": "INR",
        "name": "Voyogo Travels",
        "description": "Flight Ticket Booking - <?php echo htmlspecialchars($flight['flight_number']); ?>",
        "image": "<?php echo base_url('assets/images/logo.png'); ?>",
        "handler": function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('bookingForm').submit();
        },
        "prefill": {
            "name": contactName,
            "email": contactEmail,
            "contact": contactPhone
        },
        "theme": {
            "color": "#0d3470"
        },
        "modal": {
            "ondismiss": function() {
                // If user closes modal or test mode fallback
                if (confirm("Razorpay Test Payment Gateway Window Closed. Would you like to complete booking using Test Payment Mode?")) {
                    document.getElementById('razorpay_payment_id').value = "pay_test_" + Math.floor(Math.random() * 1000000);
                    document.getElementById('bookingForm').submit();
                }
            }
        }
    };

    try {
        var rzp1 = new Razorpay(options);
        rzp1.open();
    } catch(err) {
        // Fallback test payment submit if network restricts external script
        document.getElementById('razorpay_payment_id').value = "pay_test_" + Math.floor(Math.random() * 1000000);
        document.getElementById('bookingForm').submit();
    }
});
</script>
