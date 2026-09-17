<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    /**
     * AJAX endpoint to complete Firebase OTP verification and establish session
     */
    public function verify_firebase_login()
    {
        // Respond as JSON
        $this->output->set_content_type('application/json');

        if ($this->input->method() !== 'post') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method.']);
            return;
        }

        $phone = $this->input->post('phone', TRUE);
        $firebase_uid = $this->input->post('firebase_uid', TRUE);

        if (empty($phone)) {
            echo json_encode(['status' => false, 'message' => 'Phone number is required.']);
            return;
        }

        // Clean and validate phone number
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        if (strlen($cleanPhone) < 10) {
            echo json_encode(['status' => false, 'message' => 'Invalid mobile number format.']);
            return;
        }

        // Get or create user
        $user = $this->User_model->create_or_login_by_phone($cleanPhone, $firebase_uid);

        if (!$user) {
            echo json_encode(['status' => false, 'message' => 'Failed to initialize user session.']);
            return;
        }

        if ($user['status'] === 'inactive') {
            echo json_encode(['status' => false, 'message' => 'Your account has been deactivated. Please contact support.']);
            return;
        }

        $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

        // Establish session
        $this->session->set_userdata([
            'user_id'        => $user['id'],
            'user_phone'     => $user['phone'],
            'user_name'      => $fullName ?: $user['phone'],
            'user_email'     => $user['email'] ?? '',
            'user_logged_in' => TRUE
        ]);

        if (empty($user['first_name']) || empty($user['email'])) {
            $this->session->set_flashdata('welcome_notice', 'Welcome to Voyogo! Please complete your Name and Email ID to finalize your account profile.');
        }

        echo json_encode([
            'status'       => true,
            'message'      => 'Logged in successfully!',
            'redirect_url' => site_url('user/profile'),
            'user'         => [
                'id'    => $user['id'],
                'phone' => $user['phone'],
                'name'  => $fullName ?: $user['phone']
            ]
        ]);
    }

    /**
     * User Profile Page
     */
    public function profile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            $this->session->set_flashdata('error', 'Please log in with OTP to view your profile.');
            redirect(base_url());
            return;
        }

        $userId = $this->session->userdata('user_id');
        $user = $this->User_model->get_by_id($userId);

        if (!$user) {
            $this->session->sess_destroy();
            redirect(base_url());
            return;
        }

        $data['title'] = 'My Profile - Voyogo';
        $data['current_page'] = 'profile';
        $data['user'] = $user;

        $this->load->view('includes/header', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('includes/footer', $data);
    }

    /**
     * Update Profile (Phone is permanent/read-only)
     */
    public function update_profile()
    {
        if (!$this->session->userdata('user_logged_in')) {
            redirect(base_url());
            return;
        }

        $userId = $this->session->userdata('user_id');

        $firstName = trim($this->input->post('first_name', TRUE));
        $lastName  = trim($this->input->post('last_name', TRUE));
        $email     = trim($this->input->post('email', TRUE));

        // Validate email format if provided
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Please enter a valid email address.');
            redirect('user/profile');
            return;
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email
        ];

        $this->User_model->update_profile($userId, $updateData);

        // Refresh session data
        $fullName = trim($firstName . ' ' . $lastName);
        $this->session->set_userdata([
            'user_name'  => $fullName ?: $this->session->userdata('user_phone'),
            'user_email' => $email
        ]);

        $this->session->set_flashdata('success', 'Profile updated successfully!');
        redirect(base_url());
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->unset_userdata(['user_id', 'user_phone', 'user_name', 'user_email', 'user_logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect(base_url());
    }
}
