<?php
/**
 * Harvestly Master Entry Point & MVC Front Controller
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/auth/controllers/AuthController.php';
require_once __DIR__ . '/admin/controllers/AdminController.php';
require_once __DIR__ . '/farmer/controllers/FarmerProductController.php';

$action = $_GET['action'] ?? null;
$adminAction = $_GET['admin_action'] ?? null;
$page = $_GET['page'] ?? 'landing';

$farmerPages = [
    'farmer_dashboard' => 'dashboard.php',
    'farmer_products' => 'products.php',
    'farmer_add_product' => 'add-product.php',
    'farmer_edit_product' => 'edit-product.php',
    'farmer_inventory' => 'inventory.php',
    'farmer_orders' => 'orders.php',
    'farmer_order_details' => 'order-details.php',
    'farmer_product_details' => 'product-details.php',
    'farmer_harvest_soon' => 'harvest-soon.php',
    'farmer_sales' => 'sales.php',
    'farmer_earnings' => 'earnings.php',
    'farmer_reviews' => 'reviews.php',
    'farmer_report_issue' => 'report-issue.php',
    'farmer_notifications' => 'notifications.php',
    'farmer_profile' => 'profile.php',
];

// 1. Handle Auth & Farmer POST Actions
if ($action) {
    if (strpos($action, 'farmer_') === 0) {
        $farmerCtrl = new FarmerProductController();
        $farmerCtrl->handleAction();
        exit();
    }
    
    $authCtrl = new AuthController();
    switch ($action) {
        case 'login':
            $authCtrl->handleLogin();
            break;
        case 'signup_buyer':
            $authCtrl->handleBuyerSignup();
            break;
        case 'signup_farmer':
            $authCtrl->handleFarmerSignup();
            break;
        case 'signup_courier':
            $authCtrl->handleCourierSignup();
            break;
        case 'request_password_reset':
            $authCtrl->handlePasswordResetRequest();
            break;
        case 'reset_password':
            $authCtrl->handlePasswordReset();
            break;
        case 'logout':
            $authCtrl->handleLogout();
            break;
    }
    exit();
}

// 2. Handle Admin POST Actions
if ($adminAction) {
    $adminCtrl = new AdminController();
    $adminCtrl->handleAdminAction();
    exit();
}

// Farmer pages provide their own authenticated layout and must render outside
// the public header/footer wrapper.
if (isset($farmerPages[$page])) {
    require __DIR__ . '/View/Farmer/' . $farmerPages[$page];
    exit();
}

// 3. Render Page Views
require_once __DIR__ . '/includes/header.php';

$isAdminPage = (strpos($page, 'admin_') === 0);

if ($isAdminPage) {
    echo '<div class="app-container">';
    require_once __DIR__ . '/includes/sidebar.php';
    echo '<div class="main-content">';
    $adminCtrl = new AdminController();
    $adminCtrl->renderView($page);
    echo '</div>'; // close main-content
    echo '</div>'; // close app-container
} else {
    echo '<div class="main-content" style="padding-top: 80px;">';
    switch ($page) {
        case 'login':
            require_once __DIR__ . '/auth/views/login.php';
            break;
        case 'forgot_password':
            require_once __DIR__ . '/auth/views/forgot_password.php';
            break;
        case 'reset_password':
            require_once __DIR__ . '/auth/views/reset_password.php';
            break;
        case 'role_select':
            require_once __DIR__ . '/auth/views/role_select.php';
            break;
        case 'signup_buyer':
            require_once __DIR__ . '/auth/views/signup_buyer.php';
            break;
        case 'signup_farmer':
            require_once __DIR__ . '/auth/views/signup_farmer.php';
            break;
        case 'signup_courier':
            require_once __DIR__ . '/auth/views/signup_courier.php';
            break;
        case 'pending_approval':
            require_once __DIR__ . '/auth/views/pending_approval.php';
            break;
        case 'farmer_products':
            require_once __DIR__ . '/farmer/views/products.php';
            break;
        case 'farmer_add_product':
            require_once __DIR__ . '/farmer/views/add_product.php';
            break;
        case 'farmer_edit_product':
            require_once __DIR__ . '/farmer/views/edit_product.php';
            break;
        case 'farmer_dashboard':
            require_once __DIR__ . '/farmer/views/dashboard.php';
            break;
        case 'products':
            renderProductsPage();
            break;
        case 'product_details':
            renderProductDetailsPage();
            break;
        case 'landing':
        default:
            renderLandingPage();
            break;
    }
    echo '</div>';
}

require_once __DIR__ . '/includes/footer.php';

/**
 * Render Public Landing Page (Main Interface)
 */
