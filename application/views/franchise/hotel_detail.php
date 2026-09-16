<div style="margin-bottom: 20px;">
    <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 6px; color: #475569; text-decoration: none; font-size: 13.5px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Hotel Results
    </a>
</div>

<?php 
$hname = $hotel['name'] ?? 'Luxury Resort & Spa';
$hrating = (int)($hotel['rating'] ?? 5);
$haddress = $hotel['address'] ?? ($city . ', India');
$himg = !empty($hotel['heroImage']) ? $hotel['heroImage'] : (!empty($hotel['image']) ? $hotel['image'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1000&q=80');

$rooms_list = $hotel['rooms'] ?? array(
    array(
        'room_type'      => 'Deluxe King Room',
        'room_id'        => 'RM_DLX_01',
        'inclusion'      => 'Room with Breakfast & Free High Speed Wi-Fi',
        'cancellation'   => 'Free Cancellation before 48 hours',
        'price_per_night'=> 5500.00
    ),
    array(
        'room_type'      => 'Executive Sea View Suite',
        'room_id'        => 'RM_EXEC_02',
        'inclusion'      => 'All Meals (Breakfast + Dinner) & Airport Transfer',
        'cancellation'   => 'Free Cancellation before 24 hours',
        'price_per_night'=> 8200.00
    ),
    array(
        'room_type'      => 'Presidential Villa with Private Pool',
        'room_id'        => 'RM_PRES_03',
        'inclusion'      => 'All Inclusive Luxury Package & 24/7 Butler',
        'cancellation'   => 'Non-refundable',
        'price_per_night'=> 14500.00
    )
);

// Calculate total nights
$nights = max(1, (int)((strtotime($checkout) - strtotime($checkin)) / 86400));
?>

<!-- Hotel Header Card -->
<div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px;">
    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 24px;">
        <div style="height: 240px; background: #e2e8f0;">
            <img src="<?php echo htmlspecialchars($himg); ?>" alt="<?php echo htmlspecialchars($hname); ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 24px; display: flex; flex-direction: column; justify-content: center;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span style="color: #f59e0b; font-size: 14px;"><?php for($s=0;$s<$hrating;$s++) echo '★'; ?></span>
                <span style="font-size: 12px; background: #eff6ff; color: #2563eb; font-weight: 700; padding: 2px 8px; border-radius: 4px;"><?php echo $hrating; ?> Star Hotel</span>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 8px;"><?php echo htmlspecialchars($hname); ?></h1>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                <i class="fa-solid fa-location-dot" style="color: #ef4444; margin-right: 4px;"></i> <?php echo htmlspecialchars($haddress); ?>
            </p>

            <div style="display: flex; gap: 20px; font-size: 13px; color: #334155;">
                <div><i class="fa-solid fa-calendar-check" style="color: #78B722;"></i> <strong>Check-in:</strong> <?php echo date('d M Y', strtotime($checkin)); ?></div>
                <div><i class="fa-solid fa-calendar-xmark" style="color: #ef4444;"></i> <strong>Check-out:</strong> <?php echo date('d M Y', strtotime($checkout)); ?> (<?php echo $nights; ?> Nights)</div>
                <div><i class="fa-solid fa-user-group" style="color: #0284c7;"></i> <?php echo (int)$rooms; ?> Room(s), <?php echo (int)$adults; ?> Adult(s)</div>
            </div>
        </div>
    </div>
</div>

<!-- Available Room Categories -->
<h2 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 16px;">Available Room Options (B2B Net Rates)</h2>

<div style="display: flex; flex-direction: column; gap: 16px;">
    <?php foreach ($rooms_list as $r): 
        $r_type = $r['room_type'] ?? $r['name'] ?? 'Standard Room';
        $r_id   = $r['room_id'] ?? $r['id'] ?? 'RM_001';
        $p_night = (float)($r['price_per_night'] ?? $r['price'] ?? 5500);
        $total_room_cost = $p_night * $nights * $rooms;
    ?>
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 26px; display: grid; grid-template-columns: 1fr 240px; gap: 24px; align-items: center;">
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 6px;"><?php echo htmlspecialchars($r_type); ?></h3>
            <div style="font-size: 13px; color: #166534; font-weight: 600; margin-bottom: 6px;">
                <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($r['inclusion'] ?? 'Room with Breakfast'); ?>
            </div>
            <div style="font-size: 12px; color: #0284c7;">
                <i class="fa-solid fa-shield-halved"></i> <?php echo htmlspecialchars($r['cancellation'] ?? 'Free Cancellation available'); ?>
            </div>
        </div>

        <div style="text-align: right; border-left: 1px solid #f1f5f9; padding-left: 20px;">
            <div style="font-size: 11px; color: #64748b;">Per Night: ₹ <?php echo number_format($p_night, 2); ?></div>
            <div style="font-size: 22px; font-weight: 800; color: #09204b; margin: 4px 0;">
                ₹ <?php echo number_format($total_room_cost, 2); ?>
            </div>
            <div style="font-size: 11px; color: #64748b; margin-bottom: 12px;">Total for <?php echo $nights; ?> nights (Net Fare)</div>

            <form method="post" action="<?php echo site_url('franchise/hotel_review'); ?>">
                <input type="hidden" name="hotel_id" value="<?php echo htmlspecialchars($hotel['id'] ?? 'H_001'); ?>">
                <input type="hidden" name="hotel_name" value="<?php echo htmlspecialchars($hname); ?>">
                <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($r_type); ?>">
                <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($r_id); ?>">
                <input type="hidden" name="search_id" value="<?php echo htmlspecialchars($search_id); ?>">
                <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
                <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
                <input type="hidden" name="rooms" value="<?php echo (int)$rooms; ?>">
                <input type="hidden" name="price" value="<?php echo $total_room_cost; ?>">

                <button type="submit" style="background: #78B722; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%;">
                    <i class="fa-solid fa-wallet"></i> Select & Book
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
