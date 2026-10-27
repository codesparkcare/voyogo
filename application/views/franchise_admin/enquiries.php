<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Top Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-clipboard-question" style="color: #78B722;"></i>
            Manage FRANCHISE ENQUIRY
        </h2>
        <p style="font-size: 13.5px; color: #64748b; margin-top: 4px;">
            Track, review and convert franchise applications and candidate leads submitted from <code>/voyogo/franchise</code>.
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?php echo site_url('franchise-admin/enquiry-export' . (!empty($selected_status) ? '?status=' . urlencode($selected_status) : '') . (!empty($search) ? '&q=' . urlencode($search) : '')); ?>" class="btn btn-secondary" title="Export current list to CSV">
            <i class="fa-solid fa-file-csv" style="color: #10b981;"></i> Export CSV
        </a>
        <a href="<?php echo site_url('franchise'); ?>" target="_blank" class="btn btn-primary" title="View Public Franchise Opportunity Page">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Public Form
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px;">
    
    <!-- Total Enquiries -->
    <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 14px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #0284c7;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo (int)($stats['total'] ?? 0); ?></div>
                <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">Total Enquiries</div>
            </div>
        </div>
    </a>

    <!-- New Enquiries -->
    <a href="<?php echo site_url('franchise-admin/enquiries?status=New'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 14px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #ef4444; background: <?php echo ($selected_status === 'New') ? '#fef2f2' : '#ffffff'; ?>;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px; position: relative;">
                <i class="fa-solid fa-bell"></i>
                <?php if (!empty($stats['new']) && $stats['new'] > 0): ?>
                    <span style="position: absolute; top: 4px; right: 4px; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; border: 2px solid #fff;"></span>
                <?php endif; ?>
            </div>
            <div>
                <div style="font-size: 22px; font-weight: 800; color: #dc2626;"><?php echo (int)($stats['new'] ?? 0); ?></div>
                <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">New Applications</div>
            </div>
        </div>
    </a>

    <!-- Contacted -->
    <a href="<?php echo site_url('franchise-admin/enquiries?status=Contacted'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 14px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #06b6d4;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #cffafe; color: #0891b2; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-phone-volume"></i>
            </div>
            <div>
                <div style="font-size: 22px; font-weight: 800; color: #0891b2;"><?php echo (int)($stats['contacted'] ?? 0); ?></div>
                <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">Contacted</div>
            </div>
        </div>
    </a>

    <!-- In Discussion -->
    <a href="<?php echo site_url('franchise-admin/enquiries?status=In+Discussion'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 14px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #8b5cf6;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-comments"></i>
            </div>
            <div>
                <div style="font-size: 22px; font-weight: 800; color: #7c3aed;"><?php echo (int)($stats['in_discussion'] ?? 0); ?></div>
                <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">In Discussion</div>
            </div>
        </div>
    </a>

    <!-- Converted -->
    <a href="<?php echo site_url('franchise-admin/enquiries?status=Converted'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 14px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #10b981;">
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <div style="font-size: 22px; font-weight: 800; color: #059669;"><?php echo (int)($stats['converted'] ?? 0); ?></div>
                <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">Converted</div>
            </div>
        </div>
    </a>

</div>

<!-- Search & Status Filter Bar -->
<div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form method="get" action="<?php echo site_url('franchise-admin/enquiries'); ?>" style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
        
        <!-- Search Input -->
        <div style="flex: 1; min-width: 260px; position: relative;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search ?? ''); ?>" placeholder="Search by Candidate Name, Phone, Email, City, Locality, Profession, Associations..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px;">
        </div>

        <!-- Status Filter Select -->
        <div style="min-width: 160px;">
            <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; background: #fff; cursor: pointer;" onchange="this.form.submit()">
                <option value="all" <?php echo empty($selected_status) || $selected_status === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                <option value="New" <?php echo ($selected_status === 'New') ? 'selected' : ''; ?>>⚡ New Applications</option>
                <option value="Contacted" <?php echo ($selected_status === 'Contacted') ? 'selected' : ''; ?>>📞 Contacted</option>
                <option value="In Discussion" <?php echo ($selected_status === 'In Discussion') ? 'selected' : ''; ?>>💬 In Discussion</option>
                <option value="Converted" <?php echo ($selected_status === 'Converted') ? 'selected' : ''; ?>>✅ Converted (Store Created)</option>
                <option value="Rejected" <?php echo ($selected_status === 'Rejected') ? 'selected' : ''; ?>>❌ Rejected / Closed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>

        <?php if (!empty($search) || (!empty($selected_status) && $selected_status !== 'all')): ?>
            <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" class="btn btn-secondary" style="padding: 10px 16px;">
                <i class="fa-solid fa-xmark"></i> Clear Filters
            </a>
        <?php endif; ?>

    </form>
