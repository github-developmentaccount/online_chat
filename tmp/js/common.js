/*
     * 
     * @param {type} key
     * @returns {$_GET.s|Boolean}
     * Getting get params
     * 
     */
function $_GET(key) {

    var s = window.location.search;
    s = s.match(new RegExp(key + '=([^&=]+)'));

    return s ? s[1] : false;

}



     /*
      * 
      * @type @arr;s|Boolean|@arr;s|Boolean|Number
      * 
      *  ASSIGN VARIABLES OF URL, INSERT, GET_PARAMS
      */
  var identifier = $('input[type=hidden]').val();
  var get_param;
  var url;
  var ins;
    if($_GET('ch') != false ) {
        url = 'inc/getJson.php';
        get_param = $_GET('ch');
        ins = 'inc/insertData.php';
    }
    else if($_GET('pc') != false) {

        url = 'inc/p_conv.php';
        get_param = $_GET('pc');
        ins = 'inc/insertData_conv.php';
        

    } else {
        get_param = 1;
        url = 'inc/getJson.php';
        ins = 'inc/insertData.php';
        
    }
/*
 * 
 * @returns {Boolean}
 * Showing messages
 * 
 */

var func = function () {
             
                      
                    if(!$.isNumeric(get_param)) {
                    $('.alert-danger').fadeIn('slow').html('Enter valid data').fadeOut('slow');
                    return false;
                    }

			var result = "";
			$.ajax({
			type: 'POST',
                        data: {channel: get_param},
			url: url,
			success: function (data) {
                            
                            switch(data) {
                                case 0:
                                    
                                    $('.alert-danger').fadeIn('slow').html('Chosen channel doesn\'t exist or your permission denied by owner privacy').fadeOut('slow');
                                    return false;
                                    
                                case '2':
                                    
                                    $('.alert-success').fadeIn('slow').html('Channel is empty, type your message').fadeOut('slow');
                                    return false;
                                
                                default:
                                    if(identifier != 'admin') {
                                        $.each($.parseJSON(data), function (key, value) {
                                        result += '<div class="comments" id="' +value.uid + '"><div class="comment dialog"><i class="fa fa-user">&nbsp;' + value.login + '</i><p>' + value.text + '</p><small><i class="fa fa-calendar-o"></i>&nbsp;' + value.time + '</small></div></div>';
                                     });
                                    }
                                    else if(identifier == 'admin' && $_GET('ch') != false){
                                        
                                        $.each($.parseJSON(data), function (key, value) {
                                            result += '<div class="comments" id="' +value.uid + '"><div class="comment dialog"><i class="fa fa-user">&nbsp;' + value.login + '</i><p>' + value.text + '</p><small><i class="fa fa-calendar-o"></i>&nbsp;' + value.time + '</small>'+ '<a class="delete mess" href="?delete='+ value.m_id +'"><i class="fa fa-times"></i></a></div></div>';
                                        });
                                    }
                                    else {
                                        
                                        $.each($.parseJSON(data), function (key, value) {
                                            result += '<div class="comments" id="' +value.uid + '"><div class="comment dialog"><i class="fa fa-user">&nbsp;' + value.login + '</i><p>' + value.text + '</p><small><i class="fa fa-calendar-o"></i>&nbsp;' + value.time + '</small></div></div>';
                                                });

                                                
                                    }
                            }
                              
                        $('#wrapper-mess').empty().append(result);
                    
		}
	});


};

