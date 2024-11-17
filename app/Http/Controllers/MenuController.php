<?php

// app/Http/Controllers/MenuController.php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // public function index()
    // {
    //     $menus = Item::IsMenu()->with(['category', 'type'])->get();
    //     return view('menus.index', compact('menus'));
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'name');
        $order = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);

        // Start building the query for items
        $query = Item::query()
            ->when($search, function ($query, $search) {
                return $query->where('items.name', 'LIKE', "%$search%")  // Specify items.name
                            ->orWhere('items.price', 'LIKE', "%$search%")
                            ->orWhere('items.stock', 'LIKE', "%$search%")
                            ->orWhereHas('category', function ($query2) use ($search) {
                                $query2->where('categories.name', 'LIKE', "%$search%");  // Specify categories.name
                            });
            })
            ->IsMenu()
            ->with(['category', 'type']);

        // Handle sorting logic
        if ($sortBy === 'category_name') {
            // If sorting by category_name, join with categories table and order by category name
            $query->join('categories', 'items.category_id', '=', 'categories.id')
                ->select('items.*', 'categories.name as category_name')  // Select items columns and alias category.name
                ->orderBy('categories.name', $order);  // Sort by the category's name column
        } else {
            // Otherwise, use the normal sortBy (e.g., 'name', 'price', etc.)
            $query->select('items.*')  // Select only items columns if sorting by item fields
                ->orderBy($sortBy, $order);
        }

        // Get paginated results
        $menus = $query->paginate($perPage);
        $categories = Category::where('id','<>', 6)->get();

        // If the request is an AJAX request, return only the table view
        if ($request->ajax()) {
            return view('menus.table', compact('menus', 'search', 'sortBy', 'order', 'perPage', 'categories'));
        }

        // Return the full page view
        return view('menus.index', compact('menus', 'search', 'sortBy', 'order', 'perPage', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('id','<>', 6)->get();
        $types = Type::where('id','<>', 2)->get();
        return view('menus.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'active' => 'required|boolean', // Validasi kolom active
        ]);
        Item::create([
            'name'  => $request->name,
            'category_id'   => $request->category_id,
            'type_id'       => 1,
            'price'         => $request->price,
            'stock'         => 0,
            'active'        => $request->active ?? true,
        ]);
        // return redirect()->route('menus.index')->with('success', 'Item created successfully.');
        return response()->json(['success' => 'Menu Created successfully.']);
    }

    public function edit(Item $item)
    {
        $categories = Category::where('id','<>', 6)->get();
        $types = Type::where('id','<>', 2)->get();
        return view('menus.edit', compact('item', 'categories', 'types'));
    }

    public function update(Request $request, Item $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'active' => 'required|boolean', // Validasi kolom active
        ]);
        $menu->update([
            'name'  => $request->name,
            'category_id'   => $request->category_id,
            'type_id'       => 1,
            'price'         => $request->price,
            'stock'         => 0,
            'active'        => $request->active ?? true,
        ]);
        // return redirect()->route('menus.index')->with('success', 'Item updated successfully.');
        return response()->json(['success' => 'Menu updated successfully.']);
    }

    public function destroy(Item $menu)
    {
        $menu->delete();
        // return redirect()->route('menus.index')->with('success', 'Item deleted successfully.');
        return response()->json(['success' => 'Menu deleted successfully.']);
    }

    public function toggleActive(Item $menu)
    {
        // Toggle the active status
        $menu->active = !$menu->active;
        $menu->save();

        // return redirect()->route('menus.index');
        return response()->json(['success' => 'Menu status updated successfully.']);
    }

}
