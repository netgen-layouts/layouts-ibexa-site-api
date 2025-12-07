<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Tests\Parameters\ValueObjectProvider;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Layouts\Error\ErrorHandlerInterface;
use Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\ContentProvider;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\Content;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\ContentInfo;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContentProvider::class)]
final class ContentProviderTest extends TestCase
{
    private Stub&Repository $repositoryStub;

    private Stub&LoadService $loadServiceStub;

    private ValueObjectProviderInterface $valueObjectProvider;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(Repository::class);
        $this->loadServiceStub = self::createStub(LoadService::class);

        $this->repositoryStub
            ->method('sudo')
            ->with(self::anything())
            ->willReturnCallback(
                fn (callable $callback): mixed => $callback($this->repositoryStub),
            );

        $this->valueObjectProvider = new ContentProvider(
            $this->repositoryStub,
            $this->loadServiceStub,
            self::createStub(ErrorHandlerInterface::class),
        );
    }

    public function testGetValueObject(): void
    {
        $content = new Content(['contentInfo' => new ContentInfo(['mainLocationId' => 24])]);

        $this->loadServiceStub
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willReturn($content);

        self::assertSame($content, $this->valueObjectProvider->getValueObject(42));
    }

    public function testGetValueObjectWithNullValue(): void
    {
        self::assertNull($this->valueObjectProvider->getValueObject(null));
    }

    public function testGetValueObjectWithNonExistentLocation(): void
    {
        $this->loadServiceStub
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willThrowException(new NotFoundException('content', 42));

        self::assertNull($this->valueObjectProvider->getValueObject(42));
    }

    public function testGetValueObjectWithNoMainLocation(): void
    {
        $content = new Content(['contentInfo' => new ContentInfo(['mainLocationId' => null])]);

        $this->loadServiceStub
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willReturn($content);

        self::assertNull($this->valueObjectProvider->getValueObject(42));
    }
}
