<?php

  /**
   * OBJETO DE CONFIGURAÇÃO GERAL DO AMBIENTE MyOMNI
   */
  class Ambiente {

    public $server = "localhost/es";        //URL da aplicação
    public $lang = "pt-BR";                   //Definie idioma padrão
    public $charSet = "UTF-8";                //Define charset default
    public $timeZone = "America/Sao_Paulo";   //Define timezone
    public $environment = "DEVELOPMENT";      //Ambiente atual da aplicação 'PRODUCTION' / 'DEVELOPMENT' / 'MAINTENANCE'
    public $orquestrador = "http://chat.enterness.com";
    public $bearer = "Bearer onYz2EHfTSmo9CQz0ta0NbNAVH1LAtjjA5m3DedV3TLnZnDf5H";
    public $urlMidias = "/data/www/default/omni/my/assets/medias/"; //Caminho onde as mídias ficarão armazenadas (necessário perm. 777)

    //CONFIGURAÇÔES DE BANCO DE DADOS
    private $db_host = "localhost";
    private $db_database = "my_omni";
    private $db_user = "root";
    private $db_pass = "";
    private $db_port = 80;

    //CONFIGURAÇÕES DAS APIS DE MÍDIAS SOCIAIS
    /*WhatsApp*/
    private $whatsAppServer = "http://68.235.33.35:8100/";
    private $m_WhatsApp_url = "chats/XXXXXX/messages";
    private $c_WhatsApp_url = "messages/unread";
    private $whatsapp_port = 8100;
    private $userWhatsApp = "t09";
    private $tokenWhatsApp = "QjbXR1w2f0bRB55M8jrF2tBU6tpTkkAu";
    /*Telegram*/
    private $m_Telegram_url = "https://orchestrator.enterness.com:8243/telegram/v1.0.0/bot686376695%3AAAH7RxnwfKlP4R4PTnvkD9GgkQ9j4Sw7zJU/sendMessage?chat_id=XXXXXX&text=YYYYYY";
    private $c_Telegram_url = "https://orchestrator.enterness.com:8243/telegram/v1.0.0/bot686376695%3AAAH7RxnwfKlP4R4PTnvkD9GgkQ9j4Sw7zJU/getUpdates";
    /*Facebook Messenger*/
    private $m_Messenger_url = "";
    private $c_Messenger_url = "";
    /*Skype*/
    private $m_Skype_url = "";
    private $c_Skype_url = "";
    /*Sms*/
    private $m_Sms_url = "";
    private $c_Sms_url = "";

    function __construct(){
      if($this->timeZone != ""){
        date_default_timezone_set($this->timeZone);
      }
    }

    //Funções GETTERS
    public function getEnvironment(){
      return $this->environment;
    }

    public function getUserApi(){
      return $this->userWhatsApp;
    }

    public function getTokenWhatsApp(){
      return $this->tokenWhatsApp;
    }

    public function getWhatsAppServer(){
      return $this->whatsAppServer;
    }

    public function getWhatsAppUrl(){
      return $this->whatsAppServer . $this->m_WhatsApp_url;
    }

    public function getWhatsAppUrlCheck(){
      return $this->whatsAppServer . $this->c_WhatsApp_url;
    }

    public function getWhatsAppPort(){
      return $this->whatsapp_port;
    }

    public function getTelegramUrl(){
      return $this->m_Telegram_url;
    }

    public function getTelegramUrlCheck(){
      return $this->c_Telegram_url;
    }

    public function getMessengerUrl(){
      return $this->m_Messeger_url;
    }

    public function getMessengerUrlCheck(){
      return $this->c_Messeger_url;
    }

    public function getSkypeUrl(){
      return $this->m_Skype_url;
    }

    public function getSkypeUrlCheck(){
      return $this->c_Skype_url;
    }

    public function getSmsUrl(){
      return $this->m_Sms_url;
    }

    public function getSmsUrlCheck(){
      return $this->c_Sms_url;
    }

    //Funções GETTERS para BD
    public function dbHost(){
      return $this->db_host;
    }

    public function dbDatabase(){
      return $this->db_database;
    }

    public function dbUser(){
      return $this->db_user;
    }

    public function dbPass(){
      return $this->db_pass;
    }

    public function dbPort(){
      return $this->db_port;
    }

    //Funções Setters
    //Prepara URL da API do WhatsApp
    public function setUrlWhatsApp($dst, $msg){
      $dst = htmlentities(urlencode($dst));
      //$msg = rawurlencode($msg);
      $url = $this->m_WhatsApp_url;
      $url = str_replace('XXXXXX', $dst, $url);
      $url = str_replace('YYYYYY', $msg, $url);
      $this->m_WhatsApp_url = $url;
    }

    //Função para a nova versão da API
    public function getUrlWhatsApp($dst){
      $dst = htmlentities(urlencode($dst));
      $url = $this->whatsAppServer . $this->m_WhatsApp_url;
      $url = str_replace('XXXXXX', $dst, $url);
      return $url;
    }

    //Prepara URL da API do Telegram
    public function setUrlTelegram($dst, $msg){
      $dst = htmlentities(urlencode($dst));
      $msg = rawurlencode($msg);
      $url = $this->getTelegramUrl();
      $url = str_replace('XXXXXX', $dst, $url);
      $url = str_replace('YYYYYY', $msg, $url);
      $this->m_Telegram_url = $url;
    }

  }

 ?>
