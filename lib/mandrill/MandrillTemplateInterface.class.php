<?php

interface MandrillTemplateInterface
{
    /**
     * Returns the addresses to send to
     *
     * must be like : {
     *  to: ["alexandre.paixao@stadline.com", "fabien.rondeau@stadline.com"],
     *  bcc: ["contact@stadline.com"]
     * }
     *
     * @return string[]
     */
    public function getAddresses();

    /**
     * Returns the email subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * Returns the Mandrill template name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the GlobalVars associated to the template
     */
    public function getVars();
}
