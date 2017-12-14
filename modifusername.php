<?php
	session_start();

	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');

	try
	{
		$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$update = $conn->prepare("UPDATE Photos SET username = :newusername WHERE username = :username");
		$update->execute(array(
			':newusername' => $_POST['newname'],
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
			':newusername' => $_POST['newname'],
			':username' => $_SESSION['LOGGED_ON']
		));
	}
	catch (Exception $e)
	{
		echo "Couldn't update : " . $e->getMessage();
	}

	$_SESSION['LOGGED_ON'] = $_POST['newname'];
	header('location:user.php');

 ?>
