<?php
include_once( plugin_dir_path( __FILE__ ) . '../../libs/Utils.php' );

class WmNewsletterEmails extends Utils
{
    var $ID;
    var $data_cadastro;
	var $nome_newsletter;
    var $email_newsletter;
    
    public function getDefaultRequireds()
    {
        return Array(
			$this->nome_newsletter,
            $this->email_newsletter
        );
    }
    
    public function listEmails()
    {
        global $wpdb;
        return $wpdb->get_results( "SELECT *, date_format( data_cadastro, '%d/%m/%Y' ) as data_cadastro FROM $wpdb->WmNewsletterEmails" );
    }
    
    public function getByEmail( $email_newsletter )
    {
        global $wpdb;
        self::anti_sql_injection( $email_newsletter );
        return $wpdb->query( "SELECT email_newsletter FROM $wpdb->WmNewsletterEmails where email_newsletter = '$email_newsletter'" );        
    }
    
    public function listEmailsPaginator( $lastId = 0, $limitByPage = 50 )
    {
        global $wpdb;
        $params = " where ID > $lastId LIMIT $limitByPage";
        $emails = null;
        $emails['list'] = $wpdb->get_results( "SELECT *, date_format( data_cadastro, '%d/%m/%Y' ) as data_cadastro FROM $wpdb->WmNewsletterEmails $params" );
        $emails['total'] = $wpdb->get_row( "SELECT count(*) as total, MAX(ID) as ID FROM $wpdb->WmNewsletterEmails LIMIT 1 " );
        return $emails;
    }

    public function save( $nome, $email )
    {
        global $wpdb;
        unset( $this->ID );
        unset( $this->data_cadastro );
		self::anti_sql_injection( $nome );
        self::anti_sql_injection( $email );
        $this->email_newsletter = $email;
		$this->nome_newsletter = $nome;
        return $wpdb->insert( $wpdb->WmNewsletterEmails,  $this->object_to_array( $this ) );
    }
    
    public function delete( $id )
    {
        global $wpdb;
        self::anti_sql_injection( $id );
        $wpdb->query(
                "
                DELETE FROM $wpdb->WmNewsletterEmails 
                WHERE ID = $id
                "
        );        
    }

}

?>
