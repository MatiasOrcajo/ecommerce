@extends('layouts.app-adminkit')

@section('content')

    <div class="container-fluid p-0">
        <div class="row">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-lg-4">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Editar Producto</h2>

                    <form method="POST" action="{{route('admin.products.update', $product->id)}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Producto</label>
                            <input value="{{$product->name}}" type="text" class="form-control" id="name" name="name"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="" selected disabled>Seleccionar categoría</option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{$category->id}}" {{$category->id == $product->category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" value="{{$product->price}}" class="form-control" id="price"
                                   name="price" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="3">{{$product->description}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="discount" class="form-label">Descuento (%)</label>
                            <input value="{{$product->discount}}" type="number" class="form-control" id="discount"
                                   name="discount" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label for="discount_until" class="form-label">Descuento válido hasta</label>
                            <input value="{{\Carbon\Carbon::parse($product->discount_until)->format('Y-m-d')}}"
                                   type="date" class="form-control" id="discount_until"
                                   name="discount_until">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>

            <div class="col-lg-8">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Imagenes del producto</h2>
                    <small>La primera será la imagen de portada del producto</small>

                    <div id="board" class="d-flex flex-wrap">
                        <form action="{{route('admin.pictures.edit.order', $product->id)}}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                @foreach ($product->pictures as $picture)
                                    <div style="width: 150px; position: relative;" class="m-2">
                                        <img src="{{$picture->path}}" style="width: 150px" class="img-thumbnail m-2">
                                        <div
                                            style="position: absolute; top: 0; right: 0; background-color: red; color: white; padding: 3px; opacity: 0.8; cursor: pointer;"
                                            class="destroyPicture" data-id="{{$picture->id}}"
                                            data-url="{{route('admin.pictures.destroy', $picture->id)}}">
                                            X
                                        </div>
                                        <div>
                                            <input style="max-width: 100%;" type="number" name="{{$picture->id}}"
                                                   value="{{$picture->order}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-success mb-5">Guardar orden</button>

                        </form>
                    </div> <!-- Vista imagenes -->

                    <form action="{{route('admin.pictures.store', $product->id)}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="images" class="form-label">Selecciona imágenes:</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple
                                   accept="image/*" onchange="previewImages(event)">
                        </div>
                        <div id="preview" class="d-flex flex-wrap"></div> <!-- Vista previa -->
                        <div class="text-center mt-3" style="float: left;">
                            <button type="submit" class="btn btn-success">Subir Imágenes</button>
                        </div>
                    </form>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Añadir talles</h2>

                    <form method="POST" action="{{route('admin.product.create.size', $product->id)}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method("POST")
                        <div class="mb-3">
                            <label for="name" class="form-label">Talle:</label>
                            <input type="text" class="form-control" id="size" name="size"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Stock:</label>
                            <input type="number" class="form-control" id="stock"
                                   name="stock" min="0" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">Lista de Talles</div>
                    <div class="card-body">
                        <table class="table table-bordered" id="productSizes">

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{{asset('adminkit/js/app.js')}}"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script>


        $(document).ready(function () {
            // ID del producto desde Blade
            const productId = '{{ $product->id }}';
            // URL para cargar talles
            const listUrl   = `/api/products/${productId}/list-sizes`;

            // Si ya existe una tabla, la destruimos
            if ($.fn.DataTable.isDataTable('#productSizes')) {
                $('#productSizes').DataTable().destroy();
            }
            $('#productSizes').empty();

            // Inicializamos la DataTable
            const table = $('#productSizes').DataTable({
                deferRender: true,
                autoWidth:   true,
                paging:      true,
                stateSave:   true,
                processing:  true,
                ajax:        listUrl,
                columns: [
                    { title: 'TALLE', data: 'size' },
                    {
                        title: 'STOCK',
                        data:  'stock',
                        render: function(data, type, row) {
                            // Solo en modo "display" metemos el input
                            if (type === 'display') {
                                return `
                  <input
                    type="number"
                    class="form-control form-control-sm stock-input"
                    data-id="${row.id}"
                    value="${data}"
                    style="width:80px;"
                  >
                `;
                            }
                            // En modos de ordenamiento, filtrado, etc.
                            return data;
                        }
                    }
                ]
            });

            // Delegamos el evento change sobre los inputs
            $('#productSizes tbody').on('change', '.stock-input', function() {
                const $input   = $(this);
                const newStock = $input.val();
                const sizeId   = $input.data('id');

                $.ajax({
                    url:    `/api/products/${productId}/update-size-stock/${sizeId}`,
                    type:   'PUT',
                    data:   { stock: newStock },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Opcional: actualizar el dato en la tabla sin recargarla
                        const row = table.row( $input.closest('tr') );
                        row.data( $.extend({}, row.data(), { stock: newStock }) ).draw(false);
                        // Notificación opcional
                        console.log('Stock actualizado:', response);
                    },
                    error: function(xhr) {
                        alert('Error al actualizar el stock.');
                    }
                });
            });
        });


        document.addEventListener("DOMContentLoaded", function () {
            function setupDestroyListeners() {
                document.querySelectorAll(".destroyPicture").forEach(element => {
                    element.addEventListener("click", function () {
                        const pictureId = this.getAttribute("data-id");
                        const deleteUrl = this.getAttribute("data-url");

                        if (!deleteUrl) {
                            console.error("URL de eliminación no encontrada");
                            return;
                        }

                        if (confirm("¿Estás seguro de que quieres eliminar esta imagen?")) {


                            $.ajax({
                                url: deleteUrl,
                                type: "DELETE",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function (response) {
                                    location.reload();
                                },
                            });
                        }
                    });
                });
            }

            setupDestroyListeners();
        });


        function previewImages(event) {
            const previewContainer = document.getElementById("preview");
            previewContainer.innerHTML = ""; // Limpia la vista previa anterior

            const files = event.target.files;
            if (files.length === 0) return;

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("img-thumbnail", "m-2");
                    img.style.width = "150px";
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }


    </script>

@endsection
