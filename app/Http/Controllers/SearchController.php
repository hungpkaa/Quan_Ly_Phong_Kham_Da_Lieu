<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json(['error' => 'No query provided'], 400);
        }

        $doctors = Doctor::where('name', 'like', "%{$query}%")
            ->orWhere('specialty', 'like', "%{$query}%")
            ->select('id', 'name', 'specialty', 'image')
            ->limit(5)
            ->get()
            ->map(function ($doctor) {
                // Đảm bảo ảnh có đường dẫn chính xác
                $doctor->image = $doctor->image ? asset($doctor->image) : asset('img/default-doctor.jpg');
                return $doctor;
            });

        $services = Service::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'image')
            ->limit(5)
            ->get()
            ->map(function ($service) {
                // Đảm bảo ảnh có đường dẫn chính xác
                $service->image = $service->image ? asset($service->image) : asset('img/default-service.jpg');
                return $service;
            });

        return response()->json([
            'doctors' => $doctors,
            'services' => $services
        ]);
    }


}