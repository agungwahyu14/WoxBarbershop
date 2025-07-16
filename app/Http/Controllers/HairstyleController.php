<?php

namespace App\Http\Controllers;

use App\Models\Hairstyle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class HairstyleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Hairstyle::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
    return $row->image
        ? '<img src="' . asset('storage/hairstyles/' . $row->image) . '" width="120" class="rounded" />'
        : '-';
})
                ->addColumn('action', function ($row) {
                    $editUrl = route('hairstyles.edit', $row->id);
                    $deleteUrl = route('hairstyles.destroy', $row->id);

                    return '
                        <div class="flex justify-center gap-2">
                            <a href="'.$editUrl.'" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i></a>
                            <button class="deleteBtn text-red-600 hover:text-red-800" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>
                        </div>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('admin.hairstyles.index');
    }

    public function create()
    {
        return view('admin.hairstyles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'bentuk_kepala'  => 'required|string|max:255',
            'tipe_rambut'    => 'required|string|max:255',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'bentuk_kepala', 'tipe_rambut']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        Hairstyle::create($data);

        return redirect()->route('hairstyles.index')->with('success', 'Hairstyle created successfully.');
    }

    public function edit(Hairstyle $hairstyle)
    {
        return view('admin.hairstyles.edit', compact('hairstyle'));
    }

    public function update(Request $request, Hairstyle $hairstyle)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'bentuk_kepala'  => 'required|string|max:255',
            'tipe_rambut'    => 'required|string|max:255',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'bentuk_kepala', 'tipe_rambut']);

        if ($request->hasFile('image')) {
            if ($hairstyle->image) {
                Storage::disk('public')->delete($hairstyle->image);
            }

            $data['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        $hairstyle->update($data);

        return redirect()->route('hairstyles.index')->with('success', 'Hairstyle updated successfully.');
    }

    public function destroy(Hairstyle $hairstyle)
    {
        if ($hairstyle->image) {
            Storage::disk('public')->delete($hairstyle->image);
        }

        $hairstyle->delete();

        return response()->json(['success' => true, 'message' => 'Hairstyle deleted successfully.']);
    }
}
