<html>
 <head>
 	<title>SQL injection</title>
 	<style>
 		body{
 		}
 		.user {
 			background-color: yellow;
 		}
 	</style>
 </head>
 
 <body>
 	<h1>PDO vulnerable a SQL injection</h1>
 
 	<?php
 		// sql injection possible:
 		// coses'); drop table test;'select 
		if( isset($_POST["user"])) {

			$dbhost = $_ENV["DB_HOST"];
			$dbname = $_ENV["DB_NAME"];
			$dbuser = $_ENV["DB_USER"];
			$dbpass = $_ENV["DB_PASSWORD"];

			# Connectem a MySQL (host,usuari,contrassenya)
			$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
	 
			$username = $_POST["user"];
			$pass = $_POST["password"];
			# (2.1) creem el string de la consulta (query)
			$qstr = "INSERT INTO users (name, password, role) VALUES (?, SHA2(?,512), 'user');;";
			$consulta = $pdo->prepare($qstr);
            $consulta->bindParam(1, $username, PDO::PARAM_STR);
            $consulta->bindParam(2, $pass, PDO::PARAM_STR);
            
			# mostrem la SQL query per veure el què s'executarà (a mode debug)
			echo "<br>$qstr<br>";

			# Enviem la query al SGBD per obtenir el resultat
			$consulta->execute();
	 
			# Gestió d'errors
			if( $consulta->errorInfo()[1] ) {
				echo "<p>ERROR: ".$consulta->errorInfo()[2]."</p>\n";
				die;
			} else {
				echo "<div class='user'>Usuari $username creat correctament.</div>";
			}
		}
 	?>
 	
 	<fieldset>
 	<legend>Login form</legend>
  	<form method="post">
		User: <input type="text" name="user" /><br>
		Pass: <input type="text" name="password" /><br>
		<input type="submit" value="Registre" /><br>
 	</form>
  	</fieldset>
	
 </body>
 
 </html>
