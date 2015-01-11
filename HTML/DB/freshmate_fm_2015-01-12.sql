# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.38)
# Database: freshmate_fm
# Generation Time: 2015-01-11 20:34:57 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table b_sale_delivery
# ------------------------------------------------------------

LOCK TABLES `b_sale_delivery` WRITE;
/*!40000 ALTER TABLE `b_sale_delivery` DISABLE KEYS */;

INSERT INTO `b_sale_delivery` (`ID`, `NAME`, `LID`, `PERIOD_FROM`, `PERIOD_TO`, `PERIOD_TYPE`, `WEIGHT_FROM`, `WEIGHT_TO`, `ORDER_PRICE_FROM`, `ORDER_PRICE_TO`, `ORDER_CURRENCY`, `ACTIVE`, `PRICE`, `CURRENCY`, `SORT`, `DESCRIPTION`, `LOGOTIP`, `STORE`)
VALUES
	(2,'Самовывоз','s1',0,0,'D',0,0,0.00,100000000.00,'RUB','Y',0.00,'RUB',50,'',NULL,'a:1:{i:0;s:1:\"3\";}');

/*!40000 ALTER TABLE `b_sale_delivery` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_delivery_handler
# ------------------------------------------------------------

LOCK TABLES `b_sale_delivery_handler` WRITE;
/*!40000 ALTER TABLE `b_sale_delivery_handler` DISABLE KEYS */;

INSERT INTO `b_sale_delivery_handler` (`ID`, `LID`, `ACTIVE`, `HID`, `NAME`, `SORT`, `DESCRIPTION`, `HANDLER`, `SETTINGS`, `PROFILES`, `TAX_RATE`, `LOGOTIP`, `BASE_CURRENCY`)
VALUES
	(1,'','Y','russianpost','Почта России',100,'Доставка почтой','/bitrix/modules/sale/ru/delivery/delivery_russianpost.php','36','a:2:{s:6:\"ground\";a:9:{s:5:\"TITLE\";s:33:\"наземная доставка\";s:11:\"DESCRIPTION\";s:0:\"\";s:19:\"RESTRICTIONS_WEIGHT\";a:2:{i:0;s:4:\"0.00\";i:1;s:4:\"0.00\";}s:16:\"RESTRICTIONS_SUM\";a:2:{i:0;d:0;i:1;d:0;}s:6:\"ACTIVE\";s:1:\"Y\";s:8:\"TAX_RATE\";s:1:\"0\";s:27:\"RESTRICTIONS_DIMENSIONS_SUM\";s:1:\"0\";s:21:\"RESTRICTIONS_MAX_SIZE\";s:1:\"0\";s:23:\"RESTRICTIONS_DIMENSIONS\";a:3:{i:0;s:1:\"0\";i:1;s:1:\"0\";i:2;s:1:\"0\";}}s:4:\"avia\";a:9:{s:5:\"TITLE\";s:18:\"авиапочта\";s:11:\"DESCRIPTION\";s:0:\"\";s:19:\"RESTRICTIONS_WEIGHT\";a:2:{i:0;s:4:\"0.00\";i:1;s:4:\"0.00\";}s:16:\"RESTRICTIONS_SUM\";a:2:{i:0;d:0;i:1;d:0;}s:6:\"ACTIVE\";s:1:\"N\";s:8:\"TAX_RATE\";s:1:\"0\";s:27:\"RESTRICTIONS_DIMENSIONS_SUM\";s:1:\"0\";s:21:\"RESTRICTIONS_MAX_SIZE\";s:1:\"0\";s:23:\"RESTRICTIONS_DIMENSIONS\";a:3:{i:0;s:1:\"0\";i:1;s:1:\"0\";i:2;s:1:\"0\";}}}',0,7346,'RUB'),
	(2,'','Y','simple','Доставка курьером',100,'','/bitrix/modules/sale/delivery/delivery_simple.php','a:1:{s:7:\"price_2\";d:300;}','a:1:{s:6:\"simple\";a:9:{s:5:\"TITLE\";s:16:\"доставка\";s:11:\"DESCRIPTION\";s:6:\"<br />\";s:19:\"RESTRICTIONS_WEIGHT\";a:2:{i:0;s:4:\"0.00\";i:1;s:4:\"0.00\";}s:16:\"RESTRICTIONS_SUM\";a:2:{i:0;d:0;i:1;d:0;}s:6:\"ACTIVE\";s:1:\"Y\";s:8:\"TAX_RATE\";s:1:\"0\";s:27:\"RESTRICTIONS_DIMENSIONS_SUM\";s:1:\"0\";s:21:\"RESTRICTIONS_MAX_SIZE\";s:1:\"0\";s:23:\"RESTRICTIONS_DIMENSIONS\";a:3:{i:0;s:1:\"0\";i:1;s:1:\"0\";i:2;s:1:\"0\";}}}',0,7347,'RUB');

