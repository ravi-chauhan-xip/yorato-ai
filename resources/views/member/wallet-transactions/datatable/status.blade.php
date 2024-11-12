@if($model->type == \App\Models\WalletTransaction::TYPE_DEBIT)
    <span class="btn btn-warning btn-xs waves-effect waves-light"> Debit </span>
@elseif($model->type == \App\Models\WalletTransaction::TYPE_CREDIT)
    <span class="btn btn-success btn-xs waves-effect waves-light"> Credit </span>
@endif
