<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
		try
		{
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $conn->prepare("SELECT * FROM users WHERE username = :username");
			$update->execute(array(
				':username' => $_POST['newname']));
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		if ($update->rowCount() == 0 && $_POST['newname'])
		{
			try
			{
				$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update = $conn->prepare("UPDATE Photos SET username = :newusername WHERE username = :username");
				$update->execute(array(
					':newusername' => htmlspecialchars($_POST['newname']),
					':username' => $_SESSION['LOGGED_ON']
				));
			}
			catch (Exception $e)
			{
				echo "Couldn't update : " . $e->getMessage();
			}
			try
			{
				$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update = $conn->prepare("UPDATE users SET username = :newusername WHERE username = :username");
				$update->execute(array(
					':newusername' => htmlspecialchars($_POST['newname']),
					':username' => $_SESSION['LOGGED_ON']
				));
			}
			catch (Exception $e)
			{
				echo "Couldn't update : " . $e->getMessage();
			}
			$_SESSION['LOGGED_ON'] = htmlspecialchars($_POST['newname']);
			header('location:user.php');
		}
		else {
			echo "Please update username in settings";
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
