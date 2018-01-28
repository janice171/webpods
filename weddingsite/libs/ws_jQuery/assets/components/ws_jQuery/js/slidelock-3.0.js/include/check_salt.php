<?php

	if(isset($_POST['inputvalue']) && $_POST['inputvalue'] != '') {
		
		if(is_numeric($_POST['inputvalue'])) {
			
			$input = filter_var($_POST['inputvalue'], FILTER_SANITIZE_STRING);
			
			if(($input + 9) == 10) {
				
				print 'true';
				
			}else{
				
				print 'Invalid check value.';
					
			}
			
		}else{
			
			print 'Invalid input value.';
			
		}
		
	}else{
		
		print 'No input value given.';	
		
	}

?>