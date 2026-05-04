<?php

namespace Modules\Auth\Models;

readonly class AuthUser
{
    public function __construct(
        private array $claims
    ) {}

    public static function fromJwtPayload(array $payload): self
    {
        return new self($payload);
    }

    public function id(): string
    {
        return $this->claims['sub'];
    }

    public function username(): ?string
    {
        return $this->claims['preferred_username'] ?? null;
    }

    public function roles(): array
    {
        return $this->claims['realm_access']['roles'] ?? [];
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles(), true);
    }

    public function claims(): array
    {
        return $this->claims;
    }
}
