<?php


include_once '../includes/common.php';

$err = [];

if (isset($_POST['submit'])) {

    //This code escapes data of all POST data from the user submitted form.
    foreach ($_POST as $key => $value) {
        $data[$key] = filter(filter_var($value, FILTER_SANITIZE_STRING));
    }

    // var_dump($data);

    if (!array_key_exists("action", $data)) {
        exit();
    }

    if ($data['action'] == "register") {

        // Validation of POST data ...
        if (empty($data['username']) or strlen($data['username']) < 4) {
            $err[] = "__BADSHORTNAME__";
        }
        // Validate User Name
        if (!isUserID($data['username'])) {
            $err[] = "__BADCHARNAME__";
        }
        // Validate Email
        if (!isEmail($data['mail'])) {
            $err[] = "__BADMAIL__";
        }
        // Check User Passwords
        if (!checkPwd($data['pwd'], $data['pwd2'])) {
            $err[] = "__BADPASS__";
        }

        // Duplicates check  (we do only if everything seems ok)
        if (empty($err)) {
            $player_manager = new player_manager();
            $where = 'name="'.$data['username'].'" OR mail="'.$data['mail'].'"';
            $players = $player_manager->read_multiple($where);
            if (count($players) > 0) {
                $err[] = "__DUPLICATENAMEMAIL__";
            }
        }


        // Everything is ok ?
        if (empty($err)) {
            // create the object :
            $obj_data = [];
            // Generates activation code simple 4 digit number
            $obj_data['activcode'] = rand(1000, 9999);
            $obj_data['hashpass'] = password_hash($data['pwd'], PASSWORD_DEFAULT);
            $obj_data['ip'] = $_SERVER['REMOTE_ADDR'];
            $obj_data['mail'] = $data['mail'];
            $obj_data['name'] = $data['username'];

            $player = new player($obj_data);
            $player->save();

            // Pour le mail d'activation !!! (plus tard... )
            $md5_id = md5($player->id());
            $a_link = 'http://'.$host.$path.'/activate.php?user='.$md5_id.'&activ_code='.$obj_data['activcode'];

            // Definir notre session
            /*
            $session->set_id($player->id());
            $session->set_name($player->name());
            $session->save();
            */

            // $player->show_all();

            // Inviter a se connecter :
            echo '<h3><span class="SUCCES trn">__REGISTERSUCCES__</span></h3>';

            exit();
        }
    }

    if ($data['action'] == "login") {

        // On peut s'authentifier avec son mail ou son nom d'utilisateur :
        $player_manager = new player_manager();

        $where = '(name="'.$data['username'].'" OR mail="'.$data['username'].'")';
        //.' AND hashpass="'.password_hash($data['password'], PASSWORD_DEFAULT).'"';

        // var_dump($where);

        $players = $player_manager->read_multiple($where);

        // var_dump($players);

        if (count($players) > 0) {
            // validate password :
            $checked = password_verify($data['password'], $players[0]->hashpass());

            if ($checked == true) {
                // Definir notre session
                $session->set_id($players[0]->id());
                $session->set_name($players[0]->name());
                $session->save();

                // Change Element of menu with username...
                echo '<script>ChangeLoginMenu("'.$players[0]->name().'")</script>';

                include_once './worlds.php';
                exit();

            }


        }

        $err[] = "__INCORRECTLOGIN__";

    }


    if ($data['action'] == "subscribe") {
        if (!empty($data['fee'])) {
            if ($data['fee'] == '30' or $data['fee'] == '180' or $data['fee'] == '90') {
                if ($_SESSION['id'] != 0) {

                    $player_manager = new player_manager();
                    $player = $player_manager->read($_SESSION['id']);
                    $player->add_days((int) $data['fee']);
                    $player->save();

                    echo '<span class="SUCCES trn">__PAYMENTSUCCESS__</span>';
                    echo '<span class="trn">__THANKSPEECH__</span><br/>';
                    echo '<br/><br/><br/>';
                    echo '<center><span class="trn">__FEESTATUS__</span> :<br/>';
                    echo '<span class="SUCCES trn">'.$player->expiracy_date().'</span>';
                    echo '</center>';

                    exit();

                }
            }
        }

        $err[] = '__SOMEERRORWITHBANK__';

    }

}
