<?php

return array(
    // Module classes
    'classes' => array(
        'Backup'
    ),
    // Management links
    'managementLinks' => array(
        A::t('backup', 'Create') => 'backup/create',
        A::t('backup', 'Restore') => 'backup/restore'
    ),
);
