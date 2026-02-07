<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\Document;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\FiscalYearDataTable;

class FiscalYearController extends Controller
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
    public function index(FiscalYearDataTable $dataTable)
    {
        return $dataTable->render('dataMaster.fiscal_year.index');
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
                'year' => 'required|string|max:191',
                'is_active' => 'required|boolean'
            ]);

            if ($request->is_active) {
                FiscalYear::where('is_active', true)->update(['is_active' => false]);
            }

            FiscalYear::create([
                'year' => $request->year,
                'is_active' => $request->is_active
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
        $data = FiscalYear::find($id);
        $linkUpdate = route("fiscal_year.update", Crypt::encrypt($data->id));
        return response()->json(['year' => $data->year, 'is_active' => $data->is_active, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'year' => 'required|string|max:191',
                'is_active' => 'required|boolean'
            ]);

            if ($request->is_active) {
                FiscalYear::where('is_active', true)->update(['is_active' => false]);
            }

            $db = FiscalYear::find($id);

            $db->year = $request->year;
            $db->is_active = $request->is_active;

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
        $data = FiscalYear::where('id', $id);
        $isExist = Document::where('fiscal_year_id', $id)->first();
        if ($isExist) {
            return false;
        } else {
            $data->delete();
            return true;
        }
        // $data->delete();
        // return true;
    }
}
