<?php

namespace FactoryGirl\Tests\Provider\Doctrine\Fixtures;

use FactoryGirl\Provider\Doctrine\FieldDef;
use FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestEntity;

class ReferencesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory->defineEntity('SpaceShip', [
            'crew' => FieldDef::references('Person')
        ]);

        $this->factory->defineEntity('Person', [
            'name' => 'Eve',
        ]);
    }

    /**
     * @test
     */
    public function referencedObjectsShouldBeCreatedAutomatically()
    {
        /** @var TestEntity\SpaceShip $spaceShip */
        $spaceShip = $this->factory->get('SpaceShip');

        $crew = $spaceShip->getCrew();

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $crew);
        $this->assertContainsOnly('FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestEntity\Person', $crew);
        $this->assertCount(1, $crew);
    }

    /**
     * @test
     */
    public function referencedObjectsShouldBeOverrideable()
    {
        $count = 5;

        /** @var TestEntity\SpaceShip $spaceShip */
        $spaceShip = $this->factory->get('SpaceShip', [
            'crew' => $this->factory->getList('Person', [], $count),
        ]);

        $crew = $spaceShip->getCrew();

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $crew);
        $this->assertContainsOnly('FactoryGirl\Tests\Provider\Doctrine\Fixtures\TestEntity\Person', $crew);
        $this->assertCount($count, $crew);
    }

    /**
     * @test
     */
    public function referencedObjectsShouldBeNullable()
    {
        /** @var TestEntity\SpaceShip $spaceShip */
        $spaceShip = $this->factory->get('SpaceShip', [
            'crew' => null,
        ]);

        $crew = $spaceShip->getCrew();

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $crew);
        $this->assertEmpty($crew);
    }

    /**
     * @test
     */
    public function referencedObjectsCanBeSingletons()
    {
        /** @var TestEntity\Person $person*/
        $person = $this->factory->getAsSingleton('Person');

        /** @var TestEntity\SpaceShip $spaceShip */
        $spaceShip = $this->factory->get('SpaceShip');

        $crew = $spaceShip->getCrew();

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $crew);
        $this->assertContains($person, $crew);
        $this->assertCount(1, $crew);
    }
}
