<?php

/**
 * Description ...
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @author     Antonio Oliverio <antonio.oliverio@gmail.com>
 * @copyright  Copyright 2014, Antonio Oliverio (http://www.aoliverio.com)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @since      CakePHP(tm) v 2.1.1
 */
App::uses('FormHelper', 'View/Helper');

/**
 * 
 */
class BootstrapFormHelper extends FormHelper {

    /**
     * Create
     *
     * @param $model string
     * @param $options array
     * @return string
     */
    public function create($model = null, $options = array()) {

        if (empty($options['class']))
            $options['class'] = 'form-horizontal';

        if (empty($options['role']))
            $options['role'] = 'form';

        /**
          $modelKey = $this->model();
          $Model = ClassRegistry::init($modelKey);

          if (!empty($Model->displayFieldTypes[$modelKey])) {
          if (in_array('image', $Model->displayFieldTypes[$modelKey]) || in_array('file', $Model->displayFieldTypes[$modelKey])) {
          $type_val = array('type' => 'file');
          $options = array_merge($type_val, $options);
          }
          }
         * */
        return parent::create($model, $options);
    }

    /**
     * Input
     *
     * @param $fieldName string
     * @param $options array
     * @return string
     */
    public function input($fieldName, $options = array()) {
        $this->setEntity($fieldName);
        $defaults = array(
            'class' => 'form-control',
            'format' => array(
                'before',
                'label',
                'between',
                'input',
                'error',
                'after'
            ),
            'div' => array(
                'class' => ''
            ),
            'error' => array(
                'attributes' => array(
                    'class' => 'help-inline',
                    'wrap' => 'span'
                )
            ),
            'help' => '',
        );

        $modelKey = $this->model();
        $fieldKey = $this->field();
        $type = $this->_introspectModel($modelKey, 'fields', $fieldKey);
        $Model = ClassRegistry::init($modelKey);

        /**
         * Associations
         */
        foreach ($Model->belongsTo as $bgOptions) {
            if ($bgOptions['foreignKey'] == $fieldKey) {
                $belongModel = ClassRegistry::init($bgOptions['className']);
                $options['options'] = $belongModel->find('list', array(
                    'conditions' => $bgOptions['conditions'],
                    'fields' => $bgOptions['fields'],
                    'order' => $bgOptions['order'],
                ));
            }
        }

        /**
         * Automagic field types setting
         */
        if ($type['type'] == 'date') {
            $type_val = array('class' => 'bs-datepicker form-control', 'type' => 'text');
            $options = array_merge($type_val, $options);
        }

        if ($type['type'] == 'timestamp') {
            $type_val = array('class' => 'bs-datepicker form-control', 'type' => 'text');
            $options = array_merge($type_val, $options);
        }

        if ($type['type'] == 'datetime') {
            $before = '<div class="form-group col-sm-4"><div class="input-group">';
            $after = '<span class="input-group-addon"><span class="fa fa-calendar"></span></span></div></div>';
            $type_val = array('class' => 'bs-datepicker form-control', 'type' => 'text', 'before' => $before, 'after' => $after);
            $options = array_merge($type_val, $options);
        }

        if ($type['type'] == 'boolean') {
            $type_val = array('class' => '', 'type' => 'checkbox');
            $options = array_merge($type_val, $options);
        }

        if ($type['type'] == 'checkbox') {
            $type_val = array('class' => '', 'type' => 'checkbox');
            $options = array_merge($type_val, $options);
        }

        /**
         * Use $displayFieldTypes setting for input customize
         */
        if (!empty($Model->displayFieldTypes)) {

            if (in_array($fieldKey, array_keys($Model->displayFieldTypes))) {

                if ($Model->displayFieldTypes[$fieldKey] == 'hidden') {
                    $type_val = array('type' => 'hidden');
                    $options = array_merge($type_val, $options);
                }

                if ($Model->displayFieldTypes[$fieldKey] == 'datepicker') {
                    $type_val = array('type' => 'text', 'class' => 'bs-datepicker form-control');
                    $options = array_merge($type_val, $options);
                }

                if ($Model->displayFieldTypes[$fieldKey] == 'wysihtml') {
                    $type_val = array('type' => 'textarea', 'class' => 'wysihtml');
                    $options = array_merge($type_val, $options);
                }

                if ($Model->displayFieldTypes[$fieldKey] == 'wysiwyg') {
                    $type_val = array('type' => 'textarea', 'class' => 'summernote');
                    $options = array_merge($type_val, $options);
                }

                if ($Model->displayFieldTypes[$fieldKey] == 'file') {
                    $type_val = array('type' => 'file', 'class' => 'bs-file-input');
                    $options = array_merge($type_val, $options);
                }

                if ($Model->displayFieldTypes[$fieldKey] == 'image') {
                    $imagelink = '';
                    if (isset($this->data[$modelKey][$fieldKey]) && !empty($this->data[$modelKey][$fieldKey])) {
                        $imagefile = $this->data[$modelKey][$fieldKey];
                        $fullpath = APP . 'webroot' . DS . 'img' . DS . $imagefile;
                        if (file_exists($fullpath)) {
                            $imagelink = "<img src='../../../img/" . $imagefile . "' width='150'> ";
                        }
                    }
                    $type_val = array('type' => 'file', 'class' => 'bs-file-input', 'between' => $imagelink);
                    $options = array_merge($type_val, $options);
                }
            }
        }

        $options = array_merge($defaults, $options);

        if (!empty($options['help'])) {
            $options['after'] = '<span class="help-block">' . $options['help'] . '</span>' . $options['after'];
        }

        return parent::input($fieldName, $options);
    }

    /**
     * Submit
     *
     * @param $caption string
     * @return string
     */
    public function submit($caption = null, $options = array()) {
        $options = array(
            'div' => array(
                'class' => 'text-right'
            ),
            'class' => 'btn btn-primary'
        );
        return parent::submit($caption, $options);
    }

    /**
     * Use bootstrap3 style
     * 
     * @param type $fields
     * @param type $blacklist
     * @param type $options
     * @return string
     */
    public function inputs($fields = null, $blacklist = null, $options = array()) {

        $model = $this->model();
        $modelFields = array();

        if ($model)
            $modelFields = array_keys((array) $this->_introspectModel($model, 'fields'));

        if (empty($fields))
            $fields = $modelFields;

        $out = null;

        foreach ($fields as $name => $options) {

            if (is_numeric($name) && !is_array($options)) {
                $name = $options;
                $options = array();
            }

            $entity = explode('.', $name);
            if (is_array($blacklist) && (in_array($name, $blacklist) || in_array(end($entity), $blacklist)))
                continue;

            $uid = $model . '_' . $name;
            $caption = str_replace('_id', '', $name);
            $caption = Inflector::humanize($caption);

            $options['label'] = false;
            $options['div'] = false;
            $options['id'] = $uid;

            $out .= '<div class="form-group">';
            $out .= '<label for="' . $uid . '" class="col-sm-2 control-label">' . $caption . '</label>';
            $out .= '<div class="col-sm-10">' . $this->input($name, $options) . '</div>';
            $out .= '</div>';
        }

        return $out;
    }

}
