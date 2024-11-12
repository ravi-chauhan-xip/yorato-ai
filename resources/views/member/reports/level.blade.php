@extends('member.layouts.master')

@section('title')
    My Team
@endsection

@section('content')
    @include('member.breadcrumbs', [
                            'crumbs' => [
                                "My Team"
                            ]
                       ])
    @if(isset($levelDetails) && !empty($levelDetails) && count($levelDetails) >0)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" dir="ltr">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        Level
                                    </th>
                                    <th>
                                        Total Members
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($levelDetails as $level)
                                    <tr>
                                        <td>
                                            {{ $level['level'] }}
                                        </td>
                                        <td>
                                            {{ $level['teamCount'] }}
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-xs" target="_blank" href="{{ route('user.reports.level-detail',[
                                                        'level'=>$level['level'],
                                                        ]) }}">View Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
