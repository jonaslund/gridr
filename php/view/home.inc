<section>
  <h1 class="hidden">Make A Youtube Grid</h1>
  
  <h3 class="bold">Recent Grids</h3>
  <ul>
  <?php 
    foreach($rows as $rw) {
      $id = $rw["id"];
      $url = $rw["url"];
      $title = ct($rw["title"]);
      $slug = $rw["slug"];
      $videoCount = countRows("videos", "WHERE pid='$id'");            
      $videos = DB::query("SELECT * FROM videos WHERE pid='$id'", PDO::FETCH_ASSOC)->fetchAll();
      $rs = "";
      if(!$videoCount) {

      } else {
  ?>
  
    <li class='gridThumb g<?php echo $videoCount?>'>
      <a href="<?php echo Config::loc?>/<?php echo $url ?>/<?php echo $slug ?>">
        <span class='title'><?php echo $title ?> </span>

        <?php foreach($videos as $vid) { 
          $videoSRC = $vid["video"];
            if($videoSRC) {
                        
              $videoThumbUrl = "http://img.youtube.com/vi/$videoSRC/1.jpg";          
              $rs .= "<img class='vid' src='" . $videoThumbUrl . "' />";
            }

          } ?>
          
          <?php echo $rs;?>
       </a>
    </li>

  <?php 
      }
    }  
  ?>
  </ul>
  
</section>