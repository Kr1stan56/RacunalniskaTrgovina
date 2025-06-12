<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['shrani'])) {
    $ime = trim($_POST['ime']);

    if (!empty($ime)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO kategorije (ime) VALUES (?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $ime);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: izdelki.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj kategorijo</title>
    <link rel="stylesheet" href="css/uredi_dodaj?v=1.1.css">
</head>
<body>
<main class="uredi-izdelek">
    <form action="izdelki	.php" method="get">
        <button type="submit">← Nazaj na kategorije</button>
    </form>

    <h1>Dodaj novo kategorijo</h1>

    <form method="post">
        <label>Ime kategorije:</label>
        <input type="text" name="ime" required><br>

        <button type="submit" name="shrani">Shrani kategorijo</button>
    </form>
</main>
</body>
</html>
