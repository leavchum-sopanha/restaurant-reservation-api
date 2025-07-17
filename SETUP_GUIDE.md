# Restaurant Reservation API - Setup Guide

This guide will help you set up and run the Restaurant Reservation API on your local machine.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

### Required Software
- **PHP 8.1 or higher** with the following extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
- **Composer** (PHP dependency manager)
- **MySQL 8.0** or **MariaDB 10.3** (or PostgreSQL/SQLite as alternatives)
- **Git** (for version control)

### Optional but Recommended
- **Laravel Installer** (for future Laravel projects)
- **Postman** or **Insomnia** (for API testing)
- **MySQL Workbench** or **phpMyAdmin** (for database management)

## Installation Steps

### Step 1: Download the Project Files

1. Download all the project files to your desired directory
2. Navigate to the project directory:
   ```bash
   cd restaurant-reservation-api
   ```

### Step 2: Install PHP Dependencies

This project does not include the `vendor` directory. You need to install all required PHP packages using Composer:

```bash
composer install
```

If you don't have Composer installed, download it from [getcomposer.org](https://getcomposer.org/).

### Step 3: Environment Configuration

1. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
   
   Note: If `.env.example` doesn't exist, use the provided `.env` file directly.

2. Generate an application key:
   ```bash
   php artisan key:generate
   ```

3. Configure your database settings in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restaurant_reservation
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

### Step 4: Database Setup

1. Create a new MySQL database:
   ```sql
   CREATE DATABASE restaurant_reservation;
   ```

2. Run the database migrations:
   ```bash
   php artisan migrate
   ```

3. (Optional) Seed the database with sample data:
   ```bash
   php artisan db:seed
   ```

### Step 5: Start the Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

## Verification

### Test the API

1. **Health Check**: Visit `http://localhost:8000/api/health` in your browser
2. **API Endpoints**: Use the base URL `http://localhost:8000/api` for all API calls

### Expected Response
```json
{
    "success": true,
    "message": "Restaurant Reservation API is running",
    "timestamp": "2024-01-01T00:00:00.000000Z"
}
```


## Database Configuration Options

### MySQL Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_reservation
DB_USERNAME=root
DB_PASSWORD=
```

### PostgreSQL Configuration
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restaurant_reservation
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### SQLite Configuration (for development)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

## Artisan Commands Reference

### Database Commands
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Reset and re-run all migrations
php artisan migrate:fresh

# Seed database with sample data
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Development Commands
```bash
# Start development server
php artisan serve

# Start server on specific port
php artisan serve --port=8080

# Start server on specific host
php artisan serve --host=0.0.0.0 --port=8000

# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear
```

## Project Structure

```
restaurant-reservation-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CustomerController.php
│   │   │   ├── TableController.php
│   │   │   └── ReservationController.php
│   │   ├── Middleware/
│   │   │   └── Cors.php
│   │   ├── Requests/
│   │   │   ├── StoreCustomerRequest.php
│   │   │   └── StoreReservationRequest.php
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Customer.php
│   │   ├── Table.php
│   │   └── Reservation.php
│   └── Services/
│       └── ReservationService.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_customers_table.php
│   │   ├── 2024_01_01_000002_create_tables_table.php
│   │   └── 2024_01_01_000003_create_reservations_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
├── config/
│   └── database.php
├── .env
├── composer.json
├── API_DOCUMENTATION.md
└── SETUP_GUIDE.md
```

## API Endpoints Summary

### Customers
- `GET /api/customers` - List all customers
- `POST /api/customers` - Create customer
- `GET /api/customers/{id}` - Get customer by ID
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

### Tables
- `GET /api/tables` - List all tables
- `POST /api/tables` - Create table
- `GET /api/tables/{id}` - Get table by ID
- `PUT /api/tables/{id}` - Update table
- `DELETE /api/tables/{id}` - Delete table
- `POST /api/tables/{id}/check-availability` - Check availability

### Reservations
- `GET /api/reservations` - List reservations (with filters)
- `POST /api/reservations` - Create reservation
- `GET /api/reservations/{id}` - Get reservation by ID
- `PUT /api/reservations/{id}` - Update reservation
- `DELETE /api/reservations/{id}` - Delete reservation

### Health Check
- `GET /api/health` - API health status


## Troubleshooting

### Common Issues and Solutions

#### 1. "Class not found" errors
**Problem**: Composer autoload not updated
**Solution**:
```bash
composer dump-autoload
```

#### 2. Database connection errors
**Problem**: Incorrect database credentials or database doesn't exist
**Solutions**:
- Verify database credentials in `.env` file
- Ensure database server is running
- Create the database if it doesn't exist
- Test connection:
  ```bash
  php artisan tinker
  DB::connection()->getPdo();
  ```

#### 3. Migration errors
**Problem**: Migration files not found or database schema conflicts
**Solutions**:
```bash
# Reset migrations
php artisan migrate:fresh

