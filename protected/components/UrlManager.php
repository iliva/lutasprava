<?php
class UrlManager extends CUrlManager {

    public  $Section = null ;
    
    public function findControllerAlias($controller_name){

        $Section = Section::model()->published()->find('controller = :controller', array(':controller' => $controller_name.'Controller.php'));
        if(!$Section && $Section->code_name == '') return false;
        
        return $Section->code_name;
    }  
  
    
    public function createUrl($route,$params=array(),$ampersand='&'){

        if(Yii::app()->urlManager->Section->attributes['code_name'] != NULL){
            $route = Yii::app()->urlManager->Section->attributes['code_name'].'/'.$route;
        }  
        else{ 
            $route = Yii::app()->Getcontroller()->id.'/'.$route; 
        } 

        if (!isset($params['language'])) {
            if (Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            else if(isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
            $params['language']=Yii::app()->language;
        }
        return parent::createUrl($route, $params, $ampersand);
    }    
   
   
    public function parseUrl($request){
      
        $app = Yii::app(); 
        
        // Сначала парсим общие правила, для перегрузки обязательных ссылок
        // таких, как админка
        $route = parent::parseUrl($request); 
        
        // Проверяем, подошли ли общие правила и есть ли контроллер для них
        $ca = $app->createController($route);
        
        // Если контроллера под них не оказалось, смотрим разделы в БД
        if ($ca === null){
            // УРЛ, который ввел пользователь
            $fullPathInfo=$pathInfo=$this->removeUrlSuffix($request->getPathInfo(),$this->urlSuffix);

            // Убираем язык из урла, если включена их поддержка
            if ($app->params['createUrlLang']){
                $langList = array_values(Language::model()->getLanguageList('code_name'));
                $patern = implode('|', $langList);
                preg_match('/^('.$patern.')(\/)?/', $pathInfo, $matches);

                //var_dump($matches);
                if(@$matches[0] != null){ 

                    $matches[0]  = preg_replace('|/|', '', $matches[0]); 
                     
                    if(!in_array($matches[0], $langList))
                            Yii::app()->language = 'ua';
                    else
                        Yii::app()->language = $matches[0];
                        
                    $pathInfo   = preg_replace('|^('.$matches[0].'[/]?)|ui', '', $pathInfo);
                }   
            }    

            // тест (это для возможности добавлять .html в конце)
            $pathInfo   = preg_replace('|.html|ui', '', $pathInfo);
  
            $route = '/';
            return $route;

        }     
        return $route;
    }
    
    public function getPartInfo($part, $pathInfo){

        $partInfo = mb_substr($pathInfo, mb_strpos($pathInfo, $part, 0, 'utf-8'));
        
        return $partInfo;
    }
    
    public function parsePartInfo($partInfo, $Section){

        $partInfo = $Section->code_name.'/'.$partInfo;
        
        $controller = mb_strtolower(preg_replace('/Controller\.php$/ui', '', $Section->controller), 'utf-8');
        $Controller = Yii::app()->createController($controller);
        
        $controllerUrlRules = array();
        foreach ($Controller[0]->urlRules() as $rule)
        {
            $rule['pattern'] = preg_replace('/{this}/ui', $Section->code_name, $rule['pattern']);
            $controllerUrlRules[] = $rule;
        }
        $this->addRules($controllerUrlRules, false);
        
        $route = parent::parseUrl($partInfo);
        
        return 'test/test';
    }




}
