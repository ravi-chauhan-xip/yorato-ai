@extends('admin.layouts.master')

@section('title')
    {{ $listBuilderClass::$name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ $listBuilderClass::$name }}</h4>
                    <div class="heading-elements">
                        @if($listBuilderClass::createUrl())
                            <a href="{{ $listBuilderClass::createUrl() }}" class="btn btn-primary">
                                @if(($listBuilderClass::createButtonName()))
                                    <i class="uil uil-plus"></i>
                                    <span>{{ $listBuilderClass::createButtonName() }}</span>
                                @else
                                    <i class="uil uil-plus"></i>
                                    <span>Create</span>
                                @endif
                            </a>
                        @endif

                        <a data-bs-toggle="collapse" href="#filters" role="button"
                           aria-expanded="{{ Agent::isMobile() ? 'true' : 'false'}}"
                           aria-controls="filters" class="{{ Agent::isMobile() ? 'collapsed' : ''}}">
                            <i class="uil uil-minus"></i>
                        </a>
                    </div>
                </div>
                <div id="filters" class="collapse {{ Agent::isMobile() ? '' : 'show'}}">
                    <div class="card-body mt-2">
                        <form action="{{ request()->fullUrl() }}" id="filterForm">
                            <div class="row">
                                @foreach($listBuilderClass::columns() as $column)
                                    @if($column->filterType)
                                        {!! $column->render() !!}
                                    @endif
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ request()->url() }}"
                                       class="btn btn-danger mb-lg-0 mb-3">
                                        <i class='bx bx-refresh me-1'></i> Reset
                                    </a>
                                    <button type="submit" name="filter" value="filter"
                                            onclick="shouldExport = false;"
                                            class="btn btn-primary mb-lg-0 mb-3">
                                        <i class='bx bxs-filter-alt me-1'></i> Apply Filter
                                    </button>
                                    <button type="submit" name="export" value="csv"
                                            onclick="shouldExport = true;"
                                            class="btn btn-dark float-right mb-lg-0 mb-3">
                                        <i class='bx bxs-download me-1'></i> Export
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="my-0"/>

                <div class="card-datatable table-responsive">
                    <div id="beforeDataTable"></div>

                    <table class="table text-nowrap" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            @foreach($listBuilderClass::columns() as $column)
                                @if($column->title()=='Select')
                                    <th>
                                        <div class="form-check form-check-primary mb-0">
                                            <input class="form-check-input  checkBox chk_boxes1"
                                                   type="checkbox" id="singleCheckbox"
                                                   onclick="checkAll(this)">
                                            <label class="form-check-label" for="singleCheckbox"></label>
                                        </div>
                                    </th>
                                @else
                                    <th>{{ $column->title() }}</th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            @foreach($listBuilderClass::columns() as $column)
                                <th>{{ $column->title() }}</th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-css')
    <style>
        .form-select {
            background-position: right 0.35rem center;
        }

        div.dataTables_wrapper div.dataTables_length select {
            width: 45px;
            display: inline-block;
        }
    </style>

@endpush
@push('page-javascript')
    <script>
        const beforeDataTable = $('#beforeDataTable');
        const dataTable = $('#dataTable').DataTable({
            ajax: {
                url: '{{ request()->fullUrl() }}',
            },
            columns: [
                {data: 'DT_RowIndex', width: '5%'},
                    @foreach($listBuilderClass::columns() as $column)
                    {{--                    @if($column->hasPermission($listBuilderClass::$permissionPrefix))--}}
                {
                    data: "{{ $column->property }}"
                },
                {{--                @endif--}}
                @endforeach
            ]
        });
        dataTable.on('draw', function (event, dt) {
            if (
                dt.hasOwnProperty('json')
                && dt.json.hasOwnProperty('beforeDataTable')
                && dt.json.beforeDataTable
            ) {
                beforeDataTable.html(dt.json.beforeDataTable);
            }
            $(".image-popup").magnificPopup({
                type: "image",
                closeOnContentClick: !1,
                closeBtnInside: !1,
                mainClass: "mfp-with-zoom mfp-img-mobile",
                image: {
                    verticalFit: !0, titleSrc: function (e) {
                        return e.el.attr("title")
                    }
                },
                gallery: {enabled: !0},
                zoom: {
                    enabled: !0, duration: 300, opener: function (e) {
                        return e.find("img")
                    }
                }
            });
        });
    </script>
@endpush
