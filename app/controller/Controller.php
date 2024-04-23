<?php
class Controller
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        include('view/welcome.php');
    }
}
?>