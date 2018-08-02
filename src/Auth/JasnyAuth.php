<?php

namespace Financeiro\Auth;

use Jasny\Auth\User;
use Jasny\Auth\Sessions;
use Financeiro\Repository\RepositoryInterface;

class JasnyAuth extends \Jasny\Auth
{
	use Sessions;

	private $repository;

	public function __construct(RepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

    /**
     * @param int|string $id
     * @return User|null
     */
    public function fetchUserById($id)
	{
		return $this->repository->find($id, false);
	}

    /**
     * @param string $username
     * @return User|null
     */
    public function fetchUserByUsername($username)
	{
        $result = $this->repository->findByField('email', $username);
        return count($result)? $result[0] : null;
	}

    /**
     * @param User|null $user
     * @param string $password
     * @return bool
     */
    public function verifyCredentials($user, $password)
    {
        return isset($user) && password_verify($password, $user->getHashedPassword());
    }
}