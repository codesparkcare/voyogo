<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- ==========================================
     1. HERO BANNER SECTION (SLIDER CONTENT & DOWNLOAD BUTTON ON SLIDER)
     ========================================== -->
<section class="ref-hero-section franchise-hero-section">
  <!-- Hero Background Slider Track -->
  <div class="ref-hero-slider">
    <div class="slider-track">
      <div class="slide active franchise-hero-slide" style="background-image: url('<?php echo base_url('assets/images/VOYOGO FRANCHISE BANNER.png?v=' . filemtime(FCPATH . 'assets/images/VOYOGO FRANCHISE BANNER.png')); ?>');"></div>
    </div>
  </div>

  <!-- Hero Content Overlay (Pure text & download button on left) -->
  <div class="container ref-hero-container">
    <div class="ref-hero-header-row franchise-hero-header-row">
      <!-- Left Content on Slider -->
      <div class="franchise-hero-text-block">
        <span class="franchise-hero-pill"><i class="fa-solid fa-handshake-angle me-2"></i> Partner With India's Leading Travel Brand</span>
        <h1 class="franchise-hero-title">Own a Voyogo Franchise</h1>
        <p class="franchise-hero-subtitle">High Margins • Full Tech & Operations Support • 100+ Global Suppliers</p>
        
        <!-- Download Brochure Button directly on Slider -->
        <a href="<?php echo base_url('assets/images/Sky-Planet-%20Holidays.pdf'); ?>" download="Sky-Planet-Holidays-Franchise.pdf" target="_blank" class="btn-slider-download-only" title="Download Official Franchise Prospectus PDF">
          <i class="fa-solid fa-file-pdf"></i>
          <span>DOWNLOAD BROCHURE</span>
          <i class="fa-solid fa-cloud-arrow-down"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================
     2. FLOATING HORIZONTAL MULTI-STEP ENQUIRY FORM & TRUST BADGES
     ========================================== -->
