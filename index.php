<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orų informacija</title>
	<link href="images/ficon.png" rel="shortcut icon">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Custom styles for this template -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="css/styles.css">
  </head>
  <?php
  $servername = "sql301.epizy.com";
  $username = "epiz_21046348";
  $password = "puslapis";
  $dbname = "epiz_21046348_orai";

  $conn = new mysqli($servername, $username, $password, $dbname);
  
  function showInfo($miestas, $api_key) {	
	$jsonfile = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$miestas&units=metric&appid=$api_key");
	$jsondata = json_decode($jsonfile);
	
	$name = $jsondata->name;
	$country = $jsondata->sys->country;
	$temp = round($jsondata->main->temp,0);
	$clouds = round($jsondata->clouds->all,0);
	$humidity = round($jsondata->main->humidity,0);
	$pressure = round($jsondata->main->pressure,0);
	$wind = round($jsondata->wind->speed,0);
	$icon = $jsondata->weather[0]->icon;
	
	return '<h3><img src="http://openweathermap.org/img/w/' . $icon . '.png"> ' . $name . ' (' . $temp . ' °C)</h3><p><b>Šalis:</b> <img src="http://openweathermap.org/images/flags/' . strtolower($country) . '.png"></p><p><b>Debesuotumas:</b> ' . $clouds . ' %</p><p><b>Drėgnumas:</b> ' . $humidity . ' %</p><p><b>Slėgis:</b> ' . $pressure . ' hPa</p><p><b>Vėjas:</b> ' . $wind . ' m/s</p>';
  }
  ?>
  <body>
	<div class="container">
	  <div class="row">
	    <div class="col-xs-12">
		  <a href="index.php"><img src="images/oras.png" class="img-responsive" alt="logo"></a>
		  <h1>INFORMACIJA APIE ORUS</h1>
		  <hr>
		  <?php
		  if ($_GET['id'] == 200) {
		  ?>
		    <div class="alert alert-success" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			  </button>
			  <center>Naujas skirtukas sėkmingai pridėtas!</center>
		    </div>
		  <?php
		  }
		  else if ($_GET['id'] == 100) {
		  ?>
		  <div class="alert alert-danger" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			  </button>
			  <center>Neteisingas API raktas arba miesto pavadinimas!</center>
		    </div>
	      <?php
		  }
		  else if ($_GET['id'] == 95) {
		  ?>
		  <div class="alert alert-success" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			  </button>
			  <center>Visi skirtukai sėkmingai pašalinti!</center>
		    </div>
	      <?php
		  }
		  else if ($_GET['id'] == 90) {
		  ?>
		  <div class="alert alert-danger" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			  </button>
			  <center>Šis miestas jau egzistuoja!</center>
		    </div>
	      <?php
		  }
		  else if ($_GET['id'] == 85) {
		  ?>
		  <div class="alert alert-danger" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			  </button>
			  <center>Įvesties laukeliuose yra neleistinų simbolių!</center>
		    </div>
	      <?php
		  }
		  ?>
		  <form action="process.php" method="POST">
		    <div class="row">
	          <div class="col-xs-12 col-md-6">
				<div class="input-group">
				  <input type="text" class="form-control" name="api_key" id="api_key" placeholder="API" required>
				</div>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<div class="input-group">
				  <input type="text" class="form-control" name="miestas" placeholder="Miestas" required>
				  <span class="input-group-btn">
					<button class="btn btn-success" name="add_city" type="submit"><i class="fas fa-check"></i></button>
				  </span>
				</div>
			  </div>
			</div>
		  </form>
		  <div class="row">
		    <div class="col-xs-12 orai" id="orai">
			  <ul class="nav nav-tabs">
			    <li class="active" id="main"><a data-toggle="tab" href="#tab-info">Informacija</a></li>
				<?php
				$sql = "SELECT * FROM `duomenys` ORDER BY `miestas` ASC";
				$uzklausa = $conn->query($sql);
				if ($uzklausa->num_rows > 0) {
				  while($row = $uzklausa->fetch_assoc()) {
				    echo '<li><a data-toggle="tab" href="#tab-' . $row["miestas"] . '"><i class="far fa-compass"></i> ' . $row["miestas"] .'</a></li>';
				  }
				}
				?>
			  </ul>
			  <div class="tab-content">
			    <div id="tab-info" class="tab-pane fade in active">
				  <h3>Sveiki!</h3>
				  <p>Norėdami pridėti naują orų skirtuką įveskite API raktą, miesto pavadinimą ir spauskitę žalią mygtuką.</p>
				  <form action="process.php" method="POST">
					<button class="btn btn-primary" name="delete_all" type="submit"><i class="fas fa-trash-alt"></i> Pašalinti visus skirtukus</button>
				  </form>
			    </div>
				<?php
				$sql = "SELECT * FROM `duomenys`";
				$uzklausa = $conn->query($sql);
				if ($uzklausa->num_rows > 0) {
				  while($row = $uzklausa->fetch_assoc()) {
				    echo '<div id="tab-' . $row["miestas"] . '" class="tab-pane fade">' . showInfo($row["miestas"], $row["api_key"]) . '</div>';
				  }
				}
				?>
			  </div>
		    </div>
		  </div>
	    </div>
	  </div>
	</div>
	
	<?php
	mysqli_close($conn);
	?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
  </body>
</html>