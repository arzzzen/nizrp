<?php
namespace Admin\Lib\Ext;
use Admin\Lib\Bootstrap;

class Mailer {
    private $mail_conf = array();
    private $connect;

    /**
     * Получает коннфиг и создает соединение с сеервером почты
     */
    public function __construct() {        
        $this->mail_conf = Bootstrap::readConfig('mail');
        $host_string = "{"."{$this->mail_conf['host']}:{$this->mail_conf['port']}{$this->mail_conf['param']}"."}{$this->mail_conf['folder']}";

        if (!$this->connect = imap_open($host_string, $this->mail_conf['login'], $this->mail_conf['pass'])) {
            exit("Can't connect mail server: " . imap_last_error());
        }
    }
    
    /**
     * Возвращает массив писем
     * @return array Массив писем
     */
    public function getMails() {
        $emails = imap_search($this->connect, 'ALL');
        $messages = array();
        if ($emails) {
            /* put the newest emails on top */
            rsort($emails);
            $i = 0;
            foreach ($emails as $uid) {
                if ($i == 10) break;
                $i ++;
                $message_text = '';
                $message_text_html = '';
                $message_files = array();
                
                $body_parts = $this->getBodyParts($uid);
                foreach ($body_parts as $body_part) {
                    if ($body_part['subtype'] == 'PLAIN') {
                        $message_text = $body_part['content'];
                    } elseif ($body_part['subtype'] == 'HTML') {
                        $message_text_html = $body_part['content'];
                    } else {
                        $message_files[] = $body_part;
                    }
                }
                $messages[] = array(
                    'message_text' => $message_text_html ? $message_text_html : $message_text,
                    'message_files' => $message_files
                    );
            }
        }
        return $messages;
    }
    
    /**
     * 
     * @param array $parameters Массив параметров элемента imap_fetchstructure->parameters
     * @param ыекштп $param_name Имя параметра
     * @return string Значение параметра
     */
    private function getParamsAttr($parameters, $param_name) {
        $ret = '';
        foreach ($parameters as $parameter) {
            if ($parameter->attribute === $param_name) {
                $ret = $parameter->value;
            }
        }
        return $ret;
    }
    
    /**
     * Возвращает обработанную часть body письма
     * @param StdClass $part_structure Элемент массива imap_fetchstructure()->parts
     * @param integer $uid Номер письма
     * @param integer $i Номер части
     * @return array
     */
    private function getPart($part_structure, $uid, $i) {
        $body_part = imap_fetchbody($this->connect, $uid, $i, FT_PEEK);
        switch ($part_structure->encoding) {
            case 3:
                $body_part = imap_base64($body_part);
                break;
            case 4:
                $body_part = quoted_printable_decode($body_part);
                break;
        }
        
        if ($part_structure->ifparameters) {
            // Если part текстовая
            if ($part_structure->type == 0) {
                $charset = $this->getParamsAttr($part_structure->parameters, 'charset');
                $body_part = array(
                    'subtype' => $part_structure->subtype,
                    'content' => iconv($charset, 'utf-8', $body_part)
                    );
            } else {
                if (property_exists($part_structure, 'dparameters')) {
                    $filename = imap_utf8($this->getParamsAttr($part_structure->dparameters, 'filename'));
                } else {
                    $filename = 'Нет парметра dparameters';
                }
                $body_part = array(
                    'subtype' => $part_structure->subtype,
                    'content' => $body_part,
                    'filename' => $filename,
                    'size' => property_exists($part_structure, 'bytes') ? $part_structure->bytes : 'Нет bytes'
                    );
            }
        }
        return $body_part;
    }
    
    /**
     * Возвращает массив обработанных частей body письма с номером $uid
     * @param integer $uid Номер письма
     * @return array
     */
    private function getBodyParts($uid) {
        $structure = imap_fetchstructure($this->connect, $uid);
        $number_of_parts = count($structure->parts);
        for ($i = 1; $i <= $number_of_parts; $i++) {
            $part_structure = $structure->parts[$i-1];
            $body_parts[] = $this->getPart($part_structure, $uid, $i);
        }
        return $body_parts;
    }

    public function __destruct() {
        imap_close($this->connect);
    }

}