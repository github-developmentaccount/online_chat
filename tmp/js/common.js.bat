    
function $_GET(key) {

    var s = window.location.search;

    s = s.match(new RegExp(key + '=([^&=]+)'));

    return s ? s[1] : false;

}




var func = function () {
             
            if($_GET('pc') != false) {
                var url = 'inc/p_conv.php';
                var ch_id = $_GET('pc');
                    } else {
                         var ch_id = ($_GET('ch') != false) ? $_GET('ch') : 1;
                         var url = 'inc/getJson.php';
                    }
                    
                       
                       
                        var get_param = ($_GET('ch') != false) ? $_GET('ch') : 1;
                       if(!$.isNumeric(get_param)) 
                        {
                             $('.alert-danger').fadeIn('slow').html('Invalid get params').fadeOut('slow');
                            return false;
                        }
			var result = "";
			$.ajax({
			type: 'POST',
                        data: {channel: get_param},
			url: url,
			success: function (data) {
                            if(data == 0 || data == '') {
                                $('.alert-danger').fadeIn('slow').html('Chosen channel doesn\'t exist').fadeOut('slow');
                                return false;
                            } 
                                $.each($.parseJSON(data), function (key, value) {
                                result += '<div class="comments" id="' +value.id + '"><div class="comment dialog"><i class="fa fa-user">&nbsp;' + value.login + '</i><p>' + value.text + '</p><small><i class="fa fa-calendar-o"></i>&nbsp;' + value.time + '</small></div></div>';


                            });
                        $('#wrapper-mess').empty().append(result);
                    
		}
	});


};

$(document).ready(function () {
    func();
    
    

    
    
    
		setInterval(func, 2000);
                
                
	$('#button-send').click(function () {
		var text = $('.text-area').val();
                
                    if($_GET('pc') != false) {
                        var url = 'inc/p_conv.php';
                        var ch_id = $_GET('pc');
                    } else {
                         var ch_id = ($_GET('ch') != false) ? $_GET('ch') : 1;
                         var url = 'inc/getJson.php';
                    }
                    

            
                     
               
			if(text == "") {
				$('.alert-danger').fadeIn('slow').html('Fill up the textarea').fadeOut('slow');
                                $('.text-area').val('');
				return false;
                        }      
                
                        if(!$.isNumeric(ch_id)) {
                            
                                $('.alert-danger').fadeIn('slow').html('Invalid get params').fadeOut('slow');
                                return false;
                        }
               
                
		$.ajax({
		        type: "POST",
		        data: {message : text,
                               ch_id : ch_id},
		        url: url,
		        success: function (data) {
		                               
		                                if(data == 0 || data == '') 
		                                    {
		                                      $('.alert-danger').fadeIn('slow').html('Process failed').fadeOut('slow');
		                                      return false;
                                                    }
		                                    else {
                                                        
                                                $('.text-area').val(''); 
		                                        $('.alert-success').fadeIn('slow').fadeOut('slow');
		                                        func();
		                                    }

		                                }

		        });
		

	});	

/*
 * 
 * LOGGING 
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


});


