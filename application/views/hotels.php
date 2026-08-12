<!-- Hero Hotel Search Section -->
<section class="hero-section" style="background: linear-gradient(180deg, #09204b 0%, #153e7e 40%, var(--bg-light) 100%);">
    <div class="hero-bg-overlay"></div>
    <div class="container">
        
        <div class="hero-headline">
            <h1>Find Cheap Hotels & <span>Luxury Resorts</span></h1>
            <p>Book from 500,000+ top-rated hotels, villas, and boutique stays worldwide</p>
        </div>

        <!-- Hotel Search Widget Card -->
        <div class="search-card">
            
            <div class="search-tabs">
                <div class="trip-type-options">
                    <label class="radio-custom">
                        <input type="radio" name="hotelType" value="all" checked>
                        <span>All Property Types</span>
                    </label>
                    <label class="radio-custom">
                        <input type="radio" name="hotelType" value="resort">
                        <span>Luxury Resorts</span>
                    </label>
                    <label class="radio-custom">
                        <input type="radio" name="hotelType" value="villa">
                        <span>Villas & Homestays</span>
                    </label>
                </div>
                <div class="fare-type-tags">
                    <span class="fare-tag active"><i class="fa-solid fa-mug-hot"></i> Free Breakfast Included</span>
                    <span class="fare-tag"><i class="fa-solid fa-shield"></i> Free Cancellation Stays</span>
                </div>
            </div>

            <!-- Hotel Search Form -->
            <form action="<?php echo function_exists('site_url') ? site_url('hotels/search') : '#'; ?>" method="POST">
                <div class="search-grid hotel-grid">
                    
                    <!-- Destination / City -->
                    <div class="input-box">
                        <div class="input-label"><i class="fa-solid fa-location-dot"></i> Destination / City / Hotel Name</div>
                        <input type="text" class="field-input" name="city" value="Goa, India" placeholder="Where are you going?">
                        <div class="input-subtext">Popular: Baga Beach, Calangute, Panjim</div>
                    </div>

                    <!-- Check-in Date -->
                    <div class="input-box">
                        <div class="input-label"><i class="fa-solid fa-calendar-check"></i> Check-In</div>
                        <input type="date" class="field-input" name="checkin_date" value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                        <div class="input-subtext"><?php echo date('D, d M Y', strtotime('+2 days')); ?></div>
                    </div>

                    <!-- Check-out Date -->
                    <div class="input-box">
                        <div class="input-label"><i class="fa-solid fa-calendar-xmark"></i> Check-Out</div>
                        <input type="date" class="field-input" name="checkout_date" value="<?php echo date('Y-m-d', strtotime('+5 days')); ?>">
                        <div class="input-subtext"><?php echo date('D, d M Y', strtotime('+5 days')); ?> (3 Nights)</div>
                    </div>

                    <!-- Rooms & Guests Select -->
                    <div class="input-box">
                        <div class="input-label"><i class="fa-solid fa-bed"></i> Rooms & Guests</div>
                        <div class="input-val">1 Room, 2 Guests</div>
                        <div class="input-subtext">2 Adults, 0 Children</div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="search-btn-wrapper">
                    <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #0d3470, #fa3a3a);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Search Cheap Hotels</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- Featured Hotel Destination Cities -->
<section class="section-padding" style="background: #ffffff;">
    <div class="container">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Top Hotel Destinations</h2>
                <p>Most booked holiday locations with handpicked hotel deals</p>
            </div>
        </div>

        <div class="cards-grid">
            
            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=600&q=80" alt="Goa Beach Resort">
                    <span class="card-tag">1,240+ PROPERTIES</span>
                </div>
                <div class="card-body">
                    <h3>Goa Beach Stays</h3>
                    <div class="card-sub"><i class="fa-solid fa-umbrella-beach"></i> Beachfront, Pool Resorts & Shacks</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 1,899 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80" alt="Dubai Luxury Hotel">
                    <span class="card-tag">850+ HOTELS</span>
                </div>
                <div class="card-body">
                    <h3>Dubai Luxury Hotels</h3>
                    <div class="card-sub"><i class="fa-solid fa-building"></i> Downtown, Marina & Palm Jumeirah</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 5,499 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1599661046827-dacff0c0f09a?auto=format&fit=crop&w=600&q=80" alt="Jaipur Palace Hotel">
                    <span class="card-tag">450+ HOTELS</span>
                </div>
                <div class="card-body">
                    <h3>Jaipur Heritage Haveli Stays</h3>
                    <div class="card-sub"><i class="fa-solid fa-crown"></i> Royal Palaces & Boutique Haveli</div>
                    <div class="price-row">
                        <span class="price-label">Rooms Starting From</span>
                        <span class="price-amount">₹ 2,499 <small>/night</small></span>
                    </div>
                </div>
            </div>

            <div class="destination-card">
                <div class="card-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80" alt="Maldives Overwater Resort">
                    <span class="card-tag">120+ RESORTS</span>
                </div>
                <div class="card-body">
                    <h3>Maldives Island Resorts</h3>
                    <div class="card-sub"><i class="fa-solid fa-water"></i> Overwater Villas & All-Inclusive Stays</div>
                    <div class="price-row">
                        <span class="price-label">Resorts Starting From</span>
                        <span class="price-amount">₹ 18,999 <small>/night</small></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Filterable Hotel Results Section -->
