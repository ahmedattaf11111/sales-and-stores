<?php

namespace App\Services;

use App\Repositories\InvoiceCategoryRepository;

class InvoiceCategoryService
{
    private $invoiceCategoryRepository;
    public function __construct(InvoiceCategoryRepository $invoiceCategoryRepository)
    {
        $this->invoiceCategoryRepository = $invoiceCategoryRepository;
    }
    public function getInvoiceCategories($pageSize, $text)
    {
        return $this->invoiceCategoryRepository->getInvoiceCategories($pageSize, $text);
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        return ["invoice_category" => $this->invoiceCategoryRepository->create($input), "user" => $user];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        return ["invoice_category" => $this->invoiceCategoryRepository->update($input), "user" => $user];
    }
    public function delete($id)
    {
        $this->invoiceCategoryRepository->delete($id);
    }
}
