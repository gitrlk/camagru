
<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
		try
		{
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $conn->prepare("SELECT * FROM users WHERE email = :newmail");
			$update->bindParam(':newmail', $_POST['newmail']);
			$update->execute();
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		if ($update->rowCount() == 0 && $_POST['newmail'] && filter_var($email = $_POST['newmail'], FILTER_VALIDATE_EMAIL))
		{
		try
		{
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $conn->prepare("UPDATE users SET email = :newmail WHERE username = :username");
			$update->execute(array(
				':newmail' => $_POST['newmail'],
				':username' => $_SESSION['LOGGED_ON']
			));
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		header('location:user.php');
		}
		else
		{
			echo "Please update email in settings";
			header( "refresh:2;url=user.php" );
		}
 ?>

 <!DOCTYPE html>
 <html>
 	<head>
 		<meta charset="utf-8">
 		<link rel="icon" type="image/png" href="./ressources/icons/favicon.png" />
 		<title></title>
 	</head>
 	<body>

 	</body>
 </html>
