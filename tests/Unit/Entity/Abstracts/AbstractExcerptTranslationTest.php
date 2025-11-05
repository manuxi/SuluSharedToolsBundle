<?php

namespace Manuxi\SuluSharedToolsBundle\Tests\Unit\Entity\Abstracts;

use Doctrine\Common\Collections\Collection;
use Manuxi\SuluSharedToolsBundle\Entity\Abstracts\Entity\AbstractExcerptTranslation;
use Sulu\Bundle\CategoryBundle\Entity\Category;
use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Bundle\TagBundle\Entity\Tag;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class AbstractExcerptTranslationTest extends SuluTestCase
{
    private $mock;
    private $collection;

    protected function setUp(): void
    {
        $this->mock = $this->getMockForAbstractClass(AbstractExcerptTranslation::class);
    }

    public function testGetId(): void
    {
        $this->assertNull($this->mock->getId());
    }

    public function testLocale(): void
    {
        $this->assertSame('en', $this->mock->getLocale());
        $this->assertSame($this->mock, $this->mock->setLocale('de'));
        $this->assertSame('de', $this->mock->getLocale());
    }

    public function testTitle(): void
    {
        $title = 'A title';
        $this->assertNull($this->mock->getTitle());
        $this->assertSame($this->mock, $this->mock->setTitle($title));
        $this->assertSame($title, $this->mock->getTitle());
        $this->assertSame($this->mock, $this->mock->setTitle(null));
        $this->assertNull($this->mock->getTitle());
    }

    public function testMore(): void
    {
        $more = 'more...';
        $this->assertNull($this->mock->getMore());
        $this->assertSame($this->mock, $this->mock->setMore($more));
        $this->assertSame($more, $this->mock->getMore());
        $this->assertSame($this->mock, $this->mock->setMore(null));
        $this->assertNull($this->mock->getMore());
    }

    public function testDescription(): void
    {
        $description = 'This is a description...';
        $this->assertNull($this->mock->getDescription());
        $this->assertSame($this->mock, $this->mock->setDescription($description));
        $this->assertSame($description, $this->mock->getDescription());
        $this->assertSame($this->mock, $this->mock->setDescription(null));
        $this->assertNull($this->mock->getDescription());
    }

    public function testCategories(): void
    {
        $this->assertNull($this->mock->getCategories());

        $categoryA = $this->prophesize(Category::class);
        $categoryA->getId()->willReturn(42);
        $categoryB = $this->prophesize(Category::class);
        $categoryB->getId()->willReturn(43);

        $this->assertSame($this->mock, $this->mock->addCategory($categoryA->reveal()));
        $this->assertSame(1, $this->mock->getCategories()->count());
        $this->assertSame($categoryA->reveal(), $this->mock->getCategories()->first());
        $this->assertSame($this->mock, $this->mock->addCategory($categoryB->reveal()));
        $this->assertSame(2, $this->mock->getCategories()->count());
        $this->assertSame($categoryB->reveal(), $this->mock->getCategories()->last());

        $this->assertInstanceOf(Collection::class, $this->mock->getCategories());

        $ids = $this->mock->getCategoryIds();
        $this->assertIsArray($ids);
        $this->assertContains(42, $ids);
        $this->assertContains(43, $ids);

        $this->assertSame($this->mock, $this->mock->removeCategory($categoryB->reveal()));
        $this->assertSame(1, $this->mock->getCategories()->count());

        $this->assertSame($this->mock, $this->mock->addCategory($categoryB->reveal()));
        $this->assertSame(2, $this->mock->getCategories()->count());
        $this->assertSame($this->mock, $this->mock->removeCategories());
        $this->assertSame(0, $this->mock->getCategories()->count());

        $this->assertTrue([] === $this->mock->getCategoryIds());
    }

    public function testGetCategoryNamesReturnsEmptyArrayWhenNoCategoriesSet(): void
    {
        $this->assertSame([], $this->mock->getCategoryNames());
    }

    public function testGetCategoryNamesReturnsCorrectNamesWithIds(): void
    {
        $translationA = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translationA->getTranslation()->willReturn('Technology');

        $categoryA = $this->prophesize(Category::class);
        $categoryA->getId()->willReturn(42);
        $categoryA->findTranslationByLocale('en')->willReturn($translationA->reveal());

        $translationB = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translationB->getTranslation()->willReturn('News');

        $categoryB = $this->prophesize(Category::class);
        $categoryB->getId()->willReturn(43);
        $categoryB->findTranslationByLocale('en')->willReturn($translationB->reveal());

        $this->mock->addCategory($categoryA->reveal());
        $this->mock->addCategory($categoryB->reveal());

        $names = $this->mock->getCategoryNames();

        $this->assertIsArray($names);
        $this->assertCount(2, $names);
        $this->assertArrayHasKey(42, $names);
        $this->assertArrayHasKey(43, $names);
        $this->assertSame('Technology', $names[42]);
        $this->assertSame('News', $names[43]);
    }

    public function testGetCategoryNamesUsesCorrectLocale(): void
    {
        $translationDe = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translationDe->getTranslation()->willReturn('Technologie');

        $categoryA = $this->prophesize(Category::class);
        $categoryA->getId()->willReturn(42);
        $categoryA->findTranslationByLocale('de')->willReturn($translationDe->reveal());

        $this->mock->setLocale('de');
        $this->mock->addCategory($categoryA->reveal());

        $names = $this->mock->getCategoryNames();

        $this->assertSame('Technologie', $names[42]);
    }

    public function testGetCategoryNamesWithMultipleLocales(): void
    {
        // Test mit verschiedenen Locales
        $locales = ['en', 'de', 'fr'];
        $expectedNames = [
            'en' => 'Technology',
            'de' => 'Technologie',
            'fr' => 'Technologie'
        ];

        foreach ($locales as $locale) {
            $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
            $translation->getTranslation()->willReturn($expectedNames[$locale]);

            $category = $this->prophesize(Category::class);
            $category->getId()->willReturn(100);
            $category->findTranslationByLocale($locale)->willReturn($translation->reveal());

            // Fresh mock für jede Iteration
            $mock = $this->getMockForAbstractClass(AbstractExcerptTranslation::class);
            $mock->setLocale($locale);
            $mock->addCategory($category->reveal());

            $names = $mock->getCategoryNames();

            $this->assertSame($expectedNames[$locale], $names[100], "Failed for locale: $locale");
        }
    }

    public function testGetCategoryNamesPreservesIdAsKey(): void
    {
        $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translation->getTranslation()->willReturn('Some Category');

        $category = $this->prophesize(Category::class);
        $category->getId()->willReturn(999);
        $category->findTranslationByLocale('en')->willReturn($translation->reveal());

        $this->mock->addCategory($category->reveal());

        $names = $this->mock->getCategoryNames();

        // Key muss die ID sein, nicht ein numerischer Index
        $this->assertTrue(array_key_exists(999, $names));
        $this->assertFalse(array_key_exists(0, $names));
    }

    public function testGetCategoryNamesHandlesSpecialCharacters(): void
    {
        $testString = 'Umläütè & Söndérzeichen™ 😊😁';

        $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translation->getTranslation()->willReturn($testString);

        $category = $this->prophesize(Category::class);
        $category->getId()->willReturn(42);
        $category->findTranslationByLocale('de')->willReturn($translation->reveal());

        $this->mock->setLocale('de');
        $this->mock->addCategory($category->reveal());

        $names = $this->mock->getCategoryNames();

        $this->assertSame($testString, $names[42]);
    }

    public function testGetCategoryNamesAfterRemovingCategories(): void
    {
        $translationA = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translationA->getTranslation()->willReturn('Category A');

        $categoryA = $this->prophesize(Category::class);
        $categoryA->getId()->willReturn(1);
        $categoryA->findTranslationByLocale('en')->willReturn($translationA->reveal());

        $translationB = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translationB->getTranslation()->willReturn('Category B');

        $categoryB = $this->prophesize(Category::class);
        $categoryB->getId()->willReturn(2);
        $categoryB->findTranslationByLocale('en')->willReturn($translationB->reveal());

        $this->mock->addCategory($categoryA->reveal());
        $this->mock->addCategory($categoryB->reveal());

        $this->assertCount(2, $this->mock->getCategoryNames());

        $this->mock->removeCategory($categoryB->reveal());

        $names = $this->mock->getCategoryNames();
        $this->assertCount(1, $names);
        $this->assertArrayHasKey(1, $names);
        $this->assertArrayNotHasKey(2, $names);
        $this->assertSame('Category A', $names[1]);
    }

    public function testGetCategoryNamesAfterRemovingAllCategories(): void
    {
        $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translation->getTranslation()->willReturn('Category');

        $category = $this->prophesize(Category::class);
        $category->getId()->willReturn(1);
        $category->findTranslationByLocale('en')->willReturn($translation->reveal());

        $this->mock->addCategory($category->reveal());
        $this->assertCount(1, $this->mock->getCategoryNames());

        $this->mock->removeCategories();

        $this->assertSame([], $this->mock->getCategoryNames());
    }

    public function testGetCategoryNamesWithEmptyTranslation(): void
    {
        $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
        $translation->getTranslation()->willReturn('');

        $category = $this->prophesize(Category::class);
        $category->getId()->willReturn(42);
        $category->findTranslationByLocale('en')->willReturn($translation->reveal());

        $this->mock->addCategory($category->reveal());

        $names = $this->mock->getCategoryNames();

        $this->assertSame('', $names[42]);
    }

    public function testGetCategoryNamesPerformanceWithManyCategories(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $translation = $this->prophesize(\Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation::class);
            $translation->getTranslation()->willReturn("Category $i");

            $category = $this->prophesize(Category::class);
            $category->getId()->willReturn($i);
            $category->findTranslationByLocale('en')->willReturn($translation->reveal());

            $this->mock->addCategory($category->reveal());
        }

        $startTime = microtime(true);
        $names = $this->mock->getCategoryNames();
        $endTime = microtime(true);

        $this->assertCount(50, $names);
        $this->assertLessThan(0.1, $endTime - $startTime, 'Method should execute quickly even with 50 categories');
    }

    public function testTags(): void
    {
        $this->assertNull($this->mock->getTags());

        $tagA = $this->prophesize(Tag::class);
        $tagA->getName()->willReturn('TAG42');
        $tagB = $this->prophesize(Tag::class);
        $tagB->getName()->willReturn('Hasta la vista, baby!');

        $this->assertSame($this->mock, $this->mock->addTag($tagA->reveal()));
        $this->assertSame(1, $this->mock->getTags()->count());
        $this->assertSame($tagA->reveal(), $this->mock->getTags()->first());
        $this->assertSame($this->mock, $this->mock->addTag($tagB->reveal()));
        $this->assertSame(2, $this->mock->getTags()->count());
        $this->assertSame($tagB->reveal(), $this->mock->getTags()->last());

        $this->assertInstanceOf(Collection::class, $this->mock->getTags());

        $tagNames = $this->mock->getTagNames();
        $this->assertIsArray($tagNames);
        $this->assertTrue(\in_array('TAG42', $tagNames));
        $this->assertTrue(\in_array('Hasta la vista, baby!', $tagNames));

        $this->assertSame($this->mock, $this->mock->removeTag($tagB->reveal()));
        $this->assertSame(1, $this->mock->getTags()->count());

        $this->assertSame($this->mock, $this->mock->addTag($tagB->reveal()));
        $this->assertSame(2, $this->mock->getTags()->count());
        $this->assertSame($this->mock, $this->mock->removeTags());
        $this->assertSame(0, $this->mock->getTags()->count());

        $this->assertTrue([] === $this->mock->getTagNames());
    }

    public function testIcons(): void
    {
        $this->assertNull($this->mock->getIcons());

        $mediaA = $this->prophesize(Media::class);
        $mediaA->getId()->willReturn(112);
        $mediaB = $this->prophesize(Media::class);
        $mediaB->getId()->willReturn(117);

        $this->assertSame($this->mock, $this->mock->addIcon($mediaA->reveal()));
        $this->assertSame(1, $this->mock->getIcons()->count());
        $this->assertSame($mediaA->reveal(), $this->mock->getIcons()->first());
        $this->assertSame($this->mock, $this->mock->addIcon($mediaB->reveal()));
        $this->assertSame(2, $this->mock->getIcons()->count());
        $this->assertSame($mediaB->reveal(), $this->mock->getIcons()->last());

        $this->assertInstanceOf(Collection::class, $this->mock->getIcons());

        $iconIds = $this->mock->getIconIds();
        $this->assertIsArray($iconIds);
        $this->assertArrayHasKey('ids', $iconIds);
        $this->assertTrue(\in_array(112, $iconIds['ids']));
        $this->assertTrue(\in_array(117, $iconIds['ids']));

        $this->assertSame($this->mock, $this->mock->removeIcon($mediaB->reveal()));
        $this->assertSame(1, $this->mock->getIcons()->count());

        $this->assertSame($this->mock, $this->mock->addIcon($mediaB->reveal()));
        $this->assertSame(2, $this->mock->getIcons()->count());
        $this->assertSame($this->mock, $this->mock->removeIcons());
        $this->assertSame(0, $this->mock->getIcons()->count());

        $this->assertTrue([] === $this->mock->getIconIds()['ids']);
    }

    public function testImages(): void
    {
        $this->assertNull($this->mock->getImages());

        $mediaA = $this->prophesize(Media::class);
        $mediaA->getId()->willReturn(112);
        $mediaB = $this->prophesize(Media::class);
        $mediaB->getId()->willReturn(117);

        $this->assertSame($this->mock, $this->mock->addImage($mediaA->reveal()));
        $this->assertSame(1, $this->mock->getImages()->count());
        $this->assertSame($mediaA->reveal(), $this->mock->getImages()->first());
        $this->assertSame($this->mock, $this->mock->addImage($mediaB->reveal()));
        $this->assertSame(2, $this->mock->getImages()->count());
        $this->assertSame($mediaB->reveal(), $this->mock->getImages()->last());

        $this->assertInstanceOf(Collection::class, $this->mock->getImages());

        $iconIds = $this->mock->getImageIds();
        $this->assertIsArray($iconIds);
        $this->assertArrayHasKey('ids', $iconIds);
        $this->assertTrue(\in_array(112, $iconIds['ids']));
        $this->assertTrue(\in_array(117, $iconIds['ids']));

        $this->assertSame($this->mock, $this->mock->removeImage($mediaB->reveal()));
        $this->assertSame(1, $this->mock->getImages()->count());

        $this->assertSame($this->mock, $this->mock->addImage($mediaB->reveal()));
        $this->assertSame(2, $this->mock->getImages()->count());
        $this->assertSame($this->mock, $this->mock->removeImages());
        $this->assertSame(0, $this->mock->getImages()->count());

        $this->assertTrue([] === $this->mock->getImageIds()['ids']);
    }
}
