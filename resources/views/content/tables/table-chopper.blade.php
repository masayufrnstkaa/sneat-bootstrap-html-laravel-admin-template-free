@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')

    <!-- Striped Rows -->
    <div class="card">
        <h5 class="card-header">Tabel Data Pengamatan Chopper</h5>

        <div class="d-flex justify-content-between p-3">
            <!-- Dropdown Filter PlantGroup -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Filter Plant Group
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('tables-bajak') }}">Semua</a>
                    </li>
                    @foreach ($plantGroups as $item)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('tables-bajak') . '?plantGroup=' . $item->PlantGroup }}">{{ $item->PlantGroup }}</a>
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
                            <th>% Tanaman Hancur</th>
                            <th>% Bonggol Tercacah</th>
                            <th>% Aplikasi Rapat</th>
                            <th>Total (%)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @foreach ($choppers as $item)
                            <tr>
                                <td>{{ $item?->PlantGroup }}</td>
                                <td>{{ $item?->TanggalPengamatan }}</td>
                                <td>{{ $item?->Lokasi }}</td>
                                <td>{{ $item?->LuasAktif }}</td>
                                <td>{{ $item?->Sat }}</td>
                                <td>{{ $item?->ExsTanaman }}</td>
                                <td>{{ $item['% Tanaman Hancur'] ?? '-' }}</td>
                                <td>{{ $item['% Bonggol Tercacah'] ?? '-' }}</td> <!-- Penutupan kurung kurawal pada kolom ini diperbaiki -->
                                <td>{{ $item['% Aplikasi Rapat'] ?? '-' }}</td>
                                <td>{{ $item['Total (%)'] ?? '-' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('tables-chopper.show', ['chopper' => $item]) }}">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>

                                            {{-- form untuk menghapus data --}}
                                            <form method="POST"
                                                action="{{ route('tables-chopper.destroy', ['chopper' => $item]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
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

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            // Inisialisasi Flatpickr pada input dengan ID filterDate
            const url = new URL(window.location.href);
            const filterDate = url.searchParams.get('filterDate');
            if (filterDate) {
                document.getElementById('filterDate').value = filterDate;
            }

            flatpickr("#filterDate", {
                dateFormat: "d-m-y", // Format tanggal yang diinginkan
                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Tanggal dipilih:', dateStr); // Debugging, lihat di console apakah tanggalnya benar
                    const url = new URL(window.location.href);
                    url.searchParams.set('filterDate', dateStr);
                    window.location.href = url.toString();
                }
            });
        </script>

        <hr class="my-12">
    </div>

@endsection
