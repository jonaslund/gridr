<?php
function set_session($var){
	if ($_GET["$var"]>-1)$_SESSION["$var"]=$_GET["$var"];
	if (!isset($_SESSION["$var"]))$_SESSION["$var"]="";
	return $_SESSION["$var"];
}
/*
function validateEmail($email) {
  $validEmail = preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email);  
  return $validEmail;
}
*/

function validateEmail($adr){
	if (strlen($adr) < 9) return false; //wtf??
	$pos = strpos($adr,'@');
	if ($pos === false or $pos === 0) return false;
      $pos = strpos($adr,'.');
	if ($pos === false or $pos === 0) return false;
	list($user_name, $mail_domain) = split("@",$adr);
	//if (checkdnsrr($mail_domain, "MX")) return true;
	return true;
}


function getUser() {  
  if(isset($_SESSION["user_id"])) {
    return $_SESSION["user_id"];
  } else {
    return 0;
  }
}

function getRealPath() {
  $realPath = explode("/", $_SERVER['REQUEST_URI']);
  array_shift($realPath);
  
  $lans = array("nl", "en");
	   
  foreach($lans as $lan) {
    if($lan == $realPath[0]) {        
      array_shift($realPath);
    }
  }		      
  
  $realPath = implode("/", $realPath);  
  return "/" . $realPath;
}

	function ds($date="", $format = '%A %e %B %Y') {
		if ($date == "0000-00-00")return "";
		$date = strtotime($date);
		return strftime($format,$date);
	}

	function ct($str){
		if(!$str)return;
		$str = stripslashes($str);
		//$str = htmlentities($str,HTML_ENTITIES,'UTF-8');				

		$str = str_replace("\\r","\r",$str);

        $str = str_replace("\\n","\n",$str);
        $str = str_replace("\\n\\r","\n\r",$str);
		
		$preg = array(
		"/\[list=([a1]{1})\](.*?)\[\/list\]\n/is" => "<ol type=\"\\1\">\\2</ol>",
		"/\[list=([a1]{1})\](.*?)\[\/list\]/is" => "<ol type=\"\\1\">\\2</ol>",
		"/\n\[list\](.*?)\[\/list\]/is" => "<ul>\\1</ul>",
		"/\[list\](.*?)\[\/list\]\n/is" => "<ul>\\1</ul>",
		"/\[\*\](.*?)\n/is" => "<li>\\1</li>",
		"/\* (.*?)\n/" => "<blockquote>$1</blockquote>",
		"/\[b\](.*?)\[\/b\]/is" => "<h1>$1</h1>",
		"/\[youtube\](.*?)\[\/youtube\]/e" => "youtube_obj('$1')",
		"/\[vimeo\](.*?)\[\/vimeo\]/e" => "vimeo_obj('$1')",
		"/\s(([a-zA-Z]+:\/\/)([a-z][a-z0-9_\..-]*[a-z]{2,6})([a-zA-Z0-9\/*\.\-?&%]*))\s/i" => " <a href=\"\$1\" target=\"_blank\">$3</a>",
		"/\s(www\.([a-z][a-z0-9_\..-]*[a-z]{2,6})([a-zA-Z0-9\/*-?&%]*))\s/i" => " <a href=\"http://$1\" target=\"_blank\">$2</a>",
		
		"/\[\/(.*?) (.*?)\]/e" => "internal_link('$1', '$2')",
		
		"/\[b\](.*?)\[\/b\]/is" => "<strong>$1</strong>",
		"/\[h1\](.*?)\[\/h1\]/is" => "<h1>$1</h1>",
		"/\[h2\](.*?)\[\/h2\]/is" => "<h2>$1</h2>",
		"/\[h3\](.*?)\[\/h3\]/is" => "<h3>$1</h3>",
		"/\[i\](.*?)\[\/i\]/is" => "<em>$1</em>",
		"/\[(.*?) (.*?)\]/is" => "<a href=\"$1\" target=\"_blank\" class=\"extern\">$2</a>",
		
		"/([a-zA-Z.]+@\\S+\\.\\w+)/e" => "email('$1')");
		$str = preg_replace(array_keys($preg), array_values($preg) , $str);		
		$str = str_replace("</a> \n\n", "</a>\n", $str);


		$str = nl2br(trim($str));
		return $str;
	}

	// scramble mail
	function email($str){
		$str = scramble($str);
		$rs = "<a href=\"&#109;&#97;&#105;&#108;&#116;&#111;:$str\">$str</a>";
		return $rs;
	}        

	// scramble
	function scramble($str){
		$l = strlen($str);
		$rs = "";
		for ($i=0;$i<$l;$i++){$rs .= "&#".ord($str[$i]).";";}
		return $rs;
	}

	function imgs($tb, $pid) {	  
		$lan = Front::getLan();
		
		$qry = "SELECT * FROM img WHERE typ='$tb' AND pid='$pid' AND pub='1' ORDER BY seq";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);

    $i = 1;
		foreach ($rows as $rw) {
			$id = $rw["id"];			
			$rfn = $rw["fnm"];
			$fn = thu($rfn);
			$xt = f_e($fn);
			$txt = ct($rw["txt$lan"]);
			$w = $rw["width"];
			$h = $rw["height"];
			
			if($xt=="jpg" || $xt=="jpeg" || $xt=="gif" || $xt=="png"){
				$path = "/content/$tb/i_".a_num($pid)."/$fn";
        $rpath = "/content/$tb/i_".a_num($pid)."/$rfn";
        
				$rs .= "<div class=\"imgc posabs image".$i."\">";
				$rs .= "<img data-w=\"$w\" data-h=\"$h\" data-rel=\"$rpath\" data-orient=\"".(($w>$h)?"l":"p")."\" src=\"$path\" alt=\"".(($txt)?$txt:$fnm)."\" class=\"zoom\"/>";
				$rs .= "<img class=\"hidden fullscreeni\" src=\"/site/gfx/fullscreen.png\" />";
				$rs .= "</div>";
				$rs .= "<div class=\"image".$i."\"><div class=\"caption posabs\">$txt</div></div>";

			}
		  $i++;
		}
		return $rs;
	}

	function videos($tb, $pid, $width, $height, $i) {
		$lan = Front::getLan();
		
		$qry = "SELECT * FROM img WHERE typ='$tb' AND pid='$pid' AND pub='1' ORDER BY seq";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);
    
    foreach ($rows as $rw) {
			$id = $rw["id"];
			$fn = $rw["fnm"];
			$xt = f_e($fn);
			
			if($xt=="jpg" || $xt=="jpeg" || $xt=="gif" || $xt=="png"){				
        $posterpic = "/content/$tb/i_".a_num($pid)."/$fn";      
      	$txt = ct($rw["txt$lan"]);
  		
      }
			
			if($xt=="mp4" || $xt=="m4v"){
				$path = "/content/$tb/i_".a_num($pid)."/$fn";
				
				$rs .= "<div class=\"video posabs video".$i."\">";
        $rs .= "<video width=\"$width\" height=\"$height\" poster=\"$posterpic\" controls=\"controls\" preload=\"none\">
            <source type=\"video/mp4\" src=\"$path\" />";
            if($webM) {
              $rs .= $webM;
            }            
            
         $rs .= "<object width=\"$width\" height=\"$height\" type=\"application/x-shockwave-flash\" data=\"flashmediaelement.swf\">
                <param name=\"movie\" value=\"flashmediaelement.swf\" />
                <param name=\"flashvars\" value=\"controls=true&file=$path\" />
                <img src=\"$posterpic\" width=\"$width\" height=\"$height\" title=\"No video playback capabilities\" />
            </object>
            </video>";


				$rs .= "</div>";
				$rs .= "<div class=\"video".$i."\"><div class=\"posabs caption\">$txt</div></div>";
			}		  
		}
		return $rs;
	}

  function getVideos($tb, $pid) {
    if($tb === "works") {
      $typ = "artist";
    } else {
      $typ = "artworks";
    }
    
    $rows = DB::query("SELECT * FROM videos WHERE $typ='$pid'", PDO::FETCH_ASSOC);
    $i = 1;
    foreach($rows as $rw) {
      $id = $rw["id"];
      $width = $rw["width"];
      $height = $rw["height"];
      
      $videos = videos("videos", $id, $width, $height, $i);
      
      $rs .= $videos;
    $i++;
    }
    
    return $rs;
    
  }


	function imgs_slide($tb, $pid, $limit, $order) {	  
		$qry = "SELECT * FROM img WHERE typ='$tb' AND pid='$pid' AND pub='1' ORDER BY seq $limit";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);

    $i = $order;
		foreach ($rows as $rw) {
			$id = $rw["id"];
			$fn = $rw["fnm"];
			$xt = f_e($fn);
			$txt = $rw["txt"];
      $w = $rw["width"];
      $h = $rw["height"];

			if($xt=="jpg" || $xt=="jpeg" || $xt=="gif" || $xt=="png"){
				$path = "/content/$tb/i_".a_num($pid)."/$fn";

				$rs .= "<div data-id=\"$id\" data-order=\"$i\" class=\"img slide\">";
				$rs .= "<img src=\"$path\" alt=\"".(($txt)?$txt:$fnm)."\" class=\"".(($w>$h)?"l":"p")."\"/>";
				$rs .= "</div>";
				if($caption == true) {
					$rs .= "<div class=\"ic\">$txt</div>";
				}
			}
		  $i++;
		}
		return $rs;
	}

	function imgs_slideThu($tb, $pid) {	  
		$qry = "SELECT * FROM img WHERE typ='$tb' AND pid='$pid' AND pub='1' ORDER BY seq";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);

    $i = 1;
		foreach ($rows as $rw) {
			$id = $rw["id"];
			$fn = $rw["fnm"];
			$xt = f_e($fn);
			$txt = $rw["txt"];
      $w = $rw["width"];
      $h = $rw["height"];

			if($xt=="jpg" || $xt=="jpeg" || $xt=="gif" || $xt=="png"){
				$path = "http://localhost:8888/content/$tb/i_".a_num($pid)."/$fn";
        $hidden = (($i != 1)?"hidden":"");

//				$rs .= "<div data-id=\"$id\" data-order=\"$i\" class=\"img slide\">";
				$rs .= "<img src=\"$path\" alt=\"".(($txt)?$txt:$fnm)."\" class=\"$hidden ".(($w>$h)?"l":"p")."\"/>";
//				$rs .= "</div>";
				if($caption == true) {
					$rs .= "<div class=\"ic\">$txt</div>";
				}
			}
		  $i++;
		}
		return $rs;
	}

  function thu($filename) {
    $pp = explode(".", $filename);
    return $pp[0] . "_th." . $pp[1];
  }

	function video($tb, $pid) {
		$qry = "SELECT * FROM img WHERE typ='$tb' AND pid='$pid' AND pub='1'";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);

		foreach ($rows as $rw) {
			$id = $rw["id"];
			$fn = $rw["fnm"];
			$xt = f_e($fn);

			if($xt=="mov"){
				$path = "/content/$tb/i_".a_num($pid)."/$fn";			  			
			}
		}
		return $path;
	}


	function f_e($fnm){
		$p_i = pathinfo($fnm);
		return strtolower($p_i['extension']);
	}
	
	function a_num($n){
		return (($n<100)? (($n<10)?"00$n":"0$n"): $n);
	}

	function shorten($str,$max, $rf=""){
		if (strlen($str)>$max){
			$c = ((strpos($str," ", $max)>0)?strpos($str," ", $max):$max);
			return substr($str,0, $c)."...".(($rf)? "</i></em></strong><span class=\"rf inv\"><br />$rf</span>" : "");
		}else{
			return $str;
		}
	}

	function gv($table, $field, $filter){
		//if (is_numeric($filter))$filter="WHERE id='$filter'";
		$qry = "SELECT $field FROM $table $filter";
		$row = DB::query($qry, PDO::FETCH_ASSOC)->fetch();
	
		return $row["$field"];
	}

	function gr($table, $filter){
		$qry = "SELECT * FROM $table $filter";
		$row = DB::query($qry, PDO::FETCH_ASSOC)->fetch();
	
		return $row;
	}


  function countRows($table, $filter) {
    $count = DB::query("SELECT count(id) as n FROM $table $filter", PDO::FETCH_ASSOC)->fetch();
    return $count["n"];    
  }

	function getProjectTags($id, $typ) {
		$qry = "SELECT * FROM cat, p_c WHERE p_c.l='$id' AND cat.id=p_c.r AND typ='$typ' ORDER BY cat.tit ASC LIMIT 6";
		$rows = DB::query($qry, PDO::FETCH_ASSOC);

		foreach($rows as $rw){		
			$id = $rw["id"];
			$tit = $rw["tit"];
			$href = Config::loc."/projecten/{$rw['utt']}/";
			$rs .= "<li><a href=\"$href\" title=\"$tit\">$tit</a> /</li>";
		}
		return $rs;
	}
    
