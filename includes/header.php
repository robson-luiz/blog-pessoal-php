<?php
if (!isset($title)) {
    $title = $config['site_name'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $config['site_description']; ?>">
    <meta name="author" content="<?php echo $config['site_name']; ?>">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">   
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    
    
    <!-- Dark Mode Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;
            
            // Verificar preferência salva
            if (localStorage.getItem('darkMode') === 'enabled') {
                body.classList.add('dark-mode');
                if(darkModeToggle) darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
            
            // Toggle dark mode
            if(darkModeToggle) darkModeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                    darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                }
            });
        });
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo $config['site_name']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorias
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                            $query = "SELECT c.id, c.name, COUNT(p.id) as post_count 
                                     FROM categories c 
                                     LEFT JOIN posts p ON c.id = p.category_id 
                                     WHERE p.status = 'published' 
                                     GROUP BY c.id, c.name 
                                     HAVING post_count > 0 
                                     ORDER BY c.name";
                            $stmt = $connection->query($query);
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li><a class="dropdown-item" href="category.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="admin">Admin</a></li>
                    <li class="nav-item">
                        <button id="darkModeToggle" class="btn btn-link nav-link"><i class="fas fa-moon"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- O conteúdo principal começa no index.php, não abrir container aqui! -->
</body>

</html>