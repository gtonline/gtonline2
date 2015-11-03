<?php
switch ($mysqltype) {
    case "mysql":
      try {
        $strConnection = 'mysql:host='.$mysqlserveur.';dbname='.$mysqlmaindb ; //Ligne 1
        $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); //Ligne 2
        $pdo = new PDO($strConnection, $mysqllogin, $mysqlpassword, $arrExtraParam); //Ligne 3; Instancie la connexion
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Ligne 4
      }
      catch(PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
        die($msg);
      }
      break;
    case "sqlite":
      try {
        $strConnection = 'sqlite:gtonline.sqlite'; //Ligne 1
        $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); //Ligne 2
        $pdo = new PDO($strConnection, $mysqllogin, $mysqlpassword, $arrExtraParam); //Ligne 3; Instancie la connexion
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Ligne 4
      }
      catch(PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
        die($msg);
      }
      break;
}
?>
