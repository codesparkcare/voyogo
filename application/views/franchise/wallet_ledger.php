<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a;">Store Wallet Statement & Passbook</h2>
        <p style="font-size: 13px; color: #64748b;">Complete transaction ledger for wallet credits from Franchise Admin and ticket deductions.</p>
    </div>
</div>

<!-- Wallet Float Summary Card -->
<div style="background: linear-gradient(135deg, #09204b 0%, #1e3a8a 100%); border-radius: 16px; padding: 28px 32px; color: #ffffff; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(9, 32, 75, 0.2); display: flex; justify-content: space-between; align-items: center;">
    <div>
        <span style="font-size: 12px; font-weight: 700; color: #86efac; text-transform: uppercase; letter-spacing: 0.5px;">Available Booking Float</span>
        <div style="font-size: 36px; font-weight: 800; margin: 4px 0;">₹ <?php echo number_format($store['wallet_balance'], 2); ?></div>
        <div style="font-size: 13px; color: #cbd5e1;">
            Agent Code: <strong><?php echo htmlspecialchars($store['agent_code']); ?></strong> &bull; Agency: <strong><?php echo htmlspecialchars($store['store_name']); ?></strong>
        </div>
    </div>

    <div style="text-align: right; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 16px 20px; max-width: 320px;">
        <div style="font-size: 12px; font-weight: 700; color: #fde047; margin-bottom: 4px;"><i class="fa-solid fa-circle-info"></i> Need Wallet Top-up?</div>
        <div style="font-size: 12px; color: #e2e8f0; line-height: 1.4;">
            Contact your Voyogo Franchise Account Manager to transfer funds and update your float instantly.
        </div>
    </div>
</div>

<!-- Ledger Table Card -->
<div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
    <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Transaction Passbook</h3>
        <span style="font-size: 12px; color: #64748b;">Showing last 100 transactions</span>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Date & Time</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Type</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Amount</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Balance Before</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Balance After</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Category</th>
                <th style="padding: 12px 18px; text-align: left; font-weight: 700; color: #475569;">Remarks / Reference</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ledger)): ?>
            <tr>
                <td colspan="7" style="text-align: center; color: #94a3b8; padding: 36px;">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size: 28px; margin-bottom: 8px; display: block; color: #cbd5e1;"></i>
                    No transactions recorded on your store wallet yet.
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($ledger as $tx): ?>
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 18px; color: #475569; font-size: 12.5px; white-space: nowrap;">
                    <?php echo date('d M Y, h:i A', strtotime($tx['created_at'])); ?>
                </td>
                <td style="padding: 12px 18px;">
                    <?php if ($tx['transaction_type'] === 'credit'): ?>
                    <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-arrow-down"></i> CREDIT
                    </span>
                    <?php else: ?>
                    <span style="background: #fee2e2; color: #b91c1c; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-arrow-up"></i> DEBIT
                    </span>
                    <?php endif; ?>
                </td>
                <td style="padding: 12px 18px;">
                    <strong style="color: <?php echo ($tx['transaction_type'] === 'credit') ? '#15803d' : '#b91c1c'; ?>; font-size: 14px;">
                        <?php echo ($tx['transaction_type'] === 'credit') ? '+' : '-'; ?> ₹ <?php echo number_format($tx['amount'], 2); ?>
                    </strong>
                </td>
                <td style="padding: 12px 18px; color: #64748b;">₹ <?php echo number_format($tx['previous_balance'], 2); ?></td>
                <td style="padding: 12px 18px;"><strong style="color: #0f172a;">₹ <?php echo number_format($tx['new_balance'], 2); ?></strong></td>
                <td style="padding: 12px 18px;">
                    <span style="background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 600; padding: 2px 6px; border-radius: 4px;">
                        <?php echo htmlspecialchars($tx['reference_type']); ?>
                    </span>
                </td>
                <td style="padding: 12px 18px; color: #334155; font-size: 12.5px;">
                    <?php echo htmlspecialchars($tx['remarks']); ?>
                    <?php if (!empty($tx['reference_id'])): ?>
                    <div style="font-size: 11px; color: #0284c7;">Ref: <?php echo htmlspecialchars($tx['reference_id']); ?></div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
