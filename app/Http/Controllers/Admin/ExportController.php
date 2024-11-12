<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\ExportListBuilder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return ExportListBuilder::render([
            'admin_id' => $this->admin->id,
        ]);
    }
}
