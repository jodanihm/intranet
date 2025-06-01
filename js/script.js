$( document ).ready(function() {
    $('body').hide();
    var urlActual = window.location.href;
    var nombreArchivo = urlActual.split('/').pop();
    var parametros = {
    "accion"   : "seguridad",
    "pag" : nombreArchivo
    };
    $.ajax({            
        data:  parametros,
        url: 'php/general.php',
        type: 'post',  
        success: function (response) {
            console.log(response);
            if (response == 'denegado') {
                salir();
            }if (response == 'aceptado'){
                $('body').show();
            }
        }
    })

    $.ajax({            
        
        url: 'php/menu_lateral.php',
         
        success: function (response) {
            //console.log(response);
            $("#menu_lateral").html(response);
        }
    })
});
function salir(){
    var parametros = {
        "accion"   : "salir"
    };
    $.ajax({              
        data:  parametros,
        url: 'php/general.php',
        type: 'post',  
        success: function (response) {
            console.log(response);
            if (response == "salir") {
                var cookies = document.cookie.split(";");
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i];
                    var eqPos = cookie.indexOf("=");
                    var nombre = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                    document.cookie = nombre + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
                }
                localStorage.clear();
                location.assign("index.html");
            }
            
        }
    })
}; 
function detalle_solcitud_general(id)
    {
        var valor = $('#carga-modal-general').data('type');
        //console.log(valor);

        parametros = {
            "accion":'detalle-solicitud',
            "id":id,
            "tipo":valor
        };
        $.ajax({
            url: "php/general.php",
            type: "post",
            data: parametros,

            success: function(response) 
            {
                $('#carga-modal-general').html(response);
                const myModalAlternative = new bootstrap.Modal('#myModal-detalle');
                myModalAlternative.show();
            }
        });
    };
    function detalle_solcitud_general2(id)
    {
        var valor = $('#carga-modal-general').data('type');
        //console.log(valor);

        parametros = {
            "accion":'detalle-solicitud2',
            "id":id,
            "tipo":valor
        };
        $.ajax({
            url: "php/general.php",
            type: "post",
            data: parametros,

            success: function(response) 
            {
                $('#carga-modal-general').html(response);
                const myModalAlternative = new bootstrap.Modal('#myModal-detalle');
                myModalAlternative.show();
            }
        });
    };
    function detalle_solcitud_na(id)
    {
        var valor = $('#carga-modal-general').data('type');
        //console.log(valor);
        parametros = {
            "accion":'detalle-solicitud-na',
            "id":id,
            "tipo":valor
        };
        $.ajax({
            url: "php/general.php",
            type: "post",
            data: parametros,

            success: function(response) 
            {
                $('#carga-modal-general').html(response);
                const myModalAlternative = new bootstrap.Modal('#myModal-detalle');
                myModalAlternative.show();
            }
        });
    };

   function enviarCorreo(dest, asunto, cuerpo) {
        parametros = {
            "destinatario":dest,
            "asunto":asunto,
            "cuerpo":cuerpo
        };
     
        $.ajax({
           type: 'POST',
           url: 'php/enviar_correo.php',  // Archivo PHP que procesa el envío del correo
           data: parametros,
           success: function(response) {
            console.log (response);
                if (response == 1) {
                    console.log("correo enviado correctamente");
                }
              if (response == 0){console.log("Error en envío de correo")}
           }
        });
     }

    function cambia_estado(estado,id) {
        var contenido = $( "#texto-confirmado" ).val();
        var text = contenido.replace(/\n/g, '<br>');
        var parametros = {
          "accion"   : "3",
          "estado" : estado,
          "texto" : text,
          "id" : id,
          };
          $.ajax({            
              data:  parametros,
              url: 'php/general.php',
              type: 'post',  
              success: function (response) {
                $( "#btn-cerrar-modal" ).trigger( "click" );
                var urlActual = window.location.href;
                var nombreArchivo = urlActual.split('/').pop();
                console.log (nombreArchivo,estado);
                if (nombreArchivo == "ingreso.html" && estado == 'Pendiente') {
                    muestra_datos();

                    var asunto = 'Nueva solicitud pendiente';
                    var cuerpo = `El nuevo id generado es: ${id} `;
                    //enviarCorreo(response[3], asunto, cuerpo);
                    enviarCorreo("dhernandez@gmail.com", asunto, cuerpo);
                       
                }

                if (nombreArchivo == "impresion.html") {
                        carga_numeros();
                        var n = "";
                        if (response == 'Pendiente') {
                            n="pendiente";
                        }
                        if (response == 'En proceso') {
                            n="enproceso";
                        }
                        if (response == 'Producto impreso') {
                            n="impresos";
                        }
                        if (response == 'En prensa') {
                            n="enprensa";
                        }
                        if (response == 'Reparacion') {
                            n="reparacion";
                        }
                        if (response == 'Reimpresion') {
                            n="reimp";                
                        }
                        $(".tab-pane").removeClass("show active");
                        $("#list-"+n).addClass("show active");

                        $(".list-group-item").removeClass("active");
                        $("#list-"+n+"-list").addClass("active");
                        muestra_datos(response);
                }else if (nombreArchivo == "recep_entre.html") {
                    if (response == "Entregado") {
                        //funcion envia correo
                        muestra_datos();
                        data = {
                            "id_solicitud":id,
                            "accion": "extraer_paciente"
                        };
                        $.ajax({
                           type: 'POST',
                           url: 'php/general.php',  
                           data: data,
                           dataType: 'json',
                           success: function(response) {
                                console.log(response[3]);
                                var asunto = '¡Exclusiva Oferta para Tu Comodidad! 30% de descuento';
                                var cuerpo = '<img src="https://admin.plantiflex.cl/img/logo800.png" alt=""><br><br>' +  
                                              '<p style="text-align: justify;">Estimado/a '+response[1]+',<br><br>'+
                                              'Espero que este mensaje te encuentre bien. Queremos agradecerte por confiar en nosotros y adquirir un par de nuestras plantillas ortopédicas'+
                                              'Esperamos que estén cumpliendo con tus expectativas y brindándote el soporte necesario. <br><br>' +
                                              'Nos complace informarte que, como agradecimiento, queremos ofrecerte una exclusiva '+
                                              'promoción: <b>¡un segundo par de plantillas con un 30% de descuento!</b> Además, tienes la opción de elegir un color diferente para el forro de estas nuevas plantillas.<br><br>'+
                                              'Esta oferta es válida exclusivamente para clientes como tú, que han confiado en nosotros para cuidar de su bienestar.'+
                                              'Creemos que contar con un par adicional puede ser beneficioso, ya sea para cambiar según el estilo de tus zapatos o simplemente como respaldo.<br><br>'+
                                              'Para aprovechar esta oferta, simplemente <b>responde a este correo</b> o al escribenos al siguiente <a href="https://wa.me/56978542249?text=Hola,%20estoy%20interesado%20en%20adquirir%20mi%20segundo%20par%20de%20plantillas.">whatsapp</a> especificando el color de forro que deseas para tu segundo par,'+
                                              'y nos encargaremos de procesar tu pedido con el descuento aplicado.<br><br>'+
                                              'Agradecemos tu preferencia y esperamos seguir siendo parte de tu camino hacia una mayor comodidad y bienestar.<br><br>'+
                                              'Quedamos a tu disposición para cualquier pregunta o solicitud adicional.<br><br>'+
                                              '¡Gracias por elegirnos!<br><br>'+
                                              'Atentamente,<br>'+
                                              'PLANTIFLEX SpA</p>';
                                enviarCorreo(response[3], asunto, cuerpo);
                           }
                        });
                        
                    }else{
                        muestra_datos();
                    }
                    
                }else{
                    muestra_datos();
                }
              }
          });
        
      }
      function cancelar(estado,id) {
        var text = $( "#texto-cancelado" ).val();
        //console.log(text);
        
        if (text != "") {
          var parametros = {
          "accion"   : "3",
          "estado" : estado,
          "texto" : text,
          "id" : id,
          };
          $.ajax({            
              data:  parametros,
              url: 'php/general.php',
              type: 'post',  
              success: function (response) {
                console.log(response);
                $( "#btn-cerrar-modal" ).trigger( "click" );
                var urlActual = window.location.href;
                var nombreArchivo = urlActual.split('/').pop();

              if (nombreArchivo == "ingreso.html" && estado == 'Pendiente') {
                    muestra_datos();

                    var asunto = 'Nueva solicitud pendiente';
                    var cuerpo = `El nuevo id generado es: ${id} `;
                    //enviarCorreo(response[3], asunto, cuerpo);
                    enviarCorreo("dhernandez@gmail.com", asunto, cuerpo);
                       
                }
                
                if (nombreArchivo == "impresion.html") {
                        carga_numeros();
                        var n = "";
                        if (response == 'Pendiente') {
                            n="pendiente";
                        }
                        if (response == 'En proceso') {
                            n="enproceso";
                        }
                        if (response == 'Producto impreso') {
                            n="impresos";
                        }
                        if (response == 'En prensa') {
                            n="enprensa";
                        }
                        if (response == 'Reparacion') {
                            n="reparacion";
                        }
                        if (response == 'Reimpresion') {
                            n="reimp";                
                        }
                        $(".tab-pane").removeClass("show active");
                        $("#list-"+n).addClass("show active");

                        $(".list-group-item").removeClass("active");
                        $("#list-"+n+"-list").addClass("active");

                        muestra_datos(response);
                }else{
                    muestra_datos();
                }
              }
          });
        }else{
          alert("Debe ingresar el motivo por el cual esta cancelando la solicitud.");
        }
      }

      function checkRut(rut) {
        // Despejar Puntos
        var valor = rut.value.replace('.','');
        // Despejar Guión
    
        valor = valor.replace('-','');
        
        // Aislar Cuerpo y Dígito Verificador
        cuerpo = valor.slice(0,-1);
        dv = valor.slice(-1).toUpperCase();
        
        // Formatear RUN
        rut.value = cuerpo + '-'+ dv
        
        // Si no cumple con el mínimo ej. (n.nnn.nnn)
        if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}
        
        // Calcular Dígito Verificador
        suma = 0;
        multiplo = 2;
        
        // Para cada dígito del Cuerpo
        for(i=1;i<=cuerpo.length;i++) {
        
            // Obtener su Producto con el Múltiplo Correspondiente
            index = multiplo * valor.charAt(cuerpo.length - i);
            
            // Sumar al Contador General
            suma = suma + index;
            
            // Consolidar Múltiplo dentro del rango [2,7]
            if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
      
        }
        
        // Calcular Dígito Verificador en base al Módulo 11
        dvEsperado = 11 - (suma % 11);
        
        // Casos Especiales (0 y K)
        dv = (dv == 'K')?10:dv;
        dv = (dv == 0)?11:dv;
        
        // Validar que el Cuerpo coincide con su Dígito Verificador
        if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }
        
        // Si todo sale bien, eliminar errores (decretar que es válido)
        rut.setCustomValidity('');
    }

    function imprimirEtiqueta() {
        // Obtener el contenido del div que queremos imprimir
        console.log("hola");
        var contenido = document.getElementById('etiqueta').innerHTML;
        
        // Crear una nueva ventana para la impresión
        var ventanaImpresion = window.open('', '_blank', 'height=400,width=600');
        
        // Agregar el contenido que se va a imprimir
        ventanaImpresion.document.write('<html><head><title>Etiqueta</title></head><body><center>');
        ventanaImpresion.document.write(contenido);
        ventanaImpresion.document.write('</center></body></html>');
        
        // Llamar al método de impresión
        ventanaImpresion.print();
        
        // Cerrar la ventana después de imprimir
        ventanaImpresion.close();
      }
       function tipoTexto(tipo) {
    switch(tipo) {
        case 0: return "Administrador";
        case 1: return "Kinesiólogo";
        case 2: return "Impresor";
        case 3: return "Kinesiólogo Jefe";
        default: return "Desconocido";
    }
}
function createToastContainer() {
  if (!$("#toastContainer").length) {
      $("body").append('<div id="toastContainer" class="position-fixed  top-0 end-0 p-3" style="z-index: 1060;"></div>');
  }
}


