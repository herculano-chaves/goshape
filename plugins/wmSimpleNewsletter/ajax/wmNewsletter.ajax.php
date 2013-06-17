<?php
require_once( '../../../../wp-config.php' );
include_once( plugin_dir_path( __FILE__ ) . '../../libs/Utils.php' );
include_once( '../Model/WmNewsletterEmails.php' );

function isAjax() 
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function loadData()
{
    if( isAjax() )
    {
        $limit = 50;
        $lastId = $_REQUEST['lastId'];
        $wmnews = new WmNewsletterEmails();
        $list = $wmnews->listEmailsPaginator( $lastId, $limit );
        die( json_encode( $list ) );
    }
}

function delete()
{
    $wmnews = new WmNewsletterEmails();
    $wmnews->delete( );
}

function array_to_xml( array $arr, SimpleXMLElement &$xml ) 
{
    foreach($arr as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                array_to_xml($value, $xml);
            }
        }
        else {
            $xml->addChild("$key","$value");
        }
    }
}    

function exportFileTxt()
{
    $wmnews = new WmNewsletterEmails();
    $wmnewsList = $wmnews->listEmails();
    $wmnewsObj = new WmNewsletterEmails();
    $content = "";
    foreach ( $wmnewsList as &$wmnewsObj )
    {
        $content .= $wmnewsObj->email_newsletter . "\r\n";
    }
    
        
    $file_out = $content;    
    $fname = "emails-" . date("d-m-Y") . ".txt";
    $out = strlen( $content );
    if ( isset( $file_out ) ) 
    {
        header("Content-Length: $out");
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=$fname");
        echo $file_out;
        exit;
    }
}

function exportFileCsv()
{
    $wmnews = new WmNewsletterEmails();
    $wmnewsList = $wmnews->listEmails();
    $wmnewsObj = new WmNewsletterEmails();
    $content = "";
    foreach ( $wmnewsList as &$wmnewsObj )
    {
        $content .= '"' . $wmnewsObj->email_newsletter . '"' . "\r\n";
    }
    
        
    $file_out = $content;    
    $fname = "emails-" . date("d-m-Y") . ".csv";
    $out = strlen( $content );
    if ( isset( $file_out ) ) 
    {
        header("Content-Length: $out");
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=$fname");
        echo $file_out;
        exit;
    }
}

function exportFileXml()
{
    $wmnews = new WmNewsletterEmails();
    $wmnewsList = $wmnews->listEmails();
    foreach ( $wmnewsList as $key => $wmnewsObj )
    {
        $wmnewsObj = $wmnews->object_to_array( $wmnewsObj );
        array_push( $wmnewsList, array( 'email' => $wmnewsObj ) );
        unset( $wmnewsList[$key] );        
    }
    
    $xml = new SimpleXMLElement('<emails/>');
    array_to_xml( $wmnewsList , $xml);
    
    $file_out = $xml->asXML();    
    $fname = "emails-" . date("d-m-Y") . ".xml";
    $out = strlen( $xml->asXML() );
    if ( isset( $file_out ) ) 
    {
        header("Content-Length: $out");
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=$fname");
        echo $file_out;
        exit;
    }
}

function save()
{
    $wmnews = new WmNewsletterEmails();
    if( $wmnews->validateObject( $_POST['email_newsletter'] ) && $wmnews->validateEmail( $_POST['email_newsletter'] ) )
    {
        if ( $wmnews->getByEmail( $_POST['email_newsletter'] ) )
        {
            Utils::message_response('erro', 'E-mail já cadastrado.');
            die( json_encode( Utils::getMessageResponse() ) );            
        }
        
        if ( $wmnews->save( $_POST['email_newsletter'] ) )
        {
            Utils::message_response('ok', 'E-mail cadastrado com sucesso.');
            die( json_encode( Utils::getMessageResponse() ) );
        }
        Utils::message_response('erro', 'Houve algum problema solicitação, contate o administrador.');
        die( json_encode( Utils::getMessageResponse() ) );
    }
    Utils::message_response('alert', 'E-mail inválido.');
    die( json_encode( Utils::getMessageResponse() ) );    
}

if ( isset( $_REQUEST['action'] ) )
{
    if ( $_REQUEST['action'] == 'busca-dados-emails' )
    {
        if ( is_super_admin() )
        {
            loadData();
        }
        die( "Sem permissão para executar essa ação.");
    }
    else if( $_REQUEST['action'] == 'exporta-dados-emails-xml' )
    {
        if( is_super_admin() )
        {
            exportFileXml();
        }
        die( "Sem permissão para executar essa ação.");
    }
    else if( $_REQUEST['action'] == 'exporta-dados-emails-txt' )
    {
        if( is_super_admin() )
        {
            exportFileTxt();
        }
        die( "Sem permissão para executar essa ação.");
    }
    else if( $_REQUEST['action'] == 'exporta-dados-emails-csv' )
    {
        if( is_super_admin() )
        {
            exportFileCsv();
        }
        die( "Sem permissão para executar essa ação.");
    }
    else if ( $_REQUEST['action'] == 'cadastra-email-newsletter' )
    {
        save();
    }
}
else
{
    die( "Sem permissão para executar essa ação.");
}
?>
