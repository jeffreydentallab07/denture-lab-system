<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialsController extends Controller
{
    public function index()
    {
        $materials = Material::latest()
            ->paginate(15);

        // Calculate statistics
        $totalMaterials = Material::count();
        $totalValue = Material::sum(DB::raw('quantity * price'));
        $lowStockCount = Material::where('status', 'low stock')->count();
        $outOfStockCount = Material::where('status', 'out of stock')->count();

        return view('admin.materials.index', compact('materials', 'totalMaterials', 'totalValue', 'lowStockCount', 'outOfStockCount'));
    }

    public function create()
    {
        return view('admin.materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        // Auto-set status based on quantity
        $validated['status'] = $this->determineStatus($validated['quantity']);

        Material::create($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material added successfully.');
    }

    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('admin.materials.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        // Auto-update status based on quantity
        if ($validated['quantity'] == 0) {
            $validated['status'] = 'out of stock';
        } elseif ($validated['quantity'] < 10) { // You can adjust this threshold
            $validated['status'] = 'low stock';
        } else {
            $validated['status'] = 'available';
        }

        $material->update($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material deleted successfully.');
    }

    private function determineStatus($quantity)
    {
        if ($quantity == 0) {
            return 'out of stock';
        } elseif ($quantity <= 10) {
            return 'low stock';
        } else {
            return 'available';
        }
    }
}
