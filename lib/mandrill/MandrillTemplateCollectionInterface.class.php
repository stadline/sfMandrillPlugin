<?php

interface MandrillTemplateCollectionInterface
{
    /**
     * Returns a collection of templates
     *
     * @return MandrillTemplateInterface[]
     */
    public function getTemplates();
}
