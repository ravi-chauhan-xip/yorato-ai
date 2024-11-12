@extends('admin.layouts.master')
@section('title') Pending Invoice  @endsection

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a class="text-muted fw-light" href="{{ route('admin.dashboard') }}">Home</a>/
        </span> Pending Invoice
    </h5>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row mb-2">
                        <div class="col-sm-3 col-12 mb-1">
                            <input type="text" name="daterange"  class="form-control date" id="daterangetime" data-toggle="date-picker" data-time-picker="true" data-locale="{'format': 'DD/MM hh:mm A'}" value="{{request()->get('daterange')}}">
                        </div>
                        <div class="col-sm-5 col-9 mb-1">
                            <div class="form-group mb-2">
                                <label for="inputPassword2" class="sr-only">Search</label>
                                <input type="search" name="search" value="{{ request()->get('search') }}" class="form-control" id="inputPassword2" placeholder="Search...">
                            </div>
                        </div>
                        <div class="col-sm-1 col-3">
                            <div class="text-sm-right">
                                <button type="submit" class="btn btn-primary mb-2 mr-1"><i class='bx bx-search' ></i></button>
                            </div><!-- end col-->
                        </div>
                        <div class="col-sm-1 col-3">
                            <div class="text-sm-right">
                                <a href="{{-- {{ route('admin.invoices.invoice.pending') }} --}}">
                                <button type="button" class="btn btn-success mb-2 mr-1">
                                    <i class="fa fa-sync"></i></button></a>
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Member ID</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $key => $invoice)
                                        <tr>
                                            <td>{{ $invoices->firstItem() + $key }}</td>
                                            <td>
                                                {{$invoice->member->user->name}}
                                            </td>
                                            <td>
                                                <span class="text-primary">{{ $invoice->member->code }}</span>
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                href="{{ $invoice->getFirstMediaUrl(App\Models\MemberInvoice::MC_INVOICE) }}"
                                                class="btn btn-success">
                                                Image Link
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $invoices->links() }}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
