<div id="layer" class="bglayer">
<div class="layer bigform">
<h1>Reset Password</h1>
<form id="forgot" action="<?php echo Config::loc?>/xhr/?do=passreset" method="POST" class="cf">
  <h3 class="error hidden"></h3>
  <p class="mb1 cf">
    <label for="email">Email</label>
    <input type="text" name="email" value="" id="email" placeholder="Email"/>
  </p>
  <input class="button" id="login-btn" type="submit" value="Send Password Reset!" />  
</form>
</div>
</div>