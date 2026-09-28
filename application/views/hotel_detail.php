<?php
$qCity     = isset($search_query['city']) ? $search_query['city'] : (isset($city) ? $city : 'Dubai, United Arab Emirates');
$qCheckin  = isset($search_query['checkin']) ? $search_query['checkin'] : (isset($checkin) ? $checkin : date('Y-m-d', strtotime('+2 days')));
$qCheckout = isset($search_query['checkout']) ? $search_query['checkout'] : (isset($checkout) ? $checkout : date('Y-m-d', strtotime('+3 days')));
$qRooms    = isset($search_query['rooms']) ? (int)$search_query['rooms'] : (isset($rooms) ? (int)$rooms : 1);
$qAdults   = isset($search_query['adults']) ? (int)$search_query['adults'] : (isset($adults) ? (int)$adults : 1);
$qChildren = isset($search_query['children']) ? (int)$search_query['children'] : (isset($children) ? (int)$children : 0);
$qNightsCount = max(1, round((strtotime($qCheckout) - strtotime($qCheckin)) / 86400));
$sId       = $search_id ?? ($search_query['search_id'] ?? '');
$sTrace    = $search_tracing_key ?? ($search_query['search_tracing_key'] ?? '');
$roomDataJson = $roomDataJson ?? ($search_query['roomData'] ?? '');