<section class="ref-form-section franchise-horizontal-form-section">
  <div class="container">

    <!-- Flash Notifications -->
    <?php if ($this->session->flashdata('success_msg')): ?>
      <div class="franchise-alert success">
        <i class="fa-solid fa-circle-check"></i>
        <span><?php echo $this->session->flashdata('success_msg'); ?></span>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error_msg')): ?>
      <div class="franchise-alert error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span><?php echo $this->session->flashdata('error_msg'); ?></span>
      </div>
    <?php endif; ?>

    <!-- Wide Horizontal Form Card -->
    <div class="ref-form-card franchise-horiz-card">
      <div class="ref-form-inner franchise-horiz-inner">
        
        <!-- Form Header: Left Title & Right 6-Dot Track with Numbers 1-6 -->
        <div class="franchise-top-header-row">
          <div class="franchise-title-col">
            <h2 class="franchise-main-title">FRANCHISE <span class="title-gold">ENQUIRY</span></h2>
            <div class="step-counter-label" id="stepCounterText">Step 1 of 6 - Personal Details</div>
          </div>
          
          <!-- Step Progress Indicator (6 Numbered Dots 1,2,3,4,5,6 with Connecting Line) -->
          <div class="step-progress-col">
            <div class="step-progress-track">
              <div class="step-progress-fill" id="stepProgressFill" style="width: 16.66%;"></div>
              <div class="step-dots-row">
                <span class="step-dot active" data-step="1" title="Personal Details">1</span>
                <span class="step-dot" data-step="2" title="Location Preferences">2</span>
                <span class="step-dot" data-step="3" title="Business Background">3</span>
                <span class="step-dot" data-step="4" title="Timeline & Experience">4</span>
                <span class="step-dot" data-step="5" title="Professional Associations">5</span>
                <span class="step-dot" data-step="6" title="Discovery & Confirmation">6</span>
              </div>
            </div>
          </div>
        </div>

        <form action="<?php echo site_url('franchise/submit_enquiry'); ?>" method="POST" id="franchiseMultiStepForm" class="franchise-step-form">
          <input type="hidden" name="client_name" id="combinedClientName" value="">
          <input type="hidden" name="otp" id="combinedOtp" value="">

          <!-- ==========================================
               STEP 1: PERSONAL & CONTACT DETAILS
               ========================================== -->
          <div class="franchise-step-pane active" id="stepPane1">
            <div class="franchise-step1-horizontal-grid">
              
              <!-- First Name -->
              <div class="mockup-form-group">
                <label class="mockup-label">First Name <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-user ref-field-icon"></i>
                  <input type="text" name="first_name" id="step1FirstName" class="mockup-input" placeholder="Enter First Name" required>
                </div>
              </div>

              <!-- Last Name -->
              <div class="mockup-form-group">
                <label class="mockup-label">Last Name <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-user ref-field-icon"></i>
                  <input type="text" name="last_name" id="step1LastName" class="mockup-input" placeholder="Enter Last Name" required>
                </div>
              </div>

              <!-- Email Address -->
              <div class="mockup-form-group">
                <label class="mockup-label">Email Address <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-envelope ref-field-icon"></i>
                  <input type="email" name="email" id="step1Email" class="mockup-input" placeholder="Enter Email Address" required>
                </div>
              </div>

              <!-- Mobile Number + Send OTP -->
              <div class="mockup-form-group">
                <label class="mockup-label">Mobile Number <span class="ref-req">*</span></label>
                <div class="mockup-phone-wrap">
                  <div class="mockup-input-box phone-box">
                    <span class="country-prefix">+91</span>
                    <input type="tel" name="mobile_number" id="step1Phone" class="mockup-input" placeholder="Enter Mobile Number" pattern="[0-9]{10}" maxlength="10" required>
                  </div>
                  <button type="button" class="btn-mockup-send-otp" id="btnSendOtpMockup" onclick="triggerSendOtp()">SEND OTP</button>
                </div>
              </div>

              <!-- Enter OTP (6 Single Digit Boxes) -->
              <div class="mockup-form-group otp-group">
                <label class="mockup-label">Enter OTP</label>
                <div class="otp-boxes-row">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="0" inputmode="numeric" placeholder="•">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="1" inputmode="numeric" placeholder="•">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="2" inputmode="numeric" placeholder="•">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="3" inputmode="numeric" placeholder="•">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="4" inputmode="numeric" placeholder="•">
                  <input type="text" class="otp-digit-box" maxlength="1" data-index="5" inputmode="numeric" placeholder="•">
                </div>
              </div>

              <!-- Verify OTP Button -->
              <div class="mockup-form-group verify-group">
                <label class="mockup-label">&nbsp;</label>
                <button type="button" class="btn-mockup-verify-otp" id="btnVerifyOtpMockup" onclick="triggerVerifyOtp()">VERIFY OTP</button>
              </div>

            </div>

            <!-- OTP feedback message row -->
            <div class="otp-status-feedback" id="otpFeedbackText"></div>

            <!-- Step 1 Footer Continue -->
            <div class="step-nav-footer">
              <button type="button" class="btn-mockup-continue" onclick="goToStep(2)">
                <span>CONTINUE</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ==========================================
               STEP 2: LOCATION PREFERENCES
               ========================================== -->
          <div class="franchise-step-pane" id="stepPane2">
            <div class="franchise-horiz-grid-3col">
              
              <!-- 5. City / Location * -->
              <div class="mockup-form-group">
                <label class="mockup-label">5. City / Location <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-city ref-field-icon"></i>
                  <input type="text" name="city" id="step2City" class="mockup-input" placeholder="Where do you want to start?" required>
                </div>
              </div>

              <!-- 6. State * -->
              <div class="mockup-form-group">
                <label class="mockup-label">6. State <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-map-location-dot ref-field-icon"></i>
                  <select name="state" id="step2State" class="mockup-select" required>
                    <option value="" disabled selected>Select State</option>
                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                    <option value="Assam">Assam</option>
                    <option value="Bihar">Bihar</option>
                    <option value="Delhi NCR">Delhi NCR</option>
                    <option value="Goa">Goa</option>
                    <option value="Gujarat">Gujarat</option>
                    <option value="Haryana">Haryana</option>
                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Kerala">Kerala</option>
                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Odisha">Odisha</option>
                    <option value="Punjab">Punjab</option>
                    <option value="Rajasthan">Rajasthan</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Telangana">Telangana</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                    <option value="West Bengal">West Bengal</option>
                    <option value="Other">Other States / UT</option>
                  </select>
                </div>
              </div>

              <!-- 7. Preferred Franchise Location * -->
              <div class="mockup-form-group">
                <label class="mockup-label">7. Preferred Area / Locality <span class="ref-req">*</span></label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-location-crosshairs ref-field-icon"></i>
                  <input type="text" name="preferred_location" id="step2Loc" class="mockup-input" placeholder="Preferred area / locality for office" required>
                </div>
              </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="step-nav-footer split-nav">
              <button type="button" class="btn-mockup-back" onclick="goToStep(1)">
                <i class="fa-solid fa-arrow-left"></i>
                <span>BACK</span>
              </button>
              <button type="button" class="btn-mockup-continue" onclick="goToStep(3)">
                <span>CONTINUE</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ==========================================
               STEP 3: BUSINESS BACKGROUND
               ========================================== -->
          <div class="franchise-step-pane" id="stepPane3">
            <div class="franchise-horiz-grid-2col">
              
              <!-- 8. Do you currently own or operate a business? -->
              <div class="mockup-form-group">
                <label class="mockup-label">8. Do you currently own or operate a business? <span class="ref-req">*</span></label>
                <div class="mockup-chips-row">
                  <label class="mockup-chip">
                    <input type="radio" name="own_business" value="Yes" checked>
                    <span class="chip-label"><i class="fa-solid fa-circle-check me-1"></i> Yes</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="own_business" value="No">
                    <span class="chip-label"><i class="fa-solid fa-circle-xmark me-1"></i> No</span>
                  </label>
                </div>
              </div>

              <!-- 9. Current Business / Profession -->
              <div class="mockup-form-group">
                <label class="mockup-label">9. Current Business / Profession</label>
                <div class="mockup-input-box">
                  <i class="fa-solid fa-briefcase ref-field-icon"></i>
                  <input type="text" name="current_profession" class="mockup-input" placeholder="e.g. Travel Agent, Retail, Real Estate, IT, Corporate">
                </div>
              </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="step-nav-footer split-nav">
              <button type="button" class="btn-mockup-back" onclick="goToStep(2)">
                <i class="fa-solid fa-arrow-left"></i>
                <span>BACK</span>
              </button>
              <button type="button" class="btn-mockup-continue" onclick="goToStep(4)">
                <span>CONTINUE</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ==========================================
               STEP 4: TIMELINE & EXPERIENCE
               ========================================== -->
          <div class="franchise-step-pane" id="stepPane4">
            <div class="franchise-horiz-grid-2col">
              
              <!-- 10. When are you planning to start? * -->
              <div class="mockup-form-group">
                <label class="mockup-label">10. When are you planning to start? <span class="ref-req">*</span></label>
                <div class="mockup-chips-row wrap-chips">
                  <label class="mockup-chip">
                    <input type="radio" name="start_timeline" value="Immediately" checked>
                    <span class="chip-label">⚡ Immediately</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="start_timeline" value="Within 1 Month">
                    <span class="chip-label">Within 1 Month</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="start_timeline" value="1–3 Months">
                    <span class="chip-label">1–3 Months</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="start_timeline" value="3–6 Months">
                    <span class="chip-label">3–6 Months</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="start_timeline" value="Just Exploring">
                    <span class="chip-label">Just Exploring</span>
                  </label>
                </div>
              </div>

              <!-- 11. Have you previously operated a franchise? -->
              <div class="mockup-form-group">
                <label class="mockup-label">11. Have you previously operated a franchise? <span class="ref-req">*</span></label>
                <div class="mockup-chips-row">
                  <label class="mockup-chip">
                    <input type="radio" name="previous_franchise" value="Yes">
                    <span class="chip-label"><i class="fa-solid fa-circle-check me-1"></i> Yes</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="previous_franchise" value="No" checked>
                    <span class="chip-label"><i class="fa-solid fa-circle-xmark me-1"></i> No</span>
                  </label>
                </div>
              </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="step-nav-footer split-nav">
              <button type="button" class="btn-mockup-back" onclick="goToStep(3)">
                <i class="fa-solid fa-arrow-left"></i>
                <span>BACK</span>
              </button>
              <button type="button" class="btn-mockup-continue" onclick="goToStep(5)">
                <span>CONTINUE</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ==========================================
               STEP 5: PROFESSIONAL ASSOCIATIONS
               ========================================== -->
          <div class="franchise-step-pane" id="stepPane5">
            <div class="franchise-associations-layout">
              
              <!-- 12. Association Member? -->
              <div class="mockup-form-group">
                <label class="mockup-label">12. Are you a member of any Business or Professional Association? <span class="ref-req">*</span></label>
                <div class="mockup-chips-row">
                  <label class="mockup-chip">
                    <input type="radio" name="association_member" value="Yes" onchange="toggleStep5Associations(true)">
                    <span class="chip-label"><i class="fa-solid fa-circle-check me-1"></i> Yes</span>
                  </label>
                  <label class="mockup-chip">
                    <input type="radio" name="association_member" value="No" checked onchange="toggleStep5Associations(false)">
                    <span class="chip-label"><i class="fa-solid fa-circle-xmark me-1"></i> No</span>
                  </label>
                </div>
              </div>

              <!-- 13. Association Checkboxes -->
              <div class="mockup-associations-box" id="step5AssociationsWrap" style="display: none;">
                <label class="mockup-label" style="margin-bottom: 8px;">13. If Yes, please select all that apply:</label>
                <div class="mockup-assoc-horiz-grid">
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="BNI"><span>BNI (Business Network International)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="TAFI"><span>TAFI (Travel Agents Federation of India)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="IATO"><span>IATO (Indian Association of Tour Operators)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="ADTOI"><span>ADTOI (Adventure Tour Operators)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="TAAI"><span>TAAI (Travel Agents Association of India)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="Rotary International"><span>Rotary International</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="Lions Club International"><span>Lions Club International</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="JCI"><span>JCI (Junior Chamber International)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="CII"><span>CII (Confederation of Indian Industry)</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="FICCI CEDOI YES"><span>FICCI CEDOI YES</span></label>
                  <label class="assoc-item"><input type="checkbox" name="associations[]" value="Chamber of Commerce"><span>Chamber of Commerce Tourism</span></label>
                </div>
                <div class="mockup-input-box" style="margin-top: 10px;">
                  <i class="fa-solid fa-pen-to-square ref-field-icon"></i>
                  <input type="text" name="other_association" class="mockup-input" placeholder="Other Association (Specify if any)">
                </div>
              </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="step-nav-footer split-nav">
              <button type="button" class="btn-mockup-back" onclick="goToStep(4)">
                <i class="fa-solid fa-arrow-left"></i>
                <span>BACK</span>
              </button>
              <button type="button" class="btn-mockup-continue" onclick="goToStep(6)">
                <span>CONTINUE</span>
                <i class="fa-solid fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- ==========================================
               STEP 6: DISCOVERY & SUBMIT
               ========================================== -->
          <div class="franchise-step-pane" id="stepPane6">
            <div class="franchise-discovery-layout">
              
              <!-- 14. How did you hear about us? -->
              <div class="mockup-form-group">
                <label class="mockup-label">14. How did you hear about us? <span class="ref-req">*</span></label>
                <div class="mockup-chips-row wrap-chips">
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="Google" checked><span class="chip-label"><i class="fa-brands fa-google me-1"></i> Google</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="Instagram"><span class="chip-label"><i class="fa-brands fa-instagram me-1"></i> Instagram</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="Facebook"><span class="chip-label"><i class="fa-brands fa-facebook me-1"></i> Facebook</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="YouTube"><span class="chip-label"><i class="fa-brands fa-youtube me-1"></i> YouTube</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="WhatsApp"><span class="chip-label"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="Referral"><span class="chip-label"><i class="fa-solid fa-users me-1"></i> Referral</span></label>
                  <label class="mockup-chip"><input type="radio" name="hear_about_us" value="Website"><span class="chip-label"><i class="fa-solid fa-globe me-1"></i> Website</span></label>
                </div>
              </div>

              <!-- 15. Additional Requirements / Message -->
              <div class="mockup-form-group">
                <label class="mockup-label">15. Additional Requirements / Message</label>
                <div class="mockup-input-box textarea-box">
                  <textarea name="message" class="mockup-input" rows="2" placeholder="Tell us anything else you would like to discuss (e.g. current office setup, questions)..."></textarea>
                </div>
              </div>

              <!-- Final Consent Checkbox -->
              <div class="mockup-consent-wrap">
                <label class="consent-check-row">
                  <input type="checkbox" name="consent" value="1" required checked>
                  <span class="consent-txt">I agree to be contacted by the Voyogo franchise team regarding franchise opportunities, business details, and further discussions.</span>
                </label>
              </div>

            </div>

            <!-- Submit Buttons -->
            <div class="step-nav-footer split-nav">
              <button type="button" class="btn-mockup-back" onclick="goToStep(5)">
                <i class="fa-solid fa-arrow-left"></i>
                <span>BACK</span>
              </button>
              <button type="submit" class="btn-mockup-submit" id="btnSubmitMultiStep">
                <span>SUBMIT FRANCHISE ENQUIRY</span>
                <i class="fa-solid fa-paper-plane"></i>
              </button>
            </div>
          </div>

        </form>

      </div>
    </div>

    <!-- Trust Badges Bar Matching Other Pages -->
    <div class="ref-trust-bar franchise-bottom-trust">
      <div class="ref-trust-item">
        <div class="ref-trust-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="ref-trust-text">High ROI &<br>Attractive Margins</div>
      </div>
      <div class="ref-trust-item">
        <div class="ref-trust-icon"><i class="fa-solid fa-laptop-code"></i></div>
        <div class="ref-trust-text">Cutting-edge<br>B2B Portal Access</div>
      </div>
      <div class="ref-trust-item">
        <div class="ref-trust-icon"><i class="fa-solid fa-headset"></i></div>
        <div class="ref-trust-text">Dedicated Partner<br>Support Team</div>
      </div>
      <div class="ref-trust-item">
        <div class="ref-trust-icon"><i class="fa-solid fa-bolt"></i></div>
        <div class="ref-trust-text">Instant Ticket &<br>Voucher Issuance</div>
      </div>
      <div class="ref-trust-item">
        <div class="ref-trust-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <div class="ref-trust-text">Comprehensive<br>Training & Marketing</div>
      </div>
    </div>

  </div>
