	<?php
	require_once 'baza.php';
	include_once 'session.php';

	if (!isset($_SESSION['id_uporabnika'])) {
		header("Location: login.php");
		exit;
	}

	$id_u = $_SESSION['id_uporabnika'];

	$stmt = $conn->prepare("SELECT id_k FROM uporabniki WHERE id_u = ?");
	$stmt->bind_param("i", $id_u);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();



	if ($row && $row['id_k']) {
		$id_kosarice = $row['id_k'];/*shrani id kaoasraice*/

	} else {
		$stmt = $conn->prepare("INSERT INTO kosarica (datum_ustvarjanja, status) VALUES (CURDATE(), 1)");
		$stmt->execute();
		$id_kosarice = $conn->insert_id;/*vrna zadn id insertane kosarice*/


		/*upradata uporabnikov id kosarice */
		$stmt = $conn->prepare("UPDATE uporabniki 
								SET id_k = ? 
								WHERE id_u = ?");
								
		$stmt->bind_param("ii", $id_kosarice, $id_u);
		$stmt->execute();
	}

	/*sprejme izdelek iz izbranega elementa iz izdelkov */
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_v_kosarico'])) {
		$id_izdelka = intval($_POST['id_izdelka']);
		$kolicina = 1;

		$stmt = $conn->prepare("SELECT id_po_ko, kolicina FROM postavke_kosarice WHERE id_k = ? AND id_i = ?");
		$stmt->bind_param("ii", $id_kosarice, $id_izdelka);
		$stmt->execute();
		$res = $stmt->get_result();


	/*preveri ali je izdelek e v kosarici potem doda kolincino*/

		if ($row = $res->fetch_assoc()) {
			$nova_kolicina = $row['kolicina'] + 1;
			$stmt = $conn->prepare("UPDATE postavke_kosarice 
									SET kolicina = ? 
									WHERE id_po_ko = ?");
			$stmt->bind_param("ii", $nova_kolicina, $row['id_po_ko']);
			$stmt->execute();
			
		} else {
			$stmt = $conn->prepare("SELECT cena 
									FROM izdelek 
									WHERE id_i = ?");
			$stmt->bind_param("i", $id_izdelka);
			$stmt->execute();
			$res = $stmt->get_result();
			$row = $res->fetch_assoc();

			if ($row) {
				$cena = $row['cena'];
				$stmt = $conn->prepare("INSERT INTO postavke_kosarice (kolicina, cena_ob_nakupu, id_k, id_i) 
										VALUES (?, ?, ?, ?)");
				$stmt->bind_param("idii", $kolicina, $cena, $id_kosarice, $id_izdelka);
				$stmt->execute();
			} else {
				echo "Napaka: izdelek ne obstaja.";
				exit;
			}
		}

		header("Location: kosarica.php");
		exit;
	}

	$stmt = $conn->prepare("SELECT pk.kolicina, pk.cena_ob_nakupu, i.ime, i.slika 
							FROM postavke_kosarice pk
							JOIN izdelek i ON pk.id_i = i.id_i
							WHERE pk.id_k = ?");
							
	$stmt->bind_param("i", $id_kosarice);
	$stmt->execute();
	$result = $stmt->get_result();
	$izdelki = $result->fetch_all(MYSQLI_ASSOC);

	$skupnaCena = 0;
	foreach ($izdelki as $izdelek) {
		$skupnaCena += $izdelek['kolicina'] * $izdelek['cena_ob_nakupu'];
	}
	
	
	$_SESSION['id_k'] = $id_kosarice;

	?>
	<!DOCTYPE html>
	<html lang="sl">
	<head>
		<meta charset="UTF-8">
		<title>Košarica</title>
		<link rel="stylesheet" href="css/uredi_dodaj.css">
	</head>
	<body>
	<main class="uredi-izdelek">
		<form action="izdelki.php" method="get" style="margin-bottom: 20px;">
				<button type="submit">← Nazaj na izdelke</button>
		</form>

		<h1>Tvoja košarica</h1>

		<div>
			<?php if (empty($izdelki)): ?>
				<p>Košarica je prazna.</p>
			<?php else: ?>
				<table style="width:100%;">
					<?php foreach ($izdelki as $izdelek): ?>
						<tr>
							<td rowspan="2" style="width:110px; padding:8px;">
								<img src="<?= htmlspecialchars($izdelek['slika']) ?>" alt="Slika" />
							</td>
							<td style="font-weight: bold;">
								<?= $izdelek['ime'] ?>
							</td>
							<td rowspan="2" style="font-weight: bold;">
								<?= $izdelek['kolicina'] * $izdelek['cena_ob_nakupu'] ?> €
							</td>
						</tr>
						<tr>
							<td style="padding: 8px; color: #555;">
								Količina: <?= $izdelek['kolicina'] ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>

		<div style="text-align: center;">
			<h2>Povzetek</h2>
			<p>Skupna cena: <strong><?= $skupnaCena ?> €</strong></p>
			<form action="checkout.php" method="post">
				<input type="hidden" name="id_k" value="<?= $id_kosarice ?>">
				
				<button type="submit">Zaključi nakup</button>
			</form>

		</div>
	</main>
	</body>
	</html>
		