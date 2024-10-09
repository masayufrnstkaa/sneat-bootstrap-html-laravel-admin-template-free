@extends('layouts/contentNavbarLayout')

@section('title', 'User List')

@section('content')

<style>
    .custom-table {
        width: 100%;
        border-collapse: collapse; 
        font-family: Arial, sans-serif; 
    }

    .custom-table th {
        background-color: #f8f9fa; 
        color: #333; 
        text-align: left; 
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
    }

    .custom-table td {
        background-color: #fff; 
        color: #333; 
        padding: 10px; 
        border-bottom: 1px solid #dee2e6; 
    }

    .custom-table tr:nth-child(even) td {
        background-color: #f2f2f2;
    }

    .custom-table tr:hover td {
        background-color: #e9ecef;
    }
</style>

<div class="container-fluid p-0"> 
    <div class="row">

        <!-- Main Content -->
        <main class="col-md-12 px-3"> 
            <h2 class="mb-4">User List</h2> 

            <!-- User Table -->
            <div class="card">
                <div class="table-responsive mt-3">
                    <table class="custom-table"> 
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // Prepare data for chart
    var userData = @json($userRoleCounts);

    var ctx = document.getElementById('userChart').getContext('2d');
    var userChart = new Chart(ctx, {
        type: 'bar', // Type of chart
        data: {
            labels: Object.keys(userData), // Role names
            datasets: [{
                label: '# of Users',
                data: Object.values(userData), // Number of users in each role
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
