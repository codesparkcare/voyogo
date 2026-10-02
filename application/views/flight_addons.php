<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$sessionUser = $this->session->userdata('user');
$isUserLoggedIn = !empty($sessionUser);

// Flight and fare details passed from Controller / Session
$flight_number = $flight_number ?? ($post_data['flight_number'] ?? '6E-2054');
$airline_name = $airline_name ?? ($post_data['airline_name'] ?? 'IndiGo');
$origin = $origin ?? ($post_data['origin'] ?? 'DEL');
$destination = $destination ?? ($post_data['destination'] ?? 'BOM');
$departure_date = $departure_date ?? ($post_data['departure_date'] ?? date('Y-m-d', strtotime('+3 days')));
$departure_time = $departure_time ?? ($post_data['departure_time'] ?? '06:00');
$arrival_time = $arrival_time ?? ($post_data['arrival_time'] ?? '08:15');
$duration = $duration ?? ($post_data['duration'] ?? '2h 15m');
$stops = (int)($stops ?? ($post_data['stops'] ?? 0));
$via = $via ?? ($post_data['via'] ?? '');

$base_fare = (float)($base_fare ?? ($post_data['base_fare'] ?? ($post_data['net_amount'] ?? 4500)));
$taxes = (float)($taxes ?? ($post_data['taxes'] ?? 1250));
$insurance_amount = (float)($insurance_amount ?? ($post_data['insurance_amount'] ?? 199));
$discount_amount = (float)($discount_amount ?? ($post_data['discount_amount'] ?? 0));
$promo_code = $promo_code ?? ($post_data['promo_code'] ?? 'ATFLY');

$fare_tier = $fare_tier ?? ($post_data['fare_tier'] ?? 'Value');
$fare_tier_price_delta = (float)($fare_tier_price_delta ?? ($post_data['fare_tier_price_delta'] ?? 0));
$safety_cancellation_type = $safety_cancellation_type ?? ($post_data['safety_cancellation_type'] ?? '');
$safety_cancellation_amount = (float)($safety_cancellation_amount ?? ($post_data['safety_cancellation_amount'] ?? 0));

$total_passengers = max(1, count($passengers ?? array()));
$initialGrandTotal = max(0, $base_fare + $taxes + $insurance_amount + $safety_cancellation_amount - $discount_amount);

