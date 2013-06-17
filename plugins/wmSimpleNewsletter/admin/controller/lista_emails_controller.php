<?php
include_once( plugin_dir_path( __FILE__ ) . '../../Model/WmNewsletterEmails.php' );
unset( $_SESSION['wmNewsletter']['end-list'] );

function init()
{
    if ( is_super_admin() )
    {
        if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'remove' ) )
        {
            $wmNewsletter = new WmNewsletterEmails();
            if ( $wmNewsletter->delete( $_GET['id_email'] ) )
            {
                Utils::message_response( 'ok', 'E-mail removido com sucesso.' );
            }
            else
            {
                Utils::message_response( 'erro', 'Usuário inexistente.' );
            }
            die( "<script>document.location.href = '" . get_admin_url() . "admin.php?page=list-emails" . "';</script>" );
        }
    }
}
?>
