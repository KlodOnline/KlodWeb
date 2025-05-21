<?php

/*
 * ------------------------------------------
 *  	Fonctions utiles au CFramework
 * ------------------------------------------
 */

// Gérer l'auto inclusion des classes :
function class_autoloader($classe) { require __DIR__.'/'.$classe.'_class.php'; }
spl_autoload_register('class_autoloader');

// Fonction utile au systeme de FILTRES :

function ligne_filter_menu($session, $current_page)
    {
    // Construire le menu en precisant 'selected' a la valeur courante
    $menu = '';
    $menu = $menu .' Ligne : <input type="text" placeholder="sans filtre" name="line_filter" class="txt line_filter '.$current_page.'" value="'.$session->ligne().'">';
    echo $menu;
    }

function isSiteAvailible($url){
    // Check, if a valid url is provided
    if(!filter_var($url, FILTER_VALIDATE_URL)){
        return false;
    }

    

    // Initialize cURL
    $curlInit = curl_init($url);
    
    // Set options
    curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($curlInit,CURLOPT_HEADER,true);
    curl_setopt($curlInit,CURLOPT_NOBODY,true);
    curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

    // Get response
    $response = curl_exec($curlInit);
    
    // Close a cURL session
    curl_close($curlInit);

    return $response?true:false;
}

function tofloat($num) 
    {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
  
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
    }  

function date_filter_menu($session, $current_page)
    {
    // Construire le menu en precisant 'selected' a la valeur courante
    $menu = '';
    $menu = $menu .'Date : <input type="text" name="d_filter" placeholder="Début" class="datepicker txt date_filter debut '.$current_page.'" value="'.$session->dfstart().'">';
    $menu = $menu .' <input type="text" name="d_filter" placeholder="Fin" class="datepicker txt date_filter fin '.$current_page.'" value="'.$session->dfend().'">';
    echo $menu;
    }

function date_filter($debut, $fin)    
    {
    $start = traitement_date($debut);
    $end = traitement_date($fin);
    
    if ($end===0) {$end = 99999999999;}
      
    $filter = ' and (demande_date>='.$start.' and demande_date<='.$end.') ';
    return $filter;
    }

// Fonction utile a common.php :
function detect_site_string($string_to_inspect)
    {
    $sites = unserialize(SITES);
    foreach($sites AS $each_site)
        { if (stripos($string_to_inspect, $each_site)) { return $each_site; } }
    return '';
    }

  
// Fonction utile a BDD_IO :
function refValues($arr)
    {
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+ 
        { 
        $refs = array(); 
        foreach($arr as $key => $value) { $refs[$key] = &$arr[$key]; }
        return $refs; 
        } 
    return $arr; 
    } 

function shorten_txt($txt, $len)
	{
	if (strlen($txt)>($len+4))
		{ $ext = suffixe($txt, '.');
		if ($ext!=='none') { $txt = substr($txt, 0, $len) . '[...].'.$ext; }
			else { $txt = substr($txt, 0, $len) . '[...]'; } }
	return $txt;
	}
	
function that_dir($dir, $from='ext')
	{
	// référence au répertoire recherché :
	//
	// Accepte $from = 'ext' ou $from = 'int' :
	// 'ext' signifie "du point de vue de l'utilisateur" (donc, URL),
	// 'int' signifie "du point de vue du code" (donc, chemin de fichier)
	//
	
	$full_dir_path = '';
	if ($from === 'int') { $full_dir_path = getcwd().'/'.$dir.'/'; }
	if ($from === 'ext') { $full_dir_path = $_SERVER['HTTP_REFERER'].$dir.'/'; }
        
	return $full_dir_path;
	}
	
function fusionne_array( $array1=array(), $array2=array() )
	{
	if (is_array($array1) && is_array($array2))
		{
		$new_array = array_merge($array1, $array2);
		return $new_array;
		}
	return false;
	}
	
function clean_files_name()
	{
	if (isset($_FILES)) 
		{ foreach($_FILES as $key => $each_file) 
			{ $_FILES[$key]['name'] = filter_var($_FILES[$key]['name'], FILTER_SANITIZE_STRING); }  }
	}
	
