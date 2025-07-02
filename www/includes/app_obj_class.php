<?php

/*
 * ------------------------------------------------------------
 *
 *  Cet objet est la "racine" de tout les objets de l'appli.
 *  Il gère :
 *      - Hydratation
 *      - Listing des valeurs
 *      - sauvegarde qui s'adresse au bon manager de l'objet
 *      - Affichage texte des objets
 *
 * ------------------------------------------------------------
 */

class app_obj
{
    /*
     * --------------------------------
     *      Méthodes constructions
     * --------------------------------
     */

    public function hydrate($data = null)
    {
        // Lister les valeurs attendues, elle seront mise à leur valeur par défaut si nécessaire :
        $var_names = $this->get_var_names();

        if (is_array($data)) {
            // Si on nous donne une donnée, on excute la methode SET de l'objet pour cette donnée
            foreach ($data as $data_key => $value) {
                $method = 'set_'.$data_key;
                if (method_exists($this, $method)) {
                    $this -> $method($value);
                }

                // Retirer de la liste la  variable initialisée :
                foreach ($var_names as $var_key => $each_name) {
                    if ($each_name === $data_key) {
                        unset($var_names[$var_key]);
                    }
                }
                unset($each_name);
                unset($var_key);
            }
        }
    }

    /*
     * --------------------------------
     *      Méthodes présentation
     * --------------------------------
     */

    // Lister toutes les valeurs :
    public function get_all()
    {
        $var_names =  $this->get_var_names();
        $all_vars = [];
        foreach ($var_names as $each_var_name) {
            $all_vars[$each_var_name] = $this->$each_var_name();
        }
        return $all_vars;
    }

    // Afficher toutes les valeurs :
    public function show_all()
    {

        $class = get_class($this);

        echo 'Afficher l\'objet : <b>'.$class.'</b><br/>';

        $all_vars = $this->get_all();

        foreach ($all_vars as $key => $each_var) {
            echo  '<i>_'.strtoupper($key).'</i> = <b>'.$each_var.'</b><br/>';
        }
    }

    /*
     * --------------------------------
     *      Méthodes stockage
     * --------------------------------
     */
    public function delete()
    {
        $object_manager = $this->my_manager();
        $object_manager->delete($this);
    }

    public function save()
    {
        $object_manager = $this->my_manager();
        // Si l'objet a une ligne dans la table, on update, sinon, on insere.
        $object_read = $object_manager->read($this->id());

        if (isset($object_read)) {
            $object_manager->update($this);
        } else {
            // Ici, on insère, il faut récupérer l'ID de l'objet, et le fixer.
            $id = $object_manager->insert($this);
            $this->set_id($id);
        }
        // Vu qu'on ne peut savoir si on nous appelle pour un save ou un insert, on revoie toujours l'ID
        return $this->id();
    }

    public function my_manager()
    {
        // Charger le bon manager :
        $class_name = get_class($this);
        $manager_name = $class_name.'_manager';
        $object_manager = new $manager_name();

        return $object_manager;
    }

    /*
    * -------------------------------------
    *      Méthodes Interface inter-objet
    * -------------------------------------
    */
    public function session()
    {
        $session_manager = new session_manager();
        $session = $session_manager->read();
        return $session;
    }

    // Methode qui gere les methode inexistantes :
    /*
     *  Chaque 'app_obj' enfant a une table qui lui correspond en bdd
     *  (ex: la class 'persona' a une table persona). Chaque colonne de cette
     *  table correspond a une variable de la class (ex : colonne 'name'
     *  est la private '$_name').
     */

    public function __call($method_name, $arguments)
    {
        // Une méthode inconnue est appellée !

        // Est ce qu'elle ne serait pas un objet avec lequel nous avons une relation ?
        $needed_method = $method_name.'_id';
        if (method_exists($this, $needed_method)) {           // Si on a une colonne avec ce nom suffixé '_id', alors oui
            $class_manager_name = $method_name.'_manager';
            $object_manager = new $class_manager_name();
            return $object_manager->read($this->$needed_method());
        }

        // Il n'y a eu aucun return, on affiche une erreur :
        echo ' Erreur : Méthode indéfinie :  '.get_class($this).'::'.$method_name.' ';
    }

    /*
     * --------------------------------
     *      Méthodes communes diverses
     * --------------------------------
     */

    public function html_buttons($buttons)
    {
        $html_data = '';
        if (isset($buttons) and is_array($buttons)) {
            foreach ($buttons as $button_info) {
                $html_data .= '<a '.$button_info['link'].'>'.$button_info['info'].'</a>';
            }
        }
        return $html_data;
    }

    public function html_mini_sheet()
    {
        $html_data = '';

        // Gros Dummy
        $html_data = $this->show_all();

        return $html_data;
    }

}
