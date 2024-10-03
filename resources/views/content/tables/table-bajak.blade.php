@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')

<!-- Striped Rows -->
<div class="card">
  <h5 class="card-header">Tabel Data Pengamatan Bajak</h5>

  <div class="d-flex justify-content-between p-3">
    <!-- Dropdown Filter PlantGroup -->
    <div class="btn-group">
      <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Filter Plant Group
      </button>
      <ul class="dropdown-menu">
        <li>
          <a class="dropdown-item" href="{{ route('tables-bajak') }}">Semua</a>
        </li>
        @foreach ($plantGroups as $item)
          <li>
            <a class="dropdown-item" href="{{ route('tables-bajak') . '?plantGroup=' . $item->PlantGroup }}">{{ $item->PlantGroup }}</a>
          </li>
        @endforeach
      </ul>
    </div>

    <!-- Filter Kalender Tanggal -->
    <div>
      <input type="text" id="filterDate" class="form-control" placeholder="Pilih Tanggal" />
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Plant Group</th>
          <th>Tanggal Pengamatan</th>
          <th>Lokasi</th>
          <th>Luas</th>
          <th>Sat</th>
          <th>Exs Tanaman</th>
          <th>Kedalaman Masuk Standar</th>
          <th>Kedalaman (Min)</th>
          <th>Kedalaman (Max)</th>
          <th>Kedalaman (Rata-Rata)</th>
          <th>Aplikasi Pinggiran % Masuk STD</th>
          <th>Kerataan Aplikasi % Masuk STD</th>
          <th>Total (%)</th>
          <th>Jenis Bajak</th>
          <th>Komoditi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($bajaks as $item)
          <tr>
            <td>{{ $item->PlantGroup }}</td>
            <td>{{ $item->TGL_Pengamatan }}</td>
            <td>{{ $item->Lokasi }}</td>
            <td>{{ $item->Luas }}</td>
            <td>{{ $item->Sat }}</td>
            <td>{{ $item->Exs_Tanaman }}</td>
            <td>{{ $item['Kedalaman%MasukSTD'] }}</td>
            <td>{{ $item['Kedalaman(Min)'] }}</td>
            <td>{{ $item['Kedalaman(Max)'] }}</td>
            <td>{{ $item['Kedalaman(Rata-Rata)'] }}</td>
            <td>{{ $item['Aplikasi_pinggiran%MasukSTD'] }}</td>
            <td>{{ $item['Kerataan_Aplikasi%MasukSTD'] }}</td>
            <td>{{ $item['Total(%)'] }}</td>
            <td>{{ $item['Jenis Bajak'] }}</td>
            <td>{{ $item->Commodity }}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a class="dropdown-item" href="javascript:void(0);">
                    <i class="bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
      
    </table>
  </div>
</div>
<!--/ Striped Rows -->

<!-- Tambahkan ini di Blade file di bagian <head> atau sebelum </body> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  // Inisialisasi Flatpickr pada input dengan ID filterDate
  flatpickr("#filterDate", {
    dateFormat: "d-m-y", // Format tanggal yang diinginkan
    onChange: function(selectedDates, dateStr, instance) {
        console.log('Tanggal dipilih: ', dateStr); // Debugging, lihat di console apakah tanggalnya benar
        const url = new URL(window.location.href);
        url.searchParams.set('filterDate', dateStr);
        window.location.href = url.toString();
    }
});
</script>

<hr class="my-12">

@endsection
