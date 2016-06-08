<?php

interface MandrillMessageInterface
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
     * Returns the Mandrill html content
     *
     * @return string
     */
    public function getHtmlContent();

    /**
     * Returns the Mandrill text content
     *
     * @return string
     */
    public function getTextContent();
}
