<?php

return array(
    // Module classes
    'classes' => array(
        'Modules\Reports\Components\ReportsProjectsComponent',
		'Modules\Reports\Controllers\ReportsIndex',
        'Modules\Reports\Controllers\ReportsProjects',
        'Modules\Reports\Controllers\ReportsTypes',
        'Modules\Reports\Controllers\ReportsEntities',
        'Modules\Reports\Controllers\ReportsTypeItems',
        'Modules\Reports\Controllers\ReportsEntityItems',
        'Modules\Reports\Controllers\ReportsEntityComments'
    ),
    // Management links
    'managementLinks' => array(
        A::t('reports', 'Projects') => 'reportsProjects/manage',
        A::t('reports', 'Report Types') => 'reportsTypes/manage'
    ),
);
