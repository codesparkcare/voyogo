<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hold_bookings = $hold_bookings ?? array();
$status_filter = $status_filter ?? '';
?>

<!-- Header Section -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-hand-holding-dollar" style="color: #78B722;"></i> Manage Hold Bookings (HB)
        </h2>
        <p style="font-size: 13.5px; color: #64748b; margin: 0;">Track live airline ticketing deadlines, confirm tickets from store float, and manage held PNRs.</p>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?php echo site_url('franchise/flight'); ?>" style="background: #78B722; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(120, 183, 34, 0.3);">
            <i class="fa-solid fa-plus"></i> New Flight Search
        </a>
        <a href="<?php echo site_url('franchise/bookings'); ?>" style="background: #09204b; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-clipboard-list"></i> All Confirmed Bookings
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 10px; padding: 14px 18px; color: #065f46; font-size: 14px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-circle-check" style="font-size: 18px; color: #10b981;"></i>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; padding: 14px 18px; color: #991b1b; font-size: 14px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px; color: #ef4444;"></i>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 12px; overflow-x: auto;">
    <a href="<?php echo site_url('franchise/hold_bookings'); ?>" class="filter-pill <?php echo empty($status_filter) ? 'active' : ''; ?>">
        All Holds (<?php echo count($hold_bookings); ?>)
    </a>
    <a href="<?php echo site_url('franchise/hold_bookings?status=held'); ?>" class="filter-pill <?php echo $status_filter === 'held' ? 'active' : ''; ?>">
        <i class="fa-solid fa-clock"></i> Active Holds
    </a>
    <a href="<?php echo site_url('franchise/hold_bookings?status=confirmed'); ?>" class="filter-pill <?php echo $status_filter === 'confirmed' ? 'active' : ''; ?>">
        <i class="fa-solid fa-circle-check"></i> Confirmed &amp; Ticketed
    </a>
    <a href="<?php echo site_url('franchise/hold_bookings?status=expired'); ?>" class="filter-pill <?php echo $status_filter === 'expired' ? 'active' : ''; ?>">
        <i class="fa-solid fa-clock-rotate-left"></i> Expired Holds
    </a>
    <a href="<?php echo site_url('franchise/hold_bookings?status=cancelled'); ?>" class="filter-pill <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">
        <i class="fa-solid fa-ban"></i> Cancelled
    </a>
</div>

