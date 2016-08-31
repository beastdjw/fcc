<!DOCTYPE html>
<?php
  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);
?>

<html>
  <head>
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
       table {
          width:100%;
         
          //padding: 30px;
       //   font-family: "Lucida Console", Verdana, Arial;
        //  font-size: 1.7em; /* 40px/16=2.5em */
          font-family: Tahoma,Arial Narrow,Arial,sans-serif;
	font-size: 33px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 30.8px;

       }
       th,td {
	  padding: 10px;
          color:#006400;
       }
       td {
          padding: 10px;
          color:#aaffaa;
       }

       .wedstrijden {
          background-color: rgba(0,50,0, 0.6);
	  width:80%;
          height:500px;
          float:left;
       }

       .fcc-info {
          background-color: rgba(230,230,230, 0.95);
          width:20%;
          height:500px;
          float:left;
          color: #
       }
       .tablehead {
          background-color: rgba(150,230,150, 0.9);
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

    <h1>Welkom bij FC Castricum</h1>
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
 	  $sql =  "SELECT aanvang,thuisteam,thuiskk,uitteam,uitkk,veld 
                   FROM wedstrijden 
           	   WHERE datum='2016-08-31' 
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
   </div>




</body>
</html>
