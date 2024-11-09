<?php
session_start();
$server = "localhost";
$user = "root";
$passwd = '';
$db = "o_s_m_s";

$connection = mysqli_connect($server, $user, $passwd, $db);
if (!$connection) {
    die("Oops, connection failed!");
}

if (!isset($_SESSION['auth']) || !is_array($_SESSION['auth'])) {
    $_SESSION['auth'] = [];
}

if (!empty($_SESSION['auth']['auth'])) {
    header('location: ./?web');
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$username || !$password) {
        $_SESSION['error'] = "O'Oh! Username or password not set";
    } else {
        // Select both id and password for login verification
        $new_user = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $connection->prepare($new_user);
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Store id and username in the session
                    $_SESSION['auth']['auth'] = true;
                    $_SESSION['auth']['id'] = $user_id;
                    $_SESSION['auth']['username'] = $username;
                    header('location: ./');
                    exit();
                } else {
                    $_SESSION['error'] = "Login unsuccessful!";
                }
            } else {
                $_SESSION['error'] = "Login unsuccessful!";
            }
        } else {
            $_SESSION['error'] = "Error executing query.";
        }
        $stmt->close();
    }
}

if (isset($_POST['register'])) {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $comfirm_password = $_POST['comfirm_password'] ?? null;

    if (($username == null) || ($password == null) || ($comfirm_password == null)) {
        print_r([$_POST]);
        $_SESSION['error'] = "O'Oh! Username or password error";
    } else {
        if ($comfirm_password == $password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $new_user = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $connection->prepare($new_user);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                // After successful registration, store id and username in session
                $_SESSION['auth']['auth'] = true;
                $_SESSION['auth']['id'] = $stmt->insert_id;
                $_SESSION['auth']['username'] = $username;
                header('location: ./');
                exit();
            } else {
                $_SESSION['error'] = "Registration unsuccessful!";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "O'Oh! confirm password and password do not match";
        }
    }
}

$connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="./css/style.min.css">
</head>
<body>
    <div class="layer"></div>
    <main class="page-center">
        <article class="sign-up">
            <h1 class="sign-up__title">Welcome</h1>
            <p class="sign-up__subtitle" style="color:<?php echo isset($_SESSION['error']) ? 'red' : '';?>;"><?php echo ($_SESSION['error'] ?? "Online Store Management System"); unset($_SESSION['error']); ?></p>
            <?php if ($_GET['auth'] == "login" || !isset($_GET['auth'])): ?>
                <form class="sign-up-form form" method="post" action="auth.php">
                    <h1 class="sign-up__title">Login</h1>
                    <label class="form-label-wrapper">
                        <p class="form-label">Email</p>
                        <input type="text" class="form-input" name="username">
                    </label>
                    <label class="form-label-wrapper">
                        <p class="form-label">Password</p>
                        <input type="password" class="form-input" name="password">
                    </label>
                    <input type="submit" name="login" class="form-btn primary-default-btn transparent-btn" value="submit">
                    <a href="./auth.php?auth=register" class="link-info forget-link">New Here</a>
                </form>
            <?php elseif ($_GET['auth'] == "register"): ?>
                <form class="sign-up-form form" method="post" action="auth.php">
                    <h1 class="sign-up__title">Register</h1>
                    <label class="form-label-wrapper">
                        <p class="form-label">Email</p>
                        <input type="text" name="username">
                    </label>
                    <label class="form-label-wrapper">
                        <p class="form-label">Password</p>
                        <input type="password" class="form-input" name="password">
                    </label>
                    <label class="form-label-wrapper">
                        <p class="form-label">Comfirm Password</p>
                        <input type="password" class="form-input" name="comfirm_password">
                    </label>
                    <input type="submit" name="register" class="form-btn primary-default-btn transparent-btn" value="submit">
                    <a href="./auth.php?auth=login">I have Account</a>
                </form>
            <?php endif; ?>
        </article>
    </main>
    <script src="js/script.js"></script>
</body>
</html>
