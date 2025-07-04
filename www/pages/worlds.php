<div id="main_p">
	<h2><span class="trn">__WORLD_LIST__</span></h2>
<?php

include_once '../includes/common.php';

if ($_SESSION['id'] != 0) {
    $player_manager = new player_manager();
    $player = $player_manager->read($_SESSION['id']);
} else {
    $player = new player();
}

$world_manager = new world_manager();
$worlds = $world_manager->read_all();

echo '<table id="wtable">';
echo '<tr><th class="trn">__WSTATUS__</th>'
    .'<th class="trn">__WNAME__</th>'
  .'<th class="trn">__WTYPE__</th>'
  .'<th class="trn">__JOIN__</th></tr>';

foreach ($worlds as $world) {

    $line = '<tr><td class="status">';
    $line = $line.'<div class="status" address="'.$world->correct_address().'"><img class="status" src="./img/server_unknown.png"></div>';
    $line = $line.'</td><td>';
    $line = $line.$world->name();
    $line = $line.'</td><td>';

    if ($world->demo() == 'true') {
        $line = $line.'<span class="trn">__DEMO__</span>';
    } else {
        $line = $line.'<span class="trn">__NOTDEMO__</span>';
        if (!$player->fee_status()) {
            $line = $line.'<br/><a id="account" class="main trn">__SUBSCRIBE__</a>';
        }
    }

    $line = $line.'</td>';

    if ($player->fee_status() or $world->demo() == 'true') {
        $line = $line.'<td>';

        if (MAQUETTE_MODE == 'docker') {
            $line = $line.'<form action="'.$world->correct_address(true).'" method="post">';
        } else {
            $line = $line.'<form action="'.$world->correct_address().'" method="post">';
        }

        $line = $line.'<button class="world trn"/>__JOIN__</button>';
        $line = $line.'<input type="hidden" id="token" name="token" value="'.$player->json_token().'"></form>';
        $line = $line.'</td>';
    } else {
        $line = $line.'<td>';
        $line = $line.'<button class="world disabled trn"/>__JOIN__</button>';
        $line = $line.'</td>';
    }

    $line = $line.'</tr>';
    echo $line;
}
echo '</table>';

?>
</div>