# Check migration status
php artisan migrate:status

# Create database manually if needed
mysql -u root -p -e "CREATE DATABASE restaurant_reservation;"
```

#### 4. CORS errors when testing with frontend
**Problem**: Cross-origin requests blocked
**Solution**: The CORS middleware is already configured. If issues persist:
- Verify the middleware is registered in `app/Http/Kernel.php`
- Check that the frontend is making requests to the correct URL
- For development, ensure the API server is running on `http://localhost:8000`

#### 5. "Key not found" errors
**Problem**: Application key not generated
**Solution**:
```bash
php artisan key:generate
```

#### 6. Permission errors (Linux/Mac)
**Problem**: File permission issues
**Solutions**:
```bash
# Set proper permissions for storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If using Apache/Nginx, ensure web server has access
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### 7. Port already in use
**Problem**: Port 8000 is already occupied
**Solution**:
```bash
# Use a different port
php artisan serve --port=8080

# Or find and kill the process using port 8000
lsof -ti:8000 | xargs kill -9
```

### Development Tips

#### 1. Enable Debug Mode
Ensure `APP_DEBUG=true` in your `.env` file for detailed error messages during development.

#### 2. Log Files
Check Laravel logs for detailed error information:
```bash
tail -f storage/logs/laravel.log
```

#### 3. Database Queries
To see all database queries being executed:
```bash
# Add to any controller method for debugging
DB::enableQueryLog();
// Your code here
dd(DB::getQueryLog());
```

#### 4. API Testing
Use these tools for testing your API:
- **Postman**: Create collections for all endpoints
- **cURL**: Command-line testing (examples in API documentation)
- **Laravel Tinker**: Interactive PHP shell for testing models
  ```bash
  php artisan tinker
  Customer::all();
  ```

### Performance Optimization

#### 1. Configuration Caching
For production environments:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. Database Indexing
The migrations already include necessary indexes, but for large datasets consider:
- Adding indexes on frequently queried columns
- Using database query optimization tools

#### 3. API Response Caching
Consider implementing caching for frequently accessed data:
```php
// Example: Cache customer list for 10 minutes
$customers = Cache::remember('customers', 600, function () {
    return Customer::all();
});
```

## Production Deployment

### Environment Configuration
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure proper database credentials
4. Set up proper web server (Apache/Nginx)
5. Configure SSL certificates for HTTPS

### Security Considerations
1. Use strong database passwords
2. Implement API authentication (Laravel Sanctum/Passport)
3. Set up rate limiting
4. Configure proper CORS policies
5. Use HTTPS in production
6. Regular security updates

### Monitoring
1. Set up error logging and monitoring
2. Monitor API performance and response times
3. Set up database monitoring
4. Configure backup strategies

## Next Steps

1. **Test the API**: Use the provided API documentation to test all endpoints
2. **Integrate with Flutter**: Use the Flutter integration examples in the API documentation
3. **Customize**: Modify the code to fit your specific requirements
4. **Add Features**: Consider adding authentication, notifications, or reporting features
5. **Deploy**: When ready, deploy to your production environment

## Support

For detailed setup instructions, see [SETUP_GUIDE.md](SETUP_GUIDE.md)
For API usage examples, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## License

This project is open-source software licensed under the MIT license.


## Troubleshooting

### Common Issues and Solutions

#### 1. "Class not found" errors
**Problem**: Composer autoload not updated
**Solution**:
```bash
composer dump-autoload
```

#### 2. Database connection errors
**Problem**: Incorrect database credentials or database doesn't exist
**Solutions**:
- Verify database credentials in `.env` file
- Ensure database server is running
- Create the database if it doesn't exist
- Test connection:
  ```bash
  php artisan tinker
  DB::connection()->getPdo();
  ```

#### 3. Migration errors
**Problem**: Migration files not found or database schema conflicts
**Solutions**:
```bash
# Reset migrations
php artisan migrate:fresh