</section>

<!-- ==========================================
     3. ABOUT VOYOGO SECTION (MATCHING REFERENCE IMAGE)
     ========================================== -->
<section class="franchise-about-section">
  <!-- Decorative flight trail -->
  <div class="franchise-deco-plane-trail">
    <svg class="flight-svg-path" viewBox="0 0 500 120" fill="none">
      <path d="M 10,80 Q 250,-20 480,40" stroke="#38bdf8" stroke-width="2" stroke-dasharray="6,6" fill="none"/>
    </svg>
    <i class="fa-solid fa-plane flight-mini-plane"></i>
  </div>

  <div class="container">
    <div class="franchise-about-grid">
      
      <!-- Left Column: About Content & Stats -->
      <div class="franchise-about-content">
        <div class="section-badge-pill navy-badge">
          <span class="badge-dot orange"></span>
          <span>ABOUT VOYOGO</span>
        </div>

        <h2 class="section-display-title">
          <span class="text-orange">VOYOGO</span> is a professionally<br>managed travel company offering:
        </h2>

        <div class="about-text-body">
          <p class="about-lead-para">
            <strong>A New Brand. A Proven Legacy.</strong> Skyplanet’s VOYOGO is the flagship franchise brand of Skyplanet Voyages Private Limited, built on years of proven experience in the travel industry. Formerly operating as Skyplanet Holidays, our leadership team has successfully delivered international group departures, customized holidays, visa assistance, and comprehensive travel solutions to thousands of travellers across multiple global destinations.
          </p>
          <p>
            Over the years, we have built strong operational systems, trusted global partnerships, and a customer-first approach that have earned us the confidence of travellers and business partners alike. Today, with the launch of Skyplanet’s VOYOGO, we are transforming this proven expertise into a scalable franchise model, empowering entrepreneurs across India with a trusted brand, technology-driven operations, structured business systems, comprehensive training, and continuous support.
          </p>
          <p>
            At VOYOGO, we believe that sustainable travel is built on trust, experience, and long-term partnerships. We are not just expanding a travel brand. We are building India’s next generation travel entrepreneur network.
          </p>
        </div>

        <!-- 3 Stats Counter Badges Row -->
        <div class="about-stats-row">
          <div class="about-stat-card">
            <div class="stat-icon-circle stat-orange">
              <i class="fa-solid fa-trophy"></i>
            </div>
            <div class="stat-info">
              <span class="stat-number stat-color-orange">10+</span>
              <span class="stat-label">Years of Experience</span>
            </div>
          </div>

          <div class="about-stat-card">
            <div class="stat-icon-circle stat-blue">
              <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
              <span class="stat-number stat-color-blue">100+</span>
              <span class="stat-label">Global Destinations</span>
            </div>
          </div>

          <div class="about-stat-card">
            <div class="stat-icon-circle stat-green">
              <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-info">
              <span class="stat-number stat-color-green">100%</span>
              <span class="stat-label">Customer Satisfaction</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Visual Frame with 3 Badges & Square Polaroids Matching Ref Image -->
      <div class="franchise-about-visual-col">
        <div class="about-visual-stack">
          
          <!-- Background Square Polaroids (More Visible with Project Images) -->
          <div class="polaroid-square-card pol-top-square">
            <div class="polaroid-square-inner">
              <img src="<?php echo base_url('assets/images/voyogo maladives.png'); ?>" alt="Voyogo Maldives Luxury">
            </div>
          </div>

          <div class="polaroid-square-card pol-side-square">
            <div class="polaroid-square-inner">
              <img src="<?php echo base_url('assets/images/voyogo europe.png'); ?>" alt="Voyogo Europe Tours">
            </div>
          </div>

          <!-- Main Feature Frame with Double Contour Border -->
          <div class="about-main-frame">
            <img src="<?php echo base_url('assets/images/voyogo bali.png'); ?>" alt="Voyogo Bali Holidays" class="about-hero-img">
            
            <!-- 1. "Proven Legacy" - Dark Navy Top Right Badge with Gold Crown (Matching Ref Image) -->
            <div class="about-badge-legacy">
              <i class="fa-solid fa-crown text-gold"></i>
              <span>Proven Legacy</span>
            </div>

            <!-- 2. "Global Destinations" - Vibrant Orange Badge with Stacked Text (Matching Ref Image) -->
            <div class="about-badge-destinations">
              <span class="dest-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
              </span>
              <div class="badge-dest-text">
                <span class="dest-line-1">Global</span>
                <span class="dest-line-2">Destinations</span>
              </div>
            </div>

            <!-- 3. "Trusted Brand" - Translucent Frosted Glass Bottom Left Badge with Blue Shield (Matching Ref Image) -->
            <div class="about-badge-trusted">
              <span class="trusted-shield-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                  <path d="M12 2L4 5V11C4 16.55 7.4 21.74 12 23C16.6 21.74 20 16.55 20 11V5L12 2Z" fill="#0284c7"/>
                  <path d="M9 12L11 14L15.5 9.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <span>Trusted Brand</span>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     4. OUR VISION & OUR MISSION SECTION (MATCHING IMAGE 4)
     ========================================== -->
