<?php require_once 'baza.php'; ?>
<?php include_once 'session.php'; ?>

<?php
$sql = "
    SELECT *
    FROM izdelek
    GROUP BY id_ka
    LIMIT 8;
";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $rezultat = mysqli_stmt_get_result($stmt);
	
	$izdelki = [];
	while ($vrstica = mysqli_fetch_assoc($rezultat)) {
		$izdelki[] = $vrstica;
	}
		mysqli_stmt_close($stmt);
} else {
    $izdelki = [];
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domov | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/partials.css">	
    <link rel="stylesheet" href="css/izdelki.css">
</head>
<body>

<main>
    <div class="pozdrav">
        <h1>Dobrodošli v Računalniški Trgovini</h1>
        <p>Vaša prva postaja za vso računalniško opremo in komponente</p>
        <a href="izdelki.php" class="gumb">Oglejte si izdelke</a>
        <a href="kontakt.php" class="gumb">Kontaktirajte nas</a>
    </div>
    <div class="pozdrav-slika"></div>

    <div class="odsek">
        <div class="kartica">
            <h3>Hitra Dostava</h3>
            <p>Dostava v 24h po vsej Sloveniji</p>
        </div>
        <div class="kartica">
            <h3>2+1 Garancija</h3>
            <p>Podaljšana garancija za vse izdelke</p>
        </div>
        <div class="kartica">
            <h3>Strokovno Svetovanje</h3>
            <p>Brezplačno svetovanje naših strokovnjakov</p>
        </div>
    </div>

    <div class="odsek">
        <div class="kartica">
            <p class="akcija">AKCIJA</p>
            <h3>Popusti</h3>
            <p>Vsi izdelki 10% ceneje</p>
        </div>
        <div class="kartica">
            <p class="akcija">AKCIJA</p>
            <h3>Pomladna Ponudba</h3>
            <p>Izdelki po posebnih cenah</p>
        </div>
        <div class="kartica">
            <p class="akcija">AKCIJA</p>
            <h3>Zadnji Kosi</h3>
            <p>Izkoristite popuste do razprodaje</p>
        </div>
    </div>

    <div class="odsek">
        <h1>PRILJUBLJENI IZDELKI</h1>
        <?php foreach ($izdelki as $izdelek): ?>
            <div class="kartica-izdelka">
                <img src="<?= $izdelek['slika'] ?>" alt="<?= $izdelek['ime'] ?>" class="slika-izdelka">
                <div class="telo-kartice">
                    <h3 class="naslov-izdelka"><?= $izdelek['ime'] ?></h3>
                    <p class="opis-izdelka"><?= $izdelek['opis'] ?></p>
                </div>
                <div class="noga-kartice">
                    <div class="sekcija-cene">
                        <span class="cena"><?= $izdelek['cena'] ?> €</span>
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
