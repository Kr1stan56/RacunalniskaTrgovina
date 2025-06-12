<?php
require_once 'baza.php';
include_once 'session.php';


$napaka = [];

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


if (isset($_POST['submit'])) {
    $ime = trim($_POST['ime']);
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];
    $zaloga = $_POST['zaloga'];
    $id_kategorije = $_POST['kategorija'];

    $slika_pot = '';
    $kategorija_ime = '';
	
	
    foreach ($kategorije as $kat) {
        if ($kat['id_ka'] == $id_kategorije) {
            $kategorija_ime = $kat['ime'];
            break;
        }
    }

    if (!empty($_FILES["fileToUpload"]["name"])) {//FILES globalna sp za datoteke  "name" vrne ime datoteke
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);//preveri sli je slika 	 zacsno ime slike kamor jo php shrani
		  if($check !== false) {
		  } else {
			$napaka= "File is not an image.";
		  }
	}
	$target_dir = "images/" . htmlspecialchars($kategorija_ime) . "/";
	if(empty($napaka)){
		$file_name = uniqid(). pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);//sestavi string iz unikatnega idja ter izluščene končnice
		$target_file = $target_dir . $file_name;

			//move_uploaded_file(zacasnapot, koncna pot)
        if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
            $slika_pot = $target_file;
        }
	}
        
    

    $stmt = mysqli_prepare($conn, "INSERT INTO izdelek (ime, opis, cena, zaloga, slika, id_ka) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdiss", $ime, $opis, $cena, $zaloga, $slika_pot, $id_kategorije);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
	if(empty($napaka)){
		header("Location: izdelki.php");
		exit;
	}
}

?>


<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj nov izdelek</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css?v=1.1">
</head>
<body>
    <main class="uredi-izdelek">
        <form action="izdelki.php" method="get">
            <button type="submit">← Nazaj na izdelke</button>
        </form>

        <h1>Dodaj nov izdelek</h1>

        <section>
			<?php if ($napaka): ?>
				<div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($napaka) ?></div>
			<?php endif; ?>


			<h2>Slika</h2>
			<?php if (!empty($izdelek['slika'])): ?>
				<img src="<?= htmlspecialchars($izdelek['slika']) ?>" width="200" alt="Slika izdelka"><br>
			<?php else: ?>
				<p>Ni slike.</p>
			<?php endif; ?>

			<form method="post" enctype="multipart/form-data">
				<h2>Slika</h2>
				<input type="file" name="fileToUpload" id="fileToUpload" ¸><br>

				<h2>Splošno</h2>
				<label>Ime:</label>
				<input type="text" name="ime" required><br>

				<label>Opis:</label>
				<textarea name="opis" required></textarea><br>

				<label>Cena (€):</label>
				<input type="number" step="0.01" name="cena" required><br>

				<label>Zaloga:</label>
				<input type="number" name="zaloga" required><br>

				<label>Kategorija:</label>
				<select name="kategorija" required>
					<option value="" disabled selected>Izberi kategorijo</option>
					<?php foreach ($kategorije as $kat): ?>
						<option value="<?= $kat['id_ka'] ?>"><?= htmlspecialchars($kat['ime']) ?></option>
					<?php endforeach; ?>
				</select><br><br>

				<button type="submit" name="submit">Dodaj izdelek</button>
			</form>
			<br><br>


        </section>
    </main>
</body>
</html>
