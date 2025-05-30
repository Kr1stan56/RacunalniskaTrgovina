<?php
require_once 'baza.php';
include_once 'session.php';

// Dovoli samo adminom
if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

// Preberi kategorije iz baze za dropdown
$kategorije = [];
$result = $conn->query("SELECT  ime FROM kategorije ORDER BY ime");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kategorije[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shrani'])) {
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $cena = floatval($_POST['cena']);
    $zaloga = intval($_POST['zaloga']);
    $url_slike = trim($_POST['url_slike']);
    $id_kategorije = intval($_POST['kategorija']);

    $stmt = $conn->prepare("INSERT INTO izdelek (ime, opis, cena, zaloga, slika, id_ka) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiii", $ime, $opis, $cena, $zaloga, $url_slike, $id_kategorije);
    $stmt->execute();

    header("Location: izdelki.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj nov izdelek</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
    <main class="uredi-izdelek">
        <form action="izdelki.php" method="get">
            <button type="submit">← Nazaj na izdelke</button>
        </form>

        <h1>Dodaj nov izdelek</h1>

        <section>
            <h2>Slika</h2>
            <form method="post">
                <label>Vnesi URL slike:</label><br>
                <input type="text" name="url_slike" style="width: 100%;"><br><br>
        </section>

        <section>
            <h2>Splošno</h2>
                <label>Ime:</label>
                <input type="text" name="ime" required><br>

                <label>Opis:</label>
                <textarea name="opis" required></textarea><br>

                <label>Cena (€):</label>
                <input type="number" name="cena" required><br>

                <label>Zaloga:</label>
                <input type="number" name="zaloga" required><br>

                <label>Kategorija:</label>
                <select name="kategorija" required>
                    <option value="" disabled selected>Izberi kategorijo</option>
                    <?php foreach ($kategorije as $kat): ?>
                        <option ><?= htmlspecialchars($kat['ime']) ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <button type="submit" name="shrani">Dodaj izdelek</button>
            </form>
        </section>
    </main>
</body>
</html>
