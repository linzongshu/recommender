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
                    'url'   => '/admin/learning/collection',
                ),
                array(
                    'label' => 'Load Dictionary',
                    'url'   => '/admin/learning/dictionary',
                ),
                array(
                    'label' => 'Load User Behavior',
                    'url'   => '/admin/learning/behavior',
                ),
                array(
                    'label' => 'Calculate Related Users',
                    'url'   => '/admin/learning/urelation',
                ),
                array(
                    'label' => 'Calculate Tag Relevancy',
                    'url'   => '/admin/learning/tag-relevancy',
                ),
            ),
        ),
        array(
            'label' => 'Tag',
            'url'   => '/admin/tag/lists/active/1',
            
            'child' => array(
                array(
                    'label' => 'List',
                    'url'   => '/admin/tag/lists/active/1',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/admin/tag/add',
                ),
            ),
        ),
        array(
            'label' => 'Tag Model',
            'url'   => '/admin/model/lists',
            
            'child' => array(
                array(
                    'label' => 'lists',
                    'url'   => '/admin/model/lists',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/admin/model/add',
                ),
            ),
        ),
        array(
            'label' => 'Business',
            'url'   => '/admin/business/lists',
            
            'child' => array(
                array(
                    'label' => 'lists',
                    'url'   => '/admin/business/lists',
                ),
                array(
                    'label' => 'add',
                    'url'   => '/admin/business/add',
                ),
            ),
        ),
        array(
            'label' => 'User',
            'url'   => '/admin/user/lists',
        ),
    ),
);
