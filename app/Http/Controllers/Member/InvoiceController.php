<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\InvoiceListBuilder;
use App\Models\TopUp;
use App\Models\TopUpPackageProduct;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Image;
use PDF;

class InvoiceController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|RedirectResponse|JsonResponse
    {
        return InvoiceListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function show(TopUp $topUp): Factory|View|RedirectResponse|Application|Response
    {
        $logo = Image::make(
            settings()->getFileUrl('logo', asset(env('LOGO')))
        )->encode('data-url')->__toString();

        if (! $this->member->package_id) {
            return redirect()->route('user.dashboard.index');
        }

        if ($topUp->member_id == $this->member->id) {
            $products = $topUp->topUpPackageProducts->map(function (TopUpPackageProduct $topUpPackageProduct) {
                $gstAmount = $topUpPackageProduct->total_gst_amount;

                return [
                    'name' => $topUpPackageProduct->name,
                    'hsn_code' => $topUpPackageProduct->hsn_code,
                    'price' => $topUpPackageProduct->price - $gstAmount,
                    'gst_amount' => $gstAmount,
                    'gst_percent' => $topUpPackageProduct->present()->gstSlab(),
                    'gst_slab' => $topUpPackageProduct->gst_slab,
                    'sgst_percentage' => $topUpPackageProduct->sgst_percentage,
                    'cgst_percentage' => $topUpPackageProduct->cgst_percentage,
                    'igst_percentage' => $topUpPackageProduct->igst_percentage,
                    'gst_percentage' => $topUpPackageProduct->gst_percentage,
                    'sgst_amount' => $topUpPackageProduct->sgst_amount,
                    'cgst_amount' => $topUpPackageProduct->cgst_amount,
                    'igst_amount' => $topUpPackageProduct->igst_amount,
                    'total' => $topUpPackageProduct->price,
                ];
            });
        } else {
            return redirect()->route('user.dashboard.index');
        }

        $pdf = PDF::loadView('member.invoice.topup-invoice-html', [
            'member' => $this->member,
            'products' => $products,
            'topUp' => $topUp,
            'logo' => $logo,
        ]);

        return $pdf->stream("Invoice-{$this->member->code}.pdf")->header('X-Vapor-Base64-Encode', 'True');

    }
}
