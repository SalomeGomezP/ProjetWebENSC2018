<?php
try
{ $BDD = new PDO(
"mysql:host=localhost;dbname=bddprojet;charset=utf8", "testutilisateur","test",
array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
; // connexion serveur de BD MySql
}
catch (Exception $e) {
die('Erreur fatale : ' . $e->getMessage());
}
?> 