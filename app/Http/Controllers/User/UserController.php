<?php
//  namespace App\Http\Controllers\User;
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
//use App\Rolle;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {

        //  parent::__construct();
        //  $this->middleware('auth:api')->except(['verify', 'resend', 'setpw']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        /*
        request parametre
        email:"rolf.andal@keyteq.no"
        id:"105246103752587158064"
        idToken:"eyJhbGciOiJSUzI1NiIsImtpZCI6IjRlZjUxMThiMDg"
        image:"httpsgoogleu"
        name:"Rolf Andal"
        provider:"google"
        token:"ya29"
        */
        $user = User::findOrFail($id);

       // $kandidat->load('cv_poster');
       // return response()->json(['data' => $kandidat], 200);





        return response()->json(['data' => $request->id], 200);
    }

    public function index(Request $request)
    {
        $request->user()->authorizeRoles('brukere');
        $users = User::with('roller')->get();
        return response()->json(['data' => $users], 200);
    }



    public function rollene(Request $request)
    {
        $roller = $request->user()->roller;
        $rollenavn = array();

        foreach ($roller as $rolle) {

            $rollenavn[] = $rolle['navn'];

        }

        return response()->json(['data' => $rollenavn], 200);
    }

    public function endre_passord_for_paalogget_bruker(Request $request)
    {
        $user = $request->user();
        $user->password = bcrypt($request->passord); // $request->passord
        $user->change_password = '1';
        $user->save();
        return response()->json(['data' => $user], 200);
    }

    public function store(Request $request)
    {
        $request->user()->authorizeRoles('brukere-rediger');
        $request->user()->authorizeRoles(['roller', 'brukere']);

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users', //må være unik i users tabellen
        ];

        $this->validate($request, $rules); //validate metoden ligger i controller, ikke i ApiController
        $data = $request->all();
        $data['password'] = bcrypt('Sjakk2017');
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        $data['change_password'] = '0';
        $user = User::create($data);

        if ($request->has('roller')) {
            $user->roller()->sync($request->roller);
        }

        $user->load('roller');
        return response()->json(['data' => $user], 200);

    }

    public function show(User $user)
    {
        $request->user()->authorizeRoles('brukere');
        return $this->showOne($user);
    }

    public function update(Request $request, User $user)
    {

        $request->user()->authorizeRoles('brukere-rediger');

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id, //må være unik i users- tabellen sette bort i fra dene brukerens egen mail
            'password' => '|min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER, // admin kan være bare  User::ADMIN_USER eller User::REGULAR_USER AO True eller false
        ];
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            $this->allowedAdminAction();

            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }

            $user->admin = $request->admin;
        }

        if ($request->has('roller')) {
            $user->roller()->sync($request->roller);
        }

        if (!$user->isDirty()) {
            // return $this -> errorResponse('You need to specify a different value to update', 422);
        }

        $user->save();
        $user->load('roller');

        return response()->json(['data' => $user], 200);

    }

    public function destroy(User $user, Request $request)
    {
        $request->user()->authorizeRoles('brukere-rediger');
        $user->roller()->detach();
        $user->delete();
        return response()->json(['data' => $user], 200);
    }

}
