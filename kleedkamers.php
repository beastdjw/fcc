<!DOCTYPE html>
<?php
  //echo 'jan is gek';
  $showdatum = array_key_exists('showdatum', $_GET) ? $_GET['showdatum'] : '';
  htmlspecialchars($showdatum);
  $showtime = array_key_exists('showtime', $_GET) ? $_GET['showtime'] : '';
  htmlspecialchars($showtime);
  $showdifference = array_key_exists('showdifference', $_GET) ? $_GET['showdifference'] : '';
  htmlspecialchars($showdifference);
  $showlines = array_key_exists('showlines', $_GET) ? $_GET['showlines'] : '';
  htmlspecialchars($showlines);
  //echo "<p>".$showdatum."</p>";

  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);
  date_default_timezone_set("Europe/Amsterdam");
  $amsterdam_time = date("H:i");
  $datum = (date('Y-m-d'));
  $difference = 90; //minuten van te voren en erna
  $maxlines = 10;
  if ($showdatum !== '') {
     $datum = $showdatum;
  }
  if ($showtime !== '') {
      $amsterdam_time = $showtime;
  }
  if ($showdifference !== '') {
      $difference = $showdifference;
  }
  if ($showlines !== '') {
      $maxlines = $showlines;
  }
  #echo "<p>".$datum." ".$amsterdam_time." ".$difference." ".$maxlines."</p>";

  #$amsterdam_time = "10:00"; #for debugging purposes
  #$datum = "2016-09-03"; #for debugging purposes

  $low_time = date("H:i",(strtotime($amsterdam_time) - ($difference*60))); #anderhalve uur van te voren laten zien
  $high_time = date("H:i",(strtotime($amsterdam_time) + ($difference*60))); #tot anderhalve uur na het begin van de wedstrijd

  $sql =  "SELECT aanvang,thuisteam,thuiskk,uitteam,uitkk,veld
           FROM wedstrijden
           WHERE datum='$datum' AND lokatie='thuis' AND aanvang>='$low_time' AND aanvang<='$high_time'
           ORDER by aanvang";
  $STH = $db->prepare($sql);
  $STH->execute();
  $array_wedstrijden = [];
  $i = 0;
  while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
    $array_wedstrijden[] = array($row["aanvang"],$row["thuisteam"],$row["thuiskk"],$row["uitteam"],$row["uitkk"],$row["veld"]);
    //echo "<tr><td>".$row["aanvang"]."</td><td>".$row["thuisteam"]."</td><td>".$row["thuiskk"]."</td><td>".$row["uitteam"]."</td><td>".$row["uitkk"]."</td><td>".$row["veld"]."</td>"."</tr>";
  }

  $db = null;
?>

<html>
  <head>
        <script type="text/javascript">
           </script>
        var max_lines = <?php echo $maxlines?>;
        var js_array = <?php echo json_encode($array_wedstrijden); ?>;
        var nr_pages = Math.ceil(js_array.length / max_lines);
        var actual_page = 0;
        //document.write(nr_pages); test
        //for(var i=0;i<js_array.length;i++) {
        //document.write(js_array[i]);
      //}
        function timedEvent() {
           var t1=setInterval("showWedstrijden();",7000);
        }

        function makehtml_ofarray(inputarray) {
          var html = '';
          var datafield;
          for(var i=0;i<inputarray.length;i++) {
            html = html + "<tr>";
            for (var j=0;j<inputarray[i].length;j++) {
                  //window.alert (inputarray[i][j]);
                datafield = inputarray[i][j];
                if (datafield == null) {
                  datafield = "";
                  console.log(datafield);
                }
                html = html + "<td>" + datafield + "</td>";
            }
            html = html + "</tr>"
            //document.write(js_array[i]);
          }

          return html;

        }

        function showWedstrijden() {
           //var tabel_html = for(var i=0;i<js_array.length;i++) {document.write(js_array[i]); }
          var tabel_html ="";
          var max_row_nr;

          if (js_array.length > 0) {
            if (actual_page == nr_pages) {
              actual_page = 1;
            }
            else {
               actual_page = actual_page + 1;
            }
            var min_row_nr = (actual_page-1)*max_lines;
            if (actual_page == nr_pages) {
               max_row_nr = js_array.length;
            }
            else max_row_nr = (max_lines * actual_page);

            var part_array = js_array.slice(min_row_nr,max_row_nr);

            tabel_html = makehtml_ofarray(part_array);
            //tabel_html = tabel_html + "<tr style=\"border-style: none;\"><td style=\"border-style: none;\"></td><td></td><td>"+actual_page+"/"+nr_pages+"</td><td></td><td></td><td></td></tr>";

            if (nr_pages > 1) {
              tabel_html = tabel_html + "<tr><td style=\"border-style: none;\"</td><td style=\"border-style: none;\"</td><td style=\"border-style: none;\">"+actual_page+"/"+nr_pages+"</td></tr>";
            }
          }
          else {
            tabel_html = "<p>Geen wedstrijden op dit moment.</p>"
          }
          document.getElementById("part_wedstrijden").innerHTML = tabel_html;
           //alert("Hello! I am an alert box!");
           //for(var i=0;i<js_array.length;i++) {document.write(js_array[i]); }
           //table_content.refresh();
        }


        function timedRefresh(timeoutPeriod) {
	         setTimeout("location.reload(true);",timeoutPeriod);
        }

        //showWedstrijden();
        window.onload = timedRefresh(15*60*1000);
        timedEvent();
        </script>
