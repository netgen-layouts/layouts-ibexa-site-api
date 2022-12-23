<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Tests\Parameters\ValueObjectProvider;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\Content;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\ContentInfo;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ContentProviderTest extends TestCase
{
    private MockObject $repositoryMock;

    private MockObject $loadServiceMock;

    private ValueObjectProviderInterface $valueObjectProvider;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(Repository::class);
        $this->loadServiceMock = $this->createMock(LoadService::class);

        $this->repositoryMock
            ->expects(self::any())
            ->method('sudo')
            ->with(self::anything())
            ->willReturnCallback(
                fn (callable $callback) => $callback($this->repositoryMock),
            );

        $this->valueObjectProvider = new ContentProvider(
            $this->repositoryMock,
            $this->loadServiceMock,
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider::__construct
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider::getValueObject
     */
    public function testGetValueObject(): void
    {
        $content = new Content(['contentInfo' => new ContentInfo(['mainLocationId' => 24])]);

        $this->loadServiceMock
            ->expects(self::any())
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willReturn($content);

        self::assertSame($content, $this->valueObjectProvider->getValueObject(42));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider::getValueObject
     */
    public function testGetValueObjectWithNonExistentLocation(): void
    {
        $this->loadServiceMock
            ->expects(self::any())
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willThrowException(new NotFoundException('content', 42));

        self::assertNull($this->valueObjectProvider->getValueObject(42));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider::getValueObject
     */
    public function testGetValueObjectWithNoMainLocation(): void
    {
        $content = new Content(['contentInfo' => new ContentInfo(['mainLocationId' => null])]);

        $this->loadServiceMock
            ->expects(self::any())
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willReturn($content);

        self::assertNull($this->valueObjectProvider->getValueObject(42));
    }
}