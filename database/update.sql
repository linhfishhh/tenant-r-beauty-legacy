alter table wa_salon_orders
	add amount_coin int null after reminder_id;

alter table wa_salon_orders
	add amount_money int null after amount_coin;

alter table wa_salon_orders
	add total int null after amount_money;

