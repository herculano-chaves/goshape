<?php 
require('../../../../../wp-blog-header.php');

$nome = $_POST['nome'];
$email = $_POST['email'];
$dataNascimento = $_POST['dataNascimento'];
$cidade = $_POST['cidade'];
$pais = $_POST['pais'];
$profissao = $_POST['profissao'];

if(filter_var($email, FILTER_VALIDATE_EMAIL)) :
	if($nome!='Nome' and $nome!='' and $email!='E-mail' and $email!='') :
	
		# Procura email na tabela
		$query = "
				SELECT *  
				FROM wp_newsletter
				WHERE email = '$email'
				";
			
		$resultado = $wpdb->get_results($query, OBJECT);
		
		if(count($resultado)>0) {
			echo "E-mail já está cadastrado"; 	
		} else {
			
			# Procura email na tabela
			$query = $wpdb->insert( 
						'wp_newsletter', 
						array( 
							'nome' => $nome, 
							'email' => $email,
							'data_nascimento' => $dataNascimento,
							'cidade' => $cidade,
							'pais' => $pais,
							'profissao' => $profissao  
						), 
						array( 
							'%s', 
							'%s',
							'%s',
							'%s',
							'%s',
							'%s' 
						) 
					);
			if($query) 
			{ 
				$nome = explode(' ', $nome);
				echo "<div id='formResposta'>".$nome[0].",<br /><br /> Seu cadastro foi efetuado com sucesso.<br /><br /> Agora você receberá informações e novidades sobre a ArtRio.<br /><br /> Obrigado.</div>";
			} else {
				echo "false";	
			}
			
		}
	
	endif;
else :
	echo "E-mail inválido";	
endif;
?>