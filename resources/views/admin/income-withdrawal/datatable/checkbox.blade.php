@if($model->status == \App\Models\IncomeWithdrawalRequest::STATUS_PENDING)
    <div class="form-check form-check-primary">
        <input class="form-check-input productsCheckBox checkBox chk_boxes1" type="checkbox"
               id="singleCheckbox{{$model->id}}"
               value="{{ $model->id }}" name="change_status[]" form="statusChangeForm">
        <label class="form-check-label" for="singleCheckbox{{$model->id}}"></label>
    </div>

@endif

