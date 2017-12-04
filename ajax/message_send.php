<?php
require_once '../Classes/Vk_work.php';
require_once '../Classes/CheckInput.php';
if($_POST['message'] != ''){
    $message = checkString($_POST['message']);
    $vtoken = $_SESSION['TOKEN'];
    $id = checkString($_POST['id']);
    $fullname = $_SESSION['fist_name'].' '.$_SESSION['last_name'];
    $my_ava = $_SESSION['ava_link'];
    $VK = new VK_work;
    $VK->set_token($vtoken);
    $VK->message_send($id, $message);?>
    <div class="new-message-item me">
        <img src='<?=$my_ava?>' id = "my_ava"alt="ava">
        <b><?=$fullname?></b>
        <p><?=$message?></p>
        <div class="bx-popup pop">
            <span class="bx-popup-text"><?=$message?></span>
            <span class="bx-popup-arrow"></span>
        </div>								
    </div>		
<?php
    } else {
        echo 'Вы не ввели сообщение';
    }