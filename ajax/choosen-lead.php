<?php 
session_start();
require_once dirName(__FILE__).'./../Classes/Db_work.php';
require_once dirName(__FILE__).'./../Classes/Bitrix24.php';


$bitrix24 = new Bitrix($_SESSION['MEMBER_ID']);

if (isset($_POST['jsonData'])){
    $commentsArr = json_decode($_POST['jsonData'], true);
    $db = Database::getInstance();
    $bitrix24 = new Bitrix($_SESSION['MEMBER_ID']); 
    if(!empty($commentsArr)){
        $bitrix24->addLeadsBatch($commentsArr);
        foreach($commentsArr as $comment){
            //$db->do_query("INSERT INTO AddedVKUsers (user_id, vk_id) VALUES ('".$_SESSION['MEMBER_ID']."', '".$comment['id']."')");
            $db->do_query("INSERT INTO AddedVKUsers (user_id, vk_id) VALUES (?, ?)", [$_SESSION['MEMBER_ID'], $comment['id']]);
        }   
        echo 'Выбранные лиды успешно добавлены'; 
    } 
}


