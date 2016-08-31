<!DOCTYPE html>
<?php
  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);
?>

<html>
  <head>
        <script type="text/javascript">
        var messagenr = 1;
        function addZero(i) {
           if (i < 10) {
              i = "0" + i;
           }
           return i;
        }

        function timedMsg()
        {
           var t=setInterval("change_time();",1000);
           var t2=setInterval("changeText();",7000);
        }
        function change_time()
        {
           var d = new Date();
           var curr_hour = d.getHours();
           var curr_min = d.getMinutes();
           var curr_sec = d.getSeconds();
           //if(curr_hour > 12)
           //   curr_hour = curr_hour - 12;
           document.getElementById('Hour').innerHTML =curr_hour+':';
           document.getElementById('Minut').innerHTML=addZero(curr_min)+':';
           document.getElementById('Second').innerHTML=addZero(curr_sec);
        }
        function changeText()
        { 
           document.getElementById('hide1').style.display = 'none';
           document.getElementById('hide2').style.display = 'none';
	   document.getElementById('hide3').style.display = 'none';
 
           switch(messagenr) {
           	case 1:
                   document.getElementById('hide1').style.display = 'inline';
                   messagenr = 2;
                   break;
                case 2:
                   document.getElementById('hide2').style.display = 'inline';
                   messagenr = 3;
                   break;
                case 3:
                   document.getElementById('hide3').style.display = 'inline';
                   messagenr = 1;
                   break;
           }
        }   
        timedMsg();   
        changeText();
    </script>
    <style>
       body {
          background-image: url("grass.jpg");
          background-repeat: no-repeat;
          background-position: right top;
          background-attachment: fixed;
          color: white;
       }

       h1 {
             font-family: "Lucida Console", Verdana, Arial;
             font-size: 2em; /* 40px/16=2.5em */
             color: white;
             text-align:center;
	     opacity: 0.9;
          
       }
       h2 {
             font-family: "Lucida Console", Verdana, Arial;
             font-size: 1.5em; /* 40px/16=2.5em */
             color: white;
             text-align:center;
             opacity: 0.9;

       }

       table {
          width:100%;
         
          //padding: 30px;
       //   font-family: "Lucida Console", Verdana, Arial;
        //  font-size: 1.7em; /* 40px/16=2.5em */
          font-family: Tahoma,Arial Narrow,Arial,sans-serif;
	font-size: 1.7vw;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 30.8px;

       }
       th {
	  padding: 10px;
          color:#006400;
          background-color: rgba(102,255,0, 0.9);
       }
       td {
          padding: 10px;
          color:#aaffaa;
          border-bottom: solid;
       }

       .wedstrijden {
          background-color: rgba(0,50,0, 0.6);
	  width:65%;
          height:500px;
          float:left;
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
          color: #002200;
	  vertical-align: text-bottom;       
}
</style>
</head>
<body>

    <h1>Welkom bij FC Castricum      <span style="color:#ddffdd" id="Hour"></span><span style="color:#ddffdd" id="Minut"></span><span style="color:#ddffdd"id="Second"></span> </h1>
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

        <tbody>
          <?php
          $datum = (date('Y-m-d'));
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
        </tbody>
      </table>
      <p id="text-bottom">KK = Kleedkamer</p>
   </div>
   
   <div class="fcc-info">
      <div id="hide1">
         <H2>Kleedkamers</H2>
         <p>Onze vrijwilligers doen hun best om u een nette kleedkamer aan te bieden. Helpt u mee door uw afval in de prullenbak te gooien en de kleedkamer na gebruik even aan te vegen?</p>
      </div>
      <div id="hide2" style="display:none">
         <H2>Welkom!</H2>
         <p>Welkom op sportpark Noord End. Op dit scherm vindt u de veld- en kleedkamerindeling voor de wedstrijden van vandaag. Bezoekende clubs, meld u a.u.b. even bij het wedstrijdsecretariaat.</p>
      </div>
      <div id="hide3" style="display:none">
          <H2>Sponsoring</H2>
         <p>Met reclame maken bij FC Castricum bereikt u niet alleen een zeer grote groep Castricummers, u steunt er ook nog eens uw club mee! Ook sponsor worden bij FC Castricum? Neem contact op met de commissieleden.</p>
      </div>
   </div>


</body>
</html>
