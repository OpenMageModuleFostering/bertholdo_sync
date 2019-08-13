Portugues

Segue as informações do módulo Sync da Bertholdo Consultoria

Passos para utilização do módulo

1 - Na administração do módulo escolha uma categoria para realizar a geração do arquivo CSV Padrão, caso queira todos os produtos não modifique a mesma;
2 - Ainda na administração do módulo insira o percentual de atualização do estoque na loja;
<!-- 3 - Insira o filtro de quantidade de estoque, somente produtos com estoque inferior ao valor serão filtrados.-->
4 - Gere o arquivo CSV para poder modificar as informações dos produtos clicando no botão -> Gerar arquivo CSV Padrão;
5 - Após o passo anterior efetue o download do arquivo CSV para sua máquina e modifique os valores dos produtos no arquivo;
6 - Após modificar o arquivo CSV selecione (Selecionar arquivo...) escolha o arquivo CSV modificado;
7 - Click em (Importar);
8 - Verifique as informações dos produtos;

Ao instalar o módulo limpe as pastas var/cache e var/session e realize um novo login.

O módulo Brt Sync foi idealizado pela empresa Bertholdo Consultoria. O módulo tem  
a finalidade de ajudar no dia a dia dos administradores de lojas virtuais que não possuem ferramentas especializadas para o gerenciamento de estoque, possibilitando uma diferenciação e controle, entre o estoque da loja física e virtual. Além disso, é possível, também, fazer a inserção de novos produtos via importação. 

O processo é bem simples, basta gerar o arquivo CSV baseado nos filtros do módulo e modificar as informações dos produtos. Caso queira cadastrar um produto através do arquivo, é só inserir as informações do mesmo, lembrando-se sempre de seguir o formato do arquivo e realizar sua importação via administração. Criamos um vídeo que demonstra a utilização do módulo seguindo todos os passos necessários visando facilitar a utilização.

Siga as redes sociais da Bertholdo e fique por dentro das novidades da plataforma Magento:

Página Facebook: http://goo.gl/8Dgnt5
Blog: http://www.bertholdo.com.br/blog/
Site: http://www.bertholdo.com.br/
Video do módulo: https://www.youtube.com/watch?v=QI7gCXV-WEs

Campos do CSV padrão

descricao_produto -> Descrição do produto
codigo_sku_sistema_legado -> Código do sistema legado (ERP), esse campo vai ser utilizado na atualização dos estoques
preco_produto -> Preço do produto (Separado por ponto ou virgula (.) ou (,))
qtd_estoque -> Quantidade total do produto no estoque físico da loja
percentual_atualizacao -> Fatia em percentual sobre a quantidade total de estoque físico que vai para à loja virtual  
(Basicamente pegue o estoque total do produto na loja física e insira no campo de estoque com isso o percentual que for inserido nesse campo vai ser aplicado e ajustado na loja virtual). Obs: Se esse campo não for preenchido o mesmo vai  
ser lido das configurações do módulo (Sync-> Configurações).

A pasta media deve estar com permissão 0777.

Obs: Se o produto não for novo somente o preço e o estoque serão modificados agora se o mesmo não existe e foi inserido no arquivo CSV serão inseridos o nome, estoque e preço.

=================================================================================================================================

English

Following information from Sync module Bertholdo Consulting

Steps to use the module

1 - In the administration module select a category to perform the generation of the standard CSV file if you want all products not modify it; 
2 - Still in the administration module enter the percentage of updated stock in the store; 
<!--3 - Enter the amount of stock filter, only products with lower than stock will be filtered.-->
4 - Generate the CSV file in order to modify the information of the products by clicking on the button -> Generate CSV Standard; 
5 - After the above please download the CSV file to your machine and step modify the product values ??in the file; 
6 - After modifying select the CSV file (Select File ...) choose the modified CSV file; 
7 - Click on (Import); 
8 - Check the product information;

When installing the module clean folders var/cache and var/session and perform a new login .

The Brt Sync module was designed by the company Bertholdo Consulting. The module has
the purpose of assisting in the day to day management of virtual stores that do not have specialized for inventory management tools, enabling differentiation and control of the stock of physical and virtual store. Moreover, it is also possible to do the insert of new products via import.

The process is very simple, just generate the CSV file based on the filters of the module and modify the information of the products. If you want to register a product through the file, just enter the information yourself, always remembering to follow the format of the file and perform their import via administration. We created a video that demonstrates the use of the module followed all necessary steps to facilitate the use.

Follow the social networking Bertholdo and stay tuned with news of the Magento platform:

Facebook Page: http://goo.gl/8Dgnt5
Blog: http://www.bertholdo.com.br/blog/
Site: http://www.bertholdo.com.br/
Video module: https://www.youtube.com/watch?v=QI7gCXV-WEs

Fields of the CSV standard

descricao_produto - > Product Description
codigo_sku_sistema_legado - this field > code of the legacy system ( ERP ) , will be used to update the inventory
preco_produto - > Product Price ( Separated by comma or point (.) or (,))
qtd_estoque - > Total quantity of the product in the physical store inventory
percentual_atualizacao - > Slice on a percentage of the total amount of physical stock that goes to the shop
( Basically take the total stock of the product in the physical store and enter the field with that stock the percentage that is entered this field will be applied and adjusted in the virtual store ). Note: If this field is not completed it will
be read the settings ( Sync - > Settings ) module.

The media folder should be with permission 0777 .

Note: If the product is not only the new price and the stock now will be modified if it does not exist and was inserted into the CSV file name, and stock price will be inserted.