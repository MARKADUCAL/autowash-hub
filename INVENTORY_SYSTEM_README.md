# Inventory Management System

This document describes the inventory management system for AutoWash Hub, which allows employees to view available stock and request items, while admins can manage inventory and approve/reject requests.

## Features

### Employee Features

- **View Inventory**: Employees can see all available inventory items with stock levels, prices, and categories
- **Stock Status**: Visual indicators for stock levels (In Stock, Low Stock, Out of Stock)
- **Request Items**: Employees can request items from the inventory with quantity and notes
- **Request History**: View the status of their submitted requests

### Admin Features

- **Manage Inventory**: Add, edit, and delete inventory items
- **View Requests**: See all employee inventory requests
- **Approve/Reject Requests**: Manage employee requests with approval or rejection
- **Stock Management**: Update stock levels and item details

## API Endpoints

### Inventory Management

- `GET /get_inventory` - Get all inventory items
- `POST /add_inventory_item` - Add new inventory item (Admin only)
- `PUT /update_inventory_item` - Update inventory item (Admin only)
- `DELETE /inventory/{id}` - Delete inventory item (Admin only)

### Inventory Requests

- `GET /get_inventory_requests` - Get all inventory requests
- `POST /add_inventory_request` - Submit new inventory request (Employee)
- `PUT /update_inventory_request` - Update request status (Admin)

## Database Schema

### Inventory Table

```sql
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image_url VARCHAR(1024) NULL,
    stock INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    category VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);
```

### Inventory Requests Table

```sql
CREATE TABLE inventory_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    employee_id VARCHAR(50) NOT NULL,
    employee_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    request_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);
```

## Components

### Employee Components

- `InventoryComponent` - Main inventory view for employees
- `RequestInventoryModalComponent` - Modal for requesting items

### Admin Components

- `InventoryManagementComponent` - Main inventory management for admins
- `InventoryRequestsComponent` - Manage employee requests

### Services

- `InventoryService` - Centralized service for all inventory operations

## Usage

### For Employees

1. Navigate to the Inventory section in the employee dashboard
2. View available items with stock levels and prices
3. Click "Request Item" on any available item
4. Enter quantity and optional notes
5. Submit the request
6. Check request status in the system

### For Admins

1. Navigate to Inventory Management in the admin dashboard
2. Add, edit, or delete inventory items
3. Navigate to Inventory Requests to view employee requests
4. Approve or reject pending requests
5. Monitor stock levels and manage inventory

## Stock Status Indicators

- **In Stock** (Green): Stock > 5 items
- **Low Stock** (Yellow): Stock ≤ 5 items
- **Out of Stock** (Red): Stock = 0 items

## Authentication

The system uses the existing authentication system:

- Employee requests are associated with the logged-in employee
- Admin functions require admin privileges
- All API endpoints are protected with appropriate access controls

## Error Handling

The system includes comprehensive error handling:

- Loading states for all API calls
- Error messages for failed operations
- Retry functionality for failed requests
- Form validation for all inputs

## Future Enhancements

- Email notifications for request status changes
- Bulk inventory operations
- Inventory reports and analytics
- Barcode scanning integration
- Automatic stock alerts
- Inventory history tracking
