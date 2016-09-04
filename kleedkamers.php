<!DOCTYPE html>
<?php
  $db = new PDO('sqlite:/var/lib/fcc/fcc.sqlite');
  if (!$db) die ($error);
?>

<html>
  <head>
        <script type="text/javascript">
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
	  //width:65%;
          height:800px;
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
          color: #006400;
	  vertical-align: text-bottom;       
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
        </tbody>
      </table>
      <p id="text-bottom">KK = Kleedkamer</p>
   </div>


</body>
</html>
