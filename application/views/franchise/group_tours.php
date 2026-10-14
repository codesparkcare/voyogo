<!-- SheetJS for Client-Side Excel Export (.xlsx) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<style>
/* Page Specific Styling */
.gt-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
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
.gt-subtitle {
    font-size: 13px;
    color: #64748b;
    margin: 4px 0 0 0;
}
.gt-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* Stat Cards */
.gt-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}
.gt-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 16px;
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
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.gt-stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

/* Month Filter Tabs */
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
    font-size: 12.5px;
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

/* Search Bar */
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
    font-size: 13.5px;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    background: #f8fafc;
}
.gt-search-input:focus {
    border-color: #78B722;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(120, 183, 34, 0.15);
}

/* Main Follow-up Table */
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
    border-collapse: collapse;
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
}
.gt-table tr:hover td {
    background: #f8fafc;
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

/* Status Badges */
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

<!-- Main Container with B2B Left Sidebar -->
<div style="display: grid; grid-template-columns: 200px 1fr; gap: 24px; align-items: start;">

    <!-- Left Vertical Nav matching user interface -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03); overflow: hidden; min-height: 480px;">
        <a href="<?php echo site_url('franchise/flight'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #475569; font-weight: 600; font-size: 15px; border-left: 5px solid transparent; transition: background 0.15s;">
            <i class="fa-solid fa-plane-departure" style="font-size: 18px; color: #64748b;"></i>
            <span>Flight</span>
        </a>
        <a href="<?php echo site_url('franchise/hotel'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #475569; font-weight: 600; font-size: 15px; border-left: 5px solid transparent; transition: background 0.15s;">
            <i class="fa-solid fa-hotel" style="font-size: 18px; color: #64748b;"></i>
            <span>Hotel</span>
        </a>
        <a href="<?php echo site_url('franchise/group-tours'); ?>" style="display: flex; align-items: center; gap: 14px; padding: 18px 20px; text-decoration: none; color: #ea580c; font-weight: 700; font-size: 15px; border-left: 5px solid #f97316; background: #fff7ed;">
            <i class="fa-solid fa-users-rectangle" style="font-size: 18px; color: #ea580c;"></i>
            <span>Group Tour Follow Up</span>
        </a>
    </div>

    <!-- Right Content Area: Read-Only Master Board Matching Screenshot 2 -->
    <div>
        
        <!-- Header Bar -->
        <div class="gt-header-bar">
            <div>
                <h1 class="gt-title">
                    <i class="fa-solid fa-users-rectangle" style="color: #78B722;"></i>
                    Group Tour Follow Up
                </h1>
                <p class="gt-subtitle">
                    <strong>SKY PLANET HOLIDAYS</strong> &bull; Group Tour Board Follow-Up Sheet &bull; Live Departures &bull; Passenger Counts &bull; Visa &amp; Milestone Tracking
                </p>
            </div>

            <div class="gt-actions">
                <button type="button" class="btn btn-secondary" onclick="exportTableToExcel()" title="Export current sheet to Excel" style="background: #ffffff; border: 1px solid #cbd5e1; color: #1e293b; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-excel" style="color: #16a34a;"></i> Export to Excel
                </button>
            </div>
        </div>

        <!-- KPI Summary Stat Cards (Exact match to Screenshot 2) -->
        <div class="gt-stats-grid">
            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-route"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Total Group Tours</div>
                    <div class="gt-stat-val"><?php echo number_format($stats['total_tours'] ?? count($tours)); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Block Capacity (Grp. Tkn)</div>
                    <div class="gt-stat-val"><?php echo number_format($stats['total_grp_tkn'] ?? 0); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #fff7ed; color: #ea580c;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Total Seats Needed</div>
                    <div class="gt-stat-val" style="color: #ea580c;"><?php echo number_format($stats['total_needed'] ?? 0); ?></div>
                </div>
            </div>

            <div class="gt-stat-card">
                <div class="gt-stat-icon" style="background: #fdf2f8; color: #db2777;">
                    <i class="fa-solid fa-passport"></i>
                </div>
                <div>
                    <div class="gt-stat-label">Visa Processing Pending</div>
                    <div class="gt-stat-val" style="color: #db2777;"><?php echo number_format($stats['visa_pending_count'] ?? 0); ?></div>
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
                            <th style="width: 50px;">S. NO</th>
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
                                <i class="fa-regular fa-calendar-days" style="margin-right: 8px;"></i>
                                <?php echo htmlspecialchars($currentMonth); ?>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <tr class="tour-data-row" 
                            data-month="<?php echo htmlspecialchars($t['month_group']); ?>"
                            data-search="<?php echo htmlspecialchars(strtolower($t['tour_code'] . ' ' . $t['country_name'] . ' ' . $t['handling_person'] . ' ' . $t['tour_manager'] . ' ' . $t['tour_date'] . ' ' . $t['visa_status'])); ?>">
                            
                            <!-- 1. S. NO -->
                            <td class="col-sno" style="font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($t['s_no'] ?: $rowSeq); ?></td>

                            <!-- 2. DATE -->
                            <td style="font-weight: 600;">
                                <i class="fa-regular fa-calendar" style="color: #0284c7; margin-right: 4px; font-size: 11px;"></i>
                                <?php echo htmlspecialchars($t['tour_date'] ?: '-'); ?>
                            </td>

                            <!-- 3. TOUR CODE -->
                            <td>
                                <span class="badge-code"><?php echo htmlspecialchars($t['tour_code'] ?: '-'); ?></span>
                            </td>

                            <!-- 4. COUNTRY NAME -->
                            <td>
                                <strong style="color: #0f172a;"><?php echo htmlspecialchars($t['country_name'] ?: '-'); ?></strong>
                            </td>

                            <!-- 5. TOUR AMOUNT -->
                            <td>
                                <span class="badge-amount"><?php echo htmlspecialchars($t['tour_amount'] ?: '-'); ?></span>
                            </td>

                            <!-- 6. CONFIRMED -->
                            <td>
                                <span style="font-weight: 700; color: #0284c7;"><?php echo htmlspecialchars($t['confirmed'] ?: '-'); ?></span>
                            </td>

                            <!-- 7. GRP. TKN -->
                            <td>
                                <span style="font-weight: 700;"><?php echo htmlspecialchars($t['grp_tkn'] ?: '-'); ?></span>
                            </td>

                            <!-- 8. NEED -->
                            <td>
                                <?php 
                                $needVal = trim($t['need'] ?? '');
                                $hasNeed = (!empty($needVal) && $needVal !== '-' && (int)$needVal > 0);
                                ?>
                                <span class="badge-need <?php echo $hasNeed ? 'has-need' : 'no-need'; ?>">
                                    <?php echo htmlspecialchars($needVal ?: '-'); ?>
                                </span>
                            </td>

                            <!-- 9. 45 DAYS REMINDER -->
                            <td>
                                <?php if (!empty($t['reminder_45']) && $t['reminder_45'] !== '-'): ?>
                                <span style="color: #b45309; font-weight: 600; font-size: 12px; background: #fef3c7; padding: 2px 7px; border-radius: 4px;">
                                    <i class="fa-regular fa-bell" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($t['reminder_45']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 10. 30 DAYS REMINDER -->
                            <td>
                                <?php if (!empty($t['reminder_30']) && $t['reminder_30'] !== '-'): ?>
                                <span style="color: #b91c1c; font-weight: 600; font-size: 12px; background: #fee2e2; padding: 2px 7px; border-radius: 4px;">
                                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($t['reminder_30']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 11. TICKET STATUS -->
                            <td>
                                <?php if (!empty($t['ticket_status']) && $t['ticket_status'] !== '-'): ?>
                                <span class="badge-status ticket-taken">
                                    <i class="fa-solid fa-check" style="font-size: 9px; margin-right: 2px;"></i> <?php echo htmlspecialchars($t['ticket_status']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 12. VISA STATUS -->
                            <td>
                                <?php if (!empty($t['visa_status']) && $t['visa_status'] !== '-'): ?>
                                <span class="badge-status visa-include">
                                    <i class="fa-solid fa-passport" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($t['visa_status']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 13. HANDLING PERSON -->
                            <td>
                                <?php if (!empty($t['handling_person']) && $t['handling_person'] !== '-'): ?>
                                <span class="badge-person">
                                    <i class="fa-regular fa-user" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($t['handling_person']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- 14. TRAVEL BAG -->
                            <td>
                                <span style="color: #64748b;"><?php echo htmlspecialchars($t['travel_bag'] ?: '-'); ?></span>
                            </td>

                            <!-- 15. TOUR MANAGER -->
                            <td>
                                <?php if (!empty($t['tour_manager']) && $t['tour_manager'] !== '-' && strtoupper($t['tour_manager']) !== 'NO TOUR MANAGER'): ?>
                                <span class="badge-manager">
                                    <i class="fa-solid fa-user-tie" style="font-size: 10px; margin-right: 3px;"></i> <?php echo htmlspecialchars($t['tour_manager']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: #94a3b8; font-size: 11.5px;"><?php echo htmlspecialchars($t['tour_manager'] ?: '-'); ?></span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php $rowSeq++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// 1. Real-time Search Filter Across Table
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

    // Check which month headers have at least one visible tour row
    monthHeaders.forEach(mh => {
        const monthName = mh.getAttribute('data-month-header');
        const hasVisible = Array.from(rows).some(r => r.getAttribute('data-month') === monthName && r.style.display !== 'none');
        mh.style.display = hasVisible ? '' : 'none';
    });

    const countElem = document.getElementById('visibleRowCount');
    if (countElem) countElem.textContent = visibleCount;
}

// 2. Export Table to Excel (.xlsx)
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
