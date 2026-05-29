<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->with('category')
            ->orderBy('name', 'ASC')
            ->get();

        return view('admin.item.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->orderBy('category_name', 'ASC')
            ->get();

        return view('admin.item.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $tenant = $this->requireTenant();

        $validatedData = $request->validate(
            [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category_id' => ['required', Rule::exists('categories', 'id')->where('tenant_id', $tenant->id)],
                'is_available' => 'required|boolean',
            ]
        );

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $validatedData['tenant_id'] = $tenant->id;
        Item::create($validatedData);
        $this->forgetTenantMenuCache($tenant->id);

        return redirect()->route('items.index', ['tenant' => $tenant->slug])->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(string $tenant, string $id)
    {
        $tenantId = $this->requireTenant()->id;
        $item = Item::query()->where('tenant_id', $tenantId)->findOrFail($id);
        $categories = Category::query()->where('tenant_id', $tenantId)->orderBy('category_name', 'ASC')->get();

        return view('admin.item.edit', compact('item', 'categories'));
    }

    public function update(Request $request, string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $item = Item::query()->where('tenant_id', $tenantModel->id)->findOrFail($id);

        $validatedData = $request->validate(
            [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category_id' => ['required', Rule::exists('categories', 'id')->where('tenant_id', $tenantModel->id)],
                'is_available' => 'required|boolean',
            ]
        );

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img_item_upload'), $imageName);

            if ($item->image && file_exists(public_path('img_item_upload/' . $item->image))) {
                @unlink(public_path('img_item_upload/' . $item->image));
            }

            $validatedData['image'] = $imageName;
        } else {
            $validatedData['image'] = $item->image;
        }

        $item->update($validatedData);
        $this->forgetTenantMenuCache($tenantModel->id);

        return redirect()->route('items.index', ['tenant' => $tenantModel->slug])->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $item = Item::query()->where('tenant_id', $tenantModel->id)->findOrFail($id);

        if ($item->image && file_exists(public_path('img_item_upload/' . $item->image))) {
            @unlink(public_path('img_item_upload/' . $item->image));
        }

        $item->delete();
        $this->forgetTenantMenuCache($tenantModel->id);

        return redirect()->route('items.index', ['tenant' => $tenantModel->slug])->with('success', 'Menu berhasil dihapus.');
    }

    private function forgetTenantMenuCache(int $tenantId): void
    {
        Cache::forget('tenant.' . $tenantId . '.menu.items.available');
    }
}
