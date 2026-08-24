<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Admin_model');
        $this->CI->load->library('email');
    }

    /**
     * Configure CI Email library from Database Settings
     */
    private function initialize_config() {
        $settings = $this->CI->Admin_model->get_email_settings();

        $smtp_host = $settings['smtp_host'];
        if (!empty($settings['smtp_crypto']) && strtolower($settings['smtp_crypto']) === 'ssl' && strpos($smtp_host, 'ssl://') === false) {
            $smtp_host = 'ssl://' . $smtp_host;
        }

        $config = array(
            'protocol'    => !empty($settings['smtp_host']) ? 'smtp' : 'mail',
            'smtp_host'   => $smtp_host,
            'smtp_port'   => (int)$settings['smtp_port'],
            'smtp_user'   => $settings['smtp_user'],
            'smtp_pass'   => $settings['smtp_pass'],
            'smtp_crypto' => $settings['smtp_crypto'],
            'smtp_timeout'=> 3,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'wordwrap'    => TRUE,
            'newline'     => "\r\n",
            'crlf'        => "\r\n"
        );

        $this->CI->email->initialize($config);
        return $settings;
    }

    /**
     * Send Flight Confirmation E-Ticket Email
     */
    public function send_flight_ticket($booking) {
        $settings = $this->initialize_config();
        
        if (empty($booking['contact_email'])) return false;

        $subject = "Flight Booking Confirmed! Ref: " . $booking['booking_ref'] . " - Voyogo";

        $passengerData = json_decode($booking['passenger_details'], true);
        $passengerNames = array();
        if (is_array($passengerData)) {
            foreach ($passengerData as $p) {
                if (!empty($p['name'])) $passengerNames[] = $p['name'];
            }
        }
        $passStr = !empty($passengerNames) ? implode(', ', $passengerNames) : $booking['contact_name'];

        $html = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #ffffff;">
            <div style="background: linear-gradient(135deg, #09204b, #0d3470); color: #ffffff; padding: 24px; text-align: center;">
                <h2 style="margin: 0; font-size: 24px;">Voyogo Flight E-Ticket</h2>
                <p style="margin: 5px 0 0 0; color: #38ef7d; font-weight: bold;">Booking Status: CONFIRMED</p>
            </div>
            <div style="padding: 24px;">
                <p>Dear <strong>' . htmlspecialchars($booking['contact_name']) . '</strong>,</p>
                <p>Thank you for booking with Voyogo. Your flight ticket is confirmed. Details are below:</p>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background: #f9fbfd; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Booking Reference</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #0d3470; font-weight: bold;">' . htmlspecialchars($booking['booking_ref']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">PNR</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold;">' . htmlspecialchars($booking['pnr']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Airline & Flight</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($booking['airline_name']) . ' (' . htmlspecialchars($booking['flight_number']) . ')</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Route</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($booking['origin']) . ' to ' . htmlspecialchars($booking['destination']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Date & Time</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . date('D, d M Y - H:i', strtotime($booking['departure_datetime'])) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Passengers</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($passStr) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold;">Total Amount Paid</td>
                        <td style="padding: 10px 14px; color: #16a34a; font-weight: bold;">₹ ' . number_format($booking['total_amount'], 2) . '</td>
                    </tr>
                </table>

                <p style="margin-top: 20px; font-size: 13px; color: #64748b;">Please bring a valid Government Photo ID for airport check-in along with this e-ticket confirmation.</p>
            </div>
            <div style="background: #f1f5f9; padding: 14px; text-align: center; font-size: 12px; color: #64748b;">
                &copy; ' . date('Y') . ' Voyogo Travel Technologies. All rights reserved.
            </div>
        </div>';

        $this->CI->email->from($settings['from_email'], $settings['from_name']);
        $this->CI->email->to($booking['contact_email']);
        $this->CI->email->subject($subject);
        $this->CI->email->message($html);

        return @$this->CI->email->send();
    }

    /**
     * Send Hotel Booking Confirmation Voucher Email
     */
    public function send_hotel_voucher($booking) {
        $settings = $this->initialize_config();

        if (empty($booking['guest_email'])) return false;

        $subject = "Hotel Booking Confirmed! Voucher Ref: " . $booking['booking_ref'] . " - Voyogo";

        $html = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #ffffff;">
            <div style="background: linear-gradient(135deg, #09204b, #fa3a3a); color: #ffffff; padding: 24px; text-align: center;">
                <h2 style="margin: 0; font-size: 24px;">Voyogo Hotel Booking Voucher</h2>
                <p style="margin: 5px 0 0 0; color: #38ef7d; font-weight: bold;">Booking Status: CONFIRMED</p>
            </div>
            <div style="padding: 24px;">
                <p>Dear <strong>' . htmlspecialchars($booking['primary_guest_name']) . '</strong>,</p>
                <p>Your hotel reservation is confirmed! Here are your voucher details:</p>
                
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background: #f9fbfd; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Voucher ID</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; color: #0d3470; font-weight: bold;">' . htmlspecialchars($booking['booking_ref']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Hotel Name</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-weight: bold;">' . htmlspecialchars($booking['hotel_name']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Room Category</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($booking['room_type']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Check-In Date</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . date('D, d M Y', strtotime($booking['checkin_date'])) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Check-Out Date</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . date('D, d M Y', strtotime($booking['checkout_date'])) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0;">Primary Guest</td>
                        <td style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($booking['primary_guest_name']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; font-weight: bold;">Total Paid</td>
                        <td style="padding: 10px 14px; color: #16a34a; font-weight: bold;">₹ ' . number_format($booking['total_amount'], 2) . '</td>
                    </tr>
                </table>

                <p style="margin-top: 20px; font-size: 13px; color: #64748b;">Please present this hotel voucher and photo ID at the reception desk during check-in.</p>
            </div>
            <div style="background: #f1f5f9; padding: 14px; text-align: center; font-size: 12px; color: #64748b;">
                &copy; ' . date('Y') . ' Voyogo Travel Technologies. All rights reserved.
            </div>
        </div>';

        $this->CI->email->from($settings['from_email'], $settings['from_name']);
        $this->CI->email->to($booking['guest_email']);
        $this->CI->email->subject($subject);
        $this->CI->email->message($html);

        return @$this->CI->email->send();
    }
}
