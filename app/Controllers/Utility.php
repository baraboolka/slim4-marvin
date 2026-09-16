<?php

declare(strict_types = 1);

namespace Controllers;

class Utility {
    
    /**
     * Generate random string with length
     * 
     * @param   int  $length    Length for the string
     * 
     * @return  string          Random string
     */         
    public static function generateRandomString(int $length = 10): string {

        $result = array();
        $selection = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $selectionLength = strlen($selection) - 1;

        for ( $i = 0; $i < $length; $i++ ) {
            $letter = rand(0, $selectionLength);
            $result[] = $selection[$letter];
        }

        return implode($result);

    }

    /**
     * Put message into stash|session variable
     * 
     * @param   string  $message    Message for the stash|session variable
     * 
     * @return  void
     */ 
    public static function putMessageIntoStash(string $message): void {
       
        $_SESSION['temp_message'] = $message;
    
    }

    /**
     * Get message from stash|session variable
     * 
     * @return  string  Current message from the stash
     */     
    public static function getMessageFromStash(): string {
        
        $message = "";

        if( isset($_SESSION['temp_message']) ){

            $message = $_SESSION['temp_message'];
            unset($_SESSION['temp_message']);
            
        }
		
        return $message;
    
    }

    /**
     * Encode password
     * 
     * @param   string  $password   Password for encoding
     * 
     * @return  string              Encoded password
     */     
    public static function encodePassword(string $password): string {

        return md5($password);
    
    }   

    /**
     * Clear special symbols from the string|alias
     * 
     * @param   string  $text   Text with the symbols
     * 
     * @return  string          Cleared text
     */   
	/*	  
    public static function clearSpecialSymbols(string $text): string {
		
        $text = trim($text);
		$text = function_exists('mb_strtolower') ? mb_strtolower($text) : strtolower($text);

		$symbols = array(
			'"', 
			'\'', 
			';', 
			',', 
			':', 
			'/', 
			'\\', 
			'=', 
			')', 
			'(', 
			'%', 
			'*', 
			'.',
			'?',
			' '
		); 
		
		$text = str_replace($symbols, "-", $text);
        
        return $text;

    }*/

	/**
	 * Replace cyrillic characters with latin
	 * 
	 * @param   string  $text	String for replacing
	 * 
	 * @return 	string          Latin string
	 */
	/*
	public static function changeCyrillicSymbols(string $text): string {

		$cyrillicToLatin = array(
			'a'=>'a',
			'б'=>'b',
			'в'=>'v',
			'г'=>'g',
			'д'=>'d',
			'е'=>'e',
			'ё'=>'e',
			'ж'=>'j',
			'з'=>'z',
			'и'=>'i',
			'й'=>'y',
			'к'=>'k',
			'л'=>'l',
			'м'=>'m',
			'н'=>'n',
			'o'=>'o',
			'п'=>'p',
			'р'=>'r',
			'с'=>'s',
			'т'=>'t',
			'у'=>'u',
			'ф'=>'f',
			'х'=>'h',
			'ц'=>'c',
			'ч'=>'ch',
			'ш'=>'sh',
			'щ'=>'shch',
			'ы'=>'y',
			'э'=>'e',
			'ю'=>'yu',
			'я'=>'ya',
			'ъ'=>'',
			'ь'=>''
		);

		$text = strtr($text, $cyrillicToLatin);

		return $text;
		
	}*/

}