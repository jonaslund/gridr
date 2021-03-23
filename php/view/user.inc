<section class="user">
<?php
  $id = $row["id"];
  $username = $row["username"];
?>

<h1><?php echo $username ?></h1>

<h2>Grids</h2>
<ul>
<?php 
  $grids = DB::query("SELECT * FROM grids WHERE uid='$id'", PDO::FETCH_ASSOC);  
  foreach($grids as $rw) {
    $id = $rw["id"];
    $url = $rw["url"];
    $title = ct($rw["title"]);
    $slug = $rw["slug"];
    $videoCount = countRows("videos", "WHERE pid='$id'");            
    $videos = DB::query("SELECT * FROM videos WHERE pid='$id'", PDO::FETCH_ASSOC)->fetchAll();
    $rs = "";
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

  <?php  }  
  ?>
  </ul>

</section>


