<?php
/**
 * AuthController - Handles Login, Signup requests, validation, and session routing
 */

require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/../../includes/functions.php';

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new AuthModel();
    }

    /**
     * Handle Login Submission
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=login");
            exit();
        }

        verifyCsrfToken();
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            header("Location: index.php?page=login&error=Please+enter+valid+email+and+password.");
            exit();
        }

        $user = $this->model->findUserByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            header("Location: index.php?page=login&error=Invalid+email+or+password.");
            exit();
        }

        // Check account approval/activation status
        if ($user['status'] === 'pending') {
            header("Location: index.php?page=pending_approval&message=Your+account+is+currently+pending+Admin+verification.");
            exit();
        }

        if ($user['status'] === 'suspended' || $user['status'] === 'rejected') {
            header("Location: index.php?page=login&error=Account+is+{$user['status']}.+Please+contact+support.");
            exit();
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'admin':
                header("Location: index.php?page=admin_overview");
                break;
            case 'buyer':
                header("Location: Controller/Buyer/DashboardController.php");
                break;
            case 'farmer':
                header("Location: index.php?page=farmer_dashboard");
                break;
            case 'courier':
                header("Location: View/Delivey/index.php?page=dashboard");
                break;
            default:
                header("Location: index.php");
                break;
        }
        exit();
    }

    /**
     * Handle Buyer Signup
     */
    public function handleBuyerSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=signup_buyer");
            exit();
        }

        verifyCsrfToken();
        $fullName = sanitize($_POST['full_name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $phone = sanitize($_POST['phone'] ?? '');
        $province = sanitize($_POST['province'] ?? '');
        $district = sanitize($_POST['district'] ?? '');
        $address = sanitize($_POST['address'] ?? '');

        if (!$fullName || !$email || strlen($password) < 8 || $password !== ($_POST['confirm_password'] ?? '') || !$phone || !$district || !$address) {
            header("Location: index.php?page=signup_buyer&error=All+fields+are+required.");
            exit();
        }

        if ($this->model->findUserByEmail($email)) {
            header("Location: index.php?page=signup_buyer&error=Email+address+is+already+registered.");
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $success = $this->model->registerBuyer($fullName, $email, $passwordHash, $phone, $province, $district, $address);

        if ($success) {
            header("Location: index.php?page=login&success=Account+created+successfully.+Please+login.");
        } else {
            header("Location: index.php?page=signup_buyer&error=Failed+to+create+account.");
        }
        exit();
    }

    /**
     * Handle Farmer Signup (With ID Document Upload)
     */
    public function handleFarmerSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=signup_farmer");
            exit();
        }

        verifyCsrfToken();
        $fullName = sanitize($_POST['full_name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $phone = sanitize($_POST['phone'] ?? '');
        $nicNumber = sanitize($_POST['nic_number'] ?? '');
        $farmAddress = sanitize($_POST['farm_address'] ?? '');
        $district = sanitize($_POST['district'] ?? '');

        if (!$fullName || !$email || strlen($password) < 8 || $password !== ($_POST['confirm_password'] ?? '') || !$phone || !$nicNumber || !$district || !$farmAddress) {
            header("Location: index.php?page=signup_farmer&error=All+fields+are+required.");
            exit();
        }

        if ($this->model->findUserByEmail($email)) {
            header("Location: index.php?page=signup_farmer&error=Email+address+is+already+registered.");
            exit();
        }

        try {
            $idDocumentPath = handleFileUpload($_FILES['id_document'] ?? []);
        } catch (Exception $e) {
            header("Location: index.php?page=signup_farmer&error=" . urlencode($e->getMessage()));
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $success = $this->model->registerFarmer($fullName, $email, $passwordHash, $phone, $nicNumber, $farmAddress, $district, $idDocumentPath);

        if ($success) {
            header("Location: index.php?page=pending_approval&message=Farmer+application+submitted+successfully.+Your+account+is+under+Admin+review.");
        } else {
            header("Location: index.php?page=signup_farmer&error=Failed+to+submit+registration.");
        }
        exit();
    }

    /**
     * Handle Courier Partner Company Signup (With Optional Verification Document Upload)
     */
    public function handleCourierSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=signup_courier");
            exit();
        }

        verifyCsrfToken();
        $companyName = sanitize($_POST['company_name'] ?? '');
        $contactPerson = sanitize($_POST['contact_person'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $phone = sanitize($_POST['phone'] ?? '');
        $businessAddress = sanitize($_POST['business_address'] ?? '');
        $district = sanitize($_POST['district'] ?? '');

        if (!$companyName || !$contactPerson || !$email || strlen($password) < 8 || $password !== ($_POST['confirm_password'] ?? '') || !$phone || !$businessAddress || !$district) {
            header("Location: index.php?page=signup_courier&error=All+company+fields+are+required.");
            exit();
        }

        if ($this->model->findUserByEmail($email)) {
            header("Location: index.php?page=signup_courier&error=Email+address+is+already+registered.");
            exit();
        }

        $verificationDocumentPath = null;
        if (isset($_FILES['verification_document']) && $_FILES['verification_document']['error'] === UPLOAD_ERR_OK) {
            try {
            $verificationDocumentPath = handleFileUpload($_FILES['verification_document']);
            } catch (Exception $e) {
                header("Location: index.php?page=signup_courier&error=" . urlencode($e->getMessage()));
                exit();
            }
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $success = $this->model->registerCourierPartner($companyName, $contactPerson, $email, $passwordHash, $phone, $businessAddress, $district, $verificationDocumentPath);

        if ($success) {
            header("Location: index.php?page=pending_approval&message=Courier+Company+registration+submitted.+Your+verification+document+is+in+the+Admin+Verification+Queue.");
        } else {
            header("Location: index.php?page=signup_courier&error=Failed+to+submit+company+registration.");
        }
        exit();
    }

    /**
     * Logout
     */
    public function handleLogout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrfToken();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php?success=Logged+out+successfully.");
        exit();
    }

    public function handlePasswordResetRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=forgot_password");
            exit();
        }
        verifyCsrfToken();
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            header("Location: index.php?page=forgot_password&error=Please+enter+a+valid+email+address.");
            exit();
        }
        $token = $this->model->createPasswordResetToken($email);
        if (!$token) {
            header("Location: index.php?page=forgot_password&error=No+account+was+found+for+that+email+address.");
            exit();
        }
        header("Location: index.php?page=reset_password&token=" . urlencode($token));
        exit();
    }

    public function handlePasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=forgot_password");
            exit();
        }
        verifyCsrfToken();
        $token = $_POST['token'] ?? '';
        $password = $_POST['new_password'] ?? '';
        if (strlen($password) < 8 || $password !== ($_POST['confirm_password'] ?? '')) {
            header("Location: index.php?page=reset_password&token=" . urlencode($token) . "&error=Passwords+must+match+and+be+at+least+8+characters.");
            exit();
        }
        if (!$this->model->resetPassword($token, password_hash($password, PASSWORD_BCRYPT))) {
            header("Location: index.php?page=forgot_password&error=That+reset+link+is+invalid+or+expired.");
            exit();
        }
        header("Location: index.php?page=login&success=Password+reset+successfully.+Please+log+in.");
        exit();
    }
}