<!-- Held Bookings List Table -->
<div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Hold PNR &amp; Ref</th>
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Sector / Flight</th>
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Travel Date</th>
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Ticketing Time Limit</th>
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Net Float to Pay</th>
                <th style="padding: 14px 18px; text-align: left; font-weight: 700; color: #475569;">Status</th>
                <th style="padding: 14px 18px; text-align: right; font-weight: 700; color: #475569;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($hold_bookings)): ?>
            <tr>
                <td colspan="7" style="text-align: center; color: #94a3b8; padding: 48px;">
                    <i class="fa-solid fa-hand-holding-dollar" style="font-size: 38px; margin-bottom: 12px; display: block; color: #cbd5e1;"></i>
                    <strong style="font-size: 15px; color: #475569; display: block; margin-bottom: 4px;">No Hold Bookings Found</strong>
                    <span style="font-size: 13px;">When you place flights on hold from the Review &amp; Payment page, they will appear here.</span>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($hold_bookings as $h): ?>
                <?php
                $meta = json_decode($h['passenger_details'], true) ?: array();
                $passengers = $meta['passengers'] ?? ($meta['passenger_list'] ?? array());
                $firstPax = !empty($passengers[0]['name']) ? $passengers[0]['name'] : 'Passenger';
                $paxCount = count($passengers);
                
                $holdExpiresAt = !empty($h['hold_expires_at']) ? strtotime($h['hold_expires_at']) : 0;
                $isExpired = ($h['status'] === 'expired') || ($h['status'] === 'held' && $holdExpiresAt > 0 && $holdExpiresAt < time());
                
                $requiredNetFloat = isset($meta['wallet_deduct_amount']) ? (float)$meta['wallet_deduct_amount'] : (float)$h['total_amount'];
                $advancePaid = (float)($h['advance_amount'] ?? 0);
                $netToDeduct = max(0, $requiredNetFloat - $advancePaid);
                ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px 18px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 15px; font-weight: 800; color: #09204b; letter-spacing: 0.5px;"><?php echo htmlspecialchars($h['pnr']); ?></span>
                            <button type="button" onclick="copyText('<?php echo htmlspecialchars($h['pnr']); ?>')" title="Copy PNR" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px;">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                        <small style="color: #64748b; font-size: 11px;">Ref: <?php echo htmlspecialchars($h['booking_ref']); ?></small>
                        <div style="font-size: 11px; color: #0284c7; margin-top: 2px;">
                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($firstPax); ?> <?php echo $paxCount > 1 ? '(+' . ($paxCount - 1) . ')' : ''; ?>
                        </div>
                    </td>

                    <td style="padding: 16px 18px;">
                        <div style="font-weight: 800; color: #0f172a;">
                            <?php echo htmlspecialchars($h['origin']); ?>
                            <i class="fa-solid fa-plane" style="font-size: 11px; margin: 0 4px; color: #78B722;"></i>
                            <?php echo htmlspecialchars($h['destination']); ?>
                        </div>
                        <div style="font-size: 11.5px; color: #475569; margin-top: 2px;">
                            <?php echo htmlspecialchars($h['airline_name']); ?> &bull; <?php echo htmlspecialchars($h['flight_number']); ?>
                        </div>
                    </td>

                    <td style="padding: 16px 18px;">
                        <strong style="color: #0f172a; font-size: 13px;">
                            <?php echo !empty($h['departure_datetime']) ? date('d M Y', strtotime($h['departure_datetime'])) : 'N/A'; ?>
                        </strong>
                        <div style="font-size: 11px; color: #64748b;">
                            <?php echo !empty($h['departure_datetime']) ? date('h:i A', strtotime($h['departure_datetime'])) : ''; ?>
                        </div>
                    </td>

                    <td style="padding: 16px 18px;">
                        <?php if ($h['status'] === 'held' && !$isExpired): ?>
                            <div class="countdown-clock" data-expiry="<?php echo date('c', $holdExpiresAt); ?>" style="font-weight: 800; font-size: 13px; color: #b91c1c; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-stopwatch fa-spin"></i>
                                <span class="timer-text">Calculating...</span>
                            </div>
                            <small style="color: #64748b; font-size: 11px; display: block; margin-top: 3px;">
                                Cutoff: <?php echo date('d M, h:i A', $holdExpiresAt); ?>
                            </small>
                        <?php elseif ($h['status'] === 'confirmed'): ?>
                            <span style="color: #15803d; font-weight: 700; font-size: 12px;">
                                <i class="fa-solid fa-check"></i> Ticketed <?php echo !empty($h['hold_confirmed_at']) ? date('d M, h:i A', strtotime($h['hold_confirmed_at'])) : ''; ?>
                            </span>
                        <?php elseif ($isExpired): ?>
                            <span style="color: #94a3b8; font-weight: 700; font-size: 12px;">
                                <i class="fa-solid fa-hourglass-end"></i> Expired <?php echo date('d M, h:i A', $holdExpiresAt); ?>
                            </span>
                        <?php else: ?>
                            <span style="color: #64748b; font-size: 12px;"><?php echo htmlspecialchars(ucfirst($h['status'])); ?></span>
                        <?php endif; ?>
                    </td>

                    <td style="padding: 16px 18px;">
                        <strong style="color: #09204b; font-size: 14.5px;">₹ <?php echo number_format($netToDeduct, 2); ?></strong>
                        <div style="font-size: 11px; color: #64748b;">
                            Invoice: ₹ <?php echo number_format($h['total_amount'], 2); ?>
                        </div>
                    </td>

                    <td style="padding: 16px 18px;">
                        <?php if ($h['status'] === 'held' && !$isExpired): ?>
                            <span style="background: #fef3c7; color: #b45309; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px; text-transform: uppercase; border: 1px solid #fde68a;">
                                <i class="fa-solid fa-clock"></i> Active Hold
                            </span>
                        <?php elseif ($h['status'] === 'confirmed'): ?>
                            <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px; text-transform: uppercase; border: 1px solid #bbf7d0;">
                                <i class="fa-solid fa-circle-check"></i> Ticketed
                            </span>
                        <?php elseif ($isExpired): ?>
                            <span style="background: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px; text-transform: uppercase; border: 1px solid #e2e8f0;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Expired
                            </span>
                        <?php elseif ($h['status'] === 'cancelled'): ?>
                            <span style="background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px; text-transform: uppercase; border: 1px solid #fecaca;">
                                <i class="fa-solid fa-ban"></i> Cancelled
                            </span>
                        <?php endif; ?>
                    </td>

                    <td style="padding: 16px 18px; text-align: right;">
                        <div style="display: inline-flex; gap: 8px; align-items: center;">
                            <?php if ($h['status'] === 'held' && !$isExpired): ?>
                                <!-- Confirm & Pay Ticket Button -->
                                <button type="button" onclick="openConfirmHoldModal('<?php echo htmlspecialchars($h['booking_ref']); ?>', '<?php echo htmlspecialchars($h['pnr']); ?>', <?php echo $netToDeduct; ?>, '<?php echo htmlspecialchars($h['origin'] . ' → ' . $h['destination']); ?>')" style="background: #78B722; color: #ffffff; border: none; padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(120, 183, 34, 0.3);">
                                    <i class="fa-solid fa-circle-check"></i> Confirm &amp; Pay
                                </button>
                                
                                <!-- Cancel Hold Button -->
                                <button type="button" onclick="confirmCancelHold('<?php echo site_url('franchise/cancel_hold/' . urlencode($h['booking_ref'])); ?>', '<?php echo htmlspecialchars($h['pnr']); ?>')" style="background: #ffffff; color: #dc2626; border: 1px solid #fca5a5; padding: 7px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;" title="Cancel Hold to prevent airline ADM">
                                    <i class="fa-solid fa-xmark"></i> Cancel
                                </button>
                            <?php elseif ($h['status'] === 'confirmed'): ?>
                                <a href="<?php echo site_url('franchise/flight_ticket/' . urlencode($h['booking_ref'])); ?>" target="_blank" style="background: #eff6ff; color: #2563eb; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-print"></i> E-Ticket
                                </a>
                            <?php endif; ?>

                            <!-- Print / View Hold Voucher -->
                            <a href="<?php echo site_url('franchise/hold_voucher/' . urlencode($h['booking_ref'])); ?>" target="_blank" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; text-decoration: none; padding: 7px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;" title="Print Hold Itinerary / Quotation">
                                <i class="fa-solid fa-file-invoice"></i> Quote
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal: Confirm Ticket Issuance from Store Float -->
<div id="confirmHoldModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: #ffffff; border-radius: 14px; max-width: 480px; width: 90%; padding: 26px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); animation: modalIn 0.2s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 18px; font-weight: 800; color: #09204b; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-ticket" style="color: #78B722;"></i> Issue Confirmed Flight Ticket
            </h3>
            <button type="button" onclick="closeConfirmHoldModal()" style="background: none; border: none; font-size: 18px; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>

        <p style="font-size: 13.5px; color: #64748b; line-height: 1.5; margin-bottom: 18px;">
            You are confirming Hold PNR <strong id="modal_pnr" style="color: #09204b;"></strong> for route <span id="modal_route"></span>. This will immediately issue the live e-ticket and deduct the Net Fare from your store float.
        </p>

        <!-- Wallet Float Deduction Summary -->
        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; color: #64748b;">
                <span>Available Store Float:</span>
                <strong style="color: #09204b;">₹ <?php echo number_format($store['wallet_balance'] ?? 0, 2); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px; color: #dc2626; font-weight: 700;">
                <span>Net Float to Deduct:</span>
                <span id="modal_deduct_amount">- ₹ 0.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px dashed #cbd5e1; padding-top: 8px; font-size: 13.5px; font-weight: 800; color: #16a34a;">
                <span>Remaining Float After Issue:</span>
                <span id="modal_remaining_float">₹ 0.00</span>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="button" onclick="closeConfirmHoldModal()" style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Cancel
            </button>
            <a id="modal_confirm_btn" href="#" style="flex: 1.5; background: #78B722; color: #ffffff; text-decoration: none; padding: 12px; border-radius: 8px; font-weight: 800; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(120, 183, 34, 0.35);">
                <i class="fa-solid fa-circle-check"></i> Confirm &amp; Issue Ticket
            </a>
        </div>
    </div>
