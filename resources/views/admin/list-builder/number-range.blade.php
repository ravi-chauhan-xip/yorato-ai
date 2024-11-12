<div class="form-group col-sm-6 col-md-3 col-lg-2">
    <label>{{ $column->name }}</label>
    <div class="row">
        <div class="col-6">
            <input type="text" name="min_{{ $column->property }}" class="form-control" step="any"
                   placeholder="Min" value="{{ request(Str::replace('.', '_', "min_$column->property")) }}">
        </div>
        <div class="col-6">
            <input type="text" name="max_{{ $column->property }}" class="form-control" step="any"
                   placeholder="Max" value="{{ request(Str::replace('.', '_', "max_$column->property")) }}">
        </div>
    </div>
</div>
