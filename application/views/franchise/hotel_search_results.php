<div style="margin-bottom: 24px;">
    <!-- Query Strip -->
    <div style="background: #09204b; color: #ffffff; border-radius: 12px; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 22px; font-weight: 800;">
                <i class="fa-solid fa-hotel" style="color: #78B722; margin-right: 8px;"></i>
                Hotels in <?php echo htmlspecialchars($search_query['city']); ?>
            </div>
            <div style="font-size: 13.5px; color: #cbd5e1; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 20px;">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('d M', strtotime($search_query['checkin'])); ?> - <?php echo date('d M Y', strtotime($search_query['checkout'])); ?></span> &bull;
                <span><i class="fa-solid fa-door-closed"></i> <?php echo (int)$search_query['rooms']; ?> Room(s)</span> &bull;
                <span><i class="fa-solid fa-user"></i> <?php echo (int)$search_query['adults']; ?> Adult(s)</span>
            </div>
        </div>
        <a href="<?php echo site_url('franchise/hotel'); ?>" style="background: rgba(255,255,255,0.15); color: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Modify Search
        </a>
    </div>
</div>

<?php 
if (empty($hotels)) {
    // Fallback sample hotels for testing
    $hotels = array(
        array(
            'id'       => 'H_GOA_001',
            'name'     => 'Taj Holiday Village Resort & Spa',
            'rating'   => 5,
            'address'  => 'Candolim, Goa 403515',
            'image'    => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&q=80',
            'minPrice' => 8500.00
        ),
        array(
            'id'       => 'H_GOA_002',
            'name'     => 'Grand Hyatt Goa',
            'rating'   => 5,
            'address'  => 'Bambolim, Goa 403201',
            'image'    => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=500&q=80',
            'minPrice' => 7200.00
        ),
        array(
            'id'       => 'H_GOA_003',
            'name'     => 'Novotel Goa Candolim',
            'rating'   => 4,
            'address'  => 'Pinto Waddo, Candolim, Goa',
            'image'    => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=500&q=80',
            'minPrice' => 4500.00
        )
    );
}
?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 24px;">
    <?php foreach ($hotels as $h): 
        $hid = $h['id'] ?? $h['hotelId'] ?? 'H_001';
        $hname = $h['name'] ?? 'Hotel';
        $hrating = (int)($h['rating'] ?? $h['starRating'] ?? 4);
        $himg = !empty($h['image']) ? $h['image'] : (!empty($h['heroImage']) ? $h['heroImage'] : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&q=80');
        $hprice = (float)($h['minPrice'] ?? $h['price'] ?? 4500);
    ?>
    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
        <div style="height: 190px; position: relative; background: #e2e8f0;">
            <img src="<?php echo htmlspecialchars($himg); ?>" alt="<?php echo htmlspecialchars($hname); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <span style="position: absolute; top: 12px; left: 12px; background: rgba(9, 32, 75, 0.85); backdrop-filter: blur(4px); color: #ffffff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                <?php for($s=0; $s<$hrating; $s++) echo '★'; ?> <?php echo $hrating; ?> Star
            </span>
            <span style="position: absolute; bottom: 12px; right: 12px; background: #78B722; color: #ffffff; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 4px;">
                B2B NET
            </span>
        </div>

        <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 6px;"><?php echo htmlspecialchars($hname); ?></h3>
                <p style="font-size: 12.5px; color: #64748b; margin-bottom: 12px;">
                    <i class="fa-solid fa-location-dot" style="color: #ef4444; margin-right: 4px;"></i>
                    <?php echo htmlspecialchars($h['address'] ?? $search_query['city']); ?>
                </p>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 11px; color: #64748b; display: block;">Starting from</span>
                    <strong style="font-size: 20px; color: #09204b; font-weight: 800;">₹ <?php echo number_format($hprice, 2); ?></strong>
                    <span style="font-size: 11px; color: #64748b;">/ night</span>
                </div>

                <a href="<?php echo site_url('franchise/hotel_detail/' . urlencode($hid) . '?city=' . urlencode($search_query['city']) . '&checkin=' . $search_query['checkin'] . '&checkout=' . $search_query['checkout'] . '&rooms=' . $search_query['rooms'] . '&adults=' . $search_query['adults'] . '&children=' . $search_query['children'] . '&search_id=' . urlencode($search_id)); ?>" style="background: #78B722; color: #ffffff; text-decoration: none; padding: 9px 18px; border-radius: 6px; font-size: 13.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                    <span>View Rooms</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
