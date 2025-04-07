<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Validator;
use Input;
use App\Models\Vendedor;
use App\Models\Local;

class VendedorController extends Controller
{
   public function index(){

      $locales=Local::select("id", "nombre")
                     ->whereNull("deleted_at")
                     ->get();

      return view('Vendedor.index', compact('locales'));

   }

   public function consulta_data(){
      $userid = \Auth::id();
    
      $result=Vendedor::whereNull('deleted_at')
            ->orderBy('id')
            ->get();
      

      $titulos = [];
      $titulos[] = array('title' => '');
      $titulos[] = array('title' => 'Acciones');
      $titulos[] = array('title' => 'CI');
      $titulos[] = array('title' => 'Nombre');
      $titulos[] = array('title' => 'Apellido');
      $titulos[] = array('title' => 'Email');
      $titulos[] = array('title' => 'Dirección');

      $jsonenv=[];
      
        foreach ($result as $res) {
   
         $boton_up=' <button  title="editar" class="btn btn-success" name="editar" onclick="mostrarmodal('.$res->id.');"><i class="fa fa-edit"></i> </button>';
    
         $boton_elim=' <button title="eliminar" class="btn btn-danger" name="eliminar" onclick="elim('.$res->id.');"><i class="fa fa-trash"></i> </button>';
   
         $button= $boton_up.''.$boton_elim;

         $jsonenvtemp = ['',$button,$res->ci_ruc,$res->nombre,$res->apellido,$res->email,$res->direccion];

          array_push($jsonenv, $jsonenvtemp);
        }

       return response()->json(["sms"=> $jsonenv, "titulos"=>$titulos]);   
   }

   public function store(Request $request){
      $userid = \Auth::id();


      $v = Validator::make($request->all(),[
        'email'=>"required|unique:vendedor,email",
      ]);

      if($v->fails()){
         $mensajedereturn=strtoupper($v->errors()->first('email'));
         return response()->json(["sms"=>false ,"mensaje" => $mensajedereturn]);     
      }


      try {
         DB::beginTransaction();
            
                     Vendedor::create([
                        'id_local' => $request->local_vend,
                        'ci_ruc' => $request->ci_ruc,
                        'nombre' => $request->nombre,
                        'apellido' => $request->apellido,
                        'email' => $request->email,
                        'password' => Hash::make($request->contra),
                        'direccion' => $request->direccion,
                        'updated_at' =>now(),
                        'created_at' =>now(),
                        'user_updated' => $userid,
			'dfa' =>0,
                     ]);


         DB::commit();
         
         return response()->json(["sms"=>true, "mensaje"=>"Se creo correctamente"]);

      }catch(\Exception $e){
         DB::rollBack();
         return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);           
      }
   }

   public function edit($id){
      $result_edit=Vendedor::where('id',$id)->first();

      return view('Vendedor.edit', compact('result_edit','id'));
   }

   public function update($id, Request $request){
      $userid = \Auth::id();

      try {
         DB::beginTransaction();

            Vendedor::where('id', $id)->update([
               'nombre' => $request->nombre_edit,
               'apellido' => $request->apellido_edit,
               'direccion' => $request->direccion_edit,
               'user_updated' => $userid,
            ]);

         DB::commit();
         return response()->json(["sms"=>true, "mensaje"=>"Se edito correctamente"]);
      }catch(\Exception $e){
         DB::rollBack();
         return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);           
      }
     
   }
   public function destroy($id){
      $userid = \Auth::id();
      try 
          {
            DB::beginTransaction();

            Vendedor::where('id', $id)->update([
               'updated_at' =>now(),
               'deleted_at' =>now(),
               'user_updated' => $userid
            ]);

       DB::commit();
        
        return response()->json(["sms"=>true,"mensaje"=>"Se elimino correctamente"]);

      }catch(\Exception $e) 
      {
        DB::rollBack();
        return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
      }
   }
}
