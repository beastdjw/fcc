<!DOCTYPE html>
<?php
  //get team from the get variables (CONTROLEER DIT VANUIT DE DB, ANDERS SQL INJECTIE!!!!!)
  //$team = strtoupper(htmlspecialchars($_GET["team"]));
  $team = htmlspecialchars($_GET["team"]);
  $cookie_name = "fccastricum_team";

  //if (empty($team))
  //   echo "geen GET opties meegeven<br>";
  //else
  //   echo "get optie team is $team <br>";

  $db = new SQLite3('/home/dennis/db/fcc.sqlite');
  if (!db) die ($error);

//  if(!isset($_COOKIE[$cookie_name])) {
//    echo "Cookie named '" . $cookie_name . "' is not set!";
//  } else {
//    echo "Cookie '" . $cookie_name . "' is set!<br>";
//    echo "Value is: " . $_COOKIE[$cookie_name];
//  }

  if ($team) {
    $sql = "SELECT DISTINCT fccteam from competitie";
    $result = $db->query($sql);
    if (!result) die("Cannot execute query.");
    $gevonden= False; //initialisatie
    while (($row = $result->fetchArray()) AND !($gevonden)) {
      if ($row["fccteam"]==$team)
      $gevonden = True;
    }
    if ($gevonden) {
      $cookie_value = $team;
      //echo "cookie wordt gezet op: $cookie_value";
      setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/"); // 86400 = 1 day
    } else {
      $team='1 (zat)';
    }
  } else {
    if(isset($_COOKIE[$cookie_name])) {
      $team = $_COOKIE[$cookie_name];
    } else {
      $team='1 (zat)';
    }
  }

?>

<html>

<head>
  <title>FC Castricum</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


  <script>
  $(function(){
	$(".dropdown-menu > li > a.trigger").on("click",function(e){
		var current=$(this).next();
		var grandparent=$(this).parent().parent();
		if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
			$(this).toggleClass('right-caret left-caret');
		grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
		grandparent.find(".sub-menu:visible").not(current).hide();
		current.toggle();
		e.stopPropagation();
	});
	$(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
		var root=$(this).closest('.dropdown');
		root.find('.left-caret').toggleClass('right-caret left-caret');
		root.find('.sub-menu:visible').hide();
	});
});
  </script>


  <style>
    body {
      position: relative;
      background-color: #1e88e5;
    }

    .navbar {
      margin-bottom: 0;
      background-color: #1E88E5;
      z-index: 9999;
      border: 0;
      font-size: 12px !important;
      line-height: 1.42857143 !important;
      letter-spacing: 4px;
      border-radius: 0;
    }

    .navbar li a,
    .navbar .navbar-brand {
      color: #fff !important;
    }

    .navbar-nav li a:hover,
    .navbar-nav li.active a {
      color: #0240a0 !important;
      background-color: #fff !important;
    }

    .navbar-default .navbar-toggle {
      border-color: transparent;
      color: #fff !important;
    }

    .bg-grey {
      background-color: #f6f6f6;
    }

    .bg-blue {
      background-color: #0240a0;
    }

    .bg-lightblue {
      background-color: #1e88e5;
    }

    #div0 {
      padding-top: 30px;
      height: 170px;
      color: #fff;
    }

    #div1 {
      padding-top: 50px;
      /*height: 700px;*/
      color: #fff;
    }

    #div2 {
      padding-top: 50px;
      /*height: 700px;*/
      color: #fff;
    }

    #div3 {
      padding-top: 50px;
      /*height: 700px;*/
      color: #fff;
    }

    #div4 {
      padding-top: 50px;
      /*height: 700px;*/
      color: #fff;
    }

    #div5 {
      padding-top: 50px;
      /*height: 700px;*/
      color: #0240a0;
    }

    #section42 {
      padding-top: 50px;
      height: 700px;
      color: #fff;
      background-color: #009688;
    }

    .table-nonfluid {
      width: auto !important;
    }

    .dropdown-menu {
      background-color: #0240a0;
      color: #0240a0;

    }
    .dropdown-menu>li  {
      position:relative;
    	-webkit-user-select: none; /* Chrome/Safari */
    	-moz-user-select: none; /* Firefox */
    	-ms-user-select: none; /* IE10+ */
    	/* Rules below not implemented in browsers yet */
    	-o-user-select: none;
    	user-select: none;
    	cursor:pointer;
      /*background-color: #000;*/
      /*color: #000;*/
    }

    .dropdown-menu>li>a:hover, .dropdown-menu>li>a:focus {
          color: #00ffff;
          background-color: #0c6396;
        }

