<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_uporabnika'])) {
    header("Location: login.php");
    exit;
}
$napaka = [];

$id_u = $_SESSION['id_uporabnika'];

$stmt = mysqli_prepare($conn, "SELECT id_k FROM uporabniki WHERE id_u = ?");
mysqli_stmt_bind_param($stmt, "i", $id_u);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if ($row && $row['id_k']) {
    $id_kosarice = $row['id_k'];
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO kosarica (datum_ustvarjanja, status) VALUES (CURDATE(), 1)");
    mysqli_stmt_execute($stmt);
	
    //$id_kosarice = mysqli_insert_id($conn);//vrne zadnji vnesen id iz uporabnikove povezave conn
	$stmt = mysqli_prepare($conn, "SELECT MAX(id_k) AS id FROM kosraica");
	$result=mysqli_store_result($stmt);
	$row = mysqli_fetch_assoc($result);
	$id_kosarice = $row['id'];
	
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "UPDATE uporabniki SET id_k = ? WHERE id_u = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_kosarice, $id_u);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (isset($_POST['dodaj_v_kosarico'])) {
    $id_izdelka = $_POST['id_izdelka'];
    $kolicina = 1;

    $stmt = mysqli_prepare($conn, "SELECT id_po_ko, kolicina FROM postavke_kosarice WHERE id_k = ? AND id_i = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_kosarice, $id_izdelka);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        $nova_kolicina = $row['kolicina'] + 1;
        $stmt = mysqli_prepare($conn, "UPDATE postavke_kosarice SET kolicina = ? WHERE id_po_ko = ?");
        mysqli_stmt_bind_param($stmt, "ii", $nova_kolicina, $row['id_po_ko']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT cena FROM izdelek WHERE id_i = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_izdelka);
        mysqli_stmt_execute($stmt);
		
        $res = mysqli_stmt_get_result($stmt);//mysqli_stmt_bind_result($stmt, $cena)
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($row) {
            $cena = $row['cena'];
            $stmt = mysqli_prepare($conn, "INSERT INTO postavke_kosarice (kolicina, cena_ob_nakupu, id_k, id_i) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "idii", $kolicina, $cena, $id_kosarice, $id_izdelka);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $napaka= "Napaka: izdelek ne obstaja.";
            exit;
        }
    }

    header("Location: kosarica.php");
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT pk.kolicina, pk.cena_ob_nakupu, i.ime, i.slika 
                               FROM postavke_kosarice pk
                               JOIN izdelek i ON pk.id_i = i.id_i
                               WHERE pk.id_k = ?");
mysqli_stmt_bind_param($stmt, "i", $id_kosarice);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$izdelki = [];
while ($vrstica = mysqli_fetch_assoc($result)) {
    $izdelki[] = $vrstica;
}

mysqli_stmt_close($stmt);

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
    <link rel="stylesheet" href="css/uredi_dodaj.css?v=1.1">
</head>
<body>
<main class="uredi-izdelek">
    <form action="izdelki.php" method="get" style="margin-bottom: 20px;">
        <button type="submit">← Nazaj na izdelke</button>
    </form>

    <h1>Tvoja košarica</h1>

    <div>
		<?php if ($napaka): ?>
			<div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($napaka) ?></div>
		<?php endif; ?>
        <?php if (!isset($izdelki)): ?>
            <p>Košarica je prazna.</p>
        <?php else: ?>
            <table style="width:100%;">
                <?php foreach ($izdelki as $izdelek): ?>
                    <tr>
                        <td rowspan="2" style="width:110px; padding:8px;">
                            <img src="<?= htmlspecialchars($izdelek['slika']) ?>" alt="Slika" />
                        </td>
                        <td style="font-weight: bold;">
                            <?= htmlspecialchars($izdelek['ime']) ?>
                        </td>
                        <td rowspan="2" style="font-weight: bold;">
                            <?= number_format($izdelek['kolicina'] * $izdelek['cena_ob_nakupu'], 2) ?> €
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
        <p>Skupna cena: <strong><?= number_format($skupnaCena, 2) ?> €</strong></p>
        <form action="checkout.php" method="post">
            <input type="hidden" name="id_k" value="<?= $id_kosarice ?>">
            <button type="submit">Zaključi nakup</button>
        </form>
    </div>
</main>
</body>
</html>
