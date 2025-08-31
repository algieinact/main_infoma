# 🔌 INFOMA API Documentation

## 📋 Overview
Dokumentasi lengkap API untuk mobile app Flutter INFOMA. Semua endpoint menggunakan Laravel Sanctum authentication.

## 🔐 Base URL
```
https://your-domain.com/api
```

## 📊 Response Format
Semua response menggunakan format standar:
```json
{
  "success": true/false,
  "data": {...},
  "message": "Success/Error message"
}
```

---

## 🔐 Authentication

### Login
```http
POST /api/auth/login
```

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "role": "user",
      "phone": "08123456789",
      "avatar": "avatars/user1.jpg"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  },
  "message": "Login berhasil"
}
```

### Register
```http
POST /api/auth/register
```

**Request:**
```json
{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "user",
  "phone": "08123456789",
  "university": "Telkom University",
  "major": "Informatika"
}
```

### Logout
```http
POST /api/auth/logout
```
**Headers:** `Authorization: Bearer {token}`

### Get User Profile
```http
GET /api/auth/me
```
**Headers:** `Authorization: Bearer {token}`

---

## 🏠 Residences

### List Residences
```http
GET /api/residences
```

**Query Parameters:**
- `search` - Search by title/city/address
- `category` - Filter by category ID
- `type` - Filter by type (apartment, kost, villa, rumah)
- `gender_type` - Filter by gender (male, female, mixed)
- `city` - Filter by city
- `min_price` - Minimum price
- `max_price` - Maximum price
- `sort` - Sort by (newest, price_low, price_high, rating, featured)
- `per_page` - Items per page (default: 12)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Kost Putri Premium",
        "description": "Kost nyaman untuk mahasiswi",
        "type": "kost",
        "price": 1500000,
        "price_period": "monthly",
        "address": "Jl. Sudirman No. 123",
        "city": "Jakarta",
        "facilities": ["AC", "WiFi", "Kamar Mandi Dalam"],
        "rules": ["Tidak boleh merokok", "Jam malam 22:00"],
        "total_rooms": 20,
        "available_rooms": 5,
        "gender_type": "female",
        "images": ["residences/room1.jpg"],
        "rating": 4.5,
        "total_reviews": 12,
        "provider": {...},
        "category": {...}
      }
    ],
    "total": 50
  }
}
```

### Get Residence Detail
```http
GET /api/residences/{id}
```

**Response includes:**
- Basic residence info
- Provider details
- Category info
- Reviews with user data
- Similar residences
- Average rating

### Create Residence (Provider Only)
```http
POST /api/residences
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "title": "Kost Baru",
  "description": "Deskripsi kost",
  "type": "kost",
  "price": 1500000,
  "price_period": "monthly",
  "address": "Jl. Contoh No. 123",
  "city": "Jakarta",
  "province": "DKI Jakarta",
  "facilities": ["AC", "WiFi"],
  "rules": ["Tidak boleh merokok"],
  "total_rooms": 10,
  "available_rooms": 10,
  "gender_type": "female",
  "category_id": 1,
  "images": [File1, File2]
}
```

---

## 🎯 Activities

### List Activities
```http
GET /api/activities
```

