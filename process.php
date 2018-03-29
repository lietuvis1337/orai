<?php
$servername = "sql301.epizy.com";
$username = "epiz_21046348";
$password = "puslapis";
$dbname = "epiz_21046348_orai";

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['add_city'])) {
  $api_key = $_POST['api_key'];
  $miestas = $_POST['miestas'];
  if ((preg_match('/^[A-Za-z0-9]+$/', $api_key)) && (preg_match('/^[A-Za-z0-9]+$/', $miestas))) {
    $sql = "SELECT * FROM `duomenys` WHERE `miestas`='$miestas'";
    $uzklausa = $conn->query($sql);
    if ($uzklausa->num_rows == 0) {
      $jsonfile = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$miestas&units=metric&appid=$api_key");
      $jsondata = json_decode($jsonfile);

      $cod = $jsondata->cod;

      if ($cod == 200) {
        $sql = "INSERT INTO `duomenys` (miestas, api_key) VALUES ('$miestas', '$api_key')";
        $conn->query($sql);
	    header("Location: index.php?id=200");
      } else {
	    header("Location: index.php?id=100");
      }
    } else {
      header("Location: index.php?id=90");
    }
  } else {
	header("Location: index.php?id=85");
  }
}

if (isset($_POST['delete_all'])) {
  $sql = "TRUNCATE TABLE duomenys";
  $conn->query($sql);
  header("Location: index.php?id=95");
}

mysqli_close($conn);
?>