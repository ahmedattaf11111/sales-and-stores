<?php

namespace App\Services\Supplier;

use App\Repositories\Supplier\SupplierCategoryRepository;

class SupplierCategoryService
{
    private $supplierCategoryRepository;
    public function __construct(SupplierCategoryRepository $supplierCategoryRepository)
    {
        $this->supplierCategoryRepository = $supplierCategoryRepository;
    }
    public function getSupplierCategories($pageSize, $text)
    {
        return $this->supplierCategoryRepository->getSupplierCategories($pageSize, $text);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["supplier_category" => $this->supplierCategoryRepository->create($input), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["supplier_category" => $this->supplierCategoryRepository->update($input), "user" => $user];
    }
}
