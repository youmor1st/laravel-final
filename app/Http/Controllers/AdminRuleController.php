<?php

namespace App\Http\Controllers;

use App\Models\DisciplineRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRuleController extends Controller
{
    public function index(): View
    {
        $rules = DisciplineRule::orderBy('id')->paginate(20);

        return view('admin.rules.index', compact('rules'));
    }

    public function create(): View
    {
        return view('admin.rules.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['required', 'integer'],
            'type' => ['required', 'in:reward,penalty'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        DisciplineRule::create($data);

        return redirect()->route('admin.rules.index')->with('status', 'Правило создано');
    }

    public function edit(DisciplineRule $rule): View
    {
        return view('admin.rules.edit', compact('rule'));
    }

    public function update(Request $request, DisciplineRule $rule): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['required', 'integer'],
            'type' => ['required', 'in:reward,penalty'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $rule->update($data);

        return redirect()->route('admin.rules.index')->with('status', 'Правило обновлено');
    }

    public function destroy(DisciplineRule $rule): RedirectResponse
    {
        $rule->delete();

        return redirect()->route('admin.rules.index')->with('status', 'Правило удалено');
    }
}