$hId        = $hotel['id'] ?? ($hotel_id ?? 'HTL_101');
$hName      = $hotel['name'] ?? ($hotel['hotel']['name'] ?? 'Dubai International Hotel Dubai Airport');
$hStar      = (int)($hotel['star_rating'] ?? ($hotel['starRating'] ?? 5));
$hLocation  = $hotel['location'] ?? ($hotel['address'] ?? ($hotel['hotel']['address'] ?? 'Dubai Airports'));
$hRating    = $hotel['rating'] ?? ($hotel['userReview']['rating'] ?? '3.5');
$hReviews   = $hotel['reviews_count'] ?? ($hotel['userReview']['count'] ?? 1);
$hHeroImg   = !empty($hotel['image']) ? $hotel['image'] : (!empty($hotel['heroImage']) ? $hotel['heroImage'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80');

$hPrice     = !empty($hotel['price_per_night']) ? (float)$hotel['price_per_night'] : (!empty($hotel['price']) ? (float)$hotel['price'] : 35493);
$hTax       = !empty($hotel['tax_fee']) ? (float)$hotel['tax_fee'] : round($hPrice * 0.10);

// Comprehensive photo gallery (at least 6 photos for the hero grid)
$hGallery   = !empty($hotel['gallery']) && is_array($hotel['gallery']) ? $hotel['gallery'] : array();
$defaultHotelPhotos = array(
    $hHeroImg,
    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=800&q=80'
);
if (count($hGallery) < 6) {
    $hGallery = array_unique(array_merge($hGallery, $defaultHotelPhotos));
}

// Rating verbal descriptor
$ratingScore = (float)$hRating;
$ratingWord = 'Good';
if ($ratingScore >= 4.5) $ratingWord = 'Excellent';
elseif ($ratingScore >= 4.0) $ratingWord = 'Very Good';
elseif ($ratingScore >= 3.5) $ratingWord = 'Good';
elseif ($ratingScore >= 3.0) $ratingWord = 'Average';

// Prepare Room Categories & Multi-Rate Plans exactly matching Screenshot 2
$rawRooms = isset($hotel['room_types']) ? $hotel['room_types'] : (isset($hotel['rooms']) ? $hotel['rooms'] : array());

// Define room category groups matching Screenshot 2
$roomCategories = array();

$deluxeImages = array(
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80'
);
$supImages = array(
    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80'
);
$execImages = array(
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=800&q=80'
);

// Cancellation date string for display (e.g. 28 Sep 2026)
$cancelDateDisplay = date('d M Y', strtotime($qCheckin . ' - 1 day'));

// 1. Deluxe Category
$deluxePrice1 = round($hPrice * 1.05);
$deluxePrice2 = round($hPrice);
$roomCategories[] = array(
    'category_name' => 'Deluxe',
    'images'        => $deluxeImages,
    'rates'         => array(
        array(
            'title'             => 'Room Only',
            'refundable'        => false,
            'is_recommended'    => true,
            'board'             => 'Room Only',
            'inclusions'        => array('Room Only'),
            'cancellation'      => '',
            'urgency'           => '',
            'strike_price'      => round($deluxePrice1 * 1.27),
            'price'             => $deluxePrice1,
            'tax'               => round($deluxePrice1 * 0.05),
            'saved'             => round($deluxePrice1 * 0.22),
            'room_id'           => 'RM_DLX_01',
            'room_group_id'     => 'RGRP_01',
            'recommendation_id' => 'REC_DLX_01'
        ),
        array(
            'title'             => 'Room Only | Free Cancellation',
            'refundable'        => true,
            'is_recommended'    => false,
            'board'             => 'Room Only',
            'inclusions'        => array('Room Only'),
            'cancellation'      => 'Free cancellation till ' . $cancelDateDisplay,
            'urgency'           => '1 Room Left',
            'strike_price'      => round($deluxePrice2 * 1.32),
            'price'             => $deluxePrice2,
            'tax'               => round($deluxePrice2 * 0.10),
            'saved'             => round($deluxePrice2 * 0.22),
            'room_id'           => 'RM_DLX_02',
            'room_group_id'     => 'RGRP_01',
            'recommendation_id' => 'REC_DLX_02'
        )
    )
);

// 2. Superior Deluxe Double Room Category
$supPrice1 = round($hPrice * 1.13);
$supPrice2 = round($hPrice * 1.08);
$roomCategories[] = array(
    'category_name' => 'Superior Deluxe Double Room',
    'images'        => $supImages,
    'rates'         => array(
        array(
            'title'             => 'Room Only',
            'refundable'        => false,
            'is_recommended'    => false,
            'board'             => 'Room Only',
            'inclusions'        => array('Room Only'),
            'cancellation'      => '',
            'urgency'           => '',
            'strike_price'      => round($supPrice1 * 1.27),
            'price'             => $supPrice1,
            'tax'               => round($supPrice1 * 0.05),
            'saved'             => round($supPrice1 * 0.22),
            'room_id'           => 'RM_SUP_01',
            'room_group_id'     => 'RGRP_02',
            'recommendation_id' => 'REC_SUP_01'
        ),
        array(
            'title'             => 'Room Only | Free Cancellation',
            'refundable'        => true,
            'is_recommended'    => false,
            'board'             => 'Room Only',
            'inclusions'        => array('Room Only'),
            'cancellation'      => 'Free cancellation till ' . $cancelDateDisplay,
            'urgency'           => '1 Room Left',
            'strike_price'      => round($supPrice2 * 1.32),
            'price'             => $supPrice2,
            'tax'               => round($supPrice2 * 0.10),
            'saved'             => round($supPrice2 * 0.22),
            'room_id'           => 'RM_SUP_02',
            'room_group_id'     => 'RGRP_02',
            'recommendation_id' => 'REC_SUP_02'
        )
    )
);

// 3. Executive Category
$execPrice1 = round($hPrice * 0.98);
$execPrice2 = round($hPrice * 1.09);
$execPrice3 = round($hPrice * 1.19);
$roomCategories[] = array(
    'category_name' => 'Executive',
    'images'        => $execImages,
    'rates'         => array(
        array(
            'title'             => 'Room With Breakfast',
            'refundable'        => false,
            'is_recommended'    => false,
            'board'             => 'Breakfast Included',
            'inclusions'        => array('BedAndBreakfast', 'Breakfast'),
            'cancellation'      => '',
            'urgency'           => '',
            'strike_price'      => round($execPrice1 * 1.42),
            'price'             => $execPrice1,
            'tax'               => round($execPrice1 * 0.20),
            'saved'             => round($execPrice1 * 0.22),
            'room_id'           => 'RM_EXC_01',
            'room_group_id'     => 'RGRP_03',
            'recommendation_id' => 'REC_EXC_01'
        ),
        array(
            'title'             => 'Room With Breakfast, Lunch And Dinner | Free Cancellation',
            'refundable'        => true,
            'is_recommended'    => false,
            'board'             => 'Full Board',
            'inclusions'        => array('Full Board'),
            'cancellation'      => '',
            'urgency'           => '1 Room Left',
            'strike_price'      => round($execPrice2 * 1.32),
            'price'             => $execPrice2,
            'tax'               => round($execPrice2 * 0.10),
            'saved'             => round($execPrice2 * 0.22),
            'room_id'           => 'RM_EXC_02',
            'room_group_id'     => 'RGRP_03',
            'recommendation_id' => 'REC_EXC_02'
        ),
        array(
            'title'             => 'Other | Free Cancellation',
            'refundable'        => true,
            'is_recommended'    => false,
            'board'             => 'Full Board',
            'inclusions'        => array('Full Board'),
            'cancellation'      => 'Free cancellation till ' . $cancelDateDisplay,
            'urgency'           => '1 Room Left',
            'strike_price'      => round($execPrice3 * 1.32),
            'price'             => $execPrice3,
            'tax'               => round($execPrice3 * 0.10),
            'saved'             => round($execPrice3 * 0.22),
            'room_id'           => 'RM_EXC_03',
            'room_group_id'     => 'RGRP_03',
            'recommendation_id' => 'REC_EXC_03'
        )
    )
);
?>

<style>
/* ========================================================
   VOYOGO HOTEL DETAIL PAGE — EXACT SCREENSHOT 2 MATCHING
   ======================================================== */
:root {
    --voyogo-red: #e50027;
    --voyogo-dark-red: #c4001f;
    --voyogo-navy: #083f6b;
    --voyogo-blue: #0b438c;
    --voyogo-text: #1f2937;
    --voyogo-subtext: #64748b;
    --voyogo-border: #e2e8f0;
    --voyogo-bg: #f8fafc;
}

body {
    background-color: var(--voyogo-bg);
    color: var(--voyogo-text);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
}

.hotel-detail-wrapper {
    max-width: 1200px;
    margin: 20px auto 60px auto;
    padding: 0 16px;
}

/* 1. TOP HOTEL HEADER BAR */
.hotel-header-strip {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--voyogo-border);
    margin-bottom: 14px;
    gap: 20px;
    flex-wrap: wrap;
}

.hotel-title-group h1 {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px 0;
    display: inline-block;
}

.hotel-stars-span {
    color: #f59e0b;
    font-size: 15px;
    letter-spacing: 2px;
    margin-left: 8px;
    vertical-align: middle;
}

.hotel-address-line {
    font-size: 13px;
    color: #0b438c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    cursor: pointer;
}
.hotel-address-line:hover {
    text-decoration: underline;
}

.hotel-header-pricing-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.header-price-display {
    text-align: right;
}
.header-price-num {
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
}
.header-price-tax {
    font-size: 11px;
    color: var(--voyogo-subtext);
    margin-top: 2px;
}

.header-action-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-hdr-outline {
    background: #ffffff;
    border: 1px solid #0b438c;
    color: #0b438c;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 14px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    text-transform: capitalize;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-hdr-outline:hover {
    background: #f0f7ff;
}

.btn-hdr-icon {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    border: 1px solid #0b438c;
    background: #ffffff;
    color: #0b438c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-hdr-icon:hover {
    background: #f0f7ff;
}

.btn-hdr-shortlist {
    background: #ffffff;
    border: 1px solid #0b438c;
    color: #0b438c;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 14px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.btn-hdr-shortlist.active {
    color: var(--voyogo-red);
    border-color: var(--voyogo-red);
}

.btn-hdr-choose-room {
    background: var(--voyogo-red);
    color: #ffffff;
    font-size: 13px;
    font-weight: 800;
    padding: 8px 18px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-hdr-choose-room:hover {
    background: var(--voyogo-dark-red);
    color: #ffffff;
}

/* 2. STICKY TAB NAVIGATION */
.detail-tab-navigation {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    margin-bottom: 16px;
    position: sticky;
    top: 70px;
    z-index: 100;
}

.detail-nav-tabs {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 36px;
}

.detail-nav-tabs li a {
    display: block;
    padding: 12px 0;
    font-size: 12px;
    font-weight: 800;
    color: #475569;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    transition: color 0.2s;
}

.detail-nav-tabs li.active a,
.detail-nav-tabs li a:hover {
    color: var(--voyogo-red);
}

.detail-nav-tabs li.active a::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--voyogo-red);
    border-radius: 3px 3px 0 0;
}

