<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 10/7/16 3:57 PM
 */

namespace wom\modules\site\controllers;

use wom\controllers\BaseAppController;
use Yii;

/**
 * 解决方案
 * Class SolutionController
 * @package wom\modules\site\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class SolutionController extends BaseAppController
{
    public $layout = '//media-stage';

    public function actionIndex()
    {
        return $this->render('index');
    }
}