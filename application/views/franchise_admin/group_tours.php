<!-- SheetJS for Client-Side Excel Import/Export (.xls, .xlsx, .csv) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<style>
/* Page Specific Styling */
.gt-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.gt-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
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
    margin-bottom: 24px;
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
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.gt-stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
    margin-top: 2px;
}

/* Filter Bar */
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
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    margin-bottom: 14px;
    scrollbar-width: thin;
}
.gt-month-pill {
    padding: 7px 16px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
    text-decoration: none;
    white-space: nowrap;
    border: 1px solid transparent;
    transition: all 0.15s;
    cursor: pointer;
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

/* Master Table */
.gt-table-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.gt-table-responsive {
    overflow-x: auto;
    max-height: calc(100vh - 320px);
    scrollbar-width: thin;
}
.gt-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
    white-space: nowrap;
}
.gt-table thead th {
    background: #09204b;
    color: #ffffff;
    padding: 12px 14px;
    font-weight: 700;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #061633;
    position: sticky;
    top: 0;
    z-index: 20;
}
.gt-table thead th.sticky-col,
.gt-table tbody td.sticky-col {
    position: sticky;
    left: 0;
    z-index: 21;
    background: #09204b;
}
.gt-table tbody td.sticky-col {
    background: #ffffff;
    z-index: 10;
}
.gt-table tbody td {
    padding: 11px 14px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}