<section class="franchise-vision-mission-section">
  <div class="container">
    
    <!-- Top Tabs Switcher (Pill style matching Image 4) -->
    <div class="vm-tabs-nav-wrap">
      <div class="vm-tabs-nav">
        <button type="button" class="vm-nav-pill active" data-tab="vision" id="tabBtnVision" onclick="switchVmTab('vision')">
          <span class="pill-icon-ring"><i class="fa-solid fa-bullseye"></i></span>
          <span>OUR VISION</span>
        </button>
        <button type="button" class="vm-nav-pill" data-tab="mission" id="tabBtnMission" onclick="switchVmTab('mission')">
          <span class="pill-icon-ring"><i class="fa-solid fa-flag"></i></span>
          <span>OUR MISSION</span>
        </button>
      </div>
    </div>

    <!-- Main Card Container -->
    <div class="vm-main-banner-card">
      <!-- Decorative plane trail -->
      <div class="vm-flight-trail">
        <svg viewBox="0 0 320 80" fill="none">
          <path d="M 10,70 Q 160,-10 300,25" stroke="#0284c7" stroke-width="2" stroke-dasharray="5,5" fill="none"/>
        </svg>
        <i class="fa-solid fa-plane vm-mini-plane"></i>
      </div>

      <!-- Tab Pane 1: Vision (Active by default) -->
      <div class="vm-tab-pane active" id="vmTabVision">
        <div class="vm-pane-grid">
          
          <!-- Left Column Content -->
          <div class="vm-text-column">
            <div class="section-badge-pill orange-outline-badge">
              <i class="fa-solid fa-bullseye me-1"></i>
              <span>STRATEGIC VISION</span>
            </div>

            <h2 class="vm-banner-title">
              Building India's Most Trusted<br>
              <span class="text-orange">Travel Entrepreneur Network</span>
            </h2>
            <div class="title-orange-bar"></div>

            <p class="vm-para-desc">
              To build India’s most trusted travel entrepreneur network, empowering passionate entrepreneurs to establish successful travel businesses through a proven business model, innovative technology, structured systems, and continuous support. Our vision is to make Skyplanet’s VOYOGO the first choice for travellers and travel entrepreneurs across India, delivering world-class experiences from Andaman to Antarctica.
            </p>

            <!-- Bottom Royal Blue Quote Banner -->
            <div class="vm-quote-banner-box">
              <div class="quote-symbol-badge">
                <i class="fa-solid fa-quote-left"></i>
              </div>
              <p class="quote-text-content">
                “Our vision is not just to expand our business, but <span class="quote-highlight-gold">to empower thousands of entrepreneurs to build successful travel businesses under one trusted brand.</span>”
              </p>
            </div>
          </div>

          <!-- Right Column Visual: Halong Bay / Clifftop traveler + 3 Polaroids + Compass -->
          <div class="vm-visual-column">
            <div class="vm-scenic-wrapper">
              <img src="<?php echo base_url('assets/images/voyogo thailand.png'); ?>" alt="Travel Entrepreneurship" class="vm-scenic-img">

              <!-- Stacked Polaroid Photo Cards on the right edge -->
              <div class="vm-polaroid-trio">
                <div class="polaroid-mini pol-mini-1" title="Paris">
                  <img src="<?php echo base_url('assets/images/voyogo swiss paris.png'); ?>" alt="Paris Tour">
                </div>
                <div class="polaroid-mini pol-mini-2" title="Island Destination">
                  <img src="<?php echo base_url('assets/images/voyogo andaman.png'); ?>" alt="Island Holidays">
                </div>
                <div class="polaroid-mini pol-mini-3" title="Bali Temple">
                  <img src="<?php echo base_url('assets/images/voyogo bali.png'); ?>" alt="Bali Culture">
                </div>
              </div>

              <!-- Antique Golden Compass at bottom right -->
              <div class="vm-compass-deco">
                <i class="fa-solid fa-compass"></i>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Tab Pane 2: Mission -->
      <div class="vm-tab-pane" id="vmTabMission">
        <div class="vm-pane-grid">
          
          <!-- Left Column Content -->
          <div class="vm-text-column">
            <div class="section-badge-pill green-outline-badge">
              <i class="fa-solid fa-rocket me-1"></i>
              <span>CORE MISSION</span>
            </div>

            <h2 class="vm-banner-title">
              Empowering Entrepreneurs with<br>
              <span class="text-green">Excellence & Integrity</span>
            </h2>
            <div class="title-green-bar"></div>

            <p class="vm-para-desc">
              To empower travel entrepreneurs through a trusted brand, proven business systems, innovative technology, professional training, and continuous support, enabling them to build successful and sustainable travel businesses while delivering exceptional travel experiences with integrity, excellence, and customer satisfaction.
            </p>

            <!-- Mission 4 Pillars -->
            <div class="vm-mission-pillars-row">
              <div class="m-pillar-box">
                <div class="m-pillar-ico"><i class="fa-solid fa-handshake"></i></div>
                <div>
                  <strong>Trust & Integrity</strong>
                  <span>Transparent, long-term partnerships</span>
                </div>
              </div>
              <div class="m-pillar-box">
                <div class="m-pillar-ico"><i class="fa-solid fa-laptop-code"></i></div>
                <div>
                  <strong>Innovative Tech</strong>
                  <span>Live booking engines & portal</span>
                </div>
              </div>
              <div class="m-pillar-box">
                <div class="m-pillar-ico"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                  <strong>Structured Training</strong>
                  <span>Continuous support & sales playbook</span>
                </div>
              </div>
              <div class="m-pillar-box">
                <div class="m-pillar-ico"><i class="fa-solid fa-face-smile"></i></div>
                <div>
                  <strong>Delighted Travellers</strong>
                  <span>World-class memorable tours</span>
                </div>
              </div>
            </div>

            <!-- Bottom Royal Blue Quote Banner -->
            <div class="vm-quote-banner-box">
              <div class="quote-symbol-badge">
                <i class="fa-solid fa-quote-left"></i>
              </div>
              <p class="quote-text-content">
                “Building India's most dependable travel network by <span class="quote-highlight-gold">prioritizing partner prosperity and unmatched guest experiences.</span>”
              </p>
            </div>
          </div>

          <!-- Right Column Visual -->
          <div class="vm-visual-column">
            <div class="vm-scenic-wrapper">
              <img src="<?php echo base_url('assets/images/voyogo dubai.png'); ?>" alt="Voyogo Global Presence" class="vm-scenic-img">
              <div class="vm-polaroid-trio">
                <div class="polaroid-mini pol-mini-1">
                  <img src="<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>" alt="Cruise Tours">
                </div>
                <div class="polaroid-mini pol-mini-2">
                  <img src="<?php echo base_url('assets/images/voyogo maladives.png'); ?>" alt="Maldives">
                </div>
                <div class="polaroid-mini pol-mini-3">
                  <img src="<?php echo base_url('assets/images/voyogo europe.png'); ?>" alt="Europe Package">
                </div>
              </div>
              <div class="vm-compass-deco">
                <i class="fa-solid fa-award"></i>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
