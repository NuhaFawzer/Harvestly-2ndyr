# Harvestly Buyer - MVC + MySQL CRUD

Pure PHP, CSS and JavaScript Buyer module for Harvestly.

## Run

1. Copy the `Harvestly` folder to `C:\xampp\htdocs\Harvestly`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/Harvestly/`.
4. The application creates the `harvestly` database and required tables automatically on first database connection.

Demo buyer:

- Email: `demo@harvestly.local`
- Password: `123456`

## Buyer pages

- Landing
- Login
- Registration
- Forgot Password
- Dashboard
- Browse Products
- Product Details
- Cart
- Checkout
- Orders
- Order Tracking
- Notifications
- Profile
- Feedback / Complaints

## Main flows

Landing -> Login/Register -> Dashboard -> Products -> Product Details -> Cart -> Checkout -> Orders -> Tracking -> Feedback

## Database CRUD

- Users / buyer profile: Create, Read, Update, Delete
- Products: Create, Read, Update, Delete model/controller endpoints
- Cart: Add, Read, Update quantity, Remove, Clear
- Orders: Create, Read, Update status, Cancel, Delete cancelled orders
- Notifications: Create, Read, Update, Mark Read, Delete
- Feedback: Create, Read, Update, Delete
- Complaints: Create, Read, Update, Delete

## File organization

All Buyer CSS is inside `css/Buyer/` and all Buyer JavaScript is inside `js/Buyer/`.
No duplicate `browse-products-branded.css` or unused `browse-products.js` copies are kept.
The singular `NotificationController.php` is intentionally kept as a compatibility entry point for the older URL shown in testing; it forwards to `NotificationsController.php`.

## Important

The app uses MySQL through PDO. Make sure the PDO MySQL extension is enabled in the PHP installation used by XAMPP.
