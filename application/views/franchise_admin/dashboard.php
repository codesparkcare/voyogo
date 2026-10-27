<!-- KPI Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
    
    <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class="fa-solid fa-store"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo (int)($stats['total_stores'] ?? 0); ?></div>
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">Total Stores (<?php echo (int)($stats['active_stores'] ?? 0); ?> Active)</div>
        </div>
    </div>

    <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 800; color: #15803d;">₹ <?php echo number_format($stats['total_wallet_float'] ?? 0, 2); ?></div>
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">Total Store Wallet Float</div>
        </div>
    </div>

    <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class="fa-solid fa-plane-departure"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo (int)($stats['total_flight_bookings'] ?? 0); ?></div>
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">Flight Bookings</div>
        </div>
    </div>

    <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 50px; height: 50px; border-radius: 12px; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 22px;">
            <i class="fa-solid fa-hotel"></i>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo (int)($stats['total_hotel_bookings'] ?? 0); ?></div>
            <div style="font-size: 13px; color: #64748b; font-weight: 500;">Hotel Bookings</div>
        </div>
    </div>

    <!-- Franchise Enquiries KPI -->
    <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" style="text-decoration: none;">
        <div class="card" style="padding: 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 0; transition: transform 0.15s, box-shadow 0.15s; border-left: 4px solid #78B722;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: #ecfccb; color: #65a30d; display: flex; align-items: center; justify-content: center; font-size: 22px; position: relative;">
                <i class="fa-solid fa-clipboard-question"></i>
                <?php if (!empty($stats['new_enquiries']) && $stats['new_enquiries'] > 0): ?>
                    <span style="position: absolute; top: 4px; right: 4px; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; border: 2px solid #fff;"></span>
                <?php endif; ?>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <?php echo (int)($stats['total_enquiries'] ?? 0); ?>
                    <?php if (!empty($stats['new_enquiries']) && $stats['new_enquiries'] > 0): ?>
                        <span style="font-size: 11px; background: #fee2e2; color: #dc2626; font-weight: 700; padding: 2px 7px; border-radius: 10px;"><?php echo $stats['new_enquiries']; ?> New</span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 13px; color: #64748b; font-weight: 500;">Franchise Enquiries</div>
            </div>
        </div>
    </a>

</div>

<!-- Store Owners Overview & Recent Transactions -->
<div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 24px;">
    
    <!-- Stores Table -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 style="font-size: 17px; font-weight: 700; color: #0f172a;">Franchise Stores</h2>
            <a href="<?php echo site_url('franchise-admin/stores'); ?>" class="btn btn-primary btn-sm">Manage All Stores</a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Agent Code</th>
                        <th>Store Name</th>
                        <th>Wallet Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_stores)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 24px;">No stores created yet. Click "Manage All Stores" to add your first franchise store owner.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach (array_slice($recent_stores, 0, 6) as $s): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($s['agent_code']); ?></strong></td>
                        <td><?php echo htmlspecialchars($s['store_name']); ?><br><small style="color: #64748b;"><?php echo htmlspecialchars($s['phone']); ?></small></td>
                        <td><strong style="color: #15803d;">₹ <?php echo number_format($s['wallet_balance'], 2); ?></strong></td>
                        <td>
                            <span class="badge <?php echo ($s['status'] === 'active') ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo htmlspecialchars($s['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Wallet Activity -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 style="font-size: 17px; font-weight: 700; color: #0f172a;">Recent Wallet Activity</h2>
            <a href="<?php echo site_url('franchise-admin/wallets'); ?>" class="btn btn-secondary btn-sm">View Ledger</a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Store</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_tx)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 24px;">No wallet transactions yet.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($recent_tx as $tx): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($tx['store_name'] ?? 'Store'); ?></strong><br>
                            <small style="color: #64748b;"><?php echo htmlspecialchars($tx['remarks'] ?: $tx['reference_type']); ?></small>
                        </td>
                        <td>
                            <span class="badge <?php echo ($tx['transaction_type'] === 'credit') ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo htmlspecialchars(strtoupper($tx['transaction_type'])); ?>
                            </span>
                        </td>
                        <td>
                            <strong style="color: <?php echo ($tx['transaction_type'] === 'credit') ? '#16a34a' : '#dc2626'; ?>;">
                                <?php echo ($tx['transaction_type'] === 'credit') ? '+' : '-'; ?> ₹ <?php echo number_format($tx['amount'], 2); ?>
                            </strong>
                        </td>
                        <td><small style="color: #64748b;"><?php echo date('d M, H:i', strtotime($tx['created_at'])); ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Recent Franchise Enquiries Applications -->
