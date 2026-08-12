<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* Voyogo Custom Flight Routes */
$route['flight'] = 'welcome/index';
$route['flight/search'] = 'welcome/search_flights';
$route['flight/review'] = 'welcome/flight_review';
$route['flight/process_payment'] = 'welcome/process_flight_payment';
$route['flight/confirmation/(:any)'] = 'welcome/flight_confirmation/$1';

/* Voyogo Custom Hotel Routes */
$route['hotels'] = 'welcome/hotels';
$route['hotels/search'] = 'welcome/search_hotels';
$route['hotels/detail/(:any)'] = 'welcome/hotel_detail/$1';
$route['hotels/review'] = 'welcome/hotel_review';
$route['hotels/process_payment'] = 'welcome/process_hotel_payment';
$route['hotels/confirmation/(:any)'] = 'welcome/hotel_confirmation/$1';

/* Voyogo Super Admin Routes */
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';
$route['admin/logout'] = 'admin/logout';
$route['admin/flight_bookings'] = 'admin/manage_flight_bookings';
$route['admin/hotel_bookings'] = 'admin/manage_hotel_bookings';
$route['admin/enquiries'] = 'admin/enquiries';
$route['admin/email_settings'] = 'admin/email_settings';
$route['admin/setup_db'] = 'admin/setup_db';
