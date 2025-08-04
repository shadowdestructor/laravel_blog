# Design Document

## Overview

Laravel Blog Yönetim Sistemi, Laravel 8 framework'ü kullanarak geliştirilecek modern bir blog platformudur. Sistem, MVC mimarisi prensiplerine uygun olarak tasarlanmış olup, kullanıcı dostu arayüz, güvenli authentication, esnek içerik yönetimi ve responsive tasarım özelliklerini içerir.

## Architecture

### MVC Architecture

-   **Models**: User, Post, Category, Comment, Role
-   **Views**: Blade templates ile responsive UI
-   **Controllers**: RESTful controller yapısı
-   **Routes**: Web routes ve API routes ayrımı
-   **Middleware**: Authentication, authorization, CORS

### Database Design

```
Users Table:
- id (primary key)
- name
- email (unique)
- email_verified_at
- password
- role_id (foreign key)
- avatar
- bio
- created_at, updated_at

Posts Table:
- id (primary key)
- title
- slug (unique)
- content (text)
- excerpt
- featured_image
- status (draft, published, pending)
- user_id (foreign key)
- category_id (foreign key)
- views_count
- created_at, updated_at

Categories Table:
- id (primary key)
- name (unique)
- slug (unique)
- description
- color
- created_at, updated_at

Comments Table:
- id (primary key)
- content
- user_id (foreign key)
- post_id (foreign key)
- parent_id (foreign key, nullable)
- status (approved, pending, rejected)
- created_at, updated_at

Roles Table:
- id (primary key)
- name (admin, editor, author, reader)
- permissions (JSON)
- created_at, updated_at
```

## Components and Interfaces

### Authentication System

-   **Laravel Sanctum** for API authentication
-   **Built-in Auth** for web authentication
-   **Custom middleware** for role-based access control
-   **Password reset** functionality
-   **Email verification** system

### User Management

```php
// User Model relationships
class User extends Authenticatable
{
    public function posts() // hasMany
    public function comments() // hasMany
    public function role() // belongsTo
}
```

### Content Management

```php
// Post Model relationships
class Post extends Model
{
    public function user() // belongsTo
    public function category() // belongsTo
    public function comments() // hasMany
    public function tags() // belongsToMany
}

// Category Model relationships
class Category extends Model
{
    public function posts() // hasMany
}
```

### Comment System

```php
// Comment Model relationships
class Comment extends Model
{
    public function user() // belongsTo
    public function post() // belongsTo
    public function replies() // hasMany (self-referencing)
    public function parent() // belongsTo (self-referencing)
}
```

### Controllers Structure

#### Web Controllers

-   **HomeController**: Ana sayfa, blog listesi
-   **AuthController**: Kayıt, giriş, çıkış
-   **PostController**: Blog yazısı CRUD işlemleri
-   **CategoryController**: Kategori yönetimi
-   **CommentController**: Yorum yönetimi
-   **AdminController**: Admin panel işlemleri
-   **UserController**: Kullanıcı profil yönetimi

#### API Controllers (Future extension)

-   **API\PostController**: RESTful API endpoints
-   **API\AuthController**: Token-based authentication

### Middleware Components

-   **Authenticate**: Kullanıcı doğrulama
-   **RedirectIfAuthenticated**: Giriş yapmış kullanıcı yönlendirme
-   **RoleMiddleware**: Rol tabanlı erişim kontrolü
-   **AdminMiddleware**: Admin erişim kontrolü

## Data Models

### User Model

```php
protected $fillable = [
    'name', 'email', 'password', 'role_id', 'avatar', 'bio'
];

protected $hidden = [
    'password', 'remember_token'
];

protected $casts = [
    'email_verified_at' => 'datetime'
];
```

### Post Model

```php
protected $fillable = [
    'title', 'slug', 'content', 'excerpt', 'featured_image',
    'status', 'user_id', 'category_id'
];

protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
];

// Scopes
public function scopePublished($query)
public function scopeByCategory($query, $categoryId)
public function scopeByAuthor($query, $userId)
```

### Category Model

```php
protected $fillable = [
    'name', 'slug', 'description', 'color'
];

// Mutators
public function setNameAttribute($value)
public function setSlugAttribute($value)
```

### Comment Model

```php
protected $fillable = [
    'content', 'user_id', 'post_id', 'parent_id', 'status'
];

// Scopes
public function scopeApproved($query)
public function scopeForPost($query, $postId)
```

