<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* ==========================================================================
   VOYOGO POLICY & LEGAL PORTAL STYLES (Refined Premium Modern Aesthetics)
   ========================================================================== */
.policy-page-wrapper {
    background-color: #f1f4f9;
    background-image: radial-gradient(at 10% 20%, rgba(2, 132, 199, 0.04) 0px, transparent 50%),
                      radial-gradient(at 90% 10%, rgba(120, 183, 34, 0.04) 0px, transparent 50%);
    padding: 34px 0 80px 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1e293b;
    min-height: calc(100vh - 120px);
}

.policy-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 16px;
}

/* --- Top Utility Cards (Matching Akbar Reference with High-End Styling) --- */
.policy-top-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 26px;
    align-items: stretch;
}

@media (max-width: 960px) {
    .policy-top-cards {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

.p-top-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 22px;
    box-shadow: 0 4px 18px -3px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.p-top-card:hover {
    box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.08);
}

.p-top-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #f59e0b;
}

.p-top-card-right::before {
    background: linear-gradient(180deg, #f59e0b 0%, #0284c7 100%);
}

/* Left Card: Company & Address Details */
.card-left-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.brand-logo-pill {
    background: #09204b;
    padding: 6px 14px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(9, 32, 75, 0.15);
    flex-shrink: 0;
}

.brand-logo-pill img {
    height: 30px;
    width: auto;
    object-fit: contain;
    display: block;
}

.brand-corp-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 5px 11px;
    border-radius: 20px;
    letter-spacing: 0.2px;
    white-space: nowrap;
    flex-shrink: 0;
}

.brand-corp-tag i {
    color: #0284c7;
    font-size: 12px;
}

.card-divider-line {
    height: 1px;
    background: #f1f5f9;
    margin: 14px 0 12px 0;
}

.card-left-body {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 14px;
    align-items: center;
}

@media (max-width: 560px) {
    .card-left-body {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

.addr-block {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.addr-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #fef3c7;
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
    margin-top: 2px;
}

.addr-info {
    line-height: 1.35;
}

.addr-title {
    display: block;
    font-size: 10.5px;
    font-weight: 800;
    color: #09204b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.addr-desc {
    display: block;
    font-size: 12px;
    color: #475569;
    line-height: 1.4;
    font-weight: 500;
}

.phone-block {
    display: flex;
    justify-content: flex-end;
}

@media (max-width: 560px) {
    .phone-block {
        justify-content: flex-start;
    }
}

.phone-link-card {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #f0fdf4;
    border: 1px solid #dcfce7;
    border-radius: 10px;
    padding: 7px 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.phone-link-card:hover {
    background: #dcfce7;
    border-color: #bbf7d0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
}

.phone-icon-wrap {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: #16a34a;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.phone-info {
    display: flex;
    flex-direction: column;
}

.phone-sub {
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #166534;
    letter-spacing: 0.4px;
}

.phone-val {
    font-size: 12.5px;
    font-weight: 800;
    color: #14532d;
    letter-spacing: 0.2px;
}

/* Right Card: 24/7 Customer Support */
.card-right-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.support-title-cluster {
    display: flex;
    align-items: center;
    gap: 12px;
}

.support-avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #09204b 0%, #1e3a8a 100%);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.1;
    border: 2px solid #e0f2fe;
    box-shadow: 0 4px 12px rgba(9, 32, 75, 0.18);
    flex-shrink: 0;
}

.support-avatar-circle i {
    font-size: 14px;
    margin-bottom: 1px;
}

.support-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.support-main-heading {
    font-size: 14.5px;
    font-weight: 800;
    color: #09204b;
}

.p-badge-online {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #dcfce7;
    color: #15803d;
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    white-space: nowrap;
    flex-shrink: 0;
}

.dot-live {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16a34a;
    box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.25);
    animation: livePulse 1.8s infinite;
}

@keyframes livePulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.5); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
}

.support-caption {
    display: block;
    font-size: 11px;
    color: #64748b;
    font-weight: 500;
    margin-top: 1px;
}

.support-phones-wrap {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 5px 10px;
    border-radius: 8px;
    white-space: nowrap;
}

.sup-phone-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #09204b;
    text-decoration: none;
    transition: color 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.sup-phone-link:hover {
    color: #0284c7;
}

.sup-phone-link i {
    font-size: 11px;
    color: #0284c7;
}

.sup-phone-slash {
    color: #cbd5e1;
    font-weight: 600;
    font-size: 12px;
}

.card-right-body {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 12px;
    align-items: center;
}

@media (max-width: 560px) {
    .card-right-body {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .support-v-sep {
        display: none;
    }
}

.support-v-sep {
    width: 1px;
    height: 32px;
    background: #e2e8f0;
}

.support-email-unit {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.email-unit-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.email-chip {
    font-size: 12px;
    font-weight: 600;
    color: #0284c7;
    text-decoration: none;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 3px 9px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
    transition: all 0.2s ease;
}

.email-chip:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    transform: translateY(-1px);
}

/* --- Main Layout: Side Nav + Content Area --- */
.policy-main-grid {
    display: grid;
    grid-template-columns: 290px 1fr;
    gap: 24px;
    align-items: flex-start;
}

@media (max-width: 900px) {
    .policy-main-grid {
        grid-template-columns: 1fr;
    }
}

/* --- Side Navigation (Matching Screenshot) --- */
.policy-sidebar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 4px 20px -3px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    position: sticky;
    top: 24px;
}

.sidebar-top-bar {
    padding: 16px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.sb-tag {
    font-size: 10px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    display: block;
    margin-bottom: 4px;
}

.sb-title {
    font-size: 14px;
    font-weight: 800;
    color: #09204b;
    margin: 0;
}

.side-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.side-nav-item {
    border-bottom: 1px solid #f1f5f9;
}

.side-nav-item:last-child {
    border-bottom: none;
}

.side-nav-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12.5px 18px;
    font-size: 13.5px;
    color: #334155;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    background: #ffffff;
    border-left: 4px solid transparent;
}

.side-nav-link:hover {
    background: #f8fafc;
    color: #09204b;
    transform: translateX(3px);
}

.side-nav-link.active {
    background: linear-gradient(90deg, #f0f4fa 0%, #ffffff 100%);
    color: #09204b;
    font-weight: 700;
    border-left: 4px solid #09204b;
}

.side-nav-link .nav-left-part {
    display: flex;
    align-items: center;
    gap: 12px;
}

.side-nav-link .nav-icon-container {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.side-nav-link:hover .nav-icon-container {
    background: #e2e8f0;
    color: #09204b;
}

.side-nav-link.active .nav-icon-container {
    background: #09204b;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(9, 32, 75, 0.25);
}

.side-nav-link .nav-badge-icon {
    font-size: 14px;
    color: #94a3b8;
    transition: all 0.2s ease;
}

.side-nav-link.active .nav-badge-icon {
    color: #09204b;
}

/* --- Main Content Box (Matching Screenshot) --- */
.policy-content-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 32px 38px;
    box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.05);
    min-height: 600px;
}

@media (max-width: 600px) {
    .policy-content-card {
        padding: 20px 16px;
    }
}

/* Header Inside Content Card */
.policy-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 20px;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 14px;
}

.policy-title-block {
    display: flex;
    align-items: center;
    gap: 14px;
}

.policy-title-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 19px;
    background: linear-gradient(135deg, #09204b 0%, #1e3a8a 100%);
    box-shadow: 0 4px 14px rgba(9, 32, 75, 0.25);
    flex-shrink: 0;
}

.policy-title-block h1 {
    font-size: 22px;
    font-weight: 800;
    color: #09204b;
    margin: 0;
    letter-spacing: -0.3px;
}

