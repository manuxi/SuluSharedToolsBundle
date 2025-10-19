<?php

namespace Manuxi\SuluSharedToolsBundle\Tests\Unit\Entity\Traits;

use Manuxi\SuluSharedToolsBundle\Entity\Traits\RoutePathTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class RoutePathTraitTest extends SuluTestCase
{
    private $mock;

    protected function setUp(): void
    {
        $this->mock = $this->getMockForTrait(RoutePathTrait::class);
    }

    public function testRoutePath(): void
    {
        $test = '/this/is/my/new/path/';
        $this->assertEmpty($this->mock->getRoutePath());
        $this->mock->setRoutePath($test);
        $this->assertSame($this->mock->getRoutePath(), $test);
    }
}
