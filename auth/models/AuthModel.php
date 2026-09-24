<?php
/**
 * AuthModel - Handles User Authentication and Registration Queries for all Roles
 * Uses MySQLi with Prepared Statements.
 */

require_once __DIR__ . '/../../config/database.php';

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = getDBConnection();
    }

    /**
     * Search user by email across harvestly database
     */
    public function findUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id as id, full_name as name, email, password_hash, LOWER(role) as role, LOWER(account_status) as status 
                FROM users 
                WHERE email = ?
            ");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if ($user) {
                    if ($user['role'] === 'courier_partner') {
                        $user['role'] = 'courier';
                    }
                    if ($user['status'] === 'active' || $user['status'] === 'approved') {
                        $user['status'] = 'approved';
                    }
                    return $user;
                }
            }
        } catch (Exception $e) {}

        return false;
    }

    public function createPasswordResetToken($email) {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $invalidateStmt = $this->db->prepare("UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL");
        if ($invalidateStmt) {
            $invalidateStmt->bind_param("i", $user['id']);
            $invalidateStmt->execute();
            $invalidateStmt->close();
        }
        $stmt = $this->db->prepare("INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("iss", $user['id'], $tokenHash, $expiresAt);
        $success = $stmt->execute();
        $stmt->close();
        return $success ? $token : null;
    }

    public function resetPassword($token, $passwordHash) {
        $tokenHash = hash('sha256', $token);
        $stmt = $this->db->prepare("SELECT reset_id, user_id FROM password_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return false;
        }

        $this->db->begin_transaction();
        try {
            $passwordStmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $passwordStmt->bind_param("si", $passwordHash, $row['user_id']);
            $passwordStmt->execute();
            $passwordStmt->close();

            $usedStmt = $this->db->prepare("UPDATE password_reset_tokens SET used_at = NOW() WHERE reset_id = ?");
            $usedStmt->bind_param("i", $row['reset_id']);
            $usedStmt->execute();
            $usedStmt->close();
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Register Buyer (Immediate Active Status)
     */
    public function registerBuyer($fullName, $email, $passwordHash, $phone, $province, $district, $address) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (role, full_name, email, password_hash, phone, account_status)
                VALUES ('BUYER', ?, ?, ?, ?, 'ACTIVE')
            ");
            if (!$stmt) return false;
            $stmt->bind_param("ssss", $fullName, $email, $passwordHash, $phone);
            $stmt->execute();
            $userId = $this->db->insert_id;
            $stmt->close();

            // Get district_id
            $distId = 1;
            $distStmt = $this->db->prepare("SELECT district_id FROM districts WHERE district_name = ?");
            if ($distStmt) {
                $distStmt->bind_param("s", $district);
                $distStmt->execute();
                $res = $distStmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $distId = (int)$row['district_id'];
                }
                $distStmt->close();
            }

            $profStmt = $this->db->prepare("
                INSERT INTO buyer_profiles (buyer_id, default_address_line1, default_district_id)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE default_address_line1 = VALUES(default_address_line1)
            ");
            if ($profStmt) {
                $profStmt->bind_param("isi", $userId, $address, $distId);
                $profStmt->execute();
                $profStmt->close();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Register Farmer (Pending Admin Verification)
     */
    public function registerFarmer($fullName, $email, $passwordHash, $phone, $nicNumber, $farmAddress, $district, $idDocumentPath) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (role, full_name, email, password_hash, phone, account_status)
                VALUES ('FARMER', ?, ?, ?, ?, 'PENDING')
            ");
            if (!$stmt) return false;
            $stmt->bind_param("ssss", $fullName, $email, $passwordHash, $phone);
            $stmt->execute();
            $userId = $this->db->insert_id;
            $stmt->close();

            // Get district_id
            $distId = 6;
            $distStmt = $this->db->prepare("SELECT district_id FROM districts WHERE district_name = ?");
            if ($distStmt) {
                $distStmt->bind_param("s", $district);
                $distStmt->execute();
                $res = $distStmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $distId = (int)$row['district_id'];
                }
                $distStmt->close();
            }

            $profStmt = $this->db->prepare("
                INSERT INTO farmer_profiles (farmer_id, pickup_address_line1, district_id, verification_status)
                VALUES (?, ?, ?, 'PENDING')
                ON DUPLICATE KEY UPDATE verification_status = 'PENDING'
            ");
            if ($profStmt) {
                $profStmt->bind_param("isi", $userId, $farmAddress, $distId);
                $profStmt->execute();
                $profStmt->close();
            }

            // Save document record
            if ($idDocumentPath) {
                $docStmt = $this->db->prepare("
                    INSERT INTO verification_documents (user_id, document_type, original_file_name, stored_file_path, status)
                    VALUES (?, 'National Identity Card (NIC)', ?, ?, 'PENDING')
                ");
                if ($docStmt) {
                    $fname = basename($idDocumentPath);
                    $docStmt->bind_param("iss", $userId, $fname, $idDocumentPath);
                    $docStmt->execute();
                    $docStmt->close();
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Register Courier Partner Company (Pending Admin Verification)
     */
    public function registerCourierPartner($companyName, $contactPerson, $email, $passwordHash, $phone, $businessAddress, $district, $verificationDocumentPath) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (role, full_name, email, password_hash, phone, account_status)
                VALUES ('COURIER_PARTNER', ?, ?, ?, ?, 'PENDING')
            ");
            if (!$stmt) return false;
            $stmt->bind_param("ssss", $companyName, $email, $passwordHash, $phone);
            $stmt->execute();
            $userId = $this->db->insert_id;
            $stmt->close();

            // Get district_id
            $distId = 1;
            $distStmt = $this->db->prepare("SELECT district_id FROM districts WHERE district_name = ?");
            if ($distStmt) {
                $distStmt->bind_param("s", $district);
                $distStmt->execute();
                $res = $distStmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $distId = (int)$row['district_id'];
                }
                $distStmt->close();
            }

            $profStmt = $this->db->prepare("
                INSERT INTO courier_partner_profiles (courier_partner_id, organisation_name, contact_person_name, office_district_id, verification_status)
                VALUES (?, ?, ?, ?, 'PENDING')
                ON DUPLICATE KEY UPDATE verification_status = 'PENDING'
            ");
            if ($profStmt) {
                $profStmt->bind_param("issi", $userId, $companyName, $contactPerson, $distId);
                $profStmt->execute();
                $profStmt->close();
            }

            // Save document record
            if ($verificationDocumentPath) {
                $docStmt = $this->db->prepare("
                    INSERT INTO verification_documents (user_id, document_type, original_file_name, stored_file_path, status)
                    VALUES (?, 'Business Verification Document', ?, ?, 'PENDING')
                ");
                if ($docStmt) {
                    $fname = basename($verificationDocumentPath);
                    $docStmt->bind_param("iss", $userId, $fname, $verificationDocumentPath);
                    $docStmt->execute();
                    $docStmt->close();
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
