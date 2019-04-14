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
        <title> Liste des résultats </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";
        ?>

        <div class="container">
        <div class="row">
            <div class="col-xs-7 col-md-7  text-center ">

                <div class="row">
                    <div class="col-xs-12 col-md-12  text-center panel panel-group"> 
                        <div class="panel  panel-primary">
                        <?php
                            require("Includes/connect.php"); 
                            include('Includes\fonctions.php');
                            if ($BDD) {
                        ?>
                            <div class="panel-heading"><h4>Résultats lors de la campagne <?php echo $_POST['nom_exp'];?> </h4></div>
                        </div>

                        <div class="panel panel-body">
                        <?php

                            $MaRequete="SELECT DISTINCT repondre.login AS login FROM campagne, experience, repondre  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.id_campagne=repondre.id_campagne";
                            $connexion = $BDD -> prepare( $MaRequete );
                            $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                            $connexion -> execute();
                            
                        ?>

                            <table class="table table-bordered table-hover specialCollapse">
                            <tr>
                                    <td class="text-center">
                                        Login
                                    </td>
                                    <td class="text-center">
                                        Date de naissance
                                    </td>     
                                    <td class="text-center">
                                        Nationalité
                                    </td>  
                                    <td class="text-center">
                                        Genre
                                    </td>
                                    <td class="text-center">
                                        
                                    </td>                             
                                    </tr>
                            <?php
                            while ($tuple = $connexion ->fetch()){  //on parcourt tout les logins des personnes aillant répondu à la campagne                  
                                $MaRequete1="SELECT * FROM  utilisateur  WHERE  utilisateur.login=:login";
                                $connexion1 = $BDD -> prepare( $MaRequete1 );
                                $connexion1 -> bindParam(':login',$tuple['login']);
                                $connexion1 -> execute();
                                $tuple1 = $connexion1 ->fetch();
                                
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $tuple1['login']; ?> 
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        echo $tuple1['date_naissance'];
                                        ?> 
                                    </td>     
                                    <td class="text-center">
                                        <?php
                                        echo $tuple1['nation'];
                                        ?> 
                                    </td>  
                                    <td class="text-center">
                                        <?php
                                        echo ChiffretoGenre($tuple1['genre']);
                                        ?> 
                                    </td>   
                                    <td class="text-center">
                                    <?php                                        
                                        $MaRequete2="SELECT experience.id_exp AS id_exp, campagne.id_campagne AS id_campagne, nom_exp, type_campagne  FROM campagne, experience, repondre  WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp AND campagne.id_campagne=repondre.id_campagne AND repondre.login=:login";
                                        $connexion2 = $BDD -> prepare( $MaRequete2 );
                                        $connexion2 -> bindParam(':id_exp',$_POST['id_exp']);
                                        $connexion2 -> bindParam(':login',$tuple1['login']);
                                        $connexion2 -> execute();
                                        $tuple2 = $connexion2 ->fetch();
                                        ?>
                                        <div>
                                            <form id="formulaire" method ="POST" action="resultat.php"><!--On envoie via un formulaire les informations necessaires à la modification des réponses-->
                                                <input type="hidden" name="id_exp" value="<?php echo $tuple2['id_exp'];?>">
                                                <input type="hidden" name="id_campagne" value="<?php echo $tuple2['id_campagne'];?>">
                                                <input type="hidden" name="utilisateur" value="<?php echo $tuple1['login'];?>">
                                                <input type="hidden" name="type_campagne" value="<?php echo $tuple2['type_campagne']?>">
                                                <input type="hidden" name="nom_exp" value="<?php echo $tuple2['nom_exp'];?>">
                                                <button class="btn btn-info btn-sm btn-block" type="submit">Modifier <span class="glyphicon glyphicon-pencil"></button>
                                            </form>
                                            <form id="formulaire" method ="POST" action="supprimerReponse.php"><!--On envoie via un formulaire les informations necessaires à la suppression des réponses-->
                                                <input type="hidden" name="id_exp" value="<?php echo $tuple2['id_exp'];?>">
                                                <input type="hidden" name="utilisateur" value="<?php echo $tuple1['login'];?>">
                                                <button class="btn btn-info btn-sm btn-block" type="submit">Supprimer <span class="glyphicon glyphicon-trash"></span></button>
                                            </form>
                                        </div>
                                    </td>                              
                                    </tr>
                                <?php    
                                
                                }
                            ?>
                            </table>
                        </div>
                        <?php
                        }?>
                    </div>
                </div>
            </div>   
            <div class="col-xs-5 col-md-5  text-center ">
                <div class="row">
                    <br>
                </div>
                <div class="row">
                    <form id="formulaire" method ="POST" action="analyseResultat.php"><!--On envoie via un formulaire les informations necessaires à l'analyse des résultats-->
                        <input type="hidden" name="id_exp" value="<?php echo $_POST['id_exp'];?>">
                        <input type="hidden" name="nom_exp" value="<?php echo $_POST['nom_exp'];?>">
                        <button class="btn btn-primary btn-lg btn-block" type="submit"><h1> Analyse des résultats <span class="glyphicon glyphicon-sunglasses"></span></h1></button>
                    </form>
                </div>
            </div>  
        </div>
        <?php include('Includes\footer.html');
        ?>
    </body>    
    </html>