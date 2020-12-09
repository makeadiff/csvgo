<h2>Memcache Debug</h2>

<?php if($key) { ?>
<h4><?= htmlentities($key) ?></h4>
<pre><?= var_dump($contents) ?></pre>
<?php } ?>

<h3>All Keys(<?= count($all_keys) ?>)</h3>

<ul>
<?php foreach($all_keys as $k) { ?>
	<li><a href="memcache.php?key=<?= base64_encode($k) ?>"><?= htmlentities($k) ?></a></li>
<?php } ?>
</ul>

