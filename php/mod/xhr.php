<?php  
  $relURL = "http://gridr.org";
  //$relURL = "http://localhost:8888";

  require('phpass/PasswordHash.php');
  $pwdHasher = new PasswordHash(8, TRUE);
	   
     if(isset($_REQUEST['do'])) {
        $do = $_REQUEST['do'];
        switch($do) {
            case 'editGrid':
              
              $gridID = $_POST["gridID"];
              $user = getUser();
              $grid = gr("grids", "WHERE id='$gridID'");              
              $UID = $grid["uid"];
              
              $gridTitle = $_POST["gridTitle"];              
              $slug = slug($gridTitle);
              
              $videos = $_POST["gridVideos"];
              
              //check Authentication              
              if($UID) {
                if($UID == $user) {
                  //update grid
                  $stmt = DB::prepare("UPDATE grids SET title=?, slug=? WHERE id = ?");
                  $stmt->execute(array($gridTitle,$slug, $gridID));
                  
                  //empty grid videos
                  $stmt = DB::prepare("DELETE FROM videos WHERE pid = ?");
                  $stmt->execute(array($gridID));
                  
                  //insert new ones 
                  $i = 1;
                  if($videos) {
                    foreach($videos as $vid) {                
                      $stmt = DB::prepare("INSERT INTO videos SET video = ?, pid = ?, seq = ?");
                      $stmt->execute(array($vid, $gridID, $i));
                      $i++;
                    } 
                  }
                  
                  echo "success";
                } else {
                  echo "NON AUTHENCIATED";
                }
              } 
              //header("location: http://gridr.org/gridr/$URL/");                            
            break;

            case 'newGrid':
              $title = $_POST["gridTitle"];
              if(!$title) {$title = "Untitled";}
              $videos = $_POST["gridVideos"];
              $uid = getUser();          
              $slug = slug($title);                          
              
              if($title || $videos) {
 
                //Create Grid
                if($uid) {
                  $stmt = DB::prepare("INSERT INTO grids SET title=?, slug=?, uid=?");
                  $stmt->execute(array($title, $slug, $uid));                
                } else {
                  $stmt = DB::prepare("INSERT INTO grids SET title=?, slug=?, locked=?");
                  $stmt->execute(array($title, $slug, 1));                                
                }
                
                $lastID = DB::lastInsertId();
                $URL = alphaID($lastID, false, $pad_up=4);
                
                $stmt = DB::prepare("UPDATE grids SET url = ? WHERE id = ?");
                $stmt->execute(array($URL, $lastID));
                
                //Add Videos
                $i = 1;
                if($videos) {
                  foreach($videos as $vid) {                
                    $stmt = DB::prepare("INSERT INTO videos SET video = ?, pid = ?, seq = ?");
                    $stmt->execute(array($vid, $lastID, $i));                
                    $i++;
                  } 
                }
 
                echo "$relURL/$URL/$slug";
                //header("location: http://gridr.org/gridr/$URL/$slug");                              
              }
           break;

            
            case 'removeVideo':
              $rowID = $_POST["rowID"];
              $gridID = $_POST["gridID"];
                                                      
              $stmt = DB::prepare("DELETE FROM videos WHERE id = ? AND pid = ?");
              $stmt->execute(array($rowID, $gridID));
            
              echo "success";
            break;
            
            case 'deleteGrid':
              $gridID = $_POST["gridID"];
              $user = getUser();
              $grid = gr("grids", "WHERE id='$gridID'");              
              $gridUID = $grid["uid"];              
              
              if($gridUID) {
                if($grid["uid"] == $user) {
                                    
                  $stmt = DB::prepare("DELETE FROM grids WHERE id = ?");
                  $stmt->execute(array($gridID));                  
                  
                  echo "$relURL/settings";
                } 
              }
            
            break;
            
            case 'signup':
              
              
              $email = $_POST["email"];
              $pass = $_POST["password"];

              if(validateEmail($email) && $pass != "") {
                $userRow = gr("users", "WHERE email='$email'");
                $hash = $pwdHasher->HashPassword($pass);
  
                if($userRow["id"]) {
                  echo "Email Already In Use";
                } else {              
                  $stmt = DB::prepare("INSERT INTO users SET email = ?, password = ?");
                  $stmt->execute(array($email, $hash));
                
                  $userID = DB::lastInsertId();                              
                  //$username = "user-" . alphaID($userID, false, $pad_up=14);
                  
                  $stmt = DB::prepare("UPDATE users SET username = ? WHERE id = ?");
                  $stmt->execute(array($userID, $userID));
                  
                  if(!isset($_SESSION['user_id'])){
                    $_SESSION['user_id'] = $userID;
                  }                
                  
                  echo "success";            
                }
              } else {
                if(validateEmail($email)) {
                  echo "Password Can't Be Empty";
                } else {
                  echo "Invalid Email";
                }
              }
            break;
            
            case 'login':
              $email = $_POST["email"];
              $password = $_POST["password"];
              
              $stmt = DB::prepare("SELECT * FROM users WHERE email = ?");              
              $stmt->execute(array($email));
              $row = $stmt->fetch();

              if($pwdHasher->CheckPassword($_POST["password"], $row["password"])) {
                if(!isset($_SESSION['user_id'])){
                  $_SESSION['user_id'] = $row["id"];
                }
                print_r($_REQUEST);

              } else {
                echo "WRONG";
              }
            
            break;
            
            case "settings":
              $email = $_POST["email"];
              $password = $_POST["password"];
              $username = $_POST["username"];
              $user = getUser();
                            
              if($password) {
                $hash = $pwdHasher->HashPassword($password);            

                $stmt = DB::prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute(array($hash, $user));
              }
              
              if($username) {
                //check
                $users = gr("users", "WHERE username='$username'");
                if($users["id"]) {
                  if($users["id"] != $user) {
                    echo "Sorry, username is taken";                    
                  } 
                } else {
                  $stmt = DB::prepare("UPDATE users SET username = ? WHERE id = ?");
                  $stmt->execute(array($username, $user));
                }
              }
              
              if($email) {
                //validate email                
                $stmt = DB::prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->execute(array($email, $user));
              }
              
            break;
            
            case 'logout':
              unset($_SESSION['user_id']);
              header("location: $relURL");

            break;
            
            case 'searchYT':
              require_once 'Zend/Loader.php';
              Zend_Loader::loadClass('Zend_Gdata_YouTube');
                            
              $searchTerm = htmlentities($_POST["query"]);
              $offset = $_POST["offset"]; 
              //$startIndex = $_POST['startIndex'];
              //$maxResults = $_POST['maxResults'];
              
              if($offset) {
                //$startIndex = (($offset-1) * 12);
                $startIndex = $offset;
              } else {
                $startIndex = 1;
              }              

              //$startIndex = 1;
              $maxResults = 12;
              $queryType = "all";

              $developerKey = "AI39si6XBwxOh2wCExT6cOKAUCicK31Hpus6j_sWhIKFlgT0gyt_N6WJBRmnVEBaIP7euCi691xHie3U3xWW1Q_ItsXcpYgr4A";                          
              $applicationId = 'Video uploader v1';
              $clientId = 'My video upload client - v1';
                        
              $yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
              $yt->setMajorProtocolVersion(2);
              $query = $yt->newVideoQuery();
              $query->setQuery($searchTerm);
              $query->setStartIndex($startIndex);
              $query->setSafeSearch("none");
              $query->setMaxResults($maxResults);

              /* check for one of the standard feeds, or list from 'all' videos */
              switch ($queryType) {
              case 'most_viewed':
                  $query->setFeedType('most viewed');
                  $query->setTime('this_week');
                  $feed = $yt->getVideoFeed($query);
                  break;
              case 'most_recent':
                  $query->setFeedType('most recent');
                  $feed = $yt->getVideoFeed($query);
                  break;
              case 'recently_featured':
                  $query->setFeedType('recently featured');
                  $feed = $yt->getVideoFeed($query);
                  break;
              case 'top_rated':
                  $query->setFeedType('top rated');
                  $query->setTime('this_week');
                  $feed = $yt->getVideoFeed($query);
                  break;
              case 'all':
                  $feed = $yt->getVideoFeed($query);
                  break;
              default:
                  echo 'ERROR - unknown queryType - "' . $queryType . '"';
                  break;
              }
              
              $ret = array();
              foreach ($feed as $entry) {
                  $videoID = $entry->getVideoId();
                  $imgSRC = $entry->mediaGroup->thumbnail[0]->url;
                  $videoTitle = $entry->mediaGroup->title;
                  $duration = $entry->mediaGroup->duration->seconds;                  
                  //$tags = $entry->mediaGroup->keywords;
                  $videoTitle = "${videoTitle}";
                  
                  $ret[] = array("videoID" => $videoID, "imgSRC" => $imgSRC, "title" => $videoTitle);
              }
              print_r(json_encode($ret));            
            break;
            
            case "passreset":
              $email = $_POST["email"];
              $userRow = gr("users", "WHERE email='$email'");              

              if($userRow["id"]) {
                //reset password
                $newPass = random_string();
                $hash = $pwdHasher->HashPassword($newPass);            

                $stmt = DB::prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->execute(array($hash, $email));

                $subject = "Gridr: Password Reset";
                $message = "Hey. Sucks to forget your password, so here's your new one, \n\n $newPass  \n\n Go login and change it to something memorable. \n\n Cheers. \n The Gridr Team";
                $from = "info@gridr.org"; 
                $headers = "Reply-To: Gridr <info@gridr.org>\r\nReturn-Path: Gridr <info@gridr.org>\r\nFrom: Gridr <info@gridr.org>\r\nOrganization: GRIDR\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\nX-Mailer: PHP/";

                //send new password
                mail($email, $subject, $message, $headers);
                echo "Email Sent, check your email for instructions..!";

              } else {
                echo "Email is not signed up..";
              }
            break;

        }
      }
    
    exit(0);
?>