<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Generator PDF - Inwentaryzacja</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #800020;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #800020;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background-color: #800020;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #a8324a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background-color: #eee;
        }
        .logout {
            float: right;
            margin-top: -40px;
        }
        .logout a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .logout a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h1>Generator PDF - Inwentaryzacja</h1>
    <div class="logout">
        <a href="logout.php">Wyloguj</a>
    </div>
</header>

<div class="container">
    <h2>Wybierz firmę i generuj podsumowanie</h2>
    
    <form method="post" action="generate_pdf.php">
        <div class="form-group">
            <label for="firma">Firma:</label>
            <select name="firma" id="firma" required>
                <option value="">-- wybierz firmę --</option>
                <option value="firma1">Firma 1</option>
                <option value="firma2">Firma 2</option>
                <option value="firma3">Firma 3</option>
                <!-- później pobierzesz to z bazy -->
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Budynek</th>
                    <th>Numer inwentaryzacyjny</th>
                    <th>Nazwa</th>
                    <th>Ilość</th>
                    <th>Wartość</th>
                </tr>
            </thead>
            <tbody>
                <!-- tutaj będziesz wyświetlał dane z bazy -->
                <tr>
                    <td colspan="5">Brak danych - wybierz firmę</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align:center;">
            <button type="submit">Generuj PDF</button>
        </div>
    </form>
</div>

</body>
</html>
