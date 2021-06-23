<?php

namespace WpOop\Events\Exception\FuncTest;

use WpOop\Events\Exception\StoppedPropagationException;
use Psr\EventManager\EventInterface;
use PHPUnit\Framework\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class StoppedPropagationExceptionTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'WpOop\Events\Exception\StoppedPropagationException';

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
                     ->setConstructorArgs([$message])
                     ->getMock();

        return $mock;
    }

    /**
     * Creates a mock event instance.
     *
     * @since [*next-version*]
     *
     * @param string $name        The event name.
     * @param null   $target      The event target.
     * @param array  $params      The event params.
     * @param bool   $propagation True if the event can propagate, false otherwise.
     *
     * @return MockObject|EventInterface The created instance.
     */
    public function createEvent($name = '', $target = null, $params = [], $propagation = true)
    {
        $mock = $this->getMockBuilder('Psr\EventManager\EventInterface')
                     ->setMethods(
                         [
                             'getName',
                             'getTarget',
                             'getParams',
                             'getParam',
                             'setName',
                             'setTarget',
                             'setParams',
                             'stopPropagation',
                             'isPropagationStopped',
                         ]
                     )
                     ->getMockForAbstractClass();

        $mock->method('getName')->willReturn($name);
        $mock->method('getTarget')->willReturn($target);
        $mock->method('getParams')->willReturn($params);
        $mock->method('isPropagationStopped')->willReturn($propagation);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new StoppedPropagationException();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Exception',
            $subject,
            'Test subject is not a valid PHP exception.'
        );

        $this->assertInstanceOf(
            'WpOop\Events\Exception\StoppedPropagationExceptionInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );

        $this->assertInstanceOf(
            'Dhii\Exception\ThrowableInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests whether the constructor correctly assigns the exception data and that the getters correctly retrieve them.
     *
     * @since [*next-version*]
     */
    public function testConstructorAndGetters()
    {
        $message = uniqid('message-');
        $code = rand(0, 100);
        $previous = $this->createException();
        $event = $this->createEvent();

        $subject = new StoppedPropagationException($message, $code, $previous, $event);

        $this->assertSame($message, $subject->getMessage());
        $this->assertSame($code, $subject->getCode());
        $this->assertSame($previous, $subject->getPrevious());
        $this->assertSame($event, $subject->getEvent());
    }
}
