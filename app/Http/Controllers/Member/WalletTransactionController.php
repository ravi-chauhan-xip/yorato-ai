<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\WalletTransactionListBuilder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return WalletTransactionListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }
}
