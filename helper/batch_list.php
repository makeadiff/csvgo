<?php
require '../common.php';

$crud = new Crud('Batch');

$crud->setListingQuery("SELECT B.id, City.name as city, C.name AS center, CONCAT((CASE B.day
										WHEN '0' THEN 'Sunday'
										WHEN '1' THEN 'Monday'
										WHEN '2' THEN 'Tuesday'
										WHEN '3' THEN 'Wednesday'
										WHEN '4' THEN 'Thursday'
										WHEN '5' THEN 'Friday'
										WHEN '6' THEN 'Saturday'
										ELSE ''
										END), ' ', B.class_time) AS batch
FROM Batch B
INNER JOIN Center C ON B.center_id=C.id
INNER JOIN City ON C.city_id=City.id
WHERE B.year=2017 AND C.status='1' AND City.type='actual' AND B.status='1'
ORDER BY City.name, C.name, B.day");
$crud->setListingFields("id", "city", "center", "batch", "teachers");
$crud->addField('teachers', 'Teacher CSV', 'varchar', array(), 
		array('url'=>'"../index.php?name=batch_teachers&no_cache=1&&batch_id=$row[id]"', 'text'=>'"All Teachers in Batch"'),'text', 'url');

$crud->render();