</div>

<!-- Enquiries Table Card -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
        <span style="font-size: 14px; font-weight: 700; color: #334155;">
            Showing <?php echo count($enquiries); ?> Franchise Enquiry Application(s)
        </span>
        <span style="font-size: 12px; color: #64748b;">
            Ordered by newest first
        </span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Ref ID</th>
                    <th>Candidate Details</th>
                    <th>Location Preference</th>
                    <th>Business Profile</th>
                    <th>Timeline</th>
                    <th>Associations</th>
                    <th>Status</th>
                    <th style="text-align: right; min-width: 130px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enquiries)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 48px 20px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 26px; color: #94a3b8;">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <strong style="display: block; font-size: 16px; color: #475569; margin-bottom: 6px;">No Franchise Enquiries Found</strong>
                        <p style="font-size: 13px; max-width: 420px; margin: 0 auto 16px;">
                            <?php if (!empty($search) || !empty($selected_status)): ?>
                                No submissions match your search filter criteria. Try resetting your filters.
                            <?php else: ?>
                                Candidates who submit the multi-step form at <code>/voyogo/franchise</code> will appear here in real-time.
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($search) || !empty($selected_status)): ?>
                            <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" class="btn btn-secondary btn-sm">Reset Filters</a>
                        <?php else: ?>
                            <a href="<?php echo site_url('franchise'); ?>" target="_blank" class="btn btn-primary btn-sm">Open Public Enquiry Form</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($enquiries as $enq): ?>
                <?php 
                    $clean_phone = preg_replace('/[^0-9]/', '', $enq['mobile_number']);
                    if (strlen($clean_phone) == 10) {
                        $wa_phone = '91' . $clean_phone;
                    } else {
                        $wa_phone = $clean_phone;
                    }
                ?>
                <tr id="enquiryRow_<?php echo $enq['id']; ?>">
                    
                    <!-- Ref ID & Date -->
                    <td>
                        <span style="display: inline-block; font-weight: 700; color: #0284c7; font-size: 13px; font-family: monospace;">
                            #FE-<?php echo str_pad($enq['id'], 4, '0', STR_PAD_LEFT); ?>
                        </span>
                        <br>
                        <small style="color: #64748b; font-size: 11px;">
                            <?php echo !empty($enq['created_at']) ? date('d M Y', strtotime($enq['created_at'])) : '-'; ?>
                        </small>
                        <br>
                        <small style="color: #94a3b8; font-size: 10px;">
                            <?php echo !empty($enq['created_at']) ? date('h:i A', strtotime($enq['created_at'])) : ''; ?>
                        </small>
                    </td>

                    <!-- Candidate Name, Phone, Email -->
                    <td>
                        <div style="font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px;">
                            <?php echo htmlspecialchars($enq['client_name']); ?>
                            <?php if ($enq['status'] === 'New'): ?>
                                <span style="background: #fee2e2; color: #dc2626; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 4px; margin-left: 4px; text-transform: uppercase;">NEW</span>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #334155; margin-bottom: 2px;">
                            <i class="fa-solid fa-phone" style="font-size: 11px; color: #64748b; width: 12px;"></i>
                            <a href="tel:<?php echo htmlspecialchars($enq['mobile_number']); ?>" style="color: inherit; text-decoration: none;" title="Call candidate">
                                <?php echo htmlspecialchars($enq['mobile_number']); ?>
                            </a>
                            <?php if (!empty($clean_phone)): ?>
                                <a href="https://wa.me/<?php echo $wa_phone; ?>?text=<?php echo urlencode('Hello ' . $enq['client_name'] . ', thank you for your enquiry regarding Voyogo Franchise Opportunity.'); ?>" target="_blank" style="color: #25D366; font-size: 14px; text-decoration: none;" title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($enq['email'])): ?>
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748b;">
                            <i class="fa-solid fa-envelope" style="font-size: 11px; color: #64748b; width: 12px;"></i>
                            <a href="mailto:<?php echo htmlspecialchars($enq['email']); ?>" style="color: inherit; text-decoration: none;" title="Email candidate">
                                <?php echo htmlspecialchars($enq['email']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </td>

                    <!-- Location Preference -->
                    <td>
                        <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                            <i class="fa-solid fa-city" style="color: #64748b; font-size: 11px; margin-right: 4px;"></i>
                            <?php echo htmlspecialchars($enq['city'] ?: 'Not specified'); ?>
                        </div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            <?php echo htmlspecialchars($enq['state'] ?: '-'); ?>
                        </div>
                        <?php if (!empty($enq['preferred_location'])): ?>
                            <small style="font-size: 11.5px; color: #0284c7; display: block; margin-top: 2px;" title="Preferred Locality">
                                <i class="fa-solid fa-location-dot" style="font-size: 10px;"></i> <?php echo htmlspecialchars($enq['preferred_location']); ?>
                            </small>
                        <?php endif; ?>
                    </td>

                    <!-- Business Profile -->
                    <td>
                        <div style="font-size: 12.5px;">
                            <span style="color: #64748b;">Owns Business:</span>
                            <strong style="color: <?php echo ($enq['own_business'] === 'Yes') ? '#15803d' : '#64748b'; ?>;">
                                <?php echo htmlspecialchars($enq['own_business'] ?: 'No'); ?>
                            </strong>
                        </div>
                        <?php if (!empty($enq['current_profession'])): ?>
                        <div style="font-size: 12px; color: #334155; margin-top: 2px;">
                            <i class="fa-solid fa-briefcase" style="font-size: 10px; color: #64748b;"></i>
                            <?php echo htmlspecialchars($enq['current_profession']); ?>
                        </div>
                        <?php endif; ?>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                            Prev Franchise: <strong><?php echo htmlspecialchars($enq['previous_franchise'] ?: 'No'); ?></strong>
                        </div>
                    </td>

                    <!-- Timeline -->
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11.5px; text-transform: none; font-weight: 600;">
                            <i class="fa-regular fa-clock" style="margin-right: 4px; color: #64748b;"></i>
                            <?php echo htmlspecialchars($enq['start_timeline'] ?: 'Immediately'); ?>
                        </span>
                        <?php if (!empty($enq['hear_about_us'])): ?>
                            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                Via: <strong><?php echo htmlspecialchars($enq['hear_about_us']); ?></strong>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- Associations -->
                    <td>
                        <?php if (!empty($enq['associations']) && $enq['associations'] !== 'No'): ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 170px;">
                                <?php foreach (explode(',', $enq['associations']) as $assoc): ?>
                                    <span style="background: #e0f2fe; color: #0369a1; font-size: 10.5px; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                        <?php echo htmlspecialchars(trim($assoc)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span style="color: #94a3b8; font-size: 12px;">None</span>
                        <?php endif; ?>
                    </td>

                    <!-- Status -->
                    <td>
                        <?php
                            $badge_class = 'badge-info';
                            $status_color = '#0284c7';
                            $status_bg = '#e0f2fe';
                            if ($enq['status'] === 'New') {
                                $status_color = '#dc2626';
                                $status_bg = '#fee2e2';
                            } elseif ($enq['status'] === 'Contacted') {
                                $status_color = '#0891b2';
                                $status_bg = '#cffafe';
                            } elseif ($enq['status'] === 'In Discussion') {
                                $status_color = '#7c3aed';
                                $status_bg = '#ede9fe';
                            } elseif ($enq['status'] === 'Converted') {
                                $status_color = '#15803d';
                                $status_bg = '#dcfce7';
                            } elseif ($enq['status'] === 'Rejected') {
                                $status_color = '#64748b';
                                $status_bg = '#f1f5f9';
                            }
                        ?>
                        <span class="badge" style="background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>; font-size: 11.5px; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                            <?php echo htmlspecialchars($enq['status']); ?>
                        </span>
                        <?php if (!empty($enq['admin_notes'])): ?>
                            <div style="font-size: 11px; color: #475569; margin-top: 4px; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($enq['admin_notes']); ?>">
                                <i class="fa-regular fa-note-sticky" style="color: #f59e0b;"></i> <?php echo htmlspecialchars($enq['admin_notes']); ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- Actions -->
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px;">
                            
                            <!-- View Full Details Modal Button -->
                            <button type="button" class="btn btn-secondary btn-sm" onclick='viewEnquiryModal(<?php echo json_encode($enq); ?>)' title="View Complete Application Details">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <!-- Update Status & Notes Modal Button -->
                            <button type="button" class="btn btn-primary btn-sm" onclick='openStatusModal(<?php echo json_encode($enq); ?>)' title="Update Status & Add Notes">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Convert to Store Button -->
                            <a href="<?php echo site_url('franchise-admin/stores?convert_name=' . urlencode($enq['client_name']) . '&convert_phone=' . urlencode($clean_phone) . '&convert_email=' . urlencode($enq['email'] ?? '') . '&convert_city=' . urlencode($enq['city'] ?? '')); ?>" class="btn btn-success btn-sm" title="Create Franchise Store Owner for this candidate">
                                <i class="fa-solid fa-store"></i>
                            </a>

                            <!-- Delete Button -->
                            <a href="<?php echo site_url('franchise-admin/enquiry-delete/' . $enq['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to permanently delete Franchise Enquiry #FE-<?php echo str_pad($enq['id'], 4, '0', STR_PAD_LEFT); ?> (<?php echo addslashes($enq['client_name']); ?>)?');" title="Delete Enquiry">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>

                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- =========================================================================
     MODAL 1: VIEW FULL APPLICATION DETAILS
     ========================================================================= -->
<div id="viewEnquiryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.65); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(3px);">
    <div style="background: #ffffff; border-radius: 14px; width: 100%; max-width: 720px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); border: 1px solid var(--border); animation: modalZoom 0.2s ease-out; margin: 20px;">
        
        <!-- Modal Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #09204b; color: #fff; border-radius: 14px 14px 0 0;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-lines" style="color: #78B722;"></i>
                    <span id="modalEnqTitle">Franchise Application Details</span>
                </h3>
                <small style="color: #94a3b8; font-size: 12px;" id="modalEnqSubtitle">Received from Voyogo Franchise Opportunity</small>
            </div>
            <button type="button" onclick="closeModal('viewEnquiryModal')" style="background: transparent; border: none; color: #fff; font-size: 20px; cursor: pointer; padding: 4px 8px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body Content -->
        <div style="padding: 24px;" id="modalEnqBody">
            <!-- Populated via Javascript -->
        </div>

        <!-- Modal Footer -->
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 0 0 14px 14px;">
            <div id="modalFooterActions">
                <!-- WhatsApp button & phone call button populated dynamically -->
            </div>
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewEnquiryModal')">Close</button>
        </div>

    </div>
</div>

<!-- =========================================================================
     MODAL 2: UPDATE STATUS & ADMIN NOTES
     ========================================================================= -->
<div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.65); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(3px);">
    <div style="background: #ffffff; border-radius: 14px; width: 100%; max-width: 520px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); border: 1px solid var(--border); animation: modalZoom 0.2s ease-out; margin: 20px;">
        
        <form method="post" id="statusForm" action="">
            <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 14px 14px 0 0;">
                <h3 style="font-size: 17px; font-weight: 700; color: #0f172a;" id="statusModalTitle">Update Enquiry Status</h3>
                <button type="button" onclick="closeModal('statusModal')" style="background: transparent; border: none; font-size: 18px; color: #64748b; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div style="padding: 24px;">
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Application Status <span style="color:#ef4444;">*</span></label>
                    <select name="status" id="statusSelect" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-weight: 600;">
                        <option value="New">⚡ New Application</option>
                        <option value="Contacted">📞 Contacted (Called / Emailed)</option>
                        <option value="In Discussion">💬 In Discussion (Prospect Review / Meeting)</option>
                        <option value="Converted">✅ Converted (Approved Store Partner)</option>
                        <option value="Rejected">❌ Rejected / Not Interested</option>
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Internal Admin Notes</label>
                    <textarea name="admin_notes" id="adminNotesText" rows="4" placeholder="Enter follow-up remarks, meeting notes, investment capability, next call date, etc..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px;"></textarea>
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 14px 14px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </div>
        </form>

    </div>
