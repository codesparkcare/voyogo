<!-- Main Wrapper -->
<div style="background-color: #f5f7fa; padding-bottom: 90px;">
    
    <style>
    /* Modify Search Modal Overlay & Styling */
    .modify-search-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.72);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 99999;
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
        overflow-y: auto;
    }
    .modify-search-overlay.active {
        display: flex !important;
        animation: fadeInModal 0.25s ease-out forwards;
    }
    @keyframes fadeInModal {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }
    .modify-search-modal {
        background: linear-gradient(90deg, #1ea5f2 0%, rgba(30, 165, 242, 0.96) 32%, rgba(30, 165, 242, 0.65) 65%, rgba(30, 165, 242, 0.15) 100%), 
                    url('<?php echo base_url("assets/images/modify-search-bg.jpg"); ?>') right center / cover no-repeat;
        border-radius: 22px;
        padding: 24px 28px 26px 28px;
        width: 100%;
        max-width: 1140px;
        box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.45);
        position: relative;
        box-sizing: border-box;
    }
    .ms-close-btn {
        position: absolute;
        top: -14px;
        right: -14px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 16px;
        color: #334155;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        transition: all 0.2s ease;
        z-index: 100;
    }
    .ms-close-btn:hover {
        background: #ff3b30;
        border-color: #ff3b30;
        color: #ffffff;
        transform: scale(1.1);
    }
    .ms-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        position: relative;
    }
    .ms-trip-tabs {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .ms-trip-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 18px;
        background: #ffffff;
        border: none;
        border-radius: 30px;
        font-size: 13.5px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        transition: all 0.2s ease;
        user-select: none;
    }
    .ms-trip-tab:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
    }
    .ms-trip-tab input {
        display: none;
    }
    .ms-radio-indicator {
        width: 15px;
        height: 15px;
        border: 2px solid #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .ms-trip-tab.active .ms-radio-indicator,
    .ms-trip-tab:has(input:checked) .ms-radio-indicator {
        border-color: #ff3b30;
    }
    .ms-radio-dot {
        width: 7px;
        height: 7px;
        background: #ff3b30;
        border-radius: 50%;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s;
    }
    .ms-trip-tab.active .ms-radio-dot,
    .ms-trip-tab:has(input:checked) .ms-radio-dot {
        opacity: 1;
        transform: scale(1);
    }
    .ms-header-title {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        font-family: var(--font-heading);
        letter-spacing: -0.2px;
    }

    /* Inner White Card */
    .ms-card-inner {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
        overflow: visible;
        position: relative;
    }
    .ms-form-grid {
        display: grid;
        grid-template-columns: 1.8fr auto 1.8fr 1.3fr 1.3fr 1.5fr auto;
        gap: 0;
        align-items: center;
        padding: 6px 10px;
    }
    .ms-field {
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        border-right: 1px solid #f1f5f9;
        min-height: 64px;
        box-sizing: border-box;
    }
    .ms-field:last-of-type {
        border-right: none;
    }
    .ms-field-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 700;
        display: flex;
        align-items: center;
        margin-bottom: 2px;
        user-select: none;
    }
    .ms-chevron {
        font-size: 9px;
        color: #94a3b8;
        margin-left: 4px;
    }
    .ms-city-input {
        border: none;
        outline: none;
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        background: transparent;
        width: 100%;
        padding: 0;
        font-family: inherit;
        line-height: 1.2;
    }
    .ms-field-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ms-swap-btn {
        background: #0ea5e9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #ffffff;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(14, 165, 233, 0.45);
        transition: all 0.25s ease;
        margin: 0 -18px;
        z-index: 5;
    }
    .ms-swap-btn:hover {
        background: #0284c7;
        transform: rotate(180deg) scale(1.05);
    }
    .ms-field-to {
        padding-left: 26px;
    }
    .ms-field-date {
        position: relative;
        cursor: pointer;
    }
    .ms-date-value {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .ms-date-native {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .ms-clear-return {
        cursor: pointer;
        transition: color 0.2s;
    }
    .ms-clear-return:hover {
        color: #ef4444 !important;
    }
    .ms-field-pax {
        cursor: pointer;
    }
    .ms-traveller-display {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .ms-action-wrap {
        padding: 6px 8px 6px 12px;
        display: flex;
        align-items: center;
    }
    .ms-search-btn {
        background: #ff3b30;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 14px 26px;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.5px;
        cursor: pointer;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(255, 59, 48, 0.4);
        transition: all 0.2s ease;
        font-family: inherit;
    }
    .ms-search-btn:hover {
        background: #e0281c;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255, 59, 48, 0.5);
    }
    .ms-traveller-popup {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        min-width: 260px;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 14px;
        z-index: 1000;
        margin-top: 6px;
    }
    .ms-pax-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .ms-pax-row small { display: block; color: #94a3b8; font-size: 10px; }
    .ms-pax-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ms-pax-controls button {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        font-weight: 700;
        cursor: pointer;
    }
    .ms-pax-controls button:hover {
        background: #0d3470;
        color: #fff;
        border-color: #0d3470;
    }
    .ms-pax-done {
        width: 100%;
        background: #0d3470;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px;
        font-weight: 700;
        margin-top: 10px;
        cursor: pointer;
    }
    .ms-bottom-bar {
        border-top: 1px solid #f1f5f9;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
        background: #ffffff;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
    }
    .ms-bottom-bar.is-multicity .ms-fare-direct {
        display: none !important;
    }
    .ms-fare-checkbox {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        user-select: none;
    }
    .ms-fare-checkbox input {
        width: 15px;
        height: 15px;
        border-radius: 4px;
        accent-color: #0ea5e9;
        cursor: pointer;
    }

    /* Dual Month Interactive Calendar Popup */
    .ms-calendar-popup {
        display: none;
        position: absolute;
        top: 36px;
        left: 42%;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22);
        border: 1px solid #cbd5e1;
        padding: 0;
        z-index: 999999;
        width: 590px;
        max-width: 96vw;
        box-sizing: border-box;
        animation: fadeInModal 0.18s ease-out forwards;
        overflow: hidden;
    }
    .ms-calendar-popup.open {
        display: block !important;
    }
    .ms-cal-header-tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        margin-bottom: 0;
    }
    .ms-cal-tab {
        flex: 1;
        padding: 8px 18px 10px 18px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        border-bottom: 3px solid transparent;
        transition: all 0.15s ease;
        position: relative;
    }
    .ms-cal-tab:first-child {
        border-right: 1px solid #e2e8f0;
        border-top-left-radius: 14px;
    }
    .ms-cal-tab:last-child {
        border-top-right-radius: 14px;
    }
    .ms-cal-tab.active {
        border-bottom-color: #ff3b30;
    }
    .ms-cal-tab-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .ms-cal-tab-date {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ms-cal-clear-ret {
        font-size: 13px;
        color: #94a3b8;
        cursor: pointer;
        transition: color 0.15s;
    }
    .ms-cal-clear-ret:hover {
        color: #ef4444;
    }
    .ms-cal-months-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        padding: 14px 18px 6px 18px;
        gap: 0;
    }
    .ms-cal-month-box:first-child {
        border-right: 1px solid #f1f5f9;
        padding-right: 16px;
    }
    .ms-cal-month-box:last-child {
        padding-left: 16px;
    }
    .ms-cal-month-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        min-height: 28px;
    }
    .ms-cal-month-title {
        font-size: 13px;
        font-weight: 800;
        color: #ea580c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        flex: 1;
    }
    .ms-cal-nav-btn {
        background: transparent;
        border: none;
        color: #475569;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2px 6px;
        transition: all 0.2s;
    }
    .ms-cal-nav-btn:hover:not(:disabled) {
        color: #0ea5e9;
        transform: scale(1.15);
    }
    .ms-cal-nav-btn:disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }
    .ms-cal-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .ms-cal-table th {
        font-size: 11px;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
        padding-bottom: 6px;
    }
    .ms-cal-table td {
        padding: 1.5px 1px;
        text-align: center;
        height: 38px;
    }
    .ms-cal-day-btn {
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1px 0;
        font-family: inherit;
        transition: background 0.15s, color 0.15s;
        box-sizing: border-box;
    }
    .ms-cal-day-num {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
    }
    .ms-cal-table tr td:first-child .ms-cal-day-num {
        color: #dc2626;
    }
    .ms-cal-day-fare {
        font-size: 9px;
        font-weight: 600;
        color: #64748b;
        line-height: 1;
        margin-top: 2px;
    }
    .ms-cal-day-fare.lowest {
        color: #16a34a !important;
        font-weight: 800;
    }
    .ms-cal-day-btn:hover:not(:disabled) {
        background: #e0f2fe;
    }
    .ms-cal-day-btn.disabled {
        cursor: default;
    }
    .ms-cal-day-btn.disabled .ms-cal-day-num {
        color: #94a3b8;
    }
    .ms-cal-day-btn.is-today {
        background: #e2e8f0;
        border-radius: 4px;
    }
    .ms-cal-day-btn.selected-dep,
    .ms-cal-day-btn.selected-ret {
        background: #0d3470 !important;
        color: #ffffff !important;
        border-radius: 4px;
    }
    .ms-cal-day-btn.selected-dep .ms-cal-day-num,
    .ms-cal-day-btn.selected-ret .ms-cal-day-num,
    .ms-cal-day-btn.selected-dep .ms-cal-day-fare,
    .ms-cal-day-btn.selected-ret .ms-cal-day-fare {
        color: #ffffff !important;
    }
    .ms-cal-day-btn.in-range {
        background: #dbeafe !important;
        border-radius: 0;
    }
    .ms-cal-day-btn.in-range .ms-cal-day-num {
        color: #1e3a8a;
    }
    .ms-cal-bottom-note {
        text-align: right;
        font-size: 11px;
        color: #ef4444;
        font-weight: 600;
        padding: 4px 18px 8px 18px;
    }

    /* Multi-City Form Grid */
    .ms-multicity-grid {
        display: none;
    }
    .ms-multicity-grid.active {
        display: block !important;
    }
    .ms-multi-row {
        display: grid;
        grid-template-columns: 1.8fr 1.8fr 1.3fr 1.6fr;
        gap: 0;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding: 4px 10px;
        position: relative;
    }
    .ms-multi-row:last-child {
        border-bottom: none;
    }
    .ms-multi-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: flex-end;
        padding: 8px 14px;
        height: 100%;
        box-sizing: border-box;
    }
    .ms-btn-add-city {
        background: #ffffff;
        color: #0099ff;
        border: 1.5px solid #0099ff;
        border-radius: 6px;
        padding: 11px 18px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .ms-btn-add-city:hover {
        background: #f0f9ff;
        border-color: #0284c7;
        color: #0284c7;
    }
    .ms-remove-leg-btn {
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        background: #fee2e2;
        color: #ef4444;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 11px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        z-index: 10;
    }
    .ms-remove-leg-btn:hover {
        background: #ef4444;
        color: #ffffff;
    }

    @media (max-width: 992px) {
        .modify-search-modal {
            padding: 20px 16px;
        }
        .ms-form-grid, .ms-multi-row {
            grid-template-columns: 1fr;
            gap: 8px;
            padding: 10px;
        }
        .ms-field {
            border-right: none;
            border-bottom: 1px solid #f1f5f9;
            padding: 8px 10px;
        }
        .ms-swap-btn {
            margin: 4px auto;
        }
        .ms-field-to {
            padding-left: 10px;
        }
        .ms-search-btn {
            width: 100%;
            justify-content: center;
        }
        .ms-header-title {
            display: none;
        }
        .ms-close-btn {
            top: 10px;
            right: 10px;
        }
        .ms-calendar-popup {
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 95% !important;
        }
        .ms-cal-months-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Date Carousel Styles */
    .date-carousel-container {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    .date-arrow-btn {
        background: #f8fafc;
        border: none;
        width: 44px;
        height: 64px;
        color: #0d3470;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .date-arrow-btn:hover {
        background: #e2e8f0;
    }
    .date-carousel-items {
        display: flex;
        flex: 1;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .date-carousel-items::-webkit-scrollbar { display: none; }
    .date-pill {
        flex: 1;
        min-width: 105px;
        padding: 10px 8px;
        text-align: center;
        border-right: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        gap: 3px;
        align-items: center;
        justify-content: center;
    }
    .date-pill:hover {
        background: #f8fafc;
    }
    .date-pill.active {
        background: #eff6ff;
        border-bottom: 3px solid #2563eb;
    }
    .date-pill.active strong {
        color: #16a34a !important;
    }
    .date-pill span {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }
    .date-pill strong {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
    }
    .date-arrow-btn.disabled, .date-arrow-btn:disabled {
        opacity: 0.25 !important;
        cursor: not-allowed !important;
        pointer-events: none;
    }
    .date-pill.disabled-date {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        background: #f8fafc !important;
    }
    @keyframes pulsePlane {
        0%, 100% { transform: scale(1) rotate(-35deg); }
        50% { transform: scale(1.15) rotate(-35deg); }
    }
    @keyframes dateProgress {
        0% { left: -50%; }
        100% { left: 100%; }
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Date Search Loading Overlay */
    #dateLoadingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .dlo-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 32px 36px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        text-align: center;
        max-width: 420px;
        width: 90%;
        animation: fadeInScale 0.25s ease-out forwards;
    }
    .dlo-plane-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 16px;
        animation: pulsePlane 1.4s ease-in-out infinite;
    }
    .dlo-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .dlo-route {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 18px;
    }
    .dlo-bar-wrap {
        width: 100%;
        height: 4px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }
    .dlo-bar {
        position: absolute;
        top: 0;
        left: 0;
        width: 45%;
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #38bdf8);
        border-radius: 4px;
        animation: dateProgress 1.2s infinite ease-in-out;
    }

    /* Sort Bar Styles */
    .sorting-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 12px 0 6px 0;
    }
    .sort-tabs {
        display: flex;
        gap: 8px;
    }
    .sort-tab {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .sort-tab:hover {
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .sort-tab.active {
        background: #0d3470;
        color: #fff;
        border-color: #0d3470;
    }
    .sort-tab.active i {
        color: #fff !important;
    }

    /* Sidebar Filters - High Visibility Black Text & Crisp Icons */
    .filters-sidebar {
        background: #ffffff;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        height: fit-content;
    }
    .filter-heading {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #000000 !important;
    }
    .filter-reset-btn {
        background: none;
        border: none;
        color: #dc2626 !important;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.15s;
    }
    .filter-reset-btn:hover {
        color: #b91c1c !important;
        text-decoration: underline;
    }
    .filter-section {
        padding: 16px 20px;
        border-bottom: 1.5px solid #e2e8f0;
    }
    .filter-section:last-child {
        border-bottom: none;
    }
    .filter-title {
        font-size: 15px !important;
        font-weight: 800 !important;
        margin-bottom: 12px !important;
        color: #000000 !important;
        letter-spacing: -0.2px;
    }
    .filter-subtitle {
        font-size: 12.5px !important;
        font-weight: 700 !important;
        color: #000000 !important;
        margin-bottom: 10px !important;
    }
    .stops-grid, .time-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .stop-box {
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px 8px;
        text-align: center;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: #ffffff;
        transition: all 0.15s ease;
        user-select: none;
    }
    .stop-box:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .stop-name {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #000000 !important;
    }
    .stop-price {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #000000 !important;
    }
    .stop-box.active {
        border-color: #0d3470 !important;
        background: #eff6ff !important;
    }
    .stop-box.active .stop-name {
        color: #0d3470 !important;
        font-weight: 800 !important;
    }
    .stop-box.active .stop-price {
        color: #0d3470 !important;
        font-weight: 700 !important;
    }
    .time-box {
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px 8px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #000000 !important;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .time-box:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }
    .time-box i {
        font-size: 15px !important;
        color: #000000 !important;
        flex-shrink: 0;
    }
    .time-box.active {
        border-color: #0d3470 !important;
        background: #eff6ff !important;
        color: #0d3470 !important;
        font-weight: 800 !important;
    }
    .time-box.active i {
        color: #0d3470 !important;
    }
    .airline-filter-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 11px;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        transition: background 0.15s;
    }
    .airline-filter-row:hover {
        background: #f8fafc;
    }
    .airline-filter-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #000000 !important;
    }
    .airline-filter-info span {
        color: #000000 !important;
        font-weight: 700 !important;
    }
    .airline-filter-logo {
        width: 22px;
        height: 22px;
        object-fit: contain;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .airline-filter-price {
        font-size: 13px !important;
        color: #000000 !important;
        font-weight: 800 !important;
        margin-right: 4px;
    }
    .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #000000 !important;
    }
    .custom-checkbox span {
        color: #000000 !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
    }
    .price-range-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 13px !important;
        font-weight: 800 !important;
        color: #000000 !important;
    }
    .price-range-labels span, #priceRangeMax {
        color: #000000 !important;
        font-weight: 800 !important;
        font-size: 13px !important;
    }
    .no-filter-match {
        display: none;
        padding: 40px;
        text-align: center;
        background: #fff;
        border-radius: 10px;
        border: 1px dashed #cbd5e1;
        margin-top: 15px;
    }
    </style>
    
    <!-- Top Search Header Box -->
    <div class="search-header-container">
        <div class="container">
            <div class="search-header-box">
                <div class="sh-block">
                    <span class="sh-label">From</span>
                    <strong class="sh-title"><?php echo htmlspecialchars($search_query['from']); ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['from_code']); ?>, Airport</span>
                </div>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">To</span>
                    <strong class="sh-title"><?php echo htmlspecialchars($search_query['to']); ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['to_code']); ?>, Airport</span>
                </div>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Departure</span>
                    <strong class="sh-title"><?php echo date('d M\'y', strtotime($search_query['date'])); ?></strong>
                    <span class="sh-sub"><?php echo date('l', strtotime($search_query['date'])); ?></span>
                </div>
                <?php if (!empty($is_roundtrip) && !empty($search_query['return_date'])): ?>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Return</span>
                    <strong class="sh-title" style="color: #2563eb;"><?php echo date('d M\'y', strtotime($search_query['return_date'])); ?></strong>
                    <span class="sh-sub"><?php echo date('l', strtotime($search_query['return_date'])); ?></span>
                </div>
                <?php endif; ?>
                <div class="sh-divider"></div>
                <div class="sh-block">
                    <span class="sh-label">Travellers & Class</span>
                    <?php 
                        $total_travelers = ($search_query['adults'] ?? 1) + ($search_query['children'] ?? 0) + ($search_query['infants'] ?? 0);
                    ?>
                    <strong class="sh-title"><?php echo sprintf('%02d', $total_travelers); ?> Traveller<?php echo $total_travelers > 1 ? 's' : ''; ?></strong>
                    <span class="sh-sub"><?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?></span>
                </div>
                <div class="sh-action">
                    <button type="button" class="btn-modify" id="openModifySearchBtn">MODIFY SEARCH <i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modify Search Popup Modal Overlay -->
    <div class="modify-search-overlay" id="modifySearchOverlay">
        <div class="modify-search-modal" id="modifySearchModal">
            <button type="button" class="ms-close-btn" id="closeModifySearchBtn" title="Close"><i class="fa-solid fa-xmark"></i></button>
            
            <!-- Top Bar: Trip Type Pills & Title -->
            <div class="ms-top-bar">
                <div class="ms-trip-tabs">
                    <label class="ms-trip-tab <?php echo empty($is_roundtrip) ? 'active' : ''; ?>" id="tabOneway">
                        <input type="radio" name="ms_trip_type" value="oneway" <?php echo empty($is_roundtrip) ? 'checked' : ''; ?>>
                        <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                        <span>One Way</span>
                    </label>
                    <label class="ms-trip-tab <?php echo !empty($is_roundtrip) ? 'active' : ''; ?>" id="tabRoundtrip">
                        <input type="radio" name="ms_trip_type" value="roundtrip" <?php echo !empty($is_roundtrip) ? 'checked' : ''; ?>>
                        <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                        <span>Round Trip</span>
                    </label>
                    <label class="ms-trip-tab" id="tabMulticity">
                        <input type="radio" name="ms_trip_type" value="multicity">
                        <span class="ms-radio-indicator"><span class="ms-radio-dot"></span></span>
                        <span>Multi City</span>
                    </label>
                </div>

                <div class="ms-header-title">
                    <i class="fa-solid fa-plane" style="transform: rotate(-35deg); font-size: 20px;"></i>
                    <span>Book Flight Tickets</span>
                </div>
            </div>

            <!-- Inner White Card -->
            <div class="ms-card-inner">
                <form action="<?php echo site_url('flight/search'); ?>" method="POST" id="modifySearchForm">
                    <input type="hidden" name="tripType" id="ms_tripType" value="<?php echo !empty($is_roundtrip) ? 'roundtrip' : (!empty($is_multicity) ? 'multicity' : 'oneway'); ?>">
                    <input type="hidden" name="from_code" id="ms_from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                    <input type="hidden" name="to_code" id="ms_to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">

                    <!-- 1. Standard Search Grid (One Way & Round Trip) -->
                    <div class="ms-form-grid" id="msStandardGrid" style="<?php echo !empty($is_multicity) ? 'display: none;' : ''; ?>">
                        <!-- From -->
                        <div class="ms-field ms-field-from">
                            <div class="ms-field-label">From</div>
                            <input type="text" name="from_city" id="ms_from_city" class="ms-city-input" value="<?php echo htmlspecialchars($search_query['from']); ?>" placeholder="City or Airport" autocomplete="off">
                            <div class="ms-field-sub" id="ms_from_sub"><?php echo htmlspecialchars($search_query['from_code']); ?>, Airport</div>
                        </div>

                        <!-- Swap Button -->
                        <button type="button" class="ms-swap-btn" id="msSwapBtn" title="Swap Cities">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        </button>

                        <!-- To -->
                        <div class="ms-field ms-field-to">
                            <div class="ms-field-label">To</div>
                            <input type="text" name="to_city" id="ms_to_city" class="ms-city-input" value="<?php echo htmlspecialchars($search_query['to']); ?>" placeholder="City or Airport" autocomplete="off">
                            <div class="ms-field-sub" id="ms_to_sub"><?php echo htmlspecialchars($search_query['to_code']); ?>, Airport</div>
                        </div>

                        <!-- Departure -->
                        <div class="ms-field ms-field-date" id="msDepartureBox">
                            <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron" id="msDepChevron"></i></div>
                            <div class="ms-date-value" id="ms_dep_disp"><?php echo date('d M\'y', strtotime($search_query['date'])); ?></div>
                            <div class="ms-field-sub" id="ms_dep_sub"><?php echo date('l', strtotime($search_query['date'])); ?></div>
                            <input type="hidden" name="departure_date" id="ms_departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                        </div>

                        <!-- Return -->
                        <div class="ms-field ms-field-date ms-return-field" id="msReturnField" style="<?php echo empty($is_roundtrip) ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'; ?>">
                            <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Return <i class="fa-solid fa-chevron-down ms-chevron" id="msRetChevron"></i> <i class="fa-solid fa-circle-xmark ms-clear-return" id="msClearReturn" title="Clear return date" style="display: <?php echo !empty($is_roundtrip) ? 'inline-block' : 'none'; ?>; margin-left: 4px;"></i></div>
                            <div class="ms-date-value" id="ms_ret_disp"><?php echo !empty($is_roundtrip) && !empty($search_query['return_date']) ? date('d M\'y', strtotime($search_query['return_date'])) : '-- --\'--'; ?></div>
                            <div class="ms-field-sub" id="ms_ret_sub"><?php echo !empty($is_roundtrip) && !empty($search_query['return_date']) ? date('l', strtotime($search_query['return_date'])) : 'Select round trip'; ?></div>
                            <input type="hidden" name="return_date" id="ms_return_date" value="<?php echo htmlspecialchars($search_query['return_date'] ?? date('Y-m-d', strtotime($search_query['date'] . ' +3 days'))); ?>" <?php echo empty($is_roundtrip) ? 'disabled' : ''; ?>>
                        </div>

                        <!-- Travellers & Class -->
                        <div class="ms-field ms-field-pax ms-traveller-field" id="msTravellerBox">
                            <div class="ms-field-label">Travellers & Class</div>
                            <div class="ms-traveller-display" id="msTravellerDisplay">
                                <?php echo sprintf('%02d', $total_travelers); ?> Traveller<?php echo $total_travelers > 1 ? 's' : ''; ?>
                            </div>
                            <div class="ms-field-sub" id="ms_cabin_sub"><?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?></div>
                        </div>

                        <!-- Search Button -->
                        <div class="ms-action-wrap">
                            <button type="submit" class="ms-search-btn"><span>SEARCH</span> <i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>

                    <!-- 2. Multi-City Grid (Multi City) -->
                    <div class="ms-multicity-grid <?php echo !empty($is_multicity) ? 'active' : ''; ?>" id="msMulticityGrid">
                        <!-- Leg 1 -->
                        <div class="ms-multi-row" id="msLegRow1">
                            <div class="ms-field">
                                <div class="ms-field-label">From</div>
                                <input type="text" name="multi_from[]" id="msMultiFrom1" class="ms-city-input" value="<?php echo htmlspecialchars($search_query['from']); ?>" autocomplete="off">
                                <div class="ms-field-sub" id="msMultiFromSub1"><?php echo htmlspecialchars($search_query['from_code']); ?>, Airport</div>
                            </div>
                            <div class="ms-field">
                                <div class="ms-field-label">To</div>
                                <input type="text" name="multi_to[]" id="msMultiTo1" class="ms-city-input" value="<?php echo htmlspecialchars($search_query['to']); ?>" autocomplete="off">
                                <div class="ms-field-sub" id="msMultiToSub1"><?php echo htmlspecialchars($search_query['to_code']); ?>, Airport</div>
                            </div>
                            <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="1" id="msMultiDepBox1">
                                <div class="ms-field-label">Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                                <div class="ms-date-value" id="msMultiDepDisp1"><?php echo date('d M\'y', strtotime($search_query['date'])); ?></div>
                                <div class="ms-field-sub" id="msMultiDepSub1"><?php echo date('l', strtotime($search_query['date'])); ?></div>
                                <input type="hidden" name="multi_date[]" id="msMultiDate1" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                            </div>
                            <div class="ms-field ms-field-pax ms-traveller-field" id="msMultiTravellerBox" style="cursor: pointer;">
                                <div class="ms-field-label">Travellers & Class</div>
                                <div class="ms-traveller-display" id="msMultiTravellerDisplay"><?php echo sprintf('%02d', $total_travelers); ?> Traveller<?php echo $total_travelers > 1 ? 's' : ''; ?></div>
                                <div class="ms-field-sub" id="msMultiCabinSub"><?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?></div>
                            </div>
                        </div>

                        <!-- Leg 2 -->
                        <div class="ms-multi-row" id="msLegRow2">
                            <div class="ms-field">
                                <div class="ms-field-label">From</div>
                                <input type="text" name="multi_from[]" id="msMultiFrom2" class="ms-city-input" value="<?php echo htmlspecialchars($search_query['to']); ?>" autocomplete="off">
                                <div class="ms-field-sub" id="msMultiFromSub2"><?php echo htmlspecialchars($search_query['to_code']); ?>, Airport</div>
                            </div>
                            <div class="ms-field">
                                <div class="ms-field-label">To</div>
                                <input type="text" name="multi_to[]" id="msMultiTo2" class="ms-city-input" placeholder="Select a City" value="<?php echo !empty($search_query['multi_to'][1]) ? htmlspecialchars($search_query['multi_to'][1]) : ''; ?>" autocomplete="off">
                                <div class="ms-field-sub" id="msMultiToSub2" style="<?php echo empty($search_query['multi_to'][1]) ? 'display: none;' : ''; ?>"><?php echo !empty($search_query['multi_to_code'][1]) ? htmlspecialchars($search_query['multi_to_code'][1]) . ', Airport' : 'Destination Airport'; ?></div>
                            </div>
                            <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="2" id="msMultiDepBox2">
                                <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                                <div class="ms-date-value" id="msMultiDepDisp2"><?php echo date('d M\'y', strtotime($search_query['date'])); ?></div>
                                <div class="ms-field-sub" id="msMultiDepSub2"><?php echo date('l', strtotime($search_query['date'])); ?></div>
                                <input type="hidden" name="multi_date[]" id="msMultiDate2" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                            </div>
                            <div class="ms-multi-actions" id="msMultiActionsCell">
                                <button type="button" class="ms-btn-add-city" id="msAddCityBtn">+ ADD CITY</button>
                                <button type="submit" class="ms-search-btn"><span>SEARCH</span> <i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>

                        <!-- Dynamic Extra Legs Container -->
                        <div id="msExtraLegsContainer"></div>
                    </div>

                    <!-- Shared Hidden Traveller Inputs -->
                    <input type="hidden" name="adults" id="ms_adults" value="<?php echo $search_query['adults'] ?? 1; ?>">
                    <input type="hidden" name="children" id="ms_children" value="<?php echo $search_query['children'] ?? 0; ?>">
                    <input type="hidden" name="infants" id="ms_infants" value="<?php echo $search_query['infants'] ?? 0; ?>">
                    <input type="hidden" name="cabin_class" id="ms_cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">

                    <!-- Shared Traveller Popup -->
                    <div class="ms-traveller-popup" id="msTravellerPopup">
                        <div class="ms-pax-row">
                            <div><strong>Adults</strong><small>12+ years</small></div>
                            <div class="ms-pax-controls">
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('adult',-1);">−</button>
                                <span id="ms_adultCount"><?php echo $search_query['adults'] ?? 1; ?></span>
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('adult',1);">+</button>
                            </div>
                        </div>
                        <div class="ms-pax-row">
                            <div><strong>Children</strong><small>2-12 years</small></div>
                            <div class="ms-pax-controls">
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('child',-1);">−</button>
                                <span id="ms_childCount"><?php echo $search_query['children'] ?? 0; ?></span>
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('child',1);">+</button>
                            </div>
                        </div>
                        <div class="ms-pax-row">
                            <div><strong>Infants</strong><small>Below 2 years</small></div>
                            <div class="ms-pax-controls">
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('infant',-1);">−</button>
                                <span id="ms_infantCount"><?php echo $search_query['infants'] ?? 0; ?></span>
                                <button type="button" onclick="event.stopPropagation(); msPaxUpdate('infant',1);">+</button>
                            </div>
                        </div>
                        <div class="ms-pax-row" style="border:none; padding-bottom:0;">
                            <div><strong>Cabin Class</strong></div>
                            <select id="ms_cabinSelect" onchange="document.getElementById('ms_cabin_class').value=this.value; msUpdateTravellerText();" style="border:1.5px solid #0d3470; border-radius:6px; padding:6px 10px; font-weight:600; font-size:13px;">
                                <option value="Economy" <?php echo ($search_query['cabin_class'] ?? '') == 'Economy' ? 'selected' : ''; ?>>Economy</option>
                                <option value="Premium Economy" <?php echo ($search_query['cabin_class'] ?? '') == 'Premium Economy' ? 'selected' : ''; ?>>Premium Economy</option>
                                <option value="Business" <?php echo ($search_query['cabin_class'] ?? '') == 'Business' ? 'selected' : ''; ?>>Business</option>
                                <option value="First Class" <?php echo ($search_query['cabin_class'] ?? '') == 'First Class' ? 'selected' : ''; ?>>First Class</option>
                            </select>
                        </div>
                        <button type="button" class="ms-pax-done" onclick="event.stopPropagation(); document.getElementById('msTravellerPopup').style.display='none';">Apply</button>
                    </div>

                    <!-- Dual Month Interactive Calendar Popup -->
                    <div class="ms-calendar-popup" id="msCalendarPopup">
                        <div class="ms-cal-header-tabs">
                            <div class="ms-cal-tab active" id="msCalTabDep">
                                <span class="ms-cal-tab-label">DEPARTURE</span>
                                <span class="ms-cal-tab-date" id="msCalDepText"><?php echo date('M d, Y', strtotime($search_query['date'])); ?></span>
                            </div>
                            <div class="ms-cal-tab" id="msCalTabRet">
                                <span class="ms-cal-tab-label">RETURN</span>
                                <span class="ms-cal-tab-date" id="msCalRetText"><?php echo !empty($is_roundtrip) && !empty($search_query['return_date']) ? date('M d, Y', strtotime($search_query['return_date'])) : 'Select Return'; ?> <i class="fa-solid fa-circle-xmark ms-cal-clear-ret" id="msCalClearRet" title="Clear return date" style="display: <?php echo !empty($is_roundtrip) ? 'inline-block' : 'none'; ?>;"></i></span>
                            </div>
                        </div>
                        <div class="ms-cal-months-grid" id="msCalMonthsGrid">
                            <!-- Dynamically rendered 2 months -->
                        </div>
                        <div class="ms-cal-bottom-note">* All fares are in INR</div>
                    </div>

                    <!-- Bottom Bar: Special Fares -->
                    <div class="ms-bottom-bar <?php echo !empty($is_multicity) ? 'is-multicity' : ''; ?>" id="msBottomBar">
                        <label class="ms-fare-checkbox ms-fare-direct" id="msFareDirect"><input type="checkbox" name="direct_only"> Direct Flights</label>
                        <label class="ms-fare-checkbox"><input type="checkbox" name="defence_fare"> Defence Fare</label>
                        <label class="ms-fare-checkbox"><input type="checkbox" name="student_fare"> Student Fare</label>
                        <label class="ms-fare-checkbox"><input type="checkbox" name="senior_fare"> Senior Citizen Fare</label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
    $airlineMap = array(
        '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
        'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
        'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
        'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
        'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
        'I5' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
        'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png')
    );

    // Normalize Onward Flights
    $onwardFlights = array();
    $activeSearchTui = !empty($search_tui) ? $search_tui : ($search_query['tui'] ?? '');

    if (!empty($flightResults) && is_array($flightResults)) {
        if (isset($flightResults['Flights']) && is_array($flightResults['Flights'])) {
            $rawList = $flightResults['Flights'];
        } elseif (isset($flightResults['Trips'][0]['Journey']) && is_array($flightResults['Trips'][0]['Journey'])) {
            $rawList = $flightResults['Trips'][0]['Journey'];
        } elseif (isset($flightResults['Trips'][0]['Journeys']) && is_array($flightResults['Trips'][0]['Journeys'])) {
            $rawList = $flightResults['Trips'][0]['Journeys'];
        } elseif (isset($flightResults[0]) && is_array($flightResults[0])) {
            $rawList = $flightResults;
        } else {
            $rawList = array($flightResults);
        }

        foreach ($rawList as $idx => $item) {
            if (!is_array($item)) continue;
            if (isset($item['flight_number']) || isset($item['airline_name']) || isset($item['price'])) {
                $code = isset($item['airline_code']) ? strtoupper($item['airline_code']) : '6E';
                $defaultLogo = isset($airlineMap[$code]) ? $airlineMap[$code]['logo'] : 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png';
                $defaultName = isset($airlineMap[$code]) ? $airlineMap[$code]['name'] : ($code . ' Airlines');

                $onwardFlights[] = array(
                    'ResultID' => (!empty($item['tui']) && strpos($item['tui'], 'FL_') !== 0) ? $item['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ($item['ResultID'] ?? ('FL_' . ($idx + 100)))),
                    'AirlineCode' => $code,
                    'AirlineName' => $item['airline_name'] ?? $defaultName,
                    'AirlineLogo' => !empty($item['airline_logo']) ? $item['airline_logo'] : $defaultLogo,
                    'FlightNumber' => $item['flight_number'] ?? ($code . '-' . (2000 + $idx)),
                    'FromCode' => $item['from_code'] ?? $search_query['from_code'],
                    'ToCode' => $item['to_code'] ?? $search_query['to_code'],
                    'DepartureTime' => $item['departure_time'] ?? '06:00',
                    'ArrivalTime' => $item['arrival_time'] ?? '08:15',
                    'Duration' => $item['duration'] ?? '2h 15m',
                    'Stops' => isset($item['stops']) ? (int)$item['stops'] : 0,
                    'Price' => (float)($item['price'] ?? 4999),
                    'Baggage' => $item['checkin_baggage'] ?? $item['baggage'] ?? '15 Kgs',
                    'Refundable' => isset($item['refundable']) ? $item['refundable'] : true,
                    'SeatsLeft' => $item['seats_left'] ?? rand(3, 9)
                );
            } elseif (isset($item['AirlineName']) || isset($item['FlightNumber']) || isset($item['Price'])) {
                $code = isset($item['AirlineCode']) ? strtoupper($item['AirlineCode']) : '6E';
                $onwardFlights[] = array(
                    'ResultID' => (!empty($item['ResultID']) && strpos($item['ResultID'], 'FL_') !== 0) ? $item['ResultID'] : (!empty($item['tui']) && strpos($item['tui'], 'FL_') !== 0 ? $item['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ('FL_' . ($idx + 100)))),
                    'AirlineCode' => $code,
                    'AirlineName' => $item['AirlineName'] ?? 'IndiGo',
                    'AirlineLogo' => $item['AirlineLogo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png',
                    'FlightNumber' => $item['FlightNumber'] ?? ($code . '-' . (2000 + $idx)),
                    'FromCode' => $item['FromCode'] ?? $search_query['from_code'],
                    'ToCode' => $item['ToCode'] ?? $search_query['to_code'],
                    'DepartureTime' => $item['DepartureTime'] ?? '06:00',
                    'ArrivalTime' => $item['ArrivalTime'] ?? '08:15',
                    'Duration' => $item['Duration'] ?? '2h 15m',
                    'Stops' => isset($item['Stops']) ? (int)$item['Stops'] : 0,
                    'Price' => (float)($item['Price'] ?? 4999),
                    'Baggage' => $item['Baggage'] ?? '15 Kgs',
                    'Refundable' => isset($item['Refundable']) ? $item['Refundable'] : true,
                    'SeatsLeft' => $item['SeatsLeft'] ?? rand(3, 9)
                );
            }
        }
    }

    if (empty($onwardFlights)) {
        $mockAirlines = array(
            array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2134', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '06:00', 'arr' => '08:15', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5150),
            array('code' => 'SG', 'name' => 'SpiceJet', 'flight_no' => 'SG-162', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png', 'dep' => '09:30', 'arr' => '11:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4999),
            array('code' => 'AI', 'name' => 'Air India', 'flight_no' => 'AI-805', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png', 'dep' => '14:15', 'arr' => '16:30', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5450),
            array('code' => 'QP', 'name' => 'Akasa Air', 'flight_no' => 'QP-1311', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png', 'dep' => '18:20', 'arr' => '20:35', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4850),
            array('code' => 'UK', 'name' => 'Vistara', 'flight_no' => 'UK-945', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png', 'dep' => '20:45', 'arr' => '23:00', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5800)
        );
        foreach ($mockAirlines as $mIdx => $m) {
            $onwardFlights[] = array(
                'ResultID' => !empty($activeSearchTui) ? $activeSearchTui : ('FL_' . ($mIdx + 100)),
                'AirlineCode' => $m['code'],
                'AirlineName' => $m['name'],
                'AirlineLogo' => $m['logo'],
                'FlightNumber' => $m['flight_no'],
                'FromCode' => $search_query['from_code'],
                'ToCode' => $search_query['to_code'],
                'DepartureTime' => $m['dep'],
                'ArrivalTime' => $m['arr'],
                'Duration' => $m['dur'],
                'Stops' => $m['stops'],
                'Price' => $m['price'],
                'Baggage' => '15 Kgs',
                'Refundable' => true,
                'SeatsLeft' => rand(3, 9)
            );
        }
    }

    // Normalize Return Flights (for Round Trip)
    $inboundFlights = array();
    if (!empty($is_roundtrip)) {
        if (!empty($returnFlights) && is_array($returnFlights)) {
            $rawRetList = (isset($returnFlights['Flights'])) ? $returnFlights['Flights'] : (isset($returnFlights[0]) ? $returnFlights : array($returnFlights));
            foreach ($rawRetList as $rIdx => $rItem) {
                if (!is_array($rItem)) continue;
                $rCode = isset($rItem['airline_code']) ? strtoupper($rItem['airline_code']) : (isset($rItem['AirlineCode']) ? strtoupper($rItem['AirlineCode']) : '6E');
                $inboundFlights[] = array(
                    'ResultID' => (!empty($rItem['tui']) && strpos($rItem['tui'], 'FL_') !== 0) ? $rItem['tui'] : (!empty($activeSearchTui) ? $activeSearchTui : ('FL_RET_' . ($rIdx + 100))),
                    'AirlineCode' => $rCode,
                    'AirlineName' => $rItem['airline_name'] ?? ($airlineMap[$rCode]['name'] ?? 'IndiGo'),
                    'AirlineLogo' => $rItem['airline_logo'] ?? ($airlineMap[$rCode]['logo'] ?? 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
                    'FlightNumber' => $rItem['flight_number'] ?? ($rCode . '-' . (3000 + $rIdx)),
                    'FromCode' => $search_query['to_code'],
                    'ToCode' => $search_query['from_code'],
                    'DepartureTime' => $rItem['departure_time'] ?? '18:00',
                    'ArrivalTime' => $rItem['arrival_time'] ?? '20:15',
                    'Duration' => $rItem['duration'] ?? '2h 15m',
                    'Stops' => isset($rItem['stops']) ? (int)$rItem['stops'] : 0,
                    'Price' => (float)($rItem['price'] ?? 5150),
                    'Baggage' => $rItem['checkin_baggage'] ?? $rItem['baggage'] ?? '15 Kgs',
                    'Refundable' => true,
                    'SeatsLeft' => rand(3, 9)
                );
            }
        }
        if (empty($inboundFlights)) {
            $mockRetAirlines = array(
                array('code' => '6E', 'name' => 'IndiGo', 'flight_no' => '6E-2135', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'dep' => '15:30', 'arr' => '17:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5150),
                array('code' => 'SG', 'name' => 'SpiceJet', 'flight_no' => 'SG-163', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png', 'dep' => '17:45', 'arr' => '20:00', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4999),
                array('code' => 'AI', 'name' => 'Air India', 'flight_no' => 'AI-806', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png', 'dep' => '19:15', 'arr' => '21:30', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5450),
                array('code' => 'QP', 'name' => 'Akasa Air', 'flight_no' => 'QP-1312', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png', 'dep' => '21:30', 'arr' => '23:45', 'dur' => '2h 15m', 'stops' => 0, 'price' => 4850),
                array('code' => 'UK', 'name' => 'Vistara', 'flight_no' => 'UK-946', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png', 'dep' => '22:45', 'arr' => '01:00', 'dur' => '2h 15m', 'stops' => 0, 'price' => 5800)
            );
            foreach ($mockRetAirlines as $rIdx => $m) {
                $inboundFlights[] = array(
                    'ResultID' => !empty($activeSearchTui) ? $activeSearchTui : ('FL_RET_' . ($rIdx + 100)),
                    'AirlineCode' => $m['code'],
                    'AirlineName' => $m['name'],
                    'AirlineLogo' => $m['logo'],
                    'FlightNumber' => $m['flight_no'],
                    'FromCode' => $search_query['to_code'],
                    'ToCode' => $search_query['from_code'],
                    'DepartureTime' => $m['dep'],
                    'ArrivalTime' => $m['arr'],
                    'Duration' => $m['dur'],
                    'Stops' => $m['stops'],
                    'Price' => $m['price'],
                    'Baggage' => '15 Kgs',
                    'Refundable' => true,
                    'SeatsLeft' => rand(3, 9)
                );
            }
        }
    }
    ?>

    <!-- Main Layout: Sidebar + Results -->
    <div class="container layout-grid" style="<?php echo !empty($is_roundtrip) ? 'grid-template-columns: 240px 1fr;' : ''; ?>">
        
        <!-- Sidebar (Filters) -->
        <aside class="filters-sidebar">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1.5px solid #e2e8f0;">
                <h3 class="filter-heading" style="padding: 0; border: none; font-size: 18px; font-weight: 800; color: #000000;">Filters</h3>
                <button type="button" class="filter-reset-btn" id="resetAllFiltersBtn" onclick="resetAllFilters();"><i class="fa-solid fa-rotate-left"></i> Reset All</button>
            </div>
            
            <?php
            // Compute filter data from actual flights
            $nonStopMin = PHP_INT_MAX; $oneStopMin = PHP_INT_MAX; $twoPlusMin = PHP_INT_MAX;
            $nonStopCount = 0; $oneStopCount = 0; $twoPlusCount = 0;
            $airlineData = array();
            $pricesAll = array();
            $connectingAirports = array();
            $refundableCount = 0;

            foreach ($onwardFlights as $flt) {
                $p = (float)$flt['Price'];
                $pricesAll[] = $p;
                $s = (int)$flt['Stops'];
                $code = $flt['AirlineCode'];
                
                if ($s === 0) { $nonStopCount++; $nonStopMin = min($nonStopMin, $p); }
                elseif ($s === 1) { $oneStopCount++; $oneStopMin = min($oneStopMin, $p); }
                else { $twoPlusCount++; $twoPlusMin = min($twoPlusMin, $p); }
                
                if (!isset($airlineData[$code])) {
                    $airlineData[$code] = array('name' => $flt['AirlineName'], 'logo' => $flt['AirlineLogo'], 'count' => 0, 'min' => PHP_INT_MAX);
                }
                $airlineData[$code]['count']++;
                $airlineData[$code]['min'] = min($airlineData[$code]['min'], $p);
                
                if (!empty($flt['Refundable'])) $refundableCount++;
                
                $via = !empty($flt['via']) ? $flt['via'] : (!empty($flt['Via']) ? $flt['Via'] : '');
                if ($s > 0 && !empty($via)) {
                    $connectingAirports[$via] = true;
                }
            }
            $minPrice = !empty($pricesAll) ? min($pricesAll) : 2500;
            $maxPrice = !empty($pricesAll) ? max($pricesAll) : 25000;
            if ($nonStopMin === PHP_INT_MAX) $nonStopMin = 0;
            if ($oneStopMin === PHP_INT_MAX) $oneStopMin = 0;
            if ($twoPlusMin === PHP_INT_MAX) $twoPlusMin = 0;
            ?>
            
            <!-- Stops -->
            <div class="filter-section">
                <h4 class="filter-title">Stops</h4>
                <div class="stops-grid">
                    <div class="stop-box active" data-stop-filter="all" onclick="toggleStopFilter(this, 'all');">
                        <span class="stop-name">All (<?php echo count($onwardFlights); ?>)</span>
                    </div>
                    <?php if ($nonStopCount > 0): ?>
                    <div class="stop-box" data-stop-filter="0" onclick="toggleStopFilter(this, '0');">
                        <span class="stop-name">Non Stop (<?php echo $nonStopCount; ?>)</span>
                        <span class="stop-price">₹ <?php echo number_format($nonStopMin); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($oneStopCount > 0): ?>
                    <div class="stop-box" data-stop-filter="1" onclick="toggleStopFilter(this, '1');">
                        <span class="stop-name">1 Stop (<?php echo $oneStopCount; ?>)</span>
                        <span class="stop-price">₹ <?php echo number_format($oneStopMin); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($twoPlusCount > 0): ?>
                    <div class="stop-box" data-stop-filter="2+" onclick="toggleStopFilter(this, '2+');">
                        <span class="stop-name">2+ Stops (<?php echo $twoPlusCount; ?>)</span>
                        <span class="stop-price">₹ <?php echo number_format($twoPlusMin); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fare Type -->
            <div class="filter-section">
                <h4 class="filter-title">Fare Type</h4>
                <label class="custom-checkbox" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" id="filterRefundable" onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                    <span style="font-size:13.5px; font-weight:700; color:#000000;">Refundable Fare Only</span>
                </label>
            </div>

            <!-- Departure Times -->
            <div class="filter-section">
                <h4 class="filter-title">Departure Times</h4>
                <p class="filter-subtitle" style="font-size:12.5px; font-weight:700; color:#000000; margin-bottom:10px;">From <?php echo htmlspecialchars($search_query['from_code']); ?></p>
                <div class="time-grid">
                    <div class="time-box" data-dep-slot="morning" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-sun"></i> 05am - 12pm</div>
                    <div class="time-box" data-dep-slot="afternoon" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-sun"></i> 12pm - 6pm</div>
                    <div class="time-box" data-dep-slot="evening" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-cloud-sun"></i> 6pm - 11pm</div>
                    <div class="time-box" data-dep-slot="night" onclick="toggleTimeSlot(this, 'dep');"><i class="fa-solid fa-moon"></i> 11pm - 05am</div>
                </div>
            </div>

            <!-- Arrival Times -->
            <div class="filter-section">
                <h4 class="filter-title">Arrival Times</h4>
                <p class="filter-subtitle" style="font-size:12.5px; font-weight:700; color:#000000; margin-bottom:10px;">At <?php echo htmlspecialchars($search_query['to_code']); ?></p>
                <div class="time-grid">
                    <div class="time-box" data-arr-slot="morning" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-sun"></i> 05am - 12pm</div>
                    <div class="time-box" data-arr-slot="afternoon" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-sun"></i> 12pm - 6pm</div>
                    <div class="time-box" data-arr-slot="evening" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-cloud-sun"></i> 6pm - 11pm</div>
                    <div class="time-box" data-arr-slot="night" onclick="toggleTimeSlot(this, 'arr');"><i class="fa-solid fa-moon"></i> 11pm - 05am</div>
                </div>
            </div>

            <!-- Airlines -->
            <div class="filter-section">
                <h4 class="filter-title">Airlines</h4>
                <div id="airlineFilterList">
                    <?php foreach ($airlineData as $aCode => $aInfo): ?>
                    <label class="airline-filter-row">
                        <span class="airline-filter-info">
                            <input type="checkbox" class="airline-checkbox" value="<?php echo htmlspecialchars($aCode); ?>" checked onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                            <img src="<?php echo htmlspecialchars($aInfo['logo']); ?>" alt="" class="airline-filter-logo" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($aInfo['name']); ?>&background=0d3470&color=fff';">
                            <span style="color:#000000; font-weight:700;"><?php echo htmlspecialchars($aInfo['name']); ?> (<?php echo $aInfo['count']; ?>)</span>
                        </span>
                        <span class="airline-filter-price" style="color:#000000; font-weight:800;">₹ <?php echo number_format($aInfo['min']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Price Range -->
            <div class="filter-section">
                <h4 class="filter-title">Price Range</h4>
                <div class="price-range-wrap">
                    <input type="range" id="priceRangeSlider" min="<?php echo floor($minPrice); ?>" max="<?php echo ceil($maxPrice); ?>" value="<?php echo ceil($maxPrice); ?>" class="price-slider" oninput="updatePriceSlider(this.value);" style="width: 100%; accent-color: #0d3470; cursor: pointer;">
                    <div class="price-range-labels" style="display:flex; justify-content:space-between; margin-top:8px; font-size:13px; font-weight:800; color:#000000;">
                        <span style="color:#000000; font-weight:800;">₹ <?php echo number_format(floor($minPrice)); ?></span>
                        <span id="priceRangeMax" style="color:#000000; font-weight:800;">₹ <?php echo number_format(ceil($maxPrice)); ?></span>
                    </div>
                </div>
            </div>

            <!-- Connecting Airports -->
            <?php if (!empty($connectingAirports)): ?>
            <div class="filter-section">
                <h4 class="filter-title">Connecting Airports</h4>
                <?php foreach (array_keys($connectingAirports) as $airport): ?>
                <label class="custom-checkbox" style="display:flex; align-items:center; gap:10px; margin-bottom:8px; cursor:pointer;">
                    <input type="checkbox" class="connect-airport-cb" value="<?php echo htmlspecialchars($airport); ?>" checked onchange="applyFilters();" style="width:17px; height:17px; accent-color:#0d3470; cursor:pointer;">
                    <span style="font-size:13.5px; font-weight:700; color:#000000;">Via <?php echo htmlspecialchars($airport); ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </aside>

        <!-- Right Side Results -->
        <div class="results-area">
            
            <?php if (empty($is_roundtrip)): ?>
            <!-- ========================================== -->
            <!-- ONE WAY FLIGHT RESULTS VIEW               -->
            <!-- ========================================== -->

            <!-- 7-Day Interactive Date Carousel -->
            <?php 
            $currDateTimestamp = strtotime($search_query['date']);
            if (!$currDateTimestamp) $currDateTimestamp = time();
            $baseMin = !empty($minPrice) ? $minPrice : 4999;
            $todayTimestamp = strtotime(date('Y-m-d'));
            $prevDateTimestamp = strtotime('-1 day', $currDateTimestamp);
            $nextDateTimestamp = strtotime('+1 day', $currDateTimestamp);
            $prevDateStr = date('Y-m-d', $prevDateTimestamp);
            $nextDateStr = date('Y-m-d', $nextDateTimestamp);
            $canGoPrev = ($prevDateTimestamp >= $todayTimestamp);
            ?>
            <div class="date-carousel-container">
                <button type="button" class="date-arrow-btn <?php echo !$canGoPrev ? 'disabled' : ''; ?>" 
                    <?php echo !$canGoPrev ? 'disabled' : ''; ?>
                    onclick="<?php echo $canGoPrev ? "navigateDate('{$prevDateStr}');" : "return false;"; ?>" 
                    title="<?php echo $canGoPrev ? 'Previous Day (' . date('d M', $prevDateTimestamp) . ')' : 'Cannot search past dates'; ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="date-carousel-items" id="dateCarouselItems">
                    <?php 
                    for ($offset = -3; $offset <= 3; $offset++):
                        $dTime = strtotime("$offset days", $currDateTimestamp);
                        $dVal = date('Y-m-d', $dTime);
                        $dLabel = date('D, d M', $dTime);
                        $isCurr = ($offset === 0);
                        $isPast = ($dTime < $todayTimestamp);
                        // Realistic fare difference for adjacent days
                        $offsetPrices = array(-3 => $baseMin + 680, -2 => $baseMin + 350, -1 => $baseMin + 180, 0 => $baseMin, 1 => $baseMin + 290, 2 => $baseMin + 490, 3 => $baseMin + 750);
                        $dPrice = $offsetPrices[$offset] ?? ($baseMin + abs($offset) * 200);
                    ?>
                    <div class="date-pill <?php echo $isCurr ? 'active' : ''; ?> <?php echo $isPast ? 'disabled-date' : ''; ?>" 
                        data-date="<?php echo $dVal; ?>" 
                        <?php if (!$isPast): ?>
                        onclick="navigateDate('<?php echo $dVal; ?>', this);" 
                        title="Search flights for <?php echo date('d M Y', $dTime); ?>"
                        <?php else: ?>
                        title="Past date not available"
                        <?php endif; ?>>
                        <span><?php echo $dLabel; ?></span>
                        <strong style="color: <?php echo $isCurr ? '#16a34a' : ($isPast ? '#94a3b8' : '#0f172a'); ?>;">₹ <?php echo number_format($dPrice); ?></strong>
                    </div>
                    <?php endfor; ?>
                </div>
                <button type="button" class="date-arrow-btn" 
                    onclick="navigateDate('<?php echo $nextDateStr; ?>');" 
                    title="Next Day (<?php echo date('d M', $nextDateTimestamp); ?>)">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <!-- Dedicated Form for Clean Date Navigation -->
            <form id="dateCarouselForm" action="<?php echo site_url('flight/search'); ?>" method="POST" style="display: none;">
                <input type="hidden" name="tripType" value="<?php echo !empty($is_roundtrip) ? 'roundtrip' : 'oneway'; ?>">
                <input type="hidden" name="from_city" value="<?php echo htmlspecialchars($search_query['from']); ?>">
                <input type="hidden" name="to_city" value="<?php echo htmlspecialchars($search_query['to']); ?>">
                <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                <input type="hidden" name="departure_date" id="carousel_departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                <?php if (!empty($is_roundtrip) && !empty($search_query['return_date'])): ?>
                <input type="hidden" name="return_date" id="carousel_return_date" value="<?php echo htmlspecialchars($search_query['return_date']); ?>">
                <?php endif; ?>
                <input type="hidden" name="adults" value="<?php echo (int)($search_query['adults'] ?? 1); ?>">
                <input type="hidden" name="children" value="<?php echo (int)($search_query['children'] ?? 0); ?>">
                <input type="hidden" name="infants" value="<?php echo (int)($search_query['infants'] ?? 0); ?>">
                <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">
            </form>

            <!-- Date Search Loading Overlay -->
            <div id="dateLoadingOverlay">
                <div class="dlo-card">
                    <div class="dlo-plane-wrap">
                        <i class="fa-solid fa-plane"></i>
                    </div>
                    <div class="dlo-title" id="dloDateText">Finding flights...</div>
                    <div class="dlo-route"><?php echo htmlspecialchars($search_query['from_code']); ?> <i class="fa-solid fa-arrow-right" style="font-size: 11px; margin: 0 4px; color: #94a3b8;"></i> <?php echo htmlspecialchars($search_query['to_code']); ?></div>
                    <div class="dlo-bar-wrap">
                        <div class="dlo-bar"></div>
                    </div>
                </div>
            </div>

            <!-- Sorting Tabs -->
            <div class="sorting-bar">
                <div class="sort-tabs">
                    <button type="button" class="sort-tab" id="sortBestValue" onclick="setSortTab('best_value', this);">
                        <i class="fa-regular fa-thumbs-up" style="color:#d97706;"></i> Best Value
                    </button>
                    <button type="button" class="sort-tab active" id="sortCheapest" onclick="setSortTab('cheapest', this);">
                        <i class="fa-solid fa-money-bill-wave" style="color:#16a34a;"></i> Cheapest
                    </button>
                    <button type="button" class="sort-tab" id="sortFastest" onclick="setSortTab('fastest', this);">
                        <i class="fa-solid fa-stopwatch" style="color:#2563eb;"></i> Fastest
                    </button>
                </div>
                <div class="sort-right">
                    <span class="flight-count" id="flightCountBadge" style="font-size:13px; font-weight:700; color:#475569; background:#fff; padding:6px 14px; border-radius:20px; border:1px solid #e2e8f0;">
                        Showing <?php echo count($onwardFlights); ?> flights
                    </span>
                </div>
            </div>

            <!-- Flights List Container -->
            <div class="flights-list" id="flightListContainer">
                <?php 
                if (!empty($onwardFlights)) {
                    foreach ($onwardFlights as $f) {
                        // Parse Departure Time Slot
                        $depHour = (int)substr($f['DepartureTime'], 0, 2);
                        $depSlot = 'night';
                        if ($depHour >= 5 && $depHour < 12) $depSlot = 'morning';
                        elseif ($depHour >= 12 && $depHour < 18) $depSlot = 'afternoon';
                        elseif ($depHour >= 18 && $depHour < 23) $depSlot = 'evening';

                        // Parse Arrival Time Slot
                        $arrHour = (int)substr($f['ArrivalTime'], 0, 2);
                        $arrSlot = 'night';
                        if ($arrHour >= 5 && $arrHour < 12) $arrSlot = 'morning';
                        elseif ($arrHour >= 12 && $arrHour < 18) $arrSlot = 'afternoon';
                        elseif ($arrHour >= 18 && $arrHour < 23) $arrSlot = 'evening';

                        // Parse Duration in Minutes
                        $durStr = $f['Duration'] ?? '2h 15m';
                        preg_match('/(?:(\d+)\s*h)?\s*(?:(\d+)\s*m)?/i', $durStr, $durMatches);
                        $durMins = ((int)($durMatches[1] ?? 0) * 60) + (int)($durMatches[2] ?? 0);
                        if ($durMins <= 0) $durMins = 135;

                        $owStops = (int)$f['Stops'];
                        $owPrice = (float)$f['Price'];
                        $owVia = !empty($f['via']) ? $f['via'] : (!empty($f['Via']) ? $f['Via'] : '');
                        $owRefundable = !empty($f['Refundable']) ? '1' : '0';
                        $airlineCode = strtoupper($f['AirlineCode'] ?? '6E');

                        // Best value composite score
                        $bestValueScore = $owPrice + ($durMins * 8) + ($owStops * 1200);
                ?>
                <div class="f-card" 
                     data-airline="<?php echo htmlspecialchars($airlineCode); ?>" 
                     data-stops="<?php echo $owStops; ?>" 
                     data-price="<?php echo $owPrice; ?>" 
                     data-depslot="<?php echo $depSlot; ?>" 
                     data-arrslot="<?php echo $arrSlot; ?>" 
                     data-duration-mins="<?php echo $durMins; ?>" 
                     data-refundable="<?php echo $owRefundable; ?>" 
                     data-via="<?php echo htmlspecialchars($owVia); ?>"
                     data-score="<?php echo $bestValueScore; ?>">
                    
                    <div class="f-card-main">
                        <div class="f-airline">
                            <div class="f-logo">
                                <img src="<?php echo htmlspecialchars($f['AirlineLogo']); ?>" alt="logo" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($f['AirlineName']); ?>&background=0d3470&color=fff';">
                            </div>
                            <div class="f-name">
                                <strong><?php echo htmlspecialchars($f['AirlineName']); ?></strong>
                                <span><?php echo htmlspecialchars($f['FlightNumber']); ?></span>
                            </div>
                        </div>
                        
                        <div class="f-time-block">
                            <div class="f-time-left">
                                <strong class="f-time"><?php echo htmlspecialchars($f['DepartureTime']); ?></strong>
                                <span class="f-city"><?php echo htmlspecialchars($search_query['from_code']); ?></span>
                            </div>
                            <div class="f-duration">
                                <span><?php echo htmlspecialchars($f['Duration']); ?></span>
                                <div class="f-line">
                                    <i class="fa-solid fa-plane"></i>
                                </div>
                                <span style="font-size: 11px; color: <?php echo ($owStops == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 600;">
                                    <?php 
                                    $owLayover = !empty($f['via']) ? $f['via'] : (!empty($f['Via']) ? $f['Via'] : ($owStops > 0 ? 'HYD' : ''));
                                    echo ($owStops == 0) ? 'Non-Stop' : ($owStops . ' Stop' . ($owLayover ? (', Via ' . htmlspecialchars($owLayover)) : '')); 
                                    ?>
                                </span>
                            </div>
                            <div class="f-time-right">
                                <strong class="f-time"><?php echo htmlspecialchars($f['ArrivalTime']); ?></strong>
                                <span class="f-city"><?php echo htmlspecialchars($search_query['to_code']); ?></span>
                            </div>
                        </div>
                        
                        <div class="f-seats">
                            <i class="fa-solid fa-chair" style="color: #ef4444;"></i>
                            <span><?php echo $f['SeatsLeft']; ?> Seats Left</span>
                        </div>
                        
                        <div class="f-price">
                            <strong>₹ <?php echo number_format($f['Price']); ?></strong>
                            <span style="font-size:11px; color:#64748b; display:block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                        </div>
                        
                        <div class="f-action">
                            <?php 
                            $cardTui = (!empty($f['ResultID']) && strpos($f['ResultID'], 'FL_') !== 0) ? $f['ResultID'] : (!empty($search_tui) ? $search_tui : ($search_query['tui'] ?? $f['ResultID']));
                            ?>
                            <form action="<?php echo site_url('flight/review'); ?>" method="POST">
                                <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="tui" value="<?php echo htmlspecialchars($cardTui); ?>">
                                <input type="hidden" name="airline_name" value="<?php echo htmlspecialchars($f['AirlineName']); ?>">
                                <input type="hidden" name="airline_logo" value="<?php echo htmlspecialchars($f['AirlineLogo']); ?>">
                                <input type="hidden" name="flight_number" value="<?php echo htmlspecialchars($f['FlightNumber']); ?>">
                                <input type="hidden" name="flight_index" value="<?php echo htmlspecialchars($f['FlightIndex'] ?? $f['flight_index'] ?? ($f['Index'] ?? '6E|1')); ?>">
                                <input type="hidden" name="from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                                <input type="hidden" name="to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                                <input type="hidden" name="departure_time" value="<?php echo htmlspecialchars($f['DepartureTime']); ?>">
                                <input type="hidden" name="arrival_time" value="<?php echo htmlspecialchars($f['ArrivalTime']); ?>">
                                <input type="hidden" name="duration" value="<?php echo htmlspecialchars($f['Duration']); ?>">
                                <input type="hidden" name="stops" value="<?php echo htmlspecialchars($f['Stops'] ?? 0); ?>">
                                <input type="hidden" name="via" value="<?php echo htmlspecialchars(!empty($f['via']) ? $f['via'] : (!empty($f['Via']) ? $f['Via'] : ($f['Stops'] > 0 ? 'HYD' : ''))); ?>">
                                <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                                <input type="hidden" name="price" value="<?php echo htmlspecialchars($f['Price']); ?>">
                                <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                                <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                                <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                                <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">
                                <button type="submit" class="f-book-btn">BOOK NOW</button>
                            </form>
                        </div>
                    </div>
                    <div class="f-card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <i class="fa-solid fa-suitcase" style="color: #0d3470;"></i> Check-in Baggage: <strong><?php echo htmlspecialchars($f['Baggage']); ?></strong> Included
                            &nbsp;|&nbsp;
                            <i class="fa-solid fa-shield-halved" style="color: #16a34a;"></i> <?php echo !empty($f['Refundable']) ? '<span style="color:#16a34a; font-weight:bold;">Refundable Fare</span>' : 'Standard Fare'; ?>
                        </div>
                        <div style="font-size:12px; color:#2563eb; font-weight:600;">
                            Get ₹500 Instant Discount with Code: <strong>VOYOGO500</strong>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                ?>
                    <div class="no-flights-msg" style="padding: 50px; text-align: center; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 10px;">
                        <h3 style="color: #ef4444; font-family: var(--font-heading);">No Flights Found</h3>
                        <p style="color: #64748b; margin-top: 10px;">Please try modifying your origin, destination, or travel date.</p>
                        <a href="<?php echo site_url('flight'); ?>" class="btn-search" style="display: inline-flex; margin-top: 15px;">Search Again</a>
                    </div>
                <?php } ?>
            </div>

            <!-- No Filter Match Box -->
            <div class="no-filter-match" id="noFilterMatchMsg">
                <i class="fa-solid fa-plane-slash" style="font-size: 38px; color: #94a3b8; margin-bottom: 12px; display: block;"></i>
                <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">No flights match your filters</h4>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">Try adjusting your selected stops, airlines, departure times or price range.</p>
                <button type="button" class="btn-modify" onclick="resetAllFilters();" style="background:#0d3470; display:inline-block; border-radius:6px; padding:8px 20px;">Clear All Filters</button>
            </div>

            <?php else: ?>
            <!-- ========================================== -->
            <!-- ROUND TRIP DUAL COLUMN SELECTION VIEW     -->
            <!-- ========================================== -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                
                <!-- Left Sector: Onward Departure Flights -->
                <div>
                    <div style="background: #0d3470; color: #fff; padding: 12px 18px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; opacity: 0.8; display: block;"><i class="fa-solid fa-plane-departure"></i> DEPARTURE FLIGHT</span>
                            <strong style="font-size: 16px;"><?php echo htmlspecialchars($search_query['from_code']); ?> &rarr; <?php echo htmlspecialchars($search_query['to_code']); ?></strong>
                        </div>
                        <span style="font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px;">
                            <?php echo date('d M, D', strtotime($search_query['date'])); ?>
                        </span>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="onwardFlightGroup">
                        <?php foreach ($onwardFlights as $idx => $f): ?>
                        <label class="rt-card onward-card <?php echo ($idx === 0) ? 'selected-rt-card' : ''; ?>" data-stops="<?php echo (int)($f['Stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($f['AirlineCode'] ?? '6E'); ?>" data-price="<?php echo (float)$f['Price']; ?>" style="display: block; cursor: pointer; border: 2px solid <?php echo ($idx === 0) ? '#2563eb' : '#e2e8f0'; ?>; border-radius: 8px; padding: 12px; transition: all 0.2s; background: <?php echo ($idx === 0) ? '#eff6ff' : '#fff'; ?>;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="selected_onward_idx" value="<?php echo $idx; ?>" <?php echo ($idx === 0) ? 'checked' : ''; ?> style="accent-color: #2563eb;" onchange="updateRoundTripSelection()">
                                    <img src="<?php echo htmlspecialchars($f['AirlineLogo']); ?>" alt="logo" style="height: 24px; width: 24px; object-fit: contain;">
                                    <div>
                                        <strong style="font-size: 13px; color: #0d3470; display: block;"><?php echo htmlspecialchars($f['AirlineName']); ?></strong>
                                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($f['FlightNumber']); ?></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 16px; color: #0f172a;">₹ <?php echo number_format($f['Price']); ?></strong>
                                    <span style="font-size: 10px; color: #64748b; display: block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: 13px;">
                                <div>
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['DepartureTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($f['FromCode']); ?></span>
                                </div>
                                <div style="text-align: center; color: #64748b; font-size: 11px;">
                                    <span><?php echo htmlspecialchars($f['Duration']); ?></span>
                                    <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                    <span style="color: <?php echo ($f['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 700;">
                                        <?php 
                                        $layover = !empty($f['via']) ? $f['via'] : (!empty($f['Via']) ? $f['Via'] : ($f['Stops'] > 0 ? 'HYD' : ''));
                                        echo ($f['Stops'] == 0) ? 'Non Stop' : ($f['Stops'] . ' Stop' . ($layover ? (', Via ' . htmlspecialchars($layover)) : '')); 
                                        ?>
                                    </span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($f['ArrivalTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($f['ToCode']); ?></span>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right Sector: Return Inbound Flights -->
                <div>
                    <div style="background: #1e3a8a; color: #fff; padding: 12px 18px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; opacity: 0.8; display: block;"><i class="fa-solid fa-plane-arrival"></i> RETURN FLIGHT</span>
                            <strong style="font-size: 16px;"><?php echo htmlspecialchars($search_query['to_code']); ?> &rarr; <?php echo htmlspecialchars($search_query['from_code']); ?></strong>
                        </div>
                        <span style="font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px;">
                            <?php echo date('d M, D', strtotime($search_query['return_date'])); ?>
                        </span>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 10px 10px; padding: 12px; display: flex; flex-direction: column; gap: 10px;" id="returnFlightGroup">
                        <?php foreach ($inboundFlights as $rIdx => $rf): ?>
                        <label class="rt-card return-card <?php echo ($rIdx === 0) ? 'selected-rt-card' : ''; ?>" data-stops="<?php echo (int)($rf['Stops'] ?? 0); ?>" data-airline="<?php echo htmlspecialchars($rf['AirlineCode'] ?? '6E'); ?>" data-price="<?php echo (float)$rf['Price']; ?>" style="display: block; cursor: pointer; border: 2px solid <?php echo ($rIdx === 0) ? '#2563eb' : '#e2e8f0'; ?>; border-radius: 8px; padding: 12px; transition: all 0.2s; background: <?php echo ($rIdx === 0) ? '#eff6ff' : '#fff'; ?>;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="selected_return_idx" value="<?php echo $rIdx; ?>" <?php echo ($rIdx === 0) ? 'checked' : ''; ?> style="accent-color: #2563eb;" onchange="updateRoundTripSelection()">
                                    <img src="<?php echo htmlspecialchars($rf['AirlineLogo']); ?>" alt="logo" style="height: 24px; width: 24px; object-fit: contain;">
                                    <div>
                                        <strong style="font-size: 13px; color: #0d3470; display: block;"><?php echo htmlspecialchars($rf['AirlineName']); ?></strong>
                                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($rf['FlightNumber']); ?></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 16px; color: #0f172a;">₹ <?php echo number_format($rf['Price']); ?></strong>
                                    <span style="font-size: 10px; color: #64748b; display: block;"><?php echo ($total_travelers > 1) ? 'total for ' . $total_travelers . ' travelers' : 'per adult'; ?></span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-size: 13px;">
                                <div>
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['DepartureTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($rf['FromCode']); ?></span>
                                </div>
                                <div style="text-align: center; color: #64748b; font-size: 11px;">
                                    <span><?php echo htmlspecialchars($rf['Duration']); ?></span>
                                    <div style="height: 1px; background: #cbd5e1; width: 50px; margin: 2px auto;"></div>
                                    <span style="color: <?php echo ($rf['Stops'] == 0) ? '#16a34a' : '#d97706'; ?>; font-weight: 700;">
                                        <?php 
                                        $rLayover = !empty($rf['via']) ? $rf['via'] : (!empty($rf['Via']) ? $rf['Via'] : ($rf['Stops'] > 0 ? 'HYD' : ''));
                                        echo ($rf['Stops'] == 0) ? 'Non Stop' : ($rf['Stops'] . ' Stop' . ($rLayover ? (', Via ' . htmlspecialchars($rLayover)) : '')); 
                                        ?>
                                    </span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="font-size: 15px; color: #0f172a;"><?php echo htmlspecialchars($rf['ArrivalTime']); ?></strong>
                                    <span style="color: #64748b; font-size: 11px; display: block;"><?php echo htmlspecialchars($rf['ToCode']); ?></span>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Sticky Bottom Round Trip Action Bar -->
            <div id="roundTripStickyBar" style="position: fixed; bottom: 0; left: 0; right: 0; background: #ffffff; border-top: 2px solid #2563eb; box-shadow: 0 -8px 30px rgba(0,0,0,0.12); z-index: 9999; padding: 14px 0;">
                <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 15px;">
                    
                    <div style="display: flex; gap: 30px; align-items: center;">
                        <!-- Departure Summary -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 800; padding: 4px 8px; border-radius: 4px;">DEPART</span>
                            <div>
                                <strong style="font-size: 14px; color: #0f172a;" id="barOnwardAirline">IndiGo 6E-2134</strong>
                                <div style="font-size: 12px; color: #64748b;" id="barOnwardTime">06:00 - 08:15 (DEL &rarr; BOM)</div>
                            </div>
                        </div>

                        <div style="height: 35px; width: 1px; background: #e2e8f0;"></div>

                        <!-- Return Summary -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 800; padding: 4px 8px; border-radius: 4px;">RETURN</span>
                            <div>
                                <strong style="font-size: 14px; color: #0f172a;" id="barReturnAirline">IndiGo 6E-2135</strong>
                                <div style="font-size: 12px; color: #64748b;" id="barReturnTime">07:30 - 09:45 (BOM &rarr; DEL)</div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 24px;">
                        <div style="text-align: right;">
                            <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Round Trip Fare</span>
                            <strong style="font-size: 24px; color: #0d3470; display: block; line-height: 1;" id="barTotalPrice">₹ 10,300</strong>
                        </div>

                        <form action="<?php echo site_url('flight/review'); ?>" method="POST" id="roundTripBookingForm">
                            <input type="hidden" name="is_roundtrip" value="1">
                            <input type="hidden" name="flight_id" id="rt_flight_id" value="">
                            <input type="hidden" name="tui" id="rt_tui" value="<?php echo htmlspecialchars($activeSearchTui); ?>">
                            
                            <!-- Onward Details -->
                            <input type="hidden" name="airline_name" id="rt_airline_name" value="">
                            <input type="hidden" name="airline_logo" id="rt_airline_logo" value="">
                            <input type="hidden" name="flight_number" id="rt_flight_number" value="">
                            <input type="hidden" name="from_code" id="rt_from_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                            <input type="hidden" name="to_code" id="rt_to_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                            <input type="hidden" name="departure_time" id="rt_departure_time" value="">
                            <input type="hidden" name="arrival_time" id="rt_arrival_time" value="">
                            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($search_query['date']); ?>">
                            <input type="hidden" name="price" id="rt_onward_price" value="">
                            <input type="hidden" name="flight_index" id="rt_flight_index" value="">
                            <input type="hidden" name="duration" id="rt_duration" value="">
                            <input type="hidden" name="stops" id="rt_stops" value="">
                            <input type="hidden" name="via" id="rt_via" value="">
                            
                            <!-- Return Details -->
                            <input type="hidden" name="return_airline_name" id="rt_return_airline_name" value="">
                            <input type="hidden" name="return_airline_logo" id="rt_return_airline_logo" value="">
                            <input type="hidden" name="return_flight_number" id="rt_return_flight_number" value="">
                            <input type="hidden" name="return_from_code" id="rt_return_from_code" value="<?php echo htmlspecialchars($search_query['to_code']); ?>">
                            <input type="hidden" name="return_to_code" id="rt_return_to_code" value="<?php echo htmlspecialchars($search_query['from_code']); ?>">
                            <input type="hidden" name="return_departure_time" id="rt_return_departure_time" value="">
                            <input type="hidden" name="return_arrival_time" id="rt_return_arrival_time" value="">
                            <input type="hidden" name="return_departure_date" value="<?php echo htmlspecialchars($search_query['return_date']); ?>">
                            <input type="hidden" name="return_price" id="rt_return_price" value="">
                            <input type="hidden" name="return_flight_index" id="rt_return_flight_index" value="">
                            <input type="hidden" name="return_duration" id="rt_return_duration" value="">
                            <input type="hidden" name="return_stops" id="rt_return_stops" value="">
                            <input type="hidden" name="return_via" id="rt_return_via" value="">

                            <!-- Pax info -->
                            <input type="hidden" name="adults" value="<?php echo htmlspecialchars($search_query['adults'] ?? 1); ?>">
                            <input type="hidden" name="children" value="<?php echo htmlspecialchars($search_query['children'] ?? 0); ?>">
                            <input type="hidden" name="infants" value="<?php echo htmlspecialchars($search_query['infants'] ?? 0); ?>">
                            <input type="hidden" name="cabin_class" value="<?php echo htmlspecialchars($search_query['cabin_class'] ?? 'Economy'); ?>">

                            <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); padding: 12px 28px; font-size: 15px; font-weight: 800; border-radius: 8px; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(234, 88, 12, 0.35);">
                                <span>BOOK ROUND TRIP</span> <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <script>
            const onwardData = <?php echo json_encode($onwardFlights); ?>;
            const returnData = <?php echo json_encode($inboundFlights); ?>;

            function updateRoundTripSelection() {
                const onwardRadio = document.querySelector('input[name="selected_onward_idx"]:checked');
                const returnRadio = document.querySelector('input[name="selected_return_idx"]:checked');

                const oIdx = onwardRadio ? parseInt(onwardRadio.value) : 0;
                const rIdx = returnRadio ? parseInt(returnRadio.value) : 0;

                const o = onwardData[oIdx] || onwardData[0];
                const r = returnData[rIdx] || returnData[0];

                // Update Visual Card Borders
                document.querySelectorAll('.onward-card').forEach((card, idx) => {
                    if (idx === oIdx) {
                        card.style.borderColor = '#2563eb';
                        card.style.background = '#eff6ff';
                    } else {
                        card.style.borderColor = '#e2e8f0';
                        card.style.background = '#ffffff';
                    }
                });

                document.querySelectorAll('.return-card').forEach((card, idx) => {
                    if (idx === rIdx) {
                        card.style.borderColor = '#2563eb';
                        card.style.background = '#eff6ff';
                    } else {
                        card.style.borderColor = '#e2e8f0';
                        card.style.background = '#ffffff';
                    }
                });

                // Update Sticky Bar Text
                if (o) {
                    document.getElementById('barOnwardAirline').textContent = o.AirlineName + ' ' + o.FlightNumber;
                    document.getElementById('barOnwardTime').textContent = o.DepartureTime + ' - ' + o.ArrivalTime + ' (' + o.FromCode + ' → ' + o.ToCode + ')';
                    
                    document.getElementById('rt_flight_id').value = o.ResultID;
                    document.getElementById('rt_airline_name').value = o.AirlineName;
                    document.getElementById('rt_airline_logo').value = o.AirlineLogo;
                    document.getElementById('rt_flight_number').value = o.FlightNumber;
                    document.getElementById('rt_departure_time').value = o.DepartureTime;
                    document.getElementById('rt_arrival_time').value = o.ArrivalTime;
                    document.getElementById('rt_onward_price').value = o.Price;
                    document.getElementById('rt_flight_index').value = o.FlightIndex || o.flight_index || o.Index || ((o.FlightNumber ? o.FlightNumber.substring(0, 2) : '6E') + '|1');
                    document.getElementById('rt_duration').value = o.Duration || '02h 15m';
                    document.getElementById('rt_stops').value = (o.Stops !== undefined) ? o.Stops : 0;
                    document.getElementById('rt_via').value = o.via || o.Via || (o.Stops > 0 ? 'HYD' : '');
                }

                if (r) {
                    document.getElementById('barReturnAirline').textContent = r.AirlineName + ' ' + r.FlightNumber;
                    document.getElementById('barReturnTime').textContent = r.DepartureTime + ' - ' + r.ArrivalTime + ' (' + r.FromCode + ' → ' + r.ToCode + ')';

                    document.getElementById('rt_return_airline_name').value = r.AirlineName;
                    document.getElementById('rt_return_airline_logo').value = r.AirlineLogo;
                    document.getElementById('rt_return_flight_number').value = r.FlightNumber;
                    document.getElementById('rt_return_departure_time').value = r.DepartureTime;
                    document.getElementById('rt_return_arrival_time').value = r.ArrivalTime;
                    document.getElementById('rt_return_price').value = r.Price;
                    document.getElementById('rt_return_flight_index').value = r.FlightIndex || r.flight_index || r.Index || ((r.FlightNumber ? r.FlightNumber.substring(0, 2) : '6E') + '|1');
                    document.getElementById('rt_return_duration').value = r.Duration || '02h 15m';
                    document.getElementById('rt_return_stops').value = (r.Stops !== undefined) ? r.Stops : 0;
                    document.getElementById('rt_return_via').value = r.via || r.Via || (r.Stops > 0 ? 'HYD' : '');
                }

                const total = (o ? parseFloat(o.Price) : 0) + (r ? parseFloat(r.Price) : 0);
                document.getElementById('barTotalPrice').textContent = '₹ ' + total.toLocaleString('en-IN');
            }

            document.addEventListener('DOMContentLoaded', updateRoundTripSelection);
            </script>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
// 1. Modify Search Modal Controls & Interactive Dual Calendar
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openModifySearchBtn');
    const closeBtn = document.getElementById('closeModifySearchBtn');
    const overlay = document.getElementById('modifySearchOverlay');

    // Date Format Helpers
    function formatShortDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return dateStr;
        const day = String(d.getDate()).padStart(2, '0');
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const month = months[d.getMonth()];
        const year = String(d.getFullYear()).slice(-2);
        return `${day} ${month}'${year}`;
    }

    function formatFullDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return dateStr;
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
    }

    function getDayName(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return '';
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        return days[d.getDay()];
    }

    function formatYMD(d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Modal Open/Close Handlers
    if (openBtn && overlay) {
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn && overlay) {
        closeBtn.addEventListener('click', function() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            closeCalendar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                closeCalendar();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            closeCalendar();
        }
    });

    // Swap Cities (Standard Form)
    const swapBtn = document.getElementById('msSwapBtn');
    if (swapBtn) {
        swapBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const fromInput = document.getElementById('ms_from_city');
            const toInput = document.getElementById('ms_to_city');
            const fromSub = document.getElementById('ms_from_sub');
            const toSub = document.getElementById('ms_to_sub');

            if (fromInput && toInput) {
                const tempVal = fromInput.value;
                fromInput.value = toInput.value;
                toInput.value = tempVal;

                if (fromSub && toSub) {
                    const tempSub = fromSub.textContent;
                    fromSub.textContent = toSub.textContent;
                    toSub.textContent = tempSub;
                }

                const fromCode = document.getElementById('ms_from_code');
                const toCode = document.getElementById('ms_to_code');
                if (fromCode && toCode) {
                    const tempCode = fromCode.value;
                    fromCode.value = toCode.value;
                    toCode.value = tempCode;
                }
            }
        });
    }

    // Trip Type Pills Switching (One Way, Round Trip, Multi City)
    const tripRadios = document.querySelectorAll('input[name="ms_trip_type"]');
    const tabOW = document.getElementById('tabOneway');
    const tabRT = document.getElementById('tabRoundtrip');
    const tabMC = document.getElementById('tabMulticity');
    const standardGrid = document.getElementById('msStandardGrid');
    const multicityGrid = document.getElementById('msMulticityGrid');
    const bottomBar = document.getElementById('msBottomBar');

    function applyTripType(type) {
        document.getElementById('ms_tripType').value = type;

        const isOW = (type === 'oneway');
        const isRT = (type === 'roundtrip');
        const isMC = (type === 'multicity');

        if (tabOW) tabOW.classList.toggle('active', isOW);
        if (tabRT) tabRT.classList.toggle('active', isRT);
        if (tabMC) tabMC.classList.toggle('active', isMC);

        const retField = document.getElementById('msReturnField');
        const retInput = document.getElementById('ms_return_date');
        const retDisp = document.getElementById('ms_ret_disp');
        const retSub = document.getElementById('ms_ret_sub');
        const clearRet = document.getElementById('msClearReturn');

        if (isMC) {
            // Multi-City Mode (Screenshot 2)
            if (standardGrid) standardGrid.style.display = 'none';
            if (multicityGrid) multicityGrid.classList.add('active');
            if (bottomBar) bottomBar.classList.add('is-multicity');
        } else {
            // Standard Grid (Screenshot 1)
            if (standardGrid) standardGrid.style.display = 'grid';
            if (multicityGrid) multicityGrid.classList.remove('active');
            if (bottomBar) bottomBar.classList.remove('is-multicity');

            if (retField && retInput) {
                if (isRT) {
                    retField.style.opacity = '1';
                    retField.style.cursor = 'pointer';
                    retInput.disabled = false;
                    if (clearRet) clearRet.style.display = 'inline-block';
                    if (retInput.value) {
                        if (retDisp) retDisp.textContent = formatShortDate(retInput.value);
                        if (retSub) retSub.textContent = getDayName(retInput.value);
                    }
                } else {
                    retField.style.opacity = '0.4';
                    retField.style.cursor = 'not-allowed';
                    retInput.disabled = true;
                    if (clearRet) clearRet.style.display = 'none';
                    if (retDisp) retDisp.textContent = '-- --\'--';
                    if (retSub) retSub.textContent = 'Select round trip';
                }
            }
        }
        closeCalendar();
    }

    tripRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            applyTripType(this.value);
        });
    });

    // Multi-City Leg Sync (Leg 1 To -> Leg 2 From)
    const multiTo1 = document.getElementById('msMultiTo1');
    const multiFrom2 = document.getElementById('msMultiFrom2');
    const multiFromSub2 = document.getElementById('msMultiFromSub2');
    if (multiTo1 && multiFrom2) {
        multiTo1.addEventListener('input', function() {
            multiFrom2.value = this.value;
            if (multiFromSub2) multiFromSub2.textContent = 'Airport';
        });
    }

    // Add City / Leg functionality in Multi-City
    const addCityBtn = document.getElementById('msAddCityBtn');
    const extraLegsContainer = document.getElementById('msExtraLegsContainer');
    let extraLegCount = 0;

    if (addCityBtn && extraLegsContainer) {
        addCityBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (extraLegCount >= 3) return; // max 5 legs total
            extraLegCount++;
            const legNum = 2 + extraLegCount;

            const prevLegToInput = document.getElementById(`msMultiTo${legNum - 1}`) || document.getElementById('msMultiTo2');
            const prevCity = prevLegToInput ? prevLegToInput.value : '';

            const row = document.createElement('div');
            row.className = 'ms-multi-row';
            row.id = `msLegRow${legNum}`;
            row.innerHTML = `
                <div class="ms-field">
                    <div class="ms-field-label">From</div>
                    <input type="text" name="multi_from[]" id="msMultiFrom${legNum}" class="ms-city-input" value="${prevCity}" autocomplete="off">
                    <div class="ms-field-sub">Airport</div>
                </div>
                <div class="ms-field">
                    <div class="ms-field-label">To</div>
                    <input type="text" name="multi_to[]" id="msMultiTo${legNum}" class="ms-city-input" placeholder="Select a City" autocomplete="off">
                    <div class="ms-field-sub" style="display:none;">Destination Airport</div>
                </div>
                <div class="ms-field ms-field-date ms-multi-dep-box" data-leg="${legNum}" id="msMultiDepBox${legNum}">
                    <div class="ms-field-label"><i class="fa-regular fa-calendar-days" style="color: #0ea5e9; margin-right: 4px;"></i> Departure <i class="fa-solid fa-chevron-down ms-chevron"></i></div>
                    <div class="ms-date-value" id="msMultiDepDisp${legNum}">29 Sep'26</div>
                    <div class="ms-field-sub" id="msMultiDepSub${legNum}">Tuesday</div>
                    <input type="hidden" name="multi_date[]" id="msMultiDate${legNum}" value="2026-09-29">
                </div>
                <div class="ms-multi-actions">
                    <button type="button" class="ms-remove-leg-btn" title="Remove leg" onclick="this.closest('.ms-multi-row').remove(); extraLegCount--;">&times;</button>
                </div>
            `;
            extraLegsContainer.appendChild(row);

            // Bind calendar click to newly added leg
            const newDateBox = row.querySelector('.ms-multi-dep-box');
            if (newDateBox) {
                newDateBox.addEventListener('click', function(evt) {
                    evt.stopPropagation();
                    openCalendarForLeg(legNum);
                });
            }
        });
    }

    // ==========================================
    // DUAL MONTH INTERACTIVE CALENDAR ENGINE
    // ==========================================
    const calPopup = document.getElementById('msCalendarPopup');
    const calMonthsGrid = document.getElementById('msCalMonthsGrid');
    const calTabDep = document.getElementById('msCalTabDep');
    const calTabRet = document.getElementById('msCalTabRet');
    const calDepText = document.getElementById('msCalDepText');
    const calRetText = document.getElementById('msCalRetText');

    const depChevron = document.getElementById('msDepChevron');
    const retChevron = document.getElementById('msRetChevron');

    const depInput = document.getElementById('ms_departure_date');
    const retInput = document.getElementById('ms_return_date');

    let calMode = 'departure'; // 'departure' | 'return' | 'multi'
    let activeMultiLeg = 1;

    // Initialize calendar month from departure date or September 2026
    const initialDate = depInput && depInput.value ? new Date(depInput.value + 'T00:00:00') : new Date();
    let calViewYear = !isNaN(initialDate.getFullYear()) ? initialDate.getFullYear() : 2026;
    let calViewMonth = !isNaN(initialDate.getMonth()) ? initialDate.getMonth() : 8; // September

    // Position Calendar Popup right under the trigger
    function positionCalendarPopup(legNum) {
        if (!calPopup) return;
        const cardInner = document.querySelector('.ms-card-inner');
        if (!cardInner) return;
        const cardRect = cardInner.getBoundingClientRect();

        if (window.innerWidth <= 992) {
            calPopup.style.left = '50%';
            calPopup.style.transform = 'translateX(-50%)';
            calPopup.style.top = '100%';
            return;
        }

        calPopup.style.transform = 'none';

        if (calMode === 'multi' && legNum) {
            const legBox = document.getElementById(`msMultiDepBox${legNum}`);
            if (legBox) {
                const boxRect = legBox.getBoundingClientRect();
                const leftPos = Math.max(10, Math.min(cardRect.width - 600, boxRect.left - cardRect.left - 40));
                calPopup.style.left = leftPos + 'px';
                calPopup.style.top = (boxRect.bottom - cardRect.top + 6) + 'px';
            }
        } else {
            const depBox = document.getElementById('msDepartureBox');
            if (depBox) {
                const boxRect = depBox.getBoundingClientRect();
                calPopup.style.left = (boxRect.left - cardRect.left) + 'px';
                calPopup.style.top = (boxRect.top - cardRect.top + 34) + 'px';
            }
        }
    }

    // Fares exactly matching Screenshot 1
    function getDayFare(year, month, day) {
        if (year === 2026 && month === 8) { // September 2026
            const sepFares = { 28: 6500, 29: 5716, 30: 5716 };
            return sepFares[day] || null;
        }
        if (year === 2026 && month === 9) { // October 2026
            const octFares = {
                1: 6516, 2: 5849, 3: 5876, 4: 6085, 5: 5716, 6: 5716, 7: 5944, 8: 5850, 9: 5716,
                10: 6098, 11: 6098, 12: 5944, 13: 5944, 14: 5944, 15: 5928, 16: 5928, 17: 6098,
                18: 6039, 19: 5928, 20: 5928, 21: 5928, 22: 5944, 23: 5944, 24: 6098, 25: 6076,
                26: 5944, 27: 5944, 28: 5944, 29: 6076, 30: 6039, 31: 6039
            };
            return octFares[day] || 5944;
        }
        const seed = (year * 372 + (month + 1) * 31 + day) % 100;
        const prices = [5716, 5849, 5876, 5928, 5944, 6039, 6076, 6085, 6098, 6500, 6516, 5716, 5850, 5716];
        return prices[seed % prices.length];
    }

    function buildMonthHTML(year, month, isLeft) {
        const monthNames = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date(2026, 8, 28); // Sep 28, 2026 reference
        today.setHours(0, 0, 0, 0);

        const currentDepYMD = depInput ? depInput.value : '';
        const currentRetYMD = retInput ? retInput.value : '';
        const isRT = document.getElementById('ms_tripType').value === 'roundtrip';

        let depDate = currentDepYMD ? new Date(currentDepYMD + 'T00:00:00') : null;
        let retDate = (isRT && currentRetYMD) ? new Date(currentRetYMD + 'T00:00:00') : null;

        if (calMode === 'multi') {
            const multiDateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
            depDate = multiDateInput && multiDateInput.value ? new Date(multiDateInput.value + 'T00:00:00') : null;
            retDate = null;
        }

        let html = `<div class="ms-cal-month-box">`;
        html += `<div class="ms-cal-month-header">`;
        if (isLeft) {
            html += `<button type="button" class="ms-cal-nav-btn" id="msCalPrevBtn" title="Previous Month"><i class="fa-solid fa-arrow-left"></i></button>`;
        } else {
            html += `<div></div>`;
        }
        html += `<div class="ms-cal-month-title">${monthNames[month]} ${year}</div>`;
        if (!isLeft) {
            html += `<button type="button" class="ms-cal-nav-btn" id="msCalNextBtn" title="Next Month"><i class="fa-solid fa-arrow-right"></i></button>`;
        } else {
            html += `<div></div>`;
        }
        html += `</div>`;

        html += `<table class="ms-cal-table">
            <thead>
                <tr>
                    <th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th>
                </tr>
            </thead>
            <tbody>`;

        let dateNum = 1;
        for (let row = 0; row < 6; row++) {
            if (dateNum > daysInMonth) break;
            html += `<tr>`;
            for (let col = 0; col < 7; col++) {
                if (row === 0 && col < firstDay) {
                    html += `<td></td>`;
                } else if (dateNum > daysInMonth) {
                    html += `<td></td>`;
                } else {
                    const thisDate = new Date(year, month, dateNum);
                    thisDate.setHours(0, 0, 0, 0);
                    const ymd = formatYMD(thisDate);
                    const isPast = thisDate < today;
                    const isToday = (thisDate.getTime() === today.getTime());

                    const isDep = depDate && thisDate.getTime() === depDate.getTime();
                    const isRet = retDate && thisDate.getTime() === retDate.getTime();
                    const inRange = depDate && retDate && thisDate > depDate && thisDate < retDate;

                    const fare = (!isPast || isToday) ? getDayFare(year, month, dateNum) : null;
                    const isLowest = (fare === 5716);

                    let classes = ['ms-cal-day-btn'];
                    if (isPast && !isToday) classes.push('disabled');
                    if (isToday) classes.push('is-today');
                    if (isDep) classes.push('selected-dep');
                    if (isRet) classes.push('selected-ret');
                    if (inRange) classes.push('in-range');

                    html += `<td>
                        <button type="button" class="${classes.join(' ')}" data-date="${ymd}" ${(isPast && !isToday) ? 'disabled' : ''}>
                            <span class="ms-cal-day-num">${dateNum}</span>
                            ${fare ? `<span class="ms-cal-day-fare ${isLowest ? 'lowest' : ''}">${fare}</span>` : ''}
                        </button>
                    </td>`;
                    dateNum++;
                }
            }
            html += `</tr>`;
        }

        html += `</tbody></table></div>`;
        return html;
    }

    function renderDualCalendar() {
        if (!calMonthsGrid) return;

        let m1Year = calViewYear;
        let m1Month = calViewMonth;
        let m2Year = m1Year;
        let m2Month = m1Month + 1;
        if (m2Month > 11) {
            m2Month = 0;
            m2Year++;
        }

        const html1 = buildMonthHTML(m1Year, m1Month, true);
        const html2 = buildMonthHTML(m2Year, m2Month, false);
        calMonthsGrid.innerHTML = html1 + html2;

        // Update header tab displays
        if (calMode === 'multi') {
            const multiDateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
            const val = multiDateInput ? multiDateInput.value : '';
            if (calTabDep) {
                const label = calTabDep.querySelector('.ms-cal-tab-label');
                if (label) label.textContent = `LEG ${activeMultiLeg} DEPARTURE`;
            }
            if (calDepText) calDepText.textContent = formatFullDate(val);
            if (calTabRet) calTabRet.style.display = 'none';
        } else {
            if (calTabRet) calTabRet.style.display = 'flex';
            if (calTabDep) {
                const label = calTabDep.querySelector('.ms-cal-tab-label');
                if (label) label.textContent = 'DEPARTURE';
            }
            if (depInput && depInput.value && calDepText) {
                calDepText.textContent = formatFullDate(depInput.value);
            }
            if (calRetText) {
                const isRT = document.getElementById('ms_tripType').value === 'roundtrip';
                if (isRT && retInput && retInput.value) {
                    calRetText.innerHTML = `${formatFullDate(retInput.value)} <i class="fa-solid fa-circle-xmark ms-cal-clear-ret" id="msCalClearRet" title="Clear return date"></i>`;
                } else {
                    calRetText.innerHTML = `Select Return`;
                }
            }
        }

        // Bind active tabs style
        if (calTabDep && calTabRet) {
            calTabDep.classList.toggle('active', calMode === 'departure' || calMode === 'multi');
            calTabRet.classList.toggle('active', calMode === 'return');
        }

        // Bind navigation buttons
        const prevBtn = document.getElementById('msCalPrevBtn');
        const nextBtn = document.getElementById('msCalNextBtn');

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                calViewMonth--;
                if (calViewMonth < 0) {
                    calViewMonth = 11;
                    calViewYear--;
                }
                renderDualCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                calViewMonth++;
                if (calViewMonth > 11) {
                    calViewMonth = 0;
                    calViewYear++;
                }
                renderDualCalendar();
            });
        }

        // Re-bind clear return icon inside header
        const clearRetIcon = document.getElementById('msCalClearRet');
        if (clearRetIcon) {
            clearRetIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                applyTripType('oneway');
                const radioOW = tabOW ? tabOW.querySelector('input') : null;
                if (radioOW) radioOW.checked = true;
                calMode = 'departure';
                renderDualCalendar();
            });
        }

        // Bind Day Click Listeners
        const dayBtns = calMonthsGrid.querySelectorAll('.ms-cal-day-btn:not(.disabled)');
        dayBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const selectedYMD = this.getAttribute('data-date');
                if (!selectedYMD) return;

                if (calMode === 'multi') {
                    // Multi-City Date Selection
                    const dateInput = document.getElementById(`msMultiDate${activeMultiLeg}`);
                    const disp = document.getElementById(`msMultiDepDisp${activeMultiLeg}`);
                    const sub = document.getElementById(`msMultiDepSub${activeMultiLeg}`);
                    if (dateInput) dateInput.value = selectedYMD;
                    if (disp) disp.textContent = formatShortDate(selectedYMD);
                    if (sub) sub.textContent = getDayName(selectedYMD);
                    closeCalendar();
                    return;
                }

                const isRT = document.getElementById('ms_tripType').value === 'roundtrip';
                const clickedDate = new Date(selectedYMD + 'T00:00:00');

                if (calMode === 'departure') {
                    // Set Departure Date
                    if (depInput) depInput.value = selectedYMD;
                    const depDisp = document.getElementById('ms_dep_disp');
                    const depSub = document.getElementById('ms_dep_sub');
                    if (depDisp) depDisp.textContent = formatShortDate(selectedYMD);
                    if (depSub) depSub.textContent = getDayName(selectedYMD);

                    if (isRT) {
                        if (retInput && retInput.value) {
                            const curRet = new Date(retInput.value + 'T00:00:00');
                            if (curRet < clickedDate) {
                                const newRet = new Date(clickedDate);
                                newRet.setDate(newRet.getDate() + 1);
                                const newRetYMD = formatYMD(newRet);
                                retInput.value = newRetYMD;
                                const retDisp = document.getElementById('ms_ret_disp');
                                const retSub = document.getElementById('ms_ret_sub');
                                if (retDisp) retDisp.textContent = formatShortDate(newRetYMD);
                                if (retSub) retSub.textContent = getDayName(newRetYMD);
                            }
                        }
                        calMode = 'return';
                        renderDualCalendar();
                    } else {
                        // Switch mode to return for easy roundtrip selection
                        calMode = 'return';
                        renderDualCalendar();
                        setTimeout(() => {
                            if (document.getElementById('ms_tripType').value === 'oneway') {
                                closeCalendar();
                            }
                        }, 500);
                    }
                } else if (calMode === 'return') {
                    // Set Return Date
                    const curDep = depInput && depInput.value ? new Date(depInput.value + 'T00:00:00') : new Date();

                    if (clickedDate < curDep) {
                        // Clicked earlier than departure -> make this departure!
                        if (depInput) depInput.value = selectedYMD;
                        const depDisp = document.getElementById('ms_dep_disp');
                        const depSub = document.getElementById('ms_dep_sub');
                        if (depDisp) depDisp.textContent = formatShortDate(selectedYMD);
                        if (depSub) depSub.textContent = getDayName(selectedYMD);
                        calMode = 'return';
                        renderDualCalendar();
                    } else {
                        // Valid Return Date Selected!
                        if (retInput) retInput.value = selectedYMD;
                        const retDisp = document.getElementById('ms_ret_disp');
                        const retSub = document.getElementById('ms_ret_sub');
                        if (retDisp) retDisp.textContent = formatShortDate(selectedYMD);
                        if (retSub) retSub.textContent = getDayName(selectedYMD);

                        // Auto-select Round Trip!
                        applyTripType('roundtrip');
                        const radioRT = tabRT ? tabRT.querySelector('input') : null;
                        if (radioRT) radioRT.checked = true;

                        renderDualCalendar();

                        setTimeout(() => {
                            closeCalendar();
                        }, 300);
                    }
                }
            });
        });
    }

    function openCalendar(mode) {
        calMode = mode || 'departure';
        if (calPopup) {
            calPopup.classList.add('open');
            positionCalendarPopup();
            renderDualCalendar();
        }
        if (depChevron) {
            depChevron.className = (calMode === 'departure') ? 'fa-solid fa-chevron-up ms-chevron' : 'fa-solid fa-chevron-down ms-chevron';
            depChevron.style.color = (calMode === 'departure') ? '#0284c7' : '#94a3b8';
        }
        if (retChevron) {
            retChevron.className = (calMode === 'return') ? 'fa-solid fa-chevron-up ms-chevron' : 'fa-solid fa-chevron-down ms-chevron';
            retChevron.style.color = (calMode === 'return') ? '#0284c7' : '#94a3b8';
        }
    }

    function openCalendarForLeg(legNum) {
        calMode = 'multi';
        activeMultiLeg = legNum;
        if (calPopup) {
            calPopup.classList.add('open');
            positionCalendarPopup(legNum);
            renderDualCalendar();
        }
    }

    function closeCalendar() {
        if (calPopup) calPopup.classList.remove('open');
        if (depChevron) {
            depChevron.className = 'fa-solid fa-chevron-down ms-chevron';
            depChevron.style.color = '#94a3b8';
        }
        if (retChevron) {
            retChevron.className = 'fa-solid fa-chevron-down ms-chevron';
            retChevron.style.color = '#94a3b8';
        }
    }

    // Departure Box Click
    const depBox = document.getElementById('msDepartureBox');
    if (depBox) {
        depBox.addEventListener('click', function(e) {
            e.stopPropagation();
            if (calPopup && calPopup.classList.contains('open') && calMode === 'departure') {
                closeCalendar();
            } else {
                openCalendar('departure');
            }
        });
    }

    // Return Box Click
    const retBox = document.getElementById('msReturnField');
    if (retBox) {
        retBox.addEventListener('click', function(e) {
            if (e.target.closest('#msClearReturn')) return;
            e.stopPropagation();
            const currentTrip = document.getElementById('ms_tripType').value;
            if (currentTrip !== 'roundtrip') {
                applyTripType('roundtrip');
                const radioRT = tabRT ? tabRT.querySelector('input') : null;
                if (radioRT) radioRT.checked = true;
            }
            if (calPopup && calPopup.classList.contains('open') && calMode === 'return') {
                closeCalendar();
            } else {
                openCalendar('return');
            }
        });
    }

    // Multi-City Leg 1 & 2 Departure Click
    document.querySelectorAll('.ms-multi-dep-box').forEach(box => {
        box.addEventListener('click', function(e) {
            e.stopPropagation();
            const leg = parseInt(this.getAttribute('data-leg') || 1);
            openCalendarForLeg(leg);
        });
    });

    // Calendar Tab Clicks
    if (calTabDep) {
        calTabDep.addEventListener('click', function(e) {
            e.stopPropagation();
            calMode = 'departure';
            renderDualCalendar();
        });
    }

    if (calTabRet) {
        calTabRet.addEventListener('click', function(e) {
            e.stopPropagation();
            const currentTrip = document.getElementById('ms_tripType').value;
            if (currentTrip !== 'roundtrip') {
                applyTripType('roundtrip');
                const radioRT = tabRT ? tabRT.querySelector('input') : null;
                if (radioRT) radioRT.checked = true;
            }
            calMode = 'return';
            renderDualCalendar();
        });
    }

    // Clear Return Date in Search Bar
    const clearRetBtn = document.getElementById('msClearReturn');
    if (clearRetBtn) {
        clearRetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            applyTripType('oneway');
            const radioOW = tabOW ? tabOW.querySelector('input') : null;
            if (radioOW) radioOW.checked = true;
            closeCalendar();
        });
    }

    // Close calendar on click outside
    document.addEventListener('click', function(e) {
        if (calPopup && calPopup.classList.contains('open') && !e.target.closest('#msCalendarPopup') && !e.target.closest('#msDepartureBox') && !e.target.closest('#msReturnField') && !e.target.closest('.ms-multi-dep-box')) {
            closeCalendar();
        }
    });

    // Window resize handler to keep calendar popup aligned
    window.addEventListener('resize', function() {
        if (calPopup && calPopup.classList.contains('open')) {
            positionCalendarPopup(activeMultiLeg);
        }
    });

    // Shared Traveller Popup Handlers
    const travPopup = document.getElementById('msTravellerPopup');
    const travBox = document.getElementById('msTravellerBox');
    const multiTravBox = document.getElementById('msMultiTravellerBox');

    function toggleTravellerPopup(e) {
        e.stopPropagation();
        if (!travPopup) return;
        travPopup.style.display = (travPopup.style.display === 'block') ? 'none' : 'block';
    }

    if (travBox) travBox.addEventListener('click', toggleTravellerPopup);
    if (multiTravBox) multiTravBox.addEventListener('click', toggleTravellerPopup);

    document.addEventListener('click', function(e) {
        if (travPopup && !e.target.closest('#msTravellerPopup') && !e.target.closest('#msTravellerBox') && !e.target.closest('#msMultiTravellerBox')) {
            travPopup.style.display = 'none';
        }
    });
});

