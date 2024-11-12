<div class="form-group col-sm-6 col-md-3 col-lg-2">
    <label>From {{ $column->name }}</label>
    <input type="date" name="from_{{ $column->property }}" class="form-control"
           placeholder="{{ $column->name }}" value="{{ request(Str::replace('.', '_', "from_$column->property")) }}">
</div>
<div class="form-group col-sm-6 col-md-3 col-lg-2">
    <label>To {{ $column->name }}</label>
    <input type="date" name="to_{{ $column->property }}" class="form-control"
           placeholder="{{ $column->name }}" value="{{ request(Str::replace('.', '_', "to_$column->property")) }}">
</div>
