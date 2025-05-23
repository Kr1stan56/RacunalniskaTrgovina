<div class="sidebar">
    <div class="">
        <h3 class="">Kategorije</h3>
        <div class="">
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