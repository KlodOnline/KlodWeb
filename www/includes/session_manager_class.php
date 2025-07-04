<?php

class session_manager
{
    public function delete()
    {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['name']);
        session_write_close();
    }

    public function read()
    {
        // Chopper les données dans $_SESSION :
        session_start();
        $session_data = $_SESSION;
        session_write_close();

        // Et construire l'objet :
        $session = new session($session_data);
        return $session;
    }

    public function update(session $session)  // Imposer un objet de la class session
    {
        $session_data = $session->get_all();
        session_start();
        foreach ($session_data as $key => $each_data) {
            $_SESSION[$key] = $each_data;
        }
        session_write_close();
    }
}
