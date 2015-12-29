CREATE TABLE `beker` (
 `nr` varchar(5) ,
 `team` varchar(40) ,
 `gespeeld` varchar(5) ,
 `gewonnen` varchar(5) ,
 `gelijk` varchar(5) ,
 `verloren` varchar(5) ,
 `punten` varchar(5) ,
 `voor` varchar(5),
 `tegen` varchar(5) ,
 `verschil` varchar(5) ,
 `penaltypunten` varchar(5),
 `fccteam_id` int(8) 
);
CREATE TABLE `competitie` (
 `nr` varchar(5),
 `team` varchar(40),
 `gespeeld` varchar(5),
 `gewonnen` varchar(5),
 `gelijk` varchar(5),
 `verloren` varchar(5),
 `punten` varchar(5),
 `voor` varchar(5),
 `tegen` varchar(5),
 `verschil` varchar(5),
 `penaltypunten` varchar(5),
 `fccteam_id` int(8)
);
CREATE TABLE `programma` (
 `datum` varchar(20) ,
 `klasse` varchar(50),
 `thuis` varchar(40),
 `uit` varchar(40),
 `scheidsrechter` varchar(40),
 `aanwezig` varchar(10),
 `aanvang` varchar(10),
 `fccteam_id` int(8)
);
CREATE TABLE `uitslag` (
 `datum` varchar(20),
 `wedstrijd` varchar(80),
 `uitslag` varchar(10),
 `fccteam_id` int(8)
);
CREATE TABLE `team` (
 `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
 `naam` varchar(7)
);

