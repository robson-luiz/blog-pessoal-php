<?php
if(isset($_POST['criar_post'])){
    
    $post_titulo = $_POST['post_titulo'];
    $post_autor = $_POST['post_autor'];
    $post_categoria_id = $_POST['post_categoria_id'];
    $post_status = $_POST['post_status'];
    
    
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    
    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    $post_date = date('d-m-y');
    $post_comment_count = 4;
    
    move_uploaded_file($post_image_temp, "../images/$post_image");
    
    $query = "INSERT INTO posts(post_titulo, post_autor, post_categoria_id, post_status, post_image, post_tags, post_content, post_date, post_comment_count)";
    
    $query .= "VALUES('{$post_titulo}', '{$post_autor}',{$post_categoria_id},'{$post_status}','{$post_image}','{$post_tags}','{$post_content}', now(),'{$post_comment_count}' )";
    
    $criar_post_query = mysqli_query($connection, $query);
    
    confirmaQuery($criar_post_query);
    
}

?>
   <form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="titulo">Titulo</label>
        <input type="text" class="form-control" name="post_titulo">
    </div>
    
    <div class="form-group">
        <label for="post_category">Id Categoria</label>
        <input type="text" class="form-control" name="post_categoria_id">
    </div>
    
    <div class="form-group">
        <label for="titulo">Autor</label>
        <input type="text" class="form-control" name="post_autor">
    </div>
    
    <div class="form-group">
        <label for="titulo">Status</label>
        <input type="text" class="form-control" name="post_status">
    </div>
    
    <div class="form-group">
        <label for="post_image">Image</label>
        <input type="file" name="image">
    </div>
    
    <div class="form-group">
        <label for="titulo">Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>   
    
    <div class="form-group">
        <label for="post_content">Conteudo</label>
        <textarea class="form-control" name="post_content" id="" cols="30" rows="10">            
        </textarea>
    </div>
    
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="criar_post" value="Publicar Post">
    </div>    
    
    
    
</form>