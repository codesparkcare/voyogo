<!-- Hero Flight Search Section -->
<section class="hero-section">
    <!-- Hero Background Slider -->
    <div class="hero-slider-wrap">
        <div class="hero-slider-track" id="heroSliderTrack">
            <div class="hero-slide">
                <img src="<?php echo base_url('assets/images/flight-banner/banner1.png'); ?>" alt="Flight Banner 1" class="hero-slide-img">
            </div>
            <div class="hero-slide">
                <img src="<?php echo base_url('assets/images/flight-banner/banner2.png'); ?>" alt="Flight Banner 2" class="hero-slide-img">
            </div>
            <div class="hero-slide">
                <img src="<?php echo base_url('assets/images/flight-banner/banner3.png'); ?>" alt="Flight Banner 3" class="hero-slide-img">
            </div>
            <!-- Seamless infinite loop clone of Banner 1 -->
            <div class="hero-slide">
                <img src="<?php echo base_url('assets/images/flight-banner/banner1.png'); ?>" alt="Flight Banner 1 Loop" class="hero-slide-img">
            </div>
        </div>
        
        <!-- Darker Gradient Overlay for contrast and readability -->
        <div class="hero-slider-overlay"></div>
    </div>

    <!-- Slider Controls: Dots & Arrows -->
    <div class="hero-slider-controls">
        <button type="button" class="hero-slider-arrow prev" id="heroSliderPrev" aria-label="Previous Slide">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" class="hero-slider-arrow next" id="heroSliderNext" aria-label="Next Slide">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
        <div class="hero-slider-dots" id="heroSliderDots">
            <button type="button" class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
            <button type="button" class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
            <button type="button" class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
        </div>
    </div>

    <!-- Hero Background Auto-Scroll Script -->
    <script>
    (function initHeroSliderDirect() {
        function runSlider() {
            var track = document.getElementById('heroSliderTrack');
            var dots = document.querySelectorAll('.hero-dot');
            var prevBtn = document.getElementById('heroSliderPrev');
            var nextBtn = document.getElementById('heroSliderNext');
            if (!track || window.__heroSliderRunning) return;
            window.__heroSliderRunning = true;

            var currentSlide = 0;
            var isTransitioning = false;
            var realSlidesCount = 3;
            var totalSlidesCount = 4;
            var slideInterval = 3500; // 3.5s auto-scroll
            var timer = null;

            function updateDots(idx) {
                var activeIndex = idx % realSlidesCount;
                for (var i = 0; i < dots.length; i++) {
                    if (i === activeIndex) {
                        dots[i].classList.add('active');
                    } else {
                        dots[i].classList.remove('active');
                    }
                }
            }

            function moveToSlide(idx, animated) {
                if (animated) {
                    track.style.transition = 'transform 0.85s cubic-bezier(0.25, 1, 0.5, 1)';
                } else {
                    track.style.transition = 'none';
                }
                currentSlide = idx;
                var offset = -(currentSlide * (100 / totalSlidesCount));
                track.style.transform = 'translateX(' + offset + '%)';
                updateDots(currentSlide);
            }

            function nextSlide() {
                if (isTransitioning) return;
                isTransitioning = true;
                moveToSlide(currentSlide + 1, true);
            }

            function prevSlide() {
                if (isTransitioning) return;
                isTransitioning = true;
                if (currentSlide <= 0) {
                    moveToSlide(totalSlidesCount - 1, false);
                    void track.offsetWidth;
                    moveToSlide(realSlidesCount - 1, true);
                } else {
                    moveToSlide(currentSlide - 1, true);
                }
            }

            track.addEventListener('transitionend', function() {
                isTransitioning = false;
                if (currentSlide >= totalSlidesCount - 1) {
                    moveToSlide(0, false);
                    void track.offsetWidth;
                }
            });

            function startTimer() {
                stopTimer();
                timer = setInterval(nextSlide, slideInterval);
            }

            function stopTimer() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            for (var d = 0; d < dots.length; d++) {
                (function(index) {
                    dots[index].addEventListener('click', function(e) {
                        e.preventDefault();
                        if (isTransitioning) return;
                        isTransitioning = true;
                        moveToSlide(index, true);
                        startTimer();
                    });
                })(d);
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                    startTimer();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                    startTimer();
                });
            }

            // Start auto-scrolling immediately
            startTimer();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', runSlider);
        } else {
            runSlider();
        }
    })();
    </script>

    <div class="container" style="position: relative; z-index: 10;">
        
        <div class="hero-headline">
            <h1>Book Flight Tickets at <span>Lowest Airfares</span></h1>
            <p>Compare 500+ domestic & international airlines with zero hidden convenience fees</p>
        </div>

        <!-- Flight Search Widget Card -->
        <div class="search-card">
            
            <!-- Trip Type Selector -->
            <div class="search-tabs">
                <div class="trip-type-options">
                    <label class="radio-custom">
                        <input type="radio" name="tripType" value="oneway" checked>
                        <span>One Way</span>
                    </label>
                    <label class="radio-custom">
                        <input type="radio" name="tripType" value="roundtrip">
                        <span>Round Trip</span>
                    </label>
                    <label class="radio-custom">
                        <input type="radio" name="tripType" value="multicity">
                        <span>Multi-City</span>
                    </label>
                </div>

                <div class="fare-type-tags">
                    <span class="fare-tag active">Regular Fares</span>
                    <span class="fare-tag"><i class="fa-solid fa-graduation-cap"></i> Student Fares</span>
                    <span class="fare-tag"><i class="fa-solid fa-person-military-pointing"></i> Armed Forces</span>
                    <span class="fare-tag"><i class="fa-solid fa-person-cane"></i> Senior Citizen</span>
                </div>
            </div>

            <!-- Search Inputs Form -->
            <form action="<?php echo function_exists('site_url') ? site_url('flight/search') : '#'; ?>" method="POST" id="flightSearchForm">
                <input type="hidden" name="tripType" id="hiddenTripType" value="oneway">
                <div class="search-grid" id="standardSearchGrid">
                    
                    <!-- From & To Group with Centered Swap Button -->
                    <div class="from-to-group">
                        <!-- From City -->
                        <div class="input-box" id="fromCityBox" style="cursor: pointer; position: relative;">
                            <div class="input-label"><i class="fa-solid fa-plane-departure"></i> From</div>
                            <div class="input-val" id="fromCityText">Delhi (DEL)</div>
                            <input type="hidden" name="from_city" id="fromCity" value="Delhi (DEL)">
                            <div class="input-subtext" id="fromCitySub">Indira Gandhi Intl Airport</div>

                            <!-- Inline Autocomplete Dropdown Popup -->
                            <div class="dropdown-popup city-autocomplete-popup" id="fromCityDropdown" style="width: 340px; padding: 14px; border-radius: 12px; box-shadow: 0 12px 35px rgba(0,0,0,0.3); background: #ffffff; text-align: left; z-index: 99999;">
                                <div style="position: relative; margin-bottom: 10px;" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 10px; color: #0d3470; font-size: 13px;"></i>
                                    <input type="text" class="city-search-input" id="fromSearchInput" placeholder="Type city or airport (e.g. GOX, Delhi, BOM)..." style="width: 100%; padding: 7px 12px 7px 32px; border: 1.5px solid #0d3470; border-radius: 6px; font-size: 13px; font-weight: 600; outline: none;">
                                </div>
                                <div id="fromPopularSection">
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">Popular Destinations</div>
                                    <div class="popular-pills-wrap" id="fromPopularPills" style="display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 10px;"></div>
                                </div>
                                <div class="city-list-wrap" id="fromCityList" style="max-height: 220px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px;"></div>
                            </div>
                        </div>

                        <!-- Swap Cities Button -->
                        <button type="button" class="swap-btn" id="swapCitiesBtn" title="Swap Cities">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        </button>

                        <!-- To City -->
                        <div class="input-box" id="toCityBox" style="cursor: pointer; position: relative;">
                            <div class="input-label"><i class="fa-solid fa-plane-arrival"></i> To</div>
                            <div class="input-val" id="toCityText">Mumbai (BOM)</div>
                            <input type="hidden" name="to_city" id="toCity" value="Mumbai (BOM)">
                            <div class="input-subtext" id="toCitySub">Chhatrapati Shivaji Maharaj Intl</div>

                            <!-- Inline Autocomplete Dropdown Popup -->
                            <div class="dropdown-popup city-autocomplete-popup" id="toCityDropdown" style="width: 340px; padding: 14px; border-radius: 12px; box-shadow: 0 12px 35px rgba(0,0,0,0.3); background: #ffffff; text-align: left; z-index: 99999;">
                                <div style="position: relative; margin-bottom: 10px;" onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 10px; color: #0d3470; font-size: 13px;"></i>
                                    <input type="text" class="city-search-input" id="toSearchInput" placeholder="Type city or airport (e.g. GOX, Mumbai, GOI)..." style="width: 100%; padding: 7px 12px 7px 32px; border: 1.5px solid #0d3470; border-radius: 6px; font-size: 13px; font-weight: 600; outline: none;">
                                </div>
                                <div id="toPopularSection">
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">Popular Destinations</div>
                                    <div class="popular-pills-wrap" id="toPopularPills" style="display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 10px;"></div>
                                </div>
                                <div class="city-list-wrap" id="toCityList" style="max-height: 220px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Departure Date -->
                    <div class="input-box" id="departureDateBox" style="position: relative; cursor: pointer;">
                        <div class="input-label"><i class="fa-solid fa-calendar-days"></i> Departure</div>
                        <div class="input-val" id="departureDateDisplay" style="display: flex; align-items: center; justify-content: space-between;">
                            <span id="depDateValText"><?php echo date('d/m/Y', strtotime('+3 days')); ?></span>
                            <i class="fa-regular fa-calendar-days" style="font-size: 15px; color: #64748b;"></i>
                        </div>
                        <input type="hidden" name="departure_date" id="departureDateInput" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                        <div class="input-subtext" id="departureDateSub"><?php echo date('D, d M Y', strtotime('+3 days')); ?></div>
                    </div>

                    <!-- Return Date -->
                    <div class="input-box" id="returnDateBox" style="position: relative; cursor: pointer; opacity: 0.5;">
                        <div class="input-label"><i class="fa-solid fa-calendar-days"></i> Return</div>
                        <div class="input-val" id="returnDateDisplay" style="display: flex; align-items: center; justify-content: space-between;">
                            <span id="retDateValText"><?php echo date('d/m/Y', strtotime('+7 days')); ?></span>
                            <i class="fa-regular fa-calendar-days" style="font-size: 15px; color: #64748b;"></i>
                        </div>
                        <input type="hidden" name="return_date" id="returnDateInput" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" disabled>
                        <div class="input-subtext" id="returnDateSub">Save up to 20% on round trips</div>
                    </div>

                    <!-- Dual Month Linked Calendar Popup -->
                    <div class="dropdown-popup dual-calendar-popup" id="flightCalendarDropdown" onclick="event.stopPropagation();">
                        
                        <!-- Calendar Header Bar -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 18px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button type="button" id="calTabDeparture" class="cal-mode-btn" style="padding: 6px 12px; border-radius: 8px; border: 1.5px solid #0d3470; background: #e0f2fe; color: #0d3470; cursor: pointer; text-align: left; transition: all 0.2s;">
                                    <span style="font-size: 10px; text-transform: uppercase; font-weight: 700; display: block; color: #0369a1;">Departure Date</span>
                                    <strong id="calDepDateText" style="font-size: 13px;"><?php echo date('D, d M Y', strtotime('+3 days')); ?></strong>
                                </button>
                                <i class="fa-solid fa-arrow-right" style="color: #94a3b8; font-size: 11px;"></i>
                                <button type="button" id="calTabReturn" class="cal-mode-btn" style="padding: 6px 12px; border-radius: 8px; border: 1.5px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; text-align: left; transition: all 0.2s;">
                                    <span style="font-size: 10px; text-transform: uppercase; font-weight: 700; display: block; color: #94a3b8;">Return Date (Round Trip)</span>
                                    <strong id="calRetDateText" style="font-size: 13px; color: #0d3470;"><?php echo date('D, d M Y', strtotime('+7 days')); ?></strong>
                                </button>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span id="calStatusBadge" style="font-size: 11px; font-weight: 700; color: #0369a1; background: #e0f2fe; padding: 4px 10px; border-radius: 20px;">Picking Departure</span>
                                <button type="button" id="closeCalBtn" style="background: none; border: none; font-size: 22px; line-height: 1; color: #94a3b8; cursor: pointer; padding: 2px 6px;" title="Close Calendar">&times;</button>
                            </div>
                        </div>

                        <!-- Dual Months Side-by-Side Container -->
                        <div id="dualCalendarMonthsWrap" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 16px;">
                            <!-- Month 1 and Month 2 rendered dynamically via JS -->
                        </div>

                        <!-- Calendar Footer -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 18px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 12px;">
                            <span style="color: #64748b;"><i class="fa-solid fa-circle-info" style="color: #0284c7; margin-right: 4px;"></i> Select a return date to automatically make it a Round Trip</span>
                            <button type="button" id="calDoneBtn" style="background: #0d3470; color: #ffffff; border: none; padding: 6px 18px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer;">Done</button>
                        </div>
                    </div>

                    <!-- Passengers & Cabin Class Select -->
                    <div class="input-box" id="passengerSelectBox" style="cursor: pointer; position: relative;">
                        <div class="input-label"><i class="fa-solid fa-users"></i> Travelers & Class</div>
                        <div class="input-val" id="passengerSummary">1 Traveler, Economy</div>
                        <div class="input-subtext">Click to change</div>

                        <input type="hidden" name="adults" id="hiddenAdults" value="1">
                        <input type="hidden" name="children" id="hiddenChildren" value="0">
                        <input type="hidden" name="infants" id="hiddenInfants" value="0">
                        <input type="hidden" name="cabin_class" id="hiddenCabinClass" value="Economy">

                        <!-- Dropdown Popup -->
                        <div class="dropdown-popup" id="passengerDropdown" style="z-index: 99999; text-align: left;" onclick="event.stopPropagation();">
                            <div class="counter-row">
                                <div class="counter-info">
                                    <h4>Adults</h4>
                                    <p>12+ years</p>
                                </div>
                                <div class="counter-controls">
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('adult', -1);">-</button>
                                    <span class="counter-val" id="adultCount">1</span>
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('adult', 1);">+</button>
                                </div>
                            </div>
                            <div class="counter-row">
                                <div class="counter-info">
                                    <h4>Children</h4>
                                    <p>2-12 years</p>
                                </div>
                                <div class="counter-controls">
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('child', -1);">-</button>
                                    <span class="counter-val" id="childCount">0</span>
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('child', 1);">+</button>
                                </div>
                            </div>
                            <div class="counter-row">
                                <div class="counter-info">
                                    <h4>Infants</h4>
                                    <p>Below 2 years</p>
                                </div>
                                <div class="counter-controls">
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('infant', -1);">-</button>
                                    <span class="counter-val" id="infantCount">0</span>
                                    <button type="button" class="counter-btn" onclick="event.stopPropagation(); updatePassengers('infant', 1);">+</button>
                                </div>
                            </div>
                            <div style="margin-top: 14px;" onclick="event.stopPropagation();">
                                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 6px; color: #0d3470;">Cabin Class</label>
                                <select class="field-input" id="cabinClassSelect" style="width: 100%; padding: 8px 12px; border: 1.5px solid #0d3470; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;" onclick="event.stopPropagation();">
                                    <option value="Economy">Economy</option>
                                    <option value="Premium Economy">Premium Economy</option>
                                    <option value="Business">Business</option>
                                    <option value="First Class">First Class</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Multi City Container (hidden by default) -->
                <div id="multiCitySearchContainer" style="display: none; background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 10px;">
                    <div style="font-family: var(--font-heading); font-weight: 800; font-size: 16px; color: #0d3470; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fa-solid fa-route" style="color: #ef4444; margin-right: 6px;"></i> Build Your Multi-City Itinerary</span>
                        <span style="font-size: 12px; font-weight: 500; color: #64748b;">Add up to 5 flight legs</span>
                    </div>

                    <!-- Dynamic Flight Legs Container -->
                    <div id="multiCityLegsList" style="display: flex; flex-direction: column; gap: 12px;">
                        
                        <!-- Leg 1 -->
                        <div class="multi-leg-row" data-leg="1" style="display: grid; grid-template-columns: 2fr 2fr 1.5fr 40px; gap: 12px; align-items: center; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #cbd5e1;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Flight 1 - From</label>
                                <input type="text" class="field-input multi-from-input" name="multi_from[]" value="Delhi (DEL)" placeholder="City / Code" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">To</label>
                                <input type="text" class="field-input multi-to-input" name="multi_to[]" value="Mumbai (BOM)" placeholder="City / Code" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Departure Date</label>
                                <input type="date" class="field-input" name="multi_date[]" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 600; background: #ffffff;">
                            </div>
                            <div style="text-align: center; padding-top: 14px;">
                                <span style="font-size: 11px; font-weight: 800; color: #94a3b8;">LEG 1</span>
                            </div>
                        </div>

                        <!-- Leg 2 -->
                        <div class="multi-leg-row" data-leg="2" style="display: grid; grid-template-columns: 2fr 2fr 1.5fr 40px; gap: 12px; align-items: center; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #cbd5e1;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Flight 2 - From</label>
                                <input type="text" class="field-input multi-from-input" name="multi_from[]" value="Mumbai (BOM)" placeholder="City / Code" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">To</label>
                                <input type="text" class="field-input multi-to-input" name="multi_to[]" value="Bengaluru (BLR)" placeholder="City / Code" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #09204b; background: #ffffff;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Departure Date</label>
                                <input type="date" class="field-input" name="multi_date[]" value="<?php echo date('Y-m-d', strtotime('+6 days')); ?>" required disabled style="width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 600; background: #ffffff;">
                            </div>
                            <div style="text-align: center; padding-top: 14px;">
                                <button type="button" class="btn-remove-leg" onclick="removeMultiLeg(this)" style="background: none; border: none; color: #ef4444; font-size: 16px; cursor: pointer;" title="Remove Leg"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>

                    </div>

                    <!-- Controls Bar -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px;">
                        <button type="button" id="addMultiLegBtn" style="background: #eff6ff; color: #2563eb; border: 1.5px dashed #3b82f6; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                            <i class="fa-solid fa-circle-plus"></i> + ADD ANOTHER CITY
                        </button>
                        <div style="font-size: 12px; font-weight: 600; color: #475569;">
                            <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Single ticket checkout for all flight legs
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="search-btn-wrapper">
                    <button type="submit" class="btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Search Flights</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- Exclusive Deals Section (Akbar-Style Dynamic Tabbed Carousel) -->
