<?php

declare(strict_types = 1);

namespace Models;

class Gallery extends Basic {

    public $tableName = "galleries";
    public $tableImagesName = "galleries_images";

    /**
     * Edit gallery
     * 
     * @param   array   $value  Gallery data array
     * 
     * @return  int             Edited gallery id
     */ 
    public function edit(array $value): int {

        $sql = "UPDATE " . $this->tableName . " SET 
        `title` = :title, 
        `text` = :text, 
        `text_editor` = :text_editor, 
        `alias` = :alias, 
        `seo_title` = :seo_title, 
        `seo_keywords` = :seo_keywords, 
        `seo_description` = :seo_description, 
        `parent` = :parent 
        WHERE `id` = :id";

		$query = $this->pdo->prepare($sql);
        $query->bindParam(':id', $value["id"]);	
		$query->bindParam(':title', $value["title"]);	
		$query->bindParam(':text', $value["text"]);
		$query->bindParam(':text_editor', $value["text_editor"]);
		$query->bindParam(':alias', $value["alias"]); 
		$query->bindParam(':seo_title', $value["seo_title"]);   
		$query->bindParam(':seo_keywords', $value["seo_keywords"]);  
		$query->bindParam(':seo_description', $value["seo_description"]);     
		$query->bindParam(':parent', $value["parent"]);                                          
		$query->execute();

		return (int)$value["id"];

    }

    /**
     * Add gallery
     * 
     * @param   array   $value  Gallery data array
     * 
     * @return  int             New gallery id
     */ 
    public function add(array $value): int {

        $sql = "INSERT INTO " . $this->tableName . " 
        (`title`, `text`, `text_editor`, `alias`, `seo_title`, `seo_keywords`, `seo_description`, `parent`) 
        VALUES 
        (:title, :text, :text_editor, :alias, :seo_title, :seo_keywords, :seo_description, :parent)";

		$query = $this->pdo->prepare($sql);
		$query->bindParam(':title', $value["title"]);	
		$query->bindParam(':text', $value["text"]);
		$query->bindParam(':text_editor', $value["text_editor"]);
		$query->bindParam(':alias', $value["alias"]); 
		$query->bindParam(':seo_title', $value["seo_title"]);   
		$query->bindParam(':seo_keywords', $value["seo_keywords"]);  
		$query->bindParam(':seo_description', $value["seo_description"]);     
		$query->bindParam(':parent', $value["parent"]);  
		$query->execute();

        $galleryId = $this->pdo->lastInsertId();

		return (int)$galleryId;
        
    }

    /**
     * delete gallery
     * 
     * @param   int $value  Gallery id
     * 
     * @return  int         Deleted gallery id
     */     
    public function delete(int $value): int {

        $sql = "DELETE FROM " . $this->tableName . " WHERE `id` = ?";
        
        $query = $this->pdo->prepare($sql);
        $query->execute(array($value)); 

        return $value;

	}    
    
    /**
     * Get images by gallery id
     * 
     * @param   int         $value  Gallery id      
     * 
     * @return  array|null          Array of the images or null
     */   
    public function getImages(int $value): ?array {

		$sql = "SELECT * FROM " . $this->tableImagesName . " WHERE parent = :parent ORDER BY main DESC";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':parent', $value);
		$query->execute();
		
		$result = $query->fetchAll();

        return is_array($result) ? $result : null;

    }

    /**
     * Add image
     * 
     * @param   array   $value  Image data array
     * 
     * @return  int             New image id
     */ 
    public function addImage(array $value): int {

        $sql = "INSERT INTO " . $this->tableImagesName . " 
        (`file`, `parent`) 
        VALUES 
        (:file, :parent)";

		$query = $this->pdo->prepare($sql);
		$query->bindParam(':file', $value["file"]);	
		$query->bindParam(':parent', $value["parent"]);  
		$query->execute();

        $imageId = $this->pdo->lastInsertId();

		return (int)$imageId;
        
    }

    /**
     * edit image
     * 
     * @param   array   $value  Image data array
     * 
     * @return  int             Edited image id
     */ 
    public function editImage(array $value): int {

        $sql = "UPDATE " . $this->tableImagesName . " SET 
        `title` = :title,  
        `main` = :main  
        WHERE `id` = :id";

		$query = $this->pdo->prepare($sql);
		$query->bindParam(':title', $value["title"]);	
		$query->bindParam(':main', $value["main"]);    
		$query->bindParam(':id', $value["id"]);                                          
		$query->execute();

		return (int)$value["id"];
        
    }  

    /**
     * delete image
     * 
     * @param   array   $value  Image id
     * 
     * @return  string          Deleted image name or null
     */ 
    public function deleteImage(int $value): ?string {

        $sql = "SELECT * FROM " . $this->tableImagesName . " WHERE `id` = ? ";

		$query = $this->pdo->prepare($sql);
		$query->execute(array($value));
		
		$image = $query->fetch();

        $sqlDelete = "DELETE FROM " . $this->tableImagesName . " WHERE `id` = ?";
        
        $query = $this->pdo->prepare($sqlDelete);
        $query->execute(array($value)); 

        return is_array($image) ? $image["file"] : null;
        
    }    
}