.gt-table tbody tr:hover td {
    background-color: #f8fafc;
}
.gt-table tbody tr.month-header-row td {
    background: linear-gradient(90deg, #fce7f3 0%, #fee2e2 100%);
    color: #9d174d;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 10px 16px;
    border-top: 1.5px solid #fbcfe8;
    border-bottom: 1.5px solid #fbcfe8;
}

/* Badges */
.badge-code {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 800;
    font-family: monospace;
    font-size: 12px;
    padding: 3px 8px;
    border-radius: 6px;
    border: 1px solid #bae6fd;
}
.badge-amount {
    font-weight: 800;
    color: #0f172a;
}
.badge-need {
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 12px;
}
.badge-need.has-need {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
.badge-need.no-need {
    color: #94a3b8;
}
.badge-status {
    padding: 4px 9px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    display: inline-block;
}
.badge-status.ticket-taken {
    background: #dcfce7;
    color: #15803d;
}
.badge-status.visa-include {
    background: #ede9fe;
    color: #6d28d9;
}
.badge-person {
    background: #f1f5f9;
    color: #334155;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
}
.badge-manager {
    background: #fef3c7;
    color: #92400e;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
}

/* Modal Overlay */
.gt-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.65);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
    padding: 20px;
}
.gt-modal-overlay.active {
    display: flex;
}
.gt-modal-box {
    background: #ffffff;
    border-radius: 14px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    animation: zoomInModal 0.2s ease;
}
.gt-modal-box.wide-modal {
    max-width: 900px;
}
@keyframes zoomInModal {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}
.gt-modal-head {
    background: #09204b;
    color: #ffffff;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.gt-modal-head h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}
.gt-modal-close {
    background: none;
    border: none;
    color: #cbd5e1;
    font-size: 24px;
    cursor: pointer;
    line-height: 1;
}
.gt-modal-close:hover { color: #ffffff; }
.gt-modal-body {
    padding: 24px;
}

/* Drag Drop Zone */
.gt-dropzone {
    border: 2px dashed #94a3b8;
    border-radius: 12px;
    padding: 36px 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 20px;
}
.gt-dropzone:hover, .gt-dropzone.dragover {
    border-color: #78B722;
    background: #f0fdf4;
}
.gt-dropzone i {
    font-size: 42px;
    color: #78B722;
    margin-bottom: 12px;
}

/* Form Grid */
.gt-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.gt-form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.gt-form-group label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
}
.gt-form-group input, .gt-form-group select, .gt-form-group textarea {
    padding: 9px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13.5px;
    color: #0f172a;
    outline: none;
}
.gt-form-group input:focus, .gt-form-group select:focus {
    border-color: #09204b;
}
</style>

<!-- Top Header & Action Bar -->
<div class="gt-header-bar">
    <div>
        <h1 class="gt-title">
            <i class="fa-solid fa-users-rectangle" style="color: #78B722;"></i>
            GROUP TOUR FOLLOW UP
        </h1>
        <p class="gt-subtitle">
            <strong>SKY PLANET HOLIDAYS</strong> &bull; Group Tour Board Follow-Up Tracking Sheet &bull; Import Excel (.xls / .xlsx) & Manage Departure Milestones
        </p>
    </div>

    <div class="gt-actions">
        <button type="button" class="btn btn-primary" onclick="openImportModal()">
            <i class="fa-solid fa-file-excel"></i> Import Excel File (.xls / .xlsx)
        </button>
        <button type="button" class="btn btn-secondary" onclick="openTourModal()">
            <i class="fa-solid fa-plus"></i> Add Tour Row
        </button>
        <a href="<?php echo site_url('franchise-admin/group-tour-export-template'); ?>" class="btn btn-secondary" title="Download sample template matching headers">
            <i class="fa-solid fa-download"></i> Sample Template
        </a>
        <button type="button" class="btn btn-secondary" onclick="exportTableToExcel()" title="Export current sheet to Excel">
            <i class="fa-solid fa-file-export"></i> Export Excel
        </button>
        <a href="<?php echo site_url('franchise-admin/group-tour-seed-sample'); ?>" class="btn btn-secondary" onclick="return confirm('Restore default sample tours from workbook1.pdf? This will replace your current records.');" title="Restore workbook1.pdf sample rows">
            <i class="fa-solid fa-rotate-left"></i> Restore Sample Data
        </a>
        <a href="<?php echo site_url('franchise-admin/group-tour-clear-all'); ?>" class="btn btn-secondary" style="color: #ef4444; border-color: #fecaca;" onclick="return confirm('Are you sure you want to permanently clear all group tour records? You can upload a new Excel file anytime.');" title="Clear all tours">
            <i class="fa-solid fa-trash-can"></i> Clear All
        </a>
    </div>
</div>

<!-- Key Stat Cards -->
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

<!-- Month Selector & Search Filter Bar -->
<div class="gt-filter-panel">
    <!-- Month Tabs -->
    <div class="gt-month-pills">
        <a href="<?php echo site_url('franchise-admin/group-tour-follow-up?month=all'); ?>" class="gt-month-pill <?php echo ($selected_month == 'all' || empty($selected_month)) ? 'active' : ''; ?>">
            All Months (<?php echo count($tours); ?>)
        </a>
        <?php foreach ($months as $m): ?>
        <a href="<?php echo site_url('franchise-admin/group-tour-follow-up?month=' . urlencode($m['month_group'])); ?>" class="gt-month-pill <?php echo ($selected_month == $m['month_group']) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($m['month_group']); ?> (<?php echo $m['tour_count']; ?>)
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Real-time Search Box -->
    <div style="display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <div style="position: relative; flex: 1; max-width: 480px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 12px; color: #94a3b8;"></i>
            <input type="text" id="gtSearchInput" placeholder="Instant search by Tour Code, Country, Manager, or Person..." oninput="filterTableRealtime(this.value)" style="width: 100%; padding: 10px 14px 10px 40px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; outline: none;">
        </div>

        <div style="font-size: 13px; color: #64748b;">
            Showing <strong id="visibleRowCount"><?php echo count($tours); ?></strong> of <strong><?php echo count($tours); ?></strong> tour departures
        </div>
    </div>
</div>

<!-- Master Follow Up Table (Exact 15 Headers Matching workbook1.pdf) -->
<div class="gt-table-container">
    <div class="gt-table-responsive">
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
                    <th style="text-align: center; width: 90px;">ACTIONS</th>
                </tr>
            </thead>
            <tbody id="gtTableBody">
                <?php if (empty($tours)): ?>
                <tr>
                    <td colspan="16" style="text-align: center; padding: 48px; color: #94a3b8;">
                        <i class="fa-solid fa-file-excel" style="font-size: 38px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                        <h4 style="color: #0f172a; margin: 0 0 6px 0;">No Group Tour Follow-Up Records Found</h4>
                        <p style="margin: 0 0 16px 0; font-size: 13px;">Click below to import your Excel sheet (.xls / .xlsx) or add a tour row manually.</p>
                        <button type="button" class="btn btn-primary" onclick="openImportModal()">
                            <i class="fa-solid fa-upload"></i> Import Excel File
                        </button>
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
                    <td colspan="16">
                        <i class="fa-regular fa-calendar-days" style="margin-right: 8px;"></i>
                        <?php echo htmlspecialchars($currentMonth); ?>
                    </td>
                </tr>
                <?php endif; ?>

                <tr class="tour-data-row" 
                    id="tour-row-<?php echo $t['id']; ?>"
                    data-id="<?php echo $t['id']; ?>"
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

                    <!-- 16. ACTIONS -->
                    <td style="text-align: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editTourRow(<?php echo json_encode($t); ?>)' title="Edit Row" style="padding: 4px 8px;">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="deleteTourRow(event, <?php echo (int)$t['id']; ?>, '<?php echo htmlspecialchars(addslashes($t['tour_code'])); ?>')" title="Delete Row" style="padding: 4px 8px; color: #ef4444;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>

                </tr>
                <?php $rowSeq++; endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================
     MODAL 1: EXCEL IMPORT (.xls / .xlsx / .csv)
     ======================================================== -->
<div class="gt-modal-overlay" id="importExcelModal">
    <div class="gt-modal-box wide-modal">
        <div class="gt-modal-head">
            <h3><i class="fa-solid fa-file-excel" style="color: #86efac;"></i> Import Excel Group Tour Sheet</h3>
            <button type="button" class="gt-modal-close" onclick="closeImportModal()">&times;</button>
        </div>

        <div class="gt-modal-body">
            
            <!-- Drag and Drop Box -->
            <div class="gt-dropzone" id="excelDropzone" onclick="document.getElementById('excelFileInput').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <h4 style="font-size: 16px; color: #0f172a; margin: 0 0 6px 0;">Click or Drag & Drop Excel File Here</h4>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 14px 0;">Supports <strong>.xlsx</strong>, <strong>.xls</strong>, and <strong>.csv</strong> spreadsheets matching your 15 headers.</p>
                <button type="button" class="btn btn-secondary btn-sm">Browse Computer</button>
                <input type="file" id="excelFileInput" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleExcelFileSelect(this.files[0])">
            </div>

            <!-- Import Options -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; display: block; margin-bottom: 8px;">
                    Import Method:
                </label>
                <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; cursor: pointer;">
                        <input type="radio" name="importMode" value="replace" checked style="accent-color: #78B722;">
                        <span><strong>Sync &amp; Overwrite (Recommended)</strong> — Replaces entire board so all changes and newly added rows in your Excel file are properly updated</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; cursor: pointer;">
                        <input type="radio" name="importMode" value="append" style="accent-color: #78B722;">
                        <span><strong>Append</strong> — Add to the bottom of existing rows</span>
                    </label>
                </div>
            </div>

            <!-- Parsed Preview Box -->
            <div id="importPreviewContainer" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">
                        <i class="fa-solid fa-table" style="color: #0284c7;"></i> Parsed Sheet Preview (<span id="parsedRowCount">0</span> rows detected)
                    </h4>
                    <span id="parsedFileName" style="font-size: 12px; color: #64748b; font-weight: 600;"></span>
                </div>

                <div style="max-height: 220px; overflow-y: auto; overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px;">
                    <table class="gt-table" style="font-size: 12px;">
                        <thead id="previewThead"></thead>
                        <tbody id="previewTbody"></tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeImportModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmImport" onclick="submitParsedExcelToDatabase()">
                        <i class="fa-solid fa-circle-check"></i> Confirm &amp; Save Into Database
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================
     MODAL 2: ADD / EDIT TOUR ROW
     ======================================================== -->
<div class="gt-modal-overlay" id="tourRowModal">
    <div class="gt-modal-box wide-modal">
        <div class="gt-modal-head">
            <h3 id="tourModalHeading"><i class="fa-solid fa-route"></i> Add Group Tour Row</h3>
            <button type="button" class="gt-modal-close" onclick="closeTourModal()">&times;</button>
        </div>

        <div class="gt-modal-body">
            <form id="tourRowForm" onsubmit="saveTourRow(event)">
                <input type="hidden" name="id" id="formTourId" value="">

                <div class="gt-form-grid">
                    
                    <div class="gt-form-group">
                        <label>Month Section *</label>
                        <input type="text" name="month_group" id="formMonthGroup" placeholder="e.g. SEPTEMBER:2026" required value="SEPTEMBER:2026">
                    </div>

                    <div class="gt-form-group">
                        <label>S. NO</label>
                        <input type="text" name="s_no" id="formSNo" placeholder="1, 2, 3...">
                    </div>

                    <div class="gt-form-group">
                        <label>Departure Date</label>
                        <input type="text" name="tour_date" id="formTourDate" placeholder="e.g. 16.09.2026">
                    </div>

                    <div class="gt-form-group">
                        <label>Tour Code *</label>
                        <input type="text" name="tour_code" id="formTourCode" placeholder="e.g. NBO160926" required>
                    </div>

                    <div class="gt-form-group" style="grid-column: span 2;">
                        <label>Country / Package Name *</label>
                        <input type="text" name="country_name" id="formCountryName" placeholder="e.g. KENYA (5N/6D)" required>
                    </div>

                    <div class="gt-form-group">
                        <label>Tour Amount (INR)</label>
                        <input type="text" name="tour_amount" id="formTourAmount" placeholder="e.g. 259,000">
                    </div>

                    <div class="gt-form-group">
                        <label>Confirmed (PAX Formula)</label>
                        <input type="text" name="confirmed" id="formConfirmed" placeholder="e.g. 21+1+1+2+2">
                    </div>

                    <div class="gt-form-group">
                        <label>Grp. Tkn (Total Block)</label>
                        <input type="text" name="grp_tkn" id="formGrpTkn" placeholder="e.g. 22">
                    </div>

                    <div class="gt-form-group">
                        <label>Need (Balance Seats)</label>
                        <input type="text" name="need" id="formNeed" placeholder="e.g. 7 or -">
                    </div>

                    <div class="gt-form-group">
                        <label>45 Days Reminder</label>
                        <input type="text" name="reminder_45" id="formReminder45" placeholder="e.g. 02.08.2026">
                    </div>

                    <div class="gt-form-group">
                        <label>30 Days Reminder</label>
                        <input type="text" name="reminder_30" id="formReminder30" placeholder="e.g. 17.08.2026">
                    </div>

                    <div class="gt-form-group">
                        <label>Ticket Status</label>
                        <input type="text" name="ticket_status" id="formTicketStatus" placeholder="e.g. GROUP TAKEN">
                    </div>

                    <div class="gt-form-group">
                        <label>Visa Status</label>
                        <input type="text" name="visa_status" id="formVisaStatus" placeholder="e.g. WE APPLY (INCLUDE)">
                    </div>

                    <div class="gt-form-group">
                        <label>Handling Person</label>
                        <input type="text" name="handling_person" id="formHandlingPerson" placeholder="e.g. KEERTHANA DEVI">
                    </div>

                    <div class="gt-form-group">
                        <label>Travel Bag</label>
                        <input type="text" name="travel_bag" id="formTravelBag" placeholder="e.g. -">
                    </div>

                    <div class="gt-form-group" style="grid-column: span 2;">
                        <label>Tour Manager</label>
                        <input type="text" name="tour_manager" id="formTourManager" placeholder="e.g. YOGESH">
                    </div>

                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeTourModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveTourRow">
                        <i class="fa-solid fa-save"></i> Save Tour Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     JAVASCRIPT: SheetJS Import, Client Filtering, Export
     ======================================================== -->
<script>
let parsedImportRows = [];

// 1. Modals Open/Close
function openImportModal() {
    document.getElementById('importExcelModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeImportModal() {
    document.getElementById('importExcelModal').classList.remove('active');
    document.body.style.overflow = '';
}

function openTourModal() {
    document.getElementById('tourModalHeading').innerHTML = '<i class="fa-solid fa-route"></i> Add Group Tour Row';
    document.getElementById('tourRowForm').reset();
    document.getElementById('formTourId').value = '';
    document.getElementById('tourRowModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeTourModal() {
    document.getElementById('tourRowModal').classList.remove('active');
    document.body.style.overflow = '';
}

function editTourRow(tour) {
    document.getElementById('tourModalHeading').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Tour: ' + (tour.tour_code || 'Row');
    document.getElementById('formTourId').value = tour.id || '';
    document.getElementById('formMonthGroup').value = tour.month_group || '';
    document.getElementById('formSNo').value = tour.s_no || '';
    document.getElementById('formTourDate').value = tour.tour_date || '';
    document.getElementById('formTourCode').value = tour.tour_code || '';
    document.getElementById('formCountryName').value = tour.country_name || '';
    document.getElementById('formTourAmount').value = tour.tour_amount || '';
    document.getElementById('formConfirmed').value = tour.confirmed || '';
    document.getElementById('formGrpTkn').value = tour.grp_tkn || '';
    document.getElementById('formNeed').value = tour.need || '';
    document.getElementById('formReminder45').value = tour.reminder_45 || '';
    document.getElementById('formReminder30').value = tour.reminder_30 || '';
    document.getElementById('formTicketStatus').value = tour.ticket_status || '';
    document.getElementById('formVisaStatus').value = tour.visa_status || '';
    document.getElementById('formHandlingPerson').value = tour.handling_person || '';
    document.getElementById('formTravelBag').value = tour.travel_bag || '';
    document.getElementById('formTourManager').value = tour.tour_manager || '';

    document.getElementById('tourRowModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// 2. Drag & Drop Handling for Excel file
const dropzone = document.getElementById('excelDropzone');
if (dropzone) {
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt.files && dt.files.length > 0) {
            handleExcelFileSelect(dt.files[0]);
        }
    });
}

// 3. Parse Excel via SheetJS (.xls / .xlsx / .csv)
function handleExcelFileSelect(file) {
    if (!file) return;

    document.getElementById('parsedFileName').textContent = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array', cellDates: true });
            
            // Read first worksheet
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];

            // Convert to 2D array of rows
            const jsonRows = XLSX.utils.sheet_to_json(worksheet, { header: 1, defval: '' });

            if (jsonRows.length === 0) {
                alert('No rows found in uploaded spreadsheet.');
                return;
            }

            processExcelSheetRows(jsonRows);
        } catch (err) {
            console.error('Error parsing spreadsheet:', err);
            alert('Failed to read Excel file. Please ensure it is a valid .xlsx or .xls file.');
        }
    };
    reader.readAsArrayBuffer(file);
}

