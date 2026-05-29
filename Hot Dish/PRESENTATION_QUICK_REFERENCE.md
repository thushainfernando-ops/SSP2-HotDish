# Hot Dish - Quick Reference Card for Presentation
## Talking Points & Code Snippets (1-2 Minutes Each)

---

## 🎯 CRITERION 1: LIVEWIRE - 10 Marks
**Opening Statement:** "I used Livewire to make the shopping cart update in real-time without any JavaScript code."

**Key Points:**
- Livewire = External library for interactive components
- Used for: Cart (increment/decrement), Menu display, Cart badge count
- Benefit: Real-time updates without page refresh

**Code to Show:**
```php
// File: app/Livewire/Cart.php
public function incrementQuantity($cartId)
{
    $cartItem = CartItem::find($cartId);
    if ($cartItem && $cartItem->user_id === Auth::id()) {
        $cartItem->increment('quantity');
        $this->dispatch('cartUpdated');  // ← Triggers UI update
    }
}
```

**Live Demo:**
- Click "+" button in cart → See quantity increase WITHOUT page refresh

---

## 🎯 CRITERION 2: ELOQUENT MODELS - 10 Marks
**Opening Statement:** "I used Eloquent ORM to interact with the database instead of writing raw SQL queries."

**Key Points:**
- Eloquent = Object-Relational Mapping
- 6 Models: User, MenuItem, CartItem, Order, OrderItem, Payment
- Benefit: SQL injection prevention, relationships, less code

**Code to Show:**
```php
// File: app/Models/MenuItem.php
class MenuItem extends Model
{
    public function cartItems()  // Relationship
    {
        return $this->hasMany(CartItem::class, 'item_id');
    }
}

// Usage (no SQL needed!):
$cartItems = CartItem::where('user_id', Auth::id())
    ->with('menuItem')  // Joins table automatically
    ->get();
```

**Why It Matters:**
- Raw SQL: `SELECT * FROM cart_items WHERE user_id = ? AND item_id = ?`
- Eloquent: `CartItem::where('user_id', $id)->with('menuItem')->get();`
- Eloquent escapes SQL automatically → SQL injection prevented

---

## 🎯 CRITERION 3: JETSTREAM - 10 Marks
**Opening Statement:** "I used Laravel Jetstream for complete user authentication with password hashing and role-based access."

**Key Points:**
- Jetstream = Complete auth package (login, register, password reset, 2FA)
- Password hashing = bcrypt (cannot be reversed)
- Role-based access = admin vs customer roles

**Code to Show:**
```php
// File: config/jetstream.php
'features' => [
    Features::registration(),           // Signup form
    Features::resetPasswords(),         // Password reset
    Features::emailVerification(),      // Email verification
    Features::twoFactorAuthentication(), // 2FA
    Features::apiTokens(),              // API tokens
],
```

**Protected Routes:**
```php
// File: routes/web.php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', ...);      // Only logged-in users
    
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', ...);  // Only admins
    });
});
```

**Password Security:**
- Passwords stored hashed (bcrypt algorithm)
- Admin cannot see or recover passwords
- If database stolen, passwords still encrypted

**Live Demo:**
- Show login page → This is Jetstream
- Try to access /admin without login → Redirected to login
- Log in as admin → Admin panel shown
- Log in as customer → Customer dashboard shown

---

## 🎯 CRITERION 4: SANCTUM - 10 Marks
**Opening Statement:** "I used Laravel Sanctum to secure the API with bearer tokens, allowing only authenticated users to access their data."

**Key Points:**
- Sanctum = API authentication with tokens
- Public endpoints: /api/menu (no token needed)
- Protected endpoints: /api/customer/cart (token required)

**Code to Show:**
```php
// File: routes/api.php

// PUBLIC (no auth needed)
Route::get('/menu', [MenuController::class, 'index']);

// PROTECTED (Sanctum token required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customer/cart', [CustomerPortalController::class, 'cart']);
    Route::get('/customer/orders', [CustomerPortalController::class, 'orders']);
    
    // ADMIN ONLY
    Route::middleware('admin')->group(function () {
        Route::get('/admin/summary', [AdminPortalController::class, 'summary']);
    });
});
```

**How API Works:**
```php
// File: app/Http/Controllers/Api/CustomerPortalController.php
public function cart(Request $request)
{
    // Sanctum automatically provides $request->user()
    $cartItems = CartItem::where('user_id', $request->user()->id)
        ->with('menuItem')
        ->get();

    return response()->json($cartItems);
}
```

**Live Demo - Using curl:**
```bash
# Public endpoint - works without token
curl http://localhost:8000/api/menu

# Protected endpoint - fails without token
curl http://localhost:8000/api/customer/cart
# Response: {"message": "Unauthenticated"}

# Protected endpoint - works with token
curl -H "Authorization: Bearer hotdish_xyz123" \
     http://localhost:8000/api/customer/cart
# Response: {"success": true, "cartItems": [...]}
```

---

## 🎯 CRITERION 5: SECURITY + API - 10 Marks
**Opening Statement:** "I documented all security features in SECURITY.md and implemented 5 layers of protection."

