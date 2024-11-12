<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PackageProduct;
use App\Models\TopUp;
use Illuminate\Http\Response;
use Image;
use NumberFormatter;
use PDF;

class InvoiceController extends Controller
{
    public function show(TopUp $topUp): Response
    {
        $logo = Image::make(
            settings()->getFileUrl('logo', asset(env('LOGO')))
        )->encode('data-url')->__toString();

        $products = $topUp->member->package->products->map(function (PackageProduct $packageProduct) {
            $gstAmount = $packageProduct->gst_amount;

            return [
                'name' => $packageProduct->name,
                'price' => $packageProduct->price - $gstAmount,
                'gst_amount' => $gstAmount,
                'gst_percent' => $packageProduct->present()->gstSlab(),
                'total' => $packageProduct->price,
            ];
        });

        $pdf = PDF::loadView('member.invoice.topup-invoice-html', [
            'member' => $topUp->member,
            'products' => $products,
            'logo' => $logo,
            'topUp' => $topUp,
        ]);

        return $pdf->stream("Invoice-{$topUp->member->code}.pdf")->header('X-Vapor-Base64-Encode', 'True');

    }

    public function orderInvoice(Order $order): Response
    {
        $logo = Image::make(
            settings()->getFileUrl('logo', asset(env('LOGO')))
        )->encode('data-url')->__toString();

        $products = $order->products()->with('product.GSTType')->get()->map(function (OrderProduct $orderProduct) {
            $gstAmount = $orderProduct->gst_amount;

            return [
                'name' => $orderProduct->product->name,
                'price' => $orderProduct->product->mrp - $gstAmount,
                'gst_amount' => $gstAmount,
                //                'gst_percent' => $orderProduct->product->GSTType->hsn_code,
                'total' => $orderProduct->amount,
            ];
        });
        $f = new NumberFormatter(locale_get_default(), NumberFormatter::SPELLOUT);
        $amountInWord = $f->format($order->total);
        $amountInWords = preg_replace('/\d{4}/', '$0-', str_replace('-', ' ', trim($amountInWord)), 2);

        $pdf = PDF::loadView('order-invoice', [
            'member' => $order->member,
            'products' => $products,
            'invoice' => $order,
            'amountInWords' => $amountInWords,
            'logo' => $logo,
        ]);

        return $pdf->stream("{$order->order_no}.pdf")->header('X-Vapor-Base64-Encode', 'True');
    }
}