**Query Parameters:**
- `type` - Filter by type
- `format` - Filter by format (online, offline, hybrid)
- `city` - Filter by city
- `free` - Filter free activities
- `upcoming` - Filter upcoming activities
- `featured` - Filter featured activities
- `sort_by` - Sort field
- `sort_direction` - Sort direction (asc/desc)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Workshop Flutter",
        "description": "Belajar Flutter dari dasar",
        "type": "workshop",
        "price": 500000,
        "is_free": false,
        "location": "Online via Zoom",
        "city": "Jakarta",
        "format": "online",
        "meeting_link": "https://zoom.us/j/123456",
        "start_date": "2024-02-15T09:00:00Z",
        "end_date": "2024-02-15T17:00:00Z",
        "registration_deadline": "2024-02-14T23:59:59Z",
        "requirements": ["Basic programming", "Laptop"],
        "benefits": ["Sertifikat", "Source code"],
        "max_participants": 30,
        "current_participants": 15,
        "images": ["activities/workshop1.jpg"],
        "rating": 4.8,
        "total_reviews": 8,
        "provider": {...},
        "category": {...}
      }
    ]
  }
}
```

---

## 📅 Bookings

### List User Bookings
```http
GET /api/bookings
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` - Filter by status
- `type` - Filter by type (residence/activity)
- `per_page` - Items per page

### Create Booking
```http
POST /api/bookings
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "bookable_type": "residence",
  "bookable_id": 1,
  "start_date": "2024-02-01",
  "end_date": "2024-03-01",
  "booking_data": {
    "full_name": "John Doe",
    "phone": "08123456789",
    "emergency_contact": "Jane Doe",
    "emergency_phone": "08123456788",
    "occupation": "Mahasiswa"
  },
  "files": {
    "ktp": File,
    "agreement": File
  },
  "notes": "Catatan tambahan"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "booking_code": "INF-ABC12345",
    "user_id": 1,
    "bookable_type": "App\\Models\\Residence",
    "bookable_id": 1,
    "booking_data": {...},
    "files": {...},
    "status": "waiting_provider_approval",
    "booking_date": "2024-01-15T10:00:00Z",
    "start_date": "2024-02-01",
    "end_date": "2024-03-01",
    "total_amount": 1500000,
    "discount_amount": 0,
    "final_amount": 1500000,
    "notes": "Catatan tambahan"
  }
}
```

### Cancel Booking
```http
POST /api/bookings/{id}/cancel
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "cancellation_reason": "Jadwal berubah"
}
```

---

## 💰 Payments

### Get Payment Methods
```http
GET /api/payments/methods
```

**Response:**
```json
{
  "success": true,
  "data": {
    "bank_transfer": {
      "id": "bank_transfer",
      "name": "Bank Transfer",
      "description": "Transfer bank ke rekening yang tersedia",
      "processing_time": "1-3 hari kerja",
      "fees": 0
    },
    "e_wallet": {
      "id": "e_wallet",
      "name": "E-Wallet",
      "description": "Pembayaran melalui e-wallet",
      "processing_time": "Instan",
      "fees": 0,
      "options": ["gopay", "ovo", "dana", "linkaja"]
    }
  }
}
```

### Get Bank Accounts
```http
GET /api/payments/bank-accounts
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "bank": "BCA",
      "account_number": "1234567890",
      "account_name": "PT INFOMA INDONESIA",
      "branch": "Jakarta Pusat"
    }
  ]
}
```

### Process Payment
```http
POST /api/payments/process
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "booking_id": 1,
  "payment_method": "bank_transfer",
  "amount": 1500000,
  "payment_proof": File,
  "bank_name": "BCA",
  "account_number": "1234567890",
  "notes": "Catatan pembayaran"
}
```

---

## 🏷️ Vouchers

### Validate Voucher
```http
POST /api/user/vouchers/validate
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "code": "DISKON50",
  "bookable_type": "residence",
  "bookable_id": 1,
  "amount": 1500000
}
```

**Response:**
```json
{
  "success": true,
  "message": "Voucher berhasil digunakan!",
  "data": {
    "voucher_id": 1,
    "code": "DISKON50",
    "discount_type": "percentage",
    "discount_value": 50,
    "discount_amount": 750000,
    "original_amount": 1500000,
    "final_amount": 750000,
    "description": "Diskon 50% untuk kost premium"
  }
}
```

---

## 🔖 Bookmarks

### List Bookmarks
```http
GET /api/bookmarks
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `type` - Filter by type (residence/activity)
- `per_page` - Items per page

### Add Bookmark
```http
POST /api/bookmarks
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "bookmarkable_type": "residence",
  "bookmarkable_id": 1
}
```

### Remove Bookmark
```http
DELETE /api/bookmarks/{id}
```
**Headers:** `Authorization: Bearer {token}`

---

## 🔍 Search & Filters

### Global Search
```http
GET /api/search/global
```

**Query Parameters:**
- `q` - Search query
- `type` - Search type (all, residence, activity)
- `limit` - Result limit (default: 20)

**Response:**
```json
{
  "success": true,
  "data": {
    "residences": [...],
    "activities": [...],
    "total_results": 25
  }
}
```

### Advanced Search
```http
GET /api/search/advanced
```

