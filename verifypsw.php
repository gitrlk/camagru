<?php
    session_start();
    $_SESSION['message'] = '';
    $_SESSION['login_err'] = '';
    $_SESSION['login_success'] = '';
    $resetok = 0;
	if (isset($_GET['email']) && isset($_GET['conflink']))
	{
		$email = $_GET['email'];
		$conflink = $_GET['conflink'];
		try
		{
			$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$request = $con->prepare("SELECT email, conflink FROM users WHERE email = :email AND conflink = :conflink AND resetpsw = '1'");
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
			$resetok = 1;
        }
        else
        {
            $_SESSION['message'] = "Seems something went wrong please contact the webmaster & include this information: " .$conflink .$email;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $_GET['email'];
		$conflink = $_GET['conflink'];
        if ($_POST['password'] == $_POST['psw-repeat'])
        {
            try
            {
                $password = hash("sha512", $_POST[password]);
                $con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update = $con->prepare("UPDATE users SET resetpsw = '0', conflink = NULL, password = :password WHERE email = :email AND conflink = :conflink AND activated = '1' AND resetpsw = '1'");
				$update->execute(array(
					':email' => $email,
                    ':conflink' => $conflink,
                    ':password' => $password
				));
            }
            catch (PDOexception $e)
            {
                echo "Couldn't write in database: " . $e->getMessage();
            }

        }
        else
        {
            $_SESSION['message'] = "Password doesn't match";
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
         <?php
         if ($resetok == 1)
         {
            echo $_SESSION["message"];
            echo '<label><b> New password</b></label>
            <form class="modal-content" action="verifypsw.php?email='.$email.'&conflink='.$conflink.'" method="post">
               <div class="container">

                    <input type="password" placeholder="Enter Password" name="password" required>
                   <label><b>Repeat new password</b></label>
                   <input type="password" placeholder="Repeat Password" name="psw-repeat" required>
                   <button type="submit" class="signup" name="clickme" style= "margin-left: 2%;margin-top: 2%";>Confirm</button>
           </div>

           </form>';
         }
         ?>
         </div>
 		<div class="footer">
 		</div>
 	</body>
 </html>
