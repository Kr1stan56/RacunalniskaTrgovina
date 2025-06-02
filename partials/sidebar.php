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
		
		

			<div class="kategorije-box">
			
			    <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
				<a href="dodaj_kat.php" class="category-item " style="background-color:white;color:black;">+ Dodaj Kategorijo</a><br>
				<?php endif; ?>
				
					<a href="?" class="category-item">Vse kategorije</a><br>
				<?php foreach ($kategorije as $kat): ?>
				<div>
					<a href="?kategorija=<?= $kat['id_ka'] ?>"class="category-item" >
					   <?= htmlspecialchars($kat['ime']) ?>
					</a>
					
					<?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
					<a href="uredi_kat.php?id=<?= $kat['id_ka'] ?>"  class="category-item" style="background-color: white; color: black;">
						<i>UREDI</i>
					</a>
					<?php endif; ?><br>
				</div><br>
				<?php endforeach; ?>
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
