<?php

namespace Gregwar\Formidable\Fields;

/**
 * Date field
 *
 * @author Grégoire Passault <g.passault@gmail.com>
 */
class DateField extends Field
{

    /**
     * Field type
     */
    protected $type = 'Date';    
     /**
     * Minimal value
     */
    protected $min = null;

    /**
     * Maximum value
     */
    protected $max = null;
    
    public function push($name, DateTime $value = null) {
        switch ($name) {
            case 'min':
                if ($value instanceof DateTime && !is_null($value)) {
                    $this->min = $value;
                    $this->attributes['min'] = $value;
                } else {
                    throw new \UnexpectedValueException("Min Value is not DateTime");
                }

                break;
            case 'max':
                 if ($value instanceof DateTime && !is_null($value)) {
                    $this->max = $value;
                    $this->attributes['max'] = $value;
                } else {
                    throw new \UnexpectedValueException("Max Value is not DateTime");
                }
                break;
            default:
                parent::push($name, $value);
        }

        
    }
    
    public function fix() {
        parent::fix();
	if (is_string($value)) {
            try {
                $value = new \DateTime($value);
            } catch (Exception $e) {
                $this->value = null;
            }       
	}

	if ($value instanceof \DateTime) {
            $this->value = $value;
        } else {
            $this->value = null;
        }
    }
    
    public function check() {
        if (!$this->required && !$this->value) {
            return;
        }
        $error = parent::check();
        if ($error !== null) {
            return $error;
        }
        if ($this->min !== null) {
            if ($this->value < $this->min) {
                return array('date_min', $this->printName(), $this->min);
            }
        }

        if ($this->max !== null) {
            if ($this->value > $this->max) {
                return array('date_max', $this->printName(), $this->max);
            }
        }       

    }
    
    public function __sleep() {
        return array_merge(parent::__sleep(), array(
            'min', 'max'
        ));
    }
}
