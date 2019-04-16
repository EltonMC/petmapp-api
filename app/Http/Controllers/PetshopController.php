<?php

namespace App\Http\Controllers;

use App\Petshop;
use App\Address;
use App\Phone;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\PetshopTransformer;

class PetshopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function show($id){
        $petshop = Petshop::find($id);
        $resource = new Item($petshop, new PetshopTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $petshop = Petshop::create($request->only([
            'user_id',
            'name',
            'description'
        ]));

        $resource = new Item($petshop, new PetshopTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $petshop = Petshop::find($id);


        if($request->has('images')){
            // $request->password = Hash::make($request->password);
        }
        //Return error 404 response if petshop was not found
        if(!$petshop) return $this->customResponse('petshop not found!', 404);

        $petshop->update($request->except(['user_id', 'id']));

        if($petshop){
            //return updated data
            $resource = new Item($petshop, new PetshopTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->customResponse('Failed to update petshop!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