</section>

<!-- ==========================================
     5. WHY CHOOSE SKYPLANET'S VOYOGO? (10 PILLARS SHOWCASE)
     ========================================== -->
<section class="franchise-why-section">
  <div class="container">
    <div class="franchise-why-showcase">
      

      <!-- Top-Left Airliner Flying In -->
      <div class="why-plane-left-wrap" aria-hidden="true">
        <svg class="why-plane-svg-left" viewBox="0 0 100 65" fill="none">
          <path d="M5,42 C18,34 38,24 55,16 L52,5 C51,2 53,0 56,1 L65,11 L82,6 C86,5 89,7 87,11 L74,22 L91,24 C94,24 96,27 94,29 L86,34 L89,45 L85,46 L79,36 L64,40 L60,54 L56,54 L58,41 L22,48 C16,49 8,47 5,42 Z" fill="#0284c7" opacity="0.85"/>
          <path d="M55,16 L74,22 L64,40 L45,34 Z" fill="#38bdf8" opacity="0.6"/>
        </svg>
      </div>

      <!-- Top-Right Curved Dashed Flight Trail & Airplane -->
      <div class="why-flight-trail-right" aria-hidden="true">
        <svg viewBox="0 0 240 90" fill="none" class="why-trail-svg">
          <path d="M 15,82 Q 120,8 215,22" stroke="#0ea5e9" stroke-width="2.5" stroke-dasharray="6,6" fill="none"/>
        </svg>
        <i class="fa-solid fa-plane why-mini-plane-right"></i>
      </div>

      <!-- Subtle Bottom Ambient Wave Accent -->
      <div class="why-bottom-wave-accent" aria-hidden="true">
        <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,32 C300,8 600,52 900,24 C1050,12 1150,38 1200,28 L1200,60 L0,60 Z" fill="rgba(186, 230, 253, 0.4)"/>
          <path d="M0,42 C240,24 540,56 840,36 C1020,24 1120,46 1200,38 L1200,60 L0,60 Z" fill="rgba(224, 242, 254, 0.6)"/>
        </svg>
      </div>

      <!-- Center Content Area -->
      <div class="why-inner-container">
        
        <!-- Header Pill Badge -->
        <div class="why-badge-pill">
          <span class="pill-sparkle">✦</span>
          <span>FRANCHISE ADVANTAGES</span>
          <span class="pill-sparkle">✦</span>
        </div>

        <!-- Section Main Title -->
        <h2 class="why-hero-title">
          <span class="why-title-dark">Why Choose</span><br>
          <span class="why-title-blue">Skyplanet's</span> <span class="why-title-orange">VOYOGO?</span>
        </h2>

        <!-- Subtitle -->
        <p class="why-hero-subtitle">
          Choosing a Skyplanet's VOYOGO franchise provides access to an established network<br class="d-none d-md-block"> and proven business ecosystem:
        </p>

        <!-- 10 Feature Cards Grid (5 Columns x 2 Rows) -->
        <div class="why-features-grid">
          
          <!-- Row 1: Card 1 (Blue) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-blue">
              <i class="fa-solid fa-award"></i>
            </div>
            <h4 class="why-feature-name">Established<br>Brand Identity</h4>
            <p class="why-feature-desc">Recognised market brand & customer trust</p>
          </div>

          <!-- Row 1: Card 2 (Orange) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-orange">
              <i class="fa-solid fa-gears"></i>
            </div>
            <h4 class="why-feature-name">Proven Business<br>Processes</h4>
            <p class="why-feature-desc">Tested operating systems for seamless flow</p>
          </div>

          <!-- Row 1: Card 3 (Blue) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-blue">
              <i class="fa-solid fa-sitemap"></i>
            </div>
            <h4 class="why-feature-name">Centralised<br>Operations</h4>
            <p class="why-feature-desc">Unified backend and operational support</p>
          </div>

          <!-- Row 1: Card 4 (Orange) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-orange">
              <i class="fa-solid fa-user-tie"></i>
            </div>
            <h4 class="why-feature-name">Professional<br>CRM</h4>
            <p class="why-feature-desc">Smart lead tracking and client management</p>
          </div>

          <!-- Row 1: Card 5 (Blue) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-blue">
              <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <h4 class="why-feature-name">Cloud<br>Billing</h4>
            <p class="why-feature-desc">Automated fast invoices & payment tools</p>
          </div>

          <!-- Row 2: Card 6 (Orange) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-orange">
              <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <h4 class="why-feature-name">Operations<br>Manual</h4>
            <p class="why-feature-desc">Comprehensive SOPs & business handbook</p>
          </div>

          <!-- Row 2: Card 7 (Blue) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-blue">
              <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <h4 class="why-feature-name">Training<br>Programmes</h4>
            <p class="why-feature-desc">Regular staff coaching & sales guidance</p>
          </div>

          <!-- Row 2: Card 8 (Orange) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-orange">
              <i class="fa-solid fa-bullhorn"></i>
            </div>
            <h4 class="why-feature-name">Marketing<br>Support</h4>
            <p class="why-feature-desc">National promos & localized ad assets</p>
          </div>

          <!-- Row 2: Card 9 (Blue) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-blue">
              <i class="fa-solid fa-laptop-code"></i>
            </div>
            <h4 class="why-feature-name">Technology<br>Support</h4>
            <p class="why-feature-desc">Live booking engine & tech assistance</p>
          </div>

          <!-- Row 2: Card 10 (Orange) -->
          <div class="why-feature-card">
            <div class="why-icon-bubble bubble-orange">
              <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <h4 class="why-feature-name">High Earning<br>Potential</h4>
            <p class="why-feature-desc">High profit margins with zero royalties</p>
          </div>

        </div>

      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     6. INVESTMENT DETAILS & SPECIAL OFFER (MATCHING IMAGE 3)
     ========================================== -->
