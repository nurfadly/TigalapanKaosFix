<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::orderBy('sort_order')->get();

        return view('branches.index', compact('branches'));
    }

    public function create(): View
    {
        $nextOrder = (int) Branch::max('sort_order') + 1;

        return view('branches.create', compact('nextOrder'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBranch($request);
        $data['is_hq'] = $request->boolean('is_hq');

        Branch::create($data);

        return redirect()->route('branches.index')->with('status', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $data = $this->validateBranch($request);
        $data['is_hq'] = $request->boolean('is_hq');

        $branch->update($data);

        return redirect()->route('branches.index')->with('status', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()->route('branches.index')->with('status', 'Cabang berhasil dihapus.');
    }

    private function validateBranch(Request $request): array
    {
        return $request->validate([
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
