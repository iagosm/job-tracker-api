# 🎯 JobTracker

> Organize sua jornada profissional. Controle suas candidaturas. Conquiste sua próxima oportunidade.

## 📖 Sobre o Projeto

O **JobTracker** é uma aplicação web desenvolvida para resolver um problema comum enfrentado por profissionais em busca de recolocação ou novas oportunidades: **perder o controle das vagas aplicadas**.

Com o JobTracker, você tem uma visão completa e organizada de todo o seu processo de candidatura, desde a aplicação inicial até o resultado final. Chega de esquecer de fazer follow-up, perder prazos de entrevistas ou não lembrar onde você aplicou!

## 🎯 Objetivo

Fornecer uma ferramenta simples, prática e eficiente para:

- Centralizar informações de todas as vagas aplicadas
- Acompanhar o status de cada candidatura em tempo real
- Registrar observações importantes sobre empresas e processos seletivos
- Ter uma visão clara e objetiva da sua jornada de busca profissional
- Aumentar sua produtividade e organização durante a busca por emprego

## ✨ Funcionalidades Principais

### 📝 Gestão de Vagas
- Cadastro completo de vagas de emprego
- Informações detalhadas: empresa, cargo, descrição, localização
- Links para a vaga original e perfil da empresa
- Data de aplicação registrada automaticamente

### 📊 Controle de Status
- Acompanhamento do progresso de cada candidatura
- Status disponíveis:
  - 🆕 **Salva** - Vaga encontrada, ainda não aplicou
  - 📤 **Aplicada** - Candidatura enviada
  - ⏳ **Em Processo** - Processo seletivo em andamento
  - 🗣️ **Entrevista** - Entrevista agendada ou realizada
  - ❌ **Recusada** - Não aprovado no processo
  - ✅ **Aprovada** - Oferta recebida
  - 🚫 **Cancelada** - Desistiu da vaga

### 📝 Anotações e Observações
- Campo livre para registrar informações importantes
- Anotações sobre entrevistas realizadas
- Feedbacks recebidos
- Próximos passos do processo

### 🔒 Autenticação de Usuários
- Sistema seguro de login e registro
- Cada usuário vê apenas suas próprias vagas
- Proteção de dados pessoais

### 🔍 Filtros e Busca
- Busca rápida por empresa ou cargo
- Filtros por status da candidatura
- Ordenação por data de aplicação

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11.x** - Framework PHP robusto e elegante
- **PHP 8.2+** - Linguagem de programação moderna
- **MySQL** - Banco de dados relacional
- **RESTful API** - Arquitetura de comunicação padronizada

### Arquitetura e Boas Práticas
- **Controllers** - Gerenciamento de rotas e requisições
- **Services** - Lógica de negócio isolada e reutilizável
- **Form Requests** - Validação robusta de dados
- **Eloquent ORM** - Manipulação elegante do banco de dados
- **Migrations** - Controle de versão do banco de dados
- **API Resources** - Formatação consistente de respostas JSON

### Frontend (Preparado para integração)
- API REST permite integração com:
  - Vue.js
  - React
  - Angular
  - Aplicativos mobile (React Native, Flutter)

### Segurança
- Autenticação via Laravel Sanctum/Passport
- Validação de dados em múltiplas camadas
- Proteção CSRF
- Hash de senhas com Bcrypt
- Rate limiting

## 📁 Estrutura do Projeto

```
jobtracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Controladores da API
│   │   ├── Requests/       # Validações de entrada
│   │   └── Resources/      # Formatação de respostas
│   ├── Models/             # Modelos Eloquent
│   └── Services/           # Lógica de negócio
├── database/
│   ├── migrations/         # Estrutura do banco de dados
│   └── seeders/           # Dados iniciais
├── routes/
│   ├── api.php            # Rotas da API
│   └── web.php            # Rotas web
├── tests/                 # Testes automatizados
└── .env                   # Configurações do ambiente
```

