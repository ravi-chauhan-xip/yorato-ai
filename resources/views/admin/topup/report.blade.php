@extends('admin.layouts.master')
@section('title')
    Topup Report
@endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Topup Report'
         ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="">
                            <tr>
                                <th class="text-uppercase" scope="col">#</th>
                                <th class="text-uppercase" scope="col">Date</th>
                                <th class="text-uppercase" scope="col">Member</th>
                                <th class="text-uppercase" scope="col">Mobile number</th>
                                <th class="text-uppercase" scope="col">No of topup</th>
                                <th class="text-uppercase" scope="col">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topups as $key => $topup)
                                <tr>
                                    <td>{{  $topups->firstItem()+$key  }}</td>
                                    <td>
                                        @if( $topup->topup_count > 0)
                                            {{ $topup->topup->first()->created_at }}
                                        @else
                                            {{ $topup->created_at }}
                                        @endif
                                    </td>
                                    <td>{{ $topup->user->name }} <br> ( {{ $topup->code }} )</td>
                                    <td>{{ $topup->user->mobile }}</td>
                                    <td>{{ $topup->topup_count }}</td>
                                    <td>
                                        @if( $topup->topup_count > 0)
                                            <a href="{{ route('admin.topup.report-details',['code'=>$topup->code]) }}">
                                                <button class="btn btn-success">View</button>
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $topups->appends(['search' => request('search')])->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