<section class="exclusive-deals-section" data-scroll="fade-up">
    <div class="container">
        
        <!-- Header Bar with Title, Category Tabs, Navigation & View All -->
        <div class="deals-header-bar">
            <div class="deals-header-left">
                <h2 class="deals-main-title">Exclusive Deals</h2>
                <div class="deals-tabs-nav" id="dealsTabsNav">
                    <button type="button" class="deals-tab-btn active" data-cat="HOT DEALS">Hot Deals</button>
                    <button type="button" class="deals-tab-btn" data-cat="FLIGHT">Flight</button>
                    <button type="button" class="deals-tab-btn" data-cat="HOTEL">Hotel</button>
                    <button type="button" class="deals-tab-btn" data-cat="HOLIDAYS">Holidays</button>
                    <button type="button" class="deals-tab-btn" data-cat="VISA">Visa</button>
                </div>
            </div>

            <div class="deals-header-right">
                <button type="button" class="deals-carousel-btn btn-prev" id="dealsPrevBtn" aria-label="Previous Deals">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="deals-carousel-btn btn-next" id="dealsNextBtn" aria-label="Next Deals">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <a href="<?php echo site_url('flight/search'); ?>" class="deals-view-all">View All</a>
            </div>
        </div>

        <!-- Dynamic Deals Carousel Container -->
        <div class="deals-carousel-container" id="dealsCarouselContainer">
            <?php if (!empty($exclusive_deals)): ?>
                <?php foreach ($exclusive_deals as $deal): ?>
                    <a href="<?php echo function_exists('site_url') ? site_url($deal['link_url'] ?: 'flight/search') : '#'; ?>" 
                       class="deal-banner-card" 
                       data-category="<?php echo htmlspecialchars($deal['category']); ?>"
                       title="<?php echo htmlspecialchars($deal['title']); ?>">
                        
                        <!-- Deal Banner Image -->
                        <img src="<?php echo htmlspecialchars($deal['image_url']); ?>" alt="<?php echo htmlspecialchars($deal['title']); ?>" class="deal-banner-bg" loading="lazy">

                        <!-- Coupon Code Pill with Copy Icon on Upper Side of Image -->
                        <?php if (!empty($deal['promo_code'])): ?>
                            <span class="deal-coupon-pill" 
                                  onclick="copyPromoCode(event, '<?php echo htmlspecialchars($deal['promo_code']); ?>')" 
                                  title="Click to copy coupon code: <?php echo htmlspecialchars($deal['promo_code']); ?>">
                                <i class="fa-solid fa-ticket"></i>
                                <span class="coupon-code-text"><?php echo htmlspecialchars($deal['promo_code']); ?></span>
                                <i class="fa-regular fa-copy copy-icon"></i>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted w-100">
                    <p>No active deals currently available.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- Trending Routes With Cheap Prices (Akbar-Style 8-Card Grid) -->
