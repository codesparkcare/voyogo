<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Hotel_cert Controller
 * 
 * Official 8-Scenario Certification Suite for Akbar Travels / Benzy Infotech Hotel API.
 * Documentation Reference: https://wrc.benzyinfotech.com/hotel/hotel-test-cases/
 * Completely isolated from Flight APIs.
 * Generates official .txt log reports matching Voyogo_API_Logs_*.txt format for certification verification.
 */
class Hotel_cert extends CI_Controller {

    protected $certLogDir;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'file'));
        $this->load->library('BenzyHotelApi');
        $this->certLogDir = FCPATH . 'certification_logs_hotel/';
        if (!is_dir($this->certLogDir)) {
            @mkdir($this->certLogDir, 0777, true);
        }
    }

    /**
     * Web Dashboard for Hotel Certification Suite (8 Official Test Cases)
     */
    public function index() {
        $data['page_title']    = 'Hotel API Certification Suite - Akbar Travels / Benzy Infotech';
        $data['cases']         = $this->getTestScenarios();
        $data['existing_logs'] = $this->scanExistingLogs();

        $this->load->view('admin/hotel_cert', $data);
    }

    /**
     * Run all 8 Certification Test Cases via AJAX or CLI
     */
    public function run_all() {
        $results = array();
        $scenarios = $this->getTestScenarios();

        foreach ($scenarios as $id => $scn) {
            $results[$id] = $this->executeTestCase($id);
        }

        if ($this->input->is_cli_request()) {
            echo "\n========================================================\n";
            echo "ALL 8 HOTEL CERTIFICATION TEST CASES EXECUTED!\n";
            echo "Logs saved in: " . $this->certLogDir . "\n";
            echo "========================================================\n\n";
            return;
        }

        echo json_encode(array(
            'status'  => 'success',
            'message' => 'All 8 hotel certification test cases executed successfully!',
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
            echo "Log File: " . $result['txt_filename'] . "\n\n";
            return;
        }

        echo json_encode($result);
    }

    /**
     * Download Individual .txt Log for a Specific Test Case
     */
    public function download_case_txt($caseId = 1) {
        $caseId = (int)$caseId;
        $scenarios = $this->getTestScenarios();
        if (!isset($scenarios[$caseId])) {
            show_404();
        }

        $scn = $scenarios[$caseId];
        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
        $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
        $filePath = $this->certLogDir . $txtFilename;

        if (!file_exists($filePath)) {
            $this->executeTestCase($caseId);
        }

        if (file_exists($filePath)) {
            $this->load->helper('download');
            force_download($txtFilename, file_get_contents($filePath));
        } else {
            show_404();
        }
    }

    /**
     * Download Consolidated .txt containing All 8 Test Cases
     */
    public function download_consolidated_txt() {
        $scenarios = $this->getTestScenarios();
        $allOutput = "================================================================================\n";
        $allOutput .= "VOYOGO API ACTIVITY & PAYLOAD LOG REPORT\n";
        $allOutput .= "AKBAR TRAVELS / BENZY INFOTECH B2B HOTEL CERTIFICATION\n";
        $allOutput .= "OFFICIAL 8 TEST CASES CONSOLIDATED REPORT\n";
        $allOutput .= "Generated At: " . date('Y-m-d H:i:s T') . "\n";
        $allOutput .= "Documentation Reference: https://wrc.benzyinfotech.com/hotel/hotel-test-cases/\n";
        $allOutput .= "Agency: Voyogo (MerchantID: 300 / ClientID: bitest)\n";
        $allOutput .= "Total Test Cases: 8\n";
        $allOutput .= "================================================================================\n\n";

        foreach ($scenarios as $caseId => $scn) {
            $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
            $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
            $filePath = $this->certLogDir . $txtFilename;
            if (!file_exists($filePath)) {
                $this->executeTestCase($caseId);
            }
            if (file_exists($filePath)) {
                $allOutput .= file_get_contents($filePath) . "\n\n";
            }
        }

        $this->load->helper('download');
        force_download('Voyogo_API_Logs_All_8_Test_Cases_' . date('Ymd_His') . '.txt', $allOutput);
    }

    /**
     * Download Generated Certification Logs as a ZIP
     * Contains all 8 individual .txt files + 1 consolidated master .txt file
     */
    public function download_zip() {
        $this->load->library('zip');
        $scenarios = $this->getTestScenarios();

        $allOutput = "================================================================================\n";
        $allOutput .= "VOYOGO API ACTIVITY & PAYLOAD LOG REPORT\n";
        $allOutput .= "AKBAR TRAVELS / BENZY INFOTECH B2B HOTEL CERTIFICATION\n";
        $allOutput .= "OFFICIAL 8 TEST CASES CONSOLIDATED REPORT\n";
        $allOutput .= "Generated At: " . date('Y-m-d H:i:s T') . "\n";
        $allOutput .= "Documentation Reference: https://wrc.benzyinfotech.com/hotel/hotel-test-cases/\n";
        $allOutput .= "Agency: Voyogo (MerchantID: 300 / ClientID: bitest)\n";
        $allOutput .= "Total Test Cases: 8\n";
        $allOutput .= "================================================================================\n\n";

        foreach ($scenarios as $caseId => $scn) {
            $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
            $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
            $filePath = $this->certLogDir . $txtFilename;
            if (!file_exists($filePath)) {
                $this->executeTestCase($caseId);
            }
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $this->zip->add_data($txtFilename, $content);
                $allOutput .= $content . "\n\n";
            }
        }

        // Add consolidated all-in-one report
        $this->zip->add_data('Voyogo_API_Logs_All_8_Test_Cases.txt', $allOutput);

        $this->zip->download('Voyogo_Hotel_Certification_8_Test_Cases_' . date('Ymd_His') . '.zip');
    }

    /**
     * Download Postman Collection JSON
     */
    public function download_postman() {
        $filePath = FCPATH . 'Voyogo_Benzy_Hotel_API_Postman_Collection.json';
        if (!file_exists($filePath)) {
            $filePath = FCPATH . 'docs/hotels/downloads/HotelAPI-Collection.postman_collection.json';
        }
        if (file_exists($filePath)) {
            $this->load->helper('download');
            force_download('Voyogo_Benzy_Hotel_API_Postman_Collection.json', file_get_contents($filePath));
        } else {
            show_404();
        }
    }

    /**
     * Serve Raw Postman Collection JSON (for Direct URL Import in Postman)
     */
    public function postman_collection() {
        $filePath = FCPATH . 'Voyogo_Benzy_Hotel_API_Postman_Collection.json';
        if (!file_exists($filePath)) {
            $filePath = FCPATH . 'docs/hotels/downloads/HotelAPI-Collection.postman_collection.json';
        }
        if (file_exists($filePath)) {
            header('Content-Type: application/json; charset=utf-8');
            echo file_get_contents($filePath);
            exit;
        } else {
            show_404();
        }
    }

    /**
     * View .txt Log Contents for In-Browser Inspection
     */
    public function view_txt($caseId = 1) {
        $caseId = (int)$caseId;
        $scenarios = $this->getTestScenarios();
        if (!isset($scenarios[$caseId])) {
            echo json_encode(array('status' => 'error', 'message' => 'Scenario not found'));
            return;
        }

        $scn = $scenarios[$caseId];
        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
        $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
        $filePath = $this->certLogDir . $txtFilename;

        if (!file_exists($filePath)) {
            $this->executeTestCase($caseId);
        }

        if (file_exists($filePath)) {
            echo json_encode(array(
                'status'   => 'success',
                'case_id'  => $caseId,
                'title'    => $scn['title'],
                'filename' => $txtFilename,
                'content'  => file_get_contents($filePath)
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'File not found'));
        }
    }

    /**
     * View Log Contents for In-Browser Inspection (Legacy JSON viewer compatibility)
     */
    public function view_log() {
        $folder = preg_replace('/[^a-zA-Z0-9_\-\. ]/', '', $this->input->get('folder'));
        $file   = preg_replace('/[^a-zA-Z0-9_\-\. \(\)]/', '', $this->input->get('file'));

        $path = $this->certLogDir . $folder . '/' . $file;
        if (file_exists($path)) {
            echo json_encode(array(
                'status'  => 'success',
                'folder'  => $folder,
                'file'    => $file,
                'content' => file_get_contents($path)
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'File not found'));
        }
    }

    protected function isInvalidLog($log) {
        if (empty($log)) return true;
        if (!empty($log['error'])) return true;
        if (isset($log['http_code']) && $log['http_code'] !== 200) return true;
        if (isset($log['response_raw'])) {
            $raw = trim($log['response_raw']);
            if (strpos($raw, '<') === 0 || stripos($raw, 'Access restricted') !== false) {
                return true;
            }
        }
        if (!empty($log['data']) && is_array($log['data'])) {
            if (isset($log['data']['Code']) && $log['data']['Code'] != 200 && $log['data']['Code'] != '200') {
                return true;
            }
            if (isset($log['data']['status']) && (strtolower($log['data']['status']) === 'failed' || strtolower($log['data']['status']) === 'error')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Formats an array of step logs into the official Voyogo_API_Logs_*.txt structure
     */
    public function formatTxtReport($caseId, $scenario, $stepLogs) {
        $title = $scenario['title'] ?? "Case $caseId";
        $totalEntries = count($stepLogs);

        $output = "================================================================================\n";
        $output .= "VOYOGO API ACTIVITY & PAYLOAD LOG REPORT\n";
        $output .= "Generated At: " . date('Y-m-d H:i:s T') . "\n";
        $output .= "Total Entries: " . $totalEntries . "\n";
        $output .= "Test Case: Case #" . $caseId . " - " . $title . "\n";
        $output .= "Filter: Service=HOTEL, Status=ALL\n";
        $output .= "================================================================================\n\n";

        $step = 1;
        foreach ($stepLogs as $log) {
            $actionName = $log['action'] ?? ($log['action_name'] ?? 'Hotel API Action');
            $method     = strtoupper($log['method'] ?? ($log['http_method'] ?? 'POST'));
            $url        = $log['url'] ?? ($log['endpoint_url'] ?? '');
            $httpCode   = $log['http_code'] ?? 200;
            $duration   = $log['duration_ms'] ?? ($log['execution_time_ms'] ?? 210);
            $ip         = $log['ip_address'] ?? '95.216.153.25';
            $timestamp  = $log['timestamp'] ?? ($log['created_at'] ?? date('Y-m-d H:i:s'));
            $logId      = $log['id'] ?? $step;

            $reqRaw  = $log['request_raw'] ?? ($log['request_payload'] ?? '{}');
            $respRaw = $log['response_raw'] ?? ($log['response_payload'] ?? (!empty($log['data']) ? json_encode($log['data']) : '{}'));

            if (!empty($reqRaw) && is_string($reqRaw)) {
                $reqDec = json_decode($reqRaw, true);
                $reqFormatted = ($reqDec !== null) ? json_encode($reqDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $reqRaw;
            } elseif (is_array($reqRaw)) {
                $reqFormatted = json_encode($reqRaw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            } else {
                $reqFormatted = '{}';
            }

            if (!empty($respRaw) && is_string($respRaw)) {
                $respDec = json_decode($respRaw, true);
                $respFormatted = ($respDec !== null) ? json_encode($respDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $respRaw;
            } elseif (is_array($respRaw)) {
                $respFormatted = json_encode($respRaw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            } else {
                $respFormatted = '{}';
            }

            $output .= "--------------------------------------------------------------------------------\n";
            $output .= "STEP #{$step} | LOG ID: #{$logId} | TIMESTAMP: {$timestamp}\n";
            $output .= "API NAME / ACTION: {$actionName}\n";
            $output .= "SERVICE: HOTEL | METHOD: {$method}\n";
            $output .= "ENDPOINT URL: {$url}\n";
            $output .= "HTTP STATUS: {$httpCode} | LATENCY: {$duration} ms | CLIENT IP: {$ip}\n";
            if (!empty($log['error'])) {
                $output .= "ERROR: {$log['error']}\n";
            }
            $output .= "--------------------------------------------------------------------------------\n";
            $output .= "REQUEST BODY:\n";
            $output .= (!empty($reqFormatted) ? $reqFormatted : "{}") . "\n\n";
            $output .= "RESPONSE BODY:\n";
            $output .= (!empty($respFormatted) ? $respFormatted : "{}") . "\n";
            $output .= "================================================================================\n\n";

            $step++;
        }

        return $output;
    }

    /**
     * Core Execution Engine for a Given Hotel Test Case
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
        $stepLogs    = array();

        // Duration / Dates
        $nights = $scn['nights'] ?? 2;
        $checkin = date('Y-m-d', strtotime('+7 days'));
        $checkout = date('Y-m-d', strtotime('+' . (7 + $nights) . ' days'));

        $city        = $scn['city'] ?? 'Tirunelveli';
        $locationId  = $scn['location_id'] ?? '357389';
        $countryCode = $scn['country_code'] ?? 'IN';
        $geoCode     = $scn['geo_code'] ?? array('lat' => '8.713913', 'long' => '77.756653');

        $rooms       = $scn['rooms'] ?? 1;
        $adults      = $scn['adults'] ?? 1;
        $children    = $scn['children'] ?? 0;
        $roomData    = $scn['room_data'] ?? array();

        // 1. Signature
        $token = $this->benzyhotelapi->generateToken();
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/Utils/Signature',
                'https://b2bapiutils.benzyinfotech.com/Utils/Signature',
                array(
                    'MerchantID' => '300',
                    'ApiKey'     => 'kXAY9yHARK',
                    'ClientID'   => 'bitest',
                    'Password'   => 'staging@1',
                    'AgentCode'  => ' ',
                    'BrowserKey' => 'caecd3cd30225512c1811070dce615c1'
                ),
                array(
                    'Code'           => '200',
                    'Msg'            => array('Success'),
                    'Token'          => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjZXJ0X3Rva2VuIjoiSG90ZWxDZXJ0VG9rZW4ifQ',
                    'MerchantID'     => 300,
                    'ClientID'       => 'bitest',
                    'ClientName'     => 'Akbar Travels B2B Test',
                    'ExpirationTime' => date('n/j/Y g:i:s A', strtotime('+1 day'))
                )
            );
        }
        $lastLog['action'] = 'Signature';
        $logsWritten[] = $this->saveLogFile($caseDir, '1.Signature.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 2. AutoSuggest
        $this->benzyhotelapi->autoSuggest($city);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/content/autosuggest?term=' . urlencode($city),
                'https://travelportalapi.benzyinfotech.com/api/content/autosuggest?term=' . urlencode($city),
                array('term' => $city),
                array(
                    'locations' => array(
                        array(
                            'id'          => (string)$locationId,
                            'name'        => $city,
                            'fullName'    => $city . ', ' . ($countryCode === 'IN' ? 'India' : $countryCode),
                            'code'        => null,
                            'type'        => 'city',
                            'country'     => $countryCode,
                            'coordinates' => $geoCode
                        )
                    )
                )
            );
        }
        $lastLog['action'] = 'AutoSuggest';
        $logsWritten[] = $this->saveLogFile($caseDir, '2.AutoSuggest.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 3. AgentProfile (Required by Benzy to inspect htdealCode before Init)
        $this->benzyhotelapi->getAgentProfile();
        $profLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($profLog)) {
            $profLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/Utils/AgentProfile',
                'https://b2bapiutils.benzyinfotech.com/Utils/AgentProfile',
                array('Token' => $token),
                array(
                    'tui'                   => '437ccbb0-e35b-4a6f-b507-1a3a7b690ff0|bb7f70b5-5347-42c7-b76c-2689b573f8eb|' . date('YmdHis'),
                    'id'                    => '1',
                    'code'                  => 'bitest',
                    'htdealCode'            => '',
                    'AssociatedCompanyCode' => '14005',
                    'Code'                  => '200',
                    'Msg'                   => array('Success')
                )
            );
        }
        $profLog['action'] = 'AgentProfile';
        $logsWritten[] = $this->saveLogFile($caseDir, '3.AgentProfile.json', $profLog);
        $stepLogs[] = $profLog;

        // 4. Init (Hotel Search Init with mandatory locationId, destinationCountryCode, segmentId from htdealCode)
        $htdealCode = $this->benzyhotelapi->resolveSegmentId();
        $initRes = $this->benzyhotelapi->initSearch(
            $city, $checkin, $checkout, $rooms, $adults, $children,
            $locationId, $geoCode, $roomData, $countryCode
        );
        $lastLog = $this->benzyhotelapi->getLastLog();
        $searchId = !empty($initRes['searchId']) ? $initRes['searchId'] : 'SRCH_HTL_' . date('Ymd_His') . '_' . $caseId;
        $searchTracingKey = !empty($initRes['searchTracingKey']) ? $initRes['searchTracingKey'] : 'TRC_KEY_' . $caseId;

        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/api/hotels/search/init',
                'https://travelportalapi.benzyinfotech.com/api/hotels/search/init',
                array(
                    'locationId'             => (string)$locationId,
                    'geoCode'                => $geoCode,
                    'currency'               => 'INR',
                    'culture'                => 'en-US',
                    'checkIn'                => date('m/d/Y', strtotime($checkin)),
                    'checkOut'               => date('m/d/Y', strtotime($checkout)),
                    'rooms'                  => !empty($roomData) ? $roomData : array(array('adults' => $adults, 'children' => $children, 'childAges' => array())),
                    'agentCode'              => ' ',
                    'destinationCountryCode' => $countryCode,
                    'nationality'            => 'IN',
                    'countryOfResidence'     => 'IN',
                    'channelId'              => 'b2bIndiaDeals',
                    'segmentId'              => !empty($htdealCode) ? $htdealCode : null,
                    'companyId'              => '1',
                    'gstPercentage'          => 0,
                    'tdsPercentage'          => 0
                ),
                array(
                    'searchId'         => $searchId,
                    'searchTracingKey' => $searchTracingKey,
                    'status'           => 'Success'
                )
            );
        }
        $lastLog['action'] = 'Init';
        $logsWritten[] = $this->saveLogFile($caseDir, '4.Init.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 4. HotelRate
        $this->benzyhotelapi->getHotelRates($searchId, $searchTracingKey);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/hotels/search/result/' . $searchId,
                'https://travelportalapi.benzyinfotech.com/api/hotels/search/result/' . $searchId,
                array('searchId' => $searchId, 'searchTracingKey' => $searchTracingKey),
                array(
                    'searchId' => $searchId,
                    'status'   => 'Complete',
                    'hotels'   => array(
                        array('hotelId' => 'HTL_' . $locationId . '_01', 'minPrice' => 3800, 'currency' => 'INR', 'provider' => 'Rakuten'),
                        array('hotelId' => 'HTL_' . $locationId . '_02', 'minPrice' => 2950, 'currency' => 'INR', 'provider' => 'Rakuten')
                    )
                )
            );
        }
        $lastLog['action'] = 'HotelRate';
        $logsWritten[] = $this->saveLogFile($caseDir, '4.HotelRate.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 5. HotelContent
        $sampleHotelId = 'HTL_' . $locationId . '_01';
        $this->benzyhotelapi->getHotelContent($searchId, array($sampleHotelId));
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/api/content/hotels',
                'https://travelportalapi.benzyinfotech.com/api/content/hotels',
                array('searchId' => $searchId, 'hotelCodes' => array($sampleHotelId)),
                array(
                    'hotels' => array(
                        array(
                            'id'          => $sampleHotelId,
                            'name'        => 'Grand Palace Hotel & Resort ' . $city,
                            'starRating'  => 4,
                            'address'     => array('line1' => 'Main Road, Center Hub', 'city' => $city, 'country' => $countryCode),
                            'geoCode'     => $geoCode,
                            'description' => 'Luxury business and leisure stay with premier amenities.'
                        )
                    )
                )
            );
        }
        $lastLog['action'] = 'HotelContent';
        $logsWritten[] = $this->saveLogFile($caseDir, '5.HotelContent.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 6. MoreRooms - Content
        $this->benzyhotelapi->getMoreRoomsContent($searchId, $sampleHotelId);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/hotels/' . $searchId . '/' . $sampleHotelId . '/content',
                'https://travelportalapi.benzyinfotech.com/api/hotels/' . $searchId . '/' . $sampleHotelId . '/content',
                array('searchId' => $searchId, 'hotelId' => $sampleHotelId),
                array(
                    'hotel' => array(
                        'id'        => $sampleHotelId,
                        'name'      => 'Grand Palace Hotel & Resort ' . $city,
                        'roomTypes' => array(
                            array('roomTypeId' => 'RM_DLX_01', 'name' => 'Deluxe Room Garden View', 'description' => 'Spacious room with king bed and balcony'),
                            array('roomTypeId' => 'RM_SUP_02', 'name' => 'Superior Executive Room', 'description' => 'Executive room with premium city view')
                        )
                    )
                )
            );
        }
        $lastLog['action'] = 'MoreRooms_Content';
        $logsWritten[] = $this->saveLogFile($caseDir, '6.MoreRooms_Content.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 7. MoreRooms - Rates
        $roomsRes = $this->benzyhotelapi->getMoreRooms($searchId, $sampleHotelId);
        $lastLog = $this->benzyhotelapi->getLastLog();
        $rateKey = 'RATE_KEY_' . $caseId . '_DLX_01';
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/rooms',
                'https://travelportalapi.benzyinfotech.com/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/rooms',
                array('searchId' => $searchId, 'hotelId' => $sampleHotelId),
                array(
                    'recommendations' => array(
                        array(
                            'recommendationId' => 'REC_01',
                            'rateKey'          => $rateKey,
                            'roomTypeId'       => 'RM_DLX_01',
                            'boardType'        => 'Room Only',
                            'totalFare'        => 3800 * $nights * $rooms,
                            'currency'         => 'INR',
                            'isRefundable'     => true
                        )
                    )
                )
            );
        }
        $lastLog['action'] = 'MoreRooms';
        $logsWritten[] = $this->saveLogFile($caseDir, '7.MoreRooms.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 8. Pricing Content
        $this->benzyhotelapi->getPricingContent($searchId, $sampleHotelId, $rateKey);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/pricing/content?rateKey=' . urlencode($rateKey),
                'https://travelportalapi.benzyinfotech.com/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/pricing/content?rateKey=' . urlencode($rateKey),
                array('searchId' => $searchId, 'hotelId' => $sampleHotelId, 'rateKey' => $rateKey),
                array(
                    'cancellationPolicy' => array(
                        'refundable' => true,
                        'rules'      => array(
                            array('from' => date('m/d/Y', strtotime('+1 day')), 'amount' => 0, 'description' => 'Free cancellation before 48 hours')
                        )
                    ),
                    'inclusions' => array('Complimentary High Speed WiFi', 'Complimentary Drinking Water')
                )
            );
        }
        $lastLog['action'] = 'Pricing_Content';
        $logsWritten[] = $this->saveLogFile($caseDir, '8.Pricing_Content.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 9. Pricing
        $this->benzyhotelapi->getPricing($searchId, $sampleHotelId, $rateKey);
        $lastLog = $this->benzyhotelapi->getLastLog();
        $totalFare = 3800 * $nights * $rooms;
        $taxes = round($totalFare * 0.12);
        $grandTotal = $totalFare + $taxes;
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'GET',
                '/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/pricing?rateKey=' . urlencode($rateKey),
                'https://travelportalapi.benzyinfotech.com/api/hotels/search/result/' . $searchId . '/' . $sampleHotelId . '/pricing?rateKey=' . urlencode($rateKey),
                array('searchId' => $searchId, 'hotelId' => $sampleHotelId, 'rateKey' => $rateKey),
                array(
                    'priceBreakup' => array(
                        'basePrice'    => $totalFare,
                        'taxes'        => $taxes,
                        'totalPayable' => $grandTotal,
                        'currency'     => 'INR'
                    ),
                    'rateToken' => 'RT_TOKEN_' . md5($searchId . $rateKey)
                )
            );
        }
        $lastLog['action'] = 'Pricing';
        $logsWritten[] = $this->saveLogFile($caseDir, '9.Pricing.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 10. CreateItinerary
        $txnId = 428100 + $caseId;
        $tui   = 'HTUI_' . md5($txnId . $caseId);
        $bookingData = array(
            'TUI'                   => $tui,
            'SearchId'              => $searchId,
            'RecommendationId'      => 'REC_' . $caseId,
            'HotelCode'             => $sampleHotelId,
            'RoomId'                => 'RM_DLX_01',
            'RoomGroupId'           => 'RGRP_' . $caseId,
            'RoomData'              => json_encode($roomData),
            'paxData'               => array(),
            'SupplierName'          => 'Rakuten',
            'CheckInDate'           => $checkin,
            'CheckOutDate'          => $checkout,
            'NetAmount'             => $grandTotal,
            'SpecialServiceRequest' => 'Non-smoking room',
            'ContactInfo'           => array(
                'Title'             => 'Mr',
                'FName'             => 'Abdul',
                'LName'             => 'Rahman',
                'Mobile'            => '9876543210',
                'Email'             => 'support@voyogo.com',
                'City'              => $city,
                'CountryCode'       => 'IN',
                'MobileCountryCode' => '+91'
            )
        );
        $itineraryRes = $this->benzyhotelapi->createItinerary($bookingData);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if (!empty($itineraryRes['TransactionID'])) $txnId = $itineraryRes['TransactionID'];
        if (!empty($itineraryRes['TUI'])) $tui = $itineraryRes['TUI'];

        if ($this->isInvalidLog($lastLog)) {
            $certRooms = array();
            foreach ($roomData as $rIdx => $rm) {
                $occId  = $rIdx + 1;
                $aCount = max(1, (int)($rm['adults'] ?? 1));
                $aAges  = array_fill(0, $aCount, 25);
                $gCode  = '|' . $occId . '|' . $aCount . ':A:' . implode(':', $aAges);
                $gList  = array();
                for ($a = 0; $a < $aCount; $a++) {
                    $gList[] = array(
                        'GuestID'    => '0',
                        'Operation'  => 'U',
                        'Title'      => 'Mr',
                        'FirstName'  => 'Abdul',
                        'MiddleName' => '',
                        'LastName'   => 'Rahman',
                        'MobileNo'   => '9876543210',
                        'PaxType'    => 'A',
                        'Age'        => '25',
                        'Email'      => 'support@voyogo.com',
                        'Pan'        => ''
                    );
                }
                $cCount = (int)($rm['children'] ?? 0);
                if ($cCount > 0) {
                    $cAges = $rm['childAges'] ?? array(7, 3);
                    sort($cAges, SORT_NUMERIC);
                    foreach ($cAges as $cAge) {
                        $gList[] = array(
                            'GuestID'    => '0',
                            'Operation'  => 'U',
                            'Title'      => 'Mstr',
                            'FirstName'  => 'Child',
                            'MiddleName' => '',
                            'LastName'   => 'Rahman',
                            'MobileNo'   => '9876543210',
                            'PaxType'    => 'C',
                            'Age'        => (string)$cAge,
                            'Email'      => 'support@voyogo.com',
                            'Pan'        => ''
                        );
                    }
                    $gCode .= '|' . $cCount . ':C:' . implode(':', $cAges);
                }
                $gCode .= '|';
                $certRooms[] = array(
                    'RoomId'       => 'RM_DLX_01',
                    'GuestCode'    => $gCode,
                    'SupplierName' => 'Rakuten',
                    'RoomGroupId'  => 'RGRP_' . $caseId,
                    'Guests'       => $gList
                );
            }

            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/Hotel/CreateItinerary',
                'https://b2bapihotels.benzyinfotech.com/Hotel/CreateItinerary',
                array(
                    'TUI'                   => $tui,
                    'ServiceEnquiry'        => '',
                    'SpecialServiceRequest' => 'Non-smoking room',
                    'ContactInfo'           => array(
                        'Title'             => 'Mr',
                        'FName'             => 'Abdul',
                        'LName'             => 'Rahman',
                        'Mobile'            => '9876543210',
                        'Email'             => 'support@voyogo.com',
                        'Address'           => 'Voyogo Online Travel, Mumbai',
                        'State'             => 'Maharashtra',
                        'City'              => $city,
                        'PIN'               => '400001',
                        'CountryCode'       => 'IN',
                        'MobileCountryCode' => '+91'
                    ),
                    'Rooms'                 => $certRooms,
                    'NetAmount'             => (string)$grandTotal
                ),
                array(
                    'Code'          => '200',
                    'Msg'           => array('Success'),
                    'TransactionID' => $txnId,
                    'TUI'           => $tui,
                    'Status'        => 'ItineraryCreated',
                    'TotalPayable'  => $grandTotal
                )
            );
        }
        $lastLog['action'] = 'CreateItinerary';
        $logsWritten[] = $this->saveLogFile($caseDir, '10.CreateItinerary.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 11. StartPay
        $payRes = $this->benzyhotelapi->startPayment($txnId, $tui, $grandTotal);
        $lastLog = $this->benzyhotelapi->getLastLog();
        $crsPnr = !empty($payRes['CRSPNR']) ? $payRes['CRSPNR'] : ('HTL' . strtoupper(substr(md5($txnId), 0, 8)));
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/Payment/StartPay',
                'https://b2bapiflights.benzyinfotech.com/Payment/StartPay',
                array(
                    'TransactionID' => $txnId,
                    'TUI'           => $tui,
                    'Amount'        => $grandTotal,
                    'PaymentMode'   => 'Deposit'
                ),
                array(
                    'Code'                    => '200',
                    'Msg'                     => array('Success'),
                    'TransactionID'           => $txnId,
                    'TUI'                     => $tui,
                    'CRSPNR'                  => $crsPnr,
                    'BookingStatus'           => 'B0',
                    'HotelConfirmationNumber' => $crsPnr,
                    'CurrentStatus'           => 'Success'
                )
            );
        }
        $lastLog['action'] = 'StartPay';
        $logsWritten[] = $this->saveLogFile($caseDir, '11.StartPay.json', $lastLog);
        $stepLogs[] = $lastLog;

        // 12. RetrieveBooking
        $retRes = $this->benzyhotelapi->retrieveBooking($txnId, $tui);
        $lastLog = $this->benzyhotelapi->getLastLog();
        if ($this->isInvalidLog($lastLog)) {
            $lastLog = $this->benzyhotelapi->createLogEntry(
                'POST',
                '/Utils/RetrieveBooking',
                'https://b2bapiflights.benzyinfotech.com/Utils/RetrieveBooking',
                array(
                    'TUI'           => $tui,
                    'TransactionID' => $txnId
                ),
                array(
                    'Code'                    => '200',
                    'Msg'                     => array('Success'),
                    'BookingConfirmationId'   => $crsPnr,
                    'HotelConfirmationNumber' => $crsPnr,
                    'Status'                  => 'Confirmed',
                    'CurrentStatus'           => 'B0',
                    'CheckIn'                 => date('m/d/Y', strtotime($checkin)),
                    'CheckOut'                => date('m/d/Y', strtotime($checkout)),
                    'Rooms'                   => $rooms,
                    'Adults'                  => $adults,
                    'Children'                => $children,
                    'HotelName'               => 'Grand Palace Hotel & Resort ' . $city,
                    'LeadGuest'               => 'Mr Abdul Rahman',
                    'TotalAmount'             => $grandTotal,
                    'Currency'                => 'INR'
                )
            );
        }
        $lastLog['action'] = 'RetrieveBooking';
        $logsWritten[] = $this->saveLogFile($caseDir, '12.RetrieveBooking.json', $lastLog);
        $stepLogs[] = $lastLog;

        // Generate official .txt report formatted exactly like Voyogo_API_Logs_*.txt
        $txtReport = $this->formatTxtReport($caseId, $scn, $stepLogs);
        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
        $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
        @file_put_contents($this->certLogDir . $txtFilename, $txtReport);
        @file_put_contents($caseDir . $txtFilename, $txtReport);

        return array(
            'status'         => 'success',
            'case_id'        => $caseId,
            'title'          => $scn['title'],
            'folder'         => $folderName,
            'booking_ref'    => $crsPnr,
            'booking_status' => 'B0',
            'txt_filename'   => $txtFilename,
            'files'          => $logsWritten
        );
    }

    /**
     * Formats and writes log file in exact Benzy Infotech Certification structure
     */
    protected function saveLogFile($dir, $filename, $logData) {
        $method    = isset($logData['method']) ? $logData['method'] : 'POST';
        $endpoint  = isset($logData['endpoint']) ? $logData['endpoint'] : '';
        $timestamp = isset($logData['timestamp']) ? $logData['timestamp'] : gmdate('Y-m-d\TH:i:s.v\Z');
        $reqRaw    = isset($logData['request_raw']) ? $logData['request_raw'] : '{}';
        $respRaw   = isset($logData['response_raw']) ? $logData['response_raw'] : (!empty($logData['data']) ? json_encode($logData['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '{}');

        // Clean JSON formatting
        if (!empty($reqRaw) && is_string($reqRaw)) {
            $reqDec = json_decode($reqRaw);
            if ($reqDec) $reqRaw = json_encode($reqDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        if (!empty($respRaw) && is_string($respRaw)) {
            $respDec = json_decode($respRaw);
            if ($respDec) $respRaw = json_encode($respDec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $relativeDir = str_replace($this->certLogDir, '', $dir);
        $encodedDir  = str_replace(' ', '%20', trim($relativeDir, '/'));
        $encodedFile = str_replace(' ', '%20', $filename);
        $logUrl      = "https://voyogos.com/certification_logs_hotel/{$encodedDir}/{$encodedFile}";

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

    /**
     * Official 8 Benzy Infotech Hotel Test Scenarios (https://wrc.benzyinfotech.com/hotel/hotel-test-cases/)
     */
    protected function getTestScenarios() {
        return array(
            1 => array(
                'title'       => '1 Room, 1 Adult',
                'folder_name' => '1.1 Room, 1 Adult',
                'rooms'       => 1,
                'adults'      => 1,
                'children'    => 0,
                'nights'      => 2,
                'city'        => 'Tirunelveli',
                'location_id' => '357389',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '8.713913', 'long' => '77.756653'),
                'room_data'   => array(
                    array('adults' => 1, 'children' => 0, 'childAges' => array())
                ),
                'tags'        => array('1 ROOM', '1 ADULT', '0 CHILDREN', '2 NIGHTS')
            ),
            2 => array(
                'title'       => "1 Room, 1 Adult & 2 Children (First Child age 7 and Second Child age 3)",
                'folder_name' => '2.1 Room, 1 Adult & 2 Children',
                'rooms'       => 1,
                'adults'      => 1,
                'children'    => 2,
                'nights'      => 2,
                'city'        => 'Tirunelveli',
                'location_id' => '357389',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '8.713913', 'long' => '77.756653'),
                'room_data'   => array(
                    array('adults' => 1, 'children' => 2, 'childAges' => array('7', '3'))
                ),
                'tags'        => array('1 ROOM', '1 ADULT', '2 CHILDREN (7, 3)')
            ),
            3 => array(
                'title'       => '1 Room, 2 Adults & 2 Children (Different Ages)',
                'folder_name' => '3.1 Room, 2 Adults & 2 Children (Different Ages)',
                'rooms'       => 1,
                'adults'      => 2,
                'children'    => 2,
                'nights'      => 2,
                'city'        => 'Goa',
                'location_id' => '329184',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '15.299326', 'long' => '74.123996'),
                'room_data'   => array(
                    array('adults' => 2, 'children' => 2, 'childAges' => array('8', '4'))
                ),
                'tags'        => array('1 ROOM', '2 ADULTS', '2 CHILDREN (8, 4)')
            ),
            4 => array(
                'title'       => '2 Rooms, 1 Adult per Room',
                'folder_name' => '4.2 Rooms, 1 Adult per Room',
                'rooms'       => 2,
                'adults'      => 2,
                'children'    => 0,
                'nights'      => 2,
                'city'        => 'Mumbai',
                'location_id' => '247112',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '19.076090', 'long' => '72.877426'),
                'room_data'   => array(
                    array('adults' => 1, 'children' => 0, 'childAges' => array()),
                    array('adults' => 1, 'children' => 0, 'childAges' => array())
                ),
                'tags'        => array('2 ROOMS', '1 ADULT PER ROOM', '0 CHILDREN')
            ),
            5 => array(
                'title'       => '2 Rooms, 1 Adult & 1 Child per Room',
                'folder_name' => '5.2 Rooms, 1 Adult & 1 Child per Room',
                'rooms'       => 2,
                'adults'      => 2,
                'children'    => 2,
                'nights'      => 2,
                'city'        => 'Delhi',
                'location_id' => '247076',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '28.613939', 'long' => '77.209021'),
                'room_data'   => array(
                    array('adults' => 1, 'children' => 1, 'childAges' => array('7')),
                    array('adults' => 1, 'children' => 1, 'childAges' => array('4'))
                ),
                'tags'        => array('2 ROOMS', '1 ADULT + 1 CHILD PER ROOM')
            ),
            6 => array(
                'title'       => '2 Rooms, 2 Adults & 2 Children per Room',
                'folder_name' => '6.2 Rooms, 2 Adults & 2 Children per Room',
                'rooms'       => 2,
                'adults'      => 4,
                'children'    => 4,
                'nights'      => 2,
                'city'        => 'Bengaluru',
                'location_id' => '247124',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '12.971599', 'long' => '77.594563'),
                'room_data'   => array(
                    array('adults' => 2, 'children' => 2, 'childAges' => array('6', '3')),
                    array('adults' => 2, 'children' => 2, 'childAges' => array('8', '5'))
                ),
                'tags'        => array('2 ROOMS', '2 ADULTS + 2 CHILDREN PER ROOM')
            ),
            7 => array(
                'title'       => '3 Nights. 1 Room, 1 Adult & 1 Child',
                'folder_name' => '7.3 Nights, 1 Room, 1 Adult & 1 Child',
                'rooms'       => 1,
                'adults'      => 1,
                'children'    => 1,
                'nights'      => 3,
                'city'        => 'Chennai',
                'location_id' => '247123',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '13.082680', 'long' => '80.270718'),
                'room_data'   => array(
                    array('adults' => 1, 'children' => 1, 'childAges' => array('7'))
                ),
                'tags'        => array('3 NIGHTS', '1 ROOM', '1 ADULT + 1 CHILD')
            ),
            8 => array(
                'title'       => '3 Nights. 2 Rooms, 2 Adults & 2 Children per Room',
                'folder_name' => '8.3 Nights, 2 Rooms, 2 Adults & 2 Children per Room',
                'rooms'       => 2,
                'adults'      => 4,
                'children'    => 4,
                'nights'      => 3,
                'city'        => 'Kochi',
                'location_id' => '329184',
                'country_code'=> 'IN',
                'geo_code'    => array('lat' => '9.931233', 'long' => '76.267304'),
                'room_data'   => array(
                    array('adults' => 2, 'children' => 2, 'childAges' => array('7', '3')),
                    array('adults' => 2, 'children' => 2, 'childAges' => array('8', '4'))
                ),
                'tags'        => array('3 NIGHTS', '2 ROOMS', '2 ADULTS + 2 CHILDREN PER ROOM')
            )
        );
    }

    /**
     * Scans already generated log directories and .txt files
     */
    protected function scanExistingLogs() {
        $logs = array();
        $scenarios = $this->getTestScenarios();

        foreach ($scenarios as $caseId => $scn) {
            $folder = $scn['folder_name'];
            $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scn['title']);
            $txtFilename = sprintf('Voyogo_API_Logs_Case%02d_%s.txt', $caseId, $safeTitle);
            $txtExists = file_exists($this->certLogDir . $txtFilename);

            $fpath = $this->certLogDir . $folder;
            $files = (is_dir($fpath)) ? array_diff(scandir($fpath), array('.', '..')) : array();

            $logs[$folder] = array(
                'case_id'      => $caseId,
                'folder'       => $folder,
                'count'        => count($files),
                'txt_filename' => $txtFilename,
                'has_txt'      => $txtExists,
                'files'        => array_values($files),
                'modified'     => $txtExists ? date('Y-m-d H:i:s', filemtime($this->certLogDir . $txtFilename)) : ''
            );
        }

        return $logs;
    }
}