function find_array($data_brute, $array_name)
	{
	$my_array = array();
	$my_key = 0;
	foreach( $data_brute as $key => $each_data )
		{
		$data_line_name = explode('-', $key);
		$data_line_syllabes = explode('_', $data_line_name[0]);
		
		if ($data_line_syllabes[0]===$array_name && $data_line_syllabes[1]==='array')
			{
			$my_array[$my_key] = $each_data; 
			$my_key++;
			}
		}
		
	if (sizeof($my_array)>0) {return $my_array;}	
	return false;
	}

function clean_server()    
    {
    if (isset($_SERVER)) 
        { foreach($_SERVER as $key => $value) 
            {  $_SERVER[$key] = filter_input(INPUT_SERVER, $key, FILTER_SANITIZE_STRING); } }
    }
        
function value_from_server($key)    
    {
    clean_server();
    $data = ''; 
    if (isset($_SERVER[$key])) {$data = $_SERVER[$key];}
    return $data;
    }
    
function clean_get()    
    {
    if (isset($_GET)) 
        { foreach($_GET as $key => $value) 
            {  $_GET[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING); } }
    }
	
function data_from_get()    
    {
    clean_get();
    $data = ''; 
    if (isset($_GET['data'])) {$data = $_GET["data"];}
	return $data;
    }

function value_from_get_key($key)
    {
    clean_get();
    $data = ''; 
    if (isset($_GET[$key])) {$data = $_GET[$key];}
    return $data;
    }	

function is_in_array($array, $value_to_find)
	{
	if (is_array($array))
		{ foreach($array as $key => $value) { if ($value===$value_to_find) { return true; } } }
	return false;
	}
	
function array_from_get()    
    {
    clean_get();
    return $_GET;
    }	
		
function suffixe($string, $separateur='_')
	{
	// Cas particulier : si débute par son dséparateur, on ignore le premier separateur
	if (substr($string, 0, 1)===$separateur) { $string = substr($string, 1); }
	
	$syllabes = explode($separateur, $string);
	$suffixe = 'none';
	
	if ((sizeof($syllabes)-1)>0) { $suffixe = end($syllabes); }
	
	return $suffixe;
	}
	
function prefixe($string)
	{
	$syllabes = explode('_', $string);
	$prefixe = 'none';
	$prefixe = current($syllabes);
	return $prefixe;
	}	

function validateDate($date, $format = 'd-m-Y')
    {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
    }
        
// Formatage de donnée d'objets	
function format_date($timestamp, $format=null)	
	{
	if ($format===null) 		{return $timestamp;}
	if ($timestamp===0) 		{return '-';}
	if ($format==='string') 	{return date("d-m-Y", $timestamp);}
	}
	
function format_array($array, $format=null)	
	{
	if ($format===null) 		{return $array;}
	if ($format==='string') 	{return serialize($array);}
	}

function traitement_date($int_or_string)
	{
	// $int_or_string = '12-02-2015';
	// echo '*****************';
	// echo ' A GERER : '.$int_or_string.' --> ';
	$return_value = 0;
	
	// Ici, pas de doute, on traite un chiffre qu'on renvoie :
	if (is_int($int_or_string)) 
		{
		// echo 'EST UN INT';
		$return_value = $int_or_string; 
		}
	else
		{
		// Ici, soit c'est un INT sous forme de string, soit c'est une date :
		if (is_string($int_or_string))
			{
			// On tente de le convertir direct en INT :
			$int_value = (int) $int_or_string;
			
			// Si le resultat ==0, alors y'a du string carac dedans, sinon, Timestamp diforme.
			if ( strlen($int_value) == strlen($int_or_string) )
				{
				// echo 'EST UN INT DIFORME';
				$return_value = (int) $int_or_string; 
				}
			else 
				{
				// echo 'EST UN STRING A CONVERTIR';
				$return_value = (int) strtotime($int_or_string); 
				}
			}
		}
	
	// echo ' RESULTAT --> '.$return_value.'  ';
	
	return $return_value;
	}

