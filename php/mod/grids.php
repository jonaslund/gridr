<?php
  $path = Front::getPath();
  $row = DB::query("SELECT * FROM grids WHERE url='{$path[0]}'", PDO::FETCH_ASSOC)->fetch();
  
  Front::updateView("grids");
?>