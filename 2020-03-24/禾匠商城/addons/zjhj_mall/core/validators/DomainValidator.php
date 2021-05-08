<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/12 10:49
 */


namespace app\validators;


use yii\validators\Validator;

class DomainValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $pattern = '/^[-a-zA-Z0-9\x{4e00}-\x{9fa5}]{1,64}(\.[-a-zA-Z0-9\x{4e00}-\x{9fa5}]{1,64}){1,4}(\:[0-9]{1,5}){0,1}$/u';
        if ($value && !preg_match($pattern, $value)) {
            $model->addError($attribute, "{$model->getAttributeLabel($attribute)}格式不正确。");
        }
    }
}
