<?php
$qCity     = $search_query['city'] ?? ($city ?? 'Tirunelveli, Tamil Nadu, India');
$qCheckin  = $search_query['checkin'] ?? ($checkin ?? date('Y-m-d', strtotime('+3 days')));
$qCheckout = $search_query['checkout'] ?? ($checkout ?? date('Y-m-d', strtotime('+5 days')));
$qNights   = isset($search_query['nights']) ? (int)$search_query['nights'] : (isset($nights) ? (int)$nights : max(1, round((strtotime($qCheckout) - strtotime($qCheckin)) / 86400)));
$qRooms    = (int)($search_query['rooms'] ?? ($rooms ?? 1));
$qAdults   = (int)($search_query['adults'] ?? ($adults ?? 2));
$qChildren = (int)($search_query['children'] ?? ($children ?? 0));
$qRoomData = $search_query['roomData'] ?? ($roomDataJson ?? '');
$sId       = $search_query['search_id'] ?? ($search_id ?? ($hotelResults['searchId'] ?? ''));
$sTrace    = $search_query['search_tracing_key'] ?? ($search_tracing_key ?? ($hotelResults['searchTracingKey'] ?? ''));

$hotels = array();
if (isset($hotelResults['Hotels']) && is_array($hotelResults['Hotels'])) {
    $hotels = $hotelResults['Hotels'];
} elseif (isset($hotelResults['hotels']) && is_array($hotelResults['hotels'])) {
    $hotels = $hotelResults['hotels'];
} elseif (is_array($hotelResults)) {
    $hotels = $hotelResults;
}

// Compute counts for filter badges
$starCounts = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0, 'gen' => 0);
$minPrice = 999999;
$maxPrice = 0;
foreach ($hotels as $ht) {
    $st = (int)($ht['star_rating'] ?? 0);
    if (isset($starCounts[$st])) {
        $starCounts[$st]++;
    } else {
        $starCounts['gen']++;
    }
    $pr = (float)($ht['price_per_night'] ?? 3000);
    if ($pr < $minPrice) $minPrice = $pr;
    if ($pr > $maxPrice) $maxPrice = $pr;
}
if ($minPrice == 999999) $minPrice = 2000;
if ($maxPrice == 0) $maxPrice = 60000;
?>

<!-- Leaflet Map CSS & JS for Interactive Map View (Akbar Travels Style) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Modern CSS for Hotel Search Results Page -->
<style>
:root {
    --voyogo-navy: #082b59;
    --voyogo-blue: #0b438c;
    --voyogo-red: #e50027;
    --voyogo-red-hover: #c40021;
    --voyogo-green: #16a34a;
    --voyogo-dark: #111827;
    --voyogo-gray: #64748b;
    --voyogo-border: #e2e8f0;
    --voyogo-bg: #f4f6f8;
}

.hotel-results-page {
    background-color: var(--voyogo-bg);
    min-height: 100vh;
    padding-bottom: 70px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--voyogo-dark);
}

/* ========================================================
   1. TOP MODIFY SEARCH SUMMARY BAR (Exact like Screenshot)
   ======================================================== */
.top-modify-section {
    background: #ffffff;
    border-bottom: 1px solid var(--voyogo-border);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    position: sticky;
    top: 0;
    z-index: 90;
}

.top-modify-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    gap: 16px;
    flex-wrap: wrap;
}

.modify-info-group {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.modify-destination {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
}

.dest-icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--voyogo-red);
    font-size: 16px;
    flex-shrink: 0;
}

.dest-text-label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin: 0;
}

.dest-text-city {
    font-size: 15px;
    font-weight: 700;
    color: var(--voyogo-dark);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 240px;
}

.modify-divider {
    width: 1px;
    height: 38px;
    background: #e2e8f0;
}

.modify-date-col {
    display: flex;
    flex-direction: column;
}

.modify-col-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 2px;
}

.modify-date-row {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.modify-date-num {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}

.modify-date-sub {
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
}

.nights-duration-badge {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #0f172a;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

.modify-occupancy-col {
    display: flex;
    flex-direction: column;
}

.modify-occupancy-val {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
}

.modify-actions-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-share-trigger {
    width: 40px;
    height: 40px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #ffffff;
    color: #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 15px;
    transition: all 0.2s;
}

.btn-share-trigger:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: var(--voyogo-red);
}

.btn-modify-search {
    border: 1.5px solid #0f172a;
    background: #ffffff;
    color: #0f172a;
    font-weight: 800;
    border-radius: 6px;
    padding: 9px 18px;
    font-size: 13px;
    text-transform: uppercase;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.5px;
    transition: all 0.2s;
}

.btn-modify-search:hover {
    background: #0f172a;
    color: #ffffff;
}

/* ========================================================
   2. MAIN LAYOUT GRID (Filter Sidebar + Hotel Results)
   ======================================================== */
.hotel-results-container {
    max-width: 1240px;
    margin: 24px auto 0 auto;
    padding: 0 16px;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    align-items: flex-start;
}

/* ========================================================
/* ========================================================
   3. FILTER SIDEBAR (Unified Clean Card, Exact Screenshot Matching)
   ======================================================== */
.hotel-filter-sidebar {
    width: 280px;
    flex-shrink: 0;
    position: sticky;
    top: 76px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: calc(100vh - 90px);
    overflow-y: auto;
    overflow-x: hidden;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 4px 16px 0 !important;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.hotel-filter-sidebar::-webkit-scrollbar {
    width: 5px;
}
.hotel-filter-sidebar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.hotel-filter-sidebar > * {
    flex-shrink: 0 !important;
}

/* SEE MAP VIEW CARD */
.map-view-trigger-card {
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='280' height='56' viewBox='0 0 280 56'%3E%3Crect width='280' height='56' fill='%23dbeafe'/%3E%3Cpath d='M0 15 Q60 25 120 10 T240 20 L280 18' stroke='%23ffffff' stroke-width='6' fill='none'/%3E%3Cpath d='M0 40 Q80 30 160 45 T280 35' stroke='%23ffffff' stroke-width='5' fill='none'/%3E%3Cpath d='M70 0 L90 56' stroke='%23ffffff' stroke-width='4' fill='none'/%3E%3Cpath d='M190 0 L170 56' stroke='%23ffffff' stroke-width='4' fill='none'/%3E%3Cpath d='M140 20 Q180 5 210 25 Q230 40 200 50 Z' fill='%23bbf7d0' stroke='%2386efac' stroke-width='1'/%3E%3Cpath d='M10 5 Q40 0 50 20 Q30 35 15 25 Z' fill='%23bbf7d0' stroke='%2386efac' stroke-width='1'/%3E%3Cpath d='M0 15 Q60 25 120 10 T240 20 L280 18' stroke='%23fed7aa' stroke-width='2.5' fill='none'/%3E%3C/svg%3E") center/cover no-repeat;
    background-color: #dbeafe;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    height: 52px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 12px;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.map-view-trigger-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.map-view-pin-icon {
    font-size: 22px;
    color: var(--voyogo-red);
    flex-shrink: 0;
}

.map-view-text {
    font-size: 15px;
    font-weight: 800;
    color: #083f6b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ========================================================
   SPLIT MAP VIEW MODE (Akbar Travels Style Screenshot 2)
   ======================================================== */
.hotel-results-container.map-split-active {
    max-width: 1600px !important;
}

.hotel-results-container.map-split-active .results-split-wrapper {
    display: flex !important;
    gap: 20px;
    align-items: flex-start;
    width: 100%;
}

.hotel-results-container.map-split-active .hotel-list-split-col {
    flex: 1 1 48% !important;
    max-width: 48% !important;
    max-height: calc(100vh - 86px);
    overflow-y: auto;
    padding-right: 8px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.hotel-results-container.map-split-active .hotel-map-split-col {
    display: block !important;
    flex: 1 1 52% !important;
    max-width: 52% !important;
    height: calc(100vh - 86px) !important;
    position: sticky !important;
    top: 76px !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    border: 1px solid #cbd5e1 !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    background: #e2e8f0;
}

.back-to-list-link {
    color: #2563eb;
    font-weight: 700;
    font-size: 13.5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 0;
    transition: color 0.15s;
}
.back-to-list-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Red Circular Close Button on Top-Right of Map (Exact Screenshot 2) */
.map-split-close-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 1000;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #ef4444;
    color: #ffffff;
    border: 2px solid #ffffff;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transition: transform 0.15s, background 0.15s;
}
.map-split-close-btn:hover {
    background: #dc2626;
    transform: scale(1.08);
}

/* Custom Marker Pin */
.custom-map-marker {
    background: transparent;
    border: none;
}
.map-marker-pin {
    width: 32px;
    height: 38px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ef4444'%3E%3Cpath d='M12 0C7.58 0 4 3.58 4 8c0 5.25 7 13 8 14 1-1 8-8.75 8-14 0-4.42-3.58-8-8-8zm0 11c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z'/%3E%3C/svg%3E") no-repeat center center;
    background-size: contain;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    cursor: pointer;
    transition: transform 0.15s;
}
.map-marker-pin:hover, .map-marker-pin.active {
    transform: scale(1.25);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.45));
}

/* Custom InfoWindow Popup (Exact Screenshot 2) */
.leaflet-popup-content-wrapper {
    padding: 0 !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
.leaflet-popup-content {
    margin: 0 !important;
    line-height: 1.4 !important;
}
.map-hotel-popup-box {
    width: 270px;
    padding: 14px;
    font-family: inherit;
    background: #ffffff;
}
.map-popup-header {
    margin-bottom: 8px;
}
.map-popup-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 3px 0;
    line-height: 1.3;
}
.map-popup-stars {
    color: #f59e0b;
    font-size: 12px;
    letter-spacing: 1px;
}
.map-popup-body {
    display: flex;
    gap: 12px;
    align-items: center;
}
.map-popup-thumb {
    width: 80px;
    height: 68px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}
.map-popup-actions {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.btn-map-select-room {
    background: #ef4444;
    color: #ffffff !important;
    font-size: 12px;
    font-weight: 800;
    padding: 7px 10px;
    border-radius: 6px;
    text-align: center;
    text-decoration: none !important;
    display: block;
    box-shadow: 0 2px 6px rgba(239,68,68,0.3);
    transition: background 0.15s;
}
.btn-map-select-room:hover {
    background: #dc2626;
}
.btn-map-shortlist {
    background: #ffffff;
    color: #0284c7;
    border: 1px solid #0284c7;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 8px;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: background 0.15s;
}
.btn-map-shortlist:hover {
    background: #f0f9ff;
}

/* Unified Filters Container (Single White Box with Dividers) */
.unified-filters-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    overflow: hidden;
    display: block;
}

.filter-section-row {
    padding: 13px 16px;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
    box-sizing: border-box;
}

.filter-section-row:last-child {
    border-bottom: none;
}

.filter-extra-items {
    display: none;
    flex-direction: column;
    gap: 7px;
}

.filter-extra-items.show {
    display: flex;
}

.filter-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    user-select: none;
}

