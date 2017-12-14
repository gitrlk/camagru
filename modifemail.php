<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');

	if (filter_var($email = $_POST['newmail'], FILTER_VALIDATE_EMAIL))
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
		header( "refresh:1; url=user.php" );
		echo "this ain't no email adress bro ??? redirecting your ass";
	}
 ?>
