<?php
    require_once "../bl/ErrorLoggerManager.php";
    class UserModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        // GET Functions

        public function readUsers() {
            $selectQuery = "SELECT * 
                            FROM tbl_users";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }

        public function readUsersWithRelations() {
            try {
                $selectQuery = "SELECT 
                                    tbl_users.user_id,
                                    tbl_users.first_name,
                                    tbl_users.last_name,
                                    tbl_users.email,
                                    tbl_users.role_id,
                                    tbl_users.year_lvl_id,
                                    tbl_users.created_at AS user_created_at,
                                    tbl_users.updated_at AS user_updated_at,
                                    
                                    tbl_year_lvl.year_lvl_name,
                                    tbl_roles.role_name
                                FROM tbl_users
                                INNER JOIN tbl_year_lvl 
                                    ON tbl_users.year_lvl_id = tbl_year_lvl.year_lvl_id
                                INNER JOIN tbl_roles
                                    ON tbl_users.role_id = tbl_roles.role_id";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> execute();

                return $response;

            } catch(Exception $e){
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function searchUser($key, $value) {
            try {
                $selectQuery = "SELECT * 
                                FROM tbl_users 
                                WHERE " . $key . " = :value";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':value', $value);
                $response -> execute();

                return $response -> fetch(PDO::FETCH_ASSOC);

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function readTotalUsers() {
            try {
                $selectQuery = "SELECT 
                                    COUNT(DISTINCT user_id) as total_users
                                FROM tbl_users";
                
                $response = $this -> conn -> prepare($selectQuery);
                $response -> execute();

                return $response;

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        // POST Functions

        public function createUser($fname, $lname, $email, $password, $yearlvlID, $role) {
            try {
                $insertQuery = "INSERT INTO tbl_users (
                                    first_name, 
                                    last_name, 
                                    email, 
                                    password, 
                                    role_id, 
                                    year_lvl_id, 
                                    created_at, 
                                    updated_at) 
                                VALUES (
                                    :fname, 
                                    :lname, 
                                    :email, 
                                    :password, 
                                    :role_id, 
                                    :year_lvl_id, 
                                    :created_at, 
                                    :updated_at
                                )";

                $response = $this -> conn -> prepare($insertQuery);
                $roleID = $role;
                $dateNow = date('Y-m-d H:i:s');
                $response -> bindParam(':fname', $fname);
                $response -> bindParam(':lname', $lname);
                $response -> bindParam(':email', $email);
                $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
                $response -> bindParam(':password', $hashedPassword);
                $response -> bindParam(':role_id', $roleID);
                $response -> bindParam(':year_lvl_id', $yearlvlID);
                $response -> bindParam(':created_at', $dateNow);
                $response -> bindParam(':updated_at', $dateNow);

                return $response -> execute();
                
            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function updateUser($userID, $fname, $lname, $email, $password, $yearlvl) {
            try {
                $updateQuery = "UPDATE tbl_users 
                                SET 
                                    first_name = :firstName, 
                                    last_name = :lastName, 
                                    email = :email,"; 
                if (!empty($password)) {
                    $updateQuery .= "password = :password, ";
                }
                $updateQuery .= "year_lvl_id = :yearlvl,
                                 updated_at = :updated_at 
                                 WHERE 
                                    user_id = :userID";

                $response = $this -> conn -> prepare($updateQuery);

                $dateNow = date('Y-m-d H:i:s');

                $response -> bindParam(":firstName", $fname);
                $response -> bindParam(":lastName", $lname);
                $response -> bindParam(":email", $email);

                if(!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
                    $response -> bindParam(':password', $hashedPassword);
                }

                $response -> bindParam(':yearlvl', $yearlvl);
                $response -> bindParam(":updated_at", $dateNow);
                $response -> bindParam(":userID", $userID);

                return $response -> execute();

            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function deleteUser($userID) {
            try {
                $deleteQuery = "DELETE FROM tbl_users WHERE user_id = :userID";
                $response = $this -> conn -> prepare($deleteQuery);
                $response -> bindParam(":userID", $userID);

                return $response->execute();

            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function storeResetToken($email, $token, $expiryTime) {
            try {
                $query = "UPDATE tbl_users
                          SET
                            reset_token = :token, 
                            reset_token_expiry = :expiry 
                          WHERE email = :email";
                
                $response = $this -> conn -> prepare($query);
                $response -> bindParam(":token", $token);
                $response -> bindParam(":expiry", $expiryTime);
                $response -> bindParam(":email", $email);

                return $response -> execute();

            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function getUserByResetToken($token) {
            try {
                $query = "SELECT * FROM tbl_users WHERE reset_token = :token AND reset_token_expiry > NOW()";
                $response = $this -> conn -> prepare($query);
                $response -> bindParam(":token", $token);

                if (!$response) {
                    return [
                        "status" => false,
                        "message" => "Reset token is invalid or has expired."
                    ];
                }

                return $response -> execute();
                
            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        // UPDATE Functions

        public function updatePasswordByToken($newPassword, $token) {
            try {
                $query = "UPDATE tbl_users
                          SET
                            password = :newPassword,
                            reset_token = NULL,
                            reset_token_expiry = NULL  
                          WHERE reset_token = :token";
                $response = $this -> conn -> prepare($query);
                
                if (!$response) {
                    throw new Exception("Failed to prepare statement: " . implode(" ", $this->conn->errorInfo()));
                }
                
                $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
                $response -> bindParam(':newPassword', $hashedPassword);
                $response -> bindParam(":token", $token);

                $result = $response -> execute();
                
                return $result;

            } catch (Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function updatePassword($password) {

        }

    }
?>
