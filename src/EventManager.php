<?php

namespace Dhii\WpEvents;

use Psr\EventManager\EventInterface;
use Psr\EventManager\EventManagerInterface;

/**
 * Event Manager implementation for WordPress.
 *
 * This class complies with the PSR-14 Event Manager standard (as of date 15/10/2016).
 *
 * It wraps around the WordPress hook mechanism by utilizing filters as generic events, since in WordPress actions
 * are actually also filters. For this reason, an event will always be capable of yeilding a result.
 *
 * @author Miguel Muscat <miguelmuscat93@gmail.com>
 */
class EventManager implements EventManagerInterface
{
    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attach($event, $callback, $priority = 10)
    {
        $eventObject   = $this->normalizeEvent($event);
        $numArgsToPass = $this->getCallableNumParams($callback);
        \add_filter($eventObject->getName(), $callback, $priority, $numArgsToPass + 1);
    }

    /**
     * {@inheritdoc}
     */
    public function detach($event, $callback)
    {
        $eventObject = $this->normalizeEvent($event);
        \remove_filter($eventObject->getName(), $callback);
    }

    /**
     * {@inheritdoc}
     */
    public function clearListeners($event)
    {
        $eventObject = $this->normalizeEvent($event);
        \remove_all_filters($eventObject->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event, $target = null, $argv = array())
    {
        $args = empty($argv)
            ? array(null)
            : $argv;
        array_push($args, $target);
        $eventObject = $this->normalizeEvent($event);
        \apply_filters_ref_array($eventObject->getName(), $args);
    }

    /**
     * Normalizes the given event into an Event instance.
     *
     * @param EventInterface|string $event Event instance or an event name string.
     *
     * @return EventInterface The event instance.
     */
    protected function normalizeEvent($event)
    {
        return ($event instanceof EventInterfacevent)
            ? $event
            : new Event($event);
    }

    /**
     * Gets the number of parameters for a callable.
     *
     * @param callable $callable The callable.
     *
     * @return int The number of parameters.
     */
    protected function getCallableNumParams($callable)
    {
        return $this->getCallableReflection($callable)->getNumberOfParameter();
    }

    /**
     * Gets the reflection instance for a callable.
     *
     * @param callable $callable The callable.
     *
     * @return ReflectionFunction|ReflectionMethod The reflection instance.
     */
    protected function getCallableReflection($callable)
    {
        return is_array($callable) ?
            new ReflectionMethod($callable[0], $callable[1]) :
            new ReflectionFunction($callable);
    }
}
