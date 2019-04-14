<?php if(!session_id()) 
        { 
            session_start();
        } 
        if (EMPTY($_SESSION['connecté']))
        {
            $_SESSION['connecté']=false;
            $_SESSION['login']="";
        }   
?>

<?php include('Includes\Navigation.php');
    echo"<br>";
    echo"<br>";
    echo"<br>";
    ?>
    <?php
    if (!empty($_POST['login']))
    {
      require("Includes\connect.php"); 
      if ($BDD) {
        $MaRequete="SELECT * FROM EXPERIMENTATEUR";
        if ($_POST['typePersonne']=="utilisateur") {
          $MaRequete="SELECT * FROM UTILISATEUR";
        }
      $connexion = $BDD -> query( $MaRequete );
      }
      $bonlogin=false;
      $bonMDP=false;

      while (( $tuple = $connexion ->fetch() )&&($bonlogin!=true||$bonMDP!=true)) //On parcours tout les logins/mdp pour trouvés si le login et mdp ont une correspondance
          {
              $bonlogin=false;
              $bonMDP=false;
              if ($tuple['login']==$_POST["login"])
              {
                $bonlogin=true;
              }
              if($tuple['mdp']==$_POST["inputPassword"])
              {
                $bonMDP=true;
              }
          }
      if ($bonlogin!=true||$bonMDP!=true)//Si on a pas trouvé un login et mdp correspondant
      {

        unset($_POST);
        include("login.php");
        echo "<div class=\"alert alert-danger\">";
        echo "<strong>Danger!</strong> Identifiant ou mot de passe invalide.</div>";

      }
      else //Sinon on se connecte
      { 
        include('Includes/fonctions.php');
        connexion($_POST['typePersonne'],$_POST["login"]);
        header('Location: index.php');
      }  


    }
    else{
      ?>

      <!DOCTYPE html>
      <html>
     <head>
     <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
       <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="signin.css" rel="stylesheet">
      <title> Connexion </title>
      </head>
      <body> 

      
      <div class="container"><!-- On demande le type de personne -->
        <form  method ="POST" action="login.php">
          <h2 class="form-signin-heading text-center">Connexion</h2>
          <hr>
          <div class="text-center">
          <p class="lead">Vous êtes un :</p>
          <input type="radio" name="typePersonne" id="experimentateur" value="experimentateur">
          <label for="experimentateur">expérimentateur </label>
          <input type="radio" name="typePersonne" id="utilisateur" value="utilisateur">
          <label for="utilisateur">utilisateur </label>
          <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-log-in"></span> Valider </button>
          </div>
        </form>
      </div>
      <?php
        if (!empty($_POST['typePersonne'])){//On demande ensuite le login et mdp

          ?>
            <div class="container">
            <form  method ="POST" action="login.php?">
            <input id="typePersonne" name="typePersonne" type="hidden" value="experimentateur">
            <?php
            if ($_POST['typePersonne']=="utilisateur"){
              ?>
              <input id="typePersonne" name="typePersonne" type="hidden" value="utilisateur">
              <?php
            }
            ?>
            <label for="login" class="sr-only">Login</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Entrez votre login" required autofocus>
            <label for="inputPassword" class="sr-only">Mot de passe</label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Entrez votre mot de passe" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-log-in"></span> Se connecter</button>
            </form>

            </div> 

            <?php include('Includes\footer.html');//footer
            ?>
            </body>
            </html>
            <?php
        }

    }
?>