## 🚀 Como Instalar e Rodar o Projeto

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 5.7+ ou MariaDB 10.3+
- Node.js e NPM (opcional, para frontend)

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/iagosm/jobtracker.git
cd jobtracker
```

2. **Instale as dependências do PHP**
```bash
composer install
```

3. **Configure o arquivo de ambiente**
```bash
cp .env.example .env
```

4. **Edite o arquivo `.env` com suas credenciais do banco de dados**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jobtracker
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Gere a chave da aplicação**
```bash
php artisan key:generate
```

6. **Crie o banco de dados**
```bash
mysql -u root -p
CREATE DATABASE jobtracker;
exit;
```

7. **Execute as migrations**
```bash
php artisan migrate
```

8. **Inicie o servidor de desenvolvimento**
```bash
php artisan serve
```

9. **Acesse a aplicação**
```
http://localhost:8000
```

### Configuração Adicional (Opcional)

**Instalar dependências do frontend (se houver)**
```bash
npm install
npm run dev
```

**Popular banco com dados de teste**
```bash
php artisan db:seed
```

## 💡 Exemplos de Uso

### Cenário 1: Cadastrando uma Nova Vaga

Maria encontrou uma vaga interessante de Desenvolvedora Backend no LinkedIn. Ela acessa o JobTracker e:

1. Clica em "Nova Vaga"
2. Preenche os campos:
   - Empresa: TechCorp
   - Cargo: Desenvolvedora Backend Sênior
   - Status: Salva
   - Link: [URL da vaga]
   - Observações: "Vaga alinhada com minhas skills. Requisitos: PHP, Laravel, MySQL"
3. Salva a vaga

**Resultado**: A vaga fica registrada com status "Salva" para Maria aplicar quando estiver pronta.

### Cenário 2: Atualizando o Status

João aplicou para uma vaga há 3 dias e recebeu um email marcando entrevista. Ele:

1. Acessa a vaga no JobTracker
2. Altera o status de "Aplicada" para "Entrevista"
3. Adiciona nas observações: "Entrevista técnica agendada para 15/02 às 14h com o time de engenharia"

**Resultado**: João tem um registro claro do próximo passo e não esquece do compromisso.

### Cenário 3: Analisando o Progresso

Ana quer ter uma visão geral de suas candidaturas. Ela:

1. Acessa o dashboard do JobTracker
2. Vê que tem:
   - 15 vagas aplicadas
   - 3 entrevistas agendadas
   - 2 processos em andamento
   - 1 aprovação
3. Identifica que precisa fazer follow-up em algumas empresas que não deram retorno

**Resultado**: Ana tem controle total da sua busca e pode agir proativamente.

## 🔮 Melhorias Futuras

### Curto Prazo
- [ ] Dashboard com estatísticas e gráficos
- [ ] Sistema de notificações e lembretes
- [ ] Exportação de relatórios em PDF
- [ ] Tags personalizadas para categorização de vagas
- [ ] Campo para salário esperado e proposta recebida

### Médio Prazo
- [ ] Integração com calendário (Google Calendar, Outlook)
- [ ] Cronômetro de tempo de processo seletivo
- [ ] Histórico de alterações de status
- [ ] Anexar documentos (currículo, carta de apresentação)
- [ ] Sistema de follow-up automático

### Longo Prazo
- [ ] Aplicativo mobile nativo (iOS e Android)
- [ ] Integração com LinkedIn para importar vagas
- [ ] Sistema de recomendações baseado em perfil
- [ ] Comunidade de usuários para troca de experiências
- [ ] Análise de mercado e tendências de contratação
- [ ] Sugestões de melhoria de perfil com IA


## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Iago Sousa Miranda**

Desenvolvedor apaixonado por criar soluções que facilitam a vida das pessoas. O JobTracker nasceu da necessidade pessoal de organizar melhor minha própria busca por oportunidades profissionais.

- LinkedIn: [linkedin.com/in/iagosm](https://linkedin.com/in/iagosm)
- GitHub: [github.com/iagosm](https://github.com/iagosm)
- Email: iagosousa201486@gmail.com

## 🙏 Agradecimentos

Este projeto foi desenvolvido com o objetivo de ajudar profissionais a terem mais controle e clareza durante a busca por novas oportunidades. Se o JobTracker foi útil para você, considere dar uma ⭐ no repositório!

---

**JobTracker** - Sua jornada profissional merece organização! 🚀💼