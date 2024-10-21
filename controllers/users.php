<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pi3-smart-pill-box/helpers/full-path.php';
require_once fullPath('models/users.php');

if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['action'])) {
    controllerUsers($_POST['action']);
}

function controllerUsers($action)
{
    switch ($action) {
        case 'sign_up':
            $existing_user = getUserByEmail($_POST['email']);
            if ($existing_user) {
                $query_string = '?sign_up_status=already_registered';
                header("Location: /pi3-smart-pill-box/views/pages/sign-up.php" . $query_string);
                exit();
            } else {
                $user = [
                    'user_email'    => $_POST['email'],
                    'user_password' => hash('sha256', $_POST['password']),
                    'first_name'    => $_POST['first_name'],
                    'last_name'     => $_POST['last_name'],
                ];
                try {
                    $user_id = createUser($user);

                    $companion_user = [
                        'monitored_user_id' => $user_id,
                        'user_email'    => $_POST['companion_email'],
                        'first_name'    => $_POST['companion_first_name'],
                        'last_name'     => $_POST['companion_last_name'],
                    ];
                    createCompanionUser($companion_user);

                    $_SESSION['user_id']         = $user_id;
                    $_SESSION['user_email']      = $user['user_email'];
                    $_SESSION['companion_user_email'] = $_POST['companion_email'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name']  = $user['last_name'];
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                }

                header("Location: /pi3-smart-pill-box/views/pages/list-medicines.php");
                exit();
            }
            break;

        case 'login':
            $user = getUserByEmail($_POST['email']);
            if ($user && $user['user_password'] == hash('sha256', trim($_POST['password']))) {
                $_SESSION['user_id']         = $user['user_id'];
                $_SESSION['user_email']      = $user['user_email'];
                $_SESSION['companion_user_email'] = $user['companion_user_email'];
                $_SESSION['user_first_name'] = $user['first_name'];
                $_SESSION['user_last_name']  = $user['last_name'];

                header('Location: /pi3-smart-pill-box/views/pages/list-medicines.php');
                exit();
            } else {
                $query_string = '?login_status=incorrect_info';
                header('Location: /pi3-smart-pill-box/views/pages/login.php' . $query_string);
            }
            break;

        case 'logout':
            session_unset();
            session_destroy();
            header('Location: /pi3-smart-pill-box/views/pages/login.php');
            exit();
            break;
    }
}
