@extends('admin.layouts.master')

@section('title') Member TDS Report @endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Member TDS Report'
         ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="row mb-2">
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <select name="monthYr" id="" class="form-control">
                                        <option value="">Select Month</option>
                                        @foreach($months as $month)
                                            <option
                                                value="{{ $month->monthYear }}" {{ $month->monthYear == request()->get('monthYr') ? 'selected' : '' }} >{{ $month->monthYear }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bx bx-search"></i>
                                </button>
                                <a href="{{ route('admin.tds.index') }}" class="text-sm-right">
                                    <button type="button" class="btn btn-danger btn-lg">
                                        <i class="uil uil-refresh"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                    @if(request()->all())
                        @if(isset($records) && count($records)>0 )
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Month
                                        </th>
                                        <th>
                                            Member ID
                                        </th>
                                        <th>
                                            Member Name
                                        </th>
                                        <th>
                                            Member Mobile
                                        </th>
                                        <th>
                                            PAN No
                                        </th>
                                        <th>
                                            TDS ({{ env('APP_CURRENCY') }})
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($records as $key => $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $key }}</td>
                                            <td>
                                                {{ $record->monthYear }}
                                            </td>
                                            <td>
                                                {{ $record->memberCode }}
                                            </td>
                                            <td>
                                                {{ $record->memberName }}
                                            </td>
                                            <td>
                                                {{ $record->mobileNumber }}
                                            </td>
                                            <td>
                                                {{ $record->panCard ?? '--'}}
                                            </td>
                                            <td>
                                                {{ env('APP_CURRENCY').$record->totalTds }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="float-right">
                                    {{ $records->links("pagination::bootstrap-4")}}
                                </div>
                            </div>
                        @else
                            <table class="table text-center table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        No Record Found.
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
