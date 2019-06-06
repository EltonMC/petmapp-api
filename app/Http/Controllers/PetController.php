<?php

namespace App\Http\Controllers;

use App\Pet;
use App\Address;
use App\Phone;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\PetTransformer;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Cloudinary\Uploader;

class PetController extends Controller
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


    /**
     * GET /pets
     *
     * @return array
     */
    public function index(Request $request){
        $paginator = Pet::where('user_id', $request->auth->id)->paginate();
        $pets = $paginator->getCollection();
        $resource = new Collection($pets, new PetTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function show($id){
        $pet = Pet::find($id);
        $resource = new Item($pet, new PetTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'name' => 'required|max:255',
            'birthday' => 'required',
            'type' => 'required',
            'breed' => 'required',
            'gender' => 'required',
            'temper' => 'required',
        ]);

        // if($request->has('image')){
        //     $image = Uploader::upload($request->photo);
        //     $request->merge(['photo' => $image['secure_url']]);
        // }

        $request->merge(['user_id' => $request->auth->id]);
        $pet = Pet::create($request->all());

        $resource = new Item($pet, new PetTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'type' => 'required',
        ]);

        $pet = Pet::find($id);

        //Return error 404 response if product was not found
        if(!$pet) return $this->errorResponse('pet not found!', 404);

        $pet->update($request->except(['user_id', 'id']));

        if($pet){
            //return updated data
            $resource = new Item($pet, new PetTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->errorResponse('Failed to update pet!', 400);
    }

    public function destroy($id){
        $pet = Pet::find($id);

        //Return error 404 response if product was not found
        if(!$pet) return $this->errorResponse('Pet not found!', 404);

        //Return 410(done) success response if delete was successful
        if($pet->delete()){
            return $this->customResponse('pet deleted successfully!', 410);
        }

        //Return error 400 response if delete was not successful
        return $this->errorResponse('Failed to delete pet!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
