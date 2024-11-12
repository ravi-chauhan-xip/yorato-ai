@extends('admin.layouts.master')

@section('title')
    TDS Report for : {{ $monthYear->format('F Y') }}
@endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'TDS Report for : '.$monthYear->format('F Y')
         ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Member ID</th>
                                <th>Member Name</th>
                                <th>PAN Card</th>
                                <th>TDS ({{ env('APP_CURRENCY') }})</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($payoutMembers) > 0)
                                @foreach($payoutMembers as $index => $payoutMember)
                                    <tr>
                                        <td>
                                            {{ $index + 1 }}
                                        </td>
                                        <td>
                                            @include('copy-text', ['text' => $payoutMember->member->code])
                                        </td>
                                        <td>
                                            {{ $payoutMember->member->user->name }}
                                        </td>
                                        <td>
                                            @include('copy-text', ['text' => optional($payoutMember->member->kyc)->pan_card])
                                        </td>
                                        <td>
                                            {{ env('APP_CURRENCY').$payoutMember->total_tds }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        <h5 class="text-center">No data available in table</h5>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