function renderLandingPage() {
    ?>
    <main class="landing-page flex-1 flex flex-col relative w-full bg-surface">
        <!-- Hero Section -->
        <section class="landing-hero" style="background-color: var(--color-background); padding: 60px 24px 80px; overflow: hidden;">
            <div style="max-width: 1280px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 48px; align-items: center;">
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div style="display: inline-flex; align-items: center; gap: 8px; align-self: flex-start; padding: 6px 16px; border-radius: 9999px; background-color: var(--color-secondary-container); color: var(--color-on-secondary-container); font-size: 13px; font-weight: 700;">
                        🌱 Island-wide Direct Farm-to-Table Marketplace
                    </div>
                    <h1 style="font-size: 46px; font-weight: 800; line-height: 1.15; color: var(--color-on-surface); letter-spacing: -0.02em;">
                        Connecting Sri Lankan Farmers <span style="color: var(--color-primary); display: block;">Directly to Your Doorstep</span>
                    </h1>
                    <p style="font-size: 18px; color: var(--color-on-surface-variant); line-height: 1.6; max-width: 540px;">
                        Fresh organic harvest sourced straight from local growers across Nuwara Eliya, Dambulla, Matale, and Jaffna — delivered fast and fresh by verified logistics partners with zero middlemen.
                    </p>

                    <!-- Primary CTAs -->
                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <a href="index.php?page=products" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px;">Browse Products &rarr;</a>
                        <a href="index.php?page=role_select" class="btn btn-outline" style="padding: 14px 28px; font-size: 15px;">Join Harvestly</a>
                    </div>
                </div>

                <div class="landing-hero-media" style="position: relative;">
                    <div style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg); border: 4px solid #fff; background: var(--color-surface-container-high); position: relative; aspect-ratio: 4/3.5;">
                        <img src="assets/images/main image.jpg" alt="Fresh Sri Lankan Produce Harvest" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 60%);"></div>
                        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px; color: #fff; text-align: left; display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <span style="font-size: 13px; font-weight: 600; color: #cbffc2;">Direct from Upcountry</span>
                                <h3 style="font-size: 20px; font-weight: 800; color: #fff; margin-top: 2px;">Nuwara Eliya & Central Valleys</h3>
                            </div>
                            <span class="badge badge-success" style="padding: 6px 14px; font-size: 12px; backdrop-filter: blur(8px); background: rgba(255,255,255,0.95); color: var(--color-primary);">✓ Certified Farm</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Categories Section -->
        <section id="categories" style="background-color: #fff; padding: 60px 24px;">
            <div style="max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">
                <div style="text-align: center;">
                    <span style="font-size: 12px; font-weight: 800; color: var(--color-primary); letter-spacing: 1px; text-transform: uppercase;">AGRICULTURAL PRODUCE</span>
                    <h2 style="font-size: 32px; font-weight: 800; color: var(--color-on-surface); margin-top: 4px;">Explore Product Categories</h2>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <a href="index.php?page=products&category=Vegetables" style="text-decoration:none; background: var(--color-surface-container-low); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px; transition: transform 0.2s;">
                        <img src="assets/images/vegfr.jpg" alt="Vegetables" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Vegetables</h3>
                        <span style="font-size: 12px; color: var(--color-outline);">Highland & Lowland</span>
                    </a>

                    <a href="index.php?page=products&category=Fresh+Fruits" style="text-decoration:none; background: var(--color-surface-container-low); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px; transition: transform 0.2s;">
                        <img src="assets/images/fruits.jpg" alt="Fresh Fruits" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Fresh Fruits</h3>
                        <span style="font-size: 12px; color: var(--color-outline);">Seasonal Island Fruits</span>
                    </a>

                    <a href="index.php?page=products&category=Leafy+Greens" style="text-decoration:none; background: var(--color-surface-container-low); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px; transition: transform 0.2s;">
                        <img src="assets/images/leafy green.jpg" alt="Leafy Greens" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Leafy Greens</h3>
                        <span style="font-size: 12px; color: var(--color-outline);">Gotu Kola & Herbs</span>
                    </a>

                    <a href="index.php?page=products&category=Spices+%26+Staples" style="text-decoration:none; background: var(--color-surface-container-low); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px; transition: transform 0.2s;">
                        <img src="assets/images/spices.jpg" alt="Spices &amp; Staples" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Spices & Staples</h3>
                        <span style="font-size: 12px; color: var(--color-outline);">Ceylon Cinnamon & Spices</span>
                    </a>

                    <a href="index.php?page=products&category=Organic+Produce" style="text-decoration:none; background: var(--color-surface-container-low); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 12px; transition: transform 0.2s;">
                        <img src="assets/images/organic.jpg" alt="Organic Produce" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius-md);">
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Organic Produce</h3>
                        <span style="font-size: 12px; color: var(--color-outline);">Certified Organic</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Produce Grid -->
        <section id="featured-produce" style="background-color: var(--color-surface-container-low); padding: 80px 24px;">
            <div style="max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; border-bottom: 1px solid var(--color-outline-variant); padding-bottom: 16px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 800; color: var(--color-primary); letter-spacing: 1px; text-transform: uppercase;">DIRECT HARVEST</span>
                        <h2 style="font-size: 32px; font-weight: 800; color: var(--color-on-surface);">Fresh Today from Local Farms</h2>
                    </div>
                    <a href="index.php?page=products" class="btn btn-outline">View All Products &rarr;</a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
                    <!-- Product 1: Nuwara Eliya Crisp Carrots -->
                    <article style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm);">
                        <div style="height: 220px; overflow: hidden; position: relative; background: var(--color-surface-container);">
                            <img src="assets/images/Fresh Fruits &amp; Vegetables.jpg" alt="Nuwara Eliya Crisp Carrots" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-success" style="position: absolute; top: 12px; right: 12px;">Grade A</span>
                            <span class="badge badge-info" style="position: absolute; bottom: 12px; left: 12px;">Available Now</span>
                        </div>
                        <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Nuwara Eliya Crisp Carrots</h3>
                                <div style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 2px;">👨‍🌾 Sunil Perera (Nuwara Eliya)</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-outline-variant);">
                                <div>
                                    <span style="font-size: 22px; font-weight: 800; color: var(--color-primary);">Rs. 420</span>
                                    <span style="font-size: 12px; color: var(--color-outline);"> / kg</span>
                                </div>
                                <a href="index.php?page=product_details&id=1" class="btn btn-primary btn-sm">View Product</a>
                            </div>
                        </div>
                    </article>

                    <!-- Product 2: Dambulla Red Onions -->
                    <article style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm);">
                        <div style="height: 220px; overflow: hidden; position: relative; background: var(--color-surface-container);">
                            <img src="assets/images/Dambulla Red Onions.jpg" alt="Dambulla Red Onions" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-success" style="position: absolute; top: 12px; right: 12px;">Grade A</span>
                            <span class="badge badge-info" style="position: absolute; bottom: 12px; left: 12px;">Available Now</span>
                        </div>
                        <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Dambulla Red Onions</h3>
                                <div style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 2px;">👨‍🌾 Sunil Perera (Matale)</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-outline-variant);">
                                <div>
                                    <span style="font-size: 22px; font-weight: 800; color: var(--color-primary);">Rs. 380</span>
                                    <span style="font-size: 12px; color: var(--color-outline);"> / kg</span>
                                </div>
                                <a href="index.php?page=product_details&id=2" class="btn btn-primary btn-sm">View Product</a>
                            </div>
                        </div>
                    </article>

                    <!-- Product 3: Jaffna Karutha Colomban Mangoes -->
                    <article style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm);">
                        <div style="height: 220px; overflow: hidden; position: relative; background: var(--color-surface-container);">
                            <img src="assets/images/Jaffna Karutha Colomban.jpg" alt="Jaffna Karutha Colomban Mangoes" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-success" style="position: absolute; top: 12px; right: 12px;">Grade A</span>
                            <span class="badge badge-info" style="position: absolute; bottom: 12px; left: 12px;">Seasonal</span>
                        </div>
                        <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);">Jaffna Karutha Colomban</h3>
                                <div style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 2px;">👨‍🌾 Sunil Perera (Jaffna)</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-outline-variant);">
                                <div>
                                    <span style="font-size: 22px; font-weight: 800; color: var(--color-primary);">Rs. 650</span>
                                    <span style="font-size: 12px; color: var(--color-outline);"> / kg</span>
                                </div>
                                <a href="index.php?page=product_details&id=3" class="btn btn-primary btn-sm">View Product</a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="how-it-works-section">
            <div class="how-it-works-inner">
                <div class="how-it-works-intro">
                    <span class="section-eyebrow">HOW IT WORKS</span>
                    <h2>How Harvestly Works</h2>
                    <p>A simple way for Farmers and Buyers to connect, order, and receive fresh produce.</p>
                </div>

                <div class="how-it-works-steps">
                    <article class="how-it-works-step">
                        <div class="step-number">01</div>
                        <div class="step-icon">🌱</div>
                        <span class="step-role">Farmer & Buyer</span>
                        <h3>List &amp; Discover Products</h3>
                        <p>Farmers list fresh produce with price, availability, quality, and freshness details, while Buyers browse and find suitable products.</p>
                    </article>

                    <article class="how-it-works-step">
                        <div class="step-number">02</div>
                        <div class="step-icon">🚚</div>
                        <span class="step-role">Order & Delivery</span>
                        <h3>Order &amp; Deliver</h3>
                        <p>Buyers place orders and complete checkout, while approved Courier Partners handle delivery based on supported district routes.</p>
                    </article>

                    <article class="how-it-works-step">
                        <div class="step-number">03</div>
                        <div class="step-icon">✓</div>
                        <span class="step-role">Buyer Community</span>
                        <h3>Confirm &amp; Review</h3>
                        <p>After delivery, Buyers confirm receipt and can rate, review, or report issues, helping build trust across the Harvestly marketplace.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about-us" style="background-color: var(--color-surface-container-low); padding: 80px 24px;">
            <div style="max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">
                <div style="text-align: center; max-width: 800px; margin: 0 auto;">
                    <span style="font-size: 12px; font-weight: 800; color: var(--color-primary); letter-spacing: 1px; text-transform: uppercase;">ABOUT HARVESTLY</span>
                    <h2 style="font-size: 36px; font-weight: 800; color: var(--color-on-surface); margin-top: 4px;">Empowering Sri Lanka's Agricultural Ecosystem</h2>
                    <p style="font-size: 16px; color: var(--color-on-surface-variant); margin-top: 16px; line-height: 1.7;">
                        Harvestly is a specialized farmer-to-buyer marketplace connecting local Sri Lankan growers, household buyers, and verified Courier Partner organizations across all 25 districts. By eliminating predatory intermediary markups, Harvestly ensures farmers receive fair producer prices while buyers enjoy fresh agricultural produce straight from harvest.
                    </p>
                </div>
            </div>
        </section>
    </main>
    <?php
}

