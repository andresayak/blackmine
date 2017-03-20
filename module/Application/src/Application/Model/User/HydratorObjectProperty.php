<?php

namespace Application\Model\User;

class HydratorObjectProperty extends \Zend\Stdlib\Hydrator\ObjectProperty
{
    public function extract($object)
    {
        $data = $object->toArray();
        $filter = $this->getFilter();

        foreach ($data as $name => $value) {
            if (! $filter->filter($name)) {
                unset($data[$name]);
                continue;
            }
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
        foreach ($data as $name => $value) {
            $property = $this->hydrateName($name, $data);
            if($property == 'password'){
                if(!$object->id || (isset($data['password_change']) && $data['password_change'])){
                    $object->$property = $this->hydrateValue($property, $value, $data);
                }
            }else{
                $object->$property = $this->hydrateValue($property, $value, $data);
            }
        }

        return $object;
    }
}
