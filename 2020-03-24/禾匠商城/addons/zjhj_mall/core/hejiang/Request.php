<?php

namespace app\hejiang;

class Request extends \yii\web\Request
{
    public function getParam($name, $defaultValue = null)
    {
        $queryParam = $this->getQueryParam($name);
        if ($queryParam !== null) {
            return $queryParam;
        }

        $bodyParam = $this->getBodyParam($name);
        if ($bodyParam !== null) {
            return $bodyParam;
        }
        
        return $defaultValue;
    }
}