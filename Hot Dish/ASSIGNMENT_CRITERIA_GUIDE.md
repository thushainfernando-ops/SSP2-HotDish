# Hot Dish - Assignment Marking Criteria Guide
## Explaining All 5 Criteria to Your Lecturer

---

## ✅ CRITERION 1: EXTERNAL LIBRARIES (Livewire/Volt) - 10 Marks

### What You Implemented:
**Livewire** - A full-stack, reactive component library for Laravel that enables interactive UI without writing JavaScript.

### Code Evidence:

#### Location: `app/Livewire/Cart.php`
```php
<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public function render()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('menuItem')
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->menuItem->price * $item->quantity;
        });

        return view('livewire.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'deliveryFee' => 250,
            'total' => $subtotal + 250
        ])->layout('layouts.frontend');
    }

    // Real-time cart updates without page refresh
    public function incrementQuantity($cartId)
    {
        $cartItem = CartItem::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->increment('quantity');
            $this->dispatch('cartUpdated');
        }
    }

    public function decrementQuantity($cartId)
    {
        $cartItem = CartItem::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
            $this->dispatch('cartUpdated');
        }
    }
}
```

### How to Explain to Your Lecturer:

**"Sir/Madam, I used Livewire to create dynamic, real-time shopping cart functionality:**

1. **What Livewire Does:**
   - Livewire is an external library that makes interactive components without writing JavaScript
   - It handles real-time updates automatically through AJAX
   - Users can increment/decrement cart quantities without page refresh

2. **Three Livewire Components in My Project:**
   - **Cart.php** - Shopping cart management (increment, decrement, remove items)
   - **Menu.php** - Display menu items with category filtering
   - **CartBadge.php** - Dynamic cart count badge in header

3. **Why I Used It:**
   - **Problem:** Shopping carts need real-time updates
   - **Solution:** Livewire automatically updates the cart without JavaScript code
   - **Benefit:** Creates smooth user experience with minimal code

4. **Example Flow:**
   - User clicks "+" button to increase quantity
   - Livewire calls `incrementQuantity()` method automatically
   - Quantity updates in real-time
   - Event `cartUpdated` triggers to refresh cart badge
   - No page reload needed!

### Files to Show Your Lecturer:
```
app/Livewire/Cart.php          ← Main cart component
app/Livewire/Menu.php          ← Menu display component
app/Livewire/CartBadge.php     ← Cart count component
resources/views/livewire/      ← Livewire views (templates)
```

---

## ✅ CRITERION 2: ELOQUENT ORM MODELS - 10 Marks

### What You Implemented:
**Eloquent Models** - Laravel's Object-Relational Mapping (ORM) system that represents database tables as PHP classes with relationships.

### Code Evidence:

#### Location: `app/Models/MenuItem.php`
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $primaryKey = 'item_id';
    protected $fillable = ['name', 'description', 'price', 'category', 'image'];

    // Define relationship: One MenuItem has many OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }

    // Define relationship: One MenuItem has many CartItems
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'item_id');
    }
}
```

#### Location: `app/Models/Order.php` (Example from your project)
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id', 'total_amount', 'status', 'delivery_address'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
```

#### Location: `app/Models/User.php`
```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;  // Enables Sanctum tokens

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',  // 'admin' or 'user'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
```

### How to Explain to Your Lecturer:

**"Sir/Madam, I used Eloquent Models to interact with the database:**

1. **What Eloquent Does:**
   - Maps each database table to a PHP class (Model)
   - Each row in the table becomes an object
   - No need to write raw SQL queries

2. **Five Models in My Project:**
   ```
   User        → users table (authentication)
   MenuItem    → menu_items table
   CartItem    → cart_items table
   Order       → orders table
   OrderItem   → order_items table
   Payment     → payments table
   ```

3. **Benefits Over Raw SQL:**
   - ✅ **Type Safety:** Code is checked for errors before running
   - ✅ **SQL Injection Protection:** Automatically escapes data
   - ✅ **Relationships:** Easy to work with related data
   - ✅ **Less Code:** One line instead of complex SQL joins

