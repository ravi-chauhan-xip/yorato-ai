@if($model->status == \App\Models\SupportTicket::STATUS_OPEN)<span
    class="btn btn-success btn-sm waves-effect waves-light"> Open </span>@endif
@if($model->status == \App\Models\SupportTicket::STATUS_CLOSE)<span
    class="btn btn-danger btn-sm waves-effect waves-light"> Closed </span>@endif
