<?php
    class Front {
	    private static $path;
	    private static $section;
	    private static $cache;
	    private static $css = array();
	    private static $view = '404';
	    private static $header = 'header';
      private static $footer = 'footer';
	    private static $mod ;
	    private static $match;
      private static $usr;
			private static $title = array("GRIDR");
	    private static $nextutt;
	    private static $language = "nl";	    
	    
	    private static function parseUrl() {
		    $off = explode('/', Config::loc);
		    $url=array();
      	
      	$path = explode('/',str_replace('?'.$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']));
		    array_shift($path);
		   
		    foreach($off as $o){
			   if($o!=''){
			    array_shift($path);
			   }
		    }
		   
		    $tmp = array();
		    foreach($path as $p){
          if(trim($p) != ''){
             $tmp[] = $p;
          }
		    }
		    return $tmp;
	    }
		
		// A private constructor; prevents direct creation of object ISO8859-1
		private function __construct() {
		}
	    
		// Prevent users to clone the instance
		public function __clone() {
		    trigger_error('Clone is not allowed.', E_USER_ERROR);
		}
	    
	    public static function getMod () {
	
			if(!isset(self::$mod)) {				
				self::$mod = '404';
				
				self::$path = self::parseUrl();
				
				if(self::$path[0] != '') {					
					if(strcasecmp('xhr',self::$path[0])== 0 && self::validateMod('xhr')) { }
					
					elseif(self::findMod('grids', self::$path[0])){} 
					elseif(self::findMod('users', self::$path[0])){} 
  				elseif(self::$path[0] == "forms"){
  				  self::$mod = "forms";
  				} 
  				elseif(self::$path[0] == "new"){
  				  self::$mod = "new";
  				} 
  				elseif(self::$path[0] == "settings"){
  				  self::$mod = "settings";
  				} 
 				} else {
					self::$mod = 'home';
				}
			}			
				return self::$mod;
	    }
	    
	    public static function getView () {
			return self::$view;
	    }
	    
	    public function updateView ($view) {
			self::$view = $view;
	    }
	    
	    public static function getPath () {
		    return self::$path;
	    }
	    
	    public static function clearHeader () {
			  self::setHeader (-1);
	    }
	    
	    public static function setHeader ($header) {
			  self::$header = $header;
	    }
	    
	    public static function getHeader () {
			// need to check absolute path from /public/index.php
		    if(self::$header != -1 && file_exists("../php/mod/".self::$header.".php")) {
				include "mod/".self::$header.".php";
		    }
	    }
		
		public static function clearFooter () {
			self::setFooter (-1);
	    }
	    
	    public static function setFooter ($header) {
			self::$footer = $footer;
	    }
	    
	    public static function getFooter () {
			// need to check absolute path from /public/index.php
		    if(self::$footer != -1 && file_exists("../php/mod/".self::$footer.".php")) {
				include "mod/".self::$footer.".php";
		    }
	    }
	    
	    public static function getCacheName () {
		    $paths = self::$path;
		    if($paths) {
  		    self::$cache = implode($paths, "-");
		    } else {
		      self::$cache = "home";
		    }
		    return self::$cache;
	    }

	   	    
	    public static function getSection () {
		    if(self::$section['linked'] != '') {
				list($tb,$id) = split(':',self::$section['linked']);
				return mysql_fetch_assoc(Config::get()->runSql(" SELECT * FROM $tb WHERE id=$id LIMIT 1"));
		    } else {
				return self::$section;
		    }
		    
	    }
	    
	    public static function addStyle ($style) {
			self::$css[] = $style;
	    }
	    
	    public static function getStyle () {
			if(count(self::$css)) {
			    echo "<style type='text/css'>".
				    implode("\r\n",self::$css).
				"</style>";
			}
	    }
	    
	    private static function findMod($tb, $utt) {
		  
		  if($tb == "grids") {
			  $rw = DB::query(" SELECT * FROM $tb WHERE url = '$utt' LIMIT 1", PDO::FETCH_ASSOC)->fetch();		    
		  } elseif($tb == "users") {		  
		  	if(is_numeric($utt)) {
		  		$stmt = DB::prepare("SELECT * FROM users WHERE id = ? LIMIT 1");	
		  	} else {
		  		$stmt = DB::prepare("SELECT * FROM users WHERE username = ? LIMIT 1");	
		  	}
		    
		    $stmt->execute(array($utt));
		    $rw = $stmt->fetch();

		    //$rw = DB::query("SELECT * FROM $tb WHERE username = '$utt' OR id = '$utt' LIMIT 1", PDO::FETCH_ASSOC)->fetch();		    
		  
			} else {
		    $rw = DB::query(" SELECT * FROM $tb WHERE utt = '$utt' LIMIT 1", PDO::FETCH_ASSOC)->fetch();
		  }

			
			if($rw['id'] > 0){
			    $mod = $tb;
			    if(self::validateMod($mod) == true){
					self::$match = $rw;
			    }
			    return true;
			}
			return false;
	    }
		
		public static function getSession ($var) {
			if ($_GET[$var] == 1 || isset($_SESSION[$var])) {
				if($_GET[$var] == 1) {
					$_SESSION[$var] = true;
				}
				return true;
			}
			
			if($_GET[$var] == -1){
				unset($_SESSION[$var]);
			}
			
			return $_SESSION[$var];
		}
	    
	    
	    private static function validateMod($mod) {
			if (file_exists("../php/mod/$mod.php")){
			    self::$mod = $mod;
			    return true;
			}
			return false;
	    }
	    
	    public static function getMatch() {
			if(self::$match['linked'] != '') {
			    $pid = self::$match['id'];
			    list($tb,$id) = explode(':',self::$match['linked']);
			    self::$match = get_tb_row($tb,$id);
			    self::$match['pid'] = $pid;
			}
			return self::$match;
	    }
	    
	    public static function get($ob){
        return new $ob;
	    }
		
		public static function addTitle($tit){
			self::$title[] = $tit;
		}
		public static function addNextUtt($utt){
			self::$nextutt = $utt;
		}		
		public static function getNextUtt(){
			return self::$nextutt;
		}		
		
		public static function getTitle(){
			if(count(self::$title)) {
				return implode(' ',self::$title);
			} else {
				return "";
			}			
		}
		
		public static function getLan() {
      if(self::$language == "nl") {
        return "";
      } else {
        return "_" . self::$language;          
      }
    }

    public static function getLanWithout() {
      if(self::$language == "") {
        return "nl";
      } else {
        return self::$language;
      }      
    }            

		
    }
?>