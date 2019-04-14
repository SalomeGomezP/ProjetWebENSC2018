<?php
require("Includes\connect.php"); 
                    if ($BDD) {
                        //On supprime toutes les informations liées à la campagne dans la bdd (c'est à dire dans campagne, repondre, administrer, campagne, experience)
                        $connexion = $BDD -> prepare('SET FOREIGN_KEY_CHECKS=0');
                        $connexion -> execute();
                        $id_exp=$_POST['id_exp'];
                        $connexion1 = $BDD -> prepare("SELECT * FROM campagne WHERE id_exp=:id_exp ");
                        $connexion1 -> bindParam(':id_exp',$id_exp);
                        $connexion1 -> execute();
                        while ($tuple=$connexion1->fetch()){
                            $connexion2 = $BDD -> prepare("DELETE FROM repondre WHERE id_campagne=:id_campagne ");
                            $connexion2 -> bindParam(':id_campagne',$tuple['id_campagne']);
                            $connexion2 -> execute();
                        }
                        $connexion3 = $BDD -> prepare("DELETE FROM administrer WHERE id_exp=:id_exp ");
                        $connexion3 -> bindParam(':id_exp',$id_exp);
                        $connexion3 -> execute();

                        $connexion4 = $BDD -> prepare("DELETE FROM campagne WHERE id_exp=:id_exp ");
                        $connexion4 -> bindParam(':id_exp',$id_exp);
                        $connexion4 -> execute();

                        $connexion5 = $BDD -> prepare("DELETE FROM experience WHERE id_exp=:id_exp ");
                        $connexion5 -> bindParam(':id_exp',$id_exp);
                        $connexion5 -> execute();

                        $connexion6 = $BDD -> prepare('SET FOREIGN_KEY_CHECKS=1');
                        $connexion6 -> execute();
                        
                        header('Location: index.php');
                     }

?>