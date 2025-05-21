<?php

class player extends app_obj
    {
    /*
     * ----------------------------------------
     *          VITAL PART
     * ----------------------------------------
     */

    // Les Variables :
    private $_id = 0;
    private $_name = 'nobody';
	private $_hashpass = '######';
	private $_ip = '192.168.1.1';
	private $_mail = 'nobody@192.168.1.1';
	private $_activcode = '9999';
	private $_paidto = 0;
	
    // Construction :
    public function __construct($data=null) {$this->hydrate($data);}
    
    // Acceder aux variables :
    public function id()	{ return $this -> _id; }
	public function name()	{ return $this -> _name; }		
	public function hashpass()	{ return $this -> _hashpass; }
	public function ip()	{ return $this -> _ip; }
	public function mail()	{ return $this -> _mail; }
	public function activcode()	{ return $this -> _activcode; }
	public function paidto()	{ return $this -> _paidto; }
		
    // Definir les variables a une valeure donnée
    public function set_id($id)		{ $this -> _id = (int) $id; }
	public function set_name($name)	{ $this -> _name = (string) $name;}
	public function set_hashpass($hashpass)	{ $this -> _hashpass = (string) $hashpass;}
	public function set_ip($ip)	{ $this -> _ip = (string) $ip;}
	public function set_mail($mail)	{ $this -> _mail = (string) $mail;}
	public function set_activcode($activcode)	{ $this -> _activcode = (int) $activcode;}
	public function set_paidto($paidto)	{ $this -> _paidto = (int) $paidto;}

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

	public function expiracy_date($lang='en')
		{
		$for_js_to_translate = '<span class="trtm">'.$this->paidto().'</span>';
		return $for_js_to_translate;
		}

	public function add_days($days)
		{
		// $days = $month * 30;
		$curr_paid = new DateTime();
		$curr_paid->setTimestamp($this->paidto());
		$now = new DateTime();

		if ($curr_paid>$now) { $base_date = $curr_paid; }
		else { $base_date = $now; }

		$base_date->add(new DateInterval('P'.$days.'D'));
		$base_date->setTime(23,59);


		$this->set_paidto($base_date->getTimestamp());
		
		
		}

	public function fee_status()
		{
			// $now = time();
			// $this->set_paidto($now+100000);

		if (time() >= $this->paidto()) { return false; }
		else { return true; }
		}		

	public function json_token($secret = 'SECRET_KEY')	
		{
		//build the headers
		$headers = ['alg'=>'HS256','typ'=>'JWT'];
		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($headers)));

		//build the payload
		$payload = ['meta_id'=>$this->id(),'name'=>$this->name(), 'fee_paid'=>$this->paidto()];
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
		
		//build the signature
		$signature = hash_hmac('sha256',"$base64UrlHeader.$base64UrlPayload",$secret,true);
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		//build and return the token
		$token = $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;

		return $token;
		}		
	
    /*
     * ----------------------------------------
     *      HTML Generators
     * ----------------------------------------
     */   
/*        
    public function html_inventaire()
        {
        $html_data = '';
       
        return $html_data;
        }
		
	public function html_form()
		{
		$html_data = '';
		
       
        return $html_data;
		}
*/		
    }       
	
?>