<section class="franchise-investment-banner-section">
  <div class="container">
    <div class="investment-showcase-card">
      
      <!-- Curved Flight Trail & Airplane Icon across top center -->
      <div class="invest-flight-trail-svg">
        <svg viewBox="0 0 280 80" fill="none" class="flight-trail-path">
          <path d="M 10,65 Q 140,8 260,25" stroke="#0ea5e9" stroke-width="2.5" stroke-dasharray="6,6" fill="none"/>
        </svg>
        <i class="fa-solid fa-plane invest-mini-flight-icon"></i>
      </div>

      <!-- Interactive Live Content Grid -->
      <div class="invest-grid-layout">
        
        <!-- Center-Left: Offer Headlines & Benefits -->
        <div class="invest-left-details">
          
          <!-- Green Investment Details Badge with Golden Bar Chart -->
          <div class="invest-badge-pill">
            <i class="fa-solid fa-chart-column badge-chart-gold"></i>
            <span>INVESTMENT DETAILS</span>
          </div>

          <h3 class="invest-sub-heading">30% OFFER FOR THE FIRST</h3>
          <div class="invest-headline-row">
            <h2 class="invest-giant-headline">
              <span class="text-gradient-orange">25</span>
              <span class="text-navy">PERSONS</span>
            </h2>
          </div>

          <p class="invest-network-subtext">
            Be a part of India's fastest growing travel franchise network.
          </p>

          <!-- 4 Feature Pills in a row -->
          <div class="invest-benefits-row">
            <div class="benefit-item-chip">
              <div class="benefit-icon-ring ring-orange">
                <i class="fa-solid fa-handshake"></i>
              </div>
              <span class="benefit-name">Lower<br>Investment</span>
            </div>

            <div class="benefit-item-chip">
              <div class="benefit-icon-ring ring-blue">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <span class="benefit-name">Higher<br>Returns</span>
            </div>

            <div class="benefit-item-chip">
              <div class="benefit-icon-ring ring-green">
                <i class="fa-solid fa-headset"></i>
              </div>
              <span class="benefit-name">Complete<br>Support</span>
            </div>

            <div class="benefit-item-chip">
              <div class="benefit-icon-ring ring-purple">
                <i class="fa-solid fa-gear"></i>
              </div>
              <span class="benefit-name">Proven<br>Model</span>
            </div>
          </div>

        </div>

        <!-- Right Side: Special Offer Blue Card with Folded Orange Ribbon & ₹ 10,00,000 -->
        <div class="invest-right-offer">
          <div class="invest-special-card">
            
            <!-- Folded 3D Orange Ribbon Tag sticking out top-left -->
            <div class="invest-offer-ribbon">
              <i class="fa-solid fa-gift"></i>
              <span>SPECIAL OFFER</span>
            </div>

            <!-- Inner White Pricing Box -->
            <div class="invest-inner-white-card">
              <div class="invest-now-label">
                <span class="divider-line"></span>
                <span>NOW</span>
                <span class="divider-line"></span>
              </div>
              <div class="invest-price-display">
                <span class="currency-symbol">₹</span>
                <span class="price-digits">10,00,000</span>
              </div>
            </div>

            <!-- Green Claim Offer CTA Button with Hand Pointer & Arrow -->
            <button type="button" class="btn-claim-offer-action" onclick="document.querySelector('.franchise-horizontal-form-section').scrollIntoView({ behavior: 'smooth' });">
              <i class="fa-solid fa-hand-pointer claim-click-icon"></i>
              <span>CLAIM OFFER NOW</span>
              <i class="fa-solid fa-arrow-right claim-arrow-icon"></i>
            </button>

          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     7. OUR BUSINESS MODEL SECTION (MATCHING IMAGE 2)
     ========================================== -->
<section class="franchise-business-model-section">
  <div class="container">
    
    <!-- Section Header -->
    <div class="section-center-header">
      <div class="section-badge-pill green-pill-dot">
        <span class="accent-bar-left"></span>
        <span>OUR BUSINESS MODEL</span>
        <span class="accent-bar-right"></span>
      </div>
      <h2 class="section-hero-title">
        <span class="text-orange">VOYOGO</span> Franchise Business Model
      </h2>
      <p class="section-hero-subtitle">
        A simple and proven model to help franchise partners run a successful travel business.
      </p>
    </div>

    <!-- Top Row: Card 01 & Card 02 -->
    <div class="model-cards-top-grid">
      
      <!-- Card 01: What Franchise Does -->
      <div class="model-column-card">
        <div class="model-card-header header-navy">
          <div class="model-badge-circle badge-blue">01</div>
          <div class="model-header-text">
            <h3 class="model-card-title">What Franchise Does</h3>
            <span class="model-card-sub">VOYOGO Franchise Business Model</span>
          </div>
        </div>

        <div class="model-card-body">
          <div class="model-role-items-grid">
            
            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-blue">
                <i class="fa-solid fa-users"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Customer Acquisition</h4>
                <p class="role-entry-desc">Attract and onboard new customers.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-pink">
                <i class="fa-solid fa-bullhorn"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Local Marketing</h4>
                <p class="role-entry-desc">Promote packages in your local market.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-orange">
                <i class="fa-solid fa-chart-simple"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Sales</h4>
                <p class="role-entry-desc">Convert enquiries into bookings.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-purple">
                <i class="fa-solid fa-file-lines"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Documentation Collection</h4>
                <p class="role-entry-desc">Handle travel documents and formalities.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-teal">
                <i class="fa-solid fa-comment-dots"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Customer Relationship Management</h4>
                <p class="role-entry-desc">Build long-term customer relationships.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-amber">
                <i class="fa-solid fa-credit-card"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Payment Collection</h4>
                <p class="role-entry-desc">Manage secure and smooth payments.</p>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Card 02: Business Support -->
      <div class="model-column-card">
        <div class="model-card-header header-cyan-blue">
          <div class="model-badge-circle badge-orange">02</div>
          <div class="model-header-text">
            <h3 class="model-card-title">Business Support</h3>
            <span class="model-card-sub">Comprehensive support from VOYOGO</span>
          </div>
        </div>

        <div class="model-card-body">
          <div class="model-support-items-grid">
            
            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-blue">
                <i class="fa-solid fa-gear"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Operations</h4>
                <p class="role-entry-desc">End-to-end guidance for daily operations.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-orange">
                <i class="fa-solid fa-hotel"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Hotel Reservations</h4>
                <p class="role-entry-desc">Access to global hotel inventory.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-green">
                <i class="fa-solid fa-handshake"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Supplier Negotiation</h4>
                <p class="role-entry-desc">Exclusive rates and better deals.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-red">
                <i class="fa-solid fa-file-invoice-dollar"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Billing</h4>
                <p class="role-entry-desc">Easy and transparent billing system.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-purple">
                <i class="fa-solid fa-laptop"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Technology Support</h4>
                <p class="role-entry-desc">Advanced tools and systems.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-amber">
                <i class="fa-solid fa-tags"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Brand Management</h4>
                <p class="role-entry-desc">Complete branding support.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-cyan">
                <i class="fa-solid fa-plane-departure"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Flight Reservations</h4>
                <p class="role-entry-desc">Global flight booking support.</p>
              </div>
            </div>

            <div class="model-role-entry">
              <div class="role-icon-bubble bubble-blue">
                <i class="fa-solid fa-address-card"></i>
              </div>
              <div class="role-entry-info">
                <h4 class="role-entry-title">Visa Coordination</h4>
                <p class="role-entry-desc">Assistance with visa processing.</p>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

    <!-- Bottom Row: Card 03 - VOYOGO Services Photo Strip -->
    <div class="model-card-03-full">
      <div class="card-03-header-row">
        <div class="model-badge-circle badge-green">03</div>
        <div class="card-03-title-wrap">
          <h3 class="card-03-title">VOYOGO Services</h3>
          <span class="card-03-subtitle">Wide range of travel services for your customers</span>
        </div>
      </div>

      <!-- 9 Photo Thumbnail Cards (Matching Image 2 Card 03) -->
      <div class="services-photo-strip-grid">
        
        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo andaman.png'); ?>" alt="Domestic Tour Packages">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-location-dot"></i></span>
            <span class="strip-item-name">Domestic<br>Tour Packages</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo swiss paris.png'); ?>" alt="International Tour Packages">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-earth-americas"></i></span>
            <span class="strip-item-name">International<br>Tour Packages</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo maladives.png'); ?>" alt="Honeymoon Packages">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle text-rose"><i class="fa-solid fa-heart"></i></span>
            <span class="strip-item-name">Honeymoon<br>Packages</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo bali.png'); ?>" alt="Family Vacations">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-people-roof"></i></span>
            <span class="strip-item-name">Family<br>Vacations</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo thailand.png'); ?>" alt="Group Departures">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-users"></i></span>
            <span class="strip-item-name">Group<br>Departures</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/flight-banner/flight ticket banner 1.png'); ?>" alt="Flight Ticket Booking">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-plane"></i></span>
            <span class="strip-item-name">Flight<br>Ticket Booking</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/slider-images/visa/visa 1.png'); ?>" alt="Visa Assistance">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-passport"></i></span>
            <span class="strip-item-name">Visa<br>Assistance</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>" alt="Cruise Packages">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle"><i class="fa-solid fa-ship"></i></span>
            <span class="strip-item-name">Cruise<br>Packages</span>
          </div>
        </div>

        <div class="strip-item-card">
          <div class="strip-thumb-wrap">
            <img src="<?php echo base_url('assets/images/voyogo europe.png'); ?>" alt="Corporate Travel Solutions">
          </div>
          <div class="strip-label-row">
            <span class="strip-icon-circle text-teal"><i class="fa-solid fa-briefcase"></i></span>
            <span class="strip-item-name">Corporate<br>Travel Solutions</span>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<!-- ==========================================
     8. OUR SERVICES FULL SECTION (MATCHING IMAGE 1)
     ========================================== -->
