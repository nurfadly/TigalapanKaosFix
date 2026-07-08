<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = Lead::when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub
                ->where('name', 'like', '%'.$request->q.'%')
                ->orWhere('phone', 'like', '%'.$request->q.'%')
                ->orWhere('email', 'like', '%'.$request->q.'%')
            ))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('leads.index', compact('leads'));
    }

    public function show(Lead $lead): View
    {
        return view('leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $lead->update($data);

        return redirect()->route('leads.show', $lead)->with('status', 'Status lead berhasil diperbarui.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('leads.index')->with('status', 'Lead berhasil dihapus.');
    }
}
