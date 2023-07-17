<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function index(Request $request){

        try {
            $products = $this->product;
            $productRepository = new ProductRepository($products);
    
            if($request->has('conditions')){
                $productRepository->selectConditions($request->get('conditions'));
            }
            
            if($request->has('fields')){
                $productRepository->selectFilter($request->get('fields'));
            }
            return response()->json($productRepository->getResult()->paginate(10),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.products.search_error'),$e->getMessage()),401);
        }
    }

    public function show($id){
        
        try {
            $product = $this->product->find($id);

            if ($product){
                return response()->json(
                    ApiMessages::getSuccessMessage(config('constants.products.found'),$product),200);
            }else{
                return response()->json(ApiMessages::getErrorMessage(config('constants.products.not_found')),404);
            }
            
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.products.search_error'),$e->getMessage()),401);
        }
    }

    public function store(ProductRequest $request){
        
        try {
            $data    = $request->all();
            $product = $this->product->create($data);

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.products.store'),$product),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.products.store_error'),$e->getMessage()),401);
        }
    }

    public function update($id,ProductRequest $request){

        try {
            $data    = $request->all();
            $product = $this->product->find($id);

            if (!$product){
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.products.not_found')),404);
            }

            $product->update($data);

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.products.update'),$product),200);
    
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.products.update_error'),$e->getMessage()),401);
        }
    }

    public function destroy($id){

        try {
            $product = $this->product->find($id);

            if (!$product){
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.products.not_found')),404);
            }

            $product->delete();

            return response()->json(
                ApiMessages::getSuccessMessage(config('constants.products.delete')),200);
        } catch (\Exception $e) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.products.delete'),$e->getMessage()),401);
        }
    }
}
