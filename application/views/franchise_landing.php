<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- ==========================================
     1. HERO BANNER SECTION (SLIDER CONTENT & DOWNLOAD BUTTON ON SLIDER)
     ========================================== -->
<section class="ref-hero-section franchise-hero-section">
  <!-- Hero Background Slider Track -->
  <div class="ref-hero-slider">
    <div class="slider-track">
      <div class="slide active franchise-hero-slide" style="background-image: url('<?php echo base_url('assets/images/VOYOGO FRANCHISE BANNER.png'); ?>');"></div>
    </div>
  </div>

  <!-- Hero Content Overlay (Pure text & download button on left, cursive tag on right) -->
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

      <!-- Right Handwritten Cursive Tag on Slider -->
      <div class="ref-cursive-tag franchise-cursive-tag">
        Travel Together<br>Grow Bigger
        <i class="fa-solid fa-plane"></i>
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
     3. ABOUT US SECTION (2-COLUMN: LEFT CONTENT, RIGHT IMAGE)
     ========================================== -->
<style>
  .franchise-about-section,
  .franchise-about-section * {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif !important;
  }
  .about-main-heading {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-weight: 800 !important;
    color: #0d3470 !important;
    font-size: 2.15rem !important;
    line-height: 1.3 !important;
  }
  .about-lead-text, .about-sub-text {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-size: 16px !important;
    line-height: 1.85 !important;
    color: #334155 !important;
  }
  .about-lead-text strong {
    font-weight: 800 !important;
    color: #0F172A !important;
  }
  .franchise-vm-tab-section,
  .franchise-vm-tab-section * {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif !important;
  }
  .vm-tab-btn {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-weight: 800 !important;
    letter-spacing: 0.8px !important;
  }
  .vm-pane-title {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-weight: 800 !important;
    font-size: 1.85rem !important;
  }
  .vm-main-text {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-size: 16px !important;
    line-height: 1.9 !important;
  }
  .quote-statement {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-size: 16px !important;
    font-style: italic !important;
  }
  .model-bottom-objective {
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
    font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif !important;
    font-size: 15.5px !important;
    font-weight: 600 !important;
    color: #0d3470 !important;
    line-height: 1.65 !important;
    margin: 24px 0 0 0 !important;
    padding: 16px 24px !important;
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%) !important;
    border: 1.5px solid #86EFAC !important;
    border-left: 6px solid #15803d !important;
    border-radius: 6px 14px 14px 6px !important;
    box-shadow: 0 6px 20px rgba(21, 128, 61, 0.12) !important;
  }
  .model-bottom-objective .objective-icon {
    font-size: 20px !important;
    color: #15803d !important;
    flex-shrink: 0 !important;
  }
  .model-bottom-objective strong {
    color: #15803d !important;
    font-weight: 800 !important;
  }
</style>
<section class="franchise-about-section">
  <div class="container">
    <div class="franchise-about-grid">
      
      <!-- Left Column: About Content -->
      <div class="franchise-about-content">
        <span class="franchise-pill-badge green-badge">ABOUT VOYOGO</span>
        <h2 class="about-main-heading">VOYOGO is a professionally managed travel company offering:</h2>
        
        <p class="about-lead-text">
          <strong>A New Brand. A Proven Legacy.</strong> Skyplanet’s VOYOGO is the flagship franchise brand of Skyplanet Voyages Private Limited, built on years of proven experience in the travel industry. Formerly operating as Skyplanet Holidays, our leadership team has successfully delivered international group departures, customized holidays, visa assistance, and comprehensive travel solutions to thousands of travellers across multiple global destinations.
        </p>
        
        <p class="about-sub-text">
          Over the years, we have built strong operational systems, trusted global partnerships, and a customer-first approach that have earned us the confidence of travellers and business partners alike. Today, with the launch of Skyplanet’s VOYOGO, we are transforming this proven expertise into a scalable franchise model, empowering entrepreneurs across India with a trusted brand, technology-driven operations, structured business systems, comprehensive training, and continuous support.
        </p>
        
        <p class="about-sub-text">
          At VOYOGO, we believe that sustainable growth is built on trust, experience, and long-term partnerships. We are not just expanding a travel brand. We are building India’s next generation travel entrepreneur network.
        </p>

        <!-- Quick Stats/Highlights Row -->
        <div class="about-highlights-row">
          <div class="about-highlight-item">
            <span class="highlight-num">10+</span>
            <span class="highlight-lbl">Years Industry Experience</span>
          </div>
          <div class="about-highlight-item">
            <span class="highlight-num">100+</span>
            <span class="highlight-lbl">Global Suppliers</span>
          </div>
          <div class="about-highlight-item">
            <span class="highlight-num">100%</span>
            <span class="highlight-lbl">Tech & Ops Support</span>
          </div>
        </div>
      </div>

      <!-- Right Column: Image Showcase -->
      <div class="franchise-about-visual-col">
        <div class="about-image-card">
          <img src="<?php echo base_url('assets/images/voyogo bali.png'); ?>" alt="Skyplanet Voyogo Franchise Network" class="about-main-img">
          
          <!-- Floating Feature Badges on Image -->
          <div class="about-float-badge top-right">
            <i class="fa-solid fa-certificate"></i>
            <span>Proven Legacy</span>
          </div>
          <div class="about-float-badge bottom-left">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Trusted Brand</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     4. OUR VISION & OUR MISSION (TABBED SECTION WITH TEXT BACKGROUND)
     ========================================== -->
