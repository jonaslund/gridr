<div id="layer" class="bglayer">
<div class="layer bigform">
<h1>Log In</h1>
<form id="login" action="<?php echo Config::loc?>/xhr/?do=login" method="POST" class="cf">
  <h3 class="error hidden"></h3>
  <p class="mb1 cf">
  <label for="email">Email</label>
  <input type="text" name="email" value="" id="email" placeholder="Email"/>
  </p>

  <p class="mb1 cf">
    <label for="password">Password</label>
    <input type="password" name="password" value="" id="password" placeholder="password"/>
  </p>
  <input class="button" id="login-btn" type="submit" value="log in!" />
  <a class="forgot h3 right mt1" href="#forgot">Forgot password?</a>

</form>
</div>
</div>