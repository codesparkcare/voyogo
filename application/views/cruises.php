<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- HERO BANNER SECTION WITH IMAGE SLIDER & CRUISE ENQUIRY FORM -->
  <section class="hero-section cruises-hero">
    <!-- Hero Background Slider Track using uploaded cruise images -->
    <div class="hero-slider">
      <div class="slide active" style="background-image: url('<?php echo base_url('assets/images/cruise_slider1.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/cruise_slider2.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>');"></div>
      <div class="slide" style="background-image: url('<?php echo base_url('assets/images/voyogo cruise 2.png'); ?>');"></div>
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
        
        <!-- Hero Right Form Container (CRUISE ENQUIRY FORM) -->
        <div class="cruise-hero-form-wrapper">
          <div class="cruise-form-card cruise-glass-card">
            
            <h3 class="cruise-form-title">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 21h20M4 17l2-10 6-3 6 3 2 10M12 4v13"/>
              </svg>
              Cruise Enquiry Form
            </h3>

            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="cruiseEnquiryHeroForm">
              <input type="hidden" name="message" value="Cruise Booking Enquiry">

              <!-- Row 1: Full Name & Mobile Number -->
              <div class="form-row-2col">
                <div class="cruise-form-group">
                  <label class="cruise-form-label">Full Name <span class="req-star">*</span></label>
                  <input type="text" name="name" class="cruise-input" placeholder="Full Name" required>
                </div>
                <div class="cruise-form-group">
                  <label class="cruise-form-label">Mobile Number <span class="req-star">*</span></label>
                  <input type="tel" name="phone" class="cruise-input" placeholder="Mobile Number" required>
                </div>
              </div>

              <!-- Row 2: Email Address & Cruise Destination -->
              <div class="form-row-2col">
                <div class="cruise-form-group">
                  <label class="cruise-form-label">Email Address <span class="req-star">*</span></label>
                  <input type="email" name="email" class="cruise-input" placeholder="Email Address" required>
                </div>
                <div class="cruise-form-group">
                  <label class="cruise-form-label">Cruise Destination <span class="req-star">*</span></label>
                  <select name="destination" class="cruise-select" required>
                    <option value="" disabled selected>Select Destination</option>
                    <option value="Singapore & Malaysia Cruise">Singapore & Malaysia Cruise</option>
                    <option value="Bahamas & Caribbean Cruise">Bahamas & Caribbean Cruise</option>
                    <option value="Mediterranean & Greek Isles">Mediterranean & Greek Isles</option>
                    <option value="Alaska Glaciers Voyage">Alaska Glaciers Voyage</option>
                    <option value="Antarctica Expedition Voyage">Antarctica Expedition Voyage</option>
                    <option value="Dubai & Arabian Gulf Cruise">Dubai & Arabian Gulf Cruise</option>
                    <option value="Europe River Cruise">Europe River Cruise</option>
                  </select>
                </div>
              </div>

              <!-- Row 3: Travel Date, No. of Travelers & Budget -->
              <div class="form-row-2col">
                <div class="cruise-form-group">
                  <label class="cruise-form-label">Travel Date <span class="req-star">*</span></label>
                  <input type="text" name="travel_date" class="cruise-input" placeholder="Travel Date *" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                  <div class="cruise-form-group">
                    <label class="cruise-form-label">Travelers <span class="req-star">*</span></label>
                    <input type="number" name="travelers" class="cruise-input" placeholder="Travelers *" min="1" required>
                  </div>
                  <div class="cruise-form-group">
                    <label class="cruise-form-label">Budget</label>
                    <select name="budget" class="cruise-select">
                      <option value="" disabled selected>Budget</option>
                      <option value="Under ₹50,000">Under ₹50k</option>
                      <option value="₹50k - ₹1 Lakh">₹50k - ₹1L</option>
                      <option value="₹1 Lakh - ₹2.5 Lakh">₹1L - ₹2.5L</option>
                      <option value="₹2.5 Lakh+ Luxury">₹2.5L+ Luxury</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Row 4: Cabin Type -->
              <div class="cruise-form-group">
                <label class="cruise-form-label">Cabin Type <span class="req-star">*</span></label>
                <div class="cabin-type-grid compact-cabin-grid">
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="Interior Cabin" required> Interior</label>
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="Ocean View Cabin"> Ocean View</label>
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="Balcony Cabin"> Balcony</label>
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="Suite Cabin"> Suite</label>
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="Luxury Suite"> Luxury Suite</label>
                  <label class="cabin-radio-pill"><input type="radio" name="cabin_type" value="No Preference"> Any</label>
                </div>
              </div>

              <!-- Row 5: Consent & Submit -->
              <div class="form-bottom-row">
                <div class="consent-checkbox-group" style="margin: 0;">
                  <input type="checkbox" name="consent" id="consent_check_cruise" checked required>
                  <label for="consent_check_cruise" class="consent-label">I agree to be contacted regarding my enquiry.</label>
                </div>
                <button type="submit" class="btn-send-enquiry btn-send-wide">SUBMIT ENQUIRY</button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- EXCLUSIVE DEALS SECTION (MATCHING ATTACHMENT CARD LAYOUT & CONTENT) -->
  <section class="exclusive-deals-cruise-section">
    <div class="container">
      
      <!-- Section Header Row -->
      <div class="cruise-deals-header-wrapper">
        <h2 class="cruise-deals-title">Exclusive Deals</h2>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevCruiseDealBtn" aria-label="Previous Deal">‹</button>
          <button class="carousel-btn active" id="nextCruiseDealBtn" aria-label="Next Deal">›</button>
        </div>
      </div>

      <!-- Cruise Deals Grid / Carousel -->
      <div class="cruise-deals-grid">
        
        <!-- Deal Card 1: Cordelia Cruises -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>" alt="Cordelia Cruises" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Cordelia Cruises</h3>
            <p class="cruise-deal-sub">Kids below 12 years go free (Port charges and Gratuity applicable)</p>
            <button onclick="openEnquiryModal('Cordelia Cruises Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

        <!-- Deal Card 2: Genting Dream -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/voyogo cruise 2.png'); ?>" alt="Genting Dream" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Genting Dream</h3>
            <p class="cruise-deal-sub">Genting cruise Tour Packages</p>
            <button onclick="openEnquiryModal('Genting Dream Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

        <!-- Deal Card 3: Disney Cruise -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/voyogo cruise 3.png'); ?>" alt="Disney Cruise" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Disney Cruise</h3>
            <p class="cruise-deal-sub">Disney Cruise Packages</p>
            <button onclick="openEnquiryModal('Disney Cruise Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

        <!-- Deal Card 4: Holland America Line -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/voyogo cruise 4.png'); ?>" alt="Holland America Line" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Holland America Line</h3>
            <p class="cruise-deal-sub">Japan Cruises Starting USD 122 per person per night</p>
            <button onclick="openEnquiryModal('Holland America Line Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

        <!-- Deal Card 5: Costa Cruises -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/voyogo cruise 5.png'); ?>" alt="Costa Cruises" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Costa Cruises</h3>
            <p class="cruise-deal-sub">European Mediterranean & Greek Islands Voyages</p>
            <button onclick="openEnquiryModal('Costa Cruises Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

        <!-- Deal Card 6: Royal Caribbean -->
        <div class="cruise-deal-card">
          <img src="<?php echo base_url('assets/images/cruise_slider1.png'); ?>" alt="Royal Caribbean" class="cruise-deal-full-img">
          <div class="cruise-deal-card-body">
            <h3 class="cruise-deal-name">Royal Caribbean</h3>
            <p class="cruise-deal-sub">Bahamas & Island Hopping Caribbean Packages</p>
            <button onclick="openEnquiryModal('Royal Caribbean Deal')" class="cruise-deal-book-btn">Book Now</button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- INTERNATIONAL CRUISE TAB CATEGORIES SECTION -->
  <section class="international-cruise-section">
    <div class="container">
      
      <!-- Section Header with Title & Inline Category Tabs -->
      <div class="international-cruise-header">
        <h2 class="cruise-section-title">International Cruise</h2>

        <div class="cruise-category-tabs">
          <button type="button" class="cruise-category-tab active" id="tabBtnRoyal" onclick="switchCruiseLineTab('royal')">Royal Caribbean</button>
          <button type="button" class="cruise-category-tab" id="tabBtnSilversea" onclick="switchCruiseLineTab('silversea')">Silversea</button>
          <button type="button" class="cruise-category-tab" id="tabBtnCelebrity" onclick="switchCruiseLineTab('celebrity')">Celebrity Cruises</button>
        </div>
      </div>

      <!-- Tab Content 1: Royal Caribbean -->
      <div class="cruise-tab-content active" id="tab-cruise-royal">
        <div class="cruise-cards-grid-4col">
          
          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>" alt="Singapore & Penang Voyage" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Singapore, Penang & Phuket Voyage</h3>
              <p class="intl-cruise-duration">4 Nights / 5 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Royal Caribbean - Singapore')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 2.png'); ?>" alt="Bahamas Island Escape" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Bahamas & Perfect Day at CocoCay</h3>
              <p class="intl-cruise-duration">7 Nights / 8 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Royal Caribbean - Bahamas')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 3.png'); ?>" alt="Western Mediterranean" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Western Mediterranean Discovery</h3>
              <p class="intl-cruise-duration">9 Nights / 10 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Royal Caribbean - Mediterranean')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 5.png'); ?>" alt="Alaska Inside Passage" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Alaska Inside Passage & Glaciers</h3>
              <p class="intl-cruise-duration">7 Nights / 8 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Royal Caribbean - Alaska')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Tab Content 2: Silversea -->
      <div class="cruise-tab-content" id="tab-cruise-silversea">
        <div class="cruise-cards-grid-4col">
          
          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 4.png'); ?>" alt="Japan & Taiwan Expedition" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Japan & Taiwan Ultra-Luxury Expedition</h3>
              <p class="intl-cruise-duration">9 Nights / 10 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Silversea - Japan & Taiwan')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/Antarctica Cruise1.jpg'); ?>" alt="Antarctica Polar Glacier" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Antarctica Polar Glacier Expedition</h3>
              <p class="intl-cruise-duration">12 Nights / 13 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Silversea - Antarctica')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/cruise_slider1.png'); ?>" alt="Mediterranean Riviera" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Mediterranean Riviera All-Inclusive</h3>
              <p class="intl-cruise-duration">10 Nights / 11 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Silversea - Riviera')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/cruise_slider2.png'); ?>" alt="South Pacific & Bora Bora" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">South Pacific & Bora Bora Expedition</h3>
              <p class="intl-cruise-duration">11 Nights / 12 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Silversea - South Pacific')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Tab Content 3: Celebrity Cruises -->
      <div class="cruise-tab-content" id="tab-cruise-celebrity">
        <div class="cruise-cards-grid-4col">
          
          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 3.png'); ?>" alt="Greek Isles & Santorini" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Greek Isles & Santorini Odyssey</h3>
              <p class="intl-cruise-duration">10 Nights / 11 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Celebrity Cruises - Greek Isles')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 4.png'); ?>" alt="Dubai & Arabian Gulf" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Dubai & Arabian Gulf Heritage Voyage</h3>
              <p class="intl-cruise-duration">5 Nights / 6 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Celebrity Cruises - Dubai')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 2.png'); ?>" alt="Caribbean Southern Islands" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Caribbean Southern Islands Escape</h3>
              <p class="intl-cruise-duration">7 Nights / 8 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Celebrity Cruises - Caribbean')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

          <div class="intl-cruise-card">
            <img src="<?php echo base_url('assets/images/voyogo cruise 1.png'); ?>" alt="Northern Fjords" class="intl-cruise-img">
            <div class="intl-cruise-body">
              <h3 class="intl-cruise-title">Northern Fjords & Scandinavia</h3>
              <p class="intl-cruise-duration">11 Nights / 12 Days</p>
              <div class="intl-cruise-footer">
                <button type="button" onclick="openEnquiryModal('Celebrity Cruises - Fjords')" class="intl-details-btn">Details</button>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <script>
  function switchCruiseLineTab(tabKey) {
    document.querySelectorAll('.cruise-tab-content').forEach(function(el) {
      el.classList.remove('active');
    });
    document.querySelectorAll('.cruise-category-tab').forEach(function(el) {
      el.classList.remove('active');
    });
    
    var targetTab = document.getElementById('tab-cruise-' + tabKey);
    if (targetTab) {
      targetTab.classList.add('active');
    }
    
    if (tabKey === 'royal') {
      document.getElementById('tabBtnRoyal').classList.add('active');
    } else if (tabKey === 'silversea') {
      document.getElementById('tabBtnSilversea').classList.add('active');
    } else if (tabKey === 'celebrity') {
      document.getElementById('tabBtnCelebrity').classList.add('active');
    }
  }
  </script>

  <!-- WHY CHOOSE US SECTION -->
  <section class="why-choose-section">
    <div class="container">
      
      <div class="why-choose-header">
        <h2 class="why-choose-title">Why Choose <span>Voyogo Cruises</span>?</h2>
        <p class="why-choose-sub">Your premier cruise booking partner for luxury ocean liner vacations and shore excursions worldwide.</p>
      </div>

      <div class="why-choose-grid">
        
        <!-- Feature 1: Best Price Match -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Best Cruise Price Guarantee</h3>
            <p class="why-card-sub">Guaranteed lowest fares on Royal Caribbean, MSC, Costa, and Celebrity Cruises.</p>
          </div>
        </div>

        <!-- Feature 2: All-Inclusive Comfort -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M2 21h20M4 17l2-10 6-3 6 3 2 10M12 4v13"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">All-Inclusive Luxury Packages</h3>
            <p class="why-card-sub">Full-board gourmet dining, entertainment shows, and balcony cabin upgrades.</p>
          </div>
        </div>

        <!-- Feature 3: Dedicated Cruise Specialist -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">24/7 Dedicated Cruise Specialist</h3>
            <p class="why-card-sub">Personalized booking concierge for port visas, shore tours, and cabin preferences.</p>
          </div>
        </div>

        <!-- Feature 4: Official Cruise Line Partner -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">100% Certified Booking Partner</h3>
            <p class="why-card-sub">Authorized partner providing instant cabin vouchers & onboard credit perks.</p>
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
          <h2 class="app-banner-title">Download the <span>Voyogo</span> App for Exclusive Cruise Deals</h2>
          <p class="app-banner-desc">Explore ocean voyages, track cruise itineraries, and book luxury staterooms in seconds. Get instant price drop alerts and unlock exclusive onboard credits.</p>
          
          <!-- Key Features List -->
          <div class="app-features-list">
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Live Cruise Deck Plans, Itineraries & Port Arrival Trackers</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Instant Stateroom Upgrade Alerts & Early-Bird Deals</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
              <span>24/7 Live Cruise Concierge & Port Visa Support</span>
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

  <!-- QUICK ENQUIRY MODAL (WITH CRUISE ENQUIRY FORM FIELDS) -->
  <div class="modal-overlay" id="enquiryModal">
    <div class="modal-box cruise-form-card" style="max-width: 500px;">
      <span class="modal-close" onclick="closeEnquiryModal()">&times;</span>
      
      <h3 class="cruise-form-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2.5">
          <path d="M2 21h20M4 17l2-10 6-3 6 3 2 10M12 4v13"/>
        </svg>
        Cruise Enquiry Form
      </h3>

      <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" id="modalCruiseForm">
        <input type="hidden" name="message" id="modalPackageName" value="Cruise Modal Enquiry">

        <!-- Full Name * -->
        <div class="cruise-form-group">
          <label class="cruise-form-label">Full Name <span class="req-star">*</span></label>
          <input type="text" name="name" class="cruise-input" placeholder="Full Name" required>
        </div>

        <!-- Mobile Number * & Email Address * -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="cruise-form-group">
            <label class="cruise-form-label">Mobile Number <span class="req-star">*</span></label>
            <input type="tel" name="phone" class="cruise-input" placeholder="Mobile Number" required>
          </div>
          <div class="cruise-form-group">
            <label class="cruise-form-label">Email Address <span class="req-star">*</span></label>
            <input type="email" name="email" class="cruise-input" placeholder="Email Address" required>
          </div>
        </div>

        <!-- Cruise Destination * -->
        <div class="cruise-form-group">
          <label class="cruise-form-label">Cruise Destination <span class="req-star">*</span></label>
          <select name="destination" class="cruise-select" required>
            <option value="" disabled selected>Select Destination</option>
            <option value="Singapore & Malaysia Cruise">Singapore & Malaysia Cruise</option>
            <option value="Bahamas & Caribbean Cruise">Bahamas & Caribbean Cruise</option>
            <option value="Mediterranean & Greek Isles">Mediterranean & Greek Isles</option>
            <option value="Alaska Glaciers Voyage">Alaska Glaciers Voyage</option>
            <option value="Antarctica Expedition Voyage">Antarctica Expedition Voyage</option>
            <option value="Dubai & Arabian Gulf Cruise">Dubai & Arabian Gulf Cruise</option>
            <option value="Europe River Cruise">Europe River Cruise</option>
          </select>
        </div>

        <!-- Travel Date * & No. of Travelers * -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="cruise-form-group">
            <label class="cruise-form-label">Travel Date <span class="req-star">*</span></label>
            <input type="date" name="travel_date" class="cruise-input" required value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="cruise-form-group">
            <label class="cruise-form-label">No. of Travelers <span class="req-star">*</span></label>
            <input type="number" name="travelers" class="cruise-input" placeholder="No. of Travelers" min="1" value="1" required>
          </div>
        </div>

        <!-- Cabin Type * -->
        <div class="cruise-form-group">
          <label class="cruise-form-label">Cabin Type <span class="req-star">*</span></label>
          <div class="cabin-type-grid">
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Interior Cabin" checked> Interior Cabin
            </label>
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Ocean View Cabin"> Ocean View
            </label>
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Balcony Cabin"> Balcony Cabin
            </label>
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Suite Cabin"> Suite Cabin
            </label>
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Family Cabin"> Family Cabin
            </label>
            <label class="cabin-radio-pill">
              <input type="radio" name="cabin_type" value="Luxury Suite"> Luxury Suite
            </label>
            <label class="cabin-radio-pill full-width">
              <input type="radio" name="cabin_type" value="No Preference"> No Preference
            </label>
          </div>
        </div>

        <!-- Budget -->
        <div class="cruise-form-group">
          <label class="cruise-form-label">Budget</label>
          <select name="budget" class="cruise-select">
            <option value="" disabled selected>Select Estimated Budget</option>
            <option value="Under ₹50,000 / person">Under ₹50,000 / person</option>
            <option value="₹50,000 - ₹1,00,000 / person">₹50,000 - ₹1,00,000 / person</option>
            <option value="₹1,00,000 - ₹2,50,000 / person">₹1,00,000 - ₹2,50,000 / person</option>
            <option value="₹2,50,000+ Luxury / Suite">₹2,50,000+ Luxury / Suite</option>
          </select>
        </div>

        <!-- Consent ☐ I agree to be contacted regarding my enquiry. -->
        <div class="consent-checkbox-group">
          <input type="checkbox" name="consent" id="consent_check_modal_cruise" checked required>
          <label for="consent_check_modal_cruise" class="consent-label">
            I agree to be contacted regarding my enquiry.
          </label>
        </div>

        <button type="submit" class="btn-send-enquiry">CONFIRM CRUISE ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- Cruise Pages Script -->
  <script src="<?php echo base_url('assets/js/pages_main.js'); ?>"></script>

  <script>
    function scrollToElement(id) {
      const el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: 'smooth' });
    }
  </script>
