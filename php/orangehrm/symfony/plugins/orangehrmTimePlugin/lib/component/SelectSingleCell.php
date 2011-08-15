<?php

class SelectSingleCell extends Cell {

    public function __toString() {
        $defaultOption = $this->getPropertyValue('defaultOption');

        $options = $this->getPropertyValue('options', $defaultOption);

        if (($options instanceof sfOutputEscaperArrayDecorator) || is_array($options)) {
            list($object, $method, $params) = $options;
            $object = ($object instanceof sfOutputEscaperObjectDecorator) ? $object->getRawValue() : $object;
            $params = ($params instanceof sfOutputEscaperArrayDecorator) ? $params->getRawValue() : $params;

            foreach ($params as $key => $value) {
                if ($value === ohrmListConfigurationFactory::RECORD) {
                    $params[$key] = $this->dataObject;
                }
            }

            $options = call_user_func_array(array($object, $method), $params);
        }

        $optionsHTML = content_tag('option', __($defaultOption['label']), array('value' => $defaultOption['value'])) . "\n";

        foreach ($options as $value => $label) {
            $optionsHTML .= content_tag('option', __($label), array('value' => $value)) . "\n";
        }

        $placeholderGetters = $this->getPropertyValue('placeholderGetters');
        $id = $this->generateAttributeValue($placeholderGetters, $this->getPropertyValue('idPattern'));
        $name = $this->generateAttributeValue($placeholderGetters, $this->getPropertyValue('namePattern'));

        return content_tag('select', $optionsHTML, array(
            'id' => $id,
            'name' => $name,
        ));
    }

}