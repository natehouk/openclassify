<?php namespace Visiosoft\LocationModule\City\Table;

use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class CityTableBuilder extends TableBuilder
{

    /**
     * The table views.
     *
     * @var array|string
     */
    protected $views = [];

    /**
     * The table filters.
     *
     * @var array|string
     */
    protected $filters = [];

    /**
     * The table columns.
     *
     * @var array|string
     */
    protected $columns = [];

    /**
     * The table buttons.
     *
     * @var array|string
     */
    protected $buttons = [
        'add_sub_districts' => [
            'icon' => 'fa fa-caret-square-o-down',
            'type' => 'success',
            'href' => '/admin/location/districts/create?districts={entry.id}'
        ],
        'sub_districts' => [
            'icon' => 'fa fa-caret-square-o-down',
            'type' => 'success',
            'href' => '/admin/location/districts?city={entry.id}'
        ],
        'edit'
    ];

    /**
     * The table actions.
     *
     * @var array|string
     */
    protected $actions = [
        'delete'
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'order_by' => [
            'id' => 'DESC',
        ],
    ];

    /**
     * The table assets.
     *
     * @var array
     */
    protected $assets = [];

}
