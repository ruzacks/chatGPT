@extends('layouts.default')
@push('styles')
    <style>
        .simple-pagination ul {
            margin: 0 0 20px;
            padding: 0;
            list-style: none;
            text-align: center;
        }

        .simple-pagination li {
            display: inline-block;
            margin-right: 5px;
        }

        .simple-pagination li a,
        .simple-pagination li span {
            color: #666;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #EEE;
            background-color: #FFF;
            box-shadow: 0px 0px 10px 0px #EEE;
        }

        .simple-pagination .current {
            color: #FFF;
            background-color: #FF7182;
            border-color: #FF7182;
        }

        .simple-pagination .prev.current,
        .simple-pagination .next.current {
            background: #e04e60;
        }
    </style>
@endpush
@section('content')
    <div class="container  pt-8">
        <div class="row">
            <div class="col-lg-3">
                @include('elements.sidebar')
            </div>
            <div class="col-lg-9 product_list">
                <div class="mb-2 d-flex align-items-center justify-content-between flex-lg-row flex-column">
                    <h2 class="title_small mb-0">{{ __('products.all_pro') }}</h2>
                    @if (auth()->user() && auth()->user()->roles[0]->name=='admin')
                        <a href="{{ url('/admin/create-product') }}" class="btn_main">{{ __('products.add') }}</a>
                    @else
                        <a href="{{ url('/create-product') }}" class="btn_main">{{ __('products.add') }}</a>
                    @endif
                </div>
                <div class="section_box py-4 mb-3 add_prdBox ">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="currentListing-tab" data-bs-toggle="tab"
                                data-bs-target="#currentListing" type="button" role="tab"
                                aria-controls="currentListing" aria-selected="true">{{ __('products.all_pro') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="currentListing" role="tabpanel"
                            aria-labelledby="currentListing-tab">
                            <div class="search_prod_wrap row align-items-center justify-content-between mt-3 mb-3">
                                <div class="col-lg-6">
                                    <input class="form_field_cst ff_search w-100 mb-md-0 " class="search" type="text"
                                        placeholder="{{ __('products.search_products') }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex">
                                        <select class="form-select mb-md-0 status_id" name="status_id"
                                            aria-label="Default select example">
                                            <option selected value=null>{{ __('products.actions') }}</option>
                                            <option value="1">{{ __('products.active') }}</option>
                                            <option value="2">{{ __('products.inactive') }}</option>
                                        </select>
                                        <select class="form-select mb-md-0 group_id" name="group_id"
                                            aria-label="Default select example">
                                            <option selected="" value=null>{{ __('products.all_pro') }}</option>
                                            <option value="1">{{ __('products.retail') }}</option>
                                            <option value="2">{{ __('products.whole') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <span hidden id="user-role">{{ auth()->user()->roles[0]->name }}</span>

                            <div class="table-responsive">
                                <table class="table prodlistTB" id="products-table">
                                    <thead>
                                        <tr>

                                            <th scope="col" class="pe-0 vtbTi">{{ __('products.title') }}</th>
                                            <th scope="col"></th>
                                            <th scope="col">{{ __('products.status') }}</th>
                                            <th scope="col">{{ __('products.published') }}</th>
                                            <th scope="col">{{ __('products.amount') }}</th>
                                            {{-- <th scope="col">{{ __('products.sale') }}</th> --}}
                                            <th scope="col">{{ __('products.actions') }}</th>
                                            @if (auth()->user() && auth()->user()->roles[0]->name=='admin')
                                                <th scope="col">Active</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                @if ($product['images'])
                                                    <td class="pe-0">
                                                        <a href="#!" data-toggle="modal" data-target="#bidding-{{$product->id}}">
                                                            <img class="variantImgtb" src="{{ $product['images'][0]['thumbnail'] }}" alt="your image" />
                                                        </a>
                                                    </td>
                                                @else
                                                    <td class="ps-0"><img class="variantImgtb" width="90"
                                                            height="50"
                                                            @if ($product->getFirstMediaUrl('images')==null)
                                                                src="{{ asset('assets/images/icon/uplaodPrd.png') }}"
                                                            @else
                                                                src="{{ $product->getFirstMediaUrl('images') }}"
                                                            @endif
                                                            alt="your image" />
                                                        </td>
                                                @endif
                                                <td>
                                                    <a href="#!" data-toggle="modal" data-target="#bidding-{{$product->id}}">
                                                        {{ $product['title'] }}<br>{{ $product['sku'] }}
                                                    </a>
                                                </td>
                                                @if ($product['status_id'] == 1)
                                                    <td><span class="plst_active"></span>{{ $product['status'] }}</td>
                                                @else
                                                    <td><span class="plst_inactive"></span>{{ $product['status'] }}</td>
                                                @endif
                                                <td>{{ $product['creation_date'] }}</td>

                                                @if ($product['type_id'] == 1 && $product['group_id'] == 1)
                                                    <td>${{ $product['price_usd'] }}<br>L.L{{ $product['price_lbp'] }}

                                                    </td>
                                                @endif
                                                @if ($product['type_id'] == 1 && $product['group_id'] == 2)
                                                    <td>${{ $product['price_from_usd'] }} -
                                                        {{ $product['price_to_usd'] }}<br>L.L{{ $product['price_to_lbp'] }}
                                                        - {{ $product['price_to_lbp'] }}
                                                    </td>
                                                @endif
                                                @if ($product['type_id'] == 3 && $product['group_id'] == 3)
                                                    <td>${{ $product['price_to_usd'] }}
                                                    </td>
                                                @endif

                                                {{-- for variable product --}}
                                                @if ($product['type_id'] == 2)
                                                    @php
                                                        $price_usd = $price_lbp = "";
                                                        if(sizeof($product['variations']))
                                                        {
                                                            $collection = collect($product['variations']);
                                                            $price_usd  = '$ '   . $collection->min('price_usd') . ' - ' . $collection->max('price_usd');
                                                            $price_lbp  = 'L.L ' . $collection->min('price_lbp') . ' - ' . $collection->max('price_lbp');
                                                        }

                                                    @endphp
                                                    <td>
                                                        {{ $product->price_usd }}<br/>
                                                        {{ $price_lbp }}
                                                    </td>
                                                @endif

                                                {{-- for variable product --}}
                                                @if ($product->group_id == 3)
                                                    <td>
                                                        ${{ number_format($product->price_usd) }}
                                                    </td>
                                                @endif


                                                {{-- @if (isset($product['discount_from_date']))
                                                    <td>From: {{ $product['discount_from_date'] }}<br>To:
                                                        {{ $product['discount_to_date'] }}</td>
                                                @else
                                                    <td> <br> </td>
                                                @endif --}}
                                                <td>
                                                    <span>
                                                        @if (auth()->user() && auth()->user()->roles[0]->name=='admin')
                                                            <a href="{{ url('admin/edit-product', [$product['id']]) }}"><i
                                                                    class="fas fa-edit">
                                                                </i>
                                                            </a>
                                                        @else
                                                            <a href="{{ url('edit-product', [$product['id']]) }}"><i
                                                                    class="fas fa-edit">
                                                                </i>
                                                            </a>
                                                        @endif
                                                    </span>
                                                    <span>
                                                        <a href="javascript:void(0)" class="delete"
                                                            data-id="{{ $product['id'] }}">
                                                            <i class="fas fa-trash-alt "></i>
                                                        </a>
                                                    </span>
                                                </td>
                                                @if (auth()->user() && auth()->user()->roles[0]->name=='admin')
                                                    <td>
                                                        <form action="{{ route('admin.products.activate')}}" id="activation-form-{{ $product['id'] }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                                            <span>
                                                                @if ($product->status_id=='1')
                                                                    <a href="javascript:void(0)" onclick="document.getElementById('activation-form-{{ $product['id'] }}').submit()" class="text-danger">
                                                                        Deactivate
                                                                    </a>
                                                                @else
                                                                    <a href="javascript:void(0)" onclick="document.getElementById('activation-form-{{ $product['id'] }}').submit()" class="text-success">
                                                                        Activate
                                                                    </a>
                                                                @endif
                                                            </span>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>



                                            <div class="modal fade bd-example-modal-lg" id="bidding-{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="bidding" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <h3 class="title_small mb-3">Bid List</h3>
                                                            <div class="bidlist_wrap">
                                                                <div class="bidlist_header d-lg-flex align-items-center d-none">
                                                                    <p class="imgbider bidheader mb-0 font600 textBlack">Image</p>
                                                                    <p class="namebider bidheader mb-0 font600 textBlack"> Name</p>
                                                                    <p class="amountbider bidheader mb-0 font600 textBlack">Bid Amount ( $ )</p>
                                                                    <p class="timebid bidheader mb-0 font600 textBlack">Bid Time</p>
                                                                </div>
                                                                <div class="bidlist_body overflow-scroll">
                                                                    @php
                                                                        $auction_bid    =   \App\Models\ProductBid::where('product_id',$product->id)->get();
                                                                        $highest_bid    =   \App\Models\ProductBid::where('product_id',$product->id)->pluck('bid_amount')->max();
                                                                    @endphp

                                                                    @foreach ($auction_bid as $bid)
                                                                        <div class="bidlist_box d-flex align-items-center flex-md-nowrap mb-2 flex-wrap">
                                                                            <img src="{{ asset('assets/images/products/avatarprofile.png') }}" alt="avatar"
                                                                                class="avatarbider imgbider">
                                                                            <div class="bidernameBody namebider">
                                                                                <p class="bidername  mb-0 font600 textBlack">
                                                                                    {{\App\Models\UserField::where('user_id',$bid->user_id)->pluck('first_name')->first()}}
                                                                                    {{\App\Models\UserField::where('user_id',$bid->user_id)->pluck('last_name')->first()}}
                                                                                </p>
                                                                            </div>
                                                                            <p class="bidamountBody amountbider mb-0 font600 textBlack">
                                                                                <span>Bid Amount ( $ )</span>
                                                                            ${{number_format($bid->bid_amount)}}
                                                                            </p>
                                                                            <p class="bidtimeBody timebid mb-0 textBlack">
                                                                                <span>Bid Time</span>
                                                                                {{date('Y-m-d',strtotime($bid->created_at))}} At {{date('H:i',strtotime($bid->created_at))}}
                                                                            </p>
                                                                        </div>
                                                                    @endforeach
                                                                    <!-- bid -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="customer-reviews-box customer-bid-box text-center pb-10">
                                                                <h2 class="title_small mb-0">
                                                                    Highest Bid
                                                                </h2>
                                                                {{-- <p class="mb-0 textBlack">(Per Unit):</p> --}}
                                                                <div class="crb-price">
                                                                        ${{number_format($highest_bid==0?$product->price_usd:$highest_bid)}}
                                                                </div>
                                                                <div class="crb-text d-flex align-items-center justify-content-between mt-2">
                                                                    Starting Price:
                                                                    <span class="textBlack">
                                                                        ${{number_format($product->price_usd)}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="product-pagination"></div>
                            {{-- pagination --}}
                            {{-- <nav aria-label="...">
                                <ul class="pagination">
                                    @foreach ($products['meta']['links'] as $page)
                                        @if ($page['active'])
                                            <li class="page-item  active">
                                                <span class="page-link" data-url="{{ $page['url'] }}">
                                                    {!! $page['label'] !!}
                                                    <span class="sr-only">(current)</span>
                                                </span>
                                            </li>
                                        @else
                                            @if (isset($page['url']))
                                                <li class="page-item"><a class="page-link page"
                                                        data-url="{{ $page['url'] }}"
                                                        href="javascript:void(0)">{!! $page['label'] !!}</a></li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span data-url="{{ $page['url'] }}"
                                                        class="page-link">{!! $page['label'] !!}</span>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach

                                </ul>
                            </nav> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

        <script>
            // $('#user-role').innerTEXT;
            var userRole    =   document.getElementById('user-role').innerHTML;
            var _token = $('meta[name="_token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': `Bearer  ${_token}`,
                },
            })
            let searchParams = {}
            $('.status_id').on('change', function() {
                var status_id = $(this).val();
                if ($(this).hasClass('deleted')) {
                    searchParams = {
                        ...searchParams,
                        deleted: 1
                    }
                } else {
                    searchParams = {
                        ...searchParams,
                        deleted: 0
                    }
                }
                searchParams = {
                    ...searchParams,
                    status_id: status_id
                }
                filters(searchParams)
            });

            $('.group_id').on('change', function() {
                var group_id = $(this).val();
                if ($(this).hasClass('deleted')) {
                    searchParams = {
                        ...searchParams,
                        deleted: 1
                    }
                } else {
                    searchParams = {
                        ...searchParams,
                        deleted: 0
                    }
                }
                searchParams = {
                    ...searchParams,
                    group_id: group_id
                }
                filters(searchParams)
            });

            $('.ff_search').keyup(function() {
                var search = $(this).val();
                if ($(this).hasClass('deleted')) {
                    searchParams = {
                        ...searchParams,
                        deleted: 1
                    }
                } else {
                    searchParams = {
                        ...searchParams,
                        deleted: 0
                    }
                }
                searchParams = {
                    ...searchParams,
                    search: search
                }
                filters(searchParams)
            });


            function filters(searchParams) {


                $.ajax({
                    // enctype: 'multipart/form-data',
                    url: `{{ url('/products/filter') }}?status_id=${searchParams.status_id ?? null}&group_id=${searchParams.group_id ?? null}&title=${searchParams.search ?? null}&deleted=${searchParams.deleted ==1}`,
                    method: 'get',

                    success: function(response) {
                        console.log(searchParams.deleted);
                        if (searchParams.deleted == 0) {
                            $(".prodlistTB tbody").html(response);
                            paginateProducts()
                        } else {
                            $(".prodlistDeleted tbody").html(response);
                            paginateDeleted()
                        }
                    },
                    error: function(response) {

                        var data = $.parseJSON(response.responseText);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    }
                });
            }

            $(document).on('click', '.delete', function() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You want to remove this product!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteUrl   = `{{ url('/products') }}/${id}`;
                        if (userRole=='admin') {
                            deleteUrl   =   `{{ url('/admin/products') }}/${id}`;
                        }
                        Swal.showLoading();
                        var id = $(this).attr('data-id');
                        $.ajax({
                            // enctype: 'multipart/form-data',
                            url: deleteUrl,
                            method: 'delete',

                            success: function(result) {
                                Swal.close();
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    'Your Product has been deleted.',
                                    'success'
                                )
                                window.location.href = "{{ url('/products') }}";
                            },
                            error: function(response) {
                                Swal.close();
                                var data = $.parseJSON(response.responseText);
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your data is safe :)',
                            'success'
                        )
                    }
                })
            });

            $(document).on('click', '.restore', function() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You want to restore this product!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        var id = $(this).attr('data-id');
                        Swal.showLoading();
                        $.ajax({
                            // enctype: 'multipart/form-data',
                            url: `{{ url('/api/v1/products/${id}/restore') }}`,
                            method: 'get',

                            success: function(result) {
                                Swal.close();
                                swalWithBootstrapButtons.fire(
                                    'Restored!',
                                    'Your Product has been restored.',
                                    'success'
                                )
                                window.location.href = "{{ url('/products') }}";
                            },
                            error: function(response) {
                                Swal.close();
                                var data = $.parseJSON(response.responseText);
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your data is safe :)',
                            'success'
                        )
                    }
                })
            });

            // function listDeletedItems() {
            //     $.ajax({
            //         // enctype: 'multipart/form-data',
            //         url: `{{ url('products/deleted') }}`,
            //         method: 'get',

            //         success: function(response) {
            //             $(".prodlistDeleted tbody").html(response);
            //             paginateDeleted()
            //         },
            //         error: function(response) {

            //             var data = $.parseJSON(response.responseText);
            //             Swal.fire({
            //                 position: 'top-end',
            //                 icon: 'error',
            //                 title: data.message,
            //                 showConfirmButton: false,
            //                 timer: 1500
            //             });

            //         }
            //     });
            // }

            // $(document).on('click', '#deletedItems-tab', function() {
            //     listDeletedItems()
            // })

            // $(document).ready(function() {
            //     listDeletedItems()
            //     paginateProducts()
            // })


            function paginateProducts() {
                var items = $("#products-table tr");
                var numItems = items.length;
                var perPage = 11;

                items.slice(perPage).hide();

                $('#product-pagination').pagination({
                    items: numItems,
                    itemsOnPage: perPage,
                    prevText: "&laquo;",
                    nextText: "&raquo;",
                    onPageClick: function(pageNumber) {
                        var showFrom = perPage * (pageNumber - 1);
                        var showTo = showFrom + perPage;
                        items.hide().slice(showFrom, showTo).show();
                    }
                });
            }

            function paginateDeleted() {
                var delItems = $("#delete-items tr");
                var delNumItems = delItems.length;
                var perPage = 11;
                delItems.slice(perPage).hide();

                $('#deleted-product-pagination').pagination({
                    items: delNumItems,
                    itemsOnPage: perPage,
                    prevText: "&laquo;",
                    nextText: "&raquo;",
                    onPageClick: function(pageNumber) {
                        var showFrom = perPage * (pageNumber - 1);
                        var showTo = showFrom + perPage;
                        delItems.hide().slice(showFrom, showTo).show();
                    }
                });
            }
        </script>
    @endpush
@endsection
