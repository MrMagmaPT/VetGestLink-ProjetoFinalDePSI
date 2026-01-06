<?php

namespace common\components;

use Yii;
use yii\base\Component;
use Bluerhinos\phpMQTT;

class MqttHelper extends Component
{
    /**
     * Publica uma mensagem no broker MQTT
     * @param string $canal Tópico MQTT
     * @param string $msg Mensagem (geralmente JSON)
     * @return bool True se publicou com sucesso, false caso contrário
     */
    public static function publish($canal, $msg)
    {
        $server = "172.22.21.220";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT-publisher-" . uniqid();

        $mqtt = new phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
            return true;
        } else {
            Yii::error("Falha ao conectar ao MQTT: canal={$canal}", __METHOD__);
            return false;
        }
    }
}
