-- Author                   :   Chukky Nze
-- Company                  :   AkadaLMS.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the user
-- Database                 :   *
-- Table                    :   *

START TRANSACTION;

CREATE USER 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_db.* TO 'freelife_dba'@'localhost';

CREATE USER 'freelife_dba'@'*' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_db.* TO 'freelife_dba'@'*';

CREATE USER 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_utils.* TO 'freelife_dba'@'localhost';

CREATE USER 'freelife_dba'@'*' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_utils.* TO 'freelife_dba'@'*';

CREATE USER 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_queue.* TO 'freelife_dba'@'localhost';

CREATE USER 'freelife_dba'@'*' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON freelife_queue.* TO 'freelife_dba'@'*';

COMMIT;

-- EOF