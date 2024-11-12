<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\ExportListBuilder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ExportController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): Renderable|RedirectResponse|JsonResponse
    {
        return ExportListBuilder::render([
            'user_id' => $this->user->id,
        ]);
    }
}
