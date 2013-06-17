<?php require_once '../../../../../wp-config.php'; ?>

var goToByScroll = function( id, pos )
{
    id = id.replace("link", "");
    jQuery('#area-list-newsletter').animate
    (
    { scrollTop: pos }, 
        'slow'
    );
};

var loadData = function()
{
    jQuery('#bt-get-more').val( 'Aguarde carregando...' );
    jQuery('#bt-get-more').attr('disabled', true);
    var url = '<?php echo plugins_url(); ?>/wmSimpleNewsletter/ajax/wmNewsletter.ajax.php?id=';
    jQuery.ajax({
        url:  url,
        type: 'post',
        async: false,
        dataType: 'JSON',
        data: jQuery('#form-get-more-emails').serialize(),
        success: function(data) 
        {
            if( data != null )
            {
                if ( data['list'].length == 0 )
                {
                    jQuery('#bt-get-more').val( 'Não há mais items a serem exibidos.' );
                }
                jQuery('#end-id').val( data['total'].ID );
                jQuery('#tr-set').remove();
                var positionScroll = 0;
                var contentAnchor = '<th colspan="3"><a href="#setList"></a></th>';
                if( jQuery("#table-list-newsletter tbody").html() != '' )
                {
                    positionScroll = jQuery("#table-list-newsletter").height() + parseInt(jQuery('#area-list-newsletter').css('padding'));
                    contentAnchor = '<th><a href="#setList"></a>Nome</th>'
                    				+'<th><a href="#setList"></a>E-mail</th>'
                                    +'<th>Data de cadastro</th>'
                                    +'<th>ações</th>';
                }
                jQuery('<tr id="tr-set" class="thead">'
                    +contentAnchor
                    +'</tr>').appendTo('#table-list-newsletter tbody');
                jQuery.each(data['list'], function(index, email)
                {
                    jQuery( '<tr>'
                    +'<td>'+ email.nome_newsletter +'</td>'
                    +'<td>'+ email.email_newsletter +'</td>'
                    +'<td>'+ email.data_cadastro+'</td>'
                    +'<td class="action">'
                    +'<a href="?page=list-emails&action=remove&id_email=' + email.ID + '">Excluir</a>'
                    +'</td>'
                    +'</tr>'
                    ).appendTo('#table-list-newsletter tbody');
                    jQuery('#last-id').val( email.ID );
                    if( parseInt( email.ID ) >= parseInt( jQuery('#end-id').val() ) )
                    {
                        jQuery('#bt-get-more').attr('disabled', true);
                        jQuery('#bt-get-more').val( 'Não há mais items a serem exibidos.' );
                    }
                    else
                    {
                        jQuery('#bt-get-more').val( 'Carregar mais dados.' );
                        jQuery('#bt-get-more').attr('disabled', false);
                    }
                });
                jQuery('#total-emails').html('Total de emails: ' + data['total'].total);
                goToByScroll( "tr-set", positionScroll );
            }
            else
            {
                jQuery('#bt-get-more').val( 'Não há mais items a serem exibidos.' );
            }
        }
    });
};

jQuery(document).ready( function()
{
jQuery('#bt-get-more').click( function()
{
    loadData();
});

loadData();
});

