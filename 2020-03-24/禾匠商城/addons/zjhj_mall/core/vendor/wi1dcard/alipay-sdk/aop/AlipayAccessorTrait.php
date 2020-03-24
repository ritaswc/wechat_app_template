<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidPropertyException;

trait AlipayAccessorTrait
{
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new AlipayInvalidPropertyException('Getting write-only property', $name);
        }

        throw new AlipayInvalidPropertyException('Getting unknown property', $name);
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new AlipayInvalidPropertyException('Setting read-only property', $name);
        } else {
            throw new AlipayInvalidPropertyException('Setting unknown property', $name);
        }
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new AlipayInvalidPropertyException('Unsetting read-only property', $name);
        }
    }
}
