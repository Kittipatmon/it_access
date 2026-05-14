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
        $options = AccessOption::ordered()->get()->groupBy('category');
        
        return view('backend.access-options.index', [
            'groupedOptions' => $options,
            'categoryLabels' => [
                'system' => 'รายการเข้าถึงระบบ (Systems)',
                'program' => 'รายการเข้าถึงโปรแกรม (Programs)',
                'equipment' => 'รายการอุปกรณ์ (Equipment)',
            ]
        ]);
    }

    /**
     * Store a newly created access option in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'category_new' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:access_options,key',
            'has_sub_options' => 'boolean',
            'sub_options_list' => 'nullable|string',
            'sub_option_type' => 'nullable|in:radio,checkbox',
            'custom_fields_list' => 'nullable|string',
        ]);

        $category = $validated['category'];
        if ($category === 'new' && !empty($validated['category_new'])) {
            $category = Str::snake($validated['category_new']);
        }

        $maxSort = AccessOption::where('category', $category)->max('sort_order') ?? 0;

        $option = AccessOption::create([
            'category' => $category,
            'name' => $validated['name'],
            'key' => Str::snake($validated['key']),
            'has_sub_options' => $request->has('has_sub_options'),
            'sub_options' => $this->parseSubOptions($validated['sub_options_list']),
            'sub_option_type' => $validated['sub_option_type'] ?? 'radio',
            'custom_fields' => $this->parseSubOptions($validated['custom_fields_list']),
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
            'custom_fields_list' => 'nullable|string',
        ]);

        $option->update([
            'name' => $validated['name'],
            'key' => Str::snake($validated['key']),
            'has_sub_options' => $request->has('has_sub_options'),
            'sub_options' => $this->parseSubOptions($validated['sub_options_list']),
            'sub_option_type' => $validated['sub_option_type'] ?? 'radio',
            'custom_fields' => $this->parseSubOptions($validated['custom_fields_list']),
            'sort_order' => $validated['sort_order'] ?? $option->sort_order,
        ]);

        return redirect()->route('backend.access-options.index')
            ->with('success', "แก้ไขรายการ \"{$option->name}\" เรียบร้อยแล้ว");
    }

    /**
     * Remove the specified access option from storage.
     */
    public function destroy(AccessOption $accessOption)
    {
        $accessOption->delete();
        return redirect()->back()->with('success', 'ลบรายการเรียบร้อยแล้ว');
    }

    public function destroyCategory($category)
    {
        AccessOption::where('category', $category)->delete();
        return redirect()->back()->with('success', 'ลบหมวดหมู่และรายการทั้งหมดเรียบร้อยแล้ว');
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
