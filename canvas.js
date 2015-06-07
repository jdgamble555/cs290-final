/* 
 * Jonathan Gamble
 */

$(document).ready(function(){

    $("canvas").each(function() {
        
        var v = $(this).html();
        $(this).html('');

        var ctx = $(this)[0].getContext('2d');

        ctx.translate(100,100);
        ctx.save();
        ctx.rotate(Math.PI);
        ctx.font="30px Arial";
        ctx.fillStyle = "orange";
        ctx.fillText(v, -30, 50);

        ctx.restore();    
             
    });

});