$(document).ready(function() {
    // добавление всех лидов из диалогов
    $('.add-leads').on('click', function(){
        $.ajax({
            type: "POST",
            url: "ajax/addLeads.php",
            data: 
            {
            },
            beforeSend: function(){
                $('.download').show();			
            },            
            success: function(data)
            {
                $('.download').hide();
                alert(data);	
            },
            error: function(){
                alert('Произошла ошибка при обновлении списка лидов, свяжитесь с разработчиками');
            }
        });
    })

    // удаление всех лидов из диалогов
    $('.delete-history').on('click', function(){
    	$('.modal').show();
    });
    $('.blue').click(function(){
        $.ajax({
            type: "POST",
            url: "ajax/delete_history.php",
            data: 
            {
            },
            beforeSend: function(){
                $('.progress').show();			
            },            
            success: function(data)
            {
            	$('.modal').hide();
                alert('Данные успешно очищенны');
                $('.progress').hide();	
            },
            error: function(){
                alert('Произошла ошибка при очистке истории, свяжитесь с разработчиками');
            }
        });
    });
    
    $('.gray').click(function(){
    	$('.modal').hide();
    });

    $(".new-mess h2").click(function(){
        $(".new-mess-box").slideToggle();
    });
    
    $(".old-mess h2").click(function(){
        $(".old-mess-box").slideToggle();
    });

    var arrChoose = [];

    // выйти из профиля
    $('.declane').click(function(){
        var bitUse = "exit";
        $.ajax({
            type: "POST",
            url: "ajax/exit.php",
            data: {bitUse:bitUse},
            success: function(data){
                location.reload();
            },
            error: function(){
                alert('ошибка');
            }
        });			
    });
    
    // вход
    $('.in_accept').click(function(){
        var tokenin = $('.token-input').val();
        $.ajax({
            type: "POST",
            url: "ajax/enter.php",
            data: {tokenin:tokenin},
            success: function(data){
                location.reload();
            },
            error: function(){
            }
        });
    });
    
    //declane 
    $('.destoy-choose').click(function(){
        $('.new-message-item').removeClass('choosen');
        $('.bitsend').hide();
    });

    $('.new-mess-box').on('click', '.mess-item', function(e){
        choose($(this));
    });
    
    // новые сообщения
    $('.new-mess').on('click', '.send_mess', function(e){
        var message = $('.bx-popup-big p').text(),            
            id = $('.id').val();
        $.ajax({
            type: "POST",
            url: "ajax/message_send.php",
            data: {
                id:id,
                message:message
            },
            success: function(data){	
                $('.bx-popup-big p').text('');
                $('.one-dialog').find('.new-message-item').first().before(data);
            },
            error: function(){
                    alert('ошибка');
            }
        });
    });
    
    $('.new-mess').on('click', '.cancel_dialog', function(e){
        $('.one-dialog').hide();
        $('.new-mess-box').show();
    });	
    $('.new-mess').on('click', '.reload_dialog', function(e){
        var d_ava = $('.other_avatar').val(),
            id = $('.other_id').val(),
            fullname =$('.fullname').val(),
            reload = 'reload';
        $.ajax({
            type: "POST",
            url: "ajax/onedialog.php",
            data: {id:id,
                    fullname:fullname,
                    d_ava:d_ava,
                    reload:reload
            },
            beforeSend: function(){
                $('body').css('overflow-y','hidden');
                $('.download').show();          
            },             
            success: function(data){
                $('.one-dialog').html(data);
                $('.download').hide();
                $('body').css('overflow-y','scroll');
                // alert(data);
            },
            error: function(){
                alert('ошибка');
                 $('.download').hide();
            }
        });        
    });     
    var choose = function(form_item){
        jsonArr = [];
        $(form_item).toggleClass('choosen');
        var chooseAr = $('.choosen');
        for (var i=0; i<chooseAr.length; i++){
            jsonArr.push({'Fullname'  : chooseAr.eq(i).find($('.fullname')).val(),'first_name': chooseAr.eq(i).find($('.first_name')).val(), 'last_name': chooseAr.eq(i).find($('.last_name')).val(), 'id' : chooseAr.eq(i).find($('.vk_id')).val(), 'comment' : chooseAr.eq(i).find($('.comment')).val(), 'photo_max' : chooseAr.eq(i).find($('img')).attr('src')});
        }
        if ($('.mess-item').hasClass("choosen")){
            $('.bitsend').show();
        }else{
            $('.bitsend').hide();
        }
    }
    var accept_leads = function(){
    }
});

$('.bx-tab').click(function(){
    if($(this).hasClass('bx-tab-active')){
    }else{
        $('.bx-tab').removeClass('bx-tab-active');
        $(this).addClass('bx-tab-active');
    }

    if($('.tab-new-mess').hasClass('bx-tab-active')){
        $('.new-mess').show();
    }else{
        $('.new-mess').hide();
        $('.new-message-item').removeClass('choosen');
        $('.bitsend').hide();
    }

    if($('.tab-history').hasClass('bx-tab-active')){
        $('.tab-history-control').show();
    }else{
        $('.tab-history-control').hide();		
    }
});

function toArray(obj){ return [].slice.call(obj)}

// добавить выюранные лиды
$('.accept-leads').click(function(){
    jsonArr = (JSON.stringify(jsonArr, ["Fullname", "first_name", "last_name", "id", "comment", "photo_max"]) );
    $.ajax({
        url: "ajax/choosen-lead.php",
        type: 'POST',
        data: 'jsonData=' + jsonArr,
        beforeSend: function(){
            $('.bitsend').hide();
            $('.progress').show();
            $('.new-message-item').removeClass('choosen');			
        },
        success: function(res){
            $('.progress').hide();
            alert(res);
        }
    });	
});

function opendialog(dat){
    var id = dat.parent().find('.vk_id').val(),
        fullname = dat.parent().find('.fullname').val(),
        d_ava = dat.parent().find("#d_ava").attr('src');
    $.ajax({
        type: "POST",
        url: "ajax/onedialog.php",
        data: {id:id,
            fullname:fullname,
            d_ava:d_ava
        },
        success: function(data){
            $('.new-mess-box').last().after(data);
            $('.new-mess-box').hide();
            // $('.new-mess').html(data);
            $('.bitsend').css('display','none');
            $('.mess-item').removeClass("choosen");
            // alert(data);
        },
        error: function(){
            alert('ошибка');
        }
    });
}

$('.reloading').click(function(){
    location.reload();
});
$('.cancel_dialog').click(function(){
    location.reload();
});

//pop
$('.new-mess').on('mouseover', '.new-message-item p', function(e){
    showfull($(this));
}); 
$('.new-mess').on('mouseout', '.new-message-item p', function(e){
    hidefull($(this));
});	

function showfull(dat){
    dat.next('.pop').show();
};

function hidefull(dat){
    dat.next('.pop').hide();
};