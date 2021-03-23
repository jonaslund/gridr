<?php
  $id = $row["id"];
  $uid = $row["uid"];
  $title = ct($row["title"]);
  $locked = $row["locked"];
  $URL = $row["url"];
  $uid = $row["uid"];  
  $date = ds($row["date"]);
  $videoCount = countRows("videos", "WHERE pid='$id'");
  $username = "Anonymous";
  
  if($uid) {
    if($uid == getUser()) {
      $userRights = true;
    } else {
      $userRights = false;
    }
  }  
    
  if($uid) {
    $userRow = gr("users", "WHERE id='$uid'");
    $userRowName = $userRow["username"];
    if($userRowName) {
      $username = "<a href=\"".Config::loc."/$userRowName\" title=\"$userRowName\">$userRowName</a>";      
    } 
    
    if($userRights && $_GET["a"] == "edit") {
      //grid has owner and its the logged in user;    
      include('view/grid-edit.inc');
    } else {      
      include('view/grid-display_fixed.inc');
    }
  } else {
    //no current owner
    if($locked == 0) {
      include('view/grid-edit.inc');
    } else {
      include('view/grid-display_fixed.inc');
    }
  }
?>