function alertToast(status, mensaje) {
  createToastContainer();

  var toastId = 'liveToast' + new Date().getTime(); // Generar un ID único para cada toast
  const now = new Date();
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');

  if (status == 'info') {
    var icon = '<i class="bi bi-info-circle-fill px-2 text-info"></i>';
  }
  if (status == 'success') {
    var icon = '<i class="bi bi-check-circle-fill px-2 text-success"></i>';
  }
  if (status == 'danger') {
    var icon = '<i class="bi bi-x-circle-fill px-2 text-danger"></i>';
  }
  if (status == 'warning') {
    var icon = '<i class="bi bi-exclamation-circle-fill px-2 text-warning"></i>';
  }
  

  var html = `
    <div id="${toastId}" class="toast border-${status}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header ">
        ${icon}
        <strong class="me-auto text-${status}">PLantiflex INFO: </strong>
        <small>${hours}:${minutes}</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <strong class="text-dark">${mensaje}</strong>
      </div>
    </div>`;

  $("#toastContainer").append(html);

  var toastElement = $('#' + toastId);
  var toast = new bootstrap.Toast(toastElement[0]);
  toast.show();

  setTimeout(function() {
    toast.hide();
  }, 5000);

  toastElement.on('hidden.bs.toast', function() {
    toastElement.remove();
  });
}