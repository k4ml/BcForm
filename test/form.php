<?php

require "../init.php";

$form = new BcForm('aduan');

$properties = array(
    'values' => array(
        'default' => 'Kamal',
    ),
    'validation' => array(
        'max_lenght' => 10, 
        'required' => TRUE
    ),
);

$form->add_field('name', 'text');
$field = BcForm_Field::create('name', 'text', array(), 'helmi')->set_required(TRUE);
echo $field->render_widget();
