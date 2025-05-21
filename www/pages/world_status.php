<?php
include_once '../includes/common.php'; 


// This code escapes data of all POST data from the user submitted form.
 foreach($_GET as $key => $value) { $data[$key] = filter(filter_var($value,FILTER_SANITIZE_STRING));}

$world_manager = new world_manager();
$worlds = $world_manager->read_all();

if(isset($data['address'])) {
	foreach ($worlds as $world){
		if ($world->correct_address()==$data['address']) {
			echo $world->status();
		}
	}
}
