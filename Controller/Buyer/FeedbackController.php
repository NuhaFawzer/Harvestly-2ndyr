<?php

require_once __DIR__ . '/_bridge.php';

buyer_require_login();

$bid = currentBuyerId();

$reviewMessage = trim(
    (string)($_GET['review_message'] ?? '')
);

$complaintMessage = trim(
    (string)($_GET['complaint_message'] ?? '')
);

$isAjax = strtolower(
    (string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')
) === 'xmlhttprequest';


/*
|--------------------------------------------------------------------------
| POST REQUEST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $responseSuccess = false;

    $responseMessage = 'Unable to process the request.';


    /*
    |--------------------------------------------------------------------------
    | REVIEW - CREATE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['submit_review'])) {

        $oid = (int)preg_replace(
            '/\D/',
            '',
            (string)($_POST['order_id'] ?? '')
        );

        $order = db_fetch_one(
            "SELECT
                order_id,
                farmer_id
             FROM orders
             WHERE order_id = ?
               AND buyer_id = ?
               AND order_status = 'COMPLETED'",
            'ii',
            [
                $oid,
                $bid
            ]
        );


        if ($order) {

            $rating = max(
                1,
                min(
                    5,
                    (int)($_POST['farmer_rating'] ?? 5)
                )
            );

            $text = trim(
                (string)($_POST['quality_comment'] ?? '')
            );

            $fid = (int)$order['farmer_id'];


            /*
            | CREATE REVIEW
            */

            $st = db()->prepare(
                "INSERT INTO reviews
                (
                    order_id,
                    buyer_id,
                    farmer_id,
                    rating,
                    review_text
                )
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    rating = VALUES(rating),
                    review_text = VALUES(review_text)"
            );


            if ($st) {

                $st->bind_param(
                    'iiiis',
                    $oid,
                    $bid,
                    $fid,
                    $rating,
                    $text
                );

                $responseSuccess = $st->execute();

                $st->close();


                $reviewMessage = $responseSuccess
                    ? 'Thank you! Your review has been submitted.'
                    : 'Unable to save your review.';

            } else {

                $reviewMessage =
                    'Unable to prepare the review request.';
            }


            $responseMessage =
                $reviewMessage;

        } else {

            $reviewMessage =
                'Only your completed orders can be reviewed.';

            $responseMessage =
                $reviewMessage;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEW - UPDATE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['update_review'])) {

        $reviewId = (int)(
            $_POST['review_id'] ?? 0
        );

        $rating = (int)(
            $_POST['farmer_rating'] ?? 0
        );

        $text = trim(
            (string)($_POST['quality_comment'] ?? '')
        );


        /*
        | Validate review ID
        */

        if ($reviewId <= 0) {

            $responseSuccess = false;

            $responseMessage =
                'Invalid review selected.';

        /*
        | Validate rating
        */

        } elseif ($rating < 1 || $rating > 5) {

            $responseSuccess = false;

            $responseMessage =
                'Please select a rating from 1 to 5.';

        } else {


            /*
            |--------------------------------------------------------------------------
            | UPDATE ONLY THE LOGGED-IN BUYER'S REVIEW
            |--------------------------------------------------------------------------
            */

            $st = db()->prepare(
                "UPDATE reviews
                 SET
                    rating = ?,
                    review_text = ?
                 WHERE review_id = ?
                   AND buyer_id = ?"
            );


            if (!$st) {

                $responseSuccess = false;

                $responseMessage =
                    'Database error while preparing review update.';

            } else {

                $st->bind_param(
                    'isii',
                    $rating,
                    $text,
                    $reviewId,
                    $bid
                );


                if ($st->execute()) {

                    $responseSuccess = true;

                    $responseMessage =
                        'Review updated successfully.';

                    $reviewMessage =
                        'Review updated successfully.';

                } else {

                    $responseSuccess = false;

                    $responseMessage =
                        'Unable to update the review.';

                }

                $st->close();
            }
        }


        /*
        | AJAX response
        */

        if ($isAjax) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            echo json_encode([
                'success' =>
                    $responseSuccess,

                'message' =>
                    $responseMessage
            ]);

            exit();
        }


        /*
        | Normal browser request
        */

        header(
            'Location: ' .
            buyerRoute('FeedbackController.php') .
            '?review_message=' .
            urlencode($responseMessage)
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEW - DELETE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['delete_review'])) {

        $reviewId = (int)(
            $_POST['review_id'] ?? 0
        );


        if ($reviewId <= 0) {

            $responseSuccess = false;

            $responseMessage =
                'Invalid review selected.';

        } else {


            /*
            |--------------------------------------------------------------------------
            | DELETE ONLY THE LOGGED-IN BUYER'S REVIEW
            |--------------------------------------------------------------------------
            */

            $st = db()->prepare(
                "DELETE FROM reviews
                 WHERE review_id = ?
                   AND buyer_id = ?"
            );


            if (!$st) {

                $responseSuccess = false;

                $responseMessage =
                    'Database error while preparing review deletion.';

            } else {

                $st->bind_param(
                    'ii',
                    $reviewId,
                    $bid
                );


                if ($st->execute()) {

                    if ($st->affected_rows > 0) {

                        $responseSuccess = true;

                        $responseMessage =
                            'Review deleted successfully.';

                    } else {

                        $responseSuccess = false;

                        $responseMessage =
                            'Review not found or you are not allowed to delete it.';
                    }

                } else {

                    $responseSuccess = false;

                    $responseMessage =
                        'Unable to delete the review.';
                }


                $st->close();
            }
        }


        /*
        | AJAX response
        */

        if ($isAjax) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            echo json_encode([
                'success' =>
                    $responseSuccess,

                'message' =>
                    $responseMessage
            ]);

            exit();
        }


        /*
        | Normal browser request
        */

        header(
            'Location: ' .
            buyerRoute('FeedbackController.php') .
            '?review_message=' .
            urlencode($responseMessage)
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLAINT - UPDATE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['update_complaint'])) {

        $complaintId = (int)(
            $_POST['complaint_id'] ?? 0
        );

        $category = trim(
            (string)($_POST['category'] ?? 'Other')
        );

        $details = trim(
            (string)($_POST['details'] ?? '')
        );


        $allowedCategories = [
            'Product Quality',
            'Damaged Product',
            'Wrong Product',
            'Incorrect Quantity',
            'Delivery Issue',
            'Other'
        ];


        if (
            $complaintId <= 0 ||
            $details === ''
        ) {

            $responseMessage =
                'Please enter a complaint description.';

        } elseif (
            !in_array(
                $category,
                $allowedCategories,
                true
            )
        ) {

            $responseMessage =
                'Please select a valid complaint category.';

        } else {

            $st = db()->prepare(
                "UPDATE complaints
                 SET
                    category = ?,
                    description = ?
                 WHERE complaint_id = ?
                   AND complainant_user_id = ?
                   AND UPPER(complaint_status) = 'OPEN'
                   AND created_at >=
                       DATE_SUB(
                           NOW(),
                           INTERVAL 24 HOUR
                       )"
            );


            $st->bind_param(
                'ssii',
                $category,
                $details,
                $complaintId,
                $bid
            );


            $responseSuccess =
                $st->execute()
                && $st->affected_rows > 0;

            $st->close();


            $responseMessage =
                $responseSuccess
                    ? 'Complaint updated successfully.'
                    : 'This complaint can no longer be updated.';
        }


        if ($isAjax) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            echo json_encode([
                'success' =>
                    $responseSuccess,

                'message' =>
                    $responseMessage
            ]);

            exit();
        }


        header(
            'Location: ' .
            buyerRoute('FeedbackController.php') .
            '?complaint_message=' .
            urlencode($responseMessage)
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLAINT - DELETE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['delete_complaint'])) {

        $complaintId = (int)(
            $_POST['complaint_id'] ?? 0
        );


        if ($complaintId <= 0) {

            $responseMessage =
                'Invalid complaint selected.';

        } else {

            $st = db()->prepare(
                "DELETE FROM complaints
                 WHERE complaint_id = ?
                   AND complainant_user_id = ?
                   AND UPPER(complaint_status) = 'OPEN'
                   AND created_at >=
                       DATE_SUB(
                           NOW(),
                           INTERVAL 24 HOUR
                       )"
            );


            $st->bind_param(
                'ii',
                $complaintId,
                $bid
            );


            $responseSuccess =
                $st->execute()
                && $st->affected_rows > 0;

            $st->close();


            $responseMessage =
                $responseSuccess
                    ? 'Complaint deleted successfully.'
                    : 'This complaint can no longer be deleted.';
        }


        if ($isAjax) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            echo json_encode([
                'success' =>
                    $responseSuccess,

                'message' =>
                    $responseMessage
            ]);

            exit();
        }


        header(
            'Location: ' .
            buyerRoute('FeedbackController.php') .
            '?complaint_message=' .
            urlencode($responseMessage)
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLAINT - CREATE
    |--------------------------------------------------------------------------
    */

    if (isset($_POST['submit_complaint'])) {

        $oid = (int)preg_replace(
            '/\D/',
            '',
            (string)($_POST['order_id'] ?? '')
        );

        $cat = trim(
            (string)($_POST['category'] ?? 'Other')
        );

        $details = trim(
            (string)($_POST['details'] ?? '')
        );


        $order = $oid > 0
            ? db_fetch_one(
                'SELECT order_id
                 FROM orders
                 WHERE order_id = ?
                   AND buyer_id = ?',
                'ii',
                [
                    $oid,
                    $bid
                ]
            )
            : null;


        if ($order && $details !== '') {

            $evidencePath = null;

            $evidenceFile =
                $_FILES['evidence'] ?? null;


            /*
            | Support existing photos[] upload
            */

            if (
                !$evidenceFile &&
                isset(
                    $_FILES['photos']['name'][0]
                ) &&
                $_FILES['photos']['name'][0] !== ''
            ) {

                $evidenceFile = [

                    'name' =>
                        $_FILES['photos']['name'][0],

                    'type' =>
                        $_FILES['photos']['type'][0]
                        ?? '',

                    'tmp_name' =>
                        $_FILES['photos']['tmp_name'][0]
                        ?? '',

                    'error' =>
                        $_FILES['photos']['error'][0]
                        ?? UPLOAD_ERR_NO_FILE,

                    'size' =>
                        $_FILES['photos']['size'][0]
                        ?? 0,
                ];
            }


            if (
                $evidenceFile &&
                (int)(
                    $evidenceFile['error']
                    ?? UPLOAD_ERR_NO_FILE
                ) !== UPLOAD_ERR_NO_FILE
            ) {

                try {

                    $evidencePath =
                        handleFileUpload(
                            $evidenceFile,
                            'assets/documents/complaints/'
                        );

                } catch (Throwable $e) {

                    $complaintMessage =
                        $e->getMessage();

                    $responseMessage =
                        $complaintMessage;


                    if ($isAjax) {

                        header(
                            'Content-Type: application/json; charset=utf-8'
                        );

                        echo json_encode([
                            'success' =>
                                false,

                            'message' =>
                                $responseMessage
                        ]);

                        exit();
                    }
                }
            }


            if ($complaintMessage === '') {

                $st = db()->prepare(
                    "INSERT INTO complaints
                    (
                        order_id,
                        complainant_user_id,
                        complainant_role,
                        category,
                        description,
                        evidence_path
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        'BUYER',
                        ?,
                        ?,
                        ?
                    )"
                );


                $st->bind_param(
                    'iisss',
                    $oid,
                    $bid,
                    $cat,
                    $details,
                    $evidencePath
                );


                $responseSuccess =
                    $st->execute();

                $st->close();


                $complaintMessage =
                    $responseSuccess
                        ? 'Your complaint has been submitted.'
                        : 'Unable to submit your complaint.';

                $responseMessage =
                    $complaintMessage;
            }

        } else {

            $complaintMessage =
                'Please select one of your orders and enter complaint details.';

            $responseMessage =
                $complaintMessage;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX RESPONSE
    |--------------------------------------------------------------------------
    */

    if ($isAjax) {

        header(
            'Content-Type: application/json; charset=utf-8'
        );

        echo json_encode([
            'success' =>
                $responseSuccess,

            'message' =>
                $responseMessage
        ]);

        exit();
    }
}


/*
|--------------------------------------------------------------------------
| REVIEW - READ
|--------------------------------------------------------------------------
*/

$reviews = db_fetch_all(

    "SELECT
        r.review_id AS id,
        r.order_id,
        r.buyer_id,
        r.farmer_id,
        r.rating AS farmer_rating,
        r.rating AS delivery_rating,
        r.review_text AS quality_comment,
        '' AS delivery_comment,
        r.created_at,
        CONCAT(
            'HV',
            r.order_id
        ) AS order_number,
        'Submitted' AS status
     FROM reviews r
     WHERE r.buyer_id = ?
     ORDER BY r.created_at DESC",

    'i',

    [$bid]
);


/*
|--------------------------------------------------------------------------
| COMPLAINT - READ
|--------------------------------------------------------------------------
*/

$complaints = db_fetch_all(

    "SELECT
        c.complaint_id AS id,
        c.category,
        c.description AS details,
        c.complaint_status AS status,
        c.created_at,
        CONCAT(
            'HV',
            c.order_id
        ) AS order_number
     FROM complaints c
     WHERE c.complainant_user_id = ?
     ORDER BY c.created_at DESC",

    'i',

    [$bid]
);


/*
|--------------------------------------------------------------------------
| REVIEWABLE / COMPLAINT ORDERS
|--------------------------------------------------------------------------
*/

$reviewableOrders = [];

$complaintOrders = [];


foreach (buyer_orders() as $o) {

    $x = [

        'order_number' =>
            $o['id'],

        'delivered_at' =>
            $o['delivered_at'] ?? null,

        'total' =>
            $o['total'],

    ];


    /*
    | Completed orders can be reviewed
    */

    if (
        ($o['status'] ?? '') === 'Completed'
    ) {

        $reviewableOrders[] =
            $x;
    }


    /*
    | Orders available for complaints
    */

    $complaintOrders[] =
        $x;
}


/*
|--------------------------------------------------------------------------
| DEFAULT ORDER
|--------------------------------------------------------------------------
*/

$orderId = trim(
    (string)(
        $_GET['order_id']
        ??
        $_POST['order_id']
        ??
        (
            $reviewableOrders[0]['order_number']
            ?? ''
        )
    )
);


/*
|--------------------------------------------------------------------------
| DEFAULT FARMER
|--------------------------------------------------------------------------
*/

$farmerName =
    'Harvestly Farmer';


/*
|--------------------------------------------------------------------------
| LOAD BUYER FEEDBACK VIEW
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../../View/Buyer/feedback.php';