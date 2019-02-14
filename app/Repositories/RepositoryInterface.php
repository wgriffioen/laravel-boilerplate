<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Basic interface with all the methods a repository should have
 *
 * A convenient interface to fetch models from a data source. This could be any data source like a relation database, a
 * Document based database like ElasticSearch or even plain text files.
 */
interface RepositoryInterface
{
    /**
     * Return a collection with all the models from the data source
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all();

    /**
     * Create a new record in the data source
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing record in the data source
     *
     * @param array $data
     * @param mixed $id
     * @return bool
     */
    public function update(array $data, $id);

    /**
     * Remove a record from the data source
     *
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id);

    /**
     * Get one specific model from the data source
     *
     * @param $id
     * @return Model
     */
    public function get($id);
}
