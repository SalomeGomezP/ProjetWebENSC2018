<?php if(!session_id()) 
        { 
            session_start();
        } 
        if (EMPTY($_SESSION['connecté']))
        {
            $_SESSION['connecté']=false;
            $_SESSION['login']="";
            $_SESSION['role']="";
        }   
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <title> Questionnaire </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";

        ?>
        <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 panel panel-group"> 
            <div class="panel panel-info">
                <div class="panel-heading text-center"><h2>Passation du questionnaire</h2></div>
                    <div class="panel panel-body">
                        <?php
                            require("Includes\connect.php"); 
                            if ($BDD) {
                                include('Includes/fonctions.php');
                                //On enregistre dans la  bdd les informations qui viennent d'etre postées dans un form
                                if ($_POST['origine']!=0){
                                    if ($_POST['origine']==1){//Si la page d'origine est la numero 1, on recoit les résultats des questions 1 à 10
                                        $debut=1;
                                        $fin=11;
                                    }
                                    if ($_POST['origine']==2){//Si la page d'origine est la numero 2, on recoit les résultats des questions 11 à 19
                                        $debut=11;
                                        $fin=20;  
                                    }
                                    if ($_POST['origine']==3){//Si la page d'origine est la numero 3, on recoit les résultats des questions 20 à 28
                                        $debut=20;
                                        $fin=29;
                        
                                    }
                        
                                    
                                    for ($j=$debut;$j<$fin; $j++){//On enregistre les nouvelles réponses
                                        if (isset($_POST[$j])){
                                            if (questionDejaRepondues($_SESSION['login'], $_POST['id_campagne'],$j)!=-10)
                                            {
                                                supprimerReponse($_SESSION['login'], $_POST['id_campagne'],$j);//Si on post une nouvelle réponse à la meme question, on supprime l'ancienne avant de la remplacer par la nouvelle
                                            }
                                            $stmt = $BDD->prepare ("INSERT INTO repondre(id_question,login,reponse,id_campagne) VALUES(:id_question,:login,:reponse,:id_campagne)");
                                            $stmt -> execute(array(
                                            'id_question' => $j,
                                            'login' => $_SESSION['login'],
                                            'reponse' => $_POST[$j],
                                            'id_campagne' => $_POST['id_campagne']
                                            ));
                                        }
                                    }
                                }
                                
                                if ($_POST['page']!=0 &&$_POST['page']!=4){
                                    if ($_POST['page']==1){//Si la page est la numero 1, on affiche les questions 1 à 10
                                        $debut=0;
                                        $fin=10;
                                    }
                                    if ($_POST['page']==2){//Si la page est la numero 2, on affiche les questions 11 à 19
                                        $debut=10;
                                        $fin=19;  
                                    }
                                    if ($_POST['page']==3){//Si la page est la numero 3, on affiche les questions 20 à 28
                                        $debut=19;
                                        $fin=28;
                        
                                    }
                        
                                    $MaRequete="SELECT * FROM question ORDER BY ordre_passation";//On fait passer les questions dans l'ordre de passation et non d'analyse
                                    $connexion = $BDD -> query( $MaRequete );
                                    $cmpt=0;//Correspond au numero de question
                                    ?>  
                                        <form id="formulaire" method ="POST" action="questionnaire.php">
                                        <table class="table table-bordered table-hover specialCollapse">
                                    <?php
                                    while (($tuple = $connexion ->fetch())&&($cmpt<$fin)){
                                    $cmpt++;
                                    if ($cmpt>$debut){//On parcourt les questions entre $debut et $fin definis plus tot en fonction de la page
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                            <?php                                        
                                                echo $tuple['extr_g'];//affiche les valeurs à l'extreme gauche
                                                echo "</td>";
                                                for ( $i=-3; $i<4; $i++){//Genere des radio de valeurs entre -3 et 3, correspondant aux differentes valeurs possibles des réponses
                                                    ?>
                                                    <?php 
                                                        if ($tuple['sens_analyse']=="inverse"){//Si la valeur des radio est inversée, on affiche une radio checked lorsque la valeur de la réponse vaut -$i
                                                            if(questionDejaRepondues($_SESSION['login'], $_POST['id_campagne'], $cmpt)==-$i){
                                                                ?>
                                                                <td>
                                                                <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo -$i?>" checked="checked">
                                                                </td>
                                                            <?php
                                                            }
                                                            else{
                                                                ?>
                                                                <td>
                                                                <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo -$i?>">
                                                                </td>
                                                                <?php
                                                            }
                                                        }
                                                        else{
                                                            if(questionDejaRepondues($_SESSION['login'], $_POST['id_campagne'], $cmpt)==$i){//Si la réponse a la valeur de $i, on affiche un radio checked
                                                                ?>
                                                                <td>
                                                                <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo $i?>" checked="checked">
                                                                </td>
                                                            <?php
                                                            }
                                                            else{
                                                                ?>
                                                                <td>
                                                                <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo $i?>">
                                                                </td>
                                                                <?php
                                                            }

                                                        }

                            
                                                    ?>
                                                    <?php
                                                }
                                                ?><td class="text-center"><?php
                                                echo $tuple['extr_d'];//affiche les valeurs à l'extreme droite
                                                echo "</td>";
                        
                                        ?>
                                                                                
                                            </tr>
                                        <?php    
                                        
                                        }
                                    }
                                    ?>
                                    </table>
                                    <input type="hidden" name="id_campagne" value="<?php echo $_POST['id_campagne']?>">
                                    <?php
                                        if (isset($_POST['id_campagneSuivante'])){//Si la campagne est comparative et qu'on teste la premiere interface, on transmet page par page l'id_camapgne et le nom de l'autre interface
                                            ?><input type="hidden" name="id_campagneSuivante" value="<?php echo $_POST['id_campagneSuivante']?>">
                                            <input type="hidden" name="interfaceSuivante" value="<?php echo $_POST['interfaceSuivante']?>">
                                            <?php
                                        }
                                    ?>
                                    <input type="hidden" name="origine" value="<?php echo $_POST['page']?>"><!--On transmet entre autre la page d'arrivée (qui varient selon si on avance ou recule dans le questionnaire) et la page d'origine-->
                                    <input type="hidden" name="type_campagne" value="<?php echo $_POST['type_campagne']?>">
                                    <input type="hidden" name="id_exp" value="<?php echo $_POST['id_exp']?>">
                                    <div class="text-center">
                                        <button class="btn btn-info btn-lg " type="submit" name="page" value="<?php echo $_POST['page']-1;?>">Retour <span class="glyphicon glyphicon-chevron-left"></span></button>
                                        <button class="btn btn-info btn-lg " type="submit" name="page" value="<?php echo $_POST['page']+1;?>">Continuer <span class="glyphicon glyphicon-chevron-right"></span></button>
                                    </div>
                                    
                                    <?php
                                }
                                if($_POST['page']==0){//Affiche instruction pour l'utilisateur
                                    require("Includes\connect.php"); 
                                    if ($BDD) {
                                        $MaRequete="SELECT * FROM questionnaire ";
                                        $connexion = $BDD -> query( $MaRequete );
                                        $tuple = $connexion ->fetch()
                                        ?><h4 class="text-center">Instruction générales liées au questionnaire :</h4><br><?php echo $tuple['intruction_passation']; ?>
                        
                                        <?php
                                        echo "<br>";    
                                        echo "<br>";
                                        
                                        $MaRequete="SELECT * FROM experience WHERE id_exp=:id_exp";
                                        $connexion = $BDD -> prepare( $MaRequete );
                                        $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                                        $connexion -> execute();
                                        $tuple=$connexion ->fetch();

                                        $MaRequete2="SELECT * FROM campagne, experience WHERE experience.id_exp=campagne.id_exp AND experience.id_exp=:id_exp ";
                                        $connexion2 = $BDD -> prepare( $MaRequete2 );
                                        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
                                        $connexion2 -> execute();
                                        $tuple2=$connexion2 ->fetch();
                                        if ($tuple2['type_campagne']=='individuelle'){
                                            ?>
                                            <h4 class="text-center">Nom de l'interface testée : </h4><br><div class="text-center"><?php echo $tuple2['nom_interface'];?></div>
                                            <?php
                                        }
                                        else{
                                            $id_CampagneA=$tuple2['id_campagne'];
                                            $interfaceA=$tuple2['nom_interface'];
                                            ?>
                                            <h4 class="text-center">Noms des interfaces testées : </h4><br><div class="text-center"><?php echo $tuple2['nom_interface'];?></div>
                                            <?php $tuple2=$connexion2 ->fetch();
                                            $id_CampagneB=$tuple2['id_campagne'];
                                            $interfaceB=$tuple2['nom_interface'];
                                            ?>
                                            <br><div class="text-center"><?php echo $tuple2['nom_interface'];?></div>
                                            <?php
                                        }
                                        ?><h4 class="text-center">Description du questionnaire :</h4><br><div class="text-center"><?php echo $tuple['description'];?></div>
                                        <form id="formulaire" method ="POST" action="questionnaire.php">
                                        <?php 
                                        if ($tuple2['type_campagne']=='individuelle'){?>
                                            <input type="hidden" name="id_campagne" value="<?php echo $tuple2['id_campagne']?>">
                                            <input type="hidden" name="type_campagne" value="<?php echo $tuple2['type_campagne']?>">
                                            <?php
                                        }
                                        else{
                                            if ($tuple2['derniere_classe']==1)//Si la derniere classe est 1, on commence par l'interface A et on transmet les informations liées à l'interface B dans interfaceSuivante
                                            {
                                                ?>
                                                <h4 class="text-center">Vous allez commencer par évaluer l'interface <?php echo $interfaceA ?> </h4>
                                                <input type="hidden" name="id_campagne" value="<?php echo $id_CampagneA?>">
                                                <input type="hidden" name="id_campagneSuivante" value="<?php echo $id_CampagneB?>">
                                                <input type="hidden" name="interfaceSuivante" value="<?php echo $interfaceB?>">
                                                <input type="hidden" name="type_campagne" value="<?php echo $tuple2['type_campagne']?>">
                                                <?php
                                            }
                                            else{//On commence par l'interface B
                                                ?>
                                                <h4 class="text-center">Vous allez commencer par évaluer l'interface <?php echo $interfaceB ?> </h4>
                                                <input type="hidden" name="id_campagne" value="<?php echo $id_CampagneB?>">
                                                <input type="hidden" name="id_campagneSuivante" value="<?php echo $id_CampagneA?>">
                                                <input type="hidden" name="interfaceSuivante" value="<?php echo $interfaceA?>">
                                                <input type="hidden" name="type_campagne" value="<?php echo $tuple2['type_campagne']?>">
                                                <?php
                                            }
                                        }?>
                                        <input type="hidden" name="origine" value="<?php echo $_POST['page']?>">
                                        <input type="hidden" name="id_exp" value="<?php echo $_POST['id_exp']?>">
                                        <div class="text-center"><button class="btn btn-info btn-lg" type="submit" name="page" value="<?php echo $_POST['page']+1;?>">Commencer <span class="glyphicon glyphicon-open"></span></button></div>
                                    <?php
                                    }
                                }
                                
                                if($_POST['page']==4){
                                        if (isset($_POST['id_campagneSuivante'])){//Si l'on est en campagne comparative et sur l'etude de la premiere interface
                                            ?>
                                            <h4 class="text-center">Vous allez maintenant évaluer l'interface <?php echo $_POST['interfaceSuivante'] ?> </h4>
                                            <form id="formulaire" method ="POST" action="questionnaire.php">
                                            <input type="hidden" name="id_campagne" value="<?php echo $_POST['id_campagneSuivante']?>">
                                            <input type="hidden" name="origine" value="1">
                                            <input type="hidden" name="type_campagne" value="<?php echo $_POST['type_campagne']?>">
                                            <input type="hidden" name="id_exp" value="<?php echo $_POST['id_exp']?>">
                                            <div class="text-center"><button class="btn btn-info btn-lg " type="submit" name="page" value="1">Commencer <span class="glyphicon glyphicon-chevron-right"></span></button></div>
                                            <?php 
                                        }
                                        else{
                                            if ($_POST['type_campagne']!="individuelle"){//Si l'on est en campagne comparative et sur la seconde interface 
                                                if(testEtatCampagneUtilisateur($_SESSION["login"],$_POST['id_campagne'],$_POST['type_campagne'],$_POST['id_exp'])=="complet"){
                                                    $MaRequete="SELECT * FROM experience WHERE  id_exp=:id_exp ";
                                                    $connexion = $BDD -> prepare( $MaRequete );
                                                    $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                                                    $connexion -> execute();
                                                    $tuple=$connexion->fetch();
                                                    //On met a jour derniere classe
                                                    if ($tuple['derniere_classe']==1){
                                                        
                                                        $connexion2 = $BDD -> prepare( "UPDATE experience SET derniere_classe =2 WHERE id_exp=:id_exp" );
                                                        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
                                                        
                                                        $connexion2 -> execute();
                                                    }
                                                    else{

                                                            
                                                            $connexion2 = $BDD -> prepare( "UPDATE experience SET derniere_classe =1 WHERE id_exp=:id_exp" );
                                                            $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
                                                            
                                                            $connexion2 -> execute();
                                                        
                                                    }

                                                }
                                            }
                                            header('Location:index.php');
                                        }
                                        
                                }
                        
                            }



                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include('Includes\footer.html');//footer
        ?>
    </body>
</html>