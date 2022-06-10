<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <?php include("header.php"); ?>
    </header>
    <div id="wrapper">
        <?php
        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */

        $userId = intval($_GET['user_id']);

        /**
         * Etape 2: se connecter à la base de donnée
         */
        include("db_connection.php");
        ?>

        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
             //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                    (n° <?php echo $userId ?>)
                </p>

              <?php include ("subButton.php") ?> 
            </section>
        </aside>
        <main>
            <?php


            $listAuteurs = [];
            $laQuestionEnSql = "SELECT * FROM users";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            while ($user = $lesInformations->fetch_assoc()) {
                $listAuteurs[$user['id']] = $user['alias'];
            }


            /**
             * TRAITEMENT DU FORMULAIRE
             */
            // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
            // si on recoit un champs email rempli il y a une chance que ce soit un traitement
            $enCoursDeTraitement = isset($_POST['auteur']);
            if ($enCoursDeTraitement) {
                // on ne fait ce qui suit que si un formulaire a été soumis.
                // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                // et complétez le code ci dessous en remplaçant les ???
                $authorId = $_POST['auteur'];
                $postContent = $_POST['message'];


                //Etape 3 : Petite sécurité
                // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                $authorId = intval($mysqli->real_escape_string($authorId));
                $postContent = $mysqli->real_escape_string($postContent);
                //Etape 4 : construction de la requete
                $lInstructionSql = "INSERT INTO posts "
                    . "(id, user_id, content, created, parent_id) "
                    . "VALUES (NULL, "
                    . $userId . ", "
                    . "'" . $postContent . "', "
                    . "NOW(), "
                    . "NULL);"
                    . "";

                // echo $lInstructionSql;
                // Etape 5 : execution
                $ok = $mysqli->query($lInstructionSql);
                if (!$ok) {
                    echo "Impossible d'ajouter le message: " . $mysqli->error;
                } else {
                    echo "Message posté en tant que :" . $listAuteurs[$authorId];
                }
            }
            ?>
            <form action="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>" method="post">
                <input type='hidden' name='auteur' value='<?php $_SESSION['connected_id'] ?>'>
                <dl>

                    <dt><label for='auteur'></label></dt>
                    <!--<dd><select name='auteur'>
                                                        
                                                <?php foreach ($listAuteurs as $user_id => $alias)
                                                    echo "<option value='$id'>$alias</option>"
                                                ?>
                                                            
                                                    </select></dd>-->
                    <dt><label for='message'>Message</label></dt>
                    <dd><textarea name='message'></textarea></dd>
                </dl>
                <input type='submit'>
            </form>


            <?php
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */
            while ($post = $lesInformations->fetch_assoc()) {

                 // echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article>
                    <?php
                    include("post.php")
                    ?>
                    </footer>
                </article>
            <?php } ?>


        </main>
    </div>
</body>

</html>