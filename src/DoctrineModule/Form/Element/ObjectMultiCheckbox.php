<?php

declare(strict_types=1);

namespace DoctrineModule\Form\Element;

use Laminas\Form\Element\MultiCheckbox;
use Laminas\Stdlib\ArrayUtils;
use Traversable;
use function array_map;
use function is_array;

class ObjectMultiCheckbox extends MultiCheckbox
{
    /** @var Proxy */
    protected $proxy;

    public function getProxy() : Proxy
    {
        if ($this->proxy === null) {
            $this->proxy = new Proxy();
        }

        return $this->proxy;
    }

    /**
     * @param array|Traversable $options
     */
    public function setOptions($options) : self
    {
        $this->getProxy()->setOptions($options);

        return parent::setOptions($options);
    }

    /**
     * @param mixed $value
     */
    public function setOption(string $key, $value) : self
    {
        $this->getProxy()->setOptions([$key => $value]);

        return parent::setOption($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
        if ($value instanceof Traversable) {
            $value = ArrayUtils::iteratorToArray($value);
        } elseif ($value === null) {
            return parent::setValue([]);
        } elseif (! is_array($value)) {
            $value = (array) $value;
        }

        return parent::setValue(array_map([$this->getProxy(), 'getValue'], $value));
    }

    /**
     * {@inheritDoc}
     */
    public function getValueOptions()
    {
        if (! empty($this->valueOptions)) {
            return $this->valueOptions;
        }

        $proxyValueOptions = $this->getProxy()->getValueOptions();

        if (! empty($proxyValueOptions)) {
            $this->setValueOptions($proxyValueOptions);
        }

        return $this->valueOptions;
    }
}
