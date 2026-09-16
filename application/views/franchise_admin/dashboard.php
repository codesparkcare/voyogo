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