//
// CES FONCTIONS SONT UTILISEES DANS LE CODE DU MOTEUR v2 :
// ELLES SONT UTILISABLES PAR TOUT LES OBJETS
//
function chrono($cmd)
    {
    global $start;
    if ($cmd=='start')
        {
        $start = microtime(true);
        }
    if ($cmd=='inter')    
        {
        $now = microtime(true);
        $ecart = $now-$start;
        $ecart = round($ecart, 2);
        echo ' µT='.$ecart.' sec.<br/>';       
        }
    }

function debug_trace()    
    {
    echo '<br/>';
    try{throw new Exception("error - ");}
    catch(Exception $e ) {echo nl2br($e->getTraceAsString());}
    echo '<br/>';
    }
    


function convert_to_saphirs($amount, $value)
    {
    // 1 Sahir = 100 Or de 1 de valeur
    $poids_monnaie = $amount*$value;
    $nb_saphirs = floor($poids_monnaie / 100);
    return $nb_saphirs;
    }
function convert_to_monnaie($saphirs_amount, $value)
    {
    // 1 Sahir = 100 Or de 1 de valeur
    $monnaie = $saphirs_amount * 100 / $value;
    $monnaie = round($monnaie, 2);
    return $monnaie;
    }
    
function maps_to_path($maps)
    {
    $path='';
    foreach($maps AS $each_map)
        {
        $coords = $each_map->X().':'.$each_map->Y();
        if ($path=='') 
            {
            $path=$coords;
            }
        else
            {
            $path=$path.'-'.$coords;
            }
        }
    return $path;
    }

function magasin_city()
    {
    $magasin_id = (int) $_SESSION['SHOP']['VENDEUR'];
    
    if (is_numeric($magasin_id))
        {
        $city_manager = new city_manager();
        $magasin_city = $city_manager->read($magasin_id);
        if (is_a($magasin_city, 'city') AND $magasin_city->taille_magasin()>0)
            { return $magasin_city; }
        }
    return false;
    }

function sort_maps_by_production($product_id, $maps)
    {
    foreach ($maps AS $each_map)
        {
        $production = $each_map->common_prod();
        $nb_prod = 0;
        foreach($production AS $each_lot) 
            {
            //echo 'T='.$each_lot['type'].', V='.$volume.', ';
            if ($each_lot['type']==$product_id) 
                {$nb_prod=$nb_prod+$each_lot['volume'];} 
            }
        $vol_index[] = $nb_prod;
        $map_index[] = $each_map;
        }
    if (isset($vol_index) AND isset($map_index))    
        {
        //trier les cases par ordre de bouffe :
        array_multisort($vol_index, SORT_DESC, $map_index);    

        return $map_index;
        }
    else 
        {
        return $maps;
        }
    }

function my_random($max)    
    {
    $borne_max = $max/100*90;
    $borne_min = $max - ($max/100*90);
    
    while($r>$borne_max OR $r<$borne_min)
        {
        $n = microtime();
        $n = substr($n, 4, 3)/100;  
        $r = round(($max*(cos($n*100)+1))/2);
        }
    
    //Ramener par une règle de trois le R entre 0 et MAX :
    $r = $r - $borne_min;
    $max_obtenu = $borne_max - $borne_min;
    //echo 'Chiffre entre 0 et '.$max_obtenu.' *';
    
    $r = round($r*$max/$max_obtenu);
    
    return $r;
    }
    
function p_id($p)
    {
    if ($p=='U') {$pid=1;}
    if ($p=='F') {$pid=2;}
    if ($p=='W') {$pid=3;}
    if ($p=='S') {$pid=4;}
    if ($p=='G') {$pid=5;}

    if (isset($pid)) {return $pid;}
    }

function code_stock($stock)
    {
    $coded_stock = '';
    foreach($stock AS $each_slot)
        {
        if ($coded_stock=='')
            { $coded_stock = $each_slot['type'].':'.$each_slot['volume']; }
        else
            { $coded_stock = $coded_stock.'-'.$each_slot['type'].':'.$each_slot['volume']; }                
        }
    return $coded_stock;    
    }

