<!-- SheetJS for Client-Side Excel Export (.xlsx) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<style>
/* Full Screen Width Expansion */
.store-container,
.store-container.wide-container {
    max-width: 98% !important;
    width: 98% !important;
    margin: 14px auto !important;
    padding: 0 10px !important;
}

/* Pulse Animation */
@keyframes pulseLive {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.15); }
}

/* Page Layout with Toggleable Left Sidebar */
.gt-b2b-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 20px;
    align-items: start;
    transition: all 0.25s ease;
}
.gt-b2b-layout.full-width {
    grid-template-columns: 1fr !important;
}
.gt-b2b-layout.full-width .gt-b2b-sidebar {
    display: none !important;
}
@media (max-width: 991px) {
    .gt-b2b-layout {
        grid-template-columns: 1fr;
    }
    .gt-b2b-sidebar {
        width: 100%;
        position: static;
        display: flex;
        flex-direction: row;
        overflow-x: auto;
    }
    .gt-nav-link {
        flex: 1;
        justify-content: center;
        padding: 12px 14px;
        white-space: nowrap;
        border-left: none !important;
        border-bottom: 3px solid transparent;
    }
    .gt-nav-link.active {
        border-bottom-color: #f97316 !important;
    }
}

/* Left Sidebar Menu (Matching Flight & Hotel Pages) */
.gt-b2b-sidebar {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    overflow: hidden;
    position: sticky;
    top: 20px;
    z-index: 10;
    width: 240px;
    height: fit-content;
}
.gt-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.15s;
    border-left: 4px solid transparent;
    color: #475569;
}
.gt-nav-link:hover {
    background: #f8fafc;
    color: #0f172a;
}
.gt-nav-link.active {
    color: #ea580c;
    font-weight: 700;
    border-left-color: #f97316;
    background: #fff7ed;
}
.gt-nav-link i {
    font-size: 16px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}

/* Header & Title Bar */
.gt-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
}
.gt-title {
    font-size: 22px;
    font-weight: 800;
    color: #09204b;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}
.gt-live-tag {
    background: #dcfce7;
    color: #15803d;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.5px;
}
.gt-pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16a34a;
    display: inline-block;
    animation: pulseLive 1.5s infinite;
}
.gt-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 4px 0 0 0;
}

/* Action Buttons */
.gt-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    transition: all 0.15s;
}
.btn-action:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
.btn-action-excel:hover {
    background: #f0fdf4;
    border-color: #86efac;
    color: #15803d;
}

/* Stat Cards (4 Cards Balanced) */
.gt-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
@media (max-width: 1280px) {
    .gt-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 680px) {
    .gt-stats-grid { grid-template-columns: 1fr; }
}

.gt-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    transition: transform 0.15s, box-shadow 0.15s;
}
.gt-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.gt-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.gt-stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gt-stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

/* Month Filter Tabs & Instant Search */
.gt-filter-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.gt-month-pills {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    margin-bottom: 14px;
}
.gt-month-pills::-webkit-scrollbar { height: 4px; }
.gt-month-pills::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.gt-month-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    color: #475569;
    background: #f1f5f9;
    white-space: nowrap;
    transition: all 0.15s;
    border: 1px solid transparent;
}
.gt-month-pill:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.gt-month-pill.active {
    background: #09204b;
    color: #ffffff;
    border-color: #09204b;
}

/* Search Input */
.gt-search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.gt-search-wrapper i {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 14px;
}
.gt-search-input {
    width: 100%;
    padding: 10px 16px 10px 38px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    background: #f8fafc;
}
.gt-search-input:focus {
    border-color: #78B722;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(120, 183, 34, 0.15);
}

/* Master Table */
.gt-table-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.gt-table-scroll {
    overflow-x: auto;
    max-height: 720px;
}
.gt-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 12.5px;
    text-align: left;
    white-space: nowrap;
}
.gt-table th {
    background: #09204b;
    color: #ffffff;
    padding: 12px 14px;
    font-weight: 700;
    font-size: 11.5px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: sticky;
    top: 0;
    z-index: 10;
    border-right: 1px solid rgba(255,255,255,0.08);
}
.gt-table td {
    padding: 11px 14px;
    border-bottom: 1px solid #f1f5f9;
    border-right: 1px solid #f8fafc;
    color: #1e293b;
    vertical-align: middle;
    background: #ffffff;
}
.gt-table tr:hover td {
    background: #f8fafc !important;
}

