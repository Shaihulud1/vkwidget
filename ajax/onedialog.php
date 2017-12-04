<?php
    require_once '../Classes/Db_work.php';
    require_once '../Classes/Vk_work.php';
    if (isset($_POST['id'])):
        if($_POST['reload'] != ''){
            sleep(2);
        }
        $id = htmlspecialchars($_POST['id']);
        $profile_id = $_SESSION['profile_id'];
        $vtoken = $_SESSION['TOKEN'];
        $m_id = $_SESSION['member_id'];
        $fullname = htmlspecialchars($_POST['fullname']);
        $my_name = $_SESSION['fist_name'].' '.$_SESSION['last_name'];
        $my_ava = $_SESSION['ava_link'];
        $d_ava = $_POST['d_ava'];
        $VK = new VK_work;
        $VK->set_user($m_id);
        $VK->set_token($vtoken);
        $dialog = $VK->one_dialog($id);
    ?>
    <div class="one-dialog">
        <input type="hidden" class = "vtok" value = '<?=$vtoken?>'>
        <input type="hidden" class = "id" value = '<?=$id?>'>
        <input type="hidden" class = "fullname" value = '<?=$my_name?> (id<?=$profile_id?>)'>
        <input type="hidden" class = "other_avatar" value = '<?=$d_ava?>'>
        <input type="hidden" class = "other_id" value = '<?=$id?>'>
        <div class="bx-popup bx-popup-big" style="width: 477px;">
            <div class="bx-popup-header" style="display: none;">
                Заголовок попапа
                <span class="bx-popup-close-btn"></span>
            </div>
            <p class="bx-popup-content" contenteditable="true" style="height: 130px; width: 460px; overflow: auto;"></p>
            <div class="bx-popup-footer">
                <span class="bx-button bx-button-accept send_mess">Отправить</span>
                <span class="bx-button-decline-link reload_dialog">Обновить диалог</span>
                <span class="bx-button-decline-link cancel_dialog">Вернуться</span>
            </div>
        </div>	
        <?foreach ($dialog['response']['items'] as $di):
            if($di['user_id'] == $di['from_id']):?>
            <div class="new-message-item other">
            <img src="<?=$d_ava?>" id = "d_ava" alt="ava">
                    <b><?=$fullname?> (id<?=$id?>)</b>
                    <p><?=$di['body']?></p>
                    <div class="bx-popup pop">
                            <span class="bx-popup-text"><?=$di['body']?></span>
                            <span class="bx-popup-arrow"></span>
                    </div>								
            </div>
            <? else:?>
            <div class="new-message-item me">
            <img src="<?=$my_ava?>" id = "my_ava"alt="ava">
                <b><?=$my_name?> (id<?=$profile_id?>)</b>
                <p><?=$di['body']?></p>
                <div class="bx-popup pop">
                    <span class="bx-popup-text"><?=$di['body']?></span>
                    <span class="bx-popup-arrow"></span>
                </div>
            </div>					
            <?endif;?>
        <?php endforeach;?>	
        <?php endif;?>
    </div>