.policy-subtitle-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    color: #64748b;
    font-weight: 600;
    margin-top: 3px;
}

.policy-subtitle-pill i {
    color: #16a34a;
}

.policy-actions-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-toggle-all-acc {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
    padding: 7px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-toggle-all-acc:hover {
    background: #09204b;
    color: #ffffff;
    border-color: #09204b;
    box-shadow: 0 3px 10px rgba(9, 32, 75, 0.2);
}

/* Lead intro paragraph */
.policy-lead-para {
    font-size: 13.5px;
    color: #334155;
    line-height: 1.75;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-left: 4px solid #78B722;
    padding: 16px 20px;
    border-radius: 10px;
}

.lead-badge-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 11px;
}

.lead-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #15803d;
}

.lead-time {
    color: #94a3b8;
    font-weight: 600;
}

/* --- Accordion Dropdowns ("Dropdown Auro") --- */
.policy-accordion-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.p-accordion-item {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.p-accordion-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

.p-accordion-item.open {
    border-color: #cbd5e1;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
}

.p-accordion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    background: #ffffff;
    cursor: pointer;
    user-select: none;
    transition: background 0.2s ease;
}

.p-accordion-header:hover {
    background: #fafafa;
}

.p-accordion-item.open .p-accordion-header {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}

.p-acc-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    letter-spacing: -0.1px;
}

.p-acc-title i.prefix-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #dcfce7;
    color: #16a34a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

/* Dropdown Arrow (Dropdown Auro) */
.p-acc-arrow {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    background: #f1f5f9;
    font-size: 13px;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    flex-shrink: 0;
}

.p-accordion-header:hover .p-acc-arrow {
    color: #09204b;
    background: #e2e8f0;
}

.p-accordion-item.open .p-acc-arrow {
    transform: rotate(180deg);
    color: #ffffff;
    background: #78B722;
    box-shadow: 0 3px 10px rgba(120, 183, 34, 0.35);
}

/* Accordion Body Content */
.p-accordion-body {
    display: none;
    padding: 22px 26px;
    font-size: 13.5px;
    color: #334155;
    line-height: 1.8;
    background: #ffffff;
}

.p-accordion-item.open .p-accordion-body {
    display: block;
    animation: fadeInAccordion 0.25s ease forwards;
}

