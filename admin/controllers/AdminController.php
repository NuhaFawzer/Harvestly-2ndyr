<?php
/**
 * AdminController - Handles Admin Actions & Page Rendering
 */

require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../../includes/functions.php';

class AdminController {
    private $model;

    public function __construct() {
        checkAdminAuth();
        $this->model = new AdminModel();
    }

    /**
     * Dispatch POST actions for Admin operations
     */
    public function handleAdminAction() {
        $action = $_GET['admin_action'] ?? '';

        switch ($action) {
            /* --- PRODUCT CATEGORIES (ADMIN MAIN CRUD) --- */
            case 'create_category':
                verifyCsrfToken();
                $name = sanitize($_POST['category_name'] ?? '');
                $desc = sanitize($_POST['description'] ?? '');
                $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
                if (!empty($name)) {
                    $ok = $this->model->createCategory($name, $desc, $status);
                    header("Location: index.php?page=admin_categories&" . ($ok ? 'success=Product+category+created+successfully.' : 'error=Unable+to+create+that+category.'));
                } else {
                    header("Location: index.php?page=admin_categories&error=Category+name+is+required.");
                }
                exit();

            case 'update_category':
                verifyCsrfToken();
                $id = intval($_POST['category_id'] ?? 0);
                $name = sanitize($_POST['category_name'] ?? '');
                $desc = sanitize($_POST['description'] ?? '');
                $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
                if ($id > 0 && !empty($name)) {
                    $ok = $this->model->updateCategory($id, $name, $desc, $status);
                    header("Location: index.php?page=admin_categories&" . ($ok ? 'success=Product+category+updated+successfully.' : 'error=Unable+to+update+that+category.'));
                } else {
                    header("Location: index.php?page=admin_categories&error=Failed+to+update+category.");
                }
                exit();

            case 'deactivate_category':
                verifyCsrfToken();
                $id = intval($_POST['category_id'] ?? 0);
                if ($id > 0) {
                    $ok = $this->model->deactivateCategory($id);
                    header("Location: index.php?page=admin_categories&" . ($ok ? 'success=Category+deactivated.' : 'error=Unable+to+deactivate+that+category.'));
                }
                exit();

            case 'delete_category':
                verifyCsrfToken();
                $id = intval($_POST['category_id'] ?? 0);
                if ($id > 0) {
                    $ok = $this->model->deleteCategory($id);
                    header("Location: index.php?page=admin_categories&" . ($ok ? 'success=Category+deleted.' : 'error=Category+could+not+be+deleted+because+it+is+in+use.'));
                }
                exit();

            /* --- USER MANAGEMENT --- */
            case 'create_user':
                $role = sanitize($_POST['role'] ?? 'buyer');
                $data = [
                    'name' => sanitize($_POST['name'] ?? ''),
                    'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
                    'password' => $_POST['password'] ?? 'user123',
                    'phone' => sanitize($_POST['phone'] ?? ''),
                    'status' => sanitize($_POST['status'] ?? 'active')
                ];
                if ($data['email'] && !empty($data['name'])) {
                    $this->model->createUser($role, $data);
                    header("Location: index.php?page=admin_users&success=New+user+account+created+successfully.");
                } else {
                    header("Location: index.php?page=admin_users&error=Failed+to+create+user.+Invalid+input.");
                }
                exit();

            case 'update_user_details':
                $role = sanitize($_POST['role'] ?? '');
                $userId = intval($_POST['user_id'] ?? 0);
                $data = [
                    'name' => sanitize($_POST['name'] ?? ''),
                    'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
                    'phone' => sanitize($_POST['phone'] ?? ''),
                    'status' => sanitize($_POST['status'] ?? 'active')
                ];
                if ($userId > 0 && $data['email']) {
                    $this->model->updateUserDetails($role, $userId, $data);
                    header("Location: index.php?page=admin_users&success=User+details+updated+successfully.");
                } else {
                    header("Location: index.php?page=admin_users&error=Failed+to+update+user.");
                }
                exit();

            case 'delete_user':
                $role = sanitize($_POST['role'] ?? '');
                $userId = intval($_POST['user_id'] ?? 0);
                if ($userId > 0) {
                    $this->model->deleteUser($role, $userId);
                    header("Location: index.php?page=admin_users&success=User+deleted+successfully.");
                }
                exit();

            case 'update_user_status':
                $userType = $_POST['user_type'] ?? '';
                $userId = intval($_POST['user_id'] ?? 0);
                $status = $_POST['status'] ?? '';
                $this->model->updateUserStatus($userType, $userId, $status);
                header("Location: index.php?page=admin_users&success=User+status+updated.");
                exit();

            /* --- APPROVALS --- */
            case 'verify_farmer':
                $farmerId = intval($_POST['farmer_id'] ?? 0);
                $status = $_POST['status'] ?? 'approved';
                $reason = $_POST['rejection_reason'] ?? null;
                $this->model->updateFarmerVerification($farmerId, $status, $reason);
                header("Location: index.php?page=admin_farmer_approvals&success=Farmer+verification+updated.");
                exit();

            case 'verify_courier':
                $courierId = intval($_POST['courier_id'] ?? 0);
                $status = $_POST['status'] ?? 'approved';
                $reason = $_POST['rejection_reason'] ?? null;
                $this->model->updateCourierVerification($courierId, $status, $reason);
                header("Location: index.php?page=admin_courier_approvals&success=Courier+verification+updated.");
                exit();

            /* --- LISTINGS & ORDERS --- */
            case 'update_product_status':
                $productId = intval($_POST['product_id'] ?? 0);
                $status = $_POST['status'] ?? 'active';
                $this->model->updateProductStatus($productId, $status);
                header("Location: index.php?page=admin_listings&success=Product+status+updated.");
                exit();

            case 'override_delivery':
                $orderId = intval($_POST['order_id'] ?? 0);
                $courierId = intval($_POST['courier_id'] ?? 0);
                $this->model->overrideDeliveryAssignment($orderId, $courierId);
                header("Location: index.php?page=admin_pending_assignments&success=Courier+assignment+overridden.");
                exit();

            /* --- DISTRICT DISTANCES --- */
            case 'add_district_distance':
                $from = sanitize($_POST['from_district'] ?? '');
                $to = sanitize($_POST['to_district'] ?? '');
                $km = floatval($_POST['distance_km'] ?? 0);
                $this->model->createDistrictDistance($from, $to, $km);
                header("Location: index.php?page=admin_district_distances&success=District+distance+pair+added.");
                exit();

            case 'update_district_distance':
                $id = intval($_POST['distance_id'] ?? 0);
                $from = sanitize($_POST['from_district'] ?? '');
                $to = sanitize($_POST['to_district'] ?? '');
                $km = floatval($_POST['distance_km'] ?? 0);
                $this->model->updateDistrictDistance($id, $from, $to, $km);
                header("Location: index.php?page=admin_district_distances&success=District+distance+updated.");
                exit();

            /* --- COMPLAINTS, NOTIFICATIONS, SETTINGS & PROFILE --- */
            case 'resolve_complaint':
                $complaintId = intval($_POST['complaint_id'] ?? 0);
                $status = $_POST['status'] ?? 'resolved';
                $notes = $_POST['resolution_notes'] ?? '';
                $this->model->resolveComplaint($complaintId, $status, $notes);
                header("Location: index.php?page=admin_complaints&success=Complaint+resolved.");
                exit();

            case 'send_notification':
                $scope = $_POST['recipient_scope'] ?? 'all';
                $recId = !empty($_POST['recipient_id']) ? intval($_POST['recipient_id']) : null;
                $subject = sanitize($_POST['subject'] ?? '');
                $message = sanitize($_POST['message'] ?? '');
                $this->model->createNotification($scope, $recId, $subject, $message);
                header("Location: index.php?page=admin_notifications&success=Notification+dispatched.");
                exit();

            case 'update_settings':
                $settings = [
                    'commission_rate' => sanitize($_POST['commission_rate'] ?? '12.0'),
                    'delivery_base_fee' => sanitize($_POST['delivery_base_fee'] ?? '0.00'),
                    'delivery_per_km_rate' => sanitize($_POST['delivery_per_km_rate'] ?? '0.00'),
                ];
                $this->model->updatePlatformSettings($settings);
                header("Location: index.php?page=admin_settings&success=Platform+settings+saved.");
                exit();

            case 'update_admin_profile':
                $adminId = $_SESSION['user_id'] ?? 1;
                $name = sanitize($_POST['full_name'] ?? '');
                $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
                $pass = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
                if ($email && $name) {
                    $this->model->updateAdminProfile($adminId, $name, $email, $pass);
                    $_SESSION['user_name'] = $name;
                    header("Location: index.php?page=admin_profile&success=Profile+updated+successfully.");
                } else {
                    header("Location: index.php?page=admin_profile&error=Invalid+profile+input.");
                }
                exit();
        }
    }

