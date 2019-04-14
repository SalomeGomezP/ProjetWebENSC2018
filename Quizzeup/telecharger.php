<?php
$fp = fopen('campagne'.$_POST['id_exp'].'.csv', 'w');//On crée le fichier
require("Includes\connect.php"); 
include('Includes/fonctions.php');
$requete="SELECT * FROM  campagne WHERE id_exp=:id_exp";
$connexion = $BDD -> prepare($requete);
$connexion -> bindParam(':id_exp',$_POST['id_exp']);
$connexion -> execute();
$tuple=$connexion->fetch();
if ($tuple['type_campagne']=="comparative"){//dans le cas comparatif, on enregistre les réponses à chaque interface
    $fields=array("Interface1");
    fputcsv($fp, $fields);
    $requete="SELECT DISTINCT login, repondre.id_campagne, type_campagne  FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne AND classe=:classe";
    $connexion = $BDD -> prepare($requete);
    $classe="A";
    $connexion -> bindParam(':id_exp',$_POST['id_exp']);
    $connexion -> bindParam(':classe',$classe);
    $connexion -> execute();
    while($tuple2=$connexion->fetch()){//On parcourt tout les utilisateurs ayant répondu
        if(testEtatCampagneUtilisateur($tuple2['login'],  $tuple2['id_campagne'], $tuple2['type_campagne'],$_POST['id_exp'])=="complet"){//Si ils ont totalement répondu, on collecte leur réponses
        $requete2="SELECT * FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne AND classe=:classe AND login=:login";
        $connexion2 = $BDD -> prepare($requete2);
        $classe="A";
        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
        $connexion2-> bindParam(':classe',$classe);
        $connexion2-> bindParam(':login',$tuple2['login']);
        $connexion2 -> execute();
        $reponses=array("Reponses");
        while($reponse=$connexion2->fetch()){
            $reponses[]=$reponse['reponse'];
        }
        fputcsv($fp, $reponses);//On enregistre les réponses
    }
    }
    $fields=array("Interface2");
    fputcsv($fp, $fields);
    $requete="SELECT DISTINCT login, repondre.id_campagne, type_campagne FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne AND classe=:classe";
    $connexion = $BDD -> prepare($requete);
    $classe="B";
    $connexion -> bindParam(':id_exp',$_POST['id_exp']);
    $connexion -> bindParam(':classe',$classe);
    $connexion -> execute();
    while($tuple2=$connexion->fetch()){//On parcourt tout les utilisateurs ayant répondu
        if(testEtatCampagneUtilisateur($tuple2['login'],  $tuple2['id_campagne'], $tuple2['type_campagne'],$_POST['id_exp'])=="complet"){//Si ils ont totalement répondu, on collecte leur réponses
        $requete2="SELECT * FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne AND classe=:classe AND login=:login";
        $connexion2 = $BDD -> prepare($requete2);
        $classe="B";
        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
        $connexion2-> bindParam(':classe',$classe);
        $connexion2-> bindParam(':login',$tuple2['login']);
        $connexion2 -> execute();
        $reponses=array("Reponses");
        while($reponse=$connexion2->fetch()){
            $reponses[]=$reponse['reponse'];
        }
        fputcsv($fp, $reponses);//On enregistre les réponses
    }
}
}
else{
    $requete="SELECT DISTINCT login, repondre.id_campagne, type_campagne  FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne";
    $connexion = $BDD -> prepare($requete);
    $connexion -> bindParam(':id_exp',$_POST['id_exp']);
    $connexion -> execute();
    while($tuple2=$connexion->fetch()){//On parcourt tout les utilisateurs ayant répondu
        if(testEtatCampagneUtilisateur($tuple2['login'],  $tuple2['id_campagne'], $tuple2['type_campagne'],$_POST['id_exp'])=="complet"){//Si ils ont totalement répondu, on collecte leur réponses
        $requete2="SELECT * FROM  campagne, repondre WHERE id_exp=:id_exp AND repondre.id_campagne=campagne.id_campagne AND login=:login";
        $connexion2 = $BDD -> prepare($requete2);
        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
        $connexion2-> bindParam(':login',$tuple2['login']);
        $connexion2 -> execute();
        $reponses=array("Reponses");
        while($reponse=$connexion2->fetch()){
            $reponses[]=$reponse['reponse'];
        }
        fputcsv($fp, $reponses);//On enregistre les réponses
    }
}
}

header('Location: index.php');
?>