@keyframes fadeInAccordion {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

.p-accordion-body p {
    margin-bottom: 14px;
}

.p-accordion-body p:last-child {
    margin-bottom: 0;
}

.p-accordion-body ul, .p-accordion-body ol {
    margin: 12px 0 16px 20px;
    padding: 0;
}

.p-accordion-body li {
    margin-bottom: 8px;
    line-height: 1.65;
}

/* Highlight boxes inside content */
.p-callout {
    background: #eff6ff;
    border-left: 3.5px solid #0284c7;
    padding: 14px 18px;
    border-radius: 0 8px 8px 0;
    margin: 14px 0;
    font-size: 13px;
    color: #1e40af;
    line-height: 1.65;
}

.p-callout.warning {
    background: #fffbeb;
    border-left-color: #f59e0b;
    color: #92400e;
}

.p-callout.success {
    background: #f0fdf4;
    border-left-color: #16a34a;
    color: #166534;
}

/* Tab Panes */
.tab-content-pane {
    display: none;
}

.tab-content-pane.active {
    display: block;
}

/* Quick Filter Input */
.policy-search-wrap {
    margin-bottom: 22px;
    position: relative;
    display: flex;
    align-items: center;
}

.policy-search-input {
    width: 100%;
    padding: 12px 18px 12px 42px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    font-size: 13.5px;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.policy-search-input:focus {
    background: #ffffff;
    border-color: #09204b;
    box-shadow: 0 0 0 3.5px rgba(9, 32, 75, 0.08);
}

.policy-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 15px;
}

.search-tag-hint {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 3px 8px;
    border-radius: 6px;
    pointer-events: none;
}

/* ==========================================================================
   MOBILE & TABLET RESPONSIVE ENHANCEMENTS (< 900px, < 600px, < 420px)
   ========================================================================== */
@media (max-width: 900px) {
    .policy-page-wrapper {
        padding: 16px 0 50px 0;
    }
    .policy-container {
        padding: 0 12px;
    }
    .policy-top-cards {
        grid-template-columns: 1fr;
        gap: 14px;
        margin-bottom: 16px;
    }
    .policy-main-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    /* Transform Sidebar into Horizontal Swipeable Pill Nav on Mobile/Tablet */
    .policy-sidebar {
        position: relative;
        top: 0;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        margin-bottom: 4px;
    }
    .sidebar-top-bar {
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }
    .sidebar-top-bar .sb-tag {
        font-size: 9px;
        margin-bottom: 2px;
    }
    .sidebar-top-bar .sb-title {
        font-size: 13px;
    }
    .side-nav-list {
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 8px 10px;
        gap: 8px;
        background: #f8fafc;
        border-radius: 0 0 12px 12px;
    }
    .side-nav-list::-webkit-scrollbar {
        display: none;
    }
    .side-nav-item {
        border-bottom: none;
        flex-shrink: 0;
    }
    .side-nav-link {
        border-radius: 20px;
        padding: 7px 13px;
        font-size: 12px;
        white-space: nowrap;
        border-left: none !important;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .side-nav-link:hover {
        transform: none;
        background: #f1f5f9;
    }
    .side-nav-link.active {
        background: #09204b;
        color: #ffffff;
        border-color: #09204b;
        box-shadow: 0 2px 8px rgba(9, 32, 75, 0.2);
    }
    .side-nav-link.active .nav-icon-container {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }
    .side-nav-link .nav-badge-icon {
        display: none;
    }
    .side-nav-link .nav-icon-container {
        width: 22px;
        height: 22px;
        font-size: 10.5px;
        border-radius: 5px;
    }
}

@media (max-width: 600px) {
    .p-top-card {
        padding: 14px 14px;
        border-radius: 12px;
    }
    .card-left-header {
        gap: 8px;
    }
    .brand-logo-pill {
        padding: 5px 10px;
    }
    .brand-logo-pill img {
        height: 24px;
    }
    .brand-corp-tag {
        font-size: 10.5px;
        padding: 4px 8px;
        white-space: nowrap;
    }
    .card-left-body {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .phone-block {
        width: 100%;
        justify-content: flex-start;
    }
    .phone-link-card {
        width: 100%;
        justify-content: center;
        box-sizing: border-box;
        padding: 8px 12px;
    }
    .card-right-header {
        gap: 8px;
    }
    .support-title-cluster {
        gap: 10px;
        width: 100%;
    }
    .support-avatar-circle {
        width: 38px;
        height: 38px;
        font-size: 9px;
    }
    .support-title-row {
        gap: 6px;
        flex-wrap: wrap;
    }
    .support-main-heading {
        font-size: 13px;
    }
    .p-badge-online {
        font-size: 9.5px;
        padding: 2px 6px;
        white-space: nowrap;
    }
    .support-caption {
        font-size: 10.5px;
    }
    .support-phones-wrap {
        width: 100%;
        justify-content: center;
        box-sizing: border-box;
        margin-top: 6px;
        font-size: 12px;
        padding: 6px 8px;
    }
    .sup-phone-link {
        font-size: 11.5px;
    }
    .card-right-body {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .support-v-sep {
        display: none;
    }
    .email-chip {
        width: 100%;
        justify-content: center;
        box-sizing: border-box;
        padding: 6px 10px;
        font-size: 11.5px;
    }
    .policy-content-card {
        padding: 16px 14px;
        border-radius: 12px;
        min-height: auto;
    }
    .policy-header-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }
    .policy-title-block {
        gap: 10px;
        width: 100%;
    }
    .policy-title-block h1 {
        font-size: 16px;
    }
    .policy-title-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
        border-radius: 8px;
    }
    .policy-actions-row {
        width: 100%;
    }
    .btn-toggle-all-acc {
        width: 100%;
        justify-content: center;
        padding: 8px 12px;
        font-size: 11.5px;
    }
    .policy-lead-para {
        padding: 12px 14px;
        font-size: 12px;
        line-height: 1.6;
        margin-bottom: 14px;
    }
    .p-accordion-header {
        padding: 12px 12px;
        gap: 8px;
    }
    .p-acc-title {
        font-size: 12.5px;
        gap: 8px;
    }
    .p-acc-title i.prefix-icon {
        width: 22px;
        height: 22px;
        font-size: 10px;
    }
    .p-acc-arrow {
        width: 26px;
        height: 26px;
        font-size: 10px;
    }
    .p-accordion-body {
        padding: 12px 12px;
        font-size: 12px;
        line-height: 1.6;
    }
    .p-accordion-body table {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .search-tag-hint {
        display: none;
    }
    .policy-search-input {
        font-size: 12px;
        padding: 8px 12px 8px 34px;
    }
    .policy-search-icon {
        left: 11px;
        font-size: 12px;
    }
}

@media (max-width: 420px) {
    .card-left-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .brand-corp-tag {
        width: fit-content;
    }
    .sup-phone-link {
        font-size: 11px;
    }
}
</style>

<div class="policy-page-wrapper">
    <div class="policy-container">
        
        <!-- ==================================================================
             TOP CONTACT & SUPPORT CARDS (Matching Screenshot Design)
             ================================================================== -->
        <div class="policy-top-cards">
            
            <!-- Left Card: Company & Address Details (Matching Reference Structure) -->
            <div class="p-top-card p-top-card-left">
                <div class="card-left-header">
                    <div class="brand-logo-pill">
                        <img src="<?php echo function_exists('base_url') ? base_url('assets/images/logo.png') : './assets/images/logo.png'; ?>" alt="Voyogo Logo">
                    </div>
                    <div class="brand-corp-tag">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>SkyPlanet Voyages Pvt. Ltd.</span>
                    </div>
                </div>

                <div class="card-divider-line"></div>

                <div class="card-left-body">
                    <div class="addr-block">
                        <div class="addr-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="addr-info">
                            <span class="addr-title">Corporate Office</span>
                            <span class="addr-desc">500/1, 11th East Cross St, Aringar Anna Nagar, Anna Nagar, Madurai, Tamil Nadu 625020</span>
                        </div>
                    </div>

                    <div class="phone-block">
                        <a href="tel:+918098999096" class="phone-link-card" title="Call Voyogo 24x7 Helpline">
                            <div class="phone-icon-wrap"><i class="fa-solid fa-phone"></i></div>
                            <div class="phone-info">
                                <span class="phone-sub">24x7 Helpline</span>
                                <strong class="phone-val">+91 8098999096</strong>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Card: 24/7 Customer Support -->
            <div class="p-top-card p-top-card-right">
                <div class="card-right-header">
                    <div class="support-title-cluster">
                        <div class="support-avatar-circle">
                            <i class="fa-solid fa-headset"></i>
                            <span>24/7</span>
                        </div>
                        <div>
                            <div class="support-title-row">
                                <span class="support-main-heading">24/7 Customer Support</span>
                                <span class="p-badge-online"><span class="dot-live"></span> Live Support</span>
                            </div>
                            <span class="support-caption">Immediate booking & ticketing assistance</span>
                        </div>
                    </div>

                    <div class="support-phones-wrap">
                        <a href="tel:+918098999096" class="sup-phone-link" title="Call Flight Support"><i class="fa-solid fa-phone"></i> +91 8098999096</a>
                        <span class="sup-phone-slash">/</span>
                        <a href="tel:+918098999095" class="sup-phone-link" title="Call Hotel Support">+91 8098999095</a>
                    </div>
                </div>

                <div class="card-divider-line"></div>

                <div class="card-right-body">
                    <div class="support-email-unit">
                        <span class="email-unit-label"><i class="fa-solid fa-envelope" style="color: #0284c7;"></i> Customer Support</span>
                        <a href="mailto:support@voyogos.com" class="email-chip"><i class="fa-regular fa-envelope"></i> Support@voyogos.com</a>
                    </div>

                    <div class="support-v-sep"></div>

                    <div class="support-email-unit">
                        <span class="email-unit-label"><i class="fa-solid fa-globe" style="color: #78B722;"></i> Official Website</span>
                        <a href="https://voyogos.com/" target="_blank" class="email-chip"><i class="fa-solid fa-arrow-up-right-from-square"></i> voyogos.com</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================================================================
             MAIN SECTION: SIDEBAR NAVIGATION + RIGHT CONTENT ACCORDION PANEL
             ================================================================== -->
        <div class="policy-main-grid">
            
            <!-- Left Column: Vertical Side Nav Tabs -->
            <aside class="policy-sidebar">
                <div class="sidebar-top-bar">
                    <span class="sb-tag"><i class="fa-solid fa-scale-balanced me-1"></i> LEGAL & SUPPORT</span>
                    <h4 class="sb-title">Policies Directory</h4>
                </div>
                <ul class="side-nav-list">
                    <li class="side-nav-item">
                        <a href="<?php echo site_url('/'); ?>" class="side-nav-link">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-house"></i></span> Home</span>
                            <span class="nav-badge-icon"><i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 12px;"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'terms') ? 'active' : ''; ?>" data-target="tab-terms">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-regular fa-file-lines"></i></span> Terms & Conditions</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'change-cancellation') ? 'active' : ''; ?>" data-target="tab-change-cancellation">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-clock-rotate-left"></i></span> Change & Cancellation</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'cancellation') ? 'active' : ''; ?>" data-target="tab-cancellation">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-hand-holding-dollar"></i></span> Cancellation & Refund</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'flight-policy') ? 'active' : ''; ?>" data-target="tab-flight-policy">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-plane-circle-exclamation"></i></span> Flight Cancellation</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'terms-of-use') ? 'active' : ''; ?>" data-target="tab-terms-of-use">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-scale-balanced"></i></span> Terms of Use</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'privacy') ? 'active' : ''; ?>" data-target="tab-privacy">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-user-shield"></i></span> Privacy Policy</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'disclaimer') ? 'active' : ''; ?>" data-target="tab-disclaimer">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-triangle-exclamation"></i></span> Disclaimer</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link <?php echo ($active_tab === 'faq') ? 'active' : ''; ?>" data-target="tab-faq">
                            <span class="nav-left-part"><span class="nav-icon-container"><i class="fa-solid fa-circle-question"></i></span> Help & FAQs</span>
                            <span class="nav-badge-icon"><i class="fa-regular fa-address-card"></i></span>
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Right Column: Active Content Panel with Accordion Dropdowns -->
            <main class="policy-content-card">
                
                <!-- Quick Filter / Search Bar -->
                <div class="policy-search-wrap">
                    <i class="fa-solid fa-magnifying-glass policy-search-icon"></i>
                    <input type="text" id="policyFilterInput" class="policy-search-input" placeholder="Search any topic, clause or question (e.g. cancellation, refund, 24 hours, bank)...">
                    <span class="search-tag-hint"><i class="fa-solid fa-bolt"></i> Instant Filter</span>
                </div>

                <!-- ==========================================================
                     TAB 1: TERMS & CONDITIONS (Official PDF Document)
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'terms') ? 'active' : ''; ?>" id="tab-terms">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-regular fa-file-lines"></i></div>
                            <div>
                                <h1>Terms &amp; Conditions</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Official Agreement &bull; SkyPlanet Voyages Pvt. Ltd.</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-terms')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-shield-halved"></i> Official Terms &amp; Conditions</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> SkyPlanet Voyages Private Limited</span>
                        </div>
                        These Terms &amp; Conditions govern the use of travel-related services provided by <strong>SkyPlanet Voyages Private Limited</strong> (&ldquo;SkyPlanet's Voyogo,&rdquo; &ldquo;Company,&rdquo; &ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;) through our website, offices, representatives, telephone, email, WhatsApp, or other authorised booking channels.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <!-- Clause 1: Customer Acceptance & Agreement (PDF Page 1) -->
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Customer Acceptance &amp; Agreement</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>By accessing our website, making a booking, or using any of our travel services, the customer (&ldquo;Customer&rdquo;, &ldquo;you&rdquo;, or &ldquo;your&rdquo;) acknowledges that they have read, understood, and agreed to these Terms &amp; Conditions.</p>
                                <p>By making a booking with SkyPlanet Voyages Private Limited, you confirm your acceptance of these Terms &amp; Conditions.</p>
                            </div>
                        </div>

                        <!-- Clause 2: Scope of Travel Services & Bookings (PDF Page 1) -->
                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Scope of Travel Services &amp; Bookings</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>SkyPlanet Voyages Private Limited may provide travel-related services, including domestic and international tour packages, honeymoon packages, group tours, flight and hotel bookings, visa assistance, transportation, sightseeing, and other travel services.</p>
                                <p>All bookings are subject to the specific terms mentioned in the applicable quotation, itinerary, invoice, booking confirmation, and the terms and conditions of the respective airlines, hotels, transport operators, tour operators, and other service providers.</p>
                            </div>
                        </div>

                        <!-- Clause 3: Governing Law, Statutory Rights & Updates (PDF Page 1) -->
                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Governing Law, Statutory Rights &amp; Updates</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>These Terms &amp; Conditions shall be governed by the applicable laws of India. Nothing contained herein shall exclude or restrict any consumer right or legal remedy that cannot lawfully be excluded under applicable Indian law.</p>
                                <p>The Company reserves the right to update these Terms &amp; Conditions from time to time. The applicable terms for a confirmed booking shall be those communicated to the customer at the time of booking, subject to applicable law.</p>
                            </div>
                        </div>

                        <!-- Clause 4: Legal Entity & Policy Scope (PDF Pages 8-9) -->
                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Legal Entity &amp; Scope of Terms</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>SkyPlanet Voyages Private Limited, hereinafter referred to as &ldquo;SkyPlanet's Voyogo&rdquo;, &ldquo;SkyPlanet&rdquo;, &ldquo;Company&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo;, or &ldquo;our&rdquo;, as applicable.</p>
                                <p>These Terms, Policies, and Disclaimers are effective from [Effective Date] and shall apply to all users, customers, clients, and transactions undertaken through the website, booking platforms, offices, and other authorised services of SkyPlanet Voyages Private Limited from the Effective Date onwards.</p>
                                <p>SkyPlanet Voyages Private Limited reserves the right to modify, amend, update, or replace these Terms, Policies, and Disclaimers from time to time, subject to applicable Indian laws and regulations. Any such modification may be published on the company's website or communicated through appropriate channels, as applicable.</p>
                                <p>The updated Terms, Policies, or Disclaimers shall become effective from the date specified in the updated version or upon publication, as applicable. Continued use of the website or services after such modification may constitute acceptance of the revised terms, subject to applicable law.</p>
                            </div>
                        </div>

                        <!-- Clause 5: Client Acknowledgement & Acceptance Clause (PDF Pages 10-11) -->
                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Client Acknowledgement &amp; Acceptance Clause</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>By accessing, registering on, making a booking through, or using the website, booking platform, or travel-related services of SkyPlanet Voyages Private Limited (&ldquo;SkyPlanet&rsquo;s Voyogo&rdquo;), the customer/client hereby:</p>
                                <ul>
                                    <li>Confirms that they have read, understood, and accepted these Terms, Policies, and Disclaimers and agree to comply with them.</li>
                                    <li>Acknowledges that SkyPlanet Voyages Private Limited may act as a travel facilitator, intermediary, or booking agent for certain travel services provided by third-party suppliers.</li>
                                    <li>Agrees that any booking, transaction, or use of the services by the customer or an authorised representative shall constitute acceptance of these Terms and Conditions on behalf of the customer or organisation, where applicable.</li>
                                    <li>Acknowledges that the liability of SkyPlanet Voyages Private Limited shall be subject to the terms and limitations stated in the applicable booking conditions and these Terms, to the extent permitted under applicable Indian law.</li>
                                    <li>Confirms that these Terms and Conditions, together with the applicable quotation, invoice, itinerary, and booking confirmation, shall be binding on the customer, subject to applicable Indian law.</li>
                                </ul>
                                <p>In the event of any conflict between these Terms and Conditions and a separate written agreement expressly executed between SkyPlanet Voyages Private Limited and the customer, the terms of such written agreement shall prevail only to the extent of the conflict, unless otherwise required by applicable law.</p>
                            </div>
                        </div>

                        <!-- Clause 6: Official Support & Contact Channels (PDF Page 20 & Page 8) -->
                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Official Support &amp; Contact Details</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>For inquiries, bookings, or clarifications regarding these Terms &amp; Conditions, please contact us via our official support channels:</p>
                                <div class="p-callout success">
                                    <p style="margin-bottom: 6px;"><strong>SkyPlanet Voyages Private Limited</strong></p>
                                    <p style="margin-bottom: 4px;"><strong>Website:</strong> <a href="https://voyogos.com/" target="_blank" style="color: #0284c7; font-weight: 600;">https://voyogos.com/</a></p>
                                    <p style="margin-bottom: 4px;"><strong>Mail ID:</strong> <a href="mailto:support@voyogos.com" style="color: #0284c7; font-weight: 600;">support@voyogos.com</a></p>
                                    <p style="margin-bottom: 0;"><strong>Phone Number:</strong> <a href="tel:+918098999096" style="color: #0284c7; font-weight: 600;">+91 8098999096</a></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 2: CHANGE & CANCELLATION POLICY
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'change-cancellation') ? 'active' : ''; ?>" id="tab-change-cancellation">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div>
                                <h1>Change and Cancellation Policy</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Standard Operating Procedures &bull; SkyPlanet Voyages Pvt. Ltd.</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-change-cancellation')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Cancellation Notice</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> 24-Hour Notice Required</span>
                        </div>
                        All requests for changes, rescheduling, or cancellation of travel services, tour packages, or bookings purchased through SkyPlanet Voyages Private Limited must be submitted in writing to customer support.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Submission of Written Request & 24-Hour Notice</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>All requests for changes or cancellation of any travel services, tour packages, bookings, or other services purchased through the SkyPlanet Voyages Private Limited website or through our authorised booking channels must be submitted in writing to the customer support or email address provided at the end of these Terms & Conditions. Such requests must be received during our working days and, unless otherwise specified in the applicable booking terms, at least 24 hours before the scheduled date or time of the relevant travel service.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Working Hours & Request Receipt Timings</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Our regular working hours are <strong>Monday to Saturday, from 10:00 AM to 7:00 PM Indian Standard Time (IST)</strong>, excluding Sundays and public holidays.</p>
                                <p>Requests received outside our working hours, on Sundays, or on public holidays will be treated as received on the next working day.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Supplier & Carrier Cancellation Rules</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You agree to abide by the booking terms and conditions communicated to you at the time of purchase. The applicable terms will determine the change, cancellation, and refund conditions for your particular booking, including any cancellation charges, supplier penalties, administrative charges, or other applicable fees.</p>
                                <p>Additionally, you agree that if the applicable booking terms do not provide a specific change or cancellation policy for the service purchased, the booking may be subject to the cancellation and refund conditions of the respective airline, hotel, tour operator, transport provider, visa service provider, or other travel supplier. Any refund, where applicable, will be subject to the terms imposed by the relevant service provider and applicable Indian law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Refund Determination & Partially Utilised Services</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>If the applicable cancellation terms do not expressly specify a refundable amount or the method for calculating the refund, the amount eligible for refund will be determined based on the actual amounts refundable by the relevant service provider, after deducting applicable cancellation charges, non-refundable amounts, taxes, service charges, and other costs already incurred.</p>
                                <p>Where a booking has been partially utilised, the value of the services already utilised may be deducted when calculating any applicable refund. The calculation will be based on the actual terms and charges applicable to the booking and the relevant service provider and not necessarily on a pro rata basis.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Processing Turnaround & Payment Method</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Requests for permitted changes or cancellations will normally be processed within <strong>2 working days</strong> after receipt of the complete request and settlement of all payments or charges required to process the requested change or cancellation.</p>
                                <p>Where a refund is approved, the eligible refund amount, after deduction of applicable cancellation charges and other permitted deductions, will normally be processed to the same payment method used for the original transaction, subject to the payment gateway, bank, and service provider procedures.</p>
                                <p>Once SkyPlanet Voyages Private Limited initiates the refund, the time taken for the amount to reflect in the customer's bank account or payment card may vary depending on the payment gateway, issuing bank, or financial institution. SkyPlanet Voyages Private Limited shall not be responsible for delays caused by banks, payment gateways, or third-party service providers.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 3: CANCELLATION & REFUND POLICY
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'cancellation') ? 'active' : ''; ?>" id="tab-cancellation">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                            <div>
                                <h1>Cancellation & Refund Policy</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Supplier Rules & Refund Timelines &bull; SkyPlanet Voyages Pvt. Ltd.</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-cancellation')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Refund Terms</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> 2-Day Turnaround</span>
                        </div>
                        All cancellations, amendments, and refunds relating to travel services booked through SkyPlanet Voyages Private Limited are governed by supplier-driven terms, administrative fees, and applicable Indian laws.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Supplier-Driven Policies</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>All cancellations, amendments, and refunds relating to travel services booked through SkyPlanet Voyages Private Limited are subject to the applicable terms, fare rules, and cancellation policies of the respective service provider, including airlines, hotels, railways, car operators, tour operators, travel insurance providers, and other suppliers.</p>
                                <p>The cancellation and refund conditions applicable to a particular booking will be communicated at the time of booking or specified in the relevant quotation, invoice, itinerary, or booking confirmation.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Service Fees</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Service fees, convenience fees, booking fees, administrative charges, or other fees charged by SkyPlanet Voyages Private Limited are generally non-refundable unless otherwise expressly stated in writing or required under applicable Indian law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Refund Timelines & Bank Disclaimers</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Where a refund is approved, SkyPlanet Voyages Private Limited will process the eligible refund after receiving or confirming the applicable refund from the relevant supplier, where applicable.</p>
                                <p>The time taken for the refund to reach the customer's original payment method may depend on the supplier, bank, payment gateway, or financial institution. SkyPlanet Voyages Private Limited shall not be responsible for delays caused by such third parties.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Partial Refunds & No-Shows</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Where a booking or travel service has been partially utilised, any applicable refund will be calculated according to the relevant supplier's rules and the terms applicable to the booking.</p>
                                <p>In the event of a no-show, cancellation or failure to use a confirmed service, any refund will be subject to the applicable supplier's rules. Certain bookings may be completely non-refundable.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Force Majeure Events</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Refunds, rebooking, or alternative arrangements arising from force majeure events, including natural disasters, extreme weather, strikes, pandemics, government restrictions, border closures, civil disturbances, airline shutdowns, or other circumstances beyond reasonable control, will be subject to the applicable supplier policies and applicable Indian law.</p>
                                <p>SkyPlanet Voyages Private Limited does not guarantee a refund where the relevant supplier does not provide a refund.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> No Agency or Legal Relationship</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Nothing contained in these Terms shall be construed as creating a partnership, joint venture, or fiduciary relationship between SkyPlanet Voyages Private Limited and any customer or third-party service provider.</p>
                                <p>SkyPlanet Voyages Private Limited may act as a travel facilitator, intermediary, or booking agent for certain third-party travel services. The actual provision of such services remains subject to the terms and conditions of the respective airline, hotel, transport operator, insurer, tour operator, or other service provider.</p>
                                <p>Nothing in this section shall exclude or limit any rights, obligations, or liabilities that cannot legally be excluded or limited under applicable Indian law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Client Acknowledgement & Acceptance Clause</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>By accessing, registering on, making a booking through, or using the website, booking platform, or travel-related services of SkyPlanet Voyages Private Limited (“SkyPlanet’s Voyogo”), the customer/client hereby:</p>
                                <ul>
                                    <li>Confirms that they have read, understood, and accepted these Terms, Policies, and Disclaimers and agree to comply with them.</li>
                                    <li>Acknowledges that SkyPlanet Voyages Private Limited may act as a travel facilitator, intermediary, or booking agent for certain travel services provided by third-party suppliers.</li>
                                    <li>Agrees that any booking, transaction, or use of the services by the customer or an authorised representative shall constitute acceptance of these Terms and Conditions on behalf of the customer or organisation, where applicable.</li>
                                    <li>Acknowledges that the liability of SkyPlanet Voyages Private Limited shall be subject to the terms and limitations stated in the applicable booking conditions and these Terms, to the extent permitted under applicable Indian law.</li>
                                    <li>Confirms that these Terms and Conditions, together with the applicable quotation, invoice, itinerary, and booking confirmation, shall be binding on the customer, subject to applicable Indian law.</li>
                                </ul>
                                <p>In the event of any conflict between these Terms and Conditions and a separate written agreement expressly executed between SkyPlanet Voyages Private Limited and the customer, the terms of such written agreement shall prevail only to the extent of the conflict, unless otherwise required by applicable law.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 4: FLIGHT CANCELLATION & RESCHEDULING POLICY
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'flight-policy') ? 'active' : ''; ?>" id="tab-flight-policy">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-plane-circle-exclamation"></i></div>
                            <div>
                                <h1>Flight Cancellation, Refund & Rescheduling Policy</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Carrier Rules & Fare Conditions &bull; SkyPlanet's Voyogo</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-flight-policy')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Air Passenger Guidelines</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> Domestic & International</span>
                        </div>
                        Flight bookings are subject to individual carrier fare rules, government aviation directives, and specific airline cancellation matrices. Please read the terms below carefully.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-plane-departure prefix-icon"></i> Flight Cancellation: Within vs More than 24 Hours</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Please note that flight cancellations are subject to the cancellation rules, fare conditions, and applicable charges of the respective airline.</p>
                                <div class="p-callout warning">
                                    <strong>Cancellation Within 24 Hours of Departure:</strong> For cancellation requests made within 24 hours of the scheduled departure, the customer may be required to contact the respective airline directly, depending on the airline's applicable policy. After cancellation, the relevant confirmation or supporting document may be shared with SkyPlanet Voyages Private Limited through the official support channel for processing of any eligible refund.
                                </div>
                                <p><strong>Cancellation More Than 24 Hours Before Departure:</strong> Cancellation requests made more than 24 hours before the scheduled departure may be submitted to SkyPlanet Voyages Private Limited through the official customer-support channel. The request will be processed according to the applicable airline fare rules and booking conditions.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-receipt prefix-icon"></i> Airline Charges & Voyogo Cancellation Service Fee</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Every flight booking made through SkyPlanet's Voyogo is subject to the cancellation charges imposed by the respective airline. Such charges may vary depending on the airline, route, fare type, booking class, and time of cancellation. Certain fares may be non-refundable, subject to the applicable airline fare rules.</p>
                                <p>Where applicable, SkyPlanet Voyages Private Limited may charge a service or administrative fee for processing cancellation requests. The applicable fee, if any, will be communicated at the time of cancellation and may vary depending on the type of booking and service.</p>
                                <p>Any applicable airline cancellation charges, fare restrictions, taxes, or other supplier charges will be additional and will be deducted from the eligible refund.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-rotate-left prefix-icon"></i> Flight Refund Policy & Airline Disruption / Delays</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Where a refund is applicable, SkyPlanet Voyages Private Limited will process the eligible refund after the required information and supporting documents have been received and, where applicable, after confirmation or receipt of the refund from the airline.</p>
                                <p>The refund amount will be subject to the applicable airline fare rules, cancellation charges, non-refundable amounts, and SkyPlanet's Voyogo applicable service charges.</p>
                                <p>The time required for a refund to be credited may vary depending on the airline, payment gateway, bank, or financial institution. SkyPlanet's Voyogo shall not be responsible for delays caused by such third parties.</p>
                                <div class="p-callout">
                                    <strong>Flight Cancellation or Delay by Airline:</strong> In the event of an airline cancellation or significant flight disruption, the customer may be required to provide the relevant airline confirmation or other supporting documentation where required for processing the refund. Any refund will be processed according to the airline's applicable policy. Where the airline provides a full refund, SkyPlanet Voyages Private Limited will process the eligible amount after applicable deductions, if any.
                                </div>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-person-circle-xmark prefix-icon"></i> Partial Sectors & No-Show Penalties</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Where only part of a journey has been used and the remaining sector or service is eligible for a refund, the refund will be calculated according to the applicable airline fare rules and booking conditions.</p>
                                <p>In case of a no-show, any refund will be subject to the applicable airline fare rules. Certain fares may be completely non-refundable.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-calendar-days prefix-icon"></i> Flight Rescheduling & Route Changes (Domestic & International)</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>All flight date, time, route, or passenger-related changes are subject to the applicable airline's fare rules, availability, and change conditions.</p>
                                <p>Customers should submit rescheduling or amendment requests to SkyPlanet Voyages Private Limited through the official customer-support channel as early as possible before the scheduled departure.</p>
                                <p>Requests received close to the departure time may need to be handled directly with the respective airline, depending on the airline's applicable rules and operational requirements.</p>
                                <ul>
                                    <li><strong>Domestic Flights:</strong> For domestic flight changes or rescheduling, requests should preferably be submitted to SkyPlanet's Voyogo before the applicable airline deadline specified in the booking conditions. If the request is made after the applicable deadline or too close to departure, the customer may be required to contact the respective airline directly.</li>
                                    <li><strong>International Flights:</strong> For international flight changes or rescheduling, requests should preferably be submitted to SkyPlanet’s Voyogo before the applicable airline deadline specified in the booking conditions. Different airlines and fare types may have different advance-notice requirements. Customers should refer to the applicable booking confirmation or fare rules for the exact deadline.</li>
                                </ul>
                                <p><strong>Rescheduling Charges:</strong> All permitted changes or rescheduling requests may be subject to:</p>
                                <ul>
                                    <li>Airline change or rescheduling charges;</li>
                                    <li>Applicable fare difference;</li>
                                    <li>Taxes or government charges;</li>
                                    <li>SkyPlanet Voyages Private Limited service or administrative charges, where applicable; and</li>
                                    <li>Any other charges imposed under the applicable airline fare rules.</li>
                                </ul>
                                <p>The applicable charges will be communicated before the change is processed, wherever reasonably possible.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 5: TERMS OF USE
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'terms-of-use') ? 'active' : ''; ?>" id="tab-terms-of-use">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                            <div>
                                <h1>Terms of Use</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Legally Binding User Contract &bull; SkyPlanet Voyages Pvt. Ltd.</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-terms-of-use')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> User Agreement</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> All Website Users</span>
                        </div>
                        <strong>Please Read These Terms of Use Carefully — This Is a Binding Contract.</strong> The terms "we," "us," and “our” refer to SkyPlanet Voyages Private Limited, and "you," "your," or “customer” refers to any person accessing, registering on, or using our services.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Binding Contract & Modifications</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>These Terms of Use govern your access to and use of the website, booking platform and travel-related services provided by SkyPlanet Voyages Private Limited. By accessing or using our website or services, you agree to comply with these Terms of Use and the applicable booking terms, policies, and disclaimers.</p>
                                <p>SkyPlanet Voyages Private Limited reserves the right to modify, update, or amend these Terms of Use from time to time, subject to applicable Indian law. The updated terms will be effective from the date of publication or from the date specified in the revised terms. Your continued use of the website or services after such changes may constitute acceptance of the revised Terms of Use.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Information Available on This Website</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>The information published on the SkyPlanet Voyages Private Limited website is intended for general travel and informational purposes. We make reasonable efforts to keep the information accurate and up-to-date; however, travel prices, availability, schedules, itineraries, hotel information, photographs, facilities, and other service details may change due to supplier or operational requirements.</p>
                                <p>Information displayed on the website should not be considered a guarantee or warranty of the availability, quality, suitability, or price of any particular travel product or service unless specifically confirmed by SkyPlanet Voyages Private Limited.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Permitted Use & Speculative Bookings Prohibition</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>This website is intended for legitimate personal and travel-related use. You agree not to misuse the website, its content, booking facilities, or services for any unlawful, fraudulent, or unauthorised purpose.</p>
                                <p>You may view and download your booking confirmations, itineraries, tickets, and other travel documents for your personal travel requirements. However, without prior written permission from SkyPlanet Voyages Private Limited, you may not copy, reproduce, modify, distribute, transmit, publish, commercially exploit, resell, or create derivative works from the content, information, or services available through this website, except where permitted by applicable law.</p>
                                <p>You agree not to use the website to make speculative, fraudulent, false, duplicate, or unauthorised bookings, or bookings made with the intention of abusing supplier policies or promotional offers.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Age Capacity (18+), Passenger Data & Fee Responsibilities</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You represent that you are at least 18 years of age and have the legal capacity to enter into a binding agreement and use the website and services. Where a booking is made on behalf of another person, you confirm that you are authorised to provide the required information and make the booking on their behalf.</p>
                                <p>You are responsible for ensuring that all information provided by you, including passenger names, contact details, passport information, travel dates, and other booking information, is accurate and complete.</p>
                                <p>You shall be responsible for all applicable booking charges, service fees, taxes, government charges, and other amounts arising from your use of the website or purchase of our services.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Limitation of Liability & Indemnity</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Travel services may be provided by independent third-party suppliers such as airlines, hotels, transport operators, tour operators, and other travel service providers. Their services are subject to their respective terms and conditions.</p>
                                <p>SkyPlanet Voyages Private Limited shall not be responsible for circumstances beyond its reasonable control, including supplier delays, cancellations, schedule changes, strikes, natural disasters, government restrictions, or other force majeure events, except to the extent liability cannot legally be excluded or limited under applicable Indian law.</p>
                                <p>To the extent permitted by applicable Indian law, you agree to indemnify and hold harmless SkyPlanet Voyages Private Limited, its directors, officers, employees, and authorised representatives from claims, losses, damages, liabilities, penalties, costs, or reasonable legal expenses arising from your breach of these Terms of Use, violation of law, or unauthorised use.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Intellectual Property, Software & Dispute Resolution</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Unless otherwise stated, the content available on the SkyPlanet Voyages Private Limited website, including text, graphics, photographs, logos, designs, trademarks, layouts, documents, and other materials, is owned by or licensed to SkyPlanet Voyages Private Limited and is protected by applicable intellectual property laws.</p>
                                <p>Any dispute, difference, or claim arising out of or relating to these Terms of Use or the services provided by SkyPlanet Voyages Private Limited shall first be attempted to be resolved through good-faith communication and the company's applicable grievance process.</p>
                                <p>Subject to applicable consumer protection laws and other mandatory legal remedies, disputes may be subject to the jurisdiction of the competent courts having lawful jurisdiction over the matter and the company.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 6: PRIVACY POLICY
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'privacy') ? 'active' : ''; ?>" id="tab-privacy">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-user-shield"></i></div>
                            <div>
                                <h1>Privacy Policy</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Data Protection & Privacy Compliance &bull; SkyPlanet's Voyogo</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-privacy')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Privacy Statement</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> Indian IT Act Compliant</span>
                        </div>
                        SkyPlanet Voyages Private Limited ("SkyPlanet's Voyogo", "we", "us", or “our”) respects your privacy and is committed to protecting your personal information. This Privacy Policy explains how we collect, use, store, and protect information provided by you.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> General Browsing & Automated Technical Data</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You may browse the website of SkyPlanet Voyages Private Limited without directly identifying yourself or providing personal information. However, when you visit our website, certain technical information such as your IP address, browser type, device information, pages visited, and general website usage data may be collected automatically. We may use this information to understand website traffic, improve website performance, enhance user experience, and maintain the security of our website.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Collection of Personal Information</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>When you make an inquiry, create an account, purchase a travel service, or request assistance from SkyPlanet Voyages Private Limited, we may collect information such as your name, mobile number, email address, postal address, passport and travel document details, travel dates, destinations, booking preferences, and other information required to arrange your travel services.</p>
                                <p>Payment and transaction-related information may also be processed when you make a purchase through our website or authorised payment channels. Providing certain personal information may be necessary for us to complete your booking, process your request, provide customer support, and arrange the travel services requested by you.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Cookies & Web Technologies</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Our website may use cookies and similar technologies to remember your preferences, understand how visitors use our website, and improve the services provided by SkyPlanet Voyages Private Limited. Cookies may also help us provide relevant content and measure the effectiveness of our website and marketing activities.</p>
                                <p>Most web browsers allow you to control or disable cookies through browser settings. However, disabling certain cookies may affect the functionality or availability of some features on our website.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Use and Sharing of Information</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>SkyPlanet Voyages Private Limited may use the information provided by you to process and manage your travel bookings, arrange flights, hotels, transportation, tours, and other travel services; provide visa or travel assistance; process payments and refunds; respond to enquiries and customer-service requests; send booking confirmations and important travel updates; improve our website and services; prevent fraud or misuse; and comply with applicable legal and regulatory requirements.</p>
                                <p>We may share your personal information with airlines, hotels, tour operators, transport providers, visa service providers, travel insurance providers, payment gateways, technology providers, and other service providers where such sharing is reasonably necessary to provide the travel services requested by you.</p>
                                <p>We may also disclose information to government authorities, regulatory bodies, law enforcement agencies, or other parties where required or permitted by applicable law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Security, Data Retention & Children's Privacy</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>The security and confidentiality of your personal information are important to SkyPlanet Voyages Private Limited. We take reasonable technical and organisational measures to protect your personal information against unauthorized access, misuse, alteration, disclosure, or loss.</p>
                                <p>SkyPlanet Voyages Private Limited may retain your personal information for as long as reasonably necessary to provide our services, maintain booking and transaction records, comply with legal and regulatory obligations, resolve disputes, prevent fraud, and enforce applicable agreements.</p>
                                <p>Our website and services are primarily intended for adults. Where travel arrangements involve children or minors, their personal information may be provided by or collected with the involvement of a parent or lawful guardian where necessary to complete the requested travel arrangements.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Privacy Rights & Contact Details</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Subject to applicable Indian law, you may have rights relating to your personal information, including requesting access to or correction of certain information and raising concerns regarding its processing.</p>
                                <p>If you have any questions, concerns, or requests regarding this Privacy Policy or the handling of your personal information, you may contact:</p>
                                <div class="p-callout">
                                    <strong>SkyPlanet Voyages Private Limited</strong><br>
                                    Email: <a href="mailto:support@voyogos.com" style="color: #0284c7; font-weight: 600;">Support@voyogos.com</a><br>
                                    Helpline: +91 8098999096 / +91 8098999095
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 7: DISCLAIMER
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'disclaimer') ? 'active' : ''; ?>" id="tab-disclaimer">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div>
                                <h1>Disclaimer</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Limitation of Warranties & Notices &bull; SkyPlanet's Voyogo</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-disclaimer')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Legal Notice</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> Read Before Booking</span>
                        </div>
                        We have made reasonable commercial efforts to ensure that the information published on the SkyPlanet Voyages Private Limited website is accurate and up to date. Please review our liability, third-party, and service disclaimers below.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Accuracy of Information & Service Availability</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>We have made reasonable commercial efforts to ensure that the information published on the SkyPlanet Voyages Private Limited website is accurate and up to date. However, we do not warrant that all information, prices, availability, schedules, descriptions, photographs, hotel facilities, travel products, or services displayed on the website will always be complete, accurate, or error-free.</p>
                                <p>Travel prices, availability, airline schedules, hotel facilities, itineraries, and other travel service information may change without prior notice due to changes made by airlines, hotels, tour operators, transport providers, government authorities, or other third-party service providers. Any prices and service details displayed on the website are subject to availability and final confirmation at the time of booking.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Independent Third-Party Service Providers</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Airlines, hotels, transport operators, tour operators, visa service providers, insurance companies, and other travel suppliers are independent third-party service providers. SkyPlanet Voyages Private Limited is not responsible for their acts, errors, omissions, delays, cancellations, representations, service failures, or negligence, except where liability cannot lawfully be excluded under applicable Indian law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Force Majeure & Uncontrollable Circumstances</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>SkyPlanet Voyages Private Limited shall not be responsible for any delay, cancellation, overbooking, strike, natural disaster, extreme weather, pandemic, government restriction, border closure, civil disturbance, technical disruption, change of schedule, or other circumstances beyond our reasonable control. Any applicable refund, rebooking, or compensation will be subject to the relevant booking terms, supplier policies, and applicable law.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Visa Approval & Immigration Clearance</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Visa approval, immigration clearance, and entry into any country are solely determined by the relevant government, embassy, consulate, or immigration authority. SkyPlanet Voyages Private Limited may provide visa assistance where applicable but does not guarantee visa approval or entry.</p>
                                <p>Customers are responsible for reviewing the applicable booking terms, travel requirements, and documentation before travelling.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-circle-check prefix-icon"></i> Third-Party Links & Consumer Rights</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>This website may contain links to third-party websites or external resources. SkyPlanet Voyages Private Limited does not control or guarantee the accuracy, content, availability, or security of such external websites. Any use of third-party websites is at the customer's own discretion.</p>
                                <p>Nothing in this disclaimer shall exclude or restrict any consumer right or legal remedy that cannot lawfully be excluded or restricted under applicable Indian law, including applicable consumer protection laws.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ==========================================================
                     TAB 8: HELP & FAQS
                     ========================================================== -->
                <div class="tab-content-pane <?php echo ($active_tab === 'faq') ? 'active' : ''; ?>" id="tab-faq">
                    <div class="policy-header-row">
                        <div class="policy-title-block">
                            <div class="policy-title-icon"><i class="fa-solid fa-circle-question"></i></div>
                            <div>
                                <h1>Help & FAQs</h1>
                                <span class="policy-subtitle-pill"><i class="fa-solid fa-circle-check"></i> Knowledgebase & Partner Support &bull; Voyogo</span>
                            </div>
                        </div>
                        <div class="policy-actions-row">
                            <button type="button" class="btn-toggle-all-acc" onclick="toggleAllAccordions('tab-faq')"><i class="fa-solid fa-arrows-up-down"></i> Expand All</button>
                        </div>
                    </div>

                    <div class="policy-lead-para">
                        <div class="lead-badge-row">
                            <span class="lead-chip"><i class="fa-solid fa-circle-info"></i> Frequently Asked Questions</span>
                            <span class="lead-time"><i class="fa-regular fa-clock"></i> Quick Answers</span>
                        </div>
                        Find answers to common questions about partner agent registration, account balance recharge, markup management, failed bookings, cancellations, refunds, and statements.
                    </div>

                    <div class="policy-accordion-group">
                        
                        <div class="p-accordion-item open">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-user-plus prefix-icon"></i> How can I register as an authorised travel agent with SkyPlanet Voyages Private Limited?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You can register as an authorised travel agent through the “Sign Up / Register” option available on the <a href="https://voyogos.com/" target="_blank" style="color: #0284c7; font-weight: 600;">www.voyogos.com</a> platform.</p>
                                <p>After submitting the required details, our authorised representative may contact you to verify the information and complete the registration process.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-wallet prefix-icon"></i> How can I recharge my account?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You can recharge your SkyPlanet's Voyogo account using the payment methods made available on the platform, such as bank transfer, online banking, credit/debit card, or other supported payment options.</p>
                                <p>Where applicable, you may also contact our authorised representative or visit an authorised SkyPlanet office for assistance with account recharge.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-percent prefix-icon"></i> How can I set my own mark-up?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>If mark-up functionality is available for your account, you can set the applicable mark-up or transaction fee through the relevant account settings provided on the platform. The available options may vary depending on the service, booking type, and account configuration.</p>
                                <div class="p-callout success">
                                    <strong>Navigation Path:</strong> My Account &rarr; My Profile &rarr; Mark-up / Transaction Fee
                                </div>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-triangle-exclamation prefix-icon"></i> What should I do if my booking failed but my account was debited?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>If a booking appears to have failed but the amount has been debited from your account, please contact SkyPlanet Voyages Private Limited customer support before making another booking for the same passenger or itinerary.</p>
                                <p>The booking may have been successfully generated or may still be pending confirmation with the relevant supplier. <strong>Avoid creating a duplicate booking until the status has been verified.</strong></p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-ban prefix-icon"></i> How can I cancel a booking through the website?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Log in to your SkyPlanet’s Voyogo account and access the relevant booking through the <strong>Itinerary / My Bookings</strong> section. Select the applicable booking and choose the available cancellation option. Follow the instructions displayed on the platform and submit the cancellation request.</p>
                                <p>Cancellation will be subject to the applicable airline, hotel, transport operator, tour operator, or other supplier's cancellation terms and conditions.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-money-bill-transfer prefix-icon"></i> How will I receive a refund for a cancelled booking?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>Where a refund is applicable, SkyPlanet Voyages Private Limited will process the eligible refund in accordance with the applicable supplier and booking terms. The refund status may be available through your account or communicated by our customer support team.</p>
                                <p>The actual time taken for the refund to reflect may depend on the supplier, payment gateway, bank, or financial institution.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-file-invoice-dollar prefix-icon"></i> How can I view my account statement?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You can access your account or transaction statement through the relevant <strong>Account / Statement</strong> section of the Skyplanet’s Voyogo platform, where this functionality is available. Depending on the platform configuration, you may be able to view transactions for a selected date range and review booking or sales-related details.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-calendar-check prefix-icon"></i> How can I search for bookings based on the travel date?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>You can use the <strong>Travel Calendar / Booking Search</strong> option, where available, to search and view bookings according to the travel date. The available information may include customer details, booking information, travel dates, and relevant service details.</p>
                            </div>
                        </div>

                        <div class="p-accordion-item">
                            <div class="p-accordion-header">
                                <span class="p-acc-title"><i class="fa-solid fa-headset prefix-icon"></i> How can I contact SkyPlanet's Voyogo for assistance?</span>
                                <span class="p-acc-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="p-accordion-body">
                                <p>For assistance with registration, account recharge, bookings, cancellations, refunds, technical issues, or other travel-related queries, please contact the SkyPlanet Voyages Private Limited customer support team through the official contact details provided on our website:</p>
                                <ul>
                                    <li><strong>Helpline:</strong> +91 8098999096 / +91 8098999095</li>
                                    <li><strong>Support Email:</strong> Support@voyogos.com</li>
                                    <li><strong>Website:</strong> <a href="https://voyogos.com" target="_blank" style="color:#0284c7;">https://voyogos.com/</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- ==========================================================================
     INTERACTIVE JAVASCRIPT FOR SIDEBAR TABS & ACCORDION DROPDOWNS
     ========================================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Sidebar Tab Switching
    const navLinks = document.querySelectorAll('.side-nav-link[data-target]');
    const contentPanes = document.querySelectorAll('.tab-content-pane');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            if (!targetId) return;

            // Remove active from all links & panes
            navLinks.forEach(l => l.classList.remove('active'));
            contentPanes.forEach(p => p.classList.remove('active'));

            // Activate clicked
            this.classList.add('active');
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }

            // Update URL hash without jumping
            const slug = targetId.replace('tab-', '');
            if (history.pushState) {
                history.pushState(null, null, '#tab-' + slug);
            }

            // Scroll horizontal tab strip and main content on small screens
            if (window.innerWidth <= 900) {
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                const mainContent = document.querySelector('.policy-content-card');
                if (mainContent) {
                    mainContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // 2. Handle direct URL hash on page load
    const hash = window.location.hash;
    if (hash && hash.startsWith('#tab-')) {
        const matchingLink = document.querySelector(`.side-nav-link[data-target="${hash.substring(1)}"]`);
        if (matchingLink) {
            matchingLink.click();
        }
    } else {
        // Center default active tab on load
        const activeLink = document.querySelector('.side-nav-link.active');
        if (activeLink && window.innerWidth <= 900) {
            activeLink.scrollIntoView({ behavior: 'auto', block: 'nearest', inline: 'center' });
        }
    }

    // 3. Accordion Dropdown Toggle (Dropdown Auro)
    document.querySelectorAll('.p-accordion-header').forEach(header => {
        header.addEventListener('click', function() {
            const item = this.closest('.p-accordion-item');
            if (item) {
                item.classList.toggle('open');
            }
        });
    });

    // 4. Quick Search / Filter inside active tab
    const searchInput = document.getElementById('policyFilterInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const activePane = document.querySelector('.tab-content-pane.active');
            if (!activePane) return;

            const accordionItems = activePane.querySelectorAll('.p-accordion-item');
            accordionItems.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (!query || text.includes(query)) {
                    item.style.display = 'block';
                    if (query && !item.classList.contains('open')) {
                        item.classList.add('open'); // auto open matching result
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

});

// 5. Expand All / Collapse All Helper
function toggleAllAccordions(paneId) {
    const pane = document.getElementById(paneId);
    if (!pane) return;

    const items = pane.querySelectorAll('.p-accordion-item');
    const allOpen = Array.from(items).every(it => it.classList.contains('open'));

    items.forEach(it => {
        if (allOpen) {
            it.classList.remove('open');
        } else {
            it.classList.add('open');
        }
    });

    const btn = pane.querySelector('.btn-toggle-all-acc');
    if (btn) {
        btn.innerHTML = allOpen 
            ? '<i class="fa-solid fa-arrows-up-down"></i> Expand All' 
            : '<i class="fa-solid fa-arrows-up-down"></i> Collapse All';
    }
}
</script>
