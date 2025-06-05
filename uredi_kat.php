<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}



$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT ime FROM kategorije WHERE id_ka = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ime);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['shrani'])) {
        $novo_ime = trim($_POST['ime']);
        if (!empty($novo_ime)) {
            $stmt = $conn->prepare("UPDATE kategorije SET ime = ? WHERE id_ka = ?");
            $stmt->bind_param("si", $novo_ime, $id);
            $stmt->execute();
            header("Location: izdelki.php");
            exit;
        }
    }

    if (isset($_POST['odstrani'])) {
        $stmt = $conn->prepare("DELETE FROM kategorije WHERE id_ka = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: izdelki.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi kategorijo</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
<main class="uredi-izdelek">
    <form action="izdelki.php" method="get">
        <button type="submit">← Nazaj na kategorije</button>
    </form>

    <h1>Uredi kategorijo</h1>

    <form method="post">
        <label>Ime kategorije:</label>
        <input type="text" name="ime" value="<?= htmlspecialchars($ime) ?>" required><br>

        <button type="submit" name="shrani">Shrani spremembe</button>
        <button type="submit" name="odstrani">Odstrani kategorijo</button>
    </form>
</main>
</body>
</html>
