<?php
include_once( 'controller/lista_emails_controller.php' );
init();
?>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>js/lista-emails.js.php"></script>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>css/lista-emails.css" type="text/css" media="all">
<div id="pg-area-wm-simple-newsletter">
    <form id="form-get-more-emails" action="post">
        <input type="hidden" name="action" value="busca-dados-emails"/>
        <input type="hidden" id="last-id" name="lastId" value="0"/>
        <input type="hidden" id="end-id" name="endId" value="0"/>
    </form>
    <div class="alert-cadastro-<?php echo Utils::getMessageResponseType(); ?>"><?php echo Utils::getMessageResponseMessage(); ?></div>
    <div style="padding: 10px;">
        <a id="link-export-txt" href="<?php echo plugins_url(); ?>/wmSimpleNewsletter/ajax/wmNewsletter.ajax.php?action=exporta-dados-emails-txt" class="button-primary">Exportar dados para txt</a>
        <a id="link-export-xml" href="<?php echo plugins_url(); ?>/wmSimpleNewsletter/ajax/wmNewsletter.ajax.php?action=exporta-dados-emails-xml" class="button-primary">Exportar dados para xml</a>
        <a id="link-export-csv" href="<?php echo plugins_url(); ?>/wmSimpleNewsletter/ajax/wmNewsletter.ajax.php?action=exporta-dados-emails-csv" class="button-primary">Exportar dados para csv</a>
    </div>
    <div id="content-emails-newsletter">
        <div>
            <input id="bt-get-more" type="button" value="Carregar mais dados." class="button-secondary"/>
        </div>
        <div id="area-list-newsletter">
            <table id="table-list-newsletter" class="widefat fixed">
                <thead>
                    <tr class="thead">
                    	<th>Nome</th>
                        <th>E-mail</th>
                        <th>Data de cadastro</th>
                        <th>ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="total-emails"></div>
    </div>
</div>