<section class="franchise-full-services-section">
  <div class="container">
    
    <!-- Header with Pill, Main Title & Subtitle -->
    <div class="services-section-top">
      
      <!-- Center Title, Badge & Subtitle -->
      <div class="services-center-heading">
        <div class="section-badge-pill green-pill-dot">
          <span class="accent-bar-left"></span>
          <span>OUR SERVICES</span>
          <span class="accent-bar-right"></span>
        </div>

        <h2 class="services-main-hero-title">
          What Kind of Services <span class="text-orange">VOYOGO</span> Offered to our Customers?
        </h2>
        <p class="services-main-subtitle">
          We provide a wide range of travel services to make your journey easy, comfortable and memorable.
        </p>
      </div>

    </div>

    <!-- Main White Card Container with 12 Service Tiles & Objective Box -->
    <div class="services-master-card">
      
      <!-- 2-Column Grid of 12 Interactive Service Tiles -->
      <div class="services-tiles-2col-grid">
        
        <!-- 1. International Holiday Packages -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-sky">
              <i class="fa-solid fa-earth-americas"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">International Holiday Packages</h4>
              <p class="tile-desc">Explore the world with our international tour packages.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 2. Flight Ticketing -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-purple">
              <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Flight Ticketing</h4>
              <p class="tile-desc">Book domestic and international flights at best rates.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 3. Domestic Holiday Packages -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-amber">
              <i class="fa-solid fa-umbrella-beach"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Domestic Holiday Packages</h4>
              <p class="tile-desc">Discover amazing places within India.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 4. Hotel Reservations -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-orange">
              <i class="fa-solid fa-hotel"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Hotel Reservations</h4>
              <p class="tile-desc">Stay at the best hotels worldwide.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 5. Fixed Group Departures -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-emerald">
              <i class="fa-solid fa-users"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Fixed Group Departures</h4>
              <p class="tile-desc">Travel together, create better memories.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 6. Travel Insurance -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-green">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Travel Insurance</h4>
              <p class="tile-desc">Travel safe with our reliable insurance plans.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 7. Customized FIT Holidays -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-violet">
              <i class="fa-solid fa-suitcase-rolling"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Customized FIT Holidays</h4>
              <p class="tile-desc">Tailor-made trips for your unique needs.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 8. Forex Assistance -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-blue">
              <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Forex Assistance</h4>
              <p class="tile-desc">Get the best exchange rates for your travel.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 9. Visa Assistance -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-teal">
              <i class="fa-solid fa-passport"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Visa Assistance</h4>
              <p class="tile-desc">Get hassle-free visa support from our experts.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 10. Corporate Travel Solutions -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-rose">
              <i class="fa-solid fa-briefcase"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">Corporate Travel Solutions</h4>
              <p class="tile-desc">Simplify business travel for your team.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 11. International Cruise Booking -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-pink">
              <i class="fa-solid fa-ship"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">International Cruise Booking</h4>
              <p class="tile-desc">Sail to new destinations with ease.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

        <!-- 12. One Stop For All Kinds Of Travel Solutions -->
        <div class="service-tile-box">
          <div class="tile-left-content">
            <div class="tile-icon-circle icon-bg-darkteal">
              <i class="fa-solid fa-star"></i>
            </div>
            <div class="tile-text-info">
              <h4 class="tile-title">One Stop For All Kinds Of Travel Solutions</h4>
              <p class="tile-desc">All your travel needs under one roof.</p>
            </div>
          </div>
          <div class="tile-arrow-link">
            <i class="fa-solid fa-chevron-right"></i>
          </div>
        </div>

      </div>

      <!-- Bottom Green Objective Bar with Target & Flight Trail (Exactly as in Image 1) -->
      <div class="services-objective-bar">
        <div class="objective-left-wrap">
          <div class="objective-target-circle">
            <i class="fa-solid fa-bullseye"></i>
          </div>
          <div class="objective-message">
            <span class="objective-strong">Our objective is to deliver world-class travel experiences</span>
            <span class="objective-sub">through standardised processes and exceptional customer service.</span>
          </div>
        </div>

        <!-- Dashed Flight Path & Airplane on right -->
        <div class="objective-flight-path">
          <svg viewBox="0 0 140 40" fill="none">
            <path d="M 10,35 Q 70,-5 130,20" stroke="#16a34a" stroke-width="2" stroke-dasharray="4,4" fill="none"/>
          </svg>
          <i class="fa-solid fa-plane objective-mini-plane"></i>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- ==========================================
     7. DOWNLOAD OUR APP BANNER SECTION
     ========================================== -->
<section class="app-banner-section franchise-app-section">
  <div class="container">
    <div class="app-banner-card">

      <!-- Left Content Column -->
      <div class="app-banner-content">
        <span class="app-tag">📱 MOBILE EXPERIENCE</span>
        <h2 class="app-banner-title">Download the <span>Voyogo</span> App for Exclusive Deals</h2>
        <p class="app-banner-desc">Book holidays, visas, forex, and cabs in seconds. Track live bookings, get instant price drop alerts, and unlock up to ₹5,000 app-only discounts.</p>

        <!-- Key Features List -->
        <div class="app-features-list">
          <div class="app-feature-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
            <span>Instant Booking & Voucher Access</span>
          </div>
          <div class="app-feature-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
            <span>24/7 Live Agent Support</span>
          </div>
          <div class="app-feature-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12" />
            </svg>
            <span>Exclusive App-Only Offers & Rewards</span>
          </div>
        </div>

        <!-- App Store & Play Store Download Badges -->
        <div class="app-download-buttons">

          <!-- Google Play Store Button -->
          <a href="https://play.google.com" target="_blank" class="app-badge-btn">
            <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3.609 1.814L13.792 12 3.61 22.186A2.373 2.373 0 0 1 3 20.5V3.5c0-.665.234-1.282.609-1.686zM15.207 13.414l2.946 2.946-13.06 7.464 10.114-10.41zM18.153 10.487l3.298 1.884a1.2 1.2 0 0 1 0 2.09l-3.298 1.884-2.439-2.439 2.439-2.419zM5.093.176l13.06 7.464-2.946 2.946L5.093.176z" />
            </svg>
            <div class="badge-text">
              <span class="badge-sub">GET IT ON</span>
              <span class="badge-title">Google Play</span>
            </div>
          </a>

          <!-- Apple App Store Button -->
          <a href="https://www.apple.com/app-store/" target="_blank" class="app-badge-btn">
            <svg class="badge-icon" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.19c.67-.81 1.12-1.94.99-3.07-0.96.04-2.13.64-2.82 1.45-.61.71-1.15 1.87-1.01 2.98 1.08.08 2.17-.55 2.84-1.36z" />
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



