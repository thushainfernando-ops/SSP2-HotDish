# 🔌 External API Integration Guide

## Overview

Hot Dish integrates with **5 external APIs** to provide advanced features:

1. **Stripe** - Payment Processing
2. **Twilio** - SMS Notifications  
3. **Mailgun** - Email Notifications
4. **Google Maps** - Location Services
5. **Cloudinary** - Image Management

---

## 🔐 API SETUP & CREDENTIALS

### 1. STRIPE PAYMENT GATEWAY

**What it does:** Process credit card payments securely

**Get your keys:**
1. Go to https://dashboard.stripe.com/apikeys
2. Copy `Publishable Key` and `Secret Key`
3. Update `.env`:
```
STRIPE_PUBLIC_KEY=pk_test_xxxxx
STRIPE_SECRET_KEY=sk_test_xxxxx
```

**Testing:** Use test card `4242 4242 4242 4242` with any future expiry

---

### 2. TWILIO SMS

**What it does:** Send SMS notifications for orders and delivery updates

**Get your credentials:**
1. Sign up at https://www.twilio.com
2. Go to Console → Auth Token
3. Buy a phone number (Messages → Manage Numbers)
4. Update `.env`:
```
TWILIO_ACCOUNT_SID=ACxxxxx
TWILIO_AUTH_TOKEN=xxxxx
TWILIO_PHONE_NUMBER=+1234567890
```

**Example SMS:**
```
🍲 Your Hot Dish order #123 confirmed! Total: $25.50. 
Delivery updates coming soon.
```

---

### 3. MAILGUN EMAIL

**What it does:** Send order confirmations and receipt emails

**Get your credentials:**
1. Sign up at https://www.mailgun.com
2. Go to Domain Settings
3. Copy Domain Name and API Key
4. Update `.env`:
```
MAILGUN_DOMAIN=sandbox123456.mailgun.org
MAILGUN_SECRET=key-xxxxx
```

**Emails sent:**
- Order confirmation emails
- Receipt and payment confirmations
- Admin order notifications

---

### 4. GOOGLE MAPS API

**What it does:** Calculate delivery distance, time, and location services

**Get your API key:**
1. Go to https://console.cloud.google.com
2. Create new project
3. Enable: Distance Matrix API, Geocoding API, Maps JavaScript API
4. Create API Key (Credentials → Create Credentials)
5. Update `.env`:
```
GOOGLE_MAPS_API_KEY=AIzaSy_xxxxx
```

**Features:**
- Calculate delivery distance & time
- Geocode addresses to coordinates
- Reverse geocode coordinates to addresses

---

### 5. CLOUDINARY IMAGE STORAGE

**What it does:** Store and optimize menu item images

**Get your credentials:**
1. Sign up at https://cloudinary.com
2. Go to Dashboard → Settings
3. Copy Cloud Name, API Key, API Secret
4. Update `.env`:
```
CLOUDINARY_CLOUD_NAME=dxxxxx
CLOUDINARY_API_KEY=123456789
CLOUDINARY_API_SECRET=abcdefg_hijklmnop
```

**Image storage:**
- Menu item photos
- User profile pictures
- Marketing banners

---

## 📡 API ENDPOINTS

### Authentication (Public)

```bash
# Login to get API token
POST /api/login
{
  "email": "user@example.com",
  "password": "password123"
}

Response:
{
  "token": "hotdish_xxxxx",
  "user": { ... }
}
```

---

### Payment Endpoints (Protected)

#### Get Stripe Public Key
```bash
GET /api/payment/stripe-key

Response:
{
  "success": true,
  "public_key": "pk_test_xxxxx"
}
```

#### Process Payment
```bash
POST /api/payment/process
Authorization: Bearer <token>
{
  "order_id": 123,
  "stripe_token": "tok_xxxxx",
  "amount": 25.50
}

Response:
{
  "success": true,
  "transaction_id": "ch_xxxxx",
  "payment_id": 456
}
```

