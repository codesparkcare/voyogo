/**
 * Voyogo Travel Portal - Main Client Logic
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // 1. Swap From and To sectors in Flight Search
  const swapBtn = document.getElementById('swapCitiesBtn');
  if (swapBtn) {
    swapBtn.addEventListener('click', function() {
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

  // 2. Toggle Trip Type (One Way / Round Trip / Multi City)
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

  // 3. Passenger Counter Popup Toggle
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

  // 4. Passenger Counter Logic (+ / - buttons)
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

  // 5. FAQ Accordion Toggle
  const faqQuestions = document.querySelectorAll('.faq-question');
  faqQuestions.forEach(question => {
    question.addEventListener('click', function() {
      const faqItem = this.parentElement;
      faqItem.classList.toggle('active');
    });
  });

  // 6. Login / Register Modal Dialog
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

  // 7. Hotel Filtering Logic (Hotel Page)
  const starFilterInputs = document.querySelectorAll('.star-filter-checkbox');
  const hotelCards = document.querySelectorAll('.hotel-card');

  if (starFilterInputs.length > 0 && hotelCards.length > 0) {
    starFilterInputs.forEach(input => {
      input.addEventListener('change', filterHotels);
    });
  }

  function filterHotels() {
    const checkedStars = Array.from(starFilterInputs)
      .filter(i => i.checked)
      .map(i => parseInt(i.value));

    hotelCards.forEach(card => {
      const cardStar = parseInt(card.getAttribute('data-star') || 0);
      if (checkedStars.length === 0 || checkedStars.includes(cardStar)) {
        card.style.display = 'grid';
      } else {
        card.style.display = 'none';
      }
    });
  }

  // Reset Filters Button
  const resetFiltersBtn = document.getElementById('resetFiltersBtn');
  if (resetFiltersBtn) {
    resetFiltersBtn.addEventListener('click', function() {
      starFilterInputs.forEach(i => i.checked = false);
      filterHotels();
    });
  }
});
