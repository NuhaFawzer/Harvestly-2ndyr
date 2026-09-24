<?php
/**
 * Harvestly Global Helper Functions
 * Security, session management, output sanitization, and file uploads.
 */

if (session_status() === PHP_SESSION_NONE) {
    // Secure session cookie attributes
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Escape output string to prevent XSS attacks
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

function verifyCsrfToken() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('The form session expired. Please go back and try again.');
    }
}

/**
 * Verify Admin Authentication & Role
 */
function checkAdminAuth() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php?page=login&error=Unauthorized+access.+Please+login+as+Admin.");
        exit();
    }
}

/**
 * Base URL helper for absolute asset/link references
 */
function baseUrl($path = '') {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($scriptDir === '/' || $scriptDir === '\\') {
        $scriptDir = '';
    }
    return rtrim($scriptDir, '/') . '/' . ltrim($path, '/');
}

/**
 * Secure File Upload Handler
 * Validates file size, allowed MIME types, renames storage file, blocks executables.
 */
function handleFileUpload($fileArray, $targetDirRelative = 'assets/documents/') {
    if (!isset($fileArray['error']) || is_array($fileArray['error'])) {
        throw new Exception('Invalid upload parameter format.');
    }

    switch ($fileArray['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new Exception('No file was uploaded.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('Exceeded file size limit.');
        default:
            throw new Exception('Unknown upload error.');
    }

    // Max 5MB file size limit
    if ($fileArray['size'] > 5 * 1024 * 1024) {
        throw new Exception('Exceeded maximum allowed file size (5MB).');
    }

    // Allowed extensions and MIME types
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'pdf' => 'application/pdf',
    ];

    $ext = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));
    
    // Check extension
    if (!array_key_exists($ext, $allowedMimes)) {
        throw new Exception('Invalid file extension. Only JPG, PNG, and PDF files are permitted.');
    }

    // Check MIME type using finfo
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileArray['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception('Invalid file format. Security check failed.');
        }
    }

    // Prepare target directory
    $targetDirAbsolute = __DIR__ . '/../' . ltrim($targetDirRelative, '/');
    if (!is_dir($targetDirAbsolute)) {
        mkdir($targetDirAbsolute, 0755, true);
    }

    // Secure unique filename creation
    $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($fileArray['name'], PATHINFO_FILENAME));
    $newFileName = sprintf('%s_%s.%s', 
        trim($safeBaseName, '_') ?: 'document', 
        bin2hex(random_bytes(8)), 
        $ext
    );

    $destination = $targetDirAbsolute . '/' . $newFileName;

    if (!move_uploaded_file($fileArray['tmp_name'], $destination)) {
        throw new Exception('Failed to save uploaded file.');
    }

    return ltrim($targetDirRelative, '/') . '/' . $newFileName;
}

/**
 * Get relevant produce image path from assets/images based on product title & category
 * Uses renamed image filenames from XAMPP assets folder.
 */
function getProductImage($title, $category = '') {
    $t = strtolower($title);
    $c = strtolower($category);

    if (strpos($t, 'carrot') !== false || strpos($t, 'nuwara') !== false) {
        return 'assets/images/Fresh Fruits & Vegetables.jpg';
    } elseif (strpos($t, 'baby potato') !== false) {
        return 'assets/images/Dambulla Baby Potatoes.jpg';
    } elseif (strpos($t, 'onion') !== false || strpos($t, 'dambulla') !== false) {
        return 'assets/images/Dambulla Red Onions.jpg';
    } elseif (strpos($t, 'mango') !== false || strpos($t, 'colomban') !== false || strpos($t, 'jaffna') !== false) {
        return 'assets/images/Jaffna Karutha Colomban.jpg';
    } elseif (strpos($t, 'pineapple') !== false) {
        return 'assets/images/Golden Pineapple.jpg';
    } elseif (strpos($t, 'passion fruit') !== false) {
        return 'assets/images/Uva Passion Fruit.jpg';
    } elseif (strpos($t, 'spinach') !== false) {
        return 'assets/images/Ceylon Spinach Bunch.jpg';
    } elseif (strpos($t, 'cinnamon') !== false) {
        return 'assets/images/Ceylon Cinnamon Quills.jpg';
    } elseif (strpos($t, 'tomato') !== false) {
        return 'assets/images/tomato.jpg';
    } elseif (strpos($t, 'brinjal') !== false || strpos($t, 'eggplant') !== false) {
        return 'assets/images/brinjal.jpg';
    } elseif (strpos($t, 'avocado') !== false || strpos($t, 'avacado') !== false) {
        return 'assets/images/avacado.jpg';
    } elseif (strpos($t, 'watermelon') !== false) {
        return 'assets/images/watermelon.jpg';
    } elseif (strpos($t, 'corn') !== false || strpos($t, 'maize') !== false) {
        return 'assets/images/corn.jpg';
    } elseif (strpos($t, 'potato') !== false) {
        return 'assets/images/potato.jpg';
    } elseif (strpos($t, 'orange') !== false || strpos($t, 'citrus') !== false) {
        return 'assets/images/orange.jpg';
    } elseif (strpos($t, 'beetroot') !== false || strpos($t, 'beet') !== false) {
        return 'assets/images/beetroot.jpg';
    } elseif (strpos($t, 'gotu') !== false || strpos($t, 'leaf') !== false || strpos($t, 'green') !== false || strpos($t, 'herb') !== false) {
        return 'assets/images/gotukola.jpg';
    } elseif (strpos($t, 'cucumber') !== false) {
        return 'assets/images/fruits  cucumber 🥒.jpg';
    } elseif (strpos($c, 'fruit') !== false) {
        return 'assets/images/vegetables and fruits.jpg';
    } elseif (strpos($c, 'veg') !== false || strpos($c, 'organic') !== false) {
        return 'assets/images/vegfr.jpg';
    } elseif (strpos($c, 'leaf') !== false || strpos($c, 'green') !== false) {
        return 'assets/images/gotukola.jpg';
    }
    return 'assets/images/Fresh Fruits & Vegetables.jpg';
}

