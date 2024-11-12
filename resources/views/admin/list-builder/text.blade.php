<div class="form-group col-sm-6 col-md-3 col-lg-2">
    <label>{{ $column->name }}</label>
    <input type="text" name="{{ $column->property }}" class="form-control" placeholder="{{ $column->name }}"
           value="{{ request(Str::replace('.', '_', $column->property)) }}">
</div>