.filter-section-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-section-header h4 {
    margin: 0;
    font-size: 13px;
    font-weight: 800;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-chevron-icon {
    font-size: 11px;
    color: #475569;
    transition: transform 0.2s;
}

.filter-section-row.collapsed .filter-section-body {
    display: none;
}

.filter-section-row.collapsed .filter-chevron-icon {
    transform: rotate(-90deg);
}

.filter-section-body {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 7px;
}

/* Hotel Name Input Filter */
.filter-search-input-wrap {
    position: relative;
    width: 100%;
}

.filter-search-input-wrap input {
    width: 100%;
    box-sizing: border-box;
    padding: 8px 12px 8px 32px;
    font-size: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    outline: none;
    transition: border-color 0.2s;
    background: #ffffff;
    color: #111827;
}

.filter-search-input-wrap input:focus {
    border-color: #0b438c;
    box-shadow: 0 0 0 3px rgba(11, 67, 140, 0.1);
}

.filter-search-input-wrap i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 12px;
}

/* Custom Filter Checkboxes */
.filter-checkbox-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 13px;
    color: #1f2937;
    cursor: pointer;
    user-select: none;
    line-height: 1.4;
}

.filter-checkbox-item:hover {
    color: #000000;
}

.filter-checkbox-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-checkbox-item input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin: 0;
    accent-color: var(--voyogo-red);
    cursor: pointer;
    flex-shrink: 0;
}

.star-gold-icons {
    color: #f59e0b;
    font-size: 12px;
    letter-spacing: 1px;
}

.filter-count-badge {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

.filter-view-more-link {
    display: inline-block;
    color: #0b438c;
    font-size: 12px;
    font-weight: 700;
    margin-top: 4px;
    cursor: pointer;
    text-decoration: none;
}

.filter-view-more-link:hover {
    text-decoration: underline;
    color: var(--voyogo-red);
}

.clear-filters-btn {
    width: 100%;
    padding: 8px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.2s;
    margin-top: 6px;
}

.clear-filters-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
}

/* ========================================================
   4. HOTEL RESULTS AREA & CARDS (Exact Screenshot Matching)
   ======================================================== */
.results-area-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.results-top-header {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.results-count-text {
    font-size: 13px;
    color: #475569;
    font-weight: 500;
    margin: 0;
}

.results-count-text strong {
    color: #0f172a;
}

.results-sort-bar {
    display: flex;
    align-items: center;
    gap: 16px;
}

.sort-label {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sort-item {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
}

.sort-item:hover {
    color: var(--voyogo-red);
}

.sort-item.active {
    color: var(--voyogo-red);
    font-weight: 800;
}

/* Main Hotel Card */
.hotel-card {
    background: #ffffff;
    border: 1px solid var(--voyogo-border);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}

.hotel-card:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
}

.hotel-card-main {
    display: grid;
    grid-template-columns: 240px 1fr 220px;
    min-height: 200px;
}

/* Left Image Area */
.hotel-card-image-box {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    background: #e2e8f0;
}

.hotel-card-image-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}

.hotel-card-image-box:hover img {
    transform: scale(1.05);
}

.hotel-card-gallery-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(15, 23, 42, 0.82);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 9px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
    pointer-events: none;
}

/* Center Details Area */
.hotel-card-info-box {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-right: 1px solid #f1f5f9;
}

.hotel-card-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 4px;
}

.hotel-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    cursor: pointer;
    transition: color 0.2s;
}

.hotel-card-title:hover {
    color: var(--voyogo-red);
}

.hotel-card-location {
    font-size: 12px;
    color: #64748b;
    margin: 4px 0 10px 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.hotel-card-location i {
    color: #0284c7;
    font-size: 13px;
}

.hotel-card-facilities-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #475569;
    font-weight: 500;
}

.facility-item i {
    font-size: 13px;
    color: #64748b;
}

.hotel-card-tags-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.tag-breakfast {
    font-size: 11px;
    font-weight: 700;
    color: #0369a1;
    border: 1px solid #bae6fd;
    background: #f0f9ff;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.tag-cancellation {
    font-size: 11px;
    font-weight: 700;
    color: #15803d;
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.hotel-sold-out-strip {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #b91c1c;
    font-size: 12px;
    font-weight: 700;
}

/* Right Pricing & Action Area */
.hotel-card-pricing-box {
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-end;
    text-align: right;
    background: #fafafa;
}

.rating-badge-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-text-meta {
    text-align: right;
}

.rating-text-label {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}

.rating-count-sub {
    font-size: 11px;
    color: #64748b;
}

.rating-score-box {
    background: var(--voyogo-green);
    color: #ffffff;
    font-size: 14px;
    font-weight: 800;
    padding: 3px 7px;
    border-radius: 4px;
    line-height: 1;
}

.btn-shortlist {
    background: none;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
    transition: all 0.2s;
}

.btn-shortlist:hover, .btn-shortlist.active {
    border-color: var(--voyogo-red);
    color: var(--voyogo-red);
    background: #fee2e2;
}

.hotel-price-block {
    margin: 8px 0;
}

.price-strike {
    font-size: 13px;
    color: var(--voyogo-red);
    text-decoration: line-through;
    font-weight: 600;
    display: block;
}

.price-final-number {
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
}

.price-tax-text {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}

.price-savings-tag {
    font-size: 11px;
    font-weight: 700;
    color: var(--voyogo-green);
    margin-top: 3px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 3px;
}

.btn-select-room {
    background: var(--voyogo-red);
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 9px 20px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    text-transform: capitalize;
    transition: background 0.2s;
    width: 100%;
    text-align: center;
}

.btn-select-room:hover {
    background: var(--voyogo-red-hover);
}

/* Bottom Promo Strip */
.hotel-card-promo-strip {
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    padding: 7px 16px;
    font-size: 12px;
    color: #334155;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.promo-tag-badge {
    background: var(--voyogo-red);
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 3px;
    margin-right: 6px;
}

/* ========================================================
   5. INLINE AVAILABLE ROOMS DRAWER (Select Room Accordion)
   ======================================================== */
.hotel-rooms-drawer {
    display: none;
    background: #f8fafc;
    border-top: 1px solid var(--voyogo-border);
    padding: 16px 20px;
}

.hotel-rooms-drawer.open {
    display: block;
    animation: fadeIn 0.3s ease;
}

.rooms-drawer-heading {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.room-item-row {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 14px;
    margin-bottom: 12px;
    display: grid;
    grid-template-columns: 140px 1fr 180px;
    gap: 16px;
    align-items: center;
}

.room-thumb-box {
    position: relative;
    height: 95px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    background: #e2e8f0;
}

.room-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s;
}

.room-thumb-box:hover img {
    transform: scale(1.06);
}

.room-photo-badge {
    position: absolute;
    bottom: 4px;
    left: 4px;
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.room-info-details h5 {
    margin: 0 0 4px 0;
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}

.room-board-pill {
    font-size: 12px;
    font-weight: 700;
    color: #16a34a;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 4px;
}

.room-meta-features {
    font-size: 11px;
    color: #64748b;
    line-height: 1.5;
}

.room-price-action {
    text-align: right;
}

.room-rate-val {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
}

.room-rate-sub {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 8px;
}

.btn-book-room {
    background: #0b438c;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    width: 100%;
}

.btn-book-room:hover {
    background: #082b59;
}

/* ========================================================
   6. AKBAR TRAVELS STYLE FULL IMAGE GALLERY MODAL
   ======================================================== */
.gallery-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.92);
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

.gallery-nav-prev {
    left: 24px;
}

.gallery-nav-next {
    right: 24px;
}

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

/* ========================================================
   7. INTERACTIVE MODIFY SEARCH POPUP MODAL
   ======================================================== */
.modify-search-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.65);
    z-index: 99990;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modify-search-modal-overlay.active {
    display: flex;
}

