<?php
    session_start();
    require_once dirName(__FILE__).'./../Classes/Db_work.php';
    require_once dirName(__FILE__).'./../log/logger_class.php';
    if(isset($_POST['bitUse'])){
        $member_id = $_SESSION['MEMBER_ID'];
        $db = Database::getInstance();
        $getToken = $db->do_query("UPDATE AppUsers SET vtoken = 'exit' WHERE member_id = ?", [$member_id]);
        //$getToken = $db->do_query("UPDATE AppUsers SET vtoken = 'exit' WHERE member_id = '".$member_id."'");
        session_unset();
        session_destroy();  	
    }