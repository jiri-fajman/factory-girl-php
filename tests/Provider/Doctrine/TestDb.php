<?php

namespace FactoryGirl\Tests\Provider\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;

/**
 * @category FactoryGirl
 * @package  Doctrine
 * @author   Martin Pärtel
 * @author   Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 * @license  http://www.opensource.org/licenses/BSD-3-Clause New BSD License
 */
class TestDb
{
    /**
     * @var \Doctrine\ORM\Configuration
     */
    private $doctrineConfig;

    /**
     * @var array
     */
    private $connectionOptions;

    /**
     * @param string $annotationPath
     * @param string $proxyDir
     * @param string $proxyNamespace
     */
    public function __construct($annotationPath, $proxyDir, $proxyNamespace)
    {
        $cache = new ArrayCache();

        $config = new Configuration();
        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);
        $config->setMetadataDriverImpl(
            $config->newDefaultAnnotationDriver($annotationPath)
        );
        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace($proxyNamespace);
        $config->setAutoGenerateProxyClasses(true);

        $this->connectionOptions = [
            'driver' => 'pdo_sqlite',
            'path'   => ':memory:'
        ];

        $this->doctrineConfig = $config;
    }

    /**
     * @return EntityManager
     */
    public function createEntityManager()
    {
        $em = EntityManager::create(
            $this->connectionOptions,
            $this->doctrineConfig
        );
        $this->createSchema($em);

        return $em;
    }

    /**
     * @param EntityManager $em
     */
    private function createSchema(EntityManager $em)
    {
        $tool = new SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());
    }
}