.modify-search-modal-box {
    background: #ffffff;
    border-radius: 12px;
    width: 90%;
    max-width: 820px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: zoomIn 0.2s ease;
}

.modify-modal-head {
    background: #082b59;
    color: #ffffff;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modify-modal-head h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
}

.modify-modal-close {
    background: none;
    border: none;
    color: #cbd5e1;
    font-size: 20px;
    cursor: pointer;
}

.modify-modal-close:hover {
    color: #ffffff;
}

.modify-modal-content {
    padding: 24px;
}

.modify-grid-form {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 16px;
    margin-bottom: 20px;
}

.form-field-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-field-wrap label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
}

.form-field-wrap input {
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    color: #0f172a;
    outline: none;
}

.form-field-wrap input:focus {
    border-color: #0b438c;
}

/* Autosuggest Dropdown */
.autosuggest-dropdown-wrap {
    position: relative;
}

.city-suggestions-box {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    z-index: 100;
    max-height: 220px;
    overflow-y: auto;
    display: none;
}

.city-suggestion-item {
    padding: 10px 14px;
    font-size: 13px;
    color: #1e293b;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}

.city-suggestion-item:hover {
    background: #f0fdf4;
    color: #0b438c;
}

/* Stepper Controls */
.stepper-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 0;
}

.stepper-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stepper-btn:hover {
    background: #f1f5f9;
}

