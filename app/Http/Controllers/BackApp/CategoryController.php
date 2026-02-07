<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\CategoryDataTable;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth','admin'
        ];
    }

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('dataMaster.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:191',
                'division' => 'nullable|string|max:191'
            ]);

            Category::create([
                'name' => ucwords($request->name),
                'division' => $request->division
            ]);

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Category::find($id);
        $linkUpdate = route("category.update", Crypt::encrypt($data->id));
        return response()->json(['name' => $data->name, 'division' => $data->division, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191',
                'division' => 'nullable|string|max:191'
            ]);

            $db = Category::find($id);

            $db->name = ucwords($request->name);
            $db->division = $request->division;

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil update'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = Category::where('id', $id);
        // $data->delete();
        // return true;
        $isExist = Document::where('category_id', $id)->first();
        if ($isExist) {
            return false;
        } else {
            $data->delete();
            return true;
        }
    }
}
