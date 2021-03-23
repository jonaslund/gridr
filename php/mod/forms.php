<?php
  Front::clearHeader();
  $path = Front::getPath();
  $view = $path[1];
  
  switch ($view) {
    case 'login':
      Front::updateView("login");    
    break;

    case 'signup':
      Front::updateView("signup");    
    break;

    case 'forgot':
      Front::updateView("forgot");    
    break;

    default:
      Front::updateView("404");
      break;
  }
?>