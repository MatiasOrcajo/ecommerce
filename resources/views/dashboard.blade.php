@extends('layouts.app-adminkit')

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Dashboard</h1>
        </div>
        <div class="row">

            <div class="col-12 col-lg-12 d-flex my-3">
                <div class="mt-2">
                    <label for="temporalidad-filter" class="form-label">Filtrar por temporalidad:</label>
                    <select id="time-filter" class="form-select">
                        <option value="today">Hoy</option>
                        <option value="seven-days">Últimos 7 días</option>
                        <option value="this-month">Este mes</option>
                        <option value="this-year">Este año</option>
                        <option value="year-on-year">Interanual</option>
                    </select>
                </div>
            </div>

            <!-- Primera caja ocupa 1 columna completa a lo alto -->
            <div class="col-12 col-lg-7 d-flex">
                <div class="row flex-grow-1">
                    <div class="col-12 card flex-fill">
                        <div class="card-header">
                            <h4 class="card-title">Facturación total</h4>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h1 class="m-0" id="total-billing-amount"></h1>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h1 class="m-0" style="color: green" id="total-billing-percentage-change-difference"></h1>
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">Facturado período 1:</h5>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0" id="period-1-total-billing">0</h5>
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0">Facturado período 2:</h5>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h5 class="m-0" id="period-2-total-billing">0</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="sales-chart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 card flex-fill">
                        <div class="card-header">
                            <h4 class="card-title">Ventas totales</h4>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h1 class="m-0">ARS 1,020,202</h1>
                                </div>
                                <div class="d-flex align-items-center">
                                    <h1 class="m-0" style="color: green">&#8593 150%</h1>
                                </div>
                            </div>

{{--                            <div class="mt-3 d-flex justify-content-between align-items-center">--}}
{{--                                <div class="d-flex align-items-center">--}}
{{--                                    <h1 class="m-0">ARS 1,020,202</h1>--}}
{{--                                </div>--}}
{{--                                <div class="d-flex align-items-center">--}}
{{--                                    <h1 class="m-0" style="color: green">&#8593 150%</h1>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="sales-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Segunda columna dividida en tres secciones -->
            <div class="col-12 col-lg-5 d-flex flex-column">
                <div class="row flex-grow-1" style="height: 25%">
                    <div class="col-12">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <h5 class="card-title">Visitas</h5>
                                <h6 class="card-subtitle text-muted">Visitas al sitio</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="visitors-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row flex-grow-1">
                    <div class="col-12" style="transform: translateY(-5%);">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <h5 class="card-title">Pie Chart</h5>
                                <h6 class="card-subtitle text-muted">Pie charts are excellent at showing the relational
                                    proportions between data.</h6>
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
        </div>
    </div>




    <script type="module" src="{{ mix('resources/js/chart.js') }}"></script>
    <script type="module" src="{{ mix('resources/js/timeFilter.js') }}"></script>
@endsection

