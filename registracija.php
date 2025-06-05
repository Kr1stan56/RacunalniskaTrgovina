<?php
require_once 'baza.php';
session_start();

$napaka = [];
$uspeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registracija'])) {
    try {
        $ime = $_POST['ime'];
        $priimek = $_POST['priimek'];
        $email = $_POST['email'];
        $geslo = $_POST['geslo'];
        $naslov = $_POST['naslov'];

        if (empty($ime) || empty($priimek) || empty($email) || empty($geslo) || empty($naslov)) {
            throw new Exception("Vsa polja so obvezna.");
        }

        $stmt = $conn->prepare("SELECT id_u FROM uporabniki WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $rez = $stmt->get_result();
        if ($rez->num_rows > 0) {
            $napaka[] = ("E-pošta je že v uporabi.");
        }

        $hashed_geslo = sha1($geslo);

        $id_p = 1;

        $stmt = $conn->prepare("INSERT INTO uporabniki (ime, priimek, email, geslo, naslov, datum_registracije, id_p)
                                VALUES (?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("sssssi", $ime, $priimek, $email, $hashed_geslo, $naslov, $id_p);

        if ($stmt->execute()) {
            $uspeh = "Registracija uspešna! Lahko se prijavite.";
            header("Refresh: 3; URL=login.php");
        } else {
            throw new Exception("Napaka pri registraciji.");
        }

    } catch (Exception $e) {
        $napaka = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
    <div class="uredi-izdelek">
        <h1>Registracija</h1>

        <?php if ($napaka): ?>
            <div style="color: red; margin-bottom: 15px;"><?= $napaka ?></div>
        <?php endif; ?>

        <?php if ($uspeh): ?>
            <div style="color: green; margin-bottom: 15px;"><?= $uspeh ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" required>

            <label for="priimek">Priimek:</label>
            <input type="text" id="priimek" name="priimek" required>

            <label for="email">E-pošta:</label>
            <input type="email" id="email" name="email" required>

            <label for="geslo">Geslo:</label>
            <input type="password" id="geslo" name="geslo" required>

            <label for="naslov">Naslov:</label>
            <input type="text" id="naslov" name="naslov" required>

            <button type="submit" name="registracija">Registriraj se</button>
        </form>

        <div class="povezave">
            <a href="login.php">Že imate račun? Prijavite se</a>
        </div>
        <hr>
        <form action="index.php" method="get">
            <button type="submit" style="background-color:gray;">← Nazaj na izdelke</button>
        </form>
    </div>
</body>
</html>

