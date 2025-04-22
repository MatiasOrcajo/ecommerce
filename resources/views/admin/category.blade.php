@extends('layouts.app-adminkit')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="my-4">Categoría {{$category->name}}</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Formulario para agregar producto -->
        <div class="card mb-4">
            <div class="card-header">Agregar Producto</div>
            <div class="card-body">
                <form method="POST" action="{{route('admin.products.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category_id" required>
                            <option value="" selected disabled>Seleccionar categoría</option>
                            <option value="1">Electrónica</option>
                            <option value="2">Ropa</option>
                            <option value="3">Hogar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
                <table class="table table-bordered" id="products">

                </table>
            </div>
        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="{{asset('adminkit/js/app.js')}}"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

<script>

    $(document).ready(function () {
        let url = '/api/categories/{{$category->id}}/products'
        let table = $('#products').DataTable();
        table.destroy();
        $('#products').empty();


        $('#products').DataTable({
            deferRender: true,
            "autoWidth": true,
            "paging": true,
            stateSave: true,
            "processing": true,
            "ajax": url,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            "columns": [
                {
                    title: "ID",
                    data: 'id'
                },
                {
                    title: "",
                    data: 'picture',
                    "render": function (data, type, full, meta) {
                        return `<img src="${data}" width="100px" height="100px" alt="sin imagen">`
                    }
                },
                {
                    title: "NOMBRE",
                    data: 'name'
                },
                {
                    title: "CATEGORÍA",
                    data: 'category'
                },
                {
                    title: "VENTAS",
                    data: 'sales'
                },
                {
                    title: "STOCK",
                    data: 'stock'
                },
                {
                    title: "VISITAS",
                    data: 'visits'
                },
                {
                    title: "OPCION",
                    width: "5%",
                    sortable: false,
                    "render": function (data, type, full, meta) {
                        let id = full.id;
                        return `<a title="Editar producto" href="/admin/products/${id}" target="_blank" style="cursor: pointer"> <i class="fa-solid fa-pen-to-square"></i> </a>`;
                    }
                },
            ]
        })
    })


</script>


