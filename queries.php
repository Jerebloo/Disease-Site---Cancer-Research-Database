<?php
	require_once('dBaseAccess.php');
	$results = array();
	// check if mirna is sent using POST method
	if (ISSET($_POST['mirna']) and ISSET($_POST['min']) and ISSET($_POST['max'])) {
		# code...
		$disease = mysqli_real_escape_string($mirnabDb, $_POST['mirna']);
		$min = mysqli_real_escape_string($mirnabDb, $_POST['min']);
		$max = mysqli_real_escape_string($mirnabDb, $_POST['max']);
		
		$query = 
			"SELECT 
			        m1 AS source,
			        m2 AS target,
			        score AS type 
			FROM consensus_output 
			WHERE d1=d2 
			  AND d1= '".$disease."'
			  AND score<'".$max."'
			  AND score>'".$min."'
			limit 100";

		$result = mysqli_query($mirnabDb, $query);

		for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }
	  	echo json_encode($data);
	}
?>