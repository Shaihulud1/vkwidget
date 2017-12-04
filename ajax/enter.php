<?php
    require_once '../Classes/Db_work.php';
    require_once '../Classes/Bitrix24.php';
    require_once '../Classes/CheckInput.php';
    require_once '../log/logger_class.php';
    if(isset($_POST['tokenin'])):
        $tokenin = checkString($_POST['tokenin']);
        $status = checkString($_SESSION['TOKKEN_ERROR']);
        $member_id = $_SESSION['MEMBER_ID'];
        $bitName = $_SESSION['EMAIL'];
        $dom = $_SESSION['DOMAIN'];
        $db = Database::getInstance();
        $bitrix24 = new Bitrix($member_id);
        $bitrix24->domain = $dom;
        $userArray = $bitrix24 ->B24Method('user.current', '');

        if($status == 'NO TOKEN'){
/*            $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = '".$tokenin."' 
                                                                                WHERE member_id = '".$member_id."'");*/
            $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = ? WHERE bitName = ?", [$tokenin, $bitName]);
        }elseif($status == 'FIRST VISIT' && $tokenin != ''){
/*            $makeToken = $db->do_query("INSERT INTO AppUsers (bitName, vtoken, member_id)
                                                                                        VALUES
                                                                        ('".$_SESSION['EMAIL']."', '".$tokenin."','".$member_id."')");*/
            $makeToken = $db->do_query("INSERT INTO AppUsers (bitName, vtoken, member_id) VALUES (?, ?, ?)", [$_SESSION['EMAIL'], $tokenin, $member_id]);
        }elseif($status == 'WRONG TOKKEN' && $tokenin != ''){
/*            $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = '".$tokenin."' 
                                                                                WHERE member_id = '".$member_id."'");*/
            $makeToken = $db->do_query("UPDATE AppUsers SET vtoken = ? WHERE bitName = ?", [$tokenin, $bitName]);
        }elseif($tokenin == ''){
            echo 'Вы не ввели токен';
        }
    endif;

        
        //3c6d5f8fd405f422b2dbdeb9c4357460645dd617da8110a7cb1940f7e994547d2d7148a5b8ad7aff7a6f4