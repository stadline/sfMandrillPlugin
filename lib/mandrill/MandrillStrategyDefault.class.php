<?php

class MandrillStrategyDefault implements MandrillStrategyInterface
{
    /**
     * Convert template addresses to Mandrill "to" format
     *
     * @param array $addresses
     * @return array $to
     */
    public function convertAddresses($addresses)
    {
        $to = array();

        foreach (array('to', 'cc', 'bcc') as $type) {
            if (!isset($addresses[$type])) {
                continue;
            }

            foreach ((array) $addresses[$type] as $key => $value) {
                if (is_string($key)) {
                    $to[] = array(
                        'email' => $key,
                        'name' => $value,
                        'type' => $type,
                    );
                } else {
                    $to[] = array(
                        'email' => $value,
                        'type' => $type,
                    );
                }
            }
        }

        return $to;
    }
}
