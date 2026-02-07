<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Document;
use App\Models\FiscalYear;
use App\GoogleDriveService;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class DocumentController extends Controller
{
    public function __construct(public GoogleDriveService $googleDriveService)
    {
        //get new access token
        config(['filesystems.disks.google.accessToken' => $this->googleDriveService->getAccessToken()]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Document::with(['fiscal_year', 'category', 'uploader'])->orderBy('documents.id', 'desc');
            if ($request->fiscal_year) {
                $query = $query->where('fiscal_year_id', $request->fiscal_year);
            }
            if ($request->category) {
                $query = $query->where('category_id', $request->category);
            }
            if ($request->document_date) {
                $query = $query->where('document_date', $request->document_date);
            }
            $user = Auth::user();
            if ($user->role !== 'admin') {
                $query->whereHas('category', function ($q) use ($user) {
                    $q->where('division', $user->division);
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('category.name', function ($row) {
                    return $row->category->name ?? 'Tidak Ada';
                })
                ->editColumn('document_date', function ($row) {
                    return Carbon::parse($row->document_date)->format('d-m-Y');
                })
                ->editColumn('file_size', function ($row) {
                    return Helper::bytesToHuman($row->file_size);
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);

                    $action = '<div class="btn-group" role="group">';
                    // if(Gate::allows('edit kab')){
                    $action .= '<a href="' . route("document.show", $encryptedId) . '" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Detail Dokumen">
                                    <i class="fas fa-eye"></i>
                                </a>';
                    $action .= '<a href="' . route("document.download", $encryptedId) . '" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Download Dokumen">
                                    <i class="fas fa-download"></i>
                                </a>';
                    // }
                    if(Auth::user()->role == 'admin'){
                    $action .= '<form method="post" action="' . route("document.destroy", $encryptedId) . '"
                                    id="deleteDocument" style="display:inline" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Hapus Data">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                </div>';
                    }
                    return $action;
                })
                ->rawColumns(['action'])->make(true);
        }

        $fiscalYears = FiscalYear::all();
        $categories = Category::all();
        return view('documents.index', compact('fiscalYears', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::user()->role !== "admin" && Auth::user()->role !== "staff"){
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }
        $categories = Category::all();
        $activeFiscalYear = FiscalYear::where('is_active', true)->first();
        return view('documents.create', compact('categories', 'activeFiscalYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //get new access token
            // config(['filesystems.disks.google.accessToken' => $this->googleDriveService->getAccessToken()]);
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:191',
                'document_number' => 'nullable|string|max:191',
                'document_date' => 'required|date',
                'security_level' => 'required|in:public,internal,rahasia',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'document_file' => 'required|file|mimes:pdf|max:10240',
                'fiscal_year_id' => 'required|exists:fiscal_years,id',
                'upload_by' => 'required|exists:users,id'
            ]);

            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan file ke Google Drive
            $file = $request->file('document_file');

            $category = Category::find($request->category_id);
            $fileName = $category->name . ' ' . $request->title . ' ' . time() . '.' . $file->getClientOriginalExtension();
            // $fileName = time() . '_' . $file->getClientOriginalName();

            // Simpan file ke Google Drive
            Gdrive::put($fileName, $file);
            $contents = Gdrive::all('/');
            $uploadedContent = $contents->firstWhere('path', $fileName);

            if ($uploadedContent) {
                $fileMeta = $uploadedContent->extraMetaData() ?? [];
                if ($request->security_level == 'public') {
                    // Set file visibility to public
                    $this->googleDriveService->visibilityPublic($fileMeta['id']);
                }

                $document = Document::updateOrCreate(
                    ['google_drive_id' => $fileMeta['id']],
                    [
                        'title' => ucwords($validated['title']),
                        'document_number' => $validated['document_number'],
                        'document_date' => $validated['document_date'],
                        'security_level' => $validated['security_level'],
                        'category_id' => $validated['category_id'],
                        'description' => $validated['description'],
                        'file_name' => $fileMeta['name'],
                        'file_path' => $uploadedContent->path(),
                        'file_size' => $uploadedContent->fileSize(),
                        'file_type' => $uploadedContent->mimeType(),
                        'fiscal_year_id' => $validated['fiscal_year_id'],
                        'upload_by' => $validated['upload_by'],
                        'updated_at' => Carbon::createFromTimestampMs($uploadedContent->lastModified()),
                    ]
                );
                // DocumentLog::create([
                //     'document_id' => $document->id,
                //     'action' => 'create',
                //     'user_id' => Auth::user()->id,
                //     'description' => 'Dokumen "' . $document->title . '" berhasil diupload'
                // ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Dokumen berhasil diupload'
            ], 201);
            // return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //get new access token
        // config(['filesystems.disks.google.accessToken' => $this->googleDriveService->getAccessToken()]);
        $document = Document::findOrFail(Crypt::decrypt($id));
        $downloadUrl = "https://drive.google.com/uc?export=download&id={$document->google_drive_id}";
        $url = "https://drive.google.com/file/d/" . $document->google_drive_id . "/preview";

        return view('documents.show', compact('url', 'document', 'downloadUrl'));
    }

    public function downloadFile(string $id)
    {
        $document = Document::findOrFail(Crypt::decrypt($id));
        $data = Gdrive::get($document->file_path);

        return response($data->file, 200)
            ->header('Content-Type', $document->file_type)
            ->header('Content-Disposition', 'attachment; filename="' . $document->file_name . '"');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $id = Crypt::decrypt($id);
        $data = Document::findOrFail($id);
        Gdrive::delete($data->file_path);
        // DocumentLog::create([
        //     'document_id' => $data->id,
        //     'action' => 'delete',
        //     'user_id' => Auth::user()->id,
        //     'description' => 'Dokumen "' . $data->title . '" berhasil dihapus'
        // ]);
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Dokumen berhasil dihapus'
        ], 200);
    }
}
