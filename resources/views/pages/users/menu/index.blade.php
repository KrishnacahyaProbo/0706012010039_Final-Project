@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-lg-6 mb-lg-10 mb-8 py-4" data-kt-sticky="true"
                data-kt-sticky-name="app-toolbar-sticky" data-kt-sticky-offset="{default: 'false', lg: '300px'}">
                <!--begin::Toolbar container-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-lg-6 mb-lg-10 mb-8 py-4" data-kt-sticky="true"
                            data-kt-sticky-name="app-toolbar-sticky"
                            data-kt-sticky-offset="{default: 'false', lg: '300px'}">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container"
                                class="app-container container-xxl d-flex flex-stack flex-lg-nowrap flex-wrap gap-4">
                                <!--begin::Toolbar wrapper-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Logo-->
                                    <img src="{{ asset('assets/media/svg/brand-logos/layer.svg') }}" class="w-40px me-5"
                                        alt="" />
                                    <!--end::Logo-->
                                    <!--begin::Page title-->
                                    <div class="page-title">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex fw-bold fs-3 flex-column justify-content-center my-0 text-gray-900">
                                            Menu Vendor</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <!--begin::Navbar-->
                                <div class="card row">
                                    <div class="col-md-4 mb-2 mt-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="addMenuItem()">
                                            Tambah Menu
                                        </button>
                                    </div>

                                    <div class="card-body pb-0 pt-9">
                                        <!--begin::Details-->
                                        <div class="table-responsive">
                                            <!-- Your table goes here -->

                                            <table id="menuTable" class="table-striped table-hover table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name Menu</th>
                                                        <th>Descriptions</th>
                                                        <th>Image</th>
                                                        <th>Type</th>
                                                        <!-- Add more columns as needed -->
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('components.modal')
            @endsection

            @section('js')
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="{{ asset('js/validate/jquery.validate.min.js') }}"></script>
                <script src="{{ asset('js/menu.js') }}"></script>
            @endsection
