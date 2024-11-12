<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessPlan;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class BusinessPlanController extends Controller
{
    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws ValidationException
     */
    public function show(Request $request): RedirectResponse|Renderable
    {
        $business_plan = BusinessPlan::first();

        if ($request->isMethod('post')) {

            $rules['status'] = 'required';

            $this->validate($request, $rules);

            if (isset($business_plan)) {
                $business_plan->status = $request->status;
                $business_plan->save();
            } else {

                $business_plan = BusinessPlan::create([
                    'status' => $request->status,
                ]);
            }

            if ($fileName = $request->get('website_business_plan')) {
                $business_plan->addMediaFromDisk($fileName)
                    ->toMediaCollection(BusinessPlan::MC_WEBSITE_PLAN);
            }

            return redirect()->route('admin.business-plan.show')
                ->with(['success' => 'Business plan updated successfully']);
        }

        return view('admin.business-plan.show', ['business_plan' => $business_plan]);
    }
}
