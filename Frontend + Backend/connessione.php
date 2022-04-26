<?php

    $host = "172.17.0.1:3306";
    $user = "root";
    $pass = "root";
    $db = "mydb";

    $connessione = mysqli_connect($host,$user,$pass,$db) or die ("<br>Errore di connessione" . mysqli_error($connessione) . " ". mysqli_errno($connessione));  
    
?>
