<?php	
	// connect to mysql
	include ('secret.php');
	
	//
	// SUBDIVISIONS DETAILS
	//
	$resRows = $mysqli->query("
		SELECT
			x_city,
			y_city,
			ROUND(x_lat,2) AS x_lat,
			ROUND(x_long,2) AS x_long,
			ROUND(y_lat,2) AS y_lat,
			ROUND(y_long,2) AS y_long,
			counter
		FROM v_trips_distinct
		WHERE counter >= 5
			AND x_epoch_start >= '1999-01-01'
			AND y_epoch_end <= '2019-12-31'
	");
	
	$outputArray = array();
	while ($row = $resRows->fetch_assoc()) {
		// check if already exists reversed
		$is_found = false;
		for ($i = 0; $i < count($outputArray); $i++) {
			if (
				$outputArray[$i]["x_city"] == $row["y_city"]
				&& $outputArray[$i]["x_lat"] == $row["y_lat"]
				&& $outputArray[$i]["x_long"] == $row["y_long"]
				&& $outputArray[$i]["y_city"] == $row["x_city"]
				&& $outputArray[$i]["y_lat"] == $row["x_lat"]
				&& $outputArray[$i]["y_long"] == $row["x_long"]
			) {
				$outputArray[$i]["counter"] = $outputArray[$i]["counter"] + $row["counter"];
				$is_found = true;
			}
		}

		// check if invalid
		$is_invalid = false;
		if (
			$row["x_city"] == $row["y_city"]
			&& $row["x_lat"] == $row["y_lat"]
			&& $row["x_long"] == $row["y_long"]
		) {
			$is_invalid = true;
		}

		if (!$is_found && !$is_invalid) {
			array_push($outputArray, $row);
		}
	}
	$resRows->close();

	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($outputArray, JSON_PRETTY_PRINT);
?>