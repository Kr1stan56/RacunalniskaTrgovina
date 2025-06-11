<?php
require_once 'baza.php'; 
include_once 'session.php';

$napaka = '';
$uspeh = '';

if ( isset($_POST['prijava'])) {	
    $email = $_POST['email'] ?? '';
	$geslo = $_POST['geslo'] ?? '';

	$stmt = mysqli_prepare($conn, "SELECT id_u, ime, priimek, geslo, naslov, id_p FROM uporabniki WHERE email = ?");
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$rezultat = mysqli_stmt_get_result($stmt);
	$uporabnik = mysqli_fetch_assoc($rezultat);

	if ($uporabnik) {
		if (sha1($geslo) === $uporabnik['geslo']) {
			$_SESSION['ime'] = $uporabnik['ime'];
			$_SESSION['priimek'] = $uporabnik['priimek'];
			$_SESSION['id_uporabnika'] = $uporabnik['id_u'];
			$_SESSION['ime_kupca'] = $uporabnik['ime'];
			$_SESSION['priimek_kupca'] = $uporabnik['priimek'];
			$_SESSION['polno_ime'] = $uporabnik['ime'] . ' ' . $uporabnik['priimek'];
			$_SESSION['naslov'] = $uporabnik['naslov'];
			$_SESSION['id_p'] = $uporabnik['id_p'];
			$_SESSION['prijavljen'] = true;

			$uspeh = "Uspešna prijava! Pozdravljeni, " . $_SESSION['ime'] . " " . $_SESSION['priimek'];
			header("Refresh: 1; URL=index.php");
		} else {
			$napaka = "Napačno geslo.";
		}
	} else {
		$napaka = "Uporabnik s tem e-poštnim naslovom ne obstaja.";
	}

	mysqli_stmt_close($stmt);

}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Prijava</title>
	<link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>

	<div class="uredi-izdelek">
		<h1>Prijava</h1>

		<?php if ($napaka): ?>
			<div class="napaka"><?= htmlspecialchars($napaka) ?></div>
		<?php endif; ?>

		<?php if ($uspeh): ?>
			<div class="uspeh" style="background: #e8f5e9;" ><?= htmlspecialchars($uspeh) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="vnosna-skupina">
				<label for="email">E-pošta:</label>
				<input type="email" id="email" name="email" required>
			</div>

			<div class="vnosna-skupina">
				<label for="geslo">Geslo:</label>
				<input type="password" id="geslo" name="geslo" required>
			</div>

			<button type="submit" name="prijava">Prijavi se</button>
		</form>

		<div class="povezave" style="margin-top: 20px;">
			<a href="registracija.php">Še nimate računa? Registrirajte se</a><br>
			<a href="ponastavi_geslo.php">Pozabljeno geslo?</a>
		</div>

		<hr>

		<form action="index.php" method="get">
			<button type="submit" style="background-color: gray;">← Nazaj na izdelke</button>
		</form>
	</div>

</body>
</html>