.btn-update-search-submit {
    background: linear-gradient(135deg, var(--voyogo-navy), #2563eb);
    color: #ffffff;
    font-weight: 800;
    font-size: 15px;
    border: none;
    border-radius: 6px;
    padding: 14px 28px;
    cursor: pointer;
    text-transform: uppercase;
    width: 100%;
    letter-spacing: 0.5px;
}

.btn-update-search-submit:hover {
    filter: brightness(1.1);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .hotel-results-container {
        grid-template-columns: 1fr;
    }
    .hotel-filter-sidebar {
        position: static;
        max-height: none;
    }
}
@media (max-width: 768px) {
    .hotel-card-main {
        grid-template-columns: 1fr;
    }
    .hotel-card-image-box {
        height: 200px;
    }
    .hotel-card-pricing-box {
        align-items: flex-start;
        text-align: left;
    }
    .modify-grid-form {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="hotel-results-page">

    <!-- ========================================================
         1. TOP MODIFY SEARCH SUMMARY BAR (Exact like Screenshot)
         ======================================================== -->
    <header class="top-modify-section">
        <div class="container top-modify-card">
            
            <div class="modify-info-group">
                <!-- Destination -->
                <div class="modify-destination">
                    <div class="dest-icon-circle">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <div>
                        <p class="dest-text-label">Select Destination</p>
                        <h2 class="dest-text-city" title="<?php echo htmlspecialchars($qCity); ?>"><?php echo htmlspecialchars($qCity); ?></h2>
                    </div>
                </div>

                <div class="modify-divider"></div>

                <!-- Check-In -->
                <div class="modify-date-col">
                    <span class="modify-col-label">Check-In</span>
                    <div class="modify-date-row">
                        <span class="modify-date-num"><?php echo date('d', strtotime($qCheckin)); ?></span>
                        <span class="modify-date-sub"><?php echo date("M'y , l", strtotime($qCheckin)); ?></span>
                    </div>
                </div>

                <!-- Duration Pill -->
                <div class="nights-duration-badge">
                    <?php echo $qNights; ?> Night<?php echo $qNights > 1 ? 's' : ''; ?>
                </div>

                <!-- Check-Out -->
                <div class="modify-date-col">
                    <span class="modify-col-label">Check-Out</span>
                    <div class="modify-date-row">
                        <span class="modify-date-num"><?php echo date('d', strtotime($qCheckout)); ?></span>
                        <span class="modify-date-sub"><?php echo date("M'y , l", strtotime($qCheckout)); ?></span>
                    </div>
                </div>

                <div class="modify-divider"></div>

                <!-- Rooms & Guests -->
                <div class="modify-occupancy-col">
                    <span class="modify-col-label">Rooms &amp; Guests</span>
                    <span class="modify-occupancy-val"><?php echo $qRooms; ?> Room<?php echo $qRooms > 1 ? 's' : ''; ?> <?php echo $qAdults + $qChildren; ?> Guest<?php echo ($qAdults + $qChildren) > 1 ? 's' : ''; ?></span>
                </div>
            </div>

            <!-- Actions (Share + Modify Search Button) -->
            <div class="modify-actions-group">
                <button type="button" class="btn-share-trigger" id="btnShareSearch" title="Share search link">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>
                <button type="button" class="btn-modify-search" id="btnOpenModifyModal">
                    <i class="fa-solid fa-pen-to-square"></i> MODIFY SEARCH
                </button>
            </div>

        </div>
    </header>

    <!-- ========================================================
         2. MAIN LAYOUT (Filter Sidebar + Hotel Results)
         ======================================================== -->
    <div class="hotel-results-container">
        
        <!-- ========================================================
             LEFT FILTER SIDEBAR (Screenshot Matching)
             ======================================================== -->
        <aside class="hotel-filter-sidebar">
            
            <!-- SEE MAP VIEW Card -->
            <div class="map-view-trigger-card" id="btnSeeMapView" onclick="toggleMapView(true)">
                <i class="fa-solid fa-location-dot map-view-pin-icon"></i>
                <span class="map-view-text">SEE MAP VIEW</span>
            </div>

            <!-- Unified Filter Box (Single White Card with clean Dividers) -->
            <div class="unified-filters-card">
                
                <!-- 1. HOTEL NAME Filter -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>HOTEL NAME</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <div class="filter-search-input-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="hotelNameFilterInput" placeholder="Search by hotel name" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- 2. POPULAR FILTER -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>POPULAR FILTER</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="popular" value="choice"> Voyogo Choice
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="popular" value="cancellation"> Free Cancellation
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="popular" value="breakfast"> Breakfast Available
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="popular" value="wifi"> Wifi
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 3. CUSTOMER RATINGS -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>CUSTOMER RATINGS</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="rating" value="4.5"> 4.5 &amp; Above <strong>&nbsp;Excellent</strong>
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="rating" value="4.0"> 4 &amp; Above <strong>&nbsp;Very Good</strong>
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="rating" value="3.5"> 3.5 &amp; Above <strong>&nbsp;Good</strong>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 4. STAR RATING -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>STAR RATING</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="5">
                                <span class="star-gold-icons">★★★★★</span>
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts[5] ?? 0; ?>)</span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="4">
                                <span class="star-gold-icons">★★★★☆</span>
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts[4] ?? 0; ?>)</span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="3">
                                <span class="star-gold-icons">★★★☆☆</span>
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts[3] ?? 0; ?>)</span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="2">
                                <span class="star-gold-icons">★★☆☆☆</span>
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts[2] ?? 0; ?>)</span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="1">
                                <span class="star-gold-icons">★☆☆☆☆</span>
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts[1] ?? 0; ?>)</span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="stars" value="0"> General
                            </span>
                            <span class="filter-count-badge">(<?php echo $starCounts['gen'] ?? 0; ?>)</span>
                        </label>
                    </div>
                </div>

                <!-- 5. PRICE RANGE -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>PRICE RANGE</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="price" value="0-7000"> Upto ₹ 7000
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="price" value="7000-12000"> ₹ 7000 to ₹ 12000
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="price" value="12000-28000"> ₹ 12000 to ₹ 28000
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="price" value="28000-9999999"> ₹ 28000 &amp; More
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 6. AMENITIES -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>AMENITIES</h4>
                        </div>
                    </div>
                    <div class="filter-section-body" id="amenitiesFilterList">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="parking"> Parking
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="safe deposit box"> Safe Deposit Box
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="breakfast"> Breakfast
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="currency exchange"> Currency Exchange
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="transportation"> Transportation
                            </span>
                        </label>
                        <div id="extraAmenities" class="filter-extra-items">
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="pool"> Swimming Pool
                                </span>
                            </label>
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="gym"> Gym / Fitness Center
                                </span>
                            </label>
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="amenity" value="spa"> Spa &amp; Wellness
                                </span>
                            </label>
                        </div>
                        <a href="javascript:void(0)" class="filter-view-more-link" onclick="toggleExtraSection('extraAmenities', this)">View all</a>
                    </div>
                </div>

                <!-- 7. CHAIN PROPERTIES -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>CHAIN PROPERTIES</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="independent"> Independent Hotels
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="atour"> Atour
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="hilton"> Hilton Worldwide
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="ihg"> IHG
                            </span>
                        </label>
                        <div id="extraChains" class="filter-extra-items">
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="grt"> GRT Hotels
                                </span>
                            </label>
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="taj"> Taj Hotels
                                </span>
                            </label>
                            <label class="filter-checkbox-item">
                                <span class="filter-checkbox-left">
                                    <input type="checkbox" class="hotel-filter-cb" data-filter-type="chain" value="radisson"> Radisson Blu
                                </span>
                            </label>
                        </div>
                        <a href="javascript:void(0)" class="filter-view-more-link" onclick="toggleExtraSection('extraChains', this)">View all</a>
                    </div>
                </div>

                <!-- 8. PROPERTY TYPE -->
                <div class="filter-section-row">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <div class="filter-section-header-left">
                            <i class="fa-solid fa-chevron-down filter-chevron-icon"></i>
                            <h4>PROPERTY TYPE</h4>
                        </div>
                    </div>
                    <div class="filter-section-body">
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="property_type" value="hotel"> Hotel
                            </span>
                        </label>
                        <label class="filter-checkbox-item">
                            <span class="filter-checkbox-left">
                                <input type="checkbox" class="hotel-filter-cb" data-filter-type="property_type" value="resort"> Resort
                            </span>
                        </label>
                    </div>
                </div>

                <!-- 9. Available Only Checkbox -->
                <div class="filter-section-row">
                    <label class="filter-checkbox-item" style="padding: 0;">
                        <span class="filter-checkbox-left">
                            <input type="checkbox" id="cbShowOnlyAvailable"> Show only available hotels
                        </span>
                    </label>
                </div>

                <!-- Reset Filters Action -->
                <div style="padding: 10px 16px; background: #fafafa; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="clear-filters-btn" id="btnResetFilters">
                        <i class="fa-solid fa-rotate-left"></i> Reset All Filters
                    </button>
                </div>

            </div>
        </aside>

        <!-- ========================================================
             RIGHT HOTEL RESULTS AREA
             ======================================================== -->
        <main class="results-area-col" id="resultsAreaCol">
            
            <div class="results-split-wrapper" id="resultsSplitWrapper">
                
                <!-- Left Hotel Listings Column -->
                <div class="hotel-list-split-col" id="hotelListSplitCol">

                    <!-- Back to List View Button (Shown only when Map View is active, Screenshot 2) -->
                    <div class="map-view-back-bar" id="mapViewBackBar" style="display: none; margin-bottom: 12px;">
                        <a href="javascript:void(0)" onclick="toggleMapView(false)" class="back-to-list-link">
                            <i class="fa-solid fa-arrow-left"></i> Back to List view
                        </a>
                    </div>

                    <!-- Top Results Summary & Sort Options (Exact Screenshot) -->
                    <div class="results-top-header">
                        <p class="results-count-text">
                            Showing <strong id="visibleHotelCount"><?php echo count($hotels); ?></strong> of <strong id="totalHotelCount"><?php echo count($hotels); ?></strong> hotels found
                        </p>

                        <div class="results-sort-bar">
                            <span class="sort-label">SORT BY</span>
                            <span class="sort-item active" data-sort="featured" onclick="sortHotels('featured', this)">
                                FEATURED <i class="fa-solid fa-arrow-down" style="font-size: 11px;"></i>
                            </span>
                            <span class="sort-item" data-sort="rating" onclick="sortHotels('rating', this)">
                                RATING
                            </span>
                            <span class="sort-item" data-sort="price" onclick="sortHotels('price', this)">
                                PRICE <i class="fa-solid fa-sort" style="font-size: 11px; margin-left: 2px;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Hotel Listings Container -->
                    <div id="hotelListContainer">
                <?php 
                if (!empty($hotels)):
                    $idx = 0;
                    foreach ($hotels as $h):
                        $hId       = $h['id'] ?? ('HTL_' . $idx);
                        $hName     = $h['name'] ?? 'Hotel';
                        $hStar     = (int)($h['star_rating'] ?? 4);
                        $hRating   = (float)($h['rating'] ?? 4.5);
                        $hRatingTxt= $h['rating_text'] ?? (($hRating >= 4.5) ? 'Excellent' : 'Good');
                        $hReviews  = (int)($h['reviews_count'] ?? 1);
                        $hLocation = $h['location'] ?? 'City Center';
                        $hPrice    = (float)($h['price_per_night'] ?? 4500);
                        $hOrigPrice= (float)($h['original_price'] ?? round($hPrice * 1.28));
                        $hTax      = (float)($h['tax_fee'] ?? round($hPrice * 0.18));
                        $hSaved    = max(0, $hOrigPrice - $hPrice);
                        $hHeroImg  = !empty($h['image']) ? $h['image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
                        $hGallery  = !empty($h['gallery']) && is_array($h['gallery']) ? $h['gallery'] : array($hHeroImg);
                        $hChain    = $h['chain'] ?? 'Independent Hotels';
                        $hType     = $h['property_type'] ?? 'Hotel';
                        $hAmenities= !empty($h['amenities']) && is_array($h['amenities']) ? $h['amenities'] : array('Free Wifi', 'Parking', 'Room Service');
                        $isSoldOut = !empty($h['is_sold_out']);
                        $hRooms    = !empty($h['room_types']) && is_array($h['room_types']) ? $h['room_types'] : array();

                        // Detail URL
                        $detailUrl = site_url('hotels/detail/' . $hId . '?city=' . urlencode($qCity) . '&checkin=' . $qCheckin . '&checkout=' . $qCheckout . '&rooms=' . $qRooms . '&adults=' . $qAdults . '&children=' . $qChildren . (!empty($qRoomData) ? '&roomData=' . urlencode($qRoomData) : '') . (!empty($sId) ? '&search_id=' . urlencode($sId) : '') . (!empty($sTrace) ? '&search_tracing_key=' . urlencode($sTrace) : ''));
                ?>
                <div class="hotel-card" 
                     id="hotelCard_<?php echo $idx; ?>"
                     data-idx="<?php echo $idx; ?>"
                     data-id="<?php echo htmlspecialchars($hId); ?>"
                     data-name="<?php echo htmlspecialchars(strtolower($hName)); ?>"
                     data-price="<?php echo $hPrice; ?>"
                     data-stars="<?php echo $hStar; ?>"
                     data-rating="<?php echo $hRating; ?>"
                     data-chain="<?php echo htmlspecialchars(strtolower($hChain)); ?>"
                     data-type="<?php echo htmlspecialchars(strtolower($hType)); ?>"
                     data-amenities="<?php echo htmlspecialchars(strtolower(implode(',', $hAmenities))); ?>"
                     data-available="<?php echo $isSoldOut ? '0' : '1'; ?>"
                     data-featured="<?php echo $idx; ?>"
                     data-voyogo-choice="<?php echo (!empty($h['is_voyogo_choice']) || (float)$hRating >= 4.0 || (int)$hStar >= 4) ? '1' : '0'; ?>"
                     data-free-cancellation="<?php echo !empty($h['free_cancellation']) ? '1' : '0'; ?>"
                     data-free-breakfast="<?php echo !empty($h['free_breakfast']) ? '1' : '0'; ?>"
                     data-wifi="<?php echo (stripos(implode(',', $hAmenities), 'wifi') !== false) ? '1' : '0'; ?>"
                     data-lat="<?php echo htmlspecialchars($h['latitude'] ?? ''); ?>"
                     data-lng="<?php echo htmlspecialchars($h['longitude'] ?? ''); ?>"
                     onclick="focusHotelOnMap(<?php echo $idx; ?>)"
                     style="margin-bottom: 20px;">
                    
                    <!-- Main Hotel Card Content -->
                    <div class="hotel-card-main">
                        
                        <!-- Left Image Box with Gallery Trigger (Akbar Travels Style) -->
                        <div class="hotel-card-image-box" onclick="openHotelGallery(<?php echo $idx; ?>)">
                            <img src="<?php echo htmlspecialchars($hHeroImg); ?>" alt="<?php echo htmlspecialchars($hName); ?>" loading="lazy">
                            <div class="hotel-card-gallery-badge">
                                <i class="fa-solid fa-camera"></i> <?php echo count($hGallery); ?> Photos
                            </div>
                        </div>

                        <!-- Center Info Box -->
                        <div class="hotel-card-info-box">
                            <div>
                                <div class="hotel-card-title-row">
                                    <h3 class="hotel-card-title" onclick="window.location.href='<?php echo $detailUrl; ?>'"><?php echo htmlspecialchars($hName); ?></h3>
                                    <span class="star-gold-icons">
                                        <?php echo str_repeat('★', max(1, min(5, $hStar))); ?>
                                    </span>
                                </div>

                                <p class="hotel-card-location">
                                    <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($hLocation); ?>
                                </p>

                                <div class="hotel-card-facilities-row">
                                    <?php 
                                    $shownFac = 0;
                                    foreach ($hAmenities as $am):
                                        if ($shownFac >= 5) break;
                                        $iconClass = 'fa-check';
                                        if (stripos($am, 'parking') !== false) $iconClass = 'fa-square-parking';
                                        elseif (stripos($am, 'wifi') !== false) $iconClass = 'fa-wifi';
                                        elseif (stripos($am, 'service') !== false) $iconClass = 'fa-bell-concierge';
                                        elseif (stripos($am, 'pool') !== false) $iconClass = 'fa-person-swimming';
                                        elseif (stripos($am, 'restaurant') !== false || stripos($am, 'dining') !== false) $iconClass = 'fa-utensils';
                                        elseif (stripos($am, 'gym') !== false || stripos($am, 'fitness') !== false) $iconClass = 'fa-dumbbell';
                                        elseif (stripos($am, 'bar') !== false) $iconClass = 'fa-martini-glass-citrus';
                                    ?>
                                    <span class="facility-item">
                                        <i class="fa-solid <?php echo $iconClass; ?>"></i> <?php echo htmlspecialchars($am); ?>
                                    </span>
                                    <?php 
                                        $shownFac++;
                                    endforeach; 
                                    ?>
                                </div>

                                <div class="hotel-card-tags-row">
                                    <?php if (!empty($h['free_breakfast'])): ?>
                                    <span class="tag-breakfast">
                                        <i class="fa-solid fa-mug-hot"></i> Breakfast Available
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($h['free_cancellation'])): ?>
                                    <span class="tag-cancellation">
                                        <i class="fa-solid fa-shield-halved"></i> Free cancellation
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isSoldOut): ?>
                                <div class="hotel-sold-out-strip">
                                    <i class="fa-regular fa-clock" style="font-size: 15px;"></i>
                                    <div>Sold out on your dates! Our last room here is already booked.</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right Pricing & Select Room Action -->
                        <div class="hotel-card-pricing-box">
                            <div>
                                <div class="rating-badge-group">
                                    <div class="rating-text-meta">
                                        <div class="rating-text-label"><?php echo htmlspecialchars($hRatingTxt); ?></div>
                                        <div class="rating-count-sub"><?php echo $hReviews; ?> rating<?php echo $hReviews > 1 ? 's' : ''; ?></div>
                                    </div>
                                    <div class="rating-score-box">
                                        <?php echo number_format($hRating, 1); ?>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: flex-end; margin-top: 6px;">
                                    <button type="button" class="btn-shortlist" onclick="toggleShortlist(this)">
                                        <i class="fa-regular fa-heart"></i> Shortlist
                                    </button>
                                </div>
                            </div>

                            <div style="width: 100%;">
                                <div class="hotel-price-block">
                                    <?php if ($hOrigPrice > $hPrice): ?>
                                    <span class="price-strike">₹ <?php echo number_format($hOrigPrice); ?></span>
                                    <?php endif; ?>
                                    <div class="price-final-number">₹ <?php echo number_format($hPrice); ?></div>
                                    <div class="price-tax-text">+ ₹ <?php echo number_format($hTax); ?> Tax and Fees</div>
                                    <?php if ($hSaved > 0): ?>
                                    <div class="price-savings-tag">
                                        You Saved ₹ <?php echo number_format($hSaved); ?> <i class="fa-solid fa-check"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <?php 
                                $detailUrl = site_url('hotels/detail/' . urlencode($hId) . '?' . http_build_query(array(
                                    'city'               => $qCity,
                                    'checkin'            => $qCheckin,
                                    'checkout'           => $qCheckout,
                                    'rooms'              => $qRooms,
                                    'adults'             => $qAdults,
                                    'children'           => $qChildren,
                                    'roomData'           => $qRoomData ?? '',
                                    'search_id'          => $sId ?? '',
                                    'search_tracing_key' => $sTrace ?? ''
                                )));
                                ?>
                                <a href="<?php echo $detailUrl; ?>" target="_blank" class="btn-select-room" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                    View Room
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Promotion Strip (Screenshot Matching) -->
                    <div class="hotel-card-promo-strip">
                        <div>
                            <span class="promo-tag-badge">%</span> Flat 25% Instant Discount on HDFC Credit Card EMI- Promocode ATHDFCEMI
                        </div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600;">
                            &lt; 1 / 1 &gt;
                        </div>
                    </div>

                </div>
                <?php 
                        $idx++;
                    endforeach;
                else: 
                ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 60px 20px; text-align: center;">
                    <i class="fa-solid fa-hotel" style="font-size: 42px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 20px; color: #0f172a; margin: 0 0 8px 0;">No Hotels Found</h3>
                    <p style="font-size: 14px; color: #64748b; margin: 0 0 20px 0;">We couldn't find any properties matching your search in <?php echo htmlspecialchars($qCity); ?>.</p>
                    <button type="button" class="btn-modify-search" onclick="document.getElementById('btnOpenModifyModal').click()">
                        Try Another Search
                    </button>
                </div>
                <?php endif; ?>
                    </div> <!-- closes #hotelListContainer -->
                </div> <!-- closes #hotelListSplitCol -->

                <!-- Right Map Split Column (Shown when Map View is active, Screenshot 2) -->
                <div class="hotel-map-split-col" id="hotelMapSplitCol" style="display: none; position: relative;">
                    <button type="button" class="map-split-close-btn" onclick="toggleMapView(false)" title="Close Map View">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div id="hotelInteractiveMap" style="width: 100%; height: 100%;"></div>
                </div>

            </div> <!-- closes #resultsSplitWrapper -->

        </main>
    </div>

