    <time><?php echo $post['created'] ?></time>
    </h3>
    <address><a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
    <div>
        <p><?php echo $post['content'] ?></p>
    </div>
    <footer>
        <small>♥ <?php echo $post['like_number'] ?></small>

        <?php if (empty($post['taglist'])) {
            echo "<br>";
        } else {
            $arrayTags = explode(',', $post['taglist']);

            foreach ($arrayTags as $tags) {
                $result = $mysqli->query("
                            SELECT id FROM tags WHERE label='$tags'
                            ");

                $row = $result->fetch_array(MYSQLI_NUM);

                //echo "<pre>" . print_r($row, 1) . "</pre>";

               // echo '<a href="tags.php?tag_id=' . $row[0][0] . '">#' . $tags . ' </a>';
            }

            $tagounet ='<a href="tags.php?tag_id=' . $row[0][0] . '">#' . $tags . ' </a>';


        ?>
            <?php echo $tagounet ?>
        <?php
        }
        ?>
        <!-- 
          Faire une requête et itérer dessus 

        $result = $mysqli->query("
                            SELECT *FROM tags WHERE label='$tags'
                            ");
        fetch assoc() -->