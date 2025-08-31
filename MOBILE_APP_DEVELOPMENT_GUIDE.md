# 🚀 INFOMA Mobile App Development Guide

## 📱 Overview
Dokumen ini berisi panduan lengkap untuk pengembangan mobile app Flutter yang terintegrasi dengan sistem INFOMA (sistem booking residence dan activity untuk mahasiswa).

## 🎯 Project Goals
- **Mobile App**: Flutter app untuk iOS dan Android
- **Backend**: Laravel API yang sudah tersedia
- **Target Users**: Mahasiswa (user), Provider (penyedia layanan), Admin
- **Core Features**: Booking residence, booking activity, payment, voucher, bookmark

---

## 🏗️ System Architecture

### Backend (Laravel)
- **Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum (API tokens)
- **Database**: MySQL/PostgreSQL
- **File Storage**: Laravel Storage (public disk)

### Mobile App (Flutter)
- **Framework**: Flutter 3.16+
- **State Management**: Provider/Riverpod (pilih salah satu)
- **HTTP Client**: Dio atau http package
- **Local Storage**: SharedPreferences, Hive, atau SQLite
- **Image Handling**: cached_network_image, image_picker

---

## 🔐 Authentication System

### Login Flow
```dart
// 1. User input email & password
// 2. POST /api/auth/login
// 3. Receive token & user data
// 4. Store token securely (flutter_secure_storage)
// 5. Navigate to appropriate dashboard
```

### Token Management
- **Storage**: Gunakan `flutter_secure_storage` untuk token
- **Auto-refresh**: Implement token refresh mechanism
- **Logout**: Clear token dan navigate to login

### Role-based Navigation
```dart
switch (user.role) {
  case 'user':
    return UserDashboard();
  case 'provider':
    return ProviderDashboard();
  case 'admin':
    return AdminDashboard();
  default:
    return LoginScreen();
}
```

---

## 📱 Core Features & Screens

### 1. Authentication Screens
- **Login Screen** (`/lib/screens/auth/login_screen.dart`)
- **Register Screen** (`/lib/screens/auth/register_screen.dart`)
- **Forgot Password** (optional)

### 2. User Dashboard (`/lib/screens/user/`)
- **Home Screen**: Featured residences & activities
- **Search Screen**: Global search dengan filter
- **Bookings**: List booking user
- **Profile**: Edit profile, change password
- **Bookmarks**: Saved items

### 3. Provider Dashboard (`/lib/screens/provider/`)
- **Overview**: Statistics & recent bookings
- **Manage Residences**: CRUD residences
- **Manage Activities**: CRUD activities
- **Bookings**: Approve/reject bookings
- **Vouchers**: Create & manage vouchers

### 4. Admin Dashboard (`/lib/screens/admin/`)
- **System Overview**: Total users, content, revenue
- **User Management**: Activate/deactivate users
- **Content Management**: Moderate residences/activities
- **Financial Reports**: Revenue analytics

---

## 🔌 API Integration Guide

### Base API Configuration
```dart
// /lib/services/api_service.dart
class ApiService {
  static const String baseUrl = 'https://your-domain.com/api';
  static const String apiVersion = 'v1';
  
  static Dio getDio() {
    final dio = Dio(BaseOptions(
      baseUrl: '$baseUrl/$apiVersion',
      connectTimeout: Duration(seconds: 30),
      receiveTimeout: Duration(seconds: 30),
    ));
    
    // Add interceptors for auth token
    dio.interceptors.add(AuthInterceptor());
    return dio;
  }
}
```

### Authentication Interceptor
```dart
// /lib/interceptors/auth_interceptor.dart
class AuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    final token = AuthService.getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }
  
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.response?.statusCode == 401) {
      // Token expired, redirect to login
      AuthService.logout();
    }
    handler.next(err);
  }
}
```

---

## 📊 API Endpoints Documentation

### 🔐 Authentication
```http
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
GET  /api/auth/me
```