</div>

<style>
.filter-pill {
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.filter-pill:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.filter-pill.active {
    background: #09204b;
    color: #ffffff;
}
@keyframes modalIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
var availableBalance = <?php echo (float)($store['wallet_balance'] ?? 0); ?>;

function copyText(txt) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(txt);
        alert('PNR copied: ' + txt);
    }
}

function openConfirmHoldModal(bookingRef, pnr, netAmount, route) {
    document.getElementById('modal_pnr').innerText = pnr;
    document.getElementById('modal_route').innerText = route;
    document.getElementById('modal_deduct_amount').innerText = '- ₹ ' + Number(netAmount).toLocaleString('en-IN', {minimumFractionDigits: 2});
    
    var remaining = availableBalance - netAmount;
    var remEl = document.getElementById('modal_remaining_float');
    remEl.innerText = '₹ ' + Number(remaining).toLocaleString('en-IN', {minimumFractionDigits: 2});
    remEl.style.color = (remaining >= 0) ? '#16a34a' : '#ef4444';

    var confirmBtn = document.getElementById('modal_confirm_btn');
    if (remaining < 0) {
        confirmBtn.style.background = '#94a3b8';
        confirmBtn.style.pointerEvents = 'none';
        confirmBtn.innerHTML = '<i class="fa-solid fa-ban"></i> Insufficient Float';
    } else {
        confirmBtn.style.background = '#78B722';
        confirmBtn.style.pointerEvents = 'auto';
        confirmBtn.href = "<?php echo site_url('franchise/confirm_hold_ticket/'); ?>" + encodeURIComponent(bookingRef);
        confirmBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Confirm &amp; Issue Ticket';
    }

    var modal = document.getElementById('confirmHoldModal');
    modal.style.display = 'flex';
}