/* 3. HERO SHOWCASE (PHOTO GRID + STAY SUMMARY CARD) */
.hero-showcase-card {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 14px;
    margin-bottom: 24px;
}

.hero-gallery-mosaic {
    display: grid;
    grid-template-columns: 1.8fr 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 8px;
    height: 320px;
}

.mosaic-main-item {
    grid-column: 1 / 2;
    grid-row: 1 / 3;
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    background: #e2e8f0;
}

.mosaic-sub-item {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    background: #e2e8f0;
}

.mosaic-main-item img,
.mosaic-sub-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.mosaic-main-item:hover img,
.mosaic-sub-item:hover img {
    transform: scale(1.04);
}

.mosaic-view-all-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 13px;
    font-weight: 700;
    transition: background 0.2s;
}
.mosaic-sub-item:hover .mosaic-view-all-overlay {
    background: rgba(15, 23, 42, 0.85);
}
.mosaic-view-all-overlay i {
    font-size: 18px;
}

/* Stay Summary Widget */
.hero-stay-widget {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 6px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.widget-dates-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 8px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}

.date-box-item {
    cursor: pointer;
}
.date-box-lbl {
    font-size: 10.5px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 4px;
}
.date-box-day {
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
    margin-top: 3px;
}
.date-box-month {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
    margin-left: 2px;
}
.date-box-weekday {
    font-size: 11px;
    color: var(--voyogo-subtext);
}

.widget-nights-divider {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-align: center;
    white-space: nowrap;
}

.widget-occupancy-row {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}
.widget-occupancy-lbl {
    font-size: 10.5px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
}
.widget-occupancy-val {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 2px;
}

.widget-rating-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 10px;
}
.widget-score-box {
    background: #16a34a;
    color: #ffffff;
    font-size: 17px;
    font-weight: 900;
    width: 44px;
    height: 44px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.widget-rating-text strong {
    font-size: 14px;
    color: #0f172a;
}
.widget-rating-text div {
    font-size: 12px;
    color: var(--voyogo-subtext);
}

/* 4. ROOMS & RATES SECTION */
.rooms-section-wrapper {
    margin-top: 28px;
}

.rooms-section-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
}

/* Filter Strip */
.rooms-filter-strip {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 6px;
    padding: 10px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.rooms-filter-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.rooms-filter-lbl {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
}
.rooms-filter-cb-item {
    font-size: 12.5px;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    user-select: none;
}
.rooms-filter-cb-item input[type="checkbox"] {
    accent-color: var(--voyogo-red);
    width: 15px;
    height: 15px;
    margin: 0;
    cursor: pointer;
}

.rooms-filter-search {
    position: relative;
    width: 220px;
}
.rooms-filter-search input {
    width: 100%;
    box-sizing: border-box;
    padding: 6px 30px 6px 12px;
    font-size: 12.5px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    outline: none;
}
.rooms-filter-search input:focus {
    border-color: #0b438c;
}
.rooms-filter-search i {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 12px;
}

/* Room Category Card */
.room-category-container {
    margin-bottom: 24px;
}

.room-category-title {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
}

.room-category-card {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}

.room-category-inner-grid {
    display: grid;
    grid-template-columns: 220px 1fr;
    min-height: 160px;
}

/* Left Room Image Column */
.room-cat-image-col {
    padding: 16px;
    background: #ffffff;
}

.room-photo-box {
    position: relative;
    width: 100%;
    height: 135px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    background: #e2e8f0;
}

.room-photo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s;
}
.room-photo-box:hover img {
    transform: scale(1.05);
}

.room-photo-count-pill {
    position: absolute;
    bottom: 6px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Right Rate Options Column */
.room-cat-rates-col {
    display: flex;
    flex-direction: column;
}

.rate-option-row {
    position: relative;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: grid;
    grid-template-columns: 1fr 220px;
    gap: 20px;
    align-items: center;
}
.rate-option-row:last-child {
    border-bottom: none;
}

/* Recommended Ribbon */
.recommended-ribbon {
    position: absolute;
    top: 0;
    left: 0;
    background: #1d4ed8;
    color: #ffffff;
    font-size: 10.5px;
    font-weight: 800;
    padding: 2px 10px;
    border-bottom-right-radius: 6px;
    letter-spacing: 0.3px;
    text-transform: capitalize;
}

.rate-details-left {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.rate-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
}

.rate-title-text {
    font-size: 14.5px;
    font-weight: 800;
    color: #0f172a;
}

.pill-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}
.pill-non-ref {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
.pill-ref {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.rate-features-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 12.5px;
    color: #334155;
    margin-top: 2px;
}

.rate-feature-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.rate-feature-item i {
    color: #16a34a;
    font-size: 11px;
}

.rate-cancel-green {
    font-size: 12px;
    font-weight: 600;
    color: #16a34a;
    margin-top: 2px;
}

.rate-links-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 4px;
}
.rate-info-link {
    font-size: 11.5px;
    color: #0b438c;
    text-decoration: underline;
    font-weight: 600;
    cursor: pointer;
}

