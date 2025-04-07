<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use App\Models\Local;
use App\Models\Vendedor;
use Validator;
use Input;

class LocalController extends Controller
{
    public function index(){
        return view('Local.index');
    }
   public function consulta_data(){

      $result=Local::whereNull('deleted_at')
            ->orderBy('id')
            ->get();
      
      $titulos = [];
      $titulos[] = array('title' => '');
      $titulos[] = array('title' => 'Acciones');
      $titulos[] = array('title' => 'id');
      $titulos[] = array('title' => 'Ruc');
      $titulos[] = array('title' => 'Nombre');
      $titulos[] = array('title' => 'ListaPrecio');
      $titulos[] = array('title' => 'Email');

      $jsonenv=[];
      
        foreach ($result as $res) {
          
         $boton_up=' <button  title="editar" class="btn btn-success" name="editar" onclick="mostrarmodal('.$res->id.');"><i class="fa fa-edit"></i> </button>';
    
         $boton_elim=' <button title="eliminar" class="btn btn-danger" name="eliminar" onclick="elim('.$res->id.');"><i class="fa fa-trash"></i> </button>';


   
         $button= $boton_up.''.$boton_elim;

         $jsonenvtemp = ['',$button,$res->id,$res->ruc,$res->nombre,$res->ListaPrecio,$res->email];

          array_push($jsonenv, $jsonenvtemp);
        }

       return response()->json(["sms"=> $jsonenv, "titulos"=>$titulos]);   
   }

    public function store(Request $request){
        $userid = \Auth::id();
        $v = Validator::make($request->all(),[
              'ruc'=>"required|unique:local,ruc",
            ]);

        if($v->fails()){
          $mensajedereturn=strtoupper($v->errors()->first('ruc'));

          return response()->json(["sms"=>false ,"mensaje" => $mensajedereturn]);     
        }


        try {

            DB::beginTransaction();

            $id=Local::insertGetId(
            [ 'ruc'=>$request->ruc,
              'nombre'=>$request->nombre,
              'updated_at' =>now(),
              'created_at' =>now(),
              'user_updated' => $userid,
              'ListaPrecio'=>$request->list_prec,
	      'email'=>$request->email,
              'password' => Hash::make($request->contra),
	      'dfa' =>0  
            ]);
            
            DB::commit();
                
            return response()->json(["sms"=>true,"mensaje"=>"Se creo correctamente"
]);                    
        }catch(\Exception $e){

            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
        }
    }
    public function edit($id){
        $result_edit=Local::where('id',$id)->first();
      return view('Local.edit', compact('result_edit'));
    }

    public function update(Request $request){
        $userid = \Auth::id(); 
       
        try {
          DB::beginTransaction();
         
          $cons_insp_cab= Local::where('id', '=', $request->idunic)
          ->update(['updated_at' =>now(), 
                    'nombre'=>$request->nombre_edit,
                    'user_updated' => $userid,
		    'ListaPrecio'=>$request->list_prec_edit,
                    'email'=>$request->email_edit,
		    'password' => Hash::make($request->contraAct)]);

            DB::commit();
                
            return response()->json(["sms"=>true,"mensaje"=>"Se edito correctamente"]);                

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
        }
    }
    public function destroy($id){
        $userid = \Auth::id();

        $tien_vend= Vendedor::where("id_local", $id)
                ->whereNull('deleted_at')
                ->count();

         if($tien_vend>0){
            return response()->json(["sms"=>false ,"mensaje" => "Este local cuenta con vendedores activos"]);
         }  
         
        try {

            DB::beginTransaction();

            Local::where('id', $id)->update([
               'updated_at' =>now(),
               'deleted_at' =>now(),
               'user_updated' => $userid
            ]);

            DB::commit();
        
            return response()->json(["sms"=>true,"mensaje"=>"Se elimino correctamente"]);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(["sms"=>false,"mensaje"=>$e->getMessage()]);                 
      }
    }
}
