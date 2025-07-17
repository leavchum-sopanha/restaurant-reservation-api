#!/bin/bash

# Restaurant Reservation API Test Script
# This script tests all the main API endpoints

BASE_URL="http://localhost:8000/api"

echo "🍽️  Restaurant Reservation API Test Script"
echo "=========================================="
echo ""

# Colors for output
RED=\'\\033[0;31m\'
GREEN=\'\\033[0;32m\'
YELLOW=\'\\033[1;33m\'
NC=\'\\033[0m\' # No Color

# Function to test API endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local data=$3
    local description=$4
    
    echo -e "${YELLOW}Testing:${NC} $description"
    echo -e "${YELLOW}$method${NC} $BASE_URL$endpoint"
    
    if [ -z "$data" ]; then
        response=$(curl -s -w "\\n%{http_code}" -X $method "$BASE_URL$endpoint" -H "Content-Type: application/json")
    else
        response=$(curl -s -w "\\n%{http_code}" -X $method "$BASE_URL$endpoint" -H "Content-Type: application/json" -d "$data")
    fi
    
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n -1)
    
    if [[ $http_code -ge 200 && $http_code -lt 300 ]]; then
        echo -e "${GREEN}✅ Success ($http_code)${NC}"
    else
        echo -e "${RED}❌ Failed ($http_code)${NC}"
    fi
    
    echo "Response: $body"
    echo ""
    echo "---"
    echo ""
}

echo "1. Testing API Health Check"
test_endpoint "GET" "/health" "" "API Health Check"

echo "2. Testing Customer Operations"
test_endpoint "POST" "/customers" \'{\"name\":\"Test Customer\",\"phone\":\"+1234567890\",\"email\":\"test@example.com\"}\' "Create Customer"
test_endpoint "GET" "/customers" "" "List All Customers"
test_endpoint "GET" "/customers/1" "" "Get Customer by ID"

echo "3. Testing Table Operations"
test_endpoint "POST" "/tables" \'{\"number\":10,\"capacity\":4}\' "Create Table"
test_endpoint "GET" "/tables" "" "List All Tables"
test_endpoint "GET" "/tables/1" "" "Get Table by ID"

echo "4. Testing Reservation Operations"
# Use a future date for the reservation
FUTURE_DATE=$(date -d "+1 day" \'+%Y-%m-%dT19:00:00\')
test_endpoint "POST" "/reservations" "{\"customer_id\":1,\"table_id\":1,\"date_time\":\"$FUTURE_DATE\",\"note\":\"Test reservation\"}" "Create Reservation"
test_endpoint "GET" "/reservations" "" "List All Reservations"
test_endpoint "GET" "/reservations?upcoming=1" "" "List Upcoming Reservations"

echo "5. Testing Table Availability"
test_endpoint "POST" "/tables/1/check-availability" "{\"date_time\":\"$FUTURE_DATE\"}" "Check Table Availability"

echo "6. Testing Double-booking Prevention"
test_endpoint "POST" "/reservations" "{\"customer_id\":1,\"table_id\":1,\"date_time\":\"$FUTURE_DATE\",\"note\":\"Duplicate reservation test\"}" "Test Double-booking Prevention (Should Fail)"

echo "7. Testing Validation Errors"
test_endpoint "POST" "/customers" \'{\"name\":\"\",\"phone\":\"invalid\",\"email\":\"not-an-email\"}\' "Test Validation Errors (Should Fail)"

echo ""
echo "🎉 API Testing Complete!"
echo ""
echo "Note: Some tests may fail if:"
echo "- The API server is not running (php artisan serve)"
echo "- Database is not set up (php artisan migrate)"
echo "- Required data doesn\'t exist"
echo ""
echo "To run this script:"
echo "1. Make it executable: chmod +x test_api.sh"
echo "2. Run it: ./test_api.sh"

