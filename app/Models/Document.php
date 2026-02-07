<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'google_drive_id',
        'title',
        'document_number',
        'document_date',
        'security_level',
        'category_id',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'fiscal_year_id',
        'upload_by',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'document_date' => 'date',
        'file_size' => 'integer',
    ];

    /**
     * Category relation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Fiscal year relation.
     */
    public function fiscal_year(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * Uploader (user) relation.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upload_by');
    }

    /**
     * Document logs relation.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(DocumentLog::class);
    }

    /**
     * 2. Tambahkan Method BOOT ini untuk Otomatisasi Log
     */
    protected static function boot()
    {
        parent::boot();

        // --- EVENT: Saat Dokumen Baru Dibuat (Created) ---
        static::created(function ($document) {
            // Cek apakah ada user yang login (untuk menghindari error saat seeding/console)
            if (Auth::check()) {
                DocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'create',
                    'user_id' => Auth::user()->id,
                    'description' => 'Dokumen "' . $document->title . '" berhasil diupload'
                ]);
                UserLog::create([
                    'user_id'    => Auth::user()->id,
                    'action'     => 'UPLOAD_DOCUMENT',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    // Kita bisa ambil detail dari data dokumen yang baru disimpan
                    'details'    => "Mengunggah dokumen baru: {$document->title}",
                ]);
            }
        });

        // --- EVENT: Saat Dokumen Dihapus (Deleted) ---
        static::deleted(function ($document) {
            if (Auth::check()) {
                DocumentLog::create([
                    'document_id' => $document->id,
                    'action' => 'delete',
                    'user_id' => Auth::user()->id,
                    'description' => 'Dokumen "' . $document->title . '" berhasil dihapus'
                ]);
                UserLog::create([
                    'user_id'    => Auth::user()->id,
                    'action'     => 'DELETE_DOCUMENT',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details'    => "Menghapus dokumen: {$document->title}",
                ]);
            }
        });
    }
}
