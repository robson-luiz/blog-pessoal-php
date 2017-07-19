<form action="" method="post">
    <div class="form-group">
       <label for="cat-titulo">Editar Categoria</label>
       <?php
        if(isset($_GET['edita'])){
           $cat_id = $_GET['edita'];
           $query = "SELECT * FROM categorias WHERE id = $cat_id";
           $select_categorias = mysqli_query($connection, $query);

           while($row = mysqli_fetch_assoc($select_categorias)){
           $cat_id = $row['id'];
           $cat_titulo = $row['cat_titulo'];

           ?>
           <input value="<?php if(isset($cat_titulo)){echo $cat_titulo;} ?>" type="text" class="form-control" name="cat_titulo">                                       

            <?php } ?>
        <?php } ?>

        <?php 
        // Edita/atualiza categoria
        if(isset($_POST['edita_cat'])){
        $cat_titulo = $_POST['cat_titulo'];
        $query = "UPDATE categorias SET cat_titulo = '{$cat_titulo}' WHERE id = {$cat_id}";

        $edita_query = mysqli_query($connection, $query);
            if(!$edita_query){
                die("Falha na edição" . mysqli_error($connection));
            }
        }

        ?>


    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edita_cat" value="Editar Categoria">
    </div>
</form>