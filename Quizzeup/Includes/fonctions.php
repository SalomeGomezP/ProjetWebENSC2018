<?php

function connexion($typePersonne, $login){//permet de stocker les informations de connexion
    $_SESSION['login']=$login;
    $_SESSION['connecté']=true;
    $_SESSION['role']=$typePersonne;
}

function ChiffretoGenre($chiffre){//Conversion chiffre vers genre
    if ($chiffre==1)
    {
        return "féminin";
    }
    else if ($chiffre==2)
    {
        return "masculin" ;
    }
    else if ($chiffre==3)
    {
        return "autre" ;
    }
}

function GenretoChiffre($string){// Conversion genre vers chiffre 
    if ($string=="féminin"||string=="femme"||string=="Féminin"||string=="feminin"||string=="Feminin"||string=="FEMININ")
    {
        return 1;
    }
    else if ($string=="masculin"||string=="homme"||string=="Masculin"||string=="MASCULIN")
    {
        return 2;
    }

    else 
    {
        return 3;
    }
}

function questionDejaRepondues($login, $id_campagne, $id_question){//Verifie si un utilisateur à répondu à une certaine question d'une campagne
    require("Includes\connect.php"); 
    if ($BDD) {
        $connexion2 = $BDD -> prepare("SELECT * FROM repondre WHERE login=:login AND id_campagne=:id_campagne AND id_question=:id_question" );
        $connexion2 -> bindParam(':login',$login);
        $connexion2 -> bindParam(':id_campagne',$id_campagne);
        $connexion2 -> bindParam(':id_question', $id_question);
        $connexion2 -> execute();  
        $tuple2=$connexion2->fetch();
        if(isset($tuple2['id_question'])){
            return $tuple2['reponse'];//Renvoie la valeur de la réponse
        }
        else {
            return -10;//renvoie -10 si il n'y a pas eu de réponse
        }
    }
}

function supprimerReponse($login, $id_campagne, $id_question){//permet de supprimer une réponse à une question d'un camapgne d'un utilisateur
    require("Includes\connect.php"); 
    if ($BDD) {
        $connexion2 = $BDD -> prepare("DELETE FROM repondre WHERE login=:login AND id_campagne=:id_campagne AND id_question=:id_question");
        $connexion2 -> bindParam(':login',$login);
        $connexion2 -> bindParam(':id_campagne',$id_campagne);
        $connexion2 -> bindParam(':id_question', $id_question);
        $connexion2 -> execute();
    }
}

function testEtatCampagneUtilisateur($login, $id_campagne, $type_campagne,$id_exp){//etudie l'etat de la campagne du point de vue de l'utilisateur
    require("Includes\connect.php"); 
        if ($BDD) {
        $compteurReponses=0;//On compte le nombre de réponses
        $connexion=$BDD->prepare("SELECT * FROM repondre WHERE login=:login AND id_campagne=:id_campagne");
        $connexion -> bindParam(':login',$login);
        $connexion -> bindParam(':id_campagne',$id_campagne);
        $connexion -> execute();
        while ($tuple = $connexion ->fetch()){
                $compteurReponses++;
            }
        if ($type_campagne=="individuelle"){
            if ($compteurReponses==28) return "complet";
            if ($compteurReponses==0) return "disponible";
            return "rejoint";
        }
        else{//Lorsque la campagne est comparative, il faut avoir 56 réponses pour avoir completé le questionnaire
            $connexion=$BDD->prepare("SELECT * FROM repondre,campagne,experience WHERE login=:login AND repondre.id_campagne=campagne.id_campagne AND repondre.id_campagne!=:id_campagne AND experience.id_exp=:id_exp AND experience.id_exp=campagne.id_exp");
            $connexion -> bindParam(':login',$login);
            $connexion -> bindParam(':id_campagne',$id_campagne);
            $connexion -> bindParam(':id_exp',$id_exp);
            $connexion -> execute();
            while ($tuple = $connexion ->fetch()){
                $compteurReponses++;
            }
            if ($compteurReponses==56) return "complet";
            if ($compteurReponses==0) return "disponible";
            return "rejoint";
        }
    }
}


function testEtatCampagneExperimentateur($id_exp){//etudie l'etat de la campagne du point de vue de l'experimentateur
    require("Includes\connect.php"); 
    if ($BDD) { //On cherche le nombre de réponses complètes
        $connexion=$BDD->prepare("SELECT * FROM experience, campagne WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp");
        $connexion -> bindParam(':id_exp',$id_exp);
        $connexion -> execute();
        $tuple = $connexion ->fetch();
        $id_campagne=$tuple['id_campagne'];
        $connexion1=$BDD->prepare("SELECT DISTINCT login FROM repondre WHERE id_campagne=:id_campagne1");
        $connexion1 -> bindParam(':id_campagne1',$id_campagne);
        $connexion1 -> execute();

        $cmpt=0;
        while($tuple1 = $connexion1 ->fetch()){
            if (testEtatCampagneUtilisateur($tuple1['login'], $tuple['id_campagne'], $tuple['type_campagne'],$tuple['id_exp'])=="complet"){
                $cmpt++;
            }
        }

        if($cmpt>=$tuple['nb_partic']){//La campagne est finie, on supprime les réponses incompletes et on renvoie le status de la campagne
            if ($tuple['type_campagne']=="comparative"){
                $tuple = $connexion ->fetch();
                $id_campagne2=$tuple['id_campagne'];
                $connexion1=$BDD->prepare("SELECT DISTINCT login FROM repondre WHERE id_campagne=:id_campagne1 OR id_campagne=:id_campagne2");
                $connexion1 -> bindParam(':id_campagne1',$id_campagne);
                $connexion1 -> bindParam(':id_campagne2',$id_campagne2);
                $connexion1 -> execute();
                while($tuple1 = $connexion1 ->fetch()){
                    if (testEtatCampagneUtilisateur($tuple1['login'], $tuple['id_campagne'], $tuple['type_campagne'],$tuple['id_exp'])!="complet"){
                        $connexion2=$BDD->prepare("DELETE FROM repondre WHERE id_campagne=:id_campagne1  AND login=:login");
                        $connexion2 -> bindParam(':id_campagne1',$id_campagne);
                        $connexion2 -> bindParam(':login',$tuple1['login']);
                        $connexion2 -> execute();
                    }
                }
            }
            else {
                $connexion1=$BDD->prepare("SELECT DISTINCT login FROM repondre WHERE id_campagne=:id_campagne1");
                $connexion1 -> bindParam(':id_campagne1',$id_campagne);
                $connexion1 -> execute();

            while($tuple1 = $connexion1 ->fetch()){
                if (testEtatCampagneUtilisateur($tuple1['login'], $tuple['id_campagne'], $tuple['type_campagne'],$tuple['id_exp'])!="complet"){
                    $connexion2=$BDD->prepare("DELETE FROM repondre WHERE (id_campagne=:id_campagne1 OR id_campagne=:id_campagne2) AND login=:login");
                    $connexion2 -> bindParam(':id_campagne1',$id_campagne);
                    $connexion2 -> bindParam(':id_campagne1',$id_campagne2);
                    $connexion2 -> bindParam(':login',$tuple1['login']);
                    $connexion2 -> execute();
                }
            }
            }

            return "finie";
        }
        return "enCours";
    }
}


?>