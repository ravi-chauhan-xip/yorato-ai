@if($model->status == \App\Models\CompanyWallet::STATUS_ACTIVE)
    <a href="{{ route('admin.company-wallet.update-status',$model) }}"
       class="btn btn-danger btn-sm">
        <i class="uil uil-ban"></i>
        In-Active
    </a>
@endif
@if($model->status ==  \App\Models\CompanyWallet::STATUS_IN_ACTIVE)
    <a href="{{ route('admin.company-wallet.update-status',$model) }}"
       class="btn btn-success btn-sm">
        <i class="uil uil-check"></i>
        Active
    </a>
@endif
@if($model->locked_at)
    <a href="{{route('admin.company-wallet.update-locked-status',$model)}}"
       class="btn btn-danger btn-sm">
        <i class="uil uil-ban"></i>
        Un-Lock
    </a>
@else
    <a href="{{route('admin.company-wallet.update-locked-status',$model)}}"
       class="btn btn-success btn-sm">
        <i class="uil uil-check"></i>
        Lock
    </a>
@endif

{{--<button type="button" class="btn btn-primary btn-sm" data-toggle="modal"--}}
{{--        data-target="#exampleModal{{ $model->id }}"--}}
{{--        onclick="getWallet('{{ $model->address }}',{{ $model->id }})">--}}
{{--    <i class="uil uil-eye"></i> Wallet Amount View--}}
{{--</button>--}}
{{--<div class="modal fade" id="exampleModal{{ $model->id }}" tabindex="-1"--}}
{{--     aria-labelledby="exampleModalLabel{{ $model->id }}"--}}
{{--     aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title text-primary text-normal" id="exampleModalLabel{{ $model->id }}">{{ $model->address }}</h5>--}}
{{--                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body ps-0 pe-0">--}}
{{--                <table class="dt-advanced-search table table-bordered table-striped table-hover">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>Matic</th>--}}
{{--                        <th id="matic{{ $model->id }}"></th>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>Goldway</th>--}}
{{--                        <th id="goldway{{ $model->id }}"></th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
