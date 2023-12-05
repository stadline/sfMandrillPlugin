<?php

/**
 * Description of MandrillMailer
 *
 * @author alexandre
 */
class MandrillMailer
{
    /**
     * @var Mandrill $mandrill
     */
    private $mandrill;
    private $strategy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mandrill = new Mandrill(sfConfig::get('app_mandrill_api_key'));

        $this->strategy = new MandrillStrategyDefault();

        if (sfContext::hasInstance()) {
            $deliveryStrategy = sfContext::getInstance()->getMailer()->getDeliveryStrategy();
            $deliveryAddress = sfContext::getInstance()->getMailer()->getDeliveryAddress();

            if ($deliveryStrategy == sfMailer::SINGLE_ADDRESS) {
                $this->strategy = new MandrillStrategySingleAddress($deliveryAddress);
            }
        }
    }

    /**
     * Send Mandrill messages using provided template collection
     *
     * @param MandrillTemplateCollectionInterface $collection
     * @return array $results
     */
    public function sendTemplates(MandrillTemplateCollectionInterface $collection)
    {
        $results = array();

        foreach ($collection->getTemplates() as $template) {
            $results[] = $this->sendTemplate($template);
        }

        return $results;
    }

    /**
     * Send a new transactional message through Mandrill using a template
     *
     * @param MandrillTemplateInterface $template
     * @return array $result
     */
    public function sendTemplate(MandrillTemplateInterface $template)
    {
        $template_name = $template->getName();
        $message = array(
            'subject' => $template->getSubject(),
            'from_email' => sfConfig::get('app_mandrill_from_email'),
            'from_name' => sfConfig::get('app_mandrill_from_name'),
            'to' => $this->strategy->convertAddresses($template->getAddresses()),
            'global_merge_vars' => $this->convertVars($template->getVars()),
        );

        if ($replyTo = sfConfig::get('app_mandrill_reply_to')) {
            $message['headers']['Reply-To'] = $replyTo;
        }

        // add attachments
        if ($template instanceof MandrillAttachmentCollectionInterface) {
            $message['attachments'] = $template->getAttachments();
        }

        $async = false;
        $ip_pool = $this->getIpPool($template);

        return $this->mandrill->messages->sendTemplate($template_name, array(), $message, $async, $ip_pool);
    }

    /**
     * Send a new transactional message through Mandrill
     *
     * @param MandrillMessageInterface $template
     * @return array $result
     */
    public function send(MandrillMessageInterface $template)
    {
        $message = array(
            'subject' => $template->getSubject(),
            'from_email' => sfConfig::get('app_mandrill_from_email'),
            'from_name' => sfConfig::get('app_mandrill_from_name'),
            'to' => $this->strategy->convertAddresses($template->getAddresses()),
        );

        if ($replyTo = sfConfig::get('app_mandrill_reply_to')) {
            $message['headers']['Reply-To'] = $replyTo;
        }

        // add attachments
        if ($template instanceof MandrillAttachmentCollectionInterface) {
            $message['attachments'] = $template->getAttachments();
        }

        // set html content
        if ($html = $template->getHtmlContent()) {
            $message['html'] = $html;
        } else {
            $message['auto_html'] = true;
        }

        // set text content
        if ($text = $template->getTextContent()) {
            $message['text'] = $text;
        } else {
            $message['auto_text'] = true;
        }

        $async = false;
        $ip_pool = $this->getIpPool($template);

        return $this->mandrill->messages->send($message, $async, $ip_pool);
    }

    /**
     * Convert template vars to Mandrill "global_merge_vars" format
     *
     * @param array $vars
     * @return array $globalMergeVars
     */
    private function convertVars(array $vars)
    {
        $globalMergeVars = array();

        foreach ($vars as $key => $value) {
            $globalMergeVars[] = array(
                'name' => $key,
                'content' => $value,
            );
        }

        return $globalMergeVars;
    }

    /**
     * Returns a unique IP Pool
     *
     * @param object|mixed $source
     * @return string
     */
    private function getIpPool($source)
    {
        $key = substr(md5(__DIR__), 0, 7);

        if (is_object($source)) {
            return get_class($source) . ' Pool (' . $key . ')';
        } else {
            return ((string) $source) . ' Pool (' . $key . ')';
        }
    }
}
