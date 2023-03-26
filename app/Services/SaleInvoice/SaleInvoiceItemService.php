<?php

namespace App\Services\SaleInvoice;

use App\Models\Batch;
use App\Models\Item;
use App\Models\UnitOfMeasure;
use App\Repositories\SaleInvoice\SaleInvoiceItemRepository;

class SaleInvoiceItemService
{
    private $saleInvoiceItemRepository;
    public function __construct(SaleInvoiceItemRepository $saleInvoiceItemRepository)
    {
        $this->saleInvoiceItemRepository = $saleInvoiceItemRepository;
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        $input["total_sale_price"] = $input["sale_price"] * $input["quantity"];
        $this->saleInvoiceItemRepository->incrementTotalSale(
            $input["sale_invoice_id"],
            $input["total_sale_price"]
        );
        $this->saleInvoiceItemRepository->create($input);
        $this->saleInvoiceItemRepository->decreaseBatchQuantity($input);
    }
    public function deleteItem($input)
    {
        $input["total_sale_price"] = $input["sale_price"] * $input["quantity"];
        $this->saleInvoiceItemRepository->incrementTotalSale(
            $input["sale_invoice_id"],
            $input["total_sale_price"]
        );
        $this->saleInvoiceItemRepository->deleteItem($input);
        $this->saleInvoiceItemRepository->incrementBatchQuantity($input);
    }
    public function getInvoiceItems($saleInvoiceId)
    {
        return $this->saleInvoiceItemRepository->getInvoiceItems($saleInvoiceId);
    }
}
