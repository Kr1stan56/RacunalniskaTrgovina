<?php include_once 'session.php'; ?>
<?php require_once 'baza.php'; ?>

<?php
$izbrana_kategorija = null;
$iskalnik = null;

if (isset($_GET['kategorija']) && $_GET['kategorija'] !== '' && $_GET['kategorija'] !== 'vse') {
    $izbrana_kategorija = intval($_GET['kategorija']);
}

if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $iskalnik = '%' . trim($_GET['q']) . '%';
}

if ($iskalnik && $izbrana_kategorija !== null) {
    $stmt = $conn->prepare("
        SELECT * FROM izdelek 
        WHERE (ime LIKE ? OR opis LIKE ?) AND id_ka = ?
    ");
    $stmt->bind_param("ssi", $iskalnik, $iskalnik, $izbrana_kategorija);

} elseif ($iskalnik) {
    $stmt = $conn->prepare("
        SELECT * FROM izdelek 
        WHERE ime LIKE ? OR opis LIKE ?
    ");
    $stmt->bind_param("ss", $iskalnik, $iskalnik);

} elseif ($izbrana_kategorija !== null) {
    $stmt = $conn->prepare("
        SELECT * FROM izdelek 
        WHERE id_ka = ?
    ");
    $stmt->bind_param("i", $izbrana_kategorija);

}
?>
<header class="header">
    <div class="glava-vsebina">
        <div class="logotip">
            <a href="index.php">
                <img src="images/logo.png" alt="Logotip trgovine" class="logotip-slika">
            </a>
        </div>

        <nav class="navigacija">
            <ul class="navigacijski-seznam">
                <li><a href="index.php">Domov</a></li>
                <li><a href="izdelki.php">Izdelki</a></li>

            </ul>
        </nav>

        <div class="uporabniske-akcije">
            <?php if (isset($_SESSION['prijavljen']) && $_SESSION['prijavljen']): ?>
                <span class="uporabnisko-ime"><?= htmlspecialchars($_SESSION['ime'] . ' ' . $_SESSION['priimek']) ?></span>
                <a href="odjava.php" class="gumb-odjava">Odjava</a>
                <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
                    <a href="uredi_u.php" class="gumb-admin">Uporabniki</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="gumb-prijava">Prijava / Registracija</a>
            <?php endif; ?>

            <a href="kosarica.php" class="ikona-kosarica">
                <img src="images/kosarica.png" alt="Košarica" class="kosarica-ikona">
                <span class="stevec-kosarice">0</span>
            </a>
        </div>
    </div>


	<div class="iskalnik-vsebina">
		<form action="izdelki.php" method="get" class="iskalni-obrazec">
			<input type="text" name="q" placeholder="Kaj iščete?" value="<?= $_GET['q'] ?? '' ?>" class="iskalno-polje">

			<select name="kategorija" class="kategorija" >
				<option value="" >Vse kategorije</option>
				<?php foreach ($kategorije as $kat): ?>
					<option value="<?= $kat['id_ka'] ?>" <?= (isset($_GET['kategorija']) && $_GET['kategorija'] == $kat['id_ka']) ? 'selected' : '' ?>>
						<?=$kat['ime'] ?>
					</option>
				<?php endforeach; ?>
			</select>

			<button type="submit" class="gumb-iskanje">Najdi</button>
		</form>

	</div>

</header>
