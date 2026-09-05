# Akbar / Benzy B2B Hotel API Documentation & Specification
> **Portal Source**: [Benzy Web Connect Resource Center (WRC) - Hotels](https://wrc.benzyinfotech.com/hotels/)
> **Credentials**: `apitest` / `BenzyAPI2021*`

## Quick Downloadable Resources

| Resource | Download Link | Description |
| :--- | :--- | :--- |
| 📄 **Workflow Diagram PDF** | [B2C-Hotel-API-WorkFlow.pdf](./downloads/B2C-Hotel-API-WorkFlow.pdf) | Official Hotel Search & Booking Lifecycle Flowchart |
| 📦 **Postman Collection** | [HotelAPI-Collection.postman_collection.json](./downloads/HotelAPI-Collection.postman_collection.json) | Ready-to-import Postman collection with all 14 APIs |
| ⚙️ **Postman Environment** | [Hotel_API_Environment.postman_environment.json](./downloads/Hotel_API_Environment.postman_environment.json) | Variables and environment configuration |

## Hotel Services Summary Index

| # | Service Name | HTTP Method | Endpoint | Description |
| :-: | :--- | :-: | :--- | :--- |
| 1 | [Signature](#signature) | `POST` | `{HotelUtilsURL}/Utils/Signature` | Signature API generates a JWT Token to be passed in the header of all API calls. |
| 2 | [AutoSuggest](#autosuggest) | `POST` | `{HotelSearchURL}/Hotel/AutoSuggest` | Auto Suggest |
| 3 | [Init](#init) | `POST` | `{HotelSearchURL}/Hotel/Init` | This API initiates a hotel search based on geo-codes and returns a SearchID which is to be passed into following API Call. A Search ID is valid for 1 hour only. |
| 4 | [Hotel Content](#hotel-content) | `POST` | `{HotelSearchURL}/Hotel/HotelContent` | It returns static details of hotel like name, city, star- rating etc. |
| 5 | [Hotel Rate](#hotel-rate) | `POST` | `{HotelSearchURL}/Hotel/HotelRate` | It returns hotel indicative fare details from multiple providers. |
| 6 | [Filter Data](#filter-data) | `POST` | `{HotelSearchURL}/Hotel/FilterData` | Filter data API is used to bind filters like price range, nearest attractions, chain properties, amenities etc. |
| 7 | [More Rooms - Content](#more-rooms-content) | `POST` | `{HotelDetailsURL}/Hotel/MoreRoomsContent` | It returns static Room content details. |
| 8 | [More Rooms](#more-rooms) | `POST` | `{HotelDetailsURL}/Hotel/MoreRooms` | It returns room details with multiple fare options. |
| 9 | [Pricing Content](#pricing-content) | `POST` | `{HotelDetailsURL}/Hotel/PricingContent` | It returns pricing content details of specific rooms for a specific provider |
| 10 | [Pricing](#pricing) | `POST` | `{HotelDetailsURL}/Hotel/Pricing` | It returns the pricing details of specific rooms for a specific provider. |
| 11 | [Create Itinerary](#create-itinerary) | `POST` | `{HotelBookingURL}/Hotel/CreateItinerary` | Creates an itinerary and returns a TransactionID. |
| 12 | [Start Pay](#start-pay) | `POST` | `{HotelBookingURL}/Hotel/StartPay` | This API initiates the payment with multiple payment options and initiates booking if the payment is successful. There are two possible modes Deposit mode and Multi payment mode. In deposit mode booking is done after debiting the agent and in multi-payment, booking is initiated in another API call, CommitPay, which is initiated automatically initiated, after deducting the amount from bank. |
| 13 | [RetrieveBooking](#retrievebooking) | `POST` | `{HotelBookingURL}/Hotel/RetrieveBooking` | This Api retrieves an existing itinerary. |
| 14 | [Cancel](#cancel) | `POST` | `{HotelBookingURL}/Hotel/Cancel` | This API initiates cancellation of a hotel booking. |

---

## 1. Signature

Signature API generates a JWT Token to be passed in the header of all API calls.

**End Point** : `{HotelUtilsURL}/Utils/Signature`  
**Method** : `POST`

### Request

```json
{
   "MerchantID": "****",
    "ApiKey": "************",
    "ClientID": "************",
    "Password": "*************",
    "AgentCode": "*******",
    "BrowserKey": "gh53h3e8cdge01dhsaa1bf972f43b"

}
```

### Response

```json
{
      "TUI": "c4f5d16f-bc04-4742-948c-7dc48deccc70|62ba31a2-55dc-4341-845c-387e4a6c22d4|20200506132416",
      "Token": "1dfgeyJhbGhgfdutyutryutysInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjMwMSIsIkFnZW50SW5mbyI6IjdwRDZUTzVQeVhNNjdRQ2w0Rk1LM2x0bGVEUG90aityM0h3Sy8rREZ0UGs9IiwicHdkIjoibmZZVUpWMDVtd3RkRG93TEd5VXgfhdgfhNPZz09IiwiYWdlbnRDhgfdhb2RlIjoiL0tmZFl3ZXNxUHc9IiwiY2xpZW50SWQiOiIxRGlkUEhaTTRoaWxhUjBab0FXVHZnPT0iLCJCcm93c2VyS2V5IjoiVHBlaVpUbEpXT3ZpemtGbG0zQzQvMk96K2hTa1hUY0pUOUlrOFYzRkIzWmREb3dMR3lVc09nPT0iLCJuYmYiOjE1ODg3NTE2NTYsImV4cCI6MTU5NzM5MTY1NiwiaWF0IjoxNTg4NzUxNjU2LCJpc3MiOiJ3ZWJj5474ghjghfjb25uZWN0IiwiYXVkIjoiY2xpZW50In0.uGudrgRSxaayGMJ_o47A-D6M6vSxOkNHFNTpAAF2D654Y6",
      "ClientID": "dsg6546hgfdhdfgh896789kl=",
      "LastLoginDate": "5/6/2020 11:16:54 AM",
      "Password": "*************",
      "loginAttempts": 0,
      "Code": "200",
      "Msg": [
         "Success"
      ]   
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| TUI | Transaction Unique Identifier. | af80de34-fccb-4c28-9365-6740b775265b\|4913ba1b-4ecd-4909-9c1e-e1a47161c31c\|20200625105832 |
| Token | Token<br>This value needs to be passed into the next API request. | eyJhbGciOiJIUzI1NiIsInRsdf5cCI6IkpXVCJ9.eyJ1bm lxdWVfbmFtZSI6IjEwMCIsIkFnZW50SW5mbyI6Im9 telV5OW85WDdrdkE3Q0YvTmovclVrQ0JLakh4R0YxU zF3QnNRck9neDg9IiwicHdkIjoiZVNTOEhSVnlBaWVTR U9lQ nNSc1dVdz09IiwiYWdlbnRDb2RlIjoiL0tmZFl3ZXNxUH c9IiwiY2xpZW50SWQiOiJZL1p5U2hSRmdZOD0iLCJCcm 93c2VyS2V5IjoiQXQyRzRWMVZkVWNGRjgxdUZGY1gzS kJQR3h1TVNDUlJvc1ZWaXc4NisrbGREb3dMR3lVc09nP T0iLCJuYmYiOjE1OTMsfwNjI5MTIsImV4cCI6MTYwMTcw MjkxMiwiaWF0IjoxNTkzMDYyOTEyLCJpc3MiOiJ3ZWJjb25 uZWN0IiwiYXVkIjoiY2xpZW50In0.rflINXNwcZxWVclYju9s ds6Oa-Uenf22jIhlbtOPE7I |
| ClientID | Client ID |  |
| LastLoginDate | Last Logged in Date |  |
| Password | Password |  |
| LoginAttempts | Login Attempts | 0 |
| Code | Response Code | 200 |
| Msg | Response Message | Success |

---

## 2. AutoSuggest

Auto Suggest

**End Point** : `{HotelSearchURL}/Hotel/AutoSuggest`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{
  "locations": [
    {
      "id": "376416",
      "name": "Boma (BOA)",
      "fullName": "Boma, Democratic Republic of the Congo (BOA)",
      "code": "boa",
      "type": "airport",
      "city": null,
      "state": null,
      "country": "CD",
      "score": 0,
      "referenceId": null,
      "coordinates": {
        "lat": -5.856491,
        "long": 13.061516
      }
    },
    {
      "id": "247112",
      "name": "Mumbai (BOM-Chhatrapati Shivaji Intl.)",
      "fullName": "Mumbai, India (BOM-Chhatrapati Shivaji Intl.)",
      "code": "bom",
      "type": "airport",
      "city": null,
      "state": null,
      "country": "IN",
      "score": 0,
      "referenceId": null,
      "coordinates": {
        "lat": 19.099533,
        "long": 72.874331
      }
    },
    {
      "id": "588820",
      "name": "Bommern",
      "fullName": "Bommern, Witten, North Rhine-Westphalia, Germany",
      "code": null,
      "type": "neighborhood",
      "city": null,
      "state": null,
      "country": "DE",
      "score": 0,
      "referenceId": null,
      "coordinates": {
        "lat": 51.413364,
        "long": 7.333714
      }
    }
   
  ],
  "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| id | Location ID | 376416 |
| name | Location Name | Bommern |
| fullName | Location Full Name | Bommern, Witten, North Rhine-Westphalia, Germany |
| code | Location Code | null |
| type | Location Type | neighborhood |
| city | City |  |
| state | State |  |
| country | Country | DE |
| score | Not Used | 0 |
| referenceId | Not Used | null |
| coordinates.lat | Lattitude | 51.413364 |
| coordinates.long | Longitude | 7.333714 |
| status | Response Status | Success |

---

## 3. Init

This API initiates a hotel search based on geo-codes and returns a SearchID which is to be passed into following API Call. A Search ID is valid for 1 hour only.

**End Point** : `{HotelSearchURL}/Hotel/Init`  
**Method** : `POST`

### Request

```json
{
  "geoCode": {
    "lat": "9.931233",
    "long": "76.267304"
  },
  "locationId": "329184",
  "currency": "INR",
  "culture": "en-US",
  "checkIn": "04/23/2024",
  "checkOut": "04/24/2024",
  "rooms": [
    {
      "adults": "1",
      "children": "0",
      "childAges": []
    }
  ],
  "agentCode": "xxxxxx",
  "destinationCountryCode": "IN",
  "nationality": "IN",
  "countryOfResidence": "IN",
  "channelId": "b2bIndiaDeals",
  "affiliateRegion": "B2B_India",
  "segmentId": "",
  "companyId": "1",
  "gstPercentage": 0,
  "tdsPercentage": 0
}
```

### Response

```json
{
      "searchId": "fdc1d9fc-7df3-45ef-8474-79d300efb669",
      "searchTracingKey": "3496447b-df4b-410b-91ad-0029ad872cca",
      "status": "success"
   }
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| searchId | Search ID | fdc1d9fc-7df3-45ef-8474-79d300efb669 |
| searchTracingKey | Search Tracing Key | 3496447b-df4b-410b-91ad-0029ad872cca |
| status | Status | success |

---

## 4. Hotel Content

It returns static details of hotel like name, city, star- rating etc.

**End Point** : `{HotelSearchURL}/Hotel/HotelContent`  
**Method** : `POST`

### Request

```json
{
   "limit": "50",
   "offset": "-1",
   "filterdata": "false"
}
```

### Response

```json
{
    "searchId": "90ffddc1-6546-4d5c-8a61-736814f44f0e",
    "locationId": "246673",
    "locationName": "Singapore",
    "hotels": [
        {
            "id": "38688811",
            "name": "Resorts World Sentosa - Hotel Ora",
            "starRating": 5.0,
            "address": "8 Sentosa Gateway, Resorts World Sentosa, Singapore, SG 098269",
            "distance": 5.27,
            "heroImage": "https://i.travelapi.com/lodging/10000000/9630000/9623700/9623643/2d3922c3_b.jpg",
            "facilities": [
                {
                    "id": 62,
                    "groupId": 62,
                    "name": "Elevator"
                },
                {
                    "id": 1,
                    "groupId": 1,
                    "name": "Parking"
                },
                {
                    "id": 32,
                    "groupId": 32,
                    "name": "Safe deposit box"
                },
                {
                    "id": 2,
                    "groupId": 2,
                    "name": "Lounge"
                },
                {
                    "id": 34,
                    "groupId": 34,
                    "name": "Disable Friendly"
                },
                {
                    "id": 4,
                    "groupId": 4,
                    "name": "Casino"
                },
                {
                    "id": 5,
                    "groupId": 5,
                    "name": "Breakfast"
                },
                {
                    "id": 68,
                    "groupId": 68,
                    "name": "Electric car charging stations"
                },
                {
                    "id": 6,
                    "groupId": 6,
                    "name": "Business Center"
                },
                {
                    "id": 8,
                    "groupId": 8,
                    "name": "Bar"
                },
                {
                    "id": 11,
                    "groupId": 11,
                    "name": "Television"
                },
              {
                "id": 12,
                "groupId": 12,
                "name": "Laundry Services"
              },
                {
                    "id": 13,
                    "groupId": 13,
                    "name": "Swimming Pool"
                },
                {
                    "id": 14,
                    "groupId": 14,
                    "name": "Restaurant"
                },
                {
                    "id": 76,
                    "groupId": 76,
                    "name": "ThingsToDo"
                },
                {
                    "id": 77,
                    "groupId": 77,
                    "name": "GuestServices"
                },
                {
                    "id": 16,
                    "groupId": 16,
                    "name": "Internet"
                },
                {
                    "id": 17,
                    "groupId": 17,
                    "name": "Fitness Facility"
                },
                {
                    "id": 49,
                    "groupId": 49,
                    "name": "Concierge Services"
                },
                {
                    "id": 80,
                    "groupId": 80,
                    "name": "Conveniences"
                }
            ],
            "geoCode": {
                "lat": 1.255084,
                "long": 103.82029
            },
            "provider": "EAN",
            "userReview": {
                "provider": "EAN",
                "count": 33,
                "rating": 4.2,
                "url": null,
                "reviews": null
            },
            "relevanceScore": 35.0,
            "rate": null,
            "isRecommended": null,
            "isRefundable": null,
            "payAtHotel": null,
            "moreRatesExpected": false,
            "isSoldOut": false,
            "freeBreakfast": null,
            "freeCancellation": null,
            "chainName": "Resorts World Sentosa",
            "locationName": null,
            "propertyType": "Hotel",
            "images": []
        },
        {
            "id": "38946658",
            "name": "Resorts World Sentosa - Equarius Hotel",
            "starRating": 5.0,
            "address": "8 Sentosa Gateway, Sentosa Island, Singapore, SG 098269",
            "distance": 5.27,
            "heroImage": "https://i.travelapi.com/lodging/6000000/5420000/5415100/5415025/9da4f2fb_b.jpg",
            "facilities": [
                {
                    "id": 62,
                    "groupId": 62,
                    "name": "Elevator"
                },
                {
                    "id": 1,
                    "groupId": 1,
                    "name": "Parking"
                },
                {
                    "id": 32,
                    "groupId": 32,
                    "name": "Safe deposit box"
                },
                {
                    "id": 2,
                    "groupId": 2,
                    "name": "Lounge"
                },
                {
                    "id": 34,
                    "groupId": 34,
                    "name": "Disable Friendly"
                },
                {
                    "id": 4,
                    "groupId": 4,
                    "name": "Casino"
                },
                {
                    "id": 5,
                    "groupId": 5,
                    "name": "Breakfast"
                },
                {
                    "id": 67,
                    "groupId": 67,
                    "name": "Garden"
                },
                {
                    "id": 68,
                    "groupId": 68,
                    "name": "Electric car charging stations"
                },
                {
                    "id": 6,
                    "groupId": 6,
                    "name": "Business Center"
                },
                {
                    "id": 10,
                    "groupId": 10,
                    "name": "Non Smoking"
                },
                {
                    "id": 11,
                    "groupId": 11,
                    "name": "Television"
                },
                {
                    "id": 12,
                    "groupId": 12,
                    "name": "Laundry Services"
                },
                {
                    "id": 13,
                    "groupId": 13,
                    "name": "Swimming Pool"
                },
                {
                    "id": 14,
                    "groupId": 14,
                    "name": "Restaurant"
                },
                {
                    "id": 76,
                    "groupId": 76,
                    "name": "ThingsToDo"
                },
                {
                    "id": 77,
                    "groupId": 77,
                    "name": "GuestServices"
                },
                {
                    "id": 16,
                    "groupId": 16,
                    "name": "Internet"
                },
                {
                    "id": 17,
                    "groupId": 17,
                    "name": "Fitness Facility"
                },
                {
                    "id": 49,
                    "groupId": 49,
                    "name": "Concierge Services"
                },
                {
                    "id": 80,
                    "groupId": 80,
                    "name": "Conveniences"
                },
                {
                    "id": 29,
                    "groupId": 29,
                    "name": "Banquet"
                }
            ],
            "geoCode": {
                "lat": 1.255065,
                "long": 103.82028
            },
            "provider": "EAN",
            "userReview": {
                "provider": "EAN",
                "count": 570,
                "rating": 4.2,
                "url": null,
                "reviews": null
            },
            "relevanceScore": 35.0,
            "rate": null,
            "isRecommended": null,
            "isRefundable": null,
            "payAtHotel": null,
            "moreRatesExpected": false,
            "isSoldOut": false,
            "freeBreakfast": null,
            "freeCancellation": null,
            "chainName": "Resorts World Sentosa",
            "locationName": null,
            "propertyType": "Hotel",
            "images": []
        }   ],
    "filters": null,
    "total": 791,
    "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| searchId | Search ID | 90ffddc1-6546-4d5c-8a61-736814f44f0e |
| locationId | Location ID | 246673 |
| locationName | Location Name | Singapore |
| hotels.id | Hotel ID | 1001077774 |
| hotels.name | Hotel Name | Sri Krishna residency |
| hotels.starRating | Hotel Star Rating | 0 |
| hotels.address | address |  |
| hotels.distance | Distance from city center in Km | 0.02 |
| hotels.heroImage | heroImage | https://cdn-images.innstant-servers.com/500x250/25/366/25366892.jpg |
| hotels.facilities | Facilities |  |
| hotels.geoCode | Latitude and Longitude<br>Either geo code or location id required. Google geo co-ordinates can be passed if not using autosuggest API | "lat": 11.40737,<br>"long": 76.70396 |
| hotels.provider | Provider | Innstant |
| hotels.userReview | User Review |  |
| hotels.relevanceScore | Relevance Score |  |
| hotels.rate | Tariff |  |
| hotels.isRecommended | Whether Recommended |  |
| hotels.isRefundable | Whether rate is refundable |  |
| hotels.payAtHotel | Whether Pay At Hotel facility availabile | false |
| hotels.moreRatesExpected | Whether more rates expected | false |
| hotels.isSoldOut | Whether sold out | false |
| hotels.freeBreakfast | Whether free breakfast provided |  |
| hotels.freeCancellation | Whether cancellation is free of cost. |  |
| hotels.chainName | Hotel Chain Name |  |
| hotels.locationName | Location Name |  |
| hotels.propertyType | Property Type |  |
| hotels.images | Hotel Images | [<br>               "https://cdn-images.innstant-servers.com/500x250/25/366/25366892.jpg",<br>               "https://cdn-images.innstant-servers.com/500x250/25/366/25366893.jpg",<br>               "https://cdn-images.innstant-servers.com/375x500/25/366/25366894.jpg",<br>               "https://cdn-images.innstant-servers.com/500x286/25/366/25366895.jpg",<br>               "https://cdn-images.innstant-servers.com/500x375/25/366/25366896.jpg"<br>            ] |
| hotels.filters | filters |  |
| hotels.total | Itinerary count | 398 |
| hotels.status | Status | Success |

---

## 5. Hotel Rate

It returns hotel indicative fare details from multiple providers.

**End Point** : `{HotelSearchURL}/Hotel/HotelRate`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{
   "searchStatus": "completed",
   "hotels": [
      {
         "id": "16179257",
         "rate": {
            "total": 860.36,
            "baseRate": 934,
            "commission": 93.4,
            "discounts": 0,
            "taxes": 0,
            "provider": "Fab",
            "pointEquivalent": 0,
            "otherRateComponents": [
               {
                  "amount": 5,
                  "description": "Agency Markup",
                  "type": "AgencyMarkup"
               },
               {
                  "amount": 14.01,
                  "description": "ATO Markup",
                  "type": "Markup"
               },
               {
                  "amount": 92.26,
                  "description": "PassOnCommission",
                  "type": "PassOnCommission"
               },
               {
                  "amount": 4.61,
                  "description": "TdsOnCommission",
                  "type": "TdsOnCommission"
               }
            ],
            "offer": null,
            "gstOnCommission": 1.236
         },
         "isRecommended": true,
         "moreRatesExpected": true,
         "isRefundable": true,
         "freeBreakfast": null,
         "payAtHotel": false,
         "freeCancellation": true
      },
      {
         "id": "16352945",
         "rate": {
            "total": 860.36,
            "baseRate": 934,
            "commission": 93.4,
            "discounts": 0,
            "taxes": 0,
            "provider": "Fab",
            "pointEquivalent": 0,
            "otherRateComponents": [
               {
                  "amount": 5,
                  "description": "Agency Markup",
                  "type": "AgencyMarkup"
               },
               {
                  "amount": 14.01,
                  "description": "ATO Markup",
                  "type": "Markup"
               },
               {
                  "amount": 92.26,
                  "description": "PassOnCommission",
                  "type": "PassOnCommission"
               },
               {
                  "amount": 4.61,
                  "description": "TdsOnCommission",
                  "type": "TdsOnCommission"
               }
            ],
            "offer": null,
            "gstOnCommission": 1.236
         },
         "isRecommended": true,
         "moreRatesExpected": true,
         "isRefundable": true,
         "freeBreakfast": null,
         "payAtHotel": false,
         "freeCancellation": true
      },
      {
         "id": "16284317",
         "rate": {
            "total": 860.36,
            "baseRate": 934,
            "commission": 93.4,
            "discounts": 0,
            "taxes": 0,
            "provider": "Fab",
            "pointEquivalent": 0,
            "otherRateComponents": [
               {
                  "amount": 5,
                  "description": "Agency Markup",
                  "type": "AgencyMarkup"
               },
               {
                  "amount": 14.01,
                  "description": "ATO Markup",
                  "type": "Markup"
               },
               {
                  "amount": 92.26,
                  "description": "PassOnCommission",
                  "type": "PassOnCommission"
               },
               {
                  "amount": 4.61,
                  "description": "TdsOnCommission",
                  "type": "TdsOnCommission"
               }
            ],
            "offer": null,
            "gstOnCommission": 1.236
         },
         "isRecommended": true,
         "moreRatesExpected": true,
         "isRefundable": true,
         "freeBreakfast": null,
         "payAtHotel": false,
         "freeCancellation": true
      }
   ],
   "currency": "INR",
   "total": 66,
   "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| searchStatus | Need to call the API again until searchStatus is "Completed"<br><br>Possible values are "Completed" and "In Progress". | Completed |
| hotels.id | Hotel ID | ID |
| hotels.rate.total | Total Rate |  |
| hotels.rate.baseRate | Rate |  |
| hotels.rate.commission | Commission |  |
| hotels.rate.discounts | Discounts |  |
| hotels.rate.taxes | Taxes |  |
| hotels.rate.provider | Provider | Fab |
| hotels.rate.pointEquivalent | Point Equivalent |  |
| hotels.rate.otherRateComponents.amount | Amount | 5 |
| hotels.rate.otherRateComponents.description | Description | Agency Markup |
| hotels.rate.otherRateComponents.type | Type | AgencyMarkup |
| hotels.rate.offer | offer |  |
| hotels.rate.gstOnCommission | GST on commission | 1.236 |
| hotels.isRecommended | Whether Recommended |  |
| hotels.isRefundable | Whether rate is refundable |  |
| hotels.moreRatesExpected | Whether more rates expected | false |
| hotels.freeBreakfast | Whether free breakfast provided |  |
| currency | Currency | INR |
| total | Total Count | 66 |
| status | Status | Success |

---

## 6. Filter Data

Filter data API is used to bind filters like price range, nearest attractions, chain properties, amenities etc.

**End Point** : `{HotelSearchURL}/Hotel/FilterData`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{ 
   "JSON": { 
      "filters": [ 
         { 
            "name": "Hotel Name",
            "category": "HotelName",
            "type": "text",
            "options": null
         },
         { 
            "name": "Price Group",
            "category": "PriceGroup",
            "type": "range",
            "options": [ 
               { 
                  "min": 0,
                  "max": 7000,
                  "label": "Upto ₹7000",
                  "count": 58
               },
               { 
                  "min": 7000,
                  "max": 12000,
                  "label": "₹7000 to ₹12000",
                  "count": 5
               },
               { 
                  "min": 12000,
                  "max": 28000,
                  "label": "₹12000 to ₹28000",
                  "count": 2
               },
               { 
                  "min": 28000,
                  "max": -1,
                  "label": "₹28000 & More",
                  "count": 1
               }
            ]
         },
         { 
            "name": "Locations",
            "category": "Locations",
            "type": "list",
            "options": [ 
               { 
                  "category": null,
                  "value": "1000982903,1001027212,15402977,1000971858,16551193,15403210,17202690,15402703,15402924,15773110,15610776,16273038,15586282,16454855,16448917,15775369,16352945,15730776,16179257,16184510,16095678,15271802,16222262,16358369,1001126283,15197738,15541578,17514660,15403229,17210035,15777505,16274562,16073390,16298223,1001192571,16796461,16131420,17371280,16291017,17237844,1001031206,1000819533,17350830,1001180647,16459424,29129562,1000793292,16432867",
                  "label": "Kandal",
                  "count": 48
               
            ]
         },

         { 
            "name": "Attraction",
            "category": "Attraction",
            "type": "list",
            "options": [ 
               { 
                  "value": "11.404128,76.70798",
                  "label": "Muniswarar Temple",
                  "count": 0
               },
               { 
                  "value": "11.418877,76.71145",
                  "label": "Government Botanical Gardens",
                  "count": 0
               },
               { 
                  "value": "11.412778,76.69174",
                  "label": "Government Museum",
                  "count": 0
               },
               { 
                  "value": "11.412968,76.69929",
                  "label": "Upper Bhavani Lake",
                  "count": 0
               },
               { 
                  "value": "11.4,76.7",
                  "label": "Second World War Memorial Pillar",
                  "count": 0
               },
               { 
                  "value": "11.408446,76.70756",
                  "label": "Diyanamyam Mutt",
                  "count": 0
               },
               { 
                  "value": "11.421812,76.710686",
                  "label": "Raj Bhavan",
                  "count": 0
               },
               { 
                  "value": "11.4,76.7",
                  "label": "Lady Canning's Seat",
                  "count": 0
               },
               { 
                  "value": "11.403322,76.67638",
                  "label": "Arranmore Palace",
                  "count": 0
               },
               { 
                  "value": "11.406774,76.68799",
                  "label": "Thread Garden",
                  "count": 0
               },
               { 
                  "value": "11.40597,76.69987",
                  "label": "Mudumalai National Park",
                  "count": 0
               },
               { 
                  "value": "11.396089,76.68945",
                  "label": "Cairn Hill",
                  "count": 0
               },
               { 
                  "value": "11.421812,76.710686",
                  "label": "Raj Bhawan",
                  "count": 0
               },
               { 
                  "value": "11.394449,76.70555",
                  "label": "Sri Mariamman Temple",
                  "count": 0
               },
               { 
                  "value": "11.40106,76.73578",
                  "label": "Doddabetta Peak",
                  "count": 0
               },
               { 
                  "value": "11.414729,76.70205",
                  "label": "St. Stephen's Church",
                  "count": 0
               },
               { 
                  "value": "11.404617,76.70873",
                  "label": "Ooty Rose Garden",
                  "count": 0
               },
               { 
                  "value": "11.40432,76.68764",
                  "label": "Ooty Lake",
                  "count": 0
               }
            ]
         }
      ],
      "status": "success"
   }
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| filters.name | Filter Name | Hotel Name |
| category | Category | Hotel Name |
| type | Filter Type | Text |
| options.min | Minimum Value | 0 |
| options.max | Maximum Value | 7000 |
| options.label | Label | Upto Rs.7000 |
| options.count | Count | 56 |
| status | Status | Success |

---

## 7. More Rooms - Content

It returns static Room content details.

**End Point** : `{HotelDetailsURL}/Hotel/MoreRoomsContent`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{ 
   "hotel": {
      "id": "16093845",
      "name": "Sai Hill Top Resorts",
      "relevanceScore": 0,
      "providerFamily": "Agoda",
      "providerHotelId": "5047791",
      "language": null,
      "providerName": "Agoda",
      "geoCode": {
         "lat": 11.409,
         "long": 76.7016
      },
      "neighbourhoods": [],
      "contact": {
         "address": {
            "line1": "2F, Sathya Sai Nagar,Valley View,Coonoor Road,Ooty 643 001,Tamil Nadu 643001 Ooty",
            "line2": "",
            "destinationCode": null,
            "city": "Ooty",
            "state": "",
            "country": "IN",
            "postalCode": "643001"
         },
         "phones": null,
         "faxes": null,
         "emails": null,
         "website": "https://www.agoda.com/partners/partnersearch.aspx?hid=5047791"
      },
      "chainCode": "0",
      "chainName": "No chain",
      "type": "Hotel",
      "website": "https://www.agoda.com/partners/partnersearch.aspx?hid=5047791",
      "descriptions": [
         {
            "type": "description",
            "text": ""
         }
      ],
      "category": "Hotel",
      "starRating": 3,
      "facilityGroups": [],
      "facilities": [],
      "nearByAttractions": [],
      "images": [],
      "policies": [
         {
            "type": "children policy",
            "text": "Age between 0 to 0 is considered children. Up to 0 years is considered infant. Minimum allowed age for this hotel is 0. "
         }
      ],
      "fees": [],
      "reviews": [
         {
            "provider": "Agoda",
            "count": 0,
            "rating": 0,
            "url": null,
            "reviews": null
         }
      ],
      "checkinInfo": {
         "beginTime": null,
         "endTime": null,
         "instructions": null,
         "specialInstructions": null,
         "minAge": 0
      },
      "checkoutInfo": {
         "time": null
      },
      "heroImage": null,
      "distance": 0,
      "locationName": null
   },
   "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| hotel.id | Hotel ID | 16093845 |
| hotel.name | Hotel Name | Sai Hill Top Resorts |
| hotel.relevanceScore | Relevance Score | 0 |
| hotel.providerFamily | Provider Family | Agoda |
| hotel.providerHotelId | Provider Hotel ID | 5047791 |
| hotel.language | Language |  |
| hotel.providerName | Provider Name | Agoda |
| hotel.geoCode | Latitude and Longitude | "lat": 11.409,<br>"long": 76.7016 |
| hotel.contact.address.line1 | Address |  |
| hotel.contact.address.line2 | Address |  |
| hotel.contact.address.destinationCode | Destination Code |  |
| hotel.contact.address.city | City | Ooty |
| hotel.contact.address.state | State |  |
| hotel.contact.address.country | Country | IN |
| hotel.contact.address.postalCode | Postal Code | 643001 |
| hotel.contact.phones | Phones |  |
| hotel.contact.faxes | Faxes |  |
| hotel.contact.emails | E Mails |  |
| hotel.contact.website | Website | https://www.agoda.com/partners/partnersearch.aspx?hid=5047791 |
| hotel.chainCode | Hotel Chain Code |  |
| hotel.chainName | Hotel Chain Name | No Chain |
| hotel.website | Hotel Website | https://www.agoda.com/partners/partnersearch.aspx?hid=5047791 |
| hotel.descriptions.type | Description Type | Description |
| hotel.descriptions.text | Description |  |
| hotel.category | Category | Hotel |
| hotel.starRating | Star Rating | 3 |
| hotel.facilityGroups | Facility Groups |  |
| hotel.facilities | Facilities |  |
| hotel.nearByAttractions | Nearby Attractions |  |
| hotel.images | Images |  |
| hotel.policies.type | Policy Type | Children policy |
| hotel.policies.text | Policy | Age between 0 to 0 is considered children. Up to 0 years is considered infant. Minimum allowed age for this hotel is 0. |
| hotel.fees | fees |  |
| hotel.reviews.provider | Reviews provider |  |
| hotel.reviews.count | Count |  |
| hotel.reviews.rating | Rating |  |
| hotel.reviews.url | URL |  |
| hotel.reviews.reviews | Reviews |  |
| hotel.checkinInfo.beginTime | Check in  Begin Time |  |
| hotel.checkinInfo.endTime | Check in End Time |  |
| hotel.checkinInfo.instructions | Instructions |  |
| hotel.checkinInfo.specialInstructions | Special Instructions |  |
| hotel.checkinInfo.minAge | Minimum Age for Check In |  |
| hotel.checkoutInfo.time | Checkout Time |  |
| hotel.heroImage | Hero Image |  |
| hotel.distance | Distance from city center |  |
| hotel.locationName | Location Name |  |
| status | Status | Success |

---

## 8. More Rooms

It returns room details with multiple fare options.

**End Point** : `{HotelDetailsURL}/Hotel/MoreRooms`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{
      "recommendations": [
         {
            "id": "998d5021-6c8d-4b21-9488-22737f00fab2",
            "roomGroup": [
               {
                  "id": "05172c85-8019-4c0b-9ca8-7dd1e8636db0",
                  "code": null,
                  "availability": 1,
                  "description": null,
                  "needsPriceCheck": true,
                  "isPackageRate": null,
                  "providerId": "20016",
                  "providerName": "travelguru",
                  "room": {
                     "id": "e9dcfda9-b2ca-427d-a789-ef64a62391a0",
                     "standardRoomId": "1",
                     "standardRoomName": "Double Ac",
                     "name": "Double Non AC",
                     "description": "",
                     "beds": null,
                     "smokingAllowed": false,
                     "facilities": [],
                     "images": []
                  },
                  "roomCount": 1,
                  "occupancies": [
                     {
                        "occupancyId": 1,
                        "numOfAdults": 1,
                        "numOfChildren": 0,
                        "childAges": []
                     }
                  ],
                  "type": "negotiated",
                  "baseRate": 1440,
                  "totalRate": 1499.27,
                  "minSellingRate": 0,
                  "publishedRate": 0,
                  "taxes": [
                     {
                        "amount": 172.8,
                        "description": null,
                        "type": null
                     }
                  ],
                  "fees": null,
                  "discounts": [],
                  "otherRateComponents": [
                     {
                        "amount": 142.24,
                        "description": "PassOnCommission",
                        "type": "PassOnCommission"
                     },
                     {
                        "amount": 7.11,
                        "description": "TdsOnCommission",
                        "type": "TdsOnCommission"
                     },
                     {
                        "amount": 21.6,
                        "description": "ATO Markup applied on baseAmount",
                        "type": "Markup"
                     },
                     {
                        "amount": 5,
                        "description": "Agency Markup calculated on baseAmount",
                        "type": "AgencyMarkup"
                     }
                  ],
                  "commission": {
                     "amount": 144,
                     "description": "Agency Commission",
                     "type": null
                  },
                  "dailyRates": [
                     {
                        "amount": 1326.47,
                        "date": "2020-12-31T18:30:00+00:00",
                        "taxIncluded": false,
                        "discount": 0
                     }
                  ],
                  "pointEquivalent": 0,
                  "refundable": true,
                  "allGuestsInfoRequired": true,
                  "onlineCancellable": false,
                  "specialRequestSupported": false,
                  "payAtHotel": null,
                  "cardRequired": false,
                  "policies": null,
                  "boardBasis": {
                     "description": null,
                     "type": "Other"
                  },
                  "offers": [],
                  "cancellationPolicies": [
                     {
                        "text": "Full refund if you cancel this booking by 31-Dec-20 12:00 PM IST.No refund if you cancel this booking later than 31-Dec-20 12:00 PM IST.You might be charged upto the full cost of stay (including taxes & service charge) if you do not check-in to the hotel.",
                        "rules": null
                     }
                  ],
                  "includes": [],
                  "additionalCharges": null,
                  "depositRequired": false,
                  "gstAllowed": true,
                  "depositAmount": null,
                  "guaranteeRequired": false,
                  "gstOnCommission": 1.236
               }
            ],
            "total": 1499.27,
            "publishedRate": 0,
            "groupId": 1
         }
      ],
      "stayPeriod": {
         "start": "01/01/2021",
         "end": "01/02/2021"
      },
      "searchId": "fdc1d9fc-7df3-45ef-8474-79d300efb669",
      "searchTracingKey": null,
      "status": "success"   
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| recommendations.id | Recommendation ID | 998d5021-6c8d-4b21-9488-22737f00fab2 |
| recommendations.roomGroup.id | Room Group ID | 05172c85-8019-4c0b-9ca8-7dd1e8636db0 |
| recommendations.roomGroup.code | Room Group Code |  |
| recommendations.roomGroup.availability | Availability | 1 |
| recommendations.roomGroup.description | Description |  |
| recommendations.roomGroup.needsPriceCheck | Whether Price Check needed | false |
| recommendations.roomGroup.isPackageRate | Whether Package Rate | false |
| recommendations.roomGroup.providerId | Provider ID | 20127 |
| recommendations.roomGroup.providerName | Provider Name | travelguru |
| recommendations.roomGroup.room.id | Room ID | e9dcfda9-b2ca-427d-a789-ef64a62391a0 |
| recommendations.roomGroup.room.standardRoomId | Standard Room ID | 1 |
| recommendations.roomGroup.room.standardRoomName | Standard Room Name | Double Ac |
| recommendations.roomGroup.room.name | Room Name | Double Non AC |
| recommendations.roomGroup.room.description | Description |  |
| recommendations.roomGroup.room.beds | Beds |  |
| recommendations.roomGroup.room.smokingAllowed | Whether smoking allowed | false |
| recommendations.roomGroup.room.facilities | Facilities |  |
| recommendations.roomGroup.room.images | Room Images |  |
| recommendations.roomGroup.roomCount | Room Count | 1 |
| recommendations.roomGroup.occupancies.occupancyId | Occupancy ID | 1 |
| recommendations.roomGroup.occupancies.numOfAdults | Number of Adults | 1 |
| recommendations.roomGroup.occupancies.numOfChildren | Number of Children | 0 |
| recommendations.roomGroup.occupancies.childAges | Ages of children |  |
| recommendations.roomGroup.type | Group Type | negotiated |
| recommendations.roomGroup.baseRate | Base Rate | 1440 |
| recommendations.roomGroup.totalRate | Total Rate | 1499.77 |
| recommendations.roomGroup.minSellingRate | Minimum Selling Rate |  |
| recommendations.roomGroup.publishedRate | Published Rate |  |
| recommendations.roomGroup.taxes.amount | Tax Amount | 172.00 |
| recommendations.roomGroup.taxes.description | Description |  |
| recommendations.roomGroup.taxes.type | Tax Type |  |
| recommendations.roomGroup.fees | Fees |  |
| recommendations.roomGroup.discounts | Discounts | [ ] |
| recommendations.roomGroup.otherRateComponents.amount | otherRateComponents | 142.32 |
| recommendations.roomGroup.otherRateComponents.description | Description | PassOnCommission |
| recommendations.roomGroup.otherRateComponents.type | Type | PassOnCommission |
| recommendations.roomGroup.pointEquivalent | Point Equivalent |  |
| recommendations.roomGroup.refundable | Whether fare is Refundable | true |
| recommendations.roomGroup.allGuestsInfoRequired | Whether all guests Info required | true |
| recommendations.roomGroup.onlineCancellable | Whether Online Cancellation possible | true |
| recommendations.roomGroup.specialRequestSupported | Whether special requests are supported | false |
| recommendations.roomGroup.payAtHotel | Whether Pay at Hotel | false |
| recommendations.roomGroup.cardRequired | Whether Credit Card required | false |
| recommendations.roomGroup.policies | policies |  |
| recommendations.roomGroup.boardBasis.description | Description |  |
| recommendations.roomGroup.boardBasis.type | Type | Other |
| recommendations.roomGroup.offers | Offers |  |
| recommendations.roomGroup.cancellationPolicies.text | Cancellation Policy Text | ull refund if you cancel this booking by 31-Dec-20 12:00 PM IST.No refund if you cancel this booking later than 31-Dec-20 12:00 PM IST.You might be charged upto the full cost of stay (including taxes &amp; service charge) if you do not check-in to the hotel. |
| recommendations.roomGroup.cancellationPolicies.rules | Cancellation Rules |  |
| recommendations.roomGroup.includes | Includes |  |
| recommendations.roomGroup.additionalCharges | Additional Charges |  |
| recommendations.roomGroup.depositRequired | Deposit Required | false |
| recommendations.roomGroup.gstAllowed | Whether GST allowed | false |
| recommendations.roomGroup.depositAmount | Deposit Amount |  |
| recommendations.roomGroup.guaranteeRequired | Whether guarantee required | false |
| recommendations.roomGroup.gstOnCommission | GST on commission |  |
| recommendations.roomGroup.total | Total | 1499.27 |
| recommendations.roomGroup.publishedRate | PublishedRate |  |
| stayPeriod.start | Stay period from date | 01/01/2021 |
| stayPeriod.end | Stay period to date | 01/02/2021 |
| searchId | Search ID | fdc1d9fc-7df3-45ef-8474-79d300efb669 |
| searchTracingKey | Search Tracing Key |  |
| status | Status | success |

---

## 9. Pricing Content

It returns pricing content details of specific rooms for a specific provider

**End Point** : `{HotelDetailsURL}/Hotel/PricingContent`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{
   "hotel": {
      "id": "15254729",
      "name": "Capitol Kempinski Singapore",
      "relevanceScore": 0,
      "providerFamily": "DOTW",
      "providerHotelId": "2305585",
      "language": null,
      "providerName": "DOTW",
      "geoCode": {
         "lat": 1.29419,
         "long": 103.85061
      },
      "neighbourhoods": [],
      "contact": {
         "address": {
            "line1": "15 Stamford Road",
            "line2": null,
            "destinationCode": "0",
            "city": "SINGAPORE",
            "state": null,
            "country": "SG",
            "postalCode": "178906"
         },
         "phones": [
            "+65 6368 8888"
         ],
         "faxes": null,
         "emails": null,
         "website": null
      },
      "chainCode": "1160",
      "chainName": "Kempinski Hotels And Resorts",
      "type": "",
      "website": null,
      "descriptions": [
         {
            "type": "General",
            "text": "Located in Singapore’s civic and cultural district, the iconic Capitol Building and Stamford House have been masterfully restored to house The Capitol Kempinski Hotel Singapore.\nThe 157 rooms and suites of the ultra-luxury hotel feature contemporary interior design and state-of-the-art technology. A proud member of The Leading Hotels of The World, this exclusive retreat guarantees peace, tranquillity and privacy in this vibrant city and offers Singapore’s first saltwater relaxation pool.\nThe hotel offers direct access to the legendary Capitol Theatre, once a jewel of Singapore’s cinema scene, which can be used for festive galas, conferences and extraordinary events today."
         },
         {
            "type": "General",
            "text": "The Capitol Kempinski Singapore is a  hotel."
         }
      ],
      "category": "",
      "starRating": 5,
      "facilityGroups": [
         {
            "id": 1,
            "groupId": 1,
            "name": "Parking"
         },
         {
            "id": 6,
            "groupId": 6,
            "name": "Business Center"
         },
         {
            "id": 7,
            "groupId": 7,
            "name": "Currency Exchange"
         },
         {
            "id": 8,
            "groupId": 8,
            "name": "Bar"
         },
         {
            "id": 9,
            "groupId": 9,
            "name": "Spa"
         },
         {
            "id": 12,
            "groupId": 12,
            "name": "Laundry Services"
         },
         {
            "id": 13,
            "groupId": 13,
            "name": "Swimming Pool"
         },
         {
            "id": 14,
            "groupId": 14,
            "name": "Restaurant"
         },
         {
            "id": 16,
            "groupId": 16,
            "name": "Internet"
         },
         {
            "id": 17,
            "groupId": 17,
            "name": "Fitness Facility"
         },
         {
            "id": 24,
            "groupId": 24,
            "name": "Room service"
         }
      ],
      "facilities": [
         {
            "id": 17956,
            "groupId": 0,
            "name": "Adjoining Rooms"
         },
         {
            "id": 719,
            "groupId": 0,
            "name": "Air Conditioning"
         },
         {
            "id": 3174,
            "groupId": 0,
            "name": "Banquet Hall"
         },
         {
            "id": 3134,
            "groupId": 8,
            "name": "Bar"
         },
         {
            "id": 663,
            "groupId": 1,
            "name": "Car Parking - Onsite Paid"
         },
         {
            "id": 18776,
            "groupId": 0,
            "name": "Complimentary In-Room Coffee Or Tea"
         },
         {
            "id": 48325,
            "groupId": 16,
            "name": "Complimentary Wifi Access"
         },
         {
            "id": 646,
            "groupId": 0,
            "name": "Concierge"
         },
         {
            "id": 3084,
            "groupId": 0,
            "name": "Doctor On Call"
         },
         {
            "id": 3871,
            "groupId": 7,
            "name": "Foreign Currency Exchange"
         },
         {
            "id": 3214,
            "groupId": 0,
            "name": "Hair Dresser"
         },
         {
            "id": 18316,
            "groupId": 0,
            "name": "Housekeeping-Daily"
         },
         {
            "id": 18366,
            "groupId": 12,
            "name": "Laundry Service"
         },
         {
            "id": 18856,
            "groupId": 0,
            "name": "Limousine Service - Paid"
         },
         {
            "id": 3074,
            "groupId": 0,
            "name": "Multilingual Staff"
         },
         {
            "id": 641,
            "groupId": 14,
            "name": "Restaurant"
         },
         {
            "id": 18446,
            "groupId": 24,
            "name": "Room Service - 24 Hours"
         },
         {
            "id": 1993,
            "groupId": 0,
            "name": "Safety Deposit Box"
         },
         {
            "id": 1995,
            "groupId": 0,
            "name": "Wheelchair Access"
         },
         {
            "id": 620,
            "groupId": 0,
            "name": "Av Equipment Available"
         },
         {
            "id": 3694,
            "groupId": 6,
            "name": "Conference Rooms"
         },
         {
            "id": 17536,
            "groupId": 0,
            "name": "Meeting Rooms"
         },
         {
            "id": 17246,
            "groupId": 0,
            "name": "Post/Courier Services"
         },
         {
            "id": 47935,
            "groupId": 17,
            "name": "Gymnasium"
         },
         {
            "id": 3891,
            "groupId": 0,
            "name": "Jacuzzi"
         },
         {
            "id": 1985,
            "groupId": 9,
            "name": "Spa"
         },
         {
            "id": 616,
            "groupId": 13,
            "name": "Swimming Pool- Outdoor"
         }
      ],
      "nearByAttractions": [
         {
            "name": "Changi International Airport",
            "distance": "20",
            "unit": "km",
            "description": null,
            "type": null
         },
         {
            "name": "Woodlands Trains Checkpoint",
            "distance": "15",
            "unit": "km",
            "description": null,
            "type": null
         },
         {
            "name": "Marina Bay Cruise Centre",
            "distance": "8",
            "unit": "km",
            "description": null,
            "type": null
         },
         {
            "name": "City Hall MRT Station",
            "distance": "0.15",
            "unit": "km",
            "description": null,
            "type": null
         }
      ],
      "images": [
         {
            "caption": "",
            "category": "Exterior",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/9OeFOwBk_ea0323f5ac1a2b11042a523c8a2c49a1.jpg"
         },
         {
            "caption": "HOTEL ROOM",
            "category": "Hotel Rooms",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/ZzxGGzHd_76b325fd3f2a88574da26ed8fc4a5b76.jpg"
         },
         {
            "caption": "HOTEL ROOM",
            "category": "Hotel Rooms",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/RZbIfCXf_2ad1418a75a3c247be4936a3b218fd43.jpg"
         },
         {
            "caption": "HOTEL ROOM",
            "category": "Hotel Rooms",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/IVmpkRtx_05fdcf04b060d8d75a2db20a114feb8d.jpg"
         },
         {
            "caption": "LOUNGE",
            "category": "Amenities and Services",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/FD14THpr_b31cc90118ff2763bfdae83c0c56cdf5.jpg"
         },
         {
            "caption": "DINING",
            "category": "Amenities and Services",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/LoSHRd2k_1841865ece3f5973cc98653a69ab213d.jpg"
         },
         {
            "caption": "DINING",
            "category": "Amenities and Services",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/oq2uZLTp_52822276deec6ef3f472797eb17d21b5.jpg"
         },
         {
            "caption": "SPA",
            "category": "Leisure and Sport Facilities",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/hGiUv9x7_c8b607a58ed03f6d2e52e8039bfbd262.jpg"
         },
         {
            "caption": "GYM",
            "category": "Leisure and Sport Facilities",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/HKr2JuPH_3e41bb2fa76c04460bc709dc46fe2243.jpg"
         },
         {
            "caption": "SWIMMING POOL",
            "category": "Leisure and Sport Facilities",
            "type": null,
            "roomCodes": [],
            "size": "Standard",
            "url": "https://us.dotwconnect.com/poze_hotel/23/2305585/zJf4MNC8_91af4e3eb7cff66fc2aa8ce9fe0fa6db.jpg"
         }
      ],
      "policies": [],
      "fees": [],
      "reviews": [],
      "checkinInfo": {
         "beginTime": "12:00 AM",
         "endTime": null,
         "instructions": null,
         "specialInstructions": null,
         "minAge": 18
      },
      "checkoutInfo": {
         "time": "12:00 AM"
      },
      "heroImage": "https://us.dotwconnect.com/poze_hotel/23/2305585/9OeFOwBk_ea0323f5ac1a2b11042a523c8a2c49a1.jpg",
      "distance": 0,
      "locationName": "Colonial"
   },
   "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| id | Hotel ID | 12356764 |
| name | Hotel Name | Capitol Kempinski Singapore |
| relevanceScore | Relevance Score | 0 |
| providerFamily | Provider Family | DOTW |
| providerHotelId | Provider Hotel ID | 3436547 |
| language | Language |  |
| providerName | Provider Name | DOTW |
| genCode | Latitude and Longitude | "lat" :  1.29419 , |
|  |  | "long" :  103.85061 |
| Neighbourhoods | Neighbourhoods |  |
| chainCode | Hotel Chain Code | 1160 |
| chainName | Hotel Chain Name | Kempinski Hotels And Resorts |
| type | Type |  |
| website | Website |  |
| descriptions:type | Type |  |
| descriptions:text | Text/Description |  |
| category | Category |  |
| starRating | StarRating | 3 |
| nearByAttractions:name | Nearby attraction |  |
| nearByAttractions:distance | Distance |  |
| nearByAttractions:unit | Distance Unit | Km |
| nearByAttractions:description | Description |  |
| nearByAttractions:type | Type |  |

---

## 10. Pricing

It returns the pricing details of specific rooms for a specific provider.

**End Point** : `{HotelDetailsURL}/Hotel/Pricing`  
**Method** : `POST`

### Request

_No request payload body required (or query parameters)._

### Response

```json
{
   "hotelId": "15254729",
   "roomGroup": [
      {
         "id": "4452e3d6-3550-4230-b3e1-8e59183aecd6",
         "code": null,
         "availability": 19,
         "description": null,
         "needsPriceCheck": true,
         "isPackageRate": false,
         "providerId": "600",
         "providerName": "Agoda",
         "room": {
            "id": "88013886",
            "standardRoomId": null,
            "standardRoomName": null,
            "name": "Classic Room with King Bed",
            "description": null,
            "beds": null,
            "smokingAllowed": false,
            "facilities": null,
            "images": null
         },
         "roomCount": 1,
         "occupancies": [
            {
               "occupancyId": 1,
               "numOfAdults": 1,
               "numOfChildren": 0,
               "childAges": []
            }
         ],
         "type": "negotiated",
         "baseRate": 19273.91,
         "totalRate": 22685.39,
         "minSellingRate": 0,
         "publishedRate": 22685.39,
         "taxes": [
            {
               "amount": 1569.16,
               "description": "Tax",
               "type": null
            },
            {
               "amount": 1842.32,
               "description": "Fees",
               "type": null
            }
         ],
         "fees": null,
         "discounts": null,
         "otherRateComponents": null,
         "commission": null,
         "dailyRates": null,
         "pointEquivalent": 0,
         "refundable": false,
         "allGuestsInfoRequired": false,
         "onlineCancellable": false,
         "specialRequestSupported": false,
         "payAtHotel": null,
         "cardRequired": true,
         "policies": [],
         "boardBasis": {
            "description": "Room only",
            "type": "RoomOnly"
         },
         "offers": [],
         "cancellationPolicies": [
            {
               "text": null,
               "rules": [
                  {
                     "value": 22685.39,
                     "valueType": "Amount",
                     "estimatedValue": 22685.39,
                     "start": "2020-05-08T00:00:00",
                     "end": "2020-05-09T00:00:00"
                  }
               ]
            }
         ],
         "includes": null,
         "additionalCharges": [],
         "depositRequired": false,
         "gstAllowed": false,
         "depositAmount": null,
         "guaranteeRequired": false,
         "gstOnCommission": 0
      }
   ],
   "priceId": "5a1bd93e-7f4d-4729-bec8-f3bd411fc398",
   "isPotentialSuspect": false,
   "suspectCategory": null,
   "status": "success"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| hotelId | Hotel ID | 2674745 |
| roomGroup.id | Room Group ID | Sf24-dsf234234-245fftg245 |
| roomGroup.code | Code |  |
| roomGroup.availability | Availability | 19 |
| roomGroup.description | Description |  |
| roomGroup.needsPriceCheck | Whether price check needed | 1 |
| roomGroup.isPackageRate | Whether package Rate |  |
| roomGroup.providerId | Provider ID | 600 |
| roomGroup.providerName | Provider Name | Agoda |
| roomGroup.room.id | Room ID | 786785678 |
| roomGroup.room.standardRoomId | Standard Room ID |  |
| roomGroup.room.standardRoomName | Standard Room Name |  |
| roomGroup.room.beds | Beds |  |
| roomGroup.room.smokingAllowed | Whether smoking is allowed |  |
| roomCount | Number of Rooms | 1 |
| occupancies.occupancyId | Occupancy ID |  |
| occupancies.numOfAdults | Number of Adults | 1 |
| occupancies.numOfChildren | Number of Children | 0 |
| occupancies.childAges | Ages of children |  |
| occupancies.type | Type | Negotiated |
| occupancies.baseRate | Base Rate |  |
| occupancies.totalRate | Total Rate |  |
| occupancies.minSallingRate | Minimum selling Rate |  |
| occupancies.publishedRate | Published Rate |  |
| taxes.amount | Tax |  |
| taxes.description | Tax Description |  |
| taxes.type | Tax Type |  |
| fees | Fees |  |
| discounts | Discounts |  |
| Commission | Commission |  |
| pointEquivalent | Point Equivalent | 0 |
| refundable | Whether refundable |  |
| allGuestInfoRequired | Whether information of all guests required |  |
| onlineCancellable | Whether cancellable online |  |
| specialRequestSupported | Whether special requests are supported |  |
| payAtHotel | Whether pay at hotel allowed |  |
| cardRequired | Whether Credit Card is mandatory |  |
| priceId | Price ID |  |
| isPotentialSuspect | Whether a potential suspect |  |
| suspectCategory | Category of suspect |  |
| status | Status | Success |

---

## 11. Create Itinerary

Creates an itinerary and returns a TransactionID.

**End Point** : `{HotelBookingURL}/Hotel/CreateItinerary`  
**Method** : `POST`

### Request

```json
{
   "TUI": "281e2665-5219-488a-9211-a5a250c9b7be",
   "ServiceEnquiry": "",
   "SpecialServiceRequest": "early checkin",
   "ContactInfo": {
      "Title": "Ms",
      "FName": "REXY",
      "LName": "RAJU",
      "Mobile": "1234123412",
      "Email": "test@test.com",
      "Address": "AKBAR ONLINE BOOKING COMPANY PVT LTD",
      "State": "Maharashtra",
      "City": "Near Crawford market Mumbai",
      "PIN": "400003",
      "GSTCompanyName": "",
      "GSTTIN": "",
      "GSTMobile": "",
      "GSTEmail": "",
      "UpdateProfile": true,
      "IsGuest": false,
      "CountryCode": "IN",
      "MobileCountryCode": "+91",
      "NetAmount": ""
   },
   "Auxiliaries": [
      {
         "Code": "PROMO",
         "Parameters": [
            {
               "Type": "Code",
               "Value": ""
            },
            {
               "Type": "ID",
               "Value": ""
            },
            {
               "Type": "Amount",
               "Value": ""
            }
         ]
      },
      {
            "Code": "CUSTOMER DETAILS",
            "parameters": [
                {
                    "Type": "Nationality",
                    "Value": "IN"
                },
                {
                    "Type": "Country of Residence",
                    "Value": "IN"
                }
            ]
        }
   ],
   "Rooms": [
      {
         "RoomId": "cf243f1f-151f-4a73-9b04-7dbd1a804b51",
         "GuestCode": "|1|1:A:25|",
         "SupplierName": "Fab",
         "RoomGroupId": "6878352d-956a-4c5b-9812-882b3f725335",
         "Guests": [
            {
               "GuestID": "YGVj",
               "Operation": "U",
               "Title": "Ms",
               "FirstName": "REXY",
               "MiddleName": "",
               "LastName": "RAJU",
               "MobileNo": "",
               "PaxType": "A",
               "Age": "",
               "Email": "",
               "Pan": "H123456P"
            }
         ]
      }
   ],
   "NetAmount": "1637",
   "ClientID": "Duh0NJDTryMpAfQvqvWnPw==",
   "DeviceID": "",
   "AppVersion": "",
   "SearchId": "36c42725-fcf0-4faf-8ad8-7f9b21368c45",
   "RecommendationId": "aab02f5b-b699-42c2-9ed7-65712d93400c",
   "LocationName": null,
   "HotelCode": "15402936",
   "CheckInDate": "2021-03-04",
   "CheckOutDate": "2021-03-05",
   "TravelingFor": "NTF"
}
```

### Response

```json
{
    "TUI": "42e7e4fc-3def-4f87-9d1c-8071bf92e04f|8004f249-0941-4443-94fc-008b3493e1ca|20200623204818",
    "TransactionID": 200002450,
    "NetAmount": 3689,
    "Code": "200",
    "Msg": [
        "Success"
    ]
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| TUI | Transaction Unique Identifier. | af80de34-fccb-4c28-9365-6740b775265b\|4913ba1b-4ecd-4909-9c1e-e1a47161c31c\|20200625105832 |
| TransactionID | TransactionID | 200002450 |
| NetAmount | Net Amount | 3689 |
| Code | Response Code | 200 |
| Msg | Response Message | Success |

---

## 12. Start Pay

This API initiates the payment with multiple payment options and initiates booking if the payment is successful. There are two possible modes Deposit mode and Multi payment mode. In deposit mode booking is done after debiting the agent and in multi-payment, booking is initiated in another API call, CommitPay, which is initiated automatically initiated, after deducting the amount from bank.

**End Point** : `{HotelBookingURL}/Hotel/StartPay`  
**Method** : `POST`

### Request

```json
{
   "SID": null,
   "TUI": "281e2665-5219-488a-9211-a5a250c9b7be|8c0895a5-f7ed-453e-a581-7352dbd63594|20200423090649",
   "ClientID": "test",
   "Email": null,
   "Promo": null,
   "TransactionID": 1234124,
   "PaymentType": "",
   "BankCode": "",
   "GateWayCode": "",
   "MerchantID": 0,
   "PaymentAmount": 920,
   "PaymentCharge": 0,
   "Card": { 
      "Number": "",
      "Expiry": "",
      "CVV": "",
      "CHName": "",
      "FName": null,
      "LName": null,
      "Address": "",
      "City": "",
      "State": "",
      "Country": "",
      "PIN": "",
      "International": false,
      "SaveCard": false,
      "EMIMonths": "0",
      "Token": null,
      "NumberAlias": null
   },
   "VPA": "",
   "CardAlias": "",
   "QuickPay": null,
   "RMSSignature": "",
   "TargetCurrency": "",
   "TargetAmount": 0,
   "ThirdPartyInfo": null,
   "Hold": false,
   "TripType": null,
   "Authorization": "Bearer sdfdsf.eyJ1bmlxdWVfbmFtZSI6IjMzMyIsIkFnZW50SW5mbyI6ImNoOHROYmpKajJUdHRBc1ZtQUlRMFc0YlNYeEtYTXg0dkhYMTZvb0ZVWVE9IiwicHdkIjoiS0Z0YWpLT2lsOUJkRG93TEd5VXNPZz09IiwiYWdlbnRDb2RlIjdsfoiL0tmZFl3ZXNxUHc9IiwiY2xpZW50SWQiOiJ3NS9rSWNKdm9KYz0iLCJCcm93c2VyS2V5IjoiY2NzdWwyVmEwYXhKWThwdTl0Y0FLOUc5ZXdNRnlIOHNBcTZidWNId0tUaGREb3dMR3lVc09nPT0iLCJuYmYiOjE1ODc2MTI4NjEsImV4cCI6MTU5NjI1Mjg2MSwiaWF0IjoxNTg3NjEyODYxLCJpc3MiOiJ3ZWJjb25uZWN0IiwiYXVkIjoiY2xpZW50In0.UBEXBm-apH3otK5eG_t3FFBy7PhE9mmsfdmwaERsadf",
   "QTransactionID": 0,
   "NetAmount": 920,
   "OnlinePayment": false,
   "DepositPayment": true,
   "ReleaseDate": "/Date(-62135596800000)/",
   "BrowserKey": "b80b98aafbba086e46e6643566cd67d7",
   "BrowserKeyFromToken": "b80b98aafbba086e46e6643566cd67d7",
   "AgentInfo": "333-1234-asdf-551234-1-306"
}
```

### Response

```json
{
   "TUI": "281e2665-5219-488a-9211-a5a250c9b7be|3ab975e2-736f-4f1d-8723-337f314a60bb|20200423090716",
   "Code": "200",
   "Msg": [
      "Success"
   ],
   "PaymentID": null,
   "TransactionID": 12344,
   "RedirectMode": "R",
   "PostData": null,
   "CRSPNR": "ASDFF",
   "BookStatus": "B0",
   "TUTransactionID": 0,
   "ClientID": "asdffdfasdf==",
   "GatewayCode": "",
   "RedirectUrl": "*******************/hotel/hotelconfirmation/281e2665-5219-488a-9211-a5a250c9b7be|3ab975e2-736f-4f1d-8723-337f314a60bb|20200423090716/200008119"
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| TUI | Transaction Unique Identifier | Dsafs78678-dsfs8234234-sdf76sdf-hgjahh2354fbg |
| Code | Code | 200 |
| Msg | Message | Success |
| PaymentID | Payment ID |  |
| TransactionID | Transaction ID | 4577 |
| RedirectMode | Redirect Mode | R |
| PostData | Post Data |  |
| CRSPNR | CRS PNR | JHFHJDD |
| BookStatus | Booking Status | B0 |
| TUTransactionID | TU Transaction ID |  |
| ClientID | Client ID | Dgfsfgs%df+= |
| GatewayCode | Payment Gateway Code |  |
| RedirectUrl | Redirect URL |  |

---

## 13. RetrieveBooking

This Api retrieves an existing itinerary.

**End Point** : `{HotelBookingURL}/Hotel/RetrieveBooking`  
**Method** : `POST`

### Request

```json
{
   "TUI": null,
   "ReferenceType": "T",
   "ReferenceNumber": "1234214",
   "ServiceType": null,
   "ClientID": "asdf",
   "RequestMode": "RB",
   "Contact": null,
   "Name": null
}
```

### Response

```json
{
   "TUI": "281e2665-5219-488a-9211-a5a250c9b7be|601eda5f-2d72-4e02-aa54-d630cdade1ff|20200423090747",
   "TransactionId": 1234214,
   "ServiceType": "HTL",
   "BookingConfirmationId": "ASDF",
   "BookingStatus": "B0",
   "CurrentStatus": "B0",
   "CheckInDate": "2021-03-04 12:0:00 AM",
   "CheckOutDate": "2021-03-05 12:0:00 AM",
   "CheckInTime": "12:00 PM",
   "CheckOutTime": "11:00 AM",
   "GrossFare": 1637.48,
   "NetFare": 920,
   "PaymentType": "",
   "PaymentStatus": "I8",
   "PaymentMode": "",
   "Nationality": "",
   "CountryOfResidence": "",
   "IssuedDate": "2020-04-23 09:6:47 AM",
   "IsBadlyMapped": "False",
   "SuspectCategory": "",
   "TravelingFor": "",
   "GateWayCode": "",
   "GateWayCharge": 0,
   "InvoiceDate": "23-04-2020",
   "HotelInfo": {
      "Code": "15402936",
      "Name": "FabHotel Khems Ooty",
      "heroimage": "https://i.travelapi.com/hotels/17000000/16210000/16202000/16201997/8929e70e_b.jpg",
      "Latitude": "11.409338",
      "Longitude": "76.70985",
      "LocationName": "",
      "Distance": 0,
      "AllowedCreditCards": "",
      "AllowedCountries": "",
      "SpecialRequestSupported": "False",
      "LeadPaxInfoRequired": "False",
      "IsCardRequired": "False",
      "GuaranteeRequired": "False",
      "Phone": "91-91-7042424242",
      "StarRating": "3",
      "HotelAddress": {
         "City": "Ooty",
         "State": "",
         "Country": "IN",
         "ZIP": "643001",
         "AddressLine1": "Shoreham Palace Road",
         "AddressLine2": "Off Ettiness Road"
      }
   },
   "Rooms": [
      {
         "ID": "983",
         "RoomId": "cf243f1f-151f-4a73-9b04-7dbd1a804b51",
         "GuestCode": "|1|1:A:25",
         "RoomGroupId": "6878352d-956a-4c5b-9812-882b3f725335",
         "Name": "Deluxe Room",
         "Description": "",
         "Capacity": "1",
         "NumberOfAdults": "1",
         "NumberOfChildren": "0",
         "NumberOfExtraBeds": "0",
         "SupplierConfirmationNumber": "",
         "SupplierId": "450",
         "RecommendationId": "aab02f5b-b699-42c2-9ed7-65712d93400c",
         "Refundable": "True",
         "Guests": [
            {
               "Title": "Ms",
               "FirstName": "asdf",
               "LastName": "asdf",
               "Email": "asdf@asdf.com",
               "MobileNumber": "1234123412",
               "PaxType": "A",
               "Age": "0",
               "AadharNo": "",
               "Pan": ""
            }
         ],
         "RoomRates": [
            {
               "TotalRate": 920.24,
               "BaseRate": 999,
               "Discount": 0,
               "ServiceCharge": 14.98,
               "Commission": 98.68,
               "TDSOnCommission": 4.93,
               "PromoAmount": 0,
               "AddonMarkup": 0,
               "AddonDiscount": 0,
               "AgentMarkup": 623.49,
               "Tax": {
                  "Description": "tax_and_service_fee",
                  "Amount": 0
               },
               "AdditionalCharges": {
                  "Amount": 0,
                  "Description": "",
                  "Currency": "",
                  "Type": "",
                  "Unit": "",
                  "Frequency": "",
                  "Text": ""
               }
            }
         ],
         "CancellationPolicy": [
            {
               "text": "Cancellation charge from 2020-04-23 03:37:13 AM to 2021-03-03 12:0:00 AM is INR: 0",
               "policy": "",
               "fromDate": "2020-04-23 03:37:13 AM",
               "toDate": "2021-03-03 12:0:00 AM",
               "amount": "0"
            },
            {
               "text": "Cancellation charge from 2021-03-03 12:0:00 AM to 2021-03-04 12:0:00 AM is INR: 1013.98",
               "policy": "",
               "fromDate": "2021-03-03 12:0:00 AM",
               "toDate": "2021-03-04 12:0:00 AM",
               "amount": "1014"
            }
         ],
         "RoomFacilities": [],
         "RoomInclusions": [],
         "RoomBoardBasis": [],
         "RoomPolicies": []
      }
   ],
   "HotelFacilities": [
      {
         "name": "Free breakfast"
      },
      {
         "name": "Restaurant"
      },
      {
         "name": "Luggage storage"
      },
      {
         "name": "Accessible bathroom"
      },
      {
         "name": "Free self parking"
      },
      {
         "name": "Free WiFi"
      },
      {
         "name": "Smoke-free property"
      },
      {
         "name": "Free newspapers in lobby"
      },
      {
         "name": "24-hour front desk"
      },
      {
         "name": ""
      }
   ],
   "ContactInfo": {
      "Title": "Ms",
      "FName": "asdf",
      "LName": "asdf",
      "Mobile": "91xxxxxxxx03",
      "Phone": "",
      "Email": "asxx@xxxxxxxxxsdf.com",
      "Address": "ASDFASDF DASFDASFDSAFDAS",
      "CountryCode": "IN",
      "State": "Maharashtra",
      "City": "ASDFSADF DASFDASF DSF",
      "PIN": "123412",
      "GSTCompanyName": "",
      "GSTTIN": "",
      "GSTMobile": "",
      "GSTEmail": "",
      "GSTAddress": "ASDFASDF ASDF DASFDSAF",
      "GSTState": "Maharashtra",
      "UpdateProfile": false,
      "IsGuest": false
   },
   "MoreInfo": [
      {
         "Field1": "",
         "Field2": "",
         "Field3": "",
         "Field4": "",
         "Code": "Policy",
         "Description": " Couples wishing to share a room must provide proof of marriage. Only registered guests are allowed in the guestrooms. ",
         "Name": "know before you go"
      },
      {
         "Field1": "",
         "Field2": "",
         "Field3": "",
         "Field4": "",
         "Code": "CheckinTime",
         "Description": "Check-In Time is 12:00 PM",
         "Name": "CheckinTime"
      },
      {
         "Field1": "",
         "Field2": "",
         "Field3": "",
         "Field4": "",
         "Code": "CheckinInstructions",
         "Description": "Extra-person charges may apply and vary depending on property policy. Government-issued photo identification and a credit card, debit card, or cash deposit are required at check-in for incidental charges. Special requests are subject to availability upon check-in and may incur additional charges. Special requests cannot be guaranteed. Only bookings from non-local guests are accepted. Guests whose residence is within the same city as the property will not be allowed to check in. Please note that cultural norms and guest policies may differ by country and by property. The policies listed are provided by the property. ",
         "Name": "CheckinInstructions"
      },
      {
         "Field1": "",
         "Field2": "",
         "Field3": "",
         "Field4": "",
         "Code": "CheckinSpecialInstructions",
         "Description": "This property doesn't offer after-hours check-in. Guests arriving late won't be able to check in until the next morning. Taxes are subject to change based on Goods and Services Tax (GST) implementation. For more details, please contact the property using the information on the reservation confirmation received after booking. Please note that PAN cards are not accepted as identification at this property. For more details, please contact the office using the information on the reservation confirmation received after booking.",
         "Name": "CheckinSpecialInstructions"
      },
      {
         "Field1": "",
         "Field2": "",
         "Field3": "",
         "Field4": "",
         "Code": "CheckoutTime",
         "Description": "Check-Out Time is 11:00 AM",
         "Name": "CheckoutTime"
      }
   ],
   "ItineryStatus": {
      "Remarks": "",
      "BookingStatus": "B0",
      "CurrentStatus": "B0",
      "ServiceType": "HTL",
      "StatusDetails": [
         {
            "Status": "B0",
            "PaxSectorID": ""
         }
      ]
   },
   "Rules": null,
   "Promo": [
      {
         "Code": "",
         "Amount": 0,
         "EmployeeID": null,
         "ConvenienceFee": 0
      }
   ],
   "Auxiliaries": [
      {
         "Code": "",
         "EmployeeID": null,
         "Amount": 0
      }
   ],
   "PaymentSummary": [],
   "Remarks": null,
   "Code": "200",
   "Msg": [
      "Success"
   ]
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| TUI | Transaction Unique Identifier | Dsfsda89sdfsd-dsf8sdfsf-465237ghkj-ghf59-gfhdgfh |
| TransactionID | Transaction ID | 5688534 |
| ServiceType | Service Type | HTL |
|  | HTL – Hotel |  |
| BookingConfirmationId | Booking Confirmation ID | GDSH |
| BookingStatus | Booking Status | B0 |
| CurrentStatus | The CurrentStatus  will returns the current status of the booking | B0 |
| CheckInDate | Check In Date | 44116 |
| CheckOutDate | Check Out Date | 44117 |
| CheckInTime | Check In Time | 12.00.00 PM |
| CheckOutTime | Check Out Time | 11.00.00 AM |
| GrossFare | Gross Fare |  |
| NetFare | Net Fare |  |
| PaymentType | Payment Type |  |
| PaymentStatus | Payment Status | IS |
| PaymentMode | Payment Mode |  |
| Nationality | Nationality |  |
| CountryOfResidence | Country of residence |  |
| IssuedDate | Issued Date |  |
| IsBadlyMapped | Whether bad mapping |  |
| SuspectCategory | Suspect Category |  |
| TravelingFor | Travelling For |  |
| GatewayCode | Payment Gateway Code |  |
| GateWayCharge | Payment Gateway Charge |  |
| InvoiceDate | Invoice Date |  |
| HotelInfo.Code | Hotel Code | 43536546 |
| HotelInfo.Name | Hotel Name | FabHotel Khems Ooty |
| HotelInfo.heroimage | Image |  |
| HotelInfo.LocationName | Location Name |  |
| HotelInfo.Distance | Distance from city center |  |
| HotelInfo.AllowedCreditCards | Allowed Credit Cards |  |
| HotelInfo.AllowedCountries | Allowed countries |  |
| HotelInfo.SpecialRequestSupported | Whether special request are supported |  |
| HotelInfo.LeadPaxInfoRequired | Whether lead pax info required |  |
| HotelInfo.IsCardRequired | Whether card required for booking |  |
| HotelInfo.GuaranteeRequired | Whether guarantee required |  |
| HotelInfo.Phone | Phone |  |
| HotelInfo.StarRating | Star Rating |  |
| Rooms.ID | ID | 5734 |
| Rooms.RoomId | Room ID | Fdsafa789dsf-sagsdfg4sagds-sg89fgfg-dfsgdsj6746rtr2 |
| Rooms.GuestCode | Guest Code | \|1\|1.A.25 |
| Rooms.RoomGroupId | Room Group Id | Fdsafs-dfghdsg78dgfhgfj-54gfh |
| Rooms.Name | Name |  |
| Rooms.Description | Description |  |
| Rooms.Capacity | Capacity | 1 |
| Rooms.NumberOfAdults | Number of adults |  |
| Rooms.NumberOfChildren | Number of Children |  |
| Rooms.NumberOfExtraBeds | Number of extra beds |  |
| Rooms.SupplierConfirmationNumber | Supplier confirmation number |  |
| Rooms.SupplierId | Supplier ID |  |
| Rooms.RecommendationId | Recommendation id | Sfgfg-g5435gfhdf67dghdgf-gh78gfhjhfgj-h54546 |
| Rooms.Refundable | Whether refundable |  |
| Rooms.Guests.Title | Title | Mr |
| Rooms.Guests.FirstName | First Name |  |
| Rooms.Guests.LastName | Last Name |  |
| Rooms.Guests.Email | Email |  |
| Rooms.Guests.MobileNumber | Mobile Number |  |
| Rooms.Guests.PaxType | PaxType<br>A- Adult<br>C - Child | A |
| Rooms.Guests.Age | Age |  |
| Rooms.Guests.AadharNo | Aadhar Number |  |
| Rooms.Guests.Pan | Pan Card Number |  |
| Rooms.RoomRates.TotalRate | Total Rate |  |
| Rooms.RoomRates.BaseRate | Base Rate |  |
| Rooms.RoomRates.Discount | Discount |  |
| Rooms.RoomRates.ServiceCharge | Service Charge |  |
| Rooms.RoomRates.Commission | Commission |  |
| Rooms.RoomRates.TDSOnCommission | TDS on commission |  |
| Rooms.RoomRates.PromoAmount | Promo Amount |  |
| Rooms.RoomRates.AddonMarkup | Add-On markup |  |
| Rooms.RoomRates.AddonDiscount | Add-on discount |  |
| Rooms.RoomRates.AgentMarkup | Agent Markup |  |
| Rooms.RoomRates.CancellationPolicy.text | Cancellation Policy |  |
| Rooms.RoomRates. CancellationPolicy.fromDate | From Date |  |
| Rooms.RoomRates. CancellationPolicy.toDate | To Date |  |
| Rooms.RoomRates. CancellationPolicy.amount | Policy Amount |  |

---

## 14. Cancel

This API initiates cancellation of a hotel booking.

**End Point** : `{HotelBookingURL}/Hotel/Cancel`  
**Method** : `POST`

### Request

```json
{
  "Remarks": "test",
  "TUI": "32d0991a-a135-4c13-240f-957e52dc0618|c5c28b91-9e14-4214-84fe-235f00ac87c6|20231103085148",
  "TransactionID": 1234123412,
  "YearType": "19"
}
```

### Response

```json
{
  "TUI": "a2d099da-a135-4c13-b40f-957e52dc0618|c5ca8b95-9eb4-4114-83ff-285f00ac87c6|20231103085148",
  "Msg": [
    "Success"
  ],
  "Code": "200",
  "CancellationID": 24001303134,
  "AutoCancellation": true
}
```

### Parameters / Field Definitions

| Field Name | Description | Sample Data |
| :--- | :--- | :--- |
| TUI | Transaction Unique Identifier. | af80de34-fccb-4c28-9365-6740b775265b\|4913ba1b-4ecd-4909-9c1e-e1a47161c31c\|20200625105832 |
| AutoCancellation | AutoCancellation | true |
| CancellationID | CancellationID | 39002903176 |
| Code | Response Code | 200 |
| Msg | Response Message | Success |

---

