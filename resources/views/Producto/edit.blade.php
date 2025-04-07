<meta name="csrf-token" content="{{ csrf_token() }}">
<form  class="needs-validation" id="edit_producto" autocomplete="off" novalidate>
   @csrf 
   {{ method_field('PUT') }}
    <div class="modal-header">
  
    <h4 class="modal-title">Editar</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">   
            <div class="col-md-12">

<input class="form-control" type="hidden" name="idunic" id="idunic" readonly="readonly"  value="{{$result_edit->id}}">
               
                <div class="form-group row">
                  <label for="descripcion_edit" class="col-form-label col-sm-3">Descripción:</label>
                  <div class="col-sm-7">
                    <textarea class="form-control" name="descripcion_edit" id="descripcion_edit" cols="30" rows="3" required>{{$result_edit->descripcion}}</textarea>  
                     <div class="invalid-feedback">Ingrese Dirección.</div> 
                  </div>
                </div>

                <div class="form-group row">
                  <label for="valor_premio_edit" class="col-form-label col-sm-3">Precio 1:</label>
                  <div class="col-sm-8">
                      <input class="form-control" type="text" placeholder="Precio 1" name="valor_premio_edit" id="valor_premio_edit" onkeypress="return Lim_index(event,this);" required pattern="[0-9]{1,4}([.]{1}?([0-9]{1,2})?)?" value="{{$result_edit->valor_premio}}">
                    <div class="invalid-feedback">Ingrese Precio.</div> 
                  </div>
                </div>
                   <div class="form-group row">
                  <label for="valor_premio_edit" class="col-form-label col-sm-3">Precio 2:</label>
                  <div class="col-sm-8">
                      <input class="form-control" type="text" placeholder="Precio 2" name="valor_premio_edit2" id="valor_premio_edit2" onkeypress="return Lim_index(event,this);" required pattern="[0-9]{1,4}([.]{1}?([0-9]{1,2})?)?" value="{{$result_edit->valor_premio2}}">
                    <div class="invalid-feedback">Ingrese Precio.</div> 
                  </div>
                </div>
   		<div class="form-group row">
                  <label for="valor_premio_edit" class="col-form-label col-sm-3">Precio 3:</label>
                  <div class="col-sm-8">
                      <input class="form-control" type="text" placeholder="Precio 3" name="valor_premio_edit3" id="valor_premio_edit3" onkeypress="return Lim_index(event,this);" required pattern="[0-9]{1,4}([.]{1}?([0-9]{1,2})?)?" value="{{$result_edit->valor_premio3}}">
                    <div class="invalid-feedback">Ingrese Precio.</div> 
                  </div>
                </div>

                <div class="form-group row">
                  <label for="vigencia_edit" class="col-form-label col-sm-3">Vigencia:</label>
                  <div class="col-sm-8">
                    
                    @php
                    if(is_null($result_edit->vigencia))
                    $fec_vigencia=null;
                    else
                    $fec_vigencia= date('Y-m-d', strtotime($result_edit->vigencia))
                    @endphp
                      <input class="form-control" type="date" placeholder="Vigencia" name="vigencia_edit" id="vigencia_edit" value="{{$fec_vigencia}}">
                  </div>
                </div>
                

            </div>

    </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" >Guardar</button>
            </div>
</form>

<script type="text/javascript">


    remove_cursor_wait();
    $('#modale').modal();
    $('button[name=editar]').attr('disabled',false);




    var form2=document.getElementById('edit_producto');

    form2.addEventListener('submit', (event) => {
     event.preventDefault();
      if (!form2.checkValidity()) {
        event.stopPropagation();
      }else {
        const edit_sup = new FormData(form2); 
            $.ajax({
                url:"{{asset('')}}producto/{{$result_edit->id}}",
                type: 'POST',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: edit_sup,
                success:function(res){
                    if(res.sms){
                         consultar_tabla(); 
                         $('#modale').modal('hide');
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
        }
        form2.classList.add('was-validated');
    }, false);


    function Lim_index(evt, input) {
            var key = window.Event ? evt.which : evt.keyCode;
            var chark = String.fromCharCode(key);
            var tempValue = input.value + chark;
 

            if (key==46 || (key >= 48 && key <= 57)  ) {
                if (filter_index(tempValue) === false) {
                    return false;
                } else {                  
                    return true;
                }
            } else {
                if (key == 8 || key == 13 || key == 0 || key == 188) {
                    return true;
                }  else {
                    return false;
                }
            }
        }


        function filter_index(_val_) {
            var regexp = /^[0-9]{1,4}([.]{1}?([0-9]{1,2})?)?$/;

            if (regexp.test(_val_) === true) {
                return true;
            } else {
                return false;
            }

        }

</script>