/*!40000 ALTER TABLE `b_sale_delivery_handler` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_delivery2location
# ------------------------------------------------------------

LOCK TABLES `b_sale_delivery2location` WRITE;
/*!40000 ALTER TABLE `b_sale_delivery2location` DISABLE KEYS */;

INSERT INTO `b_sale_delivery2location` (`DELIVERY_ID`, `LOCATION_TYPE`, `LOCATION_CODE`)
VALUES
	(2,'G','moscow');

/*!40000 ALTER TABLE `b_sale_delivery2location` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_delivery2paysystem
# ------------------------------------------------------------

LOCK TABLES `b_sale_delivery2paysystem` WRITE;
/*!40000 ALTER TABLE `b_sale_delivery2paysystem` DISABLE KEYS */;

INSERT INTO `b_sale_delivery2paysystem` (`DELIVERY_ID`, `DELIVERY_PROFILE_ID`, `PAYSYSTEM_ID`)
VALUES
	('simple','simple',2),
	('simple','simple',3),
	('russianpost','ground',2),
	('russianpost','ground',3),
	('russianpost','avia',2),
	('russianpost','avia',3),
	('simple','simple',1),
	('2',NULL,2),
	('2',NULL,1),
	('2',NULL,3);

/*!40000 ALTER TABLE `b_sale_delivery2paysystem` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order
# ------------------------------------------------------------

LOCK TABLES `b_sale_order` WRITE;
/*!40000 ALTER TABLE `b_sale_order` DISABLE KEYS */;

