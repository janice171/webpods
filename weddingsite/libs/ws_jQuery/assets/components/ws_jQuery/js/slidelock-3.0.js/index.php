<?php 

	// process form submission
	if(isset($_POST['submit'])) {

		// check to see if JavaScript is disabled
		if(isset($_POST['js_check']) && $_POST['js_check'] == 0) {

			// sanatize and validate user input
			

			// process the form as usual...
			

		}else{ // we have js, check for slideLock validation

			// alter the POST array key and the return value based on your settings
			if(isset($_POST['sliderInput']) && $_POST['sliderInput'] == 10) {

				// sanatize and validate user input
				

				// process the form as usual...
				

			}else{

				// validation has failed, inform user
				

			}

		}

	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>slideLock</title>
	<meta name="description" content="">
	<meta name="author" content="Sean Mullin, http://parametercontraption.com">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon-iphone.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-ipad.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-iphone4.png" />
	<link rel="stylesheet" media="screen" href="css/screen.css">
    <script src="js/modernizr-1.7.min.js"></script>
</head>
<body>
	<header>
    	<hgroup>
        	<h1>slideLock</h1>
            <h2>an easy &amp; accessible alternative to CAPTCHA</h2>
        </hgroup>
        <img src="images/logo.png" id="logo" />
        <nav>
        	<ul>
            	<li id="download"><a href="#">Download</a></li>
                <li><a href="demo.php">Demo</a></li>
                <li><a href="docs.php">Docs</a></li>
            </ul>
        </nav>
    </header>
    <section id="content">
    	<p>This plugin grew from need to have an alternative to the traditional <a href="http://en.wikipedia.org/wiki/CAPTCHA" target="_blank">CAPTCHA</a> solution found on most web forms. I didn't want to have the ugly and confusing scrambled letters at bottom of each form, but still needed a way to prevent the site being inundated with spam—So slideLock was born.</p>
        <p>View the <a title="slideLock Demo" href="demo.php">demo</a> to get an idea of what slideLock can do. <a title="Download plugin source" href="http://plugins.jquery.com/project/slideLock" target="_blank">Download</a> the source package including a sample PHP setup to see how to handle it on the server side.</p>
    </section>
    <footer>
    	<p>&copy; 2011 <a href="http://parametercontraption.com">Parameter Contraption</a> | Release under a <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a></p>
    </footer>
</body>
</html>