#### Create Payment Intent
```bash
POST /api/payment/create-intent
Authorization: Bearer <token>
{
  "order_id": 123,
  "amount": 25.50
}

Response:
{
  "success": true,
  "client_secret": "pi_xxxxx_secret_yyyyy",
  "intent_id": "pi_xxxxx"
}
```

#### Get Payment Status
```bash
GET /api/payment/{payment_id}
Authorization: Bearer <token>

Response:
{
  "success": true,
  "status": "Completed",
  "amount": 25.50,
  "transaction_id": "ch_xxxxx"
}
```

---

### Location Endpoints (Public)

#### Calculate Delivery Distance & Time
```bash
POST /api/location/calculate-delivery
{
  "origin": "Restaurant Address or 40.7128,-74.0060",
  "destination": "Customer Address or 40.7580,-73.9855"
}

Response:
{
  "success": true,
  "distance_km": 2.5,
  "distance_text": "2.5 km",
  "duration_text": "10 mins",
  "estimated_delivery": {
    "prep_time": 2,
    "delivery_time": 5,
    "total_time": 7,
    "estimated_arrival": "14:30 PM"
  }
}
```

#### Geocode Address
```bash
POST /api/location/geocode
{
  "address": "123 Main Street, New York, NY 10001"
}

Response:
{
  "success": true,
  "latitude": 40.7128,
  "longitude": -74.0060,
  "formatted_address": "123 Main Street, New York, NY 10001, USA"
}
```

#### Reverse Geocode
```bash
POST /api/location/reverse-geocode
{
  "latitude": 40.7128,
  "longitude": -74.0060
}

Response:
{
  "success": true,
  "formatted_address": "123 Main Street, New York, NY 10001, USA",
  "address_components": [...]
}
```

---

### Image Endpoints (Protected)

#### Upload Image to Cloudinary
```bash
POST /api/image/upload
Authorization: Bearer <token>
Content-Type: multipart/form-data

Form Data:
- image: <file>
- folder: "menu" (optional)
- public_id: "menu-burger" (optional)

Response:
{
  "success": true,
  "public_id": "hotdish/menu/menu-burger",
  "url": "https://res.cloudinary.com/xxx/image/upload/xxx.jpg",
  "width": 400,
  "height": 300
}
```

#### Get Optimized Image URL
```bash
POST /api/image/get-url
Authorization: Bearer <token>
{
  "public_id": "hotdish/menu/menu-burger",
  "width": 400,
  "height": 300
}

Response:
{
  "success": true,
  "url": "https://res.cloudinary.com/xxx/image/upload/w_400,h_300/xxx.jpg"
}
```

#### Get Image Metadata
```bash
POST /api/image/metadata
Authorization: Bearer <token>
{
  "public_id": "hotdish/menu/menu-burger"
}

Response:
{
  "success": true,
  "metadata": {
    "public_id": "hotdish/menu/menu-burger",
    "format": "jpg",
    "width": 800,
    "height": 600,
    "bytes": 45123,
    "url": "https://res.cloudinary.com/xxx/xxx.jpg"
  }
}
```

#### Delete Image
```bash
DELETE /api/image/hotdish/menu/menu-burger
Authorization: Bearer <token>

Response:
{
  "success": true,
  "message": "Image deleted successfully"
}
```

---

## 💻 USAGE EXAMPLES IN CODE

### Using Stripe Service

```php
use App\Services\StripePaymentService;

$stripeService = new StripePaymentService();

// Process payment
$payment = $stripeService->processPayment(
    2550,  // $25.50 in cents
    'tok_visa',
    'Order #123',
    ['order_id' => 123]
);

if ($payment['success']) {
    echo "Payment ID: " . $payment['transaction_id'];
}
```

### Using Twilio SMS Service

```php
use App\Services\TwilioSmsService;

$smsService = new TwilioSmsService();

// Send order confirmation
$smsService->sendOrderConfirmation(
    '+1234567890',
    '123',
    '25.50'
);

// Send delivery update
$smsService->sendDeliveryUpdate(
    '+1234567890',
    'out for delivery',
    '14:30 PM'
);
```

