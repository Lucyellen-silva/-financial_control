<?php

namespace Financeiro\Auth;

use Financeiro\Models\UserInterface;

interface AuthInterface
{
	public function login(array $credentials): bool;

	public function check(): bool;

	public function logout(): void;

	public function hashPassword(string $password): string;

	public function user(): ?UserInterface;
}