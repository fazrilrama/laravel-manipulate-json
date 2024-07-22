<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JsonController extends Controller
{
    public function index()
    {
        // Load JSON files
        $json1 = json_decode(file_get_contents(storage_path('app/json-1.json')), true);
        $json2 = json_decode(file_get_contents(storage_path('app/json-2.json')), true);

        $workshops = collect($json2['data'])->keyBy('code');

        $combinedData = collect($json1['data'])->map(function ($item) use ($workshops) {
            $workshop = $workshops->get($item['booking']['workshop']['code']);

            return [
                'name' => $item['name'],
                'email' => $item['email'],
                'booking_number' => $item['booking']['booking_number'],
                'book_date' => $item['booking']['book_date'],
                'ahass_code' => $item['booking']['workshop']['code'],
                'ahass_name' => $item['booking']['workshop']['name'],
                'ahass_address' => $workshop['address'] ?? '',
                'ahass_contact' => $workshop['phone_number'] ?? '',
                'ahass_distance' => $workshop['distance'] ?? 0,
                'motorcycle_ut_code' => $item['booking']['motorcycle']['ut_code'],
                'motorcycle' => $item['booking']['motorcycle']['name'],
            ];
        });

        $sortedData = $combinedData->sortBy('ahass_distance')->values();

        $response = [
            'status' => 1,
            'message' => 'Data Successfully Retrieved.',
            'data' => $sortedData
        ];

        return response()->json($response);
    }
}
