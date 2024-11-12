@if($model->status == \App\Models\SupportTicket::STATUS_CLOSE)
    <a href="{{ route('admin.support-ticket.details.get',['id' => $model->id]) }}"
       class="btn btn-dark btn-sm">
        <i class="uil uil-eye me-1"></i> View
    </a>
@else
<a href="{{ route('admin.support-ticket.details.get',['id' => $model->id]) }}"
   class="btn btn-primary btn-sm text-white">
    <i class="uil uil-edit me-1"></i> Reply</a>
<div class="badge bg-danger rounded-pill ms-auto">
    {{ $model->message()->where('messageable_type',\App\Models\Member::class)->where('is_read',0)->count() }}
</div>
@endif
