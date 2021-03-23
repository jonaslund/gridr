<section id="gridfixed" class="g<?php echo $videoCount ?>">
<?php   
  $rows = DB::query("SELECT * FROM videos WHERE pid = $id ORDER BY seq ASC", PDO::FETCH_ASSOC);
  foreach($rows as $rw) {
    $videoSRC = $rw["video"];      
    include('view/youtube.inc');
  }
 ?>

 <div class="meta clear">
   <h1><span class="bold"><?php echo $title ?></span>, <?php echo $username ?> — <?php echo $date; ?></h1>
   <?php if($userRights):?>
     <a href="<?php echo Config::loc?>/<?php echo $URL ?>/?a=edit">Edit Grid</a>
   <?php endif;?>
 </div>
</section>