<div class="card" style="margin-top: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-clipboard-question" style="color: #78B722;"></i>
                Recent Franchise Enquiries
            </h2>
            <p style="font-size: 12.5px; color: #64748b; margin-top: 2px;">Latest candidate applications submitted from /voyogo/franchise</p>
        </div>
        <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" class="btn btn-primary btn-sm">
            Manage FRANCHISE ENQUIRY (<?php echo (int)($stats['total_enquiries'] ?? 0); ?>)
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Candidate</th>
                    <th>Location</th>
                    <th>Profession</th>
                    <th>Timeline</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_enquiries)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 28px;">
                        No franchise enquiries submitted yet. When candidates fill the form at <code>/voyogo/franchise</code>, they will appear here.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($recent_enquiries as $enq): ?>
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-size: 13px; font-family: monospace;">#FE-<?php echo str_pad($enq['id'], 4, '0', STR_PAD_LEFT); ?></strong>
                        <br><small style="color: #64748b; font-size: 11px;"><?php echo !empty($enq['created_at']) ? date('d M Y', strtotime($enq['created_at'])) : '-'; ?></small>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($enq['client_name']); ?></strong>
                        <?php if ($enq['status'] === 'New'): ?>
                            <span style="background: #fee2e2; color: #dc2626; font-size: 10px; font-weight: 700; padding: 1px 5px; border-radius: 4px; margin-left: 4px;">NEW</span>
                        <?php endif; ?>
                        <br><small style="color: #64748b;"><i class="fa-solid fa-phone" style="font-size: 10px;"></i> <?php echo htmlspecialchars($enq['mobile_number']); ?></small>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($enq['city'] ?: '-'); ?></strong>, <?php echo htmlspecialchars($enq['state'] ?: '-'); ?>
                        <?php if (!empty($enq['preferred_location'])): ?>
                            <br><small style="color: #0284c7;"><?php echo htmlspecialchars($enq['preferred_location']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($enq['current_profession'] ?: '-'); ?>
                        <br><small style="color: #64748b;">Business: <?php echo htmlspecialchars($enq['own_business'] ?: 'No'); ?></small>
                    </td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 11px; text-transform: none;">
                            <?php echo htmlspecialchars($enq['start_timeline'] ?: 'Immediately'); ?>
                        </span>
                    </td>
                    <td>
                        <?php
                            $st_bg = '#e0f2fe'; $st_c = '#0284c7';
                            if ($enq['status'] === 'New') { $st_bg = '#fee2e2'; $st_c = '#dc2626'; }
                            elseif ($enq['status'] === 'Converted') { $st_bg = '#dcfce7'; $st_c = '#15803d'; }
                            elseif ($enq['status'] === 'In Discussion') { $st_bg = '#ede9fe'; $st_c = '#7c3aed'; }
                            elseif ($enq['status'] === 'Contacted') { $st_bg = '#cffafe'; $st_c = '#0891b2'; }
                        ?>
                        <span class="badge" style="background: <?php echo $st_bg; ?>; color: <?php echo $st_c; ?>; font-size: 11px;">
                            <?php echo htmlspecialchars($enq['status']); ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="<?php echo site_url('franchise-admin/enquiries'); ?>" class="btn btn-secondary btn-sm" title="Manage enquiry">
                            <i class="fa-solid fa-arrow-right"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
