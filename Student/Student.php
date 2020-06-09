<?php 
	require '../Connection/connection.php';
	require '../Login/Validate.php'; 
	$errors = array();
	// kondisi ketika logout ditekan dan menutup session
	if(isset($_REQUEST['logout'])){
		session_start();
		unset($_SESSION['Student']);
		header('Location: ../Login/Login.php');
	}
	// kondisi ketika kelas ditekan
	if(isset($_REQUEST['class'])){ 
		$class = $_REQUEST['class'];
		$stu = $_REQUEST['id_student'];
	}	
	// mengambil data dari file login.php
	if(isset($_REQUEST['id_student'])){
		$stu = $_REQUEST['id_student'];
	}
	// kondisi ketika my profile ditekan
	if(isset($_REQUEST['edit'])){
		$stu = $_REQUEST['id_student'];
	}
	// kondisi ketika tombol home ditekan
	if(isset($_REQUEST['profil'])){
		$stu = $_REQUEST['id_student'];
	}
	// kondisi untuk validasi dan ubah email dengan nomer telepon
	if(isset($_POST['save'])){
		validateEmail($errors, $_POST, 'email');
		validateNomer($errors, $_POST, 'nomer');
		foreach($errors as $eror){
			if($eror == " "){
				$nama_gambar = $_FILES['file_foto']['name'];
				$statement = $dbc->prepare("UPDATE user SET NAMA_DEPAN = :NAMA_DEPAN, NAMA_BELAKANG = :NAMA_BELAKANG, EMAIL = :EMAIL, KOTA = :KOTA, NO_TELEPON = :NO_TELEPON, FILE_FOTO = :FILE_FOTO, KETERANGAN = :KETERANGAN WHERE USERNAME = :USERNAME");
				$statement->bindValue(':USERNAME',$stu);
				$statement->bindValue(':NAMA_DEPAN',$_POST['nama_depan']);
				$statement->bindValue(':NAMA_BELAKANG',$_POST['nama_belakang']);
				$statement->bindValue(':EMAIL',$_POST['email']);
				$statement->bindValue(':KOTA',$_POST['kota']);
				$statement->bindValue(':NO_TELEPON',$_POST['nomer']);
				$statement->bindValue(':FILE_FOTO',$nama_gambar);
				$statement->bindValue(':KETERANGAN',$_POST['keterangan']);
				$statement->execute();
			}
			else{
				$edit = 0;
			}
		}
	}
	// kondisi untuk validasi dan ubah password
	if(isset($_POST['changed'])){ 
		validatePassword($errors, $_POST, 'password_baru');
		validatePassword($errors, $_POST, 'confirm_password');
		function check(){
			require '../Connection/connection.php';
			$statement2 = $dbc->prepare("SELECT PASSWORD FROM user WHERE PASSWORD = SHA2(:PASSWORD,0)");
			$statement2->bindValue(':PASSWORD',$_POST['password_awal']);
			$statement2->execute();
			return $statement2->rowCount()>0;
		}
		if(empty($_POST['password_awal'])){
			$errors['password_awal'] = "Field is required";
		}
		elseif(check()){
			$errors['password_awal'] = " ";
		}
		else{
			$errors['password_awal'] = "Invalid password";
		}
		if($errors){
			$s = 0;
			foreach($errors as $eror){
				if($eror == " "){
					$s = $s + 1;
				}
			}
			if($s == 3){
				$statement = $dbc->prepare("UPDATE user SET PASSWORD = SHA2(:PASSWORD,0) WHERE USERNAME = :USERNAME");
				$statement->bindValue(':USERNAME',$stu);
				$statement->bindValue(':PASSWORD',$_POST['confirm_password']);
				$statement->execute();
			}
			else{
				$ubah = 0;
			}
		}
	}
	// kondisi ketika kelas ditekan dan menambah kelas
	if(isset($_REQUEST['id_class'])){
		$stu = $_REQUEST['id_student'];
		$statement = $dbc->prepare("INSERT INTO class_student (ID_CLASS, USERNAME) VALUE (:ID_CLASS, :USERNAME)");
		$statement->bindValue(':ID_CLASS',$_REQUEST['id_class']);
		$statement->bindValue(':USERNAME',$stu);
		$statement->execute();
	}
