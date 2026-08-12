/**
 * Voyogo Travel Portal - Main Client Logic
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // =========================================================================
  // 1. AIRPORT DATABASE & INTERACTIVE AUTOCOMPLETE MODAL
  // =========================================================================
  const airportsData = [
    { city: "Delhi", code: "DEL", airport: "Indira Gandhi Intl Airport", country: "India", popular: true },
    { city: "Mumbai", code: "BOM", airport: "Chhatrapati Shivaji Maharaj Intl", country: "India", popular: true },
    { city: "Bengaluru", code: "BLR", airport: "Kempegowda Intl Airport", country: "India", popular: true },
    { city: "Goa (Dabolim)", code: "GOI", airport: "Dabolim Airport", country: "India", popular: true },
    { city: "Goa (Mopa)", code: "GOX", airport: "Manohar International Airport", country: "India", popular: true },
    { city: "Dubai", code: "DXB", airport: "Dubai International Airport", country: "UAE", popular: true },
    { city: "London", code: "LHR", airport: "Heathrow Airport", country: "UK", popular: true },
    { city: "Singapore", code: "SIN", airport: "Changi Airport", country: "Singapore", popular: true },
    { city: "Hyderabad", code: "HYD", airport: "Rajiv Gandhi Intl Airport", country: "India", popular: true },
    { city: "Chennai", code: "MAA", airport: "Chennai International Airport", country: "India", popular: true },
    { city: "Kolkata", code: "CCU", airport: "Netaji Subhash Chandra Bose Intl", country: "India", popular: false },
    { city: "Ahmedabad", code: "AMD", airport: "Sardar Vallabhbhai Patel Intl", country: "India", popular: false },
    { city: "Kochi", code: "COK", airport: "Cochin International Airport", country: "India", popular: false },
    { city: "Pune", code: "PNQ", airport: "Pune Airport", country: "India", popular: false },
    { city: "Jaipur", code: "JAI", airport: "Jaipur International Airport", country: "India", popular: false },
    { city: "Lucknow", code: "LKO", airport: "Chaudhary Charan Singh Intl", country: "India", popular: false },
    { city: "Chandigarh", code: "IXC", airport: "Shaheed Bhagat Singh Intl", country: "India", popular: false },
    { city: "Srinagar", code: "SXR", airport: "Sheikh ul-Alam Intl Airport", country: "India", popular: false },
    { city: "Bangkok", code: "BKK", airport: "Suvarnabhumi Airport", country: "Thailand", popular: false },
    { city: "New York", code: "JFK", airport: "John F. Kennedy Intl Airport", country: "USA", popular: false },
    { city: "Kuala Lumpur", code: "KUL", airport: "Kuala Lumpur Intl Airport", country: "Malaysia", popular: false },
    { city: "Kathmandu", code: "KTM", airport: "Tribhuvan International Airport", country: "Nepal", popular: false }
  ];

  let currentTargetSector = 'from'; // 'from' or 'to'

  const fromCityBox = document.getElementById('fromCityBox');
  const toCityBox = document.getElementById('toCityBox');
  const airportModalOverlay = document.getElementById('airportModalOverlay');
  const closeAirportModal = document.getElementById('closeAirportModal');
  const airportModalTitle = document.getElementById('airportModalTitle');
  const airportSearchInput = document.getElementById('airportSearchInput');
  const popularAirportsPills = document.getElementById('popularAirportsPills');
  const airportsListContainer = document.getElementById('airportsListContainer');

  if (airportModalOverlay) {
    // Open FROM Modal
    if (fromCityBox) {
      fromCityBox.addEventListener('click', function() {
        currentTargetSector = 'from';
        if (airportModalTitle) airportModalTitle.textContent = 'Select Departure Airport (FROM)';
        openAirportModal();
      });
    }

    // Open TO Modal
    if (toCityBox) {
      toCityBox.addEventListener('click', function() {
        currentTargetSector = 'to';
        if (airportModalTitle) airportModalTitle.textContent = 'Select Destination Airport (TO)';
        openAirportModal();
      });
    }

    // Close Modal
    if (closeAirportModal) {
      closeAirportModal.addEventListener('click', closeAirportModalFn);
    }
    airportModalOverlay.addEventListener('click', function(e) {
      if (e.target === airportModalOverlay) closeAirportModalFn();
    });

    // Real-Time Search Filter
    if (airportSearchInput) {
      airportSearchInput.addEventListener('input', function() {
        renderAirportsList(this.value.trim().toLowerCase());
      });
    }
  }

  function openAirportModal() {
    airportModalOverlay.classList.add('open');
    if (airportSearchInput) {
      airportSearchInput.value = '';
      setTimeout(() => airportSearchInput.focus(), 100);
    }
    renderPopularPills();
    renderAirportsList('');
  }

  function closeAirportModalFn() {
    airportModalOverlay.classList.remove('open');
  }

  function renderPopularPills() {
    if (!popularAirportsPills) return;
    popularAirportsPills.innerHTML = '';
    const populars = airportsData.filter(a => a.popular);
    populars.forEach(item => {
      const pill = document.createElement('button');
      pill.type = 'button';
      pill.className = 'popular-pill-btn';
      pill.style.cssText = 'background: #f1f5f9; border: 1px solid #cbd5e1; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; color: #0d3470;';
      pill.innerHTML = `<i class="fa-solid fa-plane-up" style="color:#ef4444; margin-right:4px;"></i> ${item.city} <strong>(${item.code})</strong>`;
      pill.addEventListener('click', () => selectAirport(item));
      popularAirportsPills.appendChild(pill);
    });
  }

  function renderAirportsList(query) {
    if (!airportsListContainer) return;
    airportsListContainer.innerHTML = '';

    const filtered = airportsData.filter(item => {
      if (!query) return true;
      return (
        item.city.toLowerCase().includes(query) ||
        item.code.toLowerCase().includes(query) ||
        item.airport.toLowerCase().includes(query) ||
        item.country.toLowerCase().includes(query)
      );
    });

    if (filtered.length === 0) {
      airportsListContainer.innerHTML = `
        <div style="padding: 24px; text-align: center; color: #64748b;">
          <i class="fa-solid fa-plane-slash" style="font-size: 24px; color: #ef4444; margin-bottom: 8px;"></i>
          <p style="margin: 0; font-size: 13px;">No airports found matching "<strong>${query}</strong>"</p>
        </div>`;
      return;
    }

    filtered.forEach(item => {
      const div = document.createElement('div');
      div.className = 'airport-item-row';
      div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.15s;';
      
      div.innerHTML = `
        <div style="display: flex; align-items: center; gap: 14px;">
          <div style="width: 36px; height: 36px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
            <i class="fa-solid fa-plane"></i>
          </div>
          <div>
            <div style="font-weight: 700; font-size: 15px; color: #09204b;">${item.city}, <span style="font-weight: 400; color: #64748b; font-size: 13px;">${item.country}</span></div>
            <div style="font-size: 12px; color: #64748b;">${item.airport}</div>
          </div>
        </div>
        <div>
          <span style="background: #fef2f2; color: #ef4444; font-weight: 900; font-size: 14px; padding: 4px 10px; border-radius: 6px; border: 1px solid #fca5a5; font-family: monospace;">${item.code}</span>
        </div>
      `;

      div.addEventListener('mouseover', () => div.style.background = '#f8fafc');
      div.addEventListener('mouseout', () => div.style.background = '#ffffff');
      div.addEventListener('click', () => selectAirport(item));

      airportsListContainer.appendChild(div);
    });
  }

  function selectAirport(item) {
    const formattedVal = `${item.city} (${item.code})`;
    const subtext = item.airport;

    if (currentTargetSector === 'from') {
      const fromInput = document.getElementById('fromCity');
      const fromText = document.getElementById('fromCityText');
      const fromSub = document.getElementById('fromCitySub');

      if (fromInput) fromInput.value = formattedVal;
      if (fromText) fromText.textContent = formattedVal;
      if (fromSub) fromSub.textContent = subtext;
    } else {
      const toInput = document.getElementById('toCity');
      const toText = document.getElementById('toCityText');
      const toSub = document.getElementById('toCitySub');

      if (toInput) toInput.value = formattedVal;
      if (toText) toText.textContent = formattedVal;
      if (toSub) toSub.textContent = subtext;
    }

    closeAirportModalFn();
  }

  // =========================================================================
  // 2. SWAP FROM AND TO SECTORS
  // =========================================================================
  const swapBtn = document.getElementById('swapCitiesBtn');
  if (swapBtn) {
    swapBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      const fromInput = document.getElementById('fromCity');
      const toInput = document.getElementById('toCity');
      const fromText = document.getElementById('fromCityText');
      const toText = document.getElementById('toCityText');
      const fromSub = document.getElementById('fromCitySub');
      const toSub = document.getElementById('toCitySub');

      if (fromInput && toInput) {
        const tempVal = fromInput.value;
        fromInput.value = toInput.value;
        toInput.value = tempVal;
      }
      if (fromText && toText) {
        const tempText = fromText.textContent;
        fromText.textContent = toText.textContent;
        toText.textContent = tempText;
      }
      if (fromSub && toSub) {
        const tempSub = fromSub.textContent;
        fromSub.textContent = toSub.textContent;
        toSub.textContent = tempSub;
      }
    });
  }

  // =========================================================================
  // 3. TRIP TYPE TOGGLE (One Way / Round Trip / Multi City)
  // =========================================================================
  const tripTypeRadios = document.querySelectorAll('input[name="tripType"]');
  const returnDateBox = document.getElementById('returnDateBox');
  if (tripTypeRadios.length > 0 && returnDateBox) {
    tripTypeRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        if (this.value === 'oneway') {
          returnDateBox.style.opacity = '0.5';
          returnDateBox.style.pointerEvents = 'none';
          const returnInput = returnDateBox.querySelector('input');
          if (returnInput) returnInput.disabled = true;
        } else {
          returnDateBox.style.opacity = '1';
          returnDateBox.style.pointerEvents = 'auto';
          const returnInput = returnDateBox.querySelector('input');
          if (returnInput) returnInput.disabled = false;
        }
      });
    });
  }

  // =========================================================================
  // 4. PASSENGER COUNTER DROPDOWN LOGIC
  // =========================================================================
  const passengerBox = document.getElementById('passengerSelectBox');
  const passengerDropdown = document.getElementById('passengerDropdown');
  if (passengerBox && passengerDropdown) {
    passengerBox.addEventListener('click', function(e) {
      e.stopPropagation();
      passengerDropdown.classList.toggle('open');
    });

    document.addEventListener('click', function(e) {
      if (!passengerDropdown.contains(e.target) && !passengerBox.contains(e.target)) {
        passengerDropdown.classList.remove('open');
      }
    });
  }

  let adults = 1;
  let children = 0;
  let infants = 0;
  let travelClass = 'Economy';

  const adultValEl = document.getElementById('adultCount');
  const childValEl = document.getElementById('childCount');
  const infantValEl = document.getElementById('infantCount');
  const passengerSummaryEl = document.getElementById('passengerSummary');

  window.updatePassengers = function(type, change) {
    if (type === 'adult') {
      adults = Math.max(1, adults + change);
      if (adultValEl) adultValEl.textContent = adults;
    } else if (type === 'child') {
      children = Math.max(0, children + change);
      if (childValEl) childValEl.textContent = children;
    } else if (type === 'infant') {
      infants = Math.max(0, Math.min(adults, infants + change));
      if (infantValEl) infantValEl.textContent = infants;
    }
    updateSummaryText();
  };

  const classSelect = document.getElementById('cabinClassSelect');
  if (classSelect) {
    classSelect.addEventListener('change', function() {
      travelClass = this.value;
      updateSummaryText();
    });
  }

  function updateSummaryText() {
    const total = adults + children + infants;
    if (passengerSummaryEl) {
      passengerSummaryEl.textContent = `${total} Traveler${total > 1 ? 's' : ''}, ${travelClass}`;
    }
  }

  // =========================================================================
  // 5. FAQ ACCORDION
  // =========================================================================
  const faqQuestions = document.querySelectorAll('.faq-question');
  faqQuestions.forEach(question => {
    question.addEventListener('click', function() {
      const faqItem = this.parentElement;
      faqItem.classList.toggle('active');
    });
  });

  // =========================================================================
  // 6. LOGIN / REGISTER MODAL DIALOG
  // =========================================================================
  const loginModalBtn = document.getElementById('loginModalBtn');
  const loginModal = document.getElementById('loginModal');
  const closeLoginModal = document.getElementById('closeLoginModal');

  if (loginModalBtn && loginModal) {
    loginModalBtn.addEventListener('click', function() {
      loginModal.classList.add('open');
    });
  }

  if (closeLoginModal && loginModal) {
    closeLoginModal.addEventListener('click', function() {
      loginModal.classList.remove('open');
    });

    loginModal.addEventListener('click', function(e) {
      if (e.target === loginModal) {
        loginModal.classList.remove('open');
      }
    });
  }

});
