<?php
	session_start();
	if (isset($_GET['email']) && isset($_GET['conflink']))
	{
		$email = $_GET['email'];
		$conflink = $_GET['conflink'];
		try
		{
			$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$request = $con->prepare("SELECT email, conflink, activated FROM users WHERE email = :email AND conflink = :conflink AND activated = '0'");
			$request->execute(array(
				':email' => $email,
				':conflink' => $conflink
			));
		}
		catch(PDOexception $e)
		{
			echo "Couldn't write in database: " . $e->getMessage();
		}
		if ($request->rowCount() > 0)
		{
			try
			{
				$update = $con->prepare("UPDATE users SET activated = '1', conflink = NULL WHERE email = :email AND conflink = :conflink AND activated = '0'");
				$update->execute(array(
					':email' => $email,
					':conflink' => $conflink
				));
				$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$result = $con->query("SELECT username, id FROM users WHERE email = " . "'" . $email . "'");
				$donnees = $result->fetch();
				$_SESSION['LOGGED_ON'] = $donnees['username'];
				$_SESSION['ID'] = $donnees['id'];
			}
			catch(PDOexception $e)
			{
				echo "Couldn't write in database: " . $e->getMessage();
			}
			header( "refresh:3;url=index.php" );
		}
	}
	else
	{
		header('location:index.php');
	}
 ?>
 <html>
 	<head>
 		<link rel="stylesheet" href="styles.css">

 		<meta charset="utf-8">
 		<title></title>
 	</head>
 	<body>
 		<div class="header">
 			<a href="index.php"><button class="title" name="button">CAMAGRU</button><a/>
 			<div class="box1">
 			<?php
 			if (isset($_SESSION['LOGGED_ON']))
 			{
 				echo '<a href="profile.php"><button class="signed" style="padding-left: 0px;type="button" name="profile">' . $_SESSION['LOGGED_ON'] ."</button></a>";
 				echo '<a href="logout.php"><button class="button" type="button" name="Logout">Log out</button></a>';
 			}
 			else
 			{
 				echo '<a href="sign_in.php"><button class="button" type="button" name="Login">Sign in</button></a>';
 				echo '<a href="sign_up.php"><button class="button" type="button" name="Sign up">Sign up</button></a>';
 			}
 			?>
 			</div>
 		</div>
		 <div class="main" style="text-align:center";></br>
        </br>Welcome to Camagru, your account has been activated</br></br></br></br>
        You will be redirected to the <a href="index.php"><type="text" class="" name="button">Homepage<a/> in 5 sec ...
        </div></br>
 		<div class="footer">
 		</div>
 	</body>
 </html>
