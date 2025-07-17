# Restaurant Reservation API Documentation

## Overview

This is a RESTful API for managing restaurant reservations, customers, and tables. The API provides full CRUD operations and includes features like double-booking prevention and availability checking.

**Base URL:** `http://localhost:8000/api`

## Authentication

Currently, the API does not require authentication. All endpoints are publicly accessible for development purposes.

## Response Format

All API responses follow a consistent JSON format:

```json
{
    "success": true|false,
    "data": {...},
    "message": "Description of the result",
    "errors": {...} // Only present when success is false
}
```

## HTTP Status Codes

- `200` - OK (Success)
- `201` - Created (Resource created successfully)
- `422` - Unprocessable Entity (Validation errors)
- `404` - Not Found (Resource not found)
- `500` - Internal Server Error




## Customers API

### List All Customers
**GET** `/customers`

Returns a list of all customers with their reservations.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john.doe@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z",
            "reservations": [...]
        }
    ],
    "message": "Customers retrieved successfully"
}
```

### Get Customer by ID
**GET** `/customers/{id}`

Returns a specific customer with their reservations and table details.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "phone": "+1234567890",
        "email": "john.doe@example.com",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "reservations": [
            {
                "id": 1,
                "customer_id": 1,
                "table_id": 1,
                "date_time": "2024-01-02T19:00:00.000000Z",
                "note": "Anniversary dinner",
                "table": {
                    "id": 1,
                    "number": 1,
                    "capacity": 2
                }
            }
        ]
    },
    "message": "Customer retrieved successfully"
}
```

### Create Customer
**POST** `/customers`

Creates a new customer.

**Request Body:**
```json
{
    "name": "Jane Smith",
    "phone": "+1234567891",
    "email": "jane.smith@example.com"
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `phone`: Required, string, unique, max 20 characters, valid phone format
- `email`: Required, valid email, unique, max 255 characters

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Jane Smith",
        "phone": "+1234567891",
        "email": "jane.smith@example.com",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Customer created successfully"
}
```

### Update Customer
**PUT** `/customers/{id}`

Updates an existing customer.

**Request Body:**
```json
{
    "name": "Jane Smith Updated",
    "phone": "+1234567891",
    "email": "jane.smith.updated@example.com"
}
```

**Validation Rules:**
- `name`: Sometimes required, string, max 255 characters
- `phone`: Sometimes required, string, unique (excluding current record), max 20 characters
- `email`: Sometimes required, valid email, unique (excluding current record), max 255 characters

### Delete Customer
**DELETE** `/customers/{id}`

Deletes a customer and all associated reservations.

**Response:**
```json
{
    "success": true,
    "message": "Customer deleted successfully"
}
```


## Tables API

### List All Tables
**GET** `/tables`

Returns a list of all tables with their reservations.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "number": 1,
            "capacity": 2,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z",
            "reservations": [...]
        }
    ],
    "message": "Tables retrieved successfully"
}
```

### Get Table by ID
**GET** `/tables/{id}`

Returns a specific table with its reservations and customer details.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "number": 1,
        "capacity": 2,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "reservations": [
            {
                "id": 1,
                "customer_id": 1,
                "table_id": 1,
                "date_time": "2024-01-02T19:00:00.000000Z",
                "note": "Anniversary dinner",
                "customer": {
                    "id": 1,
                    "name": "John Doe",
                    "phone": "+1234567890",
                    "email": "john.doe@example.com"
                }
            }
        ]
    },
    "message": "Table retrieved successfully"
}
```

### Create Table
**POST** `/tables`

Creates a new table.

**Request Body:**
```json
{
    "number": 7,
    "capacity": 4
}
```

