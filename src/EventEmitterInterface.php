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

interface EventEmitterInterface
{
    public function on($event, callable $listener): self;
    public function once($event, callable $listener): self;
    public function removeListener(int|string $event, callable $listener): void;
    public function removeAllListeners(int|string|null $event = null): void;
    public function removeListenerFromListeners(int|string $event, callable $listener, array &$listeners): void;
    public function listeners(int|string|null $event = null): array;
    public function emit(int|string $event, array $arguments = []): void;
}
