<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <title> Mes informations </title>
    </head>
    <body>
    <?php if(!session_id()) 
        { 
            session_start();
        } 

        ?>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";
        ?>

        <div class="jumbotron">
            <div class="row">
                <div class="col-md-12 col-xs-8 text-center">
                    <h3 class="infoCompte">Mon compte</h3> 
                </div>
            </div>
        </div>

        <form method="POST" action="infosCompte.php">
        <?php
        require("Includes\connect.php"); 
        if($_SESSION['connecté']==false) 
        { ?> 
        <div class="container">
        <div class="row">
        <div class="col-md-12 col-xs-6 text-center">
        <h4>Vous n'êtes pas connecté !</h4>
        </div>
        </div>
        </div><?php  }

        else { 
            
            if (!((empty($_POST['mail']) ) or empty($_POST['mdp'])))
            {
                require("Includes\connect.php"); 
                if ($BDD) {
                    $login=$_SESSION['login'];
                    if ($_SESSION['role']=="experimentateur")
                    {$requete = $BDD->prepare("UPDATE experimentateur SET mail=:mail , mdp=:mdp  WHERE login=:login ");
                    $requete2 = $BDD->prepare("UPDATE experimentateur SET organisme=:organisme  WHERE login=:login ");
                    $organisme=$_POST['orga'];
                    $requete2->bindParam(':organisme',$organisme);
                    $requete2->bindParam(':login',$login);
                    $requete2->execute();
                    }//gérer si utilisateur aussi  
                    else {
                        $requete = $BDD->prepare("UPDATE utilisateur SET mail=:mail , mdp=:mdp  WHERE login=:login ");
                        $requete2 = $BDD->prepare("UPDATE utilisateur SET date_naissance=:date_n , genre=:genre , nation=:nation WHERE login=:login ");
                        $date=$_POST['date_n'];
                        $genre=$_POST['genre'];
                        $nation=$_POST['nation'];
                        $requete2->bindParam(':date_n',$date);
                        $requete2->bindParam(':genre',$genre);//C'est PAS POSSIBLE
                        $requete2->bindParam(':nation',$nation);
                        $requete2->bindParam(':login',$login);
                        $requete2->execute();
                    }
                    $mail=$_POST['mail'];
                    $mdp=$_POST['mdp'];
                    $requete->bindParam(':login',$login);
                    $requete->bindParam(':mail',$mail);
                    $requete->bindParam(':mdp',$mdp);
                    $requete->execute();
                            }
                            
                    }

            if ($_SESSION['role']=="experimentateur")
            {  
            if ($BDD) {
                $connexion = $BDD -> prepare("SELECT * FROM experimentateur  WHERE experimentateur.login=:login ");
                       } 
            }
            else  
            {
                
            if ($BDD) {
                    $connexion = $BDD -> prepare("SELECT * FROM utilisateur  WHERE utilisateur.login=:login " );
                      }  
            }
                    $connexion -> bindParam(':login',$_SESSION['login']);
                    $connexion -> execute();
             while ( $tuple = $connexion ->fetch())
                         { ?> 
                <div class="container"> 
                <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 text-center">
                <table> 
                <div class="row">
                <div class="col-md-12 col-xs-8 text-center">
                <tr> <label for="login"> Votre login : </label> <input type="text" name="login" id="login" value=<?php echo $tuple['login'] ?> disabled /> <br/> </tr>
                </div> 
                </div>
                <br/>
                <div class="row">
                <div class="col-md-12 col-xs-8 text-center">
                <tr> <label for="mdp"> Votre mot de passe : </label> <input type="password" name="mdp" id="mdp" value=<?php echo $tuple['mdp']?> /> <br/> </tr> 
                </div> 
                </div>
                <br/>
                <div class="row">
                <div class="col-md-12 col-xs-8 text-center">
                <tr> <label for="mail"> Votre adresse-email : </label> <input type="email" name="mail" id="mail" value=<?php echo $tuple['mail'] ?> /> <br/> </tr>
                </div> 
                </div>
                <br/>     
                <?php
                if ($_SESSION['role']=="experimentateur")
                {
                    ?>
                    <div class="row"> 
                    <div class="col-md-12 col-xs-8 text-center">
                    <tr> <label for="orga"> Votre organisme : </label> <input type="text" name="orga" id="orga" value=<?php echo $tuple['organisme'] ?> /> <br/>  </tr> 
                    </div>
                    </div>
               <?php } 
                else { ?>
                     <div class="row"> 
                     <div class="col-md-12 col-xs-8 text-center">
                     <tr> <label for="date_n"> Votre date de naissance : </label> <input type="date" name="date_n" id="date_n" value=<?php echo $tuple['date_naissance'] ?> /> <br/>   </tr>
                     </div> 
                     </div>
                    <br/>
                    <div class="row">
                    <div class="col-md-12 col-xs-8 text-center">
                    <tr> <label for="genre"> Votre genre : </label> 
                    <input type="radio" name="genre" id="feminin" value="1" <?php if($tuple['genre']=="1") {echo' CHECKED="checked"';} ?>/>
                    <label for= "1"> Féminin  </label>
                    <input type="radio" name="genre" id="2=masculin"  value="2" <?php if($tuple['genre']=="2") {echo' CHECKED="checked"';} ?>/>
                    <label for= "2"> Masculin </label>
                    <input type="radio" name="genre" id="autre" value="3"  <?php if($tuple['genre']=="3") {echo' CHECKED="checked"';} ?>/>
                    <label for="3"> Autre </label>

                    <br/>
                    </tr>
                    </div> 
                    </div>
                    <br/>
                    <div class="row">
                    <div class="col-md-12 col-xs-8 text-center">
                    <tr> <label for="nation"> Votre nationalité : </label> <input type="text" name="nation" id="nation" value=<?php echo $tuple['nation'] ?> /> <br/>  </tr> 
                    </div>
                    </div>
                </table>
                </div>
                </div>
                
                        <?php }//fin du else
            } //fin du fetch 
        
        //fin du 1er else
            
                ?>
        <div class="row">
            <div class="col-md-12 col-xs-8 text-center">
            <input type="submit" name="btn_envoi" id="btn_envoi" value="Modifier"/>
            <input type="reset" value= " Annuler "/>
            </div>
        </div> 
        </form>
        
        <?php }
        echo "<br>";
        echo "<br>";
        include('Includes\footer.html');
        ?>
    </body>
</html>