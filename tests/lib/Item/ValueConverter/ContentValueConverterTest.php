<?php

declare(strict_types=1);

namespace Netgen\Layouts\Ibexa\SiteApi\Tests\Item\ValueConverter;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo as IbexaContentInfo;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\Content;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\ContentInfo;
use Netgen\Layouts\Ibexa\SiteApi\Tests\Stubs\Location;
use Netgen\Layouts\Item\ValueConverterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ContentValueConverterTest extends TestCase
{
    private MockObject $innerConverterMock;

    private MockObject $loadServiceMock;

    private ContentValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->innerConverterMock = $this->createMock(ValueConverterInterface::class);
        $this->loadServiceMock = $this->createMock(LoadService::class);

        $this->valueConverter = new ContentValueConverter(
            $this->innerConverterMock,
            $this->loadServiceMock,
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::__construct
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::supports
     */
    public function testSupports(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('supports');

        self::assertTrue($this->valueConverter->supports(new ContentInfo()));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::supports
     */
    public function testSupportsWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new IbexaContentInfo();

        $this->innerConverterMock
            ->expects(self::once())
            ->method('supports')
            ->with(self::identicalTo($contentInfo))
            ->willReturn(true);

        self::assertTrue($this->valueConverter->supports($contentInfo));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getValueType
     */
    public function testGetValueType(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getValueType');

        self::assertSame(
            'ibexa_content',
            $this->valueConverter->getValueType(
                new ContentInfo(),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getId
     */
    public function testGetId(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getId');

        self::assertSame(
            24,
            $this->valueConverter->getId(
                new ContentInfo(['id' => 24]),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getId
     */
    public function testGetIdWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new IbexaContentInfo();

        $this->innerConverterMock
            ->expects(self::once())
            ->method('getId')
            ->with(self::identicalTo($contentInfo))
            ->willReturn(42);

        self::assertSame(42, $this->valueConverter->getId($contentInfo));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getRemoteId
     */
    public function testGetRemoteId(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getRemoteId');

        self::assertSame(
            'abc',
            $this->valueConverter->getRemoteId(
                new ContentInfo(['remoteId' => 'abc']),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getRemoteId
     */
    public function testGetRemoteIdWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new IbexaContentInfo();

        $this->innerConverterMock
            ->expects(self::once())
            ->method('getRemoteId')
            ->with(self::identicalTo($contentInfo))
            ->willReturn('abc');

        self::assertSame('abc', $this->valueConverter->getRemoteId($contentInfo));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getName
     */
    public function testGetName(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getName');

        self::assertSame(
            'Cool name',
            $this->valueConverter->getName(
                new ContentInfo(['name' => 'Cool name']),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getName
     */
    public function testGetNameWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new IbexaContentInfo();

        $this->innerConverterMock
            ->expects(self::once())
            ->method('getName')
            ->with(self::identicalTo($contentInfo))
            ->willReturn('Cool name');

        self::assertSame('Cool name', $this->valueConverter->getName($contentInfo));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getIsVisible
     */
    public function testGetIsVisible(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getIsVisible');

        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new ContentInfo(['mainLocation' => new Location(['invisible' => false])]),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getIsVisible
     */
    public function testGetIsVisibleWithoutMainLocation(): void
    {
        $this->innerConverterMock
            ->expects(self::never())
            ->method('getIsVisible');

        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new ContentInfo(['mainLocation' => new Location(['invisible' => true])]),
            ),
        );
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getIsVisible
     */
    public function testGetIsVisibleWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new IbexaContentInfo();

        $this->innerConverterMock
            ->expects(self::once())
            ->method('getIsVisible')
            ->with(self::identicalTo($contentInfo))
            ->willReturn(true);

        self::assertTrue($this->valueConverter->getIsVisible($contentInfo));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getObject
     */
    public function testGetObject(): void
    {
        $this->loadServiceMock
            ->expects(self::never())
            ->method('loadContent');

        $object = new ContentInfo(['id' => 42]);

        self::assertSame($object, $this->valueConverter->getObject($object));
    }

    /**
     * @covers \Netgen\Layouts\Ibexa\SiteApi\Item\ValueConverter\ContentValueConverter::getObject
     */
    public function testGetObjectWithoutSiteApiContentInfo(): void
    {
        $contentInfo = new ContentInfo();
        $content = new Content(['contentInfo' => $contentInfo]);

        $this->loadServiceMock
            ->expects(self::once())
            ->method('loadContent')
            ->with(self::identicalTo(42))
            ->willReturn($content);

        self::assertSame($contentInfo, $this->valueConverter->getObject(new IbexaContentInfo(['id' => 42])));
    }
}