function processExcelSheetRows(rows) {
    parsedImportRows = [];
    let headerRowIdx = -1;
    let colMap = {};
    let activeMonth = 'SEPTEMBER:2026';

    // 1. Find Header Row
    for (let i = 0; i < Math.min(rows.length, 10); i++) {
        const rowStr = rows[i].map(c => String(c).trim().toUpperCase()).join(' ');
        if (rowStr.includes('TOUR') || rowStr.includes('DATE') || rowStr.includes('COUNTRY')) {
            headerRowIdx = i;
            // Build column map
            rows[i].forEach((colName, cIdx) => {
                const norm = String(colName).trim().toUpperCase();
                if (norm.includes('S.') || norm === 'S NO' || norm === 'SL NO' || norm === 'SNO') colMap['s_no'] = cIdx;
                else if (norm === 'DATE' || norm.includes('TOUR DATE') || norm.includes('DEPARTURE')) colMap['tour_date'] = cIdx;
                else if (norm.includes('TOUR CODE') || norm === 'CODE') colMap['tour_code'] = cIdx;
                else if (norm.includes('COUNTRY') || norm.includes('PACKAGE') || norm.includes('DESTINATION')) colMap['country_name'] = cIdx;
                else if (norm.includes('AMOUNT') || norm.includes('PRICE') || norm.includes('FARE')) colMap['tour_amount'] = cIdx;
                else if (norm.includes('CONFIRMED') || norm === 'PAX') colMap['confirmed'] = cIdx;
                else if (norm.includes('GRP') || norm.includes('TKN')) colMap['grp_tkn'] = cIdx;
                else if (norm === 'NEED' || norm.includes('SEATS NEEDED')) colMap['need'] = cIdx;
                else if (norm.includes('45')) colMap['reminder_45'] = cIdx;
                else if (norm.includes('30')) colMap['reminder_30'] = cIdx;
                else if (norm.includes('TICKET')) colMap['ticket_status'] = cIdx;
                else if (norm.includes('VISA')) colMap['visa_status'] = cIdx;
                else if (norm.includes('HANDLING') || norm.includes('PERSON')) colMap['handling_person'] = cIdx;
                else if (norm.includes('BAG')) colMap['travel_bag'] = cIdx;
                else if (norm.includes('MANAGER')) colMap['tour_manager'] = cIdx;
                else if (norm.includes('MONTH')) colMap['month_group'] = cIdx;
            });
            break;
        }
    }

    // Default column fallback if exact header row wasn't found
    if (headerRowIdx === -1) {
        headerRowIdx = 0;
        colMap = {
            s_no: 0, tour_date: 1, tour_code: 2, country_name: 3, tour_amount: 4,
            confirmed: 5, grp_tkn: 6, need: 7, reminder_45: 8, reminder_30: 9,
            ticket_status: 10, visa_status: 11, handling_person: 12, travel_bag: 13, tour_manager: 14
        };
    }

    // 2. Loop Data Rows
    for (let i = headerRowIdx + 1; i < rows.length; i++) {
        const r = rows[i];
        if (!r || r.length === 0) continue;

        // Check if row is a month section header (e.g. "SEPTEMBER:2026")
        const firstCell = String(r[0] || '').trim().toUpperCase();
        const fullRowStr = r.map(c => String(c).trim()).join(' ').toUpperCase();
        const monthMatch = fullRowStr.match(/(JANUARY|FEBRUARY|MARCH|APRIL|MAY|JUNE|JULY|AUGUST|SEPTEMBER|OCTOBER|NOVEMBER|DECEMBER)\s*:\s*\d{4}/i);
        
        if (monthMatch) {
            activeMonth = monthMatch[0].toUpperCase();
            continue;
        }

        const tourCode = String(r[colMap.tour_code !== undefined ? colMap.tour_code : 2] || '').trim();
        const country = String(r[colMap.country_name !== undefined ? colMap.country_name : 3] || '').trim();
        const tourDate = String(r[colMap.tour_date !== undefined ? colMap.tour_date : 1] || '').trim();

        if (!tourCode && !country && !tourDate) continue;

        parsedImportRows.push({
            s_no: String(parsedImportRows.length + 1),
            tour_date: tourDate,
            tour_code: tourCode,
            country_name: country,
            tour_amount: String(r[colMap.tour_amount !== undefined ? colMap.tour_amount : 4] || '').trim(),
            confirmed: String(r[colMap.confirmed !== undefined ? colMap.confirmed : 5] || '').trim(),
            grp_tkn: String(r[colMap.grp_tkn !== undefined ? colMap.grp_tkn : 6] || '').trim(),
            need: String(r[colMap.need !== undefined ? colMap.need : 7] || '').trim(),
            reminder_45: String(r[colMap.reminder_45 !== undefined ? colMap.reminder_45 : 8] || '').trim(),
            reminder_30: String(r[colMap.reminder_30 !== undefined ? colMap.reminder_30 : 9] || '').trim(),
            ticket_status: String(r[colMap.ticket_status !== undefined ? colMap.ticket_status : 10] || '').trim(),
            visa_status: String(r[colMap.visa_status !== undefined ? colMap.visa_status : 11] || '').trim(),
            handling_person: String(r[colMap.handling_person !== undefined ? colMap.handling_person : 12] || '').trim(),
            travel_bag: String(r[colMap.travel_bag !== undefined ? colMap.travel_bag : 13] || '').trim(),
            tour_manager: String(r[colMap.tour_manager !== undefined ? colMap.tour_manager : 14] || '').trim(),
            month_group: (colMap.month_group !== undefined && r[colMap.month_group]) ? String(r[colMap.month_group]).trim() : activeMonth
        });
    }

    if (parsedImportRows.length === 0) {
        alert('Could not find valid tour rows in this file.');
        return;
    }

    // Render preview table
    renderImportPreview(parsedImportRows);
}

