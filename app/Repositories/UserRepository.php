<?php
namespace App\Repositories;

// use App\Models\Product;
// use App\Models\Address;
// use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends AbstractRepository
{
    public function findOrders($columns = ['*'], string $orderBy = 'id') : Collection
    {
        return $this->model->orders()->get($columns)->sortByDesc($orderBy);
    }

    public function findAddresses() : Collection
    {
        dd($this->model->addresses);
        return $this->model->addresses;
    }
}