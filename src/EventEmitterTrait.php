<?php declare(strict_types=1);

/*
 * This file is part of Evenement.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 * (c) Valithor Obsidion <valithor@valzargaming.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evenement;

use function array_keys;
use function array_merge;
use function array_search;
use function array_unique;

trait EventEmitterTrait
{
    protected $listeners = [];
    protected $onceListeners = [];

    public function on(int|string $event, callable $listener): self
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function once(int|string $event, callable $listener): self
    {
        $this->onceListeners[$event][] = $listener;

        return $this;
    }

    public function removeListener(int|string $event, callable $listener): void
    {
        $this->removeListenerFromListeners($event, $listener, $this->listeners);
        $this->removeListenerFromListeners($event, $listener, $this->onceListeners);
    }

    public function removeListenerFromListeners(int|string $event, callable $listener, array &$listeners): void
    {
        if (isset($listeners[$event])) {
            $index = array_search($listener, $listeners[$event], true);
            if ($index !== false) {
                unset($listeners[$event][$index]);
                if (empty($listeners[$event])) {
                    unset($listeners[$event]);
                }
            }
        }
    }

    public function removeAllListeners(int|string|null $event = null): void
    {
        if (is_null($event)) {
            $this->listeners = [];
            $this->onceListeners = [];
        } else {
            unset($this->listeners[$event]);
            unset($this->onceListeners[$event]);
        }
    }

    public function listeners(int|string|null $event = null): array
    {
        if (! is_null($event)) {
            return array_merge($this->listeners[$event] ?? [], $this->onceListeners[$event] ?? []);
        }
        
        $events = [];
        foreach (array_unique(array_merge(array_keys($this->listeners), array_keys($this->onceListeners))) as $eventName) {
            $events[$eventName] = array_merge($this->listeners[$eventName] ?? [], $this->onceListeners[$eventName] ?? []);
        }

        return $events;
    }

    public function emit(int|string $event, array $arguments = []): void
    {
        foreach ($this->listeners[$event] ?? [] as $listener) {
            $listener(...$arguments);
        }

        if (isset($this->onceListeners[$event])) {
            foreach ($this->onceListeners[$event] as $listener) {
                $listener(...$arguments);
            }
            unset($this->onceListeners[$event]);
        }
    }
}
