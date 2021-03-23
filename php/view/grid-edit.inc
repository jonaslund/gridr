<section class="grid-edit" data-id="<?php echo $id ?>">
  <div class="tools cf">      
    <div class="searchyoutube cf mb1">
    <form method="post" action="<?php echo Config::loc?>/xhr/?do=addVideo" id="search">
      <p class="mb1">      
      <label for="title"><?php echo $title ?>" </label>
      <input type="text" id="title" name="title" value="<?php echo $title ?>" placeholder="<?php echo $title ?>" /> <br/>
      </p>
      
      <label for="video">Add Youtube Video (url or search...)</label>
      <input type="text" name="video" id="video" value="" placeholder="Add Youtube Video (url or search...)"/>
      <input type="submit" name="submit" id="submit" value="search" class="button" />
      <input type="hidden" name="id" value="<?php echo $id ?>">
      <input type="hidden" name="url" value="<?php echo $URL ?>">
    </form>  
       <img src="<?php echo Config::loc?>/site/gfx/loader.gif" id="ytloader" class="hidden"/>

    </div>

    <div id="results">
    </div>
    
    <h3 class='yttitle'></h3> 
  </div>

  <div id="gridEdit" class="g<?php echo $videoCount ?>">
  <?php 
    
    $rows = DB::query("SELECT * FROM videos WHERE pid = $id ORDER BY seq ASC", PDO::FETCH_ASSOC);
    foreach($rows as $rw) {
      $rowID = $rw["id"];
      $videoSRC = $rw["video"];      
      include('view/youtube-edit.inc');
    }
   ?>
  </div>

  <div class="savetools">    
    <a class="button" id="editGrid" href="<?php echo Config::loc?>/<?php echo $URL ?>/">Save Grid</a>
    <a class="button" id="deleteGrid" href="#">Delete Grid</a>
    <a class="button" id="previewGrid" href="#">Preview Grid</a>
    <a class="button" href="<?php echo Config::loc?>/<?php echo $URL ?>/">Done Editing</a>

  </div>
</section>