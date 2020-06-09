<?php
	require '../Connection/connection.php';
	// kondisi ketika logout ditekan dan menutup session
	if(isset($_REQUEST['logout'])){
		session_start();
		unset($_SESSION['Teacher']);
		header('Location: ../Login/Login.php');
	}
	// kondisi ketika kelas ditekan
	if(isset($_REQUEST['class'])){ 
		$stu = $_REQUEST['class'];
	}
	// mengambil data dari file login.php
	if(isset($_REQUEST['id_teach'])){
		$teach = $_REQUEST['id_teach'];
	}
	// kondisi ketika gambar hapus ditekan dan menghapus murid
	if(isset($_REQUEST['id_stu'])){
		$idstu = $_REQUEST['id_stu'];
		$stu = $_REQUEST['id_class'];
		$statement = $dbc->prepare("DELETE FROM class_student WHERE ID_CLASS = '$stu' and USERNAME = '$idstu'");
		$statement->bindValue(':ID_CLASS',':ID_CLASS');
		$statement->bindValue(':USERNAME',':USERNAME');
		$statement->execute();
	}
	// kondisi ketika gambar hapus ditekan dan menghapus file
	if(isset($_REQUEST['idfile'])){
		$stu = $_REQUEST['idclass'];
		$idf = $_REQUEST['idfile'];
		$teach = $_REQUEST['id_teach'];	
		$statement = $dbc->prepare("DELETE FROM file_teacher WHERE ID_FILE = '$idf'");
		$statement->bindValue(':ID_FILE',':ID_FILE');
		$statement->execute();
	}
	// kondisi ketika edit ditekan
	if(isset($_REQUEST['edit'])){
		$stu = $_REQUEST['id_class'];
		$teach = $_REQUEST['id_teach'];	
	}
	// kondisi ketika tombol home ditekan
	if(isset($_REQUEST['profil'])){
		$teach = $_REQUEST['id_teach'];	
		$stu = null;
	}
	// kondisi ketika gambar hapus ditekan dan menghapus kelas beserta relasinya
	if(isset($_REQUEST['class_id'])){
		$stu = $_REQUEST['class_id'];
		$teach = $_REQUEST['id_teach'];	
		
		$statement1 = $dbc->prepare("DELETE FROM class_student WHERE ID_CLASS = '$stu'");
		$statement1->bindValue(':ID_CLASS',':ID_CLASS');
		$statement1->execute();
		
		$statement2 = $dbc->prepare("DELETE FROM file_teacher WHERE ID_CLASS = '$stu'");
		$statement2->bindValue(':ID_CLASS',':ID_CLASS');
		$statement2->execute();
		
		$statement = $dbc->prepare("DELETE FROM class WHERE ID_CLASS = '$stu' and USERNAME = '$teach'");
		$statement->bindValue(':ID_CLASS',':ID_CLASS');
		$statement->bindValue(':USERNAME',':USERNAME');
		$statement->execute();
	}
	// kondisi ketika tombol upload ditekan dan memasukkan data file
	if(isset($_POST['upload'])){
		$stu = $_POST['id_class'];
		$nama_gambar = $_FILES['nama_file']['name'];
		$statement = $dbc->prepare("INSERT INTO file_teacher (ID_CLASS, NAMA_FILE, KETERANGAN) VALUE (:id_class, :nama_file, :keterangan)");
		$statement->bindValue(':id_class',$stu);
		$statement->bindValue(':nama_file',$nama_gambar);
		$statement->bindValue(':keterangan',$_POST['keterangan']);
		$statement->execute();
	}
	// kondisi ketika tombol create class ditekan dan menambah kelas baru
	if(isset($_POST['submit'])){
		$a = $_POST['nama_class'];
		$statement = $dbc->prepare("INSERT INTO class (USERNAME, NAMA_CLASS, DETAIL_CLASS) VALUE (:username, :nama_class, :detail_class)");
		$statement->bindValue(':username',$_POST['id_teach']);
		$statement->bindValue(':nama_class',$_POST['nama_class']);
		$statement->bindValue(':detail_class',$_POST['detail_class']);
		$statement->execute();

		$statement = $dbc->prepare("SELECT ID_CLASS FROM class WHERE NAMA_CLASS = '$a'");
		$statement->bindValue(':ID_CLASS',':ID_CLASS');
		$statement->execute();
		foreach($statement as $b){
			$stu = $b['ID_CLASS'];
		}
	}
	// kondisi ketika tombol save ditekan dan mengupdate kelas 
	if(isset($_POST['save'])){
		$stu = $_POST['id_class'];
		$teach = $_POST['id_teach'];
		$statement = $dbc->prepare("UPDATE class SET NAMA_CLASS = :NAMA_CLASS, DETAIL_CLASS = :DETAIL_CLASS WHERE ID_CLASS = :ID_CLASS");
		$statement->bindValue(':ID_CLASS',$stu);
		$statement->bindValue(':NAMA_CLASS',$_POST['nama_class']);
		$statement->bindValue(':DETAIL_CLASS',$_POST['detail_class']);
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
				<a href = "Teacher.php?logout"> Logout </a>
			</div>
			<!-- untuk membuat tombol home -->
			<div class = "home">
				<img class = "gambar" src = "../gambar_PAW/home.png" alt = "home"/>
				<?php
					echo "<a href = \"Teacher.php?profil&id_teach=$teach\"> Home </a>";
				?>
			</div>
		</div>
		<!-- untuk membuat sidebar -->
		<div class = "sidebar"> 
			<!-- untuk membuat sidebox pertama di dalam sidebar -->
			<div class = "sidebox" > 
				<!-- untuk membuat box pertama di dalam sidebox -->
				<div class = "box">	
					<!-- untuk menampilkan logo -->
					<div class = 'info'><img class = 'gambar' src = '../gambar_PAW/profil.png' alt = 'profil'/>
					<!-- untuk menampilkan nama dan level dari pengguna elearning -->
					<?php						
						$statement = $dbc->prepare("SELECT u.USERNAME, u.NAMA_DEPAN, u.NAMA_BELAKANG, l.NAMA_LEVEL FROM user u, level l WHERE u.ID_LEVEL = l.ID_LEVEL and u.USERNAME = '$teach'");
						$statement->bindValue(':USERNAME',':USERNAME');
						$statement->bindValue(':NAMA_DEPAN',':NAMA_DEPAN');
						$statement->bindValue(':NAMA_BELAKANG',':NAMA_BELAKANG');
						$statement->bindValue(':NAMA_LEVEL',':NAMA_LEVEL');
						$statement->execute();
						foreach($statement as $a){
							echo ' ';
							echo $a['NAMA_DEPAN'];
							echo ' ';								
							echo $a['NAMA_BELAKANG'];
							echo "</div>"; 
							echo "<div class = 'level'>";
								echo $a['NAMA_LEVEL'];
							echo "</div>";
							echo "<p class = 'profil'>"; 
								echo "<a href = \"\"> My Profile </a>";
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
							$statement = $dbc->prepare("SELECT ID_CLASS, NAMA_CLASS FROM class WHERE USERNAME = '$teach'");
							$statement->bindValue(':ID_CLASS',':ID_CLASS');
							$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
							$statement->execute();
							foreach ($statement as $namaClass){
								echo "<li><a href = \"Teacher.php?class={$namaClass['ID_CLASS']}&id_teach=$teach\"> {$namaClass['NAMA_CLASS']} </a></li>";
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
					<!-- untuk menampilkan logo -->
					<div class = "info">
						<img class = "gambar" src = "../gambar_PAW/create.png" alt = "myclass"/> Create Class 
					</div>
					<!-- untuk membuat form yang digunakan untuk menambah kelas -->
					<form name = "CreateClass" action = "Teacher.php" method = "POST">
						<input type = "hidden" name = "id_teach" value = "<?php echo htmlspecialchars($teach) ?>">
						<label id = "nama_class"> Class Name : </label>
						<input type = "text" name = "nama_class" placeholder = "Input Class Name">
						<label id = "detail_class"> Class Detail : </label>
						<input type = "text" name = "detail_class" placeholder = "Input Class Detail">
						<input type = "submit" value = "Create" name = "submit">
					</form>
				</div>
				<p class = "garis"></p>
				<!-- untuk membuat box kedua di dalam sidebox -->
				<div class = "box1">
					<!-- untuk menampilkan logo -->
					<div class = "info"><img class = "gambar" src = "../gambar_PAW/student.png" alt = "student"/> List Student </div> 
						<ul>
							<!-- untuk menampilkan murid yang mengikuti kelas tersebut -->
							<?php
								if(isset($_REQUEST['id_teach'])){
									if(isset($_REQUEST['class']) || isset($_REQUEST['id_stu']) || isset($_REQUEST['idfile']) || isset($_POST['upload']) || isset($_POST['submit']) || isset($_REQUEST['edit']) || isset($_POST['save'])){
										$statement = $dbc->prepare("SELECT u.NAMA_DEPAN, u.NAMA_BELAKANG, s.USERNAME FROM class c, class_student s, user u WHERE c.USERNAME = '$teach' and s.ID_CLASS = c.ID_CLASS and s.ID_CLASS = '$stu' and s.USERNAME = u.USERNAME");
										$statement->bindValue(':NAMA_DEPAN',':NAMA_DEPAN');
										$statement->bindValue(':NAMA_BELAKANG',':NAMA_BELAKANG');
										$statement->bindValue(':USERNAME',':USERNAME');
										$statement->execute();
										foreach ($statement as $class){
											echo "<li><a href = \"\"> {$class['NAMA_DEPAN']} {$class['NAMA_BELAKANG']} </a><a href = \"Teacher.php?id_stu={$class['USERNAME']}&id_class=$stu&id_teach=$teach\" onClick = \"return confirm('Do you want to delete ???')\"><img class = 'stuhapus' src = '../gambar_PAW/hapus.png' alt = 'hapus'/></a></li>";
										}
									}
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
				if(isset($_REQUEST['edit'])){
					echo "<div class = 'judul'>";	
						echo "<div class = 'detail1'> EDIT CLASS </div>";
						$statement = $dbc->prepare("SELECT NAMA_CLASS, DETAIL_CLASS FROM class WHERE ID_CLASS = '$stu'");
						$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
						$statement->bindValue(':DETAIL_CLASS',':DETAIL_CLASS');
						$statement->execute();
						foreach($statement as $a){	
							echo "<form class = 'edit' name = \"Edit\" action = \"Teacher.php\" method = \"POST\">";
								echo "<input type = \"hidden\" name = \"id_class\" value = \"{$stu}\">";
								echo "<input type = \"hidden\" name = \"id_teach\" value = \"{$teach}\">";
								echo "<label id = \"nama_class\"> Class Name : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"nama_class\" value = \"{$a['NAMA_CLASS']}\"><br/>";
								echo "<label id = \"detail_class\"> Class Detail : </label><br/>";
								echo "<input class = 'edit' type = \"text\" name = \"detail_class\" value = \"{$a['DETAIL_CLASS']}\"><br/>";
								echo "<input class = 'edit1' type = \"submit\" value = \"Save\" name = \"save\"/>";
							echo "</form>";
						}
					echo "</div>";
				}
				// untuk menampilkan nama kelas dan detail kelas
				else if(isset($_REQUEST['id_teach'])){
					echo "<div class = 'judul'>";	
						echo "<div class = 'title'>";
							function judul($c){
								require '../Connection/connection.php';
								$statement = $dbc->prepare("SELECT c.ID_CLASS, c.NAMA_CLASS, c.DETAIL_CLASS FROM class c, user u WHERE c.ID_CLASS = '$c' and c.USERNAME = u.USERNAME");
								$statement->bindValue(':ID_CLASS',':ID_CLASS');
								$statement->bindValue(':NAMA_CLASS',':NAMA_CLASS');
								$statement->bindValue(':DETAIL_CLASS',':DETAIL_CLASS');
								$statement->execute();
								foreach($statement as $a){									
									echo $a['NAMA_CLASS'];	
									echo "<div class = 'detail'>";
										echo $a['DETAIL_CLASS'];
									echo "</div>";
									echo "<a class = 'edt' href = \"Teacher.php?edit&id_class={$a['ID_CLASS']}&id_teach={$_REQUEST['id_teach']}\"> EDIT </a>";
									echo "<a href = \"Teacher.php?class_id={$a['ID_CLASS']}&id_teach={$_REQUEST['id_teach']}\" onClick = \"return confirm('Do you want to delete ???')\"><img class = 'gambar1' src = '../gambar_PAW/hapus.png' alt = 'hapus'/></a>";
								}
							}
							if(isset($_REQUEST['class'])){
								judul($_REQUEST['class']);
							}
							if(isset($_POST['upload'])){
								judul($stu);
							}
							if(isset($_POST['submit'])){
								judul($stu);
							}
							if(isset($_REQUEST['id_stu'])){
								judul($_REQUEST['id_class']);
							}
							if(isset($_REQUEST['idfile'])){
								judul($_REQUEST['idclass']);
							}
							if(isset($_POST['save'])){
								judul($_POST['id_class']);
							}
						echo "</div>";
					echo "</div>";
					echo "<br/>";
			?>	
			<!-- untuk menampilkan form yang berisi tentang upload file -->
			<div class = "judul">
				<form name = "myUpload" action = "Teacher.php" method = "POST" enctype = "multipart/form-data">
					<input type = "hidden" name = "id_class" value = "<?php echo htmlspecialchars($stu) ?>">
					<input type = "hidden" name = "id_teach" value = "<?php echo htmlspecialchars($teach) ?>">
					<input class = "ket" type = "text" name = "keterangan" placeholder = "Description File.......">
					<input class = "file" type = "file" name = "nama_file">
					<input class = "file" type = "submit" value = "Upload" name = "upload"/>
				</form>
			</div>
			<!-- untuk menampilkan file yang telah di upload -->
			<?php				
				echo "<div class = 'judul'>";
					echo "<h3> POST FILE </h3>";
					function file_teacher($file1){
						require '../Connection/connection.php';
						$statement = $dbc->prepare("SELECT f.ID_FILE, f.ID_CLASS, f.NAMA_FILE, f.KETERANGAN, c.NAMA_CLASS, u.NAMA_DEPAN, u.NAMA_BELAKANG FROM file_teacher f, user u, class c WHERE f.ID_CLASS = '$file1' and f.ID_CLASS = c.ID_CLASS and u.USERNAME = c.USERNAME");
						$statement->bindValue(':ID_FILE',':ID_FILE');
						$statement->bindValue(':ID_CLASS',':ID_CLASS');
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
								echo "<br/>";
								echo "<a href = \"Teacher.php?idfile={$a['ID_FILE']}&idclass={$a['ID_CLASS']}&id_teach={$_REQUEST['id_teach']}\" onClick = \"return confirm('Do you want to delete ???')\"><img class = 'hapus' src = '../gambar_PAW/hapus.png' alt = 'hapus'/></a>";
							echo "</p>";
							echo "<p class = 'garis'></p>";
						}
					}
					if(isset($_REQUEST['class'])){
						file_teacher($_REQUEST['class']);					
					}
					if(isset($_POST['upload'])){
						file_teacher($stu);
					}
					if(isset($_REQUEST['id_stu'])){
						file_teacher($_REQUEST['id_class']);
					}
					if(isset($_REQUEST['idfile'])){
						file_teacher($_REQUEST['idclass']);
					}
					if(isset($_POST['save'])){
						file_teacher($_POST['id_class']);
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