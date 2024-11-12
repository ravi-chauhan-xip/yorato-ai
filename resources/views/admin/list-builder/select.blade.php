<div class="form-group col-sm-6 col-md-3 col-lg-2">
    <label>{{ $column->name }}</label>
    <select name="{{ $column->dbColumn ?: $column->property }}" class="form-control" data-toggle="select2">
        <option value="">Select {{ $column->name }}</option>
        @foreach($column->options as $optionKey => $optionName)
            <option value="{{ $optionKey }}"
                {{ request(Str::replace('.', '_', $column->dbColumn ?: $column->property)) == $optionKey ? 'selected' : '' }}
            >
                {{ $optionName }}
            </option>
        @endforeach
    </select>
</div>