// Passenger counter update helper
function msPaxUpdate(type, delta) {
    let adults = parseInt(document.getElementById('ms_adults').value || 1);
    let children = parseInt(document.getElementById('ms_children').value || 0);
    let infants = parseInt(document.getElementById('ms_infants').value || 0);

    if (type === 'adult') {
        adults = Math.max(1, Math.min(9, adults + delta));
        document.getElementById('ms_adults').value = adults;
        document.getElementById('ms_adultCount').textContent = adults;
    } else if (type === 'child') {
        children = Math.max(0, Math.min(9, children + delta));
        document.getElementById('ms_children').value = children;
        document.getElementById('ms_childCount').textContent = children;
    } else if (type === 'infant') {
        infants = Math.max(0, Math.min(4, infants + delta));
        document.getElementById('ms_infants').value = infants;
        document.getElementById('ms_infantCount').textContent = infants;
    }

    msUpdateTravellerText();
}

function msUpdateTravellerText() {
    const adults = parseInt(document.getElementById('ms_adults').value || 1);
    const children = parseInt(document.getElementById('ms_children').value || 0);
    const infants = parseInt(document.getElementById('ms_infants').value || 0);
    const total = adults + children + infants;
    const cabin = document.getElementById('ms_cabin_class').value || 'Economy';

    const disp = document.getElementById('msTravellerDisplay');
    const multiDisp = document.getElementById('msMultiTravellerDisplay');
    const sub = document.getElementById('ms_cabin_sub');
    const multiSub = document.getElementById('msMultiCabinSub');

    const formattedText = (total < 10 ? '0' + total : total) + ' Traveller' + (total > 1 ? 's' : '');

    if (disp) disp.textContent = formattedText;
    if (multiDisp) multiDisp.textContent = formattedText;
    if (sub) sub.textContent = cabin;
    if (multiSub) multiSub.textContent = cabin;
}

