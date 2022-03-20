# onlineStore
Online store project

MySQL configuration

mysql> DESCRIBE users;

+------------+--------------+------+-----+-------------------+-------------------+

| Field      | Type         | Null | Key | Default           | Extra             |

+------------+--------------+------+-----+-------------------+-------------------+

| id         | int          | NO   | PRI | NULL              | auto_increment    |

| username   | varchar(100) | NO   | UNI | NULL              |                   |

| password   | varchar(255) | NO   |     | NULL              |                   |

| datetime   | datetime     | YES  |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |

| email      | varchar(128) | YES  |     | NULL              |                   |

| confirm    | tinyint(1)   | YES  |     | NULL              |                   |

| confirmNum | int          | YES  |     | NULL              |                   |

+------------+--------------+------+-----+-------------------+-------------------+


mysql> DESCRIBE onlineshop_products;

+------------+---------------+------+-----+---------+----------------+

| Field      | Type          | Null | Key | Default | Extra          |

+------------+---------------+------+-----+---------+----------------+

| id         | int unsigned  | NO   | PRI | NULL    | auto_increment |

| product    | varchar(128)  | YES  |     | NULL    |                |

| short_desc | varchar(1024) | YES  |     | NULL    |                |

| long_desc  | varchar(3500) | YES  |     | NULL    |                |

| price      | varchar(10)   | NO   |     | 0       |                |

| image      | varchar(100)  | YES  |     | NULL    |                |

+------------+---------------+------+-----+---------+----------------+


mysql> DESCRIBE onlineshop_cart;

+----------+--------------+------+-----+---------+----------------+

| Field    | Type         | Null | Key | Default | Extra          |

+----------+--------------+------+-----+---------+----------------+

| id       | int unsigned | NO   | PRI | NULL    | auto_increment |

| user     | varchar(256) | YES  |     | NULL    |                |

| item     | varchar(512) | YES  |     | NULL    |                |

| quantity | int unsigned | YES  |     | NULL    |                |

| size     | varchar(8)   | YES  |     | NULL    |                |

| color    | varchar(24)  | YES  |     | NULL    |                |

+----------+--------------+------+-----+---------+----------------+