# Check migration status
php artisan migrate:status

# Create database manually if needed
mysql -u root -p -e "CREATE DATABASE restaurant_reservation;"
```

#### 4. CORS errors when testing with frontend
**Problem**: Cross-origin requests blocked
**Solution**: The CORS middleware is already configured. If issues persist:
- Verify the middleware is registered in `app/Http/Kernel.php`
- Check that the frontend is making requests to the correct URL
- For development, ensure the API server is running on `http://localhost:8000`

#### 5. "Key not found" errors
**Problem**: Application key not generated
**Solution**:
```bash
php artisan key:generate
```

#### 6. Permission errors (Linux/Mac)
**Problem**: File permission issues
**Solutions**:
```bash
# Set proper permissions for storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If using Apache/Nginx, ensure web server has access
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### 7. Port already in use
**Problem**: Port 8000 is already occupied
**Solution**:
```bash
# Use a different port
php artisan serve --port=8080

# Or find and kill the process using port 8000
lsof -ti:8000 | xargs kill -9
```

### Development Tips

#### 1. Enable Debug Mode
Ensure `APP_DEBUG=true` in your `.env` file for detailed error messages during development.

#### 2. Log Files
Check Laravel logs for detailed error information:
```bash
tail -f storage/logs/laravel.log
```

#### 3. Database Queries
To see all database queries being executed:
```bash
# Add to any controller method for debugging
DB::enableQueryLog();
// Your code here
dd(DB::getQueryLog());
```

#### 4. API Testing
Use these tools for testing your API:
- **Postman**: Create collections for all endpoints
- **cURL**: Command-line testing (examples in API documentation)
- **Laravel Tinker**: Interactive PHP shell for testing models
  ```bash
  php artisan tinker
  Customer::all();
  ```

### Performance Optimization

#### 1. Configuration Caching
For production environments:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. Database Indexing
The migrations already include necessary indexes, but for large datasets consider:
- Adding indexes on frequently queried columns
- Using database query optimization tools

#### 3. API Response Caching
Consider implementing caching for frequently accessed data:
```php
// Example: Cache customer list for 10 minutes
$customers = Cache::remember('customers', 600, function () {
    return Customer::all();
});
```

## Production Deployment

### Environment Configuration
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure proper database credentials
4. Set up proper web server (Apache/Nginx)
5. Configure SSL certificates for HTTPS

### Security Considerations
1. Use strong database passwords
2. Implement API authentication (Laravel Sanctum/Passport)
3. Set up rate limiting
4. Configure proper CORS policies
5. Use HTTPS in production
6. Regular security updates

### Monitoring
1. Set up error logging and monitoring
2. Monitor API performance and response times
3. Set up database monitoring
4. Configure backup strategies

## Next Steps

1. **Test the API**: Use the provided API documentation to test all endpoints
2. **Integrate with Flutter**: Use the Flutter integration examples in the API documentation
3. **Customize**: Modify the code to fit your specific requirements
4. **Add Features**: Consider adding authentication, notifications, or reporting features
5. **Deploy**: When ready, deploy to your production environment

## Support

For detailed setup instructions, see [SETUP_GUIDE.md](SETUP_GUIDE.md)
For API usage examples, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## License

This project is open-source software licensed under the MIT license.

