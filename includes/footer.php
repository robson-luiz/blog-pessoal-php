<?php
if (!isset($footer_sections)) {
    try {
        $footer_sections = $connection->query("SELECT * FROM footer_sections ORDER BY type")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $footer_sections = [];
    }
}
?>

<!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <div class="row">
                <?php foreach ($footer_sections as $section): 
                    $content = $section['type'] === 'social_media' ? json_decode($section['content'], true) : $section['content'];
                ?>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white"><?php echo htmlspecialchars($section['title']); ?></h5>
                    <?php if ($section['type'] === 'quick_links'): ?>
                        <ul class="list-unstyled">
                            <li><a href="index.php" class="text-white"><i class="fas fa-home me-2"></i>Home</a></li>
                            <li><a href="<?php echo $config['base_url']; ?>admin/login.php" class="text-white"><i class="fas fa-user-shield me-2"></i>Admin</a></li>
                        </ul>
                    <?php elseif ($section['type'] === 'social_media'): ?>
                        <div class="social-links">
                            <?php if (!empty($content['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($content['facebook']); ?>" class="text-white me-3" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-facebook-f fa-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($content['twitter'])): ?>
                                <a href="<?php echo htmlspecialchars($content['twitter']); ?>" class="text-white me-3" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-twitter fa-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($content['instagram'])): ?>
                                <a href="<?php echo htmlspecialchars($content['instagram']); ?>" class="text-white me-3" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-instagram fa-lg"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($content['linkedin'])): ?>
                                <a href="<?php echo htmlspecialchars($content['linkedin']); ?>" class="text-white" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-linkedin-in fa-lg"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-white-50"><?php echo nl2br(htmlspecialchars($content)); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> Blog Pessoal. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
        </footer>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Ativar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>