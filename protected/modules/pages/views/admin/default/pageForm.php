<?php

/*** Create/update page form ***/
Yii::import('zii.widgets.jui.CJuiDatePicker');

return array(
	'id'=>'pageUpdateForm',
	'showErrorSummary'=>true,
	'elements'=>array(
		'content'=>array(
			'type'=>'form',
			'title'=>'Содержимое',
			'elements'=>array(
				'title'=>array(
		            'type'=>'text',
		        ),
				'url'=>array(
		            'type'=>'text',
		        ),
		        'status'=>array(
		        	'type'=>'dropdownlist',
		        	'items'=>Page::statuses()
		        ),
				'publish_date'=>array(
                    'type'=>'CJuiDatePicker',
                    'options'=>array(
                        'dateFormat'=>'yy-mm-dd '.date('H:i:s'),
                    ),
                ),
		        'short_description'=>array(
		            'type'=>'textarea',
		        ),
		        'full_description'=>array(
		            'type'=>'textarea',
		        ),
			),
		),
		'seo'=>array(
			'type'=>'form',
			'title'=>'SEO',
			'elements'=>array(
				'meta_title'=>array(
		            'type'=>'text',
		        ),
				'meta_keywords'=>array(
		            'type'=>'textarea',
		        ),
		        'meta_description'=>array(
		            'type'=>'textarea',
		        ),
			),
		),
	),
);