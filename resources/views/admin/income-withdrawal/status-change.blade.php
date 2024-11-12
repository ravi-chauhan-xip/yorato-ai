<form action="{{ route('admin.income-withdrawal-requests.status-update') }}" method="post" id="statusChangeForm">
    @csrf
    <div class="row">
        <div class="form-group col-md-3 col-8">
            <label>Status</label>
            <select name="changeStatus" class="form-control" data-toggle="select2" required>
                <option value="">Select Status</option>
                <option value="{{\App\Models\IncomeWithdrawalRequest::STATUS_COMPLETED}}"> Completed</option>
            </select>
        </div>
        <div class="form-group col-md-3 col-4">
            <label for="">&nbsp;</label><br>
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="disableButton" disabled>
                Submit
            </button>
        </div>
    </div>
</form>
