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
        <title> Créer une campagne </title>
    </head>
    <body>
        <?php include('Includes\Navigation.php');
        echo"<br>";
        echo"<br>";
        echo"<br>";

        if (!empty($_POST['nomCampagne']))//traitement du post
        {
          require("Includes\connect.php"); 
          if ($BDD) {
            if($_POST['typeCampagne']=='individuelle'){
                $InterfaceDescription=$_POST['description'];
            }
            if($_POST['typeCampagne']=='comparative'){
                $InterfaceDescription=$_POST['description'];
            }
            //Pour creer une campagne il faut enregistrer des informations dans experience, campagne  et administrer
            //insertion dans experience des informations necessaires
            $stmt = $BDD->prepare ("INSERT INTO experience(statut,nom_exp,description,nb_partic,derniere_classe) VALUES(:statut,:nom_exp,:description,:nb_partic, :derniere_classe)");
            $stmt -> execute(array(
            'statut' => 0,
            'nom_exp' => $_POST['nomCampagne'],
            'nb_partic' => $_POST['nbreParticipants'],
            'description' => $InterfaceDescription,
            'derniere_classe'=>1
            ));

            $MaRequete="SELECT * FROM experience" ;
            $connexion = $BDD -> query( $MaRequete );
            $id_exp=0;
            while($experience=$connexion->fetch()){
                if($experience['id_exp']>$id_exp){
                    $id_exp=$experience['id_exp'];//on recupere id_exp qui a été auto incrementé
                }
            }
            $id_questionnaire=1;
            $MaRequete2="SELECT * FROM campagne" ;
            $connexion2 = $BDD -> query( $MaRequete2 );
            $id_campagne=0;
            while($campagne=$connexion2->fetch()){
                if($campagne['id_campagne']>$id_campagne){
                    $id_campagne=$campagne['id_campagne'];//on recupere le derniere id_campagne auto incrementé
                }
            }

            if($_POST['typeCampagne']=='individuelle'){//si la campagne est individuelle, on enregistre 1 seule ligne dans CAMPAGNE
                $stmt = $BDD->prepare ("INSERT INTO campagne(id_campagne, id_questionnaire,type_campagne,id_exp, nom_interface) VALUES(:id_campagne, :id_questionnaire,:type_campagne,:id_exp, :nom_interface)");
                $stmt -> execute(array(
                    'id_campagne'=>$id_campagne+1,
                    'id_questionnaire' =>$id_questionnaire,
                    'type_campagne' => $_POST['typeCampagne'],
                    'id_exp' =>$id_exp,
                    'nom_interface'=>$_POST['nomInterface']
                    ));
            }
            else{//Sinon il faut enregistrer les informations correspondant aux 2 interfaces
                $stmt = $BDD->prepare ("INSERT INTO campagne(id_campagne,id_questionnaire,type_campagne,id_exp, nom_interface, classe) VALUES(:id_campagne,:id_questionnaire,:type_campagne,:id_exp, :nom_interface, :classe)");
                $stmt -> execute(array(
                    'id_campagne'=>$id_campagne+1,
                    'id_questionnaire' =>$id_questionnaire,
                    'type_campagne' => $_POST['typeCampagne'],
                    'id_exp' =>$id_exp,
                    'nom_interface'=>$_POST['nomInterface1'],
                    'classe'=>'A'
                    ));
                $stmt = $BDD->prepare ("INSERT INTO campagne(id_campagne,id_questionnaire,type_campagne,id_exp, nom_interface, classe) VALUES(:id_campagne,:id_questionnaire,:type_campagne,:id_exp, :nom_interface, :classe)");
                $stmt -> execute(array(
                    'id_campagne'=>$id_campagne+2,
                    'id_questionnaire' =>$id_questionnaire,
                    'type_campagne' => $_POST['typeCampagne'],
                    'id_exp' =>$id_exp,
                    'nom_interface'=>$_POST['nomInterface2'],
                    'classe'=>'B'
                    ));
            }
            //Il faut aussi inserer dans administrer 
            $stmt = $BDD->prepare ("INSERT INTO administrer(id_exp,login) VALUES(:id_exp,:login)");
            $stmt -> execute(array(
                'id_exp' =>$id_exp,
                'login' => $_SESSION['login'],
                ));
            }


        header('Location: index.php');
        
        } 

        ?>
        
        <div class="container">
            <form id="formulaire" method ="POST" action="creerCampagne.php">
                <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1 text-center panel panel-group"> 
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><h2>Création de campagne</h2></div>
                        <div class="panel panel-body">
                            <label for="nomCampagne" class="sr-only">Nom</label>
                            <input type="text" name="nomCampagne" id="nomCampagne" class="form-control" placeholder="Entrez un nom pour votre campagne" required autofocus>
                            <br>
                            <label for="nbreParticipants" class="sr-only">Nb participants</label>
                            <input type="number" min="2" name="nbreParticipants" id="nbreParticipants" class="form-control" placeholder="Entrez un nombre de participants"  required>
                            <br>
                            <br>
                            <p>Souhaitez-vous réaliser une étude comparative entre 2 interfaces ou une étude individuelle sur une interface ?</p>
                            <input type="radio" name="typeCampagne" id="comparative" value="comparative">
                            <label for="experimentateur">étude comparative </label>
                            <input type="radio" name="typeCampagne" id="individuelle" value="individuelle">
                            <label for="utilisateur">étude individuelle </label>
                            <hr><br>
                            <p id="test"></p>
                            <button class="btn btn-secondary btn-block btn-lg " type="submit">Créer </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <?php include('Includes\footer.html');//footer
        ?>
    </body>

</html>
<script>//affiche un different reste du formulaire selon si la camapgne est individuelle ou non (on demande le nom d'une ou des deux interfaces selon le cas)
    var element = document.getElementById('individuelle');
    element.onclick = function() {
    var formulaire='<label for="nom" class="sr-only">Nom</label><input type="text" name="nomInterface" id="nomInterface" class="form-control" placeholder="Entrez le nom de l\'interface testée" required autofocus><br><textarea form ="formulaire" name="description" id="description" placeholder="Entrez une description de la campagne qui sera fournie aux personnes testées" style="width:100%" wrap="soft"></textarea></div></div></div>';                         
    var implementation=document.getElementById('test');
    implementation.innerHTML=formulaire;
                    
    };
</script>
<script>
    var element = document.getElementById('comparative');
    element.onclick = function() {
    var formulaire='<label for="nom" class="sr-only">Nom</label><input type="text" name="nomInterface1" id="nomInterface" class="form-control" placeholder="Entrez le nom de la premiere interface testée" required autofocus><br><label for="nom" class="sr-only">Nom</label><input type="text" name="nomInterface2" id="nomInterface" class="form-control" placeholder="Entrez le nom de la seconde interface testée" required autofocus><br><textarea form ="formulaire" name="description" id="description" placeholder="Entrez une description de la campagne qui sera fournie aux personnes testées" style="width:100%" wrap="soft"></textarea></div></div></div>';                         
    var implementation=document.getElementById('test');
    implementation.innerHTML=formulaire;
                    
    };
</script>

