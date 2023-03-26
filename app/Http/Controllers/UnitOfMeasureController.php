<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnitOfMeasureRequest;
use App\Http\Requests\UpdateUnitOfMeasureRequest;
use App\Services\UnitOfMeasureService;

class UnitOfMeasureController extends Controller
{
    private $unitOfMeasureService;
    public function __construct(UnitOfMeasureService $unitOfMeasureService)
    {
        $this->middleware("auth:admin");
        $this->unitOfMeasureService = $unitOfMeasureService;
    }
    public function index()
    {
        return $this->unitOfMeasureService->getUnitOfMeasures(request()->page_size, request()->text);
    }
    public function create(CreateUnitOfMeasureRequest $request)
    {
        $user = $request->user();
        return $this->unitOfMeasureService->create($user, $request->validated());
    }
    public function update(UpdateUnitOfMeasureRequest $request)
    {
        $user = $request->user();
        return $this->unitOfMeasureService->update($user, $request->validated());
    }
    public function delete($id)
    {
        $this->unitOfMeasureService->delete($id);
    }
}
