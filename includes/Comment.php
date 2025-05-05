<?php

class Comment {
    private $conn;
    private $table_name = "comments";

    public $id;
    public $post_id;
    public $author;
    public $content;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ler todos os comentários de um post
    public function readByPost() {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE post_id = ? AND status = 'approved' 
                 ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->post_id);
        $stmt->execute();
        return $stmt;
    }

    // Criar um novo comentário
    public function create($post_id, $author, $content) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (post_id, author, content, status, created_at) 
                 VALUES (:post_id, :author, :content, 'pending', NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":post_id", $post_id);
        $stmt->bindParam(":author", $author);
        $stmt->bindParam(":content", $content);
        
        return $stmt->execute();
    }

    // Aprovar um comentário
    public function approve() {
        $query = "UPDATE " . $this->table_name . "
                SET status = 'approved'
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Rejeitar um comentário
    public function reject() {
        $query = "UPDATE " . $this->table_name . "
                SET status = 'rejected'
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Deletar um comentário
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getTotalComments() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRecentComments($limit = 5) {
        $query = "SELECT c.*, p.title as post_title 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN posts p ON c.post_id = p.id 
                 ORDER BY c.created_at DESC 
                 LIMIT " . (int)$limit;
        
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllComments($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT c.*, p.title as post_title 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN posts p ON c.post_id = p.id 
                 ORDER BY c.created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentById($id) {
        $query = "SELECT c.*, p.title as post_title 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN posts p ON c.post_id = p.id 
                 WHERE c.id = :id 
                 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $author, $content, $status) {
        $query = "UPDATE " . $this->table_name . " 
                 SET author = :author, 
                     content = :content, 
                     status = :status, 
                     updated_at = NOW() 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":author", $author);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":status", $status);
        
        return $stmt->execute();
    }

    public function getCommentsByPost($post_id, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT c.*, p.title as post_title 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN posts p ON c.post_id = p.id 
                 WHERE c.post_id = :post_id AND c.status = 'approved' 
                 ORDER BY c.created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCommentsByPost($post_id) {
        $query = "SELECT COUNT(*) as total 
                 FROM " . $this->table_name . " 
                 WHERE post_id = :post_id AND status = 'approved'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
} 