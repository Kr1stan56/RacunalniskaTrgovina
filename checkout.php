<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'session.php';
require_once 'baza.php'; 

if (!isset($_SESSION['prijavljen']) || $_SESSION['prijavljen'] !== true) {
    header("Location: login.php");
    exit();
}

$id_u = $_SESSION['id_uporabnika'];

$sql = "SELECT id_k FROM uporabniki WHERE id_u = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_u);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (isset($row['id_k'])) {
    $id_kosarice = $row['id_k'];
} else {
    $id_kosarice = null;
}

$izdelki = [];
$skupnaCena = 0;

if ($id_kosarice !== null) {
    $sql = "
        SELECT pk.kolicina, pk.cena_ob_nakupu, i.ime, i.slika 
        FROM postavke_kosarice pk
        JOIN izdelek i ON pk.id_i = i.id_i
        WHERE pk.id_k = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_kosarice);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
	
	$izdelki = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$izdelki[] = $row;
	}

    foreach ($izdelki as $izdelek) {
        $skupnaCena += $izdelek['kolicina'] * $izdelek['cena_ob_nakupu'];
    }
}

$napake = [];
$uspesno = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $napake = [];

    if (empty($_POST['nacin_placila'])) {
        $napake[] = "Izbrati morate način plačila.";
    } else {
        $nacin_placila = $_POST['nacin_placila'];
    }

    if (isset($nacin_placila) && $nacin_placila === "kartica") {
        if (empty($_POST['stevilka_kartice'])) {
            $napake[] = "Številka kartice ni vpisana.";
        }
        if (empty($_POST['mesec']) || empty($_POST['leto'])) {
            $napake[] = "Datum poteka kartice ni vpisan.";
        }
        if (empty($_POST['cvc'])) {
            $napake[] = "CVC ni vpisan.";
        }
    }

    if (empty($_POST['naslov_dostave'])) {
        $napake[] = "Naslov dostave je obvezen.";
    }else{
		$naslov_dostave = $_POST['naslov_dostave'];
	}

    if (empty($napake)) {
        $uspesno = true;
    } else {
        $uspesno = false;
    }


