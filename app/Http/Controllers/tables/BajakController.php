<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use App\Models\Bajak;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BajakController extends Controller
{
  public function index(Request $request)
  {
    // Mendapatkan parameter query dari request
    $plantGroup = $request->query('plantGroup');
    $filterTime = $request->query('filterTime');
    $filterDate = $request->query('filterDate');

    $date = Carbon::createFromFormat('d-m-y', $filterDate);
    $formattedDate = $date->format('d-M-y');
    dd($formattedDate);

    // Mengambil tanggal saat ini
    $currentDate = Carbon::now();

    // Memulai query untuk Bajak
    $bajaks = Bajak::query()
      ->when($plantGroup, function ($query, $plantGroup) {
        // Filter berdasarkan Plant Group
        return $query->where('PlantGroup', $plantGroup);
      })
      ->when($filterDate, function ($query, $filterDate) {

        // Filter berdasarkan tanggal spesifik yang dipilih pengguna
        $parsedDate = Carbon::createFromFormat('d-m-y', $filterDate);

        return $query->where('TGL_Pengamatan', $parsedDate->format('j-M-y'));
      })
      ->get();


    // Mengambil daftar Plant Group yang unik
    $plantGroups = Bajak::query()
      ->select('PlantGroup')
      ->distinct()
      ->get();

    // Mengembalikan view dengan data
    return view('content.tables.table-bajak', compact('bajaks', 'plantGroups', 'filterTime', 'filterDate'));
  }
}
