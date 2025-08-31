# 🔌 INFOMA API Reference

## Base URL
```
https://your-domain.com/api
```

## Authentication
All protected endpoints require `Authorization: Bearer {token}` header.

---

## 🔐 Auth Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/login` | User login |
| POST | `/auth/register` | User registration |
| POST | `/auth/logout` | User logout |
| GET | `/auth/me` | Get user profile |

---

## 🏠 Residence Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/residences` | List residences with filters |
| GET | `/residences/{id}` | Get residence detail |
| POST | `/residences` | Create residence (Provider) |
| PUT | `/residences/{id}` | Update residence (Provider) |
| DELETE | `/residences/{id}` | Delete residence (Provider) |

**Query Parameters:**
- `search` - Search by title/city/address
- `category` - Filter by category ID
- `type` - Filter by type (apartment, kost, villa, rumah)
- `gender_type` - Filter by gender (male, female, mixed)
- `city` - Filter by city
- `min_price` - Minimum price
- `max_price` - Maximum price
- `sort` - Sort by (newest, price_low, price_high, rating, featured)

---

## 🎯 Activity Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/activities` | List activities with filters |
| GET | `/activities/{id}` | Get activity detail |
| POST | `/activities` | Create activity (Provider) |
| PUT | `/activities/{id}` | Update activity (Provider) |
| DELETE | `/activities/{id}` | Delete activity (Provider) |

**Query Parameters:**
- `type` - Filter by type
- `format` - Filter by format (online, offline, hybrid)
- `city` - Filter by city
- `free` - Filter free activities
- `upcoming` - Filter upcoming activities
- `featured` - Filter featured activities

---

## 📅 Booking Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/bookings` | List user bookings |
| GET | `/bookings/{id}` | Get booking detail |
| POST | `/bookings` | Create new booking |
| PUT | `/bookings/{id}` | Update booking |
| DELETE | `/bookings/{id}` | Cancel booking |
| POST | `/bookings/{id}/cancel` | Cancel with reason |
| POST | `/bookings/{id}/confirm` | Confirm booking (Provider) |
| POST | `/bookings/{id}/reject` | Reject booking (Provider) |

---

## 💰 Payment Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payments/methods` | Get payment methods |
| GET | `/payments/bank-accounts` | Get bank accounts |
| POST | `/payments/process` | Process payment |
| POST | `/payments/upload-proof` | Upload payment proof |
| GET | `/payments/{id}/status` | Get payment status |
| POST | `/payments/{id}/cancel` | Cancel payment |
| GET | `/payments/history` | Payment history |

---

## 🏷️ Voucher Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/user/vouchers/validate` | Validate voucher code |

---

## 🔖 Bookmark Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/bookmarks` | List user bookmarks |
| POST | `/bookmarks` | Add bookmark |
| DELETE | `/bookmarks/{id}` | Remove bookmark |

---

## 🔍 Search Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/search/global` | Global search |
| GET | `/search/advanced` | Advanced search with filters |
| GET | `/search/suggestions` | Search suggestions |
| GET | `/search/filter-options` | Available filter options |

---

## 📊 Dashboard Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/dashboard/user` | User dashboard data |
| GET | `/dashboard/provider` | Provider dashboard data |
| GET | `/dashboard/admin` | Admin dashboard data |

---

## 📁 File Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/files/upload-image` | Upload single image |
| POST | `/files/upload-multiple-images` | Upload multiple images |
| POST | `/files/upload-document` | Upload document |
| DELETE | `/files/delete` | Delete file |

---

## 🔔 Notification Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/notifications` | List notifications |
| POST | `/notifications/{id}/mark-read` | Mark as read |
| POST | `/notifications/mark-all-read` | Mark all as read |
| DELETE | `/notifications/{id}` | Delete notification |

---

## 🏷️ Category Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/categories` | List categories |
| GET | `/categories/{id}` | Get category detail |
| GET | `/categories/type/{type}` | Get by type |
| GET | `/categories/with-counts` | Get with counts |

---

## 🏙️ City Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cities` | List cities |
| GET | `/cities/type/{type}` | Get by type |
| GET | `/cities/with-counts` | Get with counts |
| GET | `/cities/search` | Search cities |

---

## 👥 Admin Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/users` | List users |
| POST | `/admin/users/{id}/status` | Update user status |
| GET | `/admin/content` | List content |
| POST | `/admin/content/{id}/status` | Update content status |
| GET | `/admin/bookings` | List all bookings |
| GET | `/admin/financial-reports` | Financial reports |

---

## 📋 Response Format

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Success message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": {...} // Optional validation errors
}
```

---

## 🚨 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## 📱 Mobile App Notes

- **Token Storage**: Use `flutter_secure_storage`
- **File Upload**: `multipart/form-data`, max 2MB images, 5MB docs
- **Error Handling**: Check `success` field, handle 401 gracefully
- **Offline Support**: Cache data, queue actions, handle offline state

---

**Version:** 1.0.0  
**Last Updated:** January 2024