/**
 * Render Public Products Catalogue Page
 */
/**
 * Render Public Products Catalogue Page
 */
function renderProductsPage() {
    $db = getDBConnection();
    $catFilter = trim($_GET['category'] ?? 'all');
    $search = trim($_GET['search'] ?? '');

    $categoryOptions = [];
    $categoryResult = $db->query("SELECT category_name FROM product_categories WHERE is_active = 1 ORDER BY category_name ASC");
    if ($categoryResult) {
        while ($categoryRow = $categoryResult->fetch_assoc()) {
            $categoryOptions[] = $categoryRow['category_name'];
        }
    }

    $sql = "
        SELECT p.product_id as id, p.product_name as title, p.unit_price, p.unit_label, p.listing_type,
               p.declared_grade, c.category_name, u.full_name as farmer_name
        FROM products p
        JOIN product_categories c ON p.category_id = c.category_id
        JOIN users u ON p.farmer_id = u.user_id
        WHERE p.listing_status = 'ACTIVE'
    ";
    $params = [];
    $types = '';

    if ($catFilter !== 'all' && !empty($catFilter)) {
        if (in_array($catFilter, $categoryOptions, true)) {
            $sql .= " AND c.category_name = ?";
            $params[] = $catFilter;
            $types .= 's';
        } else {
            $catFilter = 'all';
        }
    }
    if (!empty($search)) {
        $sql .= " AND (p.product_name LIKE ? OR u.full_name LIKE ? OR c.category_name LIKE ?)";
        $searchPattern = '%' . $search . '%';
        $params[] = $searchPattern;
        $params[] = $searchPattern;
        $params[] = $searchPattern;
        $types .= 'sss';
    }

    $sql .= " ORDER BY p.created_at DESC";
    $stmt = $db->prepare($sql);
    if ($stmt && $params) {
        $stmt->bind_param($types, ...$params);
    }
    $result = $stmt ? ($stmt->execute() ? $stmt->get_result() : null) : null;
    $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    if ($stmt) {
        $stmt->close();
    }
    ?>
    <div class="page-content" style="max-width: 1280px; margin: 40px auto; padding: 0 24px;">
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 32px; font-weight: 800; color: var(--color-on-surface);">Public Produce Catalogue</h1>
            <p style="font-size: 15px; color: var(--color-on-surface-variant);">Browse fresh organic produce directly listed by local growers across Sri Lanka</p>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="index.php" style="background:#fff; border-radius: var(--radius-xl); padding: 16px; border: 1px solid var(--color-outline-variant); margin-bottom: 32px; display: flex; flex-wrap: wrap; gap: 16px;">
            <input type="hidden" name="page" value="products">

            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="search" class="form-control" placeholder="Search vegetables, fruits, spices..." value="<?= sanitize($search); ?>">
            </div>

            <div style="width: 200px;">
                <select name="category" class="form-control">
                    <option value="all">All Categories</option>
                    <?php foreach ($categoryOptions as $categoryName): ?>
                        <option value="<?= sanitize($categoryName); ?>" <?= ($catFilter === $categoryName) ? 'selected' : ''; ?>><?= sanitize($categoryName); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filter Produce</button>
            <a href="index.php?page=products" class="btn btn-outline">Reset</a>
        </form>

        <!-- Product Cards Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
            <?php if (empty($products)): ?>
                <div style="grid-column: 1 / -1; padding: 60px; text-align: center; color: var(--color-outline);">
                    No produce listings found matching your filter criteria.
                </div>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <article style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--color-outline-variant); overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm);">
                        <div style="height: 220px; overflow: hidden; position: relative; background: var(--color-surface-container);">
                            <img src="<?= getProductImage($p['title'], $p['category_name']); ?>" alt="<?= sanitize($p['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge badge-success" style="position: absolute; top: 12px; right: 12px;">Grade <?= sanitize($p['declared_grade'] ?? 'A'); ?></span>
                            <span class="badge badge-info" style="position: absolute; bottom: 12px; left: 12px;"><?= sanitize($p['listing_type']); ?></span>
                        </div>
                        <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: var(--color-on-surface);"><?= sanitize($p['title']); ?></h3>
                                <div style="font-size: 13px; color: var(--color-on-surface-variant); margin-top: 2px;">👨‍🌾 <?= sanitize($p['farmer_name']); ?></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-outline-variant);">
                                <div>
                                    <span style="font-size: 22px; font-weight: 800; color: var(--color-primary);">Rs. <?= number_format($p['unit_price'], 2); ?></span>
                                    <span style="font-size: 12px; color: var(--color-outline);"> / <?= sanitize($p['unit_label']); ?></span>
                                </div>
                                <a href="index.php?page=product_details&id=<?= $p['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Render Public Product Details Page
 */
