<?php

//===================== COMMUNS ================================================
header( 'content-type: text/html; charset=utf-8' );

//===================== DEBUG MODE (A virer en prod) ===========================
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
// --- Fin debug mode

$host  = $_SERVER['HTTP_HOST'];
$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// toutes les fonctions 
include_once __DIR__.'/helpers.php';

//Sécurité : Prévention des injection de session.
if (isset($_REQUEST['_SESSION'])) die("...");

//===================== VARIABLES AUTHENTIFICATION =============================
define('DB_HOST', 'db'); // serveur mysql
define('DB_USER', 'klodadmin'); // nom d'utilisateur
define('DB_PASS', 'Pw3Lqb6fuLspT7IrYp'); // mot de passe
define('DB_NAME', 'klodwebsite'); // nom de la base
	
//==================== CHOSES DIVERSES =========================================
define ('MAQUETTE_MODE','docker');
define ('SERVER_OS','linux');
date_default_timezone_set('Europe/Paris');

//==================== IDENTIFICATION ==========================================
// Cet objet contient toutes les données relative à la session.
$session_manager = new session_manager();
$session = $session_manager->read();

// A ce stade, Session est normalement mûr. On le sauvegarde, pour plus tard.
$session->save();

?>
