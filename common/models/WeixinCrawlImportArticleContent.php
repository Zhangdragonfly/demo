<?php

namespace common\models;

use Yii;


class WeixinCrawlImportArticleContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_crawl_import_article_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }


}
