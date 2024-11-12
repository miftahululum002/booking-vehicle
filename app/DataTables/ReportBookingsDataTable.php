<?php

namespace App\DataTables;

use App\Models\Booking as Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportBookingsDataTable extends DataTable
{
    private $_startDate = null;
    private $_endDate = null;

    public function __construct($startDate = null, $endDate = null)
    {
        $startDate = !empty($startDate) ? $startDate : date('Y-m-d');
        $endDate = !empty($endDate) ? $endDate : date('Y-m-d');
        $this->_startDate = $startDate;
        $this->_endDate = $endDate;
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('employee', function ($query) {
                $employee = $query->employee;
                $return = null;
                if ($employee) {
                    $return = $employee->name;
                }
                return $return;
            })
            ->addColumn('vehicle', function ($query) {
                $vehicle = $query->vehicle;
                $return = null;
                if ($vehicle) {
                    $return = $vehicle->name;
                }
                return $return;
            })
            ->addColumn('approval1', function ($query) {
                $approval = $query->approval->where('order', 1)->first();
                $return = null;
                if ($approval) {
                    $return = $approval->user->name . '<br/>Status:';
                    if ($approval->status == 1) {
                        $return .= '<b>Disetujui</b><br/>Waktu Approve: <b>' . $approval->approve_at . '</b>';
                    } else {
                        $return .= '<b>Belum/Tidak Disetujui</b>';
                    }
                }
                return $return;
            })
            ->addColumn('approval2', function ($query) {
                $approval = $query->approval->where('order', 2)->first();
                $return = null;
                if ($approval) {
                    $return = $approval->user->name . '<br/>Status:';
                    if ($approval->status == 1) {
                        $return .= '<b>Disetujui</b><br/>Waktu Approve: <b>' . $approval->approve_at . '</b>';
                    } else {
                        $return .= '<b>Belum/Tidak Disetujui</b>';
                    }
                }
                return $return;
            })
            ->addColumn('is_done', function ($query) {
                $return = null;
                if ($query->is_done == 1) {
                    $return .= 'Ya';
                    $return .= '<br/>Selesai pada: ' . $query->done_at;
                } else {
                    $return .= 'Belum';
                }
                return $return;
            })
            ->addColumn('action', function ($query) {
                $return = null;
                return $return;
            })
            ->rawColumns(['action', 'approval1', 'approval2', 'is_done'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Model $model): QueryBuilder
    {
        $startDate = $this->_startDate;
        $endDate = $this->_endDate;
        return $model->newQuery()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId(getTableId())
            ->columns($this->getColumns())
            ->minifiedAjax('', scriptMinifields('#data_filter'))
            //->dom('Bfrtip')
            ->orderBy(1)
            ->addTableClass(getTableClass())
            ->pageLength(getTablePageLength())
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('number')->title('No')->render('meta.row + meta.settings._iDisplayStart + 1;')->width(10)->orderable(false)->searchable(false),
            // Column::make('id'),
            Column::make('employee')->title('Pegawai'),
            Column::make('code')->title('Kode'),
            Column::make('vehicle')->title('Kendaraan'),
            Column::make('date')->title('Tanggal'),
            Column::make('necessary')->title('Keperluan'),
            Column::make('approval1')->title('Approval 1'),
            Column::make('approval2')->title('Approval 2'),
            Column::make('status')->title('Status'),
            Column::make('is_done')->title('Selesai'),
            // Column::computed('action')
            //     ->title('Opsi')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Vehicles_' . date('YmdHis');
    }
}
