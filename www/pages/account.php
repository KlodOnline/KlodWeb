<?php
include_once '../includes/form_manager.php';

if ($_SESSION['id'] != 0) {
    $player_manager = new player_manager();
    $player = $player_manager->read($_SESSION['id']);
    ?>

<div id="main_p">
	<h2 class="trn">__ACCOUNTDETAILS__</h2>
	<center>
		<form action="logout" method="post">
			<button id="validate" class="trn"/>__LOGOUT__</button>
		</form>
	</center><br/>
	<div class="pg1"><h3><span class="trn">__ACCOUNTDATA__</span></h3>

		<table align="center">
	  	<tr>
	    	<td align="left"><span class="trn">__USERNAME__</span> : </td>
	      <td><?php echo $player->name(); ?></td>
			</tr>
			<tr>
				<td align="left"><span class="trn">__MAIL__</span> : </td>
	      <td><?php echo $player->mail(); ?></td>
			</tr>
		</table>
		</div>

		<div class="pg1"><h3><span class="trn">__ACCOUNTFEE__</span></h3>

		<center>
				<span class="trn">__FEESTATUS__</span> :<br/>
					<?php
                            if ($player->fee_status() == true) {
                                echo '<span class="SUCCES trn">'.$player->expiracy_date().'</span>';
                            } else {
                                echo '<span class="ERREUR trn">__EXPIRED__</span><br/>';
                                echo '<i><span class="trn">__NOWORRIESDEMO__</span></i>';
                            }
    ?>
				</center>
		<br/>

		<form action="subscribe" method="post" enctype="multipart/form-data" autocomplete="off">
		<table align="center">
			<tr>
				<td align="left"><span class="trn">__ADD30DAYS__</span></td>
 				<td><label><input type="radio" name="fee" value="30" checked="checked"></label></td>
			</tr>
			<tr>
				<td align="left"><span class="trn">__ADD90DAYS__</span></td>
 				<td><label><input type="radio" name="fee" value="90"></label></td>
			</tr>
			<tr>
				<td align="left"><span class="trn">__ADD180DAYS__</span></td>
 				<td><label><input type="radio" name="fee" value="180"></label></td>
			</tr>
		</table>
	
		<p align="center">
			<button id="validate" class="trn"/>__PAY__</button>
		</p>		
	  </form>
	</div>
</div>

<?php

if (!empty($err)) {
    echo '<p><div class="ERREUR">';
    foreach ($err as $e) {
        echo '<span class="trn">'.$e.'</span><br/>';
    }
    echo '</div></p>';
}

} else {
    // On reroute vers la page de login :
    include_once './login.php';
}
?>