**Login Request:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Login Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "role": "user"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  },
  "message": "Login berhasil"
}
```

### 🏠 Residences
```http
GET    /api/residences              # List residences
GET    /api/residences/{id}        # Get residence detail
POST   /api/residences             # Create residence (provider only)
PUT    /api/residences/{id}        # Update residence (provider only)
DELETE /api/residences/{id}        # Delete residence (provider only)
```

**Residence Model:**
```json
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
  "images": ["residences/room1.jpg", "residences/room2.jpg"],
  "rating": 4.5,
  "total_reviews": 12,
  "is_active": true,
  "is_featured": false
}
```

### 🎯 Activities
```http
GET    /api/activities             # List activities
GET    /api/activities/{id}       # Get activity detail
POST   /api/activities            # Create activity (provider only)
PUT    /api/activities/{id}       # Update activity (provider only)
DELETE /api/activities/{id}       # Delete activity (provider only)
```

**Activity Model:**
```json
{
  "id": 1,
  "title": "Workshop Flutter Development",
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
  "benefits": ["Sertifikat", "Source code", "Mentoring"],
  "max_participants": 30,
  "current_participants": 15,
  "images": ["activities/workshop1.jpg"],
  "rating": 4.8,
  "total_reviews": 8
}
```

### 📅 Bookings
```http
GET    /api/bookings               # List user bookings
GET    /api/bookings/{id}         # Get booking detail
POST   /api/bookings              # Create new booking
PUT    /api/bookings/{id}         # Update booking
DELETE /api/bookings/{id}         # Cancel booking
POST   /api/bookings/{id}/cancel  # Cancel with reason
POST   /api/bookings/{id}/confirm # Confirm (provider only)
POST   /api/bookings/{id}/reject  # Reject (provider only)
```

**Create Booking Request:**
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
  "voucher_code": "DISKON50",
  "payment_method": "bank_transfer"
}
```

### 💰 Payments
```http
GET    /api/payments/methods      # Get payment methods
GET    /api/payments/bank-accounts # Get bank accounts
POST   /api/payments/process      # Process payment
POST   /api/payments/upload-proof # Upload payment proof
GET    /api/payments/{id}/status  # Get payment status
POST   /api/payments/{id}/cancel  # Cancel payment
GET    /api/payments/history      # Payment history
```

**Payment Methods Response:**
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

### 🏷️ Vouchers
```http
POST /api/user/vouchers/validate  # Validate voucher code
```

**Voucher Validation Request:**
```json
{
  "code": "DISKON50",
  "bookable_type": "residence",
  "bookable_id": 1,
  "amount": 1500000
}
```

**Voucher Validation Response:**
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
    "final_amount": 750000
  }
}
```

### 🔖 Bookmarks
```http
GET    /api/bookmarks              # List bookmarks
POST   /api/bookmarks              # Add bookmark
DELETE /api/bookmarks/{id}         # Remove bookmark
```

### 🔍 Search & Filters
```http
GET /api/search/global            # Global search
GET /api/search/advanced          # Advanced search with filters
GET /api/search/suggestions       # Search suggestions
GET /api/search/filter-options    # Available filter options
```

**Search Request:**
```json
{
  "q": "kost putri",
  "type": "residence",
  "city": "Jakarta",
  "min_price": 1000000,
  "max_price": 2000000,
  "gender_type": "female"
}
```

### 📊 Dashboard
```http
GET /api/dashboard/user            # User dashboard data
GET /api/dashboard/provider        # Provider dashboard data
GET /api/dashboard/admin           # Admin dashboard data
```

**User Dashboard Response:**
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
    "total_spent": 2500000,
    "favorite_count": 8
  }
}
```

---

## 🎨 UI/UX Guidelines

### Design System
- **Primary Color**: `#2563EB` (Blue)
- **Secondary Color**: `#10B981` (Green)
- **Accent Color**: `#F59E0B` (Yellow)
- **Error Color**: `#EF4444` (Red)
- **Success Color**: `#10B981` (Green)

### Typography
- **Heading 1**: `TextStyle(fontSize: 24, fontWeight: FontWeight.bold)`
- **Heading 2**: `TextStyle(fontSize: 20, fontWeight: FontWeight.w600)`
- **Body**: `TextStyle(fontSize: 16, fontWeight: FontWeight.normal)`
- **Caption**: `TextStyle(fontSize: 14, fontWeight: FontWeight.normal)`

