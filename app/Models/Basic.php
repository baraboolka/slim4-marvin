<?php

declare(strict_types = 1);

namespace Models;

use PDO;

class Basic {
    
    public $pdo;
    public $tableName = '';

    public function __construct(PDO $pdo) {

        $this->pdo = $pdo;
    
    }

    /**
     * Get all items
     * 
     * @param   null
     * 
     * @return  array|null  Array of the items or null 
     */
    public function getAll(): ?array {

        $sql = "SELECT * FROM " . $this->tableName . " ORDER BY id ASC";

		$query = $this->pdo->prepare($sql);
		$query->execute();
		
		$result = $query->fetchAll();

        return is_array($result) ? $result : null;

    }

    /**
     * Get item by id
     * 
     * @param   int         $value  Item id      
     * 
     * @return  array|null          Item data or null
     */    
    public function getById(int $value): ?array {

		$sql = "SELECT * FROM " . $this->tableName . " WHERE id = ? ";

		$query = $this->pdo->prepare($sql);
		$query->execute(array($value));
		
		$result = $query->fetch();

        return is_array($result) ? $result : null;

    } 

    /**
     * Get item by alias
     * 
     * @param   string      $value  Item alias      
     * 
     * @return  array|null          Item data or null
     */    
    public function getByAlias(string $value): ?array {

		$sql = "SELECT * FROM " . $this->tableName . " WHERE alias = ? ";

		$query = $this->pdo->prepare($sql);
		$query->execute(array($value));
		
		$result = $query->fetch();

        return is_array($result) ? $result : null;

    }

	/**
	 * Check the table for double aliases, every item can have only one unique alias|url
	 * 
	 * @param 	string	$alias	String for checking
	 * @param	int		$id		Item(id) with this string|alias
	 * 
	 * @return	bool			False - alias is unique, true - alias is not unique, there are duplicates
	 */
	public function checkForDoubleAliases(string $alias, int $id): bool {
		$sql = "SELECT * FROM " . $this->tableName . " WHERE alias = :alias AND id != :id";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':alias', $alias);
		$query->bindParam(':id', $id);
		$query->execute();
		
		$result = $query->fetch();

		return is_array($result) ? false : true;
	}	

	/**
	 * Get array of children for the parent
	 * 
	 * @param   int 	$parent     Parent id. Or 0 - for the start level
	 * @param 	int 	$selected 	Current|selected item id in the list|ul, -1 - default|unexisting value
	 * @param 	int 	$exclude	Exclude this branch|item id for preventing recycling, -1 - default|unexisting value
	 * @param 	int 	$level		Deep of the tree, 0 - first|start level
	 * 
	 * @return 	array           	Array of the children or null
	 */
	public function getFamilyTree(int $parent = 0, int $selected = -1, int $exclude = -1, int $level = 0): ?array {

		$sql = "SELECT id, title, alias, parent FROM " . $this->tableName . " WHERE parent = :parent AND id != :exclude ORDER BY id ASC";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':parent', $parent);
		$query->bindParam(':exclude', $exclude);
		$query->execute();
		
		$result = $query->fetchAll();

		$level++;

		foreach($result as $key => $value){

			$result[$key]["sub"] = $this->getFamilyTree((int)$value["id"], (int)$selected, (int)$exclude, (int)$level);
			$result[$key]["level"] = $level;

			if( $value["id"] == $selected ){

				$result[$key]["selected"] = "selected";

			}else{

				$result[$key]["selected"] = "";

			}

		}

		return $result;
		
	}

    /**
     * Get items by parent id
     * 
     * @param   int         $value  Parent id      
     * 
     * @return  array|null          Array of the children or null
     */   
    public function getByParent(int $value): ?array {

		$sql = "SELECT * FROM " . $this->tableName . " WHERE parent = :parent";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':parent', $value);
		$query->execute();
		
		$result = $query->fetchAll();

        return is_array($result) ? $result : null;

    }

    /**
     * Search items by occurrence of a string in the title, text, text_editor
     * 
     * @param   string      $value  search word      
     * 
     * @return  array|null  		Array of the items or null
     */    
    public function search(string $value): ?array {

		$word = "%" . $value . "%";

		$sql = "SELECT * FROM " . $this->tableName . " WHERE title like :word_title OR text like :word_text OR text_editor like :word_editor";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':word_title', $word);
		$query->bindParam(':word_text', $word);
		$query->bindParam(':word_editor', $word);
		$query->execute();
		
		$result = $query->fetchAll();

        return is_array($result) ? $result : null;

    }

    /**
     * Get SEO data by id
     * 
     * @param   int         $value  Item id      
     * 
     * @return  array|null          Array of the seo data or null
     */   
    public function getSeoById(int $value): ?array {

		$sql = "SELECT seo_title, seo_keywords, seo_description FROM " . $this->tableName . " WHERE id = :id";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':id', $value);
		$query->execute();
		
		$result = $query->fetch();

        return is_array($result) ? $result : null;

    }

}