**Query Parameters:**
- `type` - Search type (residence/activity)
- `search` - Search query
- `category_id` - Category filter
- `city` - City filter
- `min_price` - Minimum price
- `max_price` - Maximum price
- `sort` - Sort option
- `per_page` - Items per page

### Get Filter Options
```http
GET /api/search/filter-options
```

**Query Parameters:**
- `type` - Filter type (residence/activity/all)

**Response:**
```json
{
  "success": true,
  "data": {
    "residence_categories": [...],
    "residence_types": ["apartment", "kost", "villa"],
    "residence_cities": ["Jakarta", "Bandung", "Surabaya"],
    "residence_gender_types": ["male", "female", "mixed"],
    "activity_categories": [...],
    "activity_types": ["workshop", "seminar", "training"],
    "activity_formats": ["online", "offline", "hybrid"],
    "activity_cities": [...]
  }
}
```

---

## 📊 Dashboard

### User Dashboard
```http
GET /api/dashboard/user
```
**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": {
    "booking_counts": {
      "total": 5,
      "pending": 1,
      "confirmed": 2,
      "completed": 1,
      "cancelled": 1
    },
    "recent_bookings": [...],
    "recent_activities": [...],
    "total_spent": 2500000,
    "favorite_count": 8
  }
}
```

### Provider Dashboard
```http
GET /api/dashboard/provider
```
**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": {
    "residence_stats": {
      "total": 5,
      "active": 4,
      "featured": 2,
      "total_rooms": 50,
      "available_rooms": 15
    },
    "activity_stats": {
      "total": 3,
      "active": 2,
      "featured": 1,
      "total_participants": 45,
      "max_participants": 60
    },
    "booking_stats": {
      "total": 25,
      "pending": 5,
      "confirmed": 15,
      "completed": 3,
      "cancelled": 2
    },
    "revenue_stats": {
      "total": 15000000,
      "this_month": 2500000,
      "this_year": 15000000
    },
    "recent_bookings": [...],
    "top_residences": [...],
    "top_activities": [...]
  }
}
```

### Admin Dashboard
```http
GET /api/dashboard/admin
```
**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": {
    "system_stats": {
      "total_users": 150,
      "total_providers": 25,
      "total_residences": 100,
      "total_activities": 50,
      "total_bookings": 300,
      "total_transactions": 250
    },
    "recent_users": [...],
    "recent_bookings": [...],
    "revenue_overview": {
      "total": 50000000,
      "this_month": 8000000,
      "this_year": 50000000
    },
    "top_categories": [...]
  }
}
```

---

## 📁 File Management

### Upload Image
```http
POST /api/files/upload-image
```
**Headers:** `Authorization: Bearer {token}`

**Request:** `multipart/form-data`
- `image` - Image file
- `folder` - Target folder (avatars, residences, activities, bookings, payments)

### Upload Multiple Images
```http
POST /api/files/upload-multiple-images
```
**Headers:** `Authorization: Bearer {token}`

**Request:** `multipart/form-data`
- `images[]` - Multiple image files
- `folder` - Target folder

### Upload Document
```http
POST /api/files/upload-document
```
**Headers:** `Authorization: Bearer {token}`

**Request:** `multipart/form-data`
- `document` - Document file (PDF, DOC, TXT)
- `folder` - Target folder (documents, bookings, payments)

---

## 🔔 Notifications

### List Notifications
```http
GET /api/notifications
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `type` - Filter by type
- `is_read` - Filter by read status
- `per_page` - Items per page

### Mark as Read
```http
POST /api/notifications/{id}/mark-read
```
**Headers:** `Authorization: Bearer {token}`

### Mark All as Read
```http
POST /api/notifications/mark-all-read
```
**Headers:** `Authorization: Bearer {token}`

### Delete Notification
```http
DELETE /api/notifications/{id}
```
**Headers:** `Authorization: Bearer {token}`

---

## 🏷️ Categories

### List Categories
```http
GET /api/categories
```

**Query Parameters:**
- `type` - Filter by type (residence/activity)
- `is_active` - Filter by active status
- `sort_by` - Sort field
- `sort_direction` - Sort direction

### Get Category Detail
```http
GET /api/categories/{id}
```

