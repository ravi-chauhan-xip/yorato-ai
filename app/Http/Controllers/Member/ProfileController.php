<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Member;
use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Str;
use Throwable;

class ProfileController extends Controller
{
    public function show(): Renderable
    {
        $country = Country::whereName('India')->first();

        return view('member.profile.show', [
            'member' => Auth::user()->member,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns|unique:users,email,'.Auth::user()->member->user->id,
        ], [
            'email.required' => 'The Email ID is required',
            'email.email' => 'The Email ID must be a valid format',
            'email.unique' => 'The Email ID has already been taken',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $this->member->user->email = $request->get('email');
                $this->member->user->save();

                if ($fileName = $request->get('profile_image')) {
                    $filePath = 'tmp/'.Str::beforeLast($fileName, '.');

                    $this->member->addMediaFromDisk($filePath)
                        ->usingFileName($fileName)
                        ->toMediaCollection(Member::MC_PROFILE_IMAGE);
                } else {
                    if ($oldProfileImage = $this->member->media()->where('collection_name', 'profile_image')->first()) {
                        $this->member->deleteMedia($oldProfileImage->id);
                    }
                }

            });
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again')->withInput();
        }

        return redirect()->back()->with(['success' => 'Profile updated successfully']);
    }
}