**Key Points:**
- Security documentation provided
- 5 layers: passwords, auth, authorization, CSRF, input validation

**Code to Show:**

**Layer 1: Password Hashing**
```php
// User model automatically hashes passwords
$user = User::create([
    'email' => 'user@example.com',
    'password' => Hash::make($request->password)  // ← Hashed
]);
// Database stores: $2y$10$... (encrypted)
```

**Layer 2: Authentication Check**
```php
// Must be logged in to access
if (!Auth::check()) {
    return redirect('/login');
}
```

**Layer 3: Authorization Middleware**
```php
// File: app/Http/Middleware/VerifyIsAdmin.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    return $next($request);
}
```

**Layer 4: CSRF Protection**
```blade
<!-- Every form includes CSRF token -->
<form method="POST" action="/order">
    @csrf  <!-- Laravel validates this -->
    <input type="text" name="address">
    <button>Place Order</button>
</form>
```

**Layer 5: Input Validation**
```php
// All inputs validated before saving
$validated = $request->validate([
    'price' => 'required|numeric|min:0',
    'name' => 'required|string|max:255',
    'image' => 'nullable|image|mimes:jpeg,png|max:2048'
]);
// Invalid data automatically rejected
```

**Security Documentation:**
```
File: SECURITY.md
- How passwords are protected
- How authentication works
- How API is secured
- Public vs protected endpoints
- Example curl commands
```

**Live Demo - Security in Action:**
```
1. Try accessing /admin without login → Redirected to login
2. Try API without token → 401 Unauthorized
3. Try invalid form data → Validation error
4. Check database → Passwords are hashed (not plain text)
```

---

## 📊 SCORING SUMMARY TABLE

| Criterion | Marks | What to Show | Time |
|-----------|-------|-------------|------|
| **1. Livewire** | 10 | app/Livewire/Cart.php + Live demo | 2 min |
| **2. Eloquent** | 10 | app/Models/ + Database diagram | 2 min |
| **3. Jetstream** | 10 | config/jetstream.php + Login demo | 2 min |
| **4. Sanctum** | 10 | routes/api.php + curl examples | 2 min |
| **5. Security** | 10 | SECURITY.md + Middleware + Demo | 2 min |
| **TOTAL** | **50** | All files present + working | ~10 min |

---

## ❓ COMMON QUESTIONS & ANSWERS

**Q: "What's the difference between Jetstream and Sanctum?"**
A: "Jetstream handles web logins (people clicking login button). Sanctum handles API authentication (apps getting tokens). Together they protect the whole system."

**Q: "Why use Livewire?"**
A: "Without Livewire, clicking '+' in cart would refresh the page. With Livewire, it updates instantly without any JavaScript code or page refresh."

**Q: "How is the database secure?"**
A: "Passwords are hashed with bcrypt - even I can't see them. Eloquent ORM prevents SQL injection. Middleware prevents unauthorized access."

**Q: "Can someone steal a Sanctum token?"**
A: "If they steal a token, I can revoke it immediately. Plus tokens expire (can set expiration time). That's why HTTPS is needed in production."

**Q: "Show me an example of SQL injection prevention?"**
A: "With Eloquent, the input is automatically escaped. If someone enters `'; DROP TABLE users; --`, Eloquent treats it as a string value, not SQL code."

---

## 🎬 PRESENTATION FLOW (10 minutes)

**0-1 min:** "I built Hot Dish using 5 key Laravel features..."  
**1-3 min:** Show Livewire (file + demo)  
**3-5 min:** Show Eloquent (file + explain models)  
**5-6 min:** Show Jetstream (file + login demo)  
**6-8 min:** Show Sanctum (file + curl demo)  
**8-9 min:** Show Security (SECURITY.md + middleware)  
**9-10 min:** Answer questions  

---

## 🎁 Files to Have Open in VS Code

```
1. ASSIGNMENT_CRITERIA_GUIDE.md      (This guide)
2. app/Livewire/Cart.php              (Criterion 1)
3. app/Models/MenuItem.php            (Criterion 2)
4. config/jetstream.php               (Criterion 3)
5. routes/api.php                     (Criterion 4)
6. SECURITY.md                        (Criterion 5)
7. app/Http/Middleware/VerifyIsAdmin.php
```

**Also have open:**
- Browser at http://localhost:8000 (app running)
- Terminal with `php artisan serve` running
- Optional: Postman or curl commands ready

---

## ✅ FINAL CHECKLIST BEFORE PRESENTATION

- [ ] Hot Dish server running (`php artisan serve`)
- [ ] Files open in VS Code
- [ ] Browser open to localhost:8000
- [ ] Can navigate to admin page (login check works)
- [ ] Can add to cart and see Livewire update
- [ ] Terminal ready for curl/API tests
- [ ] This guide printed or on second screen
- [ ] SECURITY.md read and understood
- [ ] Can explain why each technology was chosen

---

## 🚀 GO CONFIDENTLY!

Your project has ALL 5 criteria implemented. Just explain each one clearly with the code as evidence. Your lecturer will be impressed! 🎉
