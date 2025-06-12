<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);//iz url id ja pretvori v spremeljivko

$stmt = mysqli_prepare($conn, "SELECT ime FROM kategorije WHERE id_ka = ?");//pripravi sql stavek
mysqli_stmt_bind_param($stmt, "i", $id);//binda ? z $id 
mysqli_stmt_execute($stmt);//zažene		



mysqli_stmt_bind_result($stmt, $ime);//skrani oz binda rezultat ime v $ime  -- samo povezava oz priprava
mysqli_stmt_fetch($stmt);//se izvede ter shrani podatke v $stmt ....$ime output

mysqli_stmt_close($stmt);//pomnilink

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['shrani'])) {
        $novo_ime = trim($_POST['ime']);
        if (isset($novo_ime)) {
            $stmt = mysqli_prepare($conn, "UPDATE kategorije SET ime = ? WHERE id_ka = ?");
            mysqli_stmt_bind_param($stmt, "si", $novo_ime, $id);
            mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);

            header("Location: izdelki.php");
            exit;
        }
    }

    if (isset($_POST['odstrani'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM kategorije WHERE id_ka = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

        header("Location: izdelki.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi kategorijo</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
</head>
<body>
<main class="uredi-izdelek">
    <form action="izdelki.php" method="get">
        <button type="submit">← Nazaj na kategorije</button>
    </form>

    <h1>Uredi kategorijo</h1>

    <form method="post">
        <label>Ime kategorije:</label>
        <input type="text" name="ime" value="<?= htmlspecialchars($ime) ?>" required><br>

        <button type="submit" name="shrani">Shrani spremembe</button>
        <button type="submit" name="odstrani">Odstrani kategorijo</button>
    </form>
</main>
</body>
</html>
