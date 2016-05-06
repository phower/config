<?php

/**
 * Phower Config
 *
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace PhowerTest\Config;

use PHPUnit_Framework_TestCase;

/**
 * Config test case
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{

    protected $config = [
        'string_value' => 'foo',
        'int_value' => 123,
        'bool_value' => true,
        'null_value' => null,
        'array_value' => ['foo' => 'bar'],
        0 => true,
        666 => ['baz' => 'woo', 'fiz' => ['hug', 'hug' => true]],
    ];

    public function testConfigImplementsConfigInterface()
    {
        $config = $this->getMockBuilder(\Phower\Config\Config::class)
                        ->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(\Phower\Config\ConfigInterface::class, $config);
    }

    public function testConstructCanInstantiateFromArray()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertAttributeInternalType('array', 'keys', $config);
        $this->assertAttributeInternalType('array', 'values', $config);
    }

    public function testConstructCanSetInstanceReadOnly()
    {
        $config = new \Phower\Config\Config($this->config, true);
        $this->assertAttributeEquals(true, 'readOnly', $config);
    }

    public function testConstructCanSetInstanceToAllowOverrides()
    {
        $config = new \Phower\Config\Config($this->config, true, true);
        $this->assertAttributeEquals(true, 'allowOverride', $config);
    }

    public function testHasChecksWeitherAKeyExists()
    {
        $config = new \Phower\Config\Config($this->config);
        foreach ($this->config as $key => $value) {
            $this->assertTrue($config->has($key));
        }
        $this->assertFalse($config->has('not_present'));
    }

    public function testHasRaisesExceptionWhenKeyIsNotStringAndNotInt()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->setExpectedException(\Phower\Config\Exception\InvalidKeyTypeException::class);
        $config->has(false);
    }

    public function testSetCanSetNewOptionWithStringKey()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertFalse($config->has('new_option'));
        $config->set('new_option', new \stdClass());
        $this->assertTrue($config->has('new_option'));
    }

    public function testSetCanSetNewOptionWithIntKey()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertFalse($config->has(33));
        $config->set(33, new \stdClass());
        $this->assertTrue($config->has(33));
    }

    public function testSetCanSetNewOptionWithNullKey()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertAttributeCount(count($this->config), 'keys', $config);
        $config->set(null, new \stdClass());
        $this->assertAttributeCount(count($this->config) + 1, 'keys', $config);
    }

    public function testSetRaisesExceptionWhenConfigInstanceIsReadOnly()
    {
        $config = new \Phower\Config\Config($this->config, true);
        $this->setExpectedException(\Phower\Config\Exception\ReadOnlyException::class);
        $config->set('new_option', new \stdClass());
    }

    public function testSetRaisesExceptionWhenConfigInstanceDoesNotAllowOverride()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->setExpectedException(\Phower\Config\Exception\OverrideException::class);
        $config->set('string_value', 'new value');
    }

    public function testSetRaisesExceptionWhenKeyIsInvalid()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->setExpectedException(\Phower\Config\Exception\InvalidKeyTypeException::class);
        $config->set(false, 'value');
    }

    public function testSetNormalizesKeysInternally()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $config->set('snake_case_key', true);
        $this->assertTrue($config->has('snake_case_key'));
        $this->assertTrue($config->has('snakecasekey'));
        $this->assertTrue($config->has('SnakeCaseKey'));
        $this->assertTrue($config->has(' SNAKE - CASE - KEY '));
    }

    public function testGetCanRetrieveValueOfExistingKey()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertEquals($this->config['string_value'], $config->get('string_value'));
        $this->assertEquals($this->config['int_value'], $config->get('int_value'));
        $this->assertEquals($this->config['bool_value'], $config->get('bool_value'));
        $this->assertEquals($this->config['null_value'], $config->get('null_value'));
        $config->set('new_value', 1968);
        $this->assertEquals(1968, $config->get('new_value'));
    }

    public function testGetReturnsDefaultArgumentWhenKeyDoesNotExist()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertEquals('default value', $config->get('not_present', 'default value'));
    }

    public function testGetRaisesExceptionWhenKeyIsInvalid()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->setExpectedException(\Phower\Config\Exception\InvalidKeyTypeException::class);
        $config->get(false);
    }

    public function testRemoveCanUnsetAnExistingKey()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertTrue($config->has('string_value'));
        $config->remove('string_value');
        $this->assertFalse($config->has('string_value'));
        $this->assertTrue($config->has(666));
        $config->remove(666);
        $this->assertFalse($config->has(666));
    }

    public function testRemoveRaisesExceptionWhenKeyIsInvalid()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->setExpectedException(\Phower\Config\Exception\InvalidKeyTypeException::class);
        $config->remove(false);
    }

    public function testRemoveRaisesExceptionWhenConfigInstanceIsReadOnly()
    {
        $config = new \Phower\Config\Config($this->config, true);
        $this->setExpectedException(\Phower\Config\Exception\ReadOnlyException::class);
        $config->remove('string_option');
    }

    public function testToArrayCanExtractOptionsAsAnArray()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals($this->config, $config->toArray());
    }

    public function testMergeCanMergeAnotherConfigInstanceIntoTheCurrentOne()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $config2 = new \Phower\Config\Config(['new_value' => true, 'value with int key']);
        $this->assertFalse($config->has('new_value'));
        $this->assertAttributeCount(count($this->config), 'keys', $config);
        $config->merge($config2);
        $this->assertTrue($config->has('new_value'));
        $this->assertAttributeCount(count($this->config) + 2, 'keys', $config);
    }

    public function testIsReadOnlyReturnsTrueWhenConfigInstanceIsReadOnly()
    {
        $config = new \Phower\Config\Config();
        $this->assertTrue($config->isReadOnly());
    }

    public function testSetReadOnlyCanChangeReadOnlyState()
    {
        $config = new \Phower\Config\Config();
        $this->assertTrue($config->isReadOnly());
        $config->setReadOnly(false);
        $this->assertFalse($config->isReadOnly());
    }

    public function testAllowOverrideReturnsTrueWhenConfigInstanceAllowsOverride()
    {
        $config = new \Phower\Config\Config();
        $this->assertTrue($config->isReadOnly([], true, true));
    }

    public function testSetAllowOverrideCanChangeAllowOverrideState()
    {
        $config = new \Phower\Config\Config();
        $this->assertFalse($config->allowOverride());
        $config->setAllowOverride(true);
        $this->assertTrue($config->allowOverride());
    }

    public function testOffsetExistsChecksWeitherAnOffsetExists()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertTrue($config->offsetExists('string_value'));
    }

    public function testOffsetGetReturnsValueForAnExistingOffset()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals($this->config['string_value'], $config->offsetGet('string_value'));
        $this->assertNull($config->offsetGet('not_there'));
    }

    public function testOffsetSetCanSetValueForAGivenOffset()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertFalse($config->offsetExists('new_offset'));
        $config->offsetSet('new_offset', 123);
        $this->assertEquals(123, $config->offsetGet('new_offset'));
    }

    public function testOffsetUnsetCanRemoveExistingOffset()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertTrue($config->offsetExists('string_value'));
        $config->offsetUnset('string_value');
        $this->assertFalse($config->offsetExists('string_value'));
    }

    public function testCountReturnsNumberOfOptionsOnConfigInstance()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals(count($this->config), $config->count());
    }

    public function testCurrentReturnsValueOfCurrentPositionKey()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals(current($this->config), $config->current());
    }

    public function testKeyReturnsKeyOfCurrentPositionKey()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals(key($this->config), $config->key());
    }

    public function testNextMovesConfigInstanceInternalPointerForward()
    {
        $config = new \Phower\Config\Config($this->config);
        next($this->config);
        $config->next();
        $this->assertEquals(key($this->config), $config->key());
    }

    public function testValidReturnsWeitherInternalPointerReachedEndOfOptions()
    {
        $config = new \Phower\Config\Config($this->config);
        foreach ($config as $key => $value) {
            $this->assertTrue($config->valid());
        }
        $this->assertFalse($config->valid());
    }

    public function testRewindResetInternalPointerPosition()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals('string_value', $config->key());
        $config->next();
        $this->assertEquals('int_value', $config->key());
        $config->rewind();
        $this->assertEquals('string_value', $config->key());
    }

    public function testMagicGetterCallsGet()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals($config->get('string_value'), $config->string_value);
        $this->assertEquals($config->get('array_value')->get('foo'), $config->array_value->foo);
    }

    public function testMagicSetterCallsSet()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertFalse($config->has('zaa'));
        $config->zaa = true;
        $this->assertTrue($config->has('zaa'));
        $this->assertFalse($config->get('array_value')->has('zaa'));
        $config->array_value->zaa = true;
        $this->assertTrue($config->get('array_value')->has('zaa'));
    }

    public function testMagicUnsetterCallsRemove()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertTrue($config->has('string_value'));
        unset($config->string_value);
        $this->assertFalse($config->has('string_value'));
    }

    public function testMagicCallerCanCallSet()
    {
        $config = new \Phower\Config\Config($this->config, false, true);
        $this->assertTrue($config->has('string_value'));
        $config->setStringValue('new value');
        $this->assertEquals('new value', $config->stringValue);
    }

    public function testMagicCallerCanCallGet()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertEquals($config->stringValue, $config->getStringValue());
    }

    public function testMagicCallerCanCallHas()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->assertTrue($config->hasStringValue());
        $this->assertFalse($config->hasUndefined());
    }

    public function testMagicCallerCanCallRemove()
    {
        $config = new \Phower\Config\Config($this->config, false);
        $this->assertTrue($config->hasStringValue());
        $config->removeStringValue();
        $this->assertFalse($config->hasStringValue());
    }

    public function testMagicCallerRaisesExceptionWhenInvalidMethodIsCalled()
    {
        $config = new \Phower\Config\Config($this->config);
        $this->setExpectedException(\Phower\Config\Exception\InvalidMethodNameException::class);
        $config->invalidMethod();
    }
}
