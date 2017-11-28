<?php
namespace MauticPlugin\MauticVocativeBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\MauticVocativeBundle\Service\NameToVocativeConverter;

class EmailNameToVocativeSubscriber extends CommonSubscriber
{

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::EMAIL_ON_SEND => ['onEmailGenerate', -999 /* lowest priority */],
            EmailEvents::EMAIL_ON_DISPLAY => ['onEmailGenerate', -999 /* lowest priority */],
        ];
    }

    /**
     * @param EmailSendEvent $event
     */
    public function onEmailGenerate(EmailSendEvent $event): void
    {
        $content = $event->getSubject()
            . $event->getContent(true /* with tokens replaced (to get names) */)
            . $event->getPlainText();
        $tokenList = $this->getConverter()->findAndReplace($content);
        if (\count($tokenList) > 0) {
            $event->addTokens($tokenList);
            unset($tokenList);
        }
    }

    /**
     * @return NameToVocativeConverter|object
     */
    private function getConverter(): NameToVocativeConverter
    {
        return $this->factory->getKernel()->getContainer()->get('plugin.vocative.name_converter');
    }

}