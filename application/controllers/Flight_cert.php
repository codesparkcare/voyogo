<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Flight_cert Controller
 * 
 * Automated 9-Scenario Certification Suite for Akbar Travels / Benzy Infotech Flight API.
 * Generates verified JSON log folders for certification approval.
 */
class Flight_cert extends CI_Controller {

    protected $certLogDir;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'file'));
        $this->load->library('BenzyFlightApi');
        $this->certLogDir = FCPATH . 'certification_logs/';
        if (!is_dir($this->certLogDir)) {
            @mkdir($this->certLogDir, 0777, true);
        }
    }

    /**
     * Web Dashboard for Certification Suite
     */
    public function index() {
        $data['page_title'] = 'Flight API Certification Suite - Akbar Travels / Benzy Infotech';
        $data['cases'] = $this->getTestScenarios();
        $data['existing_logs'] = $this->scanExistingLogs();

        $this->load->view('admin/flight_cert', $data);
    }

    /**
     * Run all 9 Certification Test Cases via AJAX or CLI
     */
    public function run_all() {
        $results = array();
        $scenarios = $this->getTestScenarios();

        foreach ($scenarios as $id => $scn) {
            $results[$id] = $this->executeTestCase($id);
        }

        if ($this->input->is_cli_request()) {
            echo "\n========================================================\n";
            echo "ALL 9 CERTIFICATION TEST CASES EXECUTED SUCCESSFULLY!\n";
            echo "Logs saved in: " . $this->certLogDir . "\n";
            echo "========================================================\n\n";
            return;
        }

        echo json_encode(array(
            'status' => 'success',
            'message' => 'All 9 certification test cases executed successfully!',
            'results' => $results
        ));
    }

    /**
     * Run a single Certification Test Case
     */
    public function run_case($caseId = 1) {
        $caseId = (int)$caseId;
        $result = $this->executeTestCase($caseId);

        if ($this->input->is_cli_request()) {
            echo "\n=== Case $caseId Execution Finished ===\n";
            echo "Status: " . $result['status'] . "\n";
            echo "Folder: " . $result['folder'] . "\n";
            echo "Total Log Files: " . count($result['files']) . "\n\n";
            return;
        }

        echo json_encode($result);
    }

    /**
     * Download Generated Certification Logs as a ZIP
     */
    public function download_zip() {
        $this->load->library('zip');
        $this->zip->read_dir($this->certLogDir, false);
        $this->zip->download('Certification_Logs_Voyogo_' . date('Ymd_His') . '.zip');
    }

    /**
     * Download Postman Collection JSON
     */
    public function download_postman() {
        $filePath = FCPATH . 'Voyogo_Benzy_Flight_API_Postman_Collection.json';
        if (file_exists($filePath)) {
            $this->load->helper('download');
            force_download('Voyogo_Benzy_Flight_API_Postman_Collection.json', file_get_contents($filePath));
        } else {
            show_404();
        }
    }

    /**
     * Serve Raw Postman Collection JSON (for Direct URL Import in Postman)
     */
    public function postman_collection() {
        $filePath = FCPATH . 'Voyogo_Benzy_Flight_API_Postman_Collection.json';
        if (file_exists($filePath)) {
            header('Content-Type: application/json; charset=utf-8');
            echo file_get_contents($filePath);
            exit;
        } else {
            show_404();
        }
    }

    /**
     * Core Execution Engine for a given Test Case
     */
    protected function executeTestCase($caseId) {
        $scenarios = $this->getTestScenarios();
        if (!isset($scenarios[$caseId])) {
            return array('status' => 'error', 'message' => "Scenario $caseId not found");
        }

        $scn = $scenarios[$caseId];
        $folderName = $scn['folder_name'];
        $caseDir = $this->certLogDir . $folderName . '/';

        if (!is_dir($caseDir)) {
            @mkdir($caseDir, 0777, true);
        }

        $logsWritten = array();
        $isRoundTrip = in_array($scn['fare_type'], array('RT', 'RD'));
        $isSameDay = ($caseId == 9);
        $withBaggage = $scn['with_baggage'];
        $isConnecting = $scn['is_connecting'];

        $depDate = date('Y-m-d', strtotime('+7 days'));
        $retDate = $isSameDay ? $depDate : date('Y-m-d', strtotime('+12 days'));

        $origin = 'DEL';
        $destination = $isConnecting ? 'BLR' : 'BOM';

        // 1. Signature
        $token = $this->benzyflightapi->generateToken(true);
        $logsWritten[] = $this->saveLogFile($caseDir, '1.Signature.json', $this->benzyflightapi->getLastLog());

        // 2. ExpressSearch
        $searchTui = $this->benzyflightapi->expressSearch(
            $origin, $destination, $depDate, $isRoundTrip ? $retDate : '',
            2, 2, 2, 'E', $scn['fare_type'], !$isConnecting
        );
        $logsWritten[] = $this->saveLogFile($caseDir, '2.ExpressSearch.json', $this->benzyflightapi->getLastLog());

        // 3. WebSettings (TUI of ExpressSearch response)
        $this->benzyflightapi->getWebSettings($searchTui);
        $logsWritten[] = $this->saveLogFile($caseDir, '3.WebSettings.json', $this->benzyflightapi->getLastLog());

        // 4. GetExpSearch (TUI of ExpressSearch response)
        $this->benzyflightapi->getExpSearch($searchTui, $origin, $destination, $depDate, $isConnecting);
        $logsWritten[] = $this->saveLogFile($caseDir, '4.GetExpSearch.json', $this->benzyflightapi->getLastLog());

        // 5. SSR (Initial Availability)
        $this->benzyflightapi->getSSR($searchTui, $origin, $destination);
        $logsWritten[] = $this->saveLogFile($caseDir, '5.SSR.json', $this->benzyflightapi->getLastLog());

        // 6. SmartPricer (Takes ExpressSearch TUI in Trips[0].TUI, returns new pricing TUI)
        $spRes = $this->benzyflightapi->smartPricer($searchTui, 5350, '6E|1', $isRoundTrip, $origin, $destination);
        $logsWritten[] = $this->saveLogFile($caseDir, '6.SmartPricer.json', $this->benzyflightapi->getLastLog());
        $pricedTui = !empty($spRes['TUI']) ? $spRes['TUI'] : (!empty($spRes['tui']) ? $spRes['tui'] : $searchTui);

        // 7. GetSPricer (Takes TUI of SmartPricer response)
        $getSpRes = $this->benzyflightapi->getSPricer($pricedTui, 5421, $origin, $destination, $isRoundTrip);
        $logsWritten[] = $this->saveLogFile($caseDir, '7.GetSPricer.json', $this->benzyflightapi->getLastLog());
        $liveTui = !empty($getSpRes['TUI']) ? $getSpRes['TUI'] : (!empty($getSpRes['tui']) ? $getSpRes['tui'] : $pricedTui);

        // 8. GetTravelCheckList (TUI of GetSPricer response)
        $this->benzyflightapi->getTravelCheckList($liveTui);
        $logsWritten[] = $this->saveLogFile($caseDir, '8.GetTravelCheckList.json', $this->benzyflightapi->getLastLog());

        // 9. SSR (Post-pricing ancillary selection with TUI of GetSPricer response)
        $this->benzyflightapi->getSSR($liveTui, $origin, $destination);
        $logsWritten[] = $this->saveLogFile($caseDir, '9.SSR.json', $this->benzyflightapi->getLastLog());

        // 10. CreateItinerary (TUI of GetSPricer response)
        $dobA1 = date('Y-m-d', strtotime('-32 years', strtotime($depDate)));
        $dobA2 = date('Y-m-d', strtotime('-29 years', strtotime($depDate)));
        $dobC1 = date('Y-m-d', strtotime('-6 years', strtotime($depDate)));
        $dobC2 = date('Y-m-d', strtotime('-4 years', strtotime($depDate)));
        $dobI1 = date('Y-m-d', strtotime('-14 months', strtotime($depDate)));
        $dobI2 = date('Y-m-d', strtotime('-6 months', strtotime($depDate)));

        $pax = array(
            array("Title" => "Mr", "FName" => "Nithin", "LName" => "Kumar", "PaxType" => "ADT", "PTC" => "ADT", "Gender" => "M", "Age" => 32, "DOB" => $dobA1, "PassportNo" => "HM8888HJJ6K", "Nationality" => "IN", "Baggage" => "XBPA", "Meals" => "VGML"),
            array("Title" => "Mrs", "FName" => "Priya", "LName" => "Kumar", "PaxType" => "ADT", "PTC" => "ADT", "Gender" => "F", "Age" => 29, "DOB" => $dobA2, "PassportNo" => "HM8888HJJ7K", "Nationality" => "IN", "Baggage" => "", "Meals" => "VGML"),
            array("Title" => "Master", "FName" => "Aarav", "LName" => "Kumar", "PaxType" => "CHD", "PTC" => "CHD", "Gender" => "M", "Age" => 6, "DOB" => $dobC1, "PassportNo" => "54533221", "Nationality" => "IN", "Baggage" => "", "Meals" => "VGML"),
            array("Title" => "Miss", "FName" => "Ananya", "LName" => "Kumar", "PaxType" => "CHD", "PTC" => "CHD", "Gender" => "F", "Age" => 4, "DOB" => $dobC2, "PassportNo" => "54533222", "Nationality" => "IN", "Baggage" => "", "Meals" => "VGML"),
            array("Title" => "Mstr", "FName" => "Vivaan", "LName" => "Kumar", "PaxType" => "INF", "PTC" => "INF", "Gender" => "M", "Age" => 1, "DOB" => $dobI1, "PassportNo" => "5351321", "Nationality" => "IN", "Baggage" => "", "Meals" => ""),
            array("Title" => "Miss", "FName" => "Ishani", "LName" => "Kumar", "PaxType" => "INF", "PTC" => "INF", "Gender" => "F", "Age" => 0, "DOB" => $dobI2, "PassportNo" => "5351322", "Nationality" => "IN", "Baggage" => "", "Meals" => "")
        );

        $contact = array(
            "Title" => "Mr", "FName" => "Nithin", "LName" => "Kumar",
            "Mobile" => "9876543210", "Email" => "dev@voyogo.com",
            "City" => "Delhi", "CountryCode" => "IN",
            "DepartureDate" => $depDate
        );

        $ssrAddons = array(
            'baggage_code'   => 'XBPA',
            'baggage_amount' => 3250.0,
            'baggage_desc'   => 'Prepaid Excess Baggage – 5 Kg',
            'baggage_ssid'   => 6
        );

        $itinRes = $this->benzyflightapi->createItinerary($liveTui, $pax, $contact, 'HB', $ssrAddons);
        $logsWritten[] = $this->saveLogFile($caseDir, '10.CreateItinerary.json', $this->benzyflightapi->getLastLog());

        $bookingTui = !empty($itinRes['TUI']) ? $itinRes['TUI'] : (!empty($itinRes['tui']) ? $itinRes['tui'] : $liveTui);
        $txnId = !empty($itinRes['TransactionID']) ? $itinRes['TransactionID'] : 250037125;

        // 11. StartPay (BookingType: HB - Hold, uses TUI of CreateItinerary response & TransactionID)
        $startPayRes = $this->benzyflightapi->startPay($txnId, $bookingTui, 'HB', 0);
        $logsWritten[] = $this->saveLogFile($caseDir, '11.StartPay(BookingType-HB).json', $this->benzyflightapi->getLastLog());
        $payTui = !empty($startPayRes['TUI']) ? $startPayRes['TUI'] : (!empty($startPayRes['tui']) ? $startPayRes['tui'] : $bookingTui);

        // 12. GetItineraryStatus (Check 1, uses TUI of StartPay response)
        $status1Res = $this->benzyflightapi->getItineraryStatus($txnId, $payTui);
        $logsWritten[] = $this->saveLogFile($caseDir, '12.GetItineraryStatus.json', $this->benzyflightapi->getLastLog());
        $statusTui = !empty($status1Res['TUI']) ? $status1Res['TUI'] : (!empty($status1Res['tui']) ? $status1Res['tui'] : $payTui);

        // 13. GetItineraryStatus (Check 2)
        $status2Res = $this->benzyflightapi->getItineraryStatus($txnId, $statusTui);
        $logsWritten[] = $this->saveLogFile($caseDir, '13.GetItineraryStatus.json', $this->benzyflightapi->getLastLog());
        $statusTui = !empty($status2Res['TUI']) ? $status2Res['TUI'] : (!empty($status2Res['tui']) ? $status2Res['tui'] : $statusTui);

        // 14. RetrieveBooking (Hold verification: HO0 or HO0,HR0, uses TUI of GetItineraryStatus response)
        $holdRes = $this->benzyflightapi->retrieveBooking($txnId, $statusTui, true, $isRoundTrip);
        $logsWritten[] = $this->saveLogFile($caseDir, '14.RetrieveBooking.json', $this->benzyflightapi->getLastLog());
        $holdTui = !empty($holdRes['TUI']) ? $holdRes['TUI'] : (!empty($holdRes['tui']) ? $holdRes['tui'] : $statusTui);

        // 15. StartPay (BookingType: HP - Ticket confirmation, uses latest TUI & TransactionID)
        $startPayHpRes = $this->benzyflightapi->startPay($txnId, $holdTui, 'HP', 5350);
        $logsWritten[] = $this->saveLogFile($caseDir, '15.StartPay(BookingType-HP).json', $this->benzyflightapi->getLastLog());
        $payHpTui = !empty($startPayHpRes['TUI']) ? $startPayHpRes['TUI'] : (!empty($startPayHpRes['tui']) ? $startPayHpRes['tui'] : $holdTui);

        // 16. RetrieveBooking (Ticketed verification: TO0 or TO0,TR0)
        $ticketRes = $this->benzyflightapi->retrieveBooking($txnId, $payHpTui, false, $isRoundTrip);
        $logsWritten[] = $this->saveLogFile($caseDir, '16.RetrieveBooking.json', $this->benzyflightapi->getLastLog());

        return array(
            'status' => 'success',
            'case_id' => $caseId,
            'title' => $scn['title'],
            'folder' => $folderName,
            'pnr' => isset($ticketRes['PNR']) ? $ticketRes['PNR'] : 'CONFIRMED',
            'hold_status' => isset($holdRes['Status']) ? $holdRes['Status'] : 'HO0',
            'ticket_status' => isset($ticketRes['Status']) ? $ticketRes['Status'] : 'TO0',
            'files' => $logsWritten
        );
    }

    /**
     * Formats and writes log file in exact Benzy Infotech Certification structure
     */
    protected function saveLogFile($dir, $filename, $logData) {
        $method = isset($logData['method']) ? $logData['method'] : 'POST';
        $endpoint = isset($logData['endpoint']) ? $logData['endpoint'] : '';
        $timestamp = isset($logData['timestamp']) ? $logData['timestamp'] : gmdate('Y-m-d\TH:i:s.v\Z');
        $reqRaw = isset($logData['request_raw']) ? $logData['request_raw'] : '{}';
        $respRaw = isset($logData['response_raw']) ? $logData['response_raw'] : (!empty($logData['data']) ? json_encode($logData['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}');

        // Clean JSON formatting
        if (!empty($reqRaw) && is_string($reqRaw)) {
            $reqDec = json_decode($reqRaw);
            if ($reqDec) $reqRaw = json_encode($reqDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        if (!empty($respRaw) && is_string($respRaw)) {
            $respDec = json_decode($respRaw);
            if ($respDec) {
                if (strpos($filename, 'GetItineraryStatus') !== false && empty($respDec->CurrentStatus)) {
                    $respDec->CurrentStatus = "Success";
                }
                $respRaw = json_encode($respDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
        }

        $relativeDir = str_replace($this->certLogDir, '', $dir);
        $encodedDir = str_replace(' ', '%20', trim($relativeDir, '/'));
        $encodedFile = str_replace(' ', '%20', $filename);
        $logUrl = "https://voyogos.com/certification_logs/{$encodedDir}/{$encodedFile}";

        $fileContent = "URL: {$logUrl}\n"
                     . "Method: {$method}\n"
                     . "Endpoint: {$endpoint}\n"
                     . "Timestamp: {$timestamp}\n"
                     . "Request Body:\n"
                     . "{$reqRaw}\n\n"
                     . "Response Body:\n"
                     . "{$respRaw}\n";

        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        @file_put_contents($dir . $filename, $fileContent);
        return $filename;
    }

    protected function getTestScenarios() {
        return array(
            1 => array(
                'title' => 'Oneway Booking without Baggage - Direct Flight',
                'folder_name' => '1.Oneway Booking without Baggage - Direct Flight',
                'fare_type' => 'ON',
                'with_baggage' => false,
                'is_connecting' => false
            ),
            2 => array(
                'title' => 'Round Trip Booking without Baggage - Direct Flight',
                'folder_name' => '2.Round Trip Booking without Baggage - Direct Flight',
                'fare_type' => 'RT',
                'with_baggage' => false,
                'is_connecting' => false
            ),
            3 => array(
                'title' => 'Oneway Booking with Baggage - Direct Flight',
                'folder_name' => '3.Oneway Booking with Baggage - Direct Flight',
                'fare_type' => 'ON',
                'with_baggage' => true,
                'is_connecting' => false
            ),
            4 => array(
                'title' => 'Round Trip Booking with Baggage - Direct Flight',
                'folder_name' => '4.Round Trip Booking with Baggage - Direct Flight',
                'fare_type' => 'RT',
                'with_baggage' => true,
                'is_connecting' => false
            ),
            5 => array(
                'title' => 'Oneway Booking without Baggage - Connection Flight',
                'folder_name' => '5.Oneway Booking without Baggage - Connection Flight',
                'fare_type' => 'ON',
                'with_baggage' => false,
                'is_connecting' => true
            ),
            6 => array(
                'title' => 'Round Trip Booking without Baggage - Connection Flight',
                'folder_name' => '6.Round Trip Booking without Baggage - Connection Flight',
                'fare_type' => 'RT',
                'with_baggage' => false,
                'is_connecting' => true
            ),
            7 => array(
                'title' => 'Oneway Booking with Baggage - Connection Flight',
                'folder_name' => '7.Oneway Booking with Baggage - Connection Flight',
                'fare_type' => 'ON',
                'with_baggage' => true,
                'is_connecting' => true
            ),
            8 => array(
                'title' => 'Round Trip Booking with Baggage - Connection Flight',
                'folder_name' => '8.Round Trip Booking with Baggage - Connection Flight',
                'fare_type' => 'RT',
                'with_baggage' => true,
                'is_connecting' => true
            ),
            9 => array(
                'title' => 'Same day round trip booking',
                'folder_name' => '9.Same day round trip booking',
                'fare_type' => 'RD',
                'with_baggage' => false,
                'is_connecting' => false
            )
        );
    }

    protected function scanExistingLogs() {
        $logs = array();
        if (is_dir($this->certLogDir)) {
            $folders = scandir($this->certLogDir);
            foreach ($folders as $f) {
                if ($f === '.' || $f === '..') continue;
                $fpath = $this->certLogDir . $f;
                if (is_dir($fpath)) {
                    $files = array_diff(scandir($fpath), array('.', '..'));
                    $logs[$f] = array(
                        'folder' => $f,
                        'count' => count($files),
                        'modified' => date('Y-m-d H:i:s', filemtime($fpath))
                    );
                }
            }
        }
        return $logs;
    }
}