function decode_stock($stock)        
    {
    $slots = explode('-', $stock);
    if (is_array($slots))
        {
        foreach($slots AS $each_slot)
            {
            $info = explode(':', $each_slot);
            $this_prod['type']=$info[0];
            if (isset($info[1])) {$this_prod['volume']=$info[1];} else {$this_prod['volume']=0;}
            $marchandises[] = $this_prod;
            }
        if (is_array($marchandises))    {return $marchandises;}
        }
    }    
    
function compile_marchandises($lot_1, $lot_2=null)
    {
    //cette fonction permet de compilet un ou deux lots de marchandises 'deomposees' en un lot unique.
    if (isset($lot_1))
        {
        foreach($lot_1 AS $each_element)  
            {
            //ce type existe dans la compliation ?
            $type_existe = false;
            if (isset($compiled_prod))
                {
                foreach ($compiled_prod AS $key => $each_compiled_prod) 
                    { 
                    if ($each_compiled_prod['type']==$each_element['type']) 
                        {
                        $compiled_prod[$key]['volume'] = $compiled_prod[$key]['volume'] + $each_element['volume'];
                        $type_existe = true; 
                        } 
                    }
                }
            if ($type_existe==false) { $compiled_prod[] = $each_element; }
            }
        }
    if (isset($lot_2))    
        {
        foreach($lot_2 AS $each_element)  
            {
            //ce type existe dans la compliation ?
            $type_existe = false;
            if (isset($compiled_prod))
                {
                foreach ($compiled_prod AS $key => $each_compiled_prod) 
                    { 
                    if ($each_compiled_prod['type']==$each_element['type']) 
                        {
                        $compiled_prod[$key]['volume'] = $compiled_prod[$key]['volume'] + $each_element['volume'];
                        $type_existe = true; 
                        } 
                    }
                }
            if ($type_existe==false) { $compiled_prod[] = $each_element; }
            }            
        }
    if (is_array($compiled_prod)) { return $compiled_prod; }
    }

function path_dec($path)    
    {
    $new_path = '';
    if ($path!='' AND is_string($path))
        {
        $decoded_path = explode('-', $path);
        foreach($decoded_path AS $key => $coords)
            {
            if ($key>0)
                {
                if ($key==1)
                    {
                    $new_path = $coords;
                    }
                else
                    {
                    $new_path =$new_path.'-'.$coords;
                    }
                }
            }
        }
    return $new_path;
    }

function corpusify(array $array)
    {
    $corpus = '';
    foreach($array AS $each_line)
        {
        $corpus = $corpus.$each_line.'<br/>'; 
        }
    return $corpus;
    }
    
function show_array($array) { foreach($array AS $line) { echo $line.'<br/>'; } }    

function saison($Y, $seed)
    {
    //Petit calcul pour les saisons :

    //initialisation des repertoires des images de saison :
    $ete = 'ete'; $aut = 'aut'; $hiv = 'hiv'; $pri = 'pri';

    //On compte le nombre de lundi franchis depuis le debut du mois :
    $week = 0;
    $today = time();
    $i = 0;
    while ($i <= date('j',$today)) { if (date("N", mktime(0, 0, 0, date('n',$today), $i, date('Y',$today))) == "1") $week++; $i++;}

    //Les semaines vont de 0 ï¿½ 5. Hors, il n'y a que 4 saisons.
    //donc, il faut prendre 0 et 5 comme une saison speciale (ici, j'ai choisi 4, soit, printemp/Automne, pour leur neutralite naturelle)
    if ($week == 0) {$week = 4;}
    if ($week == 5) {$week = 4;}


    //La semaine du mois determine la saison (differente selon l'hemisphere):
    switch ($week)
            {
            case 1: $saison_nord = $ete; $saison_sud = $hiv; break;
            case 2: $saison_nord = $aut; $saison_sud = $pri; break;
            case 3: $saison_nord = $hiv; $saison_sud = $ete; break;
            case 4: $saison_nord = $pri; $saison_sud = $aut; break;
            }
    //echo 'Au nord, saison : '.$saison_nord.' Au sud, saison : '.$saison_sud;

    //Selon Y, on indique la saison. Les tropiques ï¿½ +/-5 cases sont toujours en "ï¿½tï¿½"
    if ($Y<65) {$saison = $saison_nord;}
    if ($Y>135) {$saison = $saison_sud;}
    if ($Y>65 AND $Y<135) 	{$saison = $ete;}

    srand($seed);
    $rand_saison = rand(1, 3);

    if ($Y==65)
        { if ($rand_saison<>1) {$saison = $saison_nord;} else {$saison = $ete;} }
    if ($Y==135)
        { if ($rand_saison<>1) {$saison = $saison_sud;} else {$saison = $ete;} }
    if ($Y==66)
        { if ($rand_saison==1) {$saison = $saison_nord;} else {$saison = $ete;} }
    if ($Y==134)
        { if ($rand_saison==1) {$saison = $saison_sud;} else {$saison = $ete;} }

    //on ajoute un marge de 1 case qui subit une seed de random :
    //echo $seed.'--';

    return $saison;
    }
        
