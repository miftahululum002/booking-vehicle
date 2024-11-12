<?php

namespace App\DataTables;

use App\Models\BookingApproval as Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ApprovalBookingsDataTable extends DataTable
{
    private $_userId = null;
    public function __construct($userId = null)
    {
        if (empty($userId)) {
            $userId = getUserLoginId();
        }
        $this->_userId = $userId;
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('booking', function ($query) {
                $booking = $query->booking;
                $employee = $booking->employee;
                $vehicle = $booking->vehicle;
                $driver = $booking->driver;
                $return = null;
                if ($employee) {
                    $return .= 'Pegawai: ' . $employee->name;
                }
                if ($booking) {
                    $return .= '<br/>Tanggal: ' . $booking->date . '<br/>Tujuan: ' . $booking->necessary;
                }
                if ($vehicle) {
                    $return .= '<br/>Kendaraan: ' . $vehicle->code . ' - ' . $vehicle->name;
                }
                if ($driver) {
                    $return .= '<br/>Sopir: ' . $driver->name;
                }
                return $return;
            })
            ->addColumn('code', function ($query) {
                return $query->booking->code;
            })
            ->addColumn('date', function ($query) {
                return $query->booking->date;
            })
            ->addColumn('necessary', function ($query) {
                return $query->booking->necessary;
            })
            ->addColumn('vehicle', function ($query) {
                $vehicle = $query->booking->vehicle;
                $return = null;
                if ($vehicle) {
                    $return = $vehicle->name;
                }
                return $return;
            })
            ->addColumn('action', function ($query) {
                return null;
            })
            ->rawColumns(['booking'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Model $model): QueryBuilder
    {
        $userId = $this->_userId;
        return $model->newQuery()
            ->with('booking')
            ->where('user_id', $userId);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId(getTableId())
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            // ->orderBy(0)
            ->ordering(false)
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
            Column::make('code')->title('Kode'),
            Column::make('booking')->title('Informasi'),
            Column::make('order')->title('Urutan Persetujuan'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Approval_Bookings' . date('YmdHis');
    }
}
