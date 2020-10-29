CREATE TABLE user(
  id INTEGER AUTO_INCREMENT,
  user_name VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME,
  PRIMARY KEY(id),
  UNIQUE KEY user_name_index(user_name)
);
CREATE TABLE status(
  id INTEGER AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  body VARCHAR(255),
  created_at DATETIME,
  PRIMARY KEY(id),
  UNIQUE KEY user_id_index(user_id),
  FOREIGN KEY (user_id) REFERENCES user(id)
);
CREATE TABLE following(
  user_id INTEGER,
  following_id INTEGER,
  PRIMARY KEY(user_id, following_id),
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (following_id) REFERENCES user(id)
);
