<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository
{
    public function getStores($pageSize, $text)
    {
        return Store::where("name", "like", "%$text%")->with(["added_by", "updated_by"])
            ->paginate($pageSize);
    }
    public function create($input)
    {
        return Store::create($input);
    }
    public function update($input)
    {
        $store = Store::find($input["id"]);
        $store->update($input);
        return $store;
    }
    public function delete($id)
    {
        Store::destroy($id);
    }
}
