# Estacionamento IoT
## Sobre o Projeto
Este é um sistema IoT para monitorização e controlo em tempo real de estacionamentos, desenvolvido para a cadeira de Tecnologias de Informação. O projeto implementa uma API RESTful para gestão de sensores de estacionamento, utilizando PHP 8.4.
## Requisitos do Sistema
- PHP 8.4 ou superior
- Servidor web (Apache, Nginx, etc.)
- Composer (gerenciador de dependências do PHP)
- WAMP, XAMPP ou ambiente similar (recomendado para desenvolvimento local)

## Instalação
### 1. Configuração do Ambiente
1. **Instale um servidor web local:**
    - WAMP (Windows): [https://www.wampserver.com/](https://www.wampserver.com/)
    - XAMPP (Multiplataforma): [https://www.apachefriends.org/](https://www.apachefriends.org/)
    - MAMP (Mac): [https://www.mamp.info/](https://www.mamp.info/)

2. **Verifique a versão do PHP:**
    - O projeto requer PHP 8.4 ou superior
    - No terminal ou prompt de comando, execute:
``` 
     php --version
```
1. **Instale o Composer:**
    - Baixe e instale o Composer a partir de [https://getcomposer.org/download/](https://getcomposer.org/download/)

### 2. Clone do Repositório
``` bash
# Clone o repositório para o diretório do seu servidor web
git clone [URL_DO_REPOSITÓRIO] projetoTI

# Navegue até o diretório do projeto
cd projetoTI
```
### 3. Instalação de Dependências
``` bash
# Instale as dependências do projeto com o Composer
composer install
```
### 4. Configuração do Servidor Web
#### Para WAMP/XAMPP/MAMP:
1. Certifique-se de que o projeto esteja no diretório web (geralmente `www` ou `htdocs`)
2. Configure o servidor para apontar para o diretório `public` do projeto
3. Ative o módulo de reescrita de URL (mod_rewrite) no Apache

#### Exemplo de configuração virtual host (Apache):
``` apache
<VirtualHost *:80>
    ServerName estacionamento-iot.local
    DocumentRoot "D:/wamp64/www/projetoTI/public"
    
    <Directory "D:/wamp64/www/projetoTI/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Adicione também uma entrada no arquivo hosts do seu sistema:
``` 
127.0.0.1 estacionamento-iot.local
```
## Estrutura do Projeto
``` 
projetoTI/
├── public/            # Diretório público (ponto de entrada)
│   ├── index.php      # Controlador frontal
│   └── views/         # Vistas HTML/PHP
├── src/               # Código fonte do projeto
│   ├── Api/           # Endpoints da API
│   ├── Core/          # Interfaces e contratos
│   ├── Domain/        # Modelos de domínio
│   ├── Infrastructure/# Implementações concretas
│   └── Service/       # Serviços da aplicação
├── vendor/            # Dependências (gerenciado pelo Composer)
├── composer.json      # Configuração do Composer
└── README.md          # Documentação do projeto
```
## Execução do Projeto
1. Inicie o seu servidor web (WAMP, XAMPP, etc.)
2. Acesse o projeto através do navegador:
    - [http://localhost/projetoTI/public/](http://localhost/projetoTI/public/) (configuração padrão)
    - [http://estacionamento-iot.local/](http://estacionamento-iot.local/) (se configurou um virtual host)

## API Endpoints
O projeto disponibiliza os seguintes endpoints de API:
### Sensores
- **GET /src/api/v1/sensors** - Lista todos os sensores
- **GET /src/api/v1/sensors/:id** - Obtém detalhes de um sensor específico
- **POST /src/api/v1/sensors** - Cria um novo sensor

### Autenticação
- **POST /src/api/v1/login** - Autenticação de utilizadores

## Desenvolvimento e Personalização
### Repositório de Dados
O projeto utiliza um repositório baseado em arquivos JSON para armazenar os dados dos sensores. O arquivo de dados está localizado em:
``` 
src/data/sensor.json
```
Para alterar a fonte de dados:
1. Implemente uma nova classe que implemente a interface `SensorRepositoryInterface`
2. Atualize as referências nas rotas da API para utilizar a nova implementação

### Adicionando Novos Sensores
Você pode adicionar sensores ao sistema através da API ou manipulando diretamente o arquivo JSON de dados.
#### Formato de Sensor:
``` json
{
  "id": "string",
  "name": "string",
  "type": "temperature|presence|humidity|pressure|unknown",
  "location": "string",
  "status": "active|inactive|maintenance",
  "metadata": {}
}
```
## Resolução de Problemas
### Permissões de Arquivo
Se encontrar problemas ao salvar dados:
``` bash
# Defina permissões de escrita para o diretório de dados
chmod 775 src/data
chmod 664 src/data/sensor.json
```
### Módulo de Reescrita não Funcionando
Certifique-se de que o módulo de reescrita está ativo no seu servidor:
``` bash
# Para Apache
a2enmod rewrite
service apache2 restart
```
## Contribuição
Para contribuir com o projeto:
1. Faça um fork do repositório
2. Crie uma branch para sua funcionalidade (`git checkout -b feature/nova-funcionalidade`)
3. Faça commit das suas alterações (`git commit -m 'Adiciona nova funcionalidade'`)
4. Faça push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

Desenvolvido para a cadeira de Tecnologias de Informação
