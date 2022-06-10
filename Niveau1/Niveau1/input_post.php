<?php
$enCoursDeTraitement = isset($POST['content']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $usId = $POST['pseudo'];
                        $usPostContent = $POST['message'];
                        // echo "<pre>" . print_r($post, 1) . "</pre>"; 

                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $usId = intval($mysqli->real_escape_string($authorId));
                        $usPostContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $usId . ", "
                                . "'" . $usPostContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;

                        echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que :" . $listAuteurs[$usId];
                        }
                    }
                    ?>

                     <form action="input_post.php" method="post">
                        <input type='hidden' name='???' value='achanger'>
                       
                        <dl>
                           <!-- <dt><label for='auteur'>Auteur</label></dt>
                            <dd><select name='auteur'>
                                </select></dd>-->
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                            
                        </dl>
                        <input type='submit'>
                        
                    </form>   