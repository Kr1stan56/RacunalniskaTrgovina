<?php
require_once 'baza.php';
include_once 'session.php';

$izbrana_kategorija = null;
$iskalnik = null;

if (isset($_GET['kategorija']) && $_GET['kategorija'] !== '' && $_GET['kategorija'] !== 'vse') {
    $izbrana_kategorija = intval($_GET['kategorija']);
}

if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $iskalnik = '%' . trim($_GET['q']) . '%';
}
if ($iskalnik && $izbrana_kategorija !== null) {
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM izdelek 
        WHERE (ime LIKE ? OR opis LIKE ?) AND id_ka = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $iskalnik, $iskalnik, $izbrana_kategorija);
} elseif ($iskalnik) {
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM izdelek 
        WHERE ime LIKE ? OR opis LIKE ?");
    mysqli_stmt_bind_param($stmt, "ss", $iskalnik, $iskalnik);
} elseif ($izbrana_kategorija !== null) {
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM izdelek 
        WHERE id_ka = ?");
    mysqli_stmt_bind_param($stmt, "i", $izbrana_kategorija);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM izdelek");
}

mysqli_stmt_execute($stmt);
$rezultat = mysqli_stmt_get_result($stmt);
$izdelki = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$stmt_kat = mysqli_prepare($conn, "SELECT * FROM kategorije");
mysqli_stmt_execute($stmt_kat);
$rezultat_kat = mysqli_stmt_get_result($stmt_kat);
$kategorije = mysqli_fetch_all($rezultat_kat, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_kat);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izdelki | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/partials.css">
		<link rel="stylesheet" href="css/izdelki.css">
    <link rel="stylesheet" href="css/overwrite.css">
</head>
<body>
    <?php include 'partials/sidebar.php'; ?>
    
    <main class="glavna-vsebina">
		<h1 class="naslov-strani" >Naši izdelki</h1>
 

        <div class="mreza-izdelkov">
            <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
                <div class="kartica-izdelka">
                    <img src="images/ADD_NEW.png" alt="ADD NEW" class="slika-izdelka">
                    <div class="telo-kartice">
                        <h3 class="naslov-izdelka">IME IZDELKA</h3>
                        <p class="opis-izdelka">OPIS IZDELKA</p>
                    </div>
                    <div class="noga-kartice">
                        <div class="sekcija-cene">
                            <span class="cena">CENA</span>
                            <a href="dodaj_izdelek.php" class="gumb-kosarica"><i>DODAJ IZDELEK</i></a>
                        </div>
                        <small class="zaloga">Na zalogi: ŠT ZALOGE</small>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($izdelki as $izdelek): ?>
                <div class="kartica-izdelka">
                    <img src="<?= $izdelek['slika'] ?>" alt="<?=$izdelek['ime'] ?>" class="slika-izdelka">
                    <div class="telo-kartice">
                        <h3 class="naslov-izdelka"><?=$izdelek['ime'] ?></h3>
                        <p class="opis-izdelka"><?=$izdelek['opis'] ?></p>
                    </div>
					
                    <div class="noga-kartice">
                        <div class="sekcija-cene">
                            <span class="cena"><?=  $izdelek['cena'] ?> €</span>
							<form method="post" action="kosarica.php">
								<input type="hidden" name="id_izdelka" value="<?= $izdelek['id_i'] ?>">
								<button type="submit" name="dodaj_v_kosarico" class="gumb-kosarica">Dodaj</button>
							</form>

                            <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
                                <a href="uredi_izdelek.php?id=<?= $izdelek['id_i'] ?>" class="gumb-kosarica" style="background-color: white; color: black;">
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
