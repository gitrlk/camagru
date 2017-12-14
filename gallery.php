<?php
session_start();
$_SESSION['message'] = '';
?>
<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<meta charset="utf-8">
		<title></title>
	</head>
	<body onload="setInterval('scroll();', 250);">
			<div class="header">
			<a href="index.php"><button class="title" name="button">CAMAGRU</button><a/>
 			<?php
 			if (isset($_SESSION['LOGGED_ON']))
 			{
				echo '<a href="user.php"><button class="icon" type="button" name="settings"><img src="./ressources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="logout.php"><button class="icon" type="button" name="logout"><img src="./ressources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}
 			else
 			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./ressources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}
 			?>
 			</div>
		</div>
		<div class="main">
		<?php

		if (isset($_GET['p']))
			$page = $_GET['p'];
		else
			$page = 1;

		$items = 10;

		try
		{
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$req = $conn->prepare('SELECT COUNT(PhotoID) FROM Photos');
			$req->execute();
			$total = $req->fetchColumn();
		}
		catch(Exception $e)
		{
			echo "Couldn't count bro: " . $e->getMessage();
		}

		try
		{
			// requete pour recupere les photos par utilisateur
			// $req = $conn->prepare('SELECT url FROM Photos WHERE username = :username ORDER BY timet');
			$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$req = $conn->prepare('SELECT url, PhotoID FROM Photos ORDER BY timet DESC LIMIT ' . (($page - 1)) * $items .' , ' . $items . '');
			$req->execute();
			$result = $req->fetchAll();
		}
		catch (Exception $e)
		{
			echo "Couldn't read in Database: " . $e->getMessage();
		}


			echo "<div class='galleryview'>";
			foreach ($result as $value)
			{
				echo "<div id='container'>
				<img class='gallery' src='./pics/" . $value['url'] . "'/>";
				if (isset($_SESSION['LOGGED_ON']))
				{
					echo "<div class='likebutton'>
							<a href='like.php?pic=" . $value['url'] . "'> <img src='./ressources/icons/like.png' style='width:4vw;height=4vw;'/></a>
							<a href='comment.php?pic=" . $value['url'] . "'><img src='./ressources/icons/comment.png' style='width:4vw;height=4vw;'/></a>
							</div>";

					echo "<div class='likencomment'>";
					try
					{
						$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$req = $conn->prepare("SELECT LikeID FROM likes WHERE photoID = :photoID");
						$req->execute(array(
							':photoID' => $value['PhotoID']
						));
					}
					catch(PDOException $e)
					{
						echo "Couldn't write in Database: " . $e->getMessage();
					}
					$count = $req->rowCount();
					echo $count . '  ❤' ;
					echo "</div>";
				}
			echo "</div>";
			}
		echo "</div>";
		$pagenumber = ceil($total / $items);
		for($i = 1; $i <= $pagenumber; $i++)
		{
			echo "<a href='gallery.php?p=". $i  ."'>" . $i . " / </a>";
		}
		?>
		</div>
		<div class="footer">
		</div>
	</body>
</html>
