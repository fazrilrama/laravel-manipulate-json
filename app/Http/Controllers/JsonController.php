<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JsonController extends Controller
{
    public function index()
    {
        $json = Storage::get('data.json');
        $data = json_decode($json, true);

        $groupedData = [];

        foreach ($data as $item) {
            $warehouseId = $item['warehouse_id'];
            if (!isset($groupedData[$warehouseId])) {
                $groupedData[$warehouseId] = [
                    'warehouse_id' => $warehouseId,
                    'total_rented_space' => 0,
                    'items' => []
                ];
            }
            $groupedData[$warehouseId]['total_rented_space'] += $item['rented_space'];
            $groupedData[$warehouseId]['items'][] = $item;
        }

        $result = array_values($groupedData);

        return response()->json($result);
    }
}
