/* */
//$("a[rel^='prettyPhoto']").prettyPhoto(); // ativação do prettyphoto
$(document).ready(function() {
/*------ Script Placeholder ------*/ 
	 if(!Modernizr.input.placeholder){

	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur();
	$('[placeholder]').parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	});

}
});



$( document ).ready(function() {

    $("span[rel*=tooltip]").tooltip({
        // live: true
    });

	//Menu
	$( "#side-menu" )
	.mouseenter(function() {
		$( ".side-menu-item" ).show(300);
	})
	.mouseleave(function() {
		$( ".side-menu-item" ).stop(true).hide(300);
	});

	//Efeitos
	$( "button" ).click(function() {
		//$( "p" ).toggle( );
	});

	$( "#btn-sign, #btn-unsubscribe" ).click(function() {
		$( "#btn-unsubscribe, #btn-sign" ).toggle();
	});

	$( "#request-simulated" ).click(function() {
		$( "#result-simulated" ).show();
		$( "#request-simulated" ).hide();
	});	

	//Tooltip
	$( "#alert-day-one" ).click(function() {
		$( ".show-test" ).show();
		$( ".show-chats" ).hide();
	});	
	$( "#alert-day-two" ).click(function() {
		$( ".show-chats" ).show();
		$( ".show-test" ).hide();
	});	
	$( "#alert-day-one" ).mouseenter(function() {
		$('#element').tooltip('show')
	});
	$( "#alert-day-two" ).mouseenter(function() {
		$('#element2').tooltip('show')
	});
});


/*//Contador
var mins = 10;  //Defina o número de minutos que você precisa
    var secs = mins * 60;
    var currentSeconds = 0;
    var currentMinutes = 0;
    setTimeout('Decrement()',1000);

    function Decrement() {
        currentMinutes = Math.floor(secs / 60);
        currentSeconds = secs % 60;
        if(currentSeconds <= 9) currentSeconds = "0" + currentSeconds;
        secs--;
        document.getElementById("timer").innerHTML = currentMinutes + ":" + currentSeconds; //Defina o ID do elemento que você precisa para colocar o tempo.
        if(secs !== -1) setTimeout('Decrement()',1000);
    }
    
*/
//Recuperar Senha
$( ".click-acess" ).click(function() {
  $( ".pass-login" ).show( "fast" );
  $( ".acess-login" ).hide( "fast" );
});
$( ".click-pass" ).click(function() {
  $( ".pass-login" ).hide( "fast" );
  $( ".acess-login" ).show( "fast" );
});

//esconder elementos
/*$( ".ticket" ).click(function() {
	$( ".forms-ticket" ).toggle( 'fast' );
});*/



//Mostrar escolas
/*$( ".click-thirst" ).click(function() {
  $( ".block-thirst" ).fadeIn( "fast", "linear" );
});

$( ".closed, .closed-block" ).click(function() {
  $( ".block-thirst" ).fadeOut( "fast", "linear" );
});*/

$(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
            $(this).toggleClass('open');
            $('b', this).toggleClass("caret caret-up");                
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
            $(this).toggleClass('open');
            $('b', this).toggleClass("caret caret-up");                
        }
    );
});