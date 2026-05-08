<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccessOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessOptionController extends Controller
{
    /**
     * Display a listing of the access options.
     */
    public function index()
    {
        return view('backend.access-options.index', [
            'systemOptions' => AccessOption::system()->ordered()->get(),
            'programOptions' => AccessOption::program()->ordered()->get(),
        ]);
    }

    /**
     * Store a newly created access option in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:system,program',
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:access_options,key',
            'has_sub_options' => 'boolean',
            'sub_options_list' => 'nullable|string',
            'sub_option_type' => 'nullable|in:radio,checkbox',
        ]);

        $maxSort = AccessOption::where('category', $validated['category'])->max('sort_order') ?? 0;

        $option = AccessOption::create([
            'category' => $validated['category'],
            'name' => $validated['name'],
            'key' => Str::snake($validated['key']),
            'has_sub_options' => $request->has('has_sub_options'),
            'sub_options' => $this->parseSubOptions($validated['sub_options_list']),
            'sub_option_type' => $validated['sub_option_type'] ?? 'radio',
            'sort_order' => $maxSort + 1,
            'is_active' => true,
        ]);

        return redirect()->route('backend.access-options.index')
            ->with('success', "เพิ่มรายการ \"{$option->name}\" เรียบร้อยแล้ว");
    }

    /**
     * Update the specified access option in storage.
     */
    public function update(Request $request, AccessOption $option)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:access_options,key,' . $option->id,
            'sort_order' => 'nullable|integer|min:1',
            'sub_options_list' => 'nullable|string',
            'sub_option_type' => 'nullable|in:radio,checkbox',
        ]);

        $option->update([
            'name' => $validated['name'],
            'key' => Str::snake($validated['key']),
            'has_sub_options' => $request->has('has_sub_options'),
            'sub_options' => $this->parseSubOptions($validated['sub_options_list']),
            'sub_option_type' => $validated['sub_option_type'] ?? 'radio',
            'sort_order' => $validated['sort_order'] ?? $option->sort_order,
        ]);

        return redirect()->route('backend.access-options.index')
            ->with('success', "แก้ไขรายการ \"{$option->name}\" เรียบร้อยแล้ว");
    }

    /**
     * Remove the specified access option from storage.
     */
    public function destroy(AccessOption $option)
    {
        $name = $option->name;
        $option->delete();

        return redirect()->route('backend.access-options.index')
            ->with('success', "ลบรายการ \"{$name}\" เรียบร้อยแล้ว");
    }

    /**
     * Toggle the active status of the access option.
     */
    public function toggleStatus(AccessOption $option)
    {
        $option->update(['is_active' => !$option->is_active]);

        $status = $option->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        return redirect()->route('backend.access-options.index')
            ->with('success', "{$status}รายการ \"{$option->name}\" เรียบร้อยแล้ว");
    }

    /**
     * Helper to parse sub-options string into array.
     */
    private function parseSubOptions(?string $list): ?array
    {
        if (!$list) return null;
        
        return array_values(array_filter(array_map('trim', explode(',', $list))));
    }
}