<section class="section-padding">
    <div class="container">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Recommended Hotel Stays in Goa</h2>
                <p>Showing 5 handpicked luxury & budget hotels</p>
            </div>
        </div>

        <div class="hotel-layout">
            
            <!-- Sidebar Filter -->
            <aside class="filter-sidebar">
                <div class="filter-header">
                    <h3>Filter Results</h3>
                    <button class="btn-reset" id="resetFiltersBtn">Reset All</button>
                </div>

                <!-- Star Rating Filter -->
                <div class="filter-group">
                    <div class="filter-title">Star Rating</div>
                    <label class="checkbox-custom">
                        <input type="checkbox" class="star-filter-checkbox" value="5">
                        <span>5 Star Luxury (2)</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox" class="star-filter-checkbox" value="4">
                        <span>4 Star Premium (2)</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox" class="star-filter-checkbox" value="3">
                        <span>3 Star Standard (1)</span>
                    </label>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-group">
                    <div class="filter-title">Price Per Night</div>
                    <label class="checkbox-custom">
                        <input type="checkbox" checked>
                        <span>Under ₹3,000</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox" checked>
                        <span>₹3,000 - ₹8,000</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox" checked>
                        <span>₹8,000 - ₹15,000</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox" checked>
                        <span>₹15,000+</span>
                    </label>
                </div>

                <!-- Amenities Filter -->
                <div class="filter-group">
                    <div class="filter-title">Popular Amenities</div>
                    <label class="checkbox-custom">
                        <input type="checkbox">
                        <span>Free Breakfast</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox">
                        <span>Swimming Pool</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox">
                        <span>Free High-Speed WiFi</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox">
                        <span>Spa & Massage Center</span>
                    </label>
                    <label class="checkbox-custom">
                        <input type="checkbox">
                        <span>Beachfront Access</span>
                    </label>
                </div>

            </aside>

            <!-- Hotel Result Cards -->
            <div class="hotel-results-list">
                
                <!-- Hotel Card 1 -->
                <div class="hotel-card" data-star="5">
                    <div class="hotel-card-img">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80" alt="Grand Hyatt Goa">
                        <span class="hotel-badge-overlay">FLAT 30% OFF</span>
                    </div>
                    <div class="hotel-card-details">
                        <div class="hotel-title-row">
                            <h3>Grand Hyatt Goa Resort</h3>
                            <div class="star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span style="color: var(--text-muted); font-size: 12px; margin-left: 4px;">5-Star Luxury Resort</span>
                            </div>
                        </div>
                        <div class="hotel-location">
                            <i class="fa-solid fa-location-dot"></i> Bambolim Bay, North Goa (15 mins from airport)
                        </div>
                        <div class="hotel-amenities">
                            <span class="amenity-pill"><i class="fa-solid fa-water-ladder"></i> Outdoor Pool</span>
                            <span class="amenity-pill"><i class="fa-solid fa-wifi"></i> Free WiFi</span>
                            <span class="amenity-pill"><i class="fa-solid fa-utensils"></i> Free Breakfast</span>
                            <span class="amenity-pill"><i class="fa-solid fa-spa"></i> Spa & Massage</span>
                        </div>
                    </div>
                    <div class="hotel-card-pricing">
                        <span class="rating-badge">4.8 / 5 Very Good</span>
                        <div class="old-price">₹ 12,000</div>
                        <div class="main-price">₹ 8,499</div>
                        <div class="price-unit">Per Night + Taxes</div>
                        <button class="btn-book" onclick="alert('Proceeding to book Grand Hyatt Goa Resort...');">Book Room</button>
                    </div>
                </div>

                <!-- Hotel Card 2 -->
                <div class="hotel-card" data-star="5">
                    <div class="hotel-card-img">
                        <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80" alt="Taj Exotica Goa">
                        <span class="hotel-badge-overlay">TOP RATED</span>
                    </div>
                    <div class="hotel-card-details">
                        <div class="hotel-title-row">
                            <h3>Taj Exotica Resort & Spa</h3>
                            <div class="star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span style="color: var(--text-muted); font-size: 12px; margin-left: 4px;">5-Star Heritage</span>
                            </div>
                        </div>
                        <div class="hotel-location">
                            <i class="fa-solid fa-location-dot"></i> Benaulim Beach, South Goa
                        </div>
                        <div class="hotel-amenities">
                            <span class="amenity-pill"><i class="fa-solid fa-umbrella-beach"></i> Private Beach</span>
                            <span class="amenity-pill"><i class="fa-solid fa-water-ladder"></i> Infinity Pool</span>
                            <span class="amenity-pill"><i class="fa-solid fa-spa"></i> Jiva Spa</span>
                        </div>
                    </div>
                    <div class="hotel-card-pricing">
                        <span class="rating-badge">4.9 / 5 Excellent</span>
                        <div class="old-price">₹ 18,500</div>
                        <div class="main-price">₹ 14,999</div>
                        <div class="price-unit">Per Night + Taxes</div>
                        <button class="btn-book" onclick="alert('Proceeding to book Taj Exotica Resort...');">Book Room</button>
                    </div>
                </div>

                <!-- Hotel Card 3 -->
                <div class="hotel-card" data-star="4">
                    <div class="hotel-card-img">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=80" alt="Novotel Goa Resort">
                        <span class="hotel-badge-overlay">BEST VALUE</span>
                    </div>
                    <div class="hotel-card-details">
                        <div class="hotel-title-row">
                            <h3>Novotel Resort & Spa Candolim</h3>
                            <div class="star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span style="color: var(--text-muted); font-size: 12px; margin-left: 4px;">4-Star Resort</span>
                            </div>
                        </div>
                        <div class="hotel-location">
                            <i class="fa-solid fa-location-dot"></i> Candolim, North Goa (Near Baga Beach)
                        </div>
                        <div class="hotel-amenities">
                            <span class="amenity-pill"><i class="fa-solid fa-wifi"></i> Free WiFi</span>
                            <span class="amenity-pill"><i class="fa-solid fa-martini-glass"></i> Poolside Bar</span>
                            <span class="amenity-pill"><i class="fa-solid fa-utensils"></i> Buffet Breakfast</span>
                        </div>
                    </div>
                    <div class="hotel-card-pricing">
                        <span class="rating-badge">4.5 / 5 Very Good</span>
                        <div class="old-price">₹ 7,500</div>
                        <div class="main-price">₹ 5,200</div>
                        <div class="price-unit">Per Night + Taxes</div>
                        <button class="btn-book" onclick="alert('Proceeding to book Novotel Resort...');">Book Room</button>
                    </div>
                </div>

                <!-- Hotel Card 4 -->
                <div class="hotel-card" data-star="4">
                    <div class="hotel-card-img">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80" alt="Hard Rock Hotel Goa">
                        <span class="hotel-badge-overlay">POPULAR</span>
                    </div>
                    <div class="hotel-card-details">
                        <div class="hotel-title-row">
                            <h3>Hard Rock Hotel Calangute</h3>
                            <div class="star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span style="color: var(--text-muted); font-size: 12px; margin-left: 4px;">4-Star Lifestyle</span>
                            </div>
                        </div>
                        <div class="hotel-location">
                            <i class="fa-solid fa-location-dot"></i> Calangute, North Goa
                        </div>
                        <div class="hotel-amenities">
                            <span class="amenity-pill"><i class="fa-solid fa-music"></i> Live Music</span>
                            <span class="amenity-pill"><i class="fa-solid fa-water-ladder"></i> Lagoon Pool</span>
                            <span class="amenity-pill"><i class="fa-solid fa-dumbbell"></i> Gym</span>
                        </div>
                    </div>
                    <div class="hotel-card-pricing">
                        <span class="rating-badge">4.4 / 5 Good</span>
                        <div class="old-price">₹ 6,800</div>
                        <div class="main-price">₹ 4,799</div>
                        <div class="price-unit">Per Night + Taxes</div>
                        <button class="btn-book" onclick="alert('Proceeding to book Hard Rock Hotel...');">Book Room</button>
                    </div>
                </div>

                <!-- Hotel Card 5 -->
                <div class="hotel-card" data-star="3">
                    <div class="hotel-card-img">
                        <img src="https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=600&q=80" alt="Bloom Suites Calangute">
                        <span class="hotel-badge-overlay">BUDGET FAVOURITE</span>
                    </div>
                    <div class="hotel-card-details">
                        <div class="hotel-title-row">
                            <h3>Bloom Suites Calangute</h3>
                            <div class="star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span style="color: var(--text-muted); font-size: 12px; margin-left: 4px;">3-Star Standard</span>
                            </div>
                        </div>
                        <div class="hotel-location">
                            <i class="fa-solid fa-location-dot"></i> Calangute Beach Road, Goa
                        </div>
                        <div class="hotel-amenities">
                            <span class="amenity-pill"><i class="fa-solid fa-wifi"></i> Free High-Speed WiFi</span>
                            <span class="amenity-pill"><i class="fa-solid fa-utensils"></i> Breakfast Included</span>
                        </div>
                    </div>
                    <div class="hotel-card-pricing">
                        <span class="rating-badge">4.2 / 5 Good</span>
                        <div class="old-price">₹ 4,200</div>
                        <div class="main-price">₹ 2,799</div>
                        <div class="price-unit">Per Night + Taxes</div>
                        <button class="btn-book" onclick="alert('Proceeding to book Bloom Suites...');">Book Room</button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
