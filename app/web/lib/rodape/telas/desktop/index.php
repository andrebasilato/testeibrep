<div class="container footerMatricula mt50">
    <div class="row">
        <div class="col-sm-12">
            <p><?= $idioma['mais_informacoes']; ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">            
                <p>
                    IBREP <br>
                    Telefone: (48) 98811-1125<br />
                    E-mail: atendimento@ibreptran.com.br
                </p>
        </div>
    </div>
    <div class="row">
        <div class="apoio mt50">
            <div class="copyright col-sm-8">
                <p><?= $idioma['descricao_site']; ?></p>
            </div>
            <div class="col-sm-4 marcaAlfama">
                <a href="http://alfamaweb.com.br" class="logo" target="_blank">
                    <span class="m1 big"><img src="/assets/loja/img/m1.png"></span><span class="m2 big"><img src="/assets/loja/img/m2.png"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="/assets/loja/js/jquery.js"></script>
<script src="/assets/loja/js/bootstrap.js"></script>
<script src="/assets/loja/js/prefixfree.min.js"></script>
<script src='/assets/loja/js/jquery.cycle2.min.js'></script>
<script src='/assets/loja/js/jquery.cycle2.carousel.min.js'></script>
<script src="/assets/loja/js/linceform/linceform.js"></script>
<script src="/assets/loja/js/velocity.min.js"></script>

<!-- //DELETAR QUANDO O SITE FOR PRO AR -->
<script src="http://www.sauloduarte.com/extra/responsive.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-61Q7DH8YXN"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-61Q7DH8YXN');
</script>

<script>
    //MENUXS
    function openNav() {
        document.getElementById("myNav").style.height = "100%";
    }
    function closeNav() {
        document.getElementById("myNav").style.height = "0%";
    }


    //MENU FLOAT

    $(document).ready(function(){
        $(window).scroll(chamandoScroll);
        chamandoScroll();
    })
    function chamandoScroll(){
        // console.log('scroll');
        var pos = $(document).scrollTop();

        if(pos > 100){
            $('.menuTopo').addClass("float");
        } else {

            $('.menuTopo').removeClass("float");
        }
        console.log();
    }

    //MAPA
    var itemMapa = "bahia";
    $('.btMapa').click(function(){
        $("#"+itemMapa).attr('class', '');
        $(this).attr('class', 'active');
        itemMapa = $(this).attr('id');

        $(".infoMapa").css("opacity", "1");
    });

    //MARCA ALFAMA
    (function(){

        var logo = $(".logo");
        var m1 = $(".logo .m1");
        var m2 = $(".logo .m2");
        var easings = ["easeOutQuad","easeInOutQuad","easeInOutBack","easeOutElastic","easeOutBounce"];
        var values = [[20,180,0],[170,170,0],[20,360,0],[350,0,0],[0,40,360],[0,320,0],[0,180,0],[180,180,0]];

        m1.colh = [100,110,120];
        m2.colh = [255,192,0]

        logo.hover(function(){
            m1.logoanim(1);
            m2.logoanim(2);
        }, function(){
            m1.velocity("reverse");
            m2.velocity("reverse");
        });

        $.fn.logoanim = function(item) {

            var duration = 250;

            var a = 5;

            var e = 3;
            var easing = easings[e];
            if(e >= 2) {duration *= 2}


            if(item==1){

                $(this).velocity({
                    rotateX: values[a][0] * 1,
                    rotateY: values[a][1] * 1,
                    rotateZ: values[a][2] * 1,
                    colorRed : this.colh[0],
                    colorGreen : this.colh[1],
                    colorBlue : this.colh[2]
                },{
                    duration: duration,
                    easing: easing
                });
            }else{
                $(this).velocity({
                    rotateX: values[a][0] * -1,
                    rotateY: values[a][1] * -1,
                    rotateZ: values[a][2] * -1,
                    colorRed : this.colh[0],
                    colorGreen : this.colh[1],
                    colorBlue : this.colh[2]
                },{
                    duration: duration,
                    easing: easing
                });
            }
        }
        $(document).ready(function() {
            m1.logoanim(1);
            m1.velocity("reverse");
            m2.logoanim(2);
            m2.velocity("reverse");
        });

    })();

    <?php //Desabilita o botão direito do mouse do usuário ?>
    $(document).bind("contextmenu",function(e){
        return false;
    });
</script>