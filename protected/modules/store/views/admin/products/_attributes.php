<?php

/**
 * Product update.
 * Options tab.
 */

if ($model->type)
{
	$chosen = array(); // Array of ids to enable chosen
	$attributes = $model->type->storeAttributes;

	if(empty($attributes))
		echo Yii::t('StoreModule.admin', 'Список свойств пустой');
	else
	{
		foreach($attributes as $a)
		{
			// Repopulate data from POST if exists
			if(isset($_POST['StoreAttribute'][$a->name]))
				$value = $_POST['StoreAttribute'][$a->name];
			else
				$value = $model->getEavAttribute($a->name);

			$a->required ? $required = ' <span class="required">*</span>' : $required = null;

			if($a->type == StoreAttribute::TYPE_DROPDOWN)
				$chosen[] = $a->getIdByName();

			echo CHtml::openTag('div', array('class'=>'row'));
			echo CHtml::label($a->attr_translate->title.$required, $a->name, array('class'=> $a->required ? 'required' : ''));
			echo '<div class="rowInput">'.$a->renderField($value).'</div>';
			echo CHtml::closeTag('div');
		}
	}

	// Enable chosen
	$this->widget('application.modules.admin.widgets.schosen.SChosen', array('elements'=>$chosen));

}
