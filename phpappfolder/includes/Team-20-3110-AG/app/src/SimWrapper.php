<?php

/**
 * SimWrapper.php
 *
 * Class to manage device simulations
 *
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */


namespace Telemetry;


class SimWrapper
{

    private float $min_temp;
    private float $max_temp;


    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function getTemperature($min_temp, $max_temp)
    {
        $this->min_temp = $min_temp;
        $this->max_temp = $max_temp;

        $temperature = rand($this->min_temp*100, $this->max_temp*100)/100;

        return $temperature;

    }

    public function getInputString($temperature, $switch1, $switch2, $switch3, $switch4, $motor, $keypad)
    {
            $temperature = $temperature*100;

        if ($temperature< 0) {
            $minus = 1;
            $temperature = $temperature * -1;
        } else {
            $minus = 0;
        }
        $done = str_pad($temperature, 6, "0", 0);

        return $switch1.$switch2.$switch3.$switch4.$motor.$keypad.$minus.$done;


    }



}