INSERT INTO `b_sale_order` (`ID`, `LID`, `PERSON_TYPE_ID`, `PAYED`, `DATE_PAYED`, `EMP_PAYED_ID`, `CANCELED`, `DATE_CANCELED`, `EMP_CANCELED_ID`, `REASON_CANCELED`, `STATUS_ID`, `DATE_STATUS`, `EMP_STATUS_ID`, `PRICE_DELIVERY`, `ALLOW_DELIVERY`, `DATE_ALLOW_DELIVERY`, `EMP_ALLOW_DELIVERY_ID`, `DEDUCTED`, `DATE_DEDUCTED`, `EMP_DEDUCTED_ID`, `REASON_UNDO_DEDUCTED`, `MARKED`, `DATE_MARKED`, `EMP_MARKED_ID`, `REASON_MARKED`, `RESERVED`, `PRICE`, `CURRENCY`, `DISCOUNT_VALUE`, `USER_ID`, `PAY_SYSTEM_ID`, `DELIVERY_ID`, `DATE_INSERT`, `DATE_UPDATE`, `USER_DESCRIPTION`, `ADDITIONAL_INFO`, `PS_STATUS`, `PS_STATUS_CODE`, `PS_STATUS_DESCRIPTION`, `PS_STATUS_MESSAGE`, `PS_SUM`, `PS_CURRENCY`, `PS_RESPONSE_DATE`, `COMMENTS`, `TAX_VALUE`, `STAT_GID`, `SUM_PAID`, `RECURRING_ID`, `PAY_VOUCHER_NUM`, `PAY_VOUCHER_DATE`, `LOCKED_BY`, `DATE_LOCK`, `RECOUNT_FLAG`, `AFFILIATE_ID`, `DELIVERY_DOC_NUM`, `DELIVERY_DOC_DATE`, `UPDATED_1C`, `STORE_ID`, `ORDER_TOPIC`, `RESPONSIBLE_ID`, `DATE_PAY_BEFORE`, `DATE_BILL`, `ACCOUNT_NUMBER`, `TRACKING_NUMBER`, `XML_ID`, `ID_1C`, `VERSION_1C`, `VERSION`, `EXTERNAL_ORDER`)
VALUES
	(1,'s1',1,'N',NULL,NULL,'N',NULL,NULL,NULL,'N','2015-01-10 23:39:26',NULL,0.00,'N',NULL,NULL,'N',NULL,NULL,NULL,'N',NULL,NULL,NULL,'Y',7722.00,'RUB',0.00,1,2,NULL,'2015-01-10 23:39:26','2015-01-10 23:39:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'BITRIX_SM.MTQxLjQuTjAuLi5zMQ==',0.00,NULL,NULL,NULL,NULL,NULL,'Y',NULL,NULL,NULL,'N',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,2,'N');

/*!40000 ALTER TABLE `b_sale_order` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_change
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_change` WRITE;
/*!40000 ALTER TABLE `b_sale_order_change` DISABLE KEYS */;

INSERT INTO `b_sale_order_change` (`ID`, `ORDER_ID`, `TYPE`, `DATA`, `DATE_CREATE`, `DATE_MODIFY`, `USER_ID`)
VALUES
	(1,1,'ORDER_ADDED','a:0:{}','2015-01-10 23:39:26','2015-01-10 23:39:26',1);

/*!40000 ALTER TABLE `b_sale_order_change` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_delivery
# ------------------------------------------------------------



# Dump of table b_sale_order_flags2group
# ------------------------------------------------------------



# Dump of table b_sale_order_history
# ------------------------------------------------------------



# Dump of table b_sale_order_processing
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_processing` WRITE;
/*!40000 ALTER TABLE `b_sale_order_processing` DISABLE KEYS */;

INSERT INTO `b_sale_order_processing` (`ORDER_ID`, `PRODUCTS_ADDED`, `PRODUCTS_REMOVED`)
VALUES
	(1,'Y','Y');

/*!40000 ALTER TABLE `b_sale_order_processing` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_props
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_props` WRITE;
/*!40000 ALTER TABLE `b_sale_order_props` DISABLE KEYS */;

INSERT INTO `b_sale_order_props` (`ID`, `PERSON_TYPE_ID`, `NAME`, `TYPE`, `REQUIED`, `DEFAULT_VALUE`, `SORT`, `USER_PROPS`, `IS_LOCATION`, `PROPS_GROUP_ID`, `SIZE1`, `SIZE2`, `DESCRIPTION`, `IS_EMAIL`, `IS_PROFILE_NAME`, `IS_PAYER`, `IS_LOCATION4TAX`, `IS_FILTERED`, `CODE`, `IS_ZIP`, `IS_PHONE`, `ACTIVE`, `UTIL`, `INPUT_FIELD_LOCATION`, `MULTIPLE`)
VALUES
	(1,1,'Ваше имя','TEXT','Y','',100,'Y','N',1,0,0,'','N','Y','Y','N','Y','name','N','N','Y','N',0,'N'),
	(2,1,'Адрес','LOCATION','Y','',100,'N','Y',2,0,0,'','N','N','N','N','Y','address','N','N','Y','N',0,'N'),
	(3,1,'Телефон','TEXT','Y','',100,'Y','N',1,0,0,'','N','N','N','N','Y','phone','N','N','Y','N',0,'N'),
	(4,1,'Эл. почта','TEXT','Y','',100,'Y','N',1,0,0,'','Y','N','N','N','Y','email','N','N','Y','N',0,'N'),
	(5,1,'Улица','TEXT','Y','',200,'N','N',2,0,0,'','N','N','N','N','Y','street','N','N','Y','N',0,'N'),
	(6,1,'Дом','TEXT','Y','',300,'N','N',2,2,0,'','N','N','N','N','Y','house','N','N','Y','N',0,'N'),
	(7,1,'Корп.','TEXT','N','',400,'N','N',2,2,0,'','N','N','N','N','Y','corpus','N','N','Y','N',0,'N'),
	(8,1,'СТР.','TEXT','N','',400,'N','N',2,2,0,'','N','N','N','N','Y','building','N','N','Y','N',0,'N'),
	(9,1,'кв./оф.','TEXT','Y','',500,'N','N',2,2,0,'','N','N','N','N','Y','flat','N','N','Y','N',0,'N'),
	(10,1,'Этаж','TEXT','N','',700,'N','N',2,2,0,'','N','N','N','N','Y','stage','N','N','Y','N',0,'N'),
	(11,1,'Желаемая дата доставки','TEXT','N','',800,'N','N',2,5,0,'','N','N','N','N','Y','date','N','N','Y','N',0,'N'),
	(12,1,'Желаемое время доставки','TEXT','N','',900,'N','N',2,5,0,'','N','N','N','N','Y','time','N','N','Y','N',0,'N'),
	(13,1,'Индекс','TEXT','N','',50,'N','N',2,0,0,'','N','N','N','N','Y','index','Y','N','Y','N',0,'N');

/*!40000 ALTER TABLE `b_sale_order_props` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_props_group
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_props_group` WRITE;
/*!40000 ALTER TABLE `b_sale_order_props_group` DISABLE KEYS */;

INSERT INTO `b_sale_order_props_group` (`ID`, `PERSON_TYPE_ID`, `NAME`, `SORT`)
VALUES
	(1,1,'Информация о покупателе',0),
	(2,1,'Адрес доставки',0);

/*!40000 ALTER TABLE `b_sale_order_props_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_props_relation
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_props_relation` WRITE;
/*!40000 ALTER TABLE `b_sale_order_props_relation` DISABLE KEYS */;

INSERT INTO `b_sale_order_props_relation` (`PROPERTY_ID`, `ENTITY_ID`, `ENTITY_TYPE`)
VALUES
	(5,'russianpost:ground','D'),
	(5,'simple:simple','D'),
	(6,'russianpost:ground','D'),
	(6,'simple:simple','D'),
	(7,'russianpost:ground','D'),
	(7,'simple:simple','D'),
	(8,'russianpost:ground','D'),
	(8,'simple:simple','D'),
	(9,'russianpost:ground','D'),
	(9,'simple:simple','D'),
	(10,'simple:simple','D'),
	(11,'simple:simple','D'),
	(12,'simple:simple','D');

/*!40000 ALTER TABLE `b_sale_order_props_relation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_props_value
# ------------------------------------------------------------

LOCK TABLES `b_sale_order_props_value` WRITE;
/*!40000 ALTER TABLE `b_sale_order_props_value` DISABLE KEYS */;

INSERT INTO `b_sale_order_props_value` (`ID`, `ORDER_ID`, `ORDER_PROPS_ID`, `NAME`, `VALUE`, `CODE`)
VALUES
	(1,1,1,'Ваше имя','<Без имени>','name');

/*!40000 ALTER TABLE `b_sale_order_props_value` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_order_props_variant
# ------------------------------------------------------------



# Dump of table b_sale_order_tax
# ------------------------------------------------------------



# Dump of table b_sale_pay_system
# ------------------------------------------------------------

LOCK TABLES `b_sale_pay_system` WRITE;
/*!40000 ALTER TABLE `b_sale_pay_system` DISABLE KEYS */;

INSERT INTO `b_sale_pay_system` (`ID`, `LID`, `CURRENCY`, `NAME`, `ACTIVE`, `SORT`, `DESCRIPTION`)
VALUES
	(1,NULL,NULL,'Наличные','Y',100,'\r\n\r\n'),
	(2,NULL,NULL,'Банковской картой Visa/Mastercard','Y',100,''),
	(3,NULL,NULL,'Сбербанк ОнЛ@йн','Y',100,'');

/*!40000 ALTER TABLE `b_sale_pay_system` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_pay_system_action
# ------------------------------------------------------------

LOCK TABLES `b_sale_pay_system_action` WRITE;
/*!40000 ALTER TABLE `b_sale_pay_system_action` DISABLE KEYS */;

INSERT INTO `b_sale_pay_system_action` (`ID`, `PAY_SYSTEM_ID`, `PERSON_TYPE_ID`, `NAME`, `ACTION_FILE`, `RESULT_FILE`, `NEW_WINDOW`, `PARAMS`, `TARIF`, `HAVE_PAYMENT`, `HAVE_ACTION`, `HAVE_RESULT`, `HAVE_PREPAY`, `HAVE_RESULT_RECEIVE`, `ENCODING`, `LOGOTIP`)
VALUES
	(1,2,1,'Банковской картой Visa/Mastercard','/bitrix/modules/sale/payment/assist',NULL,'N','a:23:{s:8:\"SHOP_IDP\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:10:\"SHOP_LOGIN\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:13:\"SHOP_PASSWORD\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:17:\"SHOP_SECRET_WORLD\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:10:\"SHOULD_PAY\";a:2:{s:4:\"TYPE\";s:5:\"ORDER\";s:5:\"VALUE\";s:10:\"SHOULD_PAY\";}s:8:\"CURRENCY\";a:2:{s:4:\"TYPE\";s:5:\"ORDER\";s:5:\"VALUE\";s:8:\"CURRENCY\";}s:8:\"ORDER_ID\";a:2:{s:4:\"TYPE\";s:5:\"ORDER\";s:5:\"VALUE\";s:2:\"ID\";}s:11:\"DATE_INSERT\";a:2:{s:4:\"TYPE\";s:5:\"ORDER\";s:5:\"VALUE\";s:11:\"DATE_INSERT\";}s:11:\"SUCCESS_URL\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:47:\"http://www.yoursite.com/sale/payment_result.php\";}s:8:\"FAIL_URL\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:47:\"http://www.yoursite.com/sale/payment_failed.php\";}s:10:\"FIRST_NAME\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:11:\"MIDDLE_NAME\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:9:\"LAST_NAME\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:5:\"EMAIL\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:7:\"ADDRESS\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:5:\"PHONE\";a:2:{s:4:\"TYPE\";s:8:\"PROPERTY\";s:5:\"VALUE\";N;}s:19:\"PAYMENT_CardPayment\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"1\";}s:17:\"PAYMENT_YMPayment\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"1\";}s:23:\"PAYMENT_WebMoneyPayment\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"1\";}s:19:\"PAYMENT_QIWIPayment\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"1\";}s:25:\"PAYMENT_AssistIDCCPayment\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"1\";}s:7:\"AUTOPAY\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:1:\"N\";}s:4:\"DEMO\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:5:\"AS000\";}}',NULL,'Y','N','Y','N','Y',NULL,7348),
	(2,1,1,'Наличные','/bitrix/modules/sale/payment/cash',NULL,'N','a:0:{}',NULL,'Y','N','N','N','N','utf-8',7349),
	(3,3,1,'Сбербанк ОнЛ@йн','/bitrix/modules/sale/payment/sberbank_new',NULL,'N','a:16:{s:12:\"COMPANY_NAME\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:3:\"INN\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:3:\"KPP\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:18:\"SETTLEMENT_ACCOUNT\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:9:\"BANK_NAME\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:8:\"BANK_BIC\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:16:\"BANK_COR_ACCOUNT\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:8:\"ORDER_ID\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:11:\"DATE_INSERT\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:20:\"PAYER_CONTACT_PERSON\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:14:\"PAYER_ZIP_CODE\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:13:\"PAYER_COUNTRY\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:12:\"PAYER_REGION\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:10:\"PAYER_CITY\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:18:\"PAYER_ADDRESS_FACT\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}s:10:\"SHOULD_PAY\";a:2:{s:4:\"TYPE\";s:0:\"\";s:5:\"VALUE\";s:0:\"\";}}',NULL,'Y','N','N','N','N',NULL,7350);

/*!40000 ALTER TABLE `b_sale_pay_system_action` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_person_type_site
# ------------------------------------------------------------

LOCK TABLES `b_sale_person_type_site` WRITE;
/*!40000 ALTER TABLE `b_sale_person_type_site` DISABLE KEYS */;

INSERT INTO `b_sale_person_type_site` (`PERSON_TYPE_ID`, `SITE_ID`)
VALUES
	(1,'s1');

/*!40000 ALTER TABLE `b_sale_person_type_site` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table b_sale_product2product
# ------------------------------------------------------------




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
