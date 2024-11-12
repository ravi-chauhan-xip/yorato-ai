<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyIncomePercentage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DailyIncomePercentageController extends Controller
{
    public function index(): Renderable
    {
        return view('admin.daily-income-percentage.index', ['dailyIncomePercentage' => DailyIncomePercentage::where('status', DailyIncomePercentage::STATUS_ACTIVE)->first()]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'dailyIncomePercentage' => 'required|numeric|min:0',
        ]);

        \DB::transaction(function () use ($request) {
            DailyIncomePercentage::where('status', DailyIncomePercentage::STATUS_ACTIVE)->update(['status' => DailyIncomePercentage::STATUS_INACTIVE]);

            DailyIncomePercentage::create([
                'percentage' => $request->input('dailyIncomePercentage'),
                'status' => DailyIncomePercentage::STATUS_ACTIVE,
            ]);

            //        settings(['dailyIncomePercentage' => $request->input('dailyIncomePercentage')]);
        });

        return redirect()->route('admin.daily-income-percentage.index')
            ->with(['success' => 'Daily Income Percentage updated successfully']);
    }
}
