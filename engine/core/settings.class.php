<?php
namespace engine\core;
class Settings{
	protected  $settings;
	public function __construct($parse_section=true){
		$this->settings = \parse_ini_file(CONFIG."config.ini",$parse_section);
	}
        
    public function config(){
       return $this->settings;
    }
    
     public static function loadExtentions($dirname){
        $path = EXTENSIONS.$dirname;
		
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        spl_autoload_extensions(".class.php,.php");
        spl_autoload_register("spl_autoload",false);
        return true;        
    }
}
?>