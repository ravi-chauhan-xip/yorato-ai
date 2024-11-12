<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\ContactInquiryListBuilder;
use App\Models\Inquiry;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        Inquiry::with('country')->update([
            'is_read' => true,
        ]);

        return ContactInquiryListBuilder::render();
    }
}
