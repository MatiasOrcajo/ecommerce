@extends('layouts.app-adminkit')

@section('title')
    <title>Panel - Atica</title>
@endsection

@section('content')
    <div class="container-fluid p-0">
        <h2 class="my-4">Panel de pedidos</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Tabla de productos -->
        <div class="card">
            <div class="card-header">Lista de Pedidos</div>
            <div class="card-body">
                <table class="table table-bordered" id="orders_list">

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
        let url = '/api/list-orders-list'
        let table = $('#orders_list').DataTable();
        table.destroy();
        $('#orders_list').empty();


        $('#orders_list').DataTable({
            deferRender: true,
            "autoWidth": true,
            "paging": true,
            stateSave: true,
            "processing": true,
            "ajax": url,
            order: [[0, "desc"]],
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            "columns": [
                {
                    title: "ID",
                    data: 'id',
                    width: "1%"
                },
                {
                    title: "CÓDIGO",
                    data: 'code',
                    width: "1%"
                },
                {
                    title: "CLIENTE",
                    data: 'customer_name'
                },
                {
                    title: "PEDIDO",
                    data: 'order',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            let html = $('<textarea/>').html(data).text(); // decodifica el HTML
                            return html;
                        }
                        return data;
                    }
                },
                {
                    title: "TOTAL",
                    data: 'total'
                },
                {
                    title: "DIRECCIÓN",
                    data: 'shipping_address'
                },
                {
                    title: "STATUS",
                    data: 'status',
                    render: function (data, type, full, meta) {
                        if (type === 'display') {
                            let id = full.id;
                            let statusOptions = [
                                'Pago recibido',
                                'Pago fallido',
                                'Pago pendiente de aprobación',
                                'No pago',
                                'En proceso',
                                'Envío realizado'
                            ];

                            // Estados que deben deshabilitar el select
                            const disabledStatuses = [
                                'Pago fallido',
                                'No pago',
                                'Pago pendiente de aprobación'
                            ];

                            // Verificamos si el select debe estar deshabilitado
                            let disabledAttr = disabledStatuses.includes(data) ? 'disabled' : '';

                            let selectHtml = `<select class="status-select" data-id="${id}" ${disabledAttr}>`;

                            statusOptions.forEach(status => {
                                let selected = data === status ? 'selected' : '';
                                selectHtml += `<option value="${status}" ${selected}>${status}</option>`;
                            });

                            selectHtml += `</select>`;
                            return selectHtml;
                        }

                        return data;
                    }
                },

                {
                    title: "FECHA",
                    data: 'created_at'
                },
            ]
        })

        $('#orders_list').on('change', '.status-select', function () {
            let newStatus = $(this).val();
            let orderId = $(this).data('id');

            // Aquí haces lo que necesites con el cambio de estado, como una petición AJAX
            $.ajax({
                url: `/api/orders/${orderId}/update-status`,
                method: 'POST',
                data: {
                    status: newStatus
                },
                success: function (response) {
                    console.log("Estado actualizado correctamente", response);
                },
                error: function (error) {
                    console.error("Error al actualizar el estado", error);
                }
            });
        });

    })





</script>


