<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
// Respuestas informativas (100–199),
// Respuestas satisfactorias (200–299),
// Redirecciones (300–399),
// Errores de los clientes (400–499),
// y errores de los servidores (500–599).
      //GET listar registros
      public function index(Request $request)
      {   
          if ($request->has('parametro')) {
              
              $filterData = DB::table('users')->
              where('nombres','LIKE','%'.$request->parametro.'%')
              ->orWhere('identificacion', $request->parametro)
              ->orWhere('id', $request->parametro)
              ->get();
          }else{
            $filterData = User::all();  

          }
          return $filterData;
      }

    public function store(Request $request){
        $input = $request->all();
        $email_exist = User::whereEmail($request->email)->first();
        $dni_exist = DB::table('users')->where('identificacion', $request->identificacion)->first();
        $tel_exist = DB::table('users')->where('telefono', $request->telefono)->first();

       
        if(!is_null($tel_exist)){
            return response()->json([
                'res' => false,
                'message' => 'El Telefono ya existe',
                'status_api' => "error"
            ], 200); 
        }
        if(!is_null($dni_exist)){
            return response()->json([
                'res' => false,
                'message' => 'El Número de Identificacion ya existe',
                'status_api' => "error"
            ], 200); 
        }
        if(!is_null($email_exist)){
            return response()->json([
                'res' => false,
                'message' => 'El email ya existe en nuestra base de datos, intente con otro',
                'status_api' => "error"
            ], 200); 
        }else{
             $input['password'] = Hash::make($request->password);
        User::create($input);
            return response()->json([
            'res' => true,
            'message' => 'Usuario creado correctamente',
            'status_api' => 'InsertOk'
        ], 200);  
        }

       
    }
    //GET retorna un solo registro
    public function show(User $user)
    {
        return $user;
    }


    public function login(Request $request){    
     
        $user = User::whereEmail($request->email)->first();
        if(!is_null($user) && Hash::check($request->password, $user->password)){

            $token = $user->createToken('apiusuarios2')->accessToken;
            $id = $user->id;
            return response()->json([
                'res' => true,
                'token' => $token,
                'id' => $id,
                'message' => 'Bienvenido al Sistema',
                'status_api' => 'ok'
            ], 200);
        } else{
            return response()->json([
                'res' => false,
                'message' => 'Cuenta o Password Incorrectos',
                'status_api' => 'noOk'
            ], 201);
        }    
}

    public function logout(){
        $user = auth()->user();
        $user->tokens->each(function ($token, $key){
            $token->delete();
        });
        $user->save();

        return response()->json([
            'res' => true,
            'message' => 'Has Salido del Sistema'
        ], 200);
    }

    //PUT
    public function update(Request $request, User $user)
    {
        $email_exist = DB::select("select count(*)  as encontrado from users where email = ? and id != ?", [$request->email,$request->id]);
        $dni_exist = DB::select("select count(*) as encontrado  from users where identificacion = ? and id != ?", [$request->identificacion,
        $request->id]);
        $tel_exist = DB::select("select count(*)  as encontrado from users where telefono = ? and id != ?", [$request->telefono,$request->id]);

      
        if(($tel_exist[0]->encontrado) > 0){
            return response()->json([
                'res' => false,
                'message' => 'El Telefono ya existe',
                'status_api' => "Error"
            ], 200); 
        }
        if(($dni_exist[0]->encontrado) > 0){
            return response()->json([
                'res' => false,
                'message' => 'El Número de Identificacion ya existe',
                'status_api' => "Error"
            ], 200); 
        }
        if(($email_exist[0]->encontrado) > 0){
            return response()->json([
                'res' => false,
                'message' => 'El email ya existe en nuestra base de datos, intente con otro',
                'status_api' => "Error"
            ], 200); 
        }else{
              $input = $request->all();
             $input['password'] = Hash::make($request->password);

        $user->update($input);
        return response()->json([
            'res' => true,
            'message' =>'Registro Atualizado Correctamente',
            'status_api' => 'UpdatedOk'
        ], 200); 
        } 
      
    }

      //DELETE
      public function destroy($id)
      {
          User::destroy($id);
          return response()->json([
              'res' => true,
              'message' =>'Registro Eliminado Correctamente',
              'status_api' => 'DestroyOk'
          ], 200);
      }
}
