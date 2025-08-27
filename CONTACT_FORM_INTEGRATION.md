# Contact Form Integration with Manage-Enquiries

This document explains how the contact form system integrates with the admin manage-enquiries component to provide a complete customer inquiry management solution.

## 🎯 Overview

The system now provides a complete workflow:

1. **Customers submit messages** through the landing page contact form
2. **Gmail verification** ensures only valid Gmail addresses are accepted
3. **Admin staff can view and manage** all contact messages in the manage-enquiries component
4. **Status tracking** allows staff to track inquiry progress

## 🔗 System Architecture

### Frontend Components

- **Landing Page Contact Form** (`landing-page.component.ts`)
- **Manage Enquiries Component** (`manage-enquiries.component.ts`)
- **Contact Service** (`contact.service.ts`)

### Backend API Endpoints

- `POST /api/submit_contact` - Submit new contact form
- `GET /api/get_contact_enquiries` - Get all contact enquiries
- `POST /api/update_contact_status` - Update enquiry status
- `DELETE /api/delete_contact_enquiry/{id}` - Delete enquiry

### Database

- `contact_messages` table stores all submissions

## 📝 Contact Form Flow

### 1. Customer Submission

```
Customer fills form → Gmail validation → Verification modal → Backend storage
```

### 2. Data Storage

- Message stored in `contact_messages` table
- Status automatically set to `'new'`
- Timestamp recorded

### 3. Admin Management

- Staff can view all messages in manage-enquiries
- Update status: `new` → `read` → `replied` → `archived`
- Delete unwanted messages
- Filter by status

## 🎨 Manage-Enquiries Features

### Status Management

- **New** (Orange) - Unread messages requiring attention
- **Read** (Blue) - Messages that have been viewed
- **Replied** (Green) - Messages that have been responded to
- **Archived** (Grey) - Old messages kept for reference

### Filtering & Organization

- Status-based filtering
- Chronological ordering (newest first)
- Visual status indicators
- Responsive card layout

### Actions Available

- **View** - Mark as read and view details
- **Reply** - Update status to replied
- **Delete** - Remove unwanted messages
- **Status Update** - Change status manually

## 🛠️ Technical Implementation

### Frontend Service Methods

```typescript
// Submit contact form
submitContactForm(contactData: ContactForm): Observable<ContactResponse>

// Get all enquiries for admin
getAllEnquiries(): Observable<ContactEnquiry[]>

// Update enquiry status
updateEnquiryStatus(enquiryId: number, status: string): Observable<ContactResponse>

// Delete enquiry
deleteEnquiry(enquiryId: number): Observable<ContactResponse>
```

### Backend Methods

```php
// Post class
submit_contact($data)
update_contact_status($data)
delete_contact_enquiry($id)

// Get class
get_contact_enquiries()
```

### Database Schema

```sql
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(500) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('new','read','replied','archived') DEFAULT 'new'
);
```

## 🚀 Usage Instructions

### For Customers

1. Navigate to landing page contact section
2. Fill out the form with Gmail address
3. Complete verification process
4. Submit message

### For Admin Staff

1. Access manage-enquiries component
2. View new messages (orange highlight)
3. Update status as you process enquiries
4. Filter by status to organize workflow

## 🔒 Security Features

- **Gmail-only validation** - Prevents spam from non-Gmail addresses
- **Input sanitization** - Protects against XSS attacks
- **SQL injection protection** - Uses prepared statements
- **Status validation** - Only allows valid status values

## 📱 Responsive Design

- **Desktop** - Full grid layout with all features
- **Tablet** - Adjusted spacing and layout
- **Mobile** - Stacked cards with touch-friendly buttons

## 🧪 Testing

### Test Contact Form

1. Use valid Gmail address (e.g., `test@gmail.com`)
2. Enter any 6-digit verification code
3. Submit form and check database

### Test Admin Interface

1. Navigate to manage-enquiries
2. Verify messages appear with correct status
3. Test status updates and filtering
4. Test delete functionality

## 🔧 Customization

### Add New Status

1. Update enum in database
2. Add to `validStatuses` array in backend
3. Update frontend status management
4. Add corresponding CSS styles

### Add New Fields

1. Update database schema
2. Modify frontend interfaces
3. Update backend validation
4. Adjust admin display

## 🚨 Troubleshooting

### Common Issues

1. **Form not submitting** - Check backend API and database
2. **Messages not appearing** - Verify GET endpoint and database connection
3. **Status not updating** - Check PUT endpoint and validation
4. **Delete not working** - Verify DELETE endpoint and permissions

### Debug Steps

1. Check browser console for errors
2. Verify backend API endpoints
3. Check database table exists
4. Test individual API calls

## 📈 Future Enhancements

- **Email notifications** - Alert staff of new enquiries
- **Auto-reply system** - Send confirmation emails
- **Priority levels** - Mark urgent enquiries
- **Assignment system** - Assign enquiries to specific staff
- **Response templates** - Pre-written reply options
- **Analytics dashboard** - Track enquiry patterns

## 📋 API Documentation

### Submit Contact Form

```http
POST /api/submit_contact
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@gmail.com",
  "subject": "Service Inquiry",
  "message": "I have a question about..."
}
```

### Get All Enquiries

```http
GET /api/get_contact_enquiries
```

### Update Status

```http
POST /api/update_contact_status
Content-Type: application/json

{
  "id": 1,
  "status": "read"
}
```

### Delete Enquiry

```http
DELETE /api/delete_contact_enquiry/1
```

## 🎉 Summary

The contact form system now provides a complete solution for:

- ✅ Customer message submission with Gmail verification
- ✅ Secure backend storage and management
- ✅ Admin interface for message management
- ✅ Status tracking and workflow management
- ✅ Responsive design for all devices
- ✅ Comprehensive security measures

This integration creates a professional customer service workflow that helps staff efficiently manage and respond to customer inquiries.
