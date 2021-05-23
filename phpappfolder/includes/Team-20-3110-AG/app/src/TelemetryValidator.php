<?php


namespace Telemetry;


class TelemetryValidator
{
    public function __construct() { }

    public function __destruct() { }

    public function sanitiseTemp($temperature_to_sanitise): float
    {

        $sanitised_temperature = 0;

        if (!empty($temperature_to_sanitise)){

            $sanitised_temperature = filter_var($temperature_to_sanitise, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        }
        return $sanitised_temperature;
    }

    public function sanitiseString($string_to_sanitise): string
    {

        $sanitised_string = '';

        if (!empty($string_to_sanitise))
        {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $sanitised_string;

    }

    public function sanitiseDigit($value_to_sanitise): int
    {

        $sanitised_value = 0;

        if (!empty($value_to_sanitise)){

            $sanitised_value = filter_var($value_to_sanitise, FILTER_SANITIZE_NUMBER_INT);
        }
        return $sanitised_value;
    }


    public function cleanupParameters($app, array $tainted_parameters): array
    {
        $cleaned_parameters = [];
        $cleaned_parameters['valid'] = true;
        $tainted_device_id = $tainted_parameters['device_id'];
        $cleaned_parameters['device_id'] = $this->sanitiseString($tainted_device_id);
        if ( $cleaned_parameters['device_id']==='AG1') {
            $tainted_min = $tainted_parameters['min_temp'];
            $tainted_max = $tainted_parameters['max_temp'];
            $cleaned_parameters['min_temp'] = $this->sanitiseTemp($tainted_min);
            $cleaned_parameters['max_temp'] = $this->sanitiseTemp($tainted_max);
            if ($cleaned_parameters['min_temp']===null||$cleaned_parameters['max_temp']===null){
                $cleaned_parameters['valid'] = false;
            }
        }
        else
            {
                $tainted_tempValue = $tainted_parameters['tempValue'];
                $tainted_keypad = $tainted_parameters['keypadControl'];
                $tainted_motorControl = $tainted_parameters['motorControl'];
                $tainted_switch1 = $tainted_parameters['switchControl1'];
                $tainted_switch2 = $tainted_parameters['switchControl2'];
                $tainted_switch3 = $tainted_parameters['switchControl3'];
                $tainted_switch4 = $tainted_parameters['switchControl4'];

                $cleaned_parameters['tempValue'] = $this->sanitiseTemp($tainted_tempValue);
                $cleaned_parameters['keypadControl'] = $this->sanitiseDigit($tainted_keypad);
                $cleaned_parameters['motorControl'] = $this->sanitiseString($tainted_motorControl);
                $cleaned_parameters['switchControl1'] = $this->sanitiseString($tainted_switch1);
                $cleaned_parameters['switchControl2'] = $this->sanitiseString($tainted_switch2);
                $cleaned_parameters['switchControl3'] = $this->sanitiseString($tainted_switch3);
                $cleaned_parameters['switchControl4'] = $this->sanitiseString($tainted_switch4);
            
            
        }
        

        return $cleaned_parameters;
    }

}