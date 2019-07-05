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

		// check if already exists similar latlong
		$is_found_similar = false;
		for ($i = 0; $i < count($outputArray); $i++) {
			if (
				$outputArray[$i]["x_city"] == $row["x_city"]
				&& $outputArray[$i]["y_city"] == $row["y_city"]
				&& abs(floatval($outputArray[$i]["x_lat"]) - floatval($row["x_lat"])) < 1
				&& abs(floatval($outputArray[$i]["x_long"]) - floatval($row["x_long"])) < 1
				&& abs(floatval($outputArray[$i]["y_lat"]) - floatval($row["y_lat"])) < 1
				&& abs(floatval($outputArray[$i]["y_long"]) - floatval($row["y_long"])) < 1
			) {
				$outputArray[$i]["counter"] = $outputArray[$i]["counter"] + $row["counter"];
				$is_found_similar = true;
			}
		}

		$is_found_reversed = false;
		// check if already exists reversed
		for ($i = 0; $i < count($outputArray); $i++) {
			if (
				$outputArray[$i]["x_city"] == $row["y_city"]
				&& $outputArray[$i]["y_city"] == $row["x_city"]
				&& abs(floatval($outputArray[$i]["x_lat"]) - floatval($row["y_lat"])) < 1
				&& abs(floatval($outputArray[$i]["x_long"]) - floatval($row["y_long"])) < 1
				&& abs(floatval($outputArray[$i]["y_lat"]) - floatval($row["x_lat"])) < 1
				&& abs(floatval($outputArray[$i]["y_long"]) - floatval($row["x_long"])) < 1
			) {
				$outputArray[$i]["counter"] = $outputArray[$i]["counter"] + $row["counter"];
				$is_found_reversed = true;
			}
		}

		// check if invalid
		$is_invalid = false;
		if (
			$row["x_city"] == $row["y_city"]
			&& abs(floatval($row["x_lat"]) - floatval($row["y_lat"])) < 1
			&& abs(floatval($row["x_long"]) - floatval($row["y_long"])) < 1
		) {
			$is_invalid = true;
		}

		if (!$is_found_similar && !$is_found_reversed && !$is_invalid) {
			array_push($outputArray, $row);
		}
	}
	$resRows->close();

	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($outputArray, JSON_PRETTY_PRINT);
?>