### Using Mailgun Email Service

```php
use App\Services\MailgunEmailService;

$emailService = new MailgunEmailService();

// Send order confirmation
$emailService->sendOrderConfirmation(
    'customer@example.com',
    'John Doe',
    [
        'order_id' => 123,
        'total' => 25.50
    ]
);

// Send receipt
$emailService->sendOrderReceipt(
    'customer@example.com',
    [
        'order_id' => 123,
        'date' => '2026-05-28',
        'total' => 25.50
    ]
);
```

### Using Google Maps Service

```php
use App\Services\GoogleMapsService;

$mapsService = new GoogleMapsService();

// Calculate distance
$distance = $mapsService->calculateDistance(
    'Restaurant Address',
    'Customer Address'
);

// Geocode address
$location = $mapsService->geocodeAddress('123 Main St, NYC');

// Estimate delivery
$delivery = $mapsService->estimateDeliveryTime(2.5); // 2.5 km
```

### Using Cloudinary Image Service

```php
use App\Services\CloudinaryImageService;

$imageService = new CloudinaryImageService();

// Upload image
$upload = $imageService->uploadImage(
    request()->file('image'),
    'hotdish/menu'
);

// Get optimized URL
$url = $imageService->getOptimizedUrl(
    'hotdish/menu/burger',
    400,
    300
);

// Get thumbnail
$thumb = $imageService->getThumbnailUrl('hotdish/menu/burger');
```

---

## 🔒 SECURITY BEST PRACTICES

1. **Never commit `.env` file** - Always use environment variables
2. **Use test keys in development** - Stripe, Mailgun, Twilio have test modes
3. **Rotate API keys regularly** - Change keys every 3-6 months
4. **Restrict API key permissions** - Only allow required scopes
5. **Use HTTPS only** - Encrypt all API communications
6. **Log API calls** - Monitor usage and errors in logs
7. **Handle errors gracefully** - Never expose API errors to users

---

## 📊 MONITORING & LOGGING

All API calls are logged to `storage/logs/laravel.log`:

```
[2026-05-28 14:30:00] laravel.INFO: Stripe Payment Successful  
    charge_id: ch_1234567890
    amount: 2550
    
[2026-05-28 14:31:00] laravel.INFO: SMS Sent Successfully
    message_id: SM1234567890
    to: +1234567890
    status: queued
```

Check logs in real-time:
```bash
tail -f storage/logs/laravel.log
```

---

## 🆘 TROUBLESHOOTING

### Stripe payments not working
- ✓ Check `STRIPE_SECRET_KEY` is correct
- ✓ Use test card: 4242 4242 4242 4242
- ✓ Check webhook configuration

### SMS not sending
- ✓ Verify `TWILIO_PHONE_NUMBER` format (+1234567890)
- ✓ Check phone number has international format
- ✓ Verify account has SMS credits

### Emails not received
- ✓ Check `MAILGUN_DOMAIN` is verified
- ✓ Check spam/junk folder
- ✓ Verify `MAIL_FROM_ADDRESS` matches domain

### Maps API errors
- ✓ Enable Distance Matrix API in Google Cloud Console
- ✓ Check API key restrictions
- ✓ Verify billing enabled

### Images not uploading
- ✓ Check `CLOUDINARY_API_KEY` and `CLOUDINARY_API_SECRET`
- ✓ Verify file size < 5MB
- ✓ Check cloud storage quota

---

## 📞 SUPPORT

For API-specific issues:
- **Stripe:** https://support.stripe.com
- **Twilio:** https://www.twilio.com/help/contact
- **Mailgun:** https://mailgun.com/support
- **Google Maps:** https://developers.google.com/maps/support
- **Cloudinary:** https://support.cloudinary.com

---

**Last Updated:** May 28, 2026
**Version:** 1.0
