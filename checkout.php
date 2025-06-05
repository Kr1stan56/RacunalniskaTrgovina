<?php
include_once 'session.php';
require_once 'baza.php';

if (!isset($_SESSION['prijavljen']) || $_SESSION['prijavljen'] !== true) {
    header("Location: login.php");
    exit();
}

$id_u = $_SESSION['id_uporabnika'];

$stmt = $conn->prepare("SELECT id_k FROM uporabniki WHERE id_u = ?");
$stmt->bind_param("i", $id_u);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && isset($row['id_k'])) {
    $id_kosarice = $row['id_k'];
} else {
    $id_kosarice = null;
}

$izdelki = [];
$skupnaCena = 0;

if ($id_kosarice !== null) {
    $stmt = $conn->prepare("
        SELECT pk.kolicina, pk.cena_ob_nakupu, i.ime, i.slika 
        FROM postavke_kosarice pk
        JOIN izdelek i ON pk.id_i = i.id_i
        WHERE pk.id_k = ?");
    $stmt->bind_param("i", $id_kosarice);
    $stmt->execute();
    $result = $stmt->get_result();
    $izdelki = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($izdelki as $izdelek) {
        $skupnaCena += $izdelek['kolicina'] * $izdelek['cena_ob_nakupu'];
    }
}

$napake = [];
$uspesno = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nacin_placila = isset($_POST['nacin_placila']) ? $_POST['nacin_placila'] : '';
    $stevilka_kartice = isset($_POST['stevilka_kartice']) ? trim($_POST['stevilka_kartice']) : '';
    $mesec = isset($_POST['mesec']) ? $_POST['mesec'] : '';
    $leto = isset($_POST['leto']) ? $_POST['leto'] : '';
    $cvc = isset($_POST['cvc']) ? trim($_POST['cvc']) : '';
    $naslov_dostave = isset($_POST['naslov_dostave']) ? trim($_POST['naslov_dostave']) : '';

    if (empty($nacin_placila)) {
        $napake[] = "Izbrati morate način plačila.";
    }

    if ($nacin_placila === "kartica") {
        if (empty($stevilka_kartice)) {
            $napake[] = "Številka kartice ni vpisana.";
        }
        if (empty($mesec) || empty($leto)) {
            $napake[] = "Datum poteka kartice ni vpisan.";
        }
        if (empty($cvc)) {
            $napake[] = "CVC ni vpisan.";
        }
    }

    if (empty($naslov_dostave)) {
        $napake[] = "Naslov dostave je obvezen.";
    }

    if (empty($napake)) {
        $uspesno = true;
        // Tu lahko dodaš kodo za shranjevanje naročila
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Checkout – Zaključi nakup</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
<main class="uredi-izdelek">
    <form action="kosarica.php" method="get" style="margin-bottom: 20px;">
        <button type="submit">← Nazaj v košarico</button>
    </form>

    <h1>Zaključi nakup</h1>

    <div>
        <h2>Izdelki v košarici</h2>
        <?php if (empty($izdelki)) { ?>
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
                            <?php echo number_format($izdelek['kolicina'] * $izdelek['cena_ob_nakupu'], 2); ?> €
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
        <p>Skupna cena: <strong><?php echo number_format($skupnaCena, 2); ?> €</strong></p>

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
                <textarea name="naslov_dostave" required><?php
                    if (isset($_POST['naslov_dostave'])) {
                        echo $_POST['naslov_dostave'];
                    } elseif (isset($_SESSION['naslov'])) {
                        echo $_SESSION['naslov'];
                    }
                ?></textarea>
            </div>

            <div>
                <h2>Način plačila</h2>
                <label>
                    <input type="radio" name="nacin_placila" value="povzetje" 
                        <?php if (isset($_POST['nacin_placila']) && $_POST['nacin_placila'] === 'povzetje')  ?>>
                    Plačilo ob prevzemu (povzetje)
                </label><br>
                <label>
                    <input type="radio" name="nacin_placila" value="kartica" 
                        <?php if (isset($_POST['nacin_placila']) && $_POST['nacin_placila'] === 'kartica') ?>>
                    Plačilo s kartico
                </label>
            </div>

            <div id="podrobnosti-kartice" >
                <h2>Podatki o kartici</h2>
                <label>Številka kartice:
                    <input type="text" name="stevilka_kartice" 
                           value="<?php if (isset($_POST['stevilka_kartice'])) { echo $_POST['stevilka_kartice']; } ?>" 
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
