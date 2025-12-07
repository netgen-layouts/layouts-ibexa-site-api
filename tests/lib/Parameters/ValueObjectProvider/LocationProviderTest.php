<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Tests\Parameters\ValueObjectProvider;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Layouts\Error\ErrorHandlerInterface;
use Netgen\Layouts\Ibexa\SiteApi\Parameters\ValueObjectProvider\LocationProvider;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\Location;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocationProvider::class)]
final class LocationProviderTest extends TestCase
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

        $this->valueObjectProvider = new LocationProvider(
            $this->repositoryStub,
            $this->loadServiceStub,
            self::createStub(ErrorHandlerInterface::class),
        );
    }

    public function testGetValueObject(): void
    {
        $location = new Location();

        $this->loadServiceStub
            ->method('loadLocation')
            ->with(self::identicalTo(42))
            ->willReturn($location);

        self::assertSame($location, $this->valueObjectProvider->getValueObject(42));
    }

    public function testGetValueObjectWithNullValue(): void
    {
        self::assertNull($this->valueObjectProvider->getValueObject(null));
    }

    public function testGetValueObjectWithNonExistentLocation(): void
    {
        $this->loadServiceStub
            ->method('loadLocation')
            ->with(self::identicalTo(42))
            ->willThrowException(new NotFoundException('location', 42));

        self::assertNull($this->valueObjectProvider->getValueObject(42));
    }
}