function renderImportPreview(rows) {
    document.getElementById('parsedRowCount').textContent = rows.length;
    document.getElementById('importPreviewContainer').style.display = 'block';

    const thead = document.getElementById('previewThead');
    const tbody = document.getElementById('previewTbody');

    thead.innerHTML = `
        <tr>
            <th>#</th>
            <th>DATE</th>
            <th>TOUR CODE</th>
            <th>COUNTRY</th>
            <th>AMOUNT</th>
            <th>CONFIRMED</th>
            <th>GRP. TKN</th>
            <th>NEED</th>
            <th>MONTH</th>
        </tr>
    `;

    tbody.innerHTML = '';
    const sampleLimit = Math.min(rows.length, 6);
    for (let i = 0; i < sampleLimit; i++) {
        const r = rows[i];
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${r.s_no || (i+1)}</td>
            <td>${r.tour_date || '-'}</td>
            <td><strong>${r.tour_code || '-'}</strong></td>
            <td>${r.country_name || '-'}</td>
            <td>${r.tour_amount || '-'}</td>
            <td>${r.confirmed || '-'}</td>
            <td>${r.grp_tkn || '-'}</td>
            <td>${r.need || '-'}</td>
            <td><span style="font-size: 11px; background: #fce7f3; color: #9d174d; padding: 2px 6px; border-radius: 4px;">${r.month_group}</span></td>
        `;
        tbody.appendChild(tr);
    }
}

// 4. Save Parsed Excel Rows to Database
function submitParsedExcelToDatabase() {
    if (parsedImportRows.length === 0) {
        alert('No rows to import.');
        return;
    }

    const btn = document.getElementById('btnConfirmImport');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Importing Rows...';

    const importMode = document.querySelector('input[name="importMode"]:checked').value;
    const replace = (importMode === 'replace');

    fetch('<?php echo site_url("franchise-admin/group-tour-import"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            rows: parsedImportRows,
            replace: replace
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            alert(data.message || 'Import successful!');
            window.location.reload();
        } else {
            alert('Import failed: ' + (data.error || 'Server error'));
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Confirm &amp; Save Into Database';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Network error while importing rows.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Confirm &amp; Save Into Database';
    });
}

// 5. Add / Edit Single Tour Row via AJAX
function saveTourRow(e) {
    e.preventDefault();
    const form = document.getElementById('tourRowForm');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSaveTourRow');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

    fetch('<?php echo site_url("franchise-admin/group-tour-save-row"); ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unable to save tour row'));
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Tour Record';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Network error while saving tour record.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save"></i> Save Tour Record';
    });
}

// 5.1 Delete Tour Row with immediate AJAX DOM removal and re-sequencing
function deleteTourRow(e, id, tourCode) {
    if (e) e.preventDefault();
    if (!confirm('Are you sure you want to permanently delete tour "' + (tourCode || id) + '"?')) {
        return;
    }

    const tr = document.getElementById('tour-row-' + id) || (e ? e.target.closest('tr') : null);
    if (tr) tr.style.opacity = '0.3';

    fetch('<?php echo site_url("franchise-admin/group-tour-delete-row/"); ?>' + id + '?ajax=1', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            if (tr) {
                tr.remove();
                resequenceTableDOM();
            } else {
                window.location.reload();
            }
        } else {
            alert('Failed to delete row: ' + (data.message || 'Server error'));
            if (tr) tr.style.opacity = '1';
        }
    })
    .catch(err => {
        // Fallback to normal HTTP redirect
        window.location.href = '<?php echo site_url("franchise-admin/group-tour-delete-row/"); ?>' + id;
    });
}

function resequenceTableDOM() {
    const snoCells = document.querySelectorAll('.tour-data-row .col-sno');
    snoCells.forEach((cell, idx) => {
        cell.textContent = (idx + 1);
    });

    const rows = document.querySelectorAll('.tour-data-row');
    const countElem = document.getElementById('visibleRowCount');
    if (countElem) countElem.textContent = rows.length;

    // Check if table is empty
    if (rows.length === 0) {
        window.location.reload();
    }
}

// 6. Real-time Search Filter Across Table
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

// 7. Export Table to Real Excel File (.xlsx)
function exportTableToExcel() {
    const table = document.getElementById('groupTourMasterTable');
    if (!table) return;

    // Build data array excluding the action column
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
