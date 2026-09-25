<?php
require_once __DIR__ . '/_bridge.php';
buyer_require_login();

$bid = currentBuyerId();
$reviewMessage = '';
$complaintMessage = trim((string)($_GET['complaint_message'] ?? ''));
$isAjax = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responseSuccess = false;
    $responseMessage = 'Unable to process the request.';


    /* =========================
       COMPLAINT UPDATE (CRUD)
    ========================= */
    if (isset($_POST['update_complaint'])) {
        $complaintId = (int)($_POST['complaint_id'] ?? 0);
        $category = trim((string)($_POST['category'] ?? 'Other'));
        $details = trim((string)($_POST['details'] ?? ''));
        $allowedCategories = [
            'Product Quality', 'Damaged Product', 'Wrong Product',
            'Incorrect Quantity', 'Delivery Issue', 'Other'
        ];

        if ($complaintId <= 0 || $details === '') {
            $responseMessage = 'Please enter a complaint description.';
        } elseif (!in_array($category, $allowedCategories, true)) {
            $responseMessage = 'Please select a valid complaint category.';
        } else {
            $st = db()->prepare(
                "UPDATE complaints
                 SET category = ?, description = ?
                 WHERE complaint_id = ?
                   AND complainant_user_id = ?
                   AND UPPER(complaint_status) = 'OPEN'
                   AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
            );
            $st->bind_param('ssii', $category, $details, $complaintId, $bid);
            $responseSuccess = $st->execute() && $st->affected_rows > 0;
            $st->close();
            $responseMessage = $responseSuccess
                ? 'Complaint updated successfully.'
                : 'This complaint can no longer be updated.';
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => $responseSuccess, 'message' => $responseMessage]);
            exit();
        }

        header('Location: ' . buyerRoute('FeedbackController.php') . '?complaint_message=' . urlencode($responseMessage));
        exit();
    }

    /* =========================
       COMPLAINT DELETE (CRUD)
    ========================= */
    if (isset($_POST['delete_complaint'])) {
        $complaintId = (int)($_POST['complaint_id'] ?? 0);

        if ($complaintId <= 0) {
            $responseMessage = 'Invalid complaint selected.';
        } else {
            $st = db()->prepare(
                "DELETE FROM complaints
                 WHERE complaint_id = ?
                   AND complainant_user_id = ?
                   AND UPPER(complaint_status) = 'OPEN'
                   AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
            );
            $st->bind_param('ii', $complaintId, $bid);
            $responseSuccess = $st->execute() && $st->affected_rows > 0;
            $st->close();
            $responseMessage = $responseSuccess
                ? 'Complaint deleted successfully.'
                : 'This complaint can no longer be deleted.';
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => $responseSuccess, 'message' => $responseMessage]);
            exit();
        }

        header('Location: ' . buyerRoute('FeedbackController.php') . '?complaint_message=' . urlencode($responseMessage));
        exit();
    }

    if (isset($_POST['submit_review'])) {
        $oid = (int)preg_replace('/\D/', '', (string)($_POST['order_id'] ?? ''));
        $order = db_fetch_one(
            "SELECT order_id, farmer_id FROM orders WHERE order_id=? AND buyer_id=? AND order_status='COMPLETED'",
            'ii',
            [$oid, $bid]
        );

        if ($order) {
            $rating = max(1, min(5, (int)($_POST['farmer_rating'] ?? 5)));
            $text = trim((string)($_POST['quality_comment'] ?? ''));
            $st = db()->prepare(
                'INSERT INTO reviews(order_id,buyer_id,farmer_id,rating,review_text) VALUES(?,?,?,?,?) '
                . 'ON DUPLICATE KEY UPDATE rating=VALUES(rating),review_text=VALUES(review_text)'
            );
            $fid = (int)$order['farmer_id'];
            $st->bind_param('iiiis', $oid, $bid, $fid, $rating, $text);
            $responseSuccess = $st->execute();
            $st->close();
            $reviewMessage = $responseSuccess
                ? 'Thank you! Your review has been submitted.'
                : 'Unable to save your review.';
            $responseMessage = $reviewMessage;
        } else {
            $reviewMessage = 'Only your completed orders can be reviewed.';
            $responseMessage = $reviewMessage;
        }
    }

    if (isset($_POST['submit_complaint'])) {
        $oid = (int)preg_replace('/\D/', '', (string)($_POST['order_id'] ?? ''));
        $cat = trim((string)($_POST['category'] ?? 'Other'));
        $details = trim((string)($_POST['details'] ?? ''));
        $order = $oid > 0
            ? db_fetch_one('SELECT order_id FROM orders WHERE order_id=? AND buyer_id=?', 'ii', [$oid, $bid])
            : null;

        if ($order && $details !== '') {
            $evidencePath = null;
            $evidenceFile = $_FILES['evidence'] ?? null;
            if (!$evidenceFile && isset($_FILES['photos']['name'][0]) && $_FILES['photos']['name'][0] !== '') {
                $evidenceFile = [
                    'name' => $_FILES['photos']['name'][0],
                    'type' => $_FILES['photos']['type'][0] ?? '',
                    'tmp_name' => $_FILES['photos']['tmp_name'][0] ?? '',
                    'error' => $_FILES['photos']['error'][0] ?? UPLOAD_ERR_NO_FILE,
                    'size' => $_FILES['photos']['size'][0] ?? 0,
                ];
            }
            if ($evidenceFile && (int)($evidenceFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                try {
                    $evidencePath = handleFileUpload($evidenceFile, 'assets/documents/complaints/');
                } catch (Throwable $e) {
                    $complaintMessage = $e->getMessage();
                    $responseMessage = $complaintMessage;
                    if ($isAjax) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode(['success' => false, 'message' => $responseMessage]);
                        exit();
                    }
                }
            }

            if ($complaintMessage === '') {
                $st = db()->prepare(
                    "INSERT INTO complaints(order_id,complainant_user_id,complainant_role,category,description,evidence_path) "
                    . "VALUES(?,?,'BUYER',?,?,?)"
                );
                $st->bind_param('iisss', $oid, $bid, $cat, $details, $evidencePath);
                $responseSuccess = $st->execute();
                $st->close();
                $complaintMessage = $responseSuccess
                    ? 'Your complaint has been submitted.'
                    : 'Unable to submit your complaint.';
                $responseMessage = $complaintMessage;
            }
        } else {
            $complaintMessage = 'Please select one of your orders and enter complaint details.';
            $responseMessage = $complaintMessage;
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $responseSuccess, 'message' => $responseMessage]);
        exit();
    }
}

