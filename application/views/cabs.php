<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- HERO BANNER SECTION WITH IMAGE SLIDER & CAB ENQUIRY FORM -->
  <section class="hero-section cabs-hero">
    <!-- Hero Background Slider Track -->
    <div class="hero-slider">
      <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/slider1.png'); ?>');">
      </div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/slider2.png'); ?>');"></div>
    </div>

    <!-- Navigation Controls -->
    <button class="slider-arrow prev" aria-label="Previous Slide">‹</button>
    <button class="slider-arrow next" aria-label="Next Slide">›</button>

    <!-- Pagination Dots -->
    <div class="slider-dots">
      <span class="dot active"></span>
      <span class="dot"></span>
    </div>

    <div class="container">
      <div class="hero-content-wrapper">
        <!-- Hero Right Form Container (EXACT CAB ENQUIRY FORM AS REQUESTED) -->
        <div class="cabs-hero-form-wrapper">
          <div class="cab-form-card cab-glass-card">

            <h3 class="cab-form-title">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11.2 2 11.6 2 12v4c0 .6.4 1 1 1h2" />
                <circle cx="7" cy="17" r="2" />
                <circle cx="17" cy="17" r="2" />
              </svg>
              Cab Enquiry Form
            </h3>

            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="cabEnquiryHeroForm">
              <input type="hidden" name="message" value="Cab Booking Enquiry">
              <input type="hidden" name="trip_type" id="hero_trip_type_input" value="One Way">

              <!-- Trip Type Tabs (Starting of Form) -->
              <div class="form-trip-tabs">
                <button type="button" class="form-trip-tab active"
                  onclick="selectFormTripType(this, 'One Way', 'hero_trip_type_input')">One Way</button>
                <button type="button" class="form-trip-tab"
                  onclick="selectFormTripType(this, 'Round Trip', 'hero_trip_type_input')">Round Trip</button>
                <button type="button" class="form-trip-tab"
                  onclick="selectFormTripType(this, 'Airport Transfer', 'hero_trip_type_input')">Airport
                  Transfer</button>
                <button type="button" class="form-trip-tab"
                  onclick="selectFormTripType(this, 'Local Rental', 'hero_trip_type_input')">Local Rental</button>
              </div>

              <!-- Row 1: Full Name & Mobile Number -->
              <div class="form-row-2col">
                <div class="cab-form-group">
                  <label class="cab-form-label">Full Name <span class="req-star">*</span></label>
                  <input type="text" name="name" class="cab-input" placeholder="Full Name" required>
                </div>
                <div class="cab-form-group">
                  <label class="cab-form-label">Mobile Number <span class="req-star">*</span></label>
                  <input type="tel" name="phone" class="cab-input" placeholder="Mobile Number" required>
                </div>
              </div>

              <!-- Row 2: Pickup Location & Drop Location -->
              <div class="form-row-2col">
                <div class="cab-form-group">
                  <label class="cab-form-label">Pickup Location <span class="req-star">*</span></label>
                  <input type="text" name="pickup_location" class="cab-input" placeholder="Pickup City / Area" required>
                </div>

                <div class="cab-form-group">
                  <label class="cab-form-label">Drop Location <span class="req-star">*</span></label>
                  <input type="text" name="drop_location" class="cab-input" placeholder="Drop City / Destination"
                    required>
                </div>
              </div>

              <!-- Row 3: Travel Date, Pickup Time & Passengers -->
              <div class="form-row-2col">
                <div class="cab-form-group">
                  <label class="cab-form-label">Travel Date <span class="req-star">*</span></label>
                  <input type="text" name="travel_date" class="cab-input" placeholder="Travel Date *"
                    onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                  <div class="cab-form-group">
                    <label class="cab-form-label">Pickup Time <span class="req-star">*</span></label>
                    <input type="text" name="pickup_time" class="cab-input" placeholder="Pickup Time *"
                      onfocus="(this.type='time')" onblur="if(!this.value)this.type='text'" required>
                  </div>
                  <div class="cab-form-group">
                    <label class="cab-form-label">Passengers <span class="req-star">*</span></label>
                    <input type="number" name="passengers" class="cab-input" placeholder="Passengers *" min="1" max="50"
                      required>
                  </div>
                </div>
              </div>

              <!-- Row 4: Cab Type -->
              <div class="cab-form-group">
                <label class="cab-form-label">Cab Type <span class="req-star">*</span></label>
                <div class="cab-radio-grid compact-cab-grid">
                  <label class="cab-radio-pill"><input type="radio" name="cab_type" value="Sedan" required>
                    Sedan</label>
                  <label class="cab-radio-pill"><input type="radio" name="cab_type" value="SUV"> SUV</label>
                  <label class="cab-radio-pill"><input type="radio" name="cab_type" value="Tempo Traveller">
                    Tempo</label>
                  <label class="cab-radio-pill"><input type="radio" name="cab_type" value="Luxury Car"> Luxury</label>
                </div>
              </div>

              <!-- Row 5: Consent & Submit -->
              <div class="form-bottom-row">
                <div class="consent-checkbox-group" style="margin: 0;">
                  <input type="checkbox" name="consent" id="consent_check_hero" checked required>
                  <label for="consent_check_hero" class="consent-label">I agree to be contacted regarding my
                    enquiry.</label>
                </div>
                <button type="submit" class="btn-send-enquiry btn-send-wide">SUBMIT CAB ENQUIRY</button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
    </div>
  </section>

  <!-- TWO COLUMN CAB SHOWCASE SECTION (CONTENT LEFT / CAR IMAGE RIGHT) -->
  <section class="cab-twocol-section">
    <div class="container">
      <div class="cab-twocol-grid">

        <!-- Leftside Content -->
        <div class="cab-twocol-left">
          <span class="slide-tag" style="background: #203A54;">⚡ Seamless & Safe Travel</span>
          <h2 class="cab-twocol-title">Premium Airport Transfers & Outstation Cabs</h2>
          <p class="cab-twocol-desc">
            Experience comfortable, reliable rides across 100+ cities with Voyogo. Whether you need a 24/7 airport
            transfer, a flexible hourly local rental, or an outstation trip, we offer transparent fixed pricing with
            zero hidden surcharges and professional commercial chauffeurs.
          </p>

          <div class="cab-feature-bullets">

            <div class="cab-bullet-item">
              <div class="cab-bullet-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
              </div>
              <div class="cab-bullet-text">
                <h5>Flight-Tracked Airport Pickups</h5>
                <p>Guaranteed on-time doorstep pickup and airport transfers with real-time flight tracking.</p>
              </div>
            </div>

            <div class="cab-bullet-item">
              <div class="cab-bullet-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </div>
              <div class="cab-bullet-text">
                <h5>Flexible Hourly Local Rentals</h5>
                <p>Book 4hr/40km or 8hr/80km local rental packages for hassle-free city travel & meetings.</p>
              </div>
            </div>

            <div class="cab-bullet-item">
              <div class="cab-bullet-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path
                    d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11.2 2 11.6 2 12v4c0 .6.4 1 1 1h2" />
                </svg>
              </div>
              <div class="cab-bullet-text">
                <h5>Intercity One-Way & Round Trips</h5>
                <p>Affordable per-kilometer rates for outstation journeys with top-rated commercial drivers.</p>
              </div>
            </div>

          </div>

          <button onclick="openEnquiryModal('Book Premium Cab')" class="btn-primary"
            style="height: 48px; padding: 0 28px; font-size: 0.95rem;">Book Your Ride Now</button>
        </div>

        <!-- Rightside Car Image -->
        <div class="cab-twocol-right">
          <div class="cab-showcase-frame">
            <img src="<?php echo base_url('assets/images/voyogo_innova_cabs.jpg'); ?>" alt="Voyogo Premium Cab Showcase"
              class="cab-showcase-img">

            <div class="cab-floating-badge">
              <div class="cab-floating-badge-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2.5">
                  <polyline points="20 6 9 17 4 12" />
                </svg>
              </div>
              <div>
                <div class="cab-floating-badge-title">24/7 Cab Service</div>
                <div class="cab-floating-badge-sub">Clean Cabs & Instant Confirmation</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CAB FLEET SHOWCASE SECTION (Sedan, SUV, Tempo Traveller, Luxury Car) -->
  <section class="cab-fleet-section">
    <div class="container">
      <div style="text-align: center; max-width: 700px; margin: 0 auto;">
        <span class="slide-tag">🚗 Our Modern Fleet</span>
        <h2 style="font-size: 2.3rem; font-weight: 800; color: #0B1938; margin-top: 8px;">Explore Vehicle Types &
          Pricing</h2>
        <p style="color: #64748B; font-weight: 500;">Choose from budget sedans to spacious SUVs and premium luxury cars
          for every travel need.</p>
      </div>

      <div class="cab-fleet-grid">

        <!-- Fleet Item 1: Sedan -->
        <div class="cab-fleet-card">
          <span class="cab-card-badge">Popular</span>
          <img src="<?php echo base_url('assets/images/voyogo sedan cap.png'); ?>" alt="Sedan Cab"
            class="cab-card-image">
          <div class="cab-card-body">
            <h3 class="cab-type-title">Sedan Cab</h3>
            <p class="cab-models-text">Dzire, Etios, Xcent or equivalent</p>

            <div class="cab-specs-list">
              <span class="cab-spec-badge">👥 4 Passengers</span>
              <span class="cab-spec-badge">🧳 2 Luggage</span>
              <span class="cab-spec-badge">❄️ AC Equipped</span>
            </div>

            <!--<div class="cab-card-price">
              ₹11 <span>/ km</span>
            </div>-->

            <button onclick="prefillCabForm('Sedan')" class="btn-send-enquiry">Book Sedan</button>
          </div>
        </div>

        <!-- Fleet Item 2: SUV -->
        <div class="cab-fleet-card">
          <span class="cab-card-badge">Best Value</span>
          <img src="<?php echo base_url('assets/images/cab_showcase.jpg'); ?>" alt="SUV Cab" class="cab-card-image">
          <div class="cab-card-body">
            <h3 class="cab-type-title">SUV Cab</h3>
            <p class="cab-models-text">Ertiga, Innova Crysta or equivalent</p>

            <div class="cab-specs-list">
              <span class="cab-spec-badge">👥 6-7 Passengers</span>
              <span class="cab-spec-badge">🧳 4 Luggage</span>
              <span class="cab-spec-badge">❄️ Dual AC</span>
            </div>

            <!--<div class="cab-card-price">
              ₹15 <span>/ km</span>
            </div>-->

            <button onclick="prefillCabForm('SUV')" class="btn-send-enquiry">Book SUV</button>
          </div>
        </div>

        <!-- Fleet Item 3: Tempo Traveller -->
        <div class="cab-fleet-card">
          <span class="cab-card-badge">Group Special</span>
          <img src="<?php echo base_url('assets/images/voyogo tempo cap.png'); ?>" alt="Tempo Traveller"
            class="cab-card-image">
          <div class="cab-card-body">
            <h3 class="cab-type-title">Tempo Traveller</h3>
            <p class="cab-models-text">12/16/20 Seater Force Traveller</p>

            <div class="cab-specs-list">
              <span class="cab-spec-badge">👥 12-20 Passengers</span>
              <span class="cab-spec-badge">🧳 10+ Luggage</span>
              <span class="cab-spec-badge">📺 Recliner Seats</span>
            </div>

            <!--<div class="cab-card-price">
              ₹24 <span>/ km</span>
            </div>-->

            <button onclick="prefillCabForm('Tempo Traveller')" class="btn-send-enquiry">Book Traveller</button>
          </div>
        </div>

        <!-- Fleet Item 4: Luxury Car -->
        <div class="cab-fleet-card">
          <span class="cab-card-badge">Premium</span>
          <img src="<?php echo base_url('assets/images/voyogo luxury cap.png'); ?>" alt="Luxury Car"
            class="cab-card-image">
          <div class="cab-card-body">
            <h3 class="cab-type-title">Luxury Car</h3>
            <p class="cab-models-text">Mercedes E-Class, BMW 5 Series, Audi</p>

            <div class="cab-specs-list">
              <span class="cab-spec-badge">👥 4 Passengers</span>
              <span class="cab-spec-badge">🧳 3 Luggage</span>
              <span class="cab-spec-badge">⭐ VIP Chauffeur</span>
            </div>

            <!--<div class="cab-card-price">
              ₹45 <span>/ km</span>
            </div>-->

            <button onclick="prefillCabForm('Luxury Car')" class="btn-send-enquiry">Book Luxury</button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- WHY CHOOSE US SECTION -->
  <section class="why-choose-section">
    <div class="container">

      <div class="why-choose-header">
        <h2 class="why-choose-title">Why Choose <span>Voyogo Cabs</span>?</h2>
        <p class="why-choose-sub">Your trusted travel partner for seamless, punctual, and safe cab journeys across 100+
          cities.</p>
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
            <h3 class="why-card-title">Best Price & Fixed Fares</h3>
            <p class="why-card-sub">Transparent per-km fares with zero night surcharges & no hidden fees.</p>
          </div>
        </div>

        <!-- Feature 2: 24/7 Doorstep Pickup -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">24/7 On-Time Pickup</h3>
            <p class="why-card-sub">Punctual airport transfers and outstation door-to-door cab pickups.</p>
          </div>
        </div>

        <!-- Feature 3: Verified Drivers -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Verified Commercial Chauffeurs</h3>
            <p class="why-card-sub">Polite, background-checked, and highly experienced drivers for safe travel.</p>
          </div>
        </div>

        <!-- Feature 4: Clean & Sanitized Fleet -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
              <polyline points="9 12 11 14 15 10" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Clean & Sanitized Fleet</h3>
            <p class="why-card-sub">Thoroughly disinfected AC vehicles with air fresheners before every ride.</p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- PLAYSTORE / APP BANNER SECTION -->
  <section class="app-banner-section">
    <div class="container">
      <div class="app-banner-card">

        <!-- Left Content Column -->
        <div class="app-banner-content">
          <span class="app-tag">📱 Mobile Experience</span>
          <h2 class="app-banner-title">Download the <span>Voyogo</span> App for Instant Cab Bookings</h2>
          <p class="app-banner-desc">Book outstation cabs, airport transfers, and hourly rentals in seconds. Track
            driver location in real-time, get instant fare estimates, and unlock app-only discounts.</p>

          <!-- Key Features List -->
          <div class="app-features-list">
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Real-Time Driver Location Tracking & Live Cab Arrival Status</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Instant Fare Calculation with Zero Peak Price Surcharges</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>24/7 Dedicated Driver & Ride Assistance Concierge</span>
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

  <!-- QUICK ENQUIRY MODAL (WITH CAB ENQUIRY FORM FIELDS) -->
  <div class="modal-overlay" id="enquiryModal">
    <div class="modal-box cab-form-card" style="max-width: 480px;">
      <span class="modal-close" onclick="closeEnquiryModal()">&times;</span>

      <h3 class="cab-form-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2.5">
          <path
            d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11.2 2 11.6 2 12v4c0 .6.4 1 1 1h2" />
          <circle cx="7" cy="17" r="2" />
          <circle cx="17" cy="17" r="2" />
        </svg>
        Cab Enquiry Form
      </h3>

      <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="modalCabForm">
        <input type="hidden" name="message" id="modalPackageName" value="Cab Modal Enquiry">
        <input type="hidden" name="trip_type" id="modal_trip_type_input" value="One Way">

        <!-- Trip Type Tabs (Starting of Form) -->
        <div class="form-trip-tabs">
          <button type="button" class="form-trip-tab active"
            onclick="selectFormTripType(this, 'One Way', 'modal_trip_type_input')">One Way</button>
          <button type="button" class="form-trip-tab"
            onclick="selectFormTripType(this, 'Round Trip', 'modal_trip_type_input')">Round Trip</button>
          <button type="button" class="form-trip-tab"
            onclick="selectFormTripType(this, 'Airport Transfer', 'modal_trip_type_input')">Airport Transfer</button>
          <button type="button" class="form-trip-tab"
            onclick="selectFormTripType(this, 'Local Rental', 'modal_trip_type_input')">Local Rental</button>
        </div>

        <!-- Full Name * -->
        <div class="cab-form-group">
          <label class="cab-form-label">Full Name <span class="req-star">*</span></label>
          <input type="text" name="name" class="cab-input" placeholder="Full Name" required>
        </div>

        <!-- Mobile Number * -->
        <div class="cab-form-group">
          <label class="cab-form-label">Mobile Number <span class="req-star">*</span></label>
          <input type="tel" name="phone" class="cab-input" placeholder="Mobile Number" required>
        </div>

        <!-- Pickup Location * & Drop Location * -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="cab-form-group">
            <label class="cab-form-label">Pickup Location <span class="req-star">*</span></label>
            <input type="text" name="pickup_location" class="cab-input" placeholder="Pickup Location" required>
          </div>
          <div class="cab-form-group">
            <label class="cab-form-label">Drop Location <span class="req-star">*</span></label>
            <input type="text" name="drop_location" class="cab-input" placeholder="Drop Location" required>
          </div>
        </div>

        <!-- Travel Date * & Pickup Time * -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="cab-form-group">
            <label class="cab-form-label">Travel Date <span class="req-star">*</span></label>
            <input type="date" name="travel_date" class="cab-input" required value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="cab-form-group">
            <label class="cab-form-label">Pickup Time <span class="req-star">*</span></label>
            <input type="time" name="pickup_time" class="cab-input" required value="09:00">
          </div>
        </div>

        <!-- Cab Type * -->
        <div class="cab-form-group">
          <label class="cab-form-label">Cab Type <span class="req-star">*</span></label>
          <div class="cab-radio-grid">
            <label class="cab-radio-pill">
              <input type="radio" name="cab_type" value="Sedan" id="modal_cab_sedan" checked> Sedan
            </label>
            <label class="cab-radio-pill">
              <input type="radio" name="cab_type" value="SUV" id="modal_cab_suv"> SUV
            </label>
            <label class="cab-radio-pill">
              <input type="radio" name="cab_type" value="Tempo Traveller" id="modal_cab_tt"> Tempo Traveller
            </label>
            <label class="cab-radio-pill">
              <input type="radio" name="cab_type" value="Luxury Car" id="modal_cab_luxury"> Luxury Car
            </label>
          </div>
        </div>

        <!-- Number of Passengers * -->
        <div class="cab-form-group">
          <label class="cab-form-label">Number of Passengers <span class="req-star">*</span></label>
          <input type="number" name="passengers" class="cab-input" placeholder="Number of Passengers" min="1" value="1"
            required>
        </div>

        <!-- Consent ☑ I agree to be contacted regarding my enquiry. -->
        <div class="consent-checkbox-group">
          <input type="checkbox" name="consent" id="consent_check_modal" checked required>
          <label for="consent_check_modal" class="consent-label">
            I agree to be contacted regarding my enquiry.
          </label>
        </div>

        <button type="submit" class="btn-send-enquiry">CONFIRM CAB ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- JS Dependencies -->
  <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>

  <!-- Dynamic Cab Page Controls -->
  <script>
    function scrollToElement(id) {
      const el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: 'smooth' });
    }

    function selectFormTripType(btn, val, inputId) {
      const parent = btn.parentElement;
      if (parent) {
        parent.querySelectorAll('.form-trip-tab').forEach(t => t.classList.remove('active'));
      }
      btn.classList.add('active');
      const hiddenInput = document.getElementById(inputId);
      if (hiddenInput) {
        hiddenInput.value = val;
      }
    }

    function switchWidgetTab(tabName) {
      const tabAirport = document.getElementById('tabAirport');
      const tabLocal = document.getElementById('tabLocal');
      const tabOutstation = document.getElementById('tabOutstation');
      const msgField = document.getElementById('widgetFormMessage');

      tabAirport.classList.remove('active');
      tabLocal.classList.remove('active');
      tabOutstation.classList.remove('active');

      if (tabName === 'airport') {
        tabAirport.classList.add('active');
        msgField.value = "Airport Transfer Search";
      } else if (tabName === 'local') {
        tabLocal.classList.add('active');
        msgField.value = "Local Package Search";
      } else if (tabName === 'outstation') {
        tabOutstation.classList.add('active');
        msgField.value = "Outstation Trip Search";
      }
    }

    function prefillCabForm(cabType) {
      openEnquiryModal('Book ' + cabType + ' Cab');

      const modalForm = document.getElementById('modalCabForm');
      if (modalForm) {
        const radios = modalForm.querySelectorAll('input[name="cab_type"]');
        radios.forEach(r => {
          if (r.value.toLowerCase() === cabType.toLowerCase()) {
            r.checked = true;
          }
        });
      }
    }
  </script>
  <!-- Cab Pages Script -->
  <script src="<?php echo base_url('assets/js/pages_main.js'); ?>"></script>