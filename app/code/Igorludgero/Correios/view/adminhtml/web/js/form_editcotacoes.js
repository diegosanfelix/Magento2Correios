/**
 * Created by igorludgeromiura on 14/09/16.
 */

require(["jquery","maskedinput"], function($){

    //Peso
    $('input[name=peso]').mask("99.999");

    //Preço
    $('input[name=valor]').mask("R$ 999.99");

    //Prazo
    $('input[name=prazo]').mask("99");

    //Ceps
    $('input[name=cep_inicio]').mask("99999-999");
    $('input[name=cep_fim]').mask("99999-999");

});