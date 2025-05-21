<?php

$data['username'] = '';
$data['mail'] = '';

include_once '../includes/form_manager.php'; 

if ($_SESSION['id']!=0) {
	
	echo '<p><div class="ERREUR trn">';
	echo '__ALREADYREGISTRED__';
    echo '</div></p>';

	exit();
}

?>

<div id="main_p">

<h2 class="trn">__FREEREGISTRATION__</h2>

<div class="pg1">
	<b><span class="trn">__REGISTERNOW__</span></b><br/>
</div>

<div class="pg1">
	<span class="trn">__REGISTERSPEECH__</span>
</div>


	<form action="register" method="post" enctype="multipart/form-data" autocomplete="off">
		<table align="center">
	  	<tr>
	    	<td align="left"><span class="trn">__USERNAME__</span><font color="#CC0000">*</font></td>
	      <td><input name="username" type="text" id="username" class="required username" minlength="5" value="<?php echo $data['username']; ?>"></td>
			</tr>
			<tr>
				<td align="left"><span class="trn">__MAIL__</span><font color="#CC0000">*</font></td>
	      <td><input name="mail" type="text" id="mail" class="required email" value="<?php echo $data['mail']; ?>"> </td>
			</tr>
			<tr>
	    	<td align="left"><span class="trn">__PASSWORD__</span><font color="#CC0000">*</font></td>
	      <td><input name="pwd" type="password" class="required password" minlength="5" id="pwd"></td>
			</tr>
	    <tr>
	    	<td align="left"><span class="trn">__PASSAGAIN__</span><font color="#CC0000">*</font></td>
	      <td><input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd"></td>
			</tr>
		</table>
		<p align="center">
			
						<button id="validate" class="trn"/>__INSCRIPTION__</button>
	  </p>
	</form>
</div>



<?php
if(!empty($err))
    {
    echo '<p><div class="ERREUR">';
    foreach ($err as $e) { echo '<span class="trn">'.$e.'</span><br/>'; }
    echo '</div></p>';
    }
?>