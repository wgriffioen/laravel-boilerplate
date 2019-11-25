<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Abstract implementation of the EloquentRepositoryInterface for Eloquent
 *
 * To create a repository for a model, all you have to do is extend this abstract class:
 *
 * ```php
 * <?php
 *
 * class UserRepository extends AbstractEloquentRepository {}
 * ```
 *
 * To instantiate the implemented repository, you have to supply an instance of the model to the constructor:
 *
 * ```php
 * <?php
 *
 * use \App\Models\User
 *
 * $shipmentsRepository = new UserRepository(new User());
 * ```
 *
 * @see \App\Repositories\EloquentRepositoryInterface
 */
abstract class AbstractEloquentRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Create an instance of a repository
     *
     * The constructor should set the $model property of the repository.
     */
    abstract function __construct();

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $data, $id)
    {
        $model = $this->get($id);

        return $model->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->model->find($id);
    }
}
