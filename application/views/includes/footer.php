    <!-- Main Footer -->
    <footer class="main-footer">
        
        <!-- Trust Badges Bar -->
        <div class="container" style="margin-bottom: 40px;">
            <div class="features-grid">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <h3>24x7 Customer Care</h3>
                    <p>Get instant assistance for flight modifications & refunds anytime.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-tag"></i></div>
                    <h3>Best Price Guarantee</h3>
                    <p>Find lower airfares elsewhere? We match the price plus extra cashback!</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Instant Booking & Cancellation</h3>
                    <p>Seamless zero-hassle ticket confirmation and instant wallet refunds.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>100% Secure Payments</h3>
                    <p>Protected by 256-bit SSL encryption across credit cards, UPI & NetBanking.</p>
                </div>
            </div>
        </div>

        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    
                    <!-- Column 1: Brand Info -->
                    <div class="footer-col footer-about">
                        <div class="brand-logo" style="margin-bottom: 16px;">
                            <img src="<?php echo function_exists('base_url') ? base_url('assets/images/logo.png') : './assets/images/logo.png'; ?>" alt="Voyogo Logo" style="max-height: 44px; width: auto;">
                        </div>
                        <p>Voyogo is India's leading online travel company offering great deals on flight bookings, hotel reservations, holiday packages, visas, and forex services. Book cheap domestic & international air tickets with ease.</p>
                        <div style="display: flex; gap: 12px; font-size: 18px; color: #ffffff;">
                            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <!-- Column 2: Flight Routes -->
                    <div class="footer-col">
                        <h4>Popular Flight Routes</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo function_exists('site_url') ? site_url('flight') : '#'; ?>">Delhi to Mumbai Flights</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('flight') : '#'; ?>">Bangalore to Goa Flights</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('flight') : '#'; ?>">Mumbai to Dubai Flights</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('flight') : '#'; ?>">Delhi to London Flights</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('flight') : '#'; ?>">Chennai to Singapore Flights</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Top Hotel Stays -->
                    <div class="footer-col">
                        <h4>Top Hotel Destinations</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo function_exists('site_url') ? site_url('hotels') : '#'; ?>">Hotels in Goa</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('hotels') : '#'; ?>">Hotels in Dubai</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('hotels') : '#'; ?>">Hotels in Jaipur</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('hotels') : '#'; ?>">Hotels in Mumbai</a></li>
                            <li><a href="<?php echo function_exists('site_url') ? site_url('hotels') : '#'; ?>">Maldives Beach Resorts</a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Newsletter & Support -->
                    <div class="footer-col">
                        <h4>Get Special Travel Deals</h4>
                        <p style="font-size: 13px; margin-bottom: 10px;">Subscribe to get secret promo codes & flight price drop alerts.</p>
                        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to Voyogo deals!');">
                            <input type="email" class="newsletter-input" placeholder="Enter your email" required>
                            <button type="submit" class="newsletter-btn">Subscribe</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="container">
            <div class="footer-bottom">
                <div>
                    <p>&copy; <?php echo date('Y'); ?> Voyogo Travel Pvt. Ltd. All rights reserved.</p>
                </div>
                <div class="payment-badge-group">
                    <i class="fa-brands fa-cc-visa" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard" title="MasterCard"></i>
                    <i class="fa-brands fa-cc-amex" title="American Express"></i>
                    <i class="fa-brands fa-google-pay" title="Google Pay"></i>
                    <i class="fa-solid fa-building-columns" title="NetBanking"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Main Client JavaScript -->
    <script src="<?php echo function_exists('base_url') ? base_url('assets/js/main.js') : '/assets/js/main.js'; ?>"></script>
</body>
</html>
