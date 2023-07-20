<?php

namespace App\Services\PurchaseInvoice;

use App\Repositories\PurchaseInvoice\PurchaseInvoiceItemRepository;

class PurchaseInvoiceItemService
{
    private $purchaseInvoiceItemRepository;
    public function __construct(PurchaseInvoiceItemRepository $purchaseInvoiceItemRepository)
    {
        $this->purchaseInvoiceItemRepository = $purchaseInvoiceItemRepository;
    }
    public function getPurchaseInvoiceItems($pageSize, $purchaseInvoiceId, $text)
    {
        return $this->purchaseInvoiceItemRepository->getPurchaseInvoiceItems($pageSize, $purchaseInvoiceId, $text);
    }
    public function getItems()
    {
        return $this->purchaseInvoiceItemRepository->getItems();
    }
    public function create($user, $input)
    {
        $input["added_by_id"] = $user->id;
        $input["total_purchase_price"] = $input["purchase_price"] * $input["quantity"];
        $this->purchaseInvoiceItemRepository->updatePurchaseInvoiceTotalPrice(
            $input["purchase_invoice_id"],
            $input["total_purchase_price"]
        );
        return [
            "purchase_invoice_item" =>  $this->purchaseInvoiceItemRepository->create($input),
            "user" => $user
        ];
    }
    public function update($user, $input)
    {
        $input["updated_by_id"] = $user->id;
        $input["total_purchase_price"] = $input["purchase_price"] * $input["quantity"];
        $purchaseInvoiceItem = $this->purchaseInvoiceItemRepository->update($input);
        $this->purchaseInvoiceItemRepository->updatePurchaseInvoiceTotalPrice(
            $purchaseInvoiceItem->purchase_invoice_id,
            $input["total_purchase_price"]
        );
        return [
            "purchase_invoice_item" => $purchaseInvoiceItem,
            "user" => $user
        ];
    }
    public function isPurchseInvoiceApproved($id)
    {
        return ["is_purchase_invoice_approved" => $this->purchaseInvoiceItemRepository->isPurchaseInvoiceApproved($id)];
    }
    public function delete($id)
    {
        $this->purchaseInvoiceItemRepository->delete($id);
    }
}
