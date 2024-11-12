<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BusinessPlan;
use App\Models\Country;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\LegalDocument;
use App\Models\Package;
use App\Models\PhotoGallery;
use App\Models\SubPhotoGallery;
use App\Models\WebsitePopup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index()
    {
        //        return redirect()->route('user.register.create');

        $popups = WebsitePopup::with('media')->whereStatus(WebsitePopup::STATUS_ACTIVE)->get();

        return view('website.index', [
            'popups' => $popups,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function contact(Request $request): Factory|View|RedirectResponse
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email:rfc,dns',
                'mobile' => 'required|regex:/^[6789][0-9]{9}$/',
                'message' => 'required',
            ],
                [
                    'name.required' => 'The name is required',
                    'mobile.required' => 'The mobile number is required',
                    'mobile.regex' => 'The mobile number format is invalid',
                    'email.required' => 'The Email ID is required',
                    'email.email' => 'The Email ID must be a valid format',
                    'message.required' => 'The message is required',
                ]
            );

            $inquiry = Inquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'message' => $request->message,
            ]);

            return redirect()->back()->with(['success' => 'Our Team Will Reach You Shortly']);
        }

        return view('website.contact', [
            'countries' => Country::get(),
        ]);
    }

    public function about()
    {
        return view('website.about');
    }

    public function message()
    {
        return view('website.message');
    }

    public function terms()
    {
        return view('website.terms');
    }

    /**
     * @return Factory|View
     */
    public function legal()
    {
        $LegalDocuments = LegalDocument::with('media')->where('status', LegalDocument::STATUS_ACTIVE)->get();

        return view('website.legal', ['LegalDocuments' => $LegalDocuments]);
    }

    /**
     * @return Factory|View
     */
    public function package()
    {
        return view('website.package', [
            'packages' => Package::orderBy('id', 'desc')
                ->whereStatus(Package::STATUS_ACTIVE)
                ->with('products')->get(),
        ]);
    }

    public function faqs()
    {
        return view('website.faqs', [
            'faqs' => Faq::where('status', 1)
                ->orderBy('id', 'desc')
                ->get(),
        ]);
    }

    /**
     * @return Factory|View
     */
    public function gallery()
    {
        $photoGalleries = PhotoGallery::with('media')->where('status', PhotoGallery::STATUS_ACTIVE)->get();

        return view('website.gallery', ['photoGalleries' => $photoGalleries]);
    }

    public function galleryDetails(PhotoGallery $photoGallery)
    {
        return view('website.gallery-details', [
            'subPhotoGalleries' => $photoGallery->subImages()->with('media')->where('status', SubPhotoGallery::STATUS_ACTIVE)->get(),
            'photoGallery' => $photoGallery,
        ]);
    }

    public function plan()
    {
        $businessPlan = BusinessPlan::with('media')->where(['status' => true])->first();
        $planUrl = optional($businessPlan)->getFirstMediaUrl(BusinessPlan::MC_WEBSITE_PLAN);

        return view('website.plan', [
            'businessPlan' => $businessPlan,
            'planUrl' => $planUrl,
        ]);
    }

    public function banking()
    {
        $bankingDetails = Bank::active()->get();

        return view('website.bank', ['bankingDetails' => $bankingDetails]);
    }

    public function returnPolicy()
    {
        return view('website.return-policy');
    }

    public function privacyPolicy()
    {
        return view('website.privacy-policy');
    }

    public function shippingPolicy()
    {
        return view('website.shipping-policy');
    }
}
