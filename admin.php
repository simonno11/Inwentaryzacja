<?php
session_start();
require_once "conf.php";

if (!isset($_SESSION['uprawnienia']) || $_SESSION['uprawnienia'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Dodawanie firmy
if (isset($_POST['add_company'])) {
    $name = trim($_POST['company_name']);
    if (!empty($name)) {
        $sql = "INSERT INTO firmy (name) VALUES (?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $name);
            if (!mysqli_stmt_execute($stmt)) {
                echo "<p style='color:red'>Błąd dodawania firmy: " . mysqli_error($link) . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Dodawanie inwentaryzacji
if (isset($_POST['add_inventory'])) {
    $company_id = $_POST['company_id'];
    $building = $_POST['budynek'];
    $inv_number = $_POST['numer_wyposazenia'];
    $inv_name = $_POST['nazwa'];
    $quantity = $_POST['ilosc'];
    $value = $_POST['cena'];

    $sql = "INSERT INTO inventory (firmy_id, budynek, numer_wyposazenia, nazwa, ilosc, value)
            VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "isssid", $company_id, $building, $inv_number, $inv_name, $quantity, $value);
        if (!mysqli_stmt_execute($stmt)) {
            echo "<p style='color:red'>Błąd dodawania inwentaryzacji: " . mysqli_error($link) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Pobranie firm
$companies = [];
$result = mysqli_query($link, "SELECT * FROM firmy");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $companies[] = $row;
    }
}

// Pobranie wszystkich inwentaryzacji (z nazwą firmy)
$inventories = [];
$sql = "SELECT i.*, f.name AS company_name 
        FROM inventory i 
        JOIN firmy f ON i.firmy_id = f.id
        ORDER BY f.name, i.budynek";
$result = mysqli_query($link, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $inventories[] = $row;
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Admina</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{ font-family: Arial, sans-serif; background:#f4f4f4; margin:0; }
        .top-bar{ background:#800020; color:white; padding:15px; display:flex; justify-content:space-between; }
        .container{ max-width:1000px; margin:20px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
        h3{ color:#800020; }
        form{ margin-bottom:30px; }
        input, select, button{ padding:10px; margin:5px 0; border-radius:8px; border:1px solid #ccc; width:100%; }
        button{ background:#800020; color:white; border:none; cursor:pointer; }
        button:hover{ background:#a83244; }
        table{ width:100%; border-collapse:collapse; margin-top:20px; }
        table, th, td{ border:1px solid #ccc; }
        th, td{ padding:10px; text-align:left; }
        th{ background:#eee; }
    </style>
</head>
<body>
    <nav class="top-bar">
        <h2>Panel administratora</h2>
        <a href="logout.php" style="color:white; text-decoration:none;">Wyloguj</a>
    </nav>

    <div class="container">
        <section>
            <h3>Dodaj firmę</h3>
            <form method="post">
                <input type="text" name="company_name" placeholder="Nazwa firmy" required>
                <button type="submit" name="add_company">Dodaj</button>
            </form>
        </section>

        <section>
            <h3>Dodaj inwentaryzację</h3>
            <form method="post">
                <select name="company_id" required>
                    <option value="">Wybierz firmę</option>
                    <?php foreach ($companies as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="budynek" placeholder="Budynek" required>
                <input type="text" name="numer_wyposazenia" placeholder="Numer wyposażenia" required>
                <input type="text" name="nazwa" placeholder="Nazwa" required>
                <input type="number" name="ilosc" placeholder="Ilość" required>
                <input type="number" step="0.01" name="cena" placeholder="Wartość (PLN)" required>
                <button type="submit" name="add_inventory">Dodaj</button>
            </form>
        </section>

        <section>
            <h3>Lista inwentaryzacji</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Firma</th>
                        <th>Budynek</th>
                        <th>Numer wyposażenia</th>
                        <th>Nazwa</th>
                        <th>Ilość</th>
                        <th>Wartość (PLN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventories)): ?>
                        <tr><td colspan="7">Brak danych</td></tr>
                    <?php else: ?>
                        <?php foreach ($inventories as $inv): ?>
                            <tr>
                                <td><?= $inv['id'] ?></td>
                                <td><?= htmlspecialchars($inv['company_name']) ?></td>
                                <td><?= htmlspecialchars($inv['budynek']) ?></td>
                                <td><?= htmlspecialchars($inv['numer_wyposazenia']) ?></td>
                                <td><?= htmlspecialchars($inv['nazwa']) ?></td>
                                <td><?= $inv['ilosc'] ?></td>
                                <td><?= number_format($inv['value'], 2, ',', ' ') ?> zł</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
