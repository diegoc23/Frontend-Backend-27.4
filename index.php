<?php
    $_REQUEST = $_SERVER["REQUEST_METHOD"];

    switch($_REQUEST)
    {
        case "GET";
        break;
            
        case "POST";
        break;
            
        case "PUT"
        break;
            
        default:
        echo: "Errore.";
        break;
?>
