<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Database;

use Cycle\Annotated;
use Cycle\Annotated\Locator\TokenizerEmbeddingLocator;
use Cycle\Annotated\Locator\TokenizerEntityLocator;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\Config\MySQL\TcpConnectionConfig;
use Cycle\Database\Config\MySQLDriverConfig;
use Cycle\Database\DatabaseManager;
use Cycle\ORM;
use Cycle\ORM\Entity\Behavior\EventDrivenCommandGenerator;
use Cycle\ORM\ORMInterface;
use Cycle\Schema;
use MicroPHP\Framework\Application;
use MicroPHP\Framework\Config\Config;
use MicroPHP\Framework\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

class CycleORM
{
    public static function init(ContainerInterface $container): void
    {
        $config = Config::get('database');
        $dbal = new DatabaseManager(
            new DatabaseConfig([
                'default' => 'default',
                'databases' => [
                    'default' => ['connection' => 'mysql'],
                ],
                'connections' => [
                    'mysql' => new MySQLDriverConfig(
                        connection: new TcpConnectionConfig(
                            database: $config['default']['database'],
                            host: $config['default']['host'],
                            port: $config['default']['port'],
                            user: $config['default']['username'],
                            password: $config['default']['password'],
                        ),
                        reconnect: true, # 运行时自动重连, 常驻内存必须设置为true
                        queryCache: true
                    ),
                ],
            ])
        );
        $finder = (new Finder())->files()->in([BASE_PATH . '/app']); // __DIR__ here is folder with entities
        $classLocator = new ClassLocator($finder);

        $embeddingLocator = new TokenizerEmbeddingLocator($classLocator);
        $entityLocator = new TokenizerEntityLocator($classLocator);

        $schema = (new Schema\Compiler())->compile(new Schema\Registry($dbal), [
            new Schema\Generator\ResetTables(),             // Reconfigure table schemas (deletes columns if necessary)
            new Annotated\Embeddings($embeddingLocator),    // Recognize embeddable entities
            new Annotated\Entities($entityLocator),         // Identify attributed entities
            new Annotated\TableInheritance(),               // Setup Single Table or Joined Table Inheritance
            new Annotated\MergeColumns(),                   // Integrate table #[Column] attributes
            new Schema\Generator\GenerateRelations(),       // Define entity relationships
            new Schema\Generator\GenerateModifiers(),       // Apply schema modifications
            new Schema\Generator\ValidateEntities(),        // Ensure entity schemas adhere to conventions
            new Schema\Generator\RenderTables(),            // Create table schemas
            new Schema\Generator\RenderRelations(),         // Establish keys and indexes for relationships
            new Schema\Generator\RenderModifiers(),         // Implement schema modifications
            new Schema\Generator\ForeignKeys(),             // Define foreign key constraints
            new Annotated\MergeIndexes(),                   // Merge table index attributes
            // new Schema\Generator\SyncTables(),              // Align table changes with the database
            new Schema\Generator\GenerateTypecast(),        // Typecast non-string columns
        ]);

        $schema = new ORM\Schema($schema);

        $commandGenerator = new EventDrivenCommandGenerator($schema, $container);
        $orm = new ORM\ORM(new ORM\Factory($dbal), $schema, commandGenerator: $commandGenerator);
        $container->add(ORMInterface::class, $orm);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function cleanHeap(): void
    {
        /** @var ORMInterface $orm */
        $orm = Application::getContainer()->get(ORMInterface::class);
        $orm->getHeap()->clean();
    }
}
