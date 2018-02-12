<?php
	require "database.php";

	if (!file_exists("../pics"))
		mkdir("../pics");
	try
	{
		$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$req = "CREATE DATABASE IF NOT EXISTS db_camagru";
		$req = $conn->prepare($req);
		$req->execute();
	}
	catch(PDOException $e)
	{
		echo "Error creating DataBase: " . $e->getMessage();
	}
	try
	{
		$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$qry = "CREATE TABLE IF NOT EXISTS `db_camagru`.`users` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`username` VARCHAR(255) NOT NULL,
			`email` VARCHAR(255) NOT NULL,
			`conflink` VARCHAR(255),
			`activated` INT NOT NULL DEFAULT 0,
			`password` VARCHAR(255) NOT NULL,
			`avatar` VARCHAR(255),
			`resetpsw` INT NOT NULL DEFAULT 0,
			`emailcomment` INT NOT NULL DEFAULT 0,
			PRIMARY KEY (`id`));
		  ";
		$conn->exec($qry);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$qry = "CREATE TABLE IF NOT EXISTS `db_camagru`.`Photos` (
		`PhotoID` INT NOT NULL AUTO_INCREMENT,
		`UserID` INT NOT NULL,
		`username` VARCHAR(255) NOT NULL,
		`timet` DATETIME NOT NULL,
		`url` VARCHAR(255) NOT NULL,
		PRIMARY KEY (`PhotoID`));
			";
		$conn->exec($qry);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$qry = "CREATE TABLE IF NOT EXISTS `db_camagru`.`comments` (
		`CommentID` INT NOT NULL AUTO_INCREMENT,
		`photoID` INT NOT NULL,
		`author` VARCHAR(255) NOT NULL,
		`timet` DATETIME NOT NULL,
		`text` TEXT NOT NULL,
		PRIMARY KEY (`CommentID`));
			";
		$conn->exec($qry);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$conn = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$qry = "CREATE TABLE IF NOT EXISTS `db_camagru`.`likes` (
		`LikeID` INT NOT NULL AUTO_INCREMENT,
		`photoID` INT NOT NULL,
		`UserID` INT NOT NULL,
		PRIMARY KEY (LikeID));
			";
		$conn->exec($qry);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	$conn = null;

	try {

	} catch (Exception $e) {

	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="/ressources/icons/favicon.png" />
		<title></title>
	</head>
	<body>

	</body>
</html>
