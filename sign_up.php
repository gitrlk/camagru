<?php
session_start();
$_SESSION["message"] = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($_POST['password'] == $_POST['psw-repeat'])
    {
        if (preg_match("/^[a-zA-Z0-9]*$/", $username = $_POST['username']))
        {
            if(!strlen($_POST['password']) < 8)
            {
                if(preg_match("#[0-9]+#", $_POST['password']))
                {
                    if(preg_match("#[a-zA-Z]+#", $_POST['password']))
                    {
                        try
                        {
                            $email = $_POST['email'];
                            $con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
							$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$request = $con->prepare("SELECT email FROM users WHERE email = :email;");
                            $request->bindParam(':email', $email);
                            $request->execute();
                        }
                        catch(PDOException $e)
                        {
                            echo "Couldn't write in database: " . $e->getMessage();
                        }
                        if ($request->rowCount() == 0)
                        {
                            if (filter_var($email = $_POST['email'], FILTER_VALIDATE_EMAIL))
                            {
                                try
                                {
                                    $con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
									$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$request = $con->prepare("SELECT username FROM users WHERE username = :name;");
                                    $request->bindParam(':name', $username);
                                    $request->execute();
                                }
                                catch(PDOException $e)
                                {
                                    echo "Couldn't write in database: " . $e->getMessage();
                                }
                                if ($request->rowCount() == 0)
                                {
                                    try
                                    {
                                        $conflink = md5( rand(0,1000) );
                                        $password = hash("sha512", $_POST['password']);
                                        $bdd = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
										$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$req = $bdd->prepare('INSERT INTO users (username, password, email, conflink, emailcomment) VALUES (:username, :password, :email, :conflink, :emailcomment)');
                                        $req->execute(array(
                                            ':username' => $_POST['username'],
                                            ':password' => $password,
                                            ':email' => $email,
                                            ':conflink' => $conflink,
											':emailcomment' => 1));
                                    }
                                    catch(PDOException $e)
                                    {
                                        echo "Couldn't write in database: " . $e->getMessage();
                                    }
                                    $to       =  $email;
                                    $subject  = 'Signup | Verification';
                                    $message  = '

                                    Thanks for signing up!
                                    Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.

                                    ------------------------
                                    Username: '.$username.'
                                    ------------------------

                                    Please click this link to activate your account:
                                    http://localhost:8080/verify.php?email='.$email.'&conflink='.$conflink.'

                                    ';

                                    $headers = 'From:noreply@camagru.com' . "\r\n";
                                    mail($to, $subject, $message, $headers);
                                    header( "refresh:0;url=account_created.php" );
                                }
                                else
                                {
                                    $_SESSION['message'] ='Username already taken';
                                }
                            }
                            else
                            {
                                $_SESSION['message'] ='Invalid email format';
                            }
                        }
                        else
                        {
                            $_SESSION['message'] = 'Email already used';
                        }
                    }
                    else
                    {
                        $_SESSION['message'] = 'Password must include at least one letter';
                    }
                }
                else
                {
                    $_SESSION['message'] = 'Password must include at least one number';
                }
            }
            else
            {
                $_SESSION['message'] = 'Password must be at least 8 characters long';
            }
        }
        else
        {
            $_SESSION['message'] = 'Invalid username use only letters or numbers';
        }
    }
    else
    {
        $_SESSION["message"] = "Your password must match";
    }
}
?>
<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="./ressources/icons/favicon.png" />
		<title></title>
	</head>
	<body>
		<div class="header">
		    <a href="index.php"><button class="title" name="button">CAMAGRU</button><a/>
			<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>
			<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./ressources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>
			<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>
		</div>
		<div class="main">
            <form class="modal-content" action="sign_up.php" method="post">
            <div class="container">
                <div class="log_error"><?= $_SESSION["message"] ?></div>
                <label><b>Users</b></label>
                <input type="text" placeholder="Enter user name" name="username" required>

                <label><b>Email</b></label>
                <input type="email" placeholder="Enter email address" name="email" required>

                <label><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <label><b>Repeat Password</b></label>
                <input type="password" placeholder="Repeat Password" name="psw-repeat" required>
                <div class="clearfix" style="text-align: center;">
                    <button type="submit" class="signup" name="clickme" style= "margin-left: 2%;margin-top:2%;";>Sign Up</button>
                </div>
            </div>
            </form>
        </div>
	</div>
	<div class="footer">
	</div>

	</body>
</html>