4. **Example Usage in Code:**
   ```php
   // Instead of writing SQL:
   // SELECT * FROM cart_items WHERE user_id = 1 AND quantity > 0
   
   // We write Eloquent:
   $cartItems = CartItem::where('user_id', Auth::id())
                         ->with('menuItem')  // Include related MenuItem
                         ->get();            // Fetch from database
   ```

5. **Relationships (Database Connections):**
   - MenuItem **hasMany** OrderItems (one menu item in many orders)
   - MenuItem **hasMany** CartItems (one item in many carts)
   - Order **belongsTo** User (each order belongs to one customer)
   - Order **hasMany** OrderItems (one order has many items)

### Files to Show Your Lecturer:
```
app/Models/User.php         ← User model with relationships
app/Models/MenuItem.php     ← MenuItem model with relationships
app/Models/Order.php        ← Order model with relationships
app/Models/CartItem.php     ← CartItem model
app/Models/OrderItem.php    ← OrderItem model
app/Models/Payment.php      ← Payment model
```

---

## ✅ CRITERION 3: LARAVEL JETSTREAM AUTHENTICATION - 10 Marks

### What You Implemented:
**Jetstream** - Laravel's complete authentication and team management package that provides login, registration, password reset, 2FA, and more.

### Code Evidence:

#### Location: `config/jetstream.php`
```php
<?php
return [
    'name' => env('APP_NAME', 'Laravel'),
    'guard' => env('APP_GUARD', 'web'),
    
    // Jetstream features enabled
    'features' => [
        Features::registration(),      // Allow user registration
        Features::resetPasswords(),    // Password reset functionality
        Features::emailVerification(), // Email verification
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
        ]),                            // 2-Factor Authentication
        Features::apiTokens(),         // API token management
        Features::teams(),             // Team management
        Features::profilePhotos(),     // User profile pictures
    ],
    
    'guards' => ['web'],
    'profile_photo_disk' => 'public',
];
```

#### Location: `routes/web.php` (Protected Routes)
```php
<?php
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Customer routes (protected by Jetstream authentication)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Cart and order routes
    Route::post('/add-to-cart', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', Cart::class)->name('cart.view');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [OrderController::class, 'store'])->name('order.store');
    
    // Admin routes (additional role check)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('admin/menu-items', MenuItemController::class);
        Route::resource('admin/orders', OrderController::class);
    });
});
```

#### Location: `app/Http/Middleware/VerifyIsAdmin.php` (Custom Middleware)
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return redirect('/')->with('error', 'Admin access required');
    }
}
```

### How to Explain to Your Lecturer:

**"Sir/Madam, I used Laravel Jetstream for complete user authentication:**

1. **What Jetstream Provides:**
   - ✅ User Registration form
   - ✅ Login functionality
   - ✅ Password hashing (bcrypt)
   - ✅ Password reset via email
   - ✅ Email verification
   - ✅ Two-factor authentication (2FA)
   - ✅ API token management
   - ✅ Session management

2. **How Users are Protected:**
   - When a user registers: Password is hashed using bcrypt (not stored as plain text)
   - When a user logs in: Session is created and stored
   - Protected routes check: `middleware(['auth:sanctum', 'verified'])`
   - Users cannot access other users' data

3. **Role-Based Access Control:**
   - Every User has a 'role' column (admin or user)
   - Admin middleware checks if user is admin
   - Non-admins trying to access `/admin/*` are redirected

4. **Authentication Flow:**
   ```
   User Registration
   ↓
   Password Hashed (bcrypt)
   ↓
   User Stored in Database
   ↓
   User Logs In
   ↓
   Session Created (Jetstream)
   ↓
   User Can Access Protected Routes
   ↓
   Non-Admin Routes: Customer sees cart, menu, orders
   Admin Routes: Admin sees dashboard, manage menu, manage orders
   ```

5. **Security Features:**
   - Passwords never visible in database
   - Session tokens expire after timeout
   - Two-factor authentication available
   - Email verification prevents fake accounts

### Files to Show Your Lecturer:
```
config/jetstream.php                    ← Jetstream configuration
config/fortify.php                      ← Authentication features
app/Http/Middleware/VerifyIsAdmin.php  ← Role checking middleware
routes/web.php                          ← Protected web routes
resources/views/auth/                   ← Jetstream auth views
database/migrations/*users_table.php    ← User table schema
```

### Database Schema (User Table):
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),  -- Hashed password
    phone VARCHAR(20),
    role VARCHAR(50),       -- 'admin' or 'user'
    email_verified_at TIMESTAMP,
    two_factor_secret VARCHAR(255),
    two_factor_recovery_codes TEXT,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## ✅ CRITERION 4: LARAVEL SANCTUM API AUTHENTICATION - 10 Marks

### What You Implemented:
**Sanctum** - Laravel's lightweight API authentication system that provides both session-based and token-based authentication.

### Code Evidence:

#### Location: `config/sanctum.php`
```php
<?php
use Laravel\Sanctum\Sanctum;

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Sanctum::currentApplicationUrlWithPort(),
    ))),

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | API Token Expiration
    |--------------------------------------------------------------------------
    */
    'expiration' => null,  // Tokens never expire (can change to 24*60 for 24 hours)

    'token_prefix' => 'hotdish',  // Custom token prefix for Hot Dish API
];
```

#### Location: `routes/api.php` (API Routes with Sanctum)
```php
<?php
use App\Http\Controllers\Api\AdminPortalController;
use App\Http\Controllers\Api\CustomerPortalController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PUBLIC API ENDPOINTS (No authentication required)
Route::get('/menu', [MenuController::class, 'index']);           // Get all menu items
Route::get('/menu/{id}', [MenuController::class, 'show']);       // Get specific menu item