function renderProductDetailsPage() {
    $db = getDBConnection();
    $id = intval($_GET['id'] ?? 1);

    $stmt = $db->prepare("
        SELECT p.product_id as id, p.product_name as title, p.unit_price, p.unit_label, p.listing_type,
               p.declared_grade, p.description, c.category_name, u.full_name as farmer_name
        FROM products p
        JOIN product_categories c ON p.category_id = c.category_id
        JOIN users u ON p.farmer_id = u.user_id
        WHERE p.product_id = ?
    ");
    $p = null;
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $p = $res ? $res->fetch_assoc() : null;
        $stmt->close();
    }

    if (!$p) {
        $p = [
            'id' => 1, 'title' => 'Nuwara Eliya Crisp Carrots', 'unit_price' => 420.00, 'unit_label' => 'kg',
            'listing_type' => 'AVAILABLE_NOW', 'declared_grade' => 'A', 'category_name' => 'Vegetables',
            'farmer_name' => 'Sunil Perera', 'description' => 'Fresh highland organic carrots harvested daily at Sunlight Highlands Farm in Moon Plains, Nuwara Eliya.'
        ];
    }
    ?>
    <div class="page-content" style="max-width: 1000px; margin: 40px auto; padding: 0 24px;">
        <a href="index.php?page=products" style="font-size: 13px; color: var(--color-outline); font-weight: 600; text-decoration: none;">&larr; Back to Catalogue</a>

        <div style="background: #fff; border-radius: var(--radius-xl); border: 1px solid var(--color-outline-variant); padding: 36px; margin-top: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 36px;">
            <div style="border-radius: var(--radius-lg); overflow: hidden; height: 320px; background: var(--color-surface-container);">
                <img src="<?= getProductImage($p['title'], $p['category_name']); ?>" alt="<?= sanitize($p['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <span class="badge badge-success">Grade <?= sanitize($p['declared_grade'] ?? 'A'); ?></span>
                    <span class="badge badge-info" style="margin-left: 6px;"><?= sanitize($p['listing_type']); ?></span>
                    <h1 style="font-size: 28px; font-weight: 800; color: var(--color-on-surface); margin-top: 8px;"><?= sanitize($p['title']); ?></h1>
                    <div style="font-size: 14px; color: var(--color-on-surface-variant); margin-top: 4px;">👨‍🌾 Listed by Grower <strong><?= sanitize($p['farmer_name']); ?></strong></div>
                </div>

                <div style="font-size: 32px; font-weight: 800; color: var(--color-primary);">
                    Rs. <?= number_format($p['unit_price'], 2); ?> <span style="font-size: 14px; color: var(--color-outline);">/ <?= sanitize($p['unit_label']); ?></span>
                </div>

                <p style="font-size: 14px; color: var(--color-on-surface-variant); line-height: 1.6;">
                    <?= sanitize($p['description'] ?? 'Fresh farm produce.'); ?>
                </p>

                <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--color-outline-variant);">
                    <?php if (isset($_SESSION['user_id']) && strtolower((string)($_SESSION['role'] ?? '')) === 'buyer'): ?>
                        <a href="Controller/Buyer/ProductController.php?action=add_to_cart&id=<?= (int)$p['id']; ?>&qty=1" class="btn btn-primary" style="width: 100%; padding: 14px; text-align: center;">Add to Cart &amp; Checkout</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-primary" style="width: 100%; padding: 14px; text-align: center;">Sign In as Buyer to Place Order</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

