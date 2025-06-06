<?php
require_once 'baza.php';
session_start();

$napaka = [];
$uspeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registracija'])) {
    try {
        $ime = trim($_POST['ime']);
        $priimek = trim($_POST['priimek']);
        $email = trim($_POST['email']);
        $geslo = $_POST['geslo'];
        $naslov = trim($_POST['naslov']);

        if (empty($ime) || empty($priimek) || empty($email) || empty($geslo) || empty($naslov)) {
            throw new Exception("Vsa polja so obvezna.");
        }

        // Preveri, ali e-pošta že obstaja
        $stmt = mysqli_prepare($conn, "SELECT id_u FROM uporabniki WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $napaka[] = "E-pošta je že v uporabi.";
        }

        mysqli_stmt_close($stmt);

        if (empty($napaka)) {
            $hashed_geslo = sha1($geslo);
            $id_p = 1;

            $stmt = mysqli_prepare($conn, "INSERT INTO uporabniki (ime, priimek, email, geslo, naslov, datum_registracije, id_p) 
                                           VALUES (?, ?, ?, ?, ?, NOW(), ?)");
            mysqli_stmt_bind_param($stmt, "sssssi", $ime, $priimek, $email, $hashed_geslo, $naslov, $id_p);

            if (mysqli_stmt_execute($stmt)) {
                $uspeh = "Registracija uspešna! Lahko se prijavite.";
                header("Refresh: 3; URL=login.php");
            } else {
                throw new Exception("Napaka pri registraciji.");
            }

            mysqli_stmt_close($stmt);
        }

    } catch (Exception $e) {
        $napaka[] = $e->getMessage();
    }
}
?>

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

