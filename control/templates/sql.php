<h1>SQL PlayArea</h1>

<form action="" method="post"> 
<textarea name="sql_query" rows="15" cols="70" style="width:100%"><?php echo $sql_query ?></textarea>
<input name="action" value="Try" type="submit" class="btn btn-primary" /><br /><br />
<input type="text" name="name" placeholder="Report Name"  value="<?php echo $name ?>" />
<input type="text" name="description" placeholder="Description" size="150" value="<?php echo $description ?>"  /><br />
<?php $html->buildInput("db", "Database", 'select', $db, array('options' => array(
			'madapp'=> 'MadApp',
			'donut'	=> 'Donut',
			'site'	=> 'Website'
		))); ?>
<input name="action" value="Save" type="submit" class="btn-sm btn btn-success" />
<input type="hidden" name="id" value="<?php echo $id ?>" />
</form>

<?php if($data) { ?>
<h3>Results...</h3>

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