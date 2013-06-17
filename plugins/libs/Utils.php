<?php
/**
 * Description of Utils
 *
 * @author roliveira
 */
class Utils 
{
    /*
     * convert um post ou um get para objeto
     */
    public static function convert_request_to_object( $class, $data, $returnArray=false )
    {
        $object = new $class();
        $keys =  array_keys( $data );
        foreach( $keys as $key )
        {
            if ( property_exists( $class, $key ) )
            {
                $object->$key = trim( $data[$key] );
            }
        }
        if ( $returnArray )
        {
            return object_to_array( $object );
        }
        return $object;
    }

    /*
     * converte um objeto para array
     */
    public static function object_to_array( $object, $unsetnull = false )
    {
        if( $object == null )
        {
            return null;
        }
        
        $array = array();
        foreach ( $object as $key => $value )
        {
            if ( is_object( $value ) )
            {
                $array[$key]= object_to_array( $value );
            }
            if ( is_array( $value ) )
            {
                $array[$key]= object_to_array( $value );
            }
            else
            {
                if( $unsetnull && $value == null )
                {
                    continue;
                }
                $array[$key]= $value;
            }
        }
        return $array;
    }

    /*
     * clona objetos
     */
    public static function recast( &$actualObject, $object )
    {
        foreach($actualObject as $property => &$value)
        {
            $actualObject->$property = null;
        }
        if ( $object != null )
        {
            foreach($object as $property => &$value)
            {
                $actualObject->$property = &$value;
                unset($object->$property);
            }
        }
    }
    
    public static function intercessionObjects( &$actualObject, $objectB )
    {
        foreach( $objectB as $property => $value )
        {
            if( isset( $objectB->$property ) && $objectB->$property != null )
            {
                $actualObject->$property = $value;
            }
        }        
    }
    
    public static function unsetPropertiesOnlyNot( &$object, array $properties )
    {
        $propertiesClass = array_keys( get_class_vars( get_class( $object ) ) );;
        $propertiesUnset = array_diff ( $propertiesClass, $properties );
        foreach( $propertiesUnset as $property )
        {
            unset( $object->$property );
        }
        return $object;
    }
    
    public static function getPropertiesClassInArray( $object )
    {
        return array_keys(get_class_vars( get_class( $object ) ) );
    }
    
    public static function validateObject( $properties )
    {
        if( is_array( $properties ) )
        {
            foreach ($properties as $property) {
                if ( !isset( $property ) || $property == null || trim( $property ) == "" )
                {
                    return false;
                }
            }
        }
        else
        {
            if ( !isset( $properties ) || $properties == null || trim( $properties ) == "" )
            {
                return false;
            }            
        }
        return true;
    }
    
    public static function validateEmail( $email )
    {
        if ( self::validateObject( $email ) )
        {
            $sRegEmail = "/^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/";
            if ( preg_match( $sRegEmail, $email ) )
            {
                return true;
            }
        }
        return false;
    }
    
    public static function message_response( $type, $message )
    {
        $GLOBALS['message_response']['type'] = $type;
        $GLOBALS['message_response']['message'] = $message;
    }
    
    public static function getMessageResponse()
    {
        global $message_response;
        return $message_response;
    }
    
    public static function getMessageResponseType()
    {
        global $message_response;
        return $message_response['type'];
    }
    
    public static function getMessageResponseMessage()
    {
        global $message_response;
        return $message_response['message'];
    }
    
    public static function destroyMessageResponse()
    {
        unset($GLOBALS['message_response']);
    }
    
