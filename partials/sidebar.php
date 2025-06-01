<?php
if (isset($_GET['toggle'])) {
    $toggle = $_GET['toggle'];
} else {
    $toggle = 1;
}
?>

<?php
if ($toggle % 2 !== 0) { 
?>
<div class="sidebar-gumb">
	<div class="sidebar">
		<div >
			<h3 class="">Kategorije</h3>

			<div class="kategorije-box">
				<a href="?" class="category-item <?= empty($izbrana_kategorija) ? 'active' : '' ?>">Vse kategorije</a><br>
				<?php foreach ($kategorije as $kat): ?>
				<a href="?kategorija=<?= $kat['id_ka'] ?>" 
				   class="category-item <?= ($kat['id_ka'] == $izbrana_kategorija) ? 'active' : '' ?>">
				   <?= htmlspecialchars($kat['ime']) ?>
				</a><br>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="zapri">
		<a href="?toggle=<?= $toggle + 1 ?>">
			<img src="images/left.webp" alt="Zapri sidebar">
		</a>
	</div>
</div>
<?php
} else 
{?>
<div class="sidebar-gumb zaprt ">
	<div class="odpri">
		<a href="?toggle=<?= $toggle -1 ?>"> 
            <img src="images/right.webp" alt="Odpri sidebar">
        </a>
	</div>
</div>
<?php
}?>
