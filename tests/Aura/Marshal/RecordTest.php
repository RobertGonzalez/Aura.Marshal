<?php
namespace Aura\Marshal;
use Aura\Marshal\Record\GenericRecord;
use Aura\Marshal\MockType;

/**
 * Test class for Record.
 * Generated by PHPUnit on 2011-11-26 at 14:30:57.
 */
class RecordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GenericRecord
     */
    protected $record;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $data = [
            'foo' => 'bar',
            'baz' => 'dim',
            'zim' => 'gir',
            'numeric' => '123',
            'zero' => 0,
            'falsy' => false,
            'nully' => null,
            'related' => 'related_record',
        ];
        
        $type = new MockType;
        
        // add a fake relation so we can check changes on relationships
        $type->addFakeRelation('related');
        
        $this->record = new GenericRecord($data, $type);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
    
    public function testSetAndGet()
    {
        $this->record->irk = 'doom';
        $this->assertSame('doom', $this->record->irk);
    }
    
    public function testIssetAndUnset()
    {
        $this->assertTrue(isset($this->record->foo));
        unset($this->record->foo);
        $this->assertTrue(isset($this->record->foo));
        
        $this->assertFalse(isset($this->record->newfield));
        
        $this->record->newfield = 'something';
        $this->assertTrue(isset($this->record->newfield));
        
        unset($this->record->newfield);
        $this->assertTrue(isset($this->record->newfield));
    }
    
    public function testGetChangedFields_newField()
    {
        $this->record->newfield = 'something';
        $expect = ['newfield' => 'something'];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetChangedFields_numeric()
    {
        // change from string '123' to int 123;
        // it should not be marked as a change
        $this->record->numeric = 123;
        $expect = [];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
        
        $this->record->numeric = 4.56;
        $expect = ['numeric' => 4.56];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
        
        $this->record->zero = '';
        $expect = ['numeric' => 4.56, 'zero' => ''];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetChangedFields_toNull()
    {
        $this->record->zero = null;
        $this->record->falsy = null;
        $expect = ['zero' => null, 'falsy' => null];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetChangedFields_fromNull()
    {
        $this->record->nully = 0;
        $expect = ['nully' => 0];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetChangedFields_other()
    {
        $this->record->foo = 'changed';
        $expect = ['foo' => 'changed'];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetChangedFields_related()
    {
        $this->record->related = 'change related record';
        $expect = [];
        $actual = $this->record->getChangedFields();
        $this->assertSame($expect, $actual);
    }
}
