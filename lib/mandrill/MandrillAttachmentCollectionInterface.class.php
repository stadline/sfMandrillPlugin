<?php

interface MandrillAttachmentCollectionInterface
{
    /**
     * Returns the attachents to send with
     *
     * @return array[]
     */
    public function getAttachments();
}
