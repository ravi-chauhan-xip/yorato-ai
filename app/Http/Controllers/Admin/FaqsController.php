<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\FaqListBuilder;
use App\Models\Faq;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FaqsController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return FaqListBuilder::render();
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|Renderable
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'question' => 'required',
                'answer' => 'required',
            ], [
                'question.required' => 'The question is required',
                'answer.required' => 'The answer is required',
            ]);

            Faq::create([
                'question' => $request->input('question'),
                'answer' => $request->input('answer'),
            ]);

            return redirect()->route('admin.faqs.index')->with(['success' => 'FAQ added successfully']);
        }

        return view('admin.faqs.create');
    }

    public function create(): Renderable
    {
        return view('admin.faqs.create');
    }

    public function edit(Faq $faq): RedirectResponse|Renderable
    {
        return view('admin.faqs.edit', [
            'faq' => $faq,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Faq $faq)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'question' => 'required',
                'answer' => 'required',
            ], [
                'question.required' => 'The question is required',
                'answer.required' => 'The answer is required',
            ]);

            $faq->update([
                'question' => $request->get('question'),
                'answer' => $request->get('answer'),
                'status' => $request->get('status'),
            ]);

            return redirect()->route('admin.faqs.index')->with([
                'success' => 'FAQ updated successfully',
            ]);
        }
    }
}
