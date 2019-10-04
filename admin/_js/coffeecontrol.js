/*
 * SCRIPT PARA O PAINEL ADMIN COFFEE CONTROL
 * Author: Guilherme Natus - Agência Digital WebService
 * 09 - 03 - 2017
 */
$(function(){
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Efeito que ao clicar na box do menu, verifica qual o left dele, dependendo dele, faz uma animação para left:0px ou left:-220px, fazendo com que o menu apareça e desapareça! $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    $(".men").click(function(){
        $('.navadmin').css('display', 'block');
        if($('.navadmin').css('left') === '0px'){
            $('.navadmin').animate({
                left: '-210px'
            }, 300);
            $('#painel').animate({
                width: '100%'
            });    
        }
        else{
            $('.navadmin').animate({
                left: '0px'
            }, 300);
            $('#painel').animate({
                width: '85%'
            }); 
        }
    });
    
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Mascara com o plugin Mask para o campo CPF do painel CoffeeControl! $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    $(".date").mask("99/99/9999");
    
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Verifica se não tem a classe no_post_ajax, para postar nos arquivos php e realizar o ajax! $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    $('form').not('.no_post_ajax').submit(function(){
        var form = $(this);
        var file = $("input[name='file']").val();
        var action = $("input[name='action']").val();

        form.ajaxSubmit({
            url: '_ajax/' + file + '.ajax.php',
            data: {action: action},
            dataType: 'json',
            beforeSend: function () {
                $('.ajax_load').fadeIn('slow');
                $('.return-ajax').fadeOut('slow');
                $('.Error').fadeOut('slow');
            },  
            success: function(data) {
                $('.ajax_load').fadeOut('slow', function(){
                    if(data.message){
                        $('.return-ajax').html(ReturnAjax(data.message));
                        $('.return-ajax').fadeIn('slow');
                    }
                    if(data.redirect){
                        setTimeout(function(){
                            window.location.href = data.redirect;
                        },2000);
                    }
                });
            }
        });
        return false;
    });
    
    // BUSCAR CIDADES QUANDO ALTERA O OPTION DOS ESTADOS
    $(".user_state").change(function(){
        var id_state = $(this).val();
        var file = 'city-states';
        var action = 'cityes';
        $.ajax({
            url: 'http://localhost/system/admin/_ajax/city-states.ajax.php',
            data: {id_state : id_state, action: action, file: file},
            type: 'POST',
            dataType: 'json',
            beforeSend: function(){
                $('.return-ajax').stop().fadeOut(500);
                if(id_state){
                    $(".user_city").html("<option class='option-cityes'>Carregando Cidades...</option>");
                    $(".user_city").attr("disabled", "true");
                }
                else{
                   $(".user_city").html('<option class="option-cityes" value="">Selecione um Estado</option>').attr("disabled", "true");
                }
            },
            success: function(data){
                if(data.cityes){
                    $.each(data.cityes, function(key, values){
                        $(".option-cityes").remove();
                        $(".user_city").append("<option value=" + values.cidade_id + ">" + values.cidade_nome + "</option>");
                        $(".user_city").removeAttr("disabled");
                    });
                }
            },
            error: function(data){
                $(".return-ajax").html(ReturnAjax(data.message));
                $('.return-ajax').fadeIn(700);
            }
        });
    });

    // OBTER ID DO PRODUTO QUE A PESSOA CLICOU PARA APLICAR UMA CLASSE E NÃO EXIBIR NO PRÓXIMO SELECT
    $(".container-products").on("click", ".product-select", function(){
        var product_id = $(this).val();
        console.log(product_id);
        $(".products-option").each(function(key, value){
            if($(value).attr("data-value") === product_id){
                $(".add-product-buy").click(function(){
                    $(".products").find(".product-selected").addClass("product-marked");
                });
                $(".products").find(".products-option").removeClass("product-selected");
                $(".products").find("#products-option" + product_id).addClass("product-selected");
            }
        });
    });

    // ADICIONAR MAIS UM PRODUTO AS COMPRAS
    $(".add-product-buy").click(function(){
        var element = "<div class='box box-full bg-writelight1 bottom30 padding20'><span class='field'>Produto:</span><select class='bottom15 product-select' name='user_product[]' title='Selecione o produto' required><option value=''>Selecione o produto</option>";
        $(".products-option").each(function(key, value){
            if(!$(value).hasClass("product-selected") && !$(value).hasClass("product-marked")){
                element += "<option value='"+$(value).attr("data-value")+"'>"+$(value).val()+"</option>";
            }
        });
        element += "</select><span class='field'>Quantidade:</span><input type='number' name='quantity[]' min='1' required/></div>";
        if($(".box-value-total").length){
            $(".box-value-total").before(element);
        }
        else{
            $(".container-products").append(element);
        }
        return false;
    });
    
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Função com formatação para poder exibir o retorno do php para o jquery! $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    function ReturnAjax(error){
        return "<p class='bg-green radius trigger'>" + error + "</p>";
    }
    
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Desaparece com a div que contém o conteúdo do retorno do ajax! $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    $(".return-ajax").click(function(){
       $(this).fadeOut(500); 
    });

    $(".content-images-gallery").on('click', '.act_delete_gallery', function(){
        var ConfirmDelete = confirm("Quer Mesmo Deletar?");
        
        if(ConfirmDelete !== true){
            return false;
        }
    });

    
    // $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$ Verifica se a pessoa não clicou em ok para não seguir o link de deletar uma categoria, post ou usuário!  $$$$$$COFFEE-GuilhermeNatus-PHP$$$$$$
    $(".act_delete").click(function(){
        var ConfirmDelete = confirm("Quer Mesmo Deletar?");
        
        if(ConfirmDelete !== true){
            return false;
        }
    });
});