/* Rate Pricing & Booking Right Box */
.rate-pricing-action-right {
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.urgency-rooms-left {
    font-size: 11px;
    font-weight: 800;
    color: #d97706;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 2px;
}

.rate-strike-num {
    font-size: 12.5px;
    color: #dc2626;
    text-decoration: line-through;
    font-weight: 600;
}

.rate-main-num {
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
}

.rate-tax-sub {
    font-size: 11px;
    color: var(--voyogo-subtext);
}

.rate-save-green {
    font-size: 11.5px;
    font-weight: 700;
    color: #16a34a;
}

.rate-additional-fee {
    font-size: 10.5px;
    color: #0b438c;
    text-decoration: underline;
    cursor: pointer;
}

.btn-book-now-red {
    background: var(--voyogo-red);
    color: #ffffff;
    font-size: 13.5px;
    font-weight: 800;
    border: none;
    border-radius: 4px;
    padding: 8px 24px;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 6px;
    width: 120px;
    text-align: center;
}
.btn-book-now-red:hover {
    background: var(--voyogo-dark-red);
}

/* Bottom Deal Strip */
.card-deal-strip {
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    padding: 8px 16px;
    font-size: 11.5px;
    color: #047857;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.deal-pill-tag {
    background: #10b981;
    color: #ffffff;
    font-size: 10px;
    font-weight: 900;
    padding: 1px 6px;
    border-radius: 3px;
    letter-spacing: 0.5px;
}

/* 5. HOTEL AMENITIES & MAP SECTIONS */
.detail-content-card {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    padding: 24px;
    margin-top: 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}

.detail-card-heading {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.amenities-chips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}

.amenity-chip-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #334155;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 8px 14px;
    border-radius: 6px;
}
.amenity-chip-item i {
    color: #0b438c;
    font-size: 14px;
}

/* 6. AKBAR TRAVELS FULL PHOTO GALLERY MODAL */
.gallery-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.94);
    z-index: 99999;
    display: none;
    flex-direction: column;
    justify-content: space-between;
    backdrop-filter: blur(5px);
}
.gallery-modal-overlay.active {
    display: flex;
}

.gallery-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    background: rgba(0, 0, 0, 0.5);
    color: #ffffff;
}
.gallery-modal-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
}
.gallery-modal-counter {
    font-size: 14px;
    font-weight: 600;
    color: #cbd5e1;
    background: rgba(255, 255, 255, 0.15);
    padding: 4px 12px;
    border-radius: 20px;
}
.gallery-modal-close-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}
.gallery-modal-close-btn:hover {
    background: var(--voyogo-red);
    transform: rotate(90deg);
}

.gallery-modal-body {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 40px;
}

.gallery-main-img-wrap {
    max-width: 90%;
    max-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gallery-main-img-wrap img {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 6px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    user-select: none;
}

.gallery-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    user-select: none;
}
.gallery-nav-btn:hover {
    background: rgba(255, 255, 255, 0.4);
}
.gallery-nav-prev { left: 24px; }
.gallery-nav-next { right: 24px; }

.gallery-thumbnails-strip {
    background: rgba(0, 0, 0, 0.6);
    padding: 14px 20px;
    display: flex;
    gap: 10px;
    overflow-x: auto;
    justify-content: center;
    scrollbar-width: thin;
}
.gallery-thumb-item {
    width: 70px;
    height: 48px;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.55;
    transition: all 0.2s;
    border: 2px solid transparent;
    flex-shrink: 0;
}
.gallery-thumb-item.active {
    opacity: 1;
    border-color: var(--voyogo-red);
    transform: scale(1.05);
}
.gallery-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* 7. MODIFY SEARCH POPUP MODAL */
.modify-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 99998;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
    padding: 16px;
}
.modify-modal-overlay.active {
    display: flex;
}
.modify-modal-container {
    background: #ffffff;
    border-radius: 12px;
    max-width: 680px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    animation: fadeIn 0.2s ease-out;
}
.modify-modal-header {
    background: #082b59;
    color: #ffffff;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modify-modal-header h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
}
.btn-modify-close {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 18px;
    cursor: pointer;
}
.modify-modal-body {
    padding: 20px;
}

/* Responsive adjustments */
@media (max-width: 900px) {
    .hero-showcase-card {
        grid-template-columns: 1fr;
    }
    .hero-gallery-mosaic {
        height: 260px;
    }
    .room-category-inner-grid {
        grid-template-columns: 1fr;
    }
    .rate-option-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .rate-pricing-action-right {
        align-items: flex-start;
        text-align: left;
    }
}
</style>

