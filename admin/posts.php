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
                            Visualizar Posts
                        </h1>
                        <?php
                        if(isset($_GET['source'])){
                            $source = $_GET['source'];
                        } else{
                            $source = '';    
                        }
                        
                        switch($source){
                            case 'add_post';
                            include "includes/add_post.php";
                            break;
                            
                            case 'edita_post';
                            include "includes/edita_post.php";
                            break;
                                
                            case '200';
                            echo "Nice 200";
                            break;
                            
                            default:
                                
                            include "includes/visual_posts.php";
                                
                            break;
                            
                        }                       
                        
                        
                        ?>
                    </div>
                    
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
        
<?php include "includes/adm_footer.php"; ?>