$reviews = db_fetch_all(
    "SELECT r.review_id id,r.rating farmer_rating,r.rating delivery_rating,r.review_text quality_comment,"
    . "'' delivery_comment,r.created_at,CONCAT('HV',r.order_id) order_number "
    . "FROM reviews r WHERE r.buyer_id=? ORDER BY r.created_at DESC",
    'i',
    [$bid]
);
$complaints = db_fetch_all(
    "SELECT c.complaint_id id,c.category,c.description details,c.complaint_status status,c.created_at,"
    . "CONCAT('HV',c.order_id) order_number FROM complaints c "
    . "WHERE c.complainant_user_id=? ORDER BY c.created_at DESC",
    'i',
    [$bid]
);

$reviewableOrders = [];
$complaintOrders = [];
foreach (buyer_orders() as $o) {
    $x = [
        'order_number' => $o['id'],
        'delivered_at' => $o['delivered_at'] ?? null,
        'total' => $o['total'],
    ];
    if ($o['status'] === 'Completed') {
        $reviewableOrders[] = $x;
    }
    $complaintOrders[] = $x;
}

$orderId = trim((string)($_GET['order_id'] ?? $_POST['order_id'] ?? ($reviewableOrders[0]['order_number'] ?? '')));
$farmerName = 'Harvestly Farmer';
require __DIR__ . '/../../View/Buyer/feedback.php';
