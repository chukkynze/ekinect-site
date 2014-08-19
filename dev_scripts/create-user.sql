-- Author                   :   Chukky Nze
-- Company                  :   Freelife.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the user
-- Database                 :   *
-- Table                    :   *

START TRANSACTION;

CREATE USER 'freelife_dba'@'localhost' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON * . * TO 'freelife_dba'@'localhost';

CREATE USER 'freelife_dba'@'*' IDENTIFIED BY 'nL]Eoa!g,[4*';
GRANT ALL PRIVILEGES ON * . * TO 'freelife_dba'@'*';

COMMIT;

-- EOF