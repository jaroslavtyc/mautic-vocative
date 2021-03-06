<?php
namespace MauticPlugin\MauticVocativeBundle\EventListener;

use Mautic\CoreBundle\Event\TokenReplacementEvent;
use Mautic\DynamicContentBundle\DynamicContentEvents;
use MauticPlugin\MauticVocativeBundle\Service\NameToVocativeConverter;
use MauticPlugin\MauticVocativeBundle\Tests\FOMTestWithMockery;
use Mockery\MockInterface;

class DynamicContentSubscriberTest extends FOMTestWithMockery
{
    /**
     * @test
     */
    public static function I_can_get_subscribed_events()
    {
        self::assertSame(
            [DynamicContentEvents::TOKEN_REPLACEMENT => ['onTokenReplacement', -10]],
            DynamicContentSubscriber::getSubscribedEvents()
        );
        $reflectionClass = new \ReflectionClass(DynamicContentSubscriber::class);
        self::assertTrue(
            $reflectionClass->hasMethod('onTokenReplacement'),
            'Used callback method does not exists: ' . DynamicContentSubscriber::class . '::onTokenReplacement'
        );
        $onTokenReplacement = $reflectionClass->getMethod('onTokenReplacement');
        self::assertTrue(
            $onTokenReplacement->isPublic(),
            'Used callback method is not public: ' . DynamicContentSubscriber::class . '::onTokenReplacement'
        );
    }

    /**
     * @test
     */
    public function I_can_use_callback()
    {
        $tokenToReplace = 'bar';
        $initialContent = "foo $tokenToReplace baz";
        $replacedToken = 'qux';
        $replacedContent = "foo $replacedToken baz";
        $converter = $this->createConverter($initialContent, [$tokenToReplace => $replacedToken]);
        $dynamicContentSubscriber = new DynamicContentSubscriber($converter);
        $tokenReplacementEvent = $this->mockery(TokenReplacementEvent::class);
        $tokenReplacementEvent->shouldReceive('getContent')
            ->once()
            ->withNoArgs()
            ->andReturn($initialContent);
        $tokenReplacementEvent->shouldReceive('setContent')
            ->once()
            ->andReturnUsing(function (string $contentToSet) use ($replacedContent) {
                self::assertSame($replacedContent, $contentToSet, 'Expected different replaced content');
                // this does not return anything, its just tests set value
            });
        /** @var TokenReplacementEvent|MockInterface $tokenReplacementEvent */
        $dynamicContentSubscriber->onTokenReplacement($tokenReplacementEvent);
    }

    /**
     * @param string $initialContent
     * @param array $tokensToReplace
     * @return NameToVocativeConverter|MockInterface
     */
    private function createConverter(string $initialContent, array $tokensToReplace): NameToVocativeConverter
    {
        $converter = $this->mockery(NameToVocativeConverter::class);
        $converter->shouldReceive('findAndReplace')
            ->once()
            ->with($initialContent)
            ->andReturn($tokensToReplace);

        return $converter;
    }
}