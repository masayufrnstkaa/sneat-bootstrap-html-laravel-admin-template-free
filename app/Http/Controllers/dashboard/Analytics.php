<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bajak;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
  public function index()
  {
    // Mengambil rata-rata 'Total(%)' dan grup 'PlantGroup' dalam satu query
    $bajakTotalPersentase = Bajak::query()
      ->select('PlantGroup', DB::raw('AVG(`Total(%)`) as avg_total'))
      ->whereIn('PlantGroup', ['PG1', 'PG2', 'PG3']) // Filter hanya untuk PG1, PG2, PG3
      ->groupBy('PlantGroup')
      ->get()
      ->pluck('avg_total', 'PlantGroup'); // Mengubah hasil query menjadi key-value pairs

    // Mengirim data ke view
    return view('content.dashboard.dashboards-analytics', compact('bajakTotalPersentase'));
  }
}
