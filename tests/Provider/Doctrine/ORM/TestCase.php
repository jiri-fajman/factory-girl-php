<?php

namespace FactoryGirl\Tests\Provider\Doctrine\ORM;

use PHPUnit\Framework;
use FactoryGirl\Tests\Provider\Doctrine\TestDb;

/**
 * @category   FactoryGirl
 * @package    Doctrine
 * @subpackage ORM
 * @author     Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause New BSD License
 */
abstract class TestCase extends Framework\TestCase
{
    /**
     * @var TestDb
     */
    protected $testDb;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();

        $here = dirname(__FILE__);

        $this->testDb = new TestDb(
            $here . '/TestEntity',
            $here . '/TestProxy',
            'FactoryGirl\Tests\Provider\Doctrine\ORM\TestProxy'
        );

        $this->em = $this->testDb->createEntityManager();
    }
}
