<section class="new-grid-wrap cf grid-edit" data-id="<?php echo $id ?>">
<h1 class="hidden">New Grid</h1>
  <div class="tools cf">    
    <div class="searchyoutube cf mb1">    
    <form method="post" action="<?php echo Config::loc?>/xhr/?do=newGridNew" id="search">            
      <p class="mb1">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="" placeholder="Title" />
      </p>
      
      <label for="video">Add Youtube Video (url or search...)</label>
      <input type="text" name="video" id="video" value="" placeholder="Add Youtube Video (url or search...)"/>
      <input type="submit" name="submit" id="submit" value="search" class="button" />
    </form>  
    <img src="<?php echo Config::loc?>/site/gfx/loader.gif" id="ytloader" class="hidden"/>
    </div>

    <div id="results">        
    </div>
    <h3 class='yttitle hidden'></h3>
  </div>
  

  <div id="gridEdit" class="">
  </div>

  <div class="savetools">    
  <?php if(getUser()):?>
    <a class="button" id="newGrid" href="#">Save Grid</a>
  <?php else:?>    

    <a class="button toggle-handle">Save Grid</a>
    <div class="save-options hidden toggle">
      <ul>
        <li><a href="#" class="button login">Log In</a></li>
        <li><a href="#" class="button signup">Sign Up</a></li>
        <li><a href="#" class="button" id="newGrid">Save Anonymously</a></li>
      </ul>    
    </div>    

  <?php endif;?>               
  <a class="button" id="previewGrid" href="#">Preview Grid</a> 
  </div>

</section>