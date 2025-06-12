<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$kategorije = [];
$sql_kat = "SELECT id_ka, ime FROM kategorije ORDER BY ime";
$stmt = mysqli_prepare($conn,"SELECT id_ka, ime FROM kategorije ORDER BY ime");
if($stmt){
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if($result){
		while($row = mysqli_fetch_assoc($result)){
			$kategorije[]=$row;
		}
		
	}
    mysqli_stmt_close($stmt);

}



if (isset($_POST['shrani'])) {
    $ime = trim($_POST['ime']);
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];
    $zaloga = $_POST['zaloga'];
    $url_slike = trim($_POST['url_slike']);
    $id_kategorije = $_POST['kategorija'];

    $stmt = mysqli_prepare($conn, "INSERT INTO izdelek (ime, opis, cena, zaloga, slika, id_ka) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdiii", $ime, $opis, $cena, $zaloga, $url_slike, $id_kategorije);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

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
    <title>Dodaj nov izdelek</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
    <main class="uredi-izdelek">
        <form action="izdelki.php" method="get">
            <button type="submit">← Nazaj na izdelke</button>
        </form>

        <h1>Dodaj nov izdelek</h1>

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
                <label>Ime:</label>
                <input type="text" name="ime" required><br>

                <label>Opis:</label>
                <textarea name="opis" required></textarea><br>

                <label>Cena (€):</label>
                <input type="number" name="cena" required><br>

                <label>Zaloga:</label>
                <input type="number" name="zaloga" required><br>

                <label>Kategorija:</label>
                <select name="kategorija" required>
                    <option value="" disabled selected>Izberi kategorijo</option>
                    <?php foreach ($kategorije as $kat): ?>
						<option value="<?= $kat['id_ka'] ?>"><?= htmlspecialchars($kat['ime']) ?></option>
					<?php endforeach; ?>

                </select><br><br>

                <button type="submit" name="shrani">Dodaj izdelek</button>
            </form>
        </section>
    </main>
</body>
</html>
