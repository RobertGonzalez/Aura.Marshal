<?php
namespace Aura\Marshal;
use Aura\Marshal\Collection\Builder as CollectionBuilder;
use Aura\Marshal\Record\Builder as RecordBuilder;
use Aura\Marshal\Record\GenericCollection;
use Aura\Marshal\Record\GenericRecord;
use Aura\Marshal\Relation\Builder as RelationBuilder;
use Aura\Marshal\Type\Builder as TypeBuilder;
use Aura\Marshal\Type\GenericType;

/**
 * Test class for Type.
 * Generated by PHPUnit on 2011-11-21 at 18:02:55.
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GenericType
     */
    protected $type;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $types = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_types.php';
        $info = $types['posts'];
        
        $this->type = new GenericType;
        $this->type->setIdentityField($info['identity_field']);
        $this->type->setRecordClass('Aura\Marshal\Record\GenericRecord');
        $this->type->setRecordBuilder(new RecordBuilder);
        $this->type->setCollectionBuilder(new CollectionBuilder);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testSetAndGetIdentityField()
    {
        $expect = 'foobar';
        $this->type->setIdentityField('foobar');
        $actual = $this->type->getIdentityField();
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetRecordBuilder()
    {
        $Builder = new RecordBuilder;
        $this->type->setRecordBuilder($Builder);
        $actual = $this->type->getRecordBuilder();
        $this->assertSame($Builder, $actual);
    }

    public function testSetAndGetCollectionBuilder()
    {
        $Builder = new CollectionBuilder;
        $this->type->setCollectionBuilder($Builder);
        $actual = $this->type->getCollectionBuilder();
        $this->assertSame($Builder, $actual);
    }

    public function testLoadAndGetStorage()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = count($data['posts']);
        $actual = count($this->type);
        $this->assertSame($expect, $actual);
    }
    
    public function testGetIdentityValues()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = array(1, 2, 3, 4, 5);
        $actual = $this->type->getIdentityValues();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetFieldValues()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        $expect = array(1 => '1', 2 => '1', 3 => '1', 4 => '2', 5 => '2');
        $actual = $this->type->getFieldValues('author_id');
        $this->assertSame($expect, $actual);
    }
    
    public function testGetRecord()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = (object) $data['posts'][2];
        $actual = $this->type->getRecord(3);
        
        $this->assertSame($expect->id, $actual->id);
        $this->assertSame($expect->author_id, $actual->author_id);
        $this->assertSame($expect->body, $actual->body);
        
        // get it again for complete code coverage
        $again = $this->type->getRecord(3);
        $this->assertSame($actual, $again);
    }
    
    public function testGetRecordByField()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = (object) $data['posts'][3];
        
        $actual = $this->type->getRecordByField('author_id', 2);
        
        $this->assertSame($expect->id, $actual->id);
        $this->assertSame($expect->author_id, $actual->author_id);
        $this->assertSame($expect->body, $actual->body);
    }
    
    public function testGetRecordByField_identity()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = (object) $data['posts'][3];
        
        $actual = $this->type->getRecordByField('id', 4);
        
        $this->assertSame($expect->id, $actual->id);
        $this->assertSame($expect->author_id, $actual->author_id);
        $this->assertSame($expect->body, $actual->body);
    }
    
    public function testGetRecordByField_none()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $expect = (object) $data['posts'][3];
        
        $actual = $this->type->getRecordByField('author_id', 'no such value');
        $this->assertNull($actual);
    }
    
    public function getCollection()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        $collection = $this->type->getCollection(array(1, 2, 3));
        
        $expect = array(
            (object) $data['posts'][0],
            (object) $data['posts'][1],
            (object) $data['posts'][2],
        );
        
        foreach ($collection as $offset => $actual) {
            $this->assertSame($expect[$offset]->id, $actual->id);
            $this->assertSame($expect[$offset]->author_id, $actual->author_id);
            $this->assertSame($expect[$offset]->body, $actual->body);
        }
    }
    
    public function testGetCollectionByField()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $collection = $this->type->getCollectionByField('author_id', 2);
        
        $expect = array(
            (object) $data['posts'][3],
            (object) $data['posts'][4],
        );
        
        foreach ($collection as $offset => $actual) {
            $this->assertSame($expect[$offset]->id, $actual->id);
            $this->assertSame($expect[$offset]->author_id, $actual->author_id);
            $this->assertSame($expect[$offset]->body, $actual->body);
        }
    }
    
    public function testGetCollectionByField_many()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $collection = $this->type->getCollectionByField('author_id', 2);
        
        $expect = array(
            (object) $data['posts'][3],
            (object) $data['posts'][4],
        );
        
        foreach ($collection as $offset => $actual) {
            $this->assertSame($expect[$offset]->id, $actual->id);
            $this->assertSame($expect[$offset]->author_id, $actual->author_id);
            $this->assertSame($expect[$offset]->body, $actual->body);
        }
    }
    
    public function testGetCollectionByField_identity()
    {
        $data = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_data.php';
        $this->type->load($data['posts']);
        
        $collection = $this->type->getCollectionByField('id', array(4, 5));
        
        $expect = array(
            (object) $data['posts'][3],
            (object) $data['posts'][4],
        );
        
        foreach ($collection as $offset => $actual) {
            $this->assertSame($expect[$offset]->id, $actual->id);
            $this->assertSame($expect[$offset]->author_id, $actual->author_id);
            $this->assertSame($expect[$offset]->body, $actual->body);
        }
    }
    
    public function testAddAndGetRelation()
    {
        $type_builder = new TypeBuilder;
        $relation_builder = new RelationBuilder;
        $types = include __DIR__ . DIRECTORY_SEPARATOR . 'fixture_types.php';
        $manager = new Manager($type_builder, $relation_builder, $types);
        
        $name = 'meta';
        $info = $types['posts']['relation_names'][$name];
        
        $relation = $relation_builder->newInstance('posts', $name, $info, $manager);
        $this->type->setRelation($name, $relation);
        
        $actual = $this->type->getRelation('meta');
        $this->assertSame($relation, $actual);
        
        // try again again, should fail
        $this->setExpectedException('Aura\Marshal\Exception');
        $this->type->setRelation($name, $relation);
    }
    
    public function testTypeBuilder_noIdentityField()
    {
        $type_builder = new TypeBuilder;
        $this->setExpectedException('Aura\Marshal\Exception');
        $type = $type_builder->newInstance(array());
    }
}