</div>

<!-- ========================================================
     MODALS SECTION
     ======================================================== -->

<!-- 1. FULL HOTEL / ROOM IMAGE GALLERY MODAL (Akbar Travels Style) -->
<div class="gallery-modal-overlay" id="galleryModalOverlay" role="dialog" aria-modal="true">
    <div class="gallery-modal-header">
        <h3 class="gallery-modal-title" id="galleryModalTitle">Hotel Photo Gallery</h3>
        <div style="display: flex; align-items: center; gap: 16px;">
            <span class="gallery-modal-counter" id="galleryModalCounter">1 / 1</span>
            <button type="button" class="gallery-modal-close-btn" id="btnGalleryClose" title="Close Gallery (Esc)">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <div class="gallery-modal-body">
        <button type="button" class="gallery-nav-btn gallery-nav-prev" id="btnGalleryPrev" title="Previous (Left Arrow)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="gallery-main-img-wrap">
            <img src="" id="galleryMainImg" alt="Gallery Preview">
        </div>

        <button type="button" class="gallery-nav-btn gallery-nav-next" id="btnGalleryNext" title="Next (Right Arrow)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <!-- Bottom Thumbnails Strip -->
    <div class="gallery-thumbnails-strip" id="galleryThumbsStrip">
        <!-- Injected via JavaScript -->
    </div>
</div>

