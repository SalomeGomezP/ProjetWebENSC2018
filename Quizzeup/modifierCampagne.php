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
        <title> Modifier une campagne </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";

        if (!empty($_POST['nomCampagne'])) //On met à jour la bdd avec toutes les informations contenues dans le formulaire (infos modifiées ou non)
        {
          require("Includes\connect.php"); 
          if ($BDD) {

            $stmt = $BDD->prepare ("UPDATE experience SET nom_exp=:nom_exp, description=:description, nb_partic=:nb_partic WHERE id_exp=:id_exp");
            $stmt -> bindParam(':id_exp',$_POST['id_exp']);
            $stmt -> bindParam(':nom_exp',$_POST['nomCampagne']);
            $stmt -> bindParam(':description',$_POST['description']);
            $stmt -> bindParam(':nb_partic',$_POST['nbreParticipants']);
            $stmt -> execute();

            if ($_POST['type_campagne']=="comparative"){
                $stmt = $BDD->prepare ("UPDATE campagne SET nom_interface=:nom_interface WHERE id_campagne=:id_campagne");
                $stmt -> bindParam(':id_campagne',$_POST['id_campagne1']);
                $stmt -> bindParam(':nom_interface',$_POST['nomInterface1']);
                $stmt -> execute();

                $stmt = $BDD->prepare ("UPDATE campagne SET nom_interface=:nom_interface WHERE id_campagne=:id_campagne");
                $stmt -> bindParam(':id_campagne',$_POST['id_campagne2']);
                $stmt -> bindParam(':nom_interface',$_POST['nomInterface2']);
                $stmt -> execute();
            }else{
                $stmt = $BDD->prepare ("UPDATE campagne SET nom_interface=:nom_interface WHERE id_exp=:id_exp");
                $stmt -> bindParam(':id_exp',$_POST['id_exp']);
                $stmt -> bindParam(':nom_interface',$_POST['nomInterface']);
                $stmt -> execute();
            }
          }

        header('Location: index.php');
        
        } 

        ?>
        
        <div class="container">
            <form id="formulaire" method ="POST" action="modifierCampagne.php">
                <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 text-center panel panel-group"> 
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><h2>Modification de campagne</h2></div>
                        <div class="panel panel-body">
                        <?php
                            require("Includes\connect.php"); 
                            if ($BDD){
                                $connexion=$BDD->prepare("SELECT * FROM experience, campagne WHERE campagne.id_exp=experience.id_exp AND experience.id_exp=:id_exp");
                                $connexion -> bindParam(':id_exp',$_POST['id_exp']);
                                $connexion -> execute();
                                $tuple=$connexion->fetch();//On recherche les informations connues sur la campagne pour pouvoir les afficher
                            }
                        ?>
                            <label for="nomCampagne">Nom</label>
                            <input type="text" name="nomCampagne" id="nomCampagne" class="form-control" value="<?php echo $tuple['nom_exp'] ?>">
                            <br>
                            <label for="nbreParticipants">Nombre de participants</label>
                            <input type="number" min="2" name="nbreParticipants" id="nbreParticipants" class="form-control" value="<?php echo $tuple['nb_partic'] ?>">
                            <br>
                            <br>
                            <br>
                            <?php if ($tuple['type_campagne']=="individuelle"){?>
                                <label for="nomInterface" >Nom interface</label>
                                    <input type="text" name="nomInterface" id="nomInterface" class="form-control" value="<?php echo $tuple['nom_interface']?>">
                                    <br>
                                    <label for="description" >Description</label>
                                    <textarea form ="formulaire" name="description" id="description" style="width:100%" wrap="soft"><?php echo $tuple['description'] ?></textarea>
                                    </div></div></div>
                            <?php
                            }
                            else {?>
                                    <label for="nomInterface1" >Nom interface 1</label>
                                    <input type="text" name="nomInterface1" id="nomInterface1" class="form-control" value="<?php echo $tuple['nom_interface']?>">
                                    <br>
                                    <input type="hidden" name="id_campagne1" value="<?php echo $tuple['id_campagne'];?>">
                                    <?php $tuple=$connexion->fetch();?>
                                    <input type="hidden" name="id_campagne2" value="<?php echo $tuple['id_campagne'];?>">
                                    <label for="nomInterface2" >Nom interface 2</label>
                                    <input type="text" name="nomInterface2" id="nomInterface2" class="form-control" value="<?php echo $tuple['nom_interface']?>">
                                    <br>
                                    <label for="description" >Description</label>
                                    <textarea form ="formulaire" name="description" id="description" style="width:100%" wrap="soft"><?php echo $tuple['description'] ?> </textarea>
                                    </div></div></div>
                                <?php
                            }?>
                            
                            <input type="hidden" name="id_exp" value="<?php echo $tuple['id_exp'];?>">
                            <input type="hidden" name="type_campagne" value="<?php echo $tuple['type_campagne'];?>">
                            <button class="btn btn-secondary btn-block btn-lg " type="submit">Modifier </button><!-- On post toutes les modifications-->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <?php include('Includes\footer.html');//footer
        ?>
    </body>

</html>


