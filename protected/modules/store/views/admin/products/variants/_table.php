<?php
/**
 * Represent attribute row on "Variants" tab
 *
 * @var StoreAttribute $attribute
 */
?>

<h4>
<?php
    echo CHtml::encode($attribute->title);
    echo CHtml::link(' +', '#', array(
        'rel'=>'#variantAttribute'.$attribute->id,
        'onclick'=>'js: return cloneVariantRow($(this));'
    ));
?>
</h4>

<table class="variantsTable" id="variantAttribute<?php echo $attribute->id ?>">
    <thead>
    <tr>
        <td>Значение</td>
        <td>Цена</td>
        <td>Тип цены</td>
        <td>Артикул</td>
        <td></td>
    </tr>
    </thead>
    <tbody>
        <?php
            if(!isset($options)):
        ?>
        <tr>
            <td>
                <?php
                    echo CHtml::dropDownList('variants['.$attribute->id.'][option_id][]', null, CHtml::listData($attribute->options, 'id', 'value'));
                ?>
            </td>
            <td>
                <input type="text" name="variants[<?php echo $attribute->id ?>][price][]">
            </td>
            <td>
                <select name="variants[<?php echo $attribute->id ?>][price_type][]">
                    <option value="fixed">Фиксированная</option>
                    <option value="percent">Процент</option>
                </select>
            </td>
            <td>
                <input type="text" name="variants[<?php echo $attribute->id ?>][sku][]">
            </td>
            <td>
                <a href="#" onclick="$(this).parent().parent().remove(); return false;">Удалить</a>
            </td>
        </tr>
        <?php
            endif;
        ?>
        <?php
            if(isset($options)):
            foreach($options as $o):
        ?>
        <tr>
            <td>
                <?php
                    echo CHtml::dropDownList('variants['.$attribute->id.'][option_id][]', $o->option->id, CHtml::listData($attribute->options, 'id', 'value'));
                ?>
            </td>
            <td>
                <input type="text" name="variants[<?php echo $attribute->id ?>][price][]" value="<?php echo $o->price ?>">
            </td>
            <td>
                <select name="variants[<?php echo $attribute->id ?>][price_type][]">
                    <option value="fixed">Фиксированная</option>
                    <option value="percent">Процент</option>
                </select>
            </td>
            <td>
                <input type="text" name="variants[<?php echo $attribute->id ?>][sku][]" value="<?php echo $o->sku ?>">
            </td>
            <td>
                <a href="#" onclick="$(this).parent().parent().remove(); return false;">Удалить</a>
            </td>
        </tr>
        <?php
            endforeach;
            endif;
        ?>
    </tbody>
</table>