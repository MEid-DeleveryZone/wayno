@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Product Inquiries'])
@section('css')
{{-- <link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" /> --}}
@endsection
@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Product Inquiries') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_vendor">{{$total_vendor}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{ __('Total Unique Vendor Count') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-dump-truck text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_product">{{$total_product}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">{{ __('Total Unique Product Count') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped" id="inquiry_datatable" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Company Name') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Product') }}</th>
                            </tr>
                        </thead>
                        <tbody id="post_list">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        function_name();
        function function_name() {
            $('#inquiry_datatable').DataTable({
                "scrollX": true,
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 50,
                "dom": '<"toolbar">Bfrtip',
                language: {
                    search: "",
                    searchPlaceholder: "Search By Name",
                    paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [],
                ajax: {
                  url: "{{ route('inquiry.filter') }}",
                  data: function (d) {
                    d.search = $('input[type="search"]').val();
                  }
                },
                columns: [
                    {data: 'name', name: 'name',orderable: false, searchable: false},
                    {data: 'email', name: 'email',orderable: false, searchable: false},
                    {data: 'phone_number', name: 'phone_number',orderable: false, searchable: false},
                    {data: 'company_name', name: 'company_name',orderable: false, searchable: false},
                    {data: 'message', name: 'message',orderable: false, searchable: false},
                    {data: 'sku', name: 'sku', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return "<a href='"+full.view_url+"' target='_blank'>"+full.product.primary.title+"</a> ";
                    }},
                ]
            });
        }
    });
</script>
@endsection
@section('script')
    <script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection