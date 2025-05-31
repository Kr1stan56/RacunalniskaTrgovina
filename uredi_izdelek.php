<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$id_izdelka = $_GET['id'] ?? null;
if (!$id_izdelka) {
    header("Location: izdelki.php");
    exit;
}

$kategorije = [];
$result = $conn->query("SELECT id_ka, ime FROM kategorije ORDER BY ime");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kategorije[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['shrani'])) {
        $ime = $_POST['ime'] ?? '';
        $opis = $_POST['opis'] ?? '';
        $cena = floatval($_POST['cena'] ?? 0);
        $zaloga = intval($_POST['zaloga'] ?? 0);
        $kategorija = intval($_POST['kategorija'] ?? 0);

        $stmt = $conn->prepare("UPDATE izdelek SET ime = ?, opis = ?, cena = ?, zaloga = ?, id_ka = ? WHERE id_i = ?");
        $stmt->bind_param("ssdiii", $ime, $opis, $cena, $zaloga, $kategorija, $id_izdelka);
        $stmt->execute();

        header("Location: uredi_izdelek.php?id=" . urlencode($id_izdelka));
        exit;
    }

    if (isset($_POST['zamenjaj_sliko'])) {
        $url_slike = trim($_POST['url_slike'] ?? '');
        $stmt = $conn->prepare("UPDATE izdelek SET slika = ? WHERE id_i = ?");
        $stmt->bind_param("si", $url_slike, $id_izdelka);
        $stmt->execute();

        header("Location: uredi_izdelek.php?id=" . urlencode($id_izdelka));
        exit;
    }

    if (isset($_POST['odstrani_sliko'])) {
        $stmt = $conn->prepare("UPDATE izdelek SET slika = NULL WHERE id_i = ?");
        $stmt->bind_param("i", $id_izdelka);
        $stmt->execute();

        header("Location: uredi_izdelek.php?id=" . urlencode($id_izdelka));
        exit;
    }

    if (isset($_POST['odstrani'])) {
        $stmt = $conn->prepare("DELETE FROM izdelek WHERE id_i = ?");
        $stmt->bind_param("i", $id_izdelka);
        $stmt->execute();

        header("Location: izdelki.php");
        exit;
    }
}

// Naloži podatke o izdelku
$stmt = $conn->prepare("SELECT * FROM izdelek WHERE id_i = ?");
$stmt->bind_param("i", $id_izdelka);
$stmt->execute();
$izdelek = $stmt->get_result()->fetch_assoc();

if (!$izdelek) {
    header("Location: izdelki.php");
    exit;
}
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
        <form action="izdelki.php" method="get" style="margin-bottom: 20px;">
            <button type="submit">← Nazaj na izdelke</button>
        </form>

        <h1>Urejanje: <?= htmlspecialchars($izdelek['ime']) ?></h1>

        <section>
            <h2>Slika</h2>
            <?php if ($izdelek['slika']): ?>
                <img src="<?= htmlspecialchars($izdelek['slika']) ?>" width="200" alt="Slika izdelka"><br>
            <?php else: ?>
                <p>Ni slike.</p>
            <?php endif; ?>
            <form method="post" style="margin-top: 10px;">
                <label>Vnesi URL slike:</label><br>
                <input type="text" name="url_slike" value="<?= htmlspecialchars($izdelek['slika'] ?? '') ?>" style="width: 100%;"><br><br>
                <button type="submit" name="zamenjaj_sliko">Shrani URL slike</button>
                <button type="submit" name="odstrani_sliko">Odstrani sliko</button>
            </form>
        </section>

        <section>
            <h2>Splošno</h2>
            <form method="post">
                <label>Ime:</label><br>
                <input type="text" name="ime" value="<?= htmlspecialchars($izdelek['ime']) ?>" required><br><br>

                <label>Opis:</label><br>
                <textarea name="opis" rows="4" cols="50" required><?= htmlspecialchars($izdelek['opis']) ?></textarea><br><br>

                <label>Cena (€):</label><br>
                <input type="number" step="0.01" name="cena" value="<?= htmlspecialchars($izdelek['cena']) ?>" required><br><br>

                <label>Zaloga:</label><br>
                <input type="number" name="zaloga" value="<?= htmlspecialchars($izdelek['zaloga']) ?>" required><br><br>

                <label>Kategorija:</label><br>
                <select name="kategorija" required>
                    <option value="" disabled>Izberi kategorijo</option>
                    <?php foreach ($kategorije as $kat): ?>
                        <option value="<?= $kat['id_ka'] ?>" <?= ($kat['id_ka'] == $izdelek['id_ka']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['ime']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>

                <button type="submit" name="shrani">Shrani spremembe</button>
            </form>
        </section>

        <section style="margin-top: 30px;">
            <form method="post">
                <button type="submit" name="odstrani" style="background-color: red; color: white;" onclick="return confirm('Ali ste prepričani, da želite odstraniti ta izdelek?');">
                    Odstrani izdelek
                </button>
            </form>
        </section>
    </main>
</body>
</html>
