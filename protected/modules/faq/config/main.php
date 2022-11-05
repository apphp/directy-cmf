<?php

return array(
    // Module classes
    'classes' => array(   	
        'Modules\Faq\Components\FaqComponent',
        'Modules\Faq\Controllers\FaqCategories',
        'Modules\Faq\Controllers\FaqCategoryItems',
    ),

    // Management links
    'managementLinks' => array(
        A::t('faq', 'FAQ Categories') => 'faqCategories/manage',       
   	)
);
