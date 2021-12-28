<?php

declare(strict_types=1);

namespace EventSauce\ObjectHydrator;

use EventSauce\ObjectHydrator\Fixtures\ClassWithComplexTypeThatIsMapped;
use PHPUnit\Framework\TestCase;

use function file_put_contents;
use function is_file;
use function unlink;
use function unserialize;
use function var_dump;

class DefinitionDumperTest extends TestCase
{
    /**
     * @before
     * @after
     */
    public function removeTestFile(): void
    {
        is_file(__DIR__.'/test.php') && unlink(__DIR__.'/test.php');
    }

    /**
     * @test
     */
    public function dumping_a_definition(): void
    {
        $dumper = new DefinitionDumper();

        $dumpedDefinition = $dumper->dump([ClassWithComplexTypeThatIsMapped::class], $className = 'Dummy\\DummyDefinitionProvider');
        file_put_contents(__DIR__.'/test.php', $dumpedDefinition);
        include_once __DIR__.'/test.php';
        /** @var DefinitionProvider $provider */
        $provider = new $className();
        $definition = $provider->provideDefinition(ClassWithComplexTypeThatIsMapped::class);

        self::assertInstanceOf(ClassDefinition::class, $definition);
    }
}