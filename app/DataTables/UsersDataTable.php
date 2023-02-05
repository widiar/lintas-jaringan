<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'users.action')
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    // public function html(): HtmlBuilder
    // {
    //     return $this->builder()
    //         ->setTableId('users-table')
    //         ->columns($this->getColumns())
    //         ->minifiedAjax()
    //         //->dom('Bfrtip')
    //         ->orderBy(1)
    //         ->selectStyleSingle()
    //         ->buttons([
    //             Button::make('excel'),
    //             Button::make('csv'),
    //             Button::make('pdf'),
    //             Button::make('print'),
    //             Button::make('reset'),
    //             Button::make('reload')
    //         ]);
    // }

    // public function getColumns(): array
    // {
    //     return [
    //         Column::computed('action')
    //             ->exportable(true)
    //             ->printable(true)
    //             ->width(60)
    //             ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('add your columns'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //     ];
    // }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom'          => 'Bfrtip',
                'buttons'      => ['export', 'print', 'reset', 'reload'],
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'username',
            'email',
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
