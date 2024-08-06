function mCPF(cpf){
    cpf=cpf.replace(/\D/g,"")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    return cpf
  }
  
  function fMasc(objeto,mascara) {
    obj=objeto
    masc=mascara
    setTimeout("fMascEx()",1)
  }
  
  function fMascEx() {
    obj.value=masc(obj.value)
  }
  
  function ValidaCPF(){	
    var RegraValida=document.getElementById("cpf").value; 
    var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/;	 
    if (cpfValido.test(RegraValida) == true)	{ 
      console.log("CPF Válido");	
    } else	{	 
      console.log("CPF Inválido");	
    }
  }
  
  // Adiciona evento de blur para validar o CPF
  document.getElementById("cpf").addEventListener("blur", ValidaCPF);