<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$id_izdelka = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shrani'])) {
    $ime = $_POST['ime'];
    $opis = $_POST['opis'];
    $cena = floatval($_POST['cena']);
    $zaloga = intval($_POST['zaloga']);

    $stmt = $conn->prepare("UPDATE izdelek SET ime = ?, opis = ?, cena = ?, zaloga = ? WHERE id_i = ?");
    $stmt->bind_param("ssdii", $ime, $opis, $cena, $zaloga, $id_izdelka);
    $stmt->execute();
    exit;
}

if (isset($_POST['zamenjaj_sliko'])) {
    $url_slike = trim($_POST['url_slike']);
    $stmt = $conn->prepare("UPDATE izdelek SET slika = ? WHERE id_i = ?");
	
    $stmt->bind_param("si", $url_slike, $id_izdelka);
    $stmt->execute();
    exit;
}


if (isset($_POST['odstrani_sliko'])) {
    $stmt = $conn->prepare("UPDATE izdelek SET slika = NULL WHERE id_i = ?");
	
    $stmt->bind_param("i", $id_izdelka);
    $stmt->execute();
    exit;
}

if (isset($_POST['odstrani'])) {
    $stmt = $conn->prepare("DELETE FROM izdelek WHERE id_i = ?");
	
    $stmt->bind_param("i", $id_izdelka);
    $stmt->execute();
    exit;
}

$stmt = $conn->prepare("SELECT * FROM izdelek WHERE id_i = ?");
$stmt->bind_param("i", $id_izdelka);
$stmt->execute();
$izdelek = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi izdelek</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
    <main class="uredi-izdelek">
        <form action="izdelki.php" method="get">
            <button type="submit">← Nazaj na izdelke</button>
        </form>

        <h1>Urejanje: <?= htmlspecialchars($izdelek['ime']) ?></h1>

        <section>
            <h2>Slika</h2>
            <?php if ($izdelek['slika']): ?>
                <img src="<?= htmlspecialchars($izdelek['slika']) ?>" width="200"><br>
            <?php else: ?>
                <p>Ni slike.</p>
            <?php endif; ?>
            <form method="post">
				<label>Vnesi URL slike:</label><br>
				<input type="text" name="url_slike" value="<?= htmlspecialchars($izdelek['slika'] ?? '') ?>" style="width: 100%;"><br><br>
				<button type="submit" name="zamenjaj_sliko">Shrani URL slike</button>
				<button type="submit" name="odstrani_sliko">Odstrani sliko</button>
			</form>

        </section>

        <section>
            <h2>Splošno</h2>
            <form method="post">
                <label>Ime:</label>
                <input type="text" name="ime" value="<?= htmlspecialchars($izdelek['ime']) ?>"><br>

                <label>Opis:</label>
                <textarea name="opis"><?= htmlspecialchars($izdelek['opis']) ?></textarea><br>

                <label>Cena (€):</label>
                <input type="number" step="0.01" name="cena" value="<?= $izdelek['cena'] ?>"><br>

                <label>Zaloga:</label>
                <input type="number" name="zaloga" value="<?= $izdelek['zaloga'] ?>"><br>

                <button type="submit" name="shrani">Shrani spremembe</button>
            </form>
        </section>

        <section>
            <form method="post">
                <button type="submit" name="odstrani" style="background-color: red; color: white;">
                    Odstrani izdelek
                </button>
            </form>
        </section>
    </main>
</body>
</html>