function closeConfirmHoldModal() {
    var modal = document.getElementById('confirmHoldModal');
    modal.style.display = 'none';
}

function confirmCancelHold(url, pnr) {
    if (confirm('Are you sure you want to cancel Hold PNR: ' + pnr + '?\nThis will release the seats with the airline and prevent ADM charges.')) {
        window.location.href = url;
    }
}

// Live Countdown Timer for Active Holds
function updateCountdownClocks() {
    var clocks = document.querySelectorAll('.countdown-clock');
    var now = new Date().getTime();

    clocks.forEach(function(clock) {
        var expiryStr = clock.getAttribute('data-expiry');
        if (!expiryStr) return;
        var expiryTime = new Date(expiryStr).getTime();
        var diff = expiryTime - now;

        var textSpan = clock.querySelector('.timer-text');
        if (!textSpan) return;

        if (diff <= 0) {
            clock.style.color = '#94a3b8';
            clock.innerHTML = '<i class="fa-solid fa-hourglass-end"></i> Expired';
        } else {
            var hours = Math.floor(diff / (1000 * 60 * 60));
            var mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var secs = Math.floor((diff % (1000 * 60)) / 1000);

            var formatted = String(hours).padStart(2, '0') + 'h ' + String(mins).padStart(2, '0') + 'm ' + String(secs).padStart(2, '0') + 's';
            textSpan.innerText = formatted + ' Remaining';

            if (diff < 15 * 60 * 1000) {
                clock.style.color = '#ef4444'; // Red if less than 15 mins
            } else if (diff < 60 * 60 * 1000) {
                clock.style.color = '#f59e0b'; // Amber if less than 1 hr
            } else {
                clock.style.color = '#003B95'; // Dark blue / navy
            }
        }
    });
}

setInterval(updateCountdownClocks, 1000);
document.addEventListener('DOMContentLoaded', updateCountdownClocks);
</script>