function numerologis($number)    
    {
    $chars = preg_split('//', $number, -1, PREG_SPLIT_NO_EMPTY);
    $last_number = 0;
    foreach($chars AS $each_char) { $last_number = $last_number + $each_char; }
    if (strlen($last_number)>1) 
        {
        $last_number = numerologis($last_number);
        }
    
    return $last_number;
    }        

function cross_meridian($X_depart, $X_arrivee)
    {
    // Prendre le méridien ?
    if (( $X_depart <= $X_arrivee ) and ( $X_arrivee - $X_depart ) <= 150 )  {$meridien=false;}
    if (( $X_depart <= $X_arrivee ) and ( $X_arrivee - $X_depart ) > 150 )	{$meridien=true;}
    if (( $X_depart > $X_arrivee ) and (  $X_depart - $X_arrivee ) <= 150 )	{$meridien=false;}
    if (( $X_depart > $X_arrivee ) and (  $X_depart - $X_arrivee ) > 150 )	{$meridien=true;}

    return $meridien;
    }

function reindex_maps_field($maps, $X_depart, $Y_depart, $X_arrivee, $Y_arrivee)
    {
    if (isset($maps))
        {
        $meridien = cross_meridian($X_depart, $X_arrivee);

        //Initialisation pour reindexation :
        $y_reindex=1; $x_reindex=1;

        // Si méridien : Pour chaque rangée de Y, prendre dans l'ordre des X les plus grands, puis X les plus petits.
        if ($meridien)	
            {
            //Trouver si le départ est à gauche ou à droite de la vue :
            if ($X_depart > $X_arrivee) {$depart=false;}	//False = left = depart à guache, donc FirstX= depart	
            if ($X_depart <= $X_arrivee) {$depart=true;}	//true = right = depart à droite, donc FirstX = arrivee (gauche)

            if ($depart) 
                {
                $X_first=$X_arrivee;$X_last=$X_depart;
                $Y_first=$Y_arrivee;$Y_last=$Y_depart;
                }
            else 
                {
                $X_first=$X_depart;$X_last=$X_arrivee;
                $Y_first=$Y_depart;$Y_last=$Y_arrivee;
                }                    

            //Taille de la vue :
            if (!$depart) {$X_size=(300-($X_depart))+$X_arrivee+1;} else {$X_size=(300-$X_arrivee)+$X_depart+1;}
            $Y_size = $Y_arrivee-$Y_depart+1;                

/*
        echo ' x_dep='.$X_depart;
        echo ' y_dep='.$Y_depart;
        echo ' x_arr='.$X_arrivee;
        echo ' y_arr='.$Y_arrivee;            
        echo ' x_siz='.$X_size;
        echo ' y_siz='.$Y_size;
*/                

            //Dans l'idéal, le tableau fourni doit etre dans l'ordre des Y, puis des X --> (y=1, x=1) (y=1, x=2) ...
            //for ($y=$Y_depart; $y<=$Y_arrivee; $y++)
            for ($y=$Y_first; $y<=$Y_last; $y++)
                {
                $x=$X_first;
                while ($x<=300)
                    {
                    foreach($maps AS $key=>$each_map) //Trouver dans notre Champ l'objet correspondant.
                        {
                        if ($each_map->X()==$x AND $each_map->Y()==$y)
                            {
                            $maps_view[$x_reindex][$y_reindex]=$each_map;
                            unset($maps[$key]); break;
                            }
                        }
                    if (!isset($maps_view[$x_reindex][$y_reindex])) {$maps_view[$x_reindex][$y_reindex]='';}        
                    //  echo '('.$x_reindex.','.$y_reindex.')';
                    $x++;
                    $x_reindex++;
                    if ($x_reindex>$X_size) {$x_reindex=1;}
                    }
                $x=1;	
                while ($x<=$X_last)
                    {
                    foreach($maps AS $key=>$each_map)
                        {
                        if ($each_map->X()==$x AND $each_map->Y()==$y)
                            {
                            $maps_view[$x_reindex][$y_reindex]=$each_map;
                            unset($maps[$key]); break;
                            }
                        }
                    if (!isset($maps_view[$x_reindex][$y_reindex])) {$maps_view[$x_reindex][$y_reindex]='';}        
                //    echo '('.$x_reindex.','.$y_reindex.')'; 
                    $x++;
                    $x_reindex++;
                    if ($x_reindex>$X_size) {$x_reindex=1;}
                    }	
                $y_reindex++;	
                if ($y_reindex>$Y_size) {$y_reindex=1;}
            //    echo '<br/>';
                }
            }

        // Si pas de méridien :  Pour chaque rangée de Y, prendre dans l'ordre des X.	
        if (!$meridien)
            {
            //Trouver si le départ est à gauche ou à droite de la vue :
            if ($X_depart <= $X_arrivee) {$depart=false;} 
            if ($X_depart > $X_arrivee){$depart=true;}   //true = right = depart à droite, donc FirstX = arrivee (gauche)
            if ($depart) 
                {
                $X_first=$X_arrivee;$X_last=$X_depart;
                $Y_first=$Y_arrivee;$Y_last=$Y_depart;
                }
            else 
                {
                $X_first=$X_depart;$X_last=$X_arrivee;
                $Y_first=$Y_depart;$Y_last=$Y_arrivee;
                }

            //Taille de la vue :
            if (!$depart) {$X_size=$X_arrivee-($X_depart-1);} else {$X_size=$X_depart-($X_arrivee-1);}
            $Y_size = $Y_arrivee-$Y_depart+1;

            for ($y=$Y_first; $y<=$Y_last; $y++)
                {
                for ($x=$X_first; $x<=$X_last; $x++)
                    {
                    foreach($maps AS $key=>$each_map)
                        {
                        if ($each_map->X()==$x AND $each_map->Y()==$y)
                            {
                            $maps_view[$x_reindex][$y_reindex]=$each_map;
                            unset($maps[$key]); break;
                            }
                        }
                    if (!isset($maps_view[$x_reindex][$y_reindex])) {$maps_view[$x_reindex][$y_reindex]='';}
                    $x_reindex++;
                    if ($x_reindex>$X_size) {$x_reindex=1;}	
                    }			
                $y_reindex++;	
                if ($y_reindex>$Y_size) {$y_reindex=1;}			

                }			
            }

        return $maps_view;
        }
    }

