<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Auto</th>
            <th>Titulo</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Imagem</th>
            <th>Tags</th>
            <th>Comentarios</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
       <?php
        $query = "SELECT * FROM posts";
        $select_posts = mysqli_query($connection, $query);

        while($row = mysqli_fetch_assoc($select_posts)){
        $post_id = $row['post_id'];
        $post_autor = $row['post_autor'];
        $post_titulo = $row['post_titulo'];
        $post_categoria_id = $row['post_categoria_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];

        echo "<tr>";    
        echo "<td>{$post_id}</td>";
        echo "<td>{$post_autor}</td>";
        echo "<td>{$post_titulo}</td>";
        echo "<td>{$post_categoria_id}</td>";
        echo "<td>{$post_status}</td>";
        echo "<td><img width='100' src='../images/$post_image' alt='image'></td>";
        echo "<td>{$post_tags}</td>";
        echo "<td>{$post_comment_count}</td>";
        echo "<td>{$post_date}</td>";
        echo "<td><a href='posts.php?source=edita_post&p_id={$post_id}'>Editar</a></td>";
        echo "<td><a href='posts.php?apaga={$post_id}'>Apagar</a></td>";        
        echo "</tr>";


        }

        ?>
        
    </tbody>
</table>

<?php
if(isset($_GET['apaga'])){
    $apaga_post_id = $_GET['apaga'];
    
    $query = "DELETE FROM posts WHERE post_id = {$apaga_post_id} ";
    $apaga_query = mysqli_query($connection, $query);
    header("Location: posts.php");
    
}

?>