/*-------empty/isset*/
    if ($uspesno) {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO narocila (id_u, nacin_placila, naslov_dostave, skupna_cena)
            VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issd", $id_u, $nacin_placila, $naslov_dostave, $skupnaCena);

        if (mysqli_stmt_execute($stmt)) {
			
			$result = mysqli_query($conn, "SELECT MAX(id_n) AS id FROM narocila");
			$row = mysqli_fetch_assoc($result);
			$id_narocila = $row['id'];

            $stmt_postavka = mysqli_prepare($conn, "INSERT INTO postavke_narocila (id_n, id_i, kolicina, cena_ob_nakupu) VALUES (?, ?, ?, ?)");

            foreach ($izdelki as $izdelek) {
                $stmt_id = mysqli_prepare($conn, "SELECT id_i FROM izdelek WHERE ime = ?");
				
                mysqli_stmt_bind_param($stmt_id, "s", $izdelek['ime']);
                mysqli_stmt_execute($stmt_id);
				
                $result_id = mysqli_stmt_get_result($stmt_id);
                $row_id = mysqli_fetch_assoc($result_id);
				
				if(!isset($row_id['id_i'])) {
					$id_izdelka = $row_id['id_i'];
				} else {
					$id_izdelka = null;
				}

                if ($id_izdelka) {
                    mysqli_stmt_bind_param($stmt_postavka, "iiid", $id_narocila, $id_izdelka, $izdelek['kolicina'], $izdelek['cena_ob_nakupu']);
                    mysqli_stmt_execute($stmt_postavka);

                    $sql_zaloga = "
                        UPDATE izdelek
                        SET zaloga = zaloga - ?
                        WHERE id_i = ?";
                    $stmt_zaloga = mysqli_prepare($conn, $sql_zaloga);
                    mysqli_stmt_bind_param($stmt_zaloga, "ii", $izdelek['kolicina'], $id_izdelka);
                    mysqli_stmt_execute($stmt_zaloga);
                }
            }

            $stmt = mysqli_prepare($conn, "DELETE FROM postavke_kosarice WHERE id_k = ?");
            mysqli_stmt_bind_param($stmt, "i", $id_kosarice);
            mysqli_stmt_execute($stmt);

            $izdelki = [];
            header("Refresh: 2; URL=index.php");

        } else {
            $napake[] = "Napaka pri vnosu naročila.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Checkout – Zaključi nakup</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css?v=1.1">
</head>
<body>
<main class="uredi-izdelek">
    <form action="kosarica.php" method="get" style="margin-bottom: 20px;">
        <button type="submit">← Nazaj v košarico</button>
    </form>

    <h1>Zaključi nakup</h1>

    <div>
        <h2>Izdelki v košarici</h2>
        <?php if (!isset($izdelki)) { ?>
            <p>Košarica je prazna.</p>
        <?php } else { ?>
            <table style="width: 100%; border-collapse: collapse;">
                <?php foreach ($izdelki as $izdelek) { ?>
                    <tr>
                        <td rowspan="2" style="width: 100px; padding: 8px;">
                            <img src="<?php echo $izdelek['slika']; ?>" alt="Slika izdelka" />
                        </td>
                        <td style="font-weight: bold; padding: 8px;">
                            <?php echo $izdelek['ime']; ?>
                        </td>
                        <td rowspan="2" style="font-weight: bold; padding: 8px; text-align: right;">
                            <?php echo $izdelek['kolicina'] * $izdelek['cena_ob_nakupu']; ?> €
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; color: #555;">
                            Količina: <?php echo (int)$izdelek['kolicina']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>

    <div>
        <h2>Povzetek</h2>
        <p>Skupna cena: <strong><?php echo $skupnaCena; ?> €</strong></p>

        <?php if (!empty($napake)) { ?>
            <div style="color: red;">
                <ul>
                    <?php foreach ($napake as $napaka) { ?>
                        <li><?php echo $napaka; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } elseif ($uspesno) { ?>
            <div style="color: green;">
                Nakup uspešno zaključen.
            </div>
        <?php } ?>

        <form action="checkout.php" method="post">
            <div>
                <h2>Podatki o uporabniku</h2>
                <p><strong>Ime in priimek:</strong> <?php echo $_SESSION['polno_ime']; ?></p>
            </div>

            <div>
                <h2>Naslov dostave</h2>
                <input type="text" name="naslov_dostave" required></input>
            </div>

            <div>
                <h2>Način plačila</h2>
                <label>
					<input type="radio" name="nacin_placila" value="povzetje"
						<?php if (isset($_POST['nacin_placila']) && $_POST['nacin_placila'] === 'povzetje')
						{
							echo 'checked';
						} ?>>
					Plačilo ob prevzemu (povzetje)
				</label><br>

				<label>
					<input type="radio" name="nacin_placila" value="kartica"
						<?php if (isset($_POST['nacin_placila']) && $_POST['nacin_placila'] === 'kartica')
						{
						echo 'checked';
						} ?>>
							
					Plačilo s kartico
				</label>

            </div>

            <div id="podrobnosti-kartice" >
                <h2>Podatki o kartici</h2>
                <label>Številka kartice:
                    <input type="text" name="stevilka_kartice" 
                           value="<?php if (isset($_POST['stevilka_kartice'])) 
						   { 
						   echo $_POST['stevilka_kartice']; 
						   } ?>" 
                           placeholder="XXXX XXXX XXXX XXXX">
                </label>
                <label>Veljavnost (mesec/leto):<br>
                    <input type="number" name="mesec" 
                           value="<?php if (isset($_POST['mesec'])) { echo $_POST['mesec']; } ?>" 
                           placeholder="MM" >
                    
                    <input type="number" name="leto" 
                           value="<?php if (isset($_POST['leto'])) { echo $_POST['leto']; } ?>" 
                           placeholder="YYYY">
                </label>
                <label>CVC:
                    <input type="text" name="cvc" 
                           value="<?php if (isset($_POST['cvc'])) { echo $_POST['cvc']; } ?>" 
                           placeholder="XXX" maxlength="4" >
                </label>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit">Zaključi nakup</button>
            </div>
        </form>
    </div>
</main>


</body>
</html>
