/**
 * Voyogo Holidays - Main Interactivity Script
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ==========================================
     1. HERO SLIDER LOGIC
     ========================================== */
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  const prevBtn = document.querySelector('.slider-arrow.prev');
  const nextBtn = document.querySelector('.slider-arrow.next');
  let currentSlide = 0;
  let slideInterval;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });
    currentSlide = index;
  }

  function nextSlide() {
    let next = (currentSlide + 1) % slides.length;
    showSlide(next);
  }

  function prevSlide() {
    let prev = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prev);
  }

  function startAutoSlide() {
    slideInterval = setInterval(nextSlide, 5000);
  }

  function stopAutoSlide() {
    clearInterval(slideInterval);
  }

  if (slides.length > 0) {
    if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); stopAutoSlide(); startAutoSlide(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); stopAutoSlide(); startAutoSlide(); });

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        showSlide(i);
        stopAutoSlide();
        startAutoSlide();
      });
    });

    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
      heroSection.addEventListener('mouseenter', stopAutoSlide);
      heroSection.addEventListener('mouseleave', startAutoSlide);
    }

    startAutoSlide();
  }

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
    'Americas': [
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
  const visaData = {
    'ALL': [
      { country: 'Dubai (UAE) Visa', badge: 'E-VISA • 24-48 HRS', badgeClass: 'e-visa', info: 'Passport Front & Back + Photo Only', validity: 'Validity: 30 Days Single Entry', price: '₹6,499/-', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'Bali (Indonesia) Visa', badge: 'E-VOA • INSTANT', badgeClass: 'e-visa', info: 'Quick E-VOA Online Verification', validity: 'Validity: 30 Days (Extendable)', price: '₹3,299/-', bgImage: 'assets/images/voyogo bali .png' },
      { country: 'Thailand E-Visa', badge: 'EXPRESS • 24 HRS', badgeClass: 'express', info: 'Minimal Paperwork & Instant Approval', validity: 'Validity: 15-30 Days Tourist', price: '₹2,899/-', bgImage: 'assets/images/voyogo thailand.png' },
      { country: 'Singapore Visa', badge: 'E-VISA • 3-4 DAYS', badgeClass: 'e-visa', info: 'Authorized Agent Submission', validity: 'Validity: 2 Years Multiple Entry', price: '₹2,499/-', bgImage: 'assets/images/voyogo singapore.png' },
      { country: 'Schengen Europe Visa', badge: 'STICKER • 15 DAYS', badgeClass: 'sticker', info: 'Full VFS Slot + Cover Letter + Itinerary', validity: 'Validity: Up to 90 Days (27 Countries)', price: '₹7,999/-', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'Japan E-Visa', badge: 'E-VISA • 5 DAYS', badgeClass: 'e-visa', info: 'Single Entry Tourist E-Visa', validity: 'Validity: 90 Days (Stay 15 Days)', price: '₹2,199/-', bgImage: 'assets/images/voyogo japan.png' },
      { country: 'Vietnam Visa', badge: 'E-VISA • 3 DAYS', badgeClass: 'e-visa', info: 'Instant Official E-Visa Approval', validity: 'Validity: 30-90 Days Single/Multiple', price: '₹1,999/-', bgImage: 'assets/images/voyogo vietnom.png' },
      { country: 'USA B1/B2 Visa', badge: 'STICKER • INTERVIEW', badgeClass: 'sticker', info: 'DS-160 Form + Appointment Booking', validity: 'Validity: 10 Years Multiple Entry', price: '₹9,999/-', bgImage: 'assets/images/voyogo usa.png' }
    ],
    'E-VISA': [
      { country: 'Dubai (UAE) Visa', badge: 'E-VISA • 24-48 HRS', badgeClass: 'e-visa', info: 'Passport Front & Back + Photo Only', validity: 'Validity: 30 Days Single Entry', price: '₹6,499/-', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'Bali (Indonesia) Visa', badge: 'E-VOA • INSTANT', badgeClass: 'e-visa', info: 'Quick E-VOA Online Verification', validity: 'Validity: 30 Days (Extendable)', price: '₹3,299/-', bgImage: 'assets/images/voyogo bali .png' },
      { country: 'Thailand E-Visa', badge: 'EXPRESS • 24 HRS', badgeClass: 'express', info: 'Minimal Paperwork & Instant Approval', validity: 'Validity: 15-30 Days Tourist', price: '₹2,899/-', bgImage: 'assets/images/voyogo thailand.png' },
      { country: 'Singapore Visa', badge: 'E-VISA • 3-4 DAYS', badgeClass: 'e-visa', info: 'Authorized Agent Submission', validity: 'Validity: 2 Years Multiple Entry', price: '₹2,499/-', bgImage: 'assets/images/voyogo singapore.png' },
      { country: 'Japan E-Visa', badge: 'E-VISA • 5 DAYS', badgeClass: 'e-visa', info: 'Single Entry Tourist E-Visa', validity: 'Validity: 90 Days (Stay 15 Days)', price: '₹2,199/-', bgImage: 'assets/images/voyogo japan.png' },
      { country: 'Vietnam Visa', badge: 'E-VISA • 3 DAYS', badgeClass: 'e-visa', info: 'Instant Official E-Visa Approval', validity: 'Validity: 30-90 Days Single/Multiple', price: '₹1,999/-', bgImage: 'assets/images/voyogo vietnom.png' }
    ],
    'STICKER VISA': [
      { country: 'Schengen Europe Visa', badge: 'STICKER • 15 DAYS', badgeClass: 'sticker', info: 'Full VFS Slot + Cover Letter + Itinerary', validity: 'Validity: Up to 90 Days (27 Countries)', price: '₹7,999/-', bgImage: 'assets/images/voyogo europe.png' },
      { country: 'USA B1/B2 Visa', badge: 'STICKER • INTERVIEW', badgeClass: 'sticker', info: 'DS-160 Form + Appointment Booking', validity: 'Validity: 10 Years Multiple Entry', price: '₹9,999/-', bgImage: 'assets/images/voyogo usa.png' },
      { country: 'UK Tourist Visa', badge: 'STICKER • 15 DAYS', badgeClass: 'sticker', info: 'VFS Biometrics + Document Scan', validity: 'Validity: 6 Months Multiple Entry', price: '₹12,499/-', bgImage: 'assets/images/voyogo swiss paris.png' }
    ],
    'EXPRESS VISA': [
      { country: 'Dubai (UAE) Express', badge: 'EXPRESS • 24 HRS', badgeClass: 'express', info: 'Urgent 24h Express Processing', validity: 'Validity: 30 Days Single Entry', price: '₹7,999/-', bgImage: 'assets/images/voyogo dubai.png' },
      { country: 'Thailand E-Visa', badge: 'EXPRESS • 24 HRS', badgeClass: 'express', info: 'Minimal Paperwork & Instant Approval', validity: 'Validity: 15-30 Days Tourist', price: '₹2,899/-', bgImage: 'assets/images/voyogo thailand.png' },
      { country: 'Vietnam Express Visa', badge: 'EXPRESS • 4 HRS', badgeClass: 'express', info: 'Same Day Emergency E-Visa Approval', validity: 'Validity: 30 Days Single Entry', price: '₹3,499/-', bgImage: 'assets/images/voyogo vietnom.png' }
    ]
  };

  visaTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      visaTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const cat = tab.textContent.trim();
      if (!visaGrid) return;
      const items = visaData[cat] || visaData['ALL'];
      visaGrid.style.opacity = '0';
      setTimeout(() => {
        visaGrid.innerHTML = items.map(item => `
          <div class="visa-card-item" onclick="openEnquiryModal('${item.country}')">
            <div class="visa-card-img" style="background-image: url('${item.bgImage}');">
              <span class="visa-badge ${item.badgeClass}">${item.badge}</span>
            </div>
            <div class="visa-card-body">
              <h3 class="visa-card-title">${item.country}</h3>
              <div class="visa-card-info">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>${item.info}</span>
              </div>
              <div class="visa-card-validity">
                <span>${item.validity}</span>
              </div>
            </div>
            <div class="visa-card-footer">
              <div class="visa-price-box">
                <span class="visa-label-small">Starting @</span>
                <span class="visa-price-val">${item.price}</span>
              </div>
              <button class="btn-apply-visa">Apply Visa</button>
            </div>
          </div>
        `).join('');
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
        group.style.display = 'block';
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



