<?php if($data) { ?><br /><br />
<h1><?php echo format($name); ?></h1>

<?php if(i($QUERY, 'no_cache')) { ?>
	<p>Data Refreshed! There might be a cache at Google Sheet end, to refresh that change the CSV URL a bit. Eg. Add <code>&x=1</code> to the end of the URL. If that is already there, increment the number by 1. This will force Sheet to refresh the data.</p>
<?php } else { ?>
	<p><a href="index.php?mime=html&name=<?php echo $name; ?>&no_cache=1">🔄 Refresh Cache</a></p>
<?php } ?>

<?php
if($pager) {
	$pager->link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%"><img alt="%%TEXT%%" src="images/icons/arrows/%%CLASS%%.png" /></a>';
	if($pager->total_pages > 1) {
		print $pager->getLink("first") . $pager->getLink("back");
		$pager->printPager();
		print $pager->getLink("next") . $pager->getLink("last") . '<br />';
	}
	if($pager->total_items) print $pager->getStatus();
}
?>

<table class="table table-striped">
<?php
$header = array_keys($data[0]);
print "<tr>";
foreach ($header as $value) {
	print "<th>" . format($value) . "</th>";
}
print "</tr>\n";

foreach ($data as $row) { 
	print "<tr>";
	foreach ($row as $value) {
		if(is_array($value)) {
			print "<td>" . implode(",", $value) . "</td>";
		} else {
			print "<td>$value</td>";
		}
	}
	print "</tr>\n";
}
?>
</table>

<?php
if($pager) {
	$pager->link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%"><img alt="%%TEXT%%" src="images/icons/arrows/%%CLASS%%.png" /></a>';
	if($pager->total_pages > 1) {
		print $pager->getLink("first") . $pager->getLink("back");
		$pager->printPager();
		print $pager->getLink("next") . $pager->getLink("last") . '<br />';
	}
	if($pager->total_items) print $pager->getStatus();
}
?>
<?php } ?>