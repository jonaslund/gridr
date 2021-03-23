<?php 
$user = getUser();
$userrow = gr("users", "WHERE id=$user");

if($userrow["username"]) {
  $inAs = $userrow["username"];
} else {
  $inAs = $userrow["email"];
}
?>
<header id="main-header" class="cf clear">
  <h1><a href="<?php echo Config::loc?>/">GRIDR</a></h1>
  <nav class="left">
    <ul>
      <li><a href="<?php echo Config::loc?>/new/">Make a new grid</a></li>
    </ul>
  </nav>
  
  <nav class="right">
  <ul>
    <?php if($user):?>
      <li><a href="<?php echo Config::loc?>/<?php echo $inAs ?>"/>Profile</a></li>  
      <li><a href="<?php echo Config::loc?>/settings/">Settings</a></li>
      <li><a href="<?php echo Config::loc?>/xhr/?do=logout" class="">Logout</a></li>
    <?php else:?>
      <li><a href="#" class="login">Log In</a></li>
      <li><a href="#" class="signup">Sign Up</a></li>
    <?php endif;?>
  </ul>
  </nav>
</header>