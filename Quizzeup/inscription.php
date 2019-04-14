<!DOCTYPE html>
  <html>
  <head>
  <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
 <title> Inscription </title>
  </head>
  
  <body>
  
  <?php include('Includes\Navigation.php');
    echo"<br>";
    echo"<br>";
    echo"<br>";
?>
 <?php      

if (!empty($_POST['login'])&&!empty($_POST['inputPassword']))//on insert dans la bdd les informations récoltées dans le formulaire
{
  require("Includes\connect.php"); 
  if ($BDD) {

    if ($_POST['typePersonne']=="experimentateur")
    {
      $stmt = $BDD->prepare ("INSERT INTO experimentateur(login, mdp,mail,organisme) VALUES(:login,:mdp,:mail,:organisme)");
      $stmt -> execute(array(
        'login' => $_POST['login'],
        'mdp' => $_POST['inputPassword'],
        'mail' => $_POST['mail'],
        'organisme' => $_POST['organisme']
        ));
    }

    if ($_POST['typePersonne']=="utilisateur")
    {

      if ($_POST["genre"]=="feminin"){
        $genre=1;
      }
      if ($_POST["genre"]=="masculin"){
        $genre=2;
      }
      if ($_POST["genre"]=="autre"){
        $genre=3;
      }
      $stmt = $BDD->prepare ("INSERT INTO utilisateur(login, mdp,date_naissance,nation,genre,mail) VALUES(:login,:mdp,:date_naissance,:nation,:genre,:mail)");
      $stmt -> execute(array(
        'login' => $_POST['login'],
        'mdp' => $_POST['inputPassword'],
        'date_naissance' => $_POST['dateNaissance'],
        'nation' => $_POST['nationalité'],
        'genre' =>$genre,
        'mail' => $_POST['mail']
        ));
    }
}
  include('Includes/fonctions.php');
  connexion($_POST['typePersonne'],$_POST["login"]);
  header('Location: index.php');

} 



else{

  ?>

      
      <div class="container">
        <form  method ="POST" action="inscription.php">
          <h2 class="form-signin-heading text-center">Inscription</h2>
          <hr>
          <div class="text-center">
          <p class="lead">Vous êtes un :</p><!--On demande d'abord si on a à faire à un experimentateur ou un utilisateur -->
          <input type="radio" name="typePersonne" id="experimentateur" value="experimentateur">
          <label for="experimentateur">expérimentateur </label>
          <input type="radio" name="typePersonne" id="utilisateur" value="utilisateur">
          <label for="utilisateur">utilisateur </label>
          <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-log-in"></span> Valider </button>
          </div>
        </form>
      </div>
        <br>


        
        <?php
          if (!empty($_POST['typePersonne'])){//On demande differentes informations selon si la personnes est utilisatrice ou experimentatrice
            ?>
            <div class="container">
            <form  method ="POST" action="inscription.php">
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
            <br>
            <label for="inputPassword" class="sr-only">Mot de passe</label>
            <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Entrez votre mot de passe" required>
            <br>
            <?php
            if ($_POST['typePersonne']=="experimentateur"){
              ?>
              <div class="checkbox">
              <label for="mail" class="sr-only">E-mail</label>
              <input type="email" name="mail" id="mail" class="form-control" placeholder="Entrez votre e-mail" required>
              <br>
              </div>
              <label for="organisme" class="sr-only">Organisme : </label>
              <input type="text" name="organisme" id="organisme" class="form-control" placeholder="Entrez l'organisme auquel vous appartenez" required autofocus>  
              <br>
              <?php
            }
            else{
              ?>
              <label for="dateNaissance" class="sr-only">Date de naissance : </label>
              <input type="date" name="dateNaissance" id="dateNaissance" placeholder="Entrez votre date de naissance" class="form-control"  required autofocus> 
              <br> 
              <label for="nationalité" class="sr-only">Nationalité : </label>
              <input type="text" name="nationalité" id="nationalité" class="form-control" placeholder="Entrez votre nationalité" required autofocus>  
              <div class="text-center">
              <div class="checkbox">
              <label for="mail" class="sr-only">E-mail</label>
              <input type="email" name="mail" id="mail" class="form-control" placeholder="Entrez votre e-mail" required>
              </div>
                <p class="lead">Genre :</p>
                <input type="radio" name="genre" id="feminin" value="feminin">
                <label for="feminin">Féminin </label>
                <input type="radio" name="genre" id="masculin" value="masculin">
                <label for="masculin">Masculin </label>
                <input type="radio" name="genre" id="autre" value="autre">
                <label for="autre">Autre </label>
                <br>
                <br>
              </div>
            <?php
            }
        ?>

        

            <button class="btn btn-lg btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-log-in"></span> S'inscrire </button>
            <button class="btn btn-lg btn-primary btn-block" type="reset"><span class="	glyphicon glyphicon-refresh"></span> Effacer </button>
    </form>
    </div> 
    <?php
          }
    ?>
    </body></html>
    <?php
  }
   include('Includes\footer.html');//footer
  

?>