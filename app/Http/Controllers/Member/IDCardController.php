<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Image;
use Intervention\Image\Constraint;
use PDF;

class IDCardController extends Controller
{
    public function show(Request $request): Renderable|Response
    {
        $logo = Image::make(
            settings()->getFileUrl('logo', asset(env('LOGO')))
        )->encode('data-url')->__toString();

        if (env('APP_ENV') == 'local') {
            $backgroundPath = public_path('images/id_card.png');
        } else {
            $backgroundPath = asset('images/id_card.png');
        }

        $background = Image::make($backgroundPath);
        $backgroundImage = $background->encode('data-url');
        $width = $backgroundImage->width();
        $height = $backgroundImage->height();

        if (! $profilePath = Auth::user()->member->getFirstMediaUrl(Member::MC_PROFILE_IMAGE)) {
            if (env('APP_ENV') == 'local') {
                $profilePath = public_path('images/user.png');
            } else {
                $profilePath = asset('images/user.png');
            }
        }

        $profileSize = 175;
        $profileCanvas = Image::canvas($profileSize, $profileSize, '#ffffff');
        $profile = Image::make($profilePath)->resize($profileSize, $profileSize, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $profileImage = $profileCanvas->insert($profile, 'center')->encode('data-url');

        return PDF::loadView('member.profile.id', [
            'member' => $this->member,
            'backgroundImage' => $backgroundImage,
            'width' => $width,
            'height' => $height,
            'profileImage' => $profileImage,
            'profileSize' => $profileSize,
            'logo' => $logo,
        ])->stream("ID-Card-{$this->member->code}.pdf");
    }
}
