<?php
class Bertholdo_Sync_Helper_Data extends Mage_Core_Helper_Abstract {

	public function toOptionArray()
    {
		$categoriesAll = array();

		$categories = Mage::getModel('catalog/category')
									->getCollection()
									->addAttributeToSelect(array('id', 'name'))
									->addIsActiveFilter();
		$i = 1;

		foreach ($categories as $categorie)
		{
			$categoriesAll[$i]['value'] = $categorie->getId();
			$categoriesAll[$i]['label'] = $categorie->getName();
			$i++;
		}

		return $categoriesAll;
    }

	public function limpaString($texto)
	{
		$aFind = array('&','А','Ю','Ц','Б','И','Й','М','С','Т','У','З','Э','Г','а','ю','ц','б','и','й','м','с','т','у','з','э','г','/',':','.','(',')','-','╨','╙','n╨','N╨','╟','Х','Р','Л','Н','Т','х','р','л','н','т','n╟','N╟','О','╢','А','Ю','Б','Ц','а','ю','ц','б','╙','М','Л','м','л','С','Р','Т','У','с','р','у','т','╨','З','Ы','Ш','з','ы','ш','Я','я','`','╗','^','~','К');

		$aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u','c','A','A','A','A','E','E','I','O','O','O','U','U','C','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');

		$novoTexto = str_replace($aFind,$aSubs,$texto);
		
		$novoTexto = trim($novoTexto);
		$novoTexto = addslashes($novoTexto);

		return $novoTexto;
	}

	public function limpaStringExtra($texto)
	{

		$novoTexto = strtoupper(strtr($texto ,"АИМСЗБЙТЦУЮХЛРЫГ","аимсзбйтцуюхлрыг"));
		$novoTexto = trim($novoTexto);

		$novoTexto = addslashes($novoTexto);
		$novoTexto = limpaString($novoTexto);

		return $novoTexto;
	}

	public function execucao()
	{
		$sec = explode(" ",microtime());
		$tempo = $sec[1] + $sec[0];
		return floor($tempo / 60);
	}

	function download( $file )
	{
		if ( is_file( $file ) && is_readable( $file ) )
		{
			if ( is_resource( $fh = fopen( $file , 'r' ) ) )
			{
				header( 'Content-type: text/csv' );
				header( sprintf( 'Content-Disposition: attachment; filename="%s"' , basename( $file ) ) );

				while ( !feof( $fh ) )
				{
					echo fread( $fh , 1024 );
					flush();
				}
				fclose( $fh );
			} 
			else 
			{
				trigger_error( 'NЦo foi possМvel abrir o arquivo para leitura.' , E_USER_ERROR );
			}
		} 
		else 
		{
			trigger_error( 'O arquivo nЦo existe ou nЦo temos permissЦo de leitura.' , E_USER_ERROR );
		}
	}
}
?>