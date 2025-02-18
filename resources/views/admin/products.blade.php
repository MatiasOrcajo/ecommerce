@extends('layouts.app-adminkit')

@section('content')
    <link href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css"/>

    <div class="container-fluid p-0">
        <h2 class="my-4">Dashboard de Productos</h2>

        <!-- Formulario para agregar producto -->
        <div class="card mb-4">
            <div class="card-header">Agregar Producto</div>
            <div class="card-body">
                <form  method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category_id" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="1">Electrónica</option>
                            <option value="2">Ropa</option>
                            <option value="3">Hogar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount" class="form-label">Descuento (%)</label>
                        <input type="number" class="form-control" id="discount" name="discount" min="0" max="100">
                    </div>

                    <div class="mb-3">
                        <label for="discount_valid_until" class="form-label">Descuento válido hasta</label>
                        <input type="date" class="form-control" id="discount_valid_until" name="discount_valid_until">
                    </div>

                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </form>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card">
            <div class="card-header">Lista de Productos</div>
            <div class="card-body">
                <table class="table table-bordered" id="myTable">

                </table>
            </div>
        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="{{asset('adminkit/js/app.js')}}"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

<script>let table = new DataTable('#myTable');</script>


