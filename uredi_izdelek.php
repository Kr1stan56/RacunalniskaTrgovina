<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$id_izdelka = $_GET['id'] ?? null;
if (!$id_izdelka) {
    header("Location: izdelki.php");
    exit;
}

//najde vse kategorije id ter ime tega izdelka
$kategorije = [];
$stmt = mysqli_prepare($conn, "SELECT id_ka, ime FROM kategorije ORDER BY ime");
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {//eno vrstico posebej shranjuje v rov 
            $kategorije[] = $row;//row se shranjuje v zadnjo mesto v kategorijah
        }
    }
    mysqli_stmt_close($stmt);
} 





if (isset($_POST['shrani'])) {
	
    if (isset($_POST['ime'])) {
        $ime = trim($_POST['ime']);
    } else {
        $ime = '';
    }
    if (isset($_POST['opis'])) {
        $opis = trim($_POST['opis']);
    } else {
        $opis = '';
    }
    if (isset($_POST['cena'])) {
        $cena = trim($_POST['cena']);
    } else {
        $cena = '0';
    }
    if (isset($_POST['zaloga'])) {
        $zaloga = trim($_POST['zaloga']);
    } else {
        $zaloga = '0';
    }
    if (isset($_POST['kategorija'])) {
        $kategorija = trim($_POST['kategorija']);
    } else {
        $kategorija = '0';
    }
	
	$stmt = mysqli_prepare($conn, "
		UPDATE izdelek
		SET ime = ?, opis = ?, cena = ?, zaloga = ?, id_ka = ?
		WHERE id_i = ?
	");
	mysqli_stmt_bind_param($stmt, "ssdiii", $ime, $opis, $cena, $zaloga, $kategorija, $id_izdelka);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	header("Location: uredi_izdelek.php?id=" . $id_izdelka);
	exit;
}



if (isset($_POST['zamenjaj_sliko'])) {
	
	if (isset($_POST['url_slike'])) {
		$url_slike = trim($_POST['url_slike']);
	} else {
		$url_slike = '';
	}
	
	$stmt = $conn->prepare("UPDATE izdelek SET slika = ? WHERE id_i = ?");
	$stmt->bind_param("si", $url_slike, $id_izdelka);
	$stmt->execute();

    mysqli_query($conn, $query);

    header("Location: uredi_izdelek.php?id=" . $id_izdelka);
    exit;
}
if (isset($_POST['odstrani_sliko'])) {
	$stmt = mysqli_prepare($conn, "UPDATE izdelek SET slika = NULL WHERE id_i = ?");
	mysqli_stmt_bind_param($stmt, "i", $id_izdelka);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

    header("Location: uredi_izdelek.php?id=" . $id_izdelka);
    exit;
}
if (isset($_POST['odstrani'])) {
	$stmt = mysqli_prepare($conn, "DELETE FROM izdelek WHERE id_i = ?");
	mysqli_stmt_bind_param($stmt, "i", $id_izdelka);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	header("Location: izdelki.php");
	exit;
}


$result = mysqli_query($conn, "SELECT * FROM izdelek WHERE id_i = $id_izdelka");
$izdelek = mysqli_fetch_assoc($result);

if (!$izdelek) {
    header("Location: izdelki.php");
    exit;
}

if (isset($_POST["submit"]) && isset($_FILES["fileToUpload"])) {
    $kategorija_ime = '';
    foreach ($kategorije as $kat) {
        if ($kat['id_ka'] == $izdelek['id_ka']) {
            $kategorija_ime = $kat['ime'];
            break;
        }
    }

    $target_dir = "images/" . htmlspecialchars($kategorija_ime) . "/";


    $imageFile = $_FILES["fileToUpload"];
    $ext = strtolower(pathinfo($imageFile["name"], PATHINFO_EXTENSION));
    $file_name = uniqid("img_", true) . "." . $ext;
	
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
		
		
        $stmt = mysqli_prepare($conn, "UPDATE izdelek SET slika = ? WHERE id_i = ?");
        mysqli_stmt_bind_param($stmt, "si", $target_file, $id_izdelka);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: uredi_izdelek.php?id=" . $id_izdelka);
        exit;
    } else {
        echo "Napaka pri nalaganju slike.";
    }
}

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi izdelek</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
<main class="uredi-izdelek">
    <form action="izdelki.php" method="get" style="margin-bottom: 20px;">
        <button type="submit">← Nazaj na izdelke</button>
    </form>

    <h1>Urejanje: <?= htmlspecialchars($izdelek['ime']) ?></h1>

    <section>
		<h2>Slika</h2>
		<?php if (!empty($izdelek['slika'])): ?>
			<img src="<?= htmlspecialchars($izdelek['slika']) ?>" width="200" alt="Slika izdelka"><br>
		<?php else: ?>
			<p>Ni slike.</p>
		<?php endif; ?>

		<form  method="post" enctype="multipart/form-data">
		  Select image to upload:
		  <input type="file" name="fileToUpload" id="fileToUpload">
		  <input type="submit" value="Upload Image" name="submit">
		</form>
	</section>


    <section>
        <h2>Splošno</h2>
        <form method="post">
            <label>Ime:</label><br>
            <input type="text" name="ime" value="<?= htmlspecialchars($izdelek['ime']) ?>" required><br><br>

            <label>Opis:</label><br>
            <textarea name="opis" rows="4" cols="50" required><?= htmlspecialchars($izdelek['opis']) ?></textarea><br><br>

            <label>Cena (€):</label><br>
            <input type="number" step="0.01" name="cena" value="<?= htmlspecialchars($izdelek['cena']) ?>" required><br><br>

            <label>Zaloga:</label><br>
            <input type="number" name="zaloga" value="<?= htmlspecialchars($izdelek['zaloga']) ?>" required><br><br>

            <label>Kategorija:</label><br>
            <select name="kategorija" required>
                <option value="" disabled>Izberi kategorijo</option>
                <?php foreach ($kategorije as $kat): ?>
                    <option value="<?= $kat['id_ka'] ?>" <?php if ($kat['id_ka'] == $izdelek['id_ka']) echo 'selected'; ?>>
                        <?= htmlspecialchars($kat['ime']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit" name="shrani">Shrani spremembe</button>
        </form>
    </section>

    <section style="margin-top: 30px;">
        <form method="post">
            <button type="submit" name="odstrani" style="background-color: red; color: white;" onclick="return confirm('Ali ste prepričani, da želite odstraniti ta izdelek?');">
                Odstrani izdelek
            </button>
        </form>
    </section>
</main>
</body>
</html>
