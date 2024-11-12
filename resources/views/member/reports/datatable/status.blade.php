@if(isset($model->package_id))
    <span class="btn btn-primary btn-sm waves-effect waves-light">Paid</span>
@else
    <span class="btn btn-warning btn-sm waves-effect waves-light">UnPaid</span>
@endif
