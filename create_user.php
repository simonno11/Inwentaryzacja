<?php
require 'conf.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['uzytkownik'] ?? '');
    $password = $_POST['haslo'] ?? '';
    $role     = ($_POST['uprawnienia'] ?? 'user') === 'admin' ? 'admin' : 'user';

    if ($username === '' || $password === '') {
        $message = "Podaj login i hasło.";
    } else {
        // sprawdź czy istnieje
        $stmt = $pdo->prepare('SELECT id FROM uzytkownicy WHERE uzytkownik = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $message = "Użytkownik już istnieje.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO uzytkownicy (uzytkownik, haslo, uprawnienia) VALUES (?, ?, ?)');
            $ins->execute([$username, $hash, $role]);
            $message = "Utworzono użytkownika: " . htmlspecialchars($username);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head><meta charset="utf-8"><title>Dodaj użytkownika</title></head>
<body>
<h2>Dodaj użytkownika</h2>
<?php if ($message) echo '<p>' . htmlspecialchars($message) . '</p>'; ?>
<form method="post">
  <input name="uzytkownik" placeholder="Login" required><br><br>
  <input name="haslo" type="password" placeholder="Hasło" required><br><br>
  <select name="uprawnienia">
    <option value="user">Użytkownik</option>
    <option value="admin">Admin</option>
  </select><br><br>
  <button type="submit">Utwórz</button>
</form>
</body>
</html>
