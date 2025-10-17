<?php

namespace Manuxi\SuluSharedToolsBundle\Tests\Unit\Entity\Traits;

use Manuxi\SuluSharedToolsBundle\Entity\Traits\DocumentTrait;
use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class DocumentTraitTest extends SuluTestCase
{
    private $mock;
    private $document;

    protected function setUp(): void
    {
        $this->document = $this->prophesize(Media::class);
        $this->mock  = $this->getMockForTrait(DocumentTrait::class);
    }

    public function testSetDocument(): void
    {
        $this->assertSame($this->mock, $this->mock->setDocument($this->document->reveal()));
    }

    public function testGetDocument(): void
    {
        $this->mock->setDocument($this->document->reveal());
        $this->assertSame($this->document->reveal(), $this->mock->getDocument());
    }

    public function testGetDocumentData(): void
    {
        $this->document->getId()->willReturn(42);
        $this->assertNull($this->mock->getDocumentData());
        $this->mock->setDocument($this->document->reveal());
        $this->assertSame(['id' => 42], $this->mock->getDocumentData());
    }
}
