<?php

class Post {
    private $conn;
    private $table_name = "posts";

    public $id;
    public $title;
    public $content;
    public $category_id;
    public $status;
    public $created_at;
    public $updated_at;
    public $category_name;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ler todos os posts
    public function read() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.status = 'published'
                 ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ler um único post
    public function readOne() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->image = $row['image'];
            return true;
        }
        return false;
    }

    // Criar um novo post
    public function create($title, $content, $category_id, $image = null, $status = 'draft') {
        $query = "INSERT INTO " . $this->table_name . " 
                 (title, content, category_id, image, status, created_at) 
                 VALUES (:title, :content, :category_id, :image, :status, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":status", $status);
        
        return $stmt->execute();
    }

    // Atualizar um post
    public function update($id, $title, $content, $category_id, $status, $image = null) {
        $query = "UPDATE " . $this->table_name . " 
                 SET title = :title, 
                     content = :content, 
                     category_id = :category_id, 
                     status = :status,
                     image = :image, 
                     updated_at = NOW() 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":image", $image);
        
        return $stmt->execute();
    }

    // Deletar um post
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getPostById($id) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.id = :id 
                 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPosts($page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 ORDER BY p.created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalPosts() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRecentPosts($limit = 5) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 ORDER BY p.created_at DESC 
                 LIMIT " . (int)$limit;
        
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostsByCategory($category_id, $page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.category_id = :category_id 
                 ORDER BY p.created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchPosts($keyword, $page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;
        $keyword = "%$keyword%";
        
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.title LIKE :keyword OR p.content LIKE :keyword 
                 ORDER BY p.created_at DESC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":keyword", $keyword);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 