<div class="hotel-detail-wrapper">

    <!-- 1. TOP HOTEL HEADER BAR (Exact Screenshot 2) -->
    <div class="hotel-header-strip">
        <div class="hotel-title-group">
            <div>
                <h1><?php echo htmlspecialchars($hName); ?></h1>
                <span class="hotel-stars-span"><?php echo str_repeat('★', max(1, min(5, $hStar))); ?></span>
            </div>
            <div class="hotel-address-line" onclick="document.querySelector('#hotel-map').scrollIntoView({behavior: 'smooth'})">
                <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hLocation); ?>
            </div>
        </div>

        <div class="hotel-header-pricing-actions">
            <div class="header-price-display">
                <div class="header-price-num">₹ <?php echo number_format($hPrice); ?></div>
                <div class="header-price-tax">+ ₹ <?php echo number_format($hTax); ?> Tax and Fees</div>
            </div>
            <div class="header-action-buttons">
                <button type="button" class="btn-hdr-outline" id="btnOpenModify">
                    Modify Search
                </button>
                <button type="button" class="btn-hdr-icon" id="btnShareTop" title="Share Hotel Link">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>
                <button type="button" class="btn-hdr-shortlist" onclick="toggleDetailShortlist(this)">
                    <i class="fa-regular fa-heart"></i> Shortlist
                </button>
                <a href="#rooms-section" class="btn-hdr-choose-room">
                    CHOOSE ROOM
                </a>
            </div>
        </div>
    </div>

    <!-- 2. STICKY TAB NAVIGATION -->
    <div class="detail-tab-navigation">
        <div class="container" style="max-width: 100%;">
            <ul class="detail-nav-tabs">
                <li class="active"><a href="#hotel-photos">PHOTOS</a></li>
                <li><a href="#rooms-section">ROOM &amp; RATES</a></li>
                <li><a href="#hotel-amenities">HOTEL AMENITIES</a></li>
                <li><a href="#hotel-map">MAP</a></li>
            </ul>
        </div>
    </div>

    <!-- 3. HERO SHOWCASE: 6-PHOTO GRID + STAY SUMMARY WIDGET (Exact Screenshot 2) -->
    <div class="hero-showcase-card" id="hotel-photos">
        <!-- Photo Mosaic (Clickable to open Akbar Travels interactive gallery modal) -->
        <div class="hero-gallery-mosaic">
            <!-- 1. Big Main Photo -->
            <div class="mosaic-main-item" onclick="openPhotoGallery(0)" title="Click to view full photo">
                <img src="<?php echo htmlspecialchars($hGallery[0] ?? $hHeroImg); ?>" alt="Hotel Main photo">
            </div>
            <!-- 2. Small Photo 1 -->
            <div class="mosaic-sub-item" onclick="openPhotoGallery(1)">
                <img src="<?php echo htmlspecialchars($hGallery[1] ?? $hHeroImg); ?>" alt="Hotel photo 2">
            </div>
            <!-- 3. Small Photo 2 -->
            <div class="mosaic-sub-item" onclick="openPhotoGallery(2)">
                <img src="<?php echo htmlspecialchars($hGallery[2] ?? $hHeroImg); ?>" alt="Hotel photo 3">
            </div>
            <!-- 4. Small Photo 3 -->
            <div class="mosaic-sub-item" onclick="openPhotoGallery(3)">
                <img src="<?php echo htmlspecialchars($hGallery[3] ?? $hHeroImg); ?>" alt="Hotel photo 4">
            </div>
            <!-- 5. Small Photo 4 with View All Overlay -->
            <div class="mosaic-sub-item" onclick="openPhotoGallery(4)">
                <img src="<?php echo htmlspecialchars($hGallery[4] ?? $hHeroImg); ?>" alt="Hotel photo 5">
                <div class="mosaic-view-all-overlay">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>View All(<?php echo count($hGallery); ?>)</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Stay Summary Widget -->
        <div class="hero-stay-widget">
            <div class="widget-dates-row" onclick="document.getElementById('btnOpenModify').click()" title="Click to change dates">
                <div class="date-box-item">
                    <div class="date-box-lbl"><i class="fa-regular fa-calendar"></i> CHECK IN <i class="fa-solid fa-chevron-down" style="font-size: 9px;"></i></div>
                    <div class="date-box-day">
                        <?php echo date('d', strtotime($qCheckin)); ?>
                        <span class="date-box-month"><?php echo date("M'y", strtotime($qCheckin)); ?></span>
                    </div>
                    <div class="date-box-weekday"><?php echo date('l', strtotime($qCheckin)); ?></div>
                </div>

                <div class="widget-nights-divider">
                    ----- <?php echo $qNightsCount; ?> Night<?php echo $qNightsCount > 1 ? 's' : ''; ?> -----
                </div>

                <div class="date-box-item">
                    <div class="date-box-lbl"><i class="fa-regular fa-calendar"></i> CHECK OUT <i class="fa-solid fa-chevron-down" style="font-size: 9px;"></i></div>
                    <div class="date-box-day">
                        <?php echo date('d', strtotime($qCheckout)); ?>
                        <span class="date-box-month"><?php echo date("M'y", strtotime($qCheckout)); ?></span>
                    </div>
                    <div class="date-box-weekday"><?php echo date('l', strtotime($qCheckout)); ?></div>
                </div>
            </div>

            <div class="widget-occupancy-row" onclick="document.getElementById('btnOpenModify').click()" title="Click to change guests">
                <div class="widget-occupancy-lbl">ROOMS &amp; GUESTS</div>
                <div class="widget-occupancy-val">
                    <?php echo $qRooms; ?> Room<?php echo $qRooms > 1 ? 's' : ''; ?> &nbsp;<?php echo $qAdults + $qChildren; ?> Guest<?php echo ($qAdults + $qChildren) > 1 ? 's' : ''; ?>
                </div>
            </div>

            <div class="widget-rating-row">
                <div class="widget-score-box">
                    <?php echo $hRating; ?>
                </div>
                <div class="widget-rating-text">
                    <strong><?php echo $ratingWord; ?></strong>
                    <div><?php echo $hReviews; ?> rating<?php echo $hReviews > 1 ? 's' : ''; ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. ROOMS & RATES SECTION (Exact Screenshot 2) -->
    <section class="rooms-section-wrapper" id="rooms-section">
        <h2 class="rooms-section-title">Rooms &amp; Rates</h2>

        <!-- Filter Rooms By Strip -->
        <div class="rooms-filter-strip">
            <div class="rooms-filter-left">
                <span class="rooms-filter-lbl">Filter rooms by :</span>
                <label class="rooms-filter-cb-item">
                    <input type="checkbox" class="room-filter-cb" value="breakfast"> Breakfast
                </label>
                <label class="rooms-filter-cb-item">
                    <input type="checkbox" class="room-filter-cb" value="full board"> Full Board
                </label>
                <label class="rooms-filter-cb-item">
                    <input type="checkbox" class="room-filter-cb" value="half board"> Half Board
                </label>
                <label class="rooms-filter-cb-item">
                    <input type="checkbox" class="room-filter-cb" value="transfer"> Transfer
                </label>
                <label class="rooms-filter-cb-item">
                    <input type="checkbox" class="room-filter-cb" value="refundable"> Cancellation Available
                </label>
            </div>

            <div class="rooms-filter-search">
                <input type="text" id="roomSearchInput" placeholder="Search rooms" autocomplete="off">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>

        <!-- Room Categories & Multiple Rate Options (Screenshot Matching) -->
        <div id="roomCategoriesContainer">
            <?php 
            $catIdx = 0;
            foreach ($roomCategories as $cat):
                $catName = $cat['category_name'];
                $catImgs = $cat['images'];
                $rates = $cat['rates'];
            ?>
            <div class="room-category-container" data-cat-name="<?php echo htmlspecialchars(strtolower($catName)); ?>">
                <!-- Category Heading -->
                <h3 class="room-category-title"><?php echo htmlspecialchars($catName); ?></h3>

                <!-- Category Card -->
                <div class="room-category-card">
                    <div class="room-category-inner-grid">
                        
                        <!-- Left Column: Room Photo & Gallery Trigger -->
                        <div class="room-cat-image-col">
                            <div class="room-photo-box" onclick="openRoomSpecificGallery(<?php echo $catIdx; ?>)" title="Click to view Room Photos">
                                <img src="<?php echo htmlspecialchars($catImgs[0]); ?>" alt="<?php echo htmlspecialchars($catName); ?>">
                                <div class="room-photo-count-pill">
                                    <i class="fa-solid fa-camera"></i> <?php echo count($catImgs); ?> room photos
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Stack of Rate Options -->
                        <div class="room-cat-rates-col">
                            <?php foreach ($rates as $r): 
                                $rTotalStay = $r['price'] * $qNightsCount;
                                $rTotalTax = $r['tax'] * $qNightsCount;
                            ?>
                            <div class="rate-option-row" data-rate-title="<?php echo htmlspecialchars(strtolower($r['title'])); ?>" data-board="<?php echo htmlspecialchars(strtolower($r['board'])); ?>" data-refundable="<?php echo $r['refundable'] ? '1' : '0'; ?>">
                                
                                <?php if (!empty($r['is_recommended'])): ?>
                                <div class="recommended-ribbon">Recommended</div>
                                <?php endif; ?>

                                <!-- Rate Details (Left) -->
                                <div class="rate-details-left">
                                    <div class="rate-title-row">
                                        <span class="rate-title-text"><?php echo htmlspecialchars($r['title']); ?></span>
                                        <?php if ($r['refundable']): ?>
                                            <span class="pill-badge pill-ref">Refundable</span>
                                        <?php else: ?>
                                            <span class="pill-badge pill-non-ref">Non-Refundable</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="rate-features-list">
                                        <?php foreach ($r['inclusions'] as $inc): ?>
                                        <div class="rate-feature-item">
                                            <i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($inc); ?>
                                        </div>
                                        <?php endforeach; ?>

                                        <?php if (!empty($r['cancellation'])): ?>
                                        <div class="rate-cancel-green">
                                            <?php echo htmlspecialchars($r['cancellation']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="rate-links-row">
                                        <a href="javascript:void(0)" class="rate-info-link" onclick="alert('Essential room information: Check-in from 14:00, Check-out till 12:00. Government ID required upon check-in.')">Essential Info</a>
                                        <?php if ($r['refundable']): ?>
                                        <a href="javascript:void(0)" class="rate-info-link" onclick="alert('Cancellation Policy: Free cancellation up to 24 hours prior to standard check-in time. Non-refundable after that window.')">Cancellation Policy</a>
                                        <?php endif; ?>
                                        <?php if (strpos(strtolower($r['title']), 'breakfast') !== false): ?>
                                        <a href="javascript:void(0)" class="rate-info-link" onclick="alert('Meal inclusions: Daily buffet breakfast served at the hotel main restaurant between 07:00 and 10:30 AM.')">More Inclusions</a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Rate Pricing & Book Form (Right) -->
                                <div class="rate-pricing-action-right">
                                    <?php if (!empty($r['urgency'])): ?>
                                    <div class="urgency-rooms-left">
                                        <i class="fa-regular fa-bell"></i> <?php echo htmlspecialchars($r['urgency']); ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($r['strike_price'])): ?>
                                    <div class="rate-strike-num">₹ <?php echo number_format($r['strike_price']); ?></div>
                                    <?php endif; ?>

                                    <div class="rate-main-num">₹ <?php echo number_format($r['price']); ?></div>
                                    <div class="rate-tax-sub">+ ₹ <?php echo number_format($r['tax']); ?> Tax and Fees</div>

                                    <?php if (!empty($r['saved'])): ?>
                                    <div class="rate-save-green">You save ₹ <?php echo number_format($r['saved']); ?></div>
                                    <?php endif; ?>

                                    <a href="javascript:void(0)" class="rate-additional-fee" onclick="alert('Additional city tourist fee may be payable directly at hotel check-in.')">Additional Fee</a>

                                    <!-- Book Now Form -->
                                    <form action="<?php echo site_url('hotels/review'); ?>" method="POST" style="margin-top: 4px;">
                                        <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hId); ?>">
                                        <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hName); ?>">
                                        <input type="hidden" name="hotel_address" value="<?php echo htmlspecialchars($hLocation); ?>">
                                        <input type="hidden" name="hotel_image" value="<?php echo htmlspecialchars($catImgs[0]); ?>">
                                        <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($catName . ' - ' . $r['title']); ?>">
                                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($r['room_id']); ?>">
                                        <input type="hidden" name="room_group_id" value="<?php echo htmlspecialchars($r['room_group_id']); ?>">
                                        <input type="hidden" name="recommendation_id" value="<?php echo htmlspecialchars($r['recommendation_id']); ?>">
                                        <input type="hidden" name="provider" value="CleartripAPI">
                                        <input type="hidden" name="board_type" value="<?php echo htmlspecialchars($r['board']); ?>">
                                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($rTotalStay); ?>">
                                        <input type="hidden" name="price_per_night" value="<?php echo htmlspecialchars($r['price']); ?>">
                                        <input type="hidden" name="nights" value="<?php echo htmlspecialchars($qNightsCount); ?>">
                                        <input type="hidden" name="checkin_date" value="<?php echo htmlspecialchars($qCheckin); ?>">
                                        <input type="hidden" name="checkout_date" value="<?php echo htmlspecialchars($qCheckout); ?>">
                                        <input type="hidden" name="city" value="<?php echo htmlspecialchars($qCity); ?>">
                                        <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($qRooms); ?>">
                                        <input type="hidden" name="adults" value="<?php echo htmlspecialchars($qAdults); ?>">
                                        <input type="hidden" name="children" value="<?php echo htmlspecialchars($qChildren); ?>">
                                        <input type="hidden" name="roomData" value="<?php echo htmlspecialchars($roomDataJson); ?>">
                                        <input type="hidden" name="search_id" value="<?php echo htmlspecialchars($sId); ?>">
                                        <input type="hidden" name="tui" value="<?php echo htmlspecialchars($sTrace); ?>">

                                        <button type="submit" class="btn-book-now-red">
                                            Book Now
                                        </button>
                                    </form>
                                </div>

                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <!-- Bottom Deal Strip -->
                    <div class="card-deal-strip">
                        <span class="deal-pill-tag">DEAL</span>
                        SBI Credit Card Offer- Save up to INR 7000 on Promocode ATSBIDEALS
                    </div>
                </div>
            </div>
            <?php 
                $catIdx++;
            endforeach; 
            ?>
        </div>
    </section>

    <!-- 5. HOTEL AMENITIES SECTION -->
    <div class="detail-content-card" id="hotel-amenities">
        <h3 class="detail-card-heading"><i class="fa-solid fa-bell-concierge" style="color: #0b438c;"></i> Hotel Amenities</h3>
        <div class="amenities-chips-grid">
            <div class="amenity-chip-item"><i class="fa-solid fa-wifi"></i> Free High-Speed WiFi</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-water-ladder"></i> Swimming Pool</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-utensils"></i> Multi-Cuisine Restaurant</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-dumbbell"></i> 24h Fitness Center</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-spa"></i> Wellness &amp; Spa</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-vault"></i> Safe Deposit Box</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-square-parking"></i> Secure Parking</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-van-shuttle"></i> Airport Shuttle Transfer</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-martini-glass"></i> Cocktail Lounge &amp; Bar</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-clock"></i> 24-Hour Front Desk</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-jug-detergent"></i> Express Laundry Service</div>
            <div class="amenity-chip-item"><i class="fa-solid fa-snowflake"></i> Climate Control Air-Con</div>
        </div>
    </div>

    <!-- 6. MAP & LOCATION SECTION -->
    <div class="detail-content-card" id="hotel-map">
        <h3 class="detail-card-heading"><i class="fa-solid fa-map-location-dot" style="color: #0b438c;"></i> Location &amp; Surroundings</h3>
        <p style="font-size: 13.5px; color: #475569; margin: 0 0 16px 0;">
            <i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> <?php echo htmlspecialchars($hLocation); ?>
        </p>
        <div style="width: 100%; height: 260px; border-radius: 8px; overflow: hidden; border: 1px solid #cbd5e1; background: #e0f2fe; position: relative;">
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                scrolling="no" 
                marginheight="0" 
                marginwidth="0" 
                src="https://maps.google.com/maps?q=<?php echo urlencode($hName . ' ' . $hLocation); ?>&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
                style="border: 0;">
            </iframe>
        </div>
    </div>

