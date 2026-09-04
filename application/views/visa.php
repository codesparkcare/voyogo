<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- HERO BANNER SECTION WITH IMAGE SLIDER & VISA ENQUIRY FORM -->
  <section class="hero-section visa-hero">
    <!-- Hero Background Slider Track -->
    <div class="hero-slider">
      <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/holidayslide1.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/holidayslide2.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/holidayslide4.png'); ?>');"></div>
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
        
        <!-- Hero Right Form Container -->
        <div class="hero-form-wrapper visa-hero-wrapper">
          <div class="voyogo-form-card wide-form-card visa-glass-card">
            <h3 class="voyogo-form-title">Visa Enquiry Form</h3>
            
            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" class="voyogo-pill-form visa-enquiry-full-form">
              <input type="hidden" name="message" value="Visa Hero Enquiry Form">
              
              <!-- Row 1: Full Name & Mobile Number -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="text" name="name" class="pill-input" placeholder="Full Name *" required>
                </div>
                <div class="pill-form-group phone-group">
                  <div class="country-code-pill">
                    <span class="flag-icon">🇮🇳</span>
                    <span class="code-text">+91</span>
                  </div>
                  <input type="tel" name="phone" class="pill-input phone-input" placeholder="Mobile Number *" required>
                </div>
              </div>

              <!-- Row 2: Email & Destination Country -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="email" name="email" class="pill-input" placeholder="Email Address *" required>
                </div>
                <div class="pill-form-group">
                  <input type="text" name="destination" class="pill-input" placeholder="Destination Country *" required>
                </div>
              </div>

              <!-- Row 3: Planned Travel Date & Number of Travelers -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="text" name="travel_date" class="pill-input" placeholder="Planned Travel Date *" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
                </div>
                <div class="pill-form-group">
                  <input type="number" name="passengers" class="pill-input" placeholder="Number of Travelers *" min="1" required>
                </div>
              </div>

              <!-- Row 4: Purpose of Travel & Passport Status -->
              <div class="form-row-2col align-top-row">
                <div class="form-section-group">
                  <label class="form-group-label">Purpose of Travel *</label>
                  <div class="radio-options-grid compact-grid">
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Tourist" required>
                      <span class="radio-label">Tourist</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Business">
                      <span class="radio-label">Business</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Student">
                      <span class="radio-label">Student</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Work">
                      <span class="radio-label">Work</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Family Visit">
                      <span class="radio-label">Family Visit</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="purpose_of_travel" value="Other">
                      <span class="radio-label">Other</span>
                    </label>
                  </div>
                </div>

                <div class="form-section-group">
                  <label class="form-group-label">Do you have a Passport? *</label>
                  <div class="radio-options-row compact-row">
                    <label class="radio-chip">
                      <input type="radio" name="has_passport" value="Yes" required>
                      <span class="radio-label">Yes</span>
                    </label>
                    <label class="radio-chip">
                      <input type="radio" name="has_passport" value="No">
                      <span class="radio-label">No</span>
                    </label>
                  </div>
                  <div class="pill-form-group passport-number-group" id="passportNumberGroup" style="margin-top: 6px;">
                    <input type="text" name="passport_number" id="passportNumberInput" class="pill-input" placeholder="Passport Number">
                  </div>
                </div>
              </div>

              <!-- Row 5: Consent Checkbox & Submit Button -->
              <div class="form-bottom-row">
                <div class="form-section-group consent-group">
                  <label class="checkbox-label-container">
                    <input type="checkbox" name="consent" value="1" required checked>
                    <span class="checkbox-text">I agree to be contacted regarding my visa enquiry.</span>
                  </label>
                </div>
                <button type="submit" class="btn-send-enquiry">SUBMIT ENQUIRY</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- POPULAR VISA DESTINATIONS SECTION (REFER HOLIDAYS.PHP) -->
  <section class="popular-visa-section" style="padding: 40px 0;">
    <div class="container">
      
      <!-- Section Header Row with Category Tabs & Nav Arrows -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">Popular Visa Destinations</h2>
          
          <div class="deals-tabs visa-category-tabs">
            <span class="deal-tab visa-tab active">ALL</span>
            <span class="deal-tab visa-tab">E-VISA</span>
            <span class="deal-tab visa-tab">STICKER VISA</span>
            <span class="deal-tab visa-tab">EXPRESS VISA</span>
          </div>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevVisaBtn" aria-label="Previous Destination">‹</button>
          <button class="carousel-btn active" id="nextVisaBtn" aria-label="Next Destination">›</button>
          <a href="#" onclick="openEnquiryModal('View All Visa Destinations')" class="view-all-link">VIEW ALL</a>
        </div>
      </div>

      <!-- Popular Visa Cards Container / Carousel Grid -->
      <div class="visa-grid-container">
        <div class="visa-grid" id="visaGrid">
          
          <!-- Card 1: Dubai UAE -->
          <div class="visa-card-item" onclick="openEnquiryModal('Dubai (UAE) Tourist Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo dubai.png'); ?>');">
              <span class="visa-badge e-visa">E-VISA • 24-48 HRS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Dubai (UAE) Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Passport Front & Back + Photo Only</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 30 Days Single Entry</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹6,499/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 2: Bali Indonesia -->
          <div class="visa-card-item" onclick="openEnquiryModal('Bali (Indonesia) E-Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo bali .png'); ?>');">
              <span class="visa-badge e-visa">E-VOA • INSTANT</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Bali (Indonesia) Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Quick E-VOA Online Verification</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 30 Days (Extendable)</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹3,299/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 3: Thailand -->
          <div class="visa-card-item" onclick="openEnquiryModal('Thailand E-Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo thailand.png'); ?>');">
              <span class="visa-badge express">EXPRESS • 24 HRS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Thailand E-Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Minimal Paperwork & Instant Approval</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 15-30 Days Tourist</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹2,899/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 4: Singapore -->
          <div class="visa-card-item" onclick="openEnquiryModal('Singapore E-Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo singapore.png'); ?>');">
              <span class="visa-badge e-visa">E-VISA • 3-4 DAYS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Singapore Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Authorized Agent Submission</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 2 Years Multiple Entry</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹2,499/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 5: Schengen Europe -->
          <div class="visa-card-item" onclick="openEnquiryModal('Schengen Europe Sticker Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo europe.png'); ?>');">
              <span class="visa-badge sticker">STICKER • 15 DAYS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Schengen Europe Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Full VFS Slot + Cover Letter + Itinerary</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: Up to 90 Days (27 Countries)</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹7,999/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 6: Japan -->
          <div class="visa-card-item" onclick="openEnquiryModal('Japan Tourist E-Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo japan.png'); ?>');">
              <span class="visa-badge e-visa">E-VISA • 5 DAYS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Japan E-Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Single Entry Tourist E-Visa</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 90 Days (Stay 15 Days)</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹2,199/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 7: Vietnam -->
          <div class="visa-card-item" onclick="openEnquiryModal('Vietnam E-Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo vietnom.png'); ?>');">
              <span class="visa-badge e-visa">E-VISA • 3 DAYS</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">Vietnam Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Instant Official E-Visa Approval</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 30-90 Days Single/Multiple</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹1,999/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

          <!-- Card 8: USA B1/B2 Visa -->
          <div class="visa-card-item" onclick="openEnquiryModal('USA B1/B2 Tourist Visa')">
            <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo usa.png'); ?>');">
              <span class="visa-badge sticker">STICKER • INTERVIEW</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">USA B1/B2 Visa</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>DS-160 Form + Appointment Booking + Mock Interview</span>
              </div>
              <div class="visa-card-validity">
                <span>Validity: 10 Years Multiple Entry</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">₹9,999/-</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- OUR VISA PROCESS SECTION (ROUND ICONS 1-4 WITH DOTTED CONNECTOR & MOVING FLIGHT) -->
  <section class="visa-process-section">
    <div class="container">
      
      <div class="visa-process-header">
        <span class="process-tag">⚡ Fast & Easy Workflow</span>
        <h2 class="section-title">Our Simple <span>4-Step Visa Process</span></h2>
      </div>

      <!-- Process Track Container -->
      <div class="process-timeline-wrapper">
        <div class="process-timeline-inner">
          
          <!-- Dotted Connecting Track Line & Moving Flight Icon -->
          <div class="timeline-dotted-track">
            <div class="moving-flight-icon" title="Flight Progress">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
              </svg>
            </div>
          </div>

          <!-- 4 Step Numerical Round Icons & Headings Grid -->
          <div class="process-steps-timeline">
            
            <!-- Step 1 -->
            <div class="process-timeline-item">
              <div class="round-step-icon">
                <span class="step-num">1</span>
              </div>
              <h3 class="step-timeline-title">Submit Documents Online</h3>
            </div>

            <!-- Step 2 -->
            <div class="process-timeline-item">
              <div class="round-step-icon">
                <span class="step-num">2</span>
              </div>
              <h3 class="step-timeline-title">Expert Verification</h3>
            </div>

            <!-- Step 3 -->
            <div class="process-timeline-item">
              <div class="round-step-icon">
                <span class="step-num">3</span>
              </div>
              <h3 class="step-timeline-title">Embassy Processing</h3>
            </div>

            <!-- Step 4 -->
            <div class="process-timeline-item">
              <div class="round-step-icon">
                <span class="step-num">4</span>
              </div>
              <h3 class="step-timeline-title">Receive Your Visa</h3>
            </div>

          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- WHY CHOOSE US SECTION (FROM HOLIDAYS.PHP) -->
  <section class="why-choose-section">
    <div class="container">
      
      <div class="why-choose-header">
        <h2 class="why-choose-title">Why Choose <span>Voyogo</span>?</h2>
        <p class="why-choose-sub">Your trusted travel partner for seamless, memorable, and worry-free vacations worldwide.</p>
      </div>

      <div class="why-choose-grid">
        
        <!-- Feature 1: Best Price Guarantee -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Best Price Guarantee</h3>
            <p class="why-card-sub">Transparent pricing with zero hidden charges & best deal match.</p>
          </div>
        </div>

        <!-- Feature 2: Customized Itineraries -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Tailor-Made Packages</h3>
            <p class="why-card-sub">Handcrafted itineraries curated by expert travel specialists.</p>
          </div>
        </div>

        <!-- Feature 3: 24/7 Expert Support -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">24/7 Dedicated Support</h3>
            <p class="why-card-sub">Round-the-clock trip assistance from departure to return.</p>
          </div>
        </div>

        <!-- Feature 4: Fast Visa Assistance -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Verified Visa Assistance</h3>
            <p class="why-card-sub">Quick, hassle-free visa processing with 99.8% approval rate.</p>
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
          <p class="app-banner-desc">Book holidays, visas, forex, and cabs in seconds. Track live bookings, get instant price drop alerts, and unlock up to ₹5,000 app-only discounts.</p>
          
          <!-- Key Features List -->
          <div class="app-features-list">
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Instant Booking & Visa Status Updates</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>24/7 Live Visa Specialist Support</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Exclusive App-Only Offers & Discounts</span>
            </div>
          </div>

          <!-- App Store & Play Store Download Badges -->
          <div class="app-download-buttons">
            
            <!-- Google Play Store Button -->
            <a href="#" onclick="openEnquiryModal('Download Android App')" class="app-badge-btn">
              <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3.609 1.814L13.792 12 3.61 22.186A2.373 2.373 0 0 1 3 20.5V3.5c0-.665.234-1.282.609-1.686zM15.207 13.414l2.946 2.946-13.06 7.464 10.114-10.41zM18.153 10.487l3.298 1.884a1.2 1.2 0 0 1 0 2.09l-3.298 1.884-2.439-2.439 2.439-2.419zM5.093.176l13.06 7.464-2.946 2.946L5.093.176z"/>
              </svg>
              <div class="badge-text">
                <span class="badge-sub">GET IT ON</span>
                <span class="badge-title">Google Play</span>
              </div>
            </a>

            <!-- Apple App Store Button -->
            <a href="#" onclick="openEnquiryModal('Download iOS App')" class="app-badge-btn">
              <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.19c.67-.81 1.12-1.94.99-3.07-0.96.04-2.13.64-2.82 1.45-.61.71-1.15 1.87-1.01 2.98 1.08.08 2.17-.55 2.84-1.36z"/>
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
            <img src="<?php echo base_url('assets/images/app_download_mockup.jpg'); ?>" alt="Voyogo Mobile App Showcase" class="app-mockup-img">
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- QUICK ENQUIRY MODAL -->
  <div class="modal-overlay" id="enquiryModal">
    <div class="modal-box voyogo-form-card">
      <span class="modal-close" onclick="closeEnquiryModal()">&times;</span>
      <h3 class="voyogo-form-title">Visa Enquiry Form</h3>

      <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" class="voyogo-pill-form visa-enquiry-full-form">
        <input type="hidden" name="message" id="modalPackageName" value="Visa Enquiry Form">
        
        <!-- 1. Full Name * -->
        <div class="pill-form-group">
          <input type="text" name="name" class="pill-input" placeholder="Full Name *" required>
        </div>

        <!-- 2. Mobile Number * -->
        <div class="pill-form-group phone-group">
          <div class="country-code-pill">
            <span class="flag-icon">🇮🇳</span>
            <span class="code-text">+91</span>
          </div>
          <input type="tel" name="phone" class="pill-input phone-input" placeholder="Mobile Number *" required>
        </div>

        <!-- 3. Email Address * -->
        <div class="pill-form-group">
          <input type="email" name="email" class="pill-input" placeholder="Email Address *" required>
        </div>

        <!-- 4. Destination Country * -->
        <div class="pill-form-group">
          <input type="text" name="destination" class="pill-input" placeholder="Destination Country *" required>
        </div>

        <!-- 5. Purpose of Travel * -->
        <div class="form-section-group">
          <label class="form-group-label">Purpose of Travel *</label>
          <div class="radio-options-grid">
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Tourist" checked required>
              <span class="radio-label">Tourist</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Business">
              <span class="radio-label">Business</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Student">
              <span class="radio-label">Student</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Work">
              <span class="radio-label">Work</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Family Visit">
              <span class="radio-label">Family Visit</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_purpose_of_travel" value="Other">
              <span class="radio-label">Other</span>
            </label>
          </div>
        </div>

        <!-- 6. Planned Travel Date * -->
        <div class="pill-form-group">
          <input type="text" name="travel_date" class="pill-input" placeholder="Planned Travel Date *" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
        </div>

        <!-- 7. Number of Travelers * -->
        <div class="pill-form-group">
          <input type="number" name="passengers" class="pill-input" placeholder="Number of Travelers *" min="1" required>
        </div>

        <!-- 8. Do you have a Passport? * -->
        <div class="form-section-group">
          <label class="form-group-label">Do you have a Passport? *</label>
          <div class="radio-options-row">
            <label class="radio-chip">
              <input type="radio" name="modal_has_passport" value="Yes" checked required onclick="document.getElementById('modalPassportNumberGroup').style.display='block'; document.getElementById('modalPassportNumberInput').setAttribute('required','required');">
              <span class="radio-label">Yes</span>
            </label>
            <label class="radio-chip">
              <input type="radio" name="modal_has_passport" value="No" onclick="document.getElementById('modalPassportNumberGroup').style.display='none'; document.getElementById('modalPassportNumberInput').removeAttribute('required');">
              <span class="radio-label">No</span>
            </label>
          </div>
        </div>

        <!-- 8.5 Passport Number for Modal (Shown when Yes is clicked) -->
        <div class="pill-form-group passport-number-group" id="modalPassportNumberGroup">
          <input type="text" name="passport_number" id="modalPassportNumberInput" class="pill-input" placeholder="Passport Number *" required>
        </div>

        <!-- 9. Consent Checkbox -->
        <div class="form-section-group consent-group">
          <label class="checkbox-label-container">
            <input type="checkbox" name="consent" value="1" required checked>
            <span class="checkbox-text">I agree to be contacted regarding my visa enquiry.</span>
          </label>
        </div>

        <!-- 10. Submit Button -->
        <button type="submit" class="btn-send-enquiry">SUBMIT ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- Visa Pages Script -->
  <script src="<?php echo base_url('assets/js/pages_main.js'); ?>"></script>