    /**
     * Render Admin Dashboard Views
     */
    public function renderView($viewName) {
        switch ($viewName) {
            case 'admin_overview':
                $kpis = $this->model->getOverviewKPIs();
                $activities = $this->model->getRecentActivityLog();
                require __DIR__ . '/../views/overview.php';
                break;

            case 'admin_users':
                $role = $_GET['role'] ?? 'all';
                $status = $_GET['status'] ?? 'all';
                $users = $this->model->getAllUsers($role, $status);
                require __DIR__ . '/../views/users.php';
                break;

            case 'admin_farmer_approvals':
                $pendingFarmers = $this->model->getPendingFarmers();
                require __DIR__ . '/../views/farmer_approvals.php';
                break;

            case 'admin_courier_approvals':
                $pendingCouriers = $this->model->getPendingCouriers();
                require __DIR__ . '/../views/courier_approvals.php';
                break;

            case 'admin_verifications': // Legacy wrapper
                $pendingFarmers = $this->model->getPendingFarmers();
                $pendingCouriers = $this->model->getPendingCouriers();
                require __DIR__ . '/../views/verifications.php';
                break;

            case 'admin_listings':
                $products = $this->model->getAllProducts();
                require __DIR__ . '/../views/listings.php';
                break;

            case 'admin_categories': // Admin CRUD Feature
                $search = $_GET['search'] ?? '';
                $status = $_GET['status'] ?? 'all';
                $categories = $this->model->getAllCategories($search, $status);
                require __DIR__ . '/../views/categories.php';
                break;

            case 'admin_orders':
                $orders = $this->model->getAllOrders();
                $couriers = $this->model->getAllUsers('courier', 'approved');
                require __DIR__ . '/../views/orders.php';
                break;

            case 'admin_deliveries':
                $deliveries = $this->model->getAllDeliveries();
                require __DIR__ . '/../views/deliveries.php';
                break;

            case 'admin_pending_assignments':
                $pendingOrders = $this->model->getPendingAssignments();
                $couriers = $this->model->getAllUsers('courier', 'approved');
                require __DIR__ . '/../views/pending_assignments.php';
                break;

            case 'admin_district_distances':
                $fromDistrict = sanitize($_GET['from_district'] ?? '');
                $toDistrict = sanitize($_GET['to_district'] ?? '');
                $distances = $this->model->getAllDistrictDistances($fromDistrict, $toDistrict);
                require __DIR__ . '/../views/district_distances.php';
                break;

            case 'admin_regions_hubs': // Legacy wrapper
                $districts = [];
                $tiers = [];
                $hubs = [];
                $coverage = [];
                require __DIR__ . '/../views/regions_hubs.php';
                break;

            case 'admin_complaints':
                $complaints = $this->model->getAllComplaints();
                require __DIR__ . '/../views/complaints.php';
                break;

            case 'admin_payments':
                $payments = $this->model->getAllPayments();
                require __DIR__ . '/../views/payments.php';
                break;

            case 'admin_settlements':
                $settlements = $this->model->getSettlements();
                require __DIR__ . '/../views/settlements.php';
                break;

            case 'admin_notifications':
                $logs = $this->model->getNotificationsLog();
                require __DIR__ . '/../views/notifications.php';
                break;

            case 'admin_settings':
                $settings = $this->model->getPlatformSettings();
                require __DIR__ . '/../views/settings.php';
                break;

            case 'admin_reports':
                $reportType = $_GET['type'] ?? 'orders';
                $startDate = $_GET['start'] ?? date('Y-m-01');
                $endDate = $_GET['end'] ?? date('Y-m-d');
                $reportData = $this->model->generateReportData($reportType, $startDate, $endDate);
                require __DIR__ . '/../views/reports.php';
                break;

            case 'admin_profile':
                $adminProfile = $this->model->getAdminProfile($_SESSION['user_id'] ?? 1);
                require __DIR__ . '/../views/profile.php';
                break;

            default:
                header("Location: index.php?page=admin_overview");
                exit();
        }
    }
}
