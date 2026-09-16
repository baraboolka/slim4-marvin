<?php

declare(strict_types = 1);

namespace Models;

class User extends Basic {

    public $tableName = "users";

    /**
     * Check user access
     * 
     * @param   string  $value  Session variable with temp hash
     * 
     * @return  bool            True - user is authorized, false - user is not authorized
     */    
    public function checkAccess(string $value): bool {

		$sql = "SELECT * FROM " . $this->tableName . " WHERE temp_hash = ? ";

		$query = $this->pdo->prepare($sql);
		$query->execute(array($value));
		
		$result = $query->fetch();

        return is_array($result) ? true : false;

    } 

    /**
     * User authorization
     * 
     * @param   array   $value  User data array  
     * 
     * @return  int             Authorizated user id    
     */    
    public function authorization(array $value): ?int {

        $sql = "SELECT * FROM " . $this->tableName . " WHERE login = :login AND password = :password";

		$query = $this->pdo->prepare($sql);	
		$query->bindParam(':login', $value["login"]);
        $query->bindParam(':password', \Controllers\Utility::encodePassword($value["password"]));
		$query->execute();
		
		$result = $query->fetch();

        return is_array($result) ? (int)$result["id"] : null;

    } 

    /**
     * Update user hash
     * 
     * @param   int     $value  User id  
     * 
     * @return  string          New temp hash for user       
     */    
    public function updateHash(int $value): string {

        $newHash = \Controllers\Utility::generateRandomString();

        $sql = "UPDATE " . $this->tableName . " SET `temp_hash` = :temp_hash WHERE `id` = :id";

		$query = $this->pdo->prepare($sql);
        $query->bindParam(':id', $value);	
		$query->bindParam(':temp_hash', $newHash);		
		$query->execute();

        return $newHash;

    } 

    /**
     * Edit user
     * 
     * @param   array   $value  User data array
     * 
     * @return  int             Edited user id
     */ 
    public function edit(array $value): int {

        $sql = "UPDATE " . $this->tableName . " SET 
        `login` = :login, 
        `password` = :password 
        WHERE `id` = :id";

		$query = $this->pdo->prepare($sql);
        $query->bindParam(':id', $value["id"]);	
		$query->bindParam(':login', $value["login"]);	
		$query->bindParam(':password', \Controllers\Utility::encodePassword($value["password"]));		
		$query->execute();

		return (int)$value["id"];

    }

    /**
     * Add user
     * 
     * @param   array   $value  User data array
     * 
     * @return  int             New user id
     */ 
    public function add(array $value): int {

        $sql = "INSERT INTO " . $this->tableName . " 
        (`login`, `password`) 
        VALUES 
        (:login, :password)";

		$query = $this->pdo->prepare($sql);
		$query->bindParam(':login', $value["login"]);	
		$query->bindParam(':password', \Controllers\Utility::encodePassword($value["password"]));		
		$query->execute();

        $userId = $this->pdo->lastInsertId();

		return (int)$userId;
        
    }

    /**
     * Delete user
     * 
     * @param   int $value  User id
     * 
     * @return  int         Deleted user id
     */     
    public function delete(int $value): int {

        $sql = "DELETE FROM " . $this->tableName . " WHERE `id` = ?";
        
        $query = $this->pdo->prepare($sql);
        $query->execute(array($value)); 

        return $value;

	}

    /**
     * Get item by alias
     * 
     * @param   string      $value  Item alias      
     * 
     * @return  array|null          Item data or null
     */    
    public function getByAlias(string $value): ?array {

        /* parent function not used for this class */

        return null;

    }

	/**
	 * Get array of children for the parent
	 * 
	 * @param   int     $parent     Parent id. Or 0 - for the start level
	 * @param 	int     $selected 	Current|selected item id in the list|ul, -1 - default|unexisting value
	 * @param 	int     $exclude	Exclude this branch|item id for preventing recycling, -1 - default|unexisting value
	 * @param 	int     $level		Deep of the tree, 0 - first|start level
	 * 
     * @return 	array               Array of the children or null
	 */
	public function getFamilyTree(int $parent = 0, int $selected = -1, int $exclude = -1, int $level = 0): ?array {

        /* parent function not used for this class */

        return null;
		
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
        
        /* parent function not used for this class */

        return false; 

    }

    /**
     * Search items by occurrence of a string in the title, text, text_editor
     * 
     * @param   string      $value  search word      
     * 
     * @return  array|null          Array of the children or null
     */    
    public function search(string $value): ?array {

        /* parent function not used for this class */

        return false; 

    }    
    /**
     * Get items by parent id
     * 
     * @param   int         $value  Parent id      
     * 
     * @return  array|null          Array of the children or null
     */   
    public function getByParent(int $value): ?array {

		/* parent function not used for this class */

        return false;

    }
}    