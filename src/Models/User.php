<?php 

declare(strict_types=1);
namespace Financeiro\Models;
use Illuminate\Database\Eloquent\Model;
Use Jasny\Auth\User as JasnyUser;

class User extends Model implements JasnyUser, UserInterface
{
	protected $fillable = [
		'first_name',
        'last_name',
        'email',
        'password',
        'deleted_at'
	];

	public function getId():int
	{
		return (int)$this->id;
	}

	public function getUserName():string
	{
		return $this->email;
	}

	public function getHashedPassword():string
	{
		return $this->password;
	}

	public function onLogin()
	{

	}

	public function onLogout()
	{
		
	}

    public function getFullname(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswork(): string
    {
        return $this->password;
    }
}