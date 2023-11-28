<?php

function openConnection($dbname = 'supermercati'){

   $host = "localhost";
   $username = "root";
   $password = "";
   
   $connessione = mysqli_connect($host, $username, $password);

   if(false === $connessione){

      exit("Errore: impossibile stabilire una connessione " . mysqli_connect_error());

   }
   echo "Connesso: " . mysqli_get_host_info($connessione);
   return $connessione;
}

$connection = openConnection();

// Creazione del database
function createDB($connection, $dbname){
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    executeQuery($connection, $sql);
    mysqli_select_db($connection, $dbname);
    
    // Aggiungi questa riga per verificare se il database è stato selezionato correttamente
    if (mysqli_errno($connection)) {
       exit("Errore durante la selezione del database: " . mysqli_error($connection));
    }
 }
 
// Creazione della tabella
function createTable($connection, $tableName) {
    $sql = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT(11) NOT NULL AUTO_INCREMENT,
        titolo VARCHAR(255) NOT NULL, 
        sottotitolo VARCHAR(255) NOT NULL,
        corpo VARCHAR(255) NOT NULL,
        categoria VARCHAR(255) NOT NULL,
        calendario TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,  
        tempolett INT(11) NOT NULL,
        stato BOOLEAN NOT NULL,
        PRIMARY KEY (id)
    )";
    executeQuery($connection, $sql);
}

// Esecuzione delle query
function executeQuery($connection, $sql){
   if (false === mysqli_query($connection, $sql)) {
      echo "Errore: impossibile eseguire la query. " . mysqli_error($connection);
   }
}

// Creazione del database "blog" e della tabella "listaarticoli"
createDB($connection, 'blog');
createTable($connection, 'listaarticoli');

mysqli_close($connection);


    
function inserisciArticolo($connection, $titolo, $sottotitolo, $corpo, $categoria, $calendario, $tempolett, $stato, $close = true) {
    $sql = "INSERT INTO listaarticoli (  titolo, sottotitolo, corpo, categoria, calendario, tempolett, stato ) 
        VALUES( '$titolo', '$sottotitolo',  '$corpo', '$categoria', '$calendario', '$tempolett', '$stato');";
     return executeQuery($connection, $sql, $close);
    
    } 


    function selectQuery($connection, $sql, $close = true) {
        // Select the database
        mysqli_select_db($connection, 'blog');
    
        $result = mysqli_query($connection, $sql);
        if ($result === false) {
            exit("Errore: impossibile eseguire la query. " . mysqli_error($connection));
        }
    
        $rows = array();
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }
    
        mysqli_free_result($result);
    
        if ($close) {
            mysqli_close($connection);
        }
    
        return $rows;
    }
    
    

    function selectArticolo($connection, $close  = true) {
        $sql = "SELECT id, titolo, sottotitolo, corpo, categoria, calendario, tempolett, stato FROM listaarticoli;
       ";
        return $sql;
   }

?>