/* Sticky S.NO column on horizontal scroll */
.gt-table th.col-sno-head {
    position: sticky;
    left: 0;
    top: 0;
    z-index: 15;
    background: #09204b;
}
.gt-table td.col-sno {
    position: sticky;
    left: 0;
    z-index: 5;
    background: #ffffff;
}

/* Month Divider Header Row in Table */
.month-header-row td {
    background: #ffe4e6 !important;
    color: #9f1239 !important;
    font-weight: 800 !important;
    font-size: 13px !important;
    letter-spacing: 0.5px !important;
    padding: 8px 16px !important;
    border-top: 2px solid #f43f5e !important;
    border-bottom: 2px solid #f43f5e !important;
}

/* Badges */
.badge-code {
    font-weight: 700;
    color: #0284c7;
    background: #e0f2fe;
    padding: 3px 8px;
    border-radius: 5px;
    display: inline-block;
    font-family: monospace;
    font-size: 12px;
}
.badge-amount {
    font-weight: 700;
    color: #0f172a;
}
.badge-need {
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
}
.badge-need.has-need {
    background: #fee2e2;
    color: #dc2626;
}
.badge-need.no-need {
    color: #94a3b8;
}

.badge-status {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.ticket-taken {
    background: #dcfce7;
    color: #15803d;
}
.visa-include {
    background: #f3e8ff;
    color: #7e22ce;
}
.badge-person {
    color: #334155;
    font-weight: 600;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11.5px;
    display: inline-block;
}
.badge-manager {
    color: #7c2d12;
    background: #ffedd5;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11.5px;
    display: inline-block;
}
</style>

<!-- Main Container with B2B Left Sidebar & Full-Width Toggle -->
<div class="gt-b2b-layout" id="gtMainLayout">

    <!-- Left Vertical Nav matching user interface properly -->
    <aside class="gt-b2b-sidebar" id="gtSidebar">
        <a href="<?php echo site_url('franchise/flight'); ?>" class="gt-nav-link">
            <i class="fa-solid fa-plane-departure" style="color: #64748b;"></i>
            <span>Flight</span>
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" class="gt-nav-link">
            <i class="fa-solid fa-hotel" style="color: #64748b;"></i>
            <span>Hotel</span>
        </a>
        <a href="<?php echo site_url('franchise/group-tours'); ?>" class="gt-nav-link active">
            <i class="fa-solid fa-users-rectangle" style="color: #ea580c;"></i>
            <span>Group Tour Follow Up</span>
        </a>
    </aside>

    <!-- Right Content Area: Read-Only Master Board -->
    <main style="min-width: 0;">
        
        <!-- Header Bar -->
        <div class="gt-header-bar">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                    <h1 class="gt-title">
                        <i class="fa-solid fa-users-rectangle" style="color: #78B722;"></i>
                        Group Tour Follow Up
                    </h1>
                    <span class="gt-live-tag">
                        <span class="gt-pulse-dot"></span> Live Departure Board
                    </span>
                </div>
                <p class="gt-subtitle">
                    <strong>SKY PLANET HOLIDAYS</strong> &bull; Group Tour Board Follow-Up Sheet &bull; Live Departures &bull; Passenger Counts &bull; Visa &amp; Milestone Tracking
                </p>
            </div>

            <!-- Action Buttons in Header Row -->
            <div class="gt-actions">
                <button type="button" class="btn-action" id="btnToggleSidebar" onclick="toggleSidebarLayout()" title="Toggle Left Menu / Full Width Table">
                    <i class="fa-solid fa-table-columns" id="toggleIcon"></i>
                    <span id="toggleSidebarText">Hide Left Menu</span>
                </button>
                <button type="button" class="btn-action btn-action-excel" onclick="exportTableToExcel()" title="Export current sheet to Excel (.xlsx)">
                    <i class="fa-solid fa-file-excel" style="color: #16a34a;"></i>
                    <span>Export Excel</span>
                </button>
                <button type="button" class="btn-action" onclick="window.print()" title="Print departures">
                    <i class="fa-solid fa-print"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>

        <!-- KPI Summary Stat Cards (All 4 Cards Fully Visible in Row) -->
        <div class="gt-stats-grid">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Total Group Tours</div>
                    <div class="gt-stat-val"><?php echo number_format((float)($stats['total_tours'] ?? count($tours))); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Block Capacity (Grp. Tkn)</div>
                    <div class="gt-stat-val"><?php echo number_format((float)($stats['total_grp_tkn'] ?? 0)); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #fff7ed; color: #ea580c;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Total Seats Needed</div>
                    <div class="gt-stat-val" style="color: #ea580c;"><?php echo number_format((float)($stats['total_needed'] ?? 0)); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #fdf2f8; color: #db2777;">
                    <i class="fa-solid fa-passport"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Visa Processing Pending</div>
                    <div class="gt-stat-val" style="color: #db2777;"><?php echo number_format((float)($stats['visa_pending_count'] ?? 0)); ?></div>
                </div>
            </div>
        </div>

        <!-- Month Filter Tabs & Instant Search -->
        <div class="gt-filter-panel">
            <div class="gt-month-pills">
                <a href="<?php echo site_url('franchise/group-tours?month=all'); ?>" class="gt-month-pill <?php echo ($selected_month == 'all' || empty($selected_month)) ? 'active' : ''; ?>">
                    All Months (<?php echo count($tours); ?>)
                </a>
                <?php foreach ($months as $m): ?>
                <a href="<?php echo site_url('franchise/group-tours?month=' . urlencode($m['month_group'])); ?>" class="gt-month-pill <?php echo ($selected_month == $m['month_group']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($m['month_group']); ?> (<?php echo $m['tour_count']; ?>)
                </a>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div class="gt-search-wrapper" style="flex: 1; min-width: 280px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="gt-search-input" id="searchFilterInput" placeholder="Instant search by Tour Code, Country, Manager, or Person..." oninput="filterTableRealtime(this.value)">
                </div>
                <div style="font-size: 13px; color: #64748b; font-weight: 500;">
                    Showing <strong id="visibleRowCount" style="color: #0f172a;"><?php echo count($tours); ?></strong> of <strong><?php echo count($tours); ?></strong> tour departures
                </div>
            </div>
        </div>

        <!-- Read-Only Master 15-Column Table -->
        <div class="gt-table-container">
            <div class="gt-table-scroll">
                <table class="gt-table" id="groupTourMasterTable">
                    <thead>
                        <tr>
                            <th class="col-sno-head" style="width: 50px;">S. NO</th>
                            <th>DATE</th>
                            <th>TOUR CODE</th>
                            <th>COUNTRY NAME</th>
                            <th>TOUR AMOUNT</th>
                            <th>CONFIRMED</th>
                            <th>GRP. TKN</th>
                            <th>NEED</th>
                            <th>45 DAYS REMINDER</th>
                            <th>30 DAYS REMINDER</th>
                            <th>TICKET STATUS</th>
                            <th>VISA STATUS</th>
                            <th>HANDLING PERSON</th>
                            <th>TRAVEL BAG</th>
                            <th>TOUR MANAGER</th>
                        </tr>
                    </thead>
                    <tbody id="tourTableBody">
                        <?php if (empty($tours)): ?>
                        <tr>
                            <td colspan="15" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fa-regular fa-folder-open" style="font-size: 32px; display: block; margin-bottom: 10px; color: #cbd5e1;"></i>
                                No group tour records found for this selection.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php 
                        $currentMonth = '';
                        $rowSeq = 1;
                        foreach ($tours as $t): 
                            $tourMonth = $t['month_group'] ?: 'General';
                            if ($tourMonth !== $currentMonth):
                                $currentMonth = $tourMonth;
                        ?>
                        <!-- Month Divider Row Matching PDF -->
                        <tr class="month-header-row" data-month-header="<?php echo htmlspecialchars($currentMonth); ?>">
                            <td colspan="15">
                                <i class="fa-regular fa-calendar-days" style="margin-right: 6px;"></i>
                                <?php echo htmlspecialchars($currentMonth); ?>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr class="tour-data-row" 
                            data-month="<?php echo htmlspecialchars($t['month_group'] ?? ''); ?>"
                            data-search="<?php echo htmlspecialchars(strtolower(($t['tour_code'] ?? '') . ' ' . ($t['country_name'] ?? '') . ' ' . ($t['handling_person'] ?? '') . ' ' . ($t['tour_manager'] ?? '') . ' ' . ($t['tour_date'] ?? '') . ' ' . ($t['visa_status'] ?? ''))); ?>">
                            
                            <!-- 1. S. NO (Sticky on Horizontal Scroll) -->
                            <td class="col-sno" style="font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($t['s_no'] ?: $rowSeq); ?></td>

                            <!-- 2. DATE -->
                            <td style="color: #334155; font-weight: 500;">
                                <i class="fa-regular fa-calendar" style="color: #0284c7; margin-right: 4px; font-size: 11px;"></i>
                                <?php echo htmlspecialchars($t['tour_date'] ?: '-'); ?>
                            </td>

                            <!-- 3. TOUR CODE -->
                            <td>
                                <span class="badge-code"><?php echo htmlspecialchars($t['tour_code'] ?: '-'); ?></span>
                            </td>

                            <!-- 4. COUNTRY NAME -->
                            <td style="font-weight: 700; color: #0f172a;">
                                <?php echo htmlspecialchars($t['country_name'] ?: '-'); ?>
                            </td>

                            <!-- 5. TOUR AMOUNT -->
                            <td class="badge-amount">
                                <?php 
                                $amtStr = trim((string)($t['tour_amount'] ?? ''));
                                if ($amtStr === '' || $amtStr === '-' || $amtStr === '0') {
                                    echo '-';
                                } else {
                                    $cleanAmt = str_replace([',', ' ', '₹', 'INR'], '', $amtStr);
                                    if (is_numeric($cleanAmt) && (float)$cleanAmt > 0) {
                                        echo number_format((float)$cleanAmt);
                                    } else {
                                        echo htmlspecialchars($amtStr);
                                    }
                                }
                                ?>
                            </td>

                            <!-- 6. CONFIRMED -->
                            <td>
                                <?php 
                                $confVal = trim((string)($t['confirmed'] ?? ''));
                                if (!empty($confVal) && $confVal !== '-'): 
                                ?>
                                    <span style="font-family: monospace; font-weight: 700; color: #0284c7; background: #f0f9ff; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd;">
                                        <?php echo htmlspecialchars($confVal); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 7. GRP. TKN -->
                            <td style="font-weight: 600; color: #334155;">
                                <?php echo htmlspecialchars($t['grp_tkn'] ?: '-'); ?>
                            </td>

                            <!-- 8. NEED -->
                            <td>
                                <?php 
                                $needVal = trim((string)($t['need'] ?? ''));
                                $hasNeed = (!empty($needVal) && $needVal !== '-' && (int)$needVal > 0);
                                ?>
                                <span class="badge-need <?php echo $hasNeed ? 'has-need' : 'no-need'; ?>">
                                    <?php echo htmlspecialchars($needVal ?: '-'); ?>
                                </span>
                            </td>

                            <!-- 9. 45 DAYS REMINDER -->
                            <td>
                                <?php if (!empty($t['reminder_45']) && $t['reminder_45'] !== '-'): ?>
                                    <span style="background: #fef9c3; color: #854d0e; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-regular fa-bell" style="font-size: 10px;"></i>
                                        <?php echo htmlspecialchars($t['reminder_45']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 10. 30 DAYS REMINDER -->
                            <td>
                                <?php if (!empty($t['reminder_30']) && $t['reminder_30'] !== '-'): ?>
                                    <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 10px;"></i>
                                        <?php echo htmlspecialchars($t['reminder_30']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 11. TICKET STATUS -->
                            <td>
                                <?php if (!empty($t['ticket_status']) && $t['ticket_status'] !== '-'): ?>
                                    <span class="badge-status ticket-taken">
                                        <i class="fa-solid fa-check" style="margin-right: 3px;"></i>
                                        <?php echo htmlspecialchars($t['ticket_status']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 12. VISA STATUS -->
                            <td>
                                <?php if (!empty($t['visa_status']) && $t['visa_status'] !== '-'): ?>
                                    <span class="badge-status visa-include">
                                        <i class="fa-solid fa-passport" style="margin-right: 3px;"></i>
                                        <?php echo htmlspecialchars($t['visa_status']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 13. HANDLING PERSON -->
                            <td>
                                <?php if (!empty($t['handling_person']) && $t['handling_person'] !== '-'): ?>
                                    <span class="badge-person">
                                        <i class="fa-regular fa-user" style="margin-right: 4px; font-size: 10px; color: #64748b;"></i>
                                        <?php echo htmlspecialchars($t['handling_person']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 14. TRAVEL BAG -->
                            <td style="color: #64748b;">
                                <?php echo htmlspecialchars($t['travel_bag'] ?: '-'); ?>
                            </td>

                            <!-- 15. TOUR MANAGER -->
                            <td>
                                <?php if (!empty($t['tour_manager']) && $t['tour_manager'] !== '-'): ?>
                                    <span class="badge-manager">
                                        <i class="fa-solid fa-user-tie" style="margin-right: 4px; font-size: 10px;"></i>
                                        <?php echo htmlspecialchars($t['tour_manager']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php 
                            $rowSeq++;
                            endforeach; 
                        ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script>
// 1. Toggle Full Screen Table View (Hides/Shows Sidebar) with LocalStorage
function toggleSidebarLayout() {
    const layout = document.getElementById('gtMainLayout');
    const btnText = document.getElementById('toggleSidebarText');
    const icon = document.getElementById('toggleIcon');
    const isFull = layout.classList.toggle('full-width');
    
    if (isFull) {
        btnText.textContent = 'Show Left Menu';
        icon.className = 'fa-solid fa-bars';
        localStorage.setItem('voyogo_gt_sidebar', 'collapsed');
    } else {
        btnText.textContent = 'Hide Left Menu';
        icon.className = 'fa-solid fa-table-columns';
        localStorage.setItem('voyogo_gt_sidebar', 'expanded');
    }
}

// Check saved preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const pref = localStorage.getItem('voyogo_gt_sidebar');
    if (pref === 'collapsed') {
        const layout = document.getElementById('gtMainLayout');
        const btnText = document.getElementById('toggleSidebarText');
        const icon = document.getElementById('toggleIcon');
        if (layout && btnText && icon) {
            layout.classList.add('full-width');
            btnText.textContent = 'Show Left Menu';
            icon.className = 'fa-solid fa-bars';
        }
    }
});

// 2. Real-time Search Filter Across Table
function filterTableRealtime(query) {
    const q = query.trim().toLowerCase();
    const rows = document.querySelectorAll('.tour-data-row');
    const monthHeaders = document.querySelectorAll('.month-header-row');
    let visibleCount = 0;

    rows.forEach(r => {
        const text = r.getAttribute('data-search') || '';
        if (!q || text.includes(q)) {
            r.style.display = '';
            visibleCount++;
        } else {
            r.style.display = 'none';
        }
    });

    monthHeaders.forEach(mh => {
        const monthName = mh.getAttribute('data-month-header');
        const hasVisible = Array.from(rows).some(r => r.getAttribute('data-month') === monthName && r.style.display !== 'none');
        mh.style.display = hasVisible ? '' : 'none';
    });

    const countElem = document.getElementById('visibleRowCount');
    if (countElem) countElem.textContent = visibleCount;
}

// 3. Export Table to Excel (.xlsx)
function exportTableToExcel() {
    const table = document.getElementById('groupTourMasterTable');
    if (!table) return;

    const rows = [];
    rows.push([
        'S. NO', 'DATE', 'TOUR CODE', 'COUNTRY NAME', 'TOUR AMOUNT',
        'CONFIRMED', 'GRP. TKN', 'NEED', '45 DAYS REMINDER', '30 DAYS REMINDER',
        'TICKET STATUS', 'VISA STATUS', 'HANDLING PERSON', 'TRAVEL BAG', 'TOUR MANAGER'
    ]);

    const dataRows = document.querySelectorAll('.tour-data-row');
    dataRows.forEach(tr => {
        if (tr.style.display === 'none') return;
        const tds = tr.querySelectorAll('td');
        if (tds.length >= 15) {
            rows.push([
                tds[0].innerText.trim(),
                tds[1].innerText.trim(),
                tds[2].innerText.trim(),
                tds[3].innerText.trim(),
                tds[4].innerText.trim(),
                tds[5].innerText.trim(),
                tds[6].innerText.trim(),
                tds[7].innerText.trim(),
                tds[8].innerText.trim(),
                tds[9].innerText.trim(),
                tds[10].innerText.trim(),
                tds[11].innerText.trim(),
                tds[12].innerText.trim(),
                tds[13].innerText.trim(),
                tds[14].innerText.trim()
            ]);
        }
    });

    const ws = XLSX.utils.aoa_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Group_Tour_Follow_Up");

    const todayStr = new Date().toISOString().split('T')[0];
    XLSX.writeFile(wb, "Group_Tour_Follow_Up_" + todayStr + ".xlsx");
}
</script>
