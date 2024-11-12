<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Member;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Sentry;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected ?User $user;

    protected ?Member $member;

    protected ?Admin $admin = null;

    protected mixed $paginate;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $this->admin = Auth::user() instanceof Admin ? Auth::user() : null;
                $this->user = Auth::user() instanceof User ? Auth::user() : null;
                $this->member = $this->user?->member;
            }

            $this->paginate = 10;
            if ($request->get('per_page')) {
                $this->paginate = $request->get('per_page');
            }

            return $next($request);
        });
    }

    public function logExceptionAndRespond(Throwable $e): RedirectResponse
    {
        Sentry::captureException($e);

        return redirect()
            ->back()
            ->with(['error' => 'Something went wrong, please try again later.'])
            ->withInput();
    }

    protected function apiResponse(bool $status, ?string $message = null, array $otherData = []): JsonResponse
    {
        return response()->json(
            array_merge([
                'status' => $status,
                'message' => $message,
            ], $otherData)
        );
    }
}