<?php
$trending_routes = array(
    array(
        'from_name' => 'Kolkata',
        'from_full' => 'Kolkata',
        'from_code' => 'CCU',
        'to_name'   => 'Bangalore',
        'to_code'   => 'BLR',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹7123',
        'image'     => 'https://images.unsplash.com/photo-1558431382-27e303142255?auto=format&fit=crop&w=300&q=80'
    ),
    array(
        'from_name' => 'Hyder...',
        'from_full' => 'Hyderabad',
        'from_code' => 'HYD',
        'to_name'   => 'Dubai',
        'to_code'   => 'DXB',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹14539',
        'image'     => 'https://images.unsplash.com/photo-1572445271230-a78b5944a659?auto=format&fit=crop&w=300&q=80'
    ),
    array(
        'from_name' => 'Mumbai',
        'from_full' => 'Mumbai',
        'from_code' => 'BOM',
        'to_name'   => 'New Delhi',
        'to_code'   => 'DEL',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹6032',
        'image'     => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=300&q=80'
    ),
    array(
        'from_name' => 'Varanasi',
        'from_full' => 'Varanasi',
        'from_code' => 'VNS',
        'to_name'   => 'Mumbai',
        'to_code'   => 'BOM',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹5650',
        'image'     => 'https://images.unsplash.com/photo-1561361513-2d000a50f0dc?auto=format&fit=crop&w=300&q=80'
    ),
    array(
        'from_name' => 'Thiruv...',
        'from_full' => 'Thiruvananthapuram',
        'from_code' => 'TRV',
        'to_name'   => 'New Delhi',
        'to_code'   => 'DEL',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹11454',
        'image'     => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=300&q=80'
    ),
    array(
        'from_name' => 'Mumbai',
        'from_full' => 'Mumbai',
        'from_code' => 'BOM',
        'to_name'   => 'Doha',
        'to_code'   => 'DOH',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹15291',
        'image'     => base_url('assets/images/routes/doha.jpg')
    ),
    array(
        'from_name' => 'Kozhik...',
        'from_full' => 'Kozhikode',
        'from_code' => 'CCJ',
        'to_name'   => 'Abu Dhabi',
        'to_code'   => 'AUH',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹12228',
        'image'     => base_url('assets/images/routes/abudhabi.jpg')
    ),
    array(
        'from_name' => 'Chennai',
        'from_full' => 'Chennai',
        'from_code' => 'MAA',
        'to_name'   => 'Singapore',
        'to_code'   => 'SIN',
        'date'      => '2026-11-02',
        'date_disp' => '02/11/2026',
        'price'     => '₹11799',
        'image'     => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=300&q=80'
    )
);
?>
<section class="trending-routes-section" data-scroll="blur-in">
    <div class="container">
        
        <h2 class="trending-routes-title">Trending Routes With Cheap Prices</h2>

        <div class="trending-routes-grid" data-scroll="fade-up" data-scroll-delay="200">
            <?php foreach ($trending_routes as $r): ?>
                <div class="trending-route-card" onclick="submitTrendingRoute('<?php echo ($r['from_full'] ?? $r['from_name']) . ' (' . $r['from_code'] . ')'; ?>', '<?php echo $r['to_name'] . ' (' . $r['to_code'] . ')'; ?>', '<?php echo $r['date']; ?>')" title="Search flights for <?php echo ($r['from_full'] ?? $r['from_name']); ?> to <?php echo $r['to_name']; ?>">
                    <div class="trending-route-img-wrap">
                        <img src="<?php echo $r['image']; ?>" alt="<?php echo $r['to_name']; ?>" class="trending-route-img" loading="lazy">
                    </div>
                    <div class="trending-route-info">
                        <div class="trending-route-cities">
                            <span><?php echo $r['from_name']; ?></span>
                            <i class="fa-solid fa-plane trending-route-plane-icon"></i>
                            <span><?php echo $r['to_name']; ?></span>
                        </div>
                        <div class="trending-route-codes">
                            <span><?php echo $r['from_code']; ?></span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                            <span><?php echo $r['to_code']; ?></span>
                        </div>
                        <div class="trending-route-meta">
                            <span class="trending-route-date"><?php echo $r['date_disp']; ?></span>
                            <span class="trending-route-price"><?php echo $r['price']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script>
