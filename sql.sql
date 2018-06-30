CREATE DATABASE districts
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_polish_ci;

CREATE TABLE city (
    city_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    city_name varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_polish_ci;

CREATE TABLE district (
    district_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    district_name varchar(50) NOT NULL,
    district_population int NOT NULL,
    district_area float NOT NULL,
    city_id int NOT NULL,

    CONSTRAINT district_city_fk
        FOREIGN KEY (city_id)
        REFERENCES city (city_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_polish_ci;
