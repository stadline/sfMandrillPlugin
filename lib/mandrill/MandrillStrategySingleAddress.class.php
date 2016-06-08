<?php

class MandrillStrategySingleAddress implements MandrillStrategyInterface
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function convertAddresses($ignoredAddresses)
    {
        $to = array();

        $to[] = array(
            'email' => $this->email,
            'name' => $this->email,
            'type' => 'to',
        );

        return $to;
    }
}
