<!DOCTYPE html>
<html>
<head>
    <title>web01</title>
</head>
<body>
    <h1>selamat belajar PHP</h1>
    <?php
    $_nama = "Nurul Fikri Alamsyah";
    $_umur = 20;
    $_prodi = "Teknik Informatika"
    $_ipk = 3.5;
?>
<p>Nama : <?php echo $_nama; ?></p>
<p>Umur : <?=$_umur?></p>
<p>prodi : <?php echo $_prodi; ?></p>
<p>ipk : <?=$_ipk?></P>

<hr>


</body>
</html>