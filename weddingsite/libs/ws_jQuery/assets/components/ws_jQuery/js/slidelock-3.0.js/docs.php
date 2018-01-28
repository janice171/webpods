<?php require_once '../db/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>slideLock :: Docs</title>
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
    	<?php
		
			$sql = "SELECT post_content FROM pc_posts WHERE ID = 68";
			$post = $db->query($sql)->fetch();
			
			print $post[0];
		
		?>
    </section>
    <footer>
    	<p>&copy; 2011 <a href="http://parametercontraption.com">Parameter Contraption</a> | Release under a <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a></p>
    </footer>
</body>
</html>