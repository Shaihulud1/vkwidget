<?php
require_once '../Classes/Vk_work.php';
require_once '../Classes/Db_work.php';
if(isset($_POST['formore'])):
    $formore = htmlspecialchars($_POST['formore']);
    $bitUse = htmlspecialchars($_POST['bitUse']);
    $db = Database::getInstance();
    $getToken = $db->fetch_query("SELECT bitName, vtoken FROM AppUsers WHERE bitName = '".$bitUse."'"); 
    $VK = new VK_work;
    $VK->set_token($getToken[0]['vtoken']);
    $msgs = $VK->dialogs_get('0', $formore);
    if(!empty($msgs)):
            foreach($msgs as $msg):?>
                    <div class="new-message-item mess-item">
                            <input type="hidden" value="<?=$msg['user_id']?>">
                            <img src="<?=$msg['photo_link']?>" alt="ava">
                            <b><?=$msg['name']?> (id<?=$msg['user_id']?>)</b>
                            <p><?=$msg['comment']?></p>
                            <div class="bx-popup pop">
                                    <span class="bx-popup-text"><?=$msg['comment']?></span>
                                    <span class="bx-popup-arrow"></span>
                            </div>								
                    </div>
            <?endforeach;
    else:
            echo 'Больше сообщений нет.';
    endif;
endif;