<!-- 2. INTERACTIVE MODIFY SEARCH POPUP MODAL -->
<div class="modify-search-modal-overlay" id="modifySearchModalOverlay" role="dialog" aria-modal="true">
    <div class="modify-search-modal-box">
        <div class="modify-modal-head">
            <h3><i class="fa-solid fa-hotel" style="margin-right: 8px;"></i> Modify Hotel Search</h3>
            <button type="button" class="modify-modal-close" id="btnModifyModalClose">&times;</button>
        </div>

        <div class="modify-modal-content">
            <form action="<?php echo site_url('hotels/search'); ?>" method="GET" id="modifySearchForm">
                
                <div class="modify-grid-form">
                    
                    <!-- Destination City with Autosuggest -->
                    <div class="form-field-wrap autosuggest-dropdown-wrap">
                        <label for="modifyCityInput">Destination City</label>
                        <input type="text" id="modifyCityInput" name="city" value="<?php echo htmlspecialchars($qCity); ?>" placeholder="Where are you going?" autocomplete="off" required>
                        <div class="city-suggestions-box" id="modifyCitySuggestions">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Check-In Date -->
                    <div class="form-field-wrap">
                        <label for="modifyCheckinDate">Check-In</label>
                        <input type="date" id="modifyCheckinDate" name="checkin" value="<?php echo htmlspecialchars($qCheckin); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Check-Out Date -->
                    <div class="form-field-wrap">
                        <label for="modifyCheckoutDate">Check-Out</label>
                        <input type="date" id="modifyCheckoutDate" name="checkout" value="<?php echo htmlspecialchars($qCheckout); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>

                    <!-- Rooms & Guests Quick Summary -->
                    <div class="form-field-wrap">
                        <label>Rooms &amp; Guests</label>
                        <input type="text" id="modifyOccupancySummary" readonly value="<?php echo $qRooms; ?> Room, <?php echo $qAdults; ?> Adults" style="cursor: pointer; background: #f8fafc;">
                    </div>

                </div>

                <!-- Hidden inputs for adults, rooms, children -->
                <input type="hidden" name="rooms" id="hiddenRoomsInput" value="<?php echo $qRooms; ?>">
                <input type="hidden" name="adults" id="hiddenAdultsInput" value="<?php echo $qAdults; ?>">
                <input type="hidden" name="children" id="hiddenChildrenInput" value="<?php echo $qChildren; ?>">
                <input type="hidden" name="roomData" id="hiddenRoomDataInput" value="<?php echo htmlspecialchars($qRoomData); ?>">

                <!-- Steppers Drawer -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        
                        <!-- Rooms Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Rooms</strong>
                                <div style="font-size: 11px; color: #64748b;">Max 8 rooms</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('rooms', -1)">-</button>
                                <span id="displayRoomsCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qRooms; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('rooms', 1)">+</button>
                            </div>
                        </div>

                        <!-- Adults Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Adults</strong>
                                <div style="font-size: 11px; color: #64748b;">Age 12+ yrs</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('adults', -1)">-</button>
                                <span id="displayAdultsCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qAdults; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('adults', 1)">+</button>
                            </div>
                        </div>

                        <!-- Children Stepper -->
                        <div class="stepper-row">
                            <div>
                                <strong style="font-size: 13px; color: #0f172a;">Children</strong>
                                <div style="font-size: 11px; color: #64748b;">Age 0-11 yrs</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button type="button" class="stepper-btn" onclick="stepCounter('children', -1)">-</button>
                                <span id="displayChildrenCount" style="font-weight: 800; min-width: 18px; text-align: center;"><?php echo $qChildren; ?></span>
                                <button type="button" class="stepper-btn" onclick="stepCounter('children', 1)">+</button>
                            </div>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn-update-search-submit">
                    <i class="fa-solid fa-magnifying-glass" style="margin-right: 8px;"></i> UPDATE SEARCH
                </button>

            </form>
        </div>
    </div>
</div>

<!-- Interactive Map View is embedded in split mode above -->

<!-- ========================================================
     JAVASCRIPT: Filtering, Galleries, Modals & Sorting
     ======================================================== -->
