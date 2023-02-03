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


class StaffsDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id');
    }
 
    public function query(User $model): QueryBuilder
    {
        $query = User::join('staffs', 'staffs.user_id', '=', 'users.id')
                ->select('users.id as account_id', 'users.name', 'users.email', 'staffs.gender');
        
        return $query;
    }
 
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->addColumn([
                        'name' => 'action',
                        'data' => 'account_id',
                        'title' => 'Action',
                        'searchable' => false,
                        'orderable' => false,
                        'render' => "function() {return `<a href='/staff/` + this.account_id + `/edit'>Edit</a> | <a href='/staff/` + this.account_id + `/delete'>Delete</a>`}",
                        'footer' => 'Action',
                        'exportable' => false,
                        'printable' => false,
                    ]);
    }
 
    public function getColumns(): array
    {
        return [
            Column::make('account_id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('gender'),
        ];
    }
 
    protected function filename(): string
    {
        return 'Users_'.date('YmdHis');
    }
}
