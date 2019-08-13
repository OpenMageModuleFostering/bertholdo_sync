<?php
class Bertholdo_Sync_Adminhtml_SyncestoqueController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('sync/adminhtml_syncestoque');
		return $this;
	}

    public function indexAction()
    {
		$this->_initAction();
		$this->renderLayout();
    }

    public function importacaoCsvAction()
    {
		set_time_limit(0);

		$inicio = 0;
		$fim = 0;
		$msgRetorno = "";
		$linhasFile = array();

		$csvEstoque = $_FILES['file_upload']['name'];
		$tipoFile = $_FILES['file_upload']['type'];

		$objBD_read = Mage::getSingleton('core/resource')->getConnection('core/read');

		$resource = Mage::getSingleton('core/resource');
		$core_config_data = $resource->getTableName('core_config_data');

		$helper = Mage::helper('sync/data');
		$inicio = $helper->execucao();

		try
		{
			if ($this->getRequest()->getPost())
			{
				if( !empty($csvEstoque) && ( ($tipoFile == "text/csv") || ($tipoFile == "application/vnd.ms-excel") ) )
				{
					// SALVANDO O ARQUIVO

					$uploaderFile = new Varien_File_Uploader('file_upload');
					$uploaderFile->setAllowedExtensions(array());
					$uploaderFile->setAllowRenameFiles(false);
					$uploaderFile->setFilesDispersion(false);

					$uploaderFilepath = Mage::getBaseDir('media') . DS . 'Bertholdo' . DS . 'importcsv' . DS ;
					$filepath = $uploaderFilepath.$csvEstoque;
					
					// CRIANDO E VERIFICANDO O DIRETÓRIO

					if (file_exists($filepath)) 
					{
						unlink("$filepath");
					} 
					else 
					{
						mkdir("$uploaderFilepath", 0777, true);
					}

					$uploaderFile->save( $uploaderFilepath, $csvEstoque );
					
					// LEITURA DO CSV

					if ( ($handle = fopen($filepath, "r")) !== FALSE )
					{
						$row = 0;
						while ( ($data = fgetcsv($handle, 10000, ";")) !== FALSE )
						{				
							$linhasFile[$row] = $data;
							$row++;
						}
						fclose($handle);
					}
					else
					{
						$message = $this->__("Return: ERROR - Reading CSV file.");
						Mage::getSingleton('adminhtml/session')->addError($message);						
					}

					// PERCENTUAL DE ATUALIZAÇÃO DE ESTOQUE

					$configModulo = $objBD_read->query("SELECT value FROM $core_config_data WHERE path LIKE '%sync/options_sync/%'")->fetchAll();

					// ATUALIZANDO PRODUTOS

					$qtdLinhas = count($linhasFile);
					for($i = 1; $i < $qtdLinhas; $i++)
					{
						// PULANDO LINHAS DE LIXO

						if (empty($linhasFile[$i][0])) continue;

						// CAMPOS DO CSV PADRÃO

						$descricao_produto = $helper->limpaString($linhasFile[$i][0]);
						$codigo_sku_sistema_legado = $linhasFile[$i][1];
						$preco_produto = $linhasFile[$i][2];
						$qtd_estoque_atual = $linhasFile[$i][3];
						$novo_estoque = $linhasFile[$i][4];
						$percentual_atualizacao = $linhasFile[$i][5];

						$product = Mage::getModel('catalog/product');
						$product_id = $product->getIdBySku($codigo_sku_sistema_legado);

						// CASO O USUÁRIO PREENCHA O CSV COM O VALOR DO PERCENTUAL O MESMO SERÁ APLICADO NO ESTOQUE

						if (empty($percentual_atualizacao))
						{
							$qtd = round(($novo_estoque * ($configModulo[3]['value']/100)));
						}
						else
						{
							$qtd = round(($novo_estoque * ($percentual_atualizacao/100)));
						}

						// 1 - EM ESTOQUE, 0 - ESGOTADO

						$estoque = ($qtd > 0) ? 1 : 0;
						
						try
						{
							// VERIFICA SE O PRODUTO É NOVO OU NÃO

							if(empty($product_id))
							{						
								$product->setAttributeSetId(4);
								$product->setTypeId('simple');
								$product->setUrlKey(str_replace(" ", "-", $descricao_produto));
								$product->setSku($codigo_sku_sistema_legado);
								$product->setName($descricao_produto);

								// TODO PRODUTO NOVO É INSERIDO NA CATEGORIA PRINCIPAL

								$product->setCategoryIds(array($configModulo[1]['value']));
								$product->setWebsiteIds(array(1));
								$product->setDescription($descricao_produto);
								$product->setShortDescription($descricao_produto);
								$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
								$product->setCreatedAt(strtotime('now'));
								$product->setTaxClassId(0);
								$product->setWeight(1);

								// 1 - HABILITADO, 2 - DESABILITADO

								$product->setStatus(2);

								$product->setPrice($preco_produto);
								$product->setMsrp($preco_produto);

								// SALVA ESTOQUE

								$stockData = array('qty' => $qtd, 'is_in_stock' => $estoque);
								$product->setStockData($stockData);
								$product->save();

								unset($product, $stockData);

								Mage::getSingleton('core/cache')->flush();
							}
							else
							{
								$loadProduct = $product->load($product_id);
								$loadProduct->setPrice($preco_produto);
								$loadProduct->setMsrp($preco_produto);

								// ATUALIZA ESTOQUE

								$stockData = array('qty' => $qtd, 'is_in_stock' => $estoque);
								$loadProduct->setStockData($stockData);
								$loadProduct->save();

								unset($product, $loadProduct, $stockData, $product_id);

								Mage::getSingleton('core/cache')->flush();
							}						
						}
						catch (Exception $ex)
						{
							//Zend_Debug::dump($ex->getMessage());
							//Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
						}
					}

					$fim = $helper->execucao();

					$message = $this->__("Return: Import successful. <br/> Time: %s minute", number_format(($fim-$inicio)));
					Mage::getSingleton('adminhtml/session')->addSuccess($message);
				}
				else
				{
					$message = $this->__("Return: ERROR - Reading CSV file.");
					Mage::getSingleton('adminhtml/session')->addError($message);
				}
			}
			else
			{
				$message = $this->__("Return: ERROR - Sending form data.");
				Mage::getSingleton('adminhtml/session')->addError($message);
			}									
		}
		catch (Exception $e) 
		{
			$message = $e->getMessage();
			Mage::getSingleton('adminhtml/session')->addError($message);
		}
		$this->_redirect('*/*');
    }

	public function exportacaoCsvAction()
    {
		$csvEstoque = "sync_estoque_produtos.csv";

		$resource = Mage::getSingleton('core/resource');
		$core_config_data = $resource->getTableName('core_config_data');

		$objBD_read = Mage::getSingleton('core/resource')->getConnection('core/read');
		$helper = Mage::helper('sync/data');		

		try
		{
			if ($this->getRequest()->getPost())
			{
				// PERCENTUAL DE ATUALIZAÇÃO DE ESTOQUE E CATEGORIA DE PRODUTO PADRÃO

				$configModulo = $objBD_read->query("SELECT value FROM $core_config_data WHERE path LIKE '%sync/options_sync/%'")->fetchAll();

				// COLEÇÃO DOS PRODUTOS BASEADO NA CATEGORIA E QTY ESTOQUE DEFINIDA NAS CONFIGURAÇÕES DO MÓDULO

				$category = Mage::getModel('catalog/category')->load($configModulo[1]['value']);
				$collection = Mage::getModel('catalog/product')->getCollection()
															   ->addAttributeToSelect(array('name','sku','price','qty'))								   
															   ->addCategoryFilter($category)					
					                                           ->joinField( 
																			'qty', 
																			'cataloginventory/stock_item', 
																			'qty', 
																			'product_id=entity_id', 
																			'{{table}}.stock_id=1', 'left'
																);

				// SE ESTIVER VAZIO NÃO APLICO O FILTRO DE QUANTIDADE E TRAGO TODO MUNDO

				if (!empty($configModulo[2]['value']))
				{
					$collection->getSelect()->where("at_qty.qty >= {$configModulo[2]['value']}");
				}

				//Mage::log($collection->getSelect()->__toString());
				
				// CRIANDO O DIRETÓRIO

				$uploaderFilepath = Mage::getBaseDir('media') . DS . 'Bertholdo' . DS . 'importcsv' . DS ;
				$filepath = $uploaderFilepath.$csvEstoque;
				
				// CRIANDO E VERIFICANDO O DIRETÓRIO

				if (file_exists($filepath)) 
				{
					unlink("$filepath");
				} 
				else 
				{
					mkdir("$uploaderFilepath", 0777, true);
				}

				// CABEÇALHO DO CSV

				$fp = fopen($filepath, 'w');
				fputcsv($fp, array("descricao_produto;codigo_sku_sistema_legado;preco_produto;qtd_estoque_atual;novo_estoque;percentual_atualizacao"));

				foreach ($collection as $product)
				{
					// TIVE QUE REMOVER OS ESPAÇOS DA DESCRIÇÃO DO PRODUTO POIS O CSV NÃO RECONHECE COMO UMA INFORMAÇÃO SÓ

					$descricao_produto = substr(str_replace(" ", "_", $helper->limpaString($product->getName())),0,15) . "...";					
					$codigo_sku_sistema_legado = $product->getSku();

					// COMO O VALOR VEM NO FORMATO X.000 TIVE QUE APLICAR A DIVISÃO PARA REMOVER O FORMATO DO MAGENTO

					$preco_produto = ($product->getPrice()/1);
					$qtd_estoque_atual = ($product->getQty()/1);
					
					// REALIZEI ESSA OPERAÇÃO CASO O USUÁRIO NÃO QUEIRA MODIFICAR O ESTOQUE O VALOR NÃO É MODIFICADO

					$novo_estoque = $qtd_estoque_atual;

					// APLICANDO OS 100% SE O PRODUTO NÃO FOR MODIFICADO O MESMO CONTINUA COM SEU ANTIGO VALOR DE ESTOQUE

					$percentual_atualizacao = 100;

					fputcsv($fp, array($descricao_produto.';'.$codigo_sku_sistema_legado.';'.$preco_produto.';'.$qtd_estoque_atual.';'.$novo_estoque.';'.$percentual_atualizacao));
				}

				$helper->download($filepath);
				fclose($fp);
				
				exit;
			}
			else
			{
				$message = $this->__("Return: ERROR - Sending form data.");
				Mage::getSingleton('adminhtml/session')->addError($message);
			}
		}
		catch (Exception $e) 
		{
			$message = $e->getMessage();
			Mage::getSingleton('adminhtml/session')->addError($message);
		}
		$this->_redirect('*/*');
	}
}
?>