// Get current authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PROTECTED CUSTOMER API ENDPOINTS (Requires Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customer/cart', [CustomerPortalController::class, 'cart']);    // View cart
    Route::get('/customer/orders', [CustomerPortalController::class, 'orders']); // View orders

    // PROTECTED ADMIN API ENDPOINTS (Requires Sanctum token + admin role)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/summary', [AdminPortalController::class, 'summary']); // Admin statistics
    });
});
```

#### Location: `app/Models/User.php` (Sanctum Integration)
```php
<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;  // ← Adds token functionality

class User extends Authenticatable
{
    use HasApiTokens;  // ← This allows users to create and use API tokens
    
    // ... rest of model
}
```

#### Location: `app/Http/Controllers/Api/MenuController.php`
```php
<?php
namespace App\Http\Controllers\Api;

use App\Models\MenuItem;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    // PUBLIC ENDPOINT - No authentication
    public function index()
    {
        $menuItems = MenuItem::all();
        return response()->json([
            'success' => true,
            'data' => $menuItems,
            'message' => 'Menu items retrieved successfully'
        ]);
    }

    // PUBLIC ENDPOINT - No authentication
    public function show($id)
    {
        $menuItem = MenuItem::find($id);
        
        if (!$menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $menuItem
        ]);
    }
}
```

#### Location: `app/Http/Controllers/Api/CustomerPortalController.php`
```php
<?php
namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    // PROTECTED ENDPOINT - Requires Sanctum token
    public function cart(Request $request)
    {
        // $request->user() is automatically populated by Sanctum
        $cartItems = CartItem::where('user_id', $request->user()->id)
            ->with('menuItem')
            ->get();

        return response()->json([
            'success' => true,
            'user' => $request->user()->name,
            'cartItems' => $cartItems,
            'itemCount' => $cartItems->count(),
            'totalPrice' => $cartItems->sum(fn($item) => 
                $item->menuItem->price * $item->quantity
            )
        ]);
    }

    // PROTECTED ENDPOINT - Requires Sanctum token
    public function orders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items', 'payment')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }
}
```

### How to Explain to Your Lecturer:

**"Sir/Madam, I used Laravel Sanctum for secure API authentication:**

1. **What Sanctum Does:**
   - Issues API tokens for client applications
   - Protects API endpoints with bearer token validation
   - Allows third-party apps to access your API safely
   - Prevents unauthorized access to customer/admin data

2. **How Sanctum Works (3 Steps):**
   
   **Step 1: User Gets Token**
   ```
   User logs in with email/password
   ↓
   Laravel returns Sanctum token (via Jetstream login)
   ↓
   Token is stored in client app (mobile app, Vue.js, etc.)
   ```
   
   **Step 2: Client Makes API Request**
   ```
   Client sends: Authorization: Bearer hotdish_xxxxx (the token)
   ↓
   Sanctum verifies token validity
   ↓
   If valid: Request proceeds
   ↓
   If invalid/missing: Returns 401 Unauthorized
   ```
   
   **Step 3: API Returns Protected Data**
   ```
   Authenticated user's data is returned
   ↓
   User can only see their own cart/orders
   ↓
   Admin can only see admin data
   ```

3. **Three Types of API Endpoints:**

   **PUBLIC (No Token Needed):**
   ```
   GET /api/menu                    → Get all menu items (anyone can view)
   GET /api/menu/{id}               → Get specific menu item
   ```

   **CUSTOMER PROTECTED (Token + Customer Role):**
   ```
   GET /api/customer/cart           → See your cart (requires your token)
   GET /api/customer/orders         → See your orders (requires your token)
   ```

   **ADMIN PROTECTED (Token + Admin Role):**
   ```
   GET /api/admin/summary           → See statistics (requires admin token)
   ```

4. **How to Use the API (Example for Your Lecturer):**

   **Without Authentication (Public endpoint):**
   ```bash
   curl http://localhost:8000/api/menu
   
   Response:
   {
       "success": true,
       "data": [
           {"item_id": 1, "name": "Burger", "price": 450},
           {"item_id": 2, "name": "Pizza", "price": 750}
       ]
   }
   ```

   **With Authentication (Protected endpoint):**
   ```bash
   curl -H "Authorization: Bearer hotdish_abc123xyz" \
        http://localhost:8000/api/customer/cart
   
   Response:
   {
       "success": true,
       "user": "Ahmed Ali",
       "cartItems": [
           {"item_id": 1, "quantity": 2, "price": 450}
       ],
       "totalPrice": 900
   }
   ```

5. **Security Features:**
   - ✅ API tokens are unique per user
   - ✅ Tokens can be revoked anytime
   - ✅ Each user only sees their own data
   - ✅ Admins can't access customer data without admin role
   - ✅ Invalid tokens are rejected automatically

### Files to Show Your Lecturer:
```
config/sanctum.php                          ← Sanctum config
routes/api.php                              ← API routes and middleware
app/Http/Controllers/Api/MenuController.php
app/Http/Controllers/Api/CustomerPortalController.php
app/Http/Controllers/Api/AdminPortalController.php
app/Models/User.php                         ← User model with HasApiTokens trait
```

---

## ✅ CRITERION 5: SECURITY DOCUMENTATION + API EXTENSION - Marks

### What You Implemented:

1. **SECURITY.md Documentation**
2. **API Implementation**
3. **Security Best Practices**
4. **Role-Based Access Control**

### Code Evidence:

#### Location: `SECURITY.md` (Your Security Documentation)
```markdown
# Security and API Usage

