<?php require_once 'baza.php'; ?>
<!DOCTYPE html>
<html lang="sl">
<head>
	<?php include 'partials/header.php'; ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Domov | Računalniška Trgovina</title>
	<link rel="stylesheet" href="css/style15.css">
</head>
<body class="domaca-stran">

	<main>
		<section class="pozdravni-odsek">
			<h1>Dobrodošli v Računalniški Trgovini</h1>
			<p>Vaša prva postaja za vso računalniško opremo in komponente</p>
			<div class="gumbi-burger">
				<a href="izdelki.php" class="gumb-burger">Oglejte si izdelke</a>
				<a href="kontakt.php" class="gumb-burger">Kontaktirajte nas</a>
			</div>
		</section>

		<section class="prednosti">
			<div class="prednost">
				<i class=""></i>
				<h3>Hitra Dostava</h3>
				<p>Dostava v 24h po vsej Sloveniji</p>
			</div>
			<div class="prednost">
				<i class=""></i>
				<h3>2+1 Garancija</h3>
				<p>Podaljšana garancija za vse izdelke</p>
			</div>
			<div class="prednost">
				<i class=""></i>
				<h3>Strokovno Svetovanje</h3>
				<p>Brezplačno svetovanje naših strokovnjakov</p>
			</div>
		</section>
		<section class="prednosti prednost2">
			<div class="prednost">
				<i class="AKCIJA">AKCIJA</i>
				<h3>AKCIJA</h3>
				<p>Vsi izdelki 10% off</p>
			</div>
			<div class="prednost">
				<i class="AKCIJA">AKCIJA</i>
				<h3>AKCIJA</h3>
				<p>blablabla</p>
			</div>
			<div class="prednost">
				<i class="AKCIJA">AKCIJA</i>
				<h3>AKCIJA</h3>
				<p>blablabla</p>
			</div>
		</section>
	</main>

	<?php include 'partials/footer.php'; ?>
</body>
</html>
