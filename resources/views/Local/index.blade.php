@extends('plantilla')
@section('content')

<style type="text/css">
#mapid {
    height: 300px;
    width: 100%;
}

#mapid_edit {
    height: 300px;
    width: 100%;
}
.pac-container {
    z-index: 1051 !important;
}
</style>

<section class="content" style="margin-top: 15px;">
    <div class="row"> 
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h2 class="card-title">Canales</h2>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">

                <button  type="button" title="Nuevo"  class="btn btn-primary" style="margin-bottom: 10px" data-toggle="modal" data-target="#modalcreate">Nuevo</button>   
       
                <div id="contenedor_principal" class="col-md-12" >



                </div>
               
              </div>
            </div>
          </div>
        </div>  
</section>




 <div class="modal fade" id="modalcreate" tabindex="-1" role="dialog" aria-labelledby="modalcreateTitle" aria-hidden="true"     data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="modal-content">
           
          <!-- //////////////////////////////////////////////////////// -->
          <form class="needs-validation" id="crear_local" autocomplete="off"  novalidate>
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Nuevo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">            
           
                <div class="form-group row">
                  <label for="ruc" class="col-form-label col-sm-3">RUC:</label>
                  <div class="col-sm-8">
                    <input class="form-control" type="text" placeholder="RUC" name="ruc" id="ruc" maxlength="14">
                     <div class="invalid-feedback">Ingrese RUC.</div> 
                  </div>
                </div>  

                <div class="form-group row">
                  <label for="nombre" class="col-form-label col-sm-3">Nombre:</label>
                  <div class="col-sm-8">
                      <input class="form-control" type="text"  placeholder="Nombre" name="nombre" id="nombre" required maxlength="150">
                      <div class="invalid-feedback">Ingrese Nombre.</div> 
                  </div>
                </div>
                <div class="form-group row">
                  <label for="nombre" class="col-form-label col-sm-3">Lista Precio:</label>
                  <div class="col-sm-7">
                   <select class="form-control select2" name="list_prec" id="list_prec">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		   </select>
                  </div>
                </div>

		<div class="form-group row">
                  <label for="nombre" class="col-form-label col-sm-3">Email:</label>
                  <div class="col-sm-8">
                      <input class="form-control" type="text"  placeholder="Email" name="email" id="email" required maxlength="250">
                      <div class="invalid-feedback">Ingrese Email.</div> 
                  </div>
                </div>
                <div class="form-group row">
                  <label for="contra" class="col-form-label col-sm-3">Contraseña:</label>
                    <div class="col-sm-7">
                      <div class="input-group mb-3">
                      <input class="form-control" type="password" name="contra" id="contra" required minlength="6">    
                        <div class="input-group-append">
                          <div class="input-group-text"  onclick="mostrarPassword();">
                            <span class="fa fa-eye-slash icon"></span>
                          </div>
                        </div> 
                      </div> 
                    </div>
                    <div class="invalid-feedback">Ingrese Contraseña, minimo 6 caracteres.</div> 
                </div>

              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" >Guardar</button>
            </div>
        </form>
        <!-- //////////////////////////////////////////////////////////////// -->
          </div>
        </div>
      </div>


      <div class="modal fade" id="modale" tabindex="-1" role="dialog" aria-labelledby="modaleditTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
       <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="modal-content" id="vistamodal_edit">               
          </div>
       </div>
      </div>

  @stop
  @section('script')
 <script type="text/javascript">

    $('#superv').select2({
      theme: 'bootstrap4'
    })



    function consultar_tabla(){  
        $("#contenedor_principal").html("<div style='text-align:center'><img src='{{asset('/dist/img/espera.gif')}}' style='pointer-events:none' width='300'  height='200' /></div>");


         var qw = '<table id="Local" class="table display responsive table-bordered table-striped" style="width:100%">';  
      
        cursor_wait();
        $.get("{{asset('')}}local/consultar").then((data)=> {
            $('#contenedor_principal').html(qw);
            $("#Local").DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "zeroRecords": "No hay registros...",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrados de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Sigue",
                        "previous": "Previo"
                    },
                },
                columnDefs: [
                  { width: 40, targets: 0 },
                  { width: 80, targets: 1 }
                ],
                "responsive": true,
                columns:data.titulos,
                data:data.sms
            });
                     remove_cursor_wait();
        });
    }

    consultar_tabla();


    var form=document.getElementById('crear_local');
    
    form.addEventListener('submit', (event) => {

    event.preventDefault();

      if (!form.checkValidity()) {
        event.stopPropagation();
        form.classList.add('was-validated');
      }else {
        const crear_sup = new FormData(form); 
            $.ajax({
                url:"{{asset('')}}local",
                headers :{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: crear_sup,
                success:function(res){
                    if(res.sms){
                         consultar_tabla();
                         $('#modalcreate').modal('hide');
                         $("#modalcreate input").val("");
                         $("#modalcreate textarea").val("");
                         toastr.success(res.mensaje);
                    }
                    else{               
                        Swal.fire({
                            closeOnClickOutside:false,
                            title: res.mensaje,
                            icon: "error",
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK',
                        });
                   }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (errorThrown=='Unauthorized') {
                      location.reload();
                    }
                }
            });   
            form.classList.remove('was-validated');   
        }
        
    }, false);


    function mostrarmodal(id){
        cursor_wait();
        $('button[name=editar]').attr('disabled',true);
        $("#vistamodal_edit").load("{{asset('')}}local/"+id+"/edit");
    }


    function elim(id){
      Swal.fire({
        closeOnClickOutside:false,
        title: "Aviso !",
        text: "Desea eliminar este registro ? ",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
            $.ajax({
            url:"{{asset('')}}local/"+id,
            headers :{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'DELETE',
            dataType: 'json',
            success:function(res){
              if(res.sms){
                  consultar_tabla();
                   toastr.success(res.mensaje); 
              }else{
                 Swal.fire({
                  closeOnClickOutside:false,
                  title: res.mensaje,
                  icon: "error",
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK',
               });
              }
            }
          })   
            }
      })
    }

  function mostrarPassword(){
    var cambio = document.getElementById("contra");
    if(cambio.type == "password"){
      cambio.type = "text";
      $('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
    }else{
      cambio.type = "password";
      $('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
    }
  } 

  </script>

  @endsection