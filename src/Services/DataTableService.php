<?php

namespace Pedreiro\Services;

use Yajra\DataTables\Html\Button;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DataTableService extends DataTable
{
    use \Pedreiro\Traits\CrudModelUtilsTrait;

    public function __construct($model)
    {
        $this->model = $model;
        if (is_string($this->model)) {
            $this->model = app($this->model);
        }
    }
    
    /**
     * @return static
     */
    public static function make($model): self
    {
        return new static($model);
    }
    public static function makeHtml($model): \Yajra\Datatables\Html\Builder
    {
        return (static::make($model))->html();
    }


    // /**
    //  * Display ajax response.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function ajax()
    // {
    //     return $this->datatables
    //         ->eloquent($this->query())
    //         ->make(true);
    // }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'users.action');
    }


    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $users = $this->model->select();

        return $this->applyScopes($users);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
        // return $this->builder()
        //             ->columns($this->getColumns())
        //             ->parameters([
        //                 'dom'          => 'Bfrtip',
        //                 'buttons'      => ['export', 'print', 'reset', 'reload'],
        //                 'initComplete' => "function () {
        //                     this.api().columns().every(function () {
        //                         var column = this;
        //                         var input = document.createElement(\"input\");
        //                         $(input).appendTo($(column.footer()).empty())
        //                         .on('change', function () {
        //                             column.search($(this).val(), false, false, true).draw();
        //                         });
        //                     });
        //                 }",
        //             ]);
    }

    /**
     * @return array
     */
    protected function getColumns()
    {
        $returnColumns = [];
        foreach ($this->getIndexFields() as $column) {
            $returnColumns[] = Column::make($column['name']);
            // Column::make('id'),
            // Column::make('name'),
            // Column::make('email'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
        }
        $returnColumns[] = Column::computed('actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center');

        return $returnColumns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }

    // /**
    //  * @return array
    //  */
    // public function scripts()
    // {
    //     $html = "<script type=\"text/javascript\">
    //     $('#table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         'ajaxSource': 'http://localhost:99/admin/photo-data',
    //         'columns': [";
    //         foreach ($this->getElementFromIndexFields('name') as $column) {
    //             $html .= "
    //                 {
    //                     data: '".$column."',
    //                     name: '".$column."'
    //                 },";
    //                 // {
    //                 //     'data': 'platform',
    //                 //     'render': '[, ].name'
    //                 // }
    //         }
    //         $html .= "
    //         {
    //             data: 'actions',
    //             name: 'actions'
    //         }";
    //         $html .= "]
    //         // ajax: 'http://localhost:99/admin/photo-data'
    //     });
    // </script>";
    //     return $html;
    // }
}
