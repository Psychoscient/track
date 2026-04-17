<?php
    session_start();

    require_once "../bl/UserManager.php";
    require_once "../bl/RoleManager.php";

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    try {

        $action = $_POST['action'] ?? '';

        $auth = new UserManager();
        $perm = new RoleManager();

        $data = errorHandle($_POST);

        switch($action) {
            case 'signup':
                $result = $auth -> addUser($data['fname'], 
                                           $data['lname'], 
                                           $data['email'], 
                                           $data['password'], 
                                           $data['yearlvl']);

                echo json_encode($result);
                break;

            case 'login':
                $result = $auth -> loginUser($data['email'], 
                                             $data['password']);

                if($result['status'] === true) {
                    $user = $auth -> getUserByEmail($data['email']);
                    $permissions = array_column($perm -> getPermissions($user['role_id']), 'permission_name');

                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['permissions'] = $permissions;

                    // echo '<pre>';
                    // echo var_dump($_SESSION);
                    // echo '</pre>';

                } else {
                    echo json_encode($result);
                    exit;
                }
                
                echo json_encode($result);
                break;
            
            case 'create':
                $result = $auth -> createUser($data['fname'], 
                                              $data['lname'], 
                                              $data['email'], 
                                              $data['password'], 
                                              $data['yearlvl'],
                                              $data['role']);

                echo json_encode($result);
                break;
            
            case 'update':
                $result = $auth -> updateUser($data['userID'], 
                                              $data['fname'], 
                                              $data['lname'], 
                                              $data['email'], 
                                              $data['password'], 
                                              $data['yearlvl']);

                echo json_encode($result);
                break;

            case 'delete':
                $result = $auth -> deleteUser($data['userID']);
                echo json_encode($result);
                break;
            
            case 'logout':

                $_SESSION = [];

                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(
                        session_name(),
                        '',
                        time() - 42000,
                        $params["path"],
                        $params["domain"],
                        $params["secure"],
                        $params["httponly"]
                    );
                }

                session_destroy();

                // echo '<pre>';
                // var_dump($_SESSION);
                // echo '</pre>';

                echo json_encode([
                    "status" => true,
                    "redirect" => 0
                ]);

                break;

            default:
                http_response_code(400);

                echo json_encode([
                    "status" => false, 
                    "message" => "Invalid action"
                ]);

                break;
        }

        exit;

    } catch (Exception $e) {
        http_response_code(400);
        echo $e -> getMessage();
        exit;
    }

    function errorHandle($data) {
        try {
            $input = array_map(function($in) {
                return is_string($in) ? trim($in) : $in;
            }, $data);

            switch($input['action']) {
                case 'signup':
                case 'update':

                    if (empty($input['fname']) || empty($input['lname']) || empty($input['email']) || empty($input['password']) || empty($input['yearlvl'])) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Fill out all fields."
                        ]);
                        exit;
                    }

                    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Invalid email format."
                        ]);
                        exit;
                    }

                    if (strlen($input['password']) <= 7) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Password should have at least 8 characters."
                        ]);
                        exit; 
                    }

                break;
            }
             
            return $input;

        } catch(Exception $e) {
            http_response_code(400);
            echo $e -> getMessage();
            exit;
        }
    }

?>