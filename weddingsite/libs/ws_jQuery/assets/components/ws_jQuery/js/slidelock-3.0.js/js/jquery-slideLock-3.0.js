// slideLock JQuery Plugin
/*

	slideLock adds a JQuery UI slider along with 'lock' and 
	'unlock' labels. Be sure to upload the default arrow icon 
	or change the path in the options. 
	
	The purpose of this plugin is to provide an alternative to
	traditional CAPTCHA. The user simply slides the lock open
	and the plugin sets a value for the server to check. 
		
	You still need to check on the server side the value of the 
	inserted hidden field with the name and id of 'inputID'.
	You can set these values in the options and must be integers.
	
	*** REQUIRES >= jQuery 1.5 	   ***
	*** REQUIRES jQuery UI >= 1.8  ***
	
	Version 3.0
		- simplified options
		- better CSS integration
		- WAI-ARIA compliant
	
	Version 2.1
		- Now resets slider controls correctly when an form is submitted
		  via AJAX. 
	
	Version 2.0
		- Fixed a bug that causes the plugin to fail when the ID of
		  the inputs are changed -- thanks to Steve Kruse <steve@wnywebsites.com>
		  
		- Includes revised PHP code to allow for Javascript being disabled
	
*/
(function($) {
		  
	$.fn.slideLock = function(options) {
		
		// set defaults
		var defaults = {
		
			// style these options with css to fit your application
			labelText: "Slide to Unlock:",
			noteText: "Proves you're human",
			lockText: "Locked",
			unlockText: "Unlocked",
			inputID: "sliderInput",
			onCSS: "#333",
			offCSS: "#aaa",
			inputValue: 1,
			submitID: "#submit",
			tabIndex: 0,
			ariavaluemin: 0,
			ariavaluemax: 1,
			ariavaluenow: 0,
			ariaLocked: 'Form is locked',
			ariaUnlocked: 'Form is unlocked',
			debug: true
									 
		};
		
		var opts = $.extend(defaults, options);
		
		// insert ui function
		function insertLocker() {
			
			var uiHTML = '<p class="slider"><label for="slider" id="sliderLabel">' + opts.labelText + '<br/><span class="quiet">' + opts.noteText + '</span></label>';
			uiHTML += '<div id="slider" title="Slide to unlock this form" tabindex="' + opts.tabIndex + '"></div></p>';
			uiHTML += '<p class="quiet"><span id="locked">' + opts.lockText + '</span><img src="' + opts.iconURL + '" alt="Slide to the right" class="ui-icon ui-icon-arrowthick-2-e-w" /><span id="unlocked">' + opts.unlockText + '</span></p>';
			uiHTML += '<input type="hidden" name="' + opts.inputID + '" value="" id="' + opts.inputID + '" />';
			
			return uiHTML;
			
		}
				
		return this.each(function() {
			
			var obj = $(this);
			
			// insert ui elements before the form's submit button
			var submitButton = $(opts.submitID);	
			submitButton.before(insertLocker());
			
			// disable submit button
			$(submitButton).css('margin-top', '15px').attr('disabled', 'disabled');
			
			// slider functionality
			$("#slider", obj).slider({
				
				animate: true,
				value: 0,
				min: 0,
				max: opts.inputValue,
				step: opts.inputValue,
				stop: function(event, ui) {
					
					// set value of usercheck
					$("#" + opts.inputID, obj).val(ui.value);
					var salt = '';
					
					// ajax request to verify input value against salt
					$.post(
						'include/check_salt.php', 
						{ inputvalue: $("#" + opts.inputID, obj).val() }, 
						function(data) {
							salt = data;
							if(opts.debug) console.log(salt);
							
							// enable submit button
							// if($("#" + opts.inputID, obj).val() == opts.checkValue) {
							if(salt === "true") {
								
								// set value of aria-valuenow & aria-valuetext
								$(".ui-slider-handle").attr({
									'aria-valuenow': ui.value,
									'aria-valuetext': opts.ariaUnlocked
								});
								
								// change color of labels
								$("#locked", obj).css({'color': opts.offCSS, 'font-weight': 'normal'});
								$("#unlocked", obj).css({'color': opts.onCSS, 'font-weight': 'bold'});
								
								// enable
								$(submitButton).attr('disabled', ''); 
							
							}else{
								
								// set value of aria-valuenow & aria-valuetext
								$(".ui-slider-handle").attr({
									'aria-valuenow': ui.value,
									'aria-valuetext': opts.ariaLocked
								});
								
								// change color of labels
								$("#locked", obj).css({'color': opts.onCSS, 'font-weight': 'bold'});
								$("#unlocked", obj).css({'color': opts.offCSS, 'font-weight': 'normal'});
								
								// disable
								$(submitButton).attr('disabled', 'disabled');
							
							}
							
						}
						
					);
						   
				}
				
			});
			
			// set WAI-ARIA attributes on the slider element
			$(".ui-slider").attr({ 
				'role': 'slider',
				'aria-labeledby': 'sliderLabel'
			});
			$(".ui-slider-handle").attr({
				'role': 'button',
				'aria-valuemin': opts.ariavaluemin,
				'aria-valuemax': opts.inputValue,
				'aria-valuenow': opts.ariavaluenow,
				'aria-valuetext': opts.ariaLocked
			});
			
			// reset slider control on submit or reset button click
			$("input:submit, input:reset").click(function() {
										   
				$("#slider").slider("option", "value", 0);						   
										   
			});
								  
		});
		
	};
		  
})(jQuery);