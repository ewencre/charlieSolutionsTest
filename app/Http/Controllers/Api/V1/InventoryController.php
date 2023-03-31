<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index ($latitude, $longitude, $radius)
    {
        // Calculer et afficher le nombre de sortie(s) par jour par capteur.
        $data = DB::table('inventory_sensors')
            ->selectRaw("
                inventory_sensors.tracker_id, 
                sensor_name, 
                latitude, 
                longitude,
                (6371000 * acos(cos(radians(?)) *
                    cos(radians(latitude))
                    * cos(radians(longitude) - radians(?)
                    ) + sin(radians(?)) *
                    sin(radians(latitude)))
                ) AS distance,
                DATE(datetime) as date,
                count(*) as amount", 
                [$latitude, $longitude, $latitude]
            )
            ->join('inventories', function (JoinClause $join) {
                $join->on('inventory_sensors.tracker_id', '=', 'inventories.tracker_id')->on('inventory_sensors.inventory_id', '=', 'inventories.inventory_id');
            })
            ->groupBy('inventory_sensors.tracker_id', 'date', 'sensor_name', 'latitude', 'longitude')
            ->having("distance", "<", $radius)
            ->orderBy('date', 'asc')
            ->orderBy('sensor_name', 'asc')
            ->orderBy('amount', 'desc')
            ->get();

        return $data;
    }
}