?>
<!DOCTYPE html>
<html lang = "en">
	<head>
		<meta charset = "utf-8">
		<title> Project Akhir PAW </title>
		<link href = "../CSS/CSS_Teacher.css" rel =stylesheet type = "text/css"/>
	</head>
	<body> 
		<!-- untuk membuat border yang berada paling atas berisi tentang nama dari elearning -->
		<div class = "atas">
			<!-- untuk memasukkan nama elearning -->
			<div>
				<h2> ENGLISH COURSE </h2>
			</div>
			
		</div>
		<!-- untuk membuat header yang berisi logout dan home -->
		<div class = "header"> 
			<!-- untuk membuat tombol logout -->
			<div class = "home">
				<img class = "gambar" src = "../gambar_PAW/profil.png" alt = "profil"/>
				<a href = "Student.php?logout"> Logout </a>
			</div>
			<!-- untuk membuat tombol home -->
			<div class = "home">
				<img class = "gambar" src = "../gambar_PAW/home.png" alt = "home"/>
				<?php
					echo "<a href = \"Student.php?profil&id_student=$stu\"> Home </a>";
				?>
			</div>
		</div>
		<!-- untuk membuat sidebar -->
		<div class = "sidebar"> 
			<!-- untuk membuat sidebox pertama di dalam sidebar -->
			<div class = "sidebox" > 
				<!-- untuk membuat box pertama di dalam sidebox -->
				<div class = "box">	
					<!-- untuk menampilkan nama dan level dari pengguna elearning -->
					<?php							
						$statement = $dbc->prepare("SELECT u.USERNAME, u.NAMA_DEPAN, u.NAMA_BELAKANG, l.NAMA_LEVEL FROM user u, level l WHERE u.ID_LEVEL = l.ID_LEVEL and u.USERNAME = '$stu'");
						$statement->bindValue(':USERNAME',':USERNAME');
						$statement->bindValue(':NAMA_DEPAN',':NAMA_DEPAN');
						$statement->bindValue(':NAMA_BELAKANG',':NAMA_BELAKANG');
						$statement->bindValue(':NAMA_LEVEL',':NAMA_LEVEL');
						$statement->execute();
						foreach($statement as $a){
							echo "<div class = 'info'><img class = 'gambar' src = '../gambar_PAW/profil.png' alt = 'profil'/>";
							echo ' ';
							echo $a['NAMA_DEPAN'];
							echo ' ';								
							echo $a['NAMA_BELAKANG'];
							echo "</div>"; 
							echo "<div class = 'level'>";
								echo $a['NAMA_LEVEL'];
							echo "</div>";
							echo "<p class = 'profil'>"; 
								echo "<a href = \"Student.php?edit&id_student=$stu\"> My Profile </a>";
							echo "</p>";
						}
					?>
				</div>
				<p class = "garis"></p>
				<!-- untuk membuat box kedua di dalam sidebox -->
				<div class = "box1">
					<!-- untuk menampilkan logo -->
					<div class = "info">
						<img class = "gambar" src = "../gambar_PAW/myclass.png" alt = "myclass"/> My Class 
					</div>
					<ul>
						<!-- untuk menampilkan kelas yang diikuti pengguna -->
						<?php
							$statement = $dbc->prepare("SELECT s.ID_CLASS, s.NAMA_CLASS FROM class s, class_student c WHERE s.ID_CLASS = c.ID_CLASS and c.USERNAME = '$stu'");	
							$statement->bindValue(':ID_CLASS',':ID_CLASS');
							$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
							$statement->execute();
							foreach ($statement as $namaClass){
								echo "<li><a href = \"Student.php?class={$namaClass['ID_CLASS']}&id_student=$stu\"> {$namaClass['NAMA_CLASS']} </a></li>";
							}
						?>
					</ul>
				</div>
			</div>
		</div>
		<!-- untuk membuat sidebar kedua -->
		<div class = "sidebar1">
			<!-- untuk membuat sidebox pertama di dalam sidebar kedua -->
			<div class = "sidebox1">
				<!-- untuk membuat box pertama di dalam sidebox -->
				<div class = "box">
					<div class = "info"><img class = "gambar" src = "../gambar_PAW/student.png" alt = "student"/> Classes </div> 
						<ul>
							<!-- untuk menampilkan semua kelas yang ada di sistem elearning -->
							<?php
								$statement1 = $dbc->prepare("SELECT c.ID_CLASS, c.NAMA_CLASS FROM class c, class_student s WHERE c.ID_CLASS = s.ID_CLASS and s.USERNAME = $stu");
								$statement1->bindValue(':ID_CLASS',':ID_CLASS');
								$statement1->bindValue(':NAMA_CLASS',':NAMA_CLASS');
								$statement1->execute();
								
								$statement2 = $dbc->prepare("SELECT l.NAMA_CLASS, l.ID_CLASS FROM class l WHERE l.ID_CLASS NOT IN (SELECT c.ID_CLASS FROM class c, class_student s WHERE c.ID_CLASS = s.ID_CLASS and s.USERNAME = $stu)");
								$statement2->bindValue(':ID_CLASS',':ID_CLASS');
								$statement2->bindValue(':NAMA_CLASS',':NAMA_CLASS');
								$statement2->execute();
								
								foreach ($statement1 as $class){
									echo "<li><a href = \"Student.php?id_class={$class['ID_CLASS']}&id_student=$stu\" onClick = \"return alert('Class is exists')\">{$class['NAMA_CLASS']}</a></li>";
								}
								
								foreach ($statement2 as $class1){
									echo "<li><a href = \"Student.php?id_class={$class1['ID_CLASS']}&id_student=$stu\" onClick = \"return confirm('Do you want join class ???')\">{$class1['NAMA_CLASS']}</a></li>";
								}
							?>
						</ul>
				</div>
			</div>
		</div>
		<!-- untuk membuat konten (isi) -->
		<div class = "content">
			<?php
				// untuk menampilkan form yang berisi tentang edit kelas
				if(isset($_REQUEST['edit']) || isset($edit)){
					echo "<div class = 'judul'>";	
						echo "<div class = 'detail1'> EDIT PROFILE </div>";
						$statement = $dbc->prepare("SELECT * FROM user WHERE USERNAME = '$stu'");
						$statement->bindValue(':USERNAME',':USERNAME');
						$statement->bindValue(':NAMA_DEPAN',':NAMA_DEPAN');
						$statement->bindValue(':NAMA_BELAKANG',':NAMA_BELAKANG');
						$statement->bindValue(':EMAIL',':EMAIL');
						$statement->bindValue(':KOTA',':KOTA');
						$statement->bindValue(':NO_TELEPON',':NO_TELEPON');
						$statement->bindValue(':FILE_FOTO',':FILE_FOTO');
						$statement->bindValue(':KETERANGAN',':KETERANGAN');
						$statement->execute();
						foreach($statement as $a){	
							echo "<form class = 'edit' name = \"Edit\" action = \"Student.php\" method = \"POST\" enctype = \"multipart/form-data\">";
								echo "<input type = \"hidden\" name = \"id_student\" value = \"{$stu}\">";
								echo "<label id = \"detail_class\"> First name : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"nama_depan\" value = \"{$a['NAMA_DEPAN']}\"><br/>";
								echo "<label id = \"nama_class\"> Last name : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"nama_belakang\" value = \"{$a['NAMA_BELAKANG']}\"><br/>";
								echo "<label id = \"nama_class\"> Email : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"email\" value = \"{$a['EMAIL']}\"><br/>";
								echo "<div class = 'merah'>";
									if(isset($_POST['email'])) 
										echo $errors['email'];
									else 
										echo ' ';
								echo "</div>";
								echo "<label id = \"nomer\"> Phone number : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"nomer\" value = \"{$a['NO_TELEPON']}\"><br/>";
								echo "<div class = 'merah'>";
									if(isset($_POST['nomer'])) 
										echo $errors['nomer'];
									else 
										echo ' ';
								echo "</div>";
								echo "<label id = \"nama_class\"> City : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"kota\" value = \"{$a['KOTA']}\"><br/>";
								echo "<label id = \"nama_class\"> Photo file : </label><br/>";
								echo "<input class = 'edit' type = \"file\" name = \"file_foto\" value = \"{$a['FILE_FOTO']}\"><br/>";
								echo "<label id = \"nama_class\"> Information : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"keterangan\" value = \"{$a['KETERANGAN']}\"><br/>";
								echo "<input class = 'edit1' type = \"submit\" value = \"Update Profile\" name = \"save\"/>";
								echo "<input class = 'edit1' type = \"submit\" value = \"Change Password\" name = \"change\"/>";
							echo "</form>";
						}
					echo "</div>";
				}
				// untuk menampilkan form yang berisi tentang ubah password
				else if(isset($_POST['change']) || isset($ubah)){
					echo "<div class = 'judul'>";	
						echo "<div class = 'detail1'> CHANGE PASSWORD </div>";
						$statement = $dbc->prepare("SELECT USERNAME FROM user WHERE USERNAME = '$stu'");
						$statement->bindValue(':USERNAME',':USERNAME');
						$statement->execute();
						foreach($statement as $a){	
							echo "<form class = 'edit' name = \"Edit\" action = \"Student.php\" method = \"POST\">";
								echo "<input type = \"hidden\" name = \"id_student\" value = \"{$stu}\">";
								echo "<label id = \"password_awal\"> Current password : </label><br/>";
								echo "<input class = 'edit' type = \"password\" name = \"password_awal\" value = \""; 
									if(isset($_POST['password_awal'])){ 
										echo $_POST['password_awal'];
									}; 
								echo "\"><br/>";
								echo "<div class = 'merah'>";
									if(isset($_POST['password_awal'])) 
										echo $errors['password_awal'];
									else 
										echo ' ';
								echo "</div>";
								echo "<label id = \"password_baru\"> New password : </label><br/>";
								echo "<input class = 'edit' type = \"password\" name = \"password_baru\" value = \""; 
									if(isset($_POST['password_baru'])){ 
										echo $_POST['password_baru'];
									}; 
								echo "\"><br/>";
								echo "<div class = 'merah'>";
									if(isset($_POST['password_baru'])) 
										echo $errors['password_baru'];
									else 
										echo ' ';
								echo "</div>";
								echo "<label id = \"confirm_password\"> Confirm your password : </label><br/>";
								echo "<input class = 'edit' type = \"password\" name = \"confirm_password\" value = \""; 
									if(isset($_POST['confirm_password'])){ 
										echo $_POST['confirm_password'];
									}; 
								echo "\"><br/>";
								echo "<div class = 'merah'>";
									if(isset($_POST['confirm_password'])) 
										echo $errors['confirm_password'];
									else 
										echo ' ';
								echo "</div>";
								echo "<input class = 'edit1' type = \"submit\" value = \"Change Password\" name = \"changed\"/>";
							echo "</form>";
						}
					echo "</div>";
				}
				// untuk menampilkan nama kelas dan detail kelas
				else if(isset($_REQUEST['id_student'])){
					echo "<div class = 'judul'>";
						echo "<div class = 'title'>";				
							function judul($cl, $st){
								require '../Connection/connection.php';
								$statement = $dbc->prepare("SELECT c.NAMA_CLASS, c.DETAIL_CLASS FROM class c, class_student s WHERE c.ID_CLASS = s.ID_CLASS and s.ID_CLASS = '$cl' and s.USERNAME = '$st'");
								$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
								$statement->bindValue(':DETAIL_CLASS',':DETAIL_CLASS');
								$statement->execute();
								foreach($statement as $a){
									echo $a['NAMA_CLASS'];
									echo "<div class = 'detail'>";
										echo $a['DETAIL_CLASS'];
									echo "</div>";
								} 
							}
							if(isset($_REQUEST['class'])){
								judul($_REQUEST['class'],$_REQUEST['id_student']);
							}
							if(isset($_REQUEST['id_class'])){
								judul($_REQUEST['id_class'],$_REQUEST['id_student']);
							}
						echo "</div>";
					echo "</div>";
					echo "<br/>";
					// untuk menampilkan input
					echo "<div class = 'judul'>";
						echo "<form name = \"myKet\" action = \"Student.php\" method = \"POST\">";
							echo "<input class = \"ket\" type = \"text\" name = \"keterangan\" placeholder = \"Input My Note.......\">";
						echo "</form>";
					echo "</div>";
					// untuk menampilkan file yang telah di upload
					echo "<div class = 'judul'>";
						echo "<h3> POST FILE </h3>";
						function file_teacher($file){
							require '../Connection/connection.php';
							$statement = $dbc->prepare("SELECT f.NAMA_FILE, f.KETERANGAN, u.NAMA_DEPAN, u.NAMA_BELAKANG, c.NAMA_CLASS FROM file_teacher f, user u, class c WHERE f.ID_CLASS = '$file' and f.ID_CLASS = c.ID_CLASS and c.USERNAME = u.USERNAME ");
							$statement->bindValue(':NAMA_FILE',':NAMA_FILE');
							$statement->bindValue(':KETERANGAN',':KETERANGAN');
							$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
							$statement->bindValue(':NAMA_DEPAN',':NAMA_DEPAN');
							$statement->bindValue(':NAMA_BELAKANG',':NAMA_BELAKANG');
							$statement->execute();
							foreach($statement as $a){
								echo "<p><img class = 'gambar1' src = '../gambar_PAW/profil.png' alt = 'profil'/>"; 
									echo ' '; 
									echo $a['NAMA_DEPAN']; 
									echo ' '; 
									echo $a['NAMA_BELAKANG']; 
									echo " send to ";						
									echo $a['NAMA_CLASS'];
								echo "</p>";
								echo "<div>";
									echo $a['KETERANGAN'];
								echo "</div>";
								echo "<p>";
									echo "<img class = 'gambar1' src = '../gambar_PAW/class.png' alt = 'class'/>";
									echo ' '; 
									echo $a['NAMA_FILE'];
								echo "</p>";
								echo "<p class = 'garis'></p>";
							}
						}
						if(isset($_REQUEST['class'])){
							file_teacher($_REQUEST['class']);					
						}
						if(isset($_REQUEST['id_class'])){
							file_teacher($_REQUEST['id_class']);
						}
					echo "</div>";
				}
			?>
		</div>
		<!-- untuk membuat footer -->
		<div class = "footer">
			<!-- untuk membuat border di dalam footer -->
			<div class = "ukuran"> Copyright @ Yourwebsite | powered by English Course </div>
		</div> 
	</body>
</html>