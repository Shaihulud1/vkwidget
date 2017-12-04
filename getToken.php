<?php
    session_start();
    require_once 'Classes/Db_work.php';
    require_once 'Classes/Bitrix24.php';
    require_once 'Classes/CheckInput.php';
    require_once 'log/logger_class.php';
    $tokenin = checkString($_POST['token']);
    $status = checkString($_SESSION['TOKKEN_ERROR']);
    $bitName = $_SESSION['EMAIL'];
    $db = Database::getInstance();
    
    if ($status == 'NO TOKEN'){
        $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = ? WHERE bitName = ?", [$tokenin, $bitName]);
    }elseif ($status == 'FIRST VISIT' && $tokenin != ''){
        $makeToken = $db->do_query("INSERT INTO AppUsers (bitName, vtoken, member_id) VALUES (?, ?, ?)", [$_SESSION['EMAIL'], $tokenin, $member_id]);
    }elseif ($status == 'WRONG TOKKEN' && $tokenin != ''){
        $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = ? WHERE bitName = ?", [$tokenin, $bitName]);
    }elseif ($tokenin == '') {
        echo 'Произошла ошибка, свяжитесь с разработчиками';
    }
    echo 'Вернитесь на предыдущую вкладку и нажмите кнопку ВОЙТИ';