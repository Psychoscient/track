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

        public function getUserByEmail($email) {
            $response = $this -> userModel -> searchUser($email);
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
                $user = $this -> userModel -> searchUser($email);

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

        public function emailExists($email) {
            try {
                $user = $this -> userModel -> searchUser($email);

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
    }
?>