////////////////////////////////////////////
//      php/mod/forms.php dependencies    //
////////////////////////////////////////////////////////////////////////////////
    
    function random_string($len = 8){
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$str = '';
		for ($i=0; $i < $len; $i++){
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $str;
	}
    
    // flatten string
	function to7bit($str,$from_enc='UTF-8') {
		$str = mb_convert_encoding($str,'HTML-ENTITIES',$from_enc);
		$str = preg_replace(array('/&szlig;/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'), array('ss',"$1","$1".'e',"$1"), $str);
		return $str;
	}
    
    
    function xpr($id){
		$res = runSql("SELECT * FROM msg WHERE id='$id'");
		$rw = mysql_fetch_array($res);
		$rs = ct($rw["txt"]);
		return $rs;
	}

	function thu_nm($fnm){
		$pp = pathinfo($fnm);
		return $pp['filename'].$add."_th.".$pp['extension'];
	}
	
	function slug($str) {
	    $url = str_replace("'", '', $str);
	    $url = str_replace('%20', ' ', $url);
	    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
	    $url = trim($url, "-");
	    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
	    $url = strtolower($url);
	    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
	    return $url;
	}
	
	function alphaID($in, $to_num = false, $pad_up = false, $passKey = null) {
    $index = "bcdefghjklmnpqrstvwxz0123456789BCDFGHJKLMNPQRSTVWXZ";
    $base  = strlen($index);
 
    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }
 
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else { 
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }
 
        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }
 
    return $out;
	}
	
?>