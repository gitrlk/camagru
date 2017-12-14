<?php
session_start();
$_SESSION['message'] = '';
$_SESSION['login_err'] = '';
$_SESSION['login_success'] = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
        $email = $_POST["email"];
		$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$req = $con->prepare("SELECT username FROM users WHERE email = :email");
        $req->execute(array(':email' => $email));
		if ($req->rowCount() > 0)
		{
            $datauser = $req->fetch();
            $username =	$datauser['username'];
            try
            {
                $result = $con->query("SELECT activated FROM users WHERE email = " . "'" . $email . "'");
            }
            catch (PDOexception $e)
            {
                echo "Error Database : " . $e->getMessage();
            }

            $dataresetstate = $result->fetch();
            $activated = $dataresetstate[activated];
            if ($activated == 1)
            {
                try
                {
                    $conflink = md5( rand(0,1000) );
                    $update = $con->prepare("UPDATE users SET resetpsw = '1', conflink = :conflink WHERE email = :email");
                    $update->execute(array(
                        ':email' => $email,
                        ':conflink' => $conflink
                    ));
                }
                catch(PDOexception $e)
                {
                    echo "Error Database : " . $e->getMessage();
                }
                $to       =  $email;
                $subject  = 'Camagru | Reset your password';
                $message  = '

                This email has been sent automatically by Camagru to your request to recorver your password.

                ------------------------
                Username: '.$username.'
                ------------------------

                Please click this link to reset your account password:
                http://localhost:8080/verifypsw.php?email='.$email.'&conflink='.$conflink.'

                ';
                $headers = 'From:noreply@camagru.com' . "\r\n";
                mail($to, $subject, $message, $headers);
                $_SESSION['login_success'] = "Reset Email has been sent";
                header( "refresh:3;url=index.php" );
            }
            else
            {
                $_SESSION['login_err'] = "Your account is not activated yet, please check your Inbox or Spam";
            }

        }
		else
		{
			$_SESSION['login_err'] = "Email doesn't exist";
		}
	}
	catch (PDOexception $e)
	{
		echo "Error Database : " . $e->getMessage();
	}
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
 			<?php
 			if (isset($_SESSION['LOGGED_ON']))
 			{
 				echo '<a href="profile.php"><button class="signed" style="padding-left: 0px;type="button" name="profile">' . $_SESSION['LOGGED_ON'] ."</button></a>";
 				echo '<a href="logout.php"><button class="button" type="button" name="Logout">Log out</button></a>';
 			}
 			else
 			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./ressources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}
 			?>
		</div>
		<div class="main">
		<form class="modal-content" action="resetpsw.php" method="post">
            <div style = "padding:14%">
                <div class="log_error"><?= $_SESSION['login_err'] ?></div>
				<div class="log_succes"><?= $_SESSION['login_success'] ?></div>
                <label><b>Email</b></label>
                <input type="text" placeholder="Enter user name" name="email" required>
                <div class="clearfix" style="text-align: center;">
                    <button type="submit" class="signup" name="clickme" style= "margin-left: 2%">Reset Password</button>
                </div>
            </div>
        </form>
		</div>
		<div class="footer">

		</div>

	</body>
</html>
