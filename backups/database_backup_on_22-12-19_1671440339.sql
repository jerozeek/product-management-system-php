

CREATE TABLE `bill` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `customer_id` int(200) NOT NULL,
  `customer_name` varchar(200) DEFAULT NULL,
  `paid` int(100) NOT NULL,
  `due` int(100) NOT NULL,
  `total` int(200) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `employee` varchar(100) NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `bill_details` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `bill_id` int(200) NOT NULL,
  `product` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `profit` int(200) NOT NULL,
  `subtotal` int(200) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `category` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO category (id, name) VALUES ('1','food');


CREATE TABLE `config` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(1000) NOT NULL,
  `address` varchar(1000) NOT NULL DEFAULT 'no 54 aba owerri road, abia state',
  `logo` varchar(1000) NOT NULL,
  `status` int(1) NOT NULL,
  `return_sales` int(1) NOT NULL DEFAULT '0',
  `sale_expired` int(10) NOT NULL DEFAULT '0',
  `store_key` varchar(12) NOT NULL,
  `code` int(55) NOT NULL DEFAULT '55',
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_key` (`store_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO config (id, store_name, address, logo, status, return_sales, sale_expired, store_key, code) VALUES ('1','Links Supermarket','no 50 aba owerri road, abia state','1671440327.jpeg','1','1','0','234758930288','55');


CREATE TABLE `customer` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `balance_in` int(100) NOT NULL DEFAULT '0',
  `balance_out` int(100) NOT NULL DEFAULT '0',
  `created` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `expenses` (
  `expenses_id` int(100) NOT NULL AUTO_INCREMENT,
  `employee` varchar(1000) NOT NULL,
  `purpose` varchar(1000) NOT NULL,
  `amount` int(100) NOT NULL,
  `assigned_by` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`expenses_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(1000) NOT NULL DEFAULT '[{"product":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `customer` varchar(2000) NOT NULL DEFAULT '[{"customer":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `sales` varchar(1000) NOT NULL DEFAULT '[{"sales":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `employee` varchar(1000) NOT NULL DEFAULT '[{"employee":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `report` varchar(1000) NOT NULL DEFAULT '[{"product":"0","sales":"0","employee":"0","customer":"0"}]',
  `expired` varchar(1000) NOT NULL DEFAULT '[{"expire":"0"}]',
  `expenses` varchar(1000) NOT NULL DEFAULT '[{"expenses":"0","add":"0","view":"0","edit":"0","delete":"0"}]',
  `employee_id` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;


INSERT INTO permissions (id, product, customer, sales, employee, report, expired, expenses, employee_id) VALUES ('1','[{"product":"1","add":"1","view":"1","edit":"1","delete":"1"}]','[{"customer":"1","add":"1","view":"1","edit":"1","delete":"1"}]','[{"sales":"1","add":"1","view":"1","edit":"1","delete":"1"}]','[{"employee":"1","add":"1","view":"1","edit":"1","delete":"1"}]','[{"employee":"1","product":"1","sales":"1","customer":"1"}]','[{"expire":"1"}]','[{"expenses":"1","add":"1","view":"1","edit":"1","delete":"1"}]','1');


CREATE TABLE `product` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `cost_price` float NOT NULL,
  `selling_price` float NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `expiry` date NOT NULL,
  `barcode` int(200) NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(200) NOT NULL DEFAULT 'cashier',
  `last_login` date NOT NULL,
  `deleted` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO users (id, username, password, role, last_login, deleted) VALUES ('1','admin','e10adc3949ba59abbe56e057f20f883e','admin','2022-12-19','0');
