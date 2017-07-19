<?php

if(isset($_GET['p_id'])){
    $posted_id = $_GET['p_id'];
}
        $query = "SELECT * FROM posts WHERE post_id = $posted_id ";
        $select_posts_id = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($select_posts_id)){
        $post_id = $row['post_id'];
        $post_autor = $row['post_autor'];
        $post_titulo = $row['post_titulo'];
        $post_categoria_id = $row['post_categoria_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];

        }

if(isset($_POST['edita_post'])){
    
    $post_autor = $_POST['post_autor'];
    $post_titulo = $_POST['post_titulo'];    
    $post_categoria_id = $_POST['post_categoria'];
    $post_status = $_POST['post_status'];
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $post_content = $_POST['post_content'];
    $post_tags = $_POST['post_tags'];    
    
    move_uploaded_file($post_image_temp, "../images/$post_image");
    
    if(empty($post_image)){
        $query = "SELECT * FROM posts WHERE post_id = $posted_id ";
        $select_image = mysqli_query($connection, $query);
        
        while($row = mysqli_fetch_array($select_image)){
            $post_image = $row['post_image'];
        }
    }
    
    $query = "UPDATE posts SET ";
    $query .="post_autor = '{$post_autor}', ";
    $query .="post_titulo = '{$post_titulo}', ";    
    $query .="post_categoria_id = '{$post_categoria_id}', ";
    $query .="post_status = '{$post_status}', ";
    $query .="post_image = '{$post_image}', ";    
    $query .="post_content = '{$post_content}', ";
    $query .="post_tags = '{$post_tags}', ";
    $query .="post_date = now() ";
    $query .= "WHERE post_id = {$posted_id} ";
    
    $atualiza_post = mysqli_query($connection, $query);
    
    confirmaQuery($atualiza_post);
}

?>
   <form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="titulo">Titulo</label>
        <input value="<?php echo $post_titulo; ?>" type="text" class="form-control" name="post_titulo">
    </div>
    
    <div class="form-group">
        <select name="post_categoria" id="">
            <?php 
            $query = "SELECT * FROM categorias";
            $select_categorias = mysqli_query($connection, $query);
            
            confirmaQuery($select_categorias);

            while($row = mysqli_fetch_assoc($select_categorias)){
            $cat_id = $row['id'];
            $cat_titulo = $row['cat_titulo'];
                echo "<option value='$cat_id'>{$cat_titulo}</option>";
               
           }    
            
            ?>            
        </select>
    </div>
    
    <div class="form-group">
        <label for="titulo">Autor</label>
        <input value="<?php echo $post_autor; ?>" type="text" class="form-control" name="post_autor">
    </div>
    
    <div class="form-group">
        <label for="titulo">Status</label>
        <input value="<?php echo $post_status; ?>" type="text" class="form-control" name="post_status">
    </div>
    
    <div class="form-group">
        <img width="100" src="../images/<?php echo $post_image; ?>" alt="">
    </div>
    
    <div class="form-group">
        <label for="post_image">Image</label>
        <input type="file" name="image">
    </div>
    
    <div class="form-group">
        <label for="titulo">Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>   
    
    <div class="form-group">
        <label for="post_content">Conteudo</label>
        <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo $post_content; ?>            
        </textarea>
    </div>
    
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edita_post" value="Editar Post">
    </div>    
    
    
    
</form>