<section class="franchise-vm-tab-section">
  <div class="container">
    
    <div class="vm-tab-container">
      
      <!-- Tab Header Buttons -->
      <div class="vm-tabs-header">
        <button type="button" class="vm-tab-btn active" data-tab="vision" onclick="switchVmTab('vision')">
          <i class="fa-solid fa-eye me-2"></i>
          <span>OUR VISION</span>
        </button>
        <button type="button" class="vm-tab-btn" data-tab="mission" onclick="switchVmTab('mission')">
          <i class="fa-solid fa-bullseye me-2"></i>
          <span>OUR MISSION</span>
        </button>
      </div>

      <!-- Tab Content Wrapper with Background Card -->
      <div class="vm-tab-content-card">
        
        <!-- Tab 1: Vision Pane -->
        <div class="vm-tab-pane active" id="vmTabVision">
          <div class="vm-pane-header">
            <span class="vm-pane-tag"><i class="fa-solid fa-compass me-1"></i> Strategic Vision</span>
            <h3 class="vm-pane-title">Building India's Most Trusted Travel Entrepreneur Network</h3>
          </div>
          
          <div class="vm-text-bg-box">
            <p class="vm-main-text">
              To build India’s most trusted travel entrepreneur network, empowering passionate entrepreneurs to establish successful travel businesses through a proven business model, innovative technology, structured systems, and continuous support. Our vision is to make Skyplanet’s VOYOGO the first choice for travellers and travel entrepreneurs across India, delivering world-class experiences from Andaman to Antarctica.
            </p>
          </div>

          <div class="vm-quote-highlight-box">
            <div class="quote-icon-wrap">
              <i class="fa-solid fa-quote-left"></i>
            </div>
            <p class="quote-statement">
              “Our vision is not just to expand our business, but to empower thousands of entrepreneurs to build successful travel businesses under one trusted brand.”
            </p>
          </div>
        </div>

        <!-- Tab 2: Mission Pane -->
        <div class="vm-tab-pane" id="vmTabMission">
          <div class="vm-pane-header">
            <span class="vm-pane-tag"><i class="fa-solid fa-rocket me-1"></i> Core Mission</span>
            <h3 class="vm-pane-title">Empowering Entrepreneurs with Excellence & Integrity</h3>
          </div>

          <div class="vm-text-bg-box">
            <p class="vm-main-text">
              To empower travel entrepreneurs through a trusted brand, proven business systems, innovative technology, professional training, and continuous support, enabling them to build successful and sustainable travel businesses while delivering exceptional travel experiences with integrity, excellence, and customer satisfaction.
            </p>
          </div>

          <!-- Mission 4 Pillars -->
          <div class="vm-mission-pillars-grid">
            <div class="mission-pillar-card">
              <div class="pillar-icon"><i class="fa-solid fa-handshake-simple"></i></div>
              <div class="pillar-text">
                <strong>Trust & Integrity</strong>
                <span>Building transparent, long-term business partnerships</span>
              </div>
            </div>
            <div class="mission-pillar-card">
              <div class="pillar-icon"><i class="fa-solid fa-laptop-code"></i></div>
              <div class="pillar-text">
                <strong>Innovative Technology</strong>
                <span>Cutting-edge booking engines & cloud systems</span>
              </div>
            </div>
            <div class="mission-pillar-card">
              <div class="pillar-icon"><i class="fa-solid fa-graduation-cap"></i></div>
              <div class="pillar-text">
                <strong>Professional Training</strong>
                <span>Continuous guidance and structured sales playbooks</span>
              </div>
            </div>
            <div class="mission-pillar-card">
              <div class="pillar-icon"><i class="fa-solid fa-face-smile"></i></div>
              <div class="pillar-text">
                <strong>Customer Satisfaction</strong>
                <span>Delivering world-class memorable travel experiences</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</section>


