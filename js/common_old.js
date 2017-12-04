$( document ).ready(function() {


    // hide
    $(".new-mess h2").click(function(){
  		$(".new-mess-box").slideToggle();
	});
	$(".old-mess h2").click(function(){
  		$(".old-mess-box").slideToggle();
	});
	//

	//pop
	var linc2 = $('.pop'),
    timeoutId;
	$('.new-message-item p').hover(function(){
	    clearTimeout(timeoutId);
	    $(this).next().show();
	}, function(){
	    timeoutId = setTimeout($.proxy(linc2,'hide'), 100)
	});
	$('.old-message-item p').hover(function(){
	    clearTimeout(timeoutId);
	    $(this).next().show();
	}, function(){
	    timeoutId = setTimeout($.proxy(linc2,'hide'), 100)
	});	
	linc2.mouseenter(function(){
	    clearTimeout(timeoutId); 
	}).mouseleave(function(){
	    linc2.hide();
	}); 	
	///

	//choose//
	var arrChoose = [];

	// $('.mess-item').click(function(){
	// 	$(this).toggleClass('choosen');
	// 	if ($(".mess-item").hasClass("choosen")){
	// 		$('.bitsend').show();
	// 	}else{
	// 		$('.bitsend').hide();
	// 	}
	
	// });
	//

	//login//
	// $('.in_accept').click(function(){
	// 	var token = $(".token-input").val(),
	// 		status = $('.status').val();
	//   	$.ajax({
	// 	    type: "POST",
	// 	    url: "ajax/tokenCheck.php",

	// 	    data: {token:token},
	// 	     success: function(data){
	// 	       alert('yes');
	// 	      },
	// 	      error: function(){
	// 	        alert('ошибка');
	// 	      }

	//     });			
	// });	
	// more mess
	$('.load-more').on('click', function(){
            var formore = $('.for-more').val(),
                bitUse = $('.bitUse').val();
                $('.for-more').val(Number(formore) + 6);
            $.ajax({
                type: "POST",
                url: "ajax/messload.php",

                data: {formore:formore,
                    bitUse:bitUse},
                success: function(data){
                   $('.new-message-item').last().after(data);
                },
                error: function(){
                    alert('ошибка');
                }
            });			
	});
	$('.declane').click(function(){
		var bitUse = 'exit';
	  	$.ajax({
		    type: "POST",
		    url: "ajax/exit.php",

		    data: {bitUse:bitUse},
		    success: function(data){
		    	alert(data);
		    },
		    error: function(){
		        alert('ошибка');
		    }

	    });			
	});
	// enter
	$('.in_accept').click(function(){
		var tokenin = $('.token-input').val(),
			status = $('.status').val(),
			m_id = $('.m_id').val(),
			dom = $('.dom').val();
		$.ajax({
			type: "POST",
			url: "ajax/enter.php",

			data: {status:status,
				   tokenin:tokenin,
				   m_id:m_id,
				   dom:dom},
			success: function(data){
				location.reload();
				// alert(data);
			},
			error: function(){
				alert('ошибка');
			}
		});

	});
	//declane 
	$('.destoy-choose').click(function(){
		$('.new-message-item').removeClass('choosen');
		$('.bitsend').hide();
	});


	$('.new-mess-box').on('click', '.mess-item', function(e){
		//e.preventDefault('.mess-item');
		// console.log($(this));
		choose($(this));
	});
	$('.new-mess').on('click', '.send_mess', function(e){
		var vtoken = $('.vtok').val(),
			message = $('.bx-popup-big p').text(),
			id = $('.id').val(),
			my_ava = $('#my_ava').attr('src'),
			fullname = $('.fullname').val();
		$.ajax({
			type: "POST",
			url: "ajax/message_send.php",

			data: {id:id,
				   message:message,
				   vtoken:vtoken,
				   my_ava:my_ava,
				   fullname:fullname},
			success: function(data){	
				$('.new-message-item').first().before(data);
			},
			error: function(){
				alert('ошибка');
			}
		});
	});
	$('.new-mess').on('click', '.cancel_dialog', function(e){
		$.ajax({
			type: "POST",
			url: "index.php",

			data: {},
			success: function(data){	
				location.reload();
			},
			error: function(){
				alert('ошибка');
			}
		});		
	});	
	


	var choose = function(form_item){
		// arrForleads = [];
		jsonArr = [];
		$(form_item).toggleClass('choosen');
		var chooseAr = $('.choosen')
		for (var i=0; i<chooseAr.length; i++){
			// arrForleads[i] = {};

// {"menu": {
//   "id": "file",
//   "value": "File",
//   "popup": {
//     "menuitem": [
//       {"value": "New", "onclick": "CreateNewDoc()"},
//       {"value": "Open", "onclick": "OpenDoc()"},
//       {"value": "Close", "onclick": "CloseDoc()"}
//     ]
//   }
// }}
			jsonArr.push({'Fullname'  : chooseAr.eq(i).find($('.fullname')).val(), 'vk_id' : chooseAr.eq(i).find($('.vk_id')).val(), 'comment' : chooseAr.eq(i).find($('.comment')).val()});


			// {
			// 			  Fullname: chooseAr.eq(i).find($('.fullname')).val(),
			// 			  vk_id: chooseAr.eq(i).find($('.vk_id')).val()					 
			// 		  	};



			// '{'+'Fullname'+ ':' + chooseAr.eq(i).find($('.fullname')).val() + '}, {'+'vk_id'+ ':' + chooseAr.eq(i).find($('.vk_id')).val() + '}'


			// arrForleads[i]['Fullname'] = '{'+'Fullname'+ ':' + chooseAr.eq(i).find($('.Fullname')).val() + '}';
			// arrForleads[i]['vk_id'] = '{'+'Fullname'+ ':' + chooseAr.eq(i).find($('.vk_id')).val() + '}';
/*			arrForleads = {
							'Name': [chooseAr.eq(i).find('p').text()]
						  };*/
			// console.log('chosar');
			// console.log(chooseAr.eq(i).find('p').text());
		}

		
		// console.log($('.choosen .vk_id').val());
		// console.log($('.choosen .fullname').val());
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
	
	// arrForleads.toArray();
	// alert(arrForleads);
	// arrForleads = JSON.stringify(arrForleads);
	// arrForleads = JSON.parse(arrForleads);
$('.accept-leads').click(function(){
	// alert(arrForleads.Fullname);

	jsonArr = (JSON.stringify(jsonArr, ["Fullname", "vk_id", "comment"]) );
	$.ajax({
		url: "ajax/leads.php",
		type: 'POST',
		data: 'jsonData=' + jsonArr,
		success: function(res){
			alert(res);
		}
	});	
	// console.log(jsonArr);
	// javascriptObj



});



function opendialog(dat){
	var id = dat.parent().find('.vk_id').val(),
		fullname = dat.parent().find('.fullname').val(),
		vtoken = $('.vtoken').val(),
		m_id = $('.m_id').val(),
		profile_id = $('.profile_id').val(),
		my_ava = $("#my_ava").attr('src'),
		d_ava = dat.parent().find("#d_ava").attr('src'),
		my_name = $('.ava h1').text();
	$.ajax({
		type: "POST",
		url: "ajax/onedialog.php",

		data: {id:id,
			   profile_id:profile_id,
			   m_id:m_id,
			   vtoken:vtoken,
			   fullname:fullname,
			   my_ava:my_ava,
			   d_ava:d_ava,
			   my_name:my_name},
		success: function(data){
			$('.new-mess').html(data);
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


$('.add-leads').on('click', function(){
    $.ajax({
        type: "POST",
        url: "ajax/addLeads.php",

        data: 
        {
        },
        success: function(data)
        {
            console.log(data)
        },
        error: function(){
            alert('ошибка');
        }
    });
    
})
