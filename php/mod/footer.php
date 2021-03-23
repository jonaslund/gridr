<?php	
	$qry = "SELECT * FROM cmp WHERE pub=1 AND pos='3' ORDER BY seq ASC LIMIT 4";
	$rows = DB::query($qry, PDO::FETCH_ASSOC);
			
	include 'view/footer.inc';
?>