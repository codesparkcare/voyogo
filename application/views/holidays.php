<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!-- HERO BANNER SECTION WITH IMAGE SLIDER & ENQUIRY FORM -->
  <section class="hero-section">
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
        <div class="hero-form-wrapper glass-hero-wrapper">
          <div class="voyogo-form-card wide-form-card voyogo-glass-card">
            <h3 class="voyogo-form-title">Plan your trip with world-class tour experts!</h3>

            <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" class="voyogo-pill-form">
              <input type="hidden" name="message" value="Hero Enquiry Form">

              <!-- Row 1: Name & Contact Number -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="text" name="name" class="pill-input" placeholder="Name *" required>
                </div>
                <div class="pill-form-group phone-group">
                  <div class="country-code-pill">
                    <span class="flag-icon">🇮🇳</span>
                    <span class="code-text">+91</span>
                  </div>
                  <input type="tel" name="phone" class="pill-input phone-input" placeholder="Contact Number *" required>
                </div>
              </div>

              <!-- Row 2: Email & Travel Destination -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="email" name="email" class="pill-input" placeholder="Email Address *" required>
                </div>
                <div class="pill-form-group">
                  <input type="text" name="destination" class="pill-input" placeholder="Travel Destination *" required>
                </div>
              </div>

              <!-- Row 3: Date of Travel & No. of People -->
              <div class="form-row-2col">
                <div class="pill-form-group">
                  <input type="text" name="travel_date" class="pill-input" placeholder="Date of Travel *"
                    onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
                </div>
                <div class="pill-form-group">
                  <input type="number" name="passengers" class="pill-input" placeholder="No. of People *" min="1" required>
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="btn-send-enquiry">SEND ENQUIRY</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- EXCLUSIVE DEALS SECTION (MATCHING IMAGE 2 EXACTLY) -->
  <section class="exclusive-deals-section" style="padding-top: 40px;">
    <div class="container">

      <!-- Section Header Row with Category Tabs & Nav Arrows -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">Exclusive Deals</h2>

          <div class="deals-tabs">
            <span class="deal-tab active">HOT DEALS</span>
            <span class="deal-tab">FIXED DEPARTURES</span>
            <span class="deal-tab">GROUP TOUR</span>
            <span class="deal-tab">HONEY MOON</span>
            <span class="deal-tab">SIGNATURE</span>
          </div>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevCarouselBtn" aria-label="Previous Deal">‹</button>
          <button class="carousel-btn active" id="nextCarouselBtn" aria-label="Next Deal">›</button>
          <a href="#" onclick="openEnquiryModal('View All Deals')" class="view-all-link">VIEW ALL</a>
        </div>
      </div>

      <!-- Deals Carousel / Grid (Shuffled Hot Deals from All Tabs) -->
      <div class="deals-grid-container">
        <div class="deals-grid" id="dealsGrid">

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo dubai.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Dubai Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Dubai</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo bali .png'); ?>');"
            onclick="openEnquiryModal('Voyogo Bali Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Bali</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo swiss paris.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Swiss Paris Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Swiss Paris</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo canada with alaska.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Canada with Alaska Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Canada with Alaska</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo japan.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Japan Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Japan</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo thailand.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Thailand Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Thailand</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo mauritius .png'); ?>');"
            onclick="openEnquiryModal('Voyogo Mauritius Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Mauritius</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo europe.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Europe Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Europe</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo kenya.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Kenya Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Kenya</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo langkawi .png'); ?>');"
            onclick="openEnquiryModal('Voyogo Langkawi Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Langkawi</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo vietnom.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Vietnam Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Vietnam</div>
            </div>
          </div>

          <div class="deal-card custom-image-card"
            style="background-image: url('<?php echo base_url('assets/images/voyogo scandinavia.png'); ?>');"
            onclick="openEnquiryModal('Voyogo Scandinavia Hot Deal')">
            <div class="custom-card-overlay">
              <div class="custom-card-title">Scandinavia</div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- TRENDING DESTINATIONS SECTION -->
  <section class="trending-destinations-section" style="padding: 10px 0 40px 0;">
    <div class="container">

      <!-- Section Header Row with Sub-Tabs & Controls -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">Trending Destinations</h2>

          <div class="trending-tabs">
            <span class="trending-tab active">INTERNATIONAL</span>
            <span class="trending-tab">DOMESTIC</span>
          </div>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevTrendingBtn" aria-label="Previous Destination">‹</button>
          <button class="carousel-btn active" id="nextTrendingBtn" aria-label="Next Destination">›</button>
          <a href="#" onclick="openEnquiryModal('View All Trending Destinations')" class="view-all-link">VIEW ALL</a>
        </div>
      </div>

      <!-- Trending Destinations Grid Container -->
      <div class="trending-grid-container">
        <div class="trending-grid" id="trendingGrid">

          <!-- 1. Maldives -->
          <div class="trending-card" onclick="openEnquiryModal('Maldives Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo maladives.png'); ?>');">
              <div class="trending-title-overlay">Maldives</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹80,000</span>
            </div>
          </div>

          <!-- 2. Singapore -->
          <div class="trending-card" onclick="openEnquiryModal('Singapore Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo singapore.png'); ?>');">
              <div class="trending-title-overlay">Singapore</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹55,000</span>
            </div>
          </div>

          <!-- 3. Malaysia -->
          <div class="trending-card" onclick="openEnquiryModal('Malaysia Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo Singapore &malaysia.png'); ?>');">
              <div class="trending-title-overlay">Malaysia</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹23,000</span>
            </div>
          </div>

          <!-- 4. Azerbaijan -->
          <div class="trending-card" onclick="openEnquiryModal('Azerbaijan Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo Azerbaijan.png'); ?>');">
              <div class="trending-title-overlay">Azerbaijan</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹45,000</span>
            </div>
          </div>

          <!-- 5. Thailand -->
          <div class="trending-card" onclick="openEnquiryModal('Thailand Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo thailand.png'); ?>');">
              <div class="trending-title-overlay">Thailand</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹29,000</span>
            </div>
          </div>

          <!-- 6. Hong Kong -->
          <div class="trending-card" onclick="openEnquiryModal('Hong Kong Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo hong kong.png'); ?>');">
              <div class="trending-title-overlay">Hong Kong</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹90,000</span>
            </div>
          </div>

          <!-- 7. Phu Quoc -->
          <div class="trending-card" onclick="openEnquiryModal('Phu Quoc Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo phu quoc.png'); ?>');">
              <div class="trending-title-overlay">Phu Quoc</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹42,000</span>
            </div>
          </div>

          <!-- 8. Almaty -->
          <div class="trending-card" onclick="openEnquiryModal('Almaty Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo almaty.png'); ?>');">
              <div class="trending-title-overlay">Almaty</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹47,000</span>
            </div>
          </div>

          <!-- 9. Georgia -->
          <div class="trending-card" onclick="openEnquiryModal('Georgia Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo europe.png'); ?>');">
              <div class="trending-title-overlay">Georgia</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹47,000</span>
            </div>
          </div>

          <!-- 10. Langkawi -->
          <div class="trending-card" onclick="openEnquiryModal('Langkawi Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo langkawi.png'); ?>');">
              <div class="trending-title-overlay">Langkawi</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹35,000</span>
            </div>
          </div>

          <!-- 11. Bali -->
          <div class="trending-card" onclick="openEnquiryModal('Bali Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo bali.png'); ?>');">
              <div class="trending-title-overlay">Bali</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹30,000</span>
            </div>
          </div>

          <!-- 12. China -->
          <div class="trending-card" onclick="openEnquiryModal('China Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo china.png'); ?>');">
              <div class="trending-title-overlay">China</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹80,000</span>
            </div>
          </div>

          <!-- 13. Dubai -->
          <div class="trending-card" onclick="openEnquiryModal('Dubai Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo dubai.png'); ?>');">
              <div class="trending-title-overlay">Dubai</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹50,000</span>
            </div>
          </div>

          <!-- 14. Sri Lanka -->
          <div class="trending-card" onclick="openEnquiryModal('Sri Lanka Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo srilanka.png'); ?>');">
              <div class="trending-title-overlay">Sri Lanka</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹27,000</span>
            </div>
          </div>

          <!-- 15. Vietnam -->
          <div class="trending-card" onclick="openEnquiryModal('Vietnam Trending Package')">
            <div class="trending-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo vietnom.png'); ?>');">
              <div class="trending-title-overlay">Vietnam</div>
            </div>
            <div class="trending-card-footer">
              Starting @ <span>₹37,000</span>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- PICK YOUR THEME SECTION (BEFORE INTERNATIONAL DESTINATION) -->
  <section class="pick-theme-section" style="padding: 10px 0 30px 0;">
    <div class="container">

      <!-- Section Header Row -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">Pick Your Theme</h2>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevThemeBtn" aria-label="Previous Theme">‹</button>
          <button class="carousel-btn active" id="nextThemeBtn" aria-label="Next Theme">›</button>
        </div>
      </div>

      <!-- Theme Slider Grid Track -->
      <div class="theme-grid-container">
        <div class="theme-grid" id="themeGrid">

          <!-- Theme 1: Adventure -->
          <div class="theme-item" onclick="openEnquiryModal('Adventure Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a8 8 0 0 0-8 8c0 5.25 7 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z" />
                <path d="M12 22s-4-6.5-4-12a4 4 0 0 1 8 0c0 5.5-4 12-4 12z" />
                <line x1="4" y1="10" x2="20" y2="10" />
              </svg>
            </div>
            <span class="theme-label">Adventure</span>
          </div>

          <!-- Theme 2: All Inclusive -->
          <div class="theme-item" onclick="openEnquiryModal('All Inclusive Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M7 8h10" />
                <path d="M7 12h10" />
                <path d="M7 16h6" />
              </svg>
            </div>
            <span class="theme-label">All Inclusive</span>
          </div>

          <!-- Theme 3: Beach -->
          <div class="theme-item" onclick="openEnquiryModal('Beach Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v10" />
                <path d="M12 12a10 10 0 0 0 10 10H2A10 10 0 0 0 12 12z" />
                <path d="M12 12a5 5 0 0 1 5-5" />
                <circle cx="19" cy="5" r="2" />
              </svg>
            </div>
            <span class="theme-label">Beach</span>
          </div>

          <!-- Theme 4: Family with Kids -->
          <div class="theme-item" onclick="openEnquiryModal('Family Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
            </div>
            <span class="theme-label">Family with Kids</span>
          </div>

          <!-- Theme 5: Food -->
          <div class="theme-item" onclick="openEnquiryModal('Food Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a10 10 0 0 0-10 10h20a10 10 0 0 0-10-10z" />
                <path d="M2 16h20" />
                <path d="M12 2v2" />
              </svg>
            </div>
            <span class="theme-label">Food</span>
          </div>

          <!-- Theme 6: Honeymoon -->
          <div class="theme-item" onclick="openEnquiryModal('Honeymoon Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.72-8.72 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
              </svg>
            </div>
            <span class="theme-label">Honeymoon</span>
          </div>

          <!-- Theme 7: Luxury -->
          <div class="theme-item" onclick="openEnquiryModal('Luxury Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 3h12l4 6-10 12L2 9z" />
                <path d="M11 3 8 9l3 12" />
                <path d="M13 3l3 9-3 12" />
              </svg>
            </div>
            <span class="theme-label">Luxury</span>
          </div>

          <!-- Theme 8: Multi-Country -->
          <div class="theme-item" onclick="openEnquiryModal('Multi-Country Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="2" y1="12" x2="22" y2="12" />
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
              </svg>
            </div>
            <span class="theme-label">Multi-Country</span>
          </div>

          <!-- Theme 9: Shopping -->
          <div class="theme-item" onclick="openEnquiryModal('Shopping Theme Packages')">
            <div class="theme-icon-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
              </svg>
            </div>
            <span class="theme-label">Shopping</span>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- INTERNATIONAL DESTINATION SECTION (NEWLY ADDED MATCHING SCREENSHOT) -->
  <section class="intl-destination-section" style="padding: 10px 0 40px 0;">
    <div class="container">

      <!-- Section Header Row -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">International Destination</h2>

          <div class="intl-tabs">
            <span class="intl-tab active">Asia</span>
            <span class="intl-tab">Middle East</span>
            <span class="intl-tab">Africa</span>
            <span class="intl-tab">Oceania</span>
            <span class="intl-tab">Europe</span>
            <span class="intl-tab">Americas</span>
          </div>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevIntlBtn" aria-label="Previous International Package">‹</button>
          <button class="carousel-btn active" id="nextIntlBtn" aria-label="Next International Package">›</button>
          <a href="#" onclick="openEnquiryModal('View All International Packages')" class="view-all-link">VIEW ALL</a>
        </div>
      </div>

      <!-- International Packages Slider Track -->
      <div class="package-grid-container">
        <div class="package-grid" id="intlGrid">

          <!-- 1. Bali -->
          <div class="package-card" onclick="openEnquiryModal('Bali Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo bali.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Bali Tropical Escape</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Kuta (3) → Ubud (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹74,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 2. China -->
          <div class="package-card" onclick="openEnquiryModal('China Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo china.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">China Imperial & Wonders</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Beijing (4) → Shanghai (4)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹2,05,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 3. Dubai -->
          <div class="package-card" onclick="openEnquiryModal('Dubai Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo dubai.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Dubai Luxury Getaway</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Dubai (3) → Desert Resort (1)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹99,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 4. Sri Lanka -->
          <div class="package-card" onclick="openEnquiryModal('Sri Lanka Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo srilanka.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Sri Lanka Island Discovery</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Colombo (2) → Kandy (2) → Bentota (1)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹57,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 5. Vietnam -->
          <div class="package-card" onclick="openEnquiryModal('Vietnam Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo vietnom.png'); ?>');">
              <span class="pkg-duration-badge">6D & 5N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Vietnam Heritage Tour</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Hanoi (2) → Halong Bay (1) → Danang (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹1,18,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 6. Singapore & Malaysia -->
          <div class="package-card" onclick="openEnquiryModal('Singapore & Malaysia Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo Singapore &malaysia.png'); ?>');">
              <span class="pkg-duration-badge">6D & 5N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Singapore & Malaysia Combo</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Singapore (3) → Kuala Lumpur (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹1,13,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 7. Vietnam & Cambodia -->
          <div class="package-card" onclick="openEnquiryModal('Vietnam & Cambodia Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo vietnom & Combodia.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Vietnam & Cambodia Expedition</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Hanoi (3) → Siem Reap (4)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹1,49,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 8. Japan -->
          <div class="package-card" onclick="openEnquiryModal('Japan Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo japan.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Japan Cherry Blossom Wonders</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Tokyo (4) → Kyoto (3)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹2,86,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 9. China Avatar Wonders -->
          <div class="package-card" onclick="openEnquiryModal('China Avatar Wonders Tour')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo china.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">China Avatar & Great Wall</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Beijing (4) → Zhangjiajie (3)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹2,05,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 10. Egypt -->
          <div class="package-card" onclick="openEnquiryModal('Egypt Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo egypt.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Egypt Pyramids & Nile Cruise</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Cairo (3) → Aswan (4)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹1,89,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 11. USA -->
          <div class="package-card" onclick="openEnquiryModal('USA Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo usa.png'); ?>');">
              <span class="pkg-duration-badge">21D & 20N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">USA Coast to Coast Wonders</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>New York (7) → Orlando (6) → LA (7)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹7,59,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 12. Scandinavia -->
          <div class="package-card" onclick="openEnquiryModal('Scandinavia Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo scandinavia.png'); ?>');">
              <span class="pkg-duration-badge">10D & 9N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Scandinavia Aurora & Fjords</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Oslo (4) → Bergen (5)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹3,84,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 13. Kenya -->
          <div class="package-card" onclick="openEnquiryModal('Kenya Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo kenya.png'); ?>');">
              <span class="pkg-duration-badge">6D & 5N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Kenya Wildlife Safari</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Nairobi (2) → Masai Mara (3)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹2,59,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 14. Sri Lanka Honeymoon -->
          <div class="package-card" onclick="openEnquiryModal('Sri Lanka Special Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo srilanka.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Sri Lanka Beach & Hills</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Nuwara Eliya (2) → Galle (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹57,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 15. Europe -->
          <div class="package-card" onclick="openEnquiryModal('Europe Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo europe.png'); ?>');">
              <span class="pkg-duration-badge">10D & 9N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Grand Europe Highlights</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Paris (3) → Swiss (3) → Rome (3)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹2,79,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 16. Canada with Alaska -->
          <div class="package-card" onclick="openEnquiryModal('Canada with Alaska Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo canada with alaska.png'); ?>');">
              <span class="pkg-duration-badge">15D & 14N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Canada with Alaska Cruise</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Vancouver (5) → Alaska Cruise (9)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹7,79,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- 17. Russia -->
          <div class="package-card" onclick="openEnquiryModal('Russia Tour Package')">
            <div class="pkg-card-img" style="background-image: url('<?php echo base_url('assets/images/voyogo russia.png'); ?>');">
              <span class="pkg-duration-badge">8D & 7N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Russia Imperial Odyssey</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Moscow (4) → St. Petersburg (3)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹1,45,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- DOMESTIC DESTINATION SECTION -->
  <section class="domestic-destination-section" style="padding: 10px 0 40px 0;">
    <div class="container">

      <!-- Section Header Row -->
      <div class="section-header-wrapper">
        <div class="header-title-tabs">
          <h2 class="section-title">Domestic Destination</h2>

          <div class="dom-tabs">
            <span class="dom-tab active">East India</span>
            <span class="dom-tab">North India</span>
            <span class="dom-tab">South India</span>
            <span class="dom-tab">Central India</span>
            <span class="dom-tab">West India</span>
          </div>
        </div>

        <div class="header-actions-right">
          <button class="carousel-btn" id="prevDomBtn" aria-label="Previous Domestic Package">‹</button>
          <button class="carousel-btn active" id="nextDomBtn" aria-label="Next Domestic Package">›</button>
          <a href="#" onclick="openEnquiryModal('View All Domestic Packages')" class="view-all-link">VIEW ALL</a>
        </div>
      </div>

      <!-- Domestic Packages Slider Track -->
      <div class="package-grid-container">
        <div class="package-grid" id="domGrid">

          <!-- Card 1: Andaman -->
          <div class="package-card" onclick="openEnquiryModal('Andaman Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo andaman.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Andaman Tour Package</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Port Blair (2) → Havelock (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹25,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 2: Goa -->
          <div class="package-card" onclick="openEnquiryModal('Goa Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo goa.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Goa Beach Retreat</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>North Goa (2) → South Goa (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹17,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 3: Shimla & Manali -->
          <div class="package-card" onclick="openEnquiryModal('Shimla & Manali Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo simla & manali.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Shimla & Manali Escape</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Shimla (2) → Manali (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹17,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 4: Rajasthan -->
          <div class="package-card" onclick="openEnquiryModal('Rajasthan Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo rajasthan.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Royal Rajasthan Heritage</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Jaipur (2) → Udaipur (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹20,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 5: Golden Triangle -->
          <div class="package-card" onclick="openEnquiryModal('Golden Triangle Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo golden triangle.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Golden Triangle Tour</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Delhi (2) → Agra (1) → Jaipur (1)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹17,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 6: Kashmir -->
          <div class="package-card" onclick="openEnquiryModal('Kashmir Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo kashmir.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Kashmir Paradise Tour</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Srinagar (2) → Gulmarg (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹22,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 7: Darjeeling -->
          <div class="package-card" onclick="openEnquiryModal('Darjeeling Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo dorjeeing.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Simply Darjeeling</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Darjeeling (4)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹22,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 8: Meghalaya -->
          <div class="package-card" onclick="openEnquiryModal('Meghalaya Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo meghalaya.png'); ?>');">
              <span class="pkg-duration-badge">5D & 4N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Meghalaya Explorer</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Shillong (3) → Cherrapunji (1)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹22,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

          <!-- Card 9: Bhutan -->
          <div class="package-card" onclick="openEnquiryModal('Bhutan Domestic Package')">
            <div class="pkg-card-img"
              style="background-image: url('<?php echo base_url('assets/images/voyogo bhutan.png'); ?>');">
              <span class="pkg-duration-badge">6D & 5N</span>
            </div>
            <div class="pkg-card-body">
              <h3 class="pkg-title">Bhutan Cultural Journey</h3>
              <div class="pkg-route">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>Paro (3) → Thimphu (2)</span>
              </div>
            </div>
            <div class="pkg-price-strip">
              <div class="pkg-price-box">
                <span class="pkg-old-price">Starting @</span>
                <span class="pkg-new-price">₹22,000/-</span>
              </div>
              <button class="btn-view-details">View Details</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- WHY CHOOSE US SECTION (COMPACT & PROFESSIONAL HORIZONTAL DESIGN) -->
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
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Best Price Guarantee</h3>
            <p class="why-card-sub">Transparent pricing with zero hidden charges & best deal match.</p>
          </div>
        </div>

        <!-- Feature 2: Customized Itineraries -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" y1="13" x2="8" y2="13" />
              <line x1="16" y1="17" x2="8" y2="17" />
              <line x1="10" y1="9" x2="8" y2="9" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Tailor-Made Packages</h3>
            <p class="why-card-sub">Handcrafted itineraries curated by expert travel specialists.</p>
          </div>
        </div>

        <!-- Feature 3: 24/7 Expert Support -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">24/7 Dedicated Support</h3>
            <p class="why-card-sub">Round-the-clock trip assistance from departure to return.</p>
          </div>
        </div>

        <!-- Feature 4: Fast Visa Assistance -->
        <div class="why-card">
          <div class="why-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
              <polyline points="9 12 11 14 15 10" />
            </svg>
          </div>
          <div class="why-text">
            <h3 class="why-card-title">Verified Visa Assistance</h3>
            <p class="why-card-sub">Quick, hassle-free visa processing with 99.8% approval rate.</p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- DOWNLOAD OUR APP BANNER SECTION (AFTER WHY CHOOSE US) -->
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
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Instant Booking & Voucher Access</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>24/7 Live Agent Support</span>
            </div>
            <div class="app-feature-item">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              <span>Exclusive App-Only Offers & Rewards</span>
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
      <h3 class="voyogo-form-title">Plan your trip with world-class tour experts!</h3>

      <form action="<?php echo site_url('welcome/save_enquiry'); ?>" method="POST" class="voyogo-pill-form">
        <input type="hidden" name="message" id="modalPackageName" value="General Enquiry">

        <div class="pill-form-group">
          <input type="text" name="name" class="pill-input" placeholder="Name" required>
        </div>

        <div class="pill-form-group phone-group">
          <div class="country-code-pill">
            <span class="flag-icon">🇮🇳</span>
            <span class="code-text">+91</span>
          </div>
          <input type="tel" name="phone" class="pill-input phone-input" placeholder="Contact Number" required>
        </div>

        <div class="pill-form-group">
          <input type="email" name="email" class="pill-input" placeholder="Email" required>
        </div>

        <div class="pill-form-group">
          <input type="text" name="destination" class="pill-input" placeholder="Travel Destination" required>
        </div>

        <div class="pill-form-group">
          <input type="text" name="travel_date" class="pill-input" placeholder="Date of Travel"
            onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" required>
        </div>

        <div class="pill-form-group">
          <input type="number" name="passengers" class="pill-input" placeholder="No. of People" min="1" required>
        </div>

        <button type="submit" class="btn-send-enquiry">SEND ENQUIRY</button>
      </form>
    </div>
  </div>

  <!-- Holiday Pages Script -->
  <script src="<?php echo base_url('assets/js/pages_main.js'); ?>"></script>