$(document).ready(function () {
    func();

		setInterval(func, 2000);
                
              /*
               * Sending message
               * 
               */  
	$('#button-send').click(function () {
		var text = $('.text-area').val();
                
			if(text == "") {
				$('.alert-danger').fadeIn('slow').html('Fill up the textarea').fadeOut('slow');
                                $('.text-area').val('');
				return false;
                        }      
                
                        if(!$.isNumeric(get_param)) {
                            
                                $('.alert-danger').fadeIn('slow').html('Invalid get params').fadeOut('slow');
                                return false;
                        }
               
                
		$.ajax({
		        type: "POST",
		        data: {message : text,
                               ch_id : get_param},
                        url: ins,
                        success: function (data) {
		                          
		                                if(data == 0 || data == '') 
		                                    {
                                                     alert(data);
		                                      $('.alert-danger').fadeIn('slow').html('Process failed').fadeOut('slow');
		                                      return false;
                                                    }
		                                    else {
                                                        
                                                $('.text-area').val(''); 
		                                        $('.alert-success').fadeIn('slow').html('Message sent').fadeOut('slow');
		                                        func();
		                                    }

		                                }

		        });
		

	});	

/*
 * 
 * Authorization 
 * 
 * 
 */

$('#sign-button').click(function (){

			
			var login = $('#inputEmail').val();
			var password = $('#inputPassword').val();
			if(login === "" || password === "") {
				$('.alert-error').html('Missing data: login & password').fadeIn(300);
				return false;
			} else {
				$.ajax({
					type: "POST",
					data: {login: login,
                                               password: password
						},
					url: "inc/login.php",
					success: function (data) {
                                            
								if(data == 0 || data === '') {
									$(".alert-error").html('Not Matched!').fadeIn(300).fadeOut(2000);
									return false;
								} else  {
									$(".alert-success").fadeIn('slow').html('Message sent').fadeOut('slow');
									
									
									setTimeout('window.location.reload()', 2000);
									
								}

					}
				});
			}
	});

/*
 * ASSIGNING ACTIVE CLASS
 * 
 */

jQuery('ul#route li').each(function(){
    if(window.location.href.indexOf(jQuery(this).find('a:first').attr('href'))>-1)
    {
        jQuery(this).addClass('active').siblings().removeClass('active');
    }
});


/*
 * creating a private room
 */

$('#p_room').click(function (){
        var select = $('.hide option:selected').val();
        
        if(!$.isNumeric(select)) {
            
            $(".alert-danger").html('Choose a user!').fadeIn(300).fadeOut(2000);
            return false;
        }
        
        $.ajax({
                type: "POST",
                data: {user_two: select},
                url: "inc/cr_room.php",
                success: function (data) {
                    
                    if(data == 0) {
                        $(".alert-error").fadeIn(300).html('Permission denied!').fadeOut(2000);
                    }
                    else {
                        $(".alert-success").fadeIn(300).html('Private room has been created').fadeOut(2000);
                        setTimeout('window.location.reload()', 1000);
                        
                    }
                    
                }
            });
        
        
        
});

/*
 * ADMIN PRIVILEGES
 * 
 */

$('.list').click(function (){
    var x =  $(this).attr('id');
    if(confirm('Are you sure?')) {
     if($.isNumeric(x)) {
        $.ajax({
            type: 'POST',
            data: {ch_id: x},
            url: 'inc/adm_rm_ch.php',
            success: function (data)  {
                
                if(data == '' || data == 0){
                    $('.alert-danger').html('Permission denied').fadeIn('slow').fadeOut('slow');
                } else {
                    $('.alert-success').html('Channel removed').fadeIn('slow').fadeOut('slow');
                    setTimeout('location.reload()', 1000);
                }
            }
        });
    }    
        
        
    } 
   
  
});

/*
 * creating a channel
 * 
 */

$('#ch_btn').click(function (){
    var title = $(this).prev().val();
    
    if(title != ''){
            $.ajax({
                type: 'POST',
                data: { title: title},
                url: 'inc/adm_cr_ch.php',
                success: function (data) {
                  
                    if(data == 0 || data == ''){
                        $('.alert-danger').html('Permission denied').fadeIn('slow').fadeOut('slow');
                        return false;
                    }
                    $('.alert-success').html('Channel has been created').fadeIn('slow').fadeOut('slow');
                    $('#ch_name').val('');
                    setTimeout('location.reload()', 1000);
                }
            });
    }else {
        $('.alert-danger').html('Title must contain min 6 characters').fadeIn('slow').fadeOut('slow');
    }
});

 /*
  * REMOVING A MESSAGE
  * 
  */

$(function () {
    if($_GET('delete') != false && $.isNumeric($_GET('delete'))) {
        var param = $_GET('delete');
        $.ajax({
            type: 'POST',
            data: {m_id: param},
            url: 'inc/adm_rm_ms.php',
            success: function (data) {
               
                if(data == 0){
                    $('.alert-danger').html("Error occured").fadeIn('slow').fadeOut('slow');
                    return false;
                }
                $('.alert-success').html('Message has been deleted').fadeIn('slow').fadeOut('slow');
               //window.location.reload('index.php?ch=1');
                func();
                
            }
        });
    }
});



});