.dropdown-menu .sub-menu {
      left: 100%;
      position: absolute;
      top: 0;
      display:none;
      margin-top: -1px;
    	border-top-left-radius:0;
    	border-bottom-left-radius:0;
    	border-left-color:#1e88e5;
    	box-shadow:none;
      background-color: #1e88e5;
      color: #0240a0;
  }
.right-caret:after {
  	content:"";
    border-bottom: 4px solid transparent;
    border-top: 4px solid transparent;
    border-left: 4px solid orange;
    display: inline-block;
    height: 0;
    opacity: 0.8;
    vertical-align: middle;
    width: 0;
	margin-left:5px;
  }
.left-caret:after {
    content:"";
    border-bottom: 4px solid transparent;
    border-top: 4px solid transparent;
    border-right: 4px solid orange;
    display: inline-block;
    height: 0;
    opacity: 0.8;
    vertical-align: middle;
    width: 0;
	   margin-left:5px;
   }

    .navbar-brand {
      float: auto;
      padding-right: 2px;
    }

    #dLabel {
      margin-top: 12px;
      padding: 3px;
      border: 1px solid lightgray;
    }

    #footer{
      color: yellow;
    }

  </style>
</head>

  <body data-spy="scroll" data-target=".navbar" data-offset="50">
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">


            <!--a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-primary bg-lightblue" data-target="#" href="/watisdit.html">
                  <?php echo $team; ?> <span class="caret"></span>
            </a> -->
            <a href="#" id="dLabel" class="btn btn-primary dropdown-toggle bg-lightblue" data-toggle="dropdown"><?php echo $team; ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li>
                  <a class="trigger right-caret">Senioren</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a tabindex="-1" href="?team=1 (zat)">1 (zat)</a></li>
                    <li><a href="?team=2 (zon)">2 (zon)</a></li>
                    <li><a href="?team=2 (zat)">2 (zat)</a></li>
                    <li><a href="?team=3 (zon)">3 (zon)</a></li>
                    <li><a href="?team=3 (zat)">3 (zat)</a></li>
                    <li><a href="?team=4 (zat)">4 (zat)</a></li>
                    <li><a href="?team=5 (zat)">5 (zat)</a></li>
                    <li><a href="?team=6 (zat)">6 (zat)</a></li>
                    <li><a href="?team=7 (zat)">7 (zat)</a></li>
                  </ul>
                  <a class="trigger right-caret">Meisjes</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=MB1">MB1</a></li>
                    <li><a href="?team=MC1">MC1</a></li>
                    <li><a href="?team=MC2">MC2</a></li>
                    <li><a href="?team=MD1">MD1</a></li>
                    <li><a href="?team=ME1">ME1</a></li>
                  </ul>
                  <a class="trigger right-caret">A</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=A1 (zon)">A1 (zon)</a></li>
                    <li><a href="?team=A2 (zon)">A2 (zon)</a></li>
                    <li><a href="?team=A3 (zon)">A3 (zon)</a></li>
                  </ul>
                  <a class="trigger right-caret">B</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=B1">B1</a></li>
                    <li><a href="?team=B2">B2</a></li>
                    <li><a href="?team=B3">B3</a></li>
                    <li><a href="?team=B4">B4</a></li>
                  </ul>
                  <a class="trigger right-caret">C</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=C1">C1</a></li>
                    <li><a href="?team=C2">C2</a></li>
                    <li><a href="?team=C3">C3</a></li>
                    <li><a href="?team=C4">C4</a></li>
                    <li><a href="?team=C5">C5</a></li>
                  </ul>
                  <a class="trigger right-caret">D</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=D1">D1</a></li>
                    <li><a href="?team=D2">D2</a></li>
                    <li><a href="?team=D3">D3</a></li>
                    <li><a href="?team=D4">D4</a></li>
                    <li><a href="?team=D5">D5</a></li>
                    <li><a href="?team=O12 1">O12 1</a></li>
                  </ul>
                  <a class="trigger right-caret">E</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=E1">E1</a></li>
                    <li><a href="?team=E2">E2</a></li>
                    <li><a href="?team=E3">E3</a></li>
                    <li><a href="?team=E4">E4</a></li>
                    <li><a href="?team=E5">E5</a></li>
                    <li><a href="?team=E6">E6</a></li>
                    <li><a href="?team=E7">E7</a></li>
                    <li><a href="?team=E8">E8</a></li>
                    <li><a href="?team=E9">E9</a></li>
                    <li><a href="?team=E10">E10</a></li>
                    <li><a href="?team=O10 1">O10 1</a></li>
                  </ul>
                  <a class="trigger right-caret">F</a>
                  <ul class="dropdown-menu sub-menu">
                    <li><a href="?team=F1">F1</a></li>
                    <li><a href="?team=F2">F2</a></li>
                    <li><a href="?team=F3">F3</a></li>
                    <li><a href="?team=F4">F4</a></li>
                    <li><a href="?team=F7">F7</a></li>
                  </ul>
              </li>
            </ul>
          <a class="navbar-brand" href="#">FC Castricum</a>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
            <li class="active">
              <a href="#div1" class="hidden-xs">Programma</a>
              <a href="#div1" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Programma</a>
            </li>
            <li>
              <a href="#div2" class="hidden-xs">Competitie</a>
              <a href="#div2" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Competitie</a>
            </li>
            <li>
              <a href="#div3" class="hidden-xs">Beker</a>
              <a href="#div3" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Beker</a>
            </li>
            <li>
              <a href="#div4" class="hidden-xs">Uitslagen</a>
              <a href="#div4" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Uitslagen</a>
            </li>

            <li>
              <a href="#div5" class="hidden-xs">Foto's</a>
              <a href="#div5" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Foto's</a>
            </li>
          </ul>
      </div>
    </div>
    </nav>



    <div id="div0" class="container-fluid bg-lightblue">
      <center>
        <p>Uitslag laatste wedstrijd
          <br>
          <?php

          $sql = "SELECT datum,wedstrijd,uitslag,fccteam FROM uitslag where fccteam='$team' LIMIT 1";
          $result = $db->query($sql);
          if (!result) die("Cannot execute query.");

          while ($row = $result->fetchArray()) {
              echo $row["wedstrijd"]."<br><font size=\"15\">".$row["uitslag"]."</font><br>Gespeeld op: ".$row["datum"];
          }
          ?>
        </p>
      </center>
    </div>

    <div id="div1" class="container-fluid bg-blue">
      <h3>Programma</h3>
      <div class="table-responsive">
        <table class="table small">
          <thead>
            <tr>
              <th>Datum</th>
              <th>Aanwezig</th>
              <th>Aanvang</th>
              <th>Thuis</th>
              <th>Uit</th>
              <th>Klasse</th>
              <th>Scheidsrechter</th>
            </tr>
          </thead>

          <tbody>
            <?php

      $sql = "SELECT datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang FROM programma where fccteam='$team'";
      $result = $db->query($sql);
      if (!result) die("Cannot execute query.");

      // output data of each row
      while($row = $result->fetchArray()) {
        echo "<tr><td>".$row["datum"]."</td><td>".$row["aanwezig"]."</td><td>".$row["aanvang"]."</td><td>".$row["thuis"]."</td><td>".$row["uit"]."</td><td>".$row["klasse"]."</td><td>".$row["scheidsrechter"]."</td>"."</tr>";
      }

      ?>
          </tbody>
        </table>
      </div>
    </div>

    <div id="div2" class="container-fluid bg-lightblue">
      <h3>Competitie</h3>
      <div class="table-responsive">
        <table class="table small">
          <thead>
            <tr>
              <th>Nr</th>
              <th>Team</th>
              <th>P</th>
              <th>G</th>
              <th>W</th>
              <th>GL</th>
              <th>V</th>
              <th>DPV</th>
              <th>DPT</th>
              <th>S</th>
              <th>PM</th>
            </tr>
          </thead>

          <tbody>
            <?php
      $sql = "SELECT nr,team,punten,gespeeld,gewonnen,gelijk,verloren,voor,tegen,verschil,penaltypunten FROM competitie where fccteam='$team'";
      $result = $db->query($sql);
      if (!result) die("Cannot execute query.");

      while($row = $result->fetchArray()) {
          echo "<tr><td>".$row["nr"].
               "</td><td>".$row["team"].
               "</td><td>".$row["punten"].
               "</td><td>".$row["gespeeld"].
               "</td><td>".$row["gewonnen"].
               "</td><td>".$row["gelijk"].
               "</td><td>".$row["verloren"].
               "</td><td>".$row["voor"].
               "</td><td>".$row["tegen"].
               "</td><td>".$row["verschil"].
               "</td><td>".$row["penaltypunten"].
               "</td>"."</tr>";
      }
      ?>
          </tbody>
        </table>
      </div>

      <center>
        <!--<button type="button" id="more" class="btn"><span class="glyphicon glyphicon-chevron-down">
  Toggle</button>-->

        <p class="small">P=Punten G=Gespeeld aantal wedstrijden W=Gewonnen GL=Gelijk V=Verloren DPV=Doelpunten voor DPT=Doelpunten tegen S=Verschil in voor en tegendoelpunten PM=Penalty punten</p>
      </center>
    </div>

    <div id="div3" class="container-fluid bg-blue">
      <h3>Beker</h3>
      <div class="table-responsive">
        <table class="table small">
          <thead>
            <tr>
              <th>Nr</th>
              <th>Team</th>
              <th>P</th>
              <th>G</th>
              <th>W</th>
              <th>GL</th>
              <th>V</th>
              <th>DPV</th>
              <th>DPT</th>
              <th>S</th>
              <th>PM</th>
            </tr>
          </thead>

          <tbody>
            <?php

      $sql = "SELECT nr,team,punten,gespeeld,gewonnen,gelijk,verloren,voor,tegen,verschil,penaltypunten FROM beker WHERE fccteam='$team'";
      $result = $db->query($sql);
      if (!result) die("Cannot execute query.");

      while($row = $result->fetchArray()) {
            echo "<tr><td>".$row["nr"].
            "</td><td>".$row["team"].
            "</td><td>".$row["punten"].
            "</td><td>".$row["gespeeld"].
            "</td><td>".$row["gewonnen"].
            "</td><td>".$row["gelijk"].
            "</td><td>".$row["verloren"].
            "</td><td>".$row["voor"].
            "</td><td>".$row["tegen"].
            "</td><td>".$row["verschil"].
            "</td><td>".$row["penaltypunten"].
            "</td>"."</tr>";
      }

      ?>
          </tbody>
        </table>
      </div>

      <center>
        <p class="small">P=Punten G=Gespeeld aantal wedstrijden W=Gewonnen GL=Gelijk V=Verloren DPV=Doelpunten voor DPT=Doelpunten tegen S=Verschil in voor en tegendoelpunten PM=Penalty punten</p>
      </center>
    </div>


    <div id="div4" class="container-fluid bg-lightblue">
      <h3>Uitslagen</h3>
      <div class="table-responsive">
        <table class="table small">
          <thead>
            <tr>
              <th>Datum</th>
              <th>Wedstrijd</th>
              <th>Uitslag</th>
            </tr>
          </thead>

          <tbody>
            <?php

      $sql = "SELECT datum,wedstrijd,uitslag FROM uitslag WHERE fccteam='$team'";
      $result = $db->query($sql);
      if (!result) die("Cannot execute query.");

      while($row = $result->fetchArray()) {
          echo "<tr><td>".$row["datum"]."</td><td>".$row["wedstrijd"]."</td><td>".$row["uitslag"]."</td>"."</tr>";
      }

      $db->close();
      ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Container (Portfolio Section) -->
    <div id="div5" class="container-fluid text-left bg-grey hide">
      <h3>Foto's</h3>
      <br>
      <h4>De spelers</h4>
      <div class="row text-center">
        <div class="col-sm-4">
          <div class="thumbnail">
            <img src="O10-2015.jpg" alt="Paris" width="400" height="300">
            <p>
              <strong>E O-10</strong>
            </p>
            <p>Yes, we can!</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="thumbnail">
            <img src="az.jpg" alt="AZ" width="400" height="300">
            <p>
              <strong>AZ</strong>
            </p>
            <p>Mooi toernooi in Alkmaar</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="thumbnail">
            <img src="spannend.jpg" alt="Spannend" width="400" height="300">
            <p>
              <strong>Spannend!</strong>
            </p>
            <p>Gaat ie er in?</p>
          </div>
        </div>
      </div>
    </div>

    <style>
      footer .glyphicon {
        font-size: 20px;
        margin-bottom: 20px;
        color: #0240a0;
      }
    </style>

    <footer id="footer" class="container-fluid text-center">
      <a href="#" title="To Top">
        <span class="glyphicon glyphicon-chevron-up"></span>
      </a>
      <p>Deze site is gemaakt door <b>veldt.it</b></p>
    </footer>

  </body>
</div>

</html>
