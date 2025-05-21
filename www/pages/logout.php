<?php
include_once '../includes/common.php'; 
$session_manager->delete();

echo '<h3><span class="SUCCES trn">__LOGOUTSUCCESS__</span></h3>';

echo '<script>RestoreLoginMenu()</script>';

?>
