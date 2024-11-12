@if($model->isCompleted())
    <a href="{{ Storage::url($model->file_path) }}" class="btn btn-dark btn-sm font-weight-semibold">
        <i class="uil uil-file-download-alt"></i> Download
    </a>
@else
    --
@endif
