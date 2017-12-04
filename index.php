<?php session_start(); ?>
<?php require_once 'token.php';?>
<?php require_once 'Classes/Bitrix24.php';?>
<?php require_once 'Classes/Db_work.php';?>
<?php require_once 'Classes/Vk_work.php';?>
<?php require_once 'log/logger_class.php';?>
<?php require_once 'Classes/CheckInput.php';?>
<?php
/*print_r($_REQUEST);
echo '<br>';*/
    $_SESSION['MEMBER_ID'] = checkString($_REQUEST['member_id']);
    $_SESSION['AUTH_ID'] = checkString($_REQUEST['AUTH_ID']);
    $_SESSION['REFRESH_ID'] = checkString($_REQUEST['REFRESH_ID']);
    $_SESSION['PROTOCOL'] = checkString($_REQUEST['PROTOCOL']);
    $_SESSION['DOMAIN'] = checkString($_REQUEST['DOMAIN']);
    $_SESSION['TOKEN'] =''; 
 	       
    $logger = Logger::getInstance();
    $err_arr = [];
    $bitrix24 = new Bitrix();
/*    $auth = $_SESSION['AUTH_ID'];
    $dom = $bitrix24->domain = ($_SESSION['PROTOCOL'] == 0 ? 'http' : 'https') . '://'.$_SESSION['DOMAIN'];
    $bitrix24->auth = $_SESSION['MEMBER_ID'];*/


    $userArray = $bitrix24->B24Method('user.current',
                                        array(
                                            'auth' => $_SESSION['AUTH_ID']
                                        )
                                    );
/*print_r($userArray);
echo '<br>';*/
    if(($userArray['result']['EMAIL'] == '') || ($_SESSION['MEMBER_ID'] == ''))
    {
        $err_arr[]= [
            'USER_ERROR' => 'Вы не являетесь пользователем Битрикс24'
        ];
    }
    else    	
    {   
    	$_SESSION['EMAIL'] = $userArray['result']['EMAIL']; 
    	$db = Database::getInstance();
        //$getToken = $db->fetch_query("SELECT bitName, vtoken, member_id FROM AppUsers WHERE member_id = '".$_SESSION['MEMBER_ID']."'");
        //print_r($_SESSION['EMAIL']);
        $getToken = $db->fetch_query("SELECT bitName, vtoken, member_id FROM AppUsers WHERE bitName = ?", [$_SESSION['EMAIL']]);
        if($getToken[0]['vtoken'] == '' && $getToken[0]['bitName'] == '' && $getToken[0]['member_id'] == '')
        {      	
            $err_arr[]= [
                            'TOKKEN_ERROR' => 'FIRST VISIT'
                        ];//нужно добавить и токен и пользователя
        }
        elseif($getToken[0]['vtoken'] != '')
        {
            $VK = new VK_work;
            $VK->set_user($_SESSION['MEMBER_ID']);
            $VK->set_token($getToken[0]['vtoken']);
            $VK->profile_info();
            $first_name = $VK->first_name;
            $last_name = $VK->last_name;
            $ava_link = $VK->ava_link;
            $profile_id = $VK->profile_id;
            if($first_name != ''){
            	$_SESSION['TOKEN'] = $getToken[0]['vtoken'];
            	$_SESSION['first_name'] = $first_name; 
            	$_SESSION['last_name'] = $last_name;
            	$_SESSION['ava_link'] = $ava_link;
            	$_SESSION['profile_id'] = $profile_id;
                $err_arr[]= [
                    'CORRECT' => 'Все верно'
                ];//загружать основной интерфейс
                $logger->log_save('Пользователь залогинился');
                $msgs = [];
                $i = 0;
                $last_comm = $VK->get_mess_Dialogs_Senders();  
                $msgs_pre = $VK ->users_get_all();
                foreach ($msgs_pre as $pre) {
                    $msgs[$i] = [
                                    'first_name' => $pre['first_name'],
                                    'last_name' => $pre['last_name'],
                                    'id' => $pre['id'],
                                    'comment' => $last_comm[$i]['body'],
                                    'out' => $last_comm[$i]['out'],
                                    'photo_max' => $pre['photo_max']
                                ];
                    $i = $i + 1;
                }
              
                $c=count($msgs);
            }else{
                $err_arr[]= [
                    'TOKKEN_ERROR' => 'WRONG TOKKEN'
                ];
            }
        }elseif(($getToken[0]['vtoken'] == '') && ($getToken[0]['bitName'] != '')){
            $err_arr[]= [
                'TOKKEN_ERROR' => 'WRONG TOKKEN'
            ];//добавить только пользователя
        }
    }

    $_SESSION['TOKKEN_ERROR'] = $err_arr[0]['TOKKEN_ERROR'];


if($err_arr[0]['USER_ERROR'] == ''):	
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">	
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/bitrix24-guide-style.css"/>
    <link rel="stylesheet" href="css/main.css"/>
    <title>Интегратор вконтакте</title>
</head>
<body>
    <div class="logo">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1>Интегратор вконтакте</h1>
                </div>			
            </div>
        </div>
    </div>

