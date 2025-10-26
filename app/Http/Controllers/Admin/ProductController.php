<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $imageUrl = $row->image ? asset('storage/' . $row->image) : asset('images/default-product.jpg');
                    
                    return '<div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                            <img src="' . $imageUrl . '" alt="' . $row->name . '" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">' . $row->name . '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">' . Str::limit($row->description, 30) . '</div>
                        </div>
                    </div>';
                })
                ->editColumn('category', function ($row) {
                    $category = $row->category ?: 'Uncategorized';
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                        ' . $category . '
                    </span>';
                })
                ->editColumn('price', function ($row) {
                    return '<div class="font-medium text-green-600 dark:text-green-400">
                        Rp ' . number_format($row->price, 0, ',', '.') . '
                    </div>';
                })
                ->editColumn('stock', function ($row) {
                    $color = $row->stock <= 5 ? 'red' : ($row->stock <= 10 ? 'yellow' : 'green');
                    return '<div class="text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                            ' . $row->stock . ' pcs
                        </span>
                    </div>';
                })
                ->editColumn('is_active', function ($row) {
                    $status = $row->is_active ? 'Active' : 'Inactive';
                    $color = $row->is_active ? 'green' : 'red';
                    return '<div class="text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                            <div class="w-1.5 h-1.5 rounded-full bg-' . $color . '-600 mr-1"></div>
                            ' . $status . '
                        </span>
                    </div>';
                })
               ->addColumn('action', function ($row) {
                    $actions = '<div class="flex items-center gap-2 justify-center">';
                    
                    // View button
                    // $actions .= '<a href="' . route('admin.products.show', $row->id) . '" 
                    //     class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-colors duration-200">
                    //     <i class="fas fa-eye"></i> 
                    // </a>';
                    
                    // Edit button
                    $actions .= '<a href="' . route('admin.products.edit', $row->id) . '" 
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200">
                        <i class="fas fa-edit"></i> 
                    </a>';
                    
                    // Delete button
                    $actions .= '<button type="button" data-id="' . $row->id . '" 
                        class="deleteBtn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200">
                        <i class="fas fa-trash"></i> 
                    </button>';
                    
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->rawColumns(['name', 'category', 'price', 'stock', 'is_active', 'action'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.'
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_id' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.'
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        try {
            // Delete image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus.'
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus produk: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus produk.');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return redirect()->back()
            ->with('success', 'Status produk berhasil diubah.');
    }
}