function show_user_count()
    {
    $bdd_io = new bdd_io();
    $request = 'SELECT COUNT(id) AS NB_USER FROM users where id<>0';
    $result = $bdd_io->query($request);
    if (!$result) {return false;}
    while ($donnees = $result->fetch_assoc()) { $user_data = $donnees; } 
    $result->close();

    echo $user_data['NB_USER'];
    }
        
function css_add_player_class($player_color, $CSS_array)
    {
    foreach ($CSS_array as $key => $value)
        {
        if ($value==$player_color) //si oui, l'attribuer a cet item
            {
            // Pas lieu de modifier CSS_array, on sort
            return $CSS_array;
            }
        }
    
    $CSS_array[]=$player_color;
    return $CSS_array;
    }

function css_find_player_class($player_color, $CSS_array)
    {
    $CSS_info = '';
    foreach ($CSS_array as $key => $value)
        {
        if ($value==$player_color) //si oui, l'attribuer a cet item
            { $CSS_info = ' P'.$key; }
        }
    
    return $CSS_info;
    }


function klod_mail($sujet, $corps, $destinataires)
    {
    $reussite = FALSE;
    
    /*
     *  $destinatires = Array de mails.
     */
    
    //On prépare le corps du message :
    $corps .= wordwrap($corps, 70, "\n");
    $corps .= "\n";
    $corps .= '_____________________________________'."\n";
    $corps .= 'KlodAdmin'."\n";
    $corps .= 'www.klod-online.com'."\n";
    
    //On prepare le "destinataire bidon"
    $dest_bidon = 'admin@klod-online.com';

    //On prépare l'en tete :
    $en_tete = 'To: '.$dest_bidon."\n";
    $en_tete .= 'From: admin@klod-online.com'."\n";
    
    //On prépare les listes de destinataires :    
    $nb_liste = 0; $compteur_dest=0; $liste_destinataires[0]='';
    foreach($destinataires AS $each_dest) 
        {
        $liste_destinataires[$nb_liste] .= ', '.$each_dest; 
        
        $compteur_dest++;
        if ($compteur_dest>=50) 
            {
            // on passe a une autre liste :
            $nb_liste++; 
            $compteur_dest=0;
            }
        }
    
    //Enfin, on envoie le mail :
    foreach($liste_destinataires AS $each_liste)
        {
        //On reformate les liste :    
        $each_liste = substr($each_liste, 2);
        $each_liste = 'Bcc: '.$each_liste."\n";
        
        //On envoie le mail :
        $each_en_tete = $en_tete.$each_liste;

/*
    echo $dest_bidon.' ****<br/>';
    echo $sujet.' ****<br/>';
    echo $corps.' ****<br/>';
    echo $each_en_tete.' ****<br/>';
*/
        
        //$reussite = mail($dest_bidon, $sujet, $corps, $each_en_tete);
        $reussite = mail($dest_bidon, $sujet, $corps);
        
        }
    
    return $reussite;
    }


