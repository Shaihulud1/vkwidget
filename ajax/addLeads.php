<?php
session_start();
require_once dirName(__FILE__).'./../Classes/Vk_work.php';
require_once dirName(__FILE__).'./../Classes/Db_work.php';
$db = Database::getInstance();
$VK = new VK_work();
/*$_SESSION['MEMBER_ID'] = $VK->set_user('68b50a8a84eff1d69336bbd2c90a70a6');
$_SESSION['TOKEN'] = $VK->set_token('5c66dd110f0968796e8a607c536b7dc7aece0102f7e79347e23f14251fefbcd6abcfb91bdf2ce916a149d');*/
$VK->set_user($_SESSION['MEMBER_ID']);
$VK->set_token($_SESSION['TOKEN']);

$ga = $VK->users_get_all();

$gb = $VK->get_users_ids();
if(!empty($gb) && !empty($ga))
{
    $vk['id'] = $ga[0]['id'];
    $in = str_repeat('?,', count($gb)-1);
    $in.='?';
    //$queryResult = $db->fetch_query_num("SELECT vk_id AS id FROM AddedVKUsers JOIN AppUsers ON user_id=member_id WHERE vk_id IN (".$in.")", $gb);
    $gb[] = $_SESSION['MEMBER_ID'];
    $queryResult = $db->fetch_query_num("SELECT vk_id AS id FROM AddedVKUsers WHERE (vk_id IN (".$in.")) and (user_id = ?)", $gb);

    $bitrix24 = new Bitrix($_SESSION['MEMBER_ID']);
    $b24out = [];
    $vk_id = [];
    foreach ($ga as $vk)
    {
        if (!in_array($vk['id'], $queryResult))
        {
            $b24out[] = $vk;
            $queryDB = $db->do_query("INSERT INTO AddedVKUsers (user_id, vk_id) VALUES (?, ?)", [$_SESSION['MEMBER_ID'], $vk['id']]);
        }
    }
    if ($b24out !== array())
    {
        print_r($bitrix24->addLeadsBatch($b24out));
        echo 'Данные успешно обновлены';
    }
    else
    {
        echo 'Новые данные отсутствуют';
    }
}else{
    echo "Диалоги отсутствуют";
}