function submitTrendingRoute(fromCity, toCity, travelDate) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo function_exists("site_url") ? site_url("flight/search") : "/flight/search"; ?>';
    
    const fields = {
        'from_city': fromCity,
        'to_city': toCity,
        'departure_date': travelDate,
        'tripType': 'oneway',
        'adults': 1,
        'children': 0,
        'infants': 0,
        'cabin_class': 'Economy'
    };

    for (const key in fields) {
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = key;
        hiddenField.value = fields[key];
        form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>

<!-- Why Choose voyogos.com Section -->
<section class="why-choose-section" data-scroll="fade-up">
    <div class="container">
        
        <h2 class="why-choose-title">Why Choose voyogos.com</h2>

        <!-- 4 Key Value Propositions -->
        <div class="why-features-grid" data-scroll="scale-in" data-scroll-delay="200">
            
            <!-- Feature 1: Easy Booking -->
            <div class="why-feature-item">
                <div class="why-feature-icon-direct">
                    <svg width="44" height="44" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="planeGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#38bdf8"/>
                                <stop offset="1" stop-color="#0284c7"/>
                            </linearGradient>
                            <linearGradient id="cloudGrad" x1="0" y1="0" x2="20" y2="10" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#e0f2fe"/>
                                <stop offset="1" stop-color="#bae6fd"/>
                            </linearGradient>
                        </defs>
                        <ellipse cx="14" cy="38" rx="8" ry="4" fill="url(#cloudGrad)"/>
                        <ellipse cx="22" cy="40" rx="6" ry="3" fill="url(#cloudGrad)"/>
                        <path d="M21 16L32 6C33.5 4.5 36 5.5 36 7.5L34 19L44 26C45 26.8 44.5 28.5 43.2 28.5L32 27L24 37C23.2 38 21.8 38.2 21 37.5L19 35.8C18.2 35 18.5 33.5 19.5 32.8L25 28L15 25L9 29C8.2 29.5 7.2 29.2 6.8 28.5L5.8 26.8C5.2 26 5.8 24.8 6.8 24.5L14 22L21 16Z" fill="url(#planeGrad)" filter="drop-shadow(0px 3px 5px rgba(2, 132, 199, 0.3))"/>
                    </svg>
                </div>
                <div class="why-feature-text">
                    <h4>Easy Booking</h4>
                    <p>Book online within minutes.</p>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div class="why-divider"></div>

            <!-- Feature 2: Best Price Guarantee -->
            <div class="why-feature-item">
                <div class="why-feature-icon-direct">
                    <svg width="44" height="44" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="goldCoinGrad" x1="18" y1="2" x2="42" y2="28" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#fde047"/>
                                <stop offset="0.6" stop-color="#eab308"/>
                                <stop offset="1" stop-color="#ca8a04"/>
                            </linearGradient>
                        </defs>
                        <!-- Coin -->
                        <circle cx="30" cy="17" r="13" fill="url(#goldCoinGrad)" filter="drop-shadow(0px 3px 5px rgba(202, 138, 4, 0.35))"/>
                        <circle cx="30" cy="17" r="10" stroke="#fef08a" stroke-width="1.5" stroke-dasharray="2 2" fill="none"/>
                        <text x="30" y="22" font-size="14" font-weight="900" text-anchor="middle" fill="#854d0e" font-family="'Outfit', sans-serif">₹</text>
                        <!-- Hand Thumbs Up -->
                        <path d="M10 28V42H16V28H10ZM18 42H27C28.5 42 29.8 41 30.2 39.6L32.2 33C32.6 31.6 31.6 30.2 30.2 30.2H23.5L24.8 23.8C25.1 22.2 24 20.8 22.4 20.8C21.5 20.8 20.7 21.2 20.2 21.8L18 24.5V42Z" fill="#2563eb"/>
                        <path d="M8 30H14V42H8V30Z" fill="#1d4ed8"/>
                    </svg>
                </div>
                <div class="why-feature-text">
                    <h4>Best Price Guarantee</h4>
                    <p>Competitive rates & exclusive weekly deals.</p>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div class="why-divider"></div>

            <!-- Feature 3: Global Network -->
            <div class="why-feature-item">
                <div class="why-feature-icon-direct">
                    <svg width="44" height="44" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="globeGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2dd4bf"/>
                                <stop offset="1" stop-color="#0f766e"/>
                            </linearGradient>
                        </defs>
                        <circle cx="24" cy="24" r="17" fill="url(#globeGrad)" filter="drop-shadow(0px 3px 5px rgba(15, 118, 110, 0.3))"/>
                        <ellipse cx="24" cy="24" rx="9" ry="17" stroke="#ccfbf1" stroke-width="1.2" fill="none"/>
                        <line x1="7" y1="24" x2="41" y2="24" stroke="#ccfbf1" stroke-width="1.2"/>
                        <line x1="11" y1="16" x2="37" y2="16" stroke="#ccfbf1" stroke-width="1.2" stroke-dasharray="2 1"/>
                        <line x1="11" y1="32" x2="37" y2="32" stroke="#ccfbf1" stroke-width="1.2" stroke-dasharray="2 1"/>
                        <!-- Location Pushpins -->
                        <circle cx="16" cy="14" r="3.5" fill="#ef4444"/>
                        <circle cx="16" cy="14" r="1.2" fill="#ffffff"/>
                        <circle cx="33" cy="29" r="3.5" fill="#ef4444"/>
                        <circle cx="33" cy="29" r="1.2" fill="#ffffff"/>
                        <path d="M16 14 Q 25 18 33 29" stroke="#fda4af" stroke-width="1.8" stroke-dasharray="2 2" fill="none"/>
                    </svg>
                </div>
                <div class="why-feature-text">
                    <h4>Global Network</h4>
                    <p>150+ Offices around the world.</p>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div class="why-divider"></div>

            <!-- Feature 4: 24/7 Network -->
            <div class="why-feature-item">
                <div class="why-feature-icon-direct">
                    <svg width="44" height="44" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="badgeGrad" x1="6" y1="6" x2="42" y2="40" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#fb923c"/>
                                <stop offset="1" stop-color="#ea580c"/>
                            </linearGradient>
                        </defs>
                        <!-- Ribbon tails -->
                        <path d="M18 29L14 44L24 38L34 44L30 29" fill="#c2410c"/>
                        <!-- Rosette star -->
                        <path d="M24 5L27.5 9.8L33.3 9L34.5 14.8L40.2 16.5L39.1 22.3L43.3 26L39.6 30.2L41.3 35.9L35.6 37.2L34.4 43L28.7 41.2L24.5 45L20.8 41.3L15.1 43L13.9 37.2L8.2 35.9L9.9 30.2L6.2 26L10.4 22.3L9.3 16.5L15 14.8L16.2 9L22 9.8L24 5Z" fill="url(#badgeGrad)" filter="drop-shadow(0px 3px 5px rgba(234, 88, 12, 0.3))"/>
                        <circle cx="24.5" cy="25" r="9.5" fill="#ffffff"/>
                        <text x="24.5" y="29.5" font-size="13" font-weight="900" text-anchor="middle" fill="#ea580c" font-family="'Outfit', sans-serif">%</text>
                    </svg>
                </div>
                <div class="why-feature-text">
                    <h4>24/7 Network</h4>
                    <p>Round-the-clock assistance anytime, anywhere.</p>
                </div>
            </div>

        </div>

        <!-- App Download & Mobile Experience Banner -->
        <div class="why-app-banner" data-scroll="fade-right">
            
            <!-- Left Side: Dual-Phone 3D Mockup Image -->
            <div class="why-app-phones-col">
                <img src="<?php echo base_url('assets/images/voyogo_phones_app.jpg'); ?>" alt="Voyogos Mobile App Mockup" class="why-phones-real-img">
            </div>

            <!-- Center Copy Column -->
            <div class="why-app-copy-col">
                <h3>Travel like a Pro - Download &amp; go!</h3>
                
                <ul class="why-app-checklist">
                    <li><i class="fa-solid fa-circle-check"></i> Instant booking</li>
                    <li><i class="fa-solid fa-circle-check"></i> Manage your booking</li>
                    <li><i class="fa-solid fa-circle-check"></i> Real time updates</li>
                    <li><i class="fa-solid fa-circle-check"></i> Exclusive Deals</li>
                    <li><i class="fa-solid fa-circle-check"></i> &amp; much more...</li>
                </ul>

                <div class="why-app-downloads-counter">
                    <strong>1.9M+ Downloads &amp; counting...</strong>
                    <div class="why-app-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                        <span>4.6/5 - Ratings</span>
                    </div>
                </div>
            </div>

            <!-- Right Side (Curved White Shape with QR Code, Store Badges & Woman Traveler Model) -->
            <div class="why-app-right-arch">
                
                <!-- QR Code & CTA -->
                <div class="why-qr-box">
                    <div class="qr-code-img-wrap">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://voyogos.com" alt="Scan QR Code" class="qr-code-img">
                    </div>
                    <span class="qr-label">Scan the QR Code to download the voyogos.com Mobile App</span>
                    
                    <div class="app-store-badges">
                        <a href="https://play.google.com/store" target="_blank" class="store-badge-btn" rel="noopener">
                            <i class="fa-brands fa-google-play"></i>
                            <div class="store-text">
                                <small>GET IT ON</small>
                                <strong>Google Play</strong>
                            </div>
                        </a>
                        <a href="https://www.apple.com/app-store/" target="_blank" class="store-badge-btn" rel="noopener">
                            <i class="fa-brands fa-apple"></i>
                            <div class="store-text">
                                <small>Download on the</small>
                                <strong>App Store</strong>
                            </div>
                        </a>
                    </div>
                    
                    <span class="app-cta-sub">DOWNLOAD OUR APP iOS | Android</span>
                    <strong class="app-cta-bold">DOWNLOAD THE APP NOW!!</strong>
                </div>

                <!-- Traveler Model Image with Red Suitcase & Sunhat -->
                <div class="why-traveler-woman-col">
                    <img src="<?php echo base_url('assets/images/traveler_woman_ok.jpg'); ?>" alt="Voyogos Traveler" class="why-traveler-woman-img">
                </div>

            </div>

        </div>

        <!-- Corporate Tabs & Information Row -->
        <div class="why-tabs-bar" data-scroll="fade-up">
            
            <div class="why-tabs-nav" id="whyTabsNav">
                <button type="button" class="why-tab-link active" onclick="switchWhyTab('about', this)">Why voyogos.com?</button>
                <button type="button" class="why-tab-link" onclick="switchWhyTab('company', this)">Company Information</button>
                <button type="button" class="why-tab-link" onclick="switchWhyTab('mobile', this)">voyogos.com On Mobile</button>
            </div>

            <div class="why-tabs-links-right">
                <a href="<?php echo site_url('flight/search'); ?>" class="why-action-link">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #0284c7;"></i> Travel Update
                </a>
            </div>

        </div>

        <!-- Tab Content Panes -->
        <div class="why-tab-pane active" id="whyTabAbout">
            <p>voyogos.com brings unbeatable value with daily flight deals, exclusive discounts, seasonal offers and one of the widest selections of flights, hotels, and holiday packages. Travellers can compare fares across multiple airlines, explore different flights and choose from countless stay options worldwide. Add visa services, sightseeing activities, and travel insurance, browse multiple tour packages - all in one place. You get a complete travel hub designed to simplify every part of your journey. Whether you're planning a family vacation, business trip, or last-minute getaway, <a href="https://voyogos.com" target="_blank" style="color: #0284c7; font-weight: 700;">voyogos.com</a> offers real-time availability, secure payments, and smooth navigation, ensuring a hassle-free booking experience every single time.</p>
        </div>

        <div class="why-tab-pane" id="whyTabCompany" style="display: none;">
            <p>voyogos.com is a premier travel portal dedicated to simplifying travel planning and booking worldwide. With modern travel technology and dedicated customer support, voyogos.com serves thousands of leisure and corporate travelers, delivering 24/7 dedicated support and unbeatable flight and hotel partnerships across global destinations.</p>
        </div>

        <div class="why-tab-pane" id="whyTabMobile" style="display: none;">
            <p>The voyogos.com mobile application puts the power of a world-class travel agency in your pocket. Experience lightning-fast flight bookings, real-time flight status tracking, exclusive app-only flash discounts, zero-fee payment gateways, and 1-tap ticket modifications. Available on both iOS and Android with a stellar 4.6/5 customer rating.</p>
        </div>

    </div>
</section>

<script>
function switchWhyTab(tabKey, btn) {
    const tabs = document.querySelectorAll('.why-tab-link');
    tabs.forEach(t => t.classList.remove('active'));
    if (btn) btn.classList.add('active');

    document.getElementById('whyTabAbout').style.display = (tabKey === 'about') ? 'block' : 'none';
    document.getElementById('whyTabCompany').style.display = (tabKey === 'company') ? 'block' : 'none';
    document.getElementById('whyTabMobile').style.display = (tabKey === 'mobile') ? 'block' : 'none';
}
</script>

<!-- Airport Selector Modal -->
<div class="modal-backdrop" id="airportModalOverlay" style="z-index: 9999;">
    <div class="modal-box" style="max-width: 600px; padding: 24px; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 20px; color: var(--primary-dark); margin: 0;" id="airportModalTitle">Select Departure Airport</h3>
                <span style="font-size: 12px; color: #64748b;" id="airportModalSub">Search by City Name, Airport Name, or 3-letter IATA Code</span>
            </div>
            <button type="button" class="modal-close" id="closeAirportModal" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Search Input -->
        <div style="position: relative; margin-bottom: 16px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 16px; top: 15px; color: #0d3470;"></i>
            <input type="text" id="airportSearchInput" placeholder="Type city or airport (e.g. Delhi, BOM, Goa, Dubai, London)..." style="width: 100%; padding: 12px 16px 12px 46px; border: 2px solid #0d3470; border-radius: 10px; font-size: 15px; font-weight: 600; outline: none; box-shadow: 0 4px 12px rgba(13, 52, 112, 0.1);">
        </div>

        <!-- Popular Cities Quick Select Pills -->
        <div style="margin-bottom: 16px;">
            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">POPULAR DESTINATIONS</span>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;" id="popularAirportsPills">
                <!-- Javascript will inject popular pills -->
            </div>
        </div>

        <!-- Results List -->
        <div style="max-height: 280px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 10px; background: #ffffff;" id="airportsListContainer">
            <!-- Dynamic airport items -->
        </div>
    </div>
</div>

