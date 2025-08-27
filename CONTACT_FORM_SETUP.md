# Contact Form Setup Guide

This guide explains how to set up the working contact form with Gmail verification for the AutoWash Hub landing page.

## Features Implemented

✅ **Working Contact Form** - Customers can submit messages through the landing page
✅ **Gmail Verification** - Only Gmail addresses are accepted
✅ **Form Validation** - All fields are required and validated
✅ **Success/Error Messages** - Clear feedback to users
✅ **Backend API** - Contact form submissions are stored in database
✅ **Security** - Input sanitization and validation

## Setup Instructions

### 1. Database Setup

Run the SQL migration to create the contact_messages table:

```sql
-- Execute the SQL in: backend/autowash-hub-api/contact_messages_table.sql
```

### 2. Backend Configuration

The backend API endpoint is already configured:

- **Endpoint**: `POST /api/submit_contact`
- **File**: `backend/autowash-hub-api/api/modules/post.php`
- **Route**: `backend/autowash-hub-api/api/routes.php`

### 3. Frontend Configuration

The frontend is already configured with:

- **Service**: `frontend/src/app/services/contact.service.ts`
- **Component**: `frontend/src/app/components/landing-page/landing-page.component.ts`
- **Template**: `frontend/src/app/components/landing-page/landing-page.component.html`
- **Styles**: `frontend/src/app/components/landing-page/landing-page.component.css`

## How It Works

### 1. Form Submission Flow

1. User fills out contact form
2. System validates all fields are filled
3. System checks if email is Gmail format (@gmail.com)
4. If valid Gmail, shows verification modal
5. User enters 6-digit verification code
6. Form is submitted to backend API
7. Success/error message displayed

### 2. Gmail Verification

- Only accepts emails ending with `@gmail.com`
- Shows verification modal for valid Gmail addresses
- For demo purposes, accepts any 6-digit code
- In production, integrate with real email verification service

### 3. Backend Processing

- Validates all required fields
- Ensures email is Gmail format
- Sanitizes inputs for security
- Stores message in database
- Returns success/error response

## API Endpoint Details

### Submit Contact Form

```
POST /api/submit_contact
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john.doe@gmail.com",
  "subject": "General Inquiry",
  "message": "I would like to know more about your services."
}
```

### Response Format

```json
{
  "status": {
    "remarks": "success",
    "message": "Contact message submitted successfully"
  },
  "payload": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@gmail.com",
    "subject": "General Inquiry",
    "message": "I would like to know more about your services.",
    "created_at": "2025-01-27 10:30:00"
  }
}
```

## Customization Options

### 1. Email Verification

To implement real email verification:

- Replace mock verification in `ContactService.sendVerificationEmail()`
- Integrate with services like SendGrid, Mailgun, or AWS SES
- Send actual verification codes via email

### 2. Form Fields

To add/remove fields:

- Update `ContactForm` interface in contact service
- Modify HTML template
- Update backend validation and database schema

### 3. Styling

Contact form styles are in:

- `landing-page.component.css` (lines 500+)
- Includes success/error message styles
- Verification modal styling

## Testing

### 1. Test Valid Gmail

- Use any email ending with `@gmail.com`
- Enter any 6-digit code in verification modal
- Should submit successfully

### 2. Test Invalid Email

- Use non-Gmail addresses
- Should show error: "Only Gmail addresses are allowed"

### 3. Test Empty Fields

- Leave any field empty
- Should show appropriate validation error

## Troubleshooting

### Common Issues

1. **Form not submitting**

   - Check browser console for errors
   - Verify backend API is running
   - Check database connection

2. **Verification modal not showing**

   - Ensure email ends with `@gmail.com`
   - Check browser console for JavaScript errors

3. **Backend errors**
   - Check PHP error logs
   - Verify database table exists
   - Check API endpoint configuration

### Debug Mode

Enable debug logging in the contact service by checking browser console for:

- 📤 Contact form data
- 📥 Backend response
- ✅ Success messages
- ❌ Error messages

## Security Considerations

- Input sanitization implemented
- Gmail domain validation
- SQL injection protection via prepared statements
- XSS protection via htmlspecialchars()

## Future Enhancements

- Real email verification service integration
- CAPTCHA protection
- Rate limiting
- Admin dashboard for viewing messages
- Email notifications to staff
- Message status tracking
