<?php
  $user = getUser();
  if($user) {
    $row = DB::query("SELECT * FROM users WHERE id = $user", PDO::FETCH_ASSOC)->fetch();
    Front::updateView("settings");
  } else {
    Front::updateView("login");
  }
?>