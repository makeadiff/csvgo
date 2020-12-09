<h2>Memcache Debug</h2>

<?php if($key) { ?>
<h4><?=$key ?></h4>
<?= htmlentities($contents) ?>
<?php } ?>

<h3>All Keys</h3>

<ul>
<?php foreach($all_keys as $k) { ?>
	<li><a href="memcache.php?key=<?= $k ?>"><?= $k ?></a></li>
<?php } ?>
</ul>

