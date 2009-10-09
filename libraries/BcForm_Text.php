<?php

class BcForm_Text extends BcForm_Field {

    function __construct($name, $properties = array(), $initial = Null) {
        parent::__construct($name, $properties, $initial);

        if (isset($this->properties['validation']['max_length'])) {
            $max_length = $this->properties['validation']['max_length'];
            $this->attributes['maxlength'] = $max_length;
            $this->attributes['size'] = $max_length;
        }
        if (is_array($this->properties['widget'])) {
            if (isset($this->properties['widget']['size'])) {
                $this->attributes['size'] = $this->properties['widget']['size'];
            }
        }
        if ($this->read_only) {
            $this->attributes['readOnly'] = True;
        }
    }

    function get_value() {
        $result = $this->value;

        if(!empty($this->value)) {
            if ($this->properties['type'] == 'numeric') {
                $result = number_format($this->value, 2);
            }
        } else {
            $result = '- n/a -';
        }

        return $result;
    }

    function validate() {
        if ($this->is_required()) {
            if (empty($this->value)) {
                $this->set_error('Required !');
                return False;
            }
        }
        return True;
    }

    function render() {
        $widget = form::input($this->attributes, $this->value);
        return $widget;
    }
}