    public static function encryptKeyNewPassword( $idUser, $email )
    {
        // Gera uma chave aleatória para ser utilizada na encriptação
        $caracteresAccepteds = 'abcdxywzABCDZYWZ0123456789@!*';
        $max = strlen( $caracteresAccepteds ) - 1;
        $key = null;
        for ( $i = 0; $i < 8; $i++ )
        {
            $key .= $caracteresAccepteds{mt_rand( 0, $max )};
        }
        // Chave utilizada na encriptação do Id
        $keyEncript = "Wm! Estudio Recuperação de Senha Clube Militar";
        // Encriptação do Id
        $hIdUser = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $keyEncript ), $idUser, MCRYPT_MODE_CBC, md5( md5( $keyEncript ) ) ) );
        // Criação do hash para envio na url
        $hash = md5( $email . $key ) . "|" . $hIdUser;
        return Array( 'key' => $key, 'hash' => $hash);
    }

    public static function decryptKeyNewPassword( $encriptId )
    {
        // Chave utilizada na encriptação do Id
        $keyEncript = "Wm! Estudio Recuperação de Senha Clube Militar";
        // Função de decriptação
        $encriptId = str_replace( " ", "+", $encriptId );
        $idUser = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $keyEncript ), base64_decode( $encriptId ), MCRYPT_MODE_CBC, md5( md5( $keyEncript ) ) ), "\0" );
        return $idUser;
    }
    
    public static function brDateToMysqlDate( $date )
    {
        list($d, $m, $y) = preg_split('/\//', $date);
        return sprintf('%4d-%02d-%02d', $y, $m, $d);
    }

    public static function converModelToForm( $Class )
    {
        $object = new $Class();
        $vars = get_object_vars( $object );
        foreach($vars as $property => &$value)
        {
            $html .= "<label for='$property'>$property</label>" . "\n" . "<input type='text' id='$property' name='$property'/> " . 
                        "\n" . 
                        "</br>" . 
                        "\n";
        }
        return $html;
    }
    
    public static function convertModelToJavaScriptObj( $Class )
    {
        $object = new $Class();
        $vars = get_object_vars( $object );
        $js .= "{";
        foreach($vars as $property => &$value)
        {
            $js .= "\n" . $property . " : null,";
        }
        $js .= "\n}";
        return $js;        
    }
    
    public static function createComboBoxHtmlBrazilStates( $elementName, $classes, $selectedValue = null )
    {
        $object =  '<select name="'.$elementName.'" id="'.$elementName.'" class="'.$classes.'">
                    <option value="">Selecione um estado</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AM">AM</option>
                    <option value="AP">AP</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MG">MG</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="PR">PR</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RR">RR</option>
                    <option value="RO">RO</option>
                    <option value="RS">RS</option>
                    <option value="SC">SC</option>
                    <option value="SE">SE</option>
                    <option value="SP">SP</option>
                    <option value="TO">TO</option>
                </select>';
        if ( $selectedValue != null && trim( $selectedValue ) != "" )
        {
            $selectedValue = 'value="' . $selectedValue . '"';
            $object = str_replace( $selectedValue, $selectedValue . " selected", $object );
            /*
            if( strpos( $object, $selectedValue ) )
            {
                $object =  stristr( $object, $selectedValue, true ) . " selected " . stristr( $object, $selectedValue );
            }*/
        }
        return $object;
    }

    public static function createComboBox( array $items, $name, $class, $selectedValue = null )
    {
        $item = null;
        $object = "<select id='$name' name='$name'>";
        foreach ( $items as $item )
        {
            $label = $item["label"];
            $value = $item["value"];
            $object .= "<option value='$value' ";
            if ( $selectedValue != null && $selectedValue == $value )
            {
                $object .= "selected";
            }
            $object .= "/>$label";
        }
        $object .= "</select>";
        return $object;
    }
    
    public static function createInputRadio( array $items, $name, $class, $selectedValue = null )
    {
        $item = null;
        $object = null;
        foreach ( $items as $item )
        {
            $label = $item["label"];
            $value = $item["value"];
            $object .= "<label><input type='radio' name='$name' class='$class' value='$value' ";
            if ( $selectedValue != null && $selectedValue == $value )
            {
                $object .= "checked";
            }
            $object .= "/>$label</label>";
        }
        return $object;
    }
    
    public static function timeToSecond( $time )
    {
        $chron = explode( ":", $time );
        $hs = ( $chron[0] * 3600 );
        $ms = ( $chron[1] * 60 );
        $s = ( $chron[2] );
        $totalSeconds = $hs + $ms + $s;
        return $totalSeconds;
    }
    
    public static function secondsToTime( $seconds )
    {
        $h = floor( $seconds / 3600 );
        $m = floor( ( $seconds % 3600 ) / 60 );
        $s = ( ( $seconds % 3600 ) % 60 );
        $time = $h . ":" . $m . ":" . $s;
        return $time;
    }
    
    public static function anti_sql_injection($str) 
    {
        if ( !is_numeric($str) ) 
        {
            $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
            $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
        }
        return $str;
    }
    
    public static function anti_sql_injection_multiples( array $args )
    {
        foreach ( $args as &$var )
        {
            $var = Utils::anti_sql_injection( $var );
        }
    }
    
    public static function anti_sql_injection_object( &$object )
    {
        $properties = get_object_vars( $object );
        foreach( $properties as $property => &$value )
        {
            $object->$property = Utils::anti_sql_injection( &$value );
        }
    }
    
    public static function array_to_xml( array $arr, SimpleXMLElement &$xml ) 
    {
        foreach($arr as $key => $value) 
        {
            if(is_array($value)) 
            {
                if(!is_numeric($key))
                {
                    $subnode = $xml->addChild("$key");
                    array_to_xml($value, $subnode);
                }
                else
                {
                    array_to_xml($value, $xml);
                }
            }
            else 
            {
                $xml->addChild("$key","$value");
            }
        }
    }
    
    public static function printIfExists( $str )
    {
        if ( isset( $str ) && $str != null )
        {
           print $str;
        }
    }

}
?>
