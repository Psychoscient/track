<?php
    // session_start();

    require_once "../model/config/Database.php";
    require_once "../model/UserModel.php";

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

        public function getUserByEmail($email) {
            $response = $this -> userModel -> searchUser($email);
            return $response;
        }

        public function addUser($fname, $lname, $email, $password, $yearlvl) {
            try {
                if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($yearlvl)) {
                    return [
                        "status" => false,
                        "message" => "Fill out all fields."
                    ];
                }

                if ($this -> emailExists($email)) {
                    return [
                        "status" => false,
                        "message" => "Email already exists."
                    ];
                }

                if ($this -> userModel -> createUser($fname, $lname, $email, $password, $yearlvl)) {
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
                
            } catch(InvalidArgumentException $e) {
                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function updateUser($userID, $fname, $lname, $email, $password, $yearlvl) {
            try {
                if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($yearlvl)) {
                    return [
                        "status" => false,
                        "message" => "Fill out all fields."
                    ];
                }

                if ($this -> emailExists($email)) {
                    return [
                        "status" => false,
                        "message" => "Email already exists."
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

            } catch(InvalidArgumentException $e) {
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
            } catch(PDOException $e) {
                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }  
        }

        public function emailExists($email) {
            try {
                $user = $this -> userModel -> searchUser($email);

                if (!$user) {
                    return false;
                } 

                return true;

            } catch(InvalidArgumentException $e) {
                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function logoutUser() {
            
        }

    }
?>