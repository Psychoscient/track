<?php
    // session_start();

    require_once "../model/config/Database.php";
    require_once "../model/UserModel.php";
    require_once "ErrorLoggerManager.php";

    class UserManager {
        private $userModel;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> userModel = new UserModel($db);
        }

        public function getUsers(){
            $response = $this -> userModel -> readUsers();
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUsersWithRelations(){
            $response = $this -> userModel -> readUsersWithRelations();
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }

        public function getTotalUsers() {
            $response = $this -> userModel -> readTotalUsers();
            return $response -> fetch(PDO::FETCH_ASSOC);
        }

        public function getUser($key, $value) {
            $response = $this -> userModel -> searchUser($key, $value);
            return $response;
        }

        public function addUser($fname, $lname, $email, $password, $yearlvl) {
            try {
                $role = 2;

                if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($yearlvl)) {
                    return [
                        "status" => false,
                        "message" => "Fill out all fields."
                    ];
                }

                $existingUser = $this -> emailExists($email);

                // echo "<pre>";
                // print_r($existingUser);
                // echo "</pre>";

                if ($existingUser['status']) {
                    return [
                        "status" => false,
                        "message" => "bitch nigga."
                    ];
                }

                if ($this -> userModel -> createUser($fname, $lname, $email, $password, $yearlvl, $role)) {
                    return [
                        "status" => true,
                        "message" => "User created successfully."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to create user."
                    ];
                }

            } catch(InvalidArgumentException $e) {
                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function loginUser($email, $password) {
            try {
                $user = $this -> userModel -> searchUser("email", $email);

                // throw new InvalidArgumentException("Test validation error");
                
                if (!($this -> emailExists($email))) {
                    return [
                        "status" => false,
                        "message" => "Invalid email or password."
                    ];
                }

                if (password_verify($password, $user['password'])) {
                    return [
                        "status" => true,
                        "message" => "Login successful.",
                        "role" => $user['role_id']
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Invalid email or password."
                    ];
                }
                
            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['userID'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function createUser($fname, $lname, $email, $password, $yearlvl, $role) {
            try {
                if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($yearlvl) || empty($role)) {
                    return [
                        "status" => false,
                        "message" => "Fill out all fields."
                    ];
                }

                $existingUser = $this -> emailExists($email);

                if ($existingUser['status']) {
                    return [
                        "status" => false,
                        "message" => "Email already exists."
                    ];
                }

                if ($this -> userModel -> createUser($fname, $lname, $email, $password, $yearlvl, $role)) {
                    return [
                        "status" => true,
                        "message" => "User created successfully."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to create user."
                    ];
                }

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function updateUser($userID, $fname, $lname, $email, $password, $yearlvl) {
            try {
                if (empty($fname) || empty($lname) || empty($email) || empty($yearlvl)) {
                    return [
                        "status" => false,
                        "message" => "Fill out all fields."
                    ];
                }

                $existingUser = $this -> emailExists($email);

                if ($existingUser['status'] && $existingUser['user']['email'] !== $email) {
                    return [
                        "status" => false,
                        "message" => "This email is already taken by another account."
                    ];
                }

                if ($this -> userModel -> updateUser($userID, $fname, $lname, $email, $password, $yearlvl)) {
                    return [
                        "status" => true,
                        "message" => "User updated successfully."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to update user."
                    ];
                }

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function deleteUser($userID) {
            try {
                if($this -> userModel -> deleteUser($userID)) {
                    return [
                        "status" => true,
                        "message" => "User deleted successfully."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to delete user."
                    ];
                }
            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }  
        }

        public function setUserRole($userID, $roleID) {
            try {
                $response = $this -> userModel -> updateUserRole($userID, $roleID);

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "User role updated successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to update user role."
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function emailExists($email) {
            try {
                $user = $this -> userModel -> searchUser("email", $email);

                if (!$user) {
                    return [
                        "status" => false,
                        "message" => "Email does not exist."
                    ];
                } 

                return [
                    "status" => true,
                    "user" => $user
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function generateResetToken() {
            try {
                return bin2hex(random_bytes(32));

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function changePassword($newPassword, $token) {
            try {
                
                if ($this -> userModel -> updatePasswordByToken($newPassword, $token)) {
                    return [
                        "status" => true,
                        "message" => "Password updated succesfully."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to update password."
                    ];
                }

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function requestPasswordReset($email) {
            try {
                $existingUser = $this -> emailExists($email);

                if (!$existingUser['status']) {
                    return [
                        "status" => false,
                        "message" => "Invalid user."
                    ];
                }

                if ($existingUser['status'] && $existingUser['user']['email'] !== $email) {
                    return [
                        "status" => false,
                        "message" => "This email is already taken by another account."
                    ];
                }

                $newToken = $this -> generateResetToken();
                $newExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                if ($this -> userModel -> storeResetToken($email, $newToken, $newExpiry)) {
                    return [
                        "status" => true,
                        "user" => $existingUser['user'],
                        "token" => $newToken,
                        "message" => "Password reset link sent to email."
                    ];
                } else {
                    return [
                        "status" => false,
                        "message" => "Failed to email user."
                    ];
                }

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function confirmPasswordReset($token, $newPassword, $confirmPassword) {
            try {
                $user = $this -> userModel -> searchUser("reset_token", $token);

                if (!$user) {
                    return [
                        "status" => false,
                        "message" => "No user found for this token."
                    ];
                }

                if (strtotime($user['reset_token_expiry']) < time()) {
                    return [
                        "status" => false,
                        "message" => "Token expired. Please request a new password reset link."
                    ];
                }

                if ($newPassword !== $confirmPassword) {
                    return [
                        "status" => false,
                        "message" => "Passwords do not match."
                    ];
                }

                return [
                    "status" => true,
                    "user" => $user
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function isValidToken($token) {
            try {
                $user = $this -> userModel -> searchUser("reset_token", $token);

                if (!$user) {
                    return [
                        "status" => false,
                        "message" => "No user found for this token."
                    ];
                }

                if (strtotime($user['reset_token_expiry']) < time()) {
                    return [
                        "status" => false,
                        "message" => "Token expired. Please request a new password reset link."
                    ];
                }

                return [
                    "status" => true,
                    "user" => $user
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }
    }
?>