### Spacing
- **Padding**: 16.0 (standard), 24.0 (large)
- **Margin**: 8.0 (small), 16.0 (standard), 24.0 (large)
- **Border Radius**: 8.0 (standard), 16.0 (large)

### Components
- **Buttons**: ElevatedButton dengan rounded corners
- **Cards**: Card dengan elevation 2 dan rounded corners
- **Input Fields**: TextFormField dengan border dan label
- **Loading**: CircularProgressIndicator dengan primary color

---

## 📁 Project Structure

```
lib/
├── main.dart
├── app.dart
├── config/
│   ├── app_config.dart
│   ├── theme.dart
│   └── routes.dart
├── models/
│   ├── user.dart
│   ├── residence.dart
│   ├── activity.dart
│   ├── booking.dart
│   ├── payment.dart
│   └── voucher.dart
├── services/
│   ├── api_service.dart
│   ├── auth_service.dart
│   ├── storage_service.dart
│   └── notification_service.dart
├── providers/
│   ├── auth_provider.dart
│   ├── booking_provider.dart
│   └── user_provider.dart
├── screens/
│   ├── auth/
│   ├── user/
│   ├── provider/
│   ├── admin/
│   └── shared/
├── widgets/
│   ├── common/
│   ├── forms/
│   └── cards/
└── utils/
    ├── constants.dart
    ├── helpers.dart
    └── validators.dart
```

---

## 🚀 Development Instructions

### 1. Setup Project
```bash
# Create new Flutter project
flutter create infoma_mobile_app
cd infoma_mobile_app

# Add dependencies to pubspec.yaml
flutter pub add provider dio flutter_secure_storage
flutter pub add cached_network_image image_picker
flutter pub add shared_preferences hive
flutter pub add intl http_parser

# Get dependencies
flutter pub get
```

### 2. Environment Configuration
```dart
// lib/config/app_config.dart
class AppConfig {
  static const String apiBaseUrl = 'https://your-domain.com/api';
  static const String appName = 'INFOMA';
  static const String appVersion = '1.0.0';
  
  // Development/Production flags
  static const bool isDevelopment = true;
  static const bool enableLogging = true;
}
```

### 3. State Management Setup
```dart
// lib/main.dart
void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => BookingProvider()),
        ChangeNotifierProvider(create: (_) => UserProvider()),
      ],
      child: MyApp(),
    ),
  );
}
```

### 4. API Service Implementation
```dart
// lib/services/api_service.dart
class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  late final Dio _dio;

  void initialize() {
    _dio = Dio(BaseOptions(
      baseUrl: AppConfig.apiBaseUrl,
      connectTimeout: Duration(seconds: 30),
      receiveTimeout: Duration(seconds: 30),
    ));
    
    _dio.interceptors.add(AuthInterceptor());
    _dio.interceptors.add(LogInterceptor(
      requestBody: AppConfig.enableLogging,
      responseBody: AppConfig.enableLogging,
    ));
  }

  // Generic methods
  Future<Response> get(String path, {Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.get(path, queryParameters: params);
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> post(String path, {dynamic data}) async {
    try {
      final response = await _dio.post(path, data: data);
      return response;
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Exception _handleError(DioException e) {
    switch (e.type) {
      case DioExceptionType.connectionTimeout:
        return Exception('Connection timeout');
      case DioExceptionType.receiveTimeout:
        return Exception('Receive timeout');
      case DioExceptionType.badResponse:
        return Exception('Server error: ${e.response?.statusCode}');
      default:
        return Exception('Network error');
    }
  }
}
```

### 5. Model Classes
```dart
// lib/models/user.dart
class User {
  final int id;
  final String name;
  final String email;
  final String role;
  final String? phone;
  final String? avatar;
  final bool isActive;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.phone,
    this.avatar,
    required this.isActive,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'],
      phone: json['phone'],
      avatar: json['avatar'],
      isActive: json['is_active'] ?? true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'phone': phone,
      'avatar': avatar,
      'is_active': isActive,
    };
  }
}
```

---

## 🔧 Testing Guidelines

### Unit Tests
- Test semua service methods
- Test model serialization/deserialization
- Test utility functions

### Widget Tests
- Test semua custom widgets
- Test form validation
- Test navigation flows

