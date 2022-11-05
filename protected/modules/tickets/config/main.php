<?php

return array(
    // Module classes
    'classes' => array(
        'Modules\Tickets\Components\TicketsComponent',
		'Modules\Tickets\Controllers\Tickets',
        'Modules\Tickets\Controllers\TicketReplies',
    ),

    // Management links
    'managementLinks' => array(
        A::t('tickets', 'Tickets Management') => 'tickets/manage',
    ),

	// Used to define members data
	'ticketMembers' => array(
		'memberRole' => 'user',
		'dbTable' => 'users_accounts',
		'loginUrl' => 'users/login',
	),
);
