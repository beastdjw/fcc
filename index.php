<!DOCTYPE html>
<?php

  //$team = strtoupper(htmlspecialchars($_GET["team"])); #hotfix!!!!
  //$team = htmlspecialchars($_GET["team"]);
  //if (!empty (htmlspecialchars($_GET["team"]))
  //   $team = htmlspecialchars($_GET["team"])
  $team = array_key_exists('team', $_GET) ? $_GET['team'] : '';
  htmlspecialchars($team);
  $cookie_name = "fccastricum_team";
  //for testing
  //$team = "2 (zat)";
  //echo 'Hello World',$team;

//$db = new SQLite3('/var/lib/fcc/fcc.sqlite');
  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);



  if ($team) {
    //$sql = "SELECT DISTINCT fccteam from competitie";
    //$sql = "SELECT naam from teams";
    $STH = $db->prepare("SELECT naam from teams");
    $STH->execute();
    //$result = $db->query($sql);
    //if (!$result) die("Cannot execute query.");
    $gevonden= False; //initialisatie
    while(( $row = $STH->fetch(PDO::FETCH_ASSOC)) AND !($gevonden))  {
    //while (($row = $result->fetchArray()) AND !($gevonden)) {
      //echo $row["naam"].'<br>';
      if ($row["naam"]==$team)
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
  <title>FC Castricum <?php echo $team; ?></title>
  <meta charset="utf-8">
  <meta name="description" content="Voor FC Castricum staan hier alle teams het programma, de competitiestand, de bekerstand en de uitslagen">
  <meta name="keywords" content="FC Castricum,FC,Castricum,voetbal,Castricum,FC-Castricum,wedstrijd,beker,competitie,programma,uitslag,uitslagen,uitwedstrijd,thuiswedstrijd">
  <meta name="author" content="Veldt IT">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


  <script>
    $(function() {
      $(".dropdown-menu > li > a.trigger").on("click", function(e) {
        var current = $(this).next();
        var grandparent = $(this).parent().parent();
        if ($(this).hasClass('left-caret') || $(this).hasClass('right-caret'))
          $(this).toggleClass('right-caret left-caret');
        grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
        grandparent.find(".sub-menu:visible").not(current).hide();
        current.toggle();
        e.stopPropagation();
      });
      $(".dropdown-menu > li > a:not(.trigger)").on("click", function() {
        var root = $(this).closest('.dropdown');
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

    .dropdown-menu>li {
      position: relative;
      -webkit-user-select: none;
      /* Chrome/Safari */
      -moz-user-select: none;
      /* Firefox */
      -ms-user-select: none;
      /* IE10+ */
      /* Rules below not implemented in browsers yet */
      -o-user-select: none;
      user-select: none;
      cursor: pointer;
      /*background-color: #000;*/
      /*color: #000;*/
    }

    .dropdown-menu>li>a:hover,
    .dropdown-menu>li>a:focus {
      color: #00ffff;
      background-color: #0c6396;
    }

    .dropdown-menu .sub-menu {
      left: 100%;
      position: absolute;
      top: 0;
      display: none;
      margin-top: -1px;
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      border-left-color: #1e88e5;
      box-shadow: none;
      background-color: #1e88e5;
      color: #0240a0;
    }

    .right-caret:after {
      content: "";
      border-bottom: 4px solid transparent;
      border-top: 4px solid transparent;
      border-left: 4px solid orange;
      display: inline-block;
      height: 0;
      opacity: 0.8;
      vertical-align: middle;
      width: 0;
      margin-left: 5px;
    }

    .left-caret:after {
      content: "";
      border-bottom: 4px solid transparent;
      border-top: 4px solid transparent;
      border-right: 4px solid orange;
      display: inline-block;
      height: 0;
      opacity: 0.8;
      vertical-align: middle;
      width: 0;
      margin-left: 5px;
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

    #footer {
      color: yellow;
    }
  </style>
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container"> <!--was container-fluid-->
      <div class="navbar-header">
        <a href="#" id="dLabel" class="btn btn-primary dropdown-toggle bg-lightblue" data-toggle="dropdown">
          <?php echo $team; ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a class="trigger right-caret">Senioren</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=1 (zat)">1 (zat)</a></li>
              <li><a href="?team=2 (zon)">2 (zon)</a></li>
              <li><a href="?team=2 (zat)">2 (zat)</a></li>
              <li><a href="?team=3 (zat)">3 (zat)</a></li>
              <li><a href="?team=4 (zat)">4 (zat)</a></li>
              <li><a href="?team=5 (zat)">5 (zat)</a></li>
            </ul>
            <a class="trigger right-caret">Dames en Meisjes</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=VR1 (zat)">VR1 (zat)</a></li>
              <li><a href="?team=MO17:1">MO17-1</a></li>
              <li><a href="?team=MO13:1">MO13-1</a></li>
            </ul>
            <a class="trigger right-caret">JO19</a>
	    <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO19:1">JO19-1</a></li>
              <li><a href="?team=JO19:2">JO19-2</a></li>
              <li><a href="?team=JO19:3">JO19-3</a></li>
            </ul>
            <a class="trigger right-caret">JO17</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO17:1">JO17-1</a></li>
              <li><a href="?team=JO17:2">JO17-2</a></li>
              <li><a href="?team=JO17:3">JO17-3</a></li>
              <li><a href="?team=JO17:4">JO17-4</a></li>
            </ul>
            <a class="trigger right-caret">JO15</a>
            <ul class="dropdown-menu sub-menu">
	      <li><a href="?team=JO15:1">JO15-1</a></li>
              <li><a href="?team=JO15:2">JO15-2</a></li>
              <li><a href="?team=JO15:3">JO15-3</a></li>
              <li><a href="?team=JO15:4">JO15-4</a></li>
              <li><a href="?team=JO15:5">JO15-5</a></li>
            </ul>
            <a class="trigger right-caret">JO13</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO13:1">JO13-1</a></li>
              <li><a href="?team=JO13:2">JO13-2</a></li>
              <li><a href="?team=JO13:3">JO13-3</a></li>
              <li><a href="?team=JO13:4">JO13-4</a></li>
              <li><a href="?team=JO13:5">JO13-5</a></li>
            </ul>
            <a class="trigger right-caret">JO12</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO12:1">JO12-1</a></li>
            </ul>
            <a class="trigger right-caret">JO11</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO11:1">JO11-1</a></li>
              <li><a href="?team=JO11:2">JO11-2</a></li>
              <li><a href="?team=JO11:3">JO11-3</a></li>
              <li><a href="?team=JO11:4">JO11-4</a></li>
              <li><a href="?team=JO11:5">JO11-5</a></li>
              <li><a href="?team=JO11:6">JO11-6</a></li>
              <li><a href="?team=JO11:7">JO11-7</a></li>
            </ul>
	    <a class="trigger right-caret">JO10</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO10:1">JO10-1</a></li>
            </ul>
            <a class="trigger right-caret">JO9</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO9:1">JO9-1</a></li>
              <li><a href="?team=JO9:2">JO9-2</a></li>
              <li><a href="?team=JO9:3">JO9-3</a></li>
              <li><a href="?team=JO9:4">JO9-4</a></li>
              <li><a href="?team=JO9:5">JO9-5</a></li>
              <li><a href="?team=JO9:6">JO9-6</a></li>
            </ul>
            <a class="trigger right-caret">JO8</a>
            <ul class="dropdown-menu sub-menu">
              <li><a href="?team=JO8:1">JO8-1</a></li>
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
            <a href="kleedkamers.php" class="hidden-xs">Veld/Kleedkamer</a>
            <a href="kleedkamers.php" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Veld/Kleedkamer</a>
          </li>

        <!--  <li>
            <a href="#div5" class="hidden-xs">Foto's</a>
            <a href="#div5" class="visible-xs" data-toggle="collapse" data-target=".navbar-collapse">Foto's</a>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>



  <div id="div0" class="container bg-lightblue">      <!--was container-fluid-->
    <center>
      <p>Uitslag laatste wedstrijd
        <br>
        <?php
          //echo $team;
          $sql = "SELECT datum,thuisteam,uitteam,uitslag,uitslag.soort
                  FROM uitslag JOIN teams
                  ON uitslag.knvb_id=teams.knvb_id
                  WHERE teams.naam='$team' LIMIT 1";

          $STH = $db->prepare($sql);
          $STH->execute();
    //      $result = $db->query($sql);
      //    if (!result) die("Cannot execute query.");
          //while ($row = $result->fetchArray()) {
          while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
            $date = date_create($row["datum"]);
            echo $row["thuisteam"]." - ".$row["uitteam"]."<br><font size=\"15\">".$row["uitslag"]."</font><br>".$row["soort"]."wedstrijd gespeeld op: ".date_format($date, 'j-n-Y');//
          }
        ?>
      </p>
    </center>
  </div>

  <div id="div1" class="container bg-blue"><!--was container-fluid-->
    <h1>Programma</h1>
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

    $sql = "SELECT datum,afgelast,aanwezig,aanvang,thuisteam,uitteam,wedstrijden.soort,scheidsrechter
            FROM wedstrijden JOIN teams
            ON wedstrijden.knvb_id=teams.knvb_id
            WHERE teams.naam='$team'";
    $STH = $db->prepare($sql);
    $STH->execute();
    //      $result = $db->query($sql);
      //    if (!result) die("Cannot execute query.");
          //while ($row = $result->fetchArray()) {
    while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
    //$result = $db->query($sql);
    //if (!result) die("Cannot execute query.");
    // output data of each row
    //while($row = $result->fetchArray()) {
      if ($row["afgelast"]=='ja')
        echo "<tr bgcolor=red><td>".$row["datum"]."</td><td>".$row["aanwezig"]."</td><td>".$row["aanvang"]."</td><td>".$row["thuisteam"]."</td><td>".$row["uitteam"]."</td><td><strong>"."AFGELAST"."</td></strong><td>"."<strong>AFGELAST"."</td>"."</strong></tr>";
      else
        echo "<tr><td>".$row["datum"]."</td><td>".$row["aanwezig"]."</td><td>".$row["aanvang"]."</td><td>".$row["thuisteam"]."</td><td>".$row["uitteam"]."</td><td>".$row["soort"]."</td><td>".$row["scheidsrechter"]."</td>"."</tr>";
    }

    ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="div2" class="container bg-lightblue"> <!--was container-fluid-->
    <h1>Competitie</h1>
    <div class="table-responsive">
      <table class="table small">
        <thead>
          <tr>
            <th>Nr</th>
            <th>Team</th>
            <th>G</th>
            <th>W</th>
            <th>GL</th>
            <th>V</th>
            <th>P</th>
            <th>DPV</th>
            <th>DPT</th>
            <th>S</th>
            <th>PM</th>
          </tr>
        </thead>

        <tbody>
          <?php
    $sql = "SELECT nr,team,punten,gespeeld,gewonnen,gelijk,verloren,voor,tegen,verschil,penaltypunten
            FROM competitie JOIN teams
            ON competitie.knvb_id=teams.knvb_id
            WHERE teams.naam='$team'";
      $STH = $db->prepare($sql);
      $STH->execute();
    while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {


    //$result = $db->query($sql);
    //if (!result) die("Cannot execute query.");

    //while($row = $result->fetchArray()) {
        echo "<tr><td>".$row["nr"].
             "</td><td>".$row["team"].
             "</td><td>".$row["gespeeld"].
             "</td><td>".$row["gewonnen"].
             "</td><td>".$row["gelijk"].
             "</td><td>".$row["verloren"].
             "</td><td>".$row["punten"].
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

  <div id="div3" class="container bg-blue"><!--was container-fluid-->
    <h1>Beker</h1>
    <div class="table-responsive">
      <table class="table small">
        <thead>
          <tr>
            <th>Nr</th>
            <th>Team</th>
            <th>G</th>
            <th>W</th>
            <th>GL</th>
            <th>V</th>
            <th>P</th>
            <th>DPV</th>
            <th>DPT</th>
            <th>S</th>
            <th>PM</th>
          </tr>
        </thead>

        <tbody>
          <?php

    $sql = "SELECT nr,team,punten,gespeeld,gewonnen,gelijk,verloren,voor,tegen,verschil,penaltypunten
            FROM beker JOIN teams
            ON beker.knvb_id=teams.knvb_id
            WHERE teams.naam='$team'";
    //$result = $db->query($sql);
    //if (!result) die("Cannot execute query.");

    //while($row = $result->fetchArray()) {
    $STH = $db->prepare($sql);
    $STH->execute();
    //      $result = $db->query($sql);
      //    if (!result) die("Cannot execute query.");
          //while ($row = $result->fetchArray()) {
    while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
          echo "<tr><td>".$row["nr"].
          "</td><td>".$row["team"].
          "</td><td>".$row["gespeeld"].
          "</td><td>".$row["gewonnen"].
          "</td><td>".$row["gelijk"].
          "</td><td>".$row["verloren"].
          "</td><td>".$row["punten"].
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


  <div id="div4" class="container bg-lightblue"> <!--was container-fluid-->
    <h1>Uitslagen</h1>
    <div class="table-responsive">
      <table class="table small">
        <thead>
          <tr>
            <th>Datum</th>
            <th>Thuisteam</th>
            <th>Uitteam</th>
            <th>Uitslag</th>
            <th>Klasse</th>
          </tr>
        </thead>

        <tbody>
          <?php

    $sql = "SELECT datum,thuisteam,uitteam,uitslag,uitslag.soort
            FROM uitslag JOIN teams
            ON uitslag.knvb_id=teams.knvb_id
            WHERE teams.naam='$team'";
    //$result = $db->query($sql);
    //if (!result) die("Cannot execute query.");

    //while($row = $result->fetchArray()) {
    $STH = $db->prepare($sql);
    $STH->execute();
    while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
        echo "<tr><td>".$row["datum"]."</td><td>".$row["thuisteam"]."</td><td>".$row["uitteam"]."</td><td>".$row["uitslag"]."</td><td>".$row["soort"]."</td>"."</tr>";
    }

    //$db->close();
    $db = null;
    ?>
        </tbody>
      </table>
    </div>
  </div>

  <style>
    footer .glyphicon {
      font-size: 20px;
      margin-bottom: 20px;
      color: #0240a0;
    }
  </style>

  <footer id="footer" class="container text-center"><!--was container-fluid-->
    <a href="#" title="To Top">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>Deze site is gemaakt door <b>veldt.it</b></p>
  </footer>

</body>
</div>

</html>
