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

        $doctors = Doctor::with('user')
            ->where(function ($doctorQuery) use ($query) {
                $doctorQuery
                    ->whereHas('user', function ($userQuery) use ($query) {
                        $userQuery->where('name', 'like', "%{$query}%");
                    })
                    ->orWhere('specialty', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($doctor) {
                $doctorName = optional($doctor->user)->name ?: 'Bác sĩ';

                return [
                    'id' => $doctor->id,
                    'name' => $doctorName,
                    'specialty' => $doctor->specialty,
                    'image' => $doctor->image ? asset($doctor->image) : asset('img/icon-doctor.png'),
                    'url' => route('doctors.search_list', ['query' => $doctorName]),
                ];
            });

        $services = Service::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'image')
            ->limit(5)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'image' => $service->image ? asset($service->image) : asset('img/service.webp'),
                    'url' => route('services.index', ['search' => $service->name]),
                ];
            });

        return response()->json([
            'doctors' => $doctors,
            'services' => $services
        ]);
    }


}
