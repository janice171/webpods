jQuery(document).ready(function() {
			//superfish menu
			if ($("nav .sf-menu").length) {
			jQuery('nav .sf-menu').superfish({			
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation             
            autoArrows:  false,                           // disable generation of arrow mark-up 
            dropShadows: false                            // disable drop shadows      
			});
			}
			
			
			//jCycle slider
			if ($('.slideshow').length) {
			jQuery('.slideshow').after('<div id="nav">')
			jQuery('.slideshow').cycle({
				fx: 'fade',
				timeout: 6000,
				pager: '#nav',
				speed: 1000,
				pagerEvent: 'click',
    			pauseOnPagerHover: true,
				cleartypeNoBg: true,
				pause: 1 });
			jQuery('.slideshow').css("display", "block");
			jQuery('#nav').css("display", "block");
			}
			
});
	  


jQuery(function () {
			jQuery('.loader  img').hide();
			});
			var i = 0;
			var int=0;
jQuery(window).bind("load", function() {
				var int = setInterval("imgLoader(i)",300);
			});
			
function imgLoader() {
				var images = jQuery('.loader  img').length;
				if (i >= images) {
					clearInterval(int);
				//	jQuery('.loader  img').css('background', 'none');
			}
			jQuery('.loader  img:hidden').eq(0).fadeIn(300)
			i++;
		}


jQuery('h4.tog').click(function () {
	jQuery(this).next('div.togcont').toggle(300);
	});
	
	jQuery('h4.tog').toggle(
	function () { jQuery(this).addClass('bounce'); },
	function () { jQuery(this).removeClass('bounce');; }
	);
	



jQuery('.txtlist li').hover(function(){
  jQuery(this).stop().animate({ paddingLeft: "10px" }, 300);
}, function(){
    jQuery(this).stop().animate({ paddingLeft: "5px" }, 150); 
});

function ajaxContact(theForm) {
		var $ = jQuery;
	
        $('#loader').fadeIn();

        var formData = $(theForm).serialize(),
			note = $('#Note');
	
        $.ajax({
            type: "POST",
            url: "send.php",
            data: formData,
            success: function(response) {
				if ( note.height() ) {			
					note.fadeIn('fast', function() { $(this).hide(); });
				} else {
					note.hide();
				}

				$('#LoadingGraphic').fadeOut('fast', function() {
					
					if (response === 'success') {
						$('.field').animate({opacity: 0},'fast');
						$('.button_contact').animate({opacity: 0},'fast');
					}

					
					result = '';
					c = '';
					if (response === 'success') { 
						result = 'Your message has been sent. Thank you!';
						c = 'success';
					}

					note.removeClass('success').removeClass('error').text('');
					var i = setInterval(function() {
						if ( !note.is(':visible') ) {
							note.html(result).addClass(c).slideDown('fast');
							clearInterval(i);
						}
					}, 40);    
				});
            }
        });

        return false;
    }
	if ($("#contactform").length) {
	jQuery("#contactform").validate({
			submitHandler: function(form) {				
				ajaxContact(form);
				return false;
			},
			 messages: {
    		 formname: "Please specify your name.",
			 formcomments: "Please enter your message.",
    		 formemail: {
      			 required: "We need your email address to contact you.",
      			 email: "Your email address must be in the format of name@domain.com"
    		 }
  		 }
		});
		 }