<?php

namespace App\Http\Controllers;

use App\User;
use App\Address;
use App\Phone;

use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\UserTransformer;

class UserController extends Controller
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
        $user = User::find($id);
        $resource = new Item($user, new UserTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'type' => 'required',
            'photo' => 'required',
        ]);

        $user = User::create($request->only([
            'email',
            'password',
            'name',
            'gender',
            'type',
            'photo'
        ]));
        $user->phone()->create($request->only(['phone']));
        $user->address()->create($request->get('address'));
        $resource = new Item($user, new UserTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function update($id, Request $request){

        //validate request parameters
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'type' => 'required',
        ]);

        $user = User::find($id);

        if($request->has('phone')){
            $user->phone()->update($request->only(['phone']));
        }
        if($request->has('address')){
            $address = $request->get('address');
            unset($address['user_id']);
            $user->address()->update($address);
        }

        //Return error 404 response if product was not found
        if(!$user) return $this->errorResponse('product not found!', 404);

        $user->update($request->except(['email', 'phone', 'address', 'id']));

        if($user){
            //return updated data
            $resource = new Item($user, new UserTransformer);
            return $this->fractal->createData($resource)->toArray();
        }

        //Return error 400 response if updated was not successful
        return $this->errorResponse('Failed to update product!', 400);
    }

    public function customResponse($message = 'success', $status = 200)
    {
        return response(['status' =>  $status, 'message' => $message], $status);
    }
}
