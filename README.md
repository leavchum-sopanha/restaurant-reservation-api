# Restaurant Reservation System API

A comprehensive Laravel-based REST API for managing restaurant reservations, customers, and tables. Built with modern PHP practices and designed for integration with Flutter mobile applications.

## Features

✅ **Complete CRUD Operations**
- Customers management (List, Add, Edit, Delete)
- Tables management (List, Add, Edit, Delete)
- Reservations management (List, Add, Edit, Delete with full relationships)

✅ **Advanced Functionality**
- Double-booking prevention
- Table availability checking
- Relationship management between customers, tables, and reservations
- Upcoming reservations filtering
- Comprehensive validation and error handling

✅ **API Features**
- RESTful API design
- JSON responses with consistent format
- CORS support for frontend integration
- Comprehensive error handling and validation
- Database relationships and eager loading

## Quick Start

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup Database**
   ```bash
   # Configure database in .env file
   php artisan migrate
   php artisan db:seed  # Optional: Add sample data
   ```

4. **Start Server**
   ```bash
   php artisan serve
   ```

5. **Test API**
   ```bash
   curl http://localhost:8000/api/health
   ```

## API Endpoints

### Customers
- `GET /api/customers` - List all customers
- `POST /api/customers` - Create customer
- `GET /api/customers/{id}` - Get customer details
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

### Tables
- `GET /api/tables` - List all tables
- `POST /api/tables` - Create table
- `GET /api/tables/{id}` - Get table details
- `PUT /api/tables/{id}` - Update table
- `DELETE /api/tables/{id}` - Delete table
- `POST /api/tables/{id}/check-availability` - Check availability

### Reservations
- `GET /api/reservations` - List reservations (with filters)
- `POST /api/reservations` - Create reservation
- `GET /api/reservations/{id}` - Get reservation details
- `PUT /api/reservations/{id}` - Update reservation
- `DELETE /api/reservations/{id}` - Delete reservation

## Database Schema

### Tables Structure
```sql
customers (id, name, phone, email, timestamps)
tables (id, number, capacity, timestamps)
reservations (id, customer_id, table_id, date_time, note, timestamps)
```

### Relationships
- Reservation belongs to Customer and Table
- Customer has many Reservations
- Table has many Reservations

## Documentation

- **[Setup Guide](SETUP_GUIDE.md)** - Complete installation and configuration instructions
- **[API Documentation](API_DOCUMENTATION.md)** - Detailed API reference with examples
- **Flutter Integration** - Examples included in API documentation

## Technology Stack

- **Framework**: Laravel 10.x
- **PHP**: 8.1+
- **Database**: MySQL/PostgreSQL/SQLite
- **Features**: Eloquent ORM, Validation, CORS, RESTful API

## Project Structure

```
app/
├── Http/Controllers/     # API Controllers
├── Models/              # Eloquent Models
├── Services/            # Business Logic
└── Http/Requests/       # Form Validation

database/
├── migrations/          # Database Schema
└── seeders/            # Sample Data

routes/
└── api.php             # API Routes
```

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 8.0+ / PostgreSQL / SQLite
- Laravel 10.x

## License

MIT License - feel free to use this project for your restaurant reservation needs.

## Support

For detailed setup instructions, see [SETUP_GUIDE.md](SETUP_GUIDE.md)
For API usage examples, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)


