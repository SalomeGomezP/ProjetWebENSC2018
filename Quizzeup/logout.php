<?php if(!session_id()) 
        { 
            session_start();
        } 

?>
<?php //On retire les informations dans $_SESSION
    $_SESSION['connecté']=false;
    $_SESSION['login']="";
    $_SESSION['role']="";
    header('Location: index.php?connexion=1');
?>