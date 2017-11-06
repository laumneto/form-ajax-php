<?php
//if (isset($_POST['enviar']) && $_POST['enviar'] == 'send') {

 $nome =     strip_tags(trim($_POST['form_name']));
 $email =    strip_tags(trim($_POST['form_email']));
 $telefone =  strip_tags(trim($_POST['form_phone']));
 $assunto =  strip_tags(trim($_POST['form_assunto']));
 $mensagem = strip_tags(trim($_POST['form_message']));

 $anexado = $_FILES['arquivo']['name'];
 $extensao = strtolower(end(explode('.', $anexado)));
 $extensoes = array ('jpg', 'png');
 $size = $_FILES['arquivo']['size'];
 $maxsize = 1024 * 1024 * 2;

 if(empty($anexado)){
 echo "";
 }elseif(array_search($extensao, $extensoes) === false){
 $retorno = '<span><br>O tipo do arquivo é inválido, aceitamos somente txt, jpg, png, docx, pdf e mp3</span>';
 }elseif($size >= $maxsize){
 $retorno = '<span><br>Arquivo permitido somente com menos de 2mb</span>';
 }if (empty($retorno)) {

//<input type="hidden" name="enviar" value="send" />

$date = date("d/m/Y h:i");

// ****** ATENÇÃO ********
// ABAIXO ESTÁ A CONFIGURAÇÃO DO SEU FORMULÁRIO.
// ****** ATENÇÃO ********

//CABEÇALHO - ONFIGURAÇÕES SOBRE SEUS DADOS E SEU WEBSITE

$nome_do_site="WWW.SITE.COM";
$email_para_onde_vai_a_mensagem = "contato@contato.com.br";
$nome_de_quem_recebe_a_mensagem = "NOME DO SITE";
$exibir_apos_enviar='';

//MAIS - CONFIGURAÇOES DA MENSAGEM ORIGINAL
$cabecalho_da_mensagem_original="From: $email\n";
$assunto_da_mensagem_original="$assunto";

// FORMA COMO RECEBERÁ O E-MAIL (FORMULÁRIO)
// ******** OBS: SE FOR ADICIONAR NOVOS CAMPOS, ADICIONE OS CAMPOS NA VARIÁVEL ABAIXO *************
$configuracao_da_mensagem_original="

<strong>ENVIADO POR:</strong><br />
<strong>Nome:</strong> $nome<br />
<strong>Email:</strong> $email<br />
<strong>Telefone:</strong> $telefone<br />
<strong>Assunto:</strong> $assunto<br /><br />
<strong>Mensagem:</strong> $mensagem<br /><br />

<strong>ENVIADO EM:</strong> $date";

//CONFIGURAÇÕES DA MENSAGEM DE RESPOSTA
// CASO $assunto_digitado_pelo_usuario="s" ESSA VARIAVEL RECEBERA AUTOMATICAMENTE A CONFIGURACAO
// "Re: $assunto"
$assunto_da_mensagem_de_resposta = "Recebemos sua mensagem";
$cabecalho_da_mensagem_de_resposta = "From: $nome_do_site <$email_para_onde_vai_a_mensagem>\n";
$configuracao_da_mensagem_de_resposta="

Obrigado por enviar seu Pedido de Orçamento.<br />
Analisaremos e responderemos o mais breve.<br /><br />
<strong>Atenciosamente,<br/>
<strong>$nome_de_quem_recebe_a_mensagem<br/>
<strong>$nome_do_site</strong><br />
<strong>$email_para_onde_vai_a_mensagem</strong><br /><br />

<strong>Enviado em:</strong> $date";

// ****** IMPORTANTE ********
// A PARTIR DE AGORA RECOMENDA-SE QUE NÃO ALTERE O SCRIPT PARA QUE O  SISTEMA FINCIONE CORRETAMENTE
// ****** IMPORTANTE ********

//ESSA VARIAVEL DEFINE SE É O USUARIO QUEM DIGITA O ASSUNTO OU SE DEVE ASSUMIR O ASSUNTO DEFINIDO
//POR VOCÊ CASO O USUARIO DEFINA O ASSUNTO PONHA "s" NO LUGAR DE "n" E CRIE O CAMPO DE NOME
//'assunto' NO FORMULARIO DE ENVIO
$assunto_digitado_pelo_usuario="s";

//ENVIO DA MENSAGEM ORIGINAL

$i = 0;

$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE;

if(file_exists($arquivo["tmp_name"]) and !empty($arquivo)){

 $fp = fopen($_FILES["arquivo"]["tmp_name"],"rb");
 $anexo = fread($fp,filesize($_FILES["arquivo"]["tmp_name"]));
 $anexo = base64_encode($anexo);

fclose($fp);


$anexo = chunk_split($anexo);

$boundary = "XYZ-" . date("dmYis") . "-ZYX";

 $mens = "--$boundary\n";
 $mens .= "Content-Transfer-Encoding: 8bits\n";
 $mens .= "Content-Type: text/html; charset=\"UTF-8\"\n\n";
 $mens .= "$configuracao_da_mensagem_original\n";
 $mens .= "--$boundary\n";
 $mens .= "Content-Type: ".$arquivo["type"]."\n";
 $mens .= "Content-Disposition: attachment; filename=\"".$arquivo["name"]."\"\n";
 $mens .= "Content-Transfer-Encoding: base64\n\n";
 $mens .= "$anexo\n";
 $mens .= "--$boundary--\r\n";

$headers  = "MIME-Version: 1.0\n";
$headers .= "$cabecalho_da_mensagem_original";
$headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
$headers .= "$boundary\n";
}else{

$mens = "$configuracao_da_mensagem_original\n";

$headers  = "MIME-Version: 1.0\n";
$headers .= "$cabecalho_da_mensagem_original";
$headers .= "Content-Type: text/html; charset=\"UTF-8\"\n\n";
}

if ($assunto_digitado_pelo_usuario=="s")
{
$assunto = "$assunto_da_mensagem_original";
};
$seuemail = "$email_para_onde_vai_a_mensagem";
mail($seuemail,$assunto,$mens,$headers);

//ENVIO DE MENSAGEM DE RESPOSTA AUTOMATICA

$headers = "$cabecalho_da_mensagem_de_resposta";
$headers .= "Content-Type: text/html; charset=\"UTF-8\"\n\n";

if ($assunto_digitado_pelo_usuario=="s")
{
$assunto = "$assunto_da_mensagem_de_resposta";
}
else
{
$assunto = "Re: $assunto";
};
$mensagem = "$configuracao_da_mensagem_de_resposta";
mail($email,$assunto,$mensagem,$headers);

/*echo "<script>window.location='$exibir_apos_enviar'</script>";*/
//echo "<span class=\"yes\"><br>Sua mensagem foi enviada com suscesso. Responderemos o mais breve possível!</span>";
unset($nome, $email, $assunto, $mensagem);
} //else {
 //echo "$retorno";
 //}
//}
?>