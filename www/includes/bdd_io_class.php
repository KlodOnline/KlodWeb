<?php

class bdd_io
    {
    /*
     * ------------------------------------------
     *      Variables & Construction
     * ------------------------------------------
     */   
    
    private $_db;          //Connection en mysqli
    private $_my_class;    // La class de l'enfant qui nous apelle 

    public function __construct() 
        {
        // L'acces BDD
        global $db_mysqli;
        $this -> set_db($db_mysqli); 
        
        $my_class = get_class($this);
        $my_class = str_replace('_manager', '', $my_class);
        $this->set_my_class($my_class);
        }
        
    // Set & Get
    public function set_db($db)             { $this ->_db = $db; }
    public function set_my_class($my_class) { $this ->_my_class = $my_class; }
    
    public function db()        { return $this->_db; }
    public function my_class()  { return $this->_my_class; }
    
    /*
     * ------------------------------------------
     *  Manager de communication avec la BDD
     * ------------------------------------------
     */
    
    // La connection à la BDD :
    public function connect_db()
        {
        $db_mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$db_mysqli->set_charset("utf8");	// jeu de caractère
        $this->set_db($db_mysqli);        
        }
    
    public function debug_db($request='UNKNOWN')
        {
        if ($this->db()->error) 
            {
            try 
                {
                throw new Exception("<br>MySQL error ".$this->db()->error." <br> Query:<br> ".$request.", ".$this->db()->errno."");
                }
            catch(Exception $e ) 
                {
                echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
                echo nl2br($e->getTraceAsString());
                }
            }
        }        

    /*
     * ------------------------------------------
     *      Requete & traitement des résultats
     * ------------------------------------------
     */    
        
    /********* TRAITEMENT DES RESULTATS *********/
    public function query($request)
        {
        $this->connect_db();
        $result = $this->db()->query($request);
        $this->debug_db($request);
        $this->db()->close();
        if (isset($result)) {return $result;}
        }
    public function to_object($result, $class)
        {
        if ($result==false) {return;}
        while ($donnees = $result->fetch_assoc()) { $object = new $class($donnees); }
        $result->close();
        if (isset($object))  { return $object; }
        }
    public function to_objects($result, $class)
        {
        if (!$result) {return;}
        $objects = array();
        while ($donnees = $result->fetch_assoc()) 
            { 
            $object = new $class($donnees); 
            if (isset($object)) { $objects[] = $object; }
            } $result->close();
        if (isset($objects)) {return $objects;}
        }        
    public function get_total($result)    
        {
        if (!$result) {return;}
        while ($donnees = $result->fetch_assoc()) { $count = (int) $donnees['TOTAL']; } $result->close();
        if (isset($count)) {return $count;}    
        }
        
    /*
     * ------------------------------------------
     *      Construction des requetes
     * ------------------------------------------
     */  
        
    /********* LECTURE *********/
        
    public function read($id)
        {
        // if ($id==0) {return;}
        $class = $this->my_class();
        $request = 'SELECT * FROM '.$class.' WHERE id='.$id;
        $result = $this->query($request);
        $object = $this->to_object($result, $class);
        if (isset($object)) {return $object;}
        }
    public function read_by($column, $value, $comparateur)
        {
        $class = $this->my_class();
        $request = 'SELECT * FROM '.$class.' WHERE '.$column.$comparateur.$value.' LIMIT 1';
        $result = $this->query($request);
        $object = $this->to_object($result, $class);
        if (isset($object)) {return $object;}
        }        
    public function count_by($column, $value, $comparateur)
        {
        $class = $this->my_class();
        $request = 'SELECT COUNT(*) AS TOTAL FROM '.$class.' WHERE '.$column.$comparateur.$value;
        $result = $this->query($request);
        $count = $this->get_total($result);
        if (isset($count)) {return $count;}
        }

    public function read_multiple($where)
        {
        $class = $this->my_class();
        $request = 'SELECT * FROM '.$class.' WHERE '.$where;
        $result = $this->query($request);
        $objects = $this->to_objects($result, $class);
        if (isset($objects)) { return $objects; }
        }        
    public function read_all($limit='none')
        {
        $class = $this->my_class();
		if ($limit!=='none') { $request = 'SELECT * FROM '.$class.' WHERE id IS NOT NULL ORDER BY id LIMIT '.$limit; }
			else { $request = 'SELECT * FROM '.$class.' WHERE id IS NOT NULL ORDER BY id'; }
        $result = $this->query($request);
        $objects = $this->to_objects($result, $class);
        if (isset($objects)) { return $objects; }
        }
	
    public function read_columns()
        {
	$class = $this->my_class();
	$request = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="'.$class.'"';
	$result = $this->query($request);
	if (!$result) {return;}
	while ($donnees = $result->fetch_assoc()) { $cols[] = $donnees['COLUMN_NAME']; }
        $result->close();
	return $cols;
	}

    /********* ECRITURE *********/    
        
    public function insert($object)  
        {
        $class = get_class($object);
        $object_vars = $object->get_all();
        
        // Redaction de la requete :
        $col_list = ''; $value_list='';
        foreach($object_vars AS $key => $each_var) 
			{
             if ($key!=='id') // OR $class==='player') 
				{
				$col_list = $col_list.$key.', ';
				$value_list = $value_list.'?, '; 
				$to_binds[]=(string) $each_var; 
				}
            }
        $col_list = substr($col_list, 0, -2);
        $value_list = substr($value_list, 0, -2);
        
        $request = 'INSERT INTO '.$class.' ('.$col_list.') VALUES ('.$value_list.')';
        $bind_type = 's';
        
        $this->connect_db();
        $statement = $this->db()->prepare($request);
        $this->debug_db($request);

        $to_binds = array_merge(array(str_repeat($bind_type, count($to_binds))), array_values($to_binds));
        call_user_func_array(array(&$statement, 'bind_param'), refValues($to_binds));
        $statement->execute();

        $id = $statement->insert_id;

        $object->set_id($id);
        $this->db()->close();
        
        return $id;
        }
     
    public function update_multiple($column, $old_value, $new_value)    
        {
        $class = $this->my_class();
                
        // Redaction de la requete :
        $request = 'UPDATE '.$class.' SET '.$column.'=? WHERE '.$column.'='.$old_value;
        
        $to_binds[]=$new_value; 
        $bind_type = 's';

        //Prepare la requete :
        $this->connect_db();
        $statement = $this->db()->prepare($request);
        $this->debug_db($request);
        $to_binds = array_merge(array(str_repeat($bind_type, count($to_binds))), array_values($to_binds));
        call_user_func_array(array(&$statement, 'bind_param'), refValues($to_binds));
        $statement->execute();
        $this->db()->close();        

        }
        
    public function update($object)    
        {
        $class = get_class($object);
        $object_vars = $object->get_all();
                
        // Redaction de la requete :
        $request = 'UPDATE '.$class.' SET ';
        //$bind_type='';
        foreach($object_vars AS $key => $each_var) 
            {
            if ($key!='id') 
                {
                $request = $request.$key.'=?, '; 
                $to_binds[]=$each_var; 
                }
            }
        $request = substr($request, 0, -2);
        $request = $request.' WHERE id='.$object->id();
        
        $bind_type = 's';

        //Prepare la requete :
        $this->connect_db();
        $statement = $this->db()->prepare($request);
        $this->debug_db($request);

        $to_binds = array_merge(array(str_repeat($bind_type, count($to_binds))), array_values($to_binds));
        call_user_func_array(array(&$statement, 'bind_param'), refValues($to_binds));
        $statement->execute();
        $this->db()->close();
        
        }

    public function delete($object)    
        {
        $class = get_class($object);
        $id = $object->id();

        if (isset($class) AND isset($id))
            {
            $this->connect_db();
            $r = $this->db()->query('DELETE FROM '.$class.' WHERE id='.$id); 
            
            if ($r)
                {
                $this->db()->close();
                return true; 
                }
            else
                {
                $msg = $this->db()->mysqli_info();
                $this->db()->close();
                return $msg;
                }
            
            }
        }
    }
	
	
	
?>
