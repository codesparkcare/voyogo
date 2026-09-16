<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* Voyogo Custom Flight Routes */
$route['flight/review/(.+)'] = 'welcome/flight_review';
$route['flight/review'] = 'welcome/flight_review';
$route['flight/search'] = 'welcome/search_flights';
$route['flight/process_payment'] = 'welcome/process_flight_payment';
$route['flight/confirmation/(:any)'] = 'welcome/flight_confirmation/$1';
$route['flight'] = 'welcome/index';

/* Voyogo Custom Hotel Routes (Isolated Controller) */
$route['hotels'] = 'hotels/index';
$route['hotels/search'] = 'hotels/search';
$route['hotels/detail/(:any)'] = 'hotels/detail/$1';
$route['hotels/review'] = 'hotels/review';
$route['hotels/process_payment'] = 'hotels/process_payment';
$route['hotels/confirmation/(:any)'] = 'hotels/confirmation/$1';
$route['hotels/autosuggest'] = 'hotels/autosuggest';

/* Voyogo Pages & Services Routes */
$route['holidays'] = 'holidays';
$route['visa']     = 'visa';
$route['forex']    = 'forex';
$route['cruises']  = 'cruises';
$route['cabs']     = 'cabs';

/* Voyogo Super Admin Routes */
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';
$route['admin/logout'] = 'admin/logout';
$route['admin/flight_bookings'] = 'admin/manage_flight_bookings';
$route['admin/flight_api_settings'] = 'admin/flight_api_settings';
$route['admin/hotel_bookings'] = 'admin/manage_hotel_bookings';
$route['admin/hotel_api_settings'] = 'admin/hotel_api_settings';
$route['admin/hotel_api_logs'] = 'admin/hotel_api_logs';
$route['admin/hotel_api_logs/clear'] = 'admin/hotel_api_logs_clear';
$route['admin/enquiries'] = 'admin/enquiries';
$route['admin/email_settings'] = 'admin/email_settings';
$route['admin/razorpay_settings'] = 'admin/razorpay_settings';
$route['admin/payment_settings'] = 'admin/razorpay_settings';
$route['admin/api_logs'] = 'admin/api_logs';
$route['admin/api_logs/detail/(:num)'] = 'admin/api_log_detail/$1';
$route['admin/api_logs/export'] = 'admin/api_logs_export';
$route['admin/api_logs/clear'] = 'admin/api_logs_clear';
$route['admin/setup_db'] = 'admin/setup_db';
$route['flight_cert'] = 'flight_cert/index';
$route['cert'] = 'flight_cert/index';
$route['admin/flight_cert'] = 'flight_cert/index';

/* ==========================================================================
   Franchise Admin & Store Owner (B2B) Routes
   ========================================================================== */
// Franchise Admin
$route['franchise-admin']                         = 'Franchise_admin/index';
$route['franchise-admin/login']                   = 'Franchise_admin/login';
$route['franchise-admin/logout']                  = 'Franchise_admin/logout';
$route['franchise-admin/stores']                  = 'Franchise_admin/stores';
$route['franchise-admin/store_create']            = 'Franchise_admin/store_create';
$route['franchise-admin/store-create']            = 'Franchise_admin/store_create';
$route['franchise-admin/store_edit/(:num)']       = 'Franchise_admin/store_edit/$1';
$route['franchise-admin/store-edit/(:num)']       = 'Franchise_admin/store_edit/$1';
$route['franchise-admin/store_toggle/(:num)']     = 'Franchise_admin/store_toggle/$1';
$route['franchise-admin/store-toggle/(:num)']     = 'Franchise_admin/store_toggle/$1';
$route['franchise-admin/wallets']                 = 'Franchise_admin/wallets';
$route['franchise-admin/wallet_update']           = 'Franchise_admin/wallet_update';
$route['franchise-admin/wallet-update']           = 'Franchise_admin/wallet_update';
$route['franchise-admin/bookings']                = 'Franchise_admin/bookings';

// Franchise Store Owner (B2B Portal)
$route['franchise']                               = 'Franchise/index';
$route['franchise/login']                         = 'Franchise/login';
$route['franchise/logout']                        = 'Franchise/logout';
$route['franchise/flight']                        = 'Franchise/flight';
$route['franchise/flight_search']                 = 'Franchise/flight_search';
$route['franchise/flight_review']                 = 'Franchise/flight_review';
$route['franchise/flight_book']                   = 'Franchise/flight_book';
$route['franchise/flight_ticket/(:any)']          = 'Franchise/flight_ticket/$1';
$route['franchise/hotel']                         = 'Franchise/hotel';
$route['franchise/hotel_search']                  = 'Franchise/hotel_search';
$route['franchise/hotel_detail/(:any)']           = 'Franchise/hotel_detail/$1';
$route['franchise/hotel_review']                  = 'Franchise/hotel_review';
$route['franchise/hotel_book']                    = 'Franchise/hotel_book';
$route['franchise/hotel_voucher/(:any)']          = 'Franchise/hotel_voucher/$1';
$route['franchise/bookings']                      = 'Franchise/bookings';
$route['franchise/wallet_ledger']                 = 'Franchise/wallet_ledger';
