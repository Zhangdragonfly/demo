DROP TABLE IF EXISTS `dev-wom2`.`media_vendor`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_vendor` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uuid` VARCHAR(45) NOT NULL COMMENT 'vendor uuid\n',
  `account_uuid` VARCHAR(45) NOT NULL COMMENT 'account uuid',
  `name` VARCHAR(45) NOT NULL COMMENT '供应商名称',
  `contact_person` VARCHAR(45) NOT NULL COMMENT '联系人姓名',
  `contact1` VARCHAR(45) NOT NULL COMMENT '联系方式',
  `contact2` VARCHAR(45) NULL COMMENT '联系方式2',
  `type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '类型\n1 个人\n2 公司/工作室\n0 未知',
  `comment` TEXT NULL COMMENT '备注',
  `comp_industry` VARCHAR(45) NULL COMMENT '所在行业',
  `comp_address` VARCHAR(256) NULL COMMENT '公司地址',
  `comp_website` VARCHAR(45) NULL COMMENT '网站',
  `balance` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '当前账户余额',
  `last_update_time` INT NOT NULL COMMENT '最后更新基本信息时间',
  `bank_account_1` VARCHAR(45) NULL COMMENT '银行账户',
  `bank_account_2` VARCHAR(45) NULL COMMENT '银行账户',
  `status` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '0 无效\n1 有效',
  `is_assigned` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '是否被分配给媒介 1 是 0 否',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `vendor_uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `account_uuid_idx` (`account_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '媒体供应商信息表';

DROP TABLE IF EXISTS `dev-wom2`.`media_executor_vendor_bind`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_executor_vendor_bind` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uuid` VARCHAR(45) NOT NULL COMMENT 'bind uuid',
  `media_executor_uuid` VARCHAR(45) NOT NULL COMMENT '媒介运营uuid',
  `media_vendor_uuid` VARCHAR(45) NOT NULL COMMENT 'vendor uuid',
  `bind_time` INT NOT NULL COMMENT '分配时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `bind_uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `media_vendor_uuid_idx` (`media_vendor_uuid` ASC)  COMMENT '',
  INDEX `media_execor_uuid_idx` (`media_execor_uuid` ASC)  COMMENT ''
)
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '媒介运营与供应商bind表 (媒介运营负责管理某些供应商)';

