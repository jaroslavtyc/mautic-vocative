<?php
namespace MauticPlugin\MauticVocativeBundle\Tests\Integration;

use Mautic\CoreBundle\Factory\MauticFactory;
use MauticPlugin\MauticVocativeBundle\Integration\FOMVocativeIntegration;
use MauticPlugin\MauticVocativeBundle\Tests\FOMTestWithMockery;
use Mockery\MockInterface;

class FOMVocativeIntegrationTest extends FOMTestWithMockery
{

    /**
     * @test
     */
    public function I_can_integrate_it_by_proper_name()
    {
        $integration = new FOMVocativeIntegration($this->createFactory());
        self::assertSame(
            $this->parseExpectedName(static::class),
            $integration->getName()
        );
    }

    /**
     * @return MauticFactory|MockInterface
     */
    private function createFactory(): MauticFactory
    {
        return $this->mockery(MauticFactory::class);
    }

    private function parseExpectedName($testClassName)
    {
        self::assertEquals(1, preg_match('~(?<name>\w+)IntegrationTest$~', $testClassName, $matches));

        return $matches['name'];
    }

    /**
     * @test
     */
    public function I_do_not_need_to_authenticate_to_use_it()
    {
        $integration = new FOMVocativeIntegration($this->createFactory());
        self::assertSame(
            'none',
            $integration->getAuthenticationType()
        );
    }
}