// 2. Date Carousel Controls & Date Navigation
function navigateDate(newDate, element) {
    if (!newDate) return;

    // Check if newDate is in the past
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const targetDate = new Date(newDate + 'T00:00:00');
    if (targetDate < today) {
        return;
    }

    // Immediate visual update on clicked element
    if (element && element.classList) {
        document.querySelectorAll('.date-pill').forEach(function(p) {
            p.classList.remove('active');
            const str = p.querySelector('strong');
            if (str && !p.classList.contains('disabled-date')) {
                str.style.color = '#0f172a';
            }
        });
        element.classList.add('active');
        const activeStr = element.querySelector('strong');
        if (activeStr) {
            activeStr.style.color = '#16a34a';
        }
    }

    // Display smooth loading overlay
    const overlay = document.getElementById('dateLoadingOverlay');
    const titleEl = document.getElementById('dloDateText');
    if (titleEl) {
        try {
            const dateObj = new Date(newDate + 'T00:00:00');
            const options = { weekday: 'short', day: '2-digit', month: 'short' };
            titleEl.textContent = 'Finding flights for ' + dateObj.toLocaleDateString('en-US', options) + '...';
        } catch(e) {
            titleEl.textContent = 'Finding flights for ' + newDate + '...';
        }
    }
    if (overlay) {
        overlay.style.display = 'flex';
    }

    // Sync departure date input in modifySearchForm as well
    const msDepInput = document.getElementById('ms_departure_date');
    if (msDepInput) {
        msDepInput.value = newDate;
    }

    // Submit dedicated date carousel form
    const carouselForm = document.getElementById('dateCarouselForm');
    const carouselDepInput = document.getElementById('carousel_departure_date');
    if (carouselForm && carouselDepInput) {
        carouselDepInput.value = newDate;

        const retInput = document.getElementById('carousel_return_date');
        if (retInput && retInput.value) {
            const retDate = new Date(retInput.value + 'T00:00:00');
            if (retDate < targetDate) {
                const newRet = new Date(targetDate);
                newRet.setDate(newRet.getDate() + 3);
                const yr = newRet.getFullYear();
                const mo = String(newRet.getMonth() + 1).padStart(2, '0');
                const da = String(newRet.getDate()).padStart(2, '0');
                retInput.value = yr + '-' + mo + '-' + da;
            }
        }
        carouselForm.submit();
        return;
    }

    // Fallback if dateCarouselForm is not found
    const form = document.getElementById('modifySearchForm');
    if (msDepInput && form) {
        form.submit();
    }
}