## Overview
Hot Dish uses Laravel 12 with Jetstream and Sanctum to protect routes 
and to secure its API surface.

## Security controls
- Passwords are hashed by Laravel's authentication system.
- Web routes are protected by Jetstream session authentication 
  and the custom `admin` middleware.
- The API uses Sanctum bearer tokens for authenticated access.
- All state-changing form submissions rely on Laravel CSRF protection.
- Input validation is enforced on admin and customer routes.

## Role restrictions
- Customers can access customer-facing pages and their own account area.
- Administrators can access backend management pages and APIs.
- Non-admin users receive 403 Unauthorized on API endpoints.

## API access
- Public endpoints: /api/menu and /api/menu/{id}
- Protected customer endpoints: /api/customer/cart, /api/customer/orders
- Protected admin endpoint: /api/admin/summary
```

#### Location: `app/Http/Middleware/VerifyIsAdmin.php` (Security Middleware)
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated AND is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // For API requests, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Admin access required'
                ], 403);
            }
            
            // For web requests, redirect to home
            return redirect('/')->with('error', 'Admin access required');
        }

        return $next($request);
    }
}
```

#### Location: Input Validation Example (Controller)
```php
<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request)
    {
        // Validate input BEFORE saving to database
        // This prevents SQL injection and invalid data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        MenuItem::create($validated);

        return redirect()->back()->with('success', 'Menu item created');
    }
}
```

