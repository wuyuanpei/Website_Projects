<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
    <link rel="stylesheet" href="homepage.css"/>
</head>
<body>
	<?php
	printf("<form class='sort' action= 'homepage.php' method='GET'>
	<input type='submit' value='Newest First' name='sorting'/>  
	<input type ='submit' value='Oldest First' name='sorting'/> 
	<input type ='submit' value='Popular' name='sorting'/> 
	<input type ='submit' value='Controversial' name = 'sorting'/> 
	</form>
	<form class='search' action = 'homepage.php' method='GET'/>
	<input type='text' value = 'Search for stories' name = 'search'/>
	<input type='submit' value = 'Search'/>
	</form>");
	?>
    <div id="title">
        <p><b>Welcome to Piles of Files </b></p>
    </div>
    <div id="caption">
        <p>!!! The News Sharing Platform !!!</p>   
    </div>
<?php
require 'database.php';
session_start();
if(!isset($_SESSION['username'])){
	echo "<form class='login_button' action='login.html'>
	<input type='submit'  value='login'/> 
	</form>
	<form class='signup_button' action = 'signup.html'>
	<input type='submit' value='signup'/> 
	</form>";
}else{
	printf("<label class='username'>Hello, %s!</label>
	<form class='upload_button' action='uploadP.php'>
	<input type='submit'  value='post story'/> 
	</form>
	<form class='logout_button' action = 'logout.php'>
	<input type='submit' value='logout'/>
	</form>
	<form class='edit_button' action = 'edit.php'>
	<input type='submit' value='edit/delete my posts'/> 
	</form>", htmlspecialchars($_SESSION['username']));
}
function stmt($stmt) {
     if(!$stmt){
	 	printf("Query Prep Failed: %s\n", $mysqli->error);
	 exit;
	 }
	
	$stmt->execute();
	
	$result = $stmt->get_result();
	
	echo "<ul>\n";
	while($row = $result->fetch_assoc()){
		printf ("<li><a href='story.php?id=%s'><div class='links'>%s</div></a></li>\n",htmlspecialchars( $row["id"] ),htmlspecialchars( $row["title"] ));
		
	}
	echo "</ul>\n";
	$stmt->close();
}

if(!isset($_GET['sorting'])&&!isset($_GET['search'])){
	$stmt = $mysqli->prepare("select id,title from stories");
	stmt($stmt);
}
if(isset($_GET['sorting'])){
	$sort_input = $_GET['sorting'];
switch ($sort_input)
    {
   case "Newest First":
		$stmt = $mysqli->prepare("select id,title from stories order by date desc");
		stmt($stmt);
		break;
   case "Oldest First":
		$stmt = $mysqli->prepare("select id,title from stories order by date asc");
		stmt($stmt);
		break;
   case "Popular":
		$stmt = $mysqli->prepare("select id,title from stories order by likes desc");
		stmt($stmt);
		break;
   case "Controversial":
		$stmt = $mysqli->prepare("select id,title from stories order by dislikes desc");
		stmt($stmt);
		break;
	default:
		$stmt = $mysqli->prepare("select id,title from stories");
		stmt($stmt);
	}
}
if(isset($_GET['search'])){
	$search_input = $mysqli->real_escape_string($_GET['search']);
	$stmt = $mysqli->prepare("select id,title from stories where title like '%".$search_input."%' ");
	stmt($stmt);
}
?>
	
</body>
</html>