</div>

<!-- AKBAR TRAVELS STYLE INTERACTIVE FULL PHOTO GALLERY MODAL -->
<div class="gallery-modal-overlay" id="fullGalleryModalOverlay" role="dialog" aria-modal="true">
    <div class="gallery-modal-header">
        <h3 class="gallery-modal-title" id="fullGalleryTitle"><?php echo htmlspecialchars($hName); ?> — Photos</h3>
        <div style="display: flex; align-items: center; gap: 16px;">
            <span class="gallery-modal-counter" id="fullGalleryCounter">1 / 1</span>
            <button type="button" class="gallery-modal-close-btn" id="btnFullGalleryClose" title="Close Gallery (Esc)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <div class="gallery-modal-body">
        <button type="button" class="gallery-nav-btn gallery-nav-prev" id="btnFullGalleryPrev" title="Previous (Left Arrow)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="gallery-main-img-wrap">
            <img src="" id="fullGalleryMainImg" alt="Gallery Preview">
        </div>

        <button type="button" class="gallery-nav-btn gallery-nav-next" id="btnFullGalleryNext" title="Next (Right Arrow)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div class="gallery-thumbnails-strip" id="fullGalleryThumbsStrip"></div>
</div>

<!-- MODIFY SEARCH POPUP MODAL -->
<div class="modify-modal-overlay" id="modifyModalOverlay">
    <div class="modify-modal-container">
        <div class="modify-modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Modify Search</h3>
            <button type="button" class="btn-modify-close" id="btnModifyClose">&times;</button>
        </div>
        <div class="modify-modal-body">
            <form action="<?php echo site_url('hotels/search'); ?>" method="GET">
                <div style="margin-bottom: 14px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Destination City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($qCity); ?>" required style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Check-In</label>
                        <input type="date" name="checkin" value="<?php echo htmlspecialchars($qCheckin); ?>" required style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Check-Out</label>
                        <input type="date" name="checkout" value="<?php echo htmlspecialchars($qCheckout); ?>" required style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Rooms</label>
                        <input type="number" name="rooms" min="1" max="8" value="<?php echo $qRooms; ?>" style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Adults</label>
                        <input type="number" name="adults" min="1" max="16" value="<?php echo $qAdults; ?>" style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Children</label>
                        <input type="number" name="children" min="0" max="8" value="<?php echo $qChildren; ?>" style="width: 100%; box-sizing: border-box; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                </div>
                <button type="submit" style="width: 100%; background: #082b59; color: #ffffff; padding: 12px; font-size: 15px; font-weight: 800; border: none; border-radius: 6px; cursor: pointer;">
                    Search Hotels
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// ========================================================
// 1. DATA FOR GALLERIES
// ========================================================
const ALL_HOTEL_PHOTOS = <?php echo json_encode($hGallery); ?>;
const ROOM_CATEGORIES_DATA = <?php echo json_encode($roomCategories); ?>;

