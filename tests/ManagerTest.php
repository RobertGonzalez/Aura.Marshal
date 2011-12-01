<?php
namespace Aura\Marshal;
use Aura\Marshal\Relation\Builder as RelationBuilder;
use Aura\Marshal\Type\Builder as TypeBuilder;

/**
 * Test class for Manager.
 * Generated by PHPUnit on 2011-11-21 at 11:28:20.
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $relation_builder = new RelationBuilder;
        $type_builder = new TypeBuilder;
        $types = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_types.php';
        $this->manager = new Manager($type_builder, $relation_builder);
        foreach ($types as $name => $info) {
            $this->manager->setType($name, $info);
        }
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function test__get()
    {
        $actual = $this->manager->posts;
        $expect = 'Aura\Marshal\Type\GenericType';
        $this->assertInstanceOf($expect, $actual);
    }
    
    public function test__getNoSuchType()
    {
        $this->setExpectedException('Aura\Marshal\Exception');
        $actual = $this->manager->no_such_type;
    }
    
    public function testGetTypes()
    {
        $actual = $this->manager->getTypes();
        $expect = array(
            'authors',
            'posts',
            'metas',
            'comments',
            'posts_tags',
            'tags',
        );
        
        $this->assertSame($expect, $actual);
    }
    
    public function testSetType_alreadySet()
    {
        $this->setExpectedException('Aura\Marshal\Exception');
        $this->manager->setType('authors', array());
    }
}