<!-- ==========================================
     4. WHY CHOOSE SKYPLANET'S VOYOGO? (12 PILLARS)
     ========================================== -->
<section class="franchise-why-section">
  <div class="container">
    <div class="franchise-why-card">
      
      <!-- Section Header -->
      <div class="franchise-why-header">
        <h2 class="why-main-title">WHY CHOOSE <span class="highlight-gold">SKYPLANET'S VOYOGO?</span></h2>
        <p class="why-subtitle">Choosing a Skyplanet's VOYOGO Franchise provides access to:</p>
      </div>

      <!-- 12 Pillars Circle Grid -->
      <div class="franchise-pillars-grid">
        
        <!-- Pillar 1 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-certificate"></i></div>
          <h4 class="pillar-circle-title">Established<br>Brand Identity</h4>
        </div>

        <!-- Pillar 2 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-gears"></i></div>
          <h4 class="pillar-circle-title">Proven Business<br>Processes</h4>
        </div>

        <!-- Pillar 3 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-network-wired"></i></div>
          <h4 class="pillar-circle-title">Centralised<br>Operations</h4>
        </div>

        <!-- Pillar 4 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-user-tie"></i></div>
          <h4 class="pillar-circle-title">Professional<br>CRM</h4>
        </div>

        <!-- Pillar 5 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
          <h4 class="pillar-circle-title">Cloud<br>Billing</h4>
        </div>

        <!-- Pillar 6 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-book-open-reader"></i></div>
          <h4 class="pillar-circle-title">Sales<br>Playbooks</h4>
        </div>

        <!-- Pillar 7 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-book-bookmark"></i></div>
          <h4 class="pillar-circle-title">Operations<br>Manual</h4>
        </div>

        <!-- Pillar 8 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
          <h4 class="pillar-circle-title">Training<br>Programmes</h4>
        </div>

        <!-- Pillar 9 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-bullhorn"></i></div>
          <h4 class="pillar-circle-title">Marketing<br>Support</h4>
        </div>

        <!-- Pillar 10 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-laptop-code"></i></div>
          <h4 class="pillar-circle-title">Technology<br>Support</h4>
        </div>

        <!-- Pillar 11 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-globe"></i></div>
          <h4 class="pillar-circle-title">Continuous Business<br>Guidance</h4>
        </div>

        <!-- Pillar 12 -->
        <div class="pillar-circle-card">
          <div class="pillar-circle-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
          <h4 class="pillar-circle-title">High Earning<br>Potential</h4>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     5. INVESTMENT DETAILS & SPECIAL OFFER SECTION (HIGHLIGHTED)
     ========================================== -->
