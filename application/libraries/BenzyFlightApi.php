<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BenzyFlightApi {

    protected $CI;
    protected $signatureUrl = 'https://b2bapiutils.benzyinfotech.com/Utils/Signature';
    protected $expressSearchUrl = 'https://b2bapiflights.benzyinfotech.com/flights/ExpressSearch';
    protected $getExpSearchUrl = 'https://b2bapiflights.benzyinfotech.com/flights/GetExpSearch';
    
    // API Credentials provided by Benzy Infotech
    protected $credentials = array(
        "MerchantID" => "300",
        "ApiKey" => "kXAY9yHARK",
        "ClientID" => "bitest",
        "Password" => "staging@1",
        "AgentCode" => "",
        "BrowserKey" => "caecd3cd30225512c1811070dce615c1",
        "Key" => "ef20-925c-4489-bfeb-236c8b406f7e"
    );

    protected $channelId = "b2bIndiaDeals";

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Generate Signature (Bearer Token)
     */
    public function generateToken() {
        $cacheFile = APPPATH . 'cache/benzy_token.txt';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 1800)) {
            $cachedToken = file_get_contents($cacheFile);
            if (!empty($cachedToken)) {
                return $cachedToken;
            }
        }

        $ch = curl_init($this->signatureUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->credentials));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (isset($data['TokenId']) && !empty($data['TokenId'])) {
            @file_put_contents($cacheFile, $data['TokenId']);
            return $data['TokenId'];
        }
        
        if (isset($data['Token']) && !empty($data['Token'])) {
            @file_put_contents($cacheFile, $data['Token']);
            return $data['Token'];
        }

        return false;
    }

    /**
     * Initialize Express Search
     */
    public function expressSearch($from, $to, $date, $adults = 1, $children = 0, $infants = 0, $cabin = 'E') {
        $token = $this->generateToken();

        $cabinCode = 'E';
        if (stripos($cabin, 'Business') !== false) $cabinCode = 'B';
        if (stripos($cabin, 'First') !== false) $cabinCode = 'F';
        if (stripos($cabin, 'Premium') !== false) $cabinCode = 'PE';

        $payload = array(
            "ADT" => (int)$adults,
            "CHD" => (int)$children,
            "INF" => (int)$infants,
            "Cabin" => $cabinCode,
            "Source" => "CF",
            "Mode" => "AS",
            "ClientID" => $this->credentials['ClientID'],
            "ChannelID" => $this->channelId,
            "TUI" => "",
            "FareType" => "ON",
            "Trips" => array(
                array(
                    "From" => $from,
                    "To" => $to,
                    "OnwardDate" => $date,
                    "TUI" => ""
                )
            ),
            "Parameters" => array(
                "Airlines" => "",
                "GroupType" => "",
                "Refundable" => "",
                "IsDirect" => false,
                "IsStudentFare" => false,
                "IsNearbyAirport" => false
            )
        );

        if ($token) {
            $ch = curl_init($this->expressSearchUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            if (isset($data['TUI']) && !empty($data['TUI'])) {
                return $data['TUI'];
            }
        }

        // Return a mock TUI token if live server IP is restricted locally
        return "MOCK_TUI_" . time() . "_" . rand(1000, 9999);
    }

    /**
     * Check if currently running on Live Production Server
     */
    public function isLiveServer() {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        return (strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false);
    }

    /**
     * Helper to resolve Airline Name and Logo from Airline IATA Code
     */
    public function getAirlineDetails($code) {
        $code = strtoupper(trim($code));
        $map = array(
            '6E' => array('name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png'),
            'SG' => array('name' => 'SpiceJet', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SG.png'),
            'AI' => array('name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png'),
            'UK' => array('name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png'),
            'QP' => array('name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png'),
            'I5' => array('name' => 'AirAsia / AIX', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/I5.png'),
            'IX' => array('name' => 'Air India Express', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/IX.png'),
            'G8' => array('name' => 'Go First', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/G8.png'),
            'S5' => array('name' => 'Star Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/S5.png'),
            'EK' => array('name' => 'Emirates', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/EK.png'),
            'EY' => array('name' => 'Etihad Airways', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/EY.png'),
            'QR' => array('name' => 'Qatar Airways', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QR.png'),
            'FZ' => array('name' => 'flydubai', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/FZ.png'),
            'SQ' => array('name' => 'Singapore Airlines', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/SQ.png')
        );

        if (isset($map[$code])) {
            return $map[$code];
        }

        return array(
            'name' => 'Airline (' . $code . ')',
            'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/' . $code . '.png'
        );
    }

    /**
     * Fetch flight search results using TUI
     */
    public function getExpSearch($tui, $from = 'DEL', $to = 'BOM', $date = '') {
        if (empty($date)) $date = date('Y-m-d', strtotime('+3 days'));

        $isLive = $this->isLiveServer();

        if (strpos($tui, 'MOCK_TUI_') === false) {
            $token = $this->generateToken();
            if ($token) {
                $payload = array("TUI" => $tui, "ChannelID" => $this->channelId);

                $ch = curl_init($this->getExpSearchUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token
                ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);
                curl_close($ch);

                $resData = json_decode($response, true);
                if (is_array($resData)) {
                    $liveFlights = array();

                    if (!empty($resData['Flights']) && is_array($resData['Flights'])) {
                        foreach ($resData['Flights'] as $f) {
                            $code = isset($f['AirlineCode']) ? $f['AirlineCode'] : '6E';
                            $details = $this->getAirlineDetails($code);
                            $f['AirlineName'] = $details['name'];
                            $f['AirlineLogo'] = $details['logo'];
                            $liveFlights[] = $f;
                        }
                    } elseif (!empty($resData['Journeys']) && is_array($resData['Journeys'])) {
                        foreach ($resData['Journeys'] as $idx => $j) {
                            $code = isset($j['AirlineCode']) ? $j['AirlineCode'] : (isset($j['Provider']) ? $j['Provider'] : '6E');
                            $details = $this->getAirlineDetails($code);

                            $liveFlights[] = array(
                                'ResultID' => isset($j['ResultID']) ? $j['ResultID'] : ('BENZY_' . ($idx + 101)),
                                'AirlineCode' => $code,
                                'AirlineName' => $details['name'],
                                'AirlineLogo' => $details['logo'],
                                'FlightNumber' => isset($j['FlightNumber']) ? $j['FlightNumber'] : (isset($j['FlightNo']) ? $code . '-' . $j['FlightNo'] : $code . '-' . rand(100, 999)),
                                'FromCode' => $from,
                                'ToCode' => $to,
                                'DepartureTime' => isset($j['DepartureTime']) ? date('H:i', strtotime($j['DepartureTime'])) : '08:00',
                                'ArrivalTime' => isset($j['ArrivalTime']) ? date('H:i', strtotime($j['ArrivalTime'])) : '10:15',
                                'Duration' => isset($j['Duration']) ? $j['Duration'] : '2h 15m',
                                'Stops' => isset($j['Stops']) ? (int)$j['Stops'] : 0,
                                'Price' => isset($j['Price']) ? (float)$j['Price'] : (isset($j['GrossFare']) ? (float)$j['GrossFare'] : 5350),
                                'BaseFare' => isset($j['BaseFare']) ? (float)$j['BaseFare'] : 4500,
                                'Taxes' => isset($j['Taxes']) ? (float)$j['Taxes'] : 850,
                                'Baggage' => isset($j['Baggage']) ? $j['Baggage'] : '15 Kgs (1 piece)',
                                'CabinBaggage' => '7 Kgs',
                                'Refundable' => isset($j['Refundable']) ? (bool)$j['Refundable'] : false,
                                'SeatsLeft' => isset($j['SeatsLeft']) ? (int)$j['SeatsLeft'] : rand(3, 9)
                            );
                        }
                    } elseif (!empty($resData['Trips'][0]['Journey']) && is_array($resData['Trips'][0]['Journey'])) {
                        foreach ($resData['Trips'][0]['Journey'] as $idx => $j) {
                            $provider = isset($j['Provider']) ? $j['Provider'] : (isset($j['AirlineCode']) ? $j['AirlineCode'] : '6E');
                            $details = $this->getAirlineDetails($provider);

                            $liveFlights[] = array(
                                'ResultID' => 'BENZY_TRIP_' . ($idx + 101),
                                'AirlineCode' => $provider,
                                'AirlineName' => $details['name'],
                                'AirlineLogo' => $details['logo'],
                                'FlightNumber' => isset($j['FlightNo']) ? $provider . '-' . $j['FlightNo'] : $provider . '-101',
                                'FromCode' => $from,
                                'ToCode' => $to,
                                'DepartureTime' => isset($j['DepartureTime']) ? date('H:i', strtotime($j['DepartureTime'])) : '08:00',
                                'ArrivalTime' => isset($j['ArrivalTime']) ? date('H:i', strtotime($j['ArrivalTime'])) : '10:15',
                                'Duration' => isset($j['Duration']) ? $j['Duration'] : '2h 15m',
                                'Stops' => 0,
                                'Price' => isset($j['GrossFare']) ? (float)$j['GrossFare'] : (isset($j['Price']) ? (float)$j['Price'] : 5350),
                                'BaseFare' => isset($j['NetFare']) ? (float)$j['NetFare'] : 4500,
                                'Taxes' => 850,
                                'Baggage' => '15 Kgs',
                                'CabinBaggage' => '7 Kgs',
                                'Refundable' => isset($j['Refundable']) ? (bool)$j['Refundable'] : false,
                                'SeatsLeft' => rand(3, 9)
                            );
                        }
                    }

                    if (!empty($liveFlights) || $isLive) {
                        return array(
                            'Status' => !empty($liveFlights) ? 'Success' : 'NoResults',
                            'Message' => !empty($liveFlights) ? 'Live Benzy API results retrieved successfully' : 'No flights returned from Benzy API for this sector.',
                            'From' => $from,
                            'To' => $to,
                            'Date' => $date,
                            'Flights' => $liveFlights,
                            'RawResponse' => $resData
                        );
                    }
                }
            }
        }

        // Only fallback to mock on local development server
        if (!$isLive) {
            return $this->getMockFlightResults($from, $to, $date);
        }

        return array(
            'Status' => 'Error',
            'Message' => 'Unable to connect to Benzy API. Please check server IP whitelisting or credentials.',
            'From' => $from,
            'To' => $to,
            'Date' => $date,
            'Flights' => array()
        );
    }

    /**
     * Generate structured mock flight results for local development
     */
    public function getMockFlightResults($from, $to, $date) {
        $airlines = array(
            array('code' => '6E', 'name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'flight_no' => '6E-2134', 'dep' => '06:00', 'arr' => '08:15', 'price' => 5350, 'stops' => 0, 'dur' => '2h 15m'),
            array('code' => 'AI', 'name' => 'Air India', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/AI.png', 'flight_no' => 'AI-805', 'dep' => '09:30', 'arr' => '11:50', 'price' => 5980, 'stops' => 0, 'dur' => '2h 20m'),
            array('code' => 'UK', 'name' => 'Vistara', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/UK.png', 'flight_no' => 'UK-943', 'dep' => '14:15', 'arr' => '16:30', 'price' => 6450, 'stops' => 0, 'dur' => '2h 15m'),
            array('code' => 'QP', 'name' => 'Akasa Air', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/QP.png', 'flight_no' => 'QP-1102', 'dep' => '18:45', 'arr' => '21:00', 'price' => 4999, 'stops' => 0, 'dur' => '2h 15m'),
            array('code' => '6E', 'name' => 'IndiGo', 'logo' => 'https://imgak.mmtcdn.com/flights/assets/media/dt/common/icons/6E.png', 'flight_no' => '6E-5091', 'dep' => '21:30', 'arr' => '00:05', 'price' => 5120, 'stops' => 1, 'dur' => '3h 35m')
        );

        $flights = array();
        foreach ($airlines as $idx => $a) {
            $flights[] = array(
                'ResultID' => 'FL_' . ($idx + 101),
                'AirlineCode' => $a['code'],
                'AirlineName' => $a['name'],
                'AirlineLogo' => $a['logo'],
                'FlightNumber' => $a['flight_no'],
                'FromCode' => $from,
                'ToCode' => $to,
                'DepartureTime' => $a['dep'],
                'ArrivalTime' => $a['arr'],
                'Duration' => $a['dur'],
                'Stops' => $a['stops'],
                'Price' => $a['price'],
                'BaseFare' => round($a['price'] * 0.82),
                'Taxes' => round($a['price'] * 0.18),
                'Baggage' => '15 Kgs (1 piece)',
                'CabinBaggage' => '7 Kgs',
                'Refundable' => ($a['code'] === 'UK' || $a['code'] === 'AI') ? true : false,
                'SeatsLeft' => rand(3, 9)
            );
        }

        return array(
            'Status' => 'Success',
            'From' => $from,
            'To' => $to,
            'Date' => $date,
            'Flights' => $flights
        );
    }
}