function page_protect()
    {
    //session_start();
    global $db;
    // Secure against Session Hijacking by checking user agent
    if (isset($_SESSION['HTTP_USER_AGENT']))
        { if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
            { logout(); exit; } }
    // before we allow sessions, we need to check authentication key - ckey and ctime stored in database
    // If session not set, check for cookies set by Remember me
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) )
        {
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key']))
            {
            //we double check cookie expiry time against stored in database
            $cookie_user_id  = filter($_COOKIE['user_id']);
            $rs_ctime = mysql_query("select `ckey`,`ctime` from `users` where `id` ='$cookie_user_id'") or die(mysql_error());
            list($ckey,$ctime) = mysql_fetch_row($rs_ctime);
            //cookie expiry
            if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) { logout(); }

            //Security check with untrusted cookies - dont trust value stored in cookie.
            //We also do authentication check of the `ckey` stored in cookie matches that stored in database during login
            if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  )
                {
                session_regenerate_id(); //against session fixation attacks.
                $_SESSION['user_id'] = $_COOKIE['user_id'];
                $_SESSION['user_name'] = $_COOKIE['user_name'];
                /* query user level from database instead of storing in cookies */
                list($user_level) = mysql_fetch_row(mysql_query("select user_level from users where id='$_SESSION[user_id]'"));
                $_SESSION['user_level'] = $user_level;
                $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
                }
            else
                { logout(); }
            }
        else
            {
            //header("Location: login.php");
            //header('Location:../website/index.php?page=login');
            echo '<script language="Javascript"> document.location.replace("../website/index.php?page=login"); </script>';
            exit();
            }
        }
    }
function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
} 

function filter($data)
    {
    $data = trim(htmlentities(strip_tags($data)));

	//if (get_magic_quotes_gpc())
    $data = stripslashes($data);

    $data = mysql_escape_mimic($data);
    return $data;
    }

function EncodeURL($url)
    {
    $new = strtolower(ereg_replace(' ','_',$url));
    return($new);
    }

