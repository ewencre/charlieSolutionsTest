<?php
// RSRP is the Reference Signal Received Power
// RSRQ is the Reference Signal Received Quality
/* 
	date,
	les coordonnées GPS du tracker, 
	sa batterie, 
	la liste des noms des capteurs détectés dans l'inventaire avec leur RSSI (puissance en DB reçue par le capteur, 
		qui nous permet d'indiquer la distance à laquelle il est détecté par rapport au tracker). 
	354679092923252  ; Time  ;  Latitude  ;  Longitude  ;  [liste capteurs] ; Battery ; RSSI…

	topic = ID de tracker
	GT = infos sur le tracker
	CP = infos sur les capteurs que le tracker à inventorié
	ble_payload = "C ID NOMDUCAPTEUR;0;0;RSSI"

	ID de l'inventaire :
	pour GT : { "ID_FRAME": { "value": "id:851" }
	pour CP : "ID_FRAME": "id:851"

	deux tables :
	inventories : idTracker ; datetime ; lat ; long ; id inventaire ; batterie
	inventory_sensors : idTracker ; id inventaire ; nom capteur ; rssi


	
	Calculer et afficher le nombre de sortie(s) par jour par capteur.

	select s.tracker_id, sensor_name, DATE(datetime) as date, count(*) as nb
	from inventory_sensors s
	inner join inventories i 
		on s.tracker_id = i.tracker_id 
		and s.inventory_id = i.inventory_id 
	group by s.tracker_id, sensor_name, date
	order by nb desc
*/
$servername = "127.0.0.1:3306";
$username = "";
$password = "";

try 
{
	$conn = new PDO("mysql:host=$servername;dbname=charlieSolutions", $username, $password);

	$sql = 'SELECT
			topic,
			message
			FROM charlie_mqtt_messages
			WHERE traitement > 0
  	';

	$dataByType = ['GT' => [], 'CP' => []];
	foreach ($conn->query($sql) as $row) 
	{
		$topic 			= $row['topic'];
		$message 		= json_decode($row['message'], false);
		$type			= explode(':', $message->s)[0];
		
		if ($type === 'GT')
		{
			$inventoryId		= explode(':', $message->v->ID_FRAME->value)[1];
			$datetime			= date('Y-m-d H:i:s', strtotime($message->ts));
			$remainingBattery	= $message->v->battery->remaining;
			$latitude			= $message->loc[0];
			$longitude			= $message->loc[1];

			$dataByType['GT'][] = [
				$topic,
				$inventoryId,
				$latitude,
				$longitude,
				$remainingBattery,
				$datetime,
			];
		}
		else if ($type === 'CP')
		{
			$inventoryId 	= explode(':', $message->ID_FRAME)[1];
			$payload 		= $message->ble_payload;

			foreach ($payload as $sensorData)
			{
				$sensorData = explode(';', $sensorData);
				$name 		= explode(' ', $sensorData[0])[2];
				$rssi		= $sensorData[3];

				$dataByType['CP'][] = [
					$topic,
					$inventoryId,
					$name,
					$rssi,
				];
			}
		}
	}

	$stmtGT = $conn->prepare("INSERT INTO inventories (tracker_id, inventory_id, latitude, longitude, remaining_battery, datetime) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE remaining_battery=remaining_battery;");

	$conn->beginTransaction();

	foreach ($dataByType['GT'] as $inventory)
	{
		echo json_encode($inventory).PHP_EOL;
		$stmtGT->execute($inventory);
	}

	$conn->commit();

	$stmtCP = $conn->prepare("INSERT INTO inventory_sensors (tracker_id, inventory_id, sensor_name, rssi) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE rssi=rssi;");
	
	$conn->beginTransaction();

	foreach ($dataByType['CP'] as $inventorySensor)
	{
		echo json_encode($inventorySensor).PHP_EOL;
		$stmtCP->execute($inventorySensor);
	}

	$conn->commit();
} 
catch (PDOException $e) 
{
	echo $e->getMessage();
	$conn->rollback();
}
