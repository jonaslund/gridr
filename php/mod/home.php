<?php	
	Front::updateView('home');					
	$rows = DB::query("SELECT * FROM grids ORDER BY date DESC LIMIT 20", PDO::FETCH_ASSOC);
	