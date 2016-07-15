<?php if($data) { ?>
<h2>Results...</h2>

<?php
$pager->link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%"><img alt="%%TEXT%%" src="images/icons/arrows/%%CLASS%%.png" /></a>';
if($pager->total_pages > 1) {
	print $pager->getLink("first") . $pager->getLink("back");
	$pager->printPager();
	print $pager->getLink("next") . $pager->getLink("last") . '<br />';
}
if($pager->total_items) print $pager->getStatus();
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
		print "<td>$value</td>";
	}
	print "</tr>\n";
}
?>
</table>

<?php } ?>