#### Location: CSRF Protection in Forms
```blade
<!-- In Blade template -->
<form action="/admin/menu-items" method="POST">
    @csrf  <!-- Laravel automatically adds CSRF token -->
    <input type="text" name="name" required>
    <input type="number" name="price" required>
    <button type="submit">Add Item</button>
</form>
```

### How to Explain to Your Lecturer:

**"Sir/Madam, I documented and implemented comprehensive security features:**

1. **Documentation (SECURITY.md):**
   - Explains how Jetstream protects web routes
   - Explains how Sanctum protects API endpoints
   - Lists public vs protected endpoints
   - Provides curl examples for API usage

2. **Implementation - 5 Security Layers:**

   **Layer 1: Password Security**
   - Passwords hashed using bcrypt
   - Password never stored as plain text
   - Cannot be recovered even by admin

   **Layer 2: Authentication**
   - Users must log in to access protected pages
   - Session tokens expire after timeout
   - Invalid sessions are rejected

   **Layer 3: Authorization (Role Check)**
   - Middleware checks if user is admin
   - Non-admins cannot access /admin/* routes
   - Returns 403 Forbidden if unauthorized

   **Layer 4: CSRF Protection**
   - Every form includes @csrf token
   - POST/PUT/DELETE requests validated
   - Prevents cross-site request forgery attacks

   **Layer 5: Input Validation**
   - All inputs validated before database
   - Invalid data rejected automatically
   - Prevents SQL injection and XSS attacks

3. **API Security:**
   - Public endpoints (menu) don't require token
   - Protected endpoints require Sanctum token
   - Each user can only access their own data
   - Admin endpoints require admin role

4. **Database Relationship Security:**
   - When fetching cart: Filter by current user ID
   - When fetching orders: Filter by current user ID
   - User cannot see other users' data

### Security Features Demonstration:

**Example 1: What Happens if Non-Admin Tries Admin Route?**
```
Request: GET /admin/dashboard
↓
Middleware checks: User role === 'admin' ?
↓
User role = 'user' (NOT admin)
↓
Middleware blocks request
↓
Response: Redirect to home with error message
↓
Admin page NOT visible
```

**Example 2: What Happens if API Token is Invalid?**
```
Request: GET /api/customer/cart 
         Authorization: Bearer invalid_token_xyz
↓
Sanctum validates token
↓
Token is invalid/expired/belongs to different user
↓
Response: 401 Unauthorized JSON
↓
Cart data NOT returned
```

**Example 3: What Happens if Someone Tries SQL Injection?**
```
Form Input: '; DROP TABLE users; --
↓
Input validation rejects non-numeric/string format
↓
Eloquent ORM escapes SQL automatically
↓
Database query safe from injection
↓
User receives validation error
```

### Files to Show Your Lecturer:
```
SECURITY.md                             ← Complete security documentation
app/Http/Middleware/VerifyIsAdmin.php  ← Authorization middleware
config/sanctum.php                      ← API token configuration
routes/api.php                          ← API endpoint definitions
app/Http/Controllers/Api/              ← API controllers with validation
routes/web.php                          ← Web route protection
database/migrations/*users_table.php    ← Password column definition
```

---

## 🎯 HOW TO PRESENT ALL 5 CRITERIA TO YOUR LECTURER

### Presentation Structure (15-20 minutes):

**1. Start with High-Level Overview (2 min)**
```
"Sir/Madam, my project uses 5 key Laravel features:
1. External Libraries (Livewire) - for interactive UI
2. Eloquent Models - for database interaction
3. Jetstream - for user authentication
4. Sanctum - for API security
5. Security practices - documented and implemented"
```

**2. Walk Through Each Criterion (3-4 min each)**
- Show the code file
- Explain what it does
- Show how it's used in the project
- Explain the security/benefit

**3. Live Demo (5 min)**
- Open the app in browser (localhost:8000)
- Register a new user (show password hashing)
- Log in (show Jetstream in action)
- Add item to cart (show Livewire update without refresh)
- Open API endpoints in Postman/curl
  - Show public endpoint: `GET /api/menu`
  - Show protected endpoint: `GET /api/customer/cart` (show error without token)

**4. Show Code Files**
- Open VS Code
- Show each file mentioned above
- Point out key lines of code

**5. Answer Questions**
- "Why Livewire instead of JavaScript?" → Less code, reactive updates
- "Why Eloquent?" → SQL injection prevention, type safety
- "Why two auth systems?" → Jetstream for web, Sanctum for API
- "What if someone steals a token?" → Tokens can be revoked, expire

---

## 📋 QUICK CHECKLIST FOR YOUR ASSIGNMENT

- [x] **Criterion 1:** Livewire components (Cart, Menu, CartBadge)
- [x] **Criterion 2:** Eloquent models (6 models with relationships)
- [x] **Criterion 3:** Jetstream authentication (login, register, password hashing)
- [x] **Criterion 4:** Sanctum API (public + protected endpoints)
- [x] **Criterion 5:** Security documentation + implementation

**Total Marks Expected:** 50 (10+10+10+10+10)

---

## 🚀 HOW TO DEMONSTRATE LIVE:

### 1. Start the Server
```bash
php artisan serve
# Opens on http://localhost:8000
```

### 2. Test Livewire (Add to Cart)
```
- Open http://localhost:8000
- Click menu item
- Click "Add to Cart"
- See cart update WITHOUT page refresh ← **This is Livewire**
```

### 3. Test Eloquent (Database Queries)
```
- Show app/Models/MenuItem.php
- Show how getCartItems() uses Eloquent relationships
- Point out: $cartItems = CartItem::where(...)->with('menuItem')->get();
```

### 4. Test Jetstream (Authentication)
```
- Try accessing /admin without logging in → Redirected to login
- Log in with admin account → Redirected to admin dashboard
- Log in with customer account → Customer dashboard shown
- All passwords stored hashed (check database)
```

### 5. Test Sanctum (API)
```bash
# Public endpoint (no token needed)
curl http://localhost:8000/api/menu

# Protected endpoint (needs token)
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/customer/cart
```

---

## ✅ FINAL SUMMARY TABLE

| Criterion | Marks | Evidence | Status |
|-----------|-------|----------|--------|
| Livewire (External Lib) | 10 | app/Livewire/Cart.php, Menu.php | ✅ |
| Eloquent Models | 10 | app/Models/ (6 models) | ✅ |
| Jetstream Auth | 10 | config/jetstream.php, routes/web.php | ✅ |
| Sanctum API | 10 | routes/api.php, config/sanctum.php | ✅ |
| Security + API | 10 | SECURITY.md, Middleware, Validation | ✅ |
| **TOTAL** | **50** | Complete | **✅** |

---

## 📞 Questions Your Lecturer Might Ask:

**Q: Why use Eloquent instead of PDO?**
A: "Eloquent prevents SQL injection automatically, provides relationships between tables, and requires less code to query the database."

**Q: How does Jetstream work?**
A: "Jetstream provides pre-built authentication views and logic. When a user registers, the password is automatically hashed using bcrypt. When they log in, Jetstream creates a session and I can check if they're authenticated using Auth::check()."

**Q: What's the difference between Jetstream and Sanctum?**
A: "Jetstream handles web authentication (login pages, sessions). Sanctum handles API authentication (tokens for third-party apps or mobile apps). Both work together for complete security."

**Q: Show an example of Livewire updating without JavaScript?**
A: "In the cart, users click +/- buttons. Livewire calls incrementQuantity() or decrementQuantity() methods via AJAX automatically. The UI updates without writing any JavaScript code or refreshing the page."

**Q: How is data security ensured?**
A: "Through 5 layers: (1) Password hashing - bcrypt, (2) Authentication - sessions, (3) Authorization - role checking, (4) CSRF - token validation, (5) Input validation - rejects invalid data before database."