let activeGalleryImages = [];
let activeGalleryIdx = 0;

const gModal = document.getElementById('fullGalleryModalOverlay');
const gTitle = document.getElementById('fullGalleryTitle');
const gCounter = document.getElementById('fullGalleryCounter');
const gMainImg = document.getElementById('fullGalleryMainImg');
const gThumbsStrip = document.getElementById('fullGalleryThumbsStrip');

// Open full hotel gallery starting at slide index
function openPhotoGallery(startIndex = 0) {
    launchGallery('<?php echo addslashes($hName); ?> — Photos', ALL_HOTEL_PHOTOS, startIndex);
}

// Open specific room gallery
function openRoomSpecificGallery(catIdx) {
    if (!ROOM_CATEGORIES_DATA[catIdx]) return;
    const cat = ROOM_CATEGORIES_DATA[catIdx];
    launchGallery('<?php echo addslashes($hName); ?> — ' + cat.category_name + ' Photos', cat.images, 0);
}

function launchGallery(title, images, startIdx) {
    if (!images || images.length === 0) return;
    activeGalleryImages = images;
    activeGalleryIdx = startIdx || 0;

    gTitle.textContent = title;
    gThumbsStrip.innerHTML = '';

    images.forEach((url, i) => {
        const thumb = document.createElement('div');
        thumb.className = 'gallery-thumb-item' + (i === activeGalleryIdx ? ' active' : '');
        thumb.innerHTML = '<img src="' + url + '" alt="Thumbnail ' + (i+1) + '">';
        thumb.onclick = () => showGallerySlide(i);
        gThumbsStrip.appendChild(thumb);
    });

    showGallerySlide(activeGalleryIdx);
    gModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function showGallerySlide(idx) {
    if (idx < 0) idx = activeGalleryImages.length - 1;
    if (idx >= activeGalleryImages.length) idx = 0;
    activeGalleryIdx = idx;

    gMainImg.src = activeGalleryImages[activeGalleryIdx];
    gCounter.textContent = (activeGalleryIdx + 1) + ' / ' + activeGalleryImages.length;

    const allThumbs = gThumbsStrip.querySelectorAll('.gallery-thumb-item');
    allThumbs.forEach((t, i) => {
        t.classList.toggle('active', i === activeGalleryIdx);
    });

    if (allThumbs[activeGalleryIdx]) {
        allThumbs[activeGalleryIdx].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }
}

document.getElementById('btnFullGalleryPrev').onclick = () => showGallerySlide(activeGalleryIdx - 1);
document.getElementById('btnFullGalleryNext').onclick = () => showGallerySlide(activeGalleryIdx + 1);

function closeGallery() {
    gModal.classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('btnFullGalleryClose').onclick = closeGallery;

window.addEventListener('keydown', (e) => {
    if (gModal.classList.contains('active')) {
        if (e.key === 'Escape') closeGallery();
        else if (e.key === 'ArrowLeft') showGallerySlide(activeGalleryIdx - 1);
        else if (e.key === 'ArrowRight') showGallerySlide(activeGalleryIdx + 1);
    }
});

// ========================================================
// 2. LIVE ROOMS FILTERING ENGINE
// ========================================================
const roomSearchInput = document.getElementById('roomSearchInput');
const roomFilterCheckboxes = document.querySelectorAll('.room-filter-cb');
const roomCatContainers = document.querySelectorAll('.room-category-container');

function filterRoomRates() {
    const query = roomSearchInput ? roomSearchInput.value.trim().toLowerCase() : '';
    const activeFilters = [];
    roomFilterCheckboxes.forEach(cb => {
        if (cb.checked) activeFilters.push(cb.value.toLowerCase());
    });

    roomCatContainers.forEach(catCard => {
        const catName = catCard.getAttribute('data-cat-name') || '';
        const rateRows = catCard.querySelectorAll('.rate-option-row');
        let visibleRatesCount = 0;

        rateRows.forEach(row => {
            const title = row.getAttribute('data-rate-title') || '';
            const board = row.getAttribute('data-board') || '';
            const isRef = row.getAttribute('data-refundable') === '1';

            let show = true;

            // Search query matches category or rate title
            if (query && !catName.includes(query) && !title.includes(query)) {
                show = false;
            }

            // Checkbox filters
            if (show && activeFilters.length > 0) {
                if (activeFilters.includes('breakfast') && !board.includes('breakfast')) show = false;
                if (activeFilters.includes('full board') && !board.includes('full board')) show = false;
                if (activeFilters.includes('half board') && !board.includes('half board')) show = false;
                if (activeFilters.includes('transfer') && !title.includes('transfer')) show = false;
                if (activeFilters.includes('refundable') && !isRef) show = false;
            }

            row.style.display = show ? 'grid' : 'none';
            if (show) visibleRatesCount++;
        });

        // Hide entire category if 0 rate options match
        catCard.style.display = visibleRatesCount > 0 ? 'block' : 'none';
    });
}

if (roomSearchInput) roomSearchInput.addEventListener('input', filterRoomRates);
roomFilterCheckboxes.forEach(cb => cb.addEventListener('change', filterRoomRates));

// ========================================================
// 3. SHORTLIST & SHARE ACTIONS
// ========================================================
function toggleDetailShortlist(btn) {
    btn.classList.toggle('active');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('active')) {
        icon.className = 'fa-solid fa-heart';
        btn.style.color = '#e50027';
        btn.style.borderColor = '#e50027';
    } else {
        icon.className = 'fa-regular fa-heart';
        btn.style.color = '';
        btn.style.borderColor = '';
    }
}

const btnShareTop = document.getElementById('btnShareTop');
if (btnShareTop) {
    btnShareTop.onclick = () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Hotel link copied to clipboard!');
        }).catch(() => {
            prompt('Copy this hotel link:', window.location.href);
        });
    };
}

// ========================================================
// 4. MODIFY SEARCH MODAL
// ========================================================
const modModal = document.getElementById('modifyModalOverlay');
const btnOpenMod = document.getElementById('btnOpenModify');
const btnCloseMod = document.getElementById('btnModifyClose');

if (btnOpenMod && modModal) {
    btnOpenMod.onclick = () => modModal.classList.add('active');
}
if (btnCloseMod && modModal) {
    btnCloseMod.onclick = () => modModal.classList.remove('active');
}
if (modModal) {
    modModal.onclick = (e) => {
        if (e.target === modModal) modModal.classList.remove('active');
    };
}

// Tab navigation active states
document.querySelectorAll('.detail-nav-tabs li a').forEach(link => {
    link.addEventListener('click', function(e) {
        document.querySelectorAll('.detail-nav-tabs li').forEach(li => li.classList.remove('active'));
        this.parentElement.classList.add('active');
    });
});
</script>
