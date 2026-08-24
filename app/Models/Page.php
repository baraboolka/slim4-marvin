<?php

declare(strict_types = 1);

namespace Models;

class Page extends Basic {

    public $tableName = "pages";

    /**
     * Edit page
     * 
     * @param   array   $value  Page data array
     * 
     * @return  int             Edited page id
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
     * Add page
     * 
     * @param   array   $value  Page data array
     * 
     * @return  int             New page id
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

        $userId = $this->pdo->lastInsertId();

		return (int)$userId;
        
    }

    /**
     * delete page
     * 
     * @param   int $value  Page id
     * 
     * @return  int         Deleted page id
     */     
    public function delete(int $value): int {

        $sql = "DELETE FROM " . $this->tableName . " WHERE `id` = ?";
        
        $query = $this->pdo->prepare($sql);
        $query->execute(array($value)); 

        return $value;

	}        
}