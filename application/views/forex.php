<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- HERO BANNER SECTION WITH IMAGE SLIDER & FOREX BUY/SELL FORM -->
  <section class="hero-section forex-hero">
    <!-- Hero Background Slider Track -->
    <div class="hero-slider">
      <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/currency-card-exchange.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/holidayslide1.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/holidayslide2.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/holidayslide3.png'); ?>');"></div>
    </div>

    <!-- Navigation Controls -->
    <button class="slider-arrow prev" aria-label="Previous Slide">‹</button>
    <button class="slider-arrow next" aria-label="Next Slide">›</button>

    <!-- Pagination Dots -->
    <div class="slider-dots">
      <span class="dot active"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <div class="container">
      <div class="hero-content-wrapper">

        <!-- Hero Right Form Container (EXACT SCREENSHOT FOREX BUY/SELL FORM) -->
        <div class="hero-form-wrapper">
          <div class="forex-form-card forex-glass-card">

            <!-- Buy / Sell Forex Tabs -->
            <div class="forex-tab-header">
              <button type="button" class="forex-tab-btn active" id="buyForexTabBtn"
                onclick="switchForexForm('buy')">Buy Forex</button>
              <button type="button" class="forex-tab-btn" id="sellForexTabBtn" onclick="switchForexForm('sell')">Sell
                Forex</button>
            </div>

            <p class="forex-form-sub">Fill in your information to access competitive rates and fast processing.</p>

            <!-- BUY FOREX FORM -->
            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="buyForexForm"
              class="forex-active-form">
              <input type="hidden" name="message" value="Buy Forex Request">
              <input type="hidden" name="forex_type" value="Buy Forex">

              <!-- Row 1: Full Name & Contact No. -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Traveller's Full Name <span class="req-star">*</span></label>
                  <input type="text" name="name" class="forex-input" placeholder="Name" required>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Contact No. <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <input type="tel" name="phone" class="forex-input input-padded" placeholder="1234567890" required>
                  </div>
                </div>
              </div>

              <!-- Row 2: Email ID & Location -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Email ID <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                      <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <input type="email" name="email" class="forex-input input-padded" placeholder="Email" required>
                  </div>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Location <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                      <circle cx="12" cy="10" r="3" />
                    </svg>
                    <input type="text" name="location" class="forex-input input-padded" required>
                  </div>
                </div>
              </div>

              <!-- Row 3: Purpose of Visit & Choose Currency -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Purpose of Visit <span class="req-star">*</span></label>
                  <select name="purpose_of_visit" class="forex-select" required>
                    <option value="" disabled selected>Select Purpose</option>
                    <option value="Tourism">Tourism</option>
                    <option value="Education">Education</option>
                    <option value="Business">Business</option>
                    <option value="Medical">Medical</option>
                    <option value="Employment">Employment</option>
                    <option value="Personal Visit">Personal Visit</option>
                  </select>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Choose Currency <span class="req-star">*</span></label>
                  <select name="currency" class="forex-select" required>
                    <option value="" disabled selected>Select Currency</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="AED">AED - UAE Dirham</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="THB">THB - Thai Baht</option>
                    <option value="CAD">CAD - Canadian Dollar</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="JPY">JPY - Japanese Yen</option>
                  </select>
                </div>
              </div>

              <!-- Row 4: Choose Product & Quantity -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Choose Product <span class="req-star">*</span></label>
                  <select name="product" class="forex-select" required>
                    <option value="" disabled selected>Select Product</option>
                    <option value="Currency Notes">Currency Notes</option>
                    <option value="Multi-Currency Forex Card">Multi-Currency Forex Card</option>
                    <option value="Reload Forex Card">Reload Forex Card</option>
                    <option value="International Wire Transfer">International Wire Transfer</option>
                  </select>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Forex Quantity <span class="req-star">*</span></label>
                  <input type="number" name="quantity" class="forex-input" placeholder="Quantity" min="1" required>
                </div>
              </div>

              <!-- Row 5: Submit Button -->
              <div class="form-bottom-row" style="margin-top: 10px;">
                <button type="submit" class="btn-send-enquiry" style="width: 100%;">BUY FOREX</button>
              </div>
            </form>

            <!-- SELL FOREX FORM -->
            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="sellForexForm"
              class="forex-hidden-form">
              <input type="hidden" name="message" value="Sell Forex Request">
              <input type="hidden" name="forex_type" value="Sell Forex">

              <!-- Row 1: Full Name & Contact No. -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Traveller's Full Name <span class="req-star">*</span></label>
                  <input type="text" name="name" class="forex-input" placeholder="Name" required>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Contact No. <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <input type="tel" name="phone" class="forex-input input-padded" placeholder="1234567890" required>
                  </div>
                </div>
              </div>

              <!-- Row 2: Email ID & Location -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Email ID <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                      <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <input type="email" name="email" class="forex-input input-padded" placeholder="Email" required>
                  </div>
                </div>
                <div class="forex-field-group">
                  <label class="forex-label">Location <span class="req-star">*</span></label>
                  <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                      <circle cx="12" cy="10" r="3" />
                    </svg>
                    <input type="text" name="location" class="forex-input input-padded" required>
                  </div>
                </div>
              </div>

              <!-- Row 3: Choose Currency & Choose Product -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Choose Currency <span class="req-star">*</span></label>
                  <select name="currency" class="forex-select" required>
                    <option value="" disabled selected>Select Currency</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="AED">AED - UAE Dirham</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="THB">THB - Thai Baht</option>
                    <option value="CAD">CAD - Canadian Dollar</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="JPY">JPY - Japanese Yen</option>
                  </select>
                </div>

                <div class="forex-field-group">
                  <label class="forex-label">Choose Product <span class="req-star">*</span></label>
                  <select name="product" class="forex-select" required>
                    <option value="" disabled selected>Select Product</option>
                    <option value="Currency Notes">Currency Notes</option>
                    <option value="Forex Card Unload">Forex Card Unload</option>
                  </select>
                </div>
              </div>

              <!-- Row 4: Forex Quantity -->
              <div class="form-row-2col">
                <div class="forex-field-group">
                  <label class="forex-label">Forex Quantity <span class="req-star">*</span></label>
                  <input type="number" name="quantity" class="forex-input" placeholder="Quantity" min="1" required>
                </div>
              </div>

              <!-- Row 5: Submit Button -->
              <div class="form-bottom-row" style="margin-top: 10px;">
                <button type="submit" class="btn-send-enquiry" style="width: 100%;">SELL FOREX</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- TWO COLUMNS CONTENT + IMAGE SECTION -->
  <section class="forex-content-image-section">
    <div class="container">
      <div class="forex-two-col-wrapper">

        <!-- Left Content Column -->
        <div class="forex-left-content">
          <span class="section-badge-tag">💱 Seamless Foreign Exchange</span>
          <h2 class="section-title">Hassle-Free Currency Exchange & <span>Multi-Currency Cards</span></h2>
          <p class="forex-desc-text">Voyogo provides live interbank exchange rates with zero hidden charges for foreign
            currency notes, international multi-currency travel cards, and wire transfers. Whether you're traveling for
            leisure, business, higher studies, or medical visits, get guaranteed best rates with instant home delivery
            or branch pickup.</p>

          <div class="forex-features-list">
            <div class="forex-feature-card">
              <div class="feature-icon-circle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
              </div>
              <div class="feature-card-text">
                <h3>Guaranteed Live Market Rates</h3>
                <p>Transparent live interbank exchange rates updated real-time with zero markup fees.</p>
              </div>
            </div>

            <div class="forex-feature-card">
              <div class="feature-icon-circle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                  <line x1="1" y1="10" x2="23" y2="10" />
                </svg>
              </div>
              <div class="feature-card-text">
                <h3>Multi-Currency Forex Cards</h3>
                <p>Lock in exchange rates and tap-and-pay worldwide with zero cross-currency conversion charges.</p>
              </div>
            </div>

            <div class="forex-feature-card">
              <div class="feature-icon-circle">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                  <polyline points="9 12 11 14 15 10" />
                </svg>
              </div>
              <div class="feature-card-text">
                <h3>100% Safe & RBI Authorized</h3>
                <p>Fully compliant transactions processed through authorized dealer banking channels.</p>
              </div>
            </div>
          </div>

          <div class="forex-cta-row">
            <button onclick="openEnquiryModal('Forex Live Rate Order')" class="btn-primary">Order Forex Now</button>
          </div>
        </div>

        <!-- Right Image Column -->
        <div class="forex-right-image">
          <div class="forex-image-wrapper">
            <img src="<?php echo base_url('assets/images/currency-card-exchange.png'); ?>"
              alt="Voyogo Currency Exchange Showcase" class="forex-showcase-img">
            <div class="forex-floating-badge">
              <span class="badge-number">30+</span>
              <span class="badge-text">Global Currencies Available</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- HOW IT WORKS SECTION (WITH EXACT USER CONTENT) -->
  <section class="forex-how-it-works-section">
    <div class="container">

      <div class="visa-process-header">
        <span class="process-tag">⚡ Fast & Easy Workflow</span>
        <h2 class="section-title">How It <span>Works</span></h2>
      </div>

      <div class="forex-works-grid">

        <!-- Step 1 -->
        <div class="forex-works-card">
          <div class="works-step-num">01</div>
          <div class="works-icon-box">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <h3 class="works-title">Choose your currency and amount</h3>
        </div>

        <!-- Step 2 -->
        <div class="forex-works-card">
          <div class="works-step-num">02</div>
          <div class="works-icon-box">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
          </div>
          <h3 class="works-title">Fill out your details</h3>
        </div>

        <!-- Step 3 -->
        <div class="forex-works-card">
          <div class="works-step-num">03</div>
          <div class="works-icon-box">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
          </div>
          <h3 class="works-title">Our agent will call you back</h3>
        </div>

        <!-- Step 4 -->
        <div class="forex-works-card">
          <div class="works-step-num">04</div>
          <div class="works-icon-box">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <rect x="1" y="3" width="15" height="13" />
              <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
              <circle cx="5.5" cy="18.5" r="2.5" />
              <circle cx="18.5" cy="18.5" r="2.5" />
            </svg>
          </div>
          <h3 class="works-title">Decide on delivery: Home delivery or Branch pickup</h3>
        </div>

      </div>

    </div>
  </section>

  <!-- WHY CHOOSE US SECTION (FROM SYSTEM) -->
  <section class="why-choose-section">
    <div class="container">

      <div class="why-choose-header">
        <h2 class="why-choose-title">Why Choose <span>Voyogo</span>?</h2>
        <p class="why-choose-sub">Your trusted travel partner for seamless, memorable, and worry-free vacations
          worldwide.</p>
      </div>

      <div class="why-choose-grid">

        <!-- Feature 1: Best Price Guarantee -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Best Price Guarantee</h3>
            <p class="why-card-sub">Transparent pricing with zero hidden charges & best deal match.</p>
          </div>
        </div>

        <!-- Feature 2: Customized Forex -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" y1="13" x2="8" y2="13" />
              <line x1="16" y1="17" x2="8" y2="17" />
              <line x1="10" y1="9" x2="8" y2="9" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Tailor-Made Forex Solutions</h3>
            <p class="why-card-sub">Customized travel cards and currency packages for all destinations.</p>
          </div>
        </div>

        <!-- Feature 3: 24/7 Expert Support -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">24/7 Dedicated Support</h3>
            <p class="why-card-sub">Round-the-clock currency support and live agent assistance worldwide.</p>
          </div>
        </div>

        <!-- Feature 4: Fast RBI Assistance -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
              <polyline points="9 12 11 14 15 10" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">RBI Authorized Partner</h3>
            <p class="why-card-sub">100% compliant, verified currency exchange with instant vouchers.</p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- PLAYSTORE / APP BANNER SECTION (FROM HOLIDAYS.PHP) -->
  <section class="app-banner-section">
    <div class="container">
      <div class="app-banner-card">

        <!-- Left Content Column -->
        <div class="app-banner-content">
          <span class="app-tag">📱 Mobile Experience</span>
          <h2 class="app-banner-title">Download the <span>Voyogo</span> App for Exclusive Deals</h2>
          <p class="app-banner-desc">Book holidays, visas, forex, and cabs in seconds. Track live bookings, get instant
            price drop alerts, and unlock up to ₹5,000 app-only discounts.</p>

          <!-- Key Features List -->
          <div class="app-features-list">
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Instant Forex Rate Alerts & Live Exchange Tracker</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>24/7 Live Forex Specialist Support</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Exclusive App-Only Zero Forex Markup Deals</span>
            </div>
          </div>

          <!-- App Store & Play Store Download Badges -->
          <div class="app-download-buttons">

            <!-- Google Play Store Button -->
            <a href="#" onclick="openEnquiryModal('Download Android App')" class="app-badge-btn">
              <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M3.609 1.814L13.792 12 3.61 22.186A2.373 2.373 0 0 1 3 20.5V3.5c0-.665.234-1.282.609-1.686zM15.207 13.414l2.946 2.946-13.06 7.464 10.114-10.41zM18.153 10.487l3.298 1.884a1.2 1.2 0 0 1 0 2.09l-3.298 1.884-2.439-2.439 2.439-2.419zM5.093.176l13.06 7.464-2.946 2.946L5.093.176z" />
              </svg>
              <div class="badge-text">
                <span class="badge-sub">GET IT ON</span>
                <span class="badge-title">Google Play</span>
              </div>
            </a>

            <!-- Apple App Store Button -->
            <a href="#" onclick="openEnquiryModal('Download iOS App')" class="app-badge-btn">
              <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.19c.67-.81 1.12-1.94.99-3.07-0.96.04-2.13.64-2.82 1.45-.61.71-1.15 1.87-1.01 2.98 1.08.08 2.17-.55 2.84-1.36z" />
              </svg>
              <div class="badge-text">
                <span class="badge-sub">Download on the</span>
                <span class="badge-title">App Store</span>
              </div>
            </a>

          </div>

        </div>

        <!-- Right Visual Column (App Phone Mockup) -->
        <div class="app-banner-visual">
          <div class="app-mockup-wrapper">
            <img src="<?php echo base_url('assets/images/app_download_mockup.jpg'); ?>" alt="Voyogo Mobile App Showcase"
              class="app-mockup-img">
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- QUICK ENQUIRY MODAL -->
  <div class="modal-overlay" id="enquiryModal">
    <div class="modal-box voyogo-form-card">
      <span class="modal-close" onclick="closeEnquiryModal()">&times;</span>
      <h3 class="voyogo-form-title">Forex Currency Exchange Request</h3>

      <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" class="voyogo-pill-form">
        <input type="hidden" name="message" id="modalPackageName" value="Forex Enquiry">

        <div class="pill-form-group">
          <input type="text" name="name" class="pill-input" placeholder="Full Name *" required>
        </div>

        <div class="pill-form-group phone-group">
          <div class="country-code-pill">
            <span class="flag-icon">🇮🇳</span>
            <span class="code-text">+91</span>
          </div>
          <input type="tel" name="phone" class="pill-input phone-input" placeholder="Contact Number *" required>
        </div>

        <div class="pill-form-group">
          <input type="email" name="email" class="pill-input" placeholder="Email Address *" required>
        </div>

        <div class="pill-form-group">
          <select name="currency" class="pill-input pill-select" required>
            <option value="" disabled selected>Choose Currency</option>
            <option value="USD">USD - US Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound</option>
            <option value="AED">AED - UAE Dirham</option>
            <option value="SGD">SGD - Singapore Dollar</option>
          </select>
        </div>

        <div class="pill-form-group">
          <input type="number" name="quantity" class="pill-input" placeholder="Enter Forex Quantity *" min="1" required>
        </div>

        <button type="submit" class="btn-send-enquiry">SUBMIT ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- JavaScript File -->
  <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

  <!-- Forex Tab Switcher Script -->
  <script>
    function switchForexForm(tabType) {
      const buyBtn = document.getElementById('buyForexTabBtn');
      const sellBtn = document.getElementById('sellForexTabBtn');
      const buyForm = document.getElementById('buyForexForm');
      const sellForm = document.getElementById('sellForexForm');

      if (tabType === 'buy') {
        buyBtn.classList.add('active');
        sellBtn.classList.remove('active');
        buyForm.style.display = 'block';
        sellForm.style.display = 'none';
      } else {
        sellBtn.classList.add('active');
        buyBtn.classList.remove('active');
        sellForm.style.display = 'block';
        buyForm.style.display = 'none';
      }
    }
  </script>
  <!-- Forex Pages Script -->
  <script src="<?php echo base_url('assets/js/pages_main.js'); ?>"></script>