@if($model->isCompleted())
    <span class="btn btn-success btn-sm">Completed</span>
@elseif($model->isPending())
    <span class="btn btn-warning btn-sm">Pending</span>
@elseif($model->isProcessing())
    <span class="btn btn-primary btn-sm">Processing</span>
@else
    N/A
@endif
