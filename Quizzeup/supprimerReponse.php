<?php
require("Includes\connect.php"); 
                    if ($BDD) {
                        //On supprime toutes les réponses à une campagne d'un utilisateur 
                        $connexion = $BDD -> prepare('SET FOREIGN_KEY_CHECKS=0');
                        $connexion -> execute();
                        $id_exp=$_POST['id_exp'];
                        $connexion1 = $BDD -> prepare("SELECT * FROM campagne WHERE id_exp=:id_exp ");
                        $connexion1 -> bindParam(':id_exp',$id_exp);
                        $connexion1 -> execute();
                        while ($tuple=$connexion1->fetch()){
                            $connexion2 = $BDD -> prepare("DELETE FROM repondre WHERE id_campagne=:id_campagne AND  login=:login");
                            $connexion2 -> bindParam(':id_campagne',$tuple['id_campagne']);
                            $connexion2 -> bindParam(':login',$_POST['utilisateur']);
                            $connexion2 -> execute();
                        }
                        $connexion = $BDD -> prepare('SET FOREIGN_KEY_CHECKS=1');
                        $connexion -> execute();
                        
                        header('Location: index.php');
                     }

?>