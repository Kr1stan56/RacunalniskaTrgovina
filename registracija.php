<?php
require_once 'baza.php';
include_once 'session.php'; 

$napaka = '';
$uspeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prijava'])) {
    try {
        $email = $_POST['email'];
        $geslo = $_POST['geslo'];


        $stmt = $conn->prepare("SELECT id_u, ime, priimek, geslo, id_p FROM uporabniki WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $rezultat = $stmt->get_result();
        $uporabnik = $rezultat->fetch_assoc();

        if ($uporabnik) {
			if (sha1($geslo) === $uporabnik['geslo']) {
				$_SESSION['ime'] = $uporabnik['ime'];
				$_SESSION['priimek'] = $uporabnik['priimek'];
				$_SESSION['id_uporabnika'] = $uporabnik['id_u'];
				$_SESSION['id_p'] = $uporabnik['id_p']; // <-- ključna vrstica
				$_SESSION['prijavljen'] = true;

				$uspeh = "Uspešna prijava! Pozdravljeni, " . $_SESSION['ime'] . " " . $_SESSION['priimek'];
				header("Refresh: 1; URL=index.php");
			}else {
                throw new Exception("Napačno geslo");
            }
        } else {
            throw new Exception("Uporabnik s tem e-poštnim naslovom ne obstaja");
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
    <link rel="stylesheet" href="css/login.css">
	
</head>
<body>
    <div class="prijavni-okvir">
        <h1 class="naslov">Registracija</h1>

        <?php if ($napaka): ?>
            <div class="napaka"><?= htmlspecialchars($napaka) ?></div>
        <?php endif; ?>

        <?php if ($uspeh): ?>
            <div class="uspeh"><?= htmlspecialchars($uspeh) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="vnosna-skupina">
                <label for="ime">Ime:</label>
                <input type="text" id="ime" name="ime" required>
            </div>

            <div class="vnosna-skupina">
                <label for="priimek">Priimek:</label>
                <input type="text" id="priimek" name="priimek" required>
            </div>

            <div class="vnosna-skupina">
                <label for="email">E-pošta:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="vnosna-skupina">
                <label for="geslo">Geslo:</label>
                <input type="password" id="geslo" name="geslo" required>
            </div>

            <div class="vnosna-skupina">
                <label for="naslov">Naslov:</label>
                <input type="text" id="naslov" name="naslov" required>
            </div>

            <button type="submit" name="registracija" class="gumb">Registriraj se</button>
        </form>

        <div class="povezave">
            <a href="login.php">Že imate račun? Prijavite se</a>
        </div>
		<hr>
		<form action="index.php" method="get" >
            <button type="submit" class="gumb" style="background-color:gray;">← Nazaj na izdelke</button>
        </form>
    </div>
</body>
</html>
