<?php

namespace Application\Model;

class HydratorObjectProperty extends \Zend\Stdlib\Hydrator\ObjectProperty
{
    public function extract($object)
    {
        $data = $object->toArray();
        $filter = $this->getFilter();
        foreach ($data as $name => $value) {
            // Filter keys, removing any we don't want
            if (! $filter->filter($name)) {
                unset($data[$name]);
                continue;
            }

            // Replace name if extracted differ
            $extracted = $this->extractName($name, $object);

            if ($extracted !== $name) {
                unset($data[$name]);
                $name = $extracted;
            }

            $data[$name] = $this->extractValue($name, $value, $object);
        }

        return $data;
    }

    public function hydrate(array $data, $object)
    {
        foreach ($object->getTable()->getCols() as $col){
            $property = $this->hydrateName($col, $data);
            $object->$property = $this->hydrateValue($property, (isset($data[$col])?$data[$col]:$object->$col), $data);
        }

        return $object;
    }
}
