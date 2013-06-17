<?php

?>
<script type="text/javascript">
    var cadastraEmailNewsletter = function()
    {
        jQuery('#bt-cadastra-newsletter').attr('disabled', true);
        var url = '<?php echo plugins_url(); ?>/wmSimpleNewsletter/ajax/wmNewsletter.ajax.php';
        jQuery.ajax({
            url:  url,
            type: 'post',
            dataType: 'JSON',
            data: jQuery('#form-cadastra-email-newsletter').serialize(),
            success: function(data) 
            {
                alert( data.message );
				jQuery('#nome_newsletter').val('');
                jQuery('#email_newsletter').val('');
                jQuery('#bt-cadastra-newsletter').attr('disabled', false);
            }
        });
    };
    
    jQuery(document).ready( function()
    {
		/*
        jQuery( '#form-cadastra-email-newsletter' ).validate(
        {
            errorClass: 'erro',
            messages: {
                email_newsletter: {
                    required: "Por favor informe o E-mail",
                    email: "Por favor informe um e-mail válido"
                }
            },
            submitHandler: function(){
                cadastraEmailNewsletter();
            }
        });
        */
        jQuery('#form-cadastra-email-newsletter #bt-cadastra-newsletter').click( function()
        {
            //jQuery( '#form-cadastra-email-newsletter' ).submit();
			cadastraEmailNewsletter();
        });
    });
</script>
<!--
<div>
	<h5 class="sub-titulo">Assinaturas por e-mail</h5>
    <p>Receba nossas notícias, promoções e novidades diretamente no seu email.</p>
    <div>
        <form id="form-cadastra-email-newsletter" action="post">
            <input type="hidden" name="action" value="cadastra-email-newsletter"/>
            <input type="text" id="email_newsletter" name="email_newsletter" class="required email" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" value="Insira seu e-mail" />
            <input type="button" id="bt-cadastra-newsletter" value="ok" class="s-button"  />
        </form>
    </div>
</div>
-->

<div class="Newsletter">
    <h3><img src="<?php bloginfo('template_url'); ?>/img/image-tit-newsletter.png" alt="" title="" /></h3>
    <form id="form-cadastra-email-newsletter" onsubmit="return false;">
        <input type="hidden" name="action" value="cadastra-email-newsletter"/>
        
        <input id="nome" class="inpt01 bdr2" type="text" text="Nome" onfocus="javascript:if (this.value == 'Nome') {this.value = '';}" onblur="javascript:if (this.value == '') {this.value = 'Nome';}" value="Nome" id="nome_newsletter" name="nome_newsletter" />
        
        <input id="email" class="inpt02 bdr2" type="text" text="E-mail" onfocus="javascript:if (this.value == 'E-mail') {this.value = '';}" onblur="javascript:if (this.value == '') {this.value = 'E-mail';}" value="E-mail" id="email_newsletter" name="email_newsletter" />
        
        <input id="bt-cadastra-newsletter" class="send" type="submit" value="ok" name="" onclick="cadastraUsuario();" />
    </form>
</div>