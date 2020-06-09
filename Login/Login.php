<?php
	require '../Connection/connection.php';
	$errors = array();
	// kondisi ketika tombol submit ditekan dan memeriksa apakah yang dimasukkan pengguna termasuk level student atau teacher
	if(isset($_POST['submit'])){
		function checkPassword($level){
			require '../Connection/connection.php';
			$statement = $dbc->prepare("SELECT USERNAME, PASSWORD, ID_LEVEL FROM user WHERE USERNAME = :USERNAME and PASSWORD = SHA2(:PASSWORD,0) and ID_LEVEL = :ID_LEVEL");
			$statement->bindValue(':USERNAME',$_POST['username']);
			$statement->bindValue(':PASSWORD',$_POST['password']);
			$statement->bindValue(':ID_LEVEL',$level);
			$statement->execute();
			return $statement->rowCount()>0;
		}
		function check1(){
			require '../Connection/connection.php';
			$statement1 = $dbc->prepare("SELECT USERNAME FROM user WHERE USERNAME = :USERNAME");
			$statement1->bindValue(':USERNAME',$_POST['username']);
			$statement1->execute();
			return $statement1->rowCount()>0;
		}
		function check2(){
			require '../Connection/connection.php';
			$statement2 = $dbc->prepare("SELECT PASSWORD FROM user WHERE PASSWORD = SHA2(:PASSWORD,0)");
			$statement2->bindValue(':PASSWORD',$_POST['password']);
			$statement2->execute();
			return $statement2->rowCount()>0;
		}
		if(check1()){
			$errors['username'] = " ";
			if(check2()){
				$errors['password'] = " ";
			}
			else{
				$errors['password'] = "Invalid password";
			}
		}
		else{
			$errors['username'] = "Invalid username";
			$errors['password'] = "Invalid password";
		}
		// kondisi ketika pengguna masuk dengan username dan password teacher dan juga untuk membuka session
		if(checkPassword('1')){
			$errors['username'] = ' ';
			session_start();
			$_SESSION['Teacher'] = true;
			$a = $_POST['username'];
			header('Location: ../Teacher/Teacher.php?id_teach='.$a);
			exit();
		}
		// kondisi ketika pengguna masuk dengan username dan password student dan juga untuk membuka session
		elseif(checkPassword('2')){
			$errors['username'] = ' ';
			session_start();
			$_SESSION['Student'] = true;
			$a = $_POST['username'];
			header('Location: ../Student/Student.php?id_student='.$a);
			exit();
		}
		else{
			if(empty($_POST['username'])){
				$errors['username'] = "Field is required";
			}
			if(empty($_POST['password'])){
				$errors['password'] = "Field is required";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang = "en">
	<head>
		<meta charset = "utf-8">
		<title> Project Akhir PAW </title>
		<link href = "../CSS/CSS_Login.css" rel =stylesheet type = "text/css"/>
	</head>
	<body> 
		<!-- untuk membuat konten (isi) -->
		<div class = "content">
			<h1> LOGIN </h1>
			<!-- untuk menampilkan form username dan password -->
			<div class = "sidebar">
				<form name = "myForm" action = "Login.php" method = "POST">
					<h2> Get Start for Free </h2>
						<div class = "isi"><label id = "username"> Username : </label></div>
						<div class = "isi"><input type = "text" name = "username" value = "<?php if(isset($_POST['username'])) echo htmlspecialchars($_POST['username'])?>"/></div>
						<div class = "merah">
							<?php 
								if(isset($_POST['username'])) 
									echo $errors['username']; 
								else 
									echo ' ';
							?>
						</div>
						<div class = "isi"><label id = "password"> Password : </label></div>
						<div class = "isi"><input type = "password" name = "password" value = "<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'])?>"/></div>
						<div class = "merah">
							<?php 
								if(isset($_POST['password'])) 
									echo $errors['password']; 
								else 
									echo ' ';
							?>
						</div>
						<div class = "isi"><input type = "submit" class = "submit" value = "Submit" name = "submit"/></div>
				</form>
				<div class = "login">
					<a href = ""><img class = "gambar" src = "../gambar_PAW/gmail.png" alt = "gmail"/></a>
					<a href = ""><img class = "gambar" src = "../gambar_PAW/google.png" alt = "google"/></a>
					<a href = ""><img class = "gambar" src = "../gambar_PAW/facebook.png" alt = "facebook"/></a>
				</div>
			</div>
		</div>
		<!-- untuk membuat footer -->
		<div class = "footer"> 
			<!-- untuk membuat border di dalam footer -->
			<div class = "batas"> 
				<div class = "ukuran"> Copyright @ Yourwebsite | powered by English Course </div>
			</div>
		</div>
	</body>
</html>