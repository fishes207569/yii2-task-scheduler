<?php

namespace ccheng\task\common\traits;

trait ValidateTrait
{
    public function validateJson($attribute, $params)
    {
        json_decode(trim($this->$attribute));
        if (json_last_error() != JSON_ERROR_NONE) {
            return $this->addError($attribute, "不是Json格式");
        }
    }
}