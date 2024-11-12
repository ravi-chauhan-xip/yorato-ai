@if($model->isActive())
    <span class="btn btn-success btn-sm">Active</span>
@elseif($model->isInActive())
    <span class="btn btn-danger btn-sm">In Active</span>
@endif
