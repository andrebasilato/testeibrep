
var wfBox = function(db,flags){ //id,posX,posY,color,label

    this.db = db;

    this.id = this.db.idapp;
    this.posX = parseFloat(this.db.posicao_x);
    this.posY = parseFloat(this.db.posicao_y);
    this.width = 100;
    this.height = 30;
    this.corner = 5;
    this.clicked = false;
    this.order = false;
    this.flags = flags;
    this.setas = {'pt1':false,'pt2':false,'pb1':false,'pb2':false};

    //console.log(this.db.posicao_y);

    this.visibleOrder = function(status){
        this.order = status;
    }

    this.draw = function(ctx){

       // console.log(this.flags);
        var ptMarg = 15;

        this.posX = parseFloat(this.db.posicao_x);
        this.posY = parseFloat(this.db.posicao_y);


        ctx.font="bold 12px Arial";
        if(this.db.nome == null){
           this.db.nome = 'Novo Box';
        }

        var textWidth  = ctx.measureText(this.db.nome).width;
        var siglaWidth = ctx.measureText(this.db.sigla.toUpperCase()).width;
        var ordemWidth  = ctx.measureText(this.db.ordem).width;

        this.width = siglaWidth + 10 + textWidth + 20 + (30);

        var legendaFlag = '';
        for(var i in flags){
            var tempFlag = flags[i];
            if(this.db[i] == 'S'){
                legendaFlag += ', '+tempFlag;
            }
        }
        legendaFlag =legendaFlag.substr(2);

        if(legendaFlag){
            var flagWidth = ctx.measureText(legendaFlag).width;
            ctx.fillStyle = '#c4c4c4';
            ctx.fillRect(this.posX-(flagWidth+20), this.posY+( this.corner/2), flagWidth+20 , this.height- this.corner);
            ctx.fillStyle = '#D1D1D1';
            ctx.fillRect(this.posX-(flagWidth+20), this.posY+( this.corner/2), flagWidth+20 , (this.height/2)-2);

            ctx.fillStyle='#FFF';
            ctx.fillText(legendaFlag, this.posX - ( flagWidth+10),this.posY+(this.height/2)+6);
            ctx.fillStyle='#000';
            ctx.fillText(legendaFlag, this.posX - ( flagWidth+10),this.posY+(this.height/2)+5);
        }


        if(this.order ){
            ctx.fillStyle = '#c4c4c4';
            //ctx.fillRect(this.posX+(this.width/2)-(ordemWidth/2)-10, this.posY-25, ordemWidth+20 , 20);
            ctx.roundRect(this.posX+(this.width/2)-(ordemWidth/2)-10, this.posY-25, ordemWidth+20 , 22, 5).fill();
            ctx.fillStyle='#000';
            ctx.fillText(this.db.ordem, this.posX+(this.width/2)-(ordemWidth/2),this.posY-10);
        }



        ctx.lineJoin = "round";
        ctx.fillStyle='#'+this.db.cor_bg;///is.color;

        if(this.clicked){
            ctx.strokeStyle='#515050';
        } else{
            ctx.strokeStyle='#'+this.db.cor_bg;
        }

        ctx.fillRect(this.posX+( this.corner/2), this.posY+( this.corner/2), this.width- this.corner, this.height- this.corner);

        var grd=ctx.createLinearGradient(this.posX,this.posY,this.posX,this.posY+this.height);
        grd.addColorStop(1,"black");
        grd.addColorStop(0,'#'+this.db.cor_bg);
        ctx.fillStyle=grd;
        ctx.globalAlpha=.2;
        ctx.fillRect(this.posX+( this.corner/2), this.posY+( this.corner/2), this.width- this.corner, this.height- this.corner);
        ctx.globalAlpha=1;

        ctx.fillStyle='#'+this.db.cor_nome;
        // ctx.font = "bold 14px Arial";
        ctx.fillText(this.db.sigla.toUpperCase(),this.posX + 10,this.posY+(this.height/2)+5);
        ctx.fillText(this.db.nome, this.posX + ( siglaWidth+20),this.posY+(this.height/2)+5);


        ctx.lineWidth = 3;
        if(this.clicked){
            ctx.strokeStyle='#515050';
        } else{
            ctx.strokeStyle='#'+this.db.cor_bg;
        }
        ctx.strokeRect(this.posX+( this.corner/2), this.posY+( this.corner/2), this.width- this.corner, this.height- this.corner);




        ctx.strokeStyle='#000';
        ctx.lineWidth = 1;
        ctx.globalAlpha=.2;
        ctx.beginPath();
        ctx.moveTo(this.posX + ( siglaWidth+15),this.posY+1);
        ctx.lineTo(this.posX + ( siglaWidth+15), this.posY + this.height-1);
        ctx.stroke();

        ctx.strokeStyle='#FFF';
        ctx.beginPath();
        ctx.moveTo(this.posX + 1 + ( siglaWidth+15),this.posY+1);
        ctx.lineTo(this.posX + 1 + ( siglaWidth+15), this.posY + this.height+1);
        ctx.stroke();

        ctx.globalAlpha=1;


        ctx.beginPath();
        ctx.arc(this.posX + this.width-15, this.posY+(this.height/2), 6, 0, 2 * Math.PI, false);
        ctx.fillStyle = '#E46402';
        ctx.fill();
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#FFF';
        ctx.stroke();







        if(this.setas.pt1){
            ctx.beginPath();
            ctx.moveTo(this.posX+ptMarg, this.posY);
            ctx.lineTo(this.posX+ptMarg+5,this.posY-6);
            ctx.lineTo(this.posX+ptMarg-5,this.posY-6);
            ctx.fill();
        }
        /*if(this.setas.pt2){
            ctx.beginPath();
            ctx.moveTo(this.posX+this.width-ptMarg, this.posY+-6);
            ctx.lineTo(this.posX+this.width-ptMarg+5,this.posY);
            ctx.lineTo(this.posX+this.width-ptMarg-5,this.posY);
            ctx.fill();
        }*/

        /*if(this.setas.pb1){
            ctx.beginPath();
            ctx.moveTo(this.posX+ptMarg, this.posY+this.height+6);
            ctx.lineTo(this.posX+ptMarg+5,this.posY+this.height);
            ctx.lineTo(this.posX+ptMarg-5,this.posY+this.height);
            ctx.fill();
        }*/
        if(this.setas.pb2){
            ctx.beginPath();
            ctx.moveTo(this.posX+this.width-ptMarg, this.posY+this.height+this.corner-6);
            ctx.lineTo(this.posX+this.width-ptMarg+5,this.posY+this.height+this.corner);
            ctx.lineTo(this.posX+this.width-ptMarg-5,this.posY+this.height+this.corner);
            ctx.fill();
        }

        this.setas = {'pt1':false,'pt2':false,'pb1':false,'pb2':false};

    }

    this.setPos = function(posX,posY){
        this.db.posicao_x = posX;
        this.db.posicao_y = posY;
    }

    this.halfWidth = function(){
        return this.width/2;
    }
    this.halfHeight = function(){
        return this.height/2;
    }
    this.centerX = function(){
        return this.posX + this.halfWidth();
    }
    this.centerY = function(){
        return this.posY + this.halfHeight();
    }


}

CanvasRenderingContext2D.prototype.roundRect = function (x, y, w, h, r) {
    if (w < 2 * r) r = w / 2;
    if (h < 2 * r) r = h / 2;
    this.beginPath();
    this.moveTo(x+r, y);
    this.arcTo(x+w, y,   x+w, y+h, r);
    this.arcTo(x+w, y+h, x,   y+h, r);
    this.arcTo(x,   y+h, x,   y,   r);
    this.arcTo(x,   y,   x+w, y,   r);
    this.closePath();
    return this;
}