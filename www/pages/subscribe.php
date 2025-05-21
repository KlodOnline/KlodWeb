<div id="main_p"><h2 class="trn">__VALIDATIONPAYMENT__</h2></div>
<?php
	include_once '../includes/form_manager.php'; 
?>



<?php
	if(!empty($err)) {
		echo '<p><div class="ERREUR">';
	  foreach ($err as $e) { echo '<span class="trn">'.$e.'</span><br/>'; }
	  echo '</div></p>';
	}
?>
