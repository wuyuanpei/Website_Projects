<!DOCTYPE html>
<!--the homepage of user-->
<html lang='en'>    
    <head>
        <title>
            Homepage
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        
        <style>
			body{font-size:140%; background-color:#c0c0c0}
			p{font-size:200%;color:#black}
            form{display:inline}
        </style>
    </head>
    <body>
        <p>
            Hello,
            <?php
                session_start();
                if((String)@$_SESSION['username']==''){
                    echo " username illegal";
                    exit;
                }
                echo (String)@$_SESSION['username'].'!';
            ?>
        </p>
        
        <!--from class wiki: upload file-->
        <form enctype="multipart/form-data" action="upload.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
        <label for="uploadfile_input">Upload:<br/></label> <input name="uploadedfile" type="file" id="uploadfile_input" />
        <input type="submit" value="submit" />
        </form>
    <br/>
    <!--display file list-->
    File List:<br/>
    <?php
        $path = '/srv/Module2-Group/'.(String)@$_SESSION['username'].'/*';
        $files = glob($path);
        for($i = 0; $i < count($files); $i++){
            $basename = basename($files[$i]);
            $num = $i+1;
            echo "<form action='download.php' method='GET'>
            <label>$num. </label>
            <input type='text' size='40' readonly='readonly' name='file' value='$basename'/>
            <input type='submit' value='view/download'/>
            </form>
            <form action='delete.php' method='GET'>
            <input type='hidden' name='file' value='$basename'/>
            <input type='submit' value='delete'/>
            </form>
            <form action='share.php' method='GET'>
            <input type='hidden' name='file' value='$basename'/>
            <input type='submit' value='share'/>
            </form><br>";
        }
    ?>
    <br/>
    <form action='logout.php' method='GET'><input type='submit' value='log out'/></form>
    </body>
</html>