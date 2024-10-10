@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
<script>
  document.addEventListener('DOMContentLoaded', function() {

    // Mengambil data dari controller (bajakTotalPersentase) yang sudah dikonversi ke JSON
    const bajakTotalPersentase = @json($bajakTotalPersentase);

    // Log untuk memastikan data yang diterima
    console.log(bajakTotalPersentase);

    // Menyiapkan data untuk sumbu X (PlantGroup) dan sumbu Y (Total(%) rata-rata)
    const plantGroups = Object.keys(bajakTotalPersentase); // PG1, PG2, PG3 sebagai sumbu X
    const totalValues = Object.values(bajakTotalPersentase); // Rata-rata Total(%) sebagai sumbu Y

    const totalRevenueChartEl = document.querySelector('#totalRevenueChart');
    
    // Dynamic colors for each plant group
    const dynamicColors = plantGroups.map((pg, index) => {
        const colors = ['#7B68EE', '#28B463', '#F39C12']; // Adjust as needed
        return colors[index % colors.length];
    });

    const totalRevenueChartOptions = {
        chart: {
            type: 'bar', // Bar chart
            height: 350,
            toolbar: {
                show: true // Add chart download and zoom options
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '60%'
                        }
                    }
                }
            }]
        },
        plotOptions: {
            bar: {
                horizontal: false, // Set bar vertikal
                columnWidth: '25%', // Lebar kolom
                borderRadius: 80, // Ujung bar lebih halus (tidak terlalu kotak)
                dataLabels: {
                    position: 'top' // Display labels on top of bars
                },
            },
        },
        series: [{
            name: 'Persentase Bajak',
            data: totalValues // Menggunakan data dari database untuk sumbu Y
        }],
        xaxis: {
            categories: plantGroups, // Sumbu X menggunakan PlantGroup (PG1, PG2, PG3)
            title: {
                text: 'Plant Group',
                style: {
                    fontWeight: 'normal' // Judul X tidak bold
                }
            }
        },
        yaxis: {
            title: {
                text: 'Rata-rata Presentase Pencapaian Kualitas Bajak',
                style: {
                    fontWeight: 'normal' // Judul Y tidak bold
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed() + "%"; // Ensure consistent 2-decimal places
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val.toFixed(2) + "%"; // Menampilkan persentase di atas bar
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        colors: dynamicColors, // Apply dynamic colors for bars
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent'] // Stroke transparan untuk bar chart
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val, opts) {
                    return val.toFixed(2) + "% for " + plantGroups[opts.dataPointIndex]; // Detailed tooltip
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        }
    };

    // Render chart jika elemen ditemukan
    if (typeof totalRevenueChartEl !== 'undefined' && totalRevenueChartEl !== null) {
        const totalRevenueChart = new window.ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
        totalRevenueChart.render();
    }
    
  });
</script>

@endsection



