<?php

class session extends app_obj {

    /*
     * --------------------------------
     *          VITAL PART
     * --------------------------------
     */   
    
    // Les Variables :
    private $_id;
    private $_name;
    
    // Construction à partir de données :
    public function __construct($data=null) { $this->hydrate($data); }        
    
    // Acceder aux variables :
    public function id()        { return $this -> _id; }
    public function name()      { return $this -> _name; }

    // Definir les variables a une valeure donnée :
    public function set_id($id)                 { $this -> _id = (int) $id; }
    public function set_name($name)             { $this -> _name = (string) $name; }
    
    // Valeur par défaut des variables :
    public function id_default()	{ $this->set_id(0); }
    public function name_default()	{ $this->set_name(''); }
        
    // Lister les variables : (utile au parent)
    public function get_var_names() {
        $var_names = array();
        $var_names[] = 'id';
        $var_names[] = 'name';
        return $var_names;
    }    
        
    /*
     * --------------------------------
     *          METHODS
     * --------------------------------
     */        
/*    
    public function change_page($new_page_given) {
        $new_page = (string) $new_page_given;
        if (isset($new_page) ) { $this->set_page($new_page); $this->save(); }
    }           
  */      
}    
    
?>
