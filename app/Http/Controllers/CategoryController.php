<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->orderBy('category_name')
            ->get();

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->orderBy('category_name', 'ASC')
            ->get();

        return view('admin.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $tenant = $this->requireTenant();

        $validatedData = $request->validate(
            [
                'category_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories', 'category_name')->where(
                        fn ($query) => $query->where('tenant_id', $tenant->id)
                    ),
                ],
                'description' => ['nullable', 'string', 'max:1000'],
            ]
        );

        $validatedData['tenant_id'] = $tenant->id;
        $validatedData['description'] = $validatedData['description'] ?? $validatedData['category_name'];

        Category::create($validatedData);

        return redirect()->route('categories.index', ['tenant' => $tenant->slug])->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(string $tenant, string $id)
    {
    }

    public function edit(string $tenant, string $id)
    {
        $categories = Category::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->findOrFail($id);

        return view('admin.category.edit', compact('categories'));
    }

    public function update(Request $request, string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $categories = Category::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($id);

        $validatedData = $request->validate(
            [
                'category_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories', 'category_name')
                        ->where(fn ($query) => $query->where('tenant_id', $tenantModel->id))
                        ->ignore($categories->id),
                ],
                'description' => ['nullable', 'string', 'max:1000'],
            ]
        );

        $validatedData['description'] = $validatedData['description'] ?? $categories->description ?? $validatedData['category_name'];
        $categories->update($validatedData);

        return redirect()->route('categories.index', ['tenant' => $tenantModel->slug])->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $categories = Category::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($id);

        // Cek apakah kategori masih dipakai oleh item
        $itemCount = \App\Models\Item::where('category_id', $categories->id)->count();
        if ($itemCount > 0) {
            return redirect()->route('categories.index', ['tenant' => $tenantModel->slug])
                ->with('error', 'Kategori ini masih digunakan oleh ' . $itemCount . ' menu. Pindahkan menu terlebih dahulu.');
        }

        $categories->delete();

        return redirect()->route('categories.index', ['tenant' => $tenantModel->slug])->with('success', 'Kategori berhasil dihapus.');
    }
}
