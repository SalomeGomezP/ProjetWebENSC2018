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
        <title> Mes résultats </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";
        ?>

        <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12  text-center ">

                <div class="row">
                    <div class="col-xs-12 col-md-12  text-center panel panel-group"> 
                        <div class="panel  panel-primary">
                        <?php
                            require("Includes\connect.php"); 
                            include('Includes/fonctions.php');

                        ?>
                            <div class="panel-heading"><h4>Résultats lors de la campagne <?php echo $_POST['nom_exp']?> </h4></div>
                        </div>

                        <div class="panel panel-body">
                        <?php
                        if($_POST['type_campagne']=="comparative"){//On prend d'abord la campagne A si on est dans un cas comparatif
                            $A="A";
                            $MaRequete="SELECT * FROM campagne, experience  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.classe=:classe";
                            $connexion = $BDD -> prepare( $MaRequete );
                            $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                            $connexion -> bindParam(':classe',$A);
                            $connexion -> execute();
                            $tuple1 = $connexion ->fetch();
                        }
                        else{
                            $MaRequete="SELECT * FROM campagne, experience  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.id_campagne=:id_campagne";
                            $connexion = $BDD -> prepare( $MaRequete );
                            $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                            $connexion -> bindParam(':id_campagne',$_POST['id_campagne']);
                            $connexion -> execute();
                            $tuple1 = $connexion ->fetch();
                        }
                        ?>
                            <h5 class="text-center">Interface : <?php echo $tuple1['nom_interface']?> </h4>
                            <?php
                                $MaRequete="SELECT * FROM question ORDER BY ordre_passation";
                                $connexion = $BDD -> query( $MaRequete );
                                $cmpt=0;
                            ?>  
                            <form id="formulaire" method ="POST" action="resultatUtilisateur.php">
                            <table class="table table-bordered table-hover specialCollapse">
                            <?php
                            while (($tuple = $connexion ->fetch())&&($cmpt<29)){//On parcourt les questions et on affiche les reponses enregistrées par l'utilisateur
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
                                                if(questionDejaRepondues($_SESSION['login'], $tuple1['id_campagne'], $cmpt)==-$i){
                                                    ?>
                                                    <td>
                                                    <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo -$i?>" checked="checked">
                                                    </td>
                                                <?php
                                                }
                                                else{
                                                    ?>
                                                    <td>
                                                    <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo -$i?>"disabled>
                                                    </td>
                                                    <?php
                                                }
                                            }
                                                else{
                                                    if(questionDejaRepondues($_SESSION['login'], $tuple1['id_campagne'], $cmpt)==$i){
                                                        ?>
                                                        <td>
                                                        <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo $i?>" checked="checked">
                                                        </td>
                                                    <?php
                                                    }
                                                    else{
                                                        ?>
                                                        <td>
                                                        <input type="radio" name="<?php echo $cmpt;?>" value="<?php echo $i?>"disabled>
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
                                <?php    
                                
                                
                            ?>
                                </table>
                            <?php
                            if ($_POST['type_campagne']=="comparative"){//Si on est en comparatif, on renvoie les informations en tant que campagne "individuelle" de la campagne B vers la page résultatUtilisateur
                                $B="B";
                                $MaRequete="SELECT * FROM campagne, experience  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.classe=:classe";
                                $connexion = $BDD -> prepare( $MaRequete );
                                $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                                $connexion -> bindParam(':classe',$B);
                                $connexion -> execute();
                                $tuple2 = $connexion ->fetch();
                            ?>
                            <input type="hidden" name="id_exp" value="<?php echo $tuple2['id_exp'];?>">
                            <input type="hidden" name="id_campagne" value="<?php echo $tuple2['id_campagne'];?>">
                            <input type="hidden" name="login" value="<?php echo $_SESSION['login'];?>">
                            <input type="hidden" name="type_campagne" value="individuelle">
                            <input type="hidden" name="nom_exp" value="<?php echo $_POST['nom_exp'];?>">
                            <div class="text-center">
                                <button class="btn btn-info btn-lg " type="submit" >Continuer <span class="glyphicon glyphicon-chevron-right"></span></button>
                            </div>
                            <?php }?>

                        </div>
                        <?php
                        ?>
                    </div>
                </div>
                
        <?php include('Includes\footer.html');
        ?>
    </body>    
    </html>