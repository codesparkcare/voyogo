<div style="margin-bottom: 24px;">
    <!-- Query Summary Strip -->
    <div style="background: #09204b; color: #ffffff; border-radius: 12px; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 22px; font-weight: 800;">
                <?php echo htmlspecialchars($search_query['origin']); ?>
                <i class="fa-solid fa-plane" style="font-size: 16px; margin: 0 10px; color: #78B722;"></i>
                <?php echo htmlspecialchars($search_query['destination']); ?>
            </div>
            <div style="font-size: 13.5px; color: #cbd5e1; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 20px;">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('D, d M Y', strtotime($search_query['depart_date'])); ?></span> &bull;
                <span><i class="fa-solid fa-user"></i> <?php echo (int)$search_query['adults']; ?> Pax</span> &bull;
                <span><?php echo htmlspecialchars($search_query['cabin']); ?></span>
            </div>
        </div>
        <a href="<?php echo site_url('franchise/flight'); ?>" style="background: rgba(255,255,255,0.15); color: #ffffff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Modify Search
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 260px 1fr; gap: 24px; align-items: start;">

    <!-- Left Filter Sidebar -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <i class="fa-solid fa-filter"></i> Filter Flights
        </h3>

        <div style="margin-bottom: 20px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Stops</div>
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 6px; cursor: pointer;">
                <input type="checkbox" checked style="accent-color: #78B722;"> Non Stop
            </label>
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer;">
                <input type="checkbox" checked style="accent-color: #78B722;"> 1 Stop
            </label>
        </div>

        <div style="margin-bottom: 20px;">
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Airlines</div>
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 6px; cursor: pointer;">
                <input type="checkbox" checked style="accent-color: #78B722;"> IndiGo
            </label>
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; margin-bottom: 6px; cursor: pointer;">
                <input type="checkbox" checked style="accent-color: #78B722;"> Air India
            </label>
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer;">
                <input type="checkbox" checked style="accent-color: #78B722;"> Akasa Air
            </label>
        </div>

        <div>
            <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Fare Type</div>
            <span style="display: inline-block; background: #f0fdf4; color: #166534; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px;">B2B Special Net Fares</span>
        </div>
    </div>

    <!-- Flight Results List -->
    <div>
        <?php 
        // If API returned empty array, supply sample B2B flight schedules for smooth booking demo
        if (empty($flights)) {
            $flights = array(
                array(
                    'id'            => 'FL_101',
                    'airline'       => 'IndiGo',
                    'airline_code'  => '6E',
                    'flight_number' => '6E-2041',
                    'origin'        => $search_query['origin'],
                    'destination'   => $search_query['destination'],
                    'departure'     => date('Y-m-d 06:15:00', strtotime($search_query['depart_date'])),
                    'arrival'       => date('Y-m-d 08:30:00', strtotime($search_query['depart_date'])),
                    'duration'      => '2h 15m',
                    'stops'         => 'Non-stop',
                    'base_fare'     => 4200.00,
                    'tax'           => 850.00,
                    'total_fare'    => 5050.00
                ),
                array(
                    'id'            => 'FL_102',
                    'airline'       => 'Air India',
                    'airline_code'  => 'AI',
                    'flight_number' => 'AI-805',
                    'origin'        => $search_query['origin'],
                    'destination'   => $search_query['destination'],
                    'departure'     => date('Y-m-d 11:30:00', strtotime($search_query['depart_date'])),
                    'arrival'       => date('Y-m-d 13:45:00', strtotime($search_query['depart_date'])),
                    'duration'      => '2h 15m',
                    'stops'         => 'Non-stop',
                    'base_fare'     => 4700.00,
                    'tax'           => 900.00,
                    'total_fare'    => 5600.00
                ),
                array(
                    'id'            => 'FL_103',
                    'airline'       => 'Akasa Air',
                    'airline_code'  => 'QP',
                    'flight_number' => 'QP-1120',
                    'origin'        => $search_query['origin'],
                    'destination'   => $search_query['destination'],
                    'departure'     => date('Y-m-d 18:40:00', strtotime($search_query['depart_date'])),
                    'arrival'       => date('Y-m-d 20:55:00', strtotime($search_query['depart_date'])),
                    'duration'      => '2h 15m',
                    'stops'         => 'Non-stop',
                    'base_fare'     => 3950.00,
                    'tax'           => 850.00,
                    'total_fare'    => 4800.00
                )
            );
        }
        ?>

        <div style="font-size: 14px; font-weight: 700; color: #475569; margin-bottom: 14px;">
            Found <?php echo count($flights); ?> flights matching your search
        </div>

        <?php foreach ($flights as $f): 
            $dep_time = date('H:i', strtotime($f['departure'] ?? 'now'));
            $arr_time = date('H:i', strtotime($f['arrival'] ?? 'now'));
            $fare = (float)($f['total_fare'] ?? 5050);
            $pax  = (int)($search_query['adults'] ?? 1);
            $total_cost = $fare * $pax;
        ?>
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 28px; margin-bottom: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.03); display: grid; grid-template-columns: 180px 1fr 180px; gap: 24px; align-items: center;">
            
            <!-- Airline Info -->
            <div>
                <div style="font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plane" style="color: #78B722;"></i>
                    <span><?php echo htmlspecialchars($f['airline'] ?? 'Airline'); ?></span>
                </div>
                <div style="font-size: 12.5px; color: #64748b; margin-top: 2px;">
                    <?php echo htmlspecialchars($f['flight_number'] ?? 'AI-101'); ?> &bull; Economy
                </div>
                <span style="display: inline-block; background: #e0f2fe; color: #0284c7; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; margin-top: 6px;">
                    Voyogo Net Fare
                </span>
            </div>

            <!-- Route Times & Duration -->
            <div style="display: flex; align-items: center; justify-content: space-around; text-align: center;">
                <div>
                    <div style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $dep_time; ?></div>
                    <div style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo htmlspecialchars($f['origin'] ?? $search_query['origin']); ?></div>
                </div>

                <div style="display: flex; flex-direction: column; align-items: center; width: 140px;">
                    <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['duration'] ?? '2h 15m'); ?></span>
                    <div style="width: 100%; height: 2px; background: #cbd5e1; position: relative; margin: 6px 0;">
                        <i class="fa-solid fa-plane" style="position: absolute; top: -6px; left: 50%; transform: translateX(-50%); font-size: 12px; color: #78B722; background: #ffffff; padding: 0 4px;"></i>
                    </div>
                    <span style="font-size: 11px; color: #15803d; font-weight: 600;"><?php echo htmlspecialchars($f['stops'] ?? 'Non-stop'); ?></span>
                </div>

                <div>
                    <div style="font-size: 22px; font-weight: 800; color: #0f172a;"><?php echo $arr_time; ?></div>
                    <div style="font-size: 13px; font-weight: 700; color: #334155;"><?php echo htmlspecialchars($f['destination'] ?? $search_query['destination']); ?></div>
                </div>
            </div>

            <!-- Price & Action -->
            <div style="text-align: right; border-left: 1px solid #f1f5f9; padding-left: 20px;">
                <div style="font-size: 24px; font-weight: 800; color: #09204b;">
                    ₹ <?php echo number_format($fare, 2); ?>
                </div>
                <div style="font-size: 11.5px; color: #64748b; margin-bottom: 12px;">per adult &bull; Net B2B</div>

                <form method="post" action="<?php echo site_url('franchise/flight_review'); ?>">
                    <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($f['id'] ?? 'FL_101'); ?>">
                    <input type="hidden" name="flight_data" value='<?php echo json_encode($f); ?>'>
                    <input type="hidden" name="adults" value="<?php echo (int)($search_query['adults'] ?? 1); ?>">
                    <input type="hidden" name="children" value="<?php echo (int)($search_query['children'] ?? 0); ?>">
                    <input type="hidden" name="infants" value="<?php echo (int)($search_query['infants'] ?? 0); ?>">

                    <button type="submit" style="background: #78B722; color: #ffffff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; transition: background 0.15s;">
                        <i class="fa-solid fa-wallet"></i> Book Flight
                    </button>
                </form>
            </div>

        </div>
        <?php endforeach; ?>

    </div>

</div>
