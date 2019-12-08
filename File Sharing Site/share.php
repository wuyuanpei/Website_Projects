<!DOCTYPE html>
<!--the page of sharing file-->
<html lang='en'>    
    <head>
        <meta charset='utf-8'/>
        <title>
            Share
        </title>
        <style>
			body{font-size:200%;background-color:#4FFFF3}
			p{color:#FF0000}
        </style>
    </head>
	<body>
		<p>
			<?php
                printf("Share %s To...",htmlentities($_GET['file']));
            ?>
		</p>
		<form action="send.php" method="GET">
			<label>
				send to:
				<input type="text" name="receivername" style="width:150px; height:30px; font-size:25px"/>
                <?php
                printf("<input type='hidden' name='filename' value='%s'>",htmlentities($_GET['file']));
                ?>
			</label><br/>
			<input type="submit" value="share"/>
		</form>
	</body>
</html>
