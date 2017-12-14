<?php
	session_start();

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
			<?php
			if (isset($_SESSION['LOGGED_ON']))
			{
				echo '<a href="user.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="logout.php"><button class="icon" type="button" name="settings"><img src="./ressources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>';
			}
			else
			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./ressources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
			}
			?>
		</div>
		<?php
		if (isset($_SESSION['LOGGED_ON']))
		{
			try
			{
				$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$req = $conn->prepare('SELECT url FROM Photos WHERE userID = :id ORDER BY timet DESC');
				$req->execute(array(
				':id' => $_SESSION['ID']
				));
				$result = $req->fetchAll();
			}
			catch (Exception $e)
			{
				echo "Couldn't load photos : " . $e->getMessage();
			}

			echo '
			<div id="global">
				<div id="gauche">
				<form class="uplod" action="upload.php" method="post" enctype="multipart/form-data">
				Select image to upload:
				<input type="file" name="fileToUpload" id="fileToUpload" required>
				<input type="submit" value="Upload Image" name="submit">
					<div class="filters">
						<input type="radio" name="filter" value="blanka" id="blanka" checked/>
						<label><img src="./ressources/filters/blanka.png" alt="missing" class="filtersize" /></label>
						<input type="radio" name="filter" value="gouki" id="gouki"/>
						<label><img src="./ressources/filters/gouki.png" alt="missing" class="filtersize" /></label>
						<br>
						<input type="radio" name="filter" value="phoenix" id="phoenix"/>
						<label><img src="./ressources/filters/phoenix.png" alt="missing" class="filtersize" /></label>
						<input type="radio" name="filter" value="marine" id="marine"/>
						<label><img src="./ressources/filters/marine.png" alt="missing" class="filtersize" /></label>
					</div>
					</form>
					<video id="video"></video>
					<button type="submit" class="cambutton" id="startbutton"><img src="./ressources/icons/photo-camera.png" style="width:4vw;height=4vw;"/></button>
					<img id="photo" />
					<canvas id="canvas" style="display:none;"></canvas>
				</div>
				<div id="droite">';
					foreach ($result as $value)
					{
							echo "<div class='del'>
									<img class='gallery' src='./pics/" . $value['url'] . "'/>
									<div class='delbutton'><a href='delpicture.php?pic=" . $value['url'] . "'><img src='./ressources/icons/delwhite.png' style='width:4vw;height=4vw;'/></a>
									</div>
								</div>";
					}
				 echo '</div>
			</div>';
		}
		?>
		<div class="footer">
		</div>
	</body>
</html>

<?php
if (isset($_SESSION['LOGGED_ON']))
{
?>
<script>
(function() {
		var streaming = false,
		video = document.querySelector('#video'),
		cover = document.querySelector('#cover'),
		canvas = document.querySelector('#canvas'),
		context = canvas.getContext('2d'),
		photo = document.querySelector('#photo'),
		startbutton = document.querySelector('#startbutton'),
		filter	= document.querySelector('#blanka'),

		width = (window.innerWidth / 5 ) ;
		height = window.innerHeight;

		navigator.getMedia = ( navigator.getUserMedia ||
								navigator.webkitGetUserMedia ||
								navigator.mozGetUserMedia ||
								navigator.msGetUserMedia);

  	navigator.getMedia(
    {
    	video: true,
    	audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

	function takepicture()
	{
		context.drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL("image/png");
		var tmp = new Image();
		tmp.src = data;

		var xml = new XMLHttpRequest()
		xml.open('POST', 'datastorage.php', true);
		xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xml.send("filter=" + document.querySelector('input[name="filter"]:checked').value + "&data=" + data);
		xml.onload = function()
		{
			var response = xml.responseText;
			photo.src = response;
			console.log(response);
		}
   }
  startbutton.addEventListener('click', function(ev)
  {
	takepicture();
  }, false);
})();
</script>
<?php
}
?>
