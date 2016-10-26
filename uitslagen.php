<!DOCTYPE html>

<?php
  //echo "jan is gek";
  $showdatum = array_key_exists('showdatum', $_GET) ? $_GET['showdatum'] : '';
  htmlspecialchars($showdatum);
  $showlines = array_key_exists('showlines', $_GET) ? $_GET['showlines'] : '';
  htmlspecialchars($showlines);
  //echo "<p>".$showdatum."</p>";

  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);
  date_default_timezone_set("Europe/Amsterdam");
  $amsterdam_time = date("H:i");
  $datum = (date('Y-m-d'));
  //$difference = 90; //minuten van te voren en erna
  $maxlines = 10;
  if ($showdatum !== '') {
     $datum = $showdatum;
  }

  if ($showlines !== '') {
      $maxlines = $showlines;
  }

  $sql =  "SELECT uitslag,thuisteam,uitteam
           FROM uitslag
           WHERE datum='$datum'";
           //ORDER by aanvang";
  $STH = $db->prepare($sql);
  $STH->execute();
  $array_uitslagen = [];
  $i = 0;
  while( $row = $STH->fetch(PDO::FETCH_ASSOC))  {
    $thuis_wo_colon = str_replace(":","-",$row["thuisteam"]);
    $uit_wo_colon = str_replace(":","-",$row["uitteam"]);
    $array_uitslagen[] = array($thuis_wo_colon,$uit_wo_colon,$row["uitslag"]);
    //echo "<tr><td>".$row["aanvang"]."</td><td>".$row["thuisteam"]."</td><td>".$row["thuiskk"]."</td><td>".$row["uitteam"]."</td><td>".$row["uitkk"]."</td><td>".$row["veld"]."</td>"."</tr>";
    //echo $row["thuisteam"].$row["uitteam"].$row["uitslag"]."\n";
    //echo "<tr><td>".$row["thuisteam"]."</td><td>".$row["uitteam"]."</td><td>".$row["uitslag"]."</td>"."</tr>";
  }
  $db = null;
?>

<html>
  <head>
        <script type="text/javascript">
        var max_lines = <?php echo $maxlines?>;
        var js_array = <?php echo json_encode($array_uitslagen); ?>;
        var nr_pages = Math.ceil(js_array.length / max_lines);
        var actual_page = 0;

        function timedEvent() {
           var t1=setInterval("showUitslagen();",7000);
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

        function showUitslagen() {
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


            //if (nr_pages > 1) {

            //}
          }
          else {
            tabel_html = "<p>Geen uitslagen op dit moment.</p>"
          }
          document.getElementById("part_wedstrijden").innerHTML = tabel_html;
          if (nr_pages > 1) {
            document.getElementById("pagnr").innerHTML = actual_page+"/"+nr_pages;

          }
        }


        function timedRefresh(timeoutPeriod) {
	         setTimeout("location.reload(true);",timeoutPeriod);
        }

        //showWedstrijden();
        window.onload = timedRefresh(15*60*1000);
        timedEvent();
        </script>
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
          padding-top: 0%;
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
          height:80%;
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
          color: #aaffaa;
	  vertical-align: text-bottom;
    }
    .footer {
        font-size:230%;
        font-family: Tahoma,Arial Narrow,Arial,sans-serif;

    }
    .alignleft {
        float: left;
        text-align:left;
        width:33.33%;
    }
    .aligncenter {
        float: left;
        text-align:center;;
        width:33.33%;
    }

    .alignright {
        float: left;
        text-align:center;
        width:33.33%;

    }
</style>
</head>
<body>

    <div class="wedstrijden">
      <table>
        <div align="center" id="pagnr" style="color:#aaffaa;font-family:Tahoma,Arial Narrow,Arial,sans-serif; font-size:180%;">&nbsp</div>
        <thead class="tablehead">
          <tr>
            <th>Thuisteam</th>
            <th>Uitteam</th>
	          <th>Uitslag</th>
          </tr>
        </thead>

        <tbody id="part_wedstrijden">
        <tr><td></td></tr>

        </tbody>

        <script type="text/javascript">
          showUitslagen();
        </script>
      </table>
        <div class="footer">
          <div class="alignleft">&nbsp</div>
          <div class="aligncenter" style="color:#ffff00;font-size:80%;">Gemaakt door <b>Veldt.IT</b></div>
          <div class="alignright" ">&nbsp</div>
          <div style="clear: both;"></div>
        </div>

      <!--p id="pagnr" align="center" style="font-size:230%;font-family: Tahoma,Arial Narrow,Arial,sans-serif;"></p-->
     </div>


</body>
</html>
