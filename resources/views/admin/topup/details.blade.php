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
                                <th class="text-uppercase" scope="col">Topup Date</th>
                                <th class="text-uppercase" scope="col">Pin</th>
                                <th class="text-uppercase" scope="col">Startdate</th>
                                <th class="text-uppercase" scope="col">PH</th>
                                <th class="text-uppercase" scope="col">GH1</th>
                                <th class="text-uppercase" scope="col">GH2</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details as $key => $topup)
                                <tr>
                                    <td>{{  $details->firstItem()+$key  }}</td>
                                    <td>
                                        {{ $topup->created_at }}
                                    </td>
                                    <td>{{ $topup->pin->code }}</td>
                                    <td>{{ $topup->start_at }}</td>
                                    <td>
                                        @if($topup->ph_done==0)
                                            Pending
                                        @elseif($topup->ph_done == 1)
                                            done
                                        @endif

                                    </td>
                                    <td>
                                        @if($topup->gh_1_done==0)
                                            Pending
                                        @elseif($topup->gh_1_done == 1)
                                            done
                                        @endif

                                    </td>
                                    <td>
                                        @if($topup->gh_2_done==0)
                                            Pending
                                        @elseif($topup->gh_2_done == 1)
                                            done
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            {{ $details->appends(['search' => request('search')])->links() }}
        </div>
    </div>
@endsection
