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

class ApprovalBookingsDataTable extends DataTable
{
    private $_userId = null;
    private $_bookingId = null;
    public function __construct($userId = null, $bookingId = null)
    {
        if (empty($userId)) {
            $userId = getUserLoginId();
        }
        if (empty($bookingId)) {
            $bookingId = getBookingIdByApprovalUserId($userId);
        }
        $this->_userId = $userId;
        $this->_bookingId = $bookingId;
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $userId = $this->_userId;
        return (new EloquentDataTable($query))
            ->addColumn('booking', function ($query) {
                $employee = $query->employee;
                $vehicle = $query->vehicle;
                $driver = $query->driver;
                $return = null;
                if ($employee) {
                    $return .= 'Pegawai: ' . $employee->name;
                }
                $return .= '<br/>Tanggal: ' . $query->date . '<br/>Keperluan: ' . $query->necessary .
                    '<br/>Status: ' . $query->status;
                if ($vehicle) {
                    $return .= '<br/>Kendaraan: ' . $vehicle->code . ' - ' . $vehicle->name;
                }
                if ($driver) {
                    $return .= '<br/>Sopir: ' . $driver->name;
                }
                return $return;
            })
            ->addColumn('approval1', function ($query) use ($userId) {
                $return = null;
                $approval = $query->approval->where('order', 1)->first();
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
            ->addColumn('approval2', function ($query) use ($userId) {
                $return = null;
                $approval = $query->approval->where('order', 2)->first();
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
            ->addColumn('order', function ($query) use ($userId) {
                $return = null;
                $approval = $query->approval->where('user_id', $userId)->first();
                if ($approval) {
                    $return = $approval->order;
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
            ->addColumn('action', function ($query) use ($userId) {
                $approval = $query->approval->where('user_id', $userId)->first();
                if ($approval->status == '0') {
                    return '<button type="button" onclick="approve(' . "'" . $query->id . "','" . $approval->id . "'" . ')" class="btn btn-primary btn-sm rounded-0">Setujui</button>';
                } else {
                    return 'Sudah Disetujui';
                }
            })
            ->rawColumns(['action', 'booking', 'approval1', 'approval2'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Model $model): QueryBuilder
    {
        $userId = $this->_userId;
        $bookingIdIn = $this->_bookingId;
        // if (empty($bookingIdIn)) {
        //     return null;
        // }
        return $model->newQuery()
            ->where(function ($query) use ($bookingIdIn) {
                if (!empty($bookingIdIn)) {
                    $query->whereIn('id', $bookingIdIn);
                } else {
                    // dapatkan data kosong
                    $query->where('code', '');
                }
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
            Column::make('approval1')->title('Approval 1'),
            Column::make('approval2')->title('Approval 2'),
            Column::make('order')->title('Urutan Persetujuan'),
            Column::computed('action')
                ->title('Opsi')
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
