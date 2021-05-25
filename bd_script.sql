

#create database mr_solis;

create table mr_solis.contact (
    id int(11) AUTO_INCREMENT,
	name varchar(50) NULL,
	city varchar(50) NULL,
	state varchar(30) NULL,
	mail varchar(100) NULL,
	consumption varchar(50) NULL,
	company varchar(100) NULL,
	is_send_mail boolean NULL,
	created_at datetime NOT NULL,
    PRIMARY KEY (id)
);