## Error Handling

### Exception Handling

-   **Custom Exception Handler**: app/Exceptions/Handler.php
-   **404 Error Pages**: Özel 404 sayfası
-   **500 Error Pages**: Özel server error sayfası
-   **Validation Errors**: Form validation hata mesajları
-   **Authentication Errors**: Giriş hata mesajları

### Logging Strategy

-   **Daily Log Files**: storage/logs/laravel.log
-   **Error Levels**: Emergency, Alert, Critical, Error, Warning, Notice, Info, Debug
-   **Custom Log Channels**: Admin actions, user activities

### Validation Rules

```php
// Post validation
'title' => 'required|string|max:255|unique:posts,title',
'content' => 'required|string|min:100',
'category_id' => 'required|exists:categories,id',
'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'

// User validation
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed'

// Comment validation
'content' => 'required|string|min:10|max:1000'
```

## Testing Strategy

### Unit Tests

-   **Model Tests**: User, Post, Category, Comment model testleri
-   **Service Tests**: Business logic testleri
-   **Helper Tests**: Utility function testleri

### Feature Tests

-   **Authentication Tests**: Kayıt, giriş, çıkış testleri
-   **Post Management Tests**: CRUD işlem testleri
-   **Comment System Tests**: Yorum sistemi testleri
-   **Admin Panel Tests**: Admin işlevleri testleri

### Browser Tests (Laravel Dusk)

-   **User Journey Tests**: Tam kullanıcı deneyimi testleri
-   **Form Interaction Tests**: Form gönderimi testleri
-   **Navigation Tests**: Site navigasyon testleri

### Database Testing

-   **Factory Classes**: Test verisi üretimi
-   **Seeder Classes**: Test veritabanı hazırlama
-   **Migration Tests**: Veritabanı şeması testleri

## Frontend Design

### Blade Template Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php (Ana layout)
│   ├── admin.blade.php (Admin layout)
│   └── guest.blade.php (Misafir layout)
├── components/
│   ├── navbar.blade.php
│   ├── sidebar.blade.php
│   ├── post-card.blade.php
│   └── comment.blade.php
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── passwords/
├── posts/
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── users.blade.php
│   └── posts.blade.php
└── home.blade.php
```

### CSS Framework

-   **Bootstrap 5**: Responsive grid system ve component'ler
-   **Custom SCSS**: Özel stil dosyaları
-   **Laravel Mix**: Asset compilation

### JavaScript Components

-   **Alpine.js**: Lightweight JavaScript framework
-   **Axios**: HTTP client for AJAX requests
-   **Custom JS**: Form validation, interactive elements

## Security Considerations

### Authentication & Authorization

-   **CSRF Protection**: Tüm formlarda CSRF token
-   **Password Hashing**: Bcrypt ile şifre hashleme
-   **Session Security**: Secure session configuration
-   **Role-based Access**: Middleware ile yetki kontrolü

### Data Validation

-   **Input Sanitization**: XSS koruması
-   **SQL Injection Prevention**: Eloquent ORM kullanımı
-   **File Upload Security**: Dosya tipi ve boyut kontrolü
-   **Rate Limiting**: API ve form gönderim sınırlaması

### Configuration Security

-   **Environment Variables**: Hassas bilgilerin .env dosyasında saklanması
-   **Debug Mode**: Production'da debug kapalı
-   **HTTPS Enforcement**: SSL sertifikası kullanımı
-   **Database Security**: Güvenli veritabanı bağlantısı

## Performance Optimization

### Database Optimization

-   **Eager Loading**: N+1 query probleminin önlenmesi
-   **Database Indexing**: Sık kullanılan kolonlarda index
-   **Query Optimization**: Efficient Eloquent queries
-   **Database Connection Pooling**: Bağlantı havuzu yönetimi

### Caching Strategy

-   **Route Caching**: Route cache kullanımı
-   **Config Caching**: Configuration cache
-   **View Caching**: Compiled view caching
-   **Query Result Caching**: Veritabanı sorgu sonuçları cache

### Asset Optimization

-   **CSS/JS Minification**: Laravel Mix ile asset sıkıştırma
-   **Image Optimization**: Resim boyutlandırma ve sıkıştırma
-   **CDN Integration**: Static asset'ler için CDN kullanımı
-   **Lazy Loading**: Sayfa yükleme optimizasyonu
