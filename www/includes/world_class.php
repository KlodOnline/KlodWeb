<?php

class world extends app_obj
    {
    /*
     * ----------------------------------------
     *          VITAL PART
     * ----------------------------------------
     */

    // Les Variables :
    private $_id = 0;
    private $_name = 'servername';
	private $_address = '192.168.1.1';
	private $_demo = 'false'; // Sadly SQL and PHP doesn't agree about boolean.
	
    // Construction :
    public function __construct($data=null) {$this->hydrate($data);}
    
    // Acceder aux variables :
    public function id()	{ return $this -> _id; }
	public function name()	{ return $this -> _name; }		
	public function address() { return $this -> _address; }
	public function demo()	{ return $this -> _demo; }
		
    // Definir les variables a une valeure donnée
    public function set_id($id)		{ $this -> _id = (int) $id; }
	public function set_name($name)	{ $this -> _name = (string) $name;}
	public function set_address($address)	{ $this -> _address = (string) $address;}
	public function set_demo($demo)	{ $this -> _demo = (string) $demo;}

	/*
     * ----------------------------------------
     *          METHODS
     * ----------------------------------------
     */ 
	 
	public function get_var_names()	
		{
		$var_names = array();
		$var_and_default_value = get_class_vars(get_class($this)); 
		foreach($var_and_default_value AS $name => $value) { $var_names[] = substr($name, 1); }
		return $var_names;
		}

	public function correct_address($docker=false)	
		{
		$address = $this->address();
		if (substr($address, -1)!='/') { $address = $address.'/';}

		$address = str_replace('/klod2webgui', '', $address);

		if ($docker) {
			$address = str_replace(':443', ':2443', $address);
			$address = preg_replace('/172\.17\.\d+\.\d+/', '127.0.0.1', $address);
		}

		return $address;
		}

	public function status()
		{
		$url = $this->correct_address().'status.php';
		$curlinit = curl_init($url);

		// Configuration de l'URL et d'autres options
		curl_setopt($curlinit, CURLOPT_URL, $url);

		curl_setopt($curlinit, CURLOPT_TIMEOUT, 10);  
    	curl_setopt($curlinit, CURLOPT_CONNECTTIMEOUT, 10);


		// IGNORE SSL = DANGER IN PRODUCTION !
		curl_setopt($curlinit, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlinit, CURLOPT_SSL_VERIFYHOST, false);

		curl_setopt($curlinit, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlinit, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		$response = curl_exec($curlinit); // $response is true or false... ?!
		$httpCode = curl_getinfo($curlinit, CURLINFO_HTTP_CODE);
		
		if ($response === false) {
			$error = curl_error($curlinit);
			// echo "cURL Error: $error";
			return '<img class="status" src="./img/server_unknown.png" title="cURL error">';

		} else {
			if ($httpCode == 404) { return '<img class="status" src="./img/server_down.png" title="404">'; }
			if ($response == '')  { return '<img class="status" src="./img/server_down.png" title="Other Error">'; }

		}

		curl_close($curlinit);

		return $response;
		}



    }       
	
?>
