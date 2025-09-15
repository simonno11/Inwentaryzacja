<?php
// login.php
session_start();
require_once "conf.php"; // musi ustawiać $link = mysqli_connect(...)

// Jeśli już zalogowany → przekieruj wg roli
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: " . ($_SESSION["uprawnienia"] === "admin" ? "admin.php" : "generator.php"));
    exit;
}

// Zmienne startowe (żeby nie było "Undefined variable")
$username = "";
$password = "";
$username_err = $password_err = $login_err = "";

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "") { $username_err = "Wpisz login."; }
    if ($password === "") { $password_err = "Wpisz hasło."; }

    if ($username_err === "" && $password_err === "") {
        $sql = "SELECT id, uzytkownik, haslo, uprawnienia FROM uzytkownicy WHERE uzytkownik = ? LIMIT 1";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) === 1) {
                    mysqli_stmt_bind_result($stmt, $id, $db_user, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // OK: logowanie
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["uzytkownik"] = $db_user;
                            $_SESSION["uprawnienia"] = $role;

                            header("Location: " . ($role === "admin" ? "admin.php" : "generator.php"));
                            exit;
                        } else {
                            $login_err = "Błędny login lub hasło.";
                        }
                    }
                } else {
                    $login_err = "Błędny login lub hasło.";
                }
            } else {
                $login_err = "Błąd serwera (execute).";
            }
            mysqli_stmt_close($stmt);
        } else {
            $login_err = "Błąd serwera (prepare).";
        }
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{ font-family: Arial, sans-serif; }
        .login-page{ height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f5; }
        .login-box{ width:320px; background:#fff; border:2px solid #800020; border-radius:12px; padding:24px; box-shadow:0 6px 20px rgba(0,0,0,.1); }
        .login-box h2{ margin:0 0 12px; color:#800020; text-align:center; }
        .login-box input{ width:100%; padding:10px; margin:8px 0; border:1px solid #bbb; border-radius:8px; }
        .login-box button{ width:100%; padding:10px; background:#800020; color:#fff; border:none; border-radius:8px; cursor:pointer; }
        .login-box button:hover{ background:#a83244; }
        .error{ color:#c62828; font-size:14px; text-align:center; }
    </style>
</head>
<body class="login-page">
<div class="login-box">
    <h2>Logowanie</h2>
    <?php if ($login_err) echo '<p class="error">'.htmlspecialchars($login_err).'</p>'; ?>
    <?php if ($username_err) echo '<p class="error">'.htmlspecialchars($username_err).'</p>'; ?>
    <?php if ($password_err) echo '<p class="error">'.htmlspecialchars($password_err).'</p>'; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="username" placeholder="Login" value="<?php echo htmlspecialchars($username); ?>" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <button type="submit">Zaloguj</button>
    </form>
</div>
</body>
</html>
