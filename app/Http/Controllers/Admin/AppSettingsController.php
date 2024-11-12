<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AppSettingsController extends Controller
{
    public function show(): Renderable
    {
        return view('admin.app-settings.create', [
            'androidApkUrl' => settings()->getFileUrl('apk_url'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $this->validate($request, [
            'androidMaintenance' => 'required',
            'maintenanceMessage' => 'required',
        ]);

        try {
            settings(['android.maintenance' => (bool) $request->input('androidMaintenance')]);
            settings(['android.maintenanceMessage' => $request->input('maintenanceMessage')]);

            return redirect()->route('admin.settings.app-setting')->with(['success' => 'Updated successfully']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    /**
     * @throws ValidationException
     */
    public function apkUpload(Request $request): JsonResponse|RedirectResponse
    {
        $this->validate($request, [
            'androidVersion' => ['required', 'regex:/^\d+(\.\d+)+$/'],
            'androidHardUpdate' => 'required',
            'webHardUpdate' => 'required',
        ], [
            'androidHardUpdate.required' => 'The android update is required',
            'webHardUpdate.required' => 'The web update is required',
        ]);

        if ($request->get('webHardUpdate') === '1') {
            $this->validate($request, [
                'androidApkUrl' => 'required',
            ]);
        }

        if ($request->get('webHardUpdate') === '0'
            && $request->get('androidHardUpdate') === '0'
        ) {
            return redirect()->back()->with(['error' => 'Web or Android update is mandatory']);
        }

        try {
            settings(['android.version' => $request->input('androidVersion')]);
            settings(['android.hardUpdate' => (bool) $request->input('androidHardUpdate')]);
            settings(['web.hardUpdate' => (bool) $request->input('webHardUpdate')]);

            if ($fileName = $request->get('androidApkUrl')) {
                $filePath = 'tmp/'.Str::beforeLast($fileName, '.');

                settings()->attachMedia('apk_url', $filePath, settings('company_name').'-'.settings('android.version').'.apk', true);
            }

            return redirect()->route('admin.app-settings.show')->with(['success' => 'Updated Successfully']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }
}
