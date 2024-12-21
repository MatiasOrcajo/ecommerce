@extends('layouts.app-adminkit')

@section('content')
    <div class="container-fluid p-0">

        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Chart.js</h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <h5 class="card-title">Ventas/mes</h5>
                        <h6 class="card-subtitle text-muted">Ventas totales en el año</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chartjs-line"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Bar Chart</h5>
                        <h6 class="card-subtitle text-muted">A bar chart provides a way of showing data values represented as vertical bars.</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chartjs-bar"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Doughnut Chart</h5>
                        <h6 class="card-subtitle text-muted">Doughnut charts are excellent at showing the relational proportions between data.</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart chart-sm">
                            <canvas id="chartjs-doughnut"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pie Chart</h5>
                        <h6 class="card-subtitle text-muted">Pie charts are excellent at showing the relational proportions between data.</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart chart-sm">
                            <canvas id="chartjs-pie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="module" src="{{ mix('resources/js/chart.js') }}"></script>
@endsection

