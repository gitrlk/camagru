<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']) || !$_POST)
		header('location:index.php');

	if ($_SESSION['LOGGED_ON'] && isset($_POST['filter']) && isset($_POST['data']))
	{
		if (!file_exists("./pics"))
			mkdir("./pics");
		$filter = "./ressources/filters/" . $_POST['filter'] . ".png";
		$img = $_POST['data'];

		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$filedata = base64_decode($img);
		$filepath = "./pics/";
		$filesql = $_SESSION['ID'] . " " . time() . '.png';
		$filename = $filepath . $_SESSION['ID'] . " " . time() . '.png';
		file_put_contents($filename, $filedata);

		if (file_exists($filter))
		{
			$dest = imagecreatefromstring($filedata);
			$src = imagecreatefrompng($filter);
			$src = imagescale($src, imagesx($dest) * 0.5);
			imagecopy($dest, $src, 0, 0, 0, 0, imagesx($src) - 1, imagesy($src) - 1);
			imagepng($dest, $filename);
		}
		try
		{
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$req = $conn->prepare('INSERT INTO Photos (username, timet, url, UserID) VALUES (:username, NOW() , :url, :userID)');
			$req->execute(array(
				':username' => $_SESSION['LOGGED_ON'],
				':url' => $filesql,
				':userID' => $_SESSION['ID']
			));
		}
		catch(PDOException $e)
		{
			echo "Couldn't write in Database: " . $e->getMessage();
		}
		echo $filename;
	}
	else
		header('location:index.php');
?>
