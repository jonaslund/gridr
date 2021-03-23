<section id="grid" class="g<?php echo $videoCount ?>">
<?php   
  $rows = DB::query("SELECT * FROM videos WHERE pid = $id ORDER BY seq ASC", PDO::FETCH_ASSOC);
  foreach($rows as $rw) {
    $videoSRC = $rw["video"];      
    include('view/youtube.inc');
  }
 ?>

 <div class="meta clear">
   <h1><?php echo $title ?></h1>
   <h2><?php echo $username ?> <br/> <?php echo $date; ?></h2>    
   <?php if($userRights):?>
     <a href="<?php echo Config::loc?>/<?php echo $URL ?>/?a=edit">Edit Grid</a>
   <?php endif;?>
 </div>
</section>