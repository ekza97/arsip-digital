<?php

namespace App\DataTables;

use App\Models\FiscalYear;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FiscalYearDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<FiscalYear> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('is_active', function($row){
            if($row->is_active == 1){
                return '<span class="badge bg-success">Aktif</span>';
            } else {
                return '<span class="badge bg-secondary">Non Aktif</span>';
            }
        })
        ->addColumn('action', function($row){
            $action = '<div class="btn-group" role="group">';
            // if(Gate::allows('edit kab')){
                $action .= '<a href="#" onclick="editData(' . $row->id . ')" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Edit Data">
                    <i class="fas fa-edit"></i>
                </a>';
            // }
            // if(Gate::allows('delete kab')){
            $action .= '<form method="post" action="' . route("category.destroy", Crypt::encrypt($row->id)) . '"
                id="deleteCategory" style="display:inline" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Hapus Data">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            </div>';
            // }
            return $action;
        })
        ->rawColumns(['action','is_active']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<FiscalYear>
     */
    public function query(FiscalYear $model): QueryBuilder
    {
        // return $model->newQuery();
        $query = $model->query()->orderBy('id','desc');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('fiscalyear-table')
                    ->addTableClass('table-hover table-striped table-bordered')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->width(20)->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('year')->title('Tahun Anggaran')->addClass('text-center'),
            Column::make('is_active')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FiscalYear_' . date('YmdHis');
    }
}
