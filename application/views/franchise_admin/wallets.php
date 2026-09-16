<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Store Wallet Float Management</h2>
        <p style="font-size: 13px; color: #64748b;">Credit or Debit franchise store balances manually and track financial audit logs.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('manualWalletModal')">
        <i class="fa-solid fa-money-bill-transfer"></i> Manual Credit / Debit Top-up
    </button>
</div>

<!-- Stores Wallet Balances Overview -->
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Current Store Wallet Balances</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
        <?php if (empty($stores)): ?>
        <p style="color: #94a3b8; font-size: 13.5px;">No stores created yet.</p>
        <?php else: ?>
        <?php foreach ($stores as $s): ?>
        <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong style="font-size: 13.5px; color: #0f172a; display: block;"><?php echo htmlspecialchars($s['store_name']); ?></strong>
                <small style="color: #0284c7; font-weight: 600;"><?php echo htmlspecialchars($s['agent_code']); ?></small>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px; font-weight: 800; color: #15803d;">₹ <?php echo number_format($s['wallet_balance'], 2); ?></div>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 4px; padding: 3px 8px; font-size: 11px;" onclick="prefillTopup(<?php echo $s['id']; ?>)">
                    <i class="fa-solid fa-plus-minus"></i> Adjust
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Ledger Card -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Wallet Transaction Audit Ledger</h3>
        <span style="font-size: 12px; color: #64748b;">Showing recent transactions</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Store / Agent</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Balance Before</th>
                    <th>Balance After</th>
                    <th>Category</th>
                    <th>Remarks</th>
                    <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ledger)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 32px;">
                        <i class="fa-solid fa-receipt" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No wallet transactions recorded yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($ledger as $tx): ?>
                <tr>
                    <td style="font-size: 12.5px; color: #475569; white-space: nowrap;">
                        <?php echo date('d M Y, h:i A', strtotime($tx['created_at'])); ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($tx['store_name'] ?? ('Store #' . $tx['store_id'])); ?></strong><br>
                        <small style="color: #0284c7;"><?php echo htmlspecialchars($tx['agent_code'] ?? ''); ?></small>
                    </td>
                    <td>
                        <?php if ($tx['transaction_type'] === 'credit'): ?>
                        <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> CREDIT</span>
                        <?php else: ?>
                        <span class="badge badge-danger"><i class="fa-solid fa-arrow-up"></i> DEBIT</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color: <?php echo ($tx['transaction_type'] === 'credit') ? '#15803d' : '#b91c1c'; ?>; font-size: 14px;">
                            <?php echo ($tx['transaction_type'] === 'credit') ? '+' : '-'; ?> ₹ <?php echo number_format($tx['amount'], 2); ?>
                        </strong>
                    </td>
                    <td style="color: #64748b;">₹ <?php echo number_format($tx['previous_balance'], 2); ?></td>
                    <td><strong style="color: #0f172a;">₹ <?php echo number_format($tx['new_balance'], 2); ?></strong></td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($tx['reference_type']); ?></span></td>
                    <td style="max-width: 200px; font-size: 12.5px; color: #334155;">
                        <?php echo htmlspecialchars($tx['remarks']); ?>
                    </td>
                    <td><small style="color: #64748b;"><?php echo htmlspecialchars($tx['created_by'] ?? 'system'); ?></small></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==================== MANUAL WALLET TOP-UP MODAL ==================== -->
<div id="manualWalletModal" class="modal-overlay" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;"><i class="fa-solid fa-money-bill-transfer"></i> Manual Wallet Adjustment</h3>
            <button type="button" class="modal-close" onclick="closeModal('manualWalletModal')">&times;</button>
        </div>
        <form method="post" action="<?php echo site_url('franchise-admin/wallet-update'); ?>">
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Select Franchise Store Owner <span style="color: #ef4444;">*</span></label>
                    <select name="store_id" id="modal_store_id" required>
                        <option value="">-- Choose Store --</option>
                        <?php foreach ($stores as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo (isset($_GET['store_id']) && $_GET['store_id'] == $s['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['store_name']); ?> (<?php echo htmlspecialchars($s['agent_code']); ?>) - Balance: ₹ <?php echo number_format($s['wallet_balance'], 2); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Action Type <span style="color: #ef4444;">*</span></label>
                        <select name="transaction_type" required>
                            <option value="credit">Credit (Add Funds)</option>
                            <option value="debit">Debit (Deduct Funds)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount (₹) <span style="color: #ef4444;">*</span></label>
                        <input type="number" step="0.01" min="1" name="amount" required placeholder="e.g. 5000.00">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 14px;">
                    <label>Remarks / Payment Reference <span style="color: #ef4444;">*</span></label>
                    <textarea name="remarks" required rows="3" placeholder="e.g. Bank Transfer Ref #NEFT12345 / Cash deposit received"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('manualWalletModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Execute Transaction</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    .modal-card {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #94a3b8;
        cursor: pointer;
    }
    .modal-body { padding: 24px; }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-group label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 13.5px;
        outline: none;
    }
</style>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function prefillTopup(storeId) {
    document.getElementById('modal_store_id').value = storeId;
    openModal('manualWalletModal');
}
<?php if (isset($_GET['store_id'])): ?>
window.onload = function() {
    openModal('manualWalletModal');
};
<?php endif; ?>
</script>
