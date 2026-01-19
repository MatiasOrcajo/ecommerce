@extends('layouts.app')

@section('title')
    <title>Guía de talles - Atica</title>
@endsection

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 style="font-size: 2rem" class="my-5">Ante cualquier duda, ¡contactanos por WhatsApp!</h1>

                <img src="{{asset('/guia-talles/1.jpg')}}" alt="" class="w-100 h-auto my-3" data-bs-toggle="modal"
                     data-bs-target="#imageModal1" style="cursor: pointer">
                <img src="{{asset('/guia-talles/2.jpg')}}" alt="" class="w-100 h-auto my-3" data-bs-toggle="modal"
                     data-bs-target="#imageModal2" style="cursor: pointer">
                <img src="{{asset('/guia-talles/3.jpg')}}" alt="" class="w-100 h-auto my-3" data-bs-toggle="modal"
                     data-bs-target="#imageModal3" style="cursor: pointer">

                <!-- Modal 1 -->
                <div class="modal fade" id="imageModal1" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0">
                                <img src="{{asset('/guia-talles/1.jpg')}}" alt="" class="w-100 h-auto"
                                     style="max-height: 90vh; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal 2 -->
                <div class="modal fade" id="imageModal2" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0">
                                <img src="{{asset('/guia-talles/2.jpg')}}" alt="" class="w-100 h-auto"
                                     style="max-height: 90vh; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal 3 -->
                <div class="modal fade" id="imageModal3" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0">
                                <img src="{{asset('/guia-talles/3.jpg')}}" alt="" class="w-100 h-auto"
                                     style="max-height: 90vh; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
