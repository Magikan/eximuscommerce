<?php

/**
 *
 * @var $this SFilterRenderer
 */

/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 *	    'title'=>'Filter Title',
 *	    'selectMany'=>true, // Can user select many filter options
 *	    'filters'=>array(array(
 *	        'title'      => 'Title',
 *	        'count'      => 'Products count',
 *	        'queryKey'   => '$_GET param',
 *	        'queryParam' => 'many',
 *	    ))
 *  );
 */

// Render active filters
$active = $this->getActiveFilters();
if(!empty($active))
{
	echo CHtml::openTag('div', array('class'=>'rounded'));
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo Yii::t('StoreModule.core', 'Текущие фильтры');
		echo CHtml::closeTag('div');

		$this->widget('zii.widgets.CMenu', array(
			'htmlOptions'=>$this->activeFiltersHtmlOptions,
			'items'=>$active
		));

		echo CHtml::link(Yii::t('StoreModule.core','Сбросить фильтр'), $this->getOwner()->createUrl('view', array('url'=>$this->model->url)), array('class'=>'cancel_filter'));
	echo CHtml::closeTag('div');
}

if(!empty($manufacturers['filters']) || !empty($attributes))
	echo CHtml::openTag('div', array('class'=>'rounded'));

	// Render manufacturers
	if(!empty($manufacturers['filters']))
	{
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo CHtml::encode(Yii::t('StoreModule.core', 'Производитель'));
		echo CHtml::closeTag('div');

		echo CHtml::openTag('ul', array('class'=>'filter_links'));
		foreach($manufacturers['filters'] as $filter)
		{
			$url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));
			$filter = CHtml::encodeArray($filter);

			echo CHtml::openTag('li');
			// Filter link was selected.
			if(in_array($filter['queryParam'], $queryData))
			{
				// Create link to clear current filter
				$url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
				echo CHtml::link($filter['title'], $url, array('style'=>'color:green'));
			}
			elseif($filter['count'] > 0)
				echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
			else
				echo Chtml::encode($filter['title']).' (0)';

			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
	}

	// Display attributes
	foreach($attributes as $attrData)
	{
		echo CHtml::openTag('div', array('class'=>'filter_header'));
		echo CHtml::encode($attrData['title']);
		echo CHtml::closeTag('div');

		echo CHtml::openTag('ul', array('class'=>'filter_links'));
		foreach($attrData['filters'] as $filter)
		{
			$url = Yii::app()->request->addUrlParam('/store/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));
			$filter = CHtml::encodeArray($filter);

			echo CHtml::openTag('li');
			// Filter link was selected.
			if(in_array($filter['queryParam'], $queryData))
			{
				// Create link to clear current filter
				$url = Yii::app()->request->removeUrlParam('/store/category/view', $filter['queryKey'], $filter['queryParam']);
				echo CHtml::link($filter['title'], $url, array('style'=>'color:green'));
			}
			elseif($filter['count'] > 0)
				echo CHtml::link($filter['title'], $url).' ('.$filter['count'].')';
			else
				echo Chtml::encode($filter['title']).' (0)';

			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
	}

if(!empty($manufacturers['filters']) || !empty($attributes))
	echo CHtml::closeTag('div');