**Validation Rules:**
- `number`: Required, integer, unique, minimum 1
- `capacity`: Required, integer, minimum 1, maximum 20

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 7,
        "number": 7,
        "capacity": 4,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Table created successfully"
}
```

### Update Table
**PUT** `/tables/{id}`

Updates an existing table.

**Request Body:**
```json
{
    "number": 7,
    "capacity": 6
}
```

### Delete Table
**DELETE** `/tables/{id}`

Deletes a table and all associated reservations.

### Check Table Availability
**POST** `/tables/{id}/check-availability`

Checks if a table is available at a specific date and time.

**Request Body:**
```json
{
    "date_time": "2024-01-02T19:00:00",
    "exclude_reservation_id": 1
}
```

**Parameters:**
- `date_time`: Required, date, must be in the future
- `exclude_reservation_id`: Optional, integer, reservation ID to exclude from availability check

**Response:**
```json
{
    "success": true,
    "data": {
        "table_id": 1,
        "table_number": 1,
        "date_time": "2024-01-02T19:00:00",
        "is_available": true
    },
    "message": "Table is available"
}
```


## Reservations API

### List All Reservations
**GET** `/reservations`

Returns a list of reservations with optional filtering.

**Query Parameters:**
- `upcoming`: Boolean, filter for upcoming reservations only
- `today`: Boolean, filter for today's reservations only
- `customer_id`: Integer, filter by customer ID
- `table_id`: Integer, filter by table ID

**Examples:**
- `/reservations?upcoming=1` - Get upcoming reservations
- `/reservations?today=1` - Get today's reservations
- `/reservations?customer_id=1` - Get reservations for customer ID 1

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "customer_id": 1,
            "table_id": 1,
            "date_time": "2024-01-02T19:00:00.000000Z",
            "note": "Anniversary dinner",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z",
            "customer": {
                "id": 1,
                "name": "John Doe",
                "phone": "+1234567890",
                "email": "john.doe@example.com"
            },
            "table": {
                "id": 1,
                "number": 1,
                "capacity": 2
            }
        }
    ],
    "message": "Reservations retrieved successfully"
}
```

### Get Reservation by ID
**GET** `/reservations/{id}`

Returns a specific reservation with customer and table details.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "customer_id": 1,
        "table_id": 1,
        "date_time": "2024-01-02T19:00:00.000000Z",
        "note": "Anniversary dinner",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "customer": {
            "id": 1,
            "name": "John Doe",
            "phone": "+1234567890",
            "email": "john.doe@example.com"
        },
        "table": {
            "id": 1,
            "number": 1,
            "capacity": 2
        }
    },
    "message": "Reservation retrieved successfully"
}
```

### Create Reservation
**POST** `/reservations`

Creates a new reservation with double-booking prevention.

**Request Body:**
```json
{
    "customer_id": 1,
    "table_id": 1,
    "date_time": "2024-01-02T19:00:00",
    "note": "Birthday celebration"
}
```

**Validation Rules:**
- `customer_id`: Required, integer, must exist in customers table
- `table_id`: Required, integer, must exist in tables table
- `date_time`: Required, date, must be in the future
- `note`: Optional, string, max 1000 characters

**Double-booking Prevention:**
The API automatically checks if the table is available at the requested time and returns an error if it's already booked.

**Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 4,
        "customer_id": 1,
        "table_id": 1,
        "date_time": "2024-01-02T19:00:00.000000Z",
        "note": "Birthday celebration",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z",
        "customer": {...},
        "table": {...}
    },
    "message": "Reservation created successfully"
}
```

**Error Response (422) - Double Booking:**
```json
{
    "success": false,
    "message": "Table is not available at the requested time",
    "errors": {
        "table_id": ["The selected table is already booked for this time."]
    }
}
```

### Update Reservation
**PUT** `/reservations/{id}`

Updates an existing reservation with double-booking prevention.

**Request Body:**
```json
{
    "customer_id": 1,
    "table_id": 2,
    "date_time": "2024-01-02T20:00:00",
    "note": "Updated reservation"
}
```

**Note:** When updating, the system excludes the current reservation from the availability check to allow time/table changes.

