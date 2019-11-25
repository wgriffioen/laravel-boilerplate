# Laravel boiler plate

Laravel boiler plate is my personal starter kit to speed up the process of bootstrapping a new Laravel project. It is 
based on the most recent version of Laravel and includes a few packages I always tend to install as well as a few 
additions which I find useful.

## Requirements

Because the compiled assets from the default Laravel project are removed, it's required to have node and yarn installed.
This is because after the project is created, the assets are compiled automatically.

## Usage

To bootstrap a new project, simply run the following commands

    $ composer create-project wgriffioen/laravel-boilerplate <new-project-name>
    $ yarn
    $ yarn run dev

## Included packages

- Laravel IDE helper - [barryvdh/larravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)
- Mockery - [mockery/mockery](https://github.com/mockery/mockery)

## Modifications to the default Laravel project

- The compiled assets in `public/js` and `public/css` are by default ignored by git
- Move the models to the namespace `App/Models` and the corresponding folder
- Enable Laravel Auth by default
- Switch to the React preset. It's easy to revert this by running `php artisan preset vue`

## Additions

I'm not a fan of Laravel's Eloquent because it's not completely separation of concerns. Because of this, I like to make
use of repositories to query, create, modify and destroy data in the data source. The way I set this up, is to extend
the `App\Repositories\AbstractEloquentRepository` which implements the `App\Repositories\RepositoryInterface`. The
repository can than be injected as a dependency as can be in `App\Http\Controllers\Auth\RegisterController`.

A basic UserRepository look like this

```php
<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractEloquentRepository
{
    public function __construct() 
    {
        $this->model = new User();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
```
    
To inject the repository as a dependency of the controller, you need to supply the repository to the constructor:

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $user = $this->repository->findByEmail($request->input('email'));

        return view('users.index', ['email' => $user->email]);
    }
}
```
    
### Testing

This approach makes it easier to cover your code with only unit tests

```php
<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function testIndex()
    {
        $user = factory(User::class)->make([
            'name' => 'Wim Griffioen',
            'email' => 'wgriffioen@example.com'
        ]);

        $repository = \Mockery::mock(UserRepository::class);
        $repository
            ->shouldReceive('findByEmail')
            ->with('wgriffioen@example.com')
            ->andReturn($user);

        $request = new Request();
        $request->replace(['email' => 'wgriffioen@gmail.com']);

        $controller = new UserController($repository);

        $this->assertInstanceOf(View::class, $controller->index($request));
        $this->assertEquals(['email' => 'wgriffioen@example.com'], $controller->index($request)->getData());
    }
}
```

In order to test the functionality of the repositories, you should write feature tests as they interact with the 
database:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testFindByEmail()
    {
        factory(User::class)->create(['email' => 'wgriffioen@example.com']);

        $repository = new UserRepository(new User());

        $this->assertInstanceOf(User::class, $repository->findByEmail('wgriffioen@gmail.com'));
        $this->assertEquals('wgriffioen@gmail.com', $repository->findByEmail('wgriffioen@gmail.com')->email);

        $this->assertNull($repository->findByEmail('johndoe@example.com'));
    }
}
```

## License

The Laravel framework as well as my modifications are open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
