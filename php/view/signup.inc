<div id="layer" class="bglayer">
<div class="bigform layer">
<h1>Sign Up</h1>
<form id="signup" action="<?php echo Config::loc?>/xhr/?do=signup" method="POST" class="cf">
  <h3 class="error hidden"></h3>
  <p class="mb1 cf">
    <label for="email">Email</label>
    <input type="text" name="email" value="" id="email" placeholder="Email"/>
  </p>

  <p class="mb1 cf">
    <label for="password">Password</label>
    <input type="password" name="password" value="" id="password" placeholder="password"/>
  </p>

  <input class="button" type="submit" value="sign up!" />
</form>
</div>
</div>