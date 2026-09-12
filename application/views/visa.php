<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- 1. HERO BANNER SECTION (MATCHING IMAGE 5) -->
  <section class="ref-hero-section">
    <!-- Hero Background Slider Track -->
    <div class="ref-hero-slider">
      <div class="slider-track">
        <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/slider-images/visa/visa 1.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider-images/visa/visa 2.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider-images/visa/visa 3.png'); ?>');"></div>
        <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider-images/visa/visa 4.png'); ?>');"></div>
      </div>
    </div>

    <!-- Slider Arrows -->
    <button class="ref-slider-arrow prev" aria-label="Previous Slide">‹</button>
    <button class="ref-slider-arrow next" aria-label="Next Slide">›</button>

    <!-- Slider Pagination Dots -->
    <div class="ref-slider-dots">
      <span class="dot active"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>

    <!-- Hero Content Overlay -->
    <div class="container ref-hero-container">
      <div class="ref-hero-header-row">
        <!-- Destination Info Left -->
        <div class="ref-hero-text-block">
          <h1 class="ref-dest-title">Schengen &<br>Europe Visa</h1>
          <p class="ref-dest-subtitle">Travel Across 27 European Countries with One Visa</p>

          <div class="ref-feature-pills">
            <div class="ref-feature-pill">
              <div class="ref-feature-pill-icon"><i class="fa-solid fa-passport"></i></div>
              <span class="ref-feature-pill-text">Schengen Express</span>
            </div>
            <div class="ref-feature-pill">
              <div class="ref-feature-pill-icon"><i class="fa-solid fa-file-circle-check"></i></div>
              <span class="ref-feature-pill-text">Full Document Audit</span>
            </div>
            <div class="ref-feature-pill">
              <div class="ref-feature-pill-icon"><i class="fa-solid fa-calendar-check"></i></div>
              <span class="ref-feature-pill-text">Priority Appointments</span>
            </div>
            <div class="ref-feature-pill">
              <div class="ref-feature-pill-icon"><i class="fa-solid fa-shield-halved"></i></div>
              <span class="ref-feature-pill-text">99.4% Approval Rate</span>
            </div>
          </div>
        </div>

        <!-- Cursive Script Right -->
        <div class="ref-cursive-tag">
          Europe<br>Express
          <i class="fa-solid fa-plane"></i>
        </div>
      </div>
    </div>
  </section>

  <!-- 2. FLOATING HORIZONTAL ENQUIRY FORM & TRUST BADGES (MATCHING IMAGE 5) -->
  <section class="ref-form-section">
    <div class="container">
      <div class="ref-form-card">
        <div class="ref-form-inner">
          <div class="visa-form-layout">
            
            <!-- Header -->
            <div class="visa-form-header">
              <h3>Visa Enquiry Form</h3>
              <p>Get expert assistance for your visa application</p>
            </div>

            <!-- Form -->
            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="visaHorizontalEnquiryForm">
              <input type="hidden" name="message" value="Visa Application Enquiry">

              <div class="visa-grid-2col">
                <!-- Full Name -->
                <div class="ref-form-group">
                  <div class="ref-input-box">
                    <i class="fa-solid fa-user ref-input-icon"></i>
                    <input type="text" name="name" class="ref-input" placeholder="Full Name *" required>
                  </div>
                </div>

                <!-- Mobile Number -->
                <div class="ref-form-group">
                  <div class="ref-phone-group">
                    <div class="ref-country-code">
                      <span class="ref-cc-display">IN +91</span>
                      <i class="fa-solid fa-chevron-down ref-cc-arrow"></i>
                      <select name="country_code" class="ref-cc-select" aria-label="Country Code">
                        <option value="+91" data-display="IN +91" selected>India (+91)</option>
                        <option value="+1" data-display="US +1">United States (+1)</option>
                        <option value="+44" data-display="UK +44">United Kingdom (+44)</option>
                        <option value="+971" data-display="AE +971">United Arab Emirates (+971)</option>
                        <option value="+65" data-display="SG +65">Singapore (+65)</option>
                        <option value="+60" data-display="MY +60">Malaysia (+60)</option>
                        <option value="+66" data-display="TH +66">Thailand (+66)</option>
                        <option value="+62" data-display="ID +62">Indonesia (+62)</option>
                        <option value="+61" data-display="AU +61">Australia (+61)</option>
                        <option value="+1" data-display="CA +1">Canada (+1)</option>
                        <option value="+49" data-display="DE +49">Germany (+49)</option>
                        <option value="+33" data-display="FR +33">France (+33)</option>
                        <option value="+966" data-display="SA +966">Saudi Arabia (+966)</option>
                        <option value="+974" data-display="QA +974">Qatar (+974)</option>
                        <option value="+965" data-display="KW +965">Kuwait (+965)</option>
                        <option value="+968" data-display="OM +968">Oman (+968)</option>
                        <option value="+973" data-display="BH +973">Bahrain (+973)</option>
                        <option value="+81" data-display="JP +81">Japan (+81)</option>
                        <option value="+86" data-display="CN +86">China (+86)</option>
                        <option value="+94" data-display="LK +94">Sri Lanka (+94)</option>
                        <option value="+977" data-display="NP +977">Nepal (+977)</option>
                        <option value="+880" data-display="BD +880">Bangladesh (+880)</option>
                        <option value="+63" data-display="PH +63">Philippines (+63)</option>
                        <option value="+64" data-display="NZ +64">New Zealand (+64)</option>
                        <option value="+41" data-display="CH +41">Switzerland (+41)</option>
                        <option value="+39" data-display="IT +39">Italy (+39)</option>
                        <option value="+34" data-display="ES +34">Spain (+34)</option>
                        <option value="+31" data-display="NL +31">Netherlands (+31)</option>
                        <option value="+7" data-display="RU +7">Russia (+7)</option>
                        <option value="+27" data-display="ZA +27">South Africa (+27)</option>
                      </select>
                    </div>
                    <div class="ref-input-box" style="flex: 1;">
                      <i class="fa-solid fa-phone ref-input-icon"></i>
                      <input type="tel" name="phone" class="ref-input" placeholder="Mobile Number *" required>
                    </div>
                  </div>
                </div>

                <!-- Email Address -->
                <div class="ref-form-group">
                  <div class="ref-input-box">
                    <i class="fa-solid fa-envelope ref-input-icon"></i>
                    <input type="email" name="email" class="ref-input" placeholder="Email Address *" required>
                  </div>
                </div>

                <!-- Destination Country -->
                <div class="ref-form-group">
                  <div class="ref-input-box">
                    <i class="fa-solid fa-location-dot ref-input-icon"></i>
                    <select name="destination" class="ref-select" required>
                      <option value="" disabled selected>Destination Country *</option>
                      <option value="Schengen Visa (France, Germany, Italy, etc.)">Schengen Visa (Europe)</option>
                      <option value="United Kingdom (UK)">United Kingdom (UK)</option>
                      <option value="United States of America (USA)">United States of America (USA)</option>
                      <option value="Canada">Canada</option>
                      <option value="Australia">Australia</option>
                      <option value="New Zealand">New Zealand</option>
                      <option value="Dubai / United Arab Emirates">Dubai / United Arab Emirates</option>
                      <option value="Singapore">Singapore</option>
                      <option value="Thailand">Thailand</option>
                      <option value="Japan">Japan</option>
                    </select>
                  </div>
                </div>

                <!-- Planned Travel Date -->
                <div class="ref-form-group">
                  <div class="ref-input-box">
                    <i class="fa-regular fa-calendar ref-input-icon"></i>
                    <input type="text" name="travel_date" class="ref-input" placeholder="Planned Travel Date *" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
                  </div>
                </div>

                <!-- Number of Travelers -->
                <div class="ref-form-group">
                  <div class="ref-input-box">
                    <i class="fa-solid fa-users ref-input-icon"></i>
                    <select name="passengers" class="ref-select" required>
                      <option value="" disabled selected>Number of Travelers *</option>
                      <option value="1 Traveler">1 Traveler</option>
                      <option value="2 Travelers">2 Travelers</option>
                      <option value="3 Travelers">3 Travelers</option>
                      <option value="4 Travelers">4 Travelers</option>
                      <option value="5+ Travelers">5+ Travelers</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Options Row: Purpose of Travel & Passport Status -->
              <div class="visa-options-row">
                <div>
                  <label class="ref-label">Purpose of Travel <span class="ref-req">*</span></label>
                  <div class="visa-radio-pills">
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Tourist" checked required>
                      <span>Tourist</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Business">
                      <span>Business</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Student">
                      <span>Student</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Work">
                      <span>Work</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Family Visit">
                      <span>Family Visit</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="purpose_of_travel" value="Other">
                      <span>Other</span>
                    </label>
                  </div>
                </div>

                <div class="visa-passport-box">
                  <label class="ref-label">Do you have a Passport? <span class="ref-req">*</span></label>
                  <div class="visa-passport-radios">
                    <label class="visa-radio-pill">
                      <input type="radio" name="has_passport" value="Yes" checked onchange="togglePassportField('Yes')">
                      <span>Yes</span>
                    </label>
                    <label class="visa-radio-pill">
                      <input type="radio" name="has_passport" value="No" onchange="togglePassportField('No')">
                      <span>No</span>
                    </label>
                  </div>
                  <div class="ref-input-box" id="passportNumberGroup" style="margin-top: 4px; display: flex;">
                    <i class="fa-solid fa-passport ref-input-icon"></i>
                    <input type="text" name="passport_number" id="passportNumberInput" class="ref-input" placeholder="Passport Number">
                  </div>
                </div>
              </div>

              <!-- Bottom Bar -->
              <div class="cabs-bottom-bar">
                <label class="ref-checkbox-label">
                  <input type="checkbox" name="consent" value="1" required>
                  <span>I agree to be contacted regarding my visa enquiry.</span>
                </label>

                <button type="submit" class="ref-btn-submit">
                  SUBMIT ENQUIRY <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>

      <!-- Trust Badges Bar (Matching Image 5) -->
      <div class="ref-trust-bar">
        <div class="ref-trust-item">
          <div class="ref-trust-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="ref-trust-text">Best Price<br>Guarantee</div>
        </div>
        <div class="ref-trust-item">
          <div class="ref-trust-icon"><i class="fa-solid fa-headset"></i></div>
          <div class="ref-trust-text">24/7<br>Travel Support</div>
        </div>
        <div class="ref-trust-item">
          <div class="ref-trust-icon"><i class="fa-solid fa-users-gear"></i></div>
          <div class="ref-trust-text">Customized<br>Itineraries</div>
        </div>
        <div class="ref-trust-item">
          <div class="ref-trust-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="ref-trust-text">Secure<br>Booking</div>
        </div>
        <div class="ref-trust-item">
          <div class="ref-trust-icon"><i class="fa-solid fa-globe"></i></div>
          <div class="ref-trust-text">Wide Range<br>of Destinations</div>
        </div>
      </div>

    </div>
  </section><?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="popular-visa-section" style="padding: 40px 0;">
  <div class="container">
    <!-- Section header: title+tabs left, nav right -->
    <div class="visa-section-header">
      <div class="visa-section-left">
        <h2 class="section-title" style="margin:0 0 10px 0;">Popular Visa Destinations</h2>
        <div class="deals-tabs visa-category-tabs" style="margin:0;">
          <span class="deal-tab visa-tab active" data-tab="ac">AC</span>
          <span class="deal-tab visa-tab" data-tab="evisa">E-VISA</span>
          <span class="deal-tab visa-tab" data-tab="sticker">STICKER VISA</span>
        </div>
      </div>
      <div class="visa-section-right">
        <button class="carousel-btn" id="prevVisaBtn" aria-label="Previous Destination">‹</button>
        <button class="carousel-btn active" id="nextVisaBtn" aria-label="Next Destination">›</button>
        <a href="#" onclick="openEnquiryModal('View All Visa Destinations')" class="view-all-link">VIEW ALL</a>
      </div>
    </div>

    <!-- Visa Cards Grid -->
    <div class="visa-grid-container">
      <div class="visa-grid" id="visaGrid">
        <!-- AC Cards -->
        <div class="visa-card-item" onclick="openEnquiryModal('Malaysia AC Visa')">
          <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/jpeg/voyogo malaysia.png'); ?>');">
            <span class="visa-badge ac">AC</span>
          </div>
          <div class="visa-card-body">
            <h3 class="visa-card-title">Malaysia AC</h3>
            <div class="visa-card-info"><span>Rs.500</span></div>
          </div>
        </div>
        <div class="visa-card-item" onclick="openEnquiryModal('Sri Lanka AC Visa')">
          <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo srilanka.png'); ?>');">
            <span class="visa-badge ac">AC</span>
          </div>
          <div class="visa-card-body"><h3 class="visa-card-title">Sri Lanka AC</h3><div class="visa-card-info"><span>Rs.500</span></div></div>
        </div>
        <div class="visa-card-item" onclick="openEnquiryModal('Thailand AC Visa')">
          <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo thailand.png'); ?>');">
            <span class="visa-badge ac">AC</span>
          </div>
          <div class="visa-card-body"><h3 class="visa-card-title">Thailand AC</h3><div class="visa-card-info"><span>Rs.500</span></div></div>
        </div>
        <div class="visa-card-item" onclick="openEnquiryModal('Hong Kong AC Visa')">
          <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/jpeg/voyogo hong kong.png'); ?>');">
            <span class="visa-badge ac">AC</span>
          </div>
          <div class="visa-card-body"><h3 class="visa-card-title">Hong Kong AC</h3><div class="visa-card-info"><span>Rs.500</span></div></div>
        </div>
        <div class="visa-card-item" onclick="openEnquiryModal('Philippines Health Arrival Card')">
          <div class="visa-card-img" style="background-image: url('<?php echo base_url('assets/images/jpeg/voyogo philipines.png'); ?>');">
            <span class="visa-badge ac">AC</span>
          </div>
          <div class="visa-card-body"><h3 class="visa-card-title">Philippines Health Arrival Card</h3><div class="visa-card-info"><span>Rs.500</span></div></div>
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
          <!-- Passport number field shown when Yes is selected -->
          <div id="modalPassportNumberGroup" style="margin-top: 12px; display: block;">
            <input type="text" name="passport_number" id="modalPassportNumberInput" class="pill-input" placeholder="Passport Number *" required style="width:100%; padding-left:18px; padding-right:18px;">
          </div>
        </div>

        <!-- 9. Consent Checkbox -->
        <div class="form-section-group consent-group">
          <label class="checkbox-label-container">
            <input type="checkbox" name="consent" value="1" required>
            <span class="checkbox-text">I agree to be contacted regarding my visa enquiry.</span>
          </label>
        </div>

        <!-- 10. Submit Button -->
        <button type="submit" class="btn-send-enquiry">SUBMIT ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- Visa Pages Script -->
  <script>
    window.voyogoBaseUrl = "<?php echo base_url(); ?>";
  </script>
  <script src="<?php echo base_url('assets/js/pages_main.js?v=' . time()); ?>"></script>
