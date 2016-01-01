CREATE TABLE `beker` (
 `nr` TEXT,
 `team` TEXT,
 `gespeeld` TEXT,
 `gewonnen` TEXT,
 `gelijk` TEXT,
 `verloren` TEXT,
 `punten` TEXT,
 `voor` TEXT,
 `tegen` TEXT,
 `verschil` TEXT,
 `penaltypunten` TEXT,
 `fccteam_id` INTEGER
);
CREATE TABLE `competitie` (
 `nr` TEXT,
 `team` TEXT,
 `gespeeld` TEXT,
 `gewonnen` TEXT,
 `gelijk` TEXT,
 `verloren` TEXT,
 `punten` TEXT,
 `voor` TEXT,
 `tegen` TEXT,
 `verschil` TEXT,
 `penaltypunten` TEXT,
 `fccteam_id` INTEGER
);
CREATE TABLE `programma` (
 `datum` TEXT,
 `klasse` TEXT,
 `thuis` TEXT,
 `uit` TEXT,
 `scheidsrechter` TEXT,
 `aanwezig` TEXT,
 `aanvang` TEXT,
 `fccteam_id` INTEGER
);
CREATE TABLE `uitslag` (
 `datum` TEXT,
 `wedstrijd` TEXT,
 `uitslag` TEXT,
 `fccteam_id` INTEGER
);
CREATE TABLE `team` (
 `id` INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE ,
 `naam` TEXT UNIQUE
);