function DecodeURL($url)
    {
    $new = ucwords(ereg_replace('_',' ',$url));
    return($new);
    }

function ChopStr($str, $len) 
    {
        if (strlen($str) < $len)
            return $str;

        $str = substr($str,0,$len);
        if ($spc_pos == strrpos($str," "))
                $str = substr($str,0,$spc_pos);

        return $str . "...";
    }

function isEmail($email)
    {
    return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
    }

function isUserID($username)
    {
    if (preg_match('/^[a-z\d_]{5,20}$/i', $username))
        { return true; } else { return false; }
    }
 
function isURL($url) 
    {
    if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url))
        { return true; } else { return false; }
    }

function checkPwd($x,$y) 
    {
    if(empty($x) || empty($y) ) { return false; }
    if (strlen($x) < 4 || strlen($y) < 4) { return false; }
    if (strcmp($x,$y) != 0) { return false; }
    return true;
    }

function GenPwd($length = 7)
    {
    $password = "";
    $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
    $i = 0;
    while ($i < $length)
        {
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        if (!strstr($password, $char))
            {
            $password .= $char;
            $i++;
            }
        }
    return $password;
    }

function GenKey($length = 7)
    {
    $password = "";
    $possible = "0123456789abcdefghijkmnopqrstuvwxyz";
    $i = 0;
    while ($i < $length) 
        {
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        if (!strstr($password, $char))
            {
            $password .= $char;
            $i++;
            }
        }
    return $password;
    }
    
function logout()
    {
    global $db;
    //session_start();

    if(isset($_SESSION['user_id']))
        {
        mysql_query("update `users` set `ckey`= '', `ctime`= '' where `id`='$_SESSION[user_id]'") or die(mysql_error());
        }
    if(isset($_COOKIE['user_id']))
        {
        mysql_query("update `users` set `ckey`= '', `ctime`= '' where `id`='$_COOKIE[user_id]'") or die(mysql_error());
        }        

    /************ Delete the sessions****************/
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_level']);
    unset($_SESSION['HTTP_USER_AGENT']);
    session_unset();
    session_destroy();

    /* Delete the cookies*******************/
    setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
    setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
    setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
    
    //header("Location: login.php");
    /*
    echo '<script language="Javascript">
        <!--
        document.location.replace("index.php");
        // -->
        </script>';
    */
    //echo '<script language="Javascript"> document.location.replace("../website/index.php"); </script>';    
    
    
    }

// Password and salt generation
function PwdHash($pwd, $salt = null)
    {
    if ($salt === null)     
        { $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH); }
    else     
        { $salt = substr($salt, 0, SALT_LENGTH); }
    return $salt . sha1($pwd . $salt);
    }

function checkAdmin() { if($_SESSION['user_level'] == ADMIN_LEVEL) { return 1; } else { return 0 ;} }

function show_hex_color($R,$G,$B)
    {
    $R_HEX=dechex($R); $G_HEX=dechex($G); $B_HEX=dechex($B);
    if (strlen($R_HEX)<=1) {$R_HEX='0'.$R_HEX;}
    if (strlen($G_HEX)<=1) {$G_HEX='0'.$G_HEX;}
    if (strlen($B_HEX)<=1) {$B_HEX='0'.$B_HEX;}

    echo '<option style="background-color:#'.$R_HEX.$G_HEX.$B_HEX.'" value="#'.$R_HEX.$G_HEX.$B_HEX.'">#'.$R_HEX.$G_HEX.$B_HEX;
    echo '</option>';
    //return ('#'.$R_HEX.$G_HEX.$B_HEX);
    }

function name_by_size($pop)
    {
    if ($pop>0 AND $pop<=1000)          { return 'Hameau'; }
    elseif ($pop>1000 AND $pop<=5000)   { return 'Colonie'; }
    elseif ($pop>5000 AND $pop<=10000)  { return 'Village'; }
    elseif ($pop>10000 AND $pop<=20000) { return 'Bourg'; }
    elseif ($pop>20000)                 { return 'Ville'; }        
    }
?>
