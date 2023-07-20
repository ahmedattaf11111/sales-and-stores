<?php

namespace App\Services\SaleReturnInvoice;

use App\Models\Batch;
use App\Models\Item;
use App\Models\UnitOfMeasure;
use App\Repositories\SaleReturnInvoice\SaleReturnInvoiceItemRepository;

class SaleReturnInvoiceItemService
{
    private $saleInvoiceItemRepository;
    public function __construct(SaleReturnInvoiceItemRepository $saleInvoiceItemRepository)
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
        $this->saleInvoiceItemRepository->incrementBatchQuantity($input);
    }
    public function deleteItem($input)
    {
        $this->saleInvoiceItemRepository->decrementTotalSale(
            $input["sale_invoice_id"],
            $input["total_sale_price"]
        );
        $this->saleInvoiceItemRepository->deleteItem($input["id"]);
        $this->saleInvoiceItemRepository->decreaseBatchQuantity($input);
    }
    public function getInvoiceItems($saleInvoiceId)
    {
        return $this->saleInvoiceItemRepository->getInvoiceItems($saleInvoiceId);
    }
}
