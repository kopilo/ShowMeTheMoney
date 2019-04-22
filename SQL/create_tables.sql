CREATE TABLE IF NOT EXISTS transactions(
	id INT AUTO_INCREMENT,
	dateCleared DATE,
	detail VARCHAR(255),
	amount NUMERIC(19,4),
	balance NUMERIC(19,4),
	tag VARCHAR(30),
	UNIQUE KEY (dateCleared,detail,amount,balance),
	PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS keyword_tag (
	keyword VARCHAR (30),
	tag VARCHAR (30),
	UNIQUE KEY (keyword)
);
