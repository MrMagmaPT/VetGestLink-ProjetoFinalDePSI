<?php 
namespace common\components\fullcalendar;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;

class FullcalendarWidget extends \yii\base\Widget{
	public $options=[];
	public $htmlOptions=[];
	
	public function run(){
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		$this->htmlOptions['id']=$id = $this->options['id'];
		
		$view = $this->getView();
		FullcalendarAsset::register($view);
		
		//Modificado para mostrar sem dar echo 
		$html = Html::beginTag('div', $this->htmlOptions);
		$html .= Html::endTag('div');

		$encodeoptions = Json::encode($this->options);
		
		$view->registerJs("jQuery('#$id').fullCalendar($encodeoptions);");
		return $html;
	}
}