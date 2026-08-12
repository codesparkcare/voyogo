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
  }

  // Global document click listener to dismiss dropdowns
  document.addEventListener('click', function(e) {
    if (fromCityBox && fromCityBox.contains(e.target)) return;
    if (toCityBox && toCityBox.contains(e.target)) return;
    const passengerBox = document.getElementById('passengerSelectBox');
    if (passengerBox && passengerBox.contains(e.target)) return;
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
        if (this.value === 'oneway') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'grid';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'none';
          if (returnDateBox) {
            returnDateBox.style.opacity = '0.5';
            returnDateBox.style.pointerEvents = 'none';
            const returnInput = returnDateBox.querySelector('input');
            if (returnInput) returnInput.disabled = true;
          }
        } else if (this.value === 'roundtrip') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'grid';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'none';
          if (returnDateBox) {
            returnDateBox.style.opacity = '1';
            returnDateBox.style.pointerEvents = 'auto';
            const returnInput = returnDateBox.querySelector('input');
            if (returnInput) returnInput.disabled = false;
          }
        } else if (this.value === 'multicity') {
          if (standardSearchGrid) standardSearchGrid.style.display = 'none';
          if (multiCitySearchContainer) multiCitySearchContainer.style.display = 'block';
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

});
