<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Policy Controller
 * Handles unified legal, policies, terms, disclaimers, FAQs, and support pages
 * with sidebar tab navigation and accordion dropdowns matching the portal design.
 */
class Policy extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
    }

    public function index($tab = 'terms') {
        $allowed_tabs = array(
            'terms'               => 'Terms & Conditions',
            'change-cancellation' => 'Change & Cancellation Policy',
            'cancellation'        => 'Cancellation & Refund Policy',
            'flight-policy'       => 'Flight Cancellation & Rescheduling',
            'terms-of-use'        => 'Terms of Use',
            'privacy'             => 'Privacy Policy',
            'disclaimer'          => 'Disclaimer',
            'faq'                 => 'Help & FAQs',
            'branches'            => 'Branches & Bank Accounts',
            'support'             => 'Support Center'
        );

        $tab = strtolower(trim((string)$tab));

        // Map common aliases
        if ($tab === 'terms-and-conditions' || $tab === 'terms-conditions' || $tab === 'terms_and_conditions') {
            $tab = 'terms';
        } elseif ($tab === 'privacy-policy' || $tab === 'privacy_policy') {
            $tab = 'privacy';
        } elseif ($tab === 'refund' || $tab === 'refund-policy' || $tab === 'refund_policy' || $tab === 'cancellation-policy') {
            $tab = 'cancellation';
        } elseif ($tab === 'flight' || $tab === 'flight-policy' || $tab === 'flight-cancellation' || $tab === 'flight-cancellation-policy') {
            $tab = 'flight-policy';
        } elseif ($tab === 'terms-use' || $tab === 'terms_of_use') {
            $tab = 'terms-of-use';
        } elseif ($tab === 'faqs' || $tab === 'help') {
            $tab = 'faq';
        } elseif ($tab === 'bank' || $tab === 'bank-accounts' || $tab === 'bank_accounts') {
            $tab = 'branches';
        } elseif ($tab === 'support-center' || $tab === 'contact') {
            $tab = 'support';
        }

        if (!array_key_exists($tab, $allowed_tabs)) {
            $tab = 'terms';
        }

        $data['active_tab']  = $tab;
        $data['page_title']  = $allowed_tabs[$tab] . " - SkyPlanet's Voyogo";
        $data['title']       = $allowed_tabs[$tab] . " - SkyPlanet's Voyogo";
        $data['active_page'] = 'policy';

        $this->load->view('includes/header', $data);
        $this->load->view('policy', $data);
        $this->load->view('includes/footer', $data);
    }
}
