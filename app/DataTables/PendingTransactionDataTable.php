<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class PendingTransactionDataTable extends DataTable
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
            ->addColumn('action', 'transaction.action')
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model): QueryBuilder
    {
        $query = Transaction::join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'customers.id', '=', 'users.customer_id')
                ->select("transactions.id as transaction_id", "customers.id as customer_id", "users.name as customer_name", "users.email as customer_email", "transactions.total as total", "transactions.status as status", "transactions.approved_at as approved_at")
                ->where('transactions.status', '=', 'PENDING');

        // Customer are only able to see their own transactions.
        if(Auth::user()->customer != null) {
            $query = $query->where('customers.id', '=', Auth::user()->customer->id);
        }
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('transaction-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->addColumn([
                        'name' => 'action',
                        'data' => 'id',
                        'title' => 'Action',
                        'searchable' => false,
                        'orderable' => false,
                        'render' => "function() {return `<a href='/transaction/` + this.transaction_id + `/view'>View</a> | <a href='/transaction/` + this.transaction_id + `/approve'>Approve</a> | <a href='/transaction/` + this.transaction_id + `/revoke'>Revoke</a>`}",
                        'footer' => 'Action',
                        'exportable' => false,
                        'printable' => false,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('transaction_id'),
            Column::make('customer_name'),
            Column::make('customer_email'),
            Column::make('total'),
            Column::make('status'),
            Column::make('approved_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
