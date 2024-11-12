<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\PackageListBuilder;
use App\Models\Package;
use App\Models\PackageProduct;
use DB;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Sentry;
use Throwable;

class PackageController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return PackageListBuilder::render();
    }

    //    public function create(Request $request): RedirectResponse|Renderable
    //    {
    //        return view('admin.package.create', [
    //            'gstSlabs' => PackageProduct::GST_SLABS
    //        ]);
    //    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:packages,name',
            'capping' => 'required|numeric|min:0',
            'pv' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.hsn_code' => 'required',
            'products.*.gstSlab' => 'required|in:'.implode(',', array_keys(PackageProduct::GST_SLABS)),
        ], [
            'name.required' => 'The Package name is required',
            'products.*.name.required' => 'The products Product/Service Name is required',
            'products.*.price.required' => 'The products DP is required',
            'products.*.hsn_code.required' => 'The products HSN Code is required',
            'products.*.gstSlab.required' => 'The products GST Slab is required',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $package = Package::create([
                    'name' => $request->get('name'),
                    'capping' => $request->get('capping'),
                    'pv' => $request->get('pv'),
                ]);

                $amount = 0;
                foreach ($request->get('products') as $product) {
                    $package->products()->create([
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'hsn_code' => $product['hsn_code'],
                        'gst_slab' => $product['gstSlab'],
                    ]);

                    $amount += $product['price'];
                }

                $package->update([
                    'amount' => $amount,
                ]);

                return redirect()->route('admin.packages.index')
                    ->with(['success' => 'Package created successfully']);
            });
        } catch (Throwable $e) {
            Sentry::captureException($e);

            return redirect()->back()->with(['error' => 'Something went wrong. Please try again'])->withInput();
        }
    }

    /**
     * @throws ValidationException
     */
    public function changeStatus(Request $request, Package $package): RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required|in:'.implode(',', array_keys(Package::STATUSES)),
        ]);

        $package->update([
            'status' => $request->get('status'),
        ]);

        return redirect()->route('admin.packages.index')
            ->with(['success' => 'Package updated successfully']);
    }
}
