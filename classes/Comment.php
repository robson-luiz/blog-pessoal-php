<?php
class Comment {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAllComments() {
        $stmt = $this->pdo->query("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCommentById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getRecentComments($limit = 5) {
        $limit = (int)$limit; // Garante que é um inteiro
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            ORDER BY c.created_at DESC 
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalComments() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM comments");
        return $stmt->fetchColumn();
    }
    
    public function getCommentsByPost($postId) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            WHERE c.post_id = ? AND c.status = 'approved' 
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createComment($postId, $author, $content, $status = 'pending') {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (post_id, author, content, status, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$postId, $author, $content, $status]);
    }
    
    public function updateComment($id, $author, $content, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE comments 
            SET author = ?, content = ?, status = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$author, $content, $status, $id]);
    }
    
    public function deleteComment($id) {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function approveComment($id) {
        $stmt = $this->pdo->prepare("
            UPDATE comments 
            SET status = 'approved', updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    public function getPendingComments() {
        $stmt = $this->pdo->query("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            WHERE c.status = 'pending' 
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 