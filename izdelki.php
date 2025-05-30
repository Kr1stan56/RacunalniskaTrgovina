<?php
require_once 'baza.php';

$izbrana_kategorija = isset($_GET['kategorija']) ? intval($_GET['kategorija']) : null;

if ($izbrana_kategorija) {
    $stmt = $conn->prepare("SELECT * FROM izdelek WHERE id_ka = ?");
    $stmt->bind_param("i", $izbrana_kategorija);
} else {
    $stmt = $conn->prepare("
        SELECT * FROM (
            SELECT *, ROW_NUMBER() OVER (PARTITION BY id_ka ORDER BY id_i) AS row_num 
            FROM izdelek
        ) AS temp WHERE row_num <= 5
    ");
}
$stmt->execute();
$izdelki = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT * FROM kategorije");
$stmt->execute();
$kategorije = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once 'session.php'; ?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izdelki | Računalniška Trgovina</title>
<link rel="stylesheet" href="css/main.css">
    
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/izdelki.css">

    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/footer.css">
	
	<link rel="stylesheet" href="css/overwrite.css">
</head>
<body>
    <?php include 'partials/sidebar.php'; ?>
		
    <main class="glavna-vsebina">
		<?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
		<a href="dodaj_izdelek.php" class="gumb-kosarica" style="background-color: white;color: black;float:left;">
			<i>DODAJ IZDELEK</i></a>
		<?php endif; ?>

        <h1 class="naslov-strani">Naši izdelki</h1>
		

        <div class="mreza-izdelkov">
            <?php foreach ($izdelki as $izdelek): ?>
                <div class="kartica-izdelka">
                    <img src="<?= htmlspecialchars($izdelek['slika']) ?>" 
                         alt="<?= htmlspecialchars($izdelek['ime']) ?>"
                         class="slika-izdelka">

                    <div class="telo-kartice">
                        <h3 class="naslov-izdelka"><?= htmlspecialchars($izdelek['ime']) ?></h3>
                        <p class="opis-izdelka"><?= htmlspecialchars($izdelek['opis']) ?></p>
                    </div>

                    <div class="noga-kartice">
                        <div class="sekcija-cene">
							<span class="cena"><?= number_format($izdelek['cena'], 2) ?> €</span>

							<button class="gumb-kosarica">
								<i class="">DODAJ</i>
							</button>

							
							<?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
								<a href="uredi_izdelek.php?id=<?= $izdelek['id_i'] ?>" class="gumb-kosarica" style="background-color: white;color: black;">
									<i>UREDI</i>
								</a>
							<?php endif; ?>

						</div>


                        <small class="zaloga">Na zalogi: <?= $izdelek['zaloga'] ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>