### Delete Reservation
**DELETE** `/reservations/{id}`

Deletes a reservation.

**Response:**
```json
{
    "success": true,
    "message": "Reservation deleted successfully"
}
```


## Health Check

### API Health Check
**GET** `/health`

Returns the API status and current timestamp.

**Response:**
```json
{
    "success": true,
    "message": "Restaurant Reservation API is running",
    "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

## Testing Guide

### Using cURL

#### 1. Test API Health
```bash
curl -X GET http://localhost:8000/api/health
```

#### 2. Create a Customer
```bash
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d 
```

#### 3. Create a Table
```bash
curl -X POST http://localhost:8000/api/tables \
  -H "Content-Type: application/json" \
  -d 
```

#### 4. Create a Reservation
```bash
curl -X POST http://localhost:8000/api/reservations \
  -H "Content-Type: application/json" \
  -d 
```

#### 5. Check Table Availability
```bash
curl -X POST http://localhost:8000/api/tables/1/check-availability \
  -H "Content-Type: application/json" \
  -d 
```

#### 6. Get Upcoming Reservations
```bash
curl -X GET "http://localhost:8000/api/reservations?upcoming=1"
```

#### 7. Testing Double-booking Prevention
```bash
curl -X POST http://localhost:8000/api/reservations \
  -H "Content-Type: application/json" \
  -d 
```

#### 8. Testing Validation Errors
```bash
# Invalid email
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d 

# Duplicate phone number
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d 

# Past date reservation
curl -X POST http://localhost:8000/api/reservations \
  -H "Content-Type: application/json" \
  -d 
```

### Using Postman

1. **Import Collection**: Create a new Postman collection for the Restaurant Reservation API
2. **Set Base URL**: Set the base URL variable to `http://localhost:8000/api`
3. **Add Requests**: Create requests for each endpoint using the examples above
4. **Test Scenarios**: 
   - Create customers, tables, and reservations
   - Test double-booking prevention
   - Test validation errors
   - Test filtering and querying

### Test Scenarios

#### Scenario 1: Basic CRUD Operations
1. Create a customer
2. Create a table
3. Create a reservation
4. Update the reservation
5. Delete the reservation

#### Scenario 2: Double-booking Prevention
1. Create a customer and table
2. Create a reservation for a specific date/time
3. Try to create another reservation for the same table and time
4. Verify that the second reservation is rejected

#### Scenario 3: Availability Checking
1. Create multiple tables
2. Create reservations for some tables
3. Check availability for different dates and times
4. Verify correct availability status

#### Scenario 4: Data Relationships
1. Create customers and tables
2. Create multiple reservations
3. Fetch customer with reservations
4. Fetch table with reservations
5. Verify all relationships are properly loaded

### Error Testing

#### Test Validation Errors
```bash
# Invalid email
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d 

# Duplicate phone number
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d 

# Past date reservation
curl -X POST http://localhost:8000/api/reservations \
  -H "Content-Type: application/json" \
  -d 
```

## Flutter Integration

### HTTP Client Setup
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';
  
  static Future<Map<String, dynamic>> get(String endpoint) async {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: {'Content-Type': 'application/json'},
    );
    return json.decode(response.body);
  }
  
  static Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode(data),
    );
    return json.decode(response.body);
  }
}
```

### Example Usage in Flutter
```dart
// Create a customer
final customerData = {
  'name': 'John Doe',
  'phone': '+1234567890',
  'email': 'john@example.com',
};
final result = await ApiService.post('/customers', customerData);

// Get reservations
final reservations = await ApiService.get('/reservations?upcoming=1');

// Create a reservation
final reservationData = {
  'customer_id': 1,
  'table_id': 1,
  'date_time': '2024-12-25T19:00:00',
  'note': 'Christmas dinner',
};
final reservation = await ApiService.post('/reservations', reservationData);
```