<section class="franchise-investment-section">
  <div class="container">
    <div class="investment-highlight-card">
      
      <!-- Top Badge -->
      <div class="investment-badge-wrap">
        <span class="franchise-pill-badge green-badge lg">INVESTMENT DETAILS</span>
      </div>

      <div class="investment-flex-row">
        
        <!-- Left: 30% Offer for the first 25 Persons -->
        <div class="investment-offer-text-col">
          <span class="offer-sub-title">30% OFFER FOR THE FIRST</span>
          <div class="offer-persons-row">
            <h2 class="offer-main-num">25 PERSONS</h2>
            <i class="fa-solid fa-hand-point-right offer-hand-icon"></i>
          </div>
        </div>

        <!-- Right: Special Offer Navy Card -->
        <div class="investment-special-offer-card">
          <h3 class="special-offer-title">SPECIAL OFFER</h3>
          <div class="offer-price-box">
            <span class="price-label-now">NOW</span>
            <div class="price-amount-wrap">
              <span class="price-currency">₹</span>
              <span class="price-value">10,00,000</span>
            </div>
          </div>
          <button type="button" class="btn-claim-special-offer" onclick="document.querySelector('.franchise-horizontal-form-section').scrollIntoView({ behavior: 'smooth' });">
            <span>CLAIM OFFER NOW</span>
            <i class="fa-solid fa-arrow-up"></i>
          </button>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- ==========================================
     6. OUR BUSINESS MODEL & SUPPORT (EXACT IMAGE CONTENT)
     ========================================== -->
<section class="franchise-model-section">
  <div class="container">
    
    <div class="franchise-model-main-card">
      
      <!-- Section Badge (No extra subheading) -->
      <div class="model-badge-header">
        <span class="franchise-pill-badge green-badge">OUR BUSINESS MODEL</span>
      </div>

      <!-- (i) VOYOGO Franchise Business Model -->
      <div class="model-group-block">
        <h3 class="model-group-title">
          <span class="title-green">(i) VOYOGO Franchise Business Model</span>
          <span class="title-sub">(What franchise does)</span>
        </h3>
        
        <div class="franchise-role-grid">
          <div class="role-item">
            <span class="role-icon">🧳</span>
            <span class="role-name">Customer Acquisition</span>
          </div>
          <div class="role-item">
            <span class="role-icon">📢</span>
            <span class="role-name">Local Marketing</span>
          </div>
          <div class="role-item">
            <span class="role-icon">📈</span>
            <span class="role-name">Sales</span>
          </div>
          <div class="role-item">
            <span class="role-icon">📋</span>
            <span class="role-name">Documentation Collection</span>
          </div>
          <div class="role-item">
            <span class="role-icon">💬</span>
            <span class="role-name">Customer Relationship Management</span>
          </div>
          <div class="role-item">
            <span class="role-icon">💳</span>
            <span class="role-name">Payment Collection</span>
          </div>
        </div>
      </div>

      <!-- (ii) VOYOGO BUSINESS SUPPORT -->
      <div class="model-group-block">
        <h3 class="model-group-title">
          <span class="title-green">(ii) VOYOGO &nbsp;BUSINESS SUPPORT</span>
        </h3>
        
        <div class="voyogo-support-bullets-grid">
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Operations</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Hotel Reservations</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Supplier Negotiation</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Billing</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Technology Support</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Brand Management</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Flight Reservations</span>
          </div>
          <div class="support-bullet-item">
            <i class="fa-solid fa-play bullet-arrow"></i>
            <span>Visa Coordination</span>
          </div>
        </div>
      </div>

      <!-- VOYOGO SERVICES -->
      <div class="model-group-block services-group-block">
        <div class="services-header-row">
          <span class="franchise-pill-badge green-badge">VOYOGO SERVICES</span>
          <span class="services-header-subtitle">(What Kind of Services VOYOGO Offered to our Customers)</span>
        </div>

        <div class="voyogo-services-list-grid">
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>International Holiday Packages</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Flight Ticketing</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Domestic Holiday Packages</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Hotel Reservations</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Fixed Group Departures</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Travel Insurance</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Customized FIT Holidays</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Forex Assistance</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Visa Assistance</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>Corporate Travel Solutions</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>International Cruise Booking</span>
          </div>
          <div class="voyogo-service-item">
            <i class="fa-solid fa-plane service-icon"></i>
            <span>One Stop For All Kinds Of Travel Solutions</span>
          </div>
        </div>

        <!-- Bottom Objective Statement -->
        <div class="model-bottom-objective">
          <i class="fa-solid fa-bullseye objective-icon"></i>
          <span><strong>Our objective</strong> is to deliver world-class travel experiences through standardised processes and exceptional customer service.</span>
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
  document.querySelectorAll('.vm-tab-btn').forEach(btn => {
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
}
</script>

