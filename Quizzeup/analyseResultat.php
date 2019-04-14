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
        <title> Analyse des résultats </title>
    </head>
    <body>
    <script src="lib/jquery.min.js"></script>
    <script src="lib/Chart.min.js"></script>

    

    <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";
        require("Includes\connect.php"); 
        include('Includes\fonctions.php');
    ?>
    <form method="post" action="" id="formulaire">

    <?php
        if ($BDD){
            $MaRequete="SELECT AVG(reponse) as moyenne, type_question  FROM campagne, experience, repondre, question  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.id_campagne=repondre.id_campagne AND repondre.id_question=question.id_question GROUP BY type_question";
            $connexion = $BDD -> prepare( $MaRequete );
            $connexion -> bindParam(':id_exp',$_POST['id_exp']);
            $connexion -> execute();
            while ($tuple = $connexion ->fetch()){
                ?><input type="hidden" name="<?php echo $tuple["type_question"]?>" value="<?php echo $tuple["moyenne"]?>"><?php
            }
        }
    ?>
    </form>

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12  text-center panel panel-group"> 
                    <div class="panel  panel-primary">
                        <div class="panel-heading"><h2>Analyse des résultats de la campagne <?php echo $_POST['nom_exp'] ?></h2></div>
                    </div>
                    <div class="panel panel-body">
                       
                        <div>
                        <h4>Les valeurs moyennes des différentes dimensions de l’AttrakDiff</h4>
                        <br>
                        </div>

                        <div>
                        <table class="table table-bordered table-hover specialCollapse">
                        <tr>
                        <td class="text-center"> 
                        </td>
                        <td class="text-center">
                        Qualité pragmatique (QP)
                        </td>
                        <td class="text-center">
                        Qualité hédonique-stimulation (QHS)
                        </td>
                        <td class="text-center">
                        Qualité hédonique-identification (QHI)
                        </td>
                        <td class="text-center">
                        Attractivité globale (ATT)
                        </td>
                        </tr>
                        
                        <?php
                        if ($BDD){
                            $MaRequete1="SELECT *  FROM campagne  WHERE id_exp=:id_exp";
                            $connexion1 = $BDD -> prepare( $MaRequete1 );
                            $connexion1 -> bindParam(':id_exp',$_POST['id_exp']);
                            $connexion1 -> execute();
                            while ($tuple1 = $connexion1 ->fetch()){//permet d'analyser les 2 résultats des campagnes comparatives
                                //On recupere les moyennes par type de question dans les réponses à la campagne
                                $MaRequete="SELECT AVG(reponse) as moyenne, type_question  FROM campagne, experience, repondre, question  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.id_campagne=repondre.id_campagne AND repondre.id_question=question.id_question GROUP BY type_question";
                                $connexion = $BDD -> prepare( $MaRequete );
                                $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                                $connexion -> execute();
                                ?>
                                    <tr>
                                    <td class="text-center">
                                        <?php echo $tuple1['nom_interface']?>
                                    </td>
                                <?php
                                while ($tuple = $connexion ->fetch()){
                                    ?><td class="text-center"><?php echo $tuple["moyenne"]?></td><?php
                                }
                                ?><tr><?php
                            }
                        }
                        ?>
                        </tr>
                        </table>
                        </div>
                        <div>
                            <em>
                            Remarque : Les valeurs proches de la moyenne (zone entre 0 et 1) sont standards. Elles ne sont pas négatives et remplissent leur fonction. Toutefois, des améliorations sont possibles sur ces aspects pour créer une UX ou attractivité très positive.
                            </em>
                        </div>

                        <div>
                        <br>
                        <h4>Graphique des paires de mots</h4>

                        </div>
                        <div>
                            <?php
                            $MaRequete1="SELECT * FROM campagne, experience  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp";
                            $connexion1 = $BDD -> prepare( $MaRequete1 );
                            $connexion1 -> bindParam(':id_exp',$_POST['id_exp']);
                            $connexion1 -> execute();
                            $tmp=0;
                            while($tuple1 = $connexion1 ->fetch()){//permet d'analyser les 2 résultats des campagnes comparatives
                            $tmp++;
                            ?>
                                <h5 class="text-center">Interface : <?php echo $tuple1['nom_interface']?> </h5>
                                <?php
                                //On recupere les moyennes par  question
                                    $MaRequete="SELECT AVG(reponse) AS moyenne, repondre.id_question,extr_g,extr_d, sens_analyse FROM question, repondre WHERE question.id_question=repondre.id_question AND id_campagne=:id_campagne GROUP BY repondre.id_question ORDER BY repondre.id_question";
                                    $connexion = $BDD -> prepare( $MaRequete );
                                    $connexion -> bindParam(':id_campagne',$tuple1['id_campagne']);
                                    $connexion ->execute();
                                    $cmpt=0;
                                ?>  
                                <table class="table table-bordered table-hover specialCollapse">
                                <?php
                                while (($tuple = $connexion ->fetch())&&($cmpt<29)){//On affiche les moyennes dans une table en les arrondissant
                                $cmpt++;
                                
                                    ?>

                                    <tr>
                                        <td class="text-center">
                                        <?php                                        
                                            echo $tuple['extr_g'];
                                            echo "</td>";
                                            for ( $i=-3; $i<4; $i++){
                                                ?>
                                                <?php 
                                                if ($tuple['sens_analyse']=="inverse"){
                                                    if(round($tuple['moyenne'])==-$i){
                                                        ?>
                                                        <td>
                                                        <input type="radio" name="<?php echo $tmp." ".$cmpt;?>" value="<?php echo -$i?>" checked="checked">
                                                        </td>
                                                    <?php
                                                    }
                                                    else{
                                                        ?>
                                                        <td>
                                                        <input type="radio" name="<?php echo $tmp." ".$cmpt;?>" value="<?php echo -$i?>"disabled>
                                                        </td>
                                                        <?php
                                                    }
                                                }
                                                    else{
                                                        if(round($tuple['moyenne']) ==$i){
                                                            ?>
                                                            <td>
                                                            <input type="radio" name="1<?php echo $tmp." ".$cmpt;?>" value="<?php echo $i?>" checked="checked">
                                                            </td>
                                                        <?php
                                                        }
                                                        else{
                                                            ?>
                                                            <td>
                                                            <input type="radio" name="<?php echo $tmp." ".$cmpt;?>" value="<?php echo $i?>"disabled>
                                                            </td>
                                                            <?php
                                                        }
                                                    }
                                                }
                        
                                                ?>
                                                <?php
                                            
                                            ?><td class="text-center"><?php
                                            echo $tuple['extr_d'];
                                            echo "</td>";
                                            }
                    
                                    ?>
                                                                            
                                        </tr>
                                        </table> 
                                    <?php    
                                    
                                    }
                                ?>
   
                        </div>
                        <div>
                            Ce diagramme présente les valeurs moyennes pour chaque paire de mots. Les items sont regroupés par sous-échelles et placés autour d’un continuum avec au centre la valeur neutre 0, ce qui permet de distinguer très rapidement quels aspects sont perçus comme négatifs et quels aspects sont perçus comme positifs.
                            <br>
                            <em>
                            Remarque : Les valeurs extrêmes (entre -2 et -3 ou à l’inverse entre +2 et +3) sont particulièrement intéressantes. Elles montrent quelles dimensions sont critiques ou au contraire particulièrement positives, et appellent à des actions d’amélioration sur ces aspects.
                            </em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('Includes\footer.html');//footer
        ?>
    </body>
</html>

