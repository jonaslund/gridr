<?php
  $id = $row["id"];
  $fullname = $row["fullname"];
  $username = $row["username"];
  $email = $row["email"];
?>
<section>
  <h1>Settings</h1>
  <form action="<?php echo Config::loc?>/xhr/?do=settings" method="POST" class="bigform" id="settingsForm">
    <h3 class="error"></h3>
    <p class="mb15 cf">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" value="<?php echo $email ?>" placeholder="email"/>
    </p>

    <p class="mb15 cf">
      <label for="password">Change Password</label>
      <input type="password" name="password" id="password" value="" placeholder="password"/>
    </p>

    <p class="mb15 cf">
      <label for="username">Username (your http://gridr.com/[username])</label>
      <input type="text" name="username" id="username" value="<?php if(!is_numeric($username)) { echo $username; }  ?>" placeholder="Username (your http://gridr.com/[username])" />
    </p>

    <input class="button" type="submit" value="Save" name="submit" id="submit" />
  </form>
  
</section>