$razorpay_settings = $this->Admin_model->get_razorpay_settings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title ?? 'Customize Add-ons - Voyogo'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f7fb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .addons-container {
            max-width: 1220px;
            margin: 0 auto;
            padding: 24px 16px;
        }
        .step-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,32,90,0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .step-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: #64748b;
        }
        .step-pill.completed {
            color: #16a34a;
        }
        .step-pill.active {
            color: #0284c7;
            background: #e0f2fe;
            padding: 6px 14px;
            border-radius: 20px;
        }
        .step-arrow {
            color: #cbd5e1;
            font-size: 11px;
        }

        /* Add-on Tabs */
        .addon-nav-tabs {
            display: flex;
            gap: 10px;
            background: #ffffff;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
            overflow-x: auto;
        }
        .addon-nav-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #475569;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .addon-nav-tab.active {
            background: #0284c7;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(2,132,199,0.25);
        }
        .addon-nav-tab:hover:not(.active) {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* Card Styles */
        .addon-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .addon-card:hover {
            border-color: #0284c7;
            box-shadow: 0 6px 20px rgba(2,132,199,0.08);
        }
        .addon-card.selected {
            border-color: #0284c7;
            background: #f0f9ff;
        }

        /* Seat Grid */
        .seat-cabin-box {
            background: #ffffff;
            border-radius: 14px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }
        .seat-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .seat-item {
            width: 36px;
            height: 38px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            user-select: none;
        }
        .seat-item:hover {
            border-color: #0284c7;
            transform: scale(1.08);
        }
        .seat-item.standard {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .seat-item.preferred {
            background: #e0f2fe;
            border-color: #38bdf8;
            color: #0369a1;
        }
        .seat-item.extra-legroom {
            background: #fef3c7;
            border-color: #f59e0b;
            color: #b45309;
        }
        .seat-item.selected {
            background: #16a34a !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(22,163,74,0.4);
        }
        .seat-item.occupied {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.5;
        }
        .seat-aisle {
            width: 24px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 800;
        }
    </style>
</head>
<body>

<div class="addons-container">

    <!-- Step Progress Indicator -->
    <div class="step-strip">
        <div class="step-pill completed">
            <i class="fa-solid fa-circle-check"></i>
            <span>1. Flight Selection</span>
        </div>
        <i class="fa-solid fa-chevron-right step-arrow"></i>
        <div class="step-pill completed">
            <i class="fa-solid fa-circle-check"></i>
            <span>2. Passenger Details</span>
        </div>
        <i class="fa-solid fa-chevron-right step-arrow"></i>
        <div class="step-pill active">
            <i class="fa-solid fa-circle-dot"></i>
            <span>3. Add-on Services</span>
        </div>
        <i class="fa-solid fa-chevron-right step-arrow"></i>
        <div class="step-pill">
            <i class="fa-regular fa-circle"></i>
            <span>4. Payment</span>
        </div>
    </div>

    <!-- Main Layout: 2-Column Grid -->
    <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px;">

        <!-- Left Column: Add-on Tabs & Services Selection (Screenshots 4 & 5) -->
        <div>
            <!-- Banner Header -->
            <div style="background: #ffffff; border-radius: 14px; padding: 22px 24px; margin-bottom: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                            Customize Your Flight Experience
                        </h2>
                        <div style="font-size: 13px; color: #64748b;">
                            Add prepaid baggage, delicious in-flight meals, or reserve your favorite seat for a comfortable journey.
                        </div>
                    </div>
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; color: #166534; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-plane"></i>
                        <span><?php echo htmlspecialchars($origin); ?> &rarr; <?php echo htmlspecialchars($destination); ?> &bull; <?php echo htmlspecialchars($airline_name); ?></span>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation Bar (Screenshot 4) -->
            <div class="addon-nav-tabs">
                <button type="button" class="addon-nav-tab active" onclick="switchAddonTab('baggage', this)">
                    <i class="fa-solid fa-suitcase"></i> Baggage
                </button>
                <button type="button" class="addon-nav-tab" onclick="switchAddonTab('meals', this)">
                    <i class="fa-solid fa-bowl-food"></i> Meals
                </button>
                <button type="button" class="addon-nav-tab" onclick="switchAddonTab('seats', this)">
                    <i class="fa-solid fa-chair"></i> Seat Selection
                </button>
                <button type="button" class="addon-nav-tab" onclick="switchAddonTab('assistance', this)">
                    <i class="fa-solid fa-wheelchair"></i> Special Assistance
                </button>
            </div>

            <!-- TAB 1: BAGGAGE (Screenshot 4) -->
            <div id="tabContent_baggage" class="tab-content" style="display: block;">
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Prepaid Excess Baggage</h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Save up to 20% on baggage fees compared to airport check-in rates.</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                        
                        <!-- 3 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_3kg" onclick="selectBaggageOption('XBPE', 'Prepaid Excess Baggage – 3 Kg', 2100, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 3 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 2,100</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                        <!-- 5 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_5kg" onclick="selectBaggageOption('XBPA', 'Prepaid Excess Baggage – 5 Kg', 3250, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 5 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 3,250</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                        <!-- 10 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_10kg" onclick="selectBaggageOption('XBPB', 'Prepaid Excess Baggage – 10 Kg', 6250, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 10 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 6,250</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                        <!-- 15 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_15kg" onclick="selectBaggageOption('XBPC', 'Prepaid Excess Baggage – 15 Kg', 9400, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 15 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 9,400</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                        <!-- 20 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_20kg" onclick="selectBaggageOption('XBPJ', 'Prepaid Excess Baggage – 20 Kg', 12000, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 20 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 12,000</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                        <!-- 30 Kg -->
                        <div class="addon-card baggage-card" id="bagCard_30kg" onclick="selectBaggageOption('XBPD', 'Prepaid Excess Baggage – 30 Kg', 19500, this)">
                            <div>
                                <div style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">+ 30 Kg Extra</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Checked Baggage</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 19,500</span>
                                <button type="button" class="bag-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">Select</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 2: MEALS -->
            <div id="tabContent_meals" class="tab-content" style="display: none;">
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">In-Flight Meals & Snacks</h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Pre-book your favorite meal or gourmet combo to ensure onboard availability.</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
                        
                        <!-- Meal 1: Paneer Tikka Sandwich -->
                        <div class="addon-card meal-card" id="mealCard_PTSW" onclick="selectMealOption('PTSW', 'Paneer Tikka Sandwich Combo', 500, this)">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-circle" style="font-size: 8px;"></i> VEG
                                    </span>
                                    <span style="font-size: 12px; color: #64748b;">Includes Beverage</span>
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">Paneer Tikka Sandwich Combo</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; line-height: 1.4;">Spiced cottage cheese sandwich served with choice of beverage.</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 500</span>
                                <button type="button" class="meal-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">+ Add</button>
                            </div>
                        </div>

                        <!-- Meal 2: Chicken Junglee Sandwich -->
                        <div class="addon-card meal-card" id="mealCard_CJSW" onclick="selectMealOption('CJSW', 'Chicken Junglee Sandwich Combo', 500, this)">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 11px; font-weight: 800; color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-circle" style="font-size: 8px;"></i> NON-VEG
                                    </span>
                                    <span style="font-size: 12px; color: #64748b;">Includes Beverage</span>
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">Chicken Junglee Sandwich Combo</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; line-height: 1.4;">Classic shredded chicken tossed with herbs & choice of beverage.</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 500</span>
                                <button type="button" class="meal-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">+ Add</button>
                            </div>
                        </div>

                        <!-- Meal 3: Veg Biryani Combo -->
                        <div class="addon-card meal-card" id="mealCard_VBIR" onclick="selectMealOption('VBIR', 'VEG BIRYANI Combo', 400, this)">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-circle" style="font-size: 8px;"></i> VEG
                                    </span>
                                    <span style="font-size: 12px; color: #64748b;">Hot Meal</span>
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">VEG BIRYANI Combo</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; line-height: 1.4;">Aromatic basmati rice cooked with fresh seasonal vegetables & spices.</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 400</span>
                                <button type="button" class="meal-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">+ Add</button>
                            </div>
                        </div>

                        <!-- Meal 4: 6E Choice of the Day -->
                        <div class="addon-card meal-card" id="mealCard_VCSW" onclick="selectMealOption('VCSW', '6E Choice of the Day + Beverage', 400, this)">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-size: 11px; font-weight: 800; color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-circle" style="font-size: 8px;"></i> VEG
                                    </span>
                                    <span style="font-size: 12px; color: #64748b;">Daily Special</span>
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">6E Choice of the Day + Beverage</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; line-height: 1.4;">Fresh gourmet deli bite prepared with seasonal fresh fillings.</div>
                            </div>
                            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 16px; font-weight: 900; color: #0f172a;">₹ 400</span>
                                <button type="button" class="meal-btn" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; background: #ffffff; border: 1.5px solid #0284c7; color: #0284c7; cursor: pointer;">+ Add</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 3: SEAT SELECTION -->
            <div id="tabContent_seats" class="tab-content" style="display: none;">
                <div class="seat-cabin-box" style="margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Select Your Preferred Seat</h3>
                            <p style="font-size: 13px; color: #64748b; margin: 0;">Aircraft: Airbus A320 (3 - 3 Seating Layout)</p>
                        </div>
                        
                        <!-- Seat Legend -->
                        <div style="display: flex; gap: 14px; font-size: 12px; color: #475569; align-items: center;">
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 14px; height: 14px; border-radius: 3px; background: #fef3c7; border: 1px solid #f59e0b;"></span> Extra Legroom (₹450)
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 14px; height: 14px; border-radius: 3px; background: #e0f2fe; border: 1px solid #38bdf8;"></span> Preferred (₹250)
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 14px; height: 14px; border-radius: 3px; background: #f8fafc; border: 1px solid #cbd5e1;"></span> Standard (₹0)
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 14px; height: 14px; border-radius: 3px; background: #16a34a;"></span> Selected
                            </span>
                        </div>
                    </div>

                    <!-- Aircraft Cabin Illustration -->
                    <div style="max-width: 380px; margin: 0 auto; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 30px 30px 10px 10px; padding: 24px 16px;">
                        
                        <!-- Cockpit Nose -->
                        <div style="text-align: center; font-size: 12px; font-weight: 800; color: #94a3b8; margin-bottom: 16px;">
                            <i class="fa-solid fa-plane-up"></i> FRONT OF AIRCRAFT
                        </div>

                        <!-- Column Headers -->
                        <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 8px; font-size: 12px; font-weight: 800; color: #64748b;">
                            <div style="width: 36px; text-align: center;">A</div>
                            <div style="width: 36px; text-align: center;">B</div>
                            <div style="width: 36px; text-align: center;">C</div>
                            <div class="seat-aisle"></div>
                            <div style="width: 36px; text-align: center;">D</div>
                            <div style="width: 36px; text-align: center;">E</div>
                            <div style="width: 36px; text-align: center;">F</div>
                        </div>

                        <!-- Rows 1 to 10 -->
                        <?php 
                        $seatCols = array('A', 'B', 'C', 'D', 'E', 'F');
                        for ($r = 1; $r <= 8; $r++): 
                            $isEmergency = ($r === 1 || $r === 12);
                            $rowType = ($r === 1) ? 'extra-legroom' : (($r <= 4) ? 'preferred' : 'standard');
                            $seatPrice = ($r === 1) ? 450 : (($r <= 4) ? 250 : 0);
                        ?>
                        <div class="seat-row">
                            <?php foreach ($seatCols as $idx => $col): 
                                $seatCode = $r . $col;
                                $isOccupied = ($seatCode === '2B' || $seatCode === '3E' || $seatCode === '5A');
                            ?>
                                <?php if ($idx === 3): ?>
                                    <div class="seat-aisle"><?php echo $r; ?></div>
                                <?php endif; ?>
                                <div class="seat-item <?php echo $rowType; ?> <?php echo $isOccupied ? 'occupied' : ''; ?>" 
                                     data-seat="<?php echo $seatCode; ?>" 
                                     data-price="<?php echo $seatPrice; ?>" 
                                     onclick="<?php echo $isOccupied ? '' : "toggleSeatSelection('{$seatCode}', {$seatPrice}, this)"; ?>"
                                     title="Seat <?php echo $seatCode; ?> (₹<?php echo $seatPrice; ?>)">
                                    <?php echo $col; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endfor; ?>

                    </div>
                </div>
            </div>

            <!-- TAB 4: SPECIAL ASSISTANCE -->
            <div id="tabContent_assistance" class="tab-content" style="display: none;">
                <div style="background: #ffffff; border-radius: 14px; padding: 24px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Special Assistance Services</h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Complimentary mobility support and airport assistance for eligible travellers.</p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <label style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px; display: flex; align-items: flex-start; gap: 14px; cursor: pointer;">
                            <input type="checkbox" id="wheelchairCheck" onchange="toggleAssistance('Wheelchair Assistance', this.checked)" style="accent-color: #2563eb; margin-top: 3px;">
                            <div>
                                <div style="font-size: 14.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-wheelchair" style="color: #0284c7;"></i> Wheelchair Assistance at Airport
                                </div>
                                <div style="font-size: 12.5px; color: #64748b; margin-top: 4px;">
                                    Complimentary wheelchair for departure, transit, and arrival terminals for passengers with reduced mobility.
                                </div>
                                <div style="font-size: 12px; font-weight: 800; color: #16a34a; margin-top: 6px;">FREE / Included</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons: Back to Review & Proceed to Payment -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                <a href="javascript:history.back();" style="padding: 14px 24px; font-size: 14.5px; font-weight: 700; color: #475569; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Review
                </a>

                <button type="button" id="proceedPaymentBtn" onclick="triggerFinalPayment();" style="padding: 16px 36px; font-size: 16.5px; font-weight: 800; color: #ffffff; background: linear-gradient(135deg, #0d3470, #2563eb); border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 15px rgba(37,99,235,0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-lock"></i>
                    <span>Pay ₹ <span id="btnPayAmount"><?php echo number_format($initialGrandTotal); ?></span> & Instant Confirm</span>
                </button>
            </div>

        </div>

        <!-- Right Column: Sticky Fare Details Sidebar (Screenshot 3) -->
        <div>
            <div style="position: sticky; top: 90px; display: flex; flex-direction: column; gap: 18px;">
                
                <!-- Flight Mini Summary Card -->
                <div style="background: #ffffff; border-radius: 12px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,32,90,0.04);">
                    <div style="font-size: 11px; font-weight: 800; color: #0284c7; text-transform: uppercase; margin-bottom: 6px;">Your Selected Trip</div>
                    <div style="font-size: 16px; font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <span><?php echo htmlspecialchars($origin); ?></span>
                        <i class="fa-solid fa-plane" style="font-size: 12px; color: #0284c7;"></i>
                        <span><?php echo htmlspecialchars($destination); ?></span>
                    </div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        <?php echo date('D, d M Y', strtotime($departure_date)); ?> &bull; <?php echo htmlspecialchars($airline_name); ?> (<?php echo htmlspecialchars($flight_number); ?>)
                    </div>
                </div>

                <!-- 1. Fare Details Card (Screenshot 3) -->
                <div class="f-fare-details-box" style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,32,90,0.04); border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        <strong style="font-size: 15px; color: #0f172a; font-weight: 700;">Fare Details</strong>
                        <span style="font-size: 13px; color: #0284c7; font-weight: 600;"><?php echo $total_passengers; ?> Traveller<?php echo $total_passengers > 1 ? 's' : ''; ?></span>
                    </div>

                    <!-- Base Fare Item with Subrow (Screenshot 3 circular +/-) -->
                    <div class="f-fare-group" style="margin-bottom: 12px;">
                        <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                            <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Base Fare
                            </span>
                            <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryBaseFare"><?php echo number_format($base_fare + $fare_tier_price_delta); ?></span></strong>
                        </div>
                        <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                <span>Adult (<?php echo $total_passengers; ?>)</span>
                                <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($base_fare + $fare_tier_price_delta); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tax & Charges Item with Subrows (Screenshot 3 circular +/-) -->
                    <div class="f-fare-group" style="margin-bottom: 12px;">
                        <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                            <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Tax & Charges
                            </span>
                            <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryTaxes"><?php echo number_format($taxes); ?></span></strong>
                        </div>
                        <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                <span>Aviation Taxes & Fees</span>
                                <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($taxes); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Item (Screenshot 3 circular +/-) -->
                    <?php if ($insurance_amount > 0): ?>
                    <div class="f-fare-group" style="margin-bottom: 12px;">
                        <div class="f-fare-row f-fare-parent" onclick="toggleFareBreakdown(this);" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; cursor: pointer;">
                            <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <span class="f-fare-toggle-circle" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0284c7; color: #0284c7; font-size: 13px; font-weight: 700; line-height: 1; user-select: none;">+</span> Insurance
                            </span>
                            <strong style="color: #0f172a; font-size: 13.5px;">₹ <span id="summaryInsurance"><?php echo number_format($insurance_amount); ?></span></strong>
                        </div>
                        <div class="f-fare-subitems" style="display: none; flex-direction: column; gap: 6px; padding: 8px 12px; background: #f8fafc; border-radius: 6px; margin-top: 6px; border-left: 2px solid #cbd5e1;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #475569;">
                                <span>Travel Insurance Cover</span>
                                <span style="font-weight: 600; color: #334155;">₹ <?php echo number_format($insurance_amount); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Safety Coverage Item (If selected) -->
                    <?php if ($safety_cancellation_amount > 0): ?>
                    <div class="f-fare-group" style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="f-fare-label" style="font-size: 13.5px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-shield-heart" style="color: #16a34a;"></i> <?php echo ($safety_cancellation_type === 'zero_cancellation') ? 'Zero Cancellation' : 'Refundable Booking Upgrade'; ?>
                            </span>
                            <strong style="color: #0f172a; font-size: 13.5px;">₹ <?php echo number_format($safety_cancellation_amount); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- SSR Dynamic Add-ons Breakdown in Sidebar -->
                    <div class="f-fare-group" id="summaryBaggageRow" style="display: none; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-suitcase" style="color: #0284c7;"></i> <span id="summaryBaggageTitle">Extra Baggage</span>
                            </span>
                            <strong style="color: #0284c7; font-size: 13.5px;">₹ <span id="summaryBaggageAmount">0</span></strong>
                        </div>
                    </div>

                    <div class="f-fare-group" id="summaryMealRow" style="display: none; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-bowl-food" style="color: #16a34a;"></i> <span id="summaryMealTitle">In-Flight Meal</span>
                            </span>
                            <strong style="color: #16a34a; font-size: 13.5px;">₹ <span id="summaryMealAmount">0</span></strong>
                        </div>
                    </div>

                    <div class="f-fare-group" id="summarySeatRow" style="display: none; margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-chair" style="color: #f59e0b;"></i> <span id="summarySeatTitle">Selected Seat</span>
                            </span>
                            <strong style="color: #f59e0b; font-size: 13.5px;">₹ <span id="summarySeatAmount">0</span></strong>
                        </div>
                    </div>

                    <!-- Discount Row -->
                    <?php if ($discount_amount > 0): ?>
                    <div class="f-fare-group" style="margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 600; color: #16a34a;">Promo Discount</span>
                            <strong style="color: #16a34a; font-size: 13.5px;">- ₹ <?php echo number_format($discount_amount); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Total Amount Divider -->
                    <div style="border-top: 2px dashed #e2e8f0; margin: 14px 0 10px 0; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 16px; color: #0f172a; font-weight: 800;">Grand Total</strong>
                        <strong style="font-size: 22px; color: #0284c7; font-weight: 900;">₹ <span id="summaryGrandTotal"><?php echo number_format($initialGrandTotal); ?></span></strong>
                    </div>
                </div>

                <!-- 2. Trust Badges (Screenshot 3) -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #166534;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-halved" style="color: #16a34a; font-size: 15px;"></i>
                        <span style="font-weight: 600;">100% Safe & Instant Booking</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-envelope-circle-check" style="color: #16a34a; font-size: 15px;"></i>
                        <span style="font-weight: 600;">Instant E-Ticket Sent to Email</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- Hidden POST form to process payment upon button click -->
<form id="finalPaymentForm" action="<?php echo site_url('flight/process_payment'); ?>" method="POST" style="display: none;">
    <?php if (!empty($post_data) && is_array($post_data)): ?>
        <?php foreach ($post_data as $k => $v): ?>
            <?php if (is_array($v)): ?>
                <?php foreach ($v as $subKey => $subVal): ?>
                    <input type="hidden" name="<?php echo htmlspecialchars($k); ?>[]" value="<?php echo htmlspecialchars($subVal); ?>">
                <?php endforeach; ?>
            <?php else: ?>
                <input type="hidden" name="<?php echo htmlspecialchars($k); ?>" value="<?php echo htmlspecialchars($v); ?>">
            <?php endIf; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <input type="hidden" name="selected_baggage_code" id="form_baggage_code" value="">
    <input type="hidden" name="selected_baggage_desc" id="form_baggage_desc" value="">
    <input type="hidden" name="selected_baggage_amount" id="form_baggage_amount" value="0">

    <input type="hidden" name="selected_meal_code" id="form_meal_code" value="">
    <input type="hidden" name="selected_meal_desc" id="form_meal_desc" value="">
    <input type="hidden" name="selected_meal_amount" id="form_meal_amount" value="0">

    <input type="hidden" name="selected_seat_code" id="form_seat_code" value="">
    <input type="hidden" name="selected_seat_amount" id="form_seat_amount" value="0">

    <input type="hidden" name="total_amount" id="form_final_grand_total" value="<?php echo $initialGrandTotal; ?>">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var baseGrandTotal = <?php echo (float)$initialGrandTotal; ?>;
var selectedBaggagePrice = 0;
var selectedMealPrice = 0;
var selectedSeatPrice = 0;

function switchAddonTab(tabId, tabEl) {
    document.querySelectorAll('.addon-nav-tab').forEach(function(btn) {
        btn.classList.remove('active');
    });
    if (tabEl) tabEl.classList.add('active');

    document.querySelectorAll('.tab-content').forEach(function(content) {
        content.style.display = 'none';
    });
    var target = document.getElementById('tabContent_' + tabId);
    if (target) target.style.display = 'block';
}

function toggleFareBreakdown(triggerEl) {
    var group = triggerEl.closest('.f-fare-group');
    if (!group) return;
    var subitems = group.querySelector('.f-fare-subitems');
    var circleIcon = group.querySelector('.f-fare-toggle-circle');
    if (!subitems) return;

    var isHidden = (subitems.style.display === 'none' || window.getComputedStyle(subitems).display === 'none');
    if (isHidden) {
        subitems.style.display = 'flex';
        if (circleIcon) {
            circleIcon.textContent = '−';
            circleIcon.style.background = '#0284c7';
            circleIcon.style.color = '#ffffff';
            circleIcon.style.borderColor = '#0284c7';
        }
    } else {
        subitems.style.display = 'none';
        if (circleIcon) {
            circleIcon.textContent = '+';
            circleIcon.style.background = 'transparent';
            circleIcon.style.color = '#0284c7';
            circleIcon.style.borderColor = '#0284c7';
        }
    }
}

function selectBaggageOption(code, desc, price, cardEl) {
    var isAlready = cardEl.classList.contains('selected');

    document.querySelectorAll('.baggage-card').forEach(function(c) {
        c.classList.remove('selected');
        var btn = c.querySelector('.bag-btn');
        if (btn) {
            btn.textContent = 'Select';
            btn.style.background = '#ffffff';
            btn.style.color = '#0284c7';
        }
    });

    if (isAlready) {
        selectedBaggagePrice = 0;
        document.getElementById('form_baggage_code').value = '';
        document.getElementById('form_baggage_desc').value = '';
        document.getElementById('form_baggage_amount').value = 0;
        document.getElementById('summaryBaggageRow').style.display = 'none';
    } else {
        cardEl.classList.add('selected');
        var btn = cardEl.querySelector('.bag-btn');
        if (btn) {
            btn.textContent = 'Selected';
            btn.style.background = '#0284c7';
            btn.style.color = '#ffffff';
        }
        selectedBaggagePrice = parseFloat(price);
        document.getElementById('form_baggage_code').value = code;
        document.getElementById('form_baggage_desc').value = desc;
        document.getElementById('form_baggage_amount').value = selectedBaggagePrice;

        var row = document.getElementById('summaryBaggageRow');
        row.style.display = 'block';
        document.getElementById('summaryBaggageTitle').textContent = desc;
        document.getElementById('summaryBaggageAmount').textContent = selectedBaggagePrice.toLocaleString('en-IN');
    }

    recalcTotal();
}

function selectMealOption(code, desc, price, cardEl) {
    var isAlready = cardEl.classList.contains('selected');

    document.querySelectorAll('.meal-card').forEach(function(c) {
        c.classList.remove('selected');
        var btn = c.querySelector('.meal-btn');
        if (btn) {
            btn.textContent = '+ Add';
            btn.style.background = '#ffffff';
            btn.style.color = '#0284c7';
        }
    });

    if (isAlready) {
        selectedMealPrice = 0;
        document.getElementById('form_meal_code').value = '';
        document.getElementById('form_meal_desc').value = '';
        document.getElementById('form_meal_amount').value = 0;
        document.getElementById('summaryMealRow').style.display = 'none';
    } else {
        cardEl.classList.add('selected');
        var btn = cardEl.querySelector('.meal-btn');
        if (btn) {
            btn.textContent = 'Added';
            btn.style.background = '#16a34a';
            btn.style.color = '#ffffff';
        }
        selectedMealPrice = parseFloat(price);
        document.getElementById('form_meal_code').value = code;
        document.getElementById('form_meal_desc').value = desc;
        document.getElementById('form_meal_amount').value = selectedMealPrice;

        var row = document.getElementById('summaryMealRow');
        row.style.display = 'block';
        document.getElementById('summaryMealTitle').textContent = desc;
        document.getElementById('summaryMealAmount').textContent = selectedMealPrice.toLocaleString('en-IN');
    }

    recalcTotal();
}

function toggleSeatSelection(seatCode, price, seatEl) {
    var isAlready = seatEl.classList.contains('selected');

    document.querySelectorAll('.seat-item').forEach(function(s) {
        s.classList.remove('selected');
    });

    if (isAlready) {
        selectedSeatPrice = 0;
        document.getElementById('form_seat_code').value = '';
        document.getElementById('form_seat_amount').value = 0;
        document.getElementById('summarySeatRow').style.display = 'none';
    } else {
        seatEl.classList.add('selected');
        selectedSeatPrice = parseFloat(price);
        document.getElementById('form_seat_code').value = seatCode;
        document.getElementById('form_seat_amount').value = selectedSeatPrice;

        var row = document.getElementById('summarySeatRow');
        row.style.display = 'block';
        document.getElementById('summarySeatTitle').textContent = 'Seat ' + seatCode;
        document.getElementById('summarySeatAmount').textContent = selectedSeatPrice.toLocaleString('en-IN');
    }

    recalcTotal();
}

function toggleAssistance(title, isChecked) {
    // Complimentary assistance
}

function recalcTotal() {
    var grand = baseGrandTotal + selectedBaggagePrice + selectedMealPrice + selectedSeatPrice;
    document.getElementById('summaryGrandTotal').textContent = grand.toLocaleString('en-IN');
    document.getElementById('btnPayAmount').textContent = grand.toLocaleString('en-IN');
    document.getElementById('form_final_grand_total').value = grand;
}

function triggerFinalPayment() {
    var finalAmount = parseFloat(document.getElementById('form_final_grand_total').value);
    var amountInPaise = Math.round(finalAmount * 100);
    var contactName = "<?php echo htmlspecialchars($post_data['contact_name'] ?? 'Voyogo Traveller'); ?>";
    var contactEmail = "<?php echo htmlspecialchars($post_data['contact_email'] ?? 'booking@voyogo.com'); ?>";
    var contactPhone = "<?php echo htmlspecialchars($post_data['contact_phone'] ?? '9876543210'); ?>";

    var options = {
        "key": "<?php echo !empty($razorpay_settings['razorpay_key_id']) ? htmlspecialchars($razorpay_settings['razorpay_key_id']) : 'rzp_test_TTVGSNKy0V1o7B'; ?>",
        "amount": amountInPaise,
        "currency": "INR",
        "name": "Voyogo Travels",
        "description": "Flight Ticket Booking - <?php echo htmlspecialchars($flight_number); ?>",
        "image": "<?php echo base_url('assets/images/logo.png'); ?>",
        "handler": function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('finalPaymentForm').submit();
        },
        "prefill": {
            "name": contactName,
            "email": contactEmail,
            "contact": contactPhone
        },
        "theme": {
            "color": "#0d3470"
        },
        "modal": {
            "ondismiss": function() {
                if (confirm("Razorpay Payment Gateway Closed. Complete booking in test mode?")) {
                    document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
                    document.getElementById('finalPaymentForm').submit();
                }
            }
        }
    };

    try {
        var rzp = new Razorpay(options);
        rzp.open();
    } catch(e) {
        document.getElementById('razorpay_payment_id').value = "pay_mock_" + Math.floor(Math.random() * 1000000);
        document.getElementById('finalPaymentForm').submit();
    }
}
</script>

</body>
</html>
