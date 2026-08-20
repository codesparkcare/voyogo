<!-- Hero Flight Search Section -->
<section class="hero-section">
    <div class="hero-bg-overlay"></div>
    <div class="container">
        
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
            <form action="<?php echo function_exists('site_url') ? site_url('flight/search') : '#'; ?>" method="POST">
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
                    <div class="input-box">
                        <div class="input-label"><i class="fa-solid fa-calendar-days"></i> Departure</div>
                        <input type="date" class="field-input" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                        <div class="input-subtext"><?php echo date('D, d M Y', strtotime('+3 days')); ?></div>
                    </div>

                    <!-- Return Date -->
                    <div class="input-box" id="returnDateBox" style="opacity: 0.5; pointer-events: none;">
                        <div class="input-label"><i class="fa-solid fa-calendar-days"></i> Return</div>
                        <input type="date" class="field-input" name="return_date" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" disabled>
                        <div class="input-subtext">Save up to 20% on round trips</div>
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

<!-- Offers & Discounts Carousel Section -->
<section class="section-padding" style="background: #ffffff;">
    <div class="container">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Exclusive Flight Offers & Coupon Deals</h2>
                <p>Use discount promo codes to get extra instant cashback on airfares</p>
            </div>
            <a href="#" class="view-all-link">View All Offers <i class="fa-solid fa-chevron-right"></i></a>
        </div>

        <div class="offers-grid">
            
            <!-- Offer 1 -->
            <div class="offer-card">
                <span class="offer-badge">DOMESTIC FLIGHTS</span>
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=300&q=80" alt="Flight Offer" class="offer-img">
                <div class="offer-content">
                    <h3>Get up to ₹2,500 OFF on Domestic Flights</h3>
                    <p>Valid on ICICI Bank Credit Cards. Minimum booking ₹6,000.</p>
                    <span class="coupon-code">CODE: VOYOICICI</span>
                </div>
            </div>

            <!-- Offer 2 -->
            <div class="offer-card">
                <span class="offer-badge">INTERNATIONAL</span>
                <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=300&q=80" alt="Dubai Flight Offer" class="offer-img">
                <div class="offer-content">
                    <h3>Flat 12% Discount on International Flights</h3>
                    <p>Book Dubai, Singapore, London & Bangkok flights today!</p>
                    <span class="coupon-code">CODE: FLYGLOBAL</span>
                </div>
            </div>

            <!-- Offer 3 -->
            <div class="offer-card">
                <span class="offer-badge">STUDENT SPECIAL</span>
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=300&q=80" alt="Student Offer" class="offer-img">
                <div class="offer-content">
                    <h3>Extra 10kg Baggage Allowance for Students</h3>
                    <p>Applicable for domestic & international university travel.</p>
                    <span class="coupon-code">CODE: STUDENTPASS</span>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Popular Flight Destinations Grid -->
<section class="section-padding">
    <div class="container">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Popular Flight Routes</h2>
                <p>Trending domestic and international airfare destinations</p>
            </div>
            <a href="#" class="view-all-link">Explore All Destinations <i class="fa-solid fa-chevron-right"></i></a>
        </div>

        <div class="cards-grid">
            
            <!-- Route 1 -->
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=600&q=80" alt="Mumbai City">
                    <span class="card-tag">DAILY 24+ FLIGHTS</span>
                </div>
                <div class="card-body">
                    <h3>Delhi to Mumbai</h3>
                    <div class="card-sub"><i class="fa-solid fa-plane"></i> IndiGo, Air India, Vistara</div>
                    <div class="price-row">
                        <span class="price-label">Fares Starting From</span>
                        <span class="price-amount">₹ 3,499</span>
                    </div>
                </div>
            </div>

            <!-- Route 2 -->
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=600&q=80" alt="Dubai skyline">
                    <span class="card-tag">POPULAR OVERSEAS</span>
                </div>
                <div class="card-body">
                    <h3>Mumbai to Dubai</h3>
                    <div class="card-sub"><i class="fa-solid fa-plane"></i> Emirates, SpiceJet, Air India</div>
                    <div class="price-row">
                        <span class="price-label">Fares Starting From</span>
                        <span class="price-amount">₹ 9,800</span>
                    </div>
                </div>
            </div>

            <!-- Route 3 -->
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80" alt="Goa Beach">
                    <span class="card-tag">HOLIDAY DESTINATION</span>
                </div>
                <div class="card-body">
                    <h3>Bengaluru to Goa</h3>
                    <div class="card-sub"><i class="fa-solid fa-plane"></i> AirAsia, Akasa Air, IndiGo</div>
                    <div class="price-row">
                        <span class="price-label">Fares Starting From</span>
                        <span class="price-amount">₹ 2,199</span>
                    </div>
                </div>
            </div>

            <!-- Route 4 -->
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=600&q=80" alt="London Big Ben">
                    <span class="card-tag">NON-STOP FLIGHTS</span>
                </div>
                <div class="card-body">
                    <h3>Delhi to London</h3>
                    <div class="card-sub"><i class="fa-solid fa-plane"></i> British Airways, Virgin Atlantic</div>
                    <div class="price-row">
                        <span class="price-label">Fares Starting From</span>
                        <span class="price-amount">₹ 28,500</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- FAQ Accordion Section -->
<section class="section-padding" style="background: #ffffff;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 36px;">
            <h2 style="font-family: var(--font-heading); font-size: 28px; font-weight: 800; color: var(--primary-dark);">Frequently Asked Questions about Flight Booking</h2>
            <p style="color: var(--text-muted); font-size: 14px;">Everything you need to know about booking tickets with Voyogo</p>
        </div>

        <div class="faq-list">
            
            <div class="faq-item active">
                <div class="faq-question">
                    <span>How can I get the cheapest flight tickets on Voyogo?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    To get the lowest airfares, we recommend booking at least 2-3 weeks in advance, opting for flexible departure dates, and using bank promo coupons like VOYOICICI or FLYGLOBAL during checkout.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Can I cancel or reschedule my flight ticket online?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Yes, Voyogo offers instant 1-click cancellations and date modifications. Log into your account, select "My Bookings", and choose "Reschedule/Cancel". Refunds are credited to your bank account or Voyogo wallet within 24 hours.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>What is the maximum free baggage allowance for domestic flights?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Most Indian domestic airlines (IndiGo, Air India, Vistara, Akasa Air) permit 15kg of check-in baggage and 7kg of hand/cabin baggage per passenger in Economy class.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>How do I claim student discount airfares?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Select the "Student Fares" option on our search widget. At the time of airport check-in, you must present a valid student ID card issued by a recognized educational institution.
                </div>
            </div>

        </div>
    </div>
</section>

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

