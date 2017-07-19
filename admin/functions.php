<?php

function confirmaQuery($resultado){
    global $connection;
    
    if(!$resultado){
        
        die("Falha na query ." . mysqli_error($connection));
    }
    
}

function inserir_categorias(){
    global $connection;
    
    if(isset($_POST['submit'])){
        $cat_titulo = $_POST['cat_titulo'];
        if($cat_titulo == "" || empty($cat_titulo)){
            echo "O campo não pode estar vazio";
        }else{
            $query = "INSERT INTO categorias(cat_titulo)";
            $query.= "VALUE('{$cat_titulo}')";

            $result_categoria = mysqli_query($connection, $query);

            if(!$result_categoria){
                die('Falha' . mysqli_error($connection));
            }
        }
    }
                            
}

function buscaCategorias(){
    global $connection;
    
    $query = "SELECT * FROM categorias";
    $select_categorias = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categorias)){
    $cat_id = $row['id'];
    $cat_titulo = $row['cat_titulo'];

    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_titulo}</td>";
    echo "<td><a href='categorias.php?apaga={$cat_id}'>Apagar</a></td>";
    echo "<td><a href='categorias.php?edita={$cat_id}'>Editar</a></td>";
    echo "</tr>";

    }
    
}

function apagaCategorias(){
    global $connection;
    
    if(isset($_GET['apaga'])){
    $apaga_id = $_GET['apaga'];
    $query = "DELETE FROM categorias WHERE id = {$apaga_id}";

    $apaga_query = mysqli_query($connection, $query);
    header("Location: categorias.php");
        
    }    
    
}

?>