<?php

abstract class BcForm_Field {
    public $name;
    public $description;
    public $read_only = False;
    public $properties = array();

    protected $form;
    protected $value;
    protected $attributes = array();
    protected $rules = array();
    protected $error;

    function __construct($name, $properties = array(), $initial = Null) {
        $this->name = $name;
        $this->properties = $properties;
        $this->attributes['name'] = $this->name;
        $this->attributes['id'] = "form-{$this->name}";

        if (isset($properties['description'])) {
            $this->description = $properties['description'];
        }

        if (isset($properties['values']['default'])) {
            $this->value = $properties['values']['default'];
        }

        // Always override if user pass initial value
        if (!empty($initial)) {
            $this->value = $initial;
        }

        if (isset($properties['read_only'])) {
            $this->read_only = $properties['read_only'];
        }
    }

    public static function create($name, $field, $properties = array(), $initial = Null) {
        // No late static binding as in PHP 5.3, otherwise we don't need
        // field and let user called BcForm_<Type> directly.
        $class_name = 'BcForm_'.BcForm::class_name($field);
        return new $class_name($name, $properties, $initial);
    }

    function set_value($value) {
        $this->value = $value;
    }

    function set_error($error) {
        $this->error = $error;
    }

    /**
     * Allow field to sanitize submitted data for validation.
     *
     * Should be override by child class to provide actual filtering.
     *
     * @param mixed
     * @return mixed filtered value
     */
    function filter($value) {
        return $value;
    }

    function get_rules() {
        $validation = $this->properties['validation'] OR array();
        $rules = array();
        if (isset($validation['rules'])) {
            $rules = $validation['rules'];
        }
        
        // We merge with the rules specified in YAML
        // This way it's easy to create self validating
        // widget.
        return array_merge($this->rules, $rules);
    }

    public function render_widget($read_only = False) {
        if ($read_only) {
            return $this->render_read_only();
        }
        $this->attributes['class'] = array();
        if ($this->is_required()) {
            $this->attributes['class'][] = 'required';
        }

        if ($this->error != '') {
            $this->attributes['class'][] = 'error';
        }
        $this->attributes['class'] = implode(' ', $this->attributes['class']);

        $out = '';
        if ($this->error != '') {
            $out = '<div class="error">';
            $out .= $this->error;
            $out .= '</div>';
        }
        $out .= $this->render();
        return $out;
    }

    function render_read_only() {
        if (isset($this->properties['values']['one_sql'])) {
            return $this->get_value(True);
        }
        return $this->get_value();
    }

    function label() {
        if (isset($this->properties['description'])) {
            $description = $this->properties['description'];
        } else {
            $description = $this->name;
        }
        $out = '<label for="'.$this->name."\">$description</label>\n";
        return $out;
    }

    function is_required() {
        $required = $this->properties['validation']['required'];
        if (!empty($required)) {
            $this->required = $required;
        }
        return $this->required;
    }

    function set_required($required) {
        $this->required = $required;
        return $this;
    }

    function validate() {
        return True;
    }

    /**
     * Allow each widget to return value in certain format.
     *
     * This way widget do not need to override render() method just
     * to customize how the value is displayed.
     *
     * @return mixed formatted value.
     */
    function get_value($lookup = False) {
        return $this->value;
    }

    function render($readonly=false, $extra=array()) {
    }
}
