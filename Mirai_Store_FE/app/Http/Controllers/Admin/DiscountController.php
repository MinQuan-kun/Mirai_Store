<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * Display a listing of discount codes
     */
    public function index()
    {
        $discounts = DiscountCode::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new discount code
     */
    public function create()
    {
        return view('admin.discounts.create');
    }

    /**
     * Store a newly created discount code
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'required|date|after:now',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        DiscountCode::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'expires_at' => Carbon::parse($request->expires_at),
            'usage_limit' => $request->usage_limit,
            'used_count' => 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    /**
     * Show the form for editing a discount code
     */
    public function edit($id)
    {
        $discount = DiscountCode::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    /**
     * Update the specified discount code
     */
    public function update(Request $request, $id)
    {
        $discount = DiscountCode::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code,' . $id . ',_id',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'required|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $discount->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'expires_at' => Carbon::parse($request->expires_at),
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được cập nhật!');
    }

    /**
     * Remove the specified discount code
     */
    public function destroy($id)
    {
        $discount = DiscountCode::findOrFail($id);
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được xóa!');
    }

    /**
     * Toggle active status (AJAX)
     */
    public function toggleActive($id)
    {
        $discount = DiscountCode::findOrFail($id);
        $discount->is_active = !$discount->is_active;
        $discount->save();

        return response()->json([
            'success' => true,
            'is_active' => $discount->is_active
        ]);
    }
}