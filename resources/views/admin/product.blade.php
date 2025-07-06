@extends('layouts.app-adminkit')

@section('title')
    <title>{{$product->name}} - Atica</title>
@endsection

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
                                   name="price" min="0" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="discount" class="form-label">Descuento (%)</label>
                            <input value="{{$product->discount}}" type="number" class="form-control" id="discount"
                                   name="discount" min="0" max="100">
                        </div>

                        <div class="mb-3">
                            <label for="discount_until" class="form-label">Descuento válido hasta</label>
                            <input value="{{ \Carbon\Carbon::parse($product->discount_until)->format('d-m-Y') }}"
                                   type="text" class="form-control" id="discount_until"
                                   name="discount_until" placeholder="dd-mm-aaaa">
                        </div>

                        <div class="form-check mb-3">
                            <!-- Campo oculto -->
                            <input type="hidden" name="featured" value="0">

                            <!-- Checkbox -->
                            <input class="form-check-input" name="featured" type="checkbox" value="1"
                                   id="featured" {{$product->featured == 1 ? 'checked' : ''}}>
                            <label class="form-check-label" for="featured">
                                ¿Destacado?
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>


            <div class="col-lg-8">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Añadir talles y colores</h2>

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
                            <label for="color" class="form-label">Color:</label>
                            <input type="text" class="form-control" id="size" name="color"
                                   required placeholder="HEX code">
                        </div>

                        <div class="mb-3">
                            <label for="color_name" class="form-label">Nombre del color:</label>
                            <input type="text" class="form-control" id="size" name="color_name"
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

            <div class="col-12">
                <div class="card">
                    <div class="card-header">Lista de Talles</div>
                    <div class="card-body">
                        <table class="table table-bordered" id="productSizes">

                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Imagenes del producto</h2>
                    <small>La primera será la imagen de portada del producto</small>

                    <div id="board" class="d-flex flex-wrap">
                        <form action="{{ route('admin.pictures.edit.order', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                @foreach ($picturesByProductVariants as $productVariant)
                                    {{-- Variante: color swatch + nombre --}}
                                    <div class="col-12 mt-5 d-flex align-items-center">
                                        <p class="me-2 mb-0">Color:</p>
                                        <div
                                            style="
                                                background-color: {{ $productVariant->color }};
                                                width: 32px;
                                                height: 32px;
                                                border: 1px solid #ccc;
                                                border-radius: 4px;
                                            "
                                            title="{{ $productVariant->color_name }}"
                                        ></div>
                                        <p class="ms-2 mb-0">{{ $productVariant->color_name }}</p>
                                    </div>

                                    {{-- Imágenes de la variante --}}
                                    @foreach ($productVariant->pictures as $picture)
                                        <div class="m-2 position-relative" style="width: 150px;">
                                            <img
                                                src="{{ $picture->path }}"
                                                class="img-thumbnail"
                                                style="width: 100%;"
                                            >
                                            <div
                                                class="destroyPicture"
                                                data-id="{{ $picture->id }}"
                                                data-url="{{ route('admin.pictures.destroy', $picture->id) }}"
                                                style="
                                position: absolute;
                                top: 0;
                                right: 0;
                                background-color: red;
                                color: white;
                                padding: 3px;
                                opacity: 0.8;
                                cursor: pointer;
                            "
                                            >X
                                            </div>
                                            <div class="mt-2">
                                                <input
                                                    type="number"
                                                    name="{{ $picture->id }}"
                                                    value="{{ $picture->order }}"
                                                    class="form-control form-control-sm"
                                                    style="max-width: 100%;"
                                                >
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div> {{-- cierre de .row --}}

                            <button type="submit" class="btn btn-success mb-5">
                                Guardar orden
                            </button>
                        </form>
                    </div>


                    <form action="{{route('admin.pictures.store', $product->id)}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="images" class="form-label">Seleccionar imágenes:</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple
                                   accept="image/*" onchange="previewImages(event)">
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label">Color correspondiente:</label>
                            <select class="form-select" id="category" name="product_variant_id" required>
                                <option value="" selected disabled>Seleccionar color</option>
                                @foreach($productColors["colors_names"] as $index => $color)

                                    {{--                                    id de product_variants--}}
                                    <option value="{{$productColors["ids_product_variants"][$index]}}">
                                        <span>{{ $color }}</span>
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div id="preview" class="d-flex flex-wrap"></div> <!-- Vista previa -->
                        <div class="text-center mt-3" style="float: left;">
                            <button type="submit" class="btn btn-success">Subir Imágenes</button>
                        </div>
                    </form>
                </div>

            </div>


            <div class="col-lg-12">
                <div class="card shadow-lg p-4">
                    <h2 class="mb-4">Descripción, medidas y referencia</h2>

                    <form method="POST" action="{{route('admin.products.update.descriptions', $product->id)}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción:</label>
                            <textarea class="form-control editor" name="description" cols="30"
                                      rows="10">{!! $product->description !!}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sizes_description" class="form-label">Medidas:</label>
                            <textarea class="form-control editor" name="sizes_description" cols="30"
                                      rows="10">{!! $product->sizes_description !!}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="model_reference" class="form-label">Referencia modelo:</label>
                            <textarea class="form-control editor" name="model_reference" cols="30"
                                      rows="10">{!! $product->model_reference !!}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{{asset('adminkit/js/app.js')}}"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- CKEditor desde CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        document.querySelectorAll('.editor').forEach((element) => {
            console.log(element);
            ClassicEditor
                .create(element)
                .catch(error => console.error(error));
        });
    </script>


    <script>


        $(document).ready(function () {
            // ID del producto desde Blade
            const productId = '{{ $product->id }}';
            // URL para cargar talles
            const listUrl = `/api/products/${productId}/list-sizes`;

            // Si ya existe una tabla, la destruimos
            if ($.fn.DataTable.isDataTable('#productSizes')) {
                $('#productSizes').DataTable().destroy();
            }
            $('#productSizes').empty();

            // Inicializamos la DataTable
            const table = $('#productSizes').DataTable({
                deferRender: true,
                autoWidth: true,
                paging: true,
                stateSave: true,
                processing: true,
                ajax: listUrl,
                columns: [
                    {
                        title: 'TALLE',
                        data: 'size'
                    },
                    {
                        title: 'COLOR',
                        data: 'color',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return `
          <div class="d-flex gap-2">

<div style="
            background-color: ${data};
            width: 32px;
            height: 32px;
            border: 1px solid #ccc;
            border-radius: 4px;
          " title="${data}"></div> <p class="m-0">${data}</p>
</div>
        `;
                            }
                            // Para ordenamiento, filtrar, etc., devolvemos el valor crudo
                            return data;
                        }
                    },
                    {
                        title: "NOMBRE COLOR",
                        data: 'color_name'
                    },
                    {
                        title: 'STOCK',
                        data: 'stock',
                        render: function (data, type, row) {
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
                    },
                ]
            });

            // Delegamos el evento change sobre los inputs
            $('#productSizes tbody').on('change', '.stock-input', function () {
                const $input = $(this);
                const newStock = $input.val();
                const productVariantId = $input.data('id');

                $.ajax({
                    url: `/api/products-variants/${productVariantId}/update-stock`,
                    type: 'PUT',
                    data: {stock: newStock},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Opcional: actualizar el dato en la tabla sin recargarla
                        const row = table.row($input.closest('tr'));
                        row.data($.extend({}, row.data(), {stock: newStock})).draw(false);
                        // Notificación opcional
                        console.log('Stock actualizado:', response);
                    },
                    error: function (xhr) {
                        alert('Error al actualizar el stock.');
                    }
                });
            });
        })
        ;


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
