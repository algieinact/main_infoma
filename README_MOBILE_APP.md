# 🚀 INFOMA Mobile App Development Guide

## 📱 Project Overview
Mobile app Flutter untuk sistem INFOMA (booking residence & activity mahasiswa) yang terintegrasi dengan Laravel API backend.

## 🎯 Core Features
- **User**: Booking residence/activity, payment, voucher, bookmark
- **Provider**: Manage content, approve bookings, create vouchers  
- **Admin**: System management, user moderation, financial reports

## 🏗️ Architecture
- **Frontend**: Flutter 3.16+
- **Backend**: Laravel API (sudah tersedia)
- **Auth**: Laravel Sanctum tokens
- **State Management**: Provider/Riverpod

## 🔌 API Endpoints

### Authentication
```http
POST /api/auth/login
POST /api/auth/register  
POST /api/auth/logout
GET  /api/auth/me
```

### Core Resources
```http
GET    /api/residences              # List residences
GET    /api/activities             # List activities
GET    /api/bookings               # User bookings
POST   /api/bookings               # Create booking
POST   /api/user/vouchers/validate # Validate voucher
GET    /api/bookmarks              # User bookmarks
```

### Dashboard
```http
GET /api/dashboard/user            # User stats
GET /api/dashboard/provider        # Provider stats  
GET /api/dashboard/admin           # Admin stats
```

### Search & Filters
```http
GET /api/search/global            # Global search
GET /api/search/advanced          # Advanced filters
GET /api/search/suggestions       # Search suggestions
```

## 📁 Project Structure
```
lib/
├── main.dart
├── config/           # App config, theme, routes
├── models/           # Data models
├── services/         # API, auth, storage services
├── providers/        # State management
├── screens/          # UI screens
├── widgets/          # Reusable widgets
└── utils/            # Helpers, constants
```

## 🚀 Development Instructions

### 1. Setup
```bash
flutter create infoma_mobile_app
cd infoma_mobile_app
flutter pub add provider dio flutter_secure_storage
flutter pub add cached_network_image image_picker
```

### 2. API Service
```dart
class ApiService {
  static const String baseUrl = 'https://your-domain.com/api';
  
  static Dio getDio() {
    final dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: Duration(seconds: 30),
    ));
    dio.interceptors.add(AuthInterceptor());
    return dio;
  }
}
```

### 3. Authentication Flow
```dart
// Login → Get token → Store securely → Navigate by role
switch (user.role) {
  case 'user': return UserDashboard();
  case 'provider': return ProviderDashboard();
  case 'admin': return AdminDashboard();
}
```

### 4. State Management
```dart
MultiProvider(
  providers: [
    ChangeNotifierProvider(create: (_) => AuthProvider()),
    ChangeNotifierProvider(create: (_) => BookingProvider()),
    ChangeNotifierProvider(create: (_) => UserProvider()),
  ],
  child: MyApp(),
)
```

## 🎨 UI Guidelines
- **Colors**: Primary #2563EB, Secondary #10B981
- **Typography**: Consistent font sizes & weights
- **Spacing**: 16px standard, 24px large
- **Components**: Rounded corners, proper elevation

## 📱 Key Screens

### User Screens
- Login/Register
- Home Dashboard
- Search & Filter
- Residence/Activity Detail
- Booking Form
- Payment
- Profile & Bookmarks

### Provider Screens  
- Dashboard Overview
- Manage Content
- Booking Management
- Voucher Creation

### Admin Screens
- System Overview
- User Management
- Content Moderation
- Financial Reports

## 🔧 Implementation Notes

### 1. Error Handling
```dart
try {
  final response = await apiService.get('/residences');
} on ApiException catch (e) {
  showErrorDialog(context, e.message);
} on NetworkException catch (e) {
  showNetworkErrorDialog(context);
}
```

### 2. Loading States
- Show loading indicators for all async operations
- Implement skeleton screens for better UX
- Handle empty states gracefully

### 3. Image Handling
- Use cached_network_image for network images
- Implement image picker for uploads
- Optimize image sizes

### 4. Local Storage
- flutter_secure_storage for tokens
- shared_preferences for user preferences
- hive for offline data caching

## 🧪 Testing Strategy
- **Unit Tests**: Services, models, utilities
- **Widget Tests**: UI components
- **Integration Tests**: Complete user flows
- **API Tests**: Endpoint testing

## 📋 Development Phases

### Phase 1: Foundation
- [ ] Project setup
- [ ] Authentication
- [ ] Basic navigation
- [ ] API integration

### Phase 2: Core Features  
- [ ] User dashboard
- [ ] Content browsing
- [ ] Booking system
- [ ] Search & filters

### Phase 3: Advanced Features
- [ ] Payment integration
- [ ] Voucher system
- [ ] Provider dashboard
- [ ] Admin features

### Phase 4: Polish
- [ ] UI/UX improvements
- [ ] Performance optimization
- [ ] Testing & bug fixes

## 🚨 Important Notes

1. **Consistency**: Ikuti struktur kode yang ada
2. **Security**: Jangan hardcode sensitive data
3. **Performance**: Monitor app performance
4. **User Experience**: Prioritaskan kemudahan penggunaan
5. **Documentation**: Update docs setiap perubahan

## 📞 Resources
- [Flutter Docs](https://flutter.dev/docs)
- [Laravel API Docs](https://laravel.com/docs)
- [Material Design](https://material.io/design)

## 🎯 Success Metrics
- App performance < 3s load time
- Crash rate < 1%
- User retention > 70%
- Rating > 4.0

---

**Happy Coding! 🚀**

*Update dokumentasi sesuai perubahan yang dilakukan.*
