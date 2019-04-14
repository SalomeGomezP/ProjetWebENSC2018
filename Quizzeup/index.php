


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
        <title> Accueil </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');//barre de navigation
        include('Includes/fonctions.php');//fonctions
        echo"<br>";
        echo"<br>";
        echo"<br>";
        
        
        if (isset($_GET['connexion']))
        {
            if ($_GET['connexion']==1)//message de deconnexion
            {
            ?>
            <div class="col-xs-6 col-xs-offset-3  col-md-6 col-md-offset-3 alert alert-success text-center">
                Vous êtes maintenant déconnecté.
            </div>
            <?php
            }
        }
        
        if($_SESSION['connecté']==false) // Affichage lorsqu'il n'y a pas de connexion
        {   

            ?>
            
            <div class="jumbotron jumbotron-fluid text-center" style="background: url('images/mem003.jpg') no-repeat center center">
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            </div>
            <div class="container">
            <div class="row">  
                <div class="col-xs-6 col-xs-offset-3  col-md-6 col-md-offset-3 text-center">
                <h1>Bienvenue ! </h1><hr/><br/> <em><strong>Quizzeup</em></strong> est un site de <strong>passation de questionnaires en ligne.</strong> <br/>
                En tant qu'<strong>expérimentateur</strong>, vous pourrez faire passer des campagnes de test afin d'évaluer l'UX d'une ou plusieurs interfaces.<br/>
                En tant qu'<strong>utilisateur</strong>, vous pourrez participer aux campagnes en répondant aux questionnaires en ligne.<br/>
                L'<em>Attrakdiff</em> est disponible, connectez-vous ou inscrivez-vous pour démarrer !<br/> 
                
                </div>
            </div>        
            <br/> 
            <div class="row">  
            <div class="col-xs-6 col-xs-offset-3  col-md-6 col-md-offset-3 text-center "><a class="btn btn-primary btn-lg btn-block" href="login.php"> <span class="glyphicon glyphicon-log-in"></span>Se connecter</a></div>
            <br/>
            <div class="col-xs-6 col-xs-offset-3  col-md-6 col-md-offset-3 text-center "><a class="btn btn-primary btn-lg btn-block" href="inscription.php"> <span class="glyphicon glyphicon-pencil"></span>S'inscrire</a></div>
            </div>
            <?php
        }
        else
        {
            if ($_SESSION['role']=="experimentateur") // Affichage lorsqu'il y a connexion en tant qu'experimentateur
            {
                ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6 col-md-6 text-center ">
                                <div class="row">
                                    <div class="col-xs-12 col-md-12 text-center"> <a href="creerCampagne.php" role="button" type="button" class="btn btn-primary btn-lg btn-block"><h1>Ajouter une campagne <span class="glyphicon glyphicon-plus"></span></h1></a></div>
                                </div>
                                <div class="row">
                                    <br>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12 text-center panel panel-group"> 
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><h4>Vos campagnes en cours</h4></div>
                                        </div>
                                    </div>
                                    <div class="panel panel-body">
                                        <table class="table table-bordered table-hover specialCollapse">
                                            <?php
                                                require("Includes\connect.php"); 
                                                if ($BDD) {
                                                    $connexion = $BDD -> prepare("SELECT * FROM experience, administrer, campagne  WHERE experience.id_exp=administrer.id_exp AND experience.id_exp=campagne.id_exp AND administrer.login=:login " );
                                                    $connexion -> bindParam(':login',$_SESSION['login']);
                                                    $connexion -> execute();
                                                    }       
                                                while ( $tuple = $connexion ->fetch())//on parcourt toutes les campagnes(enregistrées dans la table experience) de l'experimentateur
                                                    {
                                                        if (testEtatCampagneExperimentateur($tuple['id_exp'])=="enCours"){//on affiche que les campagnes en cours
                                                            if ($tuple['classe']!='B'){//permet de ne pas afficher deux fois la meme experience dans le cas d'une campagne comparative
                                                            ?>
                                                            <tr>
                                                            <td class="text-center">
                                                                <form id="formulaire" method ="POST" action="listeResultat.php"><!--On envoie via un formulaire les informations necessaires dans l'affichage de la liste des réponses-->
                                                                <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                                <input type="hidden" name="id_campagne" value="<?php echo $tuple['id_campagne'];?>">
                                                                <input type="hidden" name="login" value="<?php echo $_SESSION['login'];?>">
                                                                <input type="hidden" name="nom_exp" value="<?php echo $tuple['nom_exp'];?>">
                                                                <button class="btn btn-info" type="submit"><?php echo $tuple['nom_exp']; ?> <span class="glyphicon glyphicon-list-alt"></span></button>
                                                                </form>
                                                            </td>

                                                            <td class="small"><!-- On affiche le type de la campagne ainsi que le nombre de participants prévus-->
                                                                <?php 
                                                                    echo" Campagne {$tuple['type_campagne']} - {$tuple['nb_partic']} participants";
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                            <form id="formulaire" method ="POST" action="modifierCampagne.php"><!--On envoie via un formulaire les informations necessaires pour permettre la modification de la campagne-->
                                                                <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                                <div><button class="btn btn-info btn-sm btn-block" type="submit">Modifier <span class="glyphicon glyphicon-pencil"></span></button></div>
                                                            </form>
                                                            <div>
                                                                <form id="formulaire" method ="POST" action="supprimerCampagne.php"><!--On envoie via un formulaire les informations necessaires permettre la suppression de la campagne-->
                                                                <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                                <button class="btn btn-info btn-sm btn-block" type="submit">Supprimer <span class="glyphicon glyphicon-trash"></span></button>
                                                                </form>
                                                            </div>
                                                            <form id="formulaire" method ="POST" action="telecharger.php"><!--On envoie via un formulaire les informations necessaires permettre le telechargement des réponses -->
                                                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                            <div><button class="btn btn-info btn-sm btn-block" type="submit">Télécharger <span class="glyphicon glyphicon-floppy-save"></span></button></div>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }    
                                                    }
                                                }
                                                ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 text-center panel panel-group">
                                <div class="panel panel-success">
                                    <div class="panel-heading"><h4>
                                    Campagnes finies 
                                    </h4></div>
                                    <div class="panel panel-body">
                                        <table class="table table-bordered table-hover specialCollapse">
                                        <?php
                                            require("Includes\connect.php"); 
                                            if ($BDD) {
                                                $connexion = $BDD -> prepare("SELECT * FROM experience, administrer, campagne WHERE experience.id_exp=administrer.id_exp AND experience.id_exp=campagne.id_exp AND administrer.login=:login " );
                                                $connexion -> bindParam(':login',$_SESSION['login']);
                                                $connexion -> execute();
                                                }       
                                            while ( $tuple = $connexion ->fetch())//on parcourt toutes les campagnes(enregistrées dans la table experience) de l'experimentateur
                                                {
                                                    if (testEtatCampagneExperimentateur($tuple['id_exp'])=="finie"){//on affiche que les campagnes finies
                                                        if ($tuple['classe']!='B'){//permet de ne pas afficher deux fois la meme experience dans le cas d'une campagne comparative
                                                        ?>
                                                        <tr>
                                                        <td class="text-center"><!--On envoie via un formulaire les informations necessaires dans l'affichage de la liste des réponses-->
                                                        <form id="formulaire" method ="POST" action="listeResultat.php">
                                                        <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                        <input type="hidden" name="id_campagne" value="<?php echo $tuple['id_campagne'];?>">
                                                        <input type="hidden" name="login" value="<?php echo $_SESSION['login'];?>">
                                                        <input type="hidden" name="nom_exp" value="<?php echo $tuple['nom_exp'];?>">
                                                        <button class="btn btn-info" type="submit"><?php echo $tuple['nom_exp']; ?> <span class="glyphicon glyphicon-list-alt"></span></button>
                                                        </form>
                                                    </td>
                                                        <td class="small"><!-- On affiche le type de la campagne ainsi que le nombre de participants prévus-->
                                                            <?php 
                                                                echo"Campagne {$tuple['type_campagne']} - {$tuple['nb_partic']} participants";
                                                            ?>
                                                        </td>
                                                        <td class="text-center"><!--On envoie via un formulaire les informations necessaires pour permettre la modification de la campagne-->
                                                            <form id="formulaire" method ="POST" action="modifierCampagne.php">
                                                                <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                                <div><button class="btn btn-info btn-sm btn-block" type="submit">Modifier <span class="glyphicon glyphicon-pencil"></span></button></div>
                                                            </form>
                                                            <div>
                                                                <form id="formulaire" method ="POST" action="supprimerCampagne.php"><!--On envoie via un formulaire les informations necessaires permettre la suppression de la campagne-->
                                                                <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                                <button class="btn btn-info btn-sm btn-block" type="submit">Supprimer <span class="glyphicon glyphicon-trash"></span></button>
                                                                </form>
                                                            </div>
                                                            <form id="formulaire" method ="POST" action="telecharger.php"><!--On envoie via un formulaire les informations necessaires permettre le telechargement des réponses -->
                                                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                            <div><button class="btn btn-info btn-sm btn-block" type="submit">Télécharger <span class="glyphicon glyphicon-floppy-save"></span></button></div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                       }
                                                    }
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
            }

            else { // Affichage lorsqu'il y a connexion en tant qu'utilisateur
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-6 col-md-6  text-center ">
                            <div class="row">
                            <div class="col-xs-12 col-md-12  text-center panel panel-group"> 
                                    <div class="panel  panel-info">
                                        <div class="panel-heading"><h4>Campagnes disponibles</h4></div>
                                    </div>
                                </div>
                                <div class="panel panel-body">
                                    <table class="table table-bordered table-hover specialCollapse">
                                        <tr>
                                            <td class="text-center">
                                                Nom 
                                            </td>
                                            <td class="text-center">
                                                Description
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <?php
                                            require("Includes\connect.php"); 
                                            if ($BDD) {
                                                $MaRequete="SELECT * FROM campagne, experience WHERE campagne.id_exp=experience.id_exp";
                                                $connexion = $BDD -> query( $MaRequete );
                                                }       
                                            while ( $tuple = $connexion ->fetch())//on parcourt toutes les campagnes(enregistrées dans la table experience)
                                                {
                                                    if (testEtatCampagneUtilisateur($_SESSION['login'],$tuple['id_campagne'],$tuple['type_campagne'],$tuple['id_exp'])=="disponible" && testEtatCampagneExperimentateur($tuple['id_exp'])=="enCours"){ //on affiche que les campagnes que l'utilisateur n'a pas commencé
                                                        
                                                        if ($tuple['classe']!='B'){//permet de ne pas afficher deux fois la meme experience dans le cas d'une campagne comparative
                                                            ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $tuple['nom_exp'] ?>
                                                            </td>
                                                            <td class="text-center small">
                                                                <?php echo $tuple['description'] ?>
                                                            </td>
                                                            <td class="text-center">
                                                            <form id="formulaire" method ="POST" action="questionnaire.php"><!--On envoie via un formulaire les informations necessaires à la passation du questionnaire-->
                                                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                            <input type="hidden" name="page" value="0">
                                                            <input type="hidden" name="origine" value="0">
                                                            <button class="btn btn-info btn-sm " type="submit">Commencer <span class="glyphicon glyphicon-open"></span></button>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                        ?>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12  text-center panel panel-group"> 
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><h4>Campagnes rejointes</h4></div>
                                    </div>
                                </div>
                                <div class="panel panel-body">
                                    <table class="table table-bordered table-hover specialCollapse">
                                        <tr>
                                            <td class="text-center">
                                                Nom 
                                            </td>
                                            <td class="text-center">
                                                Description
                                            </td>

                                            <td class="text-center">
                                            </td>
                                            <hr>
                                        </tr>
                                        <?php
                                            require("Includes\connect.php"); 
                                            if ($BDD) {
                                                $MaRequete="SELECT * FROM campagne, experience WHERE campagne.id_exp=experience.id_exp";
                                                $connexion = $BDD -> query( $MaRequete );
                                                }       
                                            while ( $tuple = $connexion ->fetch())//on parcourt toutes les campagnes(enregistrées dans la table experience)
                                                {
                                                    if (testEtatCampagneUtilisateur($_SESSION['login'],$tuple['id_campagne'],$tuple['type_campagne'],$tuple['id_exp'])=="rejoint"){ //on affiche que les campagnes que l'utilisateur a commencé
                                                        if ($tuple['classe']!='B'){//permet de ne pas afficher deux fois la meme experience dans le cas d'une campagne comparative
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $tuple['nom_exp'] ?>
                                                            </td>
                                                            <td class="text-center small">
                                                                <?php echo $tuple['description'] ?>
                                                            </td>
                                                            <td class="text-center">
                                                            <form id="formulaire" method ="POST" action="questionnaire.php"><!--On envoie via un formulaire les informations necessaires à la passation du questionnaire-->
                                                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                            <input type="hidden" name="page" value="0">
                                                            <input type="hidden" name="origine" value="0">
                                                            <button class="btn btn-info btn-sm " type="submit">Reprendre <span class="glyphicon glyphicon glyphicon-new-window"></span></button>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                }
                                        ?>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-xs-6  col-md-6  text-center panel panel-group">
                            <div class="panel panel-success">
                                <div class="panel-heading"><h4>
                                Campagnes passées </h4>
                                </div>
                                <div class="panel panel-body">
                                    <table class="table table-bordered table-hover specialCollapse">
                                            <tr>
                                                <td class="text-center">
                                                    Nom
                                                </td>
                                                <td class="text-center">
                                                    Description
                                                    
                                                </td>
                                                <td class="text-center">
                                                
                                                </td>
                                            </tr>
                                            <?php
                                            require("Includes\connect.php"); 
                                            if ($BDD) {
                                                $MaRequete="SELECT * FROM campagne, experience WHERE campagne.id_exp=experience.id_exp";
                                                $connexion = $BDD -> query( $MaRequete );
                                                }       
                                            while ( $tuple = $connexion ->fetch())//on parcourt toutes les campagnes(enregistrées dans la table experience)
                                                {
                                                    if (testEtatCampagneUtilisateur($_SESSION['login'],$tuple['id_campagne'],$tuple['type_campagne'],$tuple['id_exp'])=="complet"){ //on affiche que les campagnes que l'utilisateur a terminé
                                                        if ($tuple['classe']!='B'){//permet de ne pas afficher deux fois la meme experience dans le cas d'une campagne comparative
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $tuple['nom_exp'] ?>
                                                            </td>
                                                            <td class="text-center small">
                                                                <?php echo $tuple['description'] ?>
                                                            </td>
                                                            <td class="text-center">
                                                            <form id="formulaire" method ="POST" action="resultatUtilisateur.php"><!--On envoie via un formulaire les informations permettant l'affichage des réponses-->
                                                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                                                            <input type="hidden" name="id_campagne" value="<?php echo $tuple['id_campagne'];?>">
                                                            <input type="hidden" name="login" value="<?php echo $_SESSION['login'];?>">
                                                            <input type="hidden" name="type_campagne" value="<?php echo $tuple['type_campagne'];?>">
                                                            <input type="hidden" name="nom_exp" value="<?php echo $tuple['nom_exp'];?>">
                                                            <button class="btn btn-success btn-sm " type="submit">Mes réponses <span class="glyphicon glyphicon-list-alt"></span></button>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                        ?>
                                        </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php

            }
        }
        echo"<br/>";
        include('Includes\footer.html');//footer
        ?>
        

    </body>

</html>

