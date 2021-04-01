<?php
	session_start();

	require_once 'db_connection.php';
	
	$fio=antisql($connect, $_POST['fio']);
	$email=antisql($connect, $_POST['email']);
	$tel=antisql($connect, $_POST['tel']);
	$password=$_POST['password'];
	$pass_conf=$_POST['pass_conf'];
	
	
	if(!$fio||!$email||!$tel||!$password||!$pass_conf)
	{
		$_SESSION['message']='Пожалуйста, заполните все поля';
		header('Location: ../reg.php');
		exit;
	}
	
	if($password!=$pass_conf)
	{
		$_SESSION['message']='Ошибка: Пароли не совпадают!';
		header('Location: ../reg.php');
		exit;
	}
	
	if(mb_strlen($tel)<5||mb_strlen($tel)>18)
	{
		$_SESSION['message']='Ошибка: Длина телефона должна быть в пределах 5-18 цифр';
		header('Location: ../reg.php');
		exit;
	}
	
	if(mb_strlen($password)<4||mb_strlen($password)>15)
	{
		$_SESSION['message']='Ошибка: Длина пароля должна быть в пределах 4-15 символов';
		header('Location: ../reg.php');
		exit;
	}
	
	$password=md5(antisql($connect, $password));
	
	$query=mysqli_query($connect, "INSERT INTO `user` (`id`, `fio`, `email`, `tel`, `password`,`role`) VALUES (NULL, '$fio', '$email', '$tel', '$password',1)");
	
	if(!$query)
	{
		$_SESSION['message']='Неизвестная ошибка';
		header('Location: ../reg.php');
		exit;
	}
	$_SESSION['message']='Регистрация прошла успешно';
	$check_user=mysqli_query($connect, "SELECT * FROM `user` WHERE (`fio`='$fio' OR `email`='$fio') AND `password`='$password'");
	$user=mysqli_fetch_assoc($check_user);
	$_SESSION['user']=
	[
		"id"=>$user['id'],
		"fio"=>$user['fio'],
		"email"=>$user['email'],
		"role"=>$user['role']
	];
	
	mysqli_close($connect);
	header('Location: ../../index.php');
?>