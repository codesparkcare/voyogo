<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migrate Controller
 * 
 * Runs database migrations automatically.
 * 
 * HOW TO USE:
 * -----------
 * After deploying (Pull + Deploy), visit this URL once:
 *   https://voyogos.com/migrate
 * 
 * Or call it via command line on the server:
 *   php index.php migrate
 * 
 * It will apply any new migration files that were pushed via Git.
 * 
 * SECURITY:
 * ---------
 * This controller uses a secret key to prevent unauthorized access.
 * You can also restrict it by IP or remove it entirely after setup.
 */
class Migrate extends CI_Controller {

    /**
     * Run all pending migrations to latest version
     */
    public function index()
    {
        // Security: Only allow from server CLI or with secret key
        $secret_key = 'voyogo_migrate_2026'; // Change this to your own secret
        
        $is_cli = $this->input->is_cli_request();
        $has_key = ($this->input->get('key') === $secret_key);
        
        if (!$is_cli && !$has_key) {
            show_404();
            return;
        }

        $this->load->library('migration');

        if ($this->migration->latest() === FALSE) {
            $error = $this->migration->error_string();
            if ($is_cli) {
                echo "Migration FAILED:\n" . $error . "\n";
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $error
                ]);
            }
        } else {
            // Get current version
            $version = $this->migration->find_migrations();
            $keys = array_keys($version);
            $latest_key = end($keys);

            if ($is_cli) {
                echo "Migrations completed successfully!\n";
                echo "Current version: " . $latest_key . "\n";
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Database is up to date!',
                    'version' => $latest_key
                ]);
            }
        }
    }

    /**
     * Show migration status
     */
    public function status()
    {
        $secret_key = 'voyogo_migrate_2026';
        
        $is_cli = $this->input->is_cli_request();
        $has_key = ($this->input->get('key') === $secret_key);
        
        if (!$is_cli && !$has_key) {
            show_404();
            return;
        }

        $this->load->library('migration');
        $migrations = $this->migration->find_migrations();
        
        echo "<h3>Available Migrations:</h3><ul>";
        foreach ($migrations as $version => $file) {
            echo "<li>{$version} - " . basename($file) . "</li>";
        }
        echo "</ul>";
    }
}