<script>
// Hotel Data Array populated from PHP
const HOTELS_DATA = <?php 
    $hotelsJson = array();
    $idx = 0;
    foreach ($hotels as $h) {
        $hId = $h['id'] ?? ('HTL_' . $idx);
        $hero = !empty($h['image']) ? $h['image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
        $gal = !empty($h['gallery']) && is_array($h['gallery']) ? $h['gallery'] : array($hero);
        $roomsFormatted = array();
        if (!empty($h['room_types'])) {
            foreach ($h['room_types'] as $rm) {
                $roomsFormatted[] = array(
                    'name'   => $rm['name'] ?? 'Deluxe Room',
                    'images' => !empty($rm['images']) ? $rm['images'] : $gal
                );
            }
        }
        $detailUrl = site_url('hotels/detail/' . $hId . '?city=' . urlencode($qCity) . '&checkin=' . $qCheckin . '&checkout=' . $qCheckout . '&rooms=' . $qRooms . '&adults=' . $qAdults . '&children=' . $qChildren . (!empty($qRoomData) ? '&roomData=' . urlencode($qRoomData) : '') . (!empty($sId) ? '&search_id=' . urlencode($sId) : '') . (!empty($sTrace) ? '&search_tracing_key=' . urlencode($sTrace) : ''));

        $hotelsJson[] = array(
            'index'     => $idx,
            'id'        => $hId,
            'name'      => $h['name'] ?? 'Hotel',
            'stars'     => (int)($h['star_rating'] ?? 4),
            'rating'    => (float)($h['rating'] ?? 4.5),
            'price'     => (float)($h['price_per_night'] ?? 4500),
            'image'     => $hero,
            'gallery'   => $gal,
            'rooms'     => $roomsFormatted,
            'lat'       => !empty($h['latitude']) ? (float)$h['latitude'] : null,
            'lng'       => !empty($h['longitude']) ? (float)$h['longitude'] : null,
            'detailUrl' => $detailUrl
        );
        $idx++;
    }
    echo json_encode($hotelsJson);
?>;

// ========================================================
// 1. FILTER ACCORDIONS & EXPANDERS
// ========================================================
function toggleFilterSection(headerElem) {
    const row = headerElem.closest('.filter-section-row');
    if (row) {
        row.classList.toggle('collapsed');
    }
}
function toggleFilterCard(headerElem) {
    toggleFilterSection(headerElem);
}

function toggleExtraSection(elemId, triggerElem) {
    const sec = document.getElementById(elemId);
    if (!sec) return;
    const isShown = sec.classList.toggle('show');
    triggerElem.textContent = isShown ? 'View less' : 'View all';
}

// ========================================================
// 2. LIVE FILTERING ENGINE
// ========================================================
const hotelCards = document.querySelectorAll('.hotel-card');
const hotelNameInput = document.getElementById('hotelNameFilterInput');
const filterCheckboxes = document.querySelectorAll('.hotel-filter-cb');
const cbAvailableOnly = document.getElementById('cbShowOnlyAvailable');
const visibleCountElem = document.getElementById('visibleHotelCount');

function applyFilters() {
    const nameQuery = (hotelNameInput ? hotelNameInput.value.trim().toLowerCase() : '');
    const isAvailOnly = cbAvailableOnly ? cbAvailableOnly.checked : false;

    // Collect selected checkbox filters
    const selected = {
        popular: [],
        rating: [],
        stars: [],
        price: [],
        amenity: [],
        chain: [],
        property_type: []
    };

    filterCheckboxes.forEach(cb => {
        if (cb.checked) {
            const type = cb.getAttribute('data-filter-type');
            if (selected[type]) {
                selected[type].push(cb.value.toLowerCase());
            }
        }
    });

    let visibleCount = 0;

    hotelCards.forEach(card => {
        const hName = card.getAttribute('data-name') || '';
        const hPrice = parseFloat(card.getAttribute('data-price') || 0);
        const hStars = parseInt(card.getAttribute('data-stars') || 0);
        const hRating = parseFloat(card.getAttribute('data-rating') || 0);
        const hChain = card.getAttribute('data-chain') || '';
        const hType = card.getAttribute('data-type') || '';
        const hAmenities = card.getAttribute('data-amenities') || '';
        const isAvailable = card.getAttribute('data-available') === '1';

        let show = true;

        // 1. Hotel Name search
        if (nameQuery && !hName.includes(nameQuery)) {
            show = false;
        }

        // 2. Show only available
        if (show && isAvailOnly && !isAvailable) {
            show = false;
        }

        // 2b. Popular Filters (Screenshot 3 Fix: Voyogo Choice, Free Cancellation, Breakfast Available, Wifi)
        if (show && selected.popular.length > 0) {
            for (const pop of selected.popular) {
                if (pop === 'choice' && card.getAttribute('data-voyogo-choice') !== '1') {
                    show = false; break;
                }
                if (pop === 'cancellation' && card.getAttribute('data-free-cancellation') !== '1') {
                    show = false; break;
                }
                if (pop === 'breakfast' && card.getAttribute('data-free-breakfast') !== '1') {
                    show = false; break;
                }
                if (pop === 'wifi' && card.getAttribute('data-wifi') !== '1') {
                    show = false; break;
                }
            }
        }

        // 3. Customer Ratings
        if (show && selected.rating.length > 0) {
            const minSelectedRating = Math.min(...selected.rating.map(Number));
            if (hRating < minSelectedRating) {
                show = false;
            }
        }

        // 4. Star Rating
        if (show && selected.stars.length > 0) {
            const matchStar = selected.stars.some(val => parseInt(val) === hStars);
            if (!matchStar) show = false;
        }

        // 5. Price Range
        if (show && selected.price.length > 0) {
            const inAnyPriceRange = selected.price.some(range => {
                const parts = range.split('-');
                const min = parseFloat(parts[0]);
                const max = parseFloat(parts[1]);
                return hPrice >= min && hPrice <= max;
            });
            if (!inAnyPriceRange) show = false;
        }

        // 6. Amenities
        if (show && selected.amenity.length > 0) {
            const hasAllAmenities = selected.amenity.every(am => hAmenities.includes(am));
            if (!hasAllAmenities) show = false;
        }

        // 7. Chains
        if (show && selected.chain.length > 0) {
            const matchChain = selected.chain.some(ch => hChain.includes(ch));
            if (!matchChain) show = false;
        }

        // 8. Property Type
        if (show && selected.property_type.length > 0) {
            const matchType = selected.property_type.some(tp => hType.includes(tp));
            if (!matchType) show = false;
        }

        // Render visibility
        if (show) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if (visibleCountElem) {
        visibleCountElem.textContent = visibleCount;
    }

    // Keep map markers synchronized with visible hotel cards
    updateMapMarkersVisibility();
}

// Attach filter listeners
if (hotelNameInput) {
    hotelNameInput.addEventListener('input', applyFilters);
}
filterCheckboxes.forEach(cb => {
    cb.addEventListener('change', applyFilters);
});
if (cbAvailableOnly) {
    cbAvailableOnly.addEventListener('change', applyFilters);
}

// Reset filters button
const btnReset = document.getElementById('btnResetFilters');
if (btnReset) {
    btnReset.addEventListener('click', () => {
        if (hotelNameInput) hotelNameInput.value = '';
        filterCheckboxes.forEach(cb => cb.checked = false);
        if (cbAvailableOnly) cbAvailableOnly.checked = false;
        applyFilters();
    });
}

// ========================================================
// 3. SORTING HANDLER
// ========================================================
let currentPriceSortOrder = 'asc';

function sortHotels(criteria, sortElement) {
    document.querySelectorAll('.sort-item').forEach(el => el.classList.remove('active'));
    sortElement.classList.add('active');

    const container = document.getElementById('hotelListContainer');
    const cardsArray = Array.from(hotelCards);

    cardsArray.sort((a, b) => {
        if (criteria === 'rating') {
            const rA = parseFloat(a.getAttribute('data-rating') || 0);
            const rB = parseFloat(b.getAttribute('data-rating') || 0);
            return rB - rA; // High to Low
        } else if (criteria === 'price') {
            const pA = parseFloat(a.getAttribute('data-price') || 0);
            const pB = parseFloat(b.getAttribute('data-price') || 0);
            if (currentPriceSortOrder === 'asc') {
                return pA - pB;
            } else {
                return pB - pA;
            }
        } else { // featured
            const fA = parseInt(a.getAttribute('data-featured') || 0);
            const fB = parseInt(b.getAttribute('data-featured') || 0);
            return fA - fB;
        }
    });

    if (criteria === 'price') {
        currentPriceSortOrder = (currentPriceSortOrder === 'asc') ? 'desc' : 'asc';
    }

    cardsArray.forEach(card => container.appendChild(card));
}

// ========================================================
// 4. INLINE "SELECT ROOM" DRAWER TOGGLE
// ========================================================
function toggleRoomDrawer(hotelIdx) {
    const drawer = document.getElementById('roomsDrawer_' + hotelIdx);
    if (!drawer) return;
    drawer.classList.toggle('open');
}

// Shortlist toggle
function toggleShortlist(btn) {
    btn.classList.toggle('active');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('active')) {
        icon.className = 'fa-solid fa-heart';
        btn.style.color = '#e50027';
    } else {
        icon.className = 'fa-regular fa-heart';
        btn.style.color = '';
    }
}

// ========================================================
// 5. AKBAR TRAVELS STYLE FULL IMAGE GALLERY MODAL
// ========================================================
let currentGalleryImages = [];
let currentGalleryIdx = 0;

const galleryModal = document.getElementById('galleryModalOverlay');
const galleryTitle = document.getElementById('galleryModalTitle');
const galleryCounter = document.getElementById('galleryModalCounter');
const galleryMainImg = document.getElementById('galleryMainImg');
const galleryThumbsStrip = document.getElementById('galleryThumbsStrip');

function openHotelGallery(hotelIdx) {
    const hotel = HOTELS_DATA[hotelIdx];
    if (!hotel) return;
    setupGalleryModal(hotel.name + ' - Photo Gallery', hotel.gallery);
}

function openRoomGallery(hotelIdx, roomIdx) {
    const hotel = HOTELS_DATA[hotelIdx];
    if (!hotel || !hotel.rooms || !hotel.rooms[roomIdx]) return;
    const room = hotel.rooms[roomIdx];
    setupGalleryModal(hotel.name + ' — ' + room.name + ' Photos', room.images);
}

function setupGalleryModal(title, images) {
    if (!images || images.length === 0) return;
    currentGalleryImages = images;
    currentGalleryIdx = 0;

    galleryTitle.textContent = title;
    galleryThumbsStrip.innerHTML = '';

    images.forEach((imgUrl, i) => {
        const thumb = document.createElement('div');
        thumb.className = 'gallery-thumb-item' + (i === 0 ? ' active' : '');
        thumb.innerHTML = '<img src="' + imgUrl + '" alt="Thumb ' + (i+1) + '">';
        thumb.onclick = () => showGallerySlide(i);
        galleryThumbsStrip.appendChild(thumb);
    });

    showGallerySlide(0);
    galleryModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function showGallerySlide(idx) {
    if (idx < 0) idx = currentGalleryImages.length - 1;
    if (idx >= currentGalleryImages.length) idx = 0;
    currentGalleryIdx = idx;

    galleryMainImg.src = currentGalleryImages[currentGalleryIdx];
    galleryCounter.textContent = (currentGalleryIdx + 1) + ' / ' + currentGalleryImages.length;

    const allThumbs = galleryThumbsStrip.querySelectorAll('.gallery-thumb-item');
    allThumbs.forEach((t, i) => {
        t.classList.toggle('active', i === currentGalleryIdx);
    });

    // Auto scroll thumbnail into view
    if (allThumbs[currentGalleryIdx]) {
        allThumbs[currentGalleryIdx].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }
}

document.getElementById('btnGalleryPrev').onclick = () => showGallerySlide(currentGalleryIdx - 1);
document.getElementById('btnGalleryNext').onclick = () => showGallerySlide(currentGalleryIdx + 1);

function closeGallery() {
    galleryModal.classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('btnGalleryClose').onclick = closeGallery;

// Keyboard navigation (Esc, Left, Right)
window.addEventListener('keydown', (e) => {
    if (galleryModal.classList.contains('active')) {
        if (e.key === 'Escape') closeGallery();
        else if (e.key === 'ArrowLeft') showGallerySlide(currentGalleryIdx - 1);
        else if (e.key === 'ArrowRight') showGallerySlide(currentGalleryIdx + 1);
    }
});

// ========================================================
// 6. MODIFY SEARCH POPUP MODAL & STEPPERS
// ========================================================
const modifyModal = document.getElementById('modifySearchModalOverlay');
const btnOpenModify = document.getElementById('btnOpenModifyModal');
const btnCloseModify = document.getElementById('btnModifyModalClose');

if (btnOpenModify) {
    btnOpenModify.onclick = () => {
        modifyModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
}
if (btnCloseModify) {
    btnCloseModify.onclick = () => {
        modifyModal.classList.remove('active');
        document.body.style.overflow = '';
    };
}
modifyModal.onclick = (e) => {
    if (e.target === modifyModal) {
        modifyModal.classList.remove('active');
        document.body.style.overflow = '';
    }
};

// Steppers logic
function stepCounter(type, change) {
    const countElem = document.getElementById('display' + type.charAt(0).toUpperCase() + type.slice(1) + 'Count');
    const hiddenInput = document.getElementById('hidden' + type.charAt(0).toUpperCase() + type.slice(1) + 'Input');
    if (!countElem || !hiddenInput) return;

    let current = parseInt(hiddenInput.value) || 0;
    current += change;

    if (type === 'rooms') {
        if (current < 1) current = 1;
        if (current > 8) current = 8;
    } else if (type === 'adults') {
        if (current < 1) current = 1;
        if (current > 16) current = 16;
    } else if (type === 'children') {
        if (current < 0) current = 0;
        if (current > 8) current = 8;
    }

    hiddenInput.value = current;
    countElem.textContent = current;

    // Update summary string
    const r = document.getElementById('hiddenRoomsInput').value;
    const a = document.getElementById('hiddenAdultsInput').value;
    const c = document.getElementById('hiddenChildrenInput').value;
    const summaryInput = document.getElementById('modifyOccupancySummary');
    if (summaryInput) {
        summaryInput.value = r + ' Room' + (r > 1 ? 's' : '') + ', ' + a + ' Adult' + (a > 1 ? 's' : '') + (c > 0 ? (', ' + c + ' Child') : '');
    }
}

// City Autosuggest in Modify Modal
const popularDestinations = [
    'Tirunelveli, Tamil Nadu, India',
    'Goa, India',
    'Dubai, United Arab Emirates',
    'Mumbai, Maharashtra, India',
    'New Delhi, Delhi, India',
    'Bengaluru, Karnataka, India',
    'Chennai, Tamil Nadu, India',
    'Jaipur, Rajasthan, India',
    'Kochi (Cochin), Kerala, India',
    'Singapore',
    'Bangkok, Thailand',
    'Maldives',
    'Bali, Indonesia',
    'London, United Kingdom'
];

const cityInput = document.getElementById('modifyCityInput');
const suggestionsBox = document.getElementById('modifyCitySuggestions');

if (cityInput && suggestionsBox) {
    cityInput.addEventListener('input', () => {
        const q = cityInput.value.trim().toLowerCase();
        if (q.length === 0) {
            suggestionsBox.style.display = 'none';
            return;
        }
        const matches = popularDestinations.filter(d => d.toLowerCase().includes(q));
        if (matches.length > 0) {
            suggestionsBox.innerHTML = '';
            matches.forEach(item => {
                const div = document.createElement('div');
                div.className = 'city-suggestion-item';
                div.innerHTML = '<i class="fa-solid fa-location-dot" style="color: #ef4444;"></i> ' + item;
                div.onclick = () => {
                    cityInput.value = item;
                    suggestionsBox.style.display = 'none';
                };
                suggestionsBox.appendChild(div);
            });
            suggestionsBox.style.display = 'block';
        } else {
            suggestionsBox.style.display = 'none';
        }
    });

    document.addEventListener('click', (e) => {
        if (!cityInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
}

// ========================================================
// 7. INTERACTIVE SPLIT MAP VIEW ENGINE (Akbar Travels Style Screenshot 2)
// ========================================================
let hotelMap = null;
let hotelMarkers = [];
let isMapViewActive = false;

function toggleMapView(enable) {
    const resultsContainer = document.querySelector('.hotel-results-container');
    const backBar = document.getElementById('mapViewBackBar');
    const mapCol = document.getElementById('hotelMapSplitCol');
    
    if (typeof enable === 'boolean') {
        isMapViewActive = enable;
    } else {
        isMapViewActive = !isMapViewActive;
    }

    if (isMapViewActive) {
        if (resultsContainer) resultsContainer.classList.add('map-split-active');
        if (backBar) backBar.style.display = 'block';
        if (mapCol) mapCol.style.display = 'block';
        initOrUpdateHotelMap();
    } else {
        if (resultsContainer) resultsContainer.classList.remove('map-split-active');
        if (backBar) backBar.style.display = 'none';
        if (mapCol) mapCol.style.display = 'none';
    }
}

// City coordinates mapping for automatic centering
const CITY_COORDS_MAP = {
    'dubai': [25.2048, 55.2708],
    'mumbai': [19.0760, 72.8777],
    'chennai': [13.0827, 80.2707],
    'delhi': [28.6139, 77.2090],
    'bengaluru': [12.9716, 77.5946],
    'bangalore': [12.9716, 77.5946],
    'goa': [15.2993, 74.1240],
    'tirunelveli': [8.7139, 77.7567],
    'madurai': [9.9252, 78.1198],
    'kochi': [9.9312, 76.2673],
    'singapore': [1.3521, 103.8198],
    'london': [51.5074, -0.1278]
};

function getCityCenterCoords() {
    const cityStr = '<?php echo strtolower(addslashes($qCity)); ?>';
    for (const k in CITY_COORDS_MAP) {
        if (cityStr.includes(k)) return CITY_COORDS_MAP[k];
    }
    // Check first hotel with lat/lng
    for (const h of HOTELS_DATA) {
        if (h.lat && h.lng) return [h.lat, h.lng];
    }
    return [13.0827, 80.2707]; // Chennai default
}

function initOrUpdateHotelMap() {
    if (typeof L === 'undefined') {
        setTimeout(initOrUpdateHotelMap, 300);
        return;
    }

    const mapElem = document.getElementById('hotelInteractiveMap');
    if (!mapElem) return;

    const center = getCityCenterCoords();

    if (!hotelMap) {
        hotelMap = L.map('hotelInteractiveMap', {
            center: center,
            zoom: 12,
            zoomControl: true
        });

        // CartoDB Voyager clean tiles (Google-like pastel map style matching Screenshot 2)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CARTO',
            maxZoom: 19,
            subdomains: 'abcd'
        }).addTo(hotelMap);
    } else {
        hotelMap.invalidateSize();
    }

    // Clear existing markers
    hotelMarkers.forEach(m => m.marker.remove());
    hotelMarkers = [];

    const bounds = [];

    HOTELS_DATA.forEach((hotel, idx) => {
        let lat = hotel.lat;
        let lng = hotel.lng;

        // If coordinates missing, generate deterministic offset around city center
        if (!lat || !lng) {
            const angle = (idx * 137.5) * (Math.PI / 180);
            const radius = 0.015 + ((idx % 7) * 0.008);
            lat = center[0] + (Math.sin(angle) * radius);
            lng = center[1] + (Math.cos(angle) * radius);
        }

        bounds.push([lat, lng]);

        // Custom red pin icon matching Akbar Travels Screenshot 2
        const pinIcon = L.divIcon({
            className: 'custom-map-marker',
            html: `<div class="map-marker-pin" id="markerPin_${idx}"></div>`,
            iconSize: [32, 38],
            iconAnchor: [16, 38],
            popupAnchor: [0, -38]
        });

        const starStr = '★'.repeat(Math.max(1, Math.min(5, hotel.stars)));
        const popupContent = `
            <div class="map-hotel-popup-box">
                <div class="map-popup-header">
                    <h4 class="map-popup-title">${hotel.name}</h4>
                    <span class="map-popup-stars">${starStr}</span>
                </div>
                <div class="map-popup-body">
                    <img src="${hotel.image}" alt="${hotel.name}" class="map-popup-thumb">
                    <div class="map-popup-actions">
                        <a href="${hotel.detailUrl}" class="btn-map-select-room">Select Room</a>
                        <button type="button" class="btn-map-shortlist" onclick="alert('Added ${hotel.name.replace(/'/g, "\\'")} to Shortlist!')">
                            <i class="fa-regular fa-heart"></i> Shortlist
                        </button>
                    </div>
                </div>
            </div>
        `;

        const marker = L.marker([lat, lng], { icon: pinIcon }).addTo(hotelMap);
        marker.bindPopup(popupContent, { maxWidth: 300, minWidth: 260 });

        marker.on('click', () => {
            // Scroll to hotel card in left column
            const card = document.getElementById(`hotelCard_${idx}`);
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                card.style.outline = '2px solid #ef4444';
                setTimeout(() => { card.style.outline = ''; }, 2000);
            }
        });

        hotelMarkers.push({
            index: idx,
            id: hotel.id,
            marker: marker,
            lat: lat,
            lng: lng
        });
    });

    if (bounds.length > 0) {
        hotelMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
    }
}

function focusHotelOnMap(idx) {
    if (!isMapViewActive) {
        toggleMapView(true);
    }
    setTimeout(() => {
        const item = hotelMarkers.find(m => m.index === idx);
        if (item && hotelMap) {
            hotelMap.setView([item.lat, item.lng], 15, { animate: true });
            item.marker.openPopup();
        }
    }, 250);
}

function updateMapMarkersVisibility() {
    if (!hotelMap || hotelMarkers.length === 0) return;
    hotelCards.forEach((card, idx) => {
        const isVisible = card.style.display !== 'none';
        const item = hotelMarkers.find(m => m.index === idx);
        if (item) {
            if (isVisible) {
                if (!hotelMap.hasLayer(item.marker)) {
                    item.marker.addTo(hotelMap);
                }
            } else {
                if (hotelMap.hasLayer(item.marker)) {
                    item.marker.remove();
                }
            }
        }
    });
}

// Share button copy link
const btnShare = document.getElementById('btnShareSearch');
if (btnShare) {
    btnShare.onclick = () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Search link copied to clipboard!');
        }).catch(() => {
            prompt('Copy this search link:', window.location.href);
        });
    };
}
</script>
