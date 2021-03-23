<?php
  $path = Front::getPath();
  $user = $path[0];  

  $row = DB::query("SELECT * FROM users WHERE username = '$user'", PDO::FETCH_ASSOC)->fetch();
  Front::updateView("user");