@section('content')
<div class="row">
  <div class="col-xxl-8 mb-6 order-0">
    <div class="card">
      <div class="d-flex align-items-start row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary mb-3">Hello {{ Auth::user()->name }}!</h5>
            <p class="mb-6">Kerja untuk Kinerja, untuk data yang cepat,<br>tepat, akurat dan dapat dipercaya. BISA!!!</p>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-6">
            <img src="{{asset('assets/img/illustrations/nanas.jpg')}}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded">
              </div>
            </div>
            <p class="mb-1">Yeild NSFC</p>
            <h4 class="card-title mb-3">200</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +7.80%</small>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="wallet info" class="rounded">
              </div>
            </div>
            <p class="mb-1">Yeild NSSC</p>
            <h4 class="card-title mb-3">75</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +2.42%</small>
          </div>
        </div>
      </div>
    </div>
  </div> 
  <!-- Total Revenue -->
  <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-lg-8">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Rata-Rata Pencapaian Kualitas Pengamatan Bajak Per PG</h5>
            </div>
          </div>
          <div id="totalRevenueChart" class="px-3"></div>
        </div>
        <div class="col-lg-4 d-flex align-items-center">
          <div class="card-body px-xl-9">
            <div class="text-center mb-6">
              <div class="btn-group">
                <button type="button" class="btn btn-outline-primary">
                  <script>
                  document.write(new Date().getFullYear() - 1)

                  </script>
                </button>
                <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="javascript:void(0);">2022</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                </ul>
              </div>
            </div>

            <div id="growthChart"></div>
            <div class="text-center fw-medium my-6">92% Potensi Crown</div>

            <div class="d-flex gap-3 justify-content-between">
              <div class="d-flex">
                <div class="avatar me-2">
                  <span class="avatar-initial rounded-2 bg-label-primary"><i class="bx bx-dollar bx-lg text-primary"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>
                    <script>
                    document.write(new Date().getFullYear() - 1)

                    </script>
                  </small>
                  <h6 class="mb-0">92% bibit normal</h6>
                </div>
              </div>
              <div class="d-flex">
                <div class="avatar me-2">
                  <span class="avatar-initial rounded-2 bg-label-info"><i class="bx bx-wallet bx-lg text-info"></i></span>
                </div>
                <div class="d-flex flex-column">
                  <small>
                    <script>
                    document.write(new Date().getFullYear() - 2)

                    </script>
                  </small>
                  <h6 class="mb-0">8% bibit afkir</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Total Revenue -->
  <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
    <div class="row">
      <div class="col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/paypal.png')}}" alt="paypal" class="rounded">
              </div>
            </div>
            <p class="mb-1">Keseragaman Bibit Crown</p>
            <h4 class="card-title mb-3">99.25%</h4>
            <small class="text-danger fw-medium"><i class='bx bx-down-arrow-alt'></i> 0.08%</small>
          </div>
        </div>
      </div>
      <div class="col-6 mb-6">
        <div class="card h-100">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between mb-4">
              <div class="avatar flex-shrink-0">
                <img src="{{asset('assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card" class="rounded">
              </div>
            </div>
            <p class="mb-1">Keseragaman Bibit Sucker </p>
            <h4 class="card-title mb-3">97.75%</h4>
            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> 1.25%</small>
          </div>
        </div>
      </div>
      <div class="col-12 mb-6">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
              <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                <div class="card-title mb-6">
                  <h5 class="text-nowrap mb-1">Kualitas Panen</h5>
                  <span class="badge bg-label-warning">Tahun 2024</span>
                </div>
                <div class="mt-sm-auto">
                  <span class="text-success text-nowrap fw-medium"><i class='bx bx-up-arrow-alt'></i> 96.52%</span>
                  <h4 class="mb-0">4.02%</h4>
                </div>
              </div>
              <div id="profileReportChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">

  <!-- Order Statistics -->
  <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-1 me-2">% Pencapaian Kualitas Tanam</h5>
          <p class="card-subtitle">Dari total 112 lokasi teramati, 1 Lokasi tidak masuk STD dengan 
            rata-rata pencapaian kualitas tanam sebesar 96,74%</p>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-6">
          <div class="d-flex flex-column align-items-center gap-1">
            <h3 class="mb-1">Standar Kualitas 95%</h3>
            <small>Plantation Group 1,2,3</small>
          </div>
          <div id="orderStatisticsChart"></div>
        </div>
        <ul class="p-0 m-0">
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-mobile-alt'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">JTAB</h6>
                <small>...</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">95%</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-success"><i class='bx bx-closet'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">JTDB</h6>
                <small>...</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">97%</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center mb-5">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-info"><i class='bx bx-home-alt'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Kedalaman</h6>
                <small>...</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">97.2%</h6>
              </div>
            </div>
          </li>
          <li class="d-flex align-items-center">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-secondary"><i class='bx bx-football'></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">Pencapaian Kualitas Tanam</h6>
                <small>...</small>
              </div>
              <div class="user-progress">
                <h6 class="mb-0">99%</h6>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Order Statistics -->

  <!-- Expense Overview -->
  <div class="col-md-6 col-lg-4 order-1 mb-6">
    <div class="card h-100">
      <div class="card-header nav-align-top">
        <ul class="nav nav-pills" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#gudangMixer" aria-controls="gudangMixer" aria-selected="true">Gudang Mixer</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#adukanBahan" aria-controls="adukanBahan" aria-selected="false">Adukan Bahan</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#boomSpray" aria-controls="boomSpray" aria-selected="false">Boom Spray dan Cameco</button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="gudangMixer" role="tabpanel">
            <div id="incomeChart"></div>
          </div>
          <div class="tab-pane fade" id="adukanBahan" role="tabpanel">
            <div id="incomeChart"></div>
          </div>
          <div class="tab-pane fade" id="boomSpray" role="tabpanel">
            <div id="incomeChart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!--/ Expense Overview -->
    <!-- Expense Overview -->
  <div class="col-md-6 col-lg-4 order-1 mb-6">
    <div class="card h-100">
      <div class="card-header nav-align-top">
        <h5 class="card-title m-0 me-2">Pengamatan Boom Spraying</h5>
        <p class="card-subtitle">Volume Air Per Aktivitas</p>
      </div>
      <div class="card-body">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
            <div class="d-flex mb-6">
            </div>
            <div id="incomeChart1"></div>
            <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
              <div class="flex-shrink-0">
                <div id="expensesOfWeek1"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!--/ Expense Overview -->


    <!-- Expense Overview -->
    <div class="col-md-6 col-lg-4 order-1 mb-6">
      <div class="card h-100">
        <div class="card-header nav-align-top">
          <h5 class="card-title m-0 me-2"> Pencapaian Keseragaman Bibit Lokasi Tanam</h5>
          <p class="card-subtitle">Dari total 108 lokasi teramati, Seluruh Lokasi masuk STD dengan rata2 pencapaian sebesar 98,45%
            </p>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
              <div class="d-flex mb-6">
              </div>
              <div id="incomeChart2"></div>
              <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                <div class="flex-shrink-0">
                  <div id="expensesOfWeek2"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Expense Overview -->

    <div class="col-md-6 col-lg-8 order-1 mb-6">
      <div class="card h-100">
        <div class="card-header nav-align-top">
          <h5 class="card-title m-0 me-2">Seset Bonggol</h5>
          <p class="card-subtitle">huft
            </p>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
              <div class="d-flex mb-6">
              </div>
              <div id="bonggolChart"></div>
              <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                <div class="flex-shrink-0">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Expense Overview -->
    <div class="col-md-6 col-lg-8 order-1 mb-6">
      <div class="card h-100">
          <div class="card-header nav-align-top">
              <h5 class="card-title m-0 me-2">Bonggol Tidak Terseset Per Grade</h5>
              <p class="card-subtitle">huft</p>
          </div>
          <div class="card-body">
              <div class="tab-content p-0">
                  <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                      <div class="d-flex mb-6">
                          <!-- Tempat untuk charts -->
                      </div>
                      <div class="row">
                          <!-- Chart PG1 -->
                          <div class="col-md-4">
                              <div id="chartPg1"></div>
                          </div>
                          
                          <!-- Chart PG2 -->
                          <div class="col-md-4">
                              <div id="chartPg2"></div>
                          </div>
                          
                          <!-- Chart PG3 -->
                          <div class="col-md-4">
                              <div id="chartPg3"></div>
                          </div>
                      </div>
                      <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                          <div class="flex-shrink-0"></div>
                          <h6>Bonggol tidak terseset didominasi grade A (15-22 Cm) sebesar
                            92,69%, dan diikuti grede B (23-31 Cm) sebesar 6,84%, dan 
                            grade C (>31 Cm) sebesar 0,47%.</h6>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  
  
</div>
@endsection