<? if($err_arr[0]['TOKKEN_ERROR'] != ''):?>
    <div class="login_body">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="">
                        <label class="bx-label-inp" for="inp_1">Введите токен</label>
                        <input type="hidden" class = 'bitUser' value ='<?=$userArray['result']['EMAIL']?>'>
                        <input type="hidden" class = 'm_id' value ='<?=$_SESSION['MEMBER_ID']?>'>
                        <input type="hidden" class = 'status' value = '<?=$err_arr[0]['TOKKEN_ERROR']?>'>
                        <input type="hidden" class = 'dom' value = '<?=$dom?>'>	
                        <input class="bx-input token-input" id="inp_1" type="password"><br>
                        <a href="https://oauth.vk.com/authorize?client_id=<?=$client_id;?>&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=<?=$scope;?>&response_type=token&v=5.53" target="_blank">Получить токен</a>
                        <span class="bx-button bx-button-accept in_accept">Войти</span>
                        <div class="check_extension">
                            <input type="checkbox" id="check_extension">
                            <label for="check_extension">Я использую расширение Google Chrome</label>
                        </div>
                        <?if ($err_arr[0]['TOKKEN_ERROR'] == 'WRONG TOKKEN'):?>
                            <?$logger->log_save('У пользователя неправильный токен',true);?>
                            <div class="wrong">У вас неправильный токен VK, попробуйте ввести его заново</div>
                        <?endif;?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<? endif;?>
<? if($err_arr[0]['CORRECT'] != ''):?>
	<div class="main-body">
		<div class="container">
			<div class="row">
				<div class="col-md-7 col-sm-6">
                                    <div class="bx-tabs-wrap">
                                        <span class="bx-tab tab-new-mess bx-tab-active">Диалоги(<?=$c?>)</span>
                                        <span class="bx-tab tab-history">Массовое добавление лидов</span>
                                    </div>					
                                    <div class="new-mess">						
                                        <div class="new-mess-box">
                                            <? foreach($msgs as $msg):?>
                                                <div class="new-message-item mess-item">
                                                    <img src="<?=$msg['photo_max']?>" id = "d_ava"alt="ava">
                                                    <b><?=$msg['first_name'].' '.$msg['last_name']?> (id<?=$msg['id']?>)</b>
                                                    <input type="hidden" class ="fullname" value ="<?=$msg['first_name'].' '.$msg['last_name']?>">
                                                    <input type="hidden" class ="first_name" value ="<?=$msg['first_name']?>">
                                                    <input type="hidden" class ="last_name" value ="<?=$msg['last_name']?>">
                                                    <input type="hidden" class ="vk_id" value="<?=$msg['id']?>">
                                                    <input type="hidden" class ="ava_link" value="<?=$msg['photo_max']?>">
                                                    <input type="hidden" class ="comment" value="<?=$msg['comment']?>">
                                                    <p><?php if($msg['out'] == ''){echo '';}else{echo '<b>Вы: </b>';}?><?=$msg['comment']?></p>
                                                    <div class="bx-popup pop">
                                                            <span class="bx-popup-text"><?=$msg['comment']?></span>
                                                            <span class="bx-popup-arrow"></span>
                                                    </div>	
                                                    <a href="" id= "opendialog" onclick = "opendialog($(this)); return false;">Открыть переписку</a>							
                                                </div>
                                            <? endforeach;?>																				
                                        </div>							
                                    </div>	
                                    <div class="tab-history-control" style= "margin-top: 110px;">
                                        <span class="bx-button bx-button-accept add-leads">Добавить ЛИДЫ</span>
                                        <span class="bx-button delete-history">Очистить историю</span>
                                    </div>						
				</div>
				<div class="col-md-5 col-sm-6">
                                    <div class="ava">
                                        <h1><?php echo $first_name.' '.$last_name;?></h1>
                                        <img src="<?=$ava_link?>" alt="avatarka" id = "my_ava"><br>
                                        <input type="hidden" class = 'profile_id' value = '<?=$profile_id?>'>
                                        <input type="hidden" class = 'vtoken' value = '<?=$getToken[0]['vtoken']?>'>
                                        <span class="bx-button bx-button-small bx-button-decline declane">Выйти</span>
                                        <span class="bx-button bx-button-small bx-button-accept reloading" style="width: 149px; height: 24px;">Обновить</span>
                                    </div>
				</div>
			</div>
		</div>
		<div class="bitsend">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<span class="bx-button bx-button-accept accept-leads">Загрузить лиды</span>
					</div>
					<div class="col-md-6">
						<span class="bx-button bx-button-decline destoy-choose">Отменить</span>
					</div>				
				</div>
			</div>
		</div>		
	</div>
<? endif;?>
<!-- progress-bar -->
<div class="progress" style="display: none;">
  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    Идет загрузка лидов, подождите
  </div>
</div>
<!--download area-->
<div class="download" style="display: none;">
    <div class="modal-box"></div>
    <div class="downloadBox">
        <img src="images/load.gif" alt="loading">
    </div>
</div>
<!-- modal -->
<div class="modal">
    <div class="modal-box"></div>
    <div id="confirmBox">
        <h1>Очищение истории</h1>
        <p>Вы уверены, что хотите очистить историю?</p>
        <div id="confirmButtons">
            <span class="bx-button bx-button-small bx-button-accept blue">
                Да
            </span>
            <span class="bx-button bx-button-small bx-button-decline gray">
                Нет
            </span>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="js/common.js"></script>
</body>
</html>
<?php else:
    $logger->log_save($err_arr[0]['USER_ERROR'],true);
endif;?>