</div>

<style>
@keyframes modalZoom {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
}
.detail-box-label {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}
.detail-box-val {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    word-break: break-word;
}
</style>

<script>
function openModal(id) {
    const m = document.getElementById(id);
    if (m) m.style.display = 'flex';
}

function closeModal(id) {
    const m = document.getElementById(id);
    if (m) m.style.display = 'none';
}

// Close on backdrop click
window.addEventListener('click', function(e) {
    if (e.target.id === 'viewEnquiryModal') closeModal('viewEnquiryModal');
    if (e.target.id === 'statusModal') closeModal('statusModal');
});

function openStatusModal(enq) {
    document.getElementById('statusModalTitle').textContent = 'Update Enquiry #' + String(enq.id).padStart(4, '0') + ' (' + enq.client_name + ')';
    document.getElementById('statusSelect').value = enq.status || 'New';
    document.getElementById('adminNotesText').value = enq.admin_notes || '';
    document.getElementById('statusForm').action = '<?php echo site_url("franchise-admin/enquiry-status/"); ?>' + enq.id;
    openModal('statusModal');
}

function viewEnquiryModal(enq) {
    document.getElementById('modalEnqTitle').textContent = 'Application #FE-' + String(enq.id).padStart(4, '0') + ' - ' + enq.client_name;
    document.getElementById('modalEnqSubtitle').textContent = 'Submitted on ' + (enq.created_at || 'Recently');

    const cleanPhone = (enq.mobile_number || '').replace(/[^0-9]/g, '');
    const waPhone = cleanPhone.length === 10 ? '91' + cleanPhone : cleanPhone;

    let html = `
        <!-- Section 1: Candidate Contact Info -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-user-circle" style="color: #78B722;"></i>
            Step 1: Personal & Contact Information
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <div class="detail-box-label">Full Name</div>
                <div class="detail-box-val">${escapeHtml(enq.client_name)}</div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Mobile Number</div>
                <div class="detail-box-val">
                    ${escapeHtml(enq.mobile_number)}
                    ${enq.otp ? '<span style="background:#dcfce7; color:#15803d; font-size:10.5px; padding:2px 6px; border-radius:4px; margin-left:6px;"><i class="fa-solid fa-check"></i> OTP Verified</span>' : ''}
                </div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Email Address</div>
                <div class="detail-box-val"><a href="mailto:${escapeHtml(enq.email || '')}" style="color:#0284c7;">${escapeHtml(enq.email || 'Not provided')}</a></div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Application Status</div>
                <div class="detail-box-val"><span class="badge badge-info">${escapeHtml(enq.status || 'New')}</span></div>
            </div>
        </div>

        <!-- Section 2: Location Preferences -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-location-dot" style="color: #78B722;"></i>
            Step 2: Location Preferences
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <div class="detail-box-label">City / Target Location</div>
                <div class="detail-box-val">${escapeHtml(enq.city || 'Not specified')}</div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">State</div>
                <div class="detail-box-val">${escapeHtml(enq.state || 'Not specified')}</div>
            </div>
            <div class="detail-box" style="grid-column: span 2;">
                <div class="detail-box-label">Preferred Locality / Area For Store Office</div>
                <div class="detail-box-val">${escapeHtml(enq.preferred_location || 'Not specified')}</div>
            </div>
        </div>

        <!-- Section 3 & 4: Business Background & Timeline -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-briefcase" style="color: #78B722;"></i>
            Steps 3 & 4: Business Background & Timeline
        </div>
        <div class="detail-grid">
            <div class="detail-box">
                <div class="detail-box-label">Currently Owns / Operates a Business?</div>
                <div class="detail-box-val">${escapeHtml(enq.own_business || 'No')}</div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Current Profession / Line of Business</div>
                <div class="detail-box-val">${escapeHtml(enq.current_profession || 'Not specified')}</div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Planned Timeline to Start</div>
                <div class="detail-box-val"><span style="color:#0284c7;">⚡ ${escapeHtml(enq.start_timeline || 'Immediately')}</span></div>
            </div>
            <div class="detail-box">
                <div class="detail-box-label">Previously Operated a Franchise?</div>
                <div class="detail-box-val">${escapeHtml(enq.previous_franchise || 'No')}</div>
            </div>
        </div>

        <!-- Section 5: Professional Associations -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-handshake" style="color: #78B722;"></i>
            Step 5: Professional & Business Associations
        </div>
        <div class="detail-box" style="margin-bottom: 20px;">
            <div class="detail-box-label">Member of Business / Professional Associations?</div>
            <div class="detail-box-val" style="margin-top: 4px;">
                ${enq.association_member === 'Yes' ? '<strong>Yes</strong>: ' : 'No'}
                ${enq.associations ? '<span style="color:#0369a1; font-weight:600;">' + escapeHtml(enq.associations) + '</span>' : 'None specified'}
            </div>
        </div>

        <!-- Section 6: Discovery & Message -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-circle-info" style="color: #78B722;"></i>
            Step 6: Discovery & Candidate Message
        </div>
        <div class="detail-grid">
            <div class="detail-box" style="grid-column: span 2;">
                <div class="detail-box-label">How Did You Hear About Voyogo?</div>
                <div class="detail-box-val"><i class="fa-solid fa-bullhorn" style="color:#64748b; font-size:11px;"></i> ${escapeHtml(enq.hear_about_us || 'Website')}</div>
            </div>
            <div class="detail-box" style="grid-column: span 2;">
                <div class="detail-box-label">Candidate Requirements / Message</div>
                <div class="detail-box-val" style="font-weight: 400; color:#334155; line-height: 1.5;">
                    ${escapeHtml(enq.message || 'No additional message was provided by the candidate.')}
                </div>
            </div>
        </div>

        <!-- Section 7: Internal Admin Notes -->
        <div style="font-size: 14px; font-weight: 700; color: #09204b; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clipboard-check" style="color: #78B722;"></i>
            Admin Notes & Audit Log
        </div>
        <div class="detail-box" style="background: #fffbeb; border-color: #fde68a;">
            <div class="detail-box-label" style="color: #92400e;">Staff Notes & Call History</div>
            <div class="detail-box-val" style="font-weight: 400; color: #78350f;">
                ${escapeHtml(enq.admin_notes || 'No notes added yet. Click "Update Status & Notes" to record call details.')}
            </div>
        </div>
    `;

    document.getElementById('modalEnqBody').innerHTML = html;

    // Footer actions
    let footerHtml = `
        <a href="tel:${escapeHtml(enq.mobile_number)}" class="btn btn-primary btn-sm" style="margin-right: 8px;">
            <i class="fa-solid fa-phone"></i> Call Candidate
        </a>
    `;
    if (cleanPhone) {
        footerHtml += `
            <a href="https://wa.me/${waPhone}?text=${encodeURIComponent('Hello ' + enq.client_name + ', thank you for your enquiry regarding Voyogo Franchise Opportunity.')}" target="_blank" class="btn btn-success btn-sm" style="background:#25D366; margin-right: 8px;">
                <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </a>
        `;
    }
    footerHtml += `
        <button type="button" class="btn btn-secondary btn-sm" onclick='openStatusModal(${JSON.stringify(enq)})'>
            <i class="fa-solid fa-pen-to-square"></i> Change Status / Notes
        </button>
    `;
    document.getElementById('modalFooterActions').innerHTML = footerHtml;

    openModal('viewEnquiryModal');
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
