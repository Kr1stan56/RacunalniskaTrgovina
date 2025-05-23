<?php
// Povezava z bazo
require_once 'baza.php';

// Preveri, ali je izbrana kategorija (prek GET parametra)
$izbrana_kategorija = isset($_GET['kategorija']) ? intval($_GET['kategorija']) : null;

// Pridobi izdelke iz baze - ce je izbrana kategorija, prikazi vse izdelke iz te kategorije
if ($izbrana_kategorija) {
    $stmt = $conn->prepare("SELECT * FROM izdelek WHERE id_ka = ?");
    $stmt->bind_param("i", $izbrana_kategorija);
} else {
    // Drugace prikazi prvih 5 izdelkov iz vsake kategorije
    $stmt = $conn->prepare("
        SELECT * FROM (
            SELECT *, ROW_NUMBER() OVER (PARTITION BY id_ka ORDER BY id_i) AS row_num 
            FROM izdelek
        ) AS temp WHERE row_num <= 5
    ");
}
$stmt->execute();
$izdelki = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Pridobi seznam vseh kategorij (za sidebar ali filter)
$stmt = $conn->prepare("SELECT * FROM kategorije");
$stmt->execute();
$kategorije = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izdelki | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/style15.css">
</head>
<body>
    <?php include 'partials/sidebar.php'; ?>

    <main class="glavna-vsebina">
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
                                <i class=""></i>
                            </button>
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