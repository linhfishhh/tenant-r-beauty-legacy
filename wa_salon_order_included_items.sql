DROP TABLE IF EXISTS `wa_salon_order_included_items`;
CREATE TABLE IF NOT EXISTS `wa_salon_order_included_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `included_options_id` int(11) NOT NULL,
  `included_options_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `quatity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;