<style>
#opcoesbusca{
list-style: none;
padding: 0;
margin: 0 0 12px 0;

}
#opcoesbusca li a {
color: #000000;
background-repeat: no-repeat;
background-position: 2px 2px;
font-size: 10px;
padding-bottom: 8px;
margin-bottom: 5px;
}
#opcoesbusca li a {
font-size: 10px;
color: #00000;
display: block;
outline: 0;
text-decoration: none;
text-transform: uppercase;
}
#opcoesbusca .microMenu  {
	font-size: 9px !important;
	background-color: #D7E8FD !important;
	padding: 7px 7px 0px 7px !important;
	display: block !important;
	border-radius: 3px !important;
	margin-left: 10px !important;
	margin-top: 0px !important;
}
#opcoesbusca .menuOutros a {
	font-size: 9px !important;
	background-color: #EEEEEE !important;
	padding: 4px !important;
	display: block !important;
	border-radius: 3px !important;
	margin-left: 10px !important;
	margin-top: 0px !important;
}

</style>
<div class='section section-small'>
  <div class='section-header'>
    <h5> <?php echo $idioma['quem_busca']; ?> </h5>
  </div>
  <div class='section-body'>
    <div class='row-fluid'>      
      <div style="padding: 5px 10px 0px 7px;" class='span12'>
  			<div class="input-prepend">
 		    		 <span class="add-on"><i class="icon-search"></i></span>
         <input id="buscafuncaosistema"  placeholder="Digite aqui..."  style="width: 82%;" type="text"  >
           </div>
      </div>
    </div>
	</div>
  <div style="display:none;margin-top:-10px" id="resultadobuscafuncao" class='section-body'>
 	<ul id="opcoesbusca">
    	
    </ul>
    
  </div>
</div>
<script>

$('#buscafuncaosistema').keydown(function(){ 
		var palavra = $('#buscafuncaosistema').val();
		if(palavra.length >=2){
		   jQuery.ajax({
				url: "/api/get/busca",
				dataType: "json", //Tipo de Retorno
				type: "POST",
				data: {'palavra': palavra,'painel': 'G'},
				success: function(json){ //Se ocorrer tudo certo
						$("#resultadobuscafuncao").show();
						var valor = '';
						var i =0;
						$.each(json,function(index, value){
							if( i ==0){
								valor = valor + '<li><a href="/<?=$url[0]?>'+value['link']+'">Você deseja <strong>"'+value['nome']+'" </strong> ?</a></li>'+
												'<li class="microMenu"> <a href="/<?=$url[0]?>'+value['link']+'"><i class="icon-arrow-right"></i> &nbsp;<strong>'+value['descricao']+'</strong></a></li>'+
												'<li><br><a>Outras opções: </a></li>';
							}else{
								valor = valor + '<li class="menuOutros"><a  href="/<?=$url[0]?>'+value['link']+'"><i class="icon-arrow-right"></i>&nbsp; '+value['nome']+'</a></li>'; 
							}
							
							 i++;
							 if(i == 6){
								return false;
							 }
						});	  
						$("#opcoesbusca").html(valor);
				} 	
		   });
		}else{
			$("#resultadobuscafuncao").hide();
		}
});
</script>

