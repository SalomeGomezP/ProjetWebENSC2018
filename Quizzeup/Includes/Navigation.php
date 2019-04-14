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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="lib/bootstrap/js/bootstrap.min.js"></script>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-education"></span>Quizzeup</a>
            </div>
            
            <div class="collapse navbar-collapse navbar-right" id="navbar-collapse-target">
                <ul class="nav navbar-nav">
                <?php
                                if($_SESSION['connecté']==true) 
                                {   
                                    //Amelioration possible : mettre des raccourcis                                   
                                }
                ?>
                    <li class="dropdown"> 
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user">
                            </span>
                            <?php
                                if($_SESSION['connecté']==true) 
                                {   
                                    echo "Bienvenue, ";
                                    echo $_SESSION['login'];
                                }
								
                                else 
                                {
                                    echo "Non connecté";
                                }
                            ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                        <?php
                                if($_SESSION['connecté']==true) 
                                {   
                                    echo "<li><a href=\"infosCompte.php\"> <span class=\"glyphicon glyphicon-info-sign\"></span>Mes informations</a></li>\n";
                                    echo "<li><a href=\"logout.php\"> <span class=\"glyphicon glyphicon-log-out\"></span>Se déconnecter</a></li>\n";
                                }
                                else 
                                {
                                    echo "<li><a href=\"login.php\"> <span class=\"glyphicon glyphicon-log-in\"></span>Se connecter</a></li>";
                                    echo "<li><a href=\"inscription.php\"> <span class=\"glyphicon glyphicon-pencil\"></span>S'inscrire</a></li>";
                                }
                            ?>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>