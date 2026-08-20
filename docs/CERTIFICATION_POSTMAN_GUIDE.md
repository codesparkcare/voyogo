# Akbar Travels / Benzy Infotech Flight API Certification & Postman Guide

This document summarizes the complete discussion, steps, troubleshooting, and solutions for testing and submitting the **16-step certification logs** for **Voyogo (`https://voyogos.com`)**.

---

## 1. Overview of the 16 Certification Log Files

Folder: `certification_logs/1.Oneway Booking without Baggage - Direct Flight/`

| # | Step / File | Method & Endpoint | Base URL | Key Function |
|---|---|---|---|---|
| **1** | `1.Signature.json` | `POST /Utils/Signature` | `https://b2bapiutils.benzyinfotech.com` | Returns `Token`, `ClientID`, `TUI` |
| **2** | `2.ExpressSearch.json` | `POST /flights/ExpressSearch` | `https://b2bapiflights.benzyinfotech.com` | Returns flight search `TUI` |
| **3** | `3.WebSettings.json` | `POST /Utils/WebSettings` | `https://b2bapiutils.benzyinfotech.com` | Merchant configuration check |
| **4** | `4.GetExpSearch.json` | `POST /flights/GetExpSearch` | `https://b2bapiflights.benzyinfotech.com` | Retrieves flight list |
| **5** | `5.SSR.json` | `POST /Flights/SSR` | `https://b2bapiflights.benzyinfotech.com` | Checks meals, baggage, seats |
| **6** | `6.SmartPricer.json` | `POST /flights/SmartPricer` | `https://b2bapiflights.benzyinfotech.com` | Initiates fare revalidation |
| **7** | `7.GetSPricer.json` | `POST /Flights/GetSPricer` | `https://b2bapiflights.benzyinfotech.com` | Confirms repriced fare |
| **8** | `8.GetTravelCheckList.json` | `POST /Utils/GetTravelCheckList`| `https://b2bapiutils.benzyinfotech.com` | Travel advisory / checklist |
| **9** | `9.SSR.json` | `POST /Flights/SSR` | `https://b2bapiflights.benzyinfotech.com` | Re-verifies SSR options |
| **10**| `10.CreateItinerary.json` | `POST /Flights/CreateItinerary`| `https://b2bapiflights.benzyinfotech.com` | Passenger details $\rightarrow$ Returns `TransactionID` |
| **11**| `11.StartPay(BookingType-HB).json` | `POST /Payment/StartPay` | `https://b2bapiflights.benzyinfotech.com` | Hold Booking request |
| **12**| `12.GetItineraryStatus.json` | `POST /Payment/GetItineraryStatus`| `https://b2bapiflights.benzyinfotech.com` | Status polling |
| **13**| `13.GetItineraryStatus.json` | `POST /Payment/GetItineraryStatus`| `https://b2bapiflights.benzyinfotech.com` | Status confirmation |
| **14**| `14.RetrieveBooking.json`| `POST /Utils/RetrieveBooking` | `https://b2bapiutils.benzyinfotech.com` | PNR Check (`HO0` - Hold) |
| **15**| `15.StartPay(BookingType-HP).json` | `POST /Payment/StartPay` | `https://b2bapiflights.benzyinfotech.com` | Hold to Pay / Issue Ticket |
| **16**| `16.RetrieveBooking.json`| `POST /Utils/RetrieveBooking` | `https://b2bapiutils.benzyinfotech.com` | Final ticket & confirmed PNR |

---

## 2. Testing via Postman

### Importing the Collection
1. Open **Postman** $\rightarrow$ Click **Import**.
2. Select the file: `Voyogo_Benzy_Flight_API_Postman_Collection.json`.

### Base URLs Required
- `baseUrlUtils`: `https://b2bapiutils.benzyinfotech.com`
- `baseUrlFlights`: `https://b2bapiflights.benzyinfotech.com`

> **Note on 404 Error:**
> Setting `baseUrlUtils` to `https://voyogos.com` in Postman produces `404 Not Found` because `/Utils/Signature` and `/flights/ExpressSearch` are supplier API endpoints hosted on Benzy Infotech's servers.

---

## 3. How to Generate & Show Logs to Benzy Infotech from `voyogos.com`

Benzy Infotech asks for request/response logs from your application (`voyogos.com`).

### Step 1: Open the Built-in Certification Dashboard
Visit in browser:
```text
https://voyogos.com/flight_cert
```
*(Or locally: `http://localhost/voyogo/flight_cert`)*

### Step 2: Run Scenarios
- Click **"Run All Scenarios"** or click **"Execute"** next to **Scenario 1**.
- The backend automatically executes the live API calls and writes all 16 JSON log files.

### Step 3: Download ZIP
- Click **"Download All Logs (.ZIP)"** (or visit `https://voyogos.com/flight_cert/download_zip`).

---

## 4. Live URL Header in Log Files

All generated `.json` files in `certification_logs/` start with your live URL on line 1:

```text
URL: https://voyogos.com/certification_logs/1.Oneway%20Booking%20without%20Baggage%20-%20Direct%20Flight/1.Signature.json
Method: POST
Endpoint: /Utils/Signature
Timestamp: 2026-08-20T08:25:17.000Z
Request Body:
{
    "MerchantID": "300",
    "ApiKey": "kXAY9yHARK",
    "ClientID": "bitest",
    "Password": "staging@1",
    "AgentCode": "",
    "BrowserKey": "caecd3cd30225512c1811070dce615c1",
    "Key": "ef20-925c-4489-bfeb-236c8b406f7e"
}

Response Body:
{
    "TUI": "8608dafd-b425-4e1e-832c-2809bc9566e5|20260820102517",
    "Token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "ClientID": "FVI6V120g22Ei5ztGK0FIQ==",
    "Code": "200",
    "Msg": ["Success"]
}
```

---

## 5. Sample Email Reply to Benzy Infotech

```text
Dear Benzy Infotech Team,

We have completed the API certification test scenarios from our application (https://voyogos.com).

Attached is the complete archive of Request and Response JSON logs generated by our server for all scenarios (including Scenario 1: Oneway Direct Booking without Baggage).

Each log file includes the exact voyogos.com source path, timestamp, endpoint, request body, and response payload.

Please let us know if any further verification is required.

Best regards,
Voyogo Team
```
