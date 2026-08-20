/**
 * Voyogo Travel Portal - Main Client Logic
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // =========================================================================
  // 1. AIRPORT DATABASE & INTERACTIVE INLINE AUTOCOMPLETE DROPDOWNS
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

  const fromCityBox = document.getElementById('fromCityBox');
  const toCityBox = document.getElementById('toCityBox');
  const fromCityDropdown = document.getElementById('fromCityDropdown');
  const toCityDropdown = document.getElementById('toCityDropdown');
  const passengerDropdown = document.getElementById('passengerDropdown');

  const fromSearchInput = document.getElementById('fromSearchInput');
  const toSearchInput = document.getElementById('toSearchInput');

  const fromPopularPills = document.getElementById('fromPopularPills');
  const toPopularPills = document.getElementById('toPopularPills');

  const fromCityList = document.getElementById('fromCityList');
  const toCityList = document.getElementById('toCityList');

  // Helper to close all dropdowns
  function closeAllDropdowns() {
    if (fromCityDropdown) fromCityDropdown.classList.remove('open');
    if (toCityDropdown) toCityDropdown.classList.remove('open');
    if (passengerDropdown) passengerDropdown.classList.remove('open');
    const hotelCityDropdown = document.getElementById('hotelCityDropdown');
    const hotelGuestDropdown = document.getElementById('hotelGuestDropdown');
    if (hotelCityDropdown) hotelCityDropdown.classList.remove('open');
    if (hotelGuestDropdown) hotelGuestDropdown.classList.remove('open');
  }

  // Global document click listener to dismiss dropdowns
  document.addEventListener('click', function(e) {
    if (fromCityBox && fromCityBox.contains(e.target)) return;
    if (toCityBox && toCityBox.contains(e.target)) return;
    const passengerBox = document.getElementById('passengerSelectBox');
    if (passengerBox && passengerBox.contains(e.target)) return;
    const hotelCityBox = document.getElementById('hotelCityBox');
    if (hotelCityBox && hotelCityBox.contains(e.target)) return;
    const hotelGuestBox = document.getElementById('hotelGuestSelectBox');
    if (hotelGuestBox && hotelGuestBox.contains(e.target)) return;
    closeAllDropdowns();
  });

  // Init FROM Dropdown
  if (fromCityBox && fromCityDropdown) {
    fromCityBox.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = fromCityDropdown.classList.contains('open');
      closeAllDropdowns();
      if (!isOpen) {
        fromCityDropdown.classList.add('open');
        renderPills('from');
        renderList('from', '');
        if (fromSearchInput) {
          fromSearchInput.value = '';
          setTimeout(() => fromSearchInput.focus(), 100);
        }
      }
    });

    if (fromSearchInput) {
      fromSearchInput.addEventListener('click', e => e.stopPropagation());
      fromSearchInput.addEventListener('input', function(e) {
        e.stopPropagation();
        renderList('from', this.value.trim().toLowerCase());
      });
    }
  }

  // Init TO Dropdown
  if (toCityBox && toCityDropdown) {
    toCityBox.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = toCityDropdown.classList.contains('open');
      closeAllDropdowns();
      if (!isOpen) {
        toCityDropdown.classList.add('open');
        renderPills('to');
        renderList('to', '');
        if (toSearchInput) {
          toSearchInput.value = '';
          setTimeout(() => toSearchInput.focus(), 100);
        }
      }
    });

    if (toSearchInput) {
      toSearchInput.addEventListener('click', e => e.stopPropagation());
      toSearchInput.addEventListener('input', function(e) {
        e.stopPropagation();
        renderList('to', this.value.trim().toLowerCase());
      });
    }
  }

  // Render Popular Pills
  function renderPills(target) {
    const container = (target === 'from') ? fromPopularPills : toPopularPills;
    if (!container) return;
    container.innerHTML = '';
    const populars = airportsData.filter(a => a.popular);

    populars.forEach(item => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.style.cssText = 'background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; cursor: pointer; color: #0d3470;';
      btn.innerHTML = `${item.city} <strong>(${item.code})</strong>`;
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        selectCityItem(target, item);
      });
      container.appendChild(btn);
    });
  }

  // Render City List Items
  function renderList(target, query) {
    const container = (target === 'from') ? fromCityList : toCityList;
    const popularSection = (target === 'from') ? document.getElementById('fromPopularSection') : document.getElementById('toPopularSection');

    if (popularSection) {
      popularSection.style.display = query ? 'none' : 'block';
    }

    if (!container) return;
    container.innerHTML = '';

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
      container.innerHTML = `<div style="padding: 14px; font-size: 12px; color: #64748b; text-align: center;"><i class="fa-solid fa-magnifying-glass" style="margin-right:4px;"></i> No airports found for "${query}"</div>`;
      return;
    }

    filtered.forEach(item => {
      const div = document.createElement('div');
      div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; font-size: 12px;';
      
      div.innerHTML = `
        <div>
          <div style="font-weight: 700; color: #09204b; font-size: 13px;">${item.city}, <span style="font-weight: 400; color: #64748b;">${item.country}</span></div>
          <div style="font-size: 11px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 210px;">${item.airport}</div>
        </div>
        <div>
          <span style="background: #fef2f2; color: #ef4444; font-weight: 900; font-size: 12px; padding: 3px 8px; border-radius: 4px; font-family: monospace; border: 1px solid #fca5a5;">${item.code}</span>
        </div>
      `;

      div.addEventListener('mouseover', () => div.style.background = '#f8fafc');
      div.addEventListener('mouseout', () => div.style.background = '#ffffff');
      div.addEventListener('click', (e) => {
        e.stopPropagation();
        selectCityItem(target, item);
      });

      container.appendChild(div);
    });
  }

  // Select City Handler
  function selectCityItem(target, item) {
    const formattedVal = `${item.city} (${item.code})`;
    const subtext = item.airport;

    if (target === 'from') {
      const fromInput = document.getElementById('fromCity');
      const fromText = document.getElementById('fromCityText');
      const fromSub = document.getElementById('fromCitySub');

      if (fromInput) fromInput.value = formattedVal;
      if (fromText) fromText.textContent = formattedVal;
      if (fromSub) fromSub.textContent = subtext;
      if (fromCityDropdown) fromCityDropdown.classList.remove('open');
    } else {
      const toInput = document.getElementById('toCity');
      const toText = document.getElementById('toCityText');
      const toSub = document.getElementById('toCitySub');

      if (toInput) toInput.value = formattedVal;
      if (toText) toText.textContent = formattedVal;
      if (toSub) toSub.textContent = subtext;
      if (toCityDropdown) toCityDropdown.classList.remove('open');
    }
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
  const standardSearchGrid = document.getElementById('standardSearchGrid');
  const multiCitySearchContainer = document.getElementById('multiCitySearchContainer');

  if (tripTypeRadios.length > 0) {
    tripTypeRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const multiInputs = multiCitySearchContainer ? multiCitySearchContainer.querySelectorAll('input') : [];
        if (this.value === 'oneway') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'grid';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'none';
          multiInputs.forEach(inp => inp.disabled = true);
          if (returnDateBox) {
            returnDateBox.style.opacity = '0.5';
            returnDateBox.style.pointerEvents = 'none';
            const returnInput = returnDateBox.querySelector('input');
            if (returnInput) returnInput.disabled = true;
          }
        } else if (this.value === 'roundtrip') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'grid';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'none';
          multiInputs.forEach(inp => inp.disabled = true);
          if (returnDateBox) {
            returnDateBox.style.opacity = '1';
            returnDateBox.style.pointerEvents = 'auto';
            const returnInput = returnDateBox.querySelector('input');
            if (returnInput) returnInput.disabled = false;
          }
        } else if (this.value === 'multicity') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'none';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'block';
          multiInputs.forEach(inp => inp.disabled = false);
        }
      });
    });
  }

  // Multi-City Dynamic Leg Builder
  const addMultiLegBtn = document.getElementById('addMultiLegBtn');
  const multiCityLegsList = document.getElementById('multiCityLegsList');

  if (addMultiLegBtn && multiCityLegsList) {
    addMultiLegBtn.addEventListener('click', function() {
      const legRows = multiCityLegsList.querySelectorAll('.multi-leg-row');
      const count = legRows.length;
      if (count >= 5) {
        alert('Maximum 5 flight legs allowed per Multi-City search.');
        return;
      }
      const newLegIndex = count + 1;
      
      // Default From to previous leg's To
      let defaultFrom = 'Bengaluru (BLR)';
      const lastToInput = legRows[count - 1].querySelector('.multi-to-input');
      if (lastToInput && lastToInput.value) {
        defaultFrom = lastToInput.value;
      }

      const row = document.createElement('div');
      row.className = 'multi-leg-row';
      row.setAttribute('data-leg', newLegIndex);
      row.style.cssText = 'display: grid; grid-template-columns: 2fr 2fr 1.5fr 40px; gap: 12px; align-items: center; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #cbd5e1;';
      
      const futureDate = new Date();
      futureDate.setDate(futureDate.getDate() + 3 + (newLegIndex * 3));
      const dateStr = futureDate.toISOString().split('T')[0];

      row.innerHTML = `
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Flight ${newLegIndex} - From</label>
            <input type="text" class="field-input multi-from-input" name="multi_from[]" value="${defaultFrom}" placeholder="City / Code" required style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">To</label>
            <input type="text" class="field-input multi-to-input" name="multi_to[]" value="Dubai (DXB)" placeholder="City / Code" required style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Departure Date</label>
            <input type="date" class="field-input" name="multi_date[]" value="${dateStr}" required style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 600; background: #ffffff;">
        </div>
        <div style="text-align: center; padding-top: 14px;">
            <button type="button" class="btn-remove-leg" onclick="removeMultiLeg(this)" style="background: none; border: none; color: #ef4444; font-size: 16px; cursor: pointer;" title="Remove Leg"><i class="fa-solid fa-trash-can"></i></button>
        </div>
      `;

      multiCityLegsList.appendChild(row);
    });
  }

  window.removeMultiLeg = function(btn) {
    const row = btn.closest('.multi-leg-row');
    const multiCityLegsList = document.getElementById('multiCityLegsList');
    if (!row || !multiCityLegsList) return;

    const legRows = multiCityLegsList.querySelectorAll('.multi-leg-row');
    if (legRows.length <= 2) {
      alert('Multi-City search requires at least 2 flight legs.');
      return;
    }

    row.remove();
    const remaining = multiCityLegsList.querySelectorAll('.multi-leg-row');
    remaining.forEach((r, idx) => {
      const legNum = idx + 1;
      r.setAttribute('data-leg', legNum);
      const labels = r.querySelectorAll('label');
      if (labels.length > 0) {
        labels[0].textContent = `Flight ${legNum} - From`;
      }
    });
  };

  // =========================================================================
  // 4. PASSENGER COUNTER DROPDOWN LOGIC
  // =========================================================================
  const passengerBox = document.getElementById('passengerSelectBox');
  if (passengerBox && passengerDropdown) {
    passengerBox.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = passengerDropdown.classList.contains('open');
      closeAllDropdowns();
      if (!isOpen) passengerDropdown.classList.add('open');
    });

    passengerDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
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

  const hiddenAdults = document.getElementById('hiddenAdults');
  const hiddenChildren = document.getElementById('hiddenChildren');
  const hiddenInfants = document.getElementById('hiddenInfants');
  const hiddenCabinClass = document.getElementById('hiddenCabinClass');

  window.updatePassengers = function(type, change) {
    if (type === 'adult') {
      adults = Math.max(1, adults + change);
      if (adultValEl) adultValEl.textContent = adults;
      if (hiddenAdults) hiddenAdults.value = adults;
    } else if (type === 'child') {
      children = Math.max(0, children + change);
      if (childValEl) childValEl.textContent = children;
      if (hiddenChildren) hiddenChildren.value = children;
    } else if (type === 'infant') {
      infants = Math.max(0, Math.min(adults, infants + change));
      if (infantValEl) infantValEl.textContent = infants;
      if (hiddenInfants) hiddenInfants.value = infants;
    }
    updateSummaryText();
  };

  const classSelect = document.getElementById('cabinClassSelect');
  if (classSelect) {
    classSelect.addEventListener('click', function(e) {
      e.stopPropagation();
    });
    classSelect.addEventListener('change', function(e) {
      e.stopPropagation();
      travelClass = this.value;
      if (hiddenCabinClass) hiddenCabinClass.value = travelClass;
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

  // =========================================================================
  // 7. HOTEL SEARCH PAGE DROPDOWNS (DESTINATION & ROOMS/GUESTS)
  // =========================================================================
  const hotelDestinationsData = [
    { city: "Goa", state: "Goa", country: "India", subtext: "Popular: Baga Beach, Calangute, Panjim", popular: true },
    { city: "Mumbai", state: "Maharashtra", country: "India", subtext: "Popular: Marine Drive, Juhu, Bandra", popular: true },
    { city: "Delhi NCR", state: "Delhi", country: "India", subtext: "Popular: Connaught Place, South Delhi, Aerocity", popular: true },
    { city: "Jaipur", state: "Rajasthan", country: "India", subtext: "Popular: Pink City, Amer, Malviya Nagar", popular: true },
    { city: "Dubai", state: "Dubai", country: "UAE", subtext: "Popular: Downtown, Marina, Palm Jumeirah", popular: true },
    { city: "Maldives", state: "Male", country: "Maldives", subtext: "Popular: North Male Atoll, South Male Atoll", popular: true },
    { city: "Udaipur", state: "Rajasthan", country: "India", subtext: "Popular: Lake Pichola, Fatehsagar, Old City", popular: true },
    { city: "Bengaluru", state: "Karnataka", country: "India", subtext: "Popular: Indiranagar, MG Road, Koramangala", popular: true },
    { city: "Shimla", state: "Himachal Pradesh", country: "India", subtext: "Popular: Mall Road, Kufri, Chotta Shimla", popular: false },
    { city: "Manali", state: "Himachal Pradesh", country: "India", subtext: "Popular: Mall Road, Solang Valley, Old Manali", popular: false },
    { city: "Agra", state: "Uttar Pradesh", country: "India", subtext: "Popular: Taj Ganj, Fatehabad Road", popular: false },
    { city: "Varanasi", state: "Uttar Pradesh", country: "India", subtext: "Popular: Ghats, Cantt, Assi Ghat", popular: false },
    { city: "Singapore", state: "Singapore", country: "Singapore", subtext: "Popular: Marina Bay, Orchard Road, Sentosa", popular: false },
    { city: "Bangkok", state: "Bangkok", country: "Thailand", subtext: "Popular: Sukhumvit, Siam, Silom", popular: false }
  ];

  const hotelCityBox = document.getElementById('hotelCityBox');
  const hotelCityDropdown = document.getElementById('hotelCityDropdown');
  const hotelSearchInput = document.getElementById('hotelSearchInput');
  const hotelPopularPills = document.getElementById('hotelPopularPills');
  const hotelCityList = document.getElementById('hotelCityList');

  if (hotelCityBox && hotelCityDropdown) {
    hotelCityBox.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = hotelCityDropdown.classList.contains('open');
      closeAllDropdowns();
      if (!isOpen) {
        hotelCityDropdown.classList.add('open');
        renderHotelPills();
        renderHotelList('');
        if (hotelSearchInput) {
          hotelSearchInput.value = '';
          setTimeout(() => hotelSearchInput.focus(), 100);
        }
      }
    });

    if (hotelSearchInput) {
      hotelSearchInput.addEventListener('click', e => e.stopPropagation());
      hotelSearchInput.addEventListener('input', function(e) {
        e.stopPropagation();
        renderHotelList(this.value.trim().toLowerCase());
      });
    }
  }

  function renderHotelPills() {
    if (!hotelPopularPills) return;
    hotelPopularPills.innerHTML = '';
    const populars = hotelDestinationsData.filter(item => item.popular);
    populars.forEach(item => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.style.cssText = 'background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; cursor: pointer; color: #0d3470;';
      btn.innerHTML = `${item.city}`;
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        selectHotelDestination(item);
      });
      hotelPopularPills.appendChild(btn);
    });
  }

  function renderHotelList(query) {
    const popularSection = document.getElementById('hotelPopularSection');
    if (popularSection) popularSection.style.display = query ? 'none' : 'block';
    if (!hotelCityList) return;
    hotelCityList.innerHTML = '';

    const filtered = hotelDestinationsData.filter(item => {
      if (!query) return true;
      return (
        item.city.toLowerCase().includes(query) ||
        item.state.toLowerCase().includes(query) ||
        item.country.toLowerCase().includes(query) ||
        item.subtext.toLowerCase().includes(query)
      );
    });

    if (filtered.length === 0) {
      const customDiv = document.createElement('div');
      customDiv.style.cssText = 'padding: 10px 12px; cursor: pointer; font-size: 12px; background: #f0fdf4; color: #166534; font-weight: 700; border-radius: 4px; margin: 4px;';
      customDiv.innerHTML = `<i class="fa-solid fa-location-dot" style="margin-right: 6px;"></i> Use "${query}"`;
      customDiv.addEventListener('click', (e) => {
        e.stopPropagation();
        selectHotelDestination({
          city: query.charAt(0).toUpperCase() + query.slice(1),
          country: '',
          subtext: `Hotels in ${query}`
        });
      });
      hotelCityList.appendChild(customDiv);
      return;
    }

    filtered.forEach(item => {
      const div = document.createElement('div');
      div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; font-size: 12px;';
      const locStr = item.country ? `${item.city}, ${item.country}` : item.city;
      div.innerHTML = `
        <div>
          <div style="font-weight: 700; color: #09204b; font-size: 13px;"><i class="fa-solid fa-location-dot" style="color: #fa3a3a; margin-right: 4px;"></i> ${locStr}</div>
          <div style="font-size: 11px; color: #64748b;">${item.subtext}</div>
        </div>
      `;

      div.addEventListener('mouseover', () => div.style.background = '#f8fafc');
      div.addEventListener('mouseout', () => div.style.background = '#ffffff');
      div.addEventListener('click', (e) => {
        e.stopPropagation();
        selectHotelDestination(item);
      });
      hotelCityList.appendChild(div);
    });
  }

  function selectHotelDestination(item) {
    const formattedVal = item.country ? `${item.city}, ${item.country}` : item.city;
    const hotelCityInput = document.getElementById('hotelCityInput');
    const hotelCityText = document.getElementById('hotelCityText');
    const hotelCitySub = document.getElementById('hotelCitySub');

    if (hotelCityInput) hotelCityInput.value = formattedVal;
    if (hotelCityText) hotelCityText.textContent = formattedVal;
    if (hotelCitySub) hotelCitySub.textContent = item.subtext;
    if (hotelCityDropdown) hotelCityDropdown.classList.remove('open');
  }

  // Hotel Guest & Room Counter Logic
  const hotelGuestSelectBox = document.getElementById('hotelGuestSelectBox');
  const hotelGuestDropdown = document.getElementById('hotelGuestDropdown');

  if (hotelGuestSelectBox && hotelGuestDropdown) {
    hotelGuestSelectBox.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = hotelGuestDropdown.classList.contains('open');
      closeAllDropdowns();
      if (!isOpen) hotelGuestDropdown.classList.add('open');
    });

    hotelGuestDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }

  let hotelRooms = 1;
  let hotelAdults = 2;
  let hotelChildren = 0;

  const hotelRoomsCountEl = document.getElementById('hotelRoomsCount');
  const hotelAdultsCountEl = document.getElementById('hotelAdultsCount');
  const hotelChildrenCountEl = document.getElementById('hotelChildrenCount');
  const hotelGuestSummaryEl = document.getElementById('hotelGuestSummary');
  const hotelGuestSubEl = document.getElementById('hotelGuestSub');

  const hiddenHotelRooms = document.getElementById('hiddenHotelRooms');
  const hiddenHotelAdults = document.getElementById('hiddenHotelAdults');
  const hiddenHotelChildren = document.getElementById('hiddenHotelChildren');

  window.updateHotelGuests = function(type, change) {
    if (type === 'rooms') {
      hotelRooms = Math.max(1, Math.min(10, hotelRooms + change));
      if (hotelRoomsCountEl) hotelRoomsCountEl.textContent = hotelRooms;
      if (hiddenHotelRooms) hiddenHotelRooms.value = hotelRooms;
    } else if (type === 'adults') {
      hotelAdults = Math.max(1, Math.min(30, hotelAdults + change));
      if (hotelAdultsCountEl) hotelAdultsCountEl.textContent = hotelAdults;
      if (hiddenHotelAdults) hiddenHotelAdults.value = hotelAdults;
    } else if (type === 'children') {
      hotelChildren = Math.max(0, Math.min(10, hotelChildren + change));
      if (hotelChildrenCountEl) hotelChildrenCountEl.textContent = hotelChildren;
      if (hiddenHotelChildren) hiddenHotelChildren.value = hotelChildren;
    }
    updateHotelGuestSummary();
  };

  function updateHotelGuestSummary() {
    const totalGuests = hotelAdults + hotelChildren;
    const roomStr = `${hotelRooms} Room${hotelRooms > 1 ? 's' : ''}`;
    const guestStr = `${totalGuests} Guest${totalGuests > 1 ? 's' : ''}`;
    
    if (hotelGuestSummaryEl) {
      hotelGuestSummaryEl.textContent = `${roomStr}, ${guestStr}`;
    }
    
    if (hotelGuestSubEl) {
      const adultText = `${hotelAdults} Adult${hotelAdults > 1 ? 's' : ''}`;
      const childText = `${hotelChildren} Child${hotelChildren !== 1 ? 'ren' : ''}`;
      hotelGuestSubEl.textContent = `${adultText}, ${childText}`;
    }
  }

});