### Get Categories by Type
```http
GET /api/categories/type/{type}
```

### Get Categories with Counts
```http
GET /api/categories/with-counts
```

---

## 🏙️ Cities

### List Cities
```http
GET /api/cities
```

**Query Parameters:**
- `type` - Filter by type (all, residence, activity)

### Get Cities by Type
```http
GET /api/cities/type/{type}
```

### Get Cities with Counts
```http
GET /api/cities/with-counts
```

### Search Cities
```http
GET /api/cities/search
```

**Query Parameters:**
- `q` - Search query
- `type` - Filter by type
- `limit` - Result limit

---

## 👥 User Management (Admin Only)

### List Users
```http
GET /api/admin/users
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `role` - Filter by role
- `status` - Filter by status (active/inactive)
- `search` - Search by name/email/phone
- `sort_by` - Sort field
- `sort_direction` - Sort direction

### Update User Status
```http
POST /api/admin/users/{id}/status
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "is_active": true,
  "reason": "User verified"
}
```

---

## 📊 Content Management (Admin Only)

### List Content
```http
GET /api/admin/content
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `type` - Content type (residence/activity)
- `status` - Filter by status
- `featured` - Filter by featured status
- `search` - Search query
- `sort_by` - Sort field
- `sort_direction` - Sort direction

### Update Content Status
```http
POST /api/admin/content/{id}/status
```
**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "type": "residence",
  "is_active": true,
  "is_featured": false,
  "reason": "Content approved"
}
```

---

## 💰 Financial Reports (Admin Only)

### Get Financial Reports
```http
GET /api/admin/financial-reports
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `period` - Report period (day, week, month, year)
- `start_date` - Start date
- `end_date` - End date

**Response:**
```json
{
  "success": true,
  "data": {
    "period": {
      "start_date": "2024-01-01",
      "end_date": "2024-01-31",
      "type": "month"
    },
    "summary": {
      "total_revenue": 15000000,
      "total_transactions": 45,
      "average_transaction": 333333.33
    },
    "payment_method_stats": {
      "bank_transfer": {
        "count": 30,
        "total": 10000000,
        "average": 333333.33
      },
      "e_wallet": {
        "count": 15,
        "total": 5000000,
        "average": 333333.33
      }
    },
    "daily_stats": {...},
    "transactions": [...]
  }
}
```

---

## 📋 Booking Management (Admin Only)

### List Bookings
```http
GET /api/admin/bookings
```
**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` - Filter by status
- `type` - Filter by type (residence/activity)
- `search` - Search query
- `sort_by` - Sort field
- `sort_direction` - Sort direction

---

## 🚨 Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation Error",
  "data": {
    "email": ["Email sudah terdaftar"],
    "password": ["Password minimal 6 karakter"]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Forbidden"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## 🔧 Pagination

Semua endpoint yang mendukung pagination menggunakan format Laravel standard:

```json
{
  "current_page": 1,
  "data": [...],
  "first_page_url": "https://api.example.com/endpoint?page=1",
  "from": 1,
  "last_page": 5,
  "last_page_url": "https://api.example.com/endpoint?page=5",
  "next_page_url": "https://api.example.com/endpoint?page=2",
  "path": "https://api.example.com/endpoint",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 75
}
```

---

## 📱 Mobile App Integration Notes

### Authentication Flow
1. **Login** → Get token
2. **Store token** securely (flutter_secure_storage)
3. **Add token** to all subsequent requests
4. **Handle 401** responses → Redirect to login

### File Upload
- Use `multipart/form-data` for file uploads
- Maximum file size: 2MB for images, 5MB for documents
- Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX, TXT

### Error Handling
- Always check `success` field in response
- Handle network errors gracefully
- Show user-friendly error messages
- Implement retry mechanism for failed requests

### Offline Support
- Cache frequently accessed data
- Queue actions for later sync
- Handle offline state gracefully

---

## 📞 Support

Untuk pertanyaan teknis atau bantuan API:
- **Email**: tech@infoma.com
- **Documentation**: https://docs.infoma.com/api
- **Postman Collection**: Available in project repository

---

**Last Updated:** January 2024  
**Version:** 1.0.0  
**Maintainer:** INFOMA Development Team
