<?php
include_once '../includes/form_manager.php';
?>

	<div id="main_p">
		<form action="login" method="post" enctype="multipart/form-data" autocomplete="off">
			<h2 class="trn">__LOGIN__</h2>

			<a id="register" class="main trn">__ASK_MEMBER__</a><br/><br/>

			<label class="trn">__USERNAME__</label><br/>
			<input type="text" name="username"><br/>

			<label class="trn">__PASSWORD__</label><br/>
			<input type="password" name="password"><br/><br/>

		

					
			<button id="validate" class="trn"/>__LOGIN__</button>
		

		</form>
	</div>


<?php
    if (!empty($err)) {
        echo '<p><div class="ERREUR">';
        foreach ($err as $e) {
            echo '<span class="trn">'.$e.'</span><br/>';
        }
        echo '</div></p>';
    }
?>