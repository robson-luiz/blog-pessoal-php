<?php include "includes/adm_header.php"; ?>

    <div id="wrapper">
        
        

        <!-- Navigation -->
        <?php include "includes/adm_menu.php"; ?>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Visualizar Categorias
                            <small></small>
                        </h1>
                        <div class="col-xs-6">
                           <?php inserir_categorias(); ?>
                           
                            <form action="" method="post">
                                <div class="form-group">
                                   <label for="cat-titulo">Add Categoria</label>
                                    <input type="text" class="form-control" name="cat_titulo">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" name="submit" value="Add Categoria">
                                </div>
                            </form>
                            <?php //Atualização e inclusão da query
                            if(isset($_GET['edita'])){
                                $cat_id = $_GET['edita'];
                                
                                include "includes/edita_categoria.php";
                            }                            
                            ?>                            
                        </div><!---Add Categoria---->
                        
                        <div class="col-xs-6">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Titulo da Categoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php buscaCategorias(); ?>
                                    
                                   <?php apagaCategorias(); ?>
                                </tbody>
                            </table>
                        </div>                                                                        
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
        
<?php include "includes/adm_footer.php"; ?>