function selectSearchDate(newDate) {
    navigateDate(newDate);
}

function shiftDateCarousel(direction) {
    if (direction < 0) {
        const prevBtn = document.querySelector('.date-carousel-container .date-arrow-btn:first-child');
        if (prevBtn && !prevBtn.disabled) {
            prevBtn.click();
            return;
        }
    } else {
        const nextBtn = document.querySelector('.date-carousel-container .date-arrow-btn:last-child');
        if (nextBtn && !nextBtn.disabled) {
            nextBtn.click();
            return;
        }
    }

    const container = document.getElementById('dateCarouselItems');
    if (container) {
        container.scrollBy({ left: direction * 150, behavior: 'smooth' });
    }
}

// 3. Sorting Engine (Cheapest, Fastest, Best Value)
let currentSort = 'cheapest';

function setSortTab(sortType, element) {
    currentSort = sortType;
    document.querySelectorAll('.sort-tab').forEach(t => t.classList.remove('active'));
    if (element) element.classList.add('active');

    const container = document.getElementById('flightListContainer');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.f-card'));
    if (cards.length === 0) return;

    cards.sort((a, b) => {
        const priceA = parseFloat(a.getAttribute('data-price') || 0);
        const priceB = parseFloat(b.getAttribute('data-price') || 0);
        const durA = parseInt(a.getAttribute('data-duration-mins') || 120);
        const durB = parseInt(b.getAttribute('data-duration-mins') || 120);
        const stopsA = parseInt(a.getAttribute('data-stops') || 0);
        const stopsB = parseInt(b.getAttribute('data-stops') || 0);
        const scoreA = parseFloat(a.getAttribute('data-score') || (priceA + durA * 8));
        const scoreB = parseFloat(b.getAttribute('data-score') || (priceB + durB * 8));

        if (sortType === 'cheapest') {
            return priceA - priceB || durA - durB;
        } else if (sortType === 'fastest') {
            return durA - durB || stopsA - stopsB || priceA - priceB;
        } else if (sortType === 'best_value') {
            return scoreA - scoreB;
        }
        return priceA - priceB;
    });

    cards.forEach(c => container.appendChild(c));
}

// 4. Interactive Sidebar Filter State & Engine
let selectedStopFilter = 'all';
let selectedDepSlots = [];
let selectedArrSlots = [];

function toggleStopFilter(elem, filterVal) {
    document.querySelectorAll('.stop-box').forEach(b => b.classList.remove('active'));
    elem.classList.add('active');
    selectedStopFilter = filterVal;
    applyFilters();
}

function toggleTimeSlot(elem, type) {
    elem.classList.toggle('active');
    const slot = elem.getAttribute(type === 'dep' ? 'data-dep-slot' : 'data-arr-slot');
    let targetArr = (type === 'dep') ? selectedDepSlots : selectedArrSlots;

    if (elem.classList.contains('active')) {
        if (!targetArr.includes(slot)) targetArr.push(slot);
    } else {
        const idx = targetArr.indexOf(slot);
        if (idx > -1) targetArr.splice(idx, 1);
    }
    applyFilters();
}

function updatePriceSlider(val) {
    const label = document.getElementById('priceRangeMax');
    if (label) label.textContent = '₹ ' + parseInt(val).toLocaleString('en-IN');
    applyFilters();
}

function resetAllFilters() {
    // 1. Reset Stops
    document.querySelectorAll('.stop-box').forEach(b => b.classList.remove('active'));
    const allBox = document.querySelector('.stop-box[data-stop-filter="all"]');
    if (allBox) allBox.classList.add('active');
    selectedStopFilter = 'all';

    // 2. Reset Fare Type
    const refCb = document.getElementById('filterRefundable');
    if (refCb) refCb.checked = false;

    // 3. Reset Departure & Arrival Times
    document.querySelectorAll('.time-box').forEach(b => b.classList.remove('active'));
    selectedDepSlots = [];
    selectedArrSlots = [];

    // 4. Reset Airlines
    document.querySelectorAll('.airline-checkbox').forEach(cb => cb.checked = true);

    // 5. Reset Price Range Slider
    const slider = document.getElementById('priceRangeSlider');
    if (slider) {
        slider.value = slider.max;
        updatePriceSlider(slider.max);
    }

    // 6. Reset Connecting Airports
    document.querySelectorAll('.connect-airport-cb').forEach(cb => cb.checked = true);

    applyFilters();
}

// Master Filter Function
function applyFilters() {
    const refOnly = document.getElementById('filterRefundable') ? document.getElementById('filterRefundable').checked : false;
    const slider = document.getElementById('priceRangeSlider');
    const maxPrice = slider ? parseFloat(slider.value) : Infinity;

    // Selected Airlines
    const selectedAirlines = Array.from(document.querySelectorAll('.airline-checkbox:checked')).map(cb => cb.value.toUpperCase());
    
    // Selected Connecting Airports
    const selectedAirports = Array.from(document.querySelectorAll('.connect-airport-cb:checked')).map(cb => cb.value.toUpperCase());
    const hasAirportFilters = document.querySelectorAll('.connect-airport-cb').length > 0;

    let visibleCount = 0;

    // Filter One-Way Flight Cards
    const oneWayCards = document.querySelectorAll('.f-card');
    oneWayCards.forEach(card => {
        const stops = parseInt(card.getAttribute('data-stops') || 0);
        const price = parseFloat(card.getAttribute('data-price') || 0);
        const airline = (card.getAttribute('data-airline') || '').toUpperCase();
        const depSlot = card.getAttribute('data-depslot') || 'morning';
        const arrSlot = card.getAttribute('data-arrslot') || 'morning';
        const isRef = card.getAttribute('data-refundable') === '1';
        const via = (card.getAttribute('data-via') || '').toUpperCase();

        // 1. Stops check
        let matchStops = true;
        if (selectedStopFilter === '0') matchStops = (stops === 0);
        else if (selectedStopFilter === '1') matchStops = (stops === 1);
        else if (selectedStopFilter === '2+') matchStops = (stops >= 2);

        // 2. Fare Type check
        let matchRef = true;
        if (refOnly && !isRef) matchRef = false;

        // 3. Departure Slot check
        let matchDep = true;
        if (selectedDepSlots.length > 0 && !selectedDepSlots.includes(depSlot)) matchDep = false;

        // 4. Arrival Slot check
        let matchArr = true;
        if (selectedArrSlots.length > 0 && !selectedArrSlots.includes(arrSlot)) matchArr = false;

        // 5. Airline check
        let matchAirline = selectedAirlines.includes(airline);

        // 6. Price Slider check
        let matchPrice = (price <= maxPrice);

        // 7. Connecting Airport check
        let matchAirport = true;
        if (hasAirportFilters && stops > 0 && via) {
            matchAirport = selectedAirports.includes(via);
        }

        const isMatch = matchStops && matchRef && matchDep && matchArr && matchAirline && matchPrice && matchAirport;

        card.style.display = isMatch ? 'block' : 'none';
        if (isMatch) visibleCount++;
    });

    // Filter Round-Trip Sector Cards
    const onwardCards = document.querySelectorAll('.onward-card');
    const returnCards = document.querySelectorAll('.return-card');

    if (onwardCards.length > 0 || returnCards.length > 0) {
        let firstVisibleOnward = null;
        let firstVisibleReturn = null;

        onwardCards.forEach(card => {
            const stops = parseInt(card.getAttribute('data-stops') || 0);
            const price = parseFloat(card.getAttribute('data-price') || 0);
            const airline = (card.getAttribute('data-airline') || '').toUpperCase();
            const depSlot = card.getAttribute('data-depslot') || 'morning';

            let matchStops = (selectedStopFilter === 'all') || (selectedStopFilter === '0' && stops === 0) || (selectedStopFilter === '1' && stops === 1) || (selectedStopFilter === '2+' && stops >= 2);
            let matchDep = (selectedDepSlots.length === 0) || selectedDepSlots.includes(depSlot);
            let matchAirline = selectedAirlines.includes(airline);
            let matchPrice = (price <= maxPrice);

            const isMatch = matchStops && matchDep && matchAirline && matchPrice;
            card.style.display = isMatch ? 'block' : 'none';
            if (isMatch && !firstVisibleOnward) {
                firstVisibleOnward = card.querySelector('input[type="radio"]');
            }
        });

        returnCards.forEach(card => {
            const stops = parseInt(card.getAttribute('data-stops') || 0);
            const price = parseFloat(card.getAttribute('data-price') || 0);
            const airline = (card.getAttribute('data-airline') || '').toUpperCase();
            const depSlot = card.getAttribute('data-depslot') || 'morning';

            let matchStops = (selectedStopFilter === 'all') || (selectedStopFilter === '0' && stops === 0) || (selectedStopFilter === '1' && stops === 1) || (selectedStopFilter === '2+' && stops >= 2);
            let matchDep = (selectedDepSlots.length === 0) || selectedDepSlots.includes(depSlot);
            let matchAirline = selectedAirlines.includes(airline);
            let matchPrice = (price <= maxPrice);

            const isMatch = matchStops && matchDep && matchAirline && matchPrice;
            card.style.display = isMatch ? 'block' : 'none';
            if (isMatch && !firstVisibleReturn) {
                firstVisibleReturn = card.querySelector('input[type="radio"]');
            }
        });

        if (firstVisibleOnward) firstVisibleOnward.checked = true;
        if (firstVisibleReturn) firstVisibleReturn.checked = true;
        if (typeof updateRoundTripSelection === 'function') updateRoundTripSelection();
    }

    // Update Live Count Badge & No-Result Message
    const countBadge = document.getElementById('flightCountBadge');
    if (countBadge) {
        countBadge.textContent = 'Showing ' + visibleCount + ' flight' + (visibleCount !== 1 ? 's' : '');
    }

    const noMatchMsg = document.getElementById('noFilterMatchMsg');
    if (noMatchMsg) {
        noMatchMsg.style.display = (oneWayCards.length > 0 && visibleCount === 0) ? 'block' : 'none';
    }
}

// Initial sorting & filtering on page load
document.addEventListener('DOMContentLoaded', function() {
    setSortTab('cheapest', document.getElementById('sortCheapest'));
});
</script>