DROP TABLE IF EXISTS `dev-wom2`.`media_vendor_bind`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_vendor_bind` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `media_type` TINYINT(4) NOT NULL COMMENT '媒体类型\n1 微信\n2 微博\n3 。。。\n',
  `media_uuid` VARCHAR(45) NOT NULL COMMENT '媒体uuid',
  `vendor_uuid` VARCHAR(45) NOT NULL COMMENT 'vendor uuid',
  `biz_type` TINYINT(4) NOT NULL COMMENT '业务类型（1 自营 2 独家 3 一手 4 代理）',
  `is_activated` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '激活\n(1 激活\n0 未激活)',
  `coop_level` TINYINT(4) NOT NULL DEFAULT 4 COMMENT '配合度（1 高 2 中 3 低 4 未知）\n',
  `status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '状态\n(0 待审核\n1 审核通过\n2 审核未通过)',
  `comment` TEXT NULL COMMENT '备注',
  `pay_period` TINYINT(4) NULL COMMENT '账期\n\n',
  `create_time` INT NOT NULL COMMENT '入驻时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `media_uuid_idx` (`media_uuid` ASC)  COMMENT '',
  INDEX `vendor_uuid_fk_idx` (`vendor_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '媒体（所有媒体，包括微信、微博等等） 与 供应商 bind表';

DROP TABLE IF EXISTS `dev-wom2`.`media_vendor_weixin_price_list`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_vendor_weixin_price_list` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `bind_uuid` VARCHAR(45) NOT NULL COMMENT 'media vendor bind uuid',
  `s_pub_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '单图文发布类型 0 不接单 1 只直投 2 只原创 3 可直投可原创（暂不考虑）',
  `m_1_pub_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '多图文第一条发布类型',
  `m_2_pub_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '多图文第二条发布类型',
  `m_3_pub_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '多图文3-n发布类型',
  `orig_price_s_min` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（单图文min价格）',  
  `orig_price_s_max` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（单图文max价格）', 
  `orig_price_m_1_min` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文头条min报价）',
  `orig_price_m_1_max` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文头条max报价）',
  `orig_price_m_2_min` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文第二条min报价）',
  `orig_price_m_2_max` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文第二条max报价）',
  `orig_price_m_3_min` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文3-n条min报价）',
  `orig_price_m_3_max` DECIMAL(10,2) NOT NULL COMMENT '供应商报价（多图文3-n条max报价）',
  `retail_price_s_min` DECIMAL(10,2) COMMENT '媒体零售价（单图文min报价）',
  `retail_price_s_max` DECIMAL(10,2) COMMENT '媒体零售价（单图文max报价）',
  `retail_price_m_1_min` DECIMAL(10,2) COMMENT '媒体零售价（多图文第一条min报价）',
  `retail_price_m_1_max` DECIMAL(10,2) COMMENT '媒体零售价（多图文第一条max报价）',
  `retail_price_m_2_min` DECIMAL(10,2) COMMENT '媒体零售价（多图文第二条min报价）',
  `retail_price_m_2_max` DECIMAL(10,2) COMMENT '媒体零售价（多图文第二条max报价）',
  `retail_price_m_3_min` DECIMAL(10,2) COMMENT '媒体零售价（多图文第3-n条min报价）',
  `retail_price_m_3_max` DECIMAL(10,2) COMMENT '媒体零售价（多图文第3-n条max报价）',
  `coop_price_s` DECIMAL(10,2) COMMENT '平台合作价（单图文价格）',
  `coop_price_m_1` DECIMAL(10,2) COMMENT '平台合作价（多图文第一条价格）',
  `coop_price_m_2` DECIMAL(10,2) COMMENT '平台合作价（多图文第二条价格）',
  `coop_price_m_3` DECIMAL(10,2) COMMENT '平台合作价（多图文第3-n条价格）',
  `prmt_price_s` DECIMAL(10,2) COMMENT '媒体促销折扣价（单图文价格）',
  `prmt_price_m_1` DECIMAL(10,2) COMMENT '媒体促销折扣价（多图文第一条价格）',
  `prmt_price_m_2` DECIMAL(10,2) COMMENT '媒体促销折扣价(多图文第二条价格)',
  `prmt_price_m_3` DECIMAL(10,2) COMMENT '媒体促销折扣价(多图文3-n价格)',
  `prmt_coop_price_s` DECIMAL(10,2) COMMENT '媒体促销平台合作价(单图文价格)',
  `prmt_coop_price_m_1` DECIMAL(10,2) COMMENT '媒体促销平台合作价（多图文第一条价格）',
  `prmt_coop_price_m_2` DECIMAL(10,2) COMMENT '媒体促销平台合作价(多图文第二条价格)',
  `prmt_coop_price_m_3` DECIMAL(10,2) COMMENT '媒体促销平台合作价(多图文3-n价格)',
  `pub_config` TEXT NULL COMMENT '',
  `deposit_percent_config` TEXT NULL COMMENT '', 
  `serve_percent_config` TEXT NULL COMMENT '',
  `prmt_start_time` INT COMMENT '促销开始时间',
  `prmt_end_time` INT COMMENT '促销结束时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '供应商价格表';


DROP TABLE IF EXISTS `dev-wom2`.`media_weixin`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_weixin` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uuid` VARCHAR(45) NOT NULL COMMENT '\n媒体uuid',
  `public_name` VARCHAR(45) NOT NULL COMMENT '微信名称',
  `public_id` VARCHAR(45) NOT NULL COMMENT '平台id',
  `follower_num` INT NOT NULL DEFAULT 0 COMMENT '粉丝数\n\n0 未知',
  `follower_screenshot` VARCHAR(256) NULL COMMENT '粉丝截图',
  `avatar_img` VARCHAR(245) NULL COMMENT '头像',
  `qrcode_img` VARCHAR(250) NULL COMMENT '二维码',
  `put_up` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '上架\n0 未上架\n1 上架',
  `status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '状态\n0 待审核（默认）\n1 资料审核通过\n2 资料审核未通过',
  `cust_sort` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '用于人工干预排序\n5 最高\n0 最低\n\n5 为置顶',
  `desc` TEXT NULL COMMENT '账号简介',
  `account_type` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '账号类型\n0 未知\n1 订阅号\n2 服务号\n',
  `account_cert` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '微信认证\n0 未知\n1 认证\n2 未认证',
  `media_level` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '媒体等级\n0 未设置\n1 精选\n2 优质\n3 草根',
  `s_pub_type` TINYINT(4) DEFAULT 0 COMMENT '单图文发布类型\n\n0 不接单\n1 直接发布（只发布）\n2 原创约稿（只原创）',
  `m_1_pub_type` TINYINT(4) DEFAULT 0 COMMENT '多图文第一条发布类型\n同单图文',
  `m_2_pub_type` TINYINT(4) DEFAULT 0 COMMENT '多图文第二条发布类型\n同单图文',
  `m_3_pub_type` TINYINT(4) DEFAULT 0 COMMENT '多图文（3-n）发布类型\n同单图文',
  `orig_price_s_min` DECIMAL(10,2) NULL COMMENT '供应商报价\n单图文min价格',
  `orig_price_s_max` DECIMAL(10,2) NULL COMMENT '供应商报价\n单图文max价格',
  `orig_price_m_1_min` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（头条）min报价',
  `orig_price_m_1_max` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（头条）max报价',
  `orig_price_m_2_min` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（第二条）min报价',
  `orig_price_m_2_max` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（第二条）max报价',
  `orig_price_m_3_min` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（其他）min报价',
  `orig_price_m_3_max` DECIMAL(10,2) NULL COMMENT '供应商报价\n多图文（其他）max报价',
  `retail_price_s_min` DECIMAL(10,2) NULL COMMENT '媒体零售价\n单图文min报价',
  `retail_price_s_max` DECIMAL(10,2) NULL COMMENT '媒体零售价\n单图文max报价',
  `retail_price_m_1_min` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（第一条）min报价',
  `retail_price_m_1_max` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（第一条）max报价',
  `retail_price_m_2_min` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（第二条）min报价',
  `retail_price_m_2_max` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（第二条）max报价',
  `retail_price_m_3_min` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（其他）min报价',
  `retail_price_m_3_max` DECIMAL(10,2) NULL COMMENT '媒体零售价\n多图文（其他）max报价',
  `coop_price_s` DECIMAL(10,2) NULL COMMENT '媒体合作价\n单图文',
  `coop_price_m_1` DECIMAL(10,2) NULL COMMENT '媒体合作价\n多图文第一条',
  `coop_price_m_2` DECIMAL(10,2) NULL COMMENT '媒体合作价\n多图文第二条',
  `coop_price_m_3` DECIMAL(10,2) NULL COMMENT '媒体合作价\n多图文第三~N条',
  `prmt_price_s` DECIMAL(10,2) NULL COMMENT '媒体促销折扣价\n单图文价格',
  `prmt_price_m_1` DECIMAL(10,2) NULL,
  `prmt_price_m_2` DECIMAL(10,2) NULL COMMENT '媒体促销折扣价\n多图文（第二条）价格',
  `prmt_price_m_3` DECIMAL(10,2) NULL COMMENT '媒体促销折扣价\n多图文（其他）价格',
  `prmt_coop_price_s` DECIMAL(10,2) NULL COMMENT '媒体促销平台合作价\n单图文价格',
  `prmt_coop_price_m_1` DECIMAL(10,2) NULL COMMENT '媒体促销平台合作价\n多图文（第一条）价格',
  `prmt_coop_price_m_2` DECIMAL(10,2) NULL COMMENT '媒体促销平台合作价\n多图文（第二条）价格',
  `prmt_coop_price_m_3` DECIMAL(10,2) NULL COMMENT '媒体促销平台合作价\n多图文（其他）价格',
  `pub_config` TEXT NULL COMMENT '发�' /* comment truncated */ /*�配置

{
"pos_s":{
"pub_type":0,
"orig_price_min":1111,
"orig_price_max":2222,
"retail_price_min":1111,
"retail_price_max":2222,
“coop_price”:2222,
},
"pos_m_1":{
"pub_type":1,
"orig_price_min":1111,
"orig_price_max":2222,
"retail_price_min":1111,
"retail_price_max":2222,
“coop_price”:2222,
},
"pos_m_2":{
"pub_type":2,
"orig_price_min":1111,
"orig_price_max":2222,
"retail_price_min":1111,
"retail_price_max":2222,
“coop_price”:2222,
},
"pos_m_3":{
"pub_type":2,
"orig_price_min":1111,
"orig_price_max":2222,
"retail_price_min":1111,
"retail_price_max":2222,
“coop_price”:2222,
}
}*/,
 `belong_tag` TEXT NULL COMMENT '标签\n格式：1#2#3#\n',
  `follower_area` TEXT NULL COMMENT '粉丝覆盖区域\n格式：1#2#3#',
  `comment` TEXT NULL COMMENT '接单备注',
  `prmt_start_time` INT NULL COMMENT '促销开始时间',
  `prmt_end_time` INT NULL COMMENT '促销结束时间',
  `last_crawl_time` INT NULL COMMENT '最后爬取时间',
  `has_pref_vendor` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '是否设置首选供应商\n1 已设置\n0 未设置',
  `pref_vendor_uuid` VARCHAR(45) NULL COMMENT '首选供应商uuid',
  `in_wom_rank` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '是否上沃米排行榜\n1 是\n0 否',
  `create_time` INT NOT NULL COMMENT '新建时间（入驻时间）',
  `last_update_time` INT NOT NULL COMMENT '基本信息最后修改时间（待研究）',
  `last_put_up_time` INT NULL COMMENT '最近上架时间',
  `is_activated` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '是否被激活\n1 是\n0 否',
  `has_origin_pub` tinyint(4) NULL COMMENT '是否存在原创约稿

1 存在
0 不存在' AFTER `is_activated`;


  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `media_uuid_UNIQUE` (`uuid` ASC))
ENGINE = INNODB
DEFAULT CHARSET = utf8
COMMENT = '微信资源信息表';
    
    
DROP TABLE IF EXISTS `dev-wom2`.`media_weixin`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_weixin` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '帐号ID',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `account_uuid` VARCHAR(45) NOT NULL COMMENT 'account uuid',
  `contact_name` VARCHAR(45) NOT NULL COMMENT '联系人姓名',
  `contact_1` VARCHAR(45) NOT NULL COMMENT '联系方式1',
  `contact_2` VARCHAR(45) NULL COMMENT '联系方式2',
  `comp_name` VARCHAR(45) NOT NULL COMMENT '公司名',
  `pay_pwd` VARCHAR(45) NULL COMMENT '\n支付密码',
  `cust_credit_fund` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '定制授信\n0 否\n1 是',
  `total_frozen_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '总冻结金额',
  `total_frozen_topup` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '总冻结现金',
  `total_frozen_credit` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '总冻结授信',
  `total_available_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '当前总可用金额',
  `total_available_topup` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '当前总可用现金',
  `total_available_credit` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '当前总可用授信',
  `status` TINYINT(4) NOT NULL COMMENT '状态\n1 有效\n0 无效',
  `not_yet_order` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '\n是否下过订单\n1 未\n0 已经\n默认1 ',
  `reg_random_code` VARCHAR(45) NULL COMMENT '\n注册时随机验证码',
  `remark` TEXT NULL COMMENT '备注',
  `auth_code` VARCHAR(45) NULL COMMENT '\n账号认证',
  `last_update_time` INT NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '广告主账号表';  

DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_plan`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_plan` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uuid` VARCHAR(45) NOT NULL COMMENT '订单唯一uuid',
  `ad_owner_uuid` VARCHAR(45) NOT NULL COMMENT '广告主uuid',
  `name` VARCHAR(256) NOT NULL COMMENT '\n推广投放名称',
  `budget_min` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '预算最小',
  `budget_max` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '预算最大',
  `publish_start_time` INT NOT NULL COMMENT '投放开始时间',
  `publish_end_time` INT NOT NULL COMMENT '\n投放结束时间',
  `plan_desc` TEXT NOT NULL COMMENT '投放需求描述',
  `comment` TEXT NULL COMMENT '\n订单备注',
  `cert_img_urls` TEXT NULL COMMENT '资质证明图片\njson格式',
  `deposit_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '定金金额（元）',
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '计划总金额（元）',
  `promot_discount` FLOAT NULL DEFAULT 0 COMMENT '促销折扣',
  `media_amount` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '媒体数目',
  `status` TINYINT(4) NOT NULL COMMENT '计划状态\n\n0 待提交\n1 执行中\n2 执行完',
  `pay_status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '支付状态\n\n0 未支付\n1 冻结定金\n',
  `pay_channel` TINYINT(4) NULL COMMENT '\n支付渠道\n1 沃米账户\n2 支付宝\n3 微信',
  `deposit_time` INT NULL COMMENT '\n定金支付时间',
  `create_time` INT NOT NULL COMMENT '新建时间',
  `last_update_time` INT NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `ad_owner_uuid_idx` (`ad_owner_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '计划表';


DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '\norder uuid',
  `order_code` VARCHAR(45) NOT NULL COMMENT '订单code',
  `plan_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `weixin_media_uuid` VARCHAR(45) NOT NULL COMMENT 'weixin uuid',
  `position_code` VARCHAR(10) NOT NULL COMMENT '发布文章位置\n',
  `pub_type` TINYINT(4) NULL COMMENT '发布类型\n\n1 直接投放\n2 原创约稿',
  `content_uuid` VARCHAR(45) NULL COMMENT '内容uuid',
  `is_fixed_price` VARCHAR(45) NULL COMMENT '是否是一口价\n1 是\n0 否',
  `price_min` DECIMAL(10,2) NULL COMMENT '价格区间最低价',
  `price_max` DECIMAL(10,2) NULL COMMENT '价格区间最高价',
  `position_content_conf` TEXT NOT NULL COMMENT '位置，推广内容\njson格式\n默认\n{\n\"s_1\":0,\n\"m_1\":0,\n\"m_2\":0,\n\"m_3\":0\n}\n\n{\n“s_1”:1,\n“m_1”:1,\n\"m_2\":0,\n\"m_3\":0\n}\n  表示多图文第一条已新建推广内容',
  `execute_time` INT NULL COMMENT '执行时间',
  `execute_price` DECIMAL(10,2) NULL COMMENT '执行价格',
  `status` TINYINT(4) NOT NULL COMMENT '',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  UNIQUE INDEX `order_code_UNIQUE` (`order_code` ASC)  COMMENT '',
  INDEX `plan_uuid_idx` (`plan_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '投放订单表';


DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_direct_content`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_direct_content` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT 'order uuid',
  `position_code` VARCHAR(10) NOT NULL COMMENT '位置code',
  `publish_start_time` INT NOT NULL COMMENT '投放开始时间',
  `publish_end_time` INT NOT NULL COMMENT '投放结束时间\n',
  `original_mp_url` VARCHAR(256) NULL COMMENT '文章内容的原文章URL',
  `title` VARCHAR(45) NOT NULL COMMENT '标题',
  `cover_img` VARCHAR(256) NULL COMMENT '封面图片',
  `cover_in_body` TINYINT(4) NULL DEFAULT 1 COMMENT '封面图片显示在正文中\n1 是\n0 否',
  `article_short_desc` TEXT NULL COMMENT '摘要',
  `article_content` TEXT NULL COMMENT '推广文章正文内容',
  `link_url` VARCHAR(256) NULL COMMENT '公众号文章左下角的原文链接',
  `cert_img_urls` TEXT NULL COMMENT '资质证明',
  `comment` TEXT NULL COMMENT '投放备注',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '直接投放的内容';


DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_arrange_content`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_arrange_content` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `position_code` VARCHAR(10) NOT NULL COMMENT '位置code',
  `publish_start_time` INT NOT NULL COMMENT '投放开始时间',
  `publish_end_time` INT NOT NULL COMMENT '投放结束时间',
  `content_type` TINYINT(4) NOT NULL COMMENT '创作类型',
  `requirement` TEXT NULL COMMENT '创作要求',
  `requirement_doc` VARCHAR(256) NULL COMMENT '需求文档',
  `comment` TEXT NULL COMMENT '投放备注',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '原创约稿的内容';


DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_feedback`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_feedback` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `content` TEXT NULL COMMENT '',
  `create_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '订单反馈信息';
    
    
DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_publish_result`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_publish_result` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `preview_url` VARCHAR(256) NOT NULL COMMENT '预览链接',
  `preview_screenshot` TEXT NULL COMMENT '预览截图（可多个）',
  `preview_comment` TEXT NULL COMMENT '预览备注',
  `publish_url` VARCHAR(256) NOT NULL COMMENT '',
  `publish_screenshot` TEXT NULL COMMENT '执行截图（可多个）',
  `publish_comment` TEXT NULL COMMENT '投放备注',
  `publish_effect_screenshot` TEXT NULL COMMENT '投放效果截图（可多个）',
  `publish_effect_comment` TEXT NULL COMMENT '投放效果备注',
  `preview_time` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '投放结果';


DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_arrange_outline`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_arrange_outline` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `outline_content` TEXT NOT NULL COMMENT '',
  `file_url` VARCHAR(256) NULL COMMENT '附件url',
  `comment` VARCHAR(45) NULL COMMENT '备注',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '原创约稿的大纲';

DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_order_track_log`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_order_track_log` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `order_uuid` VARCHAR(45) NOT NULL COMMENT '',
  `action_code` VARCHAR(45) NULL COMMENT '动作code\n\n11、确认订单\n21、提交大纲\n22、大纲确认\n31、自媒体主反馈\n32、广告主反馈\n41、提交预览\n42、预览确认\n51、提交投放\n52、投放确认\n61、提交效果\n62、效果确认',
  `action_name` VARCHAR(25) NOT NULL COMMENT '\n\n',
  `create_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '微信订单跟进log';

DROP TABLE IF EXISTS `dev-wom2`.`ad_weixin_direct_content_lib`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`ad_weixin_direct_content_lib` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `original_mp_url` VARCHAR(256) NULL COMMENT '文章内容的原文章URL',
  `title` VARCHAR(45) NOT NULL COMMENT '标题',
  `cover_img` VARCHAR(256) NULL COMMENT '封面图片',
  `cover_in_body` TINYINT(4) NULL DEFAULT 1 COMMENT '封面图片显示在正文中\n1 是\n0 否',
  `article_short_desc` TEXT NULL COMMENT '摘要',
  `article_content` TEXT NULL COMMENT '推广文章正文内容',
  `link_url` VARCHAR(256) NULL COMMENT '公众号文章左下角的原文链接',
  `cert_img_urls` TEXT NULL COMMENT '资质证明',
  `comment` VARCHAR(45) NULL COMMENT '投放备注',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '广告内容素材库';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_account`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_admin_account` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `auth_key` VARCHAR(256) NOT NULL COMMENT '',
  `login_name` VARCHAR(45) NOT NULL COMMENT '登录用户名',
  `login_password` VARCHAR(256) NOT NULL COMMENT '登录密码',
  `last_login_time` INT NOT NULL COMMENT '最后登录时间',
  `status` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '状态\n1 有效\n0 无效',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  UNIQUE INDEX `login_account_UNIQUE` (`login_name` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = 'WOM Admin账户表';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_account_auth_item`;
CREATE TABLE IF NOT EXISTS `mydb`.`wom_admin_account_auth_item` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(64) NOT NULL COMMENT '角色 or 权限',
  `cn_name` VARCHAR(64) NOT NULL COMMENT '角色 or 权限 中文名',
  `type` TINYINT(4) NOT NULL COMMENT '类型\n1 角色\n2 权限',
  `description` TEXT NULL COMMENT '描述',
  `rule_name` VARCHAR(64) NULL COMMENT '规则',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  INDEX `rule_name_idx` (`rule_name` ASC)  COMMENT ''
  )
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '角色（role）和权限（permission）表';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_account_auth_item_child`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_admin_account_auth_item_child` (
  `id` INT NOT NULL COMMENT '',
  `parent` VARCHAR(64) NOT NULL COMMENT '角色 or 权限',
  `child` VARCHAR(64) NOT NULL COMMENT '角色 or 权限',
  INDEX `parent_idx` (`parent` ASC)  COMMENT '',
  INDEX `child_idx` (`child` ASC)  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT ''
  )
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '角色和权限之间的关系';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_account_auth_rule`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_admin_account_auth_rule` (
  `name` VARCHAR(64) NOT NULL COMMENT '规则名',
  `cn_name` VARCHAR(64) NULL COMMENT '规则中文名称',
  `data` TEXT NULL COMMENT '执行脚本',
  `created_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`name`)  COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '定义规则';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_account_auth_assignment`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_admin_account_auth_assignment` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `account_uuid` VARCHAR(45) NOT NULL COMMENT '账户uuid',
  `auth_item` VARCHAR(64) NOT NULL COMMENT '角色 or 权限',
  `create_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  INDEX `auth_item_idx` (`account_uuid` ASC)  COMMENT '',
  INDEX `auth_item_idx1` (`auth_item` ASC)  COMMENT ''
  )
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '将角色（role）or 权限（permission） 分配给用户';

DROP TABLE IF EXISTS `dev-wom2`.`media_executor_assign`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`media_executor_assign` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `media_type` TINYINT(4) NOT NULL COMMENT '媒体类型',
  `executor_uuid` VARCHAR(45) NOT NULL COMMENT '媒介运营uuid',
  `media_uuid` VARCHAR(45) NOT NULL COMMENT 'media uuid',
  `assign_time` INT NOT NULL COMMENT '分配时间',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `bind_uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `media_execor_uuid_idx` (`executor_uuid` ASC)  COMMENT '',
  INDEX `media_uuid_idx` (`media_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '媒体分配给媒介运营人员';

DROP TABLE IF EXISTS `dev-wom2`.`wom_account`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_account` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `login_account` VARCHAR(45) NOT NULL COMMENT '',
  `login_password` VARCHAR(256) NOT NULL COMMENT '',
  `type` TINYINT(4) NOT NULL COMMENT '1 广告主\n2 供应商',
  `create_time` INT NOT NULL COMMENT '',
  `last_update_time` INT NOT NULL COMMENT '',
  `last_login_time` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '账号表（广告主/自媒体主）';

DROP TABLE IF EXISTS `dev-wom2`.`wom_admin_media_executor`;
CREATE TABLE IF NOT EXISTS `dev-wom2`.`wom_admin_media_executor` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `uuid` VARCHAR(45) NOT NULL COMMENT '',
  `name` VARCHAR(45) NOT NULL COMMENT '媒介人员姓名',
  `account_uuid` VARCHAR(45) NOT NULL COMMENT '账户uuid',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)  COMMENT '',
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC)  COMMENT '',
  INDEX `account_uuid_idx` (`account_uuid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARSET = utf8
COMMENT = '媒介运营人员表';