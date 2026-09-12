/**
 * Voyogo Holidays & Services - Main Interactivity Script
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ==========================================
     1. HERO SLIDER LOGIC (AUTOMATIC HORIZONTAL SCROLL, ZERO OPACITY)
     ========================================== */
  const heroSliders = document.querySelectorAll('.hero-slider, .ref-hero-slider');
  heroSliders.forEach(heroSlider => {
    // Ensure track exists
    let track = heroSlider.querySelector('.slider-track');
    if (!track) {
      track = document.createElement('div');
      track.className = 'slider-track';
      const existingSlides = Array.from(heroSlider.querySelectorAll(':scope > .slide'));
      existingSlides.forEach(s => track.appendChild(s));
      heroSlider.appendChild(track);
    }

    const origSlides = Array.from(track.querySelectorAll('.slide:not(.clone)'));
    const totalSlides = origSlides.length;
    if (totalSlides === 0) return;

    // Clone first slide to the end for seamless continuous forward scroll
    if (totalSlides > 1 && !track.querySelector('.slide.clone')) {
      const cloneFirst = origSlides[0].cloneNode(true);
      cloneFirst.classList.add('clone');
      cloneFirst.classList.remove('active');
      track.appendChild(cloneFirst);
    }

    const heroSection = heroSlider.closest('.hero-section, .ref-hero-section') || document.body;
    const dots = heroSection.querySelectorAll('.slider-dots .dot, .ref-slider-dots .dot');
    const prevBtn = heroSection.querySelector('.slider-arrow.prev, .ref-slider-arrow.prev');
    const nextBtn = heroSection.querySelector('.slider-arrow.next, .ref-slider-arrow.next');

    // Detect page context from first slide image URL or DOM
    let pageContext = 'holidays';
    const firstSlideStyle = origSlides[0] ? (origSlides[0].getAttribute('style') || '') : '';
    if (firstSlideStyle.includes('/cab/')) {
      pageContext = 'cabs';
    } else if (firstSlideStyle.includes('/crusie/')) {
      pageContext = 'cruises';
    } else if (firstSlideStyle.includes('/forex/')) {
      pageContext = 'forex';
    } else if (firstSlideStyle.includes('/visa/')) {
      pageContext = 'visa';
    } else if (firstSlideStyle.includes('/holidays/')) {
      pageContext = 'holidays';
    }

    // Dynamic Slide Content Data per page - Each slide has unique content & 4 unique pills
    const sliderContentMap = {
      holidays: [
        {
          title: 'Bali',
          subtitle: 'Island of Endless Wonders & Tropical Bliss',
          pills: [
            { icon: 'fa-umbrella-beach', text: 'Breathtaking Beaches' },
            { icon: 'fa-landmark-dome', text: 'Rich Culture' },
            { icon: 'fa-mountain-sun', text: 'Unforgettable Experiences' },
            { icon: 'fa-heart', text: 'Perfect for Every Traveller' }
          ],
          cursive: 'Bali<br>Calling<br><i class="fa-solid fa-plane"></i>'
        },
        {
          title: 'China',
          subtitle: 'Ancient Dynasties, Historic Wall & Modern Wonders',
          pills: [
            { icon: 'fa-archway', text: 'Great Wall of China' },
            { icon: 'fa-monument', text: 'Forbidden City' },
            { icon: 'fa-train-subway', text: 'Bullet Train Tours' },
            { icon: 'fa-utensils', text: 'Authentic Cuisine' }
          ],
          cursive: 'Discover<br>China<br><i class="fa-solid fa-dragon"></i>'
        },
        {
          title: 'Japan',
          subtitle: 'Cherry Blossoms, Timeless Shrines & Mt. Fuji',
          pills: [
            { icon: 'fa-mountain', text: 'Mount Fuji Views' },
            { icon: 'fa-torii-gate', text: 'Historic Kyoto Shrines' },
            { icon: 'fa-city', text: 'Futuristic Tokyo' },
            { icon: 'fa-bowl-food', text: 'World-Class Gastronomy' }
          ],
          cursive: 'Experience<br>Japan<br><i class="fa-solid fa-fan"></i>'
        },
        {
          title: 'Scandinavia',
          subtitle: 'Majestic Deep Fjords & Celestial Northern Lights',
          pills: [
            { icon: 'fa-snowflake', text: 'Aurora Borealis' },
            { icon: 'fa-water', text: 'Scenic Fjord Cruises' },
            { icon: 'fa-tree', text: 'Pristine Wilderness' },
            { icon: 'fa-cable-car', text: 'Arctic Cableways' }
          ],
          cursive: 'Nordic<br>Magic<br><i class="fa-solid fa-icicles"></i>'
        },
        {
          title: 'Singapore & Malaysia',
          subtitle: 'Futuristic Skylines & Exotic Tropical Rainforests',
          pills: [
            { icon: 'fa-building', text: 'Marina Bay Sands' },
            { icon: 'fa-tree-city', text: 'Gardens by the Bay' },
            { icon: 'fa-tower-observation', text: 'Petronas Twin Towers' },
            { icon: 'fa-bag-shopping', text: 'Duty-Free Shopping' }
          ],
          cursive: 'Twin City<br>Vibes<br><i class="fa-solid fa-gem"></i>'
        },
        {
          title: 'Vietnam & Cambodia',
          subtitle: 'Emerald Halong Bay & Sacred Angkor Wat Temples',
          pills: [
            { icon: 'fa-ship', text: 'Halong Bay Cruises' },
            { icon: 'fa-gopuram', text: 'Angkor Wat Sunrise' },
            { icon: 'fa-bowl-rice', text: 'Street Food Culture' },
            { icon: 'fa-camera', text: 'Mekong Delta Tours' }
          ],
          cursive: 'Ancient<br>Kingdoms<br><i class="fa-solid fa-compass"></i>'
        }
      ],
      cabs: [
        {
          title: 'Travel<br>Your Way',
          subtitle: 'Safe Rides. Happy Journeys.',
          pills: [
            { icon: 'fa-shield-halved', text: 'Reliable & Safe' },
            { icon: 'fa-indian-rupee-sign', text: 'Affordable Rates' },
            { icon: 'fa-user-tie', text: 'Professional Drivers' },
            { icon: 'fa-clock', text: 'On-Time Service' }
          ],
          cursive: 'Local<br>Outstation<br>Airport<br>Anytime<br><i class="fa-solid fa-taxi"></i>'
        },
        {
          title: 'Outstation Highway Trips',
          subtitle: 'Comfortable Intercity Travel in Premium Sedans & SUVs',
          pills: [
            { icon: 'fa-route', text: 'Intercity Routes' },
            { icon: 'fa-car-side', text: 'Premium Sedans & SUVs' },
            { icon: 'fa-gas-pump', text: 'All Tolls & Fuel Included' },
            { icon: 'fa-headset', text: 'Dedicated Trip Support' }
          ],
          cursive: 'Outstation<br>Road Trips<br><i class="fa-solid fa-road"></i>'
        },
        {
          title: 'Hourly Chauffeur Rentals',
          subtitle: 'Flexible Multi-Stop Bookings with Unlimited Kilometers',
          pills: [
            { icon: 'fa-hourglass-half', text: 'Flexible 4h, 8h & 12h' },
            { icon: 'fa-briefcase', text: 'Corporate Business' },
            { icon: 'fa-gem', text: 'Sanitized Luxury Cars' },
            { icon: 'fa-star', text: '5-Star Rated Chauffeurs' }
          ],
          cursive: 'Hourly<br>Rentals<br><i class="fa-solid fa-car"></i>'
        }
      ],
      cruises: [
        {
          title: 'Luxury Ocean Cruising',
          subtitle: 'Sail Across Crystal Waters in 5-Star Grandeur',
          pills: [
            { icon: 'fa-compass', text: 'Global Itineraries' },
            { icon: 'fa-utensils', text: 'Gourmet Dining' },
            { icon: 'fa-bed', text: 'Ocean-View Balconies' },
            { icon: 'fa-champagne-glasses', text: 'All-Inclusive Luxury' }
          ],
          cursive: 'Sail<br>Explore<br>Repeat<br><i class="fa-solid fa-ship"></i>'
        },
        {
          title: 'Private Island Getaways',
          subtitle: 'Pristine Turquoise Lagoons & Exclusive Beach Resorts',
          pills: [
            { icon: 'fa-umbrella-beach', text: 'Private Island Stops' },
            { icon: 'fa-person-swimming', text: 'Snorkeling & Reefs' },
            { icon: 'fa-music', text: 'Broadway Shows' },
            { icon: 'fa-spa', text: 'Onboard Day Spa' }
          ],
          cursive: 'Island<br>Paradise<br><i class="fa-solid fa-water"></i>'
        },
        {
          title: 'Scenic European Rivers',
          subtitle: 'Danube & Rhine Boutique River Cruising',
          pills: [
            { icon: 'fa-landmark', text: 'Historic Castles' },
            { icon: 'fa-wine-glass', text: 'Regional Wine Tastings' },
            { icon: 'fa-bicycle', text: 'Guided Shore Tours' },
            { icon: 'fa-sun', text: 'Intimate Boutique Ships' }
          ],
          cursive: 'River<br>Wonders<br><i class="fa-solid fa-sailboat"></i>'
        },
        {
          title: 'Mega Liner Adventures',
          subtitle: 'Waterparks, Theatres & World-Class Entertainment',
          pills: [
            { icon: 'fa-water-ladder', text: 'Aqua Parks & Slides' },
            { icon: 'fa-children', text: 'Kids & Family Clubs' },
            { icon: 'fa-masks-theater', text: 'Live Theatrical Shows' },
            { icon: 'fa-star', text: '24/7 Butler Service' }
          ],
          cursive: 'Ocean<br>Adventures<br><i class="fa-solid fa-anchor"></i>'
        }
      ],
      forex: [
        {
          title: 'Zero Markup Forex Card',
          subtitle: 'Smart Multi-Currency Travel Cards with Best Live Rates',
          pills: [
            { icon: 'fa-credit-card', text: 'Multi-Currency Card' },
            { icon: 'fa-percent', text: 'Zero Foreign Markup' },
            { icon: 'fa-shield-halved', text: 'Chip & PIN Secure' },
            { icon: 'fa-bolt', text: 'Instant App Reloads' }
          ],
          cursive: 'Smart<br>Forex<br><i class="fa-solid fa-credit-card"></i>'
        },
        {
          title: 'Doorstep Currency Delivery',
          subtitle: 'Fresh Cash Currency Delivered Directly to Your Door',
          pills: [
            { icon: 'fa-money-bill-wave', text: '40+ Global Currencies' },
            { icon: 'fa-truck-fast', text: 'Same-Day Doorstep' },
            { icon: 'fa-certificate', text: '100% Genuine Notes' },
            { icon: 'fa-receipt', text: 'Best Rate Guarantee' }
          ],
          cursive: 'Fast<br>Cash<br><i class="fa-solid fa-money-bill"></i>'
        },
        {
          title: 'Fast Overseas Wire Transfer',
          subtitle: 'Send Money Abroad for University Fees & Living Expenses',
          pills: [
            { icon: 'fa-graduation-cap', text: 'University Fee Wire' },
            { icon: 'fa-clock', text: 'Swift & Safe Transfers' },
            { icon: 'fa-building-columns', text: 'RBI Authorized' },
            { icon: 'fa-file-invoice-dollar', text: 'Lowest Transfer Cost' }
          ],
          cursive: 'Global<br>Remit<br><i class="fa-solid fa-paper-plane"></i>'
        },
        {
          title: 'Live Currency Exchange',
          subtitle: 'Lock In Transparent Real-Time Forex Rates Instantly',
          pills: [
            { icon: 'fa-chart-line', text: 'Live Market Rates' },
            { icon: 'fa-lock', text: 'Rate Freeze Option' },
            { icon: 'fa-hand-holding-dollar', text: 'Easy Buyback on Return' },
            { icon: 'fa-headset', text: '24/7 Dedicated Support' }
          ],
          cursive: 'Live<br>Rates<br><i class="fa-solid fa-arrow-trend-up"></i>'
        }
      ],
      visa: [
        {
          title: 'Schengen & Europe Visa',
          subtitle: 'Travel Across 27 European Countries with One Visa',
          pills: [
            { icon: 'fa-passport', text: 'Schengen Express' },
            { icon: 'fa-file-circle-check', text: 'Full Document Audit' },
            { icon: 'fa-calendar-check', text: 'Priority Appointments' },
            { icon: 'fa-shield-halved', text: '99.4% Approval Rate' }
          ],
          cursive: 'Europe<br>Express<br><i class="fa-solid fa-plane"></i>'
        },
        {
          title: 'Dubai & UAE Tourist Visa',
          subtitle: 'Instant Express E-Visa in Just 24 to 48 Hours',
          pills: [
            { icon: 'fa-bolt', text: '24h Express Processing' },
            { icon: 'fa-envelope-open-text', text: '100% Online Paperless' },
            { icon: 'fa-hotel', text: 'Free OK to Board' },
            { icon: 'fa-headset', text: 'Dedicated Visa Officer' }
          ],
          cursive: 'Dubai<br>Calling<br><i class="fa-solid fa-building"></i>'
        },
        {
          title: 'USA, UK & Canada Visa',
          subtitle: 'Expert Guidance for Long-Term Tourist & Business Visas',
          pills: [
            { icon: 'fa-briefcase', text: 'B1/B2 & Visitor Visas' },
            { icon: 'fa-comments', text: 'Mock Interview Prep' },
            { icon: 'fa-folder-open', text: 'Dossier Filing Help' },
            { icon: 'fa-clock-rotate-left', text: 'Early Slot Tracking' }
          ],
          cursive: 'Global<br>Access<br><i class="fa-solid fa-globe"></i>'
        },
        {
          title: 'Asia & Far East E-Visas',
          subtitle: 'Thailand, Singapore, Malaysia, Vietnam & Japan',
          pills: [
            { icon: 'fa-plane', text: 'Seamless E-Visa' },
            { icon: 'fa-qrcode', text: 'Digital QR Passes' },
            { icon: 'fa-tag', text: 'Lowest Service Fee' },
            { icon: 'fa-circle-check', text: 'Real-Time Tracking' }
          ],
          cursive: 'Asia<br>Entry<br><i class="fa-solid fa-passport"></i>'
        }
      ]
    };

    const destTitle = heroSection.querySelector('.ref-dest-title');
    const destSub = heroSection.querySelector('.ref-dest-subtitle');
    const pillsContainer = heroSection.querySelector('.ref-feature-pills');
    const cursiveTag = heroSection.querySelector('.ref-cursive-tag');

    function updateSlideContent(realIndex, immediate = false) {
      const pageData = sliderContentMap[pageContext];
      if (!pageData || !pageData[realIndex]) return;

      const currentData = pageData[realIndex];

      const applyContent = () => {
        if (destTitle) {
          destTitle.innerHTML = currentData.title;
          destTitle.style.opacity = '1';
        }
        if (destSub) {
          destSub.textContent = currentData.subtitle;
          destSub.style.opacity = '1';
        }
        if (pillsContainer && currentData.pills) {
          const pillElements = pillsContainer.querySelectorAll('.ref-feature-pill');
          currentData.pills.forEach((pillData, idx) => {
            if (pillElements[idx]) {
              const iconEl = pillElements[idx].querySelector('.ref-feature-pill-icon i');
              const textEl = pillElements[idx].querySelector('.ref-feature-pill-text');
              if (iconEl) {
                iconEl.className = `fa-solid ${pillData.icon}`;
              }
              if (textEl) {
                textEl.textContent = pillData.text;
              }
            }
          });
          pillsContainer.style.opacity = '1';
        }
        if (cursiveTag && currentData.cursive) {
          cursiveTag.innerHTML = currentData.cursive;
          cursiveTag.style.opacity = '1';
        }
      };

      if (immediate) {
        applyContent();
      } else {
        if (destTitle) destTitle.style.opacity = '0';
        if (destSub) destSub.style.opacity = '0';
        if (pillsContainer) pillsContainer.style.opacity = '0';
        if (cursiveTag && currentData.cursive) cursiveTag.style.opacity = '0';
        setTimeout(applyContent, 220);
      }
    }

    const updateDestinationText = updateSlideContent;

    let currentIndex = 0;
    let isTransitioning = false;
    let scrollTimer = null;
    const scrollDuration = 800; // ms
    const autoScrollDelay = 3500; // ms between automatic scrolls

    function updateIndicators(index, immediate = false) {
      const realIndex = index % totalSlides;
      dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === realIndex);
      });
      origSlides.forEach((slide, i) => {
        slide.classList.toggle('active', i === realIndex);
      });
      updateSlideContent(realIndex, immediate);
    }

    function scrollToSlide(index, animated = true) {
      if (animated) {
        track.style.transition = 'transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)';
      } else {
        track.style.transition = 'none';
      }
      track.style.transform = `translateX(-${index * 100}%)`;
      updateIndicators(index, !animated);
      currentIndex = index;
    }

    function nextSlide() {
      if (isTransitioning) return;
      isTransitioning = true;
      currentIndex++;
      scrollToSlide(currentIndex, true);

      if (currentIndex === totalSlides) {
        // Scrolled to clone at end, smoothly loop back to slide 0
        setTimeout(() => {
          scrollToSlide(0, false);
          void track.offsetWidth; // Force reflow
          isTransitioning = false;
        }, scrollDuration);
      } else {
        setTimeout(() => {
          isTransitioning = false;
        }, scrollDuration);
      }
    }

    function prevSlide() {
      if (isTransitioning) return;
      isTransitioning = true;
      if (currentIndex === 0) {
        scrollToSlide(totalSlides, false);
        void track.offsetWidth;
        currentIndex = totalSlides - 1;
        setTimeout(() => {
          scrollToSlide(currentIndex, true);
          setTimeout(() => {
            isTransitioning = false;
          }, scrollDuration);
        }, 20);
      } else {
        currentIndex--;
        scrollToSlide(currentIndex, true);
        setTimeout(() => {
          isTransitioning = false;
        }, scrollDuration);
      }
    }

    function startAutoScroll() {
      stopAutoScroll();
      scrollTimer = setInterval(nextSlide, autoScrollDelay);
    }

    function stopAutoScroll() {
      if (scrollTimer) {
        clearInterval(scrollTimer);
        scrollTimer = null;
      }
    }

    // Attach click and hover events to controls
    if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        nextSlide();
        startAutoScroll();
      });
      nextBtn.addEventListener('mouseenter', stopAutoScroll);
      nextBtn.addEventListener('mouseleave', startAutoScroll);
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        prevSlide();
        startAutoScroll();
      });
      prevBtn.addEventListener('mouseenter', stopAutoScroll);
      prevBtn.addEventListener('mouseleave', startAutoScroll);
    }

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        if (isTransitioning) return;
        currentIndex = i;
        scrollToSlide(currentIndex, true);
        startAutoScroll();
      });
      dot.addEventListener('mouseenter', stopAutoScroll);
      dot.addEventListener('mouseleave', startAutoScroll);
    });

    // Mobile touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    heroSlider.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
      stopAutoScroll();
    }, { passive: true });

    heroSlider.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 50) {
        nextSlide();
      } else if (touchEndX - touchStartX > 50) {
        prevSlide();
      }
      startAutoScroll();
    }, { passive: true });

    // Initial state & start auto scrolling
    scrollToSlide(0, false);
    startAutoScroll();
  });

  /* ==========================================
     COUNTRY CODE DROPDOWN SELECTOR
     ========================================== */
  function initCountryCodeSelectors() {
    document.querySelectorAll('.ref-cc-select').forEach(selectEl => {
      const updateDisplay = () => {
        const parent = selectEl.closest('.ref-country-code');
        if (!parent) return;
        const display = parent.querySelector('.ref-cc-display');
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        if (display && selectedOption) {
          display.textContent = selectedOption.getAttribute('data-display') || selectedOption.value;
        }
      };

      selectEl.addEventListener('change', updateDisplay);
      selectEl.addEventListener('input', updateDisplay);
    });
  }
  initCountryCodeSelectors();

  /* ==========================================
     2. EXCLUSIVE DEALS TAB FILTERING
     ========================================== */
  const dealTabs = document.querySelectorAll('.deal-tab');
  const dealsGrid = document.getElementById('dealsGrid');

  const dealData = {
    'HOT DEALS': [
      {
        type: 'custom-image',
        title: 'Voyogo Dubai Hot Deal',
        displayName: 'Dubai',
        bgImage: 'assets/images/voyogo dubai.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Bali Hot Deal',
        displayName: 'Bali',
        bgImage: 'assets/images/voyogo bali .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Swiss Paris Hot Deal',
        displayName: 'Swiss Paris',
        bgImage: 'assets/images/voyogo swiss paris.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Canada with Alaska Hot Deal',
        displayName: 'Canada with Alaska',
        bgImage: 'assets/images/voyogo canada with alaska.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Japan Hot Deal',
        displayName: 'Japan',
        bgImage: 'assets/images/voyogo japan.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Thailand Hot Deal',
        displayName: 'Thailand',
        bgImage: 'assets/images/voyogo thailand.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Mauritius Hot Deal',
        displayName: 'Mauritius',
        bgImage: 'assets/images/voyogo mauritius .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Europe Hot Deal',
        displayName: 'Europe',
        bgImage: 'assets/images/voyogo europe.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Kenya Hot Deal',
        displayName: 'Kenya',
        bgImage: 'assets/images/voyogo kenya.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Langkawi Hot Deal',
        displayName: 'Langkawi',
        bgImage: 'assets/images/voyogo langkawi .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Vietnam Hot Deal',
        displayName: 'Vietnam',
        bgImage: 'assets/images/voyogo vietnom.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Scandinavia Hot Deal',
        displayName: 'Scandinavia',
        bgImage: 'assets/images/voyogo scandinavia.png'
      }
    ],
    'FIXED DEPARTURES': [
      {
        type: 'custom-image',
        title: 'Voyogo Japan Fixed Departure',
        displayName: 'Japan',
        bgImage: 'assets/images/voyogo japan.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Kenya Fixed Departure',
        displayName: 'Kenya',
        bgImage: 'assets/images/voyogo kenya.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Rajasthan Fixed Departure',
        displayName: 'Rajasthan',
        bgImage: 'assets/images/voyogo rajasthan.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Scandinavia Fixed Departure',
        displayName: 'Scandinavia',
        bgImage: 'assets/images/voyogo scandinavia.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Singapore Fixed Departure',
        displayName: 'Singapore',
        bgImage: 'assets/images/voyogo singapore.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Sri Lanka Fixed Departure',
        displayName: 'Sri Lanka',
        bgImage: 'assets/images/voyogo srilanka.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Thailand Fixed Departure',
        displayName: 'Thailand',
        bgImage: 'assets/images/voyogo thailand.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo USA Fixed Departure',
        displayName: 'USA',
        bgImage: 'assets/images/voyogo usa.png'
      }
    ],
    'GROUP TOUR': [
      {
        type: 'custom-image',
        title: 'Voyogo Andaman Group Tour',
        displayName: 'Andaman',
        bgImage: 'assets/images/voyogo andaman.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Bali Group Tour',
        displayName: 'Bali',
        bgImage: 'assets/images/voyogo bali.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Bhutan Group Tour',
        displayName: 'Bhutan',
        bgImage: 'assets/images/voyogo bhutan.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Canada with Alaska Group Tour',
        displayName: 'Canada with Alaska',
        bgImage: 'assets/images/voyogo canada with alaska.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo China Group Tour',
        displayName: 'China',
        bgImage: 'assets/images/voyogo china.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Dubai Group Tour',
        displayName: 'Dubai',
        bgImage: 'assets/images/voyogo dubai.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Egypt Group Tour',
        displayName: 'Egypt',
        bgImage: 'assets/images/voyogo egypt.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Europe Group Tour',
        displayName: 'Europe',
        bgImage: 'assets/images/voyogo europe.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Japan Group Tour',
        displayName: 'Japan',
        bgImage: 'assets/images/voyogo japan.png'
      }
    ],
    'HONEY MOON': [
      {
        type: 'custom-image',
        title: 'Voyogo Bali Honeymoon Deal',
        displayName: 'Bali',
        bgImage: 'assets/images/voyogo bali .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Langkawi Honeymoon Deal',
        displayName: 'Langkawi',
        bgImage: 'assets/images/voyogo langkawi .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Mauritius Honeymoon Deal',
        displayName: 'Mauritius',
        bgImage: 'assets/images/voyogo mauritius .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Singapore Honeymoon Deal',
        displayName: 'Singapore',
        bgImage: 'assets/images/voyogo singapore.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Sri Lanka Honeymoon Deal',
        displayName: 'Sri Lanka',
        bgImage: 'assets/images/voyogo srilanka.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Swiss Paris Honeymoon Deal',
        displayName: 'Swiss Paris',
        bgImage: 'assets/images/voyogo swiss paris.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Thailand Honeymoon Deal',
        displayName: 'Thailand',
        bgImage: 'assets/images/voyogo thailand.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Vietnam Honeymoon Deal',
        displayName: 'Vietnam',
        bgImage: 'assets/images/voyogo vietnom.png'
      }
    ],
    'HONEYMOON': [
      {
        type: 'custom-image',
        title: 'Voyogo Bali Honeymoon Deal',
        displayName: 'Bali',
        bgImage: 'assets/images/voyogo bali .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Langkawi Honeymoon Deal',
        displayName: 'Langkawi',
        bgImage: 'assets/images/voyogo langkawi .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Mauritius Honeymoon Deal',
        displayName: 'Mauritius',
        bgImage: 'assets/images/voyogo mauritius .png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Singapore Honeymoon Deal',
        displayName: 'Singapore',
        bgImage: 'assets/images/voyogo singapore.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Sri Lanka Honeymoon Deal',
        displayName: 'Sri Lanka',
        bgImage: 'assets/images/voyogo srilanka.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Swiss Paris Honeymoon Deal',
        displayName: 'Swiss Paris',
        bgImage: 'assets/images/voyogo swiss paris.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Thailand Honeymoon Deal',
        displayName: 'Thailand',
        bgImage: 'assets/images/voyogo thailand.png'
      },
      {
        type: 'custom-image',
        title: 'Voyogo Vietnam Honeymoon Deal',
        displayName: 'Vietnam',
        bgImage: 'assets/images/voyogo vietnom.png'
      }
    ],
    'SIGNATURE': [
      {
        type: 'custom-image',
        title: 'Classic China Signature Tour',
        displayName: 'China',
        bgImage: 'assets/images/voyogo china.png'
      },
      {
        type: 'custom-image',
        title: 'Europe Signature Tour',
        displayName: 'Europe',
        bgImage: 'assets/images/voyogo europe.png'
      },
      {
        type: 'custom-image',
        title: 'Scandinavia Signature Tour',
        displayName: 'Scandinavia',
        bgImage: 'assets/images/voyogo scandinavia.png'
      },
      {
        type: 'custom-image',
        title: 'Bhutan Signature Tour',
        displayName: 'Bhutan',
        bgImage: 'assets/images/voyogo bhutan.png'
      }
    ]
  };

  dealTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      dealTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const category = tab.textContent.trim();
      renderCategoryDeals(category);
    });
  });

  function renderCategoryDeals(category) {
    if (!dealsGrid) return;

    const deals = dealData[category] || dealData['HOT DEALS'];
    dealsGrid.style.opacity = '0';

    setTimeout(() => {
      dealsGrid.innerHTML = deals.map(deal => createCardHTML(deal)).join('');
      dealsGrid.style.opacity = '1';
    }, 200);
  }

  function getCountryName(deal) {
    if (deal.displayName) return deal.displayName;
    if (!deal.bgImage) return deal.title || '';

    let filename = deal.bgImage.split('/').pop().replace(/\.[^/.]+$/, '').trim();
    filename = filename.replace(/^voyogo\s+/i, '').trim();
    if (filename.toLowerCase() === 'bal') return 'Bali';
    if (filename.toLowerCase() === 'china-avata') return 'China Avatar';
    if (filename.toLowerCase() === 'classicchin') return 'Classic China';
    if (filename.toLowerCase() === 'egypt') return 'Egypt';
    if (filename.toLowerCase() === 'hongkong') return 'Hong Kong';
    if (filename.toLowerCase() === 'antarctica cruise1') return 'Antarctica Cruise';
    if (filename.toLowerCase() === 'azerbaijan') return 'Azerbaijan';
    if (filename.toLowerCase() === 'srilanka') return 'Sri Lanka';
    if (filename.toLowerCase() === 'vietnom') return 'Vietnam';
    if (filename.toLowerCase() === 'swiss paris') return 'Swiss Paris';

    return filename.split(' ')
      .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
      .join(' ');
  }

  function createCardHTML(deal) {
    if (deal.type === 'custom-image') {
      const countryName = getCountryName(deal);
      return `
        <div class="deal-card custom-image-card" style="background-image: url('${deal.bgImage}');" onclick="openEnquiryModal('${deal.title}')">
          <div class="custom-card-overlay">
            <div class="custom-card-title">${countryName}</div>
          </div>
        </div>
      `;
    }

    const bgStyle = deal.bgImage ? `style="background-image: url('${deal.bgImage}');"` : '';

    if (deal.type === 'abu-dhabi') {
      return `
        <div class="deal-card card-abu-dhabi" ${bgStyle} onclick="openEnquiryModal('${deal.title}')">
          <div class="deal-card-overlay">
            <div class="abu-dhabi-content">
              <div class="abu-dhabi-subtitle">Marhaba, Experience</div>
              <div class="abu-dhabi-title">Abu Dhabi</div>
              <div style="font-size:0.75rem; font-weight:700; color:#DC2626;">Visa-Free</div>
              <div style="margin-top:8px;">
                <div class="abu-dhabi-price-tag">Packages Start From</div>
                <div class="abu-dhabi-price">${deal.price || 'INR 20,999*'}</div>
              </div>
              <div class="badge-red">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                ${deal.badge || 'FREE UAE VISA'}
              </div>
            </div>
          </div>
        </div>
      `;
    } else if (deal.type === 'bali') {
      return `
        <div class="deal-card card-bali" ${bgStyle} onclick="openEnquiryModal('${deal.title}')">
          <div class="deal-card-overlay">
            <div>
              <div class="card-bali-title">${deal.title}</div>
              <div class="card-bali-sub">"${deal.sub || 'Island of Endless Wonders'}"</div>
            </div>
            <div class="card-bali-date">${deal.date || '[Ex: BOM | AMD]'}</div>
          </div>
        </div>
      `;
    } else if (deal.type === 'malaysia') {
      return `
        <div class="deal-card card-malaysia" ${bgStyle} onclick="openEnquiryModal('${deal.title}')">
          <div class="deal-card-overlay">
            <div>
              <div class="card-malaysia-title">${deal.title}</div>
              <div class="card-malaysia-sub">${deal.sub || '"Your next Great Escape"'}</div>
            </div>
            <div class="promo-box-yellow">
              ${deal.promo || 'BOOK NOW & GET SPECIAL DEALS'}
            </div>
          </div>
        </div>
      `;
    } else {
      return `
        <div class="deal-card card-singapore" ${bgStyle} onclick="openEnquiryModal('${deal.title}')">
          <div class="deal-card-overlay">
            <div>
              <div class="card-singapore-title">${deal.title}</div>
              <div class="card-singapore-sub">${deal.sub || '"Experience the Lion City"'}</div>
            </div>
            <div class="promo-tag-blue">
              ${deal.discount || 'GET UPTO ₹5,000 OFF'}
            </div>
          </div>
        </div>
      `;
    }
  }

  /* ==========================================
     3. TRENDING DESTINATIONS TAB FILTERING
     ========================================== */
  const trendingTabs = document.querySelectorAll('.trending-tab');
  const trendingGrid = document.getElementById('trendingGrid');

  const trendingData = {
    'INTERNATIONAL': [
      { name: 'Maldives', price: '₹80,000', bgImage: 'assets/images/voyogo maladives.png' },
      { name: 'Singapore', price: '₹55,000', bgImage: 'assets/images/voyogo singapore.png' },
      { name: 'Malaysia', price: '₹23,000', bgImage: 'assets/images/voyogo Singapore &malaysia.png' },
      { name: 'Azerbaijan', price: '₹45,000', bgImage: 'assets/images/voyogo Azerbaijan.png' },
      { name: 'Thailand', price: '₹29,000', bgImage: 'assets/images/voyogo thailand.png' },
      { name: 'Hong Kong', price: '₹90,000', bgImage: 'assets/images/voyogo hong kong.png' },
      { name: 'Phu Quoc', price: '₹42,000', bgImage: 'assets/images/voyogo phu quoc.png' },
      { name: 'Almaty', price: '₹47,000', bgImage: 'assets/images/voyogo almaty.png' },
      { name: 'Georgia', price: '₹47,000', bgImage: 'assets/images/voyogo europe.png' },
      { name: 'Langkawi', price: '₹35,000', bgImage: 'assets/images/voyogo langkawi.png' },
      { name: 'Bali', price: '₹30,000', bgImage: 'assets/images/voyogo bali.png' },
      { name: 'China', price: '₹80,000', bgImage: 'assets/images/voyogo china.png' },
      { name: 'Dubai', price: '₹50,000', bgImage: 'assets/images/voyogo dubai.png' },
      { name: 'Sri Lanka', price: '₹27,000', bgImage: 'assets/images/voyogo srilanka.png' },
      { name: 'Vietnam', price: '₹37,000', bgImage: 'assets/images/voyogo vietnom.png' }
    ],
    'DOMESTIC': [
      { name: 'Andaman', price: '₹25,000', bgImage: 'assets/images/voyogo andaman.png' },
      { name: 'Goa', price: '₹17,000', bgImage: 'assets/images/voyogo goa.png' },
      { name: 'Shimla & Manali', price: '₹17,000', bgImage: 'assets/images/voyogo simla & manali.png' },
      { name: 'Rajasthan', price: '₹20,000', bgImage: 'assets/images/voyogo rajasthan.png' },
      { name: 'Golden Triangle', price: '₹17,000', bgImage: 'assets/images/voyogo golden triangle.png' },
      { name: 'Kashmir', price: '₹22,000', bgImage: 'assets/images/voyogo kashmir.png' },
      { name: 'Darjeeling', price: '₹22,000', bgImage: 'assets/images/voyogo dorjeeing.png' },
      { name: 'Meghalaya', price: '₹22,000', bgImage: 'assets/images/voyogo meghalaya.png' },
      { name: 'Bhutan', price: '₹22,000', bgImage: 'assets/images/voyogo bhutan.png' }
    ]
  };

  trendingTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      trendingTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const category = tab.textContent.trim();
      renderTrendingDestinations(category);
    });
  });

  function renderTrendingDestinations(category) {
    if (!trendingGrid) return;

    const destinations = trendingData[category] || trendingData['INTERNATIONAL'];
    trendingGrid.style.opacity = '0';

    setTimeout(() => {
      trendingGrid.innerHTML = destinations.map(item => `
        <div class="trending-card" onclick="openEnquiryModal('${item.name} Domestic Package')">
          <div class="trending-card-img" style="background-image: url('${item.bgImage}');">
            <div class="trending-title-overlay">${item.name}</div>
          </div>
          <div class="trending-card-footer">
            Starting @ <span>${item.price}</span>
          </div>
        </div>
      `).join('');
      trendingGrid.style.opacity = '1';
    }, 200);
  }

  /* ==========================================
     4. INTERNATIONAL & DOMESTIC DESTINATIONS TABS
     ========================================== */
  const intlTabs = document.querySelectorAll('.intl-tab');
  const intlGrid = document.getElementById('intlGrid');

  const intlData = {
    'Asia': [
      { title: 'Bali Tropical Escape', badge: '5D & 4N', route: 'Kuta (3) → Ubud (2)', oldPrice: 'Starting @', price: '₹74,000/-', theme: 'pkg-card-green', bgImage: 'assets/images/voyogo bali.png' },
      { title: 'China Imperial & Wonders', badge: '8D & 7N', route: 'Beijing (4) → Shanghai (4)', oldPrice: 'Starting @', price: '₹2,05,000/-', theme: 'pkg-card-teal', bgImage: 'assets/images/voyogo china.png' },
      { title: 'Sri Lanka Island Discovery', badge: '5D & 4N', route: 'Colombo (2) → Kandy (2) → Bentota (1)', oldPrice: 'Starting @', price: '₹57,000/-', theme: 'pkg-card-blue', bgImage: 'assets/images/voyogo srilanka.png' },
      { title: 'Vietnam Heritage & Cruise', badge: '6D & 5N', route: 'Hanoi (2) → Halong Bay (1) → Danang (2)', oldPrice: 'Starting @', price: '₹1,18,000/-', theme: 'pkg-card-green', bgImage: 'assets/images/voyogo vietnom.png' },
      { title: 'Singapore & Malaysia Combo', badge: '6D & 5N', route: 'Singapore (3) → Kuala Lumpur (2)', oldPrice: 'Starting @', price: '₹1,13,000/-', theme: 'pkg-card-slate', bgImage: 'assets/images/voyogo Singapore &malaysia.png' },
      { title: 'Vietnam & Cambodia Expedition', badge: '8D & 7N', route: 'Hanoi (3) → Siem Reap (4)', oldPrice: 'Starting @', price: '₹1,49,000/-', theme: 'pkg-card-olive', bgImage: 'assets/images/voyogo vietnom & Combodia.png' },
      { title: 'Japan Cherry Blossom Wonders', badge: '8D & 7N', route: 'Tokyo (4) → Kyoto (3)', oldPrice: 'Starting @', price: '₹2,86,000/-', theme: 'pkg-card-teal', bgImage: 'assets/images/voyogo japan.png' }
    ],
    'Middle East': [
      { title: 'Dubai Luxury & Desert Safari', badge: '5D & 4N', route: 'Dubai (3) → Desert Resort (1)', oldPrice: 'Starting @', price: '₹99,000/-', theme: 'pkg-card-teal', bgImage: 'assets/images/voyogo dubai.png' }
    ],
    'Africa': [
      { title: 'Egypt Pyramids & Nile Cruise', badge: '8D & 7N', route: 'Cairo (3) → Aswan (4)', oldPrice: 'Starting @', price: '₹1,89,000/-', theme: 'pkg-card-olive', bgImage: 'assets/images/voyogo egypt.png' },
      { title: 'Kenya Wildlife Safari', badge: '6D & 5N', route: 'Nairobi (2) → Masai Mara (3)', oldPrice: 'Starting @', price: '₹2,59,000/-', theme: 'pkg-card-green', bgImage: 'assets/images/voyogo kenya.png' }
    ],
    'Oceania': [
      { title: 'Australia & Pacific Magic', badge: '8D & 7N', route: 'Sydney (4) → Melbourne (3)', oldPrice: 'Starting @', price: '₹2,86,000/-', theme: 'pkg-card-blue', bgImage: 'assets/images/voyogo Singapore &malaysia.png' }
    ],
    'Europe': [
      { title: 'Scandinavia Aurora & Fjords', badge: '10D & 9N', route: 'Oslo (4) → Bergen (5)', oldPrice: 'Starting @', price: '₹3,84,000/-', theme: 'pkg-card-blue', bgImage: 'assets/images/voyogo scandinavia.png' },
      { title: 'Grand Europe Highlights', badge: '10D & 9N', route: 'Paris (3) → Swiss Alps (3) → Rome (3)', oldPrice: 'Starting @', price: '₹2,79,000/-', theme: 'pkg-card-teal', bgImage: 'assets/images/voyogo europe.png' },
      { title: 'Russia Imperial Odyssey', badge: '8D & 7N', route: 'Moscow (4) → St. Petersburg (3)', oldPrice: 'Starting @', price: '₹1,45,000/-', theme: 'pkg-card-slate', bgImage: 'assets/images/voyogo russia.png' }
    ],
    'America': [
      { title: 'USA Coast to Coast Wonders', badge: '21D & 20N', route: 'New York (7) → Orlando (6) → Los Angeles (7)', oldPrice: 'Starting @', price: '₹7,59,000/-', theme: 'pkg-card-dark', bgImage: 'assets/images/voyogo usa.png' },
      { title: 'Canada with Alaska Glacier Cruise', badge: '15D & 14N', route: 'Vancouver (5) → Alaska Cruise (9)', oldPrice: 'Starting @', price: '₹7,79,000/-', theme: 'pkg-card-dark', bgImage: 'assets/images/voyogo canada with alaska.png' }
    ]
  };

  intlTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      intlTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const category = tab.textContent.trim();
      renderIntlPackages(category);
    });
  });

  function renderIntlPackages(category) {
    if (!intlGrid) return;
    const items = intlData[category] || intlData['Asia'];
    intlGrid.style.opacity = '0';
    setTimeout(() => {
      intlGrid.innerHTML = items.map(item => createPackageCardHTML(item)).join('');
      intlGrid.style.opacity = '1';
    }, 200);
  }

  const domTabs = document.querySelectorAll('.dom-tab');
  const domGrid = document.getElementById('domGrid');

  const domData = {
    'East India': [
      { title: 'Darjeeling Special', badge: '5D & 4N', route: 'Darjeeling (4)', oldPrice: '₹25,000/-', price: '₹22,000/-', theme: 'pkg-card-olive', bgImage: 'assets/images/voyogo dorjeeing.png' },
      { title: 'Meghalaya Explorer', badge: '5D & 4N', route: 'Shillong (3) → Cherrapunji (1)', oldPrice: '₹25,000/-', price: '₹22,000/-', theme: 'pkg-card-blue', bgImage: 'assets/images/voyogo meghalaya.png' },
      { title: 'Bhutan Wonders', badge: '6D & 5N', route: 'Paro (3) → Thimphu (2)', oldPrice: '₹70,000/-', price: '₹22,000/-', theme: 'pkg-card-grey', bgImage: 'assets/images/voyogo bhutan.png' }
    ],
    'North India': [
      { title: 'Shimla & Manali Escapade', badge: '5D & 4N', route: 'Shimla (2) → Manali (2)', oldPrice: '₹20,000/-', price: '₹17,000/-', theme: 'pkg-card-olive', bgImage: 'assets/images/voyogo simla & manali.png' },
      { title: 'Kashmir Heavenly Gateway', badge: '5D & 4N', route: 'Srinagar (2) → Gulmarg (2)', oldPrice: '₹25,000/-', price: '₹22,000/-', theme: 'pkg-card-blue', bgImage: 'assets/images/voyogo kashmir.png' },
      { title: 'Golden Triangle Special', badge: '5D & 4N', route: 'Delhi (2) → Agra (1) → Jaipur (1)', oldPrice: '₹20,000/-', price: '₹17,000/-', theme: 'pkg-card-amber', bgImage: 'assets/images/voyogo golden triangle.png' }
    ],
    'South India': [
      { title: 'Andaman Island Paradise', badge: '5D & 4N', route: 'Port Blair (2) → Havelock (2)', oldPrice: '₹28,000/-', price: '₹25,000/-', theme: 'pkg-card-green', bgImage: 'assets/images/voyogo andaman.png' }
    ],
    'Central India': [
      { title: 'Royal Rajasthan Express', badge: '5D & 4N', route: 'Jaipur (2) → Udaipur (2)', oldPrice: '₹23,000/-', price: '₹20,000/-', theme: 'pkg-card-amber', bgImage: 'assets/images/voyogo rajasthan.png' }
    ],
    'West India': [
      { title: 'Goa Sun & Beach Retreat', badge: '5D & 4N', route: 'North Goa (2) → South Goa (2)', oldPrice: '₹20,000/-', price: '₹17,000/-', theme: 'pkg-card-teal', bgImage: 'assets/images/voyogo goa.png' }
    ]
  };

  domTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      domTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const category = tab.textContent.trim();
      renderDomPackages(category);
    });
  });

  function renderDomPackages(category) {
    if (!domGrid) return;
    const items = domData[category] || domData['East India'];
    domGrid.style.opacity = '0';
    setTimeout(() => {
      domGrid.innerHTML = items.map(item => createPackageCardHTML(item)).join('');
      domGrid.style.opacity = '1';
    }, 200);
  }

  function createPackageCardHTML(item) {
    return `
      <div class="package-card" onclick="openEnquiryModal('${item.title}')">
        <div class="pkg-card-img" style="background-image: url('${item.bgImage}');">
          <span class="pkg-duration-badge">${item.badge}</span>
        </div>
        <div class="pkg-card-body">
          <h3 class="pkg-title">${item.title}</h3>
          <div class="pkg-route">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>${item.route}</span>
          </div>
        </div>
        <div class="pkg-price-strip">
          <div class="pkg-price-box">
            <span class="pkg-old-price">${item.oldPrice}</span>
            <span class="pkg-new-price">${item.price}</span>
          </div>
          <button class="btn-view-details">View Details</button>
        </div>
      </div>
    `;
  }

  /* ==========================================
     5. CAROUSEL NAV BUTTONS (< >)
     ========================================== */
  const prevCarouselBtn = document.getElementById('prevCarouselBtn');
  const nextCarouselBtn = document.getElementById('nextCarouselBtn');

  if (prevCarouselBtn && nextCarouselBtn && dealsGrid) {
    nextCarouselBtn.addEventListener('click', () => {
      dealsGrid.scrollBy({ left: 320, behavior: 'smooth' });
    });

    prevCarouselBtn.addEventListener('click', () => {
      dealsGrid.scrollBy({ left: -320, behavior: 'smooth' });
    });
  }

  const prevTrendingBtn = document.getElementById('prevTrendingBtn');
  const nextTrendingBtn = document.getElementById('nextTrendingBtn');

  if (prevTrendingBtn && nextTrendingBtn && trendingGrid) {
    nextTrendingBtn.addEventListener('click', () => {
      trendingGrid.scrollBy({ left: 320, behavior: 'smooth' });
    });

    prevTrendingBtn.addEventListener('click', () => {
      trendingGrid.scrollBy({ left: -320, behavior: 'smooth' });
    });
  }

  const prevThemeBtn = document.getElementById('prevThemeBtn');
  const nextThemeBtn = document.getElementById('nextThemeBtn');
  const themeGrid = document.getElementById('themeGrid');

  if (prevThemeBtn && nextThemeBtn && themeGrid) {
    nextThemeBtn.addEventListener('click', () => {
      themeGrid.scrollBy({ left: 320, behavior: 'smooth' });
    });

    prevThemeBtn.addEventListener('click', () => {
      themeGrid.scrollBy({ left: -320, behavior: 'smooth' });
    });
  }

  const prevIntlBtn = document.getElementById('prevIntlBtn');
  const nextIntlBtn = document.getElementById('nextIntlBtn');

  if (prevIntlBtn && nextIntlBtn && intlGrid) {
    nextIntlBtn.addEventListener('click', () => {
      intlGrid.scrollBy({ left: 320, behavior: 'smooth' });
    });

    prevIntlBtn.addEventListener('click', () => {
      intlGrid.scrollBy({ left: -320, behavior: 'smooth' });
    });
  }

  const prevDomBtn = document.getElementById('prevDomBtn');
  const nextDomBtn = document.getElementById('nextDomBtn');

  if (prevDomBtn && nextDomBtn && domGrid) {
    nextDomBtn.addEventListener('click', () => {
      domGrid.scrollBy({ left: 320, behavior: 'smooth' });
    });

    prevDomBtn.addEventListener('click', () => {
      domGrid.scrollBy({ left: -320, behavior: 'smooth' });
    });
  }

  /* Popular Visa Carousel & Filtering */
  const prevVisaBtn = document.getElementById('prevVisaBtn');
  const nextVisaBtn = document.getElementById('nextVisaBtn');
  const visaGrid = document.getElementById('visaGrid');

  if (prevVisaBtn && nextVisaBtn && visaGrid) {
    nextVisaBtn.addEventListener('click', () => {
      visaGrid.scrollBy({ left: 300, behavior: 'smooth' });
    });

    prevVisaBtn.addEventListener('click', () => {
      visaGrid.scrollBy({ left: -300, behavior: 'smooth' });
    });
  }

  const visaTabs = document.querySelectorAll('.visa-tab');
  // AC tab data — all provided visa service entries
  const acData = [
    { country: 'Malaysia AC', badge: 'AC', badgeClass: 'ac', price: 'Rs.500', bgImage: 'assets/images/jpeg/voyogo malaysia.png' },
    { country: 'Sri Lanka AC', badge: 'AC', badgeClass: 'ac', price: 'Rs.500', bgImage: 'assets/images/voyogo srilanka.png' },
    { country: 'Thailand AC', badge: 'AC', badgeClass: 'ac', price: 'Rs.500', bgImage: 'assets/images/voyogo thailand.png' },
    { country: 'Hong Kong AC', badge: 'AC', badgeClass: 'ac', price: 'Rs.500', bgImage: 'assets/images/jpeg/voyogo hong kong.png' },
    { country: 'Philippines Health Arrival Card', badge: 'AC', badgeClass: 'ac', price: 'Rs.500', bgImage: 'assets/images/jpeg/voyogo philipines.png' },
    { country: 'Bali E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,500', bgImage: 'assets/images/voyogo bali .png' },
    { country: 'Bali Levy', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/jpeg/voyogo bali levi.png' },
    { country: 'Bali Arrival Card', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.300', bgImage: 'assets/images/jpeg/voyogo bali levi.png' },
    { country: 'Vietnam E-VISA + Arrival Card', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,000', bgImage: 'assets/images/jpeg/voyogo vietnam.png' },
    { country: 'Egypt E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo egypt.png' },
    { country: 'Dubai E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo dubai.png' },
    { country: 'Kazakhstan', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/voyogo almaty.png' },
    { country: 'Dubai Adult E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,500', bgImage: 'assets/images/voyogo dubai.png' },
    { country: 'Dubai Child E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/voyogo dubai.png' },
    { country: 'UK E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.23,000', bgImage: 'assets/images/jpeg/voyogo uk.png' },
    { country: 'Kenya E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,000', bgImage: 'assets/images/voyogo kenya.png' },
    { country: 'Tanzania E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo tanzania.png' },
    { country: 'Azerbaijan E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,500', bgImage: 'assets/images/jpeg/voyogo azerbaijan.png' },
    { country: 'Zimbabwe E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo zimbabwe.png' },
    { country: 'Georgia E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.2,000', bgImage: 'assets/images/jpeg/voyogo azerbaijan.png' },
    { country: 'Rwanda E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,500', bgImage: 'assets/images/voyogo south africe.png' },
    { country: 'Uganda E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.6,500', bgImage: 'assets/images/voyogo south africe.png' },
    { country: 'Madagascar E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo south africe.png' },
    { country: 'Myanmar E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.6,500', bgImage: 'assets/images/voyogo thailand.png' },
    { country: 'Austria E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.24,500', bgImage: 'assets/images/voyogo europe.png' },
    { country: 'New Zealand E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.24,500', bgImage: 'assets/images/voyogo europe.png' },
    { country: 'Zimbabwe ETA', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/jpeg/voyogo zimbabwe.png' },
    { country: 'Albania', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.2-3', bgImage: 'assets/images/voyogo europe.png' },
    { country: 'BHUTAN Permit', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo bhutan.png' },
    { country: 'Myanmar E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,000', bgImage: 'assets/images/voyogo thailand.png' },
    { country: 'South Korea E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo south korea.png' },
    { country: 'Laos E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,500', bgImage: 'assets/images/jpeg/voyogo vietnam.png' },
    { country: 'Turkey E-VISA', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/jpeg/voyogo turkey.png' },
    { country: 'China Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.10,000', bgImage: 'assets/images/jpeg/voyogo china.png' },
    { country: 'China Express Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.14,000', bgImage: 'assets/images/jpeg/voyogo china express.png' },
    { country: 'Japan Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.3,000', bgImage: 'assets/images/voyogo japan.png' },
    { country: 'Turkey Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.24,000', bgImage: 'assets/images/jpeg/voyogo turkey.png' },
    { country: 'Canada Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.20,000', bgImage: 'assets/images/jpeg/voyogo canada.png' },
    { country: 'Schengen Visa Specialist Only', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.14,000', bgImage: 'assets/images/voyogo europe.png' },
    { country: 'USA Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.35,000', bgImage: 'assets/images/voyogo usa.png' }
  ];

  // E-VISA and STICKER VISA tab data with correct entries
  const visaData = {
    'E-VISA': [
      { country: 'Bali E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,500', bgImage: 'assets/images/voyogo bali .png' },
      { country: 'Bali-Levy', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/jpeg/voyogo bali levi.png' },
      { country: 'Bali Arrival Card', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.300', bgImage: 'assets/images/jpeg/voyogo bali levi.png' },
      { country: 'Vietnam E-Visa + Arrival Card', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,000', bgImage: 'assets/images/jpeg/voyogo vietnam.png' },
      { country: 'Egypt E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo egypt.png' },
      { country: 'Dubai E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'Kazakhstan', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/voyogo almaty.png' },
      { country: 'Dubai Adult E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,500', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'Dubai Child E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'UK E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.23,000', bgImage: 'assets/images/jpeg/voyogo uk.png' },
      { country: 'Kenya E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,000', bgImage: 'assets/images/voyogo kenya.png' },
      { country: 'Tanzania E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo tanzania.png' },
      { country: 'Azerbaijan E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.3,500', bgImage: 'assets/images/jpeg/voyogo azerbaijan.png' },
      { country: 'Zimbabwe E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.4,000', bgImage: 'assets/images/jpeg/voyogo zimbabwe.png' },
      { country: 'Georgia E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.2,000', bgImage: 'assets/images/jpeg/voyogo azerbaijan.png' },
      { country: 'Rwanda E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,500', bgImage: 'assets/images/voyogo south africe.png' },
      { country: 'Uganda E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.6,500', bgImage: 'assets/images/voyogo south africe.png' },
      { country: 'Madagascar E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo south africe.png' },
      { country: 'Myanmar E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.6,500', bgImage: 'assets/images/voyogo thailand.png' },
      { country: 'Austria E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.24,500', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'New Zealand E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.24,500', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'Zimbabwe ETA', badge: 'E-VISA', badgeClass: 'e-visa', price: '-', bgImage: 'assets/images/jpeg/voyogo zimbabwe.png' },
      { country: 'Albania', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.2-3', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'BHUTAN Permit', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo bhutan.png' },
      { country: 'Myanmar E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,000', bgImage: 'assets/images/voyogo thailand.png' },
      { country: 'South Korea E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/voyogo south korea.png' },
      { country: 'Laos E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.7,500', bgImage: 'assets/images/jpeg/voyogo vietnam.png' },
      { country: 'Turkey E-Visa', badge: 'E-VISA', badgeClass: 'e-visa', price: 'Rs.5,000', bgImage: 'assets/images/jpeg/voyogo turkey.png' }
    ],
    'STICKER VISA': [
      { country: 'China', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.10,000', bgImage: 'assets/images/jpeg/voyogo china.png' },
      { country: 'China Express', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.14,000', bgImage: 'assets/images/jpeg/voyogo china express.png' },
      { country: 'Japan', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.3,000', bgImage: 'assets/images/voyogo japan.png' },
      { country: 'Turkey', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.24,000', bgImage: 'assets/images/jpeg/voyogo turkey.png' },
      { country: 'Canada Sticker Visa', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.20,000', bgImage: 'assets/images/jpeg/voyogo canada.png' },
      { country: 'Schengen Visa Specialist Only', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.14,000', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'USA', badge: 'STICKER', badgeClass: 'sticker', price: 'Rs.35,000', bgImage: 'assets/images/voyogo usa.png' }
    ]
  };

  // Helper: render AC-style simple cards (title + price only)
  function renderAcCards(items) {
    const base = window.voyogoBaseUrl || (window.location.pathname.startsWith('/voyogo-main') ? '/voyogo-main/' : '/');
    return items.map(item => {
      let img = item.bgImage;
      if (!img.startsWith('http://') && !img.startsWith('https://')) {
        img = base.replace(/\/$/, '') + '/' + img.replace(/^\//, '');
      }
      return `
      <div class="visa-card-item" onclick="openEnquiryModal('${item.country}')">
        <div class="visa-card-img" style="background-image: url('${img}');">
          ${item.badge ? `<span class="visa-badge ${item.badgeClass || ''}">${item.badge}</span>` : ''}
        </div>
        <div class="visa-card-body">
          <h3 class="visa-card-title">${item.country}</h3>
          <div class="visa-card-info"><span>${item.price}</span></div>
        </div>
      </div>
    `;
    }).join('');
  }

  // Helper: render simple cards for all tabs (title + price)
  function renderDetailedCards(items) {
    return renderAcCards(items);
  }

  visaTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      visaTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const cat = tab.textContent.trim();
      if (!visaGrid) return;
      visaGrid.style.opacity = '0';
      setTimeout(() => {
        if (cat === 'AC') {
          visaGrid.innerHTML = renderAcCards(acData);
        } else {
          const items = visaData[cat] || visaData['E-VISA'];
          visaGrid.innerHTML = renderDetailedCards(items);
        }
        visaGrid.style.opacity = '1';
      }, 200);
    });
  });

  /* ==========================================
     6. MODAL ENQUIRY POPUP
     ========================================== */
  window.openEnquiryModal = function (packageName) {
    const modal = document.getElementById('enquiryModal');
    const pkgField = document.getElementById('modalPackageName');
    if (modal) {
      if (pkgField) pkgField.value = packageName || 'General Holiday Enquiry';
      modal.classList.add('active');
    }
  };

  window.closeEnquiryModal = function () {
    const modal = document.getElementById('enquiryModal');
    if (modal) modal.classList.remove('active');
  };

  /* ==========================================
     7. PASSPORT NUMBER CONDITIONAL TOGGLE
     ========================================== */
  function updatePassportVisibility() {
    const selected = document.querySelector('input[name="has_passport"]:checked');
    const group = document.getElementById('passportNumberGroup');
    const input = document.getElementById('passportNumberInput');
    if (group && input && selected) {
      if (selected.value === 'Yes') {
        group.style.display = 'flex';
        input.setAttribute('required', 'required');
      } else {
        group.style.display = 'none';
        input.removeAttribute('required');
      }
    }

    const modalSelected = document.querySelector('input[name="modal_has_passport"]:checked');
    const modalGroup = document.getElementById('modalPassportNumberGroup');
    const modalInput = document.getElementById('modalPassportNumberInput');
    if (modalGroup && modalInput && modalSelected) {
      if (modalSelected.value === 'Yes') {
        modalGroup.style.display = 'block';
        modalInput.setAttribute('required', 'required');
      } else {
        modalGroup.style.display = 'none';
        modalInput.removeAttribute('required');
      }
    }
  }

  document.addEventListener('change', updatePassportVisibility);
  document.addEventListener('click', updatePassportVisibility);
  updatePassportVisibility();

  /* Mobile Process Slide Dots Sync */
  const processGridTrack = document.querySelector('.process-steps-timeline');
  const processDotItems = document.querySelectorAll('.process-dot');

  if (processGridTrack && processDotItems.length > 0) {
    processGridTrack.addEventListener('scroll', () => {
      const scrollLeft = processGridTrack.scrollLeft;
      const cardWidth = processGridTrack.offsetWidth * 0.72;
      const activeIdx = Math.min(
        processDotItems.length - 1,
        Math.floor((scrollLeft + cardWidth / 2) / cardWidth)
      );
      processDotItems.forEach((dot, i) => {
        dot.classList.toggle('active', i === activeIdx);
      });
    });
  }

});

/* Global Interactivity Helpers for Reference Forms */
window.selectTripType = function (button, type) {
  const container = button.closest('.cabs-trip-pills') || button.parentElement;
  if (container) {
    container.querySelectorAll('.cabs-trip-pill').forEach(b => b.classList.remove('active'));
  }
  button.classList.add('active');
  const hiddenInput = document.getElementById('cabs_trip_type_input') || document.querySelector('input[name="trip_type"]');
  if (hiddenInput) {
    hiddenInput.value = type;
  }

  // Toggle cab trip sections if present
  const sectionIds = ['oneWayFields', 'roundTripFields', 'airportTransferFields', 'localRentalFields'];
  sectionIds.forEach(id => {
    const sec = document.getElementById(id);
    if (sec) {
      sec.style.display = 'none';
      sec.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
    }
  });

  const targetMap = {
    'One Way': 'oneWayFields',
    'Round Trip': 'roundTripFields',
    'Airport Transfer': 'airportTransferFields',
    'Local Rental': 'localRentalFields'
  };
  const targetId = targetMap[type];
  if (targetId) {
    const targetEl = document.getElementById(targetId);
    if (targetEl) {
      targetEl.style.display = 'block';
      targetEl.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
    }
  }
};

window.switchForexTab = function (mode) {
  const buyBtn = document.getElementById('refBuyForexBtn');
  const sellBtn = document.getElementById('refSellForexBtn');
  const typeInput = document.getElementById('refForexTypeInput');
  const submitBtn = document.getElementById('refForexSubmitBtn');

  if (mode === 'buy') {
    if (buyBtn) buyBtn.classList.add('active');
    if (sellBtn) sellBtn.classList.remove('active');
    if (typeInput) typeInput.value = 'Buy Forex';
    if (submitBtn) submitBtn.innerHTML = 'BUY FOREX <i class="fa-solid fa-arrow-right"></i>';
  } else {
    if (sellBtn) sellBtn.classList.add('active');
    if (buyBtn) buyBtn.classList.remove('active');
    if (typeInput) typeInput.value = 'Sell Forex';
    if (submitBtn) submitBtn.innerHTML = 'SELL FOREX <i class="fa-solid fa-arrow-right"></i>';
  }
};

window.selectCabinBox = function (element, cabinName) {
  const parent = element.closest('.cruises-cabin-boxes');
  if (parent) {
    parent.querySelectorAll('.cruises-cabin-box').forEach(b => b.classList.remove('active'));
  }
  element.classList.add('active');
  const radio = element.querySelector('input[type="radio"]');
  if (radio) {
    radio.checked = true;
  }
  const hiddenInput = document.getElementById('selectedCabinTypeInput');
  if (hiddenInput) {
    hiddenInput.value = cabinName;
  }
};

window.togglePassportField = function (hasPassport) {
  const passportGroup = document.getElementById('passportNumberGroup');
  const passportInput = document.getElementById('passportNumberInput');
  if (passportGroup) {
    if (hasPassport === 'Yes') {
      passportGroup.style.display = 'flex';
      if (passportInput) passportInput.setAttribute('required', 'required');
    } else {
      passportGroup.style.display = 'none';
      if (passportInput) {
        passportInput.removeAttribute('required');
        passportInput.value = '';
      }
    }
  }
};




