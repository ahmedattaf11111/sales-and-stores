<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHelloRequest;
use App\Http\Requests\UpdateHelloRequest;
use App\Repositories\HelloRepository;
use Illuminate\Support\Facades\Storage;

class HelloController extends Controller
{
    private $helloRepository;
    public function __construct(HelloRepository $helloRepository)
    {
        // $this->middleware('auth');
        $this->helloRepository = $helloRepository;
    }

    //Dashboard endpoints
    public function store(StoreHelloRequest $request)
    {
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $employee = $this->helloRepository->store($request->input());
        return $employee;
    }
  
    public function update(UpdateHelloRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->helloRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_hello"];
    }

    public function delete($id)
    {
        $oldImage = $this->helloRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->helloRepository->getPage(request()->page_size, $text);
    }

}
