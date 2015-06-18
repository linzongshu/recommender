<?php
return array(
    'front' => array(),
    'admin' => array(
        array(
            'label' => 'Script',
            'url'   => '',
            
            'child' => array(
                array(
                    'label' => 'Collection',
                    'url'   => '/index.php/admin/learning/collection',
                ),
                array(
                    'label' => 'Load Dictionary',
                    'url'   => '/index.php/admin/learning/dictionary',
                ),
                array(
                    'label' => 'Load User Behavior',
                    'url'   => '/index.php/admin/learning/behavior',
                ),
                array(
                    'label' => 'Calculate Related Users',
                    'url'   => '/index.php/admin/learning/urelation',
                ),
                array(
                    'label' => 'Calculate Tag Relevancy',
                    'url'   => '/index.php/admin/learning/tag-relevancy',
                ),
            ),
        ),
        array(
            'label' => 'Tag',
            'url'   => '/index.php/admin/tag/lists/active/1',
            
            'child' => array(
                array(
                    'label' => 'List',
                    'url'   => '/index.php/admin/tag/lists/active/1',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/index.php/admin/tag/add',
                ),
            ),
        ),
        array(
            'label' => 'Tag Model',
            'url'   => '/index.php/admin/model/lists',
            
            'child' => array(
                array(
                    'label' => 'lists',
                    'url'   => '/index.php/admin/model/lists',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/index.php/admin/model/add',
                ),
            ),
        ),
        array(
            'label' => 'Business',
            'url'   => '/index.php/admin/business/lists',
            
            'child' => array(
                array(
                    'label' => 'lists',
                    'url'   => '/index.php/admin/business/lists',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/index.php/admin/business/add',
                ),
            ),
        ),
        array(
            'label' => 'User',
            'url'   => '/index.php/admin/user/lists',
        ),
    ),
);
