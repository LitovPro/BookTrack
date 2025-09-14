<?php

namespace app\validators;

use yii\validators\FileValidator;

class SafeFileValidator extends FileValidator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $file = $model->$attribute;

        // Если файл не загружен или пустой, пропускаем валидацию
        if (empty($file) || !$file->tempName || !file_exists($file->tempName)) {
            return;
        }

        // Вызываем родительскую валидацию только если файл действительно загружен
        parent::validateAttribute($model, $attribute);
    }
}
