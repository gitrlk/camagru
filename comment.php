<?php
	session_start();

	if (!isset($_SESSION['LOGGED_ON']) || !$_GET)
		header('location:index.php');

	$pic = $_GET['pic'];
?>
<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<div class="header">
			<a href="index.php" style=""><button class="title" name="button">CAMAGRU</button><a/>
				<a href="user.php"><button class="icon" type="button" name="settings"><img src="./ressources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>
				<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>
			<a href="logout.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>
		</div>
		<div id="global">
			<div id="gauche">
				<?php
				echo '<img src="./pics/' . $pic . ' "alt="missing" style="height:24vw;width:34vw;margin-top:3vw;" />';
				echo '<form id="addcomment" action="addcomment.php" method="post">
					<input type="text" placeholder="Your comment here" name="comment" style="width:34vw;" required>
					<input type="hidden" name="pic" value="' . $pic . '"/>';
				echo '<br>
					<input type="submit" value="submit">
					</form>';
				?>
			</div>
			<div id="droite">
			<?php
			if ($_SESSION['LOGGED_ON'])
			{
				try{
					$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$req = $conn->prepare("SELECT PhotoID FROM photos where url = :url");
					$req->execute(array(
						':url' => $pic
					));
					$idphoto = $req->fetch(PDO::FETCH_COLUMN, 0);
				}
				catch(PDOException $e)
				{
					echo "Couldn't write in Database: " . $e->getMessage();
				}
				try{
					$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$req = $conn->prepare("SELECT text, author FROM comments where photoID = :photoid");
					$req->execute(array(
						':photoid' => $idphoto
					));
					$comment = $req->fetchall();
				}
				catch(PDOException $e)
				{
					echo "Couldn't write in Database: " . $e->getMessage();
				}
				foreach ($comment as $value)
				{
					echo htmlspecialchars($value['author'] . ": " . $value['text']);
					echo "<br>";
				}
			}
			?>
			</div>
		</div>
			<div class="footer">

			</div>
	</body>
</html>