<!-- ==========================================
     MULTI-STEP JAVASCRIPT LOGIC
     ========================================== -->
<script>
let currentStep = 1;
const totalSteps = 6;
const stepTitles = {
  1: "Step 1 of 6 - Personal Details",
  2: "Step 2 of 6 - Location Preferences",
  3: "Step 3 of 6 - Business Background",
  4: "Step 4 of 6 - Timeline & Experience",
  5: "Step 5 of 6 - Professional Associations",
  6: "Step 6 of 6 - Discovery & Confirmation"
};

function goToStep(targetStep) {
  // Validate when going forward
  if (targetStep > currentStep) {
    if (!validateCurrentStep(currentStep)) {
      return;
    }
  }

  // Hide current pane
  const currentPane = document.getElementById('stepPane' + currentStep);
  if (currentPane) currentPane.classList.remove('active');

  // Show target pane
  const targetPane = document.getElementById('stepPane' + targetStep);
  if (targetPane) targetPane.classList.add('active');

  currentStep = targetStep;

  // Update header text
  const stepText = document.getElementById('stepCounterText');
  if (stepText) stepText.textContent = stepTitles[currentStep] || ("Step " + currentStep + " of 6");

  // Update progress bar fill
  const progressFill = document.getElementById('stepProgressFill');
  if (progressFill) {
    const percent = ((currentStep - 1) / (totalSteps - 1)) * 100;
    progressFill.style.width = Math.max(16.66, percent) + '%';
  }

  // Update dots
  document.querySelectorAll('.step-dot').forEach((dot, idx) => {
    const stepNum = idx + 1;
    if (stepNum <= currentStep) {
      dot.classList.add('active');
    } else {
      dot.classList.remove('active');
    }
  });

  // Smooth scroll to form top
  const formCard = document.querySelector('.franchise-horiz-card');
  if (formCard) {
    formCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

function validateCurrentStep(step) {
  if (step === 1) {
    const fn = document.getElementById('step1FirstName').value.trim();
    const ln = document.getElementById('step1LastName').value.trim();
    const em = document.getElementById('step1Email').value.trim();
    const ph = document.getElementById('step1Phone').value.trim();

    if (!fn) { alert('Please enter your First Name.'); document.getElementById('step1FirstName').focus(); return false; }
    if (!ln) { alert('Please enter your Last Name.'); document.getElementById('step1LastName').focus(); return false; }
    if (!em) { alert('Please enter your Email Address.'); document.getElementById('step1Email').focus(); return false; }
    if (!ph || ph.length < 10) { alert('Please enter a valid 10-digit mobile number.'); document.getElementById('step1Phone').focus(); return false; }

    // Assemble combined name and otp
    document.getElementById('combinedClientName').value = (fn + ' ' + ln).trim();
    collectOtp();
    return true;
  }

  if (step === 2) {
    const city = document.getElementById('step2City').value.trim();
    const state = document.getElementById('step2State').value.trim();
    const loc = document.getElementById('step2Loc').value.trim();

    if (!city) { alert('Please enter City / Location.'); document.getElementById('step2City').focus(); return false; }
    if (!state) { alert('Please select your State.'); document.getElementById('step2State').focus(); return false; }
    if (!loc) { alert('Please enter your Preferred Area / Locality.'); document.getElementById('step2Loc').focus(); return false; }
    return true;
  }

  return true;
}

function triggerSendOtp() {
  const ph = document.getElementById('step1Phone').value.trim();
  const fb = document.getElementById('otpFeedbackText');
  const btn = document.getElementById('btnSendOtpMockup');

  if (!ph || ph.length < 10) {
    alert('Please enter a valid 10-digit mobile number first.');
    document.getElementById('step1Phone').focus();
    return;
  }

  btn.disabled = true;
  btn.textContent = 'SENDING...';

  setTimeout(() => {
    btn.textContent = 'RESEND';
    btn.disabled = false;
    if (fb) {
      fb.innerHTML = '<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> OTP has been sent to +91 ' + ph + '</span>';
    }
    const firstOtpBox = document.querySelector('.otp-digit-box[data-index="0"]');
    if (firstOtpBox) firstOtpBox.focus();
  }, 800);
}

function triggerVerifyOtp() {
  collectOtp();
  const otpVal = document.getElementById('combinedOtp').value;
  const btn = document.getElementById('btnVerifyOtpMockup');
  const fb = document.getElementById('otpFeedbackText');

  if (!otpVal || otpVal.length < 4) {
    alert('Please enter the OTP digits in the boxes provided.');
    return;
  }

  btn.textContent = 'VERIFYING...';
  setTimeout(() => {
    btn.textContent = 'OTP VERIFIED ✓';
    btn.style.background = '#dcfce7';
    btn.style.color = '#15803d';
    btn.style.borderColor = '#86efac';
    if (fb) {
      fb.innerHTML = '<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Mobile number verified successfully!</span>';
    }
  }, 600);
}

function collectOtp() {
  let otp = '';
  document.querySelectorAll('.otp-digit-box').forEach(box => {
    otp += box.value.trim();
  });
  document.getElementById('combinedOtp').value = otp;
}

// 6-digit OTP auto-focus movement
document.querySelectorAll('.otp-digit-box').forEach((box, idx, list) => {
  box.addEventListener('input', (e) => {
    if (box.value.length === 1 && idx < list.length - 1) {
      list[idx + 1].focus();
    }
    collectOtp();
  });

  box.addEventListener('keydown', (e) => {
    if (e.key === 'Backspace' && !box.value && idx > 0) {
      list[idx - 1].focus();
    }
  });
});

function toggleStep5Associations(show) {
  const wrap = document.getElementById('step5AssociationsWrap');
  if (wrap) {
    wrap.style.display = show ? 'block' : 'none';
  }
}

function switchVmTab(tabId) {
  // Update Tab Buttons
  document.querySelectorAll('.vm-tab-btn, .vm-nav-pill').forEach(btn => {
    if (btn.getAttribute('data-tab') === tabId) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  // Update Tab Panes
  document.querySelectorAll('.vm-tab-pane').forEach(pane => {
    pane.classList.remove('active');
  });

  if (tabId === 'vision') {
    const visionPane = document.getElementById('vmTabVision');
    if (visionPane) visionPane.classList.add('active');
  } else if (tabId === 'mission') {
    const missionPane = document.getElementById('vmTabMission');
    if (missionPane) missionPane.classList.add('active');
}
</script>

