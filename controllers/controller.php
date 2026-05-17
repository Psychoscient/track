<?php
    session_start();

    require_once "../bl/UserManager.php";
    require_once "../bl/RoleManager.php";
    require_once "../bl/EventManager.php";
    require_once "../bl/OrganizerApplicationManager.php";
    require_once "../helper/send.php";
    require_once "../bl/ErrorLoggerManager.php";

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    try {

        $action = $_POST['action'] ?? '';

        $auth = new UserManager();
        $perm = new RoleManager();
        $eventManager = new EventManager();
        $organizerApplicationManager = new OrganizerApplicationManager();

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
                    $user = $auth -> getUser("email", $data['email']);
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
                                              $data['yearlvl'],
                                              $data['role']);

                echo json_encode($result);
                break;

            case 'delete':
                $result = $auth -> deleteUser($data['userID']);
                echo json_encode($result);
                break;

            case 'event-create':
                $result = $eventManager -> createEvent(
                    $data['title'] ?? '',
                    $data['description'] ?? '',
                    $data['categoryID'] ?? '',
                    $data['eventVenueID'] ?? '',
                    $data['startDateTime'] ?? '',
                    $data['endDateTime'] ?? '',
                    $data['statusID'] ?? '',
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['permissions'] ?? []
                );

                echo json_encode($result);
                break;

            case 'event-update':
                $result = $eventManager -> updateEvent(
                    $data['eventID'] ?? 0,
                    $data['title'] ?? '',
                    $data['description'] ?? '',
                    $data['categoryID'] ?? '',
                    $data['eventVenueID'] ?? '',
                    $data['startDateTime'] ?? '',
                    $data['endDateTime'] ?? '',
                    $data['statusID'] ?? '',
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['role_id'] ?? 0,
                    $_SESSION['permissions'] ?? []
                );

                echo json_encode($result);
                break;

            case 'event-delete':
                $result = $eventManager -> deleteEvent(
                    $data['eventID'] ?? 0,
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['role_id'] ?? 0,
                    $_SESSION['permissions'] ?? []
                );

                echo json_encode($result);
                break;

            case 'organizer-apply':
                $result = $organizerApplicationManager -> applyForOrganizer(
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['role_id'] ?? 0,
                    $data['reason'] ?? ''
                );

                echo json_encode($result);
                break;

            case 'organizer-approve':
                $result = $organizerApplicationManager -> approveApplication(
                    $data['applicationID'] ?? 0,
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['permissions'] ?? []
                );

                echo json_encode($result);
                break;

            case 'organizer-reject':
                $result = $organizerApplicationManager -> rejectApplication(
                    $data['applicationID'] ?? 0,
                    $_SESSION['user_id'] ?? 0,
                    $_SESSION['permissions'] ?? []
                );

                echo json_encode($result);
                break;
            
            case 'logout':
                // throw new Exception("Testing error logging");

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
            
            case 'forgot-password':
                $result = $auth -> requestPasswordReset($data['email']);

                if ($result['status']) {
                    $token = $result['token'];
                    $user = $result['user'];

                    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/track/views/reset-password.php?token=" . $token;
        
                    passwordReset($user['email'], $user['first_name'], $resetLink);
                } 
                
                echo json_encode($result);
                break;
            
            case 'reset-password':
                $result = $auth -> confirmPasswordReset($data['token'], $data['newPassword'], $data['confirmPassword']);

                if ($result['status']) {
                    $newPassword = $data['newPassword'];
                    $token = $data['token'];

                    $result = $auth -> changePassword($newPassword, $token);
                    
                    echo json_encode($result);
                    break;
                }

                echo json_encode($result);
                break;

            default:
                http_response_code(400);

                echo json_encode([
                    "status" => false, 
                    "message" => "Invalid action",
                    "action" => $_POST['action']
                ]);

                break;
        }

        exit;

    } catch (Exception $e) {
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

    function passwordReset($email, $firstName, $resetLink) {
        $name = htmlspecialchars($firstName);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email) {
            die("Invalid email.");
        }

        // UST-styled password reset email - fully compliant with design system
        $body = '
            <div style="background:#F9F7F3;padding:48px 20px;font-family:\'Segoe UI\',Tahoma,Geneva,Verdana,sans-serif;color:#1A1A1A;min-height:100vh;">
                <div style="max-width:540px;margin:0 auto;">
                    <!-- Card Container with UST Signature Gold Top Border -->
                    <div style="background:#FFFFFF;border-top:4px solid #F4C300;border-radius:8px;box-shadow:0 2px 8px rgba(26, 26, 26, 0.08);padding:48px;overflow:hidden;">
                        
                        <!-- Logo/Branding Section -->
                        <div style="display:flex;align-items:center;justify-content:center;margin-bottom:32px;gap:12px;">
                            <span style="font-family:Outfit,sans-serif;font-size:18px;font-weight:bold;color:#1A1A1A;letter-spacing:-0.5px;">UST Track</span>
                        </div>

                        <!-- Divider -->
                        <div style="height:2px;background:linear-gradient(to right, transparent, #F4C300, transparent);margin-bottom:32px;"></div>
                        
                        <!-- Header with Gold Accent -->
                        <div style="margin-bottom:32px;text-align:center;">
                            <h1 style="margin:0 0 12px 0;font-family:Outfit,sans-serif;color:#1A1A1A;font-size:32px;font-weight:bold;letter-spacing:-0.5px;">Password Reset</h1>
                            <p style="margin:0;color:#333333;font-size:14px;font-family:&quot;Segoe UI&quot;,Tahoma,Geneva,Verdana,sans-serif;">Secure your account</p>
                        </div>
                        
                        <!-- Greeting Section -->
                        <div style="margin-bottom:32px;">
                            <p style="margin:0 0 16px 0;font-size:16px;line-height:1.8;color:#1A1A1A;font-family:&quot;Segoe UI&quot;,Tahoma,Geneva,Verdana,sans-serif;">Hi <strong style="color:#F4C300;">' . $name . '</strong>,</p>
                            <p style="margin:0;font-size:15px;line-height:1.8;color:#333333;font-family:&quot;Segoe UI&quot;,Tahoma,Geneva,Verdana,sans-serif;">We received a request to reset the password associated with your UST Track account. Click the button below to create a new password and secure your account.</p>
                        </div>

                        <!-- Reset Button - UST Primary Style -->
                        <div style="text-align:center;margin:40px 0;">
                            <a href="' . htmlspecialchars($resetLink) . '" style="display:inline-block;background:#F4C300;color:#1A1A1A;padding:16px 44px;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;transition:all 0.3s ease;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(244, 195, 0, 0.2);font-family:\'Segoe UI\',sans-serif;">Reset My Password</a>
                        </div>

                        <!-- Spacer -->
                        <div style="height:8px;"></div>

                        <!-- Security Notice Box -->
                        <div style="background:#FBF8F3;border-left:4px solid #F4C300;padding:20px;border-radius:6px;margin:32px 0;">
                            <p style="margin:0 0 10px 0;color:#1A1A1A;font-size:14px;font-weight:600;">⏱️ Link Expiration</p>
                            <p style="margin:0;color:#333333;font-size:13px;line-height:1.7;">This password reset link will expire in <strong>1 hour</strong> for security reasons. If you didn\'t request this password reset, please ignore this email or contact support immediately.</p>
                        </div>

                        <!-- Alternative Link Box -->
                        <div style="background:#F9F7F3;border:1px solid #E5E5E5;padding:20px;border-radius:6px;margin:24px 0;">
                            <p style="margin:0 0 12px 0;color:#333333;font-size:13px;font-weight:500;">If the button above doesn\'t work, copy and paste this link:</p>
                            <div style="background:#FFFFFF;padding:12px;border-radius:4px;border:1px solid #E5E5E5;word-break:break-all;">
                                <span style="color:#F4C300;font-size:11px;font-family:monospace;line-height:1.6;">' . htmlspecialchars($resetLink) . '</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="height:1px;background:#E5E5E5;margin:32px 0;"></div>

                        <!-- Footer Section -->
                        <div style="text-align:center;padding-top:8px;">
                            <p style="margin:0 0 12px 0;color:#1A1A1A;font-size:13px;font-weight:500;">
                                Need help?
                            </p>
                            <p style="margin:0 0 16px 0;color:#333333;font-size:12px;line-height:1.7;">
                                This is an automated message from <strong>UST Track</strong>.<br>
                                For security reasons, please do not share this email or link with anyone.<br>
                                <span style="color:#999999;">Contact your system administrator if you have questions.</span>
                            </p>
                            <p style="margin:0;color:#999999;font-size:11px;">
                                © 2026 University of Santo Tomas. All rights reserved.
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>';
                
        $result = sendEmail($email, $name, "UST Track - Password Reset Request", $body);

        if ($result === true) {
            return true;
        } else {
            return [
                "status" => false,
                "message" => "Failed to send password reset email. Please try again later."
            ];
        }
    }

    function errorHandle($data) {
        try {
            $input = array_map(function($in) {
                return is_string($in) ? trim($in) : $in;
            }, $data);

            $action = $_POST['action'] ?? null;
            if (!$action) {
                return;
            }

            switch($input['action']) {
                case 'signup':
                    if (empty($input['fname']) || empty($input['lname']) || empty($input['email']) || empty($input['password']) || empty($input['confirmPassword']) || empty($input['yearlvl'])) {
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

                    if ($input['password'] !== $input['confirmPassword']) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Passwords do not match."
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
