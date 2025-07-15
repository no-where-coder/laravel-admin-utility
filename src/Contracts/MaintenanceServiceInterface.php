<?php

namespace Nowhere\AdminUtility\Contracts;

interface MaintenanceServiceInterface
{
    public function isDown(): bool;

    public function enable(): void;

    public function disable(): void;
}
