<?php

class BcForm {
    public $name;
    public $fields = array();
    public $bound = False;

    protected $data;

    public function __construct($name, $data = array()) {
        $this->name = $name;
        $this->data = $data;

        if (is_array($data)) {
            $this->bound = True;
            foreach ($this->fields as $field) {
                $field->set_value($data[$field->name]);
            }
        }
    }

    public function render($name = '', $read_only = False) {
        if (!empty($name)) {
            return $this->fields[$name]->render_widget($read_only);
        }
        $out = '';
        foreach ($this->fields as $field) {
            $out .= $field->render_widget($read_only);
        }
        return $out;
    }

    public function validate() {
        $out = TRUE;
        foreach ($this->fields as $field) {
            $out = $out && $field->validate();
        }
        return $out;
    }

    public function add_field($name, $field, $properties = array(), $initial = Null) {
        $field_class_name = 'BcForm_'.BcForm::class_name($field);
        $this->fields[$name] = new $field_class_name($name, $properties, $initial);
    }

    public static function class_name($name) {
        if (strpos('_', $name)) {
            $chars = explode('_', strtolower($name));
            $new_names = array();
            foreach ($chars as $char) {
                $new_names[] = ucfirst($char);
            }
            return implode('_', $new_names);
        }
        return ucfirst($name);
    }
}
