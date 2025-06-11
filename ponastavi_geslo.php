<?php
require_once 'baza.php';

$napaka = '';
$uspeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$email = $_POST['email'] ?? '';
    //$geslo = $_POST['geslo'] ?? '';
    //$ponovno = $_POST['ponovno'] ?? '';
	
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	} else {
		$email = '';
	}

	if (isset($_POST['geslo'])) {
		$geslo = $_POST['geslo'];
	} else {
		$geslo = '';
	}

	if (isset($_POST['ponovno'])) {
		$ponovno = $_POST['ponovno'];
	} else {
		$ponovno = '';
	}


    if ($geslo !== $ponovno) {
        $napaka = "Gesli se ne ujemata.";
    } else {
        $hash = sha1($geslo);

        $stmt = mysqli_prepare($conn, "UPDATE uporabniki SET geslo = ? WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $hash, $email);

        $uspeh = "Geslo uspešno posodobljeno.";
        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="sl">
<head>
	<meta charset="UTF-8">
	<title>Sprememba gesla</title>
	<link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>

<div class="uredi-izdelek">
    <h1>Spremeni geslo</h1>

    <?php if ($napaka): ?>
        <div class="napaka"><?= htmlspecialchars($napaka) ?></div>
    <?php endif; ?>

    <?php if ($uspeh): ?>
        <div class="uspeh"><?= htmlspecialchars($uspeh) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="vnosna-skupina">
            <label for="email">E-pošta:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="vnosna-skupina">
            <label for="geslo">Novo geslo:</label>
            <input type="password" id="geslo" name="geslo" required>
        </div>

        <div class="vnosna-skupina">
            <label for="ponovno">Ponovno vpiši geslo:</label>
            <input type="password" id="ponovno" name="ponovno" required>
        </div>

        <button type="submit">Spremeni geslo</button>
    </form>

    <div class="povezave">
        <a href="login.php">← Nazaj na prijavo</a>
    </div>
</div>

</body>
</html>
