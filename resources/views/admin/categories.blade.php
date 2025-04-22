@extends('layouts.app-adminkit')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="my-4">Dashboard de Categorías</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Formulario para agregar producto -->
        <div class="card mb-4">
            <div class="card-header">Agregar Categoría</div>
            <div class="card-body">
                <form method="POST" action="{{route('admin.categories.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la categoría</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                </form>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card">
            <div class="card-header">Lista de Productos</div>
            <div class="card-body">
                <table class="table table-bordered" id="categories">

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
        let url = '/api/list-categories'
        let table = $('#categories').DataTable();
        table.destroy();
        $('#categories').empty();


        $('#categories').DataTable({
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
                    data: 'id',
                    width: "5%"
                },
                {
                    title: "NOMBRE",
                    data: 'name'
                },
                {
                    title: "OPCION",
                    width: "5%",
                    sortable: false,
                    "render": function (data, type, full, meta) {
                        let id = full.id;
                        return `<a title="Editar producto" href="/admin/categories/${id}" target="_blank" style="cursor: pointer"> <i class="fa-solid fa-pen-to-square"></i> </a>`;
                    }
                },
            ]
        })
    })


</script>