### Integration Tests
- Test complete user flows
- Test API integration
- Test error handling

---

## 📱 Platform-Specific Considerations

### iOS
- **Permissions**: Camera, Photo Library, Location
- **App Store**: Screenshots, descriptions, privacy policy
- **Design**: Follow iOS Human Interface Guidelines

### Android
- **Permissions**: Camera, Storage, Location
- **Play Store**: Screenshots, descriptions, privacy policy
- **Design**: Follow Material Design guidelines

---

## 🚨 Error Handling

### API Errors
```dart
try {
  final response = await apiService.get('/residences');
  // Handle success
} on ApiException catch (e) {
  // Handle API errors
  showErrorDialog(context, e.message);
} on NetworkException catch (e) {
  // Handle network errors
  showNetworkErrorDialog(context);
} catch (e) {
  // Handle unexpected errors
  showGenericErrorDialog(context);
}
```

### User Feedback
- **Loading States**: Show loading indicators
- **Error Messages**: Clear, actionable error messages
- **Success Feedback**: Toast messages or snackbars
- **Empty States**: Helpful empty state messages

---

## 📋 Development Checklist

### Phase 1: Foundation
- [ ] Project setup & configuration
- [ ] Authentication system
- [ ] Basic navigation structure
- [ ] API service layer
- [ ] Basic models

### Phase 2: Core Features
- [ ] User dashboard
- [ ] Residence listing & detail
- [ ] Activity listing & detail
- [ ] Search & filtering
- [ ] Booking system

### Phase 3: Advanced Features
- [ ] Payment integration
- [ ] Voucher system
- [ ] Bookmark functionality
- [ ] Provider dashboard
- [ ] Admin dashboard

### Phase 4: Polish
- [ ] UI/UX improvements
- [ ] Performance optimization
- [ ] Error handling
- [ ] Testing
- [ ] Documentation

---

## 📞 Support & Resources

### Documentation
- [Flutter Documentation](https://flutter.dev/docs)
- [Laravel API Documentation](https://laravel.com/docs)
- [Material Design Guidelines](https://material.io/design)

### Tools
- **IDE**: VS Code dengan Flutter extension
- **Design**: Figma untuk UI/UX design
- **API Testing**: Postman atau Insomnia
- **Version Control**: Git dengan conventional commits

### Team Communication
- **Project Management**: Trello, Jira, atau GitHub Projects
- **Communication**: Slack, Discord, atau WhatsApp
- **Code Review**: GitHub Pull Requests
- **Documentation**: GitHub Wiki atau Notion

---

## 🎯 Success Metrics

### Technical Metrics
- **App Performance**: < 3 seconds load time
- **Crash Rate**: < 1% crash rate
- **API Response Time**: < 2 seconds average
- **App Size**: < 50MB APK size

### User Experience Metrics
- **User Retention**: > 70% after 30 days
- **Booking Completion**: > 80% success rate
- **User Satisfaction**: > 4.0 rating
- **Support Tickets**: < 5% of active users

---

## 🔄 Maintenance & Updates

### Regular Tasks
- **Weekly**: Code review & bug fixes
- **Monthly**: Performance monitoring & optimization
- **Quarterly**: Feature updates & security patches
- **Annually**: Major version updates

### Monitoring
- **Crash Reporting**: Firebase Crashlytics
- **Analytics**: Firebase Analytics
- **Performance**: Firebase Performance Monitoring
- **User Feedback**: In-app feedback system

---

## 📝 Notes for Next Developer

1. **Consistency**: Ikuti struktur kode yang sudah ada
2. **Documentation**: Update dokumentasi setiap ada perubahan
3. **Testing**: Test semua fitur sebelum deploy
4. **Performance**: Monitor app performance secara berkala
5. **Security**: Jangan hardcode sensitive data
6. **User Experience**: Prioritaskan kemudahan penggunaan
7. **Accessibility**: Pastikan app accessible untuk semua user
8. **Internationalization**: Siapkan untuk multi-language support

---

**Happy Coding! 🚀**

*Dokumen ini dibuat untuk memastikan konsistensi dan kualitas pengembangan mobile app INFOMA. Update dokumentasi sesuai dengan perubahan yang dilakukan.*
