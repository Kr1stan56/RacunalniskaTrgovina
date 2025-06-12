<?php
require_once 'baza.php';
include_once 'session.php';




$kat = '';
$niz = '';

if (!empty($_GET['kategorija'])&& $_GET['kategorija'] !== 'vse') {
    $kat = $_GET['kategorija'];
}

if (!empty($_GET['query'])) {
    $niz = '%' . trim($_GET['query']) . '%';
}else{$niz='';}

$stmt = mysqli_prepare($conn, "
    SELECT * FROM izdelek 
    WHERE (? = '' OR ime LIKE ? OR opis LIKE ?) AND (? = 0 OR id_ka = ?)");
mysqli_stmt_bind_param($stmt, "ssssi", $niz, $niz, $niz, $kat, $kat);


$izdelki = [];
mysqli_stmt_execute($stmt);
$rezultat = mysqli_stmt_get_result($stmt);
while ($vrstica = mysqli_fetch_assoc($rezultat)) {
    $izdelki[] = $vrstica;
}
mysqli_stmt_close($stmt);


?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Izdelki | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/partials.css?v=1.1">
	<link rel="stylesheet" href="css/izdelki.css?v=1.1">
    <link rel="stylesheet" href="css/overwrite.css?v=1.1">
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
                        <p class="zaloga">Na zalogi: ŠT ZALOGE</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($izdelki as $izdelek): ?>
                <div class="kartica-izdelka">
                    <img src="<?= htmlspecialchars($izdelek['slika']) ?>" alt="<?=htmlspecialchars($izdelek['ime']) ?>" class="slika-izdelka">
                    <div class="telo-kartice">
                        <h3 class="naslov-izdelka"><?=htmlspecialchars($izdelek['ime']) ?></h3>
                        <p class="opis-izdelka"><?=htmlspecialchars($izdelek['opis']) ?></p>
                    </div>
					
                    <div class="noga-kartice">
                        <div class="sekcija-cene">
                            <span class="cena"><?=  htmlspecialchars($izdelek['cena']) ?> €</span>
							<form method="post" action="kosarica.php">
								<input type="hidden" name="id_izdelka" value="<?= htmlspecialchars($izdelek['id_i']) ?>">
								<button type="submit" name="dodaj_v_kosarico" class="gumb-kosarica">Dodaj</button>
							</form>

                            <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
                                <a href="uredi_izdelek.php?id=<?= htmlspecialchars($izdelek['id_i']) ?>" class="gumb-kosarica" style="background-color: white; color: black;">
                                    <i>UREDI</i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <small class="zaloga">Na zalogi: <?= htmlspecialchars($izdelek['zaloga']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
