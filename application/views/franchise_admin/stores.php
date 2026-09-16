<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a;">Franchise Store Owners</h2>
        <p style="font-size: 13px; color: #64748b;">Create and manage franchise store accounts, credentials, and access statuses.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('addStoreModal')">
        <i class="fa-solid fa-plus"></i> Add New Store Owner
    </button>
</div>

<!-- Search & Filter Bar -->
<div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form method="get" action="<?php echo site_url('franchise-admin/stores'); ?>" style="display: flex; gap: 12px; align-items: center;">
        <div style="flex: 1; position: relative;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search ?? ''); ?>" placeholder="Search by Agent Code, Store Name, Username, Phone, Email..." style="width: 100%; padding: 9px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13.5px;">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        <?php if (!empty($search)): ?>
            <a href="<?php echo site_url('franchise-admin/stores'); ?>" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Stores Table Card -->
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Agent Code</th>
                    <th>Store Name & Username</th>
                    <th>Contact Info</th>
                    <th>GST No.</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stores)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 32px;">
                        <i class="fa-solid fa-store-slash" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No franchise stores found. Click "Add New Store Owner" to create one.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($stores as $s): ?>
                <tr>
                    <td>
                        <strong style="color: #0284c7; font-size: 14px;"><?php echo htmlspecialchars($s['agent_code']); ?></strong>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($s['store_name']); ?></strong><br>
                        <small style="color: #64748b;"><i class="fa-solid fa-user" style="font-size: 11px;"></i> @<?php echo htmlspecialchars($s['username']); ?></small>
                    </td>
                    <td>
                        <div><i class="fa-solid fa-phone" style="font-size: 11px; color: #64748b; width: 14px;"></i> <?php echo htmlspecialchars($s['phone']); ?></div>
                        <div><i class="fa-solid fa-envelope" style="font-size: 11px; color: #64748b; width: 14px;"></i> <?php echo htmlspecialchars($s['email']); ?></div>
                    </td>
                    <td>
                        <?php echo !empty($s['gst_number']) ? htmlspecialchars($s['gst_number']) : '<span style="color: #94a3b8;">N/A</span>'; ?>
                    </td>
                    <td>
                        <strong style="color: #15803d; font-size: 14px;">₹ <?php echo number_format($s['wallet_balance'], 2); ?></strong>
                    </td>
                    <td>
                        <span class="badge <?php echo ($s['status'] === 'active') ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo htmlspecialchars($s['status']); ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px;">
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-secondary btn-sm" onclick='openEditModal(<?php echo json_encode($s); ?>)' title="Edit Store">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            <!-- Toggle Status Button -->
                            <a href="<?php echo site_url('franchise-admin/store-toggle/' . $s['id']); ?>" 
                               class="btn <?php echo ($s['status'] === 'active') ? 'btn-danger' : 'btn-success'; ?> btn-sm" 
                               onclick="return confirm('Are you sure you want to change this store status to <?php echo ($s['status'] === 'active') ? 'INACTIVE' : 'ACTIVE'; ?>?');"
                               title="<?php echo ($s['status'] === 'active') ? 'Deactivate Store' : 'Activate Store'; ?>">
                                <i class="fa-solid <?php echo ($s['status'] === 'active') ? 'fa-ban' : 'fa-check'; ?>"></i>
                                <?php echo ($s['status'] === 'active') ? 'Disable' : 'Enable'; ?>
                            </a>

                            <!-- Wallet Manage Button -->
                            <a href="<?php echo site_url('franchise-admin/wallets?store_id=' . $s['id']); ?>" class="btn btn-primary btn-sm" title="Manage Wallet Float">
                                <i class="fa-solid fa-wallet"></i>
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

<!-- ==================== ADD STORE MODAL ==================== -->
<div id="addStoreModal" class="modal-overlay" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;"><i class="fa-solid fa-store"></i> Create Franchise Store Owner</h3>
            <button type="button" class="modal-close" onclick="closeModal('addStoreModal')">&times;</button>
        </div>
        <form method="post" action="<?php echo site_url('franchise-admin/store-create'); ?>">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Agent Code <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="agent_code" required placeholder="e.g. VOY-DEL01" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label>Store / Agency Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="store_name" required placeholder="e.g. Voyogo Connaught Place">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Login Username <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="username" required placeholder="e.g. agent_delhi">
                    </div>
                    <div class="form-group">
                        <label>Login Password <span style="color: #ef4444;">*</span></label>
                        <input type="password" name="password" required placeholder="Strong password">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Phone Number <span style="color: #ef4444;">*</span></label>
                        <input type="tel" name="phone" required placeholder="e.g. 9876543210">
                    </div>
                    <div class="form-group">
                        <label>Email ID <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="email" required placeholder="e.g. agent@example.com">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" name="gst_number" placeholder="e.g. 07AAAAA0000A1Z5" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label>Initial Wallet Top-up (₹)</label>
                        <input type="number" step="0.01" min="0" name="initial_wallet" value="0.00">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label>Store Physical Address</label>
                    <textarea name="address" rows="3" placeholder="Full branch address, City, State, PIN code..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addStoreModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Create Store Owner</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT STORE MODAL ==================== -->
<div id="editStoreModal" class="modal-overlay" style="display: none;">
    <div class="modal-card">
        <div class="modal-header">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;"><i class="fa-solid fa-pen-to-square"></i> Edit Franchise Store</h3>
            <button type="button" class="modal-close" onclick="closeModal('editStoreModal')">&times;</button>
        </div>
        <form id="editStoreForm" method="post" action="">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Agent Code (Read Only)</label>
                        <input type="text" id="edit_agent_code" disabled style="background: #f1f5f9; color: #64748b;">
                    </div>
                    <div class="form-group">
                        <label>Username (Read Only)</label>
                        <input type="text" id="edit_username" disabled style="background: #f1f5f9; color: #64748b;">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Store / Agency Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="edit_store_name" name="store_name" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span style="color: #ef4444;">*</span></label>
                        <input type="tel" id="edit_phone" name="phone" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Email ID <span style="color: #ef4444;">*</span></label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" id="edit_gst_number" name="gst_number" style="text-transform: uppercase;">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label>New Password <small style="color: #64748b; font-weight: normal;">(leave blank to keep current password)</small></label>
                    <input type="password" name="password" placeholder="Enter new password if changing">
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label>Store Physical Address</label>
                    <textarea id="edit_address" name="address" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editStoreModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Changes</button>
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
        max-width: 620px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
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
    .modal-close:hover { color: #0f172a; }
    .modal-body {
        padding: 24px;
        overflow-y: auto;
    }
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
        margin-bottom: 14px;
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
        transition: border-color 0.15s;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: #2563eb;
    }
</style>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function openEditModal(store) {
    document.getElementById('editStoreForm').action = '<?php echo site_url('franchise-admin/store-edit/'); ?>' + store.id;
    document.getElementById('edit_agent_code').value = store.agent_code;
    document.getElementById('edit_username').value = store.username;
    document.getElementById('edit_store_name').value = store.store_name;
    document.getElementById('edit_phone').value = store.phone;
    document.getElementById('edit_email').value = store.email;
    document.getElementById('edit_gst_number').value = store.gst_number || '';
    document.getElementById('edit_address').value = store.address || '';
    openModal('editStoreModal');
}
</script>