>>>>>>> fc23f68675437a29c8fd1620cbd9cc74b000f698
    <style>
       body {
          background-image: url("grass.jpg");
          background-repeat: no-repeat;
          background-position: right top;
          background-attachment: fixed;
          color: white;
       }

       table {
          width:100%;
          padding: 1.5%;
          font-family: Tahoma,Arial Narrow,Arial,sans-serif;
        	font-size: 1.7vw;
        	font-style: normal;
        	font-variant: normal;
        	font-weight: 400;
        	line-height: 30.8px;

       }
       th {
          padding: 1.2%;
          color:#006400;
          background-color: rgba(102,255,0, 0.9);
       }
       td {
          padding: 1.2%;
          color:#aaffaa;
          border-bottom: solid;
       }

       .wedstrijden {
          background-color: rgba(0,50,0, 0.6);
	  //width:65%;
<<<<<<< HEAD
          height:800px;
=======
          height:80%;
>>>>>>> fc23f68675437a29c8fd1620cbd9cc74b000f698
          //float:left;
       }

       .fcc-info {
	  margin-left: 20px;
          padding-left: 10px;
          padding-right: 10px;
          background-color: rgba(0,50,0, 0.6);
          border: 2px solid white;
          border-radius:10px;
          width:25%;
          height:350px;
          float:left;
          color: #0;
          font-family: Tahoma,Arial Narrow,Arial,sans-serif;
        font-size: 1.7vw;
        font-style: normal;
        font-variant: normal;
        font-weight: 400;
        line-height: 30px;

       }
       tr {
          text-align: center;
       }
       #text-bottom {
<<<<<<< HEAD
          color: #006400;
	  vertical-align: text-bottom;       
=======
          color: #aaffaa;
	  vertical-align: text-bottom;
>>>>>>> fc23f68675437a29c8fd1620cbd9cc74b000f698
}
</style>
</head>
<body>

    <div class="wedstrijden">
      <table>
        <thead class="tablehead">
          <tr>
            <th>Tijdstip</th>
            <th>Thuisteam</th>
	    <th>KK</th>
            <th>Uitteam</th>
            <th>KK</th>
            <th>Veld</th>
          </tr>
        </thead>

<<<<<<< HEAD
        <tbody>
          <?php
          $datum = (date('Y-m-d'));
          $datum = "2016-09-03";
 	  $sql =  "SELECT aanvang,thuisteam,thuiskk,uitteam,uitkk,veld 
                   FROM wedstrijden 
           	   WHERE datum='$datum' 
                   ORDER by aanvang";
    	  $STH = $db->prepare($sql);
    	  $STH->execute();
    	  while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
        		echo "<tr><td>".$row["aanvang"]."</td><td>".$row["thuisteam"]."</td><td>".$row["thuiskk"]."</td><td>".$row["uitteam"]."</td><td>".$row["uitkk"]."</td><td>".$row["veld"]."</td>"."</tr>";
    	  }		

    	  $db = null;
    	  ?>
=======
        <tbody id="part_wedstrijden">
        <tr><td></td></tr>

>>>>>>> fc23f68675437a29c8fd1620cbd9cc74b000f698
        </tbody>
        <script type="text/javascript">
          showWedstrijden();
        </script>
      </table>
      <p id="text-bottom">KK = Kleedkamer</p>
   </div>


</body>
</html>
