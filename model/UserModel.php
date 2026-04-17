<?php
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

            } catch(PDOException $e){
                http_response_code(400);
                echo "Database error: " . $e -> getMessage();
                exit;
            }
        }

        public function searchUser($email) {
            try {
                $selectQuery = "SELECT * 
                                FROM tbl_users 
                                WHERE email = :email";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':email', $email);
                $response -> execute();

                return $response -> fetch(PDO::FETCH_ASSOC);

            } catch(PDOException $e) {
                http_response_code(400);
                echo "Database error: " . $e -> getMessage();
                exit;
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

            } catch(PDOException $e) {
                http_response_code(400);
                echo "Database error: " . $e -> getMessage();
                exit;
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
                
            } catch (PDOException $e) {
                http_response_code(400);
                echo "Database error: " . $e -> getMessage();
                exit;
            }
        }

        public function updateUser($userID, $fname, $lname, $email, $password, $yearlvl) {
            try {
                $updateQuery = "UPDATE tbl_users 
                                SET 
                                    first_name = :firstName, 
                                    last_name = :lastName, 
                                    email = :email, 
                                    password = :password,
                                    year_lvl_id = :yearlvl,
                                    updated_at = :updated_at 
                                WHERE user_id = :userID";

                $response = $this->conn->prepare($updateQuery);

                $dateNow = date('Y-m-d H:i:s');

                $response -> bindParam(":firstName", $fname);
                $response -> bindParam(":lastName", $lname);
                $response -> bindParam(":email", $email);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $response -> bindParam(':password', $hashedPassword);
                $response -> bindParam(':yearlvl', $yearlvl);
                $response -> bindParam(":updated_at", $dateNow);
                $response -> bindParam(":userID", $userID);

                return $response -> execute();

            } catch (PDOException $e) {
                http_response_code(400);
                echo "Database error: " . $e -> getMessage();
                exit;
            }
        }

        public function deleteUser($userID) {
            $deleteQuery = "DELETE FROM tbl_users WHERE user_id = :userID";
            $response = $this->conn->prepare($deleteQuery);
            $response->bindParam(":userID", $userID);

            return $response->execute();
        }

    }
?>