@extends('admin.layouts.master')

@section('title', 'Daily Income Percentage')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.daily-income-percentage.update') }}" class="row">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="form-group col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="dailyIncomePercentage"
                                           min="0" id="dailyIncomePercentage"
                                           value="{{ old('dailyIncomePercentage', $dailyIncomePercentage->percentage) }}"
                                           required>
                                    <label for="dailyIncomePercentage" class="required">Daily Income Percentage (%)</label>
                                </div>
                                @foreach($errors->get('dailyIncomePercentage') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-12 mb-3">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="uil uil-message me-1"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
