<?php include 'includes/db.php'; ?>
    <?php include 'includes/header.php'; ?>

    <!-- Navigation -->
    <?php include 'includes/menu.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php
                $query = "SELECT * FROM posts";
                $select_posts = mysqli_query($connection, $query);
                    while($row = mysqli_fetch_assoc($select_posts)){
                        $post_titulo = $row['post_titulo'];
                        $post_autor = $row['post_autor'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                        
                        ?>
                        <h1 class="page-header">
                            Page Heading
                            <small>Secondary Text</small>
                        </h1>

                        <!-- First Blog Post -->
                        <h2>
                            <a href="#"><?php echo $post_titulo; ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_autor; ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                        <hr>
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <p><?php echo $post_content; ?></p>
                        <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>                

                        <hr>
                
                    
                    <?php } ?>                

                </div>

            

            <!-- Blog Sidebar Widgets Column -->
            <?php include 'includes/sidebar.php'; ?>

        </div>
        <!-- /.row -->

        <hr>
        
<?php include 'includes/footer.php'; ?>       
