<?php
session_start();
require_once dirName(__FILE__).'./../log/logger_class.php';
$dh = new DeleteHistory();
$dh->delete_from_db();

class DeleteHistory
{
    private $db;
    private $member_id;
    public function __construct()
    {
        require_once dirName(__FILE__).'./../Classes/Db_work.php';
        $this->db = Database::getInstance();
        $this->member_id = $_SESSION['MEMBER_ID'];
    }
    
    public function delete_from_db()
    {
        //$queryResult = $this->db->do_query("DELETE FROM AddedVKUsers WHERE user_id='".$this->member_id."'");
        $queryResult = $this->db->do_query("DELETE FROM AddedVKUsers WHERE user_id=?", [$this->member_id]);
    }
}