<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Address;
use App\Phone;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Transformers\UserTransformer;

use Cloudinary\Uploader;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
        $this->fractal = new Manager();

    }

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function store(Request $request){
        //validate request parameters
        $this->validate($request, [
            'email' => 'required||min:8|max:255|unique:users',
            'password' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'type' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $request->password = Hash::make($request->password);

        if($request->has('photo')){
            $image = Uploader::upload($request->photo);
            $request->merge(['photo' => $image['secure_url']]);
        }

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
        return response()->json([
            'token' => $this->jwt($user)
        ], 200);
        // $resource = new Item($user, new UserTransformer);